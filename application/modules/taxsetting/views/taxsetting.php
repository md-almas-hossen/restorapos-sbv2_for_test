<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag ">
            <div class="panel-heading">
                <div class="panel-title">
                    <?php echo $title; ?>
                </div>
            </div>

            <div class="panel-body">
                <div class="col-md-3">
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-1760" type="checkbox" class="individual" name="tax" value="tax"
                                <?php if($taxinfo->tax==1){ echo "checked";}?>>
                            <label for="chkbox-1760" style="display:inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('tex_enable') ?>
                            </label>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-1761" type="checkbox" class="individual" name="isvatinclusive"
                                value="isvatinclusive" <?php if($invsetting->isvatinclusive==1){echo "checked";}?>>
                            <label for="chkbox-1761" style="display:inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo display('isinclusivetax') ?>
                            </label>

                        </div>
                    </div>
                </div>

                <?php if($invsetting->isvatinclusive==1):?>
                <div id="recalculation_div" class="col-md-3">
                    <div class="row bg-brown">
                        <div class="col-sm-12 kitchen-tab" id="option">
                            <input id="chkbox-1762" type="checkbox" class="individual" name="recalculate_vat"
                                value="recalculate_vat" <?php if ($invsetting->recalculate_vat == 1) {
                                echo "checked";
                            } ?>>
                            <label for="chkbox-1762" style="display:inline-flex">
                                <span class="radio-shape">
                                    <i class="fa fa-check"></i>
                                </span>
                                <?php echo 'Vat Recalculation' ?>
                            </label>

                        </div>
                    </div>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
<script>
$('input[name="tax"]').click(function() {
    var csrf = $('#csrfhashresarvation').val();
    if ($(this).is(":checked")) {
        var menuid = $(this).val();
        var ischeck = 1;
        var dataString = 'menuid=' + menuid + '&status=1&csrf_test_name=' + csrf;
    } else if ($(this).is(":not(:checked)")) {
        var menuid = $(this).val();
        var ischeck = 0;
        var dataString = 'menuid=' + menuid + '&status=0&csrf_test_name=' + csrf;
    }

    $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>taxsetting/taxsettingback/settingenable",
        data: dataString,
        success: function(data) {
            if (ischeck == 1) {
                swal("Enable", "Tax Enable", "success");
            } else {
                swal("Disable", "Tax Enable", "warning");
            }
        }
    });
});

$('input[name="isvatinclusive"]').click(function() {
    var csrf = $('#csrfhashresarvation').val();
    if ($(this).is(":checked")) {
        var menuid = $(this).val();
        var ischeck = 1;
        var dataString = 'menuid=' + menuid + '&status=1&csrf_test_name=' + csrf;
    } else if ($(this).is(":not(:checked)")) {
        var menuid = $(this).val();
        var ischeck = 0;
        var dataString = 'menuid=' + menuid + '&status=0&csrf_test_name=' + csrf;
    }

    $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>taxsetting/taxsettingback/inclusivetax",
        data: dataString,
        success: function(data) {
            if (ischeck == 1) {

                swal("Enable", "Vat Inclusive Enable", "success");
                location.reload();


            } else {

                swal("Disable", "Vat Inclusive Disable", "success");
                location.reload();

            }
        }
    });
});


$('input[name="recalculate_vat"]').click(function() {
    var csrf = $('#csrfhashresarvation').val();
    if ($(this).is(":checked")) {
        var menuid = $(this).val();
        var ischeck = 1;
        var dataString = 'menuid=' + menuid + '&status=1&csrf_test_name=' + csrf;
    } else if ($(this).is(":not(:checked)")) {
        var menuid = $(this).val();
        var ischeck = 0;
        var dataString = 'menuid=' + menuid + '&status=0&csrf_test_name=' + csrf;
    }

    $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>taxsetting/taxsettingback/recalculateVat",
        data: dataString,
        success: function(data) {
            if (ischeck == 1) {
                swal("Enable", "Vat Recalculation Enabled", "success");
            } else {
                swal("Disable", "Vat Recalculation Disabled", "success");
            }
        }
    });
});
</script>