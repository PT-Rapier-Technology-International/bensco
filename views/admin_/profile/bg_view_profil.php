<?php
echo"
<div class='row'>
	<div class='col-md-12'>

		<!-- Basic legend -->
		<form class='form-horizontal' id='formAdd' name='formAdd'>
			<div class='panel panel-flat'>
				<div class='panel-heading'>
					<h5 class='panel-title'>Data Profil<a class='heading-elements-toggle'><i class='icon-more'></i></a></h5>
					<div class='heading-elements'>
						<ul class='icons-list'>
	                		<li><a data-action='collapse'></a></li>
	                		<li><a data-action='reload'></a></li>
	                		 
	                	</ul>
                	</div>
				</div>

				<div class='panel-body'>
					<fieldset>
						<legend class='text-semibold'>Form Data Profil</legend>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Username</label>
							<div class='col-lg-9'>
                                <input type='text' name='username' id='username' class='form-control' value='".$data->username."' disabled>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Email</label>
							<div class='col-lg-9'>
                                <input type='text' name='email' id='email' class='form-control' value='".$data->email."' disabled>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Nama Lengkap</label>
							<div class='col-lg-9'>
                                <input type='text' name='fullname' id='fullname' class='form-control' value='".$data->fullname."' disabled>
                            </div>
            			</div>
					</fieldset>

					<!-- <div class='text-right'>
						<button type='button' class='btn btn-primary' onclick=javascript:simpan_data('satuan')>Simpan Data <i class='icon-arrow-right14 position-right'></i></button>
					</div> -->
				</div>
			</div>
		</form>
		<!-- /basic legend -->

	</div>
</div>
";
?>