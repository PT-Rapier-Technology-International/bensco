<?php
$getPerusahaan = $this->model_master->getPerusahaanByID($getInvoice->perusahaan_id)->row();
$update = $this->db->set('count_print',$getInvoice->count_print + 1)->where('id',$getInvoice->id)->update('invoice');
$detailInvoice = $this->model_invoice->getInvoiceDetailByInvoiceId($getInvoice->id);
if($getInvoice->count_print == 1){
	foreach($detailInvoice->result() as $detailInv){
	    $data_produk = $this->model_produk->getProductByCode($detailInv->product_code)->row();
	    $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
	    //print_r($detailInv->qty_kirim);
	    $pengurangan_stok = $cekStok->stok - $detailInv->qty_kirim;
	  //  echo "".$detailInv->qty_kirim."";
	  $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
	}
}else{
	
}

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
    padding-left: 0.5cm;
    padding-right: 0.5cm; 
    padding-top: 0.3cm;
  }
</style>
	<hr style='margin-bottom:0.3mm'>
	<table width='100%' cellspacing='0'>
	
	<tr>
		<td width='50%'>
		<b style='margin-top:0.3mm; font-size: 25px;'>".strtoupper($getPerusahaan->name)."</b>
		</td>
		<td width='50%'>
		<b style='margin-top:0.3mm; font-size: 15px;'>PACKING LIST</b>
		</td>

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
			<td width='50%'>
				<table width='100%'>
				<tr>
					<td width='50%'>
						<table width='100%'>
								<tr>
									<td align='right'><b style='margin-top:0.3mm; font-size: 15px;'>Expedisi : ".$getInvoice->expedisi."</b></td>
								</tr>
								<tr>
									<td align='right'><b style='margin-top:0.3mm; font-size: 15px;'>Via Expedisi : ".$getInvoice->via_expedisi."</b></td>
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
	                <th style='height: 40px;'>Colly</th>
	                <th style='height: 40px;'>Weight</th>
	                <th style='height: 40px;'>Quantity</th>
	                <th style='height: 40px;' class='col-sm-1'>Description</th>
	                <th style='height: 40px;' class='col-sm-1'>Product</th>
            	</tr>
	        </thead>
	        <tbody>";
	        	$getInvoiceDetail = $this->model_invoice->getInvoiceDetailByInvoiceId($getInvoice->id);
	        	$total_pembayaran = 0;
	        	foreach($getInvoiceDetail->result() as $detailInvoice){
	        		$total_pembayaran = $total_pembayaran + $detailInvoice->ttl_price;
	        		echo"
	            <tr>
	            	<td style='height: 40px;'>".$detailInvoice->colly." - ".$detailInvoice->colly_to."</td>
	            	 <td style='height: 40px;'>".$detailInvoice->weight." Kg</td>
	                <td style='height: 40px;'>".$detailInvoice->qty_kirim." ".$detailInvoice->satuan."</td>
                	<td style='height: 40px;'>".$detailInvoice->deskripsi."</td>
                	<td style='height: 40px;'>
	                	".$detailInvoice->product_name."
                	</td>
	            </tr>";}echo"
	        </tbody>
	    </table>
	    <br><br><br><br><br><br>
	    <p style='font-size:10px'>Print By : ".$_SESSION['rick_auto']['username'].", ".date("d M Y H:i:s",strtotime("+0 day", strtotime(date('Y-m-d H:i:s'))))."</p>
	     <p style='font-size:15px' align='right'>Jakarta, ".date("d M Y",strtotime("+0 day", strtotime(date('Y-m-d'))))."</p>
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