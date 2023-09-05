@extends('template.master')

@section('page-title', 'Category')
@section('page-sub-title', 'Data')


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            Data Category
                        </div>
                        @can('operator')
                            <div class="col-6 d-flex align-items-center">
                                <div class="m-auto"></div>
                                <a href="{{ route('category.create') }}">
                                    <button type="button" class="btn btn-outline-primary">
                                        <i class="nav-icon fa fa-plus font-weight-bold"></i> Add
                                    </button>
                                </a>

                                {{-- <button class="btn btn-primary btn-print" onclick="printData()">Print</button> --}}

                                <button type="button" class="btn btn-outline-success btn-print ml-2">
                                    <i class="nav-icon fa fa-print font-weight-bold"></i> Print
                                </button>
                            </div>
                        @endcan
                    </div>
                </div>
                <div class="card-body" id="testing">
                    <table class="table table-hover table-striped" id="tableData">
                        <thead>
                            <th>No</th>
                            <th>Category Name</th>
                            <th>Status</th>
                            @can('operator')
                                <th>Action</th>
                            @endcan
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $category->is_active == true ? 'badge-primary' : 'badge-danger' }}">{{ $category->is_active == true ? 'Active' : 'Inactive' }}</span>
                                    </td>
                                    @can('operator')
                                        <td>
                                            <a href="{{ route('category.edit', $category->id) }}">
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
    <script src="{{ asset('assets/js/print/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("body").on("click", ".btn-print", function() {
                Swal.fire({
                    title: "Cetak data kategori?",
                    text: "Laporan akan dicetak",
                    icon: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, cetak!",
                }).then((result) => {
                    if (result.value) {
                        // Open a new window for printing
                        var printWindow = window.open('', '', '');
                        // var printWindow = window.open('', '', 'width=600,height=600');

                        // AJAX request to fetch the printable content
                        var mode = "iframe"; //popup
                var close = mode == "popup";
                        var options = {
                    mode: mode,
                    popClose: close,
                    popTitle: "LaporanDataKategori",
                    // popOrient: "landscape",
                };
                        $.ajax({
                            type: "GET",
                            url: "/category/print/",
                            dataType: "json",
                            success: function(response) {
                                // Check if the response contains the expected content
                                if (response && response.data) {
                                    // Write the content to the new window
                                    // printWindow.document.write(response.data);

                                    // // Print the window
                                    // printWindow.document.close();
                                    // printWindow.print();
                                    // printWindow.close();

                                    document.title =
                            "PT. PANUDUH ATMA WARAS | Distribusi Buku - Print" +
                            new Date().toJSON().slice(0, 10).replace(/-/g, "/");
                        $(response.data)
                            .find("div.printableArea")
                            .printArea(options);
                                } else {
                                    // Handle an unexpected response or data missing from the response
                                    Swal.fire({
                                        title: "Error",
                                        text: "Data untuk cetak tidak ditemukan.",
                                        icon: "error",
                                    });
                                }
                            },
                            error: function() {
                                // Handle AJAX request errors
                                Swal.fire({
                                    title: "Error",
                                    text: "Gagal memuat data untuk cetak.",
                                    icon: "error",
                                });
                            },
                        });
                    }
                });
            });

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
        });
    </script>
@endpush
