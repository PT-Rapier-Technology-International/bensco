<?php
echo"
<div class='content-wrapper'>
				<!-- Bordered striped table -->
				<div class='panel panel-flat'>
					<div class='panel-heading'>
						<h5 class='panel-title'>Atur Stok Produk</h5>
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
									<th>Perusahaan</th>
									<th>Gudang</th>
							</thead>
							<tbody>
							";
							$no = 0;
							foreach($getPerusahaan->result() as $perusahaan){
								$no++;
								echo"
								<tr>
									<td>$no</td>
									<td><h5>".$perusahaan->name."</h5></td>
									<td>
									";
									$getGudang = $this->model_master->getGudangbyPerusahaan($perusahaan->id);
									echo"
									<table border='0' class='table datatable-basic dataTable no-footer'>
									";
									foreach($getGudang->result() as $gudang){
										echo"
										<tr>
											<td>
									<a href='#' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:view_produk_stok(".$gudang->id.") class='btn btn-primary btn-icon'><i class='icon-database'> ".$gudang->nama_gudang."</i></a>
											<td>
										</tr>";}echo"
										</table>
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