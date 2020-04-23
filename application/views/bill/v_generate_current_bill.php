
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>
<form action="/bill/generate_current_bill/<?php echo uri_segment(3)?>" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Maklumat Akaun</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Penyewa </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo $account['NAME']?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">No. kad pengenalan </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo $account['IC_NUMBER']?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">No. akaun </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext"><?php echo $account['ACCOUNT_NUMBER']?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Jenis / Kod kategori </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext">
                        <?php echo ' <strong>'.$account['TYPE_NAME'].'</strong>' ?>
                        <br>
                        <?php echo ' <strong>'.$account['CATEGORY_CODE'].'</strong> - '.$account['CATEGORY_NAME'] ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Tarikh mula </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext">
                        <?php echo date_display($account['DATE_START']); ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Tarikh tamat </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext">
                        <?php echo date_display($account['DATE_END']); ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Tempoh </label>
                <div class="col-sm-5">
                    <p class="form-control-plaintext">
                        <?php echo $account['RENTAL_DURATION'].' Bulan'; ?>
                    </p>
                </div>
            </div>
            <hr>
            <div class="row mb-4">
                <div class="col-sm-12">
                    <h3 class="header-h1">Maklumat bil semasa (<?php echo $bill_master['BILL_MONTH'].'/'.$bill_master['BILL_YEAR']?>)</h3>
                </div>
            </div>


            <?php
                if($item_bil):
                    foreach ($item_bil as $item):
                        ?>
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label"><?php echo $item['item_desc']?> </label>
                            <div class="col-sm-2">
                                <p class="form-control-plaintext">RM<?php echo num($item['amount'])?></p>
                            </div>
                            <div class="col-sm-1">
                               <p class="form-control-plaintext"><?php echo ($item['bill_category'])?></p>
                            </div>
                        </div>
                        <?php
                    endforeach;
                endif;
            ?>

            <div id="content_transaction">

            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <button type="button" onclick="add_transaction_code('B')" class="btn btn-primary pull-left btn-submit">Tambah kod transaksi bill</button>
                    <!-- <button style="margin-left: 20px;" type="button" onclick="add_transaction_code('J')" class="btn btn-warning pull-left btn-submit">Tambah kod transaksi jurnal</button> -->
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right btn-submit">Simpan</button>
            </div>
        </div>
    </div>
</form>