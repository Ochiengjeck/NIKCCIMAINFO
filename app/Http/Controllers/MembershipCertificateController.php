<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\SystemSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class MembershipCertificateController extends Controller
{
    public function __invoke(Member $member)
    {
        $this->authorize('view', $member);

        $member->load(['chapter', 'category']);

        // The certificate is authored at 794x560 CSS px (landscape) but printed on a
        // PORTRAIT page with the artwork rotated 90deg in the view. dompdf renders at
        // 96 DPI, so points = px * 0.75. Portrait page = 560px x 794px -> 420pt x 595.5pt.
        $pdf = Pdf::loadView('pdf.membership-certificate', [
            'member' => $member,
            'logoData' => $this->logoDataUri(),
            'signatories' => [
                ['name' => SystemSetting::get('cert_sig1_name', ''), 'title' => SystemSetting::get('cert_sig1_title', 'Chapter President')],
                ['name' => SystemSetting::get('cert_sig2_name', ''), 'title' => SystemSetting::get('cert_sig2_title', 'Director General')],
            ],
        ])->setPaper([0, 0, 420, 595.5]);

        return $pdf->stream("NiKCCIMA-Certificate-{$member->membership_number}.pdf");
    }

    /**
     * Embed the organisation logo (site_logo setting) as a base64 data URI so
     * dompdf can render it without remote-file access. Returns null if unset/missing.
     */
    private function logoDataUri(): ?string
    {
        $path = SystemSetting::get('site_logo');

        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $absolute = Storage::disk('public')->path($path);
        $mime = function_exists('mime_content_type') ? mime_content_type($absolute) : null;
        $mime = $mime ?: 'image/png';

        return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($absolute));
    }
}
