<?php

echo"

<div class='form-group has-feedback'>

    <a href='".base_url("admin/invoice/export_piutang/excel")."' target='_blank' class='btn bg-green'><i class=' icon-file-download2 position-left'></i>Export Excel</a> &nbsp; 

    <a href='".base_url("admin/invoice/export_piutang/pdf")."' target='_blank' class='btn btn-danger'><i class='icon-file-download position-left'></i>Export PDF</a> &nbsp; 

    <a href='".base_url("admin/invoice/export_piutang/print")."' target='_blank' class='btn btn-primary'><i class='icon-printer position-left'></i>Print</a>

</div>

<center>

	<h3>".$_SESSION['rick_auto']['perusahaannamerp']." <br> Laporan Piutang Per Tanggal ".date("d M Y",strtotime($_SESSION['rick_auto']['tanggalfromrrp']))."</h3>

</center>

";

$totalSemuanyaa = 0;

if($getData->num_rows() > 0){

echo"

<table class='table'>

	<tr>

		<th width='5%'>No.</th>

		<th width='15%'>No. Data</th>

		<th width='15%'>Buyer / Member</th>

		<th width='15%'>Sales</th>

		<th width='15%'>Sisa Tagihan</th>

		<th width='15%'>Total Tagihan</th>

		<th width='15%'>Status</th>

	</tr>


	";
	foreach($getDataLog->result() as $dataLog){

	$getDataDetail = $this->model_invoice->getInvoiceLogByBulanTahun(date("m",strtotime($dataLog->dateorder)), date("Y",strtotime($dataLog->dateorder)));

	$no = 0;

	$sisa = 0;
$sisa2 = 0;
	foreach($getDataDetail->result() as $dataDetail){

		$dataBayar = $this->model_invoice->getPaymentTandaTerima($dataDetail->no_tt)->row();
	if($dataDetail->payment_id == 1 || $dataDetail->payment_id == 3){	
		if($_SESSION['rick_auto']['tanggalfromrrp'] < $dataBayar->liquid_date){
			// echo "filter".$_SESSION['rick_auto']['tanggalfromrrp'];
			// echo "tanggal".$dataBayar->liquid_date;
		$dataBayarr = $this->model_invoice->getTotalPaymentInvoiceByNott($dataDetail->no_tt)->row();

		$dataTotal = $this->model_invoice->getPaymentTandaTerimaAsc($dataDetail->no_tt)->row();

		$no++;

		//if($dataBayar->sisa != 0){

		echo"

	<tr>

		<td>$no</td>

		<td>".$dataDetail->no_tt."</td>

		<td>".wordwrap($dataDetail->member_name,15,"<br>\n")." - ".$dataDetail->kota_member."</td>

		<td>".$dataDetail->sales_name."</td>";

		//$sisa = $sisa + $dataBayar->sudah_dibayar;

		$sisaa = $dataTotal->total_pembayaran - $dataBayarr->total_sudah_dibayar;
		

		//if($dataDetail->nama_pembayaran == "Giro" || $dataDetail->nama_pembayaran == "Cek"){
		if($dataDetail->payment_id == 1 || $dataDetail->payment_id == 3){	
			$sisa = $sisa + $dataDetail->total;
			$s = $dataDetail->total;
			$tanggal_cair = "<br>Tanggal Cair : ".date("d/m/Y",strtotime($dataBayar->liquid_date));

		}else{
			$sisa = $sisa + $dataDetail->sisa;
			$s = $dataDetail->sisa;
			$tanggal_cair = "";
		}
		echo"

		<td align='right'>Rp. ".number_format($s,0,',','.')."</td>

		<td align='right'>Rp. ".number_format($dataDetail->total,0,',','.')."</td>

		<td>".strtoupper($dataDetail->nama_pembayaran)." ".$tanggal_cair."</td>

	</tr>";}}else{

		$dataBayarr = $this->model_invoice->getTotalPaymentInvoiceByNott($dataDetail->no_tt)->row();

		$dataTotal = $this->model_invoice->getPaymentTandaTerimaAsc($dataDetail->no_tt)->row();

		$no++;

		//if($dataBayar->sisa != 0){

		echo"

	<tr>

		<td>$no</td>

		<td>".$dataDetail->no_tt." sss</td>

		<td>".wordwrap($dataDetail->member_name,15,"<br>\n")." - ".$dataDetail->kota_member."</td>

		<td>".$dataDetail->sales_name."</td>";

		//$sisa = $sisa + $dataBayar->sudah_dibayar;

		$sisaa = $dataTotal->total_pembayaran - $dataBayarr->total_sudah_dibayar;
		if($_SESSION['rick_auto']['tanggalfromrrp'] >= $dataDetail->tanggal){
		$GetPiutang = $this->model_invoice->getPaymentPiutangdesc($dataDetail->no_tt)->row();
		}else{
		$GetPiutang = $this->model_invoice->getPaymentPiutangasc($dataDetail->no_tt)->row();	
		}
		

		//if($dataDetail->nama_pembayaran == "Giro" || $dataDetail->nama_pembayaran == "Cek"){
		if($dataDetail->payment_id == 1 || $dataDetail->payment_id == 3){	
			$sisa2 = $sisa + $dataDetail->total;
			$s = $dataDetail->total;
			$tanggal_cair = "<br>Tanggal Cair : ".date("d/m/Y",strtotime($dataBayar->liquid_date));

		}else{
			$sisa2 = $sisa + $dataDetail->sisa;
			$s = $GetPiutang->sisa;
			$tanggal_cair = "";
		}
		echo"

		<td align='right'>Rp. ".number_format($s,0,',','.')."</td>

		<td align='right'>Rp. ".number_format($dataDetail->total,0,',','.')."</td>

		<td>".strtoupper($dataDetail->nama_pembayaran)." ".$tanggal_cair."</td>

	</tr>";		
	}

	}}
	//echo $sisa;
	$totori = 0;
	foreach($getData->result() as $data){

	$getDataDetailOri = $this->model_invoice->getInvoiceOriByBulanTahun(date("m",strtotime($data->dateorder)), date("Y",strtotime($data->dateorder)));

	$noo = $getDataLog->num_rows();

	$sisaOri = 0;

	foreach($getDataDetailOri->result() as $dataDetailOri){

	$sisaOri = $sisaOri + $dataDetailOri->total;	

		$noo++;

		if($dataDetailOri->total > 0){

		echo"

	<tr>

		<td>$noo</td>

		<td>".wordwrap($dataDetailOri->nonota,5,"<br>\n")."</td>

		<td>".$dataDetailOri->member_name." - ".$dataDetailOri->kota_member."</td>

		<td>".$dataDetailOri->sales_name."</td>

		<td align='right'>Rp. ".number_format($dataDetailOri->total,0,',','.')."</td>

		<td align='right'>Rp. ".number_format($dataDetailOri->total,0,',','.')."</td>

		<td></td>

	</tr>";

	}}
$totori = $totori+$sisaOri;}

	//echo "Total Ori :".$totori;



	$totalSemuanya = $sisa + $sisa2 + $totori;

	if($totalSemuanya > 0){

	echo"

	<tr>

		<td colspan='3' align='right'><h4><b>Sisa Piutang ".date("F Y",strtotime($_SESSION['rick_auto']['tanggalfromrrp']))."</b></h4></td>

		<td colspan='3' align='center'><h4><b>Rp. ".number_format($totalSemuanya,0,',','.')."</b></h4></td>

	</tr>";}echo"

</table>";$totalSemuanyaa = $totalSemuanyaa + $totalSemuanya;

}


//foreach($getData->result() as $data){
echo "

<table class='table' width='100%'>

	<tr>

		<td class='text-center' colspan='4'><h3><b>GRAND TOTAL</b></h3></td>

		<td class='text-right'><h3><b>Rp. ".number_format($totalSemuanyaa,0,',','.')."</b></h3></td>

		<td></td>

	</tr>

</table>

";

//}

?>