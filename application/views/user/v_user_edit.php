<?php
    notify_msg('edit_user');
    checking_validation(validation_errors());
?>
<form action="/user/edit_user/<?php echo uri_segment(3)?>" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Kemaskini Pengguna</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Id pengguna (emel)</label>
                <div class="col-sm-5">
                    <input type="input" name="email_address" class="form-control" value="<?php echo set_value('email_address',$user_details['USER_EMAIL'])?>" placeholder="Eg: myemail@mpkj.com">
                    <?php echo form_error('email_address')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="name" class="form-control" value="<?php echo set_value('name',$user_details['USER_NAME'])?>" placeholder="Nama">
                    <?php echo form_error('name')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Kumpulan Pengguna <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <select class="form-control" name="user_group">
                        <option value=""> - Sila Pilih - </option>
                        <?php
                        foreach($list_user_group as $user_group):
                            echo option_value($user_group['USER_GROUP_ID'],$user_group['USER_GROUP_DESC'],'user_group',$user_details['USER_GROUP_ID']);
                        endforeach;
                        ?>
                    </select>
                    <?php echo form_error('user_group')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Bahagian / Jabatan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <select class="form-control" name="department">
                        <option value=""> - Sila Pilih - </option>
                        <?php
                        foreach($department_arr as $department):
                            $extra = '';
                            if($department['DEPT_LEVEL']==2):
                                $extra = ' -- ';
                            endif;
                            echo option_value($department['DEPARTMENT_ID'],$extra.$department['DEPARTMENT_NAME'],'department',$user_details['DEPARTMENT_ID']);
                        endforeach;
                        ?>
                    </select>
                    <?php echo form_error('department')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. Telefon</label>
                <div class="col-sm-5">
                    <input type="input" name="mobile_number" class="form-control" value="<?php echo set_value('mobile_number',$user_details['USER_NOBIMBIT'])?>" placeholder="Cth: 60131234567">
                    <?php echo form_error('mobile_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tukar kata laluan kepada (<?php echo DEFAULT_PASSWORD?>)</label>
                <div class="col-sm-5">
                    <button type="button" onclick="reset_password('<?php echo $user_details['USER_ID']?>')" data-toggle="modal" data-target="#modal_password" class="btn btn-warning btnn-sm">Change password</button>
                    <div id="status_update_pass" style="color: green"></div>
                </div>
            </div>
            <div class="checkbox">
                <label class="col-sm-5 pull-right">
                    <?php
                    $active = false;
                    if($user_details['USER_ACTIVE']==STATUS_ACTIVE):
                        $active = true;
                    endif;
                    ?>
                    <input name="status" value="1" <?php echo set_checkbox('status',STATUS_ACTIVE,$active)?> type="checkbox"> Active
                </label>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right btn-submit">Kemaskini</button>
            </div>
        </div>
    </div>
</form>

<script>
    function reset_password(user_id){
        $.ajax({
            'url': '/user/reset_password/',
            'type': 'post', //the way you want to send data to your URL
            'dataType': 'text',
            'data': {'user_id': user_id},
            'beforeSend':function(){
                $('#status_update_pass').html('<br/><img src="/assets/img/loading.gif" width="5%">');
            },
            'success': function (data) { //probably this request will return anything, it'll be put in var "data"
                $('#status_update_pass').html('Successfully change password');
            }
        });
    }
</script>