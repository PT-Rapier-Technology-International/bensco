<?php

class Model_invoice extends CI_Model {



	public function getInvoice(){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		$this->db->where('pay_status',0);

		return $this->db->get('invoice');

	}



	public function getInvoiceDesc(){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		return $this->db->get('invoice');

	}



	public function getInvoiceDescByPerusahaan($perusahaan){

		$this->db->select('*');

		$this->db->where('perusahaan_id',$perusahaan);

		$this->db->order_by('id','desc');

		return $this->db->get('invoice');

	}



	public function getInvoiceDescByPerusahaanAndYear($perusahaan,$date){

		$this->db->select('*');

		$this->db->where('perusahaan_id',$perusahaan);

		$this->db->where('YEAR(dateorder)',$date);

		$this->db->order_by('id','desc');

		return $this->db->get('invoice');

	}



	public function getTotalInvoiceByInvoice($id){

		$this->db->select('sum(ttl_price) as total_harga');

		$this->db->where('invoice_id',$id);

		return $this->db->get('invoice_detail');

	}



	public function getInvoiceByMember($member,$flag){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		$this->db->order_by('perusahaan_id','asc');

		$this->db->where('member_id',$member);

		$this->db->where('pay_status',$flag);

		return $this->db->get('invoice');

	}



	public function getInvoiceByNoNota($no){

		$this->db->select('*');

		$this->db->where('nonota',$no);

		return $this->db->get('invoice');

	}



	public function getInvoiceByIdAndFlag($id,$flag){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		$this->db->order_by('perusahaan_id','asc');

		$this->db->where('id',$id);

		$this->db->where('pay_status',$flag);

		return $this->db->get('invoice');

	}



	function removeslashes($string)

	{

	    $string=implode("",explode("\\",$string));

	    return stripslashes(trim($string));

	}



	public function getInvoiceByAllMembers($flag_tt){

		$this->db->select('i.*, m.name as nama_lengkap_member, m.city as kota');

		$this->db->order_by('i.id','desc');

		$this->db->order_by('i.perusahaan_id','asc');

		$this->db->join('member m','i.member_id = m.id');

		$this->db->where('i.flag_tanda_terima',$flag_tt);

		if(isset($_SESSION['rick_auto']['filter_start_date']) && $_SESSION['rick_auto']['filter_end_date']){

			$this->db->where('i.dateorder >',$_SESSION['rick_auto']['filter_start_date']);

			$this->db->where('i.dateorder <',$_SESSION['rick_auto']['filter_end_date']);

		}



		if(isset($_SESSION['rick_auto']['filter_sales'])){

			$string = $_SESSION['rick_auto']['filter_sales'];

			//$array =array_map('strval', explode(',', $string));

			$array = explode(',', $string);



			$this->db->where_in('i.sales_id ',$array);

		}



		// if(isset($_SESSION['rick_auto']['filter_member']) && $_SESSION['rick_auto']['filter_member'] != '0'){

		// 	$this->db->where('i.member_id ',$_SESSION['rick_auto']['filter_member']);

		// }else{

		// }



		// if(isset($_SESSION['rick_auto']['filter_member']) && $_SESSION['rick_auto']['filter_member'] != '0' && $_SESSION['rick_auto']['filter_member'] != ''){

		// 	$this->db->like('m.name',$_SESSION['rick_auto']['filter_member']);

		// 	$this->db->or_like('m.city',$_SESSION['rick_auto']['filter_member']);

		// }else{

		// }



		if(isset($_SESSION['rick_auto']['filter_member']) && $_SESSION['rick_auto']['filter_member'] != '0' && $_SESSION['rick_auto']['filter_member'] != ''){

			$this->db->where('i.member_id',$_SESSION['rick_auto']['filter_member']);

		}else{

		}



		if(isset($_SESSION['rick_auto']['filter_invoice_no']) && $_SESSION['rick_auto']['filter_invoice_no'] != ""){

			$string = $_SESSION['rick_auto']['filter_invoice_no'];



			$this->db->like('i.nonota ',$_SESSION['rick_auto']['filter_invoice_no']);

		}else{



		}



		if(isset($_SESSION['rick_auto']['filter_perusahaan']) && $_SESSION['rick_auto']['filter_perusahaan'] != 0){

			$string = $_SESSION['rick_auto']['filter_perusahaan'];



			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['filter_perusahaan']);

		}else{

			$this->db->limit(20);
		}

		if (isset($_SESSION['rick_auto']['filter_perusahaan']) && $_SESSION['rick_auto']['filter_perusahaan']){

			if (isset($_SESSION['rick_auto']['filter_member']) && $_SESSION['rick_auto']['filter_member']){

				$this->db->where('i.member_id',$_SESSION['rick_auto']['filter_member']);
				
			} else {
			
				$this->db->limit(30);
			}

		} 




		//var_dump();

		//$this->db->where('MONTH(dateorder)',$month);

