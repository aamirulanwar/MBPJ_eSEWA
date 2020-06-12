
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>
<form action="/bill/generate_current_bill/<?php echo uri_segment(3)?>" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Senarai Kelulusan Jurnal</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No. Akaun</th>
                        <th>No. Bil</th>
                        <th>Kod Jurnal/Keterangan</th>
                        <th>Bil Bulan/Tahun</th>
                        <th>Amaun</th>
                        <th>Status</th>
                        <th>Dimasukkan Oleh</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0; ?>
                        <?php if (count($data) > 0): ?>
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
                                    <?php if($d['STATUS_APPROVAL']==0): ?>
                                    <span class="badge badge-warning">Pending</span>
                                    <?php elseif($d['STATUS_APPROVAL']==1): ?>
                                    <span class="badge badge-success">Lulus</span>
                                    <?php elseif($d['STATUS_APPROVAL']==2): ?>
                                    <span class="badge badge-danger">Batal</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo $d['USER_NAME']; ?>
                                    <br>
                                    <small><i>
                                        <?php 
                                        $date = DateTime::createFromFormat("d#M#y H#i#s*A",$d['CREATED_AT']);
                                        $new_date = $date->format('d-m-Y H:i:s');

                                        echo $new_date;
                                        // echo date('Y-m-d H:i:s',strtotime($new_date." +8 hours"));
                                        ?>
                                    </i></small>
                                </td>
                                <td>
                                    <?php if($d['STATUS_APPROVAL']==0): ?>
                                    <button type="button" class="btn btn-success" onclick="showApprovalConfirmation(<?php echo $d['ID']; ?>)">Lulus <i class="fa fa-check-circle"></i></button>
                                    <button type="button" class="btn btn-danger" onclick="showDeclineConfirmation(<?php echo $d['ID']; ?>)">Batal <i class="fa fa-times-circle"></i></button>
                                    <?php endif; ?>
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

<div class="modal fade" id="lulusJurnalModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header alert-info">
                <h5 class="modal-title" id="exampleModalLongTitle">Pengesahan Lulus Jurnal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Adakah anda pasti untuk meluluskan jurnal ini ?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <span id="confirmApproveBtn"></span>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="batalJurnalModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header alert-danger">
                <h5 class="modal-title" id="exampleModalLongTitle">Pengesahan Batal Jurnal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Adakah anda pasti untuk membatalkan jurnal ini ?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <span id="confirmDeclineBtn"></span>
            </div>
        </div>
    </div>
</div>
<script>
    function showApprovalConfirmation(id) {
        
        $('#confirmApproveBtn').html('<button type="button" class="btn btn-primary" onclick="doConfirmJurnal('+id+')">Ya, pasti</button>');
        $('#lulusJurnalModal').modal('show');
    }
    function showDeclineConfirmation(id) {
        
        $('#confirmDeclineBtn').html('<button type="button" class="btn btn-danger" onclick="doDeclineJurnal('+id+')">Ya, pasti</button>');
        $('#batalJurnalModal').modal('show');
    }

    function doConfirmJurnal(id) {
        
        $.ajax({
            url: '/journal/update/'+id,
            type: 'POST',
            data: {
                approve:1
            }
        })
        .done(function(r) {
            if (r.status) {

                alert(r.message);
                location.reload();
            }
        })
        .fail(function(e) {
            console.log(e);
            console.log("error");
        });
    }

    function doDeclineJurnal(id) {
        $.ajax({
            url: '/journal/update/'+id,
            type: 'POST',
            data: {
                approve:2
            }
        })
        .done(function(r) {
            if (r.status) {

                alert(r.message);
                location.reload();
            }
        })
        .fail(function(e) {
            console.log(e);
            console.log("error");
        });
    }
</script>