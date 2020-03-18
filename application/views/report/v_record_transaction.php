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
<div class="card card-accent-info">
    <div class="card-body">
        <!-- START development here -->
        <?php 
            $filterStartDate    = $data_search["date_start"];
            $filterEndDate      = $data_search["date_end"];
            $startYear          = DateTime::createFromFormat('d M Y', $filterStartDate)->format('Y');
            $endYear            = DateTime::createFromFormat('d M Y', $filterEndDate)->format('Y');
            $nextYear           = $endYear;
            
            while ($nextYear >= $startYear)
            {
                echo '<h5 class="card-title">REKOD TAHUN '.$nextYear.'</h5>';
                echo '<div class="table-responsive">';
                echo '  <table id="tablePenyataRekod'.$nextYear.'" class="table table-bordered test1" style="width:100%">';
                echo '      <thead>';
                echo '          <tr>';
                echo '              <th>No. Bil/ No. Resit</th>';
                echo '              <th>Tarikh Bil</th>';
                echo '              <th>Kod Transaksi</th>';
                echo '              <th>Maklumat Akaun</th>';
                echo '              <th>Jenis</th>';
                echo '              <th>Amaun Debit (RM)</th>';
                echo '              <th>Amaun Kredit (RM)</th>';
                echo '              <th>Jumlah Amaun (RM)</th>';
                echo '          </tr>';
                echo '      </thead>';
                echo '      <tbody>';
                $totalAmount = 0;
                foreach ($data_report["$nextYear"] as $row)
                {
                    $totalAmount = ($row["BILL_CATEGORY"] == 'B' ? ($totalAmount + $row["AMOUNT"]) : ($totalAmount - $row["AMOUNT"]));
                    // $totalAmount = $totalAmount + $row["AMOUNT"];
                    echo '      <tr>';
                    echo '          <td>'.$row["BILL_NUMBER"].'</td>';
                    echo '          <td>'.$row["TKH_BIL"].'</td>';
                    echo '          <td>'.$row["TR_CODE"].'</td>';
                    echo '          <td>'.$row["ACCOUNT_ID"].'</td>';
                    echo '          <td>'.$row["BILL_CATEGORY"].'</td>';
                    echo '          <td>'.($row["BILL_CATEGORY"] == 'B' ? $row["AMOUNT"] : '').'</td>';
                    echo '          <td>'.($row["BILL_CATEGORY"] == 'R' ? $row["AMOUNT"] : '').'</td>';
                    echo '          <td>'.$totalAmount.'</td>';
                    echo '      </tr>';
                }
                echo '      </tbody>';                
                echo '  </table>';
                echo '</div>';
                $nextYear = $nextYear - 1;
            }
            
            // echo "<pre>";
            // var_dump($data_report);
        ?>
        <input type="hidden" id="account_number" value="" />
        <input type="hidden" id="account_name" value="" />
        <input type="hidden" id="account_address" value="" />
        <!-- END development here -->
    </div>
</div>
<style type="text/css">
    @page
    {
        size: landscape;
        margin: 10mm;
    }
</style>
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
    });

    $(document).ready(function() 
    {
        var backupTitle = document.title;
        // document.title =    "   <p style='font-size:13px'> No Akaun: "+"</br>"+
        //                     "   <p style='font-size:13px'> Nama    : "+"</br>" +
        //                     "   <p style='font-size:13px'> Alamat Harta    : "+"</p></br>";

        $('.test1').DataTable( {
            "scrollY": "200px",
            "autoWidth" : true,
            "pagingType": "full_numbers",
            "ordering": false,
            "paging": false,
            "searching": false,
            "info": false,
            "dom": 'Bfrtip',
            "buttons": [
            {
                extend: 'print',
                text: 'CETAK',
                customize: function ( win ) 
                {
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                },
                action: function(e, dt, button, config) {
                 
                    // Add code to make changes to table here
     
                    // Call the original action function afterwards to
                    // continue the action.
                    // Otherwise you're just overriding it completely.
                    document.title =    " </br><div class='container'><p style='font-size:13px'> No Akaun: "+"</br>"+
                            "   <p style='font-size:13px'> Nama    : "+"</br>" +
                            "   <p style='font-size:13px'> Alamat Harta    : "+"</p></br></div>";
                    $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                    document.title = backupTitle;
                }
            }
        ]
        } );
        // document.title = backupTitle;
    });

    $.ajax({
            url: "/report/transactionReportHeader",
            type: "POST",
            data: {account_id:1,asset_code:1},
            success: function(data){
                data = JSON.parse(data);
                // console.log("data"+data);
                console.log(data);

                $("#account_number").val(data.account_number);
                $("#account_name").val(data.account_name);
                $("#account_address").val(data.asset_add+"</br>"+data.category_name+"</br>"+data.address);
            }
        });


</script>
