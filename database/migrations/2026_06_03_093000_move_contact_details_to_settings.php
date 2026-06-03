<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Move the chapter contact details + map embed URL from the Contact CMS page's
 * `sections` JSON into global `system_settings` (group: contact), then strip them
 * from the page so they no longer appear in the per-page CMS editor.
 */
return new class extends Migration
{
    /** key => default if the page didn't have a value */
    private array $keys = [
        'nigeria_address' => 'Abuja, Federal Capital Territory, Federal Republic of Nigeria',
        'nigeria_phone' => '',
        'nigeria_email' => 'nigeria@nikccima.org',
        'kenya_address' => 'Nairobi, Republic of Kenya',
        'kenya_phone' => '',
        'kenya_email' => 'kenya@nikccima.org',
        'map_embed_url' => '',
    ];

    public function up(): void
    {
        $page = DB::table('cms_pages')->where('slug', 'contact')->first();
        $sections = $page && $page->sections ? (json_decode($page->sections, true) ?: []) : [];

        foreach ($this->keys as $key => $default) {
            $value = $sections[$key] ?? $default;

            DB::table('system_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'group' => 'contact', 'updated_at' => now(), 'created_at' => now()]
            );
        }

        if ($page) {
            foreach (array_keys($this->keys) as $key) {
                unset($sections[$key]);
            }
            DB::table('cms_pages')->where('id', $page->id)->update(['sections' => json_encode($sections)]);
        }
    }

    public function down(): void
    {
        // Data migration — values now live in system_settings. Copy them back onto the
        // contact page sections so the editor would show them again if rolled back.
        $page = DB::table('cms_pages')->where('slug', 'contact')->first();
        if (! $page) {
            return;
        }

        $sections = $page->sections ? (json_decode($page->sections, true) ?: []) : [];
        foreach (array_keys($this->keys) as $key) {
            $sections[$key] = DB::table('system_settings')->where('key', $key)->value('value') ?? '';
        }
        DB::table('cms_pages')->where('id', $page->id)->update(['sections' => json_encode($sections)]);

        DB::table('system_settings')->whereIn('key', array_keys($this->keys))->where('group', 'contact')->delete();
    }
};
