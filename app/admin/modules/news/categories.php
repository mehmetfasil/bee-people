<?php
defined("PASS") or die("Dosya yok!");

//Simgeler
$icons = getFiles("objects/icons/16x16/", "&lt;&lt; ".label("PICK")." &gt;&gt;", array(".png"));
?>
<script type="text/javascript" src="objects/js/tree/treemenu.js"></script>
<script type="text/javascript" src="objects/js/tree/treeconfig.js"></script>
<script type="text/javascript">
//<![CDATA[
var tree;
var f = new formAction();
f.url = url +'&act=categories&step=';
f.title = '<?=label("NEWS CATEGORY")?>';
f.parentDialog = 'dialogParent';
f.onShow = function (){
 this.removeErrorSpan('name');
 getEl('iconImg').src= 'objects/icons/16x16/noicon.gif';
 return true;
};
f.onInit = function (){
 this.setOrders();
}
f.onSave = function (){
 if (f.errorSpan['name']){
  messageDialog((getValue('name').length > 0 ? '<?=label("CATEGORY NAME EXISTS")?> ('+ getValue('name') +')' : '<?=label("CATEGORY NAME EMPTY")?>'), {type:'OK', icon:'caution.gif', functionOK:function(){getEl('name').select()}});
  return false;
 }else if (getValue('name').length < 1){
  messageDialog('<?=label("CATEGORY NAME CANNOT BE BLANK")?>', {type:'OK', icon:'caution.gif', functionOK:function(){getEl('name').focus()}});
  return false;
 }else if (getValue('title').length < 1){
  messageDialog('<?=label("CATEGORY TITLE CANNOT BE BLANK")?>', {type:'OK', icon:'caution.gif', functionOK:function(){getEl('title').focus()}});
  return false;
 }else if (getValue('icon') == '0'){
  messageDialog('<?=label("CATEGORY ICON CANNOT BE BLANK")?>', {type:'OK', icon:'caution.gif', functionOK:function(){getEl('icon').focus()}});
  return false;
 }
 return true;
};
f.setIcon = function (){
 getEl('iconImg').src = getValue('icon') != '0' ? 'objects/icons/16x16/'+ getValue('icon') : 'objects/icons/16x16/noicon.gif';
}
f.setOrders = function (selID){
 var target = getEl('order');
 target.disabled = true;
 clearList(target);
 
 var params = 'id='+ getValue('id')
 + '&parent='+ getValue('parent');

 var AJAX = new ajaxObject('get', this.url+'orders', params);
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var xml = AJAX.xml.getElementsByTagName('list');
   
   if (xml.length > 0){
    for (var i=0; i < xml.length; i++){
     listOptions(target,xml.item(i).firstChild.data,xml.item(i).getAttribute('id'));
    }
    
    setParam('order',(selID != undefined ? selID : xml.item(xml.length-1).getAttribute('id')));
   }
  }
  target.disabled = false;
 }
};
f.checkName = function (){
 var target = getEl('name');
 if (!target.readOnly){ 
  this.removeErrorSpan('name');
  if (getValue('name').length > 0){
   var AJAX = new ajaxObject('post', this.url+'check', 'id='+ getValue('id',0) +'&name='+encodeURIComponent(getValue('name')));
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
   this.showErrorSpan('name','<?=label("EMPTY")?>');
  }
 }
};
f.showDialog = function (type, id){
 var buttons = {
  type:'OK',
  labelOK:LANGUAGE['cancel'],
  functionOK:function(){
   layer('hide',f.parentDialog);
  }
 };
 layer('show', this.parentDialog, 400, 300, '<?=label("PICK PARENT CATEGORY")?>', buttons);
 this.setParentList();
};
f.pick = function (objID, objTitle){
 layer('hide',this.parentDialog);
 setParam('parent',objID);
 setParam('parentTitle',objTitle);
 this.setOrders();
};
f.setParentList = function (){
	var target = getEl('parentList');
 var AJAX = new ajaxObject('get', this.url+'parents', '', {type:'DIALOG',target:this.parentDialog,message:LANGUAGE['loading']});
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
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
  
  			tree.put(open, parent, '', 'javascript:void(0)" ondblclick="f.pick('+ xml.item(i).getAttribute('id') +', \''+ xml.item(i).firstChild.data +'\');', '', '');
  		}
  		
  		target.innerHTML = tree;
  	}else{
  	 target.innerHTML = '<div class="warning">'+ LANGUAGE['noResult'] +'<\/div>';
   }
  }
	}
};
f.filters = function (){
 return {
  'keyword': getValue('listKeyword'),
  'x': 0,
  'y': <?=Y?>
 }
};

