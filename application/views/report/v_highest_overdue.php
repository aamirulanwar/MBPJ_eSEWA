<style>
    .table-aging{
        font-size: 11px !important;
    }

    th{
        text-align: center;
    }
    .table th, .table td{
        padding: 1px !important;
        font-size: 11px !important;
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
    @media print{
        @page {
            size: landscape;
            margin: 0.2in;
        }
        .table th, .table td{
            padding: 1px !important;
            font-size: 9px !important;
        }
    }
</style>
<form method="post" action="/report/highest_overdue/<?php echo uri_segment(3)?>">
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="col-form-label">Jenis harta</label>
                    <select onchange="get_category_by_type('')" name="type_id" id="type_id" class="form-control">
                        <option value="-1"> - Semua - </option>
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
                        <option value="-1"> - Semua - </option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label class="col-form-label">Status akaun</label>
                    <select id="acc_status" name="acc_status" class="form-control">
                        <option value="-1"> - Semua - </option>
                        <?php
                        echo option_value(STATUS_ACCOUNT_ACTIVE,'Aktif','acc_status',search_default($data_search,'acc_status'));
                        echo option_value(STATUS_ACCOUNT_NONACTIVE,'Tidak aktif','acc_status',search_default($data_search,'acc_status'));
                        ?>
                    </select>
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
            <div class="pull-right">
                <?php 
                    if ( $data_search["search_segment"] == "two")
                    {
                        echo '<a class="btn btn-warning btn-sm pull-right" href="/report/print_highest_overdue/two" target="_blank">Cetak</a>';
                    }
                    else if ( $data_search["search_segment"] == "")
                    {
                        echo '<a class="btn btn-warning btn-sm pull-right" href="/report/print_highest_overdue" target="_blank">Cetak</a>';
                    }
                    
                ?>
            </div>
            <br>
            <br>                    
            <table class="table table-hover table-bordered" style="margin-bottom: 0px;">
                <thead>
                    <tr>
                        <th width="04%" style="text-align: center; vertical-align: top; " >Bil</th>
                        <th width="06%" style="text-align: center; vertical-align: top; " >Jenis Harta</th>
                        <th width="08%" style="text-align: center; vertical-align: top; " >No Akaun</th>
                        <th width="10%" style="text-align: center; vertical-align: top; " >Nama</th>
                        <th width="06%" style="text-align: center; vertical-align: top; " >Kod Harta</th>
                        <th width="08%" style="text-align: center; vertical-align: top; " >Status Akaun</th>
                        <th width="14%" style="text-align: center; vertical-align: top; " >Alamat Perniagaan Harta</th>
                        <th width="11%" style="text-align: center; vertical-align: top; " >Kadar Sewaan Bulanan (RM)</th>
                        <th width="11%" style="text-align: center; vertical-align: top; " >Tunggakan Sewaan (RM)</th>
                        <th width="11%" style="text-align: center; vertical-align: top; " >Tunggakan Sewaan Tahun Semasa (RM)</th>
                        <th width="11%" style="text-align: center; vertical-align: top; " >Jumlah Keseluruhan Tunggakan (RM)</th>
                    </tr>
                </thead>
            </table>
            <div class="table-own" style="max-height: 300px; overflow: overlay">
                <table class="table table-hover table-bordered">
                    <tbody>
                    <?php
                        $i = 0;
                        $all_rental_charge     = 0.00;
                        $all_tahun_lepas       = 0.00;
                        $all_tahun_semasa      = 0.00;
                        $all_jumlah_tunggakan  = 0.00;

                        if ( count($data_report) > 0 )
                        {
                            foreach ($data_report as $row)
                            {
                                $i = $i+1;


                                if( $row['ASSET_ADD'] != "" && $row['ASSET_NAME'] != "" )
                                {
                                    $asset_address = $row['ASSET_NAME'].'<br>'.$row['CATEGORY_NAME'].'<br>'.$row['ADDRESS'].'<br>';
                                }
                                else
                                {
                                    $asset_address = $row['ASSET_ADD'].'<br>'.$row['CATEGORY_NAME'].'<br>'.$row['ADDRESS'].'<br>';
                                }

                                $monthly_charge = floatval($row["RENTAL_CHARGE"]) + floatval($row["WASTE_MANAGEMENT_CHARGE"]);
                                $total_outstanding_amount = ( floatval($row["CURRENT_BILLED_AMOUNT"]) + floatval($row["OUTSTANDING_AMOUNT"]) ) - ( floatval($row["PAY_CURRENT_BILLED_AMOUNT"]) + floatval($row["PAY_OUTSTANDING_AMOUNT"]) );

                                if ( $total_outstanding_amount > 0 )
                                {
                                    $total_current_year_outstanding_charge = floatval($row["CURRENT_BILLED_AMOUNT"]) - floatval($row["PAY_CURRENT_BILLED_AMOUNT"]);
                                    $previous_year_outstanding_charge = floatval($row["OUTSTANDING_AMOUNT"]) - floatval($row["PAY_OUTSTANDING_AMOUNT"]);
                                }
                                else
                                {
                                    $total_outstanding_amount = 0.00;
                                    $total_current_year_outstanding_charge = 0.00;
                                    $previous_year_outstanding_charge = 0.00;
                                }
                                

                                echo '<tr>';
                                echo '  <td width="04%" style="text-align: center; vertical-align: middle ">'.$i.'</td>';
                                echo '  <td width="06%" style="text-align: center; vertical-align: middle ">'.$row['TYPE_NAME'].'</td>';
                                echo '  <td width="08%" style="text-align: center; vertical-align: middle ">'.$row['ACCOUNT_NUMBER'].'</td>';
                                echo '  <td width="10%" style="text-align: center; vertical-align: middle ">'.$row['NAME'].'</td>';
                                echo '  <td width="06%" style="text-align: center; vertical-align: middle ">'.$row['CATEGORY_CODE'].'</td>';
                                echo '  <td width="08%" style="text-align: center; vertical-align: middle ">'.($row['STATUS_ACC'] == "1" ? "Aktif" : "Tidak Aktif").'</td>';
                                echo '  <td width="14%" style="text-align: center; vertical-align: middle ">'.$asset_address.'</td>';
                                echo '  <td width="11%" style="text-align: right; vertical-align: middle ">'.num($monthly_charge,3).'</td>';
                                echo '  <td width="11%" style="text-align: right; vertical-align: middle ">'.num($previous_year_outstanding_charge,3).'</td>';
                                echo '  <td width="11%" style="text-align: right; vertical-align: middle ">'.num($total_current_year_outstanding_charge,3).'</td>';
                                echo '  <td width="11%" style="text-align: right; vertical-align: middle ">'.num($total_outstanding_amount,3).'</td>';
                                echo '</tr>';

                                $all_rental_charge     = $all_rental_charge + $monthly_charge;
                                $all_tahun_lepas       = $all_tahun_lepas + $previous_year_outstanding_charge;
                                $all_tahun_semasa      = $all_tahun_semasa + $total_current_year_outstanding_charge;
                                $all_jumlah_tunggakan  = $all_jumlah_tunggakan + $total_outstanding_amount;
                            }
                        }
                        else
                        {
                            echo '<tr><td colspan="11" style="text-align: center; font-size: 11pt;"> - Tiada data - </td></tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
            <br>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Bil Akaun</th>
                        <th class="text-right">Jumlah Kadar Sewaan Bulanan</th>
                        <th class="text-right">Jumlah Tunggakan Sewaan (RM)</th>
                        <th class="text-right">Jumlah Tunggakan Sewaan Tahun Semasa (RM)</th>
                        <th class="text-right">Jumlah Keseluruhan Tunggakan (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center"> <?=$i?> </td>
                        <td class="text-right"> <?=num($all_rental_charge,3)?>      </td>
                        <td class="text-right"> <?=num($all_tahun_lepas,3)?>        </td>
                        <td class="text-right"> <?=num($all_tahun_semasa,3)?>       </td>
                        <td class="text-right"> <?=num($all_jumlah_tunggakan,3)?>   </td>
                    </tr>
                </tbody>
            </table>
                
        </div>
    </div>
</div>

<script>
    $( document ).ready(function() {
        // var type_id = $('#type_id').val();
        get_category_by_type('<?php echo search_default($data_search,'category_id')?>');
    });
</script>