<?php

namespace Tests\Feature;

use App\Livewire\Cms\NewsCommentModerator;
use App\Livewire\Cms\NewsManager;
use App\Livewire\Public\NewsCommentForm;
use App\Models\Chapter;
use App\Models\NewsArticle;
use App\Models\NewsCategory;
use App\Models\NewsComment;
use App\Models\NewsTag;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        $chapter = Chapter::create(['name' => 'Nigeria Chapter', 'code' => 'NGA', 'country' => 'Nigeria']);
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create(['chapter_id' => $chapter->id]);
        $user->assignRole('super-admin');

        return $user;
    }

    private function makeArticle(array $attrs = []): NewsArticle
    {
        return NewsArticle::create(array_merge([
            'title' => 'Sample Article',
            'slug' => NewsArticle::generateSlug($attrs['title'] ?? 'Sample Article'),
            'body' => 'This is the body of the sample news article about AfCFTA trade.',
            'status' => 'published',
            'published_at' => now(),
            'author_id' => User::factory()->create()->id,
        ], $attrs));
    }

    public function test_news_index_shows_published_and_hides_drafts(): void
    {
        $this->makeArticle(['title' => 'Visible Published Article', 'slug' => 'visible-published-article']);
        $this->makeArticle(['title' => 'Hidden Draft Article', 'slug' => 'hidden-draft-article', 'status' => 'draft', 'published_at' => null]);

        $response = $this->get(route('news.index'));

        $response->assertOk();
        $response->assertSee('Visible Published Article');
        $response->assertDontSee('Hidden Draft Article');
    }

    public function test_news_show_renders_published_and_404s_drafts(): void
    {
        $published = $this->makeArticle(['title' => 'A Public Story', 'slug' => 'a-public-story']);
        $draft = $this->makeArticle(['title' => 'A Secret Draft', 'slug' => 'a-secret-draft', 'status' => 'draft', 'published_at' => null]);

        $this->get(route('news.show', $published->slug))->assertOk()->assertSee('A Public Story');
        $this->get(route('news.show', $draft->slug))->assertNotFound();
    }

    public function test_comment_submission_creates_pending_comment_not_shown(): void
    {
        $article = $this->makeArticle(['slug' => 'commentable-article']);

        Livewire::test(NewsCommentForm::class, ['articleId' => $article->id])
            ->set('name', 'Jane Reader')
            ->set('email', 'jane@example.com')
            ->set('body', 'Great insights on the corridor!')
            ->call('submit')
            ->assertSet('submitted', true);

        $comment = NewsComment::firstOrFail();
        $this->assertSame('pending', $comment->status);

        $this->get(route('news.show', $article->slug))->assertDontSee('Great insights on the corridor!');
    }

    public function test_approved_comment_is_shown(): void
    {
        $article = $this->makeArticle(['slug' => 'approved-comment-article']);
        NewsComment::create([
            'news_article_id' => $article->id,
            'author_name' => 'Approved Person',
            'author_email' => 'ok@example.com',
            'body' => 'This comment is approved and visible.',
            'status' => 'approved',
        ]);

        $this->get(route('news.show', $article->slug))
            ->assertOk()
            ->assertSee('This comment is approved and visible.');
    }

    public function test_moderator_can_approve_a_comment(): void
    {
        $user = $this->adminUser();
        $article = $this->makeArticle(['slug' => 'mod-article']);
        $comment = NewsComment::create([
            'news_article_id' => $article->id,
            'author_name' => 'Pending Person',
            'author_email' => 'p@example.com',
            'body' => 'Awaiting moderation.',
            'status' => 'pending',
        ]);

        Livewire::actingAs($user)->test(NewsCommentModerator::class)
            ->call('approve', $comment->id);

        $this->assertSame('approved', $comment->fresh()->status);
    }

    public function test_category_and_tag_filters_work(): void
    {
        $category = NewsCategory::create(['name' => 'Insights', 'slug' => 'insights', 'sort_order' => 0]);
        $tag = NewsTag::create(['name' => 'Exports', 'slug' => 'exports']);

        $inCategory = $this->makeArticle(['title' => 'Categorised Article', 'slug' => 'categorised-article', 'news_category_id' => $category->id, 'excerpt' => 'IN-CATEGORY-EXCERPT']);
        $inCategory->tags()->attach($tag->id);
        $this->makeArticle(['title' => 'Unrelated Article', 'slug' => 'unrelated-article', 'excerpt' => 'UNRELATED-EXCERPT']);

        $this->get(route('news.category', $category->slug))
            ->assertOk()->assertSee('IN-CATEGORY-EXCERPT')->assertDontSee('UNRELATED-EXCERPT');

        $this->get(route('news.tag', $tag->slug))
            ->assertOk()->assertSee('IN-CATEGORY-EXCERPT')->assertDontSee('UNRELATED-EXCERPT');
    }

    public function test_rss_feed_renders_published_articles(): void
    {
        $this->makeArticle(['title' => 'Feed Item One', 'slug' => 'feed-item-one']);

        $response = $this->get(route('news.feed'));

        $response->assertOk();
        $this->assertStringContainsString('application/rss+xml', $response->headers->get('Content-Type'));
        $response->assertSee('Feed Item One');
        $response->assertSee('<rss', false);
    }

    public function test_rss_feed_is_valid_when_empty(): void
    {
        $response = $this->get(route('news.feed'));

        $response->assertOk();
        $response->assertSee('<rss', false);
        $response->assertSee('<lastBuildDate>', false);
        $response->assertDontSee('<item>', false);
    }

    public function test_news_manager_creates_article_with_category_and_tags(): void
    {
        $user = $this->adminUser();
        $category = NewsCategory::create(['name' => 'Press Release', 'slug' => 'press-release', 'sort_order' => 0]);

        Livewire::actingAs($user)->test(NewsManager::class)
            ->call('openForm')
            ->set('title', 'My First News Article')
            ->set('newsCategoryId', $category->id)
            ->set('tagsInput', 'afcfta, exports, afcfta')
            ->set('body', 'Body content for the new news article.')
            ->set('status', 'published')
            ->call('save');

        $article = NewsArticle::firstOrFail();
        $this->assertSame('My First News Article', $article->title);
        $this->assertSame($category->id, $article->news_category_id);
        $this->assertNotNull($article->published_at);
        $this->assertCount(2, $article->tags);
    }

    public function test_news_and_blog_are_independent(): void
    {
        // A news article must not appear on the blog listing and vice-versa.
        $this->makeArticle(['title' => 'NEWS-ONLY-ITEM', 'slug' => 'news-only-item']);

        $this->get(route('news.index'))->assertSee('NEWS-ONLY-ITEM');
        $this->get(route('blog.index'))->assertDontSee('NEWS-ONLY-ITEM');
    }
}
