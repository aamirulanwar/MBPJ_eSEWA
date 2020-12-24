
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>
<form action="/rental_application/form/<?php echo uri_segment(3)?>" method="post" id="submit_image" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Permohonan Baru (<?php echo $asset_type['TYPE_NAME']?>)</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tarikh permohonan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="date_application" class="form-control date_class" placeholder="Tarikh permohonan" value="<?php echo set_value('date_application',date_display(timenow()))?>">
                    <?php echo form_error('date_application')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nombor borang </label>
                <div class="col-sm-5">
                    <input type="input" name="form_number" class="form-control" placeholder="Nombor borang" value="<?php echo set_value('form_number')?>">
                    <?php echo form_error('form_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jenis permohonan sewaan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $asset_type['TYPE_NAME']?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Lokasi yang dipohon <span class="mandatory">*</span></label>
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
            <?php
            if($asset_type['TYPE_ID']==1):
            ?>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jenis perniagaan yang dicadangkan&nbsp;<span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <select id="rental_use_id" onchange="check_rental()" name="rental_use_id" class="form-control">
                        <option value=""> - Sila pilih - </option>
                        <?php
                        foreach($rental_use as $row):
                            echo option_value($row['RENTAL_USE_ID'],$row['RENTAL_USE_CODE'].' - '.$row['RENTAL_USE_NAME'],'rental_use_id');
                        endforeach;
                        ?>
                    </select>
                    <?php echo form_error('rental_use_id')?>
                </div>
            </div>
            <?php
            endif;
            ?>
            <div class="form-group row" id="rental_use_remark" style="display: none">
                <label class="col-sm-3 col-form-label">Catatan jenis perniagaan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <textarea name="rental_use_remark" class="form-control"><?php echo set_value('rental_use_remark')?></textarea>
                    <?php echo form_error('rental_use_remark')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama pemohon <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="name" class="form-control" placeholder="Nama pemohon" value="<?php echo set_value('name')?>">
                    <?php echo form_error('name')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jenis kad pengenalan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('type_of_ic',1,radio_default(1,input_data('type_of_ic',1)))?> name="type_of_ic" value="1"> MyKad
                        </label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('type_of_ic',2,radio_default(2,input_data('type_of_ic')))?> name="type_of_ic" value="2"> MyPolis / MyTentera
                        </label>
                    </div><br>
                    <?php echo form_error('type_of_ic')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. kad pengenalan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="ic_number" class="form-control" placeholder="No. kad pengenalan" value="<?php echo set_value('ic_number')?>">
                    <?php echo form_error('ic_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tarikh lahir <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="date_of_birth" class="form-control date_class" placeholder="Tarikh lahir" value="<?php echo set_value('date_of_birth')?>">
                    <?php echo form_error('date_of_birth')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat kediaman <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" id="address_1" name="address_1" class="form-control" value="<?php echo set_value('address_1')?>">
                    <?php echo form_error('address_1')?>
                </div>
            </div>
            <div class="form-group row jus">
                <div class="col-sm-5 offset-sm-3">
                    <input type="input" id="address_2" name="address_2" class="form-control" value="<?php echo set_value('address_2')?>">
                    <?php echo form_error('address_2')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Bandar <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" id="address_3" name="address_3" class="form-control" placeholder="Bandar" value="<?php echo set_value('address_3')?>">
                    <?php echo form_error('address_3')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Poskod <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" id="postcode" name="postcode" class="form-control" placeholder="Poskod" value="<?php echo set_value('postcode')?>">
                    <?php echo form_error('postcode')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Negeri <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <?php echo select_state(input_data('address_state'))?>
                    <?php echo form_error('address_state')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat surat menyurat <span class="mandatory">*</span></label>
                <div class="col-sm-3">
                    <input name="mail_address_1" id="mail_address_1" rows="2" class="form-control" value="<?php echo set_value('mail_address_1')?>">
                    <?php echo form_error('mail_address_1')?>
                </div>
                <div class="col-sm-2">
                    <button type="button" onclick="copy_address()" class="btn btn-default btn-sm">Sama seperti alamat kediaman</button>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 offset-sm-3">
                    <input name="mail_address_2" id="mail_address_2" rows="2" class="form-control" value="<?php echo set_value('mail_address_2')?>">
                    <?php echo form_error('mail_address_2')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Bandar <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input name="mail_address_3" id="mail_address_3" rows="2" class="form-control" value="<?php echo set_value('mail_address_3')?>" placeholder="Bandar">
                    <?php echo form_error('mail_address_3')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Poskod <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" id="mail_postcode" name="mail_postcode" class="form-control" placeholder="Poskod" value="<?php echo set_value('mail_postcode')?>">
                    <?php echo form_error('mail_postcode')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Negeri <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <?php echo select_state(input_data('mail_state'),'mail_state')?>
                    <?php echo form_error('mail_state')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jarak tempat tinggal dengan lokasi dipohon (km) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" onkeyup="one_decimal_point(this)" name="location_distance" class="form-control" placeholder="Jarak tempat tinggal" value="<?php echo set_value('location_distance')?>">
                    <?php echo form_error('location_distance')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tempoh menetap di Kajang (tahun)&nbsp;<span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" onkeyup="one_decimal_point(this)" name="residence_duration" class="form-control" placeholder="Tempoh menetap di Kajang" value="<?php echo set_value('residence_duration')?>">
                    <?php echo form_error('residence_duration')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Bangsa <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('race',1,radio_default(1,input_data('race')))?> name="race" value="1"> Melayu
                        </label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('race',2,radio_default(2,input_data('race')))?> name="race" value="2"> Cina
                        </label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('race',3,radio_default(3,input_data('race')))?> name="race" value="3"> India
                        </label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('race',4,radio_default(4,input_data('race')))?> name="race" value="4"> Lain-lain
                        </label>
                    </div>
                    <?php echo form_error('race')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Status perkahwinan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('marital_status',1,radio_default(1,input_data('marital_status')))?> name="marital_status" value="1"> <?php echo marital_status(MARITAL_STATUS_SINGLE)?>
                        </label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('marital_status',2,radio_default(2,input_data('marital_status')))?> name="marital_status" value="2"> <?php echo marital_status(MARITAL_STATUS_MARRIED)?>
                        </label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('marital_status',3,radio_default(3,input_data('marital_status')))?> name="marital_status" value="3"> <?php echo marital_status(MARITAL_STATUS_SINGLE_PARENT)?>
                        </label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('marital_status',4,radio_default(4,input_data('marital_status')))?> name="marital_status" value="4"> <?php echo marital_status(MARITAL_STATUS_OTHERS)?>
                        </label>
                    </div>
                    <?php echo form_error('marital_status')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. telefon rumah </label>
                <div class="col-sm-5">
                    <input type="text" name="home_phone_number" class="form-control" placeholder="No. telefon rumah" value="<?php echo set_value('home_phone_number')?>">
                    <?php echo form_error('home_phone_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. telefon bimbit <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="mobile_phone_number" class="form-control" placeholder="No. telefon bimbit" value="<?php echo set_value('mobile_phone_number')?>">
                    <?php echo form_error('mobile_phone_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Status pekerjaan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" onClick="occupation_status_clicked()" type="radio" <?php echo set_radio('occupation_status',1,radio_default(1,input_data('occupation_status')))?> name="occupation_status" value="1"> Masih bekerja
                        </label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" onClick="occupation_status_clicked()" type="radio" <?php echo set_radio('occupation_status',2,radio_default(2,input_data('occupation_status')))?> name="occupation_status" value="2"> Tidak bekerja / baru berhenti / pencen
                        </label>
                    </div>
                    <?php echo form_error('occupation_status')?>
                </div>
            </div>
            <div class="form-group row" id="occupation" style="display: none">
                <label class="col-sm-3 col-form-label">Pekerjaan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="occupation" class="form-control" placeholder="Pekerjaan" value="<?php echo set_value('occupation')?>">
                    <?php echo form_error('occupation')?>
                </div>
            </div>
            <div class="form-group row" id="total_earnings" style="display: none">
                <label class="col-sm-3 col-form-label">Jumlah pendapatan (RM) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" onkeyup="currency_format(this)" name="total_earnings" class="form-control" placeholder="Jumlah pendapatan (RM)" value="<?php echo set_value('total_earnings')?>">
                    <?php echo form_error('total_earnings')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Pengalaman berniaga (tahun) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" onkeyup="one_decimal_point(this)" name="business_experience" class="form-control" placeholder="Pengalaman berniaga" value="<?php echo set_value('business_experience')?>">
                    <?php echo form_error('business_experience')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jenis perniagaan </label>
                <div class="col-sm-5">
                    <input type="text" name="business_type" class="form-control" placeholder="Jenis perniagaan" value="<?php echo set_value('business_type')?>">
                </div>
            </div>
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Senarai tanggungan <span class="mandatory">*</span></label>-->
<!--                <div class="col-sm-5">-->
<!--                    <input type="text" name="" class="form-control" placeholder="Pengalaman berniaga" value="--><?php //echo set_value('')?><!--">-->
<!--                    --><?php //echo form_error('')?>
<!--                </div>-->
<!--            </div>-->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Senarai tanggungan</label>
                <div class="col-sm-4">
                    <button type="button" class="btn btn-success" onclick="add_dependent()">Tambah tanggungan</button>
                </div>
            </div>
            <div id="dependent">
                <?php
                    if($_POST):
                        $no_dependent   = 100;
                        $dependent      = input_data('dependent_name[]');
                        $relationship   = input_data('dependent_relationship[]');
                        if(isset($dependent) && $dependent):
                            $index_dependent = 0;
                            foreach (input_data('dependent_name[]') as $row):
                                ?>
                                <div id="dependent_no_<?php echo $no_dependent ?>" class="form-group row">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="dependent_name[]" class="form-control" placeholder="Nama" value="<?php echo $row?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="dependent_relationship[]" class="form-control" placeholder="Hubungan" value="<?php echo (isset($relationship[$index_dependent]))?$relationship[$index_dependent]:''?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" onclick="remove_dependent(<?php echo $no_dependent ?>)" class="btn btn-danger">x</i></button>
                                    </div>
                                </div>
                                <?php
                                $no_dependent = $no_dependent+1;
                                $index_dependent = $index_dependent+1;
                            endforeach;
                        endif;
                    endif;
                ?>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Salinan kad pengenalan</label>
                <div class="col-sm-5">
                    <div id="ic_number_pic_content">
                        <?php
                        if($ic_number_pic):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_UPLOAD_TEMP.'/'.$ic_number_pic['FILE_NAME']?>">&nbsp;&nbsp;<button onclick="remove_image_upload('ic_number_pic',<?php echo $ic_number_pic['ID']?>)" type="button" class="btn btn-danger">x</i></button>
                        <?php
                        else:
                            ?>
                            <input type="file" accept="image/png,image/jpeg,image/jpg" onchange="upload_picture('ic_number_pic')" name="ic_number_pic" id="ic_number_pic" class="form-control">
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="ic_number_pic_status" id="ic_number_pic_status" class="form-control" value="<?php echo set_value('ic_number_pic_status',($ic_number_pic)?1:'')?>">
                    <?php echo form_error('ic_number_pic_status')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Gambar passport</label>
                <div class="col-sm-5">
                    <div id="passport_pic_content">
                        <?php
                        if($passport_pic):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_UPLOAD_TEMP.'/'.$passport_pic['FILE_NAME']?>">&nbsp;&nbsp;<button onclick="remove_image_upload('passport_pic',<?php echo $passport_pic['ID']?>)" type="button" class="btn btn-danger">x</i></button>
                        <?php
                        else:
                            ?>
                            <input type="file" accept="image/png,image/jpeg,image/jpg" onchange="upload_picture('passport_pic')" name="passport_pic" id="passport_pic" class="form-control">
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="passport_pic_status" id="passport_pic_status" class="form-control" value="<?php echo set_value('passport_pic_status',($passport_pic)?1:'')?>">
                    <?php echo form_error('passport_pic_status')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Salinan pendaftaran perniagaan (SSM)</label>
                <div class="col-sm-5">
                    <div id="ssm_pic_content">
                        <?php
                            if($ssm_pic):
                            ?>
                                <img class="img_upload" src="/<?php echo FILE_UPLOAD_TEMP.'/'.$ssm_pic['FILE_NAME']?>">&nbsp;&nbsp;<button onclick="remove_image_upload('ssm_pic',<?php echo $ssm_pic['ID']?>)" type="button" class="btn btn-danger">x</i></button>
                            <?php
                            else:
                            ?>
                                <input type="file" accept="image/png,image/jpeg,image/jpg" onchange="upload_picture('ssm_pic')" name="ssm_pic" id="ssm_pic" class="form-control">
                            <?php
                            endif;
                        ?>
                    </div>
                    <input type="hidden" name="ssm_pic_status" id="ssm_pic_status" class="form-control" value="<?php echo set_value('ssm_pic_status',($ssm_pic)?1:'')?>">
                    <?php echo form_error('ssm_pic_status')?>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right btn-submit">Simpan</button>
            </div>
        </div>
    </div>
    <input type="hidden"  name="upload_name" id="upload_name" class="form-control">
    <input type="hidden"  name="form_session" value="<?php echo $form_session?>" class="form-control">
    <input type="hidden"  name="file_module_type" value="<?php echo FILE_MODULE_TYPE_APPLICATION?>" class="form-control">
</form>
<script>
    $( document ).ready(function() {
        check_rental();
    });
    function check_rental() {
        var rental_use_id = $('#rental_use_id').val();
        if(rental_use_id == 40){
            $('#rental_use_remark').show();
        }else{
            $('#rental_use_remark').hide();
        }
    }

    function copy_address() {
        var address_1       = $('#address_1').val();
        var address_2       = $('#address_2').val();
        var address_3       = $('#address_3').val();
        var postcode        = $('#postcode').val();
        var address_state   = $('#address_state').val();

        $('#mail_address_1').val(address_1);
        $('#mail_address_2').val(address_2);
        $('#mail_address_3').val(address_3);
        $('#mail_postcode').val(postcode);
        $('#mail_state').val(address_state);
    }
</script>
