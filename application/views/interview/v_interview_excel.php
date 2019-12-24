<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="expires" content="0">
</head>
<body>
    <table class="table table-hover table-bordered" border="1">
        <tr>
            <th class="text-center">Bil.</th>
            <th class="text-center">Lokasi</th>
            <th class="text-center">Nama Pemohon</th>
            <th class="text-center">Alamat</th>
            <th class="text-center">No. IC</th>
            <th class="text-center">Jenis Perniagaan</th>
            <th class="text-center">No. Telefon</th>
            <th class="text-center">Masa t/d</th>
            <th class="text-center">No. Kedai</th>
            <th class="text-center">Bil. Fail</th>
            <th class="text-center">No. Fail</th>
            <th class="text-center">Status</th>
            <th class="text-center">Tarikh</th>
            <th class="text-center">No. Siri</th>
            <th class="text-center">Sewa</th>
            <th class="text-center">Cagaran</th>
        </tr>
        <tbody>
        <?php
        if($interview_application):
            $i = 0;
            foreach($interview_application as $row):
                $i=$i+1;
                ?>
                <tr>
                    <td><?php echo $i?></td>
                    <td><?php echo $row['CATEGORY_NAME']?></td>
                    <td><?php echo $row['NAME']?></td>
                    <td><?php echo $row['ADDRESS_1'].', '.$row['ADDRESS_2'].', '.$row['POSTCODE'].', '.$row['ADDRESS_3'].', '.$row['ADDRESS_STATE']?></td>
                    <td><?php echo display_ic_number($row['IC_NUMBER'])?></td>
                    <td><?php echo $row['RENTAL_USE_NAME']?></td>
                    <td><?php echo display_mobile_number($row['MOBILE_PHONE_NUMBER'])?></td>
                    <td></td>
                    <td><?php echo $row['ASSET_NAME']?></td>
                    <td></td>
                    <td><?php echo $row['FILE_NUMBER']?></td>
                    <td></td>
                    <td><?php echo date_display($row['DATE_INTERVIEW'],'d-m-Y')?></td>
                    <td><?php echo $row['FORM_NUMBER']?></td>
                    <td><?php echo num($row['RENTAL_FEE'])?></td>
                    <td><?php echo num($row['DEPOSIT_RENTAL'])?></td>
                </tr>
            <?php
            endforeach;
        else:
            echo '<tr><td colspan="10" class="text-center"> - Tiada Rekod - </td></tr>';
        endif;
        ?>
        </tbody>
    </table>