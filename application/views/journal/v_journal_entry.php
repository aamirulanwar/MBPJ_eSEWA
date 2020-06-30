
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>
<form action="<?php echo uri_segment(3)?>" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Senarai Kemasukan Jurnal</h3>
        </div>
        <div class="card-body">
            <div class="pull-right">
                <!-- <button href="/journal/doc_journal" class="btn btn-warning btn-sm pull-right">Print</button> -->
                <a class="btn btn-warning btn-sm pull-right" href="/journal/doc_journal" target="_blank">Print</a>
            </div>
            <br>
            <br>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No. Akaun</th>
                        <th>No. Bil</th>
                        <th>Kod Jurnal/Keterangan</th>
                        <th>Bil Bulan/Tahun</th>
                        <th>Amaun</th>
                        <th>No. Akaun Baru</th>
                        <th>Status</th>
                        <th>Updated by/at</th>
                    </tr>
                </thead>
                <tbody>
                        <?php $i=0; ?>
                        <?php if ( isset($data) && count($data) > 0 ): ?>
                        <?php foreach ($data as $d): ?>
                        <?php if ($d['STATUS_APPROVAL']==0): ?>
                            <?php $i++; ?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?=$d['ACCOUNT_NUMBER']?></td>
                                <td><?=$d['BILL_NUMBER']?></td>
                                <td><?=$d['JOURNAL_CODE']?> <?=$d['JOURNAL_DESC']?></td>
                                <td><?=$d['BILL_MONTH']?>/<?=$d['BILL_YEAR']?></td>
                                <td>RM <?=number_format($d['AMOUNT'],2)?></td>
                                <td>
                                    <?php if($d['TRANSFER_ACCOUNT_ID']>=0): ?>
                                    <?php echo $d['NEW_ACCOUNT']; ?>
                                    <?php else: ?>
                                        <i>Tiada Rekod</i>
                                    <?php endif; ?>
                                </td>
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
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            
        </div>
    </div>
</form>