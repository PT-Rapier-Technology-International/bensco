<?php
$jenis_ = $this->uri->segment(7);
$jenis = $this->uri->segment(4);
if($jenis == "excel"){
	header("Content-type: application/vnd-ms-excel");
	// header("Content-Disposition: attachment; filename=export_report_po_".date('d_m_y').".xls");
	header("Content-Disposition: attachment; filename=export_report_po_".date("d_m_y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['bulan']))).".xls");

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
	<p align='center'><font size='5'><b><U>LAPORAN PO <?php echo "(".$namaptsj.")";?></font></U></b>
	<br><?php echo date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['bulan']))); ?>
	</p>
	<div class='table-responsive'>
	<br>
	    <table width='100%' border='1' cellspacing='0'>
	        <thead>
	            <tr>
	            	<th align='center'>No. PO</th>
	                <th align='center'>Nama Customer</th>
	                <th align='center'>Kota</th>
					<th align='center'>Status</th>
            	</tr>
	        </thead>
	        <tbody><?php
	        	$no = 0;
	        	$grand_total = 0;
	        	foreach($getData->result() as $data){
	        		$no++;
	        		echo"
	            <tr>
	            	<td align='center'>".$data->nonota."</td>
	            	<td align='center'>".$data->nama_member."</td>
	            	<td align='center'>".$data->kota_member."</td>
								<td align='right'>";
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
								"</td>
	            </tr>";}echo"
	        </tbody>";?>
	    </table>

		<table border='0' width='100%'>
	    	<tr>
				<td style='height: 250px; vertical-align: bottom;' align='right'>
		    		<font size='3'><i>Printed date:
					<?php echo date("d M Y") ?> </font></i>
		    	</td>
		    </tr>
	    </table>


	</div>
	<?php
	if($jenis == "print"){
		echo"
		<script>
		window.print();
		window.onfocus=function(){ window.close();}
		</script>";}
	?>
