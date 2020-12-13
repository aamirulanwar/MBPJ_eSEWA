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
    .table th, .table td{
        padding: 1px !important;
        font-size: 8px !important;
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
</style>
<form method="post" action="/report/adjustment_statement">
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="col-form-label">Jenis harta</label>
                    <select onchange="get_category_by_type('')" name="type_id" id="type_id" class="form-control">
                        <option value="0"> - Semua - </option>
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
                        <option value="0"> - Semua - </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="col-form-label">Status akaun</label>
                    <select name="acc_status" class="form-control">
                        <option value="0"> - Semua - </option>
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
        <div class="table-responsive landscape" style="height: 500px;">
            <div class="pull-right">
                <a class="btn btn-warning btn-sm pull-right" href="/report/print_penyata_penyesuaian_terperinci" target="_blank">Cetak</a>
            </div>
            <br>
            <br>
            <table class="table table-hover table-bordered table-adjustment" style="margin-bottom: 0px;">
                <thead>
                    <tr>
                        <th colspan="4">Akaun</th>
                        <th width="5%" rowspan="2">Tunggakan sewa (RM)</th>
                        <th colspan="2">Pelarasan</th>
                        <th width="5%" rowspan="2">Terimaan Tunggakan (RM)</th>
                        <th colspan="2">Pelarasan</th>
                        <th width="5%" rowspan="2">Lebihan (RM)</th>
                        <th colspan="2">Pelarasan</th>
                        <th width="5%" rowspan="2">Sewaan semasa (RM)</th>
                        <th colspan="2">Pelarasan</th>
                        <th width="5%" rowspan="2">Terimaan semasa (RM)</th>
                        <th colspan="2">Pelarasan</th>
                        <th colspan="2">Jumlah</th>
                        <th width="5%" rowspan="2">Baki Bawa Kehadapan (RM)</th>
                    </tr>
                    <tr>
                        <th width="5%">Akaun No.</th>
                        <th width="6%">Nama Penyewa</th>
                        <th width="5%">Jenis Harta</th>
                        <th width="6%">Kod Kategori</th>
                        <th width="4%">Debit (RM)</th>
                        <th width="4%">Kredit (RM)</th>
                        <th width="4%">Debit (RM)</th>
                        <th width="4%">Kredit (RM)</th>
                        <th width="4%">Debit (RM)</th>
                        <th width="4%">Kredit (RM)</th>
                        <th width="4%">Debit (RM)</th>
                        <th width="4%">Kredit (RM)</th>
                        <th width="4%">Debit (RM)</th>
                        <th width="4%">Kredit (RM)</th>
                        <th width="4%">Jumlah (Debit) (RM)</th>
                        <th width="4%">Jumlah (Kredit) (RM)</th>
                    </tr>
                </thead>
            </table>
            <div class="table-own" style="height: <?=(count($test) > 0 ? "300px;" : "auto" )?>; overflow: overlay">
                <table class="table table-hover table-bordered table-adjustment">
                    <tbody>
                        <?php
                            $total_row_debit = 0;
                            $total_row_credit = 0;
                            $total_row_balance = 0;

                            if ( count($test) > 0 )
                            {
                                foreach ($test as $row) 
                                {
                                    $account_number                   =  $row["ACCOUNT_NUMBER"];
                                    $account_name                     =  $row["ACCOUNT_NAME"];
                                    $type_name                        =  $row["TYPE_NAME"];
                                    $category_name                    =  $row["CATEGORY_NAME"];
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


                                    echo "<tr>";
                                    echo "  <td width='5%'>".$account_number."</td>";
                                    echo "  <td width='6%'>".$account_name."</td>";
                                    echo "  <td width='5%'>".$type_name."</td>";
                                    echo "  <td width='6%'>".$category_name."</td>";
                                    echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_tunggakan , 2, '.', ',')."</td>";
                                    echo "  <td width='4%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_tunggakan_db , 2, '.', ',')."</td>";
                                    echo "  <td width='4%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_tunggakan_cr , 2, '.', ',')."</td>";
                                    echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_byrn_tunggakan , 2, '.', ',')."</td>";
                                    echo "  <td width='4%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_byrn_tunggakan_db , 2, '.', ',')."</td>";
                                    echo "  <td width='4%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_byrn_tunggakan_cr , 2, '.', ',')."</td>";
                                    echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_lebihan , 2, '.', ',')."</td>";
                                    echo "  <td width='4%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_lebihan_db , 2, '.', ',')."</td>";
                                    echo "  <td width='4%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_lebihan_cr , 2, '.', ',')."</td>";
                                    echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_bil_semasa , 2, '.', ',')."</td>";
                                    echo "  <td width='4%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_bil_semasa_db , 2, '.', ',')."</td>";
                                    echo "  <td width='4%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_bil_semasa_cr , 2, '.', ',')."</td>";
                                    echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_byrn_bil_semasa , 2, '.', ',')."</td>";
                                    echo "  <td width='4%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_byrn_bil_semasa_db , 2, '.', ',')."</td>";
                                    echo "  <td width='4%' style='vertical-align: middle; text-align: right'>".number_format( $total_jurnal_byrn_bil_semasa_cr , 2, '.', ',')."</td>";
                                    echo "  <td width='4%' style='vertical-align: middle; text-align: right'>".number_format( $total_debit , 2, '.', ',')."</td>";
                                    echo "  <td width='4%' style='vertical-align: middle; text-align: right'>".number_format( $total_credit , 2, '.', ',')."</td>";
                                    echo "  <td width='5%' style='vertical-align: middle; text-align: right'>".number_format( $total_balance , 2, '.', ',')."</td>";
                                    echo "</tr>";
                                }
                            }
                            else
                            {
                                echo "<tr><td colspan = '22' style = 'text-align: center;'> -- TIADA DATA -- </td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
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
                        <td style="vertical-align: middle; text-align: right" > <?=number_format( $total_row_debit, 2, '.', ',')  ?> </td>
                        <td style="vertical-align: middle; text-align: right" > <?=number_format( $total_row_credit, 2, '.', ',') ?> </td>
                        <td style="vertical-align: middle; text-align: right" > <?=number_format( $total_row_balance, 2, '.', ',')   ?> </td>
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