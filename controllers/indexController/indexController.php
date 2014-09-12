<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of index
 *
 * @author sam
 */
class IndexController extends Controller {

    //put your code here
    private $usuarios;

    public function __construct() {
        parent::__construct();
        $this->grupos = $this->LoadModelo('grupo');
    }

    public function index() {
        $this->view->setJs(array("novo"));
        $this->view->title = "";

//        $this->view->setCss(array("css"));
        if (!Session::get('autenticado')) {
            $this->redirecionar('login');
        }
       
        $this->view->renderizar("index");
    }

}
