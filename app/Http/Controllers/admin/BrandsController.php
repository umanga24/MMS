<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;


class BrandsController extends Controller
{
    public function index(Request $request)

    {
        $brands = Brand::latest();
        if (!empty($request->get('keyword'))) {
            $brands = $brands->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $brands = $brands->paginate(10);
        return view('admin.brands.list', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            //'slug' => 'required|unique:brands'
        ]);
        if ($validator->passes()) {
            $brand = new Brand();
            $brand->name = $request->name;
            // $brand->slug = $request->slug;
            //$brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success', 'Brand added Successfullyyyyyyy');
            return response()->json([
                'status' => true,
                'message' => 'Brand added successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($brandId, Request $request)
    {
        $brand = Brand::find($brandId);
        if (empty($brand)) {
            return redirect()->route('brands.index');
        }
        return view('admin.brands.edit', compact('brand'));
    }

    public function update($brandId, Request $request)
    {
        $brand = Brand::find($brandId);
        if (empty($brand)) {
            $request->session()->flash('error', 'Brand not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Brand not found'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',

        ]);
        if ($validator->passes()) {
            $brand->name = $request->name;
            // $brand->slug = $request->slug;
            //$brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success', 'Brand Update Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Brand Update successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($brandId, Request $request)
    {
        $brand = Brand::find($brandId);
        if (empty($brand)) {
            $request->session()->flash('error', 'Brand not found');
            return response()->json([
                'status' => false,
                'message' => "Brand not found"
            ]);
        }
        $brand->delete();

        return response()->jsone([
            'status' => true,
            'message' => 'Brand delete Successfully'
        ]);
    }
}
