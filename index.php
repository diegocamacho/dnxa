<? include('includes/session_ui.php'); 
include('includes/db.php'); 
include('includes/funciones.php');
$menu = isset($_GET['Modulo']) ? $_GET['Modulo']: NULL;
$clinicas2 = array();
if($s_tipo==3):
	$consulta="AND id_clinica=$s_id_clinica ";
	//Hay que poner algo para identificar el caledario de todos
	//Clinicas especiales
	$sql="SELECT * FROM clinicas WHERE todos=1 AND tipo=1 ORDER BY clinica ASC";
	$q=mysql_query($sql);
	while($datos=mysql_fetch_object($q)):
		$clinicas2[] = $datos;
	endwhile;
endif;
//Clínicas
$sql="SELECT * FROM clinicas WHERE tipo=1 $consulta ORDER BY clinica ASC";
$q=mysql_query($sql);
$clinicas = array();
while($datos=mysql_fetch_object($q)):
	$clinicas[] = $datos;
endwhile;


$valida_clinicas=count($clinicas);
$valida_especial=count($clinicas2);

$sql="SELECT * FROM books_metodo_pago WHERE activo=1 ORDER BY metodo_pago ASC";
$q=mysql_query($sql);
while($datos=mysql_fetch_object($q)):
	$metodos[] = $datos;
