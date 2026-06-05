<?php

namespace Tests\Feature;

use App\Livewire\Documents\DocumentUploader;
use App\Livewire\Policy\PolicyBriefEditor;
use App\Models\Chapter;
use App\Models\Document;
use App\Models\MediaItem;
use App\Models\PolicyBrief;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class DocumentsPublicTest extends TestCase
{
    use RefreshDatabase;

    private function chapter(): Chapter
    {
        return Chapter::create(['name' => 'Nigeria Chapter', 'code' => 'NGA', 'country' => 'Nigeria']);
    }

    private function adminUser(Chapter $chapter): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create(['chapter_id' => $chapter->id]);
        $user->assignRole('super-admin');

        return $user;
    }

    private function makeDocument(Chapter $chapter, User $user, array $attrs = []): Document
    {
        Storage::disk('local')->put('documents/test.pdf', '%PDF- fake');

        return Document::create(array_merge([
            'chapter_id' => $chapter->id,
            'title' => 'Sample Document',
            'category' => 'trade-report',
            'file_path' => 'documents/test.pdf',
            'file_size' => 1234,
            'mime_type' => 'application/pdf',
            'version' => 1,
            'uploaded_by' => $user->id,
            'status' => 'approved',
            'is_public' => true,
        ], $attrs));
    }

    public function test_downloads_page_renders_and_excludes_private_and_pending(): void
    {
        Storage::fake('local');
        $chapter = $this->chapter();
        $user = User::factory()->create(['chapter_id' => $chapter->id]);

        // A private receipt MediaItem (the kind that used to 500 the page).
        MediaItem::create([
            'chapter_id' => $chapter->id,
            'filename' => 'receipt.pdf',
            'original_filename' => 'Confidential Receipt.pdf',
            'path' => 'receipts/receipt.pdf',
            'disk' => 'local',
            'mime_type' => 'application/pdf',
            'size' => 100,
            'type' => 'document',
            'uploaded_by' => $user->id,
        ]);

        $this->makeDocument($chapter, $user, ['title' => 'Pending Internal Memo', 'status' => 'pending-approval', 'is_public' => false]);
        $this->makeDocument($chapter, $user, ['title' => 'Public Trade Report', 'status' => 'approved', 'is_public' => true]);

        $response = $this->get(route('downloads'));

        $response->assertOk();
        $response->assertSee('Public Trade Report');
        $response->assertDontSee('Pending Internal Memo');
        $response->assertDontSee('Confidential Receipt');
    }

    public function test_public_document_download_is_gated(): void
    {
        Storage::fake('local');
        $chapter = $this->chapter();
        $user = User::factory()->create(['chapter_id' => $chapter->id]);

        $public = $this->makeDocument($chapter, $user, ['status' => 'approved', 'is_public' => true]);
        $internal = $this->makeDocument($chapter, $user, ['status' => 'approved', 'is_public' => false]);
        $pending = $this->makeDocument($chapter, $user, ['status' => 'pending-approval', 'is_public' => true]);

        $this->get(route('public.document.download', $public))->assertOk();
        $this->get(route('public.document.download', $internal))->assertNotFound();
        $this->get(route('public.document.download', $pending))->assertNotFound();
    }

    public function test_published_brief_file_download_is_gated(): void
    {
        Storage::fake('local');
        $chapter = $this->chapter();
        $user = User::factory()->create(['chapter_id' => $chapter->id]);

        Storage::disk('local')->put('policy-briefs/brief.pdf', '%PDF- fake');
        $media = MediaItem::create([
            'chapter_id' => $chapter->id,
            'filename' => 'brief.pdf',
            'original_filename' => 'Corridor Brief.pdf',
            'path' => 'policy-briefs/brief.pdf',
            'disk' => 'local',
            'mime_type' => 'application/pdf',
            'size' => 200,
            'type' => 'document',
            'uploaded_by' => $user->id,
        ]);

        $published = PolicyBrief::create([
            'chapter_id' => $chapter->id, 'author_id' => $user->id,
            'title' => 'Published Brief', 'body' => 'Body', 'status' => 'published',
            'published_at' => now(), 'file_media_item_id' => $media->id,
        ]);
        $draft = PolicyBrief::create([
            'chapter_id' => $chapter->id, 'author_id' => $user->id,
            'title' => 'Draft Brief', 'body' => 'Body', 'status' => 'draft',
            'file_media_item_id' => $media->id,
        ]);

        $this->get(route('public.brief.download', $published))->assertOk();
        $this->get(route('public.brief.download', $draft))->assertNotFound();
    }

    public function test_uploader_persists_description_and_public_flag(): void
    {
        Storage::fake('local');
        $chapter = $this->chapter();
        $user = $this->adminUser($chapter);

        $media = MediaItem::create([
            'chapter_id' => $chapter->id,
            'filename' => 'doc.pdf',
            'original_filename' => 'Doc.pdf',
            'path' => 'documents/NGA/trade-report/doc.pdf',
            'disk' => 'local',
            'mime_type' => 'application/pdf',
            'size' => 321,
            'type' => 'document',
            'uploaded_by' => $user->id,
        ]);

        Livewire::actingAs($user)->test(DocumentUploader::class)
            ->set('title', 'Quarterly Report')
            ->set('category', 'trade-report')
            ->set('description', 'A summary of corridor trade.')
            ->set('is_public', true)
            ->set('fileMediaItemId', $media->id)
            ->call('save');

        $doc = Document::firstOrFail();
        $this->assertSame('A summary of corridor trade.', $doc->description);
        $this->assertTrue($doc->is_public);
        $this->assertSame('pending-approval', $doc->status);
    }

    public function test_global_user_brief_creation_falls_back_to_a_chapter(): void
    {
        // A global super-admin has no chapter_id; the brief must still get a non-null chapter.
        Chapter::create(['name' => 'Global', 'code' => 'GLOBAL', 'country' => 'Global']);
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create(['chapter_id' => null]);
        $user->assignRole('super-admin');

        Livewire::actingAs($user)->test(PolicyBriefEditor::class)
            ->call('openForm')
            ->set('title', 'Global Brief')
            ->set('body', 'Body text for a globally authored brief.')
            ->call('save');

        $brief = PolicyBrief::firstOrFail();
        $this->assertNotNull($brief->chapter_id);
        $this->assertSame('GLOBAL', $brief->chapter->code);
    }

    public function test_document_library_approve_archive_and_delete(): void
    {
        Storage::fake('local');
        $chapter = $this->chapter();
        $user = $this->adminUser($chapter);
        $doc = $this->makeDocument($chapter, $user, ['status' => 'pending-approval', 'is_public' => false]);

        Livewire::actingAs($user)->test(\App\Livewire\Documents\DocumentLibrary::class)
            ->call('approve', $doc->id);
        $this->assertSame('approved', $doc->fresh()->status);

        Livewire::actingAs($user)->test(\App\Livewire\Documents\DocumentLibrary::class)
            ->call('archive', $doc->id);
        $this->assertSame('archived', $doc->fresh()->status);

        Livewire::actingAs($user)->test(\App\Livewire\Documents\DocumentLibrary::class)
            ->call('destroy', $doc->id);
        $this->assertNull(Document::find($doc->id));
    }

    public function test_document_detail_page_renders(): void
    {
        Storage::fake('local');
        $chapter = $this->chapter();
        $user = $this->adminUser($chapter);
        $doc = $this->makeDocument($chapter, $user);

        Livewire::actingAs($user)->test(\App\Livewire\Documents\DocumentDetail::class, ['document' => $doc])
            ->assertOk()
            ->assertSee($doc->title);
    }

    public function test_brief_unpublish_and_delete(): void
    {
        $chapter = $this->chapter();
        $user = $this->adminUser($chapter);
        $brief = PolicyBrief::create([
            'chapter_id' => $chapter->id, 'author_id' => $user->id,
            'title' => 'Pub', 'body' => 'Body', 'status' => 'published', 'published_at' => now(),
        ]);

        Livewire::actingAs($user)->test(PolicyBriefEditor::class)->call('unpublish', $brief->id);
        $this->assertNull($brief->fresh()->published_at);
        $this->assertSame('in-review', $brief->fresh()->status);

        Livewire::actingAs($user)->test(PolicyBriefEditor::class)->call('destroy', $brief->id);
        $this->assertNull(PolicyBrief::find($brief->id));
    }

    public function test_public_brief_detail_page_is_gated(): void
    {
        $chapter = $this->chapter();
        $user = User::factory()->create(['chapter_id' => $chapter->id]);

        $published = PolicyBrief::create([
            'chapter_id' => $chapter->id, 'author_id' => $user->id,
            'title' => 'Visible Brief', 'body' => 'Public body text here.',
            'status' => 'published', 'published_at' => now(),
        ]);
        $draft = PolicyBrief::create([
            'chapter_id' => $chapter->id, 'author_id' => $user->id,
            'title' => 'Hidden Brief', 'body' => 'Draft body.', 'status' => 'draft',
        ]);

        $this->get(route('policy.show', $published))->assertOk()->assertSee('Public body text here.');
        $this->get(route('policy.show', $draft))->assertNotFound();
    }

    public function test_brief_editor_persists_file(): void
    {
        Storage::fake('local');
        $chapter = $this->chapter();
        $user = $this->adminUser($chapter);

        $media = MediaItem::create([
            'chapter_id' => $chapter->id,
            'filename' => 'b.pdf',
            'original_filename' => 'B.pdf',
            'path' => 'policy-briefs/b.pdf',
            'disk' => 'local',
            'mime_type' => 'application/pdf',
            'size' => 99,
            'type' => 'document',
            'uploaded_by' => $user->id,
        ]);

        Livewire::actingAs($user)->test(PolicyBriefEditor::class)
            ->call('openForm')
            ->set('title', 'New Brief')
            ->set('body', 'Body text')
            ->set('file_media_item_id', $media->id)
            ->call('save');

        $brief = PolicyBrief::firstOrFail();
        $this->assertSame($media->id, $brief->file_media_item_id);
    }
}
