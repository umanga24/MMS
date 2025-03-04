<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSell;
use App\Models\SubCategory;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', 'all');
        $query = Product::query();

        if ($date !== 'all') {
            $query->whereDate('created_at', $date);
        }

        $products = $query->paginate(20);
        $totalPurchases = $query->sum('total');
        $totalQuantity = $query->sum('qty');

        return view('admin.products.list', compact('products', 'date', 'totalPurchases', 'totalQuantity'));
    }


    public function create()
    {

        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        // $subCategories = SubCategory::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;

        return view('admin.products.create', $data);
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
            $product = new Product();
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
        $product = Product::find($productId);
        $userRole = auth('admin')->user()->role;

        if (!$product) {
            return redirect()->route('products.index');
        }

        if (in_array($userRole, [3]) && $product->created_at && Carbon::now()->diffInMinutes($product->created_at) > 10) {
            $request->session()->flash('error', 'You can only edit this form within 15 minutes of the last update.');
            return redirect()->route('products.index');
        }

        return view('admin.products.edit', compact('product', 'brands'));
    }

    public function update(Request $request, $id)

    {
        $product = Product::findOrFail($id);

        $rules = [
            'brand_id' => 'required|exists:brands,id',
            'title' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'qty' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product->brand_id = $request->brand_id;
            $product->price = $request->price;
            $product->qty = $request->qty;
            $product->total = $request->total;
            $product->title = $request->title;

            $product->save();

            // $request->session()->flash('success', 'Product updated successfully.');
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
    public function destroy($productId, Request $request)
    {
        $product = Product::find($productId);
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


    public function list()
    {
        $brands = Brand::all();

        $totals = $brands->map(function ($brand) {
            $totalBought = Product::where('brand_id', $brand->id)->sum('qty');
            $totalSold = ProductSell::where('brand_id', $brand->id)->sum('qty');
            $remaining = $totalBought - $totalSold;

            return [
                'brand' => $brand->name,
                'total_bought' => $totalBought,
                'total_sold' => $totalSold,
                'remaining' => $remaining,
            ];
        });

        return view('admin.products.report', compact('totals'));
    }
}
