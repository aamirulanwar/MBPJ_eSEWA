<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>

<form action="/journal/search" id="submit_image" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <div class="pull-left">
                <strong>Carian</strong>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-4">
                    <label class="col-form-label">No. akaun</label>
                    <input type="text" class="form-control" value="<?php echo set_value('account_number')?>" name="account_number" placeholder="No. akaun">
                </div>
                <div class="form-group col-4">
                    <label class="col-form-label">No. bill/ No. resit</label>
                    <input type="text" class="form-control"  value="<?php echo set_value('bill_number')?>" name="bill_number" id="bill_number" placeholder="No. bill/ No. resit">
                </div>
                <div class="form-group col-4">
                    <label class="col-form-label">Bil / resit</label>
                    <select class="form-control" name="bill_category">
                        <?php echo option_value('','- Semua -','bill_category')?>
                        <?php echo option_value('B','Bil','bill_category')?>
                        <?php echo option_value('R','Resit','bill_category')?>
                    </select>
                </div>
                <div class="form-group col-4">
                    <label class="col-form-label">Tarikh mula</label>
                    <input type="text" class="form-control date_class" value="<?php echo set_value('date_start')?>" name="date_start" id="date_start" placeholder="Tarikh mula">
                </div>
                <div class="form-group col-4">
                    <label class="col-form-label">Tarikh akhir</label>
                    <input type="text" class="form-control date_class" value="<?php echo set_value('date_end')?>" name="date_end" id="date_end" placeholder="Tarikh akhir">
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
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <tr>
                    <th>No.</th>
                    <th>Tarikh</th>
                    <th>No. akaun</th>
<!--                    <th>No. bill / resit</th>-->
                    <th>Bulan / Tahun</th>
                    <th>Jenis</th>
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
                            <td><?php echo date_display($row['DT_ADDED'])?></td>
                            <td>
                                <strong><?php echo $row['ACCOUNT_NUMBER']?></strong>
                            </td>
<!--                            <td>-->
<!--                                <strong>--><?php //echo $row['BILL_NUMBER']?><!--</strong><br>-->
<!--                            </td>-->
                            <td>
                                <?php
                                echo $row['BILL_MONTH'].'/'.$row['BILL_YEAR']
                                ?>
                            </td>
                            <td>
                                <?php
                                    if($row['BILL_CATEGORY']=='B'):
                                        echo 'Bil';
                                    elseif($row['BILL_CATEGORY']=='R'):
                                        echo 'Resit';
                                    endif;
                                ?>
                            </td>
                            <td class="text-center">
                                <!--                        --><?php //if($this->auth->access_view($this->curuser,array(2019))):?>
                                <a class="btn btn-block btn-primary btn-display" href="/journal/generate_current_journal/<?php echo urlEncrypt($row['BILL_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Pelarasan </a>

                                <!--                        --><?php //endif;?>
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