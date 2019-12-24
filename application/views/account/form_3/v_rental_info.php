<div class="form-group row">
    <label class="col-sm-3 col-form-label">Keluasan <span class="mandatory">*</span></label>
    <div class="col-sm-2">
        <input type="text" onkeyup="currency_format(this)" name="area_site" class="form-control" placeholder="Keluasan" value="<?php echo set_value('area_site',$data_details['AREA_SITE'])?>">
    </div>
    <div class="col-sm-3">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" name="area_site_unit" value="1" <?php echo set_radio('area_site_unit',1,radio_default(1,$data_details['AREA_SITE_UNIT']))?>> Meter persegi
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" name="area_site_unit" value="2" <?php echo set_radio('area_site_unit',2,radio_default(2,$data_details['AREA_SITE_UNIT']))?>> Kaki persegi
            </label>
        </div>
    </div>
    <div class="col-sm-5 offset-md-3">
        <?php echo form_error('area_site')?>
    </div>
</div>
<div class="form-group row" style="display: none">
    <label class="col-sm-3 col-form-label">Cadangan tempoh caj penggunaan / penyelenggaraan </label>
    <div class="col-sm-2">
        <p class="form-control-plaintext"><?php echo $data_details['DURATION_USE']?> <?php echo $data_details['DURATION_USE_UNIT']==1?'Bulan':'Unit';?> </p>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Cadangan jenis struktur bangunan</label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo $data_details['STRUCTURE_TYPE_BUILDING']?></p>
    </div>
</div>
<div class="form-group row" style="display: none">
    <label class="col-sm-3 col-form-label">Cadangan caj penggunaan / penyelanggaraan sebulan (RM)</label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo num($data_details['CHARGE_USE_IN_A_MONTH'])?></p>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Cadangan operasi / kegunaan tapak / bangunan</label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo ($data_details['OPERATION_USE'])?></p>
    </div>
</div>