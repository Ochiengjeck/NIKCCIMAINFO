<div>
    <div class="mb-6">
        <flux:heading size="xl">General Settings</flux:heading>
        <flux:subheading>Configure global system settings for NiKCCIMA Backoffice</flux:subheading>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    <div class="max-w-xl rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <form wire:submit="save" class="space-y-4">
            <flux:field>
                <flux:label>Site Name <span class="text-red-500">*</span></flux:label>
                <flux:input wire:model="siteName" placeholder="NiKCCIMA Backoffice" />
                <flux:error name="siteName" />
            </flux:field>

            <flux:field>
                <flux:label>Default Currency <span class="text-red-500">*</span></flux:label>
                <flux:select wire:model="defaultCurrency">
                    <flux:select.option value="NGN">NGN — Nigerian Naira</flux:select.option>
                    <flux:select.option value="KES">KES — Kenyan Shilling</flux:select.option>
                    <flux:select.option value="USD">USD — US Dollar</flux:select.option>
                    <flux:select.option value="EUR">EUR — Euro</flux:select.option>
                    <flux:select.option value="GBP">GBP — British Pound</flux:select.option>
                </flux:select>
                <flux:error name="defaultCurrency" />
            </flux:field>

            <flux:field>
                <flux:label>System Notification Email</flux:label>
                <flux:input wire:model="notificationEmail" type="email" placeholder="notifications@nikcima.org" />
                <flux:error name="notificationEmail" />
            </flux:field>

            <div class="pt-2">
                <flux:button type="submit" variant="primary" icon="check">Save Settings</flux:button>
            </div>
        </form>
    </div>
</div>
