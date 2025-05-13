<!-- <script src="http://localhost/bhojonsaas_2newacctopping/assets/js/select2.min.js" type="text/javascript"></script> -->
<?php $toppinginfo=$this->db->select("tbl_menutoping.*,add_ons.add_on_name")->from('tbl_menutoping')->join('add_ons','add_ons.add_on_id=tbl_menutoping.tid','left')->where('assignid',$addonsinfo->tpassignid)->where('menuid',$addonsinfo->menuid)->get()->result();
 $result = array();
 array_walk($alltoppingdropdown,function($v)use(&$result){ 
      $result[$v->add_on_id] = $v->add_on_id;
});

$result2 = array();
array_walk($toppinginfo,function($b)use(&$result2){ 
      $result2[$b->tid] = $b->tid;
});
$resulta=array_diff($result,$result2);

$test='';
foreach($toppinginfo as $tpinfo){
$test.='<option value="'.$tpinfo->tid.'" selected>'.$tpinfo->add_on_name.'</option>';
}

foreach($resulta as $newid){
	$restlist=$this->db->select("add_on_name,add_on_id")->from('add_ons')->where('add_on_id',$newid)->get()->row();
	$test.='<option value="'.$restlist->add_on_id.'">'.$restlist->add_on_name.'</option>';
	}
	//print_r($addonsinfo);
?>

<div class="row">
  <div class="col-sm-12 col-md-12">
    <div class="panel">
      <div class="panel-body"> <?php echo  form_open('itemmanage/menu_topping/assigntoppingcreate') ?> <?php echo form_hidden('tpassignid', (!empty($addonsinfo->tpassignid)?$addonsinfo->tpassignid:null)) ?>
        <div class="col-lg-12">
          <div class="form-group row">
            <label for="menuid" class="col-sm-2 col-form-label"><?php echo display('item_name') ?>*</label>
            <div class="col-sm-10">
              <?php 
						if(empty($toppingmenulist)){$toppingmenulist = array('' => '--Select--');}
						echo form_dropdown('menuid',$menudropdown,(!empty($addonsinfo->menuid)?$addonsinfo->menuid:null),'class="form-control newfrm"') ?>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="form-group row">
            <label for="toppingtitle" class="col-sm-2 col-form-label"><?php echo display('topping_head');?> *</label>
            <div class="col-sm-4">
              <input name="toppingtitle" class="form-control" type="text" placeholder="<?php echo display('topping_head');?>" id="toppingtitle"  value="<?php echo (!empty($addonsinfo->tptitle)?$addonsinfo->tptitle:null) ?>" required>
            </div>
            <label for="toppingtitle" class="col-sm-2 col-form-label"><?php echo display('max_sl_topping');?> *</label>
            <div class="col-sm-4">
              <input name="maxselection" class="form-control" type="number" placeholder="<?php echo display('max_sl_topping');?>" id="toppingtitle_1"  value="<?php echo (!empty($addonsinfo->maxoption)?$addonsinfo->maxoption:null) ?>" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="toppingid" class="col-sm-2 col-form-label"><?php echo display('toppingname') ?>*</label>
            <div class="col-sm-6">
              <select name="toppingid[]" class="form-control newfrm" multiple="multiple">
                <option value="">Select One</option>
                <?php echo $test;?>
              </select>
            </div>
            <div class=" checkAll col-sm-2">
              <input id="is_paid" name="is_paid" type="checkbox" class="selectall" value="<?php echo (!empty($addonsinfo->is_paid)?$addonsinfo->is_paid:'0') ?>" autocomplete="off" <?php if($addonsinfo->is_paid==1){ echo "checked";}?>>
              <label for="is_paid"><?php echo display('is_paid') ?></label>
            </div>
            <div class=" checkAll col-sm-2">
              <input id="isoption" name="isoption" type="checkbox" class="selectall" value="<?php echo (!empty($addonsinfo->isposition)?$addonsinfo->isposition:'0') ?>" autocomplete="off" <?php if($addonsinfo->isposition==1){ echo "checked";}?>>
              <label for="isoption"> <?php echo display('is_position') ?> </label>
            </div>
          </div>
        </div>
        <div class="form-group text-right">
          <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update') ?></button>
        </div>
        <?php echo form_close() ?> </div>
    </div>
  </div>
</div>
<script>
    $('.newfrm').select2({
        dropdownParent: $('#edit')
    });
	</script>