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

	<p align='center'>".$getPerusahaan->name."</p>

	<table width='100%' border='1' cellspacing='0'>

		<tr>

			<th>TANGGAL INVOICE</th>

			<th>NO INVOICE</th>

			<th>AMOUNT</th>

			<th>TANGGAL GIRO</th>

			<th>GIRO</th>

			<th>KETERANGAN</th>

		</tr>

		";

		$grandTotal = 0;

		foreach($getMemberInvoice->result() as $member){

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

			echo"

				<tr class='hide_all'>

					<td colspan='2' align='center'><b>AMOUNT GIRO</b></td>

					<td align='right'><b>".number_format($totalAmount,2,',','.')."</b></td>

					<td colspan='3'></td>

				</tr>



			";

			$grandTotal = $grandTotal + $totalAmount;

		}

		echo"

				<tr class='hide_all'>

					<td colspan='2' align='center'><b>TOTAL</b></td>

					<td align='right'><b>".number_format($grandTotal,2,',','.')."</b></td>

					<td colspan='3'></td>

				</tr>

		";

		echo"

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

		$grandTotal = 0;

		foreach($getMemberInvoice->result() as $member){

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

		}?>

				<tr class="hide_all">

					<td colspan="2" align="center"><b>GRAND TOTAL</b></td>

					<td align="right"><b><?php echo number_format($grandTotal,2,',','.');?></b></td>

					<td colspan="3"></td>

				</tr>

</table>

<?php

}?>