<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Taxsettingback extends MX_Controller {
    public $version='';
    public function __construct()
    {
        parent::__construct();
       $this->load->dbforge();
        $this->load->model(array(
            
            'taxsetting/taxsetting_model',
           
        ));
       $this->version=1;
    }

   public function showtaxsetting(){
    	$data['title'] = display('tax_settings');
    	$taxsinfo = $this->db->count_all('tax_settings');
        if($taxsinfo > 0){
           redirect("taxsetting/taxsettingback/update_tax_setting"); 
        }
       $data['module'] = "taxsetting";
	   $data['page']   = "back/add_setting";   
	   echo Modules::run('template/layout', $data);
    }
    public function update_tax_setting(){

    	 $data['title']    = display('tax_setting');
        $data['setinfo']  = $this->taxsetting_model->tax_setting_info();
        $data['module']   = "taxsetting";  
        $data['page']     = "back/tax_settings_update";  
        echo Modules::run('template/layout', $data);
    }


    public function create_tax_settins(){
        $taxfield = $this->input->post('taxfield',TRUE);
        $dfvalue  = $this->input->post('default_value',TRUE);
        $nt       = $this->input->post('nt',TRUE);
        $reg_no   = $this->input->post('reg_no',TRUE);
        $ishow    = $this->input->post('is_show',TRUE);
        
          $this->insert_tax_setting($taxfield,$dfvalue,$nt,$reg_no,$ishow);  
            $this->session->set_flashdata('message', display('save_successfully'));
            redirect("taxsetting/taxsettingback/showtaxsetting"); 
    }

        public function update_tax_settins(){
        
           $tablecolumn = $this->db->list_fields('tax_collection');
           $num_column = count($tablecolumn)-4;

        for ($t=0; $t < $num_column; $t++) {
        $txd = 'tax'.$t;
         if ($this->db->field_exists($txd, 'item_foods')) {
            $this->dbforge->drop_column('item_foods', $txd);
        }
        if ($this->db->field_exists($txd, 'tax_collection')) {
            $this->dbforge->drop_column('tax_collection', $txd);
        }
        if ($this->db->field_exists($txd, 'add_ons')) {
            $this->dbforge->drop_column('add_ons', $txd);
        }
     
       echo  'successfully_deleted';
          }
          

        $taxfield = $this->input->post('taxfield',TRUE);
        $dfvalue  = $this->input->post('default_value',TRUE);
        $nt       = $this->input->post('nt',TRUE);
        $reg_no   = $this->input->post('reg_no',TRUE);
        $id       = $this->input->post('id',TRUE);
        $ishow    = $this->input->post('is_show',TRUE);
        $this->db->empty_table('tax_settings');
         $this->insert_tax_setting($taxfield,$dfvalue,$nt,$reg_no,$ishow);  
           
            $this->session->set_flashdata('message', display('successfully_updated'));
            redirect("taxsetting/taxsettingback/showtaxsetting"); 
    }

     private function insert_tax_setting($taxfield,$dfvalue,$nt,$reg_no,$ishow)
	    {
	    	 for ($i=0; $i < sizeof($taxfield); $i++) {
                     $tax    = $taxfield[$i];
                    $default = $dfvalue[$i];
                    $rg_no   = $reg_no[$i];
                    $is_show = (!empty($ishow[$i])?$ishow[$i]:0);
          $data = array(
                'default_value' => $default,
                'tax_name'      => $tax,
                'nt'            => $nt,
                'reg_no'        => $rg_no,
                 ); 
         $this->db->insert('tax_settings',$data);                                   
            }
           

             for ($i=0; $i < sizeof($taxfield); $i++) {
        $fld = 'tax'.$i;

        if (!empty($fld)) {
           
                $this->dbforge->add_column('item_foods', [
                    $fld       => [
                        'type' => 'TEXT'
                    ]
                ]);

          
             $this->dbforge->add_column('tax_collection', [
                    $fld       => [
                        'type' => 'TEXT'
                    ]
                ]);
             
                $this->dbforge->add_column('add_ons', [
                    $fld       => [
                        'type' => 'TEXT'
                    ]
                ]);
          

           
            
        } 
            }

	    }

    
 public function taxsetting(){
	 	   $data['title'] = display('tax_setting');
		   $saveid=$this->session->userdata('id');
		   $data['taxinfo'] =$this->db->select('*')->from('tbl_tax')->get()->row();
		   $data['invsetting'] =$this->db->select('*')->from('tbl_invoicesetting')->get()->row();
		   $data['module'] = "taxsetting";
		   $data['page']   = "taxsetting";   
		   echo Modules::run('template/layout', $data);
	 }
  public function settingenable(){
				$status=$this->input->post('status');
				$updatetready = array(
						'tax'          => $status
				        );
				$this->db->where('taxsettings',1);
				$this->db->update('tbl_tax',$updatetready);
		} 
        public function inclusivetax()
        {
            $status = $this->input->post('status');
            $updatetready = array(
                'isvatinclusive'          => $status
            );
            $this->db->where('invstid', 1);
            $this->db->update('tbl_invoicesetting', $updatetready);
    
            if ($status == 0) {
    
                $updatetreadys = array(
                    'recalculate_vat'          => $status
                );
    
                $this->db->where('invstid', 1);
                $this->db->update('tbl_invoicesetting', $updatetreadys);
            }
        }
    
        public function recalculateVat()
        {
            $status = $this->input->post('status');
    
            $updatetready = array(
                'recalculate_vat' => $status
            );
    
            $this->db->where('invstid', 1);
            $this->db->update('tbl_invoicesetting', $updatetready);
    
            // new code by MKar starts here...
    
            if ($status == 0) {
    
                $data = [
                    'remark'  => 'recalculate vat setting disabled',
                    'status'  => 0,
                    'user_id' => $this->session->userdata('id')
                ];
    
                $this->db->insert('recalculate_vat_log', $data);
            }
    
            if ($status == 1) {
    
                $data = [
                    'remark'  => 'recalculate vat setting enabled',
                    'status'  => 1,
                    'user_id' => $this->session->userdata('id')
                ];
    
                $this->db->insert('recalculate_vat_log', $data);
            }
            // new code by MKar endss here...
    
        }
    }
