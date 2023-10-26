<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Category;
use App\Models\InvoiceMeta;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::all();
        $page_title = 'Invoice List';
        return view('invoice.index', compact('invoices', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $invoice_no = $this->uniqueNumber();
        $page_title = 'Create Invoice';
        $customers = Supplier::where('status', 2)->get();
        $categories = Category::all();
        $units = Unit::all();
        return view('invoice.create', compact('invoice_no', 'page_title', 'customers', 'categories', 'units'));
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
            'invoice_no' => 'required',
            'customer_id' => 'required',
            'paid_amount' => 'required',
            'total_amount' => 'required',
            'paid_amount' => 'required',
            'category_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required',
        ]);

        $invoice = Invoice::create([
            'invoice_no' => $request->invoice_no,
            'customer_id' => $request->customer_id,
            'paid_amount' => $request->paid_amount,
            'total_amount' => $request->total_amount,
            'due_amount' => (int)$request->total_amount-(int)$request->paid_amount,
        ]);

        for ($i=0; $i < count($request->category_id); $i++) { 
                InvoiceMeta::create([
                    'invoice_id' => $invoice->id,
                    'category_id' => $request->category_id[$i],
                    'product_id' => $request->product_id[$i],
                    'quantity' => $request->quantity[$i],
                    'unit_price' => $request->unit_price[$i],
                    'unit_id' => $request->unit_id[$i]
                ]);
            }

        return redirect()->route('invoice.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        $page_title = 'Invoice View';
        return view('invoice.show', compact('invoice', 'page_title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $page_title = 'Purchase Edit';
        $customers = Supplier::where('status', 2)->get();
        $categories = Category::all();
        $units = Unit::all();
        return view('invoice.edit', compact('invoice', 'page_title', 'customers', 'categories', 'units'));
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
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'invoice_no' => 'required',
            'customer_id' => 'required',
            'paid_amount' => 'required',
            'total_amount' => 'required',
            'paid_amount' => 'required',
            'category_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required',
        ]);

        $invoice->update([
            'invoice_no' => $request->invoice_no,
            'customer_id' => $request->customer_id,
            'paid_amount' => $request->paid_amount,
            'total_amount' => $request->total_amount,
            'due_amount' => (int)$request->total_amount-(int)$request->paid_amount,
        ]);

        foreach ($invoice->invoiceMeta as $item) {
            $item->delete();
        }
        for ($i=0; $i < count($request->category_id); $i++) { 
                InvoiceMeta::create([
                    'invoice_id' => $invoice->id,
                    'category_id' => $request->category_id[$i],
                    'product_id' => $request->product_id[$i],
                    'quantity' => $request->quantity[$i],
                    'unit_price' => $request->unit_price[$i],
                    'unit_id' => $request->unit_id[$i]
                ]);
            }

        return redirect()->route('invoice.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        foreach ($invoice->invoiceMeta as $item) {
            $item->delete();
        }

        $invoice->delete();

        return back();
    }

    public function uniqueNumber()
    {
        $invoice = Invoice::latest()->first();
        if ($invoice) {
            $name = $invoice->invoice_no;
            $number = explode('_', $name);
            $invoice_no = 'INV_'. str_pad((int)$number[1] + 1, 6, "0", STR_PAD_LEFT);
        }else {
            $invoice_no = 'INV_000001';
        }
        return $invoice_no;
    }
}
