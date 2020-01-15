<style>
    th{
        text-align: center;
    }
</style>
<form method="post" action="/report/gst_rental/">
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
            <div class="pull-right">
                <button onclick="window.print()" class="btn btn-warning btn-sm pull-right">Print</button>
            </div>
            <br>
            <br>
            <table class="table table-hover table-bordered">
                <tr>
                    <th style="text-align:center">No.</th>
                    <th style="text-align:center">Maklumat Akaun</th>
                    <th style="text-align:center">Maklumat Transaksi</th>
<!--                    <th style="text-align:center">Bil sewaan</th>-->
<!--                    <th style="text-align:center">Jumlah bil (RM)</th>-->
<!--                    <th style="text-align:center">Resit sewaan</th>-->
<!--                    <th style="text-align:center">Jumlah resit (RM)</th>-->
<!--                    <th style="text-align:center">Baki perlu bayar</th>-->
                </tr>
                <tbody>
                <?php
                $cnt = 0;

                $total_all_b        = 0;
                $total_all_r        = 0;
                $total_all_jurnal_r = 0;
                $total_all_jurnal_b = 0;
                $total_balance      = 0;
                if($data_gst):
                    foreach($data_gst as $row):
                        $cnt = $cnt+1;
                        ?>
                        <tr>
                            <td><?php echo $cnt?></td>
                            <td>
                                <strong>No. akaun&nbsp;:&nbsp;</strong><br><?php echo $row['data_acc']['ACCOUNT_NUMBER']?>
                                <br>
                                <br>
                                <strong>Nama&nbsp;:&nbsp;</strong><br><?php echo $row['data_acc']['NAME']?>
                                <br>
                                <br>
                                <?php
                                if(!empty($row['data_acc']['IC_NUMBER'])):
                                    echo '<strong>No&nbsp;kad&nbsp;pengenalan&nbsp;:</strong><br>'.$row['data_acc']['IC_NUMBER'];
                                elseif (!empty($row['data_acc']['COMPANY_REGISTRATION_NUMBER'])):
                                    echo '<strong>No&nbsp;pendaftaran&nbsp;syarikat&nbsp;:</strong><br>'.$row['data_acc']['COMPANY_REGISTRATION_NUMBER'];
                                endif;
                                ?>
                                <br>
                                <br>
                                <strong>Jenis harta&nbsp;:&nbsp;</strong><br><?php echo $row['data_acc']['TYPE_NAME']?>
                                <br>
                                <br>
                                <strong>Kod kategori&nbsp;:&nbsp;</strong><br><?php echo $row['data_acc']['CATEGORY_CODE']?> - <?php echo $row['data_acc']['CATEGORY_NAME']?>
                                <br>
                                <br>
                                <strong>Status Akaun&nbsp;:&nbsp;</strong><br><?php echo get_status_active($row['data_acc']['STATUS_ACC'])?>
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <th>
                                            No. Bil / Tarikh Bil
                                        </th>
                                        <th>
                                            Kod Transaksi / Keterangan
                                        </th>
<!--                                        <th>-->
<!--                                            Anggaran bil (RM)-->
<!--                                        </th>-->
                                        <th>
                                            Jumlah Bil (RM)
                                        </th>
                                        <th>
                                            Jumlah Bil Pelarasan (RM)
                                        </th>
                                        <th>
                                            Jumlah Bayaran / Resit (RM)
                                        </th>
                                        <th>
                                            Jumlah Bayaran / Resit Pelarasan (RM)
                                        </th>
