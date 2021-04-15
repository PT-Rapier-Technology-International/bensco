<?php
echo "
<style>
  body{
    padding-left: 1.3cm;
    padding-right: 1.3cm; 
    padding-top: 1.1cm;
  }
</style>
<style type='text/css'>
      tr.hide_right > td, td.hide_right{
        border-right-style:hidden;
      }
      tr.hide_all > td, td.hide_all{
        border-style:hidden;
      }
  }
</style>";
$jenis = $this->uri->segment(4);
if($jenis != "pdf"){
if($jenis == "excel"){
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=report_tanda_terima_".date('d_m_y').".xls");
}
echo"
<style type='text/css' media='print'>
  @page {
    size: auto;  
    margin: 0;  
  }
</style>
<style>
  body{
    padding-left: 1.3cm;
    padding-right: 1.3cm; 
    padding-top: 1.1cm;
  }
</style>
<style type='text/css'>
      tr.hide_right > td, td.hide_right{
        border-right-style:hidden;
      }
      tr.hide_all > td, td.hide_all{
        border-style:hidden;
      }
  }
</style>
	<h1 align='center'>".$getPerusahaan->name."</h1>
	<h4 align='right' style='margin-top:1px;margin-bottom:1px'>Tanggal Print : ".date("d M Y")."</h4><br>";
	if(isset($_SESSION['rick_auto']['salesrtt'])){
		$delete = $_SESSION['rick_auto']['salesrtt'];
		  $delete = explode(",",$delete);
          $del  = array('');
          $cekStoks = "";
          $num_count = 0;
          for($i=0;$i<count($delete);$i++) {
            $del[] = $delete[$i];
            //echo $delete[$i];
            //echo $delete[$i]."<br>";
            $cekStok = $this->model_master->getSalesById($delete[$i]);
 				//echo $cekStok->row()->name.",";
            $num_count = $num_count + 1;
            if ($num_count < count($delete)) {
            	$cm = ",";
            }else{
            	$cm = "";
            }

 		    $cekStoks .= $cekStok->row()->name."".$cm." ";
        }
        echo "<h4 align='right' style='margin-top:1px;margin-bottom:1px'>Sales : ".$cekStoks." </h4>";
	}
	$grandTotal = 0;
	foreach($getMemberInvoice->result() as $member){
		
			echo"
	<h3>Daerah ".$member->kota."</h3>
	<h3 align='center'>".date('F', strtotime($member->dateorder))." ".date("Y",strtotime("+0 day", strtotime($member->dateorder)))."</h3>
	<table width='100%' border='1' cellspacing='0'>
		<tr>
			<th>TANGGAL INVOICE</th>
			<th>NO TANDA TERIMA</th>
			<th>NO INVOICE</th>
			<th>AMOUNT</th>
			<th>TANGGAL GIRO</th>
			<th>GIRO</th>
			<th>KETERANGAN</th>
		</tr>
		<tr>
			<td colspan='6'><h3><b>".$member->member_name." - ".$member->kota."</b></h3></td>
		</tr>		";

			$dataPrint = $this->model_invoice->getInvoiceByMemberPrintTT(1,$member->member_id);

			$totalAmount = 0;
			foreach($dataPrint->result() as $print){
				
				$nott = $this->model_invoice->getNoTTbyInvoiceId($print->id)->row();
				$totalAmount = $totalAmount + $print->total;

				echo"

				<tr>

					<td align='center'>".date("d/m/Y",strtotime("+0 day", strtotime($print->dateorder)))."</td>
					<td align='center'>".$nott->no_tanda_terima."</td>
					<td align='center'>".$print->nonota."</td>

					<td align='right'>".number_format($print->total,2,',','.')."</td>

					<td></td>

					<td></td>

					<td></td>

				</tr>

				";

			}

			echo"

				<tr class='hide_all'>

					<td colspan='2' align='center'><b>AMOUNT GIRO</b></td>

					<td align='right'><b>".number_format($totalAmount,2,',','.')."</b></td>

					<td colspan='3'></td>

				</tr>



			";

			$grandTotal = $grandTotal + $totalAmount;

		echo"



		";

		echo"

	</table>";}
	echo"
	<table width='100%' border='1' cellspacing='0'>
				<tr class='hide_all'>

					<td colspan='2' align='center'><b>GRAND TOTAL</b></td>

					<td align='right'><b>".number_format($grandTotal,2,',','.')."</b></td>

					<td colspan='3'></td>

				</tr>
	</table>
	";
	if($jenis == "print"){
			echo"
	<script>
		window.print();
	</script>";}echo"
";
}else{
	?>
	<?php
		$grandTotal = 0;
		foreach($getMemberInvoice->result() as $member){
			?>
	<table width="100%" border="1" cellspacing="0">
		<tr>
			<th>TANGGAL INVOICE</th>
			<th>NO INVOICE</th>
			<th>AMOUNT</th>
			<th>TANGGAL GIRO</th>
			<th>GIRO</th>
			<th>KETERANGAN</th>
		</tr>
		<?php
			echo"
		<tr>
			<td colspan='6'><h3><b>".$member->member_name." - ".$member->kota."</b></h3></td>
		</tr>
		";
			$dataPrint = $this->model_invoice->getInvoiceByMemberPrintTT(1,$member->member_id);
			$totalAmount = 0;
			foreach($dataPrint->result() as $print){
				$totalAmount = $totalAmount + $print->total;
				echo"
				<tr>
					<td align='center'>".date("d/m/Y",strtotime("+0 day", strtotime($print->dateorder)))."</td>
					<td align='center'>".$print->nonota."</td>
					<td align='right'>".number_format($print->total,2,',','.')."</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				";
			}
			?>
				<tr class="hide_all">
					<td colspan="2" align="center"><b>AMOUNT GIRO</b></td>
					<td align="right"><b><?php echo number_format($totalAmount,2,',','.');?></b></td>
					<td colspan="3"></td>
				</tr>
		<?php 
			$grandTotal = $grandTotal + $totalAmount;
		?>
				<tr class="hide_all">
					<td colspan="2" align="center"><b>TOTAL</b></td>
					<td align="right"><b><?php echo number_format($totalAmount,2,',','.');?></b></td>
					<td colspan="3"></td>
				</tr>
</table><?php } ?>
<?php
}?>