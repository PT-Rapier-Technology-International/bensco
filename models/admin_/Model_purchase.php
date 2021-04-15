<?php
class Model_purchase extends CI_Model {

	public function getPurchase_(){
		if($_SESSION['rick_auto']['flag_user'] == 1){
			$this->db->select('tp.*, m.name as nama_member, s.name as nama_sales, x.name as nama_expedisi');
			$this->db->join('member m','m.id = tp.member_id');
			$this->db->join('sales s','s.id = tp.sales_id');
			$this->db->join('expedisi x','x.id = tp.expedisi');
			$this->db->order_by('tp.dateorder','DESC');
			return $this->db->get('transaction_purchase tp');
		}else{
			$this->db->select('tp.*, m.name as nama_member, s.name as nama_sales, x.name as nama_expedisi');
			$this->db->where('tp.status !=',3);
			$this->db->join('member m','m.id = tp.member_id');
			$this->db->join('sales s','s.id = tp.sales_id');
			$this->db->join('expedisi x','x.id = tp.expedisi');
			$this->db->order_by('tp.dateorder','DESC');
			return $this->db->get('transaction_purchase tp');
		}
		
	}

	public function getPurchase(){
			$this->db->select('tp.*, m.name as nama_member, s.name as nama_sales, x.name as nama_expedisi, m.city as kota_member');
			$this->db->join('member m','m.id = tp.member_id');
			$this->db->join('sales s','s.id = tp.sales_id');
			$this->db->join('expedisi x','x.id = tp.expedisi');
			$this->db->order_by('tp.dateorder','DESC');
			return $this->db->get('transaction_purchase tp');
		
	}

	public function getMonthlyPurchase(){
			$this->db->select('tp.*, m.name as nama_member, m.city as kota_member, s.name as nama_sales');
			if(isset($_SESSION['rick_auto']['perusahaan']) && $_SESSION['rick_auto']['bulan']){
				$this->db->where('tp.perusahaan_id',$_SESSION['rick_auto']['perusahaan'])->where('date(tp.dateorder)',$_SESSION['rick_auto']['bulan']);
			}
			$this->db->join('member m','m.id = tp.member_id');
			$this->db->join('sales s','s.id = tp.sales_id');
			return $this->db->get('transaction_purchase tp');		
	}

	public function getReqPurchase(){
			$this->db->select('tp.*, m.name as nama_member, m.city as kota_member, s.name as nama_sales, e.name as nama_expedisi');
			$this->db->join('member m','m.id = tp.member_id');
			$this->db->join('sales s','s.id = tp.sales_id');
			$this->db->join('expedisi e','e.id = tp.expedisi');
			$this->db->where('tp.status !=',2);
			$this->db->order_by('tp.dateorder','DESC');
			return $this->db->get('transaction_purchase_temporary tp');
		
	}

	public function getReqPurchaseCancelled(){
			$this->db->select('tp.*, m.name as nama_member, s.name as nama_sales, e.name as nama_expedisi');
			$this->db->join('member m','m.id = tp.member_id');
			$this->db->join('sales s','s.id = tp.sales_id');
			$this->db->join('expedisi e','e.id = tp.expedisi');
			$this->db->where('tp.status',2);
			$this->db->order_by('tp.dateorder','DESC');
			return $this->db->get('transaction_purchase_temporary tp');
		
	}

	public function getPurchaseByID($id){
		$this->db->select('tp.*, m.name as nama_member, m.city as kota_member, m.email as email_member, m.address as alamat_member, m.phone as phone_member, m.ktp as ktp, s.name as nama_sales');
		$this->db->join('member m','m.id = tp.member_id');
		$this->db->join('sales s','s.id = tp.sales_id');
		$this->db->where('tp.id',$id);
		return $this->db->get('transaction_purchase tp');
	}

	public function getPurchaseTempByID($id){
		$this->db->select('tp.*, m.name as nama_member, m.email as email_member, m.address as alamat_member, m.phone as phone_member, m.ktp as ktp, s.name as nama_sales');
		$this->db->join('member m','m.id = tp.member_id');
		$this->db->join('sales s','s.id = tp.sales_id');
		$this->db->where('tp.id',$id);
		return $this->db->get('transaction_purchase_temporary tp');
	}

	public function getPurchaseDetailByPurchase($id){
		$this->db->select('tpd.*, p.product_name as nama_produk, p.product_code as kode_produk, p.product_desc as deskripsi_produk, p.normal_price as harga_satuan, s.name as nama_satuan, p.product_cover as gambar_cover');
		$this->db->join('product p','p.id = tpd.product_id');
		$this->db->join('satuan s','s.id = p.satuan_id');
		$this->db->where('tpd.transaction_purchase_id',$id);
		return $this->db->get('transaction_purchase_detail tpd');
	}

