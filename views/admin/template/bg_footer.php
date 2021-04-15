<?php
echo"
		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->


    <!-- Modal with confirmation -->
	<div id='confirmation_modal' class='modal fade'>
		<div class='modal-dialog modal-lg'>
			<form id='formAdd'>
				<div class='modal-content'>
					<div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal'>&times;</button>
						<h5 class='modal-title' id='modal_judul_konten'>Data Modal</h5>
					</div>

					<div class='panel-body no-padding-bottom'>
						<div class='row'>
							<div class='col-md-12' id='konten_body'>

							</div>
						</div>
					</div>

					<div class='modal-footer'>
						<button id='btnSave' type='button' class='btn btn-primary'>Ya</button>
						<button id='btnCancel' type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- /modal with confirmation -->

    <!-- Modal with confirmation -->
	<div id='confirmation_modal_kecil' class='modal fade'>
		<div class='modal-dialog modal-sm'>
			<div class='modal-content'>
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					<h5 class='modal-title'>Data Modal</h5>
				</div>

				<div class='panel-body no-padding-bottom'>
					<div class='row'>
						<div class='col-md-12' id='konten_body_kecil'>

						</div>
					</div>
				</div>

				<div class='modal-footer'>
					<button id='btnSaveKecil' type='button' class='btn btn-primary'>Ya</button>
					<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /modal with confirmation -->

	<!-- Modal Konfirmasi Delete -->

	<div id='modal_delete_data' class='modal fade'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class='modal-header bg-danger'>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					<h6 class='modal-title'>Konfirmasi Hapus Data</h6>
				</div>

				<div class='modal-body' id='body_konfirm_delete'>
					<h6 class='text-semibold'>Apakah Anda yakin ingin menghapus data ini ?</h6>
					<hr>
				</div>

				<div class='modal-footer' id='footer_konfirm_delete'>
					<button type='button' class='btn btn-link' data-dismiss='modal'>Tidak</button>
					<button type='button' id='btnDelete' class='btn btn-danger'>Ya</button>
				</div>
			</div>
		</div>
	</div>

	<!-- End Modal Konfirmasi Delete -->

	<!-- Modal Pilihan -->

	<div id='modal_pilihan' class='modal fade' style='display: none;'>
		<div class='modal-dialog'>
			<div class='modal-content text-center'>
				<div class='modal-header'>
					<h5 class='modal-title' id='title_modal_pilihan'>Modal Pilihan </h5>
				</div>
				<form action='#' class='form-inline'>
					<div class='modal-body' id='pilihanAjax'>
						
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- End Modal Pilihan -->

	<!-- Footer -->
	<div class='navbar navbar-default navbar-fixed-bottom footer'>
		<ul class='nav navbar-nav visible-xs-block'>
			<li><a class='text-center collapsed' data-toggle='collapse' data-target='#footer'><i class='icon-circle-up2'></i></a></li>
		</ul>

		<div class='navbar-collapse collapse' id='footer'>
			<div class='navbar-text'>
				&copy; 2019. BENSCO
			</div>

			<div class='navbar-right'>
				<ul class='nav navbar-nav'>
					<li><a href='#'>About</a></li>
					<li><a href='#'>Terms</a></li>
					<li><a href='#'>Contact</a></li>
				</ul>
			</div>
		</div>
	</div>
	<!-- /footer -->
<script>
var base_url = '".base_url()."admin/';
</script>

";
if(!empty($scjav)){
    echo "<script src='".site_url($scjav)."' type='text/javascript'></script>";
}

if($this->uri->segment(2) == "produk"){
echo"
<script src='".base_url("assets/jController/CtrlGlobal.js")."' type='text/javascript'></script>";}echo"
<script>
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    return !(charCode > 31 && (charCode < 48 || charCode > 57));
}
</script>
</body>
</html>
";?>