endwhile;
?>
<!DOCTYPE html>
<!-- 
Build with Twitter Bootstrap 3.3.7
Version: 4.7.1
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="es">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>Dentixa CRM</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #3 for dashboard & statistics" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/layouts/layout3/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        
        <link href="js/dropzone.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="favicon.ico" /> </head>
        <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
		<style>
			.oculto{
				display: none;
			}
		</style>
        
    <!-- END HEAD -->
    <body class="page-container-bg-solid">
        <div class="page-wrapper">
            <div class="page-wrapper-row">
                <div class="page-wrapper-top">
                    <!-- BEGIN HEADER -->
                    <div class="page-header">
                        <!-- BEGIN HEADER TOP -->
                        <div class="page-header-top">
                            <div class="container">
                                <!-- BEGIN LOGO -->
                                <div class="page-logo">
                                    <a href="index.php">
                                        <img src="assets/dentista_logo.png" alt="logo" class="logo-default" height="55" style="margin-top: 10px;">
                                    </a>
                                </div>
                                <!-- END LOGO -->
                                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                                <a href="javascript:;" class="menu-toggler"></a>
                                <!-- END RESPONSIVE MENU TOGGLER -->
                                <!-- BEGIN TOP NAVIGATION MENU -->
                                <div class="top-menu">
                                    <ul class="nav navbar-nav pull-right  hidden-xs">
                                        
                                        <!-- BEGIN USER LOGIN DROPDOWN -->
                                        <li class="dropdown dropdown-user dropdown-dark">
                                            <a href="javascript:;" class="dropdown-toggle" data-close-others="true">
                                                <img alt="" class="img-circle" src="<? if($s_display){ echo "files/thumb_".$s_display; }else{ echo "files/bot_icon.png"; }?>">
                                                <span class="username username-hide-mobile"><?=$s_nombre?><br><small><?= dameClinica($s_id_clinica); ?></small></span>
                                            </a>
                                        </li>
                                        <!-- END USER LOGIN DROPDOWN -->
                                        <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                                        <li class="dropdown dropdown-extended quick-sidebar-toggler">
                                            <span class="sr-only">Salir</span>
                                            <i class="icon-logout"></i>
                                        </li>
                                        <!-- END QUICK SIDEBAR TOGGLER -->
                                    </ul>
                                </div>
                                <!-- END TOP NAVIGATION MENU -->
                            </div>
                        </div>
                        <!-- END HEADER TOP -->
                        <!-- BEGIN HEADER MENU -->
                        <div class="page-header-menu">
                            <div class="container">
                                <!-- BEGIN MEGA MENU -->
                                <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                                <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
                                <div class="hor-menu hor-menu-light">
                                    <ul class="nav navbar-nav">
	                                    <li <? if(!$menu){ ?>class="active"<?}?>><a href="index.php">Escritorio</a></li>
	                                    
	                                    <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
                                            <a href="javascript:;"> Personas
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu pull-left">
	                                            <li aria-haspopup="true">
                                                    <a href="?Modulo=Pacientes" class="nav-link">Pacientes</a>
                                                </li>
                                                <li aria-haspopup="true">
                                                    <a href="?Modulo=Prospectos" class="nav-link">Prospectos</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <!--
	                                    <? //if($s_tipo!=3): ?>
	                                    <li <? if($menu=="Prospectos"){ ?>class="active"<?}?>><a href="?Modulo=Prospectos"> Prospectos</a></li>
	                                    <? //endif; ?>
	                                    -->
                                        <li <? if($menu=="Citas"){ ?>class="active"<?}?>><a href="?Modulo=Citas"> Citas</a></li>
                                        <li <? if($menu=="Eventos"){ ?>class="active"<?}?>><a href="?Modulo=Eventos"> Eventos</a></li>
                                        <!-- -->
                                        <? $agenda_activa = ($menu=="Agenda") ? 'active' : ''; ?>
										<li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown <?=$agenda_activa?>">
                                            <a href="<?if($s_tipo!=3):?>?Modulo=Agenda<?endif;?>"> Agenda
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu pull-left">
	                                            <? foreach($clinicas as $clinica): ?>
                                                <li aria-haspopup="true">
                                                    <a href="?Modulo=Agenda&id_clinica=<?=$clinica->id_clinica?>" class="nav-link"><?=$clinica->clinica?></a>
                                                </li>
                                                <? endforeach; ?>
                                                <? if($valida_especial>=1): ?>
                                                	<li class="divider"> </li>
                                                	<? foreach($clinicas2 as $clinica): ?>
                                                	<li aria-haspopup="true">
                                                	    <a href="?Modulo=Agenda&id_clinica=<?=$clinica->id_clinica?>" class="nav-link"><?=$clinica->clinica?></a>
                                                	</li>
                                                	<? endforeach; ?>
                                                <? endif; ?>
                                            </ul>
                                        </li>
                                        <? if($s_tipo==3): ?>
                                        <li <? if($menu=="Gastos"){ ?>class="active"<?}?>><a href="?Modulo=Gastos"> Operaciones</a></li>
                                        <? endif; ?>
                                        <!--
                                        <li <? if($menu=="Pacientes"){ ?>class="active"<?}?>><a href="?Modulo=Pacientes"> Pacientes</a></li>
                                        -->
                                        <li <? if($s_tipo==2){ ?>><a href="../pacientes/pacientes.php"> Pacientes Móvil </a></li><?}?>
                                        
                                        <? if($s_tipo==1): ?>
                                        <? $facturacion_activa = ($menu=="Facturacion") ? 'active' : ''; ?>
                                        <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown <?=$facturacion_activa?>">
                                            <a href="javascript:;"> Facturación
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu pull-left">
	                                            <li aria-haspopup="true">
                                                    <a href="?Modulo=NuevaFactura" class="nav-link">Nueva Factura</a>
                                                </li>
                                                <li aria-haspopup="true">
                                                    <a href="?Modulo=Facturacion" class="nav-link">Facturas</a>
                                                </li>
                                                <li aria-haspopup="true">
                                                    <a href="?Modulo=Facturacion&Tipo=2" class="nav-link">Facturas Canceladas</a>
                                                </li>
                                                <li aria-haspopup="true">
                                                    <a href="?Modulo=PreFacturas" class="nav-link">Pre Facturas</a>
                                                </li>
                                                 <li aria-haspopup="true">
                                                    <a href="?Modulo=ClientesFactura" class="nav-link">Clientes Facturación</a>
                                                </li>
                                            </ul>
                                        </li>
                                        
                                        <? $operaciones_activa = ($menu=="Operaciones") ? 'active' : ''; ?>
                                        <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown <?=$operaciones_activa?>">
                                            <a href="javascript:;"> Finanzas
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu pull-left">
	                                            <li aria-haspopup="true">
                                                    <a href="?Modulo=Operaciones" class="nav-link">Operaciones</a>
                                                </li>
                                                <li aria-haspopup="true">
                                                    <a href="?Modulo=OperacionesEspecialistas" class="nav-link">Especialistas & Labs</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <!--
                                        <li <? if($menu=="Operaciones"){ ?>class="active"<?}?>><a href="?Modulo=Operaciones"> Operaciones</a></li>
                                        -->
                                        <!--
                                        <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
                                            <a href="javascript:;"> Reportes
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu pull-left">
                                                <li aria-haspopup="true">
                                                    <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-ventas" class="nav-link">Ventas</a>
                                                </li>
                                                <li aria-haspopup="true">
                                                    <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-citas" class="nav-link">Citas</a>
                                                </li>
                                                <li aria-haspopup="true">
                                                    <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-citas-dia" class="nav-link">Citas por día</a>
                                                </li>
                                            </ul>
                                        </li>
                                        -->
                                        <li aria-haspopup="true" class="menu-dropdown mega-menu-dropdown  ">
                                            <a href="javascript:;"> Reportes
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu" style="min-width: 410px">
                                                <li>
                                                    <div class="mega-menu-content">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <ul class="mega-menu-submenu">
	                                                                <li>
                                                                        <h3> Reportes CRM </h3>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-ventas" class="nav-link">Ventas</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-citas" class="nav-link">Citas</a>
                                                                    </li>
                                                                    <li>
                                                                    	<a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-citas-dia" class="nav-link">Citas por día</a>
                                                                    </li>
                                                                    <li>
                                                                    	<a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-ventas-pacientes" class="nav-link">Ventas y Pacientes</a>
                                                                    </li>
                                                                    <li>
                                                                    	<a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-pacientes-canal" class="nav-link">Pacientes por Canal</a>
                                                                    </li>
                                                                    <li>
                                                                    	<a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-agendados" class="nav-link">Agendados</a>
                                                                    </li>
                                                                    <li>
                                                                    	<a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-agendados-canal" class="nav-link">Agendados por Canal</a>
                                                                    </li>
                                                                    <li>
                                                                    	<a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-agendados-usuario" class="nav-link">Agendados por Usuario</a>
                                                                    </li>
                                                                    <li>
                                                                    	<a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-citas-canal" class="nav-link">Citas Nuevas por Canal</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <ul class="mega-menu-submenu">
	                                                                <li>
                                                                        <h3> Reportes Books </h3>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-ingresos" class="nav-link">Ingresos</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-gastos" class="nav-link">Gastos</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-gastos-completo" class="nav-link">Gastos Completos</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-forma-pago" class="nav-link">Ingresos por Forma de Pago</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-ingresos-canal" class="nav-link">Ingresos por Canal</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#reporte-ventas-tratamientos" class="nav-link">Ingresos por Tratamiento</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                        
                                        <!--
                                        <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
                                            <a href="javascript:;"> Configuración
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu pull-left">
                                                <li aria-haspopup="true">
                                                    <a href="?Modulo=Usuarios" class="nav-link">Usuarios</a>
                                                </li>
                                                <li aria-haspopup="true">
                                                    <a href="?Modulo=Clinicas" class="nav-link">Clínicas</a>
                                                </li>
                                                <li aria-haspopup="true">
                                                    <a href="?Modulo=Canales" class="nav-link">Canales</a>
                                                </li>
                                                <li aria-haspopup="true">
                                                    <a href="?Modulo=Tratamientos" class="nav-link">Tratamientos</a>
                                                </li>
                                                <li aria-haspopup="true">
                                                    <a href="?Modulo=Promociones" class="nav-link">Promociones</a>
                                                </li>
                                                <li aria-haspopup="true">
                                                    <a href="?Modulo=Proveedores" class="nav-link">Proveedores</a>
                                                </li>
                                            </ul>
                                        </li>
                                        -->
                                        
                                        <li aria-haspopup="true" class="menu-dropdown mega-menu-dropdown  ">
                                            <a href="javascript:;"> Configuración
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="dropdown-menu pull-right" style="min-width: 710px">
                                                <li>
                                                    <div class="mega-menu-content">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <ul class="mega-menu-submenu">
                                                                    <li>
                                                                        <h3> Configuración General</h3>
                                                                    </li>
                                                                    <li>
                                                                        <a href="?Modulo=Usuarios"> Usuarios </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="?Modulo=Empresas"> Empresas (Clínicas)</a>
                                                                    </li>                                                                    
                                                                    <li>
                                                                        <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#facturacion" class="nav-link">Facturación</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="?Modulo=Doctores"> Doctores </a>
                                                                    </li> 
                                                                    <li>
                                                                        <a href="../pacientes/pacientes.php"> Pacientes Móvil </a>
                                                                    </li>                                                       
                                                                </ul>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <ul class="mega-menu-submenu">
                                                                    <li>
                                                                        <h3>Configuración CRM</h3>
                                                                    </li>
                                                                    
                                                                    <li>
                                                                        <a href="?Modulo=Canales"> Canales </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="?Modulo=Tratamientos"> Tratamientos </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="?Modulo=Promociones"> Promociones </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="?Modulo=Planes"> Planes Corporativos </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="?Modulo=EspecialistasLabs"> Especialistas/Laboratorios </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <ul class="mega-menu-submenu">
                                                                    <li>
                                                                        <h3>Configuración Books</h3>
                                                                    </li>
                                                                    <!--
                                                                    <li>
                                                                        <a href="?Modulo=Empresas"> Empresas </a>
                                                                    </li>-->
                                                                    <li>
                                                                        <a href="?Modulo=Cuentas"> Cuentas </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="?Modulo=Clientes"> Clientes</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="?Modulo=Proveedores"> Proveedores </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="?Modulo=CuentasGastos"> Cuentas de Gastos </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="?Modulo=CuentasIngresos"> Cuentas de Ingresos </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                        
                                        <li class="visible-xs"><a href="login.php">Salir del Sistema</a></li>
                                        
                                        <? endif; ?>
                                    </ul>
                                </div>
                                <!-- END MEGA MENU -->
                            </div>
                        </div>
                        <!-- END HEADER MENU -->
                    </div>
                    <!-- END HEADER -->
                </div>
            </div>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <!-- BEGIN CONTAINER -->
                    <div class="page-container">
                        <!-- BEGIN CONTENT -->
                        <div class="page-content-wrapper">
                            <!-- BEGIN CONTENT BODY -->
                           
                            <!-- BEGIN PAGE CONTENT BODY -->
                            <div class="page-content">
                                <div class="container">
                                    <?
	                                if($s_tipo==1):
                                	switch($menu):
                                	
                                		case 'Planes':
							    		include("planes.php");	
							    		break;
							    		
							    		case 'Plan':
							    		include("plan.php");	
							    		break;
							    		
							    		case 'Usuarios':
							    		include("usuarios.php");	
							    		break;
							    		
							    		case 'Canales':
							    		include("canales.php");	
							    		break;
							    		
							    		case 'Empresas':
							    		include("clinicas.php");	
							    		break;
							    		
							    		case 'Clinicas':
							    		include("clinicas.php");	
							    		break;
							    		
							    		case 'HorariosClinica':
							    		include("clinicas_horarios.php");	
							    		break;
							    		
							    		case 'Tratamientos':
							    		include("tratamientos.php");	
							    		break;
							    		
							    		case 'Promociones':
							    		include("promociones.php");	
							    		break;

							    		//Todos
							    		case 'Agenda':
							    		include("agenda.php");	
							    		break;
							    		
							    		case 'Prospectos':
							    		include("prospectos.php");	
							    		break;
							    		
							    		case 'Citas':
							    		include("citas.php");	
							    		break;
							    		
							    		case 'Pacientes':
							    		include("pacientes2.php");	
							    		break;
							    		
							    		case 'Consulta':
							    		include("consulta.php");	
							    		break;
							    		
							    		case 'Perfil':
							    		include("perfil.php");	
							    		break;
										
										case 'Eventos':
							    		include("eventos_dsh.php");	
							    		break;
							    		
							    		case 'Excepciones':
							    		include("excepciones.php");	
							    		break;
							    		
							    		case 'NExcepcion':
							    		include("nueva_excepcion.php");	
							    		break;
							    		
							    		/* Books */
							    		
							    		case 'Cuentas':
							    		include("cuentas.php");	
							    		break;
							    		
							    		case 'Prueba':
							    		include("dashboard_new.php");	
							    		break;
							    		
							    		
							    		case 'CuentasGastos':
							    		include("cuentas_gastos.php");	
							    		break;
							    		
							    		case 'CuentasIngresos':
							    		include("cuentas_ingresos.php");	
							    		break;
							    		
							    		case 'Proveedores':
							    		include("proveedores.php");	
							    		break;
							    		
							    		case 'Transacciones':
							    		include("transacciones.php");	
							    		break;
							    		
							    		case 'TransaccionesClinica':
							    		include("transacciones_clinica_todas.php");	
							    		break;
							    		
							    		case 'Operaciones':
							    		include("operaciones.php");	
							    		break;
							    		
							    		case 'Clientes':
							    		include("clientes.php");	
							    		break;
							    		
							    		case 'Pacientes2':
							    		include("pacientes2.php");	
							    		break;
							    		
							    		/* Facturacion */
							    		
							    		case 'Facturacion':
							    		include("facturacion.php");	
							    		break;
							    		
							    		case 'PreFacturas':
							    		include("pre_facturas.php");	
							    		break;
							    		
							    		case 'PreImportacion':
							    		include("pre_importacion.php");	
							    		break;
							    		
							    		case 'NuevaFactura':
							    		include("nueva_factura.php");
							    		break;
							    		
							    		case 'ClientesFactura':
							    		include("clientes_facturacion.php");
							    		break;
							    		
							    		case 'NuevoCliente':
							    		include("nuevo_cliente.php");
							    		break;
							    		
							    		case 'Doctores':
							    		include("doctores.php");
							    		break;
							    		
							    		case 'EditaCliente':
							    		include("edita_cliente.php");
							    		break;
							    		
							    		/* Especialistas & Laboratiorios */
							    		
							    									    		
							    		case 'EspecialistasLabs':
							    		include("especialistas_labs.php");
							    		break;
							    		
							    		case 'OperacionesEspecialistas':
							    		include("operaciones_especialistas.php");
							    		break;
							    		
							    		case 'OperacionesEspecialistasPagadas':
							    		include("operaciones_especialistas_pagadas.php");
							    		break;
							    		
							    		case 'TransaccionesEspecialista':
							    		include("transacciones_especialista.php");
							    		break;
							    		
							    		

							    		default:
							    		include('dashboard_new.php');
							    	
									endswitch;
									else:
									switch($menu):
							    		

							    		//Todos
							    		case 'Agenda':
							    		include("agenda.php");	
							    		break;
							    		
							    		case 'Prospectos':
							    		include("prospectos.php");	
							    		break;
							    		
							    		case 'Citas':
							    		include("citas.php");	
							    		break;
							    		
							    		case 'Pacientes':
							    		include("pacientes2.php");	
							    		break;
							    		
							    		case 'Consulta':
							    		include("consulta.php");	
							    		break;
							    		
							    		case 'Gastos':
							    		include("gastos.php");	
							    		break;
							    		
							    		case 'Transacciones':
							    		include("transacciones_clinica.php");	
							    		break;
							    		
							    		case 'Perfil':
							    		include("perfil.php");	
							    		break;
							    		
							    		case 'Eventos':
							    		include("eventos_dsh.php");	
							    		break;
							    		
							    		case 'Excepciones':
							    		include("excepciones.php");	
							    		break;
							    		
							    		case 'NExcepcion':
							    		include("nueva_excepcion.php");	
							    		break;
							    		
							    		case 'TransaccionesClinica':
							    		include("transacciones_clinica_todas.php");	
							    		break;
											
										default:
										if($s_tipo==2):
							    			include('prospectos.php');
							    		else:
							    			include('agenda.php');
							    		endif;
							    	
									endswitch;
									endif;
									?>
                                </div>
                            </div>
                            <!-- END PAGE CONTENT BODY -->
                            <!-- END CONTENT BODY -->
                        </div>
                        <!-- END CONTENT -->
                        
                    </div>
                    <!-- END CONTAINER -->
                </div>
            </div>
            <div class="page-wrapper-row">
                <div class="page-wrapper-bottom">
                    <!-- BEGIN FOOTER -->
                    <!-- BEGIN INNER FOOTER -->
                    <div class="page-footer">
                        <div class="container"> <?=date('Y')?> © DENTIS+A. Hecho con <i class="fa fa-heart" style="color: #e74c3c;"></i> &amp; <i class="fa fa-coffee"></i> por <a href="http://epicmedia.pro" target="_blank">EPICMEDIA</a>
                        </div>
                    </div>
                    <div class="scroll-to-top">
                        <i class="icon-arrow-up"></i>
                    </div>
                    <!-- END INNER FOOTER -->
                    <!-- END FOOTER -->
                </div>
            </div>
        </div>
        
        <!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<script src="assets/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/fullcalendar/lang-all.js" type="text/javascript"></script>
        
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
        
        <script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
		
		<script src="assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
        
        
        <script src="assets/global/plugins/echarts/echarts.js" type="text/javascript"></script>
        
        
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.js" type="text/javascript"></script>
        
        <!-- END THEME GLOBAL SCRIPTS -->
        <script src="assets/pages/scripts/components-date-time-pickers.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-select2.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
        <script src="assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>
        <script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        <script>
	        $(function(){
	        	$('.quick-sidebar-toggler').click(function(){
		        	window.open("login.php", "_self");
	        	});
	        });
	    </script>
    </body>

