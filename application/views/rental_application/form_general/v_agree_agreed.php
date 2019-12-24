<hr>
<div class="row mb-4">
    <div class="col-sm-12">
        <h3 class="header-h1">SETUJU TERIMA</h3>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Tarikh keputusan <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <input type="input" name="date_agree" class="form-control-plaintext date_class" placeholder="Tarikh keputusan" value="<?php echo date_display($data_details['DATE_MEETING'])?>">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status setuju terima <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo status_application_applicant($data_details['STATUS_AGREE'])?></p>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Catatan </label>
    <div class="col-sm-5">
        <textarea name="remark_agree" class="form-control-plaintext"><?php echo $data_details['REMARK_AGREE']?></textarea>
        <?php echo form_error('remark_agree')?>
    </div>
</div>