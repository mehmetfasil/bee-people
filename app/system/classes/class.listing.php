<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract Listing
 * @version 1.0
 */

class listing {
	public $titles = array(); //Başlıklar
	public $mainpage="index.php"; //Anasayfa
	public $url="index.php";
	public $formname="list";
	public $showordering=true;
	public $extralink=""; //Linkin sonuna eklenmesi için
	public $icons=array("id"=>"edit.png");
	private $listtype; //Liste türü
	private $lastcolumn; //Son kolon
	private $showpaging=true; //Sayfalama
	private $x=0;
	private $y=20;
	private $total=0;
	private $x_param="x";
	private $y_param="y";

	public function __construct ($listtype=1, $lastcolumn=true, $showpaging=true){
		$this->listtype   = $listtype;
		$this->lastcolumn = $lastcolumn;
		$this->showpaging = $showpaging;
	}
	
	public function paging ($x, $y, $total, $x_param="x", $y_param="y"){
		$this->x = $x;
		$this->y = $y;
		$this->total = $total;
		$this->x_param = $x_param;
		$this->y_param = $y_param;
	}

	public function showSearchField (){
		$html = "";
		
		//Link (x,y ve keyword'ü siliyoruz)
		$uri = $this->resolveuri($_SERVER["QUERY_STRING"], "keyword");
		$uri = $this->resolveuri($uri, $this->x_param);
		$uri = $this->resolveuri($uri, $this->y_param);

		//Sonuç
		$html.= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">\n";
		$html.= "  <tr>\n";
		$html.= "    <td align=\"right\"><label for=\"keyword\">".$this->hint(2).":</label> ";
		$html.= "      ".formElement("text", "keyword", "", getvalue("keyword"), "", "style=\"width:150px\" onfocus=\"this.select();\" onkeypress=\"if(event.keyCode == 13 || event.which == 13){ window.location.href='".$this->mainpage."?".$uri."&keyword=' + document.getElementById('keyword').value }\"");
		$html.= "      <input style=\"border:0px\" type=\"image\" border=\"0\" name=\"filter\" src=\"objects/sysicons/find.gif\" width=\"24\" height=\"24\" alt=\"".$this->hint(3)."\" hspace=\"3\" align=\"absmiddle\" onclick=\"window.location.href='".$this->mainpage."?".$uri."&keyword=' + document.getElementById('keyword').value\">\n";
		$html.= "    </td>\n";
		$html.= "  </tr>\n";
		$html.= "</table>\n";

		echo $html;
	}

