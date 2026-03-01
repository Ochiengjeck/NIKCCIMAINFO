<?php

namespace App\Livewire\Admin;

use App\Models\Deal;
use App\Models\Event;
use App\Models\FinancialTransaction;
use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\Ntb;
use App\Models\TradeLead;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class Dashboard extends Component
{
    // Membership
    public int $totalMembers = 0;

    public int $activeMembers = 0;

    public int $pendingApplications = 0;

    public int $newMembersThisMonth = 0;

    // Finance
    public float $revenueThisMonth = 0;

    public int $paidTransactionsThisMonth = 0;

    // Trade
    public int $activeTradeLeads = 0;

    public int $dealsInPipeline = 0;

    public float $dealPipelineValue = 0;

    // Policy
    public int $openNtbs = 0;

    public int $escalatedNtbs = 0;

    // Events
    public int $upcomingEventsCount = 0;

    public int $registrationsThisMonth = 0;

    // Collections for list sections
    public $recentApplications = [];

    public $upcomingEventsList = [];

    public $recentActivity = [];

    public function mount(): void
    {
        $user = Auth::user();

        if ($user->can('members.view')) {
            $this->totalMembers = Member::forChapter()->count();
            $this->activeMembers = Member::forChapter()->where('status', 'active')->count();
            $this->pendingApplications = MembershipApplication::forChapter()
                ->whereIn('status', ['pending', 'under-review'])->count();
            $this->newMembersThisMonth = Member::forChapter()
                ->whereMonth('joined_at', now()->month)
                ->whereYear('joined_at', now()->year)->count();

            $this->recentApplications = MembershipApplication::forChapter()
                ->with('category')
                ->whereIn('status', ['pending', 'under-review'])
                ->latest('submitted_at')
                ->limit(5)
                ->get();
        }

        if ($user->can('finance.view')) {
            $this->revenueThisMonth = (float) FinancialTransaction::forChapter()
                ->where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount');
            $this->paidTransactionsThisMonth = FinancialTransaction::forChapter()
                ->where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->count();
        }

        if ($user->can('trade.view')) {
            $this->activeTradeLeads = TradeLead::forChapter()->whereIn('status', ['active', 'open'])->count();
            $this->dealsInPipeline = Deal::forChapter()->whereNotIn('stage', ['completed', 'cancelled'])->count();
            $this->dealPipelineValue = (float) Deal::forChapter()->whereNotIn('stage', ['completed', 'cancelled'])->sum('value_usd');
        }

        if ($user->can('policy.view')) {
            $this->openNtbs = Ntb::forChapter()->whereNotIn('status', ['resolved', 'closed'])->count();
            $this->escalatedNtbs = Ntb::forChapter()->where('status', 'escalated')->count();
        }

        if ($user->can('events.view')) {
            $this->upcomingEventsCount = Event::forChapter()
                ->where('starts_at', '>', now())
                ->whereIn('status', ['published', 'open'])->count();

            $this->registrationsThisMonth = Event::forChapter()
                ->withCount(['registrations' => fn ($q) => $q->whereMonth('created_at', now()->month)])
                ->get()
                ->sum('registrations_count');

            $this->upcomingEventsList = Event::forChapter()
                ->where('starts_at', '>', now())
                ->whereIn('status', ['published', 'open'])
                ->orderBy('starts_at')
                ->limit(4)
                ->get(['id', 'title', 'type', 'starts_at', 'venue', 'max_capacity']);
        }

        if ($user->can('audit.view')) {
            $this->recentActivity = Activity::with(['causer', 'subject'])
                ->latest()
                ->limit(8)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}
