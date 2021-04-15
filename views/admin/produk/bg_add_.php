<?php
echo"
<div class='row'>
	<div class='col-md-12'>

		<!-- Basic legend -->
		<form class='form-horizontal' id='formAdd' name='formAdd' action='".base_url("admin/produk/simpan_data")."' enctype='multipart/form-data' method='post' >
			<div class='panel panel-flat'>
				<div class='panel-heading'>
					<h5 class='panel-title'>Tambah Produk<a class='heading-elements-toggle'><i class='icon-more'></i></a></h5>
					<div class='heading-elements'>
						<ul class='icons-list'>
	                		<li><a data-action='collapse'></a></li>
	                		<li><a data-action='reload'></a></li>
	                	</ul>
                	</div>
				</div>

				<div class='panel-body'>
					<fieldset>
						<legend class='text-semibold'>Form Tambah Produk</legend>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Nama Produk</label>
							<div class='col-lg-9'>
                                <input type='text' placeholder='Masukkan Nama Produk' class='form-control' id='nama_produk' name='nama_produk'>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Kode Produk</label>
							<div class='col-lg-9'>
								<div class='row'>
									<div class='col-md-4'>
										<input type='text' placeholder='Masukkan Kode Produk ex: A001' class='form-control' id='kode_produk' name='kode_produk'>
									</div>
									<label class='col-lg-3 control-label'>Min. Stock</label>
									<div class='col-md-5'>
										<input type='text' placeholder='Masukkan Minimum Stock' class='form-control' id='stock_min' name='stock_min'>
									</div>
								</div>
							</div>
						</div>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Satuan Produk</label>
							<div class='col-md-5'>
								<select class='form-control' id='cmbSatuan' name='cmbSatuan' onclick=javascript:pilih_satuan()>
									<option value='0' selected disabled> Pilih Satuan </option>
									";
									foreach($getSatuan->result() as $satuan){
										echo"
										<option value=".$satuan->id.">".$satuan->name."</option>
										";
									}
									echo"
								</select>
							</div>
						</div>
						<div class='form-group' id='tmpIsiProduk' style='display:none'>
							<label class='col-lg-3 control-label'>Isi Produk</label>
							<div class='col-lg-9'>
								<div class='row'>
									<div class='col-md-4'>
										<input type='text' placeholder='Masukkan Isi Produk misal : 2 Pcs' class='form-control' id='isi_produk' name='isi_produk'>
									</div>
									<label class='col-lg-3 control-label'>Pcs</label>

								</div>
							</div>
						</div>
						<!-- <div class='form-group'>
							<label class='control-label col-lg-3'>Stock Gudang</label>
							<div class='col-lg-9'>
								<div class='row'>
									<div class='col-md-7'>
										<input type='text' id='nama_gudang_1' name='nama_gudang_1' class='form-control' placeholder='Masukkan Nama Gudang ex : Jakarta'>
									</div>

									<div class='col-md-5'>
										<input type='text' id='stock_gudang_1' name='stock_gudang_1' class='form-control' placeholder='Masukkan Stock'>
									</div>
								</div>
							</div>
						</div>
						<div id ='ajaxGudang'>

						</div>
						<input type='hidden' id='totalGudang' name='totalGudang' value='1'>
						<div class='form-group'>
							<label class='col-lg-3 control-label'></label>
							<div class='col-lg-9'>
                                <a onclick=javascript:tambah_gudang() class='btn btn-info btn-float'><i class=' icon-file-plus2'></i></a>
                            </div>
            			</div> -->
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Harga Produk</label>
							<div class='col-lg-9'>
                                <input type='text' placeholder='Masukkan Harga Produk ex: 100.000' class='form-control' id='rupiah_input' name='harga_produk'>
                            </div>
            			</div>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Deskripsi</label>
							<div class='col-lg-9'>
                                <textarea class='form-control' style='margin: 0px -1.75px 0px 0px; height: 115px; width: 100%;' id='deskripsi' name='deskripsi'></textarea>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Kategori</label>
							<div class='col-lg-9'>
                                <select id='cmbKategori' name='cmbKategori' data-placeholder='Pilih Kategori Produk' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true'>
                                ";
                                foreach($getKategori->result() as $kategori){
                                	echo"
                                    <option value='".$kategori->id."'>".$kategori->cat_name."</option> ";
                                }
                                echo"
                                </select>
                            </div>
            			</div>
            			<!-- <div class='form-group'>
							<label class='col-lg-3 control-label'>Perusahaan</label>
							<div class='col-lg-9'>
                                <select id='cmbPerusahaan' name='cmbPerusahaan' data-placeholder='Pilih Perusahaan Produk' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true'>
                                ";
                                foreach($getPerusahaan->result() as $perusahaan){
                                	echo"
                                    <option value='".$perusahaan->id."'>".$perusahaan->name."</option> ";
                                }
                                echo"
                                </select>
                            </div>
            			</div> -->
            			<!-- <div class='form-group'>
						<label class='col-lg-3 control-label'>Gambar Cover</label>
						<div class='col-lg-9'>
							<div class='media no-margin-top'>
								<div class='media-left'>
									<img id='img_pic_cover_1' class='images' src='".base_url("design/admin/assets/images/placeholder2.png")."' style='cursor:pointer; width: 100px; height: 100px;' class='img-rounded' alt='' style='cursor:pointer;width:150px; height:150px' onclick=javascript:click_picture('pic_cover_1')>
									<input type='file' class='pic_product'  name='pic_cover_1' id='pic_cover_1' style='opacity: 0.0;width:1px; height:1px' OnChange=javascript:picture_upload_cover(this.id,image_high_cover_1,image_tumb_cover_1,1)>
	                                <input id='image_high_cover_1' name='image_high_cover_1' type='hidden'/>
	                                <input id='image_tumb_cover_1' name='image_tumb_cover_1' type='hidden'/>
	                                <input id='gambar_default_cover_1' type='hidden' name='gambar_default_cover_1' value='1'>
								</div>
							</div>
						</div>
					</div> -->
					<div class='form-group'>
						<label class='control-label col-lg-3'>Upload Photo Cover</label>
						<div class='col-lg-9'>
							<input type='file' class='form-control' id='fileCover' name='fileCover'>
						</div>
					</div>
					<div class='form-group'>
						<label class='col-lg-3 control-label'>Gambar Detail Produk</label>
						<div class='col-lg-9'>
							<div class='media no-margin-top'>
								";
								for($i=1;$i<=5;$i++){
									echo"
								<div class='media-left'>
									<img id='img_pic_".$i."' class='images' src='".base_url("design/admin/assets/images/placeholder2.png")."' style='cursor:pointer; width: 100px; height: 100px;' class='img-rounded' alt='' style='cursor:pointer;width:150px; height:150px' onclick=javascript:click_picture('pic_".$i."')>
									<input type='file' class='pic_product'  name='pic_".$i."' id='pic_".$i."' style='opacity: 0.0;width:1px; height:1px' OnChange=javascript:picture_upload(this.id,image_high_".$i.",image_tumb_".$i.",".$i.")>                                <input id='image_high_".$i."' name='image_high_".$i."' type='hidden'/>
	                                <input id='image_tumb_".$i."' name='image_tumb_".$i."' type='hidden'/>
	                                <input id='gambar_default_".$i."' type='hidden' name='gambar_default_".$i."' value='1'>
								</div>";
							}
							echo"
								<input type='hidden' id='total_gambar' name='total_gambar' value='5'>
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