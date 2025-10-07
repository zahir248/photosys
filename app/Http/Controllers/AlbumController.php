<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Album;
use App\Models\Organization;
use App\Models\Photo;

class AlbumController extends Controller
{
    /**
     * Display a listing of albums for the authenticated user's organizations.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $organizationId = $request->get('organization_id');
        
        // Get only personal albums (no organization)
        $query = Album::where('user_id', $user->id)
                     ->whereNull('organization_id');

        $albums = $query->with(['photos'])->paginate(12);
        $organizations = $user->organizations;

        return view('albums.index', compact('albums', 'organizations', 'organizationId'));
    }

    /**
     * Show the form for creating a new album.
     */
    public function create()
    {
        $user = Auth::user();
        $organizations = $user->organizations;

        return view('albums.create', compact('organizations'));
    }

    /**
     * Store a newly created album.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'organization_id' => 'nullable|exists:organizations,id',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
            ]);

            $user = Auth::user()->load('limits');
            
            // Check user limits before proceeding
            $userLimits = $user->limits;
            if ($userLimits && !$userLimits->canCreateAlbums()) {
                $currentAlbums = $userLimits->current_albums;
                $maxAlbums = $userLimits->max_albums;
                $message = "Personal album limit reached! Current: {$currentAlbums}/{$maxAlbums} albums. Contact an administrator to increase your limit.";
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 403);
                }
                return back()->withErrors(['name' => $message]);
            }
            
            // Check if user has access to the organization (only if organization_id is provided)
            if ($request->organization_id) {
                $organization = Organization::findOrFail($request->organization_id);
                if ($organization->owner_id !== $user->id && !$organization->users->contains($user)) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'You do not have access to this organization.'
                        ], 403);
                    }
                    return back()->withErrors(['organization_id' => 'You do not have access to this organization.']);
                }
                
                // Check organization limits for album creation
                $orgLimits = $organization->getLimits();
                if ($orgLimits && !$orgLimits->canCreateAlbums()) {
                    $currentAlbums = $orgLimits->current_albums;
                    $maxAlbums = $orgLimits->max_albums;
                    $message = "Organization album limit reached! Current: {$currentAlbums}/{$maxAlbums} albums. Contact an administrator to increase the limit.";
                    
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $message
                        ], 403);
                    }
                    return back()->withErrors(['name' => $message]);
                }
            }

            // Handle cover image upload
            $coverImagePath = null;
            if ($request->hasFile('cover_image')) {
                $coverImagePath = $request->file('cover_image')->store('album-covers', 'public');
            }

            $album = Album::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'description' => $request->description,
                'organization_id' => $request->organization_id,
                'cover_image' => $coverImagePath,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Album created successfully!',
                    'album' => $album
                ]);
            }

            return redirect()->route('albums.show', $album->name)->with('success', 'Album created successfully!');
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
                    'message' => 'An error occurred while creating the album: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * Display the specified album.
     */
    public function show($name)
    {
        $user = Auth::user();
        $from = request('from');
        $orgName = request('org');
        
        $query = Album::where('name', $name);
        
        if ($from === 'organization' && $orgName) {
            // For organization albums
            $organization = Organization::where('name', $orgName)->firstOrFail();
            
            // Check if user is the owner or a member of the organization
            if ($organization->owner_id !== $user->id && !$organization->users->contains($user)) {
                abort(403, 'You do not have permission to view this album.');
            }
            
            $query->where('organization_id', $organization->id);
        } else {
            // For personal albums
            $query->where('user_id', $user->id)->whereNull('organization_id');
        }
        
        $album = $query->firstOrFail();
        $album->load(['organization', 'photos.user']);
        $medias = $album->photos()->paginate(12);

        return view('albums.show', compact('album', 'medias'));
    }

