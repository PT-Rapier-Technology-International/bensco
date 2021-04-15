<?php

$update_read = $this->db->set('read',1)->update('transaction_purchase');



//$namapt = "PT.RA";

// $getPurchasee = $this->model_purchase->getPurchaseByPerusahaan($namapt);

// $noGen = explode("/",$getPurchasee->row()->nonota);

//             $noPODesc = substr($noGen[0], 5);

//             if($getPurchasee->num_rows() > 0){

//                 $genUnik = $noPODesc + 1;

//             }else{

//                 $genUnik = 1;

//             }

echo"

<div class='content-wrapper'>



<!-- Invoice archive -->

<div class='panel panel-white'>

	<div class='panel-heading'>

		<h6 class='panel-title'>Purchase Order</h6>

		<div class='heading-elements'>

			<ul class='icons-list'>

        		<li><a data-action='collapse'></a></li>

        		<li><a data-action='reload'></a></li>

        		 

        	</ul>

    	</div>

	</div>



    <div class='table-responsive'>

    <div style='margin-bottom:1%;margin-top:1%' class='col-sm-12'>

        <div class='col-sm-8'>

            <select class='form-control' id='cmbPerusahaanFilter' name='cmbPerusahaanFilter'>

                <option value='0'>Semua Perusahaan</option>

                ";

                if(isset($_SESSION['rick_auto']['po_perusahaan_filter'])){

                foreach($getPerusahaan->result() as $perusahaan){

                    echo"

                <option value='".$perusahaan->id."' ";if($perusahaan->id == $_SESSION['rick_auto']['po_perusahaan_filter']){echo"selected";}else{}echo">".$perusahaan->name."</option>";}

            }else{

                foreach($getPerusahaan->result() as $perusahaan){

                echo"

                <option value='".$perusahaan->id."'>".$perusahaan->name."</option>

                ";}

            }echo"

            </select>

        </div>

        <div class='col-sm-4'>

            <a href='#!' onclick=javascript:filter_perusahaan_po(); class='btn btn-primary btn-labeled'><b><i class='icon-search4'></i></b> Cari </a>

            </div>

    </div>

	<table class='table datatable-basic' id='tabelPO'>

		<thead>

			<tr>

				<th>#</th>

                <th>Customer</th>

                <th>Action Status</th>

                <th>Status</th>

                <th>Total (Rp.)</th>

                <th>Ekspedisi</th>

                <th>Tanggal Update</th>

                <th>Aksi</th>

                



            </tr>

		</thead>

		<tbody>

            <tr>

            </tr>

        </tbody>

    </table>

    </div>

</div>

<!-- /invoice archive -->





<!-- Modal with invoice -->

<div id='invoice' class='modal fade'>

	<div class='modal-dialog modal-full'>

		<div class='modal-content'>

			<div id='tempatDetail'>

			

				

			</div>

		</div>

	</div>

</div>

<!-- /modal with invoice -->



</div>

";?>



<script type="text/javascript">

    var table;



        //datatables

        table = $('#tabelPO').DataTable({ 

            //"stateSave": true,

            "pageLength": 10,

            "searching": true,

            "processing": true, 

            "serverSide": true, 

            "paging": true,

            // "order": [[7, "asc"]], 
            "order": [],

            // "columnDefs": [ { goals: [2,6]  }], 

            

            "ajax": {

                "url": "<?php echo site_url('admin/purchase/getDataPO')?>",

                "type": "POST"

            },


            "columnDefs": [
                { 
                    "targets": [ 0,1,2,3,4,5,6,7 ], //first column / numbering column
                    "orderable": false, //set not orderable
                    // "targets": 2, "orderSequence": ['desc']

                },
                // { type: 'natural', targets: 2 }
                /*{targets: 7, orderSequence: ['asc']},
                {targets: 0, orderSequence: ['desc']}  */
                ],
            

            // "columnDefs": [

            // { 

            //     "targets": [ 3 ], 

            //     "orderable": true, 

            // },

            // ],



        });



</script>