<?php
defined("PASS") or die("Dosya yok!");

//Menü
include_once(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.menu.php");

//Menü
$menu = new menu($_SESSION["SYS_USER_ID"], $_SESSION["SYS_USER_LEVEL"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANG?>" lang="<?=LANG?>">
<head>
<title><?=WEBIM_PAGE_TITLE?></title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta name="author" content="<?=AUTHOR;?>" />

<link type="text/css" href="<?=ADMIN_FOLDER?>/templates/<?=WEBIM_PAGE_TEMPLATE?>/menu.css" rel="stylesheet" />
<link type="text/css" href="<?=ADMIN_FOLDER?>/templates/<?=WEBIM_PAGE_TEMPLATE?>/calendar.css" rel="stylesheet" />
<link type="text/css" href="<?=ADMIN_FOLDER?>/templates/<?=WEBIM_PAGE_TEMPLATE?>/common.css" rel="stylesheet" />
<link type="text/css" href="<?=ADMIN_FOLDER?>/templates/<?=WEBIM_PAGE_TEMPLATE?>/style.css" rel="stylesheet" />
<link type="text/css" href="<?=ADMIN_FOLDER?>/templates/<?=WEBIM_PAGE_TEMPLATE?>/tree/tree.css" rel="stylesheet" />
<link type="text/css" media="all" href="<?=ADMIN_FOLDER?>/templates/<?=WEBIM_PAGE_TEMPLATE?>/calendar/calendar-win2k-cold-2.css" title="green" rel="stylesheet" />
<script type="text/javascript" src="objects/js/jquery/core.js"></script>
<script type="text/javascript" src="objects/js/languages/<?=LANG?>/language.js"></script>
<script type="text/javascript" src="objects/js/webim.js"></script>
<script type="text/javascript" src="objects/js/menu.js"></script>
<script type="text/javascript">
<!--
var url = '<?=CONF_MAIN_PAGE."?".ADMIN_EXTENSION."&pid=".PID."&sid=query"?>';
//-->
</script>

</head>
<body>
<div id="header">
  <div id="webim_logo">&nbsp;</div>
  <div class="webim_title_shadow"><?=label("WEBIM ADMINISTRATION CENTRE")?></div>
  <div class="webim_title"><?=label("WEBIM ADMINISTRATION CENTRE")?></div>
  <div class="date"><?=showDate()?></div>
</div>
<?
if ($_SESSION["SYS_USER_LEVEL"] > 1) {
?>
<div id="menuBar"></div>

<script type="text/javascript">
<!--
var myMenu =
[[null, '<span style="font-weight:bold;"><?=label("HOMEPAGE");?><\/span>','<?=CONF_MAIN_PAGE."?".ADMIN_EXTENSION;?>', '_self', '<?=label("HOMEPAGE");?>'],
<?=$menu->getMenus("admin", menuID("SYS_MENU_ADMIN"));?>,
[null, '<span style="font-weight:bold; color:#ff0000"><?=label("LOGOUT");?><\/span>', '<?=CONF_MAIN_PAGE."?".ADMIN_EXTENSION."&logout";?>', '_self', '<?=label("LOGOUT");?>']];

cmDraw('menuBar', myMenu, 'hbr', cmTheme, 'Theme');
//-->
</script>

<div id="submenuBar">
 <div class="menuList"><?=$menu->getSubMenus(PID)?>
 </div>
 <div class="title">
   <?=(empty($_GET["pid"]) ? label("HOMEPAGE") : WEBIM_PAGE_CAPTION);?>
   <img src="<?=ADMIN_FOLDER;?>/templates/_default/images/title.png" width="16" height="16" alt="" />
 </div>
</div>
<?
}
?>
<div id="body">
<?=WEBIM_PAGE_CONTENT;?>
</div>
<div id="footer">
  <div id="copyright"><?="&copy; 2019-".date("Y")." Mehmet FASIL "?></div>
  <div id="version">v.<?=VERSION;?></div>
</div>
</body>
</html>
