<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>
<form action="/profile" id="submit_image" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Kemaskini Profil</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat emel <span class="mandatory">*</span> </label>
                <div class="col-sm-5">
                    <input type="input" name="user_email" class="form-control" value="<?php echo set_value('user_email',$user_details['USER_EMAIL'])?>" placeholder="Alamat emel">
                    <?php echo form_error('user_email')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="user_name" class="form-control" value="<?php echo set_value('user_name',$user_details['USER_NAME'])?>" placeholder="Nama">
                    <?php echo form_error('user_name')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. tel bimbit</label>
                <div class="col-sm-5">
                    <input type="input" name="user_nobimbit" class="form-control" value="<?php echo set_value('user_nobimbit',$user_details['USER_NOBIMBIT'])?>" placeholder="Cth: 60131234567">
                    <?php echo form_error('user_nobimbit')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tukar katalaluan</label>
                <div class="col-sm-5">
                    <button type="button" onclick="clear_data()" data-toggle="modal" data-target="#modal_password" class="btn btn-warning btnn-sm">Tukar katalaluan</button>
                    <div id="status_update_pass" style="color: green"></div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn text-white btn-info pull-right btn-submit">Kemaskini</button>
            </div>
        </div>
    </div>
</form>

<div class="modal" id="modal_password" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tukar Katalaluan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-12 col-form-label">Katalaluan semasa</label>
                        <div class="col-sm-12">
                            <input type="password" class="form-control" name="current_pass" id="current_pass" placeholder="Katalaluan semasa">
                            <div style="color: red" id="current_pass_err"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12 col-form-label">Katalaluan baru</label>
                        <div class="col-sm-12">
                            <input type="password" class="form-control" name="new_pass" id="new_pass" placeholder="Katalaluan baru">
                            <div style="color: red" id="new_pass_err"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12 col-form-label">Sahkan katalaluan baru</label>
                        <div class="col-sm-12">
                            <input type="password" class="form-control" name="retype_new_pass" id="retype_new_pass" placeholder="Sahkan katalaluan baru">
                            <div style="color: red" id="retype_new_pass_err"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="close_modal('modal_password')">Batal</button>
                <button type="button" class="btn btn-primary" onclick="confirm_change_password()">Tukar katalaluan</button>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript">
    function close_modal(val){
        $('#'+val)
        $('#current_pass').val('');
        $('#new_pass').val('');
        $('#retype_new_pass').val('');
    }

    function clear_data()
    {
        $('#current_pass_err').html('');
        $('#new_pass_err').html('');
        $('#retype_new_pass_err').html('');
        $("#modal_password").modal('show');
    }

    function confirm_change_password(){
        var cur_pass = $('#current_pass').val();
        var new_pass = $('#new_pass').val();
        var confirm_pass = $('#retype_new_pass').val();

        var cur_status_update = true;

        if(cur_pass != ''){
            $.ajax({
                url : '/profile/validate_password',
                type : 'post',
                data : {'cur_pass':cur_pass,'type':'current_password'},
                success: function(data)
                {
                    if(data==0){
                        $('#current_pass_err').html('Katalaluan semasa tidak padan.');
                        cur_status_update = false;
                    }else{
                        $('#current_pass_err').html('');

                        if(new_pass != ''){
                            if(new_pass.length < 6){
                                $('#new_pass_err').html('Ruang katalaluan baru mestilah sekurang-kurangnya 8 aksara.');
                                cur_status_update = false;
                            }else{
                                $('#new_pass_err').html('');
                            }
                        }else{
                            $('#new_pass_err').html('Sila masukkan katalaluan baru');
                            cur_status_update = false;
                        }

                        if(confirm_pass != ''){
                            if(confirm_pass != new_pass){
                                $('#retype_new_pass_err').html('Ruang sahkan katalaluan baru tidak sama dengan ruang katalaluan baru.');
                                cur_status_update = false;
                            }else{
                                $('#retype_new_pass_err').html('');
                            }
                        }else{
                            $('#retype_new_pass_err').html('Sila masukkan sahkan katalaluan baru');
                            cur_status_update = false;
                        }

                        if(cur_status_update){
                            $.ajax({
                                url : '/profile/update_password',
                                type : 'post',
                                data : {'new_pass':new_pass},
                                success: function(data)
                                {
                                    if(data==0){
                                        $('#status_update_pass').html('Sila cuba sebentar lagi');
                                    }else{
                                        $('#status_update_pass').html('Katalaluan berjaya dikemaskini.');
                                    }
                                    $('#current_pass').val('');
                                    $('#new_pass').val('');
                                    $('#retype_new_pass').val('');
                                    $('#modal_password').modal('hide');
                                    setTimeout(function(){
                                        $('#status_update_pass').html('');
                                    }, 2500);
                                }
                            });
                        }
                    }
                }
            });
        }else{
            $('#current_pass_err').html('Sila masukkan katalaluan semasa');
            cur_status_update = false;
        }
    }
</script>