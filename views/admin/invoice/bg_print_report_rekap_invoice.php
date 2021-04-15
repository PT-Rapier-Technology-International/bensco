<?php

$jenis = $this->uri->segment(4);
$tangal_dari = $this->uri->segment(5);
//echo $tangal_dari;
if($jenis != "pdf"){

if($jenis == "excel"){

		header("Content-type: application/vnd-ms-excel");

		header("Content-Disposition: attachment; filename=export_report_rekap_invoice_".date('d_m_y').".xls");

}
//echo $_SESSION['rick_auto']['filter_tanggal_rriss'];
if($_SESSION['rick_auto']['filter_tanggal_rriss'] = $_SESSION['rick_auto']['filter_tanggal_to_rri']){

echo "

		<p align='center'><font size='5'><b><U>REKAP INVOICE ".strtoupper($getInvoice->row()->perusahaan_name)."</font></U></b>

		<br>".date("d M Y",strtotime("+0 day", strtotime($tangal_dari)))." - ".date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_tanggal_to_rri'])))."

		</p>";

}else{

echo "

		<p align='center'><font size='5'><b><U>REKAP INVOICE HARIAN ".strtoupper($getInvoice->row()->perusahaan_name)."</font></U></b>

		<br>".date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_tanggal_to_rrii'])))."

		</p>";

}echo"



		<table class='table' border='1' cellspacing='0' width='100%'>

			<thead>

				<tr>

					<th>#</th>

					<th>No PO</th>

					<th>No Invoice</th>

					<th width='50%'>Customer</th>

					<th>Kota</th>

					<th width='20%'>Ekspedisi</th>

					<th width='20%'>Tot Inv (PPN) (Rp.)</th>

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

					<td><p style='font-size:14px'>$no</p></td>

					<td><p style='font-size:11px'>".str_replace("PT.E","PT.ETC",$invoice->purchase_no)."</a></td>";

					$soo = explode("/",$invoice->purchase_no);

					echo"

					<td><p style='font-size:11px'>".str_replace("PT.E","PT.ETC",$invoice->nonota)."</a></td>

					<td><p style='font-size:13px'>".$invoice->member_name."</p></td>

					<td><p style='font-size:12px'>".$invoice->kota."</td>

					<td><p style='font-size:12px'>".$invoice->expedisi."</p></td>

					<td align='right'><p style='font-size:14px'>".number_format($invoice->total,2,',','.')."</p></td>

				</tr>";

			}

			echo"

			<tr height='50px'>

				<td colspan='6'><p style='font-size:20px;'><b>GRAND TOTAL</b></p></td>

				<td align='right'><p style='font-size:14px'>".number_format($total,2,',','.')."</p></td>

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

if($_SESSION['rick_auto']['filter_tanggal_rriss'] = $_SESSION['rick_auto']['filter_tanggal_to_rri']){

?>

		<p align="center"><font size="20"><b><U>REKAP INVOICE <?php echo strtoupper($getInvoice->row()->perusahaan_name);?></font></U></b>

		<br><?php echo date("d M Y",strtotime("+0 day", strtotime($tangal_dari)))." - ".date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_tanggal_to_rri'])));?>

		</p>

<?php

}else{

?>

		<p align="center"><font size="20"><b><U>REKAP INVOICE HARIAN <?php echo strtoupper($getInvoice->row()->perusahaan_name);?></font></U></b>

		<br><?php echo date("d M Y",strtotime("+0 day", strtotime($_SESSION['rick_auto']['filter_tanggal_rriss'])));?>

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

			<th>Tot Inv (PPN) (Rp.)</th>

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

					<td><p style='font-size:14px'>$no</p></td>

					<td>".str_replace("PT.E","PT.ETC",$invoice->purchase_no)."</td>

					<td>".str_replace("PT.E","PT.ETC",$invoice->nonota)."</td>

					<td><p style='font-size:13px'>".$invoice->member_name."</p></td>

					<td>".$invoice->kota."</td>

					<td><p style='font-size:12px'>".$invoice->expedisi."</p></td>

					<td align='right'><p style='font-size:14px'>".number_format($invoice->total,2,',','.')."</p></td>

				</tr>";

			}?>

			<tr height='50px'>

				<td colspan="6"><p style='font-size:24px;'><b>GRAND TOTAL</b></p></td>

				<td align="right"><p style='font-size:14px'><?php echo number_format($total,2,',','.');?></p></td>

			</tr>

	</table>

<?php

}?>
