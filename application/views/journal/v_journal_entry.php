
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>
<form action="/bill/generate_current_bill/<?php echo uri_segment(3)?>" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Senarai Kemasukan Jurnal</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No. Akaun</th>
                        <th>No. Bil</th>
                        <th>Bil Bulan/Tahun</th>
                        <th>Amaun</th>
                        <th>Status</th>
                        <th>Updated by/at</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0; ?>
                    <?php foreach ($data as $d): ?>
                        <?php $i++; ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$d['ACCOUNT_NUMBER']?></td>
                            <td><?=$d['BILL_NUMBER']?></td>
                            <td><?=$d['BILL_MONTH']?>/<?=$d['BILL_YEAR']?></td>
                            <td>RM <?=number_format($d['AMOUNT'],2)?></td>
                            <td>
                                <?php if($d['STATUS_APPROVAL']==0): ?>
                                <span class="badge badge-warning">Pending</span>
                                <?php elseif($d['STATUS_APPROVAL']==1): ?>
                                <span class="badge badge-success">Lulus</span>
                                <?php elseif($d['STATUS_APPROVAL']==2): ?>
                                <span class="badge badge-danger">Batal</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (is_null($d['UPDATED_BY'])): ?>
                                    <i>Rekod belum dikemaskini</i>
                                <?php else: ?>
                                    <?php echo $d['USER_NAME']; ?>
                                    <br>
                                    <small><i>
                                        <?php 
                                        $date = DateTime::createFromFormat("d#M#y H#i#s*A",$d['UPDATED_AT']);
                                        $new_date = $date->format('Y-m-d H:i:s');

                                        echo $new_date;
                                        ?>
                                    </i></small>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            
        </div>
    </div>
</form>