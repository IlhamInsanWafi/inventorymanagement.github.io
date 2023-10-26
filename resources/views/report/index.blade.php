@extends('layouts.master')
@section('title', 'Report')
@section('content')
    <div class="section-body">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $page_title }}</h4>
                        <button onclick="printReport()" class="btn btn-primary"><i class="fas fa-print"></i></button>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-striped">
                            <thead>
                                <th>Sl No.</th>
                                <th>{{ $type == 'invoice' ? 'Invoice':'Purchase' }} No</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Due Amount</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $type == 'invoice' ? $report->invoice_no:$report->purchase_no }}</td>
                                        <td>{{ $report->total_amount }}</td>
                                        <td>{{ $report->paid_amount }}</td>
                                        <td>{{ $report->due_amount }}</td>
                                        <td>
                                            @if ($report->paid_amount < $report->total_amount)
                                                <span class="badge badge-danger">Unpaid</span>
                                            @else
                                            <span class="badge badge-success">Paid</span>
                                            @endif
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

@endsection
@section('scripts')
    <script src="{{ asset('assets/js/printThis.js') }}"></script>
    <script>
        function printReport() {
            $('#myTable').printThis();
        }
    </script>
@endsection
