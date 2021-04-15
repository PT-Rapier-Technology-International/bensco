<?php

echo "

<style>

  body{

    padding-left: 1.3cm;

    padding-right: 1.3cm;

    padding-top: 0.5cm;

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

    padding-top: 0.5cm;

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

	<h5 align='right' style='margin-top:1px;margin-bottom:1px'>Tanggal Print : ".date("d M Y")."</h5><br>";

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

        echo "<h5 align='right' style='margin-top:1px;margin-bottom:1px'>Sales : ".$cekStoks." </h5>";

	}

	$grandTotal = 0;
  $temp = "";
  $temp_tgl = "";

	foreach($getMemberInvoice->result() as $member){

    echo"<table width='100%' border='1' cellspacing='0' style='font-family:calibri;'>";

          if($temp != $member->kota)
          {echo "<p><b>Daerah ".$member->kota."</b></p>";}

           $temp = $member->kota;

           if ($temp_tgl != date('F', strtotime($member->dateorder))." ".date("Y",strtotime("+0 day", strtotime($member->dateorder))))
           {
             echo "<p align='center'><b>".date('F', strtotime($member->dateorder))." ".date("Y",strtotime("+0 day", strtotime($member->dateorder)))."</b></p>";

             echo"
             <tr>

         			<th style='width:16%'><p style='font-size:11px;'>TANGGAL INVOICE</p></th>

         			<th style='width:14%'><p style='font-size:11px;'>NO TANDA TERIMA</p></th>

         			<th style='width:14%'><p style='font-size:11px;'>NO INVOICE</p></th>

         			<th style='width:14%'><p style='font-size:11px;'>AMOUNT</p></th>

         			<th style='width:14%'><p style='font-size:11px;'>TANGGAL GIRO</p></th>

         			<th style='width:14%'><p style='font-size:11px;'>GIRO</p></th>

         			<th style='width:16%'><p style='font-size:11px;'>KETERANGAN</p></th>

         		</tr>";
           }
           $temp_tgl = date('F', strtotime($member->dateorder))." ".date("Y",strtotime("+0 day", strtotime($member->dateorder)));

    	echo"

		<tr>

			<td colspan='7' style='border:0;'>
			<p style='font-size:11px;'><b>(".($member->kodemember).") ".$member->member_name." - ".$member->kota."</b></p>
			</td>

		</tr>";



			$dataPrint = $this->model_invoice->getInvoiceByMemberPrintTT(1,$member->member_id);



			$totalAmount = 0;

			foreach($dataPrint->result() as $print){



				$nott = $this->model_invoice->getNoTTbyInvoiceId($print->id)->row();

				$totalAmount = $totalAmount + $print->total;



				echo"



			<tr>



			<td style='font-size: 11px' align='center' width='16%'>".date("d/m/Y",strtotime("+0 day", strtotime($print->dateorder)))."</td>

			<td style='font-size: 11px' align='center' width='14%'>".str_replace("PT.E","PT.ETC",$nott->no_tanda_terima)."</td>

			<td style='font-size: 11px' align='center' width='14%'>".$print->nonota."</td>

			<td style='font-size: 11px' align='center' width='14%'>".number_format($print->total,2,',','.')."</td>

			<td style='font-size: 11px' align='center' width='14%'></td>

			<td style='font-size: 11px' align='center' width='16%' ></td>

     






					<!--<td colspan='2'></td>-->
					";
					if($print->pay_status == 0){
						$st_bayar = "";
					}else{
						$st_bayar = "Lunas";
					}echo"






					<td style='font-size:11' width='15%'>".$st_bayar."</td>

				</tr>

				
				";



			}



			echo"
				<tr class='hide_all'>

				<td colspan='2' align='center' style='font-size:11; border: none;'><b>AMOUNT GIRO</b></td>
				<td style='border: none;'></td>
				<td  align='center' style='font-size:11;' width='15%'><b>".number_format($totalAmount,2,',','.')."</b></td>
				
				<td style='border: none;'></td>
				<td style='border: none;'></td>
				<td style='border: none;'></td>	
				</tr>


			";



			$grandTotal = $grandTotal + $totalAmount;



		echo"







		";



		echo"



	</table>";}
			
	echo"
		<br>
	<table width='100%' border='1' cellspacing='0' style='font-family:calibri;'>

				<tr class='hide_all'>



					<td colspan='2' align='center' style='font-size:11; border: none;'><b>GRAND TOTAL</b></td>


					<td style='border: none;'></td>
					<td align='center' style='font-size:11;' ><b>".number_format($grandTotal,2,',','.')."</b></td>
					<td style='border: none;'></td>
					<td style='border: none;'></td>
					<td style='border: none;'></td>


					<!--<td colspan='3'></td>-->



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
    // $temporary = "";
    // $temporary_tgl = "";
		foreach($getMemberInvoice->result() as $member){

			?>

	<table width="100%" border="1" cellspacing="0">

		<tr>

			<th><p style='font-size:11px;'>TANGGAL INVOICE</p></th>

			<th><p style='font-size:11px;'>NO INVOICE</p></th>

			<th><p style='font-size:11px;'>AMOUNT</p></th>

			<th><p style='font-size:11px;'>TANGGAL GIRO</p></th>

			<th><p style='font-size:11px;'>GIRO</p></th>

			<th><p style='font-size:11px;'>KETERANGAN</p></th>

		</tr>

		<?php

			echo"

		<tr>

			<td colspan='2' style='border:0;'>
			<p style='font-size:11px; border:0;'><b>".$member->member_name." - ".$member->kota."</b></p>
			</td>

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
					";
					if($print->pay_status == 0){
						$st_bayar = "";
					}else{
						$st_bayar = "Lunas";
					}echo"

					<td> ".$st_bayar."</td>

				</tr>

				";

			}

			?>

				<tr class="hide_all">

					<td colspan="2" align="center"><b>AMOUNT GIRO</b></td>

					<td align="center"><b><?php echo number_format($totalAmount,2,',','.');?></b></td>

					<td colspan="3"></td>

				</tr>

		<?php

			$grandTotal = $grandTotal + $totalAmount;

		?>

				<tr class="hide_all">

					<td colspan="2" align="center"><b>TOTAL</b></td>

					<td align="center"><b><?php echo number_format($totalAmount,2,',','.');?></b></td>

					<td colspan="3"></td>

				</tr>

</table><?php } ?>

<?php

}?>
