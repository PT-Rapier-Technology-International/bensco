<?php
$perusahaan = $this->uri->segment(5);
$pengiriman = $this->uri->segment(6);
$getPerusahaan = $this->model_master->getPerusahaanByID($perusahaan)->row();
$hari = array ( 1 =>    'Senin',
			'Selasa',
			'Rabu',
			'Kamis',
			'Jumat',
			'Sabtu',
			'Minggu'
		);

// Misal hari ini adalah sabtu
//echo date('N'); // Hasil 6
//echo $hari[ date('N') ];
if($pengiriman == ""){
	$judul = "Tanda Terima";
	$no_tt = $this->uri->segment(4);
}else{
	$judul = "Data Pengiriman";
	$no_tt = "";
}
echo"
<style type='text/css' media='print'>
  @page {
    size: auto;  
    margin: 0;  
  }
</style>
<style>
  body{
    padding-left: 1.3cm;
    padding-right: 1.3cm; 
    padding-top: 1.1cm;
  }
</style>
	<hr>
		
		<table border='0' width='100%' cellspacing='0'>
	    	<tr>
	    		<td align='left'>
	    		<h3>".strtoupper($getPerusahaan->name)."</h3>
	    		</td>
	    		<td align='center'>
	    		<h3>".$no_tt."</h3>
	    		</td>
	    		<td align='right'>
	    			<table>
	    				<tr>
	    					<td align='center'><h3>".$judul."</h3></td>
	    				</tr>
	    			</table>
	    		</td>
	    </table>
	<hr>
	<h3 align='right' style='margin-top:1px;margin-bottom:1px'>".date("d M Y")."</h3>
		<table border='0' width='100%'>
		<tr>
				<td>
					<table width='100%'>
						<tr>
							<td height='10'><p><h5>Nama Customer :</h5><td>
						</tr>
						<tr>
							<td><h3>".strtoupper($getMember->name)."</h3><td>
						</tr>
						<tr>
							<td><h3>".strtoupper($getMember->address_toko)."</h3><td>
						</tr>
						<tr>
							<td><h3>".strtoupper($getMember->city)."</h3><td>
						</tr>
					</table>
				</td>
	    </tr>
		</table>

	<div class='table-responsive'>
	<br>
	    <table width='100%' border='1'>
	        <thead>
	            <tr>
	                <th>No</th>
	                <th>Tertanggal</th>
	                <th class='col-sm-1'>No Invoice</th>
	                <th class='col-sm-1'>Nilai</th>
	                <th class='col-sm-1'>Jumlah</th>
	                <th class='col-sm-1'>Keterangan</th>
            	</tr>
	        </thead>
	        ";
	        if($pengiriman == ""){
	        	echo"
	        <tbody>";
	        	//$getInvoiceDetail = $this->model_invoice->getInvoiceDetailByInvoiceId($getInvoice->id);
	        	$total_pembayaran = 0;
	        	$total_pembayarans = 0;
	        	$no = 0;
	        	foreach($getInvoice->result() as $invoice){
	        		$total_pembayaran = $this->model_invoice->getTotalInvoiceByInvoice($invoice->id_invoice)->row()->total_harga;
	        		//$total_pembayaran = $total_pembayaran + $invoice->total ;
	        		$no++;
	        		echo"
	            <tr>
	            	<td>$no</td>
	                <td>
	                	".date("d M Y",strtotime("+0 day", strtotime($invoice->dateorder)))."
                	</td>
                	<td>
	                	".$invoice->nonota." (".$this->uri->segment(4).")
                	</td>";

                	$total_pembayaran_ppn = $total_pembayaran * 10 / 100;
                	$ppn_total = $total_pembayaran_ppn + $total_pembayaran;
                	$total_pembayarans = $total_pembayarans + $ppn_total;
                	echo"
                	<td align='right'>Rp. ".number_format($ppn_total,2,',','.')."</td>
	                <td align='right'>Rp. ".number_format($ppn_total,2,',','.')."</td>
	                <td></td>
	            </tr>";}echo"
	            <tr>
	            	<td rowspan='6' colspan='5'><h4><b>TOTAL PEMBAYARAN</b></h4></td>
	            	<td align='right'><h4>Rp. ".number_format($total_pembayarans,2,',','.')."</h4></td>
	            </tr>
	        </tbody>";}else{
	        	echo"
	        <tbody>";
	        	//$getInvoiceDetail = $this->model_invoice->getInvoiceDetailByInvoiceId($getInvoice->id);
	        	$total_pembayaran = 0;
	        	$no = 0;
	        	foreach($getInvoice->result() as $invoice){
	        		$total_pembayaran = $total_pembayaran + $invoice->nilai ;
	        		$no++;
	        		echo"
	            <tr>
	            	<td>$no</td>
	                <td>
	                	".date("d M Y",strtotime("+0 day", strtotime($invoice->dateorder)))."
                	</td>
                	<td>
	                	".$invoice->nonota."
                	</td>
                	
                	<td align='right'>Rp. ".number_format($invoice->nilai,2,',','.')."</td>
	                <td align='right'>Rp. ".number_format($invoice->nilai,2,',','.')."</td>
	                <td>
	                Expedisi(No. Resi) : ".$invoice->expedisi."(".$invoice->resi_no.") <br>";
	                $dataTT = $this->model_invoice->getNoTTbyInvoiceId($invoice->id)->row();
	                echo"
	                Tanggal Kirim : ".date("d M Y",strtotime("+0 day", strtotime($dataTT->delivery_date)))."<br>

	                </td>
	            </tr>";}echo"
	            <tr>
	            	<td rowspan='6' colspan='5'><h4><b>TOTAL PEMBAYARAN</b></h4></td>
	            	<td align='right'><h4>Rp. ".number_format($total_pembayaran,2,',','.')."</h4></td>
	            </tr>
	        </tbody>
	        	";
	        }echo"
	    </table>
	    <table border='0' width='100%'>
	    	<tr>
	    		<td align='right'>
	    			<table>
	    				<tr>
	    					<td align='center'><h5>Diterima Oleh</h5></td>
	    				</tr>
	    				<tr>
	    					<td align='center'><h5>".strtoupper($getMember->name)."</h5></td>
	    				</tr>
	    			</table>
	    			<p style='margin-top:60px'> .......................................................... </p>
	    		</td>
	    </table>
	    ";
	    if($pengiriman == ""){
	    	echo"
	    <p size='2'>
	    Memo : </p>";}echo"
	    <p style='font-size:10px'>Keterangan</p>
	    <p style='font-size:10px'>1. PEMBAYARAN DENGAN CEK/GIRO HARUS MENCANTUMKAN NAMA ".strtoupper($getPerusahaan->name)."</p>
	    <p style='font-size:10px'>2. PEMBAYARAN DAPAT DITRANSFER KE A/C ".$getPerusahaan->rek_no." A/N ".strtoupper($getPerusahaan->name)."</p>
	    <p style='font-size:10px'>3. BARANG YANG SUDAH DIBELI TIDAK DAPAT DIKEMBALIKAN</p>
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
		window.onfocus=function(){ window.close();}
	</script>
";?>