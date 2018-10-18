<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Category;
use App\Models\Periodic;
use App\Models\Debit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //

    public function index(Request $request)
    {
      $data = array();

      $now = Carbon::parse($request->input('date', Carbon::now()->toDateString()));

      $data['now'] = $now;
      $data['out'] = Expense::montlyExpenses($now);
      $data['in'] = Expense::montlyGain($now);


      $data['mov'] = Expense::orderBy('expensed_at', 'desc')->paginate(10, ['*'], 'mov');
      //$data['mov']->withPath('mov');

      $tmp = Category::all();
      foreach ($tmp as &$k) {
        $k['sum'] = $k->getSum($now);
      }

      $data['catin'] = $tmp->where('sum', '<', 0)->sortBy('sum');
      $data['catout'] = $tmp->where('sum', '>', 0)->sortByDesc('sum');

      $data['stat30'] = Expense::dailyStat(30, $now);
      $data['statYr'] = Expense::montlyStat(12, $now);

      $end = (clone $now)->subDays(30);
      $periods = Periodic::whereNull('ending_at')
        ->orWhere('ending_at', '>', $now->toDateString())->get();
      $data['periodics'] = $periods->sortBy('next_period')->take(10);

      //\Debugbar::info((clone $data['periodics'][0]->next_period)->isToday());

      $data['rem'] = round($periods->sum('yearly-balance') / 12.0);
      $data['bal'] = $data['rem'] - $data['out'] + $data['in'];

      $data['debits'] = Debit::take(10)->get();

      return view('dashboard', $data);
    }

}
