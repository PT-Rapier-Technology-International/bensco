<?php
class Model_produk extends CI_Model {

	public function getUsers(){
		$this->db->select('*');
		return $this->db->get('users');
	}

	public function getProdukSearch($search, $column){
		$this->db->select('*');
		$this->db->like('product_code',$search);
		$this->db->or_like('product_name',$search);
		$this->db->limit(10);
		$this->db->order_by('id','DESC');
		return $this->db->get('product')->result_array();		
	}
	// public function getProdukSearch($search, $column){
	// 	$this->db->select('product.product_code,product.id,product.product_name,product_barcode.barcode');
	// 	$this->db->from('product');
 //        $this->db->join('product_barcode', 'product_barcode.product_id = product.id');
 //        $this->db->where('product_barcode.barcode !=','');
	// 	$this->db->like('product.product_code',$search);
	// 	$this->db->or_like('product.product_name',$search);
	// 	$this->db->limit(10);
	// 	return $this->db->get()->result_array();		
	// }

	

	public function getProdukSearch__($search, $column, $perusahaan){
		$this->db->select('ppg.*,g.name as nama_gudang,g.id as id_gudang, p.name as nama_perusahaan, p.id as id_perusahaan, pg.perusahaan_id as id_perusahaan_pg, pr.id as id_produk, pr.product_name as product_name, pr.product_code as product_code ');
		$this->db->join('product pr','ppg.product_id = pr.id');
		$this->db->join('satuan s','pr.satuan_id = s.id');
		$this->db->join('perusahaan_gudang pg','ppg.perusahaan_gudang_id = pg.id');
		$this->db->join('perusahaan p','pg.perusahaan_id = p.id');
		$this->db->join('gudang g','pg.gudang_id = g.id');
		$this->db->where('pg.perusahaan_id',$perusahaan);
		$this->db->like('pr.product_code',$search);
		$this->db->or_like('pr.product_name',$search);
		$this->db->limit(10);
		//$this->db->where('pg.active',1);
		//$this->db->group_by('pg.perusahaan_id');
		return $this->db->get('product_perusahaan_gudangs ppg')->result_array();	
	}

	public function getProdukSearchPerusahaan($search, $column, $perusahaan){
		return $this->db->query("select	ppg.*, g.name AS nama_gudang, g.id AS id_gudang, p.name AS nama_perusahaan, p.id AS id_perusahaan, pg.perusahaan_id AS id_perusahaan_pg, pr.id AS id_produk, pr.product_name AS product_name, pr.product_code AS product_code FROM product_perusahaan_gudang ppg JOIN product pr ON ppg.product_id = pr.id JOIN perusahaan_gudang pg ON ppg.perusahaan_gudang_id = pg.id JOIN perusahaan p ON pg.perusahaan_id = p.id JOIN gudang g ON pg.gudang_id = g.id WHERE (pg.perusahaan_id = ".$perusahaan.") AND (pr.product_code LIKE '%".$search."%' OR pr.product_name LIKE '%".$search."%') GROUP BY pr.id LIMIT 10")->result_array();
	}

	public function getProdukSearchPerusahaanAndGudang($search, $column, $perusahaan, $gudang){
		return $this->db->query("select	ppg.*, g.name AS nama_gudang, g.id AS id_gudang, p.name AS nama_perusahaan, p.id AS id_perusahaan, pg.perusahaan_id AS id_perusahaan_pg, pr.id AS id_produk, pr.product_name AS product_name, pr.product_code AS product_code FROM product_perusahaan_gudang ppg JOIN product pr ON ppg.product_id = pr.id JOIN perusahaan_gudang pg ON ppg.perusahaan_gudang_id = pg.id JOIN perusahaan p ON pg.perusahaan_id = p.id JOIN gudang g ON pg.gudang_id = g.id WHERE (pg.perusahaan_id = ".$perusahaan." and pg.gudang_id = ".$gudang.") AND (pr.product_code LIKE '%".$search."%' OR pr.product_name LIKE '%".$search."%') GROUP BY pr.id HAVING SUM(ppg.stok) > 0 LIMIT 10")->result_array();
	}

	public function getProducts_(){
		$this->db->select('p.*,c.cat_name as nama_kategori, c.cat_code as kode_kategori, s.name as nama_satuan, s.flag_jenis as flag_jenis_satuan');
		$this->db->join('category_product c','p.category_id = c.id');
		$this->db->join('satuan s','p.satuan_id = s.id');
		$this->db->order_by('id','desc');
		return $this->db->get('product p');
	}

