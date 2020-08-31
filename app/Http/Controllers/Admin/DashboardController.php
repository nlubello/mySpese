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
      $end = (clone $now)->subDays(30);
      $periodics = Periodic::whereNull('ending_at')
        ->orWhere('ending_at', '>', $now->toDateString())->get();
      $data['periodics'] = $periodics->sortBy('next_period')->take(10);
      $data['expPeriodics'] = $periodics->where('prev_period', '!=', NULL)->sortByDesc('prev_period')->take(5);


      $data['out'] = Expense::montlyExpenses($now);
      $data['in'] = Expense::montlyGain($now);
      $data['rem'] = round($periodics->sum('yearly-balance') / 12.0);
      $data['bal'] = $data['rem'] - $data['out'] + $data['in'];

      \Widget::add()->to('before_content')->type('div')->class('row')->content([
        \Widget::make()
          ->type('progress')
          ->class('card border-0 text-white bg-danger')
          ->progressClass('progress-bar')
          ->value($data['out'].' €')
          ->description('Spese extra')
          ->progress(100*(int)$data['out']/max((int)$data['rem'], 1)),
        \Widget::make()
          ->type('progress')
          ->class('card border-0 text-white bg-success')
          ->progressClass('progress-bar')
          ->value($data['in'].' €')
          ->description('Guadagni extra')
          ->progress(100*(int)$data['in']/max((int)$data['rem'], 1)),
        \Widget::make()
          ->type('progress')
          ->class('card border-0 text-white bg-warning')
          ->progressClass('progress-bar')
          ->value($data['bal'].' €')
          ->description('Rimanente')
          ->progress(100*(int)$data['rem']/max((int)$data['bal'], 1)),
        \Widget::make()
          ->type('progress')
          ->class('card border-0 text-white bg-primary')
          ->progressClass('progress-bar')
          ->value($data['rem'].' €')
          ->description('Budget mensile')
          ->progress(75),
      ]);

      $data['mov'] = Expense::orderBy('expensed_at', 'desc')
        ->whereMonth('expensed_at', $now->month)
        ->whereYear('expensed_at', $now->year)
        ->paginate(10, ['*'], 'mov');
      //$data['mov']->withPath('mov');

      $tmp = Category::all();
      foreach ($tmp as &$k) {
        $k['sum'] = $k->getSum($now);
      }

      $data['catin'] = $tmp->where('sum', '<', 0)->sortBy('sum');
      $data['catout'] = $tmp->where('sum', '>', 0)->sortByDesc('sum');

      $data['stat30'] = Expense::dailyStat(30, $now);
      $data['statYr'] = Expense::montlyStat(12, $now);

      $data['statCatE'] = [];
      $data['statCatP'] = [];
      foreach ($tmp as $k) {
        //\Debugbar::info($k->name);
        $yearlyS = $k->getYearlySum($now);
        if($yearlyS > 0){
          $data['statCatE'][] = ['label' => $k->name, 'value' => $yearlyS];
        }
        if($yearlyS < 0){
          $data['statCatP'][] = ['label' => $k->name, 'value' => $yearlyS];
        }
      }
      

      

      //\Debugbar::info((clone $data['periodics'][0]->next_period)->isToday());

      

      $data['debits'] = Debit::paginate(10, ['*'], 'debits');

      return view('dashboard', $data);
    }

}
