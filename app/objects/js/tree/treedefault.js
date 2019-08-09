function loaded(document) {                                                     //>Loaded a document
 if (0==parent.frames.length) return;                                           // Only if frames are used
 var pathDocu = backslashToSlash(document.location.pathname);                   // Path of the document  with '/'
 var pathMenu = backslashToSlash(parent.menu.document.location.pathname);       // Path of the menu-file with '/'
 var common   = 0;                                                              // common = Number of common characters
 var minLen   = pathDocu.length;                                                // minLen = Min(   pathlength of Docu
 if (minLen > pathMenu.length) minLen = pathMenu.length;                        //              or pathlength of Menu )
 for (var i=0; i<minLen; i++) {                                                 // Loop over minLen:
   if (pathDocu.charAt(i)!=pathMenu.charAt(i)) { common = i;   break; } }       //   Equal character -> increase common
 var pathRel = '';                                                              // Initialize relative path
 for (var i=common; i<pathMenu.length; i++) {                                   // Loop substring behind 'common'
   if (pathMenu.charAt(i)=='/') pathRel = pathRel + '../'; }                    //   Each '/' leads to '../' in pathRel
 pathRel = pathRel + pathDocu.substr(common);                                   // Add remaining pathDocu to pathRel
 if (parent.menu.tree) parent.menu.tree.selectPath(pathRel); }                  // Select appropriate node in menu

function backslashToSlash(path) {                                               //>BackslashToSlash
 var parts = path.split("\\");                                                  // Split path at '\' into string-array
 var str   = parts[0];                                                          // Write first part to 'str'
 for (var i=1; i<parts.length; i++) str = str + '/' + parts[i];                 // Add next parts divided by '/'
 return str; }                                                                  // Return path with '/' instead of '\'