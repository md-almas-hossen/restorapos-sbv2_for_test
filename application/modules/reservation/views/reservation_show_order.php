      
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
                  <?php echo date("l d M Y", strtotime($reserveday)); //$date->format('l jS \o\f F Y h:i:s A'), "\n"; ?>
                </h4>
              </div>
              <div class="modal-body">
                <div class="table-responsive table_list">
                  <table id="dataTableExample1" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th><?php echo display('customer');?></th>
                        <th><?php echo display('phone');?></th>
                        <th><?php echo display('person');?></th>
                        <th><?php echo display('s_time'); ?></th>
                        <th><?php echo display('e_time'); ?></th>
                        <th><?php echo display('date')?></th>
                        <th><?php echo display('table')?></th>
                        <th><?php echo display('status')?></th>
                      </tr>
                    </thead>
                    <tbody>
       
                    <?php if(!empty($orderinfo)){
                     foreach($orderinfo as $order){
                      //print_r($order);
                        if($order->status==1){
                          $status="Pending";
                        }
                        if($order->status==2){
                          $status="Booked";
                        }
                    ?>
                      <tr>
                        <td><?php echo $order->customer_name;?></td>
                        <td><?php echo $order->customer_phone;?></td>
                        <td><?php echo $order->person_capicity;?></td>
                        <td><?php echo $order->formtime;?></td>
                        <td><?php echo $order->totime;?></td>
                        <td><?php echo $order->reserveday;?></td>
                        <td><?php echo $order->tablename;?></td>
                        <td><?php echo $status;?></td>
                      </tr>
                    <?php  } }?>
                      
                    </tbody>
                  </table>
                </div>
              </div>