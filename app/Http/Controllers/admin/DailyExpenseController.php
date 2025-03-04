<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyExpense;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class DailyExpenseController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', 'all');
        $query = DailyExpense::query();
    
        if ($date !== 'all') {
            $query->whereDate('created_at', $date);
        }
    
        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query->where('title', 'like', '%' . $keyword . '%');
        }
    
        $expenses = $query->paginate(10);
        $totalAmount = $query->sum('amount');
    
        return view('admin.dailyexpenses.list', compact('expenses', 'date', 'totalAmount'));
    }
    

    public function create()
    {
        return view('admin.dailyexpenses.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'amount' => 'required',
            //'slug' => 'required|unique:brands'
        ]);
        if ($validator->passes()) {
            $expense = new DailyExpense();
            $expense->title = $request->title;
            $expense->amount = $request->amount;

            $expense->save();

            $request->session()->flash('success', 'Record added Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Record added successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($expenseId, Request $request)
    {
        $expense = DailyExpense::find($expenseId);
        $userRole = auth('admin')->user()->role;
        if (empty($expense)) {
            return redirect()->route('expenses.index');
        }
        if (in_array($userRole, [3]) && $expense->created_at && Carbon::now()->diffInMinutes($expense->created_at) > 10) {
            $request->session()->flash('error', 'You can only edit this form within 15 minutes of the last update.');
            return redirect()->route('dailyexpenses.index');
        }

        return view('admin.dailyexpenses.edit', compact('expense'));
    }

    public function update($expenseId, Request $request)
    {
        $expense = DailyExpense::find($expenseId);
        if (empty($expense)) {
            $request->session()->flash('error', 'Record not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Record not found'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'amount' => 'required',

        ]);
        if ($validator->passes()) {
            $expense->title = $request->title;
            $expense->amount = $request->amount;
            // $brand->slug = $request->slug;
            //$brand->status = $request->status;
            $expense->save();

            $request->session()->flash('success', 'Record Update Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Record Update successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($expenseId, Request $request)
    {
        $expense = DailyExpense::find($expenseId);
        if (empty($expense)) {
            $request->session()->flash('error', 'Record not found');
            return response()->json([
                'status' => false,
                'message' => "Record not found"
            ]);
        }
        $expense->delete();

        return response()->jsone([
            'status' => true,
            'message' => 'Record delete Successfully'
        ]);
    }
}
