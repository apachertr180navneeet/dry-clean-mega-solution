@extends('backend.layouts.app')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y mt-5">
    <div class="content-wrapper mt-5">
        <!-- <h4 class="fw-bold py-3"><span class="text-muted fw-light">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Blog /</span> List</h4> -->
        <div class="row">
            <div class="col-md-11"></div>
            <div class="col-md-1">
                <a href="{{route('permission.add')}}" class="btn btn-primary mb-2">Create</a>
            </div>
        </div>
        <!-- Basic Bootstrap Table -->
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-11">
                <div class="card container-fluid">
                    <h5 class="card-header">Rermission List</h5>
                    <div class="table-responsive text-nowrap">
                        <table id="myTable" class="table display no-wrap table-hover role_datatable" width="100%">
                            <thead>
                                <tr>
                                    <th> Name</th>
                                    <th> Assign To </th>
                                    <th> Date </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($list))
                                @foreach ($list as $row)
                                <tr>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->guard_name  }}</td>
                                    <td>{{ $row->created_at }}</td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Basic Bootstrap Table -->
    </div>
</div>
@endsection
