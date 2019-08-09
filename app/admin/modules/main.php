<?php
defined("PASS") or die("Dosya yok!");



include_once(CONF_DOCUMENT_ROOT."system".DS."functions".DS."calendar.php");
?>
<script type="text/javascript">
<!--
var u = '<?=CONF_MAIN_PAGE."?".ADMIN_EXTENSION."&pid=".menuID("ADMIN_MESSAGES")?>';

var f1 = new formAction();
f1.url = u +'&act=notes&step=';
f1.targetList = 'list1';
f1.targetDialog = 'dialog1';

var f2 = new formAction();
f2.url = u +'&act=errors&step=';
f2.targetList = 'list2';
f2.paging = false;
f2.contents = {
	'dialog'  : true,
	'editing' : true,
	'deleting': false
};

var f3 = new formAction();
f3.url = u +'&act=onlines&step=';
f3.targetList = 'list3';
f3.paging = false;
f3.contents = {
	'dialog'  : false,
	'editing' : false,
	'deleting': false
};
f3.onList = function (){
 var xml = arguments[1];
 if (xml){
  var raw = xml.getElementsByTagName('row');
  var el = document.createElement('div');
  el.style.padding = '5px';
  el.style.border = '1px solid #dddddd';
  el.innerHTML = '<img src="objects/icons/16x16/group.png" width="16" height="16" alt="" /> <b>'+ (raw.length / 2) +'<\/b> <?=label("GUEST")?>';
  
  var target = getEl(this.targetList);
  target.insertBefore(el, target.childNodes[0]);
 }
 window.setTimeout(function(){f3.list()}, 45000);
}

var f4 = new formAction();
f4.url = u +'&act=lastaccess&step=';
f4.targetList = 'list4';
f4.paging = false;
f4.contents = {
	'dialog'  : false,
	'editing' : false,
	'deleting': false
};

addListener(window, 'load', function(){
 f1.list();
 f2.list();
 f3.list();
 f4.list();
});
//-->
</script>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td valign="top">
      <fieldset><legend><?=label("NOTES")?></legend>
        <div class="link"><a class="new" href="javascript:void(0)" onclick="f1.show()"><?=label("CREATE NEW")?></a></div>
        <div class="list" id="list1" style="height:200px"><div class="warning"><?=label("LOADING")?></div></div>
      </fieldset>
      <fieldset><legend><?=label("SYSTEM ERROR MESSAGES")?></legend>
        <div class="list" id="list2" style="height:150px"><div class="warning"><?=label("LOADING")?></div></div>
      </fieldset>
      <fieldset><legend><?=label("LAST VISITS")?></legend>
        <div class="list" id="list4" style="height:200px"><div class="warning"><?=label("LOADING")?></div></div>
      </fieldset>
    </td>
    <td valign="top" style="width:410px">
      <fieldset><legend><?=label("VISITOR GRAPHIC")?></legend>
        <script type="text/javascript">
        <!--
        var f = '<iframe name="graph1" width="100%" height="270" src="<?=CONF_MAIN_PAGE."?".ADMIN_EXTENSION."&pid=".menuID("ADMIN_GRAPHIC")?>" frameborder="0"><\/iframe>';
        document.write(f);
        //-->
        </script>
      </fieldset>
      <fieldset><legend><?=label("CALENDAR")?></legend>
        <?=calendar();?>
      </fieldset>
      <fieldset><legend><?=label("ONLINE USERS")?></legend>
        <div class="list" id="list3" style="height:200px"><div class="warning"><?=label("LOADING")?></div></div>
      </fieldset>
    </td>
  </tr>
</table>

<div id="dialog1" style="display:none">
<form id="dialog1form" method="post" onsubmit="return false" action="#">
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td>
      <div class="question"><label for="title"><?=label("NOTE TITLE")?></label></div>
      <div class="answer">
        <?=formElement("text", "title", "", "", "", "style=\"width:485px\" maxlength=\"100\"")?>
      </div>
    </td>
  </tr>
  <tr>
    <td>
      <div class="question"><label for="description"><?=label("NOTE DESCRIPTION")?></label></div>
      <div class="answer">
        <?=formElement("textarea", "description", "", "", "", "style=\"width:485px; height:80px\"")?>
      </div>
    </td>
  </tr>
</table>
</form>
</div>