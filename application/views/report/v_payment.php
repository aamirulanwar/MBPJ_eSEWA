<style>
    th{
        text-align: center;
    }
</style>
<form method="post" action="/report/payment/">
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
                if($data_report):
                    ?>
                    <div class="pull-right">
                        <button onclick="window.print()" class="btn btn-warning btn-sm pull-right">Print</button>
                    </div>
                    <br>
                    <br>
                    <?php
                    foreach ($data_report as $row):
                        echo '<h1 style="text-decoration: underline">'.$row['CATEGORY_NAME'].' - '.$row['CATEGORY_CODE'].'</h1>';
                        if($row['acc']):
                            $j = 0;
                            foreach ($row['acc'] as $acc):
                                if($acc['account_details_trans']):
                                    echo '<table class="table table-hover table-bordered table-aging">';
                                    echo '<thead>';
                                    echo '<tr>';
                                    echo '<th>No.</th>';
                                    echo '<th>No akaun</th>';
                                    echo '<th>Nama</th>';
                                    echo '<th>Kod Harta</th>';
                                    echo '<th>No. Bil</th>';
                                    echo '<th>Tarikh Bil/Resit</th>';
                                    echo '<th>Kod</th>';
                                    echo '<th>Keterangan</th>';
                                    echo '<th>Jenis</th>';
                                    echo '<th>Amaun Debit (RM)</th>';
                                    echo '<th>Amaun Kredit (RM)</th>';
                                    echo '<th>Jumlah (RM)</th>';
                                    echo '</tr>';
                                    echo '</thead>';
                                    echo '<tbody>';
                                    $data_amount = 0;
                                    $i = 0;
                                    $j = $j+1;
                                    foreach ($acc['account_details_trans'] as $trans):
                                        $i = $i+1;
                                        echo '<tr>';
                                        if($i==1):
                                            echo '<td rowspan="'.count($acc['account_details_trans']).'">'.$j.'<br>';
                                            echo '<td rowspan="'.count($acc['account_details_trans']).'">'.$acc['ACCOUNT_NUMBER'].'<br>';
                                            echo get_status_active($acc['STATUS_ACC']);
                                            echo '</td>';
                                            echo '<td rowspan="'.count($acc['account_details_trans']).'">'.$acc['NAME'].'</td>';
                                            if($acc['data_asset']):
                                                $data_asset = $acc['data_asset']['ASSET_NAME'];
                                            else:
                                                $data_asset = '-';
                                            endif;
                                            echo '<td rowspan="'.count($acc['account_details_trans']).'">'.$data_asset.'</td>';
                                        endif;
                                        echo '<td>'.$trans['BILL_NUMBER'].'</td>';
                                        echo '<td>'.date('d/m/y',strtotime($trans['DT_BILL'])).'</td>';
                                        echo '<td>'.$trans['TR_CODE'].'</td>';
                                        echo '<td>'.$trans['ITEM_DESC'].'</td>';

                                        $bill_cat       = '';
                                        $amount_debit   = 0;
                                        $amount_kredit  = 0;

                                        if($trans['BILL_CATEGORY']=='B'):
                                            $bill_cat = 'Bil';
                                            $amount_debit   = $trans['TOTAL_AMOUNT'];
                                            $data_amount    = $data_amount+($amount_debit);
                                        elseif ($trans['BILL_CATEGORY']=='R'):
                                            $bill_cat = 'Resit';
                                            $amount_kredit = $trans['TOTAL_AMOUNT'];
                                            $data_amount    = $data_amount-($amount_kredit);
                                        elseif ($trans['BILL_CATEGORY']=='J'):
                                            $bill_cat = 'Jernel';
                                            if(substr($trans['TR_CODE'],0,1)==1):
                                                $amount_debit = $trans['TOTAL_AMOUNT'];
                                                $data_amount    = $data_amount+($amount_debit);
                                            elseif (substr($trans['TR_CODE'],0,1)==2):
                                                $amount_kredit = $trans['TOTAL_AMOUNT'];
                                                $data_amount   = $data_amount-($amount_kredit);
                                            endif;
                                        endif;
                                        echo '<td>'.$bill_cat.'</td>';
                                        echo '<td style="text-align: right">'.num($amount_debit,3).'</td>';
                                        echo '<td style="text-align: right">'.num($amount_kredit,3).'</td>';
                                        echo '<td style="text-align: right">'.num($data_amount,3).'</td>';
                                        echo '</tr>';
                                    endforeach;
                                    echo '</tbody>';
                                    echo '</table>';
//                                else:
//                                    echo '<table class="table table-hover table-bordered table-aging">';
//                                    echo '<tr><td class="text-center"> - Tiada data - </td></tr>';
//                                    echo '</table>';
                                endif;
                            endforeach;
                        else:
                            echo '<table class="table table-hover table-bordered table-aging">';
                            echo '<tr><td class="text-center"> - Tiada data - </td></tr>';
                            echo '</table>';
                        endif;
                    endforeach;
                else:
                    echo '<table class="table table-hover table-bordered table-aging">';
                    echo '<tr><td class="text-center"> - Tiada data - </td></tr>';
                    echo '</table>';
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