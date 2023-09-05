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
                            Edit Medicine
                        </div>
                        {{-- @can('petugas') --}}
                            <div class="col-6 d-flex align-items-center">
                                <div class="m-auto"></div>
                                <a href="{{route('medicine.index')}}">
                                    <button type="button" class="btn btn-outline-primary">
                                        <i class="nav-icon fa fa-eye font-weight-bold"></i> View Data
                                    </button>
                                </a>
                            </div>
                        {{-- @endcan --}}
                    </div>
                </div>
                <form action="{{route('medicine.update')}}" method="POST" id="form">
                    @csrf
                    <div class="card-body">
                        <input type="hidden" name="id" id="id" value="{{$medicine->id}}">
                        <div class="form-group">
                            <label for="">Category Name</label>
                            <select name="category_id" id="category_id" class="form-control">
                                @foreach ($categories as $key => $value)
                                    <option value="{{$key}}" {{$key == $medicine->category_id ? 'selected' : ''}}>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Medicine Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="place enter medicine name" autocomplete="off" autofocus value="{{$medicine->name}}">
                        </div>
                        <div class="form-group">
                            <label for="medicine_code">Medicine Code</label>
                            <div class="row">
                                <div class="col-11">
                                    <input type="text" name="medicine_code" id="medicine_code" class="form-control" placeholder="place enter medicine code" autocomplete="off" autofocus value="{{$medicine->medicine_code}}">
                                </div>
                                <div class="col-1 d-flex">
                                    <button type="button" class="btn btn-primary btn-generate">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger text-white btn-cancel float-right ml-2" data-code="{{$medicine->medicine_code}}">Cancel</button>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="text" name="stock" id="stock" class="form-control" placeholder="place enter medicine stock" autocomplete="off" autofocus value="{{$medicine->stock}}">
                        </div> --}}
                        <div class="form-group">
                            <label for="unit">Unit</label>
                            <select name="unit" id="unit" class="form-control">
                                <option value="">Choose Unit...</option>
                                @foreach ($units as $unit)
                                    <option value="{{$unit}}" {{$medicine->unit == $unit ? 'selected' : ''}}>{{$unit}}</option>
                                @endforeach
                                <option value="Lainnya" {{ $medicine->unit == 'Lainnya' || ($medicine->unit != '' && !in_array($medicine->unit, $units)) ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group other_unit" {{in_array($medicine->unit, $units) ? 'hidden' : ''}}>
                            <input type="text" name="other_unit" id="other_unit" class="form-control mt-3" placeholder="place enter medicine unit" autocomplete="off" autofocus value="{{!in_array($medicine->unit, $units) ? $medicine->unit : ''}}">
                        </div>
                        {{-- <div class="form-group">
                            <label for="expired_date">Expired date</label>
                            <input type="date" name="expired_date" id="expired_date" class="form-control" placeholder="place enter medicine expired date" autocomplete="off" autofocus value="{{$medicine->expired_date}}">
                        </div> --}}
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" name="image" id="image" class="form-control" placeholder="place enter medicine image" autocomplete="off" autofocus>
                            <span class="text-small text-muted">*leave blank if you do not want to change the image</span>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1" {{$medicine->is_active == true ? 'selected' : ''}}>Active</option>
                                <option value="0" {{$medicine->is_active == false ? 'selected' : ''}}>Inactive</option>
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
<script src="{{asset('assets/helper/main.js')}}"></script>
<!-- Laravel Javascript Validation -->
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

{!! JsValidator::formRequest('App\Http\Requests\CategoryRequest', '#form') !!}

<script>
    $(document).ready(function () {
        @if(session('status'))
        Swal.fire(
            "{{session('title')}}",
            "{{session('message')}}",
            "{{session('status')}}",
        );
        @endif

        $('#unit').change(function() {
            let value = $(this).val();

            if(value == 'Lainnya') {
                $('.other_unit').prop('hidden', false)
            } else {
                $('.other_unit').prop('hidden', true);
            }
        });


        $('.btn-generate').click(function() {
            let medicineName = $('input[name="name"]').val();
            $('input[name="medicine_code"]').val(medicineName == '' ? generateMedicineCode() : generateMedicineCode(medicineName.slice(0, 3).toUpperCase()))
        });

        $('.btn-cancel').click(function() {
            let value = $(this).data('code');
            $('input[name="medicine_code"]').val(value)
        })
    });
</script>
@endpush
