<style>
    th{
        text-align: center;
    }
    .table-aging{
        font-size: 13px !important;
    }
</style>
<form method="post" action="/report/gst_rental_simple/">
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
                if($data_gst):
                    ?>
                    <div class="pull-right">
                        <a class="btn btn-warning btn-sm pull-right" href="/report/print_gst_rental_simple" target="_blank">Cetak</a>
                    </div>
                    <br>
                    <br>
                    <?php
                    echo '<table class="table table-hover table-bordered data-print table-scroll table-aging">';
//                        echo '<thead>';
//                        echo '<tr>';
//                        echo '<th>&nbsp;</th>';
//                        echo '<th>Jumlah bil</th>';
//                        echo '<th>Penyelarasan bil</th>';
//                        echo '<th>Jumlah resit</th>';
//                        echo '<th>Penyelarasan resit</th>';
//                        echo '</tr>';
//                        echo '</thead>';
                    $k=0;
                    $bill               = 0;
                    $bill_pelarasan     = 0;
                    $resit              = 0;
                    $resit_pelarasan    = 0;
                    $baki_all           = 0;
                    $baki_tunggakan     = 0;
                    foreach ($data_gst as $key=>$row):
                        echo '<thead>';

                        echo '<tr>';
                        echo '<th width="5%">Bil</th>';
                        echo '<th width="20%"><strong>'.$key.'</strong></th>';
                        echo '<th width="12%">Jumlah Bil (RM)</th>';
                        echo '<th width="13%">Jumlah Bil Pelarasan (RM)</th>';
                        echo '<th width="12%">Jumlah Bayaran / Resit (RM)</th>';
                        echo '<th width="12%">Jumlah Bayaran / Resit Pelarasan (RM)</th>';
                        echo '<th width="12%">Baki Tunggakan</th>';
                        echo '<th width="17%">Baki Perlu Dibayar (RM)</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        if($row):
                            $i=0;

                            foreach ($row as $key_cat=>$category):
                                $k = $k + 1;
                                $i = $i+1;
                                echo '<tr>';
                                echo '<td width="5%">'.$i.'</td>';
                                echo '<td width="20%" style="padding-left: 10px;">'.$category['CATEGORY_NAME'].'</td>';
                                echo '<td width="12%" style="text-align: right">';
                                    echo num($category['BILL'],3);
                                    $bill +=$category['BILL'];
                                echo '</td>';
                                echo '<td width="13%" style="text-align: right">';
                                    echo num($category['JOURNAL_B'],3);
                                    $bill_pelarasan += $category['JOURNAL_B'];
                                echo '</td>';
                                echo '<td width="12%" style="text-align: right">';
                                    echo num($category['RESIT'],3);
                                    $resit += $category['RESIT'];
                                echo '</td>';
                                echo '<td width="12%" style="text-align: right">';
                                    echo num($category['JOURNAL_R'],3);
                                    $resit_pelarasan += $category['JOURNAL_R'];
                                echo '</td>';
                                echo '<td width="12%" style="text-align: right">';
                                    $total = 0;
                                    if($category['data_report_prv']):
                                        $total = ($category['data_report_prv']['BILL']+$category['data_report_prv']['JOURNAL_B'])-($category['data_report_prv']['RESIT']+$category['data_report_prv']['JOURNAL_R']);
                                        echo num($total,3);
                                    else:
                                        echo num($total,3);
                                    endif;
                                    $baki_tunggakan += $total;
                                echo '</td>';
                                echo '<td width="17%" style="text-align: right">';
                                    $baki = ($category['BILL']+($category['JOURNAL_B']))-($category['RESIT']+($category['JOURNAL_R']));
                                    $baki_all += $baki;
                                    echo num($baki,3);
                                    if($total>0):
                                        echo '+'.num($total,3);
                                        echo '<br>'.num($baki+$total,3);
                                    endif;
                                echo '</td>';
                                echo '</tr>';
                            endforeach;
                            echo '</tbody>';
                        endif;
                    endforeach;
                    echo '<table>';
                    ?>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>
                                Bilangan Bil GST
                            </th>
                            <th>
                                Jumlah Bil (RM)
                            </th>
                            <th>
                                Jumlah Pelarasan Bil (RM)
                            </th>
                            <th>
                                Jumlah Bayaran / Resit (RM)
                            </th>
                            <th>
                                Jumlah Bayaran / Resit Pelarasan (RM)
                            </th>
                            <th>
                                Baki Tunggakan (RM)
                            </th>
                            <th>
                                Baki Perlu Dibayar (RM)
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-center">
                                <?php echo $k?>
                            </td>
                            <td class="text-right">
                                <?php echo num($bill,3)?>
                            </td>
                            <td class="text-right">
                                <?php echo num($bill_pelarasan,3)?>
                            </td>
                            <td class="text-right">
                                <?php echo num($resit,3)?>
                            </td>
                            <td class="text-right">
                                <?php echo num($resit_pelarasan,3)?>
                            </td>
                            <td class="text-right">
                                <?php
                                echo num($baki_tunggakan,3);
                                ?>
                            </td><td class="text-right">
                                <?php
                                echo num($baki_all,3);
                                if($baki_tunggakan>0):
                                    echo '+'.num($baki_tunggakan,3);
                                    echo '<br>'.num($baki_all+$baki_tunggakan,3);
                                endif;
                                ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <?php
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