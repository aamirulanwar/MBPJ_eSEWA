
<?php
    notify_msg('notify_msg');
    checking_validation(validation_errors());
?>
<form action="/asset/edit_category/<?php echo uri_segment(3)?>" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Tambah kod kategori</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jenis harta <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <select name="type" class="form-control">
                        <option value=""> - Sila pilih - </option>
                        <?php
                            foreach($asset_type as $row):
                                echo option_value($row['TYPE_ID'],$row['TYPE_NAME'],'type',$data['TYPE_ID']);
                            endforeach;
                        ?>
                    </select>
                    <?php echo form_error('type')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Lokasi </label>
                <div class="col-sm-5">
                    <select name="location" class="form-control">
                        <option value=""> - Sila pilih - </option>
                        <?php
                        foreach($asset_location as $row):
                            echo option_value($row['LOCATION_ID'],$row['LOCATION_NAME'],'location',$data['LOCATION_ID']);
                        endforeach;
                        ?>
                    </select>
                    <?php echo form_error('location')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kod kategori <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="category_code" class="form-control" placeholder="Kod Kategori" value="<?php echo set_value('category_code',$data['CATEGORY_CODE'])?>">
                    <?php echo form_error('category_code')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama kategori <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="category_name" class="form-control" placeholder="Nama Kategori" value="<?php echo set_value('category_name',$data['CATEGORY_NAME'])?>">
                    <?php echo form_error('category_name')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat </label>
                <div class="col-sm-5">
                    <textarea name="address" class="form-control"><?php echo set_value('address',$data['ADDRESS'])?></textarea>
                    <?php echo form_error('address')?>
                </div>
            </div>
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Area </label>-->
<!--                <div class="col-sm-5">-->
<!--                    <input type="input" name="area" class="form-control" placeholder="Area" value="--><?php //echo set_value('area')?><!--">-->
<!--                    --><?php //echo form_error('area')?>
<!--                </div>-->
<!--            </div>-->
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Jumlah unit </label>-->
<!--                <div class="col-sm-5">-->
<!--                    <input type="number"  onkeyup="number_only(this)" min="0" name="total_unit" class="form-control" placeholder="Jumlah unit" value="--><?php //echo set_value('total_unit',$data['TOTAL_UNIT'])?><!--">-->
<!--                    --><?php //echo form_error('total_unit')?>
<!--                </div>-->
<!--            </div>-->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nilai semasa (RM)</label>
                <div class="col-sm-5">
                    <input type="input" onkeyup="currency_format(this)" name="current_value" class="form-control" placeholder="Nilai semasa" value="<?php echo set_value('current_value',num($data['CURRENT_VALUE'],0))?>">
                    <?php echo form_error('current_value')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Harga sewaan </label>
                <div class="col-sm-5">
                    <input type="input" name="value_perunit" onkeyup="currency_format(this)" class="form-control" placeholder="Harga sewaan" value="<?php echo set_value('value_perunit',($data['RENTAL_FEE_DEFAULT'])?num($data['RENTAL_FEE_DEFAULT']):num(0))?>">
                    <?php echo form_error('value_perunit')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kod transaksi <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <select class="form-control js-example-basic-single" name="trans_code">
                        <?php
                        echo  option_value('','- Sila pilih -','trans_code');
                        foreach ($code_trans as $row):
                            echo option_value($row['MCT_TRCODENEW'],$row['MCT_TRCODENEW'].' - '.$row['MCT_TRDESC'],'trans_code',$data['TRCODE_CATEGORY']);
                        endforeach;
                        ?>
                    </select>
                    <?php echo form_error('trans_code')?>
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
                <button type="submit" class="btn btn-primary pull-right btn-submit">Kemaskini</button>
            </div>
        </div>
    </div>
</form>
<script>
    $('#total_unit').on('change keyup', function() {
        // Remove invalid characters
        var sanitized = $(this).val().replace(/[^0-9]/g, '');
        // Update value
        $(this).val(sanitized);
    });
</script>

