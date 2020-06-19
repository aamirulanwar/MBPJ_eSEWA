<style>
    th{
        text-align: center;
    }
    td{
        text-align: center;
    }
</style>
 <form method="post" action="/report/laporan_iso/">
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label class="col-form-label">TAHUN MULA</label>
                        <select name="year" class="form-control">
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
            <h3 class="box-title">Pencapaian Objektif Kualiti Prosedur Penyediaan Perjanjian</h3>
        </div>
            <div class="card-body">
                <div class="pull-right">
                    <button onclick="print_report_iso()" class="btn btn-warning btn-sm pull-right">Print</button>
                </div>
                <table class="table table-hover table-bordered table-aging" style="margin-bottom: 0px;">
                    <tr>
                        <th width="12%" rowspan="2" style="text-align:center">OBJEKTIF KUALITI</th>
                        <th width="12%" rowspan="2" style="text-align:center">JENIS PERJANJIAN</th>
                        <th width="12%" rowspan="2" style="text-align:center">TEMPOH PENCAPAIAN OBJEKTIF KUALITI</th>
                        <th width="12%" rowspan="2" style="text-align:center">JUMLAH PERJANJIAN YANG DITERIMA</th>
                        <th width="12%" colspan="2" style="text-align:center">STATUS</th>
                        <th width="12%" rowspan="2" style="text-align:center">PENCAPAIAN (%)</th>
                    </tr>
                    <tr>
                        <th>BILANGAN</th>
                        <th>KETERANGAN</th>
                          
                    </tr>
                    <tr>
                        <th>Maklumat tandatangan perjanjian (45 hari)</th>
                        <th>BILLBOARD</th>
                        <th>45 Hari</th>
                        <?php
                            $type_id = array(1,2,3,4,5,9);
                            $sewa = 0;
                            $billboard = 0;
                            foreach ($data_report as $data)
                            {
                                if( in_array($data["TYPE_ID"],$type_id))
                                {
                                    $sewa += $data["BIL"];
                                }
                                else if( $data["TYPE_ID"] == 6)
                                {
                                    $billboard = $data["BIL"];
                                }
                            }
                        ?>                                 
                        <th><?=$billboard?></th>
                        <td></td>   
                        <td></td>             
                        <td></td>             
                    </tr>
                    
                    <tr>
                        <th>Tandatangan Perjanjian Sewa oleh Penyewa dalam tempoh 15 minit</th>
                        <th>SEWA</th>
                        <th>15 Minit</th>
                        <th><?=$sewa?></th>
                        <td></td>   
                        <td></td>   
                        <td></td>   
                    </tr>
                </table>
            </div>
        </div>
        <script type="text/javascript">
            function print_report_iso()
            {
                var selectedYear = $("#selectedYear").val();
                window.open('/report/print_iso','_blank');
            }
        </script>
