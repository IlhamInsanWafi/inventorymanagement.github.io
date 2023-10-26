<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        $page_title = 'Product';

        return view('product.index', compact('products', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::where('status', 1)->get();
        $categories = Category::all();
        $page_title = 'Create Product';
        return view('product.create', compact('suppliers', 'categories', 'page_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png',
            'category_id' => 'required',
            'supplier_id' => 'required'
        ]);

        if ($request->hasFile('image')) {
            $image = date('YmdHis'). '.' . $request->file('image')->extension();
            Image::make($request->file('image'))->save(public_path('/uploads/product/').$image);
        }

        Product::create([
            'name' => $request->name,
            'image' => $image,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'brand' => $request->brand
        ]);

        return redirect()->route('product.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $suppliers = Supplier::where('status', 1)->get();
        $categories = Category::all();
        $page_title = 'Edit Product';
        return view('product.edit', compact('product', 'suppliers', 'categories', 'page_title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'image' => 'image|mimes:jpg,jpeg,png',
            'category_id' => 'required',
            'supplier_id' => 'required'
        ]);

        $image = '';
        if ($request->hasFile('image')) {
            $path = public_path('/uploads/product/').$product->image;
            if (file_exists($path)) {
                unlink($path);
            }
            $image = date('YmdHis'). '.' . $request->file('image')->extension();
            Image::make($request->file('image'))->save(public_path('/uploads/product/').$image);
        }

        $product->update([
            'name' => $request->name,
            'image' => $image ?:$product->image,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'brand' => $request->brand
        ]);

        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $path = public_path('/uploads/product/').$product->image;

        if (file_exists($path)) {
            unlink($path);
        }

        $product->delete();

        return back();
    }
}
