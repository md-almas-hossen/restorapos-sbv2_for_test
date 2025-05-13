<?php defined('BASEPATH') OR exit('No direct script access allowed');

class AccPredefined extends CI_Model {
	public function getPredefineCodeNewEntry() {
        $this->db->select('*')
             ->from('acc_predefined')
             ->where('is_active', 1)
             ->where('id NOT IN (SELECT predefined_id FROM acc_predefined_seeting)', NULL, FALSE); // Exclude used predefined_id
    	return $this->db->get()->result();
    }

    public function getPredefineCode($current_predefined_id = null)
	{
		$this->db->select('*')
				->from('acc_predefined')
				->where('is_active', 1);

		if ($current_predefined_id) {
			// Exclude used `predefined_id` except the one being edited
			$this->db->where("(id NOT IN (SELECT predefined_id FROM acc_predefined_seeting) OR id = $current_predefined_id)", NULL, FALSE);
		} else {
			// For new entry, exclude all used `predefined_id`
			$this->db->where('id NOT IN (SELECT predefined_id FROM acc_predefined_seeting)', NULL, FALSE);
		}

		return $this->db->get()->result();
	}

    public function getCoaHeads()
    {
        return  $this->db->select('*')->from('acc_coas')->where('head_level',4)->where('is_active',1)->get()->result();
    }

	public function getPredefineSettingsById($id) {
        return $this->db->select('*')->from('acc_predefined_seeting')->where('id',$id)->where('is_active',1)->get()->row();
    }
    
    private function get_predefined_setting_query()
	{
		
		$column_order = array(null,'ps.predefined_seeting_name','p.predefined_name,c.account_name'); //set column field database for datatable orderable
		$column_search = array('ps.predefined_seeting_name','p.predefined_name,c.account_name'); //set column field database for datatable searchable 
        $order = array('ps.id' => 'DESC');

        $this->db->select("ps.*,p.predefined_name,c.account_name,CONCAT(u.firstname, ' ', u.lastname) AS fullname");
        $this->db->from('acc_predefined_seeting ps');
        $this->db->join('acc_predefined p', 'p.id = ps.predefined_id');
        $this->db->join('acc_coas c', 'c.id = ps.acc_coa_id');
		$this->db->join('user u', 'u.id = ps.created_by');  
		$this->db->where('ps.is_active=', 1);

		$i = 0;
		foreach ($column_search as $item) // loop column 
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($order)) {
			$order = $order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	public function get_predefined_setting()
	{
		$this->get_predefined_setting_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function count_filter_predefined_setting()
	{
		$this->get_predefined_setting_query();
		$query = $this->db->get();
		return $query->num_rows();
	}
}