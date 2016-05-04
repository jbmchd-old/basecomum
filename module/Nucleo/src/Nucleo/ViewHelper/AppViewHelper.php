<?php

namespace Nucleo\ViewHelper;

use Zend\View\Helper\AbstractHelper;
use Nucleo\ViewHelper\Session;
use Nucleo\Service\Webservice;
use Nucleo\Service\ServiceManager;

class AppViewHelper extends AbstractHelper{
    
    private $sm;
    private $sessao;
    protected $sessao_usuario = [];
    protected $webservice;
    
    public function __construct(\Zend\ServiceManager\ServiceManager $sm) {
        $this->sm = $sm->get('Nucleo\ServiceManager');
        $this->sessao = new Session($sm);
        $this->webservice = new Webservice();
        
        $this->sessao_usuario = $this->sessao->getUserInfo();
    }
    
    public function modalAlerta($options, $seletor=null){
        $options = json_encode($options);
        
        return "<script> $(function(){ $('$seletor').alert($options) });</script>";
    }
    
}
