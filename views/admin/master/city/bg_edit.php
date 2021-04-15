<?php
echo"
<div class='row'>
	<div class='col-md-12'>

		<!-- Basic legend -->
		<form class='form-horizontal' id='formAdd' name='formAdd'>
			<div class='panel panel-flat'>
				<div class='panel-heading'>
					<h5 class='panel-title'>Edit Kota<a class='heading-elements-toggle'><i class='icon-more'></i></a></h5>
					<div class='heading-elements'>
						<ul class='icons-list'>
	                		<li><a data-action='collapse'></a></li>
	                		<li><a data-action='reload'></a></li>
	                		 
	                	</ul>
                	</div>
				</div>

				<div class='panel-body'>
					<fieldset>
						<legend class='text-semibold'>Form Edit Kota</legend>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Pilih Provinsi</label>
							<div class='col-lg-9'>
								<select id='cmbProv' name='cmbProv' data-placeholder='Pilih Kota Produk' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true'>
                                    <option value='0' selected disabled>Pilih Provinsi</option>
                                ";
                                foreach($getProv->result() as $prov){
                                    echo"
                                    <option value='".$prov->id."' ";if($prov->id == $getData->provinsi_id){echo "selected";}else{} echo">".$prov->name."</option> ";
                                }
                                 echo"
                                </select>
                            </div>
            			</div>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Nama Kota</label>
							<div class='col-lg-9'>
                                <input type='text' name='nama' id='nama' class='form-control' palceholder='Masukkan Nama Kota' value='".$getData->name."'>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Kode Area</label>
							<div class='col-lg-9'>
                                <input type='text' name='kode_area' id='kode_area' class='form-control' placeholder='Masukkan Kode Area Kota ex : 021' value='".$getData->area_code."'>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Singkatan Kota</label>
							<div class='col-lg-9'>
                                <input type='text' name='singkatan' id='singkatan' class='form-control' palceholder='Masukkan Singkatan Kota' value='".$getData->abbreviation."'>
                            </div>
            			</div>
					</fieldset>

					<div class='text-right'>
						<button type='button' class='btn btn-primary' onclick=javascript:simpan_edit_data('city',".$getData->id.")>Simpan Data <i class='icon-arrow-right14 position-right'></i></button>
					</div>
				</div>
			</div>
		</form>
		<!-- /basic legend -->

	</div>
</div>
";
?>