<?php
	$jenis = $this->uri->segment(4);
	if($jenis == "excel"){
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=Log_Mutasi_".date('d M y').".xls");
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
			<th>Gudang Lama</th>
			<th>Gudang Baru</th>
			<th>Stock Sebelum Mutasi</th>
			<th>Stock Mutasi</th>
			<th>Stock Setelah Mutasi</th>
			<th>Keterangan</th>
			<th>Tanggal Mutasi</th>
			<th>Dibuat Oleh</th>
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
			<td>".$data->nama_gudang_from."</td>
			<td>".$data->nama_gudang_to."</td>
			<td>".$data->from_stok." ".$data->nama_satuan."</td>
			<td>".$data->to_stok." ".$data->nama_satuan."</td>";
			$newStok = $data->from_stok - $data->to_stok;
			echo"
			<td>".$newStok."  ".$data->nama_satuan."</td>
			<td>Mutasi dari Gudang <b>".$data->nama_gudang_from." (Qty : ".$data->from_stok." ".$data->nama_satuan.")</b> Ke Gudang <b>".$data->nama_gudang_to." (Qty : ".$data->to_stok." ".$data->nama_satuan.")</b> </td>
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