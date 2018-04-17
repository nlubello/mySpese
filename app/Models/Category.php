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

    public function getSum($date){
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
