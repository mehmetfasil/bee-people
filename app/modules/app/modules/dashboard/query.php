<?
defined("PASS") or die("Dosya Yok!");
include (CONF_DOCUMENT_ROOT . "modules" . DS . "app" . DS . "includes.php");
switch(ACT){
    case "g_do_rqst":
    $user = $_SESSION['SYS_USER_ID'];
    $sql = "select d.id,CONCAT(e.emp_name,' ',e.emp_surname) as empname,dot.`name` from app_employee_dayoff as d 
    left join app_employee as e on e.id = d.emp_id 
    inner join app_firms_lookups as dot on dot.id = d.do_type where e.firm_id='".$_SESSION['SYS_USER_FIRM_ID']."'
    and d.is_approved=0 ";
    
    $s = new query($sql);
    if($s->numrows()>0){
        echo "<result status='OK'></result>";
        while($row=$s->fetchobject()){
            echo "<it i='".$row->id."' empname='".$row->empname."' name='".$row->name."'></it>";
        }
    }else{
        echo "<result status='NR'></result>";
    }
    break;
    
    case "g_izin_detay":
    $i = checkInput(getvalue("i"));
    if($i!="" || !is_numeric($i)){
        //isAllowed
        if(isAuthorizedForItemOnTable($i,"app_employee_dayoff")){
            $q = "select d.id,CONCAT(e.emp_name,' ',e.emp_surname) as empname,dot.`name`,
                DATEDIFF(end_date,start_date) as counted,
                DATE_FORMAT(d.start_date,'%d/%m/%Y') as start_date,
                DATE_FORMAT(d.end_date,'%d/%m/%Y') as end_date,d.notes from ".$table_app_employee_dayoff." as d 
                left join ".$table_app_employee." as e on e.id = d.emp_id 
                inner join ".$table_app_f_lookups." as dot on dot.id = d.do_type where d.id='".$i."'";
            $s = new query($q);
            if($s->numrows()>0){
                echo "<result status='OK'></result>";
                $row=$s->fetchobject();
                echo "<idty i='".$row->id."' emp='".$row->empname."' izin='".$row->name."' baslangic='".$row->start_date."'".
                " bitis='".$row->end_date."' aciklama='".$row->notes."' gun='".$row->counted."'></idty>";
            }
        }
    }
    break;
    
    case "s_izin_talep":
    
    /*
    [izinOnayId] => 14
    [type] => 1
    */
    
    //type = 2 red; 1 = onay
    $type = checkInput(getvalue("type"));
    $izinOnayId = checkInput(getvalue("izinOnayId"));
    
    if($type=="" || ($type!="1" && $type!="2")){
        echo "<result status='ERROR'>Lütfen değerleri kontrol ediniz!</result>";
        return;
    }
    
    if($izinOnayId==""){
      echo "<result status='ERROR'>Lütfen değerleri kontrol ediniz!</result>";
        return;  
    }
    
    if(isAuthorizedForItemOnTable($izinOnayId,"app_employee_dayoff")){
        $q = "UPDATE ".$table_app_employee_dayoff." SET is_approved='".$type."', approval_date=NOW(), approver_user_id='".$_SESSION['SYS_USER_ID']."' ".
        " WHERE id='".$izinOnayId."' ";
        $u = new  query($q);
        if($u->affectedrows()>0){
          echo "<result status='OK'>İşlem Başarıyla Gerçekleştirildi!</result>";
          return;  
        }else{
           echo "<result status='ERROR'>Kayıt esnasında hata oluştu!</result>";
          return;  
        }
    }else{
        echo "<result status='ERROR'>Yetkisiz İşlem!</result>";
        return;
    }
    
    break;
}
?>