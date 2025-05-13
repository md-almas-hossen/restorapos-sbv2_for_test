<div id="add0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('supplier_add');?></strong>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">


                            <div class="panel-body">

                                <?php echo  form_open('purchase/supplierlist/create') ?>
                                <?php echo form_hidden('supid', (!empty($intinfo->supid)?$intinfo->supid:null)) ?>
                                <div class="form-group row">
                                    <label for="suppliername"
                                        class="col-sm-5 col-form-label"><?php echo display('supplier_name') ?><i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <input name="suppliername" class="form-control" type="text"
                                            placeholder="Add <?php echo display('supplier_name') ?>" id="suppliername"
                                            value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email"
                                        class="col-sm-5 col-form-label"><?php echo display('email') ?></label>
                                    <div class="col-sm-7">
                                        <input name="email" class="form-control" type="email"
                                            placeholder="Add <?php echo display('email') ?>" id="email" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="mobile"
                                        class="col-sm-5 col-form-label"><?php echo display('mobile') ?><i
                                            class="text-danger">*</i></label>
                                    <div class="col-sm-7">
                                        <input name="mobile" class="form-control" type="text"
                                            placeholder="Add <?php echo display('mobile') ?>" id="mobile" value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="mobile"
                                        class="col-sm-5 col-form-label"><?php echo display('tax_number') ?></label>
                                    <div class="col-sm-7">
                                        <input name="tax_number" class="form-control" type="text"
                                            placeholder="Add <?php echo display('tax_number') ?>" id="tax_number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="contact_person_name"
                                        class="col-sm-5 col-form-label"><?php echo display('contact_person_name') ?></label>
                                    <div class="col-sm-7">
                                        <input name="contact_person_name" type="text" class="form-control"
                                            placeholder="Add <?php echo display('contact_person_name') ?>"
                                            id="contact_person_name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="contact_person_email"
                                        class="col-sm-5 col-form-label"><?php echo display('contact_person_email') ?></label>
                                    <div class="col-sm-7">
                                        <input name="contact_person_email" class="form-control" type="text"
                                            placeholder="Add <?php echo display('contact_person_email') ?>"
                                            id="contact_person_email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="contact_person_phone"
                                        class="col-sm-5 col-form-label"><?php echo display('contact_person_phone') ?></label>
                                    <div class="col-sm-7">
                                        <input name="contact_person_phone" class="form-control" type="number"
                                            placeholder="Add <?php echo display('contact_person_phone') ?>"
                                            id="contact_person_phone" value="">
                                    </div>
                                </div>
                                <!--<div class="form-group row">
                            <label for="mobile" class="col-sm-5 col-form-label"><?php //echo display('previous_balance') ?> *</label>
                            <div class="col-sm-7">
                                <input name="previous_balance" class="form-control" type="text" placeholder="<?php //echo display('previous_balance') ?>" id="previous_balance" value="">
                            </div>
                        </div>-->
                                <div class="form-group row">
                                    <label for="address"
                                        class="col-sm-5 col-form-label"><?php echo display('address') ?></label>
                                    <div class="col-sm-7">
                                        <textarea name="address" class="form-control" cols="50" rows="3"
                                            placeholder="Add <?php echo display('address') ?>" id="address"></textarea>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <button type="reset"
                                        class="btn btn-warning w-md m-b-5"><?php echo display('reset') ?></button>
                                    <button type="submit"
                                        class="btn btn-success w-md m-b-5"><?php echo display('Ad') ?></button>
                                </div>
                                <?php echo form_close() ?>

                            </div>
                        </div>
                    </div>
                </div>



            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>

<div id="edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('supplier_edit');?></strong>
            </div>
            <div class="modal-body editinfo">

            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>
<div class="row">
    <!--  table area -->
    <div class="col-sm-12">
        <div class="panel-title">
            <div class="row" id="hidesupplier">
                <div class="col-sm-3">
                    <div class="form-group">
                        <input type="text" name="supName" class="form-control" id="supName" placeholder="Supplier Name">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <input type="text" name="supEmail" class="form-control" id="supEmail"
                            placeholder="Supplier Email">
                    </div>

                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <input type="text" name="supMobile" class="form-control" id="supMobile"
                            placeholder="Supplier Phone">
                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <button class="btn btn-success" id="filterordlist"><?php echo display('search') ?></button>
                </div>
            </div>
            <div class="form-group text-right">
                <button class="btn btn-success" id="filterbutton"><?php echo display('filter') ?></button>
                <?php if($this->permission->method('purchase','create')->access()): ?>
                <button type="button" class="btn btn-success btn-md" data-target="#add0" data-toggle="modal"
                    data-backdrop="static" data-keyboard="false"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                    <?php echo display('supplier_add')?></button>
                <?php endif; ?>
            </div>

        </div>
        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo (!empty($title)?$title:null) ?></h4>
                </div>
            </div>

            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="supplierlist">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('supplier_name') ?></th>
                            <th><?php echo display('email') ?></th>
                            <th><?php echo display('mobile') ?></th>
                            <th><?php echo display('address') ?></th>
                            <!--<th><?php //echo display('balance') ?></th>-->
                            <th><?php echo display('action') ?></th>

                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table> <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>



