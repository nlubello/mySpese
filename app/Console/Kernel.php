<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        // Controllo le scadenze attive
        $schedule->call(function () {
          echo "Inizio lettura dei periodici!\n";

          $now = Carbon::now();
          $per = \App\Models\Periodic::whereNull('ending_at')
            ->orWhere('ending_at', '>', $now->toDateString())->get();

          echo json_encode($per);

          foreach ($per as $p) {
            echo "$p->next_period\n";
            if((clone $p->next_period)->isToday()){
              echo "Creo $p->name";
              $e = new \App\Models\Expense;
              $e->name = $p->name;
              $e->type = $p->type;
              $e->expensed_at = $now->toDateTimeString();
              $e->amount = $p->amount;
              $e->save();

              echo json_encode($p->categories);
              foreach ($p->categories as $c) {
                $e->categories()->sync($c->id);
              }
            } else {
              // Skip
            }

          }

        //})->everyMinute();
        })->dailyAt('8:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
