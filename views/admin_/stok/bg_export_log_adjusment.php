<?php
	$jenis = $this->uri->segment(4);
	if($jenis == "excel"){
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=Log_Adjustment_".date('d M y').".xls");
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
<table class="table" width="100%" border="1" cellspacing="0">
		<tr>
			<th>#</th>
			<th>Produk</th>
			<th>Perusahaan</th>
			<th>Gudang</th>
			<th>Stock Lama</th>
			<th>Stock Adjusment</th>
			<th>Stock Baru</th>
			<th>Catatan</th>
			<th>Tanggal Adjusment</th>
			<th>Transaksi dibuat</th>
		</tr>
	<?php
	$no = 0;
	foreach($getData->result() as $data){
		$no++;
		echo"
		<tr>
			<td>$no</td>
			<td>".$data->nama_produk."</td>
			<td>".$data->nama_perusahaan."</td>
			<td>".$data->nama_gudang."</td>
			<td>".$data->qty_product." ".$data->nama_satuan."</td>
			<td>".$data->stock_add." ".$data->nama_satuan."</td>";
			$newStok = $data->qty_product + $data->stock_add;
			echo"
			<td>".$newStok."  ".$data->nama_satuan."</td>
			<td>".$data->note."</td>
			<td>".date("d M Y H:i",strtotime("+0 day", strtotime($data->create_date)))."</td>
			<td>".$data->create_user."</td>
		</tr>
		";}?>
</table>
</body>
</html>

	<?php
	if($jenis == "print"){
		echo "	<script>
		window.print();
		window.onfocus=function(){ window.close();}
	</script>";}
	?>