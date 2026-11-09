<?php

/*
|--------------------------------------------------------------------------
| Access Control List
|--------------------------------------------------------------------------
| Central definition of admin permissions, grouped by module. Used by the
| RolePermissionSeeder to create permissions and by the admin sidebar /
| policies to gate access. "access_admin" is the master key that lets a
| user enter the admin panel at all.
*/

return [

    'groups' => [

        'dashboard' => [
            'label' => 'Dashboard',
            'icon' => 'home',
            'permissions' => [
                'access_admin' => 'Access admin panel',
            ],
        ],

        'catalog' => [
            'label' => 'Catalog',
            'icon' => 'box',
            'permissions' => [
                'products.view'   => 'View products',
                'products.create' => 'Create products',
                'products.edit'   => 'Edit products',
                'products.delete' => 'Delete products',
                'categories.manage' => 'Manage categories',
            ],
        ],

        'sales' => [
            'label' => 'Orders',
            'icon' => 'cart',
            'permissions' => [
                'orders.view'   => 'View orders',
                'orders.edit'   => 'Update orders',
                'orders.delete' => 'Delete orders',
            ],
        ],

        'customers' => [
            'label' => 'Customers',
            'icon' => 'users',
            'permissions' => [
                'customers.view'   => 'View customers',
                'customers.delete' => 'Delete customers',
            ],
        ],

        'reviews' => [
            'label' => 'Reviews',
            'icon' => 'star',
            'permissions' => [
                'reviews.view'   => 'View reviews',
                'reviews.manage' => 'Approve / manage reviews',
            ],
        ],

        'affiliate' => [
            'label' => 'Affiliate',
            'icon' => 'users',
            'permissions' => [
                'contributors.view'   => 'View contributors',
                'earnings.view'       => 'View earnings',
                'withdrawals.view'    => 'View withdrawals',
                'withdrawals.manage'  => 'Approve / reject withdrawals',
            ],
        ],

        'finance' => [
            'label' => 'Finance',
            'icon' => 'cash',
            'permissions' => [
                'expenses.view'   => 'View expenses',
                'expenses.manage' => 'Manage expenses',
            ],
        ],

        'content' => [
            'label' => 'Content',
            'icon' => 'document',
            'permissions' => [
                'pages.manage' => 'Manage pages',
            ],
        ],

        'settings' => [
            'label' => 'Settings',
            'icon' => 'cog',
            'permissions' => [
                'settings.manage' => 'Manage store settings (general, shipping, tax, affiliate)',
            ],
        ],

        'access' => [
            'label' => 'Access Control',
            'icon' => 'shield',
            'permissions' => [
                'users.manage' => 'Manage users / staff',
                'roles.manage' => 'Manage roles & permissions',
            ],
        ],
    ],
];
