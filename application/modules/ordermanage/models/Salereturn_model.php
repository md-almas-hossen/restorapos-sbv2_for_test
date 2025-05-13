<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salereturn_model extends CI_Model {

    public function customer_wise_order_list($customer_id) {
        $return = $this->db->select("*")->from('bill')
                       ->where('customer_id', $customer_id)
                       ->where('bill_status', 1)
                       ->order_by('order_id', 'desc')
                       ->get()
                       ->result();

        return $return;
    }

    public function customer_wise_orderreturn_list($customer_id) {
        $this->db->select('bill.*');
        $this->db->from('bill');
        $this->db->join('sale_return', 'sale_return.order_id = bill.order_id', 'left');
        $this->db->where('bill.customer_id', $customer_id);
        $this->db->where('bill.bill_status', 1);
        $this->db->where('sale_return.order_id IS NULL');
        $this->db->order_by('bill.order_id', 'desc');
        $query        = $this->db->get();
        $orderdetails = $query->result();
        return $orderdetails;
    }
    public function pur_return_insert() {
       
        /*purchase Return Insert*/
        $po_no      = $this->input->post('invoice');
        $createby   = $this->session->userdata('id');
        $createdate = date('Y-m-d H:i:s');
        $postData   = array(
            'order_id'            => $po_no,
            'customer_id'         => $this->input->post('customer_id', true),
            'return_date'         => $this->input->post('return_date', true),
            'totalamount'         => $this->input->post('grand_total_price', true),
            'service_charge'      => $this->input->post('service_charge', true),
            'totaldiscount'       => $this->input->post('tot_discount', true),
            'total_vat'           => $this->input->post('tot_po_vat', true),
            'return_reason'       => $this->input->post('reason', true),
            'sub_total'           => array_sum($this->input->post('total_price')),
            'createby'            => $createby,
            'createdate'          => $createdate,
            'full_invoice_return' => $this->input->post('full_invoice_return', true),
        );

		$postData['subcode_id'] = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 3)->where('refCode', $this->input->post('customer_id', true))->get()->row()->id;
        
        $grand_total_price = $this->input->post('grand_total_price', true);
        $this->db->insert('sale_return', $postData);
        $id = $this->db->insert_id();

        /***************End**********************/
        /***************End**********************/

        $p_id       = $this->input->post('product_id');
        $rate       = $this->input->post('product_rate');
        $quantity   = $this->input->post('total_qntt');
        $p_discount = $this->input->post('discount');
        $itemvat    = $this->input->post('vat');

        $n = count($p_id);

        for ($i = 0; $i < $n; $i++) {
            $product_quantity = $quantity[$i];
            $product_rate     = $rate[$i];
            $product_id       = $p_id[$i];
            $pdiscount        = $p_discount[$i];
            $vats             = $itemvat[$i];

            if ($product_quantity > 0) {
                $data = array(
                    'oreturn_id'   => $id,
                    'product_id'   => $product_id,
                    'order_id'     => $po_no,
                    'qty'          => $product_quantity,
                    'product_rate' => $product_rate,
                    'discount'     => $pdiscount,
                    'itemvat'      => $vats,
                );

                $this->db->insert('sale_return_details', $data);

                $branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();
                $url        = $branchinfo->branchip . "/branchsale/return";

                if (!empty($branchinfo)) {
                    $getitem = $this->db->select('*')->from('item_foods')->where('ProductsID', $product_id)->get()->row();
                    //echo $this->db->last_query();
                    $vinfo = $this->db->select('VariantCode')->from('variant')->where('menuid', $getitem->ProductsID)->get()->row();
                    //echo $this->db->last_query();
                    $iteminfo = array('variant_code' => $vinfo->VariantCode, 'quantity' => $product_quantity);
                    /*$test=array(
                    'authorization_key' => $branchinfo->authkey,
                    'invoice_no' => $po_no,
                    'return_amount' => 0,
                    'return_item_details'=>json_encode($iteminfo)
                    );
                    print_r($test);*/
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL            => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING       => '',
                        CURLOPT_MAXREDIRS      => 10,
                        CURLOPT_TIMEOUT        => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST  => 'POST',
                        CURLOPT_POSTFIELDS     => array(
                            'authorization_key'   => $branchinfo->authkey,
                            'invoice_no'          => $po_no,
                            'return_amount'       => 0,
                            'return_item_details' => json_encode($iteminfo),
                        ),
                    ));
                    $response = curl_exec($curl);
                    print_r($response);
                    curl_close($curl);
                }

            }
        }
        return true;
    }

    public function order_return_update() {
        /*purchase Return Insert*/
        $po_no      = $this->input->post('invoice');
        $preturn_id = $this->input->post('preturn_id');
        $createby   = $this->session->userdata('id');
        $createdate = date('Y-m-d H:i:s');
        $postData   = array(
            'order_id'            => $po_no,
            'customer_id'         => $this->input->post('customer_id', true),
            'return_date'         => $this->input->post('return_date', true),
            'totalamount'         => $this->input->post('grand_total_price', true),
            'service_charge'      => $this->input->post('service_charge', true),
            'totaldiscount'       => $this->input->post('tot_discount', true),
            'total_vat'           => $this->input->post('tot_po_vat', true),
            'return_reason'       => $this->input->post('reason', true),
            'createby'            => $createby,
            'createdate'          => $createdate,
            'full_invoice_return' => $this->input->post('full_invoice_return', true),
        );

        $grand_total_price = $this->input->post('grand_total_price', true);
        $this->db->where('oreturn_id', $preturn_id)->update('sale_return', $postData);

        $id         = $preturn_id;
        $p_id       = $this->input->post('product_id');
        $rate       = $this->input->post('product_rate');
        $quantity   = $this->input->post('total_qntt');
        $p_discount = $this->input->post('discount');
        $itemvat    = $this->input->post('vat');

        $n = count($p_id);
        $this->db->where('oreturn_id', $preturn_id)->delete('sale_return_details');

        for ($i = 0; $i < $n; $i++) {
            $product_quantity = $quantity[$i];
            $product_rate     = $rate[$i];
            $product_id       = $p_id[$i];
            $pdiscount        = $p_discount[$i];
            $vats             = $itemvat[$i];

            if ($product_quantity < 0) {
                $product_quantity = 0;
            }
            //  if($product_quantity>0){
            $data = array(
                'oreturn_id'   => $id,
                'product_id'   => $product_id,
                'order_id'     => $po_no,
                'qty'          => $product_quantity,
                'product_rate' => $product_rate,
                'discount'     => $pdiscount,
                'itemvat'      => $vats,
            );
            $this->db->insert('sale_return_details', $data);
            //  }
        }
        return true;
    }

    public function countreturnlist() {
        $this->db->select("*,service_charge as serv_charge,totaldiscount as t_discount,total_vat as t_vat,totalamount as tamount");
        $this->db->from('sale_return');
        $this->db->join('customer_info', 'customer_info.customer_id=sale_return.customer_id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }

    public function readinvoice($limit = null, $start = null) {

        $this->db->select("*,service_charge as serv_charge,totaldiscount as t_discount,total_vat as t_vat,totalamount as tamount");
        $this->db->from('sale_return');
        $this->db->join('customer_info', 'customer_info.customer_id=sale_return.customer_id');
        $this->db->order_by('sale_return.oreturn_id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return false;
    }

    public function order_dropdown() {
        $last_third_start = date('Y-m-d', strtotime('-2 month'));
        $currentmonth     = date('Y-m-d', strtotime('-0 month'));
        $date_range       = "DATE(bill_date) BETWEEN  '$last_third_start' AND '$currentmonth'";
        $data             = $this->db->select("*")
                                 ->from('bill')
                                 ->where($date_range)
                                 ->order_by('order_id', 'desc')
                                 ->get()
                                 ->result();
        $list[''] = 'Select Invoice/Order id';
        if (!empty($data)) {
            foreach ($data as $value) {
                $list[$value->order_id] = $value->order_id;
            }

            return $list;
        } else {
            return false;
        }
    }

    public function settinginfo() {
        return $this->db->select("*")->from('setting')
                    ->get()
                    ->row();
    }

}