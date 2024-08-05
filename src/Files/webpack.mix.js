const mix = require('laravel-mix');

mix
    .scripts([
        'resources/vuexy-assets/vendors/js/vendors.min.js',
        'resources/vuexy-assets/vendors/js/extensions/dropzone.min.js',
        'resources/vuexy-assets/vendors/js/tables/datatable/datatables.min.js',//dropzone
        'resources/vuexy-assets/vendors/js/tables/datatable/datatables.buttons.min.js',//
        'resources/vuexy-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js',//
        'resources/vuexy-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js',//
        'resources/vuexy-assets/vendors/js/tables/datatable/dataTables.select.min.js',//
        'resources/vuexy-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js',//
        'resources/vuexy-assets/vendors/js/charts/apexcharts.min.js',//
        'resources/vuexy-assets/js/core/app-menu.min.js',
        'resources/vuexy-assets/js/core/app.min.js',
        'resources/vuexy-assets/js/scripts/components.min.js',//
        'resources/vuexy-assets/js/scripts/footer.min.js',
        'resources/vuexy-assets/js/scripts/extensions/toastr.min.js',
        'resources/vuexy-assets/vendors/js/extensions/toastr.min.js',
        'resources/vuexy-assets/js/scripts/ui/data-list-view.min.js',//
    ], 'public/js/vuexy.js')

    .styles([
        'resources/vuexy-assets/vendors/css/vendors-rtl.min.css',
        'resources/vuexy-assets/css-rtl/bootstrap.min.css',
        'resources/vuexy-assets/css-rtl/bootstrap-extended.min.css',
        'resources/vuexy-assets/css-rtl/pages/data-list-view.min.css',
        'resources/vuexy-assets/css-rtl/components.min.css',
        'resources/vuexy-assets/css-rtl/themes/semi-dark-layout.min.css',
        'resources/vuexy-assets/css-rtl/core/menu/menu-types/vertical-menu.min.css',
        'resources/vuexy-assets/css-rtl/custom-rtl.min.css',
        'resources/vuexy-assets/css-rtl/plugins/extensions/toastr.min.css',
        'resources/vuexy-assets/css-rtl/pages/app-chat.min.css',
        'resources/vuexy-assets/vendors/css/tables/datatable/datatables.min.css',
        'resources/vuexy-assets/css-rtl/colors.min.css',
        'resources/vuexy-assets/vendors/css/extensions/toastr.css',
        'resources/vuexy-assets/vendors/css/charts/apexcharts.css',
    ], 'resources/css/vuexy.css');

