<?php

	$jenis = $this->uri->segment(4);

	if($jenis == "excel"){

	header("Content-type: application/vnd-ms-excel");

	header("Content-Disposition: attachment; filename=Log_Mutasi_".date('d M y').".xls");

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


<?php echo "<p style='font-size:12px'>".date("d/m/y"). "</p>"; ?>
<center>

	<h3>LAPORAN MUTASI STOK <?php echo strtoupper($getData->row()->nama_perusahaan);?> 
	<br> DARI TANGGAL <?php echo date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_start_date_lt'])))." - ".date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_end_date_lt'])));?></h3>

</center>


<p align='left' style='font-size:14px;'><b>Dari Gudang: <?php echo($getData->row()->nama_gudang_from)?></b> <br> <b>Ke Gudang: <?php echo($getData->row()->nama_gudang_to)?></b></p>


<table class="table" width="100%" border="1" cellspacing="0">

		<tr>

			<th width="2%">#</th>

			<th width="45%">Produk</th>
			
			<!-- <th>Gudang Lama</th>
			
			<th>Gudang Baru</th> -->

			<th width="10%">Stock Sebelum Mutasi</th>

			<th width="10%">Stock Mutasi</th>

			<th width="10%">Stock Setelah Mutasi</th>

			<!-- <th>Keterangan</th> -->

			<th width="13%">Tanggal Mutasi</th>

			<th width="10%">Dibuat Oleh</th>

		</tr>

	<?php

	$no = 0;

	foreach($getData->result() as $data){

		$no++;

		echo"

		<tr>

			<td>$no</td>

			<td><p style='font-size:14px;'>".$data->nama_produk."</p></td>

			

			<td align='center'><p style='font-size:13px;'>".$data->from_stok." ".$data->nama_satuan."</p></td> 

			<td align='center'><p style='font-size:13px;'>".$data->to_stok." ".$data->nama_satuan."</p></td>"; 

			$newStok = $data->from_stok - $data->to_stok; 

			echo"

			<td align='center'><p style='font-size:13px;'>".$newStok."  ".$data->nama_satuan."</p></td>

			

			<td><p style='font-size:14px;'>".date("d M Y H:i",strtotime("+0 day", strtotime($data->create_date)))."</p></td>

			<td align='center'><p style='font-size:14px;'>".$data->create_user."</p></td>

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