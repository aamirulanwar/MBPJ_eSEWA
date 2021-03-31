
<?php
    notify_msg('notify_msg');
    checking_validation(validation_errors());
?>
<form action="/asset/add_asset_type/" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Tambah Jenis Aset</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama jenis Aset <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="name_asset_type" class="form-control" placeholder="Nama jenis harta" value="<?php echo set_value('name_asset_type')?>">
                    <?php echo form_error('name_asset_type')?>
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