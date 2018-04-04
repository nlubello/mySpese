<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

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
        ->sum('amount');

    }

    public static function montlyGain($date){

      return Expense::whereMonth('expensed_at', $date->month)
        ->whereYear('expensed_at', $date->year)
        ->where('type', 1)
        ->sum('amount');

    }

    public static function montlyBalance($date){

      return Expense::montlyGain($date) - Expense::montlyExpenses($date);

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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
