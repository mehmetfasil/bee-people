<?php

defined("PASS") or die(label("Dosya Yok !"));

//Menu
include_once(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.quick.menu.php");
include_once(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.menu.php");

//Menu
$menu = new qmenu($_SESSION["SYS_USER_ID"], $_SESSION["SYS_USER_LEVEL"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANG?>" lang="<?=LANG?>">
<head>
<title><?=WEBIM_PAGE_TITLE." | ".CONF_SYSTEM_TITLE?></title>
<meta http-equiv="Content-type" content="text/html; charset=<?=LANGUAGE_CHARSET?>" />
<?=config("CONF_PAGE_HEADER","")?>

<link type="text/css" href="templates/<?=WEBIM_PAGE_TEMPLATE;?>/style.css" rel="stylesheet" />
<link type="text/css" href="templates/<?=WEBIM_PAGE_TEMPLATE?>/quickmenu/quick_menu_style.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="objects/js/jquery/theme/jquery.lightbox-0.5.css" media="screen" />
<script type="text/javascript" src="objects/js/jquery/core.js"></script>
<!-- quick menu-->
<script type="text/javascript" src="templates/<?=CONF_SITE_TEMPLATE?>/quickmenu/quick_menu.js"></script>
<script type="text/javascript" src="objects/js/languages/<?=LANG?>/language.js"></script>
<script type="text/javascript" src="objects/js/webim.js"></script>
<script type="text/javascript" src="objects/js/swfobject.js"></script>
<script type="text/javascript" src="objects/js/jquery/core.js"></script>

<script language="JavaScript" type="text/javascript">
	var url = '<?=CONF_MAIN_PAGE."?pid=".PID."&sid=query"?>'
</script>

</head>

<body>
	
	<div class="container">
	<div id="menuBar">
		<div class="topMenuLeft"></div>
		<div class="topMenuCenter">
			<div id="menuBarTop">
			</div>
			<div id="menuBarMenus">			
<!-- Create Menu Settings: (Menu ID, Is Vertical, Show Timer, Hide Timer, On Click (options: 'all' * 'all-always-open' * 'main' * 'lev2'), Right to Left, Horizontal Subs, Flush Left, Flush Top) -->
<script type="text/javascript">
$(document).ready(function(){
	qm_create(0,false,0,500,false,false,false,false,false);
});
</script>
	<ul id="qm0" class="qmmc">
		<li><a title="<?=label("Ana Sayfa")?>" href="<?=CONF_MAIN_PAGE?>"><?=label("Ana Sayfa")?></a> </li>
		<?=$menu->qgetMenus("site",menuID("SITE_PORTAL"),true)?>

		<? if(!empty($_SESSION["SYS_USER_ID"])){?>
				<li><a title="<?=label("Çıkış")?>" href="<?=CONF_MAIN_PAGE."?logout=OK"?>"><?=label("Çıkış")?></a> </li>
		<?
			}
		?>
		<li class="qmclear">&nbsp;</li>				
	</ul>
</div>
</div>

</div>
  	
</div> 
</body>
</html>

