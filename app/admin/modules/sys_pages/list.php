<?php
defined("PASS") or die("Dosya yok!");

//Genel Dosya
include_once(dirname(__FILE__).DS."lookups.php");
?>
<script type="text/javascript">
<!--
var f = new formAction();
f.url = url +'&act=';
f.form = 'mainform';
f.contents = {
 'dialog':false,
 'editing':false,
 'listing':false
};
f.onAfterSave = function (){
 f.updateVersion('v_'+getValue('language')+getValue('id',0));
};
f.setMenuList = function (){
	var target = getEl('pageList');
 var AJAX = new ajaxObject('get', this.url+'pages', 'keyword='+getValue('listKeyword'), {type:'LIST',target:target.id,message:LANGUAGE['loading']});
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var error = AJAX.xml.getElementsByTagName('error');

   if (error.length > 0){
    messageDialog(error.item(0).firstChild.data, {type:'OK', icon:'warning.gif'});
   }else{
   	var xml = AJAX.xml.getElementsByTagName('list');
    var result = '';
   
   	if (xml.length > 0){
   	 result += '<table width="100%" border="0" cellpadding="5" cellspacing="0">';
   	 
   	 for (var i=0; i < xml.length; i++){
   	  var icon = xml.item(i).getAttribute('icon');
   	  var caption = xml.item(i).childNodes[0].firstChild.data;
   	  var languages = xml.item(i).childNodes[1].childNodes;
   	  
   	  result += '<tr>'
   	  + '<td colspan="2" class="gridTitle"><img src="'+ icon +'" width="16" height="16" alt="" /> '+ caption +'<\/td>'
   	  + '<\/tr>';
   	  
   	  for (var y=0; y < languages.length; y++){
    	  result += '<tr>'
    	  + '<td class="gridRow" onmouseover="overRow(this,\'gridRowOver\')" onmouseout="overRow(this,\'gridRow\')" onclick="f.getPage('+ xml.item(i).getAttribute('id') +',\''+ languages[y].getAttribute('abbr') +'\')"><b>'+ languages[y].firstChild.data +'<\/b> (v.<span id="v_'+ languages[y].getAttribute('abbr') + xml.item(i).getAttribute('id') +'">'+ languages[y].getAttribute('version') +'<\/span>)<\/td>'
    	  + '<td class="gridRow" onmouseover="overRow(this,\'gridRowOver\')" onmouseout="overRow(this,\'gridRow\')" onclick="f.getPage('+ xml.item(i).getAttribute('id') +',\''+ languages[y].getAttribute('abbr') +'\')" style="text-align:right">'+ (languages[y].getAttribute('shown') ? 'x '+ languages[y].getAttribute('shown') : '<span id="'+ languages[y].getAttribute('abbr') + xml.item(i).getAttribute('id') +'" style="color:#ff0000; font-weight:bold"><?=label("CREATE")?><\/span>') +'<\/td>'
    	  + '<\/tr>';
   	  }
   	 }
   	 
   	 result += '<\/table>';
   	}else{
   	 result += '<div class="warning">'+ LANGUAGE['noResult'] +'<\/div>';
    }
    
    target.innerHTML = result;
   }
  }else{
 	 target.innerHTML = '<div class="warning">'+ LANGUAGE['unknownError'] +'<\/div>';
  }
	}
};
f.getPage = function (id, lang){
 var btnBar = getEl('buttonBar');
 var target = getEl('htmlContent');
 btnBar.style.display = 'none';
 target.innerHTML = '';

 var AJAX = new ajaxObject('get', this.url+'values', 'id='+id+'&language='+lang, {type:'MESSAGE', message:LANGUAGE['loading']});
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var xml = AJAX.xml.getElementsByTagName('value');
   
   if (xml.length > 0){
    var result = '<table width="100%" border="0" cellpadding="5" cellspacing="0">'
    + '<tr>'
    + '<td class="gridLeft" style="width:20%"><label><?=label("PAGE CAPTION")?><\/label><\/td>'
    + '<td class="gridRight"><span id="captionDiv"><\/span><\/td>'
    + '<\/tr>'
    + '<tr>'
    + '<td class="gridLeft"><label><?=label("CONTENT LANGUAGE")?><\/label><\/td>'
    + '<td class="gridRight"><span id="langDiv"><\/span><\/td>'
    + '<\/tr>'
    + '<tr>'
    + '<td colspan="2"><div id="textDiv"><\/div><\/td>'
    + '<\/tr>'
    + '<\/table>';
    
    target.innerHTML = result;
    
    for (var i=0; i < xml.length; i++){
     if (xml.item(i).getAttribute('target')){
      setParam(xml.item(i).getAttribute('target'), xml.item(i).firstChild.data);
      var targetDiv = getEl(xml.item(i).getAttribute('target')+'Div');
      if (targetDiv){
       targetDiv.innerHTML = xml.item(i).firstChild.data;
      }
     }else{
      eval(xml.item(i).firstChild.data);
     }
    }
    
    btnBar.style.display = 'block';
   }else{
    messageDialog(LANGUAGE['noResult'], {type:'OK', icon:'warning.gif'});
   }
  }else{
   messageDialog(LANGUAGE['unknownError'], {type:'OK', icon:'warning.gif'});
  }
 }
};
f.removeCreateText = function (lang, text){
 var target = getEl(lang);
 target.innerHTML = text;
 target.removeAttribute('style');
};
f.updateVersion = function (objID){
 var target = getEl(objID);
 if (target){
  target.innerHTML = parseInt(target.innerHTML)+1;
 }
};
f.showEditor = function (){
 if (<?=menuID("ADMIN_EDITOR")?> > 0){
  var u = '<?=CONF_MAIN_PAGE."?".ADMIN_EXTENSION."&pid=".menuID("ADMIN_EDITOR")?>&target=text';
  window.open(u, 'editor', 'width=800, height=600, scrollbars=no');
 }else{
  messageDialog('<?=label("EDITOR PAGE IS NOT DEFINED")?>', {type:'OK', icon:'warning.gif'});
 }
};

addListener(window,'load',function(){f.setMenuList()});
//-->
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td style="width:300px" valign="top">
      <fieldset>
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
          <tr>
            <td class="gridLeft" style="width:20%"><label for="listKeyword"><?=label("FILTER")?></label></td>
            <td class="gridRight">
              <?=formElement("text", "listKeyword", "", "", "", "onkeypress=\"if((event.keyCode==13)||(event.charCode==13)){f.setMenuList()}\" class=\"searchInput\" style=\"width:180px\"")?>
            </td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <div class="list" id="pageList" style="height:450px"><div class="warning"><?=label("LOADING")?></div></div>
      </fieldset>
    </td>
    <td valign="top">
      <fieldset>
        <div class="list" id="htmlContent" style="height:500px"><div class="warning"><?=label("PICK A PAGE")?></div></div>
        <form id="mainform" method="post" action="#">
          <div style="display:none">
            <?=formElement("hidden", "id", "", 0, "", "")?>
            <?=formElement("hidden", "language", "", DEFAULT_LANGUAGE, "", "")?>
            <?=formElement("hidden", "text", "", "", "", "")?>
          </div>
        </form>
      </fieldset>
      <div id="buttonBar" class="buttonBar" style="display:none">
        <?=formButton("button", "editBtn", label("EDIT"), "", "edit.png", "onclick=\"f.showEditor()\"")?>
        <?=formButton("button", "saveBtn", label("SAVE"), "", "save.png", "onclick=\"f.save()\"")?>
      </div>
    </td>
  </tr>
</table>