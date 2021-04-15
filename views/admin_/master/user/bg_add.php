<?php
$param_jenis = $this->uri->segment(4);
$jenis = base64_decode($this->uri->segment(5));
echo"
<div class='row'>
	<div class='col-md-12'>

		<!-- Basic legend -->
		<form class='form-horizontal' id='formAdd' name='formAdd'>
			<div class='panel panel-flat'>
				<div class='panel-heading'>
					<h5 class='panel-title'>Tambah ".$jenis."<a class='heading-elements-toggle'><i class='icon-more'></i></a></h5>
					<div class='heading-elements'>
						<ul class='icons-list'>
	                		<li><a data-action='collapse'></a></li>
	                		<li><a data-action='reload'></a></li>
	                		 
	                	</ul>
                	</div>
				</div>
				<input type='hidden' name='paramJenis' id='paramJenis' class='form-control' value=".$param_jenis.">
				<div class='panel-body'>
					<fieldset>
						<legend class='text-semibold'>Form Tambah ".$jenis."</legend>
						<!-- <div class='form-group'>
							<label class='col-lg-3 control-label'>Kode</label>
							<div class='col-lg-9'>
                                <input type='text' name='kode_' id='kode_' class='form-control' palceholder='Masukkan Kode'>
                            </div>
            			</div> -->
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Nama Lengkap</label>
							<div class='col-lg-9'>
                                <input type='text' name='fullname' id='fullname' class='form-control' palceholder='Masukkan Nama Lengkap'>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Username</label>
							<div class='col-lg-9'>
                                <input type='text' name='username' id='username' class='form-control' palceholder='Masukkan Username'>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Email</label>
							<div class='col-lg-9'>
                                <input type='text' name='email' id='email' class='form-control' palceholder='Masukkan Email'>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Password</label>
							<div class='col-lg-9'>
                                <input type='password' name='password' id='password' class='form-control' palceholder='Masukkan Password'>
                            </div>
            			</div>
                        <div class='form-group'>
                            <label class='col-lg-3 control-label'>No. KTP/NPWP</label>
                            <div class='col-lg-9'>
                                <input type='text' name='ktp' id='ktp' class='form-control' palceholder='Masukkan ktp'>
                            </div>
                        </div>";
                        if($param_jenis == 1){
                            $jen = "NPWP";
                        }else{
                            $jen = "";
                        }echo"
                        <div class='form-group'>
                            <label class='col-lg-3 control-label'>Alamat ".$jen."</label>
                            <div class='col-lg-9'>
                                <input type='text' name='alamat' id='alamat' class='form-control' palceholder='Masukkan Alamat'>
                            </div>
                        </div>";
                        if($param_jenis == 1){
                        echo"
                        <div class='form-group'>
                            <label class='col-lg-3 control-label'>Alamat Toko</label>
                            <div class='col-lg-9'>
                                <input type='text' name='alamat_toko' id='alamat_toko' class='form-control' palceholder='Masukkan Alamat Toko'>
                            </div>
                        </div>";
                        }echo"
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Kota</label>
							<div class='col-lg-9'>
                                <select id='cmbCity' name='cmbCity' data-placeholder='Pilih Kota Produk' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true'>
                                ";
                                foreach($getCity->result() as $city){
                                	echo"
                                    <option value='".$city->id."'>".$city->name."</option> ";
                                }
                                echo"
                                </select>
                            </div>
            			</div>";
            			if($param_jenis == 0){
            				echo"
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Pilih Level User</label>
							<div class='col-lg-9'>
                                <select data-placeholder='Pilih Level User' class='form-control' id='cmbUser' name='cmbUser' onchange=javascript:pilihLevelUser()>
                                	<option value='1'>Admin</option>
                                	<option value='2'>Sales</option>
                                </select>
                            </div>
            			</div>
                        <div class='form-group' id='tmpLevelAdmin'>
                            <label class='col-lg-3 control-label'>Pilih Level Admin</label>
                            <div class='col-lg-9'>
                                <select data-placeholder='Pilih Level Admin' class='form-control' id='cmbAdminLv' name='cmbAdminLv'>
                                    <option value='1'>Admin</option>
                                    <option value='2'>Admin Invoice</option>
                                    <option value='3'>Admin Gudang</option>
                                    <option value='4'>Admin PO</option>
                                    <option value='5'>Admin Tagihan</option>
                                    <option value='6'>Admin Stock</option>
                                    <option value='7'>Admin Order</option>
                                </select>
                            </div>
                        </div>";}elseif($param_jenis == 1){
            				echo"
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Pilih Jenis Harga</label>
							<div class='col-lg-9'>
                                <select data-placeholder='Pilih Jenis Harga' class='form-control' id='cmbHarga' name='cmbHarga'>
                                ";foreach($getHarga->result() as $harga){
                                	echo"
                                	<option value=".$harga->id.">".$harga->name." %</option>";}
                                	echo"
                                </select>
                            </div>
            			</div>
            				";}else{

            				}
            				echo"
                        <div class='form-group' id='tempatInputKode' style='display:none'>
                            <label class='col-lg-3 control-label'>Kode</label>
                            <div class='col-lg-9'>
                                <input type='text' name='kode' id='kode' class='form-control' palceholder='Masukkan Kode'>
                            </div>
                        </div>
					</fieldset>

					<div class='text-right'>
						<button type='button' class='btn btn-primary' onclick=javascript:simpan_user_data(".$param_jenis.",'".base64_encode($jenis)."','".$jenis."')>Simpan Data <i class='icon-arrow-right14 position-right'></i></button>
					</div>
				</div>
			</div>
		</form>
		<!-- /basic legend -->

	</div>
</div>
";
?>