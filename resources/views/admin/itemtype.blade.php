@extends('backend.layouts.app')
@section('content')
<style>
    .pagination-container{
        display: flex;
        justify-content: end;
        margin-top: 20px;
    }
    .pagination-container svg{
        width: 30px;
    }

    .pagination-container nav .justify-between{
        display: none;
    }
    .no-records-found {
        text-align: center;
        color: red;
        margin-top: 20px;
        font-size: 18px;
        display: none; /* Hidden by default */
    }

</style>
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="client_list_area_hp">
                <div class="card">
                    <div class="card-body">
                        <div class="client_list_heading_area">
                            <h4>Product Type</h4>
                            <a class="btn btn_1F446E_hp" data-bs-toggle="modal" data-bs-target="#ProductType">Product Type</a>
                        </div>
                        <div class="client_list_heading_area justify-content-end">

                            <div class="client_list_heading_search_area">
                                <i class="menu-icon tf-icons ti ti-search"></i>
                                <input type="search" id="ProductTypeSearch" class="form-control" placeholder="Searching ...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table_head_1f446E">
                                    <tr>
                                        <th>S. No.</th>
                                        <th>Product Type Name</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @php
                                        $serialNumber = 1; // Initialize serial number counter
                                    @endphp
                                    @foreach ($itemtype as $type)
                                        <tr>
                                            <td>{{ $serialNumber++ }}</td>
                                            <td class="px-6 py-4">{{ $type->name }}</td>
                                            <td>
                                                <div class="Client_table_action_area">
                                                    <button class="btn Client_table_action_icon px-2 edit_type_btn"
                                                        data-id="{{ $type->id }}" data-name="{{ $type->name }}"
                                                        data-bs-toggle="modal" data-bs-target="#edit_type"><i
                                                            class="tf-icons ti ti-pencil"></i></button>
                                                    <button class="btn Client_table_action_icon px-2 delete_type_btn"
                                                        data-id="{{ $type->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#delete_type"><i
                                                            class="tf-icons ti ti-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <div class="no-records-found">No records found related to your search.</div>
                            @if ($itemtype->count() > 0)
                            <div class="pagination-container">
                                {{ $itemtype->links() }}
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ProductType" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add ProductType</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('add.itemtype') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <label for="exampleInputEmail1" class="form-label">Product Type Name</label>
                        <input type="text" name="name" id="type_name" class="form-control" autofocus="autofocus"
                            placeholder="Product Type Name">
                        <span id="name_error" class="alert text-danger">
                            @error('name')
                                {{ $message }}
                            @enderror
                        </span>
                        {{-- <span class="alert text-danger">
                                @error('name')
                                    {{ $message }}
                                @enderror
                            </span> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn_1F446E_hp" id="save_type">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!--Edit ProductType Modal--->
    <div class="modal fade" id="edit_type" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit ProductType</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="edittypeform" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <input type="hidden" class="type_id" name="id" value="" />
                    <div class="modal-body">
                        <label for="exampleInputEmail1" class="form-label">ProductType Name</label>
                        <input type="text" name="name" id="edit_type_name" class="form-control type_name"
                            placeholder="ProductType Name" value="">
                        <span id="edit_name_error" class="alert text-danger"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn_1F446E_hp" id="edit_save_type">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="delete_type" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Product type?
                </div>
                <form method="post" id="deleteProductTypeForm">
                    @csrf <!-- Include CSRF token -->
                    @method('GET')
                    <input type="hidden" id="type_del_id" name="type_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirm_delete">Delete</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        $(document).ready(function() {
            // $('#type_name').focus();
            // Focus on the input field when the modal is shown
            $('#ProductType').on('shown.bs.modal', function () {
                $('#type_name').focus();
            });
            $('#typeSearch').on('keyup', function() {
                var searchText = $(this).val().toLowerCase();
                $.ajax({
                    url: '{{ route('itemtype') }}', // Adjust the route as necessary
                    type: 'GET',
                    data: {
                        search: searchText
                    },
                    success: function(response) {
                        var types = response.types;
                        var pagination = response.pagination;
                        var tbody = $('tbody');
                        tbody.empty();
                        var serialNumber = 1;

                        if (types.length === 0) {
                            $('.no-records-found').show();
                            $('.pagination-container').hide();
                        } else {
                            $('.no-records-found').hide();
                            $('.pagination-container').show().html(pagination);
                        }

                        $.each(types, function(index, type) {
                            var row = `
                                <tr>
                                    <td>${serialNumber++}</td>
                                    <td>${type.name}</td>
                                    <td>
                                        <div class="Client_table_action_area">
                                            <button class="btn Client_table_action_icon px-2 edit_type_btn"
                                                data-id="${type.id}" data-name="${type.name}"
                                                data-bs-toggle="modal" data-bs-target="#edit_type"><i
                                                    class="tf-icons ti ti-pencil"></i></button>
                                            <button class="btn Client_table_action_icon px-2 delete_type_btn"
                                                data-id="${type.id}" data-bs-toggle="modal"
                                                data-bs-target="#delete_type"><i
                                                    class="tf-icons ti ti-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            tbody.append(row);
                        });

                        // Reattach event handlers for edit and delete buttons
                        attachEventHandlers();
                    }
                });
            });

            function attachEventHandlers() {
                $('.edit_type_btn').click(function() {
                    var id = $(this).data('id');
                    var name = $(this).data('name');
                    $('.type_id').val(id);
                    $('.type_name').val(name);
                });

                $('.delete_type_btn').click(function() {
                    var typeId = $(this).data('id');
                    $('#type_del_id').val(typeId);
                    $('#delete_type').modal('show');
                });
            }
            attachEventHandlers();

            $('#edittypeform').submit(function(e) {
                e.preventDefault();
                var id = $('.type_id').val();
                var formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: '/admin/edit-itemtype/' + id,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);
                        $('#edit_type').modal('hide');
                        window.location.reload();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            $('#confirm_delete').click(function() {
                var formData = $('#deleteTypeForm').serialize();
                var typeId = $('#type_del_id').val();

                $.ajax({
                    url: '/admin/delete-itemtype/' + typeId,
                    type: 'GET',
                    data: formData,
                    success: function(response) {
                        $('#delete_type').modal('hide');
                        window.location.reload();
                    },
                    error: function(xhr) {
                        $('#delete_type').modal('hide');
                    }
                });
            });

            // ****Add validation function ****
            function validateProductTypeName() {
                var name = $('#type_name').val();
                var errorElement = $('#name_error');
                errorElement.empty();

                if (!name.trim()) {
                    errorElement.text('Product Type name is required.');
                    return;
                }

                if (name.length > 50) {
                    errorElement.text('Product Type name must not greater than 50 characters.');
                    return;
                }
                if (/[^a-zA-Z\s]/.test(name)) {
                    errorElement.text('Product Type name can only contain letters and spaces.');
                    return;
                }
                errorElement.empty();
            }
            $('#type_name').on('input', function() {
                var name = $('#type_name').val().trim();
                if (name.length >= 50) {
                    $(this).attr('maxlength', 50);
                } else {
                    $(this).removeAttr('maxlength');
                }
                if (name && (name.length > 50)) {
                    $('#name_error').empty();
                }
                // Prevent default for entering special characters and numbers
                if (/[^a-zA-Z\s]/.test(name)) {
                    $(this).val(name.replace(/[^a-zA-Z\s]/g, ''));
                    e.preventDefault();
                }
            });

            $('#type_name').on('input', function() {
                validateProductTypeName();
            });

            $('#save_type').click(function(event) {
                validateProductTypeName();
                if ($('#name_error').text()) {
                    event.preventDefault();
                }
            });


            // ****Edit validation function ****
            function validateEditProductTypeName() {
                var name = $('#edit_type_name').val();
                var errorElement = $('#edit_name_error');
                errorElement.empty();

                if (!name.trim()) {
                    errorElement.text('ProductType name is required.');
                    return;
                }

                if (name.length > 50) {
                    errorElement.text('ProductType name must not greater than 50 characters.');
                    return;
                }
                if (/[^a-zA-Z\s]/.test(name)) {
                    errorElement.text('ProductType name can only contain letters and spaces.');
                    return;
                }
                errorElement.empty();
            }
            $('#edit_type_name').on('input', function() {
                var name = $('#edit_type_name').val().trim();
                if (name.length >= 50) {
                    $(this).attr('maxlength', 50);
                } else {
                    $(this).removeAttr('maxlength');
                }
                if (name && (name.length > 50)) {
                    $('#edit_name_error').empty();
                }
                // Prevent default for entering special characters and numbers
                if (/[^a-zA-Z\s]/.test(name)) {
                    $(this).val(name.replace(/[^a-zA-Z\s]/g, ''));
                    e.preventDefault();
                }
            });

            $('#edit_type_name').on('input', function() {
                validateEditProductTypeName();
            });

            $('#edit_save_type').click(function(event) {
                validateEditProductTypeName();
                if ($('#edit_name_error').text()) {
                    event.preventDefault();
                }
            });


        });
    });
</script>
@endsection


