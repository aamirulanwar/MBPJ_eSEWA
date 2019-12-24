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
</style>
<form method="post" action="/report/adjustment_statement_ringkasan">
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
<!--                        <option value=""> - Sila pilih - </option>-->
                        <?php
                        echo option_value('','- Semua - ','type_id');
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
                    $total_debit_all    = 0;
                    $total_kredit_all   = 0;
                    $total_baki_all     = 0;
                    foreach ($data_report as $report):
                        echo '<h3 style="text-decoration: underline">'.$report['data_type_details']['TYPE_NAME'].'</h3>';
                        if($report['category']):
                            $total_bill     = 0;
                            $amount_bill    = 0;
                            $amount_resit   = 0;
                            $total_debit    = 0;
                            $total_kredit   = 0;
                            $total_baki     = 0;
                            echo '<table class="table table-hover table-bordered table-adjustment">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th rowspan="2">Kod Kategori</th>';
                            echo '<th rowspan="2">Tunggakan Sewa (RM)</th>';
                            echo '<th colspan="2">Pelarasan</th>';
                            echo '<th rowspan="2">Terimaan Tunggakan (RM)</th>';
                            echo '<th colspan="2">Pelarasan</th>';
                            echo '<th rowspan="2">Lebihan (RM)</th>';
                            echo '<th colspan="2">Pelarasan</th>';
                            echo '<th rowspan="2">Sewaan Semasa (RM)</th>';
                            echo '<th colspan="2">Pelarasan</th>';
                            echo '<th rowspan="2">Terimaan Semasa (RM)</th>';
                            echo '<th colspan="2">Pelarasan</th>';
                            echo '<th colspan="2">Jumlah </th>';
                            echo '<th rowspan="2">Baki Bawa Kehadapan (RM)</th>';
                            echo '</tr>';
                            echo '<tr>';
//                        echo '<th>Akaun No.</th>';
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

                            foreach ($report['category'] as $category):
                                echo '<tr>';
                                echo '<td>'.$category['category_details']['CATEGORY_NAME'].' - '.$category['category_details']['CATEGORY_CODE'].'</td>';
//                        echo '<td>';
//                        echo $row['ACCOUNT_NUMBER'].'<br>';
//                        echo 'Nama penyewa :<br>'.$row['NAME'].'<br>';
//                        echo 'Kod harta :<br>'.$row['TYPE_NAME'].'<br>';
//                        echo '</td>';
                                echo '<td style="text-align: right">'.num($category['tunggakan']['TUNGGAKAN'],3).'</td>';
                                echo '<td style="text-align: right">'.num($category['tunggakan']['DEBIT'],3).'</td>';
                                echo '<td style="text-align: right">'.num($category['tunggakan']['KREDIT'],3).'</td>';
                                echo '<td style="text-align: right">'.num($category['bayaran']['TUNGGAKAN'],3).'</td>';
                                echo '<td style="text-align: right">'.num($category['bayaran']['DEBIT'],3).'</td>';
                                echo '<td style="text-align: right">'.num($category['bayaran']['KREDIT'],3).'</td>';
                                echo '<td style="text-align: right">'.num(abs($category['lebihan']['TUNGGAKAN']),3).'</td>';
                                echo '<td style="text-align: right">'.num($category['lebihan']['DEBIT'],3).'</td>';
                                echo '<td style="text-align: right">'.num($category['lebihan']['KREDIT'],3).'</td>';
                                echo '<td style="text-align: right">'.num($category['semasa']['TUNGGAKAN'],3).'</td>';
                                echo '<td style="text-align: right">'.num($category['semasa']['DEBIT'],3).'</td>';
                                echo '<td style="text-align: right">'.num($category['semasa']['KREDIT'],3).'</td>';
                                echo '<td style="text-align: right">'.num($category['bayaran_semasa']['TUNGGAKAN'],3).'</td>';
                                echo '<td style="text-align: right">'.num($category['bayaran_semasa']['DEBIT'],3).'</td>';
                                echo '<td style="text-align: right">'.num($category['bayaran_semasa']['KREDIT'],3).'</td>';

//                                $debit  = $category['tunggakan']['TUNGGAKAN']+$category['cukai']['TUNGGAKAN'];
//                                $kredit = $category['bayaran']['TUNGGAKAN']+abs($category['lebihan']['TUNGGAKAN'])+$category['bayaran_cukai']['TUNGGAKAN'];
                                $debit  = $category['tunggakan']['DEBIT']+$category['bayaran']['DEBIT']+$category['lebihan']['DEBIT']+$category['semasa']['DEBIT']+$category['bayaran_semasa']['DEBIT'];
                                $kredit = $category['tunggakan']['KREDIT']+abs($category['bayaran']['KREDIT'])+$category['lebihan']['KREDIT']+$category['semasa']['KREDIT']+$category['bayaran_semasa']['KREDIT'];
                                $total_debit    += $debit;
                                $total_kredit   += $kredit;

                                echo '<td style="text-align: right">'.num($debit,3).'</td>';
                                echo '<td style="text-align: right">'.num($kredit,3).'</td>';

                                $baki_1 = $category['tunggakan']['TUNGGAKAN']+$category['semasa']['TUNGGAKAN']+$debit;
                                $baki_2 = $category['bayaran']['TUNGGAKAN']+abs($category['lebihan']['TUNGGAKAN'])+$category['bayaran_semasa']['TUNGGAKAN']+$kredit;

//                                $baki           = $debit-$kredit;
                                $baki = $baki_1-$baki_2;
                                $total_baki     += $baki;

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
                            $total_debit_all    += $total_debit;
                            $total_kredit_all   += $total_kredit;
                            $total_baki_all     += $total_baki;
                        endif;
                    endforeach;

                    echo '<h3 style="text-decoration: underline">Jumlah Keseluruhan</h3>';
                    echo '<table class="table     table-hover table-bordered ">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th class="text-right">Jumlah Keseluruhan (Debit) (RM)</th>';
                    echo '<th class="text-right">Jumlah Keseluruhan (Kredit) (RM)</th>';
                    echo '<th class="text-right">Jumlah Baki Bawa Kehadapan (RM)</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    echo '<tr>';
                    echo '<td class="text-right">'.num($total_debit_all,3).'</td>';
                    echo '<td class="text-right">'.num($total_kredit_all,3).'</td>';
                    echo '<td class="text-right">'.num($total_baki_all,3).'</td>';
                    echo '</tr>';
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
    </div>
</div>

<script>
    $( document ).ready(function() {
        var type_id = $('#type_id').val();
        get_category_by_type('<?php echo search_default($data_search,'category_id')?>');
    });
</script>