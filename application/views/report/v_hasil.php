<style>
    th{
        text-align: center;
    }
    td{
        text-align: center;
    }
</style>
 <form method="post" action="/report/hasil/">
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
            <h3 class="box-title">Laporan Hasil Perjanjian Sewa</h3>
        </div>
            <div class="card-body">
                <table class="table table-hover table-bordered table-aging" style="margin-bottom: 0px;">
                    <tr>
                        <th width="3%" rowspan="2" style="text-align:center">BIL</th>
                        <th width="7%" rowspan="2" style="text-align:center">BULAN</th>
                        <th width="12%" colspan="2" style="text-align:center">JENIS PERJANJIAN (RM)</th>
                        <th width="7%" rowspan="2" style="text-align:center">JUMLAH</th>
                        <th width="7%" rowspan="2" style="text-align:center">SASARAN TAHUNAN</th>
                        <th width="7%" rowspan="2" style="text-align:center">PERATUSAN (%)</th>
                    </tr>
                    <tr>            
                        <th width='3%'>HARTANAH</th>
                        <th width='3%'>BILLBOARD</th>
                    </tr>
                    <tr>
                        <th>1.</th>
                        <th>JANUARI</th>
                        <?php if (count($data_report) > 0): ?>
                        <?php
                            $januaryCount = 0;
                            $total_hartanah = 0;
                            $total_billboard = 0;
                            foreach ($data_report as $data)
                            {
                                if( $data["TYPE_ID"] != 6 && $data["BULAN"] == 1)
                                {
                                    $hartanah = $data["BIL"];
                                }
                                else if( $data["TYPE_ID"] == 6 && $data["BULAN"] == 1)
                                {
                                    $billboard = $data["BIL"];
                                }
                            }
                            $total_hartanah = $hartanah * 100;
                            $total_billboard = $billboard * 100;
                            $januaryCount = $total_hartanah + $total_billboard;
                            echo "<td style='text-align:center'>".$total_hartanah."</td>";
                            echo "<td style='text-align:center'>".$total_billboard."</td>";
                            echo "<td style='text-align:center'>".$januaryCount."</td>";
                            echo "<th style='text-align:center'></th>";
                            echo "<th style='text-align:center'></th";
                        ?>                                            
                    </tr>
                    <tr>
                        <th>2.</th>
                        <th>FEBRUARI</th>
                        <?php
                            $februaryCount = 0;
                            $total_hartanah = 0;
                            $total_billboard = 0;
                            foreach ($data_report as $data)
                            {
                                if( $data["TYPE_ID"] != 6 && $data["BULAN"] == 2)
                                {
                                    $hartanah = $data["BIL"];
                                }
                                else if( $data["TYPE_ID"] == 6 && $data["BULAN"] == 2)
                                {
                                    $billboard = $data["BIL"];
                                }
                            }
                            $total_hartanah = $hartanah * 100;
                            $total_billboard = $billboard * 100;
                            $februaryCount = $total_hartanah + $total_billboard;
                            echo "<td style='text-align:center'>".$total_hartanah."</td>";
                            echo "<td style='text-align:center'>".$total_billboard."</td>";
                            echo "<td style='text-align:center'>".$februaryCount."</td>";
                            echo "<th style='text-align:center'></th>";
                            echo "<th style='text-align:center'></th";
                        ?>                                                             
                    </tr>
                    <tr>
                        <th>3.</th>
                        <th>MAC</th>
                        <?php
                            $marchCount = 0;
                            $total_hartanah = 0;
                            $total_billboard = 0;
                            foreach ($data_report as $data)
                            {
                                if( $data["TYPE_ID"] != 6 && $data["BULAN"] == 3)
                                {
                                    $hartanah = $data["BIL"];
                                }
                                else if( $data["TYPE_ID"] == 6 && $data["BULAN"] == 3)
                                {
                                    $billboard = $data["BIL"];
                              
                              $total_hartanah = $hartanah * 100;
                            $total_billboard = $billboard * 100;  }
                            }

                            $marchCount = $total_hartanah + $total_billboard;
                            echo "<td style='text-align:center'>".$total_hartanah."</td>";
                            echo "<td style='text-align:center'>".$total_billboard."</td>";
                            echo "<td style='text-align:center'>".$marchCount."</td>";
                            echo "<th style='text-align:center'></th>";
                            echo "<th style='text-align:center'></th";
                        ?>                                                             
                    </tr>
                    <tr>
                        <th>4.</th>
                        <th>APRIL</th>            
                        <?php
                            $aprilCount = 0;
                            $total_hartanah = 0;
                            $total_billboard = 0;
                            foreach ($data_report as $data)
                            {
                                if( $data["TYPE_ID"] != 6 && $data["BULAN"] == 4)
                                {
                                    $hartanah = $data["BIL"];
                                }
                                else if( $data["TYPE_ID"] == 6 && $data["BULAN"] == 4)
                                {
                                    $billboard = $data["BIL"];
                                }
                            }
                            $total_hartanah = $hartanah * 100;
                            $total_billboard = $billboard * 100;
                            $aprilCount = $total_hartanah + $total_billboard;
                            echo "<td style='text-align:center'>".$total_hartanah."</td>";
                            echo "<td style='text-align:center'>".$total_billboard."</td>";
                            echo "<td style='text-align:center'>".$aprilCount."</td>";
                            echo "<th style='text-align:center'></th>";
                            echo "<th style='text-align:center'></th";
                        ?>                                   
                    </tr>
                    <tr>
                        <th>5.</th>
                        <th>MEI</th>              
                        <?php
                            $mayCount = 0;
                            $total_hartanah = 0;
                            $total_billboard = 0;
                            foreach ($data_report as $data)
                            {
                                if( $data["TYPE_ID"] != 6 && $data["BULAN"] == 5)
                                {
                                    $hartanah = $data["BIL"];
                                }
                                else if( $data["TYPE_ID"] == 6 && $data["BULAN"] == 5)
                                {
                                    $billboard = $data["BIL"];
                                }
                            }
                            $total_hartanah = $hartanah * 100;
                            $total_billboard = $billboard * 100;
                            $mayCount = $total_hartanah + $total_billboard;
                            echo "<td style='text-align:center'>".$total_hartanah."</td>";
                            echo "<td style='text-align:center'>".$total_billboard."</td>";
                            echo "<td style='text-align:center'>".$mayCount."</td>";
                            echo "<th style='text-align:center'></th>";
                            echo "<th style='text-align:center'></th";
                        ?>                              
                    </tr>
                    <tr>
                        <th>6.</th>
                        <th>JUN</th>              
                        <?php
                            $junCount = 0;
                            $total_hartanah = 0;
                            $total_billboard = 0;
                            foreach ($data_report as $data)
                            {
                                if( $data["TYPE_ID"] != 6 && $data["BULAN"] == 6)
                                {
                                    $hartanah = $data["BIL"];
                                }
                                else if( $data["TYPE_ID"] == 6 && $data["BULAN"] == 6)
                                {
                                    $billboard = $data["BIL"];
                                }
                            }
                            $total_hartanah = $hartanah * 100;
                            $total_billboard = $billboard * 100;
                            $junCount = $total_hartanah + $total_billboard;
                            echo "<td style='text-align:center'>".$total_hartanah."</td>";
                            echo "<td style='text-align:center'>".$total_billboard."</td>";
                            echo "<td style='text-align:center'>".$junCount."</td>";
                            echo "<th style='text-align:center'></th>";
                            echo "<th style='text-align:center'></th";
                        ?>                                 
                    </tr>
                    <tr>
                        <th>7.</th>
                        <th>JULAI</th>            
                        <?php
                            $julyCount = 0;
                            $total_hartanah = 0;
                            $total_billboard = 0;
                            foreach ($data_report as $data)
                            {
                                if( $data["TYPE_ID"] != 6 && $data["BULAN"] == 7)
                                {
                                    $hartanah = $data["BIL"];
                                }
                                else if( $data["TYPE_ID"] == 6 && $data["BULAN"] == 7)
                                {
                                    $billboard = $data["BIL"];
                                }
                            }
                            $total_hartanah = $hartanah * 100;
                            $total_billboard = $billboard * 100;
                            $julyCount = $total_hartanah + $total_billboard;
                            echo "<td style='text-align:center'>".$total_hartanah."</td>";
                            echo "<td style='text-align:center'>".$total_billboard."</td>";
                            echo "<td style='text-align:center'>".$julyCount."</td>";
                            echo "<th style='text-align:center'></th>";
                            echo "<th style='text-align:center'></th";
                        ?>                                
                    </tr>
                    <tr>
                        <th>8.</th>
                        <th>OGOS</th>             
                        <?php
                            $augustCount = 0;
                            $total_hartanah = 0;
                            $total_billboard = 0;
                            foreach ($data_report as $data)
                            {
                                if( $data["TYPE_ID"] != 6 && $data["BULAN"] == 8)
                                {
                                    $hartanah = $data["BIL"];
                                }
                                else if( $data["TYPE_ID"] == 6 && $data["BULAN"] == 8)
                                {
                                    $billboard = $data["BIL"];
                                }
                            }
                            $total_hartanah = $hartanah * 100;
                            $total_billboard = $billboard * 100;
                            $augustCount = $total_hartanah + $total_billboard;
                            echo "<td style='text-align:center'>".$total_hartanah."</td>";
                            echo "<td style='text-align:center'>".$total_billboard."</td>";
                            echo "<td style='text-align:center'>".$augustCount."</td>";
                            echo "<th style='text-align:center'></th>";
                            echo "<th style='text-align:center'></th";
                        ?>                                  
                    </tr> 
                    <tr>
                        <th>9.</th>
                        <th>SEPTEMBER</th>        
                        <?php
                            $septCount = 0;
                            $total_hartanah = 0;
                            $total_billboard = 0;
                            foreach ($data_report as $data)
                            {
                                if( $data["TYPE_ID"] != 6 && $data["BULAN"] == 9)
                                {
                                    $hartanah = $data["BIL"];
                                }
                                else if( $data["TYPE_ID"] == 6 && $data["BULAN"] == 9)
                                {
                                    $billboard = $data["BIL"];
                                }
                            }
                            $total_hartanah = $hartanah * 100;
                            $total_billboard = $billboard * 100;
                            $septCount = $total_hartanah + $total_billboard;
                            echo "<td style='text-align:center'>".$total_hartanah."</td>";
                            echo "<td style='text-align:center'>".$total_billboard."</td>";
                            echo "<td style='text-align:center'>".$septCount."</td>";
                            echo "<th style='text-align:center'></th>";
                            echo "<th style='text-align:center'></th";
                        ?>                                    
                    </tr>
                    <tr>
                        <th>10.</th>
                        <th>OKTOBER</th>          
                        <?php
                            $octCount = 0;
                            $total_hartanah = 0;
                            $total_billboard = 0;
                            foreach ($data_report as $data)
                            {
                                if( $data["TYPE_ID"] != 6 && $data["BULAN"] == 10)
                                {
                                    $hartanah = $data["BIL"];
                                }
                                else if( $data["TYPE_ID"] == 6 && $data["BULAN"] == 10)
                                {
                                    $billboard = $data["BIL"];
                                }
                            }
                            $total_hartanah = $hartanah * 100;
                            $total_billboard = $billboard * 100;
                            $octCount = $total_hartanah + $total_billboard;
                            echo "<td style='text-align:center'>".$total_hartanah."</td>";
                            echo "<td style='text-align:center'>".$total_billboard."</td>";
                            echo "<td style='text-align:center'>".$octCount."</td>";
                            echo "<th style='text-align:center'></th>";
                            echo "<th style='text-align:center'></th";
                        ?>                                     
                    </tr>
                    <tr>
                        <th>11.</th>
                        <th>NOVEMBER</th>         
                        <?php
                            $novCount = 0;
                            $total_hartanah = 0;
                            $total_billboard = 0;
                            foreach ($data_report as $data)
                            {
                                if( $data["TYPE_ID"] != 6 && $data["BULAN"] == 11)
                                {
                                    $hartanah = $data["BIL"];
                                }
                                else if( $data["TYPE_ID"] == 6 && $data["BULAN"] == 11)
                                {
                                    $billboard = $data["BIL"];
                                }
                            }
                            $total_hartanah = $hartanah * 100;
                            $total_billboard = $billboard * 100;
                            $novCount = $total_hartanah + $total_billboard;
                            echo "<td style='text-align:center'>".$total_hartanah."</td>";
                            echo "<td style='text-align:center'>".$total_billboard."</td>";
                            echo "<td style='text-align:center'>".$novCount."</td>";
                            echo "<th style='text-align:center'></th>";
                            echo "<th style='text-align:center'></th";
                        ?>                                   
                    </tr>
                    <tr>
                        <th>12.</th>
                        <th>DISEMBER</th>         
                        <?php
                            $decCount = 0;
                            $total_hartanah = 0;
                            $total_billboard = 0;
                            foreach ($data_report as $data)
                            {
                                if( $data["TYPE_ID"] != 6 && $data["BULAN"] == 12)
                                {
                                    $hartanah = $data["BIL"];
                                }
                                else if( $data["TYPE_ID"] == 6 && $data["BULAN"] == 12)
                                {
                                    $billboard = $data["BIL"];
                                }
                            }
                            $total_hartanah = $hartanah * 100;
                            $total_billboard = $billboard * 100;
                            $decCount = $total_hartanah + $total_billboard;
                            echo "<td style='text-align:center'>".$total_hartanah."</td>";
                            echo "<td style='text-align:center'>".$total_billboard."</td>";
                            echo "<td style='text-align:center'>".$decCount."</td>";
                            echo "<th style='text-align:center'></th>";
                            echo "<th style='text-align:center'></th";
                        ?>                                      
                    </tr>
                    <?php endif; ?>
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
                          $('table tr').last().append('<td colspan="2" style=text-align:center>Jumlah Keseluruhan</td>')
                          $(result).each(function(){
                            $('table tr').last().append('<td style=text-align:center>'+this+'</td>')
                          })
                          $('table tr').last().append('<td style=text-align:center>150000</td>');
                        });
                    </script>
            </div>
        </div>
