<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class NotificacionController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('NotificacionModel');
    }

    public function guardar_notificacion() {
        $tarjeta_id = $this->input->post('tarjeta_id');
        $mensaje = $this->input->post('mensaje');

        $data = [
            'tarjeta_id' => $tarjeta_id,
            'mensaje' => $mensaje
        ];

        // Verificar si ya existe una notificación para esa tarjeta
        $existing = $this->NotificacionModel->get_by_tarjeta_id($tarjeta_id);
        if ($existing) {
            // Actualizar si ya existe
            $this->NotificacionModel->update($existing['id'], $data);
        } else {
            // Crear nueva si no existe
            $this->NotificacionModel->create($data);
        }

        echo json_encode(['status' => 'success']);
    }

    public function eliminar_notificacion($id) {
        $this->NotificacionModel->delete($id);
        echo json_encode(['status' => 'success']);
    }
}