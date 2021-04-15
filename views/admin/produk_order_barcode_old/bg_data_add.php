<?php
echo"
            <table class='table table-bordered'>
                <thead>
                    <tr class='bg-blue'>
                        <th>Produk</th>
                        <th>Qty Order</th>
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
                        <div class='input-group bootstrap-touchspin'>
                        <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurangQtyOrder(".$detail->id.",'order')>-
                        </button>
                        </span>
                        <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
                        </span>
                        <input type='text' id='addStok_".$detail->id."' name='addStok_".$detail->id."' value='".$detail->qty."' class='touchspin-set-value form-control' style='display: block;' onkeyup=javascript:ketikQtyOrder(".$detail->id.",'order')>
                        <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
                        </span>
                        <span class='input-group-btn'>
                        <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambahQtyOrder(".$detail->id.",'order')>+
                        </button>
                        </span>
                        </div>
                    </td>
                    <td>".$detail->nama_satuan."</td>
                    <td>
                    <a href='#' onclick='javascript:hapus_order_detail(".$detail->id.")' class='btn btn-danger btn-icon'><i class='icon-trash'></i></a>
                    </td>
               </tr>";}echo"
                </tbody>
            </table>
";
?>