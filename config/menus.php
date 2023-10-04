<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Menus
    |--------------------------------------------------------------------------
    |
    | This option declares which menus are available in your application.
    |
    */

    'menus' => [
        'default' => [
            'max_depth' => 0, // 0 means no limit
            'mark_active' => true, // whether to mark the active menu item
            'mark_parent_active' => true, // whether to mark the parent of the active menu item
            'use_labels_as_translate_keys' => true, // whether to use the labels as translate keys for __() function
            'style' => 'list', // defines the HTML-tag to use for the menu items ('list' => ul/li, 'menu' => menu/li, 'div' => div/div)
        ],
    ],

    'classes' => [
        'root' => 'menu',
        'add_type_to_root' => true, // whether to add the menu type to the root classes
        'item' => 'menu-item',
        'add_has_children' => true,
        'add_menu_as_class' => true, // whether to add the menu name as class to the root element e.g. menu--default
    ]
];
