<?php
defined("PASS") or die("Dosya yok!");

$sys_users = array("&nbsp;");

$pages = getList("`sys_menus`", "`id`, `caption`", "`active`='1' AND `type`='site' AND `link` IS NOT NULL AND `visibility`='visible'", "`caption`", "&nbsp;", CONN);
foreach ($pages as $id=>$value){
 $pages[$id] = label($value);
}
?>
<script type="text/javascript">
//<![CDATA[
var f = new formAction();
f.url = url+'&act=';
f.form = 'mainform';
f.contents = {
 'dialog':false,
 'editing':false,
 'listing':false,
 'deleting':false
};
f.onValues = function (){
 getEl('saveBtn').disabled = false;
}
f.checkSYSUser = function (){
 var target = getEl('SYSTEM_USER');
 var btn = getEl('sysuserBtn');
 
 clearList(target);
 var AJAX = new ajaxObject('get', this.url+'check', null);
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var error = AJAX.xml.getElementsByTagName('error');

   if (error.length > 0){
    messageDialog(error.item(0).firstChild.data, {type:'OK', icon:'warning.gif'});
   }else{
    var xml = AJAX.xml.getElementsByTagName('list');
    if (xml.length > 0){
     for (var i=0; i < xml.length; i++){
      listOptions(target,xml.item(i).firstChild.data,xml.item(i).getAttribute('id'));
     }
    }else{
     btn.disabled = false;
     btn.style.display = 'inline';
    }
   }
  }
 }
};
f.createSYSUser = function (){
 var target = getEl('SYSTEM_USER');
 var btn = getEl('sysuserBtn');

 target.disabled = true;

 var AJAX = new ajaxObject('post', this.url+'create', '');
 AJAX.run();
 AJAX.onLoad = function (){
  target.disabled = false;

  if (AJAX.xml){
   var error = AJAX.xml.getElementsByTagName('error');

   if (error.length > 0){
    messageDialog(error.item(0).firstChild.data, {type:'OK', icon:'warning.gif'});
   }else{
    var xml = AJAX.xml.getElementsByTagName('result');

    if (xml.length > 0){
     if (xml.item(0).getAttribute('status') == 'OK'){
      btn.style.display = 'none';
      btn.disabled = true;
      clearList(target);
      listOptions (target, xml.item(0).firstChild.data, xml.item(0).getAttribute('id'));
     }else{
      messageDialog(xml.item(0).firstChild.data, {type:'OK', icon:'caution.gif'});
     }
    }else{
     messageDialog(LANGUAGE['noReturn'], {type:'OK', icon:'warning.gif'});
    }
   }
  }else{
   messageDialog(LANGUAGE['unknownError'], {type:'OK', icon:'warning.gif'});
  }
 }
};

addListener(window,'load',function(){f.values(0)});
//]]>
</script>

