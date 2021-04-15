<?php
$jenis = $this->uri->segment(4);
if($jenis != "pdf"){
if($jenis == "excel"){
		header("Content-type: application/vnd-ms-excel");
		header("Content-Disposition: attachment; filename=export_report_rekap_invoice_".date('d_m_y').".xls");
}
if($_SESSION['rick_auto']['filter_tanggal_rri'] = $_SESSION['rick_auto']['filter_tanggal_to_rri']){
echo"
		<p align='center'><font size='5'><b><U>REKAP ".strtoupper($getInvoice->row()->perusahaan_name)."</font></U></b>
		<br>".date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_tanggal_rri'])))." - ".date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_tanggal_to_rri'])))."
		</p>";	
}else{
echo"
		<p align='center'><font size='5'><b><U>REKAP HARIAN ".strtoupper($getInvoice->row()->perusahaan_name)."</font></U></b>
		<br>".date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_tanggal_to_rrii'])))." 
		</p>";		
}echo"

		<table class='table' border='1' cellspacing='0' width='100%'>
			<thead>
				<tr>
					<th>#</th>
					<th>No PO</th>
					<th>SO</th>
					<th>No Invoice</th>
					<th>Customer</th>
					<th>Kota</th>
					<th>Ekspedisi</th>
					<th>Total Invoice (Rp.) Setelah PPN</th>
				</tr>
			</thead>
			<tbody>";
			$no = 0;
			$total = 0;
			$total_sub = 0;
			foreach($getInvoice->result() as $invoice){
				$no++;
				$total = $total + $invoice->total;
				$total_sub = $total_sub + $invoice->total_before_ppn;
				echo"
				<tr>
					<td>$no</td>
					<td>".$invoice->purchase_no."</a></td>";
					$soo = explode("/",$invoice->purchase_no);
					echo"
					<td>".$soo[1]."</a></td>
					<td>".$invoice->nonota."</a></td>
					<td>".$invoice->member_name."</td>
					<td>".$invoice->kota."</td>
					<td>".$invoice->expedisi."</td>
					<td align='right'>".number_format($invoice->total,2,',','.')."</td>					
				</tr>";
			}
			echo"
			<tr>
				<td colspan='6'><h2><b>GRAND TOTAL</b></h2></td>
				<td align='right'>".number_format($total,2,',','.')."</td>	
			</tr>
			</tbody>
		</table>";
	if($jenis == "print"){
			echo"
	<script>
		window.print();
	</script>";}
}else{
	?>
	<?php
if($_SESSION['rick_auto']['filter_tanggal_rri'] = $_SESSION['rick_auto']['filter_tanggal_to_rri']){
?>	
		<p align="center"><font size="20"><b><U>REKAP <?php echo strtoupper($getInvoice->row()->perusahaan_name);?></font></U></b>
		<br><?php echo date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_tanggal_rri'])))." - ".date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_tanggal_to_rri'])));?>
		</p>
<?php
}else{
?>
		<p align="center"><font size="20"><b><U>REKAP HARIAN <?php echo strtoupper($getInvoice->row()->perusahaan_name);?></font></U></b>
		<br><?php echo date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_tanggal_rri'])));?>
		</p>
<?Php			
}?>
	<table width="100%" border="1" cellspacing="0">
		<tr>
			<th>#</th>
			<th>No PO</th>
			<th>No Invoice</th>
			<th>Customer</th>
			<th>Kota</th>
			<th>Ekspedisi</th>
			<th>Total Invoice (Rp.) Setelah PPN</th>
		</tr>
		<?php
		$no = 0;
		$total = 0;
			$total_sub = 0;
			foreach($getInvoice->result() as $invoice){
				$no++;
				$total = $total + $invoice->total;
				$total_sub = $total_sub + $invoice->total_before_ppn;
				echo"
				<tr>
					<td>$no</td>
					<td>".$invoice->purchase_no."</td>
					<td>".$invoice->nonota."</td>
					<td>".$invoice->member_name."</td>
					<td>".$invoice->kota."</td>
					<td>".$invoice->expedisi."</td>
					<td align='right'>".number_format($invoice->total,2,',','.')."</td>					
				</tr>";
			}?>
			<tr>
				<td colspan="6"><h2><b>GRAND TOTAL</b></h2></td>
				<td align="right"><?php echo number_format($total,2,',','.');?></td>	
			</tr>
	</table>
<?php
}?>