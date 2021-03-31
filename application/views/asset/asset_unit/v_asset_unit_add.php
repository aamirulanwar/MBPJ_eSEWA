
<?php
    notify_msg('notify_msg');
    checking_validation(validation_errors());
?>
<form action="/asset/add_asset_unit/" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Tambah kawasan Aset</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama Kawasan Aset <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="name_asset" class="form-control" placeholder="Nama kod harta" value="<?php echo set_value('name_asset')?>">
                    <?php echo form_error('name_asset')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kod Seksyen <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <select name="category_id" class="form-control">
                        <option value=""> - Sila pilih - </option>
                        <?php
                        foreach($category as $row):
                            echo option_value($row['CATEGORY_ID'],$row['CATEGORY_CODE'].' - '.$row['CATEGORY_NAME'],'category_id');
                        endforeach;
                        ?>
                    </select>
                    <?php echo form_error('category_id')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat</label>
                <div class="col-sm-5">
                    <textarea class="form-control" name="address"></textarea>
                    <?php echo form_error('address')?>
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