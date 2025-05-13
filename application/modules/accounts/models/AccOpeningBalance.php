<?php defined('BASEPATH') OR exit('No direct script access allowed');

class AccOpeningBalance extends CI_Model {

 
    private function get_opening_balance_query()
	{
		$column_order = array(null, 'fy.title','ob.opening_debit','ob.opening_credit','fy.title' , 'ac.account_name', 'st.name', 'sc.name'); //set column field database for datatable orderable
		$column_search = array('fy.title','ob.opening_debit','ob.opening_credit','fy.title' , 'ac.account_name', 'st.name', 'sc.name'); //set column field database for datatable searchable 
         $order = array('ob.opbalance_id' => 'DESC');

        $this->db->select("ob.*, fy.title , ac.account_name, st.name as subtype, sc.name as subcode");
        $this->db->from('acc_openingbalance ob'); 
        $this->db->join('acc_financialyear fy','ob.financial_year_id = fy.fiyear_id', 'left');
        $this->db->join('acc_coas ac','ob.acc_coa_id = ac.id', 'left');         
        $this->db->join('acc_subtype st','ob.acc_subtype_id = st.id', 'left'); 
        $this->db->join('acc_subcode sc', 'ob.acc_subcode_id = sc.id', 'left'); 
        //$this->db->order_by('ob.opbalance_id', 'DESC');

		// $startdate = $this->input->post('startdate', true);
		// $enddate = $this->input->post('enddate', true);
        $financialyear = $this->input->post('financialyear', true);
		if (!empty($financialyear)) {
			$condition = "fy.fiyear_id= '" . $financialyear. "'";
			$this->db->where($condition);
		}
		// $this->db->where('customer_order.isdelete!=', 1);

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
	public function get_opening_balance()
	{
		$this->get_opening_balance_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function count_filter_opening_balance()
	{
		$this->get_opening_balance_query();
		$query = $this->db->get();
		return $query->num_rows();
	}
    public function getFinancialYearList(){
       return $this->db->select('*')
        ->from('acc_financialyear')
        ->get()
        ->result();
    }
}