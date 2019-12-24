<table class="table table-hover table-bordered">
    <tr>
        <!-- <th style="text-align:center">No.</th> -->
        <th style="text-align:center">Kod Kategori</th>
        <th style="text-align:center">Keterangan</th>
        <th style="text-align:center">No. Akaun</th>
        <th style="text-align:center">Nama</th>
        <th style="text-align:center">Kod Harta</th>
        <th style="text-align:center">Alamat Perniagaan/Harta</th>
        <th style="text-align:center">Tarikh Mula</th>
        <th style="text-align:center">Tarikh Akhir</th>
        <th style="text-align:center">Kadar Sewaan Bulanan</th>
        <th style="text-align:center">Tunggakan Sewaan</th>
        <th style="text-align:center">Tunggakan Sewaan Tahun Semasa</th>
        <th style="text-align:center">Jumlah Keseluruhan Tunggakan</th>
    </tr>
    <tbody>
        <?php
        $cnt = 0;
        foreach($data_list as $row):
            $cnt = $cnt+1;
            ?>
            <tr>
                <!-- <td><?php echo $cnt?>.</td> -->
                <td><?php echo $row['CATEGORY_CODE']?></td>
                <td><?php echo $row['CATEGORY_NAME']?></td>
                <td><?php echo $row['ACCOUNT_NUMBER']?></td>
                <td><?php echo $row['NAME']?></td>
                <td></td>
                <td><?php echo $row['CATEGORY_NAME']?></td>
                <td style="text-align:center"></td>
                <td style="text-align:center"></td>
                <td style="text-align:right"><?php echo $row['RENTAL_CHARGE']?></td>
                <td style="text-align:right"><?php echo $row['TOTAL_AMOUNT']?></td>
                <td style="text-align:right">0.00</td>
                <td style="text-align:right"><?php echo $row['TOTAL_AMOUNT']?></td>
            </tr>
            <?php
        endforeach;
        ?>
    <tbody>
</table>