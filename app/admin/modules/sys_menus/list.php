<?php
defined("PASS") or die("Dosya yok!");

//Lookup Dosya
include_once(dirname(__FILE__).DS."lookups.php");

//Iconlar
$icons = array("spacer.gif"=>"objects/icons/16x16/noicon.gif");
$iconsTmp = getFiles("objects/icons/16x16/", "", array(".png"));

foreach ($iconsTmp as $icon){
 $icons[$icon] = "objects/icons/16x16/".$icon;
}

$site_templates = getFolders("templates");
$admin_templates = getFolders(ADMIN_FOLDER.DS."templates");
?>
<script type="text/javascript" src="objects/js/tree/treemenu.js"></script>
<script type="text/javascript" src="objects/js/tree/treeconfig.js"></script>
<script type="text/javascript">
//<![CDATA[
var tree;
var site_templates = ['<?=implode("','", $site_templates)?>'];
var admin_templates = ['<?=implode("','", $admin_templates)?>'];

var f = new formAction();
f.url = url +'&act=';
f.title = '<?=label("MENU ENTRY")?>';
f.width = 650;
f.onShow = function (){
 this.setSource('iconImg','objects/icons/16x16/noicon.gif');
 this.setReadOnly('name',true);
 this.tab(1);
 this.removeErrorSpan('name');
 return true;
};
f.onSave = function (){
 var tabs = getEl('tabs1').getElementsByTagName('a');
 if ((getValue('name').length < 1) || f.errorSpan['name']){
  var msg = '<?=label("MENU NAME EXISTS")?> ('+ getValue('name') +')';
  if (getValue('name').length < 1){
   msg = '<?=label("MENU NAME EMPTY")?>';
  }else if (!getValue('name').match(/^[a-zA-Z0-9_]+$/)){
   msg = '<?=label("MENU NAME CONTAINS INVALID CHARACTERS")?>';
  }
  messageDialog(msg, {type:'OK', icon:'caution.gif', functionOK:function(){getEl('name').select()}});
  return false;
 }else if (getValue('caption').length < 1){
  messageDialog('<?=label("MENU CAPTION MUST BE FILLED")?>', {type:'OK', icon:'caution.gif', functionOK:function(){f.tab(1,tabs[0]); getEl('caption').focus()}});
  return false;
 }else if (getEl('order').disabled){
  messageDialog('<?=label("SAVE AFTER LOADING FINISH")?>', {type:'OK', icon:'caution.gif'});
  return false;
 }
 //Subpages
 var arr = [];
 for (var i in f.subpages){
  var str = '';
  for (var y in f.subpages[i]){
   str += (str.length > 0 ? '~' : '')+ f.subpages[i][y];
  }
  arr.push(str);
 }
 setParam('subpages',arr.join('^'));
 return true;
};
f.onInit = function (){
 this.setReadOnly('name',false);
 this.setIDs();
 this.setOrders();
 this.setTemplates(site_templates);
 this.subpages = [{
  id:0,
  sid:'index',
  template:'_default',
  title:'INDEX',
  caption:'INDEX',
  icon:'spacer.gif',
  position:'relative',
  visibility:'visible',
  source:'none',
  path:''
 }];
 this.setSubpages();
};
f.setIDs = function (selID){
 var target = getEl('idTmp');
 target.disabled = true;
 clearList(target);
 
 var AJAX = new ajaxObject('get', this.url+'ids', null);
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var xml = AJAX.xml.getElementsByTagName('list');
   
   for (var i=0; i < xml.length; i++){
    listOptions(target,xml.item(i).getAttribute('id'),xml.item(i).getAttribute('id'));
   }
   
   if (selID != undefined){
    target.disabled = true;
    listOptions(target,selID,selID);
    setParam('idTmp',selID);
    setParam('id',selID);
   }else{
    setParam('id',getValue('idTmp'));
    target.disabled = false;
   }
  }
 }
};
f.setOrders = function (selID){
 var target = getEl('order');
 target.disabled = true;
 clearList(target);
 
 var params = 'type='+(getValue('site')=='site'?'site':'admin')
 + '&id='+ getValue('id')
 + '&parent='+ getValue('parent');

 var AJAX = new ajaxObject('get', this.url+'orders', params);
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var xml = AJAX.xml.getElementsByTagName('list');
   
   for (var i=0; i < xml.length; i++){
    listOptions(target,xml.item(i).firstChild.data,xml.item(i).getAttribute('id'));
   }
   
   setParam('order',(selID != undefined ? selID : xml.item(xml.length-1).getAttribute('id')));
  }
  target.disabled = false;
 }
};
f.setTemplates = function (arr){
 var target = getEl('page_template');
 clearList(target);
 for (var i=0; i < arr.length; i++){
  listOptions(target, arr[i], arr[i]);
 }
};
f.showDialog = function (type, id){
 if (type=='icon'){
  this.iconDialog = 'dialogIcon';
  var buttons = {
   type:'OK',
   labelOK:LANGUAGE['cancel'],
   functionOK:function(){
    layer('hide',f.iconDialog);
   }
  };
  layer('show', this.iconDialog, 300, 300, '<?=label("PICK AN ICON")?>', buttons);
  this.targetIcon = (!id ? 'menu' : 'page');
 }else if (type=='parent'){
  this.parentDialog = 'dialogParent';
  var buttons = {
   type:'OK',
   labelOK:LANGUAGE['cancel'],
   functionOK:function(){
    layer('hide',f.parentDialog);
   }
  };
  layer('show', this.parentDialog, 400, 300, '<?=label("PICK PARENT MENU")?>', buttons);
  this.setParentList();
 }else if (type=='subpage'){
  this.subpageDialog = 'dialogSubpage';
  var buttons = {
   type:'SAVECANCEL',
   labelSAVE:LANGUAGE['add'],
   functionSAVE:function(){
    if (getValue('page_sid').length < 1){
     messageDialog('<?=label("DO NOT LEAVE BLANK SID")?>', {type:'OK', icon:'caution.gif', functionOK:function(){getEl('page_sid').focus()}});
    }else if (!getValue('page_sid').match(/^[a-zA-Z0-9_]+$/)){
     messageDialog('<?=label("SID CONTAINS INVALID CHARACTERS")?>', {type:'OK', icon:'caution.gif', functionOK:function(){getEl('page_sid').select()}});
    }else if (getValue('page_title').length < 1){
     messageDialog('<?=label("DO NOT LEAVE BLANK PAGE TITLE")?>', {type:'OK', icon:'caution.gif', functionOK:function(){getEl('page_title').focus()}});
    }else if (getValue('page_caption').length < 1){
     messageDialog('<?=label("DO NOT LEAVE BLANK PAGE CAPTION")?>', {type:'OK', icon:'caution.gif', functionOK:function(){getEl('page_caption').focus()}});
    }else if ((getValue('page_source')=='file') && (getValue('page_path').length < 1)){
     messageDialog('<?=label("DO NOT LEAVE BLANK PAGE SOURCE FILE")?>', {type:'OK', icon:'caution.gif', functionOK:function(){getEl('page_path').focus()}});
    }else{
     var chunk = false;
     for (var i=0; i < f.subpages.length; i++){
      if ((id == undefined) && (f.subpages[i].sid==getValue('page_sid'))){
       chunk = true;
       break;
      }
     }
     
     if (chunk){
      messageDialog('<?=label("SID ALREADY EXISTS IN LIST")?>', {type:'OK', icon:'caution.gif', functionOK:function(){getEl('page_sid').select()}});
     }else{
      var vars = {
       id:getValue('page_id'),
       sid:getValue('page_sid'),
       template:(getValue('page_position')=='relative' ? getValue('page_template') : '-'),
       title:getValue('page_title'),
       caption:getValue('page_caption'),
       icon:getValue('page_icon'),
       position:getValue('page_position'),
       visibility:getValue('page_visibility'),
       source:getValue('page_source'),
       path:getValue('page_path')
      }
      if (!isNaN(id)){
       //Update
       f.subpages[id] = vars;
      }else{
       //Insert
       f.subpages.push(vars);
      }
      f.setSubpages();
      layer('hide',f.subpageDialog);
     }
    }
   },
   functionCANCEL:function(){
    layer('hide',f.subpageDialog);
   }
  };
  setParam('page_id', 0);
  this.setSource('page_iconImg','objects/icons/16x16/noicon.gif');
  this.setReadOnly('page_sid',false);
  this.setDisable('page_template',false);
  layer('show', this.subpageDialog, 600, 300, '<?=label("ADD SUBPAGE")?>', buttons);
  if (id!=undefined){
   if (this.subpages[id]){
    for (var y in this.subpages[id]){
     setParam('page_'+y, this.subpages[id][y]);
     if (y=='icon'){
      getEl('page_iconImg').src = 'objects/icons/16x16/'+ (this.subpages[id][y]=='spacer.gif'?'noicon.gif':this.subpages[id][y]);
     }
    }
    this.setReadOnly('page_sid',true);
    if (getValue('page_position') == 'absolute'){
     this.setDisable('page_template',true);
    }
   }
  }
 }else if (type=='file'){
  this.fileDialog = 'dialogFile';
  var buttons = {
   type:'OK',
   labelOK:LANGUAGE['cancel'],
   functionOK:function(){
    layer('hide',f.fileDialog);
   }
  };
  getEl('pathSpan').innerHTML = '';
  layer('show', this.fileDialog, 400, 300, '<?=label("PICK A FILE")?>', buttons);
  this.setFiles('/');
 }
};
f.pick = function (type, objID, objTitle){
 if (type=='icon'){
  layer('hide',this.iconDialog);
  setParam((this.targetIcon=='page' ? 'page_icon' : 'icon'),objID);
  getEl((this.targetIcon=='page' ? 'page_iconImg' : 'iconImg')).src = 'objects/icons/16x16/'+(objID=='spacer.gif'?'noicon.gif':objID);
 }else{
  layer('hide',this.parentDialog);
  setParam('parent',objID);
  setParam('parentTitle',objTitle);
  this.setOrders();
 }
};
f.setParentList = function (){
	var target = getEl('parentList');
	target.innerHTML = '';
	
 var AJAX = new ajaxObject('get', this.url+'parents', 'type='+(getValue('site')=='site'?'site':'admin'), {type:'DIALOG',target:this.parentDialog,message:LANGUAGE['loading']});
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
   				
   			tree.put(open, parent, '', 'javascript:void(0)" ondblclick="f.pick(\'parent\','+ xml.item(i).getAttribute('id') +', \''+ xml.item(i).firstChild.data +'\');', '', '');
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
f.setNamePrefix = function (){
 var obj = getEl('name');
 if (!obj.readOnly){
  var pos = obj.value.indexOf('_');
  obj.value = (getValue('site')=='site'?'SITE':'ADMIN') + obj.value.substr(pos);
 }
}
f.checkName = function (){
 var target = getEl('name');
 if (!target.readOnly){ 
  this.removeErrorSpan('name');
  if (getValue('name').length > 0){
   if (getValue('name').match(/^[a-zA-Z0-9_]+$/)){
    var AJAX = new ajaxObject('post', this.url+'check', 'name='+encodeURIComponent(getValue('name')));
    AJAX.run();
    AJAX.onLoad = function (){
     if (AJAX.xml){
      var xml = AJAX.xml.getElementsByTagName('result');
      if (xml.length > 0){
       if (xml.item(0).getAttribute('status') != 'OK'){
        f.showErrorSpan('name','<?=label("EXISTS")?>');
       }
      }
     }
    }
   }else{
    this.showErrorSpan('name','<?=label("INVALID")?>');
   }
  }else{
   this.showErrorSpan('name','<?=label("EMPTY")?>');
  }
 }
};
f.subpages = [];
f.setSubpages = function (){
 var sources = {};
 sources['none'] = '<?=label("NONE")?>';
 sources['file'] = '<?=label("FILE")?>';
 sources['html'] = '<?=label("HTML")?>';
 
 var target = getEl('subpagesDiv');
 if (target){
		if (target.hasChildNodes()){
		 while (target.childNodes.length >= 1){
		  target.removeChild(target.firstChild);
		 }
		}
		
  if (this.subpages.length > 0){
   //Table
 		var table = Layer.createElement('table',target);
 		table.width = '100%';
 		table.border = '0';
 		table.cellPadding = '5';
 		table.cellSpacing = '0';
 		var tbody = Layer.createElement('tbody',table);		
 		var tr = Layer.createElement('tr',tbody);
   var td = Layer.createElement('td',tr);
   td.className = 'gridTitle';
   td.style.textAlign = 'center';
   td.style.width = '5%';
   td.innerHTML = '&nbsp;';
   var td = Layer.createElement('td',tr);
   td.style.textAlign = 'center';
   td.className = 'gridTitle';
   td.style.width = '15%';
   td.innerHTML = '<?=label("SID")?>';
   var td = Layer.createElement('td',tr);
   td.style.textAlign = 'center';
   td.className = 'gridTitle';
   td.style.width = '15%';
   td.innerHTML = '<?=label("PAGE TEMPLATE")?>';
   var td = Layer.createElement('td',tr);
   td.className = 'gridTitle';
   td.innerHTML = '<?=label("PAGE CAPTION")?>';
   var td = Layer.createElement('td',tr);
   td.style.textAlign = 'center';
   td.className = 'gridTitle';
   td.style.width = '15%';
   td.innerHTML = '<?=label("PAGE SOURCE")?>';
   
 		for (var i=0; i < this.subpages.length; i++){
    var tr = Layer.createElement('tr',tbody);
    var td1 = Layer.createElement('td',tr);
    td1.className = 'gridRow';
    td1.onmouseover = new Function('overRow(this,\'gridRowOver\')');
    td1.onmouseout = new Function('overRow(this,\'gridRow\')');
    td1.style.textAlign = 'center';
    var td2 = Layer.createElement('td',tr);
    td2.style.textAlign = 'center';
    td2.className = 'gridRow';
    td2.onmouseover = new Function('overRow(this,\'gridRowOver\')');
    td2.onmouseout = new Function('overRow(this,\'gridRow\')');
    td2.onclick = new Function('f.showDialog(\'subpage\','+i+')');
    var td3 = Layer.createElement('td',tr);
    td3.style.textAlign = 'center';
    td3.className = 'gridRow';
    td3.onmouseover = new Function('overRow(this,\'gridRowOver\')');
    td3.onmouseout = new Function('overRow(this,\'gridRow\')');
    td3.onclick = new Function('f.showDialog(\'subpage\','+i+')');
    var td4 = Layer.createElement('td',tr);
    td4.className = 'gridRow';
    td4.onmouseover = new Function('overRow(this,\'gridRowOver\')');
    td4.onmouseout = new Function('overRow(this,\'gridRow\')');
    td4.onclick = new Function('f.showDialog(\'subpage\','+i+')');
    var td5 = Layer.createElement('td',tr);
    td5.style.textAlign = 'center';
    td5.className = 'gridRow';
    td5.onmouseover = new Function('overRow(this,\'gridRowOver\')');
    td5.onmouseout = new Function('overRow(this,\'gridRow\')');
    td5.onclick = new Function('f.showDialog(\'subpage\','+i+')');
    
    var box = document.createElement('input');
    box.setAttribute('type','checkbox');
    box.setAttribute('id','chk'+i);
    box.setAttribute('name','chk'+i);
    box.setAttribute('value', i);
    box.disabled = (this.subpages[i].sid=='index' ? true : false);
    td1.appendChild(box);
    
    td2.innerHTML = '<b>'+ this.subpages[i].sid +'<\/b>';
    td3.innerHTML = this.subpages[i].template;
    td4.innerHTML = this.subpages[i].caption;
    td5.innerHTML = sources[this.subpages[i].source];
   }
  }else{
   //DIV
 		var div = Layer.createElement('div',target);
 		div.className = 'warning';
 		div.innerHTML = '<?=label("YOU MUST ADD AT LEAST ONE SUBPAGE")?>';
  }
 }
};
f.removeSubpage = function (){
 var chked = [];
 var source = getEl('subpagesDiv');
 var els = source.getElementsByTagName('input');
 for (var i=0; i < els.length; i++){
  if ((els[i].type == 'checkbox') && !els[i].disabled && els[i].checked){
   chked.push(els[i].value);
  }
 }
 
 if (chked.length > 0){
  var tmp = [];
  for (var y=0; y < chked.length; y++){
   if (this.subpages[chked[y]]){
    this.subpages[chked[y]] = null;
   }
  }
  for (var i=0; i < this.subpages.length; i++){
   if (this.subpages[i]){
    tmp.push(this.subpages[i]);
   }
  }
  this.subpages = tmp;
  this.setSubpages();
 }else{
  messageDialog('<?=label("SELECT AT LEAST ONE")?>', {type:'OK', icon:'caution.gif'});
 }
};
f.setFiles = function (path){
 var target = getEl('fileList');
 target.innerHTML = '';
 var result = '';
 var AJAX = new ajaxObject('get', this.url+'files', 'path='+path, {type:'DIALOG',message:LANGUAGE['loading'],target:this.dialogFile});
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var xml = AJAX.xml.getElementsByTagName('list');
   if (xml.length > 0){
    result += '<table width="100%" border="0" cellpadding="2" cellspacing="0">\n'
    for (var i=0; i < xml.length; i++){
     result += '<tr>\n'
     + '<td style="width:20px"><img src="objects/icons/tree/'+ ((xml.item(i).getAttribute('type') == 'folder') ? 'close.gif' : 'item.gif') +'" alt=""><\/td>\n'
     + '<td><a href="javascript:void(0)" onclick="f.setPath(\''+ xml.item(i).getAttribute('type') +'\', \''+ xml.item(i).getAttribute('path') +'\')">'+ xml.item(i).firstChild.data +'<\/a><\/td>\n'
     + '<\/tr>\n';
    }
    result += '<\/table>\n';
   }else{
    result += '<div class="warning"><?=label("EMPTY FOLDER")?><\/div>';
   }
  }else{
   result += '<div class="warning">'+ LANGUAGE['unknownError'] +'<\/div>';
  }
  target.innerHTML = result;
 }
};
f.setPath = function (type, path){
 if (type == 'folder'){
  var target = getEl('pathSpan');
  var tmp = '';
  var raw = path.split('\/');
  var raw2 = '';
  for (var i=0; i < raw.length; i++){
   if (raw[i].length > 0){
    raw2 += '/'+ raw[i];
    tmp += ' / <a href="javascript:void(0)" onclick="f.setPath(\'folder\', \''+ raw2 +'/\')">'+ raw[i] +'<\/a>';
   }
  }
  target.innerHTML = tmp;
  this.setFiles(path);
 }else{
  var target = getEl('page_path');
  target.value = path;
  layer('hide', this.fileDialog);
 }
};
f.setDisable = function (objID, act){
 var target = getEl(objID);
 if (target){
  target.disabled = act;
 }
};
f.setReadOnly = function (objID, act){
 var target = getEl(objID);
 if (target){
  target.readOnly = act;
 }
};
f.setSource = function (objID, sors){
 var target = getEl(objID);
 if (target){
  target.src = sors;
 }
};
f.filters = function (){
 return {
  'orderby': getValue('listOrder'),
  'order': getValue('listBy'),
  'keyword': getValue('listKeyword'),
  'type': getValue('listType'),
  'x': 0,
  'y': <?=Y?>
 }
};

