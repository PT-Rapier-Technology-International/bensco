<?php
$getPerusahaan = $this->model_master->getPerusahaanByID($getPurchase->perusahaan_id)->row();
echo"
<div class='modal-header'>
	<button type='button' class='close' data-dismiss='modal'>&times;</button>
	<h5 class='modal-title'>Purchasing Order #".str_replace("PT.E","PT.ETC",$getPurchase->nonota)."</h5>
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
				<h5 class='text-uppercase text-semibold'>Purchasing Order #".str_replace("PT.E","PT.ETC",$getPurchase->nonota)."</h5>
				<ul class='list-condensed list-unstyled'>
					<li>Date: <span class='text-semibold'>".date("d M Y",strtotime("+0 day", strtotime($getPurchase->dateorder)))."</span></li>
					<!-- <li>Due date: <span class='text-semibold'>May 12, 2015</span></li> -->
				</ul>
			</div>
		</div>
	</div>

	<div class='row'>
		<div class='col-md-6 col-lg-9 content-group'>
			<span class='text-muted'>Pemesanan Pembelian Kepada :</span>
				<ul class='list-condensed list-unstyled'>
				<li><h5>".$getPurchase->nama_member."</h5></li>
				<!-- <li><span class='text-semibold'>".$getPurchase->ktp."</span></li>
				<li>".$getPurchase->alamat_member."</li>
				<li>".$getPurchase->phone_member."</li>
				<li><a href='#'>".$getPurchase->email_member."</a></li> -->
			</ul>
		</div>

		<div class='col-md-6 col-lg-3 content-group'>
			<span class='text-muted'>Detail Pembayaran :</span>
			<ul class='list-condensed list-unstyled invoice-payment-details'>
				<li><h5>Total Due: <span class='text-right text-semibold'>";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo" Rp. ".number_format($getPurchaseDetail->total_harga,2,',','.')."";}echo"</span></h5></li>
				<!-- <li>Nama Bank: <span class='text-semibold'>".$getPerusahaan->bank_name."</span></li>
				<li>Nomor Rekening: <span>".$getPerusahaan->rek_no."</span></li>
				<li>Kota: <span>Daerah Khusus Ibukota Jakarta</span></li>
				<li>Negara: <span>Indonesia</span></li> -->
			</ul>
		</div>
	</div>
";if($_SESSION['rick_auto']['flag_user'] == 3){
	echo"
		<div class='row'>
                <div class='col-md-6'>
                    <div class='form-group'>
                        <label>Scan Barcode : </label>
                            <input type='text' id='kode_produk' name='kode_produk' class='form-control input-xlg' placeholder='Input Kode Barcode' autofocus='' onchange='javascript:scan_barcode_order(".$getPurchase->id.");'>
                    </div>
                </div>
            </div>
	";

	}echo"
</div>

