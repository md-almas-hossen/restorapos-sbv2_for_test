<?php defined('BASEPATH') OR exit('No direct script access allowed');

class AccFinancialYear extends CI_Model {
    function get_yearlist()
    {
        $this->db->select('*');
        $this->db->from('acc_financialyear');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        } else {
            return false;
        }
    }
}