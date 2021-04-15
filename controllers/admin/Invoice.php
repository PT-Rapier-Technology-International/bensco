<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
/*
* CONTROLLER INDEX WEBSITE
* This controler for screen index
*
* Log Activity : ~ Create your log if you change this controller ~
* 1. Create 20 Mei 2019 By Devanda Andrevianto, Create All Function, Create controller
*/
class Invoice extends CI_Controller {
    var $data = array('scjav'=>'assets/jController/admin/CtrlInvoice.js');
    function __construct(){
        parent::__construct();
        $this->load->model('admin/model_master');
        $this->load->model('admin/model_produk');
        $this->load->model('admin/model_purchase');
        $this->load->model('admin/model_invoice');
        $this->load->model('admin/model_index');
        // $this->lang->load('admin', '');
        if(empty($_SESSION['rick_auto']) || empty($_SESSION['rick_auto']['id'])){
            redirect('admin/index/signin/');
            return;
        }
    }

    public function index(){
        $this->data['getMembers'] = $this->model_master->getAllMembers();
        $this->template->rick_auto('invoice/bg_list_member',$this->data);
    }
    
    // fungsi untuk mengecek apakah user sudah login
    public function invoice_detail_(){
        $member = base64_decode($this->uri->segment(4));
        $flag = $this->uri->segment(5);
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getInvoice'] = $this->model_invoice->getInvoiceByMembers($member);
        $this->data['getTotalPaymentInvoice'] = $this->model_invoice->getTotalPaymentInvoiceByMember($member,$flag);
        $this->data['getPayments'] = $this->model_master->getPayments();
        $this->data['getPiutang'] = $this->model_invoice->getPaymentCustomer($member);
        $this->template->rick_auto('invoice/bg_index',$this->data);

    }

    public function retur_revisi(){
        $invoice_id = base64_decode($this->uri->segment(4));
        $this->data['Data'] = $this->model_invoice->getDetailRevisiReturByInvoiceId($invoice_id);
        $this->template->rick_auto('invoice/bg_invoice_detail_revisi_retur',$this->data);
    }

    public function proses_tanda_terima(){
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
        $uri4 = $this->uri->segment(4);
        $this->data['Data'] = $this->model_invoice->getTandaTerimaByNoTandaTerima($uri4);
        //$this->data['DataRevRet'] = $this->model_invoice->getDetailRevisiReturByInvoiceId($uri4);
        $member = $this->model_invoice->getTandaTerimaByNoTandaTerima($uri4)->row()->member_id;
        $this->data['getPayments'] = $this->model_master->getPayments();
        $this->data['getPiutang'] = $this->model_invoice->getPaymentTandaTerima($uri4);
        $this->data['getTotalPaymentInvoice'] = $this->model_invoice->getTotalPaymentInvoiceByMemberAndNott($member,$uri4);
        $this->template->rick_auto('invoice/bg_proses_tanda_terima',$this->data);

    }

    public function filterBulan(){
        $_SESSION['rick_auto']['filter_bulan'] = $this->input->post('cmbBulan');
        
    }

    public function invoice_detail(){
        if(isset($_SESSION['rick_auto']['filter_bulan'])){
            $bulan_sekarang = $_SESSION['rick_auto']['filter_bulan'];
        }else{
            $bulan_sekarang = date('m', strtotime(date('Y-m-d')));
        }
        $uri4 = $this->uri->segment(4);
        
        $member = base64_decode($this->uri->segment(4));
        $flag = $this->uri->segment(5);
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        // if(isset($_SESSION['rick_auto']['filter_start_date']) && $_SESSION['rick_auto']['filter_end_date'] && $_SESSION['rick_auto']['filter_sales']){
        //     $this->data['getInvoice'] = $this->model_invoice->getInvoiceByMembers($member,0);
        // }else{
        //     $this->data['getInvoice'] = $this->model_invoice->getAllInvoiceByMembers($member);    
        // }
        //$this->data['getInvoice'] = $this->model_invoice->getInvoiceByMembers($member,0);
        if($uri4 == ""){
        $this->data['getInvoice'] = $this->model_invoice->getInvoiceByAllMemberss();
        }else{
        $this->data['getInvoice'] = $this->model_invoice->getInvoiceByAllMembers(0);    
        }
        $this->data['getMember'] = $this->model_master->getAllMembers();
        $this->data['getCity'] = $this->model_master->getCity();
        //$this->data['getTotalPaymentInvoice'] = $this->model_invoice->getTotalPaymentInvoiceByMember($member,$flag);
        $this->data['getPayments'] = $this->model_master->getPayments();
        //$this->data['getPiutang'] = $this->model_invoice->getPaymentCustomer($member);
        $this->data['getSales'] = $this->model_master->getSales();
        $this->template->rick_auto('invoice/bg_index',$this->data);

    }

    public function invoice_tanda_terima_(){
        $member = base64_decode($this->uri->segment(4));

        $this->data['Data'] = $this->model_invoice->getTandaTerimaByMember($member);
        $this->data['Member'] = $this->model_master->getMemberByID($member)->row();
        $this->template->rick_auto('invoice/bg_invoice_tandaterima',$this->data);

    }
    public function filter_tanda_terima(){
        $invoice_no = $this->input->post('invoice_no_tt');
        $tt_no = $this->input->post('tt_no');
        $cmbPerusahaanFilter = $this->input->post('cmbPerusahaanFilter');

        
        $_SESSION['rick_auto']['filter_invoice_no_tt'] = $invoice_no;
        $_SESSION['rick_auto']['filter_no_tt'] = $tt_no;
        $_SESSION['rick_auto']['filter_perusahaan_tt'] = $cmbPerusahaanFilter;    
    }


    public function invoice_tanda_terima(){
        //$member = base64_decode($this->uri->segment(4));

        $this->data['Data'] = $this->model_invoice->getTandaTerimaByMembers();
        //$this->data['Member'] = $this->model_master->getMemberByID($member)->row();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->template->rick_auto('invoice/bg_invoice_tandaterima',$this->data);

    }

    public function list_tanda_terima(){
        $member = base64_decode($this->uri->segment(4));

        $this->data['Data'] = $this->model_invoice->getInvoiceTandaTerimaByMember($member);
        $this->template->rick_auto('invoice/bg_invoice_tandaterima',$this->data);

    }

    public function filter(){
        $start_date = date("Y-m-d",strtotime("+0 day", strtotime($this->input->post('start_date'))));
        $end_date = date("Y-m-d",strtotime("+1 day", strtotime($this->input->post('end_date'))));
        $cmbSales = $this->db->escape_str($this->input->post('cmbSales')); 
        $invoice_no = $this->input->post('invoice_no');
        $cmbPerusahaanFilter = $this->input->post('cmbPerusahaanFilter');
        $cmbMemberFilter = $this->input->post('cmbMemberFilter');

        $dataSales = $this->model_master->getSalesByName($cmbSales)->row();

        $_SESSION['rick_auto']['filter_member'] = $cmbMemberFilter;

        if($start_date == "1970-01-01" || $end_date == "1970-01-01" || $end_date == "1970-01-02"){
        }else{
            $_SESSION['rick_auto']['filter_start_date'] = $start_date;
            $_SESSION['rick_auto']['filter_end_date'] = $end_date;
        }
        if($cmbSales == null || $cmbSales == "" || $cmbSales == "null"){
            //echo "sales kosong";
        }else{
        $_SESSION['rick_auto']['filter_sales'] = $cmbSales;
        }


        $_SESSION['rick_auto']['filter_invoice_no'] = $invoice_no;
        
        if($cmbPerusahaanFilter == null || $cmbPerusahaanFilter == "" || $cmbPerusahaanFilter == "null"){

        }else{
        $_SESSION['rick_auto']['filter_perusahaan'] = $cmbPerusahaanFilter;
        }

        //echo $invoice_no;
        
        //echo $_SESSION['rick_auto']['filter_member'];
    }   

    public function show_detail(){
        $id = $this->input->post('id');

        $this->data['getInvoice'] = $this->model_invoice->getInvoiceById($id)->row();
        $this->load->view('admin/invoice/bg_detail',$this->data);
    }

    public function proses_retur_revisi(){
        $id = $this->uri->segment(4);

        $this->data['getInvoice'] = $this->model_invoice->getInvoiceById($id)->row();
        $this->template->rick_auto('invoice/bg_invoice_detail_retur_revisi',$this->data);
    }

    public function print_invoice(){
        $jenis = $this->uri->segment(5);
        $id = base64_decode($this->uri->segment(4));

        $this->data['getInvoice'] = $this->model_invoice->getInvoiceById($id)->row();
        $cekInvoice = $this->model_invoice->getInvoiceById($id)->row();
        if($jenis != "pdf"){
            if($cekInvoice->perusahaan_id == 4){
                $this->load->view('admin/invoice/bg_print_ertraco',$this->data);
            }elseif($cekInvoice->perusahaan_id == 3){
                $this->load->view('admin/invoice/bg_print_berkat',$this->data);
            }elseif($cekInvoice->perusahaan_id == 1){
                $this->load->view('admin/invoice/bg_print_chandra',$this->data);
            }else{
                $this->load->view('admin/invoice/bg_print',$this->data);
            }
        }else{
            if($cekInvoice->perusahaan_id == 4){
                $content = $this->load->view('admin/invoice/bg_print_ertraco_pdf',$this->data,TRUE);
                $this->template->print2pdf('Print_PDF',$content,'Print_INvoice_Ertraco');
            }elseif($cekInvoice->perusahaan_id == 3){
                $content = $this->load->view('admin/invoice/bg_print_berkat_pdf',$this->data,TRUE);
                $this->template->print2pdf('Print_PDF',$content,'Print_INvoice_Berkat');
            }elseif($cekInvoice->perusahaan_id == 1){
                $content = $this->load->view('admin/invoice/bg_print_chandra_pdf',$this->data,TRUE);
                $this->template->print2pdf('Print_PDF',$content,'Print_INvoice_Chandra');
                //$this->load->view('admin/invoice/bg_print_chandra_pdf',$this->data);
            }else{
                $content = $this->load->view('admin/invoice/bg_print_pdf',$this->data,TRUE);
                $this->template->print2pdf('Print_PDF',$content,'Print_INvoice_RickAuto');
            }
            
        }
        // if($cekInvoice->perusahaan_id == 4){
        //     $this->load->view('admin/invoice/bg_print_ertraco',$this->data);
        // }elseif($cekInvoice->perusahaan_id == 3){
        //     $this->load->view('admin/invoice/bg_print_berkat',$this->data);
        // }elseif($cekInvoice->perusahaan_id == 1){
        //     $this->load->view('admin/invoice/bg_print_chandra',$this->data);
        // }else{
        //     $this->load->view('admin/invoice/bg_print',$this->data);
        // }
    }

    public function print_amplop(){
        $jenis = $this->uri->segment(5);
        $id = base64_decode($this->uri->segment(4));

        $this->data['getInvoice'] = $this->model_invoice->getInvoiceById($id)->row();
        if($jenis != "pdf"){
            $this->load->view('admin/invoice/bg_print_amplop',$this->data);
        }else{
            $content = $this->load->view('admin/invoice/bg_print_amplop_pdf',$this->data,TRUE);
            $this->template->print2pdf('Print_PDF',$content,'Amplop');
        }
    }

    public function print_packing_list(){
        $jenis = $this->uri->segment(5);
        $id = base64_decode($this->uri->segment(4));

        $this->data['getInvoice'] = $this->model_invoice->getInvoiceById($id)->row();
        if($jenis != "pdf"){
        $this->load->view('admin/invoice/bg_packing_list',$this->data);
        }else{
            $content = $this->load->view('admin/invoice/bg_packing_list_pdf',$this->data,TRUE);
            $this->template->print2pdf('Print_PDF',$content,'Packing_List');
        }
    }

    public function print_tandaterima_(){
        $perusahaan = $this->uri->segment(5);
        $member = base64_decode($this->uri->segment(4));
        $flag = 1;
        if(isset($_SESSION['rick_auto']['filter_bulan'])){
            $bulan_sekarang = $_SESSION['rick_auto']['filter_bulan'];
        }else{
            $bulan_sekarang = date('m', strtotime(date('Y-m-d')));
        }
        $this->data['getInvoice'] = $this->model_invoice->getInvoiceByMemberPerusahaan($member,$bulan_sekarang,$perusahaan);
        $this->data['getTotalPaymentInvoice'] = $this->model_invoice->getTotalPaymentInvoiceByMember($member,$flag);
        $this->data['getPayments'] = $this->model_master->getPayments();
        $this->data['getPiutang'] = $this->model_invoice->getPaymentCustomer($member);
        $this->data['getMember'] = $this->model_master->getMemberByID($member)->row();
        $this->load->view('admin/invoice/bg_print_tanda_terima',$this->data);
    }


