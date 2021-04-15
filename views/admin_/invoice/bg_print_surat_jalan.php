<?php
$jenis = $this->uri->segment(7);
if($jenis == "excel"){
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=SuratJalan_".date('d M y').".xls");
}
$perusahaan = $this->uri->segment(5);
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

$namacut = $getPerusahaan->name;
$namapt = substr($namacut,0,3);
$nama = substr($namacut,4);
$arr = explode(' ', $nama);
$singkatan = "";
foreach($arr as $kata)
{
$singkatan .= substr($kata, 0, 1);
}

$namaptsj = $namapt."".strtoupper($singkatan);
if($getInvoiceSJ->num_rows() > 0){
	$nos = substr($getInvoiceSJ->row()->surat_jalan_no, 0, 3);
	$no_suratjalan = $nos+1;
}else{
	$no_suratjalan = 1;
}
$bulan = strtoupper(date('M'));
$tahun = date('Y');
$nosj = "".sprintf("%'.03d", $no_suratjalan)."/".$namaptsj."/".$bulan."/".$tahun."";
// untuk no KTP
// $id=substr($no_ktp,-4);
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
	<p align='center'><font size='6'><b><U>SURAT JALAN</font></U></b>
	<br>".$getInvoiceRow->nonota."
	</p>
	<table width='100%'>
		<tr>
			<td width='50%'>
			<table width='100%'>
				<tr>
					<td><font size='3'><b>Nama : </b></font></td>
					<td><font size='3'><b>".strtoupper($getMember->name)."</b></td>
				</tr>
				<tr>
					<td><b>Alamat : </b></td>
					<td><b>".strtoupper($getMember->address)."</b></td>
				</tr>
				<tr>
					<td></td>
					<td><b>".strtoupper($getMember->city)."</b></td>
				</tr>
			</table>
			</td>
			<td width='50%' align='right'>
			<table width='100%'>
				<tr>
					<td align='right'></td>
					<td align='right'>Tanggal ".date("d M Y")."</td>
				</tr>
				<tr>
					<td align='right'>Expedisi </td>
					<td align='right'>".$getInvoiceRow->expedisi."</td>
				</tr>
				<tr>
					<td align='right'>Via Expedisi </td>
					<td align='right'>".$getInvoiceRow->via_expedisi."</td>
				</tr>
			</table>
			</td>
		<tr>
	</table>
	<div class='table-responsive'>
	<br>
	    <table width='100%' border='1' cellspacing='0'>
	        <thead>
	            <tr>
	                <th style='height: 40px;'>Qty</th>
	                <th style='height: 40px;' class='col-sm-1'>Keterangan</th>
	                <th style='height: 40px;' class='col-sm-1'>Product</th>
            	</tr>
	        </thead>
	        <tbody>";
	        	$no = 0;
	        	foreach($getInvoice->result() as $invoice){
	        		$insert_rekord = $this->db->set('invoice_id',$invoice->id)->set('surat_jalan_no',$nosj)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('invoice_surat_jalan');
	        		$ket = "".$_SESSION['rick_auto']['username']." telah mencetak data surat jalan No. ".$nosj."";
            		$insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('invoice_id',$invoice->id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('invoice_log');
	        		$no++;
	        		echo"
	            <tr>
	            	<td style='height: 40px; vertical-align: top;'>".$invoice->qty_kirim." ".$invoice->satuan."</td>
	                <td style='height: 40px; vertical-align: top;'>".$invoice->deskripsi."</td>
                	<td style='height: 40px; vertical-align: top;'>".$invoice->product_name."</td>
	            </tr>";}echo"
	        </tbody>
	    </table>
	    <br><br>
	    <table border='0' width='100%'>
	    	<tr>
	    		<td>
	    			<table>
	    				<tr>
	    					<td><h3>Diterima Oleh</h3></td>
	    				</tr>
	    				<tr>
	    					<td><h5>".strtoupper($getMember->name)."</h5></td>
	    				</tr>
	    			</table>
	    		</td>
	    		<td align='right'>
	    			<table>
	    				<tr>
	    					<td align='center'><h3>Hormat Kami</h3></td>
	    				</tr>
	    				<tr>
	    					<td align='center'><h5>".strtoupper($getPerusahaan->name)."</h5></td>
	    				</tr>
	    			</table>
	    		</td>
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

	";
	if($jenis == "print"){
		echo "	<script>
		window.print();
		window.onfocus=function(){ window.close();}
	</script>";}
	echo"
";?>