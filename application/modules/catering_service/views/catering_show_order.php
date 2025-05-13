      
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg xmlns="http://www.w3.org/2000/svg" width="43" height="43" viewBox="0 0 43 43" fill="none">
                    <path d="M25.547 25.5471L17.4527 17.4528M17.4526 25.5471L25.547 17.4528" stroke="#FA5B14"
                      stroke-width="3" stroke-linecap="round" />
                    <path
                      d="M41 21.5C41 12.3077 41 7.71136 38.1442 4.85577C35.2886 2 30.6923 2 21.5 2C12.3076 2 7.71141 2 4.85572 4.85577C2 7.71136 2 12.3077 2 21.5C2 30.6924 2 35.2886 4.85572 38.1443C7.71141 41 12.3076 41 21.5 41C30.6923 41 35.2886 41 38.1442 38.1443C40.0431 36.2455 40.6794 33.5772 40.8926 29.3"
                      stroke="#FA5B14" stroke-width="3" stroke-linecap="round" />
                  </svg>
                </button>
                <h4 class="modal-title" id="myModalLabel">

                <?php //echo strtotime($delivery_date);?>
                  <!-- Friday (13 Aug 2023) -->
                  <?php echo date("l d M Y", strtotime($delivery_date)); //$date->format('l jS \o\f F Y h:i:s A'), "\n"; ?>
                </h4>
              </div>
              <div class="modal-body">
                <div class="table-responsive table_list">
                  <table id="dataTableExample1" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th><?php echo display('customer');?></th>
                        <th><?php echo display('phone');?></th>
                        <th><?php echo display('address');?></th>
                        <th><?php echo display('delv_date')?></th>
                        <th><?php echo display('person');?></th>
                        <th><?php echo display('total');?></th>
                        <th><?php echo display('status');?></th>
                        <th><?php echo display('action');?></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                        //  d($orderinfo);
                        ?>
                    <?php if(!empty($orderinfo)){

                        foreach($orderinfo as $rowdata){

                          $no++;
                          $row = array();
                          if ($rowdata->order_status == 1) {
                            $status = '<span class="badge badge_paid" style="color: #fff!important;">Unpaid</span>';
                          }
                          if ($rowdata->order_status == 2) {
                            $status = '<span class="badge badge_paid" style="color: #fff!important;">Processing</span>';
                          }
                          if ($rowdata->order_status == 3) {
                            $status = '<span class="badge badge_paid" style="color: #fff!important;">Partially Paid</span>';
                          }
                          if ($rowdata->order_status == 4) {
                    
                            if($rowdata->is_duepayment ==1){
                    
                              $status = '<span class="badge badge_paid" style="color: #fff!important;">Due</span>';
                    
                            }else{
                              
                              $status = '<span class="badge badge_paid" style="color: #fff!important;">Paid</span>';
                            }
                          }
                          if ($rowdata->order_status == 5) {
                            $status = '<span class="badge badge_paid" style="color: #fff!important;">Cancel</span>';
                          }
                          $newDate = date("d-M-Y", strtotime($rowdata->order_date));
                          $update = '';
                          $posprint = '';
                          $details = '';
                          $paymentbtn = '';
                          $cancelbtn = '';
                          $acptreject = '';
                          $margeord = '';
                          $printmarge = '';
                          $duePayment = '';
                          $split = '';
                          $pickupthirdparty = '';
                    
                          $ptype = $this->db->select("bill_status")->from('catering_package_bill')->where('order_id', $rowdata->order_id)->get()->row();
                          $checkbox = "";
                          $kot = "";
                        
                    
                    
                    
                    
                      
                          if ($checkdevice == 1) {
                            $kot = '<a href="http://www.abc.com/token/' . $rowdata->order_id . '"  class="btn btn-xs btn-success btn-sm mr-1" style="margin-right: 5px;line-height: 20px;color: #fff;background: rgb(18, 121, 196);" data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT"><i class="fa fa-print"></i></a>';
                          } else {
                            $kot = '<a href="javascript:;" onclick="catering_postokenprint(' . $rowdata->order_id . ')" style="margin-right: 5px;line-height: 20px;color: #fff;background: rgb(18, 121, 196);" class="btn btn-xs btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="" data-original-title="KOT"><i class="fa fa-print"></i></a>';
                          }
                        
                    
                          if ($this->permission->method('ordermanage', 'read')->access()) :
                            $details = '<a href="javascript:;" onclick="cateringdetailspop(' . $rowdata->order_id . ')"  class="btn btn-xs  btn-sm mr-1" data-toggle="tooltip" data-placement="left" title="Details">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              width="29"
                              height="26"
                              viewBox="0 0 29 26"
                              fill="none"
                              >
                              <path
                                d="M7.61432 20.1869H4.80142C3.2479 20.1869 1.98853 18.8835 1.98853 17.2756V11.4529C1.98853 9.04107 3.8776 7.08588 6.20787 7.08588H7.61432M7.61432 20.1869V15.8199H21.6788V20.1869M7.61432 20.1869V21.6426C7.61432 23.2506 8.8737 24.554 10.4272 24.554H18.8659C20.4195 24.554 21.6788 23.2506 21.6788 21.6426V20.1869M7.61432 7.08588V4.17453C7.61432 2.56664 8.8737 1.26318 10.4272 1.26318H18.8659C20.4195 1.26318 21.6788 2.56664 21.6788 4.17453V7.08588M7.61432 7.08588H21.6788M21.6788 20.1869H24.4917C26.0453 20.1869 27.3046 18.8835 27.3046 17.2756V11.4529C27.3046 9.04107 25.4156 7.08588 23.0853 7.08588H21.6788M18.8659 11.4529H21.6788"
                                stroke="#0EA4C5"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                              />
                            </svg> </a>&nbsp;';
                          endif;
                    
                    
                    
                    
                    
                    
                          // due payment
                          if ($rowdata->order_status == 3){
                            $duePayment='
                            <button type="button" class="btn btn-xs btn-primary btn-sm mr-1" data-toggle="modal" data-target="#exampleModal'.$rowdata->order_id.'" title="due payment"><i class="fa fa-meetup"></i></button>
                    
                            <div class="modal fade" id="exampleModal'.$rowdata->order_id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel'.$rowdata->order_id.'" aria-hidden="true">
                    
                              <div class="modal-dialog" role="document">
                    
                                <div class="modal-content">
                    
                                  <div class="modal-header" style="background:#019868">
                                    <h5 style="text-align: center; color: #fff; position: absolute; left: 43%;" class="modal-title" id="exampleModalLabel'.$rowdata->order_id.'">Make Due Clear</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                    
                    
                                  
                    
                    
                                  
                                      <form action="' . base_url('catering_service/cateringservice/duePayment') . '" method="post">
                                    ' . form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) . '
                                    <div class="modal-body">
                                    
                                    <input type="hidden" name="order_id" value="'.$rowdata->order_id.'">
                    
                                    <p style="text-align:center">Total Due Amount: 
                                    <label id="due">'.($rowdata->totalamount - $rowdata->customerpaid).' </label> 
                                    
                                      <br>
                    
                                    Pay Here: <br>
                                    <input type="hidden" name="due" value="'.($rowdata->totalamount - $rowdata->customerpaid).'">
                                    <input class="form-control" name="payment" id="paid" style="border: 1px solid #019868; border-radius: 3px; margin-top: 9px;" onkeyup="calculateResult()"><br>
                                    <input id="change" style="border: none;
                                    width: 100%;
                                    text-align: center;
                                    font-size: 15px;
                                    color: #019868;" readonly>
                    
                                      </div>
                    
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm btn-success">Make Payment</button>
                                  </div>
                                  </form>
                    
                                  <script>
                                    function calculateResult() {
                                      var due = parseFloat(document.getElementById("due").textContent);
                                      var paid = parseFloat(document.getElementById("paid").value);
                    
                                      if(paid > due){
                                        var change = paid - due;
                                        document.getElementById("change").value = "Change Amount: "+change;
                                      }else{
                                        var change = due - paid;
                                          document.getElementById("change").value = "Remaining Due: "+change;
                                      }
                                    }
                                  </script>
                                </div>
                                
                              </div>
                    
                            </div>';
                          }
                          // due payment
                    
                    
                    
                          
                          if ($this->permission->method('ordermanage', 'read')->access()) :
                    
                    
                    
                            
                    
                    
                    
                            if ($rowdata->order_status == 1 || $rowdata->order_status == 2 || $rowdata->order_status == 3) {
                              $update = '<a href="javascript:void(0)" onclick="editOrder(' . $rowdata->order_id . ')" class="btn bg-transparent p-0">
                                        <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="27"
                                height="26"
                                viewBox="0 0 27 26"
                                fill="none"
                                >
                                <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M24.9603 1.35327C23.4311 -0.117099 20.9519 -0.117111 19.4227 1.35327L17.2301 3.46148L6.91092 13.3838C6.74364 13.5447 6.62498 13.7462 6.5676 13.9669L5.26236 18.987C5.15116 19.4147 5.28149 19.8672 5.60568 20.1788C5.92987 20.4906 6.4004 20.6159 6.8452 20.509L12.0661 19.254C12.2957 19.1987 12.5052 19.0847 12.6725 18.9238L22.9167 9.07367L25.1842 6.89328C26.7135 5.42291 26.7135 3.03896 25.1842 1.5686L24.9603 1.35327ZM21.2685 3.12816C21.7782 2.63804 22.6047 2.63804 23.1144 3.12816L23.3384 3.34349C23.8481 3.83362 23.8481 4.62827 23.3384 5.11839L22.0116 6.39416L19.9814 4.36578L21.2685 3.12816ZM18.1352 6.14099L20.1653 8.16938L11.0823 16.9031L8.3225 17.5666L9.01244 14.9129L18.1352 6.14099ZM2.61292 7.99606C2.61292 7.30292 3.1973 6.74102 3.91815 6.74102H10.4443C11.1652 6.74102 11.7496 6.17912 11.7496 5.48598C11.7496 4.79284 11.1652 4.23094 10.4443 4.23094H3.91815C1.75557 4.23094 0.00244141 5.91664 0.00244141 7.99606V21.8014C0.00244141 23.8809 1.75557 25.5666 3.91815 25.5666H18.2758C20.4384 25.5666 22.1915 23.8809 22.1915 21.8014V15.5262C22.1915 14.8332 21.6071 14.2712 20.8862 14.2712C20.1653 14.2712 19.581 14.8332 19.581 15.5262V21.8014C19.581 22.4946 18.9966 23.0565 18.2758 23.0565H3.91815C3.1973 23.0565 2.61292 22.4946 2.61292 21.8014V7.99606Z"
                                  fill="#0EB17D"
                                />
                                        </svg>
                                    </a>';
                              $cancelbtn = '<a href="' . base_url('catering_service/cateringservice/deleteCateringOrder/'.$rowdata->order_id) . '" class="btn bg-transparent p-0 aceptorcancels">
                              
                                <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  width="24"
                                  height="29"
                                  viewBox="0 0 24 29"
                                  fill="none"
                                  >
                                  <path
                                    d="M9.53223 14.3894V21.4779"
                                    stroke="#EA0000"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                  />
                                  <path
                                    d="M15.6082 14.3894V21.4779"
                                    stroke="#EA0000"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                  />
                                  <path
                                    d="M1.43115 7.30103H22.6967"
                                    stroke="#EA0000"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                  />
                                  <path
                                    d="M4.46899 12.3643V23.4113C4.46899 25.6992 6.16916 27.5539 8.26641 27.5539H15.8612C17.9585 27.5539 19.6586 25.6992 19.6586 23.4113V12.3643"
                                    stroke="#EA0000"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                  />
                                  <path
                                    d="M8.51953 4.7694C8.51953 3.37123 9.57741 2.23779 10.8824 2.23779H13.2452C14.5502 2.23779 15.608 3.37123 15.608 4.7694V7.30101H8.51953V4.7694Z"
                                    stroke="#EA0000"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                  />
                                </svg>
                    
                              </a>';
                            }
                    
                            
                    
                            if ($checkdevice == 1) {
                              
                              $posprint = '<a href=""http://www.abc.com/invoice/' . $paystatus . '/' . $rowdata->order_id . '" target="_blank"  class="btn bg-transparent p-0">
                              <img style="width: 22px; height: 22px; margin-left: 5px;" src="' . base_url('application/modules/catering_service/assets/images/invoicedetails/point-of-sale.png') . '" alt="" />
                                </a>';
                            } else {
                              $posprint = '<button onclick="printPosinvoice(' . $rowdata->order_id . ')" class="btn bg-transparent p-0">
                              
                              <img style="width: 22px; height: 22px; margin-left: 5px;" src="' . base_url('application/modules/catering_service/assets/images/invoicedetails/point-of-sale.png') . '" alt="" />
                            </button>';
                    
                          
                            }
                          
                          endif;
                    
                    
                          // here bill payment button
                        
                            if ( ($ptype->bill_status == 0  && $rowdata->orderacceptreject != 0 && !(float)$rowdata->customerpaid > 0) )  {
                    
                              $margeord = '<button onclick="createMargeorder(' . $rowdata->order_id . ',1)" id="hidecombtn_' . $rowdata->order_id . '" class="btn bg-transparent p-0">
                                        <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="28"
                                height="24"
                                viewBox="0 0 28 24"
                                fill="none"
                                >
                                <path
                                  d="M26.8856 9.96826H22.9797V1.23689C22.9796 1.06668 22.9343 0.899511 22.8485 0.752167C22.7628 0.604824 22.6395 0.482496 22.4911 0.397465C22.3426 0.312434 22.1743 0.267694 22.003 0.267738C21.8317 0.267782 21.6634 0.312607 21.515 0.397714L17.1209 2.93466L12.2385 0.378311C12.0966 0.302733 11.9381 0.263184 11.7771 0.263184C11.6161 0.263184 11.4576 0.302733 11.3157 0.378311L6.43332 3.02682L1.98547 0.407415C1.83702 0.322267 1.66864 0.27744 1.49723 0.27744C1.32582 0.27744 1.15743 0.322267 1.00899 0.407415C0.862117 0.49165 0.739856 0.612436 0.654246 0.757881C0.568636 0.903326 0.522628 1.06842 0.520752 1.23689V20.1549C0.520752 21.0554 0.880827 21.9191 1.52176 22.5559C2.1627 23.1926 3.032 23.5504 3.93842 23.5504H23.9562C24.0131 23.5552 24.0702 23.5552 24.1271 23.5504C24.2296 23.5504 24.337 23.5504 24.4444 23.5504C25.3509 23.5504 26.2202 23.1926 26.8611 22.5559C27.502 21.9191 27.8621 21.0554 27.8621 20.1549V10.9384C27.8621 10.6811 27.7592 10.4343 27.5761 10.2524C27.393 10.0705 27.1446 9.96826 26.8856 9.96826ZM3.93842 21.6101C3.54996 21.6101 3.1774 21.4568 2.90271 21.1838C2.62802 20.9109 2.47371 20.5408 2.47371 20.1549V2.94921L5.89138 4.97683C6.03767 5.06569 6.20493 5.11472 6.37634 5.11898C6.54776 5.12324 6.71728 5.08257 6.86785 5.00108L11.7502 2.33802L16.657 4.89922C16.8013 4.9782 16.9634 5.01962 17.1282 5.01962C17.2929 5.01962 17.455 4.9782 17.5993 4.89922L21.017 2.95891V20.1549C21.0157 20.6586 21.1292 21.1561 21.349 21.6101H3.93842ZM25.9092 20.1549C25.9092 20.5408 25.7548 20.9109 25.4802 21.1838C25.2055 21.4568 24.8329 21.6101 24.4444 21.6101C24.056 21.6101 23.6834 21.4568 23.4087 21.1838C23.134 20.9109 22.9797 20.5408 22.9797 20.1549V11.9086H25.9092V20.1549Z"
                                  fill="#1279C4"
                                />
                                <path
                                  d="M17.7357 9.37695H6.59663C6.32806 9.37695 6.07049 9.48364 5.88058 9.67355C5.69067 9.86346 5.58398 10.121 5.58398 10.3896C5.58398 10.6582 5.69067 10.9157 5.88058 11.1056C6.07049 11.2955 6.32806 11.4022 6.59663 11.4022H17.7357C18.0043 11.4022 18.2618 11.2955 18.4517 11.1056C18.6417 10.9157 18.7483 10.6582 18.7483 10.3896C18.7483 10.121 18.6417 9.86346 18.4517 9.67355C18.2618 9.48364 18.0043 9.37695 17.7357 9.37695Z"
                                  fill="#1279C4"
                                />
                                <path
                                  d="M17.7357 15.4529H6.59663C6.32806 15.4529 6.07049 15.5596 5.88058 15.7495C5.69067 15.9394 5.58398 16.197 5.58398 16.4655C5.58398 16.7341 5.69067 16.9917 5.88058 17.1816C6.07049 17.3715 6.32806 17.4782 6.59663 17.4782H17.7357C18.0043 17.4782 18.2618 17.3715 18.4517 17.1816C18.6417 16.9917 18.7483 16.7341 18.7483 16.4655C18.7483 16.197 18.6417 15.9394 18.4517 15.7495C18.2618 15.5596 18.0043 15.4529 17.7357 15.4529Z"
                                  fill="#1279C4"
                                />
                                        </svg>
                                      </button>';
                              
                            }
                          
                    
                        ?>
                      <tr>
                        <td><?php echo $rowdata->customer_name;?></td>
                        <td><?php echo $rowdata->customer_phone;?></td>
                        <td><?php echo $rowdata->delivaryaddress;?></td>
                        <td><?php echo $rowdata->shipping_date;?></td>
                        <td><?php echo $rowdata->person;?></td>
                        <td><?php echo $rowdata->totalamount;?></td>
                        <td>
                          <span class="badge badge_paid mt-10" style="color: #fff!important;"><?php echo $status;?></span>
                        </td>
                        <td><?php echo $kot . $margeord. $posprint . $details . $cancelbtn;?></td>
                      </tr>
                    <?php  } }?>
                      
                    </tbody>
                  </table>
                </div>
              </div>



<script>

  function printPosinvoice(id){
        var csrf = $('#csrfhashresarvation').val();
        var url = basicinfo.baseurl+'catering_service/cateringservice/posorderinvoice/'+id;
          $.ajax({
              type: "GET",
              url: url,
              data:{csrf_test_name:csrf},
              success: function(data) {
                  if(basicinfo.printtype!=1){
                        printRawHtml(data);
                }
              }
        });
  }


  function printRawHtml(view) {
    printJS({
      printable: view,
      type: 'raw-html',
      
    });
  }
  // kot print start
  function catering_postokenprint(id) {
      var csrf = $('#csrfhashresarvation').val();
      var url = basicinfo.baseurl +'catering_service/cateringservice/catering_paidtoken' + '/' + id + '/';
      $.ajax({
          type: "POST",
          url: url,
          data: { csrf_test_name: csrf },
          success: function(data) {
     
			      var dtype=checkdevicetype();
              if(dtype==1){
                    var url2 = "http://www.abc.com/token/"+id;
                    window.open(url2, "_blank");
                }else{
                    printRawHtml(data);
                }
              //printRawHtml(data);
          }
      });
  }

  function checkdevicetype(){
      if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
    return 1;
    }else{
     return 0
    }
  }
</script>