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
    ),
);

$config['router']['routes']['zfcadmin']['child_routes'] = array(
    'news' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/news',
            'defaults' => array(
                'controller' => 'News\Controller\NewsItem',
                'action'     => 'list',
            ),
        ),
        'may_terminate' => true, 
        'child_routes' => array(
            'add news' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/add',
                    'defaults' => array(
                        'controller' => 'News\Controller\NewsItem',
                        'action'     => 'add',
                    )
                ),
                'may_terminate' => true,
            ),
            'add news' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/full[/:id]',
                    'constraints' => array(
                        'id'     => '[1-9][0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'News\Controller\NewsItem',
                        'action'     => 'add',
                    )
                ),
                'may_terminate' => true,
            ),
        ),
    ),
);

return $config;