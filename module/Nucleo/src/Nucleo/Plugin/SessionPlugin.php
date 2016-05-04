<?php


namespace Nucleo\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Nucleo\Service\GenericSession;

class SessionPlugin extends AbstractPlugin {
    
    private $generic_session = null;
    
    public function __construct() {
        $this->generic_session = new GenericSession();   
    }
    
    public function __call($method_name, $arguments) {
        if(method_exists($this, $method_name)){
            return call_user_func_array([$this, $method_name], $arguments);
        } else {
            return call_user_func_array([$this->generic_session, $method_name], $arguments);
        }
    }
} 