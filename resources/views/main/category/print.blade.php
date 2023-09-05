@extends('template.master')

@section('page-title', 'Category')
@section('page-sub-title', 'Data')

@section('content')
    <div class="row printableArea">
        <div class="col-md-12" style="text-align: center">
            <h2><strong>MediStock: An Advanced Information System for Medicine Inventory</strong></h2>
            <h3>
                <b>Category Report</b>
            </h3>
            <div class="pull-left py-5">
                <address>
                    <p class="m-t-30">
                        <img src="{{asset('assets/images/logo.png')}}" height="100">
                    </p>
                    <p class="m-t-30">
                        <b>Print by :</b>
                        <i class="fa fa-user"></i> {{ auth()->user()->name }}
                    </p>
                    <p class="m-t-30">
                        <b>Report date :</b>
                        <i class="fa fa-calendar"></i> {{ date('d-m-Y') }}
                    </p>
                </address>
            </div>
        </div>
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" role="grid"
                        id="tableData">
                        <tr>
                            <th>No</th>
                            <th>Category Name</th>
                            <th>Status</th>
                        </tr>
                        @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $category->is_active == true ? 'badge-primary' : 'badge-danger' }}">{{ $category->is_active == true ? 'Active' : 'Inactive' }}</span>
                                    </td>
                                </tr>
                            @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
