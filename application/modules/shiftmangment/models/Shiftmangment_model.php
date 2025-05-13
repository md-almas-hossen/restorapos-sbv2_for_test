<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


class Shiftmangment_model extends CI_Model{

  
        
    public $table = "shifts";
    public function __construct()
    {
        parent::__construct();
          $this->load->model(array(  
          
            'shiftmangment/Shiftmangmentuser_model', 
        ));
    }
     public function shift_details(){
      return $this->hasMany(Shiftmangmentuser_model::class,'shift_id','id');
    }
     public function insert_data($table, $data)
    {
      
      $this->db->insert($table, $data);
      $this->db->insert_id();
    }
    public function read_all(){
       $result = $this->db->get('shifts')->result();
        return $result;
    }

    public function shiftwithcount(){
      $this->db->select('shift_title,id');
      $this->db->from('shifts');
      $i=0;
       $data = $this->db->get()->result_array();
       foreach ($data as $shift) {
         
        $data[$i]['total_empolyee']= $this->db->where('shift_id',$shift['id'])->get('shift_user')->num_rows();
         $i++;
       }
        return $data;
    }


    public function Employeename()
    {
      $this->db->select('emp_id');
      $this->db->from('shift_user');
      $shifts=$this->db->get()->result_array();
      $i=0;
      $emp_ids = array();
      foreach ($shifts as $shift) {
        $emp_ids[$i] = $shift['emp_id'];
        $i++;
      }
    
      $this->db->select('*');
        $this->db->from('position');
      
        $query=$this->db->get();
        $positions=$query->result_array();
    $poslist=array();
    foreach ($positions as $position) {
     
    
        $this->db->select('*');
        $this->db->from('employee_history');
        if(!empty($emp_ids)){
        $this->db->where_not_in('employee_id', $emp_ids);
        }
        $this->db->where('pos_id',$position['pos_id']);
        $pos=$this->db->get();
        $data=$pos->result();
        
       $list = array();
        if(!empty($data)){
            foreach ($data as $value){
                $list[$value->employee_id]=$value->first_name.$value->last_name."(".$value->employee_id.")";
            }

        }
        $poslist[$position['position_name']] =  $list;
      }
        return $poslist;
    }

     public function allemp()
    {
        $this->db->select('*');
        $this->db->from('position');
      
        $query=$this->db->get();
        $positions=$query->result_array();
    $poslist=array();
    foreach ($positions as $position) {
    
           $this->db->select('*');
        $this->db->from('employee_history');
        $this->db->where('pos_id',$position['pos_id']);
        $query=$this->db->get();
        $data=$query->result();
        
       $list = array();
        if(!empty($data)){
            foreach ($data as $value){
                $list[$value->employee_id]=$value->first_name.$value->last_name."(".$value->employee_id.")";
            }
        }
        $poslist[$position['position_name']] =  $list;
      }

        return $poslist;
    }

    public function showshift()
    {
        $this->db->select('*');
        $this->db->from('shifts');
        $query=$this->db->get();
        $data=$query->result();
        
       $list = array('' => 'Select One...');
        if(!empty($data)){
            foreach ($data as $value){
                $list[$value->id]=$value->shift_title.'('.$value->start_Time.'-'.$value->end_Time.')';
            }
        }
        return $list;
    }
    public function uniqeshift($id){
      $this->db->select('*');
        $this->db->from('shifts');
        $this->db->where('id',$id);
        
        $data=$this->db->get()->row();
        return $data;
    }

    public function showassignemp($id)
    {
        $this->db->select('*');
        $this->db->from('shift_user');
        $this->db->where('shift_id',$id);
        $query=$this->db->get();
        $data=$query->result();
        return $data;

    }

    public function viewassignemp($id){
      $this->db->select('*');
      $this->db->from('position');
      $query=$this->db->get();
      $datas=$query->result_array();
     
      $i=0;
      foreach ($datas as $data) {
        $this->db->select('emp.*');
        $this->db->from('employee_history as emp');
        $this->db->join('shift_user as shift','shift.emp_id=emp.employee_id','left');
        $this->db->where('shift.shift_id',$id);
        $this->db->where('emp.pos_id',$data['pos_id']);
        $query_info=$this->db->get();
        $datas[$i]['empinfo']= $query_info->result_array();
        $i++;
      }
    return $datas;

    }

    public function shiftwiselist($id)
    {
         $this->db->select('*');
        $this->db->from('position');
      
        $query=$this->db->get();
        $data=$query->result_array();
        
     
        if(!empty($data)){
          $i=0;
            foreach ($data as $value){
            $this->db->select('emp.*');
            $this->db->from('employee_history as emp');
            $this->db->join('shift_user as shift','shift.emp_id=emp.employee_id','left');
            $this->db->where('shift.shift_id',$id);
            $this->db->where('emp.pos_id',$value['pos_id']);     
            $query=$this->db->get();
        $data[$i]['Employee'] =$query->result();
            
               $i++; 
            }
        }
        return $data;

    }

    

     
}