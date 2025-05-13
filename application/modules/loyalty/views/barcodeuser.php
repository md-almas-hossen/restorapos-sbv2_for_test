<div class="row">
    <!--  table area -->
    <div class="col-sm-12">
    <?php echo form_open_multipart('', array('class' => 'form-inline')) ?>
		                    <div class="form-group">
		                        <label class="" for="startid"><?php echo display('barcode_start') ?></label>
		                        <input type="number" name="startid" class="form-control" id="startid" placeholder="<?php echo display('barcode_start') ?>">
		                    </div> 

		                    <div class="form-group">
		                        <label class="" for="endid"><?php echo display('barcode_end') ?></label>
		                        <input type="number" name="endid" class="form-control" id="endid" placeholder="<?php echo display('barcode_end') ?>">
		                    </div>
 							<a id="getsearchresult" style="padding: 5px;" class="btn btn-success"><?php echo display('search') ?></a>
                            <div class="form-group text-right">
                            <a  class="btn btn-info" onclick="printDiv('printableArea')" title="Print"><span class="fa fa-print"></span></a>
                            </div>
                            </form>

        <div class="panel panel-default thumbnail"  id="printableArea"> 
            <div class="panel-body">
            			
            <div class="row" id="findbarcode">
            	<?php if (!empty($barcodeinfo)){ 
				foreach ($barcodeinfo as $barcode) {
					$si_length = strlen((int)$barcode->customer_id); 
					$str = '00000000';
					$memberbarcode = substr($str, $si_length); 
					$mbarcode = $memberbarcode.$barcode->customer_id; 
					 ?>
              <div class="col-md-2 printcls" style="float:left;"><?php echo $barcode->customer_name;?><br /><?php echo $barcode->membership_name;?><br /><img src="<?php echo site_url();?>loyalty/loyalty/barcode/<?php echo $mbarcode;?>" align="left"></div>
              <?php } } ?>
            </div>
                  
            </div>
        </div>
        
    </div>
</div>

   <script type="text/javascript">
function printDiv() {
   const style = '@page { margin:0px;font-size:18px;}.printcls{width:220px; display:block; float:left;}';
   printJS({
			printable: 'printableArea',
			type: 'html',
			font_size: '25px',
			style: style,
			scanStyles: false												
		  });
}
$(document).on('click','#getsearchresult',function(){
        var startid=$("#startid").val();
		var endid=$("#endid").val();
		var csrf = $('#csrfhashresarvation').val();
		var geturl="<?php echo base_url()?>loyalty/loyalty/findbarcode";
		var dataString='startid='+startid+'&endid='+endid+'&csrf_test_name='+csrf;
		 $.ajax({
             type: "POST",
             url: geturl,
             data: dataString,
             success: function(data) {
                     $('#findbarcode').html(data);                    
             } 
        });
       
    });
</script>  
