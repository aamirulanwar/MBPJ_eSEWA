
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
                <label class="col-sm-3 col-form-label">Keluasan <span class="mandatory">*</span></label>
                <div class="col-sm-2">
                    <input type="text" onkeyup="currency_format(this)" name="area_site" class="form-control" placeholder="Keluasan" value="<?php echo set_value('area_site',$data_details['AREA_SITE'])?>">
                </div>
                <div class="col-sm-3">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="area_site_unit" value="1" <?php echo set_radio('area_site_unit',1,radio_default(1,$data_details['AREA_SITE_UNIT']))?>> Meter persegi
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="area_site_unit" value="2" <?php echo set_radio('area_site_unit',2,radio_default(2,$data_details['AREA_SITE_UNIT']))?>> Kaki persegi
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
                    <input type="text" onkeyup="currency_format(this)" name="duration_use" class="form-control" placeholder="Cadangan tempoh caj penggunaan / penyelenggaraan" value="<?php echo set_value('duration_use',$data_details['DURATION_USE'])?>">
                </div>
                <div class="col-sm-3">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="duration_use_unit" value="1" <?php echo set_radio('duration_use_unit',1,radio_default(1,$data_details['DURATION_USE_UNIT']))?>> Bulan
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="duration_use_unit" value="2" <?php echo set_radio('duration_use_unit',2,radio_default(2,$data_details['DURATION_USE_UNIT']))?>> Tahun
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
                    <input type="text" name="structure_type_building" class="form-control" placeholder="Cadangan jenis struktur bangunan" value="<?php echo set_value('structure_type_building',$data_details['STRUCTURE_TYPE_BUILDING'])?>">
                    <?php echo form_error('structure_type_building')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Cadangan caj penggunaan / penyelanggaraan sebulan (RM) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" onkeyup="currency_format(this)" name="charge_use_in_a_month" class="form-control" placeholder="Cadangan caj penggunaan / penyelanggaraan sebulan (RM)" value="<?php echo set_value('charge_use_in_a_month',num($data_details['CHARGE_USE_IN_A_MONTH']))?>">
                    <?php echo form_error('charge_use_in_a_month')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Cadangan operasi / kegunaan tapak / bangunan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="operation_use" class="form-control" placeholder="Cadangan operasi / kegunaan tapak / bangunan" value="<?php echo set_value('operation_use',$data_details['OPERATION_USE'])?>">
                    <?php echo form_error('operation_use')?>
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
                    <input type="input" name="name" class="form-control" placeholder="Nama pemohon" value="<?php echo $data_details['NAME']?>">
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
            <hr>
            <div class="row mb-4">
                <div class="col-sm-12">
                    <h3 class="header-h1">LAMPIRAN</h3>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Surat permohonan</label>
                <div class="col-sm-5">
                    <div id="letter_application_file_content">
                        <?php
                        if($letter_application):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_APPLICATION.'/'.$letter_application['REF_ID'].'/'.$letter_application['FILENAME']?>">&nbsp;&nbsp;
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
                <label class="col-sm-3 col-form-label">Salinan kad pengenalan</label>
                <div class="col-sm-5">
                    <div id="ic_number_pic_content">
                        <?php
                        if($ic_number_pic):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_APPLICATION.'/'.$ic_number_pic['REF_ID'].'/'.$ic_number_pic['FILENAME']?>">&nbsp;&nbsp;
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
                <label class="col-sm-3 col-form-label">Pelan lokasi (Google map) </label>
                <div class="col-sm-5">
                    <div id="location_plan_file_content">
                        <?php
                        if($location_plan):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_APPLICATION.'/'.$location_plan['REF_ID'].'/'.$location_plan['FILENAME']?>">&nbsp;&nbsp;
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
                <label class="col-sm-3 col-form-label">Foto lokasi</label>
                <div class="col-sm-5">
                    <div id="photo_location_file_content">
                        <?php
                        if($photo_location):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_APPLICATION.'/'.$photo_location['REF_ID'].'/'.$photo_location['FILENAME']?>">&nbsp;&nbsp;
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
                <label class="col-sm-3 col-form-label">Salinan cadangan pelan struktur </label>
                <div class="col-sm-5">
                    <div id="suggestion_structure_plan_file_content">
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
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Carian SSM (Borang 9) </label>
                <div class="col-sm-5">
                    <div id="app_ssm_file_content">
                        <?php
                        if($ssm):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_APPLICATION.'/'.$ssm['REF_ID'].'/'.$ssm['FILENAME']?>">&nbsp;&nbsp;
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