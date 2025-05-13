<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher_model extends CI_Model {

 
    private function get_voucher_query()
	{
		$column_order = array(null,'vc.TranAmount','vc.VoucherNumber'); //set column field database for datatable orderable
		$column_search = array('vc.TranAmount','vc.VoucherNumber'); //set column field database for datatable searchable 
        $order = array('vc.id' => 'DESC');

        $this->db->select("vc.*");
        $this->db->from('acc_voucher_master vc'); 
        $this->db->order_by('vc.id, vc.CreatedDate', 'desc'); 

        $voucher_type = $this->input->post('voucher_type', true);
		if (!empty($voucher_type)) {
			$condition = "vc.VoucharTypeId= '" . $voucher_type. "'";
			$this->db->where($condition);
		}
		$status = $this->input->post('status', true);
		if (!empty($status)) {
			$condition = "vc.IsApprove= '" . $status. "'";
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
	public function get_voucher()
	{
		$this->get_voucher_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function count_filter_voucher()
	{
		$this->get_voucher_query();
		$query = $this->db->get();
		return $query->num_rows();
	}
	private function get_pending_voucher_query()
	{
		$column_order = array(null,'vc.TranAmount','vc.VoucherNumber'); //set column field database for datatable orderable
		$column_search = array('vc.TranAmount','vc.VoucherNumber'); //set column field database for datatable searchable 
        $order = array('vc.id' => 'DESC');

        $this->db->select("vc.*");
        $this->db->from('acc_voucher_master vc'); 

        $voucher_type = $this->input->post('voucher_type', true);
		if (!empty($voucher_type) || $voucher_type!='0') {
			$condition = "vc.VoucharTypeId= '" . $voucher_type. "'";
			$this->db->where($condition);
		}

		$this->db->where('vc.IsApprove!=', 1);

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


	public function get_pending_voucher()
	{
		$this->get_pending_voucher_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
	public function count_filter_pending_voucher()
	{
		$this->get_pending_voucher_query();
		$query = $this->db->get();
		return $query->num_rows();
	}
	public function getFinancialYearList(){
		$this->db->select('*');
        $this->db->from('acc_financialyear');
		$this->db->where_not_in("is_active",0);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else {
            return false;
        }
	 }

	public function voucher_types(){
		return $this->db->select('*')->from('acc_vouchartype')->get()->result();
	}
	public function transaction_level(){
		return $this->db->select('*')->from('acc_coas')->where('head_level', 4)->where('is_active', true)->get()->result();
	}
	public function voucher_master($id){
		return $this->db->select('*')->from('acc_voucher_master')->where('id', $id)->get()->row();
	}
	public function voucher_details($id){
		return $this->db->select('vd.*,sc.name,ac.account_name')->from('acc_voucher_details vd')
		->join('acc_subcode sc', 'vd.subcode_id = sc.id', 'left')
		->join('acc_coas ac','vd.acc_coa_id = ac.id', 'left')
		->where('vd.voucher_master_id', $id)->get()->result();
	}

	public function deleteVoucher($vno) {
		$voucharinfo=$this->db->select('id')->from('acc_voucher_master')->where('id',$vno)->get()->row();
		$this->db->where('id',$vno)->delete('acc_voucher_master');
		 if($this->db->affected_rows()) {
			 $this->db->where('voucher_master_id',$voucharinfo->id)->delete('acc_voucher_details');
			 return true;
		 } else {
			 return false;
		 }
	  return false;
	}
	  // Reverse Voucher
	public function reverseVoucher($vno) {
	  $voucharinfo=$this->db->select('id')->from('acc_voucher_master')->where('id',$vno)->get()->row();
	  $transactioninfo=$this->db->select('voucher_master_id')->from('acc_transactions')->where('voucher_master_id',$vno)->get()->row();
	  if($transactioninfo){
		 $this->db->where('voucher_master_id',$vno)->delete('acc_transactions');
	  }else{
		 $this->db->where('voucher_master_id',$voucharinfo->id)->delete('acc_transactions');  
	  }
	
	  $updatedBy=$this->session->userdata('fullname');
	  $updateddate=date('Y-m-d H:i:s');
	  $uparray = array(
				  'IsApprove'  => 0,
				  'UpdatedBy'    => $updatedBy, 
				  'UpdatedDate'  => $updateddate, 
			  ); 
	$this->db->where('id',$vno)->update('acc_voucher_master', $uparray);
	  if($this->db->affected_rows()) {
			 return true;
		 } else {
			 return false;
		 }
	  return false;
	}
}