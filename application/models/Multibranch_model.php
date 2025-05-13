<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Welcome_model
 *
 * @author linktech
 */
class Multibranch_model extends CI_Model{
  

	public function allporeqlist(){
		$this->db->select('*');
        $this->db->from('po_tbl');
		$this->db->order_by('id', 'desc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();	
		}
		return false;
		}

			
		public function allPoDetailslist($id){
			$this->db->select('a.*, b.ProductName');
			$this->db->from('po_details_tbl a');
			$this->db->join('item_foods b', 'b.ProductsID = a.productid');
			$this->db->where('po_id', $id);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->result();    
			}
			return false;
		} 
			
		public function getSinglePoRequest($id){
			$this->db->select('a.*');
			$this->db->from('po_tbl a');
			$this->db->where('id', $id);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->row();    
			}
			return false;
		} 
	 public function insert_data($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
	 public function read($select_items, $table, $where_array)
    {
        $this->db->select($select_items);
        $this->db->from($table);
        foreach ($where_array as $field => $value) {
            $this->db->where($field, $value);
        }
        return $this->db->get()->row();
    }
   public function read2($select_items, $table, $orderby, $where_array)
			{
			   
				$this->db->select($select_items);
				$this->db->from($table);
				foreach ($where_array as $field => $value) {
					$this->db->where($field, $value);
					
				}
				$this->db->order_by($orderby,'DESC');
				return $this->db->get()->result();
			}
	public function update_date($table, $data, $field_name, $field_value)
    {
        $this->db->where($field_name, $field_value);
        $this->db->update($table, $data);
        return $this->db->affected_rows();
    }


	public function customerorder($id){
		$where="order_menu.order_id = '".$id."' ";
		$sql="SELECT order_menu.row_id,order_menu.order_id,order_menu.groupmid as menu_id,order_menu.itemdiscount,order_menu.notes,order_menu.add_on_id,order_menu.tpid,order_menu.tpassignid,order_menu.tpprice,order_menu.tpposition,order_menu.addonsqty,order_menu.groupvarient as varientid,order_menu.addonsuid,order_menu.qroupqty as menuqty,order_menu.price as price,order_menu.isgroup,order_menu.food_status,order_menu.allfoodready,order_menu.isupdate,item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price as mprice FROM order_menu LEFT JOIN item_foods ON order_menu.groupmid=item_foods.ProductsID LEFT JOIN variant ON order_menu.groupvarient=variant.variantid WHERE {$where} AND order_menu.isgroup=1 Group BY order_menu.groupmid UNION SELECT order_menu.row_id,order_menu.order_id,order_menu.menu_id as menu_id,order_menu.itemdiscount,order_menu.notes,order_menu.add_on_id,order_menu.tpid,order_menu.tpassignid,order_menu.tpprice,order_menu.tpposition,order_menu.addonsqty,order_menu.varientid as varientid,order_menu.addonsuid,order_menu.menuqty as menuqty,order_menu.price as price,order_menu.isgroup,order_menu.food_status,order_menu.allfoodready,order_menu.isupdate, item_foods.ProductName,item_foods.is_customqty,item_foods.price_editable,variant.variantid, variant.variantName, variant.price as mprice FROM order_menu LEFT JOIN item_foods ON order_menu.menu_id=item_foods.ProductsID LEFT JOIN variant ON order_menu.varientid=variant.variantid WHERE {$where} AND order_menu.isgroup=0";
		$query=$this->db->query($sql);
	
        return $query->result();
		}
	public function getiteminfo($id = null)
	{ 
		$this->db->select('*');
        $this->db->from('item_foods');
		$this->db->where('ProductsID',$id);
		$this->db->where('ProductsIsActive',1);
		$this->db->order_by('itemordering','ASC');
		$query = $this->db->get();
		$itemlist=$query->row();
	    return $itemlist;
	}
	public function summeryiteminfo($id,$tdate,$frdate){
		$where="create_at Between '$tdate' AND '$frdate'";
		$this->db->select('bill.order_id');
        $this->db->from('bill');
		$this->db->where('create_by',$id);
		$this->db->where($where);
		$this->db->where('bill_status',1);
		$this->db->where('bill.isdelete!=',1);
		$query = $this->db->get();
		$changetotal=$query->result();
		return $changetotal;
		
		}
		public function closingaddons($order_ids){
			$newids="'".implode("','",$order_ids)."'";
				$condition="order_menu.order_id IN($newids) ";
				$sql="SELECT * FROM order_menu WHERE {$condition} AND order_menu.add_on_id!=''";
			
			$query=$this->db->query($sql);
			$orderinfo=$query->result();
			return $orderinfo;
		}
		public function billsummery($id,$tdate,$crdate){
			$where="bill.create_at Between '$tdate' AND '$crdate'";
			$this->db->select('SUM(bill.total_amount) as nitamount, SUM(bill.discount) as discount, SUM(bill.service_charge) as service_charge, SUM(bill.VAT) as VAT,SUM(bill.bill_amount) as bill_amount');
			$this->db->from('bill');
			$this->db->where('bill.create_by',$id);
			$this->db->where($where);
			$this->db->where('bill.bill_status',1);
			$this->db->where('bill.isdelete!=',1);
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $billinfo=$query->row();
			}
		public function collectcashsummery($id,$tdate,$crdate){
			$where="bill.create_at Between '$tdate' AND '$crdate'";
			$this->db->select('bill.*,multipay_bill.payment_type_id,SUM(multipay_bill.amount) as totalamount,payment_method.payment_method');
			$this->db->from('multipay_bill');
			$this->db->join('bill','bill.order_id=multipay_bill.order_id','left');
			$this->db->join('payment_method','payment_method.payment_method_id=multipay_bill.payment_type_id','left');
			$this->db->where('bill.create_by',$id);
			$this->db->where($where);
			$this->db->where('bill.bill_status',1);
			$this->db->where('bill.isdelete!=',1);
			$this->db->group_by('multipay_bill.payment_type_id');
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $orderdetails=$query->result();
			}
		public function collectduesalesummery($id,$tdate,$crdate){
			$where="bill.create_at Between '$tdate' AND '$crdate'";
			$this->db->select('SUM(bill.total_amount) as nitdueamount,SUM(bill.discount) as duediscount,SUM(bill.service_charge) as dueservice_charge,SUM(bill.VAT) as dueVAT,SUM(bill.bill_amount) as totaldue,customer_order.is_duepayment');
			$this->db->from('bill');
			$this->db->join('customer_order','customer_order.order_id=bill.order_id','left');
			$this->db->where('bill.create_by',$id);
			$this->db->where($where);
			$this->db->where('bill.bill_status',1);
			$this->db->where('bill.isdelete!=',1);
			$this->db->where('customer_order.is_duepayment',1);
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $orderdetails=$query->row();
			}
			public function collectcashreturnsummery($id,$tdate,$crdate){
				$where="bill.create_at Between '$tdate' AND '$crdate'";
				$this->db->select('bill.*,multipay_bill.payment_type_id,SUM(sale_return.pay_amount) as totalreturn,payment_method.payment_method,sale_return.order_id');
				$this->db->from('multipay_bill');
				$this->db->join('bill','bill.order_id=multipay_bill.order_id','left');
				$this->db->join('payment_method','payment_method.payment_method_id=multipay_bill.payment_type_id','left');
				$this->db->join('sale_return','sale_return.order_id=bill.return_order_id','left');
				$this->db->where('bill.create_by',$id);
				$this->db->where($where);
				$this->db->where('bill.return_order_id IS NOT NULL');
				$this->db->where('bill.return_order_id >0');
				$this->db->where('bill.isdelete!=',1);
				$this->db->where('sale_return.adjustment_status!=',1);
				$query = $this->db->get();
				//echo $this->db->last_query();
				return $orderdetails=$query->row();
				}
			public function changecashsummery($id,$tdate,$crdate){
				$where="bill.create_at Between '$tdate' AND '$crdate'";
				$this->db->select('bill.*,SUM(customer_order.totalamount) as totalexchange');
				$this->db->from('customer_order');
				$this->db->join('bill','bill.order_id=customer_order.order_id','left');
				$this->db->where('bill.create_by',$id);
				$this->db->where($where);
				$this->db->where('bill.bill_status',1);
				$this->db->where('bill.isdelete!=',1);
				$query = $this->db->get();
				//echo $this->db->last_query();
				return $changetotal=$query->row();
				}
			public function closingiteminfo($order_ids){
				$this->db->select('order_menu.*,SUM(order_menu.menuqty) as totalqty,SUM(order_menu.price*order_menu.menuqty) as fprice,item_foods.*,variant.variantName,variant.price');
				$this->db->from('order_menu');
				$this->db->join('item_foods','order_menu.menu_id=item_foods.ProductsID','left');
				$this->db->join('variant','order_menu.varientid=variant.variantid','left');
				$this->db->where_in('order_menu.order_id',$order_ids);
				$this->db->group_by('order_menu.menu_id');
				$this->db->group_by('order_menu.varientid');
				$query = $this->db->get();
				$orderinfo=$query->result();
				//echo $this->db->last_query();
				return $orderinfo;
			}
		
		
}
