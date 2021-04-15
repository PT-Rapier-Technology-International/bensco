<?php

$jenis_ = $this->uri->segment(7);

$jenis = $this->uri->segment(4);

if($jenis == "excel"){

	header("Content-type: application/vnd-ms-excel");

	header("Content-Disposition: attachment; filename=export_report_po_".date('d_m_y').".xls");

}



$perusahaan = $this->uri->segment(5);

$getPerusahaan = $this->model_master->getPerusahaanByID($_SESSION['rick_auto']['perusahaan'])->row();

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

$singkatan .= substr($kata, 0);

}



$namaptsj = $namapt."".strtoupper($singkatan);

$bulan = strtoupper(date('M'));

$tahun = date('Y');

//$nosj = "".sprintf("%'.03d", $no_suratjalan)."/".$namaptsj."/".$bulan."/".$tahun."";

// untuk no KTP

// $id=substr($no_ktp,-4);

?>

<!DOCTYPE>

<html>

<head>

	<title></title>

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

</head>

<body>

	<p align="center"><font size="10"><b><U>LAPORAN SO <?php echo "(".$namaptsj.")";?></font></U></b>

	<br><?php echo date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['bulan']))); ?>

	</p>

	<div class="table-responsive">

		<br>

	    <table width="100%" border="0" cellspacing="0">

	        <thead>

	            <tr>

	                <th colspan="3" align="left">Customer</th>

	                <th align="center">No. PO</th>

	                <th  align="right">Status</th>

            	</tr>

	        </thead>

	        <tbody><?php

	        	$no = 0;

	        	$grand_total = 0;

	        	foreach($getData->result() as $data){

	        		$no++;

	        		?>

	            <tr>

	            	<td><b><?=$data->nama_member?></b></td>

	            	<td><b><?=$data->kota_member?></b></td>

	            	<td><b>Description</b></td>

	                <td align="left"><b><?=$data->nonota?></b></td>

									<td align="right">
										<?php
										if ($data->status == 0){
											echo "<span class='label label-primary'>BARU</span>";
										} elseif ($data->status_gudang == 0){
											echo "<span class='label label-primary'>DIPROSES</span>";
										} elseif ($data->status_gudang == 1) {
												echo "<span class='label label-primary'>SELESAI</span>";
										} elseif ($data->status_gudang == 2) {
												echo "<span class='label label-primary'>DITOLAK</span>";
										} else {
												echo "<span class='label label-primary'>DIBATALKAN</span>";
										}
									echo"</td>

	            </tr>";$detailData = $this->model_purchase->getPurchaseDetailByPurchase($data->id);

	            $total = 0;



	            foreach($detailData->result() as $detail){

	            	$total = $total + $detail->ttl_price;



	            	echo"

	            	<tr>

	            		<td align='right'>".$detail->qty." ".$detail->nama_satuan."</td>

	            		<td align='left'>".$detail->nama_produk."</td>

	            		<td align='right'>".number_format(round($detail->price),2,',','.')."</td>

	            		<td align='right'>".number_format(round($detail->ttl_price),2,',','.')."</td>

	            	</tr>

	            	";

	            }

	            $grand_total = $grand_total + $total;

	            ?>

	            	<tr>

								<td align="right" colspan="2"><b>Total</b></td>

	            	<td colspan='2' align='right'><b></b></td>

	            	<!-- <td align='right'><b></b></td> -->

	            	<td align='right'><b><?= number_format(round($total),2,',','.') ?></b></td>

	            	</tr>





	           <?php } echo"

		            <hr>

		            	<tr>

		            	<td align='right'><h3><b>GRAND TOTAL</b></h3></td>

		            	<td align='right'></td>

		            	<td align='right'></td>

		            	<td align='right'><h3><b>".number_format(round($grand_total),2,',','.')."</b></h3></td>

		            </tr>

	        ";?>

	        </tbody>

	    </table>

	    <p align='right'><font size='3'><i>Printed date :

		<?php echo date("d M Y") ?> </font></i></p>

	</div>

</body>

</html>