	public function showData ($result, $orderby=1, $order="DESC"){
		//Aligns
		$aligns = array("c"=>"center","l"=>"left","r"=>"right");

		//Kolon toplamı
		$columntotal = count($this->titles);

		//Dönecek sonuç
		$html = "";
		
		if ($this->showpaging) {
		 $html.= $this->directings("top");
		}

		$html.= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">\n";
		$html.= "  <tr height=\"28\">\n";

		if($this->listtype == 1){
			$html.= "    <td width=\"4%\" class=\"gridTitle\" align=\"center\">\n";
			$html.= formElement("checkbox", "checkall", "1", "0", "", "onclick=\"checkAll(this, '".$this->formname."');\"");
			$html.= "    </td>\n";
		}else{
			$html.= "    <td width=\"6%\" class=\"gridTitle\" align=\"center\">".$this->hint(4)."</td>\n";
		}

		if($order == "ASC"){
			$order = "ASC";
			$order_image = "down.gif";
		}else{
			$order = "DESC";
			$order_image = "up.gif";
		}

		for ($i=0; $i < count($this->titles); $i++){

			//Temizlenecek olanlar -> orderby, order
			$resolved = $this->resolveuri($_SERVER["QUERY_STRING"], "orderby");
			$resolved = $this->resolveuri($resolved, "order");
   
			//Linki gelen veriye göre de düzeltelim
   $raw = explode("&", $this->extralink);
   foreach ($raw as $uri){
    $loc = strpos($uri, "=");
    $resolved = $this->resolveuri($resolved, substr($uri, 0, $loc));
   }
   
   $resolved = $resolved.$this->extralink;

			//Link sonucu
			$link = $this->mainpage."?".$resolved."&orderby=".($i+1)."&order=".(($order == "ASC") ? "DESC" : "ASC");

			//Sıralama resmi
			$image = ($orderby == ($i+1)) ? " <img src=\"objects/sysicons/".$order_image."\" border=\"0\" alt=\"\" align=\"absmiddle\"/>" : "";

			//Genişlik
			$width = !empty($this->titles[$i][1]) ? " width=\"".$this->titles[$i][1]."%\"" : "";

			//Konum
			$align = (!empty($this->titles[$i][2]) and isset($aligns[$this->titles[$i][2]])) ? " align=\"".$aligns[$this->titles[$i][2]]."\"" : "";

			//Başlıklar..
			if ($this->showordering) {
				$html.= "<td".$width.$align." class=\"gridTitle\" onmouseover=\"style.cursor='pointer'; title='".$this->hint(0)."'\" onclick=\"window.location.href='".$link."';\">".$this->titles[$i][0].$image."</td>\n";
			}else{
				$html.= "<td".$width.$align." class=\"gridTitle\">".$this->titles[$i][0]."</td>\n";
			}
		}

		//Son tarafta bir kolonluk boşluk
		if($this->lastcolumn){
			$html.= "      <td width=\"4%\" class=\"gridTitle\">&nbsp;</td>\n";
		}

		$html.= "  </tr>\n";

		//Satır sayısı
		$i=0;

		//X toplamdan büyükse
		$x = ($this->x  >= count($result)) ? 0 : $this->x;

		foreach ($result as $id=>$values){
			if($i >= $x){
				$html.= "<tr height=\"28\" id=\"".$this->formname.(($i-$x)+1)."\">\n";
				if($this->listtype == 1){
					$html.= "<td width=\"4%\" class=\"gridTitle\" align=\"center\">\n";
					$html.= formElement("checkbox", "deletebox[]", $id, "0", "", "onclick=\"checkthis(this, '".$this->formname.(($i-$x)+1)."'); checkIfAll(this, '".$this->formname."');\"", "deletebox".$i);
					$html.= "</td>\n";
				}else{
					$html.= "<td width=\"6%\" class=\"gridTitle\" align=\"center\">".($i+1)."</td>\n";
				}

				if ($this->listtype == 1) {
					$jsscript = " if(document.getElementById('deletebox".$i."').checked){ document.getElementById('deletebox".$i."').checked = false; }else{ document.getElementById('deletebox".$i."').checked=true; } checkIfAll(document.getElementById('deletebox".$i."'), document.".$this->formname.");";
				}else{
					$jsscript = "";
				}

				//Değerler..
				$t=1;
				foreach ($values as $value){
					//Konum
					$align = (!empty($this->titles[($t-1)][2]) and isset($aligns[$this->titles[($t-1)][2]])) ? " align=\"".$aligns[$this->titles[($t-1)][2]]."\"" : "";
					
					//Çıkış
					$html.= "<td height=\"28\" id=\"".$this->formname.(($i-$x)+1)."-".$t."\"".$align." class=\"gridRow\" onmouseover=\"listRow ('".$this->formname.(($i-$x)+1)."', 'over', this.className)\" onmouseout=\"listRow ('".$this->formname.(($i-$x)+1)."', 'out', this.className)\" onclick=\"listRow ('".$this->formname.(($i-$x)+1)."', 'select', this.className);".$jsscript."\">".(($value == "") ? "&nbsp;" : $value)."</td>\n";
					$t++;

					if ($t > $columntotal) {
						//Başlık sayasına göre işlemi kesiyoruz..
						break;
					}
				}

				if($this->lastcolumn){

					$html.= "<td nowrap align=\"center\" width=\"4%\" class=\"gridTitle\" onmouseover=\"listRow ('".$this->formname.(($i-$x)+1)."', 'over', document.getElementById('".$this->formname.(($i-$x)+1)."-1').className)\" onmouseout=\"listRow ('".$this->formname.(($i-$x)+1)."', 'out', document.getElementById('".$this->formname.(($i-$x)+1)."-1').className)\">\n";

					//İçerisindeki iconlar..
					if (is_array($this->icons)) {
						foreach ($this->icons as $var=>$icon){
							if (eregi("this.id", $this->url)) {
								$html.= "  <a href=\"".str_replace("this.id", $id, $this->url)."\"> ";
							}else{
								$html.= "  <a href=\"".$this->url."&".$var."=".$id."\"> ";
							}
							$html.= "<img src=\"objects/icons/16x16/".$icon."\" width=\"16\" height=\"16\" alt=\"".$this->hint(5)."\" border=\"0\" vspace=\"2\"></a>&nbsp;\n";
						}
					}else{
						$html.= "&nbsp;";
					}
					$html.= "</td>\n";
				}

				$html.= "</tr>\n";
				if ((($i-$x)+1) >= $this->y) {
					break;
				}
			}
			$i++;
		}
		$html.= "</table>\n";
		
		if ($this->showpaging) {
		 $html.= $this->directings("bottom");
		}

		//Yazdırıyoruz..
		echo $html;
	}

