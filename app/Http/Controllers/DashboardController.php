<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Photo;
use App\Models\Album;
use App\Models\Organization;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user()->load('limits');
        
        // Get user's recent photos (personal only, no organization photos)
        $recent_photos = Photo::where('user_id', $user->id)
            ->whereIn('visibility', ['private', 'public'])
            ->with(['albums', 'organization', 'user'])
            ->latest()
            ->take(8)
            ->get();

        // Get user's albums (personal only, no organization albums)
        $albums = Album::where('user_id', $user->id)
            ->whereNull('organization_id')
            ->with(['organization', 'photos'])
            ->latest()
            ->take(6)
            ->get();

        // Get detailed stats (personal data only, excluding organization data)
        $stats = [
            // Basic counts (personal only)
            'photos_count' => Photo::where('user_id', $user->id)->whereNull('organization_id')->count(),
            'albums_count' => Album::where('user_id', $user->id)->whereNull('organization_id')->count(),
            'organizations_count' => $user->organizations()->count(),
            'public_photos_count' => Photo::where('user_id', $user->id)
                                        ->whereNull('organization_id')
                                        ->where('visibility', 'public')
                                        ->count(),

            // Storage stats by photo type (personal only)
            'private_photos_size' => Photo::where('user_id', $user->id)
                                        ->whereNull('organization_id')
                                        ->where('visibility', 'private')
                                        ->sum('size_bytes'),
            'public_photos_size' => Photo::where('user_id', $user->id)
                                        ->whereNull('organization_id')
                                        ->where('visibility', 'public')
                                        ->sum('size_bytes'),
            'org_photos_size' => Photo::whereIn('organization_id', function($query) use ($user) {
                                        $query->select('organizations.id')
                                              ->from('organizations')
                                              ->where('organizations.owner_id', $user->id)
                                              ->orWhereExists(function($subQuery) use ($user) {
                                                  $subQuery->select('organization_user.organization_id')
                                                          ->from('organization_user')
                                                          ->whereColumn('organization_user.organization_id', 'organizations.id')
                                                          ->where('organization_user.user_id', $user->id);
                                              });
                                    })
                                    ->sum('size_bytes'),

            // Organization stats - include both owned and member organizations
            'organizations' => $user->organizations()
                ->withCount(['photos', 'albums'])
                ->get()
                ->merge($user->ownedOrganizations()
                    ->withCount(['photos', 'albums'])
                    ->get())
                ->unique('id')
                ->map(function($org) use ($user) {
                    // Total size of ALL photos in this organization (regardless of who uploaded them)
                    $totalSize = Photo::where('organization_id', $org->id)
                        ->sum('size_bytes');

                    // Size of photos that are in at least one album under this org
                    $albumsSize = Photo::where('organization_id', $org->id)
                        ->whereHas('albums', function($q) use ($org) {
                            $q->where('organization_id', $org->id);
                        })
                        ->sum('size_bytes');

                    // Size of photos with no albums (unorganized)
                    $unorganizedSize = Photo::where('organization_id', $org->id)
                        ->doesntHave('albums')
                        ->sum('size_bytes');

                    // Count of unorganized photos
                    $unorganizedCount = Photo::where('organization_id', $org->id)
                        ->doesntHave('albums')
                        ->count();

                    // Per-album breakdown for this organization
                    $albumsBreakdown = Album::where('organization_id', $org->id)
                        ->with(['photos' => function($q) {
                            $q->select('photos.id', 'size_bytes');
                        }])
                        ->get()
                        ->map(function($album) {
                            return [
                                'name' => $album->name,
                                'photos_count' => $album->photos->count(),
                                'total_size' => $album->photos->sum('size_bytes')
                            ];
                        });

                    return [
                        'name' => $org->name,
                        'photos_count' => $org->photos_count,
                        'albums_count' => $org->albums_count,
                        'total_size' => $totalSize,
                        'albums_size' => $albumsSize,
                        'unorganized_size' => $unorganizedSize,
                        'unorganized_count' => $unorganizedCount,
                        'albums_breakdown' => $albumsBreakdown,
                    ];
                }),

            // Album stats (personal albums only)
            'albums' => Album::where('user_id', $user->id)
                ->whereNull('organization_id')
                ->withCount('photos')
                ->with(['photos' => function($q) {
                    $q->select('photos.id', 'size_bytes');
                }])
                ->get()
                ->map(function($album) {
                    return [
                        'name' => $album->name,
                        'photos_count' => $album->photos_count,
                        'total_size' => $album->photos->sum('size_bytes'),
                        'organization' => 'Personal'
                    ];
                })
        ];

        return view('dashboard', compact('recent_photos', 'albums', 'stats'));
    }
}
