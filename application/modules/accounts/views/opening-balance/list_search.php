

<div class="row" id="printArea">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd" style="border: 0; ">
            <?php
            $path = base_url((!empty($setting->logo) ? $setting->logo : 'assets/img/icons/mini-logo.png'));
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $newformDate = date("d-M-Y", strtotime($dtpFromDate));
            $newToDate = date("d-M-Y", strtotime($dtpToDate));
            ?>
            <div class="panel-body">


                <div class="table-responsive">

                    <table width="100%" class="table table-striped table-bordered table-hover" id="opb_list">
                        <thead>
                            <tr>
                                
                                <th><?php echo display('sl'); ?></th>
                                <th><?php echo display('year'); ?></th>
                                <th><?php echo display('date') ?></th>
                                <th>Accounting Type Name</th>
                                <th><?php echo display('account_name') ?></th>
                                <th><?php echo display('sub_type'); ?></th>
                                <th><?php echo display('subcode'); ?></th>
                                <th class="text-right"><?php echo display('debit') ?></th>
                                <th class="text-right"><?php echo display('credit') ?></th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php

                            $k = 0;
                            foreach ($result as $key => $data) {
                                $k++;

                                $style = $k + 1 % 2 ? '' : '#efefef!important';

                            ?>

                                <tr>
                                    <td style="background:<?php echo $style; ?>"><?php echo $k; ?></td>
                                    <td style="background:<?php echo $style; ?>"><?php echo $data['title']; ?></td>
                                    <td style="background:<?php echo $style; ?>"><?php echo $data['start_date']?date('d-m-Y', strtotime($data['start_date'])):' '; ?></td>
                                    <td style="background:<?php echo $style; ?>"><?php echo $data['account_type_name']; ?></td>
                                    <td style="background:<?php echo $style; ?>"><?php echo $data['account_name']; ?></td>
                                    <td style="background:<?php echo $style; ?>"><?php echo $data['subtype_name']; ?></td>
                                    <td style="background:<?php echo $style; ?>"><?php echo $data['subcode_name']; ?></td>
                                    <td style="background:<?php echo $style; ?>"><?php echo $data['debit']; ?></td>
                                    <td style="background:<?php echo $style; ?>"><?php echo $data['credit']; ?></td>
                                </tr>

                            <?php } ?>

                        </tbody>
                    </table>

                </div>

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



</div>



<script>


    function changePage(pageNo) {
        var fiyear_id = $('#fiyear_id').find(":selected").val();
        var row = $('#row').find(":selected").val();

        var csrf = $('#csrfhashresarvation').val();
        var myurl = baseurl + 'accounts/AccOpeningBalanceController/getOpeningBalance';

        var dataString = {
            fiyear_id: fiyear_id,
            row: row,
            page: pageNo,
            csrf_test_name: csrf
        };

        $.ajax({
            type: "POST",
            url: myurl,
            data: dataString,
            success: function(data) {
                $('#getOpeningBalance').html(data);
            },
            error: function(xhr, status, error) {
                console.error("Error occurred:", error);
            }
        });
    }
</script>