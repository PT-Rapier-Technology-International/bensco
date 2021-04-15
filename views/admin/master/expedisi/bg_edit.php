<?php
echo"
<div class='row'>
	<div class='col-md-12'>

		<!-- Basic legend -->
		<form class='form-horizontal' id='formAdd' name='formAdd'>
			<div class='panel panel-flat'>
				<div class='panel-heading'>
					<h5 class='panel-title'>Edit Expedisi<a class='heading-elements-toggle'><i class='icon-more'></i></a></h5>
					<div class='heading-elements'>
						<ul class='icons-list'>
	                		<li><a data-action='collapse'></a></li>
	                		<li><a data-action='reload'></a></li>
	                		 
	                	</ul>
                	</div>
				</div>

				<div class='panel-body'>
					<fieldset>
						<legend class='text-semibold'>Form Edit Expedisi</legend>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Nama Expedisi</label>
							<div class='col-lg-9'>
                                <input type='text' name='nama' id='nama' class='form-control' palceholder='Masukkan Nama Expedisi' value='".$getData->name."'>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>No Telephone</label>
							<div class='col-lg-9'>
                                <input type='text' name='telp_no' id='telp_no' class='form-control' palceholder='Masukkan No. Telephone' value='".$getData->telp_no."'>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>
							 Alamat</label>
							<div class='col-lg-9'>
                                <textarea name='alamat' id='alamat' class='form-control' palceholder='Masukkan Alamat Expedisi'>".$getData->address."</textarea>
                            </div>
            			</div>
					</fieldset>

					<div class='text-right'>
						<button type='button' class='btn btn-primary' onclick=javascript:simpan_edit_data('expedisi',".$getData->id.")>Simpan Data <i class='icon-arrow-right14 position-right'></i></button>
					</div>
				</div>
			</div>
		</form>
		<!-- /basic legend -->

	</div>
</div>
";
?>