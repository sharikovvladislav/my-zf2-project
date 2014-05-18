<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'news_entity' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(__DIR__ . '/../src/News/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'News\Entity' => 'news_entity',
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'News\Controller\Item' => 'News\Controller\NewsController',
            'News\Controller\Category' => 'News\Controller\CategoryController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'news' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/news',
                    'defaults' => array(
                        'controller' => 'News\Controller\Item',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true, 
                'child_routes' => array(
                    'full' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:id[/]',
                            'constraints' => array(
                                'id'     => '[1-9][0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'News\Controller\Item',
                                'action'     => 'full',
                            )
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'showMessages' => 'News\View\Helper\ShowMessages',
        ),
    ),
);

/*
                'child_routes' => array(
                    'full' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/full[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'News\Controller\Item',
                                'action'     => 'full',
                            )
                        ),
                        'may_terminate' => true,
                    ),
                ),
*/