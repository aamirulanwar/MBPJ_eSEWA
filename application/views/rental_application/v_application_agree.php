
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>
<form action="/rental_application/application_agree" id="submit_image" method="post" class="form-horizontal">
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
                            echo "<option value='-1' selected> SEMUA </option>";
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
            <h3 class="box-title">Setuju terima</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" style="font-size: 9pt;">
                    <tr>
                        <th>No.</th>
                        <th>Tarikh permohonan</th>
                        <th>Pemohon</th>
                        <th>No. Rujukan</th>
                        <th>Jenis / Lokasi permohonan</th>
                        <th>Status permohonan</th>
                        <th>Tarikh&nbsp;keputusan</th>
                        <th>Catatan</th>
                        <th>Lampiran</th>
                        <th class="text-center">Terperinci</th>
                    </tr>
                    <tbody>
                    <?php
                    if($data_list):
                        $i = (uri_segment(3)=='')?0:uri_segment(3);
                        foreach($data_list as $row):
                            $i=$i+1;
                            ?>
                            <tr>
                                <td><?php echo $i?>
                                    <input type="hidden" name="application_id[]" value="<?php echo $row['APPLICATION_ID']?>">
                                </td>
                                <td><?php echo date_display($row['DATE_APPLICATION'])?></td>
                                <td>
                                    <?php echo $row['NAME']?><br>
                                    <?php echo ($row['APPLICANT_TYPE']==APPLICANT_TYPE_INDIVIDUAL)? display_ic_number($row['IC_NUMBER']):$row['COMPANY_REGISTRATION_NUMBER']?>
                                </td>
                                <td><?php echo $row['REF_NUMBER']?></td>
                                <td>
                                    <?php echo ' <strong>'.$row['RENTAL_USE_CODE'].'</strong> - '.$row['TYPE_NAME'] ?><br>
                                    <?php echo ' <strong>'.$row['CATEGORY_CODE'].'</strong> - '.$row['CATEGORY_NAME'] ?>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="status_agree_<?php echo $row['APPLICATION_ID']?>" id="exampleRadios1" value="<?php echo set_value('status_agree_'.$row['APPLICATION_ID'],0)?>">
                                            N/A
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="status_agree_<?php echo $row['APPLICATION_ID']?>" id="exampleRadios2" value="<?php echo set_value('status_agree_'.$row['APPLICATION_ID'],STATUS_AGREE_ACCEPTED,true)?>" checked>
                                            Terima
                                        </label>
                                    </div>
                                    <div class="form-check disabled">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="status_agree_<?php echo $row['APPLICATION_ID']?>" id="exampleRadios3" value="<?php echo set_value('status_agree_'.$row['APPLICATION_ID'],STATUS_AGREE_REJECTED)?>" >
                                            Tolak
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <input type="input" name="date_agree_<?php echo $row['APPLICATION_ID']?>" class="form-control date_class" placeholder="" value="<?php echo set_value('date_agree_'.$row['APPLICATION_ID'],date_display(timenow()))?>">
                                    <?php echo form_error('date_agree_'.$row['APPLICATION_ID'])?>
                                </td>
                                <td>
                                    <textarea rows="4" name="remark_agree_<?php echo $row['APPLICATION_ID']?>" class="form-control"><?php echo set_value('remark_agree_'.$row['APPLICATION_ID'])?></textarea>
                                </td>
                                <td>
                                    <div class="attachment_application" id="attachment_agree_<?php echo $row['APPLICATION_ID']?>_content">
                                        <?php
                                        if(isset($data_attachment['attachment_agree_'.$row['APPLICATION_ID']])):
                                            ?>
                                            <img class="img_upload" src="/<?php echo FILE_UPLOAD_TEMP.'/'.$data_attachment['attachment_agree_'.$row['APPLICATION_ID']]['FILE_NAME']?>">&nbsp;&nbsp;<button onclick="remove_image_upload('attachment_agree_<?php echo $row['APPLICATION_ID']?>',<?php echo $data_attachment['attachment_agree_'.$row['APPLICATION_ID']]['ID']?>)" type="button" class="btn btn-danger">x</i></button>
                                        <?php
                                        else:
                                            ?>
                                            <input type="file" accept="image/png,image/jpeg,image/jpg" onchange="upload_picture('attachment_agree_<?php echo $row['APPLICATION_ID']?>')" name="attachment_agree_<?php echo $row['APPLICATION_ID']?>" id="attachment_agree_<?php echo $row['APPLICATION_ID']?>" class="form-control" style="display:none;">
                                            <label class="btn btn-warning" for="attachment_agree_<?php echo $row['APPLICATION_ID']?>">Muatnaik Lampiran</label>
                                        <?php
                                        endif;
                                        ?>
                                    </div>
                                    <input type="hidden" name="attachment_agree_<?php echo $row['APPLICATION_ID']?>_status" id="attachment_agree_<?php echo $row['APPLICATION_ID']?>_status" class="form-control" value="<?php echo set_value('attachment_agree_'.$row['APPLICATION_ID'].'_status')?>">
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
                <input type="hidden"  name="form_session" value="<?php echo $form_session?>" class="form-control">
                <input type="hidden"  name="upload_name" id="upload_name" class="form-control">
                <button type="submit" name="submit" value="submit" class="btn btn-primary pull-right btn-submit">Hantar</button>
            </div>
        </div>
    </div>
</form>
