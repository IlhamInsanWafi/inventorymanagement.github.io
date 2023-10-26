@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
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

    function CloneRow() {
        const tr = `
        <tr class="tr">
            <td>
                <select class="form-control" name="category_id" id="category_id">
                    <option selected>Choose Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select class="form-control" name="category_id[]" id="category_id">
                    <option value="1">Iphone 14 pro max</option>
                </select>
            </td>
            <td><select class="form-control" name="category_id[]" id="category_id">
                    <option value="1">Piece</option>
                </select></td>
            <td><input class="form-control qty" type="text" onkeyup="qty(event)" id="qty_${count}" placeholder="Quantity"></td>
            <td><input class="form-control price" type="text" id="price_${count}" placeholder="Unit Price"></td>
            <td><input class="form-control total" type="text" id="total_${count}" placeholder="Total" disabled></td>
            <td><button onclick="removeRow(event)" class="btn btn-danger">X</button></td>
        </tr>`;
        $("tbody").append(tr);
        count++;
    }

    $('.category').change( function () {
        const id = $(this).attr('id');
        $.ajax({
            type: "get",
            url: "{{ route('product.get', '') }}" + "/" + $(this).val(),
            dataType: "json",
            success: function (response) {
                const num = id.split('_');
                console.log(num[1]);
                let html = '<option selected>Choose Product</option>';
                response.forEach(element => {
                    html += `<option value="${element.id}">${element.name}</option>`
                });
                $('#product_id_'+num[1]).html(html);
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    function removeRow(event) {
        if ($('.tr').length > 1) {
            $(event.target).closest('.tr').remove();
        }
    }

    let total = 0;
    
    function qty(event) {
        let allTotal = 0;
        const dataId = $(event.target).attr('id');
        const num = dataId.split('_');
        total = parseFloat($('#qty_' + num[1]).val()) * parseFloat($('#price_' + num[1]).val());
        $('#total_' + num[1]).val(total);
        $(".total").each(function() {
            console.log(parseFloat($(this).val()));
            if ($(this).val() != '') {
                allTotal += parseFloat($(this).val());
            }
        });
        console.log(allTotal);
        $('#total').val(allTotal);

    }
</script>
@endsection