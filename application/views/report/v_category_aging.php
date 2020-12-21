<style>
    .table-aging{
        font-size: 11px !important;
    }

    th{
        text-align: center;
    }
    .table th, .table td{
        padding: 1px !important;
        font-size: 10px !important;
    }
    ::-webkit-scrollbar {
        width: 5px;
        height: 10px;
        border-radius: 5px;
    }

    ::-webkit-scrollbar-thumb {
        background: rgba(90, 90, 90,0.1);
    }
    ::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.2);
    }
    @media print{
        @page {
            size: landscape;
            margin: 0.2in;
        }
        .table th, .table td{
            padding: 1px !important;
            font-size: 9px !important;
        }
    }
</style>
<form method="post" action="/report/category_aging/" >
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="col-form-label">Jenis harta</label>
                    <select name="type_id" class="form-control">
                        <?php
                        echo option_value('semua',' - Semua - ','type_id',search_default($data_search,'type_id'));
                        ?>
                        <?php
                        if($data_type):
                            foreach ($data_type as $row):
                                echo option_value($row['TYPE_ID'],$row['TYPE_NAME'],'type_id',search_default($data_search,'type_id'));
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label class="col-form-label">Status akaun</label>
                    <select name="acc_status" class="form-control">
                        <option value=""> - Semua - </option>
                        <?php
                        echo option_value(STATUS_ACCOUNT_ACTIVE,'Aktif','acc_status',search_default($data_search,'acc_status'));
                        echo option_value(STATUS_ACCOUNT_NONACTIVE,'Tidak aktif','acc_status',search_default($data_search,'acc_status'));
                        ?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <!-- <label class="col-form-label">Tarikh mula</label>
                    <input type="input" name="date_start" id="date_start" class="form-control date_class" placeholder="Tarikh mula" value="<?php echo set_value('date_start', search_default($data_search,'date_start')) ?>">
                    <label class="no-need-print"><a href="javascript:;" onclick="document.getElementById('date_start').value=''">Kosongkan tarikh mula</a></label> -->
                </div>
            </div>

        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right btn-submit">Cari</button>
            </div>
        </div>
    </div>
