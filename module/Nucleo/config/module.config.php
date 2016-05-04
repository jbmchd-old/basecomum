<?php

namespace Nucleo;

return array(
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Nucleo\Controller\Nucleo' => 'Nucleo\Controller\NucleoController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'app' => 'Nucleo\Plugin\AppPlugin',
            'sessao' => 'Nucleo\Plugin\SessionPlugin',
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
        
    ),
);
