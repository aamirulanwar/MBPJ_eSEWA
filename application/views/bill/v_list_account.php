<?php
notify_msg('notify_msg');
?>

<form action="/bill/account_list" id="submit_image" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <div class="pull-left">
                <strong>Carian Akaun</strong>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-4">
                    <label class="col-form-label" for="exampleFormControlInput1">No. akaun</label>
                    <input type="text" class="form-control" name="account_number" value="<?php echo set_value('account_number',search_default($data_search,'account_number'))?>" placeholder="No. akaun">
                </div>
                <div class="form-group col-4">
                    <label class="col-form-label" for="exampleFormControlInput1">No. kad pengenalan / No. pendaftaran syarikat</label>
                    <input type="text" class="form-control" name="ic_number_company_reg" value="<?php echo set_value('ic_number_company_reg',search_default($data_search,'ic_number_company_reg'))?>" placeholder="No. kad pengenalan / No. pendaftaran syarikat">
                </div>
                <div class="form-group col-4">
                    <label class="col-form-label" for="exampleFormControlInput1">Nama penyewa</label>
                    <input type="text" class="form-control" name="name" value="<?php echo set_value('name',search_default($data_search,'name'))?>" placeholder="Nama penyewa">
                </div>
                <div class="form-group col-4">
                    <label class="col-form-label" for="exampleFormControlInput1">Status akaun</label>
                    <select name="almost_expired" class="form-control">
                        <option value=""> - Semua - </option>
                        <?php
                        echo option_value(2,'Akaun aktif','almost_expired',search_default($data_search,'almost_expired'));
                        echo option_value(3,'Akaun tidak aktif','almost_expired',search_default($data_search,'almost_expired'));
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary pull-right btn-submit">Cari</button>
        </div>
    </div>
</form>

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
                    <th>Penyewa</th>
                    <th>No. akaun</th>
                    <th>Jenis / Kod kategori</th>
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
                                <?php echo ($row['APPLICANT_TYPE']==APPLICANT_TYPE_INDIVIDUAL)? $row['IC_NUMBER']:$row['COMPANY_REGISTRATION_NUMBER']?>
                            </td>
                            <td>
                                <strong><?php echo $row['ACCOUNT_NUMBER']?></strong><br>
                                <?php get_status_active($row['STATUS_ACC'])?>
                            </td>
                            <td>
                                <?php echo '<strong>'.$row['TYPE_NAME'].'</strong>' ?><br>
                                <?php echo ' <strong>'.$row['CATEGORY_CODE'].'</strong> - '.$row['CATEGORY_NAME'] ?>
                            </td>
                            <td>
                                <?php echo ' <strong>'.$row['RENTAL_USE_CODE'].'</strong> - '.$row['RENTAL_USE_NAME'] ?>
                            </td>
                            <td>
                                <strong>Tarikh&nbsp;mula&nbsp;: </strong>
                                <?php
                                    echo date_display($row['DATE_START']);
                                ?>
                                <br>

                                <strong>Tarikh&nbsp;tamat&nbsp;: </strong>
                                <?php
                                    echo date_display($row['DATE_END']);
                                ?>
                                <br>

                                <strong>Tempoh&nbsp;: </strong>
                                <?php
                                    echo $row['RENTAL_DURATION'].' Bulan';
                                ?>

                            </td>
                            <td class="text-center">
                                <?php if($this->auth->access_view($this->curuser,array(6002))):?>
                                <a class="btn btn-block btn-primary btn-display" href="/bill/generate_current_bill/<?php echo urlEncrypt($row['ACCOUNT_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Maklumat Bil </a>
                                <?php endif;?>
                                <?php if($this->auth->access_view($this->curuser,array(6003))):?>
                                <a class="btn btn-block btn-success btn-display" href="/bill/current_bill/<?php echo urlEncrypt($row['ACCOUNT_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Bil semasa </a>
                                <?php endif;?>
                                <?php if($this->auth->access_view($this->curuser,array(6004))):?>
                                <div><a href="/bill/bill_history/<?php echo urlEncrypt($row['ACCOUNT_ID'])?>/<?php echo $row['ACCOUNT_NUMBER']?>/?>" >Penyata</div></a>
                                <?php endif;?>
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