    public function print_tandaterima(){
        $tandaterima = $this->uri->segment(4);
        $perusahaan = $this->uri->segment(5);
        $this->data['getInvoice'] = $this->model_invoice->getTandaTerimaByNoTandaTerimaAndPerusahaan($tandaterima,$perusahaan);
        $member = $this->model_invoice->getTandaTerimaByNoTandaTerimaAndPerusahaan($tandaterima,$perusahaan)->row();
        $this->data['getMember'] = $this->model_master->getMemberByID($member->member_id)->row();
        $jenis_cetak = $this->uri->segment(6);
        if($jenis_cetak != 'pdf'){
            $this->load->view('admin/invoice/bg_print_tanda_terima',$this->data);
        }else{
            $content = $this->load->view('admin/invoice/bg_print_tanda_terima_pdf',$this->data,TRUE);
            $this->template->print2pdf('Print_PDF',$content,'Tanda_Terima');
        }
    }

    public function print_surat_jalan(){
        $perusahaan = $this->uri->segment(5);
        $jenis = $this->uri->segment(7);
        //$member = base64_decode($this->uri->segment(4));
        $member = $this->uri->segment(4);
        $id = $this->uri->segment(6);
        $flag = 1;
        $this->data['getInvoice'] = $this->model_invoice->getInvoiceDetailByInvoiceId($id);
        $this->data['getInvoiceRow'] = $this->model_invoice->getInvoiceById($id)->row();
        //$this->data['getInvoice'] = $this->model_invoice->getInvoiceByMemberPerusahaan($member,$flag,$perusahaan);
        $this->data['getTotalPaymentInvoice'] = $this->model_invoice->getTotalPaymentInvoiceByMember($member,$flag);
        $this->data['getPayments'] = $this->model_master->getPayments();
        $this->data['getPiutang'] = $this->model_invoice->getPaymentCustomer($member);
        $this->data['getMember'] = $this->model_master->getMemberByID($member)->row();
        $this->data['getInvoiceSJ'] = $this->model_invoice->invoice_surat_jalan();
        if($jenis != 'pdf'){
            $this->load->view('admin/invoice/bg_print_surat_jalan',$this->data);
        }else{
            $content = $this->load->view('admin/invoice/bg_print_surat_jalan_pdf',$this->data,TRUE);
            $this->template->print2pdf('Print_PDF',$content,'Surat_Jalan');
        }
    }

    public function ubah_status(){
        $id = $this->input->post('id');
        $i = $this->input->post('i');
        $data = $this->model_invoice->getInvoiceById($id)->row();
        $update = $this->db->set('pay_status',$i)->where('id',$id)->update('invoice');

        if($update){
            if($i == 0){
                $st = "BELUM LUNAS";
            }elseif($i == 1){
                $st = "LUNAS";
            }else{
                $st = "BATAL";
            }
            $ket = "".$_SESSION['rick_auto']['username']." telah mengubah data INVOICE menjadi ".$st."";
            $insert_log = $this->db->set('user_id',$_SESSION['rick_auto']['id'])->set('invoice_id',$id)->set('keterangan',$ket)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('invoice_log');

            if($insert_log){
                $insert_role = $this->db->set('no_transaction',$data->nonota)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Perubahan Status Invoice Menjadi '.$st.'')
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
                echo "1";
            }
        }
    }

    public function simpan_pembayaran(){
        $no_tanda_terima = $this->input->post('no_tanda_terima');
        $member_id = $this->input->post('member_id');
        $cmbPembayaran = $this->input->post('cmbPembayaran');
        $rupiah_input = $this->input->post('rupiah_input');
        $tanggal = $this->input->post('tanggal');
        $tanggal_cair = $this->input->post('tanggal_cair');
        $nomor_giro_cek = $this->input->post('nomor_giro_cek');
        $nama_giro_cek = $this->input->post('nama_giro_cek');
        $sisa_pembayaran = $this->input->post('sisa_pembayaran');
        $total = $sisa_pembayaran - str_replace(".","",$rupiah_input);
        $cek = $this->model_invoice->getPaymentInvoiceByMemberNoTTStatus($member_id,$no_tanda_terima,0);
        if($cek->num_rows() > 0){
            $hitung = $cek->row()->cicilan_ke + 1;
        }else{
            $hitung = 1;
        }
        if($total < 0){
            echo "2";
        }else{
            if($cmbPembayaran == "1" || $cmbPembayaran == "4"){
                $tanggal_fil = date("Y-m-d",strtotime("+0 day", strtotime($tanggal_cair)));
            }else{
                $tanggal_fil = date("Y-m-d",strtotime("+0 day", strtotime($tanggal)));
            }
            $insert = $this->db->set('no_tanda_terima',$no_tanda_terima)->set('member_id',$member_id)->set('payment_id',$cmbPembayaran)->set('payment_date',date("Y-m-d",strtotime("+0 day", strtotime($tanggal))))->set('liquid_date',date("Y-m-d",strtotime("+0 day", strtotime($tanggal_cair))))->set('name',$nama_giro_cek)->set('number',$nomor_giro_cek)->set('sudah_dibayar',str_replace(".","",$rupiah_input))->set('sisa',$total)->set('total_pembayaran',$sisa_pembayaran)->set('cicilan_ke',$hitung)->set('filter_date',$tanggal_fil)->insert('invoice_payment');
            $idPayment = $this->db->insert_id();
            $data_nota2 = $this->model_invoice->getTandaTerimaByNoTandaTerima($no_tanda_terima);
            foreach($data_nota2->result() as $nota2){
            $insertPiutang = $this->db->set('invoice_id',$nota2->invoice_id)->set('no_tt',$no_tanda_terima)->set('invoice_payment_id',$idPayment)->set('total',$sisa_pembayaran)->set('sisa',$total)->set('payment_id',$cmbPembayaran)->set('tanggal',date("Y-m-d",strtotime("+0 day", strtotime($tanggal))))->set('flag',0)->insert('invoice_piutang');
            }

            if($total == 0 || $total <= 6000){
                if($cmbPembayaran == 1 || $cmbPembayaran == 3){
                    $data_nota = $this->model_invoice->getTandaTerimaByNoTandaTerima($no_tanda_terima);
                    foreach($data_nota->result() as $nota){
                        $update_status_invoice = $this->db->set('flag_giro_cek',1)->where('id',$nota->invoice_id)->where('pay_status',0)->update('invoice');
                        if($update_status_invoice){
                        $insertPiutang = $this->db->set('invoice_id',$nota->invoice_id)->set('no_tt',$no_tanda_terima)->set('invoice_payment_id',$idPayment)->set('payment_id',$cmbPembayaran)->set('total',$sisa_pembayaran)->set('sisa',$total)->set('tanggal',date("Y-m-d",strtotime("+0 day", strtotime($tanggal))))->set('flag',1)->insert('invoice_piutang');
                        }
                    }  

                    $update_status_payment_invoice = $this->db->set('flag',0)->set('flag_giro_cek',1)->where('member_id',$member_id)->where('no_tanda_terima',$no_tanda_terima)->update('invoice_payment');

                                    
                }else{
               // $update_status_invoice = $this->db->set('pay_status',1)->where('member_id',$member_id)->where('pay_status',0)->update('invoice');
                $data_nota = $this->model_invoice->getTandaTerimaByNoTandaTerima($no_tanda_terima);
                foreach($data_nota->result() as $nota){
                    $update_status_invoice = $this->db->set('pay_status',1)->where('id',$nota->invoice_id)->where('pay_status',0)->update('invoice');
                    $fee = $nota->total_nota * 0.5 / 100;
                    $cekData = $this->model_invoice->getCekFeeSalesByInvoice($nota->invoice_id);
                    if($cekData->num_rows() > 0){

                    }else{
                        $duedate_fours = date("Y-m-d",strtotime("+30 day", strtotime($nota->duedate)));
                        $duedate_four = date("Y-m-d",strtotime("+0 day", strtotime($nota->duedate)));
                        if(date('Y-m-d') >= $duedate_four){
                            //echo "Kadaluwarsa";
                        }else{
                            $insert_sales_fee = $this->db->set('invoice_id',$nota->invoice_id)->set('sales_id',$nota->id_sales)->set('fee',$fee)->insert('transaction_sales_fee');
                        }
                    } 
                    $insertPiutang = $this->db->set('invoice_id',$nota->invoice_id)->set('no_tt',$no_tanda_terima)->set('invoice_payment_id',$idPayment)->set('total',$sisa_pembayaran)->set('sisa',$total)->set('payment_id',$cmbPembayaran)->set('tanggal',strtotime("+0 day", strtotime($tanggal)))->set('flag',1)->insert('invoice_piutang');
                }

                $update_status_payment_invoice = $this->db->set('flag',1)->where('member_id',$member_id)->where('no_tanda_terima',$no_tanda_terima)->update('invoice_payment');
                
                $insert_role = $this->db->set('no_transaction',$no_tanda_terima)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Pembayaran Invoice - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
                }
            }

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

            echo "1";
        }
    } 

