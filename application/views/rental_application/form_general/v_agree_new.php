<hr>
<div class="row mb-4">
    <div class="col-sm-12">
        <h3 class="header-h1">SETUJU TERIMA</h3>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status setuju terima <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input onclick="status_agree_default()" class="form-check-input" type="radio" <?php echo set_radio('status_agree',STATUS_AGREE_DEFAULT,true)?> name="status_agree" value="<?php echo STATUS_AGREE_DEFAULT?>"> Tiada
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input onclick="status_agree_default()" class="form-check-input" type="radio" <?php echo set_radio('status_agree',STATUS_AGREE_ACCEPTED)?> name="status_agree" value="<?php echo STATUS_AGREE_ACCEPTED?>"> Terima
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input onclick="status_agree_default()" class="form-check-input" type="radio" <?php echo set_radio('status_agree',STATUS_AGREE_REJECTED,radio_default(STATUS_AGREE_REJECTED,input_data('status_agree')))?> name="status_agree" value="<?php echo STATUS_AGREE_REJECTED?>"> Tolak
            </label>
        </div>
        <?php echo form_error('status_agree')?>
    </div>
</div>
<div class="form-group row agree-default">
    <label class="col-sm-3 col-form-label">Tarikh keputusan <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <input type="input" name="date_agree" class="form-control date_class" placeholder="Tarikh keputusan" value="<?php echo set_value('date_agree',date_display(timenow()))?>">
        <?php echo form_error('date_agree')?>
    </div>
</div>
<div class="form-group row agree-default">
    <label class="col-sm-3 col-form-label">Catatan </label>
    <div class="col-sm-5">
        <textarea name="remark_agree" class="form-control"><?php echo set_value('remark_agree')?></textarea>
        <?php echo form_error('remark_agree')?>
    </div>
</div>
<script>
    $( document ).ready(function() {
        status_agree_default();
    });
</script>