	public function getProducts($limit=1000000,$offset=0){
		$this->db->select('p.*,c.cat_name as nama_kategori, c.cat_code as kode_kategori, s.name as nama_satuan, s.flag_jenis as flag_jenis_satuan');
		$this->db->join('category_product c','p.category_id = c.id');
		$this->db->join('satuan s','p.satuan_id = s.id');
		$this->db->order_by('id','desc');
		$this->db->limit($limit,$offset);
		return $this->db->get('product p');
	}

	public function getAllProducts(){
		$this->db->select('*');
		return $this->db->get('product');
	}

	public function getProdukByBarcode($barcode){
		$this->db->select('*');
		$this->db->where('barcode',$barcode);
		return $this->db->get('product_barcode');
	}
	public function getProdukByID($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		return $this->db->get('product');
	}

	public function getProdukByBarcodebyProduk($produk){
		$this->db->select('*');
		$this->db->where('product_id',$produk);
		return $this->db->get('product_barcode');
	}

	public function getProdukByBarcodebyProdukAsc($produk){
		$this->db->select('*');
		$this->db->where('product_id',$produk);
		//$this->db->order_by('isi','asc');
		return $this->db->get('product_barcode');
	}
	public function getProdukBarcode($from,$to){
		$this->db->select('*');
		//$this->db->order_by('product_id','asc');
		$this->db->where('isi',1);
		$this->db->where('barcode !=','');
		$this->db->where('id >=', $from);
		$this->db->where('id <=', $to);
		return $this->db->get('product_barcode');
	}

	public function getProdukBarcode2($from,$to){
		$this->db->select('*');
		//$this->db->order_by('product_id','asc');
		$this->db->where('isi !=',1);
		$this->db->where('barcode !=','');
		$this->db->where('id >=', $from);
		$this->db->where('id <=', $to);
		return $this->db->get('product_barcode');
	}

	public function getProductss(){
		$this->db->select('p.*,c.cat_name as nama_kategori, c.cat_code as kode_kategori, s.name as nama_satuan, s.flag_jenis as flag_jenis_satuan');
		$this->db->join('category_product c','p.category_id = c.id');
		$this->db->join('satuan s','p.satuan_id = s.id');
		$this->db->group_by('p.product_code');
		return $this->db->get('product p');
	}

	public function getJumlahStok($id){
		$this->db->select('sum(ppg.stok) as jmlStok');
		$this->db->group_by('ppg.product_id');
		$this->db->where('ppg.product_id',$id);
		return $this->db->get('product_perusahaan_gudang ppg');
	}

	public function getProductById($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		return $this->db->get('product');
	}

	public function getProductsById($id){
		$this->db->select('p.*,c.cat_name as nama_kategori, c.cat_code as kode_kategori, s.name as nama_satuan, s.flag_jenis as flag_jenis_satuan');
		$this->db->join('category_product c','p.category_id = c.id');
		$this->db->join('satuan s','p.satuan_id = s.id');
		$this->db->order_by('id','desc');
		$this->db->where('p.id',$id);
		return $this->db->get('product p');
	}

	public function getProductsByKodeAndSatuan($kode,$satuan){
		$this->db->select('p.*, s.name as nama_satuan, s.flag_jenis as flag_jenis_satuan');
		$this->db->join('satuan s','p.satuan_id = s.id');
		//$this->db->order_by('id','desc');
		$this->db->where('p.product_code_shadow',$kode);
		$this->db->where('s.name',$satuan);
		return $this->db->get('product p');
	}

	public function getProductsBySatuan($satuan){
		$this->db->select('p.*,c.cat_name as nama_kategori, c.cat_code as kode_kategori, s.name as nama_satuan, s.flag_jenis as flag_jenis_satuan');
		$this->db->join('category_product c','p.category_id = c.id');
		$this->db->join('satuan s','p.satuan_id = s.id');
		$this->db->where('s.name',$satuan);
		return $this->db->get('product p');
	}

	public function getProductByCode($id){
		$this->db->select('*');
		$this->db->where('product_code',$id);
		return $this->db->get('product');
	}

	public function getProductByBarcode($id){
		$this->db->select('*');
		$this->db->where('barcode',$id);
		$this->db->where('barcode !=','');
		return $this->db->get('product');
	}

	public function getKategori(){
		$this->db->select('*');
		return $this->db->get('category_product');
	}

	public function getImageProductByProduct($id){
		$this->db->select('*');
		$this->db->where('id_product',$id);
		return $this->db->get('product_img');
	}

