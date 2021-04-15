<?php
if($getData->status == 0){
    $read = "";
    $disa = "";
}else{
    $read = "readonly";
    $disa = "disabled";
}
$id = $this->uri->segment(4);
echo"
<!-- Main content -->
<div class='content-wrapper'>

    <!-- Media library -->
    <form id='formAdd'>
    <div class='panel panel-white'>
        <div class='panel-heading'>
            <h6 class='panel-title text-semibold'>Form Edit Order Barang</h6>
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
                                <input type='text' class='form-control' id='noFakturPabrik' name='noFakturPabrik' value='".$getData->notransaction."' readonly>
                                <input type='hidden' class='form-control' id='idBeli' name='idBeli' value='".base64_decode($id)."' readonly>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Pabrik : </label>
                                <input type='text' class='form-control' id='namaPabrik' name='namaPabrik' value='".$getData->factory_name."' ".$read.">
                        </div>
                    </div>
                </div>  
                <div class='row'>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Perusahaan : </label>";
                                if($disa == "disabled"){
                                        echo"<select class='form-control' id='cmbPerusahaann' name='cmbPerusahaann' onchange=javascript:pilihPerusahaanOB() ".$disa.">
                                    <option value='0' disabled selected>Pilih Perusahaan</option>
                                    ";foreach($getPerusahaan->result() as $perusahaans){
                                    echo"
                                        <option value='".$perusahaans->id."' ";if($perusahaans->id == $getDataDetail->row()->perusahaan_id){echo"selected";}echo">".$perusahaans->name."</option>
                                    ";
                                    }echo"
                                </select>
                                <input type='hidden' id='cmbPerusahaan' name='cmbPerusahaan' value=".$getDataDetail->row()->perusahaan_id.">";
                                }else{
                                    echo"<select class='form-control' id='cmbPerusahaan' name='cmbPerusahaan' onchange=javascript:pilihPerusahaanOB()>
                                    <option value='0' disabled selected>Pilih Perusahaan</option>
                                    ";foreach($getPerusahaan->result() as $perusahaans){
                                    echo"
                                        <option value='".$perusahaans->id."' ";if($perusahaans->id == $getDataDetail->row()->perusahaan_id){echo"selected";}echo">".$perusahaans->name."</option>
                                    ";
                                    }echo"
                                </select>";
                                }
                                echo"
                                
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Gudang : </label>";
                                if($disa == "disabled"){
                                    echo"
                                <select class='form-control' id='cmbGudangg' name='cmbGudangg'>
                                ";
                                $Data = $this->model_master->getGudangbyPerusahaan($getDataDetail->row()->perusahaan_id);
                                foreach($Data->result() as $data){
                                    echo"
                                    <option value=".$data->id_gudang." ";if($data->id_gudang == $getDataDetail->row()->gudang_id){echo"selected";}echo">".$data->nama_gudang."</option>
                                    ";
                                }echo"
                            </select>
                            <input type='hidden' id='cmbGudang' name='cmbGudang' value=".$getDataDetail->row()->gudang_id.">
                            ";}else{
                                echo"
                                <select class='form-control' id='cmbGudang' name='cmbGudang'>
                                ";
                                $Data = $this->model_master->getGudangbyPerusahaan($getDataDetail->row()->perusahaan_id);
                                foreach($Data->result() as $data){
                                    echo"
                                    <option value=".$data->id_gudang." ";if($data->id_gudang == $getDataDetail->row()->gudang_id){echo"selected";}echo">".$data->nama_gudang."</option>
                                    ";
                                }echo"
                            </select>
                                ";
                             }echo"
                        </div>

                    </div>
                </div>  
                <div class='row'>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Tanggal Faktur : </label>
                                <input type='date' class='form-control' id='tglFaktur' name='tglFaktur' value='".date("Y-m-d",strtotime("+0 day", strtotime($getData->faktur_date)))."' ".$read.">
                        </div>
                    </div>
                    ";
                    if($_SESSION['rick_auto']['flag_user'] == 6 || $_SESSION['rick_auto']['flag_user'] == 1){
                        echo"
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Tanggal Sampai : </label>
                                <input type='date' class='form-control' id='tglSampaiGudang' name='tglSampaiGudang' value='".date("Y-m-d",strtotime("+0 day", strtotime($getData->warehouse_date)))."'>
                        </div>
                    </div>";}echo"
                </div>";
                if($getData->status == 0){
                if($_SESSION['rick_auto']['flag_user'] == 6 || $_SESSION['rick_auto']['flag_user'] == 1 || $_SESSION['rick_auto']['flag_user'] == 7){
                    echo"
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='form-group'>
                            <label>Catatan : </label>
                                <textarea id='txtNote' name='txtNote' class='form-control' style='width:100%'></textarea>
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-8'>
                        <div class='form-group' id='inpManual'>
                            <label>Scan Barcode : </label>
                                <input type='text' id='kode_produk' name='kode_produk' class='form-control input-xlg' placeholder='Input Kode Barcode' autofocus='' onchange=javascript:scan_barcode_order_edit(".base64_decode($id).",'barcode');>
                        </div>
                        <div class='form-group' style='display:none' id='inpBarcode'>
                            <label>Pilih Produk : </label>
                                <select id='cmbProduk' name='cmbProduk' class='form-control' onchange=javascript:scan_manual_barcode_order_edit(".base64_decode($id).",'manual')>
                                <option value='0' selected disabled>Pilih Produk</option>
                            </select>
                        </div>
                    </div>
                    <div class='col-md-4' style='margin-top:3%'>
                        <div class='form-group' id='tmpManual'>
                            <button type='button' class='btn btn-default' onclick=javascript:klikTombol('Manual')>Cari dengan Nama Produk</button>
                        </div>
                        <div class='form-group' style='display:none' id='tmpBarcode'>
                            <button type='button' class='btn btn-primary' onclick=javascript:klikTombol('Barcode')>Cari dengan Scan Barcode</button>
                        </div>
                    </div>
                </div>

                  ";}}echo"
            </div>
        </div>
        <div id='ajax_load'>
            <table class='table table-bordered'>
                <thead>
                    <tr class='bg-blue'>
                        <th>Produk</th>
                        <th>Qty Order</th>
                        <th>Qty Diterima</th>
                        <th>Satuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id='show_data'>
        ";
             if($getData->status == 0){
                $rd = "";
            }else{
                $rd = "readonly";
            }
        foreach($getDataDetail->result() as $detail){

            echo"
               <tr>
                    <td>".$detail->nama_produk."</td>
                    <td width='25%'>
                        <input type='text' id='addStok_kali_".$detail->id."' name='addStok_kali_".$detail->id."' value='".$detail->qty."' class='form-control' style='display: block;' ".$rd." onkeyup='javascript:ubahQtyOrderEdit(".$detail->id.")'>
                    </td>
                    <td width='25%'>
                        <input type='text' id='addStokEdit_".$detail->id."' name='addStokEdit_".$detail->id."' value='".$detail->qty_receive."' class='form-control' style='display: block;' onkeyup='javascript:ubahQtyOrderEdit(".$detail->id.")'>
                    </td>
                    <td>".$detail->nama_satuan."</td>
                    <td>";
            if($getData->status == 0){
                echo"
                    <a href='#' onclick='javascript:hapus_order_detail(".$detail->id.",".$detail->produk_beli_id.")' class='btn btn-danger btn-icon'><i class='icon-trash'></i></a>";}echo"
                    </td>
               </tr>";}echo"
                </tbody>
            </table>
        </div>
        <div id='tempatAjax'>

        </div>

        <input type='hidden' id='jmlProduk' name='jmlProduk' value='0'>

        <br><br>
        <!-- <div style='margin-bottom:10px;margin-left:10px'>
            <a href='#!' onclick=javascript:tambahProdukOrder() class='btn btn-primary btn-labeled'><b><i class='icon-plus-circle2'></i></b> Tambah Produk</a>
        </div> -->
        <div class='text-right'>
            ";
            if($_SESSION['rick_auto']['flag_user'] == 6 || $_SESSION['rick_auto']['flag_user'] == 1){
                if($getData->flag_proses == 1){

                }elseif($getData->status == 0){
                }else{
                echo"
            <button type='button' id='btnApprove' onclick=javascript:approveOrder() class='btn btn-primary btn-labeled'><b><i class='icon-checkmark'></i></b> Approve</button>
            ";}}
            if($getData->status == 0){
            if($getData->flag_proses == 1){
            }else{
                echo"
            <!-- <button type='button' onclick=javascript:simpanPembuatanOrderEdit(0) class='btn btn-success btn-labeled'><b><i class='icon-floppy-disk'></i></b> Simpan </button> -->
            <button type='button' onclick=javascript:simpanPembuatanOrderEdit(1) class='btn btn-primary btn-labeled'><b><i class='icon-floppy-disk'></i></b> Selesai & Simpan</button>";}}echo"
        </div>
    </div>

    <!-- /media library -->
    </form>
</div>
<!-- /main content -->
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
                    id: item.barcode,
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