<?php
notify_msg('notify_msg');
?>
<style>
    tr{
        font-size: 14px;
    }

    page .table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 0px solid #c8ced3;
    }

    page .table td{
        padding: 0rem
    }

    .acc_info tr{
        font-weight: bold;
    }

    .acc_info{
        width: 100%;
    }

    .border-div{
        border-radius: 5px;
        border:solid #9DA3A8 1px;
    }

    body {
        background: rgb(204,204,204);
    }

    @media print{
        .card{
            border: 0px;
        }
    }

    Size : 8.27 inches and 11.69 inches

    @page Section1 {
        size: 8.27in 11.69in;
        margin: .5in .5in .5in .5in;
        mso-header-margin: .5in;
        mso-footer-margin: .5in;
        mso-paper-source: 0;
    }

    div.Section1 {
        page: Section1;
    }

    /*page {*/
        /*background: white;*/
        /*display: block;*/
        /*margin: 0 auto;*/
        /*margin-bottom: 0.5cm;*/
        /*box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);*/
    /*}*/
    /*page[size="A4"] {*/
        /*width: 21cm;*/
        /*height: 29.7cm;*/
    /*}*/
    /*page[size="A4"][layout="portrait"] {*/
        /*width: 29.7cm;*/
        /*height: 21cm;*/
    /*}*/
    /*@media print {*/
        /*body, page {*/
            /*margin: 0;*/
            /*box-shadow: 0;*/
        /*}*/
    /*}*/

    /*page[size="A4"] {*/
        /*background: white;*/
        /*width: 21cm;*/
        /*height: 29.7cm;*/
        /*display: block;*/
        /*margin: 0 auto;*/
        /*margin-bottom: 0.5cm;*/
        /*box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);*/
    /*}*/
    /*@media print {*/
        /*body, page[size="A4"] {*/
            /*margin: 0;*/
            /*box-shadow: none;*/
        /*}*/
    /*}*/

