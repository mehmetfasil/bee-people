<?php
defined("PASS") or die("Dosya yok!");
?>
<script type="text/javascript" src="objects/js/swfupload.js"></script>
<script type="text/javascript">
//<![CDATA[
var swfu;
var f = new formAction();
f.url = url +'&act=';
f.title = '<?=label("PRODUCT BRAND")?>';
f.defaultLogo = 'templates/sepett/images/default_brand_logo.png';
f.onShow = function (){
 getEl('logoImg').src = this.defaultLogo;
 getEl('uploadProgress').innerHTML = '';
 getEl('uploadResult').innerHTML = '';
 this.tab(1);
 return true;
};
f.onSave = function (){
 if (getValue('isim').length < 1){
  messageDialog('<?=label("BRAND NAME CANNOT BE BLANK")?>', {type:'OK', icon:'caution.gif', functionOK:function(){getEl('isim').focus()}});
  return false;
 }
 return true;
};
f.remove = function (){
 var source = getEl('logoImg');
 
 if (this.defaultLogo != getValue('logo')){
  var AJAX = new ajaxObject('post', this.url +'remove', 'id='+getValue('id')+'&logo='+getValue('logo'));
  AJAX.run();
  AJAX.onLoad = function (){
   if (AJAX.xml){
    var xml = AJAX.xml.getElementsByTagName('result');
    
    if (xml.length > 0){
     if (xml.item(0).getAttribute('status') == 'OK'){
      var pic = f.defaultLogo;
      
      source.onload = function (){
       fadeIn(source,0);
       setParam('logo', pic);
      }
      source.src = pic;
     }else{
      messageDialog(xml.item(0).firstChild.data, {type:'OK', icon:'caution.gif'});
     }
    }else{
     messageDialog(LANGUAGE['noReturn'], {type:'OK', icon:'warning.gif'});
    }
   }else{
    messageDialog(LANGUAGE['unknownError'], {type:'OK', icon:'warning.gif'});
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

function setPicture (){
 var source = getEl('logoImg');
 
 if (this.getStats().files_queued === 0){
  var AJAX = new ajaxObject('get', url +'&act=get', null);
  AJAX.run();
  AJAX.onLoad = function (){
   if (AJAX.xml){
    var xml = AJAX.xml.getElementsByTagName('picture');
    
    if (xml.length > 0){
     var pic = xml.item(0).firstChild.data;
     
     source.onload = function (){
      fadeIn(source,0);
      setParam('logo', pic);
     }
     source.src = pic;
    }
   }
  }
 }
}

addListener(window, 'load', function() { 
 f.list(f.filters());
 
 var settings = {
  flash_url : 'objects/assets/_system/swfupload.swf',
  upload_url: '<?=$_SERVER["PHP_SELF"]."?".ADMIN_EXTENSION."&pid=".PID."&sid=upload"?>',	// Relative to the SWF file
  post_params: {
   'PHPSESSID' : '<?=session_id();?>'
  },
  file_size_limit : '2 MB',
  file_types : '*.png',
  file_types_description : '<?=label("PNG PICTURES")?>',
  file_upload_limit : 0,
  file_queue_limit : 1,
  custom_settings : {
   progressTarget : 'uploadProgress'
  },
  debug: false,
 
  // Button settings
  button_image_url: '<?=ADMIN_FOLDER?>/templates/_default/images/upload_button.png',	// Relative to the Flash file
  button_width: '150',
  button_height: '27',
  button_placeholder_id: 'uploadSpan',
  button_text: '<span class="text">'+ LANGUAGE['pick_and_upload'] +'<\/span>',
  button_text_style: '.text { font-family:Arial; font-size:14px; text-align:center; font-weight:bold; color:#333333 }',
  button_text_left_padding: 0,
  button_text_top_padding: 3,
 
  // The event handler functions are defined in handlers.js
  file_queued_handler : fileQueued,
  file_queue_error_handler : fileQueueError,
  file_dialog_complete_handler : fileDialogComplete,
  upload_start_handler : uploadStart,
  upload_progress_handler : uploadProgress,
  upload_error_handler : uploadError,
  upload_success_handler : uploadSuccess,
  upload_complete_handler : setPicture,
  queue_complete_handler : queueComplete	// Queue plugin event
 };
 
 swfu = new SWFUpload(settings);
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
<div class="tabs" id="tabs1">
  <ul>
    <li class="here"><a href="javascript:void(0)" onclick="f.tab(1,this)"><?=label("PROPERTIES")?></a></li>
    <li><a href="javascript:void(0)" onclick="f.tab(1,this)"><?=label("LOGO")?></a></li>
  </ul>
</div>
<div class="tabbing" id="tabbing1" style="height:200px">
  <div class="tab">
    <form id="dialogform" method="post" action="#">
    <table width="100%" border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td class="gridLeft" style="width:25%"><label for="isim"><?=label("BRAND NAME")?></label></td>
        <td class="gridRight">
          <?=formElement("hidden", "id", "", 0, "", "")?>
          <?=formElement("hidden", "logo", "", "templates/sepett/images/default_brand_logo.png", "", "")?>
          <?=formElement("text", "isim", "", "", "", "style=\"width:300px\" maxlength=\"50\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft" valign="top"><label for="aciklama"><?=label("BRAND DESCRIPTION")?></label></td>
        <td class="gridRight">
          <?=formElement("textarea", "aciklama", "", "", "", "style=\"width:300px\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="url"><?=label("BRAND WEBSITE")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "url", "", "", "", "style=\"width:300px\" maxlength=\"100\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="aktif_1"><?=label("BRAND STATUS")?></label></td>
        <td class="gridRight">
          <?=formElement("radio", "aktif", 1, 1, "", "", "aktif_1")?> <label for="aktif_1"><?=label("OPEN")?></label>
          <?=formElement("radio", "aktif", 0, 1, "", "", "aktif_0")?> <label for="aktif_0"><?=label("CLOSE")?></label>
        </td>
      </tr>
    </table>
    </form>
  </div>
  <div class="tab" style="display:none">
    <table width="100%" border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td style="width:30%; text-align:center">
          <img id="logoImg" src="templates/sepett/images/default_brand_logo.png" width="100" height="100" alt="" />
        </td>
        <td>
         	<form id="uploadform" method="post" enctype="multipart/form-data" action="<?=CONF_MAIN_PAGE?>">
            <div id="uploadProgress" style="height:130px; overflow:auto"></div>
         		 <div id="uploadResult" style="margin:5px 0; height:20px"></div>
         	</form>
          <div style="text-align:right">
          	<span id="uploadSpan"></span>
          	<?=formButton("button", "removeBtn", "Varolanı Sil", "", "graphic.png", "onclick=\"f.remove()\" style=\"height:27px\"")?>
          </div>
        </td>
      </tr>
    </table>
  </div>
</div>
</div>