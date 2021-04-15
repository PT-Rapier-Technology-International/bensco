<?php

echo"
	<center>
		<h3>LAPORAN STOCK OPNAME</h3>
	</center>
	<table width='100%' cellspacing='0'>
		<tr>
			<td width='100%'>
				<table width='100%'>
				<tr>
					<td width='50%'>
						<table width='100%'>
								<tr>
								<td><h4><b>No. Transaksi</b></h4></td>
								<td><h4><b>:</b></h4></td>
								<td align='left'><h4><b>".$getData->notransaction."</b></h4></td>
								</tr>
								<tr>
								<td><h4><b>Nama Perusahaan</b></h4></td>
								<td><h4><b>:</b></h4></td>
								<td align='left'><h4><b>".$getData->nama_perusahaan."</b></h4></td>
								</tr>
								<tr>
									<td><h4><b>Penanggung Jawab SO</b></h4></td>
									<td><h4><b>:</b></h4></td>
									<td align='left'><h4><b>".$getData->pic."</b></h4></td>
								</tr>	
								";
								if($getData->flag_proses == 1){
									echo"
								<tr>
									<td><h4><b>Tanggal Approval</b></h4></td>
									<td><h4><b>:</b></h4></td>
									<td><h4><b>".date("d M y",strtotime("+0 day", strtotime($getData->approve_date)))."</b></h4></td>
								</tr>	";}echo"						
						</table>
					</td>
					<td width='50%'>
						<table width='100%'>
								<tr>
									<td><h4><b>Tanggal Stock Opname</td>
									<td><h4><b>:</td>
									<td><h4><b>".date("d M y",strtotime("+0 day", strtotime($getData->faktur_date)))."</b></h4></td>
								</tr>
								<tr>
									<td><h4><b>Stok Gudang</b></h4></td>
									<td><h4><b>:</b></h4></td>
									<td align='left'><h4><b>".$getData->nama_gudang."</b></h4></td>
								</tr>
								<tr>
									<td><h4><b>Officer SO</b></h4></td>
									<td><h4><b>:</b></h4></td>
									<td><h4><b>".$getData->create_user."</b></h4></td>
								</tr>	";
								if($getData->flag_proses == 1){
									echo"
								<tr>
									<td><h4><b>Officer App SO</b></h4></td>
									<td><h4><b>:</b></h4></td>
									<td><h4><b>".$getData->approve_user."</b></h4></td>
								</tr>	";}echo"
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
	                <th>Nama Produk</th>
	                <th class='col-sm-1'>Stock Awal Gudang</th>
	                <th class='col-sm-1'>Stock Opname</th>
            	</tr>
	        </thead>
	        <tbody>";
	        	foreach($getDataDetail->result() as $detail){
	        		echo"
	            <tr>
	                <td>
	                	<h5>".$detail->nama_produk."</h5>
                	</td>
                	<td align='center'>".$detail->qtyProduk." ".$detail->nama_satuan."</td>
                	<td align='center'>".$detail->qtySO." ".$detail->nama_satuan."</td>
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

		<!-- <h6>Catatan Oleh Gudang</h6>
		<p class='text-muted'>".$getData->note."</p> -->
	</div>

	<script>
		window.print();
	</script>
";?>