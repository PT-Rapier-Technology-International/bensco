<?php

class Model_index extends CI_Model {



	public function getUserLogin($username,$password){

		$this->db->select('*');

		//return $this->db->where('username',$username)->or_where('email',$username)->where('password',$password)->get('users');

		return $this->db->where('username',$username)->where('password',$password)->get('users');

		// return $this->db->where('username',$username)->where('password',$password)->where('token','')->get('users');

	}



	public function getUserBySession(){

		$this->db->select('*');

		return $this->db->where('id',$_SESSION['rick_auto']['id'])->get('users');

	}



	public function getCekPasswordBySession($password){

		$this->db->select('*');

		return $this->db->where('id',$_SESSION['rick_auto']['id'])->where('password',$password)->get('users');

	}



}

?>