<?php

$uri4 = $this->uri->segment(4);

// $status = $this->uri->segment(5);

// $member = base64_decode($this->uri->segment(4));

// $flag = $this->uri->segment(5);

if(isset($_SESSION['rick_auto']['filter_bulan'])){

    $bulan_sekarang = $_SESSION['rick_auto']['filter_bulan'];

}else{

    $bulan_sekarang = date('m', strtotime(date('Y-m-d')));

}

echo"



<!-- Main content -->

<div class='content-wrapper'>



	<!-- Invoice grid options -->

	<div class='navbar navbar-default navbar-xs navbar-component'>

		<ul class='nav navbar-nav no-border visible-xs-block'>

			<li><a class='text-center collapsed' data-toggle='collapse' data-target='#navbar-filter'><i class='icon-menu7'></i></a></li>

		</ul>

";

if(isset($_SESSION['rick_auto']['filter_start_date']) && $_SESSION['rick_auto']['filter_end_date']){

			$tanggal_start = $_SESSION['rick_auto']['filter_start_date'];

			$tanggal_end = date("Y-m-d",strtotime("-1 day", strtotime($_SESSION['rick_auto']['filter_end_date'])));

}else{

			$tanggal_start = "";

			$tanggal_end = "";

}

//$SalesById = $this->model_master->getSalesById($_SESSION['rick_auto']['filter_sales'])->row();



if(isset($_SESSION['rick_auto']['filter_start_date']) && $_SESSION['rick_auto']['filter_end_date']){

			$tanggal_start = $_SESSION['rick_auto']['filter_start_date'];

			$tanggal_end = date("Y-m-d",strtotime("-1 day", strtotime($_SESSION['rick_auto']['filter_end_date'])));

}else{

			$tanggal_start = "";

			$tanggal_end = "";

}

