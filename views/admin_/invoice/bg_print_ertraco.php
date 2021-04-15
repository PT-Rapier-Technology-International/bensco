<?php
$getPerusahaan = $this->model_master->getPerusahaanByID($getInvoice->perusahaan_id)->row();
$update = $this->db->set('count_print',$getInvoice->count_print + 1)->where('id',$getInvoice->id)->update('invoice');
$detailInvoice = $this->model_invoice->getInvoiceDetailByInvoiceId($getInvoice->id);
//$changeTanggal = $this->uri->segment(5);
//$changeTanggal = $getInvoice->dateprint
$changeTanggal = date('Y-m-d H:i:s');
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
    margin-left: 0.1cm; 
  }
</style>
<style>
  body{
    padding-left: 1.3cm;
    padding-right: 1.3cm; 
    padding-top: 1.1cm;
  }
</style>
	<table width='100%' cellspacing='0'>
		<td width='65%'><b style='margin-top:0.3mm; font-size: 25px;'>".strtoupper($getPerusahaan->name)."</b></td>
		<td><b style='margin-top:0.3mm; font-size: 15px;'>Faktur No : ".$getInvoice->nonota."</b><br>";
		$num = date('N',strtotime($changeTanggal)); 
		echo"
		<b style='margin-top:0.3mm; font-size: 15px;'>Hari / Tgl : ".$hari[$num].", ".date("d M Y",strtotime("+0 day", strtotime($changeTanggal)))."</b></td>
		<td></td>
	</table>
	<hr style='margin-top:1px'>
	<table width='100%' cellspacing='0'>
		<tr>
			<td width='50%' style='height: 2px; vertical-align: top;'>
				<table width='100%'>
				<tr>
					<td width='50%' style='height: 2px; vertical-align: top;'>
						<table width='100%'>
								<tr>
									<td style='height: 2px; vertical-align: top;'><b>Kepada Yth :</b></td>
								</tr>
								<tr>
									<td style='height: 2px; vertical-align: top;'><b>".strtoupper($getInvoice->member_name)."</b></td>
								</tr>
								<tr>
									<td style='height: 2px; vertical-align: top;'><b>".$getInvoice->alamat_member."</b></td>
								</tr>
								<tr>
									<td style='height: 2px; vertical-align: top;'><b>".$getInvoice->kota_member."</b></td>
								</tr>
						</table>
					</td>
				<tr>
				</table>
			<td>
			<td width='15%' style='height: 2px; vertical-align: top;'>
			</td>
			<td width='35%' style='height: 2px; vertical-align: top;'>
				<table width='100%'>
				<tr>
					<td width='50%' style='height: 2px; vertical-align: top;'>
						<table width='100%'>
								<tr>
									<td style='height: 2px; vertical-align: top;' align='left'><b><b>Expedisi : ".$getInvoice->expedisi."</b></b></td>
								</tr>
								<tr>
									<td style='height: 2px; vertical-align: top;' align='left'><b><b>Via Expedisi : ".$getInvoice->via_expedisi."</b></b></td>
								</tr>
						</table>
					</td>
				<tr>
				</table>
			</td>
		</tr>
	<div class='table-responsive'>
	    <table width='100%' border='1' cellspacing='0' style='margin-top:4px'>
	        <thead>
	            <tr>
	                <th style='height: 40px;'>Produk</th>
	                <th style='height: 40px;'>Keterangan</th>
	                <th style='height: 40px;' class='col-sm-1'>Qty</th>
	                <th style='height: 40px;' class='col-sm-1'>Harga / Unit</th>
	                <th style='height: 40px;' class='col-sm-1'>Harga Total</th>
            	</tr>
	        </thead>
	        <tbody>";
	        	$getInvoiceDetail = $this->model_invoice->getInvoiceDetailByInvoiceId($getInvoice->id);
	        	$total_pembayaran = 0;
	        	foreach($getInvoiceDetail->result() as $detailInvoice){
	        		$total_pembayaran = $total_pembayaran + $detailInvoice->ttl_price;
	        		echo"
	            <tr>
	                <td style='height: 40px;'>
	                	<b>".$detailInvoice->product_name."</b>
                	</td>
                	<td style='height: 40px;'>".$detailInvoice->deskripsi."</td>
	                <td style='height: 40px;'>".$detailInvoice->qty_kirim." ".$detailInvoice->satuan."</td>
	                <td style='height: 40px;' align='right'>".number_format($detailInvoice->price,2,',','.')."</td>
	                <td style='height: 40px;' align='right'><span class='text-semibold'>".number_format($detailInvoice->ttl_price,2,',','.')."</span></td>
	            </tr>";}echo"
	        </tbody>
	    </table>
	    <br>
	    <table width='100%' border='0' cellspacing='0'>
            <tr>
            	<td style='height: 25px; vertical-align: center;' rowspan='7'><b><b>TOTAL PEMBAYARAN</b></b></td>
            	<td style='height: 25px; vertical-align: top;' colspan='3'><b>JUMLAH ORDER</b></td>
            	<td style='height: 25px; vertical-align: top;' align='right'><b>Rp. ".number_format($total_pembayaran,2,',','.')."</b></td>
            </tr>";
            $ppn = $total_pembayaran * 10 / 100;
            $grandTotal = $total_pembayaran + $ppn;
            echo"
            <tr>
            	<td style='height: 25px; vertical-align: top;' colspan='3'><b>PPN 10% </b></td>
            	<td style='height: 25px; vertical-align: top;' align='right'><b>Rp. ".number_format($ppn,2,',','.')."</b></td>
            </tr>
            <tr>
            	<td style='height: 25px; vertical-align: top;' colspan='3'><b>EXPEDISI </b></td>
            	<td style='height: 25px; vertical-align: top;' align='right'><b>Rp. 0</b></td>
            </tr>
            <tr>
            	<td style='height: 25px; vertical-align: top;' colspan='3'><b>LAIN-LAIN </b></td>
            	<td style='height: 25px; vertical-align: top;' align='right'><b>Rp. 0</b></td>
            </tr>
            <tr>
            	<td style='height: 25px; vertical-align: top;' colspan='3'><b>BIAYA ASURANSI </b></td>
            	<td style='height: 25px; vertical-align: top;' align='right'><b>Rp. 0</b></td>
            </tr>
            <tr>
            	<td style='height: 25px; vertical-align: top;' colspan='4'><hr width='100%' style='border-top: 3px dashed black;'></td>
            	<td></td>
            </tr>
            <tr>
            	<td style='height: 25px; vertical-align: top;' colspan='3'><b>TOTAL JUMLAH FAKTUR </b></td>
            	<td style='height: 25px; vertical-align: top;' align='right'><b>Rp. ".number_format($grandTotal,2,',','.')."</b></td>
            </tr>
	    </table>
	    <br><br><br>
	    <table border='0' width='100%'>
	    	<tr>
	    		<td>
	    			<table width='90%'>
	    				<tr>
							<td style='height: 2px; vertical-align: top;'><b>Nama</b></td>
							<td style='height: 2px; vertical-align: top;'><b>:</b></td>
							<td style='height: 2px; vertical-align: top;'><b><span class='text-semibold'>".strtoupper($getPerusahaan->name)."</b></span></td>
						</tr>
						<tr>
							<td style='height: 2px; vertical-align: top;'><b>A/C</b></td>
							<td style='height: 2px; vertical-align: top;'><b>:</b></td>
							<td style='height: 2px; vertical-align: top;'><b><span>".$getPerusahaan->rek_no."</b></span></td>
						</tr>
						<tr>
							<td style='height: 2px; vertical-align: top;'><b>Bank</b></td>
							<td style='height: 2px; vertical-align: top;'><b>:</b></td>
							<td style='height: 2px; vertical-align: top;'><b><span class='text-semibold'>".$getPerusahaan->bank_name."</span></b></td>
						</tr>
						
					</table>
	    		</td>
	    		<td align='right'>
	    			<table>
	    				<tr>
	    					<td align='center' style='height: 2px; vertical-align: top;'><b>Diterima Oleh</b></td>
	    				</tr>
	    				<tr>
	    					<td align='center' style='height: 2px; vertical-align: top;'><b>".strtoupper($getInvoice->member_name)."</b></td>
	    				</tr>
	    			</table>
	    			<p style='margin-top:100px'> .......................................................... </p>
	    		</td>
	    </table>
	    <table border='0' width='100%'>
	    	<tr>
	    		<td>
				    <p size='2'>
				    Memo : </p>
				    <p style='font-size:10px'>Keterangan</p>
				    <p style='font-size:10px'>1. PEMBAYARAN DENGAN CEK/GIRO HARUS MENCANTUMKAN NAMA ".strtoupper($getPerusahaan->name)."</p>
				    <p style='font-size:10px'>2. PEMBAYARAN DAPAT DITRANSFER KE A/C ".$getPerusahaan->rek_no." A/N ".strtoupper($getPerusahaan->name)."</p>
				    <p style='font-size:10px'>3. BARANG YANG SUDAH DIBELI TIDAK DAPAT DIKEMBALIKAN</p>
				</td>
		    	<td>
		    		<p style='font-size:10px'>".$hari[$num].", ".date("d F Y",strtotime("+0 day", strtotime($getInvoice->dateorder)))."</p>
		    	</td>
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

		<!-- <h6>Other information</h6>
		<p class='text-muted'>Thank you for using Limitless. This invoice can be paid via PayPal, Bank transfer, Skrill or Payoneer. Payment is due within 30 days from the date of delivery. Late payment is possible, but with with a fee of 10% per month. Company registered in England and Wales #6893003, registered office: 3 Goodman Street, London E1 8BF, United Kingdom. Phone number: 888-555-2311</p> -->
	</div>
	<script>
		window.print();
	</script>
";?>