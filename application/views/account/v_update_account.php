
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());

pre(form_error());
?>
<?php echo validation_errors(); ?>
<form action="/account/update_acc/<?php echo uri_segment(3)?>" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">No. akaun (<?php echo $data_details['ACCOUNT_NUMBER']?>)</h3>
            <input type="hidden" id="account_id" value="<?php echo $data_details['ACCOUNT_ID']; ?>" />
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm-12">
                    <h3 class="header-h1">MAKLUMAT PEMOHON</h3>
                </div>
            </div>
            <?php
                load_view('/account/form_'.$data_details['FORM_TYPE'].'/v_applicant_update');
            ?>
            <hr>
            <div class="row mb-4">
                <div class="col-sm-12">
                    <h3 class="header-h1">LAMPIRAN</h3>
                </div>
            </div>
            <?php
                load_view('/account/form_'.$data_details['FORM_TYPE'].'/v_attachment');
            ?>
            <hr>
            <div class="row mb-4">
                <div class="col-sm-12">
                    <h3 class="header-h1">MAKLUMAT SEWAAN</h3>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. fail JUU </label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="file_number_juu" value="<?php echo $data_details['FILE_NUMBER_JUU']?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jenis permohonan sewaan </label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control-plaintext" value="<?php echo $data_details['TYPE_NAME']?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kod kategori harta sewaan yang dipohon </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo '<strong>'.$data_details['CATEGORY_CODE'].'</strong> - '.$data_details['CATEGORY_NAME'] ?></p>
                </div>
            </div>
            <?php
                load_view('/account/form_'.$data_details['FORM_TYPE'].'/v_rental_info');
            ?>
            <?php
            if($data_details['TYPE_ID']==6):
                ?>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Jenis </label>
                    <div class="col-sm-5">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" onclick="lms_charge()" type="radio"  <?php echo set_radio('billboard_type',BILLBOARD_TYPE_INTERIM,radio_default(BILLBOARD_TYPE_INTERIM,$data_details['BILLBOARD_TYPE']))?> name="billboard_type" value="<?php echo BILLBOARD_TYPE_INTERIM?>"> Interim
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" onclick="lms_charge()" type="radio" <?php echo set_radio('billboard_type',BILLBOARD_TYPE_SUBLESEN,radio_default(BILLBOARD_TYPE_SUBLESEN,$data_details['BILLBOARD_TYPE']))?> name="billboard_type" value="<?php echo BILLBOARD_TYPE_SUBLESEN?>"> Permit/LMS
                            </label>
                        </div>
                        <?php echo form_error('billboard_type')?>
                    </div>
                </div>

                <div class="form-group row" id="amount_lms" style="display: none">
                    <label class="col-sm-3 col-form-label">Amaun LMS </label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="amount_lms" placeholder="Amaun LMS"  value="<?php echo set_value('amount_lms',num($data_details['LMS_CHARGE']))?>">
                        <?php echo form_error('amount_lms')?>
                    </div>
                </div>
            <?php
            endif;
            ?>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kod harta <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <select name="asset_id" class="form-control">
                        <?php
                            if($data_asset):
                                echo '<option value=""> - Sila pilih - </option>';
                                foreach ($data_asset as $row):
                                    echo option_value($row['ASSET_ID'],$row['ASSET_NAME'],'asset_id',$data_details['ASSET_ID']);
                                endforeach;
                            else:
                                echo '<option value="0"> Tiada </option>';
                            endif;
                        ?>
                    </select>
                    <?php echo form_error('asset_id')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tarikh mula sewaan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" readonly onchange="calculate_duration()" class="form-control date_class_duration" name="date_start" id="date_start" placeholder="Tarikh mula sewaan" value="<?php echo set_value('date_start',date_display($data_details['DATE_START']))?>">
                    <?php echo form_error('date_start')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tarikh tamat sewaan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" readonly onchange="calculate_duration()" class="form-control date_class_duration" name="date_end" id="date_end" placeholder="Tarikh tamat sewaan"  value="<?php echo set_value('date_end',date_display($data_details['DATE_END']))?>">
                    <?php echo form_error('date_end')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tempoh sewaan & bil (bulan) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="number" min="0" class="form-control" name="rental_duration" id="rental_duration" placeholder="Tempoh sewaan & bil" value="<?php echo set_value('rental_duration',$data_details['RENTAL_DURATION'])?>">
                    <?php echo form_error('rental_duration')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jenis bil <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo bill_type($data_details['BILL_TYPE'])?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Dikenakan bil air <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio"  <?php echo set_radio('water_bills',1,radio_default(1,($data_details['WATER_BILLS'])))?> name="water_bills" value="1"> Ya
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('water_bills',0,radio_default(0,($data_details['WATER_BILLS'])))?> name="water_bills" value="0"> Tidak
                        </label>
                    </div>
                    <?php echo form_error('water_bills')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Dikenakan bil pengurusan sampah <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" onclick="waste_charge(1)" <?php echo set_radio('waste_management_bills',1,radio_default(1,$data_details['WASTE_MANAGEMENT_BILLS']))?> name="waste_management_bills" value="1"> Ya
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" onclick="waste_charge(0)" <?php echo set_radio('waste_management_bills',0,radio_default(0,$data_details['WASTE_MANAGEMENT_BILLS']))?> name="waste_management_bills" value="0"> Tidak
                        </label>
                    </div>
                    <?php echo form_error('waste_management_bills')?>
                </div>
            </div>
            <div class="form-group row" id="waste_management_charge">
                <label class="col-sm-3 col-form-label">Caj pengurusan sampah (RM) </label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" onkeyup="currency_format(this)" name="waste_management_charge" placeholder="Caj pengurusan sampah (RM)" value="<?php echo set_value('waste_management_charge',$data_details['WASTE_MANAGEMENT_CHARGE'])?>">
                    <?php echo form_error('waste_management_charge')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Dikenakan bil simpanan sejuk beku <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" onclick="frozen_charge(1),calculate_rental_deposit()"  <?php echo set_radio('freezer_management_bills',1,radio_default(1,$data_details['FREEZER_MANAGEMENT_BILLS']))?> name="freezer_management_bills" value="1"> Ya
                        </label>
                    </div>
                        <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" onclick="frozen_charge(0),calculate_rental_deposit()" <?php echo set_radio('freezer_management_bills',0,radio_default(0,$data_details['FREEZER_MANAGEMENT_BILLS']))?> name="freezer_management_bills" value="0"> Tidak
                        </label>
                    </div>
                    <?php echo form_error('freezer_management_bills')?>
                </div>
            </div>
            <div class="form-group row" id="freezer_management_charge">
                <label class="col-sm-3 col-form-label">Caj simpanan sejuk beku (RM) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" onkeyup="currency_format(this),calculate_rental_deposit()" onchange="calculate_rental_deposit()" name="freezer_management_charge" id="freezer_management_charge_id" placeholder="Caj simpanan sejuk beku (RM)" value="<?php echo set_value('freezer_management_charge',$data_details['FREEZER_MANAGEMENT_CHARGE'])?>">
                    <?php echo form_error('freezer_management_charge')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Harga sewaan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="hidden" name="estimation_rental_charge" id="estimation_rental_charge" onkeyup="currency_format(this),calculate_cost()" onchange="currency_format(this)" class="form-control" placeholder="Harga sewaan" value="<?php echo set_value('estimation_rental_charge',$data_details['ESTIMATION_RENTAL_CHARGE'])?>">
                    <p class="form-control-plaintext"><?php echo num($data_details['ESTIMATION_RENTAL_CHARGE'])?></p>
                    <?php echo form_error('estimation_rental_charge')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Penambahan / pengurangan caj sewa (RM) <span class="mandatory">*</span></label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" onkeyup="currency_format(this),calculate_cost()" name="difference_rental_charge" id="difference_rental_charge" placeholder="" value="<?php echo set_value('difference_rental_charge',num($data_details['DIFFERENCE_RENTAL_CHARGE']))?>">
                    <?php echo form_error('difference_rental_charge')?>
                </div>
                <div class="col-sm-3">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" onclick="calculate_cost()" type="radio"  <?php echo set_radio('difference_rental_charge_type',1,radio_default(1,$data_details['DIFFERENCE_RENTAL_CHARGE_TYPE']))?> name="difference_rental_charge_type" value="1"> Penambahan
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" onclick="calculate_cost()" type="radio" <?php echo set_radio('difference_rental_charge_type',2,radio_default(2,$data_details['DIFFERENCE_RENTAL_CHARGE_TYPE']))?> name="difference_rental_charge_type" value="2"> Pengurangan
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jumlah sewaan (RM) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" readonly onkeyup="currency_format(this)" name="rental_charge" id="rental_charge" placeholder="Jumlah sewaan (RM)" value="<?php echo set_value('rental_charge',num($data_details['RENTAL_CHARGE']))?>">
                    <?php echo form_error('rental_charge')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Cagaran sewa (RM) </label>
                <div class="col-sm-5">
                    <input type="text" class="form-control"onkeyup="currency_format(this)" name="collateral_rental" placeholder="Cagaran sewa (RM)" value="<?php echo set_value('collateral_rental',num($data_details['COLLATERAL_RENTAL']))?>">
                </div>
            </div>
            <div class="form-group row" style="display:none">
                <label class="col-sm-3 col-form-label">Cagaran air (RM) </label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" onkeyup="currency_format(this)" name="collateral_water" placeholder="Cagaran air (RM)" value="<?php echo set_value('collateral_water')?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Fi perjanjian (RM) </label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" onkeyup="currency_format(this)" name="agreement_fee" placeholder="Fi perjanjian (RM)" value="<?php echo set_value('agreement_fee',num($data_details['AGREEMENT_FEE']))?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Status akaun <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio"  <?php echo set_radio('status_acc',1,radio_default(1,($data_details['STATUS_ACC'])))?> name="status_acc" value="1"> Aktif
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('status_acc',2,radio_default(2,($data_details['STATUS_ACC'])))?> name="status_acc" value="2"> Tidak aktif
                        </label>
                    </div>
                    <?php echo form_error('status_acc')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Level notis <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <select name="notice_level" class="form-control">
                        <?php
                        echo option_value('0','0','notice_level',$data_details['NOTICE_LEVEL']);
                        echo option_value('1','1','notice_level',$data_details['NOTICE_LEVEL']);
                        echo option_value('2','2','notice_level',$data_details['NOTICE_LEVEL']);
                        echo option_value('3','3','notice_level',$data_details['NOTICE_LEVEL']);
                        echo option_value('4','4','notice_level',$data_details['NOTICE_LEVEL']);
                        echo option_value('5','5','notice_level',$data_details['NOTICE_LEVEL']);
                        ?>
                    </select>
                    <?php echo form_error('notice_level')?>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <?php
                    if(uri_segment(2)=='detail_acc'):
                        ?>
                            <button type="button" onclick="window.close()" class="btn btn-primary pull-right btn-submit">Tutup</button>
                        <?php
                    else:
                        ?>
                            <button type="submit" class="btn btn-primary pull-right btn-submit">Kemaskini</button>
                        <?php
                    endif;
                ?>
            </div>
        </div>
    </div>
</form>
<script>
    $( document ).ready(function() {
        var waste_management_bills = $('input[name=waste_management_bills]:checked').val();
        waste_charge(waste_management_bills);
        // calculate_cost();

        <?php
        if(uri_segment(2)=='detail_acc'):
        ?>
            $('.form-control').prop('disabled',true);
            $('.form-check-input').prop('disabled',true);
        <?php
        endif;
        ?>
    });
    
    function lms_charge() {
        var billboard_type = $("input[name='billboard_type']:checked").val();
        if(billboard_type==2){
            $('#amount_lms').show();
        }else{
            $('#amount_lms').hide();
        }
    }

    lms_charge();

</script>
