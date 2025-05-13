<div id="edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('Reservation');?></strong>
            </div>
            <div class="modal-body editinfo">
            
    		</div>
     
            </div>
            

        </div>

    </div>
<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-default thumbnail"> 

          <div class="panel-body">
            <div class="table_content table_contentpost" >
              <div class="table_content_booking"> <span class="table_booking_header"><?php
              echo display('create/edit_shift'); ?> 
             </span>
                <div class="row" id="shiftadd">
                    <div class="form-group text-right">
                     <button type="button" class="btn btn-primary btn-md" onclick="showassignshift()"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                        <?php
                           echo display('create'); ?>
                   </button> 

                  </div>
                   </div>
              
              <div class="row" id="availabletable">
                       
                <!--  table area -->
                <div class="col-sm-12">
                <table width="100%" class="datatable2 table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('start_time')?></th>
                            <th><?php echo display('end_time')?></th>
                            <th><?php echo display('last_date');?></th>
                            <th><?php echo display('start_date');?></th>
                             <th><?php echo display('title');?></th>
                           
                            <th><?php echo display('action') ?></th> 
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($shift)) { ?>
                            <?php $sl = 1; ?>
                            <?php foreach ($shift as $shift) { 
                              $last_date = str_replace('-','/',$shift->last_date);
                             $lastdate= date('d-m-Y' , strtotime($last_date));
                             $start_date = str_replace('-','/',$shift->start_date);
                              $startdate= date('d-m-Y' , strtotime($start_date)); ?>
                                <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                                    <td><?php echo $sl; ?></td>
                                    <td><?php echo $shift->start_Time; ?></td>
                                    <td><?php echo $shift->end_Time; ?></td>
                                    <td><?php echo $lastdate; ?></td>
                                    <td><?php echo $startdate; ?></td>
                                     <td><?php echo $shift->shift_title; ?></td>
                                     
                                   <td class="center">
                                    
                                    <input name="url" type="hidden" id="url_<?php echo $shift->id; ?>" value="<?php echo base_url("shiftmangment/shiftmangment/updateintfrm") ?>" />
                                        <a onclick="showassignshift('<?php echo $shift->id; ?>')" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
                                         
                                        <a href="<?php echo base_url("shiftmangment/shiftmangmentback/delete/$shift->id") ?>" onclick="return confirm('<?php echo display("are_you_sure") ?>')" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa fa-trash-o" aria-hidden="true"></i></a> 
                                       
                                    </td>
                                    
                                </tr>
                                <?php $sl++; ?>
                            <?php } ?> 
                        <?php } ?> 
                    </tbody>
                </table>  <!-- /.table-responsive -->
                
                
            </div>
          </div>
                 
              </div>
            </div>
            </div>
        </div>
    </div>
</div>

     <script type="text/javascript">
       
        function savedata(id = null){
          //console.log('test');
	  var csrf = $('#csrfhashresarvation').val(); 
      var start_time=$("#start_time").val();
      var end_time=$("#end_time").val();
      var last_date=$("#last_date").val();
      var shift_title=$("#shift_title").val();
      var start_date=$("#start_date").val();
      if(id == null){
      var url = "<?php echo base_url()?>shiftmangment/shiftmangmentback/addeditshift"
      }
      else{
         var url = "<?php echo base_url()?>shiftmangment/shiftmangmentback/addeditshift/"+id;

      }
      var errormessage = '';

        if(errormessage==''){
           var dataString = 'start_time='+start_time+'&end_time='+end_time+'&last_date='+last_date+'&start_date='+start_date+'&shift_title='+shift_title+'&csrf_test_name='+csrf;
              
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: dataString,
                        success: function(data){
                          if(data == 'insert'){
                            window.location.reload();
                          }
                        }
                    });
              
            
            }
        
      
    }


      function showassignshift(id = null)
        {
          var csrf = $('#csrfhashresarvation').val(); 
		  if(id == null){
                var url= 'showaddfrom';
              }
              else{
                var url= 'showaddfrom/'+id+'?csrf_test_name='+csrf;
              }
         $.ajax({
             type: "GET",
             url: url,
             success: function(data) {
              $('#shiftadd').html(data);
        }

        });


        }
     </script>
