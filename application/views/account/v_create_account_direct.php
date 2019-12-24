
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>
<form action="/account/create_acc_direct/<?php echo uri_segment(3)?>" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Pendaftaran akaun (<?php echo $data_type['TYPE_NAME']?>)</h3>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm-12">
                    <h3 class="header-h1">MAKLUMAT PEMOHON</h3>
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
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Tarikh lahir <span class="mandatory">*</span></label>-->
<!--                <div class="col-sm-5">-->
<!--                    <input type="input" name="date_of_birth" class="form-control date_class" placeholder="Tarikh lahir" value="--><?php //echo set_value('date_of_birth')?><!--">-->
<!--                    --><?php //echo form_error('date_of_birth')?>
<!--                </div>-->
<!--            </div>-->
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Tempat lahir <span class="mandatory">*</span></label>-->
<!--                <div class="col-sm-5">-->
<!--                    <input type="input" name="place_of_birth" class="form-control" placeholder="Tempat lahir" value="--><?php //echo set_value('place_of_birth')?><!--">-->
<!--                    --><?php //echo form_error('place_of_birth')?>
<!--                </div>-->
<!--            </div>-->

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat <span class="mandatory">*</span></label>
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
                    <?php echo select_state()?>
                    <?php echo form_error('address_state')?>
                </div>
            </div>

<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Maklumat kediaman </label>-->
<!--                <div class="col-sm-5">-->
<!--                    <p class="form-control-plaintext">--><?php //echo residence_information($data_details['RESIDENCE_INFORMATION'])?><!--</p>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Jawatan </label>-->
<!--                <div class="col-sm-5">-->
<!--                    <p class="form-control-plaintext">--><?php //echo ($data_details['POSITION'])?><!--</p>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Bahagian / Unit </label>-->
<!--                <div class="col-sm-5">-->
<!--                    <p class="form-control-plaintext">--><?php //echo ($department['DEPARTMENT_NAME'])?><!--</p>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Tarikh Mula Berkhidmat <span class="mandatory">*</span></label>-->
<!--                <div class="col-sm-5">-->
<!--                    <input type="input" name="starting_of_service_date" class="form-control date_class" placeholder="Tarikh Mula Berkhidmat" value="--><?php //echo set_value('starting_of_service_date',date_display($data_details['STARTING_OF_SERVICE_DATE']))?><!--">-->
<!--                    --><?php //echo form_error('starting_of_service_date')?>
<!--                </div>-->
<!--            </div>-->
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
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Senarai tanggungan </label>-->
<!--                <div class="col-sm-5">-->
<!--                    --><?php
//                    if($dependent):
//                        echo '<table class="table table-bordered ">';
//                        echo '<thead class="thead-light"><tr><th>Nama</th><th>Hubungan</th></tr></thead>';
//                        echo '<tbody>';
//                        foreach ($dependent as $row):
//                            echo '<tr>';
//                            echo '<td>'.$row['NAME'].'</td>';
//                            echo '<td>'.$row['RELATIONSHIP'].'</td>';
//                            echo '</tr>';
//                        endforeach;
//                        echo '</tbody>';
//                        echo '</table>';
//                    else:
//                        echo '<p class="form-control-plaintext">Tiada</p>';
//                    endif;
//                    ?>
<!--                    --><?php //echo form_error('')?>
<!--                </div>-->
<!--            </div>-->
            <?php
//                load_view('/account/form_'.$data_details['FORM_TYPE'].'/v_applicant');
            ?>
<!--            <hr>-->
<!--            <div class="row mb-4">-->
<!--                <div class="col-sm-12">-->
<!--                    <h3 class="header-h1">LAMPIRAN</h3>-->
<!--                </div>-->
<!--            </div>-->
            <?php
//                load_view('/account/form_'.$data_details['FORM_TYPE'].'/v_attachment');
            ?>
            <hr>
            <div class="row mb-4">
                <div class="col-sm-12">
                    <h3 class="header-h1">MAKLUMAT SEWAAN</h3>
                </div>
            </div>
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">No. fail JUU </label>-->
<!--                <div class="col-sm-5">-->
<!--                    <input type="text" class="form-control" name="file_number_juu" value="--><?php //echo set_value('file_number_juu')?><!--">-->
<!--                </div>-->
<!--            </div>-->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jenis permohonan sewaan </label>
                <div class="col-sm-5">
                    <input type="text" readonly class="form-control-plaintext" value="<?php echo $data_type['TYPE_NAME']?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kod kategori harta sewaan yang dipohon <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <select class="form-control" name="category_id">
                        <option value="">- Sila pilih -</option>
                    <?php
                        if($data_category):
                            foreach ($data_category as $row):
                                echo option_value($row['CATEGORY_ID'],$row['CATEGORY_CODE'].'-'.$row['CATEGORY_NAME'],'category_id');
                            endforeach;
                        endif;
                    ?>
                    </select>
                    <?php echo form_error('category_id')?>
                </div>
            </div>
            <?php
