<?php
$param_jenis = $this->uri->segment(4);
$jenis = base64_decode($this->uri->segment(5));
echo"
<div class='content-wrapper'>
				<!-- Bordered striped table -->
				<div class='panel panel-flat'>
					<div class='panel-heading'>
						<h5 class='panel-title'>Master ".$jenis."</h5>
						<div class='heading-elements'>
							<ul class='icons-list'>
		                		<li><a data-action='collapse'></a></li>
		                		<li><a data-action='reload'></a></li>
		                		 
		                	</ul>
	                	</div>
					</div>

					<div class='panel-body'>
						<a href='".base_url("admin/master/user_add/".$param_jenis."/".base64_encode($jenis)."")."' class='btn btn-info btn'>Tambah ".$jenis."</a>
					</div>
					<div class='table-responsive'>
						<table id='tabel_data' class='table datatable-basic'>
							<thead>
								<tr>
									<th>#</th>
									<th>Username</th>
									<th>KTP</th>
									<th>Email</th>
									<th>No HP</th>
									<th>Aksi</th>
							</thead>
							<tbody>
							";$no =0;
							foreach($getUsers->result() as $user){
								$no++;
								echo"
								<tr>
									<td>$no</td>
									<td>".$user->username."</td>
									<td>".$user->ktp."</td>
									<td>".$user->email."</td>
									<td>".$user->no_hp."</td>
									<td><a href='".base_url("admin/master/user_edit/2/".base64_encode($jenis)."/".base64_encode($user->id)."")."' class='btn btn-primary btn-labeled'><b><i class='icon-pencil'></i></b>Edit</a>
									</td>
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