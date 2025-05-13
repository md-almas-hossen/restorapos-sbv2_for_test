<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class V3 extends MY_Controller {

    protected $FILE_PATH;
    
    public function __construct()
    {
            parent::__construct();
			$this->load->library('lsoft_setting');
            $this->load->model('Api_kitchen_model');
            
            $this->FILE_PATH = base_url('upload/');
    }

    public function index()
    {
            redirect('myurl');
    }

    public function sign_in()
    {
            // TO DO / Email or Phone only one required
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required|xss_clean|trim');
            $this->form_validation->set_rules('token', 'token', 'required|xss_clean|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $errors = $this->form_validation->error_array();
                return $this->respondWithValidationError($errors);
            }
            else
            {
                $data['email']      = $this->input->post('email', TRUE);
                $data['password']   = $this->input->post('password', TRUE);

                $IsReg = $this->Api_kitchen_model->checkEmailOrPhoneIsRegistered('user', $data);

                if(!$IsReg) {
                    return $this->respondUserNotReg('This email or phone number has not been registered yet.');
                }
                $result = $this->Api_kitchen_model->authenticate_user('user', $data);
				$updatetData['waiter_kitchenToken']    			= $this->input->post('token', TRUE);
				$this->Api_kitchen_model->update_date('user', $updatetData, 'id', $result->id);
				$webseting =$this->Api_kitchen_model->read('powerbytxt,currency,servicecharge', 'setting', array('id' => 2));
				$currencyinfo = $this->Api_kitchen_model->read('currencyname,curr_icon', 'currency', array('currencyid' => $webseting->currency));
				$kitcheninfo = $this->Api_kitchen_model->readall('kitchen_id', 'tbl_assign_kitchen','kitchen_id', array('userid' => $result->id));
				$allkitchenid='';
				foreach($kitcheninfo as $kitchenid){
				    $allkitchenid.="'".$kitchenid->kitchen_id."',";
				}
				$allkitchenid=rtrim($allkitchenid,',');
                
                if ($result != FALSE) {
					$str = substr($result->picture, 2);
					$result->{"UserPictureURL"}=base_url().$str;
					$result->{"PowerBy"}=$webseting->powerbytxt;
					$result->{"currencycode"}=$currencyinfo->currencyname;
					$result->{"currencysign"}=$currencyinfo->curr_icon;
					$result->{"servicecharge"}=$webseting->servicecharge;
					$result->{"kitchenid"}=$allkitchenid;
                    return $this->respondWithSuccess('You have successfully logged in.', $result);
                } else {
                    return $this->respondWithError('The email and password you entered don\'t match.',$result);
                }
            }
    }
	
	public function kitchenlist(){
	         $this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');

            if ($this->form_validation->run() == FALSE)
            {
                $errors = $this->form_validation->error_array();
                return $this->respondWithValidationError($errors);
            }
            else
            {
              $kitchenuserid=$this->input->post('id', TRUE);
              $output=array();
              $kitcheninfo = $this->Api_kitchen_model->readallkitchen('tbl_kitchen.kitchenid,tbl_kitchen.kitchen_name,tbl_assign_kitchen.kitchen_id', 'tbl_assign_kitchen','tbl_assign_kitchen.kitchen_id', array('tbl_assign_kitchen.userid' => $kitchenuserid));
              $i=0;
              foreach($kitcheninfo as $kitchenid){
				    $output['kitchenlist'][$i]['kitchenid']=$kitchenid->kitchenid;
				    $output['kitchenlist'][$i]['kitchenname']=$kitchenid->kitchen_name;
				    $i++;
				}
				return $this->respondWithSuccess('All Kitchen list.', $output);
            }
	}
	/*public function allkitchenorderlist(){
			$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
			 if ($this->form_validation->run() == FALSE){
					$errors = $this->form_validation->error_array();
					return $this->respondWithValidationError($errors);
				}
				else{
					 $setting=$this->Api_kitchen_model->read('*', 'setting', array('id' => 2));
					 $waiterid=$this->input->post('id', TRUE);
					 $kitchenid=$this->input->post('kitchenid', TRUE);
					 $orderlist = $this->Api_kitchen_model->allkitchenorderlist($kitchenid);
					 //print_r($orderlist);
					 $output = $categoryIDs = array();
					 $hasitem='';
					if ($orderlist != FALSE) {
						$i=0;
						 foreach($orderlist as $order){
						     $con="order_menu.allfoodready IS NULL";
                            $orderdetails= $this->db->select('order_menu.*,tbl_kitchen_order.*')->from('tbl_kitchen_order')->join('order_menu','tbl_kitchen_order.orderid=order_menu.order_id')->where('order_menu.order_id',$order->order_id)->where($con)->group_by('order_menu.order_id')->group_by('order_menu.menu_id')->group_by('order_menu.varientid')->get()->result();	
				//echo $this->db->last_query();			 
                            
							 if(!empty($orderdetails)){
							 $output['orderinfo'][$i]['order_id']        = $order->order_id;
							 $output['orderinfo'][$i]['CustomerName']    = $order->customer_name;
							 $output['orderinfo'][$i]['TableName']       = $order->tablename;
							 $output['orderinfo'][$i]['OrderDate']       = $order->order_date;
							 $output['orderinfo'][$i]['TotalAmount']     = $order->totalamount;
							 $output['orderinfo'][$i]['CookedTime']      = $order->cookedtime;
							 $output['orderinfo'][$i]['notes']           = $order->customer_note;
								 
								 $k=0;
								 foreach($orderdetails as $item){
								  //print_r($item);
								  	 $acceptiteminfo= $this->db->select('*')->from('tbl_itemaccepted')->where('orderid',$order->order_id)->where('menuid',$item->menu_id)->where('varient',$item->varientid)->get()->row();
								     $iteminfo=$this->db->select('order_menu.*,item_foods.ProductsID,item_foods.ProductName,item_foods.is_customqty,variant.variantid,variant.variantName,variant.price')->from('order_menu')->join('item_foods','item_foods.ProductsID=order_menu.menu_id','left')->join('variant','variant.variantid=order_menu.varientid','left')->where('order_menu.menu_id',$item->menu_id)->where('variant.variantid',$item->varientid)->where('order_menu.order_id',$order->order_id)->get()->row();
									// echo $this->db->last_query();
								//print_r($iteminfo);
									 if(empty($iteminfo->allfoodready)){
									     $hasitem.="1,";
									 $output['orderinfo'][$i]['iteminfo'][$k]['order_id']       = $order->order_id;
									 $output['orderinfo'][$i]['iteminfo'][$k]['ProductsID']     =$iteminfo->ProductsID;
									 $output['orderinfo'][$i]['iteminfo'][$k]['ProductName']    =$iteminfo->ProductName;
									 $output['orderinfo'][$i]['iteminfo'][$k]['Kitchenid']       =$iteminfo->kitchenid;
									 $output['orderinfo'][$i]['iteminfo'][$k]['Varientid']      =$iteminfo->varientid;
								     $output['orderinfo'][$i]['iteminfo'][$k]['VarientName']      =$iteminfo->variantName;
									 $output['orderinfo'][$i]['iteminfo'][$k]['Itemqty']        = quantityshow($iteminfo->menuqty,$iteminfo->is_customqty);
									 $output['orderinfo'][$i]['iteminfo'][$k]['itemnote']        =$iteminfo->notes;
									 $output['orderinfo'][$i]['iteminfo'][$k]['food_status']    =$iteminfo->food_status;
									 if(empty($acceptiteminfo)){
											    $output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         =0; 
											 }else{
											     $output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         =1;
												 $output['orderinfo'][$i]['iteminfo'][$k]['AcceptTime']= date("H:i:s", strtotime($acceptiteminfo->accepttime));
											 }
									 if(!empty($item->add_on_id)){
									  $output['orderinfo'][$i]['iteminfo'][$k]['addons']        =1;
										 $addons=explode(",",$item->add_on_id);
										 $addonsqty=explode(",",$item->addonsqty);
										 $x=0;
										foreach($addons as $addonsid){
												$adonsinfo=$this->Api_kitchen_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
												$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsName']     =$adonsinfo->add_on_name;
												$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_id']      =$adonsinfo->add_on_id;
												$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_qty']     =$addonsqty[$x];
												$x++;
											}
									}
									 else{ $output['orderinfo'][$i]['iteminfo'][$k]['addons']        =0;}
									 $k++;
									 }
									 else{
									     $hasitem.="0,";
									   $output['orderinfo'][$i]['iteminfo']=array();  
									 }
									 } 
									 $i++;
								 } 
								else{
								     $hasitem.="0,";
								     $output['orderinfo'][$i]['order_id']        = $order->order_id;
        							 $output['orderinfo'][$i]['CustomerName']    = $order->customer_name;
        							 $output['orderinfo'][$i]['TableName']       = $order->tablename;
        							 $output['orderinfo'][$i]['OrderDate']       = $order->order_date;
        							 $output['orderinfo'][$i]['TotalAmount']     = $order->totalamount;
        							 $output['orderinfo'][$i]['notes']           = $order->customer_note;
									 $output['orderinfo'][$i]['iteminfo']=array();
									 $i++;
									}

						 }
						
						 if( strpos($hasitem, '1') !== false ) {
					       $output['hasitem'] =1;
				            }
        				 else{
        					 $output['hasitem'] =0;
        					 }
				
						return $this->respondWithSuccess('Pending Order List.', $output);
					} else {
						return $this->respondWithError('Order Not Found.!!!',$output);
					}
			}
		} */
	public function allkitchenorderlist()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$setting = $this->Api_kitchen_model->read('*', 'setting', array('id' => 2));
			$waiterid = $this->input->post('id', TRUE);
			$kitchenid = $this->input->post('kitchenid', TRUE);
			$orderlist = $this->Api_kitchen_model->kitchen_tokenlist($kitchenid,$status=1);
			//print_r($orderlist);
			$output = $categoryIDs = array();
			$hasitem = '';
			if ($orderlist != FALSE) {
				$i = 0;
				//echo "Hello";
				foreach ($orderlist as $order) {
					// print_r($order);
					$orderdetails = $this->Api_kitchen_model->apptokenorderkitchentoken($order->order_id, $order->kitchenid);

					if (!empty($orderdetails)) {
						$output['orderinfo'][$i]['order_id']        = $order->order_id;
						$output['orderinfo'][$i]['CustomerName']    = $order->customer_name;
						$output['orderinfo'][$i]['TableName']       = $order->tablename;
						$output['orderinfo'][$i]['OrderDate']       = $order->order_date;
						$output['orderinfo'][$i]['TotalAmount']     = $order->totalamount;
						$output['orderinfo'][$i]['CookedTime']      = $order->cookedtime;
						$output['orderinfo'][$i]['notes']           = $order->customer_note;

						$k = 0;
						foreach ($orderdetails as $item) {
							$acceptiteminfo = $this->db->select('*')->from('tbl_itemaccepted')->where('orderid', $order->order_id)->where('menuid', $item->menuid)->where('varient', $item->varientid)->get()->row();
							$output['orderinfo'][$i]['iteminfo'][$k]['order_id']       = $order->order_id;
							$output['orderinfo'][$i]['iteminfo'][$k]['ProductsID']     = $item->menuid;
							$output['orderinfo'][$i]['iteminfo'][$k]['ProductName']    = $item->ProductName;
							$output['orderinfo'][$i]['iteminfo'][$k]['Kitchenid']      = $kitchenid;
							$output['orderinfo'][$i]['iteminfo'][$k]['Varientid']      = $item->varientid;
							$output['orderinfo'][$i]['iteminfo'][$k]['VarientName']    = $item->variantName;
							$output['orderinfo'][$i]['iteminfo'][$k]['Itemqty']        = quantityshow($item->tqty, $iteminfo->is_customqty);
							$output['orderinfo'][$i]['iteminfo'][$k]['itemnote']       = $item->notes;
							$output['orderinfo'][$i]['iteminfo'][$k]['addonsuid']      = $item->addonsuid;
							$output['orderinfo'][$i]['iteminfo'][$k]['food_status']    = $item->foodstatus;
							if (empty($acceptiteminfo)) {
								$output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         = 0;
							} else {
								$output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         = 1;
								$output['orderinfo'][$i]['iteminfo'][$k]['AcceptTime'] = date("H:i:s", strtotime($acceptiteminfo->accepttime));
							}
							if (!empty($item->addonsid)) {
								$output['orderinfo'][$i]['iteminfo'][$k]['addons']        = 1;
								$addons = explode(",", $item->addonsid);
								$addonsqty = explode(",", $item->adonsqty);
								$x = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->Api_kitchen_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsName']     = $adonsinfo->add_on_name;
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_id']      = $adonsinfo->add_on_id;
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_qty']     = $addonsqty[$x];
									$x++;
								}
							} else {
								$output['orderinfo'][$i]['iteminfo'][$k]['addons'] = 0;
							}
							$k++;
						}
						$i++;
					}
				}


				return $this->respondWithSuccess('Pending Order List.', $output);
			} else {
				return $this->respondWithError('Order Not Found.!!!', $output);
			}
		}
	}
	public function orderlist()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('kitchenid', 'kitchenid', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$waiterid = $this->input->post('id', TRUE);
			$kitchenid = $this->input->post('kitchenid', TRUE);
			$orderlist = $this->Api_kitchen_model->orderlist($kitchenid);
			//print_r($orderlist);
			$output = $categoryIDs = array();
			$hasitem = '';
			if ($orderlist != FALSE) {
				$i = 0;
				foreach ($orderlist as $order) {
					$orderdetails = $this->Api_kitchen_model->apptokenorderkitchentoken($order->order_id, $kitchenid);

					if (!empty($orderdetails)) {
						$output['orderinfo'][$i]['order_id']        = $order->order_id;
						$output['orderinfo'][$i]['CustomerName']    = $order->customer_name;
						$output['orderinfo'][$i]['TableName']       = $order->tablename;
						$output['orderinfo'][$i]['OrderDate']       = $order->order_date;
						$output['orderinfo'][$i]['TotalAmount']     = $order->totalamount;
						$output['orderinfo'][$i]['CookedTime']      = $order->cookedtime;
						$output['orderinfo'][$i]['notes']           = $order->customer_note;

						$k = 0;
						foreach ($orderdetails as $item) {
							$acceptiteminfo = $this->db->select('*')->from('tbl_itemaccepted')->where('orderid', $order->order_id)->where('menuid', $item->menuid)->where('varient', $item->varientid)->get()->row();
							$output['orderinfo'][$i]['iteminfo'][$k]['order_id']       = $order->order_id;
							$output['orderinfo'][$i]['iteminfo'][$k]['ProductsID']     = $item->menuid;
							$output['orderinfo'][$i]['iteminfo'][$k]['ProductName']    = $item->ProductName;
							$output['orderinfo'][$i]['iteminfo'][$k]['Kitchenid']      = $kitchenid;
							$output['orderinfo'][$i]['iteminfo'][$k]['Varientid']      = $item->varientid;
							$output['orderinfo'][$i]['iteminfo'][$k]['VarientName']    = $item->variantName;
							$output['orderinfo'][$i]['iteminfo'][$k]['Itemqty']        = quantityshow($item->tqty, $iteminfo->is_customqty);
							$output['orderinfo'][$i]['iteminfo'][$k]['itemnote']        = $item->notes;
							$output['orderinfo'][$i]['iteminfo'][$k]['addonsuid']        = $item->addonsuid;
							$output['orderinfo'][$i]['iteminfo'][$k]['food_status']    = $item->foodstatus;
							if (empty($acceptiteminfo)) {
								$output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         = 0;
							} else {
								$output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         = 1;
								$output['orderinfo'][$i]['iteminfo'][$k]['AcceptTime'] = date("H:i:s", strtotime($acceptiteminfo->accepttime));
							}
							if (!empty($item->addonsid)) {
								$output['orderinfo'][$i]['iteminfo'][$k]['addons']        = 1;
								$addons = explode(",", $item->addonsid);
								$addonsqty = explode(",", $item->adonsqty);
								$x = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->Api_kitchen_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsName']     = $adonsinfo->add_on_name;
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_id']      = $adonsinfo->add_on_id;
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_qty']     = $addonsqty[$x];
									$x++;
								}
							} else {
								$output['orderinfo'][$i]['iteminfo'][$k]['addons'] = 0;
							}
							$k++;
						}
						$i++;
					}
				}
				return $this->respondWithSuccess('Pending Order List.', $output);
			} else {
				return $this->respondWithError('Order Not Found.!!!', $output);
			}
		}
	}
	public function readyorderlist()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('kitchenid', 'kitchenid', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$waiterid = $this->input->post('id', TRUE);
			$kitchenid = $this->input->post('kitchenid', TRUE);
			$orderlist = $this->Api_kitchen_model->kitchen_tokenlist($kitchenid,$status=2);
			//print_r($orderlist);
			$output = $categoryIDs = array();
			$hasitem = '';
			if ($orderlist != FALSE) {
				$i = 0;
				foreach ($orderlist as $order) {
					$orderdetails = $this->Api_kitchen_model->apptokenorderkitchentokenready($order->order_id, $order->kitchenid);
					//echo $this->db->last_query();			 
					if (!empty($orderdetails)) {
						$output['orderinfo'][$i]['order_id']        = $order->order_id;
						$output['orderinfo'][$i]['CustomerName']    = $order->customer_name;
						$output['orderinfo'][$i]['TableName']       = $order->tablename;
						$output['orderinfo'][$i]['OrderDate']       = $order->order_date;
						$output['orderinfo'][$i]['TotalAmount']     = $order->totalamount;
						$output['orderinfo'][$i]['CookedTime']      = $order->cookedtime;
						$output['orderinfo'][$i]['notes']           = $order->customer_note;

						$k = 0;
						foreach ($orderdetails as $item) {
							$acceptiteminfo = $this->db->select('*')->from('tbl_itemaccepted')->where('orderid', $order->order_id)->where('menuid', $item->menuid)->where('varient', $item->varientid)->get()->row();
							$output['orderinfo'][$i]['iteminfo'][$k]['order_id']       = $order->order_id;
							$output['orderinfo'][$i]['iteminfo'][$k]['ProductsID']     = $item->menuid;
							$output['orderinfo'][$i]['iteminfo'][$k]['ProductName']    = $item->ProductName;
							$output['orderinfo'][$i]['iteminfo'][$k]['Kitchenid']      = $kitchenid;
							$output['orderinfo'][$i]['iteminfo'][$k]['Varientid']      = $item->varientid;
							$output['orderinfo'][$i]['iteminfo'][$k]['VarientName']    = $item->variantName;
							$output['orderinfo'][$i]['iteminfo'][$k]['Itemqty']        = quantityshow($item->tqty, $iteminfo->is_customqty);
							$output['orderinfo'][$i]['iteminfo'][$k]['itemnote']        = $item->notes;
							$output['orderinfo'][$i]['iteminfo'][$k]['food_status']    = $item->foodstatus;
							if (empty($acceptiteminfo)) {
								$output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         = 0;
							} else {
								$output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         = 1;
								$output['orderinfo'][$i]['iteminfo'][$k]['AcceptTime'] = date("H:i:s", strtotime($acceptiteminfo->accepttime));
							}
							if (!empty($item->addonsid)) {
								$output['orderinfo'][$i]['iteminfo'][$k]['addons']        = 1;
								$addons = explode(",", $item->addonsid);
								$addonsqty = explode(",", $item->adonsqty);
								$x = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->Api_kitchen_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsName']     = $adonsinfo->add_on_name;
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_id']      = $adonsinfo->add_on_id;
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_qty']     = $addonsqty[$x];
									$x++;
								}
							} else {
								$output['orderinfo'][$i]['iteminfo'][$k]['addons'] = 0;
							}
							$k++;
						}
						$i++;
					}
				}

				

				return $this->respondWithSuccess('Ready Order List.', $output);
			} else {
				return $this->respondWithError('Order Not Found.!!!', $output);
			}
		}
	}
	/*public function orderlist(){
			$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
			$this->form_validation->set_rules('kitchenid', 'kitchenid', 'required|xss_clean|trim');
			 if ($this->form_validation->run() == FALSE){
					$errors = $this->form_validation->error_array();
					return $this->respondWithValidationError($errors);
				}
				else{
					 $waiterid=$this->input->post('id', TRUE);
					 $kitchenid=$this->input->post('kitchenid', TRUE);
					 $orderlist = $this->Api_kitchen_model->orderlist($kitchenid);
					 //print_r($orderlist);
					 $output = $categoryIDs = array();
					 $hasitem='';
					if ($orderlist != FALSE) {
						$i=0;
						 foreach($orderlist as $order){
						     $con="order_menu.allfoodready IS NULL";
                             $orderdetails=$this->db->select('order_menu.*,tbl_kitchen_order.*')->from('tbl_kitchen_order')->join('order_menu','tbl_kitchen_order.orderid=order_menu.order_id')->where('order_menu.order_id',$order->order_id)->where('tbl_kitchen_order.kitchenid',$kitchenid)->where($con)->group_by('order_menu.order_id')->group_by('order_menu.menu_id')->group_by('order_menu.varientid')->get()->result();	
				// echo $this->db->last_query();			 
                            
							 if(!empty($orderdetails)){
							 $output['orderinfo'][$i]['order_id']        = $order->order_id;
							 $output['orderinfo'][$i]['CustomerName']    = $order->customer_name;
							 $output['orderinfo'][$i]['TableName']       = $order->tablename;
							 $output['orderinfo'][$i]['OrderDate']       = $order->order_date;
							 $output['orderinfo'][$i]['TotalAmount']     = $order->totalamount;
							 $output['orderinfo'][$i]['CookedTime']      = $order->cookedtime;
							 $output['orderinfo'][$i]['notes']           = $order->customer_note;
								 
								 $k=0;
								 foreach($orderdetails as $item){
								  //print_r($item);
								  	 $acceptiteminfo= $this->db->select('*')->from('tbl_itemaccepted')->where('orderid',$order->order_id)->where('menuid',$item->menu_id)->where('varient',$item->varientid)->get()->row();
								     $iteminfo=$this->db->select('order_menu.*,item_foods.ProductsID,item_foods.ProductName,item_foods.is_customqty,variant.variantid,variant.variantName,variant.price')->from('order_menu')->join('item_foods','item_foods.ProductsID=order_menu.menu_id','left')->join('variant','variant.variantid=order_menu.varientid','left')->where('order_menu.menu_id',$item->menu_id)->where('variant.variantid',$item->varientid)->where('order_menu.order_id',$order->order_id)->get()->row();
									// echo $this->db->last_query();
								//print_r($iteminfo);
									 if(empty($iteminfo->allfoodready)){
									     $hasitem.="1,";
									 $output['orderinfo'][$i]['iteminfo'][$k]['order_id']       = $order->order_id;
									 $output['orderinfo'][$i]['iteminfo'][$k]['ProductsID']     =$iteminfo->ProductsID;
									 $output['orderinfo'][$i]['iteminfo'][$k]['ProductName']    =$iteminfo->ProductName;
									 $output['orderinfo'][$i]['iteminfo'][$k]['Varientid']      =$iteminfo->varientid;
								     $output['orderinfo'][$i]['iteminfo'][$k]['VarientName']      =$iteminfo->variantName;
									 $output['orderinfo'][$i]['iteminfo'][$k]['Itemqty']        =quantityshow($iteminfo->menuqty,$iteminfo->is_customqty);
									 $output['orderinfo'][$i]['iteminfo'][$k]['itemnote']        =$iteminfo->notes;
									 $output['orderinfo'][$i]['iteminfo'][$k]['food_status']    =$iteminfo->food_status;
									 if(empty($acceptiteminfo)){
											    $output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         =0; 
											 }else{
											     $output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         =1;
												 $output['orderinfo'][$i]['iteminfo'][$k]['AcceptTime']= date("H:i:s", strtotime($acceptiteminfo->accepttime));
											 }
									 if(!empty($item->add_on_id)){
									  $output['orderinfo'][$i]['iteminfo'][$k]['addons']        =1;
										 $addons=explode(",",$item->add_on_id);
										 $addonsqty=explode(",",$item->addonsqty);
										 $x=0;
										foreach($addons as $addonsid){
												$adonsinfo=$this->Api_kitchen_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
												$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsName']     =$adonsinfo->add_on_name;
												$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_id']      =$adonsinfo->add_on_id;
												$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_qty']     =$addonsqty[$x];
												$x++;
											}
									}
									 else{ $output['orderinfo'][$i]['iteminfo'][$k]['addons']        =0;}
									 $k++;
									 }
									 else{
									     $hasitem.="0,";
									   $output['orderinfo'][$i]['iteminfo']=array();  
									 }
									 } 
									 $i++;
								 } 
								else{
								     $hasitem.="0,";
								     $output['orderinfo'][$i]['order_id']        = $order->order_id;
        							 $output['orderinfo'][$i]['CustomerName']    = $order->customer_name;
        							 $output['orderinfo'][$i]['TableName']       = $order->tablename;
        							 $output['orderinfo'][$i]['OrderDate']       = $order->order_date;
        							 $output['orderinfo'][$i]['TotalAmount']     = $order->totalamount;
        							 $output['orderinfo'][$i]['notes']           = $order->customer_note;
									 $output['orderinfo'][$i]['iteminfo']=array();
									 $i++;
									}

						 }
						
						 if( strpos($hasitem, '1') !== false ) {
					       $output['hasitem'] =1;
				            }
        				 else{
        					 $output['hasitem'] =0;
        					 }
				
						return $this->respondWithSuccess('Pending Order List.', $output);
					} else {
						return $this->respondWithError('Order Not Found.!!!',$output);
					}
			}
		}
	public function readyorderlist(){
			$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
			$this->form_validation->set_rules('kitchenid', 'kitchenid', 'required|xss_clean|trim');
			 if ($this->form_validation->run() == FALSE){
					$errors = $this->form_validation->error_array();
					return $this->respondWithValidationError($errors);
				}
				else{
					 $waiterid=$this->input->post('id', TRUE);
					 $kitchenid=$this->input->post('kitchenid', TRUE);
					 $orderlist = $this->Api_kitchen_model->readyorderlist($kitchenid);
					 //print_r($orderlist);
					 $output = $categoryIDs = array();
					 $hasitem='';
					if ($orderlist != FALSE) {
						$i=0;
						 foreach($orderlist as $order){
						     $con="order_menu.allfoodready IS NOT NULL";
                             $orderdetails=$this->db->select('order_menu.*,tbl_kitchen_order.*')->from('tbl_kitchen_order')->join('order_menu','tbl_kitchen_order.orderid=order_menu.order_id')->where('order_menu.order_id',$order->order_id)->where('tbl_kitchen_order.kitchenid',$kitchenid)->where($con)->group_by('order_menu.order_id')->group_by('order_menu.menu_id')->group_by('order_menu.varientid')->get()->result();	
				//echo $this->db->last_query();			 
							 if(!empty($orderdetails)){
							 $output['orderinfo'][$i]['order_id']        = $order->order_id;
							 $output['orderinfo'][$i]['CustomerName']    = $order->customer_name;
							 $output['orderinfo'][$i]['TableName']       = $order->tablename;
							 $output['orderinfo'][$i]['OrderDate']       = $order->order_date;
							 $output['orderinfo'][$i]['TotalAmount']     = $order->totalamount;
							 $output['orderinfo'][$i]['CookedTime']      = $order->cookedtime;
							 $output['orderinfo'][$i]['notes']           = $order->customer_note;
								 
								 $k=0;
								 foreach($orderdetails as $item){
								  //print_r($item);
								  	 $acceptiteminfo= $this->db->select('*')->from('tbl_itemaccepted')->where('orderid',$order->order_id)->where('menuid',$item->menu_id)->where('varient',$item->varientid)->get()->row();
								     $iteminfo=$this->db->select('order_menu.*,item_foods.ProductsID,item_foods.ProductName,item_foods.is_customqty,variant.variantid,variant.variantName,variant.price')->from('order_menu')->join('item_foods','item_foods.ProductsID=order_menu.menu_id','left')->join('variant','variant.variantid=order_menu.varientid','left')->where('order_menu.menu_id',$item->menu_id)->where('variant.variantid',$item->varientid)->where('order_menu.order_id',$order->order_id)->get()->row();
									// echo $this->db->last_query();
								//print_r($iteminfo);
									 if(!empty($iteminfo->allfoodready)){
									     $hasitem.="1,";
									 $output['orderinfo'][$i]['iteminfo'][$k]['order_id']       = $order->order_id;
									 $output['orderinfo'][$i]['iteminfo'][$k]['ProductsID']     =$iteminfo->ProductsID;
									 $output['orderinfo'][$i]['iteminfo'][$k]['ProductName']    =$iteminfo->ProductName;
									 $output['orderinfo'][$i]['iteminfo'][$k]['Varientid']      =$iteminfo->varientid;
								     $output['orderinfo'][$i]['iteminfo'][$k]['VarientName']      =$iteminfo->variantName;
									 $output['orderinfo'][$i]['iteminfo'][$k]['Itemqty']        =quantityshow($iteminfo->menuqty,$iteminfo->is_customqty);
									 $output['orderinfo'][$i]['iteminfo'][$k]['itemnote']        =$iteminfo->notes;
									 $output['orderinfo'][$i]['iteminfo'][$k]['food_status']    =$iteminfo->food_status;
									 if(empty($acceptiteminfo)){
											    $output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         =0; 
											 }else{
											     $output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         =1;
												 $output['orderinfo'][$i]['iteminfo'][$k]['AcceptTime']= date("H:i:s", strtotime($acceptiteminfo->accepttime));
											 }
									 if(!empty($item->add_on_id)){
									  $output['orderinfo'][$i]['iteminfo'][$k]['addons']        =1;
										 $addons=explode(",",$item->add_on_id);
										 $addonsqty=explode(",",$item->addonsqty);
										 $x=0;
										foreach($addons as $addonsid){
												$adonsinfo=$this->Api_kitchen_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
												$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsName']     =$adonsinfo->add_on_name;
												$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_id']      =$adonsinfo->add_on_id;
												$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_qty']     =$addonsqty[$x];
												$x++;
											}
									}
									 else{ $output['orderinfo'][$i]['iteminfo'][$k]['addons']        =0;}
									 $k++;
									 }
									 else{
									     $hasitem.="0,";
									   $output['orderinfo'][$i]['iteminfo']=array();  
									 }
									 } 
									 $i++;
								 } 
								else{
								     $hasitem.="0,";
								     $output['orderinfo'][$i]['order_id']        = $order->order_id;
        							 $output['orderinfo'][$i]['CustomerName']    = $order->customer_name;
        							 $output['orderinfo'][$i]['TableName']       = $order->tablename;
        							 $output['orderinfo'][$i]['OrderDate']       = $order->order_date;
        							 $output['orderinfo'][$i]['TotalAmount']     = $order->totalamount;
        							 $output['orderinfo'][$i]['notes']           = $order->customer_note;
									 $output['orderinfo'][$i]['iteminfo']=array();
									 $i++;
									}

						 }
						
						 if( strpos($hasitem, '1') !== false ) {
					       $output['hasitem'] =1;
				            }
        				 else{
        					 $output['hasitem'] =0;
        					 }
				
						return $this->respondWithSuccess('Pending Order List.', $output);
					} else {
						return $this->respondWithError('Order Not Found.!!!',$output);
					}
			}
		}*/
	public function completeorcancel(){
		$this->form_validation->set_rules('Orderid', 'Orderid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('kitchenid', 'kitchenid', 'required|xss_clean|trim');
		 	if ($this->form_validation->run() == FALSE){
					$errors = $this->form_validation->error_array();
					return $this->respondWithValidationError($errors);
				}
			else{
		 $updatetData = array('order_status'     => 2);
		 $this->db->where('order_id',$orderid);
		 $this->db->update('customer_order',$updatetData);
		 $orderid=$this->input->post('Orderid', TRUE);
		 $kitchenid=$this->input->post('kitchenid', TRUE);
		 $output = $categoryIDs = array();
		 $customerorder=$this->Api_kitchen_model->read('*', 'customer_order', array('order_id' => $orderid));
		 
		 $customerinfo=$this->Api_kitchen_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		 $tableinfo=$this->Api_kitchen_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		 $typeinfo=$this->Api_kitchen_model->read('*', 'customer_type', array('customer_type_id' => $customerorder->cutomertype));
		
		  $orderdetails=$this->db->select('order_menu.*,item_foods.ProductsID,item_foods.ProductName,item_foods.is_customqty,variant.variantid,variant.variantName,variant.price')->from('order_menu')->join('customer_order','order_menu.order_id=customer_order.order_id','left')->join('item_foods','order_menu.menu_id=item_foods.ProductsID','left')->join('variant','order_menu.varientid=variant.variantid','left')->where('order_menu.order_id',$orderid)->where('item_foods.kitchenid',$kitchenid)->order_by('customer_order.order_id','desc')->get()->result();
		   //
		 $billinfo=$this->Api_kitchen_model->read('*', 'bill', array('order_id' => $orderid));
		
		 if(!empty($orderdetails)){
			 $output['CustomerName']=$customerinfo->customer_name;  
			 $output['CustomerPhone']=$customerinfo->customer_phone;
			 $output['CustomerEmail']=$customerinfo->customer_email;
			 $output['CustomerType']=$typeinfo->customer_type;
			 $output['TableName']=$tableinfo->tablename;
			 $i=0;
			  
			 foreach($orderdetails as $item){
			     $itemtotal=$item->menuqty*$item->price;
				 $output['iteminfo'][$i]['ProductsID']     =$item->ProductsID;
				 $output['iteminfo'][$i]['ProductName']    =$item->ProductName;
				 $output['iteminfo'][$i]['price']    	   =$item->price;
				 $output['iteminfo'][$i]['Varientname']    =$item->variantName;
				 $output['iteminfo'][$i]['Varientid']      =$item->variantid;
				 $output['iteminfo'][$i]['Itemqty']        =quantityshow($item->menuqty,$item->is_customqty);
				 $output['iteminfo'][$i]['Itemtotal']      =number_format($itemtotal,2);
				 if(!empty($item->add_on_id)){
				  $output['iteminfo'][$i]['addons']        =1;
					 $addons=explode(",",$item->add_on_id);
					 $addonsqty=explode(",",$item->addonsqty);
					 $x=0;
					foreach($addons as $addonsid){
							$adonsinfo=$this->Api_kitchen_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$output['iteminfo'][$i]['addonsinfo'][$x]['addonsName']     =$adonsinfo->add_on_name;
							$output['iteminfo'][$i]['addonsinfo'][$x]['add_on_id']      =$adonsinfo->add_on_id;
							$output['iteminfo'][$i]['addonsinfo'][$x]['price']      	=$adonsinfo->price;
							$output['iteminfo'][$i]['addonsinfo'][$x]['add_on_qty']     =$addonsqty[$x];
							$x++;
						}
				}
			     else{ $output['iteminfo'][$i]['addons']        =0;}
				 
				 $i++;
				 }   
			 $output['Subtotal']              =$billinfo->total_amount;
			 $output['discount']              =$billinfo->discount;
			 $output['service_charge']        =$billinfo->service_charge;
			 $output['VAT']        			  =$billinfo->VAT;
			 $output['order_total']           =$billinfo->bill_amount;
			 $output['orderdate']             =$billinfo->bill_date;
			 
			return $this->respondWithSuccess('Order Details', $output);
		 }
		 else{
			return $this->respondWithError('Order Not Found.!!!',$output); 
			 }
		 }
		}
	public function foodisready(){
		$this->form_validation->set_rules('Orderid', 'Orderid', 'required|xss_clean|trim');
		$this->form_validation->set_rules('ProductsID', 'Products ID', 'required|xss_clean|trim');
		$this->form_validation->set_rules('variantid', 'Varient ID', 'required|xss_clean|trim');
		$this->form_validation->set_rules('isready', 'isready', 'required|xss_clean|trim');
		$this->form_validation->set_rules('kitchenid', 'kitchenid', 'required|xss_clean|trim');
		 	if ($this->form_validation->run() == FALSE){
					$errors = $this->form_validation->error_array();
					return $this->respondWithValidationError($errors);
				}
			else{
		$ProductsID = $this->input->post('ProductsID', TRUE);
		$variantid = $this->input->post('variantid', TRUE);
		$fisready= $this->input->post('isready', TRUE);
		$kitchenid=$this->input->post('kitchenid', TRUE);
		$itemuid = $this->input->post('addonsuid', TRUE);
		
		$output = $categoryIDs = array();
		 $output['isready']        =$fisready;
		 $orderid=$this->input->post('Orderid', TRUE);
		 $updatetData = array('order_status'     => 2);
		 $this->db->where('order_id',$orderid);
		 $this->db->update('customer_order',$updatetData);

			   $updatetfood = array('food_status'=> $this->input->post('isready', TRUE));
		       $this->db->where('order_id',$orderid);
			   $this->db->where('menu_id',$ProductsID);
			   $this->db->where('varientid',$variantid);
		       $this->db->update('order_menu',$updatetfood);
		       
		    $appupdatetfood = array('foodstatus' => $this->input->post('isready', TRUE));
			$this->db->where('ordid', $orderid);
			$this->db->where('addonsuid', $itemuid);
			$this->db->where('foodstatus!=', 2);
			$this->db->update('tbl_apptokenupdate', $appupdatetfood);
            //echo $this->db->last_query();
		 
		 $customerorder=$this->Api_kitchen_model->read('*', 'customer_order', array('order_id' => $orderid));
		 
		 $customerinfo=$this->Api_kitchen_model->read('*', 'customer_info', array('customer_id' => $customerorder->customer_id));
		 $tableinfo=$this->Api_kitchen_model->read('*', 'rest_table', array('tableid' => $customerorder->table_no));
		 $typeinfo=$this->Api_kitchen_model->read('*', 'customer_type', array('customer_type_id' => $customerorder->cutomertype));
		 $isexit=$this->db->select('*')->from('tbl_orderprepare')->where('orderid',$orderid)->where('menuid',$ProductsID)->where('varient',$variantid)->get()->row();
		  $orderdetails=$this->db->select('order_menu.*,item_foods.ProductsID,item_foods.ProductName,item_foods.is_customqty,variant.variantid,variant.variantName,variant.price')->from('order_menu')->join('customer_order','order_menu.order_id=customer_order.order_id','left')->join('item_foods','order_menu.menu_id=item_foods.ProductsID','left')->join('variant','order_menu.varientid=variant.variantid','left')->where('order_menu.order_id',$orderid)->where('item_foods.kitchenid',$kitchenid)->order_by('customer_order.order_id','desc')->get()->result();
		   //
		 $billinfo=$this->Api_kitchen_model->read('*', 'bill', array('order_id' => $orderid));
	
		 if(!empty($orderdetails)){
			 $i=0;
			  
			 foreach($orderdetails as $item){
				 if($item->food_status==1){
					 $ready="Food Is Ready";
					 if(empty($isexit)){
						$ready = array(
						'preparetime' => date('Y-m-d H:i:s'),
						'orderid'     => $orderid,
						'menuid'     => $ProductsID,
						'varient'     => $variantid
					  );
					 $this->db->insert('tbl_orderprepare',$ready);
						}
					 //push 
					$waiteridp=$customerorder->waiter_id;
            		$this->db->select('*');
            		$this->db->from('user');
            		$this->db->where('id',$waiteridp);
            		$query = $this->db->get();
            		$allemployee = $query->row();
            		$senderid[]=$allemployee->waiter_kitchenToken;
            		define( 'API_ACCESS_KEY', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
				$registrationIds = $senderid;
				$msg = array
				(
					'message' 					=> "Orderid: ".$orderid.", Item Name: ".$item->ProductName." Amount:".$customerorder->totalamount,
					'title'						=> "Food Is Ready.",
					'subtitle'					=> $orderid,
					'tickerText'				=> "TSET",
					'vibrate'					=> 1,
					'sound'						=> 1,
					'largeIcon'					=> "TSET",
					'smallIcon'					=> "TSET"
				);
				$fields2 = array
				(
					'registration_ids' 	=> $registrationIds,
					'data'			=> $msg
				);
				 
				$headers2 = array
				(
					'Authorization: key=' . API_ACCESS_KEY,
					'Content-Type: application/json'
				);
				 
				$ch2 = curl_init();
				curl_setopt( $ch2,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
				curl_setopt( $ch2,CURLOPT_POST, true );
				curl_setopt( $ch2,CURLOPT_HTTPHEADER, $headers2 );
				curl_setopt( $ch2,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch2,CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch2,CURLOPT_POSTFIELDS, json_encode( $fields2 ) );
				$result2 = curl_exec($ch2 );
				curl_close( $ch2 );
				/*End Notification*/
				 }
				 else{
					 $ready="Food Is Cooking";
					 //push 
					$waiteridp=$customerorder->waiter_id;
            		$this->db->select('*');
            		$this->db->from('user');
            		$this->db->where('id',$waiteridp);
            		$query = $this->db->get();
            		$allemployee = $query->row();
            		$senderid[]=$allemployee->waiter_kitchenToken;
            		define( 'API_ACCESS_KEY', 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX');
				$registrationIds = $senderid;
				$msg = array
				(
					'message' 					=> "Orderid: ".$orderid.", Item Name: ".$item->ProductName." Amount:".$customerorder->totalamount,
					'title'						=> "Processing",
					'subtitle'					=> $orderid,
					'tickerText'				=> "TSET",
					'vibrate'					=> 1,
					'sound'						=> 1,
					'largeIcon'					=> "TSET",
					'smallIcon'					=> "TSET"
				);
				$fields2 = array
				(
					'registration_ids' 	=> $registrationIds,
					'data'			=> $msg
				);
				 
				$headers2 = array
				(
					'Authorization: key=' . API_ACCESS_KEY,
					'Content-Type: application/json'
				);
				 
				$ch2 = curl_init();
				curl_setopt( $ch2,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
				curl_setopt( $ch2,CURLOPT_POST, true );
				curl_setopt( $ch2,CURLOPT_HTTPHEADER, $headers2 );
				curl_setopt( $ch2,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch2,CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch2,CURLOPT_POSTFIELDS, json_encode( $fields2 ) );
				$result2 = curl_exec($ch2 );
				curl_close( $ch2 );
				/*End Notification*/
				 }
				 if(!empty($item->add_on_id)){
				
					 $addons=explode(",",$item->add_on_id);
					 $addonsqty=explode(",",$item->addonsqty);
					 $x=0;
					foreach($addons as $addonsid){
							$adonsinfo=$this->Api_kitchen_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$x++;
						}
				}
			     else{ 
				 }
				 
				 $i++;
				 }   
			
			return $this->respondWithSuccess('Order Details', $output);
		 }
		 else{
			return $this->respondWithError('Order Not Found.!!!',$output); 
			 }
		 }
		}
	public function markasready(){
	        $this->form_validation->set_rules('orderid', 'Order ID', 'required|xss_clean|trim');
			 $this->form_validation->set_rules('foodid', 'Food ID', 'required|xss_clean|trim');
			 if ($this->form_validation->run() == FALSE){
					$errors = $this->form_validation->error_array();
					return $this->respondWithValidationError($errors);
				}
				else{
				   $output=array();
				   $order_id=$this->input->post('orderid', TRUE); 
				   $allfood=$this->input->post('foodid', TRUE); 
				   $vaids=$this->input->post('varientid', TRUE); 
				   $allfood_id=explode(",",$allfood);
				    $m=0;
				   foreach($allfood_id as $foodid){
				     $updatetready = array(
					    'food_status'           => 1,
				        'allfoodready'           => 1
				        );
		        $this->db->where('order_id',$order_id);
		        $this->db->where('menu_id',$foodid);
				$this->db->update('order_menu',$updatetready); 
			
				$appupdatetfood = array('foodstatus' => 2);
				$this->db->where('ordid', $order_id);
				$this->db->update('tbl_apptokenupdate', $appupdatetfood);
				//echo $this->db->last_query();
				
					 $isexit=$this->db->select('*')->from('tbl_orderprepare')->where('orderid',$order_id)->where('menuid',$foodid)->where('varient',$vaids)->get()->row();
					 if(empty($isexit)){
						$ready = array(
						'preparetime' => date('Y-m-d H:i:s'),
						'orderid'     => $orderid,
						'menuid'     => $menuid,
						'varient'     => $vaids[$m]
					  );
					 $this->db->insert('tbl_orderprepare',$ready);
					  $m++;
						} 
				   }
				  
				   $updatetData =array('order_status'     => 3);
				   $this->db->where('order_id',$order_id);
				   $this->db->update('customer_order',$updatetData);
				   
				    return $this->respondWithSuccess('All Item is ready for this Kitchen order', $output);
				}
	    
	}
	public function allonlineorder()
	{
		$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
		$this->form_validation->set_rules('kitchenid', 'kitchenid', 'required|xss_clean|trim');
		if ($this->form_validation->run() == FALSE) {
			$errors = $this->form_validation->error_array();
			return $this->respondWithValidationError($errors);
		} else {
			$output = $categoryIDs = array();
			$waiterid = $this->input->post('id', TRUE);
			$kitchenid = $this->input->post('kitchenid', TRUE);
			$orderlist = $this->Api_kitchen_model->kitchen_tokenlist($kitchenid,$status=1);
		//print_r($orderlist);
			$output['orderinfo'] = array();
			if (!empty($orderlist)) {
				$i = 0;
				$allkitchenresult = '';

				foreach ($orderlist as $order) {
					$kitchenorder = $this->Api_kitchen_model->apptokenorderkitchentokenpending($order->order_id, $kitchenid);
					//$iteminfo = $this->db->select('order_menu.*,item_foods.*,variant.variantid,variant.variantName,variant.price')->from('order_menu')->join('customer_order', 'order_menu.order_id=customer_order.order_id', 'left')->join('item_foods', 'order_menu.menu_id=item_foods.ProductsID', 'left')->join('variant', 'order_menu.varientid=variant.variantid', 'left')->where('item_foods.kitchenid', $kitchenid)->where('order_menu.order_id', $order->order_id)->get()->result();
						//print_r($kitchenorder);
					if (!empty($kitchenorder)) {
						$output['orderinfo'][$i]['orderid']        = $order->order_id;
						$output['orderinfo'][$i]['customer']    = $order->customer_name;
						$output['orderinfo'][$i]['TableName']       = $order->tablename;
						$output['orderinfo'][$i]['OrderDate']       = $order->order_date;
						$output['orderinfo'][$i]['amount']     = $order->totalamount;
						$output['orderinfo'][$i]['OrderTime']      = $order->cookedtime;
						$output['orderinfo'][$i]['notes']           = $order->customer_note;

						$k = 0;
						foreach ($kitchenorder as $item) {
						    //print_r($item);
							$acceptiteminfo = $this->db->select('*')->from('tbl_itemaccepted')->where('orderid', $order->order_id)->where('menuid', $item->menuid)->where('varient', $item->varientid)->get()->row();
							$output['orderinfo'][$i]['iteminfo'][$k]['order_id']       = $order->order_id;
							$output['orderinfo'][$i]['iteminfo'][$k]['ProductsID']     = $item->menuid;
							$output['orderinfo'][$i]['iteminfo'][$k]['ProductName']    = $item->ProductName;
							$output['orderinfo'][$i]['iteminfo'][$k]['component']      = $item->component;
							$output['orderinfo'][$i]['iteminfo'][$k]['destcription']   = $item->descrip;
							$output['orderinfo'][$i]['iteminfo'][$k]['itemnotes']      = $item->itemnotes;
							$output['orderinfo'][$i]['iteminfo'][$k]['Kitchenid']      = $kitchenid;
							$output['orderinfo'][$i]['iteminfo'][$k]['Varientid']      = $item->varientid;
							$output['orderinfo'][$i]['iteminfo'][$k]['VarientName']    = $item->variantName;
							$output['orderinfo'][$i]['iteminfo'][$k]['Itemqty']        = quantityshow($item->tqty, $iteminfo->is_customqty);
							$output['orderinfo'][$i]['iteminfo'][$k]['addonsuid']      = $item->addonsuid;
							$output['orderinfo'][$i]['iteminfo'][$k]['itemnote']       = $item->notes;
							$output['orderinfo'][$i]['iteminfo'][$k]['food_status']    = $item->foodstatus;
							if (empty($acceptiteminfo)) {
								$output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         = 0;
							} else {
								$output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         = 1;
								//$output['orderinfo'][$i]['iteminfo'][$k]['AcceptTime'] = date("H:i:s", strtotime($acceptiteminfo->accepttime));
							}
							if (!empty($item->addonsid)) {
								$output['orderinfo'][$i]['iteminfo'][$k]['addons']        = 1;
								$addons = explode(",", $item->addonsid);
								$addonsqty = explode(",", $item->adonsqty);
								$x = 0;
								foreach ($addons as $addonsid) {
									$adonsinfo = $this->Api_kitchen_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_name']     = $adonsinfo->add_on_name;
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsid']      = $adonsinfo->add_on_id;
									$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsquantity']     = $addonsqty[$x];
									$x++;
								}
							} else {
								$output['orderinfo'][$i]['iteminfo'][$k]['addons'] = 0;
							}
							$k++;
						}
						$i++;
						
					}
				}
				return $this->respondWithSuccess('Incomming Order List', $output);
				
			} else {
				return $this->respondWithError('No Incomming Order Found!!!', $output);
			}
		}
	}
		
	public function allkitchenonlineorder(){
			 $this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
			 if ($this->form_validation->run() == FALSE){
					$errors = $this->form_validation->error_array();
					return $this->respondWithValidationError($errors);
				}
				else{
					 $output = $categoryIDs = array();
					 $waiterid=$this->input->post('id', TRUE);
					 $kitchenid=$this->input->post('kitchenid', TRUE);
					 $orderlist = $this->Api_kitchen_model->allkitchenincomminglist();
					 $output['orderinfo']=array();
					 if(!empty($orderlist)){
						 $i=0;
						 $allkitchenresult='';
						 
						 foreach($orderlist as $order){
						     $kitchenorder = $this->Api_kitchen_model->allincommingkitchen($order->order_id,$kitchenid);
							 $allkitchenresult=$kitchenorder.',';
							 $iteminfo=$this->db->select('order_menu.*,item_foods.*,variant.variantid,variant.variantName,variant.price')->from('order_menu')->join('customer_order','order_menu.order_id=customer_order.order_id','left')->join('item_foods','order_menu.menu_id=item_foods.ProductsID','left')->join('variant','order_menu.varientid=variant.variantid','left')->where('order_menu.order_id',$order->order_id)->get()->result();
						     if($kitchenorder==1){
							 $output['orderinfo'][$i]['orderid']=$order->order_id;
							 $output['orderinfo'][$i]['customer']=$order->customer_name;
							 $output['orderinfo'][$i]['amount']=$order->totalamount;
							 $output['orderinfo'][$i]['OrderDate'] = $order->order_date;
							 $output['orderinfo'][$i]['OrderTime'] = $order->order_time;
							  if(!empty($iteminfo)){
								 $output['orderinfo'][$i]['itemexist']=1;
									$k=0;
									foreach($iteminfo as $item){
											 $acceptiteminfo= $this->db->select('*')->from('tbl_itemaccepted')->where('orderid',$order->order_id)->where('menuid',$item->ProductsID)->where('varient',$item->variantid)->get()->row();
											 $output['orderinfo'][$i]['iteminfo'][$k]['ProductsID']     =$item->ProductsID;
											 $output['orderinfo'][$i]['iteminfo'][$k]['ProductName']     =$item->ProductName;
											 $output['orderinfo'][$i]['iteminfo'][$k]['Kitchenid']       =$item->kitchenid;
											 $output['orderinfo'][$i]['iteminfo'][$k]['price']    		=$item->price;
											 $output['orderinfo'][$i]['iteminfo'][$k]['component']       =$item->component;
											 $output['orderinfo'][$i]['iteminfo'][$k]['destcription']    =$item->descrip;
											 $output['orderinfo'][$i]['iteminfo'][$k]['itemnotes']       =$item->itemnotes;
											 $output['orderinfo'][$i]['iteminfo'][$k]['productvat']      =$item->productvat;
											 $output['orderinfo'][$i]['iteminfo'][$k]['offerIsavailable']=$item->offerIsavailable;
											 $output['orderinfo'][$i]['iteminfo'][$k]['offerstartdate']  =$item->offerstartdate;
											 $output['orderinfo'][$i]['iteminfo'][$k]['OffersRate']      =$item->OffersRate;
											 $output['orderinfo'][$i]['iteminfo'][$k]['offerendate']     =$item->offerendate;
											 $output['orderinfo'][$i]['iteminfo'][$k]['ProductImage']    =base_url().$item->ProductImage;
											 $output['orderinfo'][$i]['iteminfo'][$k]['Varientname']     =$item->variantName;
											 $output['orderinfo'][$i]['iteminfo'][$k]['Varientid']       =$item->variantid;
											 $output['orderinfo'][$i]['iteminfo'][$k]['Itemqty']         =quantityshow($item->menuqty,$item->is_customqty);
											 if(empty($acceptiteminfo)){
											    $output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         =0; 
											 }else{
											     $output['orderinfo'][$i]['iteminfo'][$k]['Itemaccepted']         =1;
											 }
											 if(!empty($item->add_on_id)){
											$output['orderinfo'][$i]['iteminfo'][$k]['addons']         =1;
												 $addons=explode(",",$item->add_on_id);
												 $addonsqty=explode(",",$item->addonsqty);
												 $x=0;
												foreach($addons as $addonsid){
														$adonsinfo=$this->Api_kitchen_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
														$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_name']     =$adonsinfo->add_on_name;
														$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsid']      =$adonsinfo->add_on_id;
														$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsprice']          =$adonsinfo->price;
														$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsquantity']     =$addonsqty[$x];
														$x++;
													}
											}
											else{
												$output['orderinfo'][$i]['iteminfo'][$k]['addons']         =0;
												}
											$k++;
										}
								}else{
							 	$output['orderinfo'][$i]['itemexist']=0;	
							 	}
							  $i++;
						        }
							 }
						  	if(strpos($allkitchenresult, '0') !== false ) {
						     	$output['hasitem']=0;
        					 	return $this->respondWithSuccess('Incomming Order List', $output); 
        				 	}
        				 	else{
								$output['hasitem']=1;
        				     return $this->respondWithSuccess('Incomming Order List', $output);
        					 }
						 }
					 else{
						  return $this->respondWithError('No Incomming Order Found!!!',$output);
						 }
					
				}
		}
		
	public function viewonlineorder(){
			 $this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
			 $this->form_validation->set_rules('kitchenid', 'kitchenid', 'required|xss_clean|trim');
			 $this->form_validation->set_rules('order_id', 'Order ID', 'required|xss_clean|trim');
			 if ($this->form_validation->run() == FALSE){
					$errors = $this->form_validation->error_array();
					return $this->respondWithValidationError($errors);
				}
				else{
					 $output = $categoryIDs = array();
					 $waiterid=$this->input->post('id', TRUE);
					 $kitchenid=$this->input->post('kitchenid', TRUE);
					 $order_id=$this->input->post('order_id', TRUE); 
					 
					 $orderlist = $this->Api_kitchen_model->viewincommingkitchen($order_id,$kitchenid);
					 
					 if(!empty($orderlist)){
						 $i=0;
						 foreach($orderlist as $order){
						      $kitchenorder = $this->Api_kitchen_model->allincommingkitchenview($order->order_id,$kitchenid,$order->menu_id,$order->variantid);
						      if($kitchenorder==1){
						       $output['foodinfo'][$i]['OrderID']=$order->order_id;
						       $output['foodinfo'][$i]['FoodID']=$order->menu_id;
						       $output['foodinfo'][$i]['FoodName']=$order->ProductName;
						       $output['foodinfo'][$i]['qty']=$order->menuqty;
							   $output['foodinfo'][$i]['varientid']=$order->variantid;
							   $output['foodinfo'][$i]['varientname']=$order->variantName;
		                        if(!empty($order->add_on_id)){
		                            $output['foodinfo'][$i]['addons']=1;
			                        $addons=explode(",",$order->add_on_id);
			                        $addonsqty=explode(",",$order->addonsqty);
			                        $x=0;
			                        foreach($addons as $addonsid){
					                    $adonsinfo=$this->Api_kitchen_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
					                    $output['foodinfo'][$i]['addonslist'][$x]['aodonsname']=$adonsinfo->add_on_name;
					                    $output['foodinfo'][$i]['addonslist'][$x]['aodonsqty'] =$addonsqty[$x];
					                    $x++;
				                    }
			                }
		                else{
			                $output['foodinfo'][$i]['addons']=0;
			                }
							  $i++;
				            }
						 }
						  return $this->respondWithSuccess('Incomming Order Food List', $output);
						 }
					 else{
						  return $this->respondWithError('No Order Found!!!',$output);
						 }
					
				}
		}
	public function acceptorder(){
			 $this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
			 $this->form_validation->set_rules('order_id', 'Order ID', 'required|xss_clean|trim');
			 $this->form_validation->set_rules('kitchenid', 'kitchenid', 'required|xss_clean|trim');
			 $this->form_validation->set_rules('foodid', 'Food ID', 'required|xss_clean|trim');
			 if ($this->form_validation->run() == FALSE){
					$errors = $this->form_validation->error_array();
					return $this->respondWithValidationError($errors);
				}
				else{
					 $output = $categoryIDs = array();
					 $kitchen=$this->input->post('id', TRUE);
					 $orderid=$this->input->post('order_id', TRUE);
					 $kitchenid=$this->input->post('kitchenid', TRUE);
					 $varientid=$this->input->post('varientid', TRUE);
					 $foodid=$this->input->post('foodid', TRUE);
					 $orderinfo= $this->db->select('*')->from('customer_order')->where('order_id',$orderid)->get()->row();
					 $where2="tbl_kitchen_order.kitchenid in($kitchenid)";
					 $kitcheninfo= $this->Api_kitchen_model->allincommingkitchenview($orderid,$kitchenid,$foodid,$varientid);
					 
					 if($orderinfo->order_status==5){
                        return $this->respondWithError('This Order is Cancel By Admin.Please Try Another!!!',$output);
					 }
					 else if($kitcheninfo==0){
						 return $this->respondWithError('This Order Already Assign.Please Try Another!!!',$output);
					 }
					 else{
					 $kitchenorder['kitchenid']   		    =$kitchenid;
			         $kitchenorder['orderid']   	        =$orderid;
			         $kitchenorder['itemid']   	            =$foodid;
					 $kitchenorder['varient']   	        =$varientid;
			        $this->Api_kitchen_model->insert_data('tbl_kitchen_order',  $kitchenorder);
					$itemaccepted = array(
						'accepttime' => date('Y-m-d H:i:s'),
						'orderid'     => $orderid,
						'menuid'     => $foodid,
						'varient'     =>$varientid
					  );
					 $this->db->insert('tbl_itemaccepted',$itemaccepted);
					  //push 
					$waiteridp=$orderinfo->waiter_id;
					$iteminfo=$this->db->select('item_foods.*,variant.variantName')->from('item_foods')->join('variant','variant.menuid=item_foods.ProductsID','left')->where('variant.menuid',$foodid)->where('variant.variantid',$varientid)->get()->row();
            		$this->db->select('*');
            		$this->db->from('user');
            		$this->db->where('id',$waiteridp);
            		$query = $this->db->get();
            		$allemployee = $query->row();
            		$senderid[]=$allemployee->waiter_kitchenToken;
            		define( 'API_ACCESS_KEY', 'AAAAoAlREhs:APA91bHv8mE03b6bzDQehetC5LYLawmKgTGPH0qdkNcewKuxgOmK0KcAsXguvxMCVaq6IY712y82C90r0Y_cz0hT-4UPOGxTYjBkQEz56QgoP8R1AG4UgAeg6CIZ9P6nPMPK_Egr1IPr');
				$registrationIds = $senderid;
				$msg = array
				(
					'message' 					=> "Orderid: ".$orderid.", Item Name: ".$iteminfo->ProductName." Varient:".$iteminfo->variantName,
					'title'						=> "Food Is Accepted.",
					'subtitle'					=> $orderid,
					'tickerText'				=> "TSET",
					'vibrate'					=> 1,
					'sound'						=> 1,
					'largeIcon'					=> "TSET",
					'smallIcon'					=> "TSET"
				);
				$fields2 = array
				(
					'registration_ids' 	=> $registrationIds,
					'data'			=> $msg
				);
				 
				$headers2 = array
				(
					'Authorization: key=' . API_ACCESS_KEY,
					'Content-Type: application/json'
				);
				 
				$ch2 = curl_init();
				curl_setopt( $ch2,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
				curl_setopt( $ch2,CURLOPT_POST, true );
				curl_setopt( $ch2,CURLOPT_HTTPHEADER, $headers2 );
				curl_setopt( $ch2,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch2,CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch2,CURLOPT_POSTFIELDS, json_encode( $fields2 ) );
				$result2 = curl_exec($ch2 );
				curl_close( $ch2 );
				/*End Notification*/
					 return $this->respondWithSuccess('Order Assign to Kitchen', $output);
					 }
				}
		}
	public function cancelorder(){
	            $this->form_validation->set_rules('order_id', 'Order ID', 'required|xss_clean|trim');
	            $this->form_validation->set_rules('cancelreason', 'Cancel Reason', 'required|xss_clean|trim');
	            if ($this->form_validation->run() == FALSE){
					$errors = $this->form_validation->error_array();
					return $this->respondWithValidationError($errors);
				}
				else{
					 $output = array();
					 $orderid=$this->input->post('order_id', TRUE);
					 $itemId=$this->input->post('itemId', TRUE);
					 $reason=$this->input->post('cancelreason', TRUE);
					 
					 $orderinfo= $this->db->select('*')->from('customer_order')->where('order_id',$orderid)->get()->row();
					
		             $foodname=$this->db->select("ProductName")->from('item_foods')->where('ProductsID',$itemId)->get()->row();
		             
					 $mymsg="You Order is Rejected";
				     $bodymsg="Order ID: ".$orderid." Item Name: ".$foodname->ProductName." Rejeceted with due Reason:".$reason;
				    
        			/*PUSH Notification For Customer*/
                    $customerinfo=$this->db->select("*")->from('customer_info')->where('customer_id',$orderinfo->customer_id)->get()->row();
    				 $icon=base_url('assets/img/applogo.png');
                     $fields3 = array(
            		'to'=>$customerinfo->customer_token,
            		'data'=>array(
            			'title'=>$mymsg,
            			'body'=>$bodymsg,
            			'image'=>$icon,
            			'media_type'=>"image",
            			'message'=>"test",
            			"action"=> "1",
            		),
            		'notification'=>array(
            			'sound'=>"default",
            			'title'=>$mymsg,
            			'body'=>$bodymsg,
            			'image'=>$icon,
            		)
            	);
                	$post_data3 = json_encode($fields3);
                	$url = "https://fcm.googleapis.com/fcm/send";
                	$ch3  = curl_init($url); 
                	curl_setopt($ch3, CURLOPT_FAILONERROR, TRUE); 
                	curl_setopt($ch3, CURLOPT_RETURNTRANSFER, TRUE);
                	curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, 0); 
                	curl_setopt($ch3, CURLOPT_POSTFIELDS, $post_data3);
                	curl_setopt($ch3, CURLOPT_HTTPHEADER, array('Authorization: Key=AAAAmN4ekRg:APA91bHDg_gr99QlnGtHD_exg-QuhRc_45Xluti4dmaNGSD0jfuXi3-3M_wv01TihrHlUAWUDI-dlJqr-_wEHeYigIXSjEbsXJfxI4J9x7ugZDOBv07FhAlWIdDvl8zWcKoeeqqPT9Gw',
                	    'Content-Type: application/json')
                	);
                	$result3 = curl_exec($ch3);
                	curl_close($ch3);
                    $this->db->where('order_id',$orderid)->where('menu_id',$itemId)->delete('order_menu');
                    $afterorderinfo=$this->db->select("*")->from('order_menu')->where('order_id',$orderid)->get()->row();
            		if(empty($afterorderinfo)){
            		    $updatetData = array('anyreason'=>"All item no available",'order_status'=>5,'nofification' => 1,'orderacceptreject'=>0);
            		    $this->db->where('order_id',$orderid);
        		        $this->db->update('customer_order',$updatetData);
            		}
					$socketactive=$this->db->select("socketenable")->from('setting')->where('id',2)->get()->row();
				    if($socketactive->socketenable==1){
				$output=array();
				$output['status']='success';
				$output['status_code']=1;
				$output['message']='Success';
				$output['type']='Token';
				$output['tokenstatus']='Cancel';
	    $kitchenlist=$this->db->select('kitchenid as kitchen_id,kitchen_name,ip,port')->from('tbl_kitchen')->order_by('kitchen_name','Asc')->get()->result();
		$tokenprintinfo=$this->db->select('*')->from('customer_order')->where('order_id',$orderid)->where('tokenprint',0)->order_by('order_id','Asc')->get()->result();
		
		$o=0;
		if(!empty($tokenprintinfo)){
		foreach($tokenprintinfo as $row){
		$customerinfo=$this->Api_kitchen_model->read('*', 'customer_info', array('customer_id' => $row->customer_id));
		if(!empty($row->waiter_id)){
		$waiter=$this->Api_kitchen_model->read('*', 'user', array('id' => $row->waiter_id));
		}else{
			$waiter='';
		}
		
		
		
		$settinginfo=$this->Api_kitchen_model->read('*', 'setting', array('id' => 2));
		$output['orderinfo'][$o]['title']=$settinginfo->title;
	    $output['orderinfo'][$o]['token_no']=$row->tokenno;
		$output['orderinfo'][$o]['ordertime']=date('h:i:s A');
		$output['orderinfo'][$o]['orderdate']=date('d/m/Y', strtotime($row->order_date));
		$output['orderinfo'][$o]['order_id']=$row->order_id;
		$output['orderinfo'][$o]['customerName']=$customerinfo->customer_name;
		$output['orderinfo'][$o]['customerPhone']=$customerinfo->customer_phone;
		$output['orderinfo'][$o]['ordernotes']=$row->customer_note;
		if(!empty($waiter)){
		$output['orderinfo'][$o]['waiter']=$waiter->firstname.' '.$waiter->lastname;
		}else{
		$output['orderinfo'][$o]['waiter']='';
		}
		if(!empty($row->table_no)){
		$tableinfo=$this->Api_kitchen_model->read('*', 'rest_table', array('tableid' => $row->table_no));
		$output['orderinfo'][$o]['tableno']=$tableinfo->tableid;
		$output['orderinfo'][$o]['tableName']=$tableinfo->tablename;
		}
		else{
		$output['orderinfo'][$o]['tableno']='';
		$output['orderinfo'][$o]['tableName']=''; 
		}
		$k=0;
		foreach($kitchenlist as $kitchen){
		    $isupdate=$this->Api_kitchen_model->read('*', 'tbl_apptokenupdate', array('ordid' => $row->order_id));
		    
		
			$output['orderinfo'][$o]['kitcheninfo'][$k]['kitchenName']=$kitchen->kitchen_name;
			$output['orderinfo'][$o]['kitcheninfo'][$k]['ip']=$kitchen->ip;
			$output['orderinfo'][$o]['kitcheninfo'][$k]['port']=$kitchen->port;
			
			$i=0;
			if(!empty($isupdate)){
			    $iteminfo=$this->Api_v1_model->customerupdateorderkitchen($row->order_id,$kitchen->kitchen_id);
			    if(empty($iteminfo)){
    			$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist']=0;
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo']=array();
    			}else{
    			  $output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist']=1;
				  $output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo']=array();
    			} 
				$i=0;
    			foreach($iteminfo as $item){
					$getqty=$this->Api_v1_model->read('SUM(tbl_apptokenupdate.add_qty) as cqty,SUM(tbl_apptokenupdate.del_qty) as pqty,tbl_apptokenupdate.isdel', 'tbl_apptokenupdate', array('ordid' => $item->ordid,'menuid' => $item->ProductsID,'varientid' => $item->variantid,'addonsuid' => $item->addonsuid,'isprint' => 0));
					
					$itemfoodnotes=$this->Api_v1_model->read('notes', 'order_menu', array('order_id' => $item->ordid,'menu_id' => $item->ProductsID,'varientid' => $item->variantid));
					
				    if($getqty->cqty>$getqty->pqty){
						$itemnotes=$itemfoodnotes->notes;
					if($itemfoodnotes->notes=="deleted"){
						$itemnotes="";
					}
					$qty=$getqty->cqty-$getqty->pqty;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id']=$item->printer_token_id;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName']=$item->ProductName;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID']=$item->ProductsID;
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid']=$item->variantid;
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName']=$item->variantName;
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty']=quantityshow($qty,$item->is_customqty);
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes']=$itemnotes.$getqty->isdel;
				
				if(!empty($item->addonsid)){
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons']=1;
					$addons=explode(",",$item->addonsid);
					$addonsqty=explode(",",$item->adonsqty);
					$itemsnameadons='';
					$p=0;
						foreach($addons as $addonsid){
							$adonsinfo=$this->Api_v1_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName']=$adonsinfo->add_on_name;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty']=quantityshow($addonsqty[$p],$item->is_customqty);
							$p++;
						}
					}
				else{
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons']=0;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName']="";
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty']="";
					}
					$i++;
					}
					if($getqty->pqty>$getqty->cqty){
					$qty=$getqty->pqty-$getqty->cqty;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id']=$item->printer_token_id;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName']=$item->ProductName;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID']=$item->ProductsID;
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid']=$item->variantid;
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName']=$item->variantName;
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty']="(-)".quantityshow($qty,$item->is_customqty);
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes']=$itemfoodnotes->notes.$getqty->isdel;
				
				
				if(!empty($item->addonsid)){
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons']=1;
					$addons=explode(",",$item->addonsid);
					$addonsqty=explode(",",$item->adonsqty);
					$itemsnameadons='';
					$p=0;
						foreach($addons as $addonsid){
							$adonsinfo=$this->Api_v1_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName']=$adonsinfo->add_on_name;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty']=quantityshow($addonsqty[$p],$item->is_customqty);
							$p++;
						}
					}
				else{
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons']=0;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName']="";
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty']="";
					}
					$i++;
					}
				//print_r($output);	
				
				
			}
			}else{
			    $iteminfo=$this->Api_v1_model->apptokenupdateorderkitchen($row->order_id,$kitchen->kitchen_id);
			    if(empty($iteminfo)){
    			$output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist']=0;
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo']=array();
    			}else{
    			  $output['orderinfo'][$o]['kitcheninfo'][$k]['isitemexist']=1;
				  $output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo']=array();
    			}
				$i=0;
			    foreach($iteminfo as $item){
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['printer_token_id']=$item->printer_token_id;
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemName']=$item->ProductName;
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['itemID']=$item->ProductsID;
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantid']=$item->variantid;
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['variantName']=$item->variantName;
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['qty']=quantityshow($item->menuqty,$item->is_customqty);
				$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['notes']=$item->notes;
				
				if(!empty($item->add_on_id)){
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons']=1;
					$addons=explode(",",$item->add_on_id);
					$addonsqty=explode(",",$item->addonsqty);
					$itemsnameadons='';
					$p=0;
						foreach($addons as $addonsid){
							$adonsinfo=$this->Api_v1_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsName']=$adonsinfo->add_on_name;
							$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][$p]['add_onsqty']=quantityshow($addonsqty[$p],$item->is_customqty);
							$p++;
						}
					}
				else{
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['isaddons']=0;
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsName']="";
					$output['orderinfo'][$o]['kitcheninfo'][$k]['iteminfo'][$i]['addonsinfo'][0]['add_onsqty']="";
					}
				$i++;
			}
			}
			$k++;
		 }
		 $o++;
		}
	}else{
	    $new['orderinfo']=array();
	    $output=array('status' => 'success','type' => 'Token','tokenstatus' => 'Cancel','status_code' => 0,'message' => 'Success','data' => $new);
	    
	}
		$newdata=json_encode($output,JSON_UNESCAPED_UNICODE);
		send($newdata);	
				}
            		return $this->respondWithSuccess('Order Rejected', $output);
				}
	}
	public function completeorder(){
			$this->form_validation->set_rules('id', 'id', 'required|xss_clean|trim');
			$this->form_validation->set_rules('start', 'start', 'required|xss_clean|trim');
			$this->form_validation->set_rules('kitchenid', 'kitchenid', 'required|xss_clean|trim');
			 if ($this->form_validation->run() == FALSE){
					$errors = $this->form_validation->error_array();
					return $this->respondWithValidationError($errors);
				}
				else{
					 $waiterid=$this->input->post('id', TRUE);
					 $start=$this->input->post('start', TRUE);
					 $kitchenid=$this->input->post('kitchenid', TRUE);
					 if($start==0){
					 $orderlist = $this->Api_kitchen_model->allorderlist2($waiterid,$status=4,$kitchenid,$limit=20);
					 }
					 else{
						$orderlist = $this->Api_kitchen_model->allorderlist2($waiterid,$status=4,$start,$kitchenid,$limit=20); 
						 }
					 $totalorder = $this->Api_kitchen_model->count_comorder2($waiterid,$status=4,$kitchenid);
					 $output = $categoryIDs = array();
					if ($orderlist != FALSE) {
						 $output['totalorder']        = $totalorder;
						$i=0;
						 foreach($orderlist as $order){
							 $iteminfo=$this->db->select('order_menu.*,item_foods.*,variant.variantid,variant.variantName,variant.price')->from('order_menu')->join('customer_order','order_menu.order_id=customer_order.order_id','left')->join('item_foods','order_menu.menu_id=item_foods.ProductsID','left')->join('variant','order_menu.varientid=variant.variantid','left')->where('order_menu.order_id',$order->order_id)->get()->result();
							 $output['orderinfo'][$i]['order_id']        = $order->order_id;
							 $output['orderinfo'][$i]['CustomerName']    = $order->customer_name;
							 $output['orderinfo'][$i]['TableName']       = $order->tablename;
							 $output['orderinfo'][$i]['OrderDate']       = $order->order_date;
							 $output['orderinfo'][$i]['TotalAmount']     = $order->totalamount;
							  if(!empty($iteminfo)){
								 $output['orderinfo'][$i]['itemexist']=1;
									$k=0;
									foreach($iteminfo as $item){
											 $output['orderinfo'][$i]['iteminfo'][$k]['ProductsID']     =$item->ProductsID;
											 $output['orderinfo'][$i]['iteminfo'][$k]['ProductName']     =$item->ProductName;
											 $output['orderinfo'][$i]['iteminfo'][$k]['price']    		=$item->price;
											 $output['orderinfo'][$i]['iteminfo'][$k]['component']       =$item->component;
											 $output['orderinfo'][$i]['iteminfo'][$k]['destcription']    =$item->descrip;
											 $output['orderinfo'][$i]['iteminfo'][$k]['itemnotes']       =$item->itemnotes;
											 $output['orderinfo'][$i]['iteminfo'][$k]['productvat']      =$item->productvat;
											 $output['orderinfo'][$i]['iteminfo'][$k]['offerIsavailable']=$item->offerIsavailable;
											 $output['orderinfo'][$i]['iteminfo'][$k]['offerstartdate']  =$item->offerstartdate;
											 $output['orderinfo'][$i]['iteminfo'][$k]['OffersRate']      =$item->OffersRate;
											 $output['orderinfo'][$i]['iteminfo'][$k]['offerendate']     =$item->offerendate;
											 $output['orderinfo'][$i]['iteminfo'][$k]['ProductImage']    =base_url().$item->ProductImage;
											 $output['orderinfo'][$i]['iteminfo'][$k]['Varientname']     =$item->variantName;
											 $output['orderinfo'][$i]['iteminfo'][$k]['Varientid']       =$item->variantid;
											 $output['orderinfo'][$i]['iteminfo'][$k]['Itemqty']         =quantityshow($item->menuqty,$item->is_customqty);
											 if(!empty($item->add_on_id)){
											$output['orderinfo'][$i]['iteminfo'][$k]['addons']         =1;
												 $addons=explode(",",$item->add_on_id);
												 $addonsqty=explode(",",$item->addonsqty);
												 $x=0;
												foreach($addons as $addonsid){
														$adonsinfo=$this->Api_kitchen_model->read('*', 'add_ons', array('add_on_id' => $addonsid));
														$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['add_on_name']     =$adonsinfo->add_on_name;
														$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsid']      =$adonsinfo->add_on_id;
														$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsprice']          =$adonsinfo->price;
														$output['orderinfo'][$i]['iteminfo'][$k]['addonsinfo'][$x]['addonsquantity']     =$addonsqty[$x];
														$x++;
													}
											}
											else{
												$output['orderinfo'][$i]['iteminfo'][$k]['addons']         =0;
												}
											$k++;
										}
								}else{
							 $output['orderinfo'][$i]['itemexist']=0;	
							 }
							 $i++;
						 }
					
						return $this->respondWithSuccess('Complete Order List.', $output);
					} else {
						return $this->respondWithError('Order Not Found.!!!',$output);
					}
			}

		}	
		
		
}