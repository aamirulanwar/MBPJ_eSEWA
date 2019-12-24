
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
                <label class="col-sm-3 col-form-label">Kos binaan papan iklan (RM) </label>
                <div class="col-sm-5">
                    <input type="text" onkeyup="currency_format(this)" name="cost_billboard" class="form-control" placeholder="Kos Binaan Papan Iklan" value="<?php echo set_value('cost_billboard')?>">
                    <?php echo form_error('cost_billboard')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Lampiran pengesahan kos binaan oleh perunding </label>
                <div class="col-sm-5">
                    <div id="cost_validation_content">
                        <?php
                        if($cost_validation):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_UPLOAD_TEMP.'/'.$cost_validation['FILE_NAME']?>">&nbsp;&nbsp;<button onclick="remove_image_upload('cost_validation',<?php echo $cost_validation['ID']?>)" type="button" class="btn btn-danger">x</i></button>
                        <?php
                        else:
                            ?>
                            <input type="file" accept="image/png,image/jpeg,image/jpg" onchange="upload_picture('cost_validation')" name="cost_validation" id="cost_validation" class="form-control">
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="cost_validation_status" id="cost_validation_status" class="form-control" value="<?php echo set_value('cost_validation_status',($cost_validation)?1:'')?>">
                    <?php echo form_error('cost_validation_status')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jumlah pendapatan setahun (RM) </label>
                <div class="col-sm-5">
                    <input type="text" onkeyup="currency_format(this)" name="total_income_a_year" class="form-control" placeholder="Jumlah Pendapatan Setahun" value="<?php echo set_value('total_income_a_year')?>">
                    <?php echo form_error('total_income_a_year')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jenis struktur papan iklan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="structure_type_billboard" class="form-control" placeholder="Jenis Struktur Papan Iklan" value="<?php echo set_value('structure_type_billboard')?>">
                    <?php echo form_error('structure_type_billboard')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Lokasi papan iklan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="location_billboard" class="form-control" placeholder="Lokasi papan iklan" value="<?php echo set_value('location_billboard')?>">
                    <?php echo form_error('location_billboard')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Papan iklan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="billboard" value="1" <?php echo set_radio('billboard',1,true)?>> Billboard
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="billboard" value="2" <?php echo set_radio('billboard',2,false)?>> Twinpole/unipole
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="billboard" value="3" <?php echo set_radio('billboard',3,false)?>> Verticle pole
                        </label>
                    </div>
                    <?php echo form_error('billboard')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Keluasan paparan iklan (Meter) <span class="mandatory">*</span></label>
                <div class="col-sm-2">
                    <input type="text" name="height_billboard" id="height_billboard" onkeyup="currency_format(this),calculate_area_billboard()" class="form-control" placeholder="Tinggi" value="<?php echo set_value('height_billboard')?>">
                    <?php echo form_error('height_billboard')?>
                </div>
                <div class="col-sm-1">
                    <p class="form-control-plaintext text-center"><strong>x</strong></p>
                </div>
                <div class="col-sm-2">
                    <input type="text" name="width_billboard" id="width_billboard" onkeyup="currency_format(this),calculate_area_billboard()" class="form-control" placeholder="Lebar" value="<?php echo set_value('width_billboard')?>">
                    <?php echo form_error('width_billboard')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Keluasan tapak papan iklan (meter persegi) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="area_billboard" id="area_billboard" onkeyup="currency_format(this)" class="form-control" placeholder="Keluasan Tapak Papan Iklan" value="<?php echo set_value('area_billboard')?>">
                    <?php echo form_error('area_billboard')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Carian SSM (Borang 9) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div id="app_ssm_file_content">
                        <?php
                        if($app_ssm_file):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_UPLOAD_TEMP.'/'.$app_ssm_file['FILE_NAME']?>">&nbsp;&nbsp;<button onclick="remove_image_upload('app_ssm_file',<?php echo $app_ssm_file['ID']?>)" type="button" class="btn btn-danger">x</i></button>
                        <?php
                        else:
                            ?>
                            <input type="file" accept="image/png,image/jpeg,image/jpg" onchange="upload_picture('app_ssm_file')" name="app_ssm_file" id="app_ssm_file" class="form-control">
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="app_ssm_file_status" id="app_ssm_file_status" class="form-control" value="<?php echo set_value('app_ssm_file_status',($app_ssm_file)?1:'')?>">
                    <?php echo form_error('app_ssm_file_status')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Pelan struktur <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div id="structure_plan_content">
                        <?php
                        if($structure_plan):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_UPLOAD_TEMP.'/'.$structure_plan['FILE_NAME']?>">&nbsp;&nbsp;<button onclick="remove_image_upload('structure_plan',<?php echo $structure_plan['ID']?>)" type="button" class="btn btn-danger">x</i></button>
                        <?php
                        else:
                            ?>
                            <input type="file" accept="image/png,image/jpeg,image/jpg" onchange="upload_picture('structure_plan')" name="structure_plan" id="structure_plan" class="form-control">
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="structure_plan_status" id="structure_plan_status" class="form-control" value="<?php echo set_value('structure_plan_status',($structure_plan)?1:'')?>">
                    <?php echo form_error('structure_plan_status')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Pelan lokasi <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div id="location_plan_file_content">
                        <?php
                        if($location_plan_file):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_UPLOAD_TEMP.'/'.$location_plan_file['FILE_NAME']?>">&nbsp;&nbsp;<button onclick="remove_image_upload('location_plan_file',<?php echo $location_plan_file['ID']?>)" type="button" class="btn btn-danger">x</i></button>
                        <?php
                        else:
                            ?>
                            <input type="file" accept="image/png,image/jpeg,image/jpg" onchange="upload_picture('location_plan_file')" name="location_plan_file" id="location_plan_file" class="form-control">
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="location_plan_file_status" id="location_plan_file_status" class="form-control" value="<?php echo set_value('location_plan_file_status',($location_plan_file)?1:'')?>">
                    <?php echo form_error('location_plan_file_status')?>
                </div>
            </div>
            <!--div class="form-group row">
                <label class="col-sm-3 col-form-label">Map info <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div id="map_info_content">
                        <?php
                        if($map_info):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_UPLOAD_TEMP.'/'.$map_info['FILE_NAME']?>">&nbsp;&nbsp;<button onclick="remove_image_upload('map_info',<?php echo $map_info['ID']?>)" type="button" class="btn btn-danger">x</i></button>
                        <?php
                        else:
                            ?>
                            <input type="file" accept="image/png,image/jpeg,image/jpg" onchange="upload_picture('map_info')" name="map_info" id="map_info" class="form-control">
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="map_info_status" id="map_info_status" class="form-control" value="<?php echo set_value('map_info_status',($map_info)?1:'')?>">
                    <?php echo form_error('map_info_status')?>
                </div>
            </div-->
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
