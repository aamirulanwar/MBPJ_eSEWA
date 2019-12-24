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
    <label class="col-sm-3 col-form-label">Tempat lahir <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <input type="input" name="place_of_birth" class="form-control" placeholder="Tempat lahir" value="<?php echo set_value('PLACE_OF_BIRTH',$data_details['PLACE_OF_BIRTH'])?>">
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
    <label class="col-sm-3 col-form-label">Maklumat kediaman </label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo residence_information($data_details['RESIDENCE_INFORMATION'])?></p>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Jawatan </label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo ($data_details['POSITION'])?></p>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Bahagian / Unit </label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo ($department['DEPARTMENT_NAME'])?></p>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Tarikh Mula Berkhidmat <span class="mandatory">*</span></label>
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