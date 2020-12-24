
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>
<form action="/asset/edit_asset_unit/<?php echo uri_segment(3)?>" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Kemaskini Kod Harta</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama kod harta <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="name_asset" class="form-control" placeholder="Nama kod harta" value="<?php echo set_value('name_asset',$data['ASSET_NAME'])?>">
                    <?php echo form_error('name_asset')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kod kategori <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <select name="category_id" class="form-control">
                        <option value=""> - Sila pilih - </option>
                        <?php
                        foreach($category as $row):
                            echo option_value($row['CATEGORY_ID'],$row['CATEGORY_CODE'].' - '.$row['CATEGORY_NAME'],'category_id',$data['CATEGORY_ID']);
                        endforeach;
                        ?>
                    </select>
                    <?php echo form_error('category_id')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Harga sewaan </label>
                <div class="col-sm-5">
                    <input type="input" name="harga_sewaan" onkeyup="currency_format(this)" class="form-control" placeholder="Harga sewaan" value="<?php echo set_value('harga_sewaan',($data['RENTAL_FEE'])?num($data['RENTAL_FEE']):num(0))?>">
                    <?php echo form_error('harga_sewaan')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat</label>
                <div class="col-sm-5">
                    <textarea class="form-control" name="address"><?php echo set_value('name_asset',$data['ASSET_ADD'])?></textarea>
                    <?php echo form_error('address')?>
                </div>
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
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right btn-submit">Simpan</button>
            </div>
        </div>
    </div>
</form>