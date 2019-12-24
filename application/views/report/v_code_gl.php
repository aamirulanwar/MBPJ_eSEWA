<style>
    th{
        text-align: center;
    }
</style>
<form method="post" action="/report/code_gl/">
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="col-form-label">Jenis harta</label>
                    <select onchange="get_category_by_type('')" name="type_id" id="type_id" class="form-control">
                        <option value=""> - Semua - </option>
                        <?php
                        if($data_type):
                            foreach ($data_type as $row):
                                echo option_value($row['TYPE_ID'],$row['TYPE_NAME'],'type_id',search_default($data_search,'type_id'));
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label class="col-form-label">Kod kategori</label>
                    <select name="category_id" id="category_id" class="form-control js-example-basic-single">
                        <option value=""> - Semua - </option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label class="col-form-label">Kod transaksi</label>
                    <select name="tr_code" id="tr_code" class="form-control js-example-basic-single">
                        <option value=""> - Semua - </option>
                        <?php
                        if($data_code_object):
                            foreach ($data_code_object as $row):
                                echo option_value($row['MCT_TRCODENEW'],$row['MCT_TRCODENEW'].' - '.$row['MCT_TRDESC'],'tr_code',search_default($data_search,'tr_code'));
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
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
<!--            <div class="form-group row">-->
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
<div class="card card-accent-info">
    <div class="card-body">
        <div class="table-responsive">
            <?php
//                pre($data_gst);
                if($data_report):
                    ?>
                    <div class="pull-right">
                        <button onclick="window.print()" class="btn btn-warning btn-sm pull-right">Print</button>
                    </div>
                    <br>
                    <br>
                    <?php
                    $total_bill         = 0;
                    $amount_bill        = 0;
                    $amount_bill_j      = 0;
                    $amount_bill_after  = 0;
                    $amount_resit       = 0;
                    $amount_resit_j     = 0;
                    $amount_resit_after = 0;
                    echo '<table class="table table-hover table-bordered table-scroll">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Kod transaksi</th>';
                    echo '<th>Keterangan</th>';
                    echo '<th>Bilangan Bil</th>';
                    echo '<th>Jumlah Bil (RM)</th>';
                    echo '<th>Jumlah Pelarasan Bil (RM)</th>';
                    echo '<th>Jumlah Bil Selepas Pelarasan (RM)</th>';
                    echo '<th>Jumlah Bayaran (RM)</th>';
                    echo '<th>Jumlah Pelarasan Bayaran (RM)</th>';
                    echo '<th>Jumlah Bayaran Selepas Pelarasan (RM)</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '</body>';
                    foreach ($data_report as $row):
                        echo '<tr>';
                        echo '<td>'.$row['TR_CODE'].'</td>';
                        echo '<td>'.$row['ITEM_DESC'].'</td>';
                        echo '<td style="text-align: right">'.$row['TOTAL'].'</td>';
                        $bill = $row['BILL']+($row['JOURNAL_B']);
                        echo '<td style="text-align: right">'.num($bill,3).'</td>';
//                        echo '<td style="text-align: right">'.num($bill).'('.$row['BILL'].' || '.$row['JOURNAL_B'].')</td>';
                        echo '<td style="text-align: right">'.num($row['BILL'],3).'</td>';
                        echo '<td style="text-align: right">'.num($row['JOURNAL_B'],3).'</td>';
                        $resit = $row['RESIT']+($row['JOURNAL_R']);
                        echo '<td style="text-align: right">'.num($row['RESIT'],3).'</td>';
                        echo '<td style="text-align: right">'.num($row['JOURNAL_R'],3).'</td>';
                        echo '<td style="text-align: right">'.num($resit,3).'</td>';
                        echo '</tr>';

                        $total_bill     = $total_bill+$row['TOTAL'];
                        $amount_bill    += $row['BILL'];
                        $amount_resit   += $row['RESIT'];
                        $amount_bill_j  += $row['JOURNAL_B'];
                        $amount_resit_j += $row['JOURNAL_R'];
                        $amount_bill_after += $bill;
                        $amount_resit_after += $resit;
                    endforeach;
                    echo '</tbody>';
                    echo '</table>';

                    echo '<table class="table table-hover table-bordered">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th class="text-right">Jumlah Bilangan Bil</th>';
                    echo '<th class="text-right">Jumlah Bil (RM)</th>';
                    echo '<th class="text-right">Jumlah Pelarasan Bil (RM)</th>';
                    echo '<th class="text-right">Jumlah Bil Selepas Pelarasan (RM)</th>';
                    echo '<th class="text-right">Jumlah Bayaran (RM)</th>';
                    echo '<th class="text-right">Jumlah Pelarasan Bayaran (RM)</th>';
                    echo '<th class="text-right">Jumlah Bayaran Selepas Pelarasan (RM)</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tr>';
                    echo '<td class="text-right">'.$total_bill.'</td>';
                    echo '<td class="text-right">'.num($amount_bill,3).'</td>';
                    echo '<td class="text-right">'.num($amount_bill_j,3).'</td>';
                    echo '<td class="text-right">'.num($amount_bill_after,3).'</td>';
                    echo '<td class="text-right">'.num($amount_resit,3).'</td>';
                    echo '<td class="text-right">'.num($amount_resit_j,3).'</td>';
                    echo '<td class="text-right">'.num($amount_resit_after,3).'</td>';
                    echo '</tr>';
                    echo '</table>';
                else:
                    if($_POST):
                        echo '<div class="text-center"> - Tiada data - </div>';
                    endif;
                endif;
            ?>
        </div>
    </div>
</div>

<script>
    $( document ).ready(function() {
        var type_id = $('#type_id').val();
        get_category_by_type('<?php echo search_default($data_search,'category_id')?>');
    });
</script>