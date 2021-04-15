<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
/*
* CONTROLLER INDEX WEBSITE
* This controler for screen index
*
* Log Activity : ~ Create your log if you change this controller ~
* 1. Create 20 Mei 2019 By Devanda Andrevianto, Create All Function, Create controller
*/
class Purchase extends CI_Controller {
    var $data = array('scjav'=>'assets/jController/admin/CtrlPurchase.js');
    function __construct(){
        parent::__construct();
        $this->load->model('admin/model_master');
        $this->load->model('admin/model_produk');
        $this->load->model('admin/model_purchase');
        $this->load->model('admin/model_invoice');
        
        // $this->lang->load('admin', '');
        if(empty($_SESSION['rick_auto']) || empty($_SESSION['rick_auto']['id'])){
            redirect('admin/index/signin/');
            return;
        }
    }
    
    // fungsi untuk mengecek apakah user sudah login
    public function index(){
        $insert = $this->db->set('token','Sedang Login')->where('id',$_SESSION['rick_auto']['id'])->update('users');
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->db->query("delete from transaction_purchase_temporary_process where 1=1");
        $this->data['getPurchase'] = $this->model_purchase->getPurchase();
        $this->template->rick_auto('purchase/bg_index',$this->data);

    }

    public function index_admin(){
        redirect('admin/purchase/index');
        // $this->data['getPurchase'] = $this->model_purchase->getPurchase();
        // $this->template->rick_auto('purchase/index_admin',$this->data);

    }


    public function po_add(){
        $this->data['getProducts'] = $this->model_produk->getProducts();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->data['getMembers'] = $this->model_master->getAllMembers();
        $this->data['getExpedisi'] = $this->model_master->getExpedisi();
        $this->data['getViaExpedisi'] = $this->model_master->getViaExpedisi();
        $this->template->rick_auto('purchase/bg_add',$this->data);
    }

    public function req_index(){
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getSales'] = $this->model_master->getSales();
        if($this->uri->segment(4) == ""){
            $this->data['getPurchase'] = $this->model_purchase->getReqPurchase(0);
        }else{
            $this->data['getPurchase'] = $this->model_purchase->getReqPurchaseCancelled();
        }
        $this->template->rick_auto('req_purchase/bg_index',$this->data);
    }

    public function req_bo_index(){
        $this->data['getSales'] = $this->model_master->getSales();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getPurchase'] = $this->model_purchase->getReqPurchase(1);
        $this->template->rick_auto('req_purchase/bg_index',$this->data);
    }

    public function req_show_detail(){
        $id = base64_decode($this->uri->segment(4));
        $this->data['getPurchase'] = $this->model_purchase->getReqPurchaseByID($id)->row();
        $this->data['getPerusahaans'] = $this->model_master->getPerusahaan();
        $this->template->rick_auto('req_purchase/bg_detail_purchase',$this->data);
    }

    public function show_detail(){
        $id = $this->input->post('id');
        $this->data['getPurchase'] = $this->model_purchase->getPurchaseByID($id)->row();
        $this->data['getPurchaseDetail'] = $this->model_purchase->getTotalPembayarannyaByPurchase($id)->row();
        $this->load->view('admin/purchase/bg_detail_purchase',$this->data);
    }

    public function print_purchase(){
        $id = base64_decode($this->uri->segment(4));

        $this->data['getPurchase'] = $this->model_purchase->getPurchaseByID($id)->row();
        $this->load->view('admin/purchase/bg_print',$this->data);
    }

    public function print_req_purchase(){
        $id = base64_decode($this->uri->segment(4));
        $jen = $this->uri->segment(5);
        $this->data['getPurchase'] = $this->model_purchase->getReqPurchaseByID($id)->row();
        if($jen != "pdf"){
        $this->load->view('admin/req_purchase/bg_print',$this->data);
        }else{
        $content = $this->load->view('admin/req_purchase/bg_print_pdf',$this->data,TRUE);
        $this->template->print2pdf('Print_PDF',$content, 'Req_Purchase');
        }
        
        
    }

    public function ubah_status(){
        $id = $this->input->post('id');
        $cmbStatus = $this->input->post('cmbStatus');

        $update = $this->db->set('status',$cmbStatus)->set('update_date',date("Y-m-d H:i:s"))->where('id',$id)->update('transaction_purchase');
        $getInvoice = $this->model_purchase->getPurchaseByID($id)->row();
        if($update){
            if($cmbStatus == 0){
                $st = "BARU";
            }elseif($cmbStatus == 1){
                $st = "DIPROSES";
                $insert_role = $this->db->set('no_transaction',$getInvoice->nonota)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','PO - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
            }else{
                $st = "DITOLAK";
                
                $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id);
                $this->restoreStok($id);
                $insert_role = $this->db->set('no_transaction',$getInvoice->nonota)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Transaksi Ditolak - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
                //     foreach($detailInvoice->result() as $detailInv){
                // $data_produk = $this->model_produk->getProductById($detailInv->product_id)->row();
                // $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                // //print_r($detailInv->qty_kirim);
                // $pengurangan_stok = $cekStok->stok + $detailInv->qty;
                // //  echo "".$detailInv->qty_kirim."";
                // $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                // }
            }
            $ket = "".$_SESSION['rick_auto']['username']." telah melakukan update data untuk ".$st."";
            $insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('purchase_id',$id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_log');

            if($insert_log){
                // //$insert_role = $this->db->set('no_transaction',$getInvoice->nonota)
                //                 ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                //                 ->set('user',$_SESSION['rick_auto']['fullname'])
                //                 ->set('action','PO - '.$_SESSION['rick_auto']['fullname'])
                //                 ->set('create_date',date("Y-m-d H:i:s"))
                //                 ->insert('role_transaksi');
                echo "1";
            }
        }

    }

