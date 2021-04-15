<?php
$id_invoice = $this->uri->segment(4);
$jenis = $this->uri->segment(5);
$tanggal = $this->uri->segment(6);
if($jenis == 5){
	$nama_jenis = "RET";
}else{
	$nama_jenis = "REV";
}
$getPerusahaan = $this->model_master->getPerusahaanByID($getInvoice->perusahaan_id)->row();
$getNoTT = $this->model_invoice->getNoTTbyInvoiceId($id_invoice)->row();

$namacut = $getPerusahaan->name;
$namapt = substr($namacut,0,3);
$nama = substr($namacut,4);
$arr = explode(' ', $nama);
$singkatan = "";
foreach($arr as $kata)
{
$singkatan .= substr($kata, 0, 1);
}

$namaptsj = $namapt."".strtoupper($singkatan);
$getInvoiceReturRevisi = $this->model_invoice->getCekReturRevisiByPerusahaan($namaptsj,$nama_jenis);
if($getInvoiceReturRevisi->num_rows() > 0){
	$uniqNo = $getInvoiceReturRevisi->num_rows();
	$no_transaksi = $uniqNo+1;
}else{
	$no_transaksi = 1;
}
$bulan = strtoupper(date('M'));
$tahun = date('Y');
$nosj = "".$namaptsj."/".sprintf("%'.05d", $no_transaksi)."/".$nama_jenis."/".date('m')."/".date('y')."";
echo"
<div class='content-wrapper'>
<!-- Invoice archive -->
<div class='panel panel-white'>
	<div class='panel-heading'>
		<h6 class='panel-title'>Invoice #".$getInvoice->nonota."</h6>
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
					<h5 class='text-uppercase text-semibold'>Invoice #".$getInvoice->nonota."</h5>
					<ul class='list-condensed list-unstyled'>
					<li>Date: <span class='text-semibold'>".date("d M Y",strtotime("+0 day", strtotime($getInvoice->dateorder)))."</span></li>
					<!-- <li>Due date: <span class='text-semibold'>May 12, 2015</span></li> -->
				</ul>
				</div>
			</div>
		</div>

		<div class='row'>
			<div class='col-md-6 col-lg-9 content-group'>
				<span class='text-muted'>Invoice To:</span>
					<ul class='list-condensed list-unstyled'>
					<li><h5>".$getInvoice->member_name."</h5></li>
					<li><span class='text-semibold'>".$getInvoice->ktp."</span></li>
					<li>".$getInvoice->alamat_member."</li>
					<li>".$getInvoice->phone_member."</li>
					<li><a href='#'>".$getInvoice->email_member."</a></li>
				</ul>
			</div>


		</div>
		<div class='row'>
                <div class='col-md-6'>
                    <div class='form-group'>
                        <label>Scan Barcode : </label>
                            <input type='text' id='kode_produk' name='kode_produk' class='form-control input-xlg' placeholder='Input Kode Barcode' autofocus='' onchange='javascript:scan_barcode_retur_revisi(".$getInvoice->id.");'>
                    </div>
                </div>
        </div>
	</div>
	<form id='formAdd' name='formAdd'>
	<div class='table-responsive'>
		<input type='hidden' id='txtnoTransaksi' name='txtnoTransaksi' value='".$nosj."'>
		<input type='hidden' id='txtJenisTransaksi' name='txtJenisTransaksi' value='".$jenis."'>
		<input type='hidden' id='txtnoInvoice' name='txtnoInvoice' value='".$getInvoice->nonota."'>
		<input type='hidden' id='txtidInvoice' name='txtidInvoice' value='".$getInvoice->id."'>
		<input type='hidden' id='txtnoTT' name='txtnoTT' value='".$getNoTT->no_tanda_terima."'>
		<input type='hidden' id='txtMemberId' name='txtMemberId' value='".$getInvoice->member_id."'>
		<input type='hidden' id='txtPaymentDate' name='txtPaymentDate' value='".$tanggal."'>
		<input type='hidden' id='txtJenis' name='txtJenis' value='".$jenis."'>
	    <table class='table table-lg'>
	        <thead>
	            <tr>
	                <th>Produk</th>
	                <th class='col-sm-1'>Harga Satuan</th>
	                <th class='col-sm-1'>Qty</th>
	                <th class='col-sm-1'>Harga Total</th>
            	</tr>
	        </thead>
	        <tbody>";
	        	$getInvoiceDetail = $this->model_invoice->getInvoiceDetailByInvoiceId($getInvoice->id);
	        	$total_pembayaran = 0;
	        	foreach($getInvoiceDetail->result() as $detailInvoice){
	        		$total_pembayaran = $total_pembayaran + $detailInvoice->ttl_price;
	        		echo"
	            <tr>
	                <td>
	                	<h6 class='no-margin'>".$detailInvoice->product_name."</h6>
                	</td>";
                	if($jenis == 5){
                		echo"
	                <td class='text-right' id='lbltdPrice_".$detailInvoice->id."'>".number_format($detailInvoice->price,2,',','.')."</td>";}else{
	                	echo"
	                <td class='text-right' id='lbltdPriceSatuan_".$detailInvoice->id."'><a href='#!' onclick=javascript:editAjax(".$detailInvoice->id.",'tdPriceSatuan_".$detailInvoice->id."')>".number_format($detailInvoice->price,2,',','.')."</td>";
	                }echo"
	                <td id='txttdPriceSatuan_".$detailInvoice->id."' style='display:none' width='15%'>
		                <input type='text' id='txtTotalSatuan_".$detailInvoice->id."' name='txtTotalSatuan_".$detailInvoice->id."' value='".$detailInvoice->price."' class='form-control'>
		                <input type='hidden' id='txtTotalSatuanOld_".$detailInvoice->id."' name='txtTotalSatuanOld_".$detailInvoice->id."' value='".$detailInvoice->price."' class='form-control'>
		                <div align='right'>
		                	";
		                	if($jenis == 5){
		                		echo"
			                <a href='#!' onclick=javascript:simpanAjaxRetur(".$detailInvoice->id.",'tdPriceSatuan_".$detailInvoice->id."',".$id_invoice.")><i class='icon-checkmark'></i></a>";
		                	}else{
		                		echo"
			                <a href='#!' onclick=javascript:simpanAjax(".$detailInvoice->id.",'tdPriceSatuan_".$detailInvoice->id."',".$id_invoice.")><i class='icon-checkmark'></i></a>";}
			                echo"
			                <a href='#!' onclick=javascript:cancelAjax(".$detailInvoice->id.",'tdPriceSatuan_".$detailInvoice->id."')><i class='icon-cross2'></i></a>
		                </div>
		            </td>
	                <td id='lbltdQty_".$detailInvoice->id."'><a href='#!' onclick=javascript:editAjax(".$detailInvoice->id.",'tdQty_".$detailInvoice->id."') id='atdQty_".$detailInvoice->id."'>".$detailInvoice->qty_kirim."</a> ".$detailInvoice->satuan."
	                </td>
	                <td id='txttdQty_".$detailInvoice->id."' style='display:none'>
	                	<input type='text' id='txtQty_".$detailInvoice->id."' name='txtQty_".$detailInvoice->id."' value='".$detailInvoice->qty_kirim."' class='form-control'>
	                	
	                	<input type='hidden' id='namaSatuan_".$detailInvoice->id."' name='namaSatuan_".$detailInvoice->id."' value='".$detailInvoice->satuan."'>
	                	<input type='hidden' id='txtQtyOld_".$detailInvoice->id."' name='txtQtyOld_".$detailInvoice->id."' value='".$detailInvoice->qty_kirim."' class='form-control'>
	                <div align='right'>
	                	";
                		if($jenis == 5){
                		echo"
		                <a href='#!' onclick=javascript:simpanAjaxRetur(".$detailInvoice->id.",'tdQty_".$detailInvoice->id."',".$id_invoice.")><i class='icon-checkmark'></i></a>
		                ";}else{
		                	echo"
		                <a href='#!' onclick=javascript:simpanAjax(".$detailInvoice->id.",'tdQty_".$detailInvoice->id."',".$id_invoice.")><i class='icon-checkmark'></i></a>";
		                }echo"
		                <a href='#!' onclick=javascript:cancelAjax(".$detailInvoice->id.",'tdQty_".$detailInvoice->id."')><i class='icon-cross2'></i></a>
                	</div>
	                </td>
	                <td id='lbltdTotal_".$detailInvoice->id."' class='text-right'><span class='text-semibold'>".number_format($detailInvoice->ttl_price,2,',','.')."</span></td>
	                <td id='txttdTotal_".$detailInvoice->id."' class='text-right' style='display:none'>
	                <input type='hidden' id='txtTotal_".$detailInvoice->id."' name='txtTotal_".$detailInvoice->id."' value='".$detailInvoice->ttl_price."'>
	                <input type='hidden' id='txtTotalOld_".$detailInvoice->id."' name='txtTotalOld_".$detailInvoice->id."' value='".$detailInvoice->ttl_price."'>
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
								<td class='text-right' id='subTotal'>Rp. ".number_format($total_pembayaran,2,',','.')."</td>
								</tr>
								";
								$ppn = $total_pembayaran * 10 / 100;
        						$grandTotal = $total_pembayaran + $ppn;
        						echo"
								<tr>
								<th>PPN (10%): <span class='text-regular'></span></th>
								<td class='text-right' id='ppn_total'>Rp. ".number_format($ppn,2,',','.')."</td>
								</tr>
								<tr>
									<th>Total:</th>
									<td class='text-right text-primary' id='grandTotal'><h5 class='text-semibold'>Rp. ".number_format($grandTotal,2,',','.')."</h5></td>
									<input type='hidden' id='txtTotalPembayaran' name='txtTotalPembayaran' value='".$total_pembayaran."'>
								</tr>
							</tbody>
						</table>
					</div>

					<div class='text-right'>
						<a href='".base_url("admin/invoice/proses_tanda_terima/".$getNoTT->no_tanda_terima."")."' class='btn btn-default' data-dismiss='modal'>Kembali</a>
						<button type='button' id='btnReturRevisi' onclick=javascript:saveReturRevisi(".$id_invoice.",".$jenis.") class='btn btn-primary btn-labeled'><b><i class='icon-floppy-disk'></i></b> Simpan Data</button>
					</div>
				</div>
			</div>
			<h6>Catatan</h6>
				<p class='text-muted'><textarea class='form-control' id='txtNote' name='txtNote'></textarea></p>
		</div>
		</form>
		
	</div>

	<!-- <div class='modal-footer'>
		<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
	</div> -->
</div>
</div>
";?>