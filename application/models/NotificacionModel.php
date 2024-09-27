<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class NotificacionModel extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function create($data) {
        return $this->db->insert('notificaciones', $data);
    }

    public function get_by_tarjeta_id($tarjeta_id) {
        $query = $this->db->get_where('notificaciones', ['tarjeta_id' => $tarjeta_id]);
        return $query->row_array();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('notificaciones', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('notificaciones');
    }
}
