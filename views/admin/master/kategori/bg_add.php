<?php
echo"
<div class='row'>
	<div class='col-md-12'>

		<!-- Basic legend -->
		<form class='form-horizontal' id='formAdd' name='formAdd'>
			<div class='panel panel-flat'>
				<div class='panel-heading'>
					<h5 class='panel-title'>Tambah Kategori<a class='heading-elements-toggle'><i class='icon-more'></i></a></h5>
					<div class='heading-elements'>
						<ul class='icons-list'>
	                		<li><a data-action='collapse'></a></li>
	                		<li><a data-action='reload'></a></li>
	                		 
	                	</ul>
                	</div>
				</div>

				<div class='panel-body'>
					<fieldset>
						<legend class='text-semibold'>Form Tambah Kategori</legend>
						<div class='form-group'>
							<label class='col-lg-3 control-label'>Kode Kategori</label>
							<div class='col-lg-9'>
                                <input type='text' name='kode' id='kode' class='form-control' palceholder='Masukkan Kode Kategori ex: A001'>
                            </div>
            			</div>
            			<div class='form-group'>
							<label class='col-lg-3 control-label'>Nama Kategori</label>
							<div class='col-lg-9'>
                                <input type='text' name='nama' id='nama' class='form-control' palceholder='Masukkan Nama Kategori'>
                            </div>
            			</div>
            			<div class='form-group'>
						<label class='col-lg-3 control-label'>Gambar</label>
						<div class='col-lg-9'>
							<div class='media no-margin-top'>
								<div class='media-left'>
									<img id='img_pic_1' class='images' src='".base_url("design/admin/assets/images/placeholder2.png")."' style='cursor:pointer; width: 100px; height: 100px;' class='img-rounded' alt='' style='cursor:pointer;width:150px; height:150px' onclick=javascript:click_picture('pic_1')>
									<input type='file' class='pic_product'  name='pic_1' id='pic_1' style='opacity: 0.0;width:1px; height:1px' OnChange=javascript:picture_upload(this.id,image_high_1,image_tumb_1,1)>
	                                <input id='image_high_1' name='image_high_1' type='hidden'/>
	                                <input id='image_tumb_1' name='image_tumb_1' type='hidden'/>
	                                <input id='gambar_default_1' type='hidden' name='gambar_default_1' value='1'>
								</div>
							</div>
						</div>
					</div>
					</fieldset>

					<div class='text-right'>
						<button type='button' class='btn btn-primary' onclick=javascript:simpan_data('kategori')>Simpan Data <i class='icon-arrow-right14 position-right'></i></button>
					</div>
				</div>
			</div>
		</form>
		<!-- /basic legend -->

	</div>
</div>
";
?>