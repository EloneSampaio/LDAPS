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
        $this->view->data = $this->getBibliotecas('convertData');
    }

    public function index() {
        $this->view->setJs(array("novo"));
        $this->view->link = "usuario/novo";


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

        $this->getBibliotecas('mpdf/mpdf');
        define("_MPDF_PATH", ROOT . DS . "libs" . DS . "mpdf" . DS);
        $this->pdf = new mPDF();
        $this->pdf->allow_charset_conversion = true;
        $this->pdf->charset_in = 'UTF-8';
        $this->pdf->SetDisplayMode('fullpage');

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
                $this->view->erro = "Ocorreu um erro na criação do Utilizador";
                $this->view->renderizar("novo");
                exit;
            }
            /*
             * @var $texto-> variavel para guardar informação do utilizador
             * @var rodape-> variavel para guardar informação do rodape
             *   */

            $texto = "<div id='linha'>" .
                    "<hr>" .
                    "<table border='2'><tr><th width='160'>Login</th><th width='160'>Senha</th><th width='160'>Data de Expiração</th></tr>" .
                    "<tbody>" .
                    "<tr>"
                    . "<td class='texto1'>" . $data['login'] . "</td>" .
                    "<td  class='texto1'>" . $data['senha'] . "</td>" .
                    "<td   class='texto1'>" . $data['data'] . "</td>" .
                    "</tr></tbody></table>" .
                    "</div> <br /><br /><br /><br /><br /><br />";
            $stylesheet = file_get_contents(ROOT . "views" . DS . "layout" . DS . DEFAULT_LAYOUT . DS . "bootstrap" . DS . "css" . DS . "relatorio.css");

            $this->pdf->WriteHTML($stylesheet, 1);
            $this->pdf->WriteHTML($texto);
            $this->pdf->SetFooter('{DATE j/m/Y } |ZAP/contactos:/helpdesk@zap.co.ao (credencias)');

            $this->view->mensagem = "Novo Utilizador criado com Sucesso";

            $this->pdf->Output($data['login'], "I");
            $this->view->renderizar("novo");
        }

        $this->view->renderizar("novo");
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

        $this->redirecionar('usuario');
    }

    public function editarsenha() {
        if ($this->getInt("guardar") == 1) {
            $this->getBibliotecas('mpdf/mpdf');
            define("_MPDF_PATH", ROOT . DS . "libs" . DS . "mpdf" . DS);
            $this->pdf = new mPDF();
            $this->pdf->allow_charset_conversion = true;
            $this->pdf->charset_in = 'UTF-8';
            $this->pdf->SetDisplayMode('fullpage');

            $data = array();
            $data['usuario'] = $this->getTexto('usuario');
            $data['senha'] = $this->geraSenha(6, TRUE, TRUE);
            $t = $this->usuarios->editar_senha($data);
            $d = $this->usuarios->listarinfo($data['usuario']);

            /*
             * @var $texto-> variavel para guardar informação do utilizador
             * @var rodape-> variavel para guardar informação do rodape
             *   */

            $texto = "<div id='linha'>" .
                    "<hr>" .
                    "<table border='2'><tr><th width='160'>Login</th><th width='160'>Senha</th><th width='160'>Data de Expiração</th></tr>" .
                    "<tbody>" .
                    "<tr>"
                    . "<td class='texto1'>" . $d->samaccountname . "</td>" .
                    "<td  class='texto1'>" . $data['senha'] . "</td>" .
                    "<td   class='texto1'>" . convert_AD_date($d->accountexpires) . "</td>" .
                    "</tr></tbody></table>" .
                    "</div> <br /><br /><br /><br /><br /><br />";
            $stylesheet = file_get_contents(ROOT . "views" . DS . "layout" . DS . DEFAULT_LAYOUT . DS . "bootstrap" . DS . "css" . DS . "relatorio.css");

            $this->pdf->WriteHTML($stylesheet, 1);
            $this->pdf->WriteHTML($texto);
            $this->pdf->SetFooter('{DATE j/m/Y } |ZAP/contactos:/helpdesk@zap.co.ao (credencias)');
            $this->pdf->Output($d->samaccountname, "I");
            $this->view->mensagem = "Alteração Efectuada com sucesso";
        }

        $this->redirecionar('usuario');
    }

    function dados($id) {
        $this->view->data = $this->getBibliotecas('convertData');
        $this->view->usuario = $this->usuarios->listarinfo($id);

        if ($this->view->usuario->accountexpires < $this->convertTimestamp(date("d-m-Y H:i:s"))) {
            $this->view->status = "Conta Expirada";
        }
          

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
        $this->view->usuario = $this->usuarios->listarinfo($id);
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
