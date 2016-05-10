<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Acesso;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;

class Module
{
    private $config;
    
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $this->config = $e->getApplication()->getConfig()['modules-config']['acesso'];
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

    //====================================================

    /**
     * Método que é executado quando o modulo é carregado
     * Este método esta atribuindo um evento para verificar se o cara está logado no sistema, validaAutenticacao
     * @param \Zend\ModuleManager\ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager) {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        
        $sharedEvents->attach("Zend\Mvc\Controller\AbstractActionController", 
                MvcEvent::EVENT_DISPATCH,
                array($this,'validaAutenticacao'),100);
    }
    
    /**
     * Método que verifica se o usuario está logado
     * @param type $e
     */
    public function validaAutenticacao(MvcEvent $e ){
        
        $configSession = $this->config['session'];
        
        $authenticateService = new \Zend\Authentication\AuthenticationService();
        $authenticateService->setStorage( new \Zend\Authentication\Storage\Session($configSession['owner-config']['nome']) );
        
        $controller = $e->getTarget();

        $rotaAcessada = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();
        /** Liberando rota para não precisar de autenticação */
        $rota_livre = in_array($rotaAcessada, $this->config['free-routes']);
        
        if ( $rota_livre ){ 
            return true; 
        } else if ( ! $authenticateService->hasIdentity()  ){
            return $controller->redirect()->toRoute("acesso/logout");
        } else {
            $user = $authenticateService->getIdentity();
            $this->confirmaAutorizacao($controller, $user);
        }

    }
    
    private function confirmaAutorizacao($controller, $user_name){
        return true;
        $controlador = $controller->params()->fromRoute('controller');
        $action      = $controller->params()->fromRoute('action');
        
        $autorizacao = new \Acesso\Service\Autorizacao( $em );  
        $esta_autorizado = $autorizacao->temAcesso($controlador, $action, $user);
        if ( ! $esta_autorizado ) {
            return $controller->redirect()->toRoute("acesso/nao-autorizado", array('controlador' => $controlador, 'acao' => $action));
        }
        return true;
    }
    
}

