<?php

/**
 * Description of categoriaModel
 *
 * @author sam
 */
class GrupoModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function novo_grupo($data) {

        $this->getBibliotecas('src' . DS . "adLDAP");
        $atributos = array(
            "group_name" => $data['nome'],
            "description" => $data['descricao'],
            "container" => array("FINSTAR", "Wireless", "Groups")
        );

        try {
            $adldap = new adLDAP();
            $adldap->authenticate(Session::get('usuario'), Session::get('senha'));
            $t = $adldap->group()->create($atributos);
            return $t;
        } catch (adLDAPException $e) {
            return $e->getMessage();
            exit();
        }
    }

    public function listarAll() {

        $adldap = new adLDAP();
        $adldap->authenticate(Session::get('usuario'), Session::get('senha'));
        $result = $adldap->group()->all();
        return $result;
    }

    public function listar_info($dados) {

        $adldap = new adLDAP();
        $adldap->authenticate(Session::get('usuario'), Session::get('senha'));
        $r = $adldap->group()->members($dados);
        return $r;
    }

    public function menbros_grupo() {
        $adldap = new adLDAP();
        $adldap->authenticate(Session::get('usuario'), Session::get('senha'));
        return $adldap->group()->members("Wireless-acess-guest");
    }

    public function editar_grupo($data) {
        
    }

    public function verificar_grupo($grupo) {

        $adldap = new adLDAP();
        $t = $adldap->group()->info($grupo);
        return $t;
    }

    function lista($grupo, $usuario) {

        $adldap = new adLDAP();
        $adldap->authenticate(Session::get('usuario'), Session::get('senha'));
        $result = $adldap->group()->addUser($grupo, $usuario);
        return $result;
    }

//fim
}
