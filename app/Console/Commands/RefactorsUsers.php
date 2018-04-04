<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Profile;
use App\User;
use Illuminate\Console\Command;

class RefactorsUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:refactor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (User::all() as $user) {
            if ($user->hasRoleFix('workers'))
                $user->profile()->create([]);
            if ($user->hasRoleFix('employers'))
                $user->company()->create([]);
        }
    }
}
