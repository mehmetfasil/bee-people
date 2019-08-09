<?php
defined("PASS") or die("Dosya yok!");

//Lookup Dosya
include_once(dirname(__FILE__).DS."lookups.php");
?>
<script type="text/javascript" src="objects/js/swfupload.js"></script>
<script type="text/javascript" src="objects/js/tree/treemenu.js"></script>
<script type="text/javascript" src="objects/js/tree/treeconfig.js"></script>
<script type="text/javascript">
//<![CDATA[
var swfu, tree;
var f = new formAction();
f.url = url +'&act=';
f.width = 600;
f.title = '<?=label("PRODUCT")?>';
f.onShow = function (){
 this.tab(1); 
 this.removeErrorSpan('baslik');
 this.tmpID = Math.random().toString().substr(2);
 getEl('hediye').style.visibility = 'hidden';
 getEl('detayDiv').innerHTML = '<div class="empty">&lt; <?=label("EMPTY");?> &gt;<\/div>';
 getEl('pictureList').innerHTML = '<div class="empty">&lt; <?=label("NO PICTURE FOUND")?> &gt;<\/div>';
 
 return true;
};
f.onSave = function (){
 var tabs = getEl('tabs1').getElementsByTagName('a');
 if (getValue('kategori',0) < 1){
  messageDialog('<?=label("PRODUCT CATEGORY MUST BE SELECTED")?>', {type:'OK', icon:'caution.gif', functionOK:function(){f.tab(1,tabs[0]); getEl('kategoriSec').focus()}});
  return false;
 }else if (f.errorSpan['baslik'] || (getValue('baslik').length < 1)){
  messageDialog('<?=label("PRODUCT TITLE EMPTY")?>', {type:'OK', icon:'caution.gif', functionOK:function(){f.tab(1,tabs[0]); getEl('baslik').focus()}});
  return false;
 }else if (getValue('satisfiyat').length < 1){
  messageDialog('<?=label("PRODUCT SELLING PRICE IS EMPTY")?>', {type:'OK', icon:'caution.gif', functionOK:function(){f.tab(1,tabs[0]); getEl('satisfiyat').focus()}});
  return false;
 }else if (getValue('detay').length < 1){
  messageDialog('<?=label("PRODUCT DESCRIPTION MUST BE FILLED")?>', {type:'OK', icon:'caution.gif', functionOK:function(){f.tab(1,tabs[1])}});
  return false;
 }
 
 return true;
};
f.checkTitle = function (){
 var target = getEl('baslik');
 this.removeErrorSpan('baslik');
 if (getValue('baslik').length < 1){
  this.showErrorSpan('baslik','<?=label("EMPTY")?>');
 }
};
f.showCategories = function (){
 this.categoryDialog = 'dialogCategory';
 var buttons = {
  type:'OK',
  labelOK:LANGUAGE['cancel'],
  functionOK:function(){
   layer('hide',f.categoryDialog);
  }
 };
 layer('show', this.categoryDialog, 400, 300, '<?=label("PICK A CATEGORY")?>', buttons);
 this.setCategoryList(); 
};
f.setCategoryList = function (){
	var target = getEl('categoryList');
	target.innerHTML = '';
	
 var AJAX = new ajaxObject('get', this.url+'categories', null, {type:'DIALOG',target:this.categoryDialog,message:LANGUAGE['loading']});
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
   
   			tree.put(open, parent, '', 'javascript:void(0)" ondblclick="f.pick('+ xml.item(i).getAttribute('id') +', \''+ xml.item(i).firstChild.data +'\');', '', '');
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
f.pick = function (objID, objTitle){
 layer('hide',this.categoryDialog);
 setParam('kategori',objID);
 setParam('kategoriBaslik',objTitle);
};
f.showEditor = function (target){
 if (<?=menuID("SITE_EDITOR")?> > 0){
  var u = '<?=CONF_MAIN_PAGE."?pid=".menuID("SITE_EDITOR")?>&target='+ target;
  window.open(u, 'editor', 'width=800, height=600, scrollbars=no');
 }else{
  messageDialog('<?=label("EDITOR PAGE IS NOT DEFINED")?>', {type:'OK', icon:'warning.gif'});
 }
};
f.clearHTML = function (objID){
 setParam(objID, '');
 this.setHTML();
};
f.setHTML = function (){
 getEl('detayDiv').innerHTML = getValue('detay').length > 0 ? getValue('detay') : '<div class="empty">&lt; <?=label("EMPTY");?> &gt;<\/div>';
};
f.showUpload = function (){
 this.uploadDialog = 'dialogUpload';
 getEl('uploadDiv').innerHTML = '<span id="uploadSpan"><\/span>';
 getEl('uploadProgress').innerHTML = '';
 getEl('uploadResult').innerHTML = '';
 
 layer('show', this.uploadDialog, 500, 300, '<?=label("UPLOAD IMAGE")?>', {
  onClose:function(){
   f.setPictureList();
  }});
 this.initSWF();
};
f.closeUpload = function (){
 layer('hide', this.uploadDialog);
};
f.setPictureList = function (){
	var target = getEl('pictureList');
	target.innerHTML = '';
	
 var AJAX = new ajaxObject('get', this.url+'pictures', 'path='+ this.tmpID, {type:'DIALOG',target:this.pictureDialog,message:LANGUAGE['loading']});
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var error = AJAX.xml.getElementsByTagName('error');

   if (error.length > 0){
    messageDialog(error.item(0).firstChild.data, {type:'OK', icon:'warning.gif'});
   }else{
   	var xml = AJAX.xml.getElementsByTagName('list');
   
   	if (xml.length > 0){
   	 f.showPic(target,xml,0);
   	}else{
   	 target.innerHTML = '<div class="empty">&lt; <?=label("EMPTY");?> &gt;<\/div>';
    }
   }
  }else{
 	 target.innerHTML = '<div class="warning">'+ LANGUAGE['unknownError'] +'<\/div>';
  }
	}
};
f.showPic = function (target, arr, i){
 var dv = document.createElement('div');
 dv.style.textAlign = 'center';
 dv.style.backgroundColor = (i%2 == 0 ? '#f2f2f2' : '#ffffff');
 dv.style.padding = '5px';
 dv.style.margin = '5px 0';
 
 var im = new Image(110,80);
 dv.appendChild(im);
 
 im.onload = function (){
  fadeIn(im,0);
  target.appendChild(dv);
  
  if ((i+1) < arr.length){
   f.showPic(target,arr,(i+1));
  }
 }
 im.src = arr[i].firstChild.data +'?rnd='+ Math.random();
 im.style.padding = '0';
 im.style.border = '1px solid #aaaaaa';
 
 var dv2 = Layer.createElement('div', dv);
 dv2.style.padding = '5px';
 var bt = Layer.createElement('button', dv2);
 bt.innerHTML = '<img src="objects/icons/16x16/drop.png" alt="" /> <?=label("DELETE")?>';
 bt.onclick = function (){
  f.delPicture(arr[i].firstChild.data);
 }
};
f.delPicture = function (im){
 var AJAX = new ajaxObject('post', this.url+'delpic', 'picture='+ im, {type:'DIALOG', message:LANGUAGE['loading'], target:this.pictureDialog});
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var error = AJAX.xml.getElementsByTagName('error');

   if (error.length > 0){
    messageDialog(error.item(0).firstChild.data, {type:'OK', icon:'warning.gif'});
   }else{
   	var xml = AJAX.xml.getElementsByTagName('result');
   
   	if (xml.length > 0){
   	 if (xml.item(0).getAttribute('status') == 'OK'){
   	  f.setPictureList();
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
f.initSWF = function (){
 var settings = {
  flash_url : 'objects/assets/_system/swfupload.swf',
  upload_url: '<?=$_SERVER["PHP_SELF"]."?".ADMIN_EXTENSION."&pid=".PID."&sid=upload"?>',
  post_params: {
   'PHPSESSID' : '<?=session_id();?>'
  },
  file_size_limit : '2 MB',
  file_types : '*.png',
  file_types_description : '<?=label("PNG PICTURES")?>',
  file_upload_limit : 0,
  file_queue_limit : 4,
  custom_settings : {
   progressTarget : 'uploadProgress'
  },
  debug: false,
 
  // Button settings
  button_image_url: '<?=ADMIN_FOLDER?>/templates/_default/images/upload_button.png',
  button_width: '150',
  button_height: '27',
  button_placeholder_id: 'uploadSpan',
  button_text: '<span class="text">'+ LANGUAGE['pick_and_upload'] +'<\/span>',
  button_text_style: '.text { font-family:Arial; font-size:14px; text-align:center; font-weight:bold; color:#333333 }',
  button_text_left_padding: 0,
  button_text_top_padding: 3,
 
  swfupload_loaded_handler : setPath,
  file_queued_handler : fileQueued,
  file_queue_error_handler : fileQueueError,
  file_dialog_complete_handler : fileDialogComplete,
  upload_start_handler : uploadStart,
  upload_progress_handler : uploadProgress,
  upload_error_handler : uploadError,
  upload_success_handler : uploadSuccess,
  upload_complete_handler : uploadComplete,
  queue_complete_handler : queueComplete
 };
 
 swfu = new SWFUpload(settings);
};
f.filters = function (){
 return {
  'keyword': getValue('listKeyword'),
  'x': 0,
  'y': <?=Y?>
 }
};

function setPath (){
 swfu.addPostParam('path', f.tmpID);
}

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
<div id="tabs1" class="tabs">
  <ul>
    <li class="here"><a href="javascript:void(0)" onclick="f.tab(1,this)"><?=label("PRODUCT FEATURES")?></a></li>
    <li><a href="javascript:void(0)" onclick="f.tab(1,this)"><?=label("PRODUCT DESCRIPTION")?></a></li>
    <li><a href="javascript:void(0)" onclick="f.tab(1,this)"><?=label("PRODUCT PICTURES")?></a></li>
  </ul>
</div>
<div id="tabbing1" class="tabbing" style="height:330px">
  <div class="tab">
    <form id="dialogform" method="post" action="#">
    <table width="100%" border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td class="gridLeft" style="width:25%"><label for="dil"><?=label("PRODUCT LANGUAGE")?></label></td>
        <td class="gridRight">
          <?=formElement("hidden", "id", "", 0, "", "")?>
          <?=formElement("select", "dil", getLanguages(), LANG, "", "")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="kategoriSec"><?=label("PRODUCT CATEGORY")?></label></td>
        <td class="gridRight">
          <?=formElement("hidden", "kategori", "", 0, "", "")?>
          <?=formElement("text", "kategoriBaslik", "", label("NOT SELECTED YET"), "readonly", "style=\"width:300px\"")?>
          <?=formElement("button", "kategoriSec", "", label("PICK"), "", "onclick=\"f.showCategories()\" class=\"button\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="marka"><?=label("PRODUCT BRAND")?></label></td>
        <td class="gridRight">
          <?=formElement("select", "marka", getList("urun_marka", "id, isim", "aktif='1'", "isim", label("NO BRAND"), CONN), "", "", "style=\"width:300px\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="baslik"><?=label("PRODUCT TITLE")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "baslik", "", "", "", "style=\"width:300px\" maxlength=\"100\" onblur=\"f.checkTitle()\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="stokkod"><?=label("STOCK CODE")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "stokkod", "", "", "", "style=\"width:300px\" maxlength=\"10\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="hediyeli"><?=label("PRODUCT BOUNTY")?></label></td>
        <td class="gridRight">
          <?=formElement("checkbox", "hediyeli", 1, 0, "", "onclick=\"getEl('hediye').style.visibility = (this.checked) ? 'visible' : 'hidden'\"")?>
          <label for="hediyeli"><?=label("YES")?></label>
          &nbsp;&nbsp;
          <?=formElement("text", "hediye", "", "Hediyeli Ürün", "", "style=\"visibility:hidden; width:250px\" maxlength=\"50\"")?>
        </td>
      </tr>
    </table>
    <table width="100%" border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td style="width:25%" class="gridLeft"><label for="alisfiyat"><?=label("PURCHASE PRICE")?></label></td>
        <td style="width:25%" class="gridRight">
          <?=formElement("text", "alisfiyat", "", "", "", "style=\"width:65px; text-align:right\" maxlength=\"10\"")?>
          <?=formElement("select", "alisfiyatbirim", $parabirims, "", "", "")?>
        </td>
        <td style="width:25%" class="gridLeft"><label for="satisfiyat"><?=label("SELLING PRICE")?></label></td>
        <td style="width:25%" class="gridRight">
          <?=formElement("text", "satisfiyat", "", "", "", "style=\"width:65px; text-align:right\" maxlength=\"10\"")?>
          <?=formElement("select", "satisfiyatbirim", $parabirims, "", "", "")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="kdv"><?=label("TAX RATIO")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "kdv", "", "", "", "style=\"width:30px;\" maxlength=\"2\" onkeypress=\"return onlyNum(event)\"")?>
          %
        </td>
        <td class="gridLeft"><label for="indirim"><?=label("DISCOUNT")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "indirim", "", "0", "", "style=\"width:50px; text-align:right\" maxlength=\"5\" onkeypress=\"return onlyNum(event)\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="havaleindirim"><?=label("BANKING DISCOUNT")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "havaleindirim", "", "", "", "style=\"width:30px;\" maxlength=\"2\" onkeypress=\"return onlyNum(event)\"")?>
          %
        </td>
        <td class="gridLeft"><label for="kargodesi"><?=label("SHIPPING MEASURE")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "kargodesi", "", "", "", "style=\"width:50px; text-align:right\" maxlength=\"5\"")?>
          (desi / kg)
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="garanti"><?=label("WARRANTY")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "garanti", "", "", "", "style=\"width:30px;\" maxlength=\"2\" onkeypress=\"return onlyNum(event)\"")?>
          (<?=label("MONTH")?>)
        </td>
        <td class="gridLeft"><label for="aktif_1"><?=label("PRODUCT STATUS")?></label></td>
        <td class="gridRight">
          <?=formElement("radio", "aktif", 1, 1, "", "", "aktif_1")?> <label for="aktif_1"><?=label("OPEN")?></label>
          <?=formElement("radio", "aktif", 0, 1, "", "", "aktif_0")?> <label for="aktif_0"><?=label("CLOSE")?></label>
        </td>
      </tr>
    </table>
    </form>
  </div>
  <div class="tab" style="display:none">
    <?=formElement("hidden", "detay", "", "", "", "")?>
    <fieldset>
      <div id="detayDiv" style="height:280px; overflow:auto"><div class="empty">&lt; <?=label("EMPTY")?> &gt;</div></div>
    </fieldset>
    <div class="contentEdit">
      <a class="edit" href="javascript:void(0)" onclick="f.showEditor('detay')"><?=label("EDIT")?></a>
      <a class="clear" href="javascript:void(0)" onclick="f.clearHTML('detay')"><?=label("CLEAR")?></a>
    </div>
  </div>
  <div class="tab" style="display:none">
    <fieldset>
      <div id="pictureList" style="height:280px; overflow:auto"><div class="empty">&lt; <?=label("NO PICTURE FOUND")?> &gt;</div></div>
    </fieldset>
    <div class="contentEdit">
      <a class="upload" href="javascript:void(0)" onclick="f.showUpload()"><?=label("UPLOAD IMAGE")?></a>
    </div>
  </div>
</div>
</div>

<div id="dialogCategory" style="display:none">
<div id="categoryList" style="height:300px; overflow:auto"></div>
</div>

<div id="dialogUpload" style="display:none">
<form id="dialogUploadform" method="post" enctype="multipart/form-data" action="<?=CONF_MAIN_PAGE?>">
<fieldset><legend><?=label("UPLOAD QUEUE")?></legend>
  <div id="uploadProgress" style="height:180px; overflow:auto"></div>
	 <div id="uploadResult" style="margin:5px 0; height:20px"></div>
</fieldset>
<div style="text-align:right">
 	<div id="uploadDiv" style="display:inline"></div>
 	<?=formButton("button", "okBtn", label("OK"), "", "apply.png", "onclick=\"f.closeUpload()\" style=\"height:27px\"")?>
</div>
</form>
</div>
