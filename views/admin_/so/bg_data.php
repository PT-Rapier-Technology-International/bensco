<?php
echo"
            <table class='table table-bordered'>
                <thead>
                    <tr class='bg-blue'>
                        <th>Produk</th>
                        <th>Stock Gudang</th>
                        <th>Satuan</th>
                        <th>Stock Opname</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id='show_data'>
        ";
        foreach($getDataDetail->result() as $data){
            echo"
               <tr>
                    <td>".$data->nama_produk."</td>
                    <td>".$data->qtyProduk."</td>
                    <td>".$data->nama_satuan."</td>
                    <input type='hidden' id='produkStok_".$data->id."' name='produkStok_".$data->id."' value='".$data->qtyProduk."' class='touchspin-set-value form-control' style='display: block;'>
                    <td width='25%'>
                        <div class='input-group bootstrap-touchspin'>
                        <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurangQtyOrder(".$data->id.",'so')>-
                        </button>
                        </span>
                        <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
                        </span>
                        <input type='text' id='addStok_".$data->id."' name='addStok_".$data->id."' value='".$data->qtySO."' class='touchspin-set-value form-control' style='display: block;' onkeyup=javascript:ketikQtyOrder(".$data->id.",'so')>
                        <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
                        </span>
                        <span class='input-group-btn'>
                        <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambahQtyOrder(".$data->id.",'so')>+
                        </button>
                        </span>
                        </div>
                    </td>
                    <td>
                    <a href='#' onclick='javascript:hapus_so_temp(".$data->id.")' class='btn btn-danger btn-icon'><i class='icon-trash'></i></a>
                    </td>
                    
               </tr>
        ";
        }echo"  
                </tbody>
            </table>
";
?>