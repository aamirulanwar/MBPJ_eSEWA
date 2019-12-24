<table class="table table-hover table-bordered">
<?php
if($a_type):
    foreach($a_type as $type):
        ?>
            <tr>
                <th rowspan="2" style="text-align:center">No.</th>
                <th rowspan="2" style="text-align:center">Unit</th>
                <th rowspan="2" style="text-align:center"><?php echo $type['TYPE_NAME']?></th>
                <th colspan="4" style="text-align:center">Bulan</th>
                <th colspan="6" style="text-align:center">Tahun</th>
                <th rowspan="2" style="text-align:center">Baki</th>
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
                <th>>5</th>
            </tr>
            <?php
            $cnt = 0;
            foreach($data_category as $category):
                if($type['TYPE_ID']==$category['type_id']):
                    $cnt = $cnt+1;
                ?>
                <tr>
                    <td><?php echo $cnt?>.</td>
                    <td><?php echo $category['total_unit']?></td>
                    <td><?php echo $category['category_name']?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php
                endif;
            endforeach;
            ?>
            <tr>
                <td colspan="3" align="right"><strong>JUMLAH</strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tbody>
            <tr>
                <td colspan="10" style="border-left:none; border-right:none"></td>
            </tr>
    <?php
    endforeach;
endif;
?>
</table>
</tbody>