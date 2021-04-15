<?php
$update_read = $this->db->set('read',1)->update('transaction_purchase');

//$namapt = "PT.RA";
// $getPurchasee = $this->model_purchase->getPurchaseByPerusahaan($namapt);
// $noGen = explode("/",$getPurchasee->row()->nonota);
//             $noPODesc = substr($noGen[0], 5);
//             if($getPurchasee->num_rows() > 0){
//                 $genUnik = $noPODesc + 1;
//             }else{
//                 $genUnik = 1;
//             }
echo"
<div class='content-wrapper'>

<!-- Invoice archive -->
<div class='panel panel-white'>
	<div class='panel-heading'>
		<h6 class='panel-title'>Purchase Order</h6>
		<div class='heading-elements'>
			<ul class='icons-list'>
        		<li><a data-action='collapse'></a></li>
        		<li><a data-action='reload'></a></li>
        		 
        	</ul>
    	</div>
	</div>

    <div class='table-responsive'>
    <div style='margin-bottom:1%;margin-top:1%' class='col-sm-12'>
        <div class='col-sm-8'>
            <select class='form-control' id='cmbPerusahaanFilter' name='cmbPerusahaanFilter'>
                <option value='0'>Semua Perusahaan</option>
                ";
                if(isset($_SESSION['rick_auto']['po_perusahaan_filter'])){
                foreach($getPerusahaan->result() as $perusahaan){
                    echo"
                <option value='".$perusahaan->id."' ";if($perusahaan->id == $_SESSION['rick_auto']['po_perusahaan_filter']){echo"selected";}else{}echo">".$perusahaan->name."</option>";}
            }else{
                foreach($getPerusahaan->result() as $perusahaan){
                echo"
                <option value='".$perusahaan->id."'>".$perusahaan->name."</option>
                ";}
            }echo"
            </select>
        </div>
        <div class='col-sm-4'>
            <a href='#!' onclick=javascript:filter_perusahaan_po(); class='btn btn-primary btn-labeled'><b><i class='icon-search4'></i></b> Cari </a>
            </div>
    </div>
	<table class='table datatable-basic' id='tabelPO'>
		<thead>
			<tr>
				<th>#</th>
                <th>Customer</th>
                <th>Action Status</th>
                <th>Status</th>
                <th>Total (Rp.)</th>
                <th>Ekspedisi</th>
                <th>Tanggal Update</th>
                <th>Aksi</th>
                

            </tr>
		</thead>
		<tbody>
			";
			foreach($getPurchase->result() as $purchase){
				$tanggal_purchase = date("F Y",strtotime("+0 day", strtotime($purchase->dateorder)));
                $getInvoice = $this->model_purchase->getInvoiceByNoPO($purchase->nonota);
                if($getInvoice->num_rows() > 0){
                    $noInv = "<b>No. Inv : ".$getInvoice->row()->nonota."</b>";
                }else{
                    $noInv = "";
                }


                $cekExp = $this->model_invoice->getCekExpiredStatus(date("Y-m-d"),$purchase->member_id);
                if($cekExp->num_rows() > 0){
                    // $expMem = "<span class='label label-block label-danger text-left'>Member ini belum <br> melakukan  pembayaran <br> pada invoice
                    // </span>";
                }else{
                    //$expMem = "";
                }

                $date2 = date('Y-m-d');
                $date1 = date("Y-m-d",strtotime("+0 day", strtotime($purchase->dateorder)));

                $ts1 = strtotime($date1);
                $ts2 = strtotime($date2);

                $year1 = date('Y', $ts1);
                $year2 = date('Y', $ts2);

                $month1 = date('m', $ts1);
                $month2 = date('m', $ts2);

                $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
                if($diff < 4){
                    $expMem = "";
                }else{
                    $expMem = "<span class='label label-block label-danger text-left'>Purchase Sudah <br> 4 bulan atau lebih
                    </span>";
                }
				echo"
			<tr>
				<td>#".$purchase->nonota." <br>
                ".$noInv." <br>";
                    $dataRole1 = $this->model_master->getRoleByNoTrac($purchase->kode_rpo)->row();
                    echo"<small class='display-block text-muted'> ".$dataRole1->action." <br> ".date("d M Y H:i:s",strtotime("+0 day", strtotime($dataRole1->create_date)))."</small><br>";
                    $dataRole = $this->model_master->getRoleByNoTrac($purchase->nonota);
                    if($dataRole->num_rows() > 0){
                    echo"
                        ";
                        foreach($dataRole->result() as $data){
                            if($data->flag_level == 4){
                                $flag_lv = "PO";
                            }elseif($data->flag_level == 3){
                                $flag_lv = "GDG";
                            }else{
                                $flag_lv = "INV";
                            }
                            echo"<small class='display-block text-muted'> ".$data->action." <br> ".date("d M Y H:i:s",strtotime("+0 day", strtotime($data->create_date)))."</small><br>";
                            }echo"
                    ";}
                //if($_SESSION['rick_auto']['flag_user'] == 3){
                    if($purchase->count_cetak == "" || $purchase->count_cetak == 0){
                    }else{echo "<i class='icon-printer2'></i> <i class='icon-checkmark4'></i><small class='display-block text-muted'>(Sudah diprint ".$purchase->count_cetak."x)</small>";}
                //}
                echo"</td>
                <td>
                	<h6 class='no-margin'>
                		<a href='#' data-toggle='modal' data-target='#invoice' onclick=javascript:show_detail(".$purchase->id.")>".$purchase->nama_member." - ".$purchase->kota_member." <br> ".$expMem."</a>
                		<!-- <small class='display-block text-muted'>CASH</small> -->
            		</h6>
            	</td>";
                if($_SESSION['rick_auto']['flag_user'] == 5 || $_SESSION['rick_auto']['flag_user'] == 6){
                    echo"
                    <td></td>
                    ";
                }else{
                    echo"
                <td>";
            	if($_SESSION['rick_auto']['flag_user'] == 3){
                    if($purchase->status == 0 || $purchase->status_gudang == 1 || $purchase->status_gudang == 2){
                    }else{
            		echo"
            		<select name='status' class='select' data-placeholder='Select status' name='cmbStatus_".$purchase->id."' id='cmbStatus_".$purchase->id."' onchange=javascript:ubah_status_gudang(".$purchase->id.")>
                		<option value='0' ";if($purchase->status_gudang == 0){echo "selected";}else{}echo">DIPROSES</option>
                		<option value='1' ";if($purchase->status_gudang == 1){echo "selected";}else{}echo">SELESAI</option>
                		<option value='2' ";if($purchase->status_gudang == 2){echo "selected";}else{}echo">DITOLAK</option>
                	</select>
            		";
                    }
            	}elseif($_SESSION['rick_auto']['flag_user'] == 2){
            		// if($purchase->status_gudang == 2){
              //       }else{
              //       if($purchase->status != 3){
            		// echo"
            		// <!-- <a href='#!' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:confirm_buat_nota(".$purchase->id.") class='btn btn-primary btn-labeled'><b><i class='icon-paperplane'></i></b> Buat Invoice</a> -->
              //       <select name='status' class='select' data-placeholder='Select status' name='cmbStatus_".$purchase->id."' id='cmbStatus_".$purchase->id."' onchange=javascript:ubah_status_invoice(".$purchase->id.")>
              //           <option value='0' selected disabled>Pilih Action</option>
              //           <option value='1'>PROSES INVOICE</option>
              //           <option value='2'>DITOLAK</option>
              //       </select>
            		// ";
            		// }
              //       }
                    if($purchase->status_gudang == 1 && $purchase->status != 3){
                        echo"
                        <select name='status' class='select' data-placeholder='Select status' name='cmbStatus_".$purchase->id."' id='cmbStatus_".$purchase->id."' onchange=javascript:ubah_status_invoice(".$purchase->id.")>
                            <option value='0' selected disabled>Pilih Action</option>
                            <option value='1'>PROSES INVOICE</option>
                            <option value='2'>DITOLAK</option>
                        </select>
                        ";
                    }
            	}else{
                    if($purchase->status_gudang == 1 || $purchase->status_gudang == 2){

                    }elseif($purchase->status == 2 || $purchase->status == 1){
                    }else{
            		echo"
                	<select name='status' class='select' data-placeholder='Select status' name='cmbStatus_".$purchase->id."' id='cmbStatus_".$purchase->id."' onchange=javascript:ubah_status(".$purchase->id.")>
                		<option value='0' ";if($purchase->status == 0){echo "selected";}else{}echo">BARU</option>
                		<option value='1' ";if($purchase->status == 1){echo "selected";}else{}echo">DIPROSES</option>
                		<option value='2' ";if($purchase->status == 2){echo "selected";}else{}echo">TOLAK</option>
                	</select>
                	";}}
                	echo"
            	</td>";
                }
            	$data_purc = date("Y-m-d H:i:s",strtotime("+0 day", strtotime($purchase->dateorder)));
            	$masa = $this->template->xTimeAgoDesc($data_purc,date("Y-m-d H:i:s"));
            	if($_SESSION['rick_auto']['flag_user'] == 3 || $_SESSION['rick_auto']['flag_user'] == 2){
                    if($purchase->status == 0){
                        $status_out = "<span class='label label-default'>BELUM DIPROSES ADMIN</span>";
                    }else{
                		if($purchase->status_gudang == 0){
    	            		$status_out = "<span class='label label-default'>DIPROSES</span>";
    	            	}elseif($purchase->status_gudang == 1){
    	            		$status_out = "<span class='label label-primary'>SELESAI</span>";
    	            	}elseif($purchase->status_gudang == 2){
    	            		$status_out = "<span class='label label-warning'>DITOLAK</span>";
    	            	}
                    }
            	}else{
	            	if($purchase->status == 0){
	            		$status_out = "<span class='label label-default'>BARU</span>";
	            	}elseif($purchase->status == 1 && $purchase->status_gudang == 1){
	            		$status_out = "<span class='label label-primary'>DIPROSES</span>";
	            	}elseif($purchase->status == 1 && $purchase->status_gudang == 2){
                        $status_out = "<span class='label label-warning'>DITOLAK</span>";
                    }elseif($purchase->status == 1){
                        $status_out = "<span class='label label-primary'>DIPROSES</span>";
                    }elseif($purchase->status == 2){
	            		$status_out = "<span class='label label-warning'>TOLAK</span>";
	            	}elseif($purchase->status == 3){
                        $status_out = "<span class='label label-success'>SUCCESS</span>";
                    }else{
                        $status_out = "<span class='label label-danger'>DIBATALKAN</span>";
                    }
            	}
            	echo"
                <td>
                	".$status_out."
                    <small class='display-block text-muted'>Dibuat Oleh : ".$purchase->createdby."</small>


                	";
                	if($_SESSION['rick_auto']['flag_user'] != 1){
                        if($purchase->status == 3){
                            $st_gudang = "SUKSES MEMBUAT NOTA";
                        }elseif($purchase->status == 2 && $purchase->status_gudang == 0){
                            $st_gudang = "";
                        }else{
                            if($purchase->status == 0){
                                $st_gudang = "";
                            }else{
                        		if($purchase->status_gudang == 0){
                        			$st_gudang = "DIPROSES GUDANG";
                        		}elseif($purchase->status_gudang == 1){
                        			$st_gudang = "SELESAI PROSES GUDANG";
                        		}else{
                        			$st_gudang = "DITOLAK GUDANG";
                        		}
                            }
                        }
                		echo"<small class='display-block text-muted'>".$st_gudang."</small>";
                		}echo"
            	</td>
                <td>
                	";if($_SESSION['rick_auto']['flag_user'] == 3){
                		echo"";}else{
                    $getPurchaseDetail = $this->model_purchase->getTotalPembayarannyaByPurchase($purchase->id)->row();
                    if($getInvoice->num_rows() > 0){
                            $ppn = $getPurchaseDetail->total_harga * 10 / 100;
                            $grandTotal = $getPurchaseDetail->total_harga + $ppn;
                            $tot = "<h6 class='no-margin text-bold'>".number_format($grandTotal,2,',','.')."</h6>";
                        }else{
                            $tot = "<h6 class='no-margin text-bold'>".number_format($getPurchaseDetail->total_harga,2,',','.')."</h6>";
                        }
                	}echo"
                    ".$tot."
                </td>
                <td>
                    <a href='#!' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:pilihExpedisi(".$purchase->id.") class='btn btn-primary btn-labeled'><b><i class='icon-truck'></i></b> ".$purchase->nama_expedisi."</a></a>
                </td>
                ";
                // if($_SESSION['rick_auto']['flag_user'] == 1 || $_SESSION['rick_auto']['flag_user'] == 4){
                //     $dt = $purchase->update_date_po;
                // }elseif($_SESSION['rick_auto']['flag_user'] == 2){
                //     $dt = $purchase->update_date_invoice;
                // }else{
                //     $dt = $purchase->update_date_gudang;
                // }

                $dataRoleS = $this->model_purchase->getRoleTanggalByNo($purchase->nonota);
                if($dataRoleS->num_rows() > 0){
                    $tgl = $dataRoleS->row()->create_date;
                }else{
                    $tgl = $purchase->dateorder;
                }echo"
                <td>
                    ".date("d M Y H:i:s",strtotime("+0 day", strtotime($tgl)))."
                </td>
				<td>
					<ul class='icons-list'>
						<li><a href='#' data-toggle='modal' data-target='#invoice' onclick=javascript:show_detail(".$purchase->id.")><i class='icon-file-eye'></i></a></li>
						<!-- <li class='dropdown'>
							<a href='#' class='dropdown-toggle' data-toggle='dropdown'><i class='icon-file-text2'></i> <span class='caret'></span></a>
							<ul class='dropdown-menu dropdown-menu-right'>
								<li><a href='#'><i class='icon-file-download'></i> Download</a></li>
								<li><a href='#'><i class='icon-printer'></i> Print</a></li>
								<li class='divider'></li>
								<li><a href='#'><i class='icon-file-plus'></i> Edit</a></li>
								<li><a href='#'><i class='icon-cross2'></i> Remove</a></li>
							</ul>
						</li> -->
					</ul>
				</td>
            </tr>";}echo"
        </tbody>
    </table>
    </div>
</div>
<!-- /invoice archive -->


<!-- Modal with invoice -->
<div id='invoice' class='modal fade'>
	<div class='modal-dialog modal-full'>
		<div class='modal-content'>
			<div id='tempatDetail'>
			
				
			</div>
		</div>
	</div>
</div>
<!-- /modal with invoice -->

</div>
";?>