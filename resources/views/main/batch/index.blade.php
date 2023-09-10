@extends('template.master')

@section('page-title', 'Medicine Batch')
@section('page-sub-title', 'Data')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            Data Medicine Batch
                        </div>
                        @can('operator')
                            <div class="col-6 d-flex align-items-center">
                                <div class="m-auto"></div>
                                <a href="{{route('batch.create')}}">
                                    <button type="button" class="btn btn-outline-primary">
                                        <i class="nav-icon fa fa-plus font-weight-bold"></i> Add
                                    </button>
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped" id="tableData">
                        <thead>
                            <th>No</th>
                            <th>Medicine Name</th>
                            <th>Batch Number</th>
                            <th>Quantity</th>
                            <th>Stock</th>
                            <th>Expired Date</th>
                            <th>Status</th>
                            @can('operator')
                            <th>Action</th>
                            @endcan
                        </thead>
                        <tbody>
                            @foreach ($batches as $batch)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $batch->medicine->name }}</td>
                                    <td>{{ $batch->batch_number }}</td>
                                    <td>{{ $batch->quantity . ' ' . $batch->medicine->unit }}</td>
                                    <td>{{ $batch->stock }}</td>
                                    <td>{{ date_format(date_create($batch->expired_date), 'd-m-Y') }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $batch->is_active == true ? 'badge-primary' : 'badge-danger' }}">{{ $batch->is_active == true ? 'Active' : 'Inactive' }}</span>
                                    </td>
                                    @can('operator')
                                        <td>
                                            <a href="{{route('batch.edit', $batch->id)}}">
                                                <button class="btn btn-edit btn-primary">
                                                    <i class="fa fa-pencil text-white mr-2 pointer"></i> Edit
                                                </button>
                                            </a>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            @if(session('status'))
            Swal.fire(
                "{{session('title')}}",
                "{{session('message')}}",
                "{{session('status')}}",
            );
            @endif
            var table = $('#tableData').DataTable({
                language: {
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    },
                    info: "Showing _START_ to _END_ from _TOTAL_ data",
                    infoEmpty: "Showing 0 to 0 from 0 data",
                    lengthMenu: "Showing _MENU_ data",
                    search: "Search:",
                    emptyTable: "Data doesn't exists",
                    zeroRecords: "Data doesn't match",
                    loadingRecords: "Loading..",
                    processing: "Processing...",
                    infoFiltered: "(filtered from _MAX_ total data)"
                },
                lengthMenu: [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"]
                ],
                order: [
                    [0, 'desc']
                ],
                "rowCallback": function(row, data, index) {
                    // Set the row number as the first cell in each row
                    $('td:eq(0)', row).html(index + 1);
                }
            });

            // Update row numbers when the table is sorted
            table.on('order.dt search.dt', function() {
                table.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });
    </script>
@endpush
