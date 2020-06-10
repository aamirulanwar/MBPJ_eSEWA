<style>
    th{
        text-align: center;
    }
</style>
<form method="post" action="/report/kutipan_tunggakan/">
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>
            <div class="form-group row">                
                <div class="col-sm-4">
                    <label class="col-form-label">Tahun</label>
                    <select name="selectedYear" id="selectedYear" class="form-control js-example-basic-single">
                        <option value=""> - Sila Pilih - </option>
                        <?php

                        	$currentYear = date('Y');
                        	$loopedYear = $currentYear;
                        	// echo "<option value='$currentYear'> $currentYear </option>";
                        	while ( $loopedYear >= $currentYear-10 ) 
                        	{
                        		# code...
                        		if ($data_search["selectedYear"] == $loopedYear)
                        		{
                        			echo "<option value='$loopedYear' selected> $loopedYear </option>";
                        		}
                        		else
                        		{
                        			echo "<option value='$loopedYear'> $loopedYear </option>";
                        		}

                        		$loopedYear = $loopedYear - 1;
                        	}
                        ?>
                    </select>
                </div>
                <div class="col-sm-8">
                    <!-- nothing here for now -->
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
        	<h2 style="text-align:center"> 
        		<b>
	        		LAPORAN KUTIPAN TUNGGAKAN SEWAAN OLEH </br>
	        		JABATAN UNDANG-UNDANG SETAKAT 31 DIS <?=$data_search["selectedYear"]?>
	        	</b>
        	</h2>
        	<table class="table table-bordered">
        		<tr>
        			<td style="text-align:center" rowspan="2"> JENIS NOTIS / BULAN </td>
        			<td style="text-align:center" colspan="4"> NOTIS TUNTUTAN TUNGGAKAN </td>
        			<td style="text-align:center" colspan="4"> NOTIS TINDAKAN MAHKAMAH </td>
        		</tr>
        		<tr>
        			<td style="text-align:center"> JUMLAH NOTIS </td>
        			<td style="text-align:center"> TUNGGAKAN DITUNTUT (RM) </td>
        			<td style="text-align:center"> BAYARAN (RM) </td>
        			<td style="text-align:center"> TUNGGAKAN BELUM DIJELASKAN (RM) </td>
        			<td style="text-align:center"> JUMLAH NOTIS </td>
        			<td style="text-align:center"> TUNGGAKAN DITUNTUT (RM) </td>
        			<td style="text-align:center"> BAYARAN (RM) </td>
        			<td style="text-align:center"> TUNGGAKAN BELUM DIJELASKAN (RM) </td>
        		</tr>
				<tr>
					<td style="text-align:center"> JAN </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Jan']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Jan']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Jan']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Jan']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Jan']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Jan']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Jan']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Jan']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
				</tr>
				<tr>
					<td style="text-align:center"> FEB </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Feb']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Feb']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Feb']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Feb']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Feb']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Feb']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Feb']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Feb']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
				</tr>
				<tr>
					<td style="text-align:center"> MAC </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Mac']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Mac']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Mac']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Mac']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Mac']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Mac']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Mac']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Mac']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
				</tr>
				<tr>
					<td style="text-align:center"> APR </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Apr']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Apr']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Apr']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Apr']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Apr']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Apr']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Apr']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Apr']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
				</tr>
				<tr>
					<td style="text-align:center"> MEI </td>
					<td style="text-align:center"> <?=isset($data_month_lod['May']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['May']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_lod['May']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['May']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center"> <?=isset($data_month_mah['May']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['May']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_mah['May']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['May']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
				</tr>
				<tr>
					<td style="text-align:center"> JUN </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Jun']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Jun']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Jun']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Jun']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Jun']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Jun']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Jun']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Jun']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
				</tr>
				<tr>
					<td style="text-align:center"> JULAI </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Jul']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Jul']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Jul']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Jul']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Jul']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Jul']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Jul']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Jul']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
				</tr>
				<tr>
					<td style="text-align:center"> OGOS </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Aug']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Aug']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Aug']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Aug']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Aug']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Aug']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Aug']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Aug']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
				</tr>
				<tr>
					<td style="text-align:center"> SEP </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Sep']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Sep']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Sep']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Sep']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Sep']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Sep']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Sep']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Sep']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
				</tr>
				<tr>
					<td style="text-align:center"> OKT </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Oct']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Oct']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Oct']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Oct']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Oct']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Oct']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Oct']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Oct']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
				</tr>
				<tr>
					<td style="text-align:center"> NOV </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Nov']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Nov']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Nov']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Nov']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Nov']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Nov']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Nov']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Nov']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
				</tr>
				<tr>
					<td style="text-align:center"> DIS </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Dec']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Dec']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_lod['Dec']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Dec']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Dec']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Dec']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
					<td style="text-align:center"> <?=isset($data_month_mah['Dec']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Dec']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
					<td style="text-align:center">  </td>
					<td style="text-align:center">  </td>
				</tr>
        	</table>
    	</div>
    	<div style="align:right">
    		<button onclick="print()">Cetak</button>
    	</div>
    </div>
</div>

<script type="text/javascript">
	function print()
	{
		var selectedYear = $("#selectedYear").val();
		window.open('/report/print_kutipan_tunggakan?key='+selectedYear,'_blank');
	}
</script>
