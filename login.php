<?  session_start();
	session_destroy(); ?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>Dentixa | Administrador de Pacientes</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="assets/global/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="assets/pages/css/login.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- END HEAD -->

    <body class=" login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <!--<a href="#">
                <img src="logo.png" alt="" /> </a>-->
                <h2 class="font-white">Dentixa App</h2>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content" id="contenido">
            <!-- BEGIN LOGIN FORM -->
            <form class="login-form" id="frm_guarda">
				<h3 class="form-title">Iniciar sesión</h3>
				<div class="alert alert-danger display-hide" id="msg_error">

				</div>
				<div class="form-group">
					<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
					<label class="control-label visible-ie8 visible-ie9">Email</label>
					<div class="input-icon">
						<i class="fa fa-user"></i>
						<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="user" id="user" autofocus="1"/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9">Contraseña</label>
					<div class="input-icon">
						<i class="fa fa-lock"></i>
						<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Contraseña" name="pass" id="pass"/>
					</div>
				</div>
				
				<div class="form-actions" style="border-bottom: none;">
					<button type="button" class="btn blue pull-right btn-login" onclick="javascript:login()"> Iniciar Sesión <i class="m-icon-swapright m-icon-white"></i></button>
					<div style="text-align: right;display: none;" id="load">
						<img src="assets/global/img/loading-spinner-grey.gif" />
					</div>
					<br />
				</div>
			</form>
            <!-- END LOGIN FORM -->
        </div>
        <div class="copyright"> 2017 © DENTIS+A APP. Powered by <a href="http://epicmedia.pro" target="_blank" >Epicmedia</a>. </div>
        <!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <!--<script src="assets/pages/scripts/login.min.js" type="text/javascript"></script>-->
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>

</html>

<script>
$(function() {
	$('form').submit(function(e){
		e.preventDefault();	
	});
	
	$('#pass').keyup(function(e) {
	
			if(e.keyCode==13){
				login();
			}
	
	});
});
function login(){
	
	$('#msg_error').hide('Fast');
	
	var user = $('#user').val();
	var pass = $('#pass').val();

	
	App.blockUI(
		{
			target: '#contenido',
            boxed: true,
            message: 'Comprobando...'
        }
	);



        
	$.post('ac/login.php','user='+user+'&pass='+pass,function(data) {
		if(data==1){
			window.location = 'index.php';
		}else{
			$('#pass').focus();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
			App.unblockUI('#contenido');
		}
	});
}
</script>