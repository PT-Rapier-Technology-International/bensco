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



			if(isset($_SESSION['rick_auto']['po_perusahaan_filter']) && $_SESSION['rick_auto']['po_perusahaan_filter'] != '0' && $_SESSION['rick_auto']['po_perusahaan_filter'] != ''){



				$this->db->where('tp.perusahaan_id',$_SESSION['rick_auto']['po_perusahaan_filter']);



			}







			$this->db->join('member m','m.id = tp.member_id');



			$this->db->join('sales s','s.id = tp.sales_id');



			$this->db->join('expedisi x','x.id = tp.expedisi');



			$this->db->order_by('tp.dateorder','DESC');



			//$this->db->limit(5);



			//$this->db->order_by('tp.update_date','DESC');



			return $this->db->get('transaction_purchase tp');







	}







	public function getRoleTanggalByNo($no){



			$this->db->select('*');



			$this->db->where('no_transaction',$no);



			//$this->db->where('flag_level',$_SESSION['rick_auto']['flag_user']);



			$this->db->order_by('create_date','DESC');



			return $this->db->get('role_transaksi');



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







	public function getReqPurchase($flagBo){



			$this->db->select('tp.*, m.name as nama_member, m.city as kota_member, s.name as nama_sales, e.name as nama_expedisi, ev.name as nama_via_expedisi');



			$this->db->join('member m','m.id = tp.member_id');



			$this->db->join('sales s','s.id = tp.sales_id');



			$this->db->join('expedisi e','e.id = tp.expedisi');



			$this->db->join('expedisi_via ev','ev.id = tp.expedisi_via','LEFT');



			$this->db->where('tp.status !=',2);



			$this->db->where('tp.flag_bo',$flagBo);



			if(isset($_SESSION['rick_auto']['rpo_perusahaan_filter']) && $_SESSION['rick_auto']['rpo_perusahaan_filter'] != '0' && $_SESSION['rick_auto']['rpo_perusahaan_filter'] != ''){



				$this->db->where('tp.perusahaan_id',$_SESSION['rick_auto']['rpo_perusahaan_filter']);



			}







			if(isset($_SESSION['rick_auto']['rpo_sales_filter']) && $_SESSION['rick_auto']['rpo_sales_filter'] != '0' && $_SESSION['rick_auto']['rpo_sales_filter'] != '' && $_SESSION['rick_auto']['rpo_sales_filter'] != 'null' && $_SESSION['rick_auto']['rpo_sales_filter'] != NULL){



				$string = $_SESSION['rick_auto']['rpo_sales_filter'];



				//$array =array_map('strval', explode(',', $string));



				$array = explode(',', $string);



				$this->db->where_in('tp.sales_id',$array);



			}







			$this->db->order_by('tp.dateorder','DESC');



			return $this->db->get('transaction_purchase_temporary tp');







	}







	public function getReqPurchaseBo(){



			$this->db->select('tptd.*, tp.*, m.name as nama_member, m.uniq_code as uniq_code_member, m.city as kota_member, s.name as nama_sales, e.name as nama_expedisi, ev.name as nama_via_expedisi, p.product_name as nama_produk, c.cat_name as nama_kategori, st.name as nama_satuan');



			$this->db->join('transaction_purchase_temporary tp','tp.id = tptd.transaction_purchase_temporary_id');



			$this->db->join('product p','p.id = tptd.product_id');



			$this->db->join('satuan st','st.id = p.satuan_id');



			$this->db->join('category_product c','p.category_id = c.id');



			$this->db->join('member m','m.id = tp.member_id');



			$this->db->join('sales s','s.id = tp.sales_id');



			$this->db->join('expedisi e','e.id = tp.expedisi');



			$this->db->join('expedisi_via ev','ev.id = tp.expedisi_via','LEFT');



			$this->db->where('tp.status !=',2);



			$this->db->where('tp.flag_bo',1);



			if(isset($_SESSION['rick_auto']['filter_bo_produk']) && $_SESSION['rick_auto']['filter_bo_produk'] != ''){



				$this->db->like('p.product_name',$_SESSION['rick_auto']['filter_bo_produk']);



				$this->db->or_like('p.product_code',$_SESSION['rick_auto']['filter_bo_produk']);



			}



			if(isset($_SESSION['rick_auto']['filter_bo_kategori']) && $_SESSION['rick_auto']['filter_bo_kategori'] != '0' && $_SESSION['rick_auto']['filter_bo_kategori'] != '' && $_SESSION['rick_auto']['filter_bo_kategori'] != 'null'){



				$string = $_SESSION['rick_auto']['filter_bo_kategori'];



				//$array =array_map('strval', explode(',', $string));



				$filter_bo_kategori = explode(',', $string);



				$this->db->where_in('p.category_id',$filter_bo_kategori);



			}



			if(isset($_SESSION['rick_auto']['filter_bo_tanggalfrom']) && $_SESSION['rick_auto']['filter_bo_tanggalto']){



				$this->db->where('tp.dateorder >=',$_SESSION['rick_auto']['filter_bo_tanggalfrom']);



				$this->db->where('tp.dateorder <=',$_SESSION['rick_auto']['filter_bo_tanggaltoo']);



			}



			$this->db->order_by('tp.dateorder','DESC');



			return $this->db->get('transaction_purchase_temporary_detail tptd');







	}







	public function getReqPurchaseBoQty(){



			$this->db->select('tptd.*, sum(tptd.qty) as qty, tp.*, m.name as nama_member, m.uniq_code as uniq_code_member, m.city as kota_member, s.name as nama_sales, e.name as nama_expedisi, ev.name as nama_via_expedisi, p.product_name as nama_produk, c.cat_name as nama_kategori, st.name as nama_satuan');



			$this->db->join('transaction_purchase_temporary tp','tp.id = tptd.transaction_purchase_temporary_id');



			$this->db->join('product p','p.id = tptd.product_id');



			$this->db->join('satuan st','st.id = p.satuan_id');



			$this->db->join('category_product c','p.category_id = c.id');



			$this->db->join('member m','m.id = tp.member_id');



			$this->db->join('sales s','s.id = tp.sales_id');



			$this->db->join('expedisi e','e.id = tp.expedisi');



			$this->db->join('expedisi_via ev','ev.id = tp.expedisi_via','LEFT');



			$this->db->where('tp.status !=',2);



			$this->db->where('tp.flag_bo',1);



			if(isset($_SESSION['rick_auto']['filter_bo_produk']) && $_SESSION['rick_auto']['filter_bo_produk'] != ''){



				$this->db->like('p.product_name',$_SESSION['rick_auto']['filter_bo_produk']);



				$this->db->or_like('p.product_code',$_SESSION['rick_auto']['filter_bo_produk']);



			}



			if(isset($_SESSION['rick_auto']['filter_bo_kategori']) && $_SESSION['rick_auto']['filter_bo_kategori'] != '0' && $_SESSION['rick_auto']['filter_bo_kategori'] != '' && $_SESSION['rick_auto']['filter_bo_kategori'] != 'null'){



				$string = $_SESSION['rick_auto']['filter_bo_kategori'];



				//$array =array_map('strval', explode(',', $string));



				$filter_bo_kategori = explode(',', $string);



				$this->db->where_in('p.category_id',$filter_bo_kategori);



			}



			if(isset($_SESSION['rick_auto']['filter_bo_tanggalfrom']) && $_SESSION['rick_auto']['filter_bo_tanggalto']){



				$this->db->where('tp.dateorder >=',$_SESSION['rick_auto']['filter_bo_tanggalfrom']);



				$this->db->where('tp.dateorder <=',$_SESSION['rick_auto']['filter_bo_tanggaltoo']);



			}



			$this->db->order_by('tp.dateorder','DESC');



			$this->db->group_by('tptd.product_id');



			return $this->db->get('transaction_purchase_temporary_detail tptd');







	}







	public function getPurchaseByBoQty(){



		$this->db->select('tptd.*, sum(tptd.qty) as qty, tp.*, m.name as nama_member, m.uniq_code as uniq_code_member, m.city as kota_member, s.name as nama_sales, e.name as nama_expedisi, ev.name as nama_via_expedisi, p.product_name as nama_produk, c.cat_name as nama_kategori, st.name as nama_satuan');



			$this->db->join('transaction_purchase tp','tp.id = tptd.transaction_purchase_id');



			$this->db->join('product p','p.id = tptd.product_id');



			$this->db->join('satuan st','st.id = p.satuan_id');



			$this->db->join('category_product c','p.category_id = c.id');



			$this->db->join('member m','m.id = tp.member_id');



			$this->db->join('sales s','s.id = tp.sales_id');



			$this->db->join('expedisi e','e.id = tp.expedisi');



			$this->db->join('expedisi_via ev','ev.id = tp.expedisi_via','LEFT');



			// if(isset($_SESSION['rick_auto']['rpo_perusahaan_filter']) && $_SESSION['rick_auto']['rpo_perusahaan_filter'] != '0' && $_SESSION['rick_auto']['rpo_perusahaan_filter'] != ''){



			// 	$this->db->where('tp.perusahaan_id',$_SESSION['rick_auto']['rpo_perusahaan_filter']);



			// }



			if(isset($_SESSION['rick_auto']['filter_bo_produk']) && $_SESSION['rick_auto']['filter_bo_produk'] != ''){



				// $this->db->like('p.product_name',$_SESSION['rick_auto']['filter_bo_produk']);



				// $this->db->or_like('p.product_code',$_SESSION['rick_auto']['filter_bo_produk']);



				$this->db->where('(p.product_name LIKE "%'.$_SESSION['rick_auto']['filter_bo_produk'].'%" OR p.product_code LIKE "%'.$_SESSION['rick_auto']['filter_bo_produk'].'%")');



			}



			if(isset($_SESSION['rick_auto']['filter_bo_kategori']) && $_SESSION['rick_auto']['filter_bo_kategori'] != '0' && $_SESSION['rick_auto']['filter_bo_kategori'] != '' && $_SESSION['rick_auto']['filter_bo_kategori'] != 'null'){



				$string = $_SESSION['rick_auto']['filter_bo_kategori'];



				//$array =array_map('strval', explode(',', $string));



				$filter_bo_kategori = explode(',', $string);



				$this->db->where_in('p.category_id',$filter_bo_kategori);



			}



			if(isset($_SESSION['rick_auto']['filter_bo_tanggalfrom']) && $_SESSION['rick_auto']['filter_bo_tanggalto']){



				$this->db->where('tp.dateorder >=',$_SESSION['rick_auto']['filter_bo_tanggalfrom']);



				$this->db->where('tp.dateorder <=',$_SESSION['rick_auto']['filter_bo_tanggaltoo']);



			}



			$this->db->like('tp.kode_rpo','BO');



			//$this->db->where("select * from transaction_purchase where kode_rpo = '%BO%'");



			$this->db->order_by('tp.dateorder','DESC');



			$this->db->group_by('tptd.product_id');



			return $this->db->get('transaction_purchase_detail tptd');



	}







	public function getPurchaseByBo(){



		$this->db->select('tptd.*, tp.*, m.name as nama_member, m.uniq_code as uniq_code_member, m.city as kota_member, s.name as nama_sales, e.name as nama_expedisi, ev.name as nama_via_expedisi, p.product_name as nama_produk, c.cat_name as nama_kategori, st.name as nama_satuan');



			$this->db->join('transaction_purchase tp','tp.id = tptd.transaction_purchase_id');



			$this->db->join('product p','p.id = tptd.product_id');



			$this->db->join('satuan st','st.id = p.satuan_id');



			$this->db->join('category_product c','p.category_id = c.id');



			$this->db->join('member m','m.id = tp.member_id');



			$this->db->join('sales s','s.id = tp.sales_id');



			$this->db->join('expedisi e','e.id = tp.expedisi');



			$this->db->join('expedisi_via ev','ev.id = tp.expedisi_via','LEFT');



			// if(isset($_SESSION['rick_auto']['rpo_perusahaan_filter']) && $_SESSION['rick_auto']['rpo_perusahaan_filter'] != '0' && $_SESSION['rick_auto']['rpo_perusahaan_filter'] != ''){



			// 	$this->db->where('tp.perusahaan_id',$_SESSION['rick_auto']['rpo_perusahaan_filter']);



			// }



			if(isset($_SESSION['rick_auto']['filter_bo_produk']) && $_SESSION['rick_auto']['filter_bo_produk'] != ''){



				// $this->db->like('p.product_name',$_SESSION['rick_auto']['filter_bo_produk']);



				// $this->db->or_like('p.product_code',$_SESSION['rick_auto']['filter_bo_produk']);



				$this->db->where('(p.product_name LIKE "%'.$_SESSION['rick_auto']['filter_bo_produk'].'%" OR p.product_code LIKE "%'.$_SESSION['rick_auto']['filter_bo_produk'].'%")');



			}



			if(isset($_SESSION['rick_auto']['filter_bo_kategori']) && $_SESSION['rick_auto']['filter_bo_kategori'] != '0' && $_SESSION['rick_auto']['filter_bo_kategori'] != '' && $_SESSION['rick_auto']['filter_bo_kategori'] != 'null'){



				$string = $_SESSION['rick_auto']['filter_bo_kategori'];



				//$array =array_map('strval', explode(',', $string));



				$filter_bo_kategori = explode(',', $string);



				$this->db->where_in('p.category_id',$filter_bo_kategori);



			}



			if(isset($_SESSION['rick_auto']['filter_bo_tanggalfrom']) && $_SESSION['rick_auto']['filter_bo_tanggalto']){



				$this->db->where('tp.dateorder >=',$_SESSION['rick_auto']['filter_bo_tanggalfrom']);



				$this->db->where('tp.dateorder <=',$_SESSION['rick_auto']['filter_bo_tanggaltoo']);



			}



			$this->db->like('tp.kode_rpo','BO');



			//$this->db->where("select * from transaction_purchase where kode_rpo = '%BO%'");



			$this->db->order_by('tp.dateorder','DESC');



			return $this->db->get('transaction_purchase_detail tptd');



	}







	public function getReqPurchaseCancelled(){



			$this->db->select('tp.*, m.name as nama_member, m.city as kota_member, s.name as nama_sales, e.name as nama_expedisi');



			$this->db->join('member m','m.id = tp.member_id');



			$this->db->join('sales s','s.id = tp.sales_id');



			$this->db->join('expedisi e','e.id = tp.expedisi');



			$this->db->where('tp.status',2);



			if(isset($_SESSION['rick_auto']['rpo_perusahaan_filter']) && $_SESSION['rick_auto']['rpo_perusahaan_filter'] != '0' && $_SESSION['rick_auto']['rpo_perusahaan_filter'] != ''){



				$this->db->where('tp.perusahaan_id',$_SESSION['rick_auto']['rpo_perusahaan_filter']);



			} else {

				$this->db->limit(50);
			}



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







	public function getPurchaseDetailByPurchaseAndProduk($id,$idProduk){



		$this->db->select('tpd.*, p.product_name as nama_produk, p.product_code as kode_produk, p.product_desc as deskripsi_produk, p.normal_price as harga_satuan, s.name as nama_satuan, p.product_cover as gambar_cover');



		$this->db->join('product p','p.id = tpd.product_id');



		$this->db->join('satuan s','s.id = p.satuan_id');



		$this->db->where('tpd.transaction_purchase_id',$id);



		$this->db->where('tpd.product_id',$idProduk);



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



		//$this->db->where('colly !=',' ');



		//$this->db->where('weight !=',' ');



		$this->db->where('transaction_purchase_id',$purchase);



		return $this->db->get('transaction_purchase_detail');



	}







	public function getCekValidPurchaseQty($purchase){



		return $this->db->query("select * from transaction_purchase_detail where transaction_purchase_id = ".$purchase." AND qty <> qty_kirim");



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







	public function getPurchaseDetailByPurchaseIDD($id){



		$this->db->select('*');



		$this->db->where('id',$id);;



		return $this->db->get('transaction_purchase_detail');



	}







	public function getReqPurchaseByID($id){



		$this->db->select('tp.*, m.name as nama_member, m.email as email_member, m.address as alamat_member, m.phone as phone_member, m.ktp as ktp, s.name as nama_sales, m.city as kota_member');



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







	public function getTemporaryProcessBOGroup(){



		$this->db->select('*,sum(price) as total_unit, sum(ttl_price) as total_semua');



		$this->db->group_by('perusahaan_id');



		return $this->db->get('transaction_purchase_temporary_process_bo');



	}







	public function getTemporaryProcessBO(){



		$this->db->select('*');



		return $this->db->get('transaction_purchase_temporary_process_bo');



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







	public function getTemporaryByMember($member){



		$this->db->select('*');



		$this->db->where('member_id',$member);



		return $this->db->get('transaction_purchase_temporary');



	}







	public function getTemporaryByNoBO($noBo){



		$this->db->select('*');



		$this->db->where('notransaction',$noBo);



		return $this->db->get('transaction_purchase_temporary');



	}







	public function getTemporaryBySales($sales){



		$this->db->select('*');



		$this->db->where('sales_id',$sales);



		return $this->db->get('transaction_purchase_temporary');



	}







	public function getPurchaseByMember($member){



		$this->db->select('*');



		$this->db->where('member_id',$member);



		return $this->db->get('transaction_purchase');



	}







	public function getPurchaseBySales($sales){



		$this->db->select('*');



		$this->db->where('sales_id',$sales);



		return $this->db->get('transaction_purchase');



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







	public function getPurchaseByPerusahaanYear($perusahaan,$tahun){



		$this->db->select('*');



		$this->db->like('nonota',$perusahaan);



		$this->db->where('YEAR(dateorder)',$tahun);



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







	public function getDetailPoFilterStok(){



		$this->db->select('tptd.*, tptd.qty, tp.*, tp.id as id_purchase, p.product_name as nama_produk, p.product_code as kode_produk, c.cat_name as nama_kategori, st.name as nama_satuan, i.nonota as nonotas, g.name as nama_gudang');



			$this->db->join('transaction_purchase tp','tp.id = tptd.transaction_purchase_id');



			$this->db->join('invoice i','tp.nonota = i.purchase_no','LEFT');



			$this->db->join('product p','p.id = tptd.product_id');



			$this->db->join('satuan st','st.id = p.satuan_id');



			$this->db->join('category_product c','p.category_id = c.id');



			$this->db->join('gudang g','g.id = tptd.gudang_id');







			// if(isset($_SESSION['rick_auto']['rpo_perusahaan_filter']) && $_SESSION['rick_auto']['rpo_perusahaan_filter'] != '0' && $_SESSION['rick_auto']['rpo_perusahaan_filter'] != ''){



			// 	$this->db->where('tp.perusahaan_id',$_SESSION['rick_auto']['rpo_perusahaan_filter']);



			// }



			if(isset($_SESSION['rick_auto']['filter_stok_produk']) && $_SESSION['rick_auto']['filter_stok_produk'] != ''){



				// $this->db->like('p.product_name',$_SESSION['rick_auto']['filter_stok_produk']);



				// $this->db->or_like('p.product_code',$_SESSION['rick_auto']['filter_stok_produk']);



				$this->db->where('(p.product_name LIKE "%'.$_SESSION['rick_auto']['filter_stok_produk'].'%" OR p.product_code LIKE "%'.$_SESSION['rick_auto']['filter_stok_produk'].'%")');



			}



			if(isset($_SESSION['rick_auto']['filter_stok_kategori']) && $_SESSION['rick_auto']['filter_stok_kategori'] != '0' && $_SESSION['rick_auto']['filter_stok_kategori'] != '' && $_SESSION['rick_auto']['filter_stok_kategori'] != 'null'){



				$string = $_SESSION['rick_auto']['filter_stok_kategori'];



				//$array =array_map('strval', explode(',', $string));



				$filter_stok_kategori = explode(',', $string);



				$this->db->where_in('p.category_id',$filter_stok_kategori);



			}



			if(isset($_SESSION['rick_auto']['filter_stok_tanggalfrom']) && $_SESSION['rick_auto']['filter_stok_tanggalto']){



				$this->db->where('DATE(tp.dateorder) >=',$_SESSION['rick_auto']['filter_stok_tanggalfrom']);



				$this->db->where('DATE(tp.dateorder) <=',$_SESSION['rick_auto']['filter_stok_tanggaltoo']);



			}







			if(isset($_SESSION['rick_auto']['filter_stok_perusahaan']) && $_SESSION['rick_auto']['filter_stok_perusahaan'] != '0' && $_SESSION['rick_auto']['filter_stok_perusahaan'] != '' && $_SESSION['rick_auto']['filter_stok_perusahaan'] != 'null'){



				$this->db->where('tp.perusahaan_id',$_SESSION['rick_auto']['filter_stok_perusahaan']);



			}







			if(isset($_SESSION['rick_auto']['filter_stok_gudang']) && $_SESSION['rick_auto']['filter_stok_gudang'] != '0' && $_SESSION['rick_auto']['filter_stok_gudang'] != '' && $_SESSION['rick_auto']['filter_stok_gudang'] != 'null'){



				$string = $_SESSION['rick_auto']['filter_stok_gudang'];



				//$array =array_map('strval', explode(',', $string));



				$filter_penjualan_gudang = explode(',', $string);



				$this->db->where_in('tptd.gudang_id',$filter_penjualan_gudang);



			}



			//$this->db->like('tp.kode_rpo','BO');



			//$this->db->where("select * from transaction_purchase where kode_rpo = '%BO%'");



			$this->db->order_by('tp.dateorder','DESC');



			//$this->db->group_by('tptd.product_id');



			return $this->db->get('transaction_purchase_detail tptd');



	}



	public function getCekValidasiPoInv($purchase,$member_id,$sales_id,$perusahaan_id,$create_date,$totalbdiskon){

		$this->db->select('*');

		$this->db->where('purchase_no',$purchase);

		$this->db->where('member_id',$member_id);

		$this->db->where('sales_id',$sales_id);

		$this->db->where('perusahaan_id',$perusahaan_id);

		$this->db->where('create_date',$create_date);

		$this->db->where('total_before_diskon',$totalbdiskon);

		return $this->db->get('invoice');

	}





	// public function getCekPurchaseOrderValidSave($perusahaan,$member,$sales,$expedisi,$subtotal,$total,$note){

	// 	$this->db->select('*');

	// 	$this->db->where('');

	// 	return $this->db->get('transaction_purchase');

	// }











	// public function getReqPurchase(){



	// 	$this->db->select('*');



	// 	return $this->db->get('transaction_purchase_temporary');



	// }







	// public function getReqBoPurchase(){



	// 	$this->db->select('*');



	// 	return $this->db->get('transaction_purchase_temporary');



	// }

	public function getPenguranganStok($nonota,$id_stok,$pengurangan,$node){

		$this->db->select('*');

		$this->db->where('nonota',$nonota);
		$this->db->where('id_stok',$id_stok);
		$this->db->where('pengurangan',$pengurangan);
		$this->db->where('node',$node);

		return $this->db->get('log_pengurangan_stock');

	}



}



?>



