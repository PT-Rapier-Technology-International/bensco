<?php

$getPerusahaan = $this->model_master->getPerusahaanByID($getInvoice->perusahaan_id)->row();

// $update = $this->db->set('count_print',$getInvoice->count_print + 1)->where('id',$getInvoice->id)->update('invoice');

// $detailInvoice = $this->model_invoice->getInvoiceDetailByInvoiceId($getInvoice->id);

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

    size: landscape; 

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

			<td width="60%">

				<p style="margin-left:30%"><table width="100%">

				<tr>

					<!-- <td width="50%"> -->
					<td>

						<table width="100%">

								<tr>

									<td><b style="margin-left:25%;font-size: 30px;"><?php echo strtoupper($getPerusahaan->name);?></b></td>

								</tr>

								<tr>

									<td align="left" style="margin-left:25%"><p style="margin-left:25%"><?php echo strtoupper($getPerusahaan->telephone);?></p></td>

								</tr>

								<tr>

									<td align="left" style="margin-left:25%"><p style="margin-left:25%"><?php echo strtoupper($getPerusahaan->city);?></p></td>

								</tr>

						</table>

					</td>

				</tr>

				</table></p>

			</td>

			<td width="40%">

				<table width="100%">

				<tr>

					<td width="50%">

						<table width="100%">

								<tr>

									<td align="right"><h3>Expedisi : <?php echo $getInvoice->expedisi;?><br>

									Via Expedisi : <?php echo $getInvoice->via_expedisi;?></h3></td>

								</tr>

								<?php

								$getCol = $this->model_invoice->getInvoiceDetailByInvoiceIdCollyDesc($getInvoice->id)->row();

								?>

								<tr>

									<td align="right"><h3>Colly : <?php echo $getCol->colly_to;?></h3></td>

								</tr>

						</table>

					</td>

				</tr>

				</table>

			</td>

		</tr>

	</table>

	<p style="padding-left:50%">

	<table border="0">

		<tr>

			<td></td>

			<td style="height: 50px;">Kepada Yth :</td>

			

		</tr>

		<tr>

			<td></td>

			<td><h2><?php echo strtoupper($getInvoice->member_name);?></h2></td>



		</tr>

		<tr>

			<td></td>

			<td><p style="font-size: 20px;"><?php echo strtoupper($getInvoice->alamat_member_toko);?></p></td>

		<tr>

			<td></td>

			<td><p style="font-size: 20px;"><?php echo strtoupper($getInvoice->kota_member);?></p></td>

		</tr>

		<?php

		$getCodeArea = $this->model_master->getCitybyId($getInvoice->id_kota)->row();

		?>

		<tr>

			<td></td>

			<td><p style="font-size: 20px;">+62<?php echo $getCodeArea->area_code;?> <?php echo strtoupper($getInvoice->phone_member);?></p></td>

		</tr>

	</table>

	</p>

</body>



</html>