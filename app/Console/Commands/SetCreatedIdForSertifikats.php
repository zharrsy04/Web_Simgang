<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SertifikatPKL;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SetCreatedIdForSertifikats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-created-id-for-sertifikats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate created_id for existing sertifikats (assign to first mentor)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get first mentor
        $mentor = User::where('role', 'mentor')->first();
        
        if (!$mentor) {
            $this->error('No mentor found in the system!');
            return 1;
        }

        $this->info('Using mentor: ' . $mentor->username . ' (ID: ' . $mentor->id . ')');

        // Update all NULL created_id to first mentor
        $updated = DB::table('tbl_sertifikat_pkl')
            ->whereNull('created_id')
            ->update(['created_id' => $mentor->id]);

        $this->info('Updated ' . $updated . ' sertifikats with created_id = ' . $mentor->id);
        return 0;
    }
}
