<?php
echo"
<div class='form-group has-feedback'>
    <a href='".base_url("admin/invoice/export_piutang/excel")."' target='_blank' class='btn bg-green'><i class=' icon-file-download2 position-left'></i>Export Excel</a> &nbsp; 
    <a href='".base_url("admin/invoice/export_piutang/pdf")."' target='_blank' class='btn btn-danger'><i class='icon-file-download position-left'></i>Export PDF</a> &nbsp; 
    <a href='".base_url("admin/invoice/export_piutang/print")."' target='_blank' class='btn btn-primary'><i class='icon-printer position-left'></i>Print</a>
</div>
<center>
	<h3>".$_SESSION['rick_auto']['perusahaannamerp']." <br> Laporan Per Tanggal ".date("d M Y",strtotime($_SESSION['rick_auto']['tanggalfromrrp']))."</h3>
</center>
";
$totalSemuanyaa = 0;
if($getData->num_rows() > 0){
foreach($getData->result() as $data){
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
	$getDataDetail = $this->model_invoice->getInvoiceByBulanTahun(date("m",strtotime($data->dateorder)), date("Y",strtotime($data->dateorder)));
	$no = 0;
	$sisa = 0;
	foreach($getDataDetail->result() as $dataDetail){
		$dataBayar = $this->model_invoice->getPaymentTandaTerima($dataDetail->no_tt)->row();
		$dataBayarr = $this->model_invoice->getTotalPaymentInvoiceByNott($dataDetail->no_tt)->row();
		$dataTotal = $this->model_invoice->getPaymentTandaTerimaAsc($dataDetail->no_tt)->row();
		$no++;
		if($dataBayar->sisa != 0){
		echo"
	<tr>
		<td>$no</td>
		<td>".$dataDetail->no_tt."</td>
		<td>".wordwrap($dataDetail->member_name,15,"<br>\n")." - ".$dataDetail->kota_member."</td>
		<td>".$dataDetail->sales_name."</td>";
		$sisa = $sisa + $dataBayar->sudah_dibayar;
		$sisaa = $dataTotal->total_pembayaran - $dataBayarr->total_sudah_dibayar;
		echo"
		<td align='right'>Rp. ".number_format($sisaa,0,',','.')."</td>
		<td align='right'>Rp. ".number_format($dataTotal->total_pembayaran,0,',','.')."</td>
		<td>".$dataBayar->jenis_pembayaran."</td>
	</tr>";}}
	$getDataDetailOri = $this->model_invoice->getInvoiceOriByBulanTahun(date("m",strtotime($data->dateorder)), date("Y",strtotime($data->dateorder)));
	$noo = $getDataDetail->num_rows();
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

	$totalSemuanya = $sisa + $sisaOri;
	if($totalSemuanya > 0){
	echo"
	<tr>
		<td colspan='4' align='right'><h4><b>Sisa Piutang ".date("F Y",strtotime($data->dateorder))."</b></h4></td>
		<td align='right'><h4><b>Rp. ".number_format($totalSemuanya,0,',','.')."</b></h4></td>
	</tr>";}echo"
</table>";$totalSemuanyaa = $totalSemuanyaa + $totalSemuanya;
}

echo "
<table class='table' width='100%'>
	<tr>
		<td class='text-center' colspan='4'><h3><b>GRAND TOTAL</b></h3></td>
		<td class='text-right'><h3><b>Rp. ".number_format($totalSemuanyaa,0,',','.')."</b></h3></td>
		<td></td>
	</tr>
</table>
";
}
?>