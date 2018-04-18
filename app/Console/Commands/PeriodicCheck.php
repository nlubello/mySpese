<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class PeriodicCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'periodic:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if periodics needs to be inserted as expense';

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
      $now = Carbon::now();
      $time =  $now->toDateString();
      $this->info("Inizio lettura dei periodici - $time");


      $per = \App\Models\Periodic::whereNull('ending_at')
        ->orWhere('ending_at', '>', $now->toDateString())->get();

      $this->info("Tutti i periodici attivi:");
      $this->info(json_encode($per));

      foreach ($per as $p) {

        $this->info("Controllo $p->name");
        $this->info("Prossima scadenza $p->next_period");

        if((clone $p->next_period)->isToday()){
          $this->info("Creo $p->name");
          $e = new \App\Models\Expense;
          $e->name = $p->name;
          $e->type = $p->type;
          $e->expensed_at = $now->toDateTimeString();
          $e->amount = $p->amount;
          $e->periodic_id = $p->id;
          $e->save();

          //echo json_encode($p->categories);
          foreach ($p->categories as $c) {
            $e->categories()->sync($c->id);
          }
        } else {
          // Skip
        }
      }
      $this->info("Fine lettura dei periodici - $time");
    }

}
