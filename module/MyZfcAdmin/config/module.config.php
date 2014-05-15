<?php

$config = array(
    'controllers' => array(
        'invokables' => array(
            'ZfcAdmin\Controller\AdminController' => 'MyZfcAdmin\Controller\AdminController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),        
        'template_map' => array(
            'layout/login'           => __DIR__ . '/../view/layout/login.phtml',
        ),
    ),
);

$config['router']['routes']['zfcadmin'] = array(
    'type' => 'Segment',
    'options' => array(
        'route'    => '/admin',
        'constraints' => array(
            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        ),
        'defaults' => array(
            'controller' => 'ZfcAdmin\Controller\AdminController',
            'action'     => 'index',
        ),
    ),
    'may_terminate' => true,
);

$config['router']['routes']['zfcadmin']['child_routes'] = array(
    'login' => array(
        'type'    => 'Literal',
        'options' => array(
            'route'    => '/login',
            'defaults' => array(
                'controller' => 'ZfcAdmin\Controller\AdminController',
                'action'     => 'login',
            ),
        ),
    ),
    'logout' => array(
        'type'    => 'Literal',
        'options' => array(
            'route'    => '/logout',
            'defaults' => array(
                'controller' => 'ZfcAdmin\Controller\AdminController',
                'action'     => 'logout',
            ),
        ),
    ),
    'news' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/news',
            'defaults' => array(
                'controller' => 'News\Controller\Item',
                'action'     => 'list',
            ),
        ),
        'may_terminate' => true, 
        'child_routes' => array(
            'add' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/add',
                    'defaults' => array(
                        'controller' => 'News\Controller\Item',
                        'action'     => 'add',
                    )
                ),
                'may_terminate' => true,
            ),
            'full' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/full[/:id]',
                    'constraints' => array(
                        'id'     => '[1-9][0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'News\Controller\Item',
                        'action'     => 'add',
                    )
                ),
                'may_terminate' => true,
            ),
            'categories' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/categories[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'News\Controller\Category',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
);

return $config;