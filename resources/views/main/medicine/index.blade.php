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
                        {{-- @can('petugas') --}}
                            <div class="col-6 d-flex align-items-center">
                                <div class="m-auto"></div>
                                <a href="{{route('medicine.create')}}">
                                    <button type="button" class="btn btn-outline-primary">
                                        <i class="nav-icon fa fa-plus font-weight-bold"></i> Add
                                    </button>
                                </a>
                            </div>
                        {{-- @endcan --}}
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
                            {{-- <th>Stock</th> --}}
                            {{-- <th>Expired Date</th> --}}
                            <th>Status</th>
                            {{-- @can('petugas') --}}
                            <th>Action</th>
                            {{-- @endcan --}}
                        </thead>
                        <tbody>
                            @foreach ($medicines as $medicine)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <img src="{{ $medicine->image }}" class="img-fluid" width="100px" alt="{{$medicine->name}}">
                                    </td>
                                    <td>{{ $medicine->category->name }}</td>
                                    <td>{{ $medicine->name }}</td>
                                    <td>{{ $medicine->medicine_code }}</td>
                                    {{-- <td>{{ $medicine->stock . ' ' . $medicine->unit }}</td> --}}
                                    {{-- <td>{{ date_format(date_create($medicine->expired_date), 'd-m-Y') }}</td> --}}
                                    <td>
                                        <span
                                            class="badge {{ $medicine->is_active == true ? 'badge-primary' : 'badge-danger' }}">{{ $medicine->is_active == true ? 'Active' : 'Non-Active' }}</span>
                                    </td>
                                    {{-- @can('petugas') --}}
                                        <td>
                                            <a href="{{route('medicine.edit', $medicine->id)}}">
                                                <button class="btn btn-edit btn-primary">
                                                    <i class="fa fa-pencil text-white mr-2 pointer"></i> Edit
                                                </button>
                                            </a>
                                        </td>
                                    {{-- @endcan --}}
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
