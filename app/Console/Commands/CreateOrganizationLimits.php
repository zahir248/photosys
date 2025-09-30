<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Organization;

class CreateOrganizationLimits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'organizations:create-limits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create limits for organizations that don\'t have them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating limits for organizations...');
        
        // Get all organizations without limits
        $organizations = Organization::whereDoesntHave('limits')->get();
        
        $this->info("Found {$organizations->count()} organizations without limits");
        
        foreach ($organizations as $organization) {
            $organization->getLimits();
            $this->line("Created limits for organization: {$organization->name}");
        }
        
        $this->info('Done!');
        
        return 0;
    }
}