<?php
notify_msg('notify_msg');
?>
<div class="card card-accent-info">
    <div class="card-header">
        <div class="pull-left">
            <strong>Carian resit</strong>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                No akaun : <strong><?php echo $data_account['ACCOUNT_NUMBER']?></strong><br>
                Nama : <strong><?php echo $data_account['NAME']?></strong><br>
                <?php
                if($data_account['APPLICANT_TYPE']==APPLICANT_TYPE_COMPANY):
                    echo 'No. syarikat : <strong>'.$data_account['COMPANY_REGISTRATION_NUMBER'].'</strong><br>';
                else:
                    echo 'No. ic : <strong>'.$data_account['IC_NUMBER'].'</strong><br>';
                endif;
                ?>
                Jenis / Kod kategori : <strong><?php echo $data_account['TYPE_NAME']?> (<?php echo $data_account['CATEGORY_CODE']?> - <?php echo $data_account['CATEGORY_NAME']?>)</strong>
                <br>
                <br>
            </div>
            <div class="form-group col-sm-4">
                <label>No. resit</label>
                <input type="text" class="form-control">
            </div>
            <div class="form-group col-sm-4">
                <label>Bulan</label>
                <input type="text" class="form-control">
            </div>
            <div class="form-group col-sm-4">
                <label>Tahun</label>
                <input type="text" class="form-control">
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary pull-right btn-submit">Cari</button>
    </div>
</div>

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
<!--        <form class="form-horizontal" action="/user" method="post">-->
<!--            <div class="col-lg-4 row" style="margin-top: 20px;margin-bottom: 20px;">-->
<!--                <div class="input-group">-->
<!--                    <input type="text" name="search" value="--><?php //echo (is_array($data_search))?$data_search['search']:'';?><!--" class="form-control" placeholder="">-->
<!--                    <span class="input-group-btn">-->
<!--                    <button class="btn btn-success btn-square" type="submit">Carian Pengguna</button>-->
<!--                </span>-->
<!--                </div>-->
<!--            </div><!-- /input-group -->
<!--        </form>-->
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <tr>
                    <th>No.</th>
                    <th>Tarikh</th>
                    <th>No resit</th>
                    <th>Bulan/Tahun</th>
                    <th style="text-align: right">Jumlah (RM)</th>
<!--                    <th>Terperinci</th>-->
                </tr>
                <tbody>
                <?php
                if($data_list):
                    $i = (uri_segment(4)=='')?0:uri_segment(4);
                    foreach($data_list as $row):
                        $i=$i+1;
                        ?>
                        <tr>
                            <td><?php echo $i?></td>
                            <td><?php echo date_display($row['DT_ADDED'])?></td>
                            <td>
                                <a href="/bill/statement/<?php echo urlEncrypt($row['BILL_ID'])?>" target="_blank"><?php echo $row['BILL_NUMBER']?><br>
                            </td>
                            <td>
                                <?php echo $row['BILL_MONTH']?>/<?php echo $row['BILL_YEAR']?>
                            </td>
                            <td style="text-align: right"><?php echo num($row['TOTAL_AMOUNT'])?></td>
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