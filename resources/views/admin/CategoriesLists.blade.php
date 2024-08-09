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
            /* Hidden by default */
        }
    </style>
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="client_list_area_hp">
                <div class="card">
                    <div class="card-body">
                        <div class="client_list_heading_area">
                            <div class="client_list_heading_search_area">
                                <form action="{{ route('payment') }}" method="GET" class="d-flex">
                                    <i class="menu-icon tf-icons ti ti-search" id="resetSearch"></i>
                                    <input type="search" name="search" class="form-control" placeholder="Searching ..."
                                        id="paymentSearch" value="{{ request()->input('search') }}">
                                    <button type="submit" class="btn btn-primary ms-2">Search</button>
                                </form>

                                <div class="mt-3 mb-4">
                                    <span>A</span>
                                    <span>B</span>
                                    <span>C</span>
                                    <span>D</span>
                                    <span>E</span>
                                    <span>F</span>
                                    <span>G</span>
                                    <span>H</span>
                                    <span>I</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-3">
                                <div class="border rounded p-3 mb-2 hover-shadow">
                                    <div class="row align-items-center">
                                        <div class="col-lg-9 col-md-9 mainopdiv">
                                            <h6 class="mb-2 text-dark">Testing</h6>
                                            <div class="categorysection">
                                                <span class="badge text-dark mb-2 subcategory bg-light">test</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 text-center">
                                            <img class="mb-3"
                                                src="{{ url('theam/Images/shirt/shirt.jpg') }}"
                                                alt="" style="width: 70px;height: 50px;object-fit: contain;">
                                            <div class="Add_order_btn_area">
                                                <button type="button" id="addbtnpreview"
                                                    class="btn btn-primary text-white waves-effect waves-light" data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvasRight"
                                                    aria-controls="offcanvasRight"
                                                    data-product-name=""
                                                    data-images="">Add</button>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-3">
                                <div class="border rounded p-3 mb-2 hover-shadow">
                                    <div class="row align-items-center">
                                        <div class="col-lg-9 col-md-9 mainopdiv">
                                            <h6 class="mb-2 text-dark">Testing</h6>
                                            <div class="categorysection">
                                                <span class="badge text-dark mb-2 subcategory bg-light">test</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 text-center">
                                            <img class="mb-3"
                                                src="{{ url('theam/Images/shirt/shirt.jpg') }}"
                                                alt="" style="width: 70px;height: 50px;object-fit: contain;">
                                            <div class="Add_order_btn_area">
                                                <button type="button" id="addbtnpreview"
                                                    class="btn btn-primary text-white waves-effect waves-light" data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvasRight"
                                                    aria-controls="offcanvasRight"
                                                    data-product-name=""
                                                    data-images="">Add</button>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-3">
                                <div class="border rounded p-3 mb-2 hover-shadow">
                                    <div class="row align-items-center">
                                        <div class="col-lg-9 col-md-9 mainopdiv">
                                            <h6 class="mb-2 text-dark">Testing</h6>
                                            <div class="categorysection">
                                                <span class="badge text-dark mb-2 subcategory bg-light">test</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 text-center">
                                            <img class="mb-3"
                                                src="{{ url('theam/Images/shirt/shirt.jpg') }}"
                                                alt="" style="width: 70px;height: 50px;object-fit: contain;">
                                            <div class="Add_order_btn_area">
                                                <button type="button" id="addbtnpreview"
                                                    class="btn btn-primary text-white waves-effect waves-light" data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvasRight"
                                                    aria-controls="offcanvasRight"
                                                    data-product-name=""
                                                    data-images="">Add</button>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-3">
                                <div class="border rounded p-3 mb-2 hover-shadow">
                                    <div class="row align-items-center">
                                        <div class="col-lg-9 col-md-9 mainopdiv">
                                            <h6 class="mb-2 text-dark">Testing</h6>
                                            <div class="categorysection">
                                                <span class="badge text-dark mb-2 subcategory bg-light">test</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 text-center">
                                            <img class="mb-3"
                                                src="{{ url('theam/Images/shirt/shirt.jpg') }}"
                                                alt="" style="width: 70px;height: 50px;object-fit: contain;">
                                            <div class="Add_order_btn_area">
                                                <button type="button" id="addbtnpreview"
                                                    class="btn btn-primary text-white waves-effect waves-light" data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvasRight"
                                                    aria-controls="offcanvasRight"
                                                    data-product-name=""
                                                    data-images="">Add</button>
                                                
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
    </div>
    
@endsection
