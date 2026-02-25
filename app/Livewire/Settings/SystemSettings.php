<?php

namespace App\Livewire\Settings;

use App\Services\SettingsService;
use Livewire\Component;

class SystemSettings extends Component
{
    public string $siteName = '';

    public string $defaultCurrency = 'NGN';

    public string $notificationEmail = '';

    public function mount(SettingsService $settings): void
    {
        $this->authorize('settings.edit');
        $this->siteName = $settings->get('site_name', 'NiKCCIMA Backoffice');
        $this->defaultCurrency = $settings->get('default_currency', 'NGN');
        $this->notificationEmail = $settings->get('notification_email', '');
    }

    public function save(SettingsService $settings): void
    {
        $this->validate([
            'siteName' => 'required|string|max:100',
            'defaultCurrency' => 'required|string|size:3',
            'notificationEmail' => 'nullable|email|max:255',
        ]);

        $settings->set('site_name', $this->siteName);
        $settings->set('default_currency', $this->defaultCurrency);
        $settings->set('notification_email', $this->notificationEmail);

        session()->flash('success', 'General settings saved successfully.');
    }

    public function render()
    {
        return view('livewire.settings.system-settings')
            ->layout('layouts.admin');
    }
}
