<?php
if(uri_segment(3)=='post'):
    echo '<form id="my_form" method="post" action="/report/record_transaction/'.uri_segment(4).'/'.uri_segment(5).'">';
else:
    echo '<form id="my_form" method="post" action="/report/record_transaction/'.uri_segment(3).'/'.uri_segment(4).'">';
endif;
?>
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>
            <div class="form-group row">
                <div class="col-sm-4" style="display: block">
                    <label class="col-form-label">Akaun</label>
                    <select name="account_id" id="account_id" class="form-control js-example-basic-single">
                        <option value=""> - Semua - </option>
                        <?php
                        if($data_account):
                            foreach ($data_account as $row):
                                echo option_value($row['ACCOUNT_ID'],$row['ACCOUNT_NUMBER'].' - '.$row['NAME'],'account_id',search_default($data_search,'account_id'));
                            endforeach;
                        endif;
                        ?>
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
                <div class="col-sm-4">
                    <label class="col-form-label">Susun mengikut </label>
                    <select name="order_by" id="order_by" class="form-control">
                        <option value=""> - Sila pilih - </option>
                        <?php
                            echo option_value('i.TR_CODE','Kod transaksi','order_by',search_default($data_search,'order_by'));
                            echo option_value('i.AMOUNT','Amaun','order_by',search_default($data_search,'order_by'));
                            echo option_value('i.dt_added','Tarikh bill / resit','order_by',search_default($data_search,'order_by'));
                        ?>
                    </select>
                </div>
<!--                <div class="col-sm-4">-->
<!--                    <label class="col-form-label">Jenis harta</label>-->
<!--                    <select onchange="get_category_by_type('')" name="type_id" id="type_id" class="form-control">-->
<!--                        <option value=""> - Semua - </option>-->
<!--                        --><?php
//                        if($data_type):
//                            foreach ($data_type as $row):
//                                echo option_value($row['TYPE_ID'],$row['TYPE_NAME'],'type_id',search_default($data_search,'type_id'));
//                            endforeach;
//                        endif;
//                        ?>
<!--                    </select>-->
<!--                </div>-->
<!--                <div class="col-sm-4">-->
<!--                    <label class="col-form-label">Kod kategori</label>-->
<!--                    <select name="category_id" id="category_id" class="form-control">-->
<!--                        <option value=""> - Semua - </option>-->
<!--                    </select>-->
<!--                </div>-->
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
            <?php
            if($acc_details):
                ?>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <h5>No akaun : <?php echo $acc_details['ACCOUNT_NUMBER']?></h5>
<!--                        <label class="col-form-label">No akaun</label>-->
<!--                        <p class="form-control-plaintext">--><?php //echo uri_segment(3)?><!--</p>-->
                    </div>
                </div>
            <?php
            endif;
            ?>
            <?php
                if($acc_details):
            ?>
            <div class="form-group row">
                <div class="col-sm-12">
                    <h5>Nama : <?php echo $acc_details['NAME']?></h5>
<!--                    <label class="col-form-label">Nama</label>-->
<!--                    <p class="form-control-plaintext">--><?php //echo urldecode(uri_segment(4))?><!--</p>-->
                </div>
            </div>
            <?php
                endif;
            ?>
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
            // echo pre($data_report);
            // die();
//                pre($data_gst);
                if($data_report):
                    ?>
                    <div class="pull-right">
                        <button onclick="print_report()" class="btn btn-warning btn-sm pull-right">Print</button>
                    </div>
                    <br>
                    <br>
                    <?php
                    echo '<table class="data-print table table-hover table-bordered table-scroll">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th width="12%">No. bill / no. resit</th>';
                    echo '<th width="10%">Tarikh bill/resit</th>';
                    echo '<th width="10%">Kod transaksi</th>';
                    echo '<th width="11%">Maklumat penyewa</th>';
                    echo '<th width="15%">Keterangan</th>';
                    echo '<th width="10%">Jenis</th>';
                    echo '<th width="10%">Amaun debit (RM)</th>';
                    echo '<th width="10%">Amaun kredit (RM)</th>';
                    echo '<th width="12%">Jumlah Amaun (RM)</th>';
