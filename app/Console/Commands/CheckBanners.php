<?php

namespace App\Console\Commands;

use App\Models\Banner;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckBanners extends Command
{
    protected $signature = 'banners:check';
    protected $description = 'Command description';
	protected $counter = 0;

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

		foreach (Banner::activeOnly()->get() as $item) {
			switch (true) {
				case ($item->clicks_limit && $item->clicks_limit <= $item->views):
					$item->update(['active' => false]);
					$this->counter++;
					$this->info("{$this->counter}. Expired banner with id: {$item->id}");
					break;
				case ($item->views_limit && $item->views_limit <= $item->views):
					$item->update(['active' => false]);
					$this->counter++;
					$this->info("{$this->counter}. Expired banner with id: {$item->id}");
					break;
				case ($item->expired_at <= Carbon::now()):
					$item->update(['active' => false]);
					$this->counter++;
					$this->info("{$this->counter}. Expired banner with id: {$item->id}");
					break;
			}
		}
	}
}
