<?php

$uri4 = $this->uri->segment(4);

echo"



<!-- Custom datatables -->

<link href='https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>

<link href='https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css' rel='stylesheet' type='text/css'>

<script type='text/javascript' src='https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js'></script>

<script type='text/javascript' src='https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js'></script>

<script type='text/javascript' src='https://code.jquery.com/jquery-3.3.1.js'></script>

<script type='text/javascript' src='https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js'></script>

<!-- Main content -->

<div class='content-wrapper'>



	<!-- Basic datatable -->

	<div class='panel panel-flat'>

		<div class='panel-heading'>

			<h5 class='panel-title'>Report Retur Revisi</h5>

			<div class='heading-elements'>

				<ul class='icons-list'>

            		<li><a data-action='collapse'></a></li>

            		<li><a data-action='reload'></a></li>

            		 

            	</ul>

        	</div>

		</div>

		<div class='panel-body'>

		<form class='form-horizontal' action='#'>

			<fieldset class='content-group'>

				<legend class='text-bold'></legend>



				<div class='form-group'>

					<label class='control-label col-lg-1'>Pilih Perusahaan</label>

					<div class='col-lg-2'>

						<select class='form-control' id='cmbPerusahaan' name='cmbPerusahaan'>

						<option value='0' disabled selected>Pilih Perusahaan</option>

						";

						foreach($getPerusahaan->result() as $perusahaan){

							echo"

							<option value=".$perusahaan->id.">".$perusahaan->name."</option>

							";

						}echo"

						</select>

					</div>

					<label class='control-label col-lg-1'>Dari Tanggal </label>

					<div class='col-lg-2'>

						<input type='date' class='form-control' placeholder='tanggal' id='tanggalFrom' name='tanggalFrom' placholder='Tanggal'>

					</div>

					<label class='control-label col-lg-1'>Sampai Tanggal </label>

					<div class='col-lg-2'>

						<input type='date' class='form-control' placeholder='tanggal' id='tanggalTo' name='tanggalTo' placholder='Tanggal'>

					</div>

					<label class='control-label col-lg-1'>Transaksi</label>

					<div class='col-lg-2'>

						<select class='form-control' id='cmbTransaksi' name='cmbTransaksi'>

							<option value='0' selected disabled>Pilih Jenis Transaksi</option>

							<option value='RET'>Retur</option>

							<option value='REV'>Revisi</option>

						</select>

					</div>

				</div>

				<div class='form-group'>

				<label class='control-label col-lg-1'>No Retur Revisi </label>

					<div class='col-lg-2'>

						<input type='text' class='form-control' id='no_rr' name='no_rr'>

					</div>

				<div class='col-lg-2'>

						<a href='#!' onclick=javascript:filter_report_retur_revisi() class='btn btn-primary'>Cari Data</a>

					</div>

				</div>

			</fieldset>

		</form>

		</div>

		<div style='margin-left:1%'>

			<a href='".base_url("admin/invoice/print_report_retur_rev/print")."' target='_blank' class='btn btn-primary btn-labeled'><b><i class='icon-printer'></i></b> Print </a>

			<a href='".base_url("admin/invoice/print_report_retur_rev/excel")."' target='_blank' class='btn bg-green'><i class=' icon-file-download2 position-left'></i>Export Excel</a>

			<a href='".base_url("admin/invoice/print_report_retur_rev/pdf")."' target='_blank' class='btn btn-danger'><i class='icon-file-download position-left'></i>Export PDF</a>

		</div>

		<table class='table datatable-basic'> 

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

			<tbody id='div_ajax'>";

			$no = 0;

			foreach($getData->result() as $data){

				$no++;

				echo"

				<tr>

					<td>$no</td>

					<td>".$data->nomor_retur_revisi."</td>

					<td>".$data->no_nota."</td>

					<td>".$data->nama_member." - ".$data->nama_kota."</td>

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

							$price_status = "Perubahan Harga : ".number_format($data->price_before,0,',','.')." menjadi ".number_format($data->price_change,0,',','.')."";

						}

					}else{

								$price_status = "";

					}

					echo"

					<td>".$qty_status." <br>".$price_status."</td>

					

				</tr>";

			}

			echo"

			</tbody>

		</table>

	</div>

	<!-- /basic datatable -->

</div>

<!-- /main content -->

";?>

<script>

$(document).ready(function() {

    $('#example').DataTable( {

        dom: 'Bfrtip',

        buttons: [

            'print'

        ]

    } );

} );

</script>