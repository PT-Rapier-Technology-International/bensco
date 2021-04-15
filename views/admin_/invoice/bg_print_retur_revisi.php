<?php
$id = base64_decode($this->uri->segment(4));
$singkatan_pt = $this->uri->segment(5);
$jenis = $this->uri->segment(6);
if($jenis == "RET"){
	$jenis_out = "Retur :";
}else{
	$jenis_out = "Revisi :";
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
<table width='100%'>
	<tr>
		<th align='left'><h1>".$singkatan_pt."</h1></th>
		<th align='right'><h4>".$jenis_out." ".$dataRR->row()->nomor_retur_revisi."</h4></th>
	</tr>
</table>
<hr style='margin-top:-1%'>
<table width='100%'>
	<tr>
		<th align='left'>
		<table border = '1' cellspacing='0'>
			<tr>
				<th>Dibuat O/</th>
				<th>Diterima O/</th>
				<th>Disetujui O/</th>
				<th>Dikembalikan O/</th>
			</tr>
			<tr>
				<td height='50'></td>
				<td height='50'></td>
				<td height='50'></td>
				<td height='50'></td>
			</tr>
		</table>
		</th>
		<th align='right'>".date("d M, Y",strtotime("+0 day", strtotime(date('Y-m-d'))))." <br> Nama Customer : ".$dataRR->row()->nama_member." <br> ".$dataRR->row()->nama_kota." <br> Ex_Inv : ".$dataRR->row()->no_nota."</th>
	</tr>
</table>
<br><br>
<table border='1' width='100%' cellspacing='0'>
	<tr>
		<th>Product</th>
		<th>Keterangan</th>
		<th>Qtty</th>
		<th>Harga/Unit</th>
		<th>Jumlah</th>
	</tr>";
	$total_pembayaran = 0;
	foreach($dataRR->result() as $data){
		$total = $data->qty_change * $data->price_change;
		//$total_pembayaran = $total_pembayaran + $total;
		// if($data->price_before ==  $data->price_change){
		// 	if($data->qty_before == $data->qty_change){
		// 		$total_qty = $data->qty_before;
		// 	}else{
		// 		$total_qty = $data->qty_before - $data->qty_change;
		// 	}
		// }else{
		// 	$total_qty = $data->qty_change - $data->qty_before;
		// }
		if($data->qty_before == $data->qty_change){
				$total_qty = $data->qty_before;
			}else{
				$total_qty = $data->qty_before - $data->qty_change;
			}
		$totals = $total_qty * $data->price_change;

		if($jenis == "REV"){
			if($data->qty_before > $data->qty_change){
				$tandaop = "-";
			}elseif($data->qty_before == $data->qty_change){
				$tandaop = "";
			}else{
				$tandaop = "+";
			}
		}else{
			$tandaop = "";
		}
		
		echo"
	<tr>
		<td>".$data->nama_produk."</td>
		<td>".$data->deskripsi."</td>
		<td>".$tandaop ."".$total_qty." ".$data->nama_satuan."</td>
		  ";if($jenis == "RET"){
				echo"
				<td align='right'>Rp. ".number_format($data->price_change,2,',','.')."</td>
				<td align='right'>Rp. ".number_format($totals,2,',','.')."</td>";
			}else{
				if($data->price_before ==  $data->price_change){
					if($data->price_before <  $data->price_change){
						$harga_selisih_satuan = $data->price_change - $data->price_before;
					}else{
						$harga_selisih_satuan = $data->price_before - $data->price_change;
					}
				}else{
					//$harga_selisih_satuan = $data->price_change;
					if($data->price_before <  $data->price_change){
						$harga_selisih_satuan = $data->price_change - $data->price_before;
					}else{
						$harga_selisih_satuan = $data->price_before - $data->price_change;
					}
				}
				if($data->price_before >  $data->price_change){
				echo"
				<td align='right'>(Rp. ".number_format($harga_selisih_satuan,2,',','.').")</td>";
				$total_selisih = $total_qty * $harga_selisih_satuan;
				echo"
				<td align='right'>(Rp. ".number_format($total_selisih,2,',','.').")</td>";
				}else{
				echo"
				<td align='right'>Rp. ".number_format($harga_selisih_satuan,2,',','.')."</td>";
				$total_selisih = $total_qty * $harga_selisih_satuan;
				echo"
				<td align='right'>Rp. ".number_format($total_selisih,2,',','.')."</td>";
				}
				
			}
			if($jenis == "RET"){
				$total_pembayaran = $total_pembayaran + $totals;
			}else{
				$total_pembayaran = $total_pembayaran + $total_selisih;
			}
			echo"
		
	</tr>";}
	echo"

</table>
<table border='0' width='100%'>
	";
	$ppn = $total_pembayaran * 10 / 100;
    $grandTotal = $total_pembayaran + $ppn;
	echo"
	<tr>
		<td align='right'>
			<h4>TOTAL : Rp. ".number_format($total_pembayaran,2,',','.')."</h4>
		</td>
	</tr>
	<tr>
		<td align='right'>
			<h4>PPN 10% : Rp. ".number_format($ppn,2,',','.')."</h4>
		</td>
	</tr>
	<tr>
		<td align='right'>
			<h4>GRAND TOTAL : Rp. ".number_format($grandTotal,2,',','.')."</h4>
		</td>
	</tr>
</table>
<br>
	<p>Note :<br>
	".$dataRR->row()->note."
	</p>
	<br>
	<table border='1' cellspacing='0' width='30%'>
		<tr>
			<td align='center' width='15%'></td>
			<td align='center'>Invoice Sudah Lunas</td>
			<td align='center'>SALES</td>
		</tr>
		<tr>
			<td align='center' width='15%'></td>
			<td align='center'>Invoice Belum Lunas</td>
			<td align='center'></td>
		</tr>
	<table>
	<p align='right'>Print By : ".$_SESSION['rick_auto']['fullname'].", ".date("d M Y H:i:s",strtotime("+0 day", strtotime(date('Y-m-d H:i:s'))))." 
";?>

<script>
	window.print();
</script>