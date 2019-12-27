<?php
notify_msg('notify_msg');
?>
<div class="card card-accent-info">
    <div class="card-header">
        <div class="pull-left">
            <strong>Jumlah Rekod : <?php echo $total_result?></strong>
        </div>
<!--                --><?php //if($this->auth->access_view($this->curuser,array(2018))):?>
        <div class="pull-right">
            <a href="/account/kuarters_add" class="add-new"><span style="color: green" class="glyphicon glyphicon-plus-sign"></span> Tambah</a>
        </div>
        <!--        --><?php //endif;?>
    </div>
    <div class="card-body">
        <form class="form-horizontal" action="/account/kuarters_list" method="post">
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
                <div class="col-lg-4" style="margin-top: 20px;margin-bottom: 20px;">
                    <div class="input-group">
                        <input type="text" name="search" value="<?php echo search_default($data_search,'search');?>" class="form-control" placeholder="No. Akaun / Nama / Kad pengenalan">
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
                            </td>
                            <td>
                                <strong><?php echo $row['TYPE_NAME']?></strong><br>
                                <?php echo ' <strong>'.$row['CATEGORY_CODE'].'</strong> - '.$row['CATEGORY_NAME'] ?>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-block btn-primary btn-display" href="/account/kuarters_detais/<?php echo urlEncrypt($row['ACC_KUARTERS_DEFECT_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Terperinci </a><br>
                                <button class="btn btn-block btn-danger btn-display" onclick="delete_modal('/account/delete_kuarters/','<?php echo $row['ACC_KUARTERS_DEFECT_ID']?>')"><span class="glyphicon glyphicon-remove"></span> Padam </button>
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