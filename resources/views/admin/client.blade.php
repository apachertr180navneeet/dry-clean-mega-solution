@extends('backend.layouts.app')
@section('content')
<style>
    .pagination-container {
        display: flex;
        justify-content: end;
        margin-top: 20px;
    }
    .pagination-container svg {
        width: 30px;
    }
    .pagination-container nav .justify-between {
        display: none;
    }
    .no-records-found {
        text-align: center;
        color: red;
        margin-top: 20px;
        font-size: 18px;
        display: none;
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
                    <form action="{{ route('add.client') }}" method="post" enctype="multipart/form-data" id="add_client_form">
                        @csrf
                        <div class="row align-items-center justify-content-center">
                            <div class="col-xl-4 col-lg-5 col-md-6 col-12">
                                <div class="mb-2">
                                    <label for="add_client_name" class="form-label">Client Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Daniel G. Depp" id="add_client_name">
                                    <span class="alert text-danger" id="add_username_error">
                                        @error('name')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-5 col-md-6 col-12">
                                <div class="mb-2">
                                    <label for="add_client_mobile" class="form-label">Client Number</label>
                                    <input type="number" name="mobile" class="form-control" placeholder="408-467-6211" id="add_client_mobile" maxlength="10">
                                    <span class="alert text-danger" id="add_mobile_error">
                                        @error('mobile')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-4">
                                    <button type="submit" class="btn btn_1F446E_hp w-100" id="add_save_client">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Client Modal -->
        <div class="modal fade" id="edit_client" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editClientModalLabel">Edit Client</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" id="edit_client_form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="edit_client_id">
                        <div class="modal-body">
                            <label for="edit_client_name" class="form-label">Client Name</label>
                            <input type="text" name="name" id="edit_client_name" class="form-control">
                            <span id="edit_name_error" class="alert text-danger"></span>
                        </div>
                        <div class="modal-body">
                            <label for="edit_client_mobile" class="form-label">Client Number</label>
                            <input type="number" name="mobile" id="edit_client_mobile" class="form-control">
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

        <!-- Delete Client Modal -->
        <div class="modal fade" id="delete_client" tabindex="-1" aria-labelledby="deleteClientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteClientModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">Are you sure you want to delete this client?</div>
                    <form id="delete_client_form">
                        @csrf
                        <input type="hidden" id="client_del_id" name="client_id">
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
                            <input type="search" id="client_search" class="form-control" placeholder="Searching ...">
                        </div>
                    </div>
                    <div class="">
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
                                @php $serialNumber = 1; @endphp
                                @foreach ($clients as $client)
                                    <tr>
                                        <td>{{ $serialNumber++ }}</td>
                                        <td>{{ $client->name }}</td>
                                        <td>{{ $client->mobile }}</td>
                                        <td>
                                            <div class="client_table_action_area">
                                                <button class="btn client_table_action_icon px-2 edit_client_btn" data-id="{{ $client->id }}" data-name="{{ $client->name }}" data-mobile="{{ $client->mobile }}" data-bs-toggle="modal" data-bs-target="#edit_client"><i class="tf-icons ti ti-pencil"></i></button>
                                                <button class="btn client_table_action_icon px-2 delete_client_btn" data-id="{{ $client->id }}" data-bs-toggle="modal" data-bs-target="#delete_client"><i class="tf-icons ti ti-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="no-records-found">No records found related to your search.</div>
                        <div class="pagination-container">{{ $clients->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    $(document).ready(function() {
        // Add Client Form Validation
        function validateAddForm() {
            let name = $('#add_client_name').val().trim();
            let mobile = $('#add_client_mobile').val().trim();
            let isValid = true;

            $('#add_username_error').empty();
            $('#add_mobile_error').empty();

            if (!name) {
                $('#add_username_error').text('Client name is required.');
                isValid = false;
            } else if (name.length < 3 || name.length > 20) {
                $('#add_username_error').text('Client name must have between 3 to 20 characters.');
                isValid = false;
            }

            if (!mobile) {
                $('#add_mobile_error').text('Client number is required.');
                isValid = false;
            } else if (!/^\d{10}$/.test(mobile)) {
                $('#add_mobile_error').text('Please enter a valid 10-digit mobile number.');
                isValid = false;
            }

            return isValid;
        }

        // Event listener for form submission
        $('#add_save_client').on('click', function(event) {
            if (!validateAddForm()) {
                event.preventDefault();
            }
        });

        // Real-time validation for add client form
        $('#add_client_name').on('input', function() {
            let nameMaxLength = 20;
            let value = $(this).val();

            if (value.length > nameMaxLength) {
                $(this).val(value.substr(0, nameMaxLength));
                $('#add_username_error').text('Client name must not exceed 20 characters.');
            } else if (/[^a-zA-Z0-9 ]/.test(value)) {
                $(this).val(value.replace(/[^a-zA-Z0-9 ]/g, ''));
                $('#add_username_error').text('Client name can only contain letters, numbers, and spaces.');
            } else {
                $('#add_username_error').empty();
            }
        });

        $('#add_client_mobile').on('input', function() {
            let mobileMaxLength = 10;
            let value = $(this).val();

            if (value.length > mobileMaxLength) {
                $(this).val(value.substr(0, mobileMaxLength));
                $('#add_mobile_error').text('Client number must not exceed 10 digits.');
            } else {
                $('#add_mobile_error').empty();
            }
        });

        // Edit Client Form Modal
        $('.edit_client_btn').on('click', function() {
            let clientId = $(this).data('id');
            let clientName = $(this).data('name');
            let clientMobile = $(this).data('mobile');

            $('#edit_client_id').val(clientId);
            $('#edit_client_name').val(clientName);
            $('#edit_client_mobile').val(clientMobile);
        });

        // Delete Client Modal
        $('.delete_client_btn').on('click', function() {
            let clientId = $(this).data('id');
            $('#client_del_id').val(clientId);
        });

        // Confirm Delete Client
        $('#confirm_delete').on('click', function() {
            $('#delete_client_form').submit();
        });

        // Client Search Filter
        $('#client_search').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            let noRecordsFound = true;

            $('tbody tr').filter(function() {
                let isMatch = $(this).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(isMatch);
                noRecordsFound = noRecordsFound && !isMatch;
            });

            if (noRecordsFound) {
                $('.no-records-found').show();
            } else {
                $('.no-records-found').hide();
            }
        });
    });
});
</script>
@endsection
