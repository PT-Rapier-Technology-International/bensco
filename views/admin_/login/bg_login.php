<?php
//phpinfo();
echo"
<!DOCTYPE html>
<html lang='en'>
<head>
	<title>Login RICK AUTO</title>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
<!--===============================================================================================-->	
	<link rel='icon' type='image/png' href='".base_url("design/login/images/icons/favicon2.ico")."'/>
<!--===============================================================================================-->
	<link rel='stylesheet' type='text/css' href='".base_url("design/login/vendor/bootstrap/css/bootstrap.min.css")."'>
<!--===============================================================================================-->
	<link rel='stylesheet' type='text/css' href='".base_url("design/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css")."'>
<!--===============================================================================================-->
	<link rel='stylesheet' type='text/css' href='".base_url("design/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css")."'>
<!--===============================================================================================-->
	<link rel='stylesheet' type='text/css' href='".base_url("design/login/vendor/animate/animate.css")."'>
<!--===============================================================================================-->	
	<link rel='stylesheet' type='text/css' href='".base_url("design/login/vendor/css-hamburgers/hamburgers.min.css")."'>
<!--===============================================================================================-->
	<link rel='stylesheet' type='text/css' href='".base_url("design/login/vendor/animsition/css/animsition.min.css")."'>
<!--===============================================================================================-->
	<link rel='stylesheet' type='text/css' href='".base_url("design/login/vendor/select2/select2.min.css")."'>
<!--===============================================================================================-->	
	<link rel='stylesheet' type='text/css' href='".base_url("design/login/vendor/daterangepicker/daterangepicker.css")."'>
<!--===============================================================================================-->
	<link rel='stylesheet' type='text/css' href='".base_url("design/login/css/util.css")."'>
	<link rel='stylesheet' type='text/css' href='".base_url("design/login/css/main.css")."'>
<!--===============================================================================================-->
</head>
<body>
	
	<div class='limiter'>
		<div class='container-login100' style='background-image: url(".base_url("design/login/images/machine.jpg").");'>
			<div class='wrap-login100 p-l-110 p-r-110 p-t-62 p-b-33'>
				<form id='formLogin' class='login100-form validate-form flex-sb flex-w'>
					<span class='login100-form-title p-b-53'>
						Admin <br> Login Panel
					</span>
					<div style='border-radius: 10px;background-color: chartreuse;width: inherit;height: 50px; display:none' id='notifSuccess'>
						<h4 style='text-align: -webkit-center;margin-top: 2%;' >Anda berhasil Login</h4>
					</div>
					<div style='border-radius: 10px;background-color: red;width: inherit;height: 50px; display:none' id='notifFail'>
						<h4 style='text-align: -webkit-center;margin-top: 2%;color: cornsilk;' >Gagal Login, cek Form Anda</h4>
					</div>
					<div class='p-t-31 p-b-9'>
						<span class='txt1'>
							Username / Email
						</span>
					</div>
					<div class='wrap-input100 validate-input' data-validate = 'Username is required'>
						<input class='input100' type='text' name='username' id='username' placeholder='masukkan Username / Email Anda' >
						<span class='focus-input100'></span>
					</div>
					
					<div class='p-t-13 p-b-9'>
						<span class='txt1'>
							Password
						</span>
					</div>
					<div class='wrap-input100 validate-input' data-validate = 'Password is required'>
						<input class='input100' type='password' name='password' id='password' placeholder='masukkan Password Anda' onkeypress='javascript:loginn(event)' >
						<span class='focus-input100'></span>
					</div>

					<div class='container-login100-form-btn m-t-17'>
						<button class='login100-form-btn' type='button' onclick='javascript:login()'>
							Sign In
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	

	<div id='dropDownSelect1'></div>
	
<!--===============================================================================================-->
	<script src='".base_url("design/login/vendor/jquery/jquery-3.2.1.min.js")."'></script>
<!--===============================================================================================-->
	<script src='".base_url("design/login/vendor/animsition/js/animsition.min.js")."'></script>
<!--===============================================================================================-->
	<script src='".base_url("design/login/vendor/bootstrap/js/popper.js")."'></script>
	<script src='".base_url("design/login/vendor/bootstrap/js/bootstrap.min.js")."'></script>
<!--===============================================================================================-->
	<script src='".base_url("design/login/vendor/select2/select2.min.js")."'></script>
<!--===============================================================================================-->
	<script src='".base_url("design/login/vendor/daterangepicker/moment.min.js")."'></script>
	<script src='".base_url("design/login/vendor/daterangepicker/daterangepicker.js")."'></script>
<!--===============================================================================================-->
	<script src='".base_url("design/login/vendor/countdowntime/countdowntime.js")."'></script>
<!--===============================================================================================-->
	<script src='".base_url("design/login/js/main.js")."'></script>
	<script>
		var base_url = '".base_url()."admin/';
	</script>
";
if(!empty($scjav)){
    echo "<script src='".site_url($scjav)."' type='text/javascript'></script>";
}
echo"
</body>
</html>
";?>