	public function getProductsLimit(){
		$this->db->select('*');
		if(isset($_SESSION['rick_auto']['search'])){
			$this->db->like('product_code',$_SESSION['rick_auto']['search'])->or_like('product_name',$_SESSION['rick_auto']['search']);
		}
		$this->db->limit('10,0');
		return $this->db->get('product');
	}

	public function getProdukBeli(){
		$this->db->select('*');
		$this->db->order_by('id','desc');
		return $this->db->get('produk_beli');
	}

	public function getProdukBeliById($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		return $this->db->get('produk_beli');
	}

	public function getProdukBeliDetailByIdProdukBeli($id){
		$this->db->select('pbd.*,p.name as nama_perusahaan, g.name as nama_gudang, pr.product_name as nama_produk, s.name as nama_satuan');
		$this->db->join('perusahaan p','pbd.perusahaan_id = p.id');
		$this->db->join('gudang g','pbd.gudang_id = g.id');
		$this->db->join('product pr','pbd.produk_id = pr.id');
		$this->db->join('satuan s','pr.satuan_id = s.id');
		$this->db->order_by('pbd.create_date','DESC');
		$this->db->where('pbd.produk_beli_id',$id);
		return $this->db->get('produk_beli_detail pbd');
	}

	public function getProdukBeliDetailByIdProdukBeliAndProduk($id,$produk){
		$this->db->select('pbd.*');
		$this->db->where('pbd.produk_beli_id',$id);
		$this->db->where('pbd.produk_id',$produk);
		return $this->db->get('produk_beli_detail pbd');
	}

	public function getProdukBeliTemps(){
		$this->db->select('pbd.*');
		return $this->db->get('produk_beli_detail_temp pbd');
	}

	public function getSOTemps(){
		$this->db->select('pbd.*');
		return $this->db->get('stock_opname_detail_temp pbd');
	}


	public function getProdukBeliTemp(){
		$this->db->select('pbd.*, pr.product_name as nama_produk, s.name as nama_satuan');
		$this->db->join('product pr','pbd.produk_id = pr.id');
		$this->db->join('satuan s','pr.satuan_id = s.id');
		//$this->db->order_by('pbd.produk_id','asc');
		return $this->db->get('produk_beli_detail_temp pbd');
	}

	public function getSOTempByProduk($id_produk){
		$this->db->select('pbd.*, pr.product_name as nama_produk');
		$this->db->join('product pr','pbd.produk_id = pr.id');
		$this->db->where('pbd.produk_id',$id_produk);
		return $this->db->get('stock_opname_detail_temp pbd');
	}

	public function getSOTemp(){
		$this->db->select('pbd.*, pr.product_name as nama_produk, s.name as nama_satuan');
		$this->db->join('product pr','pbd.produk_id = pr.id');
		$this->db->join('satuan s','pr.satuan_id = s.id');
		return $this->db->get('stock_opname_detail_temp pbd');
	}

	public function getProdukBeliTempByProduk($id_produk){
		$this->db->select('pbd.*, pr.product_name as nama_produk, s.name as nama_satuan');
		$this->db->join('product pr','pbd.produk_id = pr.id');
		$this->db->join('satuan s','pr.satuan_id = s.id');
		$this->db->where('pbd.produk_id',$id_produk);
		return $this->db->get('produk_beli_detail_temp pbd');
	}

	public function getProdukBeliByNoTransaction($no){
		$this->db->select('*');
		$this->db->where('notransaction',$no);
		return $this->db->get('produk_beli');
	}

	public function getSOByNoTransaction($no){
		$this->db->select('*');
		$this->db->where('notransaction',$no);
		return $this->db->get('stock_opname');
	}

	public function getLogAdjusment(){
		$this->db->select('os.*,p.name as nama_perusahaan, g.name as nama_gudang, pr.product_name as nama_produk, s.name as nama_satuan');
		$this->db->join('perusahaan p','os.perusahaan_id = p.id');
		$this->db->join('gudang g','os.gudang_id = g.id');
		$this->db->join('product pr','os.product_id = pr.id');
		$this->db->join('satuan s','pr.satuan_id = s.id');
		if(isset($_SESSION['rick_auto']['filter_perusahaan_la']) && $_SESSION['rick_auto']['filter_perusahaan_la'] != 0){
		$this->db->where('os.perusahaan_id',$_SESSION['rick_auto']['filter_perusahaan_la']);
		}
		if(isset($_SESSION['rick_auto']['filter_start_date_la'])){
			$this->db->where('os.create_date >=',$_SESSION['rick_auto']['filter_start_date_la']);
			$this->db->where('os.create_date <=',$_SESSION['rick_auto']['filter_end_date_la']);
		}
		if(isset($_SESSION['rick_auto']['filter_gudangfrom_la']) && $_SESSION['rick_auto']['filter_gudangfrom_la'] != 0 && $_SESSION['rick_auto']['filter_gudangfrom_la'] != NULL && $_SESSION['rick_auto']['filter_gudangfrom_la'] != "NULL"){
		$this->db->where('os.gudang_id',$_SESSION['rick_auto']['filter_gudangfrom_la']);
		}

		$this->db->order_by('os.create_date','desc');
		return $this->db->get('opname_stock os');
	}

