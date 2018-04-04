<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Category;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //

    public function index()
    {
      $data = array();

      $now = Carbon::now();
      $data['in'] = Expense::montlyExpenses($now);
      $data['out'] = Expense::montlyGain($now);
      $data['bal'] = Expense::montlyBalance($now);

      $data['mov'] = Expense::orderBy('expensed_at', 'desc')->take(10)->get();

      $tmp = Category::all();
      foreach ($tmp as &$k) {
        $k['sum'] = $k->getSum();
      }
      $data['catin'] = $tmp->where('sum', '<', 0)->sortBy('sum')->take(5);
      $data['catout'] = $tmp->where('sum', '>', 0)->sortByDesc('sum')->take(5);

      return view('dashboard', $data);
    }

}
