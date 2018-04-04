<?php

namespace App\Console\Commands;

use App\Models\BillingLog;
use App\Models\Vars;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckBillingLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billingLogs:check';

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

		foreach (BillingLog::whereActive(true)->whereExpired(false)->get() as $log) {
			switch (true) {
				case ($log->billable_type === 'App\Models\Vacancies\Vacancy' && $log->description === 'makeInPriority'):
					$log->update(['started_at' => $log->updated_at, 'expired' => true]);
					break;
				case ($log->billable_type === 'App\Models\Vacancies\Vacancy' && $log->description === 'makeFixed' && $log->updated_at->addDays($log->duration) < Carbon::now()):
					$log->billable ? $log->billable->update(['is_fixed' => false]) : false;
					$log->update(['started_at' => $log->updated_at, 'expired' => true]);
					break;
				case ($log->billable_type === 'App\Models\Vacancies\Vacancy' && $log->description === 'makeHot' && $log->updated_at->addDays($log->duration) < Carbon::now()):
					$log->billable ? $log->billable->update(['is_hot' => false]) : false;
					$log->update(['started_at' => $log->updated_at, 'expired' => true]);
					break;
				case ($log->billable_type === 'App\Models\Training' && $log->description === 'publish' && $log->updated_at->addDays($log->duration) < Carbon::now()):
					$log->billable ? $log->billable->update(['moderated' => false]) : false;
					$log->update(['started_at' => $log->updated_at, 'expired' => true]);
					break;
				case ($log->billable_type === 'App\Models\Company' && $log->description === 'makeLeading' && $log->updated_at->addDays($log->duration) < Carbon::now()):
					$log->billable ? $log->billable->update(['is_leading' => false]) : false;
					$log->update(['started_at' => $log->updated_at, 'expired' => true]);
					break;
				case ($log->billable_type === 'App\Models\Company' && $log->description === 'getContacts' && $log->updated_at->addDays($log->duration) < Carbon::now()):
					$log->billable ? $log->billable->update(['get_contacts' => false]) : false;
					$log->update(['started_at' => $log->updated_at, 'expired' => true]);
					break;
			}
		}

		foreach (BillingLog::whereActive(false)->get() as $log) {
			if($log->updated_at->addDays(30) < Carbon::now())
				$log->update(['started_at' => $log->updated_at, 'expired' => true]);
		}
	}
}
