<?php
echo"
<div class='content-wrapper'>
				<!-- Bordered striped table -->
				<div class='panel panel-flat'>
					<div class='panel-heading'>
						<h5 class='panel-title'>Detail Perubahan Data Invoice</h5>
						<div class='heading-elements'>
							<ul class='icons-list'>
		                		<li><a data-action='collapse'></a></li>
		                		<li><a data-action='reload'></a></li>
		                		 
		                	</ul>
	                	</div>
					</div>

					<div class='table-responsive'>
						<table class='table datatable-basic'>
							<thead>
								<tr>
									<th>#</th>
									<th>Tanggal</th>
									<th>No Revisi/Retur</th>
									<th>No Invoice</th>
									<th>Qty</th>
									<th>Harga/Unit</th>
									<th>Harga Total</th>
									<th>Catatan</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							";
							$no = 0;
							foreach($Data->result() as $data){
								$norevret = explode('/',$data->nomor_retur_revisi);
								$no++;
								echo"
								<tr>
									<td>$no</td>
									<td>".date("d M Y", strtotime("+0 day", strtotime("$data->create_date")))."</td>	
									<td>".str_replace("PT.E","PT.ETC",$data->nomor_retur_revisi)."</td>
									<td>".str_replace("PT.E","PT.ETC",$data->no_nota)."</td>";
									if($data->qty_before == $data->qty_change){
										$qty_status = "Tidak ada Perubahan Qty";
									}else{
										$qty_status = "Perubahan Qty : ".$data->qty_before." menjadi ".$data->qty_change."";
									}echo"
									<td>".$qty_status."</td>";
									if($data->price_before == $data->price_change){
										$price_status = "Tidak ada Perubahan Harga";
									}else{
										$price_status = "Perubahan Harga : ".$data->price_before." menjadi ".$data->price_change."";
									}echo"
									<td>".$price_status."</td>";
									if($data->total_before == $data->total_change){
										$total_status = "Tidak ada Perubahan Total Harga";
									}else{
										$total_status = "Perubahan Total Harga : ".$data->total_before." menjadi ".$data->total_change."";
									}echo"
									<td>".$total_status."</td>
									<td>".$data->note."</td>
									<td><a href='".base_url("admin/invoice/print_retur_revisi/".base64_encode($data->id)."/".$norevret[0]."/".$norevret[2]."")."' target='_blank' class='btn btn-primary btn-labeled'><b><i class='icon-printer'></i></b> Print</a></td>
								</tr>
								";}
								echo"
							</tbody>
						</table>
					</div>
				</div>
				<!-- /bordered striped table -->
</div>";
?>