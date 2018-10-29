<?php

return [
    'role_structure' => [
        'superadministrator' => [
            'rules' => 'c,r,u,d',
            'users' => 'c,r,u,d',
            'ingredients' => 'c,r,u,d',
            'recipes' => 'c,r,u,d',
            'analytics' => 'c,r,u,d',
        ],
        'administrator' => [
            'rules' => 'c,r,u,d',
            'users' => 'c,r,u,d',
            'ingredients' => 'c,r,u,d',
            'recipes' => 'c,r,u,d',
            'analytics' => 'c,r,u,d',
        ],
        'moderator' => [
            'rules' => 'c,r,u,d',
            'users' => 'c,r,u,d',
            'ingredients' => 'c,r,u,d',
            'recipes' => 'c,r,u,d',
            'analytics' => 'c,r,u,d',
        ],
    ],
    'permission_structure' => [],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
