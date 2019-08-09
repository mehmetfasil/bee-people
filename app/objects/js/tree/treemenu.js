
function Node(id,indent,text,target,url,tooltip,iconOpen,iconClose,isOpen) {    //>Node (Folder or Item)
 this.id        = id;                 this.indent    = indent;                  // Initialize variables
 this.text      = text;               this.target    = target;                  //     ..        ..
 this.url       = url;                this.tooltip   = tooltip;                 //     ..        ..
 this.iconOpen  = iconOpen;           this.iconClose = iconClose;               //     ..        ..
 this.parent    = null;               this.childs    = [];                      //     ..        ..
 this.isOpen    = isOpen;   }                                                   //     ..        ..

function treemenu(name, showLines, showIcons, useCookies) {                     //>treemenu
 this.name      = name;               this.showLines = showLines;               // Initialize variables
 this.showIcons = showIcons;          this.useCookies= useCookies;              //     ..        ..
 this.nodes     = [];                 this.root      = new Node(-1,-1,'root');  //     ..        ..
 this.selected  = -1;                 this.maxIndent = 0;                       //     ..        ..
 this.expire    = 1;                  this.openNodes = '';                      //     ..        ..
 this.classDepth= 2;                  this.autoclose = false;                   // ClassDepth for text-format-> css-file
 this.readCookies();                                                            // Read cookies if available
 if (!navigator.cookieEnabled) this.useCookies = false;                         // Respect the browsers cookie setting
 if (arguments.length>=5)      this.autoclose  = arguments[4];                  // Optional argument for 'autoclose'
 this.defaults = {                                                              // Default images/icons
   iconRoot  : 'objects/icons/tree/root.gif',        iconItem  : 'objects/icons/tree/item.gif',               //    ..        ..
   iconOpen  : 'objects/icons/tree/open.gif',        iconClose : 'objects/icons/tree/close.gif',              //    ..        ..
   passLine  : 'objects/icons/tree/passline.gif',    empty     : 'objects/icons/tree/empty.gif',              //    ..        ..
   tieLine   : 'objects/icons/tree/tieline.gif',     tiePlus   : 'objects/icons/tree/tieplus.gif',            //    ..        ..
   endLine   : 'objects/icons/tree/endline.gif',     endPlus   : 'objects/icons/tree/endplus.gif',            //    ..        ..
   rectPlus  : 'objects/icons/tree/rectplus.gif',    tieMinus  : 'objects/icons/tree/tieminus.gif',           //    ..        ..
   rectMinus : 'objects/icons/tree/rectminus.gif',   endMinus  : 'objects/icons/tree/endminus.gif',           //    ..        ..
   minIcon   : 'objects/icons/tree/minicon.gif'  } }                                           //
                                                                                // ----------- Build up menu -----------
treemenu.prototype.put = function(open, label, target, url,                     //>Put a node to the treemenu
                                  tooltip, iconOpen, iconClose) {               //     that is initially to be loaded
 if (this.selected==-1) this.selected = this.nodes.length;                      // Set 'selected' if not cookie-defined
 this.add(open, label, target, url, tooltip, iconOpen, iconClose); }            // Add a node to the treemenu

