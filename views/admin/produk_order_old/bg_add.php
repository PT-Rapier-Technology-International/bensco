<?php
echo"
<!-- Main content -->
<div class='content-wrapper'>

	<!-- Media library -->
	<form id='formAdd'>
	<div class='panel panel-white'>
		<div class='panel-heading'>
			<h6 class='panel-title text-semibold'>Form Order Barang Baru</h6>
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
                            <label>No Faktur Pabrik : </label>
                                <input type='text' class='form-control' id='noFakturPabrik' name='noFakturPabrik'>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Pabrik : </label>
                                <input type='text' class='form-control' id='namaPabrik' name='namaPabrik'>
                        </div>
                    </div>
                </div>  
                <div class='row'>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Perusahaan : </label>
                                <select class='form-control' id='cmbPerusahaan' name='cmbPerusahaan' onchange=javascript:pilihPerusahaanOB()>
                                <option value='0' disabled selected>Pilih Perusahaan</option>
                                ";foreach($getPerusahaan->result() as $perusahaans){
                                echo"
                                    <option value='".$perusahaans->id."'>".$perusahaans->name."</option>
                                ";
                                }echo"
                            </select>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Gudang : </label>
                                <select class='form-control' id='cmbGudang' name='cmbGudang'>
                            </select>
                        </div>

                    </div>
                </div>  
                <div class='row'>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Tanggal Faktur : </label>
                                <input type='date' class='form-control' id='tglFaktur' name='tglFaktur'>
                        </div>
                    </div>";
                    if($_SESSION['rick_auto']['flag_user'] == 3){
                        echo"
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Tanggal Sampai oleh Gudang : </label>
                                <input type='date' class='form-control' id='tglSampaiGudang' name='tglSampaiGudang'>
                        </div>
                    </div>";}echo"
                </div>  
            </div>
		</div>
    	<div class='col-md-12'>
            <!-- colmd2 untuk yng lama -->
    		<div class='col-md-4' style='background-color:#c5c5c5'>
				<div class='form-group'>
					<label>Produk: </label>
                    <select id='cmbProduk_1' name='cmbProduk_1' class='form-control' onchange=javascript:pilihProdukOrder(1)>
                		<option value='0' selected disabled>Pilih Produk</option>
                    </select>
				</div>
    		</div>
    		<div class='col-md-4' style='background-color:#c5c5c5'>
				<div class='form-group'>
					<label>Qty Order: </label>
                    <div class='input-group bootstrap-touchspin'>
                    <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurangQtyOrder(1)>-
                    </button>
                    </span>
                    <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
                    </span>
                    <input type='text' id='addStok_1' name='addStok_1' value='1' class='touchspin-set-value form-control' style='display: block;'>
                    <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
                    </span>
                    <span class='input-group-btn'>
                    <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambahQtyOrder(1)>+
                    </button>
                    </span>
                    </div>
				</div>
    		</div>
            <div class='col-md-4' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Satuan: </label>
                    <input type='type' class='form-control' id='satuan_1' name='satuan_1' disabled>
                </div>
            </div>
    	</div>	
    	<div id='tempatAjax'>

        </div>

        <input type='hidden' id='jmlProduk' name='jmlProduk' value='1'>

        <br><br>
        <div style='margin-bottom:10px;margin-left:10px'>
        	<a href='#!' onclick=javascript:tambahProdukOrder() class='btn btn-primary btn-labeled'><b><i class='icon-plus-circle2'></i></b> Tambah Produk</a>
        </div>
        <div class='text-right'>
        	<button type='button' onclick=javascript:simpanPembuatanOrder() class='btn btn-success btn-labeled'><b><i class='icon-floppy-disk'></i></b> Simpan</button>
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