<!--                                        <th>-->
<!--                                            Baki perlu dibayar-->
<!--                                        </th>-->
                                    </tr>
                                <?php
                                    if($row['data_item']):
                                        $total_amount_b         = 0;
                                        $total_amount_b_jurnal  = 0;
                                        $total_amount_r         = 0;
                                        $total_amount_r_jurnal  = 0;

                                        $amount_b2   = 0;
                                        $amount_r2   = 0;
                                        $jurnal_b2   = 0;
                                        $jurnal_r2   = 0;

                                        if($row['prv_data']['BILL']>0 || $row['prv_data']['RESIT']>0 || $row['prv_data']['JOURNAL_B']>0 || $row['prv_data']['JOURNAL_R']>0):
                                            echo '<tr>';

                                            $amount_b2      = $row['prv_data']['BILL'];
                                            $total_all_b    = $total_all_b+$amount_b2;
                                            $total_amount_b = $total_amount_b+$amount_b2;

                                            $amount_r2      = $row['prv_data']['RESIT'];
                                            $total_all_r    = $total_all_r+$amount_r2;
                                            $total_amount_r = $total_amount_r+$amount_r2;

                                            $jurnal_b2              = $row['prv_data']['JOURNAL_B'];
                                            $total_amount_b_jurnal  = $total_amount_b_jurnal+$jurnal_b2;
                                            $total_all_jurnal_b     = $total_all_jurnal_b+$jurnal_b2;

                                            $jurnal_r               = $row['prv_data']['JOURNAL_R'];
                                            $total_amount_r_jurnal  = $total_amount_r_jurnal+$jurnal_r;
                                            $total_all_jurnal_r     = $total_all_jurnal_r+$jurnal_r;

                                            echo '<td>-</td>';
                                            echo '<td>JUMLAH SEBELUM</td>';
                                            echo '<td style="text-align: right">'.num($amount_b2,3).'</td>';
                                            echo '<td style="text-align: right">'.num($amount_r2,3).'</td>';
                                            echo '<td style="text-align: right">'.num($jurnal_b2,3).'</td>';
                                            echo '<td style="text-align: right">'.num($jurnal_r2,3).'</td>';
                                            echo '</tr>';
                                        endif;

                                        foreach ($row['data_item'] as $data_item):

                                            echo '<tr>';
                                            $gst_actual = '-';
                                            if($data_item['gst_actual']):
                                                $gst_actual = $data_item['gst_actual'][0]['AMOUNT'];
                                            endif;

                                            $amount_b   = 0+$amount_b2;
                                            $amount_r   = 0+$amount_r2;
                                            $jurnal_b   = 0+$jurnal_b2;
                                            $jurnal_r   = 0+$jurnal_r2;
                                            if($data_item['BILL_CATEGORY']=='B'):
                                                $amount_b       = $data_item['AMOUNT'];
                                                $total_all_b    = $total_all_b+$amount_b;
                                                $total_amount_b = $total_amount_b+$amount_b;
                                            elseif ($data_item['BILL_CATEGORY']=='R'):
                                                $amount_r       = $data_item['AMOUNT'];
                                                $total_all_r    = $total_all_r+$amount_r;
                                                $total_amount_r = $total_amount_r+$amount_r;
                                            elseif ($data_item['BILL_CATEGORY']=='J'):
                                                if(substr($data_item['TR_CODE'],0,1)==1):
                                                    $jurnal_b               = $data_item['AMOUNT'];
                                                    $total_amount_b_jurnal  = $total_amount_b_jurnal+$jurnal_b;
                                                    $total_all_jurnal_b     = $total_all_jurnal_b+$total_amount_b_jurnal;
                                                elseif (substr($data_item['TR_CODE'],0,1)==2):
                                                    $jurnal_r               = $data_item['AMOUNT'];
                                                    $total_amount_r_jurnal  = $total_amount_r_jurnal+$jurnal_r;
                                                    $total_all_jurnal_r     = $total_all_jurnal_r+$total_amount_r_jurnal;
                                                endif;
                                            endif;

                                            echo '<td>'.$data_item['BILL_NUMBER'].'<br>('.date_display($data_item['DT_BILL']).')</td>';
                                            echo '<td>'.$data_item['TR_CODE'].'&nbsp;-&nbsp;'.$data_item['ITEM_DESC'].'</td>';
                                            echo '<td style="text-align: right">'.num($amount_b,3).'</td>';
                                            echo '<td style="text-align: right">'.num($jurnal_b,3).'</td>';
                                            echo '<td style="text-align: right">'.num($amount_r,3).'</td>';
                                            echo '<td style="text-align: right">'.num($jurnal_r,3).'</td>';
                                            echo '</tr>';
                                        endforeach;
//                                        echo $total_amount_b.'----'.$total_amount_b_jurnal.'<br>';
                                        $b = $total_amount_b+($total_amount_b_jurnal);
                                        $j = $total_amount_r+($total_amount_r_jurnal);
                                        echo '<tr>';
                                        echo '<td colspan="5" style="text-align: right"><strong>Jumlah Bil (RM)</strong></td>';
                                        echo '<td style="text-align: right">'.num($b,3).'</td>';
                                        echo '</tr>';
                                        echo '<tr>';
                                        echo '<td colspan="5" style="text-align: right"><strong>Jumlah Resit (RM)</strong></td>';
                                        echo '<td style="text-align: right">'.num($j,3).'</td>';
                                        echo '</tr>';
                                        echo '<tr>';
                                        echo '<td colspan="5" style="text-align: right"><strong>Baki Perlu Dibayar (RM)</strong></td>';
                                        $total_need_pay = $b - $j;
                                        echo '<td style="text-align: right">'.num($total_need_pay,3).'</td>';
                                        echo '</tr>';
                                    endif;
                                ?>
                                </table>
                            </td>
                        </tr>
                    <?php
//                        $total_all_b = $total_all_b+
                    endforeach;
                else:
                    echo '<tr><td colspan="9" class="text-center ">- Tiada rekod - </td></tr>';
                endif;

                ?>
                <tbody>
            </table>
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
                            Baki Perlu Dibayar (RM)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">
                            <?php echo $cnt?>
                        </td>
                        <td class="text-right">
                            <?php echo num($total_all_b,3)?>
                        </td>
                        <td class="text-right">
                            <?php echo num($total_all_jurnal_b,3)?>
                        </td>
                        <td class="text-right">
                            <?php echo num($total_all_r,3)?>
                        </td>
                        <td class="text-right">
                            <?php echo num($total_all_jurnal_r,3)?>
                        </td>
                        <td class="text-right">
                            <?php
                                $baki = ($total_all_b+($total_all_jurnal_b)) - ($total_all_r+($total_all_jurnal_r));
                                echo num($baki,3);
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $( document ).ready(function() {
        var type_id = $('#type_id').val();
        get_category_by_type('<?php echo search_default($data_search,'category_id')?>');
    });
</script>