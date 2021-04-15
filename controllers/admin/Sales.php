<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
/*
* CONTROLLER INDEX WEBSITE
* This controler for screen index
*
* Log Activity : ~ Create your log if you change this controller ~
* 1. Create 20 Mei 2019 By Devanda Andrevianto, Create All Function, Create controller
*/
class Sales extends CI_Controller {
	var $data = array('scjav'=>'assets/jController/admin/CtrlSales.js');
    function __construct(){
        parent::__construct();
        $this->load->model('admin/model_master');
        $this->load->model('admin/model_produk');
        $this->load->model('admin/model_purchase');
        $this->load->model('admin/model_sales');
        // $this->lang->load('admin', '');
        if(empty($_SESSION['rick_auto']) || empty($_SESSION['rick_auto']['id'])){
            redirect('admin/index/signin/');
            return;
        }
    }
	
	// fungsi untuk mengecek apakah user sudah login
	public function fee(){
        $this->data['getData'] = $this->model_sales->getData();
        $this->template->rick_auto('sales/bg_index',$this->data);

    }

    public function view_detail_fee(){
        $id = $this->input->post('id');
        $getData = $this->model_sales->getDetailFeeBySales($id);
        echo"
            <table class='table table-bordered table-striped'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No Nota</th>
                        <th>Total Nota (Rp.)</th>
                        <th>Fee (Rp.)</th>
                    </tr>
                </thead>
                <tbody id='tableAjax'>
                    ";
                    $no=0;
                    
                    foreach($getData->result() as $data){
                        $no++;
                        echo"
                    <tr>
                        <td>$no</td>
                        <td>".$data->nonota."</td>
                        <td class='text-right'>".number_format($data->total,2,',','.')."</td>
                        <td class='text-right'>".number_format($data->fee,2,',','.')."</td>
                    </tr>";}echo"
                </tbody>
            </table>
            <br>
        ";
    }
}

?>