<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Category extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'categories';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['name', 'icon', 'type'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function htmlIcon(){
      return '<i class="fa '.$this->icon.'" aria-hidden="true"></i>';
    }

    public function getSum($date = null){
      if(is_null($date))
        $date = Carbon::now();
      
      $in = $this->expenses()
        ->whereMonth('expenses.expensed_at', $date->month)
        ->whereYear('expenses.expensed_at', $date->year)
        ->where('type', 1)
        ->sum('amount');

      $out = $this->expenses()
        ->whereMonth('expenses.expensed_at', $date->month)
        ->whereYear('expenses.expensed_at', $date->year)
        ->where('type', 0)
        ->sum('amount');

      return $in - $out;
    }

    public function montlyStat($months = 12, $date){

      //$now = Carbon::now();
      $now = clone $date;

      $stat = array();
      for($i=0; $i<=$months; $i++){
        $tmp = array();
        $start = (clone $now)->firstOfMonth()->toDateString();
        $end = (clone $now)->lastOfMonth()->toDateString();

        //$tmp['y'] = $now->format(config('backpack.base.default_date_format'));
        $tmp['y'] = $now->toDateString();
        $tmp['in'] = $this->expenses()
          ->whereBetween('expensed_at', [$start, $end])
          ->where('type', 1);

        $tmp['out'] = $this->expenses()
          ->whereBetween('expensed_at', [$start, $end])
          ->where('type', 0);

        $tmp['in'] = $tmp['in']->sum('amount');
        $tmp['out'] = $tmp['out']->sum('amount');
        $stat[] = $tmp;

        $now->subMonth();
      }

      return $stat;

    }

    public function yearlyStat($date){

      //$now = Carbon::now();
      $now = clone $date;

      $stat = array();
      for($i=0; $i<=6; $i++){
        $tmp = array();
        $start = (clone $now)->firstOfYear()->toDateString();
        $end = (clone $now)->lastOfYear()->toDateString();

        $tmp['y'] = $now->toDateString();
        $tmp['in'] = $this->expenses()
          ->whereBetween('expensed_at', [$start, $end])
          ->where('type', 1);

        $tmp['out'] = $this->expenses()
          ->whereBetween('expensed_at', [$start, $end])
          ->where('type', 0);

        $tmp['in'] = $tmp['in']->sum('amount');
        $tmp['out'] = $tmp['out']->sum('amount');
        $stat[] = $tmp;

        $now->subYear();
      }

      return $stat;

    }

    public function getPrevMonthDifference(){
      $now = Carbon::now();

      $stat = array();
      for($i=0; $i<=1; $i++){
        $tmp = array();
        $start = (clone $now)->firstOfMonth()->toDateString();
        $end = (clone $now)->lastOfMonth()->toDateString();

        $tmp['y'] = $now->toDateString();
        $tmp['exp'] = $this->expenses()
          ->whereBetween('expensed_at', [$start, $end])
          ->sum('amount');

        $stat[] = $tmp;

        $now->subMonth();
      }

      if ($stat[1]['exp'] != 0)
        return ($stat[0]['exp'] - $stat[1]['exp']) / $stat[1]['exp'] * 100;
      else
        return 0;
    }

    public function getPrevMonthDifferenceHTML(){
      $out = $this->getSum() > 0;
      \Debugbar::info($this->name);
      \Debugbar::info($out);
      
      $diff = $this->getPrevMonthDifference();
      $positive = $diff > 0;

      $perc = number_format($out ? $diff : -$diff, 0, '.', '');

      $color = $out ? !$positive : $positive ? 'green': 'red';

      return "<span class='badge bg-$color'> $perc %</span>";
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * The roles that belong to the user.
     */
    public function expenses()
    {
        return $this->belongsToMany('App\Models\Expense');
    }

    /**
     * The roles that belong to the user.
     */
    public function periodics()
    {
        return $this->belongsToMany('App\Models\Periodic');
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
          if (\Auth::check()) {
            // The user is logged in...
            $user = \Auth::user();

            $builder->whereNull('user_id')->orWhere('user_id', $user->id);
            //$builder->Where('user_id', $user->id);
          }

        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
