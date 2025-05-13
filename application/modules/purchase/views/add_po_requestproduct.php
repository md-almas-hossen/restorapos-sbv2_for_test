<div class="form-group text-right">
    <?php if ($this->permission->method('add_po_request', 'create')->access()): ?>
    <a href="<?php echo base_url("purchase/purchase/po_request_list") ?>" class="btn btn-success btn-md "><i
            class="fa fa-plus-circle" aria-hidden="true"></i>
        <?php echo display('po_request_list') ?></a>
    <?php endif;?>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <div class="btn-group pull-right">
                        <a href="<?php echo base_url("purchase/purchase/add_po_request") ?>" class="btn btn-success"><i
                                class="fa fa-plus"></i> <?php echo display('ingredient');?></a>
                    </div>
                    <h4>
                        <?php echo (!empty($title) ? $title : null) ?>
                    </h4>
                </div>
            </div>
            <div class="panel-body">
                <?php echo form_open_multipart('purchase/purchase/poRequestSaveproduct', array('class' => 'form-vertical', 'id' => 'insert_purchase', 'name' => 'insert_purchase')) ?>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="invoice_no" class="col-sm-3 col-form-label"><?php echo display('invoice_no') ?>

                            </label>
                            <div class="col-sm-9">
                                <input type="text" name="invoice_no" class="form-control invoice_no" id="invoice_no"
                                    placeholder="Invoice No" value="<?php echo $invoice_no;?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="Note_To" class="col-sm-3 col-form-label"><?php echo display('Note') ?>
                            </label>
                            <div class="col-sm-9">
                                <textarea class="col-sm-12 form-control" id="Note_To" name="Note_To" rows="4"
                                    placeholder="Note to customer"></textarea>
                            </div>
                        </div>
                    </div>


                </div>
                <hr>

                <div class="row table-responsive" style="width: 99%; margin: auto;">
                    <table class="table table-bordered table-hover" id="purchaseTable">
                        <thead>
                            <tr>
                                <th class="text-center" width="10%"><?php echo display('food_name') ?><i
                                        class="text-danger"></i></th>
                                <th class="text-center" width="20%"><?php echo display('varient_name') ?><i
                                        class="text-danger"></i></th>
                                <th class="text-center"><?php echo display('qty') ?> <i class="text-danger">*</i></th>
                                <th class="text-center"><?php echo display('remark') ?></th>
                                <th class="text-center"><?php echo display('action') ?></th>
                            </tr>
                        </thead>
                        <tbody id="addPurchaseItem">
                            <tr>
                                <td>
                                    <select class="form-control" name="menu_id[]" id="menu_id_1"
                                        onchange="itemWiseVarient(this.value, 1)" required>
                                        <option value="">selcte food item</option>
                                        <?php foreach ($getItemFoods as $item) {?>
                                        <option value="<?php echo $item->ProductsID;?>">
                                            <?php echo $item->ProductName;?>
                                        </option>
                                        <?php } ?>
                                    </select>

                                </td>
                                <td class="span3 supplier">
                                    <select class="form-control" name="varient_id[]" id="varient_id_1"
                                        onchange="itemVarientWiseRowAdd(this.value,1)">
                                        <option value=""><?php echo display('varient_name') ?></option>
                                    </select>
                                    <input type='hidden' id='variant_code_1' name='variant_code[]'>
                                </td>
                                <td>
                                    <input type="number" name="quantity[]" id="quantity_1"
                                        class="form-control quantity_1">
                                </td>
                                <td>
                                    <textarea style="height:34px" name="remark[]" id="remark_1"
                                        class="form-control"></textarea>
                                </td>
                                <td class="text-center"></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">
                                    <input type="button" id="add_invoice_item" class="btn btn-success"
                                        name="add-invoice-item" onclick="addmore('addPurchaseItem');"
                                        value="<?php echo display('add_more') ?> <?php echo display('item') ?>"
                                        tabindex="9">
                                </td>

                            </tr>
                        </tfoot>
                    </table>
                </div>

                <br>
                <div class="form-group row">

                    <div class="col-sm-6">
                        <div class="form-group row">
                            <label for="Terms_Cond"
                                class="col-sm-3 col-form-label"><?php echo display('terms_condition') ?> </label>
                            <div class="col-sm-9">
                                <textarea class="col-sm-12 form-control" id="Terms_Cond" name="Terms_Cond" rows="4"
                                    placeholder="<?php echo display('terms_condition') ?>"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 text-right">
                        <input type="submit" id="add_purchase" class="btn btn-success btn-large" name="add-purchase"
                            value="<?php echo display('submit') ?>">
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="fooditems" hidden>
    <option value=""><?php echo display('select'); ?> <?php echo display('food_name') ?></option>
    <?php foreach ($getItemFoods as $item) {?>
    <option value="<?php echo $item->ProductsID;?>">
        <?php echo $item->ProductName;?>
    </option>
    <?php } ?>
