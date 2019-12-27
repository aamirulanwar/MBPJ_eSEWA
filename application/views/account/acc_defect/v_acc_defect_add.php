
<?php
    notify_msg('notify_msg');
    checking_validation(validation_errors());
?>
<form action="/account/kuarters_add/" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Tambah Laporan Kerosakan Kuarters</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tarikh <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="date_added" class="form-control date_class" placeholder="Tarikh" value="<?php echo set_value('date_added',date('d M Y'))?>">
                    <?php echo form_error('date_added')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. akaun sewaan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <select class="form-control js-example-basic-single" id="acc_number" name="acc_number">
                        <?php
                            echo option_value(' ','- Sila pilih -','acc_number');
                            foreach ($kuarters as $row):
                                echo option_value($row['ACCOUNT_ID'],$row['ACCOUNT_NUMBER'].' - '.$row['NAME'],'acc_number');
                            endforeach;
                        ?>
                    </select>
                    <?php echo form_error('acc_number')?>
                </div>
            </div>
            <h2 style="text-decoration: underline">Senarai kerosakan</h2>
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Butir - butir</label>-->
<!--                <div class="col-sm-5">-->
<!--                    <textarea name="info[]" rows="5" class="form-control" placeholder="Butir - butir"></textarea>-->
<!--                    --><?php //echo form_error('info[]')?>
<!--                </div>-->
<!--            </div>-->
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Kategori</label>-->
<!--                <div class="col-sm-5">-->
<!--                    <div class="form-check form-check-inline">-->
<!--                        <label class="form-check-label">-->
<!--                            <input class="form-check-input" type="radio"  --><?php //echo set_radio('category[]',1,true)?><!-- name="category[]" value="1"> Elektrik-->
<!--                        </label>-->
<!--                    </div>-->
<!--                    <div class="form-check form-check-inline">-->
<!--                        <label class="form-check-label">-->
<!--                            <input class="form-check-input" type="radio" --><?php //echo set_radio('category[]',2)?><!-- name="category[]" value="2"> Sivil-->
<!--                        </label>-->
<!--                    </div>-->
<!--                    --><?php //echo form_error('category[]')?>
<!--                </div>-->
<!--            </div>-->
<!--            <div class="form-group row">-->
<!--                <label class="col-sm-3 col-form-label">Lampiran</label>-->
<!--                <div class="col-sm-5">-->
<!--                    <input type="file" name="file_ext[]" class="form-control" placeholder="" value="--><?php //echo set_value('file_ext')?><!--">-->
<!--                    --><?php //echo form_error('file_ext')?>
<!--                </div>-->
<!--            </div>-->
            <div id="list_defect">
            </div>
            <button onclick="add_list()" class="btn btn-warning" type="button">Tambah senarai kerosakan</button>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right btn-submit">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    var no = 0;
    function add_list() {
        var acc_number = $('#acc_number').val();
        if(acc_number==' '){
            alert('Sila pilih no. akaun dahulu');
        }else{
            $('#list_defect').append(
                '<hr>\n'+
                '<div class="form-group row">\n'+
                '                <label class="col-sm-3 col-form-label">Butir - butir</label>\n'+
                '                <div class="col-sm-5">\n'+
                '                    <textarea name="info[]" rows="5" class="form-control" placeholder="Butir - butir"></textarea>\n'+
                '                </div>\n'+
                '            </div>\n'+
                '            <div class="form-group row">\n'+
                '                <label class="col-sm-3 col-form-label">Kategori</label>\n'+
                '                <div class="col-sm-5">\n' +
                '<select class="form-control" name="category[]"><option value="1">Elektirk</option><option value="2">Sivil</option></select>'+
                '                </div>\n'+
                '            </div>\n'+
                '            <div class="form-group row">\n'+
                '                <label class="col-sm-3 col-form-label">Lampiran</label>\n'+
                '                <div class="col-sm-5">\n'+
                '                    <input type="file" name="upload_name_'+no+'" class="form-control" placeholder="" value="">\n'+
                '                </div>\n'+
                '            </div>'
            )

            no = no+1;

            $("#acc_number option[value=' ']").remove();
        }
    }
</script>