addListener(window, 'load', function() { 
 f.list(f.filters());
});
//]]>
</script>

<div id="filterBar">
  <label for="listKeyword"><?=label("FILTER")?>:</label>
  <?=formElement("text", "listKeyword", "", "", "", "onkeypress=\"if((event.keyCode==13)||(event.charCode==13)){f.list(f.filters())}\" class=\"searchInput\"")?>
</div>
<div class="link"><a class="new" href="javascript:void(0)" onclick="f.show()"><?=label("CREATE NEW")?></a></div>

<fieldset><legend><?=label("RECORDS")?></legend>
<div id="list"><div class="warning"><?=label("LOADING")?></div></div>
</fieldset>

<div id="dialog" style="display:none">
<form id="dialogform" method="post" action="#">
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td class="gridLeft" style="width:25%"><label for="pickBtn"><?=label("CATEGORY PARENT")?></label></td>
    <td class="gridRight">
      <?=formElement("hidden", "id", "", 0, "", "")?>
      <?=formElement("hidden", "parent", "", 0, "", "")?>
      <?=formElement("text", "parentTitle", "", ".root", "readonly", "style=\"width:300px\"")?>
      <?=formElement("button", "pickBtn", "", label("PICK"), "", "onclick=\"f.showDialog()\" class=\"button\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="name"><?=label("CATEGORY NAME")?></label></td>
    <td class="gridRight">
      <?=formElement("text", "name", "", "", "", "style=\"width:300px\" maxlength=\"25\" onblur=\"f.checkName()\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="title"><?=label("CATEGORY TITLE")?></label></td>
    <td class="gridRight">
      <?=formElement("text", "title", "", "", "", "style=\"width:300px\" maxlength=\"50\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft" valign="top"><label for="description"><?=label("CATEGORY DESCRIPTION")?></label></td>
    <td class="gridRight">
      <?=formElement("textarea", "description", "", "", "", "style=\"width:300px; height:80px\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="icon"><?=label("CATEGORY ICON")?></label></td>
    <td class="gridRight">
      <?=formElement("select", "icon", $icons, "", "", "style=\"width:300px\" onchange=\"f.setIcon()\"")?>
      <img id="iconImg" src="objects/icons/16x16/noicon.gif" width="16" height="16" alt="" />
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="order"><?=label("CATEGORY ORDER")?></label></td>
    <td class="gridRight">
      <?=formElement("select", "order", array("&nbsp;"), "", "", "style=\"width:300px\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="active_1"><?=label("CATEGORY STATUS")?></label></td>
    <td class="gridRight">
      <?=formElement("radio", "active", 1, 1, "", "", "active_1")?> <label for="active_1"><?=label("OPEN")?></label>
      <?=formElement("radio", "active", 0, 1, "", "", "active_0")?> <label for="active_0"><?=label("CLOSE")?></label>
    </td>
  </tr>
</table>
</form>
</div>

<div id="dialogParent" style="display:none">
<div><img src="objects/icons/tree/root.gif" width="20" height="20" alt="" /> <a href="javascript:void(0)" ondblclick="f.pick(0,'.root')">.root</a></div>
<div id="parentList" style="height:200px; overflow:auto"></div>
</div>
