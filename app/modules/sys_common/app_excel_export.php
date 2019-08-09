<?
/**
 *@param: cikti dosyasi turu:string, dosyaturu:string,params:array 
*/
defined("PASS") or die("Dosya Yok!");

if(isset($_SESSION['SYS_USER_FIRM_ID']) && $_SESSION['SYS_USER_FIRM_ID']>0){
    $cikti_turu = getvalue("cikti_turu"); //cikti ne formatta isteniyor. 
    $dosya_turu = getvalue("dosya_turu"); //gonderilen dosyanýn turu ne?
    $params = $_POST['params'];
    
    if($cikti_turu=="" || $cikti_turu==null)
    die("Çýktý Türü Seçilmelidir");
    
    if($dosya_turu=="" || $dosya_turu==null)
    die("Dosya Türü Seçilmelidir");
    
    switch($dosya_turu){
        case "puantaj":
            print_r($params);
        break;
    }
}else{
    die("Dosya Yok!");
}
?>