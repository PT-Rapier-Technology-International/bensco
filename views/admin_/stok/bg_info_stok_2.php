<?php
$uri4 = $this->uri->segment(4);
echo"
<div class='content-wrapper'>
				<!-- Bordered striped table -->
				<div class='panel panel-flat'>
					<div class='panel-heading'>
						<h5 class='panel-title'>Informasi Stok Produk</h5>
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
									<th>Kode Produk</th>
									<th>Nama Produk</th>
									<th>Kategori</th>
									<th>Satuan</th>
									<th class='text-center'>Informasi Stok</th>
								</tr>
							</thead>
							<tbody>
							";
							$no = 0;
							foreach($getProducts->result() as $produk){
								$no++;
								echo"
								<tr>
									<td>$no</td>
									<td>".$produk->product_code."</td>
									<td>".$produk->product_name."</td>
									<td>".$produk->nama_kategori."</td>
									<td>".$produk->nama_satuan."</td>
									<td>
									";
									$data_stok = $this->model_master->getStokProduct($produk->id);
									echo"<table class='table datatable-basic'>";
									foreach($data_stok->result() as $stok){
										echo"
										<tr>
											<td><b>".$stok->nama_perusahaan."</b></td>
											<td>";
											$data_stok2 = $this->model_master->getStokProductGudangByPerusahaanByProduk($produk->id,$stok->id_perusahaan);
											echo"<table class='table datatable-basic'>";
											foreach($data_stok2->result() as $stok2){
												echo"
												<tr>
													<td><b>".$stok2->nama_gudang."</b></td>
													<td><b>".$stok2->jumlah_stok."</b></td>
													";
													if($uri4 != ""){
														echo"
														<td><a href='#!' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:actionMutasi(".$stok2->id.",".$stok2->jumlah_stok.",".$stok->id_perusahaan_pg.") class='btn btn-primary btn-labeled'><b><i class='icon-flip-vertical4'></i></b>Mutasi Stok</a></td>
														";
													}echo"
												</tr>";}
												echo"</table>
											</td>
										</tr>
										";
									}echo"</table>
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