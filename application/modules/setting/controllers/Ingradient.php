<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Ingradient extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'ingradient_model',
			'unit_model',
			'logs_model'
		));	
    }
 
    public function index($id = null)
    {
        
		$this->permission->method('setting','read')->redirect();
        $data['title']    = display('ingradient_list'); 
        #-------------------------------#       
        #
        #pagination starts
        #
        $config["base_url"] = base_url('setting/ingradient/index');
        $config["total_rows"]  = $this->ingradient_model->count_ingredient();
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
        $data["ingredientlist"] = $this->ingradient_model->read_ingredient($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
		$data['pagenum']=$page;
		if(!empty($id)) {
		$data['title'] = display('unit_update');
		$data['intinfo']   = $this->unit_model->findById($id);
	   }
	    $data['unitdropdown']   =  $this->unit_model->ingredient_dropdown();
        #
        #pagination ends
        #   
        $data['module'] = "setting";
        $data['page']   = "ingredientlist";   
        echo Modules::run('template/layout', $data); 
    }
	
	
    public function create($id = null)
    {
	  $this->permission->method('setting','create')->redirect();
	  $data['title'] = display('add_ingredient');
	  $this->load->library(array('my_form_validation'));
	  $types=$this->input->post('types',true);
	  if($types==3){
			$addtypes=2;
			$addonstypes=1;
			$condition="type=2 AND is_addons=1";
		}else{
			$addtypes=$types;
			$addonstypes=0;
			$condition="type=$types AND is_addons=0";
		}
	  $existingredients=$this->db->select('id,ingredient_name')->from('ingredients')->where('id',$this->input->post('id'))->where($condition)->get()->row();
	  #-------------------------------#
	  if (empty($this->input->post('id'))) {
		  $this->form_validation->set_rules('ingredientname',display('ingredient_name'),'required|name_uniquewithtype[ingredients.ingredient_name.type.'.$types.']|max_length[50]');
		  }
	  else{
			  if($this->input->post('ingredientname',true)==$existingredients->ingredient_name && $existingredients->id==$this->input->post('id')){
				$this->form_validation->set_rules('ingredientname',display('ingredient_name'),'required|max_length[50]');
			  }else{
				$this->form_validation->set_rules('ingredientname',display('ingredient_name'),'required|name_uniquewithtype[ingredients.ingredient_name.type.'.$types.']|max_length[50]');  
			  }
		  }
		$this->form_validation->set_rules('unitid',display('unit_name')  ,'required');
		$this->form_validation->set_rules('status', display('status')  ,'required');


	  $data['intinfo']="";


	  if ($this->form_validation->run()) { 
	   if (empty($this->input->post('id'))) {
		$this->permission->method('setting','create')->redirect();

		$lastid=$this->db->select("*")->from('ingredients')
		->order_by('ingCode','desc')
		->get()
		->row();
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

		
		$data['units']   = (Object) $postData = [
			'id'     			 => $this->input->post('id'),
			'storageunit' 	 	 => $this->input->post('storageunit',true),
			'ingredient_name' 	 => $this->input->post('ingredientname',true),
			'ingCode' 	         => $sino,
			'conversion_unit' 	 => $this->input->post('conversationqty',true),
			'barcode' 	 		 => $this->input->post('barcode',true),
			'uom_id' 	 		 => $this->input->post('unitid',true),
			'min_stock'       	 => $this->input->post('min_stock',true),
			'type'				 => $addtypes,
			'is_addons'			 => $addonstypes,
			'is_active' 	 	 => $this->input->post('status',true),
		 ];

		$logData = [
		'action_page'         => "Ingredient List",
		'action_done'     	  => "Insert Data", 
		'remarks'             => "New Ingredient Created",
		'user_name'           => $this->session->userdata('fullname'),
		'entry_date'          => date('Y-m-d H:i:s'),
		];

		if($this->ingradient_model->unit_ingredient($postData)){ 

		 $this->logs_model->log_recorded($logData);
		    $this->db->select('*');
			$this->db->from('ingredients');
			$this->db->where('is_active',1);
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$json_product[] = array('label'=>$row->ingredient_name,'value'=>$row->id);
			}
			$cache_file = './assets/js/indredient.json';
			$productList = json_encode($json_product);
			file_put_contents($cache_file,$productList);
		 $this->session->set_flashdata('message', display('save_successfully'));
		 redirect('setting/ingradient/index');

		}else{
		 $this->session->set_flashdata('exception',  display('please_try_again'));

		}
		redirect("setting/ingradient/index"); 
	
	   }else{
	
		$this->permission->method('setting','update')->redirect();

		$data['units']   = (Object) $postData = [
			'id'     => $this->input->post('id'),
			'storageunit' 	 	 => $this->input->post('storageunit',true),
			'ingredient_name' 	 => $this->input->post('ingredientname',true),
			'uom_id' 	 		 => $this->input->post('unitid',true),
			'min_stock'       	 => $this->input->post('min_stock',true),
			'conversion_unit' 	 => $this->input->post('conversationqty',true),
			'barcode' 	 		 => $this->input->post('barcode',true),
			'type'				 => $addtypes,
			'is_addons'			 => $addonstypes,
			'is_active' 	 	 => $this->input->post('status',true),
		 ];
		$logData = [
		'action_page'         => "Ingredient List",
		'action_done'     	 => "Update Data", 
		'remarks'             => "Ingredient Updated",
		'user_name'           => $this->session->userdata('fullname'),
		'entry_date'          => date('Y-m-d H:i:s'),
		];

		if ($this->ingradient_model->update_ingredient($postData)) { 
		 $this->logs_model->log_recorded($logData);
		 $this->db->select('*');
			$this->db->from('ingredients');
			$this->db->where('is_active',1);
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$json_product[] = array('label'=>$row->ingredient_name,'value'=>$row->id);
			}
			$cache_file = './assets/js/indredient.json';
			$productList = json_encode($json_product);
			file_put_contents($cache_file,$productList);
		 $this->session->set_flashdata('message', display('update_successfully'));

		}else{

			$this->session->set_flashdata('exception',  display('please_try_again'));

		}
		redirect("setting/ingradient/index");  
	   }
	  } else { 
	   if(!empty($id)) {
		$data['title'] = display('update_ingredient');
		$data['intinfo']   = $this->ingradient_model->findById($id);
	   }
	   $data['unitdropdown']   =  $this->unit_model->ingredient_dropdown();
	   $data['module'] = "setting";
	   $data['page']   = "ingredientlist";   
	   echo Modules::run('template/layout', $data); 
	   }   
 
    }
	public function getskubarcode(){
		$lastid=$this->db->select("*")->from('ingredients')
                    ->order_by('ingCode','desc')
                    ->get()
                    ->row();
                    $sls=$lastid->ingCode;
                    if(empty($sls)){
                        $sl = "ING0000";
                    }else{
                        $sl=$sls;
                    }
                  
                    $supno=substr($sl, 3);                    
                    $nextno=(int)$supno+1;
                    $si_length = strlen((int)$nextno); 
                    $str = '0000';
                    $cutstr = substr($str, $si_length); 
                    $sino = "ING".$cutstr.$nextno;
					$barcode=time();
			$mysku=array('sku'=>$sino,'barcode'=>$barcode);
			echo json_encode($mysku);

	}
   public function updateintfrm($id){
	  
		$this->permission->method('units','update')->redirect();
		$data['title'] = display('update_ingredient');
		$data['intinfo']   = $this->ingradient_model->findById($id);
		$data['unitdropdown']   =  $this->unit_model->ingredient_dropdown();
        $data['module'] = "setting";  
        $data['page']   = "ingredientedit";
		$this->load->view('setting/ingredientedit', $data);   
      
	   }
 
    public function delete($category = null)
    {
        $this->permission->module('setting','delete')->redirect();
		$logData = [
	   'action_page'         => "Ingredient List",
	   'action_done'     	 => "Delete Data", 
	   'remarks'             => "Ingredient Deleted",
	   'user_name'           => $this->session->userdata('fullname'),
	   'entry_date'          => date('Y-m-d H:i:s'),
	  ];
		if ($this->ingradient_model->ingredient_delete($category)) {
			#Store data to log table.
			 $this->logs_model->log_recorded($logData);
			 $this->db->select('*');
			$this->db->from('ingredients');
			$this->db->where('is_active',1);
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$json_product[] = array('label'=>$row->ingredient_name,'value'=>$row->id);
			}
			$cache_file = './assets/js/indredient.json';
			$productList = json_encode($json_product);
			file_put_contents($cache_file,$productList);
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('setting/ingradient/index');
    }
	public function downloadformat(){
		
		$this->permission->method('setting','read')->redirect();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Storage Unit');
		$sheet->setCellValue('B1', 'UnitName');
		$sheet->setCellValue('C1', 'Conversation Rate');
		$sheet->setCellValue('D1', 'Barcode');
		$sheet->setCellValue('E1', 'Unit Short Name');
		$sheet->setCellValue('F1', 'Ingredient');
		$sheet->setCellValue('G1', 'Re-Stock Level');
		$sheet->setCellValue('H1', 'Status');
		$rowCount   =   2;
		   $arrayfood=array (array("storageunit" => "Box", "unit" => "Kilogram","conversationrate"=>10,"barcode"=>"1345413134", "ushortname" => "kg.", "item" => "brinjal","restock"=>2,"status"=>"Active"),array("storageunit" => "Package", "unit" => "Per Pieces","conversationrate"=>20,"barcode"=>"", "ushortname" => "pcs", "item" => "Cauliflower","restock"=>2,"status"=>"Active"),array("storageunit" => "BOX", "unit" => "Kilogram","conversationrate"=>8,"barcode"=>"", "ushortname" => "kg.", "item" => "Carrot","restock"=>2,"status"=>"Active"),array("storageunit" => "Package", "unit" => "Per Pieces","conversationrate"=>12,"barcode"=>"13454138912", "ushortname" => "pcs", "item" => "Broccoli","restock"=>2,"status"=>"Active"));
		   foreach($arrayfood as $row){
			   $sheet->SetCellValue('A'.$rowCount, $row['storageunit'],'UTF-8');
			   $sheet->SetCellValue('B'.$rowCount, $row['unit'],'UTF-8');
			   $sheet->SetCellValue('C'.$rowCount, $row['conversationrate'],'UTF-8');
			   $sheet->SetCellValue('D'.$rowCount, $row['barcode'],'UTF-8');
			   $sheet->SetCellValue('E'.$rowCount, $row['ushortname'],'UTF-8');
			   $sheet->SetCellValue('F'.$rowCount, $row['item'],'UTF-8');
			   $sheet->SetCellValue('G'.$rowCount, $row['restock'],'UTF-8');
			   $sheet->SetCellValue('H'.$rowCount, $row['status'],'UTF-8');
			   $rowCount++;
		   }
		
		$writer = new Xlsx($spreadsheet);
		
		$filename = 'example.xlsx';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output'); // download file 
   }

	public function bulkinupload(){
	
		$this->permission->method('setting','read')->redirect();

		if(!empty($_FILES["userfile"]["name"])) {
			$_FILES["userfile"]["name"];
			$path = $_FILES["userfile"]["tmp_name"];
			$upload_file=$_FILES["userfile"]["name"];
			$extension=pathinfo($upload_file,PATHINFO_EXTENSION);
			if ($extension=='csv') {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			}elseif ($extension=='xls') {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			}else{
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}
			$spreadsheet = $reader->load($_FILES["userfile"]["tmp_name"]);
			$sheetdata = $spreadsheet->getActiveSheet()->toArray();	
			$datacount = count($sheetdata);
					
						
						
			$regenarate=array();
			if ($datacount>1) {
				for ($i=1; $i < $datacount; $i++) {
					$storageunit=$sheetdata[$i][0];
					$Unitname=$sheetdata[$i][1];
					$convrate=$sheetdata[$i][2];
					$getbcode=$sheetdata[$i][3];
					$Unitshotname=$sheetdata[$i][4];
					$ingredient=$sheetdata[$i][5];
					$restocklevel=$sheetdata[$i][6];
					$status=$sheetdata[$i][7];
					$newstatus=strtolower($status);
					if($newstatus=="active"){$acstatus=1;}
					else{$acstatus=0;}						
					$ptype=1;	
					$is_addons=0;					
					
					$checunit=$this->db->select("*")->from('unit_of_measurement')->where('uom_name',$Unitname)->get()->row();
					if(!empty($checunit)){
					$unitid=$checunit->id;
					}
					else{
							$unitsdata = array(
							'uom_name'  		     => $Unitname,
							'uom_short_code' 	 => $Unitshotname,
							'is_active' 	         => 1
							); 
							$this->db->insert('unit_of_measurement', $unitsdata);
							$unitid = $this->db->insert_id();
						}
						$iteminfo=$this->db->select("*")->from('ingredients')->where('ingredient_name',$ingredient)->get()->row();
						if(empty($iteminfo)){
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
							$barcode=time();
							if(empty($getbcode)){
								$barcode=$getbcode;
							}

								$item = array(
									'ingredient_name'  		=> $ingredient,
									'storageunit'  			=> $storageunit,
									'conversion_unit'  		=> $convrate,
									'ingCode'				=> $sino,
									'barcode'				=> $barcode,
									'uom_id' 			 	=> $unitid,
									'min_stock'				=> $restocklevel,
									'type' 	         		=> $ptype,
									'is_addons' 	 	    => $is_addons,
									'is_active' 	     	=> 1,
							); 
							$this->db->insert('ingredients', $item);
							//echo $this->db->last_query();
							} 
					}
				}
				$this->session->set_flashdata('message', display('save_successfully'));
				echo '<script>window.location.href = "'.base_url().'setting/ingradient/index"</script>';
				}
				else{
					$this->session->set_flashdata('exception',  display('please_try_again'));
					redirect("setting/ingradient/index"); 
					}
			}
 
}
