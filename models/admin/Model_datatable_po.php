<?php



class Model_datatable_po extends CI_Model {



	var $table = "

 (

		SELECT `tp`.*, `m`.`name` as `nama_member`, `s`.`name` as `nama_sales`, `x`.`name` as `nama_expedisi`, `m`.`city` as `kota_member`,  CASE WHEN tp.status = 0 THEN 0
							WHEN tp.status = 1 AND tp.status_gudang = 0 THEN 1
						    WHEN tp.status_gudang = 1 AND tp.status <> 3 THEN 2
						    WHEN tp.status = 3 THEN 3
						    WHEN tp.status = 2 THEN 4
						    ELSE 5 END 'ord' FROM `transaction_purchase` `tp` JOIN `member` `m` ON `m`.`id` = `tp`.`member_id` JOIN `sales` `s` ON `s`.`id` = `tp`.`sales_id` JOIN `expedisi` `x` ON `x`.`id` = `tp`.`expedisi` ORDER BY `tp`.`dateorder` DESC

 ) temp"; 



 //nama tabel dari database

	//var $table = 'product p join category_product c on p.category_id = c.id and join satuan s on p.satuan_id = s.id order by id desc';

	var $column_order = array(null, 'nonota','nama_member','nama_sales','nama_expedisi','kota_member','note','status'); //field yang ada di table user

	var $column_search = array('nonota','nama_member','nama_sales','nama_expedisi','kota_member'); //field yang diizin untuk pencarian 

	// var $order = array('status' => 'asc', 'id'=> 'asc'); // default order 



	public function __construct()

	{

		parent::__construct();

		$this->load->database();

	}



	private function _get_datatables_query()

	{

		if($_SESSION['rick_auto']['flag_user'] == 3){
			$this->table = "(
			
						SELECT `tp`.*, `m`.`name` as `nama_member`, `s`.`name` as `nama_sales`, `x`.`name` as `nama_expedisi`, `m`.`city` as `kota_member`, CASE
						    WHEN tp.status = 1 AND tp.status_gudang = 0 THEN 1
						    WHEN tp.status_gudang = 1 AND tp.status <> 3 THEN 2
						    WHEN tp.status = 3 THEN 3
						    WHEN tp.status = 2 THEN 4
						    ELSE 5
						END 'ord' FROM `transaction_purchase` `tp` JOIN `member` `m` ON `m`.`id` = `tp`.`member_id` JOIN `sales` `s` ON `s`.`id` = `tp`.`sales_id` JOIN `expedisi` `x` ON `x`.`id` = `tp`.`expedisi` ORDER BY ord ASC
			
					) temp";
		}elseif($_SESSION['rick_auto']['flag_user'] == 2){
			$this->table = "(
			
						SELECT `tp`.*, `m`.`name` as `nama_member`, `s`.`name` as `nama_sales`, `x`.`name` as `nama_expedisi`, `m`.`city` as `kota_member`, CASE
						    WHEN tp.status = 1 AND tp.status_gudang = 0 THEN 2
						    WHEN tp.status_gudang = 1 AND tp.status <> 3 THEN 1
						    WHEN tp.status = 3 THEN 3
						    WHEN tp.status = 2 THEN 4
						    ELSE 5
						END 'ord' FROM `transaction_purchase` `tp` JOIN `member` `m` ON `m`.`id` = `tp`.`member_id` JOIN `sales` `s` ON `s`.`id` = `tp`.`sales_id` JOIN `expedisi` `x` ON `x`.`id` = `tp`.`expedisi` ORDER BY ord ASC
			
					) temp";
		}
		 

		$this->db->from($this->table);



		$i = 0;

	

		foreach ($this->column_search as $item) // loop column 

		{

			if($_POST['search']['value']) // if datatable send POST for search

			{

				

				if($i===0) // first loop

				{

					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.

					$this->db->like($item, $_POST['search']['value']);

				}

				else

				{

					$this->db->or_like($item, $_POST['search']['value']);

				}



				if(count($this->column_search) - 1 == $i) //last loop

					$this->db->group_end(); //close bracket

			}

			$i++;

		}

		
		// if($_SESSION['rick_auto']['flag_user'] == 3 || $_SESSION['rick_auto']['flag_user'] == 2){
			$this->db->order_by('ord', 'asc');
		// }


		if(isset($_POST['order'])) // here order processing

		{

			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);

		} 

		else if(isset($this->order))

		{

			$order = $this->order;


			$this->db->order_by(key($order), $order[key($order)]);


		}

		/*if($_SESSION['rick_auto']['flag_user'] == 3){
				$this->db->order_by('ord', 'asc');
			}*/
// $this->db->order_by('ord', 'asc');
		$this->db->order_by('update_date', 'desc');

	}



	function get_datatables()

	{

		$this->_get_datatables_query();

		//if(isset($_SESSION['rick_auto']['po_perusahaan_filter']) && $_SESSION['rick_auto']['po_perusahaan_filter'] != '' && $_SESSION['rick_auto']['po_perusahaan_filter']  != '0') && && (isset($_SESSION['rick_auto']['po_member_filter']) && $_SESSION['rick_auto']['po_member_filter'] != '' && $_SESSION['rick_auto']['po_member_filter'] != '0'))
		if((isset($_SESSION['rick_auto']['po_perusahaan_filter']) && $_SESSION['rick_auto']['po_perusahaan_filter'] != '' && $_SESSION['rick_auto']['po_perusahaan_filter'] != '0') && (isset($_SESSION['rick_auto']['po_member_filter']) && $_SESSION['rick_auto']['po_member_filter'] != '' && $_SESSION['rick_auto']['po_member_filter'] != '0')) {
				//$this->db->where('perusahaan_id',$_SESSION['rick_auto']['po_perusahaan_filter']);
				$array = [
					'perusahaan_id' => $_SESSION['rick_auto']['po_perusahaan_filter'],
					'member_id'		=> $_SESSION['rick_auto']['po_member_filter']
				
				,];
				$this->db->where($array);
		}else if(isset($_SESSION['rick_auto']['po_perusahaan_filter']) && $_SESSION['rick_auto']['po_perusahaan_filter'] != '' && $_SESSION['rick_auto']['po_perusahaan_filter'] != '0'){
			$this->db->where('perusahaan_id',$_SESSION['rick_auto']['po_perusahaan_filter']);
		}else if(isset($_SESSION['rick_auto']['po_member_filter']) && $_SESSION['rick_auto']['po_member_filter'] != '' && $_SESSION['rick_auto']['po_member_filter'] != '0'){
			$this->db->where('member_id',$_SESSION['rick_auto']['po_member_filter']);
		}

		if($_POST['length'] != -1)

		$this->db->limit($_POST['length'], $_POST['start']);

		$query = $this->db->get();

		return $query->result();

	}



	function count_filtered()

	{

		$this->_get_datatables_query();

		$query = $this->db->get();

		return $query->num_rows();

	}



	public function count_all()

	{

		$this->db->from($this->table);

		return $this->db->count_all_results();

	}



}

