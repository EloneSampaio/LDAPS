<?php

//le o arquivo de configuração
require 'config/config.php';

//le o arquivo autoload, que é responsavel por ler todas as nossas classes
require 'config/autoload.php';


try {
    Session::iniciar();

    Bootstrap::run(new Request());
} catch (Exception $ex) {
    echo $ex->getMessage();
}


        
