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
            'News\Controller\NewsItem' => 'News\Controller\NewsController',
        ),
    ),
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
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);