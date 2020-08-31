<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\Expense;

/**
 * Class DailyExpensesChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DailyExpensesChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        // MANDATORY. Set the labels for the dataset points
        $labels = [];
        for ($days_backwards = 30; $days_backwards >= 0; $days_backwards--) {
            if ($days_backwards == 1) {
            }
            $labels[] = $days_backwards.' days ago';
        }
        $this->chart->labels($labels);

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/daily-expenses'));

        // OPTIONAL
        // $this->chart->minimalist(false);
        // $this->chart->displayLegend(true);
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
    public function data()
    {
        for ($days_backwards = 30; $days_backwards >= 0; $days_backwards--) {
            // Could also be an array_push if using an array rather than a collection.
            $in[] = Expense::whereDate('expensed_at', today()->subDays($days_backwards))
                ->where('type', 1)            
                ->sum('amount');
            $out[] = Expense::whereDate('expensed_at', today()->subDays($days_backwards))
                ->where('type', 0)            
                ->sum('amount');
            
        }

        $this->chart->dataset('Entrate', 'line', $in)
            ->color('#5cb85c')
            ->backgroundColor('#665cb85c');

        $this->chart->dataset('Uscite', 'line', $out)
            ->color('#d9534f')
            ->backgroundColor('#66d9534f');

    }
}