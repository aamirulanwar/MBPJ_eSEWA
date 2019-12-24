
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>
<form action="/account/time_extension" id="submit_image" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Carian akaun</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. akaun </label>
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

            <!--            <div class="form-group row">-->
            <!--                <label class="col-sm-3 col-form-label">No. fail mesyuarat/rujukan <span class="mandatory">*</span></label>-->
            <!--                <div class="col-sm-5">-->
            <!--                    <input type="input" name="ref_number" class="form-control" placeholder="Nombor Rujukan Permohonan" value="--><?php //echo set_value('ref_number')?><!--">-->
            <!--                    --><?php //echo form_error('ref_number')?>
            <!--                </div>-->
            <!--            </div>-->
            <!--            <div class="form-group row">-->
            <!--                <label class="col-sm-3 col-form-label">Tarikh mesyuarat keputusan <span class="mandatory">*</span></label>-->
            <!--                <div class="col-sm-5">-->
            <!--                    <input type="input" name="date_application" class="form-control date_class" placeholder="Tarikh permohonan" value="--><?php //echo set_value('date_application',date_display(timenow()))?><!--">-->
            <!--                    --><?php //echo form_error('date_application')?>
            <!--                </div>-->
            <!--            </div>-->

        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" name="submit" value="search" class="btn btn-primary pull-right btn-submit">Cari</button>
            </div>
        </div>
    </div>

    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Lanjutan sewaan</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">No. Bilangan Mesyuarat Penuh Majlis/No. Rujukan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="meeting_number" class="form-control" placeholder="" value="<?php echo set_value('meeting_number')?>">
                    <?php echo form_error('meeting_number')?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Tarikh mesyuarat / keputusan <span class="mandatory">*</span></label>
                <div class="col-sm-5">
                    <input type="input" name="date_meeting" class="form-control date_class" placeholder="" value="<?php echo set_value('date_meeting',date_display(timenow()))?>">
                    <?php echo form_error('date_meeting')?>
                </div>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tr>
                        <th>No.</th>
                        <th>Penyewa</th>
                        <th>No. Akaun</th>
                        <th>Jenis / Lokasi permohonan</th>
                        <th>Tarikh sewaan semasa</th>
                        <th>Tarikh sewaan lanjutan</th>
                        <th>Catatan</th>
                        <!--                        <th>Jenis perniagaan</th>-->
                        <!--                        <th>Status / Catatan</th>-->
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
                                    <input type="hidden" name="account_id[]" value="<?php echo $row['ACCOUNT_ID']?>">
                                </td>
                                <td>
                                    <?php echo $row['NAME']?><br>
                                    <?php echo ($row['APPLICANT_TYPE']==APPLICANT_TYPE_INDIVIDUAL)? display_ic_number($row['IC_NUMBER']):$row['COMPANY_REGISTRATION_NUMBER']?>
                                </td>
                                <td><?php echo $row['ACCOUNT_NUMBER']?></td>
                                <td>
                                    <?php echo ' <strong>'.$row['RENTAL_USE_CODE'].'</strong> - '.$row['TYPE_NAME'] ?><br>
                                    <?php echo ' <strong>'.$row['CATEGORY_CODE'].'</strong> - '.$row['CATEGORY_NAME'] ?>
                                </td>
                                <td>
                                    <strong>Jenis&nbsp;bil&nbsp;: <br></strong><?php echo bill_type($row['BILL_TYPE']);?><br><br>
                                    <strong>Tarikh&nbsp;mula&nbsp;: <br></strong><?php echo $row['DATE_START']?><br><br>
                                    <strong>Tarikh&nbsp;tamat&nbsp;: <br></strong><?php echo $row['DATE_END']?>
                                </td>
<!--                                lanjutan masa suggestion-->
                                <?php
                                    $date_start = '';
                                    $date_end   = '';
                                    if($row['BILL_TYPE']==BILL_TYPE_MONTHLY):
                                        if(!empty($row['DATE_END'])):
                                            if(strtotime($row['DATE_END'])>=strtotime(timenow())):
                                                $date_start = date('d-m-Y', strtotime($row['DATE_END'] . ' +1 day'));
                                            else:
                                                $date_start = date('d-m-Y');
                                            endif;
                                        else:
                                            $date_start = date('d-m-Y');
                                        endif;
                                        $date_end = date("d-m-Y", strtotime("Last day of December", strtotime($date_start)));;
                                    elseif ($row['BILL_TYPE']==BILL_TYPE_ANNUALLY):
                                        if(!empty($row['DATE_END'])):
                                            if(strtotime($row['DATE_END'])>=strtotime(timenow())):
                                                $date_start = date('d-m-Y', strtotime($row['DATE_END'] . ' +1 day'));
                                            else:
                                                $date_start = date('d-m-Y');
                                            endif;
                                        else:
                                            $date_start = date('d-m-Y');
                                        endif;

                                        if(date('d-m',strtotime($date_start))=='01-01'):
                                            $date_end = date("d-m-Y", strtotime("Last day of December", strtotime($date_start)));
                                        else:
                                            $date_end = date("d-m-Y", strtotime($date_start. "+ 1 year", strtotime($date_start)));
//                                            $date_end = date("d-m-Y", strtotime($date_end. "+ 1 year"));
                                        endif;
                                    endif;
                                ?>
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" name="status_<?php echo $row['ACCOUNT_ID']?>" value="1" class="form-check-input" id="exampleCheck1">
                                        <label class="form-check-label" for="exampleCheck1"><strong>N/A</strong></label>
                                    </div>
                                    <br>
                                    <strong>Tarikh&nbsp;mula&nbsp;: </strong><input class="form-control date_class" name="date_start_<?php echo $row['ACCOUNT_ID']?>" value="<?php echo set_value('date_start_'.$row['ACCOUNT_ID'],$date_start)?>">
                                    <strong>Tarikh&nbsp;tamat&nbsp;: </strong><input class="form-control date_class" name="date_end_<?php echo $row['ACCOUNT_ID']?>" value="<?php echo set_value('date_end_'.$row['ACCOUNT_ID'],$date_end)?>">
                                </td>
                                <td>
                                    <textarea rows="4" name="remark_<?php echo $row['ACCOUNT_ID']?>" class="form-control"><?php echo set_value('remark_'.$row['ACCOUNT_ID'])?></textarea>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-block btn-info btn-display" target="_blank" href="/account/detail_acc/<?php echo urlEncrypt($row['ACCOUNT_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Lihat </a>
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
<!--                <input type="hidden"  name="upload_name" id="upload_name" class="form-control">-->
                <button type="submit" name="submit" value="submit" class="btn btn-primary pull-right btn-submit">Hantar</button>
            </div>
        </div>
    </div>
</form>