    public function saveReturRevisi_210820(){
        $txtnoTT = $this->input->post('txtnoTT');
        $txtnoTransaksi = $this->input->post('txtnoTransaksi');
        $txtnoInvoice = $this->input->post('txtnoInvoice');
        $txtidInvoice = $this->input->post('txtidInvoice');
        $txtNote = $this->input->post('txtNote');
        $txtJenisTransaksi = $this->input->post('txtJenisTransaksi');
        $txtMemberId = $this->input->post('txtMemberId');
        $txtPaymentDate = $this->input->post('txtPaymentDate');
        $txtTotalPembayaran = $this->input->post('txtTotalPembayaran');
        $txtJenis = $this->input->post('txtJenis');
        $getdataTT = $this->model_invoice->getNoTTbyInvoiceId($txtidInvoice)->row();
        $getInvoice = $this->model_invoice->getInvoiceById($txtidInvoice)->row();
        $cek = $this->model_invoice->getPaymentInvoiceByMemberNoTTStatus($txtMemberId,$txtnoTT,0);
        if($cek->num_rows() > 0){
            $hitung = $cek->row()->cicilan_ke + 1;
        }else{
            $hitung = 1;
        }
        $dataInv = $this->model_invoice->getInvoiceById($txtidInvoice)->row();
        if($dataInv->flag_tanda_terima == 1){
            $invoiceDetail = $this->model_invoice->getInvoiceDetailByInvoiceId($txtidInvoice);
            foreach($invoiceDetail->result() as $detailInvoice){
                $txtTotalSatuan = $this->input->post('txtTotalSatuan_'.$detailInvoice->id);
                $txtTotalSatuanOld = $this->input->post('txtTotalSatuanOld_'.$detailInvoice->id);
                $txtQty = $this->input->post('txtQty_'.$detailInvoice->id);
                $txtQtyOld = $this->input->post('txtQtyOld_'.$detailInvoice->id);
                $txtTotal = $this->input->post('txtTotal_'.$detailInvoice->id);
                $txtTotalOld = $this->input->post('txtTotalOld_'.$detailInvoice->id);

                if($txtTotalSatuan == $txtTotalSatuanOld && $txtQty == $txtQtyOld && $txtTotal == $txtTotalOld){
                    $updateInvoice = "";
                   // echo "disini";
                }else{
                    $insertInvoiceRR = $this->db->set('nomor_retur_revisi',$txtnoTransaksi)->set('invoice_tanda_terima_id',$getdataTT->id)->set('invoice_id',$txtidInvoice)->set('invoice_detail_id',$detailInvoice->id)->set('note',$txtNote)->set('qty_before',$txtQtyOld)->set('qty_change',$txtQty)->set('price_before',$txtTotalSatuanOld)->set('price_change',$txtTotalSatuan)->set('total_before',$txtTotalOld)->set('total_change',$txtTotal)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('invoice_retur_revisi');
                                        if($detailInvoice->satuan == "Pcs"){
                    $getProdukByCode = $this->model_produk->getProductByCode($detailInvoice->product_code)->row();
                    $cekStok = $this->model_master->getGudangbyProductPerusahaan($getProdukByCode->id,$dataInv->perusahaan_id,$detailInvoice->gudang_id)->row();
                    $kurang = $txtQty - $txtQtyOld;
                    $pengurangan_stok = $cekStok->stok - $kurang;
                    $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                    }else{
                    //$cekProduks_ = $this->model_produk->getProductById($getKodeBayanganSet->id)->row();
                    $getProdukByCode = $this->model_produk->getProductByCode($detailInvoice->product_code)->row();
                    $getKodeBayanganSet = $this->model_produk->getProductsByKodeAndSatuan($getProdukByCode->product_code_shadow,"Set")->row();
                    $getKodeBayanganPcs = $this->model_produk->getProductsByKodeAndSatuan($getProdukByCode->product_code_shadow,"Pcs")->row();
                    $cekProduk_ = $this->model_produk->getProductById($getKodeBayanganSet->id)->row();
                    $cekStok = $this->model_master->getGudangbyProductPerusahaan($getKodeBayanganPcs->id,$dataInv->perusahaan_id,$detailInvoice->gudang_id)->row();
                    
                    $kurang = $txtQty - $txtQtyOld;
                    $qtyKurangLiner = $kurang * $cekProduk_->satuan_value;
                    $pengurangan_stok = $cekStok->stok - $qtyKurangLiner;
                    $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');   
                    }
                
                }

                $produk_id = $this->model_produk->getProductByCode($detailInvoice->product_code)->row();
                $stokPeng = $txtQtyOld - $txtQty;
                if($stokPeng > 0){
                $insert_opname_stok_bm = $this->db->set('product_id',$produk_id->id)->set('transaction_no',$txtnoTransaksi)->set('gudang_id',$detailInvoice->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$stokPeng)->set('note','Retur Barang Masuk')->set('keterangan','Retur Barang Masuk dari No. Invoice '.$txtnoInvoice.'')->set('invoice_id',$txtidInvoice)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                }
                
            }

            $insertPayment = $this->db->set('no_tanda_terima',$txtnoTT)->set('member_id',$txtMemberId)->set('payment_id',$txtJenis)->set('payment_date',$txtPaymentDate)->set('cicilan_ke',$hitung)->insert('invoice_payment');

            echo "1";
        }else{
        $ppn = $txtTotalPembayaran * 10 / 100;
        $grandTotal = $txtTotalPembayaran + $ppn;
        $updateInvoice = $this->db->set('sub_total',$txtTotalPembayaran)->set('total_before_ppn',$txtTotalPembayaran)->set('total',$grandTotal)->where('id',$txtidInvoice)->update('invoice');

        if($updateInvoice){
            $invoiceDetail = $this->model_invoice->getInvoiceDetailByInvoiceId($txtidInvoice);
            $txtTotalPembayarans = 0;
            foreach($invoiceDetail->result() as $detailInvoice){
                $txtTotalSatuan = $this->input->post('txtTotalSatuan_'.$detailInvoice->id);
                $txtTotalSatuanOld = $this->input->post('txtTotalSatuanOld_'.$detailInvoice->id);
                $txtQty = $this->input->post('txtQty_'.$detailInvoice->id);
                $txtQtyOld = $this->input->post('txtQtyOld_'.$detailInvoice->id);
                $txtTotal = $this->input->post('txtTotal_'.$detailInvoice->id);
                $txtTotalOld = $this->input->post('txtTotalOld_'.$detailInvoice->id);
                $txtTotalPembayarans = $txtTotalPembayarans + $txtTotal;
                if($txtTotalSatuan == $txtTotalSatuanOld && $txtQty == $txtQtyOld && $txtTotal == $txtTotalOld){
                    $updateInvoice = "";
                   // echo "disini";
                }else{
                    $insertInvoiceRR = $this->db->set('nomor_retur_revisi',$txtnoTransaksi)->set('invoice_tanda_terima_id',$getdataTT->id)->set('invoice_id',$txtidInvoice)->set('invoice_detail_id',$detailInvoice->id)->set('note',$txtNote)->set('qty_before',$txtQtyOld)->set('qty_change',$txtQty)->set('price_before',$txtTotalSatuanOld)->set('price_change',$txtTotalSatuan)->set('total_before',$txtTotalOld)->set('total_change',$txtTotal)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('invoice_retur_revisi');
                    $updateInvoice = $this->db->set('qty_kirim',$txtQty)->set('price',$txtTotalSatuan)->set('ttl_price',$txtTotal)->where('id',$detailInvoice->id)->update('invoice_detail');
                    if($detailInvoice->satuan == "Pcs"){
                    $getProdukByCode = $this->model_produk->getProductByCode($detailInvoice->product_code)->row();
                    $cekStok = $this->model_master->getGudangbyProductPerusahaan($getProdukByCode->id,$dataInv->perusahaan_id,$detailInvoice->gudang_id)->row();
                    $kurang = $txtQty - $txtQtyOld;
                    $pengurangan_stok = $cekStok->stok - $kurang;
                    $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                    }else{
                    //$cekProduks_ = $this->model_produk->getProductById($getKodeBayanganSet->id)->row();
                    $getProdukByCode = $this->model_produk->getProductByCode($detailInvoice->product_code)->row();
                    $getKodeBayanganSet = $this->model_produk->getProductsByKodeAndSatuan($getProdukByCode->product_code_shadow,"Set")->row();
                    $getKodeBayanganPcs = $this->model_produk->getProductsByKodeAndSatuan($getProdukByCode->product_code_shadow,"Pcs")->row();
                    $cekProduk_ = $this->model_produk->getProductById($getKodeBayanganSet->id)->row();
                    $cekStok = $this->model_master->getGudangbyProductPerusahaan($getKodeBayanganPcs->id,$dataInv->perusahaan_id,$detailInvoice->gudang_id)->row();
                    
                    $kurang = $txtQty - $txtQtyOld;
                    $qtyKurangLiner = $kurang * $cekProduk_->satuan_value;
                    $pengurangan_stok = $cekStok->stok - $qtyKurangLiner;
                    $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');   
                    }
                    
                }
                $stokPeng = $txtQtyOld - $txtQty;
                $produk_id = $this->model_produk->getProductByCode($detailInvoice->product_code)->row();
                if($stokPeng > 0){
                $insert_opname_stok_bm = $this->db->set('product_id',$produk_id->id)->set('transaction_no',$txtnoTransaksi)->set('gudang_id',$detailInvoice->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$stokPeng)->set('note','Retur Barang Masuk')->set('keterangan','Retur Barang Masuk dari No. Invoice '.$txtnoInvoice.'')->set('invoice_id',$txtidInvoice)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                }

                
            }

            $insertPayment = $this->db->set('no_tanda_terima',$txtnoTT)->set('member_id',$txtMemberId)->set('payment_id',$txtJenis)->set('payment_date',$txtPaymentDate)->set('cicilan_ke',$hitung)->insert('invoice_payment');
            $ppn = $txtTotalPembayarans * 10 / 100;
            $grandTotal = $txtTotalPembayarans + $ppn;
            $updateInvoice = $this->db->set('sub_total',$txtTotalPembayarans)->set('total_before_ppn',$txtTotalPembayarans)->set('total',$grandTotal)->where('id',$txtidInvoice)->update('invoice');
            $insert_role = $this->db->set('no_transaction',$dataInv->nonota)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Proses Retur Revisi - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');

        }

        echo "1";
    }


    }   

    public function saveReturRevisi(){
        $txtnoTT = $this->input->post('txtnoTT');
        $txtnoTransaksi = $this->input->post('txtnoTransaksi');
        $txtnoInvoice = $this->input->post('txtnoInvoice');
        $txtidInvoice = $this->input->post('txtidInvoice');
        $txtNote = $this->input->post('txtNote');
        $txtJenisTransaksi = $this->input->post('txtJenisTransaksi');
        $txtMemberId = $this->input->post('txtMemberId');
        $txtPaymentDate = $this->input->post('txtPaymentDate');
        $txtTotalPembayaran = $this->input->post('txtTotalPembayaran');
        $txtJenis = $this->input->post('txtJenis');
        $getdataTT = $this->model_invoice->getNoTTbyInvoiceId($txtidInvoice)->row();
        $getInvoice = $this->model_invoice->getInvoiceById($txtidInvoice)->row();
        $cek = $this->model_invoice->getPaymentInvoiceByMemberNoTTStatus($txtMemberId,$txtnoTT,0);
        if($cek->num_rows() > 0){
            $hitung = $cek->row()->cicilan_ke + 1;
        }else{
            $hitung = 1;
        }
        $dataInv = $this->model_invoice->getInvoiceById($txtidInvoice)->row();
        $ppn = $txtTotalPembayaran * 10 / 100;
        $grandTotal = $txtTotalPembayaran + $ppn;
        $updateInvoice = $this->db->set('sub_total',$txtTotalPembayaran)->set('total_before_ppn',$txtTotalPembayaran)->set('total',$grandTotal)->where('id',$txtidInvoice)->update('invoice');

        if($updateInvoice){
            $invoiceDetail = $this->model_invoice->getInvoiceDetailByInvoiceId($txtidInvoice);
            $txtTotalPembayarans = 0;
            foreach($invoiceDetail->result() as $detailInvoice){
                $txtTotalSatuan = $this->input->post('txtTotalSatuan_'.$detailInvoice->id);
                $txtTotalSatuanOld = $this->input->post('txtTotalSatuanOld_'.$detailInvoice->id);
                $txtQty = $this->input->post('txtQty_'.$detailInvoice->id);
                $txtQtyOld = $this->input->post('txtQtyOld_'.$detailInvoice->id);
                $txtTotal = $this->input->post('txtTotal_'.$detailInvoice->id);
                $txtTotalOld = $this->input->post('txtTotalOld_'.$detailInvoice->id);
                $txtTotalPembayarans = $txtTotalPembayarans + $txtTotal;
                if($txtTotalSatuan == $txtTotalSatuanOld && $txtQty == $txtQtyOld && $txtTotal == $txtTotalOld){
                    $updateInvoice = "";
                   // echo "disini";
                }else{
                    $insertInvoiceRR = $this->db->set('nomor_retur_revisi',$txtnoTransaksi)->set('invoice_tanda_terima_id',$getdataTT->id)
                    ->set('invoice_id',$txtidInvoice)
                    ->set('invoice_detail_id',$detailInvoice->id)
                    ->set('note',$txtNote)->set('qty_before',$txtQtyOld)
                    ->set('qty_change',$txtQty)->set('price_before',$txtTotalSatuanOld)
                    ->set('price_change',$txtTotalSatuan)->set('total_before',$txtTotalOld)
                    ->set('total_change',$txtTotal)->set('create_date',date("Y-m-d H:i:s"))
                    ->set('create_user',$_SESSION['rick_auto']['username'])->insert('invoice_retur_revisi');
                    $updateInvoice = $this->db->set('qty_kirim',$txtQty)->set('price',$txtTotalSatuan)->set('ttl_price',$txtTotal)->where('id',$detailInvoice->id)->update('invoice_detail');
                    if($detailInvoice->satuan == "Pcs"){
                    $getProdukByCode = $this->model_produk->getProductByCode($detailInvoice->product_code)->row();
                    $cekStok = $this->model_master->getGudangbyProductPerusahaan($getProdukByCode->id,$dataInv->perusahaan_id,$detailInvoice->gudang_id)->row();
                    //$kurang = $txtQty - $txtQtyOld;
                    $kurang = $txtQtyOld - $txtQty;
                    //$pengurangan_stok = $cekStok->stok - $kurang;
                    $pengurangan_stok = $cekStok->stok + $kurang;
                    $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                    }else{
                    //$cekProduks_ = $this->model_produk->getProductById($getKodeBayanganSet->id)->row();
                    $getProdukByCode = $this->model_produk->getProductByCode($detailInvoice->product_code)->row();
                    if($getProdukByCode->is_liner == 'Y') {
                    $getKodeBayanganSet = $this->model_produk->getProductsByKodeAndSatuan($getProdukByCode->product_code_shadow,"Set")->row();
                    $getKodeBayanganPcs = $this->model_produk->getProductsByKodeAndSatuan($getProdukByCode->product_code_shadow,"Pcs")->row();
                    $cekProduk_ = $this->model_produk->getProductById($getKodeBayanganSet->id)->row();
                    $cekStok = $this->model_master->getGudangbyProductPerusahaan($getKodeBayanganPcs->id,$dataInv->perusahaan_id,$detailInvoice->gudang_id)->row();
                    
                    //$kurang = $txtQty - $txtQtyOld;
                    $kurang = ($txtQtyOld - $txtQty) * $cekProduk_->satuan_value;
                }else{
                    $cekStok = $this->model_master->getGudangbyProductPerusahaan($getProdukByCode->id,$dataInv->perusahaan_id,$detailInvoice->gudang_id)->row();
                    $kurang = $txtQtyOld - $txtQty;
                }
                    $pengurangan_stok = $cekStok->stok + $kurang;

                    //$qtyKurangLiner = $kurang * $cekProduk_->satuan_value;
                    //$pengurangan_stok = $cekStok->stok - $qtyKurangLiner;
                    $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');   
                    }
                    
                }
                $stokPeng = $txtQtyOld - $txtQty;
                $produk_id = $this->model_produk->getProductByCode($detailInvoice->product_code)->row();
                if($stokPeng > 0){
                $insert_opname_stok_bm = $this->db->set('product_id',$produk_id->id)->set('transaction_no',$txtnoTransaksi)->set('gudang_id',$detailInvoice->gudang_id)->set('perusahaan_id',$getInvoice->perusahaan_id)->set('stock_input',$stokPeng)->set('note','Retur Barang Masuk')->set('keterangan','Retur Barang Masuk dari No. Invoice '.$txtnoInvoice.'')->set('invoice_id',$txtidInvoice)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('report_stok_bm_bl');
                }

                
            }

            $insertPayment = $this->db->set('no_tanda_terima',$txtnoTT)->set('member_id',$txtMemberId)->set('payment_id',$txtJenis)->set('payment_date',$txtPaymentDate)->set('cicilan_ke',$hitung)->insert('invoice_payment');
            $ppn = $txtTotalPembayarans * 10 / 100;
            $grandTotal = $txtTotalPembayarans + $ppn;
            $updateInvoice = $this->db->set('sub_total',$txtTotalPembayarans)->set('total_before_ppn',$txtTotalPembayarans)->set('total',$grandTotal)->where('id',$txtidInvoice)->update('invoice');
            $insert_role = $this->db->set('no_transaction',$dataInv->nonota)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Proses Retur Revisi - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');

        }

        echo "1";


    }   

