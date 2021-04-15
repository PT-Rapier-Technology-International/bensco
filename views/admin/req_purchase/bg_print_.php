<?php
$getPerusahaan = $this->model_master->getPerusahaanByID($getPurchase->perusahaan_id)->row();
echo"
	<table width='100%'>
		<tr>
			<td width='50%'>
				<table width='100%'>
					<tr>
						<td>".$getPerusahaan->name."</td>
					</tr>
					<tr>
						<td>".$getPerusahaan->address."</td>
					</tr>
					<tr>
						<td>".$getPerusahaan->city."</td>
					</tr>
					<tr>
						<td>".$getPerusahaan->telephone."</td>
					</tr>
				</table>
			</td>
			<td width='50%'>
				<table width='100%'>
					<tr>
							<td align='right'><h3><b>Pesanan #".$getPurchase->nonota."</b></h3></td>
						</tr>
						<tr>
							<td align='right'><h3><b>".date("d M Y",strtotime("+0 day", strtotime($getPurchase->dateorder)))."</b></h3></td>
					</tr>
				</table>
			</td>
		<tr>
	</table>
	<hr>
	<table width='100%'>
		<tr>
			<td width='50%'>
				<table width='100%'>
					<tr>
						<td><h3>Pesanan kepada : <h5>".$getPurchase->nama_member."</h3></h5></td>
					</tr>
					<tr>
						<td><span class='text-semibold'>".$getPurchase->ktp."</span></td>
					</tr>
					<tr>
						<td>".$getPurchase->alamat_member."</td>
					</tr>
					<tr>
						<td>".$getPurchase->phone_member."</td>
					</tr>
					<tr>
						<td>".$getPurchase->email_member."</td>
					</tr>
				</table>
			</td>
			<td align='right' width='50%'>
				<table width='100%'>
						<tr>
							<td align='right'><h3>Total Pembayaran:  ";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo"Rp. ".number_format($getPurchase->total,2,',','.')."";}echo"</h3></td>
						</tr>
						<tr>
							<td align='right'>Nama Bank: <span class='text-semibold'>".$getPerusahaan->bank_name."</span></td>
						</tr>
						<tr>
							<td align='right'>Nomor Rekening: <span>".$getPerusahaan->rek_no."</span></td>
						</tr>
						<tr>
							<td align='right'>Kota: <span>Daerah Khusus Ibukota Jakarta</span></td>
						</tr>
						<tr>
							<td align='right'>Negara: <span>Indonesia</span></td>
						</tr>
				</table>
			</td>
		<tr>
	</table>

	<div class='table-responsive'>
	    <table width='100%' border='1'>getPurchase
	        <thead>
	            <tr>
	                <th>Produk</th>
	                <th class='col-sm-1'>Harga Satuan</th>
	                <th class='col-sm-1'>Qty</th>
	                <th class='col-sm-1'>Harga Total</th>
            	</tr>
	        </thead>
	        <tbody>";
	        	$getPurchaseDetail = $this->model_purchase->getPurchaseDetailByPurchase($getPurchase->id);
	        	$total_pembayaran = 0;
	        	foreach($getPurchaseDetail->result() as $detailInvoice){
	        		$total_pembayaran = $total_pembayaran + $detailInvoice->ttl_price;
	        		echo"
	            <tr>
	                <td>
	                	<h5>".$detailInvoice->nama_produk."</h5>
                	</td>
	                <td align='right'>";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo"".number_format($detailInvoice->price,2,',','.')."";}echo"</td>
	                <td>".$detailInvoice->qty."</td>
	                <td align='right'><span class='text-semibold'>";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo"".number_format($detailInvoice->ttl_price,2,',','.')."";}echo"</span></td>
	            </tr>";}echo"
	            <tr>
	            	<td align='center' rowspan='3'><h3><b>TOTAL PEMBAYARAN</b></h3></td>
	            	<td align='center' colspan='2'><h3>Subtotal</h3></td>
	            	<td align='right'><h3>";if($_SESSION['rick_auto']['flag_user'] == 3){
									}else{echo"Rp. ".number_format($total_pembayaran,2,',','.')."";}echo"</h3></td>
	            </tr>
	            <tr>
	            	
	            	<td align='center' colspan='2'><h3>PPN 10% </h3></td>
	            	<td align='right'><h3>Rp. 0</h3></td>
	            </tr>
	            <tr>
	            	
	            	<td align='center' colspan='2'><h3>TOTAL </h3></td>
	            	<td align='right'><h3>";if($_SESSION['rick_auto']['flag_user'] == 3){
									}else{echo"Rp. ".number_format($total_pembayaran,2,',','.')."";}echo"</h3></td>
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

		<!-- <h6>Other information</h6>
		<p class='text-muted'>Thank you for using Limitless. This invoice can be paid via PayPal, Bank transfer, Skrill or Payoneer. Payment is due within 30 days from the date of delivery. Late payment is possible, but with with a fee of 10% per month. Company registered in England and Wales #6893003, registered office: 3 Goodman Street, London E1 8BF, United Kingdom. Phone number: 888-555-2311</p> -->
	</div>

	<script>
		window.print();
	</script>
";?>