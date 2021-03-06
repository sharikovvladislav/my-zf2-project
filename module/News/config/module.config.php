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
        ),
        'factories'     => array(
            'News\Controller\Item' => 'News\Factory\NewsControllerFactory',
            'News\Controller\Category' => 'News\Factory\CategoryControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'news' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/news',
                    'defaults' => array(
                        'controller' => 'News\Controller\Item',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'pagination' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/page-:page',
                            'constraints' => array(
                                'page'     => '[1-9][0-9]*',
                            ),
                            'defaults' => array(
                                'page'      => 1,
                            )
                        ),
                        'may_terminate' => true,
                    ),
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
                    'category' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:category',
                            'constraints' => array(
                                'category'     => '[a-z]+',
                            ),
                            'defaults' => array(
                                'controller' => 'News\Controller\Item',
                                'action'     => 'category',
                            )
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'pagination' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/page-:page',
                                    'constraints' => array(
                                        'page'     => '[1-9][0-9]*',
                                    ),
                                    'defaults' => array(
                                        'page'      => 1,
                                    )
                                ),
                                'may_terminate' => true,
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'template_map' => array(
            'pagination_control' => __DIR__ . '/../view/news/partial/paginationControls.phtml',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'showMessages' => 'News\View\Helper\ShowMessages',
        ),
    ),
);