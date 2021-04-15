<?php
echo"
<!-- Main content -->
<div class='content-wrapper'>

	<!-- Media library -->
	<form id='formAdd'>
	<div class='panel panel-white'>
		<div class='panel-heading'>
			<h6 class='panel-title text-semibold'>Form Pembuatan Purchase Order Baru</h6>
			<div class='heading-elements'>
				<ul class='icons-list'>
            		<li><a data-action='collapse'></a></li>
            		<li><a data-action='reload'></a></li>
            	</ul>
        	</div>
		</div>
		<div class='col-sm-12'>
            <div class='panel-body'>
                <div class='row'>

                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Customer : </label>
                            <select id='cmbMember' name='cmbMember' data-placeholder='Pilih Customer' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true' onchange=javascript:pilihMember()>
                                <option selected disabled>Pilih Customer</option>
                                 ";
                                 foreach($getMembers->result() as $member){
                                    echo"<option value='".$member->id."'>".$member->name." - ".$member->city."</option>";
                                 }
                                 echo"
                            </select>
                        </div>
                    </div>

                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Sales : </label>
                             <select id='cmbSales' name='cmbSales' data-placeholder='Pilih Sales' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true'>
                        	 <option selected disabled>Pilih Sales</option>
                    		</select>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Expedisi : </label>
                             <select id='cmbExpedisi' name='cmbExpedisi' data-placeholder='Pilih Expedisi' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true'>
                        	 <option selected disabled>Pilih Expedisi</option>
                        	 ";
                        	 foreach($getExpedisi->result() as $expedisi){
                        	 	echo"
                        	 	<option value=".$expedisi->id.">".$expedisi->name."</option>
                        	 	";
                        	 }echo"
                    		</select>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Via Expedisi : </label>
                             <select id='cmbViaExpedisi' name='cmbViaExpedisi' data-placeholder='Pilih Via Expedisi' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true'>
                             <option selected disabled>Pilih Via Expedisi</option>
                             ";
                             foreach($getViaExpedisi->result() as $expedisi){
                                echo"
                                <option value=".$expedisi->id.">".$expedisi->name."</option>
                                ";
                             }echo"
                            </select>
                        </div>
                    </div>
                    <div class='col-md-12'>
                        <div class='form-group'>
                            <label>Catatan : </label>
                             <textarea rows='4' cols='50' id='note' name='note' class='form-control'></textarea>
                        </div>
                    </div>
                </div>  
                
            </div>
		</div>
    	<div class='col-md-12'>
            <!-- colmd2 untuk yng lama -->
    		<div class='col-md-3' style='background-color:#c5c5c5'>
				<div class='form-group'>
					<label>Produk: </label>
                	<select id='cmbProduk_1' name='cmbProduk_1' class='form-control' onchange=javascript:proses_hitung(1)>
                		<option value='0' selected disabled>Pilih Produk</option>
                    </select>
				</div>
    		</div>
    		<div class='col-md-3' style='background-color:#c5c5c5'>
				<div class='form-group'>
					<label>Harga Satuan (Rp.) : </label>
                	<input type='text' class='form-control' id='priceSatuan_1' name='priceSatuan_1' readonly>
				</div>
    		</div>
    		<div class='col-md-3' style='background-color:#c5c5c5'>
				<div class='form-group'>
					<label>Qty Order: </label>
                    <div class='input-group bootstrap-touchspin'>
                    <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurangProsesPO(1)>-
                    </button>
                    </span>
                    <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
                    </span>
                    <input type='text' id='addStok_1' name='addStok_1' value='1' class='touchspin-set-value form-control' style='display: block;' onkeyup=javascript:ketikProsesPO(1)>
                    <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
                    </span>
                    <span class='input-group-btn'>
                    <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambahProsesPO(1)>+
                    </button>
                    </span>
                    </div>
				</div>
    		</div>
    		<!-- <div class='col-md-3' style='background-color:#c5c5c5'>
				<div class='form-group'>
					<label>Perusahaan : </label>
                	<select class='form-control' id='cmbPerusahaan_1' name='cmbPerusahaan_1' onchange=javascript:pilih_perusahaan_proses_po(1)>
            		<option value='0' disabled selected>Pilih Perusahaan</option>
            		";foreach($getPerusahaan->result() as $perusahaans){
            			echo"
            				<option value='".$perusahaans->id."'>".$perusahaans->name."</option>
            			";
            		}echo"
            		</select>
				</div>
    		</div>
    		<div class='col-md-3' style='background-color:#c5c5c5'>
				<div class='form-group'>
					<label>Gudang : </label>
                	<div id='tempatGudang_1'>
			        <select class='form-control' id='cmbGudang_1' name='cmbGudang_1'>
			            <option value='0' selected>Pilih Gudang</option>
			            ";foreach($getGudang->result() as $gudang){
			                echo"
			                    <option value='".$gudang->id."'>".$gudang->name."</option>
			                ";
			            }echo"
			        </select>
			        </div>
				</div>
    		</div> -->
    		<div class='col-md-3' style='background-color:#c5c5c5'>
				<div class='form-group'>
					<label>Total Satuan (Rp.) : </label>
	            	<input type='text' class='form-control' id='priceTotal_1' name='priceTotal_1' readonly>
				</div>
			</div>
    	</div>	
    	<div id='tempatAjax'>

        </div>

        <input type='hidden' id='jmlProduk' name='jmlProduk' value='1'>

        <br>
        <div style='margin-bottom:10px;margin-left:10px'>
        	<a href='#!' onclick=javascript:tambahProdukPO() class='btn btn-primary btn-labeled'><b><i class='icon-plus-circle2'></i></b> Tambah Produk</a>
        </div>
        <div class='text-right'>
        	<button type='button' id='btnSimpan' onclick=javascript:simpanPembuatanPO() class='btn btn-success btn-labeled'><b><i class='icon-floppy-disk'></i></b> Simpan</button>
        </div>
    </div>

    <!-- /media library -->
    </form>
</div>
<!-- /main content -->

";?>

<script>
       $('select[id="cmbProduk_1"]').select2({
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