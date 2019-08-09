<?php
defined("PASS") or die("Dosya yok!");
?>
<script type="text/javascript" src="objects/js/jquery/plugin/common.js"></script>
<script type="text/javascript" src="objects/js/jquery/plugin/tree-min.js"></script>
<script type="text/javascript" src="objects/js/jquery/plugin/loading.js"></script>
<script type="text/javascript" src="objects/js/swfupload.js"></script>
<script type="text/javascript">
//<![CDATA[
var swfu;
var f = new formAction();
f.url = url+'&act=';
f.contents = {
 'dialog':false,
 'editing':false,
 'listing':true
};
f.messages.noResult = '<?=page::showText("FOLDER IS EMPTY")?>';
f.paging = false;
f.download = function (file){
 if (<?=menuID("SITE_DOWNLOAD")?> > 0){
  var u = '<?=CONF_MAIN_PAGE?>?pid=<?=menuID("SITE_DOWNLOAD")?>'+ '&file='+ file;
  window.location.href = u;
 }else{
  messageDialog('<?=page::showText("DOWNLOAD MENU NOT FOUND")?>', {type:'OK', icon:'warning.gif'});
 }
};
f.createFolder = function (){
 if (getValue('folder').length < 1){
  messageDialog('<?=page::showText("TYPE FOLDER NAME")?>', {type:'OK', icon:'caution.gif', functionOK:function(){getEl('folder').focus()}});
 }else if (!getValue('folder').match(/^[a-zA-Z0-9_-]+$/)){
  messageDialog('<?=page::showText("FOLDER NAME IS NOT VALID")?>', {type:'OK', icon:'caution.gif', functionOK:function(){getEl('folder').select()}});
 }else{
  var params = 'path='+getValue('path')+'&folder='+getValue('folder');
  var AJAX = new ajaxObject('post', this.url+'create', params, {type:'MESSAGE', message:'<?=page::showText("UPLOADING")?>'});
  AJAX.run();
  AJAX.onLoad = function (){
   if (AJAX.xml){
    var xml = AJAX.xml.getElementsByTagName('result');
    if(xml.length > 0){
     if (xml.item(0).getAttribute('status') == 'OK'){
      f.list({path:getValue('path')+'/'+getValue('folder')});
      getEl('folder').value = '';
     }else{
      messageDialog(xml.item(0).firstChild.data, {type:'OK',icon:'caution.gif'});
     }
    }else{
     messageDialog(LANGUAGE['noReturn'], {type:'OK',icon:'warning.gif'});
    }
   }else{
    messageDialog(LANGUAGE['unknownError'], {type:'OK',icon:'warning.gif'});
   }
  }
 }
};
f.onList = function (uri){
 setParam('path',uri.path);

	var result = '';
	var tmp = uri.path.split('\/');
	var tmpPath = '';
	
	for (var i in tmp){
		if (tmp[i] != ''){
			tmpPath += tmp[i] +'\/';
			result += '\/ <a href="javascript:void(0)" onclick="f.list({path:\''+ tmpPath +'\'})">'+ tmp[i] +'<\/a> ';
		}
	}

	getEl('pathDiv').innerHTML = result;
};
f.folderUp = function (){
	var path = getValue('path','/');
	if (path.lastIndexOf('\/') == (path.length - 1)){
		path = path.substring(0, ((path.length) - 1));
	}
	var tmp = path.split('\/');
	var tmpPath = '';

	for (var i in tmp){
		if (i == (tmp.length - 1)){
			break;
		}
		if (tmp[i] != ''){
			tmpPath += tmp[i] +'\/';
		}
	}
	//Listeyi getir
	f.list({path:tmpPath});
};
f.initSWF = function (){
 var settings = {
  flash_url : 'objects/assets/_system/swfupload.swf',
  upload_url: '<?=$_SERVER["PHP_SELF"]."?".ADMIN_EXTENSION."&pid=".PID."&sid=upload"?>',
  post_params: {
   'PHPSESSID' : '<?=session_id();?>'
  },
  file_size_limit : '100 MB',
  file_types : '*.*',
  file_types_description : LANGUAGE['all_files'],
  file_upload_limit : 0,
  file_queue_limit : 0,
  custom_settings : {
   progressTarget : 'uploadProgress',
   cancelButtonId : 'cancelBtn'
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
f.showDialog = function (){
 getEl('overwrite').disabled = true;
 getEl('uploadPath').innerHTML = getValue('path').length > 0 ? getValue('path') : '<?=page::showText("MAIN FOLDER")?>';
 getEl('uploadDiv').innerHTML = '<span id="uploadSpan"><\/span>';
 getEl('uploadProgress').innerHTML = '';
 getEl('uploadResult').innerHTML = '';
 layer('show', 'dialog', 500, 300, '<?=page::showText("UPLOAD FILE")?>', {onClose:function(){f.list({path:getValue('path')})}});
 this.initSWF();
}

function setPath (){
 swfu.addPostParam('path', getValue('path'));
 getEl('overwrite').disabled = false;
}

function setOverwrite (){
 if (typeof(swfu) === 'object'){
  var source = getEl('overwrite');
  swfu.addPostParam('overwrite', (source.checked ? 1 : 0));
 }
}

addListener(window,'load',function(){
 f.list({path:''});
});

$(document).ready(function(){
	$("#directoryTree").jstree({
		collapseOthers:false,
		onClick:function(obj,path){
			if(obj.attr("className")!="selected")
			f.list({path:path.join("/")});
		},
		async:{
			type:"get",
			url:url,
			data:{
				act:"getFolders",
			}
		}
	});
});
//]]>
</script>
<div style="float:left;height:300px;width:200px;">
	<fieldset>
		<legend><?=label("Dizinler")?></legend>
		<div id="directoryTree"></div>
	</fieldset>
</div>
<div>
<form id="mainform" method="post" onsubmit="return false" action="#">
<fieldset>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td class="gridLeft" style="width:20%"><label for="folder"><?=page::showText("NEW FOLDER NAME")?></label></td>
    <td class="gridRight">
      <?=formElement("text", "folder", "", "", "", "style=\"width:300px\" onkeypress=\"if((event.keyCode==13)||(event.charCode==13)){f.createFolder()}\"")?>
      <?=formElement("button", "createBtn", "", page::showText("CREATE"), "", "onclick=\"f.createFolder()\" class=\"button\"")?>
    </td>
    <td class="gridRowOver" rowspan="2" style="width:20%; text-align:center">
      <a class="upload" href="javascript:void(0)" onclick="f.showDialog()"><?=page::showText("UPLOAD FILE")?></a>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label><?=page::showText("CURRENT PATH")?></label></td>
    <td class="gridRight">
      <?=formElement("hidden", "path", "", "", "", "")?>
  		  <a href="javascript:void(0)" onclick="f.folderUp()"><img src="objects/icons/24x24/folder_up.png" width="24" height="24" alt="" /></a>
  				<div style="display:inline; line-height:24px; margin:0 5px"><a href="javascript:void(0)" onclick="f.list({path:'/'});"><?=page::showText("MAIN FOLDER")?></a></div>
      <div id="pathDiv" style="display:inline; line-height:24px;"></div>
    </td>
  </tr>
</table>
</fieldset>
</form>

<fieldset>
<div id="list"><div class="warning"><?=page::showText("LOADING")?></div></div>
</fieldset>

<div id="dialog" style="display:none">
<form id="dialogform" method="post" enctype="multipart/form-data" action="<?=CONF_MAIN_PAGE?>">
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td class="gridLeft"><label><?=page::showText("UPLOAD PATH")?></label></td>
    <td class="gridRight">
      <div id="uploadPath" style="width:300px; overflow:hidden"></div>
    </td>
  </tr>
  <tr>
    <td class="gridLeft"><label><?=page::showText("OVERWRITE")?>?</label></td>
    <td class="gridRight">
 		   <?=formElement("checkbox", "overwrite", 1, 0, "disabled", "onclick=\"setOverwrite()\"")?>
 		   <label for="overwrite"><?=page::showText("OVERWRITE EXISTED FILES")?></label>
    </td>
  </tr>
</table>
<fieldset><legend><?=page::showText("UPLOAD QUEUE")?></legend>
  <div id="uploadProgress" style="height:180px; overflow:auto"></div>
	 <div id="uploadResult" style="margin:5px 0; height:20px"></div>
</fieldset>
<div style="text-align:right">
 	<div id="uploadDiv" style="display:inline"></div>
 	<?=formButton("button", "cancelBtn", page::showText("CANCEL ALL UPLOADS"), "disabled", "editdelete.png", "onclick=\"swfu.cancelQueue()\" style=\"width:210px; height:27px\"")?>
</div>
</form>
</div>	
</div>

<?
/*
<script type="text/javascript">
 contextMenu.setup({'preventDefault':true, 'preventForms':false});
 contextMenu.attach('container', 'CM1');
</script>

<ul id="CM1" class="contextMenu">
 <li><a href="#">Item 1</a></li>
 <li><a href="#">Item 2</a></li>
 <li><a href="#">Item 3</a></li>
 <li><a href="#">Item 4</a></li>
</ul>

<div class="container" style="border: 1px dashed red; margin-top: 30px; height: 50px; background: #f2f2f2;">Cointainer1</div>
*/
?>