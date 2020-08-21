<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

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

    public function getExpense($date=null){

      if(is_null($date)){
        $date = $this->prev_period;
      }

      $e = $this->expenses()->whereDate('expensed_at', $date)->first();

      return $e;
    }

    public function createExpense($date=null){
      $now = Carbon::now();

      if(is_null($date)){
        $date = $this->prev_period;
      }

      if(!is_null($this->getExpense($date)))
        return false;

      $e = new \App\Models\Expense;
      $e->name = $this->name . ' - ' . $now->month . '/' . $now->year;
      $e->type = $this->type;
      $e->expensed_at = $date;
      $e->amount = $this->amount;
      $e->user_id = $this->user_id;
      $e->periodic_id = $this->id;
      $e->save();

      //echo json_encode($p->categories);
      foreach ($this->categories as $c) {
        $e->categories()->sync($c->id);
      }

      return true;
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

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_id', function (Builder $builder) {
          if (backpack_auth()->check()) {
            // The user is logged in...
            $user = backpack_auth()->user();

            $builder->where('user_id', $user->id);
          }

        });
    }

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

      if(!is_null($this->ending_at) && $tmpD->gt($this->ending_at->endOfDay()))
        return null;
      
      return $tmpD;
    }

    public function getPrevPeriodAttribute()
    {
      $now = Carbon::now()->startOfDay();
      $tmpD = clone $this->starting_at->startOfDay();
      $prevD = clone $this->starting_at->startOfDay();

      while ($tmpD->lt($now)){

        $prevD = clone $tmpD;
        $tmpD = $this->nextDate($tmpD);

      }

      // Periodics not yet started
      if($prevD->gt(Carbon::now())){
        return null;
      }

      return $prevD;      
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