//                    echo '<th>No. bill</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    $amount = 0;
                    foreach ($data_report as $row):
                        echo '<tr>';
                        $bill_number    = '';
                        $resit_number   = '';
                        $journal_number = '';
                        $bill_amaun     = 0;
                        $resit_amaun    = 0;
                        $type           = '';
                        
                        if($row['BILL_CATEGORY']=='B'):
                            $bill_number    = $row['BILL_NUMBER'];
                            $bill_amaun     = empty($row['AMOUNT'])?0:$row['AMOUNT'];
                            $type           = 'Bill';
                        endif;

                        if($row['BILL_CATEGORY']=='R'):
                            $resit_number   = $row['BILL_NUMBER'];
                            $resit_amaun    = empty($row['AMOUNT'])?0:$row['AMOUNT'];
                            $type           = 'Resit';
                        endif;

                        if($row['BILL_CATEGORY']=='J'):
                            $journal_number    = $row['BILL_NUMBER'];
                            $journal_amaun     = empty($row['AMOUNT'])?0:$row['AMOUNT'];
                            $type              = 'Journal';

                            if (substr($row['JOURNAL_CODE'],0,1)=='B'):
                                $bill_amaun     = empty($row['AMOUNT'])?0:$row['AMOUNT'];
                            elseif (substr($row['JOURNAL_CODE'],0,1)=='R'):
                                $resit_amaun    = empty($row['AMOUNT'])?0:$row['AMOUNT'];
                            endif;
                        endif;

                        echo '<td width="12%">'.$bill_number.$resit_number.$journal_number.'</td>';
                        echo '<td width="10%">'.date_display($row['DT_BILL']).'</td>';
//                        echo '<td>'.$resit_number.'</td>';
                        echo '<td width="10%">'.$row['TR_CODE'].'</td>';
                        echo '<td width="11%"><strong>'.$row['ACCOUNT_NUMBER'].'</strong><br>'.$row['NAME'].'</td>';
                        echo '<td width="15%">'.$row['ITEM_DESC'].'</td>';
                        echo '<td width="10%">'.$type.'</td>';

                        $amount = $amount+($bill_amaun-($resit_amaun));
                        echo '<td  width="10%" class="text-right">'.num($bill_amaun,4).'</td>';
                        echo '<td  width="10%" class="text-right">'.num($resit_amaun,4).'</td>';
                        echo '<td  width="12%" class="text-right">'.num($amount,4).'</td>';
                        echo '</tr>';

                        // $amount = $amount+($bill_amaun-($resit_amaun));
                        // echo '<td  width="10%" class="text-right">'.num($bill_amaun,3).'</td>';
                        // echo '<td  width="10%" class="text-right">'.num($resit_amaun,3).'</td>';
                        // echo '<td  width="12%" class="text-right">'.num($amount,3).'</td>';
                        // echo '</tr>';

                        // $amount = $amount+($bill_amaun-($resit_amaun));
                        // echo '<td  width="10%" class="text-right">'.number_format(abs($bill_amaun),2).'</td>';
                        // echo '<td  width="10%" class="text-right">'.number_format(abs($resit_amaun),2).'</td>';
                        // echo '<td  width="12%" class="text-right">'.num($amount,4).'</td>';
                        // echo '</tr>';
                    endforeach;
                    echo '</tbody>';
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
        <?php
            if(uri_segment(3)=='post'):
                ?>
                    $('#my_form').submit();
                <?php
            endif;
        ?>

        window.onafterprint = function(){
            console.log("Printing completed...");
            $('.data-print').addClass('table-scroll')
        }
        // var type_id = $('#type_id').val();
        //get_category_by_type('<?php //echo search_default($data_search,'category_id')?>//');
    });
</script>