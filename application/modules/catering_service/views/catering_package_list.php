<link href="<?php echo base_url('application/modules/catering_service/assets/css/catering_package.css');?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('application/modules/catering_service/assets/css/catering_service.css')?>" rel="stylesheet" type="text/css" />

<section class="content">
    <div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd rounded-12 mt_15">
        <div class="panel-px border-btm">
            <div class="row d-flex align-items-center">
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="33" height="33" viewBox="0 0 33 33" fill="none">
                    <path d="M26.8333 1H6.16667C3.3132 1 1 3.3132 1 6.16667V26.8333C1 29.6868 3.3132 32 6.16667 32H26.8333C29.6868 32 32 29.6868 32 26.8333V6.16667C32 3.3132 29.6868 1 26.8333 1Z" stroke="#383838" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M11.334 1V11.3333L16.5007 7.88889L21.6673 11.3333V1" fill="#019868" />
                    <path d="M11.334 1V11.3333L16.5007 7.88889L21.6673 11.3333V1" stroke="#383838" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="ps-10">
                    <h3 class="m-0">Create Package</h3>
                    <p class="mb-0">Here Your order List Data</p>
                </div>
                </div>
            </div>
            <div class="col-md-4">

                <input class="form-control inputOrder input-sm" id="searchInput" name="search" type="search" placeholder="search" style="width: 250px; margin: auto" />

            </div>
            <div class="col-md-4">
                <a href="<?php echo base_url('catering_service/cateringservice/add_catering_package') ?>" class="btn btn-primary pull-right addBtn mt_0">
                <i class="ti-plus"></i>
                <span class="">Add Items</span>
                </a>
            </div>
            </div>
        </div>

        <div class="panel-body panel-px">
            <div class="table-responsive table_list">
            <table id="package_list" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Sl</th>
                    <th>Package Name</th>
                    <th>Person</th>
                    <!-- <th>Items</th> -->
                    <th>Price</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <!-- <tr>
                    <td>Buffet Food Menia</td>
                    <td>10</td>
                    <td>Chicken, Mutton,Chap,Vegetable</td>
                    <td>1400Tk</td>
                    <td class="act_td">
                    <button class="btn bg-transparent p-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="27" height="26" viewBox="0 0 27 26" fill="none">
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M24.9603 1.35327C23.4311 -0.117099 20.9519 -0.117111 19.4227 1.35327L17.2301 3.46148L6.91092 13.3838C6.74364 13.5447 6.62498 13.7462 6.5676 13.9669L5.26236 18.987C5.15116 19.4147 5.28149 19.8672 5.60568 20.1788C5.92987 20.4906 6.4004 20.6159 6.8452 20.509L12.0661 19.254C12.2957 19.1987 12.5052 19.0847 12.6725 18.9238L22.9167 9.07367L25.1842 6.89328C26.7135 5.42291 26.7135 3.03896 25.1842 1.5686L24.9603 1.35327ZM21.2685 3.12816C21.7782 2.63804 22.6047 2.63804 23.1144 3.12816L23.3384 3.34349C23.8481 3.83362 23.8481 4.62827 23.3384 5.11839L22.0116 6.39416L19.9814 4.36578L21.2685 3.12816ZM18.1352 6.14099L20.1653 8.16938L11.0823 16.9031L8.3225 17.5666L9.01244 14.9129L18.1352 6.14099ZM2.61292 7.99606C2.61292 7.30292 3.1973 6.74102 3.91815 6.74102H10.4443C11.1652 6.74102 11.7496 6.17912 11.7496 5.48598C11.7496 4.79284 11.1652 4.23094 10.4443 4.23094H3.91815C1.75557 4.23094 0.00244141 5.91664 0.00244141 7.99606V21.8014C0.00244141 23.8809 1.75557 25.5666 3.91815 25.5666H18.2758C20.4384 25.5666 22.1915 23.8809 22.1915 21.8014V15.5262C22.1915 14.8332 21.6071 14.2712 20.8862 14.2712C20.1653 14.2712 19.581 14.8332 19.581 15.5262V21.8014C19.581 22.4946 18.9966 23.0565 18.2758 23.0565H3.91815C3.1973 23.0565 2.61292 22.4946 2.61292 21.8014V7.99606Z"
                            fill="#0EB17D"
                        />
                        </svg>
                    </button>
               
                    <button class="btn bg-transparent p-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="29" viewBox="0 0 24 29" fill="none">
                        <path d="M9.53223 14.3894V21.4779" stroke="#EA0000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M15.6082 14.3894V21.4779" stroke="#EA0000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M1.43115 7.30103H22.6967" stroke="#EA0000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M4.46899 12.3643V23.4113C4.46899 25.6992 6.16916 27.5539 8.26641 27.5539H15.8612C17.9585 27.5539 19.6586 25.6992 19.6586 23.4113V12.3643" stroke="#EA0000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M8.51953 4.7694C8.51953 3.37123 9.57741 2.23779 10.8824 2.23779H13.2452C14.5502 2.23779 15.608 3.37123 15.608 4.7694V7.30101H8.51953V4.7694Z" stroke="#EA0000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    </td>
                </tr>
            -->
               
             
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
    </div>
</section>

<script>
    $(document).ready(function () {
         var orderlist=$('#package_list').DataTable({ 
            responsive: false, 
            paging: true,
            order: [[1, 'desc']],
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
            'columnDefs': [ {
                    'targets': [0], 
                    'orderable': false,
                 }],
            dom: 'Brtip', 
            "lengthMenu": [[ 25, 50, 100, 150, 200, 500, -1], [ 25, 50, 100, 150, 200, 500, "All"]], 
            // "lengthMenu": [[ 25, 50, 100, 150, 200, 500, -1], [ 25, 50, 100, 150, 200, 500, "All"]], 
            buttons: [  ],
            "searching": true,
              "processing": true,
                     "serverSide": true,
                     "ajax":{
                        url :basicinfo.baseurl+"catering_service/cateringservice/allPackageOrder",
                        type: "post",
                        "data": function ( data ) {

                            data.csrf_test_name = $('#csrfhashresarvation').val();
                           
                            // data.enddate = $('#to_date').val();
                        }
                      },
                });
                // $('#filterordlist').click(function() {
                //     var startdate=$("#from_date").val();
                //     var enddate=$("#to_date").val();
                //     if(startdate==''){
                //         alert(lang.Please_enter_From_Date);
                //         return false;
                //         }
                //     if(enddate==''){
                //         alert(lang.Please_enter_To_Date);
                //         return false;
                //         }
                //     orderlist.ajax.reload(null, false);
                // });
                // $('#filterordlistrst').click(function() {
                //     var startdate=$("#from_date").val('');
                //     var enddate=$("#to_date").val('');
                //     orderlist.ajax.reload(null, false);
                // });
                $('#searchInput').keyup(function(){
                    orderlist.search($(this).val()).draw() ;
                })
                
    });
    // var element=document.getElementById("tallorder");
    // window.prevsltab = $(element);



</script>


