<?php
defined("PASS") or die("Dosya yok!");

//Lookup Dosya
include_once(dirname(__FILE__).DS."lookups.php");
?>
<script type="text/javascript" src="objects/js/swfupload.js"></script>
<script type="text/javascript">
//<![CDATA[
var f = new formAction();
f.url = url +'&act=';
f.width = 600;
f.title = '<?=label("PICTURE GALLERY")?>';
f.onShow = function (){
 this.removeErrorSpan('name');
 this.removeErrorSpan('title');
 getEl('name').readOnly = true;
 return true;
};
f.onInit = function (){
 getEl('name').readOnly = false;
};
f.onSave = function (){
 if ((getValue('name').length < 1) || f.errorSpan['name']){
  var msg = '<?=label("GALLERY NAME EMPTY")?>';
  if (getValue('name').length < 1){
   msg = '<?=label("GALLERY NAME EMPTY")?>';
  }else if (!getValue('name').match(/^[a-zA-Z0-9_]+$/)){
   msg = '<?=label("GALLERY NAME CONTAINS INVALID CHARACTERS")?>';
  }
  messageDialog(msg, {type:'OK', icon:'caution.gif', functionOK:function(){getEl('name').select()}});
  return false;
 }else if (getValue('title').length < 1){
  messageDialog('<?=label("GALLERY TITLE CANNOT BE BLANK")?>', {type:'OK', icon:'caution.gif', functionOK:function(){getEl('title').focus()}});
  return false;
 }else if ((getValue('width',0) < 1) || (getValue('height',0) < 1)){
  messageDialog('<?=label("PICTURE SIZES CANNOT BE BLANK")?>', {type:'OK', icon:'caution.gif', functionOK:function(){getEl('width').select()}});
  return false;
 }
 return true;
};
f.onAfterDelete = function (){
 this.list(this.filters());
 getEl('pictureList').innerHTML = '<div class="warning"><?=label("PICK A GALLERY")?><\/div>';
};
f.checkName = function (){
 var target = getEl('name');
 if (!target.readOnly){
  this.removeErrorSpan('name');
  if (getValue('name').length > 0){
   if (getValue('name').match(/^[a-zA-Z0-9_]+$/)){
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
    this.showErrorSpan('name','<?=label("INVALID")?>');
   }
  }else{
   this.showErrorSpan('name','<?=label("EMPTY")?>');
  }
 }
};
f.checkEmpty = function (field){
 this.removeErrorSpan(field); 
 if (getValue(field).length < 1){
  this.showErrorSpan(field,'<?=label("EMPTY")?>');
 }
};
f.filters = function (){
 return {
  'keyword': getValue('listKeyword')
 }
};
f.list = function (uriArr){
 var target = getEl('galleryList');
 getEl('totalDiv').innerHTML = '';
 
 var uri = null;
 if (uriArr){
  uri = '';
  for (var prop in uriArr){
   uri += '&'+ prop +'='+ encodeURIComponent(uriArr[prop]);
  }
  this.uri = uriArr;
 }
 var AJAX = new ajaxObject('get', this.url+'list', uri, {type:'LIST', message:LANGUAGE['loading'], target:target});
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var xml = AJAX.xml.getElementsByTagName('list');
   var result = '';
   
   if (xml.length > 0){
    for (var i=0; i < xml.length; i++){
     result += '<div class="box">'
     + '<div>'
     + '<table width="100%" border="0" cellpadding="5" cellspacing="0">';
     
     var nod = xml.item(i).childNodes;
     
     for (var y=0; y < nod.length; y++){
      result += '<tr>'
      + '<td class="gridLeft" style="width:40%"><label>'+ nod[y].getAttribute('label') +'<\/label><\/td>'
      + '<td class="gridRight"'+ (nod[y].getAttribute('id') ? ' id="'+ nod[y].getAttribute('id') + xml.item(i).getAttribute('id') +'"' : '') +'>'+ nod[y].firstChild.data +'<\/td>'
      + '<\/tr>';
     }
     
     result += '<\/table>'
     + '<\/div>'
     + '<div class="buttonBar">'
     + '<a class="edit" href="javascript:void(0)" onclick="f.show('+ xml.item(i).getAttribute('id') +')"><?=label("EDIT")?><\/a>'
     + '&nbsp;&nbsp;'
     + '<a class="picture" href="javascript:void(0)" onclick="f.setPictureList('+ xml.item(i).getAttribute('id') +')"><?=label("PICTURES")?><\/a>'
     + '&nbsp;&nbsp;'
     + '<a class="xml" href="javascript:void(0)" onclick="f.createXMLFile('+ xml.item(i).getAttribute('id') +')"><?=label("CREATE XML")?><\/a>'
     + '<\/div>'
     + '<\/div>';
    }
    
    getEl('totalDiv').innerHTML = '<?=label("TOTAL")?>: <b>'+ xml.length +'<\/b>';
   }else{
    result += '<div class="warning">'+ LANGUAGE['noResult'] +'<\/div>';
   }
   
   target.innerHTML = result;
  }else{
   messageDialog(LANGUAGE['unknownError'], {type:'OK', icon:'warning.gif'});
  }
 }
};
f.setPictureList = function (id){
 var target = getEl('pictureList'), result = document.createElement('div');
 var AJAX = new ajaxObject('get', this.url+'pictures', 'id='+id, {type:'LIST', message:LANGUAGE['loading'], target:target});
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var info = AJAX.xml.getElementsByTagName('info');
   
   if (info.length > 0){
    var gallery = {
     'id': info.item(0).getAttribute('id'),
     'name': info.item(0).getAttribute('name'),
     'title': info.item(0).firstChild.data,
     'format': {
      'extension': info.item(0).getAttribute('image_extension'),
      'name': info.item(0).getAttribute('image_name')
     },
     'sizes': info.item(0).getAttribute('image_sizes')
    };
    
    var dv = document.createElement('div');
    dv.className = 'link';
    
    result.appendChild(dv);
    
    var a = document.createElement('a');
    a.className = 'picture';
    a.href = 'javascript:void(0)';
    a.innerHTML = '<?=label("UPLOAD IMAGE")?>';
    a.onclick = function (){
     f.showUpload(gallery);
    };
    dv.appendChild(a);
    
    var xml = AJAX.xml.getElementsByTagName('list');
    
    if (xml.length > 0){
     if (getEl('total'+ id)){
      getEl('total'+ id).innerHTML = xml.length;
     }
     //Ekleyelim
     for (var i=0; i < xml.length; i++){
      result.appendChild(f.setPicture({
       id:xml.item(i).getAttribute('id'),
       title:xml.item(i).childNodes[0].firstChild.data,
       description:xml.item(i).childNodes[1].firstChild.data,
       path:xml.item(i).childNodes[2].firstChild.data,
       active:xml.item(i).childNodes[3].firstChild.data
      }));
     }
    }else{
     var wr = document.createElement('div');
     wr.className = 'warning';
     wr.innerHTML = LANGUAGE['noResult'];
     result.appendChild(wr);
    }
   }else{
    var wr = document.createElement('div');
    wr.className = 'warning';
    wr.innerHTML = '<?=label("GALLERY INFORMATION IS NULL")?>';
    result.appendChild(wr);
   }
  }else{
   var wr = document.createElement('div');
   wr.className = 'warning';
   wr.innerHTML = LANGUAGE['unknownError'];
   result.appendChild(wr);
  }
  target.appendChild(result);
 }
};
f.createXMLFile = function (id){
 var AJAX = new ajaxObject('post', this.url+'xml', 'id='+id, {type:'MESSAGE', message:LANGUAGE['saving']});
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var xml = AJAX.xml.getElementsByTagName('result');
   if (xml.length > 0){
    messageDialog(xml.item(0).firstChild.data, {icon:(xml.item(0).getAttribute('status') == 'OK'?'info':'caution')+'.gif'});
   }
  }
 }
};
f.setPicture = function (picture){
 var el = document.createElement('div');
 el.id = 'picture'+picture.id;
 el.className = 'box';
 
 var h = '<table width="100%" border="0" cellpadding="5" cellspacing="0">'
 + '<tr>'
 + '<td rowspan="4" style="width:300px">'
 + '<img src="objects/icons/spacer.gif" width="300" height="250" alt="" style="background:url('+ picture.path +'?rnd='+ Math.random() +') no-repeat center center; border:1px solid #aaaaaa" \/>'
 + '<\/td>'
 + '<\/tr>'
 + '<tr>'
 + '<td>'
 + '<div class="question"><label for="title'+ picture.id +'"><?=label("PICTURE TITLE")?><\/label><\/div>'
 + '<div class="answer">'
 + '<input type="text" id="title'+ picture.id +'" name="title'+ picture.id +'" value="'+ picture.title +'" style="width:450px" maxlength="100" \/>'
 + '<\/div>'
 + '<div class="question"><label for="description'+ picture.id +'"><?=label("PICTURE DESCRIPTION")?><\/label><\/div>'
 + '<div class="answer">'
 + '<textarea id="description'+ picture.id +'" name="description'+ picture.id +'" cols="5" rows="5" style="width:450px; height:120px">'+ picture.description +'<\/textarea>'
 + '<\/div>'
 + '<div class="question"><label for="active'+ picture.id +'"><?=label("PICTURE STATUS")?><\/label><\/div>'
 + '<div class="answer">'
 + '<input type="checkbox" id="active'+ picture.id +'" name="active'+ picture.id +'" value="1"'+ (picture.active=='1' ? ' checked="checked"' : '') +' \/>'
 + '<label for="active'+ picture.id +'"><?=label("PUBLISHED")?><\/label>'
 + '<\/div>'
 + '<\/td>'
 + '<\/tr>'
 + '<tr>'
 + '<td>'
 + '<button type="button" id="saveButton'+ picture.id +'" name="saveButton'+ picture.id +'" style="width:120px" onclick="f.savePic('+ picture.id +')">'
 + '<img src="objects/icons/16x16/save.png" width="16" height="16" alt="" \/> '
 + LANGUAGE['save'] 
 + '<\/button>'
 + '<button type="button" id="deleteButton'+ picture.id +'" name="deleteButton'+ picture.id +'" style="width:120px" onclick="f.delPic('+ picture.id +')">'
 + '<img src="objects/icons/16x16/delete.png" width="16" height="16" alt="" \/> '
 + LANGUAGE['del'] 
 + '<\/button>'
 + '<\/td>'
 + '<\/tr>'
 + '<\/table>';
 
 el.innerHTML = h;
 
 return el;
};
f.showUpload = function (gallery){
 this.uploadDialog = 'dialogUpload';
 getEl('uploadDiv').innerHTML = '<span id="uploadSpan"><\/span>';
 getEl('uploadProgress').innerHTML = '';
 getEl('uploadResult').innerHTML = '';
 
 getEl('galleryTitleSpan').innerHTML = gallery.title;
 getEl('pictureFormatSpan').innerHTML = gallery.format.name;
 var raw = gallery.sizes.split('|');
 getEl('pictureSizeSpan').innerHTML = raw[0] +' x '+ raw[1];
 getEl('pictureThumbSizeSpan').innerHTML = raw[3] +' x '+ raw[4];
 
 layer('show', this.uploadDialog, 500, 300, '<?=label("UPLOAD IMAGE")?>', {
  onClose:function(){
   f.setPictureList(gallery.id);
  }});
 this.initSWF(gallery);
};
f.closeUpload = function (){
 layer('hide', this.uploadDialog);
};
f.initSWF = function (gallery){
 var settings = {
  flash_url : 'objects/assets/_system/swfupload.swf',
  upload_url: '<?=$_SERVER["PHP_SELF"]."?".ADMIN_EXTENSION."&pid=".PID."&sid=upload"?>',
  post_params: {
   'PHPSESSID' : '<?=session_id();?>',
   'id': gallery.id,
   'path': gallery.name,
   'extension': gallery.format.extension,
   'sizes': gallery.sizes
  },
  file_size_limit : '2 MB',
  file_types : '*.'+ gallery.format.extension,
  file_types_description : gallery.format.name,
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
f.savePic = function (id){
 var params = 'id='+ id
 + '&title='+ encodeURIComponent(getValue('title'+id))
 + '&description='+ encodeURIComponent(getValue('description'+id))
 + '&active='+ getValue('active'+id, 0);
 var AJAX = new ajaxObject('post', f.url+'picture&step=save', params, {type:'MESSAGE', message:LANGUAGE['saving']});
 AJAX.run();
 AJAX.onLoad = function (){
  if (AJAX.xml){
   var xml = AJAX.xml.getElementsByTagName('result');
   
   if (xml.length > 0){
    messageDialog(xml.item(0).firstChild.data, {icon:(xml.item(0).getAttribute('status') == 'OK' ? 'info' : 'caution')+'.gif'});
   }else{     
    messageDialog(LANGUAGE['noReturn'], {icon:'warning.gif'});
   }
  }else{
   messageDialog(LANGUAGE['unknownError'], {icon:'warning.gif'});
  }
 }
};
f.delPic = function (id){
 messageDialog('<?=label("ARE YOU SURE YOU WANT TO DELETE")?>', {type:'YESNO', icon:'caution.gif', functionYES:function(){
  var AJAX = new ajaxObject('post', f.url+'picture&step=delete', 'id='+ id, {type:'MESSAGE', message:LANGUAGE['deleting']});
  AJAX.run();
  AJAX.onLoad = function (){
   if (AJAX.xml){
    var xml = AJAX.xml.getElementsByTagName('result');
    
    if (xml.length > 0){
     if (xml.item(0).getAttribute('status') == 'OK'){
      var target = getEl('picture'+ id);
      if (target){
       target.parentNode.removeChild(target);
       var gID = xml.item(0).getAttribute('id');
       if (getEl('total'+ gID)){
        getEl('total'+ gID).innerHTML = (parseInt(getEl('total'+ gID).innerHTML) - 1);
       }
       var tmp = [];
       var els = getEl('pictureList').getElementsByTagName('div');
       for (var i=0; i < els.length; i++){
        if (els[i].id.indexOf('picture') == 0){
         tmp.push(els[i].id);
        }
       }
       if (tmp.length < 1){
        var wr = document.createElement('div');
        wr.className = 'warning';
        wr.innerHTML = LANGUAGE['noResult'];
        getEl('pictureList').appendChild(wr);
       }
      }
     }else{
      messageDialog(xml.item(0).firstChild.data, {icon:'caution.gif'});
     }
    }else{     
     messageDialog(LANGUAGE['noReturn'], {icon:'warning.gif'});
    }
   }else{
    messageDialog(LANGUAGE['unknownError'], {icon:'warning.gif'});
   }
  }
 }});
};

addListener(window,'load',function(){
 f.list(f.filters());
});
//]]>
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td style="width:300px" valign="top">
      <fieldset>
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
          <tr>
            <td class="gridLeft" style="width:20%"><label for="listKeyword"><?=label("FILTER")?></label></td>
            <td class="gridRight">
              <?=formElement("text", "listKeyword", "", "", "", "onkeypress=\"if((event.keyCode==13)||(event.charCode==13)){f.list(f.filters())}\" class=\"searchInput\" style=\"width:180px\"")?>
            </td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <div class="box" style="padding:5px">
          <a class="new" href="javascript:void(0)" onclick="f.show()"><?=label("CREATE NEW")?></a>
          <div id="totalDiv" style="position:absolute; right:0; top:0; padding:5px"></div>
        </div>
        <div class="list" id="galleryList" style="height:450px"><div class="warning"><?=label("LOADING")?></div></div>
      </fieldset>
    </td>
    <td valign="top">
      <fieldset>
        <div class="list" id="pictureList" style="height:530px"><div class="warning"><?=label("PICK A GALLERY")?></div></div>
      </fieldset>
    </td>
  </tr>
</table>

<div id="dialog" style="display:none">
<form id="dialogform" method="post" action="#">
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td class="gridLeft" style="width:25%"><label for="language"><?=label("GALLERY LANGUAGE")?></label></td>
    <td class="gridRight">
      <?=formElement("hidden", "id", "", 0, "", "")?>
      <?=formElement("select", "language", getLanguages(), LANG, "", "")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="name"><?=label("GALLERY NAME")?></label></td>
    <td class="gridRight">
      <?=formElement("text", "name", "", "", "readonly", "style=\"width:300px\" maxlength=\"50\" onblur=\"f.checkName()\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="title"><?=label("GALLERY TITLE")?></label></td>
    <td class="gridRight">
      <?=formElement("text", "title", "", "", "", "style=\"width:300px\" maxlength=\"50\" onblur=\"f.checkEmpty('title')\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft" valign="top"><label for="description"><?=label("GALLERY DESCRIPTION")?></label></td>
    <td class="gridRight">
      <?=formElement("textarea", "description", "", "", "", "style=\"width:300px; height:80px\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="author"><?=label("GALLERY AUTHOR")?></label></td>
    <td class="gridRight">
      <?=formElement("text", "author", "", "", "", "style=\"width:300px\" maxlength=\"50\"")?>
      <?=formElement("button", "authorBtn", "", label("ME"), "", "onclick=\"setParam('author','".$_SESSION["SYS_USER_FULLNAME"]."')\" class=\"button\"")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="width"><?=label("PICTURE SIZE")?></label></td>
    <td class="gridRight">
      <label for="width"><?=label("WIDTH")?>:</label>
      <?=formElement("text", "width", "", 100, "", "style=\"width:40px\" maxlength=\"4\" onkeypress=\"return onlyNum(event)\"")?>
      &nbsp;&nbsp;
      <label for="height"><?=label("HEIGHT")?>:</label>
      <?=formElement("text", "height", "", 100, "", "style=\"width:40px\" maxlength=\"4\" onkeypress=\"return onlyNum(event)\"")?>
      &nbsp;&nbsp;
      <label for="ratio"><?=label("ASPECT RATIO")?>:</label>
      <?=formElement("checkbox", "ratio", 1, 0, "", "")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="thumb_width"><?=label("THUMB SIZE")?></label></td>
    <td class="gridRight">
      <label for="thumb_width"><?=label("WIDTH")?>:</label>
      <?=formElement("text", "thumb_width", "", "", "", "style=\"width:40px\" maxlength=\"4\" onkeypress=\"return onlyNum(event)\"")?>
      &nbsp;&nbsp;
      <label for="thumb_height"><?=label("HEIGHT")?>:</label>
      <?=formElement("text", "thumb_height", "", "", "", "style=\"width:40px\" maxlength=\"4\" onkeypress=\"return onlyNum(event)\"")?>
      &nbsp;&nbsp;
      <label for="thumb_ratio"><?=label("ASPECT RATIO")?>:</label>
      <?=formElement("checkbox", "thumb_ratio", 1, 0, "", "")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="extension"><?=label("PICTURE FORMAT")?></label></td>
    <td class="gridRight">
      <?=formElement("select", "extension", $extensions, "", "", "")?>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label for="active_1"><?=label("GALLERY STATUS")?></label></td>
    <td class="gridRight">
      <?=formElement("radio", "active", "1", "1", "", "", "active_1")?> <label for="active_1"><?=label("OPEN")?></label>
      <?=formElement("radio", "active", "0", "1", "", "", "active_0")?> <label for="active_0"><?=label("CLOSE")?></label>
    </td>
  </tr>
</table>
</form>
</div>

<div id="dialogUpload" style="display:none">
<form id="dialogUploadform" method="post" enctype="multipart/form-data" action="<?=CONF_MAIN_PAGE?>">
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td class="gridLeft" style="width:35%"><label><?=label("GALLERY TITLE")?></label></td>
    <td class="gridRight"><span id="galleryTitleSpan"></span></td>
  </tr>
  <tr>
    <td class="gridLeft"><label><?=label("PICTURE FORMAT")?></label></td>
    <td class="gridRight"><span id="pictureFormatSpan"></span></td>
  </tr>
  <tr>
    <td class="gridLeft"><label><?=label("PICTURE SIZE")?></label></td>
    <td class="gridRight"><span id="pictureSizeSpan"></span> / <span id="pictureThumbSizeSpan"></span></td>
  </tr>
</table>
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
