<?php
$uri3 = $this->uri->segment(3);
if($uri3 == "req_bo_index"){
    $r_in = 1;
}else{
    $r_in = 0;
}
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
    <div style='margin-bottom:1%;margin-top:1%' class='col-sm-12'>
        <div class='col-sm-4'>
            <select class='form-control' id='cmbPerusahaanFilter' name='cmbPerusahaanFilter'>
                <option value='0'>Semua Perusahaan</option>
                ";
                if(isset($_SESSION['rick_auto']['rpo_perusahaan_filter'])){
                foreach($getPerusahaan->result() as $perusahaan){
                    echo"
                <option value='".$perusahaan->id."' ";if($perusahaan->id == $_SESSION['rick_auto']['rpo_perusahaan_filter']){echo"selected";}else{}echo">".$perusahaan->name."</option>";}
            }else{
                foreach($getPerusahaan->result() as $perusahaan){
                echo"
                <option value='".$perusahaan->id."'>".$perusahaan->name."</option>
                ";}
            }echo"
            </select>
        </div>
        <div class='col-sm-4'>
                <div class='multi-select-full' style='width:100%'>
                    <select class='multiselect-filtering' multiple='multiple' id='cmbSales' name='cmbSales'>
                        ";
                        foreach($getSales->result() as $sales){
                            echo"
                        <option value='".$sales->id."'>".$sales->name."</option>";}echo"
                    </select>
                </div>
                <input type='hidden' id='txtSales' name='txtSales'>
        </div>
        <div class='col-sm-4'>
            <a href='#!' onclick=javascript:filter_perusahaan_rpo(); class='btn btn-primary btn-labeled'><b><i class='icon-search4'></i></b> Cari </a>
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
                $dataRole = $this->model_purchase->getRoleTanggalByNo($purchase->notransaction);
                    if($dataRole->num_rows() > 0){
                        echo"<small class='display-block text-muted'> ".$dataRole->row()->action." <br> ".date("d M Y H:i:s",strtotime("+0 day", strtotime($dataRole->row()->create_date)))."</small><br>";
                    }
                if($uri4 == ""){
                    if($purchase->status == 0){

                    }else{
                        echo"
                    <small class='display-block text-muted'> Catatan : <b>".$purchase->note."</b></small>";}
                }

                $dataRoleBo = $this->model_purchase->getRoleTanggalByNo($purchase->no_po);
                    if($dataRoleBo->num_rows() > 0){
                        foreach($dataRoleBo->result() as $roleBo){
                        echo"<small class='display-block text-muted'> ".$roleBo->action." <br> ".date("d M Y H:i:s",strtotime("+0 day", strtotime($roleBo->create_date)))."</small><br>";
                        }
                    }
                    echo"</td>
				<td>".$tanggal_purchase."</td>
                <td>".$purchase->nama_member." - ".$purchase->kota_member." <br> ".$expMem."</td>";
            	// $data_purc = date("Y-m-d H:i:s",strtotime("+0 day", strtotime($purchase->dateorder)));
            	// $masa = $this->template->xTimeAgoDesc($data_purc,date("Y-m-d H:i:s"));
                $data_purc = date("Y-m-d",strtotime("+0 day", strtotime($purchase->dateorder)));
                $masa = $this->template->xTimeAgoDesc($data_purc,date("Y-m-d"));
                if($masa == 0){
                    $ms = "Today";
                }else{
                    $ms = $masa;
                }
            	echo" 
                <td>
                	".date("d M Y",strtotime("+0 day", strtotime($purchase->dateorder)))."
                	<small class='display-block text-muted'>(".$ms.")</small>
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
                    <a href='".base_url("admin/purchase/print_req_purchase/".base64_encode($purchase->id)."")."/print' target='_blank' class='btn btn-default'><i class='icon-printer position-left'></i>Print</a><br><br>
                    <a href='".base_url("admin/purchase/print_req_purchase/".base64_encode($purchase->id)."")."/excel' target='_blank' class='btn btn-success'><i class='icon-printer position-left'></i>Excel</a><br><br>
                    <a href='".base_url("admin/purchase/print_req_purchase/".base64_encode($purchase->id)."")."/pdf' target='_blank' class='btn btn-danger'><i class='icon-printer position-left'></i>PDF</a><br><br>
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