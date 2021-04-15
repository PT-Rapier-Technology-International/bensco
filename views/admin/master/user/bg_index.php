<?php
$param_jenis = $this->uri->segment(4);
$jenis = base64_decode($this->uri->segment(5));
$jenis_user = base64_decode($this->uri->segment(6));
$jenis_users = $this->uri->segment(6);
echo"
<div class='content-wrapper'>
				<!-- Bordered striped table -->
				<div class='panel panel-flat'>
					<div class='panel-heading'>
						<h5 class='panel-title'>Master ".$jenis." ".$jenis_user."</h5>
						<div class='heading-elements'>
							<ul class='icons-list'>
		                		<li><a data-action='collapse'></a></li>
		                		<li><a data-action='reload'></a></li>
		                		 
		                	</ul>
	                	</div>
					</div>

					<div class='panel-body'>
						<a href='".base_url("admin/master/user_add/".$param_jenis."/".base64_encode($jenis)."")."' class='btn btn-info btn'>Tambah ".$jenis." ".$jenis_user."</a>
					</div>
					";
					if($jenis == "User"){
						echo"
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
									<td><!-- <a href='#!' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:manage_sales_member('".$user->id."') class='btn btn-primary btn-labeled'><b><i class='icon-gear'></i></b>Sales</a> -->
									
									<a href='".base_url("admin/master/user_edit/".$param_jenis."/".base64_encode($jenis)."/".base64_encode($user->id)."")."' class='btn btn-primary btn-labeled'><b><i class='icon-pencil'></i></b>Edit</a>
									<a href='#' data-toggle='modal' data-target='#modal_delete_data' onclick=javascript:confirm_delete_user(".$user->id.") class='btn btn-danger btn-labeled'><b><i class='icon-trash'></i></b>Hapus</a>
									</td>
								</tr>";
							}
							echo"
							</tbody>
						</table>
					</div>";}else{
						echo"
					<div class='table-responsive'>
						<table id='tabel_data' class='table datatable-basic'>
							<thead>
								<tr>
									<th>#</th>
									<th>Username</th>
									<th>Kota</th>
									<th>KTP</th>
									<th>Email</th>
									<th>Tipe Harga (Rumus)</th>
									<th>Aksi</th>
							</thead>
							<tbody>
							";$no =0;
							foreach($getUsers->result() as $user){
								$no++;
								echo"
								<tr>
									<td>$no</td>
									<td>".$user->name."</td>
									<td>".$user->city."</td>
									<td>".$user->ktp."</td>
									<td>".$user->email."</td>
									<td>".$user->angka."% (".$user->rumus.")</td>
									<td><a href='#!' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:manage_sales_member('".$user->id."') class='btn btn-primary btn-labeled'><b><i class='icon-gear'></i></b>Sales</a> <br>
									<a href='".base_url("admin/master/user_edit/".$param_jenis."/".base64_encode($jenis)."/".base64_encode($user->id)."")."' class='btn btn-primary btn-labeled'><b><i class='icon-pencil'></i></b>Edit</a> <br>
									<a href='#' data-toggle='modal' data-target='#modal_delete_data' onclick=javascript:confirm_delete_member(".$user->id.") class='btn btn-danger btn-labeled'><b><i class='icon-trash'></i></b>Hapus</a>
									</td>
								</tr>";
							}
							echo"
							</tbody>
						</table>
					</div>
						";
					}echo"
				</div>
				<!-- /bordered striped table -->
</div>";
?>