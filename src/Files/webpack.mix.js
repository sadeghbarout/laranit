const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

//
mix

    // javascript files  ===============================================================================================
    .js('resources/js/app.js','public/tmp/app.js').vue()
    // .babel([
    .scripts([
        'public/tmp/app.js',
        // 'node_modules/jquery/dist/jquery.min.js',
        'node_modules/sweetalert2/dist/sweetalert2.all.min.js',
        'node_modules/bootstrap/dist/js/bootstrap.min.js',
        'resources/assets/libs/adminlte3/js/adminlte.min.js',
        'resources/assets/libs/adminlte3/plugins/chart.js/Chart.min.js',
    ], 'public/js/app.js')


    .styles([
        // 'public/tmp/app_temp.css',
        'node_modules/bootstrap/dist/css/bootstrap.min.css',
        'resources/assets/libs/adminlte3/css/adminlte.css',
        'resources/assets/libs/adminlte3/css/style.css',
        'resources/assets/libs/adminlte3/plugins/chart.js/Chart.min.css',
    ], 'public/css/app.css')

;











