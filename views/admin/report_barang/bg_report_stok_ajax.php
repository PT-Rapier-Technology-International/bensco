<?php
if($_SESSION['rick_auto']['filter_stok_gudang'] == "" || $_SESSION['rick_auto']['filter_stok_gudang'] == "0" || $_SESSION['rick_auto']['filter_stok_gudang'] == 0){
	$gud = "SEMUA GUDANG";
}else{
	$gudangs = "";
	$getGudang = $this->model_master->getGudangIn($_SESSION['rick_auto']['filter_stok_gudang']);
	foreach($getGudang->result() as $gudang){
		$gudangs = $gudangs.$gudang->name.",";
	}
	$gud = $gudangs;
}

if($_SESSION['rick_auto']['filter_stok_perusahaan'] == "" || $_SESSION['rick_auto']['filter_stok_perusahaan'] == "0" || $_SESSION['rick_auto']['filter_stok_perusahaan'] == 0){
	$per = "SEMUA PERUSAHAAN";
}else{
	$getPer = $this->model_master->getPerusahaanByID($_SESSION['rick_auto']['filter_stok_perusahaan'])->row();
	$per = $getPer->name;
}

if($_SESSION['rick_auto']['filter_stok_tanggalfrom'] != ""){
	$periode = "Periode ".date("d M Y",strtotime($_SESSION['rick_auto']['filter_stok_tanggalfrom']))." - ".date("d M Y",strtotime($_SESSION['rick_auto']['filter_stok_tanggalto']))."";
}else{
	$periode = "Periode 01 Jan 2020 - 31 Dec 2020";
}

if($_SESSION['rick_auto']['filter_stok_produk'] == ""){
	$kStok = "";
}else{
	$kStok = $_SESSION['rick_auto']['filter_stok_produk'];
}
echo"
<table class='table'>
	<tr>
		<th colspan='8'>History Stok ".$kStok." ".$periode."</th>
	</tr>
	<tr>
		<th>No Transaksi</th>
		<th>No Invoice</th>
		<th>Tanggal</th>
		<th>Stock Code</th>
		<th>Gudang</th>
		<th>Keterangan</th>
		<th>Qty</th>
		<th>Satuan</th>
	</tr>		
	";
		$no = 0;
		$total1 = 0;
		foreach($getDataInv->result() as $dataInv){
			$qty1 = "-".$dataInv->qty_kirim."";
			$total1 = $total1 + $qty1;
			$no++;
		echo"
	<tr>
		<!-- <td>$no</td> -->
		<td>".$dataInv->nonota."</td>
		<td>".$dataInv->purchase_no."</td>
		<td>".date("d M Y",strtotime($dataInv->dateorder))."</td>
		<td>".$dataInv->kode_produk."</td>
		<td>".$dataInv->nama_gudang."</td>
		<td>Data Penjualan</td>
		<td>-".$dataInv->qty_kirim."</td>
		<td>".$dataInv->satuan."</td>
	</tr>";}
	if($total1 < 0){
		$total1o = "(".$total1.")";
	}else{
		$total1o = $total1;
	}
	$total2 = 0;
	$noGr = $getDataInv->num_rows();
		foreach($getDataGr->result() as $dataGr){
			$total2 = $total2 + $dataGr->qty_receive;
			$noGr++;
		echo"
	<tr>
		<!-- <td>$noGr</td> -->
		<td>".$dataGr->nonota."</td>
		<td></td>
		<td>".date("d M Y",strtotime($dataGr->faktur_date))."</td>
		<td>".$dataGr->kode_produk."</td>
		<td>".$dataGr->nama_gudang."</td>
		<td>Good Receive</td>
		<td>".$dataGr->qty_receive."</td>
		<td>".$dataGr->satuan."</td>
	</tr>";}
	if($total2 < 0){
		$total2o = "(".$total2.")";
	}else{
		$total2o = $total2;
	}
	//$noGr = $getDataInv->num_rows();
	$total3 = 0;
		foreach($getDataBmbl->result() as $databmbl){
			$total3 = $total3 + $databmbl->stock_input;
			$noGr++;
		echo"
	<tr>
		<!-- <td>$noGr</td> -->
		<td>".$databmbl->transaction_no."</td>
		<td></td>
		<td>".date("d M Y",strtotime($databmbl->create_date))."</td>
		<td>".$databmbl->kode_produk."</td>
		<td>".$databmbl->nama_gudang."</td>
		<td>".$databmbl->keterangan."</td>
		<td>".$databmbl->stock_input."</td>
		<td>".$databmbl->satuan."</td>
	</tr>";}
	if($total3 < 0){
		$total3o = "(".$total3.")";
	}else{
		$total3o = $total3;
	}
	//$totals1 = "(".$total1 + $total2.")";
	$totals2 = $total1 + $total2 + $total3;

	$totalbefore1 = $getDataInvTotal->totalQtyKirim + $getDataGrTotal->totalQtyReceive + $getDataBmBl->totalStockInput;

	$thnBefore = date('Y') - 1;
	echo"
	<tr>
		<td colspan='6' style='align:right'><h3>Stok ".$kStok." ".$periode."</h3></td>
		<td><h3>".$totals2."</h3></td>
		<td></td>
	</tr>
	<tr>
	<td colspan='8'><h3>SUMMARY ".$kStok."</h3></td>
	</tr>
	<tr>
		<td colspan='6' style='align:right'><h3>STOCK CUT OFF SAMPAI TANGGAL 31 DESEMBER ".$thnBefore."</h3></td>
		<td> <h3>".$totalbefore1." </h3></td>
		<td></td>
	</tr>
	<tr>
		<td colspan='6' style='align:right'><h3>Stok ".$kStok." ".$periode."</h3></td>
		<td><h3>".$totals2."</h3></td>
		<td></td>
	</tr>";
	$totalsemuaa = $totalbefore1 + $totals2;
	echo"
	<tr>
		<td colspan='6' style='align:right'><h3>Total Stok ".$kStok." ".$periode."</h3></td>
		<td><h3>".$totalsemuaa."</h3></td>
		<td></td>
	</tr>
</table>
";

?>