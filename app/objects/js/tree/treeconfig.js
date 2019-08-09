function config(name, tree, title, tooltip) {                                   //>config
 this.name     = name;         this.tree       = tree;                          //
 this.title    = title;        this.titelTTip  = tooltip;                       //
 this.lines    = true;         this.linesTxt   = 'Show Lines';                  //
                               this.linesTTip  = 'Lines indicate hierarchy';    //
 this.icons    = true;         this.iconsTxt   = 'Show Symbols';                //
                               this.iconsTTip  = 'Symbols indicate node status';//
 this.aclose   = false;        this.acloseTxt  = 'Autoclose subtrees';          //
                               this.acloseTTip = 'Autoclose left subtrees';     //
 this.cookies  = false;        this.cookiesTxt = 'Save Cookies';                //
                               this.cookiesTTip= 'Stores tree configuration';   //
 this.expireTxt= 'days';       this.expireTTip = 'Cookie expiration period';    //
 this.level    = -1;
 this.levelTxt = 'Open Level'; this.levelTTip  = 'Open tree up to this level'; //
 this.helpTxt  = 'Help';       this.helpTTip   = 'Items show tooltips';        //
 this.helpAlert= 'Tooltips are shown on mouse-over'; }                          //
                                                                                // ------------- TextOf ... ------------
config.prototype.textOfLines = function(text, tooltip) {                        //>TextOfLines (show lines)
 this.linesTxt = text;         this.linesTTip = tooltip; }                      //

config.prototype.textOfIcons = function(text, tooltip) {                        //>TextOfIcons (show symbols)
 this.iconsTxt = text;         this.iconsTTip = tooltip; }                      //

config.prototype.textOfAClose= function(text,tooltip) {                         //>TextOfAClose (autoclose)
 this.acloseTxt= text;         this.acloseTTip=tooltip;  }                      //

config.prototype.textOfCookies = function(text, tooltip) {                      //>TextOfCookies
 this.cookiesTxt = text;         this.cookiesTTip = tooltip; }                  //

config.prototype.textOfExpire = function(text, tooltip) {                       //>TextOfExpire
 this.expireTxt = text;         this.expireTTip = tooltip; }                    //

config.prototype.textOfLevel = function(text, tooltip) {                        //>TextOfLevel
 this.levelTxt = text;         this.levelTTip = tooltip; }                      //

config.prototype.textOfHelp = function(text, tooltip, alert) {                  //>TextOfHelp
 this.helpTxt = text;   this.helpTTip = tooltip;   this.helpAlert = alert; }    //
                                                                                // ---------- Build Html-Code ----------
