<form id="my_form" method="post" action="/report/record_transaction/">
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>
            <div class="form-group row">
                <div class="col-sm-4" style="display: none">
                    <label class="col-form-label">Akaun</label>
                    <select name="account_id" id="account_id" class="form-control js-example-basic-single">
                        <option value=""> - Sila pilih - </option>
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
                    echo '<table class="table table-hover table-bordered table-scroll">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>No. bill / no. resit</th>';
                    echo '<th>Tarikh bill/resit</th>';
                    echo '<th>Kod transaksi</th>';
                    echo '<th>Keterangan</th>';
                    echo '<th>Jenis</th>';
                    echo '<th>Amaun debit (RM)</th>';
                    echo '<th>Amaun kredit (RM)</th>';
                    echo '<th>Jumlah Amaun (RM)</th>';
//                    echo '<th>No. bill</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '</body>';

                    $amount = 0;
                    foreach ($data_report as $row):
                        echo '<tr>';
                        $bill_number    = '';
                        $resit_number   = '';
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

                        echo '<td>'.$bill_number.$resit_number.'</td>';
                        echo '<td>'.date_display($row['DT_BILL']).'</td>';
//                        echo '<td>'.$resit_number.'</td>';
                        echo '<td>'.$row['TR_CODE'].'</td>';
                        echo '<td>'.$row['ITEM_DESC'].'</td>';
                        echo '<td>'.$type.'</td>';

                        $amount = $amount+($bill_amaun-($resit_amaun));
                        echo '<td class="text-right">'.num($bill_amaun,3).'</td>';
                        echo '<td class="text-right">'.num($resit_amaun,3).'</td>';
                        echo '<td class="text-right">'.num($amount,3).'</td>';
                        echo '</tr>';
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
        // var type_id = $('#type_id').val();
        //get_category_by_type('<?php //echo search_default($data_search,'category_id')?>//');
    });
</script>