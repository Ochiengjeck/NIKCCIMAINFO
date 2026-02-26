<?php

namespace App\Livewire\Settings;

use App\Services\SettingsService;
use Livewire\Component;

class SystemSettings extends Component
{
    public string $siteName = '';

    public string $defaultCurrency = 'NGN';

    public string $notificationEmail = '';

    public string $logoPath = '';

    public string $iconPath = '';

    public function mount(SettingsService $settings): void
    {
        $this->authorize('settings.edit');
        $this->siteName = $settings->get('site_name', 'NiKCCIMA Backoffice');
        $this->defaultCurrency = $settings->get('default_currency', 'NGN');
        $this->notificationEmail = $settings->get('notification_email', '');
        $this->logoPath = $settings->get('site_logo', '');
        $this->iconPath = $settings->get('site_icon', '');
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

    public function selectLogo(string $path, SettingsService $settings): void
    {
        $this->logoPath = $path;
        $settings->set('site_logo', $path, 'branding');
        session()->flash('success', 'Site logo updated.');
    }

    public function clearLogo(SettingsService $settings): void
    {
        $this->logoPath = '';
        $settings->set('site_logo', '', 'branding');
        session()->flash('success', 'Site logo removed.');
    }

    public function selectIcon(string $path, SettingsService $settings): void
    {
        $this->iconPath = $path;
        $settings->set('site_icon', $path, 'branding');
        session()->flash('success', 'Site icon updated.');
    }

    public function clearIcon(SettingsService $settings): void
    {
        $this->iconPath = '';
        $settings->set('site_icon', '', 'branding');
        session()->flash('success', 'Site icon removed.');
    }

    public function render()
    {
        return view('livewire.settings.system-settings')
            ->layout('layouts.admin');
    }
}
