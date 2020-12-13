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
            size: landscape;
            margin: 0.2in;
        }
        div.landscape {
            overflow: hidden;
            /*page-break-after: always;*/
            background: white;
        }
        div.landscape {
            /*width: 276mm;*/
            /*height: 190mm;*/
        }
        .table th, .table td{
            padding: 1px !important;
            font-size: 8px !important;
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
        padding: 1px !important;
        font-size: 8px !important;
    }
</style>
<form method="post" action="/report/adjustment_statement_ringkasan">
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>

            <div class="form-group row">

                <div class="col-sm-4">
                    <label class="col-form-label">Jenis harta</label>
                    <select onchange="get_category_by_type('')" name="type_id" id="type_id" class="form-control">
                        <?php
                        echo option_value('0','- Semua - ','type_id');
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
                <a class="btn btn-warning btn-sm pull-right" href="/report/print_penyata_penyesuaian_ringkasan" target="_blank">Cetak</a>
            </div>
            <br>
            <br>
            <?php

                $type_id = 0;
                $total_group_debit = 0;
                $total_group_credit = 0;
                $total_group_balance = 0;
                

                foreach ($data_report as $group => $data_list) 
                {
                    $total_row_debit = 0;
                    $total_row_credit = 0;
                    $total_row_balance = 0;

                    echo '<h3 style="text-decoration: underline">'.$group.'</h3>';
                    echo '
                        <table class="table table-hover table-bordered table-adjustment" style="margin-bottom: 0px;">
                            <thead>
                                <tr>
                                    <th width="5%" rowspan="2">Kod Kategori</th>
                                    <th width="5%" rowspan="2">Tunggakan Sewa (RM)</th>
                                    <th colspan="2">Pelarasan</th>
                                    <th width="5%" rowspan="2">Terimaan Tunggakan (RM)</th>
                                    <th colspan="2">Pelarasan</th>
                                    <th width="5%" rowspan="2">Lebihan (RM)</th>
                                    <th colspan="2">Pelarasan</th>
                                    <th width="5%" rowspan="2">Sewaan Semasa (RM)</th>
                                    <th colspan="2">Pelarasan</th>
                                    <th width="5%" rowspan="2">Terimaan Semasa (RM)</th>
                                    <th colspan="2">Pelarasan</th>
                                    <th colspan="2">Jumlah </th>
                                    <th width="5%" rowspan="2">Baki Bawa Kehadapan (RM)</th>
                                </tr>
                                <tr>
                                    <th width="5%">Debit (RM)</th>
                                    <th width="5%">Kredit (RM)</th>
                                    <th width="5%">Debit (RM)</th>
                                    <th width="5%">Kredit (RM)</th>
                                    <th width="5%">Debit (RM)</th>
                                    <th width="5%">Kredit (RM)</th>
                                    <th width="5%">Debit (RM)</th>
                                    <th width="5%">Kredit (RM)</th>
                                    <th width="5%">Debit (RM)</th>
                                    <th width="5%">Kredit (RM)</th>
                                    <th width="5%">Jumlah (Debit) (RM)</th>
                                    <th width="5%">Jumlah (Kredit) (RM)</th>
                                </tr>
                            </thead>
                        </table>
                    ';
                    echo '<div class="table-own" style="max-height:300px; overflow: overlay">';
                    echo '  <table class="table table-hover table-bordered table-adjustment">';
                    echo '      <tbody>';

                    if ( count($data_list) > 0 )
                    {
                        foreach ($data_list as $row) 
                        {
                            $category_name                    =  $row["CATEGORY_NAME"];
                            $category_code                    =  $row["CATEGORY_CODE"];
                            $total_tunggakan                  =  ( $row["TOTAL_TUNGGAKAN"] == "" ? 0 : $row["TOTAL_TUNGGAKAN"] );
                            $total_jurnal_tunggakan_db        =  ( $row["TOTAL_JURNAL_TUNGGAKAN_DB"] == "" ? 0 : $row["TOTAL_JURNAL_TUNGGAKAN_DB"] );
                            $total_jurnal_tunggakan_cr        =  abs( $row["TOTAL_JURNAL_TUNGGAKAN_CR"] == "" ? 0 : $row["TOTAL_JURNAL_TUNGGAKAN_CR"] );
                            $total_byrn_tunggakan             =  ( $row["TOTAL_BYRN_TUNGGAKAN"] == "" ? 0 : $row["TOTAL_BYRN_TUNGGAKAN"] );
                            $total_jurnal_byrn_tunggakan_db   =  ( $row["TOTAL_JURNAL_BYRN_TUNGGAKAN_DB"] == "" ? 0 : $row["TOTAL_JURNAL_BYRN_TUNGGAKAN_DB"] );
                            $total_jurnal_byrn_tunggakan_cr   =  abs( $row["TOTAL_JURNAL_BYRN_TUNGGAKAN_CR"] == "" ? 0 : $row["TOTAL_JURNAL_BYRN_TUNGGAKAN_CR"] );
                            $total_lebihan                    =  ( $row["TOTAL_LEBIHAN_TAHUN_LEPAS"] == "" ? 0 : $row["TOTAL_LEBIHAN_TAHUN_LEPAS"] );
                            $total_jurnal_lebihan_db          =  ( $row["TOTAL_JURNAL_LEBIHAN_TAHUN_LEPAS_DB"] == "" ? 0 : $row["TOTAL_JURNAL_LEBIHAN_TAHUN_LEPAS_DB"] );
                            $total_jurnal_lebihan_cr          =  abs( $row["TOTAL_JURNAL_LEBIHAN_TAHUN_LEPAS_CR"] == "" ? 0 : $row["TOTAL_JURNAL_LEBIHAN_TAHUN_LEPAS_CR"] );

                            $total_bil_semasa                 =  ( $row["TOTAL_BIL_SEMASA"] == "" ? 0 : $row["TOTAL_BIL_SEMASA"] );
                            $total_jurnal_bil_semasa_db       =  ( $row["TOTAL_JURNAL_BIL_SEMASA_DB"] == "" ? 0 : $row["TOTAL_JURNAL_BIL_SEMASA_DB"] );
                            $total_jurnal_bil_semasa_cr       =  abs( $row["TOTAL_JURNAL_BIL_SEMASA_CR"] == "" ? 0 : $row["TOTAL_JURNAL_BIL_SEMASA_CR"] );

                            $total_byrn_bil_semasa            =  ( $row["TOTAL_BYRN_BIL_SEMASA"] == "" ? 0 : $row["TOTAL_BYRN_BIL_SEMASA"] );
                            $total_jurnal_byrn_bil_semasa_db  =  ( $row["TOTAL_JURNAL_BYRN_BIL_SEMASA_DB"] == "" ? 0 : $row["TOTAL_JURNAL_BYRN_BIL_SEMASA_DB"] );
                            $total_jurnal_byrn_bil_semasa_cr  =  abs( $row["TOTAL_JURNAL_BYRN_BIL_SEMASA_CR"] == "" ? 0 : $row["TOTAL_JURNAL_BYRN_BIL_SEMASA_CR"] );


                            $total_debit   = $total_tunggakan + $total_jurnal_tunggakan_db + $total_jurnal_byrn_tunggakan_cr + $total_jurnal_lebihan_cr + $total_bil_semasa + $total_jurnal_bil_semasa_db + $total_jurnal_byrn_bil_semasa_cr;
                            $total_credit  = $total_jurnal_tunggakan_cr + $total_byrn_tunggakan + $total_jurnal_byrn_tunggakan_db + $total_lebihan + $total_jurnal_lebihan_db + $total_jurnal_bil_semasa_cr + $total_byrn_bil_semasa + $total_jurnal_byrn_bil_semasa_db;
                            $total_balance = $total_debit - $total_credit;

                            $total_row_debit = $total_row_debit + $total_debit;
                            $total_row_credit = $total_row_credit + $total_credit;
                            $total_row_balance = $total_row_balance + $total_balance;

                            echo '<tr>';
                            echo '  <td width="5%">'.$category_name.' - '.$category_code.'</td>';
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_tunggakan , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_tunggakan_db , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_tunggakan_cr , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_byrn_tunggakan , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_byrn_tunggakan_db , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_byrn_tunggakan_cr , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_lebihan , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_lebihan_db , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_lebihan_cr , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_bil_semasa , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_bil_semasa_db , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_bil_semasa_cr , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_byrn_bil_semasa , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_byrn_bil_semasa_db , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_byrn_bil_semasa_cr , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_debit , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_credit , 2, '.', ',')."</td>";
                            echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_balance , 2, '.', ',')."</td>";
                            echo "</tr>";
                        }
                    }
                    else
                    {
                        echo "<tr><td colspan = '19' style = 'text-align: center;'> -- TIADA DATA -- </td></tr>";
                    }

                    echo '      </tbody>';
                    echo '  </table>';
                    echo '</div>';
                    
                    echo '
                        <table class="table table-hover table-bordered ">
                            <thead>
                                <tr>
                                    <th style="vertical-align: middle; text-align: right" >Jumlah Keseluruhan (Debit) (RM)</th>
                                    <th style="vertical-align: middle; text-align: right" >Jumlah Keseluruhan (Kredit) (RM)</th>
                                    <th style="vertical-align: middle; text-align: right" >Jumlah Baki Bawa Kehadapan (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="vertical-align: middle; text-align: right" > '.number_format( $total_row_debit, 2, '.', ',').'</td>
                                    <td style="vertical-align: middle; text-align: right" > '.number_format( $total_row_credit, 2, '.', ',').'</td>
                                    <td style="vertical-align: middle; text-align: right" > '.number_format( $total_row_balance, 2, '.', ',').'</td>
                                </tr>
                            </tbody>
                        </table>
                    ';

                    $total_group_debit = $total_group_debit + $total_row_debit;
                    $total_group_credit = $total_group_credit + $total_row_credit;
                    $total_group_balance = $total_group_balance + $total_row_balance;
                }

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
                echo '<td style="vertical-align: middle; text-align: right" >'.number_format($total_group_debit, 2, '.', ',').'</td>';
                echo '<td style="vertical-align: middle; text-align: right" >'.number_format($total_group_credit, 2, '.', ',').'</td>';
                echo '<td style="vertical-align: middle; text-align: right" >'.number_format($total_group_balance, 2, '.', ',').'</td>';
                echo '</tr>';
                echo '</tbody>';
                echo '</table>';
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