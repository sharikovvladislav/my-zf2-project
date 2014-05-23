<?php

return array(
    'bjyauthorize' => array(
        // set the 'guest' role as default (must be defined in a role provider)
        'default_role' => 'guest',
        'authenticated_role' => 'user',
        /* this module uses a meta-role that inherits from any roles that should
         * be applied to the active user. the identity provider tells us which
         * roles the "identity role" should inherit from.
         *
         * for ZfcUser, this will be your default identity provider
         */
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
            
        /* If you only have a default role and an authenticated role, you can
         * use the 'AuthenticationIdentityProvider' to allow/restrict access
         * with the guards based on the state 'logged in' and 'not logged in'.
         *
         * 'default_role'       => 'guest',         // not authenticated
         * 'authenticated_role' => 'user',          // authenticated
         * 'identity_provider'  => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
         */

        /* role providers simply provide a list of roles that should be inserted
         * into the Zend\Acl instance. the module comes with two providers, one
         * to specify roles in a config file and one to load roles using a
         * Zend\Db adapter.
         */
        'role_providers' => array(

            /* here, 'guest' and 'user are defined as top-level roles, with
             * 'admin' inheriting from user
             */
            'BjyAuthorize\Provider\Role\Config' => array(
                'guest' => array('children' => array(
                    'user' => array(),
                )),
            ),


        ),
        // resource providers provide a list of resources that will be tracked
        // in the ACL. like roles, they can be hierarchical
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'pants' => array(),
            ),
        ),

        /* rules can be specified here with the format:
         * array(roles (array), resource, [privilege (array|string), assertion])
         * assertions will be loaded using the service manager and must implement
         * Zend\Acl\Assertion\AssertionInterface.
         * *if you use assertions, define them using the service manager!*
         */
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    // allow guests and users (and admins, through inheritance)
                    // the "wear" privilege on the resource "pants"
                    array(array('guest', 'user'), 'pants', 'wear')
                ),

                // Don't mix allow/deny rules if you are using role inheritance.
                // There are some weird bugs.
                'deny' => array(
                    // ...
                ),
            ),
        ),

        /* Currently, only controller and route guards exist
         *
         * Consider enabling either the controller or the route guard depending on your needs.
         */
        'guards' => array(
            /* If this guard is specified here (i.e. it is enabled), it will block
            * access to all controllers and actions unless they are specified here.
            * You may omit the 'action' index to allow access to the entire controller
            */
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'zfcuser',
                    'action' => array('index'),
                    'roles' => array('guest'),
                ),
                array(
                    'controller' => 'zfcuser',
                    'action' => array('login', 'authenticate', 'register'),
                    'roles' => array('guest'),
                ),
                array(
                    'controller' => 'zfcuser',
                    'action' => array('logout'),
                    'roles' => array('user'),
                ),
                array(
                    'controller' => 'Application\Controller\Index', 
                    'roles' => array()
                ),
                array(
                    'controller' => 'ZfcAdmin\Controller\AdminController', 
                    'roles' => array('guest'),
                ),
                array(
                    'controller' => 'ZfcAdmin\Controller\AdminController', 
                    'action' => array('login'),
                    'roles' => array('guest'),
                ),
                array(
                    'controller' => 'DoctrineORMModule\Yuml\YumlController', 
                    'roles' => array('user'),
                ),
                array(
                    'controller' => 'News\Controller\Item', 
                    'action' => array('full', 'index', 'category'),
                    'roles' => array('guest'),
                ),
                array(
                    'controller' => 'News\Controller\Item', 
                    'action' => array('add', 'list', 'delete', 'edit'),
                    'roles' => array('user'),
                ),
                array(
                    'controller' => 'News\Controller\Category', 
                    'roles' => array('user'),
                ),
            ),
        ),
    ),
);