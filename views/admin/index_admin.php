<?php
$update_read = $this->db->set('read',1)->update('transaction_purchase');
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
	<table class='table table-lg invoice-archive' id='tabelTransaksi'>
		<thead>
			<tr>
				<th>#</th>
				<th>Period</th>
                <th>Customer</th>
                <th>Action Status</th>
                <th>Tanggal Dibuat</th>
                <th>Status</th>
                <th>Total (Rp.)</th>
                <th>Ekspedisi</th>
                <th>Aksi</th>
                

            </tr>
		</thead>
		<tbody>
			";
			foreach($getPurchase->result() as $purchase){
				$tanggal_purchase = date("F Y",strtotime("+0 day", strtotime($purchase->dateorder)));
				echo"
			<tr>
				<td>#".$purchase->nonota."</td>
				<td>".$tanggal_purchase."</td>
                <td>
                	<h6 class='no-margin'>
                		<a href='#' data-toggle='modal' data-target='#invoice' onclick=javascript:show_detail(".$purchase->id.")>".$purchase->nama_member."- ".$purchase->kota_member."</a>
                		<!-- <small class='display-block text-muted'>CASH</small> -->
            		</h6>
            	</td>
                <td>";
            	if($_SESSION['rick_auto']['flag_user'] == 3){
                    if($purchase->status == 0 || $purchase->status_gudang == 1){
                    }else{
            		echo"
            		<select name='status' class='select' data-placeholder='Select status' name='cmbStatus_".$purchase->id."' id='cmbStatus_".$purchase->id."' onchange=javascript:ubah_status_gudang(".$purchase->id.")>
                		<option value='0' ";if($purchase->status_gudang == 0){echo "selected";}else{}echo">DIPROSES</option>
                		<option value='1' ";if($purchase->status_gudang == 1){echo "selected";}else{}echo">SELESAI</option>
                		<option value='2' ";if($purchase->status_gudang == 2){echo "selected";}else{}echo">DITOLAK</option>
                	</select>
            		";
                    }
            	}

            	if($purchase->status_gudang == 1){
            		echo"
            		<a href='#!' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:confirm_buat_nota(".$purchase->id.") class='btn btn-primary btn-labeled'><b><i class='icon-paperplane'></i></b> Buat Invoice</a>
            		";
            		}
                    if($purchase->status_gudang == 1){

                    }else{
            		echo"
                	<select name='status' class='select' data-placeholder='Select status' name='cmbStatus_".$purchase->id."' id='cmbStatus_".$purchase->id."' onchange=javascript:ubah_status(".$purchase->id.")>
                		<option value='0' ";if($purchase->status == 0){echo "selected";}else{}echo">BARU</option>
                		<option value='1' ";if($purchase->status == 1){echo "selected";}else{}echo">DIPROSES</option>
                		<option value='2' ";if($purchase->status == 2){echo "selected";}else{}echo">PENDING</option>
                	</select>
                	";}
                	echo"
            	</td>";
            	$data_purc = date("Y-m-d H:i:s",strtotime("+0 day", strtotime($purchase->dateorder)));
            	$masa = $this->template->xTimeAgoDesc($data_purc,date("Y-m-d H:i:s"));
            	echo" 
                <td>
                	".date("d M Y",strtotime("+0 day", strtotime($purchase->dateorder)))."
                	<small class='display-block text-muted'>(".$masa.")</small>
            	</td>";
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
	            	if($purchase->status == 0){
	            		$status_out = "<span class='label label-default'>BARU</span>";
	            	}elseif($purchase->status == 1){
	            		$status_out = "<span class='label label-primary'>DIPROSES</span>";
	            	}elseif($purchase->status == 2){
	            		$status_out = "<span class='label label-warning'>PENDING</span>";
	            	}elseif($purchase->status == 3){
                        $status_out = "<span class='label label-success'>SUCCESS</span>";
                    }else{
                        $status_out = "<span class='label label-danger'>DIBATALKAN</span>";
                    }
            	echo"
                <td>
                	".$status_out."


                	";
                	if($_SESSION['rick_auto']['flag_user'] != 1){
                        if($purchase->status == 3){
                            $st_gudang = "SUKSES MEMBUAT NOTA";
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
                			echo"
                	<h6 class='no-margin text-bold'>".number_format($getPurchaseDetail->total_harga,2,',','.')."</h6>";}echo"
                </td>
                <td>
                    <a href='#!' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:pilihExpedisi(".$purchase->id.") class='btn btn-primary btn-labeled'><b><i class='icon-truck'></i></b> ".$purchase->nama_expedisi."</a></a>
                </td>
				<td>
					<ul class='icons-list'>
						<li><a href='#' data-toggle='modal' data-target='#invoice' onclick=javascript:show_detail(".$purchase->id.")><i class='icon-file-eye'></i></a></li>
						<li class='dropdown'>
							<a href='#' class='dropdown-toggle' data-toggle='dropdown'><i class='icon-file-text2'></i> <span class='caret'></span></a>
							<ul class='dropdown-menu dropdown-menu-right'>
								<li><a href='#'><i class='icon-file-download'></i> Download</a></li>
								<li><a href='#'><i class='icon-printer'></i> Print</a></li>
								<li class='divider'></li>
								<li><a href='#'><i class='icon-file-plus'></i> Edit</a></li>
								<li><a href='#'><i class='icon-cross2'></i> Remove</a></li>
							</ul>
						</li>
					</ul>
				</td>
            </tr>";
            $dataRole = $this->model_master->getRoleByNoTrac($purchase->nonota);
            if($dataRole->num_rows() > 0){
            echo"
            <tr style='background:cyan'>
                <td colspan='9'>
                ";
                foreach($dataRole->result() as $data){
                    if($data->flag_level == 4){
                        $flag_lv = "PO";
                    }elseif($data->flag_level == 3){
                        $flag_lv = "GDG";
                    }else{
                        $flag_lv = "INV";
                    }
                    echo"<div class='col-md-3 col-sm-4'><i class='glyphicon glyphicon-user'></i> Status : ".$flag_lv." - ".$data->user."</div>";
                    }echo"
                </td>
            </tr>
            ";}}
            echo"
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