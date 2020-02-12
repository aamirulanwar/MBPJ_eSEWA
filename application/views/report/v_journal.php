
<style>
    th{
        text-align: center;
    }
</style>
 <form method="post" action="/report/journal/">
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="col-form-label">Kod kategori</label>
                    <select name="category_id" id="category_id" class="form-control js-example-basic-single">
                        <option value=""> - Semua - </option>
                        <?php
                        if($data_code_category):
                            foreach ($data_code_category as $row):
                                echo option_value($row['CATEGORY_ID'],$row['CATEGORY_CODE'].' - '.$row['CATEGORY_NAME'],'category_id',search_default($data_search,'category_id'));
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label class="col-form-label">Status akaun</label>
                    <select name="acc_status" class="form-control">
                        <option value=""> - Semua - </option>
                        <?php
                        echo option_value(STATUS_ACCOUNT_ACTIVE,'Aktif','acc_status',search_default($data_search,'acc_status'));
                        echo option_value(STATUS_ACCOUNT_NONACTIVE,'Tidak aktif','acc_status',search_default($data_search,'acc_status'));
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="col-form-label">Tarikh mula</label>
                    <input type="input" name="date_start" id="date_start" class="form-control date_class" placeholder="Tarikh mula" value="<?php echo set_value('date_start',$data_search['date_start'])?>">
                    <label class="no-need-print"><a href="javascript:;" onclick="document.getElementById('date_start').value=''">Kosongkan tarikh mula</a></label>
                </div>
                <div class="col-sm-4">
                    <label class="col-form-label">Tarikh tamat</label>
                    <input type="input" name="date_end" id="date_end" class="form-control date_class" placeholder="Tarikh tamat" value="<?php echo set_value('date_end',$data_search['date_end'])?>">
                    <label class="no-need-print"><a href="javascript:;" onclick="document.getElementById('date_end').value=''">Kosongkan tarikh tamat</a></label>
                </div>
            </div>
<!--            <div class="form-group row">
<!--                <div class="col-sm-4">-->
<!--                    <input type="input" name="date_start" class="form-control date_class" placeholder="Tarikh mula" value="--><?php //echo set_value('date_start',$data_search['date_start'])?><!--">-->
<!--                </div>-->
<!--                <label class="col-sm-2 col-form-label text-center"> hingga </label>-->
<!--                <div class="col-sm-4">-->
<!--                    <input type="input" name="date_end" class="form-control date_class" placeholder="Tarikh tamat" value="--><?php //echo set_value('date_end',$data_search['date_end'])?><!--">-->
<!--                </div>-->
<!--            </div>-->
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right btn-submit">Cari</button>
            </div>
        </div>
    </div>
</form> 
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>

    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Laporan Jurnal Sewaan</h3>
        </div>
        <div class="card-body">
            <div class="pull-right">
                        <button onclick="print_report()" class="btn btn-warning btn-sm pull-right">Print</button>
                    </div>
                    <br>
                    <br>
                    <?php
                    $k                = 0;
                    $journal          = 0;
                    ?>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>BIL</th>
                        <th>JURNAL</th>
                        <th>KOD JURNAL / KETERANGAN</th>
                        <th>NO. AKAUN</th>
                        <th>KOD TRANSAKSI</th>
                        <th>AMAUN</th>
                        <th>STATUS</th>
                        <th>CATATAN</th>
                    </tr>
                </thead>
                <tbody>
                        <?php $i=0; ?>
                        <?php if (count($data_report) > 0): ?>
                        <?php foreach ($data_report as $d): ?>
                        <?php if (($d['STATUS_APPROVAL']==1) or ($d['STATUS_APPROVAL']==2)) : ?>
                            <?php 
                                $k = $k + 1;
                                $i = $i+1;?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?=$d['BILL_NUMBER']?></td>
                                <td><?=$d['JOURNAL_CODE']?> <?=$d['JOURNAL_DESC']?></td>
                                <td><?=$d['ACCOUNT_NUMBER']?></td>
                                <td><?=$d['TR_CODE']?></td>
                                <td>RM <?=number_format(abs($d['AMOUNT']),2);
                                $journal += $d['AMOUNT'];?></td>
                                <td>
                                    <?php if($d['STATUS_APPROVAL']==0): ?>
                                    <span class="badge badge-warning">Pending</span>
                                    <?php elseif($d['STATUS_APPROVAL']==1): ?>
                                    <span class="badge badge-success">Lulus</span>
                                    <?php elseif($d['STATUS_APPROVAL']==2): ?>
                                    <span class="badge badge-danger">Batal</span>
                                    <?php endif; ?>
                                </td>
                                <td><?=$d['REMARK']?></td>
                            </tr>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            
        </div>
         <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>
                                Bilangan Jurnal
                            </th>
                            <th>
                                Jumlah Amaun
                            </th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-center">
                                <?php echo $k?>
                            </td>
                            <td class="text-center">
                                RM <?php echo number_format(abs($journal),2)?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <!-- <?php
                // endif;
            ?> -->
    </div>

   <!--  <script>
    $( document ).ready(function() {
        var type_id = $('#type_id').val();
        get_category_by_type('<?php echo search_default($data_search,'category_id')?>');
    });
</script> -->