config.prototype.toString = function() {                                        //>ToString
 var str  = '<form><div class="Config">';                                       // Encapsulate class 'Config' in a form
 var icon = (this.tree.showIcons) ? 'objects/icons/tree/config.gif' : 'objects/icons/tree/minicon.gif';       // Decide icon = config- or minIcon.gif
 str += '<a href="javascript: ' + this.name + '.toggle()">'                     // Config-icon:
     +  '<img id="' + this.name + 'ConfigGif"'                                  //   Write Html-string for the image
     +  ' src="' + icon + '" alt="" /></a>';                                    //   and a reference to this -> toggle
 str += '<a href="javascript: ' + this.name + '.toggle()"'                      // Config-text
     +  ' id="' + this.name + 'ConfigTxt" class="ConfigText"'                   //   Write Html-string for the text
     +  ' title="' + this.titelTTip + '">'                                      //   in class 'Text' with tooltip
     +  this.title + '</a>';                                                    //   and a reference to this -> toggle
 str += '<div id="' + this.name + 'SubTree"'                                    // SubTree-block (default is invisible:
     +  ' class="SubTree" style="display:none">';                               //   to show or hide the SubTree
 str += '<img src="objects/icons/tree/empty.gif" alt="" />'                                    // Show Lines:
     +  '<input type="checkbox" name="Lines" value="Lines"'                     //   Checkbox: Name & value is 'Lines'
     +  ' id="' + this.name + 'LinesCheck"'                                     //     ID for checkbox
     +  ' onClick="javascript: ' + this.name + '.changeLines()">'               //     OnClick -> java.changeLines
     +  '<a id="' + this.name + 'LinesTxt" class="Text"'                        //   Text: Define ID and class
     +  ' title="'  + this.linesTTip  + '">' +  this.linesTxt + '</a><br>';     //     Write tooltip and text
 str += '<img src="objects/icons/tree/empty.gif" alt="" />'                                    // Show Icons:
     +  '<input type="checkbox" name="Icons" value="Icons"'                     //   Checkbox: Name & value is 'Icons'
     +  ' id="' + this.name + 'IconsCheck"'                                     //     ID for checkbox
     +  ' onClick="javascript: ' + this.name + '.changeIcons()">'               //     OnClick -> java.changeIcons
     +  '<a id="' + this.name + 'IconsTxt" class="Text"'                        //   Text: Define ID and class
     +  ' title="'  + this.iconsTTip  + '">' +  this.iconsTxt + '</a><br>';     //     Write tooltip and text
 str += '<img src="objects/icons/tree/empty.gif" alt="" />'                                    // Autoclose:
     +  '<input type="checkbox" name="AClose" value="AClose"'                   //   Checkbox: Name & value is 'AClose'
     +  ' id="' + this.name + 'ACloseCheck"'                                    //     ID for checkbox
     +  ' onClick="javascript: ' + this.name + '.changeAClose()">'              //     OnClick -> java.changeAClose
     +  '<a id="' + this.name + 'ACloseTxt" class="Text"'                       //   Text: Define ID and class
     +  ' title="'  + this.acloseTTip  + '">' +  this.acloseTxt + '</a><br>';   //     Write tooltip and text
 str += '<img src="objects/icons/tree/empty.gif" alt="" />'                                    // Use Cookies:
     +  '<input type="checkbox" name="Cookies" value="Cookies"'                 //   Checkbox: Name & value is 'Cookies'
     +  ' id="' + this.name + 'CookiesCheck"'                                   //     ID for checkbox
     +  ' onClick="javascript: ' + this.name + '.changeCookies()">'             //     OnClick -> java.changeCookies
     +  '<a id="' + this.name + 'CookiesTxt" class="Text"'                      //   Text: Define ID and class
     +  ' title="'  + this.cookiesTTip  + '">' +  this.cookiesTxt + '</a><br>'; //     Write tooltip and text
 str += '<img src="objects/icons/tree/empty.gif" alt="" /><img src="objects/icons/tree/empty.gif" alt="" />'  // Expire period of cookies
     +  '&nbsp;<select name="Expire" size="1"'                                  //   Select options 'Expire'
     +  ' id="' + this.name + 'ExpireList"'                                     //     ID for options
     +  ' onClick="' + this.name + '.changeExpire()">';                         //   OnClick -> call this.changeExpire
 for (i=0; i<7; i++) str += '<option>' + Math.pow(2,i) + '</option>';           //   Loop: include options 1, 2, 4,...
 str += '</select>'                                                             //   Close 'Expire'-select
     +  '<a id="' + this.name + 'ExpireTxt" class="Text"'                       //   Text: Define ID and class
     +  ' title="'  + this.expireTTip  + '">' +  this.expireTxt + '</a><br>';   //     Write tooltip and text
 str += '<img src="objects/icons/tree/empty.gif" alt="" />';                                   // Open Level:
 str += '<select name="Level" size="1"'                                         //   Select options 'Level
     +  ' id="' + this.name + 'LevelList"'                                      //     ID for options
     +  ' onClick="' + this.name + '.clickLevel()">'                            //   OnClick  -> call this.clickLevel
     +  '<option>-</option>';                                                   //   Option '-'
 str += '</select>'                                                             //   End of 'select'
     +  '<a id="' + this.name + 'LevelTxt" class="Text"'                        //   Text: Define ID and class
     +  ' title="'  + this.levelTTip  + '">' +  this.levelTxt + '</a><br>';     //     Write tooltip and text
 str += '<img src="objects/icons/tree/empty.gif" alt="" />'                                    // Help:
     + '<a href="javascript: ' + this.name + '.help()">'                        //   Icon:
     +  '<img id="' + this.name + 'HelpGif"'                                    //     Write Html-string for the image
     +  ' src="objects/icons/tree/help.gif" alt="" /></a>';                                    //     and a reference to this -> toggle
 str += '<a href="javascript: ' + this.name + '.help()"'                        //   Text
     +  ' id="' + this.name + 'HelpTxt" class="Text"'                           //   Write Html-string for the text
     +  ' title="' + this.helpTTip + '">'                                       //   in class 'Text' with tooltip
     +  this.helpTxt + '</a>';                                                  //   and a reference to this -> help
 str += '<hr></div></div></form>';                                              // Close SubTree, class 'Config', form
 return str; }                                                                  // Return Html-string

