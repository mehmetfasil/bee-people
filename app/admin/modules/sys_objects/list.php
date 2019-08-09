<?php
defined("PASS") or die("Dosya yok!");

//Genel Dosya
include_once(dirname(__FILE__)."/lookups.php");
?>
<script type="text/javascript">
//<![CDATA[
var tree;
var f = new formAction();
f.url = url +'&type=user&act=';
f.title = '<?=label("USER ENTRY")?>';
f.width = 650;
f.onShow = function (){
 getEl('name').readOnly=false;
 getEl('email').readOnly=false;
 getEl('lock').disabled=true;
 this.tab(1);
 this.removeErrorSpan('name');
 this.removeErrorSpan('email');
 return true;
};
f.onSave = function (){
 var tabs = getEl('tabs1').getElementsByTagName('a');
 if ((getValue('name').length < 1) || f.errorSpan['name']){
  var msg = '<?=label("USER NAME EXISTS")?> ('+ getValue('name') +')';
  if (getValue('name').length < 1){
   msg = '<?=label("USER NAME EMPTY")?>';
  }else if (!getValue('name').match(/^[a-zA-Z0-9_]+$/)){
   msg = '<?=label("USER NAME CONTAINS INVALID CHARACTERS")?>';
  }
  messageDialog(msg, {type:'OK', icon:'caution.gif', functionOK:function(){getEl('name').select()}});
  return false;
 }else if (getValue('fullname').length < 1){
  messageDialog('<?=label("USER FULL NAME MUST BE FILLED")?>', {type:'OK', icon:'caution.gif', functionOK:function(){getEl('fullname').focus()}});
  return false;
 }else if ((getValue('email').length < 1) || f.errorSpan['email']){
  var msg = '<?=label("USER EMAIL EXISTS")?> ('+ getValue('email') +')';
  if (getValue('email').length < 1){
   msg = '<?=label("USER EMAIL EMPTY")?>';
  }else if (!getValue('email').match(/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)){
   msg = '<?=label("USER EMAIL IS INVALID")?> ('+ getValue('email') +')';
  }
  messageDialog(msg, {type:'OK', icon:'caution.gif', functionOK:function(){getEl('email').select()}});
  return false;
 }else if (getValue('question',0) < 1){
  messageDialog('<?=label("USER QUESTION MUST BE SELECTED")?>', {type:'OK', icon:'caution.gif', functionOK:function(){f.tab(1,tabs[0]); getEl('question').focus()}});
  return false;
 }else if (getValue('answer').length < 1){
  messageDialog('<?=label("USER ANSWER MUST BE FILLED")?>', {type:'OK', icon:'caution.gif', functionOK:function(){f.tab(1,tabs[0]); getEl('answer').focus()}});
  return false;
 }else if (getValue('pass') != getValue('pass2')){
  messageDialog('<?=label("PASSWORD MUST BE SAME AT TWO FIELDS")?>', {type:'OK', icon:'caution.gif', functionOK:function(){f.tab(1,tabs[0]); getEl('pass').select()}});
  return false;
 }
 //groups
 var arr = [];
 if (this.groups){
  for (var i=0; i < this.groups.length; i++){
   arr.push(this.groups[i].name);
  }
 }
 setParam('groups',arr.join(','));
 return true;
};
f.onInit = function (){
 this.groups = [{
  name:'USERS',
  fullname:'<?=label("USERS GROUP")?>'
 }];
 this.setGroups();
};
f.showDialog = function (){
 var target = getEl('groupList')
 this.groupsDialog = 'dialogGroups';
 var buttons = {
  type:'SAVECANCEL',
  labelSAVE:LANGUAGE['add'],
  functionSAVE:function(){
   f.groups = [];

   var arr = [];
   var els = target.getElementsByTagName('input');
   for (var i=0; i < els.length; i++){
    if ((els[i].type == 'checkbox') && els[i].checked){
     arr.push(els[i].value);
    }
   }

   if (arr.length > 0){
    for (var i in arr){
     f.groups.push(
     {
      name:f.groupList[arr[i]].name,
      fullname:f.groupList[arr[i]].fullname
     });
    }
    f.setGroups();
    layer('hide',f.groupsDialog);
   }else{
    messageDialog('<?=label("SELECT AT LEAST ONE")?>', {type:'OK', icon:'caution.gif'});
   }
  },
  functionCANCEL:function(){
   layer('hide',f.groupsDialog);
  }
 };
 layer('show', this.groupsDialog, 400, 300, '<?=label("PICK GROUPS")?>', buttons);

 if (!this.groupList){
  var AJAX = new ajaxObject('get', this.url+'groups', null, {type:'DIALOG',message:LANGUAGE['loading'],target:f.groupsDialog});
  AJAX.run();
  AJAX.onLoad = function (){
   if (AJAX.xml){
    var xml = AJAX.xml.getElementsByTagName('list');
    var result = '';
    f.groupList = {};

    if (xml.length > 0){
     result += '<table width="100%" border="0" cellpadding="5" cellspacing="0">'
     + '<tr>'
     + '<td class="gridTitle" style="width:5%">&nbsp;<\/td>'
     + '<td class="gridTitle"><?=label("GROUP NAME")?><\/td>'
     + '<td class="gridTitle"><?=label("GROUP FULLNAME")?><\/td>'
     + '<\/tr>';

     for (var i=0; i < xml.length; i++){
      f.groupList[xml.item(i).getAttribute('name')] = {
       name:xml.item(i).getAttribute('name'),
       fullname:xml.item(i).firstChild.data
      };

      result += '<tr>'
      + '<td class="gridRow" style="text-align:center"><input type="checkbox" id="g'+ xml.item(i).getAttribute('id') +'" name="g'+ xml.item(i).getAttribute('id') +'" value="'+ xml.item(i).getAttribute('name') +'" '+ (f.inArray(xml.item(i).getAttribute('name'),f.groups) ? 'checked="checked" ' : '') +'/><\/td>'
      + '<td class="gridRow"><label for="g'+ xml.item(i).getAttribute('id') +'">'+ xml.item(i).getAttribute('name') +'<\/label><\/td>'
      + '<td class="gridRow">'+ xml.item(i).firstChild.data +'<\/td>'
      + '<\/tr>';
     }
     result += '<\/table>';
    }else{
     result += '<div class="warning">'+ LANGUAGE['noResult'] +'<\/div>';
    }
    target.innerHTML = result;
   }else{
    messageDialog(LANGUAGE['unknownError'], {type:'OK',icon:'warning.gif'});
   }
  }
 }else{
  var els = target.getElementsByTagName('input');
  for (var i=0; i < els.length; i++){
   if ((els[i].type=='checkbox') && !els[i].disabled){
    els[i].checked = this.inArray(els[i].value, this.groups);
   }
  }
 }
};
f.inArray = function (needle, haystack){
 if (haystack){
  for (var i=0; i < haystack.length; i++){
   if (haystack[i].name == needle){
    return true;
   }
  }
 }
 return false;
};
f.groups = [];
f.setGroups = function (){
 var target = getEl('groupsDiv');
 if (target){
  if (target.hasChildNodes()){
   while (target.childNodes.length >= 1){
    target.removeChild(target.firstChild);
   }
  }

  if (this.groups.length > 0){
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
   td.width = '5%';
   td.innerHTML = '&nbsp;';
   var td = Layer.createElement('td',tr);
   td.className = 'gridTitle';
   td.innerHTML = '<?=label("GROUP NAME")?>';
   var td = Layer.createElement('td',tr);
   td.className = 'gridTitle';
   td.innerHTML = '<?=label("GROUP FULLNAME")?>';

   for (var i=0; i < this.groups.length; i++){
    var tr = Layer.createElement('tr',tbody);
    var td1 = Layer.createElement('td',tr);
    td1.className = 'gridRow';
    td1.onmouseover = new Function('overRow(this,\'gridRowOver\')');
    td1.onmouseout = new Function('overRow(this,\'gridRow\')');
    td1.style.textAlign = 'center';
    td1.style.width = '5%';
    var td2 = Layer.createElement('td',tr);
    td2.className = 'gridRow';
    td2.onmouseover = new Function('overRow(this,\'gridRowOver\')');
    td2.onmouseout = new Function('overRow(this,\'gridRow\')');
    var td3 = Layer.createElement('td',tr);
    td3.className = 'gridRow';
    td3.onmouseover = new Function('overRow(this,\'gridRowOver\')');
    td3.onmouseout = new Function('overRow(this,\'gridRow\')');

    var box = document.createElement('input');
    box.setAttribute('type','checkbox');
    box.setAttribute('id','chk'+i);
    box.setAttribute('name','chk'+i);
    box.setAttribute('value', i);
    td1.appendChild(box);

    td2.innerHTML = '<label for="chk'+i+'"><b>'+ this.groups[i].name +'<\/b><\/label>';
    td3.innerHTML = this.groups[i].fullname;
   }
  }else{
   //DIV
   var div = Layer.createElement('div',target);
   div.className = 'warning';
   div.innerHTML = '<?=label("NO GROUP SELECTED")?>';
  }
 }
};
f.removeGroups = function (){
 var chked = [];
 var source = getEl('groupsDiv');
 var els = source.getElementsByTagName('input');
 for (var i=0; i < els.length; i++){
  if ((els[i].type == 'checkbox') && els[i].checked){
   chked.push(els[i].value);
  }
 }

 if (chked.length > 0){
  var tmp = [];
  for (var y=0; y < chked.length; y++){
   if (this.groups[chked[y]]){
    this.groups[chked[y]] = null;
   }
  }
  for (var i=0; i < this.groups.length; i++){
   if (this.groups[i]){
    tmp.push(this.groups[i]);
   }
  }
  this.groups = tmp;
  this.setGroups();
 }else{
  messageDialog('<?=label("SELECT AT LEAST ONE")?>', {type:'OK', icon:'caution.gif'});
 }
};
f.check = function (field){
 var pattern = (field == 'name' ? /^[a-zA-Z0-9_]+$/ : /^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/);
 var target = getEl(field);
 if (!target.readOnly){
  this.removeErrorSpan(field);
  if (getValue(field).length > 0){
   if (getValue(field).match(pattern)){
    var AJAX = new ajaxObject('post', this.url+'check&field='+field, 'name='+encodeURIComponent(getValue(field)));
    AJAX.run();
    AJAX.onLoad = function (){
     if (AJAX.xml){
      var xml = AJAX.xml.getElementsByTagName('result');
      if (xml.length > 0){
       if (xml.item(0).getAttribute('status') != 'OK'){
        f.showErrorSpan(field,'<?=label("EXISTS")?>');
       }
      }
     }
    }
   }else{
    this.showErrorSpan(field,'<?=label("INVALID")?>');
   }
  }else{
   this.showErrorSpan(field,'<?=label("EMPTY")?>');
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
  <?=formElement("select", "listOrder", $user_orders, "", "", "onchange=\"f.list(f.filters())\"")?>
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
    <li><a href="javascript:void(0)" onclick="f.tab(1,this)"><?=label("MEMBERED GROUPS")?></a></li>
  </ul>
</div>
<div id="tabbing1" class="tabbing" style="height:400px;">
  <div class="tab">
    <fieldset>
    <table width="100%" border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td class="gridLeft" style="width:25%"><label for="name"><?=label("USER NAME")?></label></td>
        <td class="gridRight">
          <?=formElement("hidden", "id", "", 0, "", "")?>
          <?=formElement("text", "name", "", "", "", "style=\"width:300px;\" maxlength=\"50\" onblur=\"f.check('name')\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="fullname"><?=label("USER FULLNAME")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "fullname", "", "", "", "style=\"width:300px;\" maxlength=\"100\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="email"><?=label("USER EMAIL")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "email", "", "", "", "style=\"width:300px;\" maxlength=\"100\" onblur=\"f.check('email')\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="question"><?=label("USER QUESTION")?></label></td>
        <td class="gridRight">
          <?=formElement("select", "question", (array("&nbsp;")+$questions), "", "", "style=\"width:300px\"")?>
        </td>
      </tr>
      <tr>
        <td class="gridLeft"><label for="answer"><?=label("USER ANSWER")?></label></td>
        <td class="gridRight">
          <?=formElement("text", "answer", "", "", "", "style=\"width:300px;\" maxlength=\"100\"")?>
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
                <td class="gridRight">
                  <div class="question"><label for="pass"><?=label("USER PASSWORD")?></label></div>
                  <div class="answer">
                    <?=formElement("password", "pass", "", "", "", "style=\"width:200px\"")?>
                  </div>
                  <div class="answer">
                    <?=formElement("password", "pass2", "", "", "", "style=\"width:200px\"")?>
                    (<?=label("AGAIN");?>)
                  </div>
                  <div class="answer">
                    <?=formElement("checkbox", "cpnl", "1", "0", "", "")?>
                    <label for="cpnl"><?=label("CHANGE PASSWORD NEXT LOGON")?></label>          
                  </div>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
        <td valign="top" style="width:50%">
          <fieldset>
            <table width="100%" border="0" cellpadding="5" cellspacing="0">
               <tr>
                 <td class="gridRight" colspan="2">
                   <div class="question"><label for="detail"><?=label("USER DETAIL")?></label></div>
                   <div class="answer">
                     <?=formElement("textarea", "detail", "", "", "", "style=\"width:290px; height:60px\"")?>
                   </div>
                 </td>
               </tr>
            </table>
          </fieldset>
        </td>
      </tr>
    </table>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top" style="width:50%">
          <fieldset>
            <table width="100%" border="0" cellpadding="5" cellspacing="0">
              <tr>
                <td class="gridLeft" style="width:35%"><label for="level"><?=label("USER LEVEL")?></label></td>
                <td class="gridRight">
                  <?=formElement("select", "level", $levels, "", "", "style=\"width:180px\"")?>
                </td>
              </tr>
              <tr>
                <td class="gridLeft" valign="top"><label for="noexpire"><?=label("PASSWORD EXPIRATION")?></label></td>
                <td class="gridRight">
       			    		  <?=formElement("checkbox", "noexpire", "1", "1", "", "");?> <label for="noexpire"><?=label("UNLIMITED");?></label>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
        <td valign="top" style="width:50%">
          <fieldset>
            <table width="100%" border="0" cellpadding="5" cellspacing="0">
              <tr>
                <td class="gridLeft" style="width:35%"><label for="lock"><?=label("USER LOCK")?></label></td>
                <td class="gridRight">
                  <?=formElement("checkbox", "lock", "1", "0", "disabled", "")?>
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
        </td>
      </tr>
    </table>
  </div>
  <div class="tab" style="display:none">
    <div style="padding:5px 0; text-align:right">
      <?=formElement("button", "addBtn", "", "+", "", "onclick=\"f.showDialog('groups')\" class=\"button\"")?>
      <?=formElement("button", "removeBtn", "", "-", "", "onclick=\"f.removeGroups()\" class=\"button\"")?>
      <?=formElement("hidden", "groups", "", "", "", "")?>
    </div>
    <div id="groupsDiv"></div>
  </div>
</div>
</form>
</div>

<div id="dialogGroups" style="display:none">
<div id="groupList" style="height:300px; overflow:auto"></div>
</div>