<?php
class Model_master extends CI_Model {

	public function getUsers(){
		$this->db->select('*');
		return $this->db->get('users');
	}

	public function getUsersById($id){
		$this->db->select('*, fullname as name, no_hp as phone');
		$this->db->where('id',$id);
		return $this->db->get('users');
	}

	public function getAllMembersDesc(){
		$this->db->select('*');
		$this->db->order_by('id','desc');
		return $this->db->get('member');
	}


	public function getAllMembers(){
		$this->db->select('m.*, c.name as nama_kota');
		$this->db->join('city c','c.id = m.city_id');
		return $this->db->get('member m');
	}

	public function getMemberByID($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		return $this->db->get('member');
	}

	public function getMembers(){
		$this->db->select('m.*,tp.operation as rumus, tp.name as angka');
		$this->db->join('type_price tp','tp.id = m.operation_price');
		return $this->db->get('member m');
	}

	public function getMembersById($id){
		$this->db->select('m.*,tp.operation as rumus, tp.name as angka');
		$this->db->join('type_price tp','tp.id = m.operation_price');
		$this->db->where('m.id',$id);
		return $this->db->get('member m');
	}

	public function getKategori(){
		$this->db->select('*');
		return $this->db->get('category_product');
	}

	public function getKategoriById($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		return $this->db->get('category_product');
	}

	public function getSatuan(){
		$this->db->select('*');
		return $this->db->get('satuan');
	}

	public function getSatuanById($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		return $this->db->get('satuan'); 
	}

	public function getPerusahaan(){
		$this->db->select('*');
		return $this->db->get('perusahaan');
	}

	public function getPerusahaanGroupBy($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		$this->db->group_by('id');
		return $this->db->get('perusahaan');
	}

	public function getCity(){
		$this->db->select('*');
		return $this->db->get('city');
	}

	public function getCitybyName($name){
		$this->db->select('*');
		$this->db->where('name',$name);
		return $this->db->get('city');
	}

	public function getCitybyId($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		return $this->db->get('city');
	}

	public function getMemberBySingkatanDesc($singkatan){
		$this->db->select('*');
		$this->db->like('id',$singkatan);
		$this->db->order_by('create_date','desc');
		return $this->db->get('member');
	}

	public function getMemberBySingkatan($singkatan){
		$this->db->select('*');
		$this->db->like('id',$singkatan);
		return $this->db->get('member');
	}

	public function getPerusahaanByID($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		return $this->db->get('perusahaan');
	}

	public function getPerusahaanByIDGroup($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		$this->db->group_by('id');
		return $this->db->get('perusahaan');
	}

	public function getHarga(){
		$this->db->select('*');
		return $this->db->get('type_price');
	}

	public function getHargaById($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		return $this->db->get('type_price');
	}

	public function getPayments(){
		$this->db->select('*');
		return $this->db->get('payment');
	}

	public function getExpedisi(){
		$this->db->select('*');
		return $this->db->get('expedisi');
	}

	public function getExpedisiById($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		return $this->db->get('expedisi');
	}

	public function getSales(){
		$this->db->select('*');
		return $this->db->get('sales');
	}

	public function getSalesDesc(){
		$this->db->select('*');
		$this->db->order_by('id','desc');
		return $this->db->get('sales');
	}

	public function getSaless(){
		$this->db->select('*,phone as no_hp');
		return $this->db->get('sales');
	}

	public function getSalesByName($name){
		$this->db->select('*');
		$this->db->where('name',$name);
		return $this->db->get('sales');
	}

	public function getSalesById($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		return $this->db->get('sales');
	}

	public function getMemberSalesByMember($member){
		$this->db->select('*');
		$this->db->where('member_id',$member);
		return $this->db->get('member_sales');
	}

	public function getMemberSalesByMemberSales($member,$sales){
		$this->db->select('*');
		$this->db->where('member_id',$member);
		$this->db->where('sales_id',$sales);
		return $this->db->get('member_sales');
	}

	public function getMemberSalesByMemberJoin($member){
		$this->db->select('ms.*,s.name as nama_sales,s.id as id_sales');
		$this->db->join('sales s','ms.sales_id = s.id');
		$this->db->where('ms.member_id',$member);
		$this->db->where('ms.active',1);
		return $this->db->get('member_sales ms');
	}


	public function getPerusahaanGudang($perusahaan){
		$this->db->select('*');
		$this->db->where('perusahaan_id',$perusahaan);
		return $this->db->get('perusahaan_gudang');
	}

