<style type="text/css">
    <?php
    if(input_data('level')):
        if(input_data('level')==DEPARTMENT_LEVEL_1):
            echo '#department{display:none}';
        else:
            echo '#department{display:block}';
        endif;
    else:
        echo '#department{display:none}';
    endif;
    ?>
</style>

<?php
    notify_msg('add_department');
    checking_validation(validation_errors());
?>
<form action="/department/add_department/" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Tambah Jabatan/Bahagian Baru</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Peringkat</label>
                <div class="col-sm-5">
                    <select name="level" onchange="(this.value==1)?$('#department').hide():$('#department').show()" class="form-control">
                        <?php echo option_value('1','Jabatan','lavel',input_data('level'))?>
                        <?php echo option_value('2','Bahagian','lavel',input_data('level'))?>
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
                                echo option_value($department['DEPARTMENT_ID'],$department['DEPARTMENT_NAME'],'department_parent',input_data('department_parent'));
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama Jabatan/Bahagian <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="name" class="form-control" placeholder="Nama Jabatan/Bahagian" value="<?php echo set_value('name')?>">
                    <?php echo form_error('name')?>
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