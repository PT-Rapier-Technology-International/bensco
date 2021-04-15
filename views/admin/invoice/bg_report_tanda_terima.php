<?php
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
				<legend class='text-bold'>Laporan Tanda Terima</legend>

				<div class='form-group'>
					<label class='control-label col-lg-1'>Pilih Perusahaan</label>
					<div class='col-lg-2'>
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
					<label class='control-label col-lg-1'>Pilih Sales</label>
					<div class='col-lg-2'>
						<div class='multi-select-full' style='width:150px'>
						<select class='multiselect' multiple='multiple' id='cmbSales' name='cmbSales' onchange=javascript:pilihSales()>
							";
							foreach($getSales->result() as $sales){
								echo"
							<option value='".$sales->id."'>".$sales->name."</option>";}echo"
						</select>
					</div>
					<input type='hidden' id='txtSales' name='txtSales'>
					</div>
					<label class='control-label col-lg-1'>Dari Tanggal </label>
					<div class='col-lg-2'>
						<input type='date' class='form-control' placeholder='tanggal' id='tanggalFrom' name='tanggalFrom' placholder='Tanggal' value='".$_SESSION['rick_auto']['tanggalfromrtt']."'>
					</div>
					<label class='control-label col-lg-1'>Sampai Tanggal </label>
					<div class='col-lg-2'>
						<input type='date' class='form-control' placeholder='tanggal' id='tanggalTo' name='tanggalTo' placholder='Tanggal' value='".$_SESSION['rick_auto']['tanggaltortt']."'>
					</div>
				</div>
				<div class='form-group'>
					<label class='control-label col-lg-1'>Kota </label>
					<div class='col-lg-2'>
					<input type='hidden' id='txtCity' name='txtCity'>
						<!-- <input type='text' class='form-control' placeholder='kota' id='city' name='city' value='".$_SESSION['rick_auto']['cityrtt']."'> -->
					
					<div class='multi-select-full' style='width:150px'>
						<select id='city' name='city' data-placeholder='Pilih Kota' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true'>
							";
							foreach($getCity->result() as $city){
								echo"
							<option value=".$city->id.">".$city->name."</option>";}echo"
						</select>
					</div>
					</div>
					<div class='col-lg-2'>
						<!-- <a href='#!' onclick=javascript:filter_report_tanda_terima() class='btn btn-primary'>Cari Data</a> -->
						<a href='#!' data-toggle='modal' data-target='#modal_pilihan' class='btn btn-primary' onclick='javascript:pilihMenuInv()'>Cari Data</a>
					</div>

				</div>
			</fieldset>
		</form>
		</div>
	</div>
	<script>
	$('#city').select2({
		placeholder: 'Select a customer',
	    multiple: true,
	    allowClear: true,
	});
	</script>
	<script>
		$('#city > option').removeAttr('selected');
		$('#city').trigger('change');
	</script>

";?>