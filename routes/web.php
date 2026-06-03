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
Route::get('/chapters/nigeria', [PublicController::class, 'chapterNigeria'])->name('chapters.nigeria');
Route::get('/chapters/kenya', [PublicController::class, 'chapterKenya'])->name('chapters.kenya');
Route::get('/news', [PublicController::class, 'news'])->name('news.index');
Route::get('/news/{slug}', [PublicController::class, 'newsShow'])->name('news.show');
Route::get('/leadership', [PublicController::class, 'leadership'])->name('leadership');
Route::get('/downloads', [PublicController::class, 'downloads'])->name('downloads');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');

// --- SEO ---
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

// --- Auth dashboard (Fortify) ---
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Flutterwave payment webhook (CSRF exempt via bootstrap/app.php)
Route::post('/webhook/flutterwave', FlutterwaveWebhookController::class)
    ->name('webhook.flutterwave');

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
