@extends('backend.layouts.app')
@section('content')
<div class="content-wrapper mt-5">
    <div class="container-xxl flex-grow-1 container-p-y mt-5">
        <!-- <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;add /</span> Blog</h4> -->
        <div class="row">
            <!-- Browser Default -->
            <div class="col-md-2 mb-4 mb-md-0"></div>
            <div class="col-md-10 mb-4 mb-md-0">
                <div class="card">
                    <h5 class="card-header">Add Role</h5>
                    <div class="card-body">
                        <form class="needs-validation" method="post" action="{{ !empty($role->id) ?route('role.edit.post',['id' => $role->id]):route('role.post') }}" novalidate>
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fa-lg" for="validationCustom01">Role Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Role name" required value="{{ !empty($role->name) ? $role->name : '' }}">
                                    <span class="text-danger">
                                        @error('name')
                                        {{ $message }}
                                        @enderror
                                    </span>
                                    <div class="invalid-feedback">
                                        Please provide a Role Name.
                                    </div>
                                </div>
                            </div>
                            <h3>Permission</h3>
                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="checkAll" name="" value="">
                                <label class="form-check-label" for="checkAll">All Checked</label>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="skin skin-flat">
                                        <div class="form-group">
                                            @if (!empty($curruntopeningdata[0]))
                                            <h4>Currunt Openings</h4>
                                            <div class="input-group">
                                                <ul class="list-unstyled mb-0">
                                                    @foreach ($curruntopeningdata as $value)
                                                    <li class="form-check">
                                                        <input type="checkbox" {{ !empty($allpermissions) && in_array($value->id, $allpermissions) ? 'checked=checked' : '' }} type="checkbox" class="form-check-input models_checkbox" id="customCheck{{ $value->id }}" name="permissions[]" value="{{ $value->id }}">
                                                        <label class="form-check-label" for="customCheck{{$value->id }}">{{ $value->name }}</label>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="skin skin-flat">
                                        <div class="form-group">
                                            @if (!empty($userdata[0]))
                                            <h4>User</h4>
                                            <div class="input-group">
                                                <ul class="list-unstyled mb-0">
                                                    @foreach ($userdata as $value)
                                                    <li class="form-check">
                                                        <input type="checkbox" {{ !empty($allpermissions) && in_array($value->id, $allpermissions) ? 'checked=checked' : '' }} type="checkbox" class="form-check-input models_checkbox" id="customCheck{{ $value->id }}" name="permissions[]" value="{{ $value->id }}">
                                                        <label class="form-check-label" for="customCheck{{$value->id }}">{{ $value->name }}</label>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="skin skin-flat">
                                        <div class="form-group">
                                            @if (!empty($roledata[0]))
                                            <h4>Role</h4>
                                            <div class="input-group">
                                                <ul class="list-unstyled mb-0">
                                                    @foreach ($roledata as $value)
                                                    <li class="form-check">
                                                        <input type="checkbox" {{ !empty($allpermissions) && in_array($value->id, $allpermissions) ? 'checked=checked' : '' }} type="checkbox" class="form-check-input models_checkbox" id="customCheck{{ $value->id }}" name="permissions[]" value="{{ $value->id }}">
                                                        <label class="form-check-label" for="customCheck{{$value->id }}">{{ $value->name }}</label>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary text-white mt-5" type="submit">Add Role</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extrascript')
<script>
    $('#checkAll').on('click', function() {
        if (this.checked) {
            $('.models_checkbox').each(function() {
                this.checked = true;
            });
        } else {
            $('.models_checkbox').each(function() {
                this.checked = false;
            });

        }

    });
</script>
@endsection
