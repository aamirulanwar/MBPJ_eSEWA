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
                        <select name="year" class="form-control">
                            <option value=""> - Pilih Tahun - </option>
                            <?php
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
                        <select name="year2" class="form-control">
                            <option value=""> - Pilih Tahun - </option>
                            <?php
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
                <table class="table table-hover table-bordered table-aging" style="margin-bottom: 0px;">
                    <tr>
                        <th width="3%" rowspan="2" style="text-align:center">BIL</th>
                        <th width="12%" rowspan="2" style="text-align:center">JENIS PERJANJIAN</th>
                        <?php foreach ($data_report as $d):
                            $year = $d['YEAR'] ;
                            echo "<th colspan='3' width='7%'' style='text-align:center'>".$year."</th>";
                            ?>
                            <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th width="3%">BIL</th>
                        <th width="3%">RM</th>
                        <th width="3%">%</th>
                        <th width="3%">BIL</th>
                        <th width="3%">RM</th>
                        <th width="3%">%</th>
                        <th width="3%">BIL</th>
                        <th width="3%">RM</th>
                        <th width="3%">%</th>   
                    </tr>
                    <tr>
                        <th>1.</th>
                        <th>SEWA</th>
                        <?php
                                $bil = 0 ;
                                $rm  = 0 ;
                                $year = $d['YEAR'] ;
                             foreach ($data_report as $d):
                                {
                                        if( $d["TYPE_ID"] != 6 && $d['YEAR'] == $year)
                                        {
                                            $bil += $d["BIL"]; 
                                            $rm += $d["RM"];
                                        }elseif ($d["TYPE_ID"] != 6 && $d['YEAR'] == $year)
                                        {
                                            $bil += $d["BIL"]; 
                                            $rm += $d["RM"];
                                        }
                                    }
                                    ?>
                            <?php endforeach; 
                                    // $total = array_sum($bil);
                                    echo "<td style='text-align:center'>".$bil."</td>";
                                    echo "<td style='text-align:center'>".num($rm,2)."</td>";
                                    echo "<td style='text-align:center'>".$year."</td>";
                            ?>
                    </tr>
                </table>
            </div>
        </div>
