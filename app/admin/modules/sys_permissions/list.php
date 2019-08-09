<?php
defined("PASS") or die("Dosya yok!");

//Genel Dosya
include_once(dirname(__FILE__).DS."lookups.php");
?>
<script type="text/javascript" src="objects/js/tree/treemenu.js"></script>
<script type="text/javascript" src="objects/js/tree/treeconfig.js"></script>

<script type="text/javascript">
//<![CDATA[
var tree;
var f = new formAction();
f.url = url +'&act=';
f.contents = {
 'dialog':false,
 'editing':false,
 'deleting':false,
 'listing':true
};
f.getMenuList = function (){
	var target = getEl('menuList');
 var AJAX = new ajaxObject('get', this.url+'menus', 'type='+(getValue('site')=='site'?'site':'admin'), {type:'LIST',target:'menuList',message:LANGUAGE['loading']});
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var error = AJAX.xml.getElementsByTagName('error');

   if (error.length > 0){
    messageDialog(error.item(0).firstChild.data, {type:'OK', icon:'warning.gif'});
   }else{
   	var xml = AJAX.xml.getElementsByTagName('list');
   
   	if (xml.length > 0){
   		tree = new treemenu('tree', true, true, true);
   
   		for (var i=0; i < xml.length; i++){
   			var parent = '';
   			for (var y=0; y < xml.item(i).getAttribute('level'); y++){
   				parent += ' ';
   			}
   			parent += xml.item(i).firstChild.data;
   
   			if (xml.item(i).getAttribute('level') > 0){
   				var open = 0;
   			}else{
   				var open = 1;
   			}
   
   			tree.put(open, parent, '', 'javascript:void(0)" ondblclick="f.getPageList('+ xml.item(i).getAttribute('id') +');', '', '');
   		}
   		
   		target.innerHTML = tree;
   	}else{
   	 target.innerHTML = '<div class="warning">'+ LANGUAGE['noResult'] +'<\/div>';
    }
   }
  }else{
 	 target.innerHTML = '<div class="warning">'+ LANGUAGE['unknownError'] +'<\/div>';
  }
	}
};
f.getObjectList = function (){
 var target = getEl('objectList');
 var result = '';
 var params = 'users='+ getValue('listUsers') 
 + '&groups='+ getValue('listGroups') 
 + '&level='+ getValue('listLevel')
 + '&keyword='+ encodeURIComponent(getValue('listKeyword'));
 
 var AJAX = new ajaxObject('get', this.url+'objects', params, {type:'LIST', message:LANGUAGE['loading'], target:target.id});
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var error = AJAX.xml.getElementsByTagName('error');

   if (error.length > 0){
    messageDialog(error.item(0).firstChild.data, {type:'OK', icon:'warning.gif'});
   }else{
    var xml = AJAX.xml.getElementsByTagName('list');
    
    if (xml.length > 0){
     result += '<table width="100%" border="0" cellpadding="5" cellspacing="0">'
     + '<tr>'
     + '<td class="gridTitle" style="width:3%; text-align:center"><input type="checkbox" id="oall" name="oall" value="1" onclick="f.setObjectCheck(this)"><\/td>'
     + '<td class="gridTitle" style="width:15%; text-align:center"><?=label("OBJECT TYPE")?><\/td>'
     + '<td class="gridTitle" style="width:25%"><?=label("USER NAME")?><\/td>'
     + '<td class="gridTitle"><?=label("USER FULLNAME")?><\/td>'
     + '<td class="gridTitle" style="width:15%; text-align:center"><?=label("USER LEVEL")?><\/td>'
     + '<\/tr>';
   
     for (var i=0; i < xml.length; i++){
      result += '<tr>'
      + '<td class="gridRow" style="text-align:center"><input type="checkbox" id="o'+ xml.item(i).getAttribute('id') +'" name="o'+ xml.item(i).getAttribute('id') +'" value="'+ xml.item(i).getAttribute('id') +'" onclick="f.setObjectCheck(this)"><\/td>'
      + '<td class="gridRow" style="text-align:center"><label for="o'+ xml.item(i).getAttribute('id') +'"><img src="objects/icons/16x16/'+ xml.item(i).getAttribute('type') +'.png" width="16" height="16" alt="" /><\/label><\/td>'
      + '<td class="gridRow"><label for="o'+ xml.item(i).getAttribute('id') +'">'+ xml.item(i).childNodes[0].firstChild.data +'<\/label><\/td>'
      + '<td class="gridRow"><label for="o'+ xml.item(i).getAttribute('id') +'">'+ xml.item(i).childNodes[1].firstChild.data +'<\/label><\/td>'
      + '<td class="gridRow" style="text-align:center">'
      if (parseInt(xml.item(i).getAttribute('level')) > 0){
       for (var z=0; z < parseInt(xml.item(i).getAttribute('level')); z++){
        result += '<img src="objects/icons/16x16/star.png" width="16" height="16" alt="" />';
       }
      }else{
       result += '&nbsp;';
      }
      result += '<\/td>'
      + '<\/tr>';
     }
   
     result += '<\/table>';
    }else{
     result += '<div class="warning">'+ LANGUAGE['noResult'] +'<\/div>';
    }
   }
  }else{
   result += '<div class="warning">'+ LANGUAGE['unknownError'] +'<\/div>';
  }
 	target.innerHTML = result;
 }
 //Sıfırlayalım
 this.setObjectCheck();
};
f.getPagePermissions = function (){
 if (this.getPageIDs('value').length > 0){
  this.checkUncheckAllPages('uncheck');
  var params = 'objects='+ this.getObjectIDs('value',true).join(',')
  + '&pages='+ this.getPageIDs('value').join(',');
  var AJAX = new ajaxObject('get', this.url+'permissions', params, {type:'MESSAGE', message:LANGUAGE['loading']});
  AJAX.run();
  AJAX.onLoad = function (){
   if (AJAX.xml){
    var xml = AJAX.xml.getElementsByTagName('list');
    
    if (xml.length > 0){
     for (var i=0; i < xml.length; i++){
      setParam(xml.item(i).getAttribute('target'), xml.item(i).firstChild.data);
     }
    }
   }
  }
 }
};
f.getPageList = function (id){
 var buttons = getEl('buttons');
 var target = getEl('pageList');
 buttons.style.visibility = 'hidden';
 var result = '';
 
 var AJAX = new ajaxObject('get', this.url+'pages', 'mid='+id+'&objects='+ this.getObjectIDs('value',true).join(','), {type:'LIST', message:LANGUAGE['loading'], target:target.id});
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var error = AJAX.xml.getElementsByTagName('error');

   if (error.length > 0){
    messageDialog(error.item(0).firstChild.data, {type:'OK', icon:'warning.gif'});
   }else{
    var xml = AJAX.xml.getElementsByTagName('fulllist');
 
    if (xml.length > 0){
     result += '<table width="100%" border="0" cellpadding="0" cellspacing="0">'
     + '<tr>'
     + '<td class="gridTitle" style="padding:5px"><?=label("PAGE CAPTION")?><\/td>'
     + '<td class="gridTitle" style="width:30%; text-align:center"><?=label("PAGE PERMISSIONS")?><\/td>'
     + '<td class="gridTitle" style="width:30%; text-align:center"><?=label("PAGE RESTRICTIONS")?><\/td>'
     + '<\/tr>'
     + '<tr>'
     + '<td id="childs0" colspan="3">'
     + f.parseList(xml.item(0),0,0,xml.item(0).childNodes[0].childNodes.length)
     + '<\/td>'
     + '<\/tr>'
     + '<\/table>';
     
     buttons.style.visibility = 'visible';
    }else{
     result += '<div class="warning"><?=label("PAGE NOT FOUND")?><\/div>';
    }
   }
  }else{
   result += '<div class="warning">'+ LANGUAGE['unknownError'] +'<\/div>';
  }
  target.innerHTML = result;
 }
};
f.parseList = function (list, level, par, total){
 level++;
 var result = '';
 
 if (list.childNodes.length > 0){
  result += '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
  
  for (var i=0; i < list.childNodes.length; i++){
   var imgs = '';
   for (var y=0; y < level; y++){
    if (i == total-1){
     imgs += '<img src="objects/icons/tree/tieline.gif" border="0" align="left" alt="">';
    }else{
     imgs += '<img src="objects/icons/tree/tieline.gif" border="0" align="left" alt="">';
    }
   }
   
   var id = list.childNodes[i].getAttribute('id');
   var cp = list.childNodes[i].childNodes[1].firstChild.data;
   var lv = parseInt(list.childNodes[i].childNodes[2].firstChild.data);
   var pr = list.childNodes[i].childNodes[3].firstChild.data;
   var rr = list.childNodes[i].childNodes[4].firstChild.data;
 
   result += '<td class="gridRow">'+ imgs +'<label for="pr'+ id +'"><span style="margin-left:5px; line-height:20px">'+ cp +'<\/span><\/label><\/td>'
   + '<td class="gridRow" style="width:30%; text-align:center">'
   + '<label for="pr'+ id +'">R:<\/label> '
   + '<input type="checkbox" id="pr'+ id +'" name="pr'+ id +'" value="1"'+ (pr.substr(0,1) == '1' ? ' checked="checked"' : '') +' onclick="f.setPageCheck(this, '+ id +', \''+ level +'-'+ par +'\');"'+ (lv == 0 ? '' : '') +'> &nbsp; '
   + '<label for="pw'+ id +'">W:<\/label> '
   + '<input type="checkbox" id="pw'+ id +'" name="pw'+ id +'" value="1"'+ (pr.substr(1,1) == '1' ? ' checked="checked"' : '') +' onclick="f.setPageCheck(this, '+ id +', \''+ level +'-'+ par +'\');"'+ (lv == 0 ? '' : '') +'> &nbsp; '
   + '<label for="pd'+ id +'">D:<\/label> '
   + '<input type="checkbox" id="pd'+ id +'" name="pd'+ id +'" value="1"'+ (pr.substr(2,1) == '1' ? ' checked="checked"' : '') +' onclick="f.setPageCheck(this, '+ id +', \''+ level +'-'+ par +'\');"'+ (lv == 0 ? '' : '') +'> &nbsp; '
   + '<label for="pu'+ id +'">U:<\/label> '
   + '<input type="checkbox" id="pu'+ id +'" name="pu'+ id +'" value="1"'+ (pr.substr(3,1) == '1' ? ' checked="checked"' : '') +' onclick="f.setPageCheck(this, '+ id +', \''+ level +'-'+ par +'\');"'+ (lv == 0 ? '' : '') +'> &nbsp; '
   + '<\/td>'
   + '<td class="gridRow" style="width:30%; text-align:center">'
   + '<label for="rr'+ id +'">R:<\/label> '
   + '<input type="checkbox" id="rr'+ id +'" name="rr'+ id +'" value="1"'+ (rr.substr(0,1) == '1' ? ' checked="checked"' : '') + (lv == 0 ? '' : '') +'> &nbsp; '
   + '<label for="rw'+ id +'">W:<\/label> '
   + '<input type="checkbox" id="rw'+ id +'" name="rw'+ id +'" value="1"'+ (rr.substr(1,1) == '1' ? ' checked="checked"' : '') + (lv == 0 ? '' : '') +'> &nbsp; '
   + '<label for="rd'+ id +'">D:<\/label> '
   + '<input type="checkbox" id="rd'+ id +'" name="rd'+ id +'" value="1"'+ (rr.substr(2,1) == '1' ? ' checked="checked"' : '') + (lv == 0 ? '' : '') +'> &nbsp; '
   + '<label for="ru'+ id +'">U:<\/label> '
   + '<input type="checkbox" id="ru'+ id +'" name="ru'+ id +'" value="1"'+ (rr.substr(3,1) == '1' ? ' checked="checked"' : '') + (lv == 0 ? '' : '') +'> &nbsp; '
   + '<\/td>'
   + '<\/tr>';
   if (list.childNodes[i].childNodes[5]){
    result += '<tr>'
    + '<td colspan="3" id="childs'+ par +'-'+ list.childNodes[i].getAttribute('id') +'">'
    + f.parseList(list.childNodes[i].childNodes[5],level,list.childNodes[i].getAttribute('id'), total)
    + '<\/td>'
    + '<\/tr>';
   }
  }
  result += '<\/table>';
 }
 return result;
};
f.getObjectIDs = function (iv, onlyChecked){
 var target = getEl('objectList');
 var ids = new Array();
 var els = target.getElementsByTagName('input');
 for (var i=0; i < els.length; i++){
  if ((els[i].type == 'checkbox') && (els[i].disabled != true) && (els[i].id != 'oall')){
   if (onlyChecked){
    if (els[i].checked == true){
     ids.push((iv=='value') ? els[i].value : els[i].id);
    }
   }else{
    ids.push((iv=='value') ? els[i].value : els[i].id);
   }
  }
 }
 return ids;
};
f.setObjectCheck = function (obj){
 var chked = this.getObjectIDs('id',true);
 var chks = this.getObjectIDs('id');
 
 for (var i=0; i < chks.length; i++){
  this.selObject(getEl(chks[i]));
 }
 
 if (obj && (obj.id == 'oall')){
  for (var i=0; i < chks.length; i++){
   getEl(chks[i]).checked = obj.checked ? true : false;
   this.selObject(getEl(chks[i]));
  }
 }else{
  var oall = getEl('oall');
  if (oall){
   oall.checked = (chked.length == chks.length) ? true : false;
  }
 }
 
 //Seçililer
 this.setSelectedObjects();
};
f.selObject = function (obj){
 var tds = obj.parentNode.parentNode.getElementsByTagName('td');
 for (var i=0; i < tds.length; i++){
  tds[i].className = obj.checked ? 'rowSelected' : 'gridRow';
 }
};
f.setSelectedObjects = function (){
 var ids = this.getObjectIDs('id',true);
 var target = getEl('objectName');
 var result = '';
 
 if (ids.length > 0){
  if (ids.length == 1){
   result += '('+ getEl(ids[0]).parentNode.nextSibling.nextSibling.innerHTML +') '
   + getEl(ids[0]).parentNode.nextSibling.nextSibling.nextSibling.innerHTML;
  }else{
   result += '<b>'+ ids.length +'<\/b> <?=label("OBJECTS ARE AUTHORIZING")?>';
  }  
 }else{
  result += '<img src="objects/icons/16x16/caution.png" width="16" height="16" alt="" /> <?=label("PLEASE CHOOSE AN OBJECT")?>';
 }
 
 target.innerHTML = result;
 
 var pids = this.getPageIDs('id');
 if (pids.length > 0){
  this.getPagePermissions();
 }
};
f.checkUncheckAllPages = function (act){
 var ids = this.getPageIDs('id');

 for(i=0; i < ids.length; i++){
  if (ids[i].substr(0,1) != 'r'){
   getEl(ids[i]).checked = (act=='check') ? true : false;
  }
 }
};
f.getPageIDs = function (iv, onlyChecked){
 var target = getEl('pageList');
 var ids = [];
 var els = target.getElementsByTagName('input');
 for (var i=0; i < els.length; i++){
  if ((els[i].type == 'checkbox') && !els[i].disabled){
   if (onlyChecked){
    if (els[i].checked){
     ids.push((iv=='value') ? els[i].value : els[i].id);
    }
   }else{
    if (iv=='value'){
     if (els[i].id.indexOf('pr')==0){
      ids.push(els[i].id.substr(2));
     }
    }else if (iv=='id'){
     ids.push(els[i].id);
    }else{
     if (els[i].id.indexOf('pr')==0){
      var id = els[i].id.replace('pr','');
      var pr = els[i].checked ? '1' : '0';
      var pw = getEl('pw'+id).checked ? '1' : '0';
      var pd = getEl('pd'+id).checked ? '1' : '0';
      var pu = getEl('pu'+id).checked ? '1' : '0';
      var rr = getEl('rr'+id).checked ? '1' : '0';
      var rw = getEl('rw'+id).checked ? '1' : '0';
      var rd = getEl('rd'+id).checked ? '1' : '0';
      var ru = getEl('ru'+id).checked ? '1' : '0';
      ids.push(id+'-'+pr+pw+pd+pu+rr+rw+rd+ru);
     }      
    }
   }
  }
 }
 return ids;
};
f.setPageCheck = function (c, id, level){
 var pr = getEl('pr' + id);
 var pw = getEl('pw' + id);
 var pd = getEl('pd' + id);
 var pu = getEl('pu' + id);
   
 var raw = level.split('-');
 var lev = parseInt(raw[0]);
 var par = parseInt(raw[1]);

 if((c!=pr) & (c.checked)){
  pr.checked = true;  
 }else{
  if(!pr.checked){
   pw.checked = false;
   pd.checked = false;  
   pu.checked = false;  
  }
 }
 
 if (pr.checked == true){
  var arr = this.findTopPageIDs(par);
  for (var i=0; i < arr.length; i++){
   var c1 = getEl('pr'+ arr[i]);
   if (c1){
    c1.checked = true;
   }
  }
 }else{
  var arr = this.findTopPageIDs(id);
  var target = getEl('childs'+ arr.join('-'));
  
  if (target){
   var els = target.getElementsByTagName('input');
   for (var i=0; i < els.length; i++){
    if ((els[i].type == 'checkbox') && !els[i].disabled && (els[i].id.substr(0,1) != 'r')){
     els[i].checked = false;
    }
   }
  }
 }
};
f.findTopPageIDs = function (id){
 var arr = [];
 var target = getEl('pageList');
 if (target){
  var tds = target.getElementsByTagName('td');
  for (var i=0; i < tds.length; i++){
   if (tds[i].id && (tds[i].id.indexOf('childs')>-1)){
    var ids = tds[i].id.replace('childs', '');
    var raw = ids.split('-');
    
    if (raw[raw.length-1] == id){
     for (var y in raw){
      arr.push(raw[y]);
     }
     break;
    }
   }
  }
 }
 return arr;
};
f.save = function (){
 if (this.getObjectIDs('value',true).length > 0){
  var params = 'objects='+ this.getObjectIDs('value',true).join(',')
  + '&pages='+ this.getPageIDs('all').join(',');
  
  var AJAX = new ajaxObject('post', this.url+'save', params, {type:'MESSAGE', message:LANGUAGE['saving']});
  AJAX.run();
  AJAX.onLoad = function (){
   if (AJAX.xml){
    var error = AJAX.xml.getElementsByTagName('error');
 
    if (error.length > 0){
     messageDialog(error.item(0).firstChild.data, {type:'OK', icon:'warning.gif'});
    }else{
     var xml = AJAX.xml.getElementsByTagName('result');
     
     if (xml.length > 0){
      messageDialog(xml.item(0).firstChild.data, {type:'OK', icon:'info.gif'});
     }else{
      messageDialog(LANGUAGE['noReturn'], {type:'OK', icon:'warning.gif'});
     }
    }
   }else{
    messageDialog(LANGUAGE['unknownError'], {type:'OK', icon:'warning.gif'});
   }
  }
 }else{
  messageDialog('<?=label("PLEASE CHOOSE AN OBJECT")?>', {type:'OK', icon:'caution.gif'});
 }
};
f.filterObjectType = function (obj){
 var arr = [];
 if (getValue('listUsers')){
  arr.push('user');
 }
 if (getValue('listGroups')){
  arr.push('group');
 }
 if (!obj.checked){
  return (arr.length > 0) ? true : false;
 }
 return true;
};

