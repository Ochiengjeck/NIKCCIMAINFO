<div>
    <div class="mb-6">
        <flux:heading size="xl">Report Generator</flux:heading>
        <flux:subheading>Generate and export operational reports</flux:subheading>
    </div>

    <div class="max-w-xl rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="space-y-4">
            <flux:field>
                <flux:label>Report Type <span class="text-red-500">*</span></flux:label>
                <flux:select wire:model="reportType">
                    <flux:select.option value="membership">Membership Statistics</flux:select.option>
                    <flux:select.option value="financial">Financial Statement</flux:select.option>
                    <flux:select.option value="trade-leads">Trade Leads</flux:select.option>
                    <flux:select.option value="ntb">Non-Tariff Barriers</flux:select.option>
                    <flux:select.option value="corridors">Corridor Activity</flux:select.option>
                    <flux:select.option value="events">Event Attendance</flux:select.option>
                </flux:select>
            </flux:field>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Date From</flux:label>
                    <flux:input wire:model="dateFrom" type="date" />
                </flux:field>
                <flux:field>
                    <flux:label>Date To</flux:label>
                    <flux:input wire:model="dateTo" type="date" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label>Format</flux:label>
                <flux:select wire:model="format">
                    <flux:select.option value="xlsx">Excel (.xlsx)</flux:select.option>
                    <flux:select.option value="csv">CSV (.csv)</flux:select.option>
                </flux:select>
            </flux:field>

            <div class="pt-2">
                <flux:button wire:click="generate" variant="primary" icon="arrow-down-tray">Generate Report</flux:button>
            </div>
        </div>
    </div>

    <div class="mt-6 max-w-xl rounded-xl border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/30">
        <p class="text-sm text-blue-700 dark:text-blue-300">
            <strong>Note:</strong> Large reports may take a moment to generate. Reports are based on data within your chapter scope. For global reports, a super-admin account is required.
        </p>
    </div>
</div>
