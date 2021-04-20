<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
* CONTROLLER INDEX WEBSITE
* This controler for screen index
*
* Log Activity : ~ Create your log if you change this controller ~
* 1. Create 20 Mei 2019 By Devanda Andrevianto, Create All Function, Create controller
*/
class Produk extends CI_Controller
{
    var $data = array('scjav' => 'assets/jController/admin/CtrlProduk.js');
    var $limit = 10;
    var $offset = 0;
    function __construct()
    {
        parent::__construct();
        $this->load->model('admin/model_master');
        $this->load->model('admin/model_produk');
        $this->load->model('admin/model_purchase');
        $this->load->model('admin/model_datatable');
        $this->load->model('admin/model_datatable_stok');

        // $this->lang->load('admin', '');
        if (empty($_SESSION['rick_auto']) || empty($_SESSION['rick_auto']['id'])) {
            redirect('admin/index/signin/');
            return;
        }
    }

    // fungsi untuk mengecek apakah user sudah login
    public function index()
    {
        $this->data['getUsers'] = $this->model_master->getUsers();
        $this->data['getProducts'] = $this->model_produk->getProducts();
        $this->template->rick_auto('produk/bg_index', $this->data);
    }

    function data_()
    {
        $response = array(
            'data' => $this->Mod_customer->get_all()->result()
        );

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT))
            ->_display();
        exit;
    }


    public function getData_()
    {
        $response = array(
            'data' => $this->model_produk->getProducts()->result()
        );
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT))
            ->_display();
        exit;
        //echo json_encode($response, JSON_PRETTY_PRINT);
    }

    public function getData()
    {
        $list = $this->model_datatable->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $getProduks = $this->model_produk->getProdukByBarcodebyProduk($field->id);
            $getProduk = $this->model_produk->getProdukByBarcodebyProduk($field->id)->row();
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->product_code;
            if ($field->flag_jenis_satuan == 1) {
                $row[] = $field->product_name;
            } else {
                $row[] = $field->product_name . " (" . $field->satuan_value . " Pcs)";
            }
            $row[] = $field->nama_kategori;
            $row[] = $field->nama_satuan;
            $row[] = number_format($field->normal_price, 2, ',', '.');
            $row[] = $field->is_liner;

            if ($field->product_cover == "" || $field->product_cover == NULL) {
                $field->product_cover = 'web/images/no_img.png';
            }

            $row[] = "<img src=" . base_url($field->product_cover) . " width='80px;' height='80px;'>";
            if ($getProduks->num_rows() > 0) {
                $row[] = "<img src=" . base_url("qrcode/" . $getProduk->product_id . ".png") . " width='50px;' height='50px;'>";
                $row[] = "<img src=" . base_url("qrcode2/" . $getProduk->product_id . ".png") . " width='50px;' height='50px;'>";
            } else {
                $row[] = "";
                $row[] = "";
            }

            $row[] = "<a href='#' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:view_detail_image(" . $field->id . ") class='btn btn-primary btn-icon'><i class='icon-images2'></i></a>
                                    <a href='" . base_url("admin/produk/edit/" . base64_encode($field->id) . "") . "' class='btn btn-warning btn-icon'><i class='icon-pencil7'></i></a>
                                    <a href='#' data-toggle='modal' data-target='#modal_delete_data' onclick=javascript:confirm_delete_produk(" . $field->id . ") class='btn btn-danger btn-icon'><i class='icon-trash'></i></a>";
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->model_datatable->count_all(),
            "recordsFiltered" => $this->model_datatable->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function getDataStok()
    {
        $list = $this->model_datatable_stok->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->product_code;
            $row[] = $field->product_name;
            $row[] = $field->nama_kategori;
            $row[] = $field->nama_satuan;
            $nama_produk = $field->product_code . "" . $field->product_name;
            $jmlStok = $this->model_produk->getJumlahStok($field->id);
            if ($jmlStok->num_rows() > 0) {
                $jumlah = $jmlStok->row()->jmlStok;
                if ($jumlah > 0) {
                    $st = "<span class='label label-success label-rounded'>Stock Available</span>";
                } else {
                    $st = "<span class='label label-danger label-rounded'>Out Of Stock</span>";
                }
                // if($jmlStok->row()->jmlStok == 0){
                //     $jumlah = $jmlStok->row()->jmlStok;
                //     $st = "<span class='label label-danger label-rounded'>Out Of Stock</span>";
                // }
            } else {
                $jumlah = 0;
                $st = "<span class='label label-danger label-rounded'>Out Of Stock</span>";
            }

            $namaProuk = str_replace(' ', '_', $nama_produk);
            $row[] = "<a href='#' data-toggle='modal' data-target='#confirmation_modal' onclick=javascript:view_detail_stok(" . $field->id . ",'" . $namaProuk . "') class='btn btn-primary btn-icon'><i class='icon-database-menu'></i>Detail Stok </a> <br>" . $st . "";
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->model_datatable_stok->count_all(),
            "recordsFiltered" => $this->model_datatable_stok->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function getData2()
    {
        $data = $this->model_produk->getProducts()->result();
        echo json_encode($data);
    }



    // public function json(){
    //     $this->load->library('datatables');
    //      $this->datatables->add_column('foto', '<img src="http://www.rutlandherald.com/wp-content/uploads/2017/03/default-user.png" width=20>', 'foto');
    //     $this->datatables->select('nama_lengkap,email,no_hp');
    //     $this->datatables->add_column('action', anchor('karyawan/edit/.$1','Edit',array('class'=>'btn btn-danger btn-sm')), 'id_pegawai');
    //     $this->datatables->from('karyawan');
    //     return print_r($this->datatables->generate());
    // }


    public function index__()
    {
        $page = $this->uri->segment(4);
        $uri = 4;
        $limit = $this->limit;
        if (!$page) :
            $offset = $this->offset;
        else :
            $offset = $page;
        endif;
        $pg = $this->model_produk->getProducts();
        $url = 'admin/produk/index';
        $this->data['pagination'] = $this->template->paging2($pg, $uri, $url, $limit);
        $this->data['getProducts'] = $this->model_produk->getProducts($limit, $offset);
        if ($this->input->post('ajax')) {
            $this->load->view('admin/produk/bg_index_ajax', $this->data);
        } else {
            $this->template->rick_auto('produk/bg_index', $this->data);
        }
    }

    public function order()
    {
        $this->data['getData'] = $this->model_produk->getProdukBeli();
        $this->template->rick_auto('produk_order/bg_index', $this->data);
    }

    public function order_add()
    {
        $this->data['getProducts'] = $this->model_produk->getProducts();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->data['getMembers'] = $this->model_master->getAllMembers();
        $this->data['getExpedisi'] = $this->model_master->getExpedisi();
        $this->template->rick_auto('produk_order/bg_add', $this->data);
    }

    public function order_add_barcode()
    {
        $this->db->query("TRUNCATE TABLE produk_beli_detail_temp");
        $this->data['getDataDetail'] = $this->model_produk->getProdukBeliTemp();
        $this->data['getProducts'] = $this->model_produk->getProducts();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->data['getMembers'] = $this->model_master->getAllMembers();
        $this->data['getExpedisi'] = $this->model_master->getExpedisi();
        $this->template->rick_auto('produk_order_barcode/bg_add', $this->data);
    }

    public function so_add()
    {
        $this->db->query("truncate table stock_opname_detail_temp");
        $this->data['getDataDetail'] = $this->model_produk->getSOTemp();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->data['getExpedisi'] = $this->model_master->getExpedisi();
        $this->template->rick_auto('so/bg_add', $this->data);
    }

    public function so_edit()
    {
        $id = base64_decode($this->uri->segment(4));
        $this->data['getDataDetail'] = $this->model_produk->getSODetailBySO($id);
        $this->data['getData'] = $this->model_produk->getSOById($id)->row();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->data['getExpedisi'] = $this->model_master->getExpedisi();
        $this->template->rick_auto('so/bg_edit', $this->data);
    }

    public function so_print()
    {
        $id = base64_decode($this->uri->segment(4));
        $this->data['getDataDetail'] = $this->model_produk->getSODetailBySO($id);
        $this->data['getData'] = $this->model_produk->getSOById($id)->row();
        $this->load->view('admin/so/bg_print', $this->data);
    }



    public function so()
    {
        $this->data['getData'] = $this->model_produk->getSO();
        $this->template->rick_auto('so/bg_index', $this->data);
    }


    public function order_edit()
    {
        $id = base64_decode($this->uri->segment(4));
        $this->data['getData'] = $this->model_produk->getProdukBeliById($id)->row();
        $this->data['getDataDetail'] = $this->model_produk->getProdukBeliDetailByIdProdukBeli($id);

        $this->data['getProducts'] = $this->model_produk->getProducts();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->data['getMembers'] = $this->model_master->getAllMembers();
        $this->data['getExpedisi'] = $this->model_master->getExpedisi();
        $this->template->rick_auto('produk_order/bg_edit', $this->data);
    }

    public function add()
    {
        $this->data['getKategori'] = $this->model_master->getKategori();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getSatuan'] = $this->model_master->getSatuan();
        $this->template->rick_auto('produk/bg_add', $this->data);
    }

    public function mutasi_add()
    {
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->template->rick_auto('mutasi/bg_add', $this->data);
    }

    public function edit()
    {
        $id = base64_decode($this->uri->segment(4));
        //$id = $this->uri->segment(4);
        $this->data['produk'] = $this->model_produk->getProductById($id)->row();
        $this->data['getKategori'] = $this->model_master->getKategori();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getSatuan'] = $this->model_master->getSatuan();
        $this->data['getBarcode'] = $this->model_produk->getProdukByBarcodebyProdukAsc($id);
        $this->data['jumlahbarcode'] = count($this->model_produk->getProdukByBarcodebyProdukAsc($id)->result());
        $this->template->rick_auto('produk/bg_edit', $this->data);
    }

    public function delete()
    {
        $id = $this->input->post('id');

        $delete = $this->db->where('id', $id)->delete('product');

        if ($delete) {
            $delete_image = $this->db->where('id_product', $id)->delete('product_img');
        }

        echo json_encode(array('status' => 1));
    }


    public function tambah_foto()
    {
        $total_gambar = $this->input->post('total_gambar');

        $i = $total_gambar;

        echo "
            <div class='media-left'>
                <img id='img_pic_" . $i . "' class='images' src='" . base_url("design/admin/assets/images/placeholder2.png") . "' style='cursor:pointer; width: 58px; height: 58px;' class='img-rounded' alt='' style='cursor:pointer;width:150px; height:150px' onclick=javascript:click_picture('pic_" . $i . "')>
                <input type='file' class='pic_product'  name='pic_" . $i . "' id='pic_" . $i . "' style='opacity: 0.0;width:1px; height:1px' OnChange=javascript:picture_upload(this.id,image_high_" . $i . ",image_tumb_" . $i . "," . $i . ")>
                <input id='image_high_" . $i . "' name='image_high_" . $i . "' type='hidden'/>
                <input id='image_tumb_" . $i . "' name='image_tumb_" . $i . "' type='hidden'/>
                <input id='gambar_default_" . $i . "' type='hidden' name='gambar_default_" . $i . "' value='1'>
            </div>
        ";
    }

    public function pilih_satuan()
    {
        $cmbSatuan = $this->input->post('cmbSatuan');

        $getData = $this->model_master->getSatuanById($cmbSatuan)->row();

        //echo $getData->flag_jenis;
        echo json_encode(array('flag_jenis' => $getData->flag_jenis));
    }

    function imageCreateFromAny($filepath)
    {

        $size = getimagesize($filepath);
        switch ($size["mime"]) {
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

    public function simpan_data_()
    {
        @ini_set('upload_max_size', '64M');
        @ini_set('post_max_size', '64M');
        @ini_set('max_execution_time', '2500');
        $path = 'web/images/product/';
        $nama_produk = $this->input->post('nama_produk');
        $cmbSatuan = $this->input->post('cmbSatuan');
        $isi_produk = $this->input->post('isi_produk');
        $kode_produk = $this->input->post('kode_produk');
        $deskripsi = $this->input->post('deskripsi');
        $cmbKategori = $this->input->post('cmbKategori');
        $harga_produk = $this->input->post('harga_produk');
        $stock_min = $this->input->post('stock_min');
        $gambar_default_cover_1 = $this->input->post('gambar_default_cover_1');
        $isi_produk = $this->input->post('isi_produk');
        $getData = $this->model_master->getSatuanById($cmbSatuan)->row();
        //$totalGudang = $this->input->post('totalGudang');
        if ($gambar_default_cover_1 == 0) {
            $gambar_cover = $this->template->upload_picture_not_resize($path, $_POST['image_high_cover_1'], $_POST['image_tumb_cover_1'], 1);
            $link_cover = "web/images/product/" . $gambar_cover;
        } else {
            $gambar_cover = "";
            $link_cover = "";
        }
        if ($kode_produk == "" || $nama_produk == "") {
            echo "2";
        } else {
            $insert_produk = $this->db->set('product_code', $kode_produk)
                ->set('product_name', $nama_produk)
                ->set('product_cover', $link_cover)
                ->set('product_desc', $deskripsi)
                ->set('category_id', $cmbKategori)
                ->set('stock_min', $stock_min)
                ->set('satuan_id', $cmbSatuan)
                ->set('normal_price', str_replace(".", "", $harga_produk))
                ->set('product_status', 1)
                ->set('satuan_value', $isi_produk)
                ->insert('product');
            $id_product = $this->db->insert_id();

            if ($insert_produk) {
                for ($i = 1; $i <= 5; $i++) {
                    $gambar_default = $this->input->post('gambar_default_' . $i);
                    if ($gambar_default == 0) {
                        $gambar_detail = $this->template->upload_picture_not_resize($path, $_POST['image_high_' . $i], $_POST['image_tumb_' . $i], $i);
                        $link_detail = "web/images/product/" . $gambar_detail;
                    } else {
                        $gambar_detail = "";
                        $link_detail = "";
                    }
                    if ($gambar_default == 0) {
                        $insert_gambar = $this->db->set('id_product', $id_product)->set('product_img', $link_detail)->insert('product_img');
                    }
                }
            }

            echo "1";
        }
    }

    public function simpan_data()
    {
        $this->load->library('ciqrcode');
        $path = 'web/images/product/';
        $nama_produk = $this->input->post('nama_produk');
        $cmbSatuan = $this->input->post('cmbSatuan');
        $isi_produk = $this->input->post('isi_produk');
        $kode_produk = $this->input->post('kode_produk');
        $deskripsi = $this->input->post('deskripsi');
        $cmbKategori = $this->input->post('cmbKategori');
        $harga_produk = $this->input->post('harga_produk');
        $harga_ekspor = $this->input->post('harga_ekspor');
        $stock_min = $this->input->post('stock_min');
        $gambar_default_cover_1 = $this->input->post('gambar_default_cover_1');
        $isi_produk = $this->input->post('isi_produk');
        $getData = $this->model_master->getSatuanById($cmbSatuan)->row();
        //$totalGudang = $this->input->post('totalGudang');
        $config['upload_path']          = './web/images/product/';
        $config['allowed_types']        = 'gif|jpg|jpeg|png|pdf|doc';
        $config['max_size']             = 10000;
        // $config['max_width']            = 1300;
        // $config['max_height']           = 1024;
        $this->load->library('upload', $config);

        if ($kode_produk == "") {
            $this->form_validation->set_rules('kode_produk', 'required');
        }

        if (!$this->upload->do_upload('fileCover')) {

            $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

            $error = array('error' => $this->upload->display_errors());
            $dataDokumen = "";
            $link_cover = "";
            // print_r($this->upload->display_errors());
            // die();

            //$this->load->view('dokumentasi/bg_add', $error);
        } else {
            //print_r($this->upload->data());
            // $datas = array_keys($this->upload->data());
            $dataDokumen = $this->upload->data();
            $link_cover = "web/images/product/" . $dataDokumen['file_name'];
            //echo $datas['file_name'];
            //die();

            //$data = array('upload_data' => $this->upload->data());

            // $this->load->view('success', $data);
        }

        if ($kode_produk == "" || $nama_produk == "") {
            echo "2";
        } else {
            $insert_produk = $this->db->set('product_code', $kode_produk)
                ->set('product_name', $nama_produk)
                ->set('product_cover', $link_cover)
                ->set('product_desc', $deskripsi)
                ->set('category_id', $cmbKategori)
                ->set('stock_min', $stock_min)
                ->set('satuan_id', $cmbSatuan)
                ->set('normal_price', str_replace(".", "", $harga_produk))
                ->set('ekspor_price', str_replace(".", "", $harga_ekspor))
                ->set('product_status', 1)
                ->set('satuan_value', $isi_produk)
                ->insert('product');
            $id_product = $this->db->insert_id();

            if ($insert_produk) {
                for ($ii = 1; $ii <= 2; $ii++) {
                    $qrcode = $this->input->post('qrcode' . $ii);
                    $qrcode_value = $this->input->post('qrcode_value' . $ii);
                    $qr_image = $id_product . '.png';

                    $params['data'] = $qrcode;

                    $params['level'] = 'H';

                    $params['size'] = 100;
                    if ($ii == 1) {

                        $params['savename'] = FCPATH . "qrcode/" . $qr_image;
                    } else {
                        $params['savename'] = FCPATH . "qrcode2/" . $qr_image;
                    }

                    if ($this->ciqrcode->generate($params)) {

                        $qrGen = $qr_image;
                    }
                    $insert_barcode = $this->db->set('product_id', $id_product)->set('barcode', $qrcode)->set('isi', $qrcode_value)->insert('product_barcode');
                }

                for ($i = 1; $i <= 5; $i++) {
                    $config['upload_path']          = './web/images/product/';
                    $config['allowed_types']        = 'gif|jpg|jpeg|png|pdf|doc';
                    $config['max_size']             = 10000;
                    // $config['max_width']            = 1300;
                    // $config['max_height']           = 1024;
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('fileDetail_' . $i)) {

                        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

                        $error = array('error' => $this->upload->display_errors());
                        $dataDokumenDetail = "";
                        $link_detail = "";
                        // print_r($this->upload->display_errors());
                        // die();

                        //$this->load->view('dokumentasi/bg_add', $error);
                    } else {
                        //print_r($this->upload->data());
                        // $datas = array_keys($this->upload->data());
                        $dataDokumenDetail = $this->upload->data();
                        //echo $datas['file_name'];
                        //die();
                        $link_detail = "web/images/product/" . $dataDokumenDetail['file_name'];
                        //$data = array('upload_data' => $this->upload->data());

                        // $this->load->view('success', $data);
                        $insert_gambar = $this->db->set('id_product', $id_product)->set('product_img', $link_detail)->insert('product_img');
                    }
                }
            }

            redirect(base_url("admin/produk/index"));
        }
    }

    public function edit_data()
    {
        $path = 'web/images/product/';
        $id_product = $this->input->post('id');
        $nama_produk = $this->input->post('nama_produk');
        $cmbSatuan = $this->input->post('cmbSatuan');
        $isi_produk = $this->input->post('isi_produk');
        $kode_produk = $this->input->post('kode_produk');
        $kode_produk_bayangan = $this->input->post('kode_produk_bayangan');
        $cmbIsLiner = $this->input->post('cmbIsLiner');
        $deskripsi = $this->input->post('deskripsi');
        $cmbKategori = $this->input->post('cmbKategori');
        $harga_produk = $this->input->post('harga_produk');
        $harga_ekspor = $this->input->post('harga_ekspor');
        $stock_min = $this->input->post('stock_min');
        $gambar_default_cover_1 = $this->input->post('gambar_default_cover_1');
        $isi_produk = $this->input->post('isi_produk');
        $getData = $this->model_master->getSatuanById($cmbSatuan)->row();
        //$totalGudang = $this->input->post('totalGudang');
        $config['upload_path']          = './web/images/product/';
        $config['allowed_types']        = 'gif|jpg|jpeg|png|pdf|doc';
        $config['max_size']             = 10000;
        // $config['max_width']            = 1300;
        // $config['max_height']           = 1024;
        $this->load->library('upload', $config);
        $getDataProduk = $this->model_produk->getProductById($id_product)->row();

        if (!$this->upload->do_upload('fileCover')) {

            $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

            $error = array('error' => $this->upload->display_errors());
            $dataDokumen = "";
            $link_cover = $getDataProduk->product_cover;
            // print_r($this->upload->display_errors());
            // die();

            //$this->load->view('dokumentasi/bg_add', $error);
        } else {
            //print_r($this->upload->data());
            // $datas = array_keys($this->upload->data());
            $dataDokumen = $this->upload->data();
            $link_cover = "web/images/product/" . $dataDokumen['file_name'];
            //echo $datas['file_name'];
            //die();

            //$data = array('upload_data' => $this->upload->data());

            // $this->load->view('success', $data);
        }

        if ($kode_produk == "" || $nama_produk == "") {
            echo "2";
        } else {
            $update_produk = $this->db->set('product_code', $kode_produk)
                ->set('product_code_shadow', $kode_produk_bayangan)
                ->set('product_name', $nama_produk)
                ->set('product_cover', $link_cover)
                ->set('product_desc', $deskripsi)
                ->set('category_id', $cmbKategori)
                ->set('stock_min', $stock_min)
                ->set('satuan_id', $cmbSatuan)
                ->set('normal_price', str_replace(".", "", $harga_produk))
                ->set('ekspor_price', str_replace(".", "", $harga_ekspor))
                ->set('is_liner', $cmbIsLiner)
                ->set('product_status', 1)
                ->set('satuan_value', $isi_produk)
                ->where('id', $id_product)
                ->update('product');
            //$id_product = $this->db->insert_id();

            if ($update_produk) {
                $no = 0;
                $getBarcode = $this->model_produk->getProdukByBarcodebyProdukAsc($id_product);
                if (count($getBarcode->result()) == "0") {
                    for ($ii = 1; $ii <= 2; $ii++) {
                        $qrcode = $this->input->post('qrcode' . $ii);
                        $qrcode_value = $this->input->post('qrcode_value' . $ii);
                        $qr_image = $id_product . '.png';

                        $params['data'] = $qrcode;

                        $params['level'] = 'H';

                        $params['size'] = 100;
                        if ($ii == 1) {

                            $params['savename'] = FCPATH . "qrcode/" . $qr_image;
                        } else {
                            $params['savename'] = FCPATH . "qrcode2/" . $qr_image;
                        }

                        if ($this->ciqrcode->generate($params)) {

                            $qrGen = $qr_image;
                        }
                        $insert_barcode = $this->db->set('product_id', $id_product)->set('barcode', $qrcode)->set('isi', $qrcode_value)->insert('product_barcode');
                    }
                } else {
                    foreach ($getBarcode->result() as $barcode) {
                        $no++;
                        $qrcode = $this->input->post('qrcode' . $barcode->id);
                        $qrcode_value = $this->input->post('qrcode_value' . $barcode->id);
                        $qr_image = $id_product . '.png';

                        $params['data'] = $qrcode;

                        $params['level'] = 'H';

                        $params['size'] = 100;
                        if ($ii == 1) {

                            $params['savename'] = FCPATH . "qrcode/" . $qr_image;
                        } else {
                            $params['savename'] = FCPATH . "qrcode2/" . $qr_image;
                        }

                        if ($this->ciqrcode->generate($params)) {

                            $qrGen = $qr_image;
                        }
                        $update = $this->db->set('barcode', $qrcode)->set('isi', $qrcode_value)->where('id', $barcode->id)->update('product_barcode');
                    }
                }


                $getImageDetail = $this->model_produk->getImageProductByProduct($id_product);
                foreach ($getImageDetail->result() as $imageDetail) {
                    $config['upload_path']          = './web/images/product/';
                    $config['allowed_types']        = 'gif|jpg|jpeg|png|pdf|doc';
                    $config['max_size']             = 10000;
                    // $config['max_width']            = 1300;
                    // $config['max_height']           = 1024;
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('fileDetailEdit_' . $imageDetail->id)) {

                        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

                        $error = array('error' => $this->upload->display_errors());
                        $dataDokumenDetailEdit = "";
                        $link_detail_edit = "";
                        // print_r($this->upload->display_errors());
                        // die();

                        //$this->load->view('dokumentasi/bg_add', $error);
                    } else {
                        //print_r($this->upload->data());
                        // $datas = array_keys($this->upload->data());
                        $dataDokumenDetailEdit = $this->upload->data();
                        //echo $datas['file_name'];
                        //die();
                        $link_detail_edit = "web/images/product/" . $dataDokumenDetailEdit['file_name'];
                        //$data = array('upload_data' => $this->upload->data());

                        // $this->load->view('success', $data);
                        $insert_gambar = $this->db->set('product_img', $link_detail_edit)->where('id', $imageDetail->id)->update('product_img');
                    }
                }
                for ($i = 1; $i <= 5; $i++) {
                    $config['upload_path']          = './web/images/product/';
                    $config['allowed_types']        = 'gif|jpg|jpeg|png|pdf|doc';
                    $config['max_size']             = 10000;
                    // $config['max_width']            = 1300;
                    // $config['max_height']           = 1024;
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('fileDetailAdd_' . $i)) {

                        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

                        $error = array('error' => $this->upload->display_errors());
                        $dataDokumenDetailAdd = "";
                        $link_detail = "";
                        // print_r($this->upload->display_errors());
                        // die();

                        //$this->load->view('dokumentasi/bg_add', $error);
                    } else {
                        //print_r($this->upload->data());
                        // $datas = array_keys($this->upload->data());
                        $dataDokumenDetailAdd = $this->upload->data();
                        //echo $datas['file_name'];
                        //die();
                        $link_detail = "web/images/product/" . $dataDokumenDetailAdd['file_name'];
                        //$data = array('upload_data' => $this->upload->data());

                        // $this->load->view('success', $data);
                        $insert_gambar = $this->db->set('id_product', $id_product)->set('product_img', $link_detail)->insert('product_img');
                    }
                }
            }

            redirect(base_url("admin/produk/index"));
        }
    }

    public function edit_data_()
    {
        // @ini_set('upload_max_size','64M');
        // @ini_set('post_max_size','64M');
        // @ini_set('max_execution_time','2500');
        // ini_set('post_max_size', '64M');
        // ini_set('upload_max_filesize', '64M');
        $path = 'web/images/product/';
        $id_product = $this->input->post('id');
        $nama_produk = $this->input->post('nama_produk');
        $cmbSatuan = $this->input->post('cmbSatuan');
        $isi_produk = $this->input->post('isi_produk');
        $kode_produk = $this->input->post('kode_produk');
        $deskripsi = $this->input->post('deskripsi');
        $cmbKategori = $this->input->post('cmbKategori');
        $harga_produk = $this->input->post('harga_produk');
        $stock_min = $this->input->post('stock_min');
        $gambar_default_cover_1 = $this->input->post('gambar_default_cover_1');
        $isi_produk = $this->input->post('isi_produk');
        $getData = $this->model_master->getSatuanById($cmbSatuan)->row();
        $total_gambar = $this->input->post('total_gambar');
        //$totalGudang = $this->input->post('totalGudang');
        if ($gambar_default_cover_1 == 0) {
            $gambar_cover = $this->template->upload_picture_not_resize($path, $_POST['image_high_cover_1'], $_POST['image_tumb_cover_1'], 1);
            $link_cover = "web/images/product/" . $gambar_cover;
        } else {
            $gambar_cover = "";
            $link_cover = "web/images/product/product_1.jpg";
        }
        if ($kode_produk == "" || $nama_produk == "") {
            echo "2";
        } else {
            $insert_produk = $this->db->set('product_code', $kode_produk)
                ->set('product_name', $nama_produk)
                ->set('product_cover', $link_cover)
                ->set('product_desc', $deskripsi)
                ->set('category_id', $cmbKategori)
                ->set('stock_min', $stock_min)
                ->set('satuan_id', $cmbSatuan)
                ->set('normal_price', str_replace(".", "", $harga_produk))
                ->set('product_status', 1)
                ->set('satuan_value', $isi_produk)
                ->where('id', $id_product)
                ->update('product');

            if ($insert_produk) {
                $getImageDetail = $this->model_produk->getImageProductByProduct($id_product);
                foreach ($getImageDetail->result() as $imageDetail) {
                    $gambar_default_edit = $this->input->post('gambar_default_edit_' . $imageDetail->id);
                    if ($gambar_default_edit == 0) {
                        $gambar_detail_edit = $this->template->upload_picture_not_resize($path, $_POST['image_high_edit_' . $imageDetail->id], $_POST['image_tumb_edit_' . $imageDetail->id], $imageDetail->id);
                        $link_detail_edit = "web/images/product/" . $gambar_detail_edit;
                    } else {
                        $gambar_detail_edit = "";
                        $link_detail_edit = "";
                    }
                    if ($gambar_default_edit == 0) {
                        $insert_gambar = $this->db->set('product_img', $link_detail_edit)->where('id', $imageDetail->id)->update('product_img');
                    }
                }


                for ($i = 1; $i <= $total_gambar; $i++) {
                    $gambar_default = $this->input->post('gambar_default_' . $i);
                    if ($gambar_default == 0) {
                        $gambar_detail = $this->template->upload_picture_not_resize($path, $_POST['image_high_' . $i], $_POST['image_tumb_' . $i]);
                        $link_detail = "web/images/product/" . $gambar_detail;
                    } else {
                        $gambar_detail = "";
                        $link_detail = "";
                    }
                    if ($gambar_default == 0) {
                        $insert_gambar = $this->db->set('id_product', $id_product)->set('product_img', $link_detail)->insert('product_img');
                    }
                }
            }

            redirect(base_url("admin/produk/index"));
        }
    }

    public function tambah_gudang()
    {
        $i = $this->input->post('i');
        echo "
            <div class='form-group'>
                <label class='control-label col-lg-3'></label>
                <div class='col-lg-9'>
                    <div class='row'>
                        <div class='col-md-7'>
                            <input type='text' id='nama_gudang_" . $i . "' name='nama_gudang_" . $i . "' class='form-control' placeholder='Masukkan Nama Gudang ex : Jakarta'>
                        </div>

                        <div class='col-md-5'>
                            <input type='text' id='stock_gudang_" . $i . "' name='stock_gudang_" . $i . "' class='form-control' placeholder='Masukkan Stock'>
                        </div>
                    </div>
                </div>
            </div>
        ";
    }

    public function view_detail_image()
    {
        $id = $this->input->post('id');
        $getData = $this->model_produk->getImageProductByProduct($id);
        echo "
        <div class='panel-body'>
            <div class='row'>";
        if ($getData->num_rows() > 0) {
            foreach ($getData->result() as $data) {
                echo "
                <div class='col-md-4'>
                    <div class='content-group'>";
                //$rep_nama = str_replace("product/","product/resize/",$data->product_img);
                //$rep_nama = str_replace("product/","product/resize/",$data->product_img);
                echo "
                        <image src='" . base_url($data->product_img) . "' width='80%' height='80%'></image>
                    </div>
                </div>
                ";
            }
        } else {
            echo " <div class='col-md-4'>
                    <div class='content-group'>";
            //$rep_nama = str_replace("product/","product/resize/",$data->product_img);
            //$rep_nama = str_replace("product/","product/resize/",$data->product_img);
            echo "
                        <image src='" . base_url($data->product_img) . "web/images/no_img.png' width='80%' height='80%'></image>
                    </div>
                </div>";
        }
        echo "
            </div>
        </div>

        ";
    }

    public function manage_stok()
    {
        // $this->data['getUsers'] = $this->model_master->getUsers();
        // $this->data['getProducts'] = $this->model_produk->getProducts();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->template->rick_auto('stok/bg_index', $this->data);
    }

    public function log_adjusment()
    {
        // $this->data['getUsers'] = $this->model_master->getUsers();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getData'] = $this->model_produk->getLogAdjusment();
        $this->template->rick_auto('stok/bg_log_adjusment', $this->data);
    }

    public function log_mutasi()
    {
        // $this->data['getUsers'] = $this->model_master->getUsers();
        // $this->data['getProducts'] = $this->model_produk->getProducts();
        //$this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getData'] = $this->model_produk->getLogMutasiFilter();
        $this->template->rick_auto('stok/bg_log_mutasi', $this->data);
    }

    public function log_mutasi_export()
    {
        $jenis = $this->uri->segment(4);
        // $this->data['getUsers'] = $this->model_master->getUsers();
        // $this->data['getProducts'] = $this->model_produk->getProducts();
        //$this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getData'] = $this->model_produk->getLogMutasiFilter();
        if ($jenis != "pdf") {
            $this->load->view('admin/stok/bg_export_log_mutasi', $this->data);
        } else {
            $content = $this->load->view('admin/stok/bg_export_log_mutasi', $this->data, TRUE);
            $this->template->print2pdf('Print_PDF', $content);
        }
    }

    public function log_adjustment_export()
    {
        $jenis = $this->uri->segment(4);
        // $this->data['getUsers'] = $this->model_master->getUsers();
        // $this->data['getProducts'] = $this->model_produk->getProducts();
        //$this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getData'] = $this->model_produk->getLogAdjusment();
        if ($jenis != "pdf") {
            $this->load->view('admin/stok/bg_export_log_adjusment', $this->data);
        } else {
            $content = $this->load->view('admin/stok/bg_export_log_adjusment', $this->data, TRUE);
            $this->template->print2pdf('Print_PDF', $content);
        }
    }

    public function index_stok_()
    {
        // $this->data['getUsers'] = $this->model_master->getUsers();
        // $this->data['getProducts'] = $this->model_produk->getProducts();
        $this->data['getProducts'] = $this->model_produk->getProductss();
        $this->template->rick_auto('stok/bg_info_stok', $this->data);
    }

    public function index_stok()
    {
        // $this->data['getUsers'] = $this->model_master->getUsers();
        // $this->data['getProducts'] = $this->model_produk->getProducts();
        $this->data['getProducts'] = $this->model_produk->getProductss();
        $this->template->rick_auto('stok/bg_info_stok', $this->data);
    }

    public function filterPerusahaanMutasi()
    {
        $cmbPerusahaanFilter = $this->input->post('cmbPerusahaanFilter');
        $cmbGudangFrom = $this->input->post('cmbGudangFrom');
        $cmbGudangTo = $this->input->post('cmbGudangTo');
        $tanggalFrom = $this->input->post('tanggalFrom');
        $tanggalTo = $this->input->post('tanggalTo');
        if ($tanggalFrom == "1970-01-01" || $tanggalTo == "1970-01-01" || $tanggalTo == "1970-01-02") {
        } else {
            $_SESSION['rick_auto']['filter_start_date_lt'] = date("Y-m-d", strtotime("+0 day", strtotime($tanggalFrom)));
            $_SESSION['rick_auto']['filter_end_date_lt'] = date("Y-m-d", strtotime("+0 day", strtotime($tanggalTo)));
        }
        $_SESSION['rick_auto']['filter_gudangfrom_lt'] = $cmbGudangFrom;
        $_SESSION['rick_auto']['filter_gudangto_lt'] = $cmbGudangTo;
        $_SESSION['rick_auto']['filter_perusahaan_lt'] = $cmbPerusahaanFilter;
    }

    public function filterLogAdjustment()
    {
        $cmbPerusahaanFilter = $this->input->post('cmbPerusahaanFilter');
        $cmbGudangFrom = $this->input->post('cmbGudangFrom');
        $tanggalFrom = $this->input->post('tanggalFrom');
        $tanggalTo = $this->input->post('tanggalTo');
        if ($tanggalFrom == "1970-01-01" || $tanggalTo == "1970-01-01" || $tanggalTo == "1970-01-02") {
        } else {
            $_SESSION['rick_auto']['filter_start_date_la'] = date("Y-m-d", strtotime("+0 day", strtotime($tanggalFrom)));
            $_SESSION['rick_auto']['filter_end_date_la'] = date("Y-m-d", strtotime("+0 day", strtotime($tanggalTo)));
        }
        $_SESSION['rick_auto']['filter_gudangfrom_la'] = $cmbGudangFrom;
        $_SESSION['rick_auto']['filter_perusahaan_la'] = $cmbPerusahaanFilter;
    }

    public function actionMutasi()
    {
        $id = $this->input->post('id');
        $perusahaans = $this->input->post('perusahaan');

        echo "
        <!-- Masked inputs -->
        <div class='panel panel-flat'>
            <div class='panel-heading'>
                <h5 class='panel-title'>Proses Mutasi Stok</h5>
            </div>

            <div class='panel-body'>
                <div class='row'>
                    <div class='col-md-4'>
                        <div class='form-group'>
                        <label>Perusahaan : </label>
                        <select id='cmbPerusahaan_" . $id . "' name='cmbPerusahaan_" . $id . "' class='form-control' onchange=javascript:pilihPerusahaanMutasi(" . $id . ")>
                        <option selected disabled>Pilih Perusahaan</option>
                        ";
        $Perusahaan = $this->model_master->getPerusahaan();
        foreach ($Perusahaan->result() as $perusahaan) {
            if ($perusahaan->id == $perusahaans) {
                echo "
                                <option value='" . $perusahaan->id . "'>" . $perusahaan->name . "</option>
                                ";
            } else {
            }
        }
        echo "
                    </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-4'>
                        <div class='form-group'>
                        <label>Gudang : </label>
                        <select id='cmbGudang_" . $id . "' name='cmbGudang_" . $id . "' class='form-control'>
                        <option selected>Pilih Gudang</option>
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-2'>
                        <div class='form-group'>
                        <label>Stok Mutasi : </label>
                        <input type='text' id='stokTo_" . $id . "' name='stokTo_" . $id . "' class='form-control'>
                    </div>
                </div>
                    <div class='col-md-12'>
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Keterangan Perusahaan & Gudang</th>
                                    <th>Stok</th>
                                    <th>Tanggal Transaksi</th>
                            </thead>
                            <tbody>
                            ";
        $dataList = $this->model_master->getLogStokByProductPerusahaanGudang($id);
        $no = 0;
        foreach ($dataList->result() as $list) {
            $no++;
            echo "
                                <tr>
                                    <td>$no</td>
                                    <td>Dari <b>" . $list->nama_perusahaan_from . "</b> <b>" . $list->nama_gudang_from . "</b> dimutasi ke <b>" . $list->nama_perusahaan_to . "</b> <b>" . $list->nama_gudang_to . "</b> </td>
                                    <td>Dari <b>" . $list->from_stok . "</b> dimutasi <b>" . $list->to_stok . "</b></td>
                                    <td>" . date("d M Y H:i", strtotime("+0 day", strtotime($list->create_date))) . "</td>
                                </tr>";
        }
        echo "
                            </tbody>
                        </table>
                </div>
            </div>

        </div>
        <!-- /masked inputs -->
        ";
    }

    public function pilihPerusahaanMutasi()
    {
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $id = $this->input->post('id');

        $Data = $this->model_master->getGudangbyPerusahaan($cmbPerusahaan);
        foreach ($Data->result() as $data) {
            echo "
            <option value=" . $data->id_gudang . ">" . $data->nama_gudang . "</option>
            ";
        }
    }

    public function pilihPerusahaanOB()
    {
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');

        $Data = $this->model_master->getGudangbyPerusahaan($cmbPerusahaan);
        foreach ($Data->result() as $data) {
            echo "
            <option value=" . $data->id_gudang . ">" . $data->nama_gudang . "</option>
            ";
        }
    }

    public function pilihProduk()
    {
        $cmbProduk = $this->input->post('cmbProduk');
        $cmbPerusahaanFilter = $this->input->post('cmbPerusahaanFilter');
        $cmbGudangFrom = $this->input->post('cmbGudangFrom');
        $data = $this->model_master->getStokbyGudangbyProductPerusahaan($cmbProduk, $cmbPerusahaanFilter, $cmbGudangFrom)->row();
        $dataProduk = $this->model_produk->getProductsById($cmbProduk)->row();
        echo json_encode(array('jmlStok' => $data->jmlStok, 'satuan' => $dataProduk->nama_satuan));
    }

    public function pilihProdukOrder()
    {
        $cmbProduk = $this->input->post('cmbProduk');

        $data = $this->model_produk->getProductsById($cmbProduk)->row();

        echo $data->nama_satuan;
    }

    public function tambahProdukOrder()
    {
        $i = $this->input->post('total');
        $getProducts = $this->model_produk->getProducts();
        echo "
        <div class='col-md-12'>
            <!-- colmd2 untuk yng lama -->
            <div class='col-md-4' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Produk: </label>
                    <select id='cmbProduk_" . $i . "' name='cmbProduk_" . $i . "' class='form-control' onchange=javascript:pilihProdukOrder(" . $i . ")>
                        <option value='0' selected disabled>Pilih Produk</option>
                    </select>
                </div>
            </div>
            <div class='col-md-4' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Qty Order: </label>
                    <div class='input-group bootstrap-touchspin'>
                    <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurangQtyOrder(" . $i . ")>-
                    </button>
                    </span>
                    <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
                    </span>
                    <input type='text' id='addStok_" . $i . "' name='addStok_" . $i . "' value='1' class='touchspin-set-value form-control' style='display: block;'>
                    <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
                    </span>
                    <span class='input-group-btn'>
                    <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambahQtyOrder(" . $i . ")>+
                    </button>
                    </span>
                    </div>
                </div>
            </div>
            <div class='col-md-4' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Satuan: </label>
                    <input type='type' class='form-control' id='satuan_" . $i . "' name='satuan_" . $i . "' disabled>
                </div>
            </div>
        </div>  
        <script>
               $('select[id=cmbProduk_" . $i . "]').select2({
                   ajax: {
                       url: '" . base_url('admin/purchase/dataProduk') . "',
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
        ";
    }

    public function simpanPembuatanSO()
    {
        $st = $this->input->post('st');
        $noTransaksi = $this->input->post('noTransaksi');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudang = $this->input->post('cmbGudang');
        $tglTransaksi = $this->input->post('tglTransaksi');
        $jmlProduk = $this->input->post('jmlProduk');
        $pic = $this->input->post('pic');
        $cekData = $this->model_produk->getSOByNoTransaction($noTransaksi);
        if ($cekData->num_rows() > 0) {
            echo "2";
        } else {
            $insert_1 = $this->db->set('notransaction', $noTransaksi)->set('status', $st)->set('faktur_date', date("Y-m-d", strtotime("+0 day", strtotime($tglTransaksi))))->set('gudang_id', $cmbGudang)->set('perusahaan_id', $cmbPerusahaan)->set('pic', $pic)->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('stock_opname');
            $idRecent = $this->db->insert_id();
            if ($insert_1) {
                $getDataTemp = $this->model_produk->getSOTemps();
                foreach ($getDataTemp->result() as $dataTemp) {
                    $produkStok = $this->input->post('produkStok_' . $dataTemp->id);
                    $addStok = $this->input->post('addStok_' . $dataTemp->id);
                    $insert_2 = $this->db->set('so_id', $idRecent)->set('produk_id', $dataTemp->produk_id)->set('qtySO', $addStok)->set('qtyProduk', $produkStok)->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('stock_opname_detail');
                }
                echo "1";
            }
        }
    }

    public function simpanEditPembuatanSO()
    {
        $id = $this->input->post('id');
        $noTransaksi = $this->input->post('noTransaksi');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudang = $this->input->post('cmbGudang');
        $tglTransaksi = $this->input->post('tglTransaksi');
        $jmlProduk = $this->input->post('jmlProduk');
        $pic = $this->input->post('pic');
        $st = $this->input->post('st');
        $insert_1 = $this->db->set('notransaction', $noTransaksi)->set('faktur_date', date("Y-m-d", strtotime("+0 day", strtotime($tglTransaksi))))->set('gudang_id', $cmbGudang)->set('perusahaan_id', $cmbPerusahaan)->set('pic', $pic)->set('status', $st)->where('id', $id)->update('stock_opname');
        $idRecent = $id;
        if ($insert_1) {
            $getDataTemp = $this->model_produk->getSODetailBySO($id);
            foreach ($getDataTemp->result() as $dataTemp) {
                $addStok = $this->input->post('addStok_' . $dataTemp->id);
                $insert_2 = $this->db->set('qtySO', $addStok)->where('id', $dataTemp->id)->update('stock_opname_detail');
            }
            echo "1";
        }
    }


    public function simpanPembuatanOrder()
    {
        $noFakturPabrik = $this->input->post('noFakturPabrik');
        $namaPabrik = $this->input->post('namaPabrik');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudang = $this->input->post('cmbGudang');
        $tglFaktur = $this->input->post('tglFaktur');
        $tglSampaiGudang = $this->input->post('tglSampaiGudang');
        $jmlProduk = $this->input->post('jmlProduk');
        $cekData = $this->model_produk->getProdukBeliByNoTransaction($noFakturPabrik);
        if ($cekData->num_rows() > 0) {
            echo "2";
        } else {
            $insert_1 = $this->db->set('notransaction', $noFakturPabrik)->set('factory_name', $namaPabrik)->set('faktur_date', date("Y-m-d", strtotime("+0 day", strtotime($tglFaktur))))->set('warehouse_date', date("Y-m-d"))->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('produk_beli');
            $idRecent = $this->db->insert_id();
            if ($insert_1) {
                for ($i = 1; $i <= $jmlProduk; $i++) {
                    $cmbProduk = $this->input->post('cmbProduk_' . $i);
                    if ($cmbProduk > 0 || $cmbProduk != "") {
                        $addStok = $this->input->post('addStok_' . $i);
                        $insert_2 = $this->db->set('produk_beli_id', $idRecent)->set('produk_id', $cmbProduk)->set('qty', $addStok)->set('qty_receive',-1)->set('perusahaan_id', $cmbPerusahaan)->set('gudang_id', $cmbGudang)->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('produk_beli_detail');
                    } else {
                    }
                }
                echo "1";
            }
        }
    }

    public function simpanPembuatanOrderBarcode()
    {
        $st = $this->input->post('st');
        $noFakturPabrik = $this->input->post('noFakturPabrik');
        $namaPabrik = $this->input->post('namaPabrik');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudang = $this->input->post('cmbGudang');
        $tglFaktur = $this->input->post('tglFaktur');
        $tglSampaiGudang = $this->input->post('tglSampaiGudang');
        $jmlProduk = $this->input->post('jmlProduk');
        $cekData = $this->model_produk->getProdukBeliByNoTransaction($noFakturPabrik);
        if ($cekData->num_rows() > 0) {
            echo "2";
        } else {
            $insert_1 = $this->db->set('notransaction', $noFakturPabrik)
                ->set('factory_name', $namaPabrik)->set('status', $st)
                ->set('faktur_date', date("Y-m-d", strtotime("+0 day", strtotime($tglFaktur))))
                ->set('warehouse_date', date("Y-m-d"))->set('create_date', date("Y-m-d H:i:s"))
                ->set('create_user', $_SESSION['rick_auto']['username'])
                ->insert('produk_beli');

            $idRecent = $this->db->insert_id();

            if ($insert_1) {
                $getDataTemp = $this->model_produk->getProdukBeliTemps();
                foreach ($getDataTemp->result() as $dataTemp) {
                    $addStok = $this->input->post('addStok_' . $dataTemp->id);
                    $insert_2 = $this->db->set('produk_beli_id', $idRecent)
                        ->set('produk_id', $dataTemp->produk_id)->set('qty', $addStok)
                        ->set('qty_receive',-1)
                        ->set('perusahaan_id', $cmbPerusahaan)->set('gudang_id', $cmbGudang)
                        ->set('create_date', date("Y-m-d H:i:s"))
                        ->set('create_user', $_SESSION['rick_auto']['username'])
                        ->insert('produk_beli_detail');
                }
                echo "1";
            }
        }
    }

    public function hapus_temp_order()
    {
        $delete = $this->db->query("truncate table produk_beli_detail_temp");
        if ($delete) {
            echo "1";
        }
    }

    public function hapus_temp_SO()
    {
        $delete = $this->db->query("truncate table stock_opname_detail_temp");
        if ($delete) {
            echo "1";
        }
    }



    public function simpanPembuatanOrderEdit_()
    {
        $id = $this->input->post('idBeli');
        $namaPabrik = $this->input->post('namaPabrik');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudang = $this->input->post('cmbGudang');
        $tglFaktur = $this->input->post('tglFaktur');
        $tglSampaiGudang = $this->input->post('tglSampaiGudang');
        $jmlProduk = $this->input->post('jmlProduk');
        $update_1 = $this->db->set('factory_name', $namaPabrik)
            ->set('faktur_date', date("Y-m-d", strtotime("+0 day", strtotime($tglFaktur))))
            ->set('warehouse_date', date("Y-m-d", strtotime("+0 day", strtotime($tglSampaiGudang))))
            ->where('id', $id)
            ->update('produk_beli');
        if ($update_1) {
            $getDataDetail = $this->model_produk->getProdukBeliDetailByIdProdukBeli($id);
            foreach ($getDataDetail->result() as $detail) {
                $cmbProdukEdit = $this->input->post('cmbProdukEdit_' . $detail->id);
                $addStokEdit = $this->input->post('addStokEdit_' . $detail->id);
                //$update_2 = $this->db->set('produk_id',$cmbProdukEdit)->set('qty',$addStokEdit)->set('perusahaan_id',$cmbPerusahaan)->set('gudang_id',$cmbGudang)->where('id',$detail->id)->update('produk_beli_detail');
                $update_2 = $this->db->set('produk_id', $cmbProdukEdit)
                    ->set('qty_receive', $addStokEdit)
                    ->set('perusahaan_id', $cmbPerusahaan)
                    ->set('gudang_id', $cmbGudang)
                    ->where('id', $detail->id)
                    ->update('produk_beli_detail');
            }

            for ($i = 1; $i <= $jmlProduk; $i++) {
                $cmbProduk = $this->input->post('cmbProduk_' . $i);
                if ($cmbProduk > 0 || $cmbProduk != "") {
                    $addStok = $this->input->post('addStok_' . $i);
                    $insert_2 = $this->db->set('produk_beli_id', $id)
                        ->set('produk_id', $cmbProduk)
                        ->set('qty', $addStok)
                        ->set('perusahaan_id', $cmbPerusahaan)
                        ->set('gudang_id', $cmbGudang)
                        ->set('create_date', date("Y-m-d H:i:s"))
                        ->set('create_user', $_SESSION['rick_auto']['username'])
                        ->insert('produk_beli_detail');
                } else {
                }
            }
            echo "1";
        }
    }

    public function simpanPembuatanOrderEdit()
    {
        $id = $this->input->post('idBeli');
        $namaPabrik = $this->input->post('namaPabrik');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudang = $this->input->post('cmbGudang');
        $tglFaktur = $this->input->post('tglFaktur');
        $tglSampaiGudang = $this->input->post('tglSampaiGudang');
        $jmlProduk = $this->input->post('jmlProduk');
        $st = $this->input->post('st');
        $update_1 = $this->db->set('factory_name', $namaPabrik)->set('faktur_date', date("Y-m-d", strtotime("+0 day", strtotime($tglFaktur))))->set('status', $st)->set('warehouse_date', date("Y-m-d", strtotime("+0 day", strtotime($tglSampaiGudang))))->where('id', $id)->update('produk_beli');
        if ($update_1) {
            $getDataDetail = $this->model_produk->getProdukBeliDetailByIdProdukBeli($id);
            foreach ($getDataDetail->result() as $detail) {
                //$cmbProdukEdit = $this->input->post('cmbProdukEdit_'.$detail->id);
                $addStokEdit = $this->input->post('addStokEdit_' . $detail->id);
                $addStok_kali = $this->input->post('addStok_kali_' . $detail->id);

                $update_2 = $this->db->set('qty', $addStok_kali)->set('qty_receive', $addStokEdit)->set('perusahaan_id', $cmbPerusahaan)->set('gudang_id', $cmbGudang)->where('id', $detail->id)->update('produk_beli_detail');
            }

            for ($i = 1; $i <= $jmlProduk; $i++) {
                $cmbProduk = $this->input->post('cmbProduk_' . $i);
                if ($cmbProduk > 0 || $cmbProduk != "") {
                    $addStok = $this->input->post('addStok_' . $i);
                    $insert_2 = $this->db->set('produk_beli_id', $id)->set('produk_id', $cmbProduk)->set('qty', $addStok)->set('perusahaan_id', $cmbPerusahaan)->set('gudang_id', $cmbGudang)->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('produk_beli_detail');
                } else {
                }
            }
            echo "1";
        }
    }

    public function approveOrder()
    {
        $id = $this->input->post('idBeli');
        $namaPabrik = $this->input->post('namaPabrik');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudang = $this->input->post('cmbGudang');
        $tglFaktur = $this->input->post('tglFaktur');
        $tglSampaiGudang = $this->input->post('tglSampaiGudang');
        $jmlProduk = $this->input->post('jmlProduk');
        $txtNote = $this->input->post('txtNote');

        $getData = $this->model_produk->getProdukBeliById($id)->row();
        $getDataDetail = $this->model_produk->getProdukBeliDetailByIdProdukBeli($id);

        foreach ($getDataDetail->result() as $detail) {

            $addStokEdit = $this->input->post('addStokEdit_' . $detail->id);

            $update_2 = $this->db->set('produk_id', $detail->produk_id)
                ->set('qty_receive', $addStokEdit)
                ->set('perusahaan_id', $cmbPerusahaan)
                ->set('gudang_id', $cmbGudang)
                ->where('id', $detail->id)
                ->update('produk_beli_detail');

            $getIdPerusahaanGudang = $this->model_master->getPerusahaanGudangByGudang($detail->perusahaan_id, $detail->gudang_id)->row();
            $getDataByPerusahaanGudang = $this->model_master->getStokPerusahaanGudangByProduk1($detail->produk_id, $getIdPerusahaanGudang->id);

            if ($getDataByPerusahaanGudang->num_rows() > 0) {
                $stokDbApp = $getDataByPerusahaanGudang->row()->stok + $addStokEdit;

                $insert = $this->db->set('stok', $stokDbApp)
                    ->where('id', $getDataByPerusahaanGudang->row()->id)
                    ->update('product_perusahaan_gudang');
            } else {
                $stokDbApp = $addStokEdit;

                $insert = $this->db->set('stok', $stokDbApp)
                    ->set('product_id', $detail->produk_id)
                    ->set('perusahaan_gudang_id', $getIdPerusahaanGudang->id)
                    ->insert('product_perusahaan_gudang');
            }

            $insert_opname_stok_bm = $this->db->set('product_id', $detail->produk_id)
                ->set('gudang_id', $cmbGudang)->set('perusahaan_id', $cmbPerusahaan)
                ->set('stock_input', $addStokEdit)->set('note', 'Order Barang Masuk')
                ->set('keterangan', 'Order Barang Masuk')->set('produk_beli_id', $id)
                ->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])
                ->insert('report_stok_bm_bl');
        }


        for ($i = 1; $i <= $jmlProduk; $i++) {
            $cmbProduk = $this->input->post('cmbProduk_' . $i);
            if ($cmbProduk > 0 || $cmbProduk != "") {
                $addStok = $this->input->post('addStok_' . $i);
                $insert_2 = $this->db->set('produk_beli_id', $id)
                    ->set('produk_id', $cmbProduk)->set('qty', $addStok)
                    ->set('perusahaan_id', $cmbPerusahaan)
                    ->set('gudang_id', $cmbGudang)
                    ->set('create_date', date("Y-m-d H:i:s"))
                    ->set('create_user', $_SESSION['rick_auto']['username'])
                    ->insert('produk_beli_detail');
                $insert_opname_stok_bm = $this->db->set('product_id', $cmbProduk)
                    ->set('gudang_id', $cmbGudang)->set('perusahaan_id', $cmbPerusahaan)
                    ->set('stock_input', $addStokEdit)->set('note', 'Order Barang Masuk')
                    ->set('keterangan', 'Order Barang Masuk')
                    ->set('produk_beli_id', $id)
                    ->set('create_date', date("Y-m-d H:i:s"))
                    ->set('create_user', $_SESSION['rick_auto']['username'])
                    ->insert('report_stok_bm_bl');
            } else {
            }
        }

        if ($insert) {
            $update = $this->db->set('flag_proses', 1)
                ->set('note', $txtNote)
                ->set('approve_user', $_SESSION['rick_auto']['username'])
                ->set('warehouse_date', $tglSampaiGudang)
                ->where('id', $id)
                ->update('produk_beli');
            if ($update) {
                echo "1";
            }
        }
    }
    public function approveOrder_2021_03_31()
    {
        $id = $this->input->post('idBeli');
        $namaPabrik = $this->input->post('namaPabrik');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudang = $this->input->post('cmbGudang');
        $tglFaktur = $this->input->post('tglFaktur');
        $tglSampaiGudang = $this->input->post('tglSampaiGudang');
        $jmlProduk = $this->input->post('jmlProduk');
        $txtNote = $this->input->post('txtNote');

        $getData = $this->model_produk->getProdukBeliById($id)->row();
        $getDataDetail = $this->model_produk->getProdukBeliDetailByIdProdukBeli($id);
        foreach ($getDataDetail->result() as $detail) {
            //$cmbProdukEdit = $this->input->post('cmbProdukEdit_'.$detail->id);
            $addStokEdit = $this->input->post('addStokEdit_' . $detail->id);
            $update_2 = $this->db->set('produk_id', $detail->produk_id)->set('qty_receive', $addStokEdit)->set('perusahaan_id', $cmbPerusahaan)->set('gudang_id', $cmbGudang)->where('id', $detail->id)->update('produk_beli_detail');
            $getIdPerusahaanGudang = $this->model_master->getPerusahaanGudangByGudang($detail->perusahaan_id, $detail->gudang_id)->row();
            $getDataByPerusahaanGudang = $this->model_master->getStokPerusahaanGudangByProduk1($detail->produk_id, $getIdPerusahaanGudang->id);
            if ($getDataByPerusahaanGudang->num_rows() > 0) {
                $stokDbApp = $getDataByPerusahaanGudang->row()->stok + $addStokEdit;
                $insert = $this->db->set('stok', $stokDbApp)->where('id', $getDataByPerusahaanGudang->row()->id)->update('product_perusahaan_gudang');
                //  echo "ada""<br>".$getDataByPerusahaanGudang->row()->id;
            } else {
                $stokDbApp = $addStokEdit;
                $insert = $this->db->set('stok', $stokDbApp)->set('product_id', $detail->produk_id)->set('perusahaan_gudang_id', $getIdPerusahaanGudang->id)->insert('product_perusahaan_gudang');
                // echo "tidak ada ada""<br>".$getIdPerusahaanGudang->id;
            }

            $insert_opname_stok_bm = $this->db->set('product_id', $detail->produk_id)->set('gudang_id', $cmbGudang)->set('perusahaan_id', $cmbPerusahaan)->set('stock_input', $addStokEdit)->set('note', 'Order Barang Masuk')->set('keterangan', 'Order Barang Masuk')->set('produk_beli_id', $id)->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
        }


        for ($i = 1; $i <= $jmlProduk; $i++) {
            $cmbProduk = $this->input->post('cmbProduk_' . $i);
            if ($cmbProduk > 0 || $cmbProduk != "") {
                $addStok = $this->input->post('addStok_' . $i);
                $insert_2 = $this->db->set('produk_beli_id', $id)->set('produk_id', $cmbProduk)->set('qty', $addStok)->set('perusahaan_id', $cmbPerusahaan)->set('gudang_id', $cmbGudang)->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('produk_beli_detail');
                $insert_opname_stok_bm = $this->db->set('product_id', $cmbProdukEdit)->set('gudang_id', $cmbGudang)->set('perusahaan_id', $cmbPerusahaan)->set('stock_input', $addStokEdit)->set('note', 'Order Barang Masuk')->set('keterangan', 'Order Barang Masuk')->set('produk_beli_id', $id)->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
            } else {
            }
        }

        if ($insert) {
            $update = $this->db->set('flag_proses', 1)->set('note', $txtNote)->set('approve_user', $_SESSION['rick_auto']['username'])->set('warehouse_date', $tglSampaiGudang)->where('id', $id)->update('produk_beli');
            if ($update) {
                echo "1";
            }
        }
    }

    public function approveOrderSo()
    {
        $id = $this->input->post('id');
        $getData = $this->model_produk->getSOById($id)->row();
        $getDataDetail = $this->model_produk->getSODetailBySO($id);
        foreach ($getDataDetail->result() as $data) {
            $addStok  = $this->input->post('addStok_' . $data->id);
            $getStokGudang = $this->model_master->getStokbyGudangbyProductPerusahaan($data->produk_id, $getData->perusahaan_id, $getData->gudang_id);
            $getPerusahaanGudang = $this->model_master->getPerusahaanGudangByGudang($getData->perusahaan_id, $getData->gudang_id)->row();
            if ($addStok < 0){
                echo "";
            }else{
                if ($getStokGudang->num_rows() > 0) {
                    $deleteStok = $this->db->where('product_id', $data->produk_id)->where('perusahaan_gudang_id', $getPerusahaanGudang->id)->delete('product_perusahaan_gudang');
                    if ($deleteStok) {
                        $insert = $this->db->set('product_id', $data->produk_id)->set('perusahaan_gudang_id', $getPerusahaanGudang->id)->set('stok', $addStok)->insert('product_perusahaan_gudang');
                    }
                } else {
                    $insert = $this->db->set('product_id', $data->produk_id)->set('perusahaan_gudang_id', $getPerusahaanGudang->id)->set('stok', $addStok)->insert('product_perusahaan_gudang');
                }
            }
        }
        if ($insert) {
            $updateSo = $this->db->set('flag_proses', 1)->set('approve_date', date("Y-m-d H:i:s"))->set('approve_user', $_SESSION['rick_auto']['username'])->where('id', $id)->update('stock_opname');
            echo "1";
        }
    }

    public function print_data()
    {
        $id = base64_decode($this->uri->segment(4));
        $this->data['getData'] = $this->model_produk->getProdukBeliById($id)->row();
        $this->data['getDataDetail'] = $this->model_produk->getProdukBeliDetailByIdProdukBeli($id);
        $this->load->view('admin/produk_order/bg_print', $this->data);
    }

    public function saveMutasiStok_()
    {
        $id = $this->input->post('id');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudang = $this->input->post('cmbGudang');
        $dataPpg = $this->model_master->getProductPerusahaanGudangById($id)->row();
        $dataPG = $this->model_master->getPerusahaanGudangById($dataPpg->perusahaan_gudang_id)->row();

        $insertLog = $this->db->set('product_perusahaan_gudang_id', $id)->set('from_perusahaan_id', $dataPG->perusahaan_id)->set('from_gudang_id', $dataPG->gudang_id)->set('to_perusahaan_id', $cmbPerusahaan)->set('to_gudang_id', $cmbGudang)->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('log_stok');
        if ($insertLog) {
            $insertMutasi = $this->db->set('perusahaan_id', $cmbPerusahaan)->set('gudang_id', $cmbGudang)->insert('perusahaan_gudang');
            echo "1";
        }
    }

    public function saveMutasiStok()
    {
        $id = $this->input->post('id');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudang = $this->input->post('cmbGudang');
        $stok = $this->input->post('stok');
        $stokTo = $this->input->post('stokTo');
        $dataPpg = $this->model_master->getProductPerusahaanGudangById($id)->row();
        $dataPG = $this->model_master->getPerusahaanGudangById($dataPpg->perusahaan_gudang_id)->row();
        if ($stokTo > $stok) {
            echo "2";
        } else {
            $insertLog = $this->db->set('product_perusahaan_gudang_id', $id)->set('from_perusahaan_id', $dataPG->perusahaan_id)->set('from_gudang_id', $dataPG->gudang_id)->set('to_perusahaan_id', $cmbPerusahaan)->set('to_gudang_id', $cmbGudang)->set('from_stok', $dataPpg->stok)->set('to_stok', $stokTo)->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('log_stok');
            if ($insertLog) {
                $dataPerusahaanGudang = $this->model_master->getPerusahaanGudangByGudang($cmbPerusahaan, $cmbGudang)->row();
                $insertPg = $this->db->set('product_id', $dataPpg->product_id)->set('perusahaan_gudang_id', $dataPerusahaanGudang->id)->set('stok', $stokTo)->insert('product_perusahaan_gudang');
                if ($insertPg) {
                    $sisa_stok = $dataPpg->stok - $stokTo;
                    $updatePpg = $this->db->set('stok', $sisa_stok)->where('id', $id)->update('product_perusahaan_gudang');
                    if ($updatePpg) {
                        echo "1";
                    }
                }
            }
            //$insertMutasi = $this->db->set('perusahaan_id',$cmbPerusahaan)->set('gudang_id',$cmbGudang)->insert('perusahaan_gudang');

        }
    }


    public function view_produk_stok_()
    {
        unset($_SESSION['rick_auto']['search']);
        $id = $this->input->post('id');
        $dataIdentitas = $this->model_master->getPerusahaanGudangById($id)->row();
        $getData = $this->model_master->getProductPerusahaanGudangByPerusahaanGudang($id);
        $getProduk = $this->model_produk->getProductsLimit();

        echo "
            <h5 align='center'>Pengaturan Stok Produk di " . strtoupper($dataIdentitas->nama_perusahaan) . " " . strtoupper($dataIdentitas->nama_gudang) . "</h5>
            <div class='form-group'>
                <label class='control-label col-lg-2'></label>
                <div class='col-lg-10'>
                    <div class='row'>
                        <div class='col-md-8'>
                            <div class='form-group'>
                                <input class='form-control input-xlg' type='text'  id='txtSearch' name='txtSearch' placeholder='Cari Produk dari Nama / No. Barcode Produk' onchange=javascript:search_produk(" . $id . ")>
                            </div>
                        </div>

                        <div class='col-md-4'>
                            <div class='form-group'>
                                <a href='#' class='btn btn-primary btn-icon input-xlg'><i class='icon-search4' onclick=javascript:search_produk(" . $id . ")></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <hr>
            <div id='tempatAjaxData'>
                <form id='formAdd'>
                    <table class='table table-bordered table-striped'>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Stock Qty</th>
                                <th>Tambah Stock</th>
                            </tr>
                        </thead>
                        <tbody id='tableAjax'>
                            ";
        $no = 0;

        foreach ($getProduk->result() as $produk) {
            $no++;
            $getStok = $this->model_master->getStokPerusahaanGudangByProduk($produk->id, $id);
            if ($getStok->num_rows() > 0) {
                $stok = $getStok->row()->stok;
            } else {
                $stok = 0;
            }
            echo "
                            <tr>
                                <td>$no</td>
                                <td>" . $produk->product_code . "</td>
                                <td>" . $produk->product_name . "</td>
                                <td>" . $stok . "</td>
                                <td>
                                <input type='hidden' id='jmlStok_" . $produk->id . "' name='jmlStok_" . $produk->id . "' value=" . $stok . ">
                                <div class='input-group bootstrap-touchspin'>
                                <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurang(" . $produk->id . ")>-
                                </button>
                                </span>
                                <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
                                </span>
                                <input type='text' id='addStok_" . $produk->id . "' name='addStok_" . $produk->id . "' value='0' class='touchspin-set-value form-control' style='display: block;'>
                                <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
                                </span>
                                <span class='input-group-btn'>
                                <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambah(" . $produk->id . ")>+
                                </button>
                                </span>
                                </div>
                                </td>
                            </tr>";
        }
        echo "
                        </tbody>
                    </table>
                    <br>
                    <div align='right'>
                    <button class='btn btn-primary' type='button'  onclick=javascript:save_stok(" . $id . ")>Simpan Data
                                </button>
                    </div>
                </form>
            </div>
            <br>
            <hr>
        ";
    }

    public function view_produk_stok()
    {
        unset($_SESSION['rick_auto']['search']);
        $id = $this->input->post('id');
        $dataIdentitas = $this->model_master->getPerusahaanGudangById($id)->row();
        $getData = $this->model_master->getProductPerusahaanGudangByPerusahaanGudang($id);
        $getProduk = $this->model_produk->getProductsLimit();

        echo "
            <h5 align='center'>Pengaturan Stok Produk di " . strtoupper($dataIdentitas->nama_perusahaan) . " " . strtoupper($dataIdentitas->nama_gudang) . "</h5>
            <div class='form-group'>
                <label class='control-label col-lg-2'></label>
                <div class='col-lg-10'>
                    <div class='row'>
                        <div class='col-md-8'>
                            <div class='form-group'>
                                <input class='form-control input-xlg' type='text'  id='txtSearch' name='txtSearch' placeholder='Cari Produk dari Nama / No. Barcode Produk' onchange=javascript:search_produk(" . $id . ")>
                            </div>
                        </div>

                        <div class='col-md-4'>
                            <div class='form-group'>
                                <a href='#' class='btn btn-primary btn-icon input-xlg'><i class='icon-search4' onclick=javascript:search_produk(" . $id . ")></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <hr>
            <div id='tempatAjaxData'>
                <form id='formAdd'>
                    <table class='table table-bordered table-striped'>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Stock Qty</th>
                                <th>Tambah Stock</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody id='tableAjax'>

                        </tbody>
                    </table>
                    <br>
                    <div align='right'>
                    <button class='btn btn-primary' type='button'  onclick=javascript:save_stok(" . $id . ")>Simpan Data
                                </button>
                    </div>
                </form>
            </div>
            <br>
            <hr>
        ";
    }

    public function view_detail_stok()
    {
        $id = $this->input->post('id');

        $data_stok = $this->model_master->getStokProduct($id);
        echo "<table class='table datatable-basic' width='100%'>
        <tr>
            <th class='text-center'><h5>PERUSAHAAN</h5></th>
            <th class='text-center'><h5>INFORMASI STOK PER GUDANG</h5></th>
        </tr>
        ";
        foreach ($data_stok->result() as $stok) {
            echo "
            <tr>
                <td><b>" . $stok->nama_perusahaan . "</b></td>
                <td>";
            $data_stok2 = $this->model_master->getStokProductGudangByPerusahaanByProduk($id, $stok->id_perusahaan);
            echo "<table class='table datatable-basic' width='100%'>";
            foreach ($data_stok2->result() as $stok2) {
                echo "
                    <tr>
                        <td><b>" . $stok2->nama_gudang . "</b></td>
                        <td><b>" . $stok2->jumlah_stok . "</b></td>
                    </tr>";
            }
            echo "</table>
                </td>
            </tr>
            ";
        }
        echo "</table>";
    }

    public function search_produk()
    {
        $txtSearch = $this->input->post('txtSearch');

        $_SESSION['rick_auto']['search'] = $txtSearch;
        $this->data['id'] = $this->input->post('id');
        $this->data['getProduk'] = $this->model_produk->getProductsLimit();
        $this->load->view('admin/stok/bg_index_ajax', $this->data);
    }

    public function save_stok()
    {
        $id = $this->input->post('id');
        $getProduk = $this->model_produk->getProductsLimit();
        $getPP = $this->model_master->getPerusahaanGudangById($id)->row();
        foreach($getProduk->result() as $produk){
            $getStok = $this->model_master->getStokPerusahaanGudangByProduk($produk->id,$id);
            $addStok = $this->input->post('addStok_'.$produk->id);
            // print_r($addStok);
            // print_r($addStok);
            $note    = $this->input->post('note_'.$produk->id);
            if($addStok == null || $addStok == "null" || $getStok->row()->jmlStok + $addStok < 0){
                echo "";
            }else{
                if($getStok->row()->jmlStok > null){
                    // print_r($getStok->row()->jmlStok );
                    // $stok = $addStok;
                    $stok = $getStok->row()->jmlStok + $addStok;
                    // print_r($stok);
                    $qUer = $this->db->where([
                    'product_id' => $produk->id,
                    'perusahaan_gudang_id' => $id])->update('product_perusahaan_gudang', ['stok' => $stok]);
                    // $qUer = $this->db->set('stok',$addStok)->set('product_id',$produk->id)->set('perusahaan_gudang_id',$id)->insert('product_perusahaan_gudang');
                    $insert_opname_stok = $this->db->set('product_id',$produk->id)
                    ->set('gudang_id',$getPP->gudang_id)->set('perusahaan_id',$getPP->perusahaan_id)
                    ->set('qty_product',$getStok->row()->jmlStok)->set('stock_add',$addStok)->set('note',$note)
                    ->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('opname_stock');
                    if($addStok >= 0){
                    $insert_opname_stok_bm = $this->db->set('product_id',$produk->id)->set('gudang_id',$getPP->gudang_id)
                    ->set('perusahaan_id',$getPP->perusahaan_id)->set('stock_input',$addStok)->set('note','Adjusment')
                    ->set('keterangan','Adjusment Masuk ('.$note.')')->set('create_date',date("Y-m-d H:i:s"))
                    ->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                    }else{
                    $insert_opname_stok_bl = $this->db->set('product_id',$produk->id)->set('gudang_id',$getPP->gudang_id)->set('perusahaan_id',$getPP->perusahaan_id)->set('stock_input',$addStok)->set('note','Adjusment')->set('keterangan','Adjusment Keluar ('.$note.')')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                    }
                }else{
                    // echo "Sempak";
                    $stok = $addStok;
                    // print_r($stok);
                    // $stok = $getStok->row()->jmlStok + $addStok;
                    $qUer = $this->db->set('stok',$stok)
                    ->set('product_id',$produk->id)->set('perusahaan_gudang_id',$id)->insert('product_perusahaan_gudang');
                    // $qUer = $this->db->set('product_id',$produk->id)->set('perusahaan_gudang_id',$id)->set('stok',$addStok)->insert('product_perusahaan_gudang');
                    // print_r($stok);
                    $insert_opname_stok = $this->db->set('product_id',$produk->id)->set('gudang_id',$getPP->gudang_id)->set('perusahaan_id',$getPP->perusahaan_id)->set('qty_product',0)->set('stock_add',$stok)->set('note',$note)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('opname_stock');
                    $insert_opname_stok_bm = $this->db->set('product_id',$produk->id)->set('gudang_id',$getPP->gudang_id)->set('perusahaan_id',$getPP->perusahaan_id)->set('stock_input',$stok)->set('note','Adjusment')->set('keterangan','Adjusment Masuk ('.$note.')')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                }
                echo "1";
            }
        }
    }

    public function save_stok_2021_03_31()
    {
        $id = $this->input->post('id');
        $getProduk = $this->model_produk->getProductsLimit();
        $getPP = $this->model_master->getPerusahaanGudangById($id)->row();
        foreach ($getProduk->result() as $produk) {
            $getStok = $this->model_master->getStokPerusahaanGudangByProduk($produk->id, $id);
            $addStok = $this->input->post('addStok_' . $produk->id);
            $note    = $this->input->post('note_' . $produk->id);
            //echo "kosong";
            if ($addStok == 0 || $addStok == "") {
            } else {
                if ($getStok->num_rows() > 0) {
                    //echo "kosong";
                    $stok = $getStok->row()->jmlStok + $addStok;
                    //$qUer = $this->db->set('stok',$stok)->where('id',$getStok->row()->id)->update('product_perusahaan_gudangs');
                    $qUer = $this->db->set('stok', $addStok)->set('product_id', $produk->id)->set('perusahaan_gudang_id', $id)->insert('product_perusahaan_gudang');
                    $insert_opname_stok = $this->db->set('product_id', $produk->id)->set('gudang_id', $getPP->gudang_id)->set('perusahaan_id', $getPP->perusahaan_id)->set('qty_product', $getStok->row()->jmlStok)->set('stock_add', $addStok)->set('note', $note)->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('opname_stock');
                    if ($addStok >= 0) {
                        $insert_opname_stok_bm = $this->db->set('product_id', $produk->id)->set('gudang_id', $getPP->gudang_id)->set('perusahaan_id', $getPP->perusahaan_id)->set('stock_input', $addStok)->set('note', 'Adjusment')->set('keterangan', 'Adjusment Masuk (' . $note . ')')->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                    } else {
                        $insert_opname_stok_bl = $this->db->set('product_id', $produk->id)->set('gudang_id', $getPP->gudang_id)->set('perusahaan_id', $getPP->perusahaan_id)->set('stock_input', $addStok)->set('note', 'Adjusment')->set('keterangan', 'Adjusment Keluar (' . $note . ')')->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                    }
                } else {
                    $stok = $addStok;
                    $qUer = $this->db->set('stok', $stok)->set('product_id', $produk->id)->set('perusahaan_gudang_id', $id)->insert('product_perusahaan_gudang');
                    $insert_opname_stok = $this->db->set('product_id', $produk->id)->set('gudang_id', $getPP->gudang_id)->set('perusahaan_id', $getPP->perusahaan_id)->set('qty_product', 0)->set('stock_add', $addStok)->set('note', $note)->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('opname_stock');
                    $insert_opname_stok_bm = $this->db->set('product_id', $produk->id)->set('gudang_id', $getPP->gudang_id)->set('perusahaan_id', $getPP->perusahaan_id)->set('stock_input', $addStok)->set('note', 'Adjusment')->set('keterangan', 'Adjusment Masuk (' . $note . ')')->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                }
            }
        }

        echo "1";
    }

    public function prosesGetPerusahaanFrom()
    {
        $i = $this->input->post('i');
        $cmbProduk = $this->input->post('cmbProduk');

        $getData = $this->model_master->getStokProduct($cmbProduk);
        echo "
            <div class='form-group'>
                    <label>Dari Perusahaan : </label>
                    <select class='form-control' id='cmbPerusahaanFrom_" . $i . "' name='cmbPerusahaanFrom_" . $i . "' onchange=javascript:getGudangFrom(" . $i . ")>
                    <option value='0' disabled selected>Pilih Perusahaan</option>";
        foreach ($getData->result() as $data) {
            echo "
                        <option value=" . $data->id_perusahaan . ">" . $data->nama_perusahaan . "</option>
                        ";
        }
        echo "
                    </select>
            </div>
            ";
    }

    public function getGudangFrom()
    {
        $i = $this->input->post('i');
        $cmbProduk = $this->input->post('cmbProduk');
        $cmbPerusahaanFrom = $this->input->post('cmbPerusahaanFrom');
        $addStok = $this->input->post('addStok');
        $getData = $this->model_master->getCekStokGudangbyProductPerusahaan($cmbProduk, $cmbPerusahaanFrom, $addStok);
        echo "
            <div class='form-group'>
                    <label>Dari Gudang : </label>
                    <select class='form-control' id='cmbGudangFrom_" . $i . "' name='cmbGudangFrom_" . $i . "' onchange=javascript:pilihGudangFrom(" . $i . ")>
                    <option value='0' disabled selected>Pilih Gudang</option>";
        foreach ($getData->result() as $data) {
            echo "
                        <option value=" . $data->id_gudang . ">" . $data->nama_gudang . "</option>
                        ";
        }
        echo "
                    </select>
            </div>
            ";
    }

    public function pilihGudangFrom()
    {
        $i = $this->input->post('i');
        $cmbGudangFrom = $this->input->post('cmbGudangFrom');
        $getGudang = $this->model_master->getGudangByNotId($cmbGudangFrom);

        echo "
            <div class='form-group'>
                <label>Tujuan Gudang : </label>
                <div id='tempatGudang_" . $i . "'>
                <select class='form-control' id='cmbGudangTo_" . $i . "' name='cmbGudangTo_" . $i . "'>
                    <option value='0' selected>Pilih Gudang</option>
                    ";
        foreach ($getGudang->result() as $gudang) {
            echo "
                            <option value='" . $gudang->id . "'>" . $gudang->name . "</option>
                        ";
        }
        echo "
                </select>
                </div>
            </div>
        ";
    }

    public function addProdukMutasi()
    {
        $getGudang = $this->model_master->getGudang();
        $i = $this->input->post('total');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudangFrom = $this->input->post('cmbGudangFrom');
        echo "
        <div class='col-md-12' id='tmpAjaxMutasi_" . $i . "'>
            <!-- colmd2 untuk yng lama -->
            <div class='col-md-6'>
                <div class='form-group'>
                    <label>Produk: </label>
                    <select id='cmbProduk_" . $i . "' name='cmbProduk_" . $i . "' class='form-control' onchange=javascript:pilihProduk(" . $i . ")>
                        <option value='0' selected disabled>Pilih Produk</option>
                    </select>
                </div>
            </div>
            <div class='col-md-3'>
                <div class='form-group'>
                    <label>Qty Mutasi: </label>
                    <div class='input-group bootstrap-touchspin'>
                    <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurangProsesMutasi(" . $i . ")>-
                    </button>
                    </span>
                    <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
                    </span>
                    <input type='text' id='addStok_" . $i . "' name='addStok_" . $i . "' value='1' class='touchspin-set-value form-control' style='display: block;' onkeyup=javascript:ketikProsesMutasi(" . $i . ")>
                    <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
                    </span>
                    <span class='input-group-btn'>
                    <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambahProsesMutasi(" . $i . ")>+
                    </button>
                    </span>
                    </div>
                </div>
                <input type='hidden' class='form-control' id='txtJmlStok_" . $i . "' name='txtJmlStok_" . $i . "'></input>
            </div>
            <div class='col-md-2'>
                <div class='form-group'>
                    <label>Satuan: </label>
                    <input type='text' id='txtSatuan_" . $i . "' name='txtSatuan_" . $i . "' class='form-control' readonly>
                </div>
            </div>
            <div class='col-md-1'>
                <div class='form-group'>
                    <label>Aksi : </label>
                    <a href='#' onclick='javascript:hapus_mutasi(" . $i . ")' class='btn btn-danger btn-icon'><i class='icon-trash'></i></a>
                </div>
            </div>
            </div>
            <input type='hidden' id='fl_active_" . $i . "' name='fl_active_" . $i . "' value='1'>
            <script>
                   $('select[id=cmbProduk_" . $i . "]').select2({
                       ajax: {
                           url: '" . base_url('admin/purchase/dataProdukPerusahaan') . "',
                          dataType: 'json',
                          delay: 250,
                          data: function(params) {
                            //alert(params);
                            return {
                              search: params.term,
                              cmbPerusahaan: " . $cmbPerusahaan . ",
                              cmbGudangFrom: " . $cmbGudangFrom . "
                            }
                          },
                          processResults: function (data) {
                          var results = [];
                          $.each(data, function(index, item){
                            results.push({
                                id: item.product_id,
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
        </div>      
  
        ";
    }

    public function addProdukMutasi_()
    {
        $getGudang = $this->model_master->getGudang();
        $i = $this->input->post('total');
        echo "
        <div class='col-md-12'>
            <!-- colmd2 untuk yng lama -->
            <div class='col-md-3'>
                <div class='form-group'>
                    <label>Produk: </label>
                    <select id='cmbProduk_" . $i . "' name='cmbProduk_" . $i . "' class='form-control' onchange=javascript:prosesGetPerusahaanFrom(" . $i . ")>
                        <option value='0' selected disabled>Pilih Produk</option>
                    </select>
                </div>
            </div>
            <div class='col-md-3' id='tmpCmbPerusahaan_" . $i . "'>
                <div class='form-group'>
                    <label>Dari Perusahaan : </label>
                    <select class='form-control' id='cmbPerusahaanFrom_" . $i . "' name='cmbPerusahaanFrom_" . $i . "'>
                    <option value='0' disabled selected>Pilih Perusahaan</option>
                    </select>
                </div>
            </div>
            <div class='col-md-2'>
                <div class='form-group'>
                    <label>Qty Mutasi: </label>
                    <div class='input-group bootstrap-touchspin'>
                    <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurangProsesMutasi(" . $i . ")>-
                    </button>
                    </span>
                    <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
                    </span>
                    <input type='text' id='addStok_" . $i . "' name='addStok_" . $i . "' value='1' class='touchspin-set-value form-control' style='display: block;' onkeyup=javascript:ketikProsesMutasi(" . $i . ")>
                    <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
                    </span>
                    <span class='input-group-btn'>
                    <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambahProsesMutasi(" . $i . ")>+
                    </button>
                    </span>
                    </div>
                </div>
            </div>
            <div class='col-md-2' id='tmpCmbGudang_" . $i . "'>
                <div class='form-group'>
                    <label>Dari Gudang : </label>
                    <div id='tempatGudang_" . $i . "'>
                    <select class='form-control' id='cmbGudangFrom_" . $i . "' name='cmbGudangFrom_" . $i . "'>
                        <option value='0' selected>Pilih Gudang</option>
                    </select>
                    </div>
                </div>
            </div>
            <div class='col-md-2'>
                <div class='form-group'>
                    <label>Tujuan Gudang : </label>
                    <div id='tempatGudang_" . $i . "'>
                    <select class='form-control' id='cmbGudangTo_" . $i . "' name='cmbGudangTo_" . $i . "'>
                        <option value='0' selected>Pilih Gudang</option>
                        ";
        foreach ($getGudang->result() as $gudang) {
            echo "
                                <option value='" . $gudang->id . "'>" . $gudang->name . "</option>
                            ";
        }
        echo "
                    </select>
                    </div>
                </div>
            </div>
        </div>   

        ";
    }

    public function simpanPembuatanMutasi()
    {
        $jmlProduk = $this->input->post('jmlProduk');
        $cmbPerusahaanFrom = $this->input->post('cmbPerusahaan');
        $cmbGudangFrom = $this->input->post('cmbGudangFrom');
        $cmbGudangTo = $this->input->post('cmbGudangTo');
        $txtNoT = $this->input->post('txtNoT');
        $txtTglMutasi = date("Y-m-d H:i:s",strtotime($this->input->post('txtTglMutasi').date("H:i:s")));
        // print_r($txtTglMutasi);
        for($i=1; $i<=$jmlProduk; $i++){
            $cmbProduk = $this->input->post('cmbProduk_'.$i);
            
            $addStok = $this->input->post('addStok_'.$i);
            
            if($cmbProduk == 0 || $cmbProduk == NULL || $cmbProduk == "NULL" || $cmbProduk == "0" || $cmbGudangFrom == 0 || $cmbGudangFrom == NULL || $cmbGudangFrom == "NULL" || $cmbGudangFrom == "0"){
                $insert = "";
                echo "2";
            }else{
                $getPerusahaanGudang = $this->model_master->getPerusahaanGudangByGudang($cmbPerusahaanFrom,$cmbGudangFrom)->row();
                $getPerusahaanGudangTo = $this->model_master->getPerusahaanGudangByGudang($cmbPerusahaanFrom,$cmbGudangTo)->row();
                $getDataStok = $this->model_master->getStokPerusahaanGudangByProduk($cmbProduk,$getPerusahaanGudang->id);
                $getPenguranganStok = $getDataStok->row()->stok - $addStok;

                $getProductPerusahaanGudang = $this->model_master->getStokPerusahaanGudangByProduk($cmbProduk,$getPerusahaanGudang->id)->row();
                $insertLog1 = $this->db->set('no_transaction',$txtNoT)
                ->set('product_perusahaan_gudang_id',$getProductPerusahaanGudang->id)
                ->set('from_perusahaan_id',$cmbPerusahaanFrom)
                ->set('from_gudang_id',$cmbGudangFrom)
                ->set('to_perusahaan_id',$cmbPerusahaanFrom)
                ->set('to_gudang_id',$cmbGudangTo)
                ->set('from_stok',$getDataStok->row()->jmlStok)
                ->set('to_stok',$addStok)
                ->set('create_date',$txtTglMutasi)
                ->set('create_user',$_SESSION['rick_auto']['username'])->insert('log_stok');
                if($insertLog1){
                    // $update = $this->db->set('stok',$getPenguranganStok)->where('id',$getDataStok->row()->id)->update('product_perusahaan_gudang');
                    $getGudangExistTo = $this->db->query('SELECT * FROM product_perusahaan_gudang WHERE product_id="'.$cmbProduk.'" AND perusahaan_gudang_id="'.$getPerusahaanGudangTo->id.'"')->row();
                    //$getGudangExistTo = $this->db->query('SELECT * FROM product_perusahaan_gudang WHERE product_id="6307" AND perusahaan_gudang_id="13"')->row();
                    $getGudangFrom = $this->db->query('SELECT * FROM product_perusahaan_gudang WHERE product_id="'.$cmbProduk.'" AND perusahaan_gudang_id="'.$getPerusahaanGudang->id.'"')->row();
                    //print_r(count($getGudangExistTo));
                    if ($getGudangFrom->stok == 0){
                        $status = 0;
                    } else{
                    if (count($getGudangExistTo) == 0){
                        $insert = $this->db->set('stok', $addStok)
                        ->set('product_id', $cmbProduk)
                        ->set('perusahaan_gudang_id', $getPerusahaanGudangTo->id)
                        ->insert('product_perusahaan_gudang');
                    } else {
                        $pengurangan_stok = $getGudangExistTo->stok + $addStok;
                        $update = $this->db->set('stok',$pengurangan_stok)
                        ->where('product_id', $cmbProduk)
                        ->where('perusahaan_gudang_id', $getPerusahaanGudangTo->id)
                        ->update('product_perusahaan_gudang');
                    }  
                    $this->db->set('stok',($getGudangFrom->stok - $addStok))
                    ->where('product_id', $cmbProduk)
                    ->where('perusahaan_gudang_id', $getPerusahaanGudang->id)
                    ->update('product_perusahaan_gudang');

                    if($update || $insert){
                        //$insert = $this->db->set('stok',$addStok)->set('product_id',$cmbProduk)->set('perusahaan_gudang_id',$getPerusahaanGudangTo->id)->insert('product_perusahaan_gudang');
                        $insert_opname_stok_bm = $this->db->set('product_id',$cmbProduk)->set('gudang_id',$cmbGudangTo)->set('perusahaan_id',$cmbPerusahaanFrom)->set('stock_input',$addStok)->set('note','Mutasi')->set('keterangan','Mutasi')->set('create_date',$txtTglMutasi)->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                        $insert_opname_stok_bl = $this->db->set('product_id',$cmbProduk)->set('gudang_id',$cmbGudangFrom)->set('perusahaan_id',$cmbPerusahaanFrom)->set('stock_input',"-".$addStok)->set('note','Mutasi')->set('keterangan','Mutasi')->set('create_date',$txtTglMutasi)->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                    }
                    
                    $status = 1;
                }
                }

            
            }
        }
        // echo "1";
        echo $status;
    }

    public function hapus_so_temp()
    {
        $id = $this->input->post('id');
        $delete = $this->db->where('id', $id)->delete('stock_opname_detail_temp');
        if ($delete) {
            $this->data_ajax_so_order();
        }
    }

    public function hapus_so_detail()
    {
        $id = $this->input->post('id');
        $id_so = $this->input->post('id_so');

        $delete = $this->db->where('id', $id)->delete('stock_opname_detail');
        if ($delete) {
            $this->data_ajax_so_order_edit($id_so);
        }
    }

    // public function scan_barcode_so(){
    //     $kode_produk = $this->input->post('kode_produk');
    //     $cmbGudang = $this->input->post('cmbGudang');
    //     $cmbPerusahaan = $this->input->post('cmbPerusahaan');
    //     $jenis = $this->input->post('jenis');
    //     //echo $kode_produk;
    //     // if($jenis == "barcode"){
    //     //     $getProdukByCodes = $this->model_produk->getProdukByBarcode($kode_produk);
    //     // }
    //         //$getProdukByCodes = $this->model_produk->getProductByBarcode($kode_produk);
    //      $getProdukByCodes = $this->model_produk->getProdukByBarcode($kode_produk);
    //     if($getProdukByCodes->num_rows() > 0){

    //         $getProdukByCode = $this->model_produk->getProdukByBarcode($kode_produk)->row();
    //         $getStokGudang = $this->model_master->getStokbyGudangbyProductPerusahaan($getProdukByCode->product_id,$cmbPerusahaan,$cmbGudang)->row();
    //         $data = $this->model_produk->getProductsById($getProdukByCode->product_id)->row();
    //         $cekData = $this->model_produk->getSOTempByProduk($getProdukByCode->product_id);
    //         if($cekData->num_rows() > 0){
    //             $cekDataa = $this->model_produk->getSOTempByProduk($getProdukByCode->product_id);
    //             if($jenis == "barcode"){
    //             $qtyOrder = $cekDataa->row()->qtySO + $getProdukByCodes->row()->isi;
    //             }else{
    //             $qtyOrder = $cekDataa->row()->qtySO + 1;    
    //             }
    //             $insert = $this->db->set('qtySO',$qtyOrder)->where('produk_id',$getProdukByCode->product_id)->update('stock_opname_detail_temp');
    //         }else{
    //             if($jenis == "barcode"){
    //             $insert = $this->db->set('produk_id',$getProdukByCode->product_id)->set('qtySO',$getProdukByCodes->row()->isi)->set('qtyProduk',$getStokGudang->jmlStok)->insert('stock_opname_detail_temp');
    //             }else{
    //             $insert = $this->db->set('produk_id',$getProdukByCode->product_id)->set('qtySO',1)->set('qtyProduk',$getStokGudang->jmlStok)->insert('stock_opname_detail_temp');
    //             }
    //         }
    //         $this->data_ajax_so_order();
    //     }else{
    //         echo "2";
    //     }
    // }
    public function scan_barcode_so()
    {
        $kode_produk = $this->input->post('kode_produk');
        $cmbGudang = $this->input->post('cmbGudang');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $jenis = $this->input->post('jenis');

        if ($jenis == "barcode") {

            $getProdukByCode = $this->model_produk->getProdukByBarcode($kode_produk)->row();

            $data = $this->model_produk->getProductsById($getProdukByCode->product_id)->row();
            $cekData = $this->model_produk->getSOTempByProduk($getProdukByCode->product_id);
            $kodeaja = $getProdukByCode->product_id;
            $test = "hello";
        } else {
            $getProdukByCode = $this->model_produk->getProdukByID($kode_produk)->row();
            $data = $this->model_produk->getProductsById($kode_produk)->row();
            $cekData = $this->model_produk->getSOTempByProduk($kode_produk);
            $kodeaja = $kode_produk;
            $test = "hello word";
        }
        if (count($getProdukByCode) > 0) {

            //$getProdukByCode = $this->model_produk->getProdukByBarcode($kode_produk)->row();
            $getStokGudang = $this->model_master->getStokbyGudangbyProductPerusahaan($kodeaja, $cmbPerusahaan, $cmbGudang)->row();

            if ($cekData->num_rows() > 0) {
                $cekDataa = $this->model_produk->getSODetailBySOandProduk($id, $kodeaja);
                if ($jenis == "barcode") {
                    $qtyOrder = $cekDataa->row()->qtySO + $getProdukByCode->row()->isi;
                } else {
                    $qtyOrder = $cekDataa->row()->qtySO + 1;
                }
                $insert = $this->db->set('qtySO', $qtyOrder)->where('produk_id', $kodeaja)->update('stock_opname_detail_temp');
            } else {
                if ($jenis == "barcode") {
                    $insert = $this->db->set('produk_id', $kodeaja)->set('qtySO', $getProdukByCode->row()->isi)->set('qtyProduk', $getStokGudang->jmlStok)->insert('stock_opname_detail_temp');
                } else {
                    $insert = $this->db->set('produk_id', $kodeaja)->set('qtySO', 1)->set('qtyProduk', $getStokGudang->jmlStok)->insert('stock_opname_detail_temp');
                }
            }
            $this->data_ajax_so_order();
        } else {
            echo "2";
        }
    }

    // public function scan_barcode_so_edit(){
    //     $id = $this->input->post('id');
    //     $kode_produk = $this->input->post('kode_produk');
    //     $cmbGudang = $this->input->post('cmbGudang');
    //     $cmbPerusahaan = $this->input->post('cmbPerusahaan');
    //     $jenis = $this->input->post('jenis');

    //     //echo $kode_produk;
    //     $getProdukByCodes = $this->model_produk->getProdukByBarcode($kode_produk);
    //     if($getProdukByCodes->num_rows() > 0){

    //         $getProdukByCode = $this->model_produk->getProdukByBarcode($kode_produk)->row();
    //         $getStokGudang = $this->model_master->getStokbyGudangbyProductPerusahaan($getProdukByCode->product_id,$cmbPerusahaan,$cmbGudang)->row();
    //         $data = $this->model_produk->getProductsById($getProdukByCode->product_id)->row();
    //         $cekData = $this->model_produk->getSODetailBySOandProduk($id,$getProdukByCode->product_id);
    //         if($cekData->num_rows() > 0){
    //             $cekDataa = $this->model_produk->getSODetailBySOandProduk($id,$getProdukByCode->product_id);
    //             if($jenis == "barcode"){
    //             $qtyOrder = $cekDataa->row()->qtySO + $getProdukByCodes->row()->isi;
    //             }else{
    //             $qtyOrder = $cekDataa->row()->qtySO + 1;    
    //             }
    //             $insert = $this->db->set('qtySO',$qtyOrder)->where('produk_id',$getProdukByCode->product_id)->where('so_id',$id)->update('stock_opname_detail');
    //         }else{

    //             if($jenis == "barcode"){
    //             $insert = $this->db->set('so_id',$id)->set('produk_id',$getProdukByCode->product_id)->set('qtySO',$getProdukByCodes->row()->isi)->set('qtyProduk',$getStokGudang->jmlStok)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('stock_opname_detail');
    //             }else{
    //             $insert = $this->db->set('so_id',$id)->set('produk_id',$getProdukByCode->product_id)->set('qtySO',1)->set('qtyProduk',$getStokGudang->jmlStok)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('stock_opname_detail');
    //             }
    //         }
    //         $this->data_ajax_so_order_edit($id);
    //     }else{
    //         echo "2";
    //     }
    // }
    public function scan_barcode_so_edit()
    {
        $id = $this->input->post('id');
        $kode_produk = $this->input->post('kode_produk');
        $cmbGudang = $this->input->post('cmbGudang');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $jenis = $this->input->post('jenis');


        //echo $kode_produk;
        if ($jenis == "barcode") {

            $getProdukByCode = $this->model_produk->getProdukByBarcode($kode_produk)->row();
            $data = $this->model_produk->getProductsById($getProdukByCode->product_id)->row();
            $cekData = $this->model_produk->getSODetailBySOandProduk($id, $getProdukByCode->product_id);

            $kodeaja = $getProdukByCode->product_id;
            $test = "hello";
        } else {
            $getProdukByCode = $this->model_produk->getProdukByID($kode_produk)->row();
            $data = $this->model_produk->getProductsById($kode_produk)->row();
            $cekData = $this->model_produk->getSODetailBySOandProduk($id, $kode_produk);
            $kodeaja = $kode_produk;
            $test = "hello word";
        }


        if (count($getProdukByCode) > 0) {


            $getStokGudang = $this->model_master->getStokbyGudangbyProductPerusahaan($kodeaja, $cmbPerusahaan, $cmbGudang)->row();
            if ($cekData->num_rows() > 0) {
                $cekDataa = $this->model_produk->getSODetailBySOandProduk($id, $kodeaja);
                if ($jenis == "barcode") {
                    $qtyOrder = $cekDataa->row()->qtySO + $getProdukByCode->row()->isi;
                } else {
                    $qtyOrder = $cekDataa->row()->qtySO + 1;
                }
                $insert = $this->db->set('qtySO', $qtyOrder)->where('produk_id', $kodeaja)->where('so_id', $id)->update('stock_opname_detail');
            } else {

                if ($jenis == "barcode") {
                    $insert = $this->db->set('so_id', $id)->set('produk_id', $kodeaja)->set('qtySO', $getProdukByCode->row()->isi)->set('qtyProduk', $getStokGudang->jmlStok)->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('stock_opname_detail');
                } else {
                    $insert = $this->db->set('so_id', $id)->set('produk_id', $kodeaja)->set('qtySO', 1)->set('qtyProduk', $getStokGudang->jmlStok)->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('stock_opname_detail');
                }
            }
            $this->data_ajax_so_order_edit($id);
        } else {
            echo "2";
        }
    }

    public function hapus_order_barcode()
    {
        $id = $this->input->post('id');
        $delete = $this->db->where('id', $id)->delete('produk_beli_detail_temp');
        $this->data_ajax_order();
    }

    public function hapus_order_detail()
    {
        $id = $this->input->post('id');
        $id_order = $this->input->post('id_order');
        $delete = $this->db->where('id', $id)->delete('produk_beli_detail');
        $this->data_ajax_order_edit($id_order);
    }

    public function ubahQtyOrderEdit()
    {
        $id = $this->input->post('id');
        $addStokEdit = $this->input->post('addStokEdit');
        $addStok_kali = $this->input->post('addStok_kali');
        $update = $this->db->set('qty', $addStok_kali)->set('qty_receive', $addStokEdit)->where('id', $id)->update('produk_beli_detail');
    }

    public function scan_barcode_order__()
    {
        $kode_produk = $this->input->post('kode_produk');
        $jenis = $this->input->post('jenis');
        //echo $kode_produk;
        $getProdukByCodes = $this->model_produk->getProductByBarcode($kode_produk);
        if ($getProdukByCodes->num_rows() > 0) {

            $getProdukByCode = $this->model_produk->getProductByBarcode($kode_produk)->row();
            $data = $this->model_produk->getProductsById($getProdukByCode->id)->row();
            $cekData = $this->model_produk->getProdukBeliTempByProduk($getProdukByCode->id);
            if ($cekData->num_rows() > 0) {
                $cekDataa = $this->model_produk->getProdukBeliTempByProduk($getProdukByCode->id);
                $qtyOrder = $cekDataa->row()->qty + 1;
                $insert = $this->db->set('qty', $qtyOrder)->where('produk_id', $getProdukByCode->id)->update('produk_beli_detail_temp');
            } else {
                $insert = $this->db->set('produk_id', $getProdukByCode->id)->set('qty', 1)->set('satuan_id', $getProdukByCode->satuan_id)->insert('produk_beli_detail_temp');
            }
            $this->data_ajax_order();
        } else {
            echo "2";
        }
    }

    // public function scan_barcode_order(){
    //     $kode_produk = $this->input->post('kode_produk');
    //     $jenis = $this->input->post('jenis');
    //     //echo $kode_produk;
    //     $getProdukByCodes = $this->model_produk->getProdukByBarcode($kode_produk);
    //     if($getProdukByCodes->num_rows() > 0){

    //         $getProdukByCode = $this->model_produk->getProdukByBarcode($kode_produk)->row();
    //         $data = $this->model_produk->getProductsById($getProdukByCode->product_id)->row();
    //         $cekData = $this->model_produk->getProdukBeliTempByProduk($getProdukByCode->product_id);
    //         if($cekData->num_rows() > 0){
    //             $cekDataa = $this->model_produk->getProdukBeliTempByProduk($getProdukByCode->product_id);
    //              if($jenis == "barcode"){
    //             $qtyOrder = $cekDataa->row()->qty + $getProdukByCodes->row()->isi;
    //             }else{
    //             $qtyOrder = $cekDataa->row()->qty + 1;    
    //             }
    //             $insert = $this->db->set('qty',$qtyOrder)->where('produk_id',$getProdukByCode->product_id)->update('produk_beli_detail_temp');
    //         }else{
    //             if($jenis == "barcode"){
    //             $insert = $this->db->set('produk_id',$getProdukByCode->product_id)->set('qty',$getProdukByCodes->row()->isi)->insert('produk_beli_detail_temp');
    //             }else{
    //             $insert = $this->db->set('produk_id',$getProdukByCode->product_id)->set('qty',1)->insert('produk_beli_detail_temp');
    //             }

    //         }
    //         $this->data_ajax_order();
    //     }else{
    //         echo "2";

    //     }
    // }

    public function scan_barcode_order()
    {
        $kode_produk = $this->input->post('kode_produk');
        $jenis = $this->input->post('jenis');
        //echo $kode_produk;

        if ($jenis == "barcode") {

            $getProdukByCode = $this->model_produk->getProdukByBarcode($kode_produk)->row();
            $data = $this->model_produk->getProductsById($getProdukByCode->product_id)->row();
            $cekData = $this->model_produk->getProdukBeliTempByProduk($getProdukByCode->product_id);
            $kodeaja = $getProdukByCode->product_id;
            $test = "hello";
        } else {
            $getProdukByCode = $this->model_produk->getProdukByID($kode_produk)->row();
            $data = $this->model_produk->getProductsById($kode_produk)->row();
            $cekData = $this->model_produk->getProdukBeliTempByProduk($kode_produk);
            $kodeaja = $kode_produk;
            $test = "hello word";
        }
        if (count($getProdukByCode) > 0) {
            if ($cekData->num_rows() > 0) {
                $cekDataa = $this->model_produk->getProdukBeliTempByProduk($kodeaja);
                if ($jenis == "barcode") {
                    $qtyOrder = $cekDataa->row()->qty + $getProdukByCode->isi;
                } else {
                    $qtyOrder = $cekDataa->row()->qty + 1;
                }
                $insert = $this->db->set('qty', $qtyOrder)->where('produk_id', $kodeaja)->update('produk_beli_detail_temp');
            } else {
                if ($jenis == "barcode") {
                    $insert = $this->db->set('produk_id', $kodeaja)->set('qty', $getProdukByCode->isi)->insert('produk_beli_detail_temp');
                } else {
                    $insert = $this->db->set('produk_id', $kodeaja)->set('qty', 1)->insert('produk_beli_detail_temp');
                }
            }
            $this->data_ajax_order();
        } else {
            // echo "2";
            echo '2';
        }
    }
    // public function scan_barcode_order_edit(){
    //     $id = $this->input->post('id');
    //     $cmbPerusahaan = $this->input->post('cmbPerusahaan');
    //     $cmbGudang = $this->input->post('cmbGudang');
    //     $kode_produk = $this->input->post('kode_produk');
    //     //echo $kode_produk;
    //     $jenis = $this->input->post('jenis');
    //     $getProdukByCodes = $this->model_produk->getProdukByBarcode($kode_produk);
    //     if($getProdukByCodes->num_rows() > 0){

    //         $getProdukByCode = $this->model_produk->getProdukByBarcode($kode_produk)->row();
    //         $data = $this->model_produk->getProductsById($getProdukByCode->product_id)->row();
    //         $cekData = $this->model_produk->getProdukBeliDetailByIdProdukBeliAndProduk($id,$getProdukByCode->product_id);
    //         if($cekData->num_rows() > 0){
    //             $cekDataa = $this->model_produk->getProdukBeliDetailByIdProdukBeliAndProduk($id,$getProdukByCode->product_id);
    //             if($jenis == "barcode"){
    //             $qtyOrder = $cekDataa->row()->qty_receive + $getProdukByCodes->row()->isi;
    //             }else{
    //             $qtyOrder = $cekDataa->row()->qty_receive + 1;    
    //             }
    //             //$qtyOrder = $cekDataa->row()->qty_receive + 1;
    //             $insert = $this->db->set('qty_receive',$qtyOrder)->where('produk_id',$getProdukByCode->product_id)->where('id',$cekDataa->row()->id)->update('produk_beli_detail');
    //         }else{

    //             if($jenis == "barcode"){
    //             $insert = $this->db->set('produk_beli_id',$id)->set('produk_id',$getProdukByCode->product_id)->set('qty',$getProdukByCodes->row()->isi)->set('qty_receive',0)->set('perusahaan_id',$cmbPerusahaan)->set('gudang_id',$cmbGudang)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('produk_beli_detail');
    //             }else{
    //             $insert = $this->db->set('produk_beli_id',$id)->set('produk_id',$getProdukByCode->product_id)->set('qty',$getProdukByCodes->row()->isi)->set('qty_receive',0)->set('perusahaan_id',$cmbPerusahaan)->set('gudang_id',$cmbGudang)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('produk_beli_detail');
    //             }
    //         }
    //         $this->data_ajax_order_edit($id);
    //     }else{
    //         echo "2";
    //     }
    // }
    public function scan_barcode_order_edit()
    {
        $id = $this->input->post('id');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudang = $this->input->post('cmbGudang');
        $kode_produk = $this->input->post('kode_produk');
        //echo $kode_produk;
        $jenis = $this->input->post('jenis');

        if ($jenis == "barcode") {

            $getProdukByCode = $this->model_produk->getProdukByBarcode($kode_produk)->row();
            $data = $this->model_produk->getProductsById($getProdukByCode->product_id)->row();
            // $cekData = $this->model_produk->getProdukBeliTempByProduk($getProdukByCode->product_id);
            $cekData = $this->model_produk->getProdukBeliDetailByIdProdukBeliAndProduk($id, $getProdukByCode->product_id);

            $kodeaja = $getProdukByCode->product_id;
            $test = "hello";
        } else {
            $getProdukByCode = $this->model_produk->getProdukByID($kode_produk)->row();
            $data = $this->model_produk->getProductsById($kode_produk)->row();
            // $cekData = $this->model_produk->getProdukBeliTempByProduk($kode_produk);
            $cekData = $this->model_produk->getProdukBeliDetailByIdProdukBeliAndProduk($id, $kode_produk);
            $kodeaja = $kode_produk;
            $test = "hello word";
        }


        if (count($getProdukByCode) > 0) {

            // $getProdukByCode = $this->model_produk->getProdukByBarcode($kode_produk)->row();
            // $data = $this->model_produk->getProductsById($getProdukByCode->product_id)->row();

            if ($cekData->num_rows() > 0) {
                $cekDataa = $this->model_produk->getProdukBeliDetailByIdProdukBeliAndProduk($id, $kodeaja);
                if ($jenis == "barcode") {
                    $qtyOrder = $cekDataa->row()->qty_receive + $getProdukByCode->isi;
                } else {
                    $qtyOrder = $cekDataa->row()->qty_receive + 1;
                }
                //$qtyOrder = $cekDataa->row()->qty_receive + 1;
                $insert = $this->db->set('qty_receive', $qtyOrder)->where('produk_id', $getProdukByCode->product_id)->where('id', $cekDataa->row()->id)->update('produk_beli_detail');
            } else {

                if ($jenis == "barcode") {
                    $insert = $this->db->set('produk_beli_id', $id)->set('produk_id', $kodeaja)->set('qty', $getProdukByCode->isi)->set('qty_receive', 0)->set('perusahaan_id', $cmbPerusahaan)->set('gudang_id', $cmbGudang)->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('produk_beli_detail');
                } else {
                    $insert = $this->db->set('produk_beli_id', $id)->set('produk_id', $kodeaja)->set('qty', $getProdukByCode->isi)->set('qty_receive', 0)->set('perusahaan_id', $cmbPerusahaan)->set('gudang_id', $cmbGudang)->set('create_date', date("Y-m-d H:i:s"))->set('create_user', $_SESSION['rick_auto']['username'])->insert('produk_beli_detail');
                }
            }
            $this->data_ajax_order_edit($id);
        } else {
            echo "2";
        }
    }

    public function scan_barcode_order_edit_()
    {
        $id = $this->input->post('id');
        $kode_produk = $this->input->post('kode_produk');
        //echo $kode_produk;
        $getProdukByCode = $this->model_produk->getProductByBarcode($kode_produk)->row();
        $getProdukByCodes = $this->model_produk->getProdukBeliDetailByIdProdukBeliAndProduk($id, $getProdukByCode->id);
        // if($getProdukByCodes->num_rows() > 0){
        //     echo json_encode(array('message'=>'sukses','idPurchase'=>$getProdukByCodes->row()->id));
        //     //echo $getProdukByCodes->row()->id;
        //     //$this->data_ajax_order();
        // }else{
        //     echo json_encode(array('message'=>'gagal','idPurchase'=>0));
        // }
        echo $getProdukByCodes->row()->id;
    }



    public function data_ajax_order()
    {

        $this->data['getDataDetail'] = $this->model_produk->getProdukBeliTemp();
        $this->data['getProducts'] = $this->model_produk->getProducts();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->data['getMembers'] = $this->model_master->getAllMembers();
        $this->data['getExpedisi'] = $this->model_master->getExpedisi();
        $this->load->view('admin/produk_order_barcode/bg_data_add', $this->data);
    }

    public function data_ajax_order_edit($id)
    {
        $this->data['getData'] = $this->model_produk->getProdukBeliById($id)->row();
        $this->data['getDataDetail'] = $this->model_produk->getProdukBeliDetailByIdProdukBeli($id);
        $this->data['getProducts'] = $this->model_produk->getProducts();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->data['getMembers'] = $this->model_master->getAllMembers();
        $this->data['getExpedisi'] = $this->model_master->getExpedisi();
        $this->load->view('admin/produk_order_barcode/bg_data', $this->data);
    }

    public function data_ajax_so_order()
    {
        $this->data['getDataDetail'] = $this->model_produk->getSOTemp();
        $this->data['getProducts'] = $this->model_produk->getProducts();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->data['getMembers'] = $this->model_master->getAllMembers();
        $this->data['getExpedisi'] = $this->model_master->getExpedisi();
        $this->load->view('admin/so/bg_data', $this->data);
    }

    public function data_ajax_so_order_edit($id)
    {
        //$id = base64_decode($this->uri->segment(4));
        $this->data['getDataDetail'] = $this->model_produk->getSODetailBySO($id);
        $this->data['getProducts'] = $this->model_produk->getProducts();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->data['getMembers'] = $this->model_master->getAllMembers();
        $this->data['getExpedisi'] = $this->model_master->getExpedisi();
        $this->load->view('admin/so/bg_data_edit', $this->data);
    }

    public function tambahQtyOrder()
    {
        $id = $this->input->post('id');
        $jumlah = $this->input->post('jumlah');
        $table = $this->input->post('table');

        if ($table == "so") {
            $update = $this->db->set('qtySO', $jumlah)->where('id', $id)->update('stock_opname_detail_temp');
        } elseif ($table == "so_edit") {
            $id_so = $this->input->post('id_so');
            $update = $this->db->set('qtySO', $jumlah)->where('id', $id)->update('stock_opname_detail');
        } else {
            //$update = $this->db->set('qty',$jumlah)->where('produk_id',$id)->update('produk_beli_detail_temp');
            $update = $this->db->set('qty', $jumlah)->where('id', $id)->update('produk_beli_detail_temp');
        }
    }
}
