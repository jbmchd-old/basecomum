<?php

namespace Acesso\Service;

use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;

use  Nucleo\Service\GenericService;

class Authenticate extends GenericService {
    
    private $configSession;
    
    /**
     * Nome da sessão a ser criada
     * 
     * @var string $nomeSession
     */
    private $nomeSession = "DPC";

    /**
     * Objeto que contem os metodo para trabalhar com sessao
     * 
     * @var SessionStorage $sessionStorage
     */
    private $sessionStorage;

    /**
     * Objeto que contem os metodos de autenticaca
     * 
     * @var \Acesso\Authenticate\AuthenticateAdapter $authenticateAdapter
     */
    private $authenticateAdapter;

    /**
     * Servico de authenticacao do ZF2
     * 
     * @var AuthenticationService $authenticateService
     */
    private $authenticateService;

    /**
     *
     * @param DatabaseManager $em        	
     */
    public function __construct(\ZeDb\DatabaseManager $em) {
        parent::__construct($em);
        $this->configSession = $em->get('config')['modules-config']['acesso']['session'];
        $this->nomeSession = $this->configSession['owner-config']['nome'];

        $this->setAuthenticateAdapter( new \Acesso\Service\AuthenticateAdapter($em) );
        $this->setAuthenticateService(new AuthenticationService());
        $this->setSessionStorage(new SessionStorage($this->nomeSession));
        $this->getAuthenticateService()->setStorage($this->getSessionStorage());
    }

    public function getNomeSession() {
        return $this->nomeSession;
    }

    public function setNomeSession($nomeSession) {
        $this->nomeSession = $nomeSession;
    }

    public function getSessionStorage() {
        return $this->sessionStorage;
    }

    public function setSessionStorage(SessionStorage $sessionStorage) {
        $this->sessionStorage = $sessionStorage;
    }

    public function getAuthenticateAdapter() {
        return $this->authenticateAdapter;
    }

    public function setAuthenticateAdapter(\Acesso\Service\AuthenticateAdapter $authenticateAdapter) {
        $this->authenticateAdapter = $authenticateAdapter;
    }

    public function getAuthenticateService() {
        return $this->authenticateService;
    }

    public function setAuthenticateService(AuthenticationService $authenticateService) {
        $this->authenticateService = $authenticateService;
    }

    /**
     * @param string $login        	
     * @param string $passwd        	
     * @return array
     */
    public function validaAutenticacao($login, $passwd) {
        $this->authenticateAdapter->setLogin($login);
        $this->authenticateAdapter->setPasswd($passwd);

        $result = $this->authenticateService->authenticate($this->authenticateAdapter);
        
        if ($result->isValid()) {
            $identity = $result->getIdentity()['user'];
            $this->escreveSessao($identity);
            return $identity;
        } else {
            $this->escreveSessao([]);
            return array(
                'erro' => $result->getMessages()
            );
        }
    }

    /**
     * Escreve os dados recebidos por parametro na sessao
     * 
     * @param string $contents        	
     */
    public function escreveSessao($contents) {
        $this->sessionStorage->write($contents, null);
    }

    /**
     * Destroi a sessao
     */
    public function destroiSessao() {
        $storage = $this->getSessionStorage();
        $storage->clear();
        $this->authenticateService->setStorage($storage);
    }

    /**
     * Retorna o conteudo escrito na sessao quando o usuairo faz a autenticacao
     * 
     * @return type
     */
    public function getUserAuth() {
        $this->authenticateService->setStorage($this->getSessionStorage());
        return $this->authenticateService->getIdentity();
    }

}

?>