//$SalesById = $this->model_master->getSalesById($_SESSION['rick_auto']['filter_sales'])->row();

	echo"

		<div style='margin-bottom:1%;margin-top:1%' class='col-sm-12'>

			<div class='col-sm-2' style='margin-top:1%'>

				Filter Tanggal :

			</div>

			<div class='col-sm-3'>

				<input type='date' class='form-control' placeholder='tanggal' id='start_date' name='start_date' placholder='dari tanggal' value='".$tanggal_start."'>

			</div>

			<div class='col-sm-3'>

				<input type='date' class='form-control' placeholder='tanggal' id='end_date' name='end_date' placholder='sampai tanggal' value='".$tanggal_end."'>

			</div>

			<div class='col-sm-1' style='margin-top:1%'>

				Sales

			</div>

			<div class='col-sm-3'>

				<div class='multi-select-full' style='width:150px'>

					<select class='multiselect' multiple='multiple' id='cmbSales' name='cmbSales' onchange=javascript:pilihSales()>

						";

						foreach($getSales->result() as $sales){

							echo"

						<option value='".$sales->id."'>".$sales->name."</option>";}echo"

					</select>

				</div>

				<input type='hidden' id='txtSales' name='txtSales'>

			</div>

		</div>

		<div style='margin-bottom:1%;margin-top:1%' class='col-sm-12'>

			<div class='col-sm-2' style='margin-top:1%'>

				No Invoice

			</div>

			<div class='col-sm-2'>

				<input type='text' class='form-control' placeholder='No. Invoice' id='invoice_no' name='invoice_no' value='".str_replace("PT.E","PT.ETC",$_SESSION['rick_auto']['filter_invoice_no'])."'>

			</div>

			<div class='col-sm-2' style='margin-top:1%'>

				Perusahaan

			</div>

			<div class='col-sm-3'>

				<div class='multi-select-full'>

					<select class='form-control' id='cmbPerusahaanFilter' name='cmbPerusahaanFilter'>

						<option value='0'>Pilih Perusahaan</option>

						";

						if(isset($_SESSION['rick_auto']['filter_perusahaan'])){

						foreach($getPerusahaan->result() as $perusahaan){

							echo"

						<option value='".$perusahaan->id."' ";if($perusahaan->id == $_SESSION['rick_auto']['filter_perusahaan']){echo"selected";}else{}echo">".$perusahaan->name."</option>";}

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

						if(isset($_SESSION['rick_auto']['filter_member'])){

						foreach($getMember->result() as $member){

							echo"

						<option value='".$member->id."' ";if($member->id == $_SESSION['rick_auto']['filter_member']){echo"selected";}else{}echo">".$member->name." - ".$member->city."</option>";}

						}else{

						foreach($getMember->result() as $member){

							echo"

						<option value='".$member->id."'>".$member->name." - ".$member->city."</option>";}

						}



						echo"

				</select>

			</div>

			<!-- <div class='col-sm-2'>";

				if(isset($_SESSION['rick_auto']['filter_member'])){

					$membertxt = $_SESSION['rick_auto']['filter_member'];

				}else{

					$membertxt = "";

				}echo"

				<input type='text' class='form-control' id='cmbMemberFilter' name='cmbMemberFilter' value='".$membertxt."'>

			</div> -->

			<div class='col-sm-2'>

			<a href='#!' onclick=javascript:filter(); class='btn btn-primary btn-labeled'><b><i class='icon-search4'></i></b> Cari </a>

			</div>

		</div>



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



				<!-- <p class='navbar-text'>Filter Tanggal :</p> -->

					<ul class='nav navbar-nav'>

						<li class='dropdown'>



						</li>

					</ul>



			<!-- <div class='navbar-right'>

				<p class='navbar-text'>Sorting:</p>

				<ul class='nav navbar-nav'>

					<li class='active'><a href='#'><i class='icon-sort-alpha-asc position-left'></i> Asc</a></li>

					<li><a href='#'><i class='icon-sort-alpha-desc position-left'></i> Desc</a></li>

				</ul>

			</div> -->

		</div>

	</div>

	<!-- /invoice grid options -->





	<!-- Invoice grid -->

	<div class='text-center content-group text-muted content-divider'>

		<!-- <span class='pt-10 pb-10'>Today</span> -->

	</div>";

	//if($status == 1){

		echo"

	<div class='col-sm-12'>

		<!-- <div class='row col-sm-3'>

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

		</div> -->

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

	";//}

	foreach($getInvoice->result() as $invoice){

		//$duedate_four = date("Y-m-d",strtotime("+30 day", strtotime($invoice->duedate)));

                        // if(date('Y-m-d') > $duedate_four){

                        //     echo "Kadaluwarsa <br>";

                        // }else{

                        //     echo "Belum Kadaluwarsa <br>";

                        // }

		echo"



		<div class='col-sm-12'>

			<div class='panel invoice-grid'>

				<div class='panel-body'>

						<div class='col-sm-1'>

							<div class='form-group'>

								<div class='checkbox'>

									<label>

										<input type='checkbox' class='styled' id='notaCek[]' name='nota_cek' value='".$invoice->id."' onclick=javascript:GetSelected()>

									</label>

								</div>

							</div>

						</div>

						<input type='hidden' id='flag_pilih_".$invoice->id,"' name='flag_pilih_".$invoice->id,"' value='0'>

						<div class='col-sm-11'>

							<div class='col-sm-7'>

							<h6 class='text-semibold no-margin-top'>".$invoice->nama_lengkap_member." - ".$invoice->kota."</h6>

							<ul class='list list-unstyled'>

								<li>Invoice #: &nbsp;".str_replace("PT.E","PT.ETC",$invoice->nonota)."</li>

								<li>No Purchase Order #: &nbsp;".str_replace("PT.E","PT.ETC",$invoice->purchase_no)."</li>

								<li>Sales : &nbsp;".$invoice->sales_name." (Dibuat pada : <span class='text-semibold'>".date("d M Y",strtotime("+0 day", strtotime($invoice->dateorder)))."</span>)</li>

							</ul>

							</div>

							<div class='col-sm-5'>

							<h6 class='text-semibold no-margin-top' align='right'>".$invoice->perusahaan_name."</h6>";

							if($invoice->pay_status == 0){

								if(date("y-m") > date("y-m",strtotime("+1 month", strtotime($invoice->min_duedate)))){echo"

								<span class='label label-block label-warning text-left'>Peringatan! Invoice ini harus dilunasi sebelum ".date("M Y",strtotime("+1 month", strtotime($invoice->duedate)))."

								</span>

								<!-- <span class='label label-block label-warning text-left'>Peringatan! Invoice ini harus dilunasi sebelum ".date("d M Y",strtotime("+0 day", strtotime($invoice->duedate)))."

								</span> -->";

								}else{

									//echo"tanggal sekarang ".date("Y-m-d")."";

								}

							}

							echo"

							</div>

						</div>



						<div class='col-sm-12'>

							";

							//$total_semua = $this->model_invoice->getTotalInvoiceByInvoice($invoice->id)->row();

							$getInvoiceDetail = $this->model_invoice->getInvoiceDetailByInvoiceId($invoice->id);

				        	$total_pembayaran = 0;
				        	$grandTotal = 0;

				        	foreach($getInvoiceDetail->result() as $detailInvoice){

				        		$detailInvoice->ttl_price = ceil($detailInvoice->price) * $detailInvoice->qty_kirim;
				        		$total_pembayaran = $total_pembayaran + ceil($detailInvoice->ttl_price);
				        	}

				        	$diskon = ceil($invoice->discount / $total_pembayaran * 100);
							$invoice->total_before_ppn = $total_pembayaran - ceil($invoice->discount);

							$ppn = $invoice->total_before_ppn * 10 / 100;

	            			$grandTotal = ceil($invoice->total_before_ppn) + ceil($ppn);
							echo"

							<h6 class='text-semibold text-right no-margin-top'>Rp. ".number_format(ceil($grandTotal),0,',','.')."</h6>";

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

								";

								if($invoice->pay_status == 0){



								}else{

									echo"

								<!-- <li>Metode Pembayaran: <span class='text-semibold'>Transfer</span></li> -->

								";}

								echo"

								<li class='dropdown'>

									Status: &nbsp;

									<a id='statusNota_".$invoice->id."' href='#' class='label bg-".$st_ic."-400'>".$st_inv."</a>

									<!-- <a id='statusNota_".$invoice->id."' href='#' class='label bg-".$st_ic."-400 dropdown-toggle' data-toggle='dropdown'>".$st_inv." <span class='caret'></span></a>

									 <ul class='dropdown-menu dropdown-menu-right'>

										<li id='stInvoicebl_".$invoice->id."' ".$st_act_bl."><a href='#' onclick=javascript:ubah_status(0,".$invoice->id.")><i class='icon-alert'></i> Belum Lunas</a></li>

										<li id='stInvoicel_".$invoice->id."' ".$st_act_l."><a href='#' onclick=javascript:ubah_status(1,".$invoice->id.")><i class='icon-checkmark3'></i> Lunas</a></li>

										<li id='stInvoicec_".$invoice->id."' ".$st_act_c."><a href='#' onclick=javascript:ubah_status(2,".$invoice->id.")><i class='icon-cross2'></i> Canceled</a></li>

									</ul> -->

								</li>";

								$dataBayar = $this->model_invoice->getPaymentTandaTerimaInv($invoice->id);



									if($dataBayar->num_rows() > 0){

										if($dataBayar->row()->payment_id == 1 || $dataBayar->row()->payment_id == 3){

										echo"

										<li>Metode Pembayaran: <span class='text-semibold'>".$dataBayar->row()->nama_pembayaran." <br> Tanggal Cair : ".date("d/m/Y",strtotime($dataBayar->row()->liquid_date))."</span></li>";

									}}echo"

								<li> ";

            $dataRole = $this->model_master->getRoleByNoTrac($invoice->purchase_no);

            if($dataRole->num_rows() > 0){

                foreach($dataRole->result() as $data){

                    if($data->flag_level == 4){

                        $flag_lv = "PO";

                    }elseif($data->flag_level == 3){

                        $flag_lv = "GDG";

                    }else{

                        $flag_lv = "INV";

                    }

                    echo"<div class='col-md-3 col-sm-4'><i class='glyphicon glyphicon-user'></i>".$data->action."</div>";

                }}echo"

								</li>

							</ul>

						</div>

				</div>



				<div class='panel-footer panel-footer-condensed'>

					<div class='heading-elements'>

						<span class='heading-text'>";

						//$data_inv = date("Y-m-d H:i:s",strtotime("+0 day", strtotime($invoice->dateorder)));

						$data_inv = date("Y-m-d",strtotime("+0 day", strtotime($invoice->dateorder)));

						$data_inv_due = date("Y-m-d",strtotime("+0 day", strtotime($invoice->duedate)));

            			//$masa = $this->template->xTimeAgoDesc($data_inv,date("Y-m-d H:i:s"));

            			$masa = $this->template->xTimeAgoDesc($data_inv,date("Y-m-d"));

            			if($masa == 0){

            				$ms = "Today";

            			}else{

            				$ms = $masa;

            			}

            			//$jajal = date("Y-m-d") - date("Y-m-d",strtotime("+0 day", strtotime($invoice->duedate)));

            			$jajal = $this->template->xTimeAgo(date("Y-m-d"),$data_inv_due);



            			//echo "jajal".$jajal;

            	echo"

							<span class='status-mark border-danger position-left'></span><span class='text-semibold'>".$ms."</span>

						</span>



						<ul class='list-inline list-inline-condensed heading-text pull-right'>

							<li><a href='#' class='text-default' data-toggle='modal' data-target='#invoice' onclick=javascript:detail_invoice(".$invoice->id.")><i class='icon-eye8'></i></a></li>

							<li class='dropdown'>

								<a href='#' class='text-default dropdown-toggle' data-toggle='dropdown'><i class='icon-menu7'></i> <span class='caret'></span></a>

								<ul class='dropdown-menu dropdown-menu-right'>

									<li><a href='#!' data-toggle='modal' data-target='#modal_pilihan' onclick=javascript:changeTanggal(".$invoice->id.")><i class=' icon-printer'></i>Print invoice</a></li>

									<li><a href='#!' data-toggle='modal' data-target='#modal_pilihan' onclick=javascript:pilihMenu('".$invoice->member_id."',".$invoice->perusahaan_id.",".$invoice->id.")><i class=' icon-files-empty'></i>Surat Jalan</a></li>

									<li><a href='#!' data-toggle='modal' data-target='#modal_pilihan' onclick=javascript:changePackingList(".$invoice->id.")><i class=' icon-printer'></i>Print Packing List</a></li>

									<!-- <li><a href='".base_url("admin/invoice/print_packing_list/".base64_encode($invoice->id)."")."' target='_blank'><i class='icon-printer'></i> Print Packing List</a></li> -->

									<!-- <li><a href='".base_url("admin/invoice/print_amplop/".base64_encode($invoice->id)."")."' target='_blank'><i class='icon-printer'></i> Print Amplop</a></li> -->

									<li><a href='#!' data-toggle='modal' data-target='#modal_pilihan' onclick=javascript:changeAmplop(".$invoice->id.")><i class='icon-printer'></i> Print Amplop</a></li>

									<li class='divider'></li>

									<li><a href='#' data-toggle='modal' data-target='#confirmation_modal' onclick='javascript:showInputPengiriman(".$invoice->id.")'><i class='icon-truck'></i> Input Pengiriman</a></li>

									<!-- <li><a href='#'><i class='icon-cross2'></i> Remove invoice</a></li> -->

								</ul>

							</li>

						</ul>

					</div>

				</div>

			</div>

		</div>

		<div class='col-sm-12'>

 	";

    //         $dataRole = $this->model_master->getRoleByNoTrac($invoice->purchase_no);

    //         if($dataRole->num_rows() > 0){

    //             foreach($dataRole->result() as $data){

    //                 if($data->flag_level == 4){

    //                     $flag_lv = "PO";

    //                 }elseif($data->flag_level == 3){

    //                     $flag_lv = "GDG";

    //                 }else{

    //                     $flag_lv = "INV";

    //                 }

    //                 echo"<div class='col-md-3 col-sm-4'><i class='glyphicon glyphicon-user'></i>".$data->action."</div>";

    //             }}echo"

    echo"

		</div>

		";}

		echo"<input type='hidden' id='activeNota' name='activeNota' value='0'>

		";

	if($uri4 != ""){

		echo"

		<div class='col-sm-12'>

			<a href='#!' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:confirm_buat_tanda_terima() class='btn btn-success btn-labeled'><b><i class='icon-box-remove'></i></b> Proses Tanda Terima </a>

		</div>";}

	else{

		echo"";

	}

	echo"

    <!-- Modal with invoice -->

	<div id='invoice' class='modal fade'>

		<div class='modal-dialog modal-full'>

			<div class='modal-content' id='ajaxInvoice'>



			</div>

		</div>

	</div>

	<!-- /modal with invoice -->

</div>

</div>

<script>

        $('#cmbMemberFilter').select2();

        </script>

<!-- /main content -->

";?>
