<input name="sldate" id="sldate" type="hidden" value="<?php echo $newdate; ?>" />
<input name="sltime" id="sltime" type="hidden" value="<?php echo $gettime; ?>" />
<input name="people" id="people" type="hidden" value="<?php echo $nopeople; ?>" />
<?php $color = "#004040";
if (!empty($tableinfo)) {
    foreach ($tableinfo as $table) {
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
        <input name="url" type="hidden" id="url_<?php echo $table->tableid; ?>" value="<?php echo base_url("reservation/reservation/reservationform") ?>" />
        <div class="col-sm-4">
            <div id="seatsA" class="table_tables_item checkavail-block" style="cursor: pointer;">
                <!-- <div class="table_tables_item_content" onclick="editreserveinfo('<?php echo $table->tableid; ?>')"> -->
                    <!-- <span class="table_tables_item_name"><?php echo $table->tablename; ?></span> -->
                    <!-- <span class="table_tables_item_seats"> -->
                      <!-- <div class="table-inner"> -->
                        <div class="restora-table <?php echo $icon;?>"  onclick="editreserveinfo('<?php echo $table->tableid; ?>')">
                        <div class="table-user">
                            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                            <!-- <img src="<?php echo base_url(!empty($table->table_icon) ? $table->table_icon : 'assets/img/icons/table/default.jpg'); ?>" class="img-thumbnail" /> -->
                            <span class="table-user__count"> <?php echo $table->person_capicity; ?></span>
                        </div>
                        </div>
                      
                      <!-- </div> -->
                    <!-- </span> -->

                    
                <!-- </div> -->
            </div>
        </div>
<?php
    }
} else {
    echo '<div class="col-sm-4"><h2>No Table found!!!</h2></div>';
}
?>