</style>
<page size="A4">
<div class="card card-accent-info">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <button onclick="window.print()" class="btn btn-warning btn-sm pull-right">Print</button>
            </div>
            <div class="col-sm-3 text-center">
                <img src="/assets/images/logompkj.gif" style="max-width: 100px;width: 100%">
            </div>
            <div class="col-sm-6 text-center">
                <h2 style="font-weight: bold">MAJLIS PERBANDARAN KAJANG</h2>
                MENARA MPKj, JALAN CEMPAKA PUTIH, OFF JALAN SEMENYIH<br>
                43000 KAJANG, SELANGOR DARUL EHSAN,<br>
                TEL: 03-87377899 FAKS: 03-87377897<br>
                Laman web : www.mpkj.gov.my<br>
                <strong style="font-size: 16px;"><?php echo $statement_type?> - SEWAAN</strong>
            </div>
            <div class="col-sm-3">
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-6">
                <div class="p-2 border-div" style="height: 100%">
                    <?php
                    echo $account['NAME'].'<br>';
                    if($account['ASSET_ADD'] || $account['ASSET_NAME']):
                        echo ($account['ASSET_NAME'])?$account['ASSET_NAME'].'<br>':$account['ASSET_ADD'].'<br>';
                    endif;
                    echo $account['CATEGORY_NAME'].'<br>';
                    echo $account['ADDRESS'];

                    $show_asset_addr = array(
                        "W001"
                    );

                    if (in_array($account['CATEGORY_CODE'], $show_asset_addr)) {

                        echo $account['ASSET_ADD'].'<br>';
                    }

                    $show_location_billboard = array(
                        "I004",
                        "I005",
                        "I006",
                        "I007",
                        "I008",
                        "I009",
                        "I010",
                        "I011",
                        "I012",
                        "I013",
                        "I014",
                        "I016",
                        "I017",
                        "I018",
                        "I019"
                    );

                    if (in_array($account['CATEGORY_CODE'], $show_location_billboard)) {

                        echo $account['LOCATION_BILLBOARD'].'<br>';
                    }


                   // echo $account['ADDRESS_1'].', '.$account['ADDRESS_2'].'<br>';
                   // echo $account['ADDRESS_3'].'<br>';
                   // echo $account['POSTCODE'].' '.$account['STATE'];
                    ?>
                </div>
            </div>
            <div class="col-6">
                <div class="p-2">
                    <table class="acc_info">
                        <tr>
                            <td colspan="2" class="text-center">
                                <?php
                                    echo "<img alt='testing' src='/bill/generate_barcode?codetype=Code39&size=40&text=".$account['ACCOUNT_NUMBER']."&print=true'/>";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="30%">
                                NO AKAUN
                            </td>
                            <td>
                                :&nbsp;<?php echo $account['ACCOUNT_NUMBER']?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                TARIKH BIL
                            </td>
                            <td>
                                :&nbsp;<?php echo date_display($bill_master['DT_ADDED'])?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                NO AKAUN LAMA
                            </td>
                            <td>
                                :&nbsp;<?php echo $account['ACCOUNT_NUMBER_OLD']?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-8">
                <div class="p-2 border-div" style="min-height: 400px;">
                    <div class="text-center"><strong>BUTIRAN BAYARAN</strong><br><br></div>
                    <table class="table">
                        <?php
                            $total = 0;

                            if($current_bill)
                            {
                                echo '  <tr>';
                                echo '    <td colspan="3" style="width: 100px;font-weight: bold">BIL SEMASA</td>';
                                echo '  </tr>';
                                foreach ($current_bill as $current_bill_item)
                                {
                                    $total = $total + $current_bill_item['AMOUNT'];                                

                                    if ($current_bill_item['AMOUNT'] < 0) 
                                    { 
                                        $amount = 0;
                                    }
                                    else 
                                    {
                                        $amount = $current_bill_item['AMOUNT'];
                                    }

                                    echo '  <tr>';
                                    echo '    <td style="width: 35px;">&nbsp;</td>';
                                    echo '    <td>'.$current_bill_item['TR_DESC'].'</td>';
                                    echo '    <td class="text-right">'.num($amount).'</td>';
                                    echo '  </tr>';

                                    if ( $current_bill_item['AMOUNT'] < 0)
                                    {
                                        echo '  <tr>';
                                        echo '    <td>&nbsp;</td>';
                                        echo '    <td>LEBIHAN BAYARAN '.$current_bill_item['TR_DESC'].'</td>';
                                        echo '    <td class="text-right">'.num($current_bill_item['AMOUNT']).'</td>';
                                        echo '  </tr>';
                                    }
                                }

                                // Ini sample function bagi line subtotal utk paparan lebih
                                // Commented as no better view is found
                                // echo '<tr>';
                                // echo '    <td></td>';
                                // echo '    <td class="text-right" style="border-top: 1px solid;border-bottom: 1px double">'.num($total).'</td>';
                                // echo '</tr>';
                            }

                            if($outstanding_bill)
                            {
                                echo '  <tr style="height:10px;"></tr>';
                                echo '  <tr>';
                                echo '    <td colspan="3" style="width: 100px;font-weight: bold">TUNGGAKAN</td>';
                                echo '  </tr>';
                                // Ini sample function bagi line subtotal utk paparan lebih
                                // Commented as no better view is found
                                // echo "<tr style='height:5px;' ><td colspan='2'></td></tr>";
                                foreach ($outstanding_bill as $outstanding_bill_item)
                                {
                                    $total = $total + $outstanding_bill_item['AMOUNT'];                                

                                    if ($outstanding_bill_item['AMOUNT'] < 0) 
                                    { 
                                        $amount = 0;
                                    }
                                    else 
                                    {
                                        $amount = $outstanding_bill_item['AMOUNT'];
                                    }

                                    echo '  <tr>';
                                    echo '    <td>&nbsp;</td>';
                                    echo '    <td>'.$outstanding_bill_item['TR_DESC'].'</td>';
                                    echo '    <td class="text-right">'.num($amount).'</td>';
                                    echo '  </tr>';

                                    if ( $outstanding_bill_item['AMOUNT'] < 0)
                                    {
                                        echo '  <tr>';
                                        echo '    <td>&nbsp;</td>';
                                        echo '    <td>LEBIHAN BAYARAN '.$outstanding_bill_item['TR_DESC'].'</td>';
                                        echo '    <td class="text-right">'.num($outstanding_bill_item['AMOUNT']).'</td>';
                                        echo '  </tr>';
                                    }
                                }

                                // Ini sample function bagi line subtotal utk paparan lebih
                                // Commented as no better view is found
                                // echo '<tr>';
                                // echo '    <td></td>';
                                // echo '    <td class="text-right" style="border-top: 1px solid;border-bottom: 1px double">'.num($total).'</td>';
                                // echo '</tr>';
                            }

                            if($others_bill)
                            {
                                echo '  <tr style="height:10px;"></tr>';
                                echo '  <tr>';
                                echo '    <td colspan="3" style="width: 100px;">LAIN - LAIN</td>';
                                echo '  </tr>';
                                // Ini sample function bagi line subtotal utk paparan lebih
                                // Commented as no better view is found
                                // echo "<tr style='height:5px;' ><td colspan='2'></td></tr>";
                                foreach ($others_bill as $others_bill_item)
                                {                        
                                    $total = $total + $others_bill_item['AMOUNT'];                                

                                    if ($others_bill_item['AMOUNT'] < 0) 
                                    { 
                                        $amount = 0;
                                    }
                                    else 
                                    {
                                        $amount = $others_bill_item['AMOUNT'];
                                    }

                                    echo '  <tr>';
                                    echo '    <td>&nbsp;</td>';
                                    echo '    <td>'.$others_bill_item['TR_DESC'].'</td>';
                                    echo '    <td class="text-right">'.num($amount).'</td>';
                                    echo '  </tr>';

                                    if ( $others_bill_item['AMOUNT'] < 0)
                                    {
                                        echo '  <tr>';
                                        echo '    <td>&nbsp;</td>';
                                        echo '    <td>LEBIHAN BAYARAN '.$others_bill_item['TR_DESC'].'</td>';
                                        echo '    <td class="text-right">'.num($others_bill_item['AMOUNT']).'</td>';
                                        echo '  </tr>';
                                    }
                                }
                            }
                        ?>
                        <tr style="height:10px;"></tr>
                        <tr>
                            <td colspan="2" style="font-weight: bold">JUMLAH BAYARAN</td>
                            <td class="text-right" style="border-top: 1px solid;border-bottom: 1px double"><?=num($total)?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-4">
                <div class="p-2 border-div" style="border-radius: 6px 6px 0px 0px;border-bottom: 0px;height: 100%; max-height: 38px;padding-left: 0px !important;padding-right: 0px !important;">
                    <div class="text-center"><strong>MAKLUMAN</strong>
                    </div>
                </div>
                
                <textarea <?=$this->auth->restrict_access_V2( $this->curuser,array(6005)) !== true ? "readonly" : "" ?> style="padding:6px;width: 100%;border-radius: 0px 0px 6px 6px;border-top-style: hidden;" rows="13">Sila nyatakan nama Bank Pembayar, Tarikh serta No. Cek / Bank Draf / Banker's Cheque / Kiriman Wang / Wang Pos. 
