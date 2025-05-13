<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Orderreturn extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->library('lsoft_setting');
        $this->load->model(array(
            'order_model',
            'salereturn_model',
        ));
    }

    public function index() {
        $data['title']        = display('pos_invoice_return');
        $settinginfo          = $this->salereturn_model->settinginfo();
        $data['setting']      = $settinginfo;
        $data['customerlist'] = $this->order_model->customer_dropdown();
        $data['allorderlist'] = $this->salereturn_model->order_dropdown();
        $data['module']       = "ordermanage";
        $data['page']         = "order_return";
        echo Modules::run('template/layout', $data);

    }

    public function getinvoice() {
        $customer_id = $this->input->post('customer_id');
        $invoiceinfo = $this->salereturn_model->customer_wise_orderreturn_list($customer_id);
        // $invoiceinfo = $this->db->select("*")->from('bill')->where('customer_id',$customer_id)->where('bill_status',1)->order_by('order_id','desc')->get()->result();
        $option = '';
        if (!empty($invoiceinfo)) {
            foreach ($invoiceinfo as $invoice) {
                $option .= '<option value="' . $invoice->order_id . '">' . $invoice->order_id . '</option>';
            }
        }
        echo '<select name="invoice" id="invoice" class="form-control js-basic-single">
                                   <option value=""  selected="selected">Select Option</option>
                                   ' . $option . '
                                   </select>';
    }

    public function order_return_entry() {

        $data['title'] = display('purchase_return');
        $this->form_validation->set_rules('paytype', 'Payment Type', 'required');
        $payttpe  = $this->input->post('paytype');
        $qtycheck = $this->input->post('total_qntt');
        $product_rate = array_sum($this->input->post('product_rate'));

        $haystack   = count($qtycheck);
        $emptycheck = 0;

        for ($i = 0; $i < $haystack; $i++) {
            if (!empty($qtycheck[$i])) {
                $emptycheck += 1;
            }
        }

        if ($emptycheck == 0) {
            $this->form_validation->set_rules('total_qntt', 'Select quantity', 'required');
        }

        if ($emptycheck != 0) {
            if ($this->salereturn_model->pur_return_insert()) {

                $this->session->set_flashdata('message', display('save_successfully'));
                redirect('ordermanage/orderreturn/index');
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
                redirect('ordermanage/orderreturn/index');
            }
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
            redirect('ordermanage/orderreturn/index');
        }

    }

    public function order_return_item() {
        //$this->permission->method('purchase','read')->redirect();
        $data['title'] = display('purchase_return');
        $invoice       = $this->input->post('invoice');

        $isAlreadyReturn = $this->db->select("*")->from('sale_return')->where('order_id', $invoice)->get()->row();
        if ($isAlreadyReturn) {
            $this->session->set_flashdata('exception', 'Already Return This Order');
            redirect('ordermanage/orderreturn/index');
        } else {
            $invoiceinfo = $this->db->select("*")->from('customer_order')->where('order_id', $invoice)->get()->row();

            // $data['iteminfo'] = $this->db->select("*")->from('order_menu')
            // ->where('order_id',$invoice)
            // ->get()
            // ->row();
            $data['billinfo']     = $this->db->select("*")->from('bill')->join('customer_order', 'customer_order.order_id=bill.order_id')->where('bill.order_id', $invoice)->get()->row();
            $data['customerlist'] = $this->order_model->customer_dropdown();

            $data['returnitem'] = $this->db->select("*")
                                       ->from('order_menu')
                                       ->join('item_foods', 'item_foods.ProductsID=order_menu.menu_id')
                                       ->where('order_menu.order_id', $invoice)
                                       ->get()->result();

            $settinginfo     = $this->salereturn_model->settinginfo();
            $data['setting'] = $settinginfo;
            $this->load->view('ordermanage/sale_returnform', $data);

        }
    }

    public function returntbllist() {

        // $data['title'] = display('order_return');
        $data['title'] = 'Order Return';
        #-------------------------------#
        #
        #pagination starts
        #
        $config["base_url"]         = base_url('ordermanage/orderreturn/returntbllist/');
        $config["total_rows"]       = $this->salereturn_model->countreturnlist();
        $config["per_page"]         = 10;
        $config["uri_segment"]      = 4;
        $config["last_link"]        = "Last";
        $config["first_link"]       = "First";
        $config['next_link']        = 'Next';
        $config['prev_link']        = 'Prev';
        $config['full_tag_open']    = "<ul class='pagination col-xs pull-right'>";
        $config['full_tag_close']   = "</ul>";
        $config['num_tag_open']     = '<li>';
        $config['num_tag_close']    = '</li>';
        $config['cur_tag_open']     = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close']    = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open']    = "<li>";
        $config['next_tag_close']   = "</li>";
        $config['prev_tag_open']    = "<li>";
        $config['prev_tagl_close']  = "</li>";
        $config['first_tag_open']   = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open']    = "<li>";
        $config['last_tagl_close']  = "</li>";
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page               = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["returnitem"] = $this->salereturn_model->readinvoice($config["per_page"], $page);
        $data["links"]      = $this->pagination->create_links();
        $data['pagenum']    = $page;
        $data['module']     = "ordermanage";
        $data['page']       = "order_return_list";
        echo Modules::run('template/layout', $data);
    }

    public function returnedit($id) {

        $data['title'] = display('purchase_return');
        $get_order_id  = $this->db->select("*")->from('sale_return')->where('oreturn_id', $id)->get()->row();
        $query         = $this->db->select("*,service_charge as serv_charge,totalamount as totalam,full_invoice_return as full_invoic")
                              ->from('sale_return')
                              ->where('oreturn_id', $id)
                              ->get()
                              ->row();
        $query->params = '';
        $query->params = $this->db->select("a.*,a.qty as qtn,b.ProductName,")
                              ->from('sale_return_details  a')
                              ->join('item_foods b', 'b.ProductsID=a.product_id')
                              ->where('a.oreturn_id', $id)
                              ->get()->result();
        $data['returnitem'] = $query;
        $data['billinfo']   = $this->db->select("*")->from('bill')->join('customer_order', 'customer_order.order_id=bill.order_id')->where('bill.order_id', $query->order_id)->get()->row();
        $data['module']     = "ordermanage";
        $data['page']       = "edit_return_order";
        echo Modules::run('template/layout', $data);
    }

    public function order_return_update() {
        $data['title'] = display('purchase_return');
        $this->form_validation->set_rules('paytype', 'Payment Type', 'required');
        $payttpe  = $this->input->post('paytype');
        $qtycheck = $this->input->post('total_qntt');

        $haystack = count($qtycheck);

        $emptycheck = 0;
        for ($i = 0; $i < $haystack; $i++) {
            if (!empty($qtycheck[$i])) {
                $emptycheck += 1;
            }
        }

        if ($emptycheck == 0) {
            $this->form_validation->set_rules('total_qntt', 'Select quantity', 'required');
        }
        if ($emptycheck != 0) {
            if ($this->salereturn_model->order_return_update()) {

                $this->session->set_flashdata('message', display('save_successfully'));
                redirect('ordermanage/orderreturn/index');
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
                redirect('ordermanage/orderreturn/index');
            }
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
            redirect('ordermanage/orderreturn/index');
        }
    }

    public function delete_return($id) {
        $get_order_id = $this->db->select("*")->from('sale_return')->where('oreturn_id', $id)->get()->row();
        $this->db->where('order_id', $get_order_id->order_id)->delete('sale_return');
        $this->db->where('order_id', $get_order_id->order_id)->delete('sale_return_details');

        $this->session->set_flashdata('message', display('delete_successfully'));
        redirect('ordermanage/orderreturn/index');
    }

    public function make_payment() {

        $id                 = $this->input->post('id');
        $data['returnitem'] = $this->db->select("*,a.total_vat as t_vat,a.totaldiscount as tdiscount,a.service_charge as servcharge ,a.totalamount as totalam,a.full_invoice_return as full_invoic")
                                   ->from('sale_return a')
                                   ->where('a.oreturn_id', $id)
                                   ->get()
                                   ->row();

        $data['allpaymentmethod'] = $this->order_model->allpmethod();
        $data['paymentmethod']    = $this->order_model->pmethod_dropdown();
        $data['banklist']         = $this->order_model->bank_dropdown();
        $data['terminalist']      = $this->order_model->allterminal_dropdown();
        $data['mpaylist']         = $this->order_model->allmpay_dropdown();

        $data['checkisdueinvoic'] = $this->db->select('is_duepayment')->from('customer_order')->where('order_id', $data['returnitem']->order_id)->get()->row();
        $data['payment_list']     = $this->db->select('*')->from('tbl_return_payment')->where('oreturn_id', $id)->get()->result();
        $data['module']           = "ordermanage";
        $data['page']             = "make_payment_return_order";
        echo $this->load->view("make_payment_return_order", $data);
    }

    public function returnprint() {

        $id = $this->input->post('id');

        $data['returnitem'] = $this->db->select("*,a.total_vat as t_vat,a.totaldiscount as tdiscount,a.service_charge as servcharge ,a.totalamount as totalam,a.full_invoice_return as full_invoic")
                                   ->from('sale_return a')
                                   ->where('a.oreturn_id', $id)
                                   ->get()
                                   ->row();

        $data['allpaymentmethod'] = $this->order_model->allpmethod();
        $data['paymentmethod']    = $this->order_model->pmethod_dropdown();
        $data['banklist']         = $this->order_model->bank_dropdown();
        $data['terminalist']      = $this->order_model->allterminal_dropdown();
        $data['mpaylist']         = $this->order_model->allmpay_dropdown();

        $data['checkisdueinvoic'] = $this->db->select('is_duepayment')->from('customer_order')->where('order_id', $data['returnitem']->order_id)->get()->row();
        $data['payment_list']     = $this->db->select('*')->from('tbl_return_payment')->where('oreturn_id', $id)->get()->result();

        $customerorder        = $this->order_model->read('*', 'customer_order', array('order_id' => $data['returnitem']->order_id));
        $data['orderinfo']    = $customerorder;
        $data['customerinfo'] = $this->order_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
        $data['iteminfo']     = $this->order_model->customerorder($data['returnitem']->order_id);
        $data['billinfo']     = $this->order_model->billinfo($data['returnitem']->order_id);
        $data['cashierinfo']  = $this->order_model->read('*', 'user', array('id' => $data['billinfo']->create_by));
        $data['waiter']       = $this->order_model->read('*', 'user', array('id' => $customerorder->waiter_id));
        $settinginfo          = $this->order_model->settinginfo();
        if ($settinginfo->printtype == 1 || $settinginfo->printtype == 3) {
            $updatetData = array('invoiceprint' => 2);
            $this->db->where('order_id', $data['returnitem']->order_id);
            $this->db->update('customer_order', $updatetData);
        }
        $data['settinginfo']        = $settinginfo;
        $data['storeinfo']          = $settinginfo;
        $data['openiteminfo']       = $this->order_model->openorder($data['returnitem']->order_id);
        $data['tableinfo']          = $this->order_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
        $data['currency']           = $this->order_model->currencysetting($settinginfo->currency);
        $data['taxinfos']           = $this->taxchecking();
        $data['gloinvsetting']      = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
        $data['posinvoiceTemplate'] = $this->order_model->posinvoiceTemplate();

        $data['module'] = "ordermanage";
        $data['page']   = "posinvoiceviewreturn";
        echo $this->load->view("posinvoiceviewreturn", $data);
    }
    private function taxchecking() {
        $taxinfos = '';
        if ($this->db->table_exists('tbl_tax')) {
            $taxsetting = $this->db->select('*')->from('tbl_tax')->get()->row();
        }
        if (!empty($taxsetting)) {
            if ($taxsetting->tax == 1) {
                $taxinfos = $this->db->select('*')->from('tax_settings')->get()->result_array();
            }
        }

        return $taxinfos;
    }
    public function same_payment() {


        $invoice           = $this->input->post('invoice');
        $paidAmount        = $this->input->post('paidAmount');
        $due_amount        = $this->input->post('due_amount');
        $payment_method_id = $this->input->post('payment_method_id');
        $preturn_id        = $this->input->post('oreturn_id');
        $isduebill         = $this->input->post('isduebill');
        $billinfo          = $this->db->select("bill_id,order_id,return_amount")->from('bill')->where('order_id', $invoice)->get()->row();
        $cardinfo          = $this->db->select("*")->from('bill_card_payment')->where('bill_id', $billinfo->bill_id)->get()->row();
        $mobileinfo        = $this->db->select("*")->from('tbl_mobiletransaction')->where('bill_id', $billinfo->bill_id)->get()->row();
        //   card method
        if ($billinfo) {
            $bankid     = $cardinfo->bank_name;
            $terminal   = $cardinfo->terminal_name;
            $last4digit = $cardinfo->card_no;
        } else {
            $bankid     = $this->input->post('bankid');
            $terminal   = $this->input->post('terminal');
            $last4digit = $this->input->post('last4digit');
        }
        //   mobile payment
        if ($mobileinfo) {
            $mobilelist    = $mobileinfo->mobilemethod;
            $mobile        = $mobileinfo->mobilenumber;
            $transactionno = $mobileinfo->transactionnumber;
        } else {
            $mobilelist    = $this->input->post('mobilelist');
            $mobile        = $this->input->post('mobile');
            $transactionno = $this->input->post('transactionno');
        }

        $methodinfo = array(
            'bankid'        => $bankid,
            'terminal'      => $terminal,
            'last4digit'    => $last4digit,
            'mobilelist'    => $mobilelist,
            'mobile'        => $mobile,
            'transactionno' => $transactionno,
        );
        $exitqtn = $this->db->select('*')->from('sale_return')->where('oreturn_id', $preturn_id)->get()->row();

        $prevpaid_amount = $exitqtn->pay_amount + $paidAmount;
        $prev_due_amount = $exitqtn->totalamount - $prevpaid_amount;


        $customer_id =  $this->db->select('customer_id')->from('sale_return')->where('oreturn_id',$preturn_id)->get()->row()->customer_id;
        $acc_subcode_id = $this->db->select('id')->from('acc_subcode')->where('subTypeID',3)->where('refCode', $customer_id)->get()->row()->id; // 3 for customer
        $acc_coa_id = $this->db->select('acc_coa_id')->from('payment_method')->where('payment_method_id',$payment_method_id)->get()->row()->acc_coa_id;

        $data = array(
            'order_id'          => $invoice,
            'pay_amount'        => $paidAmount,
            'oreturn_id'        => $preturn_id,
            'payment_method_id' => $payment_method_id,
            'customer_id'       => $customer_id,
            'subcode_id'        => $acc_subcode_id,
            'acc_coa_id'        => $acc_coa_id,
            'createddate'       => date('Y-m-d'),
        );

        $returntable = array(
            "pay_amount" => $prevpaid_amount,
            // "due_amount"=>$prev_due_amount,
            'pay_status' => 1,
        );

        $isreturnord = array(
            "return_order_id" => $exitqtn->order_id,
            'return_amount'   => $billinfo->return_amount + $paidAmount,
        );

        $orderinfo      = $this->db->select('customer_id')->from('customer_order')->where('order_id', $invoice)->get()->row();
        

        $settinginfo    = $this->db->select("*")->from('setting')->get()->row();

        /*
            $predefine      = $this->db->select("*")->from('tbl_predefined')->get()->row();
            $financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
            $tblsubcode     = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 3)->where('refCode', $orderinfo->customer_id)->get()->row();
            // Return -send
            $row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
            if (empty($row1->max_rec)) {
                $voucher_no = 1;
            } else {
                $voucher_no = $row1->max_rec;
            }

            $cinsert = array(
                'Vno'         => $voucher_no,
                'Vdate'       => date('Y-m-d'),
                'companyid'   => 0,
                'BranchId'    => 0,
                'Remarks'     => "Return Order Adjusted",
                'createdby'   => $this->session->userdata('fullname'),
                'CreatedDate' => date('Y-m-d H:i:s'),
                'updatedBy'   => $this->session->userdata('fullname'),
                'updatedDate' => date('Y-m-d H:i:s'),
                'voucharType' => ($isduebill == 1) ? 3 : 1,
                'refno'       => $preturn_id,
                'isapprove'   => ($settinginfo->is_auto_approve_acc == 1) ? 1 : 0,
                'fin_yearid'  => $financialyears->fiyear_id,
            );

            $this->db->insert('tbl_voucharhead', $cinsert);
            $dislastid = $this->db->insert_id();

            if ($isduebill == 1) {
                $income4 = array(
                    'voucherheadid' => $dislastid,
                    'HeadCode'      => $predefine->CustomerAcc,
                    'Debit'         => 0,
                    'Creadit'       => $paidAmount,
                    'RevarseCode'   => $predefine->SalesAcc,
                    'subtypeID'     => 3,
                    'subCode'       => $tblsubcode->id,
                    'LaserComments' => 'Return Order Adjusted',
                    'chequeno'      => NULL,
                    'chequeDate'    => NULL,
                    'ishonour'      => NULL,
                );
                $this->db->insert('tbl_vouchar', $income4);
                $income5 = array(
                    'VNo'            => $voucher_no,
                    'Vtype'          => 3,
                    'VDate'          => date('Y-m-d'),
                    'COAID'          => $predefine->CustomerAcc,
                    'ledgercomments' => 'Return Order Adjusted',
                    'Debit'          => 0,
                    'Credit'         => $paidAmount,
                    'reversecode'    => $predefine->SalesAcc,
                    'subtype'        => 3,
                    'subcode'        => $tblsubcode->id,
                    'refno'          => $preturn_id,
                    'chequeno'       => NULL,
                    'chequeDate'     => NULL,
                    'ishonour'       => NULL,
                    'IsAppove'       => 1,
                    'IsPosted'       => 1,
                    'CreateBy'       => $this->session->userdata('fullname'),
                    'CreateDate'     => date('Y-m-d H:i:s'),
                    'UpdateBy'       => $this->session->userdata('fullname'),
                    'UpdateDate'     => date('Y-m-d H:i:s'),
                    'fin_yearid'     => $financialyears->fiyear_id,

                );
                if ($settinginfo->is_auto_approve_acc == 1) {
                    $this->db->insert('acc_transaction', $income5);
                }
                //Discount For Credit
                $income = array(
                    'VNo'            => $voucher_no,
                    'Vtype'          => 3,
                    'VDate'          => date('Y-m-d'),
                    'COAID'          => $predefine->SalesAcc,
                    'ledgercomments' => 'Return Order Adjusted',
                    'Debit'          => $paidAmount,
                    'Credit'         => 0,
                    'reversecode'    => $predefine->CustomerAcc,
                    'subtype'        => 1,
                    'subcode'        => 0,
                    'refno'          => $preturn_id,
                    'chequeno'       => NULL,
                    'chequeDate'     => NULL,
                    'ishonour'       => NULL,
                    'IsAppove'       => 1,
                    'IsPosted'       => 1,
                    'CreateBy'       => $this->session->userdata('fullname'),
                    'CreateDate'     => date('Y-m-d H:i:s'),
                    'UpdateBy'       => $this->session->userdata('fullname'),
                    'UpdateDate'     => date('Y-m-d H:i:s'),
                    'fin_yearid'     => $financialyears->fiyear_id,
                );
                if ($settinginfo->is_auto_approve_acc == 1) {
                    $this->db->insert('acc_transaction', $income);
                }
            } else {
                if ($payment_method_id == 4) {
                    $headcode = $predefine->CashCode;
                } elseif ($payment_method_id == 1) {
                    $bankinfo = $this->db->select('bank_name')->from('tbl_bank')->where('bankid', (int) $bankid)->get()->row();
                    $coainfo  = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->bank_name)->get()->row();
                    $headcode = $coainfo->id;
                } elseif ($payment_method_id == 14) {
                    $bankinfo = $this->db->select('mobilePaymentname')->from('tbl_mobilepmethod')->where('mpid', (int) $mobilelist)->get()->row();
                    $coainfo  = $this->db->select('id')->from('tbl_ledger')->where('Name', $bankinfo->mobilePaymentname)->get()->row();
                    $headcode = $coainfo->id;
                } else {
                    $onlinepaytype = $this->db->select('payment_method')->from('payment_method')->where('payment_method_id', $payment_method_id)->get()->row();
                    $coainfo       = $this->db->select('id')->from('tbl_ledger')->where('Name', $onlinepaytype->payment_method)->get()->row();
                    $headcode      = $coainfo->id;
                }

                $income4 = array(
                    'voucherheadid' => $dislastid,
                    'HeadCode'      => $predefine->SalesAcc,
                    'Debit'         => $paidAmount,
                    'Creadit'       => 0,
                    'RevarseCode'   => $headcode,
                    'subtypeID'     => 1,
                    'subCode'       => 0,
                    'LaserComments' => 'Return Amount',
                    'chequeno'      => NULL,
                    'chequeDate'    => NULL,
                    'ishonour'      => NULL,
                );
                $this->db->insert('tbl_vouchar', $income4);
                //print_r($income4);
                $income4 = array(
                    'VNo'            => $voucher_no,
                    'Vtype'          => 1,
                    'VDate'          => date('Y-m-d'),
                    'COAID'          => $predefine->SalesAcc,
                    'ledgercomments' => 'Return Amount',
                    'Debit'          => $paidAmount,
                    'Credit'         => 0,
                    'reversecode'    => $headcode,
                    'subtype'        => 1,
                    'subcode'        => 0,
                    'refno'          => $preturn_id,
                    'chequeno'       => NULL,
                    'chequeDate'     => NULL,
                    'ishonour'       => NULL,
                    'IsAppove'       => 1,
                    'IsPosted'       => 1,
                    'CreateBy'       => $this->session->userdata('fullname'),
                    'CreateDate'     => date('Y-m-d H:i:s'),
                    'UpdateBy'       => $this->session->userdata('fullname'),
                    'UpdateDate'     => date('Y-m-d H:i:s'),
                    'fin_yearid'     => $financialyears->fiyear_id,

                );
                if ($settinginfo->is_auto_approve_acc == 1) {
                    $this->db->insert('acc_transaction', $income4);
                }
                //Discount For Credit
                $income = array(
                    'VNo'            => $voucher_no,
                    'Vtype'          => 1,
                    'VDate'          => date('Y-m-d'),
                    'COAID'          => $headcode,
                    'ledgercomments' => 'Return Amount',
                    'Debit'          => 0,
                    'Credit'         => $paidAmount,
                    'reversecode'    => $predefine->SalesAcc,
                    'subtype'        => 1,
                    'subcode'        => 0,
                    'refno'          => $preturn_id,
                    'chequeno'       => NULL,
                    'chequeDate'     => NULL,
                    'ishonour'       => NULL,
                    'IsAppove'       => 1,
                    'IsPosted'       => 1,
                    'CreateBy'       => $this->session->userdata('fullname'),
                    'CreateDate'     => date('Y-m-d H:i:s'),
                    'UpdateBy'       => $this->session->userdata('fullname'),
                    'UpdateDate'     => date('Y-m-d H:i:s'),
                    'fin_yearid'     => $financialyears->fiyear_id,
                );
                if ($settinginfo->is_auto_approve_acc == 1) {
                    $this->db->insert('acc_transaction', $income);
                }

            }


        */

        $event_code = 'SPMSR';

        $sale_return = $this->db->select('*')->from('sale_return')->where('oreturn_id', $preturn_id)->get()->row();

        $event_code .= $sale_return->full_invoice_return == 1 ? 'F' : ($sale_return->adjustment_status == 1 ? 'A' : 'P');
  
        $this->db->where('oreturn_id', $preturn_id)->update('sale_return', ['voucher_event_code'=> $event_code]); 
        
        if (!empty($paidAmount)) {
            $this->db->where('oreturn_id', $preturn_id)->update('sale_return', $returntable);
            $this->db->where('order_id', $exitqtn->order_id)->update('bill', $isreturnord);
            $this->db->insert('tbl_return_payment', $data);

            $branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();
            $url        = $branchinfo->branchip . "/branchsale/return";
            $testar     = array(
                'authorization_key'   => $branchinfo->authkey,
                'invoice_no'          => $exitqtn->order_id,
                'return_amount'       => $paidAmount,
                'return_item_details' => '',
            );
            //print_r($testar);
            if (!empty($branchinfo)) {
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
                        'invoice_no'          => $exitqtn->order_id,
                        'return_amount'       => $paidAmount,
                        'return_item_details' => '',
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }
            $this->session->set_flashdata('message', display('save_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }

        $posting_setting = auto_manual_voucher_posting(1);
		if($posting_setting == true){

            $is_sub_branch = $this->session->userdata('is_sub_branch');
			if($is_sub_branch == 0){
                $this->db->query("CALL AccIntegrationVoucherPosting(?, ?, @output_message)", array($preturn_id, $event_code));
                $process_query = $this->db->query("SELECT @output_message AS output_message");
                $process_result = $process_query->row();
            }
        }
        redirect("ordermanage/orderreturn/make_payment/$preturn_id");

    }

    public function paymentinfoList() {
        $preturn_id   = $this->input->post('preturn_id');
        $payment_list = $this->db->select('*')->from('tbl_return_payment')->where('oreturn_id', $preturn_id)->get()->result();
        $html         = "";
        $sl           = 1;
        foreach ($payment_list as $list) {
            $paymentinfo = $this->db->select("a.payment_method")->from('payment_method a')->where('a.payment_method_id', $list->payment_method_id)->get()->row();
            $html .= '<tr>';
            $html .= '<td>' . $sl . '</td>';
            $html .= '<td>' . $list->order_id . '</td>';
            $html .= '<td>' . $list->pay_amount . '</td>';
            $html .= '<td>' . $list->createddate . '</td>';
            $html .= '<td>' . $paymentinfo->payment_method . '</td>';
            $html .= '</tr>';

            $sl++;
        }
        echo $html;
    }

}