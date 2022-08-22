<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Income as IncomeModel;
use App\Http\Controllers\Api\IncomeController;

class ScheduleIncome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduleIncome:cron';

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
     * @return int
     */
    public function handle() {

        $incomeSchedule = new  IncomeController;
        $incomeSchedule->taskSchedulingIncomeCronJob();
    }
}