addListener(window,'load',function(){f.list(f.filters())});
//]]>
</script>

<div id="filterBar">
  <label for="listOrder"><?=label("ORDERING")?>:</label>
  <?=formElement("select", "listOrder", $orders, "", "", "onchange=\"f.list(f.filters())\"")?>
  <?=formElement("select", "listBy", array("ASC"=>label("ASCENDING"), "DESC"=>label("DESCENDING")), "DESC", "", "onchange=\"f.list(f.filters())\"")?>
  &nbsp;&nbsp;
  <label for="listType"><?=label("MENU TYPE")?>:</label>
  <?=formElement("select", "listType", (array("&lt;&lt;".label("ALL")."&gt;&gt;")+$types), "", "", "onchange=\"f.list(f.filters())\"")?>
  &nbsp;&nbsp;
  <label for="listKeyword"><?=label("FILTER")?>:</label>
  <?=formElement("text", "listKeyword", "", "", "", "onkeypress=\"if((event.keyCode==13)||(event.charCode==13)){f.list(f.filters())}\" class=\"searchInput\"")?>
</div>
<div class="link"><a class="new" href="javascript:void(0)" onclick="f.show()"><?=label("CREATE NEW")?></a></div>

<fieldset><legend><?=label("RECORDS")?></legend>
<div id="list"><div class="warning"><?=label("LOADING")?></div></div>
</fieldset>

