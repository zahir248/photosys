<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Album;

class SetAlbumCoverImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'albums:set-cover-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set cover images for albums that don\'t have them by using their first photo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting cover images for albums...');
        
        $albums = Album::whereNull('cover_image')->with('photos')->get();
        $processed = 0;
        
        foreach ($albums as $album) {
            if ($album->photos->count() > 0) {
                $album->setFirstPhotoAsCover();
                $processed++;
                $this->line("Set cover for album: {$album->name}");
            }
        }
        
        $this->info("Processed {$processed} albums.");
    }
}
