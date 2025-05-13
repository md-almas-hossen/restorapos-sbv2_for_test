<style>
/* custom checkbox */

.check_container {
    display: block;
    position: relative;
    top: 5px;
    padding-left: 35px;
    cursor: pointer;
    font-size: 14px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    width: 22%;
}

.check_container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: #d1d0d0;
}

.check_container:hover input~.checkmark {
    background-color: #ccc;
}

.check_container input:checked~.checkmark {
    background-color: #019868;
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.check_container input:checked~.checkmark:after {
    display: block;
}

.check_container .checkmark:after {
    left: 8px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}

/* custom checkbox */
</style>



<?php echo form_open('setting/customerlist/insert_customer', 'method="post" class="form-vertical" id="validate"') ?>
<div class="modal fade" id="client-info" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"><?php echo display('add_customer'); ?></h3>
            </div>

            <div class="modal-body">
                <div class="form-group row">
                    <label for="name" class="col-sm-4 col-form-label"><?php echo display('customer_name'); ?> <i
                            class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <input class="form-control simple-control" name="customer_name" id="name" type="text"
                            placeholder="Customer Name" onkeyup="special_character(this.value,'name')" required="">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-4 col-form-label"><?php echo display('email'); ?><i
                            class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <input class="form-control" name="email" id="email" type="email" placeholder="Customer Email"
                            required="">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="mobile" class="col-sm-4 col-form-label"><?php echo display('mobile'); ?><i
                            class="text-danger">*</i></label>
                    <div class="col-sm-6">
                        <input class="form-control" name="mobile" id="mobile" type="number"
                            placeholder="Customer Mobile" required="" min="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address " class="col-sm-4 col-form-label"><?php echo display('password'); ?> </label>
                    <div class="col-sm-6">
                        <input class="form-control" name="password" id="password" type="password"
                            placeholder="<?php echo display('password'); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tax_number" class="col-sm-4 col-form-label"><?php echo display('tax_number'); ?></label>
                    <div class="col-sm-6">
                        <input class="form-control" name="tax_number" id="tax_number" type="text"
                            placeholder="Tax Number" min="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="max_discount"
                        class="col-sm-4 col-form-label"><?php echo display('max_discount'); ?></label>
                    <div class="col-sm-6">
                        <input class="form-control" name="max_discount" id="max_discount" type="max_discount"
                            placeholder="Max Discount" min="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="date_of_birth"
                        class="col-sm-4 col-form-label"><?php echo display('date_of_birth'); ?></label>
                    <div class="col-sm-6">
                        <input type="date" class="form-control" name="date_of_birth"
                            value="<?php echo date('Y-m-d'); ?>" id="date_of_birth" required="" tabindex="2">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address " class="col-sm-4 col-form-label"><?php echo display('b_address'); ?></label>
                    <div class="col-sm-6">
                        <textarea class="form-control" name="address" id="address " rows="3"
                            placeholder="Customer Address"></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address " class="col-sm-4 col-form-label"><?php echo display('fav_addesrr'); ?></label>
                    <div class="col-sm-6">
                        <textarea class="form-control" name="favaddress" id="favaddress " rows="3"
                            placeholder="Customer Address"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><?php echo display('submit'); ?> </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>
<div id="edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('update_member'); ?></strong>
            </div>
            <div class="modal-body editinfo">

            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>
<div id="add1" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content customer-list">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('bulk_upload'); ?></strong>
            </div>
            <div class="modal-body">
                <div class="container">
                    <br>

                    <?php if (isset($error)) : ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('success') == TRUE) : ?>
                    <div class="form-control alert alert-success"><?php echo $this->session->flashdata('success'); ?>
                    </div>
                    <?php endif; ?>
                    <h3>You can export test.csv file Example-</h3>
                    <h4>memberid,membername,mobile,status</h4>
                    <h4>1,jhon doe,01717426371,Active</h4>
                    <h2><?php echo display('import_customer') ?> <?php echo display('upload_csv') ?></h2>
                    <?php echo form_open_multipart('setting/customerlist/importmembercsv', array('class' => 'form-vertical', 'id' => 'validate', 'name' => 'insert_attendance')) ?>
                    <input type="file" name="userfile" id="userfile"><br><br>
                    <input type="submit" name="submit" value="UPLOAD" class="btn btn-primary">
                    <?php echo form_close() ?>



                </div>

            </div>

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
                        <input type="text" name="customer_name" class="form-control" id="customer_name"
                            placeholder="<?php echo display('customer_name');?>">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <input type="text" name="customer_email" class="form-control" id="customer_email"
                            placeholder="<?php echo display('customer_email');?>">
                    </div>

                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <input type="text" name="customer_phone" class="form-control" id="customer_phone"
                            placeholder="<?php echo display('customer_phone');?>">
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-success" id="filterordlist"><?php echo display('search') ?></button>
                </div>



            </div>
            <div class="form-group text-right">

                <button class="btn  btn-inverse" id="filterbutton"><?php echo display('filter') ?></button>

                <?php if ($this->permission->method('setting', 'create')->access()) : ?>
                <button type="button" class="btn btn-success btn-md" data-target="#client-info" data-toggle="modal"
                    data-backdrop="static" data-keyboard="false"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                    <?php echo display('add_customer') ?></button>
                <?php endif; ?>

                <button class="btn btn-danger" onclick="deleteSelectedRows()">Delete Selected</button>

            </div>

        </div>
        <div class="panel panel-default thumbnail">
            <div class="panel-body">



                <table width="100%" class="table table-striped table-bordered table-hover" id="customer_list">
                    <thead>
                        <tr>
                            <th width="15px">
                                <label class="check_container"><?php echo display('all');?>
                                    <input type="checkbox" class="all_customers" onchange="selectAllCustomers()">
                                    <span class="checkmark"></span>
                                </label>
                            </th>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('customer_name') ?></th>
                            <th><?php echo display('email') ?></th>
                            <th><?php echo display('mobile') ?></th>
                            <th><?php echo display('address') ?></th>
                            <th><?php echo display('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>

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
    var orderlist = $('#customer_list').DataTable({
        responsive: true,
        paging: true,
        order: [
            [1, 'desc']
        ],
        "language": {
            "sProcessing": lang.Processingod,
            "sSearch": lang.search,
            "sLengthMenu": lang.sLengthMenu,
            "sInfo": lang.sInfo,
            "sInfoEmpty": lang.sInfoEmpty,
            "sInfoFiltered": lang.sInfoFiltered,
            "sInfoPostFix": "",
            "sLoadingRecords": lang.sLoadingRecords,
            "sZeroRecords": lang.sZeroRecords,
            "sEmptyTable": lang.sEmptyTable,
            "oPaginate": {
                "sFirst": lang.sFirst,
                "sPrevious": lang.sPrevious,
                "sNext": lang.sNext,
                "sLast": lang.sLast
            },
            "oAria": {
                "sSortAscending": ":" + lang.sSortAscending + '"',
                "sSortDescending": ":" + lang.sSortDescending + '"'
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
        'columnDefs': [{
            'targets': [0],
            'orderable': false,
        }],
        dom: 'Blfrtip',
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
            url: basicinfo.baseurl + "setting/customerlist/customer_list", // json datasource
            type: "post", // type of method  ,GET/POST/DELETE
            "data": function(data) {
                // console.log(data);
                data.csrf_test_name = $('#csrfhashresarvation').val();
                data.customer_name = $('#customer_name').val();
                data.customer_email = $('#customer_email').val();
                data.customer_phone = $('#customer_phone').val();
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
        var customer_name = $("#customer_name").val();
        var customer_email = $("#customer_email").val();
        var customer_phone = $("#customer_phone").val();
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














// new code by MKar starts here...
// set id to row
$(document).ready(function() {
    // Initialize DataTable
    var dataTable = $('#customer_table').DataTable();

    // Function to set IDs for rows
    function setRowIds() {
        $('#customer_table tbody tr').each(function(index) {
            // Set a unique id for each row based on its index
            $(this).attr('id', 'row_' + (index + 1));
        });
    }

    // Initial setting of IDs
    setRowIds();

    // Event delegation for dynamically added rows
    $('#customer_table tbody').on('DOMNodeInserted', 'tr', function() {
        // Set IDs for the newly added rows
        setRowIds();
    });

    // Event handler for adding a new row
    $('#addRow').on('click', function() {
        // Add a new row to DataTable
        dataTable.row.add([
            dataTable.rows().count() + 1, // Incremental ID
            'New Customer'
            // Add more data for additional columns as needed
        ]).draw('full-hold');

        // Trigger the DOMNodeInserted event manually
        $('#customer_table tbody tr:last').trigger('DOMNodeInserted');
    });
});



// get all selected
function selectAllCustomers() {

    var allCustomersCheckbox = document.querySelector('.all_customers');
    var customerCheckboxes = document.querySelectorAll('.customer');

    customerCheckboxes.forEach(function(checkbox) {
        checkbox.checked = allCustomersCheckbox.checked;
    });

}
// delete all
function deleteSelectedRows() {


    var selectedCheckboxes = $('.customer:checked');
    var idsToDelete = [];

    selectedCheckboxes.each(function() {
        var customerId = $(this).closest('tr').find('td').find('input.customer').val();
        idsToDelete.push(customerId);
    });



    swal({
            title: "Delete Customers",
            text: "Are you sure want Delete Selected Customers?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: true,
            closeOnCancel: true
        },

        function(isConfirm) {

            if (isConfirm) {

                $.ajax({

                    url: basicinfo.baseurl + "setting/customerlist/deleteSelected",
                    method: 'POST',
                    data: {
                        csrf_test_name: $('#csrfhashresarvation').val(),
                        ids: idsToDelete
                    },
                    success: function(response) {

                        var respo = JSON.parse(response);
                        toastr.options = {
                            closeButton: true,
                            progressBar: true,
                            showMethod: 'slideDown',
                            timeOut: 5000

                        };
                        if(respo.status){

                            toastr.success(respo.msg, 'success');

                            setTimeout(() => {
                                location.reload();
                            }, 2000);

                        }else{
                            
                            toastr.error(respo.msg, "warning");
                        }
                        
                        // selectedCheckboxes.closest('tr').remove();
                        // location.reload();

                    },
                    error: function(error) {

                        alert('Error Deleting Customers');
                        console.error('Error deleting rows:', error);
                    }
                });

            }
        }


    )
};
// new code by MKar ends here...
</script>