<div id="dialog" style="display:none">
<form id="dialogform" method="post" onsubmit="return false" action="#">
<div id="tabs1" class="tabs">
  <ul>
    <li class="here"><a href="javascript:void(0)" onclick="f.tab(1,this)"><?=label("PROPERTIES")?></a></li>
    <li><a href="javascript:void(0)" onclick="f.tab(1,this)"><?=label("PAGES")?></a></li>
  </ul>
</div>
<div id="tabbing1" class="tabbing" style="height:400px">
  <div class="tab">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top" style="width:50%">
          <fieldset>
            <table width="100%" border="0" cellpadding="5" cellspacing="0">
              <tr>
                <td class="gridLeft" style="width:35%"><label for="site"><?=label("MENU TYPE")?></label></td>
                <td class="gridRight">
                  <?=formElement("radio", "type", "site", "site", "", "onclick=\"f.setOrders();f.setNamePrefix();f.setTemplates(site_templates)\"", "site")?> <label for="site"><?=label("SITE")?></label>
                  <?=formElement("radio", "type", "admin", "site", "", "onclick=\"f.setOrders();f.setNamePrefix();f.setTemplates(admin_templates)\"", "admin")?> <label for="admin"><?=label("ADMIN")?></label>
                </td>
              </tr>
              <tr>
                <td class="gridLeft"><label for="id"><?=label("MENU ID")?></label></td>
                <td class="gridRight">
                  <?=formElement("hidden", "id", "", 0, "", "")?>
                  <?=formElement("select", "idTmp", array("&nbsp;"), "", "", "style=\"width:50px\"")?>
                </td>
              </tr>
              <tr>
                <td class="gridLeft"><label><?=label("MENU ICON")?></label></td>
                <td class="gridRight">
                  <?=formElement("text", "icon", "", "spacer.gif", "readonly", "style=\"width:120px\"")?>
                  <a href="javascript:void(0)" onclick="f.showDialog('icon')"><img id="iconImg" src="objects/icons/16x16/noicon.gif" width="16" height="16" alt="" /></a>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
        <td valign="top" style="width:50%">
          <fieldset>
            <table width="100%" border="0" cellpadding="5" cellspacing="0">
              <tr>
                <td class="gridLeft" style="width:35%"><label for="split"><?=label("MENU SPLIT")?></label></td>
                <td class="gridRight">
                  <?=formElement("checkbox", "split", "1", "0", "", "")?> <label for="split"><?=label("SPLIT AFTER MENU")?></label>
                </td>
              </tr>
              <tr>
                <td class="gridLeft"><label for="visible"><?=label("MENU VISIBILITY")?></label></td>
                <td class="gridRight">
                  <?=formElement("radio", "visibility", "visible", "visible", "", "", "visible")?> <label for="visible"><?=label("VISIBLE")?></label>
                  <?=formElement("radio", "visibility", "hidden", "visible", "", "", "hidden")?> <label for="hidden"><?=label("HIDDEN")?></label>
                </td>
              </tr>
              <tr>
                <td class="gridLeft"><label for="active_1"><?=label("MENU STATUS")?></label></td>
                <td class="gridRight">
                  <?=formElement("radio", "active", "1", "1", "", "", "active_1")?> <label for="active_1"><?=label("OPEN")?></label>
                  <?=formElement("radio", "active", "0", "1", "", "", "active_0")?> <label for="active_0"><?=label("CLOSE")?></label>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
    </table>
    <fieldset>
    <table width="100%" border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td class="gridLeft" style="width:25%"><label for="parentBtn"><?=label("MENU PARENT")?></label></td>
        <td class="gridRight">
          <?=formElement("hidden", "parent", "", 0, "", "")?>
          <?=formElement("text", "parentTitle", "", ".root", "readonly", "style=\"width:300px;\" maxlength=\"100\"")?>
          <?=formElement("button", "parentBtn", "", label("PICK"), "", "onclick=\"f.showDialog('parent')\" class=\"button\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="name"><?=label("MENU NAME")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "name", "", "SITE_", "", "style=\"width:300px;\" maxlength=\"100\" onkeyup=\"f.checkName()\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="caption"><?=label("MENU CAPTION")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "caption", "", "", "", "style=\"width:300px;\" maxlength=\"100\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="detail"><?=label("MENU DETAIL")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "detail", "", "", "", "style=\"width:300px;\" maxlength=\"100\" onfocus=\"if(this.value.length==0){this.value=getValue('caption'); this.select()}\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="link"><?=label("MENU LINK")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "link", "", "", "", "style=\"width:300px;\" maxlength=\"100\" onfocus=\"if(this.value.length==0){this.value='".CONF_MAIN_PAGE."?pid='+ getValue('id'); this.select()}\"")?>
          &nbsp;<label for="void" style="font-weight:bold;"><?=label("Void")?></label>
          <input type="checkbox" id="void" onclick="if(this.checked){getEl('link').value='javascript:void(0);'}else{getEl('link').value=''}" />
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="order"><?=label("MENU ORDER")?></label></td>
        <td class="gridRight">
          <?=formElement("select", "order", array("&nbsp;"), "", "", "style=\"width:300px\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="self"><?=label("MENU TARGET")?></label></td>
        <td class="gridRight">
          <?=formElement("radio", "target", "_self", "_self", "", "", "self")?> <label for="self"><?=label("CURRENT PAGE")?></label>
          <?=formElement("radio", "target", "_blank", "_self", "", "", "blank")?> <label for="blank"><?=label("BLANK PAGE")?></label>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="level"><?=label("MENU LEVEL")?></label></td>
        <td class="gridRight">
          <?=formElement("select", "level", $levels, "", "", "style=\"width:300px\"")?>
        </td>
      </tr>
    </table>
    </fieldset>
  </div>
  <div class="tab" style="display:none">
    <div style="padding:5px 0; text-align:right">
      <?=formElement("button", "addBtn", "", "+", "", "onclick=\"f.showDialog('subpage')\" class=\"button\"")?>
      <?=formElement("button", "removeBtn", "", "-", "", "onclick=\"f.removeSubpage()\" class=\"button\"")?>
      <?=formElement("hidden", "subpages", "", "", "", "")?>
    </div>
    <div id="subpagesDiv"></div>
  </div>
</div>
</form>
</div>

<div id="dialogIcon" style="display:none">
<div id="iconList">
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
  <?
  $i=0;
  $t=count($icons);
  foreach ($icons as $key=>$value){
   echo "<td style=\"width:10%; text-align:center\">";
   echo "<a href=\"javascript:void(0)\" onclick=\"f.pick('icon','".$key."')\" title=\"".$key."\"><img src=\"".$value."\" width=\"16\" height=\"16\" alt=\"".$key."\" /></a>\n";
   echo "</td>";
   $i++;
   $t--;
   if (($t > 0) and ($i%10==0)){
    echo "</tr>\n";
    echo "<tr>\n";
   }elseif ($t==0){
    while ($i%10 > 0) {
     echo "<td style=\"width:10%; text-align:center\">&nbsp;</td>\n";
     $i++;
    }
   }
  }
  ?>
  </tr>
</table>
</div>
</div>

<div id="dialogParent" style="display:none">
<div><img src="objects/icons/tree/root.gif" width="20" height="20" alt="" /> <a href="javascript:void(0)" ondblclick="f.pick('parent',0,'.root')">.root</a></div>
<div id="parentList" style="height:300px; overflow:auto"></div>
</div>

<div id="dialogSubpage" style="display:none">
<form id="dialogSubpageform" method="post" onsubmit="return false" action="#">
<fieldset>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td class="gridLeft" style="width:25%"><label for="page_sid">SID</label></td>
    <td class="gridRight">
      <?=formElement("hidden", "page_id", "", 0, "", "")?>
      <?=formElement("text", "page_sid", "", "", "", "style=\"width:300px;\" maxlength=\"50\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="page_title"><?=label("PAGE TITLE")?></label></td>
    <td class="gridRight">
      <?=formElement("text", "page_title", "", "", "", "style=\"width:300px;\" maxlength=\"50\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="page_caption"><?=label("PAGE CAPTION")?></label></td>
    <td class="gridRight">
      <?=formElement("text", "page_caption", "", "", "", "style=\"width:300px;\" maxlength=\"100\" onfocus=\"if(this.value.length==0){this.value=getValue('page_title'); this.select()}\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft" valign="top"><label for="none"><?=label("PAGE SOURCE")?></label></td>
    <td class="gridRight">
      <table width="100%" border="0" cellpadding="0" cellspacing="3">
        <tr>
          <td style="width:20%">
            <?=formElement("radio", "page_source", "none", "none", "", "", "none")?> <label for="none"><?=label("NONE")?></label>
          </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>
            <?=formElement("radio", "page_source", "file", "none", "", "", "file")?> <label for="file"><?=label("FILE")?></label>
          </td>
          <td>
            <?=formElement("text", "page_path", "", "", "", "style=\"width:250px;\" maxlength=\"100\" onfocus=\"setParam('page_source','file')\"")?>
            <?=formElement("button", "pathBtn", "", label("PICK"), "", "onclick=\"setParam('page_source','file'); f.showDialog('file')\" class=\"button\"")?>
          </td>
        </tr>
        <tr>
          <td>
            <?=formElement("radio", "page_source", "html", "none", "", "", "html")?> <label for="html"><?=label("HTML")?></label>
          </td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</fieldset>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top" style="width:50%">
      <fieldset>
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
          <tr>
            <td class="gridLeft" style="width:35%"><label><?=label("PAGE ICON")?></label></td>
            <td class="gridRight">
              <?=formElement("text", "page_icon", "", "spacer.gif", "readonly", "style=\"width:120px\"")?>
              <a href="javascript:void(0)" onclick="f.showDialog('icon','page')"><img id="page_iconImg" src="objects/icons/16x16/noicon.gif" width="16" height="16" alt="" /></a>
            </td>
          </tr>
          <tr>
            <td class="gridLeft"><label for="relative2"><?=label("PAGE POSITION")?></label></td>
            <td class="gridRight">
              <?=formElement("radio", "page_position", "relative", "relative", "", "onclick=\"f.setDisable('page_template',false)\"", "relative2")?> <label for="relative2"><?=label("RELATIVE")?></label>
              <?=formElement("radio", "page_position", "absolute", "relative", "", "onclick=\"f.setDisable('page_template',true)\"", "absolute2")?> <label for="absolute2"><?=label("ABSOLUTE")?></label>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
    <td valign="top" style="width:50%">
      <fieldset>
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
          <tr>
            <td class="gridLeft" style="width:35%"><label for="visible2"><?=label("PAGE VISIBILITY")?></label></td>
            <td class="gridRight">
              <?=formElement("radio", "page_visibility", "visible", "visible", "", "", "visible2")?> <label for="visible2"><?=label("VISIBLE")?></label>
              <?=formElement("radio", "page_visibility", "hidden", "visible", "", "", "hidden2")?> <label for="hidden2"><?=label("HIDDEN")?></label>
            </td>
          </tr>
          <tr>
            <td class="gridLeft"><label for="page_template"><?=label("PAGE TEMPLATE")?></label></td>
            <td class="gridRight">
              <?=formElement("select", "page_template", array("&nbsp;"), "", "", "style=\"width:170px;\"")?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
</form>
</div>

<div id="dialogFile" style="display:none">
<div class="gridRowOver" style="line-height:25px; overflow:hidden;"><a href="javascript:void(0)" onclick="f.setPath('folder', '')">.root</a> <span id="pathSpan"></span></div>
<div id="fileList" style="height:250px; overflow:auto"></div>
</div>
