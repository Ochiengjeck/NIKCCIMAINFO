<?php

use App\Models\Member;
use App\Models\SystemSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

$member = Member::with(['chapter', 'category'])->first();

$logoData = null;
$path = SystemSetting::get('site_logo');
if ($path && Storage::disk('public')->exists($path)) {
    $abs = Storage::disk('public')->path($path);
    $mime = function_exists('mime_content_type') ? mime_content_type($abs) : 'image/png';
    $logoData = 'data:'.($mime ?: 'image/png').';base64,'.base64_encode(file_get_contents($abs));
}

$signatories = [
    ['name' => 'Dr. Jane W. Mwangi', 'title' => 'Chapter President'],
    ['name' => 'Amb. Chukwuma Okafor', 'title' => 'Director General'],
];

$pdf = Pdf::loadView('pdf.membership-certificate', compact('member', 'logoData', 'signatories'))
    ->setPaper([0, 0, 420, 595.5]);
file_put_contents(storage_path('cert_preview.pdf'), $pdf->output());
echo "Rendered\n";
