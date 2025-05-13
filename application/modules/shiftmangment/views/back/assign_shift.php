<div id="ajax_modal_id" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md" id="ajax_view">
        

        </div>

    </div>

<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-default thumbnail"> 

            <div class="panel-body">
            <div class="table_content table_contentpost" >
              <div class="table_content_booking"> <span class="table_booking_header"><?php
              echo display('assign_shift'); ?> 
             </span>
              
                    <div class="row" id="shiftadd">
                        <div class="form-group text-right">
                   <button type="button" class="btn btn-primary btn-md" onclick="showassignshift()"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                  <?php
              echo display('assign_shift'); ?></button> 

                  </div>
                        
                         </div>

                   
    <!--  table area -->
    <div class="col-sm-12">

        

           
                <table width="100%" class="datatable2 table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('title')?></th>
                            <th><?php echo display('total_empolyee')?></th>              
                            <th><?php echo display('action') ?></th> 
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($shift_withcount)) { ?>
                            <?php $sl = 1; 
                            
                            ?>
                            <?php foreach ($shift_withcount as $shift) { 
                              $id = $shift['id'];
                              ?>
                                <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                                    <td><?php echo $sl; ?></td>
                                    <td><?php echo $shift['shift_title']; ?></td>
                                    <td><?php echo $shift['total_empolyee']; ?></td>
                                                                      
                                     
                                   <td class="center">
                                    
                                    <input name="url" type="hidden" id="url_<?php echo $id; ?>" value="<?php echo base_url("shiftmangment/shiftmangment/updateintfrm") ?>" />
                                        <a onclick="showassignshift('<?php echo $id; ?>')" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
                                         
                                        <a  onclick="viewmember('<?php echo $id; ?>')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="right" title="View"><i class="fa fa-window-maximize" aria-hidden="true"></i></a> 
                                       
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
  

<script type="text/javascript">
       
        function saveshift(id = null){
          //console.log('test');
      var shift_id=$("#shift_id").val();
      var employee_id=$("#employee_id").val();
	  var csrf = $('#csrfhashresarvation').val();   
      if(id == null){
        var url = "<?php echo base_url()?>shiftmangment/shiftmangmentback/assign_shift";
      }
      else{
        var url = "<?php echo base_url()?>shiftmangment/shiftmangmentback/assign_shift/"+id;
      }
     
      var errormessage = '';
               
        
        if(errormessage==''){
           var dataString = 'shift='+shift_id+'&employee='+employee_id+'&csrf_test_name='+csrf;
              
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
                var url= 'shiftaddview';
              }
              else{
                var url = 'shiftaddview/'+id+'?csrf_test_name='+csrf;
              }
         $.ajax({
             type: "GET",
             url: url,
             success: function(data) {
              $('#shiftadd').html(data);
        }

        });


        }

         function viewmember(id = null)
        {
                var csrf = $('#csrfhashresarvation').val();
                var url = 'viewShift/'+id+'?csrf_test_name='+csrf;
            
         $.ajax({
             type: "GET",
             url: url,
             success: function(data) {
              $('#ajax_view').html(data);
              $('#ajax_modal_id').modal('show');
        }

        });


        }
     </script>    
