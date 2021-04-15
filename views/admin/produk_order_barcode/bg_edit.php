<?php
$id = $this->uri->segment(4);
echo"
<!-- Main content -->
<div class='content-wrapper'>

	<!-- Media library -->
	<form id='formAdd'>
	<div class='panel panel-white'>
		<div class='panel-heading'>
			<h6 class='panel-title text-semibold'>Form Edit Order Barang</h6>
			<div class='heading-elements'>
				<ul class='icons-list'>
            		<li><a data-action='collapse'></a></li>
            		<li><a data-action='reload'></a></li>
            	</ul>
        	</div>
		</div>
		<div class='col-sm-12'>
            <div class='panel-body'>
                <div class='row'>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>No Faktur Pabrik : </label>
                                <input type='text' class='form-control' id='noFakturPabrik' name='noFakturPabrik' value='".$getData->notransaction."' readonly>
                                <input type='hidden' class='form-control' id='idBeli' name='idBeli' value='".base64_decode($id)."' readonly>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Pabrik : </label>
                                <input type='text' class='form-control' id='namaPabrik' name='namaPabrik' value='".$getData->factory_name."'>
                        </div>
                    </div>
                </div>  
                <div class='row'>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Perusahaan : </label>
                                <select class='form-control' id='cmbPerusahaan' name='cmbPerusahaan' onchange=javascript:pilihPerusahaanOB()>
                                <option value='0' disabled selected>Pilih Perusahaan</option>
                                ";foreach($getPerusahaan->result() as $perusahaans){
                                echo"
                                    <option value='".$perusahaans->id."' ";if($perusahaans->id == $getDataDetail->row()->perusahaan_id){echo"selected";}echo">".$perusahaans->name."</option>
                                ";
                                }echo"
                            </select>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Gudang : </label>
                                <select class='form-control' id='cmbGudang' name='cmbGudang'>
                                ";
                                $Data = $this->model_master->getGudangbyPerusahaan($getDataDetail->row()->perusahaan_id);
                                foreach($Data->result() as $data){
                                    echo"
                                    <option value=".$data->id_gudang." ";if($data->id_gudang == $getDataDetail->row()->gudang_id){echo"selected";}echo">".$data->nama_gudang."</option>
                                    ";
                                }echo"
                            </select>
                        </div>

                    </div>
                </div>  
                <div class='row'>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Tanggal Faktur : </label>
                                <input type='date' class='form-control' id='tglFaktur' name='tglFaktur' value='".date("Y-m-d",strtotime("+0 day", strtotime($getData->faktur_date)))."'>
                        </div>
                    </div>
                    ";
                    if($_SESSION['rick_auto']['flag_user'] == 6 || $_SESSION['rick_auto']['flag_user'] == 1){
                        echo"
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Tanggal Sampai : </label>
                                <input type='date' class='form-control' id='tglSampaiGudang' name='tglSampaiGudang' value='".date("Y-m-d",strtotime("+0 day", strtotime($getData->warehouse_date)))."'>
                        </div>
                    </div>";}echo"
                </div>";
                if($_SESSION['rick_auto']['flag_user'] == 6 || $_SESSION['rick_auto']['flag_user'] == 1){
                    echo"
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='form-group'>
                            <label>Catatan : </label>
                                <textarea id='txtNote' name='txtNote' class='form-control' style='width:100%'></textarea>
                        </div>
                    </div>
                </div>  ";}echo"
            </div>
		</div>
        ";
        foreach($getDataDetail->result() as $detail){
            echo"
    	<div class='col-md-12'>
            <!-- colmd2 untuk yng lama -->
    		<div class='col-md-4' style='background-color:#c5c5c5'>
				<div class='form-group'>
					<label>Produk: </label>
                	<select id='cmbProdukEdit_".$detail->id."' name='cmbProdukEdit_".$detail->id."' data-placeholder='Pilih Produk' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true' onchange=javascript:pilihProdukOrder(1)>
                		<option value='0' selected disabled>Pilih Produk</option>
                        ";
                        foreach($getProducts->result() as $product){
                                echo"<option value=".$product->id." ";if($product->id == $detail->produk_id){echo"selected";}echo">".$product->product_code." - ".$product->product_name."</option> ";
                        }
                        echo"
                    </select>
				</div>
    		</div>
    		<div class='col-md-2' style='background-color:#c5c5c5'>
				<div class='form-group'>
					<label>Qty Order: </label>
                    <input type='text' id='addStokOrderEdit_".$detail->id."' name='addStokOrderEdit_".$detail->id."' value='".$detail->qty."' class='form-control' style='display: block;' readonly>
				</div>
    		</div>
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Qty Kali : </label>
                    <div class='input-group bootstrap-touchspin'>
                    <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurangQtyOrderEdit(".$detail->id.")>-
                    </button>
                    </span>
                    <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
                    </span>
                    <input type='text' id='addStok_kali_".$detail->id."' name='addStok_kali_".$detail->id."' value='1' class='touchspin-set-value form-control' style='display: block;'>
                    <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
                    </span>
                    <span class='input-group-btn'>
                    <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambahQtyOrderEdit(".$detail->id.")>+
                    </button>
                    </span>
                    </div>
                </div>
            </div>
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Qty Diterima: </label>
                    <input type='text' id='addStokEdit_".$detail->id."' name='addStokEdit_".$detail->id."' value='".$detail->qty."' class='form-control' style='display: block;'>
                </div>
            </div>
            ";
            $data = $this->model_produk->getProductsById($detail->produk_id)->row();
            echo"
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Satuan: </label>
                    <input type='type' class='form-control' id='satuanEdit_".$detail->id."' name='satuanEdit_".$detail->id."' value='".$data->nama_satuan."' disabled>
                </div>
            </div>
    	</div>";}echo"

    	<div id='tempatAjax'>

        </div>

        <input type='hidden' id='jmlProduk' name='jmlProduk' value='0'>

        <br><br>
        <div style='margin-bottom:10px;margin-left:10px'>
        	<a href='#!' onclick=javascript:tambahProdukOrder() class='btn btn-primary btn-labeled'><b><i class='icon-plus-circle2'></i></b> Tambah Produk</a>
        </div>
        <div class='text-right'>
            ";
            if($_SESSION['rick_auto']['flag_user'] == 6 || $_SESSION['rick_auto']['flag_user'] == 1){
                if($getData->flag_proses == 1){

                }else{
                echo"
            <button type='button' id='btnApprove' onclick=javascript:approveOrder() class='btn btn-primary btn-labeled'><b><i class='icon-checkmark'></i></b> Approve</button>
            ";}}
            if($getData->flag_proses == 1){
            }else{
                echo"
        	<button type='button' onclick=javascript:simpanPembuatanOrderEdit() class='btn btn-success btn-labeled'><b><i class='icon-floppy-disk'></i></b> Simpan</button>";}echo"
        </div>
    </div>

    <!-- /media library -->
    </form>
</div>
<!-- /main content -->
";?>