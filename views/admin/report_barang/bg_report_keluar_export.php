<?php


$jenis = $this->uri->segment(4);

if($jenis == "excel"){

	header("Content-type: application/vnd-ms-excel");

	header("Content-Disposition: attachment; filename=export_barang_keluar_".date('d_m_y').".xls");

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
    margin-right:0.9cm;

  }

</style>

<style>

  body{

    padding-left: 0.8cm;

    padding-right: 0.8cm;

    padding-top: 1.1cm;

  }

</style>

</head>

<body>

<?php echo "<p style='font-size:12px'>".date("d/m/Y"). "</p>"; ?>

<center>

	<h3>LAPORAN BARANG KELUAR <?php echo strtoupper($getPerusahaan); ?> <br> DARI TANGGAL <?php echo date("d/m/Y",strtotime($_SESSION['rick_auto']['tanggalfromrrk']));?> - <?php echo date("d/m/Y",strtotime($_SESSION['rick_auto']['tanggaltorrk']));?></h3>

</center>

<table class="table" border="1" cellspacing="0" width="105%">

	<tr>

		<th>No</th>

		<th>Tanggal Invoice</th>

		<th>No. Invoice</th>

		<th>No. Sales Order</th>

		<th>Gudang</th>

		<th>Nama Buyer / Member</th>

		<th>Kota</th>

		<th>Produk</th>

		<th>QTY</th>

		<th>Satuan</th>

		<th>Unit Price</th>

	</tr>

	<?php

	$no = 0;
	$temp = 0;
	foreach($getData->result() as $data){
		if ($data->note == "Adjusment" || $data->note == "adjusment"){
			echo "";
		} else{
			$temp = $temp + $data->stock_input;
		
		$no++;

		echo"

	<tr>

		<td style='font-size:13px'>$no</td>

		<td style='font-size:13px'>".date("d/m/Y",strtotime($data->create_date))."</td>";

		if($data->purchase_detail_id == "" || $data->purchase_detail_id == "0" || $data->purchase_detail_id == 0){

			if($data->nonota == ""){

				echo"

				<td width='5px'>".wordwrap(Adjustment, 6, '<br />', true)."</td>";


			}else{

				echo"

				<td width='5px'>".str_replace("PT.E","PT.ETC",$data->nonota)."</td>";

			}

		}else{

				echo"

		<td style='font-size:13px'>".wordwrap(str_replace("PT.E","PT.ETC",$data->nonota_nota), 10, true)."</td>";

		}echo"

		<td style='font-size:13px'>".wordwrap(str_replace("PT.E","PT.ETC",$data->no_purchase), 10, true)."</td>

		<td align='center'; style='font-size:14px'>".$data->nama_gudang."</td>";

		if($data->purchase_detail_id == "" || $data->purchase_detail_id == "0" || $data->purchase_detail_id == 0){

			echo"

		<td style='font-size:13px'>".$data->nama_member."</td>";

		}else{

			echo"

		<td style='font-size:13px'>".$data->nama_member_po."</td>";


		}if($data->purchase_detail_id == "" || $data->purchase_detail_id == "0" || $data->purchase_detail_id == 0){

			echo"

		<td align='center'; style='font-size:14px'>".$data->kota."</td>";

		}else{

			echo"

		<td align='center'; style='font-size:14px'>".$data->kota_po."</td>";

		}echo"

		<td width='70%'; style='font-size:13px'>".$data->nama_produk."</td>

		<td align='center'>".$data->stock_input."</td>

		<td>".$data->nama_satuan."</td>

		<td align='right'; width='24%'>".number_format($data->harga_satuan_po,0,',','.')."</td>

	</tr>";}}?>
	<tr>
		
		<td style="text-align: center; border: none;" colspan="8"><b>Total</b></td>
		<td style="text-align: center; border: none;"><?= $temp ?></td> 
		<td style="border: none;"><?= $data->nama_satuan ?></td>
		
	</tr>

	

</table>

</body>

</html>

<?php

if($jenis == "print"){

	echo"

	<script>

		window.print();

	</script>

	";

}?>
