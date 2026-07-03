<?php

namespace Tests\Feature;

use App\Livewire\Cms\BlogManager;
use App\Models\BlogPost;
use App\Models\Chapter;
use App\Models\MediaItem;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BlogDocumentTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $chapter = Chapter::create(['name' => 'Nigeria', 'code' => 'NGA', 'country' => 'Nigeria']);
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create(['chapter_id' => $chapter->id]);
        $user->assignRole('super-admin');

        return $user;
    }

    private function document(User $user): MediaItem
    {
        return MediaItem::create([
            'chapter_id' => $user->chapter_id,
            'filename' => 'abc-report.pdf',
            'original_filename' => 'Q2 Trade Report.pdf',
            'path' => 'cms/blog/abc-report.pdf',
            'disk' => 'public',
            'mime_type' => 'application/pdf',
            'size' => 2 * 1048576,
            'type' => 'document',
            'uploaded_by' => $user->id,
        ]);
    }

    public function test_post_can_be_saved_with_and_reloaded_and_cleared_of_a_document(): void
    {
        $user = $this->admin();
        $this->actingAs($user);
        $doc = $this->document($user);

        // Create a post with an attached document.
        Livewire::test(BlogManager::class)
            ->call('openForm')
            ->set('title', 'Corridor Update')
            ->set('body', 'Full body text here.')
            ->set('status', 'published')
            ->set('documentMediaItemId', $doc->id)
            ->call('save')
            ->assertHasNoErrors();

        $post = BlogPost::firstOrFail();
        $this->assertTrue($post->hasDocument());
        $this->assertSame('cms/blog/abc-report.pdf', $post->document_path);
        $this->assertSame('Q2 Trade Report.pdf', $post->document_name);
        $this->assertSame('2.0 MB', $post->documentHumanSize());

        // Re-open the post in the manager: the picker rehydrates.
        Livewire::test(BlogManager::class)
            ->call('openForm', $post->id)
            ->assertSet('documentMediaItemId', $doc->id)
            // clear the document and save
            ->set('documentMediaItemId', null)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertFalse($post->fresh()->hasDocument());
    }
}