	public function getTotalPembayarannyaByPurchase($id){
		$this->db->select('sum(ttl_price) as total_harga');
		$this->db->where('transaction_purchase_id',$id);
		return $this->db->get('transaction_purchase_detail');
	}

	public function getPurchaseByStatusRead($param){
		$this->db->select('*');
		$this->db->where('read',$param);
		return $this->db->get('transaction_purchase');
	}

	public function getCekValidPurchase($purchase){
		$this->db->where('qty_kirim !=',' ');
		$this->db->where('colly !=',' ');
		$this->db->where('weight !=',' ');
		$this->db->where('transaction_purchase_id',$purchase);
		return $this->db->get('transaction_purchase_detail');
	}

	public function getPurchaseDetailByID($id){
		$this->db->where('qty_kirim !=',' ');
		$this->db->where('colly !=',' ');
		$this->db->where('weight !=',' ');
		$this->db->where('id',$id);;
		return $this->db->get('transaction_purchase_detail');
	}

	public function getPurchaseDetailByPurchaseID($id){
		$this->db->select('*');
		$this->db->where('transaction_purchase_id',$id);;
		return $this->db->get('transaction_purchase_detail');
	}

	public function getReqPurchaseByID($id){
		$this->db->select('tp.*, m.name as nama_member, m.email as email_member, m.address as alamat_member, m.phone as phone_member, m.ktp as ktp, s.name as nama_sales');
		$this->db->join('member m','m.id = tp.member_id');
		$this->db->join('sales s','s.id = tp.sales_id');
		$this->db->where('tp.id',$id);
		return $this->db->get('transaction_purchase_temporary tp');
	}

	public function getReqPurchaseDetailByPurchase($id){
		$this->db->select('tpd.*, p.product_name as nama_produk, p.id as id_produk, p.product_code as kode_produk, p.product_desc as deskripsi_produk, s.name as nama_satuan, p.product_cover as gambar_cover');
		$this->db->join('product p','p.id = tpd.product_id');
		$this->db->join('satuan s','s.id = p.satuan_id');
		$this->db->where('tpd.transaction_purchase_temporary_id',$id);
		return $this->db->get('transaction_purchase_temporary_detail tpd');
	}

	public function getTemporaryProcessGroup(){
		$this->db->select('*,sum(price) as total_unit, sum(ttl_price) as total_semua');
		$this->db->group_by('perusahaan_id');
		return $this->db->get('transaction_purchase_temporary_process');
	}

	public function getTemporaryProcess(){
		$this->db->select('*');
		return $this->db->get('transaction_purchase_temporary_process');
	}

	public function getTemporaryProcessByPerusahaan($perusahaan){
		$this->db->select('*');
		$this->db->where('perusahaan_id',$perusahaan);
		return $this->db->get('transaction_purchase_temporary_process');
	}

	public function getPurchaseByIds($id,$perusahaan_id){
		$this->db->select('*');
		$this->db->where('id',$id);
		return $this->db->get('transaction_purchase_temporary_process');
	}

	public function getPurchaseByPerusahaan($perusahaan){
		$this->db->select('*');
		$this->db->like('nonota',$perusahaan);
		$this->db->order_by('id','desc');
		return $this->db->get('transaction_purchase');
	}

	public function getPurchaseByIdDesc(){
		$this->db->select('*');
		$this->db->order_by('id','desc');
		return $this->db->get('transaction_purchase');
	}

	public function getPurchaseTempByIdDesc(){
		$this->db->select('*');
		$this->db->order_by('id','desc');
		return $this->db->get('transaction_purchase_temporary');
	}

	public function getInvoiceByNoNota($no){
		$this->db->select('*');
		$this->db->where('nonota',$no);
		return $this->db->get('invoice');
	}


	public function getInvoiceByNoPO($no){
		$this->db->select('*');
		$this->db->where('purchase_no',$no);
		return $this->db->get('invoice');
	}

	public function getCekPurchaseOrderValidSave($perusahaan,$member,$sales,$expedisi,$subtotal,$total,$note){
		$this->db->select('*');
		$this->db->where('');
		return $this->db->get('transaction_purchase');
	}


	// public function getReqPurchase(){
	// 	$this->db->select('*');
	// 	return $this->db->get('transaction_purchase_temporary');
	// }
}
?>