<?php
$uri4 = $this->uri->segment(4);
echo"
<div class='content-wrapper'>
				<!-- Bordered striped table -->
				<div class='panel panel-flat'>
					<div class='panel-heading'>
						<h5 class='panel-title'>Daftar Order Barang</h5>
						<div class='heading-elements'>
							<ul class='icons-list'>
		                		<li><a data-action='collapse'></a></li>
		                		<li><a data-action='reload'></a></li>
		                		 
		                	</ul>
	                	</div>
					</div>

					<div class='panel-body'>";
					//if($_SESSION['rick_auto']['flag_user'] == 3 || $_SESSION['rick_auto']['flag_user'] == 6){
					if($_SESSION['rick_auto']['flag_user'] == 3){
						echo"";
					}else{
						echo"
						<a href='".base_url("admin/produk/order_add")."' class='btn btn-info btn'>Order Barang Baru</a>";
					}echo"
					</div>

					<div class='table-responsive'>
						<table class='table datatable-basic'>
							<thead>
								<tr>
									<th>#</th>
									<th>No Faktur</th>
									<th>Perusahaan</th>
									<th>Pabrik</th>
									<th>Tanggal Faktur</th>
									<th>Tanggal Transaksi</th>
									<th class='text-center'>Status</th>
									<th>Transaksi dibuat oleh</th>
									<th>Transaksi diapprove oleh</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							";
							$no = 0;
							foreach($getData->result() as $data){
								if($data->flag_proses == 0){
									$stSpan = "<span class='label label-default'>BELUM DIPROSES</span>";
								}else{
									$stSpan = "<span class='label label-primary'>SUDAH DIAPPROVE</span>";
								}
								$no++;
								$getDetailProdukBeli = $this->model_produk->getProdukBeliDetailByIdProdukBeli($data->id)->row();
								echo"
								<tr>
									<td>$no</td>
									<td>".$data->notransaction."</td>
									<td>".$getDetailProdukBeli->nama_perusahaan."</td>
									<td>".$data->factory_name."</td>

									<td>".date("d M y",strtotime("+0 day", strtotime($data->faktur_date)))."</td>
									<td>".date("d M y",strtotime("+0 day", strtotime($data->create_date)))."</td>
									<td>".$stSpan."</td>
									<td>".$data->create_user."</td>
									<td>".$data->approve_user."</td>
									<td><a href='".base_url("admin/produk/order_edit/".base64_encode($data->id)."")."' class='btn btn-primary btn-labeled'><b><i class='icon-make-group'></i></b> Detail Data</a><br><br>
										<a href='".base_url("admin/produk/print_data/".base64_encode($data->id)."")."' target='_blank' class='btn btn-success btn-labeled'><b><i class='icon-printer'></i></b> Print Data</a>


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