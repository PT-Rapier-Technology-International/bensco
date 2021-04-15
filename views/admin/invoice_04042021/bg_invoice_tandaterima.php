<?php

echo"
<!-- Main content -->
<div class='content-wrapper'>

	<!-- Basic datatable -->
	<div class='panel panel-flat'>
		<div class='panel-heading'>
			<h5 class='panel-title'>Halaman Tanda Terima Invoice</h5>
			<div class='heading-elements'>
				<ul class='icons-list'>
            		<li><a data-action='collapse'></a></li>
            		<li><a data-action='reload'></a></li>
            		 
            	</ul>
        	</div>
		</div>
		<div class='panel-heading col-sm-12'>
			<div class='col-sm-1' style='margin-top:1%'>
				No Invoice
			</div>
			<div class='col-sm-2'>
			";
			if(isset($_SESSION['rick_auto']['filter_invoice_no_tt'])){
				$sess = $_SESSION['rick_auto']['filter_invoice_no_tt'];
			}elseif($_SESSION['rick_auto']['filter_invoice_no_tt'] == 0){
				$sess = "";
			}else{
				$sess = "";
			}echo"
				<input type='text' class='form-control' placeholder='No. Invoice' id='invoice_no_tt' name='invoice_no_tt' value='".$sess."'>
			</div>
			<div class='col-sm-1' style='margin-top:1%'>
				No Tanda Terima 
			</div>
			<div class='col-sm-2'>
			";
			if(isset($_SESSION['rick_auto']['filter_no_tt'])){
				$sesstt = $_SESSION['rick_auto']['filter_no_tt'];
			}elseif($_SESSION['rick_auto']['filter_no_tt'] == 0){
				$sesstt = "";
			}else{
				$sesstt = "";
			}echo"
				<input type='text' class='form-control' placeholder='No. Tanda Terima' id='tt_no' name='tt_no' value='".$sesstt."'>
			</div>
			<div class='col-sm-1' style='margin-top:1%'>
				Perusahaan 
			</div>
			<div class='col-sm-3'>
				<div class='multi-select-full'>
					<select class='form-control' id='cmbPerusahaanFilter' name='cmbPerusahaanFilter'>
						<option value='0'>Pilih Perusahaan</option>
						";
						if(isset($_SESSION['rick_auto']['filter_perusahaan_tt'])){
						foreach($getPerusahaan->result() as $perusahaan){
							echo"
						<option value='".$perusahaan->id."' ";if($perusahaan->id == $_SESSION['rick_auto']['filter_perusahaan_tt']){echo"selected";}else{}echo">".$perusahaan->name."</option>";}
					}else{
						foreach($getPerusahaan->result() as $perusahaan){
						echo"
						<option value='".$perusahaan->id."'>".$perusahaan->name."</option>
						";}
					}echo"
					</select>
				</div>
			</div>
			<div class='col-sm-2'>
			<a href='#!' onclick=javascript:filter_tanda_terima(); class='btn btn-primary btn-labeled'><b><i class='icon-search4'></i></b> Cari </a>
			</div>
		</div>
		";
		if($this->uri->segment(4)==""){
			if(isset($_SESSION['rick_auto']['filter_invoice_no_tt']) || isset($_SESSION['rick_auto']['filter_no_tt']) || isset($_SESSION['rick_auto']['filter_perusahaan_tt'])) {
		echo" 
		<table class='table'>
			<thead>
				<tr>
					<th>#</th>
					<th>No Tanda Terima</th>
					<th>No Nota</th>
					<th>Member - Kota</th>
					<th>Perusahaan</th>
					<th>Tanggal dibuat</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>";
			$no = 0;
			foreach($Data->result() as $data){
				$getCekData = $this->model_invoice->getPaymentTandaTerima($data->no_tanda_terima)->row();

				$no++;
				echo"
				<tr>
					<td>$no</td>
					<!--<td>".$data->no_tanda_terima."</td>-->
					<td>".str_replace("PT.E","PT.ETC",$data->no_tanda_terima)."</td>
					<td>
					<table>";
					$getInv = $this->model_invoice->getTandaTerimaByNoTandaTerima($data->no_tanda_terima);
					foreach ($getInv->result() as $inv) {
						echo"
						<tr>
							<td>".$inv->no_nota."</td>
						</tr>
						";
					}
					echo"
					</table>
					</td>
					<td>".$data->nama_member." - ".$data->kota_member."</td>
					<td>".$data->nama_perusahaan."</td>
					<td>".date('d M y H:i', strtotime(date($data->create_date)))."</td>
					<td><a href='".base_url("admin/invoice/proses_tanda_terima/".$data->no_tanda_terima."")."' class='btn btn-success btn-labeled'><b><i class='icon-cash'></i></b> Input Pembayaran</a> <br><br>
					<!-- <a href='#' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:view_print_perusahaan('".$data->no_tanda_terima."') class='btn btn-warning btn-labeled'><b><i class='icon-printer'></i></b> Print Tanda Terima</a> -->
					";
					if($getCekData->flag == 1){

					}else{
						echo"
					<!-- <a href='".base_url("admin/invoice/print_tandaterima/".$data->no_tanda_terima."/".$data->perusahaan_id."")."' target='_blank' class='btn btn-warning btn-labeled'><b><i class='icon-printer'></i></b> Print Tanda Terima</a><br><br> -->
					<a  href='#!' data-toggle='modal' data-target='#modal_pilihan' onclick=javascript:changeTandaTerima('".$data->no_tanda_terima."',".$data->perusahaan_id.") class='btn btn-warning btn-labeled'><b><i class='icon-printer'></i></b> Print Tanda Terima</a><br><br>
					<a href='#' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:view_input_pengiriman('".$data->no_tanda_terima."') class='btn btn-danger btn-labeled'><b><i class='icon-truck'></i></b> Input Data Pengiriman</a> <br><br>";}echo"
					</td>
					
				</tr>";
			}
			echo"
			</tbody>
		</table>";}
	}else{
			echo" 
			<table class='table'>
				<thead>
					<tr>
						<th>#</th>
						<th>No Tanda Terima</th>
						<th>No Nota</th>
						<th>Customer</th>
						<th>Kota</th>
						<th>Perusahaan</th>
						<th>Tanggal dibuat</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>";
				$no = 0;
				foreach($Data->result() as $data){
					$no++;
					echo"
					<tr>
						<td>$no</td>
						<td>".$data->no_tanda_terima."</td>
						<td>
							<table>";
							$getInv = $this->model_invoice->getTandaTerimaByNoTandaTerima($data->no_tanda_terima);
							foreach ($getInv->result() as $inv) {
								echo"
								<tr>
									<td>".$inv->no_nota."</td>
								</tr>
								";
							}
							echo"
							</table>
						</td>
						<td>".$data->nama_member."</td>
						<td>".$data->alamat_member_toko." ".$data->kota_member."</td>
						<td>".$data->nama_perusahaan."</td>
						<td>".date('d M y H:i', strtotime(date($data->create_date)))."</td>
						<td>
						<!-- <a href='#' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:view_print_perusahaan('".$data->no_tanda_terima."') class='btn btn-warning btn-labeled'><b><i class='icon-printer'></i></b> Print Tanda Terima</a> --> 
						<!-- <a href='#' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:view_print_perusahaan('".$data->no_tanda_terima."') class='btn btn-warning btn-labeled'><b><i class='icon-printer'></i></b> Print Tanda Terima</a> -->
						<!-- <a href='".base_url("admin/invoice/print_tandaterima/".$data->no_tanda_terima."/".$data->perusahaan_id."")."' target='_blank' class='btn btn-warning btn-labeled'><b><i class='icon-printer'></i></b> Print Tanda Terima</a><br><br> -->
						<a  href='#!' data-toggle='modal' data-target='#modal_pilihan' onclick=javascript:changeTandaTerima('".$data->no_tanda_terima."',".$data->perusahaan_id.") class='btn btn-warning btn-labeled'><b><i class='icon-printer'></i></b> Print Tanda Terima</a><br><br>
						<!-- <a href='".base_url("admin/invoice/print_tandaterima/".$data->no_tanda_terima."/".$data->perusahaan_id."")."/delivery' target='_blank' class='btn btn-success btn-labeled'><b><i class='icon-printer'></i></b> Print Data Pengiriman</a><br><br> -->
						<a href='#!' data-toggle='modal' data-target='#modal_pilihan' onclick=javascript:changeTandaTerimaDelivery('".$data->no_tanda_terima."',".$data->perusahaan_id.") class='btn btn-success btn-labeled'><b><i class='icon-printer'></i></b> Print Data Pengiriman</a><br><br>
						<a href='#' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:view_input_pengiriman('".$data->no_tanda_terima."') class='btn btn-danger btn-labeled'><b><i class='icon-truck'></i></b> Input Data Pengiriman</a> <br><br>
						</td>
						
					</tr>";
				}
				echo"
				</tbody>
			</table>";
		}echo"
	</div>
	<!-- /basic datatable -->
</div>
<!-- /main content -->
";?>