    public function simpanReturRevisiPembayaran(){
        $cmbRetRev = $this->input->post('cmbRetRev');
        $DataRevRet = $this->model_invoice->getRevisiReturByNomor($cmbRetRev);
        $DataRevRetTotal = $this->model_invoice->getRevisiReturByNomorPembayaran($cmbRetRev)->row();
        $total_unit = 0;
        $total_grand = 0;
        foreach($DataRevRet->result() as $Datarev){
            $update_detail_invoice = $this->db->set('qty_kirim',$Datarev->qty_change)->set('price',$Datarev->price_change)->set('ttl_price',$Datarev->total_change)->where('id',$Datarev->invoice_detail_id)->update('invoice_detail');
            $total_unit = $total_unit + $Datarev->price_change;
            $total_grand = $total_unit + $Datarev->total_change;
        }
        $ppn = $DataRevRetTotal->grandTotalNew * 10 / 100;
        $grandTotal = $DataRevRetTotal->grandTotalNew + $ppn;
        $updateInvoice = $this->db->set('sub_total',$DataRevRetTotal->totalHargaNew)->set('total_before_ppn',$DataRevRetTotal->grandTotalNew)->set('total',$grandTotal)->where('id',$DataRevRet->row()->invoice_id)->update('invoice');
        if($updateInvoice){
            echo "1";
        }
    }

    public function simpan_pembayaran_(){
        $member_id = $this->input->post('member_id');
        $cmbPembayaran = $this->input->post('cmbPembayaran');
        $rupiah_input = $this->input->post('rupiah_input');
        $tanggal = $this->input->post('tanggal');
        $sisa_pembayaran = $this->input->post('sisa_pembayaran');
        $total = $sisa_pembayaran - str_replace(".","",$rupiah_input);
        $cek = $this->model_invoice->getPaymentInvoiceByMemberStatus($member_id,0);
        if($cek->num_rows() > 0){
            $hitung = $cek->row()->cicilan_ke + 1;
        }else{
            $hitung = 1;
        }
        if($total < 0){
            echo "2";
        }else{
            $insert = $this->db->set('member_id',$member_id)->set('payment_id',$cmbPembayaran)->set('payment_date',date("Y-m-d",strtotime("+0 day", strtotime($tanggal))))->set('sudah_dibayar',str_replace(".","",$rupiah_input))->set('sisa',$total)->set('total_pembayaran',$sisa_pembayaran)->set('cicilan_ke',$hitung)->insert('invoice_payment');

            if($total == 0 || $total <= 6000){
                $update_status_invoice = $this->db->set('pay_status',1)->where('member_id',$member_id)->where('pay_status',0)->update('invoice');
                $update_status_payment_invoice = $this->db->set('flag',1)->where('member_id',$member_id)->update('invoice_payment');
                $getInvoiceMember = $this->model_invoice->getInvoiceByMember($member_id,1);
                foreach($getInvoiceMember->result() as $invMember){
                    $fee = $invMember->total * 0.5 / 100;
                    $cekData = $this->model_invoice->getCekFeeSalesByInvoice($invMember->id);
                    if($cekData->num_rows() > 0){

                    }else{
                        $insert_sales_fee = $this->db->set('invoice_id',$invMember->id)->set('sales_id',$invMember->sales_id)->set('fee',$fee)->insert('transaction_sales_fee');
                    } 

                    // $detailInvoice = $this->model_invoice->getInvoiceDetailByInvoiceId($invMember->id);
                    
                    // foreach($detailInvoice->result() as $detailInv){
                    //     $data_produk = $this->model_produk->getProductByCode($detailInv->product_code)->row();
                    //     $cekStok = $this->model_master->getGudangbyProductPerusahaan($data_produk->id,$invMember->perusahaan_id,$detailInv->gudang_id)->row();
                    //     //print_r($detailInv->qty_kirim);
                    //     $pengurangan_stok = $cekStok->stok - $detailInv->qty_kirim;
                    //   //  echo "".$detailInv->qty_kirim."";
                    //   $update_stok = $this->db->set('stok',$pengurangan_stok)->where('id',$cekStok->id)->update('product_perusahaan_gudang');
                    // }
                }
            }
            echo "1";
        }
    }

    public function pilihMenu(){
        $uri4 = $this->input->post('uri4');
        $perusahaan = $this->input->post('perusahaan');
        $invoice = $this->input->post('invoice');

        echo"
            <div class='form-group has-feedback'>
                <a href=".base_url("admin/invoice/print_surat_jalan/".$uri4."/".$perusahaan."/".$invoice."/excel")." target='_blank' class='btn bg-green'><i class=' icon-file-download2 position-left'></i>Export Excel</a>
            </div>
            <div class='form-group has-feedback'>
                <a href=".base_url("admin/invoice/print_surat_jalan/".$uri4."/".$perusahaan."/".$invoice."/print")." target='_blank' class='btn bg-brown'><i class='icon-printer2 position-left'></i>Print</a>
            </div>
            <div class='form-group has-feedback'>
                <a href=".base_url("admin/invoice/print_surat_jalan/".$uri4."/".$perusahaan."/".$invoice."/pdf")." target='_blank' class='btn bg-danger'><i class='icon-printer2 position-left'></i>PDF</a>
            </div>
        ";
    }

    public function changeTanggal(){
        $id = $this->input->post('id');
        $data = $this->model_invoice->getInvoiceById($id)->row();
        echo"
            <!-- <div class='form-group has-feedback'>
                <label>Tanggal Nota</label>
                <input type='date' class='form-control' id='tanggal_change' name='tanggal_change' value='". date("Y-m-d",strtotime($data->dateprint))."'>
            </div>
            <div class='form-group has-feedback'>
                <a href='#!' onclick=javascript:saveDatePrint(".$id.") class='btn btn-primary'>Simpan Tanggal<i class='icon-arrow-right14 position-right'></i></a>
            </div> -->
            <div class='form-group has-feedback'>
                <a href='#!' onclick=javascript:printInvoice('".base64_encode($id)."','print') class='btn bg-brown'><i class='icon-printer2 position-left'></i>Print</a>
                <a href='#!' onclick=javascript:printInvoice('".base64_encode($id)."','excel') class='btn bg-success'><i class='icon-printer2 position-left'></i>Excel</a>
                <a href='#!' onclick=javascript:printInvoice('".base64_encode($id)."','pdf') class='btn bg-danger'><i class='icon-printer2 position-left'></i>PDF</a>
            </div>
        ";
    }

    public function changePackingList(){
        $id = $this->input->post('id');
        echo"
        <div class='form-group has-feedback'>
                <a href='#!' onclick=javascript:printPackingList('".base64_encode($id)."','print') class='btn bg-brown'><i class='icon-printer2 position-left'></i>Print</a>
                <a href='#!' onclick=javascript:printPackingList('".base64_encode($id)."','excel') class='btn bg-success'><i class='icon-printer2 position-left'></i>Excel</a>
                <a href='#!' onclick=javascript:printPackingList('".base64_encode($id)."','pdf') class='btn bg-danger'><i class='icon-printer2 position-left'></i>PDF</a>
            </div>
        ";

    }

    public function changeTandaTerima(){
        $noTT = $this->input->post('noTT');
        $perusahaan = $this->input->post('perusahaan');
        echo"
        <div class='form-group has-feedback'>
                <a href='#!' onclick=javascript:printTandaTerima('".$noTT."','".$perusahaan."','print') class='btn bg-brown'><i class='icon-printer2 position-left'></i>Print</a>
                <a href='#!' onclick=javascript:printTandaTerima('".$noTT."','".$perusahaan."','excel') class='btn bg-success'><i class='icon-printer2 position-left'></i>Excel</a>
                <a href='#!' onclick=javascript:printTandaTerima('".$noTT."','".$perusahaan."','pdf') class='btn bg-danger'><i class='icon-printer2 position-left'></i>PDF</a>
            </div>
        ";

    }

    public function changeTandaTerimaDelivery(){
        $noTT = $this->input->post('noTT');
        $perusahaan = $this->input->post('perusahaan');
        echo"
        <div class='form-group has-feedback'>
                <a href='#!' onclick=javascript:printTandaTerimaDelivery('".$noTT."','".$perusahaan."','print') class='btn bg-brown'><i class='icon-printer2 position-left'></i>Print</a>
                <a href='#!' onclick=javascript:printTandaTerimaDelivery('".$noTT."','".$perusahaan."','excel') class='btn bg-success'><i class='icon-printer2 position-left'></i>Excel</a>
                <a href='#!' onclick=javascript:printTandaTerimaDelivery('".$noTT."','".$perusahaan."','pdf') class='btn bg-danger'><i class='icon-printer2 position-left'></i>PDF</a>
            </div>
        ";

    }

    public function changeAmplop(){
        $id = $this->input->post('id');
        echo"
        <div class='form-group has-feedback'>
                <a href='#!' onclick=javascript:printAmplop('".base64_encode($id)."','print') class='btn bg-brown'><i class='icon-printer2 position-left'></i>Print</a>
                <a href='#!' onclick=javascript:printAmplop('".base64_encode($id)."','excel') class='btn bg-success'><i class='icon-printer2 position-left'></i>Excel</a>
                <a href='#!' onclick=javascript:printAmplop('".base64_encode($id)."','pdf') class='btn bg-danger'><i class='icon-printer2 position-left'></i>PDF</a>
            </div>
        ";

    }

    public function saveDatePrint(){
        $id = $this->input->post('id');
        $tanggal_change = $this->input->post('tanggal_change');

        $update = $this->db->set('dateprint',$tanggal_change)->where('id',$id)->update('invoice');
        if($update){
            echo "1";
        }
    }

    public function printInvoice(){
        $id = base64_decode($this->input->post('id'));
        $tanggal_change = $this->input->post('tanggal_change');

        $update = $this->db->set('dateprint',$tanggal_change)->where('id',$id)->update('invoice');
    }

    public function pilihMenuInv(){

        echo"
            <div class='form-group has-feedback'>
                <a href='#!' onclick=javascript:filter_report_tanda_terima('excel') class='btn bg-green'><i class=' icon-file-download2 position-left'></i>Export Excel</a>
            </div>
            <div class='form-group has-feedback'>
                <a href='#!' onclick=javascript:filter_report_tanda_terima('print') class='btn bg-brown'><i class='icon-printer2 position-left'></i>Print</a>
            </div>
            <div class='form-group has-feedback'>
                <a href='#!' onclick=javascript:filter_report_tanda_terima('pdf') class='btn btn-danger'><i class='icon-file-download position-left'></i>Export PDF</a>
            </div>
        ";
    }

