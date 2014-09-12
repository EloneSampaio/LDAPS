
<?php

function autoload($class) {
   
 //se o ficheiro não existe dentro  APP_PATH a pasta [seta o arquivo dentro de config/config.php]
    if (file_exists(APP_PATH . $class . ".php")) {
     
        require APP_PATH . $class . ".php";
    } else {
        exit('O arquivo ' . $class . '.php não existe no servidor.');
    }
}

spl_autoload_register("autoload");
?>