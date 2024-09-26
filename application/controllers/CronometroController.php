<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CronometroController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->model('UserModel');
    }

    public function index() {
        $this->load->view('cronometro_form');
    }

    public function procesarFormulario() {
        // Validar los campos del formulario
        $this->form_validation->set_rules('username', 'Nombre de usuario', 'required');
        $this->form_validation->set_rules('contrasena', 'Contraseña', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            // Si la validación falla, recargar el formulario
            $this->load->view('cronometro_form');
        } else {
            // Si la validación es exitosa, procesar el formulario
            $username = $this->input->post('username');
            $password = $this->input->post('contrasena');
            
            // Validar las credenciales
            $user = $this->UserModel->validar_usuario($username, $password);
            if ($user) {
                if ($user->role == 'operador_cronometro') {
                    $data['username'] = $username;
                    $data['rol'] = $user->role;
                    $this->load->view('mostrar_tarjetas_cronometro', $data);
                } elseif ($user->role == 'operador_temporizador') {
                    $data['username'] = $username;
                    $data['rol'] = $user->role;
                    $this->load->view('mostrar_tarjetas_tempo', $data);
                } elseif ($user->role == 'administrador') {
                    $data['username'] = $username;
                    $data['rol'] = $user->role;
                    $this->mostrar_admin();
                }
            } else {
                $data['error'] = 'Credenciales incorrectas. Por favor, intente de nuevo.';
                $this->load->view('cronometro_form', $data);
            }
        }
    }
    
    public function cronometro_form() {
        $this->load->view('cronometro_form');  // Carga la vista del formulario cronómetro
    }
    

    public function mostrar_admin() {
        $data['roles'] = $this->UserModel->obtener_roles(); // Cargar roles para el select de usuarios
        $data['users'] = $this->UserModel->obtener_users(); // Cargar usuarios para la tabla
        $this->load->view('mostrar_admin', $data); // Cargar la vista con los datos
    }

    public function agregar_rol() {
        $role_name = $this->input->post('role_name');
        if ($this->UserModel->agregar_rol($role_name)) {
            redirect('CronometroController/mostrar_admin');
        }
    }
    
    public function editar_rol($id) {
        $role_name = $this->input->post('role_name');
        if ($this->UserModel->editar_rol($id, $role_name)) {
            redirect('CronometroController/mostrar_admin');
        }
    }
    
    public function eliminar_rol($id) {
        if ($this->UserModel->eliminar_rol($id)) {
            redirect('CronometroController/mostrar_admin');
        }
    }

    // Usuarios
    // Mostrar la lista de usuarios
    public function mostrar_admin2() {
     
    }

    // Agregar un nuevo usuario
    public function agregar_usuario() {
        // Validar los campos del formulario
        $this->form_validation->set_rules('username', 'Nombre de Usuario', 'required');
        $this->form_validation->set_rules('password', 'Contraseña', 'required');
        $this->form_validation->set_rules('role_id', 'Rol', 'required');
    
        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'role_id' => $this->input->post('role_id')
            );
            $this->UserModel->agregar_usuario($data);
            redirect('CronometroController/mostrar_admin'); // Redirigir a mostrar_admin
        } else {
            $this->mostrar_admin();
        }
    }
    

    // Editar usuario
    public function editar_usuario($id) {
        $this->form_validation->set_rules('username', 'Nombre de Usuario', 'required');
        $this->form_validation->set_rules('password', 'Contraseña', 'required');
        $this->form_validation->set_rules('role_id', 'Rol', 'required');

        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'role_id' => $this->input->post('role_id')
            );
            $this->UserModel->editar_usuario($id, $data);
            redirect('CronometroController/mostrar_admin');
        } else {
            $this->mostrar_admin();
        }
    }

    // Eliminar usuario
    public function eliminar_usuario($id) {
        $this->UserModel->eliminar_usuario($id);
        redirect('CronometroController/mostrar_admin');
    }

    
}
