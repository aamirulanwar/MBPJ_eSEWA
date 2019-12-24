<?php
    notify_msg('notify_msg');
    checking_validation(validation_errors());
?>
<form action="/asset/edit_asset_location/<?php echo uri_segment(3)?>" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Kemaskini Kawasan</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama kawasan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="name_asset_location" class="form-control" placeholder="Nama kawasan" value="<?php echo set_value('name_asset_location',$data['LOCATION_NAME'])?>">
                    <?php echo form_error('name_asset_location')?>
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