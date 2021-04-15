<?php
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
?>			