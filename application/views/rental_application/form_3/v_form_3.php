
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>
<form action="/rental_application/form/<?php echo uri_segment(3)?>" method="post" id="submit_image" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Permohonan Baru(<?php echo $asset_type['TYPE_NAME']?>)</h3>
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
                <label class="col-sm-3 col-form-label">Nama syarikat <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="name" class="form-control" placeholder="Nama syarikat" value="<?php echo set_value('name')?>">
                    <?php echo form_error('name')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. pendaftaran syarikat <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="company_registration_number" class="form-control" placeholder="No. pendaftaran syarikat" value="<?php echo set_value('company_registration_number')?>">
                    <?php echo form_error('company_registration_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat syarikat <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="address_1" class="form-control" placeholder="" value="<?php echo set_value('address_1')?>">
                    <?php echo form_error('address_1')?>
                </div>
            </div>
            <div class="form-group row">
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
                <label class="col-sm-3 col-form-label">Alamat negeri <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <?php echo select_state(input_data('address_state'))?>
                    <?php echo form_error('address_state')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. telefon syarikat <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="home_phone_number" class="form-control" placeholder="No. telefon syarikat" value="<?php echo set_value('home_phone_number')?>">
                    <?php echo form_error('home_phone_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. faks </label>
                <div class="col-sm-5">
                    <input type="text" name="fax_number" class="form-control" placeholder="No. faks" value="<?php echo set_value('fax_number')?>">
                    <?php echo form_error('fax_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama pengarah/pegawai <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="director_name" class="form-control" placeholder="Nama Pengarah/Pegawai" value="<?php echo set_value('director_name')?>">
                    <?php echo form_error('director_name')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. telefon pengarah/pegawai <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="mobile_phone_number" class="form-control" placeholder="No. telefon pengarah/pegawai" value="<?php echo set_value('mobile_phone_number')?>">
                    <?php echo form_error('mobile_phone_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kod kategori harta sewaan yang dipohon <span class="mandatory">*</span></label>
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
                <label class="col-sm-3 col-form-label">Keluasan <span class="mandatory">*</span></label>
                <div class="col-sm-2">
                    <input type="text" onkeyup="currency_format(this)" name="area_site" class="form-control" placeholder="Keluasan" value="<?php echo set_value('area_site')?>">
                </div>
                <div class="col-sm-3">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="area_site_unit" value="1" <?php echo set_radio('area_site_unit',1,true)?>> Meter persegi
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="area_site_unit" value="2" <?php echo set_radio('area_site_unit',2)?>> Kaki persegi
                        </label>
                    </div>
                </div>
                <div class="col-sm-5 offset-md-3">
                    <?php echo form_error('area_site')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Cadangan tempoh caj penggunaan / penyelenggaraan <span class="mandatory">*</span></label>
                <div class="col-sm-2">
                    <input type="text" onkeyup="currency_format(this)" name="duration_use" class="form-control" placeholder="Cadangan tempoh caj penggunaan / penyelenggaraan" value="<?php echo set_value('duration_use')?>">
                </div>
                <div class="col-sm-3">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="duration_use_unit" value="1" <?php echo set_radio('duration_use_unit',1,true)?>> Bulan
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="duration_use_unit" value="2" <?php echo set_radio('duration_use_unit',2)?>> Tahun
                        </label>
                    </div>
                </div>
                <div class="col-sm-5 offset-md-3">
                    <?php echo form_error('duration_use')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Cadangan jenis struktur bangunan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="structure_type_building" class="form-control" placeholder="Cadangan jenis struktur bangunan" value="<?php echo set_value('structure_type_building')?>">
                    <?php echo form_error('structure_type_building')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Cadangan caj penggunaan / penyelanggaraan sebulan (RM) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" onkeyup="currency_format(this)" name="charge_use_in_a_month" class="form-control" placeholder="Cadangan caj penggunaan / penyelanggaraan sebulan (RM)" value="<?php echo set_value('charge_use_in_a_month')?>">
                    <?php echo form_error('charge_use_in_a_month')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Cadangan operasi / kegunaan tapak / bangunan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="operation_use" class="form-control" placeholder="Cadangan operasi / kegunaan tapak / bangunan" value="<?php echo set_value('operation_use')?>">
                    <?php echo form_error('operation_use')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Surat permohonan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div id="letter_application_file_content">
                        <?php
                        if($letter_application):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_UPLOAD_TEMP.'/'.$letter_application['FILE_NAME']?>">&nbsp;&nbsp;<button onclick="remove_image_upload('letter_application_file',<?php echo $letter_application['ID']?>)" type="button" class="btn btn-danger">x</i></button>
                        <?php
                        else:
                            ?>
                            <input type="file" accept="image/png,image/jpeg,image/jpg" onchange="upload_picture('letter_application_file')" name="letter_application_file" id="letter_application_file" class="form-control">
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="letter_application_file_status" id="letter_application_file_status" class="form-control" value="<?php echo set_value('letter_application_file_status',($letter_application)?1:'')?>">
                    <?php echo form_error('letter_application_file_status')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Salinan kad pengenalan <span class="mandatory">*</span></label>
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
                <label class="col-sm-3 col-form-label">Pelan lokasi (Google map) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div id="location_plan_file_content">
                        <?php
                        if($location_plan):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_UPLOAD_TEMP.'/'.$location_plan['FILE_NAME']?>">&nbsp;&nbsp;<button onclick="remove_image_upload('location_plan_file',<?php echo $location_plan['ID']?>)" type="button" class="btn btn-danger">x</i></button>
                        <?php
                        else:
                            ?>
                            <input type="file" accept="image/png,image/jpeg,image/jpg" onchange="upload_picture('location_plan_file')" name="location_plan_file" id="location_plan_file" class="form-control">
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="location_plan_file_status" id="location_plan_file_status" class="form-control" value="<?php echo set_value('location_plan_file_status',($location_plan)?1:'')?>">
                    <?php echo form_error('location_plan_file_status')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Foto lokasi <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div id="photo_location_file_content">
                        <?php
                        if($photo_location):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_UPLOAD_TEMP.'/'.$photo_location['FILE_NAME']?>">&nbsp;&nbsp;<button onclick="remove_image_upload('photo_location_file',<?php echo $photo_location['ID']?>)" type="button" class="btn btn-danger">x</i></button>
                        <?php
                        else:
                            ?>
                            <input type="file" accept="image/png,image/jpeg,image/jpg" onchange="upload_picture('photo_location_file')" name="photo_location_file" id="photo_location_file" class="form-control">
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="photo_location_file_status" id="photo_location_file_status" class="form-control" value="<?php echo set_value('photo_location_file_status',($photo_location)?1:'')?>">
                    <?php echo form_error('photo_location_file_status')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Salinan cadangan pelan struktur <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div id="suggestion_structure_plan_file_content">
                        <?php
                        if($structure_plan):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_UPLOAD_TEMP.'/'.$structure_plan['FILE_NAME']?>">&nbsp;&nbsp;<button onclick="remove_image_upload('suggestion_structure_plan_file',<?php echo $structure_plan['ID']?>)" type="button" class="btn btn-danger">x</i></button>
                        <?php
                        else:
                            ?>
                            <input type="file" accept="image/png,image/jpeg,image/jpg" onchange="upload_picture('suggestion_structure_plan_file')" name="suggestion_structure_plan_file" id="suggestion_structure_plan_file" class="form-control">
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="suggestion_structure_plan_file_status" id="suggestion_structure_plan_file_status" class="form-control" value="<?php echo set_value('suggestion_structure_plan_file_status',($structure_plan)?1:'')?>">
                    <?php echo form_error('suggestion_structure_plan_file_status')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Carian SSM (Borang 9) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div id="app_ssm_file_content">
                        <?php
                        if($ssm):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_UPLOAD_TEMP.'/'.$ssm['FILE_NAME']?>">&nbsp;&nbsp;<button onclick="remove_image_upload('app_ssm_file',<?php echo $ssm['ID']?>)" type="button" class="btn btn-danger">x</i></button>
                        <?php
                        else:
                            ?>
                            <input type="file" accept="image/png,image/jpeg,image/jpg" onchange="upload_picture('app_ssm_file')" name="app_ssm_file" id="app_ssm_file" class="form-control">
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="app_ssm_file_status" id="app_ssm_file_status" class="form-control" value="<?php echo set_value('app_ssm_file_status',($ssm)?1:'')?>">
                    <?php echo form_error('app_ssm_file_status')?>
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
