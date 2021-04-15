<?php

	$jenis = $this->uri->segment(4);

	if($jenis == "excel"){

	header("Content-type: application/vnd-ms-excel");

	header("Content-Disposition: attachment; filename=Log_Adjustment_".date('d M y').".xls");

	}

?>

<!DOCTYPE>

<html>

<head>

	<title></title>

	<style type='text/css' media='print'>

  @page {

    size: auto;  

    margin: 0;  

  }

</style>

<style>

  body{

    padding-left: 1.3cm;

    padding-right: 1.3cm; 

    padding-top: 1.1cm;

  }

</style>

</head>

<body>



<center>



<h3>LAPORAN ADJUSTMENT STOK <?php echo strtoupper($getData->row()->nama_perusahaan);?> <br> DARI TANGGAL <?php echo date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_start_date_la'])))." - ".date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_end_date_la'])));?></h3>



</center>

 

<p align='left'><b>Gudang: <?php echo($getData->row()->nama_gudang)?></b></p>




<table class="table" width="100%" border="1" cellspacing="0">

		<tr>

			<th>#</th>

			<th>Produk</th>	

			<th>Stock Lama</th>

			<th>Stock Adjusment</th>

			<th>Stock Baru</th>

			<th>Catatan</th>

			<th>Tanggal Adjusment</th>

			<th>Transaksi dibuat</th>

		</tr>

	<?php

	$no = 0;

	foreach($getData->result() as $data){

		$no++;

		echo"

		<tr>

			<td>$no</td>

			<td style='font-size: 14px'>".$data->nama_produk."</td>

			<td style='font-size: 14px' align='center'>".$data->qty_product." ".$data->nama_satuan."</td>

			<td style='font-size: 14px' align='center'>".$data->stock_add." ".$data->nama_satuan."</td>";

			$newStok = $data->qty_product + $data->stock_add;

			echo"

			<td style='font-size: 14px' align='center'>".$newStok."  ".$data->nama_satuan."</td>

			<td>".$data->note."</td>

			<td style='font-size: 12px'>".date("d M Y H:i",strtotime("+0 day", strtotime($data->create_date)))."</td>

			<td>".$data->create_user."</td>

		</tr>

		";}?>

</table>

</body>

</html>



	<?php

	if($jenis == "print"){

		echo "	<script>

		window.print();

		window.onfocus=function(){ window.close();}

	</script>";}

	?>