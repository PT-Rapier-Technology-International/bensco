<?php

$perusahaan = $this->uri->segment(5);

$jenis_cetak = $this->uri->segment(6);

$pengiriman = $this->uri->segment(7);

$getPerusahaan = $this->model_master->getPerusahaanByID($perusahaan)->row();

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

if($pengiriman == "TT"){

	$judul = "Tanda Terima";

	// $no_tt = $this->uri->segment(4);
	$no_tt = str_replace("PT.E","PT.ETC",$this->uri->segment(4));

}else{

	$judul = "Data Pengiriman";

	$no_tt = "";

}

//echo $getPerusahaan->name;

?>


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


  }

</style>

	<hr>

		<table border="0" width="100%" cellspacing="0">
	    	<tr>
	    		<td align="left">
					<p style="font-size:24px;"><?php echo strtoupper($getPerusahaan->name); ?></p>
	    		</td>

	    		<td align="center">

					<p style="font-size:18px;"><?php echo $no_tt; ?></p>

	    		</td>

	    		<td align="right">

	    			<table>

	    				<tr>

							<td align="right">

								<p style="font-size:24px;"><?php echo $judul; ?></p>

							</td>

	    				</tr>

	    			</table>

	    		</td>
	    	</tr>

	    </table>

	<hr>



		<table border="0" width="100%">

		<tr>
				<td>

					<table border="0" width="100%">

						<tr>

							<th width="80%">

								<p align="left"; style="font-size:14px;">Nama Customer :</p>

							</th>

							<th width="20%">

								<p align="right"; style="font-size:15px;"><?php echo date("d M Y"); ?></p>

							</th>

						</tr>

						<tr>

							<td>

								<p style="font-size:14px;"><?php echo strtoupper($getMember->name); ?></p>

							</td>

						</tr>

						<tr>

							<td>

								<p style="font-size:14px;"><?php echo strtoupper($getMember->address_toko); ?></p>

							</td>

						</tr>

						<tr>

							<td>

								<p style="font-size:14px;"><?php echo strtoupper($getMember->city); ?></p>

							</td>

						</tr>

					</table>

				</td>
	    </tr>

		</table>



	<div class="table-responsive">

	    <table width="100%" border="1">

	        <thead>

	            <tr>

	                <th>No</th>

	                <th>Tertanggal</th>

	                <th class="col-sm-1">No Invoice</th>

	                <th class="col-sm-1">Nilai</th>

	                <th class="col-sm-1">Jumlah</th>

	                <th class="col-sm-1">Keterangan</th>

            	</tr>

	        </thead>
	        <?php

	        if($pengiriman == "TT"){

	        	echo"

	        <tbody>";

	        	//$getInvoiceDetail = $this->model_invoice->getInvoiceDetailByInvoiceId($getInvoice->id);

	        	$total_pembayaran = 0;

	        	$total_pembayarans = 0;

	        	$no = 0;

	        	foreach($getInvoice->result() as $invoice){

	        		$total_pembayaranx = $this->model_invoice->getTotalInvoiceByInvoice($invoice->id_invoice)->row()->total_harga;
	        		$getInv = $this->model_invoice->getInvoiceById($invoice->id_invoice)->row();
	        		$total_pembayaran = $total_pembayaranx - $getInv->discount;

	        		//$total_pembayaran = $total_pembayaran + $invoice->total ;

	        		$no++;

	        		echo"

	            <tr height='25px'>

	            	<td style='font-size:13px;'>$no</td>

	                <td style='font-size:13px;'>

	                	".date("d M Y",strtotime("+0 day", strtotime($invoice->dateorder)))."

                	</td>

                	<td style='font-size:13px;'>

	                	".str_replace("PT.E","PT.ETC",$invoice->nonota)."

                	</td>";
					$total_pembayaran2 = 0;

					$grandTotal = 0;
					$getInvoiceDetail = $this->model_invoice->getInvoiceDetailByInvoiceId($invoice->id);
		        	foreach($getInvoiceDetail->result() as $detailInvoice){
		        		$sub_tot = ceil($detailInvoice->price) * $detailInvoice->qty_kirim;
		        		$total_pembayaran2 = $total_pembayaran2 + $sub_tot;
		        	}

					$diskon = ceil($invoice->discount / $total_pembayaran2 * 100);
					$invoice->total_before_ppn = $total_pembayaran2 - ceil($invoice->discount);

					$ppn = $invoice->total_before_ppn * 10 / 100;

					$grandTotal = ceil($invoice->total_before_ppn) + ceil($ppn);

                	// $total_pembayaran_ppn = $total_pembayaran * 10 / 100;

                	// $ppn_total = $total_pembayaran_ppn + $total_pembayaran;

                	//$total_pembayarans = $total_pembayarans + $ppn_total;
					$total_pembayarans = $total_pembayarans + $grandTotal;

                	echo"
					<!--nilai-->
                	<td align='right' style='font-size:13px;'>Rp. ".number_format(ceil($grandTotal),2,',','.')."</td> 
					<!--jumlah-->
	                <td align='right' style='font-size:13px;'>Rp. ".number_format(ceil($grandTotal),2,',','.')."</td>

	                <td style='font-size:13px;'></td>

	            </tr>";}
	            ?>

	            <tr height="25px">

	            	<td rowspan="6" colspan="5"><p style="font-size:14px;"><b>TOTAL PEMBAYARAN</b></p></td>

	            	<td align="right" style="height: 15px;"><p style="font-size:14px;"><b>Rp.  <?php echo number_format($total_pembayarans,2,',','.');?></b></p></td>

	            </tr>

	        </tbody>
	    <?php }else{

	        	echo"

	        <tbody>";

	        	//$getInvoiceDetail = $this->model_invoice->getInvoiceDetailByInvoiceId($getInvoice->id);

	        	$total_pembayaran = 0;

	        	$no = 0;

	        	foreach($getInvoice->result() as $invoice){

	        		$total_pembayaran = $total_pembayaran + $invoice->nilai ;

	        		$no++;

	        		echo"

	            <tr height='30px'>

	            	<td style='font-size:13px;'>$no</td>

	                <td style='font-size:13px;'>

	                	".date("d M Y",strtotime("+0 day", strtotime($invoice->dateorder)))."

                	</td>

                	<td style='font-size:13px;'>

	                	".$invoice->nonota."

                	</td>



                	<td align='right' style='font-size:13px;'>Rp. ".number_format($invoice->nilai,2,',','.')."</td>

	                <td align='right' style='font-size:13px;'>Rp. ".number_format($invoice->nilai,2,',','.')."</td>

	                <td style='font-size:13px;'>

	                Expedisi(No. Resi) : ".$invoice->expedisi."(".$invoice->resi_no.") <br>";

	                $dataTT = $this->model_invoice->getNoTTbyInvoiceId($invoice->id)->row();

	                echo"

	                Tanggal Kirim : ".date("d M Y",strtotime("+0 day", strtotime($dataTT->delivery_date)))."<br>



	                </td>

	            </tr>";}
	            ?>

	            <tr height='30px'>

	            	<td rowspan='6' colspan='5'><p style='font-size:13px;'><b>TOTAL PEMBAYARAN</b></p></td>

	            	<td align='right'><p style='font-size:13px;'><b>Rp. <?php echo number_format($total_pembayaran,2,',','.'); ?></b></p></td>

	            </tr>

	        </tbody>

	        <?php

	        }?>

	    </table>

	    <table border="0" width="100%">

			<tr>

				<td>

				</td>

	    		<td align="right" width="253px">

	    			<table width="253px">

	    				<tr>

							<td align="center">

								<p style="font-size:12px;">Diterima Oleh</p>

							</td>

	    				</tr>

	    				<tr>

	    					<td align="center"><h5><?php echo strtoupper($getMember->name); ?></h5><br><br><br></td>

						</tr>

						<tr>

	    					<td align="center"><p style="margin-top:15px"> .......................................................... </p></td>

	    				</tr>

	    			</table>

	    		</td>
	    	</tr>

		</table>







		<table border="0" width="100%">

        	<tr>

            	<td rowspan="1" colspan="2">

                	<?php

	    			if($pengiriman == ""){

	    			?>

	    			<p size="2">

	    			Memo : </p> <?php } ?>

                </td>

            </tr>

	    	<tr>

	    		<td align="left" valign="top" width="60px">

                	<p style="font-size:16px">NOTE : </p>

                </td>

                <td align="left" valign="top">
				<?php
                if($pengiriman == "TT"){

                ?>

                	<b><p style="font-size:14px; font-type:bold">PEMBAYARAN DENGAN CEK/GIRO HARUS MENCANTUMKAN NAMA <?php echo strtoupper($getPerusahaan->name); ?><br>PEMBAYARAN DAPAT DITRANSFER KE A/C <?php echo $getPerusahaan->rek_no; ?> A/N <?php echo strtoupper($getPerusahaan->name); ?></p></b>
                <?php } ?>

                </td>

	    	</tr>

	    </table>



	</div>



	<div class="panel-body">

		<div class="row invoice-payment">

			<div class="col-sm-7">

				<!-- <div class="content-group">

					<h6>Authorized person</h6>

					<div class="mb-15 mt-15">

						<img src="assets/images/signature.png" class="display-block" style="width: 150px;" alt=">

					</div>



					<ul class="list-condensed list-unstyled text-muted">

						<li>Eugene Kopyov</li>

						<li>2269 Elba Lane</li>

						<li>Paris, France</li>

						<li>888-555-2311</li>

					</ul>

				</div> -->

			</div>

		</div>



		<!-- <h6>Other information</h6>

		<p class='text-muted'>Thank you for using Limitless. This invoice can be paid via PayPal, Bank transfer, Skrill or Payoneer. Payment is due within 30 days from the date of delivery. Late payment is possible, but with with a fee of 10% per month. Company registered in England and Wales #6893003, registered office: 3 Goodman Street, London E1 8BF, United Kingdom. Phone number: 888-555-2311</p> -->

	</div>

	<?php
	if($jenis_cetak == "print"){
		echo"

	<script>

		window.print();

		window.onfocus=function(){ window.close();}

	</script>";}

	if($jenis_cetak == "excel"){
		header("Content-type: application/vnd-ms-excel");

		header("Content-Disposition: attachment; filename=Print_Tanda_Terima_".date('d M y').".xls");
	}
	echo"

";?>
