<?php

echo"

<div class='form-group has-feedback'>

    <a href='".base_url("admin/invoice/export_piutang/excel")."' target='_blank' class='btn bg-green'><i class=' icon-file-download2 position-left'></i>Export Excel</a> &nbsp; 

    <a href='".base_url("admin/invoice/export_piutang/pdf")."' target='_blank' class='btn btn-danger'><i class='icon-file-download position-left'></i>Export PDF</a> &nbsp; 

    <a href='".base_url("admin/invoice/export_piutang/print")."' target='_blank' class='btn btn-primary'><i class='icon-printer position-left'></i>Print</a>

</div>

<center>

	<h3>".$_SESSION['rick_auto']['perusahaannamerp']." <br> Laporan Per Tanggal ".date("d M Y",strtotime($_SESSION['rick_auto']['tanggalfromrrp']))."</h3>

</center>

";

$totalSemuanyaa = 0;

if($getData->num_rows() > 0){

foreach($getData->result() as $data){

	echo"

<table class='table'>

	<tr>

		<th width='5%'>No.</th>

		<th width='15%'>No. Data</th>

		<th width='15%'>Buyer / Member</th>

		<th width='15%'>Sales</th>

		<th width='15%'>Sisa Tagihan</th>

		<th width='15%'>Total Tagihan</th>

		<th width='15%'>Status</th>

	</tr>

	";

	$getDataDetail = $this->model_invoice->getInvoiceByBulanTahun(date("m",strtotime($data->dateorder)), date("Y",strtotime($data->dateorder)));

	$no = 0;

	$sisaaa = 0;

	foreach($getDataDetail->result() as $dataDetail){

		$dataBayar = $this->model_invoice->getPaymentTandaTerima($dataDetail->no_tt)->row();
		$dataBayar2 = $this->model_invoice->getPaymentTandaTerimaL($dataDetail->no_tt)->row();
		$dataBayar2s = $this->model_invoice->getPaymentTandaTerimaL($dataDetail->no_tt);
		$databayarDesc = $this->model_invoice->getPaymentTandaTerimaa($dataDetail->no_tt)->row();
		$dataBayarr = $this->model_invoice->getTotalPaymentInvoiceByNott($dataDetail->no_tt,$databayarDesc->payment_id)->row();
		$dataTotal = $this->model_invoice->getPaymentTandaTerimaAsc($dataDetail->no_tt)->row();
		$dataTotals = $this->model_invoice->getTotalPembayaranByNott($dataDetail->no_tt)->row(); 

		$no++;

		if($dataBayar->id_pembayaran == 1 || $dataBayar->id_pembayaran == 4){	
			$sisa = $sisa + $dataDetail->total;
			$s = $dataDetail->total;
			$tanggal_cair = "<br>Tanggal Cair : ".date("d/m/Y",strtotime($dataBayar->liquid_date));
			

		}else{
			$sisa = $sisa + $dataDetail->sisa;
			$s = $dataDetail->sisa;
			$tanggal_cair = "";
			
		}



		if($databayarDesc->payment_id == 1 || $databayarDesc->payment_id == 4){
			$tanggal_t = $dataBayar2->liquid_date;
			$_SESSION['rick_auto']['tanggalfromrrps'] = date("Y-m-d",strtotime("+1 day",strtotime($_SESSION['rick_auto']['tanggalfromrrp'] )));
			$tanggal_f = $_SESSION['rick_auto']['tanggalfromrrps'];
			
		}else{
			if($dataBayar2s->num_rows() > 0){
				$tanggal_t = $dataBayar2->payment_date;
			}else{
				$tanggal_t = '2020-12-31';
			}

			$tanggal_f = $_SESSION['rick_auto']['tanggalfromrrp'];
		}

		if($tanggal_f > $tanggal_t){
			if($databayarDesc->payment_id == 1 || $databayarDesc->payment_id == 4){	
				if($dataBayar->flag == 0){	
					// if($_SESSION['rick_auto']['tanggalfromrrp'] < $dataBayar->liquid_date){
					// $dataBayarrr = $this->model_invoice->getTotalPaymentInvoiceByNott($dataDetail->no_tt,2)->row();
					// }else{
					// $dataBayarrr = $this->model_invoice->getTotalPaymentInvoiceByNott($dataDetail->no_tt,$databayarDesc->payment_id)->row();	
					// }
					$dataBayarrr = $this->model_invoice->getTotalPaymentInvoiceByNott($dataDetail->no_tt,$databayarDesc->payment_id)->row();
				echo"

			<tr>

				<td>$no</td>

				<td>".$dataDetail->no_tt."</td>

				<td>".wordwrap($dataDetail->member_name,15,"<br>\n")." - ".$dataDetail->kota_member."</td>

				<td>".$dataDetail->sales_name."</td>";

				$sisaa = $sisa + $dataBayar->sudah_dibayar;

				$sisaa = $dataTotals->total_pembayaran - $dataBayarrr->total_sudah_dibayar;
				$sisaaa = $sisaaa + $sisaa;

				echo"

				<td align='right'>Rp. ".number_format($sisaa,0,',','.')."</td>

				<td align='right'>Rp. ".number_format($dataTotals->total_pembayaran,0,',','.')."</td>

				<td>".$dataBayar->jenis_pembayaran." ".$tanggal_cair."</td>

			</tr>";}
			}else{
				echo"
			<tr>

				<td>$no</td>

				<td>".$dataDetail->no_tt."</td>

				<td>".wordwrap($dataDetail->member_name,15,"<br>\n")." - ".$dataDetail->kota_member."</td>

				<td>".$dataDetail->sales_name."</td>";

				$sisaa = $sisa + $dataBayar->sudah_dibayar;

				$sisaa = $dataTotals->total_pembayaran - $dataBayarr->total_sudah_dibayar;
				$sisaaa = $sisaaa + $sisaa;

				echo"

				<td align='right'>Rp. ".number_format($sisaa,0,',','.')."</td>

				<td align='right'>Rp. ".number_format($dataTotals->total_pembayaran,0,',','.')."</td>

				<td>".$dataBayar->jenis_pembayaran." ".$tanggal_cair."</td>

			</tr>";
			}
		}else{
			$dataBayarrr = $this->model_invoice->getTotalPaymentInvoiceByNott($dataDetail->no_tt,2)->row();
				echo"

			<tr>

				<td>$no</td>

				<td>".$dataDetail->no_tt."</td>

				<td>".wordwrap($dataDetail->member_name,15,"<br>\n")." - ".$dataDetail->kota_member."</td>

				<td>".$dataDetail->sales_name."</td>";

				$sisaa = $sisa + $dataBayar->sudah_dibayar;

				$sisaa = $dataTotals->total_pembayaran - $dataBayarrr->total_sudah_dibayar;
				$sisaaa = $sisaaa + $sisaa;

				echo"

				<td align='right'>Rp. ".number_format($sisaa,0,',','.')."</td>

				<td align='right'>Rp. ".number_format($dataTotals->total_pembayaran,0,',','.')."</td>

				<td>".$dataBayar->jenis_pembayaran." ".$tanggal_cair."</td>

			</tr>";}}

			$getDataDetailOri = $this->model_invoice->getInvoiceOriByBulanTahun(date("m",strtotime($data->dateorder)), date("Y",strtotime($data->dateorder)));

			$noo = $getDataDetail->num_rows();

			$sisaOri = 0;

			foreach($getDataDetailOri->result() as $dataDetailOri){

			$sisaOri = $sisaOri + $dataDetailOri->total;	

				$noo++;

				if($dataDetailOri->total > 0){

				echo"

			<tr>

				<td>$noo</td>

				<td>".wordwrap($dataDetailOri->nonota,5,"<br>\n")."</td>

				<td>".$dataDetailOri->member_name." - ".$dataDetailOri->kota_member."</td>

				<td>".$dataDetailOri->sales_name."</td>

				<td align='right'>Rp. ".number_format($dataDetailOri->total,0,',','.')."</td>

				<td align='right'>Rp. ".number_format($dataDetailOri->total,0,',','.')."</td>

				<td></td>

			</tr>";

			}}



	$totalSemuanya = $sisaaa + $sisaOri;

	if($totalSemuanya > 0){

	echo"

	<tr>

		<td colspan='4' align='right'><h4><b>Sisa Piutang ".date("F Y",strtotime($data->dateorder))."</b></h4></td>

		<td align='right'><h4><b>Rp. ".number_format($totalSemuanya,0,',','.')."</b></h4></td>

	</tr>";}echo"

</table>";$totalSemuanyaa = $totalSemuanyaa + $totalSemuanya;

}



echo "

<table class='table' width='100%'>

	<tr>

		<td class='text-center' colspan='4'><h3><b>GRAND TOTAL</b></h3></td>

		<td class='text-right'><h3><b>Rp. ".number_format($totalSemuanyaa,0,',','.')."</b></h3></td>

		<td></td>

	</tr>

</table>

";

}

?>