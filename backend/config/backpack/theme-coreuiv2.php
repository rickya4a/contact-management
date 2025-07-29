<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Theme Configuration Values
    |--------------------------------------------------------------------------
    |
    | The file is for unifying the naming of blade elements
    |
    */

    // Templates used for Backpack CRUD Views
    'view_namespace' => 'backpack.theme-coreuiv2::',

    /*
    |--------------------------------------------------------------------------
    | Theme Files
    |--------------------------------------------------------------------------
    |
    | The file is for unifying the naming of blade elements
    |
    */

    'files' => [
        'styles' => [
            'packages/@digitallyhappy/backstrap/css/style.min.css',
            'packages/animate.css/animate.min.css',
            'packages/noty/noty.css',
            'packages/noty/themes/mint.css',
            'packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
            'packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css',
            'packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css',
        ],
        'scripts' => [
            'packages/jquery/jquery.min.js',
            'packages/popper.js/popper.min.js',
            'packages/bootstrap/bootstrap.min.js',
            'packages/perfect-scrollbar/perfect-scrollbar.min.js',
            'packages/@digitallyhappy/backstrap/js/main.min.js',
            'packages/datatables.net/js/jquery.dataTables.min.js',
            'packages/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
            'packages/datatables.net-responsive/js/dataTables.responsive.min.js',
            'packages/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js',
            'packages/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js',
            'packages/datatables.net-fixedheader-bs4/js/fixedHeader.bootstrap4.min.js',
            'packages/noty/noty.min.js',
            'packages/sweetalert/sweetalert.min.js',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Colors
    |--------------------------------------------------------------------------
    |
    | The file is for unifying the naming of blade elements
    |
    */

    'colors' => [
        'primary' => '#4f46e5', // Indigo 600
        'secondary' => '#6b7280', // Gray 500
        'info' => '#3b82f6', // Blue 500
        'success' => '#10b981', // Emerald 500
        'warning' => '#f59e0b', // Amber 500
        'danger' => '#ef4444', // Red 500
    ],
];
