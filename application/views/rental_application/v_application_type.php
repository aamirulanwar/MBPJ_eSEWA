<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>
<div class="card card-accent-info">
    <div class="card-header">
        <h3 class="box-title">Pilih jenis permohonan</h3>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <?php
                foreach ($asset_type as $row):
                    if($row['FORM_TYPE']>0):
                    ?>
                        <div class="col-sm-6 col-md-4">
                            <div class="card">
                                <a href="/rental_application/form/<?php echo urlEncrypt($row['TYPE_ID'])?>">
                                    <div class="card-body">
                                        <h4><?php echo strtoupper($row['TYPE_NAME'])?></h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php
                    else:
                        ?>
                        <div class="col-sm-6 col-md-4">
                            <div class="card">
                                <a href="/account/create_acc_direct/<?php echo urlEncrypt($row['TYPE_ID'])?>">
                                    <div class="card-body">
                                        <h4><?php echo strtoupper($row['TYPE_NAME'])?></h4>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php
                    endif;
                endforeach;
            ?>
        </div>
    </div>
</div>