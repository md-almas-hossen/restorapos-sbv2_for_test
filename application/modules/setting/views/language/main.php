<div id="add0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content customer-list">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('bulk_upload');?></strong>
            </div>
            <div class="modal-body">

                <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('success') == TRUE): ?>
                <div class="form-control alert alert-success"><?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php endif; ?>
                <h3><?php echo display('you_can_export_test_csv_file_example');?><br /><a class="btn btn-success btn-md"
                        href="<?php echo base_url() ?>setting/language/downloadformat"><i class="fa fa-download"
                            aria-hidden="true"></i><?php echo display('Download_CSV_Format');?></a></h3>
                <h4>phase,English,Spanish,Turkish</h4>
                <h4>ad,Add,Agregar,Ekle</h4>
                <h2><?php echo display('bulk_upload')?></h2>
                <?php echo form_open_multipart('setting/language/bulklanupload',array('class' => 'form-vertical', 'id' => 'validate','name' => 'insert_attendance'))?>
                <input type="file" name="userfile" id="userfile"><br><br>
                <input type="submit" name="submit" value="<?php echo display('upload_csv');?>" class="btn btn-success">
                <?php echo form_close()?>


            </div>

        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd ">
            <div class="panel-heading">
                <div class="btn-group">
                    <a class="btn btn-success" href="<?php echo base_url("setting/language/phrase") ?>"> <i
                            class="fa fa-plus"></i> <?php echo display('add_phrase');?></a>
                </div>
                <div class="btn-group pull-right">
                    <?php if($this->permission->method('setting','update')->access()): ?>
                    <a data-target="#add0" data-toggle="modal" class="btn btn-success btn-md"><i
                            class="fa fa-plus-circle" aria-hidden="true"></i>
                        <?php echo display('bulk_upload')?></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="panel-body">

                <!-- language -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td colspan="3">
                                <?php echo  form_open('setting/language/addlanguage', ' class="form-inline" ') ?>
                                <div class="form-group">
                                    <label class="sr-only" for="addLanguage">
                                        <?php echo display('language_name');?></label>
                                    <input name="language" type="text" class="form-control" id="addLanguage"
                                        placeholder=" <?php echo display('language_name');?>">
                                </div>

                                <button type="submit" class="btn btn-success"><?php echo display('save');?></button>
                                <?php echo  form_close(); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fa fa-th-list"></i></th>
                            <th><?php echo display('language');?></th>
                            <th><i class="fa fa-cogs"></i></th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php if (!empty($languages)) {?>
                        <?php $sl = 1 ?>
                        <?php foreach ($languages as $key => $language) {?>
                        <tr>
                            <td><?php echo  $sl++ ?></td>
                            <td><?php echo  $language ?></td>
                            <td><a href="<?php echo  base_url("setting/language/editPhrase/$key") ?>"
                                    class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                <?php if($key!='english'){?>
                                <a href="<?php echo  base_url("setting/language/deletelang/$key") ?>"
                                    onclick="return confirm('<?php echo display("are_you_sure") ?>')"
                                    class="btn btn-xs btn-success"><i class="fa fa-remove"></i></a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>