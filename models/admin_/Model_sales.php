<?php
class Model_sales extends CI_Model {

	public function getData(){
		$this->db->select('*');
		return $this->db->get('sales');
	}

	public function getSumFee($id){
		$this->db->select('sum(fee) as total_fee');
		$this->db->where('sales_id',$id);
		$this->db->group_by('sales_id');
		return $this->db->get('transaction_sales_fee');
	}

	public function getDetailFeeBySales($sales){
		$this->db->select('tsf.*, i.nonota as nonota, i.total as total');
		$this->db->join('invoice i','i.id = tsf.invoice_id');
		$this->db->where('tsf.sales_id',$sales);
		return $this->db->get('transaction_sales_fee tsf');
	}


}
?>