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
                            Add Category
                        </div>
                        {{-- @can('petugas') --}}
                            <div class="col-6 d-flex align-items-center">
                                <div class="m-auto"></div>
                                <a href="{{route('category.index')}}">
                                    <button type="button" class="btn btn-outline-primary">
                                        <i class="nav-icon fa fa-eye font-weight-bold"></i> View Data
                                    </button>
                                </a>
                            </div>
                        {{-- @endcan --}}
                    </div>
                </div>
                <form action="{{route('category.store')}}" method="POST" id="form">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Category Name</label>
                            <input class="form-control" name="name" id="name" placeholder="category name" value="{{old('name')}}" autocomplete="off" autofocus>
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
    });
</script>
@endpush
