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
        
        $id = base64_decode($this->uri->segment(4));

        $this->data['getInvoice'] = $this->model_invoice->getInvoiceById($id)->row();
        $cekInvoice = $this->model_invoice->getInvoiceById($id)->row();
        if($cekInvoice->perusahaan_id == 4){
            $this->load->view('admin/invoice/bg_print_ertraco',$this->data);
        }elseif($cekInvoice->perusahaan_id == 3){
            $this->load->view('admin/invoice/bg_print_berkat',$this->data);
        }elseif($cekInvoice->perusahaan_id == 1){
            $this->load->view('admin/invoice/bg_print_chandra',$this->data);
        }else{
            $this->load->view('admin/invoice/bg_print',$this->data);
        }
    }

    public function print_amplop(){
        
        $id = base64_decode($this->uri->segment(4));

        $this->data['getInvoice'] = $this->model_invoice->getInvoiceById($id)->row();
        $this->load->view('admin/invoice/bg_print_amplop',$this->data);
    }

    public function print_packing_list(){
        
        $id = base64_decode($this->uri->segment(4));

        $this->data['getInvoice'] = $this->model_invoice->getInvoiceById($id)->row();
        $this->load->view('admin/invoice/bg_packing_list',$this->data);
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
        $this->load->view('admin/invoice/bg_print_tanda_terima',$this->data);
    }

    public function print_surat_jalan(){
        $perusahaan = $this->uri->segment(5);
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
        $this->load->view('admin/invoice/bg_print_surat_jalan',$this->data);
    }

    public function ubah_status(){
        $id = $this->input->post('id');
        $i = $this->input->post('i');

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
            $insert = $this->db->set('no_tanda_terima',$no_tanda_terima)->set('member_id',$member_id)->set('payment_id',$cmbPembayaran)->set('payment_date',date("Y-m-d",strtotime("+0 day", strtotime($tanggal))))->set('liquid_date',date("Y-m-d",strtotime("+0 day", strtotime($tanggal_cair))))->set('name',$nama_giro_cek)->set('number',$nomor_giro_cek)->set('sudah_dibayar',str_replace(".","",$rupiah_input))->set('sisa',$total)->set('total_pembayaran',$sisa_pembayaran)->set('cicilan_ke',$hitung)->insert('invoice_payment');

            if($total == 0 || $total <= 5000){
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
                }

                $update_status_payment_invoice = $this->db->set('flag',1)->where('member_id',$member_id)->where('no_tanda_terima',$no_tanda_terima)->update('invoice_payment');
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
                    $updateInvoice = $this->db->set('qty_kirim',$txtQty)->set('price',$txtTotalSatuan)->set('ttl_price',$txtTotal)->where('id',$detailInvoice->id)->update('invoice_detail');
                }
            }

            $insertPayment = $this->db->set('no_tanda_terima',$txtnoTT)->set('member_id',$txtMemberId)->set('payment_id',$txtJenis)->set('payment_date',$txtPaymentDate)->set('cicilan_ke',$hitung)->insert('invoice_payment');
            $ppn = $txtTotalPembayaran * 10 / 100;
            $grandTotal = $txtTotalPembayaran+ $ppn;
            $updateInvoice = $this->db->set('sub_total',$txtTotalPembayaran)->set('total_before_ppn',$txtTotalPembayaran)->set('total',$grandTotal)->where('id',$txtidInvoice)->update('invoice');
        }

        echo "1";
    }


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

            if($total == 0 || $total <= 5000){
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
        ";
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


    public function process_tanda_terima(){
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
                             <select id='expedisi_".$data->id."' name='expedisi_".$data->id."' class='form-control'>
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
            </div>";}

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
        $resi_no = $this->input->post('resi_no');
        $expedisi = $this->input->post('expedisi');
        $delivery_date = $this->input->post('delivery_date');
        $delivery_dates = date("Y-m-d",strtotime("+0 day", strtotime($delivery_date)));
        $update = $this->db->set('expedisi',$expedisi)->set('resi_no',$resi_no)->set('delivery_date',$delivery_dates)->where('id',$id)->update('invoice');

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
            echo json_encode(array('total_pembayaran'=>number_format($total_pembayaran,2,',','.'),'grandTotal'=>number_format($grandTotal,2,',','.')));
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
        $jenis = $this->uri->segment(4);
        $this->data['getInvoice'] = $this->model_invoice->getInvoiceByAllMemberssRekapInvoice();
        if($jenis != "pdf"){
        $this->load->view('admin/invoice/bg_print_report_rekap_invoice',$this->data);
        }else{
            $content = $this->load->view('admin/invoice/bg_print_report_rekap_invoice',$this->data,TRUE);
            $this->template->print2pdf('Print_PDF',$content);
        }

    }

    public function filter_report_rekap_invoice(){
        $cmbPerusahaan = $this->input->post('cmbPerusahaan');
        $tanggalFrom = $this->input->post('tanggalFrom');
        $tanggalTo = $this->input->post('tanggalTo');

        $_SESSION['rick_auto']['filter_perusahaan_rri'] = $cmbPerusahaan;
        $_SESSION['rick_auto']['filter_tanggal_rri'] = date("Y-m-d",strtotime("+0 day",strtotime($tanggalFrom)));
        $_SESSION['rick_auto']['filter_tanggal_to_rri'] = date("Y-m-d",strtotime("+0 day",strtotime($tanggalTo)));
        $_SESSION['rick_auto']['filter_tanggal_to_rrii'] = date("Y-m-d",strtotime("+1 day", strtotime($tanggalTo)));
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
        $this->data['getData'] = $this->model_invoice->getRevisiReturs();
        if($jenis != "pdf"){
        $this->load->view('admin/retur_revisi/bg_print_report',$this->data);
        }else{
        $content = $this->load->view('admin/retur_revisi/bg_print_report',$this->data,TRUE);
        $this->template->print2pdf('Print_PDF',$content);
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
        $this->data['getPerusahaan'] = $this->model_master->getPerusahaanByID($_SESSION['rick_auto']['perusahaanrtt'])->row();
        $jenis = $this->uri->segment(4);
        if($jenis != "pdf"){
            $this->load->view('admin/invoice/bg_print_report_tanda_terima',$this->data);
        }else{
            $content = $this->load->view('admin/invoice/bg_print_report_tanda_terima',$this->data,TRUE);
            $this->template->print2pdf('Print_PDF',$content);
        }

    }





}?>