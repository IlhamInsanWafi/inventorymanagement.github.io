@extends('layouts.master')
@section('title', 'Show | Invoice')
@section('content')
    <div class="section-body">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $page_title }}</h4>
                    </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Purchase No</label>
                                        <input type="text" name="invoice_no" id="invoice_no" class="form-control" disabled value="{{ $invoice->invoice_no }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Supplier</label>
                                        <input type="text" name="invoice_no" id="invoice_no" class="form-control" disabled value="{{ $invoice->customer->name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Paid Amount</label>
                                        <input type="text" name="paid_amount" id="paid_amount" class="form-control" value="{{ $invoice->paid_amount }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <table class="table table-responsive table-striped">
                                        <thead>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Product</th>
                                            <th class="text-center">Unit</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Unit Price</th>
                                            <th class="text-center">Total</th>
                                        </thead>
                                        <tbody class="tbody">
                                            @foreach ($invoice->invoiceMeta as $item)
                                            <tr class="tr">
                                                <td>
                                                    <input type="text" id="invoice_no" class="form-control" disabled value="{{ $item->category->name }}">
                                                </td>
                                                <td>
                                                    <input type="text" id="invoice_no" class="form-control" disabled value="{{ $item->product->name }}">
                                                </td>
                                                <td>
                                                    <input type="text" id="invoice_no" class="form-control" disabled value="{{ $item->unit->name }}">
                                                </td>
                                                <td><input type="text" id="invoice_no" class="form-control" disabled value="{{ $item->quantity }}"></td>
                                                <td><input type="text" id="invoice_no" class="form-control" disabled value="{{ $item->unit_price }}"></td>
                                                <td><input type="text" id="invoice_no" class="form-control" disabled value="{{ $item->unit_price * $item->quantity }}"></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <th colspan="5">Total</th>
                                            <th><input class="form-control" type="text" name="total_amount" id="total" placeholder="All Total" disabled value="{{ $invoice->total_amount }}"></th>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection