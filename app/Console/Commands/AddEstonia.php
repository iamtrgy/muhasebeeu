<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;

class AddEstonia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:add-estonia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Estonia to the countries table if it does not exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking if Estonia exists...');
        
        $exists = Country::where('code', 'EE')->exists();
        
        if (!$exists) {
            Country::create([
                'name' => 'Estonia',
                'code' => 'EE'
            ]);
            $this->info('Estonia added successfully!');
        } else {
            $this->info('Estonia already exists in the database.');
        }
        
        return Command::SUCCESS;
    }
}
