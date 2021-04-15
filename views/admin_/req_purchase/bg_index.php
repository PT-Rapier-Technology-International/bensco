<?php
$uri4 = $this->uri->segment(4);
$update_read = $this->db->set('read',1)->update('transaction_purchase');
echo"
<div class='content-wrapper'>

<!-- Invoice archive -->
<div class='panel panel-white'>
	<div class='panel-heading'>
        ";
         if($uri4 == ""){
            echo"
		<h6 class='panel-title'>Request Purchase Order</h6>";}else{
            echo"
        <h6 class='panel-title'>Purchase Order Batal</h6>";
        }echo"
		<div class='heading-elements'>
			<ul class='icons-list'>
        		<li><a data-action='collapse'></a></li>
        		<li><a data-action='reload'></a></li>
        	</ul>
    	</div>
	</div>

	<table class='table table-lg invoice-archive' id='tabelTransaksi'>
		<thead>
			<tr>
				<th>#</th>
				<th>Period</th>
                <th>Customer</th>
                <th>Tanggal Dibuat</th>
                <th>Status</th>
                <th>Expedisi</th>
                <th>Sales</th>
                <th>Total (Rp.)</th>";
                 if($uri4 == ""){
                    echo"
                <th class='text-center'>Aksi</th>";}else{
                    echo"
                <th>Catatan Gudang</th>
                    ";
                }echo"

            </tr>
		</thead>
		<tbody>
			";
			foreach($getPurchase->result() as $purchase){
				$tanggal_purchase = date("F Y",strtotime("+0 day", strtotime($purchase->dateorder)));
            $cekExp = $this->model_invoice->getCekExpiredStatus(date("Y-m-d"),$purchase->member_id);
            if($cekExp->num_rows() > 0){
				$expMem = "<span class='label label-block label-danger text-left'>Member ini belum <br> melakukan  pembayaran <br> pada invoice
                </span>";
            }else{
                $expMem = "";
            }
                echo"
			<tr>
				<td><h6 class='no-margin'>#<b>".$purchase->notransaction."</b></h6> ";
                if($uri4 == ""){
                    if($purchase->status == 0){

                    }else{
                        echo"
                    <small class='display-block text-muted'> Catatan : <b>".$purchase->note."</b></small>";}
                }
                    echo"</td>
				<td>".$tanggal_purchase."</td>
                <td>".$purchase->nama_member." - ".$purchase->kota_member." <br> ".$expMem."</td>";
            	$data_purc = date("Y-m-d H:i:s",strtotime("+0 day", strtotime($purchase->dateorder)));
            	$masa = $this->template->xTimeAgoDesc($data_purc,date("Y-m-d H:i:s"));
            	echo" 
                <td>
                	".date("d M Y",strtotime("+0 day", strtotime($purchase->dateorder)))."
                	<small class='display-block text-muted'>(".$masa.")</small>
            	</td>";
	            	if($purchase->status == 0){
	            		$status_out = "<span class='label label-default'>BARU</span>";
	            	}else{
                        $status_out = "<span class='label label-danger'>DITOLAK</span>";
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
                    ".$purchase->nama_expedisi." <br>
                    <b>Via Expedisi : ".$purchase->nama_via_expedisi."</b> 
                </td>
                <td>
                    ".$purchase->nama_sales."
                </td>
                <td class='text-right'>
                	";if($_SESSION['rick_auto']['flag_user'] == 3){
                		echo"";}else{
                			echo"
                	<h6 class='no-margin text-bold'>".number_format($purchase->total,2,',','.')."</h6>";}echo"
                </td>";
                if($uri4 == ""){
                        echo"
				<td class='text-center'>
					<a href='".base_url("admin/purchase/req_show_detail/".base64_encode($purchase->id)."")."' class='btn btn-primary'><i class='icon-make-group position-left'></i>Proses Data</a><br><br>";
                    //if($purchase->status == 1){
                        echo"
                    <a href='#!' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:confirmBatalReqPO(".$purchase->id.") class='btn btn-warning'><i class='icon-folder-remove position-left'></i>Konfirm Batal</a>
                        ";
                    //}
                        echo"
				</td>";
                    }else{
                        echo "
                        <td>".$purchase->note."</td>";
                    }echo"
            </tr>
            ";}
            echo"
        </tbody>
    </table>
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