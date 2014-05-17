<?php

namespace MyZfcAdmin;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'selectLayoutBasedOnRoute'));
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(            
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function selectLayoutBasedOnRoute(MvcEvent $e)
    {
        $app = $e->getParam('application');
        $sm = $app->getServiceManager();
        $config = $sm->get('config');

        if (false === $config['myzfcadmin']['use_admin_layout']) {
            return;
        }

        $match = $e->getRouteMatch();
        $controller = $e->getTarget();

        if (!$match instanceof \Zend\Mvc\Router\RouteMatch
            || 0 !== strpos($match->getMatchedRouteName(), 'zfcadmin')
            || $controller->getEvent()->getResult()->terminate()
        ) {
            return;
        }

        if ($controller instanceof \MyZfcAdmin\Controller\AdminController
            && $match->getParam('action') == 'login'
        ) {
            // if you'd rather just set the layout in your controller action just return here
            // return;
            // otherwise, use the configured login layout ..
            $layout = $config['myzfcadmin']['login_layout_template'];
        } else {
            $layout = $config['myzfcadmin']['admin_layout_template'];
        }

        $controller->layout($layout);                
    }
}