	public function getLogMutasi(){
		$this->db->select('ls.*,p.name as nama_perusahaan, g.name as nama_gudang_from, p.name as nama_perusahaan_from, gg.name as nama_gudang_to, pp.name as nama_perusahaan_to, pr.product_name as nama_produk, s.name as nama_satuan');
		$this->db->join('product_perusahaan_gudang ppg','ls.product_perusahaan_gudang_id = ppg.id');
		$this->db->join('product pr','ppg.product_id = pr.id');
		$this->db->join('satuan s','pr.satuan_id = s.id');
		$this->db->join('gudang g','ls.from_gudang_id = g.id');
		$this->db->join('perusahaan p','ls.from_perusahaan_id = p.id');
		$this->db->join('gudang gg','ls.to_gudang_id = gg.id');
		$this->db->join('perusahaan pp','ls.to_perusahaan_id = pp.id');
		$this->db->order_by('ls.id','DESC');
		return $this->db->get('log_stok ls');
	}

	public function getLogMutasiFilter(){
		$this->db->select('ls.*,p.name as nama_perusahaan, g.name as nama_gudang_from, p.name as nama_perusahaan_from, gg.name as nama_gudang_to, pp.name as nama_perusahaan_to, pr.product_name as nama_produk, s.name as nama_satuan');
		$this->db->join('product_perusahaan_gudang ppg','ls.product_perusahaan_gudang_id = ppg.id');
		$this->db->join('product pr','ppg.product_id = pr.id');
		$this->db->join('satuan s','pr.satuan_id = s.id');
		$this->db->join('gudang g','ls.from_gudang_id = g.id');
		$this->db->join('perusahaan p','ls.from_perusahaan_id = p.id');
		$this->db->join('gudang gg','ls.to_gudang_id = gg.id');
		$this->db->join('perusahaan pp','ls.to_perusahaan_id = pp.id');
		if(isset($_SESSION['rick_auto']['filter_perusahaan_lt']) && $_SESSION['rick_auto']['filter_perusahaan_lt'] != 0){
		$this->db->where('ls.from_perusahaan_id',$_SESSION['rick_auto']['filter_perusahaan_lt']);
		}
		if(isset($_SESSION['rick_auto']['filter_start_date_lt'])){
			$this->db->where('ls.create_date >=',$_SESSION['rick_auto']['filter_start_date_lt']);
			$this->db->where('ls.create_date <=',$_SESSION['rick_auto']['filter_end_date_lt']);
		}
		if(isset($_SESSION['rick_auto']['filter_gudangfrom_lt']) && $_SESSION['rick_auto']['filter_gudangfrom_lt'] != 0 && $_SESSION['rick_auto']['filter_gudangfrom_lt'] != NULL && $_SESSION['rick_auto']['filter_gudangfrom_lt'] != "NULL"){
		$this->db->where('ls.from_gudang_id',$_SESSION['rick_auto']['filter_gudangfrom_lt']);
		}
		if(isset($_SESSION['rick_auto']['filter_gudangto_lt']) && $_SESSION['rick_auto']['filter_gudangto_lt'] != 0 && $_SESSION['rick_auto']['filter_gudangfrom_lt'] != NULL && $_SESSION['rick_auto']['filter_gudangfrom_lt'] != "NULL"){
		$this->db->where('ls.to_gudang_id',$_SESSION['rick_auto']['filter_gudangto_lt']);
		}
		$this->db->order_by('ls.id','DESC');
		return $this->db->get('log_stok ls');
	}

