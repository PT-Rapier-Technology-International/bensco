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

header("Content-Disposition: attachment; filename=Report_Bo_Qty".date('d M y').".xls");
}
if(isset($_SESSION['rick_auto']['filter_bo_status'])){
	if($_SESSION['rick_auto']['filter_bo_status'] == 1){
		$trbt = "style='display:show'";
		$trt = "style='display:none'";
		$trs = "style='display:none'";
		$trsd = "style='display:none'";
		$trst = "style='display:none'";
		$lblSt = "BELUM TERKIRIM";
	}elseif($_SESSION['rick_auto']['filter_bo_status'] == 2){
		$trbt = "style='display:none'";
		$trt = "style='display:show'";
		$trs = "style='display:none'";
		$trsd = "style='display:none'";
		$trst = "style='display:none'";
		$lblSt = "TERKIRIM";
	}else{
		$trbt = "style='display:show'";
		$trt = "style='display:show'";
		$trs = "style='display:show'";
		$trsd = "style='display:show'";
		$trst = "style='display:show'";
		$lblSt = "SEMUA";
	}
}
?>


<?php if($_SESSION['rick_auto']['filter_bo_tanggalfrom'] != ""){
	?>
<center></h3>LAPORAN BACK ORDER QTY <?php echo date("d M Y",strtotime($_SESSION['rick_auto']['filter_bo_tanggalfrom'])); ?> - <?php echo date("d M Y",strtotime($_SESSION['rick_auto']['filter_bo_tanggalto'])); ?></h3></center>
<?php }else{ ?>
<center></h3>LAPORAN BACK ORDER QTY 01 Jan 2020 - 31 Dec 2020</h3></center>
<?php } ?>
&nbsp; <h3>STATUS : <?php echo $lblSt; ?></h3>
<table border="1" width="100%" cellspacing="0">
	<tr>
		<th>No</th>
		<th>Tanggal</th>
		<th>Nama Barang</th>
		<th>Sub Category</th>
		<th>Qty BO</th>
		<th class="th_status" <?php echo $trs; ?>>Status</th>
	</tr>		
<?php
		$no = 0;
		foreach($getPoBo->result() as $pobo){
			$no++;
?>
	<tr class="tr_belum_terkirim" <?php echo $trbt; ?>>
		<td><?php echo $no; ?></td>
		<td><?php echo date("d M Y",strtotime($pobo->dateorder)); ?></td>
		<td><?php echo $pobo->nama_produk; ?></td>
		<td><?php echo $pobo->nama_kategori; ?></td>
		<td><?php echo $pobo->qty; ?> <?php echo $pobo->nama_satuan; ?></td>
		<td class="td_status" <?php echo $trsd; ?>>Belum Terkirim</td>
	</tr>
	<?php
		}
		$no = $getPoBo->num_rows();
		foreach($getPoBoTerkirim->result() as $po){
			$no++;
		?>
	<tr class="tr_terkirim" <?php echo $trt; ?>>
		<td><?php echo $no; ?></td>
		<td><?php echo date("d M Y",strtotime($po->dateorder)); ?></td>
		<td><?php echo $po->nama_produk; ?></td>
		<td><?php echo $po->nama_kategori; ?></td>
		<td><?php echo $po->qty; ?> <?php echo $po->satuan; ?></td>
		<td class="td_status" <?php echo $trst; ?>>Terkirim</td>
	</tr><?php } ?>
</table>