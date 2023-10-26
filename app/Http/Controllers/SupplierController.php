<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Image;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::where('status', 1)->get();
        $page_title = 'Suppliers Info';
        return view('supplier.index', compact('suppliers', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('supplier.create');
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
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
            'email' => 'required|unique:suppliers,email',
            'phone' => 'required|numeric|unique:suppliers,phone',
            'address' => 'required',
        ]);

        $image = '';
        if ($request->hasFile('image')) {
            $image = date('YmdHis'). '.' . $request->file('image')->extension();
            Image::make($request->file('image'))->save(public_path('/uploads/supplier/').$image);
        }

        Supplier::create([
            'name' => $request->name,
            'image' => $image ? $image:'',
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status
        ]);

        if ($request->status == 1) {
            return redirect()->route('supplier.index');
        }

        return redirect()->route('customer.index');
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
        $supplier = Supplier::findOrFail($id);
        return view('supplier.edit', compact('supplier'));
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
        $supplier = Supplier::findOrFail($id);

        if ($request->email != $supplier->email) {
            $request->validate([
                'email' => 'required|unique:suppliers,email'
            ]);
        }

        if ($request->phone != $supplier->phone) {
            $request->validate([
                'phone' => 'required|numeric|unique:suppliers,phone',
            ]);
        }
        $request->validate([
            'name' => 'required|string',
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
            'email' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required',
        ]);

        $image = '';
        if ($request->hasFile('image')) {
            $path = public_path('/uploads/supplier/').$supplier->image;
            if (file_exists($path)) {
                unlink($path);
            }

            $image = date('YmdHis'). '.' . $request->file('image')->extension();
            Image::make($request->file('image'))->save(public_path('/uploads/supplier/').$image);
        }

        $supplier->update([
            'name' => $request->name,
            'image' => $image ?:$supplier->image,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        if ($request->status == 1) {
            return redirect()->route('supplier.index');
        }
        return redirect()->route('customer.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        $path = public_path('/uploads/supplier/').$supplier->image;
        if (file_exists($path)) {
            unlink($path);
        }

        $supplier->delete();

        return back();
    }
}
