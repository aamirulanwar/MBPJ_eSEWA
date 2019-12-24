
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>
<form action="/interview/add_interview" id="submit_image" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Carian permohonan</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. rujukan permohonan </label>
                <div class="col-sm-2">
                        <input type="input" name="ref_number_start" class="form-control" placeholder="" value="<?php echo set_value('ref_number_start')?>">
                    <?php echo form_error('ref_number_start')?>
                </div>
                <div class="col-sm-1 text-center">
                    hingga
                </div>
                <div class="col-sm-2">
                    <input type="input" name="ref_number_end" class="form-control" placeholder="" value="<?php echo set_value('ref_number_end')?>">
                    <?php echo form_error('ref_number_end')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Jenis sewaan </label>
                <div class="col-sm-5">
                    <select name="type_id" class="form-control">
                        <?php
                        foreach($asset_type as $row):
                            echo option_value($row['TYPE_ID'],$row['TYPE_NAME'],'type_id');
                        endforeach;
                        ?>
                    </select>
                    <?php echo form_error('type_id')?>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" name="submit" value="search" class="btn btn-primary pull-right btn-submit">Cari</button>
            </div>
        </div>
    </div>

    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Temuduga pemohon</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tajuk <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="interview_name" class="form-control" placeholder="" value="<?php echo $interview_title?>">
                    <?php echo form_error('interview_name')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tarikh temuduga <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="date_interview" class="form-control date_class" placeholder="" value="<?php echo set_value('date_interview',date_display(timenow()))?>">
                    <?php echo form_error('date_interview')?>
                </div>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tr>
                        <th></th>
                        <th>Tarikh permohonan</th>
                        <th>Pemohon</th>
                        <th>No. Rujukan</th>
                        <th>Jenis / Lokasi permohonan</th>
                        <th>Jenis perniagaan</th>
                        <th class="text-center">Terperinci</th>
                    </tr>
                    <tbody>
                    <?php
                    if($data_list):
                        foreach($data_list as $row):
                            ?>
                            <tr>
                                <td>
                                    <label class="form-check-label">
                                        <input type="checkbox" value="<?php echo $row['APPLICATION_ID']?>" name="application_id[]" checked="checked">
                                    </label>
                                </td>
                                <td><?php echo date_display($row['DATE_APPLICATION'])?></td>
                                <td>
                                    <?php echo $row['NAME']?><br>
                                    <?php echo ($row['APPLICANT_TYPE']==APPLICANT_TYPE_INDIVIDUAL)? $row['IC_NUMBER']:$row['COMPANY_REGISTRATION_NUMBER']?>
                                </td>
                                <td><?php echo $row['REF_NUMBER']?></td>
                                <td>
                                    <?php echo ' <strong>'.$row['CATEGORY_CODE'].'</strong> - '.$row['CATEGORY_NAME'] ?>
                                </td>
                                <td>
                                    <?php echo ' <strong>'.$row['RENTAL_USE_CODE'].'</strong> - '.$row['RENTAL_USE_NAME'] ?>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-block btn-info btn-display" target="_blank" href="/rental_application/application_process/<?php echo urlEncrypt($row['APPLICATION_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Lihat </a>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                    else:
                        echo '<tr><td colspan="10" class="text-center"> - Tiada Rekod - </td></tr>';
                    endif;
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" name="submit" value="submit" class="btn btn-primary pull-right btn-submit">Hantar</button>
            </div>
        </div>
    </div>
</form>
