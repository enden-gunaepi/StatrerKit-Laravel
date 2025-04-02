<?php

return [
    [
        'title' => 'Menu',
        'menus' => [
            [
                'title' => 'Dashboard',
                'icon' => 'bi bi-laptop',
                'href' => 'dashboard',
                'data_key' => 'dashboards',
                // 'permission' => 'view-dashboard'
            ]
        ]
    ],
    [
        'title' => 'Management Users',
        'menus' => [
            [
                'title' => 'Users',
                'icon' => 'bi bi-person-square',
                'href' => 'users',
                'data_key' => 'users',
                'permission' => 'view-user'
            ],
            [
                'title' => 'Roles',
                'icon' => 'ti ti-accessible',
                'href' => 'roles',
                'data_key' => 'roles',
                'permission' => 'view-role'
            ],
            [
                'title' => 'Logs',
                'icon' => 'bi bi-file-earmark-text',
                'href' => 'logs',
                'data_key' => 'logs',
                'permission' => 'view-log'
            ],
            [
                'title' => 'Settings',
                'icon' => 'bi bi-sliders',
                'href' => 'settings',
                'data_key' => 'settings',
                'permission' => 'view-settings'
            ],
        ]
    ],
    [
        'title' => 'Components',
        'menus' => [
            [
                'title' => 'Layouts',
                'icon' => 'ti ti-layout-board',
                'href' => 'sidebarLayouts',
                'data_key' => 'layouts',
                'badge' => [
                    'text' => 'Hot',
                    'class' => 'bg-primary',
                    'data_key' => 'hot'
                ],
                'submenus' => [
                    ['title' => 'Horizontal', 'href' => 'layouts-horizontal', 'data_key' => 'horizontal'],
                    ['title' => 'Detached', 'href' => 'layouts-detached', 'data_key' => 'detached'],
                    ['title' => 'Two Column', 'href' => 'layouts-two-column', 'data_key' => 'two-column'],
                    ['title' => 'Hovered', 'href' => 'layouts-vertical-hovered', 'data_key' => 'hovered'],
                ],
            ],
            [
                'title' => 'Multi Level',
                'icon' => 'ti ti-brand-stackshare',
                'href' => 'sidebarMultilevel',
                'data_key' => 'multi-level',
                'submenus' => [
                    ['title' => 'Level 1.1', 'href' => '#', 'data_key' => 'level-1.1'],
                    [
                        'title' => 'Level 1.2',
                        'href' => 'sidebarLevel1_2',
                        'data_key' => 'level-1.2',
                        'submenus' => [
                            ['title' => 'Level 2.1', 'href' => '#', 'data_key' => 'level-2.1'],
                            [
                                'title' => 'Level 2.2',
                                'href' => 'sidebarLevel2_2',
                                'data_key' => 'level-2.2',
                                'submenus' => [
                                    ['title' => 'Level 3.1', 'href' => '#', 'data_key' => 'level-3.1'],
                                    ['title' => 'Level 3.2', 'href' => '#', 'data_key' => 'level-3.2'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]
    ]
];
