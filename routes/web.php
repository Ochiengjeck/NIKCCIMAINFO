<?php

use App\Http\Controllers\FlutterwaveWebhookController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

// --- Public Website ---
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/pillars', [PublicController::class, 'pillars'])->name('pillars');
Route::get('/pillars/{slug}', [PublicController::class, 'pillarShow'])->name('pillars.show');
Route::get('/trade', [PublicController::class, 'trade'])->name('trade');
Route::get('/membership', [PublicController::class, 'membership'])->name('membership');
Route::get('/membership/apply', [PublicController::class, 'membershipApply'])->name('membership.apply');
Route::get('/events', [PublicController::class, 'events'])->name('events.index');
Route::get('/events/{id}', [PublicController::class, 'eventShow'])->name('events.show');
Route::get('/policy', \App\Livewire\Public\PolicySearch::class)->name('policy');
Route::get('/policy/{brief}', [PublicController::class, 'policyBriefShow'])->name('policy.show');
Route::get('/chapters/nigeria', [PublicController::class, 'chapterNigeria'])->name('chapters.nigeria');
Route::get('/chapters/kenya', [PublicController::class, 'chapterKenya'])->name('chapters.kenya');
// --- Blog ---
Route::get('/blog', [PublicController::class, 'blog'])->name('blog.index');
Route::get('/blog/feed', [PublicController::class, 'blogFeed'])->name('blog.feed');
Route::get('/blog/category/{slug}', [PublicController::class, 'blogCategory'])->name('blog.category');
Route::get('/blog/tag/{slug}', [PublicController::class, 'blogTag'])->name('blog.tag');
Route::get('/blog/{slug}', [PublicController::class, 'blogShow'])->name('blog.show');

// --- News ---
Route::get('/news', [PublicController::class, 'news'])->name('news.index');
Route::get('/news/feed', [PublicController::class, 'newsFeed'])->name('news.feed');
Route::get('/news/category/{slug}', [PublicController::class, 'newsCategory'])->name('news.category');
Route::get('/news/tag/{slug}', [PublicController::class, 'newsTag'])->name('news.tag');
Route::get('/news/{slug}', [PublicController::class, 'newsShow'])->name('news.show');
Route::get('/leadership', [PublicController::class, 'leadership'])->name('leadership');
Route::get('/downloads', [PublicController::class, 'downloads'])->name('downloads');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');

// Public document / brief downloads — gated by the parent record's published state
Route::get('/downloads/{document}/file', function (\App\Models\Document $document) {
    abort_unless($document->is_public && $document->status === 'approved', 404);

    $name = $document->title.'.'.$document->extension();

    return \Illuminate\Support\Facades\Storage::disk('local')->download($document->file_path, $name);
})->name('public.document.download');

Route::get('/briefs/{brief}/file', function (\App\Models\PolicyBrief $brief) {
    abort_unless($brief->status === 'published' && $brief->file, 404);

    $item = $brief->file;

    return \Illuminate\Support\Facades\Storage::disk($item->disk)->download($item->path, $item->original_filename);
})->name('public.brief.download');

// --- SEO ---
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

// XSL stylesheet that renders the RSS feeds (/blog/feed, /news/feed) as a styled
// page in browsers. Served via a route so the content-type is always XML-correct
// (a static .xsl can be served as octet-stream on some hosts, which breaks XSLT).
Route::get('/feed.xsl', function () {
    return response(file_get_contents(resource_path('feed.xsl')), 200, [
        'Content-Type' => 'text/xsl; charset=UTF-8',
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->name('feed.style');

// --- Auth dashboard (Fortify) ---
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Flutterwave payment webhook (CSRF exempt via bootstrap/app.php)
Route::post('/webhook/flutterwave', FlutterwaveWebhookController::class)
    ->name('webhook.flutterwave');

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
