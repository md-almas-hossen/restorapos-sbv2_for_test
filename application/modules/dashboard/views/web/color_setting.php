
<div class="row"> 
  <!--  table area -->
  <div class="col-sm-12">
    <div class="panel panel-bd lobidrag">
      <div class="panel-heading">
        <div class="panel-title">
          <h4><?php echo display('color_setting');?></h4>
        </div>
      </div>

       <div class="panel-body">
            <?php 			
            echo form_open_multipart('dashboard/web_setting/update_color_setting','class="form-inner"') ?>
            <?php echo form_hidden('id', isset($color_setting->id) ? @$color_setting->id:null) ?>
        
            <div class="form-group row">

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('text_color');?></label>
                        <input type="color" id="text_color"  pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="<?php echo isset($color_setting->web_text_color) ? @$color_setting->web_text_color:''?>" class="form-control"> 
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('color_hex_code');?><span class="">*</span></label>
                        <input type="text" name="web_text_color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="<?php echo isset($color_setting->web_text_color) ? @$color_setting->web_text_color:''?>" id="text_color_hexcolor" class="form-control">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('button_color');?></label>
                        <input type="color" id="button_color"  pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="<?php echo isset($color_setting->web_button_color) ? @$color_setting->web_button_color:''?>" class="form-control"> 
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('color_hex_code');?><span class="">*</span></label>
                        <input type="text" name="web_button_color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="<?php echo isset($color_setting->web_button_color) ? @$color_setting->web_button_color:''?>" id="button_color_hexcolor" class="form-control">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('header_bg');?></label>
                        <input type="color" id="header_bg"  pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="<?php echo isset($color_setting->web_header_bg_color) ? @$color_setting->web_header_bg_color:''?>" class="form-control"> 
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('color_hex_code');?><span class="">*</span></label>
                        <input type="text" name="web_header_bg_color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="<?php echo isset($color_setting->web_header_bg_color) ? @$color_setting->web_header_bg_color:''?>" id="header_bg_hexcolor" class="form-control">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('footer_bg');?></label>
                        <input type="color" id="footer_bg"  pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="<?php echo isset($color_setting->web_footer_bg_color) ? @$color_setting->web_footer_bg_color:''?>" class="form-control"> 
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-600"><?php echo display('color_hex_code');?><span class="">*</span></label>
                        <input type="text" name="web_footer_bg_color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="<?php echo isset($color_setting->web_footer_bg_color) ? @$color_setting->web_footer_bg_color:''?>" id="footer_bg_hexcolor" class="form-control">
                    </div>
                </div>

            </div>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary pull-right addBtn"><?php echo display('save') ?></button>
            </div>
            <?php echo form_close() ?>
        </div>
    </div>
  </div>
</div>

<script>

    $('#text_color').on('change', function() {
      $('#text_color_hexcolor').val(this.value);
    });
    $('#button_color').on('change', function() {
      $('#button_color_hexcolor').val(this.value);
    });
    $('#header_bg').on('change', function() {
      $('#header_bg_hexcolor').val(this.value);
    });
    $('#footer_bg').on('change', function() {
      $('#footer_bg_hexcolor').val(this.value);
    });

</script>
