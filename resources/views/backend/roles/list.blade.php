@extends('backend.layouts.app')
@section('content')
<div class="content-wrapper mt-5">
    <div class="container-xxl flex-grow-1 container-p-y mt-5">
        <!-- <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;add /</span> Blog</h4> -->
        <div class="row">
            <div class="col-md-11"></div>
            <div class="col-md-1">
                <a href="{{route('role.add')}}" class="btn btn-primary mb-2">Create</a>
            </div>
        </div>
        <div class="row">
            <!-- Browser Default -->
            <div class="col-md-2 mb-4 mb-md-0"></div>
            <div class="col-md-10 mb-4 mb-md-0">
                <div class="card">
                    <h5 class="card-header">Add Role</h5>
                    <div class="card-body">
                        <table id="myTable" class="table display no-wrap table-hover role_datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extrascript')
<script type="text/javascript">
    $(function() {
        var table = $('.role_datatable').DataTable({
            processing: false,
            serverSide: true,
            scrollX: true,
            fixedHeader: {
                header: true,
                footer: true
            },
            stateSave: true,
            ajax: "",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            bLengthChange: false
        });
    });
</script>
@endsection