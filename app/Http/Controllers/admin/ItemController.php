<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Item;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Psy\Readline\Hoa\Console;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::latest();
        return view('admin.item.create', compact('items'));
    }

    public function create()
    {

        return view('admin.item.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }



        // Create a new player
        $item = new Item();
        $item->name = $request->name;

        $item->save();

        $request->session()->flash('success', 'Item added successfully');
        return response()->json([
            'status' => true,
            'message' => 'Item added successfully'
        ]);

        return view();
    }

    public function edit()
    {

        return view();
    }

    public function update()
    {

        return view();
    }

    public function destroy()
    {

        return view();
    }
}
