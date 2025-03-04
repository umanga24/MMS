<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyExpense;
use App\Models\Category;
use App\Models\DailyIncome;
use App\Models\Product;
use App\Models\ProductSell;
use Carbon\Carbon;


class SummaryController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', 'all');

        if ($date === 'all') {
            // Retrieve total counts without filtering by date
            $totalCategories = Category::sum('total');
            $totalExpenses = DailyExpense::sum('amount');
            $totalIncomes = DailyIncome::sum('amount');
            $totalBuyProducts = Product::sum('total');
            $totalProductsSold = ProductSell::sum('total');
        } else {
            // Retrieve total counts based on the filtered date
            $totalCategories = Category::whereDate('created_at', $date)->sum('total');
            $totalExpenses = DailyExpense::whereDate('created_at', $date)->sum('amount');
            $totalIncomes = DailyIncome::whereDate('created_at', $date)->sum('amount');
            $totalBuyProducts = Product::whereDate('created_at', $date)->sum('total');
            $totalProductsSold = ProductSell::whereDate('created_at', $date)->sum('total');
        }

        // Pass the data to the view along with the selected date
        return view('admin.summary.index', compact('totalCategories', 'totalExpenses', 'totalBuyProducts', 'totalProductsSold', 'date', 'totalIncomes'));
    }


    //     public function index(Request $request)
    // {
    //     $date = $request->input('date', \Carbon\Carbon::today()->format('Y-m-d'));

    //     // Fetch totals based on the selected date
    //     $totalCategories = Category::whereDate('created_at', $date)->sum('total');
    //     $totalExpenses = DailyExpense::whereDate('created_at', $date)->sum('amount');
    //     $totalIncomes = DailyIncome::whereDate('created_at', $date)->sum('amount');
    //     $totalBuyProducts = Product::whereDate('created_at', $date)->sum('total');
    //     $totalProductsSold = ProductSell::whereDate('created_at', $date)->sum('total');

    //     // Pass the data to the view along with the selected date
    //     return view('admin.summary.index', compact(
    //         'totalCategories', 
    //         'totalExpenses', 
    //         'totalBuyProducts', 
    //         'totalProductsSold', 
    //         'date', 
    //         'totalIncomes'
    //     ));
    // }

}
