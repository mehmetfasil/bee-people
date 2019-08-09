<?php
class WidgetPage{
    private $page_header;
    private $page_icon;
    private $page_desc;
    
    
    //page header
    public function Widget_Page_Header($page_header_text ="",$icon="document" ){
        echo "<div class='page_header_background'><h3><img src='objects/icons/16x16/".$icon.".png'/>".$page_header_text."</h3></div>";
    }
    
    public function Widget_Page_Loading($loading=true,$text= "Yükleniyor..."){
        if($loading==true)
            echo "<div align='center'><img src='objects/icons/loaders/3.gif'/>".$text."</div>";
        
    }
    
    
    
    //constructor
    function __construct(){
    }
    
}

?>