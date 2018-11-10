<?php

return [
    'default_superadministrator' => [
        'name' => env('DEFAULT_SUPERADMINISTRATOR_NAME', 'Username'),
        'email' => env('DEFAULT_SUPERADMINISTRATOR_EMAIL', 'default.superadministrator@email.com'),
        'password' => env('DEFAULT_SUPERADMINISTRATOR_PASSWORD', '11111111'),
    ],
    'role_structure' => [
        'superadministrator' => [
            'role_to_module' => [
                'administrators' => 'c,r,u,d',
                'rules' => 'c,r,u,d',
                'users' => 'c,r,u,d',
                'ingredients' => 'c,r,u,d',
                'recipes' => 'c,r,u,d',
                'analytics' => 'c,r,u,d',
            ],
            'display_name'=>env('LARATRUST_SUPERADMINISTRATOR_DISPLAY_NAME', 'superadministrator_display_name'),
            'description'=>env('LARATRUST_SUPERADMINISTRATOR_DESCRIPTION', 'superadministrator_description'),
        ],
        'administrator' => [
            'role_to_module' => [
                'rules' => 'c,r,u,d',
                'users' => 'c,r,u,d',
                'ingredients' => 'c,r,u,d',
                'recipes' => 'c,r,u,d',
                'analytics' => 'c,r,u,d',
            ],
            'display_name'=>env('LARATRUST_ADMINISTRATOR_DISPLAY_NAME', 'administrator_display_name'),
            'description'=>env('LARATRUST_ADMINISTRATOR_DESCRIPTION', 'administrator_description'),
        ],
        'moderator' => [
            'role_to_module' => [
                'users' => 'c,r,u,d',
                'ingredients' => 'c,r,u,d',
                'recipes' => 'c,r,u,d',
                'analytics' => 'c,r,u,d',
            ],
            'display_name'=>env('LARATRUST_MODERATOR_DISPLAY_NAME', 'moderator_display_name'),
            'description'=>env('LARATRUST_MODERATOR_DESCRIPTION', 'moderator_description'),
        ],
        'client' => [
            'role_to_module' => [
                'users' => 'r',
                'ingredients' => 'r',
                'recipes' => 'r',
            ],
            'display_name'=>env('LARATRUST_CLIENT_DISPLAY_NAME', 'client_display_name'),
            'description'=>env('LARATRUST_CLIENT_DESCRIPTION', 'client_description'),
        ],
        'guest' => [
            'role_to_module' => [
                'users' => 'r',
                'ingredients' => 'r',
                'recipes' => 'r',
            ],
            'display_name'=>env('LARATRUST_GUEST_DISPLAY_NAME', 'guest_display_name'),
            'description'=>env('LARATRUST_GUEST_DESCRIPTION', 'guest_description'),
        ],
    ],
    'permission_structure' => [],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ],
    'non_removable_role' => [
        'role_name' => env('LARATRUST_NON_REMOVABLE_ROLE', 'non_removable_role')
    ],
];
