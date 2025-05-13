<div class="form-group text-right">
    <?php if($this->permission->method('purchase','create')->access()): ?>
    <a href="<?php echo base_url("purchase/purchase/create")?>" class="btn btn-success btn-md"><i
            class="fa fa-plus-circle" aria-hidden="true"></i>
        <?php echo display('purchase_add')?></a>
    <?php endif; ?>
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
                            <th>Vouchers</th>
                            <th><?php echo display('action') ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($purchaselist)) { ?>
                        <?php $sl = $pagenum+1; ?>
                        <?php foreach ($purchaselist as $items) { ?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td>
                                <a href="<?php echo base_url("purchase/purchase/purchaseinvoice/$items->purID") ?>">
                                    <?php echo getPrefixSetting()->purchase. '-' . $items->invoiceid; ?>
                                </a>
                            </td>
                            <td><?php echo $items->supName; ?></td>
                            <td><?php $originalDate = $items->purchasedate;
									echo $newDate = date("d-M-Y", strtotime($originalDate));
									 ?></td>
                            <td><?php if($currency->position==1){echo $currency->curr_icon;}?>
                                <?php echo $items->total_price; ?>
                                <?php if($currency->position==2){echo $currency->curr_icon;}?></td>

                            <td><?php echo $items->VoucherNumber;?></td>

                            <td class="center">
                                <?php if($this->permission->method('setting','update')->access()): ?>
                              
                                <input name="url" type="hidden" id="url_purchase_<?php echo $items->purID; ?>" value="<?php echo base_url("purchase/purchase/viewPurchaseInfo") ?>" />
                                <a onclick="viewPurchaseInfo(<?php echo $items->purID;?>)" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-eye" aria-hidden="true"></i></a> 
                              
                                <input name="url" type="hidden" id="url_<?php echo $items->purID; ?>"
                                    value="<?php echo base_url("purchase/purchase/updateintfrm") ?>" />
                                <a href="<?php echo base_url("purchase/purchase/updateintfrm/$items->purID") ?>"
                                    class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left"
                                    title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                <a href="<?php echo base_url("purchase/purchase/deletePurchase/$items->purID/$items->voucher_event_code") ?>"
                                    class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left"
                                    title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>

                                <?php endif;  ?>
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
                <strong><?php echo "Invoice Information"; ?></strong>
            </div>
            <div class="modal-body editinfo">

            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>

<style>

    .font-bold {
        font-weight: bold;
    }

</style>

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

    "use strict"; 
    function viewPurchaseInfo(id){
	   var geturl=$("#url_purchase_"+id).val();
	   var myurl =geturl+'/'+id;
	   var csrf = $('#csrfhashresarvation').val();
	    var dataString = "id="+id+"&csrf_test_name="+csrf;
        console.log(myurl);

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
     
