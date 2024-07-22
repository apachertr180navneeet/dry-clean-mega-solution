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
            <div class="add_client_form_area_hp mb-4">
                <div class="card">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <h4>Add Client</h4>
                        <form action="{{ route('add.client') }}" method="post" enctype="multipart/form-data"
                            id="add_client_form">
                            @csrf
                            <div class="row align-items-center justify-content-center">
                                <div class="col-xl-4 col-lg-5 col-md-6 col-12">
                                    <div class="mb-2">
                                        <label for="exampleFormControlInput1" class="form-label">Client Name</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Daniel G. Depp" id="add_client_name">
                                        {{-- <label id="name-error" class="error" for="name" style=""></label> --}}
                                    </div>
                                    <span class="alert text-danger" id="add_username_error">
                                        @error('name')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="col-xl-4 col-lg-5 col-md-6 col-12">
                                    <div class="mb-2">
                                        <label for="exampleFormControlInput1" class="form-label">Client Number</label>
                                        <input type="number" name="mobile" class="form-control" id="add_client_mobile"
                                            placeholder="408-467-6211" maxlength="10">
                                        {{-- <label id="mobile-error" class="error" for="mobile" style=""></label> --}}
                                    </div>
                                    <span class="alert text-danger" id="add_mobile_error">
                                        @error('mobile')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="mb-4">
                                        <label for="exampleFormControlInput1" class="form-label"></label>
                                        <button type="submit" class="btn btn_1F446E_hp w-100"
                                            id="add_save_client">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--Edit client Modal--->
            <div class="modal fade" id="edit_client" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Client</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" id="editclientform" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <input type="hidden" class="client_id" name="id" value="" />
                            <div class="modal-body">
                                <label for="exampleInputEmail1" class="form-label">Client Name</label>
                                <input type="text" name="name" id="edit_client_name" class="form-control client_name"
                                    placeholder="Client Name" value="">
                                <span id="edit_name_error" class="alert text-danger"></span>
                            </div>
                            <div class="modal-body">
                                <label for="exampleInputEmail1" class="form-label">Client Number</label>
                                <input type="number" name="mobile" id="edit_client_mobile"
                                    class="form-control client_mobile" placeholder="Client Number" value="">
                                <span id="edit_mobile_error" class="alert text-danger"></span>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn_1F446E_hp" id="edit_save_client">Save</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="delete_client" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this client?
                        </div>
                        <form id="deleteClientForm">
                            @csrf
                            @method('GET')
                            <input type="hidden" id="client_del_id" name="client_id" value=" ">
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirm_delete">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="client_list_area_hp">
                <div class="card">
                    <div class="card-body">
                        <div class="client_list_heading_area">
                            <h4>Client List</h4>
                            <div class="client_list_heading_search_area">
                                <i class="menu-icon tf-icons ti ti-search"></i>
                                <input type="search" id="clientSearch" class="form-control"
                                    placeholder="Searching ...">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead class="table_head_1f446E">
                                            <tr>
                                                <th>S. No.</th>
                                                <th>Client Name</th>
                                                <th>Client Number</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $serialNumber = 1;
                                            @endphp
                                            @foreach ($clients as $client)
                                                <tr>
                                                    <td>{{ $serialNumber++ }}</td>
                                                    <td>{{ $client->name }}</td>
                                                    <td>{{ $client->mobile }}</td>
                                                    <td>
                                                        <div class="Client_table_action_area">
                                                            <button
                                                                class="btn Client_table_action_icon px-2 edit_client_btn"
                                                                data-id="{{ $client->id }}"
                                                                data-name="{{ $client->name }}"
                                                                data-mobile="{{ $client->mobile }}" data-bs-toggle="modal"
                                                                data-bs-target="#edit_client"><i
                                                                    class="tf-icons ti ti-pencil"></i></button>

                                                            <button id="client_del_id"
                                                                class="btn Client_table_action_icon px-2 delete_client_btn"
                                                                data-id="{{ $client->id }}" data-bs-toggle="modal"
                                                                data-bs-target="#delete_client"><i
                                                                    class="tf-icons ti ti-trash"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            {{-- <tr>
                                            <td>0.2</td>
                                            <td>Deepak</td>
                                            <td>895-564-XXXX</td>
                                            <td>
                                                <div class="Client_table_action_area">
                                                    <div class="Client_table_action_area">
                                                        <button class="btn Client_table_action_icon px-2"><i class="tf-icons ti ti-pencil"></i></button>
                                                        <button class="btn Client_table_action_icon px-2"><i class="tf-icons ti ti-trash"></i></button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr> --}}
                                        </tbody>
                                    </table>
                                <div class="no-records-found">No records found related to your search.</div>
                                <div class="pagination-container">
                                    {{ $clients->links() }}
                                </div>
                                </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><script>
    document.addEventListener("DOMContentLoaded", function() {

        $(document).ready(function() {
            // alert("++++++++++");
            //Add New Validation
            // $("#add_client_form").validate({
            //     rules: {
            //         name: {
            //             required: true,
            //             minlength: 2,
            //             maxlength: 50,
            //         },
            //         mobile: {
            //             required: true,
            //             // email: true,
            //             minlength: 10,
            //             maxlength: 10,
            //         }
            //     },
            //     messages: {
            //         name: {
            //             required: "Please enter client name",
            //             minlength: "Please enter client name minimun 2 character",
            //             maxlength: "Please enter client name maximum 50 character"
            //         },
            //         mobile: {
            //             required: "Please enter mobile number",
            //             // email: "Please enter a valid email address"
            //             minlength: "Plese enter mobile number minimun 10 digit",
            //             maxlength: "Please enter mobile number maximum 10 digit",
            //         },
            //     },
            //     submitHandler: function(form) {
            //         $(form).submit();
            //     }
            // });

            //add client validation start
            function validateaddForm() {
                var name = $('#add_client_name').val();
                var mobile = $('#add_client_mobile').val();
                var nameErrorElement = $('#add_username_error');
                var mobileErrorElement = $('#add_mobile_error');
                nameErrorElement.empty();
                mobileErrorElement.empty();
                var isValid = true;

                if (!name) {
                    nameErrorElement.text('Client name is required.');
                    isValid = false;
                } else if (name.length < 3 || name.length > 20) {
                    nameErrorElement.text('Client name must have between 3 to 20 characters.');
                    isValid = false;
                }

                if (!mobile) {
                    mobileErrorElement.text('Client number is required.');
                    isValid = false;
                } else if (!/^\d{10}$/.test(mobile)) {
                    mobileErrorElement.text('Please enter a valid 10-digit mobile number.');
                    isValid = false;
                }
                return isValid;
        }

            // // Event listener for form submission
            $('#add_save_client').on('click', function(event) {
                //alert('____________');
                if (!validateaddForm()) {
                    event.preventDefault();
                }
                validateaddForm();

            });

            $('#add_client_name').on('input', function() {
                var nameMaxLength = 20;
                if ($(this).val().length > nameMaxLength) {
                    $(this).val($(this).val().substr(0, nameMaxLength));
                    $('#add_username_error').text('Client name must not exceed 20 characters.');
                } else if ($(this).val().match(/[^a-zA-Z0-9 ]/)) {
                    $(this).val($(this).val().replace(/[^a-zA-Z0-9 ]/g, ''));
                    $('#add_username_error').text('Special characters are not allowed in client name.');
                } else {
                    $('#add_username_error').empty();
                }
                validateaddForm();
                });

            $('#add_client_mobile').on('input', function() {
                var mobileMaxLength = 10;
                if ($(this).val().length > mobileMaxLength) {
                    $(this).val($(this).val().substr(0, mobileMaxLength));
                    $('#add_mobile_error').text('Mobile number must not exceed 10 characters.');
                } else if ($(this).val().match(/^[-]/)) {
                    $(this).val('');
                    $('#add_mobile_error').text('Mobile number cannot be negative.');
                } else {
                    $('#add_mobile_error').empty();
                }
                validateaddForm();
                });


            // Edit validation start (when edit)
            function validateEditClient() {
                var name = $('#edit_client_name').val().trim();
                var mobile = $('#edit_client_mobile').val().trim();
                var nameErrorElement = $('#edit_name_error');
                var mobileErrorElement = $('#edit_mobile_error');
                nameErrorElement.empty();
                mobileErrorElement.empty();
                var isValid = true;

                if (!name) {
                    nameErrorElement.text('Client name is required.');
                    isValid = false;
                } else if (name.length < 3 || name.length > 20) {
                    nameErrorElement.text('Client name must have between 3 to 20 characters.');
                    isValid = false;
                }

                if (!mobile) {
                    mobileErrorElement.text('Client number is required.');
                    isValid = false;
                } else if (!/^\d{10}$/.test(mobile)) {
                    mobileErrorElement.text('Please enter a valid 10-digit mobile number.');
                    isValid = false;
                }
                return isValid;
            }

            $('#edit_client_name').on('input', function() {
                var nameMaxLength = 20;
                if ($(this).val().length > nameMaxLength) {
                    $(this).val($(this).val().substr(0, nameMaxLength));
                    $('#edit_name_error').text('Client name must not exceed 20 characters.');
                } else {
                    $('#edit_name_error').empty();
                }
                validateEditClient();
            });

            $('#edit_client_mobile').on('input', function() {
                var mobileMaxLength = 10;
                if ($(this).val().length > mobileMaxLength) {
                    $(this).val($(this).val().substr(0, mobileMaxLength));
                    $('#edit_mobile_error').text(
                    'Mobile number must not exceed 10 characters.');
                } else {
                    $('#edit_mobile_error').empty();
                }
                validateEditClient();
            });

            $('#editclientform').on('submit', function(event) {
                if (!validateEditClient()) {
                    event.preventDefault();
                } else {
                    event.preventDefault(); // Prevent default form submission

                    var id = $('.client_id').val();
                    var formData = new FormData(this);

                    $.ajax({
                        type: 'POST',
                        url: '/admin/edit-client/' + id,
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            console.log(response);
                            $('#edit_client').modal('hide');
                            window.location.reload();
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
            });
            // Edit validation end



            $('#clientSearch').keyup(function() {
            var searchText = $(this).val().toLowerCase();
            var hasResults = false;

            $('tbody tr').each(function() {
                var clientName = $(this).find('td:nth-child(2)').text().toLowerCase();
                var clientMobile = $(this).find('td:nth-child(3)').text().toLowerCase();
                if (clientName.indexOf(searchText) === -1 && clientMobile.indexOf(searchText) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                    hasResults = true;
                }
            });

            if (!hasResults) {
                $('.no-records-found').show();
            } else {
                $('.no-records-found').hide();
            }
        });


            $('.edit_client_btn').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var mobile = $(this).data('mobile');
                $('.client_id').val(id);
                $('.client_name').val(name);
                $('.client_mobile').val(mobile);
            });

            // $('#editclientform').submit(function(e) {
            //     validateEditClient();
            //     e.preventDefault();
            //     // alert("hellobub");
            //     var id = $('.client_id').val();
            //     var formData = new FormData(this);

            //     $.ajax({
            //         type: 'POST',
            //         url: '/admin/edit-client/' + id,
            //         data: formData,
            //         contentType: false,
            //         processData: false,
            //         success: function(response) {
            //             console.log(response);
            //             $('#edit_client').modal('hide');
            //             window.location.reload();
            //         },
            //         error: function(error) {
            //             console.log(error);
            //         }
            //     });
            // });

            // for delete client
            $('.delete_client_btn').click(function() {
                var id = $(this).data('id');
                $('#client_del_id').val(id);
                $('#delete_client').modal('show');
            });

            $('#confirm_delete').click(function(e) {
                e.preventDefault();
                var id = $('#client_del_id').val();
                $.ajax({
                    type: 'GET',
                    url: '/admin/delete-client/' + id,
                    data: $('#deleteClientForm').serialize(),
                    success: function(response) {
                        console.log(response);
                        $('#delete_client').modal('hide');
                        window.location.reload();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

        });
    });
</script>
@endsection