    public function ubah_status_gudang_(){
        $id = $this->input->post('id');
        $dPurchase = $this->model_purchase->getPurchaseByID($id)->row();
        $cmbStatus = $this->input->post('cmbStatus');
        if($cmbStatus == 1){
            $cekValid = $this->model_purchase->getCekValidPurchase($id);
            if($cekValid->num_rows() > 0){
            $update = $this->db->set('status_gudang',$cmbStatus)->set('update_date',date("Y-m-d H:i:s"))->where('id',$id)->update('transaction_purchase');

                if($update){
                    if($cmbStatus == 0){
                        $st = "DIPROSES";
                    }elseif($cmbStatus == 1){
                        $st = "SELESAI";
                        $getInvoice = $this->model_purchase->getPurchaseByID($id)->row();
                        $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id);
                            foreach($detailInvoice->result() as $detailInv){
                        $data_produk = $this->model_produk->getProductById($detailInv->product_id)->row();
                        $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                        //print_r($detailInv->qty_kirim);
                        $pengurangan_stok = $cekStok->stok - $detailInv->qty_kirim;
                        //  echo "".$detailInv->qty_kirim."";
                        $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                        }
                    }else{
                        $st = "DITOLAK";
                        $txtNoteCancel = $this->input->post('txtNoteCancel');
                        $getInvoice = $this->model_purchase->getPurchaseByID($id);
                        $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id);
                        foreach($detailInvoice->result() as $detailInv){
                                $cmbProduk = $detailInv->product_id;
                                $priceSatuan = $detailInv->price;
                                $addStok = $detailInv->qty;
                                $cmbPerusahaan = 1;
                                $cmbGudang = $detailInv->gudang_id;
                                $priceTotal = $detailInv->ttl_price;
                                $insert_proses = $this->db->set('perusahaan_id',$cmbPerusahaan)->set('product_id',$cmbProduk)->set('qty',$addStok)->set('price',$priceSatuan)->set('ttl_price',$priceTotal)->set('gudang_id',$cmbGudang)->insert('transaction_purchase_temporary_process');
                            }

                             $getProses = $this->model_purchase->getTemporaryProcessGroup();

                            $getPurchase = $this->model_purchase->getPurchaseByIdDesc()->row();
                            $getPurchase_temp = $this->model_purchase->getPurchaseTempByIdDesc()->row();
                            $cmbMember = $getPurchase->member_id;
                            $cmbSales = $getPurchase->sales_id;
                            $cmbExpedisi = $getPurchase->expedisi;
                            $nopoplus = 0;
                            foreach($getProses->result() as $gProses){
                            $nopoplus++;
                            $genUnik = $getPurchase_temp->id + 1;
                            //$nopo = "PO".date('dmy')."".sprintf("%'.05d", $genUnik)."";
                            $nopo = "".date('dmy')."".$cmbMember."".sprintf("%'.05d", $genUnik)."";
                            $insert_po = $this->db->set('perusahaan_id',$gProses->perusahaan_id)->set('member_id',$cmbMember)->set('sales_id',$cmbSales)->set('no_po',$dPurchase->nonota)->set('expedisi',$cmbExpedisi)->set('notransaction',$nopo)->set('dateorder',date("Y-m-d H:i:s"))->set('sub_total',$gProses->total_unit)->set('total',$gProses->total_semua)->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime(date("Y-m-d H:i:s")))))->set('pay_status',0)->set('status',1)->set('note',$txtNoteCancel)->set('createdby',$_SESSION['rick_auto']['fullname'])->set('createdon',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_temporary');
                                $id_recent = $this->db->insert_id();
                                $getPurchaseData = $this->model_purchase->getPurchaseByIdDesc($id_recent);
                                $getProses_perusahaan = $this->model_purchase->getTemporaryProcessByPerusahaan($gProses->perusahaan_id);
                                foreach($getProses_perusahaan->result() as $prosesDetail){
                                    $insert_detail_po = $this->db->set('transaction_purchase_temporary_id',$id_recent)->set('product_id',$prosesDetail->product_id)->set('qty',$prosesDetail->qty)->set('price',$prosesDetail->price)->set('ttl_price',$prosesDetail->ttl_price)->insert('transaction_purchase_temporary_detail');
                                }
                            }
                            if($insert_po){
                            $deletepo = $this->db->query("delete from transaction_purchase where id=".$id."");
                            $deletepo_detail = $this->db->query("delete from transaction_purchase_detail where transaction_purchase_id=".$id."");
                            $delete = $this->db->query("delete from transaction_purchase_temporary_process where 1=1");

                            //echo "1";
                            }

                    }
                    $ket = "data telah ".$st." oleh ".$_SESSION['rick_auto']['username']."";
                    $insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('purchase_id',$id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_log');

                    if($insert_log){
                        
                        echo "1";
                    }                
                } 
            }else{
                echo "2";
            }
        }else{
        $update = $this->db->set('status_gudang',$cmbStatus)->set('update_date',date("Y-m-d H:i:s"))->where('id',$id)->update('transaction_purchase');

        if($update){
            if($cmbStatus == 0){
                $st = "DIPROSES";
            }elseif($cmbStatus == 1){
                $st = "SELESAI";
            }else{
                $st = "DITOLAK";
                $getInvoice = $this->model_purchase->getPurchaseByID($id);
                $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id);
                foreach($detailInvoice->result() as $detailInv){
                        $cmbProduk = $detailInv->product_id;
                        $priceSatuan = $detailInv->price;
                        $addStok = $detailInv->qty;
                        $cmbPerusahaan = 1;
                        $cmbGudang = $detailInv->gudang_id;
                        $priceTotal = $detailInv->ttl_price;
                        $insert_proses = $this->db->set('perusahaan_id',$cmbPerusahaan)->set('product_id',$cmbProduk)->set('qty',$addStok)->set('price',$priceSatuan)->set('ttl_price',$priceTotal)->set('gudang_id',$cmbGudang)->insert('transaction_purchase_temporary_process');
                    }
                    $txtNoteCancel = $this->input->post('txtNoteCancel');
                     $getProses = $this->model_purchase->getTemporaryProcessGroup();

                    $getPurchase = $this->model_purchase->getPurchaseByIdDesc()->row();
                    $getPurchase_temp = $this->model_purchase->getPurchaseTempByIdDesc()->row();
                    $cmbMember = $getPurchase->member_id;
                    $cmbSales = $getPurchase->sales_id;
                    $cmbExpedisi = $getPurchase->expedisi;
                    $nopoplus = 0;
                    foreach($getProses->result() as $gProses){
                    $nopoplus++;
                    $genUnik = $getPurchase_temp->id + 1;
                    //$nopo = "PO".date('dmy')."".sprintf("%'.05d", $genUnik)."";
                    $nopo = "".date('dmy')."".$cmbMember."".sprintf("%'.05d", $genUnik)."";
                    $insert_po = $this->db->set('perusahaan_id',$gProses->perusahaan_id)->set('no_po',$dPurchase->nonota)->set('member_id',$cmbMember)->set('sales_id',$cmbSales)->set('expedisi',$cmbExpedisi)->set('notransaction',$nopo)->set('dateorder',date("Y-m-d H:i:s"))->set('sub_total',$gProses->total_unit)->set('total',$gProses->total_semua)->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime(date("Y-m-d H:i:s")))))->set('pay_status',0)->set('status',1)->set('note',$txtNoteCancel)->set('createdby',$_SESSION['rick_auto']['fullname'])->set('createdon',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_temporary');
                        $id_recent = $this->db->insert_id();
                        $getPurchaseData = $this->model_purchase->getPurchaseByIdDesc($id_recent);
                        $cmbMember = $getPurchaseData->row()->member_id;
                        $cmbSales = $getPurchaseData->row()->sales_id;
                        $getProses_perusahaan = $this->model_purchase->getTemporaryProcessByPerusahaan($gProses->perusahaan_id);
                        foreach($getProses_perusahaan->result() as $prosesDetail){
                            $insert_detail_po = $this->db->set('transaction_purchase_temporary_id',$id_recent)->set('product_id',$prosesDetail->product_id)->set('qty',$prosesDetail->qty)->set('price',$prosesDetail->price)->set('ttl_price',$prosesDetail->ttl_price)->insert('transaction_purchase_temporary_detail');
                        }
                    }
                    if($insert_po){
                    
                        $deletepo = $this->db->query("delete from transaction_purchase where id=".$id."");
                        $deletepo_detail = $this->db->query("delete from transaction_purchase_detail where transaction_purchase_id=".$id."");
                        $delete = $this->db->query("delete from transaction_purchase_temporary_process where 1=1");
                    //echo "1";
                    }
            }
            $ket = "data telah ".$st." oleh ".$_SESSION['rick_auto']['username']."";
            $insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('purchase_id',$id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_log');

            if($insert_log){
                echo "1";
                
            }
            }
        }

    }
    //pemotongan stok dari gudang dengan qty kirim
    public function ubah_status_gudang2(){
        $id = $this->input->post('id');
        $cmbStatus = $this->input->post('cmbStatus');
        if($cmbStatus == 1){
            $cekValid = $this->model_purchase->getCekValidPurchase($id);
            if($cekValid->num_rows() > 0){
            $update = $this->db->set('status_gudang',$cmbStatus)->where('id',$id)->update('transaction_purchase');

                if($update){
                    if($cmbStatus == 0){
                        $st = "DIPROSES";
                    }elseif($cmbStatus == 1){
                        $st = "SELESAI";
                        $getInvoice = $this->model_purchase->getPurchaseByID($id)->row();
                        $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id);
                            foreach($detailInvoice->result() as $detailInv){
                        $data_produk = $this->model_produk->getProductById($detailInv->product_id)->row();
                        $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                        //print_r($detailInv->qty_kirim);
                        $pengurangan_stok = $cekStok->stok - $detailInv->qty_kirim;
                        //  echo "".$detailInv->qty_kirim."";
                        $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                        }
                    }else{
                        $st = "DITOLAK";
                        $txtNoteCancel = $this->input->post('txtNoteCancel');

                    }
                    $ket = "data telah ".$st." oleh ".$_SESSION['rick_auto']['username']."";
                    $insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('purchase_id',$id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_log');

                    if($insert_log){
                        
                        echo "1";
                    }                
                } 
            }else{
                echo "2";
            }
        }else{
        $update = $this->db->set('status_gudang',$cmbStatus)->where('id',$id)->update('transaction_purchase');

        if($update){
            if($cmbStatus == 0){
                $st = "DIPROSES";
            }elseif($cmbStatus == 1){
                $st = "SELESAI";
            }else{
                $st = "DITOLAK";

            }
            $ket = "data telah ".$st." oleh ".$_SESSION['rick_auto']['username']."";
            $insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('purchase_id',$id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_log');

            if($insert_log){
                echo "1";
            
            }
            }
        }

    }

    public function ubah_status_gudang(){
        $id = $this->input->post('id');
        $cmbStatus = $this->input->post('cmbStatus');
        $dPurchase = $this->model_purchase->getPurchaseByID($id)->row();
        $getInvoice = $this->model_purchase->getPurchaseByID($id)->row();
        if($cmbStatus == 1){
            $cekValid = $this->model_purchase->getCekValidPurchase($id);
            if($cekValid->num_rows() > 0){
            $update = $this->db->set('status_gudang',$cmbStatus)->set('update_date',date("Y-m-d H:i:s"))->where('id',$id)->update('transaction_purchase');

                if($update){
                    if($cmbStatus == 0){
                        $st = "DIPROSES";
                    }elseif($cmbStatus == 1){
                        $st = "SELESAI";
                        $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id);
                            foreach($detailInvoice->result() as $detailInv){
                                //$delete = $this->db->where('qty_kirim',0)->where('id',$detailInv->id)->delete('transaction_purchase_detail');
                                $total_price = $detailInv->qty_kirim * $detailInv->price;
                                $update = $this->db->set('ttl_price',$total_price)->where('id',$detailInv->id)->update('transaction_purchase_detail');
                                if($detailInv->qty_kirim == $detailInv->qty){

                                }else{

                                if($detailInv->product_id_shadow == ""){
                                    $data_produk = $this->model_produk->getProductById($detailInv->product_id)->row();
                                }else{
                                    $data_produk = $this->model_produk->getProductById($detailInv->product_id_shadow)->row();
                                }
                                
                                //print_r($detailInv->qty_kirim);
                                if($detailInv->product_id_shadow == ""){
                                    $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                                    if($detailInv->qty_kirim < $detailInv->qty){
                                        $kurangStok = $detailInv->qty - $detailInv->qty_kirim;
                                        $pengurangan_stok = $cekStok->stok + $kurangStok;

                                        $insert_proses = $this->db->set('perusahaan_id',$getInvoice->perusahaan_id)->set('product_id',$detailInv->product_id)->set('product_id_shadow',$detailInv->product_id_shadow)->set('no_rpo',$getInvoice->nonota)->set('qty',$kurangStok)->set('price',$detailInv->price)->set('ttl_price',$detailInv->price * $kurangStok)->set('gudang_id',$detailInv->gudang_id)->set('satuan',$detailInv->satuan)->insert('transaction_purchase_temporary_process_bo');


                                    }
                                    $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                                    if($update_stok){
                                        //$delete = $this->db->where('qty_kirim',0)->where('id',$detailInv->id)->delete('transaction_purchase_detail');

                                    }
                                   // if($kurangStok > 0){
                                    // if($update_stok){
                                    // $insert_opname_stok_bm_masuk = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$kurangStok)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Masuk')->set('keterangan','Purchase Masuk')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                                    // //}
                                    // $insert_opname_stok_bm_keluar = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty_kirim)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                                    // }
                                }else{
                                    
                                    $cekProduk = $this->model_produk->getProductById($detailInv->product_id)->row();
                                    $getKodeBayangan = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Pcs")->row();
                                    $getKodeBayanganSet = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Set")->row();
                                    //$st = $txtQty * $getKodeBayanganSet->satuan_value;
                                    $cekProdukk = $this->model_produk->getProductsById($detailInv->product_id)->row();
                                    if($detailInv->satuan == "Pcs"){
                                    //$cekStok = $this->model_master->getGudangbyProductPerusahaan($detailInv->product_id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                                    $cekStok = $this->model_master->getGudangbyProductPerusahaan($getKodeBayangan->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                                    $cekProduk_ = $this->model_produk->getProductById($getKodeBayangan->id)->row();
                                    $qtyKurangLiner = $detailInv->qty;
                                    if($detailInv->qty_kirim < $detailInv->qty){
                                        $kurangStok = $detailInv->qty - $detailInv->qty_kirim;
                                        $pengurangan_stok = $cekStok->stok + $kurangStok;
                                    }
                                    
                                    $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                                    if($update_stok){
                                        //$delete = $this->db->where('qty_kirim',0)->where('id',$detailInv->id)->delete('transaction_purchase_detail');

                                    }
                                    //$insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Masuk')->set('keterangan','Purchase Masuk')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                                    //if($kurangStok > 0){
                                    // if($update_stok){
                                    //     $insert_opname_stok_bm_masuk = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$kurangStok)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Masuk')->set('keterangan','Purchase Masuk')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                                    //     //}
                                    //     $insert_opname_stok_bm_keluar = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty_kirim)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                                    //     }
                                    }else{
                                    $cekProduk_ = $this->model_produk->getProductById($getKodeBayanganSet->id)->row();
                                    //$cekStok = $this->model_master->getGudangbyProductPerusahaan($getKodeBayangan->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                                    $cekStok = $this->model_master->getGudangbyProductPerusahaan($detailInv->product_id_shadow,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                                    if($detailInv->qty_kirim < $detailInv->qty){
                                        $kurangStok = $detailInv->qty - $detailInv->qty_kirim;
                                        // $pengurangan_stok = $cekStok->stok + $kurangStok;
                                        $qtyKurangLiner = $kurangStok * $getKodeBayanganSet->satuan_value;
                                        $pengurangan_stok = $cekStok->stok + $qtyKurangLiner;

                                        $insert_proses = $this->db->set('perusahaan_id',$getInvoice->perusahaan_id)->set('product_id',$detailInv->product_id)->set('product_id_shadow',$detailInv->product_id_shadow)->set('no_rpo',$getInvoice->nonota)->set('qty',$kurangStok)->set('price',$detailInv->price)->set('ttl_price',$detailInv->price * $kurangStok)->set('gudang_id',$detailInv->gudang_id)->set('satuan',$detailInv->satuan)->insert('transaction_purchase_temporary_process_bo');
                                    }
                                    
                                    //echo $pengurangan_stok;
                                    $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                                    if($update_stok){
                                        //$delete = $this->db->where('qty_kirim',0)->where('id',$detailInv->id)->delete('transaction_purchase_detail');

                                    }
                                    //$insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Masuk')->set('keterangan','Purchase Masuk')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                                    //if($kurangStok > 0){
                                      // if($update_stok){
                                      //   $insert_opname_stok_bm_masuk = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$kurangStok)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Masuk')->set('keterangan','Purchase Masuk')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                                      //   //}
                                      //   $insert_opname_stok_bm_keluar = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty_kirim)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                                      //   }
                                    }
                                }



                                }
                                
                            }
                    }else{
                        $st = "DITOLAK";
                        $txtNoteCancel = $this->input->post('txtNoteCancel');
        $getInvoice = $this->model_purchase->getPurchaseByID($id);
        $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id);
        foreach($detailInvoice->result() as $detailInv){
            //$delete = $this->db->where('qty_kirim',0)->where('id',$detailInv->id)->delete('transaction_purchase_detail');
                $cmbProduk = $detailInv->product_id;
                $priceSatuan = $detailInv->price;
                $addStok = $detailInv->qty;
                //$cmbPerusahaan = $detailInv->perusahaan_id;
                $cmbGudang = $detailInv->gudang_id;
                $priceTotal = $detailInv->ttl_price;
                $insert_proses = $this->db->set('perusahaan_id',$getInvoice->row()->perusahaan_id)->set('product_id',$cmbProduk)->set('qty',$addStok)->set('price',$priceSatuan)->set('ttl_price',$priceTotal)->set('gudang_id',$cmbGudang)->insert('transaction_purchase_temporary_process');
                $perusahaan_gudang = $this->model_master->getPerusahaanGudangByGudang($getInvoice->row()->perusahaan_id,$cmbGudang)->row();
                if($detailInv->product_id_shadow == ""){
                $update_stok = $this->db->set('stok',$detailInv->qty)->set('product_id',$detailInv->product_id)->set('perusahaan_gudang_id',$perusahaan_gudang->id)->insert('product_perusahaan_gudang');
                }else{
                    if($detailInv->satuan == "Pcs"){
                        $update_stok = $this->db->set('stok',$detailInv->qty)->set('product_id',$detailInv->product_id)->set('perusahaan_gudang_id',$perusahaan_gudang->id)->insert('product_perusahaan_gudang');  
                    }else{
                        $cekProduk = $this->model_produk->getProductById($detailInv->product_id)->row();
                        $getKodeBayangan = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Pcs")->row();
                        $getKodeBayanganSet = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Set")->row();
                        $qtyKurangLiner = $detailInv->qty * $getKodeBayanganSet->satuan_value;
                        $update_stok = $this->db->set('stok',$qtyKurangLiner)->set('product_id',$detailInv->product_id_shadow)->set('perusahaan_gudang_id',$perusahaan_gudang->id)->insert('product_perusahaan_gudang');  
                        //$insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                    }
                 
                }

        }

            // foreach($detailInvoice->result() as $detailInvv){
            //     $data_produk = $this->model_produk->getProductById($detailInvv->product_id)->row();
            //     $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->row()->perusahaan_id,$detailInvv->gudang_id)->row();
            //     //print_r($detailInvv->qty_kirim);
            //     $pengurangan_stok = $cekStok->stok + $detailInvv->qty;
            //     //  echo "".$detailInvv->qty_kirim."";
            //     $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
            // }

             $getProses = $this->model_purchase->getTemporaryProcessGroup();

           // $getPurchase = $this->model_purchase->getPurchaseByIdDesc()->row();
            $getPurchase = $this->model_purchase->getPurchaseByID($id)->row();  
            $getPurchase_temps = $this->model_purchase->getPurchaseTempByIdDesc();
            $getPurchase_temp = $this->model_purchase->getPurchaseTempByIdDesc()->row();
            $cmbMember = $getPurchase->member_id;
            $cmbSales = $getPurchase->sales_id;
            $cmbExpedisi = $getPurchase->expedisi;
            $nopoplus = 0;
            foreach($getProses->result() as $gProses){
            $nopoplus++;
            if($getPurchase_temps->num_rows() > 0){
                $genUnik = $getPurchase_temp->id + 1;
            }else{
                $genUnik = 1;
            }
            //$nopo = "PO".date('dmy')."".sprintf("%'.05d", $genUnik)."";
            $nopo = "".date('dmy')."".$cmbMember."".sprintf("%'.05d", $genUnik)."";
            $insert_po = $this->db->set('perusahaan_id',$gProses->perusahaan_id)->set('member_id',$cmbMember)->set('sales_id',$cmbSales)->set('expedisi',$cmbExpedisi)->set('notransaction',$nopo)->set('no_po',$dPurchase->nonota)->set('dateorder',date("Y-m-d H:i:s"))->set('sub_total',$gProses->total_unit)->set('total',$gProses->total_semua)->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime(date("Y-m-d H:i:s")))))->set('pay_status',0)->set('status',1)->set('note',$txtNoteCancel)->set('createdby',$_SESSION['rick_auto']['fullname'])->set('createdon',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_temporary');
                $id_recent = $this->db->insert_id();
                $getPurchaseData = $this->model_purchase->getPurchaseByID($id);
                $getProses_perusahaan = $this->model_purchase->getTemporaryProcessByPerusahaan($getPurchaseData->row()->perusahaan_id);
                foreach($getProses_perusahaan->result() as $prosesDetail){
                    $insert_po = $this->db->set('transaction_purchase_temporary_id',$id_recent)->set('product_id',$prosesDetail->product_id)->set('qty',$prosesDetail->qty)->set('price',$prosesDetail->price)->set('ttl_price',$prosesDetail->ttl_price)->insert('transaction_purchase_temporary_detail');
                }
            }

            if($insert_po){
                $insert_role = $this->db->set('no_transaction',$nopo)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Transaksi Ditolak - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
                $getInvoicee = $this->model_purchase->getPurchaseByID($id)->row();
                $insert_role2 = $this->db->set('no_transaction',$getInvoicee->nonota)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Transaksi Ditolak - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
                // $deletepo = $this->db->query("delete from transaction_purchase where id=".$id."");
                // $deletepo_detail = $this->db->query("delete from transaction_purchase_detail where transaction_purchase_id=".$id."");
                //
                $ket = "data PO telah ditolak oleh ".$_SESSION['rick_auto']['username']."";
                $insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('purchase_id',$id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_log');

                if($insert_log){
                    //echo "1";
                    //$delete = $this->db->query("delete from transaction_purchase_temporary_process where 1=1");
                    
                    
                }  
            }
                        //$this->restoreStok($id);
                        // $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id);
                        //     foreach($detailInvoice->result() as $detailInv){
                        // $data_produk = $this->model_produk->getProductById($detailInv->product_id)->row();
                        // $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                        // //print_r($detailInv->qty_kirim);
                        // $pengurangan_stok = $cekStok->stok + $detailInv->qty;
                        // //  echo "".$detailInv->qty_kirim."";
                        // $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                        //}

                    }
                    $ket = "data telah ".$st." oleh ".$_SESSION['rick_auto']['username']."";
                    $insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('purchase_id',$id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_log');
                    $insert_role = $this->db->set('no_transaction',$getInvoice->nonota)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                //->set('action',$st)
                                ->set('action','GDG - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
                    if($insert_log){
                        
                        echo "1";
                    }                
                } 
            }else{
                echo "2";
            }
        }else{
        $update = $this->db->set('status_gudang',$cmbStatus)->set('update_date',date("Y-m-d H:i:s"))->where('id',$id)->update('transaction_purchase');

        if($update){
            if($cmbStatus == 0){
                $st = "DIPROSES";
            }elseif($cmbStatus == 1){
                $st = "SELESAI";
            }else{
                $st = "DITOLAK";

                                        $txtNoteCancel = $this->input->post('txtNoteCancel');
        $getInvoice = $this->model_purchase->getPurchaseByID($id);
        $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id);
        foreach($detailInvoice->result() as $detailInv){
                $cmbProduk = $detailInv->product_id;
                $priceSatuan = $detailInv->price;
                $addStok = $detailInv->qty;
                //$cmbPerusahaan = $detailInv->perusahaan_id;
                $cmbGudang = $detailInv->gudang_id;
                $priceTotal = $detailInv->ttl_price;
                $insert_proses = $this->db->set('perusahaan_id',$getInvoice->row()->perusahaan_id)->set('product_id',$cmbProduk)->set('qty',$addStok)->set('price',$priceSatuan)->set('ttl_price',$priceTotal)->set('gudang_id',$cmbGudang)->insert('transaction_purchase_temporary_process');
                $perusahaan_gudang = $this->model_master->getPerusahaanGudangByGudang($getInvoice->row()->perusahaan_id,$cmbGudang)->row();
                if($detailInv->product_id_shadow == ""){
                $update_stok = $this->db->set('stok',$detailInv->qty)->set('product_id',$detailInv->product_id)->set('perusahaan_gudang_id',$perusahaan_gudang->id)->insert('product_perusahaan_gudang');
                }else{
                if($detailInv->satuan == "Pcs"){
                    $update_stok = $this->db->set('stok',$detailInv->qty)->set('product_id',$detailInv->product_id)->set('perusahaan_gudang_id',$perusahaan_gudang->id)->insert('product_perusahaan_gudang');  
                }else{
                    $cekProduk = $this->model_produk->getProductById($detailInv->product_id)->row();
                    $getKodeBayangan = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Pcs")->row();
                    $getKodeBayanganSet = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Set")->row();
                    $qtyKurangLiner = $detailInv->qty * $getKodeBayanganSet->satuan_value;
                    $update_stok = $this->db->set('stok',$qtyKurangLiner)->set('product_id',$detailInv->product_id_shadow)->set('perusahaan_gudang_id',$perusahaan_gudang->id)->insert('product_perusahaan_gudang');  
                    //$insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                }
                 
                }

        }

            // foreach($detailInvoice->result() as $detailInvv){
            //     $data_produk = $this->model_produk->getProductById($detailInvv->product_id)->row();
            //     $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->row()->perusahaan_id,$detailInvv->gudang_id)->row();
            //     //print_r($detailInvv->qty_kirim);
            //     $pengurangan_stok = $cekStok->stok + $detailInvv->qty;
            //     //  echo "".$detailInvv->qty_kirim."";
            //     $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
            // }

             $getProses = $this->model_purchase->getTemporaryProcessGroup();

            // $getPurchase = $this->model_purchase->getPurchaseByIdDesc()->row();
            $getPurchase = $this->model_purchase->getPurchaseByID($id)->row();  
            $getPurchase_temps = $this->model_purchase->getPurchaseTempByIdDesc();
            $getPurchase_temp = $this->model_purchase->getPurchaseTempByIdDesc()->row();
            $cmbMember = $getPurchase->member_id;
            $cmbSales = $getPurchase->sales_id;
            $cmbExpedisi = $getPurchase->expedisi;
            $nopoplus = 0;
            foreach($getProses->result() as $gProses){
            $nopoplus++;
            if($getPurchase_temps->num_rows() > 0){
                $genUnik = $getPurchase_temp->id + 1;
            }else{
                $genUnik = 1;
            }
            //$nopo = "PO".date('dmy')."".sprintf("%'.05d", $genUnik)."";
            $nopo = "".date('dmy')."".$cmbMember."".sprintf("%'.05d", $genUnik)."";
            $insert_po = $this->db->set('perusahaan_id',$gProses->perusahaan_id)->set('member_id',$cmbMember)->set('sales_id',$cmbSales)->set('expedisi',$cmbExpedisi)->set('notransaction',$nopo)->set('no_po',$dPurchase->nonota)->set('dateorder',date("Y-m-d H:i:s"))->set('sub_total',$gProses->total_unit)->set('total',$gProses->total_semua)->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime(date("Y-m-d H:i:s")))))->set('pay_status',0)->set('status',1)->set('note',$txtNoteCancel)->set('createdby',$_SESSION['rick_auto']['fullname'])->set('createdon',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_temporary');
                $id_recent = $this->db->insert_id();
                $getPurchaseData = $this->model_purchase->getPurchaseByID($id);
                $getProses_perusahaan = $this->model_purchase->getTemporaryProcessByPerusahaan($getPurchaseData->row()->perusahaan_id);
                foreach($getProses_perusahaan->result() as $prosesDetail){
                    $insert_po = $this->db->set('transaction_purchase_temporary_id',$id_recent)->set('product_id',$prosesDetail->product_id)->set('qty',$prosesDetail->qty)->set('price',$prosesDetail->price)->set('ttl_price',$prosesDetail->ttl_price)->insert('transaction_purchase_temporary_detail');
                }
            }

            if($insert_po){
                                $insert_role = $this->db->set('no_transaction',$nopo)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Transaksi Ditolak - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
                $getInvoicee = $this->model_purchase->getPurchaseByID($id)->row();
                $insert_role2 = $this->db->set('no_transaction',$getInvoicee->nonota)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Transaksi Ditolak - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
                // $deletepo = $this->db->query("delete from transaction_purchase where id=".$id."");
                // $deletepo_detail = $this->db->query("delete from transaction_purchase_detail where transaction_purchase_id=".$id."");
                //
                $ket = "data PO telah ditolak oleh ".$_SESSION['rick_auto']['username']."";
                $insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('purchase_id',$id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_log');

                if($insert_log){
                    //echo "1";
                    //$delete = $this->db->query("delete from transaction_purchase_temporary_process where 1=1");
                    
                    
                }  
            }

            }
            $ket = "data telah ".$st." oleh ".$_SESSION['rick_auto']['username']."";
            $insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('purchase_id',$id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_log');
            $insert_role = $this->db->set('no_transaction',$getInvoice->nonota)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','GDG - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');

            if($insert_log){
                    // if($getTempBo->num_rows() > 0){
                    // $getTempBo = $this->model_purchase->getTemporaryProcessBO();
                    // $norpo = $getTempBo->row()->no_rpo;
                    // $norpoOut = "BO-".$getTempBo->row()->no_rpo;
                    // $cekBo = $this->model_purchase->getTemporaryByNoBO($norpoOut);
                    // // if($cekBo->num_rows() > 0){

                    // // }else{
                    // $insert_po_bo = $this->db->set('perusahaan_id',$getInvoice->perusahaan_id)->set('member_id',$getInvoice->member_id)->set('sales_id',$getInvoice->sales_id)->set('expedisi',$getInvoice->expedisi)->set('expedisi_via',$getInvoice->expedisi_via)->set('notransaction',$norpoOut)->set('no_po',$getInvoice->nonota)->set('dateorder',date("Y-m-d H:i:s"))->set('sub_total',$getTempBo->row()->sub_total)->set('total',$getTempBo->row()->total)->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime(date("Y-m-d H:i:s")))))->set('createdby',$_SESSION['rick_auto']['fullname'])->set('createdon',$_SESSION['rick_auto']['username'])->set('flag_bo',1)->insert('transaction_purchase_temporary');
                    // $idPObo = $this->db->insert_id();
                    // $subTotals = 0;
                    // $totals = 0;
                    // foreach($getTempBo->result() as $tempBo){
                    //     $insert_po_detail_bo = $this->db->set('transaction_purchase_temporary_id',$idPObo)->set('product_id',$tempBo->product_id)->set('qty',$tempBo->qty)->set('price',$tempBo->price)->set('ttl_price',$tempBo->ttl_price)->insert('transaction_purchase_temporary_detail');
                    //     $subTotals = $subTotals + $tempBo->price;
                    //     $totals = $totals + $tempBo->ttl_price;
                    // }

                    // $updatePo = $this->db->set('sub_total',$subTotals)->set('total',$totals)->where('id',$idPObo)->update('transaction_purchase_temporary');
                    // }
                echo "1";
                 
            
                }
            }
        }

    }

    public function insertBO(){
            $id = $this->input->post('id');
            $dPurchase = $this->model_purchase->getPurchaseByID($id)->row();
            $getInvoice = $this->model_purchase->getPurchaseByID($id)->row();
            $getTempBo = $this->model_purchase->getTemporaryProcessBO();
            if($getTempBo->num_rows() > 0){
            $norpo = $getTempBo->row()->no_rpo;
            $norpoOut = "BO-".$getTempBo->row()->no_rpo;
            $cekBo = $this->model_purchase->getTemporaryByNoBO($norpoOut);
            // if($cekBo->num_rows() > 0){

            // }else{
            $insert_po_bo = $this->db->set('perusahaan_id',$getInvoice->perusahaan_id)->set('member_id',$getInvoice->member_id)->set('sales_id',$getInvoice->sales_id)->set('expedisi',$getInvoice->expedisi)->set('expedisi_via',$getInvoice->expedisi_via)->set('notransaction',$norpoOut)->set('no_po',$getInvoice->nonota)->set('dateorder',date("Y-m-d H:i:s"))->set('sub_total',$getTempBo->row()->price)->set('total',$getTempBo->row()->ttl_price)->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime(date("Y-m-d H:i:s")))))->set('createdby',$_SESSION['rick_auto']['fullname'])->set('createdon',$_SESSION['rick_auto']['username'])->set('flag_bo',1)->insert('transaction_purchase_temporary');
            $idPObo = $this->db->insert_id();
            $subTotals = 0;
            $totals = 0;
            foreach($getTempBo->result() as $tempBo){
                $insert_po_detail_bo = $this->db->set('transaction_purchase_temporary_id',$idPObo)->set('product_id',$tempBo->product_id)->set('qty',$tempBo->qty)->set('price',$tempBo->price)->set('ttl_price',$tempBo->ttl_price)->insert('transaction_purchase_temporary_detail');
                $subTotals = $subTotals + $tempBo->price;
                $totals = $totals + $tempBo->ttl_price;
            }

            $updatePo = $this->db->set('sub_total',$subTotals)->set('total',$totals)->where('id',$idPObo)->update('transaction_purchase_temporary');
            $insert_role = $this->db->set('no_transaction',$norpoOut)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','BO - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
            }
        if($updatePo){

            $this->db->query('TRUNCATE TABLE transaction_purchase_temporary_process_bo');
            
        }
        echo "1";
        
    }
    public function reportMasukKeluar(){
        $id = $this->input->post('id');
        $cmbStatus = $this->input->post('cmbStatus');
        if($cmbStatus == 1){
            $getInvoice = $this->model_purchase->getPurchaseByID($id)->row();
            $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id);
            foreach($detailInvoice->result() as $detailInv){
            $kurangStok = $detailInv->qty - $detailInv->qty_kirim;
            //$insert_opname_stok_bm_masuk = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$kurangStok)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Masuk')->set('keterangan','Purchase Masuk')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
            $insert_opname_stok_bm_keluar = $this->db->set('product_id',$detailInv->product_id)->set('transaction_no',$getInvoice->nonota)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty_kirim)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
            }
        }
        echo "1";

    }

    public function process_cancel_invoice(){
        $id = $this->input->post('id');
        $dPurchase = $this->model_purchase->getPurchaseByID($id)->row();
        $update = $this->db->set('status_gudang',2)->set('status',1)->where('id',$id)->update('transaction_purchase');
        $txtNoteCancel = $this->input->post('txtNoteCancel');
        $getInvoice = $this->model_purchase->getPurchaseByID($id);
        $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id);
        foreach($detailInvoice->result() as $detailInv){
                $cmbProduk = $detailInv->product_id;
                $priceSatuan = $detailInv->price;
                $addStok = $detailInv->qty_kirim;
                //$cmbPerusahaan = $detailInv->perusahaan_id;
                $cmbGudang = $detailInv->gudang_id;
                $priceTotal = $detailInv->ttl_price;
                $insert_proses = $this->db->set('perusahaan_id',$getInvoice->row()->perusahaan_id)->set('product_id',$cmbProduk)->set('qty',$addStok)->set('price',$priceSatuan)->set('ttl_price',$priceTotal)->set('gudang_id',$cmbGudang)->insert('transaction_purchase_temporary_process');
                $perusahaan_gudang = $this->model_master->getPerusahaanGudangByGudang($getInvoice->row()->perusahaan_id,$cmbGudang)->row();
                if($detailInv->product_id_shadow == ""){
                $update_stok = $this->db->set('stok',$detailInv->qty_kirim)->set('product_id',$detailInv->product_id)->set('perusahaan_gudang_id',$perusahaan_gudang->id)->insert('product_perusahaan_gudang');
                }else{
                if($detailInv->satuan == "Pcs"){
                    $update_stok = $this->db->set('stok',$detailInv->qty_kirim)->set('product_id',$detailInv->product_id)->set('perusahaan_gudang_id',$perusahaan_gudang->id)->insert('product_perusahaan_gudang');  
                }else{
                    $cekProduk = $this->model_produk->getProductById($detailInv->product_id)->row();
                    $getKodeBayangan = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Pcs")->row();
                    $getKodeBayanganSet = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Set")->row();
                    $qtyKurangLiner = $detailInv->qty_kirim * $getKodeBayanganSet->satuan_value;
                    $update_stok = $this->db->set('stok',$qtyKurangLiner)->set('product_id',$detailInv->product_id_shadow)->set('perusahaan_gudang_id',$perusahaan_gudang->id)->insert('product_perusahaan_gudang');  
                    //$insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                }
                 
                }

        }

            // foreach($detailInvoice->result() as $detailInvv){
            //     $data_produk = $this->model_produk->getProductById($detailInvv->product_id)->row();
            //     $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->row()->perusahaan_id,$detailInvv->gudang_id)->row();
            //     //print_r($detailInvv->qty_kirim);
            //     $pengurangan_stok = $cekStok->stok + $detailInvv->qty;
            //     //  echo "".$detailInvv->qty_kirim."";
            //     $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
            // }

             $getProses = $this->model_purchase->getTemporaryProcessGroup();

            // $getPurchase = $this->model_purchase->getPurchaseByIdDesc()->row();
            $getPurchase = $this->model_purchase->getPurchaseByID($id)->row();  
            $getPurchase_temps = $this->model_purchase->getPurchaseTempByIdDesc();
            $getPurchase_temp = $this->model_purchase->getPurchaseTempByIdDesc()->row();
            $cmbMember = $getPurchase->member_id;
            $cmbSales = $getPurchase->sales_id;
            $cmbExpedisi = $getPurchase->expedisi;
            $nopoplus = 0;
            foreach($getProses->result() as $gProses){
            $nopoplus++;
            if($getPurchase_temps->num_rows() > 0){
                $genUnik = $getPurchase_temp->id + 1;
            }else{
                $genUnik = 1;
            }
            //$nopo = "PO".date('dmy')."".sprintf("%'.05d", $genUnik)."";
            $nopo = "".date('dmy')."".$cmbMember."".sprintf("%'.05d", $genUnik)."";
            $insert_po = $this->db->set('perusahaan_id',$gProses->perusahaan_id)->set('member_id',$cmbMember)->set('sales_id',$cmbSales)->set('expedisi',$cmbExpedisi)->set('notransaction',$nopo)->set('no_po',$dPurchase->nonota)->set('dateorder',date("Y-m-d H:i:s"))->set('sub_total',$gProses->total_unit)->set('total',$gProses->total_semua)->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime(date("Y-m-d H:i:s")))))->set('pay_status',0)->set('status',1)->set('note',$txtNoteCancel)->set('createdby',$_SESSION['rick_auto']['fullname'])->set('createdon',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_temporary');
                $id_recent = $this->db->insert_id();
                $getPurchaseData = $this->model_purchase->getPurchaseByID($id);
                $getProses_perusahaan = $this->model_purchase->getTemporaryProcessByPerusahaan($getPurchaseData->row()->perusahaan_id);
                foreach($getProses_perusahaan->result() as $prosesDetail){
                    $insert_po = $this->db->set('transaction_purchase_temporary_id',$id_recent)->set('product_id',$prosesDetail->product_id)->set('qty',$prosesDetail->qty)->set('price',$prosesDetail->price)->set('ttl_price',$prosesDetail->ttl_price)->insert('transaction_purchase_temporary_detail');
                }
            }

            if($insert_po){
                                $insert_role = $this->db->set('no_transaction',$nopo)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Transaksi Ditolak - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
                $getInvoicee = $this->model_purchase->getPurchaseByID($id)->row();
                $insert_role2 = $this->db->set('no_transaction',$getInvoicee->nonota)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Transaksi Ditolak - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
                // $deletepo = $this->db->query("delete from transaction_purchase where id=".$id."");
                // $deletepo_detail = $this->db->query("delete from transaction_purchase_detail where transaction_purchase_id=".$id."");
                //
                $ket = "data PO telah ditolak oleh ".$_SESSION['rick_auto']['username']."";
                $insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('purchase_id',$id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_log');

                if($insert_log){
                    echo "1";
                    //$delete = $this->db->query("delete from transaction_purchase_temporary_process where 1=1");
                    
                    
                }  
            }

    }

   public function process_cancel_poo(){
        $id = $this->input->post('id');
        $dPurchase = $this->model_purchase->getPurchaseByID($id)->row();
        $update = $this->db->set('status_gudang',2)->set('status',1)->where('id',$id)->update('transaction_purchase');
        $txtNoteCancel = $this->input->post('txtNoteCancel');
        $getInvoice = $this->model_purchase->getPurchaseByID($id);
        $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id);
        foreach($detailInvoice->result() as $detailInv){
                $cmbProduk = $detailInv->product_id;
                $priceSatuan = $detailInv->price;
                $addStok = $detailInv->qty;
                //$cmbPerusahaan = $detailInv->perusahaan_id;
                $cmbGudang = $detailInv->gudang_id;
                $priceTotal = $detailInv->ttl_price;
                $insert_proses = $this->db->set('perusahaan_id',$getInvoice->row()->perusahaan_id)->set('product_id',$cmbProduk)->set('qty',$addStok)->set('price',$priceSatuan)->set('ttl_price',$priceTotal)->set('gudang_id',$cmbGudang)->insert('transaction_purchase_temporary_process');
                $perusahaan_gudang = $this->model_master->getPerusahaanGudangByGudang($getInvoice->row()->perusahaan_id,$cmbGudang)->row();
                if($detailInv->product_id_shadow == ""){
                $update_stok = $this->db->set('stok',$detailInv->qty)->set('product_id',$detailInv->product_id)->set('perusahaan_gudang_id',$perusahaan_gudang->id)->insert('product_perusahaan_gudang');
                }else{
                if($detailInv->satuan == "Pcs"){
                    $update_stok = $this->db->set('stok',$detailInv->qty)->set('product_id',$detailInv->product_id)->set('perusahaan_gudang_id',$perusahaan_gudang->id)->insert('product_perusahaan_gudang');  
                }else{
                    $cekProduk = $this->model_produk->getProductById($detailInv->product_id)->row();
                    $getKodeBayangan = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Pcs")->row();
                    $getKodeBayanganSet = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Set")->row();
                    $qtyKurangLiner = $detailInv->qty * $getKodeBayanganSet->satuan_value;
                    $update_stok = $this->db->set('stok',$qtyKurangLiner)->set('product_id',$detailInv->product_id_shadow)->set('perusahaan_gudang_id',$perusahaan_gudang->id)->insert('product_perusahaan_gudang');  
                    //$insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                }
                 
                }

        }

            // foreach($detailInvoice->result() as $detailInvv){
            //     $data_produk = $this->model_produk->getProductById($detailInvv->product_id)->row();
            //     $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->row()->perusahaan_id,$detailInvv->gudang_id)->row();
            //     //print_r($detailInvv->qty_kirim);
            //     $pengurangan_stok = $cekStok->stok + $detailInvv->qty;
            //     //  echo "".$detailInvv->qty_kirim."";
            //     $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
            // }

             $getProses = $this->model_purchase->getTemporaryProcessGroup();

            // $getPurchase = $this->model_purchase->getPurchaseByIdDesc()->row();
            $getPurchase = $this->model_purchase->getPurchaseByID($id)->row();  
            $getPurchase_temps = $this->model_purchase->getPurchaseTempByIdDesc();
            $getPurchase_temp = $this->model_purchase->getPurchaseTempByIdDesc()->row();
            $cmbMember = $getPurchase->member_id;
            $cmbSales = $getPurchase->sales_id;
            $cmbExpedisi = $getPurchase->expedisi;
            $nopoplus = 0;
            foreach($getProses->result() as $gProses){
            $nopoplus++;
            if($getPurchase_temps->num_rows() > 0){
                $genUnik = $getPurchase_temp->id + 1;
            }else{
                $genUnik = 1;
            }
            //$nopo = "PO".date('dmy')."".sprintf("%'.05d", $genUnik)."";
            $nopo = "".date('dmy')."".$cmbMember."".sprintf("%'.05d", $genUnik)."";
            $insert_po = $this->db->set('perusahaan_id',$gProses->perusahaan_id)->set('member_id',$cmbMember)->set('sales_id',$cmbSales)->set('expedisi',$cmbExpedisi)->set('notransaction',$nopo)->set('no_po',$dPurchase->nonota)->set('dateorder',date("Y-m-d H:i:s"))->set('sub_total',$gProses->total_unit)->set('total',$gProses->total_semua)->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime(date("Y-m-d H:i:s")))))->set('pay_status',0)->set('status',1)->set('note',$txtNoteCancel)->set('createdby',$_SESSION['rick_auto']['fullname'])->set('createdon',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_temporary');
                $id_recent = $this->db->insert_id();
                $getPurchaseData = $this->model_purchase->getPurchaseByID($id);
                $getProses_perusahaan = $this->model_purchase->getTemporaryProcessByPerusahaan($getPurchaseData->row()->perusahaan_id);
                foreach($getProses_perusahaan->result() as $prosesDetail){
                    $insert_po = $this->db->set('transaction_purchase_temporary_id',$id_recent)->set('product_id',$prosesDetail->product_id)->set('qty',$prosesDetail->qty)->set('price',$prosesDetail->price)->set('ttl_price',$prosesDetail->ttl_price)->insert('transaction_purchase_temporary_detail');
                }
            }

            if($insert_po){
                                $insert_role = $this->db->set('no_transaction',$nopo)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Transaksi Ditolak - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
                $getInvoicee = $this->model_purchase->getPurchaseByID($id)->row();
                $insert_role2 = $this->db->set('no_transaction',$getInvoicee->nonota)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Transaksi Ditolak - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
                // $deletepo = $this->db->query("delete from transaction_purchase where id=".$id."");
                // $deletepo_detail = $this->db->query("delete from transaction_purchase_detail where transaction_purchase_id=".$id."");
                //
                $ket = "data PO telah ditolak oleh ".$_SESSION['rick_auto']['username']."";
                $insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('purchase_id',$id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_log');

                if($insert_log){
                    echo "1";
                    //$delete = $this->db->query("delete from transaction_purchase_temporary_process where 1=1");
                    
                    
                }  
            }

    }

    public function delete_data_transaksi(){
        $id = $this->input->post('id');
       // $deletepo = $this->db->query("delete from transaction_purchase where id=".$id."");
        //$deletepo_detail = $this->db->query("delete from transaction_purchase_detail where transaction_purchase_id=".$id."");
        //$delete = $this->db->query("delete from transaction_purchase_temporary_process where 1=1");
        echo "1";
    }

    public function process_cancel_invoice_(){
        $id = $this->input->post('id');
        $txtNoteCancel = $this->input->post('txtNoteCancel');
        $getInvoice = $this->model_purchase->getPurchaseByID($id);
        $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id);

            $update = $this->db->set('status_gudang',2)->where('id',$id)->update('transaction_purchase');
            $ket = "data PO telah ditolak oleh ".$_SESSION['rick_auto']['username']."";
            $insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('purchase_id',$id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_log');

            if($insert_log){
                echo "1";
            }  
    }

    public function edit_data(){
        $id = $this->input->post('id');
        $inputs = $this->input->post('input');
        $kolom = $this->input->post('kolom');

        $update = $this->db->set($kolom, $inputs)->where('id',$id)->update('transaction_purchase_detail');
        if($update){
            echo "1";
        }
    }

    public function process_nota_(){
        $id = $this->input->post('id');
        $txtDateInvoice = date("Y-m-d",strtotime("+0 day", strtotime($this->input->post('txtDateInvoice')))); 
        $getPurchase = $this->model_purchase->getPurchaseByID($id)->row();
        $note = "";
        $getPerusahaan = $this->model_master->getPerusahaanByID($getPurchase->perusahaan_id)->row();

        $namaPerus = $this->model_master->getPerusahaanByID($getPurchase->perusahaan_id)->row();
        $namacut = $namaPerus->name;
        $namapt = substr($namacut,0,3);
        $nama = substr($namacut,4);
        $arr = explode(' ', $nama);
        $singkatan = "";
        foreach($arr as $kata)
        {
        $singkatan .= substr($kata, 0, 1);
        }

        $namapt = $namapt."".strtoupper($singkatan);

        $getExpedisi = $this->model_master->getExpedisiById($getPurchase->expedisi)->row();
        if($getPurchase->expedisi_via == ""){
            $getViaExpedisi = "";
        }else{
            $getViaExpedisi = $this->model_master->getExpedisiById($getPurchase->expedisi_via)->row()->name;
        }
        $getInvoice = $this->model_invoice->getInvoiceDescByPerusahaan($getPurchase->perusahaan_id);
        $cutNo = explode("/",$getInvoice->row()->nonota);
        //echo $v[0];
        if($getInvoice->num_rows() > 0){
                $genUnik = $cutNo[0] + 1;
        }else{
                $genUnik = 1;
        }
        $number_invoice = sprintf("%'.05d", $genUnik)."/".$namapt."/".date('m')."/".date('y')."";
        //$number_invoice = str_replace("PO","INV",$getPurchase->nonota);
        $cekDataInv = $this->model_purchase->getInvoiceByNoNota($number_invoice);
        //echo $number_invoice;
        $ppn = $getPurchase->total * 10 / 100;
        $grandTotal = $getPurchase->total + $ppn;
        if($cekDataInv->num_rows() > 0){
        echo "2";
        }else{
        $insert_purchase = $this->db->set('nonota',$number_invoice)
                                    ->set('dateorder',date("Y-m-d H:i:s"))
                                    ->set('member_id',$getPurchase->member_id)
                                    ->set('purchase_no',$getPurchase->nonota)
                                    ->set('member_name',$getPurchase->nama_member)
                                    ->set('sales_id',$getPurchase->sales_id)
                                    ->set('sales_name',$getPurchase->nama_sales)
                                    ->set('perusahaan_name',$getPerusahaan->name)
                                    ->set('perusahaan_id',$getPurchase->perusahaan_id)
                                    ->set('sub_total',$getPurchase->sub_total)
                                    ->set('discount',$getPurchase->discount)
                                    ->set('total_before_ppn',$getPurchase->total)
                                    ->set('total',$grandTotal)
                                    ->set('note',$note)
                                    ->set('expedisi',$getExpedisi->name)
                                    ->set('via_expedisi',$getViaExpedisi)
                                    ->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime(date("Y-m-d H:i:s")))))
                                    ->set('min_duedate',date("Y-m-d",strtotime("+90 day", strtotime(date("Y-m-d")))))
                                    ->set('pay_status',0)
                                    ->set('dateprint',date("Y-m-d H:i:s"))
                                    ->set('create_date',date("Y-m-d H:i:s"))
                                    ->set('create_user',$_SESSION['rick_auto']['username'])
                                    ->insert('invoice');
        $id_invoice = $this->db->insert_id();
        if($insert_purchase){
            $getDetailPurchase = $this->model_purchase->getPurchaseDetailByPurchase($id);
            $sub_total = 0;
            $grand_total = 0;
            foreach($getDetailPurchase->result() as $detailPurchase){
                $ttl_price = $detailPurchase->price * $detailPurchase->qty_kirim;
                $sub_total = $sub_total + $detailPurchase->price;
                $grand_total = $grand_total + $ttl_price;
                $insert_detail = $this->db->set('invoice_id',$id_invoice)->set('product_code',$detailPurchase->kode_produk)->set('product_name',$detailPurchase->nama_produk)->set('qty',$detailPurchase->qty)->set('qty_kirim',$detailPurchase->qty_kirim)->set('colly',$detailPurchase->colly)->set('colly_to',$detailPurchase->colly_to)->set('weight',$detailPurchase->weight)->set('price',$detailPurchase->price)->set('ttl_price',$ttl_price)->set('satuan',$detailPurchase->nama_satuan)->set('deskripsi',$detailPurchase->deskripsi_produk)->set('product_img',$detailPurchase->gambar_cover)->set('gudang_id',$detailPurchase->gudang_id)->insert('invoice_detail');
            }
            
            $ppn = $grand_total * 10 / 100;
            $grand_Total_semua = $grand_total + $ppn;

            $update_status = $this->db->set('status',3)->where('id',$id)->update('transaction_purchase');
            if($update_status){
                $st = "Dibuat";
                $ket = "Invoice telah ".$st." oleh ".$_SESSION['rick_auto']['username']." dengan Nomor INVOICE : ".$number_invoice."";
                $insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('purchase_id',$id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_log');

                $insert_role = $this->db->set('no_transaction',$number_invoice)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','INV - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');

                $insert_role = $this->db->set('no_transaction',$getPurchase->nonota)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','INV - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
                

                $updateInvoice = $this->db->set('sub_total',$sub_total)->set('total_before_ppn',$grand_total)->set('total',$grand_Total_semua)->where('id',$id_invoice)->update('invoice');
                
                }
        }
        echo "1";
        }
        ///if($insert_log){
                    
                   // }
    }

    public function process_nota(){
        $id = $this->input->post('id');
        $txtTotal = $this->input->post('txtTotal');
        $txtDiskon = $this->input->post('txtDiskon');
        $txtTotalAfter = $this->input->post('txtTotalAfter');
        $txtDiskonRupiah = $this->input->post('txtDiskonRupiah');

        $update_status = $this->db->set('status',3)->set('update_date',date("Y-m-d H:i:s"))->where('id',$id)->update('transaction_purchase');
        $txtDateInvoice = date("Y-m-d",strtotime("+0 day", strtotime($this->input->post('txtDateInvoice')))); 
        $txtDateInvoiceY = date("Y",strtotime("+0 day", strtotime($this->input->post('txtDateInvoice')))); 
        $txtDateInvoiceyk = date("y",strtotime("+0 day", strtotime($this->input->post('txtDateInvoice')))); 
        $getPurchase = $this->model_purchase->getPurchaseByID($id)->row();
        $note = "";
        $getPerusahaan = $this->model_master->getPerusahaanByID($getPurchase->perusahaan_id)->row();

        $namaPerus = $this->model_master->getPerusahaanByID($getPurchase->perusahaan_id)->row();
        $namacut = $namaPerus->name;
        $namapt = substr($namacut,0,3);
        $nama = substr($namacut,4);
        $arr = explode(' ', $nama);
        $singkatan = "";
        foreach($arr as $kata)
        {
        $singkatan .= substr($kata, 0, 1);
        }

        $namapt = $namapt."".strtoupper($singkatan);

        $getExpedisi = $this->model_master->getExpedisiById($getPurchase->expedisi)->row();
        if($getPurchase->expedisi_via == "" || $getPurchase->expedisi_via == "0" || $getPurchase->expedisi_via == 0){
            $getViaExpedisi = "";
        }else{
            $getViaExpedisi = $this->model_master->getExpedisiById($getPurchase->expedisi_via)->row()->name;
        }
        $getInvoice = $this->model_invoice->getInvoiceDescByPerusahaanAndYear($getPurchase->perusahaan_id,$txtDateInvoiceY);
        $cutNo = explode("/",$getInvoice->row()->nonota);
        //echo $v[0];
        if($getInvoice->num_rows() > 0){
                $genUnik = $cutNo[0] + 1;
        }else{
                $genUnik = 1;
        }
        //$number_invoice = sprintf("%'.05d", $genUnik)."/".$namapt."/".date('m')."/".date('y')."";
        $number_invoice = sprintf("%'.05d", $genUnik)."/".$namapt."/".date("m",strtotime("+0 day", strtotime($this->input->post('txtDateInvoice'))))."/".$txtDateInvoiceyk."";
        //$number_invoice = str_replace("PO","INV",$getPurchase->nonota);
        $cekDataInv = $this->model_purchase->getInvoiceByNoNota($number_invoice);
        //echo $number_invoice;
        // $ppn = $getPurchase->total * 10 / 100;
        // $grandTotal = $getPurchase->total + $ppn;
        $ppn = $txtTotalAfter * 10 / 100;
        $grandTotal = $txtTotalAfter + $ppn;

        if($cekDataInv->num_rows() > 0){
        echo "2";
        }else{
        // $insert_purchase = $this->db->set('nonota',$number_invoice)
        //                             ->set('dateorder',$txtDateInvoice)
        //                             ->set('member_id',$getPurchase->member_id)
        //                             ->set('purchase_no',$getPurchase->nonota)
        //                             ->set('member_name',$getPurchase->nama_member)
        //                             ->set('sales_id',$getPurchase->sales_id)
        //                             ->set('sales_name',$getPurchase->nama_sales)
        //                             ->set('perusahaan_name',$getPerusahaan->name)
        //                             ->set('perusahaan_id',$getPurchase->perusahaan_id)
        //                             ->set('sub_total',$getPurchase->sub_total)
        //                             ->set('discount',$getPurchase->discount)
        //                             ->set('total_before_ppn',$getPurchase->total)
        //                             ->set('total',$grandTotal)
        //                             ->set('note',$note)
        //                             ->set('expedisi',$getExpedisi->name)
        //                             ->set('via_expedisi',$getViaExpedisi)
        //                             ->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime($this->input->post('txtDateInvoice')))))
        //                             ->set('min_duedate',date("Y-m-d",strtotime("+90 day", strtotime($this->input->post('txtDateInvoice')))))
        //                             ->set('pay_status',0)
        //                             ->set('dateprint',$txtDateInvoice)
        //                             ->set('create_date',$txtDateInvoice)
        //                             ->set('create_user',$_SESSION['rick_auto']['username'])
        //                             ->insert('invoice');
        $insert_purchase = $this->db->set('nonota',$number_invoice)
                                    ->set('dateorder',$txtDateInvoice)
                                    ->set('invoice_date_tt',$txtDateInvoice)
                                    ->set('member_id',$getPurchase->member_id)
                                    ->set('purchase_no',$getPurchase->nonota)
                                    ->set('member_name',$getPurchase->nama_member)
                                    ->set('sales_id',$getPurchase->sales_id)
                                    ->set('sales_name',$getPurchase->nama_sales)
                                    ->set('perusahaan_name',$getPerusahaan->name)
                                    ->set('perusahaan_id',$getPurchase->perusahaan_id)
                                    ->set('sub_total',$getPurchase->sub_total)
                                    ->set('discount',$txtDiskonRupiah)
                                    ->set('total_before_ppn',$txtTotalAfter)
                                    ->set('total',$grandTotal)
                                    ->set('total_before_diskon',$grandTotal)
                                    ->set('note',$note)
                                    ->set('expedisi',$getExpedisi->name)
                                    ->set('via_expedisi',$getViaExpedisi)
                                    ->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime($this->input->post('txtDateInvoice')))))
                                    ->set('min_duedate',date("Y-m-d",strtotime("+90 day", strtotime($this->input->post('txtDateInvoice')))))
                                    ->set('pay_status',0)
                                    ->set('dateprint',$txtDateInvoice)
                                    ->set('create_date',$txtDateInvoice)
                                    ->set('create_user',$_SESSION['rick_auto']['username'])
                                    ->insert('invoice');
        $id_invoice = $this->db->insert_id();
        if($insert_purchase){
            $getDetailPurchase = $this->model_purchase->getPurchaseDetailByPurchase($id);
            $sub_total = 0;
            $grand_total = 0;
            foreach($getDetailPurchase->result() as $detailPurchase){
                $ttl_price = $detailPurchase->price * $detailPurchase->qty_kirim;
                $sub_total = $sub_total + $detailPurchase->price;
                $grand_total = $grand_total + $ttl_price;
                if($detailPurchase->qty_kirim == 0 || $detailPurchase->qty_kirim == ""){

                }else{
                $insert_detail = $this->db->set('invoice_id',$id_invoice)->set('product_code',$detailPurchase->kode_produk)->set('product_name',$detailPurchase->nama_produk)->set('qty',$detailPurchase->qty)->set('qty_kirim',$detailPurchase->qty_kirim)->set('colly',$detailPurchase->colly)->set('colly_to',$detailPurchase->colly_to)->set('weight',$detailPurchase->weight)->set('price',$detailPurchase->price)->set('ttl_price',$ttl_price)->set('satuan',$detailPurchase->nama_satuan)->set('deskripsi',$detailPurchase->deskripsi_produk)->set('product_img',$detailPurchase->gambar_cover)->set('gudang_id',$detailPurchase->gudang_id)->insert('invoice_detail');
                }
            }
            
            $ppn = $grand_total * 10 / 100;
            $grand_Total_semua = $grand_total + $ppn;

            //$update_status = $this->db->set('status',3)->where('id',$id)->update('transaction_purchase');
            if($update_status){
                $st = "Dibuat";
                $ket = "Invoice telah ".$st." oleh ".$_SESSION['rick_auto']['username']." dengan Nomor INVOICE : ".$number_invoice."";
                $insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('purchase_id',$id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('transaction_purchase_log');

                $insert_role = $this->db->set('no_transaction',$number_invoice)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','INV - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');

                $insert_role2 = $this->db->set('no_transaction',$getPurchase->nonota)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','INV - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
                

                $updateInvoice = $this->db->set('sub_total',$sub_total)->set('total_before_ppn',$txtTotalAfter)->set('total',$grandTotal)->where('id',$id_invoice)->update('invoice');
                
                }
        }
        echo "1";
        }
        ///if($insert_log){
                    
                   // }
    }

    public function process_batal(){
        $id = $this->input->post('id');
        $txtNoteCancel = $this->input->post('txtNoteCancel');
        $cekData = $this->model_purchase->getPurchaseTempByID($id)->row();
        if($cekData->status == 0){
            $update = $this->db->set('status',2)->set('note',$txtNoteCancel)->where('id',$id)->update('transaction_purchase_temporary');
        }else{
            $update = $this->db->set('status',2)->where('id',$id)->update('transaction_purchase_temporary');
        }

        $insert_role = $this->db->set('no_transaction',$cekData->notransaction)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Confirm Batal PO - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
        if($update){
            echo "1";
        }
    }



    public function pilih_perusahaan(){
        $id = $this->input->post('id');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $txtProduk = $this->input->post('txtProduk');
        $txtQty = $this->input->post('txtQty');
        $idProdukShadow = $this->input->post('idProdukShadow');
        $isliner = $this->input->post('isliner');
        if($isliner == "Y"){
            $cekProduk = $this->model_produk->getProductById($txtProduk)->row();
            $cekProdukk = $this->model_produk->getProductsById($txtProduk)->row();
            $getKodeBayangan = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Pcs")->row();
            $getKodeBayanganSet = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Set")->row();
            if($cekProdukk->nama_satuan == "Pcs"){
                $getGudang = $this->model_master->getCekStokGudangbyProductPerusahaanLiner($getKodeBayangan->id,$cmbPerusahaan,$txtQty);
            }else{
            $st = $txtQty * $getKodeBayanganSet->satuan_value;
            $getGudang = $this->model_master->getCekStokGudangbyProductPerusahaanLiner($getKodeBayangan->id,$cmbPerusahaan,$st);
            }
        }else{
            $getGudang = $this->model_master->getCekStokGudangbyProductPerusahaan($txtProduk,$cmbPerusahaan,$txtQty);
        }
        
        echo"
        <select class='form-control' id='cmbGudang_".$id."' name='cmbGudang_".$id."'>
            <option value='0' selected>Pilih Gudang</option>
            ";foreach($getGudang->result() as $gudang){
                echo"
                    <option value='".$gudang->id_gudang."'>".$gudang->nama_gudang." (".$gudang->stok_gudang.")</option>
                ";
            }echo"
        </select>
        ";
    }

    public function pilih_perusahaan_(){
        $id = $this->input->post('id');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $getGudang = $this->model_master->getGudangbyPerusahaan($cmbPerusahaan);
        
        echo"
        <select class='form-control' id='cmbGudang_".$id."' name='cmbGudang_".$id."'>
            <option value='0' selected>Pilih Gudang</option>
            ";foreach($getGudang->result() as $gudang){
                echo"
                    <option value='".$gudang->id."'>".$gudang->nama_gudang."</option>
                ";
            }echo"
        </select>";
    }

    public function pilih_perusahaan__(){
        $id = $this->input->post('id');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $txtProduk = $this->input->post('txtProduk');
        $txtQty = $this->input->post('txtQty');
        $getGudang = $this->model_master->getCekStokGudangbyProductPerusahaan($txtProduk,$cmbPerusahaan,$txtQty);
        
        echo"
        <select class='form-control' id='cmbGudang_".$id."' name='cmbGudang_".$id."'>
            <option value='0' selected>Pilih Gudang</option>
            ";foreach($getGudang->result() as $gudang){
                echo"
                    <option value='".$gudang->id_gudang."'>".$gudang->nama_gudang." (".$gudang->stok_gudang.")</option>
                ";
            }echo"
        </select>";
    }

    public function process_po_(){
        $id = $this->input->post('id');

        $getDetail = $this->model_purchase->getReqPurchaseDetailByPurchase($id);
        $totalPeru = 0;
        foreach($getDetail->result() as $detail){
            $txtPerusahaan = $this->input->post('txtPerusahaan_'.$detail->id);
            $cmbPerusahaan = $this->input->post('cmbPerusahaan_'.$detail->id);
            $getPerusahaanById = $this->model_master->getPerusahaanByID($cmbPerusahaan);
            $getPerusahaan = $this->model_master->getPerusahaanByID($cmbPerusahaan);
            $total_unit = 0;
            $total_semua = 0;
            foreach($getPerusahaanById->result() as $perusahaan){
                $txtTotalSatuan = $this->input->post('txtTotalSatuan_'.$detail->id);
                $total_unit = $total_unit + $txtTotalSatuan;
                $txtTotal = $this->input->post('txtTotal_'.$detail->id);
                $total_semua = $total_semua + $txtTotal;
            }
           // echo $total_semua;
           // $insert_temp_detail = $this->db->set('perusahaan_id',$)
            $totalPeru = $txtPerusahaan;
        }

        echo $totalPeru;

    }

    public function process_po__(){
        $id = $this->input->post('id');
        $getTempById = $this->model_purchase->getReqPurchaseByID($id)->row();
        $getDetail = $this->model_purchase->getReqPurchaseDetailByPurchase($id);
        foreach($getDetail->result() as $detail){
            $txtProduk = $this->input->post('txtProduk_'.$detail->id);
            $txtQty = $this->input->post('txtQty_'.$detail->id);
            $txtTotalSatuan = $this->input->post('txtTotalSatuan_'.$detail->id);
            $txtTotal = $this->input->post('txtTotal_'.$detail->id);
            $cmbPerusahaan = $this->input->post('cmbPerusahaan_'.$detail->id);
            $txtDiscount = $this->input->post('txtDiscount_'.$detail->id);
            $cmbGudang = $this->input->post('cmbGudang_'.$detail->id);
            $namaSatuan = $this->input->post('namaSatuan_'.$detail->id);
            $cekProduks = $this->model_produk->getProductById($txtProduk)->row();
            if($cekProduks->is_liner == "Y"){
            $idProdukShadow = $this->input->post('idProdukShadow_'.$detail->id);
            }else{
            $idProdukShadow = "";    
            }
            
            if($cmbGudang == "" || $cmbGudang == null || $cmbGudang == 0){
                $insert_proses = "";
            }else{
            $insert_proses = $this->db->set('perusahaan_id',$cmbPerusahaan)->set('product_id',$txtProduk)->set('product_id_shadow',$idProdukShadow)->set('qty',$txtQty)->set('price',$txtTotalSatuan)->set('discount',$txtDiscount)->set('ttl_price',$txtTotal)->set('gudang_id',$cmbGudang)->set('satuan',$namaSatuan)->insert('transaction_purchase_temporary_process');
            }
        }      

        $getProses = $this->model_purchase->getTemporaryProcessGroup();

        $getPurchase = $this->model_purchase->getPurchaseByIdDesc()->row();
        $nopoplus = 0;
        foreach($getProses->result() as $gProses){
            $namaPerus = $this->model_master->getPerusahaanByID($gProses->perusahaan_id)->row();
            $namacut = $namaPerus->name;
            $namapt = substr($namacut,0,3);
            $nama = substr($namacut,4);
            $arr = explode(' ', $nama);
            $singkatan = "";
            foreach($arr as $kata)
            {
            $singkatan .= substr($kata, 0, 1);
            }

            $namapt = $namapt."".strtoupper($singkatan);
            $dataSales = $this->model_master->getSalesById($getTempById->sales_id)->row();
            $namaSales = substr($dataSales->name,0,3);
            $nopoplus++;
            $getPurchasee = $this->model_purchase->getPurchaseByPerusahaan($namapt);
            $getPurchasee = $this->model_purchase->getPurchaseByPerusahaan($namapt);
            $noGen = explode("/",$getPurchasee->row()->nonota);
            $noPODesc = substr($noGen[0], 5);
            if($getPurchasee->num_rows() > 0){
                $genUnik = $noPODesc + 1;
            }else{
                $genUnik = 1;
            }
            
            $nopo = $namapt."".sprintf("%'.05d", $genUnik)."/".strtoupper($namaSales)."/".date('m')."/".date('y')."";
            //$nopo = "PO".date('dmy')."".sprintf("%'.05d", $genUnik)."";
            $insert_po = $this->db->set('perusahaan_id',$gProses->perusahaan_id)->set('member_id',$getTempById->member_id)->set('sales_id',$getTempById->sales_id)->set('expedisi',$getTempById->expedisi)->set('expedisi_via',$getTempById->expedisi_via)->set('nonota',$nopo)->set('dateorder',date("Y-m-d H:i:s"))->set('sub_total',$gProses->total_unit)->set('note',$getTempById->note)->set('total',$gProses->total_semua)->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime(date("Y-m-d H:i:s")))))->set('pay_status',0)->set('createdby',$_SESSION['rick_auto']['fullname'])->set('createdon',$_SESSION['rick_auto']['username'])->insert('transaction_purchase');
            $id_recent = $this->db->insert_id();
            $getPurchaseData = $this->model_purchase->getPurchaseByID($id_recent);
            $getProses_perusahaan = $this->model_purchase->getTemporaryProcessByPerusahaan($getPurchaseData->row()->perusahaan_id);
            foreach($getProses_perusahaan->result() as $prosesDetail){
                $insert_po_detail = $this->db->set('transaction_purchase_id',$id_recent)->set('gudang_id',$prosesDetail->gudang_id)->set('product_id',$prosesDetail->product_id)->set('product_id_shadow',$prosesDetail->product_id_shadow)->set('qty',$prosesDetail->qty)->set('discount',$prosesDetail->discount)->set('qty',$prosesDetail->qty)->set('price',$prosesDetail->price)->set('ttl_price',$prosesDetail->ttl_price)->set('satuan',$prosesDetail->satuan)->insert('transaction_purchase_detail');
                if($insert_po_detail){
                    $getInvoice = $this->model_purchase->getPurchaseByID($id_recent)->row();
                    $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id_recent);
                    foreach($detailInvoice->result() as $detailInv){
                    if($detailInv->product_id_shadow == ""){
                        $data_produk = $this->model_produk->getProductById($detailInv->product_id)->row();
                    }else{
                        $data_produk = $this->model_produk->getProductById($detailInv->product_id_shadow)->row();
                    }
                    
                    //print_r($detailInv->qty_kirim);
                    if($detailInv->product_id_shadow == ""){
                        $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                        $pengurangan_stok = $cekStok->stok - $detailInv->qty;
                        $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                    $insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('transaction_no',$getInvoice->nonota)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                    }else{
                        
                        $cekProduk = $this->model_produk->getProductById($detailInv->product_id)->row();
                        $getKodeBayangan = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Pcs")->row();
                        $getKodeBayanganSet = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Set")->row();
                        //$st = $txtQty * $getKodeBayanganSet->satuan_value;
                        $cekProdukk = $this->model_produk->getProductsById($detailInv->product_id)->row();
                        if($detailInv->satuan == "Pcs"){
                        //$cekStok = $this->model_master->getGudangbyProductPerusahaan($detailInv->product_id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                        $cekStok = $this->model_master->getGudangbyProductPerusahaan($getKodeBayangan->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                        $cekProduk_ = $this->model_produk->getProductById($getKodeBayangan->id)->row();
                        $qtyKurangLiner = $detailInv->qty;
                        $pengurangan_stok = $cekStok->stok - $qtyKurangLiner;
                        $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                        $insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('transaction_no',$getInvoice->nonota)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                        }else{
                        $cekProduk_ = $this->model_produk->getProductById($getKodeBayanganSet->id)->row();
                        $cekStok = $this->model_master->getGudangbyProductPerusahaan($getKodeBayangan->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                        $qtyKurangLiner = $detailInv->qty * $cekProduk_->satuan_value;
                        $pengurangan_stok = $cekStok->stok - $qtyKurangLiner;
                        //echo $pengurangan_stok;
                        $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                        $insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('transaction_no',$getInvoice->nonota)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                        }
                    }
                }
                    //  echo "".$detailInv->qty_kirim."";
                    

                    // $insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                    }
            }
        }
        if($insert_opname_stok_bm){
            // $insert_role = $this->db->set('no_transaction',$nopo)
            //                     ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
            //                     ->set('user',$_SESSION['rick_auto']['fullname'])
            //                     ->set('action','PO - '.$_SESSION['rick_auto']['fullname'])
            //                     ->set('create_date',date("Y-m-d H:i:s"))
            //                     ->insert('role_transaksi');

        echo "1";
        }
    }

    public function process_po(){
        $id = $this->input->post('id');
        $getTempById = $this->model_purchase->getReqPurchaseByID($id)->row();
        $getDetail = $this->model_purchase->getReqPurchaseDetailByPurchase($id);
        foreach($getDetail->result() as $detail){
            $txtProduk = $this->input->post('txtProduk_'.$detail->id);
            $txtQty = $this->input->post('txtQty_'.$detail->id);
            $txtTotalSatuan = $this->input->post('txtTotalSatuan_'.$detail->id);
            $txtafterUnitTotal = $this->input->post('txtafterUnitTotal_'.$detail->id);
            $txtTotal = $this->input->post('txtTotal_'.$detail->id);
            $cmbPerusahaan = $this->input->post('cmbPerusahaan_'.$detail->id);
            $txtDiscount = $this->input->post('txtDiscount_'.$detail->id);
            $cmbGudang = $this->input->post('cmbGudang_'.$detail->id);
            $namaSatuan = $this->input->post('namaSatuan_'.$detail->id);
            $cekProduks = $this->model_produk->getProductById($txtProduk)->row();
            if($cekProduks->is_liner == "Y"){
            $idProdukShadow = $this->input->post('idProdukShadow_'.$detail->id);
            }else{
            $idProdukShadow = "";    
            }
            
            if($cmbGudang == "" || $cmbGudang == null || $cmbGudang == 0){
            $insert_proses = $this->db->set('perusahaan_id',$cmbPerusahaan)->set('product_id',$txtProduk)->set('product_id_shadow',$idProdukShadow)->set('no_rpo',$getTempById->notransaction)->set('qty',$txtQty)->set('price',$txtafterUnitTotal)->set('discount',$txtDiscount)->set('ttl_price',$txtTotal)->set('gudang_id',$cmbGudang)->set('satuan',$namaSatuan)->insert('transaction_purchase_temporary_process_bo');

                    //}

                    // if($updatePo){

                    //     echo "1";
                    //     //echo json_encode(array('msg'=>1,'idPO'=>$id_recent));
                    // }
                //}
            }else{
            $insert_proses = $this->db->set('perusahaan_id',$cmbPerusahaan)->set('product_id',$txtProduk)->set('product_id_shadow',$idProdukShadow)->set('qty',$txtQty)->set('price',$txtafterUnitTotal)->set('discount',$txtDiscount)->set('ttl_price',$txtTotal)->set('gudang_id',$cmbGudang)->set('satuan',$namaSatuan)->insert('transaction_purchase_temporary_process');
            }
        }      

        $getProses = $this->model_purchase->getTemporaryProcessGroup();

        $getPurchase = $this->model_purchase->getPurchaseByIdDesc()->row();
        $nopoplus = 0;
        foreach($getProses->result() as $gProses){
            $namaPerus = $this->model_master->getPerusahaanByID($gProses->perusahaan_id)->row();
            $namacut = $namaPerus->name;
            $namapt = substr($namacut,0,3);
            $nama = substr($namacut,4);
            $arr = explode(' ', $nama);
            $singkatan = "";
            foreach($arr as $kata)
            {
            $singkatan .= substr($kata, 0, 1);
            }

            $namapt = $namapt."".strtoupper($singkatan);
            $dataSales = $this->model_master->getSalesById($getTempById->sales_id)->row();
            $namaSales = substr($dataSales->name,0,3);
            $nopoplus++;
            // $getPurchasee = $this->model_purchase->getPurchaseByPerusahaan($namapt);
            // $getPurchasee = $this->model_purchase->getPurchaseByPerusahaan($namapt);
            $getPurchasee = $this->model_purchase->getPurchaseByPerusahaanYear($namapt,date('Y'));
            $noGen = explode("/",$getPurchasee->row()->nonota);
            $noPODesc = substr($noGen[0], 5);
            if($getPurchasee->num_rows() > 0){
                $genUnik = $noPODesc + 1;
            }else{
                $genUnik = 1;
            }
            
            $nopo = $namapt."".sprintf("%'.05d", $genUnik)."/".strtoupper($namaSales)."/".date('m')."/".date('y')."";
            //$nopo = "PO".date('dmy')."".sprintf("%'.05d", $genUnik)."";
            $insert_po = $this->db->set('perusahaan_id',$gProses->perusahaan_id)->set('member_id',$getTempById->member_id)->set('kode_rpo',$getTempById->notransaction)->set('sales_id',$getTempById->sales_id)->set('expedisi',$getTempById->expedisi)->set('expedisi_via',$getTempById->expedisi_via)->set('nonota',$nopo)->set('dateorder',date("Y-m-d H:i:s"))->set('sub_total',$gProses->total_unit)->set('note',$getTempById->note)->set('total',$gProses->total_semua)->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime(date("Y-m-d H:i:s")))))->set('pay_status',0)->set('createdby',$_SESSION['rick_auto']['fullname'])->set('createdon',$_SESSION['rick_auto']['username'])->insert('transaction_purchase');
            $id_recent = $this->db->insert_id();
            $getPurchaseData = $this->model_purchase->getPurchaseByID($id_recent);
            $getProses_perusahaan = $this->model_purchase->getTemporaryProcessByPerusahaan($getPurchaseData->row()->perusahaan_id);
            $subTotal = 0;
            $totalSemua = 0;
            foreach($getProses_perusahaan->result() as $prosesDetail){
                $subTotal = $subTotal + $prosesDetail->price;
                $totalSemua = $totalSemua + $prosesDetail->ttl_price;
                $insert_po_detail = $this->db->set('transaction_purchase_id',$id_recent)->set('gudang_id',$prosesDetail->gudang_id)->set('product_id',$prosesDetail->product_id)->set('product_id_shadow',$prosesDetail->product_id_shadow)->set('qty',$prosesDetail->qty)->set('discount',$prosesDetail->discount)->set('qty',$prosesDetail->qty)->set('price',$prosesDetail->price)->set('ttl_price',$prosesDetail->ttl_price)->set('satuan',$prosesDetail->satuan)->insert('transaction_purchase_detail');
                $idPOd = $this->db->insert_id();


            //$getInvoice = $this->model_purchase->getPurchaseByID($id_recent)->row();
            $detailInv = $this->model_purchase->getPurchaseDetailByPurchaseIDD($idPOd)->row();
            //foreach($detailInvoice->result() as $detailInv){
            if($detailInv->product_id_shadow == ""){
                $data_produk = $this->model_produk->getProductById($detailInv->product_id)->row();
            }else{
                $data_produk = $this->model_produk->getProductById($detailInv->product_id_shadow)->row();
            }
            
            //print_r($detailInv->qty_kirim);
            if($detailInv->product_id_shadow == ""){
                $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$gProses->perusahaan_id,$prosesDetail->gudang_id)->row();
                $pengurangan_stok = $cekStok->stok - $detailInv->qty;
                $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
            //$insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
            }else{
                
                $cekProduk = $this->model_produk->getProductById($detailInv->product_id)->row();
                $getKodeBayangan = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Pcs")->row();
                $getKodeBayanganSet = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Set")->row();
                //$st = $txtQty * $getKodeBayanganSet->satuan_value;
                $cekProdukk = $this->model_produk->getProductsById($detailInv->product_id)->row();
                if($detailInv->satuan == "Pcs"){
                    //$cekStok = $this->model_master->getGudangbyProductPerusahaan($detailInv->product_id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                    $cekStok = $this->model_master->getGudangbyProductPerusahaan($getKodeBayangan->id,$gProses->perusahaan_id,$prosesDetail->gudang_id)->row();
                    $cekProduk_ = $this->model_produk->getProductById($detailInv->product_id)->row();
                    $qtys = $detailInv->qty;
                    $pengurangan_stok = $cekStok->stok - $qtys;
                    $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                    //$insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                }else{
                    $cekProduk_ = $this->model_produk->getProductById($detailInv->product_id_shadow)->row();
                    $cekStok = $this->model_master->getGudangbyProductPerusahaan($detailInv->product_id_shadow,$gProses->perusahaan_id,$detailInv->gudang_id)->row();
                    $qtyKurangLiner = $detailInv->qty * $getKodeBayanganSet->satuan_value;
                    $pengurangan_stok = $cekStok->stok - $qtyKurangLiner;
                    //echo $pengurangan_stok;
                    //echo $cekStok->stok."-".$qtyKurangLiner;
                    $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                    //$insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                }
            }
        //}



            }
            $updatePo = $this->db->set('sub_total',$subTotal)->set('total',$totalSemua)->where('id',$id_recent)->set('update_date',date("Y-m-d H:i:s"))->update('transaction_purchase');
        }


        //if($updatePo){
                    $getTempBo = $this->model_purchase->getTemporaryProcessBO();
                    if($getTempBo->num_rows() > 0){
                    $norpo = $getTempBo->row()->no_rpo;
                    $norpoOut = "BO-".$getTempBo->row()->no_rpo;
                    $cekBo = $this->model_purchase->getTemporaryByNoBO($norpoOut);
                    // if($cekBo->num_rows() > 0){

                    // }else{
                    $insert_po_bo = $this->db->set('perusahaan_id',$getTempById->perusahaan_id)->set('member_id',$getTempById->member_id)->set('sales_id',$getTempById->sales_id)->set('expedisi',$getTempById->expedisi_id)->set('notransaction',$norpoOut)->set('dateorder',date("Y-m-d H:i:s"))->set('sub_total',$getTempById->sub_total)->set('total',$getTempById->total)->set('expedisi',$getTempById->expedisi)->set('expedisi_via',$getTempById->expedisi_via)->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime(date("Y-m-d H:i:s")))))->set('createdby',$_SESSION['rick_auto']['fullname'])->set('createdon',$_SESSION['rick_auto']['username'])->set('flag_bo',1)->insert('transaction_purchase_temporary');
                    $idPObo = $this->db->insert_id();
                    $subTotals = 0;
                    $totals = 0;
                    foreach($getTempBo->result() as $tempBo){
                        $insert_po_detail_bo = $this->db->set('transaction_purchase_temporary_id',$idPObo)->set('product_id',$tempBo->product_id)->set('qty',$tempBo->qty)->set('price',$tempBo->price)->set('ttl_price',$tempBo->ttl_price)->insert('transaction_purchase_temporary_detail');
                        $subTotals = $subTotals + $tempBo->price;
                        $totals = $totals + $tempBo->ttl_price;
                    }

                    $updatePo = $this->db->set('sub_total',$subTotals)->set('total',$totals)->where('id',$idPObo)->update('transaction_purchase_temporary');
                    $insert_role = $this->db->set('no_transaction',$norpoOut)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','BO - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');

        
    //     //echo json_encode(array('msg'=>1,'idPO'=>$id_recent));
    }

    echo "1";
       // }
    }

    public function cutStok_(){
                        $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id);
                            foreach($detailInvoice->result() as $detailInv){
                                $total_price = $detailInv->qty_kirim * $detailInv->price;
                                $update = $this->db->set('ttl_price',$total_price)->where('id',$detailInv->id)->update('transaction_purchase_detail');
                                if($detailInv->qty_kirim == $detailInv->qty){

                                }else{

                                if($detailInv->product_id_shadow == ""){
                                    $data_produk = $this->model_produk->getProductById($detailInv->product_id)->row();
                                }else{
                                    $data_produk = $this->model_produk->getProductById($detailInv->product_id_shadow)->row();
                                }
                                
                                //print_r($detailInv->qty_kirim);
                                if($detailInv->product_id_shadow == ""){
                                    $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                                    $pengurangan_stok = $cekStok->stok - $detailInv->qty;
                                    $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                                    $insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Masuk')->set('keterangan','Purchase Masuk')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                                    }else{
                                    
                                    $cekProduk = $this->model_produk->getProductById($detailInv->product_id)->row();
                                    $getKodeBayangan = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Pcs")->row();
                                    $getKodeBayanganSet = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Set")->row();
                                    //$st = $txtQty * $getKodeBayanganSet->satuan_value;
                                    $cekProdukk = $this->model_produk->getProductsById($detailInv->product_id)->row();
                                    if($detailInv->satuan == "Pcs"){
                                    //$cekStok = $this->model_master->getGudangbyProductPerusahaan($detailInv->product_id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                                    $cekStok = $this->model_master->getGudangbyProductPerusahaan($getKodeBayangan->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                                    $cekProduk_ = $this->model_produk->getProductById($getKodeBayangan->id)->row();
                                    $qtyKurangLiner = $detailInv->qty;
                                        $kurangStok = $detailInv->qty;
                                        $pengurangan_stok = $cekStok->stok + $kurangStok;
                                    
                                    $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                                    $insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Masuk')->set('keterangan','Purchase Masuk')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                                    }else{
                                    $cekProduk_ = $this->model_produk->getProductById($getKodeBayanganSet->id)->row();
                                    $cekStok = $this->model_master->getGudangbyProductPerusahaan($getKodeBayangan->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                                        $qtyKurangLiner = $kurangStok * $cekProduk_->satuan_value;
                                        $pengurangan_stok = $cekStok->stok - $qtyKurangLiner;
                                    
                                    //echo $pengurangan_stok;
                                    $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                                    $insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Masuk')->set('keterangan','Purchase Masuk')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                                    }
                                }



                                }
                                
                            }
    }

    public function restoreStok($id_recent){
            $getInvoice = $this->model_purchase->getPurchaseByID($id_recent)->row();
            $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id_recent);
            foreach($detailInvoice->result() as $detailInv){
            if($detailInv->product_id_shadow == ""){
                $data_produk = $this->model_produk->getProductById($detailInv->product_id)->row();
            }else{
                $data_produk = $this->model_produk->getProductById($detailInv->product_id_shadow)->row();
            }
            
            //print_r($detailInv->qty_kirim);
            if($detailInv->product_id_shadow == ""){
                $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                $pengurangan_stok = $cekStok->stok + $detailInv->qty;
                $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
            //$insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
            }else{
                
                $cekProduk = $this->model_produk->getProductById($detailInv->product_id)->row();
                $getKodeBayangan = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Pcs")->row();
                $getKodeBayanganSet = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Set")->row();
                //$st = $txtQty * $getKodeBayanganSet->satuan_value;
                $cekProdukk = $this->model_produk->getProductsById($detailInv->product_id)->row();
                if($detailInv->satuan == "Pcs"){
                //$cekStok = $this->model_master->getGudangbyProductPerusahaan($detailInv->product_id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                $cekStok = $this->model_master->getGudangbyProductPerusahaan($getKodeBayangan->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                $cekProduk_ = $this->model_produk->getProductById($detailInv->product_id)->row();
                $qtys = $detailInv->qty;
                $pengurangan_stok = $cekStok->stok + $qtys;
                $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                //$insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                }else{
                $cekProduk_ = $this->model_produk->getProductById($detailInv->product_id_shadow)->row();
                $cekStok = $this->model_master->getGudangbyProductPerusahaan($detailInv->product_id_shadow,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
                $qtyKurangLiner = $detailInv->qty * $getKodeBayanganSet->satuan_value;
                $pengurangan_stok = $cekStok->stok + $qtyKurangLiner;
                //echo $pengurangan_stok;
                $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                //$insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                }
            }
        }
        echo "1";
    }

    public function cutStok(){
        //     $id_recent = $this->input->post('id');
        //     $getInvoice = $this->model_purchase->getPurchaseByID($id_recent)->row();
        //     $detailInvoice = $this->model_purchase->getPurchaseDetailByPurchaseID($id_recent);
        //     foreach($detailInvoice->result() as $detailInv){
        //     if($detailInv->product_id_shadow == ""){
        //         $data_produk = $this->model_produk->getProductById($detailInv->product_id)->row();
        //     }else{
        //         $data_produk = $this->model_produk->getProductById($detailInv->product_id_shadow)->row();
        //     }
            
        //     //print_r($detailInv->qty_kirim);
        //     if($detailInv->product_id_shadow == ""){
        //         $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
        //         $pengurangan_stok = $cekStok->stok - $detailInv->qty;
        //         $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
        //     //$insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
        //     }else{
                
        //         $cekProduk = $this->model_produk->getProductById($detailInv->product_id)->row();
        //         $getKodeBayangan = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Pcs")->row();
        //         $getKodeBayanganSet = $this->model_produk->getProductsByKodeAndSatuan($cekProduk->product_code_shadow,"Set")->row();
        //         //$st = $txtQty * $getKodeBayanganSet->satuan_value;
        //         $cekProdukk = $this->model_produk->getProductsById($detailInv->product_id)->row();
        //         if($detailInv->satuan == "Pcs"){
        //         //$cekStok = $this->model_master->getGudangbyProductPerusahaan($detailInv->product_id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
        //         $cekStok = $this->model_master->getGudangbyProductPerusahaan($getKodeBayangan->id,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
        //         $cekProduk_ = $this->model_produk->getProductById($detailInv->product_id)->row();
        //         $qtys = $detailInv->qty;
        //         $pengurangan_stok = $cekStok->stok - $qtys;
        //         $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
        //         //$insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
        //         }else{
        //         $cekProduk_ = $this->model_produk->getProductById($detailInv->product_id_shadow)->row();
        //         $cekStok = $this->model_master->getGudangbyProductPerusahaan($detailInv->product_id_shadow,$getInvoice->perusahaan_id,$detailInv->gudang_id)->row();
        //         $qtyKurangLiner = $detailInv->qty * $getKodeBayanganSet->satuan_value;
        //         $pengurangan_stok = $cekStok->stok - $qtyKurangLiner;
        //         //echo $pengurangan_stok;
        //         $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
        //         //$insert_opname_stok_bm = $this->db->set('product_id',$detailInv->product_id)->set('gudang_id',$detailInv->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$detailInv->qty)->set('purchase_detail_id',$detailInv->id)->set('note','Purchase Barang Keluar')->set('keterangan','Purchase Keluar')->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
        //         }
        //     }
        // }
        echo "1";
    }

    public function delete_temporary(){
        $id = $this->input->post('id');
        $delete_temporary = $this->db->query("delete from transaction_purchase_temporary where id=".$id."");
        $delete_temporary_detail = $this->db->query("delete from transaction_purchase_temporary_detail where transaction_purchase_temporary_id=".$id."");
        $delete = $this->db->query("delete from transaction_purchase_temporary_process where 1=1");
        $delete2 = $this->db->query("delete from transaction_purchase_temporary_process_bo where 1=1");
        echo "1";        
    }

    public function simpanPembuatanPO_(){
        $cmbMember  = $this->input->post('cmbMember');
        $cmbSales   = $this->input->post('cmbSales');
        $cmbExpedisi = $this->input->post('cmbExpedisi');
        $jmlProduk = $this->input->post('jmlProduk');
        $totals = 0;
        for($i=1;$i<=$jmlProduk;$i++){
            $cmbProduk = $this->input->post('cmbProduk_'.$i);
            $priceSatuan = str_replace(".","",$this->input->post('priceSatuan_'.$i));
            $addStok = $this->input->post('addStok_'.$i);
            $cmbPerusahaan = $this->input->post('cmbPerusahaan_'.$i);
            $cmbGudang = $this->input->post('cmbGudang_'.$i);
            $priceTotal = str_replace(".","",$this->input->post('priceTotal_'.$i));
                // $total_sub = 0;
                // $total_semua = 0;
                // $getPerusahaan = $this->model_master->getPerusahaanGroupBy($cmbPerusahaan);
                // foreach($getPerusahaan->result() as $perusahaan){
                //     $total_sub = $total_sub + $priceSatuan;
                //     $total_semua = $total_semua + $priceTotal;
                // }
                //$totals = $totals + $total_semua;
           // $insert = $this->db->set('');
            $insert_proses = $this->db->set('perusahaan_id',$cmbPerusahaan)->set('product_id',$cmbProduk)->set('qty',$addStok)->set('price',$priceSatuan)->set('ttl_price',$priceTotal)->set('gudang_id',$cmbGudang)->insert('transaction_purchase_temporary_process');
        }

        $getProses = $this->model_purchase->getTemporaryProcessGroup();

        $getPurchase = $this->model_purchase->getPurchaseByIdDesc()->row();
        $nopoplus = 0;
        foreach($getProses->result() as $gProses){
            $nopoplus++;
            $genUnik = $getPurchase->id + $nopoplus;
            $nopo = "PO".date('dmy')."".sprintf("%'.05d", $genUnik)."";
            $insert_po = $this->db->set('perusahaan_id',$gProses->perusahaan_id)->set('member_id',$cmbMember)->set('sales_id',$cmbSales)->set('expedisi',$cmbExpedisi)->set('nonota',$nopo)->set('dateorder',date("Y-m-d H:i:s"))->set('sub_total',$gProses->total_unit)->set('total',$gProses->total_semua)->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime(date("Y-m-d H:i:s")))))->set('pay_status',0)->set('createdby',$_SESSION['rick_auto']['fullname'])->set('createdon',$_SESSION['rick_auto']['username'])->insert('transaction_purchase');
            $id_recent = $this->db->insert_id();
            $getPurchaseData = $this->model_purchase->getPurchaseByID($id_recent);
            $getProses_perusahaan = $this->model_purchase->getTemporaryProcessByPerusahaan($getPurchaseData->row()->perusahaan_id);
            foreach($getProses_perusahaan->result() as $prosesDetail){
                $insert_po = $this->db->set('transaction_purchase_id',$id_recent)->set('gudang_id',$prosesDetail->gudang_id)->set('product_id',$prosesDetail->product_id)->set('qty',$prosesDetail->qty)->set('price',$prosesDetail->price)->set('ttl_price',$prosesDetail->ttl_price)->insert('transaction_purchase_detail');
            }
        }
        if($insert_po){
        //$delete_temporary = $this->db->query("delete from transaction_purchase_temporary where id=".$id."");
        //$delete_temporary_detail = $this->db->query("delete from transaction_purchase_temporary_detail where transaction_purchase_temporary_id=".$id."");
        $delete = $this->db->query("delete from transaction_purchase_temporary_process where 1=1");

        echo "1";
        }

        
    }

    public function simpanPembuatanPO(){
        $cmbMember  = $this->input->post('cmbMember');
        $cmbSales   = $this->input->post('cmbSales');
        $cmbExpedisi = $this->input->post('cmbExpedisi');
        $cmbViaExpedisi = $this->input->post('cmbViaExpedisi');
        $note = $this->input->post('note');
        $jmlProduk = $this->input->post('jmlProduk');
        $totals = 0;
        for($i=1;$i<=$jmlProduk;$i++){
            $cmbProduk = $this->input->post('cmbProduk_'.$i);
            $priceSatuan = str_replace(".","",$this->input->post('priceSatuan_'.$i));
            $addStok = $this->input->post('addStok_'.$i);
            //$cmbPerusahaan = $this->input->post('cmbPerusahaan_'.$i);
            $cmbPerusahaan = 1;
            $cmbGudang = $this->input->post('cmbGudang_'.$i);
            $priceTotal = str_replace(".","",$this->input->post('priceTotal_'.$i));
                // $total_sub = 0;
                // $total_semua = 0;
                // $getPerusahaan = $this->model_master->getPerusahaanGroupBy($cmbPerusahaan);
                // foreach($getPerusahaan->result() as $perusahaan){
                //     $total_sub = $total_sub + $priceSatuan;
                //     $total_semua = $total_semua + $priceTotal;
                // }
                //$totals = $totals + $total_semua;
           // $insert = $this->db->set('');
            if($cmbProduk == 0 || $cmbProduk == ""){
            }else{
                $insert_proses = $this->db->set('perusahaan_id',$cmbPerusahaan)->set('product_id',$cmbProduk)->set('qty',$addStok)->set('price',$priceSatuan)->set('ttl_price',$priceTotal)->set('gudang_id',$cmbGudang)->insert('transaction_purchase_temporary_process');
            }
        }

        $getProses = $this->model_purchase->getTemporaryProcessGroup();

        $getPurchase = $this->model_purchase->getPurchaseTempByIdDesc();
        if($getPurchase->num_rows() > 0){
            $idDesc = $getPurchase->row()->id;
        }else{
            $idDesc = 0;
        }
        $nopoplus = 0;
        foreach($getProses->result() as $gProses){
            $nopoplus++;
            $genUnik =  $idDesc + $nopoplus;
            //$nopo = "PO".date('dmy')."".sprintf("%'.05d", $genUnik)."";
            $nopo = "".date('dmy')."".$cmbMember."".sprintf("%'.05d", $genUnik)."";
            $insert_po = $this->db->set('perusahaan_id',$gProses->perusahaan_id)->set('member_id',$cmbMember)->set('sales_id',$cmbSales)->set('expedisi',$cmbExpedisi)->set('expedisi_via',$cmbViaExpedisi)->set('notransaction',$nopo)->set('dateorder',date("Y-m-d H:i:s"))->set('sub_total',$gProses->total_unit)->set('total',$gProses->total_semua)->set('duedate',date("Y-m-d",strtotime("+120 day", strtotime(date("Y-m-d H:i:s")))))->set('pay_status',0)->set('createdby',$_SESSION['rick_auto']['fullname'])->set('note',$note)->set('createdon',$_SESSION['rick_auto']['username'])->set('access',"WEB")->insert('transaction_purchase_temporary');
            $id_recent = $this->db->insert_id();
            $getPurchaseData = $this->model_purchase->getPurchaseTempByID($id_recent);
            $getProses_perusahaan = $this->model_purchase->getTemporaryProcessByPerusahaan($getPurchaseData->row()->perusahaan_id);
            foreach($getProses_perusahaan->result() as $prosesDetail){
                $insert_po = $this->db->set('transaction_purchase_temporary_id',$id_recent)->set('product_id',$prosesDetail->product_id)->set('qty',$prosesDetail->qty)->set('price',$prosesDetail->price)->set('ttl_price',$prosesDetail->ttl_price)->insert('transaction_purchase_temporary_detail');
            }
            $insert_role = $this->db->set('no_transaction',$nopo)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','RPO - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
        }
        if($insert_po){
        //$delete_temporary = $this->db->query("delete from transaction_purchase_temporary where id=".$id."");
        //$delete_temporary_detail = $this->db->query("delete from transaction_purchase_temporary_detail where transaction_purchase_temporary_id=".$id."");
        $delete = $this->db->query("delete from transaction_purchase_temporary_process where 1=1");
        

        echo "1";
        }

        
    }

    public function monthly_report(){
        $this->data['getData'] = $this->model_purchase->getMonthlyPurchase();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->template->rick_auto('purchase/bg_report_monthly',$this->data);
    }

    public function filter_po(){
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbBulan = $this->input->post('cmbBulan');
        $hari = date("Y-m-d",strtotime("+0 day",strtotime($cmbBulan)));
        $_SESSION['rick_auto']['perusahaan'] = $cmbPerusahaan;
        $_SESSION['rick_auto']['bulan'] = $hari;
       // echo $hari;
    }

    public function print_report_monthly(){
        $this->data['getData'] = $this->model_purchase->getMonthlyPurchase();
        $jenis = $this->uri->segment(4);
        if($jenis != "pdf"){
        $this->load->view('admin/purchase/bg_print_report_po',$this->data);
        }else{
        $content = $this->load->view('admin/purchase/bg_print_report_po_pdf',$this->data,TRUE);
        $this->template->print2pdf('Print_PDF',$content, 'Report_Bulanan');
        }
    }

    public function print_report_monthly_po(){
        $this->data['getData'] = $this->model_purchase->getMonthlyPurchase();
        $jenis = $this->uri->segment(4);
        if($jenis != "pdf"){
        $this->load->view('admin/purchase/bg_print_report_so',$this->data);
        }else{
        $content = $this->load->view('admin/purchase/bg_print_report_so_pdf',$this->data,TRUE);
        $this->template->print2pdf('Print_PDF',$content,  'Report_Bulanan_PO');
        }
    }

    public function simpanAjax(){
        $txtTotalSatuan = $this->input->post('txtTotalSatuan');
        $txtTotalSatuanB = $this->input->post('txtTotalSatuanB');
        $txtQty = $this->input->post('txtQty');
        $txtDiscount = $this->input->post('txtDiscount');
        $hitungDiskon = $txtTotalSatuan * $txtDiscount / 100;
        $hitungTotal = $txtTotalSatuan - $hitungDiskon;
        $total = $hitungTotal * $txtQty;
        $totalbdiskon = $txtTotalSatuanB * $txtQty;
        $txtTotalSatuanBs = $txtTotalSatuan / $txtQty;
        $txtTotalSatuans = $total / $txtQty;
        $txtTotalSatuanss = $txtTotalSatuan;
        $txtTotalBeforeDiskon = $txtTotalSatuanss * $txtQty;

        //echo json_encode(array('Qty'=>$txtQty,'priceSatuan'=>$txtTotalSatuans,'priceSatuanrp'=>number_format($txtTotalSatuans,2,',','.'),'priceTotal'=>$total,'priceTotalrp'=>number_format($total,2,',','.')));
       //echo json_encode(array('Qty'=>$txtQty,'priceSatuan'=>$txtTotalSatuans,'priceSatuanrp'=>number_format($txtTotalSatuans,2,',','.'),'priceTotal'=>$total,'priceTotalrp'=>number_format($total,2,',','.'),'priceTotalbDiskon'=>$totalbdiskon,'priceTotalbDiskonrp'=>number_format($totalbdiskon,2,',','.')));
        echo json_encode(array('Qty'=>$txtQty,'priceSatuan'=>$txtTotalSatuanss,'priceSatuanrp'=>number_format($txtTotalSatuanss,2,',','.'),'priceSatuanAdiskon'=>$txtTotalSatuans,'priceSatuanAdiskonrp'=>number_format($txtTotalSatuans,2,',','.'),'priceBSatuan'=>$txtTotalSatuanBs,'priceBSatuanrp'=>number_format($txtTotalSatuanBs,2,',','.'),'priceBTotal'=>$total,'priceTotal'=>$total,'priceTotalrp'=>number_format($total,2,',','.'),'priceTotalbDiskon'=>$txtTotalBeforeDiskon,'priceTotalbDiskonrp'=>number_format($txtTotalBeforeDiskon,2,',','.')));
    }

    public function proses_hitung(){
        $cmbMember   = $this->input->post('cmbMember');
        $cmbProduk   = $this->input->post('cmbProduk');
        $cmbJenis   = $this->input->post('cmbJenis');
        $priceSatuan = $this->input->post('priceSatuan');
        $addStok = $this->input->post('addStok');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $priceTotal = $this->input->post('priceTotal');

        $getDataProduk = $this->model_produk->getProductById($cmbProduk)->row();

        $getMember = $this->model_master->getMembersById($cmbMember)->row();
        if($cmbJenis == 0){
            $product_prices = $getDataProduk->normal_price;
        }else{
            $product_prices = $getDataProduk->ekspor_price;
        }
        $getHargaSatuan = $product_prices + $product_prices * $getMember->angka / 100;
        $getHargaTotalSatuan = $getHargaSatuan * $addStok;
        //echo $getHargaSatuan;

        $cekExp = $this->model_invoice->getCekExpiredStatus(date("Y-m-d"),$cmbMember);
        if($cekExp->num_rows() > 0){
            // $expMem = "<span class='label label-block label-danger text-left'>Member ini belum <br> melakukan  pembayaran <br> pada invoice
            // </span>";
            $cekMemberExp = 1;
        }else{
            $cekMemberExp = 0;
            //$expMem = "";
        }

        echo json_encode(array('harga_satuan_rp'=>number_format($getHargaSatuan,2,',','.'),'harga_total_satuan_rp'=>number_format($getHargaTotalSatuan,2,',','.'),'nama_produk'=>$getDataProduk->product_name,'kode_produk'=>$getDataProduk->product_code,'cekMemberExp'=>$cekMemberExp));

    }

    public function hitung_grand_total(){
        $id = $this->input->post('id_purchase');
        $purchase = $this->model_purchase->getReqPurchaseByID($id)->row();
        $purchaseDetail = $this->model_purchase->getReqPurchaseDetailByPurchase($purchase->id);
            $total_pembayaran = 0;
            foreach($purchaseDetail->result() as $purchaseDetail){
                $txtTotal = $this->input->post('txtTotal_'.$purchaseDetail->id);
                $total_pembayaran =  $total_pembayaran + $txtTotal;
               // echo $txtTotal;
            }

            echo number_format($total_pembayaran,2,',','.');
            //echo json_encode(array('priceTotal'=>number_format($total_pembayaran,2,',','.')));
    }

    public function pilihExpedisi(){
        $id = $this->input->post('id');
        $getPurchase = $this->model_purchase->getPurchaseByID($id)->row();
        $getExpedisi = $this->model_master->getExpedisi();

        echo"
        <center>
        <div class='form-group'>
            <label class='control-label col-lg-2'>Pilih Expedisi</label>
            <div class='col-lg-10'>
                <div class='row'>
                    <div class='col-lg-8'>
                        <div class='form-group'>                            <select id='cmbExpedisi_".$id."' name='cmbExpedisi_".$id."' data-placeholder='Pilih Expedisi' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true'>
                            ";
                            foreach($getExpedisi->result() as $exp){
                                echo"
                                <option value=".$exp->id." ";if($exp->id == $getPurchase->expedisi){echo "selected";}echo">".$exp->name."</option>
                                ";
                            }echo"
                            </select>
                        </div>
                    </div>

                    <div class='col-md-4'>
                        <div class='form-group'>
                            <a href='#!' onclick=javascript:simpanExpedisi(".$id.") class='btn btn-primary btn-labeled'><b><i class='icon-floppy-disk'></i></b> Simpan </a></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><br><br><br>

        <script>
        $('#cmbExpedisi_".$id."').select2();
        </script>
        ";
        
    }

    public function input_confirm(){
        $id = $this->input->post('id');
        echo"
        <div class='form-group'>
            <label class='control-label col-lg-2'>Catatan Penolakan</label>
            <div class='col-lg-10'>
                <div class='row'>
                    <div class='col-lg-8'>
                        <div class='form-group'>
                            <textarea id='txtNoteCancel_".$id."' name='txtNoteCancel_".$id."' style='margin: 0px; width: 100%; height: 100%;'></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ";
    }

    public function input_confirm_selesai(){
        $id = $this->input->post('id');
        $getCekValidPurchase = $this->model_purchase->getCekValidPurchaseQty($id);
        // echo $getCekValidPurchase->qty;
        // echo $getCekValidPurchase->qty_kirim;
        if($getCekValidPurchase->num_rows() > 0){
            echo"
            <center><h3>Terdapat data pada PO ini QTY Kirim berbeda dengan QTY Order, apakah anda ingin memproses?</h3></center>";
            
        }else{
            echo"
            <center><h3>Apakah anda yakin ingin memproses data ini?</h3></center>";
        }
        echo"

        ";
    }

    public function input_invoice_date(){
        $id = $this->input->post('id');
        $getPurchaseDetail = $this->model_purchase->getTotalPembayarannyaByPurchase($id)->row();
        echo"
        <center><h3>Apakah Anda yakin ingin memproses data ini sebagai Invoice ?</h3></center>
        <div class='form-group'>
            <label class='control-label col-lg-2'>Tanggal Invoice</label>
            <div class='col-lg-10'>
                <div class='row'>
                    <div class='col-lg-8'>
                        <div class='form-group'>
                            <input type='date' class='form-control' id='txtDateInvoice_".$id."' name='txtDateInvoice_".$id."' value=".date('Y-m-d').">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class='form-group'>
            <label class='control-label col-lg-2'>Total</label>
            <div class='col-lg-10'>
                <div class='row'>
                    <div class='col-lg-8'>
                        <div class='form-group'>
                            <p style='font-size:20px'>".number_format($getPurchaseDetail->total_harga,0,',','.')."</p> 
                            <input type='hidden' class='form-control' id='txtTotal_".$id."' name='txtTotal_".$id."' value=".$getPurchaseDetail->total_harga.">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class='form-group'>
            <label class='control-label col-lg-2'>Diskon</label>
            <div class='col-lg-10'>
                <div class='row'>
                    <div class='col-lg-8'>
                        <div class='form-group'>
                            <input type='number' class='form-control' id='txtDiskon_".$id."' name='txtDiskon_".$id."' onclick=javascript:hitungDiskonPO(".$id.") onchange=javascript:hitungDiskonPO(".$id.") onkeyup=javascript:hitungDiskonPO(".$id.")>
                            <input type='hidden' class='form-control' id='txtDiskonRupiah_".$id."' name='txtDiskonRupiah_".$id."')>
                        </div>
                    </div>
                    <div class='col-lg-2'>
                     %
                    </div>
                </div>
            </div>
        </div>
        <br>
        <hr>
        <br>
        <div class='form-group'>
            <label class='control-label col-lg-2'>Total Setelah Diskon</label>
            <div class='col-lg-10'>
                <div class='row'>
                    <div class='col-lg-8'>
                        <div class='form-group'>
                            <p style='font-size:20px' id='lblTotalSetelahDiskon_".$id."'>Rp. ".number_format($getPurchaseDetail->total_harga,0,',','.')."</p> 
                            <input type='hidden' class='form-control' id='txtTotalAfter_".$id."' name='txtTotalAfter_".$id."' value=".$getPurchaseDetail->total_harga.">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ";
    }

    public function hitungDiskonPO(){
        $txtTotal = $this->input->post('txtTotal');
        $txtDiskon = $this->input->post('txtDiskon');
        if($txtDiskon < 0){
            $txtDiskonOut = 0;
        }else{
            $txtDiskonOut = $txtDiskon;
        }
        $pdiskon = $txtDiskonOut * $txtTotal / 100;
        $diskon = $txtTotal - $pdiskon;

        echo json_encode(array('TotalRp'=>number_format($diskon,0,',','.'),'Total'=>$diskon,'pDsikon'=>$pdiskon));
    }

    public function confirmBatalReqPO(){
        $id = $this->input->post('id');
        $data = $this->model_purchase->getPurchaseTempByID($id)->row();
        echo"
        <h3>Apakah Anda yakin ingin membatalkan order ini ?</h3>";
        if($data->status == 0){
            echo"
        <div class='form-group'>
            <label class='control-label col-lg-2'>Catatan Penolakan</label>
            <div class='col-lg-10'>
                <div class='row'>
                    <div class='col-lg-8'>
                        <div class='form-group'>
                            <textarea id='txtNoteCancel_".$id."' name='txtNoteCancel_".$id."' style='margin: 0px; width: 100%; height: 100%;'></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ";}        
    }

    public function simpanExpedisi(){
        $id = $this->input->post('id');
        $cmbExpedisi = $this->input->post('cmbExpedisi');

        $update = $this->db->set('expedisi',$cmbExpedisi)->where('id',$id)->update('transaction_purchase');
        if($update){
            echo "1";
        }
    }

    public function pilihMember(){
        $cmbMember = $this->input->post('cmbMember');
        $getDataSales = $this->model_master->getMemberSalesByMemberJoin($cmbMember);
        foreach($getDataSales->result() as $sales){
            echo"<option value='".$sales->id_sales."'>".$sales->nama_sales."</option>";
        }
    }

    public function tambahProdukPO_(){
        $total = $this->input->post('total');
        $getProducts = $this->model_produk->getProducts();
        $getPerusahaan = $this->model_master->getPerusahaan();
        $getGudang = $this->model_master->getGudang();
        $getMembers = $this->model_master->getAllMembers();
        echo"

        <div class='col-md-12'>
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Produk: </label>
                    <select id='cmbProduk_".$total."' name='cmbProduk_".$total."' data-placeholder='Pilih Produk' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true' onchange=javascript:proses_hitung(".$total.")>
                        <option value='0' disabled selected>Pilih Produk</option>
                        ";
                        foreach($getProducts->result() as $product){
                            $cekStok = $this->model_master->getStokProduct($product->id);
                                if($cekStok->num_rows() > 0){
                                    echo"<option value=".$product->id.">".$product->product_code." - ".$product->product_name."</option> ";
                                }else{
                                echo"
                                ";
                                }
                            }
                        echo"
                    </select>
                </div>
            </div>
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Harga Satuan (Rp.) : </label>
                    <input type='text' class='form-control' id='priceSatuan_".$total."' name='priceSatuan_".$total."' readonly>
                </div>
            </div>
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Qty Order: </label>
                    <div class='input-group bootstrap-touchspin'>
                    <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurangProsesPO(".$total.")>-
                    </button>
                    </span>
                    <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
                    </span>
                    <input type='text' id='addStok_".$total."' name='addStok_".$total."' value='1' class='touchspin-set-value form-control' style='display: block;' onkeyup=javascript:ketikProsesPO(".$total.") >
                    <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
                    </span>
                    <span class='input-group-btn'>
                    <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambahProsesPO(".$total.")>+
                    </button>
                    </span>
                    </div>
                </div>
            </div>
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Perusahaan : </label>
                    <select class='form-control' id='cmbPerusahaan_".$total."' name='cmbPerusahaan_".$total."' onchange=javascript:pilih_perusahaan_proses_po(".$total.")>
                    <option value='0' disabled selected>Pilih Perusahaan</option>
                    ";foreach($getPerusahaan->result() as $perusahaans){
                        echo"
                            <option value='".$perusahaans->id."'>".$perusahaans->name."</option>
                        ";
                    }echo"
                    </select>
                </div>
            </div>
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Gudang : </label>
                    <div id='tempatGudang_".$total."'>
                    <select class='form-control' id='cmbGudang_".$total."' name='cmbGudang_".$total."'>
                        <option value='0' selected>Pilih Gudang</option>
                        ";foreach($getGudang->result() as $gudang){
                            echo"
                                <option value='".$gudang->id."'>".$gudang->name."</option>
                            ";
                        }echo"
                    </select>
                    </div>
                </div>
            </div>
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Total Satuan (Rp.) : <p align='left'>X</p></label>
                    <input type='text' class='form-control' id='priceTotal_".$total."' name='priceTotal_".$total."' readonly>
                </div>
            </div>
        </div>   

        <script>
        $('#cmbProduk_".$total."').select2();
        </script>
        ";
    }

    public function tambahProdukPO2(){
        $total = $this->input->post('total');
        $getProducts = $this->model_produk->getProducts();
        $getPerusahaan = $this->model_master->getPerusahaan();
        $getGudang = $this->model_master->getGudang();
        $getMembers = $this->model_master->getAllMembers();
        echo"
        <!-- colmd2 untuk yng lama -->
        <div class='col-md-12' id='tempatAjaxPO_".$total."'>
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Produk: </label>
                    <select id='cmbProduk_".$total."' name='cmbProduk_".$total."' data-placeholder='Pilih Produk' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true' onchange=javascript:proses_hitung(".$total.")>
                        <option value='0' disabled selected>Pilih Produk</option>
                        ";
                        foreach($getProducts->result() as $product){
                            $cekStok = $this->model_master->getStokProduct($product->id);
                                if($cekStok->num_rows() > 0){
                                    echo"<option value=".$product->id.">".$product->product_code." - ".$product->product_name."</option> ";
                                }else{
                                echo"
                                ";
                                }
                            }
                        echo"
                    </select>
                </div>
            </div>
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Jenis Harga: </label>
                    <select id='cmbJenis_".$total."' name='cmbJenis_".$total."' class='form-control' onchange=javascript:proses_hitung(".$total.")>
                        <option value='0'>Harga Normal</option>
                        <option value='2'>Harga Ekspor</option>
                    </select>
                </div>
            </div>
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Harga Satuan (Rp.) : </label>
                    <input type='text' class='form-control' id='priceSatuan_".$total."' name='priceSatuan_".$total."' readonly>
                </div>
            </div>
            <div class='col-md-3' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Qty Order: </label>
                    <div class='input-group bootstrap-touchspin'>
                    <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurangProsesPO(".$total.")>-
                    </button>
                    </span>
                    <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
                    </span>
                    <input type='text' id='addStok_".$total."' name='addStok_".$total."' value='1' class='touchspin-set-value form-control' style='display: block;'>
                    <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
                    </span>
                    <span class='input-group-btn'>
                    <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambahProsesPO(".$total.")>+
                    </button>
                    </span>
                    </div>
                </div>
            </div>
            <!-- <div class='col-md-3' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Perusahaan : </label>
                    <select class='form-control' id='cmbPerusahaan_".$total."' name='cmbPerusahaan_".$total."' onchange=javascript:pilih_perusahaan_proses_po(".$total.")>
                    <option value='0' disabled selected>Pilih Perusahaan</option>
                    ";foreach($getPerusahaan->result() as $perusahaans){
                        echo"
                            <option value='".$perusahaans->id."'>".$perusahaans->name."</option>
                        ";
                    }echo"
                    </select>
                </div>
            </div> -->
            <!-- <div class='col-md-3' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Gudang : </label>
                    <div id='tempatGudang_".$total."'>
                    <select class='form-control' id='cmbGudang_".$total."' name='cmbGudang_".$total."'>
                        <option value='0' selected>Pilih Gudang</option>
                        ";foreach($getGudang->result() as $gudang){
                            echo"
                                <option value='".$gudang->id."'>".$gudang->name."</option>
                            ";
                        }echo"
                    </select>
                    </div>
                </div>
            </div> -->
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Total Satuan (Rp.) : </label>
                    <input type='text' class='form-control' id='priceTotal_".$total."' name='priceTotal_".$total."' readonly>
                </div>
            </div>
            <div class='col-md-1' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Aksi : </label>
                    <button type='button' class='btn btn-danger btn-icon btn-rounded' onclick='javascript:remove_ini(".$total.")'><i class='icon-trash'></i></button>
                </div>
            </div>
        </div>   

        <script>
        $('#cmbProduk_".$total."').select2();
        </script>
        ";
    }

    public function tambahProdukPO(){
        $total = $this->input->post('total');
        $getProducts = $this->model_produk->getProducts();
        $getPerusahaan = $this->model_master->getPerusahaan();
        $getGudang = $this->model_master->getGudang();
        $getMembers = $this->model_master->getAllMembers();
        echo"
        <!-- colmd2 untuk yng lama -->
        <div class='col-md-12' id='tempatAjaxPO_".$total."'>
            <div class='col-md-3' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Produk: </label>
                    <select id='cmbProduk_".$total."' name='cmbProduk_".$total."' class='form-control' onchange=javascript:proses_hitung(".$total.")>
                        <option value='0' selected disabled>Pilih Produk</option>
                    </select>
                </div>
            </div>
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Jenis Harga: </label>
                    <select id='cmbJenis_".$total."' name='cmbJenis_".$total."' class='form-control' onchange=javascript:proses_hitung(".$total.")>
                        <option value='0'>Harga Normal</option>
                        <option value='2'>Harga Ekspor</option>
                    </select>
                </div>
            </div>
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Harga Satuan (Rp.) : </label>
                    <input type='text' class='form-control' id='priceSatuan_".$total."' name='priceSatuan_".$total."' readonly>
                </div>
            </div>
            <div class='col-md-2' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Qty Order: </label>
                    <div class='input-group bootstrap-touchspin'>
                    <span class='input-group-btn'><button class='btn btn-default bootstrap-touchspin-down' type='button' onclick=javascript:kurangProsesPO(".$total.")>-
                    </button>
                    </span>
                    <span class='input-group-addon bootstrap-touchspin-prefix' style='display: none;'>
                    </span>
                    <input type='text' id='addStok_".$total."' name='addStok_".$total."' value='1' class='touchspin-set-value form-control' style='display: block;'>
                    <span class='input-group-addon bootstrap-touchspin-postfix' style='display: none;'>
                    </span>
                    <span class='input-group-btn'>
                    <button class='btn btn-default bootstrap-touchspin-up' type='button'  onclick=javascript:tambahProsesPO(".$total.")>+
                    </button>
                    </span>
                    </div>
                </div>
            </div>
            <!-- <div class='col-md-3' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Perusahaan : </label>
                    <select class='form-control' id='cmbPerusahaan_".$total."' name='cmbPerusahaan_".$total."' onchange=javascript:pilih_perusahaan_proses_po(".$total.")>
                    <option value='0' disabled selected>Pilih Perusahaan</option>
                    ";foreach($getPerusahaan->result() as $perusahaans){
                        echo"
                            <option value='".$perusahaans->id."'>".$perusahaans->name."</option>
                        ";
                    }echo"
                    </select>
                </div>
            </div> -->
            <!-- <div class='col-md-3' style='background-color:#c5c5c5'>
                <div class='form-group'>
                    <label>Gudang : </label>
                    <div id='tempatGudang_".$total."'>
                    <select class='form-control' id='cmbGudang_".$total."' name='cmbGudang_".$total."'>
                        <option value='0' selected>Pilih Gudang</option>
                        ";foreach($getGudang->result() as $gudang){
                            echo"
                                <option value='".$gudang->id."'>".$gudang->name."</option>
                            ";
                        }echo"
                    </select>
                    </div>
                </div>
            </div> -->
            <div class='col-md-3' style='background-color:#c5c5c5'>
                <div class='form-group'> 
                    <label>Total Satuan (Rp.) : </label> <a href='#!' onclick='javascript:remove_ini(".$total.")'> <i class='icon-cross2 text-danger-400'></i> </a>
                    <input type='text' class='form-control' id='priceTotal_".$total."' name='priceTotal_".$total."' readonly> 

                </div>
            </div>
        </div>   

        <script>
       $('select[id=cmbProduk_".$total."]').select2({
           ajax: {
               url: '".base_url('admin/purchase/dataProduk')."',
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

    public function saveNote(){
        $id = $this->input->post('id');
        $txtNoteEdit = $this->input->post('txtNoteEdit');
        $update = $this->db->set('note',$txtNoteEdit)->where('id',$id)->update('transaction_purchase');
        if($update){
            echo "1";
        }
    }

    public function pilihMenuPO_(){

        echo"
            <div class='form-group has-feedback'>
                <a href='#!' onclick=javascript:filter_po('excel') class='btn bg-green'><i class=' icon-file-download2 position-left'></i>Export Excel</a>
            </div>
            <div class='form-group has-feedback'>
                <a href='#!' onclick=javascript:filter_po('print') class='btn bg-brown'><i class='icon-printer2 position-left'></i>Print</a>
            </div>
            <div class='form-group has-feedback'>
                <a href='#!' onclick=javascript:filter_po('pdf') class='btn btn-danger'><i class='icon-file-download position-left'></i>Export PDF</a>
            </div>
        ";
    }

    public function pilihMenuPO(){
        $jenis = $this->input->post('jenis');
        echo"
            <div class='form-group has-feedback'>
                <a href='#!' onclick=javascript:filter_po('excel','".$jenis."') class='btn bg-green'><i class=' icon-file-download2 position-left'></i>Export Excel</a>
            </div>
            <div class='form-group has-feedback'>
                <a href='#!' onclick=javascript:filter_po('print','".$jenis."') class='btn bg-brown'><i class='icon-printer2 position-left'></i>Print</a>
            </div>
            <div class='form-group has-feedback'>
                <a href='#!' onclick=javascript:filter_po('pdf','".$jenis."') class='btn btn-danger'><i class='icon-file-download position-left'></i>Export PDF</a>
            </div>
        ";
    }


    public function dataProduk()
    {
        $search = $this->input->get('search');
        $getData = $this->model_produk->getProdukSearch($search,'product_name');
        echo json_encode($getData);
    }

    public function dataProdukPerusahaan()
    {
        $search = $this->input->get('search');
        $cmbPerusahaan = $this->input->get('cmbPerusahaan');
        $cmbGudangFrom = $this->input->get('cmbGudangFrom');
        $getData = $this->model_produk->getProdukSearchPerusahaanAndGudang($search,'product_name',$cmbPerusahaan, $cmbGudangFrom);
        echo json_encode($getData);
    }

    public function scan_barcode_order(){
        $id = $this->input->post('id');
        $kode_produk = $this->input->post('kode_produk');
        //echo $kode_produk;
        //$getProdukByCode = $this->model_produk->getProductByBarcode($kode_produk)->row();
        $getProdukByCodess = $this->model_produk->getProdukByBarcode($kode_produk);
        $getProdukByCode = $this->model_produk->getProductByBarcode($kode_produk)->row();
        $getProdukByCodes = $this->model_purchase->getPurchaseDetailByPurchaseAndProduk($id,$getProdukByCodess->row()->product_id);
        // if($getProdukByCodes->num_rows() > 0){
        //     echo json_encode(array('message'=>'sukses','idPurchase'=>$getProdukByCodes->row()->id));
        //     //echo $getProdukByCodes->row()->id;
        //     //$this->data_ajax_order();
        // }else{
        //     echo json_encode(array('message'=>'gagal','idPurchase'=>0));
        // }
        //echo $getProdukByCodes->row()->id;
        echo json_encode(array('message'=>'sukses','qtyR'=>$getProdukByCodess->row()->isi, 'idDetail'=>$getProdukByCodes->row()->id));
    }

    public function filter_perusahaan_po(){
        $_SESSION['rick_auto']['po_perusahaan_filter'] = $this->input->post('cmbPerusahaanFilter');

    }

    public function filter_perusahaan_rpo(){
        $_SESSION['rick_auto']['rpo_sales_filter'] = $this->input->post('cmbSales');
        $_SESSION['rick_auto']['rpo_perusahaan_filter'] = $this->input->post('cmbPerusahaanFilter');
    }

    public function report_bo(){
        $jenis = $this->uri->segment(4);
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getKategori'] = $this->model_master->getKategori();
        $this->data['getReqPoBo'] = $this->model_purchase->getReqPurchaseBo();
        $this->template->rick_auto('report_barang/bg_report_bo',$this->data);
    }

    public function filter_bo(){
        $txtProduk = $this->input->post('txtProduk');
        $cmbKategori = $this->input->post('cmbKategori');
        $tanggalFrom = $this->input->post('tanggalFrom');
        $tanggalTo = $this->input->post('tanggalTo');
        $cmbStatus = $this->input->post('cmbStatus');
        $jenis = $this->input->post('jenis');
        $_SESSION['rick_auto']['filter_bo_produk'] = $txtProduk;
        $_SESSION['rick_auto']['filter_bo_kategori'] = $cmbKategori;
        $_SESSION['rick_auto']['filter_bo_status'] = $cmbStatus;
        $_SESSION['rick_auto']['filter_bo_tanggalfrom'] = $tanggalFrom;
        $_SESSION['rick_auto']['filter_bo_tanggalto'] = $tanggalTo;
        $_SESSION['rick_auto']['filter_bo_tanggaltoo'] = date("Y-m-d",strtotime("+1 day", strtotime($tanggalTo)));
        //echo "Produk ".$txtProduk;
        // echo $jenis;
        
        if($jenis == "undefined" || $jenis == ""){
            $this->data['getPoBo'] = $this->model_purchase->getReqPurchaseBo();
            $this->data['getPoBoTerkirim'] = $this->model_purchase->getPurchaseByBo();
        $this->load->view('admin/report_barang/bg_report_bo_ajax',$this->data);
        }else{
            $this->data['getPoBo'] = $this->model_purchase->getReqPurchaseBoQty();
            $this->data['getPoBoTerkirim'] = $this->model_purchase->getPurchaseByBoQty();
        $this->load->view('admin/report_barang/bg_report_bo_qty_ajax',$this->data);   
        }
    }

    public function print_bo(){
        $jenis = $this->uri->segment(4);
        //echo "Produk ".$txtProduk;
        $_SESSION['rick_auto']['filter_bo_produk'];
        $_SESSION['rick_auto']['filter_bo_kategori'];
        $_SESSION['rick_auto']['filter_bo_status'];
        $_SESSION['rick_auto']['filter_bo_tanggalfrom'];
        $_SESSION['rick_auto']['filter_bo_tanggalto'];
        $this->data['getPoBo'] = $this->model_purchase->getReqPurchaseBo();
        $this->data['getPoBoTerkirim'] = $this->model_purchase->getPurchaseByBo();
        if($jenis != "pdf"){
        $this->load->view('admin/report_barang/bg_report_bo_export',$this->data);
        }else{
        $content = $this->load->view('admin/report_barang/bg_report_bo_export',$this->data,TRUE);
        $this->template->print2pdf('Print_PDF',$content,'Report_BO');
        }
        
    }

        public function print_bo_qty(){
        $jenis = $this->uri->segment(4);
        //echo "Cek".$jenis;
        //echo "Produk ".$txtProduk;
        $_SESSION['rick_auto']['filter_bo_produk'];
        $_SESSION['rick_auto']['filter_bo_kategori'];
        $_SESSION['rick_auto']['filter_bo_status'];
        $_SESSION['rick_auto']['filter_bo_tanggalfrom'];
        $_SESSION['rick_auto']['filter_bo_tanggalto'];
        $this->data['getPoBo'] = $this->model_purchase->getReqPurchaseBoQty();
        $this->data['getPoBoTerkirim'] = $this->model_purchase->getPurchaseByBoQty();
        if($jenis != "pdf"){
        $this->load->view('admin/report_barang/bg_report_bo_qty_export',$this->data);
        }else{
        $content = $this->load->view('admin/report_barang/bg_report_bo_qty_pdf',$this->data,TRUE);
        $this->template->print2pdf('Print_PDF',$content,'Report_BO_Qty');
        }
        
    }





}?>