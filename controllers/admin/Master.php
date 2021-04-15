<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
/*
* CONTROLLER INDEX WEBSITE
* This controler for screen index
*
* Log Activity : ~ Create your log if you change this controller ~
* 1. Create 13 Juni 2019 By Devanda Andrevianto, Create All Function, Create controller
*/
class Master extends CI_Controller {
	var $data = array('scjav'=>'assets/jController/admin/CtrlMaster.js');
    function __construct(){
        parent::__construct();
        $this->load->model('admin/model_master');
        $this->load->model('admin/model_produk');
        $this->load->model('admin/model_purchase');
        $this->load->model('admin/model_city');
        $this->load->model('admin/model_provinsi');
        $this->load->model('admin/model_negara');
        // $this->lang->load('admin', '');
        if(empty($_SESSION['rick_auto']) || empty($_SESSION['rick_auto']['id'])){
            redirect('admin/index/signin/');
            return;
        }
    }

    public function atur_perusahaan_gudang(){
        $dataPerusahaan = $this->model_master->getPerusahaan();
        $dataGudang = $this->model_master->getGudang();
        foreach($dataPerusahaan->result() as $perusahaan){
            foreach($dataGudang->result() as $gudang){
                $insert = $this->db->set('perusahaan_id',$perusahaan->id)->set('gudang_id',$gudang->id)->set('active',1)->insert('perusahaan_gudang');
            }
        }
    }


    function imageCreateFromAny( $filepath ) {
       
      $size=getimagesize($filepath);
        switch($size["mime"]){
            case "image/gif":
            $im = imageCreateFromGif($filepath); 
            break; 
            case "image/jpeg":
                $im = imageCreateFromJpeg($filepath); 
            break; 
            case "image/png":
                $im = imageCreateFromPng($filepath); 
            break; 
            case "image/bmp":
                $im = imageCreateFromBmp($filepath); 
            break; 
        }    
        return $im;  
    } 

    public function city(){
        $this->template->rick_auto('master/city/bg_index',$this->data);
    }

    public function provinsi(){
        $this->template->rick_auto('master/provinsi/bg_index',$this->data);
    }

    public function negara(){
        $this->template->rick_auto('master/negara/bg_index',$this->data);
    }

