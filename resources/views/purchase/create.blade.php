@extends('layouts.master')
@section('title', 'Create | Purchase')
@section('content')
    <div class="section-body">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $page_title }}</h4>
                    </div>
                    <form action="{{ route('purchase.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Purchase No</label>
                                        <input type="text" name="purchase_no" id="purchase_no" class="form-control" readonly value="{{ $purchase_no }}">
                                    </div>
                                    @error('purchase_no')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Supplier</label>
                                        <select class="form-control" name="supplier_id" id="supplier_id">
                                            <option selected>Choose Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    @error('supplier_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Paid Amount</label>
                                        <input type="text" name="paid_amount" id="paid_amount" class="form-control">
                                    </div>
                                    @error('paid_amount')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
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
                                            <th class="text-center">
                                                <button onclick="cloneRow()" type="button" class="btn btn-success"><i class="fas fa-plus"></i></button>
                                            </th>
                                        </thead>
                                        <tbody class="tbody">
                                            <tr class="tr">
                                                <td>
                                                    <select class="form-control category" name="category_id[]" id="category_1">
                                                        <option selected>Choose Category</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="product_id[]" id="product_1">
                                                        <option selected>Choose Product</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="unit_id[]" id="unit_1">
                                                        <option selected>Choose Unit</option>
                                                        @foreach ($units as $unit)
                                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input class="form-control" type="text" name="quantity[]" id="quantity_1" placeholder="Quantity" onkeyup="calculateTotal(event)"></td>
                                                <td><input class="form-control" type="text" name="unit_price[]" id="price_1" placeholder="Unit Price" onkeyup="calculateTotal(event)"></td>
                                                <td><input class="form-control total" type="text" id="total_1" placeholder="Total" disabled></td>
                                                <td><button type="button" onclick="removeRow(event)" class="btn btn-danger">X</button></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <th colspan="5">Total</th>
                                            <th><input class="form-control" type="text" name="total_amount" id="total" placeholder="All Total" readonly></th>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let count = 2;
        function cloneRow() {
            const tr = `
            <tr class="tr">
                <td>
                    <select class="form-control category" name="category_id[]" id="category_${count}">
                        <option selected>Choose Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-control" name="product_id[]" id="product_${count}">
                        <option selected>Choose Product</option>
                    </select>
                </td>
                <td>
                    <select class="form-control" name="unit_id[]" id="unit_${count}">
                        <option selected>Choose Unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input class="form-control" type="text" name="quantity[]" id="quantity_${count}" placeholder="Quantity" onkeyup="calculateTotal(event)"></td>
                <td><input class="form-control" type="text" name="unit_price[]" id="price_${count}" placeholder="Unit Price" onkeyup="calculateTotal(event)"></td>
                <td><input class="form-control total" type="text" id="total_${count}" placeholder="Total" disabled></td>
                <td><button type="button" onclick="removeRow(event)" class="btn btn-danger">X</button></td>
            </tr>
            `;

            $('.tbody').append(tr);
            count++;
        }
        function removeRow(event) {
            if ($('.tr').length > 1) {
                $(event.target).closest('.tr').remove();
            }
        }
        function calculateTotal(event) {
            let allTotal = 0;
            const id = $(event.target).attr('id');
            const num = id.split('_');
            const quantity = parseFloat($('#quantity_'+num[1]).val());
            const price = parseFloat($('#price_'+num[1]).val());
            const total = quantity * price;
            $('#total_'+num[1]).val(total);
            $('.total').each(function () {
                const value = parseFloat($(this).val());
                if ($(this).val() != '') {
                    allTotal += value;
                }
            })
            $('#total').val(allTotal);
        }

        $(document).on('change', '.category', function () {
            const id = $(this).val();
            const dataId = $(this).attr('id');
            const num = dataId.split('_');
            $.ajax({
                type: "get",
                url: "{{ route('product.get', '') }}" + "/" + id,
                dataType: "json",
                success: function (data) {
                    let html = '<option selected>Choose Product</option>';
                    data.forEach(product => {
                        html += `<option value="${product.id}">${product.name}</option>`;
                    });
                    console.log(html);
                    $('#product_'+num[1]).html(html);
                }
            });
        })
    </script>
@endsection