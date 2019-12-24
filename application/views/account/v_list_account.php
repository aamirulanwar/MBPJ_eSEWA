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
        <form class="form-horizontal" action="/account/account_list" method="post">
            <div class="row">
<!--                <div class="col-lg-3" style="margin-top: 20px;margin-bottom: 20px;">-->
<!--                    <select name="type_id" class="form-control">-->
<!--                        <option value=""> - Semua - </option>-->
<!--                        --><?php
//                        if($data_type):
//                            foreach ($data_type as $row):
//                                echo option_value($row['TYPE_ID'],$row['TYPE_NAME'],'type_id',search_default($data_search,'type_id'));
//                            endforeach;
//                        endif;
//                        ?>
<!--                    </select>-->
<!--                </div>-->
                <div class="col-lg-3" style="margin-top: 20px;margin-bottom: 20px;">
                    <select name="category_id" class="form-control js-example-basic-single">
                        <option value=""> - Semua - </option>
                        <?php
                        if($data_category):
                            foreach ($data_category as $row):
                                echo option_value($row['CATEGORY_ID'],$row['CATEGORY_CODE'].' - '.$row['CATEGORY_NAME'],'category_id',search_default($data_search,'category_id'));
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
                <div class="col-lg-3" style="margin-top: 20px;margin-bottom: 20px;">
                    <select name="almost_expired" class="form-control">
                        <option value=""> - Status akaun - </option>
                        <?php
                        echo option_value(1,'Akaun hampir tamat','almost_expired',search_default($data_search,'almost_expired'));
                        echo option_value(2,'Akaun aktif','almost_expired',search_default($data_search,'almost_expired'));
                        echo option_value(3,'Akaun tidak aktif','almost_expired',search_default($data_search,'almost_expired'));
                        ?>
                    </select>
<!--                    <div class="form-check">-->
<!--                        <input name="almost_expired" class="form-check-input" --><?php //echo set_checkbox('almost_expired',search_default($data_search,'almost_expired'))?><!-- type="checkbox" value="1">-->
<!--                        <label class="form-check-label" for="defaultCheck1">-->
<!--                            Akaun hampir tamat-->
<!--                        </label>-->
<!--                    </div>-->
                </div>
                <div class="col-lg-4" style="margin-top: 20px;margin-bottom: 20px;">
                    <div class="input-group">
                        <input type="text" name="account_number" value="<?php echo search_default($data_search,'account_number');?>" class="form-control" placeholder="No. Akaun">
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
                    <th>Tarikh</th>
                    <th>Penyewa</th>
                    <th>No. Akaun</th>
                    <th>Jenis / Lokasi permohonan</th>
                    <th>Jenis perniagaan</th>
                    <th>Tempoh sewaan</th>
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
                            <td><?php echo date_display($row['DT_CREATE_ACC'])?></td>
                            <td>
                                <?php echo $row['NAME']?><br>
                                <?php echo ($row['APPLICANT_TYPE']==APPLICANT_TYPE_INDIVIDUAL)? display_ic_number($row['IC_NUMBER']):$row['COMPANY_REGISTRATION_NUMBER']?>
                            </td>
                            <td>
                                <strong><?php echo $row['ACCOUNT_NUMBER']?></strong><br>
                                <?php get_status_active($row['STATUS_ACC'])?>
                            </td>
                            <td>
                                <strong><?php echo $row['TYPE_NAME']?></strong><br>
                                <?php echo ' <strong>'.$row['CATEGORY_CODE'].'</strong> - '.$row['CATEGORY_NAME'] ?>
                            </td>
                            <td><?php echo ' <strong>'.$row['RENTAL_USE_CODE'].'</strong> - '.$row['RENTAL_USE_NAME'] ?>
                            </td>
                            <td class="text-left">
                                <strong>Tarikh&nbsp;mula </strong><br>
                                <?php
                                    echo date_display($row['DATE_START']);
                                ?>
                                <br>

                                <strong>Tarikh&nbsp;tamat </strong><br>
                                <?php
                                    echo date_display($row['DATE_END']);
                                ?>
                                <br>

                                <strong>Tempoh </strong><br>
                                <?php
                                    echo $row['RENTAL_DURATION'].' Bulan';
                                ?>

                            </td>
                            <td class="text-center">
                                <?php if($this->auth->access_view($this->curuser,array(5004))):?>
                                <a class="btn btn-block btn-primary btn-display" href="/account/update_acc/<?php echo urlEncrypt($row['ACCOUNT_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Kemaskini </a><br>
                                <?php endif;?>

                                <?php
                                if($row['FORM_TYPE']==1 || $row['FORM_TYPE']==2):
                                ?>
                                    <i class="icons font-xl mt-5 cui-file"></i><div><a href="/account/doc_agreement/<?php echo urlEncrypt($row['ACCOUNT_ID'])?>" target="_blank">Dokumen Perjanjian</div></a>
                                <?php
                                elseif($row['FORM_TYPE']==4):
                                ?>
                                    <i class="icons font-xl mt-5 cui-file"></i><div><a href="/account/doc_agreement/<?php echo urlEncrypt($row['ACCOUNT_ID'])?>" target="_blank">Dokumen Perjanjian</div></a>
                                    <?php
                                    if($row['STATUS_ACC']==STATUS_ACCOUNT_ACTIVE):
                                    ?>
                                        <i class="icons font-xl mt-5 cui-lock-unlocked"></i><div><a href="/account/doc_quarters/<?php echo urlEncrypt($row['ACCOUNT_ID'])?>/1" target="_blank">Sijil Akuan Masuk Rumah</div></a>
                                    <?php
                                    else:
                                    ?>
                                        <i class="icons font-xl mt-5 cui-lock-locked"></i><div><a href="/account/doc_quarters/<?php echo urlEncrypt($row['ACCOUNT_ID'])?>/2" target="_blank">Sijil Akuan Keluar Rumah</div></a>
                                    <?php
                                    endif;
                                endif;
                                ?>
								<i class="icons font-xl mt-5 cui-file"></i><div><a href="/account/doc_signature/<?php echo urlEncrypt($row['ACCOUNT_ID'])?>" target="_blank">Dokumen Tandatangan</div></a>
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