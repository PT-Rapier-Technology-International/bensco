<?php
$uri4 = $this->uri->segment(4);
if($uri4 == "so"){
	$judul_page = "Sales Order";
}else{
	$judul_page = "Purchase Order";
}
echo"
	<div class='panel panel-flat'>
		<div class='panel-heading'>
			<h5 class='panel-title'></h5>
			<div class='heading-elements'>
				<ul class='icons-list'>
            		<li><a data-action='collapse'></a></li>
            		<li><a data-action='reload'></a></li>
            	</ul>
        	</div>
		</div>

		<div class='panel-body'>
		<form class='form-horizontal' action='#'>
			<fieldset class='content-group'>
				<legend class='text-bold'>Print Laporan ".$judul_page."</legend>

				<div class='form-group'>
					<label class='control-label col-lg-2'>Pilih Perusahaan</label>
					<div class='col-lg-3'>
						<select class='form-control' id='cmbPerusahaan' name='cmbPerusahaan'>
						<option value='0' disabled selected>Pilih Perusahaan</option>
						";
						foreach($getPerusahaan->result() as $perusahaan){
							echo"
							<option value=".$perusahaan->id.">".$perusahaan->name."</option>
							";
						}echo"
						</select>
					</div>
					<label class='control-label col-lg-2'>Filer Tanggal</label>
					<!-- <div class='col-lg-3'>
						<select class='form-control' id='cmbBulan' name='cmbBulan'>
						<option value='0' disabled selected>Pilih Bulan</option>
						";
						for($i=1;$i<=12;$i++){
							if($i == 1){
								$i_nama = "Januari";
							}elseif($i == 2){
								$i_nama = "Februari";
							}elseif($i == 3){
								$i_nama = "Maret";
							}elseif($i == 4){
								$i_nama = "April";
							}elseif($i == 5){
								$i_nama = "Mei";
							}elseif($i == 6){
								$i_nama = "Juni";
							}elseif($i == 7){
								$i_nama = "Juli";
							}elseif($i == 8){
								$i_nama = "Agustus";
							}elseif($i == 9){
								$i_nama = "September";
							}elseif($i == 10){
								$i_nama = "Oktober";
							}elseif($i == 11){
								$i_nama = "November";
							}else{
								$i_nama = "Desember";
							}
							echo"
							<option value=".$i.">".$i_nama."</selected>
							";
						}
						echo"
						</select>
					</div> -->
					<div class='col-lg-3'>
						<input type='date' class='form-control' placeholder='tanggal' id='cmbTanggal' name='cmbTanggal' placholder='Tanggal' value='".$_SESSION['rick_auto']['bulan']."'>
					</div>
					<div class='col-lg-2'>
						<a href='#!' data-toggle='modal' data-target='#modal_pilihan' class='btn btn-primary' onclick=javascript:pilihMenuPO('".$uri4."')>Cari Data</a>
					</div>
				</div>
			</fieldset>
		</form>
		</div>
	</div>
";?>