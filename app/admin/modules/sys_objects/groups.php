<?php
defined("PASS") or die("Dosya yok!");

//Genel Dosya
include_once(dirname(__FILE__).DS."lookups.php");
?>
<script type="text/javascript">
//<![CDATA[
var tree;
var f = new formAction();
f.url = url +'&type=group&act=';
f.title = '<?=label("GROUP ENTRY")?>';
f.width = 650;
f.y = <?=Y?>;
f.onShow = function (){
 getEl('name').readOnly=false;
 this.tab(1);
 this.removeErrorSpan('name');
 this.users = null;
 this.members = null;
 return true;
};
f.onSave = function (){
 var tabs = getEl('tabs1').getElementsByTagName('a');
 if ((getValue('name').length < 1) || f.errorSpan['name']){
  var msg = '<?=label("GROUP NAME EXISTS")?> ('+ getValue('name') +')';
  if (getValue('name').length < 1){
   msg = '<?=label("GROUP NAME EMPTY")?>';
  }else if (!getValue('name').match(/^[a-zA-Z0-9_]+$/)){
   msg = '<?=label("GROUP NAME CONTAINS INVALID CHARACTERS")?>';
  }
  messageDialog(msg, {type:'OK', icon:'caution.gif', functionOK:function(){getEl('name').select()}});
  return false;
 }else if (getValue('fullname').length < 1){
  messageDialog('<?=label("GROUP FULL NAME MUST BE FILLED")?>', {type:'OK', icon:'caution.gif', functionOK:function(){f.tab(1,tabs[0]); getEl('fullname').focus()}});
  return false;
 }
 //Members
 var arr = [];
 if (this.members){
  for (var i=0; i < this.members.length; i++){
   arr.push(this.members[i].id);
  }
 }
 setParam('members',arr.join(','));
 return true;
};
f.onInit = function (){
 this.getUsers(0);
};
f.getUsers = function (gid){
 if (!this.users){
  this.users = [];
  var AJAX = new ajaxObject('get',this.url+'users&gid='+gid,null,{type:'LIST',message:LANGUAGE['loading'],target:'usersDiv'});
  AJAX.run();
  AJAX.onLoad = function (){
   if (AJAX.xml){
    var xml = AJAX.xml.getElementsByTagName('list');
    
    for (var i=0; i < xml.length; i++){
     f.users.push({
      id:xml.item(i).getAttribute('id'),
      level:xml.item(i).getAttribute('level'),
      name:xml.item(i).getAttribute('name'),
      fullname:xml.item(i).firstChild.data
     });
    }
    
    f.setUserList(gid);
   }
  }
 }else{
  this.setUserList(gid);
 }
};
f.setUserList = function (gid){
 var target = getEl('usersDiv');
 var spanTarget = getEl('usersSpan');
 var result = '';
 var list = [];
 
 for (var i=0; i < this.users.length; i++){
  if (this.users[i]){
   if (getValue('filter1').length > 0){
    if ((this.users[i].name.indexOf(getValue('filter1')) < 0)
     && (this.users[i].fullname.indexOf(getValue('filter1')) < 0)){
     continue;
    }
   }
   list.push(this.users[i]);
  }
  if (list.length >= this.y){
   break;
  }
 }
 
 if (list.length > 0){
  //Sonuç
  result += '<table width="100%" border="0" cellpadding="5" cellspacing="0">'
  for (var i=0; i < list.length; i++){
   result += '<tr>'
   + '<td class="gridRow" style="width:5%"><input type="checkbox" id="u'+ list[i].id +'" name="u'+ list[i].id +'" value="'+ list[i].id +'" /><\/td>'
   + '<td class="gridRow"><label for="u'+ list[i].id +'">('+ list[i].name +') '+ list[i].fullname +'<\/label><\/td>'
   + '<td class="gridRow" style="text-align:center; width:15%">'
   + '<img src="objects/icons/16x16/user_'+ list[i].level +'.png" width="16" height="16" alt="" />'
   + '<\/td>'
   + '<\/tr>';
  }
  result += '<\/table>';
 }else{
  result += '<div class="warning">'+ LANGUAGE['noResult'] +'<\/div>';
 }
 
 target.innerHTML = result;
 spanTarget.innerHTML = this.users.length;
 //Members
 this.getMembers(gid);
};
f.getMembers = function (gid){
 if (!this.members){
  this.members = [];
  var AJAX = new ajaxObject('get',this.url+'members&gid='+gid,null,{type:'LIST',message:LANGUAGE['loading'],target:'membersDiv'});
  AJAX.run();
  AJAX.onLoad = function (){
   if (AJAX.xml){
    var xml = AJAX.xml.getElementsByTagName('list');
    
    for (var i=0; i < xml.length; i++){
     f.members.push({
      id:xml.item(i).getAttribute('id'),
      level:xml.item(i).getAttribute('level'),
      name:xml.item(i).getAttribute('name'),
      fullname:xml.item(i).firstChild.data
     });
    }
    
    f.setMemberList();
   }
  }
 }else{
  f.setMemberList();
 }
};
f.setMemberList = function (){
 var target = getEl('membersDiv');
 var spanTarget = getEl('membersSpan');
 var result = '';
 var list = [];
 
 for (var i=0; i < this.members.length; i++){
  if (this.members[i]){
   if (getValue('filter2').length > 0){
    if ((this.members[i].name.indexOf(getValue('filter2')) < 0)
     && (this.members[i].fullname.indexOf(getValue('filter2')) < 0)){
     continue;
    }
   }
   list.push(this.members[i]);
  }
  if (list.length >= this.y){
   break;
  }
 }
 
 if (list.length > 0){
  //Sonuç
  result += '<table width="100%" border="0" cellpadding="5" cellspacing="0">'
  for (var i=0; i < list.length; i++){
   result += '<tr>'
   + '<td class="gridRow" style="width:5%"><input type="checkbox" id="m'+ list[i].id +'" name="m'+ list[i].id +'" value="'+ list[i].id +'" /><\/td>'
   + '<td class="gridRow"><label for="m'+ list[i].id +'">('+ list[i].name +') '+ list[i].fullname +'<\/label><\/td>'
   + '<td class="gridRow" style="text-align:center; width:15%">'
   + '<img src="objects/icons/16x16/user_'+ list[i].level +'.png" width="16" height="16" alt="" />'
   + '<\/td>'
   + '<\/tr>';
  }
  result += '<\/table>';
 }else{
  result += '<div class="warning">'+ LANGUAGE['noResult'] +'<\/div>';
 }
 
 target.innerHTML = result;
 spanTarget.innerHTML = this.members.length;
};
f.addMember = function (){
 var arr = [];
 var els = getEl('usersDiv').getElementsByTagName('input');
 for (var i=0; i < els.length; i++){
  if ((els[i].type=='checkbox') && !els[i].disabled && els[i].checked){
   arr.push(els[i].value);
  }
 }
 if (arr.length > 0){
  for (var i=0; i < f.users.length; i++){
   for (var y=0; y < arr.length; y++){
    if (this.users[i] && (this.users[i].id == arr[y])){
     this.members.unshift({
      id:this.users[i].id,
      level:this.users[i].level,
      name:this.users[i].name,
      fullname:this.users[i].fullname
     });
     delete this.users[i];
     break;
    }
   }
  }
  this.getUsers(0);
 }else{
  messageDialog('<?=label("SELECT AT LEAST ONE")?>', {type:'OK',icon:'caution.gif'});
 }
};
f.removeMember = function (){
 var arr = [];
 var els = getEl('membersDiv').getElementsByTagName('input');
 for (var i=0; i < els.length; i++){
  if ((els[i].type == 'checkbox') && els[i].checked){
   arr.push(els[i].value);
  }
 }

 if (arr.length > 0){
  for (var i=0; i < f.members.length; i++){
   for (var y=0; y < arr.length; y++){
    if (this.members[i] && (this.members[i].id == arr[y])){
     this.users.unshift({
      id:this.members[i].id,
      level:this.members[i].level,
      name:this.members[i].name,
      fullname:this.members[i].fullname
     });
     delete this.members[i];
     break;
    }
   }
  }
  this.getUsers(0);
 }else{
  messageDialog('<?=label("SELECT AT LEAST ONE")?>', {type:'OK', icon:'caution.gif'});
 }
};
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

