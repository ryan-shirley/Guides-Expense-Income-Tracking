<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Payment;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Show the admin home dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Retrieve all payments and pluck the 'date' property
        $uniqueYears = Payment::pluck('purchase_date')
        ->map(function ($date) {
            return date('Y', strtotime($date));
        })
        ->unique()
        ->toArray();

        // Get the current year
        $currentYear = date('Y');

        // Add the current year if it's not already in the list
        if (!in_array($currentYear, $uniqueYears)) {
            $uniqueYears[] = $currentYear;
        }

        // Sort the years in descending order
        rsort($uniqueYears);

        $usersPendingApproval = User::whereNull('approved_at')->get();

        $paymentsToBePaidBack = Payment::where('paid_back', false)->get();
        $leadersToPayBack = $this->groupAndSumPayments($paymentsToBePaidBack);

        return view('admin.home')->with([
            'show_sidebar' => false,
            'years' => $uniqueYears,
            'users_pending_approval' => $usersPendingApproval,
            'leadersToPayBack' => $leadersToPayBack
        ]);
    }

    private function groupAndSumPayments($array) {
        // Group
        $groups = array();
        foreach ( $array as $value ) {
            $groups[$value['user_id']][] = $value;
        }

        // Sum
        $groupSum = array();
        foreach ( $groups as $group ) {
            $userSum = array_sum(array_column($group, 'amount'));
            $userId = $group[0]->user_id;

            $groupSum[$userId] = $userSum;
        }

        return $groupSum;
    }
}
