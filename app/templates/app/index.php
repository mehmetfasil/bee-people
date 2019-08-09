<?php defined( "PASS") or die(label( "Dosya Yok !")); 
include("modules/app/menus.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= LANG ?>" lang="<?= LANG ?>">
<head>
<title><?=CONF_SYSTEM_TITLE ?> | <?=WEBIM_PAGE_TITLE ?></title>
<meta description="" />
<meta copyright="" />
<meta keywords=""/>
<meta charset="utf-8"/>
<meta name="description" content=""/>
<meta name="author" content="Mehmet FASIL"/>
<meta title="Mehmet FASIL" content="Mehmet FASIL"/>
<meta name="robots" content="INDEX, FOLLOW"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-type" content="text/html; charset=<?= LANGUAGE_CHARSET ?>" />
<link rel="shortcut icon" href="templates/<?= WEBIM_PAGE_TEMPLATE ?>/images/favicon.png" />
<link type="text/css" href="templates/<?= WEBIM_PAGE_TEMPLATE; ?>/style.css" rel="stylesheet" />
<!-- Bootstrap -->
<link href="objects/js/app/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="objects/js/app/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<!-- NProgress -->
<link href="objects/js/app/vendors/nprogress/nprogress.css" rel="stylesheet">
<!-- Custom Theme Style -->
<link href="objects/js/app/build/css/custom.min.css" rel="stylesheet">
<!-- PNotify -->
<link href="objects/js/app/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<link href="objects/js/app/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
<link href="objects/js/app/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
<!-- iCheck -->
<link href="objects/js/app/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
<!-- Datatables -->
<link href="objects/js/app/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="objects/js/app/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="objects/js/app/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
<link href="objects/js/app/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="objects/js/app/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
<script>
	var url = '<?= CONF_MAIN_PAGE . "?pid=" . PID . "&sid=query" ?>';
	var urlCommon = '<?= CONF_MAIN_PAGE . "?pid=" . menuID("SITE_APP_COMMONS") ."&act=" ?>';
    var PID = <?=PID;?>
</script>
</head>
<body class="nav-md">
    <div id="load-screen" align="center" class="display-none">
        <img src="objects/icons/loaders/11.gif"/>
    </div>
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
					<div class="navbar nav_title" style="border: 0;">
						<a href="index.php" class="site_title" id="tour_site_title"><span><?= $_SESSION['SYS_USER_FIRM_NAME']; ?></span></a>
					</div>
					<div class="clearfix"></div>
				    <!-- menu profile quick info -->
					<div class="profile">
						<div class="profile_pic">
							<img src="templates/<?= WEBIM_PAGE_TEMPLATE ?>/images/logo.png" alt="..." class="img-circle profile_img">
						</div>
						<div class="profile_info">
							<span>
								Hoşgeldiniz,
							</span>
							<h2>
								<?=$_SESSION[ 'SYS_USER_FULLNAME'] ?>
							</h2>
						</div>
					</div>
					<!-- /menu profile quick info -->
					<br />
					<!-- sidebar menu -->
					<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
						<div class="menu_section">
							<h3 id="tour_user_role_name">
								<?=$_SESSION[ 'SYS_USER_ROLE_NAME']; ?>
                                
							</h3>
							<ul class="nav side-menu" id="tour_menus">
								<li>
								<!--	<a href="index.php"><i class="fa fa-home"></i> Anasayfa</a>	-->
                                <a href="index.php">ANASAYFA</a>	
								</li>
                                <?=GetMenus($_SESSION['SYS_USER_ROLE_ID']);?>
                                
							</ul>
						</div>
						<div class="menu_section">
							<h3>
								ARAÇLAR
							</h3>
							<ul class="nav side-menu">
								<li>
									<a id="tour_system">SİSTEM<span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li>
											<a href="#">SİSTEM DURUMU</a>
										</li>
                                        <?
                                        if($_SESSION['SYS_USER_ROLE_ID']==1){
                                            ?>
                                            <li>
    											<a id="tour_system_settings" href="index.php?pid=<?=menuID("SITE_APP_SETTINGS") ?>">AYARLAR</a>
    										</li>
                                            <?
                                        }
                                        ?>
									</ul>
								</li>
								<li>
									<a href="javascript:void(0)"><i class="fa fa-laptop"></i> CANLI DESTEK</a>
								</li>
							</ul>
						</div>
					</div>
					<!-- /sidebar menu -->
					<!-- /menu footer buttons -->
					<div class="sidebar-footer hidden-small">
						<a data-toggle="tooltip" data-placement="top" title="Ayarlar">
						<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
						</a>
						<a data-toggle="tooltip" data-placement="top" title="Tam Ekran">
						<span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
						</a>
						<a data-toggle="tooltip" data-placement="top" title="Kilitle">
						<span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
						</a>
						<a data-toggle="tooltip" data-placement="top" title="Çıkış Yap" href="<?= CONF_MAIN_PAGE . (PID !=
						0 ? "?pid=" . menuID("SITE_APP ") . "&amp;logout=OK " : "?logout=OK ") ?>">
						<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
						</a>
					</div>
					<!-- /menu footer buttons -->
				</div>
    		</div>
    		<!-- top navigation -->
			<div class="top_nav">
				<div class="nav_menu">
					<nav>
						<div class="nav toggle">
							<a id="menu_toggle"><i class="fa fa-bars"></i></a>
						</div>
						<ul class="nav navbar-nav navbar-right">
							<li class="">
								<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<img src="objects/assets/uploads/user_photos/img.png" alt=""><?= $_SESSION['SYS_USER_FULLNAME'] ?>
								<span class=" fa fa-angle-down"></span>
								</a>
								<ul class="dropdown-menu dropdown-usermenu pull-right">
									<li>
										<a href="index.php?pid=<?= menuID("SITE_APP_USER_PROFILE"); ?>"> Profil</a>
									</li>
									<!--<li>
										<a href="javascript:;">
										<span class="badge bg-red pull-right">50%</span>
										<span>Ayarlar</span>
										</a>
									</li>-->
									<li>
										<a href="javascript:;">Yardım</a>
									</li>
									<li>
										<a href="<?= CONF_MAIN_PAGE . (PID != 0 ? "?pid=" .
										menuID("SITE_DOKTORUM ") . "&amp;logout=OK " : "?logout=OK ") ?>"><i class="fa fa-sign-out pull-right"></i> Çıkış Yap</a>
									</li>
								</ul>
							</li>
							<li role="presentation" class="dropdown">
								<a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
								<i class="fa fa-envelope-o"></i>
								<!--<span class="badge bg-green">6</span>-->
								</a>
								<!--<ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
									<li>
										<a>
										<span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
										<span>
										<span>John Smith</span>
										<span class="time">3 mins ago</span>
										</span>
										<span class="message">
										Film festivals used to be do-or-die moments for movie makers. They were where...
										</span>
										</a>
									</li>
								</ul>-->
							</li>
						</ul>
					</nav>
				</div>
			</div>
			<!-- /top navigation -->
			<!-- page content -->
			<div class="right_col" role="main">
				<div class="">
					<? //user trial day left 
                    if (isset($_SESSION[ 'IS_USER_OWNER']) && $_SESSION[ 'IS_USER_OWNER']==true) { if ($_SESSION[ 'SYS_USER_ACCOUNT_REMAINING_DAY']==0) { 
                        //trial expired. head to payment page 
                        if (PID !=menuID( "SITE_APP_SETTINGS")) { echo ( "<script>location.href = 'index.php?pid=" . menuID( "SITE_APP_SETTINGS") . "'</script>"); } ?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							</button>
							<strong>
								Merhaba, Deneme Süreniz Sona Erdi. EKare'yi kullanmaya devam edebilmek için lütfen aylık aboneliklerimizden birini seçerek ödeme yapınız.
							</strong>
						</div>
						<? } else { ?>
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
							<strong>Merhaba, Deneme Sürümünün Sona Ermesine <b><?=$_SESSION[ 'SYS_USER_ACCOUNT_REMAINING_DAY'] ?></b> gün kaldı.</strong>
						</div>
						<? } } ?>
						<div class="page-title">
							<div class="title_left">
								<h3><?=WEBIM_PAGE_TITLE; ?></h3>
							</div>
							<div class="title_right">
								<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
									<div class="input-group">
										<input type="text" class="form-control" placeholder="Metni Girin...">
										<span class="input-group-btn">
											<button class="btn btn-default" type="button">
												Ara!
											</button>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="row">
						  <div class="col-md-12 col-sm-12 col-xs-12">
    						<!-- jQuery -->
    						<script src="objects/js/app/vendors/jquery/dist/jquery.min.js"></script>
    						<!-- Bootstrap -->
    						<script src="objects/js/app/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    						<!-- FastClick -->
    						<script src="objects/js/app/vendors/fastclick/lib/fastclick.js"></script>
    						<!-- NProgress -->
    						<script src="objects/js/app/vendors/nprogress/nprogress.js"></script>
    						<!-- Custom Theme Scripts -->
    						<script src="objects/js/app/build/js/custom.min.js"></script>
    						<!-- validator -->
    						<script src="objects/js/app/vendors/validator/validator.js"></script>
    						<!-- Select2 -->
    						<script src="objects/js/app/vendors/select2/dist/js/select2.full.min.js"></script>
    						<!-- jquery.inputmask -->
    						<script src="objects/js/app/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    						<!-- Parsley -->
    						<script src="objects/js/app/vendors/parsleyjs/dist/parsley.min.js"></script>
    						<!-- PNotify -->
    						<script src="objects/js/app/vendors/pnotify/dist/pnotify.js"></script>
    						<script src="objects/js/app/vendors/pnotify/dist/pnotify.buttons.js"></script>
    						<script src="objects/js/app/vendors/pnotify/dist/pnotify.nonblock.js"></script>
    						<!-- iCheck -->
    						<script src="objects/js/app/vendors/iCheck/icheck.min.js"></script>
    						<!-- Datatables -->
    						<script src="objects/js/app/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    						<script src="objects/js/app/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    						<script src="objects/js/app/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    						<script src="objects/js/app/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    						<script src="objects/js/app/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    						<script src="objects/js/app/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    						<script src="objects/js/app/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    						<!--<script src="objects/js/app/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    						<script src="objects/js/app/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>-->
    						<script src="objects/js/app/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    						<script src="objects/js/app/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    						<script src="objects/js/app/vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
    						<!--<script src="objects/js/app/vendors/jszip/dist/jszip.min.js"></script>
    						<script src="objects/js/app/vendors/pdfmake/build/pdfmake.min.js"></script>
    						<script src="objects/js/app/vendors/pdfmake/build/vfs_fonts.js"></script>-->
    						<script type="text/javascript" src="objects/js/languages/<?= LANG ?>/language.js"></script>
    						<!--<script type="text/javascript" src="objects/js/webim.js"></script>-->
    						<script type="text/javascript" src="objects/js/swfobject.js"></script>
    						<script type="text/javascript" src="modules/app/includes_app.js"></script>
                            
							<div class="mainContent">
								<?=WEBIM_PAGE_CONTENT ?>
							</div>
							
                            <script>
								$(document).ajaxStart(function() {
									$('#load-screen').fadeIn();
								});
								$(document).ajaxComplete(function() {
									$('#load-screen').fadeOut();
								});

								// initialize the validator function
								validator.message.date = 'Tarih hatalı';
								validator.message.empty = 'Boş Olamaz';
								// validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
								$('form').on('blur', 'input[required], input.optional, select.required', validator.checkField).on('change', 'select.required', validator.checkField).on('keypress', 'input[required][pattern]', validator.keypress);
								$('.multi.required').on('keyup blur', 'input', function() {
									validator.checkField.apply($(this).siblings().last()[0]);
								});
								$('form').submit(function(e) {
									e.preventDefault();
									var submit = true;
									// evaluate the form using generic validaing
									if (!validator.checkAll($(this))) {
										submit = false;
									}
									if (submit) this.submit();
									return false;
								});
							</script>
							<!-- jquery.inputmask -->
							<script>
								$(document).ready(function() {
									$(":input").inputmask();
								});
							</script>
							<!-- /jquery.inputmask -->
						  </div>
				        </div>
				</div>
			</div>
            <!-- /page content -->
</body>
</html>
<script type="text/javascript">
	var LHCChatOptions = {};
	LHCChatOptions.opt = {widget_height:340,widget_width:300,popup_height:520,popup_width:500};
	(function() {
	var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
	var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://')+1)) : '';
	var location  = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
	po.src = '//www.ekare.online/support/index.php/tur/chat/getstatus/(click)/internal/(position)/bottom_right/(ma)/br/(top)/350/(units)/pixels/(leaveamessage)/true?r='+referrer+'&l='+location;
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	})();
</script>