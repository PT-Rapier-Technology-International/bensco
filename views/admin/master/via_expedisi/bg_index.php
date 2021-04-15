<?php
echo"
<div class='content-wrapper'>
	<!-- Bordered striped table -->
	<div class='panel panel-flat'>
		<div class='panel-heading'>
			<h5 class='panel-title'>Master Via Expedisi</h5>
			<div class='heading-elements'>
				<ul class='icons-list'>
            		<li><a data-action='collapse'></a></li>
            		<li><a data-action='reload'></a></li>
            		 
            	</ul>
        	</div>
		</div>

		<div class='panel-body'>
			<a href='".base_url("admin/master/via_expedisi_add")."' class='btn btn-info btn'>Tambah Via Expedisi</a>
		</div>

		<div class='table-responsive'>
			<table id='tabel_data' class='table datatable-basic'>
				<thead>
					<tr>
						<th>#</th>
						<th>Nama</th>
						<th>Alamat</th>
						<th>Phone</th>
						<th>Aksi</th>
				</thead>
				<tbody>
				";$no =0;
				foreach($getExpedisi->result() as $expedisi){
					$no++;
					echo"
					<tr>
						<td>$no</td>
						<td>".$expedisi->name."</td>
						<td>".$expedisi->address."</td>
						<td>".$expedisi->telp_no."</td>
						<td><a href='".base_url("admin/master/expedisi_edit/".base64_encode($expedisi->id)."")."' class='btn btn-primary btn-labeled'><b><i class='icon-pencil'></i></b>Edit</a></td>
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