<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Income;
use DateTime;

class IncomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    /**
     *  Loads incomes from date range
     */
    public function export(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        // Get Incomes
        if(!is_null($startDate) && !is_null($endDate)) {
            $from = new DateTime($startDate);
            $to = new DateTime($endDate);

            $incomes = Income::whereBetween('date', [$from, $to])
                ->orderByRaw('CAST(date as DATETIME) ASC')
                ->get();
        } else {
            $incomes = null;
        }

        // Format Incomes ID
        if($incomes) {
            foreach ($incomes as $index => $income) {
                $incomes[$index]->keyID = "i_" . $income->ref_id;
                $incomes[$index]->code = $income->code != null ? $income->code : "N/A";

                if($income->is_cash) {
                    $incomes[$index]->cash_and_cheque = $income->amount;
                    $incomes[$index]->online = 0;
                } else {
                    $incomes[$index]->cash_and_cheque = 0;
                    $incomes[$index]->online = $income->amount;
                }
            }
        }

        return [
            'data' => $incomes,
            'endDate' => $endDate,
            'startDate' => $startDate
        ];
    }

}
