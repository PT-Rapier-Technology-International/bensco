<?php
echo"
<div class='form-group has-feedback'>
    <a href='".base_url("admin/invoice/export_barang_masuk/excel")."' target='_blank' class='btn bg-green'><i class=' icon-file-download2 position-left'></i>Export Excel</a> &nbsp; 
    <a href='".base_url("admin/invoice/export_barang_masuk/pdf")."' target='_blank' class='btn btn-danger'><i class='icon-file-download position-left'></i>Export PDF</a> &nbsp; 
    <a href='".base_url("admin/invoice/export_barang_masuk/print")."' target='_blank' class='btn btn-primary'><i class='icon-printer position-left'></i>Print</a>
</div>
<center>
	<h3>LAPORAN BARANG MASUK ".strtoupper($getPerusahaan)." <br> DARI TANGGAL ".date("d/m/Y",strtotime($_SESSION['rick_auto']['tanggalfromrrb']))." - ".date("d/m/Y",strtotime($_SESSION['rick_auto']['tanggaltorrb']))."</h3>
</center>
<table class='table'>
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
	";
	$no = 0;
	foreach($getData->result() as $data){
		$no++;
		echo"
	<tr>
		<td>$no</td>
		<td>".date("d/m/Y",strtotime($data->create_date))."</td>
		<td>".$data->transaction_no."</td>";
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
	</tr>";}
	echo"
</table>
";?>