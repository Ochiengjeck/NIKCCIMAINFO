<?php

namespace Tests\Feature;

use App\Livewire\Cms\BlogCommentModerator;
use App\Livewire\Cms\BlogManager;
use App\Livewire\Public\BlogCommentForm;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Chapter;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BlogTest extends TestCase
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

    private function makePost(array $attrs = []): BlogPost
    {
        return BlogPost::create(array_merge([
            'title' => 'Sample Post',
            'slug' => BlogPost::generateSlug($attrs['title'] ?? 'Sample Post'),
            'body' => 'This is the body of the sample blog post about AfCFTA trade.',
            'status' => 'published',
            'published_at' => now(),
            'author_id' => User::factory()->create()->id,
        ], $attrs));
    }

    public function test_blog_index_shows_published_and_hides_drafts(): void
    {
        $this->makePost(['title' => 'Visible Published Post', 'slug' => 'visible-published-post']);
        $this->makePost(['title' => 'Hidden Draft Post', 'slug' => 'hidden-draft-post', 'status' => 'draft', 'published_at' => null]);

        $response = $this->get(route('blog.index'));

        $response->assertOk();
        $response->assertSee('Visible Published Post');
        $response->assertDontSee('Hidden Draft Post');
    }

    public function test_blog_show_renders_published_and_404s_drafts(): void
    {
        $published = $this->makePost(['title' => 'A Public Story', 'slug' => 'a-public-story']);
        $draft = $this->makePost(['title' => 'A Secret Draft', 'slug' => 'a-secret-draft', 'status' => 'draft', 'published_at' => null]);

        $this->get(route('blog.show', $published->slug))->assertOk()->assertSee('A Public Story');
        $this->get(route('blog.show', $draft->slug))->assertNotFound();
    }

    public function test_comment_submission_creates_pending_comment_not_shown(): void
    {
        $post = $this->makePost(['slug' => 'commentable-post']);

        Livewire::test(BlogCommentForm::class, ['postId' => $post->id])
            ->set('name', 'Jane Reader')
            ->set('email', 'jane@example.com')
            ->set('body', 'Great insights on the corridor!')
            ->call('submit')
            ->assertSet('submitted', true);

        $comment = BlogComment::firstOrFail();
        $this->assertSame('pending', $comment->status);

        // Pending comment must not appear on the public page.
        $this->get(route('blog.show', $post->slug))->assertDontSee('Great insights on the corridor!');
    }

    public function test_approved_comment_is_shown(): void
    {
        $post = $this->makePost(['slug' => 'approved-comment-post']);
        $comment = BlogComment::create([
            'blog_post_id' => $post->id,
            'author_name' => 'Approved Person',
            'author_email' => 'ok@example.com',
            'body' => 'This comment is approved and visible.',
            'status' => 'approved',
        ]);

        $this->get(route('blog.show', $post->slug))
            ->assertOk()
            ->assertSee('This comment is approved and visible.');
    }

    public function test_moderator_can_approve_a_comment(): void
    {
        $user = $this->adminUser();
        $post = $this->makePost(['slug' => 'mod-post']);
        $comment = BlogComment::create([
            'blog_post_id' => $post->id,
            'author_name' => 'Pending Person',
            'author_email' => 'p@example.com',
            'body' => 'Awaiting moderation.',
            'status' => 'pending',
        ]);

        Livewire::actingAs($user)->test(BlogCommentModerator::class)
            ->call('approve', $comment->id);

        $this->assertSame('approved', $comment->fresh()->status);
    }

    public function test_category_and_tag_filters_work(): void
    {
        $category = BlogCategory::create(['name' => 'Insights', 'slug' => 'insights', 'sort_order' => 0]);
        $tag = BlogTag::create(['name' => 'Exports', 'slug' => 'exports']);

        $inCategory = $this->makePost(['title' => 'Categorised Post', 'slug' => 'categorised-post', 'blog_category_id' => $category->id]);
        $inCategory->tags()->attach($tag->id);
        $other = $this->makePost(['title' => 'Unrelated Post', 'slug' => 'unrelated-post']);

        $this->get(route('blog.category', $category->slug))
            ->assertOk()->assertSee('Categorised Post')->assertDontSee('Unrelated Post');

        $this->get(route('blog.tag', $tag->slug))
            ->assertOk()->assertSee('Categorised Post')->assertDontSee('Unrelated Post');
    }

    public function test_rss_feed_renders_published_posts(): void
    {
        $this->makePost(['title' => 'Feed Item One', 'slug' => 'feed-item-one']);

        $response = $this->get(route('blog.feed'));

        $response->assertOk();
        $this->assertStringContainsString('application/rss+xml', $response->headers->get('Content-Type'));
        $response->assertSee('Feed Item One');
        $response->assertSee('<rss', false);
    }

    public function test_legacy_news_urls_redirect_to_blog(): void
    {
        $post = $this->makePost(['slug' => 'redirect-target']);

        $this->get('/news')->assertRedirect('/blog');
        $this->get('/news/redirect-target')->assertRedirect(route('blog.show', 'redirect-target'));
    }

    public function test_blog_manager_creates_post_with_category_and_tags(): void
    {
        $user = $this->adminUser();
        $category = BlogCategory::create(['name' => 'News', 'slug' => 'news', 'sort_order' => 0]);

        Livewire::actingAs($user)->test(BlogManager::class)
            ->call('openForm')
            ->set('title', 'My First Blog Post')
            ->set('blogCategoryId', $category->id)
            ->set('tagsInput', 'afcfta, exports, afcfta')
            ->set('body', 'Body content for the new blog post.')
            ->set('status', 'published')
            ->call('save');

        $post = BlogPost::firstOrFail();
        $this->assertSame('My First Blog Post', $post->title);
        $this->assertSame($category->id, $post->blog_category_id);
        $this->assertNotNull($post->published_at);
        // Duplicate tag de-duplicated to 2 unique tags.
        $this->assertCount(2, $post->tags);
    }
}
