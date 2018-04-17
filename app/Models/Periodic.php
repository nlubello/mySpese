<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Carbon\Carbon;

class Periodic extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'periodics';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    protected $dates = ['ending_at', 'starting_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function htmlType(){
      $txt = "";
      switch ($this->type){
        case 0:
          $txt = '<span class="label label-warning">Spesa</span>';
          break;
        case 1:
          $txt = '<span class="label label-success">Entrata</span>';
          break;

      }

      return $txt;
    }

    public function htmlPeriod(){
      $txt = "";
      switch ($this->period){
        case 'm':
          $txt = '<span class="label label-warning">Mensile</span>';
          break;
        case 'g':
          $txt = '<span class="label label-warning">Giornaliero</span>';
          break;
        case 'y':
          $txt = '<span class="label label-warning">Annuale</span>';
          break;
        case '4m':
          $txt = '<span class="label label-warning">4 Mesi</span>';
          break;

      }

      return $txt;
    }

    private function nextDate($inDate){
      switch ($this->period){
        case 'm':
          return $inDate->addMonth();
        case 'g':
          return $inDate->addDay();
        case 'y':
          return $inDate->addYear();
        case '4m':
          return $inDate->addMonths(4);
      }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * The roles that belong to the user.
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category');
    }

    public function expenses()
    {
        return $this->hasMany('App\Models\Expense');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*public function getDatePeriodsAttribute()
    {
      return "{$this->first_name} {$this->last_name}";
    }*/

    public function getNextPeriodAttribute()
    {
      $now = Carbon::now()->startOfDay();
      $tmpD = clone $this->starting_at->startOfDay();

      while ($tmpD->lt($now)){

        $tmpD = $this->nextDate($tmpD);

      }

      return $tmpD;
    }


    public function getYearlyBalanceAttribute(){

      switch ($this->type){
        case 0:
          $tmpAmount = -1 * $this->amount;
          break;
        case 1:
          $tmpAmount = $this->amount;
          break;

      }

      if (!is_null($this->ending_at)){
        // Finished periodic?
        $now = Carbon::now();
        $tmpD = clone $this->ending_at;

        if($tmpD->lt($now))
          $tmpAmount = 0;
      }

      switch ($this->period){
        case 'm':
          return $tmpAmount * 12;
        case 'g':
          return $tmpAmount * 365;
        case 'y':
          return $tmpAmount;
        case '4m':
          return $tmpAmount * 3;
      }
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
