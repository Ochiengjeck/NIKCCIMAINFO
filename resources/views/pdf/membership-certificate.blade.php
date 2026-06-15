<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <style>
        @page { margin: 0; }

        * { box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #2a2a2a;
        }

        .serif { font-family: 'DejaVu Serif', Georgia, serif; }

        /* ---- Portrait sheet holding the rotated landscape artwork ----
           Page is portrait 560x794px (420x595.5pt). The landscape design
           (794x560px) is centred and rotated 90deg about its centre to fill it. */
        .sheet {
            width: 560px;
            height: 794px;
            position: relative;
        }
        .rotor {
            position: absolute;
            top: 117px;          /* (794-560)/2 */
            left: -117px;        /* (560-794)/2 */
            width: 794px;
            height: 560px;
            /* rotate to fill the portrait sheet, then scale down about the centre
               so the whole certificate sits smaller and centred on the page */
            transform: rotate(90deg) scale(0.86);
            transform-origin: center center;
        }

        /* ---- Page ---- */
        .page {
            width: 794px;
            height: 560px;
            position: relative;
            overflow: hidden;
            background: #fffdf7;
            padding: 14px;
        }

        /* ---- Decorative frame ---- */
        .frame-outer {
            position: absolute;
            top: 14px; left: 14px; right: 14px; bottom: 14px;
            border: 2px solid #a67c2e;            /* gold */
        }
        .frame-inner {
            position: absolute;
            top: 20px; left: 20px; right: 20px; bottom: 20px;
            border: 5px solid #1c6123;            /* green */
        }
        .frame-keyline {
            position: absolute;
            top: 28px; left: 28px; right: 28px; bottom: 28px;
            border: 1px solid #cda35a;            /* light gold */
        }
        /* gold corner diamonds */
        .diamond {
            position: absolute;
            width: 12px; height: 12px;
            background: #a67c2e;
            z-index: 4;
        }
        .diamond.tl { top: 22px;  left: 22px; }
        .diamond.tr { top: 22px;  right: 22px; }
        .diamond.bl { bottom: 22px; left: 22px; }
        .diamond.br { bottom: 22px; right: 22px; }

        /* Diagonal watermark. The whole .page is rotated +90deg, so rotating the
           text -45deg here makes it sit at a 45deg diagonal on the final certificate. */
        .watermark {
            position: absolute;
            top: 232px; left: 0; right: 0;
            text-align: center;
            font-size: 96px;
            font-weight: bold;
            color: #1c6123;
            opacity: 0.05;
            letter-spacing: 8px;
            transform: rotate(-45deg);
            transform-origin: center center;
            z-index: 0;
        }

        .content {
            position: absolute;
            top: 40px; left: 50px; right: 50px; bottom: 38px;
            z-index: 2;
        }

        /* ---- Header / logo ---- */
        .header { text-align: center; }
        .logo { height: 58px; width: auto; }
        .org-name {
            font-size: 16px;
            font-weight: bold;
            color: #1c6123;
            letter-spacing: 2px;
            margin-top: 6px;
        }
        .org-sub {
            font-size: 8.5px;
            color: #7a7a7a;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 3px;
        }

        /* ornamental divider */
        .divider {
            text-align: center;
            margin: 10px 0 4px;
        }
        .divider .bar {
            display: inline-block;
            width: 90px;
            height: 0;
            border-top: 1px solid #cda35a;
            vertical-align: middle;
        }
        .divider .gem {
            display: inline-block;
            width: 7px; height: 7px;
            background: #a67c2e;
            margin: 0 7px;
            vertical-align: middle;
        }

        /* ---- Title ---- */
        .title {
            font-size: 30px;
            font-weight: bold;
            text-align: center;
            color: #1a1a1a;
            margin: 6px 0 2px;
            letter-spacing: 3px;
        }
        .title-sub {
            text-align: center;
            font-size: 9.5px;
            color: #a67c2e;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        /* ---- Recipient ---- */
        .lead {
            font-size: 11.5px;
            text-align: center;
            color: #6b6b6b;
            font-style: italic;
            margin: 2px 0;
        }
        .member-name {
            font-size: 32px;
            font-weight: bold;
            color: #1c6123;
            text-align: center;
            margin: 4px 0 2px;
        }
        .name-underline {
            width: 340px;
            height: 0;
            border-top: 1px solid #cda35a;
            margin: 0 auto 8px;
        }
        .body-text {
            font-size: 11.5px;
            text-align: center;
            color: #3a3a3a;
            line-height: 1.55;
            margin: 0 30px 4px;
        }

        /* ---- Meta panel ---- */
        .meta-wrap {
            margin: 12px 24px 0;
        }
        .meta { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .meta td {
            width: 25%;
            text-align: center;
            vertical-align: middle;
            padding: 0 8px;
            border-right: 1px solid #e3d9c4;
        }
        .meta td.last { border-right: none; }
        .meta-label {
            color: #a67c2e;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .meta-value {
            font-weight: bold;
            font-size: 11px;
            color: #1a1a1a;
            margin-top: 3px;
            word-wrap: break-word;
        }

        /* ---- Footer ---- (direct child of .page; bottom keeps it inside the border) */
        .footer {
            position: absolute;
            left: 50px; right: 50px; bottom: 44px;
            z-index: 2;
        }
        .sig-table { width: 100%; border-collapse: collapse; }
        .sig-table td { width: 33.33%; text-align: center; vertical-align: bottom; }
        .sig-line {
            width: 150px;
            border-top: 1px solid #333;
            margin: 0 auto 3px;
        }
        .sig-name {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
            font-family: 'DejaVu Serif', Georgia, serif;
        }
        .sig-label {
            font-size: 8.5px;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1px;
        }

        /* seal */
        .seal {
            width: 88px; height: 88px;
            border-radius: 44px;
            border: 3px solid #a67c2e;
            background: #fbf6ea;
            margin: 0 auto;
        }
        .seal-ring {
            width: 76px; height: 76px;
            border-radius: 38px;
            border: 1px solid #1c6123;
            margin: 3px auto 0;
            text-align: center;
        }
        .seal-star { font-size: 12px; color: #a67c2e; margin-top: 12px; line-height: 1; }
        .seal-org { font-size: 11px; font-weight: bold; color: #1c6123; letter-spacing: 1px; margin-top: 3px; line-height: 1; }
        .seal-sub { font-size: 6px; color: #777; letter-spacing: 1px; text-transform: uppercase; margin-top: 3px; }

        .issued {
            text-align: center;
            font-size: 8px;
            color: #9a9a9a;
            margin-top: 7px;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
<div class="sheet">
<div class="rotor">
<div class="page">
    {{-- Decorative frame --}}
    <div class="frame-outer"></div>
    <div class="frame-inner"></div>
    <div class="frame-keyline"></div>
    <div class="diamond tl"></div>
    <div class="diamond tr"></div>
    <div class="diamond bl"></div>
    <div class="diamond br"></div>

    <div class="watermark serif">NiKCCIMA</div>

    <div class="content">
        {{-- Header / logo --}}
        <div class="header">
            @if($logoData)
                <img src="{{ $logoData }}" alt="NiKCCIMA" class="logo" />
                <div class="org-sub">Nigeria&ndash;Kenya Chamber of Commerce, Industry, Mines &amp; Agriculture</div>
            @else
                <div class="org-name serif" style="font-size:24px;letter-spacing:3px;">NiKCCIMA</div>
                <div class="org-sub">Nigeria&ndash;Kenya Chamber of Commerce, Industry, Mines &amp; Agriculture</div>
            @endif
        </div>

        <div class="divider">
            <span class="bar"></span><span class="gem"></span><span class="bar"></span>
        </div>

        {{-- Title --}}
        <div class="title serif">Certificate of Membership</div>
        <div class="title-sub">AfCFTA Corridor Execution &middot; {{ $member->chapter->name }} Chapter</div>

        {{-- Recipient --}}
        <div class="lead">This is to certify that</div>
        <div class="member-name serif">{{ $member->full_name }}</div>
        <div class="name-underline"></div>
        <p class="body-text">
            @if($member->organization)
                representing <strong>{{ $member->organization }}</strong>,
            @endif
            is a duly registered member of the Nigeria&ndash;Kenya Chamber of Commerce, Industry,
            Mines &amp; Agriculture in the <strong>{{ $member->category->name ?? 'General' }}</strong> category.
        </p>

        {{-- Meta --}}
        <div class="meta-wrap">
            <table class="meta">
                <tr>
                    <td>
                        <div class="meta-label">Membership No.</div>
                        <div class="meta-value">{{ $member->membership_number }}</div>
                    </td>
                    <td>
                        <div class="meta-label">Date Joined</div>
                        <div class="meta-value">{{ $member->joined_at ? $member->joined_at->format('d M Y') : '—' }}</div>
                    </td>
                    <td>
                        <div class="meta-label">Valid Until</div>
                        <div class="meta-value">{{ $member->expires_at ? $member->expires_at->format('d M Y') : 'Lifetime' }}</div>
                    </td>
                    <td class="last">
                        <div class="meta-label">Chapter</div>
                        <div class="meta-value">{{ $member->chapter->name }}</div>
                    </td>
                </tr>
            </table>
        </div>

    </div>{{-- .content --}}

    {{-- Footer: signatures + seal — anchored to .page so it stays inside the border --}}
    <div class="footer">
            <table class="sig-table">
                <tr>
                    <td>
                        <div class="sig-line"></div>
                        @if(!empty($signatories[0]['name']))<div class="sig-name">{{ $signatories[0]['name'] }}</div>@endif
                        <div class="sig-label">{{ $signatories[0]['title'] ?? 'Chapter President' }}</div>
                    </td>
                    <td>
                        <div class="seal">
                            <div class="seal-ring">
                                <div class="seal-star">&#9733;</div>
                                <div class="seal-org serif">NiKCCIMA</div>
                                <div class="seal-sub">Official Member</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="sig-line"></div>
                        @if(!empty($signatories[1]['name']))<div class="sig-name">{{ $signatories[1]['name'] }}</div>@endif
                        <div class="sig-label">{{ $signatories[1]['title'] ?? 'Director General' }}</div>
                    </td>
                </tr>
            </table>
            <div class="issued">Issued {{ now()->format('d F Y') }} &nbsp;&middot;&nbsp; Verify at nikccima.org</div>
    </div>{{-- .footer --}}
</div>{{-- .page --}}
</div>{{-- .rotor --}}
</div>{{-- .sheet --}}
</body>
</html>
