<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>

<form action="/journal/search" id="submit_image" method="post" class="form-horizontal">
    <div class="card card-accent-info">
        <div class="card-header">
            <div class="pull-left">
                <strong>Carian</strong>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-4">
                    <label class="col-form-label">No. akaun</label>
                    <input type="text" class="form-control" value="<?php echo set_value('account_number')?>" id="account_number" name="account_number" placeholder="No. akaun">
                </div>
                <div class="form-group col-4">
                    <label class="col-form-label">No. bill/ No. resit</label>
                    <input type="text" class="form-control"  value="<?php echo set_value('bill_number')?>" name="bill_number" id="bill_number" placeholder="No. bill/ No. resit">
                </div>
                <div class="form-group col-4">
                    <label class="col-form-label">Bil / resit</label>
                    <select class="form-control" name="bill_category">
                        <?php echo option_value('','- Semua -','bill_category')?>
                        <?php echo option_value('B','Bil','bill_category')?>
                        <?php echo option_value('R','Resit','bill_category')?>
                    </select>
                </div>
                <div class="form-group col-4">
                    <label class="col-form-label">Tarikh mula</label>
                    <input type="text" class="form-control date_class" value="<?php echo set_value('date_start')?>" name="date_start" id="date_start" placeholder="Tarikh mula">
                </div>
                <div class="form-group col-4">
                    <label class="col-form-label">Tarikh akhir</label>
                    <input type="text" class="form-control date_class" value="<?php echo set_value('date_end')?>" name="date_end" id="date_end" placeholder="Tarikh akhir">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary pull-right btn-submit">Cari</button>
        </div>
    </div>
</form>

<div class="card card-accent-info">
    <div class="card-header">
        <div class="pull-left">
            <strong>Jumlah Rekod : <?php echo $total_result?></strong>
        </div>
        <div class="pull-right">
            <button class="btn btn-success" id="add-new-receipt">Tambah Resit</button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="senarai-pelarasan">
                <tr>
                    <th>No.</th>
                    <th>Tarikh</th>
                    <th>No. akaun</th>
                    <!-- <th>No. bill / resit</th> -->
                    <th>Bulan / Tahun</th>
                    <th>Jenis</th>
                    <th class="text-center">Tindakan</th>
                </tr>
                <tbody>
                <?php
                if($data_list):
                    $i = (uri_segment(3)=='')?0:uri_segment(3);
                    foreach($data_list as $row):
                        $i=$i+1;
                        ?>
                        <tr>
                            <td><?php echo $i?></td>
                            <td><?php echo date_display($row['DT_ADDED'])?></td>
                            <td>
                                <strong><?php echo $row['ACCOUNT_NUMBER']?></strong>
                            </td>
                            <td>
                                <?php
                                echo $row['BILL_MONTH'].'/'.$row['BILL_YEAR']
                                ?>
                            </td>
                            <td>
                                <?php
                                    if($row['BILL_CATEGORY']=='B'):
                                        echo 'Bil';
                                    elseif($row['BILL_CATEGORY']=='R'):
                                        echo 'Resit';
                                    elseif($row['BILL_CATEGORY']=='J'):
                                        echo 'Jurnal';
                                    endif;
                                ?>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-block btn-primary btn-display" href="/journal/generate_current_journal/<?php echo urlEncrypt($row['BILL_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Pelarasan </a>
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
        <div class="pull-right">
            <?php echo paging_link(); ?>
        </div>
    </div>
</div>

<!-- Add datepicker library for journal only -->
<link href="/assets/date_picker/css/gijgo.min.css" rel="stylesheet">
<script src="/assets/date_picker/js/gijgo.min.js"></script>

<!-- Add momentjs library -->
<script src="/assets/moment.min.js"></script>

<script type="text/javascript">
    
    $(document).ready(function($) 
    {
        $('#add-new-receipt').click(function(event) 
        {
            console.log("clicked");
            var account_no_search = $("#account_number").val();

            var html =  "<tr>" + 
                        "   <form method='POST' action='/journal/customAdd'>" + 
                        "       <td>0</td>" + 
                        "       <td><input type='text' class='form-control datepicker' id='tarikh' name='tarikh' required></td>" +
                        "       <td><input type='text' class='form-control' id='no_akaun' name='no_akaun' required></td>" +
                        "       <td><input type='text' class='form-control' id='bulantahun' name='bulantahun' required></td>" +
                        "       <td>Resit</td>" +
                        "       <td class='text-center'>" +
                        // "           <a class='btn btn-block btn-primary btn-display' href='/journal/generate_current_journal/LTk5OQ%3D%3D' >" +
                        "           <a class='btn btn-block btn-primary btn-display' href='javascript:;' onclick='addNewReceipt()' >" +
                        "               <span class='glyphicon glyphicon-edit'></span> Pelarasan" +
                        "           </a>" +
                        "       </td>" +
                        "   </form>" +
                        "</tr>" ;

            $("#senarai-pelarasan tr:first").after(html);
            $("#no_akaun").val(account_no_search);
            $('.datepicker').datepicker({ dateFormat: 'dd M yy' });
        });        

    });

    $(document).on('change', '#tarikh', function() 
    {
        // Does some stuff and logs the event to the console
        // Check input( $( this ).val() ) for validity here
        moment.locale('en');
        var input_tarikh = $( this ).val();
        var bulantahun = moment(input_tarikh, "DD MMM YYYY");
        $("#bulantahun").val( bulantahun.format("M/YYYY") );

        // console.log( input_tarikh );
        // console.log(moment().format("DD MMM YYYY"));
        // console.log("bulantahun => ");
        // console.log(bulantahun.format("M/YYYY"));
    });

    function addNewReceipt() 
    {
        // body...
        var account_no = $("#no_akaun").val();
        var tarikh_resit = $("#tarikh").val();
        var bulantahun_resit = $("#bulantahun").val();

        var formData = account_no + ":" + tarikh_resit + ":" + bulantahun_resit;

        $.ajax({
            url: '/journal/encrypt/',
            type: 'POST',
            data: {"formData" : formData},
            success: function(data)
            {
                console.log(data);
                window.location.href = "/journal/generate_current_journal/LTk5OQ%3D%3D/" + data;
            }
        });
        
    }
</script>