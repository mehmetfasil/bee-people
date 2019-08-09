<?php
defined("PASS") or die("Dosya yok!");

//Lookup Dosya
include_once(dirname(__FILE__).DS."lookups.php");
?>
<script type="text/javascript" src="objects/js/swfupload.js"></script>
<script type="text/javascript" src="objects/js/calendar.js"></script>
<script type="text/javascript" src="objects/js/tree/treemenu.js"></script>
<script type="text/javascript" src="objects/js/tree/treeconfig.js"></script>
<script type="text/javascript">
//<![CDATA[
var swfu, tree;
var f = new formAction();
f.url = url +'&act=';
f.width = 600;
f.title = '<?=label("NEWS")?>';
f.onShow = function (){
 this.tab(1);
 this.removeErrorSpan('title');
 this.removePicture();
 getEl('expiredate').disabled = true;
 getEl('expiredatePick').disabled = true;
 getEl('descriptionDiv').innerHTML = '<div class="empty">&lt; <?=label("EMPTY");?> &gt;<\/div>';
 getEl('detailDiv').innerHTML = '<div class="empty">&lt; <?=label("EMPTY");?> &gt;<\/div>';
 return true;
};
f.onSave = function (){
 var tabs = getEl('tabs1').getElementsByTagName('a');
 if (getValue('category',0) < 1){
  messageDialog('<?=label("NEWS CATEGORY MUST BE SELECTED")?>', {type:'OK', icon:'caution.gif', functionOK:function(){f.tab(1,tabs[0]); getEl('categoryPick').focus()}});
  return false;
 }else if (f.errorSpan['title'] || (getValue('title').length < 1)){
  messageDialog('<?=label("NEWS TITLE EMPTY")?>', {type:'OK', icon:'caution.gif', functionOK:function(){f.tab(1,tabs[0]); getEl('title').focus()}});
  return false;
 }else if (getValue('description').length < 1){
  messageDialog('<?=label("NEWS DESCRIPTION MUST BE FILLED")?>', {type:'OK', icon:'caution.gif', functionOK:function(){f.tab(1,tabs[1])}});
  return false;
 }else if (!getEl('unlimited').checked && (getValue('expiredate').length != 10)){
  messageDialog('<?=label("EXPIRE DATE MUST BE FILLED")?>', {type:'OK', icon:'caution.gif', functionOK:function(){f.tab(1,tabs[0]); getEl('expiredatePick').focus()}});
  return false;
 }
 
 return true;
};
f.checkTitle = function (){
 var target = getEl('title');
 this.removeErrorSpan('title');
 if (getValue('title').length < 1){
  this.showErrorSpan('title','<?=label("EMPTY")?>');
 }
};
f.removePicture = function (){
 getEl('pictureImg').src = 'objects/assets/default_image.png';
 getEl('pictureName').innerHTML = '<?=label("NOT SELECTED YET")?>';
 setParam('picture', ''); 
};
f.showEditor = function (objID){
 if (<?=menuID("ADMIN_EDITOR")?> > 0){
  var u = '<?=CONF_MAIN_PAGE."?".ADMIN_EXTENSION."&pid=".menuID("ADMIN_EDITOR")?>&target='+ objID;
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
 getEl('descriptionDiv').innerHTML = getValue('description').length > 0 ? getValue('description') : '<div class="empty">&lt; <?=label("EMPTY");?> &gt;<\/div>';
 getEl('detailDiv').innerHTML = getValue('detail').length > 0 ? getValue('detail') : '<div class="empty">&lt; <?=label("EMPTY");?> &gt;<\/div>';
};
f.isDate = function (obj){
 if (!isDate(obj)){
  messageDialog('<?=label("INVALID DATE")?>', {type:'OK', icon:'warning.gif', functionOK:function(){obj.value=''; obj.focus()}});
 }
};
f.expiredateCheck = function (){
 if (getEl('unlimited').checked){
  getEl('expiredate').value = '';
  getEl('expiredate').disabled = true;
  getEl('expiredatePick').disabled = true;
 }else{
  getEl('expiredate').disabled = false;
  getEl('expiredatePick').disabled = false;
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
	
 var AJAX = new ajaxObject('get', this.url+'categories&step=parents', null, {type:'DIALOG',target:this.categoryDialog,message:LANGUAGE['loading']});
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
 setParam('category',objID);
 setParam('categoryTitle',objTitle);
};
f.showPicture = function (){
 this.pictureDialog = 'dialogPicture';
 var buttons = {
  type:'OK',
  labelOK:LANGUAGE['cancel'],
  functionOK:function(){
   layer('hide',f.pictureDialog);
  }
 };
 layer('show', this.pictureDialog, 400, 300, '<?=label("PICK AN IMAGE")?>', buttons);
 this.setPictureList(); 
};
f.setPictureList = function (){
	var target = getEl('pictureList');
	target.innerHTML = '';
	
 var AJAX = new ajaxObject('get', this.url+'pictures', null, {type:'DIALOG',target:this.pictureDialog,message:LANGUAGE['loading']});
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
   	 target.innerHTML = '<div class="warning">'+ LANGUAGE['noResult'] +'<\/div>';
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
 bt.innerHTML = '<img src="objects/icons/16x16/apply.png" alt="" /> <?=label("PICK")?>';
 bt.onclick = function (){
  var m = arr[i].firstChild.data.match(/.*\/(.*?\.jpg)$/);
  setParam('picture', arr[i].firstChild.data);
  getEl('pictureImg').src = arr[i].firstChild.data;
  getEl('pictureName').innerHTML = m[1] +' <a href="javascript:void(0)" onclick="f.removePicture()"><img src="objects/icons/16x16/drop.png" width="16" height="16" alt="" /><\/a>';
  layer('hide', f.pictureDialog);
 }
 var bt2 = Layer.createElement('button', dv2);
 bt2.innerHTML = '<img src="objects/icons/16x16/drop.png" alt="" /> <?=label("DELETE")?>';
 bt2.onclick = function (){
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
f.initSWF = function (){
 var settings = {
  flash_url : 'objects/assets/_system/swfupload.swf',
  upload_url: '<?=$_SERVER["PHP_SELF"]."?".ADMIN_EXTENSION."&pid=".PID."&sid=upload"?>',
  post_params: {
   'PHPSESSID' : '<?=session_id();?>'
  },
  file_size_limit : '8 MB',
  file_types : '*.jpg',
  file_types_description : '<?=label("JPG IMAGES")?>',
  file_upload_limit : 0,
  file_queue_limit : 0,
  custom_settings : {
   progressTarget : 'uploadProgress'
  },
  debug: false,
 
  button_image_url: '<?=ADMIN_FOLDER?>/templates/_default/images/upload_button.png',
  button_width: '150',
  button_height: '27',
  button_placeholder_id: 'uploadSpan',
  button_text: '<span class="text">'+ LANGUAGE['pick_and_upload'] +'<\/span>',
  button_text_style: '.text { font-family:Arial; font-size:14px; text-align:center; font-weight:bold; color:#333333 }',
  button_text_left_padding: 0,
  button_text_top_padding: 4,
 
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
  'orderby': getValue('listOrder'),
  'order': getValue('listBy'),
  'keyword': getValue('listKeyword'),
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
  <label for="listKeyword"><?=label("FILTER")?>:</label>
  <?=formElement("text", "listKeyword", "", "", "", "onkeypress=\"if((event.keyCode==13)||(event.charCode==13)){f.list(f.filters())}\" class=\"searchInput\"")?>
</div>
<div class="link"><a class="new" href="javascript:void(0)" onclick="f.show()"><?=label("CREATE NEW")?></a></div>

<fieldset><legend><?=label("RECORDS")?></legend>
<div id="list"><div class="warning"><?=label("LOADING")?></div></div>
</fieldset>

<div id="dialog" style="display:none">
<form id="dialogform" method="post" action="#">
<div id="tabs1" class="tabs">
  <ul>
    <li class="here"><a href="javascript:void(0)" onclick="f.tab(1,this)"><?=label("PROPERTIES")?></a></li>
    <li><a href="javascript:void(0)" onclick="f.tab(1,this)"><?=label("DESCRIPTION")?></a></li>
    <li><a href="javascript:void(0)" onclick="f.tab(1,this)"><?=label("DETAILS")?></a></li>
  </ul>
</div>
<div id="tabbing1" class="tabbing" style="height:330px">
  <div class="tab">
    <fieldset>
    <table width="100%" border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td class="gridLeft" style="width:25%"><label><?=label("NEWS CATEGORY")?></label></td>
        <td class="gridRight">
          <?=formElement("hidden", "id", "", 0, "", "")?>
          <?=formElement("hidden", "category", "", 0, "", "")?>
          <?=formElement("text", "categoryTitle", "", label("NOT SELECTED YET"), "readonly", "style=\"width:300px\"")?>
          <?=formElement("button", "categoryPick", "", label("PICK"), "", "onclick=\"f.showCategories()\" class=\"button\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="title"><?=label("NEWS TITLE")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "title", "", "", "", "style=\"width:300px\" maxlength=\"100\" onblur=\"f.checkTitle()\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="subtitle"><?=label("NEWS SUBTITLE")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "subtitle", "", "", "", "style=\"width:300px\" maxlength=\"255\"")?>
          (Max: 255)
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="author"><?=label("NEWS AUTHOR")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "author", "", "", "", "style=\"width:300px\" maxlength=\"100\"")?>
          <?=formElement("button", "authorBtn", "", label("ME"), "", "onclick=\"setParam('author','".$_SESSION["SYS_USER_FULLNAME"]."')\" class=\"button\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="publishdatePick"><?=label("PUBLISH DATE")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "publishdate", "", date("d-m-Y"), "", "style=\"width:80px\" maxlength=\"10\" onblur=\"f.isDate(this)\"")?>
          <?=formElement("button", "publishdatePick", "", "...", "", "class=\"button\" onclick=\"return showCalendar('publishdate');\"")?>
          &nbsp;&nbsp;
          -
          &nbsp;&nbsp;
          <?=formElement("checkbox", "unlimited", 1, 1, "", "onclick=\"f.expiredateCheck()\"")?>
          <label for="unlimited"><?=label("UNLIMITED")?></label>
          &nbsp;&nbsp;
          <?=formElement("text", "expiredate", "", "", "disabled", "style=\"width:80px\" maxlength=\"10\" onblur=\"f.isDate(this)\"")?>
          <?=formElement("button", "expiredatePick", "", "...", "disabled", "class=\"button\" onclick=\"return showCalendar('expiredate');\"")?>
        </td>
      </tr>
    </table>
    </fieldset>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top" style="width:60%">
          <fieldset>
            <table width="100%" border="0" cellpadding="5" cellspacing="0">
              <tr>
                <td class="gridLeft" style="width:40%"><label for="language"><?=label("NEWS LANGUAGE")?></label></td>
                <td class="gridRight">
                  <?=formElement("select", "language", getLanguages(), LANG, "", "")?>
                </td>
              </tr>
              <tr>
                <td class="gridLeft"><label for="level"><?=label("NEWS LEVEL")?></label></td>
                <td class="gridRight">
                  <?=formElement("select", "level", $levels, "", "", "style=\"width:150px\"")?>
                </td>
              </tr>
              <tr>
                <td class="gridLeft"><label for="active_1"><?=label("NEWS STATUS")?></label></td>
                <td class="gridRight">
                  <?=formElement("radio", "active", "1", "1", "", "", "active_1")?> <label for="active_1"><?=label("OPEN")?></label>
                  <?=formElement("radio", "active", "0", "1", "", "", "active_0")?> <label for="active_0"><?=label("CLOSE")?></label>
                </td>
              </tr>
              <tr>
                <td class="gridLeft"><label for="hit"><?=label("NEWS READ")?></label> / <label for="rate"><?=label("NEWS RATE")?></label></td>
                <td class="gridRight">
                  <?=formElement("text", "hit", "", 0, "readonly", "style=\"width:60px; text-align:right\"")?>
                  /
                  <?=formElement("text", "rate", "", 0, "readonly", "style=\"width:60px; text-align:right\"")?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
        <td valign="top" style="width:40%">
          <fieldset>
            <table width="100%" border="0" cellpadding="5" cellspacing="0">
              <tr>
                <td style="text-align:center">
                  <?=formElement("hidden", "picture", "", "", "", "")?>
                  <div><a href="javascript:void(0)" onclick="f.showPicture()"><?=label("PICK AN IMAGE")?></a></div>
                  <div style="padding:3px">
                    <img id="pictureImg" src="objects/assets/default_image.png" width="110" height="80" alt="" />
                  </div>
                  <div id="pictureName" class="smallDescription"><?=label("NOT SELECTED YET")?></div>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
    </table>    
  </div>
  <div class="tab" style="display:none">
    <?=formElement("hidden", "description", "", "", "", "")?>
    <fieldset>
      <div id="descriptionDiv" style="width:570px; height:280px; overflow:auto"><div class="empty">&lt; <?=label("EMPTY");?> &gt;</div></div>
    </fieldset>
    <div class="contentEdit">
      <a class="edit" href="javascript:void(0)" onclick="f.showEditor('description')"><?=label("EDIT")?></a>
      <a class="clear" href="javascript:void(0)" onclick="f.clearHTML('description')"><?=label("CLEAR")?></a>
    </div>
  </div>
  <div class="tab" style="display:none">
    <?=formElement("hidden", "detail", "", "", "", "")?>
    <fieldset>
      <div id="detailDiv" style="width:570px; height:280px; overflow:auto"><div class="empty">&lt; <?=label("EMPTY");?> &gt;</div></div>
    </fieldset>
    <div class="contentEdit">
      <a class="edit" href="javascript:void(0)" onclick="f.showEditor('detail')"><?=label("EDIT")?></a>
      <a class="clear" href="javascript:void(0)" onclick="f.clearHTML('detail')"><?=label("CLEAR")?></a>
    </div>
  </div>
</div>
</form>
</div>

<div id="dialogCategory" style="display:none">
<div id="categoryList" style="height:300px; overflow:auto"></div>
</div>

<div id="dialogPicture" style="display:none">
<div style="text-align:right"><a class="upload" href="javascript:void(0)" onclick="f.showUpload()"><?=label("UPLOAD AN IMAGE")?></a></div>
<div id="pictureList" style="height:300px; overflow:auto"></div>
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
