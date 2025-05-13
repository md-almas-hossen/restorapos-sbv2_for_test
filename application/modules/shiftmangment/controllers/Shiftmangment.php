<?php
defined('BASEPATH') OR exit('No direct script access allowed');

   
class Shiftmangment extends MX_Controller {
	public $version='';
    public function __construct() {
       parent::__construct();
       $this->load->library("session");
         $this->load->model(array(  
            'shiftmangment/shiftmangment_model', 
        ));
      $this->version=1;
         

    }
  
    
}