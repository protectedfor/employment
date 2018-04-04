<?php

namespace App\Console;

use App\Console\Commands\AddEmailsToMailings;
use App\Console\Commands\BrokenItemsRemove;
use App\Console\Commands\CheckBanners;
use App\Console\Commands\CheckBillingLogs;
use App\Console\Commands\FillUsersPersonalBill;
use App\Console\Commands\OldEmployersSeed;
use App\Console\Commands\OldUsersInform;
use App\Console\Commands\OldWorkersSeed;
use App\Console\Commands\RefactorsUsers;
use App\Console\Commands\RenameBillingLogs;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        RefactorsUsers::class,
        FillUsersPersonalBill::class,
	    CheckBillingLogs::class,
	    RenameBillingLogs::class,
	    OldWorkersSeed::class,
	    OldEmployersSeed::class,
	    OldUsersInform::class,
	    BrokenItemsRemove::class,
	    AddEmailsToMailings::class,
	    CheckBanners::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('billingLogs:check')->everyFiveMinutes();
        $schedule->command('banners:check')->everyFiveMinutes();
    }
}
