<?php
$uri4 = $this->uri->segment(4);
echo"
<div class='content-wrapper'>
				<!-- Bordered striped table -->
				<div class='panel panel-flat'>
					<div class='panel-heading'>
						<h5 class='panel-title'>Informasi Stok Produk</h5>
						<div class='heading-elements'>
							<ul class='icons-list'>
		                		<li><a data-action='collapse'></a></li>
		                		<li><a data-action='reload'></a></li>
		                		 
		                	</ul>
	                	</div>
					</div>

					<div class='panel-body'>
						<!-- <a href='".base_url("admin/produk/add")."' class='btn btn-info btn'>Tambah Produk</a> --> 
					</div>

					<div class='table-responsive'>
						<table class='table datatable-basic' id='example'>
							<thead>
								<tr>
									<th>#</th>
									<th>Kode Produk</th>
									<th>Nama Produk</th>
									<th>Kategori</th>
									<th>Satuan</th>
									<th class='text-center'>Informasi Stok</th>
								</tr>
							</thead>
							<tbody>
							<tbody id='show_data'>
								<tr>
									
								</tr>
							</tbody>
							</tbody>
						</table>
					</div>
				</div>
				<!-- /bordered striped table -->
</div>";
?>

<script type="text/javascript">
    var table;

        //datatables
        table = $('#example').DataTable({ 

            "processing": true, 
            "serverSide": true, 
            "order": [], 
            
            "ajax": {
                "url": "<?php echo site_url('admin/produk/getDataStok')?>",
                "type": "POST"
            },

            
            "columnDefs": [
            { 
                "targets": [ 0 ], 
                "orderable": false, 
            },
            ],

        });

</script>