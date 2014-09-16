<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of loginController
 *
 * @author sam
 */
class LoginController extends Controller {

    //put your code here

    private $_login;

    public function __construct() {
        parent::__construct();
        $this->_login = $this->LoadModelo('login');
    }

    public function index() {

        $this->view->setJs(array("novo"));
        $this->view->setCss(array("style"));
        $this->view->titulo = "Iniciar Sessão";
        if ($this->getInt('enviar') == 1) {

            $this->dados = $_POST;

            if (!$this->getSqlverifica('nome')) {
                $this->view->erro = "POrfavor Introduza um Noome Valido";
                $this->view->renderizar("index");
                exit;
            }
            if (!$this->getSqlverifica('senha')) {
                $this->view->erro = "POrfavor Introduza uma senha Valida";
                $this->view->renderizar("index");
                exit;
            }


            $data = array();
            $data['senha'] = $this->getTexto('senha');
            $data['nome'] = $this->getTexto('nome');

            $linha = $this->_login->login($data);
           
            if (!$linha) {
                $this->view->erro = "Usuario ou Palavra Passe Incorreta";
                $this->view->renderizar("index");
                exit;
            }
            
            Session::set("autenticado", true);
            Session::set('nivel', 'admin');
            Session::set('usuario', $data['nome']);
            Session::set('senha', $data['senha']);
            Session::set('nome',$linha->displayName);
            Session::set('time', time());
            $this->redirecionar();
        }

        $this->view->renderizar("index");
    }

    public function logof() {
        Session::destruir(array('autenticado','usuario', 'nome','nivel', 'time'));
        $this->_login->logof();
        $this->redirecionar("login");
    }

}