<div class='table-responsive'>

    <table class='table table-lg'>
        <thead>
            <tr>
                <th>Produk</th>
                <th class='col-sm-1'>Qty</th>
                ";if($_SESSION['rick_auto']['flag_user'] == 3){
                	echo"
                <th class='col-sm-1'>Qty Kirim</th>
                <th colspan='3' class='col-sm-2 text-center'>Colly</th>
                <th class='col-sm-1'>Berat (Kg)</th>";}
                if($_SESSION['rick_auto']['flag_user'] != 3){echo"
                <th class='col-sm-1'>Diskon (%)</th>
                <th class='col-sm-1'>Harga Satuan Sebelum Diskon</th>
                <th class='col-sm-1'>Harga Total Sebelum Diskon</th>
                <th class='col-sm-1'>Harga Satuan Setelah Diskon</th>
                <th class='col-sm-1'>Harga Total Setelah Diskon</th>";
                }echo"
            </tr>
        </thead>
        <tbody>
        	";
        	$purchaseDetail = $this->model_purchase->getPurchaseDetailByPurchase($getPurchase->id);
        	$total_pembayaran = 0;
        	foreach($purchaseDetail->result() as $purchaseDetail){
        		$total_pembayaran = $total_pembayaran + $purchaseDetail->ttl_price;
        		echo"
            <tr>
                <td>
                	<h6 class='no-margin'>".$purchaseDetail->nama_produk."</h6>
            	</td>
                ";
                if($purchaseDetail->qty_kirim == "" || $purchaseDetail->qty_kirim == 0){
                $qty_out = $purchaseDetail->qty;
                	echo"
                <td>".$purchaseDetail->qty." ".$purchaseDetail->nama_satuan."</td>";
                }else{
                //$qty_out = $purchaseDetail->qty_kirim;
                 $qty_out = $purchaseDetail->qty;
                	echo"
                <td>".$purchaseDetail->qty." ".$purchaseDetail->nama_satuan."</td>";
            	}
                if($_SESSION['rick_auto']['flag_user'] == 3){
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
                <td>-</td>
                <td><input type='text' name='colly_to_".$purchaseDetail->id."' id='colly_to_".$purchaseDetail->id."' class='form-control' onkeyup='return isNumberKey($(this))' onchange=javascript:edit_data('colly_to',".$purchaseDetail->id.",'colly_to') palceholder='Masukkan Colly' value='".$purchaseDetail->colly_to."' ".$readon."></td>
                <td><input type='text' name='berat_".$purchaseDetail->id."' id='berat_".$purchaseDetail->id."' class='form-control' onkeyup='return isNumberKey($(this))' onchange=javascript:edit_data('berat',".$purchaseDetail->id.",'weight') palceholder='Masukkan Berat (Kg)' value='".$purchaseDetail->weight."' ".$readon."></td>
                ";}
                $totalSebelum = $purchaseDetail->harga_satuan * $qty_out;
                $totalSatuanAfter = $purchaseDetail->ttl_price / $qty_out;
                //$totalSatuanSetelah = $purchaseDetail->price * $qty_out;
                if($_SESSION['rick_auto']['flag_user'] != 3){
                echo"
                <td class='text-right'><span class='text-semibold'>".$purchaseDetail->discount."</span></td>
                <td class='text-right'>";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo"".number_format($purchaseDetail->harga_satuan,2,',','.')."";}echo"</td>
                <td class='text-right'><span class='text-semibold'>";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo"".number_format($totalSebelum,2,',','.')."";}echo"</span></td>
                <!-- <td class='text-right'>";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo"".number_format($purchaseDetail->price,2,',','.')."";}echo"</td> -->
                <!--<td class='text-right'>";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo"".number_format(floor($totalSatuanAfter),2,',','.')."";}echo"</td>-->

                <td class='text-right'>";if($_SESSION['rick_auto']['flag_user'] == 3){
                	echo"";
                	}
                	elseif ($purchaseDetail->price != $totalSatuanAfter){
                		echo"".number_format(floor($purchaseDetail->price),2,',','.')."";
                		}else{
                			echo"".number_format(floor($totalSatuanAfter),2,',','.')."";
                		}
                		echo"</td>

                <td class='text-right'><span class='text-semibold'>";if($_SESSION['rick_auto']['flag_user'] == 3){echo"";}else{echo"".number_format($purchaseDetail->ttl_price,2,',','.')."";}echo"</span></td>
            </tr>";}}echo"
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
								<td class='text-right'>";if($_SESSION['rick_auto']['flag_user'] == 3){
									}else{echo"Rp. ".number_format($total_pembayaran,2,',','.')."";}echo"</td>
							</tr>
							<!-- <tr>
								<th>Tax: <span class='text-regular'>(25%)</span></th>
								<td class='text-right'>$1,750</td>
							</tr> -->
							<tr>
								<th>Total:</th>
								<td class='text-right text-primary'><h5 class='text-semibold'>";if($_SESSION['rick_auto']['flag_user'] == 3){
									}else{echo"Rp. ".number_format($total_pembayaran,2,',','.')."";}echo"</h5></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class='text-right'>
					<a href='".base_url("admin/purchase/print_purchase/".base64_encode($getPurchase->id)."")."' target='_blank' class='btn btn-primary btn-labeled'><b><i class='icon-printer'></i></b> Print</a>

				";if($getPurchase->status_gudang == 1){
					if($_SESSION['rick_auto']['flag_user'] == 3){
					echo"
				<!-- <div class='text-right'>
					<a href='#!' onclick=javascript:save_data(".$getPurchase->id.") class='btn btn-primary btn-labeled'><b><i class='icon-paperplane'></i></b> Simpan Data</a>
				</div> -->";
					}else{
					echo"
					<!-- <a href='#!' onclick=javascript:process_nota(".$getPurchase->id.") class='btn btn-primary btn-labeled'><b><i class='icon-paperplane'></i></b> Buat Invoice</a> -->
				";}}
				echo"</div>
			</div>
		</div>
	</div>

	<h6>Informasi Tambahan</h6>
	<p class='text-muted'><h2 id='lblNote_".$getPurchase->id."'>Catatan : ".$getPurchase->note."</h2></p>
	<div class='form-group' id='txtNote_".$getPurchase->id."' style='display:none'>
        <label>Catatan : </label>
         <textarea rows='4' cols='50' id='txtNoteEdit_".$getPurchase->id."' name='txtNoteEdit_".$getPurchase->id."' class='form-control'>".$getPurchase->note."</textarea>
    </div>
    ";
    if($_SESSION['rick_auto']['flag_user'] == 4 || $_SESSION['rick_auto']['flag_user'] == 1){
    	echo"
	<div id='btnEdit_".$getPurchase->id."'>
	<button type='button' class='btn border-slate text-slate-800 btn-flat' onclick=javascript:editNote(".$getPurchase->id.")><i class='icon-pencil7 position-left'></i>Edit Catatan</button>
	</div>";}echo"
	<div id='btnSave_".$getPurchase->id."' style='display:none'>
	<button type='button' class='btn border-slate text-slate-800 btn-flat' onclick=javascript:saveNote(".$getPurchase->id.")><i class='icon-floppy-disk position-left'></i>Simpan Catatan</button>
	<button type='button' class='btn border-slate text-slate-800 btn-flat' onclick=javascript:cancelNote(".$getPurchase->id.")><i class='icon-undo2 position-left'></i>Cancel</button>
	</div>
	<br>
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

<div class='modal-footer'>
	<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
</div>

<script>

function isNumberKey(a){
  var x=a.val();
  a.val(x.replace(/[^0-9\.]/g,''));

}

</script>
";?>