	public function showNoRecord($value=""){
		$html = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" class=\"messagebox\">\n";
		$html.= "  <tr>\n";
		$html.= "    <td align=\"center\">".(empty($value) ? $this->hint(1) : $value)."</td>\n";
		$html.= "  </tr>\n";
		$html.= "</table>\n";

		//Yazdırıyoruz..
		echo $html;
	}
  
 public  function directings ($type="top"){
	 //İlk, Önceki, Sonraki, Son ID ler
  $first_id = 0;
	 $back_id  = $this->x - $this->y;
  $next_id  = $this->x + $this->y;
	 $last_id  = $this->total - ((($this->total%$this->y) == 0) ? $this->y : ($this->total%$this->y));
	 
	 //Linki temizleyelim
		$resolved = $this->resolveuri($_SERVER["QUERY_STRING"], $this->x_param);
   
		//Linki gelen veriye göre de düzeltelim
  $raw = explode("&", $this->extralink);
  foreach ($raw as $uri){
   $loc = strpos($uri, "=");
   $resolved = $this->resolveuri($resolved, substr($uri, 0, $loc));
  }
  
  $resolved = $resolved.$this->extralink;
	 
	 //İlk kayıt görünümü
	 if($first_id == $this->x){
	  $show_first = "<span style=\"color:#CCCCCC\">".$this->hint(6)."</span>";
	 }else{
	  $show_first = "<a href=\"".$this->mainpage."?".$resolved."&".$this->x_param."=".$first_id."\" title=\"".$this->hint(6)."\">".$this->hint(6)."</a>\n";
	 }
	
	 //Önceki Kayıt Görünümü	
	 if($this->x > 0){
	  $show_back = "<a href=\"".$this->mainpage."?".$resolved."&".$this->x_param."=".$back_id."\" title=\"".$this->hint(7)."\">".$this->hint(7)."</a>\n";
	 }else{
	  $show_back = "<span style=\"color:#CCCCCC\">".$this->hint(7)."</span>";
	 }
	
	 //Sonraki Kayıt Görünümü
  if ($this->x < ($this->total - $this->y)){
	  $show_next = "<a href=\"".$this->mainpage."?".$resolved."&".$this->x_param."=".$next_id."\" title=\"".$this->hint(8)."\">".$this->hint(8)."</a>\n";
	 }else{
	  $show_next = "<span style=\"color:#CCCCCC\">".$this->hint(8)."</span>";
	 }
	
	 //Son Kayıt Görünümü
	 if(($this->x >= ($this->total - $this->y)) or ($this->y >= $this->total)){
	  $show_last = "<span style=\"color:#CCCCCC\">".$this->hint(9)."</span>";
	 }else{
	  $show_last = "<a href=\"".$this->mainpage."?".$resolved."&".$this->x_param."=".$last_id."\" title=\"".$this->hint(9)."\">".$this->hint(9)."</a>\n";
	 }
	
	 //Kaçta kaçı
	 $position = ($this->x + 1)."-".($this->x + $this->y);
		
	 //Y si temizlenmiş
		$resolved = $this->resolveuri($resolved, $this->y_param);
		
		//Y yönlendirmesi
		$y_direct1 = formElement("select", $this->y_param."1", array(10=>10, 20=>20, 30=>30, 50=>50), $this->y, "", "onchange=\"window.location.href='".$this->mainpage."?".$resolved."&".$this->y_param."=' + this.options[this.selectedIndex].value;\"");
		$y_direct2 = formElement("select", $this->y_param."2", array(10=>10, 20=>20, 30=>30, 50=>50), $this->y, "", "onchange=\"window.location.href='".$this->mainpage."?".$resolved."&".$this->y_param."=' + this.options[this.selectedIndex].value;\"");
		
		//Sayfalama
		$pages = array();
		
		for($i=0; $i < ceil($this->total / $this->y); $i++){		
	  $pages[$i*$this->y] = ($i+1);
		}
		
		//Y sayfalaması
		$y_paging = formElement("select", $this->y_param."paging", $pages, $this->x, "onchange=\"window.location.href='".$this->mainpage."?".$resolved."&y=".$this->y."&".$this->x_param."=' + this.options[this.selectedIndex].value;\"");

	 $html = "<table style=\"padding-top:5px; padding-bottom:5px\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">\n";
  $html.= "  <tr>\n";
  
  if ($type == "top") {
   $html.= "    <td width=\"33%\"><label for=\"".$this->y_param."1\">".$this->hint(10).":</label> ".$y_direct1."</td>\n";
   $html.= "    <td align=\"center\" nowrap>".$this->hint(11).": ".$this->total." | ".$position."</td>\n";
  }else{
   $html.= "    <td width=\"33%\"><label for=\"".$this->y_param."2\">".$this->hint(10).":</label> ".$y_direct2."</td>\n";
   $html.= "    <td align=\"center\" nowrap><label for=\"".$this->y_param."paging\">".$this->hint(12).":</label> ".$y_paging."</td>\n";  	
  }
  $html.= "    <td nowrap align=\"right\" width=\"33%\">".$show_first." | ".$show_back." | ".$show_next." | ".$show_last."</td>\n";
  $html.= "  </tr>\n";
  $html.= "</table>\n";
	 
  //Sonuç          
  return $html; 
 }

	public function resolveuri($uri, $variable="x"){
		//Bir tane array oluşturuyoruz (Buna yeni değerleri ekleyeceğiz)
		$result = array();

		//Sonra ayıklıyoruz
		$raw = explode("&", $uri);

		foreach($raw as $value){
			//Değerleri de explode ediyoruz
			$raw_uri = explode("=", $value);
			if($raw_uri[0] != $variable){
				array_push($result, $value);
			}
		}
		return implode("&", $result);
	}

	public function hint ($id){
		//Hintler
		$hint[0] = "Sırala";
		$hint[1] = "Kayıt bulunamadı!";
		$hint[2] = "Kriter";
		$hint[3] = "Ara";
		$hint[4] = "Sıra";
		$hint[5] = "İşlem";
		$hint[6] = "İlk";
		$hint[7] = "Önceki";
		$hint[8] = "Sonraki";
		$hint[9] = "Son";
		$hint[10] = "Gösterilen";
		$hint[11] = "Toplam";
		$hint[12] = "Sayfa";

		return $hint[$id];
	}
}