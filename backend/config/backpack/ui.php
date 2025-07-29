<?php

return [
    // Change the view namespace in order to load a different theme than the one Backpack provides.
    'view_namespace' => 'backpack.theme-coreuiv2::',

    // Project name - shown in the breadcrumbs and a few other places
    'project_name' => 'Contact Manager',

    // When clicking on the admin panel's top-left logo/name,
    // where should the user be redirected?
    'home_link' => '/admin',

    // Content of the HTML meta tags (dashboard and login page)
    'meta_description' => 'Contact Manager Admin Panel',
    'meta_keywords' => 'admin,contact,manager',
    'meta_author' => 'Contact Manager Team',

    // Show / hide breadcrumbs
    'breadcrumbs' => true,

    // Colors
    'colors' => [
        'primary' => '#4f46e5', // Indigo 600
        'secondary' => '#6b7280', // Gray 500
        'info' => '#3b82f6', // Blue 500
        'success' => '#10b981', // Emerald 500
        'warning' => '#f59e0b', // Amber 500
        'danger' => '#ef4444', // Red 500
    ],

    // Additional classes for <body> tag
    'body_class' => 'app aside-menu-fixed sidebar-lg-show',

    // Options: horizontal/vertical
    'layout' => 'vertical',

    // Background image - visible on login page
    'background_image' => false,
];