		return $this->db->get('invoice i');



	}



	public function getInvoiceByAllMembersReportTT_($flag_tt){

		$this->db->select('i.*, m.name as nama_lengkap_member, m.city as kota');

		$this->db->order_by('i.id','desc');

		$this->db->order_by('i.perusahaan_id','asc');

		$this->db->where('i.flag_tanda_terima',$flag_tt);

		$this->db->join('member m','i.member_id = m.id');

		if(isset($_SESSION['rick_auto']['tanggalfromrtt']) && $_SESSION['rick_auto']['tanggaltortt']){

			$this->db->where('i.dateorder >',$_SESSION['rick_auto']['tanggalfromrtt']);

			$this->db->where('i.dateorder <',$_SESSION['rick_auto']['tanggaltortt']);

		}



		if(isset($_SESSION['rick_auto']['salesrtt'])){

			$string = $_SESSION['rick_auto']['salesrtt'];

			//$array =array_map('strval', explode(',', $string));

			$array = explode(',', $string);



			$this->db->where_in('i.sales_id ',$array);

		}



		// if(isset($_SESSION['rick_auto']['filter_member']) && $_SESSION['rick_auto']['filter_member'] != '0'){

		// 	$this->db->where('i.member_id ',$_SESSION['rick_auto']['filter_member']);

		// }else{

		// }





		if(isset($_SESSION['rick_auto']['perusahaanrtt']) && $_SESSION['rick_auto']['perusahaanrtt'] != 0){

			$string = $_SESSION['rick_auto']['perusahaanrtt'];



			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['perusahaanrtt']);

		}else{



		}





		//var_dump();

		//$this->db->where('MONTH(dateorder)',$month);

		$this->db->order_by('m.name','asc');

		return $this->db->get('invoice i');



	}



	public function getInvoiceByAllMembersReportTT($flag_tt){

		$this->db->select('i.*, m.name as nama_lengkap_member, m.city as kota,  m.uniq_code as kodemember');

		//$this->db->order_by('i.id','desc');



		$this->db->join('member m','i.member_id = m.id');

		//$this->db->join('invoice_tanda_terima itt', 'i.id = itt.invoice_id');

		$this->db->where('i.flag_tanda_terima',$flag_tt);

		 // $this->db->order_by('i.dateorder','asc');

		if(isset($_SESSION['rick_auto']['tanggalfromrtt']) && $_SESSION['rick_auto']['tanggaltortt']){

			$this->db->where('i.dateorder >=',$_SESSION['rick_auto']['tanggalfromrtt']);

			$this->db->where('i.dateorder <=',$_SESSION['rick_auto']['tanggaltortt']);

		}



		if(isset($_SESSION['rick_auto']['salesrtt'])){

			$string = $_SESSION['rick_auto']['salesrtt'];

			//$array =array_map('strval', explode(',', $string));

			$array = explode(',', $string);



			$this->db->where_in('i.sales_id',$array);

		}



		// if(isset($_SESSION['rick_auto']['filter_member']) && $_SESSION['rick_auto']['filter_member'] != '0'){

		// 	$this->db->where('i.member_id ',$_SESSION['rick_auto']['filter_member']);

		// }else{

		// }

		// if(isset($_SESSION['rick_auto']['cityrtt']) && $_SESSION['rick_auto']['cityrtt'] != ""){

		// 	$this->db->like('m.city',$_SESSION['rick_auto']['cityrtt']);

		// }



		if(isset($_SESSION['rick_auto']['cityrtt'])){

			$stringMember = $_SESSION['rick_auto']['cityrtt'];

			//$array =array_map('strval', explode(',', $string));

			$arrayMember = explode(',', $stringMember);



			$this->db->where_in('m.city_id',$arrayMember);

		}





		if(isset($_SESSION['rick_auto']['perusahaanrtt']) && $_SESSION['rick_auto']['perusahaanrtt'] != 0){

			$string = $_SESSION['rick_auto']['perusahaanrtt'];



			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['perusahaanrtt']);

		}else{



		}

		$this->db->order_by('member_name','asc');

		$this->db->order_by('i.dateorder','asc');

		$this->db->order_by('m.city','asc');

		$this->db->group_by('i.member_id');

		$this->db->order_by('m.city_id','asc');

		//var_dump();

		//$this->db->where('MONTH(dateorder)',$month);

		return $this->db->get('invoice i');



	}

	public function getCityIn(){

		$this->db->select('*');

		$stringMember = $_SESSION['rick_auto']['cityrtt'];

			//$array =array_map('strval', explode(',', $string));

		$arrayMember = explode(',', $stringMember);



		$this->db->where_in('m.id',$arrayMember);

		return $this->db->get('city');

	}



	public function getInvoiceByMemberPrintTT($flag_tt,$member_id){

		$this->db->select('i.*, m.name as nama_lengkap_member, m.city as kota, m.id as id_member');

		$this->db->order_by('i.nonota','asc');

		$this->db->order_by('i.id','desc');

		$this->db->order_by('i.perusahaan_id','asc');

		$this->db->join('member m','i.member_id = m.id');

		//$this->db->join()

		$this->db->where('i.flag_tanda_terima',$flag_tt);

		if(isset($_SESSION['rick_auto']['tanggalfromrtt']) && $_SESSION['rick_auto']['tanggaltortt']){

			$this->db->where('i.dateorder >=',$_SESSION['rick_auto']['tanggalfromrtt']);

			$this->db->where('i.dateorder <=',$_SESSION['rick_auto']['tanggaltorttt']);

		}



		if(isset($_SESSION['rick_auto']['salesrtt'])){

			$string = $_SESSION['rick_auto']['salesrtt'];

			//$array =array_map('strval', explode(',', $string));

			$array = explode(',', $string);



			$this->db->where_in('i.sales_id ',$array);

		}



		// if(isset($_SESSION['rick_auto']['filter_member']) && $_SESSION['rick_auto']['filter_member'] != '0'){

		// 	$this->db->where('i.member_id ',$_SESSION['rick_auto']['filter_member']);

		// }else{

		// }

		if(isset($_SESSION['rick_auto']['cityrtt'])){

			$stringMember = $_SESSION['rick_auto']['cityrtt'];

			//$array =array_map('strval', explode(',', $string));

			$arrayMember = explode(',', $stringMember);



			$this->db->where_in('m.city_id',$arrayMember);

		}





		if(isset($_SESSION['rick_auto']['perusahaanrtt']) && $_SESSION['rick_auto']['perusahaanrtt'] != 0){

			$string = $_SESSION['rick_auto']['perusahaanrtt'];



			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['perusahaanrtt']);

		}else{



		}

		//$this->db->group_by('i.member_id');

		$this->db->where('i.member_id',$member_id);



		//var_dump();

		//$this->db->where('MONTH(dateorder)',$month);

		return $this->db->get('invoice i');



	}



	public function getInvoiceByAllMemberss(){

		$this->db->select('i.*, m.name as nama_lengkap_member, m.city as kota, m.id as id_member, vt.totalz');

		$this->db->order_by('i.id','desc');

		$this->db->order_by('i.perusahaan_id','asc');

		$this->db->join('member m','i.member_id = m.id');

		$this->db->join('vw_totinvoice vt','i.id = vt.invoice_id');

		if(isset($_SESSION['rick_auto']['filter_start_date']) && $_SESSION['rick_auto']['filter_end_date']){

			$this->db->where('i.dateorder >',$_SESSION['rick_auto']['filter_start_date']);

			$this->db->where('i.dateorder <',$_SESSION['rick_auto']['filter_end_date']);

		}



		if(isset($_SESSION['rick_auto']['filter_sales'])){

			$string = $_SESSION['rick_auto']['filter_sales'];

			//$array =array_map('strval', explode(',', $string));

			$array = explode(',', $string);



			$this->db->where_in('i.sales_id ',$array);

		}



		// if(isset($_SESSION['rick_auto']['filter_member']) && $_SESSION['rick_auto']['filter_member'] != '0'){

		// 	$this->db->where('i.member_id ',$_SESSION['rick_auto']['filter_member']);

		// }else{

		// }



		// if(isset($_SESSION['rick_auto']['filter_member']) && $_SESSION['rick_auto']['filter_member'] != '0' && $_SESSION['rick_auto']['filter_member'] != ''){

		// 	$this->db->like('m.name',$_SESSION['rick_auto']['filter_member']);

		// 	$this->db->or_like('m.city',$_SESSION['rick_auto']['filter_member']);

		// }else{

		// }



		if(isset($_SESSION['rick_auto']['filter_member']) && $_SESSION['rick_auto']['filter_member'] != '0' && $_SESSION['rick_auto']['filter_member'] != ''){

			$this->db->where('i.member_id',$_SESSION['rick_auto']['filter_member']);

		}else{

		}



		if(isset($_SESSION['rick_auto']['filter_invoice_no']) && $_SESSION['rick_auto']['filter_invoice_no'] != ""){

			$string = $_SESSION['rick_auto']['filter_invoice_no'];



			$this->db->like('i.nonota ',$_SESSION['rick_auto']['filter_invoice_no']);

		}else{



		}



		if(isset($_SESSION['rick_auto']['filter_perusahaan']) && $_SESSION['rick_auto']['filter_perusahaan'] != 0){

			$string = $_SESSION['rick_auto']['filter_perusahaan'];



			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['filter_perusahaan']);

		}else{

				$this->db->limit(20);

		}

		if (isset($_SESSION['rick_auto']['filter_perusahaan']) && $_SESSION['rick_auto']['filter_perusahaan']){
			if (isset($_SESSION['rick_auto']['filter_member']) && $_SESSION['rick_auto']['filter_member']){

				$this->db->where('i.member_id',$_SESSION['rick_auto']['filter_member']);

			} else {

				$this->db->limit(30);
			}
		}



		//var_dump();

		//$this->db->where('MONTH(dateorder)',$month);
		//$this->db->limit(20);

		return $this->db->get('invoice i');



	}



	public function getInvoiceByAllMemberssRekapInvoice(){

		$this->db->select('i.*, m.name as nama_lengkap_member, m.city as kota, m.id as id_member');

		$this->db->order_by('i.nonota','desc');

		$this->db->join('member m','i.member_id = m.id');



		if(isset($_SESSION['rick_auto']['filter_perusahaan_rri']) && $_SESSION['rick_auto']['filter_perusahaan_rri'] != 0){

			$string = $_SESSION['rick_auto']['filter_perusahaan_rri'];



			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['filter_perusahaan_rri']);

		}else{



		}



		// if(isset($_SESSION['rick_auto']['filter_tanggal_rri'])){

		// 	$string = $_SESSION['rick_auto']['filter_tanggal_rri'];



		// 	$this->db->where('date(i.create_date) ',$_SESSION['rick_auto']['filter_tanggal_rri']);

		// }else{



		// }

		if(isset($_SESSION['rick_auto']['filter_tanggal_rri']) && $_SESSION['rick_auto']['filter_tanggal_to_rri']){

			$this->db->where('date(i.dateorder) >=',$_SESSION['rick_auto']['filter_tanggal_rri']);

			$this->db->where('date(i.dateorder) <=',$_SESSION['rick_auto']['filter_tanggal_to_rrii']);

		}





		//var_dump();

		//$this->db->where('MONTH(dateorder)',$month);

		return $this->db->get('invoice i');



	}



	public function getInvoiceByAllMembersRevisi($flag_tt){

		$this->db->select('i.*, m.name as nama_lengkap_member, m.city as kota, itt.no_tanda_terima as no_tt');



		if(isset($_SESSION['rick_auto']['filter_invoice_no_rr']) && $_SESSION['rick_auto']['filter_invoice_no_rr'] != ""){

			$string = $_SESSION['rick_auto']['filter_invoice_no_rr'];



			$this->db->like('i.nonota ',$_SESSION['rick_auto']['filter_invoice_no_rr']);

		}



		if(isset($_SESSION['rick_auto']['filter_perusahaan_rr']) && $_SESSION['rick_auto']['filter_perusahaan_rr'] != 0){

			$string = $_SESSION['rick_auto']['filter_perusahaan_rr'];



			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['filter_perusahaan_rr']);

		}



		if(isset($_SESSION['rick_auto']['filter_member_rr']) && $_SESSION['rick_auto']['filter_member_rr'] != '0'){

			$this->db->where('i.member_id ',$_SESSION['rick_auto']['filter_member_rr']);

		}else{

		}





		$this->db->order_by('i.id','desc');

		//$this->db->order_by('i.perusahaan_id','asc');

		$this->db->join('member m','i.member_id = m.id');

		$this->db->join('invoice_tanda_terima itt','i.id = itt.invoice_id');

		$this->db->where('i.flag_tanda_terima',$flag_tt);

		return $this->db->get('invoice i');



	}



	public function getInvoiceByAllMembersRevisii(){

		$this->db->select('i.*, m.name as nama_lengkap_member, m.city as kota, itt.no_tanda_terima as no_tt');



		if(isset($_SESSION['rick_auto']['filter_invoice_no_rr']) && $_SESSION['rick_auto']['filter_invoice_no_rr'] != ""){

			$string = $_SESSION['rick_auto']['filter_invoice_no_rr'];



			$this->db->like('i.nonota ',$_SESSION['rick_auto']['filter_invoice_no_rr']);

		}



		if(isset($_SESSION['rick_auto']['filter_no_retur_revisi']) && $_SESSION['rick_auto']['filter_no_retur_revisi'] != ""){

			$string = $_SESSION['rick_auto']['filter_no_retur_revisi'];



			$this->db->where('irr.nomor_retur_revisi ',$_SESSION['rick_auto']['filter_no_retur_revisi']);

		}



		if(isset($_SESSION['rick_auto']['filter_perusahaan_rr']) && $_SESSION['rick_auto']['filter_perusahaan_rr'] != 0){

			$string = $_SESSION['rick_auto']['filter_perusahaan_rr'];



			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['filter_perusahaan_rr']);

		} else {

			$this->db->limit(50);
		}



		if(isset($_SESSION['rick_auto']['filter_member_rr']) && $_SESSION['rick_auto']['filter_member_rr'] != '0'){

			$this->db->where('i.member_id ',$_SESSION['rick_auto']['filter_member_rr']);

		}else{

		}

		if (isset($_SESSION['rick_auto']['filter_perusahaan_rr']) && $_SESSION['rick_auto']['filter_perusahaan_rr']){
			if (isset($_SESSION['rick_auto']['filter_member_rr']) && $_SESSION['rick_auto']['filter_member_rr']){

				$this->db->where('i.member_id ',$_SESSION['rick_auto']['filter_member_rr']);
			} else {
				$this->db->limit(30);
			}
		}





		$this->db->order_by('i.id','desc');

		//$this->db->order_by('i.perusahaan_id','asc');

		$this->db->join('member m','i.member_id = m.id','LEFT');

		$this->db->join('invoice_tanda_terima itt','i.id = itt.invoice_id','LEFT');

		$this->db->join('invoice_retur_revisi irr','i.id = irr.invoice_id','LEFT');

		//$this->db->group_by('irr.invoice_id');

		$this->db->group_by('i.nonota');

		return $this->db->get('invoice i');



	}



	public function getInvoiceByMembers($member,$flag_tt){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		$this->db->order_by('perusahaan_id','asc');

		$this->db->where('member_id',$member);

		$this->db->where('flag_tanda_terima',$flag_tt);

		if(isset($_SESSION['rick_auto']['filter_start_date']) && $_SESSION['rick_auto']['filter_end_date']){

			$this->db->where('dateorder >',$_SESSION['rick_auto']['filter_start_date']);

			$this->db->where('dateorder <',$_SESSION['rick_auto']['filter_end_date']);

		}



		if(isset($_SESSION['rick_auto']['filter_sales'])){

			$string = $_SESSION['rick_auto']['filter_sales'];

			//$array =array_map('strval', explode(',', $string));

			$array = explode(',', $string);



			$this->db->where_in('sales_id ',$array);

		}



		//var_dump();

		//$this->db->where('MONTH(dateorder)',$month);

		return $this->db->get('invoice');



	}



	public function getInvoiceByMembersSave_($member,$flag_tt,$id){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		$this->db->order_by('perusahaan_id','asc');

		$this->db->where('member_id',$member);

		$this->db->where('flag_tanda_terima',$flag_tt);

		if(isset($_SESSION['rick_auto']['filter_start_date']) && $_SESSION['rick_auto']['filter_end_date']){

			$this->db->where('dateorder >',$_SESSION['rick_auto']['filter_start_date']);

			$this->db->where('dateorder <',$_SESSION['rick_auto']['filter_end_date']);

		}



		if(isset($_SESSION['rick_auto']['filter_sales'])){

			$string = $_SESSION['rick_auto']['filter_sales'];

			//$array =array_map('strval', explode(',', $string));

			$array = explode(',', $string);



			$this->db->where_in('sales_id ',$array);

		}

		$this->db->where_in('id',$id);

		//var_dump();

		//$this->db->where('MONTH(dateorder)',$month);

		return $this->db->get('invoice');



	}



	public function getInvoiceByMembersSave($flag_tt,$id){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		$this->db->order_by('perusahaan_id','asc');

		$this->db->where('flag_tanda_terima',$flag_tt);

		if(isset($_SESSION['rick_auto']['filter_start_date']) && $_SESSION['rick_auto']['filter_end_date']){

			$this->db->where('dateorder >',$_SESSION['rick_auto']['filter_start_date']);

			$this->db->where('dateorder <',$_SESSION['rick_auto']['filter_end_date']);

		}



		if(isset($_SESSION['rick_auto']['filter_sales'])){

			$string = $_SESSION['rick_auto']['filter_sales'];

			//$array =array_map('strval', explode(',', $string));

			$array = explode(',', $string);



			$this->db->where_in('sales_id ',$array);

		}

		$arrayID = explode(',', $id);

		$this->db->where_in('id',$arrayID);

		//var_dump();

		//$this->db->where('MONTH(dateorder)',$month);

		//$this->db->group_by('id,member_id');

		return $this->db->get('invoice');



	}



	public function getInvoiceMultipleDesc($flag_tt,$id){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		$this->db->where('flag_tanda_terima',$flag_tt);

		if(isset($_SESSION['rick_auto']['filter_start_date']) && $_SESSION['rick_auto']['filter_end_date']){

			$this->db->where('dateorder >',$_SESSION['rick_auto']['filter_start_date']);

			$this->db->where('dateorder <',$_SESSION['rick_auto']['filter_end_date']);

		}



		if(isset($_SESSION['rick_auto']['filter_sales'])){

			$string = $_SESSION['rick_auto']['filter_sales'];

			//$array =array_map('strval', explode(',', $string));

			$array = explode(',', $string);



			$this->db->where_in('sales_id ',$array);

		}

		$arrayID = explode(',', $id);

		$this->db->where_in('id',$arrayID);

		//var_dump();

		//$this->db->where('MONTH(dateorder)',$month);

		//$this->db->group_by('id,member_id');

		return $this->db->get('invoice');



	}



	public function getTandaTerimaTemp(){

		$this->db->select('*');

		return $this->db->get('invoice_tanda_terima_temp');

	}



	public function getTandaTerimaAsc(){

		$this->db->select('*');

		$this->db->order_by('id','ASC');

		return $this->db->get('invoice_tanda_terima_temp');

	}



	public function getTandaTerimaTempGroupByTT(){

		$this->db->select('*');

		$this->db->group_by('no_tanda_terima');

		return $this->db->get('invoice_tanda_terima_temp');

	}



	public function getTandaTerimaTemps(){

		$this->db->select('*');

		$this->db->group_by('no_tanda_terima_temp');

		return $this->db->get('invoice_tanda_terima_temp');

	}



	public function getTandaTerimaTempsByPerusahaanTTTemp($perusahaan,$noTTTemp){

		$this->db->select('*');

		$this->db->where('perusahaan_id',$perusahaan);

		$this->db->where('no_tanda_terima_temp',$noTTTemp);

		return $this->db->get('invoice_tanda_terima_temp');

	}



	public function getCountTandaTerimaTempsByPerusahaanTTTemp_______($perusahaan){

		$this->db->select('count(*) as jmlData');

		$this->db->where('perusahaan_id',$perusahaan);

		$this->db->group_by('no_tanda_terima');

		$this->db->group_by('perusahaan_id');

		return $this->db->get('invoice_tanda_terima_temps');

	}



	public function getCountTandaTerimaTempsByPerusahaanTTTemp($perusahaan){

		$this->db->select('*');

		$this->db->where('perusahaan_id',$perusahaan);

		$this->db->group_by('no_tanda_terima');

		$this->db->group_by('perusahaan_id');

		return $this->db->get('invoice_tanda_terima_temp');

	}



	public function getCountTandaTerimaTempsByPerusahaanTTTemp_($perusahaan){

		return $this->db->query('SELECT * FROM `invoice_tanda_terima_temp` WHERE `perusahaan_id` = '.$perusahaan.' GROUP BY no_tanda_terima');

	}







	public function getTandaTerimaTempsByNOTTTemp($noTT,$notemps){

		$this->db->select('*');

		$this->db->where('no_tanda_terima',$noTT);

		$this->db->where('no_tanda_terima_temp',$notemps);

		$this->db->group_by('no_tanda_terima_temp');

		return $this->db->get('invoice_tanda_terima_temp');

	}



	public function getAllInvoiceByMembers($member){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		$this->db->order_by('perusahaan_id','asc');

		$this->db->where('member_id',$member);

		return $this->db->get('invoice');



	}



	public function getInvoiceByMemberPerusahaan_($member,$flag,$perusahaan){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		$this->db->where('member_id',$member);

		$this->db->where('pay_status',$flag);

		$this->db->where('perusahaan_id',$perusahaan);

		return $this->db->get('invoice');

	}



	public function getInvoiceByMemberPerusahaan($member,$month,$perusahaan){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		$this->db->where('member_id',$member);

		$this->db->where('perusahaan_id',$perusahaan);

		$this->db->where('MONTH(dateorder)',$month);

		return $this->db->get('invoice');

	}



	public function getInvoiceById($id){

		$this->db->select('i.*, m.name as nama_member, m.email as email_member, m.address as alamat_member, m.address_toko as alamat_member_toko, m.city as kota_member, m.city_id as id_kota, m.phone as phone_member, m.ktp as ktp, s.name as nama_sales, c.area_code as kode_area, m.prov as nama_provinsi');

		$this->db->join('member m','m.id = i.member_id');

		$this->db->join('sales s','s.id = i.sales_id');

		$this->db->join('city c','c.id = m.city_id');

		$this->db->where('i.id',$id);

		return $this->db->get('invoice i');

	}



	public function getInvoiceByIdd($id){

		$this->db->select('i.*');

		$this->db->where('i.id',$id);

		return $this->db->get('invoice i');

	}



	public function getInvoiceDetailByInvoiceId($id){

		$this->db->select('id.*,pc.product_name newname, pc.normal_price netprice, i.total total');

		$this->db->join('product pc','pc.product_code = id.product_code');

		$this->db->join('invoice i','i.id = id.invoice_id');

		$this->db->where('invoice_id',$id);

		return $this->db->get('invoice_detail id');

	}



	public function getInvoiceDetailByInvoiceIdCollyDesc($id){

		$this->db->select('*');

		$this->db->where('invoice_id',$id);

		$this->db->order_by('colly_to','desc');

		return $this->db->get('invoice_detail');

	}



	public function getDetailInvoiceByInvoiceProduk($id,$produk){

		$this->db->select('*');

		$this->db->where('invoice_id',$id);

		$this->db->where('product_code',$produk);

		return $this->db->get('invoice_detail');

	}



	public function getDetailInvoiceByInvoiceProduks($id){

		$this->db->select('id.*,p.id as id_produk');

		$this->db->join('product p','p.product_code = id.product_code');

		$this->db->where('id.invoice_id',$id);

		return $this->db->get('invoice_detail id');

	}



	public function getPaymentInvoiceByMember($member){

		$this->db->select('*');

		$this->db->where('member_id',$member);

		return $this->db->get('invoice_payment');

	}



	public function getTotalPaymentInvoiceByMember($member,$flag){

		$this->db->select('sum(sudah_dibayar) as sudah_dibayar');

		$this->db->where('member_id',$member);

		$this->db->where('flag',$flag);

		$this->db->group_by('member_id');

		return $this->db->get('invoice_payment');

	}



	public function getTotalPaymentInvoiceByMemberAndNott($member,$nott){

		$this->db->select('sum(sudah_dibayar) as sudah_dibayar');

		$this->db->where('member_id',$member);

		$this->db->where('no_tanda_terima',$nott);

		$this->db->group_by('member_id');

		return $this->db->get('invoice_payment');

	}



	public function getTotalPaymentInvoiceByNott_($nott,$id_payment){

		$this->db->select('sum(sudah_dibayar) as total_sudah_dibayar');

		$this->db->where('no_tanda_terima',$nott);

		if(isset($_SESSION['rick_auto']['tanggalfromrrp']) && $_SESSION['rick_auto']['tanggalfromrrp'] != "" && $_SESSION['rick_auto']['tanggalfromrrp'] != 'null' && $_SESSION['rick_auto']['tanggalfromrrp'] != null){

			$string = $_SESSION['rick_auto']['tanggalfromrrp'];

			if($id_payment == 1 || $id_payment == 4){

				$this->db->where('(SELECT DATE(liquid_date) FROM invoice_payment where no_tanda_terima = "'.$nott.'" order by id DESC) <=',$_SESSION['rick_auto']['tanggalfromrrp']);

			}else{

				if($id_payment == ""){

				$this->db->where('(SELECT DATE(payment_date) FROM invoice_payment where no_tanda_terima = "'.$nott.'" order by id DESC) <',$_SESSION['rick_auto']['tanggalfromrrp']);

				}else{

				$this->db->where('(SELECT DATE(payment_date) FROM invoice_payment where no_tanda_terima = "'.$nott.'" order by id DESC) <=',$_SESSION['rick_auto']['tanggalfromrrp']);

				}

			}

		}

		//$this->db->where('payment_id',$id_payment);

		$this->db->group_by('member_id');

		return $this->db->get('invoice_payment');

	}



	public function getTotalPaymentInvoiceByNott($nott){

		// if($id_payment == 1 || $id_payment == 4){

		// 		return $this->db->query("SELECT sum(sudah_dibayar) as total_sudah_dibayar FROM invoice_payment WHERE no_tanda_terima = '".$nott."' AND '(SELECT DATE(liquid_date) FROM invoice_payment where no_tanda_terima = ".$nott." order by id DESC)' <= '".$_SESSION['rick_auto']['tanggalfromrrp']."' AND payment_id = ".$id_payment." GROUP BY `member_id`");

		// }else{

		// 	return $this->db->query("SELECT sum(sudah_dibayar) as total_sudah_dibayar FROM invoice_payment WHERE no_tanda_terima = '".$nott."' AND '(SELECT DATE(payment_date) FROM invoice_payment where no_tanda_terima = ".$nott." order by id DESC)' <= '".$_SESSION['rick_auto']['tanggalfromrrp']."' AND payment_id = ".$id_payment." GROUP BY `member_id`");

		// }



		return $this->db->query("SELECT sum(sudah_dibayar) as total_sudah_dibayar FROM invoice_payment WHERE no_tanda_terima = '".$nott."' AND filter_date <= '".$_SESSION['rick_auto']['tanggalfromrrp']."' GROUP BY `member_id`");

	}





	public function getPaymentInvoiceByMemberStatus($member,$flag){

		$this->db->select('*');

		$this->db->where('member_id',$member);

		$this->db->where('flag',$flag);

		return $this->db->get('invoice_payment');

	}



	public function getPaymentInvoiceByMemberNoTTStatus($member,$nott,$flag){

		$this->db->select('*');

		$this->db->where('member_id',$member);

		$this->db->where('no_tanda_terima',$nott);

		$this->db->where('flag',$flag);

		return $this->db->get('invoice_payment');

	}



	public function getPaymentCustomer($member){

		$this->db->select('ip.*, m.name as nama_member,m.city as kota_member, p.name as jenis_pembayaran');

		$this->db->join('member m','ip.member_id = m.id');

		$this->db->join('payment p','ip.payment_id = p.id');

		$this->db->order_by('id','DESC');

		$this->db->where('ip.member_id',$member);

		return $this->db->get('invoice_payment ip');

	}



	public function getPaymentTandaTerima($tanda_terima){

		$this->db->select('ip.*, DATE(ip.liquid_date) as liquid_date, m.name as nama_member,m.city as kota_member, p.name as jenis_pembayaran, p.id as id_pembayaran');

		$this->db->join('member m','ip.member_id = m.id');

		$this->db->join('payment p','ip.payment_id = p.id');

		$this->db->order_by('id','DESC');

		$this->db->where('ip.no_tanda_terima',$tanda_terima);

		// if(isset($_SESSION['rick_auto']['paymentrrp']) && $_SESSION['rick_auto']['paymentrrp'] != '0'){

		// 	$this->db->where('ip.payment_id ',$_SESSION['rick_auto']['paymentrrp']);

		// }else{

		// }

		if(isset($_SESSION['rick_auto']['tanggalfromrrp']) && $_SESSION['rick_auto']['tanggalfromrrp'] != "" && $_SESSION['rick_auto']['tanggalfromrrp'] != 'null' && $_SESSION['rick_auto']['tanggalfromrrp'] != null){

			$string = $_SESSION['rick_auto']['tanggalfromrrp'];

			if($id_payment == 1 || $id_payment == 4){

				$this->db->where('DATE(ip.liquid_date) <=',$_SESSION['rick_auto']['tanggalfromrrp']);

			}else{

				$this->db->where('DATE(ip.payment_date) <=',$_SESSION['rick_auto']['tanggalfromrrp']);

			}

		}

		return $this->db->get('invoice_payment ip');

	}



	public function getPaymentTandaTerimaL($tanda_terima){

		$this->db->select('ip.*');

		$this->db->where('ip.no_tanda_terima',$tanda_terima);

		$this->db->where('ip.flag',1);

		$this->db->order_by('id','DESC');

		// if(isset($_SESSION['rick_auto']['paymentrrp']) && $_SESSION['rick_auto']['paymentrrp'] != '0'){

		// 	$this->db->where('ip.payment_id ',$_SESSION['rick_auto']['paymentrrp']);

		// }else{

		// }

		if(isset($_SESSION['rick_auto']['tanggalfromrrp']) && $_SESSION['rick_auto']['tanggalfromrrp'] != "" && $_SESSION['rick_auto']['tanggalfromrrp'] != 'null' && $_SESSION['rick_auto']['tanggalfromrrp'] != null){

			$string = $_SESSION['rick_auto']['tanggalfromrrp'];

			if($id_payment == 1 || $id_payment == 4){

				$this->db->where('DATE(ip.liquid_date) <=',$_SESSION['rick_auto']['tanggalfromrrp']);

			}else{

				$this->db->where('DATE(ip.payment_date) <=',$_SESSION['rick_auto']['tanggalfromrrp']);

			}

		}

		return $this->db->get('invoice_payment ip');

	}



