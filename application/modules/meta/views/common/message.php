<?php if ($success_message = $this->session->flashdata('success_message')) : ?>
    <div class="alert alert-success">
        <?php echo $success_message ?></li>
    </div>
<?php endif ?>

<?php if ($validation_error_msg = $this->session->flashdata('validation_error')) : ?>
    <div class="alert alert-danger">
        <ul>
            <li><?php echo implode('</li><li>', $validation_error_msg) ?></li>
        </ul>
    </div>
<?php endif ?>