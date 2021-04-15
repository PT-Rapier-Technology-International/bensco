<?php
echo"
<script type='text/javascript' src='".base_url("design/admin/assets/js/pages/gallery_library.js")."'></script>
<script type='text/javascript' src='".base_url("design/admin/assets/js/pages/gallery_library.js")."'></script>

<!-- Main content -->
<div class='content-wrapper'>

	<!-- Media library -->
	<div class='panel panel-white'>
		<div class='panel-heading'>
			<h6 class='panel-title text-semibold'>Form Pembuatan Purchase Order Baru</h6>
			<div class='heading-elements'>
				<ul class='icons-list'>
            		<li><a data-action='collapse'></a></li>
            		<li><a data-action='reload'></a></li>
            	</ul>
        	</div>
		</div>

		<table class='table datatable-basic'>
            <thead>
                <tr>
                	<th><input type='checkbox' class='styled'></th>
                    <th>Gambar</th>
                    <th>Produk</th>
                    <th>Harga Satuan (Rp.)</th>
                    <th>Qty Order</th>
                    <th>Perusahaan</th>
                    <th>Gudang</th>
                    <th>Total Satuan</th>
                </tr>
            </thead>
            <tbody>";
            	foreach($getProducts->result() as $product){
            		echo"
                <tr>
                	<td><input type='checkbox' class='styled'></td>
                    <td>
                        <a href='".base_url($product->product_cover)."' data-popup='lightbox'>
	                        <img src='".base_url($product->product_cover)."' alt='' class='img-rounded img-preview'>
                        </a>
                    </td>
                    <td><a href='#'>".$product->product_name."</a></td>
                    <td class='text-right'>".number_format($product->normal_price,2,',','.')."</td>
                    <td width='17%'>
                        <div class='input-group bootstrap-touchspin'>
                        <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurang(".$product->id.")>-
                        </button>
                        </span>
                        <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
                        </span>
                        <input type='text' id='addStok_".$product->id."' name='addStok_".$product->id."' value='0' class='touchspin-set-value form-control' style='display: block;'>
                        <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
                        </span>
                        <span class='input-group-btn'>
                        <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambah(".$product->id.")>+
                        </button>
                        </span>
                        </div>
                    </td>
                    <td width='20%'>
						<select class='form-control' id='cmbPerusahaan_".$product->id."' name='cmbPerusahaan_".$product->id."' onchange=javascript:pilih_perusahaan(".$product->id.")>
                		<option value='0' disabled>Pilih Perusahaan</option>
                		";foreach($getPerusahaan->result() as $perusahaans){
                			echo"
                				<option value='".$perusahaans->id."'>".$perusahaans->name."</option>
                			";
                		}echo"
                		</select>
                    </td>
                    <td width='20%' id='tempatGudang_".$product->id."'>
				        <select class='form-control' id='cmbGudang_".$product->id."' name='cmbGudang_".$product->id."'>
				            <option value='0' selected>Pilih Gudang</option>
				            ";foreach($getGudang->result() as $gudang){
				                echo"
				                    <option value='".$gudang->id."'>".$gudang->name."</option>
				                ";
				            }echo"
				        </select>
                	</td>
                	<td class='text-right'>Rp. 0.00</td>
                </tr>";}
                echo"
            </tbody>
        </table>
    </div>
    <!-- /media library -->

</div>
<!-- /main content -->
";?>