treemenu.prototype.add = function(open, label, target, url,                     //>Add a node to the treemenu
                                  tooltip, iconOpen, iconClose) {               //
 var indent = 0;                                                                // Indent: initialize
 while (label.charAt(indent)==' ') indent++;                                    //   Indent by leading spaces
 if (this.maxIndent<indent) this.maxIndent = indent;                            //   Adjust 'maxIndent'
 var id     = this.nodes.length;                                                // ID of the new node
 var isOpen = (open==0) ? false : true;                                         // IsOpen from given value '0' or '1'
 if (this.openNodes && id<this.openNodes.length)                                // On given 'OpenNodes'
     isOpen = (this.openNodes.charAt(id)=='1') ? true : false;                  // -> Status depending on cookie
 var node   = new Node(id, indent, label.substr(indent),                        // New node: ID corresponds with number
                       target, url, tooltip, iconOpen, iconClose, isOpen);      //   Text without leading spaces
 this.nodes[this.nodes.length] = node;                                          //   Append node to the nodes-array
 for (var i=this.nodes.length-1; i>=0; i--)                                     // Parent node:
   if (this.nodes[i].indent < indent) { node.parent = this.nodes[i];   break; } //   Loop back to find parent by indent
 if (!node.parent) node.parent = this.root;                                     //   Root-node is parent if none found
 if (node.parent.indent<node.indent-1)                                          //   Invalid indent
     alert('Indent of "' + node.text + '" must be <' + (node.parent.indent+2)); //   -> alert-message
 node.parent.childs[node.parent.childs.length] = node; }                        //   New node is child of the parent

                                                                                // ---------- Build Html-code ----------
treemenu.prototype.toString = function() {                                      //>ToString used by document.write(...)
 var str = '<div class="TreeMenu">';                                            // Encapsulate class 'TreeMenu'
 var lastIndent = 0;                                                            // Initialize lastIndent
 for (var id=0; id<this.nodes.length; id++) {                                   // Loop: Nodes
   var node = this.nodes[id]                                                    //   Current node
   if (lastIndent < node.indent) lastIndent = node.indent;                      //   Update lastIndent to max
   while (lastIndent>node.indent) { str += '</div>';   lastIndent--; }          //   Close previous </div>-Subtrees
   str += this.writeNode(node);                                                 //   Write node
   if (0<node.childs.length) {                                                  //   Parent -> SubTree of childs
     str += '<div id="' + this.name + 'SubTree_' + id                           //   -> Write <div..-block to display
         +  '" class="SubTree_' + node.indent                                   //           or to hide the SubTree
         +  '" style="display:'                                                 //           according to isOpen-value
         +  ((node.isOpen) ? 'block' : 'none') + '">'; } }                      //         + Defining class SubTree_x
 for (var i=lastIndent; i>0; i--) str += '</div>';                              // Close remaining SubTrees
 str += '</div>';                                                               // Close class 'TreeMenu'
 this.setCookies(this.expire);                                                  // Set Cookies
 this.loadSelected();                                                           // LoadSelected on already filled frames
 // alert(str);                                                                 // Discomment to see the Html-Code
 return str;  }                                                                 // Return HTML-String

                                                                                // -------------- Write ----------------
treemenu.prototype.writeNode = function(node) {                                 //>WriteNode
 if (node.target=='hide') return '';                                            // Only node with no hidden target
 var str = '<div>'                                                              // Open <div>-block for the node
         + this.writeIndenting(node)  + this.writeTieUpIcon(node)               // Write Indenting, tieUpIcon
         + this.writeNodeSymbol(node) + this.writeNodeText(node) + '</div>';    //       Symbol, Text, close 'TreeNode'
 return str; }                                                                  // Return cumulated Html-String

treemenu.prototype.writeIndenting = function(node) {                            //>WriteIndenting
 if (node.indent < 2) return '';                                                // Only if node-indent >= 2
 var str      = '';                                                             // Initialize str
 var icons    = [];                                                             //            icons[]
 var ancestor = node.parent;                                                    // Start at ancestor = node.parent
 for (var i=node.indent-2; i>=0; i--, ancestor=ancestor.parent) {               // Loop ancestors from right to left
      icons[i] = (this.isLastChild(ancestor) ? 'empty' : 'passLine');  }        //   Last child -> empty, else passLine
 for (var i=0; i<=node.indent-2; i++) {                                         // Loop from left to right:
      var icon = this.defaults.empty;                                           //   Default icon = empty
      if (this.showLines && icons[i]!='empty') icon = this.defaults.passLine;   //   or passLine to be shown
      str += '<img name="' + icons[i] + '" src="' + icon + '" border="0" alt="" align="top" />'; }     //   Html-string for the icon
 return str;  }                                                                 // Return html-string

