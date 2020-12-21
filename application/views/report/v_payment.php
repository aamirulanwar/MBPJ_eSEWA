<style>
    th{
        text-align: center;
    }
    
    @media print{
        .table th, .table td{
            padding: 4px !important;
            /*font-size: 8px !important;*/
        }

        .table-responsive{
            overflow: hidden;
        }

        .card-footer{
            display: none;
        }

    }
    ::-webkit-scrollbar {
        width: 5px;
        height: 10px;
        border-radius: 5px;
    }

    ::-webkit-scrollbar-thumb {
        background: rgba(90, 90, 90,0.1);
    }
    ::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.2);
    }
    .table th, .table td{
        padding: 4px !important;
        font-size: 11px !important;
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
                if($data_report)
                {
                    ?>
                    <div class="pull-right">
                        <a class="btn btn-warning btn-sm pull-right" href="/report/print_pembayaran_penyewa" target="_blank">Cetak</a>
                    </div>
                    <br>
                    <br>
                    <?php
                    $category_code = "";
                    foreach ($data_report as $category => $account )
                    {
                        echo '<h1 style="text-decoration: underline">'.$category.'</h1>';
                        
                        $account_number = "";
                        $account_name = "";
                        $asset_name = "";
                        $total_transaction = 0;
                        $i = 0;

                        echo '<table class="table table-hover table-bordered table-aging" style="margin-bottom: 0px">';
                        echo '  <thead>';
                        echo '      <tr>';
                        echo '          <th width="5%">No.</th>';
                        echo '          <th width="7%">No akaun</th>';
                        echo '          <th width="9%">Nama</th>';
                        echo '          <th width="9%">Kod Harta</th>';
                        echo '          <th width="9%">No. Bil</th>';
                        echo '          <th width="8%">Tarikh Bil/Resit</th>';
                        echo '          <th width="8%">Kod</th>';
                        echo '          <th width="11%">Keterangan</th>';
                        echo '          <th width="7%">Jenis</th>';
                        echo '          <th width="9%">Amaun Debit (RM)</th>';
                        echo '          <th width="9%">Amaun Kredit (RM)</th>';
                        echo '          <th width="9%">Jumlah (RM)</th>';
                        echo '      </tr>';
                        echo '  </thead>';
                        echo '</table>';
                        echo '<div class="table-own" style="max-height: 300px; overflow: overlay; padding: 0px;">';
                        echo '  <table class="table table-hover table-bordered table-adjustment">';
                        echo '      <tbody>';

                        foreach ($account as $account_id => $account_details) 
                        {
                            $i = $i + 1;
                            $data_amount = 0;

                            foreach ($account_details as $single_row) 
                            {
                                
                                $reference_no = $single_row["REFERENCE_NO"];
                                $tkh_bil = $single_row["TKH_BIL"];
                                $tr_code_new = $single_row["TR_CODE"];
                                $tr_code_old = $single_row["TR_CODE_OLD"];
                                $tr_item_desc = $single_row["TR_DESC"];
                                $bill_category = $single_row["BILL_CATEGORY"];
                                $amount_credit = floatval( $single_row["AMOUNT"] );
                                $data_amount = floatval($data_amount)  - floatval($single_row["AMOUNT"]);

                                echo '      <tr>';
                                if ( $account_number != $single_row["ACCOUNT_NUMBER"] )
                                {
                                    $total_transaction = count($account_details);
                                    $account_number = $single_row["ACCOUNT_NUMBER"];
                                    $account_name = $single_row["NAME"];
                                    $asset_name = $single_row["ASSET_NAME"];

                                    echo '     <td width="5%" rowspan="'.$total_transaction.'"> '.$i.' </td>';
                                    echo '     <td width="7%" rowspan="'.$total_transaction.'"> '.$account_number.' </td>';
                                    echo '     <td width="9%" rowspan="'.$total_transaction.'"> '.$account_name.' </td>';
                                    echo '     <td width="9%" rowspan="'.$total_transaction.'"> '.$asset_name.' </td>';
                                }
                                echo '         <td width="9%">'.$reference_no.'</td>';
                                echo '         <td width="8%">'.$tkh_bil.'</td>';
                                echo '         <td width="8%">'.$tr_code_new.'</td>';
                                echo '         <td width="11%">'.$tr_item_desc.'</td>';
                                echo '         <td width="7%">'.$bill_category.'</td>';
                                echo '         <td width="9%" style="text-align: right"> 0.00 </td>';
                                echo '         <td width="9%" style="text-align: right">'.num($amount_credit,4,2).'</td>';
                                echo '         <td width="9%" style="text-align: right">'.num($data_amount,4,2).'</td>';

                                echo '      </tr>';
                            }
                        }
                        echo '      </tbody>';
                        echo '  </table>';
                        echo '</div>';
                    }
                }
                else
                {
                    echo '<table class="table table-hover table-bordered table-aging">';
                    echo '<tr><td class="text-center"> - Tiada data - </td></tr>';
                    echo '</table>';
                }
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