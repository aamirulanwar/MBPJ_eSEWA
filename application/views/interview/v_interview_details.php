<?php
notify_msg('notify_msg');
?>
<div class="card card-accent-info">
    <div class="card-header">
        <h3 class="box-title">Senarai Pemohon</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered" border="1">
                <tr>
                    <th>No.</th>
                    <th>Tarikh permohonan</th>
                    <th>Pemohon</th>
                    <th>No. rujukan</th>
                    <th>Lokasi permohonan</th>
                    <th>Jenis perniagaan</th>
                    <th class="text-center">Terperinci</th>
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
                            <td><?php echo date_display($row['DATE_APPLICATION'])?></td>
                            <td>
                                <?php echo $row['NAME']?><br>
                                <?php echo ($row['APPLICANT_TYPE']==APPLICANT_TYPE_INDIVIDUAL)? display_ic_number($row['IC_NUMBER']):$row['COMPANY_REGISTRATION_NUMBER']?>
                            </td>
                            <td><?php echo $row['REF_NUMBER']?></td>
                            <td>
                                <?php echo ' <strong>'.$row['CATEGORY_CODE'].'</strong> - '.$row['CATEGORY_NAME'] ?>
                            </td>
                            <td>
                                <?php echo ' <strong>'.$row['RENTAL_USE_CODE'].'</strong> - '.$row['RENTAL_USE_NAME'] ?>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-block btn-info btn-display" target="_blank" href="/rental_application/application_process/<?php echo urlEncrypt($row['APPLICATION_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Lihat </a>
                                <br><i class="icons font-xl mt-5 cui-note"></i><div><a href="/interview/doc_interview_rating/<?php echo urlEncrypt($row['APPLICATION_ID'])?>" target="_blank">Borang Pemarkahan</div></a>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                else:
                    echo '<tr><td colspan="10" class="text-center"> - Tiada Rekod - </td></tr>';
                endif;
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>