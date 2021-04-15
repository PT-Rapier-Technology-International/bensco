<?php
if(isset($_SESSION['rick_auto']['filter_bo_status'])){
	if($_SESSION['rick_auto']['filter_bo_status'] == 1){
		$trbt = "style='display:show'";
		$trt = "style='display:none'";
		$trs = "style='display:none'";
		$trsd = "style='display:none'";
		$trst = "style='display:none'";
		$lblSt = "BELUM TERKIRIM";
	}elseif($_SESSION['rick_auto']['filter_bo_status'] == 2){
		$trbt = "style='display:none'";
		$trt = "style='display:show'";
		$trs = "style='display:none'";
		$trsd = "style='display:none'";
		$trst = "style='display:none'";
		$lblSt = "TERKIRIM";
	}else{
		$trbt = "style='display:show'";
		$trt = "style='display:show'";
		$trs = "style='display:show'";
		$trsd = "style='display:show'";
		$trst = "style='display:show'";
		$lblSt = "SEMUA";
	}
}
echo"
&nbsp; <h3>STATUS : ".$lblSt."</h3>
<table class='table'>
	<tr>
		<th>No</th>
		<th>Tanggal</th>
		<th>Nama Barang</th>
		<th>Sub Category</th>
		<th>Qty BO</th>
		<th class='th_status' ".$trs.">Status</th>
	</tr>		
	";
		$no = 0;
		foreach($getPoBo->result() as $pobo){
			$no++;
		echo"
	<tr class='tr_belum_terkirim' ".$trbt.">
		<td>$no</td>
		<td>".date("d M Y",strtotime($pobo->dateorder))."</td>
		<td>".$pobo->nama_produk."</td>
		<td>".$pobo->nama_kategori."</td>
		<td>".$pobo->qty." ".$pobo->nama_satuan."</td>
		<td class='td_status' ".$trsd.">Belum Terkirim</td>
	</tr>";
		}
		$no = $getPoBo->num_rows();
		foreach($getPoBoTerkirim->result() as $po){
			$no++;
		echo"
	<tr class='tr_terkirim' ".$trt.">
		<td>$no</td>
		<td>".date("d M Y",strtotime($po->dateorder))."</td>
		<td>".$po->nama_produk."</td>
		<td>".$po->nama_kategori."</td>
		<td>".$po->qty." ".$po->satuan."</td>
		<td class='td_status' ".$trst.">Terkirim</td>
	</tr>";}
		echo"
</table>
";

?>