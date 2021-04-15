<?php
echo"
<div class='row'>
	<div class='col-md-12'>

		<!-- Basic legend -->
		<form class='form-horizontal' id='formAdd' name='formAdd'>
			<div class='panel panel-flat'>
				<div class='panel-heading'>
					<h5 class='panel-title'>Tambah Satuan<a class='heading-elements-toggle'><i class='icon-more'></i></a></h5>
					<div class='heading-elements'>
						<ul class='icons-list'>
	                		<li><a data-action='collapse'></a></li>
	                		<li><a data-action='reload'></a></li>
	                		 
	                	</ul>
                	</div>
				</div>

				<div class='panel-body'>
					<fieldset>
						<legend class='text-semibold'>Form Tambah Satuan</legend>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Nama Satuan</label>
							<div class='col-lg-9'>
                                <input type='text' name='nama' id='nama' class='form-control' palceholder='Masukkan Nama Satuan'>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Jenis Satuan</label>
							<div class='col-lg-9'>
                                <select name='jenis' id='jenis' class='form-control'>
                                	<option value='0' selected>Pilih Jenis Satuan</option>
                                	<option value='1'>Ecer</option>
                                	<option value='2'>Grosir</option>
                                </select>
                            </div>
            			</div>
					</fieldset>

					<div class='text-right'>
						<button type='button' class='btn btn-primary' onclick=javascript:simpan_data('satuan')>Simpan Data <i class='icon-arrow-right14 position-right'></i></button>
					</div>
				</div>
			</div>
		</form>
		<!-- /basic legend -->

	</div>
</div>
";
?>