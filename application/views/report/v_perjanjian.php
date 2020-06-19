<style>
    th{
        text-align: center;
    }
    td{
        text-align: center;
    }
</style>
 <form method="post" action="/report/perjanjian_kutipan/">
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label class="col-form-label">TAHUN MULA</label>
                        <select id="year" name="year" class="form-control">
                            <option value=""> - Pilih Tahun - </option>
                            <?php
                            echo option_value(2020,2020,'year',search_default($data_search,'year'));
                            echo option_value(2019,2019,'year',search_default($data_search,'year'));
                            echo option_value(2018,2018,'year',search_default($data_search,'year'));
                            echo option_value(2017,2017,'year',search_default($data_search,'year'));
                            echo option_value(2016,2016,'year',search_default($data_search,'year'));
                            echo option_value(2015,2015,'year',search_default($data_search,'year'));
                            echo option_value(2014,2014,'year',search_default($data_search,'year'));
                            echo option_value(2013,2013,'year',search_default($data_search,'year'));
                            echo option_value(2012,2012,'year',search_default($data_search,'year'));
                            echo option_value(2011,2011,'year',search_default($data_search,'year'));
                            echo option_value(2010,2010,'year',search_default($data_search,'year'));
                            echo option_value(2009,2009,'year',search_default($data_search,'year'));
                            echo option_value(2008,2008,'year',search_default($data_search,'year'));
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label class="col-form-label">TAHUN TAMAT</label>
                        <select id="year2" name="year2" class="form-control">
                            <option value=""> - Pilih Tahun - </option>
                            <?php
                            echo option_value(2020,2020,'year2',search_default($data_search,'year2'));
                            echo option_value(2019,2019,'year2',search_default($data_search,'year2'));
                            echo option_value(2018,2018,'year2',search_default($data_search,'year2'));
                            echo option_value(2017,2017,'year2',search_default($data_search,'year2'));
                            echo option_value(2016,2016,'year2',search_default($data_search,'year2'));
                            echo option_value(2015,2015,'year2',search_default($data_search,'year2'));
                            echo option_value(2014,2014,'year2',search_default($data_search,'year2'));
                            echo option_value(2013,2013,'year2',search_default($data_search,'year2'));
                            echo option_value(2012,2012,'year2',search_default($data_search,'year2'));
                            echo option_value(2011,2011,'year2',search_default($data_search,'year2'));
                            echo option_value(2010,2010,'year2',search_default($data_search,'year2'));
                            echo option_value(2009,2009,'year2',search_default($data_search,'year2'));
                            echo option_value(2008,2008,'year2',search_default($data_search,'year2'));
                            ?>
                        </select>
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

<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>

    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Perbandingan Jumlah Perjanjian & Kutipan</h3>
        </div>
            <div class="card-body">
                <div class="pull-right">
                    <button onclick="print_report_perjanjian_kutipan()" class="btn btn-warning btn-sm pull-right">Print</button>
                </div>
                <table class="table table-hover table-bordered table-aging" style="margin-bottom: 0px;">
                    <tr>
                        <th width="3%" rowspan="2" style="text-align:center">BIL</th>
                        <th width="12%" rowspan="2" style="text-align:center">JENIS PERJANJIAN</th>
                        <?php if (count($data_report) > 0): ?>
                        <?php foreach ($data_report[1] as $d):
                            $year = $d['YEAR'] ;
                            echo "<th colspan='3' width='7%'' style='text-align:center'>".$year."</th>";
                        ?>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <?php
                            foreach ($data_report[1] as $d) 
                            {
                                echo "
                                    <th width='3%'>BIL</th>
                                    <th width='3%'>RM</th>
                                    <th width='3%'>%</th>
                                    ";
                            }
                        ?>
                    </tr>
                    <tr>
                        <th>1.</th>
                        <th>SEWA</th>
                        <?php
                            $bil_sewa = 0 ;
                            $bil_billboard = 0 ;
                            $total_sewa = 0;
                            $total_billboard = 0;
                            $total_bil = 0;

                            foreach ($data_report[1] as $d)
                            {
                                $bil_sewa = $d["BIL"];                                  
                            }

                            foreach ($data_report[2] as $d)
                            {
                                $bil_billboard = $d["BIL"];                                   
                            }

                            $total_sewa = $bil_sewa * 100;                            
                            $total_billboard = $bil_billboard * 100;
                            $total_bil = $bil_sewa + $bil_billboard;
                            $total = $total_sewa + $total_billboard;
                            $peratus = $total / 150000 * 100;
                        ?>
                        <td style='text-align:center'> <?=$bil_sewa?> </td>
                        <td style='text-align:center'> <?=num($total_sewa,2)?> </td>
                        <td style='text-align:center'> </td>                     
                    </tr>
                    <tr>
                        <th>2.</th>
                        <th>BILLBOARD</th>
                        <td style='text-align:center'> <?=$bil_billboard?> </td>
                        <td style='text-align:center'> <?=num($total_billboard,2)?> </td>
                        <td style='text-align:center'> </td>
                    </tr>
                    <tr>
                        <th colspan="2">JUMLAH KESELURUHAN</th> 
                        <td style='text-align:center'> <?=$total_bil?> </td>
                        <td style='text-align:center'> <?=num($total,2)?> </td>
                        <td style='text-align:center'> <?=round($peratus,2)?> </td>    
                    </tr>
                    <tr>
                        <th colspan="2">SASARAN TAHUNAN</th> 
                        <td style='text-align:center'>960</td>
                        <td style='text-align:center'>150000.00</td>
                        <td style='text-align:center'></td>    
                    </tr>
                <?php endif; ?> 
                </table>
                <script type="text/javascript">
                    function print_report_perjanjian_kutipan()
                        {
                            var selectedYear = $("#selectedYear").val();
                            window.open('/report/print_perjanjian_kutipan','_blank');
                        }
                </script>
            </div>
        </div>
