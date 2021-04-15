<?php
$param_jenis = $this->uri->segment(4);
$jenis = base64_decode($this->uri->segment(5));
$jenis_user = $this->uri->segment(6);
if($jenis_user != ""){
    $jn_user = "user";
}else{
    $jn_user = "sales";
}
echo"
<div class='row'>
	<div class='col-md-12'>

		<!-- Basic legend -->
		<form class='form-horizontal' id='formAdd' name='formAdd'>
			<div class='panel panel-flat'>
				<div class='panel-heading'>
					<h5 class='panel-title'>Edit ".$jenis."<a class='heading-elements-toggle'><i class='icon-more'></i></a></h5>
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
						<legend class='text-semibold'>Form Edit ".$jenis."</legend>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Kode</label>
							<div class='col-lg-9'>
                                <input type='text' name='kode' id='kode' class='form-control' palceholder='Masukkan Kode' value='".$getData->id."' readonly>
                            </div>
            			</div>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Nama Lengkap</label>
							<div class='col-lg-9'>
                                <input type='text' name='fullname' id='fullname' class='form-control' palceholder='Masukkan Nama Lengkap' value='".$getData->name."'>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Username</label>
							<div class='col-lg-9'>
                                <input type='text' name='username' id='username' class='form-control' palceholder='Masukkan Username' value='".$getData->username."' readonly>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Email</label>
							<div class='col-lg-9'>
                                <input type='text' name='email' id='email' class='form-control' palceholder='Masukkan Email' value='".$getData->email."' >
                            </div>
            			</div>
                        <div class='form-group'>
                            <label class='col-lg-3 control-label'>Password</label>
                            <div class='col-lg-9'>
                                <input type='text' name='password' id='password' class='form-control' placeholder='Masukkan password baru jika ingin merubah password' >
                                <input type='hidden' name='password_old' id='password_old' class='form-control' placeholder='Masukkan password baru jika ingin merubah password' value='".$getData->password."' >
                            </div>
                        </div>
                        <div class='form-group'>
                            <label class='col-lg-3 control-label'>Phone</label>
                            <div class='col-lg-9'>
                                <input type='text' name='phone' id='phone' class='form-control' palceholder='Masukkan Hp' value='".$getData->phone."' >
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
                                <input type='text' name='alamat' id='alamat' class='form-control' palceholder='Masukkan Alamat' value='".$getData->address."' >
                            </div>
                        </div>";
                        if($param_jenis == 1){
                        echo"
                        <div class='form-group'>
                            <label class='col-lg-3 control-label'>Alamat Toko</label>
                            <div class='col-lg-9'>
                                <input type='text' name='alamat_toko' id='alamat_toko' class='form-control' palceholder='Masukkan Alamat Toko' value='".$getData->address_toko."'>
                            </div>
                        </div>";
                        }echo"
                        <div class='form-group'>
                            <label class='col-lg-3 control-label'>KTP/NPWP</label>
                            <div class='col-lg-9'>
                                <input type='text' name='ktp' id='ktp' class='form-control' palceholder='Masukkan ktp' value='".$getData->ktp."' >
                            </div>
                        </div>
            			";
            			if($param_jenis == 0){
            				echo"
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Pilih Level User</label>
							<div class='col-lg-9'>
                                <select data-placeholder='Pilih Level User' class='form-control' id='cmbUser' name='cmbUser'>
                                	<option value='1' ";if($getData->flag_user == 1){echo"selected";}echo">Admin</option>
                                    <option value='2' ";if($getData->flag_user == 2){echo"selected";}echo">Admin Invoice</option>
                                    <option value='3' ";if($getData->flag_user == 3){echo"selected";}echo">Admin Gudang</option>
                                    <option value='4' ";if($getData->flag_user == 4){echo"selected";}echo">Admin PO</option>
                                    <option value='5' ";if($getData->flag_user == 5){echo"selected";}echo">Admin Tagihan</option>
                                    <option value='6' ";if($getData->flag_user == 6){echo"selected";}echo">Admin Stock</option>
                                    <option value='7' ";if($getData->flag_user == 7){echo"selected";}echo">Admin Order</option>
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
                                	<option value=".$harga->id." ";if($harga->id == $getData->operation_price){echo"selected";}echo">".$harga->name." %</option>";}
                                	echo"
                                </select>
                            </div>
            			</div>
                        <div class='form-group'>
                            <label class='col-lg-3 control-label'>Kota</label>
                            <div class='col-lg-9'>
                                <select id='cmbCity' name='cmbCity' data-placeholder='Pilih Kota Produk' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true'>
                                ";
                                foreach($getCity->result() as $city){
                                    echo"
                                    <option value='".$city->id."' ";if($city->id == $getData->city_id){echo"selected";}echo">".$city->name."</option> ";
                                }
                                echo"
                                </select>
                            </div>
                        </div>
            				";}else{

            				}
            				echo"
					</fieldset>

					<div class='text-right'>
						<button type='button' class='btn btn-primary' onclick=javascript:simpan_edit_user_data(".$param_jenis.",'".base64_encode($jenis)."','".$jenis."','".$getData->id."','".$jn_user."')>Simpan Data <i class='icon-arrow-right14 position-right'></i></button>
					</div>
				</div>
			</div>
		</form>
		<!-- /basic legend -->

	</div>
</div>
";
?>