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
            $rol = '';
            if ($user) {
              
                if ($user->role == 'operador_cronometro') {
                    $rol = ('operador_cronometro');
                    // Pasar el username y rol a la vista mostrar_tarjetas_cronometro
                    $data['username'] = $username;
                    $data['rol'] = $rol;

                    $this->load->view('mostrar_tarjetas_cronometro', $data);

                } elseif ($user->role == 'operador_temporizador') {
                    $rol = 'operador_temporizador';
                    // Pasar el username y rol a la vista mostrar_tarjetas_tempo
                    $data['username'] = $username;
                    $data['rol'] = $rol;
                    $this->load->view('mostrar_tarjetas_tempo', $data);

                } elseif ($user->role == 'administrador') {
                    $rol = 'operador_temporizador';
                    // Pasar el username y rol a la vista mostrar_tarjetas_tempo
                    $data['username'] = $username;
                    $data['rol'] = $rol;
                    $this->load->view('mostrar_admin', $data);
                }
            } else {
                // Si no coincide el usuario/contraseña, mostrar error
                $data['error'] = 'Credenciales incorrectas. Por favor, intente de nuevo.';
                $this->load->view('cronometro_form', $data);
            }
        }
    }
    

    public function cronometro_form() {
        $this->load->view('cronometro_form');  // Carga la vista del formulario cronómetro
    }


}