</html>




<!-- Reportes -->

<!-- Modal -->
<div class="modal fade" id="reporte-ventas">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de ventas</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="reporte-msg_error"></div>
<!--Formulario -->
		<form class="form-horizontal" id="frm-reporte-ventas">
			
			<div class="form-group">
				<label for="direccion" class="col-md-4 control-label">Clínica</label>
				<div class="col-md-7">
					<select class="form-control r_limpia_s" name="id_clinica" id="ventas_id_clinica">
						<option value="0">Seleccione una clínica</option>
						<? foreach($clinicas as $clinica): ?>
						<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>
            
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control r_limpia" name="fecha1" id="ventas_fecha1">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control r_limpia" name="fecha2" id="ventas_fecha2"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="reporte-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="reporteVentas()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	

<div class="modal fade" id="reporte-citas">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de citas</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="citas-msg_error"></div>
<!--Formulario -->
		<form class="form-horizontal" id="frm-reporte-citas">
			
			<div class="form-group">
				<label for="direccion" class="col-md-4 control-label">Clínica</label>
				<div class="col-md-7">
					<select class="form-control r_limpia_s" name="id_clinica" id="citas_id_clinica">
						<option value="0">Seleccione una clínica</option>
						<? foreach($clinicas as $clinica): ?>
						<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>
            
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control r_limpia" name="fecha1" id="citas_fecha1">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control r_limpia" name="fecha2" id="citas_fecha2"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="citas-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="reporteCitas()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	


