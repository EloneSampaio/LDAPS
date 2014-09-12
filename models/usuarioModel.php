<?php

/**
 * Description of categoriaModel
 *
 * @author sam
 */
class usuarioModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function novo_usuario($data) {


        $date = new DateTime($data['data']);
        $y = gmdate('d.m.Y H:i', $date->getTimestamp());
        $f = strtotime($y);
        $g = bcadd($f, "11644477200");
        $tempo = bcmul($g, "10000000");
        $win_time = ($f + 11644477200) * 10000000;
        $this->getBibliotecas('src' . DS . "adLDAP");


        $atributos = array(
            "username" => $data['login'],
            "logon_name" => $data['login'],
            "firstname" => $data['primeiro'],
            "surname" => $data['ultimo'],
            "company" => $data['empresa'],
            //"department" => "desi",
            "email" => "",
            "telephone" => $data['telefone'],
            "container" => array("FINSTAR", "Wireless", "Guest"),
            "enabled" => 1,
            "expires" => $tempo,
            "password" => $data['senha'],
        );


        try {
            $adldap = new adLDAP();
            $adldap->authenticate(Session::get('user'), Session::get('senha'));
            return $adldap->user()->create($atributos);
        } catch (adLDAPException $e) {
            return $e->getMessage();
        }
    }

    public function editar_data($data) {


        $date = new DateTime($data['data']);
        $y = gmdate('d.m.Y H:i', $date->getTimestamp());
        $f = strtotime($y);
        $g = bcadd($f, "11644477200");
        $tempo = bcmul($g, "10000000");
        $win_time = ($f + 11644477200) * 10000000;


        $atributos = array(
            "expires" => $tempo,
        );


        try {
            $adldap = new adLDAP();
            $adldap->authenticate(Session::get('user'), Session::get('senha'));

            $adldap->user()->modify($data['usuario'], $atributos);
        } catch (adLDAPException $e) {
            return $e->getMessage();
        }
    }

    public function editar_senha($data) {
        try {
            $adldap = new adLDAP();
            $adldap->authenticate(Session::get('user'), Session::get('senha'));

            $adldap->user()->password($data['usuario'], $data['senha']);
        } catch (adLDAPException $e) {
            return $e->getMessage();
        }
    }

    public function listarinfo($dados) {

        $adldap = new adLDAP();
        $adldap->authenticate(Session::get('user'), Session::get('senha'));
        $r = $adldap->user()->infoCollection($dados, array("accountexpires", "displayName", "samaccountname"));
        return $r;
    }

    public function apagar_usuario($id) {
        $adldap = new adLDAP();
        $adldap->authenticate(Session::get('user'), Session::get('senha'));
        $t = $adldap->user()->delete($id);
        return $t;
    }

    public function verificar_usuario($usuario) {
        $adldap = new adLDAP();
        $t = $adldap->user()->info($usuario);
        return $t;
    }

    public function user_pertence($usuario) {
        $adldap = new adLDAP();
        $adldap->authenticate(Session::get('user'), Session::get('senha'));
        $v = $adldap->user()->groups($usuario);
        return $v;
    }

    public function pesquisar($usuario) {
        $adldap = new adLDAP();
        $adldap->authenticate(Session::get('user'), Session::get('senha'));
        return $adldap->user()->infoCollection($usuario, array("*"));
    }

    function add_grupo($usuario) {
        $adldap = new adLDAP();
        $adldap->authenticate(Session::get('user'), Session::get('senha'));
        $adldap->group()->addUser(GROUPNAME, $usuario);
    }

    public function listarAll() {

        $adldap = new adLDAP();
        $adldap->authenticate(Session::get('user'), Session::get('senha'));
        $result = $adldap->user()->all();
        return $result;
    }

    public function retirar_usuario($id) {
        $adldap = new adLDAP();
        $adldap->authenticate(Session::get('user'), Session::get('senha'));
        $t = $adldap->group()->removeUser(GROUPNAME, $id);
        return $t;
    }

//fim
}
