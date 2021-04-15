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

header("Content-Disposition: attachment; filename=Laporan_Penjualan_".date('d M y').".xls");
}
if($_SESSION['rick_auto']['filter_penjualan_gudang'] == "" || $_SESSION['rick_auto']['filter_penjualan_gudang'] == "0" || $_SESSION['rick_auto']['filter_penjualan_gudang'] == 0){
	$gud = "SEMUA GUDANG";
}else{
	$gudangs = "";
	$getGudang = $this->model_master->getGudangIn($_SESSION['rick_auto']['filter_penjualan_gudang']);
	foreach($getGudang->result() as $gudang){
		$gudangs = $gudangs.$gudang->name.",";
	}
	$gud = $gudangs;
}

if($_SESSION['rick_auto']['filter_penjualan_perusahaan'] == "" || $_SESSION['rick_auto']['filter_penjualan_perusahaan'] == "0" || $_SESSION['rick_auto']['filter_penjualan_perusahaan'] == 0){
	$per = "SEMUA PERUSAHAAN";
}else{
	$getPer = $this->model_master->getPerusahaanByID($_SESSION['rick_auto']['filter_penjualan_perusahaan'])->row();
	$per = $getPer->name;
}

	if($_SESSION['rick_auto']['filter_penjualan_tanggalfrom'] != ""){
	$periode = "Periode ".date("d M Y",strtotime($_SESSION['rick_auto']['filter_penjualan_tanggalfrom']))." - ".date("d M Y",strtotime($_SESSION['rick_auto']['filter_penjualan_tanggalto']))."";
}else{
	$periode = "Periode 01 Jan 2020 - 31 Dec 2020";
}
?>
<table class="table" border="1" width="100%" cellspacing="0">
	<tr>
		<th colspan="5">Penjualan (<?php echo $per; ?>) Gudang : <?php echo $gud; ?> <?php echo $periode; ?></th>
	</tr>
	<tr>
		<th>No</th>
		<th>Stock Code</th>
		<th>Sub Category</th>
		<th>Part No</th>
		<th>Qty Penjualan</th>
	</tr>		
	<?php
		$no = 0;
		foreach($getData->result() as $data){
			$no++;
		echo"
	<tr>
		<td>$no</td>
		<td>".$data->kode_produk."</td>
		<td>".$data->nama_kategori."</td>
		<td>".$data->part_no."</td>
		<td>".$data->qty_kirim." ".$data->satuan."</td>
	</tr>";}?>
</table>
