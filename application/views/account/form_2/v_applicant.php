<div class="form-group row">
    <label class="col-sm-3 col-form-label">Nama syarikat <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <input type="input" name="name" class="form-control" placeholder="Nama pemohon" value="<?php echo $data_details['NAME']?>">
        <?php echo form_error('name')?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">No. pendaftran syarikat <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <input type="input" name="company_registration_number" class="form-control" placeholder="No. pendaftran syarikat" value="<?php echo set_value('company_registration_number',$data_details['COMPANY_REGISTRATION_NUMBER'])?>">
        <?php echo form_error('company_registration_number')?>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Alamat syarikat <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <textarea name="address_1" rows="2" class="form-control"><?php echo set_value('address_1',$data_details['APPLICANT_ADDRESS'])?></textarea>
        <?php echo form_error('address_1')?>
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-5 offset-sm-3">
        <textarea name="address_2" rows="2" class="form-control"><?php echo set_value('address_2',$data_details['ADDRESS_2'])?></textarea>
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
        <input type="text" name="director_name" class="form-control" placeholder="Nama pengarah/pegawai" value="<?php echo set_value('director_name',$data_details['DIRECTOR_NAME'])?>">
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