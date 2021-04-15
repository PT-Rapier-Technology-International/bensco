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
				<legend class='text-bold'>Laporan Sisa Hutang</legend>

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
					<label class='control-label col-lg-1'>Pilih Member</label>
					<div class='col-lg-3'>
						<div class='multi-select-full' style='width:200px'>
						    <select id='cmbMember' name='cmbMember' data-placeholder='Pilih Customer' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true'>
                                <option selected disabled>Pilih Customer</option>
                                 ";
                                 foreach($getMember->result() as $member){
                                    echo"<option value='".$member->id."'>".$member->name." - ".$member->city."</option>";
                                 }
                                 echo"
                            </select>
					</div>
					</div>
					<label class='control-label col-lg-1'>Per Tanggal </label>
					<div class='col-lg-2'>
						<input type='date' class='form-control' placeholder='tanggal' id='tanggalFrom' name='tanggalFrom' placholder='Tanggal'>
					</div>
					<div class='col-lg-2'>
						<a href='#!' onclick=javascript:filter_piutang() class='btn btn-primary'>Cari Data</a>
						<!-- <a href='#!' data-toggle='modal' data-target='#modal_pilihan' class='btn btn-primary' onclick='javascript:pilihMenuInv()'>Cari Data</a> -->
					</div>
				</div>
				</div>
			</fieldset>
		</form>
			<div id='div-ajax'>

			</div>
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
	$('#cmbMember').select2({
	});
	</script>
	<script>
		$('#city > option').removeAttr('selected');
		$('#city').trigger('change');
	</script>

";?>
