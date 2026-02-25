<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Barryvdh\DomPDF\Facade\Pdf;

class MembershipCertificateController extends Controller
{
    public function __invoke(Member $member)
    {
        $this->authorize('view', $member);

        $member->load(['chapter', 'category']);

        $pdf = Pdf::loadView('pdf.membership-certificate', compact('member'))
            ->setPaper([0, 0, 794, 560], 'landscape');

        return $pdf->stream("NiKCCIMA-Certificate-{$member->membership_number}.pdf");
    }
}
