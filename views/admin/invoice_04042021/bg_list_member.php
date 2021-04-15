<?php
$uri4 = $this->uri->segment(4);
if($uri4 == ""){
	$title = "Invoice Member";
}else{
	$title = "Halaman untuk Invoice Tanda Terima oleh Member";
}
echo"
<!-- Main content -->
<div class='content-wrapper'>

	<!-- Basic datatable -->
	<div class='panel panel-flat'>
		<div class='panel-heading'>
			<h5 class='panel-title'>".$title ."</h5>
			<div class='heading-elements'>
				<ul class='icons-list'>
            		<li><a data-action='collapse'></a></li>
            		<li><a data-action='reload'></a></li>
            		 
            	</ul>
        	</div>
		</div>

		<table class='table datatable-basic' id='tabelTransaksi'>
			<thead>
				<tr>
					<th>#</th>
					<th>Kode Member</th>
					<th>Nama Lengkap</th>
					<th>Daerah</th>
					<th>Total Invoice (Rp.)</th>
					<!-- <th>Jumlah Invoice Belum Lunas(Rp.)</th>
					<th>Jumlah Invoice Lunas(Rp.)</th> -->
				</tr>
			</thead>
			<tbody>";
			$no = 0;
			foreach($getMembers->result() as $member){
				if($uri4 == ""){
					$total_inv = $this->db->query("select pay_status,sum(total) as total_invoice, count('*') as jumlah_invoice from invoice where member_id = '".$member->id."' and flag_tanda_terima = '0'")->row();
				}else{
					$total_inv = $this->db->query("select pay_status,sum(total) as total_invoice, count('*') as jumlah_invoice from invoice where member_id = '".$member->id."' and flag_tanda_terima = '1'")->row();
				}
				$total_inv_l = $this->db->query("select pay_status,sum(total) as total_invoice, count('*') as jumlah_invoice from invoice where member_id = '".$member->id."' and pay_status = 1")->row();
				$total_inv_bl = $this->db->query("select pay_status,sum(total) as total_invoice, count('*') as jumlah_invoice from invoice where member_id = '".$member->id."' and pay_status = 0")->row();
				$no++;
				echo"
				<tr>
					<td>$no</td>
					<td>".$member->id."</td>
					<td>".$member->name."</td>
					<td>".$member->city."</td>
					";if($uri4 == ""){
						echo"
					<td class='text-right'><a href='".base_url("admin/invoice/invoice_detail/".base64_encode($member->id)."")."/0'>".number_format($total_inv->total_invoice,2,',','.')."</a></td>";}else{
						echo"
					<td class='text-right'><a href='".base_url("admin/invoice/invoice_tanda_terima/".base64_encode($member->id)."")."'>".number_format($total_inv->total_invoice,2,',','.')."</a></td>
						";
					}echo"
					<!-- <td class='text-right'><a href='".base_url("admin/invoice/invoice_detail/".base64_encode($member->id)."")."/0'>".number_format($total_inv_bl->total_invoice,2,',','.')."</td>
					<td class='text-right'><a href='".base_url("admin/invoice/invoice_detail/".base64_encode($member->id)."")."/1'>".number_format($total_inv_l->total_invoice,2,',','.')."</td> -->
					
				</tr>";
			}
			echo"
			</tbody>
		</table>
	</div>
	<!-- /basic datatable -->
</div>
<!-- /main content -->
";?>