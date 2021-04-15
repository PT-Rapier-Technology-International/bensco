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
			$j = explode("/", $data->nomor_retur_revisi);
					if($j[2] == "RET"){
						if($data->qty_before == $data->qty_change){
							$qty_status = "Tidak ada Perubahan Qty";
						}else{
							$qty_status = "Perubahan Qty : ".$data->qty_before." menjadi ".$data->qty_change."";
						}
					}else{
						$qty_status = "";
					}echo"
					
					";
					if($j[2] == "REV"){
						if($data->price_before == $data->price_change){
							$price_status = "Tidak ada Perubahan Harga Satuan";
						}else{
							$price_status = "Perubahan Harga : ".$data->price_before." menjadi ".$data->price_change."";
						}
					}else{
								$price_status = "";
					}
					echo"
					<td>".$qty_status." <br>".$price_status."</td>
			
		</tr>";
	}
?>			