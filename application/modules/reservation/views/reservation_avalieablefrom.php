      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <svg xmlns="http://www.w3.org/2000/svg" width="43" height="43" viewBox="0 0 43 43" fill="none">
            <path d="M25.547 25.5471L17.4527 17.4528M17.4526 25.5471L25.547 17.4528" stroke="#FA5B14" stroke-width="3" stroke-linecap="round" />
            <path d="M41 21.5C41 12.3077 41 7.71136 38.1442 4.85577C35.2886 2 30.6923 2 21.5 2C12.3076 2 7.71141 2 4.85572 4.85577C2 7.71136 2 12.3077 2 21.5C2 30.6924 2 35.2886 4.85572 38.1443C7.71141 41 12.3076 41 21.5 41C30.6923 41 35.2886 41 38.1442 38.1443C40.0431 36.2455 40.6794 33.5772 40.8926 29.3" stroke="#FA5B14" stroke-width="3" stroke-linecap="round" />
          </svg>
        </button>
        <h4 class="modal-title" id="myModalLabel">
          <?php //echo strtotime($delivery_date);
          ?>
          <!-- Friday (13 Aug 2023) -->
          Book a Table
        </h4>
      </div>
      <div class="modal-body">

        <div class="row">
          <!--  table area -->
          <div class="col-sm-12">

            <div class="panel panel-default thumbnail">

              <div class="panel-body">
                <div class="table_content table_contentpost">
                  <!-- <div class="table_content_booking"> <span class="table_booking_header">Book a Table</span> -->
                    <!-- <div class="table_booking"> -->
                      <!-- <div class="table_tables"> -->
                        <div class="row">
                          <!-- <div class="col-sm-3"> -->
                            <!-- <div class="form-group"> -->
                              <!-- <label for="date">Date</label> -->
                              <!-- <div class="input__holder3"> -->
                                <!-- <input id="date" name="date" type="text" class="form-control datepicker" placeholder="Date" readonly="readonly"> -->
                                <input id="sdate" name="date" type="hidden" class="form-control">
                                <input id="inresp" type="hidden" value="0">
                                <input id="checkdatetime" type="hidden" value="0">
                              <!-- </div> -->
                            <!-- </div> -->
                          <!-- </div> -->
                          <div class="col-sm-3">
                            <div class="form-group">
                              <label for="time">Time</label>
                              <div class="input__holder3">
                                <input id="sltime" name="time" type="text" class="form-control timepicker" placeholder="time" readonly="readonly">
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="form-group">
                              <label for="people">People</label>
                              <div class="input__holder3">
                                <input id="people" name="people" type="text" class="form-control" placeholder="No. of people">
                              </div>
                            </div>
                          </div>

                          <div class="col-sm-3">
                            <div class="form-group">
                              <label for="date">&nbsp;</label>
                              <div class="input__holder3">
                                <input name="checkurl" id="checkurl" type="hidden" value="<?php echo base_url("reservation/reservation/checkavailablity") ?>" />
                                <input type="button" class="btn btn-success" onclick="editreserveinfo(0)" value="<?php echo display('check_availablity') ?>">
                              </div>
                            </div>
                          </div>
                        </div>
                        <div id="showsubtext">

                        </div>
                        <div class="row" id="availabletable">


                        </div>
                        <input type="hidden" value="calender" id="calender">
                      <!-- </div> -->
                    <!-- </div> -->
                  <!-- </div> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>