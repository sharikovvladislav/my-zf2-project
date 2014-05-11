<?php
$config = array(
    'view_manager' => array(
        'template_path_stack' => array(
            'zfcuser' => __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'zfcuser' => 'MyZfcUser\Controller\UserController',
        ),
    )
);

//$config['router']['routes']['zfcuser']['options']['route'] = '/admin/login';

$config['router']['routes']['zfcuser']['child_routes']['trailing_slash'] = array(
    'type' => 'Literal',
    'options' => array(
        'route' => '/',
        'defaults' => array(
            'controller' => 'zfcuser',
            'action'     => 'index',
        ),
    ),
);
return $config;