<form id="mainform" method="post" onsubmit="return false" action="#">
<fieldset><legend><?=label("COMMON CONFIG")?></legend>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td class="gridLeft" style="width:25%"><label for="SYSTEM_NAME"><?=label("SYSTEM NAME");?></label></td>
    <td class="gridRight">
      <?=formElement("text", "SYSTEM_NAME", "", "", "", "style=\"width:400px;\" maxlength=\"250\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="SYSTEM_TITLE"><?=label("SYSTEM TITLE");?></label></td>
    <td class="gridRight">
      <?=formElement("text", "SYSTEM_TITLE", "", "", "", "style=\"width:400px;\" maxlength=\"250\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="SYSTEM_ADDRESS"><?=label("SYSTEM ADDRESS");?></label></td>
    <td class="gridRight">
      <?=formElement("text", "SYSTEM_ADDRESS", "", "", "", "style=\"width:400px;\" maxlength=\"250\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft" valign="top"><label for="KEYWORDS"><?=label("KEYWORDS");?></label></td>
    <td class="gridRight">
      <?=formElement("textarea", "KEYWORDS", "", "", "", "style=\"width:400px; height:50px\"")?>
      <i><?=label("SEPERATOR IS COMMA");?> (,)</i>
    </td>
  </tr>
  <tr>
    <td class="gridLeft" valign="top"><label for="DESCRIPTION"><?=label("DESCRIPTION");?></label></td>
    <td class="gridRight">
      <?=formElement("textarea", "DESCRIPTION", "", "", "", "style=\"width:400px; height:50px\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="SYSTEM_HOME_PAGE"><?=label("DEFAULT HOMEPAGE");?></label></td>
    <td class="gridRight">
      <?=formElement("select", "SYSTEM_HOME_PAGE", $pages, "", "", "style=\"width:400px;\"")?>
    </td>
  </tr>
</table>
</fieldset>
<fieldset><legend><?=label("MANAGEMENT CONFIG")?></legend>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td class="gridLeft" style="width:25%"><label for="SYSTEM_ADMIN"><?=label("SYSTEM ADMIN");?></label></td>
    <td class="gridRight">
      <?=formElement("text", "SYSTEM_ADMIN", "", "", "", "style=\"width:400px;\" maxlength=\"250\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="SYSTEM_ADMIN_MAIL"><?=label("SYSTEM ADMIN MAIL");?></label></td>
    <td class="gridRight">
      <?=formElement("text", "SYSTEM_ADMIN_MAIL", "", "", "", "style=\"width:400px;\" maxlength=\"250\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="SYSTEM_USER"><?=label("SYSTEM USER");?></label></td>
    <td class="gridRight">
      <?=formElement("select", "SYSTEM_USER", $sys_users, "", "", "style=\"width:400px;\"")?>
      <?=formButton("button", "sysuserBtn", label("CREATE"), "disabled", "apply.png", "onclick=\"f.createSYSUser();\" class=\"button\" style=\"display:none\"");?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="ADMIN_TEMPLATE"><?=label("ADMIN TEMPLATE");?></label></td>
    <td class="gridRight">
      <?=formElement("select", "ADMIN_TEMPLATE", getFolders(ADMIN_EXTENSION."/templates/"),"", "", "")?>
    </td>
  </tr>
</table>
</fieldset>
<fieldset><legend><?=label("SYSTEM CONFIG")?></legend>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td class="gridLeft" valign="top" style="width:25%"><label for="PAGE_HEADER"><?=label("PAGE HEADER");?></label></td>
    <td class="gridRight">
      <?=formElement("textarea", "PAGE_HEADER", "", "", "", "style=\"width:400px; height:50px\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft" valign="top"><label for="PAGE_FOOTER"><?=label("PAGE FOOTER");?></label></td>
    <td class="gridRight">
      <?=formElement("textarea", "PAGE_FOOTER", "", "", "", "style=\"width:400px; height:50px\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="SYSTEM_DEBUG_MODE"><?=label("DEBUG MODE");?></label></td>
    <td class="gridRight">
      <?=formElement("checkbox", "SYSTEM_DEBUG_MODE", "1", "0", "", "")?> <label for="SYSTEM_DEBUG_MODE"><?=label("CHECKED AS TRUE")?></label>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="online"><?=label("SYSTEM STATUS");?></label></td>
    <td class="gridRight">
      <?=formElement("radio", "SYSTEM_STATUS", "online", "online", "", "", "online")?> <label for="online"><?=label("ONLINE")?></label> &nbsp;&nbsp;
      <?=formElement("radio", "SYSTEM_STATUS", "offline", "online", "", "", "offline")?> <label for="offline"><?=label("OFFLINE")?></label>
    </td>
  </tr>
  <tr>
    <td class="gridLeft" valign="top"><label for="OFFLINE_MESSAGE"><?=label("OFFLINE MESSAGE");?></label></td>
    <td class="gridRight">
      <?=formElement("textarea", "OFFLINE_MESSAGE", "", "", "", "style=\"width:400px; height:50px\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="SESSION_TIMELIMIT"><?=label("SESSION TIMELIMIT");?></label></td>
    <td class="gridRight">
      <?=formElement("text", "SESSION_TIMELIMIT", "", 0, "", "style=\"width:30px;\" maxlength=\"3\" onkeypress=\"return onlyNum(event)\"")?>
      <?=label("MINUTE");?>
      <i>(<?=label("UNLIMITED IF NULL");?>)</i>
    </td>
  </tr>
  <tr>
    <td class="gridLeft" valign="top"><label for="SESSION_TIMELIMIT_MESSAGE"><?=label("SESSION TIMELIMIT MESSAGE");?></label></td>
    <td class="gridRight">
      <?=formElement("textarea", "SESSION_TIMELIMIT_MESSAGE", "", "", "", "style=\"width:400px; height:50px\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="SITE_TEMPLATE"><?=label("SITE TEMPLATE");?></label></td>
    <td class="gridRight">
      <?=formElement("select", "SITE_TEMPLATE", getFolders("templates/"), "", "", "")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="MAIN_PAGE"><?=label("MAIN PAGE FILE");?></label></td>
    <td class="gridRight">
      <?=formElement("text", "MAIN_PAGE", "", "index.php", "", "style=\"width:400px;\"")?>
    </td>
  </tr>
</table>
</fieldset>
<div class="buttonBar">
<?=formButton("button", "saveBtn", label("SAVE"), "disabled", "save.png", "onclick=\"f.save();\"");?>
</div>
</form>