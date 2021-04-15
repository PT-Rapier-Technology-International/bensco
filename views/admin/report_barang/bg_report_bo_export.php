<?php
$jenis = $this->uri->segment(4);
if($jenis == "print"){
	echo"
	<script>

		window.print();

	</script>
	";
}elseif($jenis == "excel"){
header("Content-type: application/vnd-ms-excel");

header("Content-Disposition: attachment; filename=Report_Bo_".date('d M y').".xls");
}
if(isset($_SESSION['rick_auto']['filter_bo_status'])){
	if($_SESSION['rick_auto']['filter_bo_status'] == 1){
		$trbt = "style='display:show'";
		$trt = "style='display:none'";
	}elseif($_SESSION['rick_auto']['filter_bo_status'] == 2){
		$trbt = "style='display:none'";
		$trt = "style='display:show'";
	}else{
		$trbt = "style='display:show'";
		$trt = "style='display:show'";
	}
}
?>
<?php if($_SESSION['rick_auto']['filter_bo_tanggalfrom'] != ""){
	?>
<center></h3>LAPORAN BACK ORDER <?php echo date("d M Y",strtotime($_SESSION['rick_auto']['filter_bo_tanggalfrom'])); ?> - <?php echo date("d M Y",strtotime($_SESSION['rick_auto']['filter_bo_tanggalto'])); ?></h3></center>
<?php }else{ ?>
<center></h3>LAPORAN BACK ORDER 01 Jan 2020 - 31 Dec 2020</h3></center>
<?php } ?>
<table width="100%" border="1" cellspacing="0">
	<tr>
		<th>No</th>
		<th>Tanggal</th>
		<th>Nama Toko</th>
		<th>Kota</th>
		<th>Nama Barang</th>
		<th>Sub Category</th>
		<th>Qty BO</th>
		<th>Unit Harga</th>
		<th>List Net</th>
		<th>No. BO/PO</th>
		<th>Status</th>
	</tr>		
	<?php
		$no = 0;
		foreach($getPoBo->result() as $pobo){
			$no++;
		echo"
	<tr class='tr_belum_terkirim' ".$trbt.">
		<td>$no</td>
		<td>".date("d M Y",strtotime($pobo->dateorder))."</td>
		<td>(".$pobo->uniq_code_member.") ".$pobo->nama_member."</td>
		<td>".$pobo->kota_member."</td>
		<td>".$pobo->nama_produk."</td>
		<td>".$pobo->nama_kategori."</td>
		<td>".$pobo->qty." ".$pobo->nama_satuan."</td>
		<td align='right'>".number_format($pobo->price,0,',','.')."</td>
		<td align='right'>".number_format($pobo->ttl_price,0,',','.')."</td>
		<td>".$pobo->notransaction."</td>
		<td>Belum Terkirim</td>
	</tr>";
		}
		$no = $getPoBo->num_rows();
		foreach($getPoBoTerkirim->result() as $po){
			$no++;
		echo"
	<tr class='tr_terkirim' ".$trt.">
		<td>$no</td>
		<td>".date("d M Y",strtotime($po->dateorder))."</td>
		<td>(".$po->uniq_code_member.") ".$po->nama_member."</td>
		<td>".$po->kota_member."</td>
		<td>".$po->nama_produk."</td>
		<td>".$po->nama_kategori."</td>
		<td>".$po->qty." ".$po->satuan."</td>
		<td align='right'>".number_format($po->price,0,',','.')."</td>
		<td align='right'>".number_format($po->ttl_price,0,',','.')."</td>
		<td>".$po->nonota."</td>
		<td>Terkirim</td>
	</tr>";}
	?>
</table>
<?php

?>