</div>
<script src="<?php //echo base_url('application/modules/purchase/assets/js/addpurchase_script.js');
?>" type="text/javascript"></script>

<script type="">
    $(document).ready(function(){

    });
    var base_url = "<?php echo base_url(); ?>";
	var csrf_token = $("#csrfhashresarvation").val();

        ("use strict");
        function itemWiseVarient(menu_id, sl) {
            $.ajax({
                url: base_url + "/purchase/purchase/itemWiseVarient",
                type: "POST",
                data: {
                csrf_test_name: csrf_token,
                menu_id: menu_id,
                },
                success: function (r) {
                    $("#varient_id_"+sl).html(r);
                },
            });
        }
		function itemVarientWiseRowAdd(vid, sl) {
            var vcode=$("#varient_id_"+sl).find('option:selected').attr('data-code');
			$("#variant_code_"+sl).val(vcode);
			
        }

        var count = 2;
        var limits = 500;

        function addmore(divName){
            var fooditems = $('#fooditems').html();
            // var types = $('#types').html();
            // var vatypes = $('#vattypes').html();

            var row = $("#purchaseTable tbody tr").length;
            var count = row + 1;

            var limits = 500;
            if (count == limits) {
                alert("You have reached the limit of adding " + count + " inputs");
            } else {
                // productLoad(count);
                var newdiv = document.createElement('tr');
                var tabin = "product_name_" + count;
                newdiv.innerHTML = "<td class='span3 supplier_load_" + count + "'>\n\
                                    <select class='form-control placeholder-single' name='menu_id[]' id='menu_id_"+count+"' onchange='itemWiseVarient(this.value,"+count+")'>"+fooditems+"</select>\n\
                                    </td>\n\
                                    <td><select class='form-control placeholder-single' name='varient_id[]' id='varient_id_"+count+"' onchange='itemVarientWiseRowAdd(this.value,"+count+")'><option value=''>selcte Varient</option></select><input type='hidden' id='variant_code_"+count+"' name='variant_code[]'></td>\n\
                                    <td class='text-right'><input type='number' name='quantity[]' id='quantity_1' class='form-control quantity_1'></td>\n\
                                    <td class='text-right'><textarea style='height:34px' name='remark[]' class='form-control'></textarea></td>\n\
                                    <td class='text-center'><button style='text-align: right;' class='btn btn-danger red' type='button' value='Delete' onclick='deleteRow(this)' >Delete</button></td>";
                document.getElementById(divName).appendChild(newdiv);
                document.getElementById(tabin);
                count++;
                $(".placeholder-single").select2();
            }
        }

    function deleteRow(e) {
        var t = $("#purchaseTable > tbody > tr").length;
        if (1 == t)
            alert("There only one row you can't delete.");
        else {
            var a = e.parentNode.parentNode;
            a.parentNode.removeChild(a)
        }
    }

    function productTypes(sl){
	var supplier_id = $('#suplierid').val();
	var csrf_token=$("#setcsrf").val();
	var csrfname=$("#csrfname").val();
	var csrf = $('#csrfhashresarvation').val();
	var product_type = $('#product_type_'+sl).val();
    var isMasterBranch = 1;

	// if (supplier_id == 0 || supplier_id=='') {
	// 	$("#product_type_"+sl).val('');
    //     alert('Please select Supplier !');
    //     return false;
    // }

     $.ajax({
			type: "POST",
			url: baseurl+"purchase/purchase/productTypeWiseIngredient",
			data: {product_type:product_type, isMasterBranch:isMasterBranch, csrf_test_name:csrf},
			cache: false,
			success: function(data)
			{

			 $('#product_id_'+sl).html(data);
			}
       });
}

    function singleProductInfo(sl){
        var product_id = $("#product_id_"+sl).val();

         $.ajax({
                url: base_url + "/purchase/purchase/singleProductInfo",
                type: "POST",
                data: {
                csrf_test_name: csrf_token,
                product_id: product_id,
                },
                success: function (r) {
                    var obj = JSON.parse(r);
                    // console.log(obj.ingCode);
                    $("#ingredient_code_"+sl).val(obj.ingCode);
                },
            });
    }



    </script>