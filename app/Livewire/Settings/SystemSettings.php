<?php

namespace App\Livewire\Settings;

use App\Services\SettingsService;
use Livewire\Component;

class SystemSettings extends Component
{
    public string $siteName = '';

    public string $defaultCurrency = 'USD';

    public string $notificationEmail = '';

    public bool $membershipGroupByType = false;

    public string $logoPath = '';

    public string $iconPath = '';

    // Contact details (global — shown on the public contact page & footer)
    public string $nigeriaAddress = '';

    public string $nigeriaPhone = '';

    public string $nigeriaEmail = '';

    public string $kenyaAddress = '';

    public string $kenyaPhone = '';

    public string $kenyaEmail = '';

    public string $mapEmbedUrl = '';

    // SEO & analytics
    public string $seoDefaultDescription = '';

    public string $shareImagePath = '';

    public string $gaMeasurementId = '';

    public string $searchConsoleVerification = '';

    public function mount(SettingsService $settings): void
    {
        $this->authorize('settings.edit');
        $this->siteName = $settings->get('site_name', 'NiKCCIMA Backoffice');
        $this->defaultCurrency = $settings->get('default_currency', 'USD');
        $this->notificationEmail = $settings->get('notification_email', '');
        $this->membershipGroupByType = (bool) $settings->get('membership_group_by_type', false);
        $this->logoPath = $settings->get('site_logo', '');
        $this->iconPath = $settings->get('site_icon', '');

        $this->nigeriaAddress = $settings->get('nigeria_address', 'Abuja, Federal Capital Territory, Federal Republic of Nigeria');
        $this->nigeriaPhone = $settings->get('nigeria_phone', '');
        $this->nigeriaEmail = $settings->get('nigeria_email', 'nigeria@nikccima.org');
        $this->kenyaAddress = $settings->get('kenya_address', 'Nairobi, Republic of Kenya');
        $this->kenyaPhone = $settings->get('kenya_phone', '');
        $this->kenyaEmail = $settings->get('kenya_email', 'kenya@nikccima.org');
        $this->mapEmbedUrl = $settings->get('map_embed_url', '');

        $this->seoDefaultDescription = $settings->get('seo_default_description', '');
        $this->shareImagePath = $settings->get('seo_share_image', '');
        $this->gaMeasurementId = $settings->get('ga_measurement_id', '');
        $this->searchConsoleVerification = $settings->get('search_console_verification', '');
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

    public function saveMembership(SettingsService $settings): void
    {
        $this->authorize('settings.edit');

        $settings->set('membership_group_by_type', $this->membershipGroupByType ? '1' : '0', 'membership');

        session()->flash('success', 'Membership settings saved successfully.');
    }

    public function saveContact(SettingsService $settings): void
    {
        $this->authorize('settings.edit');

        $this->validate([
            'nigeriaAddress' => 'nullable|string|max:255',
            'nigeriaPhone' => 'nullable|string|max:50',
            'nigeriaEmail' => 'nullable|email|max:255',
            'kenyaAddress' => 'nullable|string|max:255',
            'kenyaPhone' => 'nullable|string|max:50',
            'kenyaEmail' => 'nullable|email|max:255',
            'mapEmbedUrl' => 'nullable|url|max:500',
        ]);

        $settings->set('nigeria_address', $this->nigeriaAddress, 'contact');
        $settings->set('nigeria_phone', $this->nigeriaPhone, 'contact');
        $settings->set('nigeria_email', $this->nigeriaEmail, 'contact');
        $settings->set('kenya_address', $this->kenyaAddress, 'contact');
        $settings->set('kenya_phone', $this->kenyaPhone, 'contact');
        $settings->set('kenya_email', $this->kenyaEmail, 'contact');
        $settings->set('map_embed_url', $this->mapEmbedUrl, 'contact');

        session()->flash('success', 'Contact details saved successfully.');
    }

    public function saveSeo(SettingsService $settings): void
    {
        $this->authorize('settings.edit');

        $this->validate([
            'seoDefaultDescription' => 'nullable|string|max:300',
            'gaMeasurementId' => 'nullable|string|max:50',
            'searchConsoleVerification' => 'nullable|string|max:255',
        ]);

        $settings->set('seo_default_description', $this->seoDefaultDescription, 'seo');
        $settings->set('ga_measurement_id', trim($this->gaMeasurementId), 'seo');
        $settings->set('search_console_verification', trim($this->searchConsoleVerification), 'seo');

        session()->flash('success', 'SEO settings saved successfully.');
    }

    public function selectShareImage(string $path, SettingsService $settings): void
    {
        $this->shareImagePath = $path;
        $settings->set('seo_share_image', $path, 'seo');
        session()->flash('success', 'Social share image updated.');
    }

    public function clearShareImage(SettingsService $settings): void
    {
        $this->shareImagePath = '';
        $settings->set('seo_share_image', '', 'seo');
        session()->flash('success', 'Social share image removed.');
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
