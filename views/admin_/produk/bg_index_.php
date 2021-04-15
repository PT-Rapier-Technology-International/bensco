<?php
echo"
<div class='content-wrapper'>
				<!-- Bordered striped table -->
				<div class='panel panel-flat'>
					<div class='panel-heading'>
						<h5 class='panel-title'>List Produk</h5>
						<div class='heading-elements'>
							<ul class='icons-list'>
		                		<li><a data-action='collapse'></a></li>
		                		<li><a data-action='reload'></a></li>
		                		 
		                	</ul>
	                	</div>
					</div>

					<div class='panel-body'>
						<!-- <a href='".base_url("admin/produk/add")."' class='btn btn-info btn'>Tambah Produk</a> --> 
					</div>

					<div class='table-responsive'>
						<table class='table datatable-basic'>
							<thead>
								<tr>
									<th>#</th>
									<th>Kode</th>
									<th>Nama</th>
									<th>Kategori</th>
									<th>Stock</th>
									<th>Harga (Rp.)</th>
									<th>Gambar Cover</th>
									<th>Aksi</th>
							</thead>
							<tbody>
							";
							$no = 0;
							foreach($getProducts->result() as $product){
								$no++;
								echo"
								<tr>
									<td>$no</td>
									<td>".$product->product_code."</td>
									";
									if($product->flag_jenis_satuan == 1){
										echo"
									<td>".$product->product_name."</td>";
									}else{
										echo"
									<td>".$product->product_name." (".$product->satuan_value." Pcs)</td>";
									}
									echo"
									<td>".$product->nama_kategori."</td>
									<td>".$product->stock." ".$product->nama_satuan." </td>
									<td class='text-right'>".number_format($product->normal_price,2,',','.')."</td>
									<td class='text-center'><img src=".base_url($product->product_cover)." width='80px;' height='80px;'></td>
									<td width='20%'>
									<a href='#' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:view_detail_image(".$product->id.") class='btn btn-primary btn-icon'><i class='icon-images2'></i></a>
									<a href='".base_url("admin/produk/edit/".base64_encode($product->id)."")."' class='btn btn-warning btn-icon'><i class='icon-pencil7'></i></a>
									<a href='#' data-toggle='modal' data-target='#modal_delete_data' onclick=javascript:confirm_delete_produk(".$product->id.") class='btn btn-danger btn-icon'><i class='icon-trash'></i></a>
									</td>
								</tr>
								";}
								echo"
							</tbody>
						</table>
					</div>
				</div>
				<!-- /bordered striped table -->
</div>";
?>