//                load_view('/account/form_'.$data_details['FORM_TYPE'].'/v_rental_info');
            ?>

<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Kod harta <span class="mandatory">*</span></label>-->
<!--                <div class="col-sm-5">-->
<!--                    <select name="asset_id" class="form-control">-->
<!--                        --><?php
//                            if($data_asset):
//                                echo '<option value=""> - Sila pilih - </option>';
//                                foreach ($data_asset as $row):
//                                    echo option_value($row['ASSET_ID'],$row['ASSET_NAME'],'asset_id',$data_details['ASSET_ID']);
//                                endforeach;
//                            else:
//                                echo '<option value="0"> Tiada </option>';
//                            endif;
//                        ?>
<!--                    </select>-->
<!--                    --><?php //echo form_error('asset_id')?>
<!--                </div>-->
<!--            </div>-->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tarikh mula sewaan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" readonly onchange="calculate_duration()" class="form-control date_class_duration" name="date_start" id="date_start" placeholder="Tarikh mula sewaan" value="<?php echo set_value('date_start')?>">
                    <?php echo form_error('date_start')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tarikh tamat sewaan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" readonly onchange="calculate_duration()" class="form-control date_class_duration" name="date_end" id="date_end" placeholder="Tarikh tamat sewaan"  value="<?php echo set_value('date_end')?>">
                    <?php echo form_error('date_end')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tempoh sewaan & bil (bulan) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="number" min="0" class="form-control" name="rental_duration" id="rental_duration" placeholder="Tempoh sewaan & bil" value="<?php echo set_value('rental_duration')?>">
                    <?php echo form_error('rental_duration')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jenis bil <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio"  <?php echo set_radio('bill_type',1,radio_default(1,$data_type['BILL_TYPE']))?> name="bill_type" value="1"> Bulanan
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('bill_type',2,radio_default(2,$data_type['BILL_TYPE']))?> name="bill_type" value="2"> Tahunan
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('bill_type',3,radio_default(3,$data_type['BILL_TYPE']))?> name="bill_type" value="3"> Tidak diproses
                        </label>
                    </div>
                    <?php echo form_error('bill_type')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Dikenakan bil air <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio"  <?php echo set_radio('water_bills',1)?> name="water_bills" value="1"> Ya
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" <?php echo set_radio('water_bills',0,true)?> name="water_bills" value="0"> Tidak
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
                            <input class="form-check-input" type="radio" onclick="waste_charge(1)" <?php echo set_radio('waste_management_bills',1,false)?> name="waste_management_bills" value="1"> Ya
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" onclick="waste_charge(0)" <?php echo set_radio('waste_management_bills',0,true)?> name="waste_management_bills" value="0"> Tidak
                        </label>
                    </div>
                    <?php echo form_error('waste_management_bills')?>
                </div>
            </div>
            <div class="form-group row" id="waste_management_charge">
                <label class="col-sm-3 col-form-label">Caj pengurusan sampah (RM) </label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" onkeyup="currency_format(this)" name="waste_management_charge" placeholder="Caj pengurusan sampah (RM)" value="<?php echo set_value('waste_management_charge')?>">
                    <?php echo form_error('waste_management_charge')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Dikenakan bil simpanan sejuk beku <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" onclick="frozen_charge(1),calculate_rental_deposit()"  <?php echo set_radio('freezer_management_bills',1)?> name="freezer_management_bills" value="1"> Ya
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" onclick="frozen_charge(0),calculate_rental_deposit()" <?php echo set_radio('freezer_management_bills',0,true)?> name="freezer_management_bills" value="0"> Tidak
                        </label>
                    </div>
                    <?php echo form_error('freezer_management_bills')?>
                </div>
            </div>
            <div class="form-group row" id="freezer_management_charge">
                <label class="col-sm-3 col-form-label">Caj simpanan sejuk beku (RM) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" onkeyup="currency_format(this),calculate_rental_deposit()" onchange="calculate_rental_deposit()" name="freezer_management_charge" id="freezer_management_charge_id" placeholder="Caj simpanan sejuk beku (RM)" value="<?php echo set_value('freezer_management_charge')?>">
                    <?php echo form_error('freezer_management_charge')?>
                </div>
            </div>
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Kadar sewa (RM) <span class="mandatory">*</span></label>-->
<!--                <div class="col-sm-5">-->
<!--                    <input type="text" class="form-control" onkeyup="currency_format(this),calculate_cost()" name="estimation_rental_charge" id="estimation_rental_charge" placeholder="Kadar sewa (RM)" value="--><?php //echo set_value('estimation_rental_charge',num($data_details['RENTAL_FEE']))?><!--">-->
<!--                    --><?php //echo form_error('estimation_rental_charge')?>
<!--                </div>-->
<!--            </div>-->
<!--            --><?php
//            if($data_asset):
//                ?>
<!--                <div class="form-group row">-->
<!--                    <label class="col-sm-3 col-form-label">Harga sewaan <span class="mandatory">*</span></label>-->
<!--                    <div class="col-sm-5">-->
<!--                        <input type="input" name="estimation_rental_charge" id="estimation_rental_charge" onkeyup="currency_format(this),calculate_cost()" onchange="currency_format(this)" class="form-control" placeholder="Harga sewaan" value="--><?php //echo set_value('estimation_rental_charge',$data_details['RENTAL_FEE'])?><!--">-->
<!--                        --><?php //echo form_error('estimation_rental_charge')?>
<!--                    </div>-->
<!--                </div>-->
<!--            --><?php
//            else:
//                ?>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Harga sewaan <span class="mandatory">*</span></label>
                    <div class="col-sm-5">
                        <input type="input" name="estimation_rental_charge" id="estimation_rental_charge" onkeyup="currency_format(this),calculate_cost()" onchange="currency_format(this)" class="form-control" placeholder="Harga sewaan" value="<?php echo set_value('estimation_rental_charge')?>">
                        <?php echo form_error('estimation_rental_charge')?>
                    </div>
                </div>
