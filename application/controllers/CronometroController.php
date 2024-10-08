<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CronometroController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('url');
    }

    public function index() {
        $this->load->view('cronometro_form');
    }

    public function procesarFormulario() {
        $this->form_validation->set_rules('username', 'Nombre de usuario', 'required');
        $this->form_validation->set_rules('num_controles', 'Cantidad de tarjetas', 'required|integer|greater_than[0]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('cronometro_form');
        } else {
            $username = $this->input->post('username');
            $num_controles = $this->input->post('num_controles');

            // Inicializar tarjetas
            $tarjetas = array();
            for ($i = 1; $i <= $num_controles; $i++) {
                $tarjetas[] = array('tipo' => 'cronometro', 'nombre' => 'Tarjeta ' . $i);
            }

            $data = array(
                'username' => $username,
                'tarjetas' => $tarjetas
            );

            $this->load->view('mostrar_tarjetas', $data);
        }
    }
    

    public function cronometro_form() {
        $this->load->view('cronometro_form');  // Carga la vista del formulario cronómetro
    }


}





