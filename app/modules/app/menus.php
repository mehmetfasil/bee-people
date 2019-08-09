<?
defined("PASS") or die("Dosya Yok!");

function GetMenus($role){
    //anasayfa herkesde gelecek.
    $html = "";
    switch($role){
        case "1":
        case "2":
        //hesap yoneticisi veya yetki verdigi yonetici
        $html = "<li>".
                "<a id='tour_employee'>ÇALIŞANLAR <span class='fa fa-chevron-down'></span></a>".
				    "<ul class='nav child_menu'>".
						"<li>".
							"<a id='tour_add_new_employee' href='index.php?pid=".menuID("SITE_APP_EMPLOYEE")."'>ÇALIŞAN LİSTESİ</a>".
						"</li>".
						"<li>".
							"<a href='index.php?pid=".menuID("SITE_APP_EMPLOYEE")."&sid=calisan_ekle'>ÇALIŞAN EKLE</a>".
						"</li>".
					"</ul>".
				"</li>".
                "<li>".
					"<a>İNSAN KAYNAKLARI<span class='fa fa-chevron-down'></span></a>".
					"<ul class='nav child_menu'>".
						"<li>".
							"<a href='index.php?pid=".menuID("SITE_APP_CORP_PUANTAJ")."'>PUANTAJ</a>".
						"</li>".
                        "<li>".
							"<a href='index.php?pid=".menuID("SITE_APP_CORP_CALENDAR")."'>TAKVİM</a>".
						"</li>".
                        "<li>".
							"<a href='index.php?pid=".menuID("SITE_APP_CORP_BULK_EMPLOYEE")."'>TOPLU İŞLEM</a>".
						"</li>".
					"</ul>".
				"</li>".
                "<li>".
					"<a>MUHASEBE<span class='fa fa-chevron-down'></span></a>".
					"<ul class='nav child_menu'>".
						"<li>".
							"<a href='index.php?pid=".menuID("SITE_APP_ACCOUNT_BORDRO")."'>BORDRO</a>".
						"</li>".
					"</ul>".
				"</li>".
                "<li>".
    				"<a>RAPORLAR<span class='fa fa-chevron-down'></span></a>".
    				"<ul class='nav child_menu'>".
    					"<li>".
    						"<a href=''>RAPORLAR</a>".
    					"</li>".
    				"</ul>".
    			"</li>";
        echo $html;
        break;
        
        case "4":
        //IK personeli
        $html = "<li>".
                "<a id='tour_employee'><i class='fa fa-user'></i> Çalışanlar <span class='fa fa-chevron-down'></span></a>".
				    "<ul class='nav child_menu'>".
						"<li>".
							"<a id='tour_add_new_employee' href='index.php?pid=".menuID("SITE_APP_EMPLOYEE")."'>Çalışan Listesi</a>".
						"</li>".
						"<li>".
							"<a href='index.php?pid=".menuID("SITE_APP_EMPLOYEE")."&sid=calisan_ekle'>Çalışan Ekle</a>".
						"</li>".
					"</ul>".
				"</li>".
                "<li>".
					"<a><i class='fa fa-briefcase'></i> İnsan Kaynakları <span class='fa fa-chevron-down'></span></a>".
					"<ul class='nav child_menu'>".
						"<li>".
							"<a href='index.php?pid=".menuID("SITE_APP_CORP_PUANTAJ")."'>Puantaj</a>".
						"</li>".
                        "<li>".
							"<a href='index.php?pid=".menuID("SITE_APP_CORP_CALENDAR")."'>Takvim</a>".
						"</li>".
                        "<li>".
							"<a href='index.php?pid=".menuID("SITE_APP_CORP_BULK_EMPLOYEE")."'>Toplu İşlem</a>".
						"</li>".
					"</ul>".
				"</li>".
                "<li>".
    				"<a><i class='fa fa-bar-chart-o'></i> Raporlar <span class='fa fa-chevron-down'></span></a>".
    				"<ul class='nav child_menu'>".
    					"<li>".
    						"<a href=''>Raporlar</a>".
    					"</li>".
    				"</ul>".
    			"</li>";
        echo $html;
        break;
        
        case "8":
        $html = "<li>".
					"<a><i class='fa fa-bank'></i> Muhasebe <span class='fa fa-chevron-down'></span></a>".
					"<ul class='nav child_menu'>".
						"<li>".
							"<a href='index.php?pid=".menuID("SITE_APP_ACCOUNT_BORDRO")."'>Bordro</a>".
						"</li>".
					"</ul>".
				"</li>";
                
        echo $html;
        break;
    }
}

?>