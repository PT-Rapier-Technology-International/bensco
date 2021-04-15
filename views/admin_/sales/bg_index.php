<?php
echo"
<div class='content-wrapper'>
    <!-- Bordered striped table -->
    <div class='panel panel-flat'>
        <div class='panel-heading'>
            <h5 class='panel-title'>Data Komisi Sales</h5>
            <div class='heading-elements'>
                <ul class='icons-list'>
                    <li><a data-action='collapse'></a></li>
                    <li><a data-action='reload'></a></li>
                     
                </ul>
            </div>
        </div>

        <div class='table-responsive'>
            <table class='table datatable-basic'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Komisi</th>
                </thead>
                <tbody>
                ";
                $no = 0;
                foreach($getData->result() as $data){
                    $no++;
                    echo"
                    <tr>
                        <td>$no</td>
                        <td>".$data->id."</td>
                        <td>".$data->name."</td>
                        <td class='text-right'>
                        ";
                        $getFee = $this->model_sales->getSumFee($data->id);
                        if($getFee->num_rows() > 0){
                            $totalFee = $getFee->row()->total_fee;
                        }else{
                            $totalFee = 0;
                        }
                        echo"
                        <a href='#' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:view_detail_fee('".$data->id."')><i class='icon-cash'>
                         Rp. ".number_format($totalFee,2,',','.')."</i></a>
                        </td>
                    </tr>
                    ";}
                    echo"
                </tbody>
            </table>
        </div>
    </div>
    <!-- /bordered striped table -->
</div>";
?>