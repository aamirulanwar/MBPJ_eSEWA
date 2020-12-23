<div class="form-group row">
    <label class="col-sm-3 col-form-label">No. fail </label>
    <div class="col-sm-5">
        <input type="input" name="no_fail" class="form-control" placeholder="No. fail" value="<?php echo set_value('no_fail',$data_details['FILE_NUMBER'])?>">
        <?php echo form_error('no_fail')?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Kod harta / no. gerai <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <select id="asset_id" onchange="get_asset_status(),get_rent(),calculate_rental_deposit()" name="asset_id" class="form-control">
            <?php
            if($asset):
                echo '<option value=""> - Sila pilih - </option>';
                foreach ($asset as $row):
                    echo option_value($row['ASSET_ID'],$row['ASSET_NAME'],'asset_id',$data_details['ASSET_ID']);
                endforeach;
            else:
                echo '<option value="0"> Tiada </option>';
            endif;
            ?>
        </select>
        <label id="asset_status" style="color: red"></label>
        <?php echo form_error('asset_id')?>
    </div>
    <label class="col-4" id="asset_status" style="color: red"></label>
</div>
<?php
if($asset):
    ?>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Harga sewaan <span class="mandatory">*</span></label>
        <div class="col-sm-5">
            <input type="input" name="rental_fee" id="rent_fee" onkeyup="currency_format(this),calculate_cost_application(),calculate_rental_deposit()" onchange="currency_format(this)" class="form-control" placeholder="Harga sewaan" value="<?php echo set_value('rental_fee',$data_details['ESTIMATION_RENTAL_CHARGE'])?>">
            <?php echo form_error('rental_fee')?>
        </div>
    </div>
<?php
else:
    ?>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Harga sewaan <span class="mandatory">*</span></label>
        <div class="col-sm-5">
            <input type="input" name="rental_fee" id="rent_fee" onkeyup="currency_format(this),calculate_cost_application(),calculate_rental_deposit()" onchange="currency_format(this)" class="form-control" placeholder="Harga sewaan" value="<?php echo set_value('rental_fee',empty($data_details['ESTIMATION_RENTAL_CHARGE'])?$data_details['RENTAL_FEE_DEFAULT']:$data_details['ESTIMATION_RENTAL_CHARGE'])?>">
            <?php echo form_error('rental_fee')?>
        </div>
    </div>
<?php
endif;
?>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Penambahan / pengurangan caj sewa (RM) <span class="mandatory">*</span></label>
    <div class="col-sm-2">
        <input type="text" class="form-control" onkeyup="currency_format(this),calculate_cost_application(),calculate_rental_deposit()" name="difference_rental_charge" id="difference_rental_charge" placeholder="" value="<?php echo set_value('difference_rental_charge',$data_details['DIFFERENCE_RENTAL_CHARGE'])?>">
        <?php echo form_error('difference_rental_charge')?>
    </div>
    <div class="col-sm-3">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" onclick="calculate_cost_application()" type="radio"  <?php echo set_radio('difference_rental_charge_type',1,radio_default(1,$data_details['DIFFERENCE_RENTAL_CHARGE_TYPE']))?> name="difference_rental_charge_type" value="1"> Penambahan
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" onclick="calculate_cost_application()" type="radio" <?php echo set_radio('difference_rental_charge_type',2,radio_default(2,$data_details['DIFFERENCE_RENTAL_CHARGE_TYPE']))?> name="difference_rental_charge_type" value="2"> Pengurangan
            </label>
        </div>
        <!--                    --><?php //echo form_error('difference_rental_charge_type')?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Jumlah sewaan (RM) <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <input type="text" class="form-control" readonly onkeyup="currency_format(this)" name="rental_charge" id="rental_charge" placeholder="Jumlah sewaan (RM)" value="<?php echo set_value('rental_charge',$data_details['RENTAL_FEE'])?>">
        <?php echo form_error('rental_charge')?>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Dikenakan bil pengurusan sampah <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" onclick="waste_charge(1),calculate_rental_deposit()"  <?php echo set_radio('waste_management_bills',1,radio_default(1,$data_details['WASTE_MANAGEMENT_BILLS']))?> name="waste_management_bills" value="1"> Ya
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" onclick="waste_charge(0),calculate_rental_deposit()" <?php echo set_radio('waste_management_bills',0,radio_default(0,$data_details['WASTE_MANAGEMENT_BILLS']))?> name="waste_management_bills" value="0"> Tidak
            </label>
        </div>
        <?php echo form_error('waste_management_bills')?>
    </div>
</div>
<div class="form-group row" id="waste_management_charge">
    <label class="col-sm-3 col-form-label">Caj pengurusan sampah (RM) </label>
    <div class="col-sm-5">
        <input type="text" class="form-control" onkeyup="currency_format(this),calculate_rental_deposit()" onchange="calculate_rental_deposit()" name="waste_management_charge" id="waste_management_charge_id" placeholder="Caj pengurusan sampah (RM)" value="<?php echo set_value('waste_management_charge',$data_details['WASTE_MANAGEMENT_CHARGE'])?>">
        <?php echo form_error('waste_management_charge')?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Dikenakan bil simpanan sejuk beku <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" onclick="frozen_charge(1),calculate_rental_deposit()"  <?php echo set_radio('freezer_management_bills',1,radio_default(1,$data_details['FREEZER_MANAGEMENT_BILLS']))?> name="freezer_management_bills" value="<?php echo set_value('freezer_management_bills',$data_details['FREEZER_MANAGEMENT_BILLS'])?>"> Ya
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" onclick="frozen_charge(0),calculate_rental_deposit()" <?php echo set_radio('freezer_management_bills',0,radio_default(0,$data_details['FREEZER_MANAGEMENT_BILLS']))?> name="freezer_management_bills" value="<?php echo set_value('freezer_management_bills',$data_details['FREEZER_MANAGEMENT_BILLS'])?>"> Tidak
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
    <label class="col-sm-3 col-form-label">Cagaran sewaan / Deposit sewaan <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <input type="text" class="form-control" onkeyup="currency_format(this)" name="deposit_rental" id="deposit_rental" placeholder="Cagaran sewaan" value="<?php echo set_value('deposit_rental',$data_details['DEPOSIT_RENTAL'])?>">
        <?php echo form_error('deposit_rental')?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Kos perjanjian sewa <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <input type="text" class="form-control" onkeyup="currency_format(this)" name="rental_agreement_cost" placeholder="Kos perjanjian sewa" value="<?php echo set_value('rental_agreement_cost',$data_details['RENTAL_AGREEMENT_COST'])?>">
        <?php echo form_error('rental_agreement_cost')?>
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
        <input type="number" min="0" class="form-control" readonly name="rental_duration" id="rental_duration" placeholder="Tempoh sewaan & bil" value="<?php echo set_value('rental_duration',$data_details['RENTAL_DURATION'])?>">
        <?php echo form_error('rental_duration')?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Jenis bil <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio"  <?php echo set_radio('bill_type',1,radio_default(1,$data_details['BILL_TYPE']))?> name="bill_type" value="1"> Bulanan
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" <?php echo set_radio('bill_type',2,radio_default(2,$data_details['BILL_TYPE']))?> name="bill_type" value="2"> Tahunan
            </label>
        </div>
        <?php echo form_error('bill_type')?>
    </div>
</div>