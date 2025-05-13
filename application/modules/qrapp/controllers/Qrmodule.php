<?php
/*
 * @System      : Software Addon Module
 * @developer   : Md.Mamun Khan Sabuj
 * @E-mail      : mamun.sabuj24@gmail.com
 * @datetime    : 10-10-2020
 * @version     : Version 1.0
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Qrmodule extends MX_Controller
{
	public $version='';
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'qrmodule_model',
			'ordermanage/order_model'
        ));
		$this->version=1;
        //$this->auth->check_admin_auth();
    }

    public function index()
    {
        $this->permission->method('qrapp','read')->redirect(); 
		$data['title'] = display('module_list');
        $data['module'] = "qrapp";
        $data['page'] = "qrorder";
        echo Modules::run('template/layout', $data);
    }
	public function allqrorder(){
		$this->permission->method('qrapp','read')->redirect(); 
		$list = $this->qrmodule_model->get_qronlineorder();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rowdata) {
			if($rowdata->bill_status==1){
			$paymentyst="Paid";
			}
			else{$paymentyst="Unpaid";}
			$no++;
			$row = array();
			$update='';
			$print='';
			$details='';
			$paymentbtn='';
			$cancelbtn='';
			$rejectbtn='';
			$posprint='';
			
			if($this->permission->method('ordermanage','read')->access()):
			$details='&nbsp;<a onclick="detailspop('.$rowdata->order_id.')" class="btn btn-xs btn-success btn-sm mr-1" data-placement="left" title="" data-original-title="Details" data-toggle="modal" data-target="#orderdetailsp" data-dismiss="modal"><i class="fa fa-eye"></i></a>&nbsp;';
			$posprint='<a onclick="pospageprint('.$rowdata->order_id.')" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="Pos Invoice"><i class="fa fa-window-maximize"></i></a>';
			endif;
			$row[] = $no;
			$row[] = $rowdata->saleinvoice;
			$row[] = $rowdata->customer_name;
			$row[] = "QR Customer";
			$row[] = $rowdata->first_name.$rowdata->last_name;
			$row[] = $rowdata->tablename;
			$row[] = $paymentyst;
			$row[] = $rowdata->order_date;
			$row[] = $rowdata->totalamount;
			$row[] =$cancelbtn.$rejectbtn.$paymentbtn.$update.$posprint.$details;
			$row[] = $rowdata->isupdate;
			$data[] = $row;
		}
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->qrmodule_model->count_allqrorder(),
						"recordsFiltered" => $this->qrmodule_model->count_filtertqrorder(),
						"data" => $data,
				);
		echo json_encode($output);
		
		}
	public function tableqrcode(){
		$this->permission->method('qrapp','read')->redirect(); 
		$data['title'] = display('module_list');
		$data['tablelist']   = $this->qrmodule_model->tablelist();
        $data['module'] = "qrapp";
        $data['page'] = "tableqr";
        echo Modules::run('template/layout', $data);
		}
	public function roomqrcode(){
		$this->permission->method('qrapp','read')->redirect(); 
		$data['title'] = display('module_list');
		$data['roomlist']   = $this->qrmodule_model->roomlist();
        $data['module'] = "qrapp";
        $data['page'] = "roomqr";
        echo Modules::run('template/layout', $data);
		}
	public function qrpaymentsetting(){
		$this->permission->method('qrapp','read')->redirect(); 
		$data['title'] = display('qr_payment');
		$data['paymentlist']   = $this->db->select('payment_method.*,paymentsetup.*')->from('payment_method')->join('paymentsetup','paymentsetup.paymentid=payment_method.payment_method_id','left')->where('payment_method.is_active',1)->order_by('payment_method.payment_method_id','DESC')->get()->result();
		$data['slpaymentlist'] =$this->db->select('*')->from('tbl_qrpayments')->get()->row();
		//echo $this->db->last_query();
        $data['module'] = "qrapp";
        $data['page'] = "paymentpage";
        echo Modules::run('template/layout', $data);
		}
	public function savepayment(){
			$this->permission->method('qrapp','read')->redirect();
			$payments=$this->input->post('payments');
			if(!empty($payments)){
			$exist=$this->db->select('paymentsid')->from('tbl_qrpayments')->get()->row();
			if(!empty($exist)){
			$updatetready = array(
						'paymentsid'    => $payments
				        );
			$tt=$this->db->where('qrpid',1)->update('tbl_qrpayments', $updatetready); 
			}else{
				$insertdata = array(
						'paymentsid'    => $payments
				        );
				$tt=$this->db->insert('tbl_qrpayments', $insertdata);
				}
			
			if($tt){
				echo 1;
			}else{
				echo 0;
				}
			}else{
				$updatetready = array(
						'paymentsid'    => $payments
				        );
			$tt=$this->db->where('qrpid',1)->update('tbl_qrpayments', $updatetready); 
				echo 0;
				}
			
		    
			
		}
		public function qrthemesetting(){
		$this->permission->method('qrapp','read')->redirect(); 
		$data['title'] = display('qr_setting');
		$data['qrsetting']   = $this->db->select('*')->from('tbl_qrsetting')->get()->row();
		//echo $this->db->last_query();
        $data['module'] = "qrapp";
        $data['page'] = "settingpage";
        echo Modules::run('template/layout', $data);
		}
		public function saveqrsetting(){
				$this->permission->method('qrapp','read')->redirect();
				$postData = array(
					'id'          => $this->input->post('id',true),
					'image' 	  => $this->input->post('image',true)?$this->input->post('image',true):0,
					'cartbtn' 	  => $this->input->post('cartbtn',true)?$this->input->post('cartbtn',true):0,
					'theme' 	  => $this->input->post('theme',true),
					'backgroundcolorqr'  => $this->input->post('headercolor', true),
					'qrheaderfontcolor'  => $this->input->post('headerfontcolor', true),
					
					'qrbuttoncolor'  => $this->input->post('qrbuttoncolor', true),
					'qrbuttonhovercolor'  => $this->input->post('qrbuttonhovercolor', true),
					'qrbuttontextcolor'  => $this->input->post('qrbuttontextcolor', true),
					
					'review_code'  => $this->input->post('review_code'),
					'isactivereview'  => $this->input->post('isreview', true)
				); 
				print_r($postData);
				$this->db->update('tbl_qrsetting',$postData);
				
				$this->session->set_flashdata('message',display('save_successfully'));
				redirect('qrapp/qrmodule/qrthemesetting');
		}

   

}
