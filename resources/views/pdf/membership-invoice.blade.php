<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    @php
        $settings = app(\App\Services\SettingsService::class);
        $usd = $application->chargeUsd();
        $ngn = $application->chargeNgn();
        $proforma = $application->invoice()?->invoice_number
            ?? 'PF-'.now()->format('Y').'-'.str_pad((string) $application->id, 5, '0', STR_PAD_LEFT);
        $contactEmail = $settings->get('notification_email') ?: $settings->get('nigeria_email', 'info@nikccima.org');
        $contactPhone = $settings->get('nigeria_phone', '');
        $contactAddress = $settings->get('nigeria_address', 'Abuja, Federal Republic of Nigeria');
    @endphp
    <style>
        @page { margin: 36px 40px; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1a1a1a; font-size: 12px; }
        .header { border-bottom: 3px solid #166534; padding-bottom: 12px; margin-bottom: 20px; }
        .org-name { font-size: 22px; font-weight: bold; color: #166534; letter-spacing: 1px; }
        .org-sub { font-size: 10px; color: #555; margin-top: 3px; }
        .doc-title { float: right; text-align: right; }
        .doc-title h1 { font-size: 22px; margin: 0; color: #1a1a1a; letter-spacing: 2px; text-transform: uppercase; }
        .doc-title .num { font-size: 11px; color: #777; margin-top: 4px; }
        .clear { clear: both; }
        .cols { width: 100%; margin: 14px 0 20px; }
        .cols td { vertical-align: top; width: 50%; font-size: 11px; line-height: 1.6; }
        .label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.8px; color: #888; margin-bottom: 4px; }
        .strong { font-weight: bold; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.items th { background: #166534; color: #fff; text-align: left; padding: 9px 12px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        table.items th.right, table.items td.right { text-align: right; }
        table.items td { padding: 12px; border-bottom: 1px solid #e5e5e5; vertical-align: top; }
        .totals { width: 45%; float: right; margin-top: 12px; border-collapse: collapse; }
        .totals td { padding: 7px 12px; font-size: 12px; }
        .totals .tot td { border-top: 2px solid #166534; font-weight: bold; font-size: 14px; color: #166534; }
        .muted { color: #777; font-size: 10px; }
        .note { clear: both; margin-top: 36px; border: 1px solid #e5e5e5; background: #fafafa; padding: 14px 16px; font-size: 10.5px; color: #444; line-height: 1.6; }
        .footer { margin-top: 24px; text-align: center; font-size: 9.5px; color: #999; }
        .badge { display: inline-block; background: #fef3c7; color: #92400e; font-size: 9px; padding: 3px 8px; border-radius: 3px; letter-spacing: 0.5px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="doc-title">
            <h1>Pro-forma Invoice</h1>
            <div class="num">{{ $proforma }}</div>
            <div class="num">Date: {{ now()->format('d M Y') }}</div>
        </div>
        <div class="org-name">NiKCCIMA</div>
        <div class="org-sub">Nigeria-Kenya Chamber of Commerce, Industry, Mines &amp; Agriculture</div>
        <div class="clear"></div>
    </div>

    <table class="cols">
        <tr>
            <td>
                <div class="label">Bill To</div>
                <div class="strong">{{ $application->applicant_name }}</div>
                @if($application->organization && $application->organization !== $application->applicant_name)
                    <div>{{ $application->organization }}</div>
                @endif
                @if($application->address)<div>{{ $application->address }}</div>@endif
                @if($application->country)<div>{{ $application->country }}</div>@endif
                <div>{{ $application->email }}</div>
                @if($application->phone)<div>{{ $application->phone }}</div>@endif
            </td>
            <td>
                <div class="label">Application</div>
                <div>Reference: <span class="strong">#{{ $application->id }}</span></div>
                <div>Chapter: {{ $application->chapter?->name ?? '—' }}</div>
                @if($application->member_group)
                    <div>Type: {{ ucfirst($application->member_group) }}</div>
                @endif
                <div style="margin-top:6px;"><span class="badge">PAYMENT PENDING</span></div>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th class="right" style="width:30%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="strong">{{ $application->category?->name ?? 'Membership' }}</div>
                    <div class="muted">Annual membership subscription — NiKCCIMA</div>
                </td>
                <td class="right">
                    @if($usd > 0)<div class="strong">${{ number_format($usd, 2) }}</div>@endif
                    @if($ngn > 0)<div class="muted">₦{{ number_format($ngn, 0) }}</div>@endif
                    @if($usd <= 0 && $ngn <= 0)<div>—</div>@endif
                </td>
            </tr>
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="right">@if($usd > 0)${{ number_format($usd, 2) }}@elseif($ngn > 0)₦{{ number_format($ngn, 0) }}@else—@endif</td>
        </tr>
        <tr class="tot">
            <td>Total Due</td>
            <td class="right">
                @if($usd > 0)${{ number_format($usd, 2) }}@elseif($ngn > 0)₦{{ number_format($ngn, 0) }}@else—@endif
            </td>
        </tr>
        @if($usd > 0 && $ngn > 0)
            <tr><td colspan="2" class="muted right">(≈ ₦{{ number_format($ngn, 0) }})</td></tr>
        @endif
    </table>
    <div class="clear"></div>

    <div class="note">
        <span class="strong">Payment instructions:</span>
        Please contact the secretariat to complete payment and activate your membership.
        Email: {{ $contactEmail }}@if($contactPhone) &middot; Tel: {{ $contactPhone }}@endif.
        {{ $contactAddress }}.
        Quote your application reference <span class="strong">#{{ $application->id }}</span> and pro-forma number
        <span class="strong">{{ $proforma }}</span> with your payment.
        <br><br>
        This is a pro-forma invoice issued for the purpose of facilitating payment and is not a tax invoice.
    </div>

    <div class="footer">
        NiKCCIMA — Nigeria-Kenya Chamber of Commerce, Industry, Mines &amp; Agriculture &middot; nikccima.org
    </div>
</body>
</html>