public function getPaymentTandaTerimaInv($inv){

		$this->db->select('ip.*, m.name as nama_member,m.city as kota_member, p.name as jenis_pembayaran, ip.payment_id as payment_id, p.name as nama_pembayaran');

		$this->db->join('member m','ip.member_id = m.id');

		$this->db->join('payment p','ip.payment_id = p.id');

		$this->db->join('invoice_tanda_terima itt','ip.no_tanda_terima = itt.no_tanda_terima');

		$this->db->order_by('id','DESC');

		$this->db->where('itt.invoice_id',$inv);

		// if(isset($_SESSION['rick_auto']['paymentrrp']) && $_SESSION['rick_auto']['paymentrrp'] != '0'){

		// 	$this->db->where('ip.payment_id ',$_SESSION['rick_auto']['paymentrrp']);

		// }else{

		// }

		return $this->db->get('invoice_payment ip');

	}



	public function getPaymentTandaTerimaAsc($tanda_terima){

		$this->db->select('ip.*, m.name as nama_member,m.city as kota_member, p.name as jenis_pembayaran, sum(total_pembayaran) as total_pembayarans');

		$this->db->join('member m','ip.member_id = m.id');

		$this->db->join('payment p','ip.payment_id = p.id');

		$this->db->order_by('id','ASC');

		$this->db->where('ip.no_tanda_terima',$tanda_terima);

		return $this->db->get('invoice_payment ip');

	}



	public function getTotalPembayaranByNott($tanda_terima){

			return $this->db->query('select sum(i.total) as total_pembayaran from invoice_tanda_terima itt join invoice i on i.id = itt.invoice_id where itt.no_tanda_terima = "'.$tanda_terima.'"');

	}



	public function getPaymentTandaTerimaa($tanda_terima){

		$this->db->select('ip.*');

		if(isset($_SESSION['rick_auto']['tanggalfromrrp']) && $_SESSION['rick_auto']['tanggalfromrrp'] != "" && $_SESSION['rick_auto']['tanggalfromrrp'] != 'null' && $_SESSION['rick_auto']['tanggalfromrrp'] != null){

			$string = $_SESSION['rick_auto']['tanggalfromrrp'];

			if($id_payment == 1 || $id_payment == 4){

				$this->db->where('DATE(ip.liquid_date) <=',$_SESSION['rick_auto']['tanggalfromrrp']);

			}else{

				$this->db->where('DATE(ip.payment_date) <=',$_SESSION['rick_auto']['tanggalfromrrp']);

			}

		}

		$this->db->where('ip.no_tanda_terima',$tanda_terima);

		$this->db->order_by('id','DESC');

		return $this->db->get('invoice_payment ip');

	}



	public function invoice_surat_jalan(){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		return $this->db->get('invoice_surat_jalan');

	}



	public function getCekFeeSalesByInvoice($invoice){

		$this->db->select('*');

		$this->db->where('invoice_id',$invoice);

		return $this->db->get('transaction_sales_fee');

	}



	public function getInvoiceTandaTerima(){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		return $this->db->get('invoice_tanda_terima');

	}



	public function getInvoiceTandaTerimaByNOTT161020($nott){

		$this->db->select('*');

		$this->db->like('no_tanda_terima',$nott);

		//$this->db->order_by('id','desc');

		$this->db->order_by('no_tanda_terima','desc');

		return $this->db->get('invoice_tanda_terima');

	}



	public function getInvoiceTandaTerimaByNOTT($nott){

		$this->db->select('*');

		$this->db->like('no_tanda_terima',$nott);

		//$this->db->order_by('id','desc');

		$this->db->order_by('no_tanda_terima','desc');

		return $this->db->get('invoice_tanda_terima');

	}







	public function getInvoiceTandaTerimaByNOTT__($nott,$perusahaan){

		$this->db->select('itt.*');

		$this->db->join('invoice i','itt.invoice_id = i.id');

		$this->db->like('itt.no_tanda_terima',$nott);

		$this->db->where('i.perusahaan_id',$perusahaan);

		$this->db->group_by('i.perusahaan_id');

		$this->db->order_by('itt.id','desc');

		return $this->db->get('invoice_tanda_terima itt');

	}



	public function getInvoiceTandaTerimaByMember($member){

		$this->db->select('*');

		$this->db->where('member_id',$member);

		return $this->db->get('invoice_tanda_terima');

	}



	public function getTandaTerimaByMember($member){

		$this->db->select('DISTINCT(no_tanda_terima) as no_tanda_terima, create_date');

		$this->db->where('member_id',$member);

		return $this->db->get('invoice_tanda_terima');

	}



	public function getTandaTerimaByMembers(){

		$this->db->select('itt.*,i.nonota as no_nota, i.perusahaan_name as nama_perusahaan, i.perusahaan_id as perusahaan_id, m.name as nama_member, m.city as kota_member, m.address as alamat_member, m.address_toko as alamat_member_toko');

		if(isset($_SESSION['rick_auto']['filter_invoice_no_tt']) && $_SESSION['rick_auto']['filter_invoice_no_tt'] != ""){

			$string = $_SESSION['rick_auto']['filter_invoice_no_tt'];



			$this->db->like('i.nonota',$_SESSION['rick_auto']['filter_invoice_no_tt']);

		}



		if(isset($_SESSION['rick_auto']['filter_no_tt']) && $_SESSION['rick_auto']['filter_no_tt'] != ""){

			$string = $_SESSION['rick_auto']['filter_no_tt'];



			$this->db->like('itt.no_tanda_terima ',$_SESSION['rick_auto']['filter_no_tt']);

		}







		if(isset($_SESSION['rick_auto']['filter_perusahaan_tt']) && $_SESSION['rick_auto']['filter_perusahaan_tt'] != '0'){

			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['filter_perusahaan_tt']);

			$this->db->limit(30);

		} else {

			$this->db->limit(30);
		}

		$this->db->join('invoice i','i.id = itt.invoice_id');

		$this->db->join('member m','i.member_id = m.id');

		//$this->db->order_by('m.name','asc');

		$this->db->order_by('itt.create_date','desc');

		$this->db->group_by('itt.no_tanda_terima');

		return $this->db->get('invoice_tanda_terima itt');

	}



	public function getTandaTerimaByNoTandaTerima($tandaterima){

		$this->db->select('itt.*,i.nonota as no_nota,i.id as id_nota, i.total as total_nota, i.sales_id as id_sales, i.duedate as duedate, i.min_duedate as min_duedate');

		$this->db->join('invoice i','i.id = itt.invoice_id');

		$this->db->where('itt.no_tanda_terima',$tandaterima);

		return $this->db->get('invoice_tanda_terima itt');

	}



	public function getTandaTerimaByNoTandaTerimaPerusahaan($tandaterima){

		$this->db->select('itt.*,i.nonota as no_nota, i.total as total_nota, p.name as nama_perusahaan, i.perusahaan_id');

		$this->db->join('invoice i','i.id = itt.invoice_id');

		$this->db->join('perusahaan p','p.id = i.perusahaan_id');

		$this->db->where('itt.no_tanda_terima',$tandaterima);

		$this->db->group_by('i.perusahaan_id');

		return $this->db->get('invoice_tanda_terima itt');

	}



	public function getTandaTerimaByNoTandaTerimaPerusahaanNoGroup($tandaterima){

		$this->db->select('itt.*,i.nonota as no_nota, i.total as total_nota, p.name as nama_perusahaan, i.perusahaan_id');

		$this->db->join('invoice i','i.id = itt.invoice_id');

		$this->db->join('perusahaan p','p.id = i.perusahaan_id');

		$this->db->where('itt.no_tanda_terima',$tandaterima);

		//$this->db->group_by('i.perusahaan_id');

		return $this->db->get('invoice_tanda_terima itt');

	}



	public function getTandaTerimaByNoTandaTerimaAndPerusahaan($tandaterima,$perusahaan){

		$this->db->select('itt.*,i.*,i.id as id_invoice');

		$this->db->join('invoice i','i.id = itt.invoice_id');

		$this->db->join('perusahaan p','p.id = i.perusahaan_id');

		$this->db->where('itt.no_tanda_terima',$tandaterima);

		$this->db->where('i.perusahaan_id',$perusahaan);

		$this->db->order_by('i.nonota','asc');

		return $this->db->get('invoice_tanda_terima itt');

	}



	public function getNoTTbyInvoiceId($invoice){

		// $this->db->select('*');
		$this->db->select('it.*, i.nonota as nota, i.dateorder as tglorder');

		$this->db->join('invoice i','i.id = it.invoice_id');

		$this->db->where('invoice_id',$invoice);

		return $this->db->get('invoice_tanda_terima it');

	}



	public function getCekReturRevisi(){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		return $this->db->get('invoice_retur_revisi');

	}



	public function getCekReturRevisiPembayaran(){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		$this->db->group_by('nomor_retur_revisi');

		return $this->db->get('invoice_retur_revisi');

	}



	public function getCekReturRevisiByPerusahaan($perusahaan,$jenis){

		$this->db->select('*');

		$this->db->order_by('id','desc');

		$this->db->like('nomor_retur_revisi',$perusahaan);

		$this->db->like('nomor_retur_revisi',$jenis);

		return $this->db->get('invoice_retur_revisi');

	}

	public function getCekReturRevisiByPerusahaan2($perusahaan,$jenis){

		$this->db->select('nomor_retur_revisi');

		$this->db->order_by('id','desc');

		$this->db->like('nomor_retur_revisi',$perusahaan);

		$this->db->like('nomor_retur_revisi',$jenis);

		$this->db->where('RIGHT(nomor_retur_revisi,2)',date("y"));

		$this->db->group_by('nomor_retur_revisi');

		return $this->db->get('invoice_retur_revisi');

	}





	public function getDetailRevisiReturByInvoiceId($id_nota){

		$this->db->select('irr.*,i.nonota as no_nota');

		$this->db->join('invoice i','i.id = irr.invoice_id');

		$this->db->where('irr.invoice_id',$id_nota);

		return $this->db->get('invoice_retur_revisi irr');

	}



	public function getRevisiReturById($id){

		$this->db->select('irr.*');

		$this->db->where('irr.id',$id);

		return $this->db->get('invoice_retur_revisi irr');

	}



	public function getRevisiReturByNomor($nomor){

		$this->db->select('irr.*, irr.create_date as tgl, m.name as nama_member, m.city as nama_kota, i.nonota as no_nota, id.product_name as nama_produk, id.deskripsi as deskripsi, id.qty as qtty, id.price as harga_satuan, id.ttl_price as harga_total, id.satuan as nama_satuan, i.perusahaan_name as ptname');

		$this->db->join('invoice i','i.id = irr.invoice_id');

		$this->db->join('invoice_detail id','id.id = irr.invoice_detail_id');

		$this->db->join('member m','i.member_id = m.id');

		$this->db->where('irr.nomor_retur_revisi',$nomor);

		return $this->db->get('invoice_retur_revisi irr');

	}



	public function getRevisiReturByNomorPembayaran($nomor){

		$this->db->select('sum(price_change) as totalHargaNew, sum(total_change) as grandTotalNew');

		$this->db->group_by('irr.nomor_retur_revisi');

		$this->db->where('irr.nomor_retur_revisi',$nomor);

		return $this->db->get('invoice_retur_revisi irr');

	}



	public function getRevisiReturs(){

		$this->db->select('irr.*,m.name as nama_member, m.city as nama_kota, i.nonota as no_nota, id.product_name as nama_produk, id.product_code as kode_produk, id.deskripsi as deskripsi, id.qty as qtty, id.price as harga_satuan, id.ttl_price as harga_total, id.satuan as nama_satuan,p.name as nama_perusahaan');

		if(isset($_SESSION['rick_auto']['filter_start_date_rrr']) && $_SESSION['rick_auto']['filter_end_date_rrr']){

			$this->db->where('i.dateorder >',$_SESSION['rick_auto']['filter_start_date_rrr']);

			$this->db->where('i.dateorder <',$_SESSION['rick_auto']['filter_end_date_rrr']);

		}

		if(isset($_SESSION['rick_auto']['filter_perusahaan_rrr']) && $_SESSION['rick_auto']['filter_perusahaan_rrr'] != 'null'){

			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['filter_perusahaan_rrr']);

		}



		if(isset($_SESSION['rick_auto']['filter_transaksi']) && $_SESSION['rick_auto']['filter_transaksi'] != '0' && $_SESSION['rick_auto']['filter_transaksi'] != 'null'){

			$this->db->like('irr.nomor_retur_revisi ',$_SESSION['rick_auto']['filter_transaksi']);

		}



		if(isset($_SESSION['rick_auto']['no_rr']) && $_SESSION['rick_auto']['no_rr'] != ''){

			$this->db->where('irr.nomor_retur_revisi ',$_SESSION['rick_auto']['no_rr']);

		}

		$this->db->join('invoice i','i.id = irr.invoice_id');

		$this->db->join('invoice_detail id','id.id = irr.invoice_detail_id');

		$this->db->join('perusahaan p','i.perusahaan_id = p.id');

		$this->db->join('member m','i.member_id = m.id');

		$this->db->order_by('irr.nomor_retur_revisi','desc');

		return $this->db->get('invoice_retur_revisi irr');

	}



	public function getRevisiRetursTanggal($sort){

		$this->db->select('irr.*,m.name as nama_member, m.city as nama_kota, i.nonota as no_nota, id.product_name as nama_produk, id.product_code as kode_produk, id.deskripsi as deskripsi, id.qty as qtty, id.price as harga_satuan, id.ttl_price as harga_total, id.satuan as nama_satuan,p.name as nama_perusahaan');

		if(isset($_SESSION['rick_auto']['filter_start_date_rrr']) && $_SESSION['rick_auto']['filter_end_date_rrr']){

			$this->db->where('i.dateorder >',$_SESSION['rick_auto']['filter_start_date_rrr']);

			$this->db->where('i.dateorder <',$_SESSION['rick_auto']['filter_end_date_rrr']);

		}

		if(isset($_SESSION['rick_auto']['filter_perusahaan_rrr']) && $_SESSION['rick_auto']['filter_perusahaan_rrr'] != 'null'){

			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['filter_perusahaan_rrr']);

		}



		if(isset($_SESSION['rick_auto']['filter_transaksi']) && $_SESSION['rick_auto']['filter_transaksi'] != '0' && $_SESSION['rick_auto']['filter_transaksi'] != 'null'){

			$this->db->like('irr.nomor_retur_revisi ',$_SESSION['rick_auto']['filter_transaksi']);

		}



		if(isset($_SESSION['rick_auto']['no_rr']) && $_SESSION['rick_auto']['no_rr'] != ''){

			$this->db->where('irr.nomor_retur_revisi ',$_SESSION['rick_auto']['no_rr']);

		}

		$this->db->join('invoice i','i.id = irr.invoice_id');

		$this->db->join('invoice_detail id','id.id = irr.invoice_detail_id');

		$this->db->join('perusahaan p','i.perusahaan_id = p.id');

		$this->db->join('member m','i.member_id = m.id');

		$this->db->order_by('irr.id',''.$sort.'');

		return $this->db->get('invoice_retur_revisi irr');

	}



	public function getInvoiceFilter_(){

		$this->db->select('i.*');



		if(isset($_SESSION['rick_auto']['filter_invoice_no_rr']) && $_SESSION['rick_auto']['filter_invoice_no_rr'] != ""){

			$string = $_SESSION['rick_auto']['filter_invoice_no_rr'];



			$this->db->like('i.nonota ',$_SESSION['rick_auto']['filter_invoice_no_rr']);

		}



		if(isset($_SESSION['rick_auto']['filter_perusahaan_rr']) && $_SESSION['rick_auto']['filter_perusahaan_rr'] != 0){

			$string = $_SESSION['rick_auto']['filter_perusahaan_rr'];



			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['filter_perusahaan_rr']);

		}



		if(isset($_SESSION['rick_auto']['filter_member_rr']) && $_SESSION['rick_auto']['filter_member_rr'] != '0'){

			$this->db->where('i.member_id ',$_SESSION['rick_auto']['filter_member_rr']);

		}else{

		}





		$this->db->order_by('i.id','desc');

		$this->db->join('member m','i.member_id = m.id');

		$this->db->join('invoice_tanda_terima itt','i.id = itt.invoice_id');

		return $this->db->get('invoice i');

	}



	public function getInvoicePembayaranFilterPiutang(){

		$this->db->select('i.*');

		//$this->db->where('i.pay_status ',0);

		if(isset($_SESSION['rick_auto']['tanggalfromrrp']) && $_SESSION['rick_auto']['tanggalfromrrp'] != "" && $_SESSION['rick_auto']['tanggalfromrrp'] != 'null' && $_SESSION['rick_auto']['tanggalfromrrp'] != null){

			$string = $_SESSION['rick_auto']['tanggalfromrrp'];

			$this->db->where('i.invoice_date_tt <=',$_SESSION['rick_auto']['tanggalfromrrp']);

		}



		if(isset($_SESSION['rick_auto']['perusahaanrp']) && $_SESSION['rick_auto']['perusahaanrp'] != 0){

			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['perusahaanrp']);

		}



		if(isset($_SESSION['rick_auto']['memberrp']) && $_SESSION['rick_auto']['memberrp'] != '0' && $_SESSION['rick_auto']['memberrp'] != 'null' && $_SESSION['rick_auto']['memberrp'] != null){

			$this->db->where('i.member_id ',$_SESSION['rick_auto']['memberrp']);

		}else{

		}





		$this->db->order_by('MONTH(i.invoice_date_tt)','asc');

		$this->db->join('member m','i.member_id = m.id');

		$this->db->group_by('MONTH(i.invoice_date_tt)');

		return $this->db->get('invoice i');

	}



	public function getInvoiceLogPembayaranFilterPiutang(){

		$this->db->select('ip.*,i.*');

		//$this->db->where('i.pay_status ',0);

		if(isset($_SESSION['rick_auto']['tanggalfromrrp']) && $_SESSION['rick_auto']['tanggalfromrrp'] != "" && $_SESSION['rick_auto']['tanggalfromrrp'] != 'null' && $_SESSION['rick_auto']['tanggalfromrrp'] != null){

			$string = $_SESSION['rick_auto']['tanggalfromrrp'];

			$this->db->where('i.dateorder <=',$_SESSION['rick_auto']['tanggalfromrrp']);

		}



		if(isset($_SESSION['rick_auto']['perusahaanrp']) && $_SESSION['rick_auto']['perusahaanrp'] != 0){

			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['perusahaanrp']);

		}



		if(isset($_SESSION['rick_auto']['memberrp']) && $_SESSION['rick_auto']['memberrp'] != '0' && $_SESSION['rick_auto']['memberrp'] != 'null' && $_SESSION['rick_auto']['memberrp'] != null){

			$this->db->where('i.member_id ',$_SESSION['rick_auto']['memberrp']);

		}else{

		}




		$this->db->where('ip.flag <>',1);
		$this->db->order_by('MONTH(ip.tanggal)','asc');

		$this->db->join('invoice i','ip.invoice_id = i.id');

		$this->db->join('member m','i.member_id = m.id');

		//

		$this->db->group_by('MONTH(ip.tanggal)');

		//$this->db->order_by('ip.id','DESC');

		return $this->db->get('invoice_piutang ip');

	}



	public function getInvoicePembayaranFilter(){

		$this->db->select('i.*, itt.no_tanda_terima as no_tt');



		if(isset($_SESSION['rick_auto']['tanggalfromrrp']) && $_SESSION['rick_auto']['tanggalfromrrp'] != "" && $_SESSION['rick_auto']['tanggalfromrrp'] != 'null' && $_SESSION['rick_auto']['tanggalfromrrp'] != null){

			$string = $_SESSION['rick_auto']['tanggalfromrrp'];

			$this->db->where('i.dateorder <=',$_SESSION['rick_auto']['tanggalfromrrp']);

		}



		if(isset($_SESSION['rick_auto']['perusahaanrp']) && $_SESSION['rick_auto']['perusahaanrp'] != 0){

			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['perusahaanrp']);

		}



		if(isset($_SESSION['rick_auto']['memberrp']) && $_SESSION['rick_auto']['memberrp'] != '0' && $_SESSION['rick_auto']['memberrp'] != 'null' && $_SESSION['rick_auto']['memberrp'] != null){

			$this->db->where('i.member_id ',$_SESSION['rick_auto']['memberrp']);

		}else{

		}





		$this->db->order_by('MONTH(i.dateorder)','asc');

		$this->db->join('member m','i.member_id = m.id');

		$this->db->join('invoice_tanda_terima itt','i.id = itt.invoice_id');

		$this->db->join('invoice_payment ip','ip.no_tanda_terima = itt.no_tanda_terima');

		$this->db->group_by('MONTH(i.dateorder)');

		return $this->db->get('invoice i');

	}

	public function getInvoiceLogByBulanTahun($bulan,$tahun){

		$this->db->select('i.*, ipi.*, itt.no_tanda_terima as no_tt, p.name as nama_pembayaran, ipa.id as id_invoice_payment, m.city as kota_member');

		$this->db->join('invoice i','i.id = ipi.invoice_id');

		$this->db->join('invoice_tanda_terima itt','i.id = itt.invoice_id','LEFT');

		//$this->db->join('(SELECT invoice_id,no_tanda_terima from invoice_tanda_terima order by id desc) itt','i.id = itt.invoice_id','LEFT');

		//$this->db->join('invoice_payment ip','ip.no_tanda_terima = itt.no_tanda_terima','LEFT');

		$this->db->join('invoice_payment ipa','ipa.no_tanda_terima = itt.no_tanda_terima');

		$this->db->join('payment p','p.id = ipi.payment_id');

		$this->db->join('member m','m.id = i.member_id');

		$this->db->where('MONTH(ipi.tanggal)',$bulan);

		$this->db->where('YEAR(ipi.tanggal)',$tahun);

		$this->db->where('ipi.flag',0);

		// if(isset($_SESSION['rick_auto']['tanggalfromrrp']) && $_SESSION['rick_auto']['tanggalfromrrp'] != "" && $_SESSION['rick_auto']['tanggalfromrrp'] != 'null' && $_SESSION['rick_auto']['tanggalfromrrp'] != null){

		// 	$string = $_SESSION['rick_auto']['tanggalfromrrp'];

		// 	$this->db->where('i.dateorder <=',$_SESSION['rick_auto']['tanggalfromrrp']);

		// }



		if(isset($_SESSION['rick_auto']['perusahaanrp']) && $_SESSION['rick_auto']['perusahaanrp'] != 0){

			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['perusahaanrp']);

		}



		if(isset($_SESSION['rick_auto']['memberrp']) && $_SESSION['rick_auto']['memberrp'] != '0' && $_SESSION['rick_auto']['memberrp'] != 'null' && $_SESSION['rick_auto']['memberrp'] != null){

			$this->db->where('i.member_id ',$_SESSION['rick_auto']['memberrp']);

		}else{

		}



		if(isset($_SESSION['rick_auto']['tanggalfromrrp']) && $_SESSION['rick_auto']['tanggalfromrrp'] != "" && $_SESSION['rick_auto']['tanggalfromrrp'] != 'null' && $_SESSION['rick_auto']['tanggalfromrrp'] != null){

			$string = $_SESSION['rick_auto']['tanggalfromrrp'];

			$this->db->where('i.dateorder <=',$_SESSION['rick_auto']['tanggalfromrrp']);

			$this->db->where('ipi.tanggal <=',$_SESSION['rick_auto']['tanggalfromrrp']);

		}

		//$this->db->group_by('i.nonota');

		$this->db->group_by('ipi.no_tt');

		$this->db->order_by('ipi.id','DESC');

		return $this->db->get('invoice_piutang ipi');

	}



	public function getPaymentPiutangdesc($nott){

		$this->db->select('*');

		$this->db->where('no_tt',$nott);

		$this->db->order_by('id','DESC');

		return $this->db->get('invoice_piutang ipi');

	}



	public function getPaymentPiutangasc($nott){

		$this->db->select('*');

		$this->db->where('no_tt',$nott);

		$this->db->order_by('id','ASC');

		return $this->db->get('invoice_piutang ipi');

	}



	public function getInvoiceByBulanTahun_($bulan,$tahun){

		$this->db->select('i.*, itt.no_tanda_terima as no_tt, p.name as nama_pembayaran, ip.id as id_invoice_payment, m.city as kota_member');

		$this->db->join('invoice_tanda_terima itt','i.id = itt.invoice_id','LEFT');

		//$this->db->join('(SELECT invoice_id,no_tanda_terima from invoice_tanda_terima order by id desc) itt','i.id = itt.invoice_id','LEFT');

		//$this->db->join('invoice_payment ip','ip.no_tanda_terima = itt.no_tanda_terima','LEFT');

		$this->db->join('(SELECT * from invoice_payment order by id DESC) ip','ip.no_tanda_terima = itt.no_tanda_terima','LEFT');

		$this->db->join('payment p','p.id = ip.payment_id');

		$this->db->join('member m','m.id = i.member_id');

		$this->db->where('MONTH(i.dateorder)',$bulan);

		$this->db->where('YEAR(i.dateorder)',$tahun);

		$this->db->where('ip.flag',0);

		$this->db->where('i.pay_status',0);

		$this->db->where('ip.sisa >',0);

		$this->db->where('ip.sisa !=','');

		// if(isset($_SESSION['rick_auto']['tanggalfromrrp']) && $_SESSION['rick_auto']['tanggalfromrrp'] != "" && $_SESSION['rick_auto']['tanggalfromrrp'] != 'null' && $_SESSION['rick_auto']['tanggalfromrrp'] != null){

		// 	$string = $_SESSION['rick_auto']['tanggalfromrrp'];

		// 	$this->db->where('i.dateorder <=',$_SESSION['rick_auto']['tanggalfromrrp']);

		// }



		if(isset($_SESSION['rick_auto']['perusahaanrp']) && $_SESSION['rick_auto']['perusahaanrp'] != 0){

			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['perusahaanrp']);

		}



		if(isset($_SESSION['rick_auto']['memberrp']) && $_SESSION['rick_auto']['memberrp'] != '0' && $_SESSION['rick_auto']['memberrp'] != 'null' && $_SESSION['rick_auto']['memberrp'] != null){

			$this->db->where('i.member_id ',$_SESSION['rick_auto']['memberrp']);

		}else{

		}

		$this->db->group_by('i.nonota');

		//$this->db->order_by('ip.id','DESC');

		return $this->db->get('invoice i');

	}



	public function getInvoiceByBulanTahun__($bulan,$tahun){

		$this->db->select('i.*, itt.no_tanda_terima as no_tt, p.name as nama_pembayaran, ip.id as id_invoice_payment, m.city as kota_member, ip.payment_id as id_payment,DATE(ip.liquid_date) as liquid_date, DATE(ip.payment_date) as payment_date, DATE(i.dateorder) as dateorder');

		$this->db->join('invoice_tanda_terima itt','i.id = itt.invoice_id','LEFT');

		//$this->db->join('(SELECT invoice_id,no_tanda_terima from invoice_tanda_terima order by id desc) itt','i.id = itt.invoice_id','LEFT');

		$this->db->join('invoice_payment ip','ip.no_tanda_terima = itt.no_tanda_terima');

		//$this->db->join('(SELECT * from invoice_payment order by id DESC) ip','ip.no_tanda_terima = itt.no_tanda_terima','LEFT');

		$this->db->join('payment p','p.id = ip.payment_id');

		$this->db->join('member m','m.id = i.member_id');

		$this->db->where('MONTH(i.dateorder)',$bulan);

		$this->db->where('YEAR(i.dateorder)',$tahun);

		//$this->db->where('DATE(ip.payment_date) <',$_SESSION['rick_auto']['tanggalfromrrp']);

		//$this->db->where('ip.flag',0);

		//$this->db->where('i.pay_status',0);

		// $this->db->where('ip.sisa >',0);

		// $this->db->where('ip.sisa !=','');

		// if(isset($_SESSION['rick_auto']['tanggalfromrrp']) && $_SESSION['rick_auto']['tanggalfromrrp'] != "" && $_SESSION['rick_auto']['tanggalfromrrp'] != 'null' && $_SESSION['rick_auto']['tanggalfromrrp'] != null){

		// 	$string = $_SESSION['rick_auto']['tanggalfromrrp'];

		// 	if('ip.payment_id' == 1 || 'ip.payment_id' == 4){

		// 		$this->db->where('DATE(ip.liquid_date) <',$_SESSION['rick_auto']['tanggalfromrrp']);

		// 	}else{

		// 		$this->db->where('DATE(ip.payment_date) <',$_SESSION['rick_auto']['tanggalfromrrp']);

		// 	}

		// }

		if(isset($_SESSION['rick_auto']['tanggalfromrrp']) && $_SESSION['rick_auto']['tanggalfromrrp'] != "" && $_SESSION['rick_auto']['tanggalfromrrp'] != 'null' && $_SESSION['rick_auto']['tanggalfromrrp'] != null){

			$string = $_SESSION['rick_auto']['tanggalfromrrp'];

			//$this->db->where('itt.invoice_date <=',$_SESSION['rick_auto']['tanggalfromrrp']);

			if('ip.payment_id' == 1 || 'ip.payment_id' == 4){

				//$this->db->or_where('DATE(ip.liquid_date) <',$_SESSION['rick_auto']['tanggalfromrrp']);

				//$this->db->where('i.dateorder <=',$_SESSION['rick_auto']['tanggalfromrrp']);

				$this->db->where('i.dateorder <=',$_SESSION['rick_auto']['tanggalfromrrp']);

			}else{

				$this->db->where('i.dateorder <=',$_SESSION['rick_auto']['tanggalfromrrp']);

			}

		}



		if(isset($_SESSION['rick_auto']['perusahaanrp']) && $_SESSION['rick_auto']['perusahaanrp'] != 0){

			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['perusahaanrp']);

		}



		if(isset($_SESSION['rick_auto']['memberrp']) && $_SESSION['rick_auto']['memberrp'] != '0' && $_SESSION['rick_auto']['memberrp'] != 'null' && $_SESSION['rick_auto']['memberrp'] != null){

			$this->db->where('i.member_id ',$_SESSION['rick_auto']['memberrp']);

		}else{

		}

		$this->db->group_by('i.nonota');

		//$this->db->order_by('ip.id','DESC');

		return $this->db->get('invoice i');

	}



	public function getInvoiceByBulanTahun($bulan,$tahun){

		// return $this->db->query("SELECT `i`.*, `itt`.`no_tanda_terima` as `no_tt`, `p`.`name` as `nama_pembayaran`, `ip`.`id` as `id_invoice_payment`, `m`.`city` as `kota_member`, `ip`.`payment_id` as `id_payment`, DATE(ip.liquid_date) as liquid_date, DATE(ip.payment_date) as payment_date, DATE(i.dateorder) as dateorder FROM `invoice` `i` LEFT JOIN `invoice_tanda_terima` `itt` ON `i`.`id` = `itt`.`invoice_id` JOIN `invoice_payment` `ip` ON `ip`.`no_tanda_terima` = `itt`.`no_tanda_terima` JOIN `payment` `p` ON `p`.`id` = `ip`.`payment_id` JOIN `member` `m` ON `m`.`id` = `i`.`member_id` WHERE MONTH(i.dateorder) = '".$bulan."' AND YEAR(i.dateorder) = '".$tahun."' AND (`i`.`dateorder` <= '".$_SESSION['rick_auto']['tanggalfromrrp']."' OR DATE(ip.liquid_date) < '".$_SESSION['rick_auto']['tanggalfromrrp']."') AND `i`.`perusahaan_id` = '".$_SESSION['rick_auto']['perusahaanrp']."' GROUP BY `i`.`nonota`");

		return $this->db->query("SELECT `i`.*, `itt`.`no_tanda_terima` as `no_tt`, `p`.`name` as `nama_pembayaran`, `ip`.`id` as `id_invoice_payment`, `m`.`city` as `kota_member`, `ip`.`payment_id` as `id_payment`, DATE(ip.liquid_date) as liquid_date, DATE(ip.payment_date) as payment_date, DATE(i.dateorder) as dateorder, DATE(i.invoice_date_tt) as invoice_date_tt FROM `invoice` `i` LEFT JOIN `invoice_tanda_terima` `itt` ON `i`.`id` = `itt`.`invoice_id` JOIN `invoice_payment` `ip` ON `ip`.`no_tanda_terima` = `itt`.`no_tanda_terima` JOIN `payment` `p` ON `p`.`id` = `ip`.`payment_id` JOIN `member` `m` ON `m`.`id` = `i`.`member_id` WHERE MONTH(i.invoice_date_tt) = '".$bulan."' AND YEAR(i.invoice_date_tt) = '".$tahun."' AND `i`.`invoice_date_tt` <= '".$_SESSION['rick_auto']['tanggalfromrrp']."' AND `i`.`perusahaan_id` = '".$_SESSION['rick_auto']['perusahaanrp']."' GROUP BY `itt`.`no_tanda_terima`");

	}



	public function getInvoiceOriByBulanTahun($bulan,$tahun){

		$this->db->select('i.*, m.city as kota_member');

		$this->db->join('member m','m.id = i.member_id');

		$this->db->where('MONTH(i.dateorder)',$bulan);

		$this->db->where('YEAR(i.dateorder)',$tahun);

		$this->db->where('i.flag_tanda_terima',0);

		if(isset($_SESSION['rick_auto']['tanggalfromrrp']) && $_SESSION['rick_auto']['tanggalfromrrp'] != "" && $_SESSION['rick_auto']['tanggalfromrrp'] != 'null' && $_SESSION['rick_auto']['tanggalfromrrp'] != null){

			$string = $_SESSION['rick_auto']['tanggalfromrrp'];

			$this->db->where('i.dateorder <=',$_SESSION['rick_auto']['tanggalfromrrp']);

		}



		if(isset($_SESSION['rick_auto']['perusahaanrp']) && $_SESSION['rick_auto']['perusahaanrp'] != 0){

			$this->db->where('i.perusahaan_id ',$_SESSION['rick_auto']['perusahaanrp']);

		}



		if(isset($_SESSION['rick_auto']['memberrp']) && $_SESSION['rick_auto']['memberrp'] != '0' && $_SESSION['rick_auto']['memberrp'] != 'null' && $_SESSION['rick_auto']['memberrp'] != null){

			$this->db->where('i.member_id ',$_SESSION['rick_auto']['memberrp']);

		}else{

		}

		//$this->db->group_by('i.nonota');

		return $this->db->get('invoice i');

	}





	public function getReportMasuk(){

		$this->db->select('rsbmbl.*,pb.notransaction as notransaction, pb.factory_name as factory_name, p.name as nama_perusahaan, g.name as nama_gudang, pr.product_name as nama_produk, s.name as nama_satuan, i.nonota as nonota, i.purchase_no as purchase_no, i.member_name as nama_member');

		if(isset($_SESSION['rick_auto']['perusahaanrb']) && $_SESSION['rick_auto']['perusahaanrb'] != 0){

		$this->db->where('rsbmbl.perusahaan_id',$_SESSION['rick_auto']['perusahaanrb']);

		}

		if(isset($_SESSION['rick_auto']['gudangrb']) && $_SESSION['rick_auto']['gudangrb'] != 0){

			$string = $_SESSION['rick_auto']['gudangrb'];

			$array = explode(',', $string);



			$this->db->where_in('rsbmbl.gudang_id',$array);

		}

		if(isset($_SESSION['rick_auto']['produkrb']) && $_SESSION['rick_auto']['produkrb'] != 0){

		$this->db->where('rsbmbl.product_id',$_SESSION['rick_auto']['produkrb']);

		}

		if(isset($_SESSION['rick_auto']['tanggalfromrrb']) && $_SESSION['rick_auto']['tanggaltorrb']){

			$this->db->where('date(rsbmbl.create_date) >=',$_SESSION['rick_auto']['tanggalfromrrb']);

			$this->db->where('date(rsbmbl.create_date) <=',$_SESSION['rick_auto']['tanggaltorrb']);

		}

		$this->db->join('produk_beli pb','pb.id = rsbmbl.produk_beli_id','LEFT');

		//$this->db->join('produk_beli_detail pbd','pb.id = pbd.produk_beli_id','LEFT');

		$this->db->join('invoice i','i.id = rsbmbl.invoice_id','LEFT');

		$this->db->join('perusahaan p','rsbmbl.perusahaan_id = p.id');

		$this->db->join('gudang g','rsbmbl.gudang_id = g.id');

		$this->db->join('product pr','rsbmbl.product_id = pr.id');

		$this->db->join('satuan s','pr.satuan_id = s.id');

		$this->db->like('rsbmbl.keterangan','Masuk');

		$this->db->order_by('rsbmbl.id','desc');

		return $this->db->get('report_stok_bm_bl rsbmbl');

	}



	public function getReportKeluar(){

		$this->db->select('rsbmbl.*,pb.notransaction as notransaction, pb.factory_name as factory_name, p.name as nama_perusahaan, g.name as nama_gudang, pr.product_name as nama_produk, pr.normal_price as harga_satuan, s.name as nama_satuan, i.nonota as nonota, i.purchase_no as purchase_no, ii.nonota as nonota_nota, m.name as nama_member, m.city as kota, mm.name as nama_member_po, mm.city as kota_po, tpd.price as harga_satuan_po, tp.nonota as no_purchase');

		if(isset($_SESSION['rick_auto']['perusahaanrk']) && $_SESSION['rick_auto']['perusahaanrk'] != 0){

		$this->db->where('rsbmbl.perusahaan_id',$_SESSION['rick_auto']['perusahaanrk']);

		}

		if(isset($_SESSION['rick_auto']['gudangrk']) && $_SESSION['rick_auto']['gudangrk'] != 0){

					$string = $_SESSION['rick_auto']['gudangrk'];

			$array = explode(',', $string);



			$this->db->where_in('rsbmbl.gudang_id',$array);

		//$this->db->where('rsbmbl.gudang_id',$_SESSION['rick_auto']['gudangrk']);

		}

		if(isset($_SESSION['rick_auto']['produkrk']) && $_SESSION['rick_auto']['produkrk'] != 0){

		$this->db->where('rsbmbl.product_id',$_SESSION['rick_auto']['produkrk']);

		}

		if(isset($_SESSION['rick_auto']['tanggalfromrrk']) && $_SESSION['rick_auto']['tanggaltorrk']){

			$this->db->where('date(rsbmbl.create_date) >=',$_SESSION['rick_auto']['tanggalfromrrk']);

			$this->db->where('date(rsbmbl.create_date) <=',$_SESSION['rick_auto']['tanggaltorrk']);

		}

		$this->db->join('produk_beli pb','pb.id = rsbmbl.produk_beli_id','LEFT');

		//$this->db->join('produk_beli_detail pbd','pb.id = pbd.produk_beli_id','LEFT');

		$this->db->join('invoice i','i.id = rsbmbl.invoice_id','LEFT');



		$this->db->join('member m','i.member_id = m.id','LEFT');

		$this->db->join('perusahaan p','rsbmbl.perusahaan_id = p.id','LEFT');

		$this->db->join('transaction_purchase_detail tpd','rsbmbl.purchase_detail_id = tpd.id','LEFT');

		$this->db->join('transaction_purchase tp','tpd.transaction_purchase_id = tp.id','LEFT');

		$this->db->join('invoice ii','ii.purchase_no = tp.nonota','LEFT');

		$this->db->join('member mm','tp.member_id = mm.id','LEFT');

		$this->db->join('gudang g','rsbmbl.gudang_id = g.id','LEFT');

		$this->db->join('product pr','rsbmbl.product_id = pr.id','LEFT');

		$this->db->join('satuan s','pr.satuan_id = s.id','LEFT');

		$this->db->like('rsbmbl.keterangan','Keluar');

		$this->db->order_by('rsbmbl.id','desc');

		return $this->db->get('report_stok_bm_bl rsbmbl');

	}



	public function getCekExpiredStatus($tanggal,$member_id){

		$this->db->select('*');

		$this->db->where('pay_status',0);

		$this->db->where('member_id',$member_id);

		$this->db->where('DATE(duedate) <', $tanggal);

		return $this->db->get('invoice');

	}



	public function getFlagGiroCek(){

		$this->db->select('ip.*,itt.invoice_id as invoice_id');

		$this->db->join('invoice_tanda_terima itt','ip.no_tanda_terima = itt.no_tanda_terima');

		$this->db->where('ip.flag_giro_cek',1);

		$this->db->where('ip.liquid_date <=',date('Y-m-d'));

		return $this->db->get('invoice_payment ip');

	}



	public function getTanggalInvoiceByNoInv($inv){

		$this->db->select('*');

		$this->db->where('invoice_id',$inv);

		return $this->db->get('invoice_tanda_terima');

	}





	public function getDetailInvoiceFilter(){

		$this->db->select('id.*, p.product_name as nama_produk, c.cat_name as nama_kategori, p.product_code as kode_produk, p.part_no as part_no, sum(id.qty_kirim) as qty_kirim');

		$this->db->join('invoice i','i.id = id.invoice_id');

		$this->db->join('product p','p.product_code = id.product_code');

		$this->db->join('category_product c','p.category_id = c.id');



		if(isset($_SESSION['rick_auto']['filter_penjualan_produk']) && $_SESSION['rick_auto']['filter_penjualan_produk'] != ''){

				// $this->db->like('p.product_name',$_SESSION['rick_auto']['filter_penjualan_produk']);

				// $this->db->or_like('p.product_code',$_SESSION['rick_auto']['filter_penjualan_produk']);

				$this->db->where('(p.product_name LIKE "%'.$_SESSION['rick_auto']['filter_penjualan_produk'].'%" OR p.product_code LIKE "%'.$_SESSION['rick_auto']['filter_penjualan_produk'].'%")');

			}

		if(isset($_SESSION['rick_auto']['filter_penjualan_kategori']) && $_SESSION['rick_auto']['filter_penjualan_kategori'] != '0' && $_SESSION['rick_auto']['filter_penjualan_kategori'] != '' && $_SESSION['rick_auto']['filter_penjualan_kategori'] != 'null'){

			$string = $_SESSION['rick_auto']['filter_penjualan_kategori'];

			//$array =array_map('strval', explode(',', $string));

			$filter_penjualan_kategori = explode(',', $string);

			$this->db->where_in('p.category_id',$filter_penjualan_kategori);

		}

		if(isset($_SESSION['rick_auto']['filter_penjualan_tanggalfrom']) && $_SESSION['rick_auto']['filter_penjualan_tanggalto']){

			$this->db->where('DATE(i.dateorder) >=',$_SESSION['rick_auto']['filter_penjualan_tanggalfrom']);

			$this->db->where('DATE(i.dateorder) <=',$_SESSION['rick_auto']['filter_penjualan_tanggaltoo']);

		}



		if(isset($_SESSION['rick_auto']['filter_penjualan_perusahaan']) && $_SESSION['rick_auto']['filter_penjualan_perusahaan'] != '0' && $_SESSION['rick_auto']['filter_penjualan_perusahaan'] != '' && $_SESSION['rick_auto']['filter_penjualan_perusahaan'] != 'null'){

			$this->db->where('i.perusahaan_id',$_SESSION['rick_auto']['filter_penjualan_perusahaan']);

		}



		if(isset($_SESSION['rick_auto']['filter_penjualan_gudang']) && $_SESSION['rick_auto']['filter_penjualan_gudang'] != '0' && $_SESSION['rick_auto']['filter_penjualan_gudang'] != '' && $_SESSION['rick_auto']['filter_penjualan_gudang'] != 'null'){

			$string = $_SESSION['rick_auto']['filter_penjualan_gudang'];

			//$array =array_map('strval', explode(',', $string));

			$filter_penjualan_gudang = explode(',', $string);

			$this->db->where_in('id.gudang_id',$filter_penjualan_gudang);

		}

		$this->db->group_by('id.product_code');

		return $this->db->get('invoice_detail id');

	}





	public function getDetailInvoiceFilterStok(){

		$this->db->select('id.*, p.product_name as nama_produk, c.cat_name as nama_kategori, p.product_code as kode_produk, p.part_no as part_no, i.nonota as no_transaksi, i.dateorder as dateorder, g.name as nama_gudang, i.nonota as nonota, i.purchase_no as purchase_no');

		$this->db->join('invoice i','i.id = id.invoice_id');

		$this->db->join('product p','p.product_code = id.product_code');

		$this->db->join('category_product c','p.category_id = c.id');

		$this->db->join('gudang g','g.id = id.gudang_id');



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

			$this->db->where('DATE(i.dateorder) >=',$_SESSION['rick_auto']['filter_stok_tanggalfrom']);

			$this->db->where('DATE(i.dateorder) <=',$_SESSION['rick_auto']['filter_stok_tanggaltoo']);

		}



		if(isset($_SESSION['rick_auto']['filter_stok_perusahaan']) && $_SESSION['rick_auto']['filter_stok_perusahaan'] != '0' && $_SESSION['rick_auto']['filter_stok_perusahaan'] != '' && $_SESSION['rick_auto']['filter_stok_perusahaan'] != 'null'){

			$this->db->where('i.perusahaan_id',$_SESSION['rick_auto']['filter_stok_perusahaan']);

		}



		if(isset($_SESSION['rick_auto']['filter_stok_gudang']) && $_SESSION['rick_auto']['filter_stok_gudang'] != '0' && $_SESSION['rick_auto']['filter_stok_gudang'] != '' && $_SESSION['rick_auto']['filter_stok_gudang'] != 'null'){

			$string = $_SESSION['rick_auto']['filter_stok_gudang'];

			//$array =array_map('strval', explode(',', $string));

			$filter_penjualan_gudang = explode(',', $string);

			$this->db->where_in('id.gudang_id',$filter_penjualan_gudang);

		}

		//$this->db->group_by('id.product_code');

		return $this->db->get('invoice_detail id');

	}



	public function getSumDetailInvoiceFilterStok(){

		$this->db->select('id.*, p.product_name as nama_produk, c.cat_name as nama_kategori, p.product_code as kode_produk, p.part_no as part_no, i.nonota as no_transaksi, i.dateorder as dateorder, g.name as nama_gudang, i.nonota as nonota, i.purchase_no as purchase_no, sum(id.qty_kirim) as totalQtyKirim');

		$this->db->join('invoice i','i.id = id.invoice_id');

		$this->db->join('product p','p.product_code = id.product_code');

		$this->db->join('category_product c','p.category_id = c.id');

		$this->db->join('gudang g','g.id = id.gudang_id');

		if(isset($_SESSION['rick_auto']['filter_stok_produk']) && $_SESSION['rick_auto']['filter_stok_produk'] != ''){

				// $this->db->like('p.product_name',$_SESSION['rick_auto']['filter_stok_produk']);

				// $this->db->or_like('p.product_code',$_SESSION['rick_auto']['filter_stok_produk']);

				$this->db->where('(p.product_name LIKE "%'.$_SESSION['rick_auto']['filter_stok_produk'].'%" OR p.product_code LIKE "%'.$_SESSION['rick_auto']['filter_stok_produk'].'%")');

			}

		$this->db->where('YEAR(i.dateorder)',date('Y') - 1);

		return $this->db->get('invoice_detail id');

	}



	public function getProdukBeliDetailFilterStok(){

		$this->db->select('pbd.*,p.name as nama_perusahaan, g.name as nama_gudang, pr.product_name as nama_produk, pr.product_code as kode_produk, s.name as satuan, pb.notransaction as nonota,pb.faktur_date as faktur_date');

		$this->db->join('produk_beli pb','pbd.produk_beli_id = pb.id');

		$this->db->join('perusahaan p','pbd.perusahaan_id = p.id');

		$this->db->join('gudang g','pbd.gudang_id = g.id');

		$this->db->join('product pr','pbd.produk_id = pr.id');

		$this->db->join('category_product c','pr.category_id = c.id');

		$this->db->join('satuan s','pr.satuan_id = s.id');

		if(isset($_SESSION['rick_auto']['filter_stok_produk']) && $_SESSION['rick_auto']['filter_stok_produk'] != ''){

				// $this->db->like('p.product_name',$_SESSION['rick_auto']['filter_stok_produk']);

				// $this->db->or_like('p.product_code',$_SESSION['rick_auto']['filter_stok_produk']);

				$this->db->where('(pr.product_name LIKE "%'.$_SESSION['rick_auto']['filter_stok_produk'].'%" OR pr.product_code LIKE "%'.$_SESSION['rick_auto']['filter_stok_produk'].'%")');

			}

		if(isset($_SESSION['rick_auto']['filter_stok_kategori']) && $_SESSION['rick_auto']['filter_stok_kategori'] != '0' && $_SESSION['rick_auto']['filter_stok_kategori'] != '' && $_SESSION['rick_auto']['filter_stok_kategori'] != 'null'){

			$string = $_SESSION['rick_auto']['filter_stok_kategori'];

			//$array =array_map('strval', explode(',', $string));

			$filter_stok_kategori = explode(',', $string);

			$this->db->where_in('pr.category_id',$filter_stok_kategori);

		}

		if(isset($_SESSION['rick_auto']['filter_stok_tanggalfrom']) && $_SESSION['rick_auto']['filter_stok_tanggalto']){

			$this->db->where('DATE(pb.faktur_date) >=',$_SESSION['rick_auto']['filter_stok_tanggalfrom']);

			$this->db->where('DATE(pb.faktur_date) <=',$_SESSION['rick_auto']['filter_stok_tanggaltoo']);

		}



		if(isset($_SESSION['rick_auto']['filter_stok_perusahaan']) && $_SESSION['rick_auto']['filter_stok_perusahaan'] != '0' && $_SESSION['rick_auto']['filter_stok_perusahaan'] != '' && $_SESSION['rick_auto']['filter_stok_perusahaan'] != 'null'){

			$this->db->where('pbd.perusahaan_id',$_SESSION['rick_auto']['filter_stok_perusahaan']);

		}



		if(isset($_SESSION['rick_auto']['filter_stok_gudang']) && $_SESSION['rick_auto']['filter_stok_gudang'] != '0' && $_SESSION['rick_auto']['filter_stok_gudang'] != '' && $_SESSION['rick_auto']['filter_stok_gudang'] != 'null'){

			$string = $_SESSION['rick_auto']['filter_stok_gudang'];

			//$array =array_map('strval', explode(',', $string));

			$filter_penjualan_gudang = explode(',', $string);

			$this->db->where_in('pbd.gudang_id',$filter_penjualan_gudang);

		}

		$this->db->where('pb.status',1);



		return $this->db->get('produk_beli_detail pbd');

	}



	public function getSumProdukBeliDetailFilterStok(){

		$this->db->select('pbd.*,p.name as nama_perusahaan, g.name as nama_gudang, pr.product_name as nama_produk, pr.product_code as kode_produk, s.name as satuan, pb.notransaction as nonota,pb.faktur_date as faktur_date, sum(pbd.qty_receive) as totalQtyReceive');

		$this->db->join('produk_beli pb','pbd.produk_beli_id = pb.id');

		$this->db->join('perusahaan p','pbd.perusahaan_id = p.id');

		$this->db->join('gudang g','pbd.gudang_id = g.id');

		$this->db->join('product pr','pbd.produk_id = pr.id');

		$this->db->join('category_product c','pr.category_id = c.id');

		$this->db->join('satuan s','pr.satuan_id = s.id');

		if(isset($_SESSION['rick_auto']['filter_stok_produk']) && $_SESSION['rick_auto']['filter_stok_produk'] != ''){

				// $this->db->like('p.product_name',$_SESSION['rick_auto']['filter_stok_produk']);

				// $this->db->or_like('p.product_code',$_SESSION['rick_auto']['filter_stok_produk']);

				$this->db->where('(pr.product_name LIKE "%'.$_SESSION['rick_auto']['filter_stok_produk'].'%" OR pr.product_code LIKE "%'.$_SESSION['rick_auto']['filter_stok_produk'].'%")');

			}

		$this->db->where('pb.status',1);

		$this->db->where('YEAR(pb.faktur_date)',date('Y') - 1);

		return $this->db->get('produk_beli_detail pbd');

	}



	public function getReportBmBlFilterStok($arr){

		$this->db->select('pbd.*,p.name as nama_perusahaan, g.name as nama_gudang, pr.product_name as nama_produk, pr.product_code as kode_produk, s.name as satuan');

		$this->db->join('perusahaan p','pbd.perusahaan_id = p.id');

		$this->db->join('gudang g','pbd.gudang_id = g.id');

		$this->db->join('product pr','pbd.product_id = pr.id');

		$this->db->join('category_product c','pr.category_id = c.id');

		$this->db->join('satuan s','pr.satuan_id = s.id');

		if(isset($_SESSION['rick_auto']['filter_stok_produk']) && $_SESSION['rick_auto']['filter_stok_produk'] != ''){

				// $this->db->like('p.product_name',$_SESSION['rick_auto']['filter_stok_produk']);

				// $this->db->or_like('p.product_code',$_SESSION['rick_auto']['filter_stok_produk']);

				$this->db->where('(pr.product_name LIKE "%'.$_SESSION['rick_auto']['filter_stok_produk'].'%" OR pr.product_code LIKE "%'.$_SESSION['rick_auto']['filter_stok_produk'].'%")');

			}

		if(isset($_SESSION['rick_auto']['filter_stok_kategori']) && $_SESSION['rick_auto']['filter_stok_kategori'] != '0' && $_SESSION['rick_auto']['filter_stok_kategori'] != '' && $_SESSION['rick_auto']['filter_stok_kategori'] != 'null'){

			$string = $_SESSION['rick_auto']['filter_stok_kategori'];

			//$array =array_map('strval', explode(',', $string));

			$filter_stok_kategori = explode(',', $string);

			$this->db->where_in('pr.category_id',$filter_stok_kategori);

		}

		if(isset($_SESSION['rick_auto']['filter_stok_tanggalfrom']) && $_SESSION['rick_auto']['filter_stok_tanggalto']){

			$this->db->where('DATE(pbd.create_date) >=',$_SESSION['rick_auto']['filter_stok_tanggalfrom']);

			$this->db->where('DATE(pbd.create_date) <=',$_SESSION['rick_auto']['filter_stok_tanggaltoo']);

		}



		if(isset($_SESSION['rick_auto']['filter_stok_perusahaan']) && $_SESSION['rick_auto']['filter_stok_perusahaan'] != '0' && $_SESSION['rick_auto']['filter_stok_perusahaan'] != '' && $_SESSION['rick_auto']['filter_stok_perusahaan'] != 'null'){

			$this->db->where('pbd.perusahaan_id',$_SESSION['rick_auto']['filter_stok_perusahaan']);

		}



		if(isset($_SESSION['rick_auto']['filter_stok_gudang']) && $_SESSION['rick_auto']['filter_stok_gudang'] != '0' && $_SESSION['rick_auto']['filter_stok_gudang'] != '' && $_SESSION['rick_auto']['filter_stok_gudang'] != 'null'){

			$string = $_SESSION['rick_auto']['filter_stok_gudang'];

			//$array =array_map('strval', explode(',', $string));

			$filter_penjualan_gudang = explode(',', $string);

			$this->db->where_in('pbd.gudang_id',$filter_penjualan_gudang);

		}

		$arr1 = explode(',', $arr);

		$this->db->where_in('note',$arr1);

		return $this->db->get('report_stok_bm_bl pbd');

	}



	public function getSumReportBmBlFilterStok($arr){

		$this->db->select('pbd.*,p.name as nama_perusahaan, g.name as nama_gudang, pr.product_name as nama_produk, pr.product_code as kode_produk, s.name as satuan, sum(pbd.stock_input) as totalStockInput');

		$this->db->join('perusahaan p','pbd.perusahaan_id = p.id');

		$this->db->join('gudang g','pbd.gudang_id = g.id');

		$this->db->join('product pr','pbd.product_id = pr.id');

		$this->db->join('category_product c','pr.category_id = c.id');

		$this->db->join('satuan s','pr.satuan_id = s.id');

		if(isset($_SESSION['rick_auto']['filter_stok_produk']) && $_SESSION['rick_auto']['filter_stok_produk'] != ''){

				// $this->db->like('p.product_name',$_SESSION['rick_auto']['filter_stok_produk']);

				// $this->db->or_like('p.product_code',$_SESSION['rick_auto']['filter_stok_produk']);

				$this->db->where('(pr.product_name LIKE "%'.$_SESSION['rick_auto']['filter_stok_produk'].'%" OR pr.product_code LIKE "%'.$_SESSION['rick_auto']['filter_stok_produk'].'%")');

			}

		$arr1 = explode(',', $arr);

		$this->db->where_in('note',$arr1);

		$this->db->where('YEAR(pbd.create_date)',date('Y') - 1);

		return $this->db->get('report_stok_bm_bl pbd');

	}





}?>
