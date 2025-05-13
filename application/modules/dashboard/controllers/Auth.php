<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller {
 	
 	public function __construct()
 	{
 		parent::__construct();

 		$this->load->model(array(
 			'auth_model',
			'setting/setting_model'
 		));
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->helper('captcha');
 	}

	//  public function index()
	//  {  
	//  //echo "Bla Bla";
	 
	// 	 if($this->session->userdata('isLogIn'))
	// 	 redirect('dashboard/home');
	// 	 $data['title']    = display('login'); 
	// 	 #-------------------------------------#
	 
	// 	 $this->form_validation->set_rules('email', display('email'), 'required|valid_email|max_length[100]|trim');
	// 	 $this->form_validation->set_rules('password', display('password'), 'required|max_length[32]|md5|trim');
	// 	 /*$this->form_validation->set_rules('captcha', display('captcha'),  array('matches[captcha]', function($captcha){ 
	// 				 $oldCaptcha = $this->session->userdata('captcha');
	// 				 if ($captcha == $oldCaptcha) {
	// 					 return true;
	// 				 }
	// 			 }
	// 		 )
	// 	 );*/
		 
	// 	 #-------------------------------------#
	// 	 $data['user'] = (object)$userData = array(
	// 		 'email' 	 => $this->input->post('email',true),
	// 		 'password'   => $this->input->post('password',true),
	// 	 );
	// 	 #-------------------------------------#
	// 	 if ( $this->form_validation->run())
	// 	 {
	// 		 $this->session->unset_userdata('captcha');
 
	// 		 $user = $this->auth_model->checkUser($userData);
 
	// 		 if($user->num_rows() > 0) {
 
	// 		 $user_info = $user->row();
	// 		 if(!$user_info->status){
	// 			 $this->session->set_flashdata('exception', display('your_account_is_inactive_please_contact_support'));
	// 			 redirect('login');
	// 		 }
 
	// 		 $chef = $this->db->select('emp_his_id,employee_id,pos_id')->where('emp_his_id',$user->row()->id)->get('employee_history')->row();
	// 		 $chefid='';
	// 		 if(!empty($chef)) {
	// 				 $shiftcheck = true;
	// 			 $shiftmangment = $this->db->where('directory','shiftmangment')->where('status',1)->get('module')->num_rows();
				 
	// 			 if($shiftmangment == 1){
	// 			 $shiftcheck = $this->checkshift($chef->employee_id);
	// 				 }
 
					 
	// 			 if($shiftcheck == true){
	// 				 if($chef->pos_id == 1){
	// 				 $chefid=$chef->emp_his_id;
	// 				 }
					 
	// 			 }
	// 			 else{
					 
	// 				 $this->session->set_flashdata('exception', display('not_your_working_time'));
	// 			 redirect('login');
					 
	// 			 }
				 
			 
	// 		 }
			 
	// 		 $checkPermission = $this->auth_model->userPermission2($user->row()->id);
	// 		 if($checkPermission!=NULL){
	// 			 $permission = array();
	// 			 $permission1 = array();
	// 			 if(!empty($checkPermission)){
	// 				 foreach ($checkPermission as $value) {
	// 					 $permission[$value->module] = array( 
	// 						 'create' => $value->create,
	// 						 'read'   => $value->read,
	// 						 'update' => $value->update,
	// 						 'delete' => $value->delete
	// 					 );
 
	// 					 $permission1[$value->menu_title] = array( 
	// 						 'create' => $value->create,
	// 						 'read'   => $value->read,
	// 						 'update' => $value->update,
	// 						 'delete' => $value->delete
	// 					 );
					 
	// 				 }
	// 			 } 
	// 		 }
 
	// 		 if($user->row()->is_admin == 2){
	// 			 $row = $this->db->select('client_id,client_email')->where('client_email',$user->row()->email)->get('setup_client_tbl')->row();
	// 		 }
 
	// 				  $sData = array(
	// 				 'isLogIn' 	  => true,
	// 				 'isAdmin' 	  => (($user->row()->is_admin == 1)?true:false),
	// 				 'user_type'   => $user->row()->is_admin,
	// 				 'id' 		  => $user->row()->id,
	// 				 'client_id'   => @$row->client_id,
	// 				 'fullname'	  => $user->row()->fullname,
	// 				 'user_level'  => $user->row()->user_level,
	// 				 'email' 	  => $user->row()->email,
	// 				 'image' 	  => $user->row()->image,
	// 				 'last_login'  => $user->row()->last_login,
	// 				 'last_logout' => $user->row()->last_logout,
	// 				 'ip_address'  => $user->row()->ip_address,
	// 				 'permission'  => json_encode(@$permission), 
	// 				 'label_permission'  => json_encode(@$permission1) 
	// 				 );	
	// 				 //store date to session 
	// 				 $this->session->set_userdata($sData);
	// 				 //update database status
	// 				 $this->auth_model->last_login();
	// 				 //welcome message

	// 				 ////// Use HOOK related database conneciton

	// 				 $session_data = array(
	// 					'user_for_hook' => array(
	// 						'hostname' => 'localhost',
	// 						'database' => 'restorapos_main',
	// 						// 'database' => 'restorapos_main_for_client',
	// 						'db_username' => 'misor',
	// 						'password' => '123456'
	// 					),
	// 				);
	// 				$this->session->set_userdata($session_data);

	// 				// dd($this->session->userdata('user_for_hook'));

	// 				 ////// END

	// 				 $this->session->set_flashdata('message', display('welcome_back').' '.$user->row()->fullname);
	// 				 if(!empty($chefid)){
	// 				 redirect('ordermanage/order/allkitchen');
	// 				 }
	// 				 else if($user->row()->counter==1){
	// 					 redirect('ordermanage/order/counterboard');
	// 					 }
	// 				 else{
	// 				 redirect('dashboard/home');
	// 				 }
 
	// 		 } else {
	// 			 $this->session->set_flashdata('exception', display('incorrect_email_or_password'));
	// 			 redirect('login');
	// 		 } 
 
	// 	 } else {
	// 		 //echo FCPATH .'captcha/font/captcha4.ttf';
	// 		 /*$captcha = create_captcha(array(
	// 			 'img_path'      =>  './captcha/',
	// 			 'img_url'       =>  base_url().'captcha/',
	// 			 'font_path'     =>  FCPATH . 'captcha/font/captcha4.ttf',
	// 			 'img_width'     => '290',
	// 			 'img_height'    => 65,
	// 			 'expiration'    => 7200, //5 min
	// 			 'word_length'   => 4,
	// 			 'font_size'     => 35,
	// 			 'img_id'        => 'Imageid',
	// 			 'pool'          => '23456789abcdefghijkmnpqrstuvwxyz',
 
	// 			 // White background and border, black text and red grid
	// 			 'colors'        => array(
	// 					 'background' => array(255, 255, 255),
	// 					 'border' => array(228, 229, 231),
	// 					 'text' => array(49, 141, 1),
	// 					 'grid' => array(241, 243, 246)
	// 			 )
	// 		 ));*/
	// 		 $vals = array(
	// 					 //'word'          => 'Random word',
	// 					 'img_path'      => './captcha/',
	// 					 'img_url'       => base_url().'captcha/',
	// 					 'font_path'     => FCPATH . 'captcha/font/captcha4.ttf',
	// 					 'img_width'     => '290',
	// 					 'img_height'    => 65,
	// 					 'expiration'    => 7200,
	// 					 'word_length'   => 4,
	// 					 'font_size'     => 28,
	// 					 'img_id'        => 'Imageid',
	// 					 'pool'          => '23456789ABCDEFGHJKMNPQRSTUVWXYZ',//abcdefghijklmnopqrstuvwxyz
 
	// 					 // White background and border, black text and red grid
	// 					 'colors'        => array(
	// 							 'background' => array(255, 255, 255),
	// 							 'border' => array(228, 229, 231),
	// 							 'text' => array(49, 141, 1),
	// 							 'grid' => array(241, 243, 246)
	// 					 )
	// 			 );
 
	// 		 $captcha = create_captcha($vals);
	// 		 $data['captcha_word'] = isset($captcha['word'])? $captcha['word']:'';
	// 		 $data['captcha_image'] = isset($captcha['image'])? $captcha['image']:'';
	// 		 $this->session->set_userdata('captcha', isset($captcha['word'])? $captcha['word']:'');
 
	// 		 echo Modules::run('template/login', $data);
	// 	 }
	//  }
 

	public function index()
	{  

		//echo "Bla Bla";
	

		$auto_posting_setting = $this->db->select('login_auto_posting')->from('setting')->get()->row_array();

		if($auto_posting_setting['login_auto_posting'] == 1){

			$is_sub_branch = $this->session->userdata('is_sub_branch');
			if($is_sub_branch == 0){
				$this->db->query("CALL ProcessBulkVoucherPosting(@output_message)");
				$process_query = $this->db->query("SELECT @output_message AS output_message");
				$process_result = $process_query->row();
			}
		}



		if($this->session->userdata('isLogIn'))
		redirect('dashboard/home');
		$data['title']    = display('login'); 
		#-------------------------------------#
	
		$this->form_validation->set_rules('email', display('email'), 'required|valid_email|max_length[100]|trim');
		$this->form_validation->set_rules('password', display('password'), 'required|max_length[32]|md5|trim');
		/*$this->form_validation->set_rules('captcha', display('captcha'),  array('matches[captcha]', function($captcha){ 
		        	$oldCaptcha = $this->session->userdata('captcha');
		        	if ($captcha == $oldCaptcha) {
		        		return true;
		        	}
		        }
		    )
		);*/
		
		#-------------------------------------#
		$data['user'] = (object)$userData = array(
			'email' 	 => $this->input->post('email',true),
			'password'   => $this->input->post('password',true),
		);
		#-------------------------------------#
		if ( $this->form_validation->run())
		{
			$this->session->unset_userdata('captcha');

			$user = $this->auth_model->checkUser($userData);

			if($user->num_rows() > 0) {

			$user_info = $user->row();
			if(!$user_info->status){
				$this->session->set_flashdata('exception', display('your_account_is_inactive_please_contact_support'));
				redirect('login');
			}

            $chef = $this->db->select('emp_his_id,employee_id,pos_id')->where('emp_his_id',$user->row()->id)->get('employee_history')->row();
			$chefid='';
			if(!empty($chef)) {
					$shiftcheck = true;
				$shiftmangment = $this->db->where('directory','shiftmangment')->where('status',1)->get('module')->num_rows();
				
				if($shiftmangment == 1){
				$shiftcheck = $this->checkshift($chef->employee_id);
					}

					
				if($shiftcheck == true){
					if($chef->pos_id == 1){
					$chefid=$chef->emp_his_id;
					}
					
				}
				else{
					
					$this->session->set_flashdata('exception', display('not_your_working_time'));
				redirect('login');
					
				}
				
			
			}
			
			$checkPermission = $this->auth_model->userPermission2($user->row()->id);
			if($checkPermission!=NULL){
				$permission = array();
				$permission1 = array();
				if(!empty($checkPermission)){
					foreach ($checkPermission as $value) {
						$permission[$value->module] = array( 
							'create' => $value->create,
							'read'   => $value->read,
							'update' => $value->update,
							'delete' => $value->delete
						);

						$permission1[$value->menu_title] = array( 
							'create' => $value->create,
							'read'   => $value->read,
							'update' => $value->update,
							'delete' => $value->delete
						);
					
					}
				} 
			}

			if($user->row()->is_admin == 2){
				$row = $this->db->select('client_id,client_email')->where('client_email',$user->row()->email)->get('setup_client_tbl')->row();
			}

				     $sData = array(
					'isLogIn' 	  => true,
					'isAdmin' 	  => (($user->row()->is_admin == 1)?true:false),
					'user_type'   => $user->row()->is_admin,
					'id' 		  => $user->row()->id,
					'client_id'   => @$row->client_id,
					'fullname'	  => $user->row()->fullname,
					'user_level'  => $user->row()->user_level,
					'email' 	  => $user->row()->email,
					'image' 	  => $user->row()->image,
					'last_login'  => $user->row()->last_login,
					'last_logout' => $user->row()->last_logout,
					'ip_address'  => $user->row()->ip_address,
					'permission'  => json_encode(@$permission), 
					'label_permission'  => json_encode(@$permission1) 
					);	
					// dd($sData);
					//store date to session 
					$this->session->set_userdata($sData);
					//update database status
					$this->auth_model->last_login();
					// SET if the applicasiton is Single Server or Sub Branch***
					$this->setIsSubBranch();
					//welcome message
					$this->session->set_flashdata('message', display('welcome_back').' '.$user->row()->fullname);
					if(!empty($chefid)){
					redirect('ordermanage/order/allkitchen');
					}
					else if($user->row()->counter==1){
						redirect('ordermanage/order/counterboard');
						}
					else{
					redirect('dashboard/home');
					}

			} else {
				$this->session->set_flashdata('exception', display('incorrect_email_or_password'));
				redirect('login');
			} 

		} else {
			//echo FCPATH .'captcha/font/captcha4.ttf';
			/*$captcha = create_captcha(array(
			    'img_path'      =>  './captcha/',
			    'img_url'       =>  base_url().'captcha/',
			    'font_path'     =>  FCPATH . 'captcha/font/captcha4.ttf',
			    'img_width'     => '290',
			    'img_height'    => 65,
			    'expiration'    => 7200, //5 min
			    'word_length'   => 4,
			    'font_size'     => 35,
			    'img_id'        => 'Imageid',
			    'pool'          => '23456789abcdefghijkmnpqrstuvwxyz',

			    // White background and border, black text and red grid
			    'colors'        => array(
			            'background' => array(255, 255, 255),
			            'border' => array(228, 229, 231),
			            'text' => array(49, 141, 1),
			            'grid' => array(241, 243, 246)
			    )
			));*/
			$vals = array(
                        //'word'          => 'Random word',
                        'img_path'      => './captcha/',
                        'img_url'       => base_url().'captcha/',
                        'font_path'     => FCPATH . 'captcha/font/captcha4.ttf',
                        'img_width'     => '290',
                        'img_height'    => 65,
                        'expiration'    => 7200,
                        'word_length'   => 4,
                        'font_size'     => 28,
                        'img_id'        => 'Imageid',
                        'pool'          => '23456789ABCDEFGHJKMNPQRSTUVWXYZ',//abcdefghijklmnopqrstuvwxyz

                        // White background and border, black text and red grid
                        'colors'        => array(
                                'background' => array(255, 255, 255),
                                'border' => array(228, 229, 231),
                                'text' => array(49, 141, 1),
                                'grid' => array(241, 243, 246)
                        )
                );

            $captcha = create_captcha($vals);
			$data['captcha_word'] = isset($captcha['word'])? $captcha['word']:'';
			$data['captcha_image'] = isset($captcha['image'])? $captcha['image']:'';
			$this->session->set_userdata('captcha', isset($captcha['word'])? $captcha['word']:'');

			echo Modules::run('template/login', $data);
		}
	}
   public function forgotpass()
	{ 
	   $data['title'] = display('forgot_password');	   
	    echo Modules::run('template/forgotpass', $data);
	}

	public function passwordreset()
	{ 
	    $data['title'] = display('forgot_password');
		$this->form_validation->set_rules('email', display('email'), 'required|valid_email|max_length[100]|trim');
		if($this->form_validation->run()){
			$setting=$this->db->select('*')->from('setting')->get()->row();
			$email = $this->input->post('email',true);
			$check_email = $this->db->select('email')->from('user')->where('email',$email)->get()->row();
			$random_key = ("RK" . date('y') . strtoupper($this->randstrGen(2, 4)));
			
			if($check_email){

				//Otp send through Email
				$subject="Password Reset";
				$send_email = $this->db->select('*')->from('email_config')->where('email_config_id',1)->get()->row();
				$code = $this->db->select('password_reset_token')->from('user')->where('email',$email)->get()->row();
				$htmlContent="Your New Password is ".$random_key.".\n Please Login Now With This Password.";
				$config = array(
					'protocol'  => $send_email->protocol,
					'smtp_host' => $send_email->smtp_host,
					'smtp_port' => $send_email->smtp_port,
					'smtp_user' => $send_email->sender,
					'smtp_pass' => $send_email->smtp_password,
					'mailtype'  => "html",
					'smtp_crypto'=>"tls",
					'charset'   => 'utf-8'
				);
			
				$this->load->library('email');
				$this->email->initialize($config);
				$this->email->set_newline("\r\n");
				$this->email->set_mailtype("html");
				$this->email->from($send_email->sender, $setting->title);
				$this->email->to($email);
				$this->email->subject($subject);
				$this->email->message($htmlContent);
				$this->email->send();

				if (!$this->email->send()) {

					$this->session->set_flashdata('exception', 'Email Is Not Sent, Please Try Again.');
					redirect('forgotpassword');

					// echo show_error($this->email->print_debugger()); exit; 

				}else {

					$data_up = array(
						'password'  => md5($random_key)
					);
					$res_update = $this->db->where('email', $email)->update("user", $data_up);

					if($res_update){

						$this->session->set_flashdata('message',"Password Reset Successfully, Please Check Your Email Inbox/Spam");
						redirect('login');
					}else{
						$this->session->set_flashdata('exception', 'Something Wrong , Please Try Again.');
						redirect('forgotpassword');
					}
				} 

			}else{

				$this->session->set_flashdata('exception', 'This Email Is Not Found!!!');
			
			}
			redirect('forgotpassword');
		}else{
			echo Modules::run('template/forgotpass', $data);
		}	   
	    
	}
	public function passwordreset_old()
	{ 
	    $data['title'] = display('forgot_password');
		$this->form_validation->set_rules('email', display('email'), 'required|valid_email|max_length[100]|trim');
		if($this->form_validation->run()){
			$setting=$this->db->select('*')->from('setting')->get()->row();
			$email = $this->input->post('email',true);
			$checkemail = $this->db->select('email')->from('user')->where('email',$email)->get()->row();
			$random_key = ("RK" . date('y') . strtoupper($this->randstrGen(2, 4)));
			if($checkemail){		
			$data = array(
				'password'  => md5($random_key)
				);
			$this->db->where('email', $email)->update("user", $data);
			//Otp send through Email
			$subject="Password Reset";
			$send_email = $this->db->select('*')->from('email_config')->where('email_config_id',1)->get()->row();
			$code = $this->db->select('password_reset_token')->from('user')->where('email',$email)->get()->row();
			$htmlContent="Your New Password is ".$random_key.".\n Please Login Now With This Password.";
             $config = array(
			'protocol'  => $send_email->protocol,
			'smtp_host' => $send_email->smtp_host,
			'smtp_port' => $send_email->smtp_port,
			'smtp_user' => $send_email->sender,
			'smtp_pass' => $send_email->smtp_password,
			'mailtype'  => "html",
			'smtp_crypto'=>"tls",
			'charset'   => 'utf-8'
		    );
   
		
			$this->load->library('email');
			$this->email->initialize($config);
			$this->email->set_newline("\r\n");
			$this->email->set_mailtype("html");
			$this->email->from($send_email->sender, $setting->title);
			$this->email->to($email);
			$this->email->subject($subject);
			$this->email->message($htmlContent);
			$this->email->send();
			/*if (!$this->email->send()) {
    			echo show_error($this->email->print_debugger()); }
  			else {
    				echo  'Your e-mail has been sent!';
  			  } */
			$this->session->set_flashdata('message',"Password Reset Successfully.Please Check Your Email Inbox/Spam");
		    redirect('login');
			}else{
			$this->session->set_flashdata('exception', 'This Email Not Fount!!!');
			redirect('forgotpassword');
			}
		}else{
			echo Modules::run('template/forgotpass', $data);
		}	   
	    
	}
	function randstrGen($mode = null, $len = null) {
        $result = "";
        if ($mode == 1):
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        elseif ($mode == 2):
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        elseif ($mode == 3):
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        elseif ($mode == 4):
            $chars = "0123456789";
        endif;
        $charArray = str_split($chars);
        for ($i = 0; $i < $len; $i++) {
            $randItem = array_rand($charArray);
            $result .= "" . $charArray[$randItem];
        }
        return $result;
    }

	// public function logout()
	// { 
	// 	// Destroy the session or unset the stored DB connection
	// 	$this->session->unset_userdata('user_db_config');
	// 	$this->session->unset_userdata('user_for_hook');

	// 	Db_switcher::db_connectio_reset(); // Reset the connection

	// 	//update database status
	// 	$this->auth_model->last_logout();
	// 	//destroy session
	// 	$this->session->sess_destroy();

	// 	// $this->db->close(); // Close the database connection

	// 	redirect('login');
	// }

	public function logout()
	{ 
		//update database status
		$this->auth_model->last_logout();
		//destroy session
		$this->session->sess_destroy();
		redirect('login');
	}

	public function checkshift($id){
		 $this->db->select('shift.*');
         $this->db->from('shift_user as shiftuser');
         $this->db->join('shifts as shift','shiftuser.shift_id=shift.id','left');
         $this->db->where('shiftuser.emp_id',$id);
         $shift=$this->db->get()->row();
         $timezone = $this->db->select('timezone')->get('setting')->row();
         $tz_obj = new DateTimeZone($timezone->timezone);
		 $today = new DateTime("now", $tz_obj);
		 $today_formatted = $today->format('H:i:s');
		 
		if ( $today_formatted>=$shift->start_Time && $today_formatted <= $shift->end_Time ) 
		{
		
			return true;
		}
		else{
			
			return false;
		}

        
	}

	public function setIsSubBranch(){

		if($this->session->userdata('isLogIn')){

			// Seeting in session if sub branch or single server based POS/application
			$this->session->set_userdata('is_sub_branch', 0); // Single Server Based Applicaiton
			$setting =  $this->setting_model->read();
			$mainbranchinfo = $this->db->select('*')->from('tbl_mainbranchinfo')->get()->row();
			if($mainbranchinfo && $setting->app_type){
				$this->session->set_userdata('is_sub_branch', 1); // Sub Branch Applicaiton
			}
			// End

		}

	}

}
