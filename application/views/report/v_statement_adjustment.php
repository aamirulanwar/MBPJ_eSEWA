<style>
    .table-adjustment{
        font-size: 11px !important;
    }

    .table-adjustment th{
        text-align: center;
        vertical-align: middle !important;
    }
    th{
        text-align: center;
    }


    @media print{
        @page {
            size: landscape
        }
    }

    .table-fixed tbody {
        height: 300px;
        overflow-y: auto;
        width: 100%;
    }

    .table-fixed thead,
    .table-fixed tbody,
    .table-fixed tr,
    .table-fixed td,
    .table-fixed th {
        display: block;
    }

    .table-fixed tbody td,
    .table-fixed tbody th,
    .table-fixed thead > tr > th {
        float: left;
        position: relative;

        &::after {
            content: '';
            clear: both;
            display: block;
        }
    }
</style>
<form method="post" action="/report/adjustment_statement">
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>

            <div class="form-group row">
<!--                <div class="col-sm-4">-->
<!--                    <label class="col-form-label">Kod kategori</label>-->
<!--                    <select name="category_id" class="form-control">-->
<!--                        <option value=""> - Sila pilih - </option>-->
<!--                        --><?php
//                        if($data_category):
//                            foreach ($data_category as $row):
//                                echo option_value($row['CATEGORY_ID'],$row['CATEGORY_NAME'],'asset_code',search_default($data_search,'category_id'));
//                            endforeach;
//                        endif;
//                        ?>
<!--                    </select>-->
<!--                </div>-->

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
                if($data_report):
                    ?>
                    <div class="pull-right">
                        <button onclick="window.print()" class="btn btn-warning btn-sm pull-right">Print</button>
                    </div>
                    <br>
                    <br>
                    <?php
                    $total_debit     = 0;
                    $total_kredit    = 0;
                    $total_baki     = 0;
                    echo '<table class="table table-hover table-bordered table-adjustment">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th colspan="4">Akaun</th>';
                    echo '<th rowspan="2">Tunggakan sewa (RM)</th>';
                    echo '<th colspan="2">Pelarasan</th>';
                    echo '<th rowspan="2">Terimaan Tunggakan (RM)</th>';
                    echo '<th colspan="2">Pelarasan</th>';
                    echo '<th rowspan="2">Lebihan (RM)</th>';
                    echo '<th colspan="2">Pelarasan</th>';
                    echo '<th rowspan="2">Sewaan semasa (RM)</th>';
                    echo '<th colspan="2">Pelarasan</th>';
                    echo '<th rowspan="2">Terimaan semasa (RM)</th>';
                    echo '<th colspan="2">Pelarasan</th>';
                    echo '<th colspan="2">Jumlah</th>';
                    echo '<th rowspan="2">Baki Bawa Kehadapan (RM)</th>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<th>Akaun No.</th>';
                    echo '<th>Nama Penyewa</th>';
                    echo '<th>Kod Harta</th>';
                    echo '<th>Kod Kategori</th>';
                    echo '<th>Debit (RM)</th>';
                    echo '<th>Kredit (RM)</th>';
                    echo '<th>Debit (RM)</th>';
                    echo '<th>Kredit (RM)</th>';
                    echo '<th>Debit (RM)</th>';
                    echo '<th>Kredit (RM)</th>';
                    echo '<th>Debit (RM)</th>';
                    echo '<th>Kredit (RM)</th>';
                    echo '<th>Debit (RM)</th>';
                    echo '<th>Kredit (RM)</th>';
                    echo '<th>Jumlah (Debit) (RM)</th>';
                    echo '<th>Jumlah (Kredit) (RM)</th>';
                    echo '<tr>';
                    echo '</thead>';
                    echo '</body>';
                    foreach ($data_report as $row):
                        echo '<tr>';
                        echo '<td>'.$row['ACCOUNT_NUMBER'].'</td>';
                        echo '<td>'.$row['NAME'].'</td>';
                        echo '<td>'.$row['TYPE_NAME'].'</td>';
                        echo '<td>'.$row['CATEGORY_NAME'].'</td>';
