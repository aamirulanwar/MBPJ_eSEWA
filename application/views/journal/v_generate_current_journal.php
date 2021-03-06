
<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>
<form id="journalForm" action="/journal/generate_current_journal/<?php echo uri_segment(3)?>/<?php echo uri_segment(4)?>" method="post" class="form-horizontal" >
    <input type="hidden" name="account_id" value="<?=$account['ACCOUNT_ID']?>">
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
            <div class="row mb-0    ">
                <div class="col-sm-12">
                    <?php
                        if($bill_master['BILL_CATEGORY']=='B'):
                            $title  = 'Maklumat bil';
                            $no     = 'No. bil';
                            $date   = 'Tarikh bil';
                        elseif ($bill_master['BILL_CATEGORY']=='R'):
                            $title  = 'Maklumat resit';
                            $no     = 'No. resit';
                            $date   = 'Tarikh resit';
                        elseif ($bill_master['BILL_CATEGORY']=='J'):
                            $title  = 'Maklumat jurnal';
                            $no     = 'No. jurnal';
                            $date   = 'Tarikh jurnal';
                        endif;
                    ?>
                    <h3 class="header-h1"><?php echo $title?> (<?php echo $bill_master['BILL_MONTH'].'/'.$bill_master['BILL_YEAR']?>)</h3>
                    <input type="hidden" id="journal_month" name="journal_month" value="<?=$bill_master['BILL_MONTH']?>" />
                    <input type="hidden" id="journal_year" name="journal_year" value="<?=$bill_master['BILL_YEAR']?>" />
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><?php echo $no?> </label>
                        <div class="col-sm-2">
                            <?php
                                if ( $bill_master['BILL_NUMBER'] == "NEW_RESIT_JOURNAL_R02")
                                {
                                    echo '<p class="form-control-plaintext"><input class="form-control" style="width:310px" type="text" id="bill_number" name="bill_number" value="" placeholder="Sila masukkan no resit bagi transaksi bulan ini"</p>';
                                }
                                else
                                {
                                    echo '<p class="form-control-plaintext">'.$bill_master['BILL_NUMBER'].'</p>';
                                }
                            ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><?php echo $date?> </label>
                        <div class="col-sm-2">
                            <p class="form-control-plaintext"><?php echo (date_display($bill_master['DT_ADDED']))?></p>
                            <input type="hidden" id="DT_ADDED" name="DT_ADDED" value="<?=$bill_master['CUSTOM_DT_ADDED']?>" />
                        </div>
                        <input type="hidden" id="b_master_bill_category" name="b_master_bill_category" value="<?=$bill_master['BILL_CATEGORY']?>">
                    </div>
                </div>
            </div>
            <hr>

            <?php
                if($bill_item):
                    foreach ($bill_item as $item):
                        ?>
                        <input type="hidden" name="item_id[<?=$item['ITEM_ID']?>]" value="<?=$item['AMOUNT']?>">
                        <div class="form-group row">
                            <label class="col-sm-6 col-form-label"><?php echo '<strong>'.$item['TR_CODE'] .'</strong> - '. $item['ITEM_DESC']?> </label>
                            <div class="col-sm-2">
                                <p class="form-control-plaintext">RM<?php echo num($item['AMOUNT'])?></p>
                            </div>
                            <div class="col-sm-1">
                                <p class="form-control-plaintext"> <?php echo ($item['BILL_CATEGORY'])?></p>
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
                    <!-- <button type="button" onclick="add_transaction_code('B')" class="btn btn-primary pull-left btn-submit">Tambah kod transaksi bill</button> -->
                    <button style="margin-left: 20px;" type="button" onclick="chooseJournalCode()" class="btn btn-warning pull-left btn-submit">Tambah kod transaksi jurnal</button>
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

<div class="modal fade" id="add_transaction_code_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pilih Kod Jurnal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <center>
                    <div class="form-group">
                        <input type="hidden" id="default_bill_category" value="<?=( $bill_master['BILL_NUMBER'] == "NEW_RESIT_JOURNAL_R02") ? 'R' : $bill_master['BILL_CATEGORY']?>">
                        <label>Sila pilih kod transaksi</label>
                        <select name="choose_transaction_code" id="choose_transaction_code" class="form-control">
                            <?php
                                if($bill_item):
                                    foreach ($bill_item as $item):
                                        ?>
                                        <option value="<?=$item['TR_CODE']?>" data-bill-amount="<?=$item['AMOUNT']?>" data-bill-category="<?=$item['BILL_CATEGORY']?>"><?php echo '<strong>'.$item['TR_CODE'] .'</strong> - '. $item['ITEM_DESC']?></option>
                                        <?php
                                    endforeach;
                                endif;
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Sila pilih kod jurnal</label>
                        <select name="choose_journal_code" id="choose_journal_code" class="form-control"></select>
                    </div>
                </center>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="confirmChooseJournalCode('#choose_journal_code','#choose_transaction_code')">Teruskan</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">

    $('#journalForm').submit(function(event) 
    {
        var proceedStatus = [];        
        var i = 0;
        $.each( $("input[name='mct_trcodenew[]']"), function (key,value)
        {
            var selectedTrCode = $("#"+value.id).val();
            console.log(value.id);
            if (selectedTrCode == "" || selectedTrCode == -1 || selectedTrCode == " ")
            {
                proceedStatus[i] = false;
                $("#"+value.id+"_error").html("**Sila pilih kod transaksi");
            }
            else if (selectedTrCode != "" || selectedTrCode != -1 || selectedTrCode != " ")
            {
                proceedStatus[i] = true;
                $("#"+value.id+"_error").html("");
            }

            i = i + 1;
        });

        console.log(proceedStatus);
        var check = true;
        $.each(proceedStatus, function(index, val) 
        {
            /* iterate through array or object */
            console.log(val);
            if (val == false)
            {
                check = false;
            }
        });

        if (check == true)
        {
            $(this).unbind('submit').submit();
        }
        else if (check == false)
        {
            event.preventDefault();
        }
    });
</script>