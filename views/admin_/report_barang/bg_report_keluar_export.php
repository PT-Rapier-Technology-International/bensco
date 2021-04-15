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
	<h3>LAPORAN BARANG KELUAR <?php echo strtoupper($getPerusahaan); ?> <br> DARI TANGGAL <?php echo date("d/m/Y",strtotime($_SESSION['rick_auto']['tanggalfromrrk']));?> - <?php echo date("d/m/Y",strtotime($_SESSION['rick_auto']['tanggaltorrk']));?></h3>
</center>
<table class="table" border="1" cellspacing="0">
	<tr>
		<th>No</th>
		<th>Tanggal Invoice</th>
		<th>No. Invoice</th>
		<th>No. Sales Order</th>
		<th>Gudang</th>
		<th>Nama Buyer / Member</th>
		<th>Kota</th>
		<th>Produk</th>
		<th>QTTY</th>
		<th>Satuan</th>
		<th>Unit Price</th>
	</tr>
	<?php
	$no = 0;
	foreach($getData->result() as $data){
		$no++;
		echo"
	<tr>
		<td>$no</td>
		<td>".date("d/m/Y",strtotime($data->create_date))."</td>";
		if($data->purchase_detail_id == "" || $data->purchase_detail_id == "0" || $data->purchase_detail_id == 0){
			if($data->nonota == ""){
				echo"
				<td>Adjustment</td>";
			}else{
				echo"
				<td>".$data->nonota."</td>";
			}
		}else{
				echo"
		<td>".$data->nonota_nota."</td>";
		}echo"
		<td>".$data->no_purchase."</td>
		<td>".$data->nama_gudang."</td>";
		if($data->purchase_detail_id == "" || $data->purchase_detail_id == "0" || $data->purchase_detail_id == 0){
			echo"
		<td>".$data->nama_member."</td>";
		}else{
			echo"
		<td>".$data->nama_member_po."</td>";
		}if($data->purchase_detail_id == "" || $data->purchase_detail_id == "0" || $data->purchase_detail_id == 0){
			echo"
		<td>".$data->kota."</td>";
		}else{
			echo"
		<td>".$data->kota_po."</td>";
		}echo"
		<td>".$data->nama_produk."</td>
		<td>".$data->stock_input."</td>
		<td>".$data->nama_satuan."</td>
		<td>".number_format($data->harga_satuan_po,0,',','.')."</td>
	</tr>";}
	?>
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