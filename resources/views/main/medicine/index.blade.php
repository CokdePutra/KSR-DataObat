@extends('template.master')

@section('page-title', 'Medicine')
@section('page-sub-title', 'Data')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            Data Medicine
                        </div>
                        <div class="col-6 d-flex align-items-center">
                            <div class="m-auto"></div>
                            @can('admin')
                                <a href="{{ route('medicine.create') }}">
                                    <button type="button" class="btn btn-outline-primary">
                                        <i class="nav-icon fa fa-plus font-weight-bold"></i> Add
                                    </button>
                                </a>
                            @endcan
                            <button type="button" class="btn btn-outline-success ml-2 btn-print">
                                <i class="nav-icon fa fa-print font-weight-bold"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-striped" id="tableData">
                        <thead>
                            <th>No</th>
                            <th>Image</th>
                            <th>Category Name</th>
                            <th>Medicine Name</th>
                            <th>Medicine Code</th>
                            {{-- <th>Description</th> --}}
                            {{-- <th>Expired Date</th> --}}
                            <th>Status</th>
                            @can('admin')
                                <th>Action</th>
                            @endcan
                        </thead>
                        <tbody>
                            @foreach ($medicines as $medicine)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <img src="{{ $medicine->image }}" class="img-fluid" width="100px"
                                            alt="{{ $medicine->name }}">
                                    </td>
                                    <td>{{ $medicine->category->name }}</td>
                                    <td>{{ $medicine->name }}</td>
                                    <td>{{ $medicine->medicine_code }}</td>
                                    {{-- <td>
                                        <p class="description">
                                            {{ $medicine->description ?? '-' }}
                                        </p>
                                    </td> --}}
                                    {{-- <td>{{ $medicine->stock . ' ' . $medicine->unit }}</td> --}}
                                    {{-- <td>{{ date_format(date_create($medicine->expired_date), 'd-m-Y') }}</td> --}}
                                    <td>
                                        <span
                                            class="badge {{ $medicine->is_active == true ? 'badge-primary' : 'badge-danger' }}">{{ $medicine->is_active == true ? 'Active' : 'Inactive' }}</span>
                                    </td>
                                    @can('admin')
                                        <td>
                                            <div class="row">
                                                <div class="col-3">
                                                    <a href="{{ route('medicine.edit', $medicine->id) }}">
                                                        <button class="btn btn-edit btn-primary">
                                                            <i class="fa fa-pencil text-white mr-2 pointer"></i> Edit
                                                        </button>
                                                    </a>
                                                </div>
                                                <div class="col-9">
                                                    <form method="POST"
                                                        action="{{ route('medicine.delete', $medicine->id) }}">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <button class="btn btn-delete btn-danger"
                                                            data-id="{{ $medicine->id }}">
                                                            <i class="fa fa-trash-alt text-white mr-2 pointer"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
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

@section('modal')
    <div class="modal fade" id="modalPrint" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Print Report</h5>
                    <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times-circle"></i>
                    </button>
                </div>
                <form action="{{ route('medicine.print') }}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <label for="start">Start Date (Date created)</label>
                                <input type="date" name="start_date" id="start_date" class="form-control"
                                    value="{{ date_format(date_create(now()), 'Y-m-d') }}" max="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-6">
                                <label for="end">End Date (Date created)</label>
                                <input type="date" name="end_date" id="end_date" class="form-control"
                                    value="{{ date_format(date_create(now()), 'Y-m-d') }}" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary pull-right" type="submit">Print</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            @if (session('status'))
                Swal.fire(
                    "{{ session('title') }}",
                    "{{ session('message') }}",
                    "{{ session('status') }}",
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

            $("body").on("click", ".btn-delete", function(event) {
                var form = $(this).closest("form");
                event.preventDefault();
                Swal.fire({
                    title: "Delete this item?",
                    text: "Data will be deleted",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, deleted!",
                }).then((result) => {
                    if (result.value) {
                        form.submit();
                    }
                });
            });

            $('body').on('click', '.btn-print', function() {
                $('#modalPrint').modal('show');
            })
        });
    </script>
@endpush
