<?php
defined("PASS") or die("Dosya yok!");

if ($_SESSION["SYS_USER_ID"] > 0){
 echo "<div style=\"text-align:center; font-size:16px;\">Şu anda ".$_SESSION["SYS_USER_FULLNAME"]." olarak bağlısınız</div>";
 echo "<div style=\"text-align:center;\">";
 echo "<ul>\n";
 echo "<li><a href=\"".CONF_MAIN_PAGE."?pid=".menuID("SITE_PMYO")."\">Bağıl Değerlendirme Sistemi</a></li>\n";
 echo "<li><a href=\"".CONF_MAIN_PAGE."?pid=".menuID("SITE_TIM_KURUMSAL")."\">Time Is Money</a></li>\n";
 echo "<li><a href=\"".CONF_MAIN_PAGE."?pid=".menuID("SITE_SEPETT")."\">Sepet</a></li>\n";
 echo "</ul>\n";
 echo "</div>";
}else{
?>
<script type="text/javascript">
//<![CDATA[
var url = '<?=CONF_MAIN_PAGE;?>?pid=<?=menuID("SITE_LOGIN");?>';

function checkLogin (){
 if (getValue('user_name').length > 0){
 	
 	var AJAX = new ajaxObject('post', url+'&act=login', getParams('loginform'), {type:'MESSAGE', message:'<?=label("QUERYING")?>', form:'loginform'});
 	AJAX.run();
 	AJAX.onLoad = function (){
 	 if (AJAX.xml){
 	  var xml = AJAX.xml.getElementsByTagName('result');
 	  
 	  if (xml.length > 0){
   		if (xml.item(0).getAttribute('status') == 'OK'){   			
   			redirect();
   		}else{
   			messageDialog(xml.item(0).firstChild.data, {type:'OK', icon:'caution.gif', functionOK:focusOn});
   		}
 	  }else{
   		messageDialog('<?=label("RETURNED NO RESULT");?>', {type:'OK', icon:'warning.gif', title:'<?=label("ERROR")?>', functionOK:'focusOn()'});
 	  }
 	 }else{
  		messageDialog('<?=label("AN ERROR OCCURED");?>', {type:'OK', icon:'warning.gif', title:'<?=label("ERROR")?>', functionOK:'focusOn()'});
 	 }
 	}
 }else{
  messageDialog('<?=label("USER NAME MUST ENTERED");?>', {type:'OK', icon:'caution.gif', title:'<?=label("ERROR")?>', functionOK:'focusOn()'});
 }
}

function redirect (){
	window.location.href = '<?=CONF_MAIN_PAGE;?>';
}

function focusOn (){
 setParam('user_name', '');
 setParam('user_pass', '');
	getEl('user_name').focus();
}

wolAdd('focusOn()');
//]]>
</script>

<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td style="height:400px; text-align:center">
      <form id="loginform" method="post" action="#">
      <table style="margin:auto; border:2px solid #dddddd; width:400px; background-color:#ffffff" border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td colspan="2" style="padding:10px; background-color:#f2f2f2">Giriş yapmak için kullanıcı adı ve parolanızı kullanınız</td>
        </tr>
        <tr>
          <td style="width:35%; text-align:right"><label for="user_name">Kullanıcı Adınız</label></td>
          <td style="text-align:left">
            <?=formElement("text", "user_name", "", "", "", "style=\"width:200px\" maxlength=\"50\"")?>
          </td>
        </tr>
        <tr>
          <td style="text-align:right"><label for="user_pass">Parolanız</label></td>
          <td style="text-align:left">
            <?=formElement("password", "user_pass", "", "", "", "style=\"width:200px\"")?>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td style="text-align:left">
            <?=formButton("button", "loginBtn", "Giriş Yap", "", "decrypted.png", "onclick=\"checkLogin()\"")?>
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
</table>
<?
}
?>