</form>
<div class="card card-accent-info">
    <div class="card-body">
        <div class="table-responsive">
   
            <div class="pull-right">
                <a class="btn btn-warning btn-sm pull-right" href="/report/print_category_aging" target="_blank">Cetak</a>
            </div>
            <br>
            <?php
                foreach ($data_report as $type_name => $report_by_type)
                {
                    $total_current_year_1_3     =   0.00;
                    $total_current_year_4_6     =   0.00;
                    $total_current_year_7_9     =   0.00;
                    $total_current_year_10_12   =   0.00;
                    $total_last_1_year          =   0.00;
                    $total_last_2_year          =   0.00;
                    $total_last_3_year          =   0.00;
                    $total_last_4_year          =   0.00;
                    $total_last_5_year          =   0.00;
                    $total_last_6_year_above    =   0.00;
                    $sum_total_outstanding      =   0.00;


                    echo '<h2 style="text-decoration: underline;margin-bottom: 15px;">'.$type_name.'</h2>';
                    echo '
                        <table class="table table-hover table-bordered " style="margin-bottom: 0px;">
                            <thead>
                                <tr>
                                    <th width="3%" rowspan="2" style="text-align:center">No.</th>
                                    <th width="11%" rowspan="2" style="text-align:center">Kategory Nama</th>
                                    <th width="6%" rowspan="2" style="text-align:center">Kod Kategori</th>
                                    <th colspan="4" width="28%" style="text-align:center">Bulan (RM)</th>
                                    <th colspan="6" width="42%" style="text-align:center">Tahun (RM)</th>
                                    <th width="10%" rowspan="2" style="text-align:center">Baki (RM)</th>
                                </tr>
                                <tr>
                                    <th width="7%">1-3</th>
                                    <th width="7%">4-6</th>
                                    <th width="7%">7-9</th>
                                    <th width="7%">10-12</th>
                                    <th width="7%">1</th>
                                    <th width="7%">2</th>
                                    <th width="7%">3</th>
                                    <th width="7%">4</th>
                                    <th width="7%">5</th>
                                    <th width="7%">>6</th>
                                </tr>
                            </thead>
                        </table>
                        <div class="table-own" style="max-height: 300px; overflow: overlay">
                            <table class="table table-hover table-bordered " >
                                <tbody>
                    ';

                    if ( count($report_by_type) > 0 )
                    {
                        $cnt = 1;

                        foreach ($report_by_type as $row) 
                        {
                            // Calculate aging based on outstanding amount
                            $total_outstanding = $row["BALANCE_AMOUNT"];
                            $monthly_charge = floatval($row["RENTAL_CHARGE"]) + floatval($row["WASTE_MANAGEMENT_CHARGE"]);
                            $current_month = date('n');

                            // Set value to allocated field
                            // Check if the rental charge is empty in table acc_account then skip
                            if ( $monthly_charge > 0 )
                            {
                                $total_month_is_unpaid = ( $total_outstanding / $monthly_charge ) - 1; // Minus 1 is used to exclude current month bill
                            }
                            else
                            {
                                $total_month_is_unpaid = 1;
                            }


                            if ( $total_outstanding > 0 )
                            {
                                if ( $current_month >= 1 && $current_month <= 3 )
                                {
                                    if ( $total_month_is_unpaid > 4 )
                                    {
                                        /* Formula
                                         * y = T - xi
                                         *
                                         * y = Value to be carried to previous year 
                                         * T = Total outstanding value excluding current month bill
                                         * x = Current rental charge including Tipping fee
                                         * i = total month in current year excluding current month
                                         *  
                                         *  
                                        */

                                        /* $new_total_outstanding == xi as mention above formula
                                         *
                                         * xi also equal to the amount that need to be fill in 
                                         * the quarterly month in current year fields
                                         * 
                                         */

                                        $new_total_outstanding  =   ( $monthly_charge * ($current_month-1) );
                                        $current_year_1_3       =   $new_total_outstanding;
                                        $current_year_4_6       =   0.00;
                                        $current_year_7_9       =   0.00;
                                        $current_year_10_12     =   0.00;

                                        // $previous_year_outstanding == T as mention above formula
                                        $previous_year_outstanding = $total_outstanding - $new_total_outstanding;

                                        // Check if amount is exceed one year rental outstanding amount then carry to next two previous year
                                        $previous_year_total_month_is_unpaid = $previous_year_outstanding/$monthly_charge;

                                        if ( $previous_year_total_month_is_unpaid > 60)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $last_3_year               =   ( $monthly_charge * 12 );
                                            $last_4_year               =   ( $monthly_charge * 12 );
                                            $last_5_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year -$last_3_year - $last_4_year - $last_5_year );
                                            $last_6_year_above         =   $remaining_unpaid_amount;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 48 && $previous_year_total_month_is_unpaid <= 60)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $last_3_year               =   ( $monthly_charge * 12 );
                                            $last_4_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year -$last_3_year - $last_4_year );
                                            $last_5_year               =   $remaining_unpaid_amount; 
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 36 && $previous_year_total_month_is_unpaid <= 48)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $last_3_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year -$last_3_year );
                                            $last_4_year               =   $remaining_unpaid_amount; 
                                            $last_5_year               =   0.00;
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 24 && $previous_year_total_month_is_unpaid <= 36)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year );
                                            $last_3_year               =   $remaining_unpaid_amount; 
                                            $last_4_year               =   0.00;
                                            $last_5_year               =   0.00;
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 12 && $previous_year_total_month_is_unpaid <= 24)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year );
                                            $last_2_year               =   $remaining_unpaid_amount; 
                                            $last_3_year               =   0.00;
                                            $last_4_year               =   0.00;
                                            $last_5_year               =   0.00;
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid <= 12 )
                                        {
                                            // Multiply with the remaining unpaid month for the previous 1 year
                                            $last_1_year        =   $previous_year_outstanding; 
                                            $last_2_year        =   0.00;
                                            $last_3_year        =   0.00;
                                            $last_4_year        =   0.00;
                                            $last_5_year        =   0.00;
                                            $last_6_year_above  =   0.00;
                                        }
                                    }
                                    else if ( $total_month_is_unpaid <= 3 )
                                    {
                                        if ( $total_outstanding > ( $monthly_charge * 9 ) )
                                        {
                                            $current_year_1_3   =   ( $monthly_charge * 3 );
                                            $current_year_4_6   =   ( $monthly_charge * 3 );
                                            $current_year_7_9   =   ( $monthly_charge * 3 );
                                            $current_year_10_12 =   ( $total_outstanding - $current_year_1_3 - $current_year_4_6 - $current_year_7_9 );
                                        }
                                        else if ( $total_outstanding > ( $monthly_charge * 6 ) )
                                        {
                                            $current_year_1_3   =   ( $monthly_charge * 3 );
                                            $current_year_4_6   =   ( $monthly_charge * 3 );
                                            $current_year_7_9   =   ( $total_outstanding - $current_year_1_3 - $current_year_4_6 );
                                            $current_year_10_12 =   0.00;
                                        }
                                        else if ( $total_outstanding > ( $monthly_charge * 3 ) )
                                        {
                                            $current_year_1_3   =   ( $monthly_charge * 3 );
                                            $current_year_4_6   =   ( $total_outstanding - $current_year_1_3 );
                                            $current_year_7_9   =   0.00;
                                            $current_year_10_12 =   0.00;
                                        }
                                        else if ( $total_outstanding <= ( $monthly_charge * 3 ) )
                                        {
                                            $current_year_1_3   =   $total_outstanding;
                                            $current_year_4_6   =   0.00;
                                            $current_year_7_9   =   0.00;
                                            $current_year_10_12 =   0.00;
                                        }
                                        
                                        $last_1_year        =   0.00;
                                        $last_2_year        =   0.00;
                                        $last_3_year        =   0.00;
                                        $last_4_year        =   0.00;
                                        $last_5_year        =   0.00;
                                        $last_6_year_above  =   0.00;
                                    }
                                }
                                else if( $current_month >= 4 && $current_month <= 6 )
                                {
                                    if ( $total_month_is_unpaid > 6 )
                                    {
                                        /* Formula
                                         * y = T - xi
                                         *
                                         * y = Value to be carried to previous year 
                                         * T = Total outstanding value excluding current month bill
                                         * x = Current rental charge including Tipping fee
                                         * i = total month in current year excluding current month
                                         *  
                                         *  
                                        */

                                        /* $new_total_outstanding == xi as mention above formula
                                         *
                                         * xi also equal to the amount that need to be fill in 
                                         * the quarterly month in current year fields
                                         * 
                                         */

                                        $new_total_outstanding  =   ( $monthly_charge * ($current_month-1) );
                                        $current_year_1_3       =   ( $monthly_charge * 3 );
                                        $current_year_4_6       =   ( $new_total_outstanding - $current_year_1_3 );
                                        $current_year_7_9       =   0.00;
                                        $current_year_10_12     =   0.00;

                                        // $previous_year_outstanding == T as mention above formula
                                        $previous_year_outstanding = $total_outstanding - $new_total_outstanding;

                                        // Check if amount is exceed one year rental outstanding amount then carry to next two previous year
                                        $previous_year_total_month_is_unpaid = $previous_year_outstanding/$monthly_charge;

                                        if ( $previous_year_total_month_is_unpaid > 60)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $last_3_year               =   ( $monthly_charge * 12 );
                                            $last_4_year               =   ( $monthly_charge * 12 );
                                            $last_5_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year -$last_3_year - $last_4_year - $last_5_year );
                                            $last_6_year_above         =   $remaining_unpaid_amount;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 48 && $previous_year_total_month_is_unpaid <= 60)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $last_3_year               =   ( $monthly_charge * 12 );
                                            $last_4_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year -$last_3_year - $last_4_year );
                                            $last_5_year               =   $remaining_unpaid_amount; 
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 36 && $previous_year_total_month_is_unpaid <= 48)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $last_3_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year -$last_3_year );
                                            $last_4_year               =   $remaining_unpaid_amount; 
                                            $last_5_year               =   0.00;
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 24 && $previous_year_total_month_is_unpaid <= 36)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year );
                                            $last_3_year               =   $remaining_unpaid_amount; 
                                            $last_4_year               =   0.00;
                                            $last_5_year               =   0.00;
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 12 && $previous_year_total_month_is_unpaid <= 24)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year );
                                            $last_2_year               =   $remaining_unpaid_amount; 
                                            $last_3_year               =   0.00;
                                            $last_4_year               =   0.00;
                                            $last_5_year               =   0.00;
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid <= 12 )
                                        {
                                            $last_1_year        =   $previous_year_outstanding; 
                                            $last_2_year        =   0.00;
                                            $last_3_year        =   0.00;
                                            $last_4_year        =   0.00;
                                            $last_5_year        =   0.00;
                                            $last_6_year_above  =   0.00;
                                        }
                                    }
                                    else if ( $total_month_is_unpaid <= 6 )
                                    {
                                        if ( $total_outstanding > ( $monthly_charge * 9 ) )
                                        {
                                            $current_year_1_3   =   ( $monthly_charge * 3 );
                                            $current_year_4_6   =   ( $monthly_charge * 3 );
                                            $current_year_7_9   =   ( $monthly_charge * 3 );
                                            $current_year_10_12 =   ( $total_outstanding - $current_year_1_3 - $current_year_4_6 - $current_year_7_9 );
                                        }
                                        else if ( $total_outstanding > ( $monthly_charge * 6 ) )
                                        {
                                            $current_year_1_3   =   ( $monthly_charge * 3 );
                                            $current_year_4_6   =   ( $monthly_charge * 3 );
                                            $current_year_7_9   =   ( $total_outstanding - $current_year_1_3 - $current_year_4_6 );
                                            $current_year_10_12 =   0.00;
                                        }
                                        else if ( $total_outstanding > ( $monthly_charge * 3 ) )
                                        {
                                            $current_year_1_3   =   ( $monthly_charge * 3 );
                                            $current_year_4_6   =   ( $total_outstanding - $current_year_1_3 );
                                            $current_year_7_9   =   0.00;
                                            $current_year_10_12 =   0.00;
                                        }
                                        else if ( $total_outstanding <= ( $monthly_charge * 3 ) )
                                        {
                                            $current_year_1_3   =   $total_outstanding;
                                            $current_year_4_6   =   0.00;
                                            $current_year_7_9   =   0.00;
                                            $current_year_10_12 =   0.00;
                                        }
                                        
                                        $last_1_year        =   0.00;
                                        $last_2_year        =   0.00;
                                        $last_3_year        =   0.00;
                                        $last_4_year        =   0.00;
                                        $last_5_year        =   0.00;
                                        $last_6_year_above  =   0.00;
                                    }
                                }
                                else if( $current_month >= 7 && $current_month <= 9 )
                                {
                                    if ( $total_month_is_unpaid > 9 )
                                    {
                                        /* Formula
                                         * y = T - xi
                                         *
                                         * y = Value to be carried to previous year 
                                         * T = Total outstanding value excluding current month bill
                                         * x = Current rental charge including Tipping fee
                                         * i = total month in current year excluding current month
                                         *  
                                         *  
                                        */

                                        /* $new_total_outstanding == xi as mention above formula
                                         *
                                         * xi also equal to the amount that need to be fill in 
                                         * the quarterly month in current year fields
                                         * 
                                         */

                                        $new_total_outstanding  =   ( $monthly_charge * ($current_month-1) );
                                        $current_year_1_3       =   ( $monthly_charge * 3 );
                                        $current_year_4_6       =   ( $monthly_charge * 3 );
                                        $current_year_7_9       =   ( $new_total_outstanding - $current_year_1_3 - $current_year_4_6 );
                                        $current_year_10_12     =   0.00;

                                        // $previous_year_outstanding == T as mention above formula
                                        $previous_year_outstanding = $total_outstanding - $new_total_outstanding;

                                        // Check if amount is exceed one year rental outstanding amount then carry to next two previous year
                                        $previous_year_total_month_is_unpaid = $previous_year_outstanding/$monthly_charge;

                                        if ( $previous_year_total_month_is_unpaid > 60)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $last_3_year               =   ( $monthly_charge * 12 );
                                            $last_4_year               =   ( $monthly_charge * 12 );
                                            $last_5_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year -$last_3_year - $last_4_year - $last_5_year );
                                            $last_6_year_above         =   $remaining_unpaid_amount;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 48 && $previous_year_total_month_is_unpaid <= 60)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $last_3_year               =   ( $monthly_charge * 12 );
                                            $last_4_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year -$last_3_year - $last_4_year );
                                            $last_5_year               =   $remaining_unpaid_amount; 
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 36 && $previous_year_total_month_is_unpaid <= 48)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $last_3_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year -$last_3_year );
                                            $last_4_year               =   $remaining_unpaid_amount; 
                                            $last_5_year               =   0.00;
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 24 && $previous_year_total_month_is_unpaid <= 36)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year );
                                            $last_3_year               =   $remaining_unpaid_amount; 
                                            $last_4_year               =   0.00;
                                            $last_5_year               =   0.00;
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 12 && $previous_year_total_month_is_unpaid <= 24)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year );
                                            $last_2_year               =   $remaining_unpaid_amount; 
                                            $last_3_year               =   0.00;
                                            $last_4_year               =   0.00;
                                            $last_5_year               =   0.00;
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid <= 12 )
                                        {
                                            $last_1_year        =   $previous_year_outstanding; 
                                            $last_2_year        =   0.00;
                                            $last_3_year        =   0.00;
                                            $last_4_year        =   0.00;
                                            $last_5_year        =   0.00;
                                            $last_6_year_above  =   0.00;
                                        }
                                    }
                                    else if ( $total_month_is_unpaid <= 9 )
                                    {
                                        if ( $total_outstanding > ( $monthly_charge * 9 ) )
                                        {
                                            $current_year_1_3   =   ( $monthly_charge * 3 );
                                            $current_year_4_6   =   ( $monthly_charge * 3 );
                                            $current_year_7_9   =   ( $monthly_charge * 3 );
                                            $current_year_10_12 =   ( $total_outstanding - $current_year_1_3 - $current_year_4_6 - $current_year_7_9 );
                                        }
                                        else if ( $total_outstanding > ( $monthly_charge * 6 ) )
                                        {
                                            $current_year_1_3   =   ( $monthly_charge * 3 );
                                            $current_year_4_6   =   ( $monthly_charge * 3 );
                                            $current_year_7_9   =   ( $total_outstanding - $current_year_1_3 - $current_year_4_6 );
                                            $current_year_10_12 =   0.00;
                                        }
                                        else if ( $total_outstanding > ( $monthly_charge * 3 ) )
                                        {
                                            $current_year_1_3   =   ( $monthly_charge * 3 );
                                            $current_year_4_6   =   ( $total_outstanding - $current_year_1_3 );
                                            $current_year_7_9   =   0.00;
                                            $current_year_10_12 =   0.00;
                                        }
                                        else if ( $total_outstanding <= ( $monthly_charge * 3 ) )
                                        {
                                            $current_year_1_3   =   $total_outstanding;
                                            $current_year_4_6   =   0.00;
                                            $current_year_7_9   =   0.00;
                                            $current_year_10_12 =   0.00;
                                        }
                                        
                                        $last_1_year        =   0.00;
                                        $last_2_year        =   0.00;
                                        $last_3_year        =   0.00;
                                        $last_4_year        =   0.00;
                                        $last_5_year        =   0.00;
                                        $last_6_year_above  =   0.00;
                                    }
                                }
                                else if( $current_month >= 10 && $current_month <= 12 )
                                {
                                    if ( $total_month_is_unpaid > 12 )
                                    {
                                        /* Formula
                                         * y = T - xi
                                         *
                                         * y = Value to be carried to previous year 
                                         * T = Total outstanding value excluding current month bill
                                         * x = Current rental charge including Tipping fee
                                         * i = total month in current year excluding current month
                                         *  
                                         *  
                                        */

                                        /* $new_total_outstanding == xi as mention above formula
                                         *
                                         * xi also equal to the amount that need to be fill in 
                                         * the quarterly month in current year fields
                                         * 
                                         */

                                        $new_total_outstanding  =   ( $monthly_charge * ($current_month-1) );
                                        $current_year_1_3       =   ( $monthly_charge * 3 );
                                        $current_year_4_6       =   ( $monthly_charge * 3 );
                                        $current_year_7_9       =   ( $monthly_charge * 3 );
                                        $current_year_10_12     =   ( $new_total_outstanding - $current_year_1_3 - $current_year_4_6 - $current_year_7_9 );

                                        // $previous_year_outstanding == T as mention above formula
                                        $previous_year_outstanding = $total_outstanding - $new_total_outstanding;

                                        // Check if amount is exceed one year rental outstanding amount then carry to next two previous year
                                        $previous_year_total_month_is_unpaid = $previous_year_outstanding/$monthly_charge;

                                        if ( $previous_year_total_month_is_unpaid > 60)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $last_3_year               =   ( $monthly_charge * 12 );
                                            $last_4_year               =   ( $monthly_charge * 12 );
                                            $last_5_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year -$last_3_year - $last_4_year - $last_5_year );
                                            $last_6_year_above         =   $remaining_unpaid_amount;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 48 && $previous_year_total_month_is_unpaid <= 60)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $last_3_year               =   ( $monthly_charge * 12 );
                                            $last_4_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year -$last_3_year - $last_4_year );
                                            $last_5_year               =   $remaining_unpaid_amount; 
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 36 && $previous_year_total_month_is_unpaid <= 48)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $last_3_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year -$last_3_year );
                                            $last_4_year               =   $remaining_unpaid_amount; 
                                            $last_5_year               =   0.00;
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 24 && $previous_year_total_month_is_unpaid <= 36)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $last_2_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year - $last_2_year );
                                            $last_3_year               =   $remaining_unpaid_amount; 
                                            $last_4_year               =   0.00;
                                            $last_5_year               =   0.00;
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid > 12 && $previous_year_total_month_is_unpaid <= 24)
                                        {
                                            $last_1_year               =   ( $monthly_charge * 12 );
                                            $remaining_unpaid_amount   =   ( $previous_year_outstanding - $last_1_year );
                                            $last_2_year               =   $remaining_unpaid_amount; 
                                            $last_3_year               =   0.00;
                                            $last_4_year               =   0.00;
                                            $last_5_year               =   0.00;
                                            $last_6_year_above         =   0.00;
                                        }
                                        else if ( $previous_year_total_month_is_unpaid <= 12 )
                                        {
                                            $last_1_year        =   $previous_year_outstanding; 
                                            $last_2_year        =   0.00;
                                            $last_3_year        =   0.00;
                                            $last_4_year        =   0.00;
                                            $last_5_year        =   0.00;
                                            $last_6_year_above  =   0.00;
                                        }
                                    }
                                    else if ( $total_month_is_unpaid <= 12 )
                                    {
                                        if ( $total_outstanding > ( $monthly_charge * 9 ) )
                                        {
                                            $current_year_1_3   =   ( $monthly_charge * 3 );
                                            $current_year_4_6   =   ( $monthly_charge * 3 );
                                            $current_year_7_9   =   ( $monthly_charge * 3 );
                                            $current_year_10_12 =   ( $total_outstanding - $current_year_1_3 - $current_year_4_6 - $current_year_7_9 );
                                        }
                                        else if ( $total_outstanding > ( $monthly_charge * 6 ) )
                                        {
                                            $current_year_1_3   =   ( $monthly_charge * 3 );
                                            $current_year_4_6   =   ( $monthly_charge * 3 );
                                            $current_year_7_9   =   ( $total_outstanding - $current_year_1_3 - $current_year_4_6 );
                                            $current_year_10_12 =   0.00;
                                        }
                                        else if ( $total_outstanding > ( $monthly_charge * 3 ) )
                                        {
                                            $current_year_1_3   =   ( $monthly_charge * 3 );
                                            $current_year_4_6   =   ( $total_outstanding - $current_year_1_3 );
                                            $current_year_7_9   =   0.00;
                                            $current_year_10_12 =   0.00;
                                        }
                                        else if ( $total_outstanding <= ( $monthly_charge * 3 ) )
                                        {
                                            $current_year_1_3   =   $total_outstanding;
                                            $current_year_4_6   =   0.00;
                                            $current_year_7_9   =   0.00;
                                            $current_year_10_12 =   0.00;
                                        }

                                        $last_1_year        =   0.00;
                                        $last_2_year        =   0.00;
                                        $last_3_year        =   0.00;
                                        $last_4_year        =   0.00;
                                        $last_5_year        =   0.00;
                                        $last_6_year_above  =   0.00;
                                    }
                                }
                            }
                            else
                            {
                                // Kalau amaoun negative set value kepada 0
                                $current_year_1_3   =   0.00;
                                $current_year_4_6   =   0.00;
                                $current_year_7_9   =   0.00;
                                $current_year_10_12 =   0.00;
                                $last_1_year        =   0.00;
                                $last_2_year        =   0.00;
                                $last_3_year        =   0.00;
                                $last_4_year        =   0.00;
                                $last_5_year        =   0.00;
                                $last_6_year_above  =   0.00;
                                $total_outstanding  =   0.00;
                            }

                            echo "<tr>";
                            echo    '<td width="3%" style="vertical-align: middle; text-align: center;">'.$cnt.'</td>';
                            echo    '<td width="11%" style="vertical-align: middle; text-align: left;" >'.$row['CATEGORY_NAME'].'</td>';
                            echo    '<td width="6%" style="vertical-align: middle; text-align: left;" >'.$row['CATEGORY_CODE'].'</td>';
                            echo    '<td width="7%" style="vertical-align: middle; text-align: right;" > '.number_format($current_year_1_3,2,'.',',').' </td>';
                            echo    '<td width="7%" style="vertical-align: middle; text-align: right;" > '.number_format($current_year_4_6,2,'.',',').' </td>';
                            echo    '<td width="7%" style="vertical-align: middle; text-align: right;" > '.number_format($current_year_7_9,2,'.',',').' </td>';
                            echo    '<td width="7%" style="vertical-align: middle; text-align: right;" > '.number_format($current_year_10_12,2,'.',',').' </td>';
                            echo    '<td width="7%" style="vertical-align: middle; text-align: right;" > '.number_format($last_1_year,2,'.',',').' </td>';
                            echo    '<td width="7%" style="vertical-align: middle; text-align: right;" > '.number_format($last_2_year,2,'.',',').' </td>';
                            echo    '<td width="7%" style="vertical-align: middle; text-align: right;" > '.number_format($last_3_year,2,'.',',').' </td>';
                            echo    '<td width="7%" style="vertical-align: middle; text-align: right;" > '.number_format($last_4_year,2,'.',',').' </td>';
                            echo    '<td width="7%" style="vertical-align: middle; text-align: right;" > '.number_format($last_5_year,2,'.',',').' </td>';
                            echo    '<td width="7%" style="vertical-align: middle; text-align: right;" > '.number_format($last_6_year_above,2,'.',',').' </td>';
                            echo    '<td width="10%" style="vertical-align: middle; text-align: right;" > '.number_format($total_outstanding,2,'.',',').' </td>';
                            echo "</tr>";

                            $total_current_year_1_3     =   $total_current_year_1_3 + $current_year_1_3 ;
                            $total_current_year_4_6     =   $total_current_year_4_6 + $current_year_4_6 ;
                            $total_current_year_7_9     =   $total_current_year_7_9 + $current_year_7_9 ;
                            $total_current_year_10_12   =   $total_current_year_10_12 + $current_year_10_12 ;
                            $total_last_1_year          =   $total_last_1_year + $last_1_year ;
                            $total_last_2_year          =   $total_last_2_year + $last_2_year ;
                            $total_last_3_year          =   $total_last_3_year + $last_3_year ;
                            $total_last_4_year          =   $total_last_4_year + $last_4_year ;
                            $total_last_5_year          =   $total_last_5_year + $last_5_year ;
                            $total_last_6_year_above    =   $total_last_6_year_above + $last_6_year_above ;
                            $sum_total_outstanding      =   $sum_total_outstanding + $total_outstanding ;
                            $cnt = $cnt + 1;
                        }
                    }
                    else
                    {
                        echo '      <tr><td colspan="14" class="text-center"> - Tiada data - </td></tr>';
                    }

                    echo '  
                                    <tr>
                                        <td colspan="3" align="right"><strong>JUMLAH (RM)</strong></td>
                                        <td width="7%" style="vertical-align: middle; text-align: right;"> '.number_format($total_current_year_1_3,2,'.',',').' </td>
                                        <td width="7%" style="vertical-align: middle; text-align: right;"> '.number_format($total_current_year_4_6,2,'.',',').' </td>
                                        <td width="7%" style="vertical-align: middle; text-align: right;"> '.number_format($total_current_year_7_9,2,'.',',').' </td>
                                        <td width="7%" style="vertical-align: middle; text-align: right;"> '.number_format($total_current_year_10_12,2,'.',',').' </td>
                                        <td width="7%" style="vertical-align: middle; text-align: right;"> '.number_format($total_last_1_year,2,'.',',').' </td>
                                        <td width="7%" style="vertical-align: middle; text-align: right;"> '.number_format($total_last_2_year,2,'.',',').' </td>
                                        <td width="7%" style="vertical-align: middle; text-align: right;"> '.number_format($total_last_3_year,2,'.',',').' </td>
                                        <td width="7%" style="vertical-align: middle; text-align: right;"> '.number_format($total_last_4_year,2,'.',',').' </td>
                                        <td width="7%" style="vertical-align: middle; text-align: right;"> '.number_format($total_last_5_year,2,'.',',').' </td>
                                        <td width="7%" style="vertical-align: middle; text-align: right;"> '.number_format($total_last_6_year_above,2,'.',',').' </td>
                                        <td width="10%" style="vertical-align: middle; text-align: right;"> '.number_format($sum_total_outstanding,2,'.',',').' </td>
                                    </tr>
                                </tbody>
                            </table>
                        ';

                    echo "</div><br>";
                }
            ?>
        </div>
    </div>
</div>