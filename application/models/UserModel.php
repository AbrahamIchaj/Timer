<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();  // Cargar la base de datos
    }

    // Método para verificar si el usuario existe y coincide la contraseña
    public function validar_usuario($username, $password) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            $user = $query->row();

            // Compara la contraseña
            if ($user->password == $password) {
                // Ahora obtenemos el rol del usuario
                $this->db->where('id', $user->role_id);
                $role_query = $this->db->get('roles');

                if ($role_query->num_rows() == 1) {
                    $user->role = $role_query->row()->role_name;
                } else {
                    $user->role = null;  // Por si el rol no existe
                }

                return $user;  // Devuelve el objeto del usuario junto con su rol
            }
        }

        return false;  // Si no coincide, devolver false
    }
}
