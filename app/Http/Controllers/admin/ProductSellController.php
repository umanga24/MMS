<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductSell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ProductSellController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', 'all');
        $query = ProductSell::query();

        if ($date !== 'all') {
            $query->whereDate('created_at', $date);
        }

        $products = $query->paginate(20);
        $totalSales = $query->sum('total');
        $totalqty = $query->sum('qty');

        return view('admin.productsells.list', compact('products', 'date', 'totalSales', 'totalqty'));
    }

    public function create()
    {

        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        // $subCategories = SubCategory::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;

        return view('admin.productsells.create', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'brand_id' => 'required', // Validate that brand_id exists in the brands table
            'title' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'qty' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product = new ProductSell();
            $product->brand_id = $request->brand_id;
            $product->price = $request->price;
            $product->qty = $request->qty;
            $product->total = $request->total;
            $product->title = $request->title;
            // $product->description = $request->description;

            $product->save();
            $request->session()->flash('success', 'Product created successfully.');
            return response([
                'status' => true,
                'message' => 'Product created successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($productId, Request $request)
    {

        $brands = Brand::all();
        $product = ProductSell::find($productId);
        $userRole = auth('admin')->user()->role;
        if (empty($product)) {
            return redirect()->route('productsells.index');
        }

        if (in_array($userRole, [3]) && $product->created_at && Carbon::now()->diffInMinutes($product->created_at) > 10) {
            $request->session()->flash('error', 'You can only edit this form within 15 minutes of the last update.');
            return redirect()->route('productsells.index');
        }

        return view('admin.productsells.edit', compact('product', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = ProductSell::findOrFail($id);

        $rules = [
            'brand_id' => 'required',
            'price' => 'required|numeric|min:0',
            'qty' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'title' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product->brand_id = $request->brand_id;
            $product->price = $request->price;
            $product->qty = $request->qty;
            $product->total = $request->total;
            $product->title = $request->title;

            $product->save();

            $request->session()->flash('success', 'Product updated successfully.');
            return response([
                'status' => true,
                'message' => 'Product updated successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    // public function destroy($productId, Request $request)
    // {
    //     $product = ProductSell::find($productId);
    //     if (empty($product)) {
    //         $request->session()->flash('error', 'Product not found');
    //         return response()->json([
    //             'status' => false,
    //             'message' => "Product not found"
    //         ]);
    //     }
    //     $product->delete();

    //     return response()->jsone([
    //         'status' => true,
    //         'message' => 'Product delete Successfully'
    //     ]);
    // }



    public function destroy($productId, Request $request)
    {
        $product = ProductSell::find($productId);
        if (empty($product)) {
            $request->session()->flash('error', 'Product not found');
            return response()->json([
                'status' => false,
                'message' => "Product not found"
            ]);
        }
        $product->delete();

        return response()->jsone([
            'status' => true,
            'message' => 'Product delete Successfully'
        ]);
    }
}
