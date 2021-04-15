<?php
echo"
<!-- Main content -->
<div class='content-wrapper'>

	<!-- Media library -->
	<form id='formAdd'>
	<div class='panel panel-white'>
		<div class='panel-heading'>
			<h6 class='panel-title text-semibold'>Form Mutasi Stok</h6>
			<div class='heading-elements'>
				<ul class='icons-list'>
            		<li><a data-action='collapse'></a></li>
            		<li><a data-action='reload'></a></li>
            	</ul>
        	</div>
		</div>
        <br>
        <div class='col-md-12'>
            <div class='col-md-3'>
                <div class='form-group'>
                    <label>Pilih Perusahaan : </label>
                    <select class='form-control' id='cmbPerusahaan' name='cmbPerusahaan'>
                    <option value='0' disabled selected>Pilih Perusahaan</option>
                    ";
                    foreach($getPerusahaan->result() as $perusahaan){
                        echo"<option value='".$perusahaan->id."'>".$perusahaan->name."</option>";
                    }echo"
                    </select>
                </div>
            </div>
            <div class='col-md-3'>
                <div class='form-group'>
                    <label>No. Transaksi : </label>
                    <input class='form-control' id='txtNoT' name='txtNoT'></input>
                </div>
            </div>
            <div class='col-md-2'>
                <div class='form-group'>
                    <label>Dari Gudang : </label>
                    <select class='form-control' id='cmbGudangFrom' name='cmbGudangFrom' >
                    <option value='0' selected>Pilih Gudang</option>
                        ";foreach($getGudang->result() as $gudang){
                            echo"
                                <option value='".$gudang->id."'>".$gudang->name."</option>
                            ";
                        }echo"
                    </select>
                </div>
            </div>
            <div class='col-md-2'>
                <div class='form-group'>
                    <label>Tujuan Gudang : </label>
                    <select class='form-control' id='cmbGudangTo' name='cmbGudangTo' >
                    <option value='0' selected>Pilih Gudang</option>
                        ";foreach($getGudang->result() as $gudang){
                            echo"
                                <option value='".$gudang->id."'>".$gudang->name."</option>
                            ";
                        }echo"
                    </select>
                </div>
            </div>
            <div class='col-md-2'>
                <div class='form-group'>
                    <label>Tanggal Mutasi : </label>
                    <input type='date' class='form-control' id='txtTglMutasi' name='txtTglMutasi' value='".date('Y-m-d')."'></input>
                </div>
            </div>
        </div>
        <hr>
        <br>
    	<div class='col-md-12'>
            <!-- colmd2 untuk yng lama -->
    		<div class='col-md-7'>
				<div class='form-group'>
					<label>Produk: </label>
                	<select id='cmbProduk_1' name='cmbProduk_1' class='form-control' onchange=javascript:pilihProduk(1)>
                		<option value='0' selected disabled>Pilih Produk</option>
                    </select>
				</div>
    		</div>
    		<div class='col-md-3'>
				<div class='form-group'>
					<label>Qty Mutasi: </label>
                    <div class='input-group bootstrap-touchspin'>
                    <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurangProsesMutasi(1)>-
                    </button>
                    </span>
                    <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
                    </span>
                    <input type='text' id='addStok_1' name='addStok_1' value='1' class='touchspin-set-value form-control' style='display: block;' onkeyup=javascript:ketikProsesMutasi(1)>
                    <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
                    </span>
                    <span class='input-group-btn'>
                    <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambahProsesMutasi(1)>+
                    </button>
                    </span>
                    </div>
				</div>
                <input type='hidden' class='form-control' id='txtJmlStok_1' name='txtJmlStok_1'></input>
            </div>
            <div class='col-md-2'>
                <div class='form-group'>
                    <label>Satuan: </label>
                    <input type='text' id='txtSatuan_1' name='txtSatuan_1' class='form-control' readonly>
                </div>
    		</div>
    	</div>	
    	<div id='tempatAjax'>

        </div>

        <input type='hidden' id='jmlProduk' name='jmlProduk' value='1'>

        <br>
        <div style='margin-bottom:10px;margin-left:10px'>
        	<a href='#!' onclick=javascript:addProdukMutasi() class='btn btn-primary btn-labeled'><b><i class='icon-plus-circle2'></i></b> Tambah Produk</a>
        </div>
        <div class='text-right'>
        	<button type='button' onclick=javascript:simpanPembuatanMutasi() class='btn btn-success btn-labeled'><b><i class='icon-floppy-disk'></i></b> Simpan</button>
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
              url: '<?php echo base_url('admin/purchase/dataProdukPerusahaan')?>',
              dataType: 'json',
              delay: 250,
              data: function(params) {
                //alert(params);
                var cmbPerusahaan = $('#cmbPerusahaan').val();
                var cmbGudangFrom = $('#cmbGudangFrom').val();
                return {
                  search: params.term,
                  cmbPerusahaan: cmbPerusahaan,
                  cmbGudangFrom: cmbGudangFrom
                }
              },
              processResults: function (data) {
              var results = [];
              $.each(data, function(index, item){
                results.push({
                    id: item.product_id,
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