<style type="text/css">
    <?php
    if(input_data('level')):
        if(input_data('level')==DEPARTMENT_LEVEL_1):
            echo '#department{display:none}';
        else:
            echo '#department{display:block}';
        endif;
    else:
        if($get_dept_details['level']==DEPARTMENT_LEVEL_1):
            echo '#department{display:none}';
        else:
            echo '#department{display:block}';
        endif;
    endif;
    ?>
</style>

<?php
    notify_msg('edit_department');
    checking_validation(validation_errors());
?>
<form action="/department/edit_department/<?php echo uri_segment(3)?>" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Kemaskini Jabatan/Bahagian</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Peringkat</label>
                <div class="col-sm-5">
                    <select name="level" id="level" onchange="level_jabatan()" class="form-control">
                        <?php echo option_value('1','Jabatan','level',$get_dept_details['DEPT_LEVEL'])?>
                        <?php echo option_value('2','Bahagian','level',$get_dept_details['DEPT_LEVEL'])?>
                    </select>
                </div>
            </div>
            <div id="department">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Jabatan/Bahagian</label>
                    <div class="col-sm-5">
                        <select name="department_parent" class="form-control">
                            <?php
                            foreach($get_department_layer_1 as $department):
                                echo option_value($department['DEPARTMENT_ID'],$department['DEPARTMENT_NAME'],'department_parent',$get_dept_details['PARENT_ID']);
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama</label>
                <div class="col-sm-5">
                    <input type="input" name="name" class="form-control" placeholder="Nama" value="<?php echo set_value('name',$get_dept_details['DEPARTMENT_NAME'])?>">
                    <?php echo form_error('name')?>
                </div>
            </div>
            <div class="checkbox">
                <label class="col-sm-5 pull-right">
                    <?php
                    $active = false;
                    if($get_dept_details['ACTIVE']==STATUS_ACTIVE):
                        $active = true;
                    endif;
                    ?>
                    <input name="status" value="1" <?php echo set_checkbox('status',STATUS_ACTIVE,$active)?> type="checkbox"> Aktif
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
    function level_jabatan() {
        var level = $('#level').val();
        if(level==1){
            $('#department').hide()
        }else{
            $('#department').show()
        }
    }

    level_jabatan();
</script>