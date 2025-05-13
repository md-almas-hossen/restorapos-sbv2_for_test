<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'catering_service/report_model',
			// 'logs_model',
			// 'accounts/accounts_model'
		));
	}

	public function index($id = null)
	{


		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('purchase_report');
		$first_date = str_replace('/', '-', $from_date);
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $to_date);
		$end_date = date('Y-m-d', strtotime($second_date));
		$suplierid = $this->input->post('supplier');
		$data['supplierinfo'] = $this->db->select("*")->from('supplier')->get()->result();
		$data['preport']  = $this->report_model->pruchasereport($start_date, $end_date, $suplierid);
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);

		$data['module'] = "report";
		$data['page']   = "prechasereport";
		echo Modules::run('template/layout', $data);
	}


	public function purchasereport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('purchase_report');
		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$suplierid = $this->input->post('supplier');
		$data['preport']  = $this->report_model->pruchasereport($start_date, $end_date, $suplierid);
		$settinginfo = $this->report_model->settinginfo();
		$totalpaid = $this->totalpurchasepaid($suplierid, $start_date, $end_date);
		// $data['totalpaid']=$totalpaid;
		$data['supplier'] = $suplierid;
		$data['dtpFromDate'] = $start_date;
		$data['dtpToDate'] = $end_date;
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['page']   = "getpreport";
		$this->load->view('report/getpreport', $data);
	}
	public function totalpurchasepaid($supid = null, $dtpFromDate, $dtpToDate)
	{
		$financialyears = $this->db->select('*')->from('tbl_financialyear')->where("is_active", 2)->get()->row();
		$predefine = $this->db->select("*")->from('tbl_predefined')->get()->row();
		$subcodeinfo = $this->db->select("*")->from('tbl_subcode')->where("subTypeID", 4)->where("refCode", $supid)->get()->row();
		$this->db->select('acc_transaction.*,a.Name,b.Name as reversename');
		$this->db->from('acc_transaction');
		$this->db->join('tbl_ledger a', 'a.id = acc_transaction.COAID', 'left');
		$this->db->join('tbl_ledger b', 'b.id = acc_transaction.reversecode', 'left');
		$this->db->where('acc_transaction.IsAppove', 1);
		$this->db->where('acc_transaction.VDate BETWEEN "' . $dtpFromDate . '" and "' . $dtpToDate . '"');
		$this->db->where('acc_transaction.COAID', $predefine->SupplierAcc);
		if (!empty($supid)) {
			$this->db->join('tbl_subtype st', 'acc_transaction.subtype = st.id', 'left');
			$this->db->join('tbl_subcode sc', 'acc_transaction.subcode = sc.id', 'left');
			$this->db->where('acc_transaction.subtype', 4);
			$this->db->where('acc_transaction.subcode', $subcodeinfo->id);
		}

		$this->db->order_by('acc_transaction.VDate', 'Asc');
		$this->db->order_by('acc_transaction.Vtype', 'Asc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		$totalpaid = $query->result();
		if (!empty($supid)) {
			$prebalance = $this->report_model->get_opening_balance_subtype($predefine->SupplierAcc, $dtpFromDate, $dtpToDate, 4, $subcodeinfo->id);
			$TotalCredit = 0;
			$TotalDebit  = 0;
			$CurBalance = $prebalance;
			$openid = 1;
			$ledger = $this->accounts_model->general_led_report_headname($predefine->SupplierAcc);

			foreach ($totalpaid as $key => $data) {
				$TotalDebit += $data->Debit;
				$TotalCredit += $data->Credit;
				if ($ledger->NatureID == 1 || $ledger->NatureID == 4) {
					if ($data->Debit > 0) {
						$CurBalance += $data->Debit;
					}
					if ($data->Credit > 0) {
						$CurBalance -= $data->Credit;
					}
				} else {
					if ($data->Debit > 0) {
						$CurBalance -= $data->Debit;
					}
					if ($data->Credit > 0) {
						$CurBalance += $data->Credit;
					}
				}
			}
			return $CurBalance;
		} else {
			$output = array();
			$allsubcodes = $this->report_model->get_subTypeItems(4);

			$m = 0;
			foreach ($allsubcodes as $row) {
				$subLedger = $this->report_model->get_subcode_byid($row->id);
				$HeadName = $this->report_model->general_led_report_headname($predefine->SupplierAcc);
				$pre_balance = $this->report_model->get_opening_balance_subtype($predefine->SupplierAcc, $dtpFromDate, $dtpToDate, 4, $row->id);
				$HeadName2 = $this->report_model->get_general_ledger_report($predefine->SupplierAcc, $dtpFromDate, $dtpToDate, 1, 0, 4, $row->id);
				$TotalCredit = 0;
				$TotalDebit  = 0;
				$CurBalance = $pre_balance;

				foreach ($HeadName2 as $newdata) {
					$TotalDebit += $newdata->Debit;
					$TotalCredit += $newdata->Credit;
					if ($HeadName->NatureID == 1 || $HeadName->NatureID == 4) {
						if ($newdata->Debit > 0) {
							$CurBalance += $newdata->Debit;
						}
						if ($newdata->Credit > 0) {
							$CurBalance -= $newdata->Credit;
						}
					} else {
						if ($newdata->Debit > 0) {
							$CurBalance -= $newdata->Debit;
						}
						if ($newdata->Credit > 0) {
							$CurBalance += $newdata->Credit;
						}
					}
				}
				if ($CurBalance > 0) {
					$output[$m]['subcode'] = $row->id;
					$output[$m]['subcodeName'] = $row->name;
					$output[$m]['payable'] = $CurBalance;
					$output[$m]['receivable'] = 0.00;
				} else {
					$output[$m]['subcode'] = $row->id;
					$output[$m]['subcodeName'] = $row->name;
					$output[$m]['payable'] = 0.00;
					$output[$m]['receivable'] = $CurBalance;
				}
				$m++;
			}
			$Totalpayable = 0;
			$Totalreceivable  = 0;
			$k = 0;
			foreach ($output as $data) {
				$k++;
				$Totalpayable += $data['payable'];
				$Totalreceivable += $data['receivable'];
			}
			return $total = $Totalpayable - $Totalreceivable;
		}
	}
	public function productwise()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('purchase_report');
		$data['catinfoinfo'] = $this->db->select("item_category.Name,item_foods.CategoryID")->from('item_category')->join('item_foods', 'item_foods.CategoryID=item_category.CategoryID', 'left')->where('ProductsIsActive', 1)->group_by('CategoryID')->get()->result();
		//echo $this->db->last_query();
		$data['allproduct'] = $this->report_model->productreportall();
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['page']   = "product_wise_report";
		echo Modules::run('template/layout', $data);
	}
	public function getcateryproduct()
	{
		$categoryid = $this->input->post('categoryid');
		if ($categoryid != -1) {
			$contition = "CategoryID='" . $categoryid . "' AND ProductsIsActive=1";
		} else {
			$contition = "ProductsIsActive=1";
		}
		$html = '';
		$allproduct = $this->db->select("item_foods.ProductsID,item_foods.CategoryID,item_foods.ProductName")->from('item_foods')->where($contition)->group_by('ProductsID')->get()->result();
		$html = '<option value="">' . display('select') . '</option>';
		$html .= '<option value="-1">All</option>';
		foreach ($allproduct as $product) {
			$html .= '<option value="' . $product->ProductsID . '">' . $product->ProductName . '</option>';
		}
		echo $html;
	}
	public function productwisereport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('purchase_report');
		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$pid = $this->input->post('productid');
		$catid = $this->input->post('catid');
		$data['allproduct']  = $this->report_model->productreport($start_date, $end_date, $pid, $catid);
		//print_r($data['allproduct']);
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['page']   = "getproreport";
		$this->load->view('report/getproreport', $data);
	}
	public function readyitem()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('purchase_report');
		//echo $this->db->last_query();
		$data['allproduct'] = $this->db->select("item_foods.ProductsID,item_foods.CategoryID,item_foods.ProductName")->from('item_foods')->where('withoutproduction', 1)->where('ProductsIsActive', 1)->group_by('ProductsID')->get()->result();
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['page']   = "product_wise_readyitem_report";
		echo Modules::run('template/layout', $data);
	}
	public function productwisereadyreport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('purchase_report');
		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$pid = $this->input->post('productid');
		$catid = $this->input->post('catid');
		$data['allproduct']  = $this->report_model->productreportitem($start_date, $end_date, $pid);
		//print_r($data['allproduct']);
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['page']   = "getproreportready";
		$this->load->view('report/getproreportready', $data);
	}

	public function ingredientbytype()
	{
		$ingredient = $this->input->post('q', true);
		$type = $this->input->post('type', true);
		if ($type == 3) {
			$cond = "type=2 AND is_addons=1";
		} else {
			$cond = "type=$type AND is_addons=0";
		}
		$delinfo = $this->db->select("ingredient_name,id")->from('ingredients')->where($cond)->like('ingredient_name', $ingredient)->limit('15')->get()->result();
		//echo $this->db->last_query();
		$response = array();
		foreach ($delinfo as $address) {
			$response[] = array("value" => $address->ingredient_name, "id" => $address->id);
		}
		echo json_encode($response);
	}
	public function ingredientwise()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('purchase_report');
		$data['allingredient']  = $this->report_model->allingredients();
		$data['allproduct'] = $this->report_model->allingredient();
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['page']   = "ingredient_wise_report";
		echo Modules::run('template/layout', $data);
	}
	public function ingredientwisereport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('purchase_report');
		$start_date = $this->input->post('from_date');
		$end_date = $this->input->post('to_date');
		$pid = $this->input->post('productid');
		$ptype = $this->input->post('producttype');
		$stock = $this->input->post('stock');
		$data['allproduct']  = $this->report_model->ingredientreport($start_date, $end_date, $pid, $ptype, $stock);
		// dd($allproduct);
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['page']   = "kitchenreport";
		$this->load->view('report/kitchenreport', $data);
	}
	public function sellrpt()
	{
		// $this->permission->method('report', 'read')->redirect();
		$data['title']    = display('catering_sell_report');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['paymentmethod']   = $this->report_model->pmethod_dropdown();

		$data['module'] = "catering_service/report";
		$data['page']   = "salereportfrm";
		echo Modules::run('template/layout', $data);
	}
	public function salereport()
	{
		// $this->permission->method('report', 'read')->redirect();
		$data['title']    = display('catering_sell_report');

		$pid = $this->input->post('paytype', true);
		$invoie_no = $this->input->post('invoie_no', true);

		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$data['preport']  = $this->report_model->salereport($start_date, $end_date, $pid, $invoie_no);
		$settinginfo = $this->report_model->settinginfo();
		$data['paytype'] = $pid;
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['page']   = "ajaxsalereport";
		$this->load->view('catering_service/report/ajaxsalereport', $data);
	}
	public function sellrptbydate()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['page']   = "salereportbyproduct";
		echo Modules::run('template/layout', $data);
	}
	public function salereportbydate()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report');
		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$data['preport']  = $this->report_model->salereportbydates($start_date, $end_date);
		$settinginfo = $this->report_model->settinginfo();
		$data['daterange'] = "customer_order.order_date BETWEEN '$start_date' AND '$end_date' AND customer_order.order_status=4 AND isdelete!=1";
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['page']   = "salebydate";
		$this->load->view('report/salebydate', $data);
	}
	public function datewisereport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('datebydatesale');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['paymentmethod']   = $this->report_model->pmethod_dropdown();
		$data['module'] = "report";
		$data['page']   = "salereporttotal";
		echo Modules::run('template/layout', $data);
	}
	public function salereportbydatewise()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('datebydatesale');
		$pid = $this->input->post('paytype', true);
		$invoie_no = $this->input->post('invoie_no', true);

		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$data['preport']  = $this->report_model->salereportbyday($start_date, $end_date, $pid);
		$settinginfo = $this->report_model->settinginfo();
		$data['paytype'] = $pid;
		$data['startdate'] = $start_date;
		$data['enddate'] = $end_date;
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['page']   = "ajaxsalereportbyday";
		$this->load->view('report/ajaxsalereportbyday', $data);
	}
	public function sellrpt2()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['ctypeoption'] = $this->report_model->ctype_dropdown();
		$data['module'] = "report";
		$data['page']   = "salereportfrm2";
		echo Modules::run('template/layout', $data);
	}
	public function generaterpt()
	{
		$this->permission->method('report', 'read')->redirect();
		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$preport = $this->report_model->salereport($start_date, $end_date);
		if ($preport) {
			foreach ($preport as $pitem) {
				$existsorder = $this->db->select("*")->from('tbl_generatedreport')->where('order_id', $pitem->order_id)->order_by('order_id', 'desc')->get()->row();
				if (empty($existsorder)) {
					$generaterpt = array(
						'order_id'			    =>	$pitem->order_id,
						'saleinvoice'			=>	$pitem->saleinvoice,
						'customer_id'			=>	$pitem->customer_id,
						'cutomertype'		    =>	$pitem->cutomertype,
						'isthirdparty'	        =>	$pitem->isthirdparty,
						'waiter_id'	        	=>	$pitem->waiter_id,
						'kitchen'	        	=>	$pitem->kitchen,
						'order_date'	        =>	$pitem->order_date,
						'order_time'	        =>	$pitem->order_time,
						'table_no'		    	=>	$pitem->table_no,
						'tokenno'		        =>	$pitem->tokenno,
						'totalamount'		 	=>  $pitem->totalamount,
						'customerpaid'		    =>	$pitem->customerpaid,
						'customer_note'		    =>	$pitem->customer_note,
						'anyreason'		        =>	$pitem->anyreason,
						'order_status'		    =>	$pitem->order_status,
						'nofification'		    =>	$pitem->nofification,
						'orderacceptreject'		=>	$pitem->orderacceptreject,
						'reportDate'		    =>	$start_date
					);
					$this->db->insert('tbl_generatedreport', $generaterpt);
				}
			}
		}
	}
	public function generatedrpt()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['ctypeoption'] = $this->report_model->ctype_dropdown();
		$data['module'] = "report";
		$data['page']   = "searchgenrpt";
		echo Modules::run('template/layout', $data);
	}
	public function allsellrpt()
	{
		$setting = $this->report_model->settinginfo();
		$list = $this->report_model->get_allsalesorder();
		$card = $this->report_model->count_allpayments(1);
		$online = $this->report_model->count_allpayments(0);
		$cash = $this->report_model->count_allpayments(4);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rowdata) {
			$no++;
			$row = array();
			$thisrpartyid = $rowdata->isthirdparty;
			if ($thisrpartyid > 0) {
				$thirdpartyinfo = $this->db->select('*')->from('tbl_thirdparty_customer')->where('companyId', $thisrpartyid)->get()->row();
				$persent = ($thirdpartyinfo->commision * $rowdata->totalamount) / 100;
				$delivaricompany = ' - ' . $thirdpartyinfo->company_name;
			} else {
				$persent = 0;
				$delivaricompany = '';
			}

			$row[] = $rowdata->order_date;
			$row[] = getPrefixSetting()->sales . '-' . $rowdata->saleinvoice;
			$row[] = $rowdata->customer_name;
			$row[] = $rowdata->first_name . $rowdata->last_name;
			$row[] = $rowdata->customer_type . $delivaricompany;
			$row[] = numbershow($rowdata->discount, $setting->showdecimal);
			$row[] = numbershow($persent, $setting->showdecimal);
			$row[] = numbershow($rowdata->totalamount, $setting->showdecimal);
			$data[] = $row;
		}
		if (empty($card)) {
			$card = 0;
		}
		if (empty($online)) {
			$online = 0;
		}
		if (empty($cash)) {
			$cash = 0;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"cardpayments" => numbershow($card, $setting->showdecimal),
			"Onlinepayment" => numbershow($online, $setting->showdecimal),
			"Cashpayment" => numbershow($cash, $setting->showdecimal),
			"recordsTotal" => $this->report_model->count_allsalesorder(),
			"recordsFiltered" => $this->report_model->count_filtersalesorder(),
			"data" => $data,
		);
		echo json_encode($output);
	}
	public function allsellgtrpt()
	{
		$list = $this->report_model->get_allsalesgtorder();
		$card = $this->report_model->count_allpaymentsgt(1);
		$online = $this->report_model->count_allpaymentsgt(0);
		$cash = $this->report_model->count_allpaymentsgt(4);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $rowdata) {
			$no++;
			$row = array();
			$thisrpartyid = $rowdata->isthirdparty;
			if ($thisrpartyid > 0) {
				$thirdpartyinfo = $this->db->select('*')->from('tbl_thirdparty_customer')->where('companyId', $thisrpartyid)->get()->row();
				$persent = ($thirdpartyinfo->commision * $rowdata->totalamount) / 100;
				$delivaricompany = ' - ' . $thirdpartyinfo->company_name;
			} else {
				$persent = 0;
				$delivaricompany = '';
			}

			$row[] = $rowdata->order_date;
			$row[] = $rowdata->saleinvoice;
			$row[] = $rowdata->customer_name;
			$row[] = $rowdata->first_name . $rowdata->last_name;
			$row[] = $rowdata->customer_type . $delivaricompany;
			$row[] = $rowdata->discount;
			$row[] = $persent;
			$row[] = $rowdata->totalamount;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"cardpayments" => $card,
			"Onlinepayment" => $online,
			"Cashpayment" => $cash,
			"recordsTotal" => $this->report_model->count_allsalesgtorder(),
			"recordsFiltered" => $this->report_model->count_filtersalesgtorder(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function itemsReport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report');
		$catid = $this->input->post('catid');

		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$billdateRange = "bill_date BETWEEN '$start_date%' AND '$end_date%'";
		$preports  = $this->report_model->itemsReport($start_date, $end_date);
		$i = 0;
		$order_ids = array('');
		foreach ($preports as $preport) {
			$order_ids[$i] = $preport->order_id;
			$i++;
		}

		$data['items']  = $this->report_model->order_items($order_ids, $catid);
		$addonsitem  = $this->report_model->order_itemsaddons($order_ids);
		$k = 0;
		$test = array();
		foreach ($addonsitem as $addonsall) {

			$addons = explode(",", $addonsall->add_on_id);
			$addonsqty = explode(",", $addonsall->addonsqty);
			$x = 0;
			foreach ($addons as $addonsid) {
				$test[$k][$addonsid] = $addonsqty[$x];
				$x++;
			}
			$k++;
		}

		$final = array();
		array_walk_recursive($test, function ($item, $key) use (&$final) {
			$final[$key] = isset($final[$key]) ?  $item + $final[$key] : $item;
		});
		$adobstotalprice = 0;
		if ($final) {
			foreach ($final as $key => $item) {
				$addonsinfo = $this->db->select("*")->from('add_ons')->where('add_on_id', $key)->get()->row();
				$adobstotalprice = $adobstotalprice + ($addonsinfo->price * $item);
			}
		}
		$totaldiscount = $this->db->select("SUM(discount) as totaldisamount")->from('bill')->where($billdateRange)->where('bill_status', 1)->where('isdelete!=', 1)->get()->row();

		$data['allorderid']  = $order_ids;
		$data['addonsprice']  = $adobstotalprice;
		$data['iscat']  = $catid;
		$data['totaldiscount']  = $totaldiscount->totaldisamount;
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['name'] = 'Items Name';
		$data['page']   = "ajaxsalereportitems";
		$this->load->view('report/ajaxsalereportitems', $data);
	}


	public function sellrptItems()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report_items');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['categorylist']   = $this->report_model->category_dropdown();
		$data['module'] = "report";
		$data['view'] = 'itemsReport';
		$data['page']   = "salereportfrmItems";
		echo Modules::run('template/layout', $data);
	}

	public function sellrptaddons()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report_items');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['view'] = 'addonsReport';
		$data['page']   = "salereportfrmaddons";
		echo Modules::run('template/layout', $data);
	}
	public function addonsReport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report');

		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$preports  = $this->report_model->itemsReport($start_date, $end_date);
		$catid = '';
		$i = 0;
		$order_ids = array('');
		foreach ($preports as $preport) {
			$order_ids[$i] = $preport->order_id;
			$i++;
		}

		$addonsitem  = $this->report_model->order_itemsaddons($order_ids);

		$k = 0;
		$test = array();
		foreach ($addonsitem as $addonsall) {

			$addons = explode(",", $addonsall->add_on_id);
			$addonsqty = explode(",", $addonsall->addonsqty);
			$x = 0;
			foreach ($addons as $addonsid) {
				$test[$k][$addonsid] = $addonsqty[$x];
				$x++;
			}
			$k++;
		}

		$final = array();
		array_walk_recursive($test, function ($item, $key) use (&$final) {
			$final[$key] = isset($final[$key]) ?  $item + $final[$key] : $item;
		});
		$data['addonsitem'] = $final;
		$data['allorderid']  = $order_ids;
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['name'] = 'Addons Name';
		$data['page']   = "ajaxsalereportaddons";
		$this->load->view('report/ajaxsalereportaddons', $data);
	}

	public function sellrptwaiter()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report_waiters');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['categorylist']   = '';
		$data['view'] = 'waitersReport';
		$data['page']   = "salereportfrmItems";
		echo Modules::run('template/layout', $data);
	}


	public function waitersReport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report');

		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$data['items']  = $this->report_model->order_waiters($start_date, $end_date);


		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['name'] = 'Waiter Name';
		$data['startdate'] = $start_date;
		$data['enddate'] = $end_date;
		$data['page']   = "ajaxsalereportitems";
		$this->load->view('report/ajaxsalereportitems', $data);
	}
	public function delviryReport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report');


		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$data['items']  = $this->report_model->order_delviry($start_date, $end_date);
		$billdateRange = "bill_date BETWEEN '$start_date%' AND '$end_date%'";
		$totaldiscount = $this->db->select("SUM(discount) as totaldisamount")->from('bill')->where($billdateRange)->where('bill_status', 1)->where('isdelete!=', 1)->get()->row();



		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['totaldiscount']  = $totaldiscount->totaldisamount;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['name'] = 'Delivery type';
		$data['page']   = "ajaxsalereportdelivery";
		$this->load->view('report/ajaxsalereportdelivery', $data);
	}



	public function sellrptdelvirytype()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report_delvirytype');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";

		$data['view'] = 'delviryReport';
		$data['page']   = "salereportfrmItems";
		echo Modules::run('template/layout', $data);
	}

	public function sellrptCasher()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report_Casher');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['categorylist']   = '';
		$data['view'] = 'casherReport';
		$data['page']   = "salereportfrmItems";
		echo Modules::run('template/layout', $data);
	}

	public function casherReport()
	{

		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report');
		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$data['items']  = $this->report_model->order_casher($start_date, $end_date);



		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['name'] = 'Casher name';
		$data['page']   = "ajaxsalereportitems";
		$this->load->view('report/ajaxsalereportitems', $data);
	}
	public function itemreportcashier()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report_Casher');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['salesmanlist'] = $this->report_model->salesmanlist();
		$data['module'] = "report";
		$data['categorylist']   = '';
		$data['page']   = "salereportcashier";
		echo Modules::run('template/layout', $data);
	}
	public function itemwisecashierReport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report');
		$salesman = $this->input->post('salesman', true);

		$start_date = $this->input->post('from_date');
		$end_date = $this->input->post('to_date');
		$data['preport']  = $this->report_model->salereportcashier($start_date, $end_date, $salesman);
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['page']   = "ajaxsalereportbycashier";
		$this->load->view('report/ajaxsalereportbycashier', $data);
	}

	public function unpaid_sell()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('unpaid_sell');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";

		$data['view'] = 'unpaidReport';
		$data['page']   = "salereportfrmunpaid";
		echo Modules::run('template/layout', $data);
	}

	public function unpaidReport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('unpaid_sell');



		$memberid = $this->input->post('memberid');

		$data['items']  = $this->report_model->show_marge_payment($memberid);

		$data['memberid'] = $memberid;

		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['name'] = display('ordid');
		$data['page']   = "ajaxsalereportunpaid";
		$this->load->view('report/ajaxsalereportunpaid', $data);
	}


	public function showpaymentmodal($id)
	{
		$marge = $this->report_model->show_marge_payment_modal($id);
		$data['marge'] = $marge;
		$data['paymentmethod']   = $this->report_model->pmethod_dropdown();

		$this->load->view('ordermanage/paymodal', $data);
	}


	public function kichansrpt()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('kitchen_sell');
		$data['kitchen'] = $this->report_model->allkitchan();
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['view'] = 'kichanReport';

		$data['page']   = "kicReport";
		echo Modules::run('template/layout', $data);
	}


	public function kichanReport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report');


		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$billdateRange = "bill_date BETWEEN '$start_date%' AND '$end_date%'";

		$i = 0;

		$findkicen = $this->report_model->kiread();

		$kichendata = array();
		$y = 0;
		foreach ($findkicen as $kitchen) {
			$preports  = $this->report_model->itemsKiReport($kitchen->kitchenid, $start_date, $end_date);
			//    d($preports);
			$totalamount = 0;
			$pricewithaddons = 0;
			foreach ($preports as $value) {

				$kitchenOrders = array();
				$kitchen_return_amount = 0;
				foreach ($preports as $kitchenOrder) {
					$kitchenOrders[] = $kitchenOrder->order_id;
				}
				$getKitchenReturnAmount = $this->db->select('SUM(return_amount) kithcen_return_amount')->from('bill')->where_in('return_order_id', $kitchenOrders)->get()->row();
				$kitchen_return_amount = (!empty($getKitchenReturnAmount->kithcen_return_amount) ? $getKitchenReturnAmount->kithcen_return_amount : 0);
				// d($kitchen_return_amount);

				if ($value->price > 0) {
					$newprice = $value->price;
				} else {
					$newprice = $value->mprice;
				}
				if ($value->OffersRate > 0) {

					$getdisprice = $newprice * $value->OffersRate / 100;
					$grprice = $newprice - $getdisprice;
					$itemprice = $value->menuqty * $grprice;
				} else {
					$itemprice = $value->menuqty * $newprice;
				}

				if ($countprice->add_on_id != NULL) {
					$add_on_ids = explode(',', $countprice->add_on_id);
					$add_on_qtys = explode(',', $countprice->addonsqty);
					$i = 0;
					foreach ($add_on_ids as $add_on_id) {
						$add_on_price = $this->report_model->findaddons($add_on_id);
						$pricewithaddons = $add_on_price->price * $add_on_qtys[$i];

						$i++;
					} //end foreach

				}
				$totalamount = $totalamount + $pricewithaddons + $itemprice;
			}
			//end foreach
			$kichendata[$y] =   array(
				'kiname' => $kitchen->kitchen_name,
				'totalprice' => $totalamount,
				'kitchenid' => $kitchen->kitchenid,
				'kitchen_return_amount' => $kitchen_return_amount
			);
			$y++;
		}

		$data['items'] = $kichendata;

		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$wherevat = "`bill_date` BETWEEN '" . $start_date . "' AND '" . $end_date . "' AND `bill_status`=1 AND isdelete!=1";
		$sdvat = $this->db->select("SUM(service_charge+VAT) as sdvat")->from('bill')->where($wherevat)->get()->row();
		$totaldiscount = $this->db->select("SUM(discount) as totaldisamount")->from('bill')->where($billdateRange)->where('bill_status', 1)->where('isdelete!=', 1)->get()->row();
		$data['vatsd'] = $sdvat->sdvat;
		$data['totaldiscount']  = $totaldiscount->totaldisamount;
		$data['module'] = "report";
		$data['name'] = 'Kitchen Name';
		$data['page']   = "kicanwiseReport";
		$this->load->view('report/kicanwiseReport', $data);
	}
	public function servicerpt()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('scharge_report');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['view'] = 'schargeReport';
		$data['page']   = "schargeReport";
		echo Modules::run('template/layout', $data);
	}


	public function schargeReport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report');


		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));


		$i = 0;
		$id = $this->input->post('orderid');
		$findkicen = $this->report_model->kiread($id);
		$data['allservicecharge']  = $this->report_model->serchargeReport($id, $start_date, $end_date);

		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['name'] = 'Order ID';
		$data['page']   = "servicechargewisereport";
		$this->load->view('report/servicechargewisereport', $data);
	}

	public function vatrpt()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('vat_report');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['view'] = 'vatReport';
		$data['page']   = "vatReport";
		echo Modules::run('template/layout', $data);
	}


	public function vatReport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report');


		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));


		$i = 0;
		$id = $this->input->post('orderid');
		$findkicen = $this->report_model->kiread($id);
		$data['allvat']  = $this->report_model->varReport($id, $start_date, $end_date);

		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['name'] = 'Order ID';
		$data['page']   = "vatwisereport";
		$this->load->view('report/vatwisereport', $data);
	}
	#payroll commission

	public function payroll_commission($id = null)
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('commission');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		if (!empty($id)) {
			$data['table_id'] = $id;
			$data['table_details'] = $this->db->select('tablename')->from('rest_table')->where('tableid', $id)->get()->row();
		}
		$data['module'] = "report";
		$data['view'] = 'showpayroll_commission';
		$data['page']   = "commissionReport";
		echo Modules::run('template/layout', $data);
	}

	public function showpayroll_commission()
	{
		$data['title']    = display('commission') . ' ' . display('report');

		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$table_id = $this->input->post('table_id');
		if (!empty($table_id)) {
			$data['showcommision'] = $this->report_model->showDataCommsion($start_date, $end_date, $table_id);
		} else {
			$data['showcommision'] = $this->report_model->showDataCommsion($start_date, $end_date);
		}

		$data['commissionRate'] = $this->report_model->showCommsionRate(6);


		$this->load->view('report/showcommision', $data);
	}

	public function table_sale()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('table');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['view'] = 'table_sale_show';
		$data['page']   = "salebytable";
		echo Modules::run('template/layout', $data);
	}

	public function table_sale_show()
	{
		$data['title']    = display('table') . ' ' . display('report');

		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$data['showcommision'] = $this->report_model->showDataTable($start_date, $end_date);
		$this->load->view('report/totaltablewisesale', $data);
	}
	public function cashregister()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('sell_report_cashregister');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$counterlist = $this->db->select('*')->from('tbl_cashcounter')->get()->result();
		$userlist = $this->db->select('tbl_cashregister.*,user.firstname,user.lastname')->from('tbl_cashregister')->join('user', 'user.id=tbl_cashregister.userid', 'left')->get()->result();
		$list[''] = 'Select Counter No';
		$list2[''] = 'Select Cashier';
		if (!empty($counterlist)) {
			foreach ($counterlist as $value)
				$list[$value->counterno] = $value->counterno;
		}
		$data['allcounter'] = $list;
		if (!empty($userlist)) {
			foreach ($userlist as $value)
				$list2[$value->userid] = $value->firstname . ' ' . $value->lastname;
		}
		$data['alluser'] = $list2;
		$data['module'] = "report";
		$data['page']   = "cashregister";
		echo Modules::run('template/layout', $data);
	}
	public function getcashregister()
	{
		$data['start_date'] = $this->input->post('from_date');
		$data['end_date'] = $this->input->post('to_date');
		$data['cashreport'] = $this->report_model->cashregister();
		$data['setting'] = $this->report_model->settinginfo();
		$this->load->view('report/cash_report', $data);
	}
	public function getcashregisterorder()
	{
		$this->permission->method('report', 'read')->redirect();
		$start_date = $this->input->post('startdate');
		$end_date = $this->input->post('enddate');
		$uid = $this->input->post('uid');
		$data['billeport'] = $this->report_model->cashregisterbill($start_date, $end_date, $uid);
		$this->load->view('report/details', $data);
	}

	public function downloadcashregister()
	{
		$startdate = $this->input->post('startdate');
		$enddate = $this->input->post('enddate');
		$saveid = $this->input->post('uid');
		$where = "opendate Between '$startdate' AND '$enddate'";
		$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $saveid)->where($where)->where('status', 1)->order_by('id', 'DESC')->get()->row();

		$iteminfo = $this->report_model->summeryiteminfo($saveid, $startdate, $enddate);
		$i = 0;
		$order_ids = array('');
		foreach ($iteminfo as $orderid) {
			$order_ids[$i] = $orderid->order_id;
			$i++;
		}
		$addonsitem  = $this->report_model->closingaddons($order_ids);
		$k = 0;
		$test = array();
		foreach ($addonsitem as $addonsall) {
			$addons = explode(",", $addonsall->add_on_id);
			$addonsqty = explode(",", $addonsall->addonsqty);
			$x = 0;
			foreach ($addons as $addonsid) {
				$test[$k][$addonsid] = $addonsqty[$x];
				$x++;
			}
			$k++;
		}

		$final = array();
		array_walk_recursive($test, function ($item, $key) use (&$final) {
			$final[$key] = isset($final[$key]) ?  $item + $final[$key] : $item;
		});
		$totalprice = 0;
		foreach ($final as $key => $item) {
			$addonsinfo = $this->db->select("*")->from('add_ons')->where('add_on_id', $key)->get()->row();
			$totalprice = $totalprice + ($addonsinfo->price * $item);
		}
		$data['get_cashregister_details'] = $this->db->select('a.*, b.title, b.amount')
			->from('tbl_cashregister_details a')
			->join('currencynotes_tbl b', 'b.id = a.note_id', 'left')
			->where('a.cashregister_id', $checkuser->id)
			->get()->result();
		$data['addonsprice'] = $totalprice;
		$data['registerinfo'] = $checkuser;
		$data['invsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		$data['billinfo'] = $this->report_model->billsummery($saveid, $startdate, $enddate);
		$data['totalamount'] = $this->report_model->collectcashsummery($saveid, $startdate, $enddate);
		$data['totalcreditsale'] = $this->report_model->collectduesalesummery($saveid, $checkuser->opendate, $checkuser->closedate);
		$data['totalreturnamount'] = $this->report_model->collectcashreturnsummery($saveid, $startdate, $enddate);
		$data['totalchange'] = $this->report_model->changecashsummery($saveid, $startdate, $enddate);
		$data['itemsummery'] = $this->report_model->closingiteminfo($order_ids);
		$data['settinginfo'] = $this->db->select('*')->from('setting')->get()->row();
		echo $viewprint = $this->load->view('cashclosingsummeryreportpdf', $data, true);
	}
	public function printcashregister()
	{
		$startdate = $this->input->post('startdate');
		$enddate = $this->input->post('enddate');
		$saveid = $this->input->post('uid');
		$where = "opendate Between '$startdate' AND '$enddate'";
		$checkuser = $this->db->select('*')->from('tbl_cashregister')->where('userid', $saveid)->where($where)->where('status', 1)->order_by('id', 'DESC')->get()->row();
		$data['get_cashregister_details'] = $this->db->select('a.*, b.title, b.amount')
			->from('tbl_cashregister_details a')
			->join('currencynotes_tbl b', 'b.id = a.note_id', 'left')
			->where('a.cashregister_id', $checkuser->id)
			->get()->result();

		$iteminfo = $this->report_model->summeryiteminfo($saveid, $startdate, $enddate);
		$i = 0;
		$order_ids = array('');
		foreach ($iteminfo as $orderid) {
			$order_ids[$i] = $orderid->order_id;
			$i++;
		}
		$addonsitem  = $this->report_model->closingaddons($order_ids);
		$k = 0;
		$test = array();
		foreach ($addonsitem as $addonsall) {
			$addons = explode(",", $addonsall->add_on_id);
			$addonsqty = explode(",", $addonsall->addonsqty);
			$x = 0;
			foreach ($addons as $addonsid) {
				$test[$k][$addonsid] = $addonsqty[$x];
				$x++;
			}
			$k++;
		}

		$final = array();
		array_walk_recursive($test, function ($item, $key) use (&$final) {
			$final[$key] = isset($final[$key]) ?  $item + $final[$key] : $item;
		});
		$totalprice = 0;
		foreach ($final as $key => $item) {
			$addonsinfo = $this->db->select("*")->from('add_ons')->where('add_on_id', $key)->get()->row();
			$totalprice = $totalprice + ($addonsinfo->price * $item);
		}
		$data['addonsprice'] = $totalprice;
		$data['registerinfo'] = $checkuser;
		$data['settinginfo'] = $this->db->select('*')->from('setting')->get()->row();
		$data['billinfo'] = $this->report_model->billsummery($saveid, $startdate, $enddate);
		$data['totalamount'] = $this->report_model->collectcashsummery($saveid, $startdate, $enddate);
		$data['totalcreditsale'] = $this->report_model->collectduesalesummery($saveid, $checkuser->opendate, $checkuser->closedate);
		$data['totalreturnamount'] = $this->report_model->collectcashreturnsummery($saveid, $startdate, $enddate);
		$data['totalchange'] = $this->report_model->changecashsummery($saveid, $startdate, $enddate);
		$data['itemsummery'] = $this->report_model->closingiteminfo($order_ids);
		$data['invsetting'] = $this->db->select('*')->from('tbl_invoicesetting')->where('invstid', 1)->get()->row();
		echo $viewprint = $this->load->view('cashclosingsummeryreport', $data, true);
	}



	public function third_party_sale_commission()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('commission');
		// $settinginfo=$this->report_model->settinginfo();
		// $data['setting']=$settinginfo;
		// $data['currency']=$this->report_model->currencysetting($settinginfo->currency);
		$data['thirdparty']   = $this->report_model->thirdparty_dropdown();
		$data['module'] = "report";
		$data['page']   = "third_party_sale_commission";
		echo Modules::run('template/layout', $data);
	}

	public function third_party_sale_commissionreport()
	{
		//   $this->permission->method('report','read')->redirect();
		$data['title']    = display('sell_report');

		$pid = $this->input->post('paytype', true);
		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$data['preport']  = $this->report_model->sale_commissionreport($start_date, $end_date, $pid);
		$settinginfo = $this->report_model->settinginfo();
		$data['payid'] = $pid;
		if (!empty($pid)) {
			$data['paycompany'] = $this->db->select("*")->from('tbl_thirdparty_customer')->where('companyId', $pid)->get()->row();
			//echo $this->db->last_query();
		}
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		// $data['module'] = "report";
		// $data['page']   = "ajaxthird_party_sale_commissionreport";  
		$this->load->view('report/ajaxthird_party_sale_commissionreport', $data);
	}


	public function payment_gateway_reportfrom()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = "Payment" . ' ' . 'Gateway' . ' ' . display("commission") . " " . display('report');
		$data['module'] = "report";
		$data['page']   = "payment_gateway_commission";
		echo Modules::run('template/layout', $data);
	}

	public function payment_gateway_report()
	{

		//   $this->permission->method('report','read')->redirect();
		$data['title']    = "Payment" . ' ' . 'Gateway' . ' ' . display("commission") . " " . display('report');
		$pid = ''; //$this->input->post('paytype',true);
		$invoie_no = ''; // $this->input->post('invoie_no',true);
		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$data['preport']  = $this->report_model->payment_g_commission_report($start_date, $end_date, $pid, $invoie_no);


		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		// $data['module'] = "report";
		// $data['page']   = "ajaxthird_party_sale_commissionreport";  
		$this->load->view('report/ajaxPaymentGatewayCommission', $data);
	}



	public function return_invoice()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title'] = display('return_invoice');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['paymentmethod']   = $this->report_model->pmethod_dropdown();

		$data['module'] = "report";
		$data['page']   = "return_invoice";
		echo Modules::run('template/layout', $data);
	}

	public function getReturnReport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('return_invoice');

		$pid = $this->input->post('paytype', true);
		$invoie_no = $this->input->post('invoie_no', true);

		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$data['preport']  = $this->report_model->getReturnReport($start_date, $end_date, $pid, $invoie_no);
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);

		$data['module'] = "report";
		$data['page']   = "ajaxsalereport";
		$this->load->view('report/ajaxGetReturnreport', $data);
	}


	public function returnreportlist()
	{
		$data['title'] = display('sale_return_report');

		// $data['returnitem']=$this->db->select("*,service_charge as serv_charge,totaldiscount as t_discount,total_vat as t_vat,totalamount as tamount")
		// ->from('sale_return')
		// ->join('customer_info','customer_info.customer_id=sale_return.customer_id')
		// // ->group_by('order_id')
		// ->get()->result();


		#-------------------------------#       
		#
		#pagination starts
		#

		$config["base_url"] = base_url('report/reports/returnreportlist/');
		$config["total_rows"]  = $this->report_model->count_order_returnlist();
		$config["per_page"]    = 10;
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
		$data["returnitem"] = $this->report_model->read_return_order($config["per_page"], $page);
		$data["links"] = $this->pagination->create_links();
		$data['pagenum'] = $page;

		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";

		$data['page']   = "orderreturnlist";
		echo Modules::run('template/layout', $data);
	}
	public function get_order_ReturnReport()
	{
		$this->permission->method('report', 'read')->redirect();

		$data['title']    = display('return_invoice');
		//   $pid = $this->input->post('paytype',true);
		$invoie_no = $this->input->post('invoie_no', true);
		$pay_type = $this->input->post('pay_type', true);
		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$data['preport']  = $this->report_model->get_order_ReturnReport($start_date, $end_date, $invoie_no, $pay_type);
		//   dd($data['preport']);
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['page']   = "ajax_orderReturn_report";
		$this->load->view('report/ajax_orderReturn_report', $data);
	}


	public function due_sale_report()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('due_sale_report');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['getActiveCustomers']   = $this->report_model->getActiveCustomers('1');

		$data['module'] = "report";
		$data['view'] = 'due_sale_report';
		$data['page']   = "due_sale_report";
		echo Modules::run('template/layout', $data);
	}


	public function getDueSaleReport()
	{
		$this->permission->method('report', 'read')->redirect();
		$data['title']    = display('due_sale_report');
		$customer_id = $this->input->post('customer_id', true);

		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$data['preport']  = $this->report_model->getDueSaleReport($start_date, $end_date, $customer_id);
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);

		$data['module'] = "report";
		$data['page']   = "ajaxduesalereport";
		$this->load->view('report/ajaxduesalereport', $data);
	}

	public function purchase_vat_report()
	{
		$this->permission->method('report', 'read')->redirect();
		// $list = $this->db->select('purchaseitem.*,supplier.supName')->from("purchaseitem")->join('supplier','purchaseitem.suplierID = supplier.supid','left')->order_by('purID', 'desc')->get()->result();
		$data['title']    = display('purchase_vat_report');
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		// $data['purchase_vat_report']   = $list;//$this->report_model->purchase_vat_report('1');
		$data['module'] = "report";
		// $data['view'] = 'purchase_vat_report';
		$data['page']   = "purchase_vat_report";
		echo Modules::run('template/layout', $data);
	}

	public function getPurchasevatReport()
	{
		// $this->permission->method('report','read')->redirect();
		$data['title']    = display('sell_report');
		$first_date = str_replace('/', '-', $this->input->post('from_date'));
		$start_date = date('Y-m-d', strtotime($first_date));
		$second_date = str_replace('/', '-', $this->input->post('to_date'));
		$end_date = date('Y-m-d', strtotime($second_date));
		$id = $this->input->post('invoice_id');
		$data['allvat']  = $this->report_model->purchase_vat_report($id, $start_date, $end_date);
		$settinginfo = $this->report_model->settinginfo();
		$data['setting'] = $settinginfo;
		$data['currency'] = $this->report_model->currencysetting($settinginfo->currency);
		$data['module'] = "report";
		$data['name'] = 'Order ID';
		$this->load->view('report/ajax_purchase_vat_report', $data);
	}
}
