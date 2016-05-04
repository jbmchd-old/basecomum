<?php

namespace Acesso\Service;

use ZeDb\DatabaseManager;

/**
 * Classe responsável por verificar se o usario tem acesso a uma funcionalidades, funciona de maneira similar a ACL do zend mas pouca recurso do sistema
 * @author João Paulo Constantino <joaopauloctga@gmail.com>
 * @version 1
 */
class Autorizacao {
    
    /**
     * Id do suario que está logado no sistema
     * @var int $usuario
     */
    private $usuario;
    
    /**
     * Controlador que está sendo requisitado
     * @var string
     */
    private $controller;
    
    /**
     * Action que está sendo requisitada
     * @var string 
     */
    private $action;
    /**
     * Gerenciador de entidades
     * @var DatabaseManager $em
     */
    private $em;
    /**
     * Contem o nome da entidade que contem as informacoes do acesso do usuario
     * @var string
     */
    private $entityAcesso = "Acesso\Entity\DimUsuario";
    
    public function __construct(DatabaseManager $em) {
        $this->em = $em;
    }
    
    /**
     * Verifica se o usuario tem acesso a rota requisitada
     * @param string $rotaName
     * @param int $usuario
     * @return boolean
     */
    public function temAcesso( $controller, $action, $usuario ){
        return true;
    }
    
    
    
}

?>