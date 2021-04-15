<?php

$getPerusahaan = $this->model_master->getPerusahaanByID($getInvoice->perusahaan_id)->row();

echo"

	<div class='modal-header'>

		<button type='button' class='close' data-dismiss='modal'>&times;</button>

		<h5 class='modal-title'>Invoice #".str_replace("PT.E","PT.ETC",$getInvoice->nonota)."</h5>

	</div>



	<div class='panel-body no-padding-bottom'>

		<div class='row'>

			<div class='col-md-6 content-group'>

				<ul class='list-condensed list-unstyled'>

					<li><h5>".$getPerusahaan->name."</h5></li>

					<li>".$getPerusahaan->address."</li>

					<li>".$getPerusahaan->city."</li>

					<li>".$getPerusahaan->telephone."</li>

				</ul>

			</div>



			<div class='col-md-6 content-group'>

				<div class='invoice-details'>

					<h5 class='text-uppercase text-semibold'>Invoice #".str_replace("PT.E","PT.ETC",$getInvoice->nonota)."</h5>

					<ul class='list-condensed list-unstyled'>

					<li>Date: <span class='text-semibold'>".date("d M Y",strtotime("+0 day", strtotime($getInvoice->dateorder)))."</span></li>

					<!-- <li>Due date: <span class='text-semibold'>May 12, 2015</span></li> -->

				</ul>

				</div>

			</div>

		</div>



		<div class='row'>

			<div class='col-md-6 col-lg-9 content-group'>

				<span class='text-muted'>Invoice To:</span>

					<ul class='list-condensed list-unstyled'>

					<li><h5>".$getInvoice->nama_member." - ".$getInvoice->kota_member."</h5></li>

					<li><span class='text-semibold'>".$getInvoice->ktp."</span></li>

					<li>".$getInvoice->alamat_member_toko."</li>

					<li>(+62".$getInvoice->kode_area.") ".$getInvoice->phone_member."</li>

					<li><a href='#'>".$getInvoice->email_member."</a></li>

				</ul>

			</div>



			<div class='col-md-6 col-lg-3 content-group'>

				<span class='text-muted'>Payment Details:</span>

				<ul class='list-condensed list-unstyled invoice-payment-details'>

					<li><h5>Total Due: <span class='text-right text-semibold'>Rp. ".number_format($getInvoice->total,2,',','.')."</span></h5></li>

					<li>Nama Bank: <span class='text-semibold'>".$getPerusahaan->bank_name."</span></li>

					<li>Nomor Rekening: <span>".$getPerusahaan->rek_no."</span></li>

					<li>Kota: <span>Daerah Khusus Ibukota Jakarta</span></li>

					<li>Negara: <span>Indonesia</span></li>

				</ul>

			</div>

		</div>

	</div>



	<div class='table-responsive'>

	    <table class='table table-lg'>

	        <thead>

	            <tr>

	                <th>Produk</th>

	                <th class='col-sm-1'>Harga Satuan</th>

	                <th class='col-sm-1'>Qty</th>

	                <th class='col-sm-1'>Include Diskon</th>

	                <th class='col-sm-1'>Harga Total</th>

            	</tr>

	        </thead>

	        <tbody>";

	        	$getInvoiceDetail = $this->model_invoice->getInvoiceDetailByInvoiceId($getInvoice->id);

	        	$total_pembayaran = 0;

	        	foreach($getInvoiceDetail->result() as $detailInvoice){

	        		$total_pembayaran = $total_pembayaran + $detailInvoice->ttl_price;
	        		$diskon = round(100 - (($detailInvoice->price / $detailInvoice->netprice) * 100));
					$discpersen = $total_pembayaran / $getInvoice->discount;

					$diskon = 100 / $discpersen;
	        		echo"

	            <tr>

	                <td>

	                	<h6 class='no-margin'>".$detailInvoice->product_name."</h6>

                	</td>

	                <td class='text-right'>".number_format(round($detailInvoice->price),2,',','.')."</td>

	                <td>".$detailInvoice->qty_kirim." ".$detailInvoice->satuan."</td>

	                <!--<td>".$detailInvoice->dsc."%</td>-->

					<td>".floor($diskon)."%</td>

	                <td class='text-right'><span class='text-semibold'>".number_format(round($detailInvoice->ttl_price),2,',','.')."</span></td>

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



			<div class='col-sm-5'>

				<div class='content-group'>

					<h6>Total due</h6>

					<div class='table-responsive no-border'>

						<table class='table'>

							<tbody>

								<tr>

								<th>Subtotal:</th>

								<td class='text-right'>Rp. ".number_format(round($total_pembayaran),2,',','.')."</td>

								</tr>";

								$dpersen = $total_pembayaran / $getInvoice->discount;

								$dpersen2 = 100 / $dpersen;

								$dpersen3 = explode('.',$dpersen2);

								$afterdiskon = $total_pembayaran - $getInvoice->discount;

								echo"

								<tr>

								<th>Diskon ".floor($dpersen2)."%:</th>

								<td class='text-right'>Rp. ".number_format($getInvoice->discount,2,',','.')."</td>

								</tr>

								<th>Total Setelah Diskon:</th>

								<td class='text-right'>Rp. ".number_format(round($afterdiskon),2,',','.')."</td>

								</tr>";

								$ppn = ceil($getInvoice->total_before_ppn * 10 / 100);

            					$grandTotal = $getInvoice->total + $ppn;

								$grandttl = round($afterdiskon + $ppn);

            					echo"

								<tr>

								<th>PPN 10%:</th>

								<td class='text-right'>Rp. ".number_format($ppn,2,',','.')."</td>

								</tr>

								<tr>

									<th>Total:</th>

									<td class='text-right text-primary'><h5 class='text-semibold'>Rp. ".number_format($grandttl,2,',','.')."</h5></td>

								</tr>

							</tbody>

						</table>

					</div>



					<div class='text-right'>

						<a href='".base_url("admin/invoice/print_invoice/".base64_encode($getInvoice->id)."")."' target='_blank' class='btn btn-primary btn-labeled'><b><i class='icon-printer'></i></b> Print invoice</a>

					</div>

				</div>

			</div>

		</div>



		<!-- <h6>Other information</h6>

		<p class='text-muted'>Thank you for using Limitless. This invoice can be paid via PayPal, Bank transfer, Skrill or Payoneer. Payment is due within 30 days from the date of delivery. Late payment is possible, but with with a fee of 10% per month. Company registered in England and Wales #6893003, registered office: 3 Goodman Street, London E1 8BF, United Kingdom. Phone number: 888-555-2311</p> -->

	</div>



	<div class='modal-footer'>

		<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>

	</div>

";?>