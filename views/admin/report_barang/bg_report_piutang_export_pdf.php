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

	<h3><?php echo $_SESSION['rick_auto']['perusahaannamerp'];?> <br> Laporan Piutang Per Tanggal <?php echo date("d M ",strtotime($_SESSION['rick_auto']['tanggalfromrrp']));?> <?php echo date("Y",strtotime($_SESSION['rick_auto']['tanggalfromrrp']));?> </h3>

</center>



<table class='table' border="1" width="100%" cellspacing="0">

	<tr>

		<th width="5%">No</th>

		<th width="15%">No. Inv</th>

		<th width="15%">Buyer / Member</th>

		<th width="15%">Sales</th>

		<th width="15%">Sisa Tagihan</th>

		<th width="15%">Total Tagihan</th>

		<th width="15%">Status</th>

	</tr>

<?php

$totalSemuanyaa = 0;

foreach($getData->result() as $data){

	$getDataDetail = $this->model_invoice->getInvoiceByBulanTahun(date("m",strtotime($data->dateorder)), date("Y",strtotime($data->dateorder)));

	$no = 0;

	$sisa = 0;

	foreach($getDataDetail->result() as $dataDetail){

		$dataTotal = $this->model_invoice->getPaymentTandaTerimaAsc($dataDetail->no_tt)->row();

		$no++;

		echo"

	<tr>

		<td>$no</td>

		<td>".$dataDetail->no_tt."</td>

		<td>".$dataDetail->member_name." - ".$dataDetail->kota_member."</td>

		<td>".$dataDetail->sales_name."</td>";

		$dataBayar = $this->model_invoice->getPaymentTandaTerima($dataDetail->no_tt)->row();

		//$sisa = $sisa + $dataBayar->sisa;

		$sisaa = $dataTotal->total_pembayaran - $dataBayarr->total_sudah_dibayar;
		

		if($dataBayar->jenis_pembayaran == "Giro" || $dataBayar->jenis_pembayaran == "Cek"){
			
			$sisa = $sisa + $dataTotal->total_pembayaran;
			$s = $dataTotal->total_pembayaran;
			$tanggal_cair = "<br>Tanggal Cair : ".date("d/m/Y",strtotime($dataBayar->liquid_date));

		}else{
			$sisa = $sisa + $sisaa;
			$s = $sisaa;
			$tanggal_cair = "";
		}
		echo"

		<td style='text-align: right;'>Rp. ".number_format($dataBayar->sisa,0,',','.')."</td>

		<td style='text-align: right;'>Rp. ".number_format($dataTotal->total_pembayaran,0,',','.')."</td>

		<td>".strtoupper($dataBayar->jenis_pembayaran)." ".$tanggal_cair."</td>

	</tr>";}

	$getDataDetailOri = $this->model_invoice->getInvoiceOriByBulanTahun(date("m",strtotime($data->dateorder)), date("Y",strtotime($data->dateorder)));

	$noo = $getDataDetail->num_rows();

	$sisaOri = 0;

	foreach($getDataDetailOri->result() as $dataDetailOri){

	$sisaOri = $sisaOri + $dataDetailOri->total;	

		$noo++;

		echo"

	<tr>

		<td>$noo</td>

		<td>".$dataDetailOri->nonota."</td>

		<td>".$dataDetailOri->member_name." - ".$dataDetailOri->kota_member."</td>

		<td>".$dataDetailOri->sales_name."</td>

		<td style='text-align: right;'>Rp. ".number_format($dataDetailOri->total,0,',','.')."</td>

		<td style='text-align: right;'>Rp. ".number_format($dataDetailOri->total,0,',','.')."</td>

		<td></td>

	</tr>";

	}

	$totalSemuanya = $sisa + $sisaOri;

	?>

	<tr>

		<td colspan="4" align="right"><h4><b>Sisa Piutang <?php echo date("F Y",strtotime($data->dateorder)); ?></b></h4></td>

		<td align="right"><h4><b>Rp. <?php echo number_format($totalSemuanya,0,',','.'); ?></b></h4></td>

	</tr>

<?php 

$totalSemuanyaa = $totalSemuanyaa + $totalSemuanya;

} ?>

</table>

<table class="table" width="100%">

	<tr>

		<td align="center" colspan="6"><h3><b>GRAND TOTAL</b></h3></td>

		<td align="right"><h3><b>Rp. <?php echo number_format($totalSemuanyaa,0,',','.'); ?></b></h3></td>

		<td></td>

	</tr>

</table>

</body>

</html>