addListener(window,'load',function(){
 f.getMenuList();
 f.getObjectList();
});
//]]>
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td style="width:250px" valign="top">
      <fieldset>
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
          <tr>
            <td class="gridLeft" style="width:25%"><label for="site"><?=label("MENU TYPE")?></label></td>
            <td class="gridRight">
              <?=formElement("radio", "type", "site", "site", "", "onclick=\"f.getMenuList()\"", "site")?> <label for="site"><?=label("SITE")?></label>
              <?=formElement("radio", "type", "admin", "site", "", "onclick=\"f.getMenuList()\"", "admin")?> <label for="admin"><?=label("ADMIN")?></label>
            </td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <div class="list" id="menuList" style="height:500px;width:300px;overflow:auto;"><div class="warning"><?=label("LOADING")?></div></div>
      </fieldset>
    </td>
    <td valign="top">
      <fieldset>
        <div class="filterBar">
          <label><?=label("OBJECT TYPE")?>:</label>
          <?=formElement("checkbox", "listUsers", 1, 1, "", "onclick=\"if(f.filterObjectType(this)){f.getObjectList(); return true;} return false;\"")?>
          <label for="listUsers"><?=label("USER")?></label>
          <?=formElement("checkbox", "listGroups", 1, 1, "", "onclick=\"if(f.filterObjectType(this)){f.getObjectList(); return true;} return false;\"")?>
          <label for="listGroups"><?=label("GROUP")?></label>
          &nbsp;&nbsp;
          <label for="listLevel"><?=label("USER LEVEL")?>:</label>
          <?=formElement("select", "listLevel", (array("&lt;&lt; ".label("ALL")." &gt;&gt;")+$levels), "", "", "onchange=\"f.getObjectList()\"")?>
          &nbsp;&nbsp;
          <label for="listKeyword"><?=label("FILTER")?>:</label>
          <?=formElement("text", "listKeyword", "", "", "", "onkeypress=\"if((event.keyCode==13)||(event.charCode==13)){f.getObjectList()}\" onfocus=\"this.select()\" class=\"searchInput\"")?>
        </div>
      </fieldset>
      <fieldset>
        <div class="list" id="objectList" style="height:150px"><div class="warning"><?=label("LOADING")?></div></div>
      </fieldset>
      <fieldset>
        <div style="height:25px">
          <div id="objectName" style="float:left;"><img src="objects/icons/16x16/caution.png" width="16" height="16" alt="" /> <?=label("PLEASE CHOOSE AN OBJECT")?></div>
          <div style="float:right">
            <b>R:</b> <?=label("READ")?> &nbsp;
            <b>W:</b> <?=label("WRITE")?> &nbsp;
            <b>D:</b> <?=label("DELETE")?> &nbsp;
            <b>U:</b> <?=label("UPLOAD")?>
          </div>
        </div>
        <div class="list" id="pageList" style="clear:both; height:300px"><div class="warning"><?=label("PICK A MENU")?></div></div>
      </fieldset>
      <div id="buttons" style="visibility:hidden">
        <div style="float:left">
          <a class="on" href="javascript:void(0)" onclick="f.checkUncheckAllPages('check');"><?=label("SELECT ALL")?></a>
          &nbsp;&nbsp;
          <a class="off" href="javascript:void(0)" onclick="f.checkUncheckAllPages('uncheck');"><?=label("UNSELECT ALL")?></a>
        </div>
        <div style="float:right">
          <?=formButton("button", "saveBtn", label("SAVE"), "", "save.png", "onclick=\"f.save()\"")?>
        </div>
      </div>
    </td>
  </tr>
</table>