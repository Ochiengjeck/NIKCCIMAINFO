<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
            color: #1a1a1a;
        }

        .page {
            width: 794px;
            height: 560px;
            padding: 20px;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
            background: #fff;
        }

        .frame {
            width: 100%;
            height: 100%;
            border: 10px solid #166534;
            box-sizing: border-box;
            padding: 22px 26px 18px;
            position: relative;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 72px;
            font-weight: bold;
            color: rgba(22, 101, 52, 0.04);
            white-space: nowrap;
            z-index: 0;
        }

        .content {
            position: relative;
            z-index: 2;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #166534;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .org-name {
            font-size: 20px;
            font-weight: bold;
            color: #166534;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .org-sub {
            font-size: 10px;
            color: #555;
            letter-spacing: 0.8px;
            margin-top: 4px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            color: #1a1a1a;
            margin: 12px 0 6px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .subtitle {
            text-align: center;
            font-size: 11px;
            color: #555;
            margin-bottom: 16px;
        }

        .body-text {
            font-size: 13px;
            text-align: center;
            color: #333;
            line-height: 1.55;
            margin: 0 14px 8px;
        }

        .member-name {
            font-size: 24px;
            font-weight: bold;
            color: #166534;
            text-align: center;
            margin: 8px 0 10px;
            text-transform: uppercase;
        }

        .meta-wrap {
            margin-top: 14px;
            margin-bottom: 12px;
        }

        .meta {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .meta td {
            width: 25%;
            text-align: center;
            vertical-align: top;
            padding: 0 4px;
        }

        .meta-label {
            color: #777;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .meta-value {
            font-weight: bold;
            font-size: 11px;
            color: #1a1a1a;
            margin-top: 3px;
            word-wrap: break-word;
        }

        .footer {
            margin-top: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .sig-table {
            width: 100%;
            border-collapse: collapse;
        }

        .sig-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
        }

        .sig-line {
            width: 140px;
            border-top: 1px solid #333;
            margin: 0 auto 4px;
        }

        .sig-label {
            font-size: 10px;
            color: #888;
        }

        .issued {
            font-size: 10px;
            color: #8c8c8c;
            line-height: 1.45;
        }
    </style>
</head>
<body>
<div class="page">
    <div class="frame">
        <div class="watermark">NiKCCIMA</div>

        <div class="content">
            <div class="header">
                <div class="org-name">NiKCCIMA</div>
                <div class="org-sub">Nigeria-Kenya Chamber of Commerce, Industry, Mines &amp; Agriculture</div>
            </div>

            <div class="title">Certificate of Membership</div>
            <div class="subtitle">AfCFTA Corridor Execution - {{ $member->chapter->name }} Chapter</div>

            <p class="body-text">This is to certify that</p>
            <div class="member-name">{{ $member->full_name }}</div>
            <p class="body-text">
                @if($member->organization)
                    representing <strong>{{ $member->organization }}</strong>,
                @endif
                is a duly registered member of the Nigeria-Kenya Chamber of Commerce,
                Industry, Mines &amp; Agriculture in the <strong>{{ $member->category->name }}</strong> category.
            </p>

            <div class="meta-wrap">
                <table class="meta">
                    <tr>
                        <td>
                            <div class="meta-label">Membership Number</div>
                            <div class="meta-value">{{ $member->membership_number }}</div>
                        </td>
                        <td>
                            <div class="meta-label">Date Joined</div>
                            <div class="meta-value">{{ $member->joined_at->format('d F Y') }}</div>
                        </td>
                        <td>
                            <div class="meta-label">Valid Until</div>
                            <div class="meta-value">{{ $member->expires_at ? $member->expires_at->format('d F Y') : 'Lifetime' }}</div>
                        </td>
                        <td>
                            <div class="meta-label">Chapter</div>
                            <div class="meta-value">{{ $member->chapter->name }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="footer">
                <table class="sig-table">
                    <tr>
                        <td>
                            <div class="sig-line"></div>
                            <div class="sig-label">Chapter President</div>
                        </td>
                        <td>
                            <div class="issued">
                                Issued: {{ now()->format('d F Y') }}<br>
                                Verify at nikccimainfo.org
                            </div>
                        </td>
                        <td>
                            <div class="sig-line"></div>
                            <div class="sig-label">Director General</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>