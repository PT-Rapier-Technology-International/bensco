<?php
echo"
<div class='content-wrapper'>
				<!-- Bordered striped table -->
				<div class='panel panel-flat'>
					<div class='panel-heading'>
						<h5 class='panel-title'>List Purchase</h5>
						<div class='heading-elements'>
							<ul class='icons-list'>
		                		<li><a data-action='collapse'></a></li>
		                		<li><a data-action='reload'></a></li>
		                		 
		                	</ul>
	                	</div>
					</div>

					<div class='panel-body'>
						<!-- <a href='".base_url("admin/produk/add")."' class='btn btn-info btn'>Tambah Produk</a> --> 
					</div>

					<div class='table-responsive'>
				<table class='table datatable-basic'>
				<thead>
					<tr>
						<th>#</th>
		                <th>Customer</th>
		                <th>Status Data Purchasing</th>
		                <th>Tanggal Dibuat</th>
		                <th>Status oleh Gudang</th>
		                <th>Status oleh PO</th>
		                <th>Total (Rp.)</th>
		                <th>Aksi</th>
		            </tr>
				</thead>
				<tbody>
				";
				$no = 0;
				foreach($getPurchase->result() as $purchase){
					$no++;
					if($purchase->status == 0){
	            		$st_purchasing = "<span class='label label-default'>BARU</span>";
	            		$st_gudang = "";
	            	}elseif($purchase->status == 1){
	            		$st_purchasing = "<span class='label label-primary'>DIPROSES</span>";
	            		$st_gudang = "";
	            	}elseif($purchase->status == 2){
	            		$st_purchasing = "<span class='label label-warning'>PENDING</span>";
	            		$st_gudang = "";
	            	}elseif($purchase->status == 3){
                        $st_purchasing = "<span class='label label-success'>SUCCESS</span>";
                        $st_gudang = "Nota Telah dibuat";
                    }else{
                        $st_purchasing = "<span class='label label-danger'>DIBATALKAN</span>";
                        $st_gudang = "";
                    }
		           	if($purchase->status_gudang == 0){
	            		$status_out = "<span class='label label-default'>DIPROSES GUDANG</span>";
	            		$st_po = "";
	            	}elseif($purchase->status_gudang == 1){
	            		$status_out = "<span class='label label-primary'>SELESAI PROSES GUDANG</span>";
	            		if($purchase->status == 3){
	            			$st_po = "<span class='label label-success'>Sudah membuat Nota</span>";
	            		}else{
	            			$st_po = "<span class='label label-warning'>belum membuat Nota</span>";
	            		}
	            	}else{
	            		$status_out = "<span class='label label-warning'>DITOLAK GUDANG</span>";
	            		$st_po = "";
	            	}
					echo"
					<tr>
						<td>#".$purchase->nonota." ";
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
                            echo"<small class='display-block text-muted'>Status : ".$flag_lv." - ".$data->user."</small><br>";
                            }echo"
                    ";}echo"</td>
		                <td>
		                	<h6 class='no-margin'>".$purchase->nama_member." - ".$purchase->kota_member."
		            		</h6>
		            	</td>
		            	<td>".$st_purchasing." 
		            	<small class='display-block text-muted'>".$st_gudang."</small></td>";
		            	$data_purc = date("Y-m-d H:i:s",strtotime("+0 day", strtotime($purchase->dateorder)));
            			$masa = $this->template->xTimeAgoDesc($data_purc,date("Y-m-d H:i:s"));
		            	echo" 
		            	<td>
		                	".date("d M Y",strtotime("+0 day", strtotime($purchase->dateorder)))."
		                	<small class='display-block text-muted'>(".$masa.")</small>
		            	</td>
    	            	<td>".$status_out."</td>
    	            	<td>".$st_po."</td>
    	            	<td class='text-right'>".number_format($purchase->total,2,',','.')."</td>
    	            	<td>
							<ul class='icons-list'>
								<li><a href='#' data-toggle='modal' data-target='#invoice' onclick=javascript:show_detail(".$purchase->id.")><i class='icon-file-eye'></i></a></li>
							</ul>
						</td>
					</tr>";
        }
					echo"
				</tbody>
			</table>
		</div>
		</div>
	<!-- /bordered striped table -->
</div>

<!-- Modal with invoice -->
<div id='invoice' class='modal fade'>
	<div class='modal-dialog modal-full'>
		<div class='modal-content'>
			<div id='tempatDetail'>
			
				
			</div>
		</div>
	</div>
</div>
<!-- /modal with invoice -->";
?>