    /**
     * Get album data for modal editing.
     */
    public function editData($name)
    {
        try {
            $user = Auth::user();
            $from = request('from');
            $orgName = request('org');
            
            $query = Album::where('name', $name);
            
            if ($from === 'organization' && $orgName) {
                // For organization albums
                $organization = Organization::where('name', $orgName)->firstOrFail();
                
                // Check if user is the owner or a member of the organization
                if ($organization->owner_id !== $user->id && !$organization->users->contains($user)) {
                    abort(403, 'You do not have permission to view this album.');
                }
                
                $query->where('organization_id', $organization->id);
            } else {
                // For personal albums
                $query->where('user_id', $user->id)->whereNull('organization_id');
            }
            
            $album = $query->firstOrFail();
            $album->load(['organization', 'photos', 'user']);
            
            // Calculate total file size of all photos in the album
            $totalFileSize = $album->photos->sum('size_bytes');
            
            return response()->json([
                'success' => true,
                'album' => [
                    'id' => $album->id,
                    'name' => $album->name,
                    'description' => $album->description,
                    'organization_id' => $album->organization_id,
                    'created_at' => $album->created_at,
                    'photos_count' => $album->photos->count(),
                    'total_size' => $totalFileSize,
                    'owner' => $album->user,
                    'organization' => $album->organization ? $album->organization->name : 'Personal',
                    'cover_image_url' => $album->cover_image_url,
                    'has_cover_image' => $album->hasCoverImage()
                ],
                'organizations' => $user->organizations->map(function($org) {
                    return [
                        'id' => $org->id,
                        'name' => $org->name
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Album not found or access denied'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified album.
     */
    public function edit($name)
    {
        $user = Auth::user();
        
        $album = Album::where('name', $name)->where('user_id', $user->id)->firstOrFail();

        $organizations = $user->organizations;

        return view('albums.edit', compact('album', 'organizations'));
    }

    /**
     * Update the specified album.
     */
    public function update(Request $request, $name)
    {
        try {
            $user = Auth::user();
            
            $album = Album::where('name', $name)->where('user_id', $user->id)->firstOrFail();

            $request->validate([
                'name' => 'required|string|max:255|unique:albums,name,' . $album->id,
                'description' => 'nullable|string',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
            ]);

            // Handle cover image upload
            $updateData = [
                'name' => $request->name,
                'description' => $request->description,
            ];

            if ($request->hasFile('cover_image')) {
                // Delete old cover image if it exists
                if ($album->cover_image && Storage::disk('public')->exists($album->cover_image)) {
                    Storage::disk('public')->delete($album->cover_image);
                }
                
                // Store new cover image
                $coverImagePath = $request->file('cover_image')->store('album-covers', 'public');
                $updateData['cover_image'] = $coverImagePath;
            }

            $album->update($updateData);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Album updated successfully!'
                ]);
            }

            return redirect()->route('albums.show', $album->name)->with('success', 'Album updated successfully!');
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
                    'message' => 'An error occurred while updating the album: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * Remove the specified album.
     */
    public function destroy(Request $request, $name)
    {
        try {
            $user = Auth::user();
            $from = request('from');
            $orgName = request('org');
            
            $query = Album::where('name', $name);
            
            if ($from === 'organization' && $orgName) {
                // For organization albums
                $organization = Organization::where('name', $orgName)->firstOrFail();
                
                // Check if user is the owner or a member of the organization
                if ($organization->owner_id !== $user->id && !$organization->users->contains($user)) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'You do not have permission to delete this album.'
                        ], 403);
                    }
                    abort(403, 'You do not have permission to delete this album.');
                }
                
                $query->where('organization_id', $organization->id);
            } else {
                // For personal albums
                $query->where('user_id', $user->id)->whereNull('organization_id');
            }
            
            $album = $query->firstOrFail();

            // Remove all photos from this album (don't delete photos)
            $album->photos()->detach();

            // Delete the album
            $album->delete();

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Album deleted successfully! Photos have been moved to "No Album".'
                ]);
            }

            return redirect()->route('albums.index')->with('success', 'Album deleted successfully! Photos have been moved to "No Album".');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while deleting the album: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * Remove the cover image from the album.
     */
    public function removeCover(Request $request, $name)
    {
        try {
            $user = Auth::user();
            $from = request('from');
            $orgName = request('org');
            
            $query = Album::where('name', $name);
            
            if ($from === 'organization' && $orgName) {
                // For organization albums
                $organization = Organization::where('name', $orgName)->firstOrFail();
                
                // Check if user is the owner or a member of the organization
                if ($organization->owner_id !== $user->id && !$organization->users->contains($user)) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'You do not have permission to modify this album.'
                        ], 403);
                    }
                    abort(403, 'You do not have permission to modify this album.');
                }
                
                $query->where('organization_id', $organization->id);
            } else {
                // For personal albums
                $query->where('user_id', $user->id)->whereNull('organization_id');
            }
            
            $album = $query->firstOrFail();
            
            // Delete the cover image file if it exists
            if ($album->cover_image && Storage::disk('public')->exists($album->cover_image)) {
                Storage::disk('public')->delete($album->cover_image);
            }
            
            // Remove the cover image reference from the database
            $album->update(['cover_image' => null]);
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cover image removed successfully!'
                ]);
            }
            
            return redirect()->back()->with('success', 'Cover image removed successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while removing the cover image: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * Remove a photo from the album (but don't delete the photo).
     */
    public function removePhoto(Request $request, $name, $filename)
    {
        try {
            \Log::info('Remove photo request', ['album_name' => $name, 'filename' => $filename]);
            
            $user = Auth::user();
            $from = request('from');
            $orgName = request('org');
            
            // Decode the album name since it might contain URL-encoded characters
            $decodedName = urldecode($name);
            $query = Album::where('name', $decodedName);
            
            if ($from === 'organization' && $orgName) {
                // For organization albums
                $organization = Organization::where('name', $orgName)->firstOrFail();
                
                // Check if user is the owner or a member of the organization
                if ($organization->owner_id !== $user->id && !$organization->users->contains($user)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to remove photos from this album.'
                    ], 403);
                }
                
                $query->where('organization_id', $organization->id);
            } else {
                // For personal albums
                $query->where('user_id', $user->id)->whereNull('organization_id');
            }
            
            $album = $query->firstOrFail();
            \Log::info('Album found', ['album_id' => $album->id]);
            
            $media = Photo::where('filename', $filename)->whereHas('albums', function($query) use ($album) {
                $query->where('albums.id', $album->id);
            })->firstOrFail();
            
            \Log::info('Photo found', ['photo_id' => $media->id]);
            
            // Remove photo from album
            $media->albums()->detach($album->id);
            
            \Log::info('Photo removed from album successfully');
            
            return response()->json([
                'success' => true,
                'message' => 'Photo removed from album successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error removing photo from album', [
                'error' => $e->getMessage(),
                'album_name' => $name,
                'filename' => $filename
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove photo from album: ' . $e->getMessage()
            ], 500);
        }
    }
}
