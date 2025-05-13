<style>
.sameChecked {
    width: 26%;
    box-sizing: border-box;
    border: 1px solid #e4e5e7;
    height: calc(0.5em + 0.75rem + 2px);
    padding: 6px 12px;
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
}
</style>
<div class="form-group text-right">
    <?php //if($this->permission->method('purchase','create')->access()): ?>
    <a href="<?php echo base_url("purchase/purchase/supplier_po_request_save")?>" class="btn btn-success btn-md"><i
            class="fa fa-plus-circle" aria-hidden="true"></i>
        <?php echo display('add_po_request')?></a>
    <?php //endif; ?>

</div>
<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <table width="100%" class="datatable2 table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('purchase_no'); ?></th>
                            <th><?php echo display('supplier_name') ?></th>
                            <th><?php echo display('date') ?></th>
                            <th><?php echo display('price') ?></th>
                            <th><?php echo display('action') ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($purchaselist)) { ?>
                        <?php $sl = $pagenum+1; ?>
                        <?php foreach ($purchaselist as $items) { 
                                
                     
                                ?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td>
                                <?php echo getPrefixSetting()->purchase. '-' . $items->purID; ?>
                                <!-- <a href="<?php echo base_url("purchase/purchase/purchaseinvoice/$items->purID") ?>">
                                            <?php //echo getPrefixSetting()->purchase. '-' . $items->purID; ?>
                                            </a>
                                        </td> -->
                            <td><?php echo $items->supName; ?></td>
                            <td><?php $originalDate = $items->purchasedate;
									echo $newDate = date("d-M-Y", strtotime($originalDate));
									 ?></td>
                            <td><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                <?php echo $items->total_price; ?>
                                <?php if($currency->position==2){echo $currency->curr_icon;}?></td>
                            <td>



                                <input name="url" type="hidden" id="url_purchase_<?php echo $items->purID; ?>" value="<?php echo base_url("purchase/purchase/viewSinglePurchaseOrderInfo") ?>" />
                                <a onclick="viewPurchaseInfo(<?php echo $items->purID;?>)" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-eye" aria-hidden="true"></i></a> 




                                
                                <?php if($this->permission->method('setting','update')->access() && $items->purchase_status == Null) : ?>
                                <input name="url" type="hidden" id="url_<?php echo $items->purID; ?>"
                                    value="<?php echo base_url("purchase/purchase/updateintfrm") ?>" />
                                <a href="<?php echo base_url("purchase/purchase/supplier_po_request_edit/$items->purID") ?>"
                                    class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left"
                                    title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                <?php endif;  ?>

                                <!-- <input type="checkbox"  class="sameChecked" onclick="updatecolection(<?php echo $sl;?>,<?php echo $items->purID ?>)"  id="csl_<?php echo $sl;?>"  <?php if($items->purchase_status=='1'){ echo "checked" ;}?>> -->

                                <?php if($items->purchase_status == Null){?>

                                <a href="<?php echo base_url("purchase/purchase/supplier_po_request_convert/$items->purID") ?>"
                                    class="btn btn-primary btn-md"
                                    id="csl_<?php echo $sl;?>"><?php echo display('send_to_purchase');?></a>

                                <!-- <a href="javascript:void(0)" class="btn btn-primary btn-md" onclick="updatecolection(<?php echo $sl;?>,<?php echo $items->purID ?>)"  id="csl_<?php echo $sl;?>"><?php echo display('send_to_purchase');?></a> -->

                                <?php }?>

                                <input type="hidden" class="sameCheckedval" value="0" name="investigate_status[]"
                                    id="csls_<?php echo $sl;?>">
                            </td>

                        </tr>
                        <?php $sl++; ?>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table> <!-- /.table-responsive -->
                <div class="text-right"><?php echo @$links?></div>
            </div>
        </div>
    </div>
</div>


<div id="edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close font-bold" data-dismiss="modal"><strong>&times;</strong></button>
                <strong><?php echo "PO Request Details"; ?></strong>
            </div>
            <div class="modal-body editinfo">

            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>

<script src="<?php echo base_url('application/modules/purchase/assets/js/purchaseinvoice_script.js'); ?>" type="text/javascript"></script>


<script>

    "use strict";
    function printModalContent(modald) {
        var divName = "vaucherPrintArea";
        document.title = '<?php echo $setting->title;?>'; // Set a blank title
        var printContents = document.getElementById(modald).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;

        window.print();
        document.body.innerHTML = originalContents;
        setTimeout(function() {
            // $('#'+modald).modal().hide();;
            // $("#"+modald + " .close").click();
                location.reload();
            }, 100);
    }


    function viewPurchaseInfo(id){
	   var geturl=$("#url_purchase_"+id).val();
	   var myurl =geturl+'/'+id;
	   var csrf = $('#csrfhashresarvation').val();
	    var dataString = "id="+id+"&csrf_test_name="+csrf;
        console.log(id);

		 $.ajax({
            type: "GET",
            url: myurl,
            data: dataString,
            success: function(data) {
                $('.editinfo').html(data);
                $('#edit').modal({backdrop: 'static', keyboard: false},'show');
                $(".datepicker").datepicker({
                    dateFormat: "dd-mm-yy"
                }); 
            } 
        });
	}
</script>


<script>
(function($) {
    "use strict";
    $(document).ready(function() {


        // $(".sameChecked").prop('checked', $(this).prop('checked'));
        //     if ($(".sameChecked").is(":checked")) {
        //         $(".sameCheckedval").attr("value", "1");
        //     } else {
        //         $(".sameCheckedval").attr("value", "0");
        //     } 

    });
})(jQuery);

function updatecolection(sl, id) {

    // if ($("#csl_"+sl).is(":checked")) {
    //     $("#csl_"+sl).attr("value", "1");
    // } else {
    //     $("#csl_"+sl).attr("value", "0");
    // } 
    // $value=$("#csl_"+sl).val();


    $value = parseInt($("#csls_" + sl).val());
    if ($value == 0) {
        $("#csls_" + sl).val(1);
        $value = 1;
    }

    var csrf = $('#csrfhashresarvation').val();
    var submit_url = basicinfo.baseurl + "purchase/purchase/po_request_send_purchase";

    if (confirm(lang.are_you_sure)) {

        $.ajax({
            type: 'POST',
            url: submit_url,
            data: {
                'csrf_test_name': csrf,
                'id': id,
                'itemvalue': $value
            },
            dataType: 'JSON',
            success: function(res) {



                if (res.success == true) {
                    toastr.success('success', res.message);

                    $("#csl_" + sl).hide();
                    window.location.href = res.return_url;

                } else {
                    toastr.success('error', res.message);
                }
            }
        });

    } else {
        console.log('Execution cancelled.');
    }

}
</script>