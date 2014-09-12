<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login_Model
 *
 * @author sam
 */
class loginModel extends Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }

    public function login($dados) {

        $this->getBibliotecas('src' . DS . "adLDAP");
        $adldap = new adLDAP();
        $t = $adldap->authenticate($dados['nome'], $dados['senha']);
        return $t;
    }
    
//fim

     public function logof() {
         $adldap = new adLDAP();
         $t = $adldap->close();
        return $t;
    }
   
    
    

}
