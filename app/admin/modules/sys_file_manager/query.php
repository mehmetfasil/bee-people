<?php
defined("PASS") or die("Dosya yok!");

//MIME Tipleri
$mimes = array(
 "ai"=>"Adobe Illustrator",
 "aif"=>"Audio Interchange",
 "aiff"=>"Audio Interchange",
 "ani"=>"Animated Cursor",
 "ans"=>"ANSI Text",
 "api"=>"Application Program Interface",
 "app"=>"Macromedia Authorware Package",
 "arc"=>"ARC Compressed",
 "arj"=>"ARJ Compressed",
 "asc"=>"ASCII Text",
 "asf"=>"Active Streaming",
 "asm"=>"Assembly Source Code",
 "asp"=>"Active Server Page",
 "avi"=>"AVI Movie",
 "bak"=>"Backup Copy",
 "bas"=>"BASIC Program",
 "bat"=>"Batch",
 "bk"=>"Backup",
 "bk"=>"Backup Copy",
 "bmp"=>"Bitmap",
 "c"=>"C Program",
 "cab"=>"Microsoft Compressed",
 "cdr"=>"CorelDraw",
 "cdt"=>"CorelDraw Template",
 "cdx"=>"CorelDraw Compressed",
 "cdx"=>"FoxPro Database Index",
 "cfg"=>"Configuration",
 "cgi"=>"Common Gateway Interface",
 "cpp"=>"C++ Program",
 "css"=>"Cascading Style Sheet",
 "csv"=>"Comma Delimited",
 "cur"=>"Windows Cursor Image",
 "dat"=>"Data",
 "db"=>"Table - Paradox",
 "dbc"=>"Visual FoxPro Database",
 "dbf"=>"dBASE Database",
 "dbt"=>"dBASE Database Text",
 "doc"=>"Document file",
 "drv"=>"Driver",
 "dwg"=>"AutoCAD Vector",
 "eml"=>"Electronic Mail",
 "enc"=>"Encoded",
 "eps"=>"Encapsulated PostScript",
 "exe"=>"Executable",
 "fax"=>"Fax",
 "fla"=>"Flash File",
 "fnt"=>"Font",
 "fon"=>"Bitmapped Font",
 "fot"=>"TrueType Font",
 "gif"=>"Graphics Interchange",
 "gz"=>"GZIP Compressed",
 "h"=>"C Header",
 "hlp"=>"Help",
 "htm"=>"HTML Document",
 "html"=>"HTML Document",
 "ico"=>"Icon",
 "it"=>"MOD Music",
 "jpeg"=>"JPEG Image",
 "jpg"=>"JPEG Image",
 "js"=>"Javascript",
 "lib"=>"Library",
 "log"=>"Log",
 "lst"=>"List",
 "mime"=>"MIME",
 "mme"=>"MIME Encoded",
 "mov"=>"QuickTime Movie",
 "movie"=>"QuickTime movie",
 "mp2"=>"MP2 Audio",
 "mp3"=>"MP3 Audio",
 "mpe"=>"MPEG",
 "mpeg"=>"MPEG Movie",
 "mpg"=>"MPEG Movie",
 "pas"=>"Pascal Program",
 "phps"=>"PHP Source Code",
 "phtml"=>"HTML Document",
 "php"=>"PHP Document",
 "png"=>"Portable Network Graphics",
 "pjx"=>"Visual FoxPro Project",
 "pl"=>"Perl script",
 "pps"=>"PowerPoint Slideshow",
 "ppt"=>"PowerPoint Presentation",
 "psd"=>"Photoshop Document",
 "qt"=>"QuickTime Movie",
 "qtm"=>"QuickTime Movie",
 "ra"=>"Real Audio",
 "ram"=>"Real Audio",
 "rar"=>"Winrar Archive",
 "reg"=>"Registration File",
 "rm"=>"Real Media",
 "s"=>"Assembly Language",
 "shtml"=>"HTML Document",
 "sit"=>"StuffIT Compressed",
 "snd"=>"Sound File",
 "swf"=>"Flash Movie",
 "swp"=>"Swap Temporary File",
 "sql"=>"SQL Script File",
 "sys"=>"System File",
 "tar"=>"Tape Archive",
 "tga"=>"TARGA Graphics",
 "tgz"=>"Tape Archive",
 "tif"=>"TIFF Graphics",
 "tiff"=>"TIFF Graphics",
 "tmp"=>"Temporary File",
 "ttf"=>"TrueType Font",
 "txt"=>"Text File",
 "uu"=>"Uuencode Compressed",
 "uue"=>"Uuencode File",
 "vbp"=>"Visual Basic Project",
 "vbx"=>"Visual Basic Extension",
 "wab"=>"Windows Address Book",
 "wav"=>"Sound File",
 "xlm"=>"Excel Macro",
 "xls"=>"Excel Worksheet",
 "xlt"=>"Excel Template",
 "xm"=>"MOD Music",
 "xxe"=>"Xxencoded File",
 "z"=>"Unix Archive",
 "zip"=>"ZIP Archive"
);

