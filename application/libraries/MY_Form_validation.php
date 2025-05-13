<?php

class MY_Form_validation extends CI_Form_validation {

    function run($module = '', $group = '') {
        (is_object($module)) AND $this->CI = &$module;
        return parent::run($group);
    }
	function edit_unique($value, $params)  {
    $CI =& get_instance();
    $CI->load->database();

    $CI->form_validation->set_message('edit_unique', "Sorry, that %s is already being used.");
	
	

    list($table, $field, $filedparam, $current_id) = explode(".", $params);
    $query = $CI->db->select()->from($table)->where($field, $value)->limit(1)->get();
		if ($query->row() && $query->row()->$filedparam != $current_id)
		{
			return FALSE;
		} else {
			return TRUE;
		}
	}
	function name_uniquewithtype($value, $params) {
		
		$CI =& get_instance();
    $CI->load->database();

    $CI->form_validation->set_message('name_uniquewithtype', "Sorry, that %s is already being used.");
	
	

    list($table, $field, $filedparam, $current_id) = explode(".", $params);
	//echo $current_id;
	if($current_id==3){
			$condition="type=2 AND is_addons=1";
		}else{
			$condition="type=$current_id AND is_addons=0";
		}
    $query = $CI->db->select()->from($table)->where($field, $value)->where($condition)->limit(1)->get();
		//echo $CI->db->last_query();
		if ($query->row())
		{
			return FALSE;
		} else {
			return TRUE;
		}
		
   
}

}   