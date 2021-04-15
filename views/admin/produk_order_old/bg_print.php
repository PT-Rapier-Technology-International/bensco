<?php

echo "<p style='font-size:12px'>".date("d/m/yy"). "
	</p>
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
		.tdTop{
			font-size:14px;
		}
		.tdMid{
			font-size:12px;
		}
	</style>

	<h1 style='font-size:20px' align='center'><b>FORM ORDER PRODUK</b></h1>
	

	<table width='100%' cellspacing='0'>

		<tr>

			<td>

				<table width='100%'>

					<tr>

						<td align='left' class='tdTop'>
							<b style='font-size: 17px'>Perusahaan : ".$getDataDetail->row()->nama_perusahaan."</b>
						</td>

					</tr>

					<tr>

						<td style='padding-top: 8px' align='left' class='tdTop'>
							<b style='font-size: 17px'>Gudang : ".$getDataDetail->row()->nama_gudang."</b>
						</td>

					</tr>

					<tr>

						<td style='font-size: 17px; padding-top: 8px' align='left' class='tdMid'>Tanggal Faktur : ".date("d M y",strtotime("+0
							day", strtotime($getData->faktur_date)))."</td>

					</tr>

				</table>

			</td>

			<td style='right:0; float:right;'>

				<table width='100%'>

					<tr>

						<td style='font-size: 17px' align='left' class='tdTop'>
							<b>Faktur No : ".$getData->notransaction."</b>
						</td>

					</tr>

					<tr>

						<td style='font-size: 17px; padding-top: 8px' align='left' class='tdTop'>
							<b>Pabrik : ".$getData->factory_name."</b>
						</td>

					</tr>

					<tr>

						<td style='font-size: 17px; padding-top: 8px' align='left' class='tdMid'>Tanggal Terima Barang : ".date("d M
							y",strtotime("+0 day", strtotime($getData->warehouse_date)))."</td>

					</tr>

				</table>

			</td>

		<tr>

	</table>
	
	<div class='table-responsive'>

		<br>

		<table align='center' width='100%' border='1' cellspacing='0'>

			<thead>

				<tr>

					<th>Produk</th>

					<th class='col-sm-1'>Qty Order</th>

				</tr>

			</thead>

			<tbody>";

				foreach($getDataDetail->result() as $detail){

				echo"

				<tr>

					<td>

						<p style='font-size: 14px'>".$detail->nama_produk."</p>

					</td>

					<td align='center' style='font-size:14px'>".$detail->qty." ".$detail->nama_satuan."</td>

				</tr>";}echo"

			</tbody>

		</table>

	</div>

	<div class='panel-body'>

		<div class='row invoice-payment'>

			<div class='col-sm-7'>

				<!-- <div class='content-group'>

				<h6>Authorized person</h6>

				<div class='mb-15 mt-15'>

					<img src='assets/images/signature.png' class='display-block' style='width: 150px;' alt=''>

				</div>



				<ul class='list-condensed list-unstyled text-muted'>

					<li>Eugene Kopyov</li>

					<li>2269 Elba Lane</li>

					<li>Paris, France</li>

					<li>888-555-2311</li>

				</ul>

			</div> -->

			</div>

		</div>



		<h6>Catatan Oleh Gudang</h6>

		<p class='text-muted'>".$getData->note."</p>

	</div>

	<script>

		window.print();

	</script>

";?>