    public function process_tanda_terima_(){
        $member = $this->input->post('member');
        $cekData = $this->model_invoice->getInvoiceTandaTerima();
        $activeNota = $this->input->post('activeNota');
        $tanggal_hari_ini = date("dmyhis");
        if($cekData->num_rows() > 0){
            $tt_unik = $cekData->row()->id + 1;
            $nott = "TT-".$tanggal_hari_ini."-".sprintf("%'.05d", $tt_unik);
        }else{
            $tt_unik = 1;
            $nott = "TT-".$tanggal_hari_ini."-".sprintf("%'.05d", $tt_unik);
        }
        if($activeNota == "" || $activeNota == 0){
            //$getData = $this->model_invoice->getInvoiceByMembers($member,0);
        }else{
            $getData = $this->model_invoice->getInvoiceByMembersSave(0,$activeNota);
        }
        foreach($getData->result() as $data){
            $insert = $this->db->set('no_tanda_terima',$nott)->set('invoice_id',$data->id)->set('member_id',$data->member_id)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('invoice_tanda_terima');
            //if($insert){
                $update = $this->db->set('flag_tanda_terima',1)->where('id',$data->id)->update('invoice');
            //}
        }

        echo "1";
        unset($_SESSION['rick_auto']['filter_start_date']);
        unset($_SESSION['rick_auto']['filter_end_date']);
        unset($_SESSION['rick_auto']['filter_sales']);
        unset($_SESSION['rick_auto']['filter_invoice_no']);
        unset($_SESSION['rick_auto']['filter_perusahaan']);
        unset($_SESSION['rick_auto']['filter_city']);
    }


    public function process_tanda_terima__(){
        $activeNota = $this->input->post('activeNota');
        $member = $this->input->post('member');
        if($activeNota == "" || $activeNota == 0){
            //$getData = $this->model_invoice->getInvoiceByMembers($member,0);
        }else{
            $getData = $this->model_invoice->getInvoiceByMembersSave(0,$activeNota);
        }

        $namacutt = $getData->row()->perusahaan_name;
        $namaptt = substr($namacutt,0,3);
        $namaa = substr($namacutt,4);
        $arrr = explode(' ', $namaa);
        $singkatann = "";
        foreach($arrr as $kataa)
        {
        $singkatann .= substr($kataa, 0, 1);
        }

        $namaptsjj = $namaptt."".strtoupper($singkatann);

        $cekData = $this->model_invoice->getInvoiceTandaTerimaByNOTT($namaptsjj);
        foreach($getData->result() as $data){
        $namacut = $data->perusahaan_name;
        $namapt = substr($namacut,0,3);
        $nama = substr($namacut,4);
        $arr = explode(' ', $nama);
        $singkatan = "";
        foreach($arr as $kata)
        {
        $singkatan .= substr($kata, 0, 1);
        }

        $namaptsj = $namapt."".strtoupper($singkatan);

        //$cekData = $this->model_invoice->getInvoiceTandaTerimaByNOTT($namaptsj);
        //$cekData = $this->model_invoice->getInvoiceTandaTerimaByNOTTMmeber($namaptsjj,$data->member_id);
        
        $tanggal_hari_ini = date("dmyhis");
        if($cekData->num_rows() > 0){
            //$noGen = explode("-",$cekData->row()->no_tanda_terima);
            $noGen = explode("-",$cekData->row()->no_tanda_terima);
            $tt_unik = $noGen[1] + 1;
            $nott = "".$namaptsj."-".sprintf("%'.04d", $tt_unik);
        }else{
            $tt_unik = 1;
            $nott = "".$namaptsj."-".sprintf("%'.04d", $tt_unik);
        }
            $insert = $this->db->set('no_tanda_terima',$nott)->set('invoice_id',$data->id)->set('member_id',$data->member_id)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->insert('invoice_tanda_terima');
            if($insert){
            $getInvoice  = $this->model_invoice->getInvoiceByIdd($data->id)->row();
            $insert_payment = $this->db->set('no_tanda_terima',$nott)->set('member_id',$data->member_id)->set('payment_id',2)->set('payment_date',date("Y-m-d H:i:s"))->set('liquid_date',date("Y-m-d H:i:s"))->set('sudah_dibayar',str_replace(".","",0))->set('sisa',$getInvoice->total)->set('total_pembayaran',$getInvoice->total)->set('cicilan_ke',0)->insert('invoice_payment');
            
                if($insert_payment){
                    $update = $this->db->set('flag_tanda_terima',1)->where('id',$data->id)->update('invoice');
                }

                $insert_role = $this->db->set('no_transaction',$data->nonota)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Proses Tanda Terima - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
            }

            //if($insert){
                
            //}
        }

        echo "1";
        unset($_SESSION['rick_auto']['filter_start_date']);
        unset($_SESSION['rick_auto']['filter_end_date']);
        unset($_SESSION['rick_auto']['filter_sales']);
        unset($_SESSION['rick_auto']['filter_invoice_no']);
        unset($_SESSION['rick_auto']['filter_perusahaan']);
        unset($_SESSION['rick_auto']['filter_city']);
    }

    public function process_tanda_terima(){
        $activeNota = $this->input->post('activeNota');
        $member = $this->input->post('member');
        if($activeNota == "" || $activeNota == 0){
            //$getData = $this->model_invoice->getInvoiceByMembers($member,0);
        }else{
            $getData    = $this->model_invoice->getInvoiceByMembersSave(0,$activeNota);
            $getDataRow = $this->model_invoice->getInvoiceMultipleDesc(0,$activeNota)->row();
        }

        $namacutt = $getData->row()->perusahaan_name;
        $namaptt = substr($namacutt,0,3);
        $namaa = substr($namacutt,4);
        $arrr = explode(' ', $namaa);
        $singkatann = "";
        foreach($arrr as $kataa)
        {
        $singkatann .= substr($kataa, 0, 1);
        }

        $namaptsjj = $namaptt."".strtoupper($singkatann);

        //$cekData = $this->model_invoice->getInvoiceTandaTerimaByNOTT($namaptsjj);
        foreach($getData->result() as $data){
        $namacut = $data->perusahaan_name;
        $namapt = substr($namacut,0,3);
        $nama = substr($namacut,4);
        $arr = explode(' ', $nama);
        $singkatan = "";
        foreach($arr as $kata)
        {
        $singkatan .= substr($kata, 0, 1);
        }
        $namaptsjs = strtoupper($singkatan);

        $namaptsj = $namapt."".strtoupper($singkatan);

        $cekData = $this->model_invoice->getInvoiceTandaTerimaByNOTT($namaptsjs);
        //$cekData = $this->model_invoice->getInvoiceTandaTerimaByNOTTMmeber($namaptsjj,$data->member_id);
        
        $tanggal_hari_ini = date("dmyhis");
        if($cekData->num_rows() > 0){
            //$noGen = explode("-",$cekData->row()->no_tanda_terima);
            $noGen = explode("-",$cekData->row()->no_tanda_terima);
            $tt_unik = $noGen[1] + 1;
            //$tt_unik = $noGen[1] + 0;
            $nott = "".$namaptsj."-".sprintf("%'.04d", $tt_unik);
            $nottt = "".$namaptsj."-".sprintf("%'.04d", $tt_unik)."-".$data->member_id;
        }else{
            $tt_unik = 1;
            $nott = "".$namaptsj."-".sprintf("%'.04d", $tt_unik);
            $nottt = "".$namaptsj."-".sprintf("%'.04d", $tt_unik)."-".$data->member_id;
        }
            $insert = $this->db->set('no_tanda_terima',$nott)->set('no_tanda_terima_temp',$nottt)->set('invoice_id',$data->id)->set('member_id',$data->member_id)->set('perusahaan_id',$data->perusahaan_id)->set('nilai',$data->total)->set('create_date',date("Y-m-d H:i:s"))->set('create_user',$_SESSION['rick_auto']['username'])->set('invoice_date',date("Y-m-d",strtotime($getDataRow->dateorder)))->insert('invoice_tanda_terima_temp');

            if($insert){
               
                $updates = $this->db->set('flag_tanda_terima',1)->set('invoice_date_tt',date("Y-m-d",strtotime($getDataRow->dateorder)))->where('id',$data->id)->update('invoice');
            }

            //if($insert){
                
            //}
        }

        echo "1";
        unset($_SESSION['rick_auto']['filter_start_date']);
        unset($_SESSION['rick_auto']['filter_end_date']);
        unset($_SESSION['rick_auto']['filter_sales']);
        unset($_SESSION['rick_auto']['filter_invoice_no']);
        unset($_SESSION['rick_auto']['filter_perusahaan']);
        unset($_SESSION['rick_auto']['filter_city']);


    }

    public function process_tanda_terima_insert(){
        
        $getDataTemps = $this->model_invoice->getTandaTerimaTemps();
        //$no = 0;
        foreach($getDataTemps->result() as $dataTemp){
            
            //$getDataTempsPerusahaan = $this->model_invoice->getTandaTerimaTempsByPerusahaanTTTemp($dataTemp->perusahaan_id,$dataTemp->no_tanda_terima_temp);
            //foreach($getDataTempsPerusahaan->result() as $dataPerusahaan){
            //$jmlData = $this->model_invoice->getCountTandaTerimaTempsByPerusahaanTTTemp($dataTemp->perusahaan_id)->row();
            // if($jmlData->jmlData > 1){
            // $jmlDatas = 1;
            // }else{
            //  $jmlDatas = $jmlData->jmlData - 1;   
            // }
            //$jmlDatas = $jmlData->jmlData - 1;  
            $jmlData = $this->model_invoice->getCountTandaTerimaTempsByPerusahaanTTTemp($dataTemp->perusahaan_id)->num_rows();
            //$jmlDatas = $jmlData - 1; 
            //$s = 0-1;
            for($i=0;$i<=$jmlData;$i++){
            //echo $i;
            //$no++;
            //$nos = $no - 1;
            //echo $nos;
            $nos = $i;
            $noGen = explode("-",$dataTemp->no_tanda_terima);
            $noGens = $noGen[1] + $nos;
            //$noGenss = $noGen[1] - 1;
            //$noGens = $noGen[1];
            $pt = $noGen[0]."-".sprintf("%'.04d", $noGens);

            //echo $pt;
            //$update = $this->db->set('no_tanda_terima',$pt)->where('no_tanda_terima_temp',$dataTemp->no_tanda_terima_temp)->update('invoice_tanda_terima_temp');
            $update = $this->db->set('no_tanda_terima',$pt)->where('no_tanda_terima_temp',$dataTemp->no_tanda_terima_temp)->update('invoice_tanda_terima_temp');
            }
            //}

        }

        // $getDataTemps2 = $this->model_invoice->getTandaTerimaTempGroupByTT();
        // $noTT = 0;
        // foreach($getDataTemps2->result() as $dataTemp2){
        //     $noTT++;
        //     $getDataAsc = $this->model_invoice->getTandaTerimaAsc()->row();
        //     $noGen2 = explode("-",$getDataAsc->no_tanda_terima);
        //     $noGens2 = $noGen2[1] + $nos;
        //     $pt2 = $dataTemp2[0]."-".sprintf("%'.04d", $noGens2);

        //     $update = $this->db->set('no_tanda_terima',$pt2)->where('no_tanda_terima_temp',$dataTemp->no_tanda_terima_temp)->update('invoice_tanda_terima_temp');

        // }
        $getTTTemp = $this->model_invoice->getTandaTerimaTemp();

        foreach($getTTTemp->result() as $gtt){
            $gtt_nott = explode("-",$gtt->no_tanda_terima);
            $ngtt = $gtt_nott[1] - 1; 
            $noTT = $gtt_nott[0]."-".sprintf("%'.04d", $ngtt);
            $insert = $this->db->set('no_tanda_terima',$noTT)->set('no_tanda_terima_temp',$gtt->no_tanda_terima_temp)->set('invoice_id',$gtt->invoice_id)->set('member_id',$gtt->member_id)->set('resi_no',$gtt->resi_no)->set('expedisi',$gtt->expedisi)->set('delivery_date',$gtt->delivery_date)->set('nilai',$gtt->nilai)->set('create_date',$gtt->create_date)->set('create_user',$gtt->create_user)->set('invoice_date',$gtt->invoice_date)->insert('invoice_tanda_terima');
             $getInvoice  = $this->model_invoice->getInvoiceByIdd($gtt->invoice_id)->row();
             $insert_payment = $this->db->set('no_tanda_terima',$noTT)->set('member_id',$gtt->member_id)->set('payment_id',7)->set('payment_date',date("Y-m-d H:i:s"))->set('liquid_date',date("Y-m-d H:i:s"))->set('sudah_dibayar',str_replace(".","",0))->set('sisa',$getInvoice->total)->set('total_pembayaran',$getInvoice->total)->set('cicilan_ke',0)->insert('invoice_payment');
             $idPayment = $this->db->insert_id();
             $data_nota2 = $this->model_invoice->getTandaTerimaByNoTandaTerima($noTT);
            foreach($data_nota2->result() as $nota2){
            $insertPiutang = $this->db->set('invoice_id',$nota2->invoice_id)->set('no_tt',$noTT)->set('invoice_payment_id',$idPayment)->set('total',$getInvoice->total)->set('sisa',$getInvoice->total)->set('payment_id',7)->set('tanggal',date("Y-m-d H:i:s"))->set('flag',0)->insert('invoice_piutang');
            }
        }

        //$insert = $this->db->query("INSERT INTO invoice_tanda_terima (SELECT * FROM invoice_tanda_terima_temp)");
        if($insert){
            $this->db->query('TRUNCATE TABLE invoice_tanda_terima_temp');
            echo "1";
            
        }

    }

