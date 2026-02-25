<?php

namespace App\Livewire\Settings;

use App\Services\SettingsService;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class SmtpConfigurator extends Component
{
    public string $smtpHost = '';

    public string $smtpPort = '587';

    public string $smtpUsername = '';

    public string $smtpPassword = '';

    public string $smtpEncryption = 'tls';

    public string $fromAddress = '';

    public string $fromName = '';

    public ?string $testResult = null;

    public function mount(SettingsService $settings): void
    {
        $this->authorize('settings.edit');
        $this->loadSettings($settings);
    }

    protected function loadSettings(SettingsService $settings): void
    {
        $this->smtpHost = $settings->get('smtp_host', '');
        $this->smtpPort = $settings->get('smtp_port', '587');
        $this->smtpUsername = $settings->get('smtp_username', '');
        $this->smtpPassword = $settings->get('smtp_password', '');
        $this->smtpEncryption = $settings->get('smtp_encryption', 'tls');
        $this->fromAddress = $settings->get('mail_from_address', '');
        $this->fromName = $settings->get('mail_from_name', '');
    }

    public function save(SettingsService $settings): void
    {
        $this->validate([
            'smtpHost' => 'required|string',
            'smtpPort' => 'required|integer',
            'smtpUsername' => 'nullable|string',
            'smtpPassword' => 'nullable|string',
            'smtpEncryption' => 'required|in:tls,ssl,starttls',
            'fromAddress' => 'required|email',
            'fromName' => 'required|string|max:100',
        ]);

        $settings->set('smtp_host', $this->smtpHost, 'mail');
        $settings->set('smtp_port', $this->smtpPort, 'mail');
        $settings->set('smtp_username', $this->smtpUsername, 'mail');
        $settings->set('smtp_password', $this->smtpPassword, 'mail');
        $settings->set('smtp_encryption', $this->smtpEncryption, 'mail');
        $settings->set('mail_from_address', $this->fromAddress, 'mail');
        $settings->set('mail_from_name', $this->fromName, 'mail');

        session()->flash('success', 'SMTP settings saved successfully.');
    }

    public function testConnection(): void
    {
        try {
            config([
                'mail.mailers.smtp.host' => $this->smtpHost,
                'mail.mailers.smtp.port' => $this->smtpPort,
                'mail.mailers.smtp.username' => $this->smtpUsername,
                'mail.mailers.smtp.password' => $this->smtpPassword,
                'mail.mailers.smtp.encryption' => $this->smtpEncryption,
                'mail.from.address' => $this->fromAddress,
                'mail.from.name' => $this->fromName,
            ]);

            Mail::raw('NiKCCIMA SMTP test connection successful.', function ($msg) {
                $msg->to(auth()->user()->email)
                    ->subject('NiKCCIMA SMTP Test');
            });

            $this->testResult = 'success';
            session()->flash('success', 'Test email sent to '.auth()->user()->email);
        } catch (\Throwable $e) {
            $this->testResult = 'error';
            session()->flash('error', 'Connection failed: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.settings.smtp-configurator')
            ->layout('layouts.admin');
    }
}
