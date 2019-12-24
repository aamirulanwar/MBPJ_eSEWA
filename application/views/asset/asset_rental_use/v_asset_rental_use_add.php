
<?php
    notify_msg('notify_msg');
    checking_validation(validation_errors());
?>
<form action="/asset/add_asset_rental_use/" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Tambah Kegunaan Sewaan</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama kegunaan sewaan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="name_asset_rental_use" class="form-control" placeholder="Nama kegunaan sewaan" value="<?php echo set_value('name_asset_rental_use')?>">
                    <?php echo form_error('name_asset_rental_use')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kod kegunaan sewaan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="code_asset_rental_use" class="form-control" placeholder="Kod kegunaan sewaan" value="<?php echo set_value('code_asset_rental_use')?>">
                    <?php echo form_error('code_asset_rental_use')?>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right btn-submit">Simpan</button>
            </div>
        </div>
    </div>
</form>