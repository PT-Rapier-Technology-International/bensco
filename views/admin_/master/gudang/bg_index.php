<?php
echo"
<div class='content-wrapper'>
				<!-- Bordered striped table -->
				<div class='panel panel-flat'>
					<div class='panel-heading'>
						<h5 class='panel-title'>Master Gudang</h5>
						<div class='heading-elements'>
							<ul class='icons-list'>
		                		<li><a data-action='collapse'></a></li>
		                		<li><a data-action='reload'></a></li>
		                		 
		                	</ul>
	                	</div>
					</div>

					<div class='panel-body'>
						<a href='".base_url("admin/master/gudang_add")."' class='btn btn-info btn'>Tambah Gudang</a>
					</div>

					<div class='table-responsive'>
						<table id='tabel_data' class='table datatable-basic'>
							<thead>
								<tr>
									<th>#</th>
									<th>Nama</th>
									<th>Aksi</th>
							</thead>
							<tbody>
							";$no =0;
							foreach($getGudang->result() as $gudang){
								$no++;
								echo"
								<tr>
									<td>$no</td>
									<td>".$gudang->name."</td>
									<td><a href='".base_url("admin/master/gudang_edit/".base64_encode($gudang->id)."")."' class='btn btn-primary btn-labeled'><b><i class='icon-pencil'></i></b>Edit</a></td>
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