treemenu.prototype.writeTieUpIcon = function(node)  {                           //>WriteTieUpIcon
 if (node.indent < 1) return '';                                                // Only for indents > 1
 var icon = this.getTieUpIcon(node);                                            // GetTieUpIcon
 var str  = '';                                                                 // Initialize str
 if (0==node.childs.length)                                                     // No childs -> Return only TieUpIcon
      str = '<img id="' + this.name + 'TieUp_' + node.id                        //   Write tieUpIcon with
          + '" src="' + icon + '" border="0" alt="" align="top" />';                                   //   name & source
 else str = '<a href="javascript: ' + this.name + '.toggle(' + node.id + ')">'  // Parent node:
          +  '<img id="' + this.name + 'TieUp_' + node.id                       //   Write tieUpIcon with
          +  '" src="' + icon + '" border="0" alt="" align="top" /></a>';                              //   name, source & javascript:toggle
 return str; }                                                                  // Html-code for the TieUpIcon

treemenu.prototype.getTieUpIcon = function(node) {                              //>GetTieUpIcon
 if (0 == node.childs.length) {                                                 // No childs:
   if      (!this.showLines)        return this.defaults.empty;                 //   Don't show Lines   -> empty
   else if (this.isLastChild(node)) return this.defaults.endLine;               //   Else if last child -> endLine
   else                             return this.defaults.tieLine;  }            //   Else if fore child -> tieLine
 else if (node.isOpen) {                                                        // Open parent:
   if      (!this.showLines)        return this.defaults.rectMinus;             //   Don't show Lines   -> rectMinus
   else if (this.isLastChild(node)) return this.defaults.endMinus;              //   Else if last child -> endMinus
   else                             return this.defaults.tieMinus; }            //   Else if fore child -> tieMinus
 else {                                                                         // Closed parent:
   if      (!this.showLines)        return this.defaults.rectPlus;              //   Don't show Lines   -> rectPlus
   else if (this.isLastChild(node)) return this.defaults.endPlus;               //   Else if last child -> endPlus
   else                             return this.defaults.tiePlus;  } }          //   Else if fore child -> tiePlus

treemenu.prototype.writeNodeSymbol = function(node) {                           //>WriteNodeSymbol
 var icon = this.getNodeSymbol(node) ;                                          // GetNodeSymbol
 if (0==node.childs.length) {                                                   // No childs:
   var str = '';                                                                //   Reference to the nodes url
   if (node.url) {    str += '<a href="' + node.url + '"';                      //     if a url is given and load
     if (node.target) str += ' target="' + node.target + '"';                   //     the url into the target frame.
                      str += '>'; }                                             //     Close leading <a..>-tag
   str += '<img id="' + this.name + 'Symbol_' + node.id                         //   Write the Html-code for the
       +  '" src="'   + icon + '" border="0" alt="" align="top" />';                                   //     image of the node-symbol
   if (node.url) str += '</a>';                                                 //   Close trailing </a>-tag if any
   return str; }                                                                //   Return Html-string for symbol
 return   '<a href="javascript: ' + this.name + '.toggle(' + node.id + ')">'    // Parent:
          + '<img id="' + this.name + 'Symbol_' + node.id                       //   Write Html-string for the image
          + '" src="' + icon + '" border="0" alt="" align="top" /></a>'; }                             //   and a reference to java -> toggle

treemenu.prototype.getNodeSymbol = function(node) {                             //>GetNodeSymbol
 if (!this.showIcons)  return this.defaults.minIcon;                            // No Symbols-> 'minIcon' (for IE)
 if (0==node.childs.length) {                                                   // No childs:
   if (node.iconOpen)  return node.iconOpen;                                    //   Use nodes  'iconOpen'
   else                return this.defaults.iconItem;  }                        //   or default 'iconItem'
 else if (node.isOpen) {                                                        // Open parent:
   if (node.iconOpen)  return node.iconOpen;                                    //   Use nodes  'iconOpen'
   else                return this.defaults.iconOpen;  }                        //   or default 'iconOpen'
 else {                                                                         // Closed parent:
   if (node.iconClose) return node.iconClose;                                   //   Use nodes  'iconClose'
   else                return this.defaults.iconClose; } }                      //   or default 'iconClose'

