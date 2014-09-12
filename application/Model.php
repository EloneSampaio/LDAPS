<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



/**
 * Description of Model
 *
 * @author sam
 */
class Model{
    //put your code here
    protected $ldap;
    public function __construct() {
    
}

protected function getBibliotecas($lib){
        $caminho=ROOT."libs".DS.$lib.".php";
        if(is_readable($caminho)):
            require_once  $caminho;
        else:
            throw new Exception("Erro ao Ler Biblioteca");
        endif;
        
    }
   
    
    
}
