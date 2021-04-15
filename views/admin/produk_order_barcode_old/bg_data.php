<?php
             if($getData->status == 0){
                $rd = "";
            }else{
                $rd = "readonly";
            }
echo"
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
        foreach($getDataDetail->result() as $detail){
            echo"
               <tr>
                    <td>".$detail->nama_produk."</td>
                    <td width='25%'>
                        <input type='text' id='addStok_kali_".$detail->id."' name='addStok_kali_".$detail->id."' value='".$detail->qty."' class='form-control' style='display: block;'  ".$rd."onkeyup='javascript:ubahQtyOrderEdit(".$detail->id.")'>
                    </td>
                    <td width='25%'>
                        <input type='text' id='addStokEdit_".$detail->id."' name='addStokEdit_".$detail->id."' value='".$detail->qty_receive."' class='form-control' style='display: block;'>
                    </td>
                    <td>".$detail->nama_satuan."</td>
                    <td>
                    <a href='#' onclick='javascript:hapus_order_detail(".$detail->id.",".$detail->produk_beli_id.")' class='btn btn-danger btn-icon'><i class='icon-trash'></i></a>
                    </td>
               </tr>";}echo"
                </tbody>
            </table>
";
?>