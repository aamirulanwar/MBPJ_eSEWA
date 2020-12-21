
<style>
    th{
        text-align: center;
    }
</style>
 <form method="post" action="/report/journal/">
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="col-form-label">Kod kategori</label>
                    <select name="category_id" id="category_id" class="form-control js-example-basic-single">
                        <option value=""> - Semua - </option>
                        <?php
                        if($data_code_category):
                            foreach ($data_code_category as $row):
                                echo option_value($row['CATEGORY_ID'],$row['CATEGORY_CODE'].' - '.$row['CATEGORY_NAME'],'category_id',search_default($data_search,'category_id'));
                            endforeach;
                        endif;
                        ?>
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
                <div class="col-sm-4">
                    <label class="col-form-label">KOD JURNAL</label>
                    <select name="journal_id" class="form-control">
                        <option value=""> - Semua - </option>
                        <?php
                        if($data_code_journal):
                        foreach ($data_code_journal as $row):
                                echo option_value($row['JOURNAL_ID'],$row['JOURNAL_CODE'].' - '.$row['JOURNAL_DESC'],'journal_id',search_default($data_search,'journal_id'));
                            endforeach;
                        endif;
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
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>

    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Laporan Jurnal Sewaan</h3>
        </div>
        <div class="card-body">
            <div class="pull-right">
                <button onclick="print_journal_sewaan()" class="btn btn-warning btn-sm pull-right">Cetak</button>
            </div>
            <br>
            <br>

            <?php
                $k                = 0;
                $journal          = 0;
            ?>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="text-align:center; vertical-align: middle">BIL</th>
                        <th style="text-align:center; vertical-align: middle">JURNAL</th>
                        <th style="text-align:center; vertical-align: middle">KOD JURNAL / KETERANGAN</th>
                        <th style="text-align:center; vertical-align: middle">NO. AKAUN</th>
                        <th style="text-align:center; vertical-align: middle">KOD TRANSAKSI</th>
                        <th style="text-align:center; vertical-align: middle">AMAUN (RM)</th>
                        <th style="text-align:center; vertical-align: middle">NO. AKAUN BARU</th>
                        <th style="text-align:center; vertical-align: middle">STATUS</th>
                        <th style="text-align:center; vertical-align: middle">Diluluskan Oleh</th>
                        <th style="text-align:center; vertical-align: middle">CATATAN</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $k = 0;
                    $journal = 0;

                    if ( isset($data_report) )
                    {
                        if (count($data_report) > 0)
                        {
                            $i = 0;

                            foreach ($data_report as $d) 
                            {
                                # code...
                                if ( ($d['STATUS_APPROVAL'] == 1 ) or ( $d['STATUS_APPROVAL'] == 2 ) )
                                {
                                    $i = $i + 1;
                                    $k = $k + 1;
                                    $journal = $journal + $d['AMOUNT'];
                                    $status = "";

                                    // Check status approval
                                    if ( $d['STATUS_APPROVAL'] == 0 ) 
                                    {
                                        # code...
                                        $status = "Pending";
                                    }
                                    else if ( $d['STATUS_APPROVAL'] == 1 ) 
                                    {
                                        # code...
                                        $status = "Lulus";
                                    }
                                    else if ( $d['STATUS_APPROVAL'] == 2 ) 
                                    {
                                        # code...
                                        $status = "Batal";
                                    } 
                                    else 
                                    {
                                        // do nothing
                                    }                            

                                    echo "<tr>";
                                    echo "  <td>".$i."</td>";
                                    echo "  <td>".$d['BILL_NUMBER']."</td>";
                                    echo "  <td>".$d['JOURNAL_CODE']." ".$d['JOURNAL_DESC']."</td>";
                                    echo "  <td>".$d['ACCOUNT_NUMBER']."</td>";
                                    echo "  <td>".$d['TR_CODE']."</td>";
                                    echo "  <td>".number_format($d['AMOUNT'],2)."</td>";
                                    echo "  <td style='text-align:center'>". (($d['TRANSFER_ACCOUNT_ID']>=0) ? $d['NEW_ACCOUNT'] : "<i>-</i>") . "</td>";
                                    echo "  <td><span>".$status."</span></td>";
                                    echo "  <td>".$d['USER_NAME']."</td>";
                                    echo "  <td>".$d['REMARK']."</td>";
                                    echo "</tr>";
                                }
                            }
                        }
                        else
                        {
                            echo "<tr><td colspan='10' style='text-align:center'> Tiada Rekod </td></tr>";
                        }
                    }
                    else
                    {
                        echo "<tr><td colspan='10' style='text-align:center'> Tiada Rekod </td></tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer"> </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>
                        Bilangan Jurnal
                    </th>
                    <th>
                        Jumlah Amaun
                    </th>
                    
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center"> <?=$k?> </td>
                    <td class="text-center"> RM <?=number_format($journal,2)?> </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        function print_journal_sewaan()
        {
            var selectedYear = $("#selectedYear").val();
            window.open('/report/print_journal','_blank');
        }
    </script>

    <!-- <script>
        $( document ).ready(function() {
            var type_id = $('#type_id').val();
            get_category_by_type('<?php //echo search_default($data_search,'category_id')?>');
        });
    </script> -->