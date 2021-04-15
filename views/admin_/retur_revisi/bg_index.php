<?php
$uri4 = $this->uri->segment(4);
echo"
<!-- Main content -->
<div class='content-wrapper'>

	<!-- Basic datatable -->
	<div class='panel panel-flat'>
		<div class='panel-heading'>
			<h5 class='panel-title'>Halaman untuk Proses Retur Revisi</h5>
			<div class='heading-elements'>
				<ul class='icons-list'>
            		<li><a data-action='collapse'></a></li>
            		<li><a data-action='reload'></a></li>
            		 
            	</ul>
        	</div>
		</div>
		<div class='col-sm-12'>
			<div class='col-sm-1' style='margin-top:1%'>
				No Invoice
			</div>
			<div class='col-sm-2'>
			";
			if(isset($_SESSION['rick_auto']['filter_invoice_no_rr'])){
				$sess = $_SESSION['rick_auto']['filter_invoice_no_rr'];
			}elseif($_SESSION['rick_auto']['filter_invoice_no_rr'] == 0){
				$sess = "";
			}else{
				$sess = "";
			}echo"
				<input type='text' class='form-control' placeholder='No. Invoice' id='invoice_no' name='invoice_no' value='".$sess."'>
			</div>
			<div class='col-sm-1' style='margin-top:1%'>
				Perusahaan 
			</div>
			<div class='col-sm-3'>
				<div class='multi-select-full'>
					<select class='form-control' id='cmbPerusahaanFilter' name='cmbPerusahaanFilter'>
						<option value='0'>Pilih Perusahaan</option>
						";
						if(isset($_SESSION['rick_auto']['filter_perusahaan_rr'])){
						foreach($getPerusahaan->result() as $perusahaan){
							echo"
						<option value='".$perusahaan->id."' ";if($perusahaan->id == $_SESSION['rick_auto']['filter_perusahaan_rr']){echo"selected";}else{}echo">".$perusahaan->name."</option>";}
					}else{
						foreach($getPerusahaan->result() as $perusahaan){
						echo"
						<option value='".$perusahaan->id."'>".$perusahaan->name."</option>
						";}
					}echo"
					</select>
				</div>
			</div>
			<div class='col-sm-1' style='margin-top:1%'>
				Customer  
			</div>
			<div class='col-sm-2'>
				<select id='cmbMemberFilter' name='cmbMemberFilter' data-placeholder='Pilih Customer' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true'>
						<option value='0'>Pilih Customer</option>
						";
						if(isset($_SESSION['rick_auto']['filter_member_rr'])){
						foreach($getMember->result() as $member){
							echo"
						<option value='".$member->id."' ";if($member->id == $_SESSION['rick_auto']['filter_member_rr']){echo"selected";}else{}echo">".$member->name." - ".$member->nama_kota."</option>";}
						}else{
						foreach($getMember->result() as $member){
							echo"
						<option value='".$member->id."'>".$member->name." - ".$member->nama_kota."</option>";}
						}

						echo"
				</select>
			</div>
		</div>
		<div class='col-sm-12' style='margin-top:2%'>
			<div class='col-sm-1' >
				No Retur Revisi
			</div>
			<div class='col-sm-2'>
			";
			if(isset($_SESSION['rick_auto']['filter_no_retur_revisi'])){
				$sesss = $_SESSION['rick_auto']['filter_no_retur_revisi'];
			}elseif($_SESSION['rick_auto']['filter_no_retur_revisi'] == 0){
				$sesss = "";
			}else{
				$sesss = "";
			}echo"
				<input type='text' class='form-control' placeholder='No. Retur Revisi' id='rr_no' name='rr_no' value='".$sesss."'>
			</div>
			<div class='col-sm-2'>
			<a href='#!' onclick=javascript:filter_retur_revisi(); class='btn btn-primary btn-labeled'><b><i class='icon-search4'></i></b> Cari </a>
			</div>
		</div>

		<table class='table'>
			<thead>
				<tr>
					<th>#</th>
					<th>No Invoice</th>
					<th>Perusahaan</th>
					<th>Customer - Daerah</th>
					<th>No Tanda Terima</th>
					<th>Total Invoice (Rp.)</th>";
					if($_SESSION['rick_auto']['flag_user'] == 1 || $_SESSION['rick_auto']['flag_user'] == 2){
						echo"
					<th colspan='2'>Aksi</th>";
					}echo"
				</tr>
			</thead>
			<tbody>";
			$no = 0;
			foreach($getData->result() as $data){
				$cekHistory = $this->model_invoice->getDetailRevisiReturByInvoiceId($data->id);
				if($cekHistory->num_rows() > 0){
					$iconCekHistory = "<i class='icon-stack-check'></i> ada data log";
				}else{
					$iconCekHistory = "";
				}
				$no++;
				echo"
				<tr>
					<td>$no</td>
					<td><a href='".base_url("admin/invoice/retur_revisi/".base64_encode($data->id)."")."'>".$data->nonota."</a><br>".$iconCekHistory."</td>
					<td>".$data->perusahaan_name."</td>
					<td>".$data->nama_lengkap_member." - ".$data->kota."</td>
					<td>".$data->no_tt."</td>
					<td class='text-right'>".number_format($data->total,2,',','.')."</td>";
					if($data->pay_status == 1){
					echo"
					<td></td>
					<td></td>
					";
					}else{
					if($_SESSION['rick_auto']['flag_user'] == 1 || $_SESSION['rick_auto']['flag_user'] == 2){
						echo"
					<td><a href='".base_url("admin/invoice/proses_retur_revisi/".$data->id."/5/".date('Y-m-d')."")."' class='btn btn-primary'><i class='icon-undo position-left'></i> Retur</button></td>
					<td><a href='".base_url("admin/invoice/proses_retur_revisi/".$data->id."/6/".date('Y-m-d')."")."' class='btn btn-success'><i class='icon-pencil7 position-left'></i> Revisi</button></td>";
						}
					}echo"
					
				</tr>";
			}
			echo"
			</tbody>
		</table>
	</div>
	<!-- /basic datatable -->
</div>
<!-- /main content -->
		<script>
        $('#cmbMemberFilter').select2();
        </script>
";?>