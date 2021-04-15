<?php
echo"
<div class='content-wrapper'>
				<!-- Bordered striped table -->
				<div class='panel panel-flat'>
					<div class='panel-heading'>
						<h5 class='panel-title'>History Adjusment Stok</h5>
						<div class='heading-elements'>
							<ul class='icons-list'>
		                		<li><a data-action='collapse'></a></li>
		                		<li><a data-action='reload'></a></li>
		                		 
		                	</ul>
	                	</div>
					</div>

					<div class='panel-body'>
												<form class='form-horizontal' action='#'>
							<fieldset class='content-group'>

								<div class='form-group'>
									<label class='control-label col-lg-1'>Pilih Perusahaan</label>
									<div class='col-lg-2'>
										<select class='form-control' id='cmbPerusahaanFilter' name='cmbPerusahaanFilter' >
														<option value='0' disabled selected>Pilih Perusahaan</option>
														";
													if(isset($_SESSION['rick_auto']['filter_perusahaan_la'])){
														foreach($getPerusahaan->result() as $perusahaan){
															echo"
														<option value='".$perusahaan->id."' ";if($perusahaan->id == $_SESSION['rick_auto']['filter_perusahaan_la']){echo"selected";}else{}echo">".$perusahaan->name."</option>";}
													}else{
														foreach($getPerusahaan->result() as $perusahaan){
														echo"
														<option value='".$perusahaan->id."'>".$perusahaan->name."</option>
														";}
													}echo"
													</select>
									</div>
									<label class='control-label col-lg-1'>Gudang</label>
									<div class='col-lg-2'>
										<div class='multi-select-full' style='width:150px'>
										<select class='form-control select2' id='cmbGudangFrom' name='cmbGudangFrom'>
										<option value='0' disabled selected>Pilih Gudang</option>
											";
											if(isset($_SESSION['rick_auto']['filter_gudangfrom_la'])){
														foreach($getGudang->result() as $gudang){
															echo"
														<option value='".$gudang->id."' ";if($gudang->id == $_SESSION['rick_auto']['filter_gudangfrom_la']){echo"selected";}else{}echo">".$gudang->name."</option>";}
													}else{
											foreach($getGudang->result() as $gudang){
												echo"
											<option value='".$gudang->id."'>".$gudang->name."</option>";}}echo"
										</select>
										</div>
									</div>
									<label class='control-label col-lg-1'>Dari Tanggal </label>
									";
											if(isset($_SESSION['rick_auto']['filter_start_date_la'])){
												$val_tanggal_from = $_SESSION['rick_auto']['filter_start_date_la'];
											}else{
												$val_tanggal_from = "";
											}
											echo"

									<div class='col-lg-2'>
										<input type='date' class='form-control' placeholder='tanggal' id='tanggalFrom' name='tanggalFrom' placholder='Tanggal' value='".$val_tanggal_from."'>
									</div>
									<label class='control-label col-lg-1'>Sampai Tanggal </label>
									";
												if(isset($_SESSION['rick_auto']['filter_end_date_la'])){
													$val_tanggal_to = $_SESSION['rick_auto']['filter_end_date_la'];
												}else{
													$val_tanggal_to = "";
												}
												echo"
									<div class='col-lg-2'>
										<input type='date' class='form-control' placeholder='tanggal' id='tanggalTo' name='tanggalTo' placholder='Tanggal' value='".$val_tanggal_to."'>
									</div>
								</div>
								
								
								<div class='col-lg-2'>
									<a href='#!' onclick=javascript:filterLogAdjustment() class='btn btn-default btn-labeled'><b><i class='icon-search4'></i></b> Cari Data </a>
								</div>
									<div class='col-sm-2'>
											<a href='".base_url("admin/produk/log_adjustment_export/excel")."' class='btn btn-success btn-labeled'><b><i class='icon-printer'></i></b> Export Excel </a>
									</div>
									<div class='col-sm-2'>
										<a href='".base_url("admin/produk/log_adjustment_export/print")."' target='_blank' class='btn btn-primary btn-labeled'><b><i class='icon-printer'></i></b> Print </a>
									</div>
									<div class='col-sm-2'>
										<a href='".base_url("admin/produk/log_adjustment_export/pdf")."' target='_blank' class='btn btn-primary btn-danger'><b><i class='icon-printer'></i></b> Export PDF </a>
									</div>
								</div>
							</fieldset>
						</form>
					

					<div class='table-responsive'>
						<table class='table'>
							<thead>
								<tr>
									<th>#</th>
									<th>Produk</th>
									<th>Perusahaan</th>
									<th>Gudang</th>
									<th>Stock Lama</th>
									<th>Stock Adjusment</th>
									<th>Stock Baru</th>
									<th>Catatan</th>
									<th>Tanggal Adjusment</th>
									<th>Transaksi dibuat</th>
							</thead>
							<tbody>
							";
							$no = 0;
							foreach($getData->result() as $data){
								$no++;
								echo"
								<tr>
									<td>$no</td>
									<td>".$data->nama_produk."</td>
									<td>".$data->nama_perusahaan."</td>
									<td>".$data->nama_gudang."</td>
									<td>".$data->qty_product." ".$data->nama_satuan."</td>
									<td>".$data->stock_add." ".$data->nama_satuan."</td>";
									$newStok = $data->qty_product + $data->stock_add;
									echo"
									<td>".$newStok."  ".$data->nama_satuan."</td>
									<td>".$data->note."</td>
									<td>".date("d M Y H:i",strtotime("+0 day", strtotime($data->create_date)))."</td>
									<td>".$data->create_user."</td>
								</tr>
								";}
								echo"
							</tbody>
						</table>
					</div>
				</div>
				<!-- /bordered striped table -->
			</div>
</div>";
?>