treemenu.prototype.writeNodeText = function(node) {                             //>WriteNodeText
 var cls = this.getNodeTextClass(node, this.selected);                          // Get NodeTextClass
 var str = '<a id="' + this.name + 'Node_' + node.id + '" class="' + cls + '"'; // Add '<a id=...' and 'class=...'
 if (node.url) str += ' href="' + node.url + '"';                               // HRef-link to node.url
 else          str += ' href="javascript: '+this.name+'.toggle('+node.id+')"';  //     or to java.toggle
 if (node.url && node.target)  str += ' target="' + node.target   + '"';        // Target ="node.target"
 if (node.tooltip)             str += ' title="'  + node.tooltip  + '"';        // Title  ="node.tooltip"
 str += ' onclick="javascript: ' + this.name + '.pick(' + node.id + ')"';       // OnClick="javascript.pick"
 str += '>' + node.text + ((node.url) ? '</a>' : '</a>') ;                      // Node text, close 'a>'
 return str; }                                                                  // Return HTML-string

treemenu.prototype.getNodeTextClass = function(node, selectID) {                //>GetNodeTextClass for TreeMenu.css
 var cls = (node.id==selectID) ? 'Selected' : 'Node';                           // Class 'Selected', 'Node'
 if (!node.url) cls = 'Item';                                                   //    or 'Item' (without url)
 return cls + '_' + Math.min(node.indent, this.classDepth); }                   // Append '_indent' or '_classDepth'

treemenu.prototype.writeCreatedWithTreeMenu = function() {                      //>WriteCreatedWithTreeMenu
 var path = '',    target ='';                                                  // Path to the freeware 'treemenu'
 var elem = document.getElementById('treepath');                                // Path defined in document:
 if (elem) { target = 'main';       path = elem.title;                 }        //   ->   Use defined path
 else      { target = '_parent';                                                //   Else call 'http://h-bauer.de?...'
             path   = 'http://ibs.egmidb.gov.tr'; }        //        using direct access
 var str = '<br><a class="CreatedBy" target="' + target +'"'       // ! Please don't alter this code to   !
         + ' href="' + path + '">Masters</a>';                                 // ! give your visitors the ability to !
 return str; }                                                                  // ! also access this utility. THANKS! !

                                                                                // --------------- Load ----------------
treemenu.prototype.loadSelected = function() { this.loadNode(this.selected); }  //>LoadSelected

treemenu.prototype.loadNode = function(id) {                                    //>LoadNode by ID into it's target frame
 if (id<0 || id>=this.nodes.length) return;                                     // Only nodes with valid id
 if (this.nodes[id].target=='hide') return;                                     // Only nodes with no hidden target
 for (var i=0; i<parent.frames.length; i++) {                                   // Loop: Frames in frameset
   if (parent.frames[i].name==this.nodes[id].target) {                          //   Target-frame of the selected node
     parent.frames[i].location.href = this.nodes[id].url;                       //   -> Reference to the node to load
     break; } } }                                                               //      Break the loop and return

                                                                                // ----------- Pick / Select -----------
treemenu.prototype.pick = function(id) {                                        //>Pick a node by id
 var node = this.nodes[id];                                                     // Picked node
 if (node.url) {                                                                // Nodes with URL (->no href to toggle)
   if      (node.indent==0       && this.showIcons==false) this.toggle(id);     // -> Toggle top node without icon
   else if (node.childs.length>0 && node.isOpen==false)    this.toggle(id); }   //    Else: open closed parent node
 this.select(id); }                                                             // Select node by ID & unselect previous

