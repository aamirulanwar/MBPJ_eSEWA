<table class="table table-hover table-bordered">
    <tr>
        <th>No.</th>
        <th>Kod Objek</th>
        <th>Keterangan</th>
        <th>Bilangan Bil</th>
        <th>Jumlah Bil</th>
        <th>Jumlah Resit</th>
    </tr>
    <tbody>
    <?php
    if($data_gl):
        $i = 0;
        $total_bill_count       = 0;
        $total_bill_amount      = 0;
        $total_receipt_count    = 0;
        foreach($data_gl as $gl):
            if($gl['bill_count']>0):
                $i=$i+1;
                $total_bill_count   = $total_bill_count + $gl['bill_count'];
                $total_bill_amount  = $total_bill_amount + $gl['bill_amount'];
                $total_receipt_count= $total_receipt_count + $gl['receipt_amount'];
                ?>
                <tr>
                    <td><?php echo $i?>.</td>
                    <td><?php echo $gl['object_code']?></td>
                    <td><?php echo $gl['desc']?></td>
                    <td><?php echo $gl['bill_count']?></td>
                    <td align="right"><?php echo $gl['bill_amount']?></td>
                    <td align="right"><?php echo $gl['receipt_amount']?></td>
                </tr>
            <?php
            endif;
        endforeach;
        ?>
        <tr>
            <td colspan="3" align="right"><strong>Jumlah Keseluruhan</strong></td>
            <td><strong><?php echo $total_bill_count?></strong></td>
            <td align="right"><strong><?php echo $total_bill_amount?></strong></td>
            <td align="right"><strong><?php echo $total_receipt_count?></strong></td>
        </tr>
    <?php
    else:
        echo '<tr><td colspan="4" class="text-center"> - Tiada Rekod - </td></tr>';
    endif;
    ?>
    </tbody>
</table>