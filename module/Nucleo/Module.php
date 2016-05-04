<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonAdmin for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Nucleo;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;

class Module {

    private $config;

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $this->config = $e->getApplication()->getConfig();
        $this->initGlobalSession();
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Nucleo\ServiceManager' => function( $sm ) {
                    return new \Nucleo\Service\ServiceManager($sm->get('ZeDbManager'));
                },
            )
        );
    }

    public function getViewHelperConfig() {
        return array(
            'factories' => array(
                'app' => function($sm) {
                    return new ViewHelper\AppViewHelper($sm->getServiceLocator());
                },
                'sessao' => function($sm) {
                    return new ViewHelper\SessionViewHelper($sm->getServiceLocator());
                },
            )
        );
    }

    private function initGlobalSession() {

        if (isset($this->config['modules-config']['nucleo']['session'])) {
            $session_config = $this->config['modules-config']['nucleo']['session'];
            $owner_config = $session_config['owner-config'];

            //seta as opções
            $sessionConfig = new SessionConfig();
            $sessionConfig->setOptions($session_config['zend-config']);

            //cria o gerenciador de sessões e inicia a sessão
            $sessionManager = new SessionManager($sessionConfig);
            $sessionManager->start();

            //armazena a sessão num conteiner para acessos
            Container::setDefaultManager($sessionManager);
        }
    }

}