<script>
$("#hidesupplier").hide();

$('body').on('click', '#filterbutton', function() {
    // $("#hidesupplier").show(); 
    $("#hidesupplier").slideToggle();
});

// JavaScript Document
$(document).ready(function() {
    "use strict";
    var orderlist = $('#supplierlist').DataTable({
        responsive: true,
        paging: true,
        "language": {
            "sProcessing": lang.Processingod,
            "sSearch": lang.search,
            "sLengthMenu": lang.sLengthMenu,
            "sInfo": lang.sInfo,
            "sInfoEmpty": lang.sInfoEmpty,
            "sInfoFiltered": "",
            "sInfoPostFix": "",
            "sLoadingRecords": lang.sLoadingRecords,
            "sZeroRecords": lang.sZeroRecords,
            "sEmptyTable": lang.sEmptyTable,
            "paginate": {
                "first": lang.sFirst,
                "last": lang.sLast,
                "next": lang.sNext,
                "previous": lang.sPrevious
            },
            "oAria": {
                "sSortAscending": ": " + lang.sSortAscending,
                "sSortDescending": ": " + lang.sSortDescending
            },
            "select": {
                "rows": {
                    "_": lang._sign,
                    "0": lang._0sign,
                    "1": lang._1sign
                }
            },
            buttons: {
                copy: lang.copy,
                csv: lang.csv,
                excel: lang.excel,
                pdf: lang.pdf,
                print: lang.print,
                colvis: lang.colvis
            }
        },
        dom: 'Bfrtip',
        "lengthMenu": [
            [25, 50, 100, 150, 200, 500, -1],
            [25, 50, 100, 150, 200, 500, "All"]
        ],
        buttons: [{
                extend: 'copy',
                className: 'btn-sm'
            },
            {
                extend: 'csv',
                title: 'ExampleFile',
                className: 'btn-sm',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excel',
                title: 'ExampleFile',
                className: 'btn-sm',
                title: 'exportTitle',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                title: 'ExampleFile',
                className: 'btn-sm',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'print',
                className: 'btn-sm',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'colvis',
                className: 'btn-sm'
            }

        ],
        "searching": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: basicinfo.baseurl + "purchase/supplierlist/supplier_list", // json datasource
            type: "post", // type of method  ,GET/POST/DELETE
            "data": function(data) {
                // console.log(data);
                data.csrf_test_name = $('#csrfhashresarvation').val();
                data.supName = $('#supName').val();
                data.supEmail = $('#supEmail').val();
                data.supMobile = $('#supMobile').val();
                // data.enddate = $('#to_date').val();
            }
        },
        //  "footerCallback": function ( row, data, start, end, display ) {
        //     var api = this.api(), data;

        // Remove the formatting to get integer data for summation
        // var intVal = function ( i ) {
        //     return typeof i === 'string' ?
        //         i.replace(/[\$,]/g, '')*1 :
        //         typeof i === 'number' ?
        //             i : 0;
        // };

        // Total over all pages
        // total = api
        //     .column(4)
        //     .data()
        //     .reduce( function (a, b) {
        //         return intVal(a) + intVal(b);
        //     }, 0 );

        // Total over this page
        // pageTotal = api
        //     .column( 4, { page: 'current'} )
        //     .data()
        //     .reduce( function (a, b) {
        //         return intVal(a) + intVal(b);
        //     }, 0 );
        // var pageTotal = pageTotal.toFixed(2); 
        // var total = total.toFixed(2); 
        // // Update footer
        // $( api.column(4 ).footer() ).html(
        //     pageTotal +' ( '+ total +' total)'
        // );
        //   }
    });
    $('#filterordlist').click(function() {
        var supName = $("#supName").val();
        var supEmail = $("#supEmail").val();
        var supMobile = $("#supMobile").val();
        // var enddate=$("#to_date").val();
        // if(customer_name==''  || customer_email=='' || customer_phone==''){
        // 	alert('lang.Please_enter_From_Date');
        // 	return false;
        // }
        // if(customer_email==''){
        // 	alert('lang.Please_enter_From_Date');
        // 	return false;
        // }
        // if(customer_phone==''){
        // 	alert('lang.Please_enter_From_Date');
        // 	return false;
        // }
        // if(enddate==''){
        // 	alert(lang.Please_enter_To_Date);
        // 	return false;
        // 	}
        orderlist.ajax.reload(null, false);
    });

});
</script>