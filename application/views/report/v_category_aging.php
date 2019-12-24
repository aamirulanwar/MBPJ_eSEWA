<style>
    .table-aging{
        font-size: 11px !important;
    }
    th{
        text-align: center;
    }
    @media print{
        @page {
            size: landscape
        }
    }
</style>
<form method="post" action="/report/category_aging/" >
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="col-form-label">Jenis harta</label>
                    <select name="asset_type" class="form-control">
                        <?php
                        echo option_value('semua',' - Semua - ','asset_type',search_default($data_search,'asset_type'));
                        ?>
                        <?php
                        if($data_type):
                            foreach ($data_type as $row):
                                echo option_value($row['TYPE_ID'],$row['TYPE_NAME'],'asset_type',search_default($data_search,'asset_type'));
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
                    <label class="col-form-label">Tarikh mula</label>
                    <input type="input" name="date_start" id="date_start" class="form-control date_class" placeholder="Tarikh mula" value="<?php echo set_value('date_start', search_default($data_search,'date_start')) ?>">
                    <label class="no-need-print"><a href="javascript:;" onclick="document.getElementById('date_start').value=''">Kosongkan tarikh mula</a></label>
                </div>
            </div>

            <!--            <div class="form-group row">-->
            <!--                <div class="col-sm-4">-->
            <!--                    <input type="input" name="date_start" class="form-control date_class" placeholder="Tarikh mula" value="--><?php //echo set_value('date_start','')?><!--">-->
            <!--                </div>-->
            <!--                <label class="col-sm-2 col-form-label text-center"> hingga </label>-->
            <!--                <div class="col-sm-4">-->
            <!--                    <input type="input" name="date_end" class="form-control date_class" placeholder="Tarikh tamat" value="--><?php //echo set_value('date_end','')?><!--">-->
            <!--                </div>-->
            <!--            </div>-->
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

            if($data_report):
            ?>
            <div class="pull-right">
                <button onclick="window.print()" class="btn btn-warning btn-sm pull-right">Print</button>
            </div>
            <br>
            <br>
            <?php
            foreach ($data_report as $category):
            echo '<h2 style="text-decoration: underline;margin-bottom: 15px;">'.$category['data_type']['TYPE_NAME'].'</h2>';
            if($category['data_report']):
            ?>
            <table class="table table-hover table-bordered table-aging">
                <tr>
                    <th rowspan="2" style="text-align:center">No.</th>
                    <th rowspan="2" style="text-align:center">Kategory Nama</th>
                    <th rowspan="2" style="text-align:center">Kod Kategori</th>
                    <th colspan="4" style="text-align:center">Bulan (RM)</th>
                    <th colspan="6" style="text-align:center">Tahun (RM)</th>
                    <th rowspan="2" style="text-align:center">Baki (RM)</th>
<!--                    <th rowspan="2" style="text-align:center">Status akaun</th>-->
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
                        <td><?php echo $row['CATEGORY_NAME']?></td>
                        <td><?php echo $row['CATEGORY_CODE']?></td>
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
<!--                        <td class="text-center">--><?php //echo get_status_active($row['STATUS_ACC'])?><!--</td>-->
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
<!--                    <td class="text-right"></td>-->
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
        </div>
    </div>
</div>