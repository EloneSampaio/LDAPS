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
class UsuarioController extends Controller {

    //put your code here
    private $usuarios;
    private $grupos;

    public function __construct() {
        parent::__construct();
        $this->usuarios = $this->LoadModelo('usuario');
        $this->grupos = $this->LoadModelo('grupo');
        $this->view->ldap = $this->getLdap('adLDAP', 'index');
    }

    public function index() {
        $this->view->setJs(array("novo"));
        $this->view->link = "usuario/novo";
        $this->view->data = $this->getBibliotecas('convertData');
        
        $this->view->titulo = "Usuarios";
//        $this->view->setCss(array("css"));
        if (!Session::get('autenticado')) {
            $this->redirecionar('login');
        }


        $this->view->usuario = $this->grupos->menbros_grupo();
        $this->view->ldap = $this->getLdap('adLDAP', 'index');
        $this->view->renderizar("index");
    }

    public function novo() {
      

        if ($this->getInt("guardar") == 1) {
            $this->view->dados = $_POST;

//
//            if (!$this->alphaNumeric('primeiro')) {
//                $this->view->erro = "O Campo Nome é Obrigatorio preencha-o";
//                $this->view->renderizar("novo");
//                exit;
//            }
//            if (!$this->alphaNumeric('ultimo')) {
//                $this->view->erro = "O Campo Nome é Obrigatorio preencha-o";
//                $this->view->renderizar("novo");
//                exit;
//            }
//
//
//            if (!$this->alphaNumeric('empresa')) {
//                $this->view->erro = "O Campo empresa é Obrigatorio preencha-o";
//                $this->view->renderizar("novo");
//                exit;
//            }

//            if (!$this->getInt('telefone')) {
//                $this->view->erro = "O Campo telefone é Obrigatorio preencha-o";
//                $this->view->renderizar("novo");
//                exit;
//            }

            $data = array();
            $data['primeiro'] = $_POST['primeiro'];
            $data['ultimo'] = $_POST['ultimo'];
            $data['empresa'] = $_POST['empresa'];
            $data['telefone'] = $_POST['telefone'];
            $data['data'] = $_POST['data'];
            $data['senha'] = $this->geraSenha(6, TRUE, TRUE);
            $data['login'] = $_POST['primeiro'] . "." . $_POST['ultimo'];
            $v = $this->usuarios->novo_usuario($data);
            $this->usuarios->add_grupo($data['login']);

            if (!$v) {
                $this->view->erro = "Não Possivel criar a usuario";
                $this->view->renderizar("novo");
                exit;
            }
            $this->view->mensagem = "Novo usuario criado com Sucesso";
        }

        $this->view->renderizar("novo");
    }

    public function editar($usuario) {
        $this->view->usuario = $usuario;
        $this->view->renderizar("editar");
    }

    public function editardata() {
        if ($this->getInt("guardar") == 1) {
            if (!$this->getTexto('data')) {
                $this->view->erro = "O Campo data é Obrigatorio preencha-o";
                $this->view->renderizar("update");
                exit;
            }
            $data = array();
            $data['login'] = $this->getTexto('login');
            $data['usuario'] = $this->getTexto('usuario');
            $data['data'] = $_POST['data'];
            $t = $this->usuarios->editar_data($data);
            $this->view->mensagem = "Novo usuario criado com Sucesso";
            $this->redirecionar("usuario");
        }

        $this->view->renderizar('update');
    }

    public function editarsenha() {
        if ($this->getInt("guardar") == 1) {
            if (!$this->getTexto('senha')) {
                $this->view->erro = "O Campo data é Obrigatorio preencha-o";
                $this->view->renderizar("update");
                exit;
            }
            $data = array();
            $data['usuario'] = $this->getTexto('usuario');
            $data['senha'] = $this->getTexto('senha');
            $t = $this->usuarios->editar_senha($data);
            $this->view->mensagem = "Novo usuario criado com Sucesso";
            $this->redirecionar("usuario");
        }

        $this->view->renderizar('update');
    }

    public function editarlogin() {
        if ($this->getInt("guardar") == 1) {
            if (!$this->getTexto('login')) {
                $this->view->erro = "O Campo login  é Obrigatorio preencha-o";
                $this->view->renderizar("update");
                exit;
            }
            $data = array();
            $data['usuario'] = $this->getTexto('usuario');
            $data['login'] = $this->getTexto('login');
            $t = $this->usuarios->editar_login($data);
            $this->view->mensagem = "editado com Sucesso";
            $this->redirecionar("usuario");
        }

        $this->view->renderizar('update');
    }

    function dados($id) {
        $this->view->data = $this->getBibliotecas('convertData');
        $this->view->setJs(array("novo"));
        
     
        $this->view->usuario = $this->usuarios->listarinfo($id);
//           if($t=convert_AD_date($this->view->usuario->accountexpires) < $this->convertTimestamp(date("Y-m-d H:i:s")) ){
//               print convert_AD_date($this->view->usuario->accountexpires); 
//               //print $this->convertTimestamp(date("Y-m-d H:i:s"));
//               exit;
//               $this->view->status="Conta Expirada";
//        }
        $this->view->renderizar("ver_dados");
    }

    public function deletar() {
        Session::nivelRestrito(array("usuario"));
        if (!$this->getSqlverifica('id')) {
            $this->view->erro = "Erro ao apagar usuario";
            $this->redirecionar("usuario");
        }

        if ($this->usuarios->apagar_usuario($this->getSqlverifica('id'))) {

            $this->view->mensagem = "Usuario apagado com sucesso";
//        
        } else {
            $this->view->erro = "Erro ao apagar usuario";
        }
    }

    public function retirar() {
        Session::nivelRestrito(array("usuario"));
        if (!$this->getSqlverifica('id')) {
            $this->view->erro = "usuario não existe";
            $this->redirecionar("usuario");
        }


        if ($this->usuarios->retirar_usuario($this->getSqlverifica('id'))) {

            $this->view->mensagem = "Operação feita com sucesso";
            exit;
        } else {
            $this->view->erro = "Erro ao retirar usuario";
        }
    }

    public function update($id) {
        $this->view->usuario = $id;
        $this->view->titulo = "Escolha uma das opções que pretendes alterar";
        $this->view->setJs(array("novo"));

        $this->view->renderizar('update');
    }

    public function pesquisar() {
        $this->view->data = $this->getBibliotecas('convertData');
        $this->view->usuario = $this->usuarios->pesquisar($_GET['id']);
        $this->view->renderizar("pesquisar");
    }

}
