<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controller
 *
 * @author sam
 */
abstract class Controller {

//put your code here

    protected $view;
    protected $acl;

    public function __construct() {

        $this->view = new View(new Request);
    }

    protected function LoadModelo($modelo) {
        $modelo = $modelo . "Model";
        $caminho = ROOT . "models" . DS . $modelo . ".php";
        if (is_readable($caminho)):
            require $caminho;
            $modelo = new $modelo;
            return $modelo;
        else :
            throw new Exception("Erro No Modelo");
        endif;
    }

    protected function getBibliotecas($lib) {
        $caminho = ROOT . "libs" . DS . $lib . ".php";
        if (is_readable($caminho)):
            include_once $caminho;
        else:
            throw new Exception("Erro ao Ler Biblioteca");
        endif;
    }

    protected function getTexto($chave) {
        if (isset($_POST[$chave]) && !empty($_POST[$chave])):
            $_POST[$chave] = htmlspecialchars($_POST[$chave], ENT_QUOTES);
            return $_POST[$chave];
        endif;
        return "";
    }

    protected function getInt($chave) {
        if (isset($_POST[$chave]) && !empty($_POST[$chave])):
            $_POST[$chave] = filter_input(INPUT_POST, $chave, FILTER_VALIDATE_INT);
            return $_POST[$chave];
        endif;
        return 0;
    }

    protected function filtraInt($int) {
        $int = (int) $int;
        if (is_int($int)):
            return $int;
        else:
            return 0;
        endif;
    }

    protected function getSqlverifica($chave) {
        if (isset($_POST[$chave]) && !empty($_POST[$chave])) {
            $_POST[$chave] = strip_tags($_POST[$chave]);
            if (!get_magic_quotes_gpc()) {
                $_POST[$chave] = addslashes($_POST[$chave]);
            }
            return trim($_POST[$chave]);
        }
    }

    protected function alphaNumeric($chave) {
        if (isset($_POST[$chave]) && !empty($_POST[$chave])) {
            $_POST[$chave] = (string) preg_replace('/[^A-Z0-9_]/i', '', $_POST[$chave]);
            return trim($_POST[$chave]);
        }
    }

    protected function verificarEmail($chave) {
        if (isset($_POST[$chave]) && !empty($_POST[$chave])) {
            if (filter_var($_POST[$chave], FILTER_VALIDATE_EMAIL)) {
                return trim($_POST[$chave]);
            }
        }
    }

    protected function getPostParam($param) {

        if (isset($_POST[$param])):
            return $_POST[$param];
        endif;
    }

    protected function redirecionar($caminho = FALSE) {
        if ($caminho) {
            header("location:" . URL . $caminho);
            exit;
        } else {
            header("location:" . URL);
            exit;
        }
    }

    public function getLdap($vista, $link = false) {
        $rutaView = ROOT . "libs" . DS . "src" . DS . $vista . ".php";

        if ($link)
            $link = URL . $link . '/';

        if (is_readable($rutaView)) {
            ob_start();

            include_once $rutaView;

            $contenido = ob_get_contents();

            ob_end_clean();

            return $contenido;
        }

        throw new Exception('Erro ao inserir Rodape');
    }

    /**
      @param integer $tamanho Tamanho da senha a ser gerada
      09
     * @param boolean $maiusculas Se terá letras maiúsculas
      10
     * @param boolean $numeros Se terá números
      11
     * @param boolean $simbolos Se terá símbolos
      12
     *
      13
     * @return string A senha gerada
      14
     */
    function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false) {
        $lmin = 'abcdefghijklmnopqrstuvwxyz';
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        $simb = '!@#$%*-_';
        $retorno = '';
        $caracteres = '';
        $caracteres .= $lmin;
        if ($maiusculas)
            $caracteres .= $lmai;
        if ($numeros)
            $caracteres .= $num;
        if ($simbolos)
            $caracteres .= $simb;
        $len = strlen($caracteres);
        for ($n = 1; $n <= $tamanho; $n++) {
            $rand = mt_rand(1, $len);
            $retorno .= $caracteres[$rand - 1];
        }
        return $retorno;
    }

    function convertTimestamp($data) {

        $date = new DateTime($data);
        $y = gmdate('d.m.Y H:i', $date->getTimestamp());
        $f = strtotime($y);

        $g = bcadd($f, "11644477200");
        $tempo = bcmul($g, "10000000");
        $win_time = ($f + 11644477200) * 10000000;
        return $win_time;
    }

    function apagarAuto() {

        array_walk(glob($dir . '/*'), function ($fn) {
            if (date("U", filectime($file) >= time() - 3600)):
                if (is_file($fn))
                    unlink($fn);

            endif;
        });
        unlink($dir);
    }

    abstract public function index();
}

/*
  // Gera uma senha com 10 carecteres: letras (min e mai), números
  03
  $senha = geraSenha(10);
  04
  // gfUgF3e5m7
  05

  06
  // Gera uma senha com 9 carecteres: letras (min e mai)
  07
  $senha = geraSenha(9, true, false);
  08
  // BJnCYupsN
  09

  10
  // Gera uma senha com 6 carecteres: letras minúsculas e números
  11
  $senha = geraSenha(6, false, true);
  12
  // sowz0g
  13

  14
  // Gera uma senha com 15 carecteres de números, letras e símbolos
  15
  $senha = geraSenha(15, true, true, true);
  16
  // fnwX@dGO7P0!iWM
  17
 */
?>

