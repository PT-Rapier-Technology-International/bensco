	<?php
	$uri3 = $this->uri->segment(3);
	$uri2 = $this->uri->segment(2);
	echo"
	<body class='navbar-bottom'>
	<div id='loading_place' style='display:none'></div>
	<!-- Main navbar -->
	<div class='navbar navbar-inverse'>
	<div class='navbar-header'>
	<a class='navbar-brand' href='".base_url("admin/index/dashboard")."'><img src='".base_url("design/admin/assets/images/logo_rick.png")."' alt=''></a>

	<ul class='nav navbar-nav visible-xs-block'>
	<li><a data-toggle='collapse' data-target='#navbar-mobile'><i class='icon-tree5'></i></a></li>
	<li><a class='sidebar-mobile-main-toggle'><i class='icon-paragraph-justify3'></i></a></li>
	</ul>
	</div>

	<div class='navbar-collapse collapse' id='navbar-mobile'>
	<ul class='nav navbar-nav'>
	<li><a class='sidebar-control sidebar-main-toggle hidden-xs'><i class='icon-paragraph-justify3'></i></a></li>

	<li class='dropdown'>
	<!-- <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
	<i class='icon-git-compare'></i>
	<span class='visible-xs-inline-block position-right'>Git updates</span>
	<span class='badge bg-warning-400'>9</span> 
	</a>-->

	<div class='dropdown-menu dropdown-content'>
	<div class='dropdown-content-heading'>
	Git updates
	<ul class='icons-list'>
	<li><a href='#'><i class='icon-sync'></i></a></li>
	</ul>
	</div>

	<ul class='media-list dropdown-content-body width-350'>
	<li class='media'>
	<div class='media-left'>
		<a href='#' class='btn border-primary text-primary btn-flat btn-rounded btn-icon btn-sm'><i class='icon-git-pull-request'></i></a>
	</div>

	<div class='media-body'>
		Drop the IE <a href='#'>specific hacks</a> for temporal inputs
		<div class='media-annotation'>4 minutes ago</div>
	</div>
	</li>

	<li class='media'>
	<div class='media-left'>
		<a href='#' class='btn border-warning text-warning btn-flat btn-rounded btn-icon btn-sm'><i class='icon-git-commit'></i></a>
	</div>

	<div class='media-body'>
		Add full font overrides for popovers and tooltips
		<div class='media-annotation'>36 minutes ago</div>
	</div>
	</li>

	<li class='media'>
	<div class='media-left'>
		<a href='#' class='btn border-info text-info btn-flat btn-rounded btn-icon btn-sm'><i class='icon-git-branch'></i></a>
	</div>

	<div class='media-body'>
		<a href='#'>Chris Arney</a> created a new <span class='text-semibold'>Design</span> branch
		<div class='media-annotation'>2 hours ago</div>
	</div>
	</li>

	<li class='media'>
	<div class='media-left'>
		<a href='#' class='btn border-success text-success btn-flat btn-rounded btn-icon btn-sm'><i class='icon-git-merge'></i></a>
	</div>

	<div class='media-body'>
		<a href='#'>Eugene Kopyov</a> merged <span class='text-semibold'>Master</span> and <span class='text-semibold'>Dev</span> branches
		<div class='media-annotation'>Dec 18, 18:36</div>
	</div>
	</li>

	<li class='media'>
	<div class='media-left'>
		<a href='#' class='btn border-primary text-primary btn-flat btn-rounded btn-icon btn-sm'><i class='icon-git-pull-request'></i></a>
	</div>

	<div class='media-body'>
		Have Carousel ignore keyboard events
		<div class='media-annotation'>Dec 12, 05:46</div>
	</div>
	</li>
	</ul>

	<div class='dropdown-content-footer'>
	<a href='#' data-popup='tooltip' title='All activity'><i class='icon-menu display-block'></i></a>
	</div>
	</div>
	</li>
	</ul>

	<p class='navbar-text'><span class='label bg-success-400'>Online</span></p>

	<ul class='nav navbar-nav navbar-right'>

	<li class='dropdown'>
	<!-- <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
	<i class='icon-bubbles4'></i>
	<span class='visible-xs-inline-block position-right'>Messages</span>
	<span class='badge bg-warning-400'>2</span>
	</a> -->

	<div class='dropdown-menu dropdown-content width-350'>
	<div class='dropdown-content-heading'>
	Messages
	<ul class='icons-list'>
	<li><a href='#'><i class='icon-compose'></i></a></li>
	</ul>
	</div>

	<ul class='media-list dropdown-content-body'>
	<li class='media'>
	<div class='media-left'>
		<img src='assets/images/placeholder.jpg' class='img-circle img-sm' alt=''>
		<span class='badge bg-danger-400 media-badge'>5</span>
	</div>

	<div class='media-body'>
		<a href='#' class='media-heading'>
			<span class='text-semibold'>James Alexander</span>
			<span class='media-annotation pull-right'>04:58</span>
		</a>

		<span class='text-muted'>who knows, maybe that would be the best thing for me...</span>
	</div>
	</li>

	<li class='media'>
	<div class='media-left'>
		<img src='assets/images/placeholder.jpg' class='img-circle img-sm' alt=''>
		<span class='badge bg-danger-400 media-badge'>4</span>
	</div>

	<div class='media-body'>
		<a href='#' class='media-heading'>
			<span class='text-semibold'>Margo Baker</span>
			<span class='media-annotation pull-right'>12:16</span>
		</a>

		<span class='text-muted'>That was something he was unable to do because...</span>
	</div>
	</li>

	<li class='media'>
	<div class='media-left'><img src='assets/images/placeholder.jpg' class='img-circle img-sm' alt=''></div>
	<div class='media-body'>
		<a href='#' class='media-heading'>
			<span class='text-semibold'>Jeremy Victorino</span>
			<span class='media-annotation pull-right'>22:48</span>
		</a>

		<span class='text-muted'>But that would be extremely strained and suspicious...</span>
	</div>
	</li>

	<li class='media'>
	<div class='media-left'><img src='assets/images/placeholder.jpg' class='img-circle img-sm' alt=''></div>
	<div class='media-body'>
		<a href='#' class='media-heading'>
			<span class='text-semibold'>Beatrix Diaz</span>
			<span class='media-annotation pull-right'>Tue</span>
		</a>

		<span class='text-muted'>What a strenuous career it is that I've chosen...</span>
	</div>
	</li>

	<li class='media'>
	<div class='media-left'><img src='assets/images/placeholder.jpg' class='img-circle img-sm' alt=''></div>
	<div class='media-body'>
		<a href='#' class='media-heading'>
			<span class='text-semibold'>Richard Vango</span>
			<span class='media-annotation pull-right'>Mon</span>
		</a>
		
		<span class='text-muted'>Other travelling salesmen live a life of luxury...</span>
	</div>
	</li>
	</ul>

	<div class='dropdown-content-footer'>
	<a href='#' data-popup='tooltip' title='All messages'><i class='icon-menu display-block'></i></a>
	</div>
	</div>
	</li>

	<li class='dropdown dropdown-user'>
	<a class='dropdown-toggle' data-toggle='dropdown'>
	<img src='".base_url("design/admin/assets/images/placeholder.jpg")."' alt=''>
	<span>".$_SESSION['rick_auto']['fullname']."</span>
	<i class='caret'></i>
	</a>

	<ul class='dropdown-menu dropdown-menu-right'>
	<li><a href='".base_url("admin/index/view_profile")."'><i class='icon-user-plus'></i> My profile</a></li>
	<li class='divider'></li>
	<li><a href='".base_url("admin/index/edit_profile")."'><i class='icon-cog5'></i> Account settings</a></li>
	<li><a href='".base_url("admin/index/logout")."'><i class='icon-switch2'></i> Logout</a></li>
	</ul>
	</li>
	</ul>
	</div>
	</div>
	<!-- /main navbar -->


	<!-- Page header -->
	<div class='page-header'>
	<div class='breadcrumb-line'>
	<ul class='breadcrumb'>
	<li><a href='".base_url()."'><i class='icon-home2 position-left'></i> Home</a></li>
	<li class='active'>Dashboard</li>
	</ul>

	<ul class='breadcrumb-elements'>
	<!-- <li><a href='#'><i class='icon-comment-discussion position-left'></i> Support</a></li>
	<li class='dropdown'>
	<a href='#' class='dropdown-toggle' data-toggle='dropdown'>
	<i class='icon-gear position-left'></i>
	Settings
	<span class='caret'></span>-->
	</a>

	<ul class='dropdown-menu dropdown-menu-right'>
	<li><a href='#'><i class='icon-user-lock'></i> Account security</a></li>
	<li><a href='#'><i class='icon-statistics'></i> Analytics</a></li>
	<li><a href='#'><i class='icon-accessibility'></i> Accessibility</a></li>
	<li class='divider'></li>
	<li><a href='#'><i class='icon-gear'></i> All settings</a></li>
	</ul>
	</li>
	</ul>
	</div>

	<div class='page-header-content'>
	<div class='page-title'>
	<h4><i class='icon-arrow-left52 position-left'></i> <span class='text-semibold'>Home</span> - ".ucfirst($uri2)." <small>Hello, ".$_SESSION['rick_auto']['fullname']."</small></h4>
	</div>

	<div class='heading-elements'>
	<div class='heading-btn-group'>
	<!-- <a href='#' class='btn btn-link btn-float has-text'><i class='icon-bars-alt text-primary'></i><span>Statistics</span></a> -->
	<a href='".base_url("admin/invoice/invoice_detail")."' class='btn btn-link btn-float has-text'><i class='icon-calculator text-primary'></i> <span>Invoices</span></a>
	<!-- <a href='#' class='btn btn-link btn-float has-text'><i class='icon-calendar5 text-primary'></i> <span>Schedule</span></a> -->
	</div>
	</div>
	</div>
	</div>
	<!-- /page header -->


	<!-- Page container -->
	<div class='page-container'>

	<!-- Page content -->
	<div class='page-content'>

	<!-- Main sidebar -->
	<div class='sidebar sidebar-main sidebar-default'>
	<div class='sidebar-content'>

	<!-- Main navigation -->
	<div class='sidebar-category sidebar-category-visible'>
	<div class='category-title h6'>
	<span>Main navigation</span>
	<ul class='icons-list'>
	<li><a href='#' data-action='collapse'></a></li>
	</ul>
	</div>

	<div class='category-content sidebar-user'>
	<div class='media'>
	<a href='#' class='media-left'><img src='".base_url("design/admin/assets/images/placeholder.jpg")."' class='img-circle img-sm' alt=''></a>
	<div class='media-body'>
		<span class='media-heading text-semibold'>".$_SESSION['rick_auto']['fullname']."</span>
		<div class='text-size-mini text-muted'>
			<i class='icon-pin text-size-small'></i> &nbsp;Tangerang, Banten
		</div>
	</div>

	<div class='media-right media-middle'>
		<ul class='icons-list'>
			<li>
				<a href='#'><i class='icon-cog3'></i></a>
			</li>
		</ul>
	</div>
	</div>
	</div>

	<div class='category-content no-padding'>
	<ul class='navigation navigation-main navigation-accordion'>

	<!-- Main -->
	<li class='navigation-header'><span>Main</span> <i class='icon-menu' title='Main pages'></i></li>
	";if($_SESSION['rick_auto']['flag_user'] == 1){
		
	echo"
	<li class='active'><a href='".base_url()."admin/purchase/index_admin'><i class='icon-home4'></i> <span>Dashboard</span></a></li>";
	}else{
		echo"
		
	<li class='active'><a href='".base_url()."admin/index/dashboard'><i class='icon-home4'></i> <span>Dashboard</span></a></li>";
	}
	if($_SESSION['rick_auto']['flag_user'] == 1){
		echo"
	<li>
		<a href='#'><i class='icon-task'></i> <span>Produk</span></a>
		<ul>
			<li><a href='".base_url("admin/produk/index")."'>List Produk</a></li>
			<li><a href='".base_url("admin/produk/add")."'>Tambah Produk</a></li>
		</ul>
	</li>
	<li>
		<a href='#'><i class='icon-stack'></i> <span>Master</span></a>
		<ul>
			<li><a href='".base_url("admin/master/perusahaan")."'>Master Perusahaan</a></li>
			<li><a href='".base_url("admin/master/kategori")."'>Master Kategori</a></li>
			<li><a href='".base_url("admin/master/user/0/".base64_encode("User")."")."'>Master User</a></li>
			<li><a href='".base_url("admin/master/user/0/".base64_encode("User")."/".base64_encode("Sales")."")."'>Master Sales</a></li>
			<li><a href='".base_url("admin/master/user/1/".base64_encode("Member")."")."'>Master Member</a></li>
			<li><a href='".base_url("admin/master/satuan")."'>Master Satuan</a></li>
			<li><a href='".base_url("admin/master/harga")."'>Master Jenis Harga</a></li>
			<li><a href='".base_url("admin/master/expedisi")."'>Master Expedisi</a></li>
			<li><a href='".base_url("admin/master/gudang")."'>Master Gudang</a></li>
		</ul>
	</li>
	<li>
		<a href='#'><i class='icon-database'></i> <span>Stok</span></a>
		<ul>
			<li><a href='".base_url("admin/produk/index_stok")."'>Informasi Stok</a></li>
			<li><a href='".base_url("admin/produk/manage_stok")."'>Adjusment Stok</a></li>
			<li><a href='".base_url("admin/produk/log_adjusment")."'>History Adjusment Stok</a></li>
			<li><a href='".base_url("admin/produk/index_stok/mutasi")."'>Mutasi Stok</a></li>
			<li><a href='".base_url("admin/produk/log_mutasi")."'>History Mutasi Stok</a></li>
		</ul>
	</li>
	<!-- <li><a href='".base_url("admin/produk/manage_stok")."'><i class='icon-file-plus2'></i>Stok Produk</a></li> -->
	<li><a href='".base_url("admin/sales/fee")."'><i class=' icon-cash3'></i>Fee Sales</a></li>
	<!-- <li><a href='".base_url("admin/invoice/invoice_detail/TandaTerima")."'><i class='icon-picassa'></i>Tanda Terima</a></li> -->
	<li>
		<a href='#'><i class='icon-picassa'></i> <span>Tanda Terima</span></a>
		<ul>
			<li><a href='".base_url("admin/invoice/invoice_detail/TandaTerima")."'>Proses Tanda Terima</a></li>
			<li><a href='".base_url("admin/invoice/invoice_tanda_terima/4B39E9C96FE25F5E7B61F4186913425FDA90A8DD9D0D8CED21A08C3EC325AA109F08E32A8767B1C06F7B9CB0D35A8241240B59DE411F3AEC78D08345F532B5B9")."'>Invoice Tanda Terima</a></li>
		</ul>
	</li>
	<li><a href='".base_url("admin/invoice/invoice_detail")."'><i class=' icon-calculator'></i>Invoices</a></li>
	<li><a href='".base_url("admin/invoice/retur_revisi_view")."'><i class=' icon-flip-vertical3'></i>Proses Retur Revisi</a></li>
	<!-- <li><a href='".base_url("admin/invoice/index/1")."'><i class=' icon-calculator2'></i>Input Pembayaran</a></li> -->
	<li><a href='".base_url("admin/invoice/invoice_tanda_terima")."'><i class=' icon-calculator2'></i>Input Pembayaran</a></li>
	";}
	if($_SESSION['rick_auto']['flag_user'] == 5){
	echo"
	<li><a href='".base_url("admin/invoice/invoice_tanda_terima")."'><i class=' icon-calculator2'></i>Input Pembayaran</a></li>
	
	";}
	$countPO = $this->model_purchase->getPurchaseByStatusRead(0);
	$total = $countPO->num_rows();
	if($total == 0){
		$iconnn = "";
	}else{
		$iconnn = "<span class='badge badge-danger'>".$total."</span>";
	}
	if($_SESSION['rick_auto']['flag_user'] != 5){
	echo"
	<!-- <li><a href='".base_url("admin/purchase/index")."'><i class='icon-list-unordered'></i> Purchase Order ".$iconnn."</a></li> -->
	<li>
		<a href='#'><i class='icon-list-unordered'></i> <span>Purchase Order ".$iconnn."</span></a>
		<ul>
			<li><a href='".base_url("admin/purchase/po_add")."'>Tambah Purchase Order</a></li>
			<li><a href='".base_url("admin/purchase/index")."'>Purchase Order List ".$iconnn."</a></li>
		</ul>
	</li>
	";
	}
	$countRPO = $this->model_purchase->getReqPurchase();
	$totalP = $countRPO->num_rows();
	if($totalP == 0){
		$iconReq = "";
	}else{
		$iconReq = "<span class='badge badge-danger'>".$totalP."</span>";
	}

	$countRPOC = $this->model_purchase->getReqPurchaseCancelled();
	$totalPC = $countRPOC->num_rows();
	if($totalPC == 0){
		$iconReqC = "";
	}else{
		$iconReqC = "<span class='badge badge-danger'>".$totalPC."</span>";
	}
	if($_SESSION['rick_auto']['flag_user'] == 4 || $_SESSION['rick_auto']['flag_user'] == 1){
	echo"
	<li><a href='".base_url("admin/purchase/req_index")."'><i class='icon-clipboard'></i> Request Purchase Order ".$iconReq."</a></li>
	<li><a href='".base_url("admin/purchase/req_index/82C1C4B588039470E3160B9303AE4F7231277D6D95FD0829F6A569CAA45E2C947C613CFA8467B9086D0A84AEB702751508CAF622526195076184F40B824B9633")."'><i class='icon-clipboard'></i> Purchase Order Batal ".$iconReqC."</a></li>
	";}

	if($_SESSION['rick_auto']['flag_user'] != 5){echo"
	<!-- <li><a href='".base_url("admin/purchase/monthly_report")."'><i class='icon-printer4'></i> Report PO Bulanan</a></li> -->
	<li><a href='".base_url("admin/produk/order")."'><i class='  icon-cart-add2'></i>Order Produk</a></li>
	<li>
		<a href='#'><i class='icon-printer4'></i> <span>Report</span></a>
		<ul>
			<li><a href='".base_url("admin/purchase/monthly_report")."'>Report PO</a></li>
			<li><a href='".base_url("admin/invoice/report_retur_revisi")."'>Report Retur Revisi</a></li>
		</ul>
	</li>";}echo"
	<!-- <li>
		<a href='#'><i class='icon-printer2'></i> <span>Cetak Surat</span></a>
		<ul>
			<li><a href='".base_url("admin/produk/index")."'>List Produk</a></li>
			<li><a href='".base_url("admin/produk/add")."'>Tambah Produk</a></li>
		</ul>
	</li> -->
	<!-- /page kits -->

	</ul>
	</div>
	</div>
	<!-- /main navigation -->

	</div>
	</div>
	<!-- /main sidebar -->
	";?>