<?php
$getPerusahaan = $this->model_master->getPerusahaanByID($getPurchase->perusahaan_id)->row();
echo"
	<table width='100%'>
		<tr>
			<td><h2 style='word-spacing: 10px;'><b>No : ".$getPurchase->nonota."</b></h2></td>
			<td align='right'><h3><b>".date("d M Y",strtotime("+0 day", strtotime($getPurchase->dateorder)))."</b></h3></td>

		<tr>
	</table>
	<table width='100%'>
		<tr>
			<td width='50%'>
				<table width='50%' border='1' cellspacing='0'>
					<tr>
						<th>Ditrm O/</th>
						<th>Diserahkan O/</th>
					</tr>
					<tr>
						<td height='60'></td>
						<td></td>
					</tr>
				</table>
			</td>
			<td width='50%' align='right'>
				<table width='100%'>
					<tr>
						<td align='right'><h3>".$getPurchase->nama_member."</h3></td>
					</tr>
					<tr>
						<td align='right'>".$getPurchase->alamat_member."</td>
					</tr>
					<tr>
					";
					$getExp = $this->model_master->getExpedisiById($getPurchase->expedisi)->row();
					echo"
						<td align='right'>Expedisi : ".$getExp->name." </td>
					</tr>
				</table>
			</td>
		<tr>
	</table>
	<h2>GUDANG : ".strtoupper($getPerusahaan->name)." </h2>
	<div class='table-responsive'>
	    <table width='100%' border='1' cellspacing='0'>
	        <thead>
	            <tr>
	                <th>Produk</th>
	                <th>Keterangan</th>
	                <th class='col-sm-1'>Qty Order</th>
	                <th class='col-sm-1'>Qty Kirim</th>
	                ";if($_SESSION['rick_auto']['flag_user'] == 3){echo"
	                <th class='col-sm-1'>Colly</th>
	                <th class='col-sm-1'>Berat</th>";
	                }if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{
	                	echo"
	                <th class='col-sm-1'>Unit Harga</th>
	                <th class='col-sm-1'>Disc</th>
	                <th class='col-sm-1'>Harga Total</th>";}echo"
            	</tr>
	        </thead>
	        <tbody>";
	        	$getPurchaseDetail = $this->model_purchase->getPurchaseDetailByPurchase($getPurchase->id);
	        	$total_pembayaran = 0;
	        	$total_unit = 0;
	        	foreach($getPurchaseDetail->result() as $detailInvoice){
	        		$total_pembayaran = $total_pembayaran + $detailInvoice->ttl_price;
	        		$total_unit = $total_unit + $detailInvoice->price;
	        		echo"
	            <tr>
	                <td>
	                	".$detailInvoice->nama_produk."
                	</td>
                	<td>
	                	".$detailInvoice->deskripsi_produk."
                	</td>
	                <td>".$detailInvoice->qty." ".$detailInvoice->nama_satuan."</td>
	                <td>".$detailInvoice->qty_kirim." ".$detailInvoice->nama_satuan."</td>
	                ";if($_SESSION['rick_auto']['flag_user'] == 3){echo"
	                <td>".$detailInvoice->colly."</td>
	                <td>".$detailInvoice->weight." Kg</td>
	                ";}if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{
	                	echo"
	                <td align='right'>";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo"".number_format($detailInvoice->price,2,',','.')."";}echo"</td>
	                <td>0</td>
	                <td align='right'><span class='text-semibold'>";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo"".number_format($detailInvoice->ttl_price,2,',','.')."";}echo"</span></td>";}echo"
	            </tr>";}echo"
	            ";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{
	                	echo"<!-- <tr>
	            	 <td align='center' rowspan='3' colspan='2'><h3><b>TOTAL PEMBAYARAN</b></h3></td>
	            	<td align='center' colspan='2'><h3>Subtotal</h3></td>
	            	<td align='right'><h3>";if($_SESSION['rick_auto']['flag_user'] == 3){
									}else{echo"Rp. ".number_format($total_unit,2,',','.')."";}echo"</h3></td>
					<td>0</td>
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
	            </tr>-->";}echo"

	        </tbody>
	    </table>
	    <br><br><br>
	    <table border='1' width='40%' cellspacing='0'>
	    		<tr>
	            	<td width='80' align='center'><b>No Inv</b></td>
	            	<td width='80'></td>
	            </tr>
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
		<h3>Note :</h3>
		<p align='right'>Print By : ".$_SESSION['rick_auto']['fullname'].", ".date("d M Y H:i:s",strtotime("+0 day", strtotime(date('Y-m-d H:i:s'))))." </p>
	</div>

	<script>
		window.print();
	</script>
";?>