config.prototype.toggle = function() {                                          //>Toggle config tree
 subTree = document.getElementById(this.name + 'SubTree');                      // Get element: SubTree
 status  = subTree.style.display;                                               // Status of the SubTree (open/close)
 subTree.style.display = (status=='none') ? 'block' : 'none';                   // Toggle SubTree-status
 if (subTree.style.display=='block') this.updateOnOpen(); }                     // UpdateOnOpen this using tree.values

config.prototype.updateOnOpen = function() {                                    //>Update config-tree on opening
 var check;                                                                     // Initialize 'check'
 check = document.getElementById(this.name + 'LinesCheck');                     // Lines checkbox
 check.checked = (this.tree.showLines)  ? true : false;                         //   Get setting from 'tree'
 check = document.getElementById(this.name + 'IconsCheck');                     // Icons checkbox
 check.checked = (this.tree.showIcons)  ? true : false;                         //   Get setting from 'tree'
 check = document.getElementById(this.name + 'ACloseCheck');                    // Icons checkbox
 check.checked = (this.tree.autoclose)  ? true : false;                         //   Get setting from 'tree'
 check = document.getElementById(this.name + 'CookiesCheck');                   // Cookies checkbox
 check.checked = (this.tree.useCookies) ? true : false;                         //   Get setting from 'tree'
 this.updateExpireList();                                                       // Update Expire-list
 this.fillLevelList(); }                                                        // Fill Level-list

config.prototype.updateExpireList = function() {                                //>UpdateExpireList
 for (i=0; i<7; i++) if (Math.pow(2,i)==this.tree.expire) break;                // Loop to find the tree's expire
 var list = document.getElementById(this.name + 'ExpireList');                  // Expire list
 list.selectedIndex = i; }                                                      // Select appropriate option

config.prototype.fillLevelList = function() {                                   //>FillLevelList
 var list = document.getElementById(this.name + 'LevelList');                   // Level list
 if (list.length>1) return;                                                     // List already filled -> nothing
 for (i=0; i<=this.tree.maxIndent; i++) {                                       // Loop levels up to maxIndent of tree
   var item = new Option(i,i,false,false);                                      //   Options 0, 1,... maxIndent-1
   list.options[list.length] = item; } }                                        //   Append next indent-number

config.prototype.changeLines = function() {                                     //>ChangeLines (Checkbox Show Lines)
 var check = document.getElementById(this.name + 'LinesCheck');                 // Lines checkbox
 this.tree.lines(check.checked); }                                              // Transfer setting to 'tree'

config.prototype.changeIcons = function() {                                     //>ChangeIcons (Checkbox Show Icons)
 var icon  = document.getElementById(this.name + 'ConfigGif');                  // Main icon of config-tree:
 var check = document.getElementById(this.name + 'IconsCheck');                 // Icons checkbos
 icon.src  = (check.checked) ? 'objects/icons/tree/config.gif' : 'objects/icons/tree/minIcon.gif';            // Show or hide main icon of this
 this.tree.icons(check.checked); }                                              // Show or hide the icons of the tree

config.prototype.changeAClose = function() {                                    //>ChangeAClose (Checkbox Autoclose)
 var check = document.getElementById(this.name + 'ACloseCheck');                // Autoclose checkbox
 this.tree.setAutoclose(check.checked); }                                       // Transfer setting to 'tree'

config.prototype.changeCookies = function() {                                   //>ChangeCookies (Checkbox Use Cookies)
 var check = document.getElementById(this.name + 'CookiesCheck');               // Cookies checkbox
 this.tree.cookies(check.checked); }                                            // Transfer setting to 'tree'

config.prototype.changeExpire = function() {                                    //>ChangeExpire (Listbox expire)
 var list = document.getElementById(this.name + 'ExpireList');                  // ExpireList, IE fakes 'list.value'
 this.tree.expiration(Math.pow(2,list.selectedIndex)); }                        // Transfer selected option to 'tree'

config.prototype.clickLevel = function() {                                      //>ClickLevel
 var list = document.getElementById(this.name + 'LevelList');                   // Level list
 if (this.level==list.selectedIndex-1) this.level = -1;                         // Invalidate level on opening options
 else { this.level = list.selectedIndex - 1;                                    // Else: Get selected level and
        if (this.level>=0) this.tree.level(this.level); } }                     //       apply selected level in tree

config.prototype.help = function() { alert(this.helpAlert); }                   //>Help message on click
