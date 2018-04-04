<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class FillUsersPersonalBill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:bills';

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
        foreach (User::where('personal_bill', 0)->get() as $user) {
            $user->personal_bill = $this->generateCode();
            $user->save();
        }
    }

    /**
     * Generate unique integer code for registered user
     *
     * @param int $length
     * @return string
     */
    protected function generateCode($length = 6)
    {
        $number = '';

        do {
            for ($i = $length; $i--; $i > 0) {
                $number = random_int(100000, 999999);
            }
        } while (!empty(User::where('personal_bill', $number)->first(['id'])));

        return $number;
    }
}
