<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
/*
* CONTROLLER INDEX WEBSITE
* This controler for screen index
*
* Log Activity : ~ Create your log if you change this controller ~
* 1. Create 15 Mei 2019 By Devanda Andrevianto, Create All Function, Create controller
*/
class Index extends CI_Controller {
	var $data = array('scjav'=>'assets/jController/admin/ctrlIndex.js');
    function __construct(){
        parent::__construct();
        $this->load->model("admin/model_index");
        $this->load->model('admin/model_master');
        $this->load->model('admin/model_produk');
        $this->load->model('admin/model_purchase');
        $this->load->model('admin/model_invoice');


        
    }
	
	// fungsi untuk mengecek apakah user sudah login
	 public function index(){

        if(empty($_SESSION['rick_auto'])){
            $url= base_url().'admin/index/signin';
            redirect($url);
			return;
		}else{	
			$url= base_url().'admin/index/dashboard';
			redirect($url);
			return;
		}

    }

    public function signin(){
		if(!$_POST){
			//echo"asasa";	
				if(isset($_SESSION['rick_auto']['id'])){
					//$insert = $this->db->set('token','')->where('id',$_SESSION['rick_auto']['id'])->update('users');
					redirect('admin/purchase/index');
				}else{
	      			$this->load->view("admin/login/bg_login",$this->data);
	      		}
        }else{
            $this->form_validation->set_rules('username', '', 'required');
            $this->form_validation->set_rules('password', '', 'required');
            
			$username   = $this->db->escape_str($this->input->post('username'));
			$password   = $this->db->escape_str(sha1($this->input->post('password')));
            $Qdata = $this->model_index->getUserLogin($username,$password)->row();
            if(!empty($Qdata)){
				$_SESSION['rick_auto']['id'] = $Qdata->id;
				$_SESSION['rick_auto']['username'] = $Qdata->username;
				$_SESSION['rick_auto']['fullname'] = $Qdata->fullname;
				$_SESSION['rick_auto']['flag_user'] = $Qdata->flag_user;
				
				//$_SESSION['rick_auto']['flag_action'] = $Qdata->flag_action;
				echo "1";
			}else{
				echo "0";
			}   
        }
	}

	public function dashboard(){

    $data_nota = $this->model_invoice->getFlagGiroCek();
    foreach($data_nota->result() as $nota){
        $update_status_invoice = $this->db->set('pay_status',1)->where('id',$nota->invoice_id)->where('pay_status',0)->update('invoice');
        $fee = $nota->total_nota * 0.5 / 100;
        $cekData = $this->model_invoice->getCekFeeSalesByInvoice($nota->invoice_id);
        if($cekData->num_rows() > 0){

        }else{
            // $duedate_fours = date("Y-m-d",strtotime("+30 day", strtotime($nota->duedate)));
            // $duedate_four = date("Y-m-d",strtotime("+0 day", strtotime($nota->duedate)));
            // if(date('Y-m-d') >= $duedate_four){
            //     //echo "Kadaluwarsa";
            // }else{
                $insert_sales_fee = $this->db->set('invoice_id',$nota->invoice_id)->set('sales_id',$nota->id_sales)->set('fee',$fee)->insert('transaction_sales_fee');
            //}
        } 

        $update_status_payment_invoice = $this->db->set('flag',1)->where('no_tanda_terima',$nota->no_tanda_terima)->update('invoice_payment');
        $insert_role = $this->db->set('no_transaction',$nota->no_tanda_terima)
                    ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                    ->set('user',$_SESSION['rick_auto']['fullname'])
                    ->set('action','Pembayaran Invoice - '.$_SESSION['rick_auto']['fullname'])
                    ->set('create_date',date("Y-m-d H:i:s"))
                    ->insert('role_transaksi');
    	}

    
    

	 	if(empty($_SESSION['rick_auto']) || empty($_SESSION['rick_auto']['id'])){
        redirect('admin/index/signin/');
        return;
    	}
    	$this->data['getPurchase'] = $this->model_purchase->getPurchase();
    	if($_SESSION['rick_auto']['flag_user'] == 1){
    		//redirect('admin/purchase/index_admin');
    		redirect('admin/purchase/index');
    	}else{
			//$this->template->rick_auto('index',$this->data);
			redirect('admin/purchase/index');
		}
	}

	public function view_profile(){
		$this->data['data'] = $this->model_index->getUserBySession()->row();
		$this->template->rick_auto('profile/bg_view_profil',$this->data);
	}

	public function edit_profile(){
		$this->data['data'] = $this->model_index->getUserBySession()->row();
		$this->template->rick_auto('profile/bg_edit_profil',$this->data);
	}

	public function logout(){
		$insert = $this->db->set('token','')->where('id',$_SESSION['rick_auto']['id'])->update('users');
		if($insert){
		unset($_SESSION['rick_auto']);
		unset($_SESSION['login_error']);
        // session_destroy();

		redirect('admin/index/signin');
		}
	}

	public function simpan_profil(){
		$txtPilihPassword = $this->input->post('txtPilihPassword');
		$username = $this->input->post('username');
		$email = $this->input->post('email');
		$fullname = $this->input->post('fullname');
		$password = $this->input->post('password');
		$new_password = $this->input->post('new_password');

		if($txtPilihPassword == 0){
			$update = $this->db->set('username',$username)->set('fullname',$fullname)->set('email',$email)->where('id',$_SESSION['rick_auto']['id'])->update('users');
		}else{
			$cekPassword = $this->model_index->getCekPasswordBySession(sha1($password));
			if($cekPassword->num_rows() > 0){
				$update = $this->db->set('username',$username)->set('fullname',$fullname)->set('email',$email)->set('password',sha1($new_password))->where('id',$_SESSION['rick_auto']['id'])->update('users');
			}else{
				$update = "";
				echo "2";
			}
			
		}
		if($update){
			echo "1";
		}
	}
}
?>