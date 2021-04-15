<?php
$uri4 = $this->uri->segment(4);
echo"
	<div class='panel panel-flat'>
		<div class='panel-heading'>
			<h5 class='panel-title'></h5>
			<div class='heading-elements'>
				<ul class='icons-list'>
            		<li><a data-action='collapse'></a></li>
            		<li><a data-action='reload'></a></li>
            	</ul>
        	</div>
		</div>

		<div class='panel-body'>
			<form class='form-horizontal' action='#'>
				<fieldset class='content-group'>
					<legend class='text-bold'>Laporan Back Order ".strtoupper($uri4)."</legend>

					<div class='form-group'>
						<label class='control-label col-lg-1'>Cari Kode / Nama Produk</label>
						<div class='col-lg-2'>
							<input type='text' class='form-control' id='txtProduk' name='txtProduk' placeholder='Cari berdasarkan Kode / Nama Produk'>
						</div>
						<label class='control-label col-lg-1'>Pilih Kategori</label>
						<div class='col-lg-2'>
								<div class='multi-select-full' style='width:100%'>
									<select class='multiselect-filtering' multiple='multiple' id='cmbKategori' name='cmbKategori'>
										";
									foreach($getKategori->result() as $kategori){
										echo"
										<option value=".$kategori->id.">".$kategori->cat_name."</option>
										";
									}echo"
									</select>
								</div>
						</div>
						<label class='control-label col-lg-1'>Dari Tanggal </label>
						<div class='col-lg-2'>
							<input type='date' class='form-control' placeholder='tanggal' id='tanggalFrom' name='tanggalFrom' placholder='Tanggal'>
						</div>
						<label class='control-label col-lg-1'>Sampai Tanggal </label>
						<div class='col-lg-2'>
							<input type='date' class='form-control' placeholder='tanggal' id='tanggalTo' name='tanggalTo' placholder='Tanggal'>
						</div>
					</div>
					<label class='control-label col-lg-1'>Status</label>
						<div class='col-md-3'>
			                	<select id='cmbStatus' name='cmbStatus' class='form-control'>
			                		<option value='0' selected>Semua Status</option>
			                		<option value='1'>Belum Terkirim</option>
			                		<option value='2'>Terkirim</option>
			                    </select>
			    		</div>
						<div class='col-md-2'>
							<a href='#!' onclick=javascript:filter_bo('".$uri4."') class='btn btn-primary'>Cari Data</a>
							
						</div>
						";
						if($uri4 == ""){
							echo"
						<div class='col-md-4'>
							<a href='".base_url("admin/purchase/print_bo/excel")."' target='_blank' class='btn btn-success'><i class='icon-printer position-left'></i>Excel</a>
							<a href='".base_url("admin/purchase/print_bo/print")."' target='_blank' class='btn btn-default'><i class='icon-printer position-left'></i>Print</a>
							<a href='".base_url("admin/purchase/print_bo/pdf")."' target='_blank' class='btn btn-danger'><i class='icon-printer position-left'></i>PDF</a>
						</div>";}else{
							echo"
						<div class='col-md-4'>
							<a href='".base_url("admin/purchase/print_bo_qty/excel")."' target='_blank' class='btn btn-success'><i class='icon-printer position-left'></i>Excel</a>
							<a href='".base_url("admin/purchase/print_bo_qty/print")."' target='_blank' class='btn btn-default'><i class='icon-printer position-left'></i>Print</a>
							<a href='".base_url("admin/purchase/print_bo_qty/pdf")."' target='_blank' class='btn btn-danger'><i class='icon-printer position-left'></i>PDF</a>
						</div>";
						}echo"

					</div>
				</fieldset>
			</form>
			<div id='div-ajax'>

			</div>
		</div>
	</div>
	<script>
	$('#city').select2({
		placeholder: 'Select a customer',
	    multiple: true,
	    allowClear: true,
	});
	</script>
	<script>
		$('#city > option').removeAttr('selected');
		$('#city').trigger('change');
	</script>

";?>

<script>
       $('select[id="cmbProduk"]').select2({
           ajax: {
               url: '<?php echo base_url('admin/purchase/dataProduk')?>',
              dataType: 'json',
              delay: 250,
              data: function(params) {
                //alert(params);
                return {
                  search: params.term
                }
              },
              processResults: function (data) {
              var results = [];
              $.each(data, function(index, item){
                results.push({
                    id: item.id,
                    text : item.product_code+'-'+item.product_name,
                });
              });
              return{
                results: results
              };
            }
          }
      });

</script>
<!-- 
<script>
	$('#cmbKategori').select2();
</script> -->