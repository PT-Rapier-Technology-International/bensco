<?php
echo"
<div class='content-wrapper'>
				<!-- Bordered striped table -->
				<div class='panel panel-flat'>
					<div class='panel-heading'>
						<h5 class='panel-title'>Master Satuan</h5>
						<div class='heading-elements'>
							<ul class='icons-list'>
		                		<li><a data-action='collapse'></a></li>
		                		<li><a data-action='reload'></a></li>
		                		 
		                	</ul>
	                	</div>
					</div>

					<div class='panel-body'>
						<a href='".base_url("admin/master/satuan_add")."' class='btn btn-info btn'>Tambah Satuan</a>
					</div>

					<div class='table-responsive'>
						<table id='tabel_data' class='table datatable-basic'>
							<thead>
								<tr>
									<th>#</th>
									<th>Nama</th>
									<th>Jenis</th>
									<th>Aksi</th>
							</thead>
							<tbody>
							";$no =0;
							foreach($getSatuan->result() as $satuan){
								$no++;
								if($satuan->flag_jenis == 1){
									$jenis = "Ecer";
								}else{
									$jenis = "Grosir";
								}
								echo"
								<tr>
									<td>$no</td>
									<td>".$satuan->name."</td>
									<td>".$jenis."</td>
									<td><a href='".base_url("admin/master/satuan_edit/".base64_encode($satuan->id)."")."' class='btn btn-primary btn-labeled'><b><i class='icon-pencil'></i></b>Edit</a></td>
								</tr>";
							}
							echo"
							</tbody>
						</table>
					</div>
				</div>
				<!-- /bordered striped table -->
</div>";
?>