Kadar sewaan bulanan hendaklah dibayar SELEWAT-LEWATNYA SEBELUM ATAU PADA 7HB setiap bulan. Penalti lewat bayar pada kadar 8% atas jumlah tertunggak akan dikenakan jika kadar sewaan bulanan gagal dibayar dalam tempoh ditetapkan.</textarea>
                <!--
                <div class="p-2 border-div" style="padding:6px;width: 100%;height:auto;border-radius: 0px 0px 6px 6px;border-top-style: hidden;">
                    Sila nyatakan nama Bank Pembayar, Tarikh serta No. Cek / Bank Draf / Banker's Cheque / Kiriman Wang / Wang Pos.
                    <br>
                    Kadar sewaan bulanan hendaklah dibayar <b>SELEWAT-LEWATNYA SEBELUM ATAU PADA 7HB</b> setiap bulan. Penalti lewat bayar pada kadar 8% atas jumlah tertunggak akan dikenakan jika kadar sewaan bulanan gagal dibayar dalam tempoh ditetapkan.
                </div>
                -->
            </div>
        </div>

        <div style="border-bottom: 2px dotted black;font-size: 10px;margin-top: 10px;margin-bottom: 15px;padding-left: 15px;">
            JANGAN CERAIKAN KERATAN
        </div>

        <div class="row">
            <div class="col-8">
                <div class="p-2 border-div">
                    <strong>MAJLIS PERBANDARAN KAJANG - <?php echo $statement_type?> SEWAAN</strong>
                    <table class="acc_info">
                        <tr>
                            <td width="30%">
                                NO AKAUN
                            </td>
                            <td>
                                :&nbsp;<?php echo $account['ACCOUNT_NUMBER']?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                TARIKH BIL
                            </td>
                            <td>
                                :&nbsp;<?php echo date_display($bill_master['DT_ADDED'])?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                NO AKAUN LAMA
                            </td>
                            <td>
                                :&nbsp;<?php echo $account['ACCOUNT_NUMBER_OLD']?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <div class="p-2 border-div" style="min-height: 400px;">
                    <div class="row">
                        <div class="col-4">
                            <div class="text-center"><strong>&nbsp;</strong><br><br></div>
                            <?php
                            echo $account['NAME'].'<br>';
                            if($account['ASSET_ADD'] || $account['ASSET_NAME']):
                                echo ($account['ASSET_NAME'])?$account['ASSET_NAME'].'<br>':$account['ASSET_ADD'].'<br>';
                            endif;
                            echo $account['CATEGORY_NAME'].'<br>';
                            echo $account['ADDRESS'].'<br>';

                           // echo $account['NAME'].'<br>';
                           // echo $account['ADDRESS_1'].', '.$account['ADDRESS_2'].'<br>';
                           // echo $account['ADDRESS_3'].'<br>';
                           // echo $account['POSTCODE'].' '.$account['STATE'];
                            ?>
                            <div style="margin-top: 20px; text-align: center">
                                <?php
                                echo "<img alt='testing' src='/bill/generate_barcode?codetype=Code39&size=40&text=".$account['ACCOUNT_NUMBER']."&print=true'/>";
                                ?>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="text-left"><strong>BUTIRAN BAYARAN</strong><br><br></div>
                            
                            <table class="table">
                                <?php
                                    $total = 0;

                                    if($current_bill)
                                    {
                                        foreach ($current_bill as $current_bill)
                                        {
                                            $total = $total + $current_bill['AMOUNT'];                                

                                            if ($current_bill['AMOUNT'] < 0) 
                                            { 
                                                $amount = 0;
                                            }
                                            else 
                                            {
                                                $amount = $current_bill['AMOUNT'];
                                            }

                                            echo '  <tr>';
                                            echo '    <td>'.$current_bill['TR_DESC'].'</td>';
                                            echo '    <td class="text-right">'.num($amount).'</td>';
                                            echo '  </tr>';

                                            if ( $current_bill['AMOUNT'] < 0)
                                            {
                                                echo '  <tr>';
                                                echo '    <td>LEBIHAN BAYARAN '.$current_bill['TR_DESC'].'</td>';
                                                echo '    <td class="text-right">'.num($current_bill['AMOUNT']).'</td>';
                                                echo '  </tr>';
                                            }
                                        }

                                        // Ini sample function bagi line subtotal utk paparan lebih
                                        // Commented as no better view is found
                                        // echo '<tr>';
                                        // echo '    <td></td>';
                                        // echo '    <td class="text-right" style="border-top: 1px solid;border-bottom: 1px double">'.num($total).'</td>';
                                        // echo '</tr>';
                                    }

                                    if($outstanding_bill)
                                    {
                                        // Ini sample function bagi line subtotal utk paparan lebih
                                        // Commented as no better view is found
                                        // echo "<tr style='height:5px;' ><td colspan='2'></td></tr>";
                                        foreach ($outstanding_bill as $outstanding_bill_item)
                                        {
                                            $total = $total + $outstanding_bill_item['AMOUNT'];                                

                                            if ($outstanding_bill_item['AMOUNT'] < 0) 
                                            { 
                                                $amount = 0;
                                            }
                                            else 
                                            {
                                                $amount = $outstanding_bill_item['AMOUNT'];
                                            }

                                            echo '  <tr>';
                                            echo '    <td>'.$outstanding_bill_item['TR_DESC'].'</td>';
                                            echo '    <td class="text-right">'.num($amount).'</td>';
                                            echo '  </tr>';

                                            if ( $outstanding_bill_item['AMOUNT'] < 0)
                                            {
                                                echo '  <tr>';
                                                echo '    <td>LEBIHAN BAYARAN '.$outstanding_bill_item['TR_DESC'].'</td>';
                                                echo '    <td class="text-right">'.num($outstanding_bill_item['AMOUNT']).'</td>';
                                                echo '  </tr>';
                                            }
                                        }

                                        // Ini sample function bagi line subtotal utk paparan lebih
                                        // Commented as no better view is found
                                        // echo '<tr>';
                                        // echo '    <td></td>';
                                        // echo '    <td class="text-right" style="border-top: 1px solid;border-bottom: 1px double">'.num($total).'</td>';
                                        // echo '</tr>';
                                    }

                                    if($others_bill)
                                    {
                                        // Ini sample function bagi line subtotal utk paparan lebih
                                        // Commented as no better view is found
                                        // echo "<tr style='height:5px;' ><td colspan='2'></td></tr>";
                                        foreach ($others_bill as $others_bill_item)
                                        {                        
                                            $total = $total + $others_bill_item['AMOUNT'];                                

                                            if ($others_bill_item['AMOUNT'] < 0) 
                                            { 
                                                $amount = 0;
                                            }
                                            else 
                                            {
                                                $amount = $others_bill_item['AMOUNT'];
                                            }

                                            echo '  <tr>';
                                            echo '    <td>'.$others_bill_item['TR_DESC'].'</td>';
                                            echo '    <td class="text-right">'.num($amount).'</td>';
                                            echo '  </tr>';

                                            if ( $others_bill_item['AMOUNT'] < 0)
                                            {
                                                echo '  <tr>';
                                                echo '    <td>LEBIHAN BAYARAN '.$others_bill_item['TR_DESC'].'</td>';
                                                echo '    <td class="text-right">'.num($others_bill_item['AMOUNT']).'</td>';
                                                echo '  </tr>';
                                            }
                                        }
                                    }
                                ?>
                                <tr>
                                    <td>JUMLAH BAYARAN</td>
                                    <td class="text-right" style="border-top: 1px solid;border-bottom: 1px double"><?=num($total)?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</page>