<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Illuminate\Support\Facades\Session;
use Psy\Readline\Hoa\Console;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // public function index(Request $request)
    // {
    //     $categories = Category::latest();

    //     if (!empty($request->get('keyword'))) {
    //         $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
    //     }

    //     $categories = $categories->orderBy('id', 'desc')->paginate(10);
    //     //$data['categories'] = $categories;
    //     return view('admin.category.list', compact('categories'));
    // }


    public function index(Request $request)
    {
        $categories = Category::query()->where('is_active', 1)
            ->where('schedule', 'full-time');


        // Define the keyword variable if it exists in the request
        if (!empty($request->get('keyword'))) {
            $keyword = $request->get('keyword');
            $categories = $categories->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('cardnumber', 'like', '%' . $keyword . '%');
            });
        }

        $categories = $categories->orderBy('created_at', 'asc')->paginate(500);

        return view('admin.category.list', compact('categories'));
    }



    public function half(Request $request)
    {
        $categories = Category::query()->where('is_active', 1)
            ->where('schedule', 'half-time');


        // Define the keyword variable if it exists in the request
        if (!empty($request->get('keyword'))) {
            $keyword = $request->get('keyword');
            $categories = $categories->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('cardnumber', 'like', '%' . $keyword . '%');
            });
        }

        $categories = $categories->orderBy('created_at', 'asc')->paginate(500);

        return view('admin.category.half', compact('categories'));
    }



    public function create()
    {

        return view('admin.category.create');
    }

    // public function store(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'cardnumber' => 'required',
    //         'mobilenumber' => 'required',
    //         'playernumber' => 'required',
    //         'perplayer' => 'required',
    //         'subtotal' => 'required',
    //         'discount' => 'required',
    //         'total' => 'required',

    //     ]);
    //     if ($validator->passes()) {
    //         $category = new Category();
    //         $category->name = $request->name;
    //         $category->cardnumber = $request->cardnumber;
    //         $category->mobilenumber = $request->mobilenumber;
    //         $category->playernumber = $request->playernumber;
    //         $category->perplayer = $request->perplayer;
    //         $category->subtotal = $request->subtotal;
    //         $category->discount = $request->discount;
    //         $category->total = $request->total;
    //         $category->save();

    //         //$request->session();
    //         $request->session()->flash('success', 'Player added successfully');
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Player added successfully'
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors()
    //         ]);
    //     }
    // }

    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cardnumber' => 'required',
            'mobilenumber' => 'required',
            'playernumber' => 'required',
            'perplayer' => 'required',
            'subtotal' => 'required',
            'discount' => 'nullable',
            'first_total' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Check if a player with the same card number exists and is active
        $existingPlayer = Category::where('cardnumber', $request->cardnumber)
            ->where('is_active', 1)
            ->first();

        if ($existingPlayer) {
            return response()->json([
                'status' => false,
                'errors' => ['cardnumber' => ['The card number is already in use by an active player.']]
            ]);
        }

        // Create a new player
        $category = new Category();
        $category->name = $request->name;
        $category->cardnumber = $request->cardnumber;
        $category->mobilenumber = $request->mobilenumber;
        $category->playernumber = $request->playernumber;
        $category->perplayer = $request->perplayer;
        $category->subtotal = $request->subtotal;
        $category->discount = $request->discount;
        $category->first_total = $request->first_total;
        $category->total = $request->first_total;
        $category->schedule = $request->schedule;

        $category->last_updated_at = now();

        $category->save();

        $request->session()->flash('success', 'Player added successfully');
        return response()->json([
            'status' => true,
            'message' => 'Player added successfully'
        ]);
    }

    public function edit($categoryId, Request $request)
    {

        $category = Category::find($categoryId);

        if (empty($category)) {
            return redirect()->route('report.list');
        }

        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $player = Category::findOrFail($id);
        $userRole = auth('admin')->user()->role;
        if (in_array($userRole, [2, 3]) && $player->last_updated_at && Carbon::now()->diffInMinutes($player->last_updated_at) > 10) {
            $request->session()->flash('error', 'You can only edit this form within 15 minutes of the last updateeeeee.');
            return response()->json(['status' => false, 'message' => 'You can only edit this form within 15 minutes of the last update.'], 403);
        }

        // Validate request data
        $request->validate([
            'cardnumber' => 'required|numeric',
            'name' => 'required|string|max:255',
            'mobilenumber' => 'required|numeric',
            'playernumber' => 'required|numeric',
            'perplayer' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'additional_player' => 'nullable|numeric',
            'additional_per_player' => 'nullable|numeric',
        ]);

        // check for additional total 
        $additionalSubTotal = $player->additional_sub_total ?? 0;

        // Update player data
        $player->cardnumber = $request->input('cardnumber');
        $player->name = $request->input('name');
        $player->mobilenumber = $request->input('mobilenumber');
        $player->playernumber = $request->input('playernumber');
        $player->perplayer = $request->input('perplayer');
        $player->subtotal = $request->input('subtotal');
        $player->discount = $request->input('discount');
        $player->first_total = $request->input('first_total');
        $player->schedule = $request->schedule;

        $player->total = $request->input('first_total') +  $additionalSubTotal;


        // Set the last updated timestamp
        // $player->last_updated_at = now();

        // Save player
        $player->save();

        return response()->json(['status' => true]);
    }

    public function updateAdditional(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'additional_player' => 'required|numeric|min:0',
            'additional_per_player' => 'required|numeric|min:0',
        ]);

        // Fetch the player by ID
        $player = Category::findOrFail($id);

        // Getting Previous Player Data
        $previousPlayer = $player->additional_player ?? 0;
        $previousAdditionalSubTotal = $player->additional_sub_total ?? 0;
        $previousTotal = $player->total ?? 0;

        // Calculate the new totals
        $newAdditionalPlayer = $previousPlayer + $request->additional_player;
        $newAdditionalPerPlayer = $request->additional_per_player;
        $newAdditionalSubTotal = $previousAdditionalSubTotal + ($request->additional_player * $request->additional_per_player);
        $updateTotal = $previousTotal + ($request->additional_player * $request->additional_per_player);

        // Update the player's additional fields
        $player->additional_player = $newAdditionalPlayer;
        $player->additional_per_player = $newAdditionalPerPlayer;
        $player->additional_sub_total = $newAdditionalSubTotal;
        $player->total = $updateTotal;

        $player->save();

        return response()->json([
            'status' => true,
            'message' => 'Additional player data updated successfully',
        ]);
    }


    public function destroy($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if (empty($category)) {
            $request->session()->flash('error', 'Player not found');
            return response()->json([
                'status' => true,
                'message' => "Category not found"
            ]);
        }
        $category->delete();
        $request->session()->flash('success', 'Playerssssss deleted successfully');
        return response()->json([
            'status' => true,
            'message' => "Player deleted successfully"
        ]);
    }


    public function hide($id, Request $request)
    {
        $category = Category::findOrFail($id);
        $category->update([
            'is_active' => 0,
            'elapsed_time' => $request->input('elapsed_time')
        ]);

        return response()->json(['message' => 'Player marked as hidden successfully']);
    }

    public function list(Request $request)
    {
        $categories = Category::query();

        if (!empty($request->get('keyword'))) {
            $keyword = $request->get('keyword');
            $categories = $categories->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('created_at', 'like', '%' . $keyword . '%')
                    ->orwhere('name', 'like', '%' . $keyword . '%')
                    ->orWhere('cardnumber', 'like', '%' . $keyword . '%');
            });
        }

        // Filter by is_active if 'status' parameter is present
        if ($request->has('status') && $request->status != 'all') {
            $status = $request->input('status');
            if ($status === 'in') {
                $categories->where('is_active', 1);
            } elseif ($status === 'out') {
                $categories->where('is_active', 0);
            }
        } else {
            $status = 'all'; // Default status if none provided
        }

        // Retrieve distinct dates from database for dropdown
        $dates = Category::pluck('created_at')->map(function ($date) {
            return $date->format('Y-m-d');
        })->unique();

        // Default selected date (today's date)
        $selectedDate = $request->input('date', now()->format('Y-m-d'));

        // Apply date filter if 'date' parameter is present
        if ($request->has('date') && !empty($selectedDate)) {
            $categories->whereDate('created_at', $selectedDate);
        }

        $categories = $categories->orderBy('created_at', 'asc')->paginate(1000);

        return view('admin.category.reportlist', compact('categories', 'dates', 'selectedDate', 'status'));
    }












    public function summary(Request $request)
    {
        $categories = Category::query();

        if (!empty($request->get('keyword'))) {
            $keyword = $request->get('keyword');
            $categories = $categories->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('created_at', 'like', '%' . $keyword . '%')
                    ->orwhere('name', 'like', '%' . $keyword . '%')
                    ->orWhere('cardnumber', 'like', '%' . $keyword . '%');
            });
        }

        // Filter by is_active if 'status' parameter is present
        if ($request->has('status') && $request->status != 'all') {
            $status = $request->input('status');
            if ($status === 'in') {
                $categories->where('is_active', 1);
            } elseif ($status === 'out') {
                $categories->where('is_active', 0);
            }
        } else {
            $status = 'all'; // Default status if none provided
        }

        // Retrieve distinct dates from database for dropdown
        $dates = Category::pluck('created_at')->map(function ($date) {
            return $date->format('Y-m-d');
        })->unique();

        // Default selected date (today's date)
        $selectedDate = $request->input('date', now()->format('Y-m-d'));

        // Apply date filter if 'date' parameter is present
        if ($request->has('date') && !empty($selectedDate)) {
            $categories->whereDate('created_at', $selectedDate);
        }

        $categories = $categories->orderBy('created_at', 'asc')->paginate(1000);

        return view('admin.category.summarylist', compact('categories', 'dates', 'selectedDate', 'status'));
    }



    // list for addition player

    public function listAdditional(Request $request)
    {
        $categories = Category::query();

        if ($request->filled('keyword')) {
            $keyword = $request->get('keyword');
            $categories = $categories->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('created_at', 'like', '%' . $keyword . '%')
                    ->orWhere('cardnumber', 'like', '%' . $keyword . '%');
            });
        }

        // Filter by is_active if 'status' parameter is present
        if ($request->has('status') && $request->status != 'all') {
            $status = $request->input('status');
            $categories->where('is_active', $status === 'in' ? 1 : 0);
        } else {
            $status = 'all'; // Default status if none provided
        }

        // Retrieve distinct dates from database for dropdown
        $dates = Category::pluck('created_at')->map(fn($date) => $date->format('Y-m-d'))->unique();

        // Default selected date (today's date)
        $selectedDate = $request->input('date', now()->format('Y-m-d'));

        // Apply date filter if 'date' parameter is present
        if ($request->filled('date')) {
            $categories->whereDate('created_at', $selectedDate);
        }

        $categories = $categories->orderBy('created_at', 'asc')->paginate(1000);

        return view('admin.category.additionallist', compact('categories', 'dates', 'selectedDate', 'status'));
    }



    public function editAdditional($id, $additionalId)
    {
        $category = Category::findOrFail($id);
        $additionalPlayer = Category::findOrFail($additionalId);
        return view('admin.category.edit_additional', compact('category', 'additionalPlayer'));
    }

    // Handle the update request for additional player data
    public function updateAdditionallist(Request $request, $id, $additionalId)
    {
        $request->validate([
            'additional_player' => 'required|numeric|min:0',
            'additional_per_player' => 'required|numeric|min:0',
        ]);

        // Fetch the player by ID
        $player = Category::findOrFail($id);

        // Getting Previous Player Data
        $previousPlayer = $player->additional_player ?? 0;
        $previousAdditionalSubTotal = $player->additional_sub_total ?? 0;
        $previousTotal = $player->total ?? 0;

        // Calculate the new totals
        $newAdditionalPlayer = $request->additional_player;
        $newAdditionalPerPlayer = $request->additional_per_player;
        $newAdditionalSubTotal = $newAdditionalPlayer * $newAdditionalPerPlayer;
        $updateTotal = $previousTotal - $previousAdditionalSubTotal + $newAdditionalSubTotal;

        // Update the player's additional fields
        $player->additional_player = $newAdditionalPlayer;
        $player->additional_per_player = $newAdditionalPerPlayer;
        $player->additional_sub_total = $newAdditionalSubTotal;
        $player->total = $updateTotal;

        $player->save();
        return redirect()->route('player.listAdditional')->with('message', 'Additional player data updated successfully.');
    }
}
