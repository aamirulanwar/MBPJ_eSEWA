<?php
notify_msg('notify_msg');
?>
<div class="card card-accent-info">
    <div class="card-header">
        <div class="pull-left">
            <strong>Jumlah Rekod : <?php echo $total_result?></strong>
        </div>
        <!--        --><?php //if($this->auth->access_view($this->curuser,array(2018))):?>
<!--        <div class="pull-right">-->
<!--            <a href="/asset/add_asset" class="add-new"><span style="color: green" class="glyphicon glyphicon-plus-sign"></span> Tambah</a>-->
<!--        </div>-->
        <!--        --><?php //endif;?>
    </div>
    <div class="card-body">
        <form class="form-horizontal" action="/rental_application/application" method="post">
            <div class="row">
                <div class="col-lg-3" style="margin-top: 20px;margin-bottom: 20px;">
                    <select name="type_id" class="form-control">
                        <option value=""> - Jenis harta - </option>
                        <?php
                            if($data_type):
                                foreach ($data_type as $row):
                                    echo option_value($row['TYPE_ID'],$row['TYPE_NAME'],'type_id',search_default($data_search,'type_id'));
                                endforeach;
                            endif;
                        ?>
                    </select>
                </div>
                <div class="col-lg-3" style="margin-top: 20px;margin-bottom: 20px;">
                    <select name="status_application" class="form-control">
                        <option value=""> - Status permohonan - </option>
                        <?php
                            echo option_value(STATUS_APPLICATION_NEW,'Baru','status_application',search_default($data_search,'status_application'));
                            echo option_value(STATUS_APPLICATION_APPROVED,'Diterima','status_application',search_default($data_search,'status_application'));
                            echo option_value(STATUS_APPLICATION_REJECTED,'Ditolak','status_application',search_default($data_search,'status_application'));
                            echo option_value(STATUS_APPLICATION_KIV,'Simpanan','status_application',search_default($data_search,'status_application'));
                        ?>
                    </select>
                </div>
                <div class="col-lg-3" style="margin-top: 20px;margin-bottom: 20px;">
                    <select name="status_create_account" class="form-control">
                        <option value=""> - Status pendaftran - </option>
                        <?php
                        echo option_value('1','Ya','status_create_account',search_default($data_search,'status_create_account'));
                        echo option_value('0','Tidak','status_create_account',search_default($data_search,'status_create_account'));
                        ?>
                    </select>
                </div>
                <div class="col-lg-3" style="margin-top: 20px;margin-bottom: 20px;">
                    <div class="input-group">
                        <input type="text" name="ref_number" value="<?php echo search_default($data_search,'ref_number');?>" class="form-control" placeholder="Pemohon">
                        <span class="input-group-btn">
                        <button class="btn btn-success btn-square" type="submit">Cari</button>
                    </span>
                    </div>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <tr>
                    <th>No.</th>
                    <th>Tarikh permohonan</th>
                    <th>Pemohon</th>
                    <th>No. Rujukan</th>
                    <th>Jenis / Lokasi permohonan</th>
                    <th>Jenis perniagaan</th>
                    <th>Status / Catatan</th>
                    <th class="text-center">Tindakan</th>
                </tr>
                <tbody>
                <?php
                if($data_list):
                    $i = (uri_segment(3)=='')?0:uri_segment(3);
                    foreach($data_list as $row):
                        $i=$i+1;
                        ?>
                        <tr>
                            <td><?php echo $i?></td>
                            <td><?php echo date_display($row['DATE_APPLICATION'])?></td>
                            <td>
                                <?php echo $row['NAME']?><br>
                                <?php echo ($row['APPLICANT_TYPE']==APPLICANT_TYPE_INDIVIDUAL)? display_ic_number($row['IC_NUMBER']):$row['COMPANY_REGISTRATION_NUMBER']?>
                            </td>
                            <td><?php echo $row['REF_NUMBER']?></td>
                            <td>
                                <strong><?php echo $row['TYPE_NAME'] ?></strong><br>
                                <?php echo ' <strong>'.$row['CATEGORY_CODE'].'</strong> - '.$row['CATEGORY_NAME'] ?>
                            </td>
                            <td>
                                <?php echo ' <strong>'.$row['RENTAL_USE_CODE'].'</strong> - '.$row['RENTAL_USE_NAME'] ?>
                            </td>
                            <td>
                                <strong>Status permohonan : </strong>
                                <?php
                                    echo status_application($row['STATUS_APPLICATION']).'<br>';
                                    if($row['REMARK']):
                                        echo 'Catatan : '.$row['REMARK'];
                                    endif;
                                ?>
                                <br>

                                <?php
                                    if($row['STATUS_APPLICATION']==STATUS_APPLICATION_APPROVED):
                                ?>

                                <strong>Status setuju terima : </strong>
                                <?php
                                    echo status_application_applicant($row['STATUS_AGREE']).'<br>';
                                    if($row['REMARK_AGREE']):
                                        echo 'Catatan : '.$row['REMARK_AGREE'];
                                    endif;
                                ?>

                                <?php
                                    endif;
                                ?>
                            </td>
                            <td class="text-center">
                                <?php
                                    if($row['STATUS_APPLICATION']== STATUS_APPLICATION_NEW || $row['STATUS_APPLICATION']== STATUS_APPLICATION_KIV || ($row['STATUS_APPLICATION']== STATUS_APPLICATION_APPROVED && $row['STATUS_AGREE']==STATUS_AGREE_DEFAULT)):
                                        if($this->auth->access_view($this->curuser,array(4003))):?>
                                            <a class="btn btn-block btn-primary btn-display" href="/rental_application/application_process/<?php echo urlEncrypt($row['APPLICATION_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Proses </a>
                                            <br>
                                            <i class="icons font-xl mt-5 cui-print"></i><br>
                                            <a class="" target="_blank" href="/rental_application/application_process/<?php echo urlEncrypt($row['APPLICATION_ID'])?>/print"><span class="glyphicon glyphicon-edit"></span> Print </a><br>
                                        <?php endif;
                                    elseif ($row['STATUS_APPLICATION']== STATUS_APPLICATION_APPROVED && $row['STATUS_AGREE']==STATUS_AGREE_ACCEPTED && $row['STATUS_CREATE_ACCOUNT']==STATUS_CREATE_ACCOUNT_NO):
                                        if($this->auth->access_view($this->curuser,array(4004))):?>
                                    <a class="btn btn-block btn-primary btn-display" href="/account/create_acc/<?php echo urlEncrypt($row['APPLICATION_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Daftar akaun </a><br>
                                    <?php endif;
                                    else:
                                        ?>
                                            <a class="btn btn-block btn-secondary btn-display" href="/rental_application/application_process/<?php echo urlEncrypt($row['APPLICATION_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Terperinci </a><br>
                                        <?php
                                    endif;

                                    if($row['STATUS_APPLICATION']!= STATUS_APPLICATION_REJECTED):
                                        if($row['FORM_TYPE']==1):
                                        ?>
                                            <i class="icons font-xl mt-5 cui-envelope-closed"></i><div><a href="/rental_application/doc_offer_letter/<?php echo urlEncrypt($row['APPLICATION_ID'])?>" target="_blank">Surat Tawaran</div></a>
                                            <i class="icons font-xl mt-5 cui-task"></i><div><a href="/rental_application/doc_acceptance_letter/<?php echo urlEncrypt($row['APPLICATION_ID'])?>" target="_blank">Surat Setuju Terima</div></a>
                                        <?php
                                        elseif($row['FORM_TYPE']==4):
                                        ?>
                                            <i class="icons font-xl mt-5 cui-envelope-closed"></i><div><a href="/rental_application/doc_offer_letter/<?php echo urlEncrypt($row['APPLICATION_ID'])?>" target="_blank">Kertas Pertimbangan Permohonan</div></a>
                                            <i class="icons font-xl mt-5 cui-task"></i><div><a href="/rental_application/doc_acceptance_letter/<?php echo urlEncrypt($row['APPLICATION_ID'])?>" target="_blank">Surat Setuju Terima</div></a>
                                        <?php
                                        endif;
                                    endif;
                                ?>
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
        <div class="pull-right">
            <?php echo paging_link(); ?>
        </div>
    </div>
</div>