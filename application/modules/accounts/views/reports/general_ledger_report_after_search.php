<link rel="stylesheet" href="<?php //echo base_url('application/modules/accounts/assets/reports/general_ledger_report_script.css'); ?>">

<style>
    .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
        z-index: 3;
        color: #fff;
        cursor: default;
        background-color: #37a000;
        border-color: #37a000;
    }


    .pagination>li>a, .pagination>li>span {
        position: relative;
        float: left;
        padding: 6px 12px;
        margin-left: -1px;
        line-height: 1.42857143;
        color: #37a000;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #ddd;
    }

</style>

<!-- print area and whole report -->
<div class="col-sm-12 col-md-12" id="printArea">
    <div class="panel panel-bd" style="border: 0;">

        <?php
        $path = base_url((!empty($setting->logo) ? $setting->logo : 'assets/img/icons/mini-logo.png'));
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $newformDate = date("d-M-Y", strtotime($dtpFromDate));
        $newToDate = date("d-M-Y", strtotime($dtpToDate));
        ?>


        <div class="panel-body">

            <div class="text-center">
                <img src="<?php echo  $path; ?>" alt="logo">
                <h2 class="mb-0"><?php echo $setting->title; ?></h2>
                <h3 class="mt-10"><?php echo display('general_ledger') . ' ' . display('report'). ' ('.$account_name.')' ?></h3>
                <h5>As on <?php echo (!empty($newformDate) ? $newformDate : ''); ?> To <?php echo (!empty($newToDate) ? $newToDate : ''); ?></h5>
            </div>
            <div class="row">
                <div class="col-xs-12 text-right">
                    <strong><?php echo display('date') ?>: <?php echo date("d-M-Y"); ?></strong><br><br>
                </div>
            </div>

            <div class="table-responsive">
                <table width="99%" align="center" class="table table-bordered table-hover" cellpadding="5" cellspacing="5" border="2">

                    <tr class="voucherList" align="center">
                        <td colspan="10" style="background: #abbff9!important">
                            <font size="+1" class="general_ledger_report_fontfamily"> <strong><?php echo display('general_ledger_of') . ' ' . $ledger->account_name . ' on ' . date('d-m-Y', strtotime($dtpFromDate)) . ' To '  . date('d-m-Y', strtotime($dtpToDate)); ?></strong></font><strong></th></strong>
                    </tr>

                    <tr class="voucherList">
                        <td style="background: #efefef!important" width="5%"><strong><?php echo display('sl'); ?></strong></td>
                        <td style="background:#efefef!important" width="10%"><strong><?php echo display('date'); ?></strong></td>
                        <td style="background: #efefef!important" width="10%"><strong><?php echo display('voucher_no'); ?></strong></td>
                        <td style="background: #efefef!important"><strong><?php echo display('rev_acc_name'); ?></strong></td>
                        <td style="background: #efefef!important" width="12%"><strong><?php echo display('remarks'); ?></strong></td>
                        <td style="background: #efefef!important" width="10%" align="right"><strong><?php echo display('voucher_type'); ?></strong></td>
                        <td style="background: #efefef!important" width="10%" align="right"><strong>(<?php echo $currency; ?>)<?php echo display('debit'); ?></strong></td>
                        <td style="background: #efefef!important" width="10%" align="right"><strong>(<?php echo $currency; ?>)<?php echo display('credit'); ?></strong></td>
                        <td style="background: #efefef!important" width="10%" align="right"><strong>(<?php echo $currency; ?>)<?php echo display('balance'); ?></strong></td>
                        <!-- <td style="background: #efefef!important" width="10%" align="right"><strong><?php echo display('voucher_id'); ?></strong></td> -->

                    </tr>

                    <tbody id="ledgerTableBody">


                        <?php

                        $k = 0;
                        foreach ($ledger_data as $key => $l_data) {
                            $k++;

                            $style = $k + 1 % 2 ? '' : '#efefef!important';

                        ?>

                            <tr class="<?php echo $k + 1 % 2 ? 'voucherList' : '' ?>">
                                <td style="background:<?php echo $style; ?>"><?php echo $k; ?></td>
                                <td style="background:<?php echo $style; ?>"><?php echo $l_data['v_date']; ?></td>

                                
                                <td style="background:<?php echo $style; ?>" align="center"><a href="javascript:" data-id="<?php echo $l_data['v_voucher_id'] ?>" data-vdate="<?php echo $l_data['v_date'] ?>" class="v_view" style="margin-right:10px" title="View Vaucher"><?php echo $l_data['v_voucher_no'] ?></a></td>
                                <td style="background:<?php echo $style; ?>"><?php echo $l_data['v_rev_acc_name']; ?></td>
                                <td style="background:<?php echo $style; ?>" align="left"><?php echo $l_data['v_remarks']; ?></td>
                            

                                <td style="background:<?php echo $style; ?>" align="left"><strong>

                                        <?php
                                        if ($l_data['v_voucher_type_id'] != 0) {
                                            $voucher_info = $vouchartypes[$l_data['v_voucher_type_id']];
                                            echo $voucher_info['name'];
                                        } else {
                                            echo "";
                                        }

                                        ?>
                                    </strong></td>

                                <td style="background:<?php echo $style; ?>" align="right"><strong><?php echo $l_data['v_debit']; ?></strong></td>
                                <td style="background:<?php echo $style; ?>" align="right"><strong><?php echo $l_data['v_credit']; ?></strong></td>
                                <td style="background:<?php echo $style; ?>" align="right"><strong><?php echo $l_data['v_balance']; ?></strong></td>
                            

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>
            </div>

        </div>

        
    </div>
</div>

<!-- pagination area -->
<div class="text-right" style="margin-right:2%">
    <nav aria-label="Page navigation">
        <ul class="pagination">

            <?php

            $totalPages = ceil($totalRow / $row); // Calculate total pages
            $currentPage = $page_n;

            if ($currentPage > 1) {
                echo '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="changePage(' . ($currentPage - 1) . ')">Previous</a></li>';
            } else {
                echo '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
            }

            for ($i = 1; $i <= $totalPages; $i++) {
                if ($i == $currentPage) {
                    echo '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
                } else {
                    echo '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="changePage(' . $i . ')">' . $i . '</a></li>';
                }
            }

            if ($currentPage < $totalPages) {
                echo '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="changePage(' . ($currentPage + 1) . ')">Next</a></li>';
            } else {
                echo '<li class="page-item disabled"><a class="page-link" href="#">Next</a></li>';
            }
            ?>
        </ul>
    </nav>
</div>

<!-- print and pdf -->
<div class="text-center general_ledger_report_btn" id="print">
    <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="printDiv();" />
    <input type="button" class="btn btn-success"  value="PDF" onclick="getPDF('printArea');"/>
</div>



<?php echo include($this->load->view('accounts/modal/voucher_details')) ?>
<script src="<?php echo base_url('application/modules/accounts/assets/reports/general_ledger_report_script.js?v=2'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/accounts/assets/js/canvas-pdf/jspdf.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('application/modules/accounts/assets/js/canvas-pdf/html2canvas.js'); ?>" type="text/javascript"></script>


<script>
    function printDiv() {
        var divName = "printArea";
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;

        window.print();
        document.body.innerHTML = originalContents;
    }

    function changePage(pageNo) {
        var cmbCode = $('#cmbCode').find(":selected").val();
        var dtpYear = $('#financial_year').find(":selected").val();
        var dtpFromDate = $('#from_date').val();
        var dtpToDate = $('#to_date').val();
        var row = $('#row').find(":selected").val();

        var csrf = $('#csrfhashresarvation').val();
        var myurl = baseurl + 'accounts/AccReportController/general_ledger_report_search';

        var dataString = {
            cmbCode: cmbCode,
            dtpYear: dtpYear,
            dtpFromDate: dtpFromDate,
            dtpToDate: dtpToDate,
            row: row,
            page: pageNo,
            csrf_test_name: csrf
        };

        $.ajax({
            type: "POST",
            url: myurl,
            data: dataString,
            success: function(data) {
                $('#getgeneralLedgerreport').html(data);
            },
            error: function(xhr, status, error) {
                console.error("Error occurred:", error);
            }
        });
    }
</script>












