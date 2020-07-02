<?php
    notify_msg('add_user');
    checking_validation(validation_errors());
?>

<form action="/user/add_user/" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Tambah Pengguna Baru</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Id pengguna (emel) <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="email_address" class="form-control" value="<?php echo set_value('email_address')?>" placeholder="Eg: myemail@mpkj.gov.my">
                    <?php echo form_error('email_address')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="name" class="form-control" value="<?php echo set_value('name')?>" placeholder="Nama">
                    <?php echo form_error('name')?>
                </div>
            </div>
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">User Role <span class="mandatory">*</span></label>-->
<!--                <div class="col-sm-5">-->
<!--                    <select class="form-control" name="user_role">-->
<!--                        <option value=""> - Please Select - </option>-->
<!--                        --><?php
//                        echo option_value(USER_ROLE_CUSTOMER_SERVICE,'Customer Service','user_role');
//                        echo option_value(USER_ROLE_HOD,'HOD','user_role');
//                        echo option_value(USER_ROLE_EXEC,'Executive','user_role');
//                        echo option_value(USER_ROLE_TECHNICAL_SUPPORT,'Technical Support','user_role');
//                        ?>
<!--                    </select>-->
<!--                    --><?php //echo form_error('level')?>
<!--                </div>-->
<!--            </div>-->
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Able to assign online tickets to Customer Service staff <span class="mandatory">*</span></label>-->
<!--                <div class="col-sm-5">-->
<!--                    <select class="form-control" name="assign_to_csr">-->
<!--                        --><?php
//                        echo option_value(0,'No','assign_to_csr');
//                        echo option_value(1,'Yes','assign_to_csr');
//                        ?>
<!--                    </select>-->
<!--                    --><?php //echo form_error('assign_to_csr')?>
<!--                </div>-->
<!--            </div>-->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kumpulan Pengguna <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <select class="form-control" name="user_group">
                        <option value=""> - Sila Pilih - </option>
                        <?php
                        foreach($list_user_group as $user_group):
                            echo option_value($user_group['USER_GROUP_ID'],$user_group['USER_GROUP_DESC'],'user_group',input_data('user_group'));
                        endforeach;
                        ?>
                    </select>
                    <?php echo form_error('user_group')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jabatan/Bahagian <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <select class="form-control" name="department">
                        <option value=""> - Sila Pilih - </option>
                        <?php
                        foreach($department_arr as $department):
                            $extra = '';
                            if($department['DEPT_LEVEL']==2):
                                $extra = ' -- ';
                            endif;
                            echo option_value($department['DEPARTMENT_ID'],$extra.$department['DEPARTMENT_NAME'],'department',input_data('department'));
                        endforeach;
                        ?>
                    </select>
                    <?php echo form_error('department')?>
                </div>
            </div>
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Position <span class="mandatory">*</span></label>-->
<!--                <div class="col-sm-5">-->
<!--                    <select class="form-control" name="position">-->
<!--                        <option value=""> - Please Select - </option>-->
<!--                        --><?php
//                        foreach($list_position as $position):
//                            echo option_value($position['pos_id'],$position['pos_name'],'position',input_data('position'));
//                        endforeach;
//                        ?>
<!--                    </select>-->
<!--                    --><?php //echo form_error('position')?>
<!--                </div>-->
<!--            </div>-->
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. Telefon <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="mobile_number" class="form-control" value="<?php echo set_value('mobile_number')?>" placeholder="Eg: 0131234567">
                    <?php echo form_error('mobile_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kata laluan</label>
                <div class="col-sm-5">
                    <p class="form-control-static"><strong><?php echo DEFAULT_PASSWORD?></strong> <span class="valError"><em>* Sila tukar kata laluan selepas berjaya didaftarkan</em></span></p>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right btn-submit">Simpan</button>
            </div>
        </div>
    </div>
</form>