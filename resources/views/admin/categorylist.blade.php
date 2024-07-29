@extends('backend.layouts.app')
@section('content')
<style>
    .no-records-found {
        text-align: center;
        color: red;
        margin-top: 20px;
        font-size: 18px;
        display: none;
        /* Hidden by default */
    }
</style>
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="client_list_area_hp">
                <div class="card">
                    <div class="card-body">
                        <div class="client_list_heading_area">
                            <h4>Category</h4>
                            <a class="btn btn_1F446E_hp" href="{{ route('category') }}">Add Category</a>
                        </div>
                        <div class="client_list_heading_area justify-content-end">

                            <div class="client_list_heading_search_area">
                                <i class="menu-icon tf-icons ti ti-search"></i>
                                <input type="search" id="search-input" class="form-control" placeholder="Searching ...">
                            </div>
                        </div>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button " type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseOne" aria-expanded="false"
                                        aria-controls="flush-collapseOne">
                                        Clothes
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <select class="form-select" name="item_name" id="dropdown" aria-label="Default select example">
                                                    <option selected>Select Item</option>
                                                    @foreach ($clothes_datas as $clothes_data)
                                                        <option value="{{ $clothes_data->id }}">{{ $clothes_data->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped">
                                                <thead class="table_head_1f446E">
                                                    <tr>
                                                        <th>S. No.</th>
                                                        <th>Item Type</th>
                                                        <th>Service</th>
                                                        <th>Price</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data-list">

                                                </tbody>
                                            </table>
                                            <div class="no-records-found">No records found related to your search.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                        aria-controls="flush-collapseTwo">
                                        Upholstrey
                                    </button>
                                </h2>
                                <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <select class="form-select" aria-label="Default select example" id="dropdown_upholstrey">
                                                    <option selected>Select Item</option>
                                                    @foreach ($upholstery_datas as $upholstery_data)
                                                        <option value="{{ $upholstery_data->id }}">{{ $upholstery_data->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped">
                                                <thead class="table_head_1f446E">
                                                    <tr>
                                                        <th>S. No.</th>
                                                        <th>Item Type</th>
                                                        <th>Service</th>
                                                        <th>Price</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data-listupholstrey">

                                                </tbody>
                                            </table>
                                            <div class="no-records-found">No records found related to your search.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-heading3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapse4" aria-expanded="false"
                                        aria-controls="flush-collapse4">
                                        Footwear & Bags
                                    </button>
                                </h2>
                                <div id="flush-collapse4" class="accordion-collapse collapse"
                                    aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <select class="form-select" aria-label="Default select example" id="dropdown_footbags">
                                                    <option selected>Select Item</option>
                                                    @foreach ($footwearandbags as $footwearandbag)
                                                        <option value="{{ $footwearandbag->id }}">{{ $footwearandbag->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped">
                                                <thead class="table_head_1f446E">
                                                    <tr>
                                                        <th>S. No.</th>
                                                        <th>Item Type</th>
                                                        <th>Service</th>
                                                        <th>Price</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data-listfootbags">

                                                </tbody>
                                            </table>
                                            <div class="no-records-found">No records found related to your search.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-heading3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapse3" aria-expanded="false"
                                        aria-controls="flush-collapse3">
                                        Others
                                    </button>
                                </h2>
                                <div id="flush-collapse3" class="accordion-collapse collapse"
                                    aria-labelledby="flush-heading3" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <select class="form-select" aria-label="Default select example" id="dropdown_other">
                                                    <option selected>Select Item</option>
                                                    @foreach ($others as $other)
                                                        <option value="{{ $other->id }}">{{ $other->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped">
                                                <thead class="table_head_1f446E">
                                                    <tr>
                                                        <th>S. No.</th>
                                                        <th>Item Type</th>
                                                        <th>Service</th>
                                                        <th>Price</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data-listother">

                                                </tbody>
                                            </table>
                                            <div class="no-records-found">No records found related to your search.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-heading5">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapse5" aria-expanded="false"
                                        aria-controls="flush-collapse5">
                                        Laundry
                                    </button>
                                </h2>
                                <div id="flush-collapse5" class="accordion-collapse collapse"
                                    aria-labelledby="flush-heading5" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <select class="form-select" aria-label="Default select example" id="dropdown_laundry">
                                                    <option selected>Select Item</option>
                                                    @foreach ($laundries as $laundry)
                                                        <option value="{{ $laundry->id }}">{{ $laundry->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped">
                                                <thead class="table_head_1f446E">
                                                    <tr>
                                                        <th>S. No.</th>
                                                        <th>Item Type</th>
                                                        <th>Service</th>
                                                        <th>Price</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data-listlaundry">

                                                </tbody>
                                            </table>
                                            <div class="no-records-found">No records found related to your search.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Edit Item Modal--->
    <div class="modal fade" id="edit_items" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Categories Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="edititemForm">
                    @csrf
                    @method('POST')
                    <input type="hidden" class="item_id" id="item_id" name="item_id" value="" />
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Item type</label>
                            <input type="text" name="name" id="edit_item_name" class="form-control item_name"
                            placeholder="Item type" value="">
                            <span class="name-error text-danger"></span>
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Services</label>
                            <select name="service" id="serviceDropdown" class="form-select">
                                <option value="" selected disabled> Select Service</option>
                                @foreach ($services as $Service)
                                    <option value="{{ $Service->id }}" >{{ $Service->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="service-error text-danger"></span>
                        </div>
                        <div>
                            <label for="exampleInputEmail1" class="form-label">Price</label>
                            <input type="text" name="price" id="edit_price" class="form-control item_price" placeholder="Service price" value="">
                            {{-- <span id="edit_name_error" class="alert text-danger"></span> --}}
                            <span class="price-error text-danger"></span>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="edit_item_save" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn_1F446E_hp" id="edit_save_service">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="delete_item" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this service?
                </div>
                <form method="post" id="deleteitemForm">
                    @csrf
                    @method('POST')
                    <input type="hidden" id="clothdel_id" name="cloth_id">
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
    // Assuming you're using jQuery for AJAX
    document.addEventListener("DOMContentLoaded", function() {
    $(document).ready(function() {
        applyeditValidation();
        function fetchAllClothesData() {
            $.ajax({
                url: '/admin/fetch-data-clothes',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    displayData(response);
                    if (noRecord) {
                        $('.no-records-found').show();
                    } else {
                        $('.no-records-found').hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // Function to fetch and display filtered data
        function fetchFilteredData(selectedOption) {
            $.ajax({
                url: '/admin/fetch-data-clothes',
                method: 'GET',
                dataType: 'json',
                data: {
                    option: selectedOption
                },
                success: function(response) {
                    displayData(response);
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(error);
                }
            });
        }

        // Function to display data in the UI
        function displayData(data) {
            $('#data-list').empty();
            var serialNumber = 1;
            data.forEach(function(item) {
                var temp = `
                    <tr>
                        <td>` + serialNumber + `</td>
                        <td>`+item.product_name+`</td>
                        <td>`+item.service_name+`</td>
                        <td>`+item.price+`</td>
                        <td>
                            <div class="Client_table_action_area">
                                <button class="btn Client_table_action_icon px-2 edit_item_btn" data-id="`+item.product_cat_id+`" data-op_id="` + item.operation_id + `" data-name="`+item.product_name+`" data-price="`+item.price+`" data-bs-toggle="modal" data-bs-target="#edit_items"><i class="tf-icons ti ti-pencil"></i></button>
                                <button class="btn Client_table_action_icon px-2 delete_btn" data-id="`+item.product_cat_id+`" data-bs-toggle="modal" data-bs-target="#delete_item"><i class="tf-icons ti ti-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
                serialNumber++;
                $('#data-list').append(temp);

            });
        }

        fetchAllClothesData();

        // Set up event listener on the dropdown menu
        $('#dropdown').change(function() {
            var selectedOption = $(this).val();
            fetchFilteredData(selectedOption);
        });

        // Delete functionality
        $(document).on('click', '.delete_btn', function() {
            var tenantId = $(this).data('id');
            $('#clothdel_id').val(tenantId);
            $('#delete_item').modal('show');
        });

        $('#confirm_delete').click(function() {
            var formData = $('#deleteitemForm').serialize();
            var tenantId = $('#clothdel_id').val();

            $.ajax({
                url: '/admin/delete-clothes/' + tenantId,
                type: 'post',
                data: formData,
                success: function(response) {
                    alert(response.message);
                    $('#delete_item').modal('hide');
                    window.location.reload();
                },
                error: function(xhr) {
                    alert('Error deleting resource');
                    $('#delete_item').modal('hide');
                }
            });
        });

        //Edit functinality
        $(document).on('click', '.edit_item_btn', function() {
            // alert("+++++++");
            var id = $(this).data('id');
            var name = $(this).data('name');
            var price = $(this).data('price');
            var serviceId = $(this).data('op_id');
            // alert(id + " " +name + " " +price);
            $('.item_id').val(id);
            $('.item_name').val(name);
            $('.item_price').val(price);
            $('#serviceDropdown').val(serviceId);
            $('#edit_items').modal('show');
        });

        $(document).on('submit', '#edititemform', function(e) {
            // alert("++++++");
            console.log("++++++");
            e.preventDefault();
            var formData = new FormData(this);
            var itemId = $('#item_id').val();
            $.ajax({
                type: 'POST',
                url: '/admin/categorylist',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert(response.message);
                    $('#edit_items').modal('hide');
                    window.location.reload();
                },
                error: function(xhr) {
                    alert('Error deleting resource');
                    $('#edit_items').modal('hide');
                }
            });
        });
    });

    // Fetch Upholstery Data
    $(document).ready(function(){
        function fetchAllUpholsteryData() {
            $.ajax({
                url: '/admin/fetch-data-upholstrey',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    displayData(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // Function to fetch and display filtered data
        function fetchFiltereUpholsterydData(selectedOption) {
            $.ajax({
                url: '/admin/fetch-data-upholstrey',
                method: 'GET',
                dataType: 'json',
                data: {
                    option: selectedOption
                },
                success: function(response) {
                    displayData(response);
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(error);
                }
            });
        }

        // Function to display data in the UI
        function displayData(data) {
            $('#data-listupholstrey').empty();
            var serialNumber = 1;
            data.forEach(function(items) {
                var temp = `
                    <tr>
                        <td>` + serialNumber + `</td>
                        <td>`+items.product_name+`</td>
                        <td>`+items.service_name+`</td>
                        <td>`+items.price+`</td>
                        <td>
                            <div class="Client_table_action_area">
                                <button class="btn Client_table_action_icon px-2 edit_item_btn" data-id="`+items.product_cat_id+ `" data-op_id="` + items.operation_id +`" data-name="`+items.product_name+`" data-price="`+items.price+`" data-bs-toggle="modal" data-bs-target="#edit_items"><i class="tf-icons ti ti-pencil"></i></button>
                                <button class="btn Client_table_action_icon px-2 delete_btn" data-id="`+items.product_cat_id+`" data-bs-toggle="modal" data-bs-target="#delete_item"><i class="tf-icons ti ti-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
                serialNumber++;
                $('#data-listupholstrey').append(temp);

            });
        }

        fetchAllUpholsteryData();

        // Set up event listener on the dropdown menu
        $('#dropdown_upholstrey').change(function() {
            var selectedOption = $(this).val();
            fetchFiltereUpholsterydData(selectedOption);
        });
    });

    //Footwear and Bags
    $(document).ready(function(){
        function fetchAllFootBagsData() {
            $.ajax({
                url: '/admin/fetch-data-footbags',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    displayFootBagsData(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // Function to fetch and display filtered data
        function fetchFiltereFootBagsdData(selectedOption) {
            $.ajax({
                url: '/admin/fetch-data-footbags',
                method: 'GET',
                dataType: 'json',
                data: {
                    option: selectedOption
                },
                success: function(response) {
                    displayFootBagsData(response);
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(error);
                }
            });
        }

        // Function to display data in the UI
        function displayFootBagsData(data) {
            $('#data-listfootbags').empty();
            var serialNumber = 1;
            data.forEach(function(items_foot) {
                var temp = `
                    <tr>
                        <td>` + serialNumber + `</td>
                        <td>`+items_foot.product_name+`</td>
                        <td>`+items_foot.service_name+`</td>
                        <td>`+items_foot.price+`</td>
                        <td>
                            <div class="Client_table_action_area">
                                <button class="btn Client_table_action_icon px-2 edit_item_btn" data-id="`+items_foot.product_cat_id+ `" data-op_id="` + items_foot.operation_id +`"  data-name="`+items_foot.product_name+`" data-price="`+items_foot.price+`" data-bs-toggle="modal" data-bs-target="#edit_items"><i class="tf-icons ti ti-pencil"></i></button>
                                <button class="btn Client_table_action_icon px-2 delete_btn" data-id="`+items_foot.product_cat_id+`" data-bs-toggle="modal" data-bs-target="#delete_item"><i class="tf-icons ti ti-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
                serialNumber++;
                $('#data-listfootbags').append(temp);

            });
        }

        fetchAllFootBagsData();

        // Set up event listener on the dropdown menu
        $('#dropdown_footbags').change(function() {
            var selectedOption = $(this).val();
            fetchFiltereFootBagsdData(selectedOption);
        });
    });

    //Others Data
    $(document).ready(function(){
        // Fetch Upholstery Data
        function fetchAllOtherData() {
            $.ajax({
                url: '/admin/fetch-data-other',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    displayOtherData(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // Function to fetch and display filtered data
        function fetchFiltereOtherData(selectedOption) {
            $.ajax({
                url: '/admin/fetch-data-other',
                method: 'GET',
                dataType: 'json',
                data: {
                    option: selectedOption
                },
                success: function(response) {
                    displayOtherData(response);
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(error);
                }
            });
        }

        // Function to display data in the UI
        function displayOtherData(data) {
            $('#data-listother').empty();
            var serialNumber = 1;
            data.forEach(function(other) {
                var temp = `
                    <tr>
                        <td>` + serialNumber + `</td>
                        <td>`+other.product_name+`</td>
                        <td>`+other.service_name+`</td>
                        <td>`+other.price+`</td>
                        <td>
                            <div class="Client_table_action_area">
                                <button class="btn Client_table_action_icon px-2 edit_item_btn" data-id="`+other.product_cat_id+ `" data-op_id="` + other.operation_id +`"  data-name="`+other.product_name+`" data-price="`+other.price+`" data-bs-toggle="modal" data-bs-target="#edit_items">
                                    <i class="tf-icons ti ti-pencil"></i>
                                </button>
                                <button class="btn Client_table_action_icon px-2 delete_btn" data-id="`+other.product_cat_id+`" data-bs-toggle="modal" data-bs-target="#delete_item">
                                    <i class="tf-icons ti ti-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                serialNumber++;
                $('#data-listother').append(temp);

            });
        }

        fetchAllOtherData();

        // Set up event listener on the dropdown menu
        $('#dropdown_other').change(function() {
            var selectedOption = $(this).val();
            fetchFiltereOtherData(selectedOption);
        });
    });

    //Laundry Data
    $(document).ready(function(){
        // Fetch Upholstery Data
        function fetchAllLaundryData() {
            $.ajax({
                url: '/admin/fetch-data-laundry',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    displayLaundryData(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // Function to fetch and display filtered data
        function fetchFiltereLaundryData(selectedOption) {
            $.ajax({
                url: '/admin/fetch-data-laundry',
                method: 'GET',
                dataType: 'json',
                data: {
                    option: selectedOption
                },
                success: function(response) {
                    displayLaundryData(response);
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(error);
                }
            });
        }

        // Function to display data in the UI
        function displayLaundryData(data) {
            $('#data-listlaundry').empty();
            var serialNumber = 1;
            data.forEach(function(laundry) {
                var temp = `
                    <tr>
                        <td>` + serialNumber + `</td>
                        <td>`+laundry.product_name+`</td>
                        <td>`+laundry.service_name+`</td>
                        <td>`+laundry.price+`</td>
                        <td>
                            <div class="Client_table_action_area">
                                <button class="btn Client_table_action_icon px-2 edit_item_btn" data-id="`+laundry.product_cat_id+ `" data-op_id="` + laundry.operation_id +`"  data-name="`+laundry.product_name+`" data-price="`+laundry.price+`" data-bs-toggle="modal" data-bs-target="#edit_items">
                                    <i class="tf-icons ti ti-pencil"></i>
                                </button>
                                <button class="btn Client_table_action_icon px-2 delete_btn" data-id="`+laundry.product_cat_id+`" data-bs-toggle="modal" data-bs-target="#delete_item">
                                    <i class="tf-icons ti ti-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                serialNumber++;
                $('#data-listlaundry').append(temp);

            });
        }

        fetchAllLaundryData();

        // Set up event listener on the dropdown menu
        $('#dropdown_laundry').change(function() {
            var selectedOption = $(this).val();
            fetchFiltereLaundryData(selectedOption);
        });
    });

    // $(document).ready(function(){
    //     // alert("+++++");
    //     // $('.edit_item_btn').click(function() {
    //     //     alert("+++++++");
    //     //     var id = $(this).data('id');
    //     //     var name = $(this).data('name');
    //     //     alert(id + name);
    //     //     $('.item_id').val(id);
    //     //     $('.item_name').val(name);
    //     // });
    // });
    $.validator.addMethod("filesize", function(value, element, param) {
        return this.optional(element) || (element.files[0].size <= param);
    }, "File size must be less than {0}");

    function applyeditValidation() {
        $("#edititemForm").validate({
            rules: {
                "name": {
                    required: true,
                    maxlength: 50,
                    // pattern: /^[a-zA-Z\s]+$/
                },
                "service": {
                    required: true
                },
                "price": {
                    required: true,
                    number: true,
                    maxlength: 8,
                    min: 0
                }
            },
            messages: {
                "name": {
                    required: "Please enter item name",
                    maxlength: "Item name must be less than 50 characters",
                    // pattern: "Please enter a valid name (only letters and spaces are allowed)"
                },
                "service": {
                    required: "Please select a service"
                },
                "price": {
                    required: "Please enter price",
                    number: "Please enter a valid number",
                    maxlength: "Price must be less than 8 characters",
                    min: "Price must be greater than or equal to 0"
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") === "name") {
                    element.next('.name-error').text(error.text()).show();
                } else if (element.attr("name") === "service") {
                    element.next('.service-error').text(error.text()).show();
                } else if (element.attr("name") === "price") {
                    element.next('.price-error').text(error.text()).show();
                }
            },
            success: function(label, element) {
                if ($(element).attr("name") === "name") {
                    $(element).next('.name-error').hide();
                } else if ($(element).attr("name") === "service") {
                    $(element).next('.service-error').hide();
                } else if ($(element).attr("name") === "price") {
                    $(element).next('.price-error').hide();
                }
            },
            submitHandler: function(form, event) {
                event.preventDefault(); // Prevent default form submission
                form.submit(); // Submit the form if valid
            }
        });
    }
    applyeditValidation();
    $('#search-input').keyup(function() {
    var searchText = $(this).val().toLowerCase();
    var noRecord = true;
    $('tbody tr').each(function() {
        var itemName = $(this).find('td:nth-child(2)').text().toLowerCase();
        var serviceName = $(this).find('td:nth-child(3)').text().toLowerCase();
        var price = $(this).find('td:nth-child(4)').text().toLowerCase();
        if (itemName.indexOf(searchText) === -1 &&
            serviceName.indexOf(searchText) === -1 &&
            price.indexOf(searchText) === -1) {
            $(this).hide();
        } else {
            $(this).show();
            noRecord = false;
        }
    });
    if (noRecord) {
        $('.no-records-found').show();
    } else {
        $('.no-records-found').hide();
    }
});
    });
</script>
