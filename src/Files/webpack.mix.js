const mix = require('laravel-mix');

mix
    .scripts([
        'node_modules/jquery/dist/jquery.min.js',
        'resources/assets/libs/adminlte3/js/adminlte.min.js',
    ], 'public/js/adminlte.js')

    .styles([
        'resources/assets/libs/adminlte3/css/adminlte.css',
        'resources/assets/libs/adminlte3/css/style.css',
    ], 'resources/css/adminlte.css');

