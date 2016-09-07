<?php
 
namespace Acesso\Service;
 
use Zend\Authentication\Adapter\AdapterInterface,
    Zend\Authentication\Result;
 
class AuthenticateAdapter extends \Nucleo\Service\GenericService implements AdapterInterface {
     
    private $configAuth;
    /**
     * Login de acesso no sistema
     * @var string $login
     */
    private $login;
    private $nomeCampoLogin;
     
    /**
     * Senha de acesso ao sistema
     * @var string $passwd
     */
    private $passwd;
    private $nomeCampoSenha;

    public function __construct(\ZeDb\DatabaseManager $em ) {
        $this->em = $em;
        $this->configAuth = $em->get('config')['app-config-acesso']['auth'];        
    }
 
    public function getLogin() {
        return strtoupper($this->login);
    }
 
    public function setLogin($login) {
        $this->login = $login;
    }
 
    public function getPasswd() {
        return $this->passwd;
    }
 
    public function setPasswd($passwd) {
        $this->passwd = $passwd;
    }
 
    /**
     * Realiza a autenticação do usuario
     * @return Result
     */
    public function authenticate() {
        $module = 'Acesso';
        $entity = $entity = $this->configAuth['entity'];
        if(is_array($this->configAuth['entity'])){
            $module = $this->configAuth['entity']['modulo'];
            $entity = $this->configAuth['entity']['nome'];
        }
        
        $srv_usuario = $this->getEntity($module, $entity);
        $this->nomeCampoLogin = implode('', explode(' ', ucwords(str_replace('_', ' ',$this->configAuth['campo_usuario']))));
        $this->nomeCampoSenha = implode('', explode(' ', ucwords(str_replace('_', ' ',$this->configAuth['campo_senha']))));
        
        $metodoBusca = 'getBy'.$this->nomeCampoLogin.'And'.$this->nomeCampoSenha;
        $user = $srv_usuario->$metodoBusca($this->getLogin(),$this->getPasswd());
        
        if( is_object($user) ){
            $user = $user->toArray();
            unset($user[$this->configAuth['campo_senha']]);
            return new Result(Result::SUCCESS, array('usuario'=>$user), array('OK'));
        }
        else {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, array('user'=>$user));
        }
    }    
}
 
