<?php
echo"
<div class='row'>
	<div class='col-md-12'>

		<!-- Basic legend -->
		<form class='form-horizontal' id='formAdd' name='formAdd' action='".base_url("admin/produk/edit_data")."'enctype='multipart/form-data' method='post'>
			<div class='panel panel-flat'>
				<div class='panel-heading'>
					<h5 class='panel-title'>Edit Produk<a class='heading-elements-toggle'><i class='icon-more'></i></a></h5>
					<div class='heading-elements'>
						<ul class='icons-list'>
	                		<li><a data-action='collapse'></a></li>
	                		<li><a data-action='reload'></a></li>
	                	</ul>
                	</div>
				</div>

				<div class='panel-body'>
					<fieldset>
						<legend class='text-semibold'>Form Edit Produk</legend>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Nama Produk</label>
							<div class='col-lg-9'>
                                <input type='text' placeholder='Masukkan Nama Produk' class='form-control' id='nama_produk' name='nama_produk' value='".$produk->product_name."'>
                                <input type='hidden' placeholder='Masukkan Nama Produk' class='form-control' id='id' name='id' value='".$produk->id."'>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Kode Produk Shadow</label>
							<div class='col-lg-9'>
                                <input type='text' placeholder='Masukkan Kode Produk Bayangan' class='form-control' id='kode_produk_bayangan' name='kode_produk_bayangan' value='".$produk->product_code_shadow."'>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Kode Produk</label>
							<div class='col-lg-9'>
								<div class='row'>
									<div class='col-md-4'>
										<input type='text' placeholder='Masukkan Kode Produk ex: A001' class='form-control' id='kode_produk' name='kode_produk' value='".$produk->product_code."'>
									</div>
									<label class='col-lg-3 control-label'>Min. Stock</label>
									<div class='col-md-5'>
										<input type='text' placeholder='Masukkan Minimum Stock' class='form-control' id='stock_min' name='stock_min' value='".$produk->stock_min."'>
									</div>
								</div>
							</div>
						</div>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Satuan Produk</label>
							<div class='col-md-4'>
								<select class='form-control' id='cmbSatuan' name='cmbSatuan' onclick=javascript:pilih_satuan()>
									<option value='0' selected disabled> Pilih Satuan </option>
									";
									foreach($getSatuan->result() as $satuan){
										if($satuan->id == $produk->satuan_id){
											$selected = "selected";
										}else{
											$selected = "";
										}
										echo"
										<option value=".$satuan->id." $selected>".$satuan->name."</option>
										";
									}
									echo"
								</select>
							</div>
							<label class='col-lg-2 control-label'>Produk ini LINER ?</label>
							<div class='col-md-3'>
								<select class='form-control' id='cmbIsLiner' name='cmbIsLiner'>
									";
										if($produk->is_liner == "N"){
											$selectedN = "selected";
										}else{
											$selectedN = "";
										}

										if($produk->is_liner == "Y"){
											$selectedY = "selected";
										}else{
											$selectedY = "";
										}
										echo"
										<option value='N' $selectedN>Tidak</option>
										<option value='Y' $selectedY>Ya</option>
								</select>
							</div>
						</div>";
						if($produk->satuan_id == 23){
							$tampil = "block";
						}else{
							$tampil = "none";
						}echo"
						<div class='form-group' id='tmpIsiProduk' style='display:".$tampil."'>
							<label class='col-lg-3 control-label'>Isi Produk</label>
							<div class='col-lg-9'>
								<div class='row'>
									<div class='col-md-4'>
										<input type='text' placeholder='Masukkan Isi Produk misal : 2 Pcs' class='form-control' id='isi_produk' name='isi_produk' value='".$produk->satuan_value."'>
									</div>
									<label class='col-lg-3 control-label'>Pcs</label>

								</div>
							</div>
						</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Harga Produk</label>
							<div class='col-lg-9'>
                                <input type='text' placeholder='Masukkan Harga Produk ex: 100.000' class='form-control' id='rupiah_input' name='harga_produk' value='".number_format($produk->normal_price,0,'','.')."'>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Harga Ekspor</label>
							<div class='col-lg-9'>
                                <input type='text' placeholder='Masukkan Harga Ekspor ex: 100.000' class='form-control' id='rupiah_input' name='harga_ekspor' value='".number_format($produk->ekspor_price,0,'','.')."'>
                            </div>
            			</div>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Deskripsi</label>
							<div class='col-lg-9'>
                                <textarea class='form-control' style='margin: 0px -1.75px 0px 0px; height: 115px; width: 738px;' id='deskripsi' name='deskripsi'>".$produk->product_desc."</textarea>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Kategori</label>
							<div class='col-lg-9'>
                                <select id='cmbKategori' name='cmbKategori' data-placeholder='Pilih Kategori Produk' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true'>
                                ";
                                foreach($getKategori->result() as $kategori){
                                	if($produk->category_id == $kategori->id){
                                		$slkategori = "selected";
                                	}else{
                                		$slkategori = "";
                                	}
                                	echo"
                                    <option value='".$kategori->id."' $slkategori>".$kategori->cat_name."</option> ";
                                }
                                echo"
                                </select>
                            </div>
            			</div> ";
            			$no = 0;
            			foreach($getBarcode->result() as $barcode){
            				$no++;
            				echo"
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>QRCode $no</label>
							<div class='col-lg-9'>
								<div class='row'>
									<div class='col-md-4'>
										<input type='text' placeholder='Masukkan Qrcode' class='form-control' id='qrcode".$barcode->id."' name='qrcode".$barcode->id."' value='".$barcode->barcode."'>
									</div>
									<label class='col-lg-3 control-label'>Isi $no</label>
									<div class='col-md-5'>
										<input type='text' placeholder='Masukkan Value' class='form-control' id='qrcode_value".$barcode->id."' name='qrcode_value".$barcode->id."' value='".$barcode->isi."'>
									</div>
								</div>
							</div>
						</div>
            				";
            			}echo"
            			
            			<div class='form-group' id='tmpGambarCover'>
						<label class='col-lg-3 control-label'>Gambar Cover</label>
						<div class='col-lg-9'>
							<div class='media no-margin-top'>
								<div class='media-left'>";
									if($produk->product_cover == ""){
										$img_src = "design/admin/assets/images/placeholder2.png";
									}else{
										$img_src = $produk->product_cover;
									}echo"
									<img id='img_pic_cover_1' class='images' src='".base_url("".$img_src."")."' class='img-rounded' alt='Produk Bensco ".$produk->product_name."' style='width:150px; height:150px'>
								</div>
							</div>
							<button type='button' id='btnGantiGambar' class='btn btn-primary' onclick=javascript:gantiGambarCover();>Ganti Gambar / Upload Gambar</button>
						</div>
					</div>
					<div class='form-group' id='tmpUploadCover' style='display:none'>
						<label class='control-label col-lg-3'>Upload Photo Cover</label>
						<div class='col-lg-9'>
							<input type='file' class='form-control' id='fileCover' name='fileCover'>
						<br>
						<button type='button' id='btnCancelGanti' class='btn btn-warning' onclick=javascript:batalgantiGambarCover();>Batal Upload / Ganti Gambar</button>
						</div>
						<input type='hidden' id='txtGantiCover' name='txtGantiCover' value='0'>
					</div>
					<div class='form-group'>
						<label class='col-lg-3 control-label'>Gambar Detail Produk</label>
						<div class='col-lg-9'>
							<div class='media no-margin-top'>
								";
								$getImageDetail = $this->model_produk->getImageProductByProduct($produk->id);
								$totalGambar = 5 - $getImageDetail->num_rows();
								foreach($getImageDetail->result() as $imageDetail){
									echo"
								<div class='media-left'>
									<img id='img_pic_edit_".$imageDetail->id."' class='images' src='".base_url($imageDetail->product_img)."' style='cursor:pointer; width: 100px; height: 100px;' class='img-rounded' alt='' style='cursor:pointer;width:150px; height:150px'>
									<input type='file' class='pic_product'  name='pic_edit_".$imageDetail->id."' id='pic_edit_".$imageDetail->id."' style='opacity: 0.0;width:1px; height:1px'>                                
									<input id='image_high_edit_".$imageDetail->id."' name='image_high_edit_".$imageDetail->id."' type='hidden'/>
	                                <input id='image_tumb_edit_".$imageDetail->id."' name='image_tumb_edit_".$imageDetail->id."' type='hidden'/>
	                                <input id='gambar_default_edit_".$imageDetail->id."' type='hidden' name='gambar_default_edit_".$imageDetail->id."' value='1'>
	                                <input type='file' class='form-control' id='fileDetailEdit_".$imageDetail->id."' name='fileDetailEdit_".$imageDetail->id."'>
								</div>";
								}
								for($i=1;$i<=$totalGambar;$i++){
									echo"
								<div class='media-left'>
									<img id='img_pic_".$i."' class='images' src='".base_url("design/admin/assets/images/placeholder2.png")."' style='cursor:pointer; width: 100px; height: 100px;' class='img-rounded' alt='' style='cursor:pointer;width:150px; height:150px' onclick=javascript:click_picture('pic_".$i."')>
									<input type='file' class='pic_product'  name='pic_".$i."' id='pic_".$i."' style='opacity: 0.0;width:1px; height:1px'>
									<input id='image_high_".$i."' name='image_high_".$i."' type='hidden'/>
	                                <input id='image_tumb_".$i."' name='image_tumb_".$i."' type='hidden'/>
	                                <input id='gambar_default_".$i."' type='hidden' name='gambar_default_".$i."' value='1'>
								</div>
								<input type='file' class='form-control' id='fileDetailAdd_".$i."' name='fileDetailAdd_".$i."'>";
							}
							echo"
								<input type='hidden' id='total_gambar' name='total_gambar' value='".$totalGambar."'>
								<br>
								<!-- <a href='#!' onclick=javascript:tambah_foto() class='btn btn-success'>Tambah Foto</a> -->
							</div>
						</div>
					</fieldset>

					<div class='text-right'>
						<button type='submit' class='btn btn-primary'>Simpan Data <i class='icon-arrow-right14 position-right'></i></button>
					</div>
				</div>
			</div>
		</form>
		<!-- /basic legend -->

	</div>
</div>
";
?>