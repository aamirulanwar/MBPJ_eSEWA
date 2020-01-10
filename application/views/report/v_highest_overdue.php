<form method="post" action="/report/highest_overdue/<?php echo uri_segment(3)?>">
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="col-form-label">Jenis harta</label>
                    <select onchange="get_category_by_type('')" name="type_id" id="type_id" class="form-control">
                        <option value=""> - Semua - </option>
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
                        <option value=""> - Semua - </option>
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
<!--                <div class="col-sm-4">-->
<!--                    <label class="col-form-label">Susun mengikut</label>-->
<!--                    <select name="order_by" class="form-control">-->
<!--                        --><?php
//                        echo option_value('1','Tunggakan tertinggi','order_by',search_default($data_search,'order_by'));
//                        echo option_value('2','Senarai tunggakan','order_by',search_default($data_search,'order_by'));
//                        ?>
<!--                    </select>-->
<!--                </div>-->
            </div>
<!--            <div class="form-group row">-->
<!--                <div class="col-sm-4">-->
<!--                    <label class="col-form-label">Tarikh mula</label>-->
<!--                    <input type="input" name="date_start" class="form-control date_class" placeholder="Tarikh mula" value="--><?php //echo set_value('date_start',$data_search['date_start'])?><!--">-->
<!--                </div>-->
<!--                <div class="col-sm-4">-->
<!--                    <label class="col-form-label">Tarikh tamat</label>-->
<!--                    <input type="input" name="date_end" class="form-control date_class" placeholder="Tarikh tamat" value="--><?php //echo set_value('date_end',$data_search['date_end'])?><!--">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="form-group row">-->
<!--                <div class="col-sm-4">-->
<!--                    <input type="input" name="date_start" class="form-control date_class" placeholder="Tarikh mula" value="--><?php //echo set_value('date_start',$data_search['date_start'])?><!--">-->
<!--                </div>-->
<!--                <label class="col-sm-2 col-form-label text-center"> hingga </label>-->
<!--                <div class="col-sm-4">-->
<!--                    <input type="input" name="date_end" class="form-control date_class" placeholder="Tarikh tamat" value="--><?php //echo set_value('date_end',$data_search['date_end'])?><!--">-->
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
//                pre($data_gst);
                $i = 0;
                $all_rental_charge      = 0;
                $all_tahun_lepas        = 0;
                $all_tahun_semasa       = 0;
                $all_jumlah_tunggakan   = 0;
                if($data_report):
                    ?>
                    <div class="pull-right">
                        <button onclick="print_report()" class="btn btn-warning btn-sm pull-right">Print</button>
                    </div>
                    <br>
                    <br>
                    <?php
                    echo '<table class="table table-hover table-bordered table-scroll data-print" style="font-size: 9px;">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th width="4%">Bil</th>';
                    echo '<th width="6%">Jenis Harta</th>';
                    echo '<th width="8%">No Akaun</th>';
                    echo '<th width="9%">Nama</th>';
                    echo '<th width="5%">Kod Harta</th>';
                    echo '<th width="10%">Alamat Perniagaan Harta</th>';
                    echo '<th width="9%">Tarikh Mula</th>';
                    echo '<th width="9%">Tarikh Akhir</th>';
                    echo '<th width="10%">Kadar Sewaan Bulanan (RM)</th>';
                    echo '<th width="10%">Tunggakan Sewaan (RM)</th>';
                    echo '<th width="10%">Tunggakan Sewaan Tahun Semasa (RM)</th>';
                    echo '<th width="10%">Jumlah Keseluruhan Tunggakan (RM)</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '</body>';
                    foreach ($data_report as $row):
                        $i = $i+1;
                        echo '<tr>';
                        echo '<td width="4%">'.$i.'</td>';
                        echo '<td width="6%">'.$row['account_details']['TYPE_NAME'].'</td>';
                        echo '<td width="8%">'.$row['account_details']['ACCOUNT_NUMBER'].'<br>';
                            get_status_active($row['account_details']['STATUS_ACC']);
                        echo '</td>';
                        echo '<td width="9%">'.$row['account_details']['NAME'].'</td>';
                        echo '<td width="5%">'.$row['account_details']['CATEGORY_CODE'].'</td>';
                        echo '<td width="10%">'.$row['account_details']['ASSET_ADD'].'</td>';
                        $date_start = '-';
                        $date_end   = '-';
                        if($row['account_details']['FIRST_DATE_START']):
                            $date_start = date_display($row['account_details']['FIRST_DATE_START']);
                        endif;
                        if($row['account_details']['DATE_END']):
                            $date_end   = date_display($row['account_details']['DATE_END']);
                        endif;
                        echo '<td width="9%">'.$date_start.'</td>';
                        echo '<td width="9%">'.$date_end.'</td>';
                        echo '<td width="10%" style="text-align: right">'.num($row['account_details']['RENTAL_CHARGE'],3).'</td>';
                        echo '<td width="10%" style="text-align: right">'.num($row['TAHUN_LEPAS'],3).'</td>';
                        echo '<td width="10%" style="text-align: right">'.num($row['TAHUN_SEMASA'],3).'</td>';
                        echo '<td width="10%" style="text-align: right">'.num($row['JUMLAH_TUNGGAKAN'],3).'</td>';
                        echo '</tr>';

                        $all_rental_charge      += $row['account_details']['RENTAL_CHARGE'];
                        $all_tahun_lepas        += $row['TAHUN_LEPAS'];
                        $all_tahun_semasa       += $row['TAHUN_SEMASA'];
                        $all_jumlah_tunggakan   += $row['JUMLAH_TUNGGAKAN'];
                    endforeach;
                    echo '</tbody>';
                    echo '</table>';

                    echo '<table class="table table-hover table-bordered">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th class="text-center">Bil Akaun</th>';
                    echo '<th class="text-right">Jumlah Kadar Sewaan Bulanan</th>';
                    echo '<th class="text-right">Jumlah Tunggakan Sewaan (RM)</th>';
                    echo '<th class="text-right">Jumlah Tunggakan Sewaan Tahun Semasa (RM)</th>';
                    echo '<th class="text-right">Jumlah Keseluruhan Tunggakan (RM)</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    echo '<tr>';
                    echo '<td class="text-center">'.$i.'</td>';
                    echo '<td class="text-right">'.num($all_rental_charge,3).'</td>';
                    echo '<td class="text-right">'.num($all_tahun_lepas,3).'</td>';
                    echo '<td class="text-right">'.num($all_tahun_semasa,3).'</td>';
                    echo '<td class="text-right">'.num($all_jumlah_tunggakan,3).'</td>';
                    echo '</tr>';
                    echo '</tbody>';
                    echo '</table>';
                else:
                    if($_POST):
                        echo '<div class="text-center"> - Tiada data - </div>';
                    endif;
                endif;
            ?>
        </div>
    </div>
</div>

<script>
    $( document ).ready(function() {
        // var type_id = $('#type_id').val();
        get_category_by_type('<?php echo search_default($data_search,'category_id')?>');
    });
</script>