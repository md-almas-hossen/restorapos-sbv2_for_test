<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Purchase extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'purchase_model',
			'logs_model'
		));
		$this->load->library('cart');
	}

	public function index($id = null)
	{

		$this->permission->method('purchase', 'read')->redirect();
		$data['title']    = display('purchase_list');
		#-------------------------------#       
		#
		#pagination starts
		#
		$config["base_url"] = base_url('purchase/purchase/index');
		$config["total_rows"]  = $this->purchase_model->countlist();
		$config["per_page"]    = 25;
		$config["uri_segment"] = 4;
		$config["last_link"] = "Last";
		$config["first_link"] = "First";
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Prev';
		$config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		/* ends of bootstrap */
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data["purchaselist"] = $this->purchase_model->read($config["per_page"], $page);
		$data["links"] = $this->pagination->create_links();
		$data['pagenum'] = $page;
		$data['items']   = $this->purchase_model->ingrediant_dropdown();
		$data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
		$data['supplier']   = $this->purchase_model->supplier_dropdown();
		$settinginfo = $this->purchase_model->settinginfo();
		$data['currency'] = $this->purchase_model->currencysetting($settinginfo->currency);

		if (!empty($id)) {
			$data['title'] = display('purchase_edit');
			$data['intinfo']   = $this->purchase_model->findById($id);
		}
		#
		#pagination ends
		#   

		$data['module'] = "purchase";
		$data['page']   = "purchaselist";
		echo Modules::run('template/layout', $data);
	}


	public function create($id = null)
	{
		$this->permission->method('purchase', 'read')->redirect();
		$data['title'] = display('purchase_add');
		#-------------------------------#
		$saveid = $this->session->userdata('supid');
		$data['intinfo'] = "";
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->readsingle('*', 'currency', array('currencyid' => $settinginfo->currency));
		$data['supplier']   = $this->purchase_model->supplier_dropdown();
		$data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
		$data['list']   = $this->db->select("*")->from('tbl_trams_condition')->get()->row();
		$data['module'] = "purchase";
		$data['page']   = "addpurchase";
		echo Modules::run('template/layout', $data);
	}
	public function purchase_entry()
	{
		
		$purID = $this->input->post('purID');
		
		if($purID){
			$purchaseitem_info =$this->db->select('*')->from('purchaseitem')->where('purID',$purID)->get()->row();
			$posting_setting = auto_manual_voucher_posting(2);
			if($posting_setting == true){
				$sql = "CALL AccVoucherDelete(?, ?, @message);";
				$this->db->query($sql, array($purID, $purchaseitem_info->voucher_event_code));
				$query = $this->db->query("SELECT @message AS message");
			}
		}

		$this->form_validation->set_rules('invoice_no', 'Invoice Number', 'required|max_length[50]');
		$this->form_validation->set_rules('purchase_date', 'Purchase Date', 'required');
		$saveid = $this->session->userdata('id');

		if ($this->form_validation->run()) {

			// dd($this->input->post());

			$this->permission->method('purchase', 'create')->redirect();
			$logData = array(
				'action_page'         => "Add Purchase",
				'action_done'     	 => "Insert Data",
				'remarks'             => "Item Purchase Created",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);
			if ($this->purchase_model->create()) {
				$this->logs_model->log_recorded($logData);
				$this->session->set_flashdata('message', display('save_successfully'));
				redirect('purchase/purchase/create');
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("purchase/purchase/create");
		} else {
			redirect("purchase/purchase/create");
		}
	}

	public function converted_purchase_entry()
	{
		$pur_request_id = $this->input->post('purID');
		$this->form_validation->set_rules('invoice_no', 'Invoice Number', 'required|max_length[50]');
		$this->form_validation->set_rules('purchase_date', 'Purchase Date', 'required');
		$saveid = $this->session->userdata('id');

		if ($this->form_validation->run()) {

			// dd($this->input->post());

			$this->permission->method('purchase', 'create')->redirect();
			$logData = array(
				'action_page'         => "Add Purchase",
				'action_done'     	 => "Insert Data",
				'remarks'             => "Item Purchase Created",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);
			if ($this->purchase_model->create()) {

				// Update purchase request as purchased..
				$data = array(
					'purchase_status' => 1
				);
				$this->db->where('purID', $pur_request_id)->update('supplier_po_request', $data);
				// End

				$this->logs_model->log_recorded($logData);
				$this->session->set_flashdata('message', display('save_successfully'));
				redirect('purchase/purchase/index');
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("purchase/purchase/supplier_po_request_convert/".$pur_request_id);
		} else {
			redirect("purchase/purchase/supplier_po_request_convert/".$pur_request_id);
		}
	}

	//Bulk Food Upload
	public function bulkpurchaseupload()
	{
		$settinginfo = $this->db->select("*")->from('setting')->get()->row();
		$this->permission->method('purchase', 'read')->redirect();
		if (!empty($_FILES["userfile"]["name"])) {
			$_FILES["userfile"]["name"];
			$path = $_FILES["userfile"]["tmp_name"];
			$upload_file = $_FILES["userfile"]["name"];
			$extension = pathinfo($upload_file, PATHINFO_EXTENSION);
			if ($extension == 'csv') {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			} elseif ($extension == 'xls') {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}
			$spreadsheet = $reader->load($_FILES["userfile"]["tmp_name"]);
			$sheetdata = $spreadsheet->getActiveSheet()->toArray();
			$newsheetdata =	$sheetdata;
			$datacount = count($sheetdata);
			unset($newsheetdata[0]);
			$details = $this->unique_multidim_array($newsheetdata, '0');


			$regenarate = array();
			if ($datacount > 1) {
				foreach ($details as $array) {
					//print_r($array);
					$invoice = $array[0];
					$supplier = $array[1];
					$mobile = $array[2];
					$purchasedate = $array[3];
					$totalamount = $array[4];
					$paidamount = $array[5];

					$paymenttype = 1;
					$checkinvoice = $this->db->select("*")->from('purchaseitem')->where('invoiceid', $invoice)->get()->row();
					if (empty($checkinvoice)) {
						$suplierinfo = $this->db->select("*")->from('supplier')->where('supMobile', $mobile)->get()->row();
						if (empty($suplierinfo)) {
							$lastid = $this->db->select("*")->from('supplier')->order_by('suplier_code', 'desc')->get()->row();
							$sl = $lastid->suplier_code;
							if (empty($sl)) {
								$sl = "sup_001";
							} else {
								$sl = $sl;
							}
							$supno = explode('_', $sl);
							$nextno = $supno[1] + 1;
							$si_length = strlen((int)$nextno);

							$str = '000';
							$cutstr = substr($str, $si_length);
							$sino = $supno[0] . "_" . $cutstr . $nextno;
							$supplier = array(
								'suplier_code'  		 => $sino,
								'supName' 			 => $supplier,
								'supEmail' 	         => $sino . $supplier . '@gmail.com',
								'supMobile' 	 	     => $mobile,
								'supAddress' 	     => "Bulk Upload",
							);
							$this->db->insert('supplier', $supplier);
							$supplierid = $this->db->insert_id();
							$subcode = array(
								'name'         	   => $supplier,
								'subTypeID'        => 4,
								'refCode'          => $supplier_id
							);
							$this->db->insert('acc_subcode', $subcode);
						} else {
							$supplierid = $suplierinfo->supid;
						}
						$purchasemain = array(
							'invoiceid'  		 => $invoice,
							'suplierID' 			 => $supplierid,
							'paymenttype' 	     => 1,
							'bankid'				 => 0,
							'total_price' 	 	 => $totalamount,
							'paid_amount' 	     => $paidamount,
							'details' 	     	 => '',
							'purchasedate' 	     => $purchasedate,
							'purchaseexpiredate'  => $purchasedate,
							'savedby' 	     	 => $this->session->userdata('id')
						);
						$this->db->insert('purchaseitem', $purchasemain);

						$purchaseid = $this->db->insert_id();
						$c = 0;
						for ($i = 1; $i < $datacount; $i++) {
							$producttype = $sheetdata[$i][6];
							$units = $array[9];
							$itemname = $sheetdata[$i][7];
							$ptype = '';
							$is_addons = 0;
							if ($producttype == "Raw") {
								$ptype = 1;
							}
							if ($producttype == "Finish") {
								$ptype = 2;
							}
							if ($producttype == "Addons") {
								$ptype = 3;
								$is_addons = 1;
							}
							$unitinfo = $this->db->select("*")->from('unit_of_measurement')->where('uom_name', $units)->get()->row();
							if (!empty($unitinfo)) {
								$iteminfo = $this->db->select("*")->from('ingredients')->where('ingredient_name', $itemname)->get()->row();
								if (empty($iteminfo)) {
									$item = array(
										'ingredient_name'  		=> $itemname,
										'uom_id' 			 	=> $unitinfo->id,
										'type' 	         		=> $ptype,
										'is_addons' 	 	    => $is_addons,
										'is_active' 	     	=> 1,
									);
									$this->db->insert('ingredients', $supplier);
									$itemid = $this->db->insert_id();
								} else {
									$itemid = $iteminfo->id;
								}
								if ($sheetdata[$i][0] == $invoice) {
									$product_quantity = $sheetdata[$i][10];
									$product_rate = $sheetdata[$i][11];
									$productTypes = $ptype;
									$pwiseexpdate = $sheetdata[$i][8];
									$product_id = $itemid;
									$total_price = $product_quantity * $product_rate;

									$data1 = array(
										'purchaseid'		=>	$purchaseid,
										'indredientid'		=>	$product_id,
										'typeid'			=>	$productTypes,
										'quantity'			=>	$product_quantity,
										'price'				=>	$product_rate,
										'totalprice'		=>	$total_price,
										'purchaseby'		=>	$this->session->userdata('id'),
										'purchasedate'		=>	$purchasedate,
										'purchaseexpiredate' =>	$pwiseexpdate
									);

									if (!empty($product_quantity)) {
										/*add stock in ingredients*/
										$this->db->set('stock_qty', 'stock_qty+' . $product_quantity, FALSE);
										$this->db->where('id', $product_id);
										$this->db->update('ingredients');
										/*end add ingredients*/
										$this->db->insert('purchase_details', $data1);
									}
									$c = 1;
								}
							} else {
								$c = 2;
							}
						}
						if ($c == 2) {
							$this->db->where('invoiceid', $invoice)->delete('purchaseitem');
						}
						if ($c == 1) {
							//Account Part
							$supinfo = $this->db->select('*')->from('supplier')->where('supid', $supplierid)->get()->row();
							$tblsubcode = $this->db->select('*')->from('acc_subcode')->where('subTypeID', 4)->where('refCode', $supinfo->supid)->get()->row();
							// Acc transaction
							$recv_id = date('YmdHis');
							$financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
							$predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();

							$duetotal = $totalamount - $paidamount;
							if ($paidamount > 0 && $paidamount < $totalamount) {
								$vainfo = $this->voucharhead($purchasedate, $purchaseid, 3);
								$voucher = explode('_', $vainfo);
								$expensedue = array(
									'voucherheadid'     =>  $voucher[0],
									'HeadCode'          =>  $predefine->SupplierAcc,
									'Debit'          	  =>  0,
									'Creadit'           =>  $duetotal,
									'RevarseCode'       =>  $predefine->purchaseAcc,
									'subtypeID'         =>  4,
									'subCode'           =>  $tblsubcode->id,
									'LaserComments'     =>  'credit For Invoice ' . $voucher[1],
									'chequeno'          =>  NULL,
									'chequeDate'        =>  NULL,
									'ishonour'          =>  NULL
								);
								$this->db->insert('tbl_vouchar', $expensedue);
								if ($settinginfo->is_auto_approve_acc == 1) {
									$this->acctransaction($voucher[0]);
								}
								$vainfo1 = $this->voucharhead($purchasedate, $purchaseid, 1);
								$voucher1 = explode('_', $vainfo1);

								$expensepaid = array(
									'voucherheadid'     =>  $voucher1[0],
									'HeadCode'          =>  $predefine->CashCode,
									'Debit'          	  =>  0,
									'Creadit'           =>  $paidamount,
									'RevarseCode'       =>  $predefine->purchaseAcc,
									'subtypeID'         =>  4,
									'subCode'           =>  $tblsubcode->id,
									'LaserComments'     =>  'credit For Invoice ' . $voucher1[1],
									'chequeno'          =>  NULL,
									'chequeDate'        =>  NULL,
									'ishonour'          =>  NULL
								);
								$this->db->insert('tbl_vouchar', $expensepaid);
								if ($settinginfo->is_auto_approve_acc == 1) {
									$this->acctransaction($voucher1[0]);
								}
							} else {
								$vainfo = $this->voucharhead($purchasedate, $purchaseid, 1);
								$voucher = explode('_', $vainfo);
								$expensepaid = array(
									'voucherheadid'     =>  $voucher[0],
									'HeadCode'          =>  $predefine->CashCode,
									'Debit'          	  =>  0,
									'Creadit'           =>  $totalamount,
									'RevarseCode'       =>  $predefine->purchaseAcc,
									'subtypeID'         =>  4,
									'subCode'           =>  $tblsubcode->id,
									'LaserComments'     =>  'credit For Invoice ' . $voucher[1],
									'chequeno'          =>  NULL,
									'chequeDate'        =>  NULL,
									'ishonour'          =>  NULL
								);
								$this->db->insert('tbl_vouchar', $expensepaid);
								if ($settinginfo->is_auto_approve_acc == 1) {
									$this->acctransaction($voucher[0]);
								}
							}
						}
					} else {
						$this->session->set_flashdata('exception',  display('please_try_again'));
						redirect("purchase/purchase/create");
					}
				}
			}
			$this->session->set_flashdata('message', display('save_successfully'));
			echo '<script>window.location.href = "' . base_url() . 'purchase/purchase/create"</script>';
		} else {
			$this->session->set_flashdata('exception',  display('please_try_again'));
			redirect("purchase/purchase/create");
		}
	}


	public function deletePurchase($id, $voucher_event_code){

		$sql = "CALL AccVoucherDelete(?, ?, @message);";
        $this->db->query($sql, array($id, $voucher_event_code));
        $query = $this->db->query("SELECT @message AS message");
		$this->session->set_flashdata('message', $query->row()->message);
		redirect('purchase/purchase/index');
		
	}


	public function voucharhead($vdate, $refno, $vtype)
	{
		$recv_id = date('YmdHis');
		$financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
		$predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();
		$row1 = $this->db->select('(max(Vno)+1) as max_rec')->from('tbl_voucharhead')->get()->row();
		$settinginfo = $this->db->select("*")->from('setting')->get()->row();
		if (empty($row1->max_rec)) {
			$voucher_no = 1;
		} else {
			$voucher_no = $row1->max_rec;
		}
		$cinsert = array(
			'Vno'            =>  $voucher_no,
			'Vdate'          =>  $vdate,
			'companyid'      =>  0,
			'BranchId'       =>  0,
			'Remarks'        =>  "product purchase",
			'createdby'      =>  $this->session->userdata('fullname'),
			'CreatedDate'    =>  date('Y-m-d H:i:s'),
			'updatedBy'      =>  $this->session->userdata('fullname'),
			'updatedDate'    =>  date('Y-m-d H:i:s'),
			'voucharType'    =>  $vtype,
			'isapprove'      => ($settinginfo->is_auto_approve_acc == 1) ? 1 : 0,
			'refno'		   =>  "purchase-item:" . $refno,
			'fin_yearid'	   => $financialyears->fiyear_id
		);

		$this->db->insert('tbl_voucharhead', $cinsert);
		$vatlastid = $this->db->insert_id();
		return $vatlastid . '_' . $voucher_no;
	}
	public function acctransaction($vheadid)
	{
		$vinfo = $this->db->select('*')->from('tbl_voucharhead')->where('id', $vheadid)->get()->row();
		//echo $this->db->last_query();
		$vouchar = $this->db->select('*')->from('tbl_vouchar')->where('voucherheadid', $vheadid)->get()->row();
		//echo $this->db->last_query();
		$reverseinsert = array(
			'VNo'     		  =>  $vinfo->Vno,
			'Vtype'          	  =>  $vinfo->voucharType,
			'VDate'          	  =>  $vinfo->Vdate,
			'COAID'          	  =>  $vouchar->HeadCode,
			'ledgercomments'    =>  $vouchar->LaserComments,
			'Debit'          	  =>  $vouchar->Debit,
			'Credit'            =>  $vouchar->Creadit,
			'reversecode'       =>  $vouchar->RevarseCode,
			'subtype'        	  =>  $vouchar->subtypeID,
			'subcode'           =>  $vouchar->subCode,
			'refno'     		  =>  $vinfo->refno,
			'chequeno'          =>  $vouchar->chequeno,
			'chequeDate'        =>  $vouchar->chequeDate,
			'ishonour'          =>  $vouchar->ishonour,
			'IsAppove'		  =>  $vinfo->isapprove,
			'CreateBy'          =>  $vinfo->createdby,
			'CreateDate'        =>  $vinfo->CreatedDate,
			'UpdateBy'          =>  $vinfo->updatedBy,
			'UpdateDate'        =>  $vinfo->updatedDate,
			'fin_yearid'		  =>  $vinfo->fin_yearid
		);
		$this->db->insert('acc_transaction', $reverseinsert);
		$reverseinsert = array(
			'VNo'     		  =>  $vinfo->Vno,
			'Vtype'          	  =>  $vinfo->voucharType,
			'VDate'          	  =>  $vinfo->Vdate,
			'COAID'          	  =>  $vouchar->RevarseCode,
			'ledgercomments'    =>  $vouchar->LaserComments,
			'Debit'          	  =>  $vouchar->Creadit,
			'Credit'            =>  $vouchar->Debit,
			'reversecode'       =>  $vouchar->HeadCode,
			'subtype'        	  =>  $vouchar->subtypeID,
			'subcode'           =>  $vouchar->subCode,
			'refno'     		  =>  $vinfo->refno,
			'chequeno'          =>  $vouchar->chequeno,
			'chequeDate'        =>  $vouchar->chequeDate,
			'ishonour'          =>  $vouchar->ishonour,
			'IsAppove'		  =>  $vinfo->isapprove,
			'CreateBy'          =>  $vinfo->createdby,
			'CreateDate'        =>  $vinfo->CreatedDate,
			'UpdateBy'          =>  $vinfo->updatedBy,
			'UpdateDate'        =>  $vinfo->updatedDate,
			'fin_yearid'		  =>  $vinfo->fin_yearid
		);
		$this->db->insert('acc_transaction', $reverseinsert);
		//echo $this->db->last_query();
		return true;
	}

	function unique_multidim_array($array, $key)
	{
		$temp_array = array();
		$i = 0;
		$key_array = array();

		foreach ($array as $val) {
			if (!in_array($val[$key], $key_array)) {
				$key_array[$i] = $val[$key];
				$temp_array[$i] = $val;
			}
			$i++;
		}
		return $temp_array;
	}
	public function downloadformat()
	{

		$this->permission->method('purchase', 'read')->redirect();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Invoice');
		$sheet->setCellValue('B1', 'Supplier');
		$sheet->setCellValue('C1', 'Supplier Mobile');
		$sheet->setCellValue('D1', 'Purchase Date');
		$sheet->setCellValue('E1', 'Total Amount');
		$sheet->setCellValue('F1', 'Paid Amount');
		$sheet->setCellValue('G1', 'Product Type(Raw,Finish,Addons)');
		$sheet->setCellValue('H1', 'Item name');
		$sheet->setCellValue('I1', 'Expire Date');
		$sheet->setCellValue('J1', 'Unit');
		$sheet->setCellValue('K1', 'Qty');
		$sheet->setCellValue('L1', 'Itemprice');
		$rowCount   =   2;
		$arrayfood = array(array("invoice" => "0123", "suplier" => "Jhon", "mobile" => "0163545665", "prdate" => "2022-02-23", "total" => 2085, "paid" => 2085, "ptype" => "Raw", "item" => "Egg", "expdate" => "2022-03-17", "unit" => "Per Pieces", "qty" => 30, "itemprice" => 12), array("invoice" => "0123", "suplier" => "Jhon", "mobile" => "0163545665", "prdate" => "2022-02-23", "total" => 2085, "paid" => 2085, "ptype" => "Raw", "item" => "Onion", "expdate" => "2022-03-15", "unit" => "Kilogram", "qty" => 5, "itemprice" => 45), array("invoice" => "0123", "suplier" => "Jhon", "mobile" => "0163545665", "prdate" => "2022-02-23", "total" => 2085, "paid" => 2085, "ptype" => "Raw", "item" => "Beef Meat", "expdate" => "2022-03-24", "unit" => "Kilogram", "qty" => 2.5, "itemprice" => 600), array("invoice" => "0124", "suplier" => "Karim", "mobile" => "0173545625", "prdate" => "2022-02-27", "total" => 1500, "paid" => 1000, "ptype" => "Raw", "item" => "Oil", "expdate" => "2023-01-17", "unit" => "Liter", "qty" => 5, "itemprice" => 185), array("invoice" => "0124", "suplier" => "Karim", "mobile" => "0173545625", "prdate" => "2022-02-27", "total" => 1500, "paid" => 1000, "ptype" => "Raw", "item" => "Rice", "expdate" => "2023-01-14", "unit" => "Kilogram", "qty" => 8, "itemprice" => 71.875));
		foreach ($arrayfood as $row) {
			$sheet->SetCellValue('A' . $rowCount, $row['invoice'], 'UTF-8');
			$sheet->SetCellValue('B' . $rowCount, $row['suplier'], 'UTF-8');
			$sheet->SetCellValue('C' . $rowCount, $row['mobile'], 'UTF-8');
			$sheet->SetCellValue('D' . $rowCount, $row['prdate'], 'UTF-8');
			$sheet->SetCellValue('E' . $rowCount, $row['total'], 'UTF-8');
			$sheet->SetCellValue('F' . $rowCount, $row['paid'], 'UTF-8');
			$sheet->SetCellValue('G' . $rowCount, $row['ptype'], 'UTF-8');
			$sheet->SetCellValue('H' . $rowCount, $row['item'], 'UTF-8');
			$sheet->SetCellValue('I' . $rowCount, $row['expdate'], 'UTF-8');
			$sheet->SetCellValue('J' . $rowCount, $row['unit'], 'UTF-8');
			$sheet->SetCellValue('K' . $rowCount, $row['qty'], 'UTF-8');
			$sheet->SetCellValue('L' . $rowCount, $row['itemprice'], 'UTF-8');
			$rowCount++;
		}

		$writer = new Xlsx($spreadsheet);

		$filename = 'example.xlsx';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output'); // download file 
	}

	public function checkinvoiceno()
	{
		$invoiceno 	= $this->input->post('invoiceno', true);
		$purchaseid 	= $this->input->post('purchaseid', true);
		if (!empty($purchaseid)) {
			$condition = "invoiceid='" . $invoiceno . "' AND purID!='" . $purchaseid . "'";
		} else {
			$condition = "invoiceid='" . $invoiceno . "'";
		}
		$getrow = $this->db->select("purID,invoiceid")->from('purchaseitem')->where($condition)->get()->row();
		if (!empty($getrow)) {
			echo 0;
		} else {
			echo 1;
		}
	}
	public function banklist()
	{
		$allbank = $this->db->select("*")->from('tbl_bank')->where('acc_coa_id !=', null)->get()->result();
		echo json_encode($allbank);
	}
	public function purchaseitem()
	{
		$csrf_token = $this->security->get_csrf_hash();
		$product_name 	= $this->input->post('product_name', true);
		$product_info 	= $this->purchase_model->finditem($product_name);

		$list[''] = '';
		foreach ($product_info as $value) {
			$json_product[] = array('label' => $value['ingredient_name'], 'value' => $value['id']);
		}
		echo json_encode($json_product);
	}
	public function purchaseitembytype()
	{
		$csrf_token = $this->security->get_csrf_hash();
		$producttype 	= $this->input->post('product_type', true);
		$product_info 	= $this->purchase_model->finditembytypes($producttype);
		$ingredientslist = '<option value="">' . display('select') . ' ' . display('ingredients') . ' </option>';
		foreach ($product_info as $item) {
			$ingredientslist .= '<option value="' . $item->id . '">' . $item->ingredient_name . ' (' . $item->uom_short_code . ') </option>';
		}
		echo $ingredientslist;
	}

	public function typewiseproducts()
	{
		$producttype 	= $this->input->post('product_type', true);

		if($producttype == 1){

			$product_info 	= $this->purchase_model->finditembytypes($producttype);
			$ingredientslist = '<option value="">' . display('select') . ' ' . display('ingredients') . ' </option>';
			foreach ($product_info as $item) {
				$ingredientslist .= '<option value="' . $item->id . '">' . $item->ingredient_name . ' (' . $item->uom_short_code . ') </option>';
			}
			echo $ingredientslist;

		}

		if($producttype == 2){

			$product_info = $this->db->distinct()
									->select('f.*, uom.uom_short_code')
									->from('item_foods f')
									->join('ingredients i', 'i.itemid = f.ProductsID', 'right')
									->join('unit_of_measurement uom', 'uom.id = i.uom_id', 'left')
									->where('f.isIngredient', 1)
									->where('f.withoutproduction', 1)
									->get()
									->result();

		

			$ingredientslist = '<option value="">' . display('select') . ' ' . display('ingredients') . ' </option>';
			foreach ($product_info as $item) {
				$ingredientslist .= '<option value="' . $item->ProductsID . '">' . $item->ProductName . ' (' . $item->uom_short_code . ') </option>';
			}
			echo $ingredientslist;

		}
	}
	


	public function productTypeWiseIngredient()
	{
		$csrf_token = $this->security->get_csrf_hash();
		$producttype 	= $this->input->post('product_type', true);
		$isMasterBranch 	= $this->input->post('isMasterBranch', true);
		$product_info 	= $this->purchase_model->productTypeWiseIngredient($producttype, $isMasterBranch);
		$ingredientslist = '<option value="">' . display('select') . ' ' . display('ingredients') . ' </option>';
		foreach ($product_info as $item) {
			$ingredientslist .= '<option value="' . $item->id . '">' . $item->ingredient_name . ' (' . $item->uom_short_code . ') </option>';
		}
		echo $ingredientslist;
	}

	public function purchasequantity()
	{
		$product_id = $this->input->post('product_id');
		$product_info =  $this->purchase_model->get_total_product($product_id);
		echo json_encode($product_info);
	}


	public function stock_price(){
	    $product_type = $this->input->post('product_type');
		$product_id = $this->input->post('product_id');

		if($product_type == 1){
			$product_info =  $this->purchase_model->ingredientstockprice($product_id);
			echo json_encode($product_info);
		}

		if($product_type == 2){
			$product_info =  $this->purchase_model->readyfoodstockprice(12);
			echo json_encode($product_info);
		}

	}

	public function updateintfrm($id)
	{
		$this->permission->method('purchase', 'update')->redirect();
		$data['title'] = display('purchase_edit');
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->readsingle('*', 'currency', array('currencyid' => $settinginfo->currency));

		$data['supplier']   = $this->purchase_model->supplier_dropdown();
		$data['purchaseinfo']   = $this->purchase_model->findById($id);
		$data['iteminfo']       = $this->purchase_model->iteminfo($id);
		// dd($data['iteminfo']);
		$data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
		// $allbank = $this->db->select("*")->from('tbl_bank')->get()->result();
		$allbank = $this->db->select("*")->from('tbl_bank')->where('acc_coa_id !=', null)->get()->result();
		$data['banklist']       = $allbank;
		$data['module'] = "purchase";
		$data['page']   = "purchaseedit";
		echo Modules::run('template/layout', $data);
	}
	public function purchaseinvoice($id = null)
	{
		$this->permission->method('purchase', 'update')->redirect();
		$data['title'] = display('purchase_edit');
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->currencysetting($settinginfo->currency);
		$data['purchaseinfo']   = $this->purchase_model->findById($id);
		$supid = $data['purchaseinfo']->suplierID;
		$data['supplierinfo']   = $this->purchase_model->suplierinfo($supid);
		$data['iteminfo']       = $this->purchase_model->iteminfo($id);
		$data['module'] = "purchase";
		$data['page']   = "purchaseinvoice";
		echo Modules::run('template/layout', $data);
	}
	public function update_entry()
	{
		$this->form_validation->set_rules('invoice_no', 'Invoice Number', 'required|max_length[50]');
		$this->form_validation->set_rules('purchase_date', 'Purchase Date', 'required');
		$saveid = $this->session->userdata('id');
		if ($this->form_validation->run()) {

			// dd($this->input->post());

			$this->permission->method('purchase', 'update')->redirect();
			$logData = array(
				'action_page'         => "Update Purchase",
				'action_done'     	 => "Update Data",
				'remarks'             => "Item Purchase Updated",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);
			//echo "dfdsf";
			// if ($this->purchase_model->update()) {
			if ($this->purchase_model->create()) {

				$is_sub_branch = $this->session->userdata('is_sub_branch');
				if($is_sub_branch == 0){

					$this->db->query("CALL AccVoucherDelete(?, ?, @output_message)", array($id, $purchaseitem_info->voucher_event_code));
					$process_query = $this->db->query("SELECT @output_message AS output_message");
					$process_result = $process_query->row();
				}
				
				$this->logs_model->log_recorded($logData);
				$this->session->set_flashdata('message', display('update_successfully'));
				redirect('purchase/purchase/index');
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("purchase/purchase/index");
		} else {
			redirect("purchase/purchase/index");
		}
	}
	public function getlist($id)
	{
		$suplierinfo = $this->purchase_model->suplierinfo($id);
		echo json_encode($suplierinfo);
	}
	public function delete($id = null)
	{
		$this->permission->module('purchase', 'delete')->redirect();
		$logData = array(
			'action_page'         => "Purchase List",
			'action_done'     	 => "Delete Data",
			'remarks'             => "Purchase Deleted",
			'user_name'           => $this->session->userdata('fullname'),
			'entry_date'          => date('Y-m-d H:i:s'),
		);
		if ($this->purchase_model->delete($id)) {
			#Store data to log table.
			$this->logs_model->log_recorded($logData);
			#set success message
			$this->session->set_flashdata('message', display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception', display('please_try_again'));
		}
		redirect('purchase/purchase/index');
	}
	public function addproduction($id = null)
	{
		$this->permission->method('purchase', 'read')->redirect();
		$data['title'] = display('purchase_add');
		#-------------------------------#
		$saveid = $this->session->userdata('supid');
		$data['intinfo'] = "";

		$data['item']   = $this->purchase_model->item_dropdown();

		$data['module'] = "purchase";
		$data['page']   = "addproduction";
		echo Modules::run('template/layout', $data);
	}

	public function production_entry()
	{
		$this->form_validation->set_rules('foodid', 'Food Name', 'required');
		$this->form_validation->set_rules('purchase_date', 'Purchase Date', 'required');
		$this->form_validation->set_rules('pro_qty', 'Production Quantity', 'required');
		$saveid = $this->session->userdata('id');

		if ($this->form_validation->run()) {
			$this->permission->method('purchase', 'create')->redirect();
			$logData = array(
				'action_page'         => "Add Production",
				'action_done'     	 => "Insert Data",
				'remarks'             => "Item Production Created",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);
			if ($this->purchase_model->makeproduction()) {
				$this->logs_model->log_recorded($logData);
				$this->session->set_flashdata('message', display('save_successfully'));
				redirect('purchase/purchase/addproduction');
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("purchase/purchase/addproduction");
		} else {
			redirect("purchase/purchase/addproduction");
		}
	}
	public function return_form()
	{
		$this->permission->method('purchase', 'read')->redirect();
		$data['title'] = display('purchase_return');
		$data['supplier']   = $this->purchase_model->supplier_dropdown();
		$data['module'] = "purchase";
		$data['page']   = "purchasereturn";
		echo Modules::run('template/layout', $data);
	}
	public function getinvoice()
	{
		$suplier 	= $this->input->post('id');
		$invoiceinfo = $this->purchase_model->invoicebysupplier($suplier);
		$option = '';
		if (!empty($invoiceinfo)) {
			foreach ($invoiceinfo as $invoice) {
				$option .= '<option value="' . $invoice->invoiceid . '">' . $invoice->invoiceid . '</option>';
			}
		}
		echo  '<select name="invoice" id="invoice" class="form-control">
                                	<option value=""  selected="selected">Select Option</option>
									' . $option . '
									</select>';
	}
	public function returnlist()
	{
		$this->permission->method('purchase', 'read')->redirect();
		$data['title'] = display('purchase_return');
		$invoice 	= $this->input->post('invoice');
		$data['invoice'] = $invoice;
		$invoiceinfo = $this->purchase_model->getinvoice($invoice);
		$purchaseid = $invoiceinfo->purID;
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->currencysetting($settinginfo->currency);
		$supid = $invoiceinfo->suplierID;
		$data['supplierinfo']     = $this->purchase_model->suplierinfo($supid);
		$data['returnitem']       = $this->purchase_model->iteminfo($purchaseid);

		$data['module'] = "purchase";
		$data['page']   = "purchasereturnform";
		$this->load->view('purchase/purchasereturnform', $data);
	}
	public function purchase_return_entry()
	{
		$data['title'] = display('purchase_return');
		$this->form_validation->set_rules('paytype', 'Payment Type', 'required');
		//$this->form_validation->set_rules('total_qntt','Return qty'  ,'required');
		$payttpe =  $this->input->post('paytype');
		$qtycheck =  $this->input->post('total_qntt');
		$haystack = count($qtycheck);
		$emptycheck = 0;
		for ($i = 0; $i <= $haystack; $i++) {
			if (!empty($qtycheck[$i])) {
				$emptycheck += 1;
			}
		}

		// dd($this->input->post());

		if ($payttpe == 2) {
			$this->form_validation->set_rules('bank', 'Select Bank', 'required');
		}
		if ($emptycheck == 0) {
			$this->form_validation->set_rules('total_qntt', 'Select quantity', 'required');
		}

		if ($this->form_validation->run()) {
			if ($this->purchase_model->pur_return_insert()) {
				$this->session->set_flashdata('message', display('save_successfully'));
				redirect('purchase/purchase/return_form/');
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
				redirect('purchase/purchase/return_form/');
			}
		} else {
			redirect("purchase/purchase/return_form");
		}
	}
	public function return_invoice()
	{
		$this->permission->method('purchase', 'read')->redirect();
		$data['title']    = display('purchase_list');
		#-------------------------------#       
		#
		#pagination starts
		#
		$config["base_url"] = base_url('purchase/purchase/return_invoice');
		$config["total_rows"]  = $this->purchase_model->countreturnlist();
		$config["per_page"]    = 25;
		$config["uri_segment"] = 4;
		$config["last_link"] = "Last";
		$config["first_link"] = "First";
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Prev';
		$config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		/* ends of bootstrap */
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data["invoicelist"] = $this->purchase_model->readinvoice($config["per_page"], $page);
		$data["links"] = $this->pagination->create_links();
		$data['pagenum'] = $page;
		$settinginfo = $this->purchase_model->settinginfo();
		$data['currency'] = $this->purchase_model->currencysetting($settinginfo->currency);
		#
		#pagination ends
		#   
		$data['module'] = "purchase";
		$data['page']   = "invoicelist";
		echo Modules::run('template/layout', $data);
	}

	public function returnview($id)
	{
		$this->permission->method('purchase', 'read')->redirect();
		$data['title'] = display('invoice_view');
		$data['purchaseinfo']   = $this->purchase_model->findByreturnId($id);
		$data['iteminfo']       = $this->purchase_model->returniteminfo($id);
		$data['module'] = "purchase";
		$data['page']   = "purchasereturnview";
		echo Modules::run('template/layout', $data);
	}

	public function purchaseReturnListCount()
	{
		$this->db->select('*');
		$this->db->from('purchase_return a');
		$this->db->order_by('a.preturn_id', 'desc');
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function purchase_return_list()
	{
		$data['title'] = display('purchase_return_list');
		#-------------------------------#       
		#
		#pagination starts
		#
		$config["base_url"] = base_url('purchase/purchase/purchase_return_list/');
		$config["total_rows"]  = $this->purchaseReturnListCount();
		$config["per_page"]    = 20;
		$config["uri_segment"] = 4;
		$config["last_link"] = "Last";
		$config["first_link"] = "First";
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Prev';
		$config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		/* ends of bootstrap */
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data["getPurchaseReturnList"] = $this->purchase_model->purchaseReturnList($config["per_page"], $page);
		$data["links"] = $this->pagination->create_links();
		$data['pagenum'] = $page;

		$data['module'] = "purchase";
		$data['page']   = "purchase_return_list";
		echo Modules::run('template/layout', $data);
	}

	public function stock_out_ingredients()
	{
		$this->permission->method('purchase', 'read')->redirect();

		$data['title'] = display('stock_out_ingredients');
		$qreslt = $this->db->query("SELECT A.id, A.ingredient_name, A.stock_qty, A.type FROM ingredients A WHERE EXISTS (SELECT B.id FROM ingredients B WHERE B.id = A.id AND B.ingredient_name = A.ingredient_name AND B.stock_qty < A.min_stock )");
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['module'] = "purchase";
		$data['page']   = "outstock";
		$data['outstock'] =  $qreslt->result();
		echo Modules::run('template/layout', $data);
	}
	public function openingstock()
	{
		$this->permission->method('purchase', 'read')->redirect();

		$data['title'] = display('openingstock');
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['ingredients'] =  $this->purchase_model->allingredient();

		// $qreslt = $this->db->query("SELECT tbl_openingstock.*,ingredients.ingredient_name,ingredients.stock_qty,ingredients.storageunit,ingredients.conversion_unit,unit_of_measurement.uom_short_code FROM tbl_openingstock Left Join ingredients ON ingredients.id=tbl_openingstock.itemid Inner join unit_of_measurement ON unit_of_measurement.id=ingredients.uom_id");
		$qreslt = $this->db->select('o.id, o.fiyear_id, a.title, a.end_date, o.total_amount')->from('tbl_openingstock_master o')->join('acc_financialyear a', 'a.fiyear_id = o.fiyear_id', 'left')->get();
		$data['openstock'] =  $qreslt->result();


		$data['module'] = "purchase";
		$data['page']   = "openstock";
		echo Modules::run('template/layout', $data);
	}


	public function add_opening_stock_multiple()
	{
		$this->permission->method('purchase', 'read')->redirect();
		$data['title'] = display('openingstock');
		#-------------------------------#
		$saveid = $this->session->userdata('supid');
		$data['intinfo'] = "";
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->readsingle('*', 'currency', array('currencyid' => $settinginfo->currency));
		$data['supplier']   = $this->purchase_model->supplier_dropdown();
		$data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
		$data['list']   = $this->db->select("*")->from('tbl_trams_condition')->get()->row();
		$data['financial_years'] = $this->db->select('*')->from('acc_financialyear')->where('is_active', 0)->get()->result();
		$data['module'] = "purchase";
		$data['page']   = "openstock_add_multiple";

		echo Modules::run('template/layout', $data);
	}


	public function editOpeningStock($id){
		$this->permission->method('purchase', 'read')->redirect();
		$data['title'] = display('openingstock');
		#-------------------------------#
		$saveid = $this->session->userdata('supid');
		$data['intinfo'] = "";
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->readsingle('*', 'currency', array('currencyid' => $settinginfo->currency));
		$data['supplier']   = $this->purchase_model->supplier_dropdown();
		$data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
		$data['list']   = $this->db->select("*")->from('tbl_trams_condition')->get()->row();
		$data['financial_years'] = $this->db->select('*')->from('acc_financialyear')->where('is_active', 0)->get()->result();
		$data['opening_stock'] = $this->db->select('*')->from('tbl_openingstock')->where('parent_id', $id)->get()->result();

		$data['old'] = $old = $this->db->select('o.id, o.fiyear_id, a.title, a.end_date, o.total_amount')->from('tbl_openingstock_master o')->join('acc_financialyear a', 'a.fiyear_id = o.fiyear_id', 'left')->where('id', $id)->get()->row();


		$data['module'] = "purchase";
		$data['page']   = "openstock_edit_multiple";
		echo Modules::run('template/layout', $data);
	}

	public function openstock_entry()
	{
		$this->permission->method('purchase', 'create')->redirect();
		$this->form_validation->set_rules('foodid', 'Item Name', 'required');
		$this->form_validation->set_rules('openstockqty', 'Open stock Qty', 'required');
		$saveid = $this->session->userdata('id');

		if ($this->form_validation->run()) {
			$this->permission->method('purchase', 'create')->redirect();
			if (empty($this->input->post('id'))) {
				$data['openstock']   = (object) $postData = array(
					'id'     			=> $this->input->post('id'),
					'itemid'     		=> $this->input->post('foodid'),
					'itemtype'   		=> 0,
					'storageqty'   		=> $this->input->post('openstockqtystorage', true),
					'openstock'   		=> $this->input->post('openstockqty', true),
					'unitprice'   		=> $this->input->post('unitprice', true),
					'entrydate'   		=> $this->input->post('entrydate', true),
					'createby'           => $saveid,
					'createdate'         => date('Y-m-d H:i:s')
				);

				if ($this->purchase_model->stockinsert($postData)) {
					//$product = $this->db->insert_id();
					$productid = $this->input->post('foodid');
					$stockqty = $this->input->post('openstockqty', true);
					$this->db->set('stock_qty', 'stock_qty+' . $stockqty, FALSE);
					$this->db->where('id', $productid);
					$this->db->update('ingredients');

					$this->session->set_flashdata('message', display('save_successfully'));
					redirect('purchase/purchase/openingstock');
				} else {
					$this->session->set_flashdata('exception',  display('please_try_again'));
					redirect("purchase/purchase/openingstock");
				}
			} else {
				$prevstock = $this->db->select('*')->from('tbl_openingstock')->where('id', $this->input->post('id'))->get()->row();
				$data['openstock']   = (object) $postData = array(
					'id'     			=> $this->input->post('id'),
					'itemid'     		=> $this->input->post('foodid'),
					'itemtype'   		=> 0,
					'storageqty'   		=> $this->input->post('openstockqtystorage', true),
					'openstock'   		=> $this->input->post('openstockqty', true),
					'unitprice'   		=> $this->input->post('unitprice', true),
					'entrydate'   		=> $this->input->post('entrydate', true),
					'createby'           => $saveid,
					'createdate'         => date('Y-m-d H:i:s')
				);
				if ($this->purchase_model->updatestock($postData)) {
					$productid = $this->input->post('foodid');
					$stockqty = $this->input->post('openstockqty', true);
					if ($stockqty >= $prevstock) {
						$newstock = $stockqty - $prevstock;
						$this->db->set('stock_qty', 'stock_qty+' . $newstock, FALSE);
					} else {
						$newstock = $prevstock - $stockqty;
						$this->db->set('stock_qty', 'stock_qty-' . $newstock, FALSE);
					}
					$this->db->where('id', $productid);
					$this->db->update('ingredients');

					redirect("purchase/purchase/openingstock");
				} else {
					$this->session->set_flashdata('exception',  display('please_try_again'));
					redirect("purchase/purchase/openingstock");
				}
			}
		} else {
			redirect("purchase/purchase/openingstock");
		}
	}

	public function openstock_entry_multiple()
	{
		$this->permission->method('purchase', 'create')->redirect();
		$saveid = $this->session->userdata('id');

		if(count($this->input->post('product_id')) > 0){

			$product_types = $this->input->post('product_type');
			$food_ids = $this->input->post('product_id');
			$storage_quantities = $this->input->post('storage_quantity');
			$conversion_values = $this->input->post('conversion_value');
			$product_quantities = $this->input->post('product_quantity');
			$product_rates = $this->input->post('product_rate');
			$entrydate = $this->input->post('entrydate');
			$fiyear_id = $this->input->post('fiyear_id');

			$exist = $this->db->select('*')->from('tbl_openingstock_master')->where('fiyear_id',$fiyear_id)->where('date',$entrydate)->get()->row();

			if(empty($exist)){
				
				$master = [
					'fiyear_id' => $fiyear_id,
					'date' => $entrydate,
					'total_amount' => NULL,
					'createdate' => date('Y-m-d h:i:s'),
					'updatedate' => date('Y-m-d h:i:s'),
					'createby' => $saveid,
					'updateby' => $saveid
				];

				$this->db->insert('tbl_openingstock_master', $master);
				$master_id = $this->db->insert_id();
			
			}else{

				$master_id = $exist->id;
			}

			
			$totalPrice = 0;
			
			foreach ($food_ids as $key => $food_id) {
				
				$totalPrice += $product_quantities[$key]*$product_rates[$key];
				$data['openstock']   = (object) $postData = array(
					'id'     			=> Null,
					'itemid'     		=> $food_id,
					'itemtype'   		=> $product_types[$key] == 1 ? 0 : 1,
					'storageqty'   		=> $storage_quantities[$key],
					'openstock'   		=> $product_quantities[$key],
					'unitprice'   		=> $product_rates[$key],
					'entrydate'   		=> $entrydate,
					'createby'          => $saveid,
					'createdate'        => date('Y-m-d H:i:s'),
					'parent_id'         => $master_id
				);
				$exis_check = $this->purchase_model->checkItemOpenStockExistis($food_id, $postData['itemtype']);

				if(!$exis_check){
				// insert
					if ($this->purchase_model->stockinsert($postData)) {

						$productid = $food_id;
						$stockqty = $product_quantities[$key];
						$this->db->set('stock_qty', 'stock_qty+' . $stockqty, FALSE);
						$this->db->where('id', $productid);
						$this->db->update('ingredients');

					} else {
					
						$this->session->set_flashdata('exception',  display('please_try_again'));
						redirect("purchase/purchase/openingstock");
					}

				}else{
					// update

					$this->db->where('itemid', $food_id);
					$this->db->where('itemtype', $postData['itemtype']);

					$data = array(
						'storageqty'   		=> $storage_quantities[$key],
						'openstock'   		=> $product_quantities[$key],
						'unitprice'   		=> $product_rates[$key]
					);

					$this->db->update('tbl_openingstock', $data);

				}
			}

		
			define('STOCK_MASTER_OLD_AMOUNT', $this->db->select('total_amount')->from('tbl_openingstock_master')->get()->row()->total_amount);

			$this->db->where('id', $master_id)->update('tbl_openingstock_master', ['total_amount' => $totalPrice]);
			
			// opening balance insertion ends
			
			$capital = $this->db->select('acc_coa_id')
			->from('acc_predefined p')
			->join('acc_predefined_seeting ps', 'ps.predefined_id = p.id', 'inner')
			->where('p.id', 36)
			->where('p.is_active', TRUE)
			->where('ps.is_active', TRUE)->get()->row()->acc_coa_id;

			$inventory = $this->db->select('acc_coa_id')
			->from('acc_predefined p')
			->join('acc_predefined_seeting ps', 'ps.predefined_id = p.id', 'inner')
			->where('p.id', 13)
			->where('p.is_active', TRUE)
			->where('ps.is_active', TRUE)->get()->row()->acc_coa_id;

				$capital_cr_exist =  $this->db->select('*')->from('acc_openingbalance')->where('financial_year_id', $fiyear_id)->where('acc_coa_id', $capital)->get()->row();
				$inventory_dr_exist =  $this->db->select('*')->from('acc_openingbalance')->where('financial_year_id', $fiyear_id)->where('acc_coa_id', $inventory)->get()->row();
				

				// dr

				if(!empty($inventory_dr_exist)){

					if(STOCK_MASTER_OLD_AMOUNT == 0 && $inventory_dr_exist->debit > 0){
						$this->db->where('financial_year_id', $fiyear_id)->where('acc_coa_id', $inventory)->limit(1)->update('acc_openingbalance', ['debit'=> $inventory_dr_exist->debit + $totalPrice ]);
					}
					
					if(STOCK_MASTER_OLD_AMOUNT > 0 && $inventory_dr_exist->debit > 0){
						$this->db->where('financial_year_id', $fiyear_id)->where('acc_coa_id', $inventory)->limit(1)->update('acc_openingbalance', ['debit'=> $inventory_dr_exist->debit + $totalPrice - STOCK_MASTER_OLD_AMOUNT]);
					}



				}else{
					$opening_balance_data = [
							'financial_year_id' => $fiyear_id,
							'acc_coa_id'   => $inventory,
							'debit'        => $totalPrice,
							'credit'       => 0,
							'open_date'    => $entrydate,
							'created_by' => $saveid,
							'updated_by' => $saveid,
							'created_at' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s'),
						
					];

					$this->db->insert('acc_openingbalance', $opening_balance_data);
				}

				
				// cr
				if(!empty($capital_cr_exist)){

						$this->db->where('financial_year_id', $fiyear_id)->where('acc_coa_id', $capital)->limit(1)->update('acc_openingbalance', ['credit'=> $capital_cr_exist->credit+$totalPrice]);

						if(STOCK_MASTER_OLD_AMOUNT == 0 && $inventory_dr_exist->debit > 0){
							$this->db->where('financial_year_id', $fiyear_id)->where('acc_coa_id', $capital)->limit(1)->update('acc_openingbalance', ['credit'=> $capital_cr_exist->credit + $totalPrice]);
						}
						
						if(STOCK_MASTER_OLD_AMOUNT > 0 && $inventory_dr_exist->debit > 0){
							$this->db->where('financial_year_id', $fiyear_id)->where('acc_coa_id', $capital)->limit(1)->update('acc_openingbalance', ['credit'=> $capital_cr_exist->credit + $totalPrice-STOCK_MASTER_OLD_AMOUNT]);
						}
				
				}else{
					$opening_balance_data = [
						
							'financial_year_id' => $fiyear_id,
							'acc_coa_id'   => $capital,
							'debit'        => 0,
							'credit'       => $totalPrice,
							'open_date'    => $entrydate,
							'created_by' => $saveid,
							'updated_by' => $saveid,
							'created_at' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s'),
						
					];

					$this->db->insert('acc_openingbalance', $opening_balance_data);

				}



				
				

			
			// opening balance insertion ends
				$this->session->set_flashdata('message', display('save_successfully'));
				redirect('purchase/purchase/openingstock');
			
		}
		
	}











	public function updatestockfrm($id)
	{
		$this->permission->method('purchase', 'update')->redirect();
		$data['title'] = display('update_stock');
		$data['intinfo']   = $this->purchase_model->findBystockId($id);
		$data['ingredients'] =  $this->purchase_model->allingredient();
		$data['module'] = "production";
		$data['page']   = "openstockedit";
		$this->load->view('purchase/openstockedit', $data);
	}
	public function deleteopen($id = null)
	{
		$this->permission->module('purchase', 'delete')->redirect();

		if ($this->purchase_model->openstockitem_delete($id)) {
			$this->session->set_flashdata('message', display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception', display('please_try_again'));
		}
		redirect('purchase/purchase/openingstock');
	}
	//Bulk Food Upload
	public function bulkstockupload()
	{

		$this->permission->method('purchase', 'read')->redirect();
		$saveid = $this->session->userdata('id');
		if (!empty($_FILES["userfile"]["name"])) {
			$_FILES["userfile"]["name"];
			$path = $_FILES["userfile"]["tmp_name"];
			$upload_file = $_FILES["userfile"]["name"];
			$extension = pathinfo($upload_file, PATHINFO_EXTENSION);
			if ($extension == 'csv') {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			} elseif ($extension == 'xls') {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}
			$spreadsheet = $reader->load($_FILES["userfile"]["tmp_name"]);
			$sheetdata = $spreadsheet->getActiveSheet()->toArray();
			$datacount = count($sheetdata);

			//print_r($spreadsheet);

			if ($datacount > 1) {
				for ($i = 1; $i < $datacount; $i++) {
					$ingredientname = $sheetdata[$i][0];
					$storageunit = $sheetdata[$i][1];
					$units = $sheetdata[$i][2];
					$storagevalue = $sheetdata[$i][3];
					$openstock = $sheetdata[$i][4];
					$conversion = $sheetdata[$i][5];
					$unitprice = $sheetdata[$i][6];
					$entrydate = $sheetdata[$i][7];

					$unitinfo = $this->db->select("*")->from('unit_of_measurement')->where('uom_name', $units)->get()->row();
					if (!empty($unitinfo)) {
						$unitid = $unitinfo->id;
					} else {
						$unit = array(
							'uom_name'  		=> $units,
							'uom_short_code' 	=> $units,
							'is_active' 	    => 1,
						);
						$this->db->insert('unit_of_measurement', $unit);
						$unitid = $this->db->insert_id();
					}

					if (!empty($ingredientname)) {
						$ingredientinfo = $this->db->select('ingredient_name,id')->from("ingredients")->where('ingredient_name', $ingredientname)->get()->row();
						if (!empty($ingredientinfo)) {
							$itemid = $ingredientinfo->id;
						} else {
							$lastid=$this->db->select("*")->from('ingredients')->order_by('ingCode','desc')->get()->row();
							$sls=$lastid->ingCode;
							if(empty($sls)){
								$sl = "ING-0000";
							}else{
								$sl=$sls;
							}
							$supno=substr($sl, 3);                    
							$nextno=(int)$supno+1;
							$si_length = strlen((int)$nextno); 
							$str = '0000';
							$cutstr = substr($str, $si_length); 
							$sino = "ING".$cutstr.$nextno; 


							(object) $catData = array(
								'ingredient_name'   => $ingredientname,
								'ingCode'			=> $sino,
								'storageunit'     	=> $storageunit,
								'conversion_unit'	=> $conversion,
								'uom_id'     		=> $unitid,
								'min_stock'     	=> 5,
								'status'     		=> 0,
								'type'     			=> 1,
								'is_addons'     	=> 0,
								'is_active'			=> 1
							);
							//print_r($catData);
							$this->db->insert('ingredients', $catData);
							$itemid = $this->db->insert_id();
						}
					}

					(object) $foodData = array(
						'itemid'     		=> $itemid,
						'itemtype'     		=> 0,
						'storageqty'		=> $storagevalue,
						'openstock'     	=> $openstock,
						'unitprice'     	=> $unitprice,
						'entrydate'     	=> $entrydate,
						'createby'     		=> $saveid,
						'createdate'  		=> date('Y-m-d H:i:s')
					);
					//print_r($foodData);
					$this->db->insert('tbl_openingstock', $foodData);

					$this->db->set('stock_qty', 'stock_qty+' . $openstock, FALSE);
					$this->db->where('id', $itemid);
					$this->db->update('ingredients');
				}
			}
			$this->session->set_flashdata('message', display('save_successfully'));
			echo '<script>window.location.href = "' . base_url() . 'purchase/purchase/openingstock"</script>';
		} else {
			$this->session->set_flashdata('exception',  display('please_try_again'));
			redirect("purchase/purchase/openingstock");
		}
	}

	public function downloadstockformat()
	{

		$this->permission->method('purchase', 'read')->redirect();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Ingredient Name');
		$sheet->setCellValue('B1', 'Storage Unit');
		$sheet->setCellValue('C1', 'UnitName');
		$sheet->setCellValue('D1', 'Storage Qty');
		$sheet->setCellValue('E1', 'Ingredient Qty');
		$sheet->setCellValue('F1', 'Conversion Value');
		$sheet->setCellValue('G1', 'Unit Price');
		$sheet->setCellValue('H1', 'Date');

		$rowCount   =   2;
		$arrayfood = array(array("ingredient" => "Sugar", "storage" => "Box", "unit" => "Kilogram", "storageqty" => "1", "openstockqty" => "10", "conversionvalue" => "10","rete" => "110", "entrydate" => "2023-01-01"), array("ingredient" => "Salt", "storage" => "Pack", "unit" => "Kilogram", "storageqty" => "2","openstockqty" => "20","conversionvalue" => "10","rete" => "40", "entrydate" => "2023-01-01"));
		foreach ($arrayfood as $row) {
			$sheet->SetCellValue('A' . $rowCount, $row['ingredient'], 'UTF-8');
			$sheet->SetCellValue('B' . $rowCount, $row['storage'], 'UTF-8');
			$sheet->SetCellValue('C' . $rowCount, $row['unit'], 'UTF-8');
			$sheet->SetCellValue('D' . $rowCount, $row['storageqty'], 'UTF-8');
			$sheet->SetCellValue('E' . $rowCount, $row['openstockqty'], 'UTF-8');
			$sheet->SetCellValue('F' . $rowCount, $row['conversionvalue'], 'UTF-8');
			$sheet->SetCellValue('G' . $rowCount, $row['rete'], 'UTF-8');
			$sheet->SetCellValue('H' . $rowCount, $row['entrydate'], 'UTF-8');
			$rowCount++;
		}

		$writer = new Xlsx($spreadsheet);

		$filename = 'example.xlsx';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output'); // download file 
	}

	public function getconversion()
	{
		$ingid=$this->input->post('foodid', true);
		$ingredientinfo=$this->db->select("*")->from('ingredients')->where('id',$ingid)->order_by('ingCode','desc')->get()->row();
		$totalvalue=0;
		if(!empty($ingredientinfo)){
			$totalvalue=$ingredientinfo->conversion_unit;
		}
		$mysku=array('conversion'=>$totalvalue);
		echo json_encode($mysku);
		
	}

	public function add_po_request()
	{
		$this->permission->method('purchase', 'read')->redirect();
		$data['title']    = display('add_po_request');
		$data['getItemFoods'] = $this->purchase_model->getItemFoods();
		$data['invoice_no'] = 'PO-' . rand(100, 1000);
		$data['module'] = "purchase";
		$data['page']   = "add_po_request";
		echo Modules::run('template/layout', $data);
	}

	public function add_po_request_product()
	{
		$this->permission->method('purchase', 'read')->redirect();
		$data['title']    = display('add_po_request');
		$data['getItemFoods'] = $this->purchase_model->getItemFoods();
		$data['invoice_no'] = 'PO-' . rand(10, 100);
		$data['module'] = "purchase";
		$data['page']   = "add_po_requestproduct";
		echo Modules::run('template/layout', $data);
	}

	// ================ its for itemWiseVarient ==============
	public function itemWiseVarient()
	{
		$menu_id = $this->input->post('menu_id', TRUE);
		$getVarientByItemFood = $this->purchase_model->getVarientByItemFood($menu_id);

		echo "<option value=''>-- select one --</option>";
		foreach ($getVarientByItemFood as $value) {
			echo "<option value='$value->variantid' data-code='$value->VariantCode'>$value->variantName</option>";
		}
	}

	/*public function singleProductInfo(){
			$product_id = $this->input->post('product_id');
			$getSingleProductInfo = get_tableinfobyfield('ingredients', 'id', $product_id);
			echo json_encode($getSingleProductInfo);
		}*/
		public function singleProductInfo()
		{
			$product_id = $this->input->post('product_id');
			$date = date('Y-m-d');
			$getSingleProductInfo = get_tableinfobyfield('ingredients', 'id', $product_id);
			$currentstock = $this->purchase_model->get_stock_data($product_id, $date)[0]['closingqty'];
			$getSingleProductInfo->currentstock = $currentstock;
			echo json_encode($getSingleProductInfo);
		}

	public function poRequestSave()
	{


		$saveid = $this->session->userdata('id');
		$invoice_no = $this->input->post('invoice_no');
		$Note_To = $this->input->post('Note_To');
		$Terms_Cond = $this->input->post('Terms_Cond');
		$product_type = $this->input->post('product_type');
		$product_id = $this->input->post('product_id');
		$ingredient_code = $this->input->post('ingredient_code');
		$quantity = $this->input->post('quantity');
		$remarks = $this->input->post('remark');


		$data = array(
			'invoice_no' => $invoice_no,
			'note' => $Note_To,
			'termscondition' => $Terms_Cond,
			'date' => date('Y-m-d'),
			'status' => 1,
			'created_by' => $saveid,
			'created_date' => date('Y-m-d H:i:s'),
		);

		$this->db->insert('po_tbl', $data);

		$po_id = $this->db->insert_id();

		$invoice_no = $this->db->select('invoice_no')->from('po_tbl')->where('id', $po_id)->get()->row()->invoice_no . $po_id;
		$this->db->set('invoice_no', $invoice_no)->where('id', $po_id)->update('po_tbl');


		for ($i = 0, $n = count($product_type); $i < $n; $i++) {
			$qty = $quantity[$i];
			$remark = $remarks[$i];
			$producttype = $product_type[$i];
			$productid = $product_id[$i];
			$ingredientcode = $ingredient_code[$i];

			$poDetailsData = array(
				'po_id'       => $po_id,
				'producttype' => $producttype,
				'productid'   => $productid,
				'ingredient_code' => $ingredientcode,
				'quantity' => $qty,
				'remark'   => $remark,
				'created_date' => date('Y-m-d H:i:s'),
			);

			if (!empty($qty)) {
				$this->db->insert('po_details_tbl', $poDetailsData);
			}
		}

		// --------------------------- send to main branch  start ----------------------
		$branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();
		$url = $branchinfo->branchip . "/deliveryorder/store";

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(
				'authorization_key' => $branchinfo->authkey,
				'invoice_no' => $invoice_no,
				'is_update'  => 0,
				'order_date' => date('Y-m-d'),
				'details' => $Note_To,
				'terms_conditions' => $Terms_Cond,
				'ingredient_code' => json_encode($ingredient_code),
				'quantity' => json_encode($quantity),
				'remark' => json_encode($remarks)
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		// status update

		$responsedata = json_decode($response, true);

		if (!$responsedata['success'] == 1) {

			$this->db->where('invoice_no', $invoice_no);
			$this->db->update('po_tbl', ['status' => 6]); // 6 = false(Not Sent)

		}

		// status update

		// -------------------------- close -----------------------------------

		$this->session->set_flashdata('message', display('save_successfully'));
		redirect("purchase/purchase/po_request_list");
	}

	public function poRequestSaveproduct()
	{


		$saveid = $this->session->userdata('id');
		$invoice_no = $this->input->post('invoice_no');
		$Note_To = $this->input->post('Note_To');
		$Terms_Cond = $this->input->post('Terms_Cond');
		$product_type = 2;
		$product_id = $this->input->post('menu_id');
		$ingredient_code = $this->input->post('variant_code');
		$quantity = $this->input->post('quantity');
		$remarks = $this->input->post('remark');

		$data = array(
			'invoice_no' => $invoice_no,
			'note' => $Note_To,
			'termscondition' => $Terms_Cond,
			'date' => date('Y-m-d'),
			'status' => 1,
			'created_by' => $saveid,
			'created_date' => date('Y-m-d H:i:s'),
		);
		$this->db->insert('po_tbl', $data);
		$po_id = $this->db->insert_id();

		$invoice_no = $this->db->select('invoice_no')->from('po_tbl')->where('id', $po_id)->get()->row()->invoice_no . $po_id;
		$this->db->set('invoice_no', $invoice_no)->where('id', $po_id)->update('po_tbl');

		for ($i = 0, $n = count($product_id); $i < $n; $i++) {
			$qty = $quantity[$i];
			$remark = $remarks[$i];
			$producttype = 2;
			$productid = $product_id[$i];
			$ingredientcode = $ingredient_code[$i];

			$poDetailsData = array(
				'po_id' => $po_id,
				'producttype' => $producttype,
				'productid' => $productid,
				'variant_code' => $ingredientcode,
				'quantity' => $qty,
				'remark' => $remark,
				'created_date' => date('Y-m-d H:i:s'),
			);

			//print_r($poDetailsData);

			if (!empty($qty)) {
				$this->db->insert('po_details_tbl', $poDetailsData);
			}
		}
		// --------------------------- send to main branch  start ----------------------
		$branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();
		$url = $branchinfo->branchip . "/deliveryorder/store";

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(
				'authorization_key' => $branchinfo->authkey,
				'invoice_no' => $invoice_no,
				'is_update'  => 0,
				'order_date' => date('Y-m-d'),
				'details' => $Note_To,
				'terms_conditions' => $Terms_Cond,
				'variant_code' => json_encode($ingredient_code),
				'quantity' => json_encode($quantity),
				'remark' => json_encode($remarks)
			),
		));

		$response = curl_exec($curl);
		curl_close($curl);

		// status update
		$responsedata = json_decode($response, true);

		if (!$responsedata['success'] == 1) {

			$this->db->where('invoice_no', $invoice_no);
			$this->db->update('po_tbl', ['status' => 6]); // 6 = false(Not Sent)

		}
		// status update

		// -------------------------- close -----------------------------------

		$this->session->set_flashdata('message', display('save_successfully'));
		redirect("purchase/purchase/po_request_list");
	}

	public function po_request_list()
	{
		$this->permission->method('purchase', 'read')->redirect();
		$data['title']    = display('po_request_list');
		// $data['intinfo']   = $this->purchase_model->findById($id);
		#-------------------------------#       
		#
		#pagination starts
		#
		$config["base_url"] = base_url('purchase/purchase/po_request_list');
		$config["total_rows"]  = $this->purchase_model->poCount();
		$config["per_page"]    = 25;
		$config["uri_segment"] = 4;
		$config["last_link"] = "Last";
		$config["first_link"] = "First";
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Prev';
		$config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		/* ends of bootstrap */
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data["getPoList"] = $this->purchase_model->poList($config["per_page"], $page);
		$data["links"] = $this->pagination->create_links();
		$data['pagenum'] = $page;
		// $data['items']   = $this->purchase_model->ingrediant_dropdown();
		// $data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
		// $data['supplier']   = $this->purchase_model->supplier_dropdown();
		$settinginfo = $this->purchase_model->settinginfo();
		$data['currency'] = $this->purchase_model->currencysetting($settinginfo->currency);

		$data['module'] = "purchase";
		$data['page']   = "po_request_list";
		echo Modules::run('template/layout', $data);
	}

	public function poviewdetails($id = null)
	{
		$this->permission->method('purchase', 'update')->redirect();
		$data['title'] = "View PO";
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$potype = $this->db->select('producttype')->from("po_details_tbl")->where('po_id', $id)->get()->row();
		$data['currency'] = $this->purchase_model->currencysetting($settinginfo->currency);
		$data['getItemFoods'] = $this->purchase_model->getItemFoods();
		$data['getPOData']   = $this->purchase_model->getPOData($id);
		$data['getPODetailsData']   = $this->purchase_model->getPODetailsData($id);
		$data['module'] = "purchase";
		$data['page']   = "viewpodetails";
		echo Modules::run('template/layout', $data);
	}

	public function poReqUpdateFrm($id)
	{

		$this->permission->method('purchase', 'update')->redirect();
		$data['title'] = display('edit_po_request');
		$settinginfo = $this->purchase_model->settinginfo();
		$potype = $this->db->select('producttype')->from("po_details_tbl")->where('po_id', $id)->get()->row();
		$data['setting'] = $settinginfo;

		$data['getItemFoods'] = $this->purchase_model->getItemFoods();

		$data['getPOData']   = $this->purchase_model->getPOData($id);
		$data['getPODetailsData']   = $this->purchase_model->getPODetailsData($id);
		$data['module'] = "purchase";


		$ingredient_code = $this->db->select('*')->from('po_details_tbl')->where('po_id', $id)->get()->row()->ingredient_code;



		// if($potype->producttype==2){
		if ($ingredient_code == null) {
			$data['page']   = "edit_po_request_product";
			$data['ptype'] = 2;
		} else {
			$data['page']   = "edit_po_request";
			$data['ptype'] = '';
		}

		echo Modules::run('template/layout', $data);
	}

	public function poRequestUpdate()
	{

		$po_id = $this->input->post('po_id');
		$invoice_no = $this->input->post('invoice_no');
		$saveid = $this->session->userdata('id');
		$Note_To = $this->input->post('Note_To');
		$Terms_Cond = $this->input->post('Terms_Cond');
		$product_type = $this->input->post('product_type');
		$product_id = $this->input->post('product_id');
		$quantity = $this->input->post('quantity');
		$remarks = $this->input->post('remark');
		$po_detail_id = $this->input->post('po_detail_id');
		$ingredient_code = $this->input->post('ingredient_code');

		// --------------------------- send to main branch  start ----------------------
		$branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();
		$url = $branchinfo->branchip . "/deliveryorder/store";

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(
				'authorization_key' => $branchinfo->authkey,
				'invoice_no' => $invoice_no,
				'is_update'  => 1,
				'order_date' => date('Y-m-d'),
				'details' => $Note_To,
				'terms_conditions' => $Terms_Cond,
				'ingredient_code' => json_encode($ingredient_code),
				'quantity' => json_encode($quantity),
				'remark' => json_encode($remarks)
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		$responsedata = json_decode($response, true);

		// -------------------------- close -----------------------------------

		if ($responsedata['success'] == 1) {

			$data = array(
				'note' => $Note_To,
				'termscondition' => $Terms_Cond,
				'date' => date('Y-m-d'),
				'created_by' => $saveid,
				'created_date' => date('Y-m-d H:i:s'),
			);

			$this->db->where('id', $po_id)->update('po_tbl', $data);

			for ($i = 0, $n = count($product_type); $i < $n; $i++) {
				$qty = $quantity[$i];
				$remark = $remarks[$i];
				$producttype = $product_type[$i];
				$productid = $product_id[$i];
				$ingredientcode = $ingredient_code[$i];
				$po_detailid = $po_detail_id[$i];

				$poDetailsData = array(
					'po_id' => $po_id,
					'producttype' => $producttype,
					'productid' => $productid,
					'ingredient_code' => $ingredientcode,
					'quantity' => $qty,
					'remark' => $remark,
					'created_date' => date('Y-m-d H:i:s'),
				);

				if ($po_detailid) {
					$this->db->where('id', $po_detailid)->update('po_details_tbl', $poDetailsData);
				} else {
					$this->db->insert('po_details_tbl', $poDetailsData);
				}
			}

			$this->session->set_flashdata('message', display('updated_successfully'));
			redirect("purchase/purchase/po_request_list");
		} else {

			$this->session->set_flashdata('exception',  display('please_try_again'));
			redirect("purchase/purchase/po_request_list");
		}
	}

	public function poRequestUpdateproduct()
	{

		$po_id = $this->input->post('po_id');
		$invoice_no = $this->input->post('invoice_no');
		$saveid = $this->session->userdata('id');
		$Note_To = $this->input->post('Note_To');
		$Terms_Cond = $this->input->post('Terms_Cond');
		$product_type = 2;
		$product_id = $this->input->post('menu_id');
		$quantity = $this->input->post('quantity');
		$remarks = $this->input->post('remark');

		$po_detail_id = $this->input->post('po_detail_id');




		$ingredient_code = $this->input->post('variant_code');

		// --------------------------- send to main branch  start ----------------------
		$branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();
		$url = $branchinfo->branchip . "/deliveryorder/store";

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(
				'authorization_key' => $branchinfo->authkey,
				'invoice_no' => $invoice_no,
				'is_update'  => 1,
				'order_date' => date('Y-m-d'),
				'details' => $Note_To,
				'terms_conditions' => $Terms_Cond,
				'variant_code' => json_encode($ingredient_code),
				'quantity' => json_encode($quantity),
				'remark' => json_encode($remarks)
			),
		));

		$response = curl_exec($curl);
		curl_close($curl);

		// status update
		$responsedata = json_decode($response, true);

		// -------------------------- close -----------------------------------



		if ($responsedata['success'] == 1) {

			$data = array(
				'note' => $Note_To,
				'termscondition' => $Terms_Cond,
				'date' => date('Y-m-d'),
				'created_by' => $saveid,
				'created_date' => date('Y-m-d H:i:s'),
			);

			$this->db->where('id', $po_id)->update('po_tbl', $data);

			for ($i = 0, $n = count($product_id); $i < $n; $i++) {

				$qty = $quantity[$i];
				$remark = $remarks[$i];
				$producttype = $product_type[$i];
				$productid = $product_id[$i];
				$po_detailid = $po_detail_id[$i];
				$ingredientcode = $ingredient_code[$i];


				$poDetailsData = array(
					'po_id' => $po_id,
					'producttype' => 2,
					'productid' => $productid,
					'variant_code' => $ingredientcode,
					'quantity' => $qty,
					'remark' => $remark,
					'created_date' => date('Y-m-d H:i:s'),
				);


				if ($po_detailid) {

					$this->db->where('id', $po_detailid)->update('po_details_tbl', $poDetailsData);
				} else {

					$this->db->insert('po_details_tbl', $poDetailsData);
				}
			}


			$this->session->set_flashdata('message', display('updated_successfully'));
			redirect("purchase/purchase/po_request_list");
		} else {

			$this->session->set_flashdata('exception',  display('please_try_again'));
			redirect("purchase/purchase/po_request_list");
		}
	}

	public function poReqResend($id)
	{

		$po = $this->db->select('*')->from('po_tbl')->where('id', $id)->get()->row();
		$po_details = $this->db->select('*')->from('po_details_tbl')->where('po_id', $id)->get()->result();

		$ingredients = [];
		$quantity = [];
		$remark = [];

		foreach ($po_details as $pod) {

			$ingredients[] = $pod->ingredient_code;
			$quantity[] = $pod->quantity;
			$remark[] = $pod->remark;
		}



		$branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();
		$url = $branchinfo->branchip . "/deliveryorder/store";



		if ($po_details[0]->ingredient_code == null) {

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => array(
					'authorization_key' => $branchinfo->authkey,
					'invoice_no' => $po->invoice_no,
					'is_update'  => 0,
					'order_date' => date('Y-m-d'),
					'details' => $po->note,
					'terms_conditions' => $po->termscondition,
					'variant_code' => json_encode($ingredients),
					'quantity' => json_encode($quantity),
					'remark' => json_encode($remark)
				),
			));

			$response = curl_exec($curl);
			curl_close($curl);
		} else {
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => array(
					'authorization_key' => $branchinfo->authkey,
					'invoice_no' => $po->invoice_no,
					'is_update'  => 0,
					'order_date' => date('Y-m-d'),
					'details' => $po->note,
					'terms_conditions' => $po->termscondition,
					'ingredient_code' => json_encode($ingredients),
					'quantity' => json_encode($quantity),
					'remark' => json_encode($remark)
				),
			));

			$response = curl_exec($curl);
			curl_close($curl);
		}


		$responsedata = json_decode($response, true);

		if (!$responsedata['success'] == 1) {

			$this->db->where('invoice_no', $po->invoice_no);
			$this->db->update('po_tbl', ['status' => 6]); // 6 = false(Not Sent)

			$this->session->set_flashdata('exception',  display('please_try_again'));
			redirect("purchase/purchase/po_request_list");
		} else {

			$this->session->set_flashdata('message', display('save_successfully'));
			redirect("purchase/purchase/po_request_list");
		}
	}

	public function deleteItemRow()
	{
		$po_detail_id = $this->input->post('po_detail_id');
		if ($po_detail_id) {
			$this->db->where('id', $po_detail_id)->delete('po_details_tbl');
			echo 'deleted done';
		}
	}

	public function poReqDelete($po_id)
	{
		if ($po_id) {
			$this->db->where('id', $po_id)->delete('po_tbl');
			$this->db->where('po_id', $po_id)->delete('po_details_tbl');

			$this->session->set_flashdata('message', display('deleted_successfully'));
			redirect("purchase/purchase/po_request_list");
		}
	}

	public function poRequestReceived($id)
	{

		$po_details_id   = $this->input->post('po_details_id');
		$ingredient_id   = $this->input->post('ingredient_id');
		$ingredient_code = $this->input->post('ingredient_code');
		$variant_code    = $this->input->post('variant_code');
		$received_qty    = $this->input->post('received_qty');


		$poRequestInfo = get_tableinfobyfield('po_tbl', 'id', $id);
		$branchinfo = $this->db->select("*")->from('tbl_mainbranchinfo')->get()->row();

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $branchinfo->branchip . '/deliveryorder/received',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array(

				'authorization_key' => $branchinfo->authkey,
				'invoice_no' => $poRequestInfo->invoice_no,
				'status' => '5',

				'ingredient_code' => json_encode($ingredient_code),
				'variant_code'    => json_encode($variant_code),
				'received_qty'    => json_encode($received_qty),

			),
		));

		$response = curl_exec($curl);


		curl_close($curl);
		$result = json_decode($response);

		if ($result->success == 1) {

			$poReceivedData = array(
				'status' => 5,
			);

			$this->db->where('id', $id)->update('po_tbl', $poReceivedData);


			for ($i = 0; $i < count($ingredient_id); $i++) {


				$data['received_quantity']  = $received_qty[$i];

				$this->db->where('po_id', $id)->where('productid', $po_details_id[$i])->update('po_details_tbl', $data);
			}

			$this->session->set_flashdata('message', "Received successfully!");
			redirect("purchase/purchase/po_request_list");
		} else {
			$this->session->set_flashdata('exception', "Please Try Again!");
			redirect("purchase/purchase/po_request_list");
		}
	}


	public function purchase_return_edit($id)
	{

		$this->permission->method('purchase', 'read')->redirect();
		$data['title'] = display('purchase_return');
		$data['returnitem'] = $this->purchase_model->edit_item($id);
		$data['module'] = "purchase";
		$data['page']   = "purchase_return_edit";
		echo Modules::run('template/layout', $data);
	}

	public function purchase_return_update($id)
	{

		$payttpe =  $this->input->post('paytype');

		$qtycheck =  $this->input->post('total_qntt');
		$haystack = count($qtycheck);
		$emptycheck = 0;
		for ($i = 0; $i <= $haystack; $i++) {
			if (!empty($qtycheck[$i])) {
				$emptycheck += 1;
			}
		}

		if ($payttpe == 2) {
			$this->form_validation->set_rules('bank', 'Select Bank', 'required');
		}
		if ($emptycheck == 0) {
			$this->form_validation->set_rules('total_qntt', 'Select quantity', 'required');
		}
		if ($this->form_validation->run()) {
			redirect("purchase/purchase/return_form");
		} else {

			if ($this->purchase_model->pur_return_update($id)) {
				$this->session->set_flashdata('message', display('save_successfully'));
				redirect('purchase/purchase/return_form/');
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
		}
	}
	/*public function assigninventory(){
		   $this->permission->method('purchase','read')->redirect();
		   $data['title'] = display('purchase_add');
		   #-------------------------------#
		   $saveid=$this->session->userdata('supid');
		   $data['intinfo']="";
		   $settinginfo = $this->purchase_model->settinginfo();
		   $data['setting']=$settinginfo;
		   $data['currency']=$this->purchase_model->readsingle('*', 'currency', array('currencyid' => $settinginfo->currency));
		   $data['supplier']   = $this->purchase_model->supplier_dropdown();
		   $data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
		   $data['list']   = $this->db->select("*")->from('tbl_trams_condition')->get()->row(); 
		   $data['module'] = "purchase";
		   $data['page']   = "addinventoryassign";   
		   echo Modules::run('template/layout', $data);  
		}*/
	//For Supplier PO
	public function add_supplier_po_request()
	{


		$this->permission->method('purchase', 'read')->redirect();
		$data['title']    = display('add_po_request');
		$data['getItemFoods'] = $this->purchase_model->getItemFoods();
		$data['supplier']   = $this->purchase_model->supplier_dropdown();
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->readsingle('*', 'currency', array('currencyid' => $settinginfo->currency));
		$data['module'] = "purchase";
		$data['page']   = "add_supplier_po_request";
		echo Modules::run('template/layout', $data);
	}


	public function supplier_po_request_save()
	{

		// $this->form_validation->set_rules('invoice_no', 'Invoice Number', 'required|max_length[50]');
		$this->form_validation->set_rules('purchase_date', 'Purchase Date', 'required');
		$saveid = $this->session->userdata('id');

		if ($this->form_validation->run()) {
			// $this->permission->method('purchase', 'create')->redirect();

			$logData = array(
				'action_page'         => "Add Supplier Po Request",
				'action_done'        => "Insert Data",
				'remarks'             => "Supplier Po Created",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);

			if ($this->purchase_model->supplier_po_request_save()) {
				$this->logs_model->log_recorded($logData);
				$this->session->set_flashdata('message', display('save_successfully'));
				redirect('purchase/purchase/supplier_po_request_list');
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("purchase/purchase/add_supplier_po_request");
		} else {
			redirect("purchase/purchase/add_supplier_po_request");
		}
	}

	public function supplier_po_request_list()
	{
		$this->permission->method('purchase', 'read')->redirect();
		$data['title']    = display('po_request_list');
		// $data['intinfo']   = $this->purchase_model->findById($id);
		#-------------------------------#       
		#
		#pagination starts
		#
		$config["base_url"] = base_url('purchase/purchase/supplier_po_request_list');
		$config["total_rows"]  = $this->purchase_model->supplier_poCount();
		$config["per_page"]    = 25;
		$config["uri_segment"] = 4;
		$config["last_link"] = "Last";
		$config["first_link"] = "First";
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Prev';
		$config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		/* ends of bootstrap */
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data["purchaselist"] = $this->purchase_model->supplier_poList($config["per_page"], $page);
		$data["links"] = $this->pagination->create_links();
		$data['pagenum'] = $page;

		// dd($data["purchaselist"]);

		$data['items']   = $this->purchase_model->ingrediant_dropdown();
		$data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
		$data['supplier']   = $this->purchase_model->supplier_dropdown();
		$settinginfo = $this->purchase_model->settinginfo();
		$data['currency'] = $this->purchase_model->currencysetting($settinginfo->currency);
		$data['module'] = "purchase";
		$data['page']   = "supplier_po_request_list";
		echo Modules::run('template/layout', $data);
	}

	public function supplier_po_request_view($id)
	{
		// $this->permission->method('purchase', 'update')->redirect();
		$data['title'] = display('edit_po_request');
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->readsingle('*', 'currency', array('currencyid' => $settinginfo->currency));

		$data['supplier']   = $this->purchase_model->supplier_dropdown();
		$data['purchaseinfo']   = $this->purchase_model->supplier_po_findById($id);
		$data['iteminfo']       = $this->purchase_model->supplier_po_iteminfo($id);
		$data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
		$allbank = $this->db->select("*")->from('tbl_bank')->get()->result();
		$data['banklist']       = $allbank;
		$data['module'] = "purchase";
		$data['page']   = "poinvoice";
		echo Modules::run('template/layout', $data);
	}

	public function supplier_po_request_edit($id)
	{
		// $this->permission->method('purchase', 'update')->redirect();
		$data['title'] = display('edit_po_request');
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->readsingle('*', 'currency', array('currencyid' => $settinginfo->currency));

		$data['supplier']   = $this->purchase_model->supplier_dropdown();
		$data['purchaseinfo']   = $this->purchase_model->supplier_po_findById($id);
		$data['iteminfo']       = $this->purchase_model->supplier_po_iteminfo($id);
		$data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
		$allbank = $this->db->select("*")->from('tbl_bank')->get()->result();
		$data['banklist']       = $allbank;
		$data['module'] = "purchase";
		$data['page']   = "edit_supplier_po_request";
		echo Modules::run('template/layout', $data);
	}

	public function supplier_po_request_convert($id)
	{
		// $this->permission->method('purchase', 'update')->redirect();
		$data['title'] = display('edit_po_request');
		$data['purchaseinfo'] = $purchaseinfo = $this->purchase_model->supplier_po_findById($id);
		// check if the purchase request already converted to purchase
		if($purchaseinfo->purchase_status == 1){

			$this->session->set_flashdata('exception',  display('purchase_order_already_converted'));
			redirect("purchase/purchase/supplier_po_request_list");
		}
		// End
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->readsingle('*', 'currency', array('currencyid' => $settinginfo->currency));

		$data['supplier']   = $this->purchase_model->supplier_dropdown();
		$data['iteminfo']       = $this->purchase_model->supplier_po_iteminfo($id);
		$data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
		$allbank = $this->db->select("*")->from('tbl_bank')->get()->result();
		$data['banklist']       = $allbank;

		// dd($data);

		$data['module'] = "purchase";
		$data['page']   = "purchase_request_convert";
		echo Modules::run('template/layout', $data);
	}

	public function supplier_po_request_update()
	{
		// $this->form_validation->set_rules('invoice_no', 'Invoice Number', 'required|max_length[50]');
		$this->form_validation->set_rules('purchase_date', 'Purchase Date', 'required');
		$saveid = $this->session->userdata('id');
		$purID = $this->input->post('purID');

		if ($this->form_validation->run()) {
			$this->permission->method('purchase', 'update')->redirect();
			$logData = array(
				'action_page'         => "Update Supplier Po",
				'action_done'        => "Update Data",
				'remarks'             => "Item Po Request Updated",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);
			//echo "dfdsf";
			if ($this->purchase_model->supplier_po_request_update()) {
				$this->logs_model->log_recorded($logData);
				$this->session->set_flashdata('message', display('update_successfully'));
				redirect('purchase/purchase/supplier_po_request_list');
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("purchase/purchase/supplier_po_request_edit/" . $purID);
		} else {
			redirect("purchase/purchase/supplier_po_request_edit/" . $purID);
		}
	}

	public function po_request_send_purchase()
	{
		$id = $this->input->post('id');
		$itemvalue = $this->input->post('itemvalue');
		if ($itemvalue == 1) {
			if ($info = $this->purchase_model->poRequest_item_purchase($id)) {
				$data = array(
					'purchase_status' => 1
				);
				$this->db->where('purID', $id)->update('supplier_po_request', $data);

				$data = array(
					'success' => true,
					'message' => 'Purchase Successfully',
					'return_url' => base_url('purchase/purchase/updateintfrm/'.$info),

				);
				echo json_encode($data);
			}
		}
	}

	//Physical Stock  
	public function physical_stock()
	{
		$this->permission->method('purchase', 'read')->redirect();
		$data['title'] = 'Pysical Stock Entry';
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['ingredients'] =  $this->purchase_model->allingredient();
		$data['module'] = "purchase";
		$data['page']   = "physical_stock";
		$data['physical_stocks'] = $this->db->select('ps.*, i.ingredient_name')
			->from('tbl_physical_stock ps')
			->join('ingredients i', 'ps.ingredient_id = i.id', 'inner')
			->get()
			->result();
		echo Modules::run('template/layout', $data);
	}

	public function physical_stock_entry()
	{
		$this->permission->method('purchase', 'create')->redirect();

		$saveid = $this->session->userdata('id');
		$data = [];
		$data['ingredient_id'] = $this->input->post('foodid');
		$data['qty']     	   = $this->input->post('qty');
		$data['unit_price']    = $this->input->post('unitprice');
		$data['total_price']   = $this->input->post('unitprice') * $this->input->post('qty');
		$data['entry_date']    = $this->input->post('entrydate');
		$data['created_by']    = $saveid;
		$data['created_at']    = date('Y-m-d H:i:s');

		$this->db->insert('tbl_physical_stock', $data);

		$this->session->set_flashdata('message', display('save_successfully'));

		redirect("purchase/purchase/physical_stock");
	}

	public function physical_stock_edit($id)
	{
		$this->permission->method('purchase', 'update')->redirect();
		$data['title'] = 'Update Physical Stock';
		$data['intinfo']   = $this->purchase_model->findByPhysicalStockId($id);
		$data['ingredients'] =  $this->purchase_model->allingredient();
		$data['module'] = "production";
		$data['page']   = "physical_stock_edit";
		$this->load->view('purchase/physical_stock_edit', $data);
	}

	public function physical_stock_update()
	{

		$this->permission->method('purchase', 'update')->redirect();

		$saveid = $this->session->userdata('id');
		$data = [];
		$id          = $this->input->post('id');
		$data['ingredient_id'] = $this->input->post('foodid');
		$data['qty']     	   = $this->input->post('qty');
		$data['unit_price']    = $this->input->post('unitprice');
		$data['total_price']   = $this->input->post('unitprice') * $this->input->post('qty');
		$data['entry_date']    = $this->input->post('entrydate');
		$data['created_by']    = $saveid;
		$data['created_at']    = date('Y-m-d H:i:s');

		$this->db->where('id', $id);
		$this->db->update('tbl_physical_stock', $data);
		$this->session->set_flashdata('message', display('update_successfully'));
		redirect("purchase/purchase/physical_stock");
	}

	public function physical_stock_delete($id = null)
	{
		$this->permission->module('purchase', 'delete')->redirect();

		$this->db->where('id', (int)$id)->delete('tbl_physical_stock');


		if ($this->db->affected_rows()) {
			$this->session->set_flashdata('message', display('delete_successfully'));
		} else {
			$this->session->set_flashdata('exception', display('please_try_again'));
		}

		redirect('purchase/purchase/physical_stock');
	}

	public function get_user_by_kitchen()
	{
		$kitchen_id = $this->input->post('kitchen_id');
		$user = $this->purchase_model->get_user_by_kitchen($kitchen_id);
		header('Content-Type: application/json');
		echo json_encode($user);
	}

	public function get_items_by_product_type()
	{

		$product_type = $this->input->post('product_type');

		if ($product_type == 1 || $product_type == 2) {
			$products = $this->purchase_model->get_items_by_product_type($product_type);
			$select = array('id' => 0, 'ingredient_name' => 'Select');
			array_unshift($products, $select);
		}

		if ($product_type == 3) {
			$products = $this->purchase_model->get_items_by_product_type_addons($product_type);
			$select = array('id' => 0, 'ingredient_name' => 'Select');
			array_unshift($products, $select);
		}

		header('Content-Type: application/json');
		echo json_encode($products);
	}
	
	
	public function get_items_by_product_type_consumption()
	{
		$product_type = $this->input->post('product_type');

		if ($product_type == 1 || $product_type == 2) {
			$products = $this->purchase_model->get_items_by_product_type_consumption($product_type);
			$select = array('id' => 0, 'ingredient_name' => 'Select');
			array_unshift($products, $select);
		}

		if ($product_type == 3) {
			$products = $this->purchase_model->get_items_by_product_type_addons_consumption($product_type);
			$select = array('id' => 0, 'ingredient_name' => 'Select');
			array_unshift($products, $select);
		}


		header('Content-Type: application/json');
		echo json_encode($products);
	}

	public function get_stock_data()
	{
		$product_id = $this->input->post('product_id');
		$date = $this->input->post('date');
		$stock_data = $this->purchase_model->get_stock_data($product_id, $date);
		header('Content-Type: application/json');
		echo json_encode($stock_data);
	}

	public function get_kitchen_stock_data()
	{
		$kitchen_id = $this->input->post('kitchen_id');
		$product_id = $this->input->post('product_id');
		$stock_data = $this->purchase_model->kitchen_stock($product_id, $kitchen_id);
		header('Content-Type: application/json');
		echo json_encode($stock_data);
	}

	public function getKitchenStockData()
	{

		$product_id = $this->input->post('product_id');
		$kitchen_id = $this->input->post('kitchen_id');

		$stock_data = $this->purchase_model->kitchen_stock($product_id, $kitchen_id);

		header('Content-Type: application/json');
		echo json_encode($stock_data);
	}
	// common and dependent functions ends here...


	// kitchen assign part starts here...

	public function assignInventoryList()
	{
		$this->permission->method('purchase', 'read')->redirect();
		$data['title']  = display('assigned_kitchen');

		$logged_in_user = $this->session->userdata('id');
		$check = $this->db->select('is_admin')->from('user')->where('id', $logged_in_user)->get()->row()->is_admin;

		if ($check == 1) {
			$data['list'] = $this->purchase_model->assignedListKitchenWise();
		} else {
			$data['list'] = $this->purchase_model->assignedListForKitchenUser($logged_in_user);
		}


		$data['module'] = "purchase";
		$data['page']   = "inventory_assign_list_kitchen_wise";
		echo Modules::run('template/layout', $data);
	}

	public function assigninventory()
	{
		$this->permission->method('purchase', 'create')->redirect();
		$data['title'] = display('assign_kitchen');
		#-------------------------------#
		$saveid = $this->session->userdata('id');

		$data['intinfo'] = "";
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->readsingle('*', 'currency', array('currencyid' => $settinginfo->currency));

		// new code by MKar starts here...
		$data['kitchen']   = $this->purchase_model->kitchen_dropdown();
		$data['user']   = $this->purchase_model->user_dropdown();
		// new code by MKar ends here...

		$data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
		$data['list']   = $this->db->select("*")->from('tbl_trams_condition')->get()->row();
		$data['module'] = "purchase";
		$data['page']   = "addinventoryassign";
		echo Modules::run('template/layout', $data);
	}

	public function editAssignInventory($id)
	{
		$this->permission->method('purchase', 'update')->redirect();

		$data['title']   = display('update_assigned_inventory');
		$data['list']    = $this->purchase_model->editAssigned($id);
		$data['module']  = "purchase";
		$data['assign_inventory_main_data'] = $this->db->select('*')->from('assign_inventory_main')->where('id', $id)->get()->row();
		$data['kitchen'] = $this->db->select('*')->from('tbl_kitchen')->get()->result();
		$data['user']    = $this->db->select('*')->from('user')->get()->result();
		//$data['product'] = $this->db->select('*')->from('ingredients')->get()->result();
		$data['page']    = "editinventoryassign";
		echo Modules::run('template/layout', $data);
	}

	public function storeAssignInvetory()
	{

		$this->permission->method('purchase', 'create')->redirect();

		$this->db->trans_start();

		try {

			$userId      = $this->input->post('userid');
			$kitchenId   = $this->input->post('kitchenid');
			$assignDate  = date('Y-m-d', strtotime($this->input->post('date')));
			$productType = $this->input->post('product_type');
			$productId   = $this->input->post('product_id');
			$productQty  = $this->input->post('product_quantity');
			$kitchennote = $this->input->post('kitchennote');
			$assignedBy  = $this->session->userdata('id');

			// assign_inventory_main insertion starts...

			$assign_main_data = [
				'code'       => $this->purchase_model->insert_new_record(),
				'kitchen_id' => $kitchenId,
				'user_id'    => $userId,
				'date'       => $assignDate,
				'kitchennote' => $kitchennote
			];

			$this->db->insert('assign_inventory_main', $assign_main_data);

			$last_inserted_id = $this->db->insert_id();

			// assign_inventory_main insertion ends...

			// assign_inventory insertion starts...
			for ($i = 0; $i < count($productType); $i++) {

				// insert
				$data['assign_inventory_main_id'] = $last_inserted_id;
				$data['product_type']  = $productType[$i];
				$data['product_id']    = $productId[$i];
				$data['product_qty']   = $productQty[$i];
				$data['assigned_date'] = $assignDate;
				$data['assigned_by']   = $assignedBy;

				$check = $this->db->insert('assign_inventory', $data);
				$kitchen_setup = $this->purchase_model->kitchen_stock_store($data, $kitchenId,  $type = 0);
			}
			// assign_inventory insertion ends...

			$this->db->trans_commit();

			$this->session->set_flashdata('message', display('save_successfully'));
			redirect('purchase/purchase/assigninventoryList');
		} catch (Exception $e) {
			$this->db->trans_rollback();
			// echo 'Exception: ' . $e->getMessage();
			$this->session->set_flashdata('exception',  display('please_try_again'));
		}
	}

	public function updateAssignInventory($id)
	{

		$this->permission->method('purchase', 'update')->redirect();

		$this->db->trans_start();

		try {

			// assign_inventory_main table update starts...
			$data['user_id']     = $this->input->post('user_id');
			$data['kitchen_id']  = $this->input->post('kitchen_id');
			$data['date']        = date('Y-m-d', strtotime($this->input->post('date')));
			$data['kitchennote'] = $this->input->post('kitchennote');

			$this->db->where('id', $id)->update('assign_inventory_main', $data);

			// assign_inventory_main table update ends...

			// update assign_inventory starts...
			$productType = $this->input->post('product_type');
			$productId   = $this->input->post('product_id');
			$productQty  = $this->input->post('product_quantity');
			$assignedBy  = $this->session->userdata('id');

			for ($i = 0; $i < count($productType); $i++) {
				$idata['assign_inventory_main_id'] = $id;
				$idata['product_type']  = $productType[$i];
				$idata['product_id']    = $productId[$i];
				$idata['product_qty']   = $productQty[$i];
				$idata['assigned_date'] = $data['date'];
				$idata['assigned_by']   = $assignedBy;

				$check1 = $this->db->where('assign_inventory_main_id', $id)->where('product_id', $productId[$i])->update('assign_inventory', $idata);
				$kitchen_setup1 = $this->purchase_model->kitchen_stock_update($idata, $data['kitchen_id'], $type = 0);
			}


			// update assign_inventory ends...

			// new data insertion starts...
			$newProductType     = $this->input->post('new_product_type');
			$newProductId       = $this->input->post('new_product_id');
			$newProductQuantity = $this->input->post('new_product_quantity');

			if ($newProductType || $newProductId || $newProductQuantity) {

				for ($i = 0; $i < count($newProductType); $i++) {

					$ndata['assign_inventory_main_id'] = $id;
					$ndata['product_type']  = $newProductType[$i];
					$ndata['product_id']    = $newProductId[$i];
					$ndata['product_qty']   = $newProductQuantity[$i];
					$ndata['assigned_date'] = $data['date'];
					$ndata['assigned_by']   = $assignedBy;

					$check2 = $this->db->insert('assign_inventory', $ndata);
					$kitchen_setup2 = $this->purchase_model->kitchen_stock_store($ndata,  $data['kitchen_id'], $type = 0);
				}
			}
			// new data insertion ends...

			$this->db->trans_commit();

			$this->session->set_flashdata('message', display('save_successfully'));
			redirect('purchase/purchase/assignInventoryList');
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo 'Exception: ' . $e->getMessage();
			$this->session->set_flashdata('exception',  display('please_try_again'));
		}
	}


	public function deleteKitchenAssign($id)
	{
		$this->permission->method('purchase', 'delete')->redirect();
		$this->db->where('id', $id)->delete('assign_inventory_main'); // main
		$this->db->where('assign_inventory_main_id', $id)->delete('assign_inventory'); // details
		$this->db->where('assign_inventory_main_id', $id)->delete('kitchen_stock_new'); // stock
		$this->session->set_flashdata('message', 'Assign Kitchen data has been deleted');
		redirect('purchase/purchase/assigninventoryList');
	}

	public function updateStatusAssignInventory($id)
	{

		$this->db->where('id', $id)->update('assign_inventory_main', ['status' => 1]);
		$this->session->set_flashdata('message', 'Stock is ready to use');
		redirect('purchase/purchase/assigninventoryList');
	}

	// kitchen assign part ends here...


	// reedem part starts...

	public function reedemList()
	{
		$this->permission->method('purchase', 'read')->redirect();
		$data['title']  = display('reedem_list');
		$logged_in_user = $this->session->userdata('id');
		$check = $this->db->select('is_admin')->from('user')->where('id', $logged_in_user)->get()->row()->is_admin;

		if ($check == 1) {
			$data['list']   = $this->purchase_model->reedem_list();
		} else {
			$data['list']   = $this->purchase_model->reedem_list_for_kitchen_user($logged_in_user);
		}

		$data['module'] = "purchase";
		$data['page']   = "reedemlist";
		echo Modules::run('template/layout', $data);
	}

	public function addReedem()
	{
		$this->permission->method('purchase', 'create')->redirect();
		$data['title']    = display('add_reedem');
		$data['kitchen']  = $this->db->select('*')->from('tbl_kitchen')->get()->result();
		$data['items']    = $this->purchase_model->item_list();
		$data['module']   = "purchase";
		$data['page']     = "add_reedem";
		echo Modules::run('template/layout', $data);
	}

	public function editReedem($id)
	{

		$this->permission->method('purchase', 'update')->redirect();
		$data['title']   = display('edit_reedem');
		$data['kitchen'] = $this->db->select('*')->from('tbl_kitchen')->get()->result();
		$data['user'] = $this->db->select('*')->from('user')->get()->result();
		//$data['product'] = $this->db->select('*')->from('ingredients')->get()->result();


		$data['list']        = $this->purchase_model->edit_reedem_list($id);
		$data['reedem_data'] = $this->db->select('*')->from('tbl_reedem')->where('id', $id)->get()->row();

		$data['module']  = "purchase";
		$data['page']    = "editreedem";
		echo Modules::run('template/layout', $data);
	}

	public function storeReedem()
	{

		$this->permission->method('purchase', 'create')->redirect();

		$this->db->trans_start();

		try {

			// insert to tbl_reedem insertion or update starts...
			$data['kitchen_id']  = $this->input->post('kitchen_id');
			$data['code']        = $this->purchase_model->insert_new_record_reedem();
			$data['date']        = date('Y-m-d', strtotime($this->input->post('date')));
			$data['kitchennote'] = $this->input->post('kitchennote');
			$data['reedem_by']   = $this->input->post('user_id');

			$this->db->insert('tbl_reedem', $data);
			$last_inserted_id = $this->db->insert_id();
			// insert to tbl_reedem insertion or update ends...

			// inserting data to reedem details starts...

			$productType = $this->input->post('product_type');
			$productId   = $this->input->post('product_id');


			$stockQty     = $this->input->post('stock_quantity');
			$remainingQty = $this->input->post('remaining_quantity');

			if ($remainingQty) {


				if (count($stockQty) === count($remainingQty)) {
					$count = count($stockQty);
					for ($i = 0; $i < $count; $i++) {
						$usedQty[$i] = $stockQty[$i] - $remainingQty[$i];
					}
				}
			} else {
				$usedQty = $this->input->post('used_quantity');
				$wastageQty  = $this->input->post('wastage_quantity');
				$expiredQty  = $this->input->post('expired_quantity');
			}

			for ($i = 0; $i < count($productType); $i++) {

				// insert
				$rdata['reedem_id']     = $last_inserted_id;
				$rdata['product_type']  = $productType[$i];
				$rdata['product_id']    = $productId[$i];
				$rdata['used_qty']      = $usedQty[$i];
				$rdata['wastage_qty']   = $wastageQty[$i];
				$rdata['expired_qty']   = $expiredQty[$i];
				$rdata['remaining_qty'] = $remainingQty[$i];
				$rdata['date']          = $data['date'];

				$check = $this->db->insert('tbl_reedem_details', $rdata);
				$kitchen_setup = $this->purchase_model->kitchen_stock_reduce($rdata, $data['kitchen_id'], $type = 1);
			}



			// inserting data to reedem details ends...

			$this->db->trans_commit();

			$this->session->set_flashdata('message', display('save_successfully'));
			redirect('purchase/purchase/reedemList');
		} catch (Exception $e) {
			$this->db->trans_rollback();
			dd($e->getMessage());
			$this->session->set_flashdata('exception',  display('please_try_again'));
		}
	}

	public function updateReedem($id)
	{

		$this->permission->method('purchase', 'update')->redirect();

		$this->db->trans_start();

		try {

			// tbl_reedem table update starts...
			$data['reedem_by']   = $this->input->post('user_id');
			$data['kitchen_id']  = $this->input->post('kitchen_id');
			$data['date']        = date('Y-m-d', strtotime($this->input->post('date')));
			$data['kitchennote'] = $this->input->post('kitchennote');

			$this->db->where('id', $id)->update('tbl_reedem', $data);
			// tbl_reedem table update ends...

			// update assign_inventory starts...
			$productType = $this->input->post('product_type');
			$productId   = $this->input->post('product_id');
			$assignedBy  = $this->session->userdata('id');

			$stockQty     = $this->input->post('stock_quantity');
			$remainingQty = $this->input->post('remaining_qty');

			if ($remainingQty) {

				if (count($stockQty) === count($remainingQty)) {
					$count = count($stockQty);
					for ($i = 0; $i < $count; $i++) {
						$usedQty[$i] = $stockQty[$i] - $remainingQty[$i];
					}
				}
			} else {
				$usedQty = $this->input->post('used_qty');
				$wastageQty  = $this->input->post('wastage_qty');
				$expiredQty  = $this->input->post('expired_qty');
			}

			for ($i = 0; $i < count($productType); $i++) {

				$idata['reedem_id']    = $id;
				$idata['product_type']  = $productType[$i];
				$idata['product_id']    = $productId[$i];
				$idata['used_qty']      = $usedQty[$i];
				$idata['wastage_qty']   = $wastageQty[$i];
				$idata['expired_qty']   = $expiredQty[$i];
				$idata['remaining_qty'] = $remainingQty[$i];
				$idata['date'] = $data['date'];

				$this->db->where('reedem_id', $id)->where('product_id', $productId[$i])->update('tbl_reedem_details', $idata);
				$this->purchase_model->kitchen_stock_reedem_update($idata, $data['kitchen_id'], $type = 1);
			}


			// update assign_inventory ends...

			// new data insertion starts...
			$newProductType     = $this->input->post('new_product_type');
			$newProductId       = $this->input->post('new_product_id');

			$newStockQty = $this->input->post('new_stock_quantity');
			$newRemainingQty = $this->input->post('new_remaining_qty');

			if ($newRemainingQty) {

				if (count($newStockQty) === count($newRemainingQty)) {
					$count = count($newStockQty);
					for ($i = 0; $i < $count; $i++) {
						$newUsedQty[$i] = $newStockQty[$i] - $newRemainingQty[$i];
					}
				}
			} else {
				$newUsedQty = $this->input->post('new_used_qty');
				$newWastageQty = $this->input->post('new_wastage_qty');
				$newExpiredQty = $this->input->post('new_expired_qty');
			}



			if ($newProductType || $newProductId || $newUsedQty || $newWastageQty || $newExpiredQty) {

				for ($i = 0; $i < count($newProductType); $i++) {

					$ndata['reedem_id']     = $id;
					$ndata['product_type']  = $newProductType[$i];
					$ndata['product_id']    = $newProductId[$i];
					$ndata['used_qty']      = $newUsedQty[$i];
					$ndata['wastage_qty']   = $newWastageQty[$i];
					$ndata['expired_qty']   = $newExpiredQty[$i];
					$ndata['remaining_qty'] = $newRemainingQty[$i];
					$ndata['date'] = $data['date'];

					$this->db->insert('tbl_reedem_details', $ndata);
					$this->purchase_model->kitchen_stock_reduce($ndata, $data['kitchen_id'], $type = 1);
				}
			}

			// new data insertion ends...

			$this->db->trans_commit();

			redirect('purchase/purchase/reedemList');
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo 'Exception: ' . $e->getMessage();
			$this->session->set_flashdata('exception',  display('please_try_again'));
		}
	}

	public function deleteReedem($id)
	{
		$this->permission->method('purchase', 'delete')->redirect();
		$this->db->where('id', $id)->delete('tbl_reedem'); // main
		$this->db->where('reedem_id', $id)->delete('tbl_reedem_details'); // details
		$this->db->where('reedem_id', $id)->delete('kitchen_stock_new'); // stock
		$this->session->set_flashdata('message', 'Reedem has been deleted');
		redirect('purchase/purchase/reedemList');
	}

	public function updateStatusReedem($id)
	{

		$this->db->where('id', $id)->update('tbl_reedem', ['status' => 1]);
		$this->session->set_flashdata('message', 'Consumption non editable for kitchen user');
		redirect('purchase/purchase/assigninventoryList');
	}

	// reedem part ends...


	// kitchen user side stock report admin panel starts here...
	public function kitchenUserStockReport()
	{

		$this->permission->method('purchase', 'read')->redirect();
		$data['title'] = display('kitchen_user_stock_report');
		$data['list'] = $this->purchase_model->reedem_list();
		$data['kitchen'] = $this->db->select('*')->from('tbl_kitchen')->get()->result();
		$data['product'] = $this->db->select('*')->from('ingredients')->get()->result();

		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;


		$data['module'] = "purchase";
		$data['page']   = "kitchen_user_stock_report";
		echo Modules::run('template/layout', $data);
	}

	public function kitchenUserStockReportResult()
	{

		$this->permission->method('purchase', 'read')->redirect();
		$data['title'] = display('reedem_list');
		$data['list'] = $this->purchase_model->reedem_list();
		$data['kitchen'] = $this->db->select('*')->from('tbl_kitchen')->get()->result();
		$data['product'] = $this->db->select('*')->from('ingredients')->get()->result();

		$dateTime1 = DateTime::createFromFormat('d-m-Y', $this->input->post('from_date'));
		$dateTime2 = DateTime::createFromFormat('d-m-Y', $this->input->post('to_date'));

		$data['from_date'] = $this->input->post('from_date');
		$data['to_date']   = $this->input->post('to_date');

		$from_date = $dateTime1->format('Y-m-d');
		$to_date   = $dateTime2->format('Y-m-d');
		$data['kitchen_id']   = $kitchen_id   = $this->input->post('kitchen_id');
		$data['product_type'] = $product_type = $this->input->post('product_type');
		$data['product_id']   = $product_id = $this->input->post('ingredient_id');

		$data['report'] = $this->purchase_model->kitchenSideReport($from_date, $to_date, $product_id, $kitchen_id, $product_type);


		$data['module'] = "purchase";
		$data['page']   = "kitchen_user_stock_report";
		echo Modules::run('template/layout', $data);
	}
	// kitchen user side stock report admin panel ends here...

	// admin user side stock report starts here...
	public function AdminUserStockReport()
	{
		$this->permission->method('purchase', 'read')->redirect();
		$data['title'] = display('admin_user_stock_report');
		$data['list'] = $this->purchase_model->reedem_list();
		$data['kitchen'] = $this->db->select('*')->from('tbl_kitchen')->get()->result();
		$data['product'] = $this->db->select('*')->from('ingredients')->get()->result();

		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;

		$data['module'] = "purchase";
		$data['page']   = "kitchen_admin_stock_report";
		echo Modules::run('template/layout', $data);
	}

	public function AdminUserStockReportResult()
	{

		$this->permission->method('purchase', 'read')->redirect();
		$data = [];
		$data['title'] = display('reedem_list');
		$data['list'] = $this->purchase_model->reedem_list();
		$data['kitchen'] = $this->db->select('*')->from('tbl_kitchen')->get()->result();
		$data['product'] = $this->db->select('*')->from('ingredients')->get()->result();

		$dateTime1 = DateTime::createFromFormat('d-m-Y', $this->input->post('from_date'));
		$dateTime2 = DateTime::createFromFormat('d-m-Y', $this->input->post('to_date'));

		$data['from_date'] = $this->input->post('from_date');
		$data['to_date']   = $this->input->post('to_date');

		$from_date = $dateTime1->format('Y-m-d');
		$to_date   = $dateTime2->format('Y-m-d');

		$data['product_type'] = $product_type = $this->input->post('product_type');
		$data['product_id']   = $product_id = $this->input->post('product_id');

		$kitchen = $this->db->select('*')->from('tbl_kitchen')->get()->result();

		$final_sum_kit_given = '';
		$zero_sum_kit_given  = '';
		$main_sum_kit_given  = '';

		$final_sum_kit_stock = '';
		$zero_sum_kit_stock  = '';
		$main_sum_kit_stock  = '';

		$latest_sum_kit_stock = '';

		foreach ($kitchen as $key => $da) {
			$serial  = $key + 1;
			
			$final_sum_kit_given .= "SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN kit$serial ELSE 0 END) as kit$serial,";
			$zero_sum_kit_given  .= "0 as kit$serial,";
			$main_sum_kit_given  .= "SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' AND fkd.kitchen_id= $da->kitchenid  THEN fkd.total_given ELSE 0 END) AS kit$serial,";

			$final_sum_kit_stock .= "SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' THEN current_stock_kit$serial ELSE 0 END) as available_stock_kit$serial,";
			$zero_sum_kit_stock  .= "0 as current_stock_kit$serial,";
			$main_sum_kit_stock  .= "SUM(CASE WHEN Date >= '$from_date' AND DATE <= '$to_date' AND  fkd.kitchen_id = $da->kitchenid THEN fkd.current_stock ELSE 0 END) AS current_stock_kit$serial,";
			
			$latest_sum_kit_stock .= "SUM(current_stock_kit$serial) as latest_available_stock_kit$serial,";

		}

		$data['report'] = $this->purchase_model->all_kitchen_report($final_sum_kit_given, $zero_sum_kit_given, $main_sum_kit_given, $final_sum_kit_stock, $zero_sum_kit_stock, $main_sum_kit_stock, $latest_sum_kit_stock, $from_date, $to_date, $product_type, $product_id);


		$data['module'] = "purchase";
		$data['page']   = "kitchen_admin_stock_report";
		echo Modules::run('template/layout', $data);
	}
	// admin user side stock report ends here... 

	// so request part starts here...

	public function soRequestList()
	{

		$this->permission->method('purchase', 'read')->redirect();

		$data['title']  = display('so_request_list');

		$logged_in_user = $this->session->userdata('id');
		$check = $this->db->select('is_admin')->from('user')->where('id', $logged_in_user)->get()->row()->is_admin;

		if ($check == 1) {
			$data['list'] = $this->purchase_model->soRequestList();
		} else {
			$data['list'] = $this->purchase_model->soRequestListKitchen($logged_in_user);
		}




		$data['module'] = "purchase";
		$data['page']   = "so_request_list";
		echo Modules::run('template/layout', $data);
	}

	public function soRequestAdd()
	{

		$this->permission->method('purchase', 'create')->redirect();
		$data['title'] = display('so_request_add');
		$saveid = $this->session->userdata('id');

		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->readsingle('*', 'currency', array('currencyid' => $settinginfo->currency));

		// new code by MKar starts here...
		$data['kitchen'] = $this->purchase_model->kitchen_dropdown();
		$data['user']    = $this->purchase_model->user_dropdown();
		// new code by MKar ends here...

		$data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
		$data['list']   = $this->db->select("*")->from('tbl_trams_condition')->get()->row();
		$data['module'] = "purchase";
		$data['page']   = "so_request_add";

		echo Modules::run('template/layout', $data);
	}

	// both admin and user task
	public function soRequestEdit($id)
	{

		$this->permission->method('purchase', 'update')->redirect();
		$data['title']   = display('edit_so_request');
		$data['list']    = $this->purchase_model->soRequestEdit($id);
		$data['module']  = "purchase";
		$data['so_request'] = $this->db->select('*')->from('so_request')->where('id', $id)->get()->row();
		$data['kitchen'] = $this->db->select('*')->from('tbl_kitchen')->get()->result();
		$data['user']    = $this->db->select('*')->from('user')->get()->result();
		$data['product'] = $this->db->select('*')->from('ingredients')->get()->result();
		$data['page']    = "so_request_edit";

		echo Modules::run('template/layout', $data);
	}

	public function soRequestStore()
	{

		$this->permission->method('purchase', 'create')->redirect();
		$this->db->trans_start();

		try {

			$userId      = $this->input->post('userid');
			$kitchenId   = $this->input->post('kitchenid');
			$requestDate  = date('Y-m-d', strtotime($this->input->post('date')));

			$productType = $this->input->post('product_type');
			$productId   = $this->input->post('product_id');
			$productQty  = $this->input->post('product_quantity');
			$kitchennote = $this->input->post('kitchennote');

			$requestBy  = $this->session->userdata('id');

			// so request insertion starts...

			$so_data = [
				'code'       => $this->purchase_model->insert_new_so_record(),
				'kitchen_id' => $kitchenId,
				'user_id'    => $userId,
				'date'       => $requestDate,
				'kitchennote' => $kitchennote
			];

			$this->db->insert('so_request', $so_data);


			$last_inserted_id = $this->db->insert_id();

			// so request insertion ends...

			// so request details insertion starts...
			for ($i = 0; $i < count($productType); $i++) {

				// insert
				$data['so_request_id'] = $last_inserted_id;
				$data['product_type']  = $productType[$i];
				$data['product_id']    = $productId[$i];
				$data['product_qty']   = $productQty[$i];
				$data['request_date']  = $requestDate;
				$data['request_by']    = $requestBy;

				$check = $this->db->insert('so_request_details', $data);
			}
			// so request details insertion ends...

			$this->db->trans_commit();

			$this->session->set_flashdata('message', display('save_successfully'));
			redirect('purchase/purchase/soRequestList');
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('exception',  display('please_try_again'));
		}
	}

	public function soRequestUpdate($id)
	{

		$this->permission->method('purchase', 'update')->redirect();
		$this->db->trans_start();

		try {

			// so request table update starts...
			$data['user_id']     = $this->input->post('user_id');
			$data['kitchen_id']  = $this->input->post('kitchen_id');
			$data['date']        = date('Y-m-d', strtotime($this->input->post('date')));
			$data['kitchennote'] = $this->input->post('kitchennote');

			$this->db->where('id', $id)->update('so_request', $data);

			// so request table update ends...

			// so request details update starts...
			$productType = $this->input->post('product_type');
			$productId   = $this->input->post('product_id');
			$productQty  = $this->input->post('product_quantity');
			$requestBy  = $this->session->userdata('id');

			for ($i = 0; $i < count($productType); $i++) {
				$idata['so_request_id'] = $id;
				$idata['product_type']  = $productType[$i];
				$idata['product_id']    = $productId[$i];
				$idata['product_qty']   = $productQty[$i];
				$idata['request_date']  = $data['date'];
				$idata['request_by']    = $requestBy;

				$check1 = $this->db->where('so_request_id', $id)->where('product_id', $productId[$i])->update('so_request_details', $idata);
			}
			// so request details update ends...

			// new data insertion starts...
			$newProductType     = $this->input->post('new_product_type');
			$newProductId       = $this->input->post('new_product_id');
			$newProductQuantity = $this->input->post('new_product_quantity');

			if ($newProductType || $newProductId || $newProductQuantity) {

				for ($i = 0; $i < count($newProductType); $i++) {

					$ndata['so_request_id'] = $id;
					$ndata['product_type']  = $newProductType[$i];
					$ndata['product_id']    = $newProductId[$i];
					$ndata['product_qty']   = $newProductQuantity[$i];
					$ndata['request_date']  = $data['date'];
					$ndata['request_by']    = $requestBy;

					$check2 = $this->db->insert('so_request_details', $ndata);
				}
			}
			// new data insertion ends...

			$this->db->trans_commit();

			$this->session->set_flashdata('message', display('save_successfully'));
			redirect('purchase/purchase/soRequestList');
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo 'Exception: ' . $e->getMessage();
			$this->session->set_flashdata('exception',  display('please_try_again'));
		}
	}

	public function soRequestSingleDelete($id)
	{

		$this->permission->method('purchase', 'delete')->redirect();

		$this->db->where('id', $id)->delete('so_request_details');
		$this->session->set_flashdata('message', display('delete_successfully'));
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function soRequestDelete($id)
	{

		$this->permission->method('purchase', 'delete')->redirect();

		$this->db->where('id', $id)->delete('so_request'); // main
		$this->db->where('so_request_id', $id)->delete('so_request_id'); // details
		$this->db->where('so_request_id', $id)->delete('kitchen_stock_new'); // stock
		$this->session->set_flashdata('message', 'SO Request has been deleted');
		redirect('purchase/purchase/soRequestList');
	}


	// admin task
	public function soRequestAssign($id)
	{

		$this->permission->method('purchase', 'create')->redirect();

		$data['title']   = display('assign_so_request');
		$data['list']    = $this->purchase_model->soRequestEdit($id);
		$data['module']  = "purchase";
		$data['so_request'] = $this->db->select('*')->from('so_request')->where('id', $id)->get()->row();
		$data['kitchen'] = $this->db->select('*')->from('tbl_kitchen')->get()->result();
		$data['user']    = $this->db->select('*')->from('user')->get()->result();
		$data['product'] = $this->db->select('*')->from('ingredients')->get()->result();
		$data['page']    = "so_request_assign";

		echo Modules::run('template/layout', $data);
	}

	// admin task
	public function soRequestAssignStore($id)
	{

		$this->permission->method('purchase', 'create')->redirect();

		$this->db->trans_start();

		try {

			// so request table update starts...
			$data['user_id']     = $this->input->post('user_id');
			$data['kitchen_id']  = $this->input->post('kitchen_id');
			$data['date']        = date('Y-m-d', strtotime($this->input->post('date')));
			$data['kitchennote'] = $this->input->post('kitchennote');

			$this->db->where('id', $id)->update('so_request', $data);
			// so $request table update ends...

			$productType  = $this->input->post('product_type');
			$productId    = $this->input->post('product_id');
			$availableQty = $this->input->post('available_quantity');
			$productQty   = $this->input->post('product_quantity');
			$assignedBy   = $this->session->userdata('id');

			// checking if there is any requested qty greater than stock starts...
			if (count($availableQty) === count($productQty)) {

				$count = count($availableQty);
				for ($i = 0; $i < $count; $i++) {

					if (preg_replace('/[^0-9]/', '', $availableQty[$i]) < $productQty[$i]) {
						$limit = 1;
					}
				}
			}


			if ($limit == 1) {
				$this->session->set_flashdata('exception',  "SO Request quantity can't be greater than Stock");
				redirect($_SERVER['HTTP_REFERER']);
				// checking if there is any requested qty greater than stock ends...

			} else {

				$status = $this->db->from('so_request')->where('id', $id)->get()->row()->status;

				if ($status == 0) {

					// update so request details starts...
					for ($i = 0; $i < count($productType); $i++) {

						$idata['so_request_id'] = $id;
						$idata['product_type']  = $productType[$i];
						$idata['product_id']    = $productId[$i];
						$idata['product_qty']   = $productQty[$i];
						$idata['assigned_date'] = $data['date'];
						$idata['assigned_by']   = $assignedBy;

						// so status change...
						$this->db->where('id', $id)->update('so_request', ['status' => 1]);
						// updating given qty field...
						$this->db->where('so_request_id', $id)->where('product_id', $idata['product_id'])->update('so_request_details', ['given_qty' => $productQty[$i], 'assigned_date' => $data['date'], 'assigned_by' => $assignedBy]);
						// kitchen stock store...
						$this->purchase_model->so_kitchen_stock_store($idata, $data['kitchen_id'],  $type = 0);
					}
				} else {

					// update so request details starts...
					for ($i = 0; $i < count($productType); $i++) {

						$idata['so_request_id'] = $id;
						$idata['product_type']  = $productType[$i];
						$idata['product_id']    = $productId[$i];
						$idata['product_qty']   = $productQty[$i];
						$idata['assigned_date'] = $data['date'];
						$idata['assigned_by']   = $assignedBy;

						// updating given qty field...
						$this->db->where('so_request_id', $id)->where('product_id', $idata['product_id'])->update('so_request_details', ['given_qty' => $productQty[$i], 'assigned_date' => $data['date'], 'assigned_by' => $assignedBy]);
						// kitchen stock update...
						$this->purchase_model->so_kitchen_stock_update($idata, $data['kitchen_id'], $type = 0);
					}
				}
				// update so request detailsy ends...
				// new data insertion starts...
				$newProductType     = $this->input->post('new_product_type');
				$newProductId       = $this->input->post('new_product_id');
				$newProductQuantity = $this->input->post('new_product_quantity');

				if ($newProductType || $newProductId || $newProductQuantity) {

					for ($i = 0; $i < count($newProductType); $i++) {

						$ndata['assign_inventory_main_id'] = $id;
						$ndata['product_type']  = $newProductType[$i];
						$ndata['product_id']    = $newProductId[$i];
						$ndata['product_qty']   = $newProductQuantity[$i];
						$ndata['assigned_date'] = $data['date'];
						$ndata['assigned_by']   = $assignedBy;

						$check2 = $this->db->insert('assign_inventory', $ndata);
						$kitchen_setup2 = $this->purchase_model->kitchen_stock_store($ndata,  $data['kitchen_id'], $type = 0);
					}
				}
				// new data insertion ends...
			}
			$this->db->trans_commit();

			$this->session->set_flashdata('message', display('save_successfully'));
			redirect('purchase/purchase/soRequestList');
		}catch (Exception $e) {
			$this->db->trans_rollback();
			echo 'Exception: ' . $e->getMessage();
			$this->session->set_flashdata('exception',  display('please_try_again'));
		}
	}

	//Inventory Adjustment
	
	public function adjustmentlist($id = null)
	{
	$this->permission->method('purchase', 'read')->redirect();
	$data['title']    = display('adjustment_list');
	#-------------------------------#       
	#
	#pagination starts
	#
	$config["base_url"] = base_url('purchase/purchase/addadjustment');
	$config["total_rows"]  = $this->purchase_model->countadjlist();
	$config["per_page"]    = 25;
	$config["uri_segment"] = 4;
	$config["last_link"] = "Last";
	$config["first_link"] = "First";
	$config['next_link'] = 'Next';
	$config['prev_link'] = 'Prev';
	$config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
	$config['full_tag_close'] = "</ul>";
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
	$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
	$config['next_tag_open'] = "<li>";
	$config['next_tag_close'] = "</li>";
	$config['prev_tag_open'] = "<li>";
	$config['prev_tagl_close'] = "</li>";
	$config['first_tag_open'] = "<li>";
	$config['first_tagl_close'] = "</li>";
	$config['last_tag_open'] = "<li>";
	$config['last_tagl_close'] = "</li>";
	/* ends of bootstrap */
	$this->pagination->initialize($config);
	$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
	$data["purchaselist"] = $this->purchase_model->readadjustment($config["per_page"], $page);
	$data["links"] = $this->pagination->create_links();
	$data['pagenum'] = $page;
	$data['items']   = $this->purchase_model->ingrediant_dropdown();
	$data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
	$settinginfo = $this->purchase_model->settinginfo();
	$data['currency'] = $this->purchase_model->currencysetting($settinginfo->currency);

	if (!empty($id)) {
		$data['title'] = display('edit_adjustment');
		$data['intinfo']   = $this->purchase_model->findByadjId($id);
	}
	#
	#pagination ends
	#   

	$data['module'] = "purchase";
	$data['page']   = "adjustmentlist";
	echo Modules::run('template/layout', $data);
}
	public function addadjustment(){
		$this->permission->method('purchase', 'create')->redirect();
		$data['title']   = display('addadjustment');
		$data['module']  = "purchase";
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->readsingle('*', 'currency', array('currencyid' => $settinginfo->currency));
		$data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
		$data['page']    = "addadjustment";
		echo Modules::run('template/layout', $data);
	}

	public function adjustment_entry()
	{

		

		$this->form_validation->set_rules('referenceno', 'Reference Number', 'required|max_length[50]');
		$this->form_validation->set_rules('adjust_date', 'Adjusted Date', 'required');
		$saveid = $this->session->userdata('id');
        
		if ($this->form_validation->run()) {
			$this->permission->method('purchase', 'create')->redirect();
			$logData = array(
				'action_page'         => "Add Adjust",
				'action_done'     	 => "Insert Data",
				'remarks'             => "Item adjust Created",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);
			if ($this->purchase_model->createcreateadjust()) {
				$this->logs_model->log_recorded($logData);
				$this->session->set_flashdata('message', display('save_successfully'));
				redirect('purchase/purchase/addadjustment');
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("purchase/purchase/addadjustment");
		} else {
			$this->session->set_flashdata('exception',  display('please_try_again'));
			redirect("purchase/purchase/addadjustment");
		}
	}
	public function updateadjfrm($id)
	{
		$this->permission->method('purchase', 'update')->redirect();
		$data['title'] = display('edit_adjustment');
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->readsingle('*', 'currency', array('currencyid' => $settinginfo->currency));
		$data['purchaseinfo']   = $this->purchase_model->findByadjId($id);
		$data['iteminfo']       = $this->purchase_model->adjustiteminfo($id);
		$data['ingrdientslist']   = $this->purchase_model->ingrediantlist();
		$data['module'] = "purchase";
		$data['page']   = "adjustedit";
		echo Modules::run('template/layout', $data);
	}
	public function adjustinvoice($id = null)
	{
		$this->permission->method('purchase', 'update')->redirect();
		$data['title'] = display('purchase_edit');
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->currencysetting($settinginfo->currency);
		$data['purchaseinfo']   = $this->purchase_model->findByadjId($id);
		$data['iteminfo']       = $this->purchase_model->adjustiteminfo($id);
		$data['module'] = "purchase";
		$data['page']   = "adjustinvoice";
		echo Modules::run('template/layout', $data);
	}
	public function update_adjentry()
	{
		$this->form_validation->set_rules('referenceno', 'Reference Number', 'required|max_length[50]');
		$this->form_validation->set_rules('adjust_date', 'Adjusted Date', 'required');
		$saveid = $this->session->userdata('id');
		if ($this->form_validation->run()) {
			$this->permission->method('purchase', 'update')->redirect();
			$logData = array(
				'action_page'         => "Update Purchase",
				'action_done'     	 => "Update Data",
				'remarks'             => "Item Purchase Updated",
				'user_name'           => $this->session->userdata('fullname'),
				'entry_date'          => date('Y-m-d H:i:s'),
			);
			//echo "dfdsf";
			if ($this->purchase_model->adjupdate()) {
				$this->logs_model->log_recorded($logData);
				$this->session->set_flashdata('message', display('update_successfully'));
				redirect('purchase/purchase/adjustmentlist');
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("purchase/purchase/adjustmentlist");
		} else {
			redirect("purchase/purchase/adjustmentlist");
		}
	}

	public function setOpeningStockDate(){
		$fiyear_id = $this->input->post('fiyear_id');
		$fy_info = $this->db->select('*')->from('acc_financialyear')->get()->row();
		$end_date = $fy_info->end_date;
		echo json_encode($end_date);
	}



	public function opening_stock_item_delete($id){
		
		$totalprice = $this->db->select('sum(openstock * unitprice) as total_price')
								->from('tbl_openingstock')  
								->where('itemid', $id)       
								->get()
								->row()                       
								->total_price;               

		$credit = $this->db->select('acc_coa_id')
			->from('acc_predefined p')
			->join('acc_predefined_seeting ps', 'ps.predefined_id = p.id', 'inner')
			->where('p.id', 36)
			->where('p.is_active', TRUE)
			->where('ps.is_active', TRUE)->get()->row()->acc_coa_id;

		$debit = $this->db->select('acc_coa_id')
			->from('acc_predefined p')
			->join('acc_predefined_seeting ps', 'ps.predefined_id = p.id', 'inner')
			->where('p.id', 13)
			->where('p.is_active', TRUE)
			->where('ps.is_active', TRUE)->get()->row()->acc_coa_id;


		$totalamount = $this->db->select('total_amount')->from('tbl_openingstock_master')->get()->row()->total_amount;


		$dr = $this->db->select('debit')->from('acc_openingbalance')->where('acc_coa_id', $debit)->get()->row()->debit;
		$cr = $this->db->select('credit')->from('acc_openingbalance')->where('acc_coa_id', $credit)->get()->row()->credit;

		$this->db->where('acc_coa_id', $debit);
		$this->db->limit(1);
		$this->db->update('acc_openingbalance', ['debit'=> $dr-$totalprice]);

		$this->db->where('acc_coa_id', $credit);
		$this->db->limit(1);
		$this->db->update('acc_openingbalance', ['credit'=> $cr-$totalprice]);



		$this->db->where('id', 1);
		$this->db->update('tbl_openingstock_master', ['total_amount'=> $totalamount-$totalprice]);


		$this->db->where('itemid', $id);
		$this->db->delete('tbl_openingstock');

	}

	public function viewPurchaseInfo($id = null)
	{
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->currencysetting($settinginfo->currency);
		$data['purchaseinfo']   = $this->purchase_model->findById($id);
		$supid = $data['purchaseinfo']->suplierID;
		$data['supplierinfo']   = $this->purchase_model->suplierinfo($supid);
		$data['iteminfo']       = $this->purchase_model->iteminfo($id);

		$this->load->view('purchase/purchaseinvoice_view', $data); 
		
	}

	public function viewSinglePurchaseOrderInfo($id = null)
	{
		
		$settinginfo = $this->purchase_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->purchase_model->currencysetting($settinginfo->currency);
		
		$single_po_request_info   = $this->purchase_model->single_po_request_info($id);
		$data['single_po_request_info']   =$single_po_request_info[0];
		
		// dd($data['single_po_request_info']);
		// $supid = $data['purchaseinfo']->suplierID;
		// $data['supplierinfo']   = $this->purchase_model->suplierinfo($supid);
		// $data['iteminfo']       = $this->purchase_model->iteminfo($id);


		$this->load->view('purchase/supplier_po_request_model_view', $data); 
		
	}


}