    public function process_tanda_terima_insert_(){
        
        $getDataTemps = $this->model_invoice->getTandaTerimaTemps();
        $no = 0;
        foreach($getDataTemps->result() as $dataTemp){
            
            //$getDataTempsPerusahaan = $this->model_invoice->getTandaTerimaTempsByPerusahaanTTTemp($dataTemp->perusahaan_id,$dataTemp->no_tanda_terima_temp);
            //foreach($getDataTempsPerusahaan->result() as $dataPerusahaan){
            $no++;
            $nos = $no - 1;
            //echo $nos;
            $noGen = explode("-",$dataTemp->no_tanda_terima);
            $noGens = $noGen[1] + $nos;
            //$noGens = $noGen[1];
            $pt = $noGen[0]."-".sprintf("%'.04d", $noGens);
            //echo $pt;
            $update = $this->db->set('no_tanda_terima',$pt)->where('no_tanda_terima_temp',$dataTemp->no_tanda_terima_temp)->update('invoice_tanda_terima_temp');
            //}

        }

        $getTTTemp = $this->model_invoice->getTandaTerimaTemp();

        foreach($getTTTemp->result() as $gtt){
            $insert = $this->db->set('no_tanda_terima',$gtt->no_tanda_terima)->set('no_tanda_terima_temp',$gtt->no_tanda_terima_temp)->set('invoice_id',$gtt->invoice_id)->set('member_id',$gtt->member_id)->set('resi_no',$gtt->resi_no)->set('expedisi',$gtt->expedisi)->set('delivery_date',$gtt->delivery_date)->set('nilai',$gtt->nilai)->set('create_date',$gtt->create_date)->set('create_user',$gtt->create_user)->insert('invoice_tanda_terima');
             $getInvoice  = $this->model_invoice->getInvoiceByIdd($gtt->invoice_id)->row();
             $insert_payment = $this->db->set('no_tanda_terima',$gtt->no_tanda_terima)->set('member_id',$gtt->member_id)->set('payment_id',2)->set('payment_date',date("Y-m-d H:i:s"))->set('liquid_date',date("Y-m-d H:i:s"))->set('sudah_dibayar',str_replace(".","",0))->set('sisa',$getInvoice->total)->set('total_pembayaran',$getInvoice->total)->set('cicilan_ke',0)->insert('invoice_payment');
        }

        //$insert = $this->db->query("INSERT INTO invoice_tanda_terima (SELECT * FROM invoice_tanda_terima_temp)");
        if($insert){
            $this->db->query('TRUNCATE TABLE invoice_tanda_terima_temp');
            echo "1";
            
        }

    }

    public function view_print_perusahaan(){
        $no_tanda_terima = $this->input->post('no_tanda_terima');
        $Data = $this->model_invoice->getTandaTerimaByNoTandaTerimaPerusahaan($no_tanda_terima);
        foreach($Data->result() as $data){
        echo"
            <div class='form-group has-feedback'>
                <a href='".base_url("admin/invoice/print_tandaterima/".$no_tanda_terima."/".$data->perusahaan_id."")."' target='_blank' class='btn bg-brown'><i class='icon-printer2 position-left'></i>Print dari ".$data->nama_perusahaan."</a>
            </div>
        ";
        }
    }

