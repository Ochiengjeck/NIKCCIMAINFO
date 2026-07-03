<?php

namespace Tests\Feature;

use App\Livewire\Events\EventManager;
use App\Models\Chapter;
use App\Models\Event;
use App\Models\EventResource;
use App\Models\MediaItem;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EventResourceTest extends TestCase
{
    use RefreshDatabase;

    private function seedAdmin(Chapter $chapter): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create(['chapter_id' => $chapter->id]);
        $user->assignRole('super-admin');

        return $user;
    }

    private function makeEvent(Chapter $chapter, User $user): Event
    {
        return Event::create([
            'chapter_id' => $chapter->id,
            'organizer_id' => $user->id,
            'title' => 'Corridor Summit 2026',
            'type' => 'flagship',
            'starts_at' => now()->addDays(10),
            'ends_at' => now()->addDays(11),
            'status' => 'published',
        ]);
    }

    private function makeDocument(Chapter $chapter, User $user, string $name = 'keynote.pptx'): MediaItem
    {
        return MediaItem::create([
            'chapter_id' => $chapter->id,
            'filename' => 'abc123-'.$name,
            'original_filename' => $name,
            'path' => 'cms/events/abc123-'.$name,
            'disk' => 'public',
            'mime_type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'size' => 1048576,
            'type' => 'document',
            'uploaded_by' => $user->id,
        ]);
    }

    public function test_add_resource_via_picker_persists_free_and_paid_rows(): void
    {
        $chapter = Chapter::create(['name' => 'Nigeria', 'code' => 'NGA', 'country' => 'Nigeria']);
        $user = $this->seedAdmin($chapter);
        $this->actingAs($user);

        $event = $this->makeEvent($chapter, $user);
        $free = $this->makeDocument($chapter, $user, 'report.pdf');
        $paid = $this->makeDocument($chapter, $user, 'deck.pptx');

        Livewire::test(EventManager::class)
            ->call('edit', $event->id)
            // add first (free) resource through the flat picker
            ->set('resourcePickerId', $free->id)
            ->call('addResource')
            ->assertSet('resourcePickerId', null)
            ->assertCount('resources', 1)
            ->set('resources.0.title', 'Post-event Report')
            // add second (paid) resource
            ->set('resourcePickerId', $paid->id)
            ->call('addResource')
            ->assertCount('resources', 2)
            ->set('resources.1.title', 'Keynote Slides')
            ->set('resources.1.is_paid', true)
            ->set('resources.1.price', '20')
            ->set('resources.1.currency', 'USD')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('event_resources', 2);

        $report = EventResource::where('title', 'Post-event Report')->firstOrFail();
        $this->assertFalse($report->is_paid);
        $this->assertSame('Free', $report->priceLabel());
        $this->assertSame('cms/events/abc123-report.pdf', $report->file_path);

        $slides = EventResource::where('title', 'Keynote Slides')->firstOrFail();
        $this->assertTrue($slides->is_paid);
        $this->assertSame('USD 20.00', $slides->priceLabel());
        $this->assertSame($event->id, $slides->event_id);
    }

    public function test_editing_reloads_resources_and_resync_replaces_them(): void
    {
        $chapter = Chapter::create(['name' => 'Kenya', 'code' => 'KEN', 'country' => 'Kenya']);
        $user = $this->seedAdmin($chapter);
        $this->actingAs($user);

        $event = $this->makeEvent($chapter, $user);
        $event->resources()->create([
            'title' => 'Old File', 'file_path' => 'cms/events/old.pdf', 'file_name' => 'old.pdf',
            'is_paid' => false, 'currency' => 'USD', 'sort_order' => 0,
        ]);

        Livewire::test(EventManager::class)
            ->call('edit', $event->id)
            ->assertCount('resources', 1)
            ->call('removeResource', 0)
            ->assertCount('resources', 0)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('event_resources', 0);
    }
}
