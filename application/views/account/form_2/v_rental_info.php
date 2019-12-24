<div class="form-group row">
    <label class="col-sm-3 col-form-label">Kos binaan papan iklan (RM)</label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo num($data_details['COST_BILLBOARD']) ?></p>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Lampiran pengesahan kos binaan oleh perunding </label>
    <div class="col-sm-5">
        <div id="cost_validation_content">
            <?php
            if($cost_validation):
                ?>
                <img class="img_upload" src="/<?php echo $cost_validation['PATH'].$cost_validation['FILENAME']?>">&nbsp;&nbsp;
            <?php
            else:
                ?>
                -
            <?php
            endif;
            ?>
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Jumlah pendapatan setahun (RM) </label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo $data_details['TOTAL_INCOME_A_YEAR'] ?></p>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Jenis struktur papan iklan </label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo $data_details['STRUCTURE_TYPE_BILLBOARD'] ?></p>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Lokasi papan iklan </label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo $data_details['LOCATION_BILLBOARD'] ?></p>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Papan iklan </label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo billboard_type($data_details['BILLBOARD']) ?></p>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Keluasan paparan iklan (Meter) </label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo $data_details['HEIGHT_BILLBOARD'].' <strong>X</strong> '.$data_details['WIDTH_BILLBOARD'] ?></p>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Keluasan tapak papan iklan (meter persegi) </label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo $data_details['AREA_BILLBOARD'] ?></p>
    </div>
</div>