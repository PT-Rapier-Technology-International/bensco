<?php
echo"
<div class='row'>
	<div class='col-md-12'>

		<!-- Basic legend -->
		<form class='form-horizontal' id='formAdd' name='formAdd'>
			<div class='panel panel-flat'>
				<div class='panel-heading'>
					<h5 class='panel-title'>Edit Provinsi<a class='heading-elements-toggle'><i class='icon-more'></i></a></h5>
					<div class='heading-elements'>
						<ul class='icons-list'>
	                		<li><a data-action='collapse'></a></li>
	                		<li><a data-action='reload'></a></li>
	                		 
	                	</ul>
                	</div>
				</div>

				<div class='panel-body'>
					<fieldset>
						<legend class='text-semibold'>Form Edit Provinsi</legend>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Pilih Negara</label>
							<div class='col-lg-9'>
								<select id='cmbCountry' name='cmbCountry' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true'  onchange=javascript:changeNegara()>
                                    <option value='0' selected disabled>Pilih Negara</option>
                                ";
                                foreach($getCountry->result() as $country){
                                    echo"
                                    <option value='".$country->id."' ";if($country->id == $getData->country_id){echo "selected";}else{} echo">".$country->name."</option> ";
                                }
                                echo"
                                </select>
                            </div>
            			</div>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Nama Provinsi</label>
							<div class='col-lg-9'>
                                <input type='text' name='nama' id='nama' class='form-control' palceholder='Masukkan Nama Provinsi' value='".$getData->name."'>
                            </div>
            			</div>
					</fieldset>

					<div class='text-right'>
						<button type='button' class='btn btn-primary' onclick=javascript:simpan_edit_data('provinsi',".$getData->id.")>Simpan Data <i class='icon-arrow-right14 position-right'></i></button>
					</div>
				</div>
			</div>
		</form>
		<!-- /basic legend -->

	</div>
</div>
";
?>