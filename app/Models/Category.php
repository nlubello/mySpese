<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
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
    protected $fillable = ['user_id', 'name', 'icon', 'type', 'budget_income', 'budget_expense'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['exp_last_year', 'inc_last_year', 'exp_curr_year', 'inc_curr_year',
      'exp_curr_year_perc', 'inc_curr_year_perc'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function htmlIcon(){
      return '<i class="'.$this->icon.'" aria-hidden="true"></i>';
    }

    public function getSumType($type, $year, $month=null){
      $q = $this->expenses()
        ->whereYear('expenses.expensed_at', $year)
        ->where('type', $type);

      if(!is_null($month)){
        $q->whereMonth('expenses.expensed_at', $date->month);
      }

      return $q->sum('amount');
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

    public function getYearlySum($date = null){
      if(is_null($date))
        $date = Carbon::now();
      
      $in = $this->expenses()
        ->whereYear('expenses.expensed_at', $date->year)
        ->where('type', 1)
        ->sum('amount');

      $out = $this->expenses()
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
        $tmp['y'] = (clone $now)->firstOfMonth()->toDateString();
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

        //$tmp['y'] = $now->toDateString();
        $tmp['y'] = $now->year;
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

    public function getPrevMonthsDifferenceHTML($date){
      $now = clone $date;

      $stats = $this->montlyStat(5, $date);
      $percIn = [];
      $percOut = [];
      foreach($stats as $s){
        $tmpIn = round(floatVal($s['in']) - ($this->budget_income / 12), 2);
        $tmpOut = round(floatVal($s['out']) - ($this->budget_expense / 12), 2);

        $percIn[] = $tmpIn;
        $percOut[] = $tmpOut;
      }

      //return "<span class='badge bg-$color'> $perc %</span>";
      $red = "#dd4b39";
      $green = "#00a65a";
      $htmlIn = '<div class="sparkbar" data-color="'.$green.'" data-negColor="'.$red.'" data-height="20" style="display: inline;">'.rtrim(implode(',', $percIn), ',').'</div>';
      $htmlOut = '<div class="sparkbar" data-color="'.$red.'" data-negColor="'.$green.'" data-height="20" style="display: inline; margin-left: 5px;">'.rtrim(implode(',', $percOut), ',').'</div>';
      return $htmlIn.$htmlOut;
    }

    public function detailsBtn($crud){
      $url = backpack_url('category/'.$this->id);
      return "<a class='btn btn-primary btn-sm' href='$url'><i class='fa fa-eye'></i> Dettagli</a>";
    }

    public function getTotalExpense(){
      return number_format($this->expenses->where('type', 0)->sum('amount'), 2, '.', '') . " &euro;";
    }

    public function getTotalProfit(){
      return number_format($this->expenses->where('type', 1)->sum('amount'), 2, '.', '') . " &euro;";
    }

    public function getAvgExpense(){
      $monthData = $this->expenses->where('type', 0)
        ->groupBy(function (\App\Models\Expense $item) {
          return $item->created_at->format('Y-m');
        });
      $sum = $monthData->sum(function ($item){
          return $item->sum('amount');
        });
      return number_format($monthData->count() > 0 ? $sum / $monthData->count() : 0, 2, '.', '') . " &euro;";
    }

    public function getAvgProfit(){
      $monthData = $this->expenses->where('type', 1)
        ->groupBy(function (\App\Models\Expense $item) {
          return $item->created_at->format('Y-m');
        });
      $sum = $monthData->sum(function ($item){
          return $item->sum('amount');
        });
      return number_format($monthData->count() > 0 ? $sum / $monthData->count() : 0, 2, '.', '') . " &euro;";
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

    public function getExpLastYearAttribute()
    {
        $date = Carbon::now()->subYear();
        return $this->getSumType(0, $date->year, null);
    }

    public function getIncLastYearAttribute()
    {
        $date = Carbon::now()->subYear();
        return $this->getSumType(1, $date->year, null);
    }

    public function getExpCurrYearAttribute()
    {
        $date = Carbon::now();
        return $this->getSumType(0, $date->year, null);
    }

    public function getIncCurrYearAttribute()
    {
        $date = Carbon::now();
        return $this->getSumType(1, $date->year, null);
    }

    public function getExpCurrYearPercAttribute()
    {
        $date = Carbon::now();
        if($this->budget_expense > 0){
          return round($this->getSumType(0, $date->year, null) / $this->budget_expense * 100, 2);
        } else {
          return 0;
        }
        
    }

    public function getIncCurrYearPercAttribute()
    {
        $date = Carbon::now();
        if($this->budget_income > 0){
          return round($this->getSumType(1, $date->year, null) / $this->budget_income * 100, 2);
        } else {
          return 0;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
