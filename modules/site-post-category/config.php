<?php

return [
    '__name' => 'site-post-category',
    '__version' => '0.0.1',
    '__git' => 'git@github.com:getmim/site-post-category.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'app/site-post-category' => ['install','remove'],
        'modules/site-post-category' => ['install','update','remove'],
        'theme/site/post-category' => ['install','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'post' => NULL
            ],
            [
                'post-category' => NULL
            ],
            [
                'site' => NULL
            ],
            [
                'site-meta' => NULL
            ],
            [
                'lib-formatter' => NULL
            ]
        ],
        'optional' => [
            [
                'lib-event' => NULL
            ],
            [
                'lib-cache-output' => NULL
            ]
        ]
    ],
    'autoload' => [
        'classes' => [
            'SitePostCategory\\Controller' => [
                'type' => 'file',
                'base' => ['modules/site-post-category/controller','app/site-post-category/controller']
            ],
            'SitePostCategory\\Library' => [
                'type' => 'file',
                'base' => 'modules/site-post-category/library'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'site' => [
            'sitePostCategorySingle' => [
                'path' => [
                    'value' => '/post/category/(:slug)',
                    'params' => [
                        'slug' => 'slug'
                    ]
                ],
                'method' => 'GET',
                'handler' => 'SitePostCategory\\Controller\\Category::single'
            ],
            'sitePostCategorySingleFeed' => [
                'path' => [
                    'value' => '/post/category/(:slug)/feed.xml',
                    'params' => [
                        'slug' => 'slug'
                    ]
                ],
                'method' => 'GET',
                'handler' => 'SitePostCategory\\Controller\\Robot::feed'
            ]
        ]
    ],
    'libFormatter' => [
        'formats' => [
            'post-category' => [
                'page' => [
                    'type' => 'router',
                    'router' => [
                        'name' => 'sitePostCategorySingle',
                        'params' => [
                            'slug' => '$slug'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'libEvent' => [
        'events' => [
            'post-category:created' => [
                'SitePostCategory\\Library\\Event::clear' => TRUE
            ],
            'post-category:deleted' => [
                'SitePostCategory\\Library\\Event::clear' => TRUE
            ],
            'post-category:updated' => [
                'SitePostCategory\\Library\\Event::clear' => TRUE
            ]
        ]
    ],
    'site' => [
        'robot' => [
            'feed' => [
                'SitePostCategory\\Library\\Robot::feed' => TRUE
            ],
            'sitemap' => [
                'SitePostCategory\\Library\\Robot::sitemap' => TRUE
            ]
        ]
    ]
];