treemenu.prototype.select = function(id) {                                      //>Select a node by a given ID
 if (id<0 || id>=this.nodes.length) return;                                     // Only nodes with valid id
 if (!this.nodes[id].url) return;                                               // Only for a node with url:
 if (this.autoclose==true) this.autocloseTree(id);                              // Autoclose the old tree (?)
 if (this.selected>=0 && this.selected<this.nodes.length) {                     // Deselect selected Html-node:
   var nodeA = document.getElementById(this.name + 'Node_' + this.selected);    //   Get selected Html-node by id
   var nameA = this.getNodeTextClass(this.nodes[this.selected],-1);             //   ClassName for unselected
   if (nodeA && nameA) nodeA.className = nameA;                                 //   Unselect previous selected node
   this.selected  = -1;  }                                                      //   Invalidate this.selected
 var nodeB = document.getElementById(this.name + 'Node_' + id);                 // Select Html-node:
 var nameB = this.getNodeTextClass(this.nodes[id], id);                         //   ClassName for selected
 if (nodeB && nameB) nodeB.className = nameB;                                   //     Selected previous unselected node
 this.selected  = id;                                                           //     Set this.selected value to id
 this.openAncestors(id);                                                        //     Open the nodes ancestors
 this.setCookies(this.expire); }                                                //     Set cookies

treemenu.prototype.selectPath = function(path) {                                //>SelectPath
 var path = this.pathWithSlash(path);                                           // Ensure path with slash '/'
 if (this.selected>=0 && this.selected<this.nodes.length) {                     // A node ist already selected:
   var url = this.pathWithSlash(this.nodes[this.selected].url);                 //   URL of the selected node
   if (url==path) return;  }                                                    //   URL already selected -> return
 for (var id=0; id<this.nodes.length; id++) {                                   // Loop to search node:
   var url = this.pathWithSlash(this.nodes[id].url);                            //   Node path with slash '/'
   if (url && url==path) { this.select(id);    break; } } }                     //   Equal path -> select node by id

treemenu.prototype.openAncestors = function(id) {                               //>OpenAncestors of the node with id
 if (id<0) return;                                                              // Only valid nodes with ID>=0
 var ancestor = this.nodes[id].parent;                                          // Ancestor is parent node;
 while(ancestor.indent>=0) {                                                    // Loop: Ancestors
   if (!ancestor.isOpen) { ancestor.isOpen=true;  this.updateNode(ancestor); }  //   Open and update ancestor
   ancestor = ancestor.parent;   } }                                            //   Parent of ancestor  }

treemenu.prototype.pathWithSlash = function(path) {                             //>PathWithSlash
 var parts = path.split("\\");                                                  // Split path at '\' into string-array
 var str   = parts[0];                                                          // Write first part to 'str'
 for (var i=1; i<parts.length; i++) str = str + '/' + parts[i];                 // Add next parts divided by '/'
 return str; }                                                                  // Return path with '/' instead of '\'

                                                                                // --------- Autoclose Tree ------------
treemenu.prototype.setAutoclose = function(bool) { this.autoclose = bool; }     //>SetAutoclose variable

treemenu.prototype.autocloseTree = function(id) {                               //>AutocloseTree for left subtree
 var current = this.getPathIDs(this.selected);                                  // PathIDs of current selected node
 var picked  = this.getPathIDs(id);                                             // PathIDs of mewly picked node
 var minLen  = (current.length<picked.length) ? current.length : picked.length; // Minimal number of path-steps
 for (var i=0; i<minLen; i++) {                                                 // Loop common ancestors:
   if (current[i]!=picked[i]) {                                                 //   First uncommon ancestors
     var id = current[i];                                                       //   -> Get the node-id of the selected
     this.nodes[id].isOpen=false;                                               //      Close the associated subtree
     this.updateNode(this.nodes[id]);                                           //      Update the associated node
     return; } } }                                                              //      Return: Autoclose is done

