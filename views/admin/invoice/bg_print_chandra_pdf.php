<?php
$jenis = $this->uri->segment(5);
$getPerusahaan = $this->model_master->getPerusahaanByID($getInvoice->perusahaan_id)->row();

$update = $this->db->set('count_print',$getInvoice->count_print + 1)->where('id',$getInvoice->id)->update('invoice');

$detailInvoice = $this->model_invoice->getInvoiceDetailByInvoiceId($getInvoice->id);

//$changeTanggal = $this->uri->segment(5);

//$changeTanggal = $getInvoice->dateprint

$changeTanggal = date('Y-m-d H:i:s');

// if($getInvoice->count_print == 1){

// 	foreach($detailInvoice->result() as $detailInv){

// 	    $data_produk = $this->model_produk->getProductByCode($detailInv->product_code)->row();

// 	    $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();

// 	    //print_r($detailInv->qty_kirim);

// 	    $pengurangan_stok = $cekStok->stok - $detailInv->qty_kirim;

// 	  //  echo "".$detailInv->qty_kirim."";

// 	  $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');

// 	}

// }else{

	

// }



$hari = array ( 1 =>    'Senin',

			'Selasa',

			'Rabu',

			'Kamis',

			'Jumat',

			'Sabtu',

			'Minggu'

		);



// Misal hari ini adalah sabtu

//echo date('N'); // Hasil 6

//echo $hari[ date('N') ];
?>
<!DOCTYPE>

<html>

<head>

	<title></title>
<style type='text/css' media='print'>

  @page {

    size: auto;  

    margin: 0;

    margin-left: 0.1cm; 

  }

</style>

<style>

  body{

    padding-left: 1.3cm;

    padding-right: 1.3cm; 

    padding-top: 1.1cm;

  }