<div class="modal fade" id="reporte-citas-dia">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de citas por día</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="dia-msg_error"></div>
<!--Formulario -->
		<form class="form-horizontal" id="frm-reporte-dia">
			
			<div class="form-group">
				<label for="direccion" class="col-md-4 control-label">Clínica</label>
				<div class="col-md-7">
					<select class="form-control r_limpia_s" name="id_clinica" id="dia_id_clinica">
						<option value="0">Seleccione una clínica</option>
						<? foreach($clinicas as $clinica): ?>
						<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>
            
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="dd-mm-yyyy">
						<input type="text" class="form-control r_limpia" name="fecha1" id="dia_fecha1">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control r_limpia" name="fecha2" id="dia_fecha2"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="citas-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="reporteCitasDia()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	


<!-- Modal -->
<div class="modal fade" id="reporte-ingresos">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de ingresos</h4>
      </div>
      <div class="modal-body">
<!--Formulario -->
		<form class="form-horizontal" id="frm-reporte-ingresos">
			<div class="alert alert-danger oculto" role="alert" id="ingresos-msg_error"></div>
			<div class="form-group">
				<label for="direccion" class="col-md-4 control-label">Clínica</label>
				<div class="col-md-7">
					<select class="form-control r_limpia_s" name="id_clinica" id="ingresos_id_clinica">
						<option value="0">Seleccione una clínica</option>
						<? foreach($clinicas as $clinica): ?>
						<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label for="direccion" class="col-md-4 control-label">Cuenta</label>
				<div class="col-md-7">
					<select class="form-control r_limpia_s" name="id_cuenta" id="ingresos_id_cuenta">
						<option value="0">Seleccione cuenta</option>
					</select>
				</div>
			</div>
            
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control r_limpia" name="fecha1" id="ingresos_fecha1">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control r_limpia" name="fecha2" id="ingresos_fecha2"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="reporte-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="reporteIngresos()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- Modal -->
<div class="modal fade" id="reporte-gastos">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de gastos</h4>
      </div>
      <div class="modal-body">
