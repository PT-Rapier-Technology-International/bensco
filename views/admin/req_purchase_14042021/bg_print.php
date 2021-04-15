<?php
$jen = $this->uri->segment(5);
if($jen != "pdf"){
	if($jen == "print"){
		echo"
		<script>

			window.print();

		</script>";

	}else{
		header("Content-type: application/vnd-ms-excel");

		header("Content-Disposition: attachment; filename=Print_Purchase_".date('d M y').".xls");
	}
}

$getPerusahaan = $this->model_master->getPerusahaanByID($getPurchase->perusahaan_id)->row();

$getPurchaseDetail = $this->model_purchase->getReqPurchaseDetailByPurchase($getPurchase->id);


echo"

<style type='text/css' media='print'>

  @page {

    size: auto;  

    margin: 0;  

  }

</style>

<style>

  body{

    padding-left: 0.8cm;

    padding-right: 0.8cm; 

    padding-top: 0.3cm;

  }

</style>

	<table width='95%'>

		<tr>

			<td><b style='font-size: 25px;'>No : ".$getPurchase->notransaction."</b></td>

			<td align='Right'><h4><b style='font-size: 15px;'>".date("d M Y",strtotime("+0 day", strtotime($getPurchase->dateorder)))."</b></h4></td>
		</tr>

	</table>

	<table width='100%'>

		<tr>

			<td width='30%'>

				<table width='100%' border='0' cellspacing='0'>

					<tr>
						<td><b style='font-size: 15px;'>Sales</b></td>
						<td><b style='font-size: 15px;'>:</b></td>
						<td style='width: 50%; vertical-align: top;'><b style='font-size: 15px;'>".$getPurchase->nama_sales."</b></td>

					</tr>

					<tr>
						<td><b style='font-size: 15px;'>Tanggal</b></td>
						<td><b style='font-size: 15px;'>:</b></td>
						<td style='width: 50%; vertical-align: top;'><b style='font-size: 15px;'>".date("d M Y",strtotime("+0 day", strtotime($getPurchase->dateorder)))."</b></td>

					</tr>

					<tr>
						<td><b style='font-size: 15px;'>Jam</b></td>
						<td><b style='font-size: 15px;'>:</b></td>
						<td style='width: 50%; vertical-align: top;'><b style='font-size: 15px;'>".date("G:i:s",strtotime("+0 day", strtotime($getPurchase->dateorder)))."</b></td>

					</tr>

				</table>

			</td>

			<td width='20%'>



			</td>

			<td width='50%' align='Left'>

				<table width='90%'>

					<tr>

						<td align='right'><b style='font-size: 14px;'>".$getPurchase->nama_member."</b></td>

					</tr>

					<tr>

						<td align='right' style='font-size: 14px;'>".$getPurchase->alamat_member."</td>

					</tr>

					<tr>

						<td align='right' style='font-size: 14px;'>".$getPurchase->kota_member."</td>

					</tr>

					<tr>";

					$getExp = $this->model_master->getExpedisiById($getPurchase->expedisi)->row();

					echo"

						<td align='right' style='font-size: 14px;'>Expedisi : ".$getExp->name." </td>

					</tr>

					<tr>";

					$getSubExp = $this->model_master->getViaExpedisiById($getPurchase->expedisi_via)->row();

					if($getPurchase->expedisi_via == 0){

					echo"
						<td align='right' style='font-size: 14px;'></td>
						";

					}else{

					echo"

						<td align='right' style='font-size: 14px;'>Via Expedisi : ".$getSubExp->name." </td>";

						}echo"

					</tr>

				</table>

			</td>

		</tr>

	</table>

	<!-- <b>PERUSAHAAN : ".strtoupper($getPerusahaan->name)."</b> -->

	<div class='table-responsive'>

		<br>

	    <table width='100%' border='1' cellspacing='0'>

	        <thead>

	            <tr>

	                <th>Produk</th>

	                <th>Ket</th>

	                <th class='col-sm-1' align='center'>Qty</th>

	                <th class='col-sm-1'>Unit Harga</th>

	                <th class='col-sm-1'>Disc</th>

	                <th class='col-sm-1'>Harga Total</th>

            	</tr>

	        </thead>

	        <tbody>";

	        	

	        	$total_pembayaran = 0;

	        	$total_unit = 0;

	        	foreach($getPurchaseDetail->result() as $detailInvoice){

	        		// $total_pembayaran = $total_pembayaran + $detailInvoice->ttl_price;

	        		$total_unit = $total_unit + $detailInvoice->price;

	        		$total_hrg = $detailInvoice->qty * $detailInvoice->price;

	        		$total_pembayaran = $total_pembayaran + $total_hrg;

	        		echo"

	            <tr>

	                <td style='font-size: 13px;'>

	                	".$detailInvoice->nama_produk."

                	</td>

                	<td style='font-size: 13px;'>

	                	".$detailInvoice->deskripsi_produk."

                	</td>

	                <td align='center' style='font-size: 13px;'>".$detailInvoice->qty." ".$detailInvoice->nama_satuan."</td>

	                <td align='right' style='font-size: 13px;' >".number_format($detailInvoice->price,0,',','.')."</td>

	                <td align='center' style='font-size: 13px;' >".$detailInvoice->discount." %</td>

	                <!--<td align='right' style='font-size: 13px;' ><span class='text-semibold'>".number_format($detailInvoice->ttl_price,0,',','.')."</span></td>-->

	                <td align='right' style='font-size: 13px;' ><span class='text-semibold'>".number_format($total_hrg,0,',','.')."</span></td>

	            </tr>";}echo"

	            <tr>

	            	

	            	<td align='right' colspan='5'><H4>GRAND TOTAL </H4></td>

	            	<td align='right'><H4>Rp. ".number_format($total_pembayaran,0,',','.')."</H4></td>

	            </tr>



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



		<br>

		<h3>Note : ".$getPurchase->note."</h3>

		<p style='font-size: 12px; padding-left:430px; '>Print By : ".$_SESSION['rick_auto']['fullname'].", ".date("d M Y H:i:s",strtotime("+0 day", strtotime(date('Y-m-d H:i:s'))))." </p>

	</div>




";?>