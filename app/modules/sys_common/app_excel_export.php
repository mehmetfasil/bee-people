<?
/**
 *@param: cikti dosyasi turu:string, dosyaturu:string,params:array 
*/
defined("PASS") or die("Dosya Yok!");

if(isset($_SESSION['SYS_USER_FIRM_ID']) && $_SESSION['SYS_USER_FIRM_ID']>0){
    $cikti_turu = getvalue("cikti_turu"); //cikti ne formatta isteniyor. 
    $dosya_turu = getvalue("dosya_turu"); //gonderilen dosyan�n turu ne?
    $params = $_POST['params'];
    
    if($cikti_turu=="" || $cikti_turu==null)
    die("��kt� T�r� Se�ilmelidir");
    
    if($dosya_turu=="" || $dosya_turu==null)
    die("Dosya T�r� Se�ilmelidir");
    
    switch($dosya_turu){
        case "puantaj":
            print_r($params);
        break;
    }
}else{
    die("Dosya Yok!");
}
?>