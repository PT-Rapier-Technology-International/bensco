<?php 
echo"
<style type='text/css' media='print'>

  @page {

    size: auto;

    margin: 0;

    margin-left: 0.1cm; 

  }

</style>

<style>

  body{

    padding-left: 1.0cm;

    padding-right: 0.7cm; 

    padding-top: 1.1cm;

  }

</style>
";

$jenis = $this->uri->segment(4);

if($jenis != "pdf"){

	if($jenis == "excel"){

		header("Content-type: application/vnd-ms-excel");

		header("Content-Disposition: attachment; filename=export_retur_revisi_".date('d_m_y').".xls");

	}

	echo date("d/m/Y") . "<br>" . "
	<center>
	";
	//if($_SESSION['rick_auto']['filter_start_date_rrr'] != "1970-01-01" || $_SESSION['rick_auto']['filter_start_date_rrr'] != " "){
	if(isset($_SESSION['rick_auto']['filter_start_date_rrr'] )){
		echo"
	<h3>LAPORAN RETUR DAN REVISIaa ".strtoupper($getData->row()->nama_perusahaan)." <br> DARI TANGGAL ".date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_start_date_rrr'])))." - ".date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_end_date_rrr'])))."</h3>";}else{
		echo"
	<h3>LAPORAN RETUR DAN REVISIbb ".strtoupper($getData->row()->nama_perusahaan)." <br> DARI TANGGAL ".date("d M Y",strtotime("+0 day", strtotime($getDataAsc->create_date)))." - ".date("d M Y",strtotime("+0 day", strtotime($getDataDesc->create_date)))."</h3>";
	}
	echo"

	</center>


			<table border='1' width='100%' cellspacing='0'> 

				<thead>

					<tr>

						<th>#</th>

						<th>No Retur Revisi</th>

						<th>No Invoice</th>

						<th>Customer</th>

						<th>Kode-Nama (Produk)</th>

						<th>Keterangan</th>

					</tr>

				</thead>

				<tbody>";

				$no = 0;

				foreach($getData->result() as $data){

					$no++;

					echo"

					<tr>

						<td style='font-size: 10px;'>$no</td>

						<td style='font-size: 12px;'>".$data->nomor_retur_revisi."</td>

						<td style='font-size: 12px;'>".$data->no_nota."</td>

						<td style='font-size: 14px;'>".$data->nama_member." - ".$data->nama_kota."</td>

						<td style='font-size: 14px;'> ".$data->nama_produk."</td>

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

								$price_status = "Perubahan Harga : ".number_format($data->price_before,0,',','.')." menjadi ".number_format($data->price_change,0,',','.')."";

							}
							}else{
								$price_status = "";
								}echo"

						<td style='font-size: 13px;'>".$qty_status." <br>".$price_status."</td>

						

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

						<th>Kode-Nama (Produk)</th>

						<th>Keterangan</th>

					</tr>

				</thead>

				<tbody><?php

				$no = 0;

				foreach($getData->result() as $data){

					$no++;

					echo"

					<tr>

						<td style='font-size: 10px;'>$no</td>

						<td style='font-size: 12px;'>".$data->nomor_retur_revisi."</td>

						<td style='font-size: 12px;'>".$data->no_nota."</td>

						<td style='font-size: 10px;'>".$data->nama_member." - ".$data->nama_kota."</td>

						<td style='font-size: 10px;'>".$data->kode_produk." - ".$data->nama_produk."</td>

						";

						if($data->qty_before == $data->qty_change){

							$qty_status = "Tidak ada Perubahan Qty";

						}else{

							$qty_status = "Perubahan Qty : ".$data->qty_before." menjadi ".$data->qty_change."";

						}echo"

						

						";if($data->price_before == $data->price_change){

							$price_status = "Tidak ada Perubahan Harga Satuan";

						}else{

							$price_status = "Perubahan Harga : ".number_format($data->price_before,0,',','.')." menjadi ".number_format($data->price_change,0,',','.')."";

						}echo"

						<td>".$qty_status." <br><hr>".$price_status."</td>

						

					</tr>";

				}?>

				</tbody>

			</table>

			<?php	

}

?>