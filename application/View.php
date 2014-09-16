<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//require_once URL."libs".DS."libs".DS."Smarty".DS."libs".DS."Smarty.class.php.php";
/**
 * Description of View
 *
 * @author sam
 */
class View {

    private $_controller;
    private $_js;
    private $_css;

//put your code here
    function __construct(Request $pedido) {
//parent::__construct();
        $this->_controller = $pedido->getController();
        $this->_js = array();
        $this->_css = array();
    }

    public function renderizar($nome, $item = FALSE) {

        $menu = array(
            array(
                "id" => "usuario",
                "titulo" => "Usuarios",
                "link" => URL . "usuario"
            ),
//            array(
//                "id" => "Grupos",
//                "titulo" => "Grupos",
//                "link" => URL . "grupo"
//            )
        );
        if (Session::get('autenticado')) {
            $menu[] = array(
                "id" => "login",
                "titulo" => "Terminar sessão",
                "link" => URL . "login/logof"
            );
        } else {
            $menu[] = array(
                "id" => "login",
                "titulo" => "Inciar Sessão",
                "link" => URL . "login"
            );
        }


        $js = array();
        if (count($this->_js)) {
            $js = $this->_js;
        }
        $css = array();
        if (count($this->_css)) {
            $css = $this->_css;
        }


        $_layoutParam = array(
            "caminho_css" => URL . "views/layout" . DS . DEFAULT_LAYOUT . "/bootstrap" . DS . "css/",
            "caminho_js" => URL . "views/layout" . DS . DEFAULT_LAYOUT . "/bootstrap" . DS . "js/",
            "caminho_images" => URL . "public" . DS . "img".DS,
            "caminho_vendores" => URL . "views/layout" . DS . DEFAULT_LAYOUT . "/vendors/",
            "caminho_assets" => URL . "views/layout" . DS . DEFAULT_LAYOUT . "/assets/",
            "menu" => $menu,
            "js" => $js,
            "css" => $css,
        );


        $caminho = ROOT . "views" . DS . $this->_controller . DS . $nome . ".phtml"; //ou phtml
        $header = ROOT . "views" . DS . "layout" . DS . DEFAULT_LAYOUT . DS . "header.php";
        $footer = ROOT . "views" . DS . "layout" . DS . DEFAULT_LAYOUT . DS . "footer.php";
        if (is_readable($caminho)) {
            require $header;
//$this->assign("conteudo",$caminho);
            require $caminho;
//require $footer;
        } else {

            throw new Exception("erro da view");
        }
    }

    public function setJs(array $js) {
        if (is_array($js) && count($js)) {
            for ($i = 0; $i < count($js); $i++) {
                $this->_js[] = URL . "views/" . $this->_controller . "/js/" . $js[$i] . ".js";
            }
        } else {
            throw new Exception("Erro de Javascript");
        }
    }

    public function setCss(array $css) {
        if (is_array($css) && count($css)) {
            for ($i = 0; $i < count($css); $i++) {
                $this->_css[] = URL . "views/" . $this->_controller . "/css/" . $css[$i] . ".css";
            }
        } else {
            throw new Exception("Erro de Css");
        }
    }

}
