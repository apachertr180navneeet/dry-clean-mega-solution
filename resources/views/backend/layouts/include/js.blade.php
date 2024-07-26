<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{url('theam/assets/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/popper/popper.js')}}"></script>
<script src="{{url('theam/assets/vendor/js/bootstrap.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{url('package/jquery.validate.min.js')}}"></script>
<script src="{{url('package/additional-methods.min.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/node-waves/node-waves.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/hammer/hammer.js')}}"></script>
<script src="{{url('/theam/assets/vendor/js/menu.js')}}"></script>
<script src="{{url('theam/assets/js/main.js')}}"></script>
<script src="{{url('theam/assets/vendor/js/helpers.js')}}"></script>
<script src="{{url('theam/assets/vendor/js/template-customizer.js')}}"></script>
<script src="{{url('theam/assets/js/config.js')}}"></script>
<script src="{{url('theam/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('theam/assets/vendor/js/helpers.js')}}"></script>
<script src="{{url('theam/assets/js/config.js')}}"></script>
<script src="{{url('theam/assets/js/forms-editors.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/quill/katex.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/quill/quill.js')}}"></script>
<script src="{{url('package/toastr.min.js')}}"></script>
<script src="{{url('package/bootstrap2-toggle.min.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/tagify/tagify.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/bloodhound/bloodhound.js')}}"></script>
<script src="{{url('theam/assets/js/main.js')}}"></script>
<script src="{{url('theam/assets/js/forms-selects.js')}}"></script>
<script src="{{url('theam/assets/js/forms-tagify.js')}}"></script>
<script src="{{url('theam/assets/js/forms-typeahead.js')}}"></script>
<script src="{{url('package/dropify.min.js')}}"></script>
<script src="{{url('summernote/jquery.min.js')}}"></script>
<script src="{{url('summernote/summernote-bs5.js')}}"></script>
<script src="{{url('summernote/summernote-bs4.js')}}"></script>
<script src="{{url('summernote/summernote.js')}}"></script>
<script src="{{url('summernote/summernote-lite.js')}}"></script>
<script src="{{url('summernote/summernote-es-ES.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/i18n/i18n.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/jquery-timepicker/jquery-timepicker.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/pickr/pickr.js')}}"></script>
<script src="{{url('theam/assets/js/forms-pickers.js')}}"></script>
<script src="{{url('theam/assets/vendor/js/menu.js')}}"></script>
<!-- endbuild -->
<!-- Vendors JS -->
<script src="{{url('theam/assets/vendor/libs/swiper/swiper.js')}}"></script>
<!-- <script src="{{url('theam/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script> -->
<!-- Main JS -->
<script src="{{url('theam/assets/js/main.js')}}"></script>
<!-- Page JS -->
<script src="{{url('theam/assets/js/dashboards-analytics.js')}}"></script>
<script>
    $('.summernote').summernote({
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']],
        ],
    });
</script>
<script>
    var newButton = function(context) {
        var ui = $.summernote.ui;

        // create button
        var button = ui.button({
            contents: 'New',
            tooltip: 'A New Button',
            click: function() {
                // invoke insertText method with 'new' on editor module.
                context.invoke('editor.insertText', 'new');
            }
        });

        return button.render(); // return button as jquery object

    }

    $('.summernote').summernote({
        toolbar: [
            ['mybutton', ['new']]
        ],

        buttons: {
            new: newButton
        }
    });
</script>
<script src="{{url('theam/assets/js/form-validation.js')}}"></script>

<script src="{{url('theam/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{url('theam/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