	public function getProdukBeliByReport(){
		$this->db->select('pb.*,pbd.qty as qty, p.name as nama_perusahaan, g.name as nama_gudang, pr.product_name as nama_produk, s.name as nama_satuan');
		if(isset($_SESSION['rick_auto']['perusahaanrb']) && $_SESSION['rick_auto']['perusahaanrb'] != 0){
		$this->db->where('pbd.perusahaan_id',$_SESSION['rick_auto']['perusahaanrb']);
		}
		if(isset($_SESSION['rick_auto']['gudangrb']) && $_SESSION['rick_auto']['gudangrb'] != 0){
		$this->db->where('pbd.gudang_id',$_SESSION['rick_auto']['gudangrb']);
		}
		if(isset($_SESSION['rick_auto']['produkrb']) && $_SESSION['rick_auto']['produkrb'] != 0){
		$this->db->where('pbd.produk_id',$_SESSION['rick_auto']['produkrb']);
		}
		if(isset($_SESSION['rick_auto']['tanggalfromrrb']) && $_SESSION['rick_auto']['tanggaltorrb']){
			$this->db->where('date(pb.create_date) >=',$_SESSION['rick_auto']['tanggalfromrrb']);
			$this->db->where('date(pb.create_date) <=',$_SESSION['rick_auto']['tanggaltorrb']);
		}
		$this->db->join('produk_beli_detail pbd','pb.id = pbd.produk_beli_id');
		$this->db->join('perusahaan p','pbd.perusahaan_id = p.id');
		$this->db->join('gudang g','pbd.gudang_id = g.id');
		$this->db->join('product pr','pbd.produk_id = pr.id');
		$this->db->join('satuan s','pr.satuan_id = s.id');
		return $this->db->get('produk_beli pb');
	}

	public function getInvoiceByReport(){
		$this->db->select('i.*,id.qty as qty, m.name as nama_member, m.city as kota, p.name as nama_perusahaan, g.name as nama_gudang, pr.product_name as nama_produk, id.satuan as nama_satuan, id.price as harga_satuan');
		if(isset($_SESSION['rick_auto']['perusahaanrk']) && $_SESSION['rick_auto']['perusahaanrk'] != 0){
		$this->db->where('i.perusahaan_id',$_SESSION['rick_auto']['perusahaanrk']);
		}
		if(isset($_SESSION['rick_auto']['gudangrk']) && $_SESSION['rick_auto']['gudangrk'] != 0){
		$this->db->where('id.gudang_id',$_SESSION['rick_auto']['gudangrk']);
		}
		if(isset($_SESSION['rick_auto']['produkrk']) && $_SESSION['rick_auto']['produkrk'] != 0){
		$this->db->where('id.product_code',$_SESSION['rick_auto']['produkrk']);
		}
		if(isset($_SESSION['rick_auto']['tanggalfromrrk']) && $_SESSION['rick_auto']['tanggaltorrk']){
			$this->db->where('date(i.create_date) >=',$_SESSION['rick_auto']['tanggalfromrrk']);
			$this->db->where('date(i.create_date) <=',$_SESSION['rick_auto']['tanggaltorrk']);
		}
		$this->db->join('member m','i.member_id = m.id');
		$this->db->join('invoice_detail id','i.id = id.invoice_id');
		$this->db->join('perusahaan p','i.perusahaan_id = p.id');
		$this->db->join('gudang g','id.gudang_id = g.id');
		$this->db->join('product pr','id.product_code = pr.product_code');
		return $this->db->get('invoice i');
	}

	public function getSOById($id){
		$this->db->select('so.*, p.name as nama_perusahaan, g.name as nama_gudang');
		$this->db->join('perusahaan p','p.id = so.perusahaan_id');
		$this->db->join('gudang g','g.id = so.gudang_id');
		$this->db->where('so.id',$id);
		return $this->db->get('stock_opname so');
	}

	public function getSO(){
		$this->db->select('so.*, p.name as nama_perusahaan, g.name as nama_gudang');
		$this->db->join('perusahaan p','p.id = so.perusahaan_id');
		$this->db->join('gudang g','g.id = so.gudang_id');
		$this->db->order_by('so.id','DESC');
		return $this->db->get('stock_opname so');
	}

	public function getSODetailBySO($id){
		$this->db->select('sod.*,p.product_name as nama_produk, s.name as nama_satuan');
		$this->db->join('product p','p.id = sod.produk_id');
		$this->db->join('satuan s','p.satuan_id = s.id');
		$this->db->where('sod.so_id',$id);
		return $this->db->get('stock_opname_detail sod');

	}

	public function getSODetailBySOandProduk($id,$produk){
		$this->db->select('sod.*,p.product_name as nama_produk, s.name as nama_satuan');
		$this->db->join('product p','p.id = sod.produk_id');
		$this->db->join('satuan s','p.satuan_id = s.id');
		$this->db->where('sod.so_id',$id);
		$this->db->where('sod.produk_id',$produk);
		return $this->db->get('stock_opname_detail sod');

	}
}
?>