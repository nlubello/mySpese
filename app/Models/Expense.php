<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
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

    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        // or use your own disk, defined in config/filesystems.php
        $disk = config('backpack.base.root_disk_name'); 
        // destination path relative to the disk above
        $destination_path = "public/uploads/expenses/"; 

        // if the image was erased
        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (\Str::startsWith($value, 'data:image'))
        {
            // 0. Make the image
            $image = \Image::make($value)->encode('jpg', 90);

            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';

            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());

            // 3. Delete the previous image, if there was one.
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // 4. Save the public path to the database
            // but first, remove "public/" from the path, since we're pointing to it 
            // from the root folder; that way, what gets saved in the db
            // is the public URL (everything that comes after the domain name)
            $public_destination_path = \Str::replaceFirst('public/', '', $destination_path);
            $this->attributes[$attribute_name] = $public_destination_path.'/'.$filename;
        }
    }
}
