<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get the first user to assign to existing albums
        $firstUser = DB::table('users')->first();
        
        if ($firstUser) {
            // Update all existing albums to have the first user as owner
            DB::table('albums')
                ->whereNull('user_id')
                ->update(['user_id' => $firstUser->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set user_id to null for all albums
        DB::table('albums')->update(['user_id' => null]);
    }
};