	public function getGudang(){
		$this->db->select('*');
		return $this->db->get('gudang');
	}

	public function getGudangByNotId($id){
		$this->db->select('*');
		$this->db->where('id !=',$id);
		return $this->db->get('gudang');
	}

	public function getGudangById($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		return $this->db->get('gudang');
	}

	public function getPerusahaanGudangByGudang($perusahaan,$gudang){
		$this->db->select('*');
		$this->db->where('perusahaan_id',$perusahaan);
		$this->db->where('gudang_id',$gudang);
		return $this->db->get('perusahaan_gudang');
	}

	public function getDistinctPerusahaan(){
		$this->db->select('DISTINCT(perusahaan_id) as perusahaan_id');
		return $this->db->get('perusahaan_gudang');
	}

	public function getGudangbyPerusahaan($perusahaan){
		$this->db->select('pg.*,g.name as nama_gudang,g.id as id_gudang');
		$this->db->where('pg.active',1);
		$this->db->where('pg.perusahaan_id',$perusahaan);
		$this->db->join('gudang g','pg.gudang_id = g.id');
		return $this->db->get('perusahaan_gudang pg');
	}

	public function getGudangPerusahaan(){
		$this->db->select('pg.*,g.name as nama_gudang');
		$this->db->where('pg.active',1);
		$this->db->join('gudang g','pg.gudang_id = g.id');
		return $this->db->get('perusahaan_gudang pg');
	}

	public function getPerusahaanGudangById($id){
		$this->db->select('pg.*,g.name as nama_gudang, p.name as nama_perusahaan');
		$this->db->where('pg.id',$id);
		$this->db->join('gudang g','pg.gudang_id = g.id');
		$this->db->join('perusahaan p','pg.perusahaan_id = p.id');
		return $this->db->get('perusahaan_gudang pg');
	}

	public function getLogStokByProductPerusahaanGudang($perusahaan_gudang){
		$this->db->select('ls.*,g.name as nama_gudang_from, p.name as nama_perusahaan_from, gg.name as nama_gudang_to, pp.name as nama_perusahaan_to');
		$this->db->where('ls.product_perusahaan_gudang_id',$perusahaan_gudang);
		$this->db->join('gudang g','ls.from_gudang_id = g.id');
		$this->db->join('perusahaan p','ls.from_perusahaan_id = p.id');
		$this->db->join('gudang gg','ls.to_gudang_id = gg.id');
		$this->db->join('perusahaan pp','ls.to_perusahaan_id = pp.id');
		$this->db->order_by('ls.id','DESC');
		return $this->db->get('log_stok ls');
	}

	public function getProductPerusahaanGudangByPerusahaanGudang($id){
		$this->db->select('ppg.*,p.product_name as nama_produk, p.product_code as kode_produk');
		$this->db->join('product p','ppg.product_id = p.id');
		$this->db->where('ppg.perusahaan_gudang_id',$id);
		return $this->db->get('product_perusahaan_gudang ppg');
	}

	public function getProductPerusahaanGudangById($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		return $this->db->get('product_perusahaan_gudang');
	}


	public function getStokPerusahaanGudangByProduk($product_id,$perusahaan_gudang_id){
		$this->db->select('*');
		$this->db->where('product_id',$product_id);
		$this->db->where('perusahaan_gudang_id',$perusahaan_gudang_id);
		return $this->db->get('product_perusahaan_gudang');
	}

	//SELECT `ppg`.*, `g`.`name` as `nama_gudang`, `g`.`id` as `id_gudang`, sum(`ppg`.`stok`) as `stok_gudang` FROM `product_perusahaan_gudang` `ppg` JOIN `perusahaan_gudang` `pg` ON `ppg`.`perusahaan_gudang_id` = `pg`.`id` JOIN `gudang` `g` ON `pg`.`gudang_id` = `g`.`id` WHERE `ppg`.`product_id` = '5803' AND `pg`.`perusahaan_id` = '1' GROUP BY `ppg`.`product_id`, `ppg`.`perusahaan_gudang_id` HAVING sum(`ppg`.`stok`) > 2

	public function getCekStokGudangbyProductPerusahaan($id_produk,$id_perusahaan,$stok){
		$this->db->select('ppg.*,g.name as nama_gudang,g.id as id_gudang, ppg.stok as stok_gudang, sum(ppg.stok) as stok_gudang');
		$this->db->join('perusahaan_gudang pg','ppg.perusahaan_gudang_id = pg.id');
		$this->db->join('gudang g','pg.gudang_id = g.id');
		$this->db->where('ppg.product_id',$id_produk);
		$this->db->where('pg.perusahaan_id',$id_perusahaan);
		$this->db->group_by('ppg.perusahaan_gudang_id');
		$this->db->group_by('ppg.product_id');
		$this->db->having('sum(ppg.stok) >=',$stok);
		return $this->db->get('product_perusahaan_gudang ppg');
	}

