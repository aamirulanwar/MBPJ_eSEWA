<?php
    notify_msg('department');
?>
<div class="card card-accent-info">
    <div class="card-header">
        <div class="pull-left">
            <strong> Data akruan bagi bulan <?php echo date('M')." ".date('Y') ?> </strong>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            
            <!-- <pre> -->
            <?php

            // var_dump($bill_record);
            // die();
            ?>
            <table id="table_kewangan_dataTable" class="table table-striped table-bordered">
                <thead>
                    <th>No.</th>
                    <th>Kod Kategori</th>
                    <th>Perihal Kategori</th>
                    <th>Jumlah Bilangan Bil</th>
                    <th>Jumlah Amaun Bil</th>
                </thead>
                <tbody>
                <?php
                    $total_bill_amount = 0;
                    if($bill_record)
                    {
                        $i = 0;
                        foreach($bill_record as $bill)
                        {
                            $i=$i+1;
                            $tab = '10px';
                            $total_bill_amount = $total_bill_amount + $bill["TOTAL_BILL_AMOUNT"];
                            
                            echo "<tr>";
                            echo "<td>".$i."</td>";
                            echo "<td>".$bill["CATEGORY_CODE"]."</td>";
                            echo "<td>".$bill["CATEGORY_NAME"]."</td>";
                            echo "<td>".$bill["TOTAL_BILL_COUNT"]."</td>";
                            echo "<td>".$bill["TOTAL_BILL_AMOUNT"]."</td>";
                            echo "</tr>";
                            
                        }
                    }
                    else
                    {
                        echo '<tr><td colspan="5" class="text-center"> - Tiada Rekod - </td></tr>';
                    }

                ?>
                </tbody>
            </table>
        </div>
        <div>
            <table>
                <tr>
                    <td style="font-weight: bold">Jumlah keseluruhan bilangan bil di proses</td>
                    <td style="min-width: 20px;text-align: center">:</td>
                    <td style="font-weight: bold"><?=$total_bill?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold">Jumlah keseluruhan amaun bil di proses</td>
                    <td style="min-width: 20px;text-align: center">:</td>
                    <td style="font-weight: bold"> RM <?=number_format($total_bill_amount,2)?></td>
                </tr>
            </table>
        </div>
        <div style="padding-top: 20px;">
            <button class="btn btn-primary" onclick="pushToSP()"> Hantar ke Sistem Perakaunan</button>
        </div>
    </div>
    
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#table_kewangan_dataTable').DataTable();
    } );

    function pushToSP()
    {
        $.ajax({
            url: "/integration/pushBillGeneratedToSP",
            type: "GET",
            // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
            // data: {param1: 'value1'},
        })
        .done(function(data) {
            data = JSON.parse(data);
            console.log(data.status);
            if ( data.status == false )
            {
                alert(data.message);
            }
            else if ( data.status == true )
            {
                alert(data.message);
            }
        })
        .fail(function() {
            // console.log("error");
        })
        .always(function() {
            // console.log("complete");
        });
        
    }
</script>