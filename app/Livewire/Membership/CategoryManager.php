<?php

namespace App\Livewire\Membership;

use App\Models\MembershipCategory;
use App\Services\SettingsService;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $slug = '';

    public string $description = '';

    // Flat pricing (used when grouping is off)
    public string $fee_usd = '';

    public string $fee_ngn = '';

    // Per-group pricing (used when grouping is on)
    public bool $corporate_enabled = false;

    public string $corporate_fee_usd = '';

    public string $corporate_fee_ngn = '';

    public bool $individual_enabled = false;

    public string $individual_fee_usd = '';

    public string $individual_fee_ngn = '';

    public bool $is_active = true;

    public int $sort_order = 0;

    public bool $grouped = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:membership_categories,slug,'.($this->editingId ?? 'NULL'),
            'description' => 'nullable|string',
            'fee_usd' => 'nullable|numeric|min:0',
            'fee_ngn' => 'nullable|numeric|min:0',
            'corporate_enabled' => 'boolean',
            'corporate_fee_usd' => 'nullable|numeric|min:0',
            'corporate_fee_ngn' => 'nullable|numeric|min:0',
            'individual_enabled' => 'boolean',
            'individual_fee_usd' => 'nullable|numeric|min:0',
            'individual_fee_ngn' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    public function mount(SettingsService $settings): void
    {
        $this->authorize('settings.edit');
        $this->grouped = $settings->membershipGroupByType();
    }

    public function updatedName(): void
    {
        if (! $this->editingId) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $category = MembershipCategory::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description ?? '';
        $this->fee_usd = (string) ($category->fee_usd ?? '');
        $this->fee_ngn = (string) ($category->fee_ngn ?? '');
        $this->corporate_enabled = (bool) $category->corporate_enabled;
        $this->corporate_fee_usd = (string) ($category->corporate_fee_usd ?? '');
        $this->corporate_fee_ngn = (string) ($category->corporate_fee_ngn ?? '');
        $this->individual_enabled = (bool) $category->individual_enabled;
        $this->individual_fee_usd = (string) ($category->individual_fee_usd ?? '');
        $this->individual_fee_ngn = (string) ($category->individual_fee_ngn ?? '');
        $this->is_active = $category->is_active;
        $this->sort_order = $category->sort_order;
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        foreach (['fee_usd', 'fee_ngn', 'corporate_fee_usd', 'corporate_fee_ngn', 'individual_fee_usd', 'individual_fee_ngn'] as $feeField) {
            $data[$feeField] = $this->{$feeField} === '' ? null : $this->{$feeField};
        }

        if ($this->editingId) {
            MembershipCategory::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Category updated.');
        } else {
            MembershipCategory::create($data);
            session()->flash('success', 'Category created.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function toggleActive(int $id): void
    {
        $cat = MembershipCategory::findOrFail($id);
        $cat->update(['is_active' => ! $cat->is_active]);
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->fee_usd = '';
        $this->fee_ngn = '';
        $this->corporate_enabled = false;
        $this->corporate_fee_usd = '';
        $this->corporate_fee_ngn = '';
        $this->individual_enabled = false;
        $this->individual_fee_usd = '';
        $this->individual_fee_ngn = '';
        $this->is_active = true;
        $this->sort_order = 0;
    }

    public function render()
    {
        return view('livewire.membership.category-manager', [
            'categories' => MembershipCategory::orderBy('sort_order')->paginate(15),
        ])->layout('layouts.admin');
    }
}
