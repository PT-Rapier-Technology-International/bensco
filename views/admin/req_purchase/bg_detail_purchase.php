<?php
$getPerusahaan = $this->model_master->getPerusahaanByID($getPurchase->perusahaan_id)->row();
echo"
<div class='content-wrapper'>

<!-- Invoice archive -->
<div class='panel panel-white'>
	<div class='panel-heading'>
		<h6 class='panel-title'>Request Purchase Order</h6>
		<div class='heading-elements'>
			<ul class='icons-list'>
        		<li><a data-action='collapse'></a></li>
        		<li><a data-action='reload'></a></li>
        	</ul>
    	</div>
	</div>
<div class='panel-body no-padding-bottom'>
	<div class='row'>
		<div class='col-md-6 content-group'>
			<ul class='list-condensed list-unstyled'>
				<li><h5>".$getPerusahaan->name."</h5></li>
				<li>".$getPerusahaan->address."</li>
				<li>".$getPerusahaan->city."</li>
				<li>".$getPerusahaan->telephone."</li>
			</ul>
		</div>

		<div class='col-md-6 content-group'>
			<div class='invoice-details'>
				<h5 class='text-uppercase text-semibold'>Request Purchasing Order #".$getPurchase->notransaction."</h5>
				<ul class='list-condensed list-unstyled'>
					<li>Date: <span class='text-semibold'>".date("d M Y",strtotime("+0 day", strtotime($getPurchase->dateorder)))."</span></li>
					<!-- <li>Due date: <span class='text-semibold'>May 12, 2015</span></li> -->
				</ul>
			</div>
		</div>
	</div>

	<div class='row'>
		<div class='col-md-6 col-lg-9 content-group'>
			<span class='text-muted'>Permintaan Pemesanan Pembelian Kepada :</span>
				<ul class='list-condensed list-unstyled'>
				<li><h5>".$getPurchase->nama_member." - ".$getPurchase->kota_member."</h5></li>
				<!-- <li><span class='text-semibold'>".$getPurchase->ktp."</span></li>
				<li>".$getPurchase->alamat_member."</li>
				<li>".$getPurchase->phone_member."</li>
				<li><a href='#'>".$getPurchase->email_member."</a></li> -->
			</ul>
		</div>

		<div class='col-md-6 col-lg-3 content-group'>

		</div>
	</div>
