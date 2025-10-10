<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Photo;
use App\Models\Album;
use App\Models\Organization;
use App\Models\Tag;

class MediaController extends Controller
{
    /**
     * Detect media type based on MIME type and file extension.
     */
    private function detectMediaType($mimeType, $extension)
    {
        $imageMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/svg+xml', 'image/tiff'];
        $videoMimes = ['video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/flv', 'video/webm', 'video/mkv', 'video/quicktime'];
        $audioMimes = ['audio/mp3', 'audio/wav', 'audio/ogg', 'audio/aac', 'audio/flac', 'audio/m4a', 'audio/wma'];
        $documentMimes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        $archiveMimes = ['application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed', 'application/gzip', 'application/x-tar'];
        
        if (in_array($mimeType, $imageMimes)) {
            return 'image';
        } elseif (in_array($mimeType, $videoMimes)) {
            return 'video';
        } elseif (in_array($mimeType, $audioMimes)) {
            return 'audio';
        } elseif (in_array($mimeType, $documentMimes)) {
            return 'document';
        } elseif (in_array($mimeType, $archiveMimes)) {
            return 'archive';
        }
        
        // Fallback based on file extension
        $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'tiff'];
        $videoExts = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv', 'qt'];
        $audioExts = ['mp3', 'wav', 'ogg', 'aac', 'flac', 'm4a', 'wma'];
        $documentExts = ['pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx'];
        $archiveExts = ['zip', 'rar', '7z', 'gz', 'tar'];
        
        $ext = strtolower($extension);
        
        if (in_array($ext, $imageExts)) {
            return 'image';
        } elseif (in_array($ext, $videoExts)) {
            return 'video';
        } elseif (in_array($ext, $audioExts)) {
            return 'audio';
        } elseif (in_array($ext, $documentExts)) {
            return 'document';
        } elseif (in_array($ext, $archiveExts)) {
            return 'archive';
        }
        
