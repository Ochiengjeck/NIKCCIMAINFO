<?php

namespace App\Http\Controllers;

use App\Models\MediaItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaUploadController extends Controller
{
    /**
     * Accept a file upload, store it, create a MediaItem record, return JSON.
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:51200',
            'alt_text' => 'nullable|string|max:255',
            'disk' => 'nullable|in:public,local',
            'folder' => 'nullable|string|max:255',
        ]);

        $disk = $request->input('disk', 'public');
        $mime = $request->file('file')->getMimeType();

        // Determine file type
        $isImage = str_starts_with($mime, 'image/');
        $isDoc = in_array($mime, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/csv',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ]);
        $type = $isImage ? 'image' : ($isDoc ? 'document' : 'other');

        // Determine storage folder
        if ($request->filled('folder')) {
            $folder = $request->input('folder');
        } elseif ($disk === 'public') {
            $folder = $isImage ? 'cms/images' : 'cms/downloads';
        } else {
            $folder = 'uploads';
        }

        $path = $request->file('file')->store($folder, $disk);
        $filename = basename($path);

        $item = MediaItem::create([
            'chapter_id' => auth()->user()->chapter_id,
            'filename' => $filename,
            'original_filename' => $request->file('file')->getClientOriginalName(),
            'path' => $path,
            'disk' => $disk,
            'mime_type' => $mime,
            'size' => $request->file('file')->getSize(),
            'alt_text' => $request->input('alt_text') ?: null,
            'type' => $type,
            'uploaded_by' => auth()->id(),
        ]);

        // For public disk, return the storage URL; for local, return the download route URL
        $url = $disk === 'public'
            ? Storage::disk('public')->url($path)
            : route('admin.media.download', $item->id);

        return response()->json([
            'id' => $item->id,
            'url' => $url,
            'path' => $path,
            'filename' => $filename,
            'original_filename' => $item->original_filename,
            'mime_type' => $mime,
            'size' => $item->size,
            'human_size' => $item->humanSize(),
            'type' => $type,
            'disk' => $disk,
        ], 201);
    }

    /**
     * Stream-download a private (local-disk) media item.
     */
    public function download(int $id)
    {
        $item = MediaItem::findOrFail($id);

        abort_if($item->disk !== 'local', 404);

        return Storage::disk('local')->download($item->path, $item->original_filename);
    }
}
