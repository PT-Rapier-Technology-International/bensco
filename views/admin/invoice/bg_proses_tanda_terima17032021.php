<?php

echo"
<!-- Main content -->
<div class='content-wrapper'>

	<!-- Basic datatable -->
	<div class='panel panel-flat'>
		<div class='panel-heading'>
			<h5 class='panel-title'>Halaman No Tanda Terima <b>".str_replace("PT.E","PT.ETC",$this->uri->segment(4))."</b> Customer <b>".$getPiutang->row()->nama_member." - ".$getPiutang->row()->kota_member."</b></h5>
			<div class='heading-elements'>
				<ul class='icons-list'>
            		<li><a data-action='collapse'></a></li>
            		<li><a data-action='reload'></a></li>

            	</ul>
        	</div>
		</div>

		<table class='table datatable-basic'>
			<thead>
				<tr>
					<th>#</th>
					<th>No Tanda Terima</th>
					<th>Total Nota (Rp.)</th>
				</tr>
			</thead>
			<tbody>";
			$no = 0;
			$total = 0;
			foreach($Data->result() as $data){

				//$total_pembayaran = $this->model_invoice->getTotalInvoiceByInvoice($data->id_nota)->row()->total_harga;
				$total_pembayaran = $data->total_nota;
				//$total_pembayaran = $data->nilai;
				$total = $total + $total_pembayaran;
				$no++;
				echo"
				<tr>
					<td>$no</td>
					<td><a href='".base_url("admin/invoice/retur_revisi/".base64_encode($data->id_nota)."")."'>".str_replace("PT.E","PT.ETC",$data->no_nota)."</a></td>
					<td class='text-right'>".number_format(ceil($total_pembayaran),2,',','.')."</td>

				</tr>";
			}
			echo"
				<tr>
					<td><h4>Total Nota</h4></td>
					<td colspan='2' class='text-right'><h4>".number_format($total,2,',','.')."</h4></td>
				</tr>
			</tbody>
		</table>
	</div>";
	//$total_inv = $this->db->query("select sum(total) as total_invoice, count('*') as jumlah_invoice from invoice where member_id = '".base64_decode($this->uri->segment(4))."' and pay_status = 0 and flag_tanda_terima = 0 ".$tanggal_filter." ".$sales_filter."")->row();

	echo"
	<table width='100%' class='table table-hover'>
			<tr>
				<td colspan='2'><h5><b>TOTAL SEMUA</b></h5></td>
				<td class='text-right'><h5><b>Rp. ".number_format($total,2,',','.')."</b></h5></td>
			</tr>";
			if($getTotalPaymentInvoice->num_rows() > 0){
				$sudah_terbayar = $getTotalPaymentInvoice->row()->sudah_dibayar;
			}else{
				$sudah_terbayar = 0;
			}
			$sisa = $total - $sudah_terbayar;
			echo"
			<tr>
				<td colspan='2'><h5><b>Sudah Dibayar</b></h5></td>
				<td class='text-right'><h5><b>Rp. ".number_format($sudah_terbayar,2,',','.')."</b></h5></td>
			</tr>";
			if($sisa <= 5000){
			echo"
			<tr>
				<td colspan='2'><h5><b>Sisa Pembayaran</b></h5></td>
				<td class='text-right'><h5><b>Rp. 0</b></h5></td>
				<input type='hidden' id='sisa_pembayaran' name='sisa_pembayaran' value='0'>
			</tr>";
			}else{
				echo"
			<tr>
				<td colspan='2'><h5><b>Sisa Pembayaran</b></h5></td>
				<td class='text-right'><h5><b>Rp. ".number_format($sisa,2,',','.')."</b></h5></td>
				<input type='hidden' id='sisa_pembayaran' name='sisa_pembayaran' value='".$sisa."'>
			</tr>";}echo"
	</table>
	";
	if($sisa == 0 || $sisa <= 5000){
		}else{
		echo"
	<div class='panel-body'>
		<fieldset>
			<legend class='text-semibold'>Form Input Pembayaran</legend>
			<div class='form-group'>
				<label class='col-lg-2 control-label'>Jenis Pembayaran</label>
				<div class='col-lg-10'>
					<div class='row'>
						<div class='col-md-3'>
							<select class='form-control' id='cmbPembayaran' name='cmbPembayaran' onchange=javascript:pilihPembayaran()>
								<option value='0' selected disabled>Pilih Jenis Pembayaran</option>
								";foreach($getPayments->result() as $payment){
									echo"
									<option value=".$payment->id.">".$payment->name."</option>
									";
								}echo"
							</select>
						</div>
						<label class='col-lg-1 control-label'>Tanggal</label>
						<div class='col-md-3'>
							<input class='form-control' id='tanggal' name='tanggal' type='date' value='".date('Y-m-d')."' >
						</div>
						<div id='tmpNilai'>
						<label class='col-lg-1 control-label'>Nilai</label>
						<div class='col-md-2'>
							<input type='text' class='form-control' id='rupiah_input' name='nilai'>
						</div>
						</div>
						<div id='tmpInvoice' style='display:none'>
						<label class='col-lg-1 control-label'>No Retur/Revisi</label>
						<div class='col-md-2'>
							<select class='form-control' id='cmbRetRev' name='cmbRetRev'>
								<option value='0' selected disabled>Pilih No. Rev/Ret</option>
								";
								$DataRevRet = $this->model_invoice->getCekReturRevisiPembayaran();
								foreach($DataRevRet->result() as $revret){
								echo"
								<option value='".$revret->nomor_retur_revisi."'>".$revret->nomor_retur_revisi."</option>
								";
								 }echo"

							</select>
						</div>
						</div>
						<input type='hidden' id='member_id' name='member_id' value='".$Data->row()->member_id."'>
						<div class='col-md-2'>
							<button id='btnPembayaran' type='button' class='btn btn-primary' onclick=javascript:simpan_pembayaran('".$this->uri->segment(4)."')>Simpan <i class='icon-arrow-right14 position-right'></i></button>
						</div>
					</div>
				</div>
			</div>
			<br>
			<div class='form-group' id='tempatGiroCek' style='display:none'>
				<label class='col-lg-2 control-label'>Tanggal Cair</label>
				<div class='col-lg-10'>
					<div class='row'>
						<div class='col-md-3'>
							<input class='form-control' id='tanggal_cair' name='tanggal_cair' type='date' value='".date('Y-m-d')."' >
						</div>
						<label class='col-lg-1 control-label'>No. Giro/No. Cek</label>
						<div class='col-md-3'>
							<input class='form-control' id='nomor_giro_cek' name='nomor_giro_cek' type='text' >
						</div>
						<label class='col-lg-1 control-label'>Nama Giro/Nama Cek</label>
						<div class='col-md-2'>
							<input type='text' class='form-control' id='nama_giro_cek' name='nama_giro_cek'>
						</div>
					</div>
				</div>
			</div>
		</fieldset>
	</div>";}echo"


		<!-- Main content -->
	<div class='content-wrapper col-sm-12'>

		<!-- Basic datatable -->
		<div class='panel panel-flat'>
			<div class='panel-heading'>
				<h5 class='panel-title'>Data Piutang Customer</h5>
				<div class='heading-elements'>
					<ul class='icons-list'>
                		<li><a data-action='collapse'></a></li>
                		<li><a data-action='reload'></a></li>

                	</ul>
            	</div>
			</div>

			<div class='panel-body'>

			</div>

			<table class='table datatable-basic' width='100%'>
				<thead>
					<tr>
						<th>#</th>
						<th>Jenis Pembayaran</th>
						<th>Nama Giro/Cek</th>
						<th>No Giro/Cek</th>
						<th>Kota</th>
						<th>Total Pembayaran</th>
						<th>Sudah Dibayar</th>
						<th>Sisa Pembayaran</th>
						<th>Tanggal Setoran</th>
						<th>Tanggal Cair</th>
					</tr>
				</thead>
				<tbody>";
				$no=0;
				foreach($getPiutang->result() as $piutang){
					if ($piutang->sudah_dibayar == "0" || $piutang->sudah_dibayar == 0){
						echo "<td></td>";
					} else {
					$no++;
					echo"
					<tr>
						<td>$no</td>
						<td>".$piutang->jenis_pembayaran."</td>
						<td>".$piutang->name."</td>
						<td>".$piutang->number."</td>
						<td>".$piutang->kota_member."</td>
						<td class='text-right'>".number_format($piutang->total_pembayaran,2,',','.')."</td>
						<td class='text-right'>".number_format($piutang->sudah_dibayar,2,',','.')."</td>
						<td class='text-right'>".number_format($piutang->sisa,2,',','.')."</td>
						<td>".date("d M y",strtotime("+0 day", strtotime($piutang->payment_date)))."</td>";
						if($piutang->jenis_pembayaran == "Giro"){
							echo"
						<td>".date("d M y",strtotime("+0 day", strtotime($piutang->liquid_date)))."</td>";}
						else{
							echo"<td></td>";
						}echo"
					</tr>";}}echo"
				</tbody>
			</table>
		</div>
		<!-- /basic datatable -->

	</div>
	<!-- /main content -->

	<!-- /basic datatable -->
</div>
<!-- /main content -->
";?>
