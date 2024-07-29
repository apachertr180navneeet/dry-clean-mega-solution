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
                            <h4>Service</h4>
                            <a class="btn btn_1F446E_hp" data-bs-toggle="modal" data-bs-target="#Service">Add Service</a>
                        </div>
                        <div class="client_list_heading_area justify-content-end">

                            <div class="client_list_heading_search_area">
                                <i class="menu-icon tf-icons ti ti-search"></i>
                                <input type="search" id="serviceSearch" class="form-control" placeholder="Searching ...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table_head_1f446E">
                                    <tr>
                                        <th>S. No.</th>
                                        <th>Service Name</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @php
                                        $serialNumber = 1; // Initialize serial number counter
                                    @endphp
                                    @foreach ($services as $service)
                                        <tr>
                                            <td>{{ $serialNumber++ }}</td>
                                            <td class="px-6 py-4">{{ $service->name }}</td>
                                            <td>
                                                <div class="Client_table_action_area">
                                                    <button class="btn Client_table_action_icon px-2 edit_service_btn"
                                                        data-id="{{ $service->id }}" data-name="{{ $service->name }}"
                                                        data-bs-toggle="modal" data-bs-target="#edit_service"><i
                                                            class="tf-icons ti ti-pencil"></i></button>
                                                    <button class="btn Client_table_action_icon px-2 delete_service_btn"
                                                        data-id="{{ $service->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#delete_service"><i
                                                            class="tf-icons ti ti-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <div class="no-records-found">No records found related to your search.</div>
                            @if ($services->count() > 0)
                            <div class="pagination-container">
                                {{ $services->links() }}
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Service" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('add.service') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <label for="exampleInputEmail1" class="form-label">Service Name</label>
                        <input type="text" name="name" id="service_name" class="form-control" autofocus="autofocus"
                            placeholder="Service Name">
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
                        <button type="submit" class="btn btn_1F446E_hp" id="save_service">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!--Edit Service Modal--->
    <div class="modal fade" id="edit_service" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="editserviceform" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <input type="hidden" class="service_id" name="id" value="" />
                    <div class="modal-body">
                        <label for="exampleInputEmail1" class="form-label">Service Name</label>
                        <input type="text" name="name" id="edit_service_name" class="form-control service_name"
                            placeholder="Service Name" value="">
                        <span id="edit_name_error" class="alert text-danger"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn_1F446E_hp" id="edit_save_service">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="delete_service" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this service?
                </div>
                <form method="post" id="deleteServiceForm">
                    @csrf <!-- Include CSRF token -->
                    @method('GET')
                    <input type="hidden" id="service_del_id" name="service_id">
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
            // $('#service_name').focus();
            // Focus on the input field when the modal is shown
            $('#Service').on('shown.bs.modal', function () {
                $('#service_name').focus();
            });
            $('#serviceSearch').keyup(function() {
                var searchText = $(this).val().toLowerCase();
                var noRecord = true;  
                $('tbody tr').each(function() {
                    var serviceName = $(this).find('td:nth-child(2)').text()
                        .toLowerCase();
                    if (serviceName.indexOf(searchText) === -1) {
                        $(this).hide();
                    } else {
                        $(this).show();
                        noRecord = false;
                    }
                });
                if (noRecord) {
                    $('.no-records-found').show();
                    $('.pagination-container').hide(); // Hide pagination
                } else {
                    $('.no-records-found').hide();
                    $('.pagination-container').show(); // Show pagination
                }
            });

            $('.edit_service_btn').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('.service_id').val(id);
                $('.service_name').val(name);
            });

            $('#editserviceform').submit(function(e) {
                e.preventDefault();
                var id = $('.service_id').val();
                var formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: '/admin/edit-services/' + id,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);
                        $('#edit_service').modal('hide');
                        window.location.reload();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            // for delete service
            $('.delete_service_btn').click(function() {
                var serviceId = $(this).data('id');
                $('#service_del_id').val(
                    serviceId);  
                $('#delete_service').modal('show');  
            });

            $('#confirm_delete').click(function() {
                var formData = $('#deleteServiceForm').serialize();  
                var serviceId = $('#service_del_id').val(); 

                $.ajax({
                    url: '/admin/delete-services/' + serviceId,
                    type: 'GET',
                    data: formData,
                    success: function(response) { 
                        $('#delete_service').modal('hide');
                        window.location.reload();
                    },
                    error: function(xhr) { 
                        $('#delete_service').modal('hide');
                    }
                });
            });

            // ****Add validation function ****
            function validateServiceName() {
                var name = $('#service_name').val();
                var errorElement = $('#name_error');
                errorElement.empty();

                if (!name.trim()) {
                    errorElement.text('Service name is required.');
                    return;
                }

                if (name.length > 50) {
                    errorElement.text('Service name must not greater than 50 characters.');
                    return;
                }
                if (/[^a-zA-Z\s]/.test(name)) {
                    errorElement.text('Service name can only contain letters and spaces.');
                    return;
                }
                errorElement.empty();
            }
            $('#service_name').on('input', function() {
                var name = $('#service_name').val().trim();
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

            $('#service_name').on('input', function() {
                validateServiceName();
            });

            $('#save_service').click(function(event) {
                validateServiceName();
                if ($('#name_error').text()) {
                    event.preventDefault();
                }
            });


            // ****Edit validation function ****
            function validateEditServiceName() {
                var name = $('#edit_service_name').val();
                var errorElement = $('#edit_name_error');
                errorElement.empty();

                if (!name.trim()) {
                    errorElement.text('Service name is required.');
                    return;
                }

                if (name.length > 50) {
                    errorElement.text('Service name must not greater than 50 characters.');
                    return;
                }
                if (/[^a-zA-Z\s]/.test(name)) {
                    errorElement.text('Service name can only contain letters and spaces.');
                    return;
                }
                errorElement.empty();
            } 
            $('#edit_service_name').on('input', function() {
                var name = $('#edit_service_name').val().trim();
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

            $('#edit_service_name').on('input', function() {
                validateEditServiceName();
            });

            $('#edit_save_service').click(function(event) {
                validateEditServiceName();
                if ($('#edit_name_error').text()) {
                    event.preventDefault();
                }
            });


        });
    });
</script>
@endsection
 

