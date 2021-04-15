<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
/*
* CONTROLLER INDEX WEBSITE
* This controler for screen index
*
* Log Activity : ~ Create your log if you change this controller ~
* 1. Create 15 Mei 2019 By Devanda Andrevianto, Create All Function, Create controller
*/
class Index extends CI_Controller {
	//var $data = array('scjav'=>'assets/jController/admin/CtrlIndex.js');
    function __construct(){
        parent::__construct();
        // $this->lang->load('admin', '');
        
    }
	
	// fungsi untuk mengecek apakah user sudah login
	 public function index(){
            $url= base_url().'admin/index/signin';
            redirect($url);
			return;

    }

}
?>