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
            <h3 class="box-title">TAPAK PAPAN IKLAN LUARAN</h3>
        </div>
            <div class="card-body">
                <!-- <div class="pull-right"> -->
                        <table class="table table-hover table-bordered table-aging" style="margin-bottom: 0px;">
                            <tr>
                                <th width="12%" rowspan="2" style="text-align:center">JENIS PERJANJIAN</th>
                                <th colspan="12" width="7%" style="text-align:center">BULAN</th>
                                <th width="5%" rowspan="2" style="text-align:center">JUMLAH</th>
                            </tr>
                            <tr>
                                <th width="3%">1</th>
                                <th width="3%">2</th>
                                <th width="3%">3</th>
                                <th width="3%">4</th>
                                <th width="3%">5</th>
                                <th width="3%">6</th>
                                <th width="3%">7</th>
                                <th width="3%">8</th>
                                <th width="3%">9</th>
                                <th width="3%">10</th>
                                <th width="3%">11</th>
                                <th width="3%">12</th>
                            </tr>
                            <tr>
                                <th>INTERIM</th>
                                <?php
                                    $InterimCount = 0;
                                    $jan = 0;
                                    $feb = 0;
                                    $mar = 0;
                                    $apr = 0;
                                    $may = 0;
                                    $jun = 0;
                                    $jul = 0;
                                    $aug = 0;
                                    $sep = 0;
                                    $oct = 0;
                                    $nov = 0;
                                    $dec = 0;
                                    
                                    foreach ($data as $d)
                                    {
                                        if( $d["BILLBOARD_TYPE"] == 1 && $d["BULAN"] == 1)
                                        {
                                            $jan = $d["BIL"];
                                        }
                                        else if( $d["BILLBOARD_TYPE"] == 1 && $d["BULAN"] == 2)
                                        {
                                            $feb = $d["BIL"];
                                        }
                                        else if( $d["BILLBOARD_TYPE"] == 1 && $d["BULAN"] == 3)
                                        {
                                            $mar = $d["BIL"];
                                        }
                                        else if( $d["BILLBOARD_TYPE"] == 1 && $d["BULAN"] == 4)
                                        {
                                            $apr = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 1 && $d["BULAN"] == 5)
                                        {
                                            $may = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 1 && $d["BULAN"] == 6)
                                        {
                                            $jun = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 1 && $d["BULAN"] == 7)
                                        {
                                            $jul = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 1 && $d["BULAN"] == 8)
                                        {
                                            $aug = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 1 && $d["BULAN"] == 9)
                                        {
                                            $sep = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 1 && $d["BULAN"] == 10)
                                        {
                                            $oct = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 1 && $d["BULAN"] == 11)
                                        {
                                            $nov = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 1 && $d["BULAN"] == 12)
                                        {
                                            $dec = $d["BIL"];
                                        }
                                    }
                                    $InterimCount = $jan + $feb + $mar + $apr + $may + $jun + $jul + $aug + $sep + $oct + $nov + $dec;
                                    echo "<td style='text-align:center'>".$jan."</td>";
                                    echo "<td style='text-align:center'>".$feb."</td>";
                                    echo "<td style='text-align:center'>".$mar."</td>";
                                    echo "<td style='text-align:center'>".$apr."</td>";
                                    echo "<td style='text-align:center'>".$may."</td>";
                                    echo "<td style='text-align:center'>".$jun."</td>";
                                    echo "<td style='text-align:center'>".$jul."</td>";
                                    echo "<td style='text-align:center'>".$aug."</td>";
                                    echo "<td style='text-align:center'>".$sep."</td>";
                                    echo "<td style='text-align:center'>".$oct."</td>";
                                    echo "<td style='text-align:center'>".$nov."</td>";
                                    echo "<td style='text-align:center'>".$dec."</td>";
                                    echo "<td style='text-align:center'>".$InterimCount."</td>";
                                ?>                                
                            </tr>
                            <tr>
                                <th>SUB - LESEN</th>
                                <?php
                                    $SublesenCount = 0;
                                    $jan = 0;
                                    $feb = 0;
                                    $mar = 0;
                                    $apr = 0;
                                    $may = 0;
                                    $jun = 0;
                                    $jul = 0;
                                    $aug = 0;
                                    $sep = 0;
                                    $oct = 0;
                                    $nov = 0;
                                    $dec = 0;
                                    
                                    foreach ($data as $d)
                                    {
                                        if( $d["BILLBOARD_TYPE"] == 2 && $d["BULAN"] == 1)
                                        {
                                            $jan = $d["BIL"];
                                        }
                                        else if( $d["BILLBOARD_TYPE"] == 2 && $d["BULAN"] == 2)
                                        {
                                            $feb = $d["BIL"];
                                        }
                                        else if( $d["BILLBOARD_TYPE"] == 2 && $d["BULAN"] == 3)
                                        {
                                            $mar = $d["BIL"];
                                        }
                                        else if( $d["BILLBOARD_TYPE"] == 2 && $d["BULAN"] == 4)
                                        {
                                            $apr = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 2 && $d["BULAN"] == 5)
                                        {
                                            $may = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 2 && $d["BULAN"] == 6)
                                        {
                                            $jun = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 2 && $d["BULAN"] == 7)
                                        {
                                            $jul = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 2 && $d["BULAN"] == 8)
                                        {
                                            $aug = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 2 && $d["BULAN"] == 9)
                                        {
                                            $sep = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 2 && $d["BULAN"] == 10)
                                        {
                                            $oct = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 2 && $d["BULAN"] == 11)
                                        {
                                            $nov = $d["BIL"];
                                        }
                                        else if ( $d["BILLBOARD_TYPE"] == 2 && $d["BULAN"] == 12)
                                        {
                                            $dec = $d["BIL"];
                                        }
                                    }
                                    $SublesenCount = $jan + $feb + $mar + $apr + $may + $jun + $jul + $aug + $sep + $oct + $nov + $dec;
                                    echo "<td style='text-align:center'>".$jan."</td>";
                                    echo "<td style='text-align:center'>".$feb."</td>";
                                    echo "<td style='text-align:center'>".$mar."</td>";
                                    echo "<td style='text-align:center'>".$apr."</td>";
                                    echo "<td style='text-align:center'>".$may."</td>";
                                    echo "<td style='text-align:center'>".$jun."</td>";
                                    echo "<td style='text-align:center'>".$jul."</td>";
                                    echo "<td style='text-align:center'>".$aug."</td>";
                                    echo "<td style='text-align:center'>".$sep."</td>";
                                    echo "<td style='text-align:center'>".$oct."</td>";
                                    echo "<td style='text-align:center'>".$nov."</td>";
                                    echo "<td style='text-align:center'>".$dec."</td>";
                                    echo "<td style='text-align:center'>".$SublesenCount."</td>";
                                ?>                                
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
                                        $('table tr').last().append('<td style=text-align:center>JUMLAH KESELURUHAN</td>')
                                        $(result).each(function(){
                                        $('table tr').last().append('<td style=text-align:center>'+this+'</td>')
                                      });
                                });
                        </script>
                    </div>
                <!-- </div> -->
            </div>
    </div>
