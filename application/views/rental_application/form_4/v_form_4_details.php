
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
                    <p class="form-control-plaintext"><?php echo date_display($data_details['DATE_APPLICATION'])?></p>
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
                    <input type="input" name="date_of_birth" class="form-control" placeholder="Tarikh lahir" value="<?php echo set_value('date_of_birth',date_display($data_details['DATE_OF_BIRTH']))?>">
                    <?php echo form_error('date_of_birth')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tempat lahir <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="place_of_birth" class="form-control" placeholder="Tempat lahir" value="<?php echo set_value('place_of_birth',($data_details['PLACE_OF_BIRTH']))?>">
                    <?php echo form_error('place_of_birth')?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat Tempat Tinggal Sekarang <span class="mandatory">*</span></label>
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
                    <input type="input" name="address_3" class="form-control" placeholder="" value="<?php echo set_value('address_3',$data_details['ADDRESS_3'])?>">
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
                <label class="col-sm-3 col-form-label">Maklumat kediaman <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('residence_information',1,radio_default(1,intval($data_details['RESIDENCE_INFORMATION'])))?> name="residence_information" value="1"> Rumah Sendiri
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('residence_information',2,radio_default(2,$data_details['RESIDENCE_INFORMATION']))?> name="residence_information" value="2"> Rumah Keluarga
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('residence_information',3,radio_default(3,$data_details['RESIDENCE_INFORMATION']))?> name="residence_information" value="3"> Rumah Sewa
                        </label>
                    </div>
                    <?php echo form_error('residence_information')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jawatan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="position" class="form-control" placeholder="Jawatan" value="<?php echo set_value('position',$data_details['POSITION'])?>">
                    <?php echo form_error('position')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. kakitangan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="staff_number" class="form-control" placeholder="No. kakitangan" value="<?php echo set_value('staff_number',$data_details['STAFF_NUMBER'])?>">
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
                            echo option_value($department['DEPARTMENT_ID'],$extra.$department['DEPARTMENT_NAME'],'department_id',$data_details['DEPARTMENT_ID']);
                        endforeach;
                        ?>
                    </select>
                    <?php echo form_error('department_id')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tarikh mula berkhidmat <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="starting_of_service_date" class="form-control date_class" placeholder="Tarikh Mula Berkhidmat" value="<?php echo set_value('starting_of_service_date',date_display($data_details['STARTING_OF_SERVICE_DATE']))?>">
                    <?php echo form_error('starting_of_service_date')?>
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
                <label class="col-sm-3 col-form-label">Salinan kad pengenalan </label>
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
