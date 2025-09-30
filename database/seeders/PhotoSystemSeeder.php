<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Organization;
use App\Models\Album;
use App\Models\Photo;

class PhotoSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo users
        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create organizations
        $personalOrg = Organization::create([
            'name' => 'John\'s Personal',
            'type' => 'personal',
            'owner_id' => $user1->id,
        ]);

        $teamOrg = Organization::create([
            'name' => 'Marketing Team',
            'type' => 'team',
            'owner_id' => $user1->id,
        ]);

        // Add users to organizations
        $personalOrg->users()->attach($user1->id, ['role' => 'owner']);
        $teamOrg->users()->attach($user1->id, ['role' => 'owner']);
        $teamOrg->users()->attach($user2->id, ['role' => 'member']);

        // Create albums
        $vacationAlbum = Album::create([
            'user_id' => $user1->id,
            'name' => 'Summer Vacation 2024',
            'description' => 'Photos from our amazing summer vacation',
            'organization_id' => $personalOrg->id,
        ]);

        $workAlbum = Album::create([
            'user_id' => $user1->id,
            'name' => 'Product Photos',
            'description' => 'Product photography for marketing materials',
            'organization_id' => $teamOrg->id,
        ]);

        $eventAlbum = Album::create([
            'user_id' => $user1->id,
            'name' => 'Company Events',
            'description' => 'Photos from various company events and meetings',
            'organization_id' => $teamOrg->id,
        ]);

        // Create some demo photos (without actual files for now)
        $photos = [
            [
                'filename' => 'beach_sunset.jpg',
                'storage_path' => 'photos/demo/beach_sunset.jpg',
                'mime' => 'image/jpeg',
                'size_bytes' => 2048000,
                'visibility' => 'org',
                'album_id' => $vacationAlbum->id,
                'organization_id' => $personalOrg->id,
                'user_id' => $user1->id,
            ],
            [
                'filename' => 'mountain_view.jpg',
                'storage_path' => 'photos/demo/mountain_view.jpg',
                'mime' => 'image/jpeg',
                'size_bytes' => 1536000,
                'visibility' => 'public',
                'album_id' => $vacationAlbum->id,
                'organization_id' => $personalOrg->id,
                'user_id' => $user1->id,
            ],
            [
                'filename' => 'product_shot_1.jpg',
                'storage_path' => 'photos/demo/product_shot_1.jpg',
                'mime' => 'image/jpeg',
                'size_bytes' => 1024000,
                'visibility' => 'org',
                'album_id' => $workAlbum->id,
                'organization_id' => $teamOrg->id,
                'user_id' => $user1->id,
            ],
            [
                'filename' => 'product_shot_2.jpg',
                'storage_path' => 'photos/demo/product_shot_2.jpg',
                'mime' => 'image/jpeg',
                'size_bytes' => 1280000,
                'visibility' => 'org',
                'album_id' => $workAlbum->id,
                'organization_id' => $teamOrg->id,
                'user_id' => $user2->id,
            ],
            [
                'filename' => 'team_meeting.jpg',
                'storage_path' => 'photos/demo/team_meeting.jpg',
                'mime' => 'image/jpeg',
                'size_bytes' => 1800000,
                'visibility' => 'org',
                'album_id' => $eventAlbum->id,
                'organization_id' => $teamOrg->id,
                'user_id' => $user1->id,
            ],
            [
                'filename' => 'private_photo.jpg',
                'storage_path' => 'photos/demo/private_photo.jpg',
                'mime' => 'image/jpeg',
                'size_bytes' => 900000,
                'visibility' => 'private',
                'album_id' => null,
                'organization_id' => $personalOrg->id,
                'user_id' => $user1->id,
            ],
        ];

        foreach ($photos as $photoData) {
            Photo::create($photoData);
        }

        $this->command->info('Demo data created successfully!');
        $this->command->info('Demo users:');
        $this->command->info('- john@example.com / password');
        $this->command->info('- jane@example.com / password');
    }
}
