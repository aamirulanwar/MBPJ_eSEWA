<?php
    notify_msg('add_user_group');
    checking_validation(validation_errors());
?>

<!--<div class="card card-accent-info">-->
<!--    <div class="card-header">-->
<!--        <h3 class="box-title">New User Group</h3>-->
<!--    </div>-->
<!--    <div class="card-body">-->
<!--    </div>-->
<!--    <div class="card-footer">-->
<!--        <div class="col-sm-12">-->
<!--            <button type="submit" class="btn btn-primary pull-right btn-submit">Save</button>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<form action="/user_group/add_user_group" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Tambah Kumpulan Pengguna Baru</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="name" value="<?php echo set_value('name')?>" class="form-control" placeholder="Nama">
                    <?php echo form_error('name')?>
                </div>
            </div>
            <div class="form-group row" style="display: none">
                <label class="col-sm-4 col-form-label">Access Level (Reporting) <span class="mandatory">*</span></label>
                <div class="col-sm-6">
                    <select class="form-control" name="level">
                        <option value=""> - Please Select - </option>
                        <?php
                        echo option_value(ACCESS_LEVEL_ALL,'Department/Section','level');
                        echo option_value(ACCESS_LEVEL_OWN,'Own Department/Section','level');
                        ?>
                    </select>
                    <?php echo form_error('level')?>
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
                                    ?>
                                    <div class="col-sm-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="access[]" <?php echo set_checkbox('access',$child['ACCESS_ID'])?> value="<?php echo $child['ACCESS_ID']?>"> <?php echo $child['DESCRIPTION']?>
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
                <button type="submit" class="btn btn-primary pull-right btn-submit">Simpan</button>
            </div>
        </div>
    </div>
</form>