    public function getDataCity(){
        $list = $this->model_city->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->name;
            $row[] = $field->abbreviation;
            $row[] = "<a href='".base_url("admin/master/editCity/".base64_encode($field->id)."")."' class='btn btn-warning btn-icon'><i class='icon-pencil7'></i></a>";
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->model_city->count_all(),
            "recordsFiltered" => $this->model_city->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output); 
    }

    public function getDataProvinsi(){
        $list = $this->model_provinsi->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->name;
            $row[] = "<a href='".base_url("admin/master/editProvinsi/".base64_encode($field->id)."")."' class='btn btn-warning btn-icon'><i class='icon-pencil7'></i></a>";
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->model_city->count_all(),
            "recordsFiltered" => $this->model_city->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output); 
    }

    public function getDataNegara(){
        $list = $this->model_negara->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->name;
            $row[] = "<a href='".base_url("admin/master/editNegara/".base64_encode($field->id)."")."' class='btn btn-warning btn-icon'><i class='icon-pencil7'></i></a>";
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->model_negara->count_all(),
            "recordsFiltered" => $this->model_negara->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output); 
    }

    public function editCity(){
        $id = base64_decode($this->uri->segment(4));
        $this->data['getData'] = $this->model_master->getCitybyId($id)->row();
        $this->data['getProv'] = $this->model_master->getProv();
        $this->template->rick_auto('master/city/bg_edit',$this->data);
    }

    public function addCity(){
        
        $this->data['getCountry'] = $this->model_master->getCountry();
        $this->data['getProv'] = $this->model_master->getProv();
        $this->template->rick_auto('master/city/bg_add',$this->data);
    }

    public function addProvinsi(){
        $this->data['getCountry'] = $this->model_master->getCountry();
        $this->template->rick_auto('master/provinsi/bg_add',$this->data);  
    }

    public function addNegara(){
        $this->template->rick_auto('master/negara/bg_add',$this->data);  
    }

    public function editProvinsi(){
        $id = base64_decode($this->uri->segment(4));
        $this->data['getCountry'] = $this->model_master->getCountry();
        $this->data['getData'] = $this->model_master->getProvbyId($id)->row();
        $this->template->rick_auto('master/provinsi/bg_edit',$this->data);  
    }

    public function editNegara(){
        $id = base64_decode($this->uri->segment(4));
        $this->data['getData'] = $this->model_master->getCountryById($id)->row();
        $this->template->rick_auto('master/negara/bg_edit',$this->data);  
    }
	
	// fungsi untuk mengecek apakah user sudah login
	 public function user(){
        $jenis = base64_decode($this->uri->segment(5));
        $subJenis = base64_decode($this->uri->segment(6));
        $subJeniss = base64_decode($this->uri->segment(7));
        if($subJenis == ""){
            if($jenis == "User"){
                $this->data['getUsers'] = $this->model_master->getUsers();
            }else{
            $this->data['getUsers'] = $this->model_master->getMembers();
            }
            $this->template->rick_auto('master/user/bg_index',$this->data);
        }else{
            $this->data['getUsers'] = $this->model_master->getSaless();
            $this->template->rick_auto('master/user/bg_index_sales',$this->data);
            
        }
    }

    public function user_add(){
        //$this->data = "";
        $this->data['getCity'] = $this->model_master->getCity();
        $this->data['getProv'] = $this->model_master->getProv();
        $this->data['getHarga'] = $this->model_master->getHarga();
        $this->template->rick_auto('master/user/bg_add',$this->data);
    }

    public function changeProv(){
        $cmbProv = $this->input->post('cmbProv');

        $getCity = $this->model_master->getCitybyProvId($cmbProv);

        foreach($getCity->result() as $city){
         echo"
            <option value='".$city->id."'>".$city->name."</option> ";
        }
    }

    public function changeNegara(){
        $cmbCountry = $this->input->post('cmbCountry');

        $getCountry = $this->model_master->getProvByCountry($cmbCountry);

        foreach($getCountry->result() as $country){
         echo"
            <option value='".$country->id."'>".$country->name."</option> ";
        }
    }

    public function user_edit(){
        $jenis = $this->uri->segment(4);
        $id = base64_decode($this->uri->segment(6));
        if($jenis == 1){
            $this->data['getData'] = $this->model_master->getMemberByID($id)->row(); 
        }elseif($jenis == 2){
            $this->data['getData'] = $this->model_master->getSalesById($id)->row(); 
        }else{
            $this->data['getData'] = $this->model_master->getUsersById($id)->row();
        }

        //$this->data = "";
        $this->data['jenis'] = $jenis;
        $this->data['getCity'] = $this->model_master->getCity();
        $this->data['getHarga'] = $this->model_master->getHarga();
        $this->template->rick_auto('master/user/bg_edit',$this->data);
    }

    public function kategori(){
        $this->data['getKategori'] = $this->model_master->getKategori();
        $this->template->rick_auto('master/kategori/bg_index',$this->data);  
    }

    public function satuan(){
        $this->data['getSatuan'] = $this->model_master->getSatuan();
        $this->template->rick_auto('master/satuan/bg_index',$this->data);  
    }

    public function save_satuan(){
        $nama = $this->input->post('nama');
        $jenis = $this->input->post('jenis');

        $insert = $this->db->set('name',$nama)->set('flag_jenis',$jenis)->insert('satuan');

        if($insert){
            echo "1";
        }
    }

    public function save_expedisi(){
        $nama = $this->input->post('nama');
        $telp_no = $this->input->post('telp_no');
        $alamat = $this->input->post('alamat');
        $insert = $this->db->set('name',$nama)->set('telp_no',$telp_no)->set('address',$alamat)->insert('expedisi');

        if($insert){
            echo "1";
        }
    }

    public function save_edit_expedisi(){
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
        $telp_no = $this->input->post('telp_no');
        $alamat = $this->input->post('alamat');
        $update = $this->db->set('name',$nama)->set('telp_no',$telp_no)->set('address',$alamat)->where('id',$id)->update('expedisi');

        if($update){
            echo "1";
        }
    }

    public function save_via_expedisi(){
        $nama = $this->input->post('nama');
        $telp_no = $this->input->post('telp_no');
        $alamat = $this->input->post('alamat');
        $insert = $this->db->set('name',$nama)->set('telp_no',$telp_no)->set('address',$alamat)->insert('expedisi_via');

        if($insert){
            echo "1";
        }
    }

    public function save_edit_via_expedisi(){
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
        $telp_no = $this->input->post('telp_no');
        $alamat = $this->input->post('alamat');
        $update = $this->db->set('name',$nama)->set('telp_no',$telp_no)->set('address',$alamat)->where('id',$id)->update('expedisi_via');

        if($update){
            echo "1";
        }
    }

    public function save_gudang(){
        $nama = $this->input->post('nama');

        $insert = $this->db->set('name',$nama)->insert('gudang');
        $id_gudang = $this->db->insert_id();
        if($insert){
            
            $perusahaan = $this->model_master->getPerusahaan();

            foreach($perusahaan->result() as $peru){
                $insert_perusahaan_gudang = $this->db->set('perusahaan_id',$peru->id)->set('gudang_id',$id_gudang)->set('active',0)->set('stok',0)->insert('perusahaan_gudang');
            }
            echo "1";
        }
    }

    public function save_edit_gudang(){
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');

        $update = $this->db->set('name',$nama)->where('id',$id)->update('gudang');

        if($update){
            echo "1";
        }
    }

    public function save_kategori(){
        $path = 'web/images/category/';
        $kode = $this->input->post('kode');
        $nama = $this->input->post('nama');
        $gambar_default_1 = $this->input->post('gambar_default_1');
        if($gambar_default_1 == 0){
            $gambar = $this->template->upload_picture_not_resize($path,$_POST['image_high_1'],$_POST['image_tumb_1']);
            $link_gambar = "web/images/category/".$gambar;
        }else{
            $gambar = "";
            $link_gambar = "";
        }
        $insert = $this->db->set('cat_code',$kode)->set('cat_name',$nama)->set('cat_image',$link_gambar)->set('cat_status',1)->insert('category_product');

        if($insert){
            echo "1";
        }
    }

    public function save_edit_kategori(){
        $path = 'web/images/category/';
        $id = $this->input->post('id');
        $kode = $this->input->post('kode');
        $nama = $this->input->post('nama');
        $gambar_default_1 = $this->input->post('gambar_default_1');
        if($gambar_default_1 == 0){
            $gambar = $this->template->upload_picture_not_resize($path,$_POST['image_high_1'],$_POST['image_tumb_1']);
            $link_gambar = "web/images/category/".$gambar;
        }else{
            $gambar = "";
            $link_gambar = "";
        }
        $update = $this->db->set('cat_code',$kode)->set('cat_name',$nama)->set('cat_image',$link_gambar)->set('cat_status',1)->where('id',$id)->update('category_product');

        if($update){
            echo "1";
        }       

    }

    public function kategori_add(){
        //$this->data = "";
        $this->template->rick_auto('master/kategori/bg_add',$this->data);     
    }

    public function kategori_edit(){
        //$this->data = "";
        $id = base64_decode($this->uri->segment(4));
        $this->data['getData'] = $this->model_master->getKategoriById($id)->row();
        $this->template->rick_auto('master/kategori/bg_edit',$this->data);     
    }

    public function satuan_add(){
        ////$this->data = "";
        $this->template->rick_auto('master/satuan/bg_add',$this->data);     
    }

    public function satuan_edit(){
        $id = base64_decode($this->uri->segment(4));
        $this->data['getData'] = $this->model_master->getSatuanById($id)->row();
        ////$this->data = "";
        $this->template->rick_auto('master/satuan/bg_edit',$this->data);     
    }

    public function perusahaan(){
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->template->rick_auto('master/perusahaan/bg_index',$this->data);  
    }

    public function perusahaan_add(){
        ////$this->data = "";
        $this->template->rick_auto('master/perusahaan/bg_add',$this->data);     
    }

    public function perusahaan_edit(){
        $id = base64_decode($this->uri->segment(4));
        $this->data['getData'] = $this->model_master->getPerusahaanByID($id)->row();
        ////$this->data = "";
        $this->template->rick_auto('master/perusahaan/bg_edit',$this->data);     
    }

    public function save_perusahaan(){
        $nama = $this->input->post('nama');

        $insert = $this->db->set('name',$nama)->insert('perusahaan');

        if($insert){
            echo "1";
        }
    }

    public function save_edit_perusahaan(){
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');

        $insert = $this->db->set('name',$nama)->where('id',$id)->update('perusahaan');

        if($insert){
            echo "1";
        }
    }

    public function save_edit_city(){
        $id = $this->input->post('id');
        $cmbProv = $this->input->post('cmbProv');
        $nama = $this->input->post('nama');
        $abbreviation = $this->input->post('singkatan');
        $kode_area = $this->input->post('kode_area');

        $insert = $this->db->set('abbreviation',$abbreviation)->set('name',$nama)->set('provinsi_id',$cmbProv)->set('area_code',$kode_area)->where('id',$id)->update('city');

        if($insert){
            echo "1";
        }
    }

    public function save_city(){
        $cmbProv = $this->input->post('cmbProv');
        $nama = $this->input->post('nama');
        $abbreviation = $this->input->post('singkatan');
        $kode_area = $this->input->post('kode_area');

        $insert = $this->db->set('abbreviation',$abbreviation)->set('name',$nama)->set('provinsi_id',$cmbProv)->set('area_code',$kode_area)->insert('city');

        if($insert){
            echo "1";
        }
    }

    public function save_provinsi(){
        $cmbCountry = $this->input->post('cmbCountry');
        $nama = $this->input->post('nama');

        $insert = $this->db->set('name',$nama)->set('country_id',$cmbCountry)->insert('master_provinsi');

        if($insert){
            echo "1";
        }
    }

    public function save_edit_provinsi(){
        $id = $this->input->post('id');
        $cmbCountry = $this->input->post('cmbCountry');
        $nama = $this->input->post('nama');

        $insert = $this->db->set('name',$nama)->set('country_id',$cmbCountry)->where('id',$id)->update('master_provinsi');

        if($insert){
            echo "1";
        }
    }

    public function save_negara(){
        $nama = $this->input->post('nama');

        $insert = $this->db->set('name',$nama)->insert('country');

        if($insert){
            echo "1";
        }
    }

    public function save_edit_negara(){
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');

        $insert = $this->db->set('name',$nama)->where('id',$id)->update('country');

        if($insert){
            echo "1";
        }
    }


    public function save_edit_satuan(){
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
        $jenis = $this->input->post('jenis');

        $insert = $this->db->set('name',$nama)->set('flag_jenis',$jenis)->where('id',$id)->update('satuan');

        if($insert){
            echo "1";
        }
    }

    

    public function harga(){
        $this->data['getHarga'] = $this->model_master->getHarga();
        $this->template->rick_auto('master/harga/bg_index',$this->data);  
    }

    public function harga_add(){
        $this->template->rick_auto('master/harga/bg_add',$this->data);  
    }

    public function harga_edit(){
        $id = base64_decode($this->uri->segment(4));
        $this->data['getData'] = $this->model_master->getHargaById($id)->row();
        $this->template->rick_auto('master/harga/bg_edit',$this->data);  
    }

    public function save_harga(){
        $nama = $this->input->post('nama');
        $nilai = "".$nama." / 100";
        $pr = $this->db->escape_str('$product_price');
        $rumus = "".$pr." + ".$pr." * ".$nilai."";
        $insert = $this->db->set('name',$nama)->set('operation',$rumus )->insert('type_price');

        if($insert){
            echo "1";
        }
    }

    public function save_edit_harga(){
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
        $nilai = "".$nama." / 100";
        $pr = $this->db->escape_str('$product_price');
        $rumus = "".$pr." + ".$pr." * ".$nilai."";
        $update = $this->db->set('name',$nama)->set('operation',$rumus )->where('id',$id)->update('type_price');

        if($update){
            echo "1";
        }
    }

    public function expedisi(){
        $this->data['getExpedisi'] = $this->model_master->getExpedisi();
        $this->template->rick_auto('master/expedisi/bg_index',$this->data);  
    }

    public function via_expedisi(){
        $this->data['getExpedisi'] = $this->model_master->getViaExpedisi();
        $this->template->rick_auto('master/via_expedisi/bg_index',$this->data);  
    }

    public function expedisi_add(){
        ////$this->data = "";
        $this->template->rick_auto('master/expedisi/bg_add',$this->data);     
    }

    public function via_expedisi_add(){
        ////$this->data = "";
        $this->template->rick_auto('master/via_expedisi/bg_add',$this->data);     
    }

    public function expedisi_edit(){
        $id = base64_decode($this->uri->segment(4));
        $this->data['getData'] = $this->model_master->getExpedisiById($id)->row();
        ////$this->data = "";
        $this->template->rick_auto('master/expedisi/bg_edit',$this->data);     
    }

    public function via_expedisi_edit(){
        $id = base64_decode($this->uri->segment(4));
        $this->data['getData'] = $this->model_master->getViaExpedisiById($id)->row();
        ////$this->data = "";
        $this->template->rick_auto('master/via_expedisi/bg_edit',$this->data);     
    }


    public function gudang(){
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->template->rick_auto('master/gudang/bg_index',$this->data);  
    }

    public function gudang_add(){
        ////$this->data = "";
        $this->template->rick_auto('master/gudang/bg_add',$this->data);     
    }   

    public function gudang_edit(){
        $id = base64_decode($this->uri->segment(4));
        $this->data['getData'] = $this->model_master->getGudangById($id)->row();
        ////$this->data = "";
        $this->template->rick_auto('master/gudang/bg_edit',$this->data);     
    }   

    public function simpanUser(){
        $kode     = $this->input->post('kode');
        $fullname = $this->input->post('fullname');
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $password = sha1($this->input->post('password'));
        $cmbUser = $this->input->post('cmbUser');
        $ktp = $this->input->post('ktp');
        $alamat = $this->input->post('alamat');
        $alamat_toko = $this->input->post('alamat_toko');
        $cmbAdminLv = $this->input->post('cmbAdminLv');
        if($cmbUser == 1){
        $cekData = $this->model_master->getUsersByUserName($username);
        if($cekData->num_rows() > 0){
        }else{
         $insert = $this->db->set('fullname',$fullname)
                            ->set('username',$username)
                            ->set('email',$email)
                            ->set('password',$password)
                            ->set('address',$alamat)
                            ->set('ktp',$ktp)
                            ->set('flag_user',$cmbAdminLv)
                            ->set('create_date',date("Y-m-d H:i:s"))
                            ->set('create_user',$_SESSION['rick_auto']['username'])
                            ->insert('users');
            if($insert){
                echo "1";
            }
        }
        }else{
         $cekData = $this->model_master->getSalesById($kode);
         if($cekData->num_rows() > 0){
            echo "3";
         }elseif($kode == ""){
            echo "2";
         }else{
         $insert = $this->db->set('id',$kode)
                            ->set('name',$fullname)
                            ->set('username',$username)
                            ->set('email',$email)
                            ->set('password',$password)
                            ->set('address',$alamat)
                            ->set('ktp',$ktp)
                            ->insert('sales');
         $idSales = $this->db->insert_id();
         if($insert){
            $getMember = $this->model_master->getAllMembers();
            foreach($getMember->result() as $member){
                $insertMemberSales = $this->db->set('member_id',$member->id)->set('sales_id',$kode)->set('active',1)->insert('member_sales');
            }
         }
        }
        if($insert){
            echo "1";
        }
    }
    }

    public function simpan_edit_User(){
        $id       = $this->input->post('id');
        $kode     = $this->input->post('kode');
        $fullname = $this->input->post('fullname');
        $username = $this->input->post('username');
        $email = $this->input->post('email');
       // $password = sha1($this->input->post('password'));
        $jnuser = $this->input->post('jnuser');
         $cmbUser = $this->input->post('cmbUser');
        if($jnuser == "user"){
            if($this->input->post('password') == ""){
                $passwords = $this->input->post('password_old');
            }else{
                $passwords = sha1($this->input->post('password'));
            }
         $update = $this->db->set('fullname',$fullname)
                            ->set('username',$username)
                            ->set('email',$email)
                            ->set('password',$passwords)
                            ->set('flag_user',$cmbUser)
                            ->set('create_date',date("Y-m-d H:i:s"))
                            ->set('create_user',$_SESSION['rick_auto']['username'])
                            ->where('id',$id)
                            ->update('users');
        }else{
        $getData = $this->model_master->getSalesById($id)->row();
        $passwordsales = $this->input->post('password');
        if($passwordsales == ""){
            $passwords = $getData->password;
        }else{
            $passwords = sha1($passwordsales);
        }
         $ktp = $this->input->post('ktp');
         $phone = $this->input->post('phone');
         $alamat = $this->input->post('alamat');   
         $update = $this->db->set('name',$fullname)
                            ->set('username',$username)
                            ->set('email',$email)
                            ->set('password',$passwords)
                            ->set('ktp',$ktp)
                            ->set('phone',$phone)
                            ->set('address',$alamat)
                            ->where('id',$kode)
                            ->update('sales');
        }
        if($update){
            echo "1";
        }
    }

    public function simpanMember_(){
        $username = $this->input->post('username');
        $kode     = $this->input->post('kode');
        $fullname = $this->input->post('fullname');
        $email = $this->input->post('email');
        $password = sha1($this->input->post('password'));
        $cmbHarga = $this->input->post('cmbHarga');
        $cmbCity  = $this->input->post('cmbCity');
        $ktp = $this->input->post('ktp');
        $alamat = $this->input->post('alamat');
        $alamat_toko = $this->input->post('alamat_toko');
        $cekData = $this->model_master->getAllMembersDesc();
        $namacutProv = $cmbCity;
        $namapt = substr($namacutProv,0,4);
        $namaProv = substr($namacutProv,0);
        $arr = explode(' ', $namaProv);
        $singkatan = "";
        foreach($arr as $kata)
        {
        $singkatan .= substr($kata, 0, 4);
        }

        if($cekData->num_rows() > 0){
            $uniq = sprintf("%'.05d", $cekData->num_rows() + 1);
            $kodee = $singkatan."".$uniq;
        }else{
            $uniq = sprintf("%'.05d", 1);
            $kodee = $singkatan."".$uniq;
        }

         $insert = $this->db->set('id',$kodee)
                            ->set('name',$fullname)
                            ->set('username',$username)
                            ->set('email',$email)
                            ->set('password',$password)
                            ->set('address',$alamat)
                            ->set('address_toko',$alamat_toko)
                            ->set('ktp',$ktp)
                            ->set('operation_price',$cmbHarga)
                            ->set('city',$cmbCity)
                            ->set('create_date',date("Y-m-d H:i:s"))
                            ->set('create_user',$_SESSION['rick_auto']['username'])
                            ->insert('member');
        if($insert){
            $getSales = $this->model_master->getSales();
            foreach($getSales->result() as $sales){
                $insertMemberSales = $this->db->set('member_id',$kodee)->set('sales_id',$sales->id)->set('active',0)->insert('member_sales');
            }
            echo "1";
        }
    }

    public function simpanMember(){
        $username = $this->input->post('username');
        $kode     = $this->db->escape_str($this->input->post('kode'));
        $fullname = $this->input->post('fullname');
        $email = $this->input->post('email');
        $password = sha1($this->input->post('password'));
        $cmbHarga = $this->input->post('cmbHarga');
        $cmbCity  = $this->input->post('cmbCity');
        $phone    = $this->input->post('phone');
        $cmbProv  = $this->input->post('cmbProv');
        $ktp = $this->input->post('ktp');
        $alamat = $this->input->post('alamat');
        $alamat_toko = $this->input->post('alamat_toko');
        $dataKota = $this->model_master->getCitybyName($cmbCity);
        $cekData = $this->model_master->getMemberBySingkatan($dataKota->row()->abbreviation);
        $cekDataa = $this->model_master->getMemberBySingkatanDesc($dataKota->row()->abbreviation);
        $namacutProv = $cmbCity;
        $namapt = substr($namacutProv,0,4);
        $namaProv = substr($namacutProv,0);
        $singkatan = $dataKota->row()->abbreviation;

        if($cekData->num_rows() > 0){
            
            $uniq = sprintf("%'.04d", $cekDataa->row()->uniq_code + 1);
            //echo $cekDataa->row()->uniq_code;
            $kodee = $singkatan."".$uniq;
        }else{
            //echo "keneeee";
            $uniq = sprintf("%'.04d", 1);
            $kodee = $singkatan."".$uniq;
        }

        $getCity = $this->model_master->getCitybyId($cmbCity)->row();
        $getProv = $this->model_master->getProvbyId($getCity->provinsi_id)->row();


         $insert = $this->db->set('uniq_code',$kode)
                            ->set('name',$fullname)
                            ->set('username',$username)
                            ->set('email',$email)
                            ->set('password',$password)
                            ->set('address',$alamat)
                            ->set('address_toko',$alamat_toko)
                            ->set('ktp',$ktp)
                            ->set('operation_price',$cmbHarga)
                            ->set('city_id',$cmbCity)
                            ->set('city',$getCity->name)
                            ->set('prov',$getProv->name)
                            ->set('phone',$phone)
                            ->set('create_date',date("Y-m-d H:i:s"))
                            ->set('create_user',$_SESSION['rick_auto']['username'])
                            ->insert('member');
            $idMember = $this->db->insert_id();
        if($insert){
            $getSales = $this->model_master->getSales();
            foreach($getSales->result() as $sales){
                $insertMemberSales = $this->db->set('member_id',$idMember)->set('sales_id',$sales->id)->set('active',1)->insert('member_sales');
            }
            echo "1";
        }
    }

    public function simpan_edit_Member(){
        $id       = $this->input->post('id');
        $getData = $this->model_master->getMemberByID($id)->row();
        $password = $this->input->post('password');
        if($password == ""){
            $passwords = $getData->password;
        }else{
            $passwords = sha1($password);
        }
        $username = $this->input->post('username');
        $kode     = $this->input->post('kode');
        $fullname = $this->input->post('fullname');
        $email    = $this->input->post('email');
        $password = sha1($this->input->post('password'));
        $cmbHarga = $this->input->post('cmbHarga');
        $cmbCity  = $this->input->post('cmbCity');
        $phone    = $this->input->post('phone');
        $ktp = $this->input->post('ktp');
        $alamat_toko = $this->input->post('alamat_toko');
        $alamat   = $this->input->post('alamat');
        $ktp      = $this->input->post('ktp');
        $getCity = $this->model_master->getCitybyId($cmbCity)->row();
        $getProv = $this->model_master->getProvbyId($cmbProv)->row();
         $update = $this->db->set('email',$email)
                            ->set('name',$fullname)
                            ->set('operation_price',$cmbHarga)
                            ->set('city_id',$cmbCity)
                            ->set('city',$getCity->name)
                            ->set('prov',$getProv->name)
                            ->set('password',$passwords)
                            ->set('address',$alamat)
                            ->set('address_toko',$alamat_toko)
                            ->set('phone',$phone)
                            ->set('ktp',$ktp)
                            ->where('id',$id)
                            ->update('member');
        if($update){
            echo "1";
        }        
    }

    public function manage_sales_member(){
        $id_member = $this->input->post('id');
        $cek = $this->model_master->getMemberSalesByMember($id_member);
        if($cek->num_rows() > 0){
        echo"
        <form id='formEdit'>
            <div class='col-md-12'><label class='text-semibold'>Daftar Pilihan Sales</label>
                <div class='form-group'>";
                $getSales = $this->model_master->getSales();
                foreach($getSales->result() as $sales){
                    $cekMember = $this->model_master->getMemberSalesByMemberSales($id_member,$sales->id)->row();
                    if($cekMember->active == 1){
                        $checked = "checked";
                        $valuee = "1";
                    }else{
                        $checked = "";
                        $valuee = "0";
                    }
                    echo"
                    <div class='checkbox col-md-4'>
                        <label>
                            <input type='checkbox' $checked id='sales_cek_edit".$cekMember->id."'  onclick=pilih_sales_edit(".$cekMember->id.")>
                            ".$sales->name."
                        </label>
                        <input type='hidden' id='txtSalesEdit_".$cekMember->id."' name='txtSalesEdit_".$cekMember->id."' value='".$valuee."'>
                    </div>";}echo"
                </div>
            </div>
            <div style='margin-top:30px;margin-bottom:30px'>
                <button type='button' onclick=javascript:simpan_edit_member_sales('".$id_member."') class='btn btn-primary'>Simpan Data</button>
                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
            </div>
        </form>";            
        }else{
        echo"
        <form id='formAdd'>
            <div class='col-md-12'><label class='text-semibold'>Daftar Pilihan Sales</label>
                <div class='form-group'>";
                $getSales = $this->model_master->getSales();
                foreach($getSales->result() as $sales){
                    echo"
                    <div class='checkbox col-md-4'>
                        <label>
                            <input type='checkbox' id='sales_cek".$sales->id."'  onclick=pilih_sales('".$sales->id."')>
                            ".$sales->name."
                        </label>
                        <input type='hidden' id='txtSales_".$sales->id."' name='txtSales_".$sales->id."' value='0'>
                    </div>";}echo"
                </div>
            </div>
            <div style='margin-top:30px;margin-bottom:30px'>
                <button id='btnSave' type='button' class='btn btn-primary'>Ya</button>
                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
            </div>
        </form>
        ";
        }

    }

    public function manage_perusahaan_gudang_(){
        $id_perusahaan = $this->input->post('id');
        $cek = $this->model_master->getPerusahaanGudang($id_perusahaan);
        $perusahaan = $this->model_master->getPerusahaanByID($id_perusahaan)->row();
        if($cek->num_rows() > 0){
        echo"
        <form id='formEdit'>
            <div class='col-md-12'><label class='text-semibold'>Daftar Pilihan Gudang ".$perusahaan->name."</label>
                <div class='form-group'>";
                $getGudang = $this->model_master->getGudang();
                foreach($getGudang->result() as $gudang){
                    $cekPerusahaan = $this->model_master->getPerusahaanGudangByGudang($id_perusahaan,$gudang->id)->row();
                    if($cekPerusahaan->active == 1){
                        $checked = "checked";
                        $valuee = "1";
                        $read = "";
                    }else{
                        $checked = "";
                        $valuee = "0";
                        $read = "readonly";
                    }
                    echo"
                    <div class='checkbox col-md-3'>
                        <label>
                            <input type='checkbox' $checked id='gudang_cek_edit".$cekPerusahaan->id."'  onclick=pilih_gudang_edit(".$cekPerusahaan->id.")>
                            ".$gudang->name."
                        </label>
                        <input type='hidden' id='txtGudangEdit_".$cekPerusahaan->id."' name='txtGudangEdit_".$cekPerusahaan->id."' value='".$valuee."' >
                    </div>
                    <div class='checkbox col-md-3'>
                        <label>
                            <input type='text' id='txtStockEdit_".$cekPerusahaan->id."' name='txtStockEdit_".$cekPerusahaan->id."' class='form-control' value='".$cekPerusahaan->stok."' placeholder='Masukan Stok' ".$read.">
                        </label>
                    </div>";}echo"
                </div>
            </div>
            <div style='margin-top:30px;margin-bottom:30px'>
                <button type='button' onclick=javascript:simpan_edit_perusahaan_gudang('".$id_perusahaan."') class='btn btn-primary'>Simpan Data</button>
                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
            </div>
        </form>";            
        }else{
        echo"
        <form id='formAdd'>
            <div class='col-md-12'>
                <div class='form-group'>
                <label class='text-semibold'>Daftar Pilihan Gudang ".$perusahaan->name."</label>
                <div class='row'>";
                $getGudang = $this->model_master->getGudang();
                foreach($getGudang->result() as $gudang){
                    echo"
                    <div class='checkbox col-md-3'>
                        <div class='checkbox'>
                        <label>
                            <input type='checkbox' id='gudang_cek".$gudang->id."'  onclick=pilih_gudang('".$gudang->id."')>
                            ".$gudang->name."
                        </label>
                        <input type='hidden' id='txtGudang_".$gudang->id."' name='txtGudang_".$gudang->id."' value='0'>
                        </div>
                    </div>
                    <div class='checkbox col-md-3'>
                        <div class='checkbox'>
                        <label>
                            <input type='text' id='txtStock_".$gudang->id."' name='txtStock_".$gudang->id."' class='form-control' placeholder='Masukan Stok' readonly>
                        </label>
                        </div>
                    </div>";}echo"
                </div>
                </div>
            </div>
            <div style='margin-top:30px;margin-bottom:30px'>
                <button id='btnSave' type='button' class='btn btn-primary'>Ya</button>
                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
            </div>
        </form>
        ";
        }        
    }

    public function manage_perusahaan_gudang(){
        $id_perusahaan = $this->input->post('id');
        $cek = $this->model_master->getPerusahaanGudang($id_perusahaan);
        $perusahaan = $this->model_master->getPerusahaanByID($id_perusahaan)->row();
        if($cek->num_rows() > 0){
        echo"
        <form id='formEdit'>
            <div class='col-md-12'><label class='text-semibold'>Daftar Pilihan Gudang ".$perusahaan->name."</label>
                <div class='form-group'>";
                $getGudang = $this->model_master->getGudang();
                foreach($getGudang->result() as $gudang){
                    $cekPerusahaan = $this->model_master->getPerusahaanGudangByGudang($id_perusahaan,$gudang->id)->row();
                    if($cekPerusahaan->active == 1){
                        $checked = "checked";
                        $valuee = "1";
                        $read = "";
                    }else{
                        $checked = "";
                        $valuee = "0";
                        $read = "readonly";
                    }
                    echo"
                    <div class='checkbox col-md-12'>
                        <label>
                            <input type='checkbox' $checked id='gudang_cek_edit".$cekPerusahaan->id."'  onclick=pilih_gudang_edit(".$cekPerusahaan->id.")>
                            ".$gudang->name."
                        </label>
                        <input type='hidden' id='txtGudangEdit_".$cekPerusahaan->id."' name='txtGudangEdit_".$cekPerusahaan->id."' value='".$valuee."' >
                    </div>";}echo"
                </div>
            </div>
            <div style='margin-top:30px;margin-bottom:30px'>
                <button type='button' onclick=javascript:simpan_edit_perusahaan_gudang('".$id_perusahaan."') class='btn btn-primary'>Simpan Data</button>
                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
            </div>
        </form>";            
        }else{
        echo"
        <form id='formAdd'>
            <div class='col-md-12'>
                <div class='form-group'>
                <label class='text-semibold'>Daftar Pilihan Gudang ".$perusahaan->name."</label>
                <div class='row'>";
                $getGudang = $this->model_master->getGudang();
                foreach($getGudang->result() as $gudang){
                    echo"
                    <div class='checkbox col-md-12'>
                        <div class='checkbox'>
                        <label>
                            <input type='checkbox' id='gudang_cek".$gudang->id."'  onclick=pilih_gudang('".$gudang->id."')>
                            ".$gudang->name."
                        </label>
                        <input type='hidden' id='txtGudang_".$gudang->id."' name='txtGudang_".$gudang->id."' value='0'>
                        </div>
                    </div>";}echo"
                </div>
                </div>
            </div>
            <div style='margin-top:30px;margin-bottom:30px'>
                <button id='btnSaveKecil' type='button' class='btn btn-primary'>Ya</button>
                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
            </div>
        </form>
        ";
        }        
    }

    public function simpan_member_sales(){
        $id = $this->input->post('id');
        $getSales = $this->model_master->getSales();
        foreach($getSales->result() as $sales){
            $active = $this->input->post('txtSales_'.$sales->id);
            $insert = $this->db->set('member_id',$id)->set('sales_id',$sales->id)->set('active',$active)->insert('member_sales');
        }

        echo "1";

    }

    public function simpan_perusahaan_gudang_(){
        $id = $this->input->post('id');
        $getGudang = $this->model_master->getGudang();
        foreach($getGudang->result() as $gudang){
            $active = $this->input->post('txtGudang_'.$gudang->id);
            $txtStock = $this->input->post('txtStock_'.$gudang->id);
            $insert = $this->db->set('perusahaan_id',$id)->set('gudang_id',$gudang->id)->set('active',$active)->set('stok',$txtStock)->insert('perusahaan_gudang');
        }

        echo "1";        
    }

    public function simpan_perusahaan_gudang(){
        $id = $this->input->post('id');
        $getGudang = $this->model_master->getGudang();
        foreach($getGudang->result() as $gudang){
            $active = $this->input->post('txtGudang_'.$gudang->id);
            //$txtStock = $this->input->post('txtStock_'.$gudang->id);
            $insert = $this->db->set('perusahaan_id',$id)->set('gudang_id',$gudang->id)->set('active',$active)->insert('perusahaan_gudang');
        }

        echo "1";        
    }

    public function simpan_edit_member_sales(){
        $id = $this->input->post('id');
        $getSales = $this->model_master->getMemberSalesByMember($id);
        foreach($getSales->result() as $sales){
           // echo $this->input->post('txtSalesEdit_'.$sales->id);
            $active = $this->input->post('txtSalesEdit_'.$sales->id);
            $insert = $this->db->set('active',$active)->where('id',$sales->id)->update('member_sales');
        }

        echo "1";        
    }

    public function simpan_edit_perusahaan_gudang_(){
        $id = $this->input->post('id');
        $getGudang = $this->model_master->getPerusahaanGudang($id);
        foreach($getGudang->result() as $gudang){
           // echo $this->input->post('txtSalesEdit_'.$gudang->id);
            $active = $this->input->post('txtGudangEdit_'.$gudang->id);
            $stok = $this->input->post('txtStockEdit_'.$gudang->id);
            $insert = $this->db->set('active',$active)->set('stok',$stok)->where('id',$gudang->id)->update('perusahaan_gudang');
        }

        echo "1";             
    }

    public function simpan_edit_perusahaan_gudang(){
        $id = $this->input->post('id');
        $getGudang = $this->model_master->getPerusahaanGudang($id);
        foreach($getGudang->result() as $gudang){
           // echo $this->input->post('txtSalesEdit_'.$gudang->id);
            $active = $this->input->post('txtGudangEdit_'.$gudang->id);
            $cekData = $this->model_master->getPerusahaanGudangByGudang($id,$gudang->id);
            //if($cekData->num_rows() > 0){
            //$stok = $this->input->post('txtStockEdit_'.$gudang->id);
            $insert = $this->db->set('active',$active)->where('id',$gudang->id)->update('perusahaan_gudang');
            // }else{
            // $insert = $this->db->set('active',$active)->set('perusahaan_id',$id)->set('gudang_id',$gudang->id)->insert('perusahaan_gudang');    
            // }
        }

        echo "1";             
    }

    public function getSingkatan(){
        $cmbCity = $this->input->post('cmbCity');
        $dataCity = $this->model_master->getCitybyId($cmbCity)->row();
        $dataMaster = $this->model_master->getAllMembersByAbbr($dataCity->abbreviation)->row();
        $dataMasters = explode(".",$dataMaster->uniq_code);
        $dm = $dataMasters[1] + 1;
        //echo $dataMaster->uniq_code;


        echo strtoupper($dataCity->abbreviation).".".sprintf("%'.04d", $dm); 
    }

    public function confirm_delete_member(){
        $id = $this->input->post('id');
        $getPurc = $this->model_purchase->getPurchaseByMember($id);
        $getTemp = $this->model_purchase->getTemporaryByMember($id);
        
        if($getTemp->num_rows() > 0){
            echo "3";
        }elseif($getPurc->num_rows() > 0){
            echo "2";
        }else{
            echo "1";
        }
        
    }

    public function confirm_delete_sales(){
        $id = $this->input->post('id');
        $getPurc = $this->model_purchase->getPurchaseBySales($id);
        $getTemp = $this->model_purchase->getTemporaryBySales($id);
        
        if($getTemp->num_rows() > 0){
            echo "3";
        }elseif($getPurc->num_rows() > 0){
            echo "2";
        }else{
            echo "1";
        }
        
    }

    public function hapus_member(){
        $id = $this->input->post('id');
        $delete = $this->db->where('id',$id)->delete('member');
        
        if($delete){
            echo "1";
        }
        
    }

    public function hapus_sales(){
        $id = $this->input->post('id');
        $delete = $this->db->where('id',$id)->delete('sales');
        
        if($delete){
            echo "1";
        }
        
    }

    public function hapus_user(){
        $id = $this->input->post('id');
        $delete = $this->db->where('id',$id)->delete('users');
        
        if($delete){
            echo "1";
        }
        
    }

    

    


}
?>