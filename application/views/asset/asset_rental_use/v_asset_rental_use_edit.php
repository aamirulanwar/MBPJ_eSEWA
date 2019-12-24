<?php
    notify_msg('notify_msg');
    checking_validation(validation_errors());
?>
<form action="/asset/edit_asset_rental_use/<?php echo uri_segment(3)?>" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Kemaskini Kegunaan Sewaan</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama kegunaan sewaan<span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="name_asset_rental_use" class="form-control" placeholder="Nama kegunaan sewaan" value="<?php echo set_value('name_asset_rental_use',$data['RENTAL_USE_NAME'])?>">
                    <?php echo form_error('name_asset_rental_use')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kod kegunaan sewaan<span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="code_asset_rental_use" class="form-control" placeholder="Kod kegunaan sewaan" value="<?php echo set_value('code_asset_rental_use',$data['RENTAL_USE_CODE'])?>">
                    <?php echo form_error('code_asset_rental_use')?>
                </div>
            </div>
            <div class="checkbox">
                <label class="col-sm-5 pull-right">
                    <?php
                    $active = false;
                    if($data['ACTIVE']==STATUS_ACTIVE):
                        $active = true;
                    endif;
                    ?>
                    <input name="status" value="1" <?php echo set_checkbox('status',STATUS_ACTIVE,$active)?> type="checkbox"> Aktif
                </label>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right btn-submit">Kemaskini</button>
            </div>
        </div>
    </div>
</form>