<!--Formulario -->
		<form class="form-horizontal" id="frm-reporte-gastos">
			<div class="alert alert-danger oculto" role="alert" id="gastos-msg_error"></div>
			<div class="form-group">
				<label for="direccion" class="col-md-4 control-label">Clínica</label>
				<div class="col-md-7">
					<select class="form-control r_limpia_s" name="id_clinica" id="gastos_id_clinica">
						<option value="0">Seleccione una clínica</option>
						<? foreach($clinicas as $clinica): ?>
						<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label for="direccion" class="col-md-4 control-label">Cuenta</label>
				<div class="col-md-7">
					<select class="form-control r_limpia_s" name="id_cuenta" id="gastos_id_cuenta">
						<option value="0">Seleccione cuenta</option>
					</select>
				</div>
			</div>
            
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control r_limpia" name="fecha1" id="gastos_fecha1">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control r_limpia" name="fecha2" id="gastos_fecha2"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="reporte-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="reporteGastos()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal -->
<div class="modal fade" id="reporte-ventas-pacientes">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de ventas y pacientes</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="reporte-msg_error"></div>
<!--Formulario -->
		<form class="form-horizontal" id="frm-reporte-ventas-pacientes">
			
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control r_limpia" name="fecha1" id="ventas_pacientes_fecha1">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control r_limpia" name="fecha2" id="ventas_pacientes_fecha2"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="reporte-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="reporteVentasPac()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	

<!-- Modal -->
<div class="modal fade" id="reporte-pacientes-canal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de pacientes por Canal</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="reporte-msg_error"></div>
<!--Formulario -->
		<form class="form-horizontal" id="frm-reporte-pacientes-canal">
			
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control r_limpia" name="fecha1" id="pacientes_canal_fecha1">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control r_limpia" name="fecha2" id="pacientes_canal_fecha2"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="reporte-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="reportePacientesCan()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	

<!-- Modal -->
<div class="modal fade" id="reporte-agendados">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Pacientes Agendados</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="reporte-msg_error"></div>
<!--Formulario -->
		<form class="form-horizontal" id="frm-reporte-agendados">
			
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control r_limpia" name="fecha1" id="agendados_fecha1">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control r_limpia" name="fecha2" id="agendados_fecha2"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="reporte-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="reporteAgendados()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	

<!-- Modal -->
<div class="modal fade" id="reporte-agendados-usuario">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Pacientes Agendados por Usuario</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="reporte-msg_error"></div>
<!--Formulario -->
		<form class="form-horizontal" id="frm-reporte-agendados-usuario">
			
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control r_limpia" name="fecha1" id="agendados_usuario_fecha1">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control r_limpia" name="fecha2" id="agendados_usuario_fecha2"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="reporte-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="reporteAgendadosUsu()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	

<!-- Modal -->
<div class="modal fade" id="reporte-agendados-canal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Pacientes Agendados por Canal</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="reporte-msg_error"></div>
<!--Formulario -->
		<form class="form-horizontal" id="frm-reporte-agendados-canal">
			
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control r_limpia" name="fecha1" id="agendados_canal_fecha1">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control r_limpia" name="fecha2" id="agendados_canal_fecha2"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="reporte-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="reporteAgendadosCan()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	

<!-- Modal -->
<div class="modal fade" id="reporte-citas-canal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Citas por Canal</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="reporte-msg_error"></div>
<!--Formulario -->
		<form class="form-horizontal" id="frm-reporte-citas-canal">
			
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control r_limpia" name="fecha1" id="citas_canal_fecha1">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control r_limpia" name="fecha2" id="citas_canal_fecha2"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="reporte-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="reporteCitasCan()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	

<!-- Modal -->
<div class="modal fade" id="reporte-gastos-completo">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Gastos Completo</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="reporte-msg_error"></div>
<!--Formulario -->
		<form class="form-horizontal" id="frm-reporte-gastos-completo">
			
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control r_limpia" name="fecha1" id="gastos_completo_fecha1">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control r_limpia" name="fecha2" id="gastos_completo_fecha2"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="reporte-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="reporteGastosCom()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	

