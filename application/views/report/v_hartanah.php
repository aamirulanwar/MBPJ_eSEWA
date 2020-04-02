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
                                    foreach ($data_jan as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BIL"] > 0)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BIL"] > 0)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BIL"] > 0)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BIL"] > 0)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BIL"] > 0)
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
                                    foreach ($data_feb as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BIL"] > 0)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BIL"] > 0)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BIL"] > 0)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BIL"] > 0)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BIL"] > 0)
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
                                    foreach ($data_mar as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BIL"] > 0)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BIL"] > 0)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BIL"] > 0)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BIL"] > 0)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BIL"] > 0)
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
                                    foreach ($data_apr as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BIL"] > 0)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BIL"] > 0)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BIL"] > 0)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BIL"] > 0)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BIL"] > 0)
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
                                    foreach ($data_may as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BIL"] > 0)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BIL"] > 0)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BIL"] > 0)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BIL"] > 0)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BIL"] > 0)
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
                                    foreach ($data_jun as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BIL"] > 0)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BIL"] > 0)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BIL"] > 0)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BIL"] > 0)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BIL"] > 0)
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
                                    foreach ($data_jul as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BIL"] > 0)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BIL"] > 0)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BIL"] > 0)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BIL"] > 0)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BIL"] > 0)
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
                                    foreach ($data_aug as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BIL"] > 0)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BIL"] > 0)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BIL"] > 0)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BIL"] > 0)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BIL"] > 0)
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
                                    foreach ($data_sep as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BIL"] > 0)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BIL"] > 0)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BIL"] > 0)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BIL"] > 0)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BIL"] > 0)
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
                                    foreach ($data_oct as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BIL"] > 0)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BIL"] > 0)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BIL"] > 0)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BIL"] > 0)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BIL"] > 0)
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
                                    foreach ($data_nov as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BIL"] > 0)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BIL"] > 0)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BIL"] > 0)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BIL"] > 0)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BIL"] > 0)
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
                                    foreach ($data_dec as $data)
                                    {
                                        if( $data["TYPE_ID"] == 3 && $data["BIL"] > 0)
                                        {
                                            $plb = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 1 && $data["BIL"] > 0)
                                        {
                                            $pasar = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 2 && $data["BIL"] > 0)
                                        {
                                            $medan_selera = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 4 && $data["BIL"] > 0)
                                        {
                                            $upen = $data["BIL"];
                                        }
                                        else if( $data["TYPE_ID"] == 5 && $data["BIL"] > 0)
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
