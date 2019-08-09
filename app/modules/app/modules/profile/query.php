<?
defined("PASS") or die("Dosya Yok!");
include (CONF_DOCUMENT_ROOT . "modules" . DS . "app" . DS . "includes.php");
switch(ACT){
    case "s_izin":
    
    /**
    [izin_turu] => 1
    [izin_baslangic_tarihi] => 11/15/2016
    [izin_bitis_tarihi] => 11/13/2016
    [izin_aciklama] => asd
    */
    
    $get_emp_id = new query(" SELECT id FROM ".$table_app_employee." WHERE user_id='".$_SESSION['SYS_USER_ID']."' ");
    $row=$get_emp_id->fetchobject();
    $emp_id=$row->id;
    
    //controls
    $izin_turu = checkInput(getvalue("izin_turu"));
    $izin_baslangic_tarihi = checkInput(getvalue("izin_baslangic_tarihi"));
    $izin_bitis_tarihi = checkInput(getvalue("izin_bitis_tarihi"));
    $izin_aciklama = checkInput(getvalue("izin_aciklama"));
    if($izin_turu=="" || $izin_turu<=0 ||  !is_numeric($izin_turu)){
        echo "<result status='ERROR'>İzin türü seçilmelidir.</result>";
        return;
    }
    if($izin_baslangic_tarihi=="" || !isDate($izin_baslangic_tarihi)){
        echo "<result status='ERROR'>İzin başlangıç tarihi seçilmelidir.</result>";
        return;
    }
    if($izin_bitis_tarihi=="" || !isDate($izin_bitis_tarihi)){
        echo "<result status='ERROR'>İzin bitiş tarihi seçilmelidir.</result>";
        return;
    }
    //date logic
    $izin_baslangic_tarihi = convertToMysqlDateFormat($izin_baslangic_tarihi);
    $izin_bitis_tarihi = convertToMysqlDateFormat($izin_bitis_tarihi);
    if($izin_bitis_tarihi<=$izin_baslangic_tarihi){
        echo "<result status='ERROR'>İzin bitiş tarihi başlangıç tarihinden küçük olamaz.</result>";
        return;
    }
    $izin_baslangic_tarihi = finalizeDateForDb($izin_baslangic_tarihi);
    $izin_bitis_tarihi = finalizeDateForDb($izin_bitis_tarihi);
    
    //is there any other dayoff in that date?
    
    
    $sql = "INSERT INTO ".$table_app_employee_dayoff. " (id,emp_id,firm_id,do_type,start_date,start_time,end_date,end_time,approver_user_id,is_approved,notes,approval_date,is_active) VALUES (null,'".$emp_id."','".$_SESSION['SYS_USER_FIRM_ID']."','".$izin_turu."',".$izin_baslangic_tarihi.",'00:00',".$izin_bitis_tarihi.",'00:00',null,0,'".$izin_aciklama."',null,1)";
    $i = new query($sql);
    if($i->affectedrows()>0){
        echo "<result status='OK'>İzin kaydı başarıyla oluşturuldu.</result>";
        return;
    }else{
       echo "<result status='ERROR'>İzin kaydı esnasında bir hata oluştu.</result>";
        return; 
    }
    
    break;
    
    case "g_izin":
    
    
        $get_emp_id = new query(" SELECT id FROM ".$table_app_employee." WHERE user_id='".$_SESSION['SYS_USER_ID']."' ");
        $row=$get_emp_id->fetchobject();
        $emp_id=$row->id;
        
    
        $sql = "select DATE_FORMAT(d.start_date,'%d/%m/%Y') as start_date,DATE_FORMAT(d.end_date,'%d/%m/%Y') as end_date,dt.`name`,case d.is_approved when '0' then 'Onayda' when '1' then 'Onaylandı' when '2' then 'Reddedildi' end as approved from ".$table_app_employee_dayoff." as d
        left join ".$table_app_f_lookups." as dt on dt.id = d.do_type where emp_id='".$emp_id."' ";
        $get = new query($sql);
        if($get->numrows()>0){
            echo "<result status='OK'></result>";
            while($row=$get->fetchobject()){
               echo "<i s='".$row->start_date."' f='".$row->end_date."' n='".$row->name."' a='".$row->approved."'></i>"; 
            }
        }else{
          echo "<result status='NR'></result>";
          return;  
        }
        
    break;
    
    case "s_p":
    
    /*
     [profile_old_pass] => 1234
    [profile_new_pass] => 1234
    [profile_new_pass_again] => 1234
    */
    
    $old_pass = checkInput(getvalue("profile_old_pass"));
    $new_pass = checkInput(getvalue("profile_new_pass"));
    $new_pass_again = checkInput(getvalue("profile_new_pass_again"));
    
    if($old_pass=="" || $old_pass==null){
        echo "<result status='ERROR'>Eski Şifre Girilmelidir.</result>";
        return;  
    }
    
    if($new_pass=="" || $new_pass==null || $new_pass_again=="" || $new_pass_again==null){
        echo "<result status='ERROR'>Yeni Şifre Girilmelidir.</result>";
        return;  
    }
    
    if($new_pass!=$new_pass_again){
        echo "<result status='ERROR'>Girilen Şifreler Uyuşmuyor.</result>";
        return;
    }
    
    //old pass control
    $check = new query("SELECT pass FROM ".$table_sys_users. " WHERE pass='".md5($old_pass)."' and id='".$_SESSION['SYS_USER_ID']."'  ");
    if(!$check->numrows()>0){
        echo "<result status='ERROR'>Lütfen mevcut şifrenizi doğru olarak giriniz.</result>";
        return;
    }
    
    //update 
    $update = new query("UPDATE ".$table_sys_users." SET pass='".md5($new_pass)."' WHERE id='".$_SESSION['SYS_USER_ID']."' ");
    if($update->affectedrows()>0){
        echo "<result status='OK'>Şifre Başarıyla Güncellendi</result>";
        return;
    }else{
        echo "<result status='ERROR'>Şifre Güncellenirken Hata Oluştu!</result>";
        return;
    } 
    break;
}
?>