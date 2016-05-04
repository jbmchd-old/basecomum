<?php

namespace Acesso\Controller;

use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Nucleo\Controller\ControllerGenerico;

class AuthenticateController extends ControllerGenerico {

    public function loginAction(){
        $request = $this->getRequest();
        $retorno = [];
        
        if ( $request->isPost() ){
            $dados_form = $request->getPost()->toArray();
            /* faz a validação e retorna o resultado para a tela de login */
            $dados = $this->validaUsuarioSenha($dados_form);
            echo '<pre>';
            print_r($_SESSION);
            die();
            return $this->redirect()->toRoute('home');
        }
    }

    public function logoutAction($redirect = true){
        $autenticaService = $this->app()->getService('Authenticate');
        $autenticaService->destroiSessao();
        if($redirect){
            return $this->redirect()->toUrl('/acesso/login');
        }
            
    }
    
    private function validaUsuarioSenha($dados_form){
        $autenticaService = $this->app()->getService('Authenticate');
        $auth = $autenticaService->validaAutenticacao(strtoupper($dados_form['username']), $dados_form['password']);
        
        if ( is_object($auth) ){
            $dados['message'] = 'Erro desconhecido.';
        }

        if ( isset($auth['erro']) ){
            $dados['message'] = 'Usuário/senha inválidos, verifique.';
        } else {
            $dados = $auth;
        }
        
        return $dados;
    }
    
}
