<?php

echo"
<div class='content-wrapper'>
				<!-- Bordered striped table -->
				<div class='panel panel-flat'>
					<div class='panel-heading'>
						<h5 class='panel-title'>List Master Kota</h5>
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
									<th>Nama</th>
									<th>Singkatan</th>
                                    <th>Aksi</th>
								</tr>
							</thead>
							<tbody id='show_data'>
								<tr>
									
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!-- /bordered striped table -->
</div>";
?>
<!-- <script>

    var save_method; //for save method string
    var table;

    $(document).ready(function() {
        //datatables
        table = $('#example').DataTable({ 
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php //echo site_url("admin/produk/json")?>",
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columns": [
            	{"data": "no",width:170},
                {"data": "product_code",width:170},
                {"data": "product_name",width:100},
                {"data": "nama_kategori",width:100}
            ],

        });

    });
</script> -->

<!-- <script>

    tampil_data(); //pemanggilan fungsi tampil barang.
    
    
    //fungsi tampil barang
    function tampil_data(){
    $('#loading').show();
    var base_url = '<?php //echo base_url();?>'
      //alert('dsini');
        $.ajax({
            type  : 'ajax',
            url   : '<?php //echo base_url("admin/produk/getData")?>',
            async : false,
            dataType : 'json',
            success : function(data){
            	$('#loading_place').hide();
                var html = '';
                var i;
                for(i=0; i<data.length; i++){
                	var no = i + 1;
                    html += '<tr>'+
                    		'<td>'+no+'</td>'+
                            '<td>'+data[i].product_code+'</td>'+
                            '<td>'+data[i].product_name+'</td>'+
                            '<td>'+data[i].nama_kategori+'</td>'+
                            '<td>'+data[i].nama_satuan+'</td>'+
                            '<td class="text-right">'+data[i].normal_price+'</td>'+
                            '<td><img src="'+base_url+data[i].product_cover+'" width="80px;" height="80px;"></td>'+
                            '<td width="20%"> <a href="#" data-toggle="modal" data-target="#confirmation_modal" onclick=javascript:view_detail_image('+data[i].id+') class="btn btn-primary btn-icon"><i class="icon-images2"></i></a> <a href='+base_url+'admin/produk/edit/'+data[i].id+' class="btn btn-warning btn-icon"><i class="icon-pencil7"></i></a></td>'
                            '</tr>';
                }
                $('#show_data').html(html);
                $('#example').dataTable();
            }

        });
    }
</script> -->

<script type="text/javascript">
    var table;

        //datatables
        table = $('#example').DataTable({ 

            "processing": true, 
            "serverSide": true, 
            "order": [], 
            
            "ajax": {
                "url": "<?php echo site_url('admin/master/getDataCity')?>",
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