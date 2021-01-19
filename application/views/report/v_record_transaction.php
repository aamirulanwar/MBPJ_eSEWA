<style>
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
        width: 1px;
        /*height: 10px;*/
        /*border-radius: 5px;*/
    }

    ::-webkit-scrollbar-thumb {
        background: rgba(90, 90, 90,0.1);
    }
    ::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.2);
    }
</style>

<?php
    if(uri_segment(3)=='post'):
        echo '<form id="my_form" method="post" action="/report/record_transaction/'.uri_segment(4).'/'.uri_segment(5).'">';
    else:
        echo '<form id="my_form" method="post" action="/report/record_transaction/'.uri_segment(3).'/'.uri_segment(4).'">';
    endif;
?>    
    <!-- Modal -->
    <div class="modal fade" id="loadMe" tabindex="-1" role="dialog" data-toggle="modal" aria-labelledby="loadMeLabel" >
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div style="align:center">
                    <img class="navbar-brand-full" src="/assets/images/waiting.gif" width="120" alt="MPKj">
                </div>
                <div clas="loader-txt">
                  <p>Laporan Rekod Transaksi Sedang Dijana<br><br><small>Sila tunggu sementara rekod sedang dijana</small></p>
                </div>
            </div>
            <a href="#" id="ClosePopup" style="display:none" data-dismiss="modal"> </a>
        </div>
      </div>
    </div>


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
<script type="text/javascript">
    function closePopup()
    {
        $('#loadMe').modal('hide');
        // console.log("enter");
    }
    function openPopup()
    {
        $('#loadMe').modal('show');
        // console.log("test");
    }
