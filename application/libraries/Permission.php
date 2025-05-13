<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Permission
{    
	protected $permission;
	protected $module; 
	protected $label; 
	protected $redirect = "login";
	protected $ci;

	public function __construct()
	{
		$this->module = '';
		$this->label = '';
		$this->ci =& get_instance();
	}

 
	public function access()
	{ 
		return $this->permission;
	}

	public function redirect()
	{  
		if ($this->permission) { 
			return $this->permission;
		} else {
			$this->ci->session->set_flashdata('exception', "You do not have permission to access. Please contact with administrator.");
			redirect($this->redirect);
		}
	}


	public function module($module = null)
	{
		$module = (($module!=null)?strtolower($module):$this->ci->uri->segment(1));
		$this->module = $module;
		if ($this->checkModule($module)) {
			$this->permission = true;
		} else {
			$this->permission = false;
		} 
		return $this; 
	}



	public function check_label($label = null)
	{
		$label = (($label!=null)?strtolower($label):$this->ci->uri->segment(1));
		$this->label = $label;
		if ($this->check_label_to_Permission($label)) {
			$this->permission = true;
		} else {
			$this->permission = false;
		} 
		return $this; 
	}


	public function method($module = null, $method = null)
	{

		$module = (($module!=null)?strtolower($module):$this->ci->uri->segment(1));
		$method = strtolower($method);

		if ($this->checkMethod($module, $method)) {
			$this->permission = true;
		} else {
			$this->permission = false;
		} 
		return $this;
	}	


	public function create()
	{   
		if ($this->checkLebel_permission_type($this->label, 'create')) {
			$this->permission = true;
		} else {
			$this->permission = false;
		} 
		return $this;
	}
 

	public function read()
	{   
		if ($this->checkLebel_permission_type($this->label, 'read')) {
			$this->permission = true;
		} else {
			$this->permission = false;
		} 
		return $this;
	}

	public function update()
	{   
		if ($this->checkLebel_permission_type($this->label, 'update')) {
			$this->permission = true;
		} else {
			$this->permission = false;
		} 
		return $this;
	}
 
	public function delete()
	{   
		if ($this->checkLebel_permission_type($this->label, 'delete')) {
			$this->permission = true;
		} else {
			$this->permission = false;
		} 
		return $this;
	}
	 
	protected function checkModule($module = NULL)
	{ 
		$permission = $this->ci->session->userdata('permission');
		$isAdmin    = $this->ci->session->userdata('isAdmin');
		$isLogIn    = $this->ci->session->userdata('isLogIn');

		if ($isLogIn && $isAdmin) { 
			return true;
		} else if($isLogIn) { 

			if ($permission!=NULL) {

				$permission = json_decode($permission, true);
				//module list
				@$modules = array_keys(@$permission);

				if($modules!=NULL){
					//check current module permission
					if (in_array(@$module, @$modules) ) {
						return true;  
					} else {
						return false;
					} 
				}else{
						return false;
					}
			} else {
				return false;
			} 
		} else {
			return false;
		} 
	}


	protected function check_label_to_Permission($label = null)
	{ 
		
		
		$permission = $this->ci->session->userdata('label_permission');
		$isAdmin    = $this->ci->session->userdata('isAdmin');
		$isLogIn    = $this->ci->session->userdata('isLogIn');

		if ($isLogIn && $isAdmin) { 
			return true;
		} else if($isLogIn) {
			if (($permission!=null)) {
				$permission = json_decode($permission, true);
				
				$labels = array_keys(@$permission);
				
				//check current module permission
				if (in_array($label, $labels) ) {
					
					return true;  
				} else {
					return false;
				} 
			} else {
				return false;
			} 
		} else {
			return false;
		} 
	}


	protected function checkMethod($module = null, $method = null)
	{ 
		$permission = $this->ci->session->userdata('permission');
		$isAdmin    = $this->ci->session->userdata('isAdmin');
		$isLogIn    = $this->ci->session->userdata('isLogIn');

		if ($isLogIn && $isAdmin) {
			//action of administrator
			return true;
		} else if($isLogIn) {

			if (($permission!=null)) {
				$permission = json_decode($permission, true);

				//module list
				$modules = array_keys($permission);

				//check current module permission
				if ( in_array($module, $modules) ) {

					//convert method to asoc
					$methodList = $permission[$module]; 

					$methods = array_keys($permission[$module]);

					//check for each input
					if (in_array(strtolower($method), $methods)) {
						if ($methodList[$method] == 1) {
							return true;
						} else {
							return false;
						}	

					} else {
						return false;
					} 

				} else {
					return false;
				} 
			} else {
				return false;
			}

		} else {
			return false;
		} 
	}


	protected function checkLebel_permission_type($label = null, $method = null)
	{ 
		$permission = $this->ci->session->userdata('label_permission');
		$isAdmin    = $this->ci->session->userdata('isAdmin');
		$isLogIn    = $this->ci->session->userdata('isLogIn');

		if ($isLogIn && $isAdmin) {
			//action of administrator
			return true;
		} else if($isLogIn) {

			if (($permission!=null)) {

			$permission = json_decode($permission, true);
				//module list
				$labels = array_keys($permission);

				//check current module permission
				if (in_array($label, $labels) ) {
					//convert method to asoc
					$labelList = $permission[$label]; 

					$methods = array_keys($permission[$label]);

					//check for each input
					if (in_array(strtolower($method), $methods)) {
						if ($labelList[$method] == 1) {
							return true;
						} else {
							return false;
						}	

					} else {
						return false;
					} 

				} else {
					return false;
				} 
			} else {
				return false;
			}

		} else {
			return false;
		} 
	}

	/*
		Created later to solve the issue for HRM attendance update butotn access as using method() it was not working
	*/
	public function method_label_wise($label_name = null, $method = null)
	{
		$label_name = (($label_name!=null)?strtolower($label_name):$this->ci->uri->segment(1));
		$method = strtolower($method);

		// return $this->checkMethod_label_wise($label_name, $method);

		if ($this->checkMethod_label_wise($label_name, $method)) {
			$this->permission = true;
		} else {
			$this->permission = false;
		} 
		return $this;
	}

	protected function checkMethod_label_wise($label_name = null, $method = null)
	{ 
		$label_permission = $this->ci->session->userdata('label_permission');
		$isAdmin    = $this->ci->session->userdata('isAdmin');
		$isLogIn    = $this->ci->session->userdata('isLogIn');

		if ($isLogIn && $isAdmin) {
			//action of administrator
			return true;
		} else if($isLogIn) {

			if (($label_permission!=null)) {
				$label_permission = json_decode($label_permission, true);

				//label_name list
				$labels = array_keys($label_permission);

				// return  $labels;

				//check current module label_permission
				if ( in_array($label_name, $labels) ) {

					//convert method to asoc
					$methodList = $label_permission[$label_name]; 

					$methods = array_keys($label_permission[$label_name]);

					//check for each input
					if (in_array(strtolower($method), $methods)) {
						if ($methodList[$method] == 1) {
							return true;
						} else {
							return false;
						}	

					} else {
						return false;
					} 

				} else {
					return false;
				} 
			} else {
				return false;
			}

		} else {
			return false;
		} 
	}


}

