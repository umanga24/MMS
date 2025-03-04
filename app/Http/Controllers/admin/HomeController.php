<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\DailyExpense;
use App\Models\DailyIncome;
use App\Models\Product;
use App\Models\ProductSell;
use Illuminate\Support\Carbon;
class HomeController extends Controller
{
    public function index()
{
    $categories = Category::query();
    $todayCategories = Category::whereDate('created_at', Carbon::today())->get();

    // Calculate totals
    $totalPlayers = 0;
    $totalAdditionalPlayer = 0;
    $total = 0;

    $totalExpenses = 0;
    $totalIncomes = 0;
    $totalSells = 0;
    $totalBuys = 0;

    foreach ($todayCategories as $category) {
        $totalPlayers += $category->playernumber;
        $totalAdditionalPlayer += $category->additional_player;
        $total += $category->total;
    }

    $totalExpenses = DailyExpense::whereDate('created_at', Carbon::today())->sum('amount');
   // $totalExpenses amount;

    $totalIncomes = DailyIncome::whereDate('created_at', Carbon::today())->sum('amount');

    $totalSells = ProductSell::whereDate('created_at', Carbon::today())->sum('total');

    $totalBuys = Product::whereDate('created_at', Carbon::today())->sum('total');




    return view('admin.dashboard', compact('categories', 'todayCategories', 'totalPlayers', 'totalAdditionalPlayer', 'total','totalExpenses','totalIncomes','totalSells','totalBuys'));
}


    public function  logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
