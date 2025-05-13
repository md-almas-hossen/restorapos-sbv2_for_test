<div class="row">

    <div class="col-sm-12 col-md-12">
      <div class="panel panel-bd">
        <div class="panel-heading">
          <div class="panel-title">
            <h4>
              <?php echo display('financial_year_list'); ?>
            </h4>
          </div>
        </div>
        <div class="panel-body">
          <table width="100%" id="exdatatable"
            class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="5%"><?php echo display('sl_no') ?></th>
                <th><?php echo display('title') ?></th>
                <th><?php echo display('from_date') ?></th>
                <th><?php echo display('to_date') ?></th>
                <th><?php echo display('status') ?></th>
                <th width="15%"><?php echo display('action') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($yearlist)) {
                $count_year=count($yearlist);
              ?>
                <?php $sl = 1; ?>
                <?php foreach ($yearlist as $list) { ?>
                  <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                    <td><?php echo $sl; ?></td>
                    <td id="title_<?php echo html_escape($list->fiyear_id); ?>"><?php echo html_escape($list->title); ?></td>
                    <td id="start_<?php echo html_escape($list->fiyear_id); ?>"><?php echo html_escape($list->start_date); ?></td>
                    <td id="end_<?php echo html_escape($list->fiyear_id); ?>"><?php echo html_escape($list->end_date); ?></td>
                    <td id="status_<?php echo html_escape($list->fiyear_id); ?>"><?php if ($list->is_active == 2) {
                                                                                    echo "Ended";
                                                                                  } else if ($list->is_active == 1) {
                                                                                    echo display("active");
                                                                                  } else {
                                                                                    echo display("inactive");
                                                                                  } ?></td>
                    
                      <td class="center">

                        <?php if ($count_year==1 && $list->is_active == 1) { ?>
                        <a href="javascript:void(0)"
                          class="btn btn-info btn-sm financial_year_modal" data-year_id="<?php echo $list->fiyear_id;?>" data-year_title="<?php echo $list->title;?>" data-modal_type="1" data-toggle="tooltip" data-placement="left"
                          title="Update"><i class="ti-pencil-alt text-white"
                            aria-hidden="true"></i></a>
                        <?php } ?>
                        <?php if ($list->is_active == 1){ ?>
                      <a href="javascript:void(0)" class="btn btn-sm btn-success financial_year_modal" data-year_id="<?php echo $list->fiyear_id;?>" data-year_title="<?php echo $list->title;?>" data-modal_type="2"><i class="fa fa-book"></i> Open New Financial Year</a></td>
                      <?php } ?>
                      </td>
                     
                  </tr>
                  <?php $sl++; ?>
                <?php } ?>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

</div>
<div class="modal fade" id="financialyearModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true" >
    <div class="modal-dialog custom-modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="ModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="financial_year_view"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class='fa fa-cross'></i> Close</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('application/modules/accounts/assets/financialYear/financial_year.js?v=4'); ?>" type="text/javascript"></script>