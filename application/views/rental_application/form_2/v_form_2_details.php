
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>
<form action="/rental_application/application_process/<?php echo uri_segment(3)?>" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Proses permohonan (<?php echo $data_details['TYPE_NAME']?>)</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Status Permohonan </label>
                <div class="col-sm-5">
                    <?php echo status_application($data_details['STATUS_APPLICATION'])?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. borang </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo ($data_details['FORM_NUMBER'])?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. rujukan </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo ($data_details['REF_NUMBER'])?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tarikh permohonan </label>
                <div class="col-sm-5">
                    <input type="input"  name="date_application" class="form-control-plaintext" readonly placeholder="Tarikh permohonan" value="<?php echo date_display($data_details['DATE_APPLICATION'])?>">
                    <?php echo form_error('date_application')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jenis permohonan sewaan </label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $data_details['TYPE_NAME']?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kod kategori harta sewaan yang dipohon </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo '<strong>'.$data_details['CATEGORY_CODE'].'</strong> - '.$data_details['CATEGORY_NAME'] ?></p>
                </div>
            </div>


            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kos binaan papan iklan (RM) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" onkeyup="currency_format(this)" name="cost_billboard" class="form-control" placeholder="Kos Binaan Papan Iklan" value="<?php echo set_value('cost_billboard',num($data_details['COST_BILLBOARD']))?>">
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
                            <img class="img_upload" src="/<?php echo FILE_APPLICATION.'/'.$cost_validation['REF_ID'].'/'.$cost_validation['FILENAME']?>">&nbsp;&nbsp;
                        <?php
                        else:
                            ?>
                            -
                        <?php
                        endif;
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jenis struktur papan iklan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="structure_type_billboard" class="form-control" placeholder="Jenis Struktur Papan Iklan" value="<?php echo set_value('structure_type_billboard',$data_details['STRUCTURE_TYPE_BILLBOARD'])?>">
                    <?php echo form_error('structure_type_billboard')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Lokasi papan iklan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="location_billboard" class="form-control" placeholder="Lokasi papan iklan" value="<?php echo set_value('location_billboard',$data_details['LOCATION_BILLBOARD'])?>">
                    <?php echo form_error('location_billboard')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Papan iklan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="billboard" value="1" <?php echo set_radio('billboard',1,radio_default(1,$data_details['BILLBOARD']))?>> Billboard
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="billboard" value="2" <?php echo set_radio('billboard',2,radio_default(2,$data_details['BILLBOARD']))?>> Twinpole/unipole
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="billboard" value="3" <?php echo set_radio('billboard',3,radio_default(3,$data_details['BILLBOARD']))?>> Verticle pole
                        </label>
                    </div>
                    <?php echo form_error('billboard')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Keluasan paparan iklan (Meter) <span class="mandatory">*</span></label>
                <div class="col-sm-2">
                    <input type="text" name="height_billboard" id="height_billboard" onkeyup="currency_format(this),calculate_area_billboard()" class="form-control" placeholder="Tinggi" value="<?php echo set_value('height_billboard',$data_details['HEIGHT_BILLBOARD'])?>">
                    <?php echo form_error('height_billboard')?>
                </div>
                <div class="col-sm-1">
                    <p class="form-control-plaintext text-center"><strong>x</strong></p>
                </div>
                <div class="col-sm-2">
                    <input type="text" name="width_billboard" id="width_billboard" onkeyup="currency_format(this),calculate_area_billboard()" class="form-control" placeholder="Lebar" value="<?php echo set_value('width_billboard',$data_details['WIDTH_BILLBOARD'])?>">
                    <?php echo form_error('width_billboard')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Keluasan tapak papan iklan (meter persegi) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="area_billboard" id="area_billboard" onkeyup="currency_format(this)" class="form-control" placeholder="Keluasan Tapak Papan Iklan" value="<?php echo set_value('area_billboard',$data_details['AREA_BILLBOARD'])?>">
                    <?php echo form_error('area_billboard')?>
                </div>
            </div>
            <?php
                load_view('/rental_application/v_form_details');
            ?>
            <hr>
            <div class="row mb-4">
                <div class="col-sm-12">
                    <h3 class="header-h1">MAKLUMAT PEMOHON</h3>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama syarikat <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="name" class="form-control" placeholder="Nama syarikat" value="<?php echo set_value('name',$data_details['NAME'])?>">
                    <?php echo form_error('name')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. pendaftaran syarikat <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="company_registration_number" class="form-control" placeholder="No. pendaftaran syarikat" value="<?php echo set_value('company_registration_number',$data_details['COMPANY_REGISTRATION_NUMBER'])?>">
                    <?php echo form_error('company_registration_number')?>
                </div>
            </div>


            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat syarikat <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="address_1" class="form-control" placeholder="" value="<?php echo set_value('address_1',$data_details['ADDRESS_1'])?>">
                    <?php echo form_error('address_1')?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 offset-sm-3">
                    <input type="input" name="address_2" class="form-control" placeholder="" value="<?php echo set_value('address_2',$data_details['ADDRESS_2'])?>">
                    <?php echo form_error('address_2')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Bandar <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="address_3" class="form-control" placeholder="Bandar" value="<?php echo set_value('address_3',$data_details['ADDRESS_3'])?>">
                    <?php echo form_error('address_3')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Poskod <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="postcode" class="form-control" placeholder="Poskod" value="<?php echo set_value('postcode',$data_details['POSTCODE'])?>">
                    <?php echo form_error('postcode')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat negeri <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <?php echo select_state($data_details['ADDRESS_STATE'])?>
                    <?php echo form_error('address_state')?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. telefon syarikat <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="home_phone_number" class="form-control" placeholder="No. telefon syarikat" value="<?php echo set_value('home_phone_number',$data_details['HOME_PHONE_NUMBER'])?>">
                    <?php echo form_error('home_phone_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. faks </label>
                <div class="col-sm-5">
                    <input type="text" name="fax_number" class="form-control" placeholder="No. faks" value="<?php echo set_value('fax_number',$data_details['FAX_NUMBER'])?>">
                    <?php echo form_error('fax_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama pengarah/pegawai <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="director_name" class="form-control" placeholder="Nama Pengarah/Pegawai" value="<?php echo set_value('director_name',$data_details['DIRECTOR_NAME'])?>">
                    <?php echo form_error('director_name')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. telefon pengarah/pegawai <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                        <input type="text" name="mobile_phone_number" class="form-control" placeholder="No. telefon pengarah/pegawai" value="<?php echo set_value('mobile_phone_number',$data_details['MOBILE_PHONE_NUMBER'])?>">
                    <?php echo form_error('mobile_phone_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jumlah pendapatan setahun (RM) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" onkeyup="currency_format(this)" name="total_income_a_year" class="form-control" placeholder="Jumlah Pendapatan Setahun" value="<?php echo set_value('total_income_a_year',$data_details['TOTAL_INCOME_A_YEAR'])?>">
                    <?php echo form_error('total_income_a_year')?>
                </div>
            </div>
            <hr>
            <div class="row mb-4">
                <div class="col-sm-12">
                    <h3 class="header-h1">LAMPIRAN</h3>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Carian SSM (Borang 9)</label>
                <div class="col-sm-5">
                    <div id="app_ssm_file_content">
                        <?php
                        if($app_ssm_file):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_APPLICATION.'/'.$app_ssm_file['REF_ID'].'/'.$app_ssm_file['FILENAME']?>">&nbsp;&nbsp;
                        <?php
                        else:
                            ?>
                        -
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="app_ssm_file_status" id="app_ssm_file_status" class="form-control" value="<?php echo set_value('app_ssm_file_status',($app_ssm_file)?1:'')?>">
                    <?php echo form_error('app_ssm_file_status')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Pelan struktur </label>
                <div class="col-sm-5">
                    <div id="structure_plan_content">
                        <?php
                        if($structure_plan):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_APPLICATION.'/'.$structure_plan['REF_ID'].'/'.$structure_plan['FILENAME']?>">&nbsp;&nbsp;
                        <?php
                        else:
                            ?>
                            -
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="structure_plan_status" id="structure_plan_status" class="form-control" value="<?php echo set_value('structure_plan_status',($structure_plan)?1:'')?>">
                    <?php echo form_error('structure_plan_status')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Pelan lokasi</label>
                <div class="col-sm-5">
                    <div id="location_plan_file_content">
                        <?php
                        if($location_plan_file):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_APPLICATION.'/'.$location_plan_file['REF_ID'].'/'.$location_plan_file['FILENAME']?>">&nbsp;&nbsp;
                        <?php
                        else:
                            ?>
                            -
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="location_plan_file_status" id="location_plan_file_status" class="form-control" value="<?php echo set_value('location_plan_file_status',($location_plan_file)?1:'')?>">
                    <?php echo form_error('location_plan_file_status')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Map info</label>
                <div class="col-sm-5">
                    <div id="map_info_content">
                        <?php
                        if($map_info):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_APPLICATION.'/'.$map_info['REF_ID'].'/'.$map_info['FILENAME']?>">&nbsp;&nbsp;
                        <?php
                        else:
                            ?>
                            -
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="map_info_status" id="map_info_status" class="form-control" value="<?php echo set_value('map_info_status',($map_info)?1:'')?>">
                    <?php echo form_error('map_info_status')?>
                </div>
            </div>
            <?php
                if($data_details['STATUS_APPLICATION']==STATUS_APPLICATION_NEW || $data_details['STATUS_APPLICATION']==STATUS_APPLICATION_KIV):
                    load_view('/rental_application/form_general/v_approval_new');
                endif;

                if($data_details['STATUS_APPLICATION']==STATUS_APPLICATION_APPROVED):
                    load_view('/rental_application/form_general/v_approval_approved');
                endif;

                if($data_details['STATUS_AGREE']==STATUS_AGREE_DEFAULT && $data_details['STATUS_APPLICATION']==STATUS_APPLICATION_APPROVED):
                    load_view('/rental_application/form_general/v_agree_new');
                elseif($data_details['STATUS_AGREE']!=STATUS_AGREE_DEFAULT && $data_details['STATUS_APPLICATION']==STATUS_APPLICATION_APPROVED):
                    load_view('/rental_application/form_general/v_agree_agreed');
                endif;
                ?>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <?php
                    if($data_details['STATUS_APPLICATION']!=STATUS_APPLICATION_REJECTED && $data_details['STATUS_AGREE']==STATUS_AGREE_DEFAULT && $data_details['STATUS_CREATE_ACCOUNT']!=STATUS_CREATE_ACCOUNT_YES):
                ?>
                    <button type="submit" class="btn btn-primary pull-right btn-submit">Hantar</button>
                <?php
                    elseif ($data_details['STATUS_APPLICATION']==STATUS_APPLICATION_APPROVED && $data_details['STATUS_AGREE']==STATUS_AGREE_ACCEPTED && $data_details['STATUS_CREATE_ACCOUNT']!=STATUS_CREATE_ACCOUNT_YES):
                ?>
                    <a href="/account/create_acc/<?php echo urlEncrypt($data_details['APPLICATION_ID'])?>">
                        <button type="button" class="btn btn-primary pull-right btn-submit mr-3">Daftar akaun</button>
                    </a>
                <?php
                    endif;
                ?>
                <a href="/rental_application/application">
                    <button type="button" class="btn btn-default pull-right btn-submit mr-3">Kembali</button>
                </a>
            </div>
        </div>
    </div>
</form>
<script>
    $( document ).ready(function() {
        var waste_management_bills = $('input[name=waste_management_bills]:checked').val();
        waste_charge(waste_management_bills);

        var freezer_management_bills = $('input[name=freezer_management_bills]:checked').val();
        frozen_charge(freezer_management_bills);
        <?php
            if(!$_POST):
                ?>
                calculate_rental_deposit();
                calculate_cost_application();
                <?php
            endif;
        ?>
        if('<?php echo uri_segment(4)?>'=='print'){
            // setTimeout(function(){
            //     javascript:window.print();
            // }, 3000);
            //
            // // window.print();
            // window.onfocus=function () {
            //     window.close();
            // }

            window.print();
            setTimeout(window.close, 0);
        }
    });
</script>