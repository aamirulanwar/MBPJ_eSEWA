<?php
    notify_msg('edit_user_group');
    checking_validation(validation_errors());
?>
<form action="/user_group/edit_user_group/<?php echo uri_segment(3)?>" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Kemaskini Kumpulan Pengguna</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-4 control-label">Nama <span class="mandatory">*</span></label>
                <div class="col-sm-6">
                    <input type="input" name="name" class="form-control" value="<?php echo set_value('name',$user_group_details['USER_GROUP_DESC'])?>" placeholder="Nama">
                    <?php echo form_error('name')?>
                </div>
            </div>
            <div class="form-group row" style="display: none">
                <label class="col-sm-4 control-label">Access Level (Reporting)<span class="mandatory">*</span></label>
                <div class="col-sm-6">
                    <select class="form-control" name="level">
                        <option value=""> - Sila Pilih - </option>
                        <?php
                        echo option_value(ACCESS_LEVEL_ALL,'Department/Section','level',$user_group_details['ACCESS_LEVEL']);
                        echo option_value(ACCESS_LEVEL_OWN,'Own Department/Section','level',$user_group_details['ACCESS_LEVEL']);
                        ?>
                    </select>
                    <?php echo form_error('level')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 control-label">Status</label>
                <div class="col-sm-6">
                    <div class="checkbox">
                        <label>
                            <?php
                            $active_status = false;
                            if($user_group_details['ACTIVE']==STATUS_ACTIVE):
                                $active_status = true;
                            endif;
                            ?>
                            <input type="checkbox" <?php echo set_checkbox('status',STATUS_ACTIVE,$active_status)?> name="status" value="1"> Aktif
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-sm-10">
                <?php
                foreach($file_access_parent as $access):
                    $child_access = $this->m_access_file->get_child_access($access['PARENT_ID']);
                    if($child_access):
                        ?>
                        <div class="card border-primary">
                            <div class="card-header"><strong><?php echo $access['DESCRIPTION']?></strong></div>
                            <div class="card-body">
                                <?php
                                foreach($child_access as $child):
                                    $file_access = false;
                                    if(in_array($child['ACCESS_ID'],$file_access_arr)):
                                        $file_access = true;
                                    endif;
                                    ?>
                                    <div class="col-sm-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="access[]" <?php echo set_checkbox('access',$child['ACCESS_ID'],$file_access)?> value="<?php echo $child['ACCESS_ID']?>"> <?php echo $child['DESCRIPTION']?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php
                                endforeach;
                                ?>
                            </div>
                        </div>
                        <?php
                    endif;
                endforeach;
                ?>
                <?php echo form_error('access')?>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right btn-submit">Kemaskini</button>
            </div>
        </div>
    </div>
</form>