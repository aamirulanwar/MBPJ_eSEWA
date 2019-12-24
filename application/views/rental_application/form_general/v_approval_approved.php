<hr>
<div class="row mb-4">
    <div class="col-sm-12">
        <h3 class="header-h1">KELULUSAN PERMOHONAN</h3>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">No. Bilangan Mesyuarat Penuh Majlis/No. Rujukan </label>
    <div class="col-sm-5">
        <input type="text" autocomplete="off" class="form-control-plaintext" placeholder="No. Bilangan Mesyuarat Penuh Majlis/No. Rujukan" value="<?php echo $data_details['MEETING_NUMBER']?>">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Tarikh mesyuarat / keputusan </label>
    <div class="col-sm-5">
        <input type="input" class="form-control-plaintext date_class" placeholder="Tarikh mesyuarat / keputusan" value="<?php echo date_display($data_details['DATE_MEETING'])?>">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status permohonan </label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo status_application($data_details['STATUS_APPLICATION'])?></p>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Catatan </label>
    <div class="col-sm-5">
        <textarea class="form-control-plaintext"><?php echo $data_details['APPLICATION_REMARK']?></textarea>
    </div>
</div>