<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BudgetController extends Controller
{
    //
    public function dashboard(){
        $data = [];
        $data['now'] = \Carbon\Carbon::now();
        return view('budget.dashboard', $data);
    }
}
