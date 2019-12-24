<style>
    .table-aging{
        font-size: 11px !important;
    }

    th{
        text-align: center;
    }
</style>
<?php

if($data_report):
?>
<div class="pull-right">
    <button onclick="window.print()" class="btn btn-warning btn-sm pull-right">Print</button>
</div>
<br>
<br>
<?php
    foreach ($data_report as $category):
        echo '<h2 style="text-decoration: underline;margin-bottom: 15px;">'.$category['data_category']['CATEGORY_NAME'].' ('.$category['data_category']['CATEGORY_CODE'].')</h2>';
        if($category['data_report']):
        ?>
        <table class="table table-hover table-bordered">
            <tr>
                <th rowspan="2" style="text-align:center">No.</th>
                <th rowspan="2" style="text-align:center">Nama</th>
                <th rowspan="2" style="text-align:center">Kod Harta / <br>No Akaun</th>
                <th colspan="4" style="text-align:center">Bulan (RM)</th>
                <th colspan="6" style="text-align:center">Tahun (RM)</th>
                <th rowspan="2" style="text-align:center">Baki (RM)</th>
                <th rowspan="2" style="text-align:center">Status Akaun</th>
            </tr>
            <tr>
                <th>1-3</th>
                <th>4-6</th>
                <th>7-9</th>
                <th>10-12</th>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>>6</th>
            </tr>
            <?php
            $cnt = 0;
            $data_1 = 0;
            $data_2 = 0;
            $data_3 = 0;
            $data_4 = 0;
            $data_5 = 0;
            $data_6 = 0;
            $data_7 = 0;
            $data_8 = 0;
            $data_9 = 0;
            $data_10 = 0;
            foreach($category['data_report'] as $row):
                $cnt=$cnt+1;
                $data_1 = $data_1+$row['data_1'];
                $data_2 = $data_2+$row['data_2'];
                $data_3 = $data_3+$row['data_3'];
                $data_4 = $data_4+$row['data_4'];
                $data_5 = $data_5+$row['data_5'];
                $data_6 = $data_6+$row['data_6'];
                $data_7 = $data_7+$row['data_7'];
                $data_8 = $data_8+$row['data_8'];
                $data_9 = $data_9+$row['data_9'];
                $data_10 = $data_10+$row['data_10'];

                $data_all = $data_1+$data_2+$data_3+$data_4+$data_5+$data_6+$data_7+$data_8+$data_9+$data_10;
                ?>
                <tr>
                    <td class="text-right"><?php echo $cnt?>.</td>
                    <td><?php echo $row['NAME']?></td>
                    <td><?php echo $row['ASSET_NAME']?> / <br><?php echo $row['ACCOUNT_NUMBER']?></td>
                    <td class="text-right"><?php echo num($row['data_1'],3)?></td>
                    <td class="text-right"><?php echo num($row['data_2'],3)?></td>
                    <td class="text-right"><?php echo num($row['data_3'],3)?></td>
                    <td class="text-right"><?php echo num($row['data_4'],3)?></td>
                    <td class="text-right"><?php echo num($row['data_5'],3)?></td>
                    <td class="text-right"><?php echo num($row['data_6'],3)?></td>
                    <td class="text-right"><?php echo num($row['data_7'],3)?></td>
                    <td class="text-right"><?php echo num($row['data_8'],3)?></td>
                    <td class="text-right"><?php echo num($row['data_9'],3)?></td>
                    <td class="text-right"><?php echo num($row['data_10'],3)?></td>
                    <td class="text-right"><?php echo num($row['data_1']+$row['data_2']+$row['data_3']+$row['data_4']+$row['data_5']+$row['data_6']+$row['data_7']+$row['data_8']+$row['data_9']+$row['data_10'],3)?></td>
                    <td class="text-center"><?php echo get_status_active($row['STATUS_ACC'])?></td>
                </tr>
            <?php
            endforeach;
            ?>
            <tr>
                <td colspan="3" align="right"><strong>JUMLAH (RM)</strong></td>
                <td class="text-right"><?php echo num($data_1,3)?></td>
                <td class="text-right"><?php echo num($data_2,3)?></td>
                <td class="text-right"><?php echo num($data_3,3)?></td>
                <td class="text-right"><?php echo num($data_4,3)?></td>
                <td class="text-right"><?php echo num($data_5,3)?></td>
                <td class="text-right"><?php echo num($data_6,3)?></td>
                <td class="text-right"><?php echo num($data_7,3)?></td>
                <td class="text-right"><?php echo num($data_8,3)?></td>
                <td class="text-right"><?php echo num($data_9,3)?></td>
                <td class="text-right"><?php echo num($data_10,3)?></td>
                <td class="text-right"><?php echo num($data_all,3)?></td>
                <td class="text-right"></td>
            </tr>
            <tbody>
            <?php
        endif;
        echo '</table>';
    endforeach;
else:
    if($_POST):
        echo '<table class="table table-hover table-bordered table-aging">';
        echo '<tr><td class="text-center"> - Tiada data - </td></tr>';
        echo '</table>';
    endif;
endif;
?>