//Yol
$path = strlen(getvalue("path")) > 0 ? getvalue("path") : "";
$path = ((strlen($path) > 0) and ($path{strlen($path)-1} != "/")) ? $path."/" : $path;

$dir = CONF_DOCUMENT_ROOT.$path;

switch (ACT){
 case "list":

  clearstatcache();
  
  //Klasörler
  $folders = getFolders($dir);

  //Dosyalar
  $files = getFiles($dir);
  
  //Gönderilecek liste
  $list = array();

  foreach ($folders as $folder){
   $fo = getFolders($dir.$folder."/");
   $fi = getFiles($dir.$folder."/");
   $deletable = ((count($fo) < 1) and (count($fi) < 1)) ? true : false;
   
   $list[] = array(
    "type"=>"folder",
    "path"=>$path.$folder,
    "name"=>$folder,
    "size"=>"-",
    "modified"=>"-",
    "mime"=>page::showText("FOLDER"),
    "icon"=>"objects/icons/24x24/folder.png",
    "writable"=>(is_writable($dir.$folder) ? true : false),
    "deletable"=>$deletable
   );
  }

  foreach ($files as $file){
   $ext = str_replace(".", "", strtolower(strrchr($file, ".")));
   $icon = file_exists(CONF_DOCUMENT_ROOT."objects/icons/mimetypes/".$ext.".png") ? $ext.".png" : "unknown.png";
   
   $list[] = array(
    "type"=>"file",
    "path"=>$path.$file,
    "name"=>$file,
    "size"=>round((filesize($dir.$file) / 1024), 2)." KB",
    "modified"=>date("d-m-Y H:i:s", fileatime($dir.$file)),
    "mime"=>(isset($mimes[$ext]) ? $mimes[$ext] : page::showText("UNKNOWN")),
    "icon"=>"objects/icons/mimetypes/".$icon,
    "writable"=>(is_writable($dir.$file) ? true : false),
    "deletable"=>(is_writable($dir.$file) ? true : false)
   );
  }
  
  if (count($list) > 0) {
  	echo "<list>";
  	echo "<head>";
  	echo "<title width=\"5%\" align=\"center\"><![CDATA[&nbsp;]]></title>";
  	echo "<title><![CDATA[".page::showText("FILE OR FOLDER NAME")."]]></title>";
  	echo "<title align=\"center\"><![CDATA[".page::showText("SIZE")."]]></title>";
  	echo "<title align=\"center\"><![CDATA[".page::showText("LAST ACCESS")."]]></title>";
  	echo "<title align=\"center\"><![CDATA[".page::showText("MIME TYPE")."]]></title>";
  	echo "<title align=\"center\"><![CDATA[".page::showText("ISWRITABLE")."]]></title>";
  	echo "<title align=\"center\"><![CDATA[&nbsp;]]></title>";
  	echo "</head>";
  	echo "<body>";
  	
  	foreach ($list as $values){
  	 echo "<row>";
  	 echo "<value><![CDATA[<img src=\"".$values["icon"]."\" width=\"24\" height=\"24\" alt=\"".$values["name"]."\" />]]></value>";
  	 echo "<value><![CDATA[<a href=\"javascript:void(0)\" onclick=\"".($values["type"]=="folder" ? "f.list({path:'".$values["path"]."'})" : "f.download('".$values["path"]."')")."\">".$values["name"]."</a>]]></value>";
  	 echo "<value><![CDATA[".$values["size"]."]]></value>";
  	 echo "<value><![CDATA[".$values["modified"]."]]></value>";
  	 echo "<value><![CDATA[".$values["mime"]."]]></value>";
  	 echo "<value><![CDATA[<span class=\"".($values["writable"] ? "green" : "red")."\">".($values["writable"] ? page::showText("WRITABLE") : page::showText("UNWRITABLE"))."</span>]]></value>";
  	 echo "<value><![CDATA[".($values["deletable"] ? "<a href=\"javascript:void(0)\" onclick=\"f.del('".$values["path"]."')\"><img src=\"objects/icons/16x16/trash.png\" width=\"16\" height=\"16\" alt=\"".page::showText("DELETE")."\" /></a>" : "&nbsp;")."]]></value>";
  	 echo "</row>";
  	}
  	
  	echo "</body>";
  	echo "</list>";
  }
	
  
  break;
	
	case "getFolders":
		//Klasörler
  	$folders = getFolders($dir);
		$xml ="<list>";
		foreach($folders as $folder){
			$xml.="<item><label><![CDATA[".$folder."]]></label></item>";
		}
		$xml.="</list>";
		echo $xml;
		break;
	
 case "create":

  if (preg_match("/^[a-zA-Z0-9_-]+$/", getvalue("folder"))) {
   if (is_dir($dir)){
    if (is_writable($dir)){
     if (@mkdir($dir.getvalue("folder"), 0755)){
      echo "<result status=\"OK\" />";
     }else{
      echo "<result status=\"ERROR\"><![CDATA[".page::showText("FOLDER NOT CREATED")."]]></result>";
     }
    }else{
     echo "<result status=\"ERROR\"><![CDATA[".page::showText("FOLDER IS NOT WRITABLE")."]]></result>";
    }
   }else{
    echo "<result status=\"ERROR\"><![CDATA[".page::showText("PATH IS NOT DIRECTORY")."]]></result>";
   }
  }else{
   echo "<result status=\"ERROR\"><![CDATA[".page::showText("FOLDER NAME IS NOT VALID")."]]></result>";
  }

  break;
 case "delete":
  
  $filename = getvalue("id");
  $file = CONF_DOCUMENT_ROOT.$filename;
  
  if ((strlen($filename) > 0) and file_exists($file)) {
   if (is_file($file)) {
   	if (is_writable($file)) {
   		if (@unlink($file)) {
      echo "<result status=\"OK\"><![CDATA[".page::showText("FILE DELETED")."]]></result>";
   		}else{
      echo "<result status=\"ERROR\"><![CDATA[".page::showText("FILE CANNOT BE DELETED")."]]></result>";
   		}
   	}else{
     echo "<result status=\"ERROR\"><![CDATA[".page::showText("FILE IS NOT WRITABLE")."]]></result>";
   	}
   }elseif (is_dir($file)){
   	if (is_writable($file)) {
   		if (@rmdir($file)) {
      echo "<result status=\"OK\"><![CDATA[".page::showText("FOLDER DELETED")."]]></result>";
   		}else{
      echo "<result status=\"ERROR\"><![CDATA[".page::showText("FOLDER CANNOT BE DELETED")."]]></result>";
   		}
   	}else{
     echo "<result status=\"ERROR\"><![CDATA[".page::showText("FOLDER IS NOT WRITABLE")."]]></result>";
   	}
   }
  }else{
   echo "<result status=\"ERROR\"><![CDATA[".page::showText("TARGET NOT FOUND")."]]></result>";
  }
  
  break;
}
?>