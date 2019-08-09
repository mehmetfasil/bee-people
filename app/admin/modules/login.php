<?php
/**
 * @abstract Giriş Sayfası
 */
defined("PASS") or die("Dosya yok!");
?>
<script type="text/javascript">
//<![CDATA[
var url = '<?=CONF_MAIN_PAGE;?>?pid=<?=menuID("SITE_LOGIN");?>';

function checkLogin (){
 if (getValue('user_name').length > 0){
 	var target = getEl('loginresult');
 	target.className = '';
 	
 	var AJAX = new ajaxObject('post', url+'&act=login', getParams('loginform'), {type:'MESSAGE', message:'<?=label("QUERYING")?>', form:'loginform'});
 	AJAX.run();
 	AJAX.onLoad = function (){
 	 if (AJAX.xml){
 	  var xml = AJAX.xml.getElementsByTagName('result');
 	  
 	  if (xml.length > 0){
   		if (xml.item(0).getAttribute('status') == 'OK'){
   		 setParam('user_name', '');
   		 setParam('user_pass', '');
   			formDisabled('loginform', true);
   			
   			window.setTimeout("redirect()", 1500);
   			target.className = 'login_accepted';
   			target.innerHTML = '<?=label("REDIRECTING");?>';
   		}else{
   			target.className = 'login_error';
   			target.innerHTML = xml.item(0).firstChild.data +' ('+ xml.item(0).getAttribute('try') +')';
   			focusOn();
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
	window.location.href = '<?=CONF_MAIN_PAGE."?".ADMIN_EXTENSION;?>';
}

function focusOn (){
 setParam('user_name', '');
 setParam('user_pass', '');
	getEl('user_name').focus();
}

function centerForm (){
	var target = getEl('logindiv');
	var h = getWinH();
	var w = getWinW();
	var rh = (h / 2) - (target.offsetHeight / 2);
	var rw = (w / 2) - (target.offsetWidth / 2);
	target.style.top = (rh > 55 ? rh : 55) +'px'
	target.style.left = rw +'px';
}

addListener(window,'load',function(){
 focusOn();
 centerForm();
});
addListener(window,'resize',function(){
 centerForm();
});
//]]>
</script>

<div id="logindiv">
	<div id="loginresult"><?=label("WELCOME TO ADMINISTRATION CENTRE");?></div>
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
			<tr>
					<td align="center"><img src="<?=ADMIN_FOLDER;?>/templates/<?=CONF_ADMIN_TEMPLATE;?>/images/login.gif" alt="" /></td>
					<td>
							<form id="loginform" method="post"<?=(strpos($_SERVER["HTTP_USER_AGENT"], "MSIE") ? " autocomplete=\"off\"" : "");?> onsubmit="return false" action="#">
									<table width="100%" border="0" cellpadding="5" cellspacing="0" style="border-left:1px solid #dddddd">
											<tr>
													<td style="width:30%" align="right"><label for="user_name"><?=label("USER NAME");?>:</label></td>
													<td>
															<?=formElement("text", "user_name", "", "", "", "style=\"width:200px\" maxlength=\"50\" onkeypress=\"if((event.keyCode==13)||(event.which==13)){return getEl('user_pass').focus()}\"");?>
													</td>
											</tr>
											<tr>
													<td align="right" valign="top"><label for="user_pass"><?=label("USER PASS");?>:</label></td>
													<td>
															<?=formElement("password", "user_pass", "", "", "", "style=\"width:200px\" onkeypress=\"if((event.keyCode==13)||(event.which==13)){checkLogin()}\"");?>
													</td>
											</tr>
											<tr>
													<td>&nbsp;</td>
													<td>
													  <?=formButton("button", "login", label("LOGIN"), "", "unlock.png", "class=\"button\" onclick=\"checkLogin()\"");?>
													</td>
											</tr>
									</table>
							</form>
					</td>
			</tr>
	</table>
</div>
