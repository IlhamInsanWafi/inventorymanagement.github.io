@extends('layouts.master')
@section('title', 'Product')
@section('content')
    <div class="section-body">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $page_title }}</h4>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-striped">
                            <thead>
                                <th>Sl No.</th>
                                <th>Name</th>
                                <th>Thumbnail</th>
                                <th>Category</th>
                                <th>Supplier</th>
                                <th>Brand</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            <img src="{{ url('/uploads/product/'.$product->image) }}" alt="Product Img" width="60">
                                        </td>
                                        <td>{{ $product->category->name }}</td>
                                        <td>{{ $product->supplier->name }}</td>
                                        <td>{{ $product->brand }}</td>
                                        <td>
                                            <a class="btn btn-primary"
                                                href="{{ route('product.edit', $product->id) }}">Edit</a> |
                                            <button type="button" class="btn btn-danger delete" data-toggle="modal"
                                                data-target="#exampleModal" id="{{ $product->id }}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="deleteModal" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this data?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();

            $('.delete').on('click', function () {
                const id = this.id;
                $('#deleteModal').attr('action', '{{ route("product.destroy", "") }}' + '/' + id);
            });
        });
    </script>
@endsection
