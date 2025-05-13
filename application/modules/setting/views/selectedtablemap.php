 <?php if (!empty($tablesetting)) { 
  $sl = 0;
  foreach ($tablesetting as $table) {
								$sl++; 
								if(empty($table->iconpos)){
									$style='style="position:absolute"';
								}else{
								$style='style="'.$table->iconpos.'"';	
								}
								
								if($table->table_icon==1){ 
								$icon="table-round";
								}
								if($table->table_icon==2){ 
								$icon="";
								}
								if($table->table_icon==3){ 
								$icon="table-lg";
								}
								if($table->table_icon==4){ 
								$icon="table-rotate";
								}
								?>
                        
                        <div class="restora-table boxpad <?php echo $icon;?> draggable" id="<?php echo $table->tableid;?>" <?php echo $style;?> data-itemid='<?php echo $table->tableid;?>'>
                        <input name="sortid[]" type="hidden" value="<?php echo $table->tableid;?>" />
                                          <div class="table-inner">
                                          	<div class="table-number">
                                              Table : <?php echo $table->tablename; ?>
                                            </div>
                                            <div class="table-user">
                                              <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                              <span class="table-user__count"> <?php echo $table->person_capicity; ?></span>
                                            </div>
                                          </div>
                                        </div>
                        <?php } }?>
                        