<!-- Modal -->
<div class="modal fade" id="reporte-forma-pago">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Ingresos por Método de Pago</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="reporte-msg_error"></div>
<!--Formulario -->
		<form class="form-horizontal" id="frm-reporte-metodo-pago">
			
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control r_limpia" name="fecha1" id="metodo_pago_fecha1">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control r_limpia" name="fecha2" id="metodo_pago_fecha2"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="reporte-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="reporteMetodoPag()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	

<!-- Modal -->
<div class="modal fade" id="reporte-ingresos-canal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Ingresos por Canal</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="reporte-msg_error"></div>
<!--Formulario -->
		<form class="form-horizontal" id="frm-reporte-ingresos-canal">
			
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control r_limpia" name="fecha1" id="ingresos_canal_fecha1">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control r_limpia" name="fecha2" id="ingresos_canal_fecha2"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="reporte-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="reporteIngresosCan()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	

<!-- Modal -->
<div class="modal fade" id="reporte-ventas-tratamientos">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Reporte de Ventas por Tratamiento</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="reporte-msg_error"></div>
<!--Formulario -->
		<form class="form-horizontal" id="frm-reporte-ventas-tratamientos">
			<div class="form-group">
				<label for="direccion" class="col-md-4 control-label">Clínica</label>
				<div class="col-md-7">
					<select class="form-control r_limpia_s" name="id_clinica" id="tratamientos_id_clinica">
						<option value="0">Seleccione una clínica</option>
						<? foreach($clinicas as $clinica): ?>
						<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>
			
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control r_limpia" name="fecha1" id="ventas_tratamientos_fecha1">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control r_limpia" name="fecha2" id="ventas_tratamientos_fecha2"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="reporte-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="reporteVentasTrat()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	



<!-- Visor de facturas -->
<div class="modal fade" id="verFactura" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body text-center">
               <br/><br/> <img src="assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                <span>&nbsp;&nbsp;Cargando... </span><br/><br/>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="facturacion">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Días para Facturación</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="facturacion_msg_error"></div>
<!--Formulario -->
		<form class="form-horizontal" id="frm-facturacion">
            
            <div class="form-group">
                <label class="control-label col-md-4">Días</label>
                <div class="col-md-8">
                    <div class="input-group input-large">
						<input type="text" class="form-control r_limpia" name="dias" id="facturacion_dias"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="facturacion-load" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cerrar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="facturar()">Editar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	



