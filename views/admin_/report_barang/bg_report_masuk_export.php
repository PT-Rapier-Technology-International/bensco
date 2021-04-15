<?php
$jenis = $this->uri->segment(4);
if($jenis == "excel"){
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=export_barang_masuk_".date('d_m_y').".xls");
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
	<h3>LAPORAN BARANG MASUK <?php echo strtoupper($getPerusahaan); ?> <br> DARI TANGGAL <?php echo date("d/m/Y",strtotime($_SESSION['rick_auto']['tanggalfromrrb']));?> - <?php echo date("d/m/Y",strtotime($_SESSION['rick_auto']['tanggaltorrb']));?></h3>
</center>
<br>
<table class="table" border="1" cellspacing="0">
	<tr>
		<th>No</th>
		<th>Tanggal Masuk</th>
		<th>No. Purchase Order</th>
		<th>Nama Supplier</th>
		<th>Produk</th>
		<th>Gudang</th>
		<th colspan='2' class='text-center'>Qty</th>
		<th>Keterangan</th>
	</tr>
	<?php
	$no = 0;
	foreach($getData->result() as $data){
		$no++;
		echo"
	<tr>
		<td>$no</td>
		<td>".date("d/m/Y",strtotime($data->create_date))."</td>
		";
		if($data->notransaction == ""){
			echo"
		<td>-</td>";
		}else{
			echo"
		<td>".$data->notransaction."</td>";
		}
		if($data->factory_name == ""){
			echo"
		<td>-</td>";
		}else{
			echo"
		<td>".$data->factory_name."</td>";
		}echo"
		<td>".$data->nama_produk."</td>
		<td>".$data->nama_gudang."</td>
		<td>".$data->stock_input."</td>
		<td>".$data->nama_satuan."</td>
		<td>".$data->note."</td>

	</tr>";}?>
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