</style>


	<table width="100%" cellspacing="0">
		<tr>

			<td><b style="margin-top:0.3mm; font-size: 25PX;"><?php echo strtoupper($getPerusahaan->name);?></b></td>

			<td style="float: right; right:0;"><b style="margin-top:0.3mm; font-size: 14px;">Faktur No : <?php echo $getInvoice->nonota;?></b><br>
			<?php
			$num = date("N",strtotime($getInvoice->dateorder)); 
			?>

			<b style="margin-top:0.3mm; font-size: 14px;">Hari / Tgl : <?php echo $hari[$num];?>, <?php echo date("d F Y",strtotime("+0 day", strtotime($getInvoice->dateorder)));?></b></td>
		</tr>

	</table>


	<table width="100%" cellspacing="0">

		<tr>

			<td width="50%" style="height: 2px; vertical-align: top;">

				<table width="100%">

				<tr>

					<td width="50%" style="height: 2px; vertical-align: top;">
						<?php
					$getPerusahaanProfil = $this->model_master->getPerusahaanByID($getInvoice->perusahaan_id)->row();
					?>

						<table width="100%">

								<tr>

									<td style="height: 2px; vertical-align: top;"><b><?php echo $getPerusahaanProfil->address;?> <?php echo $getPerusahaanProfil->city;?></b></td>

								</tr>

								<tr>

									<td style="height: 2px; vertical-align: top;"><b>Telp : <?php echo $getPerusahaanProfil->telephone;?></b></td>

								</tr>

								<tr>

									<td style="height: 2px; vertical-align: top;"><b>NPWP : 66.502.535.9-047.000</b></td>

								</tr>

						</table>

					</td>

				</tr>

				</table>

			</td>

			<td width="50%" style="height: 2px; vertical-align: top;">
	
				<table width="100%">

				<tr>

					<td width="50%" style="height: 2px; vertical-align: top;">

						<table width="100%">

								<tr>

									<td style="height: 2px; vertical-align: top;"><b>Kepada Yth :</b></td>

								</tr>

								<tr>

									<td style="height: 2px; vertical-align: top;"><b><?php echo strtoupper($getInvoice->nama_member);?></b></td>

								</tr>

								<tr>

									<td style="height: 2px; vertical-align: top;"><b><?php echo $getInvoice->alamat_member;?></b></td>

								</tr>

								<tr>

									<td style="height: 2px; vertical-align: top;"><b><?php echo $getInvoice->kota_member;?></b></td>

								</tr>

								<tr>

									<td style="height: 2px; vertical-align: top;" align="left"><b><b>Expedisi : <?php echo $getInvoice->expedisi;?></b></b></td>

								</tr>

								<tr>
									<?php 
									if($getInvoice->via_expedisi == ""){
									}else{
										?>
									<td style="height: 2px; vertical-align: top;" align="left"><b><b>Via Expedisi : <?php echo $getInvoice->via_expedisi;?></b></b></td>
									<?php } ?>
								</tr>

						</table>

					</td>

				</tr>

				</table>

			</td>

		</tr>
	</table>

	<div class="table-responsive">

	    <table width="100%" border="1" cellspacing="0" style="margin-top:4px">

	        <thead>

	            <tr>

	                <th style="height: 20px;" align="center"><b>PRODUK</b></th>

	                <th style="height: 20px;" align="center"><b>KET</b></th>

	                <th style="height: 20px;" align="center" class="col-sm-1"><b>QTY</b></th>

	                <th style="height: 20px;" align="center" class="col-sm-1"><b>HARGA / UNIT</b></th>

	                <!--<th style="height: 20px;" align="center" class="col-sm-1"><b>Include Diskon</b></th>-->

	                <th style="height: 20px;" align="center" class="col-sm-1"><b>HARGA TOTAL</b></th>

            	</tr>

	        </thead>

	        <tbody>
	        	<?php

	        	$getInvoiceDetail = $this->model_invoice->getInvoiceDetailByInvoiceId($getInvoice->id);

	        	$total_pembayaran = 0;

	        	foreach($getInvoiceDetail->result() as $detailInvoice){

	        		$total_pembayaran = $total_pembayaran + $detailInvoice->ttl_price;
	        		$discpersen = $total_pembayaran / $getInvoice->discount;

					$diskon = 100 / $discpersen;
	        		?>

	            <tr>

	                <td style="height: 10px;">

	                	<b><?php echo $detailInvoice->product_name;?></b>

                	</td>

                	<td style="height: 10px;"><?php echo $detailInvoice->deskripsi;?></td>

	                <td style="height: 10px;" align="center"><?php echo $detailInvoice->qty_kirim;?> <?php echo $detailInvoice->satuan;?></td>

	                <td style="height: 10px;" align="right"><?php echo number_format($detailInvoice->price,0,",",".");?></td>

	                <!--<td style="height: 10px;" align="right"><?= $diskon; ?>%</td>-->

	                <td style="height: 10px;" align="right"><span class="text-semibold"> <?php echo number_format($detailInvoice->ttl_price,0,",",".");?></span></td>

	            </tr><?php } ?>

	        </tbody>

	    </table>

	    <table width="100%" border="0" cellspacing="0">

            <tr>

            	<td style="height: 15px; vertical-align: center;" rowspan="9"><b>TOTAL PEMBAYARAN</b></td>

            	<td style="height: 10px; vertical-align: top;" colspan="3"><b>JUMLAH ORDER</b></td>

            	<td style="height: 10px; vertical-align: top;" align="right"><b>Rp. <?php echo number_format($total_pembayaran,0,",",".");?></b></td>

            </tr>
            <?php

	    $diskon = round($getInvoice->discount / $total_pembayaran * 100);
	    ?>

	   	 	<tr>

            	<td style="height: 10px; vertical-align: top;" colspan="3"><b>DISKON <?php echo  $diskon ;?>%</b></td>

            	<td style="height: 10px; vertical-align: top;" align="right"><b>Rp. <?php echo number_format($getInvoice->discount,0,',','.');?></b></td>

            </tr>
            <tr>

            	<td style="height: 10px; vertical-align: top;" colspan="3"><b>TOTAL SETELAH DISKON </b></td>

            	<td style="height: 10px; vertical-align: top;" align="right"><b>Rp. <?php echo number_format($getInvoice->total_before_ppn,0,',','.');?></b></td>

            </tr>
            <?php


            $ppn = $getInvoice->total_before_ppn * 10 / 100;

            $grandTotal = $getInvoice->total_before_ppn + $ppn;

            ?>

            <tr>

            	<td style="height: 10px; vertical-align: top;" colspan="3"><b>PPN 10% </b></td>

            	<td style="height: 10px; vertical-align: top;" align="right"><b>Rp. <?php echo number_format($ppn,0,",",".");?></b></td>

            </tr>

            <tr>

            	<td style="height: 10px; vertical-align: top;" colspan="3"><b>EXPEDISI </b></td>

            	<td style="height: 10px; vertical-align: top;" align="right"><b>Rp. 0</b></td>

            </tr>

            <tr>

            	<td style="height: 10px; vertical-align: top;" colspan="3"><b>LAIN-LAIN </b></td>

            	<td style="height: 10px; vertical-align: top;" align="right"><b>Rp. 0</b></td>

            </tr>

            <tr>

            	<td style="height: 10px; vertical-align: top;" colspan="3"><b>BIAYA ASURANSI </b></td>

            	<td style="height: 10px; vertical-align: top;" align="right"><b>Rp. 0</b></td>

            </tr>

            <tr>

            	<td style="height: 10px; vertical-align: top;" colspan="4"><hr width="100%" style="border-top: 3px dashed black;"></td>

            	<td></td>

            </tr>

            <tr>

            	<td style="height: 15px; vertical-align: top;" colspan="3"><b>TOTAL JUMLAH FAKTUR </b></td>

            	<td style="height: 15px; vertical-align: top;" align="right"><b>Rp. <?php echo number_format($grandTotal,0,",","."); ?></b></td>

            </tr>

	    </table>

	    <table border="0" width="100%">

	    	<tr>

	    		<td>

	    			<table width="90%">

	    				<tr>

							<td style="height: 2px; vertical-align: top;"><b>Nama</b></td>

							<td style="height: 2px; vertical-align: top;"><b>:</b></td>

							<td style="height: 2px; vertical-align: top;"><b><span class="text-semibold"><?php echo strtoupper($getPerusahaan->name);?></b></span></td>

						</tr>

						<tr>

							<td style="height: 2px; vertical-align: top;"><b>A/C</b></td>

							<td style="height: 2px; vertical-align: top;"><b>:</b></td>

							<td style="height: 2px; vertical-align: top;"><b><span><?php echo $getPerusahaan->rek_no;?></b></span></td>

						</tr>

						<tr>

							<td style="height: 2px; vertical-align: top;"><b>Bank</b></td>

							<td style="height: 2px; vertical-align: top;"><b>:</b></td>

							<td style="height: 2px; vertical-align: top;"><b><span class="text-semibold"><?php echo $getPerusahaan->bank_name;?></span></b></td>

						</tr>

						

					</table>

	    		</td>

	    		<td align="center">

	    			<table>

	    				<tr>

	    					<td align="center" style="height: 2px; vertical-align: top;"><b>Diterima Oleh</b></td>

	    				</tr>

	    				<tr>

	    					<td align="center" style="height: 2px; vertical-align: top;"><b><?php echo strtoupper($getInvoice->member_name); ?></b></td>

	    				</tr>

	    			</table>

	    			<p style="margin-top:100px"> .......................................................... </p>

	    		</td>
	    	</tr>

	    </table>

	    <table border="0" width="100%">

	    	<tr>

	    		<td>

				    <p size="2">

				    Memo : </p>

				    <p style="font-size:10px">Keterangan</p>

				    <p style="font-size:10px">1. PEMBAYARAN DENGAN CEK/GIRO HARUS MENCANTUMKAN NAMA <?php echo strtoupper($getPerusahaan->name); ?></p>

				    <p style="font-size:10px">2. PEMBAYARAN DAPAT DITRANSFER KE A/C <?php echo $getPerusahaan->rek_no; ?> A/N <?php echo strtoupper($getPerusahaan->name); ?></p>

				    <p style="font-size:10px">3. BARANG YANG SUDAH DIBELI TIDAK DAPAT DIKEMBALIKAN</p>

				</td>

			</tr>

		</table>



		<table border="0" width="100%">

			<tr>

				<td style="height: 15px; vertical-align: bottom;" align="right">";
						<?php 
						$nums = date("N",strtotime(date("Y-m-d"))); 
						?>

		    		<p style="font-size:10px; padding-left:80%; margin-top:10%;"><?php echo $hari[$nums]; ?>, <?php echo date("d F Y",strtotime("+0 day", strtotime($changeTanggal))); ?></p>

		    	</td>

		    </tr>

	    </table>

	</div>
</body>

</html>