        return 'other';
    }
    /**
     * Display a listing of all media accessible to the authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $organizationId = $request->get('organization_id');
        
        // Get only the user's own personal media (private and public, no organization media)
        $query = Photo::where('user_id', $user->id) // Only user's own photos
                     ->whereNull('organization_id') // Exclude organization media
                     ->whereIn('visibility', ['private', 'public']); // Only private and public visibility

        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        $media = $query->with(['albums', 'organization', 'user'])->latest()->paginate(12);
        $organizations = $user->organizations;

        return view('media.index', compact('media', 'organizations', 'organizationId'));
    }


    /**
     * Show the form for uploading new media.
     */
    public function create()
    {
        $user = Auth::user();
        $organizations = $user->organizations;
        $albums = $user->albums()->with('organization')->get();

        return view('media.create', compact('organizations', 'albums'));
    }

    /**
     * Show the form for uploading a new photo to a specific album.
     */
    public function createWithAlbum($albumName)
    {
        $user = Auth::user();
        $organizations = $user->organizations;
        $albums = $user->albums()->with('organization')->get();
        
        // Find the specific album
        $selectedAlbum = $user->albums()->where('name', $albumName)->firstOrFail();

        return view('media.create', compact('organizations', 'albums', 'selectedAlbum'));
    }

    /**
     * Store newly uploaded media files.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'photos' => 'required|array|min:1',
                'photos.*' => 'required|file|max:102400', // 100MB max per file, all file types
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'organization_id' => 'nullable|exists:organizations,id',
                'album_ids' => $request->organization_id ? 'required|array|min:1' : 'nullable|array',
                'album_ids.*' => 'exists:albums,id',
                'visibility' => 'required|in:private,public,org',
                'tags' => 'nullable|array|max:2',
                'tags.*' => 'string|max:50',
            ], [
                'photos.required' => 'Please select at least one media file to upload.',
                'photos.array' => 'Media must be uploaded as files.',
                'photos.*.required' => 'Each media file is required.',
                'photos.*.file' => 'Each file must be a valid file.',
                'photos.*.max' => 'Each media file must not exceed 100MB.',
            ]);

            $user = Auth::user()->load('limits');
            $organization = null;
            
            // Check user limits only for personal uploads (not organization uploads)
            $userLimits = $user->limits;
            if ($userLimits && !$request->organization_id) {
                $files = $request->file('photos');
                $fileCount = count($files);
                $totalFileSizeMb = 0;
                
                // Calculate total file size for all files
                foreach ($files as $file) {
                    $totalFileSizeMb += round($file->getSize() / (1024 * 1024), 2);
                }
                
                // Check photo count limit (considering all files being uploaded)
                if (!$userLimits->unlimited_photos) {
                    $currentPhotos = $userLimits->current_photos;
                    if (($currentPhotos + $fileCount) > $userLimits->max_photos) {
                        $maxPhotos = $userLimits->max_photos;
                        $message = "Personal photo limit would be exceeded! Current: {$currentPhotos}/{$maxPhotos} media. Trying to upload {$fileCount} more media. Contact an administrator to increase your limit.";
                        
                        if ($request->ajax() || $request->wantsJson()) {
                            return response()->json([
                                'success' => false,
                                'message' => $message
                            ], 403);
                        }
                        return back()->withErrors(['photos' => $message]);
                    }
                }
                
                // Check storage limit (considering total size of all files)
                if (!$userLimits->unlimited_storage) {
                    $currentStorage = $userLimits->current_storage_mb;
                    if (($currentStorage + $totalFileSizeMb) > $userLimits->max_storage_mb) {
                        $maxStorage = $userLimits->max_storage_mb;
                        $message = "Personal storage limit would be exceeded! Current: {$currentStorage}MB/{$maxStorage}MB. Total upload size: {$totalFileSizeMb}MB. Contact an administrator to increase your limit.";
                        
                        if ($request->ajax() || $request->wantsJson()) {
                            return response()->json([
                                'success' => false,
                                'message' => $message
                            ], 403);
                        }
                        return back()->withErrors(['photos' => $message]);
                    }
                }
            }
            
            // Check if user has access to the organization (if provided)
            if ($request->organization_id) {
                $organization = Organization::findOrFail($request->organization_id);
                if (!$organization->users->contains($user)) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'You do not have access to this organization.'
                        ], 403);
                    }
                    return back()->withErrors(['organization_id' => 'You do not have access to this organization.']);
                }

                // Check organization limits for photo uploads
                $orgLimits = $organization->getLimits();
                if ($orgLimits) {
                    $files = $request->file('photos');
                    $fileCount = count($files);
                    $totalFileSizeMb = 0;
                    
                    // Calculate total file size for all files
                    foreach ($files as $file) {
                        $totalFileSizeMb += round($file->getSize() / (1024 * 1024), 2);
                    }
                    
                    // Check photo count limit (considering all files being uploaded)
                    if (!$orgLimits->unlimited_photos) {
                        $currentPhotos = $orgLimits->current_photos;
                        if (($currentPhotos + $fileCount) > $orgLimits->max_photos) {
                            $maxPhotos = $orgLimits->max_photos;
                            $message = "Organization photo limit would be exceeded! Current: {$currentPhotos}/{$maxPhotos} media. Trying to upload {$fileCount} more media. Contact an administrator to increase the limit.";
                            
                            if ($request->ajax() || $request->wantsJson()) {
                                return response()->json([
                                    'success' => false,
                                    'message' => $message
                                ], 403);
                            }
                            return back()->withErrors(['photos' => $message]);
                        }
                    }
                    
                    // Check storage limit (considering total size of all files)
                    if (!$orgLimits->unlimited_storage) {
                        $currentStorage = $orgLimits->current_storage_mb;
                        if (($currentStorage + $totalFileSizeMb) > $orgLimits->max_storage_mb) {
                            $maxStorage = $orgLimits->max_storage_mb;
                            $message = "Organization storage limit would be exceeded! Current: {$currentStorage}MB/{$maxStorage}MB. Total upload size: {$totalFileSizeMb}MB. Contact an administrator to increase the limit.";
                            
                            if ($request->ajax() || $request->wantsJson()) {
                                return response()->json([
                                    'success' => false,
                                    'message' => $message
                                ], 403);
                            }
                            return back()->withErrors(['photos' => $message]);
                        }
                    }
                }

                // Check if albums belong to the organization
                if ($request->album_ids) {
                    foreach ($request->album_ids as $albumId) {
                        $album = Album::findOrFail($albumId);
                        if ($album->organization_id !== $organization->id) {
                            if ($request->ajax() || $request->wantsJson()) {
                                return response()->json([
                                    'success' => false,
                                    'message' => 'One or more albums do not belong to the selected organization.'
                                ], 422);
                            }
                            return back()->withErrors(['album_ids' => 'One or more albums do not belong to the selected organization.']);
                        }
                    }
                }
            }

            $files = $request->file('photos');
            $uploadedMedia = [];
            
            // Additional validation for each file
            foreach ($files as $index => $file) {
                if (!$file || !$file->isValid()) {
                    throw new \Illuminate\Validation\ValidationException(
                        validator([], []),
                        ['photos.' . $index => ['The file at position ' . ($index + 1) . ' is invalid or corrupted.']]
                    );
                }
                
                if ($file->getSize() === 0) {
                    throw new \Illuminate\Validation\ValidationException(
                        validator([], []),
                        ['photos.' . $index => ['The file at position ' . ($index + 1) . ' is empty.']]
                    );
                }
            }
            
            foreach ($files as $file) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $mimeType = $file->getMimeType();
                $extension = $file->getClientOriginalExtension();
                $photoType = $this->detectMediaType($mimeType, $extension);
                
                // Determine storage path based on organization
                if ($organization) {
                    $storagePath = "media/{$organization->id}/{$filename}";
                    $file->storeAs("media/{$organization->id}", $filename, 'public');
                } else {
                    $storagePath = "media/personal/{$user->id}/{$filename}";
                    $file->storeAs("media/personal/{$user->id}", $filename, 'public');
                }

                // Create media record - use filename as title if no title provided or multiple files
                $photoTitle = $request->title;
                if (!$photoTitle || count($files) > 1) {
                    $photoTitle = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                }

                $photo = Photo::create([
                    'organization_id' => $request->organization_id,
                    'user_id' => $user->id,
                    'filename' => $filename, // Use unique generated filename
                    'title' => $photoTitle,
                    'description' => $request->description,
                    'storage_path' => $storagePath,
                    'mime' => $mimeType,
                    'media_type' => $photoType,
                    'file_extension' => $extension,
                    'size_bytes' => $file->getSize(),
                    'visibility' => $request->visibility,
                ]);

                // Attach albums if provided
                if ($request->album_ids) {
                    $photo->albums()->attach($request->album_ids);
                }
                
                // Handle tags
                if ($request->tags && count($request->tags) > 0) {
                    $tagIds = [];
                    foreach ($request->tags as $tagName) {
                        $tagName = trim($tagName);
                        if ($tagName) {
                            $tag = Tag::firstOrCreate(
                                ['name' => strtolower($tagName)],
                                ['color' => '#6c757d']
                            );
                            $tagIds[] = $tag->id;
                        }
                    }
                    if (!empty($tagIds)) {
                        $photo->tags()->attach($tagIds);
                    }
                }
                
                $uploadedPhotos[] = $photo;
            }

            $photoCount = count($uploadedPhotos);
            $message = $photoCount === 1 
                ? 'Media uploaded successfully!' 
                : "{$photoCount} media files uploaded successfully!";

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'photos' => $uploadedPhotos
                ]);
            }

            return redirect()->route('media.show', $uploadedPhotos[0]->filename)->with('success', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while uploading the photos: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * Display the specified photo.
     */
    public function show($filename)
    {
        $user = Auth::user();
        
        $photo = Photo::where('filename', $filename)->firstOrFail();
        
        if (!$photo->isAccessibleBy($user)) {
            abort(403, 'You do not have permission to view this photo.');
        }

        $photo->load(['albums', 'organization', 'user']);

        return view('media.show', compact('photo'));
    }

    /**
     * Download the specified photo.
     */
    public function download($filename)
    {
        $user = Auth::user();
        
        // Log the download attempt for debugging
        \Log::info('Download attempt', [
            'filename' => $filename,
            'user_id' => $user ? $user->id : 'not authenticated',
            'ip' => request()->ip()
        ]);
        
        $photo = Photo::where('filename', $filename)->firstOrFail();
        
        if (!$photo->isAccessibleBy($user)) {
            \Log::warning('Download denied - no permission', [
                'filename' => $filename,
                'user_id' => $user ? $user->id : 'not authenticated',
                'photo_user_id' => $photo->user_id,
                'photo_visibility' => $photo->visibility
            ]);
            abort(403, 'You do not have permission to download this photo.');
        }

        $filePath = storage_path('app/public/' . $photo->storage_path);
        
        if (!file_exists($filePath)) {
            \Log::error('Download failed - file not found', [
                'filename' => $filename,
                'file_path' => $filePath
            ]);
            abort(404, 'Photo file not found.');
        }

        \Log::info('Download successful', [
            'filename' => $filename,
            'user_id' => $user->id
        ]);

        return response()->download($filePath, $photo->filename);
    }

    /**
     * Display a public shared photo.
     */
    public function share($token)
    {
        $photo = Photo::where('share_token', $token)->firstOrFail();
        
        if ($photo->visibility !== 'public') {
            abort(404, 'Photo not found.');
        }

        $photo->load(['albums', 'organization', 'user']);

        return view('media.share', compact('photo'));
    }

    /**
     * Remove the specified photo from storage.
     */
    public function destroy(Request $request, $filename)
    {
        $user = Auth::user();
        
        $photo = Photo::where('filename', $filename)->firstOrFail();
        
        // Only the uploader or organization owner can delete
        if ($photo->user_id !== $user->id && ($photo->organization && $photo->organization->owner_id !== $user->id)) {
            abort(403, 'You do not have permission to delete this photo.');
        }

        // Delete the file
        Storage::disk('public')->delete($photo->storage_path);
        
        // Delete the record
        $photo->delete();

        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Media deleted successfully!'
            ]);
        }

        return redirect()->route('media.index')->with('success', 'Media deleted successfully!');
    }

    /**
     * Show the form for editing the specified photo.
     */
    public function edit($filename)
    {
        $user = Auth::user();
        
        $photo = Photo::where('filename', $filename)->firstOrFail();
        
        // Check if user has access to this photo
        if (!$photo->isAccessibleBy($user)) {
            abort(403, 'You do not have permission to edit this photo.');
        }

        // Get user's organizations and albums for the form
        $organizations = $user->organizations;
        $albums = Album::whereHas('organization.users', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('organization')->get();

        return view('media.edit', compact('photo', 'organizations', 'albums'));
    }

    /**
     * Get photo data for modal editing.
     */
    public function editData($filename)
    {
        try {
            $user = Auth::user();
            $from = request('from');
            $orgName = request('org');
            
            $photo = Photo::where('filename', $filename)->firstOrFail();
            
            // Check if user has access to this photo
            if (!$photo->isAccessibleBy($user)) {
                return response()->json(['error' => 'You do not have permission to edit this photo.'], 403);
            }

            // Get albums based on context
            if ($from === 'organization' && $orgName) {
                // For organization context, get organization albums
                $organization = Organization::where('name', $orgName)->firstOrFail();
                
                // Check if user is a member of the organization
                if (!$organization->users->contains($user)) {
                    return response()->json(['error' => 'You do not have permission to access this organization.'], 403);
                }
                
                $albums = Album::where('organization_id', $organization->id)->get();
            } else {
                // For personal context, get personal albums
                $albums = Album::where('user_id', $user->id)
                             ->whereNull('organization_id')
                             ->get();
            }

            \Log::info('Photo editData response', [
                'photo_id' => $photo->id,
                'photo_visibility' => $photo->visibility,
                'photo_organization_id' => $photo->organization_id,
                'context_from' => $from,
                'context_org' => $orgName
            ]);

            return response()->json([
                'photo' => [
                    'id' => $photo->id,
                    'title' => $photo->title,
                    'description' => $photo->description,
                    'url' => $photo->url,
                    'visibility' => $photo->visibility,
                    'organization_id' => $photo->organization_id,
                    'album_ids' => $photo->albums->pluck('id'),
                    'mime' => $photo->mime,
                    'size_bytes' => $photo->size_bytes,
                    'created_at' => $photo->created_at,
                    'albums' => $photo->albums,
                    'organization' => $photo->organization,
                    'user' => $photo->user,
                    'tags' => $photo->tags
                ],
                'albums' => $albums
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in editData: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while loading photo data.'], 500);
        }
    }

    /**
     * Update the specified photo in storage.
     */
    public function update(Request $request, $filename)
    {
        $user = Auth::user();
        
        $photo = Photo::where('filename', $filename)->firstOrFail();
        
        // Check if user has access to this photo
        if (!$photo->isAccessibleBy($user)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to edit this photo.'
                ], 403);
            }
            abort(403, 'You do not have permission to edit this photo.');
        }

        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'organization_id' => 'nullable|exists:organizations,id',
                'album_ids' => 'nullable|array',
                'album_ids.*' => 'exists:albums,id',
                'visibility' => 'required|in:private,public,org',
                'tags' => 'nullable|array|max:2',
                'tags.*' => 'string|max:50',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        // Check if user has access to the selected organization (if provided)
        if ($request->organization_id) {
            $organization = Organization::findOrFail($request->organization_id);
            if (!$organization->users->contains($user->id)) {
                return back()->withErrors(['organization_id' => 'You do not have access to this organization.']);
            }

            // Check if user has access to the selected albums (if provided)
            if ($request->album_ids) {
                foreach ($request->album_ids as $albumId) {
                    $album = Album::findOrFail($albumId);
                    if (!$album->organization->users->contains($user->id)) {
                        if ($request->ajax() || $request->wantsJson()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'You do not have access to one or more selected albums.'
                            ], 403);
                        }
                        return back()->withErrors(['album_ids' => 'You do not have access to one or more selected albums.']);
                    }
                    
                    // Ensure album belongs to the selected organization
                    if ($album->organization_id != $request->organization_id) {
                        if ($request->ajax() || $request->wantsJson()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'One or more selected albums do not belong to the selected organization.'
                            ], 422);
                        }
                        return back()->withErrors(['album_ids' => 'One or more selected albums do not belong to the selected organization.']);
                    }
                }
            }
        }

        // Update the photo
        try {
            $photo->update([
                'title' => $request->title,
                'description' => $request->description,
                'organization_id' => $request->organization_id,
                'visibility' => $request->visibility,
            ]);

            // Sync albums
            $photo->albums()->sync($request->album_ids ?: []);
            
            // Handle tags
            if ($request->tags && count($request->tags) > 0) {
                $tagIds = [];
                foreach ($request->tags as $tagName) {
                    $tagName = trim($tagName);
                    if ($tagName) {
                        $tag = Tag::firstOrCreate(
                            ['name' => strtolower($tagName)],
                            ['color' => '#6c757d']
                        );
                        $tagIds[] = $tag->id;
                    }
                }
                $photo->tags()->sync($tagIds);
            } else {
                // Remove all tags if none provided
                $photo->tags()->sync([]);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Photo updated successfully!',
                    'photo' => $photo
                ]);
            }

            return redirect()->route('media.show', $photo->filename)->with('success', 'Photo updated successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating the photo: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }
}
