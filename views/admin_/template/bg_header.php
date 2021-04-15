<?php
$uri2 = $this->uri->segment(2);
$uri3 = $this->uri->segment(3);
echo"
<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='utf-8'>
	<meta http-equiv='X-UA-Compatible' content='IE=edge'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<title>Main Pages</title>

	<!-- Global stylesheets -->
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900' rel='stylesheet' type='text/css'>
	<link href='".base_url("design/admin/assets/css/icons/icomoon/styles.css")."' rel='stylesheet' type='text/css'>
	<link href='".base_url("design/admin/assets/css/bootstrap.css")."' rel='stylesheet' type='text/css'>
	<link href='".base_url("design/admin/assets/css/core.css")."' rel='stylesheet' type='text/css'>
	<link href='".base_url("design/admin/assets/css/components.css")."' rel='stylesheet' type='text/css'>
	<link href='".base_url("design/admin/assets/css/colors.css")."' rel='stylesheet' type='text/css'>
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type='text/javascript' src='".base_url("design/admin/assets/js/plugins/loaders/pace.min.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/core/libraries/jquery.min.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/core/libraries/bootstrap.min.js")."'></script>

	<script type='text/javascript' src='".base_url("design/admin/assets/js/plugins/loaders/blockui.min.js")."'></script>
	<link href='".base_url("design/assets/compo_notif/jquery.ambiance.css")."' rel='stylesheet' type='text/css'>
	<script type='text/javascript' src='".base_url("design/assets/compo_notif/jquery.ambiance.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/plugins/tables/datatables/datatables.min.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/pages/invoice_archive.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/pages/invoice_grid.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/pages/datatables_basic.js")."'></script>
	
	<!-- /core JS files -->
	";
	if($uri2 == "produk" || $uri2 == "master" || $uri3 == "po_add"){
	echo"

	<script type='text/javascript' src='".base_url("design/admin/assets/js/plugins/forms/selects/select2.min.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/plugins/forms/styling/uniform.min.js")."'></script>

	<script type='text/javascript' src='".base_url("design/admin/assets/js/core/app.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/pages/form_layouts.js")."'></script>


	<!-- /theme JS files -->
		";
	}else{
		echo"
	<!-- Theme JS files -->
	<script type='text/javascript' src='".base_url("design/admin/assets/js/pages/form_multiselect.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/plugins/visualization/d3/d3.min.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/plugins/visualization/d3/d3_tooltip.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/plugins/forms/styling/switchery.min.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/plugins/forms/selects/select2.min.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/plugins/forms/styling/uniform.min.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/plugins/forms/selects/bootstrap_multiselect.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/plugins/ui/moment/moment.min.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/plugins/pickers/daterangepicker.js")."'></script>

	<script type='text/javascript' src='".base_url("design/admin/assets/js/core/app.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/pages/dashboard.js")."'></script>
	<!-- /theme JS files -->
	<script type='text/javascript' src='".base_url("design/admin/assets/js/pages/form_layouts.js")."'></script>
	<script type='text/javascript' src='".base_url("design/admin/assets/js/pages/form_select2.js")."'></script>
	";
	}
	echo"

	<script type='text/javascript' src='".base_url("design/admin/assets/js/pages/components_buttons.js")."'></script>
	<style>
            #loading_place {
              display: block;
              position: absolute;
              top: 0;
              left: 0;
              z-index: 100;
              width: 100vw;
              height: 100vh;
              background-color: rgba(192, 192, 192, 0.5);
              background-image: url('https://loading.io/spinners/equalizer/lg.equalizer-bars-loader.gif');
              background-repeat: no-repeat;
              background-position: center;
            }
    </style>
</head>
";?>