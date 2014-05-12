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
        'type' => 'literal',
        'options' => array(
            'route' => '/news',
            'defaults' => array(
                'controller' => 'News\Controller\NewsItem',
                'action'     => 'index',
            ),
            'may_terminate' => true, 
            'child_routes' => array(
                'addsome' => array(
                    'type' => 'literal',
                    'options' => array(
                        'route' => '/add',
                        'defaults' => array(
                            'controller' => 'News\Controller\NewsItem',
                            'action'     => 'add',
                        ),
                        'may_terminate' => true, 
                    ),
                ),
            ),
        ),
    ),
);


/*
    'router' => array(
        'routes' => array(
            'news' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/news[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'News\Controller\NewsItem',
                        'action'     => 'index',
                    ),
                ),
            ),
            'add news item' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/news/add',
                    'defaults' => array(
                        'controller' => 'News\Controller\NewsItem',
                        'action'     => 'add',
                    ),
                ),
            ),
        ),
    ),

*/

return $config;