<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\NewsArticle;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /** Static public routes to include in the sitemap. */
    private array $staticRoutes = [
        'home', 'about', 'pillars', 'trade', 'membership', 'membership.apply',
        'events.index', 'news.index', 'leadership', 'downloads', 'contact',
        'chapters.nigeria', 'chapters.kenya', 'policy',
    ];

    private array $pillarSlugs = ['executive', 'trade', 'policy', 'admin'];

    public function sitemap(): Response
    {
        $urls = [];

        foreach ($this->staticRoutes as $name) {
            $urls[] = ['loc' => route($name), 'priority' => $name === 'home' ? '1.0' : '0.7'];
        }

        foreach ($this->pillarSlugs as $slug) {
            $urls[] = ['loc' => route('pillars.show', $slug), 'priority' => '0.6'];
        }

        foreach (NewsArticle::published()->latest('published_at')->get() as $article) {
            $urls[] = [
                'loc' => route('news.show', $article->slug),
                'lastmod' => optional($article->updated_at)->toAtomString(),
                'priority' => '0.6',
            ];
        }

        foreach (Event::public()->latest('starts_at')->get() as $event) {
            $urls[] = [
                'loc' => route('events.show', $event->id),
                'lastmod' => optional($event->updated_at)->toAtomString(),
                'priority' => '0.6',
            ];
        }

        return response()
            ->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /dashboard',
            'Disallow: /settings',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
    }
}
