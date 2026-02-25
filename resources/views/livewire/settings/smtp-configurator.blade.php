<div>
    <div class="mb-6">
        <flux:heading size="xl">SMTP Configuration</flux:heading>
        <flux:subheading>Configure outbound email settings for the system</flux:subheading>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif
    @if(session('error'))
        <flux:callout variant="danger" class="mb-4">{{ session('error') }}</flux:callout>
    @endif

    <div class="max-w-2xl rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>SMTP Host <span class="text-red-500">*</span></flux:label>
                    <flux:input wire:model="smtpHost" placeholder="smtp.example.com" />
                    <flux:error name="smtpHost" />
                </flux:field>

                <flux:field>
                    <flux:label>Port <span class="text-red-500">*</span></flux:label>
                    <flux:input wire:model="smtpPort" type="number" placeholder="587" />
                    <flux:error name="smtpPort" />
                </flux:field>

                <flux:field>
                    <flux:label>Username</flux:label>
                    <flux:input wire:model="smtpUsername" placeholder="user@example.com" />
                    <flux:error name="smtpUsername" />
                </flux:field>

                <flux:field>
                    <flux:label>Password</flux:label>
                    <flux:input wire:model="smtpPassword" type="password" placeholder="••••••••" />
                    <flux:error name="smtpPassword" />
                </flux:field>

                <flux:field>
                    <flux:label>Encryption <span class="text-red-500">*</span></flux:label>
                    <flux:select wire:model="smtpEncryption">
                        <flux:select.option value="tls">TLS</flux:select.option>
                        <flux:select.option value="ssl">SSL</flux:select.option>
                        <flux:select.option value="starttls">STARTTLS</flux:select.option>
                    </flux:select>
                    <flux:error name="smtpEncryption" />
                </flux:field>

                <flux:field>
                    <flux:label>From Address <span class="text-red-500">*</span></flux:label>
                    <flux:input wire:model="fromAddress" type="email" placeholder="noreply@nikcima.org" />
                    <flux:error name="fromAddress" />
                </flux:field>

                <div class="sm:col-span-2">
                    <flux:field>
                        <flux:label>From Name <span class="text-red-500">*</span></flux:label>
                        <flux:input wire:model="fromName" placeholder="NiKCCIMA Backoffice" />
                        <flux:error name="fromName" />
                    </flux:field>
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <flux:button type="submit" variant="primary" icon="check">Save Settings</flux:button>
                <flux:button wire:click="testConnection" variant="ghost" icon="paper-airplane">Test Connection</flux:button>
            </div>
        </form>
    </div>
</div>
