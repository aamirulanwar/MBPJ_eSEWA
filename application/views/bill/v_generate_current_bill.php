
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
                    <h3 class="header-h1">Maklumat bil semasa (<?php echo date('n').'/'.date('Y'); ?>)</h3>
                </div>
            </div>
            
            <!-- Display the current bill charge [START]-->
            <?php
                /*
                if($test_function):
                    foreach ($test_function as $item):
                        ?>
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label"><?php echo $item['PERIHAL_CAJ_BARU']; ?> </label>
                            <div class="col-sm-2">
                                <p class="form-control-plaintext">RM<?php echo num($item['CAJ_ANGGARAN']); ?></p>
                            </div>
                            <div class="col-sm-1">
                               <p class="form-control-plaintext"><?php echo $item['BILL_CATEGORY']; ?></p>
                            </div>
                        </div>
                        <?php
                    endforeach;
                endif;
                */
            ?>
            <!-- Display the current bill charge [END]-->
            <!-- Display the outstanding bill charge [START]-->
            <?php
                /*
                if($test_function2):
                    foreach ($test_function2 as $item):
                        ?>
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label"><?php echo $item['TR_DESC_TUNGGAKAN']; ?> </label>
                            <div class="col-sm-2">
                                <p class="form-control-plaintext">RM<?php echo num($item['BALANCE_AMOUNT']); ?></p>
                            </div>
                            <div class="col-sm-1">
                               <p class="form-control-plaintext">B</p>
                            </div>
                        </div>
                        <?php
                    endforeach;
                endif;
                */
            ?>
            <!-- Display the outstanding bill charge [END]-->

            <!-- TEST DISPLAY -->
            <?php
                // // Select list of column that we need to sort by
                // foreach ($list_of_bill as $key => $row) 
                // {
                //     # code...
                //     $display_priority_1[$key]  = $row['DISPLAY_PRIORITY'];
                //     $display_priority_2[$key]  = $row['TR_CODE_NEW'];
                // }

                // // Sort the data retrieved from function with out custom sort by column above
                // array_multisort($display_priority_1, SORT_ASC, $display_priority_2, SORT_ASC, $list_of_bill);

                // Display the sorted data
                if($list_of_bill)
                {
                    foreach ($list_of_bill as $item)
                    {                        
                        echo '<div class="form-group row">';
                        echo '  <label class="col-sm-6 col-form-label">'.$item['TR_DESC'].'</label>';
                        echo '  <div class="col-sm-2">';
                        echo '      <p class="form-control-plaintext">';

                        if ($item['AMOUNT'] < 0) 
                        { 
                            echo "RM 0.00";
                        }
                        else 
                        {
                            echo "RM ".num($item['AMOUNT']);
                        }
                        
                        echo '      </p>';
                        echo '  </div>';
                        echo '  <div class="col-sm-1">';
                        echo '      <p class="form-control-plaintext">B</p>';
                        echo '  </div>';
                        echo '</div>';

                        if ( $item['AMOUNT'] < 0)
                        {
                            echo '<div class="form-group row">';
                            echo '  <label class="col-sm-6 col-form-label">LEBIHAN BAYARAN '.$item['TR_DESC'].'</label>';
                            echo '  <div class="col-sm-2">';
                            echo '      <p class="form-control-plaintext">RM '.num($item['AMOUNT']).'</p>';
                            echo '  </div>';
                            echo '  <div class="col-sm-1">';
                            echo '      <p class="form-control-plaintext">B</p>';
                            echo '  </div>';
                            echo '</div>';
                        }
                    }
                }
            ?>
            <!-- TEST DISPLAY -->
            <div>
                <?php
                    // echo "<pre>";
                    // var_dump($test_function2);
                    // echo "<pre>";
                ?>
            </div>

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