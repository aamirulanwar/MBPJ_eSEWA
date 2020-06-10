<html lang="en">
<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>LAPORAN KUTIPAN TUNGGAKAN SEWAAN OLEH JABATAN UNDANG-UNDANG SETAKAT 31 DIS <?=$data_search["selectedYear"]?></title>
    <!-- Icons-->
    <link rel="icon" href="<?=base_url()?>/favicon.ico" type="image/x-icon">
    <link href="/assets/node_modules/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
    <link href="/assets/node_modules/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
    <link href="/assets/node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/node_modules/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">

    <script src="/assets/node_modules/jquery/dist/jquery.min.js"></script>
    <link href="/assets/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <style>
		@page { size: A4 landscape;margin: 1cm; }
	</style>
</head>
<body>
	<div class="table-responsive">
		<h2 style="text-align:center;font-size:20px"> 
			<b>
	    		LAPORAN KUTIPAN TUNGGAKAN SEWAAN OLEH </br>
	    		JABATAN UNDANG-UNDANG SETAKAT 31 DIS <?=$data_search["selectedYear"]?>
	    	</b>
			</br>
			&nbsp;
		</h2>
		<table class="table table-bordered">
			<tr>
				<td style="text-align:center;font-size:13px;font-weight: bold;" rowspan="2"> JENIS NOTIS / BULAN </td>
				<td style="text-align:center;font-size:13px;font-weight: bold;" colspan="4"> NOTIS TUNTUTAN TUNGGAKAN </td>
				<td style="text-align:center;font-size:13px;font-weight: bold;" colspan="4"> NOTIS TINDAKAN MAHKAMAH </td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px;font-weight: bold;"> JUMLAH NOTIS </td>
				<td style="text-align:center;font-size:13px;font-weight: bold;"> TUNGGAKAN DITUNTUT (RM) </td>
				<td style="text-align:center;font-size:13px;font-weight: bold;"> BAYARAN (RM) </td>
				<td style="text-align:center;font-size:13px;font-weight: bold;"> TUNGGAKAN BELUM DIJELASKAN (RM) </td>
				<td style="text-align:center;font-size:13px;font-weight: bold;"> JUMLAH NOTIS </td>
				<td style="text-align:center;font-size:13px;font-weight: bold;"> TUNGGAKAN DITUNTUT (RM) </td>
				<td style="text-align:center;font-size:13px;font-weight: bold;"> BAYARAN (RM) </td>
				<td style="text-align:center;font-size:13px;font-weight: bold;"> TUNGGAKAN BELUM DIJELASKAN (RM) </td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px"> JAN </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Jan']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Jan']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Jan']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Jan']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Jan']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Jan']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Jan']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Jan']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px"> FEB </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Feb']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Feb']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Feb']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Feb']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Feb']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Feb']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Feb']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Feb']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px"> MAC </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Mac']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Mac']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Mac']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Mac']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Mac']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Mac']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Mac']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Mac']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px"> APR </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Apr']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Apr']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Apr']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Apr']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Apr']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Apr']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Apr']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Apr']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px"> MEI </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['May']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['May']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['May']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['May']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['May']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['May']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['May']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['May']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px"> JUN </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Jun']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Jun']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Jun']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Jun']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Jun']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Jun']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Jun']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Jun']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px"> JULAI </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Jul']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Jul']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Jul']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Jul']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Jul']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Jul']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Jul']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Jul']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px"> OGOS </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Aug']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Aug']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Aug']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Aug']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Aug']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Aug']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Aug']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Aug']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px"> SEP </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Sep']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Sep']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Sep']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Sep']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Sep']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Sep']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Sep']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Sep']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px"> OKT </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Oct']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Oct']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Oct']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Oct']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Oct']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Oct']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Oct']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Oct']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px"> NOV </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Nov']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Nov']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Nov']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Nov']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Nov']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Nov']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Nov']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Nov']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
			</tr>
			<tr>
				<td style="text-align:center;font-size:13px"> DIS </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Dec']["0"]["TOTAL_GENERATED"]) ? $data_month_lod['Dec']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_lod['Dec']["0"]["TOTAL_AMOUNT"]) ? $data_month_lod['Dec']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Dec']["0"]["TOTAL_GENERATED"]) ? $data_month_mah['Dec']["0"]["TOTAL_GENERATED"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px"> <?=isset($data_month_mah['Dec']["0"]["TOTAL_AMOUNT"]) ? $data_month_mah['Dec']["0"]["TOTAL_AMOUNT"] : 0 ?> </td>
				<td style="text-align:center;font-size:13px">  </td>
				<td style="text-align:center;font-size:13px">  </td>
			</tr>
		</table>
	</div>
	<script>
		$( window ).on('load', '', function(event) {
			window.print();
		});

		$(function()
		{
	    $("body").hover(function(){
	        window.close();
	    });
		});
	</script>
</body>