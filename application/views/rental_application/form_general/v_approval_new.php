<hr>
<div class="row mb-4">
    <div class="col-sm-12">
        <h3 class="header-h1">KELULUSAN PERMOHONAN</h3>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status permohonan <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input onclick="status_approve_new()" class="form-check-input" type="radio" <?php echo set_radio('status_application',STATUS_APPLICATION_NEW,true)?> name="status_application" value="<?php echo STATUS_APPLICATION_NEW?>"> <?php echo status_application_label(STATUS_APPLICATION_NEW)?>
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input onclick="status_approve_new()" class="form-check-input" type="radio" <?php echo set_radio('status_application',STATUS_APPLICATION_APPROVED)?> name="status_application" value="<?php echo STATUS_APPLICATION_APPROVED?>"> <?php echo status_application_label(STATUS_APPLICATION_APPROVED)?>
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input onclick="status_approve_new()" class="form-check-input" type="radio" <?php echo set_radio('status_application',STATUS_APPLICATION_REJECTED,radio_default(STATUS_APPLICATION_REJECTED,input_data('status_application')))?> name="status_application" value="<?php echo STATUS_APPLICATION_REJECTED?>"> <?php echo status_application_label(STATUS_APPLICATION_REJECTED)?>
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input onclick="status_approve_new()" class="form-check-input" type="radio" <?php echo set_radio('status_application',STATUS_APPLICATION_KIV,radio_default(STATUS_APPLICATION_KIV,input_data('status_application')))?> name="status_application" value="<?php echo STATUS_APPLICATION_KIV?>"> <?php echo status_application_label(STATUS_APPLICATION_KIV)?>
            </label>
        </div>
        <?php echo form_error('status_application')?>
    </div>
</div>
<div class="form-group row approval-new">
    <label class="col-sm-3 col-form-label">No. Bilangan Mesyuarat Penuh Majlis/No. Rujukan <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <input type="text" autocomplete="off" name="meeting_number" class="form-control" placeholder="No. Bilangan Mesyuarat Penuh Majlis/No. Rujukan" value="<?php echo set_value('meeting_number')?>">
        <?php echo form_error('meeting_number')?>
    </div>
</div>
<div class="form-group row approval-new">
    <label class="col-sm-3 col-form-label">Tarikh mesyuarat / keputusan <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <input type="input" name="date_meeting" class="form-control date_class" placeholder="Tarikh mesyuarat / keputusan" value="<?php echo set_value('date_meeting',date_display(timenow()))?>">
        <?php echo form_error('date_meeting')?>
    </div>
</div>
<div class="form-group row approval-new">
    <label class="col-sm-3 col-form-label">Catatan </label>
    <div class="col-sm-5">
        <textarea name="remark" class="form-control"><?php echo set_value('remark')?></textarea>
        <?php echo form_error('remark')?>
    </div>
</div>
<script>
    $( document ).ready(function() {
        status_approve_new();
    });
</script>