treemenu.prototype.getPathIDs = function(id) {                                  //>GetPathIDs (array of id's)
 var ids       = new Array(this.nodes[id].indent+1);                            // Instantiate array[0,1..] of id's
 var ancestor  = this.nodes[id];                                                // Self-ID
 while(ancestor.indent>=0) {                                                    // Loop ancestors:
   ids[ancestor.indent] = ancestor.id;                                          //   Store the id using the indent
   ancestor = ancestor.parent; }                                                //   Get next ancestor
 return ids; }                                                                  // Return array of id's

                                                                                // ---------- Toggle / Update ----------
treemenu.prototype.toggle = function(id) {                                      //>Toggle a node by id
 if (this.nodes[id].childs.length==0) return;                                   // Only for parent nodes
 this.nodes[id].isOpen = !this.nodes[id].isOpen;                                // Toggle node-status (open or close)
 this.updateNode(this.nodes[id]);                                               // Update the node
 this.setCookies(this.expire); }                                                // Set cookies

treemenu.prototype.updateNode = function(node) {                                //>UpdateNode
 var subTree = document.getElementById(this.name + 'SubTree_' + node.id);       // Get Html-element: SubTree
 var tieUp   = document.getElementById(this.name + 'TieUp_'   + node.id);       //                   TieUpIcon
 var symbol  = document.getElementById(this.name + 'Symbol_'  + node.id);       //                   NodeSymbol
 if (subTree)  subTree.style.display = (node.isOpen) ? 'block' : 'none';        // Update Html-elem. SubTree
 if (tieUp)    tieUp.src   = this.getTieUpIcon(node);                           //                   TieUpIcon
 if (symbol)   symbol.src  = this.getNodeSymbol(node); }                        //                   NodeSymbol

                                                                                // ----------- IsLastChild -------------
treemenu.prototype.isLastChild = function(node) {                               //>IsLastChild (?)
 var parent = node.parent;                                                      // Parent of the node
 return ((node == parent.childs[parent.childs.length-1]) ? true : false); }     // Check for last child

                                                                                // --------- Level/Lines/Icons ---------
treemenu.prototype.level = function(level) {                                    //>Level to open/close menu
 for (var id=0; id<this.nodes.length; id++) {                                   // Loop: nodes
   this.nodes[id].isOpen = (this.nodes[id].indent<level) ? true : false;        //   Open/close node depending on level
   this.updateNode(this.nodes[id]); }                                           //   Update the node
 this.setCookies(this.expire); }                                                // Set cookies

treemenu.prototype.lines = function(bool) {                                     //>Lines to be shown (?)
 if (this.showLines == bool) return;                                            // Nothing changed -> return
 this.showLines = bool;                                                         // Update 'showLines'
 var passLines = document.getElementsByName("passLine");                        // Get PassLines
 if (!passLines) return;                                                        // Existing passLines:
 for (var i=0; i<passLines.length; i++) {                                       //  Loop: passLines
   passLines[i].src = (bool) ? this.defaults.passLine : this.defaults.empty; }  //   Update icon-source
 for (var id=0; id<this.nodes.length; id++) {                                   // TieUpIcon for each node
   if (this.nodes[id].indent < 1) continue;                                     //   with indent >= 1
   var tieUp = document.getElementById(this.name + 'TieUp_' + id);              //   TieUpIcon of the node
   if (tieUp) tieUp.src = this.getTieUpIcon(this.nodes[id]);  }                 //   Update icon-source
 this.setCookies(this.expire); }                                                // Set cookies

treemenu.prototype.icons = function(bool) {                                     //>Icons to be shown (?)
 if (this.showIcons == bool) return;                                            // Nothing changed -> return
 this.showIcons = bool;                                                         // Set 'showIcons'-value
 for (var id=0; id<this.nodes.length; id++) {                                   // Loop: nodes
   var icon  = this.getNodeSymbol(this.nodes[id]);                              //   Get node symbol
   var image = document.getElementById(this.name + 'Symbol_' + id)              //   Get Html-image by id
   if (image)  image.src = icon; }                                              //   Set image source to node symbol
 this.setCookies(this.expire); }                                                // Set cookies

                                                                                // -------------- Cookies --------------