</div>
<form id='formAdd' name='formAdd'>
<div class='table-responsive'>
    <table class='table table-lg'>
        <thead>
            <tr>
                <th>Produk</th>
                
                <th class='col-sm-1'>Diskon (%)</th>
                <th class='col-sm-1'>Qty Order</th>
                ";if($_SESSION['rick_auto']['flag_user'] == 3){
                	echo"
                <th class='col-sm-1'>Qty Kirim</th>
                <th class='col-sm-1'>Colly</th>
                <th class='col-sm-1'>Berat (Kg)</th>";}echo"
                <th class='col-sm-1'>Unit Sebelum Diskon</th>
                <th class='col-sm-1'>Harga Total Sebelum Diskon</th>
                <th class='col-sm-1'>Unit Setelah Diskon</th>
                <th class='col-sm-1'>Harga Total Setelah Diskon</th>
                <th class='col-sm-1'>Perusahaan</th>
                <th class='col-sm-1'>Gudang</th>
            </tr>
        </thead>
        <tbody>
        	";
        	$purchaseDetail = $this->model_purchase->getReqPurchaseDetailByPurchase($getPurchase->id);
        	$total_pembayaran = 0;
        	foreach($purchaseDetail->result() as $purchaseDetail){
        		$total_pembayaran = $total_pembayaran + $purchaseDetail->ttl_price;
        		echo"
            <tr>
                <td id='lbltdProduk_".$purchaseDetail->id."'>
                	<h6 class='no-margin'>".$purchaseDetail->nama_produk."</h6>
                	<input type='hidden' id='txtProduk_".$purchaseDetail->id."' name='txtProduk_".$purchaseDetail->id."' value='".$purchaseDetail->id_produk."'>
            	</td>
                <td id='lbltdDiskon_".$purchaseDetail->id."' class='text-right'>";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo"<a href='#!' onclick=javascript:editAjax(".$purchaseDetail->id.",'tdDiskon_".$purchaseDetail->id."')>".$purchaseDetail->discount."</a>";}echo"
                </td>   
                <td id='txttdDiskon_".$purchaseDetail->id."' style='display:none'>
                <input type='text' id='txtDiscount_".$purchaseDetail->id."' name='txtDiscount_".$purchaseDetail->id."' class='form-control' value=".$purchaseDetail->discount.">
                <div align='right'>
	                <a href='#!' onclick=javascript:simpanAjax(".$purchaseDetail->id.",'tdDiskon_".$purchaseDetail->id."',".base64_decode($this->uri->segment(4)).")><i class='icon-checkmark'></i></a>
	                <a href='#!' onclick=javascript:cancelAjax(".$purchaseDetail->id.",'tdDiskon_".$purchaseDetail->id."')><i class='icon-cross2'></i></a>
                </div>
                </td>
                <td id='lbltdQty_".$purchaseDetail->id."'><a href='#!' onclick=javascript:editAjax(".$purchaseDetail->id.",'tdQty_".$purchaseDetail->id."')>".$purchaseDetail->qty."</a> ".$purchaseDetail->nama_satuan."</td>
                <td id='txttdQty_".$purchaseDetail->id."' style='display:none'>
                <input type='text' id='txtQty_".$purchaseDetail->id."' name='txtQty_".$purchaseDetail->id."' value='".$purchaseDetail->qty."' class='form-control'>
                <input type='hidden' id='namaSatuan_".$purchaseDetail->id."' name='namaSatuan_".$purchaseDetail->id."' value='".$purchaseDetail->nama_satuan."'>
                <div align='right'>
	                <a href='#!' onclick=javascript:simpanAjax(".$purchaseDetail->id.",'tdQty_".$purchaseDetail->id."',".base64_decode($this->uri->segment(4)).")><i class='icon-checkmark'></i></a>
	                <a href='#!' onclick=javascript:cancelAjax(".$purchaseDetail->id.",'tdQty_".$purchaseDetail->id."')><i class='icon-cross2'></i></a>
                </div>
                </td>
                ";if($_SESSION['rick_auto']['flag_user'] == 3){
                // $readon = "";
                // if($purchaseDetail->qty_kirim != ""){
                // 	$readon = "disabled";
                // }elseif($purchaseDetail->colly != ""){
                // 	$readon = "disabled";
                // }elseif($purchaseDetail->weight != ""){
                // 	$readon = "disabled";
                // }
                if($getPurchase->status_gudang == 1){
                	$readon = "disabled";
                }else{
                	$readon = "";
                }
                echo"
                <td><input type='text' name='qty_kirim_".$purchaseDetail->id."' id='qty_kirim_".$purchaseDetail->id."' class='form-control' onkeyup='return isNumberKey($(this))' onchange=javascript:edit_data('qty_kirim',".$purchaseDetail->id.",'qty_kirim') palceholder='Masukkan Qty Kirim' value='".$purchaseDetail->qty_kirim."' ".$readon."></td>
                <td><input type='text' name='colly_".$purchaseDetail->id."' id='colly_".$purchaseDetail->id."' class='form-control' onkeyup='return isNumberKey($(this))' onchange=javascript:edit_data('colly',".$purchaseDetail->id.",'colly') palceholder='Masukkan Colly' value='".$purchaseDetail->colly."' ".$readon."></td>
                <td><input type='text' name='berat_".$purchaseDetail->id."' id='berat_".$purchaseDetail->id."' class='form-control' onkeyup='return isNumberKey($(this))' onchange=javascript:edit_data('berat',".$purchaseDetail->id.",'weight') palceholder='Masukkan Berat (Kg)' value='".$purchaseDetail->weight."' ".$readon."></td>
                ";}echo"
                
                <td id='txttdPriceSatuan_".$purchaseDetail->id."' style='display:none' width='15%'>
                <input type='text' id='txtTotalSatuan_".$purchaseDetail->id."' name='txtTotalSatuan_".$purchaseDetail->id."' value='".$purchaseDetail->price."' value='".$purchaseDetail->price."' class='form-control'>
                <input type='hidden' id='txtTotalSatuanB_".$purchaseDetail->id."' name='txtTotalSatuanB_".$purchaseDetail->id."' value='".$purchaseDetail->price."' class='form-control'>
                <div align='right'>
                    <a href='#!' onclick=javascript:simpanAjax(".$purchaseDetail->id.",'tdPriceSatuan_".$purchaseDetail->id."',".base64_decode($this->uri->segment(4)).")><i class='icon-checkmark'></i></a>
                    <a href='#!' onclick=javascript:cancelAjax(".$purchaseDetail->id.",'tdPriceSatuan_".$purchaseDetail->id."')><i class='icon-cross2'></i></a>
                </div>
                </td>
                <td id='lbltdPriceSatuan_".$purchaseDetail->id."' class='text-right'>";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo"<a href='#!' onclick=javascript:editAjax(".$purchaseDetail->id.",'tdPriceSatuan_".$purchaseDetail->id."')>".number_format($purchaseDetail->price,2,',','.')."</a>";}echo"
                </td>   
                <td id='lblbeforedafterTotalSemua_".$purchaseDetail->id."'  class='text-right'>";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo"".number_format($purchaseDetail->ttl_price,2,',','.')."";}echo"
                </td> 
                
                <td id='lbltdafterUnitTotal_".$purchaseDetail->id."' class='text-right'>";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo"".number_format($purchaseDetail->price,2,',','.')."";}echo"
                
                </td> 
                <input type='hidden' id='txtafterUnitTotal_".$purchaseDetail->id."' name='txtafterUnitTotal_".$purchaseDetail->id."' value='".$purchaseDetail->price."' class='form-control'>
                <td id='lbltdTotal_".$purchaseDetail->id."' class='text-right'><span class='text-semibold'>";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo"".number_format($purchaseDetail->ttl_price,2,',','.')."";}echo"</span></td>
                <td id='txttdTotal_".$purchaseDetail->id."' class='text-right' style='display:none'>
                <input type='hidden' id='txtTotal_".$purchaseDetail->id."' name='txtTotal_".$purchaseDetail->id."' value='".$purchaseDetail->ttl_price."'>`
                </td>
                <td width='15%'>
                	<select class='form-control' id='cmbPerusahaan_".$purchaseDetail->id."' name='cmbPerusahaan_".$purchaseDetail->id."' onchange=javascript:pilih_perusahaan(".$purchaseDetail->id.")>
                		<option value='0' disabled>Pilih Perusahaan</option>
                		";foreach($getPerusahaans->result() as $perusahaans){
                			if($getPurchase->perusahaan_id == $perusahaans->id){
                				$slc = "selected";
                			}else{
                				$slc = "";
                			}
                			echo"
                				<option value='".$perusahaans->id."' ".$slc.">".$perusahaans->name."</option>
                			";
                		}echo"
                	</select>
                	<input type='hidden' id='txtPerusahaan_".$purchaseDetail->id."' name='txtPerusahaan_".$purchaseDetail->id."' value=".$getPurchase->perusahaan_id.">

                </td>                ";
                $cekProduk = $this->model_produk->getProductById($purchaseDetail->id_produk)->row();
                $cekProdukk = $this->model_produk->getProductsById($purchaseDetail->id_produk)->row();
                if($cekProduk->is_liner == "Y"){
                $getKodeBayangan = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Pcs")->row();
                $getKodeBayanganSet = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Set")->row();
                $st = $purchaseDetail->qty * $getKodeBayanganSet->satuan_value;
                if($cekProdukk->nama_satuan == "Pcs"){
                $getGudang = $this->model_master->getCekStokGudangbyProductPerusahaanLiner($getKodeBayangan->id,$getPurchase->perusahaan_id,$purchaseDetail->qty);
                }else{
                $getGudang = $this->model_master->getCekStokGudangbyProductPerusahaanLiner($getKodeBayangan->id,$getPurchase->perusahaan_id,$st);
                }
                //$getGudang = $this->model_master->getCekStokGudangbyProductPerusahaanLiner($getKodeBayangan->id,$getPurchase->perusahaan_id,$purchaseDetail->qty);
                $idProdukGet = $getKodeBayangan->id;
                $isLiner = "Y";
                }else{
                $getGudang = $this->model_master->getCekStokGudangbyProductPerusahaan($purchaseDetail->id_produk,$getPurchase->perusahaan_id,$purchaseDetail->qty);
                $idProdukGet = "";
                $isLiner = "N";
            	}
                echo"
                <input type='hidden' id='idProdukShadow_".$purchaseDetail->id."' name='idProdukShadow_".$purchaseDetail->id."' value='".$idProdukGet."'>
			    <input type='hidden' id='isliner_".$purchaseDetail->id."' name='isliner_".$purchaseDetail->id."' value='".$isLiner."'>
                <td width='15%' id='tempatGudang_".$purchaseDetail->id."'>

			        <select class='form-control' id='cmbGudang_".$purchaseDetail->id."' name='cmbGudang_".$purchaseDetail->id."'>
			            <option value='0' selected>Pilih Gudang</option>
			            ";foreach($getGudang->result() as $gudang){
			                echo"
			                    <option value='".$gudang->id_gudang."'>".$gudang->nama_gudang." (".$gudang->stok_gudang.")</option>
			                ";
			            }echo"
			        </select>
			        
                </td>
            </tr>";}echo"
        </tbody>
    </table>
</div>

<div class='panel-body'>
	<div class='row invoice-payment'>
		<div class='col-sm-7'>
			<!-- <div class='content-group'>
				<h6>Authorized person</h6>
				<div class='mb-15 mt-15'>
					<img src='assets/images/signature.png' class='display-block' style='width: 150px;' alt=''>
				</div>

				<ul class='list-condensed list-unstyled text-muted'>
					<li>Eugene Kopyov</li>
					<li>2269 Elba Lane</li>
					<li>Paris, France</li>
					<li>888-555-2311</li>
				</ul>
			</div> -->
		</div>

		<div class='col-sm-5'>
			<div class='content-group'>
				<h6>Total due</h6>
				<div class='table-responsive no-border'>
					<table class='table'>
						<tbody>
							<tr>
								<th>Subtotal:</th>
								<td class='text-right' id='subTotal'>";if($_SESSION['rick_auto']['flag_user'] == 3){
									}else{echo"Rp. ".number_format($total_pembayaran,2,',','.')."";}echo"</td>
							</tr>
							<!-- <tr>
								<th>Tax: <span class='text-regular'>(25%)</span></th>
								<td class='text-right'>$1,750</td>
							</tr> -->
							<tr>
								<th>Total:</th>
								<td class='text-right text-primary' id='grandTotal'><h5 class='text-semibold'>";if($_SESSION['rick_auto']['flag_user'] == 3){
									}else{echo"Rp. ".number_format($total_pembayaran,2,',','.')."";}echo"</h5></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class='text-right'>
					<button type='button' href='#!' id='btnSimpan' onclick=javascript:process_po(".$getPurchase->id.") class='btn btn-primary btn-labeled'><b><i class=' icon-floppy-disk'></i></b> Simpan</button>
				</div>
			</div>
		</div>
	</div>
</form>
	<h6>Informasi Tambahan</h6>
	<p class='text-muted'><h2>".$getPurchase->note."</h2><br>
	";
	if($_SESSION['rick_auto']['flag_user'] == 1){
		$nama_tuju =  "Admin";
	}elseif($_SESSION['rick_auto']['flag_user'] == 2){
		$nama_tuju =  "Bagian PO";
	}else{
		$nama_tuju =  "Bagian Gudang";
	}echo"
	<span class='text-muted'><i>*Lembar ini untuk ".$nama_tuju."</i></span>
	</p>
</div>

</div>
</div>

<script>

function isNumberKey(a){
  var x=a.val();
  a.val(x.replace(/[^0-9\.]/g,''));

}

</script>
";?>