<div class="filterBar">
  <label for="listOrder"><?=label("ORDERING")?>:</label>
  <?=formElement("select", "listOrder", $group_orders, "", "", "onchange=\"f.list(f.filters())\"")?>
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
<form id="dialogform" method="post" onsubmit="return false" action="#">
<div id="tabs1" class="tabs">
  <ul>
    <li class="here"><a href="javascript:void(0)" onclick="f.tab(1,this)"><?=label("PROPERTIES")?></a></li>
    <li><a href="javascript:void(0)" onclick="f.tab(1,this)"><?=label("GROUP MEMBERS")?></a></li>
  </ul>
</div>
<div id="tabbing1" class="tabbing" style="height:290px;">
  <div class="tab">
    <fieldset>
    <table width="100%" border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td class="gridLeft" style="width:25%"><label for="name"><?=label("GROUP NAME")?></label></td>
        <td class="gridRight">
          <?=formElement("hidden", "id", "", 0, "", "")?>
          <?=formElement("text", "name", "", "", "", "style=\"width:300px;\" maxlength=\"50\" onblur=\"f.checkName()\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="fullname"><?=label("GROUP FULLNAME")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "fullname", "", "", "", "style=\"width:300px;\" maxlength=\"100\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft" valign="top"><label for="detail"><?=label("GROUP DETAIL")?></label></td>
        <td class="gridRight">
          <?=formElement("textarea", "detail", "", "", "", "style=\"width:300px; height:100px\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="active_1"><?=label("OBJECT STATUS")?></label></td>
        <td class="gridRight">
          <?=formElement("radio", "active", "1", "1", "", "", "active_1")?> <label for="active_1"><?=label("OPEN")?></label>
          <?=formElement("radio", "active", "0", "1", "", "", "active_0")?> <label for="active_0"><?=label("CLOSE")?></label>
        </td>
      </tr>
    </table>
    </fieldset>
  </div>
  <div class="tab" style="display:none">
    <?=formElement("hidden", "members", "", "", "", "")?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td style="width:48%">
          <div style="padding:5px">
            <label><?=label("FILTER")?>:</label>
            <?=formElement("text", "filter1", "", "", "", "style=\"width:200px\" onkeypress=\"if((event.keyCode==13)||(event.charCode==13)){f.getUsers(0)}\"")?>
          </div>
          <fieldset>
            <div id="usersDiv" class="list" style="height:210px"></div>
          </fieldset>
          <div style="padding:5px"><label><?=label("TOTAL")?>:</label> <span id="usersSpan" style="font-weight:bold">0</span></div>
        </td>
        <td style="width:4%; text-align:center">
          <?=formElement("button", "addBtn", "", "&gt;&gt;", "", "class=\"button\" style=\"width:30px;\" onclick=\"f.addMember()\"")?>
          <br/>
          <br/>
          <br/>
          <?=formElement("button", "removeBtn", "", "&lt;&lt;", "", "class=\"button\" style=\"width:30px;\" onclick=\"f.removeMember()\"")?>
        </td>
        <td style="width:48%">
          <div style="padding:5px">
            <label><?=label("FILTER")?>:</label>
            <?=formElement("text", "filter2", "", "", "", "style=\"width:200px\" onkeypress=\"if((event.keyCode==13)||(event.charCode==13)){f.getMembers(0)}\"")?>
          </div>
          <fieldset>
            <div id="membersDiv" class="list" style="height:210px"></div>
          </fieldset>
          <div style="padding:5px"><label><?=label("TOTAL")?>:</label> <span id="membersSpan" style="font-weight:bold">0</span></div>
        </td>
      </tr>
    </table>
  </div>
</div>
</form>
</div>