    public function view_input_pengiriman(){
        $no_tanda_terima = $this->input->post('nott');
        $Data = $this->model_invoice->getTandaTerimaByNoTandaTerimaPerusahaanNoGroup($no_tanda_terima);
        echo"
        <!-- Masked inputs -->
        <div class='panel panel-flat'>
            <div class='panel-heading'>
                <h5 class='panel-title'>Input Data Pengiriman</h5>
            </div>
            ";
            $items = array();
            foreach($Data->result() as $data){
                $items[] = $data->id;
                echo"
            <div class='panel-heading'>
                <h5 class='panel-title'>Invoice No : <b>".$data->no_nota." : </b></h5>
            </div>
            <div class='panel-body'>
                <div class='row'>";
                if($data->delivery_date == ""){
                    $date_kirim = "".date("Y-m-d",strtotime("-1 day", strtotime(date("Y-m-d"))))."";
                    //$nooo = 1;
                }else{
                    $date_kirim = "".date("Y-m-d",strtotime("+0 day", strtotime($data->delivery_date)))."";
                    //$nooo = 2;
                }echo"
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Tanggal Kirim: </label>
                            <input type='date' id='delivery_date_".$data->id."' name='delivery_date_".$data->id."' class='form-control' data-mask='99/99/9999' placeholder='Masukkan Tanggal Kirim' value='".$date_kirim."'>
                        </div>                 
                    </div>

                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Expedisi: </label>
                             <select id='expedisi_".$data->id."' name='expedisi_".$data->id."' class='select select2-hidden-accessible' tabindex='-1' aria-hidden='true'>
                        <option selected disabled>Pilih Expedisi</option>
                        ";
                        $expedisi = $this->model_master->getExpedisi();
                        foreach($expedisi->result() as $exp){
                            echo"
                            <option value='".$exp->name."' ";if($exp->name == $data->expedisi){echo"selected";}echo">".$exp->name."</option>
                            ";
                            }echo"
                    </select>
                        </div>
                    </div>

                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>No Resi </label>
                            <input type='text' id='resi_no_".$data->id."' name='resi_no_".$data->id."'  class='form-control' placeholder='Masukkan No Resi' value='".$data->resi_no."'>
                        </div>
                    </div>

                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label>Nilai (Rp.) </label>
                            <input type='text' id='nilai_".$data->id."' name='nilai_".$data->id."'  class='form-control' placeholder='Masukkan No Resi' value='".$data->nilai."'>
                        </div>
                    </div>

                </div>  
            </div>
        <script>
        $('#expedisi_".$data->id."').select2();
        </script>
            ";}

            $str = implode (", ", $items);
            echo"
            <input type='hidden' id='id_arr' name='id_arr' value='".$str."'>
        </div>
        <!-- /masked inputs -->

        ";
    }

    public function showInputPengiriman(){
        $id = $this->input->post('id');
        $data =  $this->model_invoice->getInvoiceById($id)->row();

        echo"
        <!-- Masked inputs -->
        <div class='panel panel-flat'>
            <div class='panel-heading'>
                <h5 class='panel-title'>Input Data Pengiriman</h5>
            </div>

            <div class='panel-body'>
                <div class='row'>";
                if($data->delivery_date == ""){
                    $date_kirim = "".date("Y-m-d",strtotime("-1 day", strtotime(date("Y-m-d"))))."";
                    //$nooo = 1;
                }else{
                    $date_kirim = "".date("Y-m-d",strtotime("+0 day", strtotime($data->delivery_date)))."";
                    //$nooo = 2;
                }echo"
                    <div class='col-md-4'>
                        <div class='form-group'>
                            <label>Tanggal Kirim: </label>
                            <input type='date' id='delivery_date' name='delivery_date' class='form-control' data-mask='99/99/9999' placeholder='Masukkan Tanggal Kirim' value='".$date_kirim."'>
                        </div>                 
                    </div>

                    <div class='col-md-4'>
                        <div class='form-group'>
                            <label>Expedisi: </label>
                             <select id='expedisi' name='expedisi' class='form-control'>
                        <option selected disabled>Pilih Expedisi</option>
                        ";
                        $expedisi = $this->model_master->getExpedisi();
                        foreach($expedisi->result() as $exp){
                            echo"
                            <option value='".$exp->name."' ";if($exp->name == $data->expedisi){echo"selected";}echo">".$exp->name."</option>
                            ";
                            }echo"
                    </select>
                        </div>
                    </div>

                    <div class='col-md-4'>
                        <div class='form-group'>
                            <label>No Resi </label>
                            <input type='text' id='resi_no' name='resi_no'  class='form-control' placeholder='Masukkan No Resi' value='".$data->resi_no."'>
                        </div>
                    </div>

                </div>  
            </div>
        </div>
        <!-- /masked inputs -->

        ";
    }

    public function save_input_pengiriman(){
        $nott = $this->input->post('nott');
        
        $Data = $this->model_invoice->getTandaTerimaByNoTandaTerimaPerusahaanNoGroup($nott);
        foreach($Data->result() as $data){
            $no_resi = $this->input->post('resi_no_'.$data->id);
            $expedisi = $this->input->post('expedisi_'.$data->id);
            $delivery_date = $this->input->post('delivery_date_'.$data->id);
            $delivery_dates = date("Y-m-d",strtotime("+0 day", strtotime($delivery_date)));
            $nilai = $this->input->post('nilai_'.$data->id);
            $update = $this->db->set('resi_no',$no_resi)->set('expedisi',$expedisi)->set('delivery_date',$delivery_dates)->set('nilai',$nilai)->where('id',$data->id)->update('invoice_tanda_terima');
            }
        //if($update){
            echo "1";
        //}
    }

    public function save_input_pengiriman_invoice(){
        $id = $this->input->post('id');
        $data = $this->model_invoice->getInvoiceById($id)->row();
        $resi_no = $this->input->post('resi_no');
        $expedisi = $this->input->post('expedisi');
        $delivery_date = $this->input->post('delivery_date');
        $delivery_dates = date("Y-m-d",strtotime("+0 day", strtotime($delivery_date)));
        $update = $this->db->set('expedisi',$expedisi)->set('resi_no',$resi_no)->set('delivery_date',$delivery_dates)->where('id',$id)->update('invoice');
        $insert_role = $this->db->set('no_transaction',$data->nonota)
                                ->set('flag_level',$_SESSION['rick_auto']['flag_user'])
                                ->set('user',$_SESSION['rick_auto']['fullname'])
                                ->set('action','Simpan Data Expedisi Invoice - '.$_SESSION['rick_auto']['fullname'])
                                ->set('create_date',date("Y-m-d H:i:s"))
                                ->insert('role_transaksi');
        if($update){
            echo "1";
        }
    }

    public function simpanAjax(){
        $txtTotalSatuan = $this->input->post('txtTotalSatuan');
        $txtQty = $this->input->post('txtQty');
        
        $total = $txtTotalSatuan * $txtQty;
        $ppn = $total * 10 / 100;
        $grandTotal = $total + $ppn;
        echo json_encode(array('Qty'=>$txtQty,'ppn'=>number_format($ppn,2,',','.'),'priceSatuan'=>$txtTotalSatuan,'priceSatuanrp'=>number_format($txtTotalSatuan,2,',','.'),'priceTotal'=>$total,'priceTotalrp'=>number_format($total,2,',','.')));
    }

    public function hitung_grand_total(){
        $id = $this->input->post('id_invoice');
        $invoice = $this->model_invoice->getInvoiceById($id)->row();
        $invoiceDetails = $this->model_invoice->getInvoiceDetailByInvoiceId($invoice->id);
            $total_pembayaran = 0;
            foreach($invoiceDetails->result() as $invoiceDetail){
                $txtTotal = $this->input->post('txtTotal_'.$invoiceDetail->id);
                $total_pembayaran =  $total_pembayaran + $txtTotal;
               // echo $txtTotal;
            }
            $ppn = $total_pembayaran * 10 / 100;
            $grandTotal = $total_pembayaran + $ppn;

            //echo number_format($total_pembayaran,2,',','.');
            echo json_encode(array('total_pembayaran'=>number_format($total_pembayaran,2,',','.'),'grandTotal'=>number_format($grandTotal,2,',','.'),'ppn'=>number_format($ppn,2,',','.')));
            //echo json_encode(array('priceTotal'=>number_format($total_pembayaran,2,',','.')));
    }

    public function print_retur_revisi(){
        $id = base64_decode($this->uri->segment(4));
        $data = $this->model_invoice->getRevisiReturById($id)->row();
        $this->data['dataRR'] = $this->model_invoice->getRevisiReturByNomor($data->nomor_retur_revisi);
        $this->load->view('admin/invoice/bg_print_retur_revisi',$this->data);
    }

    public function filter_retur_revisi(){
        $invoice_no = $this->input->post('invoice_no');
        $cmbPerusahaanFilter = $this->input->post('cmbPerusahaanFilter');
        $cmbMemberFilter = $this->input->post('cmbMemberFilter');
        $rr_no = $this->input->post('rr_no');

        $_SESSION['rick_auto']['filter_member_rr'] = $cmbMemberFilter;
        $_SESSION['rick_auto']['filter_invoice_no_rr'] = $invoice_no;
        $_SESSION['rick_auto']['filter_perusahaan_rr'] = $cmbPerusahaanFilter; 
        $_SESSION['rick_auto']['filter_no_retur_revisi']  = $rr_no;
    }

    public function retur_revisi_view(){
        $this->data['getData'] = $this->model_invoice->getInvoiceByAllMembersRevisii();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getMember'] = $this->model_master->getAllMembers();
        $this->template->rick_auto('retur_revisi/bg_index',$this->data);
    }

    public function report_retur_revisi(){
        $this->data['getData'] = $this->model_invoice->getRevisiReturs();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->template->rick_auto('retur_revisi/bg_report',$this->data); 
    }

    public function report_rekap_invoice(){
        $this->data['getInvoice'] = $this->model_invoice->getInvoiceByAllMemberssRekapInvoice();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->template->rick_auto('invoice/bg_report_rekap_invoice',$this->data);

    }

    public function print_report_rekap_invoice(){
       // echo $_SESSION['rick_auto']['filter_tanggal_rriss'];
        $jenis = $this->uri->segment(4);
        $this->data['getInvoice'] = $this->model_invoice->getInvoiceByAllMemberssRekapInvoice();
        if($jenis != "pdf"){
        $this->load->view('admin/invoice/bg_print_report_rekap_invoice',$this->data);
        }else{
            $content = $this->load->view('admin/invoice/bg_print_report_rekap_invoice',$this->data,TRUE);
            $this->template->print2pdf('Print_PDF',$content,'Report_Rekap_Invoice');
        }

    }

    public function filter_report_rekap_invoice(){
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $tanggalFrom = $this->input->post('tanggalFrom');
        $tanggalTo = $this->input->post('tanggalTo');

        $_SESSION['rick_auto']['filter_perusahaan_rri'] = $cmbPerusahaan;
        $_SESSION['rick_auto']['filter_tanggal_rri'] = $tanggalFrom;
        $_SESSION['rick_auto']['filter_tanggal_rriss'] = $tanggalFrom;
        $_SESSION['rick_auto']['filter_tanggal_to_rri'] = $tanggalTo;
        $_SESSION['rick_auto']['filter_tanggal_to_rrii'] = $tanggalTo;
        //echo $_SESSION['rick_auto']['filter_tanggal_rri'];
        $getInvoice = $this->model_invoice->getInvoiceByAllMemberssRekapInvoice();
        $no = 0;
            foreach($getInvoice->result() as $invoice){
                $no++;
                echo"
                <tr>
                    <td>$no</td>
                    <td>".$invoice->purchase_no."</a></td>";
                    $soo = explode("/",$invoice->purchase_no);
                    echo"
                    <td>".$soo[1]."</a></td>
                    <td>".$invoice->nonota."</a></td>
                    <td>".$invoice->member_name."</td>
                    <td>".$invoice->kota."</td>
                    <td>".$invoice->expedisi."</td>
                    <td class='text-right'>".number_format($invoice->total,2,',','.')."</td>                    
                </tr>";
            }
    }

    public function filter_report_retur_revisi(){
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $tanggalFrom = $this->input->post('tanggalFrom');
        $tanggalTo = $this->input->post('tanggalTo');
        $cmbTransaksi = $this->input->post('cmbTransaksi');
        $no_rr = $this->input->post('no_rr');
        $_SESSION['rick_auto']['filter_perusahaan_rrr'] = $cmbPerusahaan;
        if($tanggalFrom == "1970-01-01" || $tanggalTo == "1970-01-01" || $tanggalTo == "1970-01-02"){
        }else{
            $_SESSION['rick_auto']['filter_start_date_rrr'] = $tanggalFrom;
            $_SESSION['rick_auto']['filter_end_date_rrr'] = $tanggalTo;
        }

        if($cmbPerusahaan != 0 || $cmbPerusahaan != '0' || $cmbPerusahaan != 'null' || $cmbPerusahaan != null){
        $_SESSION['rick_auto']['filter_perusahaan_rrr'] = $cmbPerusahaan;
        }

        if($cmbTransaksi != 0 || $cmbTransaksi != '0' || $cmbTransaksi != 'null' || $cmbTransaksi != null){
        $_SESSION['rick_auto']['filter_transaksi'] = $cmbTransaksi;
        }
        
        if($no_rr != '' || $no_rr != null){
        $_SESSION['rick_auto']['no_rr'] = $no_rr;
        }
        // $_SESSION['rick_auto']['filter_start_date_rrr'] = date("Y-m-d",strtotime("+0 day",strtotime($tanggalFrom)));
        // $_SESSION['rick_auto']['filter_end_date_rrr'] = date("Y-m-d",strtotime("+1 day", strtotime($tanggalTo)));
        $this->data['getData'] = $this->model_invoice->getRevisiReturs();
        $this->load->view('admin/retur_revisi/bg_report_ajax',$this->data);        
    }

    public function print_report_retur_rev(){
        $jenis = $this->uri->segment(4);
        $this->data['getDataAsc'] = $this->model_invoice->getRevisiRetursTanggal('ASC')->row();
        $this->data['getDataDesc'] = $this->model_invoice->getRevisiRetursTanggal('DESC')->row();
        $this->data['getData'] = $this->model_invoice->getRevisiReturs();
        if($jenis != "pdf"){
        $this->load->view('admin/retur_revisi/bg_print_report',$this->data);
        }else{
        $content = $this->load->view('admin/retur_revisi/bg_print_report',$this->data,TRUE);
        $this->template->print2pdf('Print_PDF',$content,'Report_Retur_Revisi');
        }
                 
    }

    public function report_tanda_terima(){
        //$this->data['getData'] = $this->model_invoice->getRevisiReturs();
        $this->data['getSales'] = $this->model_master->getSales();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getCity'] = $this->model_master->getCity();
        $this->template->rick_auto('invoice/bg_report_tanda_terima',$this->data);
    }



    public function filter_report_tanda_terima(){
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbSales = $this->input->post('cmbSales');
        $tanggalFrom = $this->input->post('tanggalFrom');
        $tanggalTo = $this->input->post('tanggalTo');
        $city = $this->input->post('city');
        $txtCity = $this->input->post('txtCity');
        $_SESSION['rick_auto']['perusahaanrtt'] = $cmbPerusahaan;
        //$_SESSION['rick_auto']['salesrtt'] = $this->db->escape_str($cmbSales);
        if($cmbSales == null || $cmbSales == "" || $cmbSales == "null"){
            //echo "sales kosong";
        }else{
        $_SESSION['rick_auto']['salesrtt'] = $this->db->escape_str($cmbSales);
        }
        if($city == null || $city == "" || $city == "null"){
            //echo "sales kosong";
        }else{
        $_SESSION['rick_auto']['cityrtt'] = $this->db->escape_str($city);
        }

        if($tanggalFrom == "1970-01-01" || $tanggalFrom == "" || $tanggalTo == "1970-01-01" || $tanggalTo == "" || $tanggalTo == "1970-01-02" || $tanggalTo == ""){
        }else{
            $_SESSION['rick_auto']['tanggalfromrtt'] = date("Y-m-d",strtotime("+0 day",strtotime($tanggalFrom)));
            $_SESSION['rick_auto']['tanggaltortt'] = date("Y-m-d",strtotime("+0 day",strtotime($tanggalTo)));
            $_SESSION['rick_auto']['tanggaltorttt'] = date("Y-m-d",strtotime("+1 day", strtotime($tanggalTo)));
           
        }
        
        //$_SESSION['rick_auto']['cityrtt'] = $city;
        //echo $_SESSION['rick_auto']['salesrtt'];
    }

    public function print_report_tanda_terima(){
        //$this->data['getKota'] = $this->model_invoice->getCityIn();
        $this->data['getMemberInvoice'] = $this->model_invoice->getInvoiceByAllMembersReportTT(1);
        //echo json_encode($this->data['getMemberInvoice']);
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaanByID($_SESSION['rick_auto']['perusahaanrtt'])->row();
        $jenis = $this->uri->segment(4);
        if($jenis != "pdf"){
            $this->load->view('admin/invoice/bg_print_report_tanda_terima',$this->data);
        }else{
            $content = $this->load->view('admin/invoice/bg_print_report_tanda_terima',$this->data,TRUE);
            $this->template->print2pdf('Print_PDF',$content,'Report_Tanda_Terima');
        }

    }

    public function report_barang(){
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->template->rick_auto('report_barang/bg_index',$this->data);
    }


    public function filter_barang_masuk(){
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudang = $this->input->post('cmbGudang');
        $tanggalFrom = $this->input->post('tanggalFrom');
        $tanggalTo = $this->input->post('tanggalTo');
        $cmbProduk = $this->input->post('cmbProduk');

        if($tanggalFrom == "1970-01-01" || $tanggalFrom == "" || $tanggalTo == "1970-01-01" || $tanggalTo == "" || $tanggalTo == "1970-01-02" || $tanggalTo == ""){
        }else{
            $_SESSION['rick_auto']['tanggalfromrrb'] = date("Y-m-d",strtotime("+0 day",strtotime($tanggalFrom)));
            $_SESSION['rick_auto']['tanggaltorrb'] = date("Y-m-d",strtotime("+0 day",strtotime($tanggalTo)));
        }

        $_SESSION['rick_auto']['perusahaanrb']  = $cmbPerusahaan;
        $dataPerusahaan = $this->model_master->getPerusahaanByID($_SESSION['rick_auto']['perusahaanrb'])->row();
        $_SESSION['rick_auto']['gudangrb']      = $cmbGudang;
        $_SESSION['rick_auto']['produkrb']      = $cmbProduk;
        $this->data['getPerusahaan'] = $dataPerusahaan->name;
        $this->data['getData'] = $this->model_invoice->getReportMasuk();
        $this->load->view('admin/report_barang/bg_report_masuk',$this->data);

    }

    public function export_barang_masuk(){

        // $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        // $cmbGudang = $this->input->post('cmbGudang');
        // $tanggalFrom = $this->input->post('tanggalFrom');
        // $tanggalTo = $this->input->post('tanggalTo');
        // $cmbProduk = $this->input->post('cmbProduk');

        // if($tanggalFrom == "1970-01-01" || $tanggalFrom == "" || $tanggalTo == "1970-01-01" || $tanggalTo == "" || $tanggalTo == "1970-01-02" || $tanggalTo == ""){
        // }else{
        //     $_SESSION['rick_auto']['tanggalfromrrb'] = date("Y-m-d",strtotime("+0 day",strtotime($tanggalFrom)));
        //     $_SESSION['rick_auto']['tanggaltorrb'] = date("Y-m-d",strtotime("+0 day",strtotime($tanggalTo)));
        //     $_SESSION['rick_auto']['tanggaltorrb'] = date("Y-m-d",strtotime("+1 day", strtotime($tanggalTo)));
        // }

        // $_SESSION['rick_auto']['perusahaanrb']  = $cmbPerusahaan;
        // $_SESSION['rick_auto']['gudangrb']      = $cmbGudang;
        // $_SESSION['rick_auto']['produkrb']      = $cmbProduk;
        $dataPerusahaan = $this->model_master->getPerusahaanByID($_SESSION['rick_auto']['perusahaanrb'])->row();
        $this->data['getPerusahaan'] = $dataPerusahaan->name;
        $jenis = $this->uri->segment(4);
        $this->data['getData'] = $this->model_invoice->getReportMasuk();
        if($jenis != "pdf"){
        $this->load->view('admin/report_barang/bg_report_masuk_export',$this->data);
        }else{
            $content = $this->load->view('admin/report_barang/bg_report_masuk_export',$this->data,TRUE);
            $this->template->print2pdf('Print_PDF',$content,'Report_Barang_Masuk');
        }
        
        

    }

    public function filter_barang_keluar(){
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudang = $this->input->post('cmbGudang');
        $tanggalFrom = $this->input->post('tanggalFrom');
        $tanggalTo = $this->input->post('tanggalTo');
        $cmbProduk = $this->input->post('cmbProduk');

        if($tanggalFrom == "1970-01-01" || $tanggalFrom == "" || $tanggalTo == "1970-01-01" || $tanggalTo == "" || $tanggalTo == "1970-01-02" || $tanggalTo == ""){
        }else{
            $_SESSION['rick_auto']['tanggalfromrrk'] = date("Y-m-d",strtotime("+0 day",strtotime($tanggalFrom)));
            $_SESSION['rick_auto']['tanggaltorrk'] = date("Y-m-d",strtotime("+0 day",strtotime($tanggalTo)));
        }

        $_SESSION['rick_auto']['perusahaanrk']  = $cmbPerusahaan;
        $_SESSION['rick_auto']['gudangrk']      = $cmbGudang;
        $dataProduk = $this->model_produk->getProductsById($cmbProduk)->row();
        $dataPerusahaan = $this->model_master->getPerusahaanByID($_SESSION['rick_auto']['perusahaanrk'])->row();
        $this->data['getPerusahaan'] = $dataPerusahaan->name;
        //$_SESSION['rick_auto']['produkrk']      = $dataProduk->product_code;
        $_SESSION['rick_auto']['produkrk']      = $cmbProduk;
        $this->data['getData'] = $this->model_invoice->getReportKeluar();
        $this->load->view('admin/report_barang/bg_report_keluar',$this->data);

    }

    public function export_barang_keluar(){
        // $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        // $cmbGudang = $this->input->post('cmbGudang');
        // $tanggalFrom = $this->input->post('tanggalFrom');
        // $tanggalTo = $this->input->post('tanggalTo');
        // $cmbProduk = $this->input->post('cmbProduk');

        // if($tanggalFrom == "1970-01-01" || $tanggalFrom == "" || $tanggalTo == "1970-01-01" || $tanggalTo == "" || $tanggalTo == "1970-01-02" || $tanggalTo == ""){
        // }else{
        //     $_SESSION['rick_auto']['tanggalfromrrk'] = date("Y-m-d",strtotime("+0 day",strtotime($tanggalFrom)));
        //     $_SESSION['rick_auto']['tanggaltorrk'] = date("Y-m-d",strtotime("+0 day",strtotime($tanggalTo)));
        //     $_SESSION['rick_auto']['tanggaltorrk'] = date("Y-m-d",strtotime("+1 day", strtotime($tanggalTo)));
        // }

        // $_SESSION['rick_auto']['perusahaanrk']  = $cmbPerusahaan;
        // $_SESSION['rick_auto']['gudangrk']      = $cmbGudang;
        // $dataProduk = $this->model_produk->getProductsById($cmbProduk)->row();
        // $_SESSION['rick_auto']['produkrk']      = $dataProduk->product_code;

        $jenis = $this->uri->segment(4);
        $this->data['getData'] = $this->model_invoice->getReportKeluar();
        $dataPerusahaan = $this->model_master->getPerusahaanByID($_SESSION['rick_auto']['perusahaanrk'])->row();
        $this->data['getPerusahaan'] = $dataPerusahaan->name;
        if($jenis != "pdf"){
        $this->load->view('admin/report_barang/bg_report_keluar_export',$this->data);
        }else{
            $content = $this->load->view('admin/report_barang/bg_report_keluar_export',$this->data,TRUE);
            $this->template->print2pdf('Print_PDF',$content,'Report_Barang_Keluar');
        }

    }

    public function report_piutang(){
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getMember'] = $this->model_master->getAllMembers();
        $this->data['getPayments'] = $this->model_master->getPayments();
        
        $this->template->rick_auto('report_barang/bg_report_piutang',$this->data);
    }



    public function filter_piutang(){
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbMember = $this->input->post('cmbMember');
        $tanggalFrom = $this->input->post('tanggalFrom');
        $cmbPayment = $this->input->post('cmbPayment');
        
        //echo $tanggalFrom;
        if($tanggalFrom == "1970-01-01" || $tanggalFrom == ""){
        }else{
            $_SESSION['rick_auto']['tanggalfromrrp'] = date("Y-m-d",strtotime("+0 day",strtotime($tanggalFrom)));
        }
        $getPerusahaan = $this->model_master->getPerusahaanByID($cmbPerusahaan)->row();
        $_SESSION['rick_auto']['perusahaannamerp']  = $getPerusahaan->name;
        $_SESSION['rick_auto']['perusahaanrp']  = $cmbPerusahaan;
        $_SESSION['rick_auto']['memberrp']      = $cmbMember;
        $_SESSION['rick_auto']['tanggalfromrrp'] = $tanggalFrom;
        $_SESSION['rick_auto']['paymentrrp']      = $cmbPayment;

        $this->data['getData'] = $this->model_invoice->getInvoicePembayaranFilterPiutang();
        $this->data['getDataLog'] = $this->model_invoice->getInvoiceLogPembayaranFilterPiutang();
        $this->load->view('admin/report_barang/bg_report_piutang_ajax',$this->data);

    }

    public function export_piutang(){
        //echo $_SESSION['rick_auto']['tanggalfromrrp'];
        // $_SESSION['rick_auto']['tanggalfromrrp'];
        // $_SESSION['rick_auto']['perusahaannamerp'];
        // $_SESSION['rick_auto']['perusahaanrp'];
        // $_SESSION['rick_auto']['memberrp'];
        //$this->data['getData'] = $this->model_invoice->getInvoicePembayaranFilter();
        $jenis = $this->uri->segment(4);
        $this->data['getData'] = $this->model_invoice->getInvoicePembayaranFilterPiutang();
        $this->data['tanggal'] = $_SESSION['rick_auto']['tanggalfromrrp'];
        if($jenis != "pdf"){
        $this->load->view('admin/report_barang/bg_report_piutang_export',$this->data);
        }else{
            $content = $this->load->view('admin/report_barang/bg_report_piutang_export_pdf',$this->data,TRUE);
            $this->template->print2pdf('Print_PDF',$content,'Report_Piutang');
        }        
    }



    public function scan_barcode_order(){
        $id = $this->input->post('id');
        $jenis = $this->input->post('jenis');
        $kode_produk = $this->input->post('kode_produk');
        //$getProdukByCodes = $this->model_produk->getProductByBarcode($kode_produk);
        $getProdukByCodess = $this->model_produk->getProdukByBarcode($kode_produk);
        if($getProdukByCodess->num_rows() > 0){
            //$getProdukByCode = $this->model_produk->getProductByBarcode($kode_produk)->row();
            //$getProdukBarcodeByCode = $this->model_produk->getProdukByBarcode($kode_produk)->row();
            $getProdukById = $this->model_produk->getProductById($getProdukByCodess->row()->product_id)->row();
            $getProdukByCode = $this->model_produk->getProductByBarcode($getProdukById->product_code)->row();
            $getProdukByCodes= $this->model_invoice->getDetailInvoiceByInvoiceProduk($id,$getProdukById->product_code);

            if($getProdukByCodes->num_rows() > 0){
                echo json_encode(array('message'=>'sukses','qtyR'=>$getProdukByCodess->row()->isi, 'idDetail'=>$getProdukByCodes->row()->id));
                //echo $getProdukByCodes->row()->id;
                //$this->data_ajax_order();
            }else{
                echo json_encode(array('message'=>'gagal','idDetail'=>0));
            }
        }else{
            echo json_encode(array('message'=>'not_found','idDetail'=>0));
        }
        //echo $getProdukByCodes->row()->id;
    }


    public function penjualan(){
        $jenis = $this->uri->segment(4);
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getKategori'] = $this->model_master->getKategori();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->template->rick_auto('report_barang/bg_report_penjualan',$this->data);
    }

    public function report_stok(){
        $jenis = $this->uri->segment(4);
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->data['getKategori'] = $this->model_master->getKategori();
        $this->data['getGudang'] = $this->model_master->getGudang();
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaan();
        $this->template->rick_auto('report_barang/bg_report_stok',$this->data);
    }

    public function filter_report_stok(){
        $txtProduk = $this->input->post('txtProduk');
        $cmbKategori = $this->input->post('cmbKategori');
        $tanggalFrom = $this->input->post('tanggalFrom');
        $tanggalTo = $this->input->post('tanggalTo');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudang = $this->input->post('cmbGudang');
        $_SESSION['rick_auto']['filter_stok_produk'] = $txtProduk;
        $_SESSION['rick_auto']['filter_stok_kategori'] = $cmbKategori;
        $_SESSION['rick_auto']['filter_stok_tanggalfrom'] = $tanggalFrom;
        $_SESSION['rick_auto']['filter_stok_tanggalto'] = $tanggalTo;
        $_SESSION['rick_auto']['filter_stok_tanggaltoo'] = date("Y-m-d",strtotime("+1 day", strtotime($tanggalTo)));
        $_SESSION['rick_auto']['filter_stok_perusahaan'] = $cmbPerusahaan;
        $_SESSION['rick_auto']['filter_stok_gudang'] = $cmbGudang;
        //echo "Produk ".$txtProduk;
        // echo $jenis;
        $this->data['getDataInv'] = $this->model_invoice->getDetailInvoiceFilterStok();
        $this->data['getDataGr'] = $this->model_invoice->getProdukBeliDetailFilterStok();
        $this->data['getDataBmbl'] = $this->model_invoice->getReportBmBlFilterStok('mutasi,Adjusment,Retur Barang Masuk,Retur Barang Keluar');
        $this->data['getDataInvTotal'] = $this->model_invoice->getSumDetailInvoiceFilterStok()->row();
        $this->data['getDataGrTotal'] = $this->model_invoice->getSumProdukBeliDetailFilterStok()->row();
        $this->data['getDataBmBl'] = $this->model_invoice->getSumReportBmBlFilterStok('mutasi,Adjusment,Retur Barang Masuk,Retur Barang Keluar')->row();
        
        $this->load->view('admin/report_barang/bg_report_stok_ajax',$this->data);   
    }

    public function filter_report_penjualan(){
        $txtProduk = $this->input->post('txtProduk');
        $cmbKategori = $this->input->post('cmbKategori');
        $tanggalFrom = $this->input->post('tanggalFrom');
        $tanggalTo = $this->input->post('tanggalTo');
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $cmbGudang = $this->input->post('cmbGudang');
        $_SESSION['rick_auto']['filter_penjualan_produk'] = $txtProduk;
        $_SESSION['rick_auto']['filter_penjualan_kategori'] = $cmbKategori;
        $_SESSION['rick_auto']['filter_penjualan_tanggalfrom'] = $tanggalFrom;
        $_SESSION['rick_auto']['filter_penjualan_tanggalto'] = $tanggalTo;
        $_SESSION['rick_auto']['filter_penjualan_tanggaltoo'] = date("Y-m-d",strtotime("+1 day", strtotime($tanggalTo)));
        $_SESSION['rick_auto']['filter_penjualan_perusahaan'] = $cmbPerusahaan;
        $_SESSION['rick_auto']['filter_penjualan_gudang'] = $cmbGudang;
        //echo "Produk ".$txtProduk;
        // echo $jenis;
        $this->data['getData'] = $this->model_invoice->getDetailInvoiceFilter();
        $this->load->view('admin/report_barang/bg_penjualan_ajax',$this->data);   
    }

    public function print_penjualan(){
        $jenis = $this->uri->segment(4);
        $_SESSION['rick_auto']['filter_penjualan_produk'];
        $_SESSION['rick_auto']['filter_penjualan_kategori'];
        $_SESSION['rick_auto']['filter_penjualan_tanggalfrom'];
        $_SESSION['rick_auto']['filter_penjualan_tanggalto'];
        $_SESSION['rick_auto']['filter_penjualan_tanggaltoo'];
        $_SESSION['rick_auto']['filter_penjualan_perusahaan'];
        $_SESSION['rick_auto']['filter_penjualan_gudang'];
        $this->data['getData'] = $this->model_invoice->getDetailInvoiceFilter();
        if($jenis != "pdf"){
        $this->load->view('admin/report_barang/bg_penjualan_export',$this->data);
        }else{
        $content = $this->load->view('admin/report_barang/bg_penjualan_export',$this->data,TRUE);
        $this->template->print2pdf('Print_PDF',$content,'Report_BO');
        }
        
    }






}?>