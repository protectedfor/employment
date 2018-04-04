<?php

namespace App\Console\Commands;

use App\Models\BillingLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RenameBillingLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billingLogs:rename';

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
		\DB::connection()->disableQueryLog();
		$counter = 0;

		foreach (BillingLog::get() as $log) {
			$log->timestamps = false;
			switch (true) {
				case ($log->description === 'priority' || $log->description === 'in_priority'):
					$log->update(['description' => 'makeInPriority']);
					break;
				case ($log->description === 'fixed'):
					$log->update(['description' => 'makeFixed']);
					break;
				case ($log->description === 'hot'):
					$log->update(['description' => 'makeHot']);
					break;
				case ($log->description === 'leading'):
					$log->update(['description' => 'makeLeading']);
					break;
				case ($log->description === 'get_contacts'):
					$log->update(['description' => 'getContacts']);
					break;
				case ($log->description === 'post'):
					$log->update(['description' => 'publish']);
					break;
			}
			$counter = $counter + 1;
			$this->info("{$counter}. Updated log with id: {$log->id}");
		}
	}
}
