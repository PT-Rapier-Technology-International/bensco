<?php

$uri4 = $this->uri->segment(4);

echo"

<!-- Main content -->

<div class='content-wrapper'>



	<!-- Basic datatable -->

	<div class='panel panel-flat'>

		<div class='panel-heading'>

			<h5 class='panel-title'>Report Rekap Invoice</h5>

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

					<label class='control-label col-lg-2'>Pilih Perusahaan</label>

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

					<div class='col-lg-2'>

						<a href='#!' onclick=javascript:filter_report_rekap_invoice() class='btn btn-primary'>Cari Data</a>

					</div>

				</div>

			</fieldset>

		</form>

		</div>

		<div style='margin-left:1%;display:block' id='btnExport'>

			<a href='".base_url("admin/invoice/print_report_rekap_invoice/print/".$_SESSION['rick_auto']['filter_tanggal_rriss']."")."' target='_blank' class='btn btn-primary btn-labeled'><b><i class='icon-printer'></i></b> Print </a>

			<a href='".base_url("admin/invoice/print_report_rekap_invoice/excel/".$_SESSION['rick_auto']['filter_tanggal_rriss']."")."' target='_blank' class='btn bg-green'><i class=' icon-file-download2 position-left'></i>Export Excel</a>

			<a href='".base_url("admin/invoice/print_report_rekap_invoice/pdf/".$_SESSION['rick_auto']['filter_tanggal_rriss']."")."' target='_blank' class='btn btn-danger'><i class='icon-file-download position-left'></i>Export PDF</a>

		</div>

		<table class='table'>

			<thead>

				<tr>

					<th>#</th>

					<th>No PO</th>

					<th>SO</th>

					<th>No Invoice</th>

					<th>Customer</th>

					<th>Kota</th>

					<th>Ekspedisi</th>

					<th>Total Invoice (Rp.) Setelah PPN</th>

				</tr>

			</thead>

			<tbody id='div-ajax'>";

			$no = 0;

			foreach($getInvoice->result() as $invoice){

				$no++;

				echo"

				<tr>

					<td>$no</td>

					<td>".$invoice->purchase_no."</a></td>";

					$soo = explode("/",$invoice->purchase_no);

					echo"

					<td>".$soo[1]."</a></td>

					<td>".$invoice->nonota."</a></td>

					<td>".$invoice->member_name."</td>

					<td>".$invoice->kota."</td>

					<td>".$invoice->expedisi."</td>

					<td class='text-right'>".number_format($invoice->total,2,',','.')."</td>					

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