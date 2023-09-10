@extends('template.master')

@section('page-title', 'Medicine Out')
@section('page-sub-title', 'Data')

@push('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            Add Medicine Out
                        </div>
                        {{-- @can('petugas') --}}
                        <div class="col-6 d-flex align-items-center">
                            <div class="m-auto"></div>
                            <a href="{{ route('batch.index') }}">
                                <button type="button" class="btn btn-outline-primary">
                                    <i class="nav-icon fa fa-eye font-weight-bold"></i> View Data
                                </button>
                            </a>
                        </div>
                        {{-- @endcan --}}
                    </div>
                </div>
                <form action="{{ route('outgoing.store') }}" method="POST" id="form">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="out_date">Medicine Out Date</label>
                            <input type="date" class="form-control" name="out_date" id="out_date"
                                placeholder="enter medicine out date" autocomplete="off" autofocus max="{{date('Y-m-d')}}">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" rows="5" name="description" id="description" placeholder="enter description"
                                autocomplete="off"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary btn-search-medicine float-right mt-2 mb-2">
                                <i class="fa fa-search"></i> Search
                            </button>
                            <table class="table table-hover table-bordered" id="medicineTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Medicine Name</th>
                                        <th>Batch Number</th>
                                        <th>Expired Date</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail</h5>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times-circle"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="keyword" id="keyword" placeholder="search..."
                                autocomplete="off">
                            <div class="input-group-append">
                                <span class="input-group-text bg-transparent" id="basic-addon2">
                                    <i class="i-Search-People"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <table class="table table-hover table-bordered" id="medicineDetail">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Category</th>
                                <th>Medicine Name</th>
                                <th>Medicine Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center" colspan="5">No data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\OutgoingMedicineRequest', '#form') !!}

    <script>
        function getCart() {
            $('#medicineTable tbody').empty()
            let list = '';
            if (!localStorage.getItem('cart')) {
                list += '<tr><td class=text-center colspan=6>No data</td></tr>';
            } else {
                $.each(JSON.parse(localStorage.getItem('cart')), function(index, cart) {
                    list += '<tr>';
                    list += '<td>' + (index + 1) + '</td>';
                    list += '<td>' + cart.medicine_name + '</td>';
                    list += '<td>' + cart.batch_number + '</td>';
                    list += '<td>' + cart.expired_date + '</td>';
                    list += '<td>';
                    list += '<input type=text class="form-control text-center" data-batch-number="' + cart
                        .batch_number + '" id="quantity" name="quantity[]" value="' + cart.quantity + '">';
                    list += '<input type=hidden name="batch_number[]" value="'+cart.batch_number+'">'
                    list += '</td>';
                    list +=
                        '<td><button type=button class="btn btn-danger btn-remove btn-rounded" data-batch-number="' +
                        cart
                        .batch_number + '"><i class="fa fa-trash"></i></button></td>';
                        list += '</tr>';
                });
            }

            $('#medicineTable tbody').append(list);
        }
        $(document).ready(function() {
            localStorage.clear('cart')
            getCart();
            @if (session('status'))
                Swal.fire(
                    "{{ session('title') }}",
                    "{{ session('message') }}",
                    "{{ session('status') }}",
                );
            @endif

            $('.btn-search-medicine').click(function() {
                $('#modal').modal('show');
            });

            $('#keyword').keyup(function() {
                let value = $(this).val() ?? 'empty';
                let category = 'searchDetail';
                let keyword = category + '-' + value;
                let list = '';
                $.get("/outgoing/medicine-search/" + keyword, function(data) {
                    $('#medicineDetail tbody').empty();
                    if (data.length == 0) {
                        list += '<tr><td colspan="5" class="text-center">No Data</td></tr>';
                    } else {
                        $.each(data, function(index, medicine) {
                            list += '<tr  data-toggle="collapse" data-target="#row' + (
                                index + 1) + '" class="clickable">';
                            list += '<td>' + (index + 1) + '</td>';
                            list += '<td>' + medicine.category.name + '</td>';
                            list += '<td>' + medicine.name + '</td>';
                            list += '<td>' + medicine.medicine_code + '</td>';
                            list +=
                                '<td><button type=button class="btn btn-primary btn-rounded">View</button></td>';
                            list += '</tr>';

                            $.each(medicine.batches, function(key, batch) {
                                list += '<tr id="row' + (index + 1) +
                                    '" class="collapse">';
                                list += '<td colspan=5>';
                                list += '<div class=row>';
                                list += '<div class="col-7">';
                                list += '<div class=row>';
                                list += '<div class=col-4>Batch Number</div>';
                                list += '<div class=col-8>: ' + batch.batch_number +
                                    '</div>';
                                list += '<div class=col-4>Stock</div>';
                                list += '<div class=col-8>: ' + batch.stock +
                                    '</div>';
                                list += '<div class=col-4>Expired Date</div>';
                                list += '<div class=col-8>: ' + batch.expired_date +
                                    '</div>';
                                list += '</div>';
                                list += '</div>';
                                list += '<div class="col-5">';
                                list +=
                                    '<button type=button class="btn btn-success text-white btn-add-medicine" data-batch-stock="'+batch.stock+'" data-batch-quantity="' +
                                    batch.quantity + '" data-expired-date="' + batch
                                    .expired_date + '" data-batch-number="' + batch
                                    .batch_number + '" data-medicine-name="' +
                                    medicine.name +
                                    '"><i class="fa fa-plus-circle"></i></button>';
                                list += '</div>';
                                list += '</div>';
                                list += '</td>';
                                list += '<tr>';
                            });
                        });
                    }

                    $('#medicineDetail tbody').append(list);
                });
            });

            $('body').on('click', '.btn-add-medicine', function() {
                let medicineName = $(this).data('medicine-name');
                let batchNumber = $(this).data('batch-number');
                let expiredDate = $(this).data('expired-date');
                let batchStock = $(this).data('batch-stock');

                var cart = JSON.parse(localStorage.getItem('cart')) || [];

                let existingBatch = cart.find(item => item.batch_number === batchNumber);
                if (!existingBatch) {
                    var newData = {
                        medicine_name: medicineName,
                        batch_number: batchNumber,
                        expired_date: expiredDate,
                        stock: batchStock,
                        quantity: 1,
                    };
                    cart.push(newData);

                    localStorage.setItem('cart', JSON.stringify(cart));
                    getCart();

                    Swal.fire(
                        "Success",
                        "Success to add medicine to cart",
                        "success"
                    )
                } else {
                    Swal.fire(
                        "Error",
                        "Medicine already in cart",
                        "error"
                    )
                }
            })

            $('body').on('click', '.btn-remove', function() {
                let batchNumber = $(this).data('batch-number');

                Swal.fire({
                    title: "Remove",
                    text: "Data will be remove from cart?",
                    icon: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Delete!",
                }).then((result) => {
                    if (result.value) {
                        var cart = JSON.parse(localStorage.getItem('cart')) || [];
                        cart = cart.filter(item => item.batch_number !== batchNumber)

                        localStorage.setItem('cart', JSON.stringify(cart))

                        Swal.fire(
                            "Success",
                            "Data removed",
                            "success"
                        );

                        getCart();
                    }
                });
            });

            $('body').on('blur', '#quantity', function() {
                let value = $(this).val();
                let batchNumber = $(this).data('batch-number');

                cart = JSON.parse(localStorage.getItem('cart')) || [];
                let findData = cart.find(item => item.batch_number === batchNumber);
                if (findData) {
                    if (findData.stock >= value) {
                        findData.quantity = value;

                        localStorage.setItem('cart', JSON.stringify(cart))

                        Swal.fire(
                            "Success",
                            "Quantity updated",
                            "success"
                        );
                        getCart()
                    } else {
                        Swal.fire("Error", "Stock not enough!", "error");
                        getCart();
                    }
                }
            });
            // $('#form').submit(function(e) {
            //     // e.preventDefault();
            //     $.ajaxSetup({
            //         headers: {
            //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            //         },
            //     });
            //     let form = $("#form")[0];
            //     let data = new FormData(form);
            //     data.append('batch', JSON.parse(localStorage.getItem('cart')))
            //     $.ajax({
            //         type: "POST",
            //         url: "{{route('outgoing.store')}}",
            //         data: data,
            //         processData: false,
            //         contentType: false,
            //         cache: false,
            //         success: function(response) {
            //             //
            //         },
            //         error: function(error) {
            //             console.log(error)
            //         },
            //     });
            // });
        });
    </script>
@endpush
