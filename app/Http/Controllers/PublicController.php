<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Chapter;
use App\Models\CmsPage;
use App\Models\Corridor;
use App\Models\Document;
use App\Models\Event;
use App\Models\Investor;
use App\Models\LeadershipProfile;
use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\Ntb;
use App\Models\TradeLead;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        $page = CmsPage::where('slug', 'homepage')->first();
        $sectorsPage = CmsPage::where('slug', 'sectors')->first();
        $latestNews = BlogPost::published()->with('category')->latest('published_at')->take(3)->get();
        $upcomingEvents = Event::public()->where('starts_at', '>=', now())->orderBy('starts_at')->limit(3)->get();

        $stats = [
            'members' => Member::where('status', 'active')->count(),
            'leads' => TradeLead::where('status', 'active')->count(),
            'corridors' => Corridor::count(),
            'ntbs' => Ntb::where('status', 'resolved')->count(),
        ];

        $categories = MembershipCategory::where('is_active', true)->orderBy('sort_order')->get();
        $leadership = LeadershipProfile::where('is_active', true)->orderBy('sort_order')->take(4)->get();
        $grouped = app(\App\Services\SettingsService::class)->membershipGroupByType();

        return view('public.home', compact('page', 'sectorsPage', 'latestNews', 'upcomingEvents', 'stats', 'categories', 'leadership', 'grouped'));
    }

    public function about(): View
    {
        $page = CmsPage::where('slug', 'about')->first();

        return view('public.about', compact('page'));
    }

    public function blog(): View
    {
        $articles = BlogPost::published()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(9);

        return view('public.blog.index', array_merge(
            ['articles' => $articles, 'heading' => 'Blog', 'activeCategory' => null, 'activeTag' => null],
            $this->blogSidebar(),
        ));
    }

    public function blogShow(string $slug): View
    {
        $article = BlogPost::published()
            ->with(['category', 'author', 'tags', 'approvedComments'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.blog.show', compact('article'));
    }

    public function blogCategory(string $slug): View
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();

        $articles = BlogPost::published()
            ->with(['category', 'author'])
            ->where('blog_category_id', $category->id)
            ->latest('published_at')
            ->paginate(9);

        return view('public.blog.index', array_merge(
            ['articles' => $articles, 'heading' => $category->name, 'activeCategory' => $category, 'activeTag' => null],
            $this->blogSidebar(),
        ));
    }

    public function blogTag(string $slug): View
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();

        $articles = $tag->posts()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(9);

        return view('public.blog.index', array_merge(
            ['articles' => $articles, 'heading' => '#'.$tag->name, 'activeCategory' => null, 'activeTag' => $tag],
            $this->blogSidebar(),
        ));
    }

    public function blogFeed(): Response
    {
        $articles = BlogPost::published()
            ->with('category')
            ->latest('published_at')
            ->take(30)
            ->get();

        return response()
            ->view('public.blog.feed', ['articles' => $articles])
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }

    /** Shared sidebar data for blog listing pages. */
    private function blogSidebar(): array
    {
        return [
            'categories' => BlogCategory::withCount('publishedPosts')
                ->orderBy('sort_order')->orderBy('name')->get()
                ->filter(fn ($c) => $c->published_posts_count > 0)->values(),
            'tags' => BlogTag::whereHas('posts', fn ($q) => $q->where('status', 'published')->whereNotNull('published_at'))
                ->orderBy('name')->take(30)->get(),
            'recent' => BlogPost::published()->latest('published_at')->take(5)->get(['id', 'title', 'slug', 'published_at']),
        ];
    }

    public function policyBriefShow(\App\Models\PolicyBrief $brief): View
    {
        abort_unless($brief->status === 'published' && $brief->published_at, 404);

        $brief->load(['file', 'author']);

        return view('public.policy-brief', compact('brief'));
    }

    public function leadership(): View
    {
        $profiles = LeadershipProfile::with('chapter')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('public.leadership', compact('profiles'));
    }

    public function downloads(): View
    {
        $files = Document::public()
            ->latest()
            ->paginate(20);

        return view('public.downloads', compact('files'));
    }

    public function pillars(): View
    {
        $page = CmsPage::where('slug', 'pillars-overview')->first();

        return view('public.pillars', compact('page'));
    }

    public function pillarShow(string $slug): View
    {
        $map = [
            'executive' => 'pillar-executive',
            'trade' => 'pillar-trade',
            'policy' => 'pillar-policy',
            'admin' => 'pillar-admin',
        ];

        abort_unless(isset($map[$slug]), 404);

        $page = CmsPage::where('slug', $map[$slug])->first();
        $pillarSlug = $slug;

        return view('public.pillar-show', compact('page', 'pillarSlug'));
    }

    public function trade(): View
    {
        $page = CmsPage::where('slug', 'trade-investment')->first();
        $leads = TradeLead::where('status', 'active')->with('sector')->latest()->paginate(12);
        $investors = Investor::where('status', 'active')->with('sector')->get();

        return view('public.trade', compact('page', 'leads', 'investors'));
    }

    public function membership(): View
    {
        $page = CmsPage::where('slug', 'membership')->first();
        $categories = MembershipCategory::where('is_active', true)->orderBy('sort_order')->get();
        $grouped = app(\App\Services\SettingsService::class)->membershipGroupByType();

        return view('public.membership', compact('page', 'categories', 'grouped'));
    }

    public function membershipApply(): View
    {
        $categories = MembershipCategory::where('is_active', true)->orderBy('sort_order')->get();

        return view('public.membership-apply', compact('categories'));
    }

    public function events(): View
    {
        $page = CmsPage::where('slug', 'events-missions')->first();
        $upcoming = Event::public()->where('starts_at', '>=', now())->orderBy('starts_at')->paginate(9);
        $past = Event::public()->where('starts_at', '<', now())->orderByDesc('starts_at')->limit(6)->get();

        return view('public.events.index', compact('page', 'upcoming', 'past'));
    }

    public function eventShow(int $id): View
    {
        $event = Event::public()->with('organizer')->findOrFail($id);

        return view('public.events.show', compact('event'));
    }

    public function chapterNigeria(): View
    {
        $page = CmsPage::where('slug', 'chapter-nigeria')->first();
        $chapter = Chapter::where('code', 'NGA')->orWhere('name', 'like', '%Nigeria%')->first();
        $profiles = $chapter
            ? LeadershipProfile::where('chapter_id', $chapter->id)->where('is_active', true)->orderBy('sort_order')->get()
            : collect();
        $events = $chapter
            ? Event::public()->where('chapter_id', $chapter->id)->where('starts_at', '>=', now())->orderBy('starts_at')->limit(3)->get()
            : collect();

        return view('public.chapters.nigeria', compact('page', 'chapter', 'profiles', 'events'));
    }

    public function chapterKenya(): View
    {
        $page = CmsPage::where('slug', 'chapter-kenya')->first();
        $chapter = Chapter::where('code', 'KEN')->orWhere('name', 'like', '%Kenya%')->first();
        $profiles = $chapter
            ? LeadershipProfile::where('chapter_id', $chapter->id)->where('is_active', true)->orderBy('sort_order')->get()
            : collect();
        $events = $chapter
            ? Event::public()->where('chapter_id', $chapter->id)->where('starts_at', '>=', now())->orderBy('starts_at')->limit(3)->get()
            : collect();

        return view('public.chapters.kenya', compact('page', 'chapter', 'profiles', 'events'));
    }

    public function contact(): View
    {
        $page = CmsPage::where('slug', 'contact')->first();

        return view('public.contact', compact('page'));
    }
}
