<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usuariogoriaController
 *
 * @author sam
 */
class GrupoController extends Controller {

    //put your code here
    private $grupos;

    public function __construct() {
        parent::__construct();
        $this->grupos = $this->LoadModelo('grupo');
    }

    public function index() {

        $this->view->setJs(array("novo"));
        $this->view->titulo = "Grupos";
        if (!Session::get('autenticado')) {
            $this->redirecionar('login');
        }

        $this->view->grupo = $this->grupos->listarAll();
        $this->view->ldap = $this->getLdap('adLDAP', 'index');
        $this->view->renderizar("index");
    }

    public function novo() {


//        $this->view->setJs(array("novo"));
        if ($this->getInt("guardar") == 1) {
            $this->view->dados = $_POST;

            if (!$this->getTexto('nome')) {
                $this->view->erro = "O Campo nome é Obrigatorio preencha-o";
                $this->view->renderizar("novo");
                exit;
            }


            if (!$this->getTexto('descricao')) {
                $this->view->erro = "O Campo descrição é Obrigatorio preencha-o";
                $this->view->renderizar("novo");
                exit;
            }

            $data = array();
            $data['nome'] = $this->getTexto('nome');
            $data['descricao'] = $this->getTexto('descricao');
            $cat = $this->grupos->verificar_grupo($data['nome']);
            if ($cat) {
                $this->view->mensagem = "Já existe um usuario com esse nome ";
                $this->view->renderizar("novo");
                exit;
            }


            $this->grupos->novo_grupo($data);
            if (!$this->grupos->verificar_grupo($data['nome'])) {
                $this->view->erro = "Não Possivel criar o grupo";
                $this->view->renderizar("novo");
                exit;
            }
            $this->view->mensagem = "Novo grupo criado com Sucesso";
            $this->redirecionar("grupo");
        }

        $this->view->renderizar("novo");
    }

    public function apagar($id) {
        Session::nivelRestrito(array("usuario"));
        if (!$this->filtraInt($id)) {
            $this->redirecionar("usuario");
        }

        if (!$this->grupos->listar_id($this->filtraInt($id))) {
            $this->redirecionar("usuario");
        }
        $this->grupos->apagar_usuario($this->filtraInt($id));
        $this->redirecionar("usuario");
    }

    public function grupo_novo() {

        if ($this->getInt("guardar") == 1) {
            $this->view->dados = $_POST;


            if (!$this->getTexto('grupo')) {
                $this->view->erro = "O Campo nome é Obrigatorio preencha-o";
                $this->view->renderizar("grupo");
                exit;
            }

            if (!$this->getTexto('nome')) {
                $this->view->erro = "O Campo nome é Obrigatorio preencha-o";
                $this->view->renderizar("grupo");
                exit;
            }

            $t = $this->grupos->lista($this->getTexto('grupo'), $this->getTexto('nome'));
          
            if ($t) {
                $this->view->mensagem = "Usuario Adicionado com sucesso";
            }

            $this->redirecionar("usuario");
        }

        $this->redirecionar("grupo");
    }

}
