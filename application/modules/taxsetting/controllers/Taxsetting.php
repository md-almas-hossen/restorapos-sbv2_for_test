<?php
defined('BASEPATH') OR exit('No direct script access allowed');

   
class Taxsetting extends MX_Controller {
    public $fb;
    public $version='';
    public function __construct() {
       parent::__construct();
       $this->load->library("session");
         $this->load->model(array(  
            'texsetting/texsetting_model', 
        ));
        $this->version=1;
         

    }
   


    
}