<script>
$(function(){
	$('#reporte-citas-dia').on('hidden.bs.modal',function(e){
		$('#dia_id_clinica').val("0");
		$('.r_limpia').val("");
		$('#dia-msg_error').hide();
	});	
	$('#reporte-citas').on('hidden.bs.modal',function(e){
		$('#citas_id_clinica').val("0");
		$('.r_limpia').val("");
		$('#citas-msg_error').hide();
	});	
	$('#reporte-ventas').on('hidden.bs.modal',function(e){
		$('#ventas_id_clinica').val("0");
		$('.r_limpia').val("");
		$('#reporte-msg_error').hide();
	});
	$('#reporte-ventas-pacientes').on('hidden.bs.modal',function(e){
		$('.r_limpia').val("");
		$('#reporte-msg_error').hide();
	});
	$('#reporte-ventas-tratamientos').on('hidden.bs.modal',function(e){
		$('.r_limpia').val("");
		$('#reporte-msg_error').hide();
	});
	$('#reporte-pacientes-canal').on('hidden.bs.modal',function(e){
		$('.r_limpia').val("");
		$('#reporte-msg_error').hide();
	});
	$('#reporte-agendados').on('hidden.bs.modal',function(e){
		$('.r_limpia').val("");
		$('#reporte-msg_error').hide();
	});
	$('#reporte-agendados-canal').on('hidden.bs.modal',function(e){
		$('.r_limpia').val("");
		$('#reporte-msg_error').hide();
	});
	$('#reporte-agendados-usuario').on('hidden.bs.modal',function(e){
		$('.r_limpia').val("");
		$('#reporte-msg_error').hide();
	});
	$('#reporte-citas-canal').on('hidden.bs.modal',function(e){
		$('.r_limpia').val("");
		$('#reporte-msg_error').hide();
	});
	$('#reporte-gasto-completo').on('hidden.bs.modal',function(e){
		$('.r_limpia').val("");
		$('#reporte-msg_error').hide();
	});
	$('#reporte-forma-pago').on('hidden.bs.modal',function(e){
		$('.r_limpia').val("");
		$('#reporte-msg_error').hide();
	});
	$('#reporte-ingresos-canal').on('hidden.bs.modal',function(e){
		$('.r_limpia').val("");
		$('#reporte-msg_error').hide();
	});
	$('#reporte-ingresos').on('hidden.bs.modal',function(e){
		$('#ingresos_id_clinica').val("0");
		$('#ingresos_id_metodo_pago').val("0");
		$('#ingresos_id_cuenta').val("0");
		$('.r_limpia').val("");
		$('.oculto').hide();
	});
	
	
	
	
	$('#ingresos_id_clinica').change(function(){
		var id_clinica = $('#ingresos_id_clinica').val();
		$.ajax({
			url: "data/books_select_cuentas.php",
	   		data: 'id_clinica='+id_clinica,
	   		success: function(data){
		   		console.log(data);
		   		$('#ingresos_id_cuenta').html(data);
	   	},
	   	cache: false
	   	});
		
	});
	
	
	$('#gastos_id_clinica').change(function(){
		var id_clinica = $('#gastos_id_clinica').val();
		$.ajax({
			url: "data/books_select_cuentas.php",
			data: 'id_clinica='+id_clinica,
			success: function(data){
				console.log(data);
				$('#gastos_id_cuenta').html(data);
		},
		cache: false
		});
		
	});
	
	$('#facturacion').on('shown.bs.modal',function(e){
		$('#facturacion_dias').val('Cargando...');
		$.ajax({
			url: "data/dias_factura.php",
	   		success: function(data){
		   		console.log(data);
		   		$('#facturacion_dias').val(data);
	   	},
	   	cache: false
	   	});
		
	});
});
function reporteVentas(){
	var id_clinica 	= Number($('#ventas_id_clinica').val());
	var fecha1		= $('#ventas_fecha1').val();
	var fecha2		= $('#ventas_fecha2').val();
	
	if(id_clinica==0){
		$('#reporte-msg_error').html("Debe seleccionar una clínica para generar el reporte");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha1){
		$('#reporte-msg_error').html("Seleccione la fecha de inicio");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha2){
		$('#reporte-msg_error').html("Seleccione la fecha final");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	$('#reporte-msg_error').hide('Fast');
	//$('.btn_ac').hide();
	//$('#reporte-load').show();
	var datos=$('#frm-reporte-ventas').serialize();
	window.open("reportes/ventas.php?"+datos, "_blank");	    
}

function reporteVentasPac(){
	var fecha1		= $('#ventas_pacientes_fecha1').val();
	var fecha2		= $('#ventas_pacientes_fecha2').val();
	
	if(!fecha1){
		$('#reporte-msg_error').html("Seleccione la fecha de inicio");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha2){
		$('#reporte-msg_error').html("Seleccione la fecha final");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	$('#reporte-msg_error').hide('Fast');
	//$('.btn_ac').hide();
	//$('#reporte-load').show();
	var datos=$('#frm-reporte-ventas-pacientes').serialize();
	window.open("reportes/ventas_pacientes.php?"+datos, "_blank");	    
}

function reporteVentasTrat(){
	var id_clinica 	= Number($('#tratamientos_id_clinica').val());
	var fecha1		= $('#ventas_tratamientos_fecha1').val();
	var fecha2		= $('#ventas_tratamientos_fecha2').val();
	
	if(id_clinica==0){
		$('#reporte-msg_error').html("Debe seleccionar una clínica para generar el reporte");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha1){
		$('#reporte-msg_error').html("Seleccione la fecha de inicio");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha2){
		$('#reporte-msg_error').html("Seleccione la fecha final");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	$('#reporte-msg_error').hide('Fast');
	//$('.btn_ac').hide();
	//$('#reporte-load').show();
	var datos=$('#frm-reporte-ventas-tratamientos').serialize();
	window.open("reportes/ventas_tratamientos.php?"+datos, "_blank");	    
}

function reportePacientesCan(){
	var fecha1		= $('#pacientes_canal_fecha1').val();
	var fecha2		= $('#pacientes_canal_fecha2').val();
	
	if(!fecha1){
		$('#reporte-msg_error').html("Seleccione la fecha de inicio");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha2){
		$('#reporte-msg_error').html("Seleccione la fecha final");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	$('#reporte-msg_error').hide('Fast');
	//$('.btn_ac').hide();
	//$('#reporte-load').show();
	var datos=$('#frm-reporte-pacientes-canal').serialize();
	window.open("reportes/pacientes_x_canal.php?"+datos, "_blank");	    
}

function reporteAgendados(){
	var fecha1		= $('#agendados_fecha1').val();
	var fecha2		= $('#agendados_fecha2').val();
	
	if(!fecha1){
		$('#reporte-msg_error').html("Seleccione la fecha de inicio");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha2){
		$('#reporte-msg_error').html("Seleccione la fecha final");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	$('#reporte-msg_error').hide('Fast');
	//$('.btn_ac').hide();
	//$('#reporte-load').show();
	var datos=$('#frm-reporte-agendados').serialize();
	window.open("reportes/agendados_asistencia.php?"+datos, "_blank");	    
}

function reporteAgendadosCan(){
	var fecha1		= $('#agendados_canal_fecha1').val();
	var fecha2		= $('#agendados_canal_fecha2').val();
	
	if(!fecha1){
		$('#reporte-msg_error').html("Seleccione la fecha de inicio");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha2){
		$('#reporte-msg_error').html("Seleccione la fecha final");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	$('#reporte-msg_error').hide('Fast');
	//$('.btn_ac').hide();
	//$('#reporte-load').show();
	var datos=$('#frm-reporte-agendados-canal').serialize();
	window.open("reportes/agendados_x_canal.php?"+datos, "_blank");	    
}

function reporteAgendadosUsu(){
	var fecha1		= $('#agendados_usuario_fecha1').val();
	var fecha2		= $('#agendados_usuario_fecha2').val();
	
	if(!fecha1){
		$('#reporte-msg_error').html("Seleccione la fecha de inicio");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha2){
		$('#reporte-msg_error').html("Seleccione la fecha final");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	$('#reporte-msg_error').hide('Fast');
	//$('.btn_ac').hide();
	//$('#reporte-load').show();
	var datos=$('#frm-reporte-agendados-usuario').serialize();
	window.open("reportes/agendados_x_usuario.php?"+datos, "_blank");	    
}


function reporteCitasCan(){
	var fecha1		= $('#citas_canal_fecha1').val();
	var fecha2		= $('#citas_canal_fecha2').val();
	
	if(!fecha1){
		$('#reporte-msg_error').html("Seleccione la fecha de inicio");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha2){
		$('#reporte-msg_error').html("Seleccione la fecha final");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	$('#reporte-msg_error').hide('Fast');
	//$('.btn_ac').hide();
	//$('#reporte-load').show();
	var datos=$('#frm-reporte-citas-canal').serialize();
	window.open("reportes/citas_x_canal.php?"+datos, "_blank");	    
}

function reporteGastosCom(){
	var fecha1		= $('#gastos_completo_fecha1').val();
	var fecha2		= $('#gastos_completo_fecha2').val();
	
	if(!fecha1){
		$('#reporte-msg_error').html("Seleccione la fecha de inicio");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha2){
		$('#reporte-msg_error').html("Seleccione la fecha final");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	$('#reporte-msg_error').hide('Fast');
	//$('.btn_ac').hide();
	//$('#reporte-load').show();
	var datos=$('#frm-reporte-gastos-completo').serialize();
	window.open("reportes/gastos_todas.php?"+datos, "_blank");	    
}

function reporteMetodoPag(){
	var fecha1		= $('#metodo_pago_fecha1').val();
	var fecha2		= $('#metodo_pago_fecha2').val();
	
	if(!fecha1){
		$('#reporte-msg_error').html("Seleccione la fecha de inicio");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha2){
		$('#reporte-msg_error').html("Seleccione la fecha final");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	$('#reporte-msg_error').hide('Fast');
	//$('.btn_ac').hide();
	//$('#reporte-load').show();
	var datos=$('#frm-reporte-metodo-pago').serialize();
	window.open("reportes/ventas_metodos.php?"+datos, "_blank");	    
}

function reporteIngresosCan(){
	var fecha1		= $('#ingresos_canal_fecha1').val();
	var fecha2		= $('#ingresos_canal_fecha2').val();
	
	if(!fecha1){
		$('#reporte-msg_error').html("Seleccione la fecha de inicio");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha2){
		$('#reporte-msg_error').html("Seleccione la fecha final");
		$('#reporte-msg_error').show('Fast');
		return false;
	}
	
	$('#reporte-msg_error').hide('Fast');
	//$('.btn_ac').hide();
	//$('#reporte-load').show();
	var datos=$('#frm-reporte-ingresos-canal').serialize();
	window.open("reportes/ventas_x_canal.php?"+datos, "_blank");	    
}

function reporteCitas(){
	var id_clinica 	= Number($('#citas_id_clinica').val());
	var fecha1		= $('#citas_fecha1').val();
	var fecha2		= $('#citas_fecha2').val();
	
	if(id_clinica==0){
		$('#citas-msg_error').html("Debe seleccionar una clínica para generar el reporte");
		$('#citas-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha1){
		$('#citas-msg_error').html("Seleccione la fecha de inicio");
		$('#citas-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha2){
		$('#citas-msg_error').html("Seleccione la fecha final");
		$('#citas-msg_error').show('Fast');
		return false;
	}
	
	$('#citas-msg_error').hide('Fast');
	//$('.btn_ac').hide();
	//$('#reporte-load').show();
	var datos=$('#frm-reporte-citas').serialize();
	window.open("reportes/citas.php?"+datos, "_blank");	    
}

function reporteCitasDia(){
	var id_clinica 	= Number($('#dia_id_clinica').val());
	var fecha1		= $('#dia_fecha1').val();
	var fecha2		= $('#dia_fecha2').val();
	
	if(id_clinica==0){
		$('#dia-msg_error').html("Debe seleccionar una clínica para generar el reporte");
		$('#dia-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha1){
		$('#dia-msg_error').html("Seleccione la fecha de inicio");
		$('#dia-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha2){
		$('#dia-msg_error').html("Seleccione la fecha final");
		$('#dia-msg_error').show('Fast');
		return false;
	}
	
	$('#dia-msg_error').hide('Fast');
	//$('.btn_ac').hide();
	//$('#reporte-load').show();
	var datos=$('#frm-reporte-dia').serialize();
	window.open("reportes/citasxdia.php?"+datos, "_blank");	    
}

function reporteIngresos(){
	var id_clinica 	= Number($('#ingresos_id_clinica').val());
	var id_metodo_pago 	= Number($('#ingresos_id_metodo_pago').val());
	var fecha1		= $('#ingresos_fecha1').val();
	var fecha2		= $('#ingresos_fecha2').val();
	
	if(id_clinica==0){
		$('#ingresos-msg_error').html("Debe seleccionar una clínica para generar el reporte");
		$('#ingresos-msg_error').show('Fast');
		return false;
	}
	
	
	
	if(!fecha1){
		$('#ingresos-msg_error').html("Seleccione la fecha de inicio");
		$('#ingresos-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha2){
		$('#ingresos-msg_error').html("Seleccione la fecha final");
		$('#ingresos-msg_error').show('Fast');
		return false;
	}
	
	$('#ingresos-msg_error').hide('Fast');
	//$('.btn_ac').hide();
	//$('#reporte-load').show();
	var datos=$('#frm-reporte-ingresos').serialize();
	window.open("reportes/ingresos.php?"+datos, "_blank");	    
}

function reporteGastos(){
	var id_clinica 	= Number($('#gastos_id_clinica').val());
	var id_metodo_pago 	= Number($('#gastos_id_metodo_pago').val());
	var fecha1		= $('#gastos_fecha1').val();
	var fecha2		= $('#gastos_fecha2').val();
	
	if(id_clinica==0){
		$('#ingresos-msg_error').html("Debe seleccionar una clínica para generar el reporte");
		$('#ingresos-msg_error').show('Fast');
		return false;
	}
	
	
	
	if(!fecha1){
		$('#ingresos-msg_error').html("Seleccione la fecha de inicio");
		$('#ingresos-msg_error').show('Fast');
		return false;
	}
	
	if(!fecha2){
		$('#ingresos-msg_error').html("Seleccione la fecha final");
		$('#ingresos-msg_error').show('Fast');
		return false;
	}
	
	$('#gastos-msg_error').hide('Fast');
	//$('.btn_ac').hide();
	//$('#reporte-load').show();
	var datos=$('#frm-reporte-gastos').serialize();
	window.open("reportes/gastos.php?"+datos, "_blank");	    
}
function facturar(){
	var dias = Number($('#facturacion_dias').val());
	
	if(dias==0){
		$('#facturacion_msg_error').html("Debe dar al menos un día para poder facturar");
		$('#facturacion_msg_error').show('Fast');
		return false;
	}
	
	$('#facturacion_msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#facturacion-load').show();
	var datos=$('#frm-facturacion').serialize();
	$.post('ac/dias_facturar.php',datos,function(data){
	    if(data==1){
			location.reload();
	    }else{
	    	$('#facturacion-load').hide();
			$('.btn_ac').show();
			$('#facturacion_msg_error').html(data);
			$('#facturacion_msg_error').show('Fast');
	    }
	});	    
}

</script>