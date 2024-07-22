@extends('backend.layouts.app')
@section('content')
    <div class="content-wrapper page_content_section_hp">
        <div class="container-xxl">
            <div class="client_list_area_hp Add_order_page_section">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-between mb-3">
                            <div class="col-lg-3">
                                <a type="button" class="text-primary" href="{{ url('/admin/view-order') }}">
                                    <i class="fa-solid fa-arrow-left me-2"></i> Tags
                                </a>
                            </div>
                            <div class="col-lg-1">
                                <a class="btn btn-primary" href="{{ url('/print-taglist/' . $order->id) }}"
                                    type="button"><i class="fa-solid fa-print me-2"></i></a>
                            </div>
                        </div>
                        @include('admin.downloadTagslist');

                    </div>
                </div>
            </div>
        </div>
    @endsection
    <!-- <style>
        ul {
            counter-reset: list-counter;
            list-style-type: none;
        }

        li::before {
            content: counter(list-counter);
            counter-increment: list-counter;
            margin-right: 0.5em;
        }
    </style> -->
