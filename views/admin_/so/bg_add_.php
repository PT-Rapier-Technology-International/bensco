<?php
//$this->db->query("truncate table stock_opname_detail_temp");
echo"
<!-- Main content -->
<div class='content-wrapper'>

	<!-- Media library -->
	<form id='formAdd'>
	<div class='panel panel-white'>
		<div class='panel-heading'>
			<h6 class='panel-title text-semibold'>Form Tambah Stock Opname</h6>
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
                            <label>No Transaksi : </label>
                                <input type='text' class='form-control' id='noTransaksi' name='noTransaksi'>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Tanggal Transaksi : </label>
                                <input type='date' class='form-control' id='tglTransaksi' name='tglTransaksi'>
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
                    ";
                    if($_SESSION['rick_auto']['flag_user'] == 3){
                        echo"
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Tanggal Sampai oleh Gudang : </label>
                                <input type='date' class='form-control' id='tglSampaiGudang' name='tglSampaiGudang'>
                        </div>
                    </div>";}echo"
                </div>  
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='form-group'>
                            <label>Scan Barcode : </label>
                                <input type='text' id='kode_produk' name='kode_produk' class='form-control input-xlg' placeholder='Input Kode Barcode' autofocus='' onchange='javascript:scan_barcode_so();'>
                        </div>
                    </div>
                </div>
            </div>
		</div>
        <div id='ajax_load'>
            <table class='table table-bordered'>
                <thead>
                    <tr class='bg-blue'>
                        <th>Produk</th>
                        <th>Stock Gudang</th>
                        <th>Stock Opname</th>
                    </tr>
                </thead>
                <tbody id='show_data'>
        ";
        foreach($getDataDetail->result() as $data){
            echo"
    	       <tr>
                    <td>".$data->nama_produk."</td>
                    <td>".$data->qtyProduk."</td>
                    <input type='hidden' id='produkStok_".$data->id."' name='produkStok_".$data->id."' value='".$data->qtyProduk."' class='touchspin-set-value form-control' style='display: block;'>
                    <td width='25%'>
                        <div class='input-group bootstrap-touchspin'>
                        <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurangQtyOrder(".$data->id.")>-
                        </button>
                        </span>
                        <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
                        </span>
                        <input type='text' id='addStok_".$data->id."' name='addStok_".$data->id."' value='".$data->qtySO."' class='touchspin-set-value form-control' style='display: block;'>
                        <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
                        </span>
                        <span class='input-group-btn'>
                        <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambahQtyOrder(".$data->id.")>+
                        </button>
                        </span>
                        </div>
                    </td>
                    
               </tr>
        ";
        }echo"	
                </tbody>
            </table>
        </div>
    	<div id='tempatAjax'>

        </div>

        <input type='hidden' id='jmlProduk' name='jmlProduk' value='0'>

        <br><br>
        <!-- <div style='margin-bottom:10px;margin-left:10px'>
        	<a href='#!' onclick=javascript:tambahProdukOrder() class='btn btn-primary btn-labeled'><b><i class='icon-plus-circle2'></i></b> Tambah Produk</a>
        </div>-->
        <div class='text-right'>
        	<button type='button' id='btnSimpanSO' onclick=javascript:simpanPembuatanSO() class='btn btn-success btn-labeled'><b><i class='icon-floppy-disk'></i></b> Simpan</button>
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