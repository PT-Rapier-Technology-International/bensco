<?php
$getPerusahaan = $this->model_master->getPerusahaanByID($getInvoice->perusahaan_id)->row();
$update = $this->db->set('count_print',$getInvoice->count_print + 1)->where('id',$getInvoice->id)->update('invoice');
$detailInvoice = $this->model_invoice->getInvoiceDetailByInvoiceId($getInvoice->id);
// if($getInvoice->count_print == 1){
// 	foreach($detailInvoice->result() as $detailInv){
// 	    $data_produk = $this->model_produk->getProductByCode($detailInv->product_code)->row();
// 	    $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
// 	    //print_r($detailInv->qty_kirim);
// 	    $pengurangan_stok = $cekStok->stok - $detailInv->qty_kirim;
// 	  //  echo "".$detailInv->qty_kirim."";
// 	  $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
// 	}
// }else{
	
// }

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
		<h4>".strtoupper($getPerusahaan->name)."</h4>
	<hr>
	<table width='100%' cellspacing='0'>
		<tr>
			<td width='50%'>
				<p><h5>Kepada Yth :</h5></p>
				<p><h5>".strtoupper($getInvoice->member_name)."</p>
				<p>".$getInvoice->alamat_member."</p>
			<td>
			<td width='50%'>
				<table width='100%'>
				<tr>
					<td width='50%'>
						<table width='100%'>
								<tr>
									<td align='right'><h5><b>Faktur No : ".$getInvoice->nonota."</b></h5></td>
								</tr>
								<tr>
									";
									$num = date('N',strtotime($getInvoice->dateorder)); 
									// echo $num; // Hasil 7
									// echo $hari[$num]; // Hasil: Minggu
									echo"
									<td align='right'><h5><b>Hari/Tgl : ".$hari[$num].",".date("d M Y",strtotime("+0 day", strtotime($getInvoice->dateorder)))."</b></h5></td>
								</tr>
								<tr>
									<td align='right'><h5><b>Expedisi : ".$getInvoice->expedisi."</b></h5></td>
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
	                <th>Keterangan</th>
	                <th class='col-sm-1'>Qty</th>
	                <th class='col-sm-1'>Harga / Unit</th>
	                <th class='col-sm-1'>Harga Total</th>
            	</tr>
	        </thead>
	        <tbody>";
	        	$getInvoiceDetail = $this->model_invoice->getInvoiceDetailByInvoiceId($getInvoice->id);
	        	$total_pembayaran = 0;
	        	foreach($getInvoiceDetail->result() as $detailInvoice){
	        		$total_pembayaran = $total_pembayaran + $detailInvoice->ttl_price;
	        		echo"
	            <tr>
	                <td>
	                	<h5>".$detailInvoice->product_name."</h5>
                	</td>
                	<td>".$detailInvoice->deskripsi."</td>
	                <td>".$detailInvoice->qty_kirim." ".$detailInvoice->satuan."</td>
	                <td align='right'>".number_format($detailInvoice->price,2,',','.')."</td>
	                <td align='right'><span class='text-semibold'>".number_format($detailInvoice->ttl_price,2,',','.')."</span></td>
	            </tr>";}echo"
	        </tbody>
	    </table>
	    <table width='100%' border='0' cellspacing='0'>
            <tr>
            	<td rowspan='6'><h5><b>TOTAL PEMBAYARAN</b></h5></td>
            	<td colspan='3'><h5>JUMLAH ORDER</h5></td>
            	<td align='right'><h5>Rp. ".number_format($total_pembayaran,2,',','.')."</h5></td>
            </tr>";
            $ppn = $total_pembayaran * 10 / 100;
            $grandTotal = $total_pembayaran + $ppn;
            echo"
            <tr>
            	<td colspan='3'><h5>PPN 10% </h5></td>
            	<td align='right'><h5>Rp. ".number_format($ppn,2,',','.')."</h5></td>
            </tr>
            <tr>
            	<td colspan='3'><h5>EXPEDISI </h5></td>
            	<td align='right'><h5>Rp. 0</h5></td>
            </tr>
            <tr>
            	<td colspan='3'><h5>LAIN-LAIN </h5></td>
            	<td align='right'><h5>Rp. 0</h5></td>
            </tr>
            <tr>
            	<td colspan='3'><h5>BIAYA ASURANSI </h5></td>
            	<td align='right'><h5>Rp. 0</h5></td>
            </tr>
            <tr>
            	
            	<td colspan='3'><h5>TOTAL JUMLAH FAKTUR </h5></td>
            	<td align='right'><h5>Rp. ".number_format($grandTotal,2,',','.')."</h5></td>
            </tr>
	    </table>
	    <table border='0' width='100%'>
	    	<tr>
	    		<td>
	    			<table width='70%'>
	    				<tr>
							<td><h5>Nama</h5></td>
							<td><h5>:</h5></td>
							<td><h5><span class='text-semibold'>".strtoupper($getPerusahaan->name)."</h5></span></td>
						</tr>
						<tr>
							<td><h5>A/C</h5></td>
							<td><h5>:</h5></td>
							<td><h5><span>".$getPerusahaan->rek_no."</h5></span></td>
						</tr>
						<tr>
							<td><h5>Bank</h5></td>
							<td><h5>:</h5></td>
							<td><h5><span class='text-semibold'>".$getPerusahaan->bank_name."</span></td>
						</tr>
						
					</table>
	    		</td>
	    		<td align='right'>
	    			<table>
	    				<tr>
	    					<td align='center'><h5>Diterima Oleh</h5></td>
	    				</tr>
	    				<tr>
	    					<td align='center'><h5>".strtoupper($getInvoice->member_name)."</h5></td>
	    				</tr>
	    			</table>
	    			<p style='margin-top:60px'> .......................................................... </p>
	    		</td>
	    </table>
	    <p size='2'>
	    Memo : </p>
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
	</script>
";?>