</script>
<div class="card card-accent-info">
    <div id="reportContainer" class="card-body" >
        <!-- START development here -->
        <?php 
            $filterStartDate    = $data_search["date_start"];
            $filterEndDate      = $data_search["date_end"];
            $startYear          = DateTime::createFromFormat('d M Y', $filterStartDate)->format('Y');
            $endYear            = DateTime::createFromFormat('d M Y', $filterEndDate)->format('Y');
            $nextYear           = $endYear;

            // echo "<script>openPopup();</script>";
            
            while ($nextYear >= $startYear)
            {
                echo '<div class = "pull-right">';
                echo '  <button class="btn btn-warning" onclick="printYear('.$nextYear.')">Cetak Tahun '.$nextYear.' sahaja</button>';
                echo '</div>';
                echo '<br>';
                echo '<h5 class="card-title">REKOD TAHUN '.$nextYear.'</h5>';
                echo '<div class="table-responsive">';
                echo '  <table id="tablePenyataRekod'.$nextYear.'" class="table test1 table-bordered" style="width:100%;font-size:9pt;">';
                echo '      <thead>';
                echo '          <tr>';
                echo '              <th align="center" style="text-align:center;vertical-align:middle;color:grey">No. Bil/ No. Resit</th>';
                echo '              <th align="center" style="text-align:center;vertical-align:middle">Tarikh Bil</th>';
                echo '              <th align="center" style="text-align:center;vertical-align:middle">Kod Transaksi</th>';
                echo '              <th align="center" style="text-align:center;vertical-align:middle">Perihal Transaksi</th>';
                echo '              <th align="center" style="text-align:center;vertical-align:middle">Maklumat Akaun</th>';
                echo '              <th align="center" style="text-align:center;vertical-align:middle">Jenis</th>';
                echo '              <th align="center" style="text-align:center;vertical-align:middle">Amaun Debit (RM)</th>';
                echo '              <th align="center" style="text-align:center;vertical-align:middle">Amaun Kredit (RM)</th>';
                echo '              <th align="center" style="text-align:center;vertical-align:middle">Jumlah (RM)</th>';
                echo '          </tr>';
                echo '      </thead>';
                echo '      <tbody>';

                if ( isset($data_report["$nextYear"] ) )
                {
                    $totalAmount = 0;

                    foreach ($data_report["$nextYear"] as $row)
                    {
                        $debitAmount = 0;
                        $creditAmount = 0;

                        $account_no = $row["ACCOUNT_NUMBER"];
                        $item_desc = $row["ITEM_DESC"];
                        $bill_category = $row["BILL_CATEGORY"];

                        if ( $row["TR_CODE"] == "" && $row["TR_CODE_OLD"] != ""  )
                        {
                            $tr_code = $row["TR_CODE_OLD"];
                        }
                        else
                        {
                            $tr_code = $row["TR_CODE"];
                        }

                        if ( substr($tr_code,0,1) == "1" )
                        {
                            $debitAmount = $row["AMOUNT"];
                            $totalAmount = $totalAmount + $debitAmount;

                            // Format style amount to be display
                            $debitAmount_display = number_format($debitAmount,2,'.',',');
                            $creditAmount_display = NULL;
                            $totalAmount_display = number_format($totalAmount,2,'.',',');
                        }
                        else if ( substr($tr_code,0,1) == "2" )
                        {
                            $creditAmount = $row["AMOUNT"];
                            $totalAmount = $totalAmount - $creditAmount;
                            
                            // Format style amount to be display
                            $debitAmount_display = NULL;
                            $creditAmount_display = number_format($creditAmount,2,'.',',');
                            $totalAmount_display = number_format($totalAmount,2,'.',',');
                        }

                        /*
                        if ( $row["MASTER_BILL_CATEGORY"] == 'B' && $row["BILL_CATEGORY"] == 'B' )
                        {
                            $totalAmount = $totalAmount + $row["AMOUNT"];
                            $debitAmount = $row["AMOUNT"];
                            $creditAmount = NULL;
                        }
                        else if ( $row["MASTER_BILL_CATEGORY"] == 'B' && $row["BILL_CATEGORY"] == 'J' )
                        {
                            $totalAmount = $totalAmount + $row["AMOUNT"];
                            $debitAmount = $row["AMOUNT"];
                            $creditAmount = NULL;
                        }
                        else if ( $row["MASTER_BILL_CATEGORY"] == 'R' && $row["BILL_CATEGORY"] == 'R' )
                        {
                            $totalAmount = $totalAmount - $row["AMOUNT"];
                            $debitAmount = NULL;
                            $creditAmount = $row["AMOUNT"];
                        }
                        else if ( $row["MASTER_BILL_CATEGORY"] == 'R' && $row["BILL_CATEGORY"] == 'J' )
                        {
                            $totalAmount = $totalAmount - $row["AMOUNT"];
                            $debitAmount = NULL;
                            $creditAmount = $row["AMOUNT"];
                        }
                        else if ( $row["MASTER_BILL_CATEGORY"] == 'J' && $row["BILL_CATEGORY"] == 'J' )
                        {
                            if ( substr($row["TR_CODE"],0,1) == "1" || substr($row["TR_CODE_OLD"],0,1) == "1" )
                            {
                                $totalAmount = $totalAmount + $row["AMOUNT"];
                                $debitAmount = NULL;
                                $creditAmount = $row["AMOUNT"];
                            }
                            else if ( substr($row["TR_CODE"],0,1) == "2" || substr($row["TR_CODE_OLD"],0,1) == "2" )
                            {
                                $totalAmount = $totalAmount - $row["AMOUNT"];
                                $debitAmount = NULL;
                                $creditAmount = $row["AMOUNT"];
                            }
                        }
                        */



                        echo '      <tr>';
                        echo '          <td align="center">'.$row["REFERENCE_NO"].'</td>';
                        echo '          <td align="center">'.$row["TKH_BIL"].'</td>';
                        echo '          <td align="left">'.$tr_code.'</td>';
                        echo '          <td align="left">'.$item_desc.'</td>';
                        echo '          <td align="center">'.$account_no.'</td>';
                        echo '          <td align="center">'.$bill_category.'</td>';
                        echo '          <td align="right">'.$debitAmount_display.'</td>';
                        echo '          <td align="right">'.$creditAmount_display.'</td>';
                        echo '          <td align="right">'.$totalAmount_display.'</td>';
                        echo '      </tr>';
                    }
                }

                echo '      </tbody>';                
                echo '  </table>';
                echo '</div>';
                echo '</br>';
                $nextYear = $nextYear - 1;
            }
            
            if (isset($account_details[0]))
            {
                $account_details = $account_details[0];                
            }
            else
            {
                $account_details['ACCOUNT_NUMBER']="";
                $account_details['NAME']="";
                $account_details['ASSET_ADD']="";
                $account_details['CATEGORY_NAME']="";
                $account_details['ADDRESS']="";
                $account_details['ESTIMATION_RENTAL_CHARGE']=0;
                $account_details['STATUS_ACC']="-";
                $user_details["USER_NAME"]="";
            }

            // echo "<script>closePopup();</script>";
        ?>        
        <input type="hidden" id="account_number" value="<?=$account_details['ACCOUNT_NUMBER'];?>" />
        <input type="hidden" id="account_name" value="<?=$account_details['NAME'];?>" />
        <input type="hidden" id="account_rental_charge" value="<?=$account_details['ESTIMATION_RENTAL_CHARGE'];?>" />
        <input type="hidden" id="account_status" value="<?=$account_details['STATUS_ACC'];?>" />
        <input type="hidden" id="account_address1" value="<?=$account_details['ASSET_ADD'];?>" />
        <input type="hidden" id="account_address2" value="<?=$account_details['CATEGORY_NAME'];?>" />
        <input type="hidden" id="account_address3" value="<?=$account_details['ADDRESS'];?>" />
        <input type="hidden" id="user_name" value="<?=$user_details["USER_NAME"]?>" />
        <!-- END development here -->
    </div>
    <div class="card-footer">
        <button class="btn btn-primary" onclick="printAll()">CETAK SEMUA</button>
    </div>
</div>
<style type="text/css">
    @page
    {
        size: portrait;
        margin: 10mm;
    }
</style>

<script type="text/javascript">
    function printAll()
    {
        window.open('/report/print_all_record_transaction','_blank');
    }

    function printYear(year)
    {
        window.open('/report/print_all_record_transaction?year=' + year,'_blank');
    }
</script>
