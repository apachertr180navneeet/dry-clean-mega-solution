@extends('backend.layouts.app')
<style>
    .badge
    {
        display: inline-block;
        padding: .25em .4em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25rem;
    }
    .badge-success {
        color: #fff;
        background-color: #28a745;
    }

    .badge-danger {
        color: #fff;
        background-color: #dc3545;
    }
</style>
@section('content')
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="client_list_area_hp">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-10">
                                <h5 class="card-header ">Users List</h5>
                            </div>
                            <div class="col-md-2 text-end mb-2">
                                <a href="{{ route('tenants.create') }}" class="btn btn_1F446E_hp">Create</a>
                            </div>
                        </div>
                        <div class="client_list_heading_area justify-content-end">
                            <div class="client_list_heading_search_area">
                                <i class="menu-icon tf-icons ti ti-search"></i>
                                <input type="search" id="tenantSearch" class="form-control" placeholder="Searching ...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table_head_1f446E">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Domain</th>
                                        <th>Date</th>
                                        <th>Is Active</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach ($tenants as $tenant)
                                        <tr>
                                            <td>{{ $tenant->name }}</td>
                                            <td>{{ $tenant->email }}</td>
                                            <td>
                                                @foreach ($tenant->domains as $domain)
                                                    {{ $domain->domain }}{{ $loop->last ? '' : ',' }}
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($tenant->subscriptions as $subscription)
                                                    {{ $subscription->starting_date }} - {{ $subscription->end_date }}
                                                @endforeach
                                            </td>
                                            <td>
                                                @if ($tenant->is_active == 1)
                                                    <span class="badge badge-success">Activate</span>
                                                @else
                                                    <span class="badge badge-danger">Deactivate</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="Client_table_action_area">
                                                    <a href="{{ route('tenants.edit', $tenant->id) }}"
                                                        class="btn Client_table_action_icon px-2"><i
                                                            class="tf-icons ti ti-pencil"></i></a>
                                                    <button class="btn Client_table_action_icon px-2 delete_tenent_btn"
                                                        data-id="{{ $tenant->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#delete_service"><i
                                                            class="tf-icons ti ti-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="delete_tenant" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this service?
                </div>
                <form method="post" id="deletetenantForm">
                    @csrf <!-- Include CSRF token -->
                    @method('POST')
                    <input type="hidden" id="tenant_del_id" name="tenent_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirm_delete">Delete</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection 

<script>
    document.addEventListener("DOMContentLoaded", function() {
    $(document).ready(function(){
        // alert("+++++");
        $('.delete_tenent_btn').click(function() {
            var tenantId = $(this).data('id');
            $('#tenant_del_id').val(tenantId);
            $('#delete_tenant').modal('show');
        });

        $('#confirm_delete').click(function() {
            var formData = $('#deletetenantForm').serialize(); // Serialize form data
            var tenantId = $('#tenant_del_id').val();
            // alert(tenantId);

            $.ajax({
                url: '/admin/delete-tenant/' + tenantId,
                type: 'post',
                data: formData,
                success: function(response) {
                    alert(response.message); // or update UI
                    $('#delete_tenant').modal('hide');
                    window.location.reload();
                },
                error: function(xhr) {
                    alert('Error deleting resource'); // handle error
                    $('#delete_tenant').modal('hide');
                }
            });
        });

        $('#tenantSearch').keyup(function() {
                var searchText = $(this).val().toLowerCase();
                $('tbody tr').each(function() {
                    var tenantName = $(this).find('td:nth-child(1)').text()
                        .toLowerCase();
                    var tenantEmail = $(this).find('td:nth-child(2)').text()
                        .toLowerCase();
                    if (tenantName.indexOf(searchText) === -1 && tenantEmail.indexOf(
                            searchText) === -1) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
        });
    });
});
    // for delete service

</script>
