<style>
    th{
        text-align: center;
    }
</style>
 <form method="post" action="/report/hartanah/">
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label class="col-form-label">TAHUN</label>
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
            <h3 class="box-title">Laporan Perjanian Sewaan Hartanah</h3>
        </div>
            <div class="card-body">
                <!-- <div class="pull-right"> -->
                        <table class="table table-hover table-bordered table-aging" style="margin-bottom: 0px;">
                            <tr>
                                <th width="3%" rowspan="2" style="text-align:center">BULAN</th>
                                <th colspan="5" width="7%" style="text-align:center">JENIS HARTANAH</th>
                                <th width="7%" rowspan="2" style="text-align:center">JUMLAH</th>
                            </tr>
                            <tr>
                                <th width="7%">PLB</th>
                                <th width="7%">PASAR</th>
                                <th width="7%">MEDAN SELERA</th>
                                <th width="7%">UPEN</th>
                                <th width="7%">KIOSK MIKRO-MARA</th>
                            </tr>
                            <tr>
                                <th>January</th>
                                <?php
                                    $januaryCount = 0;
                                    $plb = 0;
                                    $pasar = 0;
                                    $medan_selera = 0;
                                    $upen = 0;
                                    $kiosk = 0;
                                    foreach ($data_report as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BULAN"] == 1)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BULAN"] == 1)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BULAN"] == 1)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BULAN"] == 1)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BULAN"] == 1)
                                        {
                                            $kiosk = $data["BIL"];
                                        }
                                    }
                                    $januaryCount = $plb + $pasar + $medan_selera + $upen + $kiosk;
                                    echo "<td style='text-align:center'>".$plb."</td>";
                                    echo "<td style='text-align:center'>".$pasar."</td>";
                                    echo "<td style='text-align:center'>".$medan_selera."</td>";
                                    echo "<td style='text-align:center'>".$upen."</td>";
                                    echo "<td style='text-align:center'>".$kiosk."</td>";
                                    echo "<td style='text-align:center'>".$januaryCount."</td>";
                                ?>                                
                            </tr>
                            <tr>
                                <th>February</th>
                                <?php
                                    $februaryCount = 0;
                                    $plb = 0;
                                    $pasar = 0;
                                    $medan_selera = 0;
                                    $upen = 0;
                                    $kiosk = 0;
                                    foreach ($data_report as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BULAN"] == 2)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BULAN"] == 2)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BULAN"] == 2)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BULAN"] == 2)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BULAN"] == 2)
                                        {
                                            $kiosk = $data["BIL"];
                                        }
                                    }
                                    $februaryCount = $plb + $pasar + $medan_selera + $upen + $kiosk;
                                    echo "<td style='text-align:center'>".$plb."</td>";
                                    echo "<td style='text-align:center'>".$pasar."</td>";
                                    echo "<td style='text-align:center'>".$medan_selera."</td>";
                                    echo "<td style='text-align:center'>".$upen."</td>";
                                    echo "<td style='text-align:center'>".$kiosk."</td>";
                                    echo "<td style='text-align:center'>".$februaryCount."</td>";
                                ?>                                
                            </tr>
                            <tr>
                                <th>March</th>
                                <?php
                                    $marchCount = 0;
                                    $plb = 0;
                                    $pasar = 0;
                                    $medan_selera = 0;
                                    $upen = 0;
                                    $kiosk = 0;
                                    foreach ($data_report as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BULAN"] == 3)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BULAN"] == 3)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BULAN"] == 3)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BULAN"] == 3)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BULAN"] == 3)
                                        {
                                            $kiosk = $data["BIL"];
                                        }
                                    }
                                    $marchCount = $plb + $pasar + $medan_selera + $upen + $kiosk;
                                    echo "<td style='text-align:center'>".$plb."</td>";
                                    echo "<td style='text-align:center'>".$pasar."</td>";
                                    echo "<td style='text-align:center'>".$medan_selera."</td>";
                                    echo "<td style='text-align:center'>".$upen."</td>";
                                    echo "<td style='text-align:center'>".$kiosk."</td>";
                                    echo "<td style='text-align:center'>".$marchCount."</td>";
                                ?>                                
                            </tr>
                            <tr>
                                <th>April</th>
                                <?php
                                    $aprilCount = 0;
                                    $plb = 0;
                                    $pasar = 0;
                                    $medan_selera = 0;
                                    $upen = 0;
                                    $kiosk = 0;
                                    foreach ($data_report as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BULAN"] == 4)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BULAN"] == 4)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BULAN"] == 4)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BULAN"] == 4)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BULAN"] == 4)
                                        {
                                            $kiosk = $data["BIL"];
                                        }
                                    }
                                    $aprilCount = $plb + $pasar + $medan_selera + $upen + $kiosk;
                                    echo "<td style='text-align:center'>".$plb."</td>";
                                    echo "<td style='text-align:center'>".$pasar."</td>";
                                    echo "<td style='text-align:center'>".$medan_selera."</td>";
                                    echo "<td style='text-align:center'>".$upen."</td>";
                                    echo "<td style='text-align:center'>".$kiosk."</td>";
                                    echo "<td style='text-align:center'>".$aprilCount."</td>";
                                ?>                                
                            </tr>
                            <tr>
                                <th>May</th>
                                <?php
                                    $mayCount = 0;
                                    $plb = 0;
                                    $pasar = 0;
                                    $medan_selera = 0;
                                    $upen = 0;
                                    $kiosk = 0;
                                    foreach ($data_report as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BULAN"] == 5)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BULAN"] == 5)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BULAN"] == 5)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BULAN"] == 5)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BULAN"] == 5)
                                        {
                                            $kiosk = $data["BIL"];
                                        }
                                    }
                                    $mayCount = $plb + $pasar + $medan_selera + $upen + $kiosk;
                                    echo "<td style='text-align:center'>".$plb."</td>";
                                    echo "<td style='text-align:center'>".$pasar."</td>";
                                    echo "<td style='text-align:center'>".$medan_selera."</td>";
                                    echo "<td style='text-align:center'>".$upen."</td>";
                                    echo "<td style='text-align:center'>".$kiosk."</td>";
                                    echo "<td style='text-align:center'>".$mayCount."</td>";
                                ?>                                
                            </tr>
                            <tr>
                                <th>Jun</th>
                                <?php
                                    $junCount = 0;
                                    $plb = 0;
                                    $pasar = 0;
                                    $medan_selera = 0;
                                    $upen = 0;
                                    $kiosk = 0;
                                    foreach ($data_report as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BULAN"] == 6)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BULAN"] == 6)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BULAN"] == 6)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BULAN"] == 6)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BULAN"] == 6)
                                        {
                                            $kiosk = $data["BIL"];
                                        }
                                    }
                                    $junCount = $plb + $pasar + $medan_selera + $upen + $kiosk;
                                    echo "<td style='text-align:center'>".$plb."</td>";
                                    echo "<td style='text-align:center'>".$pasar."</td>";
                                    echo "<td style='text-align:center'>".$medan_selera."</td>";
                                    echo "<td style='text-align:center'>".$upen."</td>";
                                    echo "<td style='text-align:center'>".$kiosk."</td>";
                                    echo "<td style='text-align:center'>".$junCount."</td>";
                                ?>                                
                            </tr>
                            <tr>
                                <th>July</th>
                                <?php
                                    $julyCount = 0;
                                    $plb = 0;
                                    $pasar = 0;
                                    $medan_selera = 0;
                                    $upen = 0;
                                    $kiosk = 0;
                                    foreach ($data_report as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BULAN"] == 7)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BULAN"] == 7)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BULAN"] == 7)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BULAN"] == 7)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BULAN"] == 7)
                                        {
                                            $kiosk = $data["BIL"];
                                        }
                                    }
                                    $julyCount = $plb + $pasar + $medan_selera + $upen + $kiosk;
                                    echo "<td style='text-align:center'>".$plb."</td>";
                                    echo "<td style='text-align:center'>".$pasar."</td>";
                                    echo "<td style='text-align:center'>".$medan_selera."</td>";
                                    echo "<td style='text-align:center'>".$upen."</td>";
                                    echo "<td style='text-align:center'>".$kiosk."</td>";
                                    echo "<td style='text-align:center'>".$julyCount."</td>";
                                ?>                                
                            </tr>
                            <tr>
                                <th>August</th>
                                <?php
                                    $augustCount = 0;
                                    $plb = 0;
                                    $pasar = 0;
                                    $medan_selera = 0;
                                    $upen = 0;
                                    $kiosk = 0;
                                    foreach ($data_report as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BULAN"] == 8)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BULAN"] == 8)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BULAN"] == 8)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BULAN"] == 8)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BULAN"] == 8)
                                        {
                                            $kiosk = $data["BIL"];
                                        }
                                    }
                                    $augustCount = $plb + $pasar + $medan_selera + $upen + $kiosk;
                                    echo "<td style='text-align:center'>".$plb."</td>";
                                    echo "<td style='text-align:center'>".$pasar."</td>";
                                    echo "<td style='text-align:center'>".$medan_selera."</td>";
                                    echo "<td style='text-align:center'>".$upen."</td>";
                                    echo "<td style='text-align:center'>".$kiosk."</td>";
                                    echo "<td style='text-align:center'>".$augustCount."</td>";
                                ?>                                
                            </tr>
                            <tr>
                                <th>September</th>
                                <?php
                                    $septemberCount = 0;
                                    $plb = 0;
                                    $pasar = 0;
                                    $medan_selera = 0;
                                    $upen = 0;
                                    $kiosk = 0;
                                    foreach ($data_report as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BULAN"] == 9)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BULAN"] == 9)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BULAN"] == 9)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BULAN"] == 9)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BULAN"] == 9)
                                        {
                                            $kiosk = $data["BIL"];
                                        }
                                    }
                                    $septemberCount = $plb + $pasar + $medan_selera + $upen + $kiosk;
                                    echo "<td style='text-align:center'>".$plb."</td>";
                                    echo "<td style='text-align:center'>".$pasar."</td>";
                                    echo "<td style='text-align:center'>".$medan_selera."</td>";
                                    echo "<td style='text-align:center'>".$upen."</td>";
                                    echo "<td style='text-align:center'>".$kiosk."</td>";
                                    echo "<td style='text-align:center'>".$septemberCount."</td>";
                                ?>                                
                            </tr>
                            <tr>
                                <th>October</th>
                                <?php
                                    $octoberCount = 0;
                                    $plb = 0;
                                    $pasar = 0;
                                    $medan_selera = 0;
                                    $upen = 0;
                                    $kiosk = 0;
                                    foreach ($data_report as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BULAN"] == 10)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BULAN"] == 10)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BULAN"] == 10)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BULAN"] == 10)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BULAN"] == 10)
                                        {
                                            $kiosk = $data["BIL"];
                                        }
                                    }
                                    $octoberCount = $plb + $pasar + $medan_selera + $upen + $kiosk;
                                    echo "<td style='text-align:center'>".$plb."</td>";
                                    echo "<td style='text-align:center'>".$pasar."</td>";
                                    echo "<td style='text-align:center'>".$medan_selera."</td>";
                                    echo "<td style='text-align:center'>".$upen."</td>";
                                    echo "<td style='text-align:center'>".$kiosk."</td>";
                                    echo "<td style='text-align:center'>".$octoberCount."</td>";
                                ?>                                
                            </tr>
                            <tr>
                                <th>November</th>
                                <?php
                                    $novemberCount = 0;
                                    $plb = 0;
                                    $pasar = 0;
                                    $medan_selera = 0;
                                    $upen = 0;
                                    $kiosk = 0;
                                    foreach ($data_report as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BULAN"] == 11)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BULAN"] == 11)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BULAN"] == 11)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BULAN"] == 11)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BULAN"] == 11)
                                        {
                                            $kiosk = $data["BIL"];
                                        }
                                    }
                                    $novemberCount = $plb + $pasar + $medan_selera + $upen + $kiosk;
                                    echo "<td style='text-align:center'>".$plb."</td>";
                                    echo "<td style='text-align:center'>".$pasar."</td>";
                                    echo "<td style='text-align:center'>".$medan_selera."</td>";
                                    echo "<td style='text-align:center'>".$upen."</td>";
                                    echo "<td style='text-align:center'>".$kiosk."</td>";
                                    echo "<td style='text-align:center'>".$novemberCount."</td>";
                                ?>                                
                            </tr>
                            <tr>
                                <th>December</th>
                                <?php
                                    $decemberCount = 0;
                                    $plb = 0;
                                    $pasar = 0;
                                    $medan_selera = 0;
                                    $upen = 0;
                                    $kiosk = 0;
                                    foreach ($data_report as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BULAN"] == 12)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BULAN"] == 12)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BULAN"] == 12)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BULAN"] == 12)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BULAN"] == 12)
                                        {
                                            $kiosk = $data["BIL"];
                                        }
                                    }
                                    $decemberCount = $plb + $pasar + $medan_selera + $upen + $kiosk;
                                    echo "<td style='text-align:center'>".$plb."</td>";
                                    echo "<td style='text-align:center'>".$pasar."</td>";
                                    echo "<td style='text-align:center'>".$medan_selera."</td>";
                                    echo "<td style='text-align:center'>".$upen."</td>";
                                    echo "<td style='text-align:center'>".$kiosk."</td>";
                                    echo "<td style='text-align:center'>".$decemberCount."</td>";
                                ?>                                
                            </tr>
                            <!-- <?php 
                            $totalCount = $januaryCount + $februaryCount + $marchCount + $aprilCount + $mayCount + $junCount + $julyCount + $augustCount + $septemberCount + $octoberCount + $novemberCount + $decemberCount;
                            ?>
                            <tr style="background-color: #adaaaa;font-weight: bold">
                                <td colspan="6" style="text-align: right">Jumlah Keseluruhan</td>
                                <td style="text-align: center"><?php echo $totalCount; ?></td> -->
                            </tr>
                        </table>
                        <script>
                        $(document).ready(function(){
                          var result = [];
                          $('table tr').each(function(){
                            $('td', this).each(function(index, val){
                                if(!result[index]) result[index] = 0;
                              result[index] += parseInt($(val).text());
                            });
                          });

                          $('table').append('<tr style="background-color: #adaaaa;font-weight: bold"></tr>');
                          $('table tr').last().append('<td style=text-align:center>Jumlah Keseluruhan</td>')
                          $(result).each(function(){
                            $('table tr').last().append('<td style=text-align:center>'+this+'</td>')
                          });
                        });
                    </script>
                    </div>
                <!-- </div> -->
            </div>
    </div>
