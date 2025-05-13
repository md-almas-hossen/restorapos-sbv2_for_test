<div class="modal fade " id="allvaucherModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true" >
    <div class="modal-dialog custom-modal-dialog modal-lg">
        <div class="modal-content " >
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="allAppointModalLabel">Vaucher Detail</h5>
                <button type="button" class="close rmpdf" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body" id="all_vaucher_view" style="padding:0;"> 
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" name="btnPrint" id="btnPrint" onclick="printVaucher('all_vaucher_view');"><i class='fa fa-print'></i>  Print </button>
                <a id="pdfDownload"  href="" target="_blank" title="download pdf">
                    <button  class="btn btn-success btn-md" name="btnPdf" id="btnPdf" ><i class="fa-file-pdf-o"></i> PDF</button>
             </a>
                <button type="button" class="btn btn-danger rmpdf" data-dismiss="modal"><i class='fa fa-cross'></i> close</button>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('application/modules/accounts/assets/vouchers/voucher-details.js'); ?>" type="text/javascript"></script>