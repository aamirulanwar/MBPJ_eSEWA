<?php
    notify_msg('notify');
?>

<form action="/reset_password/" method="post" class="form-horizontal">
<div class="card card-accent-info">
    <div class="card-header">
        <div class="pull-left">
            <strong>Tukar Kata Laluan</strong>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Kata laluan <span class="mandatory">*</span></label>
            <div class="col-sm-5">
                <input type="input" name="password_1" class="form-control" value="<?php echo set_value('password_1')?>">
                <?php echo form_error('password_1')?>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Taip semula kata laluan <span class="mandatory">*</span></label>
            <div class="col-sm-5">
                <input type="input" name="password_2" class="form-control" value="<?php echo set_value('password_2')?>">
                <?php echo form_error('password_2')?>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right btn-submit">Simpan</button>
            </div>
        </div>
    </div>
</div>
</form>