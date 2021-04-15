<?php
        $no=0;
        
        foreach($getProduk->result() as $produk){
            $no++;
            $getStok = $this->model_master->getStokPerusahaanGudangByProduks($produk->id,$id);
            if($getStok->num_rows() > 0){
                $stok = $getStok->row()->stokk;
            }else{
                $stok = 0;
            }
            echo"
        <tr>
            <td>$no</td>
            <td>".$produk->product_code."</td>
            <td>".$produk->product_name."</td>
            <td>".$stok."</td>
            <td>
            <input type='hidden' id='jmlStok_".$produk->id."' name='jmlStok_".$produk->id."' value=".$stok.">
            <div class='input-group bootstrap-touchspin'>
            <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurang(".$produk->id.")>-
            </button>
            </span>
            <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
            </span>
            <input type='text' id='addStok_".$produk->id."' name='addStok_".$produk->id."' value='0' class='touchspin-set-value form-control' style='display: block;'>
            <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
            </span>
            <span class='input-group-btn'>
            <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambah(".$produk->id.")>+
            </button>
            </span>
            </div>
            </td>
            <td>
                <input type='text' class='form-control' id='note_".$produk->id."' name='note_".$produk->id."'>
            </td>
        </tr>";}

 ?>