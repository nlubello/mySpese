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

    public static function dailyStat($days = 30, $date, $catId = null){

      //$now = Carbon::now();
      $now = clone $date;

      $stat = array();
      for($i=0; $i<=$days; $i++){
        $tmp = array();

        //$tmp['y'] = $now->format(config('backpack.base.default_date_format'));
        $tmp['y'] = $now->toDateString();
        $tmp['in'] = Expense::whereDate('expensed_at', $now->toDateString())
          ->where('type', 1);

        $tmp['out'] = Expense::whereDate('expensed_at', $now->toDateString())
          ->where('type', 0);

        if(!is_null($catId)){
          $tmp['in']->with(['category' => function ($query) {
            $query->where('id', $catId);
          }]);
          $tmp['out']->with(['category' => function ($query) {
            $query->where('id', $catId);
          }]);
        }

        $tmp['in'] = $tmp['in']->sum('amount');
        $tmp['out'] = $tmp['out']->sum('amount');
        $stat[] = $tmp;

        $now->subDay();
      }

      return $stat;

    }

    public static function montlyStat($months = 12, $date, $catId = null){

      //$now = Carbon::now();
      $now = clone $date->firstOfMonth();

      $stat = array();
      for($i=0; $i<=$months; $i++){
        $tmp = array();
        $start = (clone $now)->firstOfMonth()->toDateString();
        $end = (clone $now)->lastOfMonth()->toDateString();

        //$tmp['y'] = $now->format(config('backpack.base.default_date_format'));
        $tmp['y'] = $now->toDateString();
        $tmp['in'] = Expense::whereBetween('expensed_at', [$start, $end])
          ->where('type', 1);

        $tmp['out'] = Expense::whereBetween('expensed_at', [$start, $end])
          ->where('type', 0);

        if(!is_null($catId)){
          $tmp['in']->with(['category' => function ($query) {
            $query->where('id', $catId);
          }]);
          $tmp['out']->with(['category' => function ($query) {
            $query->where('id', $catId);
          }]);
        }

        $tmp['in'] = $tmp['in']->sum('amount');
        $tmp['out'] = $tmp['out']->sum('amount');
        $stat[] = $tmp;

        $now->subMonth();
      }

      return $stat;

    }

    public static function yearlyStat($date, $catId = null){

      //$now = Carbon::now();
      $now = clone $date->firstOfMonth();

      $stat = array();
      for($i=0; $i<=6; $i++){
        $tmp = array();
        $start = (clone $now)->firstOfYear()->toDateString();
        $end = (clone $now)->lastOfYear()->toDateString();

        $tmp['y'] = $now->toDateString();
        $tmp['in'] = Expense::whereBetween('expensed_at', [$start, $end])
          ->where('type', 1);

        $tmp['out'] = Expense::whereBetween('expensed_at', [$start, $end])
          ->where('type', 0);

        if(!is_null($catId)){
          $tmp['in']->with('category');
          $tmp['out']->with('category');
        }

        $tmp['in'] = $tmp['in']->sum('amount');
        $tmp['out'] = $tmp['out']->sum('amount');
        $stat[] = $tmp;

        $now->subYear();
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
