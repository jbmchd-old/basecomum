<?php

namespace Nucleo\ViewHelper;

use Zend\View\Helper\AbstractHelper;
use Nucleo\Service\GenericSession;

class SessionViewHelper extends AbstractHelper {
    private $sm;
    private $generic_session = null;
    
    public function __construct(\Zend\ServiceManager\ServiceManager $sm) {
        $this->sm = $sm->get('Nucleo\ServiceManager');
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