<!--            --><?php
//            endif;
//            ?>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Penambahan / pengurangan caj sewa (RM) <span class="mandatory">*</span></label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" onkeyup="currency_format(this),calculate_cost()" name="difference_rental_charge" id="difference_rental_charge" placeholder="" value="<?php echo set_value('difference_rental_charge')?>">
                    <?php echo form_error('difference_rental_charge')?>
                </div>
                <div class="col-sm-3">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" onclick="calculate_cost()" type="radio"  <?php echo set_radio('difference_rental_charge_type',1,true)?> name="difference_rental_charge_type" value="1"> Penambahan
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" onclick="calculate_cost()" type="radio" <?php echo set_radio('difference_rental_charge_type',2)?> name="difference_rental_charge_type" value="2"> Pengurangan
                        </label>
                    </div>
<!--                    --><?php //echo form_error('difference_rental_charge_type')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jumlah sewaan (RM) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" readonly onkeyup="currency_format(this)" name="rental_charge" id="rental_charge" placeholder="Jumlah sewaan (RM)" value="<?php echo set_value('rental_charge')?>">
                    <?php echo form_error('rental_charge')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Cagaran sewa (RM) </label>
                <div class="col-sm-5">
                    <input type="text" class="form-control"onkeyup="currency_format(this)" name="collateral_rental" placeholder="Cagaran sewa (RM)" value="<?php echo set_value('collateral_rental')?>">
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
                    <input type="text" class="form-control" onkeyup="currency_format(this)" name="agreement_fee" placeholder="Fi perjanjian (RM)" value="<?php echo set_value('agreement_fee')?>">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right btn-submit">Hantar</button>
<!--                <a href="/rental_application/application">-->
<!--                    <button type="button" class="btn btn-default pull-right btn-submit mr-3">Batal</button>-->
<!--                </a>-->
            </div>
        </div>
    </div>
</form>
<script>
    $( document ).ready(function() {
        var waste_management_bills = $('input[name=waste_management_bills]:checked').val();
        waste_charge(waste_management_bills);
        // calculate_cost();
        var freezer_management_bills = $('input[name=freezer_management_bills]:checked').val();
        frozen_charge(freezer_management_bills);
    });

</script>
