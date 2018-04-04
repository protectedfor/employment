<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Role;
use App\User;
use Illuminate\Console\Command;

class OldWorkersSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oldWorkers:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
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
	    function csv_to_array($filename='', $delimiter=',')
	    {
		    if(!file_exists($filename) || !is_readable($filename))
			    return FALSE;

		    $header = NULL;
		    $data = array();
		    if (($handle = fopen($filename, 'r')) !== FALSE)
		    {
			    while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
			    {
				    if(!$header)
					    $header = $row;
				    else
					    $data[] = array_combine($header, $row);
			    }
			    fclose($handle);
		    }
		    return $data;
	    }

	    $csvFile = public_path(config('admin.imagesUploadDirectory') . '/oldUsers/oldWorkers.csv');
	    $cols = csv_to_array($csvFile);

	    $number = 0;
	    foreach($cols as $col) {
		    $existedUser = \App\User::whereEmail($col['email'])->first();

		    if(!$existedUser) {
			    $user = User::create(['name' => $col['nickname'], 'email' => $col['email'], 'remember_token' => str_random(50)]);

			    $role = Role::whereId('1')->first();
			    $user->attachRole($role);

			    Profile::create(['user_id' => $user->id]);

			    $number = $number + 1;
			    $this->info($number . '. Executed: ' . $user->email);
		    } else {
			    $number = $number + 1;
			    $this->info($number . '. Skipped: ' . $existedUser->email);
		    }
	    }
    }
}
