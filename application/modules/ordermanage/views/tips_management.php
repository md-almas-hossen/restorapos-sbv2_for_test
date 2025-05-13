    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="align-items-center panel panel-bd">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><?php echo (!empty($title)?$title:null) ?></h4>
                        <div class="form-group text-right">
                         <button type="button" class="btn btn-primary btn-md" data-target="#add_tips_modal" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                        <?php echo 'Add Tips'?></button> 
                        </div>
                    </div>

                </div>
                <div class="panel-body">
                    <table width="100%"  class="table table-striped table-bordered table-hover" id="thirdpartyorderlist">
                        <thead>
                            <tr>
                                <th ><?php echo display('sl');?>. </th>  
                                <th ><?php echo display('waiter');?></th>
                                <th ><?php echo display('amount');?></th>
                                <th ><?php echo display('date');?></th>
                                <th ><?php echo display('action');?></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>  
            </div>
        </div>
    </div>



    <div id="add_tips_modal" class="modal fade" role="dialog">
       <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <strong><?php echo  "Add Tips";?></strong>
                </div>
                <div class="modal-body" id="add_tips_modalview">
                  <?php echo  form_open('ordermanage/order/waiter_tips_entry') ?>
                    <div class="form-group row">
                        <label for="payment" class="col-sm-4 col-form-label"><?php echo display('waiter')?></label>
                        <div class="col-sm-8">
                        <?php echo form_dropdown('waiter_id',$waiterlist,'','class="form-control js-basic-single" id="waiter_id"') ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="amount" class="col-sm-4 col-form-label"><?php echo display('amount')?></label>
                        <div class="col-sm-8">
                                <input name="amount" class="form-control" id="amount" type="text" placeholder="Amount" >
                        </div>
                    </div>
                    <div class="form-group row">
                         <label for="amount" class="col-sm-4 col-form-label"></label>
                         <div class="col-sm-8">
                          <button type="submit" class="btn btn-success w-md m-b-5" id="tips_check"><?php echo display('save') ?></button>
                         </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
    <div id="edit_tips_modal" class="modal fade" role="dialog">
       <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <strong><?php echo  "Pic-up Delivery Agent";?></strong>
                </div>
                <div class="modal-body" id="edit_tips_modalview">
                 
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>


    <!-- edit_tips_management.php -->

    <script>
// JavaScript Document
$(document).ready(function () {
    "use strict";
    $('#thirdpartyorderlist').DataTable({ 
            responsive: true, 
            paging: true,
                "language": {
                "sProcessing":     lang.Processingod,
                "sSearch":         lang.search,
                "sLengthMenu":     lang.sLengthMenu,
                "sInfo":           lang.sInfo,
                "sInfoEmpty":      lang.sInfoEmpty,
                "sInfoFiltered":   lang.sInfoFiltered,
                "sInfoPostFix":    "",
                "sLoadingRecords": lang.sLoadingRecords,
                "sZeroRecords":    lang.sZeroRecords,
                "sEmptyTable":     lang.sEmptyTable,
                "oPaginate": {
                    "sFirst":      lang.sFirst,
                    "sPrevious":   lang.sPrevious,
                    "sNext":       lang.sNext,
                    "sLast":       lang.sLast
                },
                "oAria": {
                    "sSortAscending":  ":"+lang.sSortAscending+'"',
                    "sSortDescending": ":"+lang.sSortDescending+'"'
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
            "lengthMenu": [[ 25, 50, 100, 150, 200, 500, -1], [ 25, 50, 100, 150, 200, 500, "All"]], 
            buttons: [  
                {extend: 'copy', className: 'btn-sm',footer: true}, 
                {extend: 'csv', title: 'ExampleFile', className: 'btn-sm',footer: true}, 
                {extend: 'excel', title: 'ExampleFile', className: 'btn-sm', title: 'exportTitle',footer: true}, 
                {extend: 'pdf', title: 'ExampleFile', className: 'btn-sm',footer: true}, 
                {extend: 'print', className: 'btn-sm',footer: true},
                {extend: 'colvis', className: 'btn-sm',footer: true}  
                
            ],
            "searching": true,
              "processing": true,
                     "serverSide": true,
                     "ajax":{
                        url :basicinfo.baseurl+"ordermanage/order/tips_managementslist", // json datasource
                        type: "post",  // type of method  ,GET/POST/DELETE
                        "data": function ( data ) {
                            data.csrf_test_name = $('#csrfhashresarvation').val();
                        }
                      },
                "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
     
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
     
                // Total over all pages
                // total = api
                //     .column(5 )
                //     .data()
                //     .reduce( function (a, b) {
                //         return intVal(a) + intVal(b);
                //     }, 0 );
     
                // Total over this page
                // pageTotal = api
                //     .column( 5, { page: 'current'} )
                //     .data()
                //     .reduce( function (a, b) {
                //         return intVal(a) + intVal(b);
                //     }, 0 );
                // var pageTotal = pageTotal.toFixed(2); 
                // var total = total.toFixed(2); 
                // // Update footer
                // $( api.column( 5 ).footer() ).html(
                //     pageTotal +' ( '+ total +' total)'
                // );
            }
    });
});

// 




    $('#tips_check').on('click',function(){
        var  amount=$('#amount').val();
        var  waiter_id=$('#waiter_id').val();
        if(waiter_id ==""){
          toastr.error('Waiter is required');
          return false;
        }
        if(amount ==""){
          toastr.error('Tips Amount is required');
          return false;
        }
    });


    function edit_tipsinfo(id){
        var csrf = $('#csrfhashresarvation').val();
        $.ajax({
            url: basicinfo.baseurl + "ordermanage/order/edit_waiter_tips",
            type: "POST",
            data: {
                id:id,
                csrf_test_name: csrf
            },
            success: function(data) {
                    
                // $("#pickupmodalview").html("");
                $('#edit_tips_modalview').html(data);
                $('#edit_tips_modal').modal('show');

            },
            error: function(xhr) {
                alert('failed!');
            }
        });

    }
    </script>