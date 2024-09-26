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

    // Roles
    public function obtener_roles() {
        $query = $this->db->get('roles'); 
        return $query->result();
    }


    public function agregar_rol($role_name) {
        $data = array(
            'role_name' => $role_name
        );
        return $this->db->insert('roles', $data);
    }
    
    public function editar_rol($id, $role_name) {
        $data = array(
            'role_name' => $role_name
        );
        $this->db->where('id', $id);
        return $this->db->update('roles', $data);
    }
    
    public function eliminar_rol($id) {
        $this->db->where('id', $id);
        return $this->db->delete('roles');
    }

    // Usuarios
     // Obtener la lista de usuarios
     public function obtener_users() {
        $this->db->select('users.*, roles.role_name');
        $this->db->join('roles', 'users.role_id = roles.id', 'left');
        $query = $this->db->get('users');
        return $query->result();
    }

    // Agregar usuario
    public function agregar_usuario($data) {
        return $this->db->insert('users', $data);
    }

    // Editar usuario
    public function editar_usuario($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    // Eliminar usuario
    public function eliminar_usuario($id) {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }

    // Obtener roles para el select
    public function obtener_roles_u() {
        $query = $this->db->get('roles');
        return $query->result();
    }
}