	public function getGudangbyProductPerusahaan($id_produk,$id_perusahaan,$id_gudang){
		$this->db->select('ppg.*,g.name as nama_gudang,g.id as id_gudang');
		$this->db->join('perusahaan_gudang pg','ppg.perusahaan_gudang_id = pg.id');
		$this->db->join('gudang g','pg.gudang_id = g.id');
		$this->db->where('ppg.product_id',$id_produk);
		$this->db->where('pg.perusahaan_id',$id_perusahaan);
		$this->db->where('pg.gudang_id',$id_gudang);
		return $this->db->get('product_perusahaan_gudang ppg');
	}

	public function getStokProduct($id_produk){
		$this->db->select('ppg.*,g.name as nama_gudang,g.id as id_gudang, p.name as nama_perusahaan, p.id as id_perusahaan, pg.perusahaan_id as id_perusahaan_pg');
		$this->db->join('perusahaan_gudang pg','ppg.perusahaan_gudang_id = pg.id');
		$this->db->join('perusahaan p','pg.perusahaan_id = p.id');
		$this->db->join('gudang g','pg.gudang_id = g.id');
		$this->db->where('ppg.product_id',$id_produk);
		$this->db->where('pg.active',1);
		$this->db->group_by('pg.perusahaan_id');
		return $this->db->get('product_perusahaan_gudang ppg');
	}

	public function getStokProdukset($satuan){
		$this->db->select('ppg.*,g.name as nama_gudang,g.id as id_gudang, p.name as nama_perusahaan, p.id as id_perusahaan, pg.perusahaan_id as id_perusahaan_pg, pr.satuan_value as satuan_value, pr.id as id_produk');
		$this->db->join('product pr','ppg.product_id = pr.id');
		$this->db->join('satuan s','pr.satuan_id = s.id');
		$this->db->join('perusahaan_gudang pg','ppg.perusahaan_gudang_id = pg.id');
		$this->db->join('perusahaan p','pg.perusahaan_id = p.id');
		$this->db->join('gudang g','pg.gudang_id = g.id');
		$this->db->where('s.name',$satuan);
		//$this->db->where('pg.active',1);
		//$this->db->group_by('pg.perusahaan_id');
		return $this->db->get('product_perusahaan_gudang ppg');
	}

	public function getStokProductProses2($id_produk){
		$this->db->select('ppg.*,g.name as nama_gudang,g.id as id_gudang, p.name as nama_perusahaan, p.id as id_perusahaan, pg.perusahaan_id as id_perusahaan_pg');
		$this->db->join('perusahaan_gudang pg','ppg.perusahaan_gudang_id = pg.id');
		$this->db->join('perusahaan p','pg.perusahaan_id = p.id');
		$this->db->join('gudang g','pg.gudang_id = g.id');
		$this->db->where('ppg.product_id',$id_produk);
		//$this->db->where('pg.active',1);
		$this->db->group_by('pg.perusahaan_id');
		return $this->db->get('product_perusahaan_gudang ppg');
	}

	public function getStokProductGudangByPerusahaanByProduk($id_produk,$id_perusahaan){
		$this->db->select('ppg.*,g.name as nama_gudang,g.id as id_gudang, p.name as nama_perusahaan, p.id as id_perusahaan, sum(ppg.stok) as jumlah_stok');
		$this->db->join('perusahaan_gudang pg','ppg.perusahaan_gudang_id = pg.id');
		$this->db->join('perusahaan p','pg.perusahaan_id = p.id');
		$this->db->join('gudang g','pg.gudang_id = g.id');
		$this->db->where('ppg.product_id',$id_produk);
		$this->db->where('p.id',$id_perusahaan);
		$this->db->where('pg.active',1);
		$this->db->group_by('pg.gudang_id,pg.perusahaan_id');
		return $this->db->get('product_perusahaan_gudang ppg');
	}

	public function getRoleByNoTrac($noTrac){
		$this->db->select('*');
		$this->db->where('no_transaction',$noTrac);
		$this->db->group_by('flag_level');
		return $this->db->get('role_transaksi');
	}

}
?>