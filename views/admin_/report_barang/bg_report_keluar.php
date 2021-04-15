<?php
echo"
<div class='form-group has-feedback'>
    <a href='".base_url("admin/invoice/export_barang_keluar/excel")."' target='_blank' class='btn bg-green'><i class=' icon-file-download2 position-left'></i>Export Excel</a> &nbsp; 
    <a href='".base_url("admin/invoice/export_barang_keluar/pdf")."' target='_blank' class='btn btn-danger'><i class='icon-file-download position-left'></i>Export PDF</a> &nbsp;
    <a href='".base_url("admin/invoice/export_barang_keluar/print")."' target='_blank' class='btn btn-primary'><i class='icon-printer position-left'></i>Print</a>
</div>
<center>
	<h3>LAPORAN BARANG KELUAR ".strtoupper($getPerusahaan)." <br> DARI TANGGAL ".date("d/m/Y",strtotime($_SESSION['rick_auto']['tanggalfromrrk']))." - ".date("d/m/Y",strtotime($_SESSION['rick_auto']['tanggaltorrk']))."
</center>
<table class='table'>
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
	";
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
	echo"
</table>
";?>