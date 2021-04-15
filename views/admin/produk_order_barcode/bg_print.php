<?php

echo"
	<table width='100%' cellspacing='0'>
		<tr>
			<td width='50%'>
				<table width='100%'>
				<tr>
					<td width='50%'>
						<table width='100%'>
								<tr>
									<td align='left'><h5><b>Perusahaan : ".$getDataDetail->row()->nama_perusahaan."</b></h5></td>
								</tr>
								<tr>
									<td align='left'><h5><b>Gudang : ".$getDataDetail->row()->nama_gudang."</b></h5></td>
								</tr>
								<tr>
									<td>Tanggal Faktur : ".date("d M y",strtotime("+0 day", strtotime($getData->faktur_date)))."</td>
								</tr>
						</table>
					</td>
					<td width='50%'>
						<table width='100%'>
								<tr>
									<td align='right'><h5><b>Faktur No : ".$getData->notransaction."</b></h5></td>
								</tr>
								<tr>
									<td align='right'><h5><b>Pabrik : ".$getData->factory_name."</b></h5></td>
								</tr>
								<tr>
									<td align='right'>Tanggal Terima Barang : ".date("d M y",strtotime("+0 day", strtotime($getData->warehouse_date)))."</td>
								</tr>
						</table>
					</td>
				<tr>
				</table>
			</td>
		</tr>

	<div class='table-responsive'>
	<br>
	    <table width='100%' border='1' cellspacing='0'>
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
	                	<h5>".$detail->nama_produk."</h5>
                	</td>
                	<td align='right'>".$detail->qty." ".$detail->nama_satuan."</td>
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