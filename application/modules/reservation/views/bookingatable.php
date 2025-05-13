<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-bd">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('book_table'); ?></h4>
                </div>
            </div>

            <div class="panel-body">
                <div class="table_content table_contentpost">
                    <div class="table_content_booking">
                        <div class="table_booking">
                            <div class="table_tables">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="sdate"><?php echo display('date'); ?></label>
                                            <div class="input__holder3">
                                                <input id="sdate" name="date" type="text"
                                                    class="form-control datepicker"
                                                    placeholder="<?php echo display('date'); ?>" readonly="readonly">
                                                <input id="inresp" type="hidden" value="1">
                                                <input id="checkdatetime" type="hidden" value="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="sltime"><?php echo display('time'); ?></label>
                                            <div class="input__holder3">
                                                <input id="sltime" name="time" type="text"
                                                    class="form-control timepicker"
                                                    placeholder="<?php echo display('time'); ?>" readonly="readonly">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="people"><?php echo display('people'); ?></label>
                                            <div class="input__holder3">
                                                <input id="people" name="people" type="text" class="form-control"
                                                    placeholder="<?php echo display('people'); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="date">&nbsp;</label>
                                            <div class="input__holder3">
                                                <input name="checkurl" id="checkurl" type="hidden"
                                                    value="<?php echo base_url("reservation/reservation/checkavailablity") ?>" />
                                                <input type="button" class="btn btn-success"
                                                    onclick="editreserveinfo(0)"
                                                    value="<?php echo display('check_availablity') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="availabletable">


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('Reservation'); ?></strong>
            </div>
            <div class="modal-body editinfo">

            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>