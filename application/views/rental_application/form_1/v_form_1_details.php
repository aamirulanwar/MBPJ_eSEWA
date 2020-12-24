
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
                <label class="col-sm-3 col-form-label">No. rujukan </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo ($data_details['REF_NUMBER'])?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tarikh permohonan </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo date_display($data_details['DATE_APPLICATION'])?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. borang </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo ($data_details['FORM_NUMBER'])?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jenis permohonan sewaan </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo $data_details['TYPE_NAME']?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Lokasi yang dipohon </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo '<strong>'.$data_details['CATEGORY_CODE'].'</strong> - '.$data_details['CATEGORY_NAME'] ?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jenis perniagaan yang dicadangkan </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo set_value('business_experience',$data_details['RENTAL_USE_NAME'])?></p>
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
                <label class="col-sm-3 col-form-label">Nama pemohon <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="name" class="form-control" placeholder="Nama pemohon" value="<?php echo $data_details['NAME']?>">
                    <?php echo form_error('name')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. kad pengenalan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="ic_number" class="form-control" placeholder="No. kad pengenalan" value="<?php echo set_value('ic_number',$data_details['IC_NUMBER'])?>">
                    <?php echo form_error('ic_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tarikh lahir <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="date_of_birth" class="form-control date_class" placeholder="Tarikh lahir" value="<?php echo set_value('date_of_birth',date_display($data_details['DATE_OF_BIRTH']))?>">
                    <?php echo form_error('date_of_birth')?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat kediaman <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="address_1" class="form-control" placeholder="" value="<?php echo set_value('address_1',$data_details['ADDRESS_1'])?>">
                    <?php echo form_error('address_1')?>
                </div>
            </div>
            <div class="form-group row jus">
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
                <label class="col-sm-3 col-form-label">Negeri <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <?php echo select_state($data_details['ADDRESS_STATE'])?>
                    <?php echo form_error('address_state')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat surat menyurat <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="mail_address_1" class="form-control" placeholder="" value="<?php echo set_value('mail_address_1',$data_details['MAIL_ADDRESS_1'])?>">
                    <?php echo form_error('mail_address_1')?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 offset-sm-3">
                    <input type="input" name="mail_address_2" class="form-control" placeholder="" value="<?php echo set_value('mail_address_2',$data_details['MAIL_ADDRESS_2'])?>">
                    <?php echo form_error('mail_address_2')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Bandar <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="mail_address_3" class="form-control" placeholder="Bandar" value="<?php echo set_value('mail_address_3',$data_details['MAIL_ADDRESS_3'])?>">
                    <?php echo form_error('mail_address_3')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Poskod <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="mail_postcode" class="form-control" placeholder="Poskod" value="<?php echo set_value('mail_postcode',$data_details['MAIL_POSTCODE'])?>">
                    <?php echo form_error('mail_postcode')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Negeri <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <?php echo select_state($data_details['MAIL_STATE'],'mail_state')?>
                    <?php echo form_error('mail_state')?>
                </div>
            </div>


            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jarak tempat tinggal dengan lokasi dipohon (km) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" onkeyup="one_decimal_point(this)" name="location_distance" class="form-control" placeholder="Jarak tempat tinggal" value="<?php echo set_value('location_distance',$data_details['LOCATION_DISTANCE'])?>">
                    <?php echo form_error('location_distance')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tempoh menetap di Kajang (tahun) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" onkeyup="one_decimal_point(this)" name="residence_duration" class="form-control" placeholder="Tempoh menetap di Kajang" value="<?php echo set_value('residence_duration',$data_details['RESIDENCE_DURATION'])?>">
                    <?php echo form_error('residence_duration')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Bangsa <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('race',1,radio_default(1,($data_details['RACE'])))?> name="race" value="1"> Melayu
                        </label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('race',2,radio_default(2,($data_details['RACE'])))?> name="race" value="2"> Cina
                        </label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('race',3,radio_default(3,($data_details['RACE'])))?> name="race" value="3"> India
                        </label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('race',4,radio_default(4,($data_details['RACE'])))?> name="race" value="4"> Lain-lain
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
                            <input class="form-check-input" type="radio" <?php echo set_radio('marital_status',1,radio_default(1,$data_details['MARITAL_STATUS']))?> name="marital_status" value="1"> <?php echo marital_status(MARITAL_STATUS_SINGLE)?>
                        </label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('marital_status',2,radio_default(2,$data_details['MARITAL_STATUS']))?> name="marital_status" value="2"> <?php echo marital_status(MARITAL_STATUS_MARRIED)?>
                        </label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('marital_status',3,radio_default(3,$data_details['MARITAL_STATUS']))?> name="marital_status" value="3"> <?php echo marital_status(MARITAL_STATUS_SINGLE_PARENT)?>
                        </label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('marital_status',4,radio_default(4,$data_details['MARITAL_STATUS']))?> name="marital_status" value="4"> <?php echo marital_status(MARITAL_STATUS_OTHERS)?>
                        </label>
                    </div>
                    <?php echo form_error('marital_status')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. telefon rumah </label>
                <div class="col-sm-5">
                    <input type="text" name="home_phone_number" class="form-control" placeholder="No. telefon rumah" value="<?php echo set_value('home_phone_number',$data_details['HOME_PHONE_NUMBER'])?>">
                    <?php echo form_error('home_phone_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. telefon bimbit <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="mobile_phone_number" class="form-control" placeholder="No. telefon bimbit" value="<?php echo set_value('mobile_phone_number',$data_details['MOBILE_PHONE_NUMBER'])?>">
                    <?php echo form_error('mobile_phone_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Status pekerjaan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" onClick="occupation_status_clicked()" type="radio" <?php echo set_radio('occupation_status',1,radio_default(1,$data_details['OCCUPATION_STATUS']))?> name="occupation_status" value="1"> Masih bekerja
                        </label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" onClick="occupation_status_clicked()" type="radio" <?php echo set_radio('occupation_status',2,radio_default(2,$data_details['OCCUPATION_STATUS']))?> name="occupation_status" value="2"> Tidak bekerja / baru berhenti / pencen
                        </label>
                    </div>
                    <?php echo form_error('occupation_status')?>
                </div>
            </div>
            <div class="form-group row" id="occupation" style="display: none">
                <label class="col-sm-3 col-form-label">Pekerjaan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="occupation" class="form-control" placeholder="Pekerjaan" value="<?php echo set_value('occupation',$data_details['OCCUPATION'])?>">
                    <?php echo form_error('occupation')?>
                </div>
            </div>
            <div class="form-group row" id="total_earnings" style="display: none">
                <label class="col-sm-3 col-form-label">Jumlah pendapatan (RM) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" onkeyup="currency_format(this)" name="total_earnings" class="form-control" placeholder="Jumlah pendapatan (RM)" value="<?php echo set_value('total_earnings',num($data_details['TOTAL_EARNINGS']))?>">
                    <?php echo form_error('total_earnings')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Pengalaman berniaga (tahun) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" name="business_experience" class="form-control" placeholder="Pengalaman berniaga" value="<?php echo set_value('business_experience',$data_details['BUSINESS_EXPERIENCE'])?>">
                    <?php echo form_error('business_experience')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Senarai tanggungan </label>
                <div class="col-sm-5">
                    <?php
                    if($dependent):
                        echo '<table class="table table-bordered ">';
                        echo '<thead class="thead-light"><tr><th>Nama</th><th>Hubungan</th></tr></thead>';
                        echo '<tbody>';
                        foreach ($dependent as $row):
                            echo '<tr>';
                            echo '<td>'.$row['NAME'].'</td>';
                            echo '<td>'.$row['RELATIONSHIP'].'</td>';
                            echo '</tr>';
                        endforeach;
                        echo '</tbody>';
                        echo '</table>';
                    else:
                        echo '<p class="form-control-plaintext">Tiada</p>';
                    endif;
                    ?>
                    <?php echo form_error('')?>
                </div>
            </div>
            <hr>
            <div class="row mb-4">
                <div class="col-sm-12">
                    <h3 class="header-h1">LAMPIRAN</h3>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Salinan kad pengenalan <span class="mandatory">*</span></label>
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
                    <input type="hidden" name="ic_number_pic_status" id="ic_number_pic_status" class="form-control" value="<?php echo set_value('ic_number_pic_status',($ic_number_pic)?1:'')?>">
                    <?php echo form_error('ic_number_pic_status')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Gambar passport <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div id="passport_pic_content">
                        <?php
                        if($passport_pic):
                            ?>
                            <img class="img_upload" src="/<?php echo FILE_APPLICATION.'/'.$passport_pic['REF_ID'].'/'.$passport_pic['FILENAME']?>">&nbsp;&nbsp;
                        <?php
                        else:
                            ?>
                        -
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
                            <img class="img_upload" src="/<?php echo FILE_APPLICATION.'/'.$ssm_pic['REF_ID'].'/'.$ssm_pic['FILENAME']?>">&nbsp;&nbsp;
                        <?php
                        else:
                            ?>
                        -
                        <?php
                        endif;
                        ?>
                    </div>
                    <input type="hidden" name="ssm_pic_status" id="ssm_pic_status" class="form-control" value="<?php echo set_value('ssm_pic_status',($ssm_pic)?1:'')?>">
                    <?php echo form_error('ssm_pic_status')?>
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
        occupation_status_clicked();

        if('<?php echo uri_segment(4)?>'=='print')
        {

            $( window ).on('load', '', function(event) {
                window.print();
            });

            $(function()
            {
                $("body").hover(function(){
                    window.close();
                });
            });
        }
    });

</script>
