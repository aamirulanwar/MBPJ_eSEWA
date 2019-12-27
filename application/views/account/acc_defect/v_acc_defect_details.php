
<?php
    notify_msg('notify_msg');
//    checking_validation(validation_errors());
?>
<form action="/account/kuarters_detais/<?php echo uri_segment(3)?>" method="post" class="form-horizontal" enctype="multipart/form-data">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Terperinci Kerosakan Kuarters</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tarikh </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo date_display($get_details['DATE_ADDED'])?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. akaun sewaan </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo $get_details['ACCOUNT_NUMBER']?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Penyewa </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo $get_details['NAME']?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No Kad Pengenalan </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo $get_details['IC_NUMBER']?></p>
                </div>
            </div>
            <h2 style="text-decoration: underline">Senarai kerosakan</h2>
            <?php
                if($get_list):
                    echo '<div class="table-responsive">';
                    echo '<table class="table table-hover table-bordered">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Butir-butir</th>';
                    echo '<th>Kategori</th>';
                    echo '<th>Lampiran</th>';
                    echo '<th>Tindakan</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    foreach ($get_list as $row):
                        echo '<tr>';
                        echo '<td>'.nl2br($row['INFO']).'</td>';
                        if($row['CATEGORY']==1):
                            $cat = 'Elektrik';
                        else:
                            $cat = 'Sivil';
                        endif;
                        echo '<td>'.$cat.'</td>';
                        echo '<td><a target="_blank" href="'.$row['ATTACHMENT_FILE'].'">'.$row['FILE_NAME'].'</a></td>';
                        echo '<td><button type="button" class="btn btn-block btn-danger btn-display" onclick="delete_modal(\'/account/delete_item/\',\''.$row['ID_LIST'].'\')"><span class="glyphicon glyphicon-remove"></span> Padam </button></td>';
                        echo '</tr>';
                    endforeach;
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                endif;
            ?>
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