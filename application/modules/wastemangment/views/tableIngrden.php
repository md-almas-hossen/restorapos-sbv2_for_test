 <table class="table table-bordered table-striped table-hover" id="respritbl">
                    <thead>
                        <tr>
                            <th><?php echo display('checked_by') ?></th>
                            <th><?php echo display('used_items');?></th>
                            <th><?php echo display('qnty');?></th>
                            <th><?php echo display('lost_price');?></th>
                            <th><?php echo display('note');?></th>
                            <th><?php echo display('date') ?></th> 
                           
                        </tr>
                    </thead>
                    <tbody>
                       <?php 
                       $totalprice =0;
                       if(!empty($details)){
                        $sl=1;
                        
                        foreach ($details as $detail) {
                          $totalprice = $totalprice+$detail->l_price;
                          ?>
                          <tr>
                          <td><?php echo $detail->fullname; ?></td>
                          <td><?php echo $detail->ingredient_name; ?></td>
                          <td><?php echo $detail->qnty; ?></td>
                          <td class="text-right"><?php echo $detail->l_price; ?></td>
                           <td><?php echo $detail->note; ?></td>
                           <td><?php echo $detail->created_at; ?></td>
                           </tr>
                          <?php 
                          $sl++;
                        }


                       }?>
                    </tbody>
                      <tfoot>
                    <tr>
                      <td colspan="3" align="right" style="text-align:right;font-size:14px !Important">&nbsp; <b><?php echo display('total').' '.display('lost_price'); ?> </b></td>
                      <td style="text-align: right;"><b> <?php echo $totalprice;?></b></td>
                    </tr>
                  </tfoot>
                </table>  