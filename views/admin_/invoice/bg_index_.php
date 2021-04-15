<?php
$status = $this->uri->segment(5);
$member = base64_decode($this->uri->segment(4));
$flag = $this->uri->segment(5);
echo"
<!-- Main content -->
<div class='content-wrapper'>

	<!-- Invoice grid options -->
	<div class='navbar navbar-default navbar-xs navbar-component'>
		<ul class='nav navbar-nav no-border visible-xs-block'>
			<li><a class='text-center collapsed' data-toggle='collapse' data-target='#navbar-filter'><i class='icon-menu7'></i></a></li>
		</ul>

		<div class='navbar-collapse collapse' id='navbar-filter'>
			<!-- <p class='navbar-text'>Filter:</p>
			<ul class='nav navbar-nav'>
				<li class='dropdown'>
					<a href='#' class='dropdown-toggle' data-toggle='dropdown'><i class='icon-sort-amount-desc position-left'></i> By status <span class='caret'></span></a>
					<ul class='dropdown-menu'>
						<li><a href='#'>Show all</a></li>
						<li class='divider'></li>
						<li><a href='#'>Lunas</a></li>
						<li><a href='#'>Belum Lunas</a></li>
					</ul>
				</li>

			</ul> -->

				<p class='navbar-text'>Filter Tanggal:</p>
					<ul class='nav navbar-nav'>
						<li class='dropdown'>
							<div style='margin-top:1%'>
								<div class='col-md-5'>
									<input type='date' class='form-control' placeholder='tanggal' id='start_date' name='start_date'>
								</div>
								<div class='col-md-5'>
									<input type='date' class='form-control' placeholder='tanggal' id='end_date' name='end_date'>
								</div>
								<div class='col-md-2'>
								<a href='#!' onclick=javascript:filter_date(); class='btn btn-primary btn-labeled'><b><i class='icon-search4'></i></b> Cari </a>
								</div>
							</div>
						</li>
					</ul>

			<div class='navbar-right'>
				<p class='navbar-text'>Sorting:</p>
				<ul class='nav navbar-nav'>
					<li class='active'><a href='#'><i class='icon-sort-alpha-asc position-left'></i> Asc</a></li>
					<li><a href='#'><i class='icon-sort-alpha-desc position-left'></i> Desc</a></li>
				</ul>
			</div>
		</div>
	</div>
	<!-- /invoice grid options -->


	<!-- Invoice grid -->
	<div class='text-center content-group text-muted content-divider'>
		<!-- <span class='pt-10 pb-10'>Today</span> -->
	</div>";
	if($status == 1){
		echo"
	<div class='col-sm-12'>
		<div class='row col-sm-3'>
			<div class='btn-group pull-left' style='margin-bottom:30px'>
				<div class='btn-group btn-group-fade'>
		            <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>Print Tanda Terima <span class='caret'></span></button>
					<ul class='dropdown-menu'>
					";foreach($getPerusahaan->result() as $perusahaan){
						echo"
						<li><a href='".base_url("admin/invoice/print_tandaterima/".$this->uri->segment(4)."/".$perusahaan->id."")."' target='_blank'><i class='icon-printer'></i> ".$perusahaan->name."</a></li>";}
						echo"
					</ul>
		        </div>
			</div>
		</div>
		<!-- <div class='row col-sm-3'>
			<div class='btn-group pull-left' style='margin-bottom:30px'>
				<div class='btn-group btn-group-fade'>
		            <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>Print Surat Jalan <span class='caret'></span></button>
					<ul class='dropdown-menu'>
					";foreach($getPerusahaan->result() as $perusahaan){
						echo"
						<li><a href='".base_url("admin/invoice/print_surat_jalan/".$this->uri->segment(4)."/".$perusahaan->id."")."' target='_blank'><i class='icon-printer'></i> ".$perusahaan->name."</a></li>";}
						echo"
					</ul>
		        </div>
			</div>
		</div>-->
	";}
	foreach($getInvoice->result() as $invoice){
		echo"

		<div class='col-md-12'>
			<div class='panel invoice-grid'>
				<div class='panel-body'>
					<div class='row'>
						<div class='col-sm-12'>
							<div class='col-sm-7'>
							<h6 class='text-semibold no-margin-top'>".$invoice->member_name."</h6>
							<ul class='list list-unstyled'>
								<li>Invoice #: &nbsp;".$invoice->nonota."</li>
								<li>Dibuat pada : <span class='text-semibold'>".date("d M Y",strtotime("+0 day", strtotime($invoice->dateorder)))."</span></li>
							</ul>
							</div>
							<div class='col-sm-5'>";
							if($invoice->pay_status == 0){
								if(date("Y-m-d") >= $invoice->min_duedate){echo"
								<span class='label label-block label-warning text-left'>Peringatan! Invoice ini sudah harus dilunasi sebelum ".date("d M Y",strtotime("+0 day", strtotime($invoice->duedate)))."
								</span>";
								}else{
									//echo"tanggal sekarang ".date("Y-m-d")."";
								}
							}
							echo"
							</div>
						</div>

						<div class='col-sm-12'>
							<h6 class='text-semibold text-right no-margin-top'>Rp. ".number_format($invoice->total,2,',','.')."</h6>";
							if($invoice->pay_status == 0){
								$st_inv = "Belum Lunas";
								$st_ic = "warning";
								$st_act_bl = "class='active'";
								$st_act_l = "";
								$st_act_c = "";
							}elseif($invoice->pay_status == 1){
								$st_inv = "Lunas";
								$st_ic = "success";
								$st_act_bl = "";
								$st_act_l = "class='active'";
								$st_act_c = "";
							}else{
								$st_inv = "Batal";
								$st_ic = "danger";
								$st_act_bl = "";
								$st_act_l = "";
								$st_act_c = "class='active'";
							}echo"
							<ul class='list list-unstyled text-right'>
								<li>Metode Pembayaran: <span class='text-semibold'>Transfer</span></li>
								<li class='dropdown'>
									Status: &nbsp;
									<a id='statusNota_".$invoice->id."' href='#' class='label bg-".$st_ic."-400'>".$st_inv."</a>
									<!-- <a id='statusNota_".$invoice->id."' href='#' class='label bg-".$st_ic."-400 dropdown-toggle' data-toggle='dropdown'>".$st_inv." <span class='caret'></span></a>
									 <ul class='dropdown-menu dropdown-menu-right'>
										<li id='stInvoicebl_".$invoice->id."' ".$st_act_bl."><a href='#' onclick=javascript:ubah_status(0,".$invoice->id.")><i class='icon-alert'></i> Belum Lunas</a></li>
										<li id='stInvoicel_".$invoice->id."' ".$st_act_l."><a href='#' onclick=javascript:ubah_status(1,".$invoice->id.")><i class='icon-checkmark3'></i> Lunas</a></li>
										<li id='stInvoicec_".$invoice->id."' ".$st_act_c."><a href='#' onclick=javascript:ubah_status(2,".$invoice->id.")><i class='icon-cross2'></i> Canceled</a></li>
									</ul> -->
								</li>
							</ul>
						</div>
					</div>
				</div>

				<div class='panel-footer panel-footer-condensed'>
					<div class='heading-elements'>
						<span class='heading-text'>";
						$data_inv = date("Y-m-d H:i:s",strtotime("+0 day", strtotime($invoice->dateorder)));
						$data_inv_due = date("Y-m-d",strtotime("+0 day", strtotime($invoice->duedate)));
            			$masa = $this->template->xTimeAgoDesc($data_inv,date("Y-m-d H:i:s"));
            			//$jajal = date("Y-m-d") - date("Y-m-d",strtotime("+0 day", strtotime($invoice->duedate))); 
            			$jajal = $this->template->xTimeAgo(date("Y-m-d"),$data_inv_due); 

            			//echo "jajal".$jajal;
            	echo"
							<span class='status-mark border-danger position-left'></span><span class='text-semibold'>".$masa."</span>
						</span>

						<ul class='list-inline list-inline-condensed heading-text pull-right'>
							<li><a href='#' class='text-default' data-toggle='modal' data-target='#invoice' onclick=javascript:detail_invoice(".$invoice->id.")><i class='icon-eye8'></i></a></li>
							<li class='dropdown'>
								<a href='#' class='text-default dropdown-toggle' data-toggle='dropdown'><i class='icon-menu7'></i> <span class='caret'></span></a>
								<ul class='dropdown-menu dropdown-menu-right'>
									<li><a href='".base_url("admin/invoice/print_invoice/".base64_encode($invoice->id)."")."' target='_blank'><i class='icon-printer'></i> Print invoice</a></li>
									<li><a href='#!' data-toggle='modal' data-target='#modal_pilihan' onclick=javascript:pilihMenu('".$this->uri->segment(4)."',".$invoice->perusahaan_id.",".$invoice->id.")><i class=' icon-files-empty'></i>Surat Jalan</a></li>
									<li class='divider'></li>
									<li><a href='#'><i class='icon-file-plus'></i> Edit invoice</a></li>
									<li><a href='#'><i class='icon-cross2'></i> Remove invoice</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>";}
		echo"
		
	
	";
	if($status == 0){
	$total_inv = $this->db->query("select sum(total) as total_invoice, count('*') as jumlah_invoice from invoice where member_id = '".base64_decode($this->uri->segment(4))."' and pay_status = 0")->row();
	echo"
	<table width='100%' class='table table-hover'>
			<tr>
				<td colspan='2'><h5><b>TOTAL SEMUA</b></h5></td>
				<td class='text-right'><h5><b>Rp. ".number_format($total_inv->total_invoice,2,',','.')."</b></h5></td>
			</tr>";
			if($getTotalPaymentInvoice->num_rows() > 0){
				$sudah_terbayar = $getTotalPaymentInvoice->row()->sudah_dibayar;
			}else{
				$sudah_terbayar = 0;
			}
			$sisa = $total_inv->total_invoice - $sudah_terbayar;
			echo"
			<tr>
				<td colspan='2'><h5><b>Sudah Dibayar</b></h5></td>
				<td class='text-right'><h5><b>Rp. ".number_format($sudah_terbayar,2,',','.')."</b></h5></td>
			</tr>
			<tr>
				<td colspan='2'><h5><b>Sisa Pembayaran</b></h5></td>
				<td class='text-right'><h5><b>Rp. ".number_format($sisa,2,',','.')."</b></h5></td>
				<input type='hidden' id='sisa_pembayaran' name='sisa_pembayaran' value='".$sisa."'>
			</tr>
	</table>";
	if($total_inv->total_invoice == 0){
	}else{
		echo"
	<hr>
	<div class='panel-body'>
		<fieldset>
			<legend class='text-semibold'>Form Input Pembayaran</legend>
			<div class='form-group'>
				<label class='col-lg-2 control-label'>Jenis Pembayaran</label>
				<div class='col-lg-10'>
					<div class='row'>
						<div class='col-md-3'>
							<select class='form-control' id='cmbPembayaran' name='cmbPembayaran'>
								<option value='0' selected>Pilih Jenis Pembayaran</option>
								";foreach($getPayments->result() as $payment){
									echo"
									<option value=".$payment->id.">".$payment->name."</option>
									";
								}echo"
							</select>
						</div>
						<label class='col-lg-1 control-label'>Tanggal</label>
						<div class='col-md-3'>
							<input class='form-control' id='tanggal' name='tanggal' type='date' >
						</div>
						<label class='col-lg-1 control-label'>Nilai</label>
						<div class='col-md-2'>
							<input type='text' class='form-control' id='rupiah_input' name='nilai'>
						</div>
						<input type='hidden' id='member_id' name='member_id' value='".base64_decode($this->uri->segment(4))."'>
						<div class='col-md-2'>
							<button type='button' class='btn btn-primary' onclick=javascript:simpan_pembayaran()>Simpan <i class='icon-arrow-right14 position-right'></i></button>
						</div>
					</div>
				</div>
			</div>
		</fieldset>
	</div>";}}
	echo "</div>";
	if($status == 1){
		echo"
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

					<table class='table datatable-basic'>
						<thead>
							<tr>
								<th>#</th>
								<th>Nama Customer</th>
								<th>Kota</th>
								<th>Total Pembayaran</th>
								<th>Sudah Dibayar</th>
								<th>Sisa Pembayaran</th>
								<th>Tanggal</th>
							</tr>
						</thead>
						<tbody>";
						$no=0;
						foreach($getPiutang->result() as $piutang){
							$no++;
							echo"
							<tr>
								<td>$no</td>
								<td>".$piutang->nama_member."</td>
								<td>".$piutang->kota_member."</td>
								<td class='text-right'>".number_format($piutang->total_pembayaran,2,',','.')."</td>
								<td class='text-right'>".number_format($piutang->sudah_dibayar,2,',','.')."</td>
								<td class='text-right'>".number_format($piutang->sisa,2,',','.')."</td>
								<td>".date("d M y",strtotime("+0 day", strtotime($piutang->payment_date)))."</td>
							</tr>";}echo"
						</tbody>
					</table>
				</div>
				<!-- /basic datatable -->

			</div>
			<!-- /main content -->

		";
	}
	echo"


	<!-- Pagination -->
	<!-- <div class='text-center content-group-sm pt-20'>
		<ul class='pagination'>
			<li class='disabled'><a href='#'><i class='icon-arrow-small-left'></i></a></li>
			<li class='active'><a href='#'>1</a></li>
			<li><a href='#'>2</a></li>
			<li><a href='#'>3</a></li>
			<li><a href='#'>4</a></li>
			<li><a href='#'>5</a></li>
			<li><a href='#'><i class='icon-arrow-small-right'></i></a></li>
		</ul>
	</div> -->
	<!-- /pagination -->


    <!-- Modal with invoice -->
	<div id='invoice' class='modal fade'>
		<div class='modal-dialog modal-full'>
			<div class='modal-content' id='ajaxInvoice'>
				
			</div>
		</div>
	</div>
	<!-- /modal with invoice -->

</div>
<!-- /main content -->
";?>