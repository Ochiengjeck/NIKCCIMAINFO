<?php

namespace App\Livewire\Membership;

use App\Models\MembershipCategory;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $slug = '';

    public string $member_type = 'individual';

    public string $description = '';

    public string $fee_ngn = '';

    public string $fee_kes = '';

    public bool $is_active = true;

    public int $sort_order = 0;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:membership_categories,slug,'.($this->editingId ?? 'NULL'),
            'member_type' => 'required|in:corporate,individual',
            'description' => 'nullable|string',
            'fee_ngn' => 'nullable|numeric|min:0',
            'fee_kes' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    public function mount(): void
    {
        $this->authorize('settings.edit');
    }

    public function updatedName(): void
    {
        $this->regenerateSlug();
    }

    public function updatedMemberType(): void
    {
        $this->regenerateSlug();
    }

    private function regenerateSlug(): void
    {
        if (! $this->editingId) {
            $this->slug = \Illuminate\Support\Str::slug($this->member_type.'-'.$this->name);
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
        $this->member_type = $category->member_type ?? 'individual';
        $this->description = $category->description ?? '';
        $this->fee_ngn = $category->fee_ngn ?? '';
        $this->fee_kes = $category->fee_kes ?? '';
        $this->is_active = $category->is_active;
        $this->sort_order = $category->sort_order;
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['fee_ngn'] = $this->fee_ngn === '' ? null : $this->fee_ngn;
        $data['fee_kes'] = $this->fee_kes === '' ? null : $this->fee_kes;

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
        $this->member_type = 'individual';
        $this->description = '';
        $this->fee_ngn = '';
        $this->fee_kes = '';
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
