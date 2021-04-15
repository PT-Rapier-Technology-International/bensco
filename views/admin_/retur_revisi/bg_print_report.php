<?php
$jenis = $this->uri->segment(4);
if($jenis != "pdf"){
	if($jenis == "excel"){
		header("Content-type: application/vnd-ms-excel");
		header("Content-Disposition: attachment; filename=export_retur_revisi_".date('d_m_y').".xls");
	}
	echo"
			<table border='1' width='100%' cellspacing='0'> 
				<thead>
					<tr>
						<th>#</th>
						<th>No Retur Revisi</th>
						<th>No Invoice</th>
						<th>Customer</th>
						<th>Perusahaan</th>
						<th>Kode-Nama (Produk)</th>
						<th>Keterangan Revisi</th>
					</tr>
				</thead>
				<tbody>";
				$no = 0;
				foreach($getData->result() as $data){
					$no++;
					echo"
					<tr>
						<td>$no</td>
						<td>".$data->nomor_retur_revisi."</td>
						<td>".$data->no_nota."</td>
						<td>".$data->nama_member."</td>
						<td>".$data->nama_perusahaan."</td>
						<td>".$data->kode_produk." - ".$data->nama_produk."</td>
						";
						if($data->qty_before == $data->qty_change){
							$qty_status = "Tidak ada Perubahan Qty";
						}else{
							$qty_status = "Perubahan Qty : ".$data->qty_before." menjadi ".$data->qty_change."";
						}echo"
						
						";if($data->price_before == $data->price_change){
							$price_status = "Tidak ada Perubahan Harga Satuan";
						}else{
							$price_status = "Perubahan Harga : ".$data->price_before." menjadi ".$data->price_change."";
						}echo"
						<td>".$qty_status." <br><hr>".$price_status."</td>
						
					</tr>";
				}
				echo"
				</tbody>
			</table>
			";
			if($jenis == "print"){
				echo"
			<script>
			window.print();
			</script>";}echo"
";}else{
?>
			<table border="1" width="100%" cellspacing="0"> 
				<thead>
					<tr>
						<th>#</th>
						<th>No Retur Revisi</th>
						<th>No Invoice</th>
						<th>Customer</th>
						<th>Perusahaan</th>
						<th>Kode-Nama (Produk)</th>
						<th>Keterangan Revisi</th>
					</tr>
				</thead>
				<tbody><?php
				$no = 0;
				foreach($getData->result() as $data){
					$no++;
					echo"
					<tr>
						<td>$no</td>
						<td>".$data->nomor_retur_revisi."</td>
						<td>".$data->no_nota."</td>
						<td>".$data->nama_member."</td>
						<td>".$data->nama_perusahaan."</td>
						<td>".$data->kode_produk." - ".$data->nama_produk."</td>
						";
						if($data->qty_before == $data->qty_change){
							$qty_status = "Tidak ada Perubahan Qty";
						}else{
							$qty_status = "Perubahan Qty : ".$data->qty_before." menjadi ".$data->qty_change."";
						}echo"
						
						";if($data->price_before == $data->price_change){
							$price_status = "Tidak ada Perubahan Harga Satuan";
						}else{
							$price_status = "Perubahan Harga : ".$data->price_before." menjadi ".$data->price_change."";
						}echo"
						<td>".$qty_status." <br><hr>".$price_status."</td>
						
					</tr>";
				}?>
				</tbody>
			</table>
			<?php	
}
?>