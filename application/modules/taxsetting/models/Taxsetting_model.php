<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
 
class Taxsetting_model extends CI_Model{ 
        
  
    public function __construct()
    {
        parent::__construct();
    }

        public function tax_setting_info(){
        $this->db->select('*');
        $this->db->from('tax_settings');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
 }

}