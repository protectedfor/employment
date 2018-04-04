<?php

namespace App\Console\Commands;

use App\Models\MailingEmail;
use App\User;
use Illuminate\Console\Command;

class AddEmailsToMailings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emailsToMailings:add';

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

		foreach (User::where('id', '<=', 5000)->get() as $user) {
			if(!MailingEmail::where('email', $user->email)->first()) {
				MailingEmail::create(['user_id' => $user->id, 'email' => $user->email, 'subscribed' => true]);
				$number = $number + 1;
				$this->info($number . '. Added user with email: ' . $user->email);
			}
		}

		foreach (User::where('id', '>', 5000)->get() as $user) {
			if(!MailingEmail::where('email', $user->email)->first()) {
				MailingEmail::create(['user_id' => $user->id, 'email' => $user->email, 'subscribed' => true]);
				$number = $number + 1;
				$this->info($number . '. Added user with email: ' . $user->email);
			}
		}
	}
}
