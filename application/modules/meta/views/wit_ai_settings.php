<div class="row meta-config-area" id="meta-config-area">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo $title ?></h4>
                </div>
            </div>

            <div class="panel-body meta-config">
                <?php $this->view('common/progressive_tab') ?>

                <form method="post" action="<?php echo base_url('meta/messenger/set_config/wit_ai_basic'); ?>">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash() ?>">

                    <div class="row">
                        <div class="col-lg-6 col-lg-offset-3">
                            <?php $this->view('common/message') ?>

                            <div class="form-group">
                                <label for="wit_server_token" class="form-label"><?php echo display('wit_ai_server_token') ?> <i class="text-danger">*</i></label>
                                <input name="witServerToken" class="form-control" type="text" placeholder="<?php echo display('wit_ai_server_token') ?>" id="wit_server_token" value="<?php echo $wit_config->getConfig('witServerToken') ?>" required />
                            </div>

                            <div class="form-group">
                                <label for="wit_client_token" class="form-label"><?php echo display('wit_ai_client_token') ?> <i class="text-danger">*</i></label>
                                <input name="witClientToken" class="form-control" type="text" placeholder="<?php echo display('wit_ai_client_token') ?>" id="wit_client_token" value="<?php echo $wit_config->getConfig('witClientToken') ?>" required />
                            </div>

                            <div class="form-group">
                                <label class="form-label">Wit.Ai Category Intent <i class="text-danger">*</i></label>
                                <input class="form-control" type="text" value="category_questions" readonly />
                            </div>

                            <div class="form-group">
                                <label class="form-label">Wit.Ai Item Entity <i class="text-danger">*</i></label>
                                <input class="form-control" type="text" value="item:item" readonly />
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success w-md m-b-5">Save</button>
                                <hr />
                            </div>
                        </div>
                    </div>
                </form>

                <form method="post" action="<?php echo base_url('meta/messenger/set_config/wit_ai_utterances'); ?>">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash() ?>">

                    <div class="row">
                        <div class="col-lg-6 col-lg-offset-3">
                            <div class="form-group">
                                <label class="form-label">Utterance</label>
                                <table class="table table-bordered table-hover" id="purchaseTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Text <i class="text-danger">*</i></th>
                                            <th class="text-center">Intent <i class="text-danger">*</i></th>
                                            <th class="text-center">Item Entities <i class="text-danger">*</i></th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="addUtterance">

                                        <?php
                                        $utterances = json_decode($wit_config->getConfig('witUtterances')) ?: [''];
                                        foreach ($utterances as $ukey => $utterance) :
                                        ?>
                                            <tr>
                                                <td class="col-sm-4">
                                                    <textarea class="form-control item_texts" name="witUtterances[text][]" required><?php echo $utterance ? $utterance->text : ''; ?></textarea>
                                                </td>
                                                <td class="col-sm-3">

                                                    <?php
                                                    echo form_dropdown(
                                                        'witUtterances[intent][]',
                                                        [
                                                            '' => 'Select Intent',
                                                            'out_of_scope' => 'out_of_scope',
                                                            'category_questions' => 'category_questions'
                                                        ],
                                                        $utterance ? $utterance->intent : '',
                                                        'class="resizeselect form-control intents dont-select-me" data-minimum-results-for-search="Infinity" data-placeholder="Select Intent" required'
                                                    );
                                                    ?>

                                                </td>
                                                <td class="col-sm-4">
                                                    <input name="witUtterances[entity][]" class="form-control item_entities" data-role="tagsinput" type="text" value="<?php echo $utterance ? $utterance->entity : ''; ?>">
                                                </td>
                                                <td class="col-sm-1">
                                                    <?php if ($ukey) : ?>
                                                        <button class="btn btn-danger remove_row_btn" type="button">&times;</button>
                                                    <?php endif ?>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="100%" class="text-right">
                                                <button type="button" id="add_row_btn" class="btn btn-info" tabindex="9"><?php echo display('add_more') . " " . display('item') ?></button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success w-md m-b-5">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var $utteranceTableBody = $('#addUtterance');

        $('#purchaseTable').on('click', '#add_row_btn', function() {
            var $newRow = $(`<tr>
                    <td class="col-sm-4"><textarea class="form-control item_texts" name="witUtterances[text][]" required></textarea></td>
                    <td class="col-sm-3">
                        <select name="witUtterances[intent][]" class="resizeselect form-control intents dont-select-me" data-minimum-results-for-search="Infinity" data-placeholder="Select Intent" required>
                            <option value="">Select Intent</option>
                            <option value="out_of_scope">out_of_scope</option>
                            <option value="category_questions">category_questions</option>
                        </select>
                    </td>
                    <td class="col-sm-4"><input name="witUtterances[entity][]" class="form-control item_entities" data-role="tagsinput" type="text" value=""></td>
                    <td class="col-sm-1"><button class="btn btn-danger remove_row_btn" type="button">&times;</button></td>
                </tr>`);

            $utteranceTableBody.append($newRow);
            $('.intents', $newRow).select2();
            $('.item_entities', $newRow).tagsinput();
        });

        $('#purchaseTable').on('change.select2', '.intents', function() {
            var $row = $(this).closest('tr'),
                $entity = $('.item_entities', $row),
                intentValue = $(this).val();

            if (intentValue != 'out_of_scope') {
                $entity.removeAttr('disabled');
                $entity.tagsinput('destroy');
                $entity.tagsinput();
            } else {
                $entity.val('').attr('disabled', true);
                $entity.tagsinput('destroy');
            }
        });

        $('#purchaseTable').on('click', '.remove_row_btn', function() {
            var $row = $(this).closest('tr'),
                utterance = $('.item_texts', $row).val(),
                csrf_hash = $('#csrfhashresarvation').val();

            if (utterance != '') {
                $.post(
                    '<?php echo base_url('meta/messenger/delete_utterance'); ?>', {
                        csrf_test_name: csrf_hash,
                        text: utterance
                    },
                    function(result) {
                        if (result.sent) {
                            $row.remove();
                        }
                    }
                );
            } else {
                $row.remove();
            }
        });

        $('.intents').select2();
    });
</script>