<?php
return [
    'service_manager' => [
        'aliases' => [
            'HttpRouter' => 'Laminas\\Router\\Http\\TreeRouteStack',
            'router' => 'Laminas\\Router\\RouteStackInterface',
            'Router' => 'Laminas\\Router\\RouteStackInterface',
            'RoutePluginManager' => 'Laminas\\Router\\RoutePluginManager',
            'Zend\\Router\\Http\\TreeRouteStack' => 'Laminas\\Router\\Http\\TreeRouteStack',
            'Zend\\Router\\RoutePluginManager' => 'Laminas\\Router\\RoutePluginManager',
            'Zend\\Router\\RouteStackInterface' => 'Laminas\\Router\\RouteStackInterface',
            'Laminas\\Validator\\Translator\\TranslatorInterface' => 'Laminas\\Validator\\Translator\\Translator',
            'ValidatorManager' => 'Laminas\\Validator\\ValidatorPluginManager',
            'Zend\\Validator\\ValidatorPluginManager' => 'Laminas\\Validator\\ValidatorPluginManager'
        ],
        'factories' => [
            'Laminas\\Router\\Http\\TreeRouteStack' => 'Laminas\\Router\\Http\\HttpRouterFactory',
            'Laminas\\Router\\RoutePluginManager' => 'Laminas\\Router\\RoutePluginManagerFactory',
            'Laminas\\Router\\RouteStackInterface' => 'Laminas\\Router\\RouterFactory',
            'Laminas\\Validator\\Translator\\Translator' => 'Laminas\\Validator\\Translator\\TranslatorFactory',
            'Laminas\\Validator\\ValidatorPluginManager' => 'Laminas\\Validator\\ValidatorPluginManagerFactory'
        ]
    ],
    'route_manager' => [],
    'router' => [
        'routes' => [
            'home' => [
                'type' => 'Laminas\\Router\\Http\\Literal',
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => 'Application\\Controller\\IndexController',
                        'action' => 'index'
                    ]
                ]
            ],
            'application' => [
                'type' => 'Laminas\\Router\\Http\\Segment',
                'options' => [
                    'route' => '/application[/:action]',
                    'defaults' => [
                        'controller' => 'Application\\Controller\\IndexController',
                        'action' => 'index'
                    ]
                ]
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            'Application\\Controller\\IndexController' => 'Laminas\\ServiceManager\\Factory\\InvokableFactory'
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => 'C:\\xampp\\htdocs\\estoque-supermercado-tres-glorias\\src\\module\\Application\\config/../view/layout/layout.phtml',
            'application/index/index' => 'C:\\xampp\\htdocs\\estoque-supermercado-tres-glorias\\src\\module\\Application\\config/../view/application/index/index.phtml',
            'error/404' => 'C:\\xampp\\htdocs\\estoque-supermercado-tres-glorias\\src\\module\\Application\\config/../view/error/404.phtml',
            'error/index' => 'C:\\xampp\\htdocs\\estoque-supermercado-tres-glorias\\src\\module\\Application\\config/../view/error/index.phtml'
        ],
        'template_path_stack' => [
            'C:\\xampp\\htdocs\\estoque-supermercado-tres-glorias\\src\\module\\Application\\config/../view'
        ]
    ]
];
