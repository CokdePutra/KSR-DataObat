@extends('template.master')

@section('page-title', 'Batch')
@section('page-sub-title', 'Data')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            Edit Batch
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
                <form action="{{ route('batch.update') }}" method="POST" id="form">
                    @csrf
                    <div class="card-body">
                        <input type="hidden" name="id" id="id" value="{{ $batch->id }}">
                        <div class="form-group">
                            <label for="medicine_id">Medicine Name</label>
                            <select name="medicine_id" id="medicine_id" class="form-control js-states" style="width: 100%;">
                                <option></option>
                                @foreach ($medicines as $key => $value)
                                    <option value="{{ $key }}" {{$key == $batch->medicine_id ? 'selected' : ''}}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group hidden-input" hidden>
                            <label for="category">Category</label>
                            <input type="text" id="category" class="form-control" disabled>
                        </div>
                        <div class="form-group hidden-input" hidden>
                            <label for="medicine_code">Medicine Code</label>
                            <input type="text" id="medicine_code" class="form-control" disabled>
                        </div>
                        <div class="form-group hidden-input" hidden>
                            <label for="unit">Unit</label>
                            <input type="text" id="unit" class="form-control" disabled>
                        </div>
                        <div class="form-group hidden-input" hidden>
                            <label for="current_stock">Current Stock</label>
                            <input type="text" id="current_stock" class="form-control" disabled>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="text" class="form-control" name="quantity" id="quantity"
                                placeholder="enter medicine quantity" autocomplete="off" autofocus value="{{$batch->quantity}}">
                        </div>
                        <div class="form-group">
                            <label for="expired_date">Expired Date</label>
                            <input type="date" class="form-control" name="expired_date" id="expired_date"
                                placeholder="enter medicine expired date" autocomplete="off" value="{{$batch->expired_date}}">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1" {{$batch->is_active == true ? 'selected' : ''}}>Active</option>
                                <option value="0" {{$batch->is_active == false ? 'selected' : ''}}>Non-Active</option>
                            </select>
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

@push('script')
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\BatchRequest', '#form') !!}

    <script>
        $(document).ready(function() {
            @if (session('status'))
                Swal.fire(
                    "{{ session('title') }}",
                    "{{ session('message') }}",
                    "{{ session('status') }}",
                );
            @endif

            $('#medicine_id').select2({
                placeholder: "Choose Medicine...",
                allowClear: true,
            });

            setTimeout(() => {
                $('#medicine_id').trigger('change');
            }, 100);

            $('#medicine_id').change(function() {
                let value = $(this).val();
                $('.hidden-input').prop('hidden', (value == '' ? true : false));
                $.get("/batch/medicine-detail/" + value, function(data) {
                    $('#category').val(data.category.name)
                    $('#medicine_code').val(data.medicine_code)
                    $('#unit').val(data.unit)
                    $('#current_stock').val(data.unit)
                });
            });
        });
    </script>
@endpush
