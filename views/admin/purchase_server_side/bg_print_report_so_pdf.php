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
$singkatan .= substr($kata, 0, 1);
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
	<p align="center"><font size="10"><b><U>LAPORAN PO <?php echo "(".$namaptsj.")";?></font></U></b>
	<br><?php echo date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['bulan']))); ?>
	</p>
	<div class="table-responsive">
		<br>
	    <table width="100%" border="1" cellspacing="0">
	        <thead>
	            <tr>
	                <th align="center"><b>No. PO</b></th>
	                <th align="center"><b>Nama Customer</b></th>
	                <th align="center"><b>Kota</b></th>
            	</tr>
	        </thead>
	        <tbody><?php
	        	$no = 0;
	        	$grand_total = 0;
	        	foreach($getData->result() as $data){
	        		$no++;
	        	?>
	            <tr>
	            	<td align="center"><?php echo $data->nonota; ?></td>
	            	<td align="center"><?php echo $data->nama_member; ?></td>
	            	<td align="center"><?php echo $data->kota_member; ?></td>
	            </tr>
	        <?php } ?>
	        </tbody>
	    </table>
	    <font size='3'><i>Printed date : 
		<?php echo date("d M Y") ?> </font></i>
	</div>
</body>
</html>