//                        echo '<td>';
//                        echo $row['ACCOUNT_NUMBER'].'<br>';
//                        echo 'Nama penyewa :<br>'.$row['NAME'].'<br>';
//                        echo 'Kod harta :<br>'.$row['TYPE_NAME'].'<br>';
//                        echo '</td>';
                        echo '<td style="text-align: right">'.num($row['tunggakan']['TUNGGAKAN'],3).'</td>';
                        echo '<td style="text-align: right">'.num($row['tunggakan']['DEBIT'],3).'</td>';
                        echo '<td style="text-align: right">'.num($row['tunggakan']['KREDIT'],3).'</td>';
                        echo '<td style="text-align: right">'.num($row['bayaran']['TUNGGAKAN'],3).'</td>';
                        echo '<td style="text-align: right">'.num($row['bayaran']['DEBIT'],3).'</td>';
                        echo '<td style="text-align: right">'.num($row['bayaran']['KREDIT'],3).'</td>';
                        echo '<td style="text-align: right">'.num(abs($row['lebihan']['TUNGGAKAN']),3).'</td>';
                        echo '<td style="text-align: right">'.num($row['lebihan']['DEBIT'],3).'</td>';
                        echo '<td style="text-align: right">'.num($row['lebihan']['KREDIT'],3).'</td>';
                        echo '<td style="text-align: right">'.num($row['semasa']['TUNGGAKAN'],3).'</td>';
                        echo '<td style="text-align: right">'.num($row['semasa']['DEBIT'],3).'</td>';
                        echo '<td style="text-align: right">'.num($row['semasa']['KREDIT'],3).'</td>';
                        echo '<td style="text-align: right">'.num($row['bayaran_semasa']['TUNGGAKAN'],3).'</td>';
                        echo '<td style="text-align: right">'.num($row['bayaran_semasa']['DEBIT'],3).'</td>';
                        echo '<td style="text-align: right">'.num($row['bayaran_semasa']['KREDIT'],3).'</td>';

                        $debit  = $row['tunggakan']['DEBIT']+$row['bayaran']['DEBIT']+$row['lebihan']['DEBIT']+$row['semasa']['DEBIT']+$row['bayaran_semasa']['DEBIT'];
                        $kredit = $row['tunggakan']['KREDIT']+abs($row['bayaran']['KREDIT'])+$row['lebihan']['KREDIT']+$row['semasa']['KREDIT']+$row['bayaran_semasa']['KREDIT'];

                        $total_debit     += $debit;
                        $total_kredit    += $kredit;

                        echo '<td style="text-align: right">'.num($debit,3).'</td>';
                        echo '<td style="text-align: right">'.num($kredit,3).'</td>';

                        $baki_1 = $row['tunggakan']['TUNGGAKAN']+$row['semasa']['TUNGGAKAN']+$debit;
                        $baki_2 = $row['bayaran']['TUNGGAKAN']+abs($row['lebihan']['TUNGGAKAN'])+$row['bayaran_semasa']['TUNGGAKAN']+$kredit;

                        $baki = $baki_1-$baki_2;
                        $total_baki += $baki;
                        echo '<td style="text-align: right">'.num($baki,3).'</td>';
//                        echo '<td>'.$row['ITEM_DESC'].'</td>';
//                        echo '<td style="text-align: right">'.$row['TOTAL'].'</td>';
//                        $bill = $row['BILL']+($row['JOURNAL_B']);
////                        echo '<td style="text-align: right">'.num($bill).'('.$row['BILL'].' || '.$row['JOURNAL_B'].')</td>';
//                        echo '<td style="text-align: right">'.num($bill).'</td>';
//                        $resit = $row['RESIT']+($row['JOURNAL_R']);
//                        echo '<td style="text-align: right">'.num($resit).'</td>';
                        echo '</tr>';
//
//                        $total_bill     = $total_bill+$row['TOTAL'];
//                        $amount_bill    = $amount_bill+$bill;
//                        $amount_resit   = $amount_resit+$resit;
                    endforeach;
                    echo '</tbody>';
                    echo '</table>';

//                    echo '<table class="table table-hover table-bordered">';
//                    echo '<thead>';
//                    echo '<tr>';
//                    echo '<td>Jumlah bilangan bil</td>';
//                    echo '<td>Jumlah bil (RM)</td>';
//                    echo '<td>Jumlah resit (RM)</td>';
//                    echo '</tr>';
//                    echo '</thead>';
//                    echo '<tr>';
//                    echo '<td>'.$total_bill.'</td>';
//                    echo '<td class="text-right">'.num($amount_bill).'</td>';
//                    echo '<td class="text-right">'.num($amount_resit).'</td>';
//                    echo '</tr>';
//                    echo '</table>';
                else:
                    if($_POST):
                        echo '<div class="text-center"> - Tiada data - </div>';
                    endif;
                endif;
            ?>
        </div>
        <?php
        if($data_report):
            echo '<table class="table table-hover table-bordered ">';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="text-right">Jumlah Keseluruhan (Debit) (RM)</th>';
            echo '<th class="text-right">Jumlah Keseluruhan (Kredit) (RM)</th>';
            echo '<th class="text-right">Jumlah Baki Bawa Kehadapan (RM)</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo '<td class="text-right" >'.num($total_debit,3).'</td>';
            echo '<td class="text-right">'.num($total_kredit,3).'</td>';
            echo '<td class="text-right">'.num($total_baki,3).'</td>';
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
        endif;
        ?>
    </div>
</div>

<script>
    $( document ).ready(function() {
        var type_id = $('#type_id').val();
        get_category_by_type('<?php echo search_default($data_search,'category_id')?>');
    });
</script>