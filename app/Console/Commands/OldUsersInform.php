<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;

class OldUsersInform extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oldUsers:inform';

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
	    $number = 0;
	    foreach(User::whereActivated(0)->whereInformed(0)->get() as $user) {
		    $response = Password::sendResetLink(['email' => $user->email], function (Message $message) {
			    $message->subject('Новый сайт employment.kg! Мы сделали это для Вас!');
		    });
		    $number = $number + 1;
            $this->info($number . '. Sent to: ' . $user->email);
		    $user->update(['informed' => true, 'informed_at' => Carbon::now()]);
	    }
    }
}