treemenu.prototype.expiration = function(expire) {                              //>Expiration
 this.expire = expire;          this.setCookies(this.expire); }                 // Set/Save expiration period of cookies

treemenu.prototype.cookies = function(bool) {                                   //>Cookies to be used (?)
 if (bool) { this.useCookies = bool;  this.setCookies(this.expire);   }         // Use cookies -> Set cookies
 else      { this.setCookies(-1);     this.useCookies = bool;         } }       // No  cookies -> Clear existing cookies

treemenu.prototype.setCookies = function(expire) {                              //>SetCookies
 this.openNodes = '';                                                           // Initialize 'openNodes'-String
 for (var i=0; i<this.nodes.length; i++)                                        // Loop: nodes
   this.openNodes += (this.nodes[i].isOpen) ? '1' : '0';                        //   Fill 'openNodes'-String
 this.setCookie("OpenNodes", this.openNodes, expire);                           // Set cookie 'OpenNodes'
 this.setCookie("ShowLines", this.showLines, expire);                           //            'ShowLines'
 this.setCookie("ShowIcons", this.showIcons, expire);                           //            'ShowIcons'
 this.setCookie("Selected",  this.selected,  expire);                           //            'Selected'
 this.setCookie("Autoclose", this.autoclose, expire);                           //            'Autoclose'
 this.setCookie("Expire"   , this.expire,    expire); }                         //            'Expire'

treemenu.prototype.readCookies = function() {                                   //>ReadCookies (as string!)
 var lines  = this.getCookie("ShowLines");                                      // Get Cookie:  'ShowLines'
 var icons  = this.getCookie("ShowIcons");                                      //              'ShowIcons'
 var select = this.getCookie("Selected");                                       //              'Selected'
 var autocl = this.getCookie("Autoclose");                                      //              'Autoclose'
 var open   = this.getCookie("OpenNodes");                                      //              'OpenNodes'
 var expire = this.getCookie("Expire");                                         //              'Expire'
 if (lines)   this.showLines = (lines=='true') ? true : false;                  // Set value of 'showLines'
 if (icons)   this.showIcons = (icons=='true') ? true : false;                  //              'showIcons'
 if (select)  this.selected  = select;                                          //              'selected'
 if (autocl)  this.autoclose = autocl;                                          //              'autoclose'
 if (open)    this.openNodes = open;                                            //              'openNodes'
 if (expire)  this.expire    = expire;                                          //              'expire'
 if (lines || icons || select || open || autocl) this.useCookies = true;  }     // Cookies found -> useCookies is true

                                                                                // --------------- Cookie --------------
treemenu.prototype.setCookie = function(name, value, expire) {                  //>SetCookie by name and value
 if (!this.useCookies) return;                                                  // Only if cookies are to be used
 var exp = new Date();                                                          // Actual date
 var end = exp.getTime() + (expire * 24 * 60 * 60 * 1000);                      // In 'expire'-days (-1: -> invalidate)
 exp.setTime(end);                                                              // Expire time of cookes
 document.cookie = this.name+name+'='+value+'; expires='+exp.toGMTString(); }   // Set cookie with expiration-date

treemenu.prototype.getCookie = function(name) {                                 //>GetCookie value (as string!)
 var cookies  = document.cookie;                                                // Cookies separated by ';'
 var posName  = cookies.indexOf(this.name + name + '=');                        // Start position of 'tree_name='
 if (posName == -1) return '';                                                  // Cookie not found -> Return ''
 var posValue = posName + this.name.length + name.length + 1;                   // Start position of cookie-value
 var endValue = cookies.indexOf(';',posValue);                                  // End position of cookie value at ';'
 if (endValue !=-1) return cookies.substring(posValue, endValue);               // ';' -> Return substring as value
 return cookies.substring(posValue); }                                          // Else-> Return rest of line as value
