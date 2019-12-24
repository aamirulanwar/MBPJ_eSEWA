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
    <label class="col-sm-3 col-form-label">Tarikh lahir </label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo date_display($data_details['DATE_OF_BIRTH'])?></p>
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
        <?php echo select_state($data_details['STATE'])?>
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

<!--<div class="form-group row">-->
<!--    <label class="col-sm-3 col-form-label">Alamat kediaman <span class="mandatory">*</span></label>-->
<!--    <div class="col-sm-5">-->
<!--        <!--                    <p class="form-control-plaintext">--><?php ////echo $data_details['APPLICANT_ADDRESS']?><!--<!--</p>-->
<!--        <textarea name="address" class="form-control" rows="5">--><?php //echo $data_details['USER_ADDRESS']?><!--</textarea>-->
<!--        --><?php //echo form_error('address')?>
<!--    </div>-->
<!--</div>-->
<!--<div class="form-group row">-->
<!--    <label class="col-sm-3 col-form-label">Alamat surat menyurat <span class="mandatory">*</span></label>-->
<!--    <div class="col-sm-5">-->
<!--        <textarea name="mail_address" class="form-control" rows="5">--><?php //echo $data_details['MAIL_ADDRESS']?><!--</textarea>-->
<!--        --><?php //echo form_error('mail_address')?>
<!--        <!--                    <p class="form-control-plaintext">--><?php ////echo $data_details['MAIL_ADDRESS']?><!--<!--</p>-->
<!--    </div>-->
<!--</div>-->
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Bangsa </label>
    <div class="col-sm-5">
        <input type="text" name="business_experience" class="form-control-plaintext" placeholder="Bangsa" value="<?php echo race($data_details['RACE'])?>">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status perkahwinan </label>
    <div class="col-sm-5">
        <p class="form-control-plaintext"><?php echo marital_status($data_details['MARITAL_STATUS'])?></p>
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
    <label class="col-sm-3 col-form-label">Pekerjaan </label>
    <div class="col-sm-5">
        <input type="text" name="occupation" class="form-control-plaintext" placeholder="Pekerjaan" value="<?php echo set_value('occupation',$data_details['OCCUPATION'])?>">
        <?php echo form_error('occupation')?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Jumlah pendapatan (RM) </label>
    <div class="col-sm-5">
        <input type="text" name="total_earnings" class="form-control-plaintext" placeholder="Jumlah pendapatan (RM)" value="<?php echo set_value('total_earnings',$data_details['TOTAL_EARNINGS'])?>">
        <?php echo form_error('total_earnings')?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Pengalaman berniaga (tahun)</label>
    <div class="col-sm-5">
        <input type="text" name="business_experience" class="form-control-plaintext" placeholder="Pengalaman berniaga" value="<?php echo set_value('business_experience',$data_details['BUSINESS_EXPERIENCE'].' Tahun')?>">
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