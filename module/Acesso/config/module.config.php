<?php

namespace Acesso;

return array(
//    'module_layouts' => [
//        'Acesso' => 'layout/padrao',
//    ],
    'app-config-acesso' => [
        'free-routes' => [ 
            'home','acesso/login', 'acesso/logout'
        ],
        'auth' => [
            //tambem pode ser um array ex: [ 'modulo' => 'Pessoas', 'nome' => 'Pessoas' ],
            'entity' => 'Usuario',
            'campo_usuario' => 'usr_login',
            'campo_senha' => 'usr_senha', // deve ser SHA1
        ],
        'session' => [
            'owner-config' => [
                'nome' => 'acesso',
            ],
        ],
    ],
    'router' => array(
        'routes' => array(
            'acesso' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/acesso',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Acesso\Controller',
                        'controller'    => 'Acesso\Controller\Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller'    => 'Acesso\Controller\Index',
                                'action'        => 'login',
                            ),
                        ),
                    ),
                    'login' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/login',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller'    => 'Acesso\Controller\Authenticate',
                                'action'        => 'login',
                            ),
                        ),
                    ),
                    'logado' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/logado',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller'    => 'Acesso\Controller\Authenticate',
                                'action'        => 'logado',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/logout',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller'    => 'Acesso\Controller\Authenticate',
                                'action'        => 'logout',
                            ),
                        ),
                    ),
                    'nao-autorizado' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/401',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller'    => 'Acesso\Controller\Authenticate',
                                'action'        => 'nao-autorizado',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Acesso\Controller\Authenticate' => 'Acesso\Controller\AuthenticateController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
