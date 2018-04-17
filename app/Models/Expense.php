<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Expense extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'expenses';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    protected $dates = ['expensed_at'];

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

    public static function montlyExpenses($date){

      return Expense::whereMonth('expensed_at', $date->month)
        ->whereYear('expensed_at', $date->year)
        ->where('type', 0)
        ->whereNull('periodic_id')
        ->sum('amount');

    }

    public static function montlyGain($date){

      return Expense::whereMonth('expensed_at', $date->month)
        ->whereYear('expensed_at', $date->year)
        ->where('type', 1)
        ->whereNull('periodic_id')
        ->sum('amount');

    }

    public static function montlyStat(){

      $now = Carbon::now();

      $stat = array();
      for($i=0; $i<=30; $i++){
        $tmp = array();

        //$tmp['y'] = $now->format(config('backpack.base.default_date_format'));
        $tmp['y'] = $now->toDateString();
        $tmp['in'] = Expense::whereDate('expensed_at', $now->toDateString())
          ->where('type', 1)
          ->sum('amount');
        $tmp['out'] = Expense::whereDate('expensed_at', $now->toDateString())
          ->where('type', 0)
          ->sum('amount');
        $stat[] = $tmp;

        $now->subDay();
      }

      return $stat;

    }

    public static function yearlyStat(){

      $now = Carbon::now();

      $stat = array();
      for($i=0; $i<=12; $i++){
        $tmp = array();
        $start = (clone $now)->firstOfMonth()->toDateString();
        $end = (clone $now)->lastOfMonth()->toDateString();

        $tmp['y'] = $now->toDateString();
        $tmp['in'] = Expense::whereBetween('expensed_at', [$start, $end])
          ->where('type', 1)
          ->sum('amount');
        $tmp['out'] = Expense::whereBetween('expensed_at', [$start, $end])
          ->where('type', 0)
          ->sum('amount');
        $stat[] = $tmp;

        $now->subMonth();
      }

      return $stat;

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

    public function periodic()
    {
      return $this->belongsTo('App\Models\Periodic');
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

            $builder->where('user_id', $user->id);
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
