<?php

use RedFreak\Menu\RenderOptions;

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
            RenderOptions::KEY_USE_LABELS_AS_TRANSLATION_KEYS => true, // whether to use the labels as translate keys for __() function
            RenderOptions::KEY_STYLE => 'list', // defines the HTML-tag to use for the menu items ('list' => ul/li, 'menu' => menu/li, 'div' => div/div)
            RenderOptions::KEY_LINK_SUBMENU_ANCHOR => true, // whether to set the link at the submenu anchor or not
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | classes
    |--------------------------------------------------------------------------
    |
    | This option declares which classes are used for the menus.
    |
    */

    'classes' => [
        'root' => 'menu',
        'add_type_to_root' => true, // whether to add the menu style to the root classes e.g. menu--list
        'sub_menu' => 'sub-menu',
        'item' => 'menu-item',
        'add_has_children' => true,
        'add_menu_as_class' => true, // whether to add the menu name as class to the root element e.g. menu--default
    ],

    /*
    |--------------------------------------------------------------------------
    | default_translation_prefix
    |--------------------------------------------------------------------------
    |
    | This option declares which prefix is used for the translation keys.
    | E.g. for the generic method addFromModel().
    |
    */

    'default_translation_prefix' => 'menu.label.',

    /*
    |--------------------------------------------------------------------------
    | model_routes
    |--------------------------------------------------------------------------
    |
    | The model routes uses by addFromModel().
    | index is used everytime.
    |
    */

    'model_routes' => ['create'],
];
