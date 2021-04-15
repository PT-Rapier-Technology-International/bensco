<?php
echo"
<div class='content-wrapper'>
				<!-- Bordered striped table -->
				<div class='panel panel-flat'>
					<div class='panel-heading'>
						<h5 class='panel-title'>Master Kategori</h5>
						<div class='heading-elements'>
							<ul class='icons-list'>
		                		<li><a data-action='collapse'></a></li>
		                		<li><a data-action='reload'></a></li>
		                		 
		                	</ul>
	                	</div>
					</div>

					<div class='panel-body'>
						<a href='".base_url("admin/master/kategori_add")."' class='btn btn-info btn'>Tambah Kategori</a>
					</div>

					<div class='table-responsive'>
						<table id='tabel_data' class='table datatable-basic'>
							<thead>
								<tr>
									<th>#</th>
									<th>Kode</th>
									<th>Name</th>
									<th>Gambar</th>
									<th>Aksi</th>
							</thead>
							<tbody>
							";$no =0;
							foreach($getKategori->result() as $kategori){
								$no++;
								echo"
								<tr>
									<td>$no</td>
									<td>".$kategori->cat_code."</td>
									<td>".$kategori->cat_name."</td>
									<td class='text-center'><img src=".base_url($kategori->cat_image)." width='80px;' height='80px;'></td>
									<td><a href='".base_url("admin/master/kategori_edit/".base64_encode($kategori->id)."")."' class='btn btn-primary btn-labeled'><b><i class='icon-pencil'></i></b>Edit</a></td>
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