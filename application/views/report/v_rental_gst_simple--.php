<form method="post" action="/report/gst_rental_simple/">
    <div class="card card-accent-info">
        <div class="card-body">
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
                    <select name="category_id" id="category_id" class="form-control">
                        <option value=""> - Semua - </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="col-form-label">Tarikh mula</label>
                    <input type="input" name="date_start" class="form-control date_class" placeholder="Tarikh mula" value="<?php echo set_value('date_start',$data_search['date_start'])?>">
                </div>
                <div class="col-sm-4">
                    <label class="col-form-label">Tarikh tamat</label>
                    <input type="input" name="date_end" class="form-control date_class" placeholder="Tarikh tamat" value="<?php echo set_value('date_end',$data_search['date_end'])?>">
                </div>
            </div>
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
                if($data_gst):
                    echo '<table class="table table-hover table-bordered">';
//                        echo '<thead>';
//                        echo '<tr>';
//                        echo '<th>&nbsp;</th>';
//                        echo '<th>Jumlah bil</th>';
//                        echo '<th>Penyelarasan bil</th>';
//                        echo '<th>Jumlah resit</th>';
//                        echo '<th>Penyelarasan resit</th>';
//                        echo '</tr>';
//                        echo '</thead>';
                    foreach ($data_gst as $key=>$row):
                        echo '<thead>';

                        echo '<tr>';
                        echo '<th><strong>'.$key.'</strong></th>';
                        echo '<th>Jumlah bil</th>';
                        echo '<th>Penyelarasan bil</th>';
                        echo '<th>Jumlah resit</th>';
                        echo '<th>Penyelarasan resit</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        if($row):
                            foreach ($row as $key_cat=>$category):
                                echo '<tr>';
                                echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;'.$key_cat.'</td>';
                                echo '<td style="text-align: right">';
                                    echo isset($category['b'])?num($category['b'],3):'0';
                                echo '</td>';
                                echo '<td style="text-align: right">';
                                    echo isset($category['jb'])?num($category['jb'],3):'0';
                                echo '</td>';
                                echo '<td style="text-align: right">';
                                    echo isset($category['r'])?num($category['r'],3):'0';
                                echo '</td>';
                                echo '<td style="text-align: right">';
                                    echo isset($category['jr'])?num($category['jr'],3):'0';
                                echo '</td>';
                                echo '</tr>';
                            endforeach;
                            echo '</tbody>';
                        endif;
                    endforeach;
                    echo '<table>';
                endif;
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