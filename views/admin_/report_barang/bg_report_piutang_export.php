<?php
$jenis = $this->uri->segment(4);
if($jenis == "excel"){
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=export_piutang_".date('d_m_y').".xls");
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
	<h3><?php echo $_SESSION['rick_auto']['perusahaannamerp'];?> <br> Laporan Per Tanggal <?php echo date("d M ",strtotime($_SESSION['rick_auto']['tanggalfromrrp']));?> <?php echo date("Y",strtotime($_SESSION['rick_auto']['tanggalfromrrp']));?> </h3>
</center>
<?php
$totalSemuanyaa = 0;
foreach($getData->result() as $data){
	?>
<table border="1" width="100%" cellspacing="0">
	<tr>
		<th width="5px">No</th>
		<th width="20%">No. Inv</th>
		<th width="20%">Buyer / Member</th>
		<th width="50px">Sales</th>
		<th width="50px">Sisa Tagihan</th>
		<th width="50px">Total Tagihan</th>
		<th width="50px">Status</th>
	</tr>
<?php
	$getDataDetail = $this->model_invoice->getInvoiceByBulanTahun(date("m",strtotime($data->dateorder)), date("Y",strtotime($data->dateorder)));
	$no = 0;
	$sisa = 0;
	foreach($getDataDetail->result() as $dataDetail){
		$dataTotal = $this->model_invoice->getPaymentTandaTerimaAsc($dataDetail->no_tt)->row();
		$dataBayar = $this->model_invoice->getPaymentTandaTerima($dataDetail->no_tt)->row();

		$no++;
		echo"
	<tr>
		<td style='width:10%'>$no</td>
		<td style='width:10%'>".$dataDetail->no_tt."</td>
		<td style='width:10%'>".$dataDetail->member_name." - ".$dataDetail->kota_member."</td>
		<td style='width:10%'>".$dataDetail->sales_name."</td>";
		
		$sisa = $sisa + $dataBayar->sisa;
		echo"
		<td align='right'>Rp. ".number_format($dataBayar->sisa,0,',','.')."</td>
		<td align='right'>Rp. ".number_format($dataTotal->total_pembayaran,0,',','.')."</td>
		<td style='width:10%'>".$dataBayar->jenis_pembayaran."</td>
	</tr>";}
	$getDataDetailOri = $this->model_invoice->getInvoiceOriByBulanTahun(date("m",strtotime($data->dateorder)), date("Y",strtotime($data->dateorder)));
	$noo = $getDataDetail->num_rows();
	$sisaOri = 0;
	foreach($getDataDetailOri->result() as $dataDetailOri){
	$sisaOri = $sisaOri + $dataDetailOri->total;	
		$noo++;
		echo"
	<tr>
		<td style='width:10%'>$noo</td>
		<td style='width:10%'>".$dataDetailOri->nonota."</td>
		<td style='width:10%'>".$dataDetailOri->member_name." - ".$dataDetailOri->kota_member."</td>
		<td style='width:10%'>".$dataDetailOri->sales_name."</td>
		<td align='right'>Rp. ".number_format($dataDetailOri->total,0,',','.')."</td>
		<td align='right'>Rp. ".number_format($dataDetailOri->total,0,',','.')."</td>
		<td style='width:10%'></td>
	</tr>";
	}
	$totalSemuanya = $sisa + $sisaOri;
	?>
	<?php
	echo"
	<tr>
		<td colspan='4' align='right'><b>Sisa Piutang ".date("F Y",strtotime($data->dateorder))."</b></td>
		<td align='right'><b>Rp. ".number_format($totalSemuanya,0,',','.')."</b></td>
		<td></td>
	</tr>";
	$totalSemuanyaa = $totalSemuanyaa + $totalSemuanya;
	echo"
	";}
?>
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

<?php
if($jenis == "print"){
	echo"
	<script>
		window.print();
	</script>
	";
}?>