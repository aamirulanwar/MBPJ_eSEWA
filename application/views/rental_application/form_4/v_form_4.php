
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
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama pemohon <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="name" class="form-control" placeholder="Nama pemohon" value="<?php echo set_value('name')?>">
                    <?php echo form_error('name')?>
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
                <label class="col-sm-3 col-form-label">Tempat lahir <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="place_of_birth" class="form-control" placeholder="Tempat lahir" value="<?php echo set_value('place_of_birth')?>">
                    <?php echo form_error('place_of_birth')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat Tempat Tinggal Sekarang <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="address_1" class="form-control" placeholder="" value="<?php echo set_value('address_1')?>">
                    <?php echo form_error('address_1')?>
                </div>
            </div>
            <div class="form-group row jus">
                <div class="col-sm-5 offset-sm-3">
                    <input type="input" name="address_2" class="form-control" placeholder="" value="<?php echo set_value('address_2')?>">
                    <?php echo form_error('address_2')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Bandar <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="address_3" class="form-control" placeholder="Bandar" value="<?php echo set_value('address_3')?>">
                    <?php echo form_error('address_3')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Poskod <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="postcode" class="form-control" placeholder="Poskod" value="<?php echo set_value('postcode')?>">
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
                <label class="col-sm-3 col-form-label">Maklumat kediaman <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('residence_information',1,true)?> name="residence_information" value="1"> Rumah Sendiri
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                                <input class="form-check-input" type="radio" <?php echo set_radio('residence_information',2,radio_default(2,input_data('residence_information')))?> name="residence_information" value="2"> Rumah Keluarga
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('residence_information',3,radio_default(3,input_data('residence_information')))?> name="residence_information" value="3"> Rumah Sewa
                        </label>
                    </div>
                    <?php echo form_error('residence_information')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jawatan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="position" class="form-control" placeholder="Jawatan" value="<?php echo set_value('position')?>">
                    <?php echo form_error('position')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. kakitangan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="staff_number" class="form-control" placeholder="No. kakitangan" value="<?php echo set_value('staff_number')?>">
                    <?php echo form_error('staff_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Bahagian / Unit <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <select class="form-control" name="department_id">
                        <option value=""> - Sila Pilih - </option>
                        <?php
                        foreach($department_arr as $department):
                            $extra = '';
                            if($department['DEPT_LEVEL']==2):
                                $extra = ' -- ';
                            endif;
                            echo option_value($department['DEPARTMENT_ID'],$extra.$department['DEPARTMENT_NAME'],'department_id',input_data('department_id'));
                        endforeach;
                        ?>
                    </select>
                    <?php echo form_error('department_id')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tarikh mula berkhidmat <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="starting_of_service_date" class="form-control date_class" placeholder="Tarikh Mula Berkhidmat" value="<?php echo set_value('starting_of_service_date')?>">
                    <?php echo form_error('starting_of_service_date')?>
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
                <label class="col-sm-3 col-form-label">Senarai tanggungan</label>
                <div class="col-sm-4">
                    <button type="button" class="btn btn-success" onclick="add_dependent()">Tambah tanggungan</button>
                </div>
            </div>
            <div id="dependent">
                <?php
                    if($_POST):
                        $no_dependent = 100;
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
        }
    }
</script>
