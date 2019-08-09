<?
defined("PASS") or die("Dosya Yok!");
include(CONF_DOCUMENT_ROOT . "modules" . DS . "app" . DS . "includes.php");
switch (ACT) {
    
    case "dismiss_emp":
    
    /*
    [emp_dismiss_reason] => 9
    [emp_dismiss_desc] => asdasd
    [i] => 95529B7F-D846-4050-9651-059690FAA89A
    */
    
    $dismiss_reason = checkInput(getvalue("emp_dismiss_reason"));
    $dismiss_desc = checkInput(getvalue("emp_dismiss_desc"));
    $i = checkInput(getvalue("i"));
    
    if($i=="" || $i==null || !isAuthorized("",$i,"")){
            echo "<result status='ERROR'>Yetkisiz Erişim. Kayıt altına alındı.</result>";
            return;
    }
    
    if($dismiss_reason=="" || !is_numeric($dismiss_reason)){
        echo "<result status='ERROR'>İşden Çıkarma Sebebi Seçilmelidir.</result>";
        return;
    }
    
    $select = new query(" SELECT user_id FROM ".$table_app_employee." WHERE id='".$i."' and is_active='1' ");
    
    if(!$select->numrows()>0){
        echo "<result status='ERROR'>İşden Çıkarma Sebebi Seçilmelidir.</result>";
        return;
    }
    
    $row=$select->fetchobject();
    $UserId = $row->user_id;
    
    //oncelikle yetkilerini silelim.
    $delete = new query("DELETE FROM ".$table_sys_permissions. " WHERE oid='".$UserId."' ");
    if($delete->affectedrows()>0){
        $delete2 = new query("UPDATE ".$table_app_employee_roles." SET update_date=now(), update_by='".$_SESSION['SYS_USER_ID']."', desc='işden çıkarıldığı için iptal edildi', is_active='0' WHERE employee_user_id='".$UserId."' ");
        if($delete2->affectedrows()>0){
            $delete3 = "UPDATE ".$table_app_employee." SET is_active='0' WHERE user_id='".$UserId."' ";
            if($delete3->affectedrows>0){
                $delete4 = "UPDATE ".$table_app_user_detail." SET canceled_date=now(),canceled_by='".$_SESSION['SYS_USER_ID']."',cancel_reason='".$dismiss_reason."',is_active='0' WHERE user_id='".$UserId."' ";
                if($delete4->affectedrows()>0){
                    $delete5 = "UPDATE ".$table_sys_users." SET lock='1' WHERE id='".$UserId."' ";
                    if($delete5->affectedrows()>0){
                        $delete6 = "UPDATE ".$table_sys_objetcs." SET active='0' WHERE id='".$UserId."' ";
                        if($delete6->affectedrows()>0){
                            echo "<result status='ERROR'>Personel İşden Çıkarıldı.</result>";
                            return; 
                        }else{
                            echo "<result status='ERROR'>Personelin Sistem Bilgileri Güncellenirken Hata Oluştu.</result>";
                            return;  
                        }
                    }else{
                      echo "<result status='ERROR'>Personelin Kullanıcı Bilgileri Güncellenirken Hata Oluştu.</result>";
                      return;  
                    }
                }else{
                    echo "<result status='ERROR'>Personel Detay Bilgileri Güncellenirken Hata Oluştu.</result>";
                    return;
                }
            }else{
              echo "<result status='ERROR'>Personel Pasif Hale Getirilirken Hata Oluştu.</result>";
                return;  
            }
        }else{
            echo "<result status='ERROR'>Personelin Rolleri İptal Edilirken Hata Oluştu.</result>";
            return;    
        }
    }else{
        echo "<result status='ERROR'>Personelin Yetkileri İptal Edilirken Hata Oluştu.</result>";
        return;
    }
    
    /**
    tables to handle
    sys_users
    app_employee
    app_user_detail
    app_employee_roles
    sys_permissions
    */
    
    break;
    
    case "get_emp_ql":
        $i = checkInput(getvalue("i"));
        
        if($i=="" || $i==null || !isAuthorized("",$i,"")){
            echo "<result status='ERROR'>Yetkisiz Erişim. Kayıt altına alındı.</result>";
            return;
        }
        
        //datas
        //isim,soyadi,dogumtarihi,adres bilgiler, aylık maaş, resim,tckimlikno,görevi,telefon,mail
        $s = "select emp_name,emp_surname,emp_birthplace, emp_citizenid,emp_job,emp_phone_number_gsm, emp_email_work,emp_birthdate,".
        " emp_work_start_date,emp_work_end_date,emp_sex,emp_marital_status,emp_habit_fulladdress, is_active FROM ".$table_app_employee. " WHERE id='".$i."'";
        $q = new query($s);
        if($q->numrows()>0){
            echo "<result status='OK'></result>";
            $row = $q->fetchobject();
            //photo
            $query = "SELECT path FROM ".$table_app_employee_files." WHERE emp_id='".$i."' and app_file_id='1' and is_active='1' ";
            $get = new query($query);
            $photo = "objects/icons/no-photo.png";
            if($get->numrows()>0){
                $r  = $get->fetchobject();
                $photo = $r->path;
            }
            $brutmaas = "";
            $iban = "";
            $query = "SELECT gross_wage,iban FROM ".$table_app_employee_accountancy." WHERE emp_id='".$i."' ";
            $get = new query($query);
            if($get->numrows()>0){
                $r  = $get->fetchobject();
                $brutmaas = $r->gross_wage;
                $iban = $r->iban;
            }
            
            echo "<ql emp_name='".$row->emp_name."' emp_surname='".$row->emp_surname."' job='".$row->emp_job."' citizenid='".$row->emp_citizenid."'".
            " gsm='".$row->emp_phone_number_gsm."' email='".$row->emp_email_work."' birthdate='".$row->emp_birthdate."'".
            " birth_place='".$row->emp_birthplace."' gender='".($row->emp_sex==2 ? "Erkek" : "Kadın")."' work_start='".$row->emp_work_start_date."' work_end='".$row->emp_work_end_date."' address='".$row->emp_habit_fulladdress."'".
            " sistem_durumu='".($row->is_active==1 ? "Aktif" : "Pasif")."' marital='".($row->emp_marital_status==1 ? "Evli" : "Bekar")."' p='".$photo."' gross_wage='".$brutmaas."' iban='".$iban."'></ql>";
        }else{
          echo "<result status='NR'>Kayıt Bulunamadı</result>";
          return;  
        }
        
        
    break;
    
    case "deEdu":
    $i = checkInput(getvalue("i"));
    if($i=="" || $i==null){
        echo "<result status='ERROR'>Hata Oluştu. Kayıt altına alındı.</result>";
        return;
    }
    
    $sql_delete_app_edu = " UPDATE ".$table_app_employee_education. " SET is_active=0 WHERE id='".$i."'";
    $delete_app_edu = new query($sql_delete_app_edu);
    if($delete_app_edu->affectedrows()>0){
        echo "<result status='OK'>Kayıt Başarıyla Kaldırıldı.</result>";
        return;
    }else{
        echo "<result status='ERROR'>Hata Oluştu.</result>";
        return;
    }
    
    break;
    
    case "ged":
    
    $i = checkInput(getvalue("i"));
    if($i=="" || $i==null){
        echo "<result status='ERROR'>Eğitim bilgileri alınırken hata oluştu.</result>";
        return;
    }
    if(!isAuthorized("",$i,"","")){
        echo "<result status='ERROR'>Yetkisiz işlem. Kayıt altına alındı.</result>";
        return;
    }
    
    //get
    $sql_select_emp_edu = "SELECT ed.id,case ed.education_type when 1 THEN 'Üniversite' WHEN 2 THEN 'Lise' WHEN 3 THEN 'İlk/Orta Okul' END
 as education_type,ed.school_name,l.isim as school_city FROM ".$table_app_employee_education." as ed
left join ".$table_l_county." as l on ed.school_city = l.id
"." WHERE emp_id='".$i."' and ed.is_active=1 ";
    $select_emp_edu = new query($sql_select_emp_edu);
    if($select_emp_edu->numrows()>0){
        echo "<result status='OK'></result>";
        while($row=$select_emp_edu->fetchobject()){
            echo "<e id='".$row->id."' etype='".$row->education_type."' school='".$row->school_name."' city='".$row->school_city."'></e>";
        }
    }else{
        echo "<result status='NR'></result>";
    }
    
    break;
    
    
    case "gexp":
        $i = checkInput(getvalue("i"));
        if($i=="" || $i==null){
            echo "<result status='ERROR'>İş Deneyimi bilgileri alınırken hata oluştu.</result>";
            return;
        }
        if(!isAuthorized("",$i,"","")){
            echo "<result status='ERROR'>Yetkisiz işlem. Kayıt altına alındı.</result>";
            return;
        }
        //get
        $sql_get = "SELECT id,exp_firm_name,exp_emp_position,TIMESTAMPDIFF(YEAR
       , exp_start_date
       , exp_end_date
       ) AS yil,
       TIMESTAMPDIFF(MONTH
       , exp_start_date
           + INTERVAL TIMESTAMPDIFF(YEAR,exp_start_date, exp_end_date) YEAR
       , exp_end_date
       ) AS ay,
       TIMESTAMPDIFF(DAY
       , exp_start_date
           + INTERVAL TIMESTAMPDIFF(MONTH, exp_start_date, exp_end_date) MONTH
       , exp_end_date
       ) AS gun FROM ".$table_app_employee_experience." WHERE emp_id='".$i."'";
       $get = new query($sql_get);
       
       if($get->numrows()>0){
        echo "<result status='OK'></result>";
        while($row=$get->fetchobject()){
            echo "<ex id='".$row->id."' fname='".$row->exp_firm_name."' expo='".$row->exp_emp_position."' yil='".$row->yil." Yıl' ay='".$row->ay." Ay' gun='".$row->gun." Gün'></ex>";    
        }
       }else{
        echo "<result status='NR'></result>";
        return;
       }
        
        
        
    break;
    
    case "save_exp":
    
    /**
    [exp_rid] => 
    [deneyim_firma_adi] => GOOGLE
    [deneyim_firma_ili] => 6
    [deneyim_firma_ilcesi] => 63
    [deneyim_baslangic] => 02/12/2016
    [deneyim_bitis] => 02/12/2016
    [deneyim_pozisyon] => MAPPER
    [deneyim_ayrilma_nedeni] => WORK
    [i] => 55188D23-5B41-4383-A355-B46DCCC8AB82
    */
    
    $i = checkInput(getvalue("i"));
    if($i=="" || $i==null){
        echo "<result status='ERROR'>İş Deneyimi bilgileri alınırken hata oluştu.</result>";
        return;
    }
    
    if(!isAuthorized("",$i,"","")){
        echo "<result status='ERROR'>Yetkisiz işlem. Kayıt altına alındı.</result>";
        return;
    }
    
    $deneyim_firma_adi = checkInput(getvalue("deneyim_firma_adi"));
    $deneyim_firma_ili = checkInput(getvalue("deneyim_firma_ili"));
    $deneyim_firma_ilcesi = checkInput(getvalue("deneyim_firma_ilcesi"));
    $deneyim_baslangic = checkInput(getvalue("deneyim_baslangic"));
    $deneyim_bitis = checkInput(getvalue("deneyim_bitis"));
    $deneyim_pozisyon = checkInput(getvalue("deneyim_pozisyon"));
    $deneyim_ayrilma_nedeni = checkInput(getvalue("deneyim_ayrilma_nedeni"));
    $exp_rid = checkInput(getvalue("exp_rid"));
    
    //controls
    if($deneyim_firma_adi=="" || $deneyim_firma_adi==null){
        echo "<result status='ERROR'>Firma adı boş olamaz</result>";
        return;
    }
    if($deneyim_firma_ili=="" || $deneyim_firma_ili==null || !is_numeric($deneyim_firma_ili)){
        echo "<result status='ERROR'>Firma ili boş olamaz</result>";
        return; 
    }
    if($deneyim_firma_ilcesi=="" || $deneyim_firma_ilcesi==null || !is_numeric($deneyim_firma_ilcesi)){
        echo "<result status='ERROR'>Firma ilçesi boş olamaz</result>";
        return; 
    }
    if($deneyim_baslangic=="" || $deneyim_baslangic==null || !isDate($deneyim_baslangic)){
        echo "<result status='ERROR'>Başlangıç tarihi boş olamaz</result>";
        return;
    }
    if($deneyim_bitis=="" || $deneyim_bitis==null || !isDate($deneyim_bitis)){
        echo "<result status='ERROR'>Bitiş tarihi boş olamaz</result>";
        return;
    }
    if($deneyim_pozisyon=="" || $deneyim_pozisyon==null){
        echo "<result status='ERROR'>Pozisyon boş olamaz</result>";
        return;
    }
    if($deneyim_ayrilma_nedeni=="" ||$deneyim_ayrilma_nedeni==null){
        echo "<result status='ERROR'>Ayrılma nedeni boş olamaz</result>";
        return;
    }
    
    //fixdate
    $deneyim_baslangic = convertToMysqlDateFormat($deneyim_baslangic);
    $deneyim_bitis = convertToMysqlDateFormat($deneyim_bitis);
    if($deneyim_baslangic>$deneyim_bitis){
        echo "<result status='ERROR'>Deneyim Başlangıç Tarihi Bitiş tarihinden küçük olmalıdır.</result>";
        return;
    }
    $deneyim_baslangic = finalizeDateForDb($deneyim_baslangic);
    $deneyim_bitis = finalizeDateForDb($deneyim_bitis);
    
    
    
    if($exp_rid!=""){
        //update
    }else{
        //insert
        $sql_insert_exp = "INSERT INTO ".$table_app_employee_experience. " (id,emp_id,exp_firm_name,exp_firm_county,exp_firm_town,exp_start_date,exp_end_date,exp_emp_position,exp_leave_reason,insert_date,inserted_by,is_active)".
        " VALUES(null,'".$i."','".$deneyim_firma_adi."','".$deneyim_firma_ili."','".$deneyim_firma_ilcesi."',".$deneyim_baslangic.",".$deneyim_bitis.",'".$deneyim_pozisyon."','".$deneyim_ayrilma_nedeni."',NOW(),'".$_SESSION['SYS_USER_ID']."',1) ";
        $insert = new query($sql_insert_exp);
        if($insert->affectedrows()>0){
            echo "<result status='OK'>Deneyim başarıyla eklendi.</result>";
            return;
        }else{
            echo "<result status='ERROR'>Kayıt anında hata oluştu.</result>";
            return;
        }
    }
    
    
    
    
    break;
    
    
    case "gref":
        $i = checkInput(getvalue("i"));
        if($i=="" || $i==null){
            echo "<result status='ERROR'>Referans bilgileri alınırken hata oluştu.</result>";
            return;
        }
        if(!isAuthorized("",$i,"","")){
            echo "<result status='ERROR'>Yetkisiz işlem. Kayıt altına alındı.</result>";
            return;
        }
        $sql_select_ref = " SELECT id,emp_ref_fullname,emp_ref_working_place,emp_ref_phone FROM ".$table_app_employee_reference." WHERE emp_id='".$i."' ";
        $get = new query($sql_select_ref);
        if($get->numrows()>0){
            echo "<result status='OK'></result>";
            while($row=$get->fetchobject()){
                echo "<r id='".$row->id."' fname='".$row->emp_ref_fullname."' wplace='".$row->emp_ref_working_place."' rphone='".$row->emp_ref_phone."'></r>";
            }
        }else{
            echo "<result status='NR'></result>";
        }
    break;
    
    
    case "save_reference":
    
    /**
    [referans_adisoyadi] => MEHMET FASIL
    [referans_kurumadi] => MGA
    [referans_gorevi] => MÜHENDİS
    [referans_adresi] => ANKARA
    [reference_rid]=>12
    [referans_telefon] => (555) 666-7788
    [i] => 55188D23-5B41-4383-A355-B46DCCC8AB82
     * */
    
    //sets
    $referans_adisoyadi = checkInput(getvalue("referans_adisoyadi"));
    $referans_kurumadi = checkInput(getvalue("referans_kurumadi"));
    $referans_gorevi = checkInput(getvalue("referans_gorevi"));
    $referans_adresi = checkInput(getvalue("referans_adresi"));
    $referans_telefon = checkInput(getvalue("referans_telefon"));
    $emp_id = checkInput(getvalue("i"));
    $reference_rid = checkInput(getvalue("reference_rid"));
    
    //controls
    if($emp_id=="" || $emp_id==null || !isAuthorized("",$emp_id,"","")){
        echo "<result status='ERROR'>Yetkisiz işlem. Kayıt altına alındı.</result>";
        return;
    }
    //null controls
    if($referans_adisoyadi=="" || $referans_adisoyadi==null){
        echo "<result status='ERROR'>Referans Adı Soyadı Boş Olamaz.</result>";
        return;
    }
    if($referans_kurumadi=="" ||$referans_kurumadi==null){
        echo "<result status='ERROR'>Referans Kurum Adı Boş Olamaz.</result>";
        return;
    }
    if($referans_gorevi=="" ||$referans_gorevi==null){
        echo "<result status='ERROR'>Referans Kişisi Görevi Boş Olamaz.</result>";
        return;
    }
    if($referans_adresi=="" ||$referans_adresi==null){
        echo "<result status='ERROR'>Referans Adresi Boş Olamaz.</result>";
        return;
    }
    if($referans_telefon=="" ||$referans_telefon==null){
        echo "<result status='ERROR'>Referans Telefonu Boş Olamaz.</result>";
        return;
    }
    
    $referans_telefon = trimMaskedDataForPhone($referans_telefon);
    if (strlen($referans_telefon) != 10 || !is_numeric($referans_telefon)) {
        echo "<result status='REGEX'>Telefon Numarası Formatı Hatalıdır.</result>";
        return;
    }
    
    if($reference_rid!=""){
        //udate
    }else{
        //insert
        $sql_insert_ref = "INSERT INTO ".$table_app_employee_reference." (id,emp_id,emp_ref_fullname,emp_ref_title,emp_ref_working_place,emp_ref_address,emp_ref_phone,insert_date,inserted_by,is_active)".
        " VALUES (null,'".$emp_id."','".$referans_adisoyadi."','".$referans_gorevi."','".$referans_kurumadi."','".$referans_adresi."','".$referans_telefon."',NOW(),'".$_SESSION['SYS_USER_ID']."',1)";
        $insert = new query($sql_insert_ref);
        
        if($insert->affectedrows()>0){
            echo "<result status='OK'>Referans Kaydı Başarıyla Eklendi.</result>";
            return;
        }else{
            echo "<result status='ERROR'>Hata Oluştu.</result>";
            return;
        }        
    }
    
    
        
    break;
    
    case "save_education":
    
    /**
    
    [egitim_turu] => 1
    [egitim_baslangic] => 12/07/2014
    [egitim_bitis] => 12/05/2017
    [egitim_durumu] => 1
    [egitim_okul_adi] => AHMET YESEVİ ÜNİVERSİTESİ
    [egitim_okul_ulke] => 1
    [egitim_okul_il] => 6
    [egitim_bolum_adi] => BİLGİSAYAR MÜHENDİSLİĞİ
    [egitim_mezuniyet_derecesi] => 83
    
    */
    $egitim_id = checkInput(getvalue("egitim_id")); //employee id
    $egitim_rid = checkInput(getvalue("egitim_rid")); // egitim record id
    
    $egitim_turu = checkInput(getvalue("egitim_turu"));
    $egitim_baslangic = checkInput(getvalue("egitim_baslangic"));
    $egitim_bitis = checkInput(getvalue("egitim_bitis"));
    $egitim_durumu = checkInput(getvalue("egitim_durumu"));
    $egitim_okul_adi = checkInput(getvalue("egitim_okul_adi"));
    $egitim_okul_ulke = checkInput(getvalue("egitim_okul_ulke"));
    $egitim_okul_il = checkInput(getvalue("egitim_okul_il"));
    $egitim_bolum_adi = checkInput(getvalue("egitim_bolum_adi"));
    $egitim_mezuniyet_derecesi = checkInput(getvalue("egitim_mezuniyet_derecesi"));
    
    //null controls
    if($egitim_id=="" || $egitim_id==null){
        echo "<result status='ERROR'>Bilinmeyen Hata. İşlem kayıt altına alındı.</result>";
        return;
    }
    
    
    if($egitim_turu=="" || $egitim_turu==null | !is_numeric($egitim_turu) || $egitim_turu<=0){
        echo "<result status='ERROR'>Eğitim türü seçilmelidir.</result>";
        return;
    }
    if($egitim_baslangic=="" || $egitim_baslangic==null || !isDate($egitim_baslangic)){
        echo "<result status='ERROR'>Eğitim başlangıcı girilmelidir.</result>";
        return;
    }
    if($egitim_bitis=="" || $egitim_bitis==null || !isDate($egitim_bitis)){
        echo "<result status='ERROR'>Eğitim bitişi girilmelidir.</result>";
        return;
    }
    if($egitim_durumu=="" || $egitim_durumu==null | !is_numeric($egitim_durumu) || $egitim_durumu<=0){
        echo "<result status='ERROR'>Eğitim durumu seçilmelidir.</result>";
        return;
    }
    
    if($egitim_okul_adi=="" || $egitim_okul_adi==null){
        echo "<result status='ERROR'>Okul adı girilmelidir.</result>";
        return;
    }
    
    if($egitim_okul_ulke=="" || $egitim_okul_ulke==null | !is_numeric($egitim_okul_ulke) || $egitim_okul_ulke<=0){
        echo "<result status='ERROR'>Eğitim durumu seçilmelidir.</result>";
        return;
    }
    
    if($egitim_okul_il=="" || $egitim_okul_il==null | !is_numeric($egitim_okul_il) || $egitim_okul_il<=0){
        echo "<result status='ERROR'>İl seçilmelidir.</result>";
        return;
    }
    
    if($egitim_turu==1 && ($egitim_bolum_adi=="" || $egitim_bolum_adi==null)){
        echo "<result status='ERROR'>Bölüm adı girilmelidir.</result>";
        return;
    }
    
    if($egitim_mezuniyet_derecesi=="" || $egitim_mezuniyet_derecesi==null){
        echo "<result status='ERROR'>Mezuniyet Derecesi girilmelidir.</result>";
        return;
    }
    
    //isAuth
    
    if(!isAuthorized("",$egitim_id,"")){
         echo "<result status='ERROR'>Yetkisiz işlem. kayıt altına alındı.</result>";
        return;
    }
    
    
    $egitim_baslangic =  finalizeDateForDb(convertToMysqlDateFormat($egitim_baslangic));
    $egitim_bitis = finalizeDateForDb(convertToMysqlDateFormat($egitim_bitis));
    //save
    if($egitim_rid!="" && len($egitim_rid)>0){
        //update
    }else{
        //insert
        $sql_insert_emp_education = "INSERT INTO ".$table_app_employee_education." (id,emp_id,education_type,start_date,end_date,status,school_name,school_state,school_city,faculty_department,notes,insert_date,inserted_by,is_active)".
        " VALUES(null,'".$egitim_id."','".$egitim_turu."',".$egitim_baslangic.",".$egitim_bitis.",'".$egitim_durumu."','".$egitim_okul_adi."','".$egitim_okul_ulke."','".$egitim_okul_il."','".$egitim_bolum_adi."','".$egitim_mezuniyet_derecesi."',NOW(),'".$_SESSION['SYS_USER_ID']."',1) ";
        
        $insert_emp_edu = new query($sql_insert_emp_education);
        if($insert_emp_edu->affectedrows()>0){
            echo "<result status='OK'>Kayıt Başarıyla Eklendi.</result>";
            return;
        }else{
            echo "<result status='ERROR'>Kayıt anında hata oluştu.</result>";
            return;
        }
    }
    
        
    break;
    
    case "gf":
    $i = trim(getvalue("i"));
        if($i=="" || $i==null){
            echo "<result status='ERROR'>Personel Dosyalarına Yetkisiz Erişim</result>";
            return;
        }
        if(!isAuthorized("",$i,"")){
            echo "<result status='ERROR'>Personel Dosyalarına Yetkisiz Erişim</result>";
            return;
        }
        
        $sql_emp_files = "select id,filename,path from ".$table_app_employee_files." where emp_id='".$i."' and app_file_id!=1 and is_active=1";
        $select_emp_files = new query($sql_emp_files);
        if($select_emp_files->numrows()>0){
            echo "<result status='OK'></result>";
            while($row = $select_emp_files->fetchobject()){
                echo "<f i='".$row->id."' n='".$row->filename."' p='".$row->path."'></f>";
            }
        }else{
            echo "<result status='NR'></result>";
        }
    break;
    
    case "gem":
    
    $id = checkInput(getvalue("id"));
    $sql_select_app_employee = "SELECT emp_name,user_id FROM ".$table_app_employee." WHERE id='".$id."' AND firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' ";
    $select_app_employee = new query($sql_select_app_employee);
    if($select_app_employee->numrows()>0){
        $sql_select_app_employee = " SELECT * FROM ".$table_app_employee." WHERE id='".$id."'";
        $select_app_employee = new query($sql_select_app_employee);
        $row = $select_app_employee->fetchobject();
        echo "<result status='OK'></result>";
        /*
        //fix for date
        //YYYY-mm-dd => dd/mm/YYYY
        */
        
        $get_role = "SELECT employee_role_id FROM ".$table_app_employee_roles." WHERE employee_user_id='".$row->user_id."' ";
        $get = new query($get_role);
        $r = $get->fetchobject();
        $role = $r->employee_role_id;
        $photo = "";
        
        $get_photo = "SELECT path FROM ".$table_app_employee_files." WHERE emp_id='".$id."' AND app_file_id='1' and is_active=1 ";
        $get = new query($get_photo);
        if($get->numrows()>0){
            $p = $get->fetchobject();
            $photo = $p->path;
        }
        
        echo "<e r='".$role."' p='".$photo."' emp_job='".$row->emp_job."' emp_name='".$row->emp_name."' emp_surname='".$row->emp_surname."' 
        emp_citizenid='".$row->emp_citizenid."' emp_phone_number_gsm='".$row->emp_phone_number_gsm."' ".
        " emp_phone_number_work='".$row->emp_phone_number_work."' emp_email_work='".$row->emp_email_work."'".
        " b_county='".$row->emp_birth_county."' b_town='".$row->emp_birth_town."' b_date='".(($row->emp_birthdate!=null || $row->emp_birthdate!="") ? date("d-m-Y", strtotime($row->emp_birthdate)) : null)."'".
        " e_b_place='".$row->emp_birthplace."' e_state='".$row->emp_state_origin."' e_w_start_date='".(($row->emp_work_start_date!=null || $row->emp_work_start_date!="") ? date("d-m-Y", strtotime($row->emp_work_start_date)) : null)."'".
        " e_nationality='".$row->emp_nationality."' e_h_county='".$row->emp_habit_county."' e_h_town='".$row->emp_habit_town."'".
        " e_address='".$row->emp_habit_fulladdress."' e_sex='".$row->emp_sex."' e_mil_o='".$row->emp_mil_obligation."'".
        " e_mil_sus_date='".(($row->emp_mil_suspension_date!=null || $row->emp_mil_suspension_date!="") ? date("d-m-Y", strtotime($row->emp_mil_suspension_date)) : null)."' e_mi_sus_reason='".$row->emp_mil_suspension_reason."'".
        " e_mil_end_date='".(($row->emp_mil_end_date!=null || $row->emp_mil_end_date!="") ? date("d-m-Y", strtotime($row->emp_mil_end_date)) : null)."' e_marital='".$row->emp_marital_status."' e_child='".$row->emp_child_count."'".
        " e_d_licence='".$row->emp_driving_licence_status."' e_d_l_type='".$row->emp_driving_licence_type."' e_d_l_year='".$row->emp_driving_licence_year."'".
        " e_spouse_w='".$row->emp_spouse_work_state."' e_spouse_w_p='".$row->emp_spouse_workplace."' e_spouse_w_pos='".$row->emp_spouse_work_position."'".
        " e_mother_n='".$row->emp_mother_name."' e_father_n='".$row->emp_father_name."' e_father_j='".$row->emp_father_job."'".
        " e_mother_j='".$row->emp_mother_job."' i='".$row->id."'></e>";
        
        $sql_select_app_emp_ac = "SELECT net_salary, food_cost, road_fee, gross_wage, account_number, bank_id, branch_code,iban FROM ".$table_app_employee_accountancy. " WHERE emp_id='".$id."'";
        $select_ap_emp_ac = new query($sql_select_app_emp_ac);
        if($select_ap_emp_ac->numrows()>0){
            $row=$select_ap_emp_ac->fetchobject();
            echo "<a n_s='".$row->net_salary."' f_c='".$row->food_cost."' r_f='".$row->road_fee."' g_w='".$row->gross_wage."'".
            " a_n='".$row->account_number."' b_c='".$row->branch_code."' iban='".$row->iban."'></a>";
        }
        
    }else{
        echo "<result status='ERROR'>Bu kayıtla ilgili işlem yapılamaz. İşlem kayıt altına alındı.</result>";
    }
    
    
    break;
    
    case "get_employee_list":
        //get employee list
        $sql_select_app_employee =
            "select id,emp_name,emp_surname,emp_job,emp_citizenid,emp_phone_number_gsm,emp_birthdate from " .
            $table_app_employee . " WHERE firm_id='" . $_SESSION['SYS_USER_FIRM_ID'] . "'";
        $select_app_employee = new query($sql_select_app_employee);
        if ($select_app_employee->numrows() > 0) {
            
            //record
            echo "<result status='OK'>";
            while ($row = $select_app_employee->fetchobject()) {
                //photo 
                $p = "";
                $get_photo = "SELECT path FROM ".$table_app_employee_files." WHERE emp_id='".$row->id."' AND app_file_id='1' and is_active=1";
                $get = new query($get_photo);
                if($get->numrows()>0){
                    $pi = $get->fetchobject();
                    $p = $pi->path;
                }else{
                    $p = "objects/icons/no-photo.png";
                }
                echo "<emp id='" . $row->id . "' p='".$p."' name='" . $row->emp_name . "' surname='" . $row->
                    emp_surname . "' position='" . $row->emp_job . "'" . " tc='" . $row->
                    emp_citizenid . "' phone='" . $row->emp_phone_number_gsm . "' birthdate='" . $row->
                    emp_birthdate . "'></emp>";
            }
        } else {
            //norecord
            echo "<result status='NR'>";
        }
        echo "</result>";
        break;

    case "save_employee":
    
        //params

        /**
         * "id=0&yetki_turu=4&tckimlikno=33028933258&isim=&soyisim=&dogum_tarihi=&dogum_yeri=
         * &telefon=&gsm_number=&eposta=&ikamet_ulke=&ikamet_il=1&ikamet_ilce=-1&adres=&uyruk=
         * &cinsiyeti=1&askerlik_durumlari=1&muaf_neden=&tecil_tarihi=&terhis_tarihi=&medeni_durum=1&
         * cocuk_sayisi=&es_calisma_durumu=1&es_isyeri_adi=&es_gorevi=&anne_adi=&anne_meslegi=
         * &baba_adi=&baba_meslegi=&personelin_gorevi=&ise_baslama_tarihi=&net_maas=&yemek_ucreti=
         * &yol_ucreti=&brut_ucret=&hesap_no=&sube_kodu=&iban=&ehliyet=1&ehliyet_sinifi=1&ehliyet_yili="
         */
         
        //controls
        //sets
        $get_yetki_turu = checkInput(getvalue("yetki_turu"));
        $get_tckimlikno = checkInput(getvalue("tckimlikno"));
        $get_isim = checkInput(getvalue("isim"));
        $get_soyisim = checkInput(getvalue("soyisim"));
        $get_dogum_tarihi = checkInput(getvalue("dogum_tarihi"));
        $get_dogum_yeri = checkInput(getvalue("dogum_yeri"));
        $get_telefon = checkInput(getvalue("telefon"));
        $get_gsm_number = checkInput(getvalue("gsm_number"));
        $get_eposta = checkInput(getvalue("eposta"));
        $get_ikamet_ulke = checkInput(getvalue("ikamet_ulke"));
        $get_ikamet_il = checkInput(getvalue("ikamet_il"));
        $get_ikamet_ilce = checkInput(getvalue("ikamet_ilce"));
        $get_adres = checkInput(getvalue("adres"));
        $get_uyruk = checkInput(getvalue("uyruk"));
        $get_cinsiyeti = checkInput(getvalue("cinsiyeti"));
        $get_askerlik_durumlari = checkInput(getvalue("askerlik_durumlari"));
        $get_muaf_neden = checkInput(getvalue("muaf_neden"));
        $get_tecil_tarihi = checkInput(getvalue("tecil_tarihi"));
        $get_terhis_tarihi = checkInput(getvalue("terhis_tarihi"));
        $get_medeni_durum = checkInput(getvalue("medeni_durum"));
        $get_cocuk_sayisi = checkInput(getvalue("cocuk_sayisi"));
        $get_es_calisma_durumu = checkInput(getvalue("es_calisma_durumu"));
        $get_es_isyeri_adi = checkInput(getvalue("es_isyeri_adi"));
        $get_es_gorevi = checkInput(getvalue("es_gorevi"));
        $get_anne_adi = checkInput(getvalue("anne_adi"));
        $get_anne_meslegi = checkInput(getvalue("anne_meslegi"));
        $get_baba_adi = checkInput(getvalue("baba_adi"));
        $get_baba_meslegi = checkInput(getvalue("baba_meslegi"));
        $get_personelin_gorevi = checkInput(getvalue("personelin_gorevi"));
        $get_ise_baslama_tarihi = checkInput(getvalue("ise_baslama_tarihi"));
        $get_net_maas = checkInput(getvalue("net_maas"));
        $get_yemek_ucreti = checkInput(getvalue("yemek_ucreti"));
        $get_yol_ucreti = checkInput(getvalue("yol_ucreti"));
        $get_brut_ucret = checkInput(getvalue("brut_ucret"));
        $get_hesap_no = checkInput(getvalue("hesap_no"));
        $get_sube_kodu = checkInput(getvalue("sube_kodu"));
        $get_iban = checkInput(getvalue("iban"));
        $get_ehliyet = checkInput(getvalue("ehliyet"));
        $get_ehliyet_sinifi = checkInput(getvalue("ehliyet_sinifi"));
        $get_ehliyet_yili = checkInput(getvalue("ehliyet_yili"));

        if (!is_numeric($get_yetki_turu) || $get_yetki_turu < 0 || $get_yetki_turu > 8 ||
            $get_yetki_turu == 3) {
            echo "<result status='REGEX'>Yetki Türü Doğru Seçilmelidir</result>";
            return;
        }
        //tckimlik check
        if (!isTcKimlik($get_tckimlikno)) {
            echo "<result status='REGEX'>Tc Kimlik Numarası Hatalıdır.</result>";
            return;
        }
        //dogumtarihi check
        if ($get_dogum_tarihi != "" && !isDate($get_dogum_tarihi)) {
            echo "<result status='REGEX'>Doğum Tarihi Formatı Hatalıdır.</result>";
            return;
        }
        //fix date
        $get_dogum_tarihi = !empty($get_dogum_tarihi) ? $get_dogum_tarihi : "NULL";
        if ($get_dogum_tarihi != "") {
            $get_dogum_tarihi = convertToMysqlDateFormat($get_dogum_tarihi);
        }

        //phone number and gsm number trims
        if ($get_telefon != "") {
            $get_telefon = trimMaskedDataForPhone($get_telefon);
            if (strlen($get_telefon) != 10 || !is_numeric($get_telefon)) {
                echo "<result status='REGEX'>Telefon Numarası Formatı Hatalıdır.</result>";
                return;
            }
        }
        if ($get_gsm_number != "") {
            $get_gsm_number = trimMaskedDataForPhone($get_gsm_number);
            if (strlen($get_gsm_number) != 10 || !is_numeric($get_gsm_number)) {
                echo "<result status='REGEX'>GSM Numarası Formatı Hatalıdır.</result>";
                return;
            }
        }
        //check email
        if ($get_eposta != "") {
            $get_eposta = strtolowerTR($get_eposta);
            if (filter_var($get_eposta, FILTER_VALIDATE_EMAIL) === false) {
                echo "<result status='REGEX'>Eposta Formatı Hatalıdır.</result>";
                return;
            }
        }
        //check ulke
        if ($get_ikamet_ulke != "") {
            if (!is_numeric($get_ikamet_ulke)) {
                echo "<result status='REGEX'>Ülkeyi yeniden seçiniz.</result>";
                return;
            }
        }

        //check ikamet il
        if ($get_ikamet_il != "") {
            if (!is_numeric($get_ikamet_il)) {
                echo "<result status='REGEX'>İkamet ilini yeniden seçiniz.</result>";
                return;
            }
        }
        //check ikamet ilce
        if ($get_ikamet_ilce != "") {
            if (!is_numeric($get_ikamet_ilce)) {
                echo "<result status='REGEX'>İkamet ilçesini yeniden seçiniz.</result>";
                return;
            }
        }
        //address check
        if ($get_adres != "") {
            //new lines set
            $get_adres = nl2br($get_adres);
        }
        //uyruk check
        if ($get_uyruk != "") {
            if (!is_numeric($get_uyruk) || !$get_uyruk > 0) {
                echo "<result status='REGEX'>Uyruğu yeniden seçiniz.</result>";
                return;
            }
        }
        //cinsiyet check
        if ($get_cinsiyeti != "1" && $get_cinsiyeti != "2") {
            echo "<result status='REGEX'>Cinsiyeti yeniden seçiniz.</result>";
            return;
        }
        //military obligation status
        //firstly dates
        if ($get_terhis_tarihi != "" && !isDate($get_terhis_tarihi)) {
            echo "<result status='REGEX'>Terhis tarihi formatını yeniden giriniz.</result>";
            return;
        }
        //fix date
        $get_terhis_tarihi = (($get_terhis_tarihi != "") ? $get_terhis_tarihi : "NULL");
        if ($get_terhis_tarihi != "" && $get_terhis_tarihi != "NULL") {
            $get_terhis_tarihi = convertToMysqlDateFormat($get_terhis_tarihi);
        }

        if ($get_tecil_tarihi != "" && !isDate($get_tecil_tarihi)) {
            echo "<result status='REGEX'>Tecil tarihi formatını yeniden giriniz.</result>";
            return;
        }
        //fix date
        $get_tecil_tarihi = !empty($get_tecil_tarihi) ? $get_tecil_tarihi : "NULL";
        if ($get_tecil_tarihi != "" && $get_tecil_tarihi != "NULL") {
            $get_tecil_tarihi = convertToMysqlDateFormat($get_tecil_tarihi);
        }
        //then logic controls
        if (($get_tecil_tarihi != "" && $get_terhis_tarihi != "") && ($get_tecil_tarihi !=
            "NULL" && $get_terhis_tarihi != "NULL")) {
            echo "<result status='REGEX'>Askerlik durumunda sadece bir seçeneği seçebilirsiniz.</result>";
            return;
        }

        if ($get_askerlik_durumlari == 2 && $get_terhis_tarihi == "") {
            echo "<result status='REGEX'>Askerlik Bilgisini Yapıldı olarak girdiniz. Terhis tarihini girmelisiniz.</result>";
            return;
        }
        if ($get_askerlik_durumlari == 3 && $get_muaf_neden == "") {
            echo "<result status='REGEX'>Askerlik Bilgisini Muaf olarak girdiniz. Muaf sebebini girmelisiniz.</result>";
            return;
        }
        if ($get_askerlik_durumlari == 4 && $get_tecil_tarihi == "") {
            echo "<result status='REGEX'>Askerlik Bilgisini Tecilli olarak girdiniz. Tecil tarihini girmelisiniz.</result>";
            return;
        }
        //check medeni durum
        if ($get_medeni_durum != 1 && $get_medeni_durum != 2 && $get_medeni_durum != 3) {
            echo "<result status='REGEX'>Medeni durumu yeniden seçiniz.</result>";
            return;
        }
        //check cocuk sayisi
        if ($get_cocuk_sayisi != "") {
            if (!is_numeric($get_cocuk_sayisi)) {
                echo "<result status='REGEX'>Çocuk sayısı sayısal bir değer olmalıdır.</result>";
                return;
            }
        }
        //check es calisma durumu
        if ($get_es_calisma_durumu != 1 && $get_es_calisma_durumu != 2) {
            echo "<result status='REGEX'>Eş çalışma durumu çalışıyor ya da çalışmıyor şeklinde olmalıdır.</result>";
            return;
        }
        //check es isyeri adi
        if ($get_es_calisma_durumu == 2 && $get_es_isyeri_adi == "") {
            echo "<result status='REGEX'>Eş işyeri adı girilmelidir.</result>";
            return;
        }
        //check gorevi
        if ($get_es_calisma_durumu == 2 && $get_es_gorevi == "") {
            echo "<result status='REGEX'>Eş görevi girilmelidir.</result>";
            return;
        }
        //check anne adi
        if ($get_anne_adi != "") {

        }
        //check anne gorevi
        if ($get_anne_meslegi != "") {

        }
        //check babaadi
        if ($get_baba_adi != "") {

        }
        //check baba meslegi
        if ($get_baba_meslegi != "") {

        }
        //check personelin gorevi
        if ($get_personelin_gorevi == "") {
            echo "<result status='REGEX'>Personelin görevi girilmelidir.</result>";
            return;
        }
        //check isebaslama tarihi
        if ($get_ise_baslama_tarihi == "" || !isDate($get_ise_baslama_tarihi)) {
            echo "<result status='REGEX'>İşe başlama tarihi girilmelidir.</result>";
            return;
        }
        //fix date
        $get_ise_baslama_tarihi = !empty($get_ise_baslama_tarihi) ? $get_ise_baslama_tarihi :
            "NULL";
        if ($get_ise_baslama_tarihi != "" && $get_ise_baslama_tarihi != "NULL") {
            $get_ise_baslama_tarihi = convertToMysqlDateFormat($get_ise_baslama_tarihi);
        }
        //check maas
        if ($get_net_maas != "") {
            //set money format
            $get_net_maas = checkTLCurrency($get_net_maas);
        }
        //check yemek ucreti
        if ($get_yemek_ucreti != "") {
            $get_yemek_ucreti = checkTLCurrency($get_yemek_ucreti);
        }
        //check yol ucreti
        if ($get_yol_ucreti != "") {
            $get_yol_ucreti = checkTLCurrency($get_yol_ucreti);
        }
        //check brut ucret
        if ($get_brut_ucret != "") {
            $get_brut_ucret = checkTLCurrency($get_brut_ucret);
        }
        //hesap no
        if ($get_hesap_no != "") {

        }
        //check sube kodu
        if ($get_sube_kodu != "") {

        }
        //check IBAN
        if ($get_iban != "") {

        }

        //check ehliyet
        if ($get_ehliyet != 1 && $get_ehliyet != 2) {
            echo "<result status='REGEX'>Ehliyet var veya yok olarak seçilmelidir.</result>";
            return;
        }
        if ($get_ehliyet == 2 && $get_ehliyet_yili == "") {
            echo "<result status='REGEX'>Ehliyet var ise ehliyet yılı seçilmelidir.</result>";
            return;
        }
        if ($get_ehliyet == 2 && $get_ehliyet_yili == "") {
            echo "<result status='REGEX'>Ehliyet var ise ehliyet yılı seçilmelidir.</result>";
            return;
        }


        //last fix for employee record.
        //date params in DB must be DB NULL if not set to some value.
        $get_dogum_tarihi = finalizeDateForDb($get_dogum_tarihi);
        $get_terhis_tarihi = finalizeDateForDb($get_terhis_tarihi);
        $get_tecil_tarihi = finalizeDateForDb($get_tecil_tarihi);
        $get_ise_baslama_tarihi = finalizeDateForDb($get_ise_baslama_tarihi);

        //if id is innocent.
        //if id is > 0 and belongs to that user
        if (trim(getvalue("id"))!="" && trim(getvalue("id"))!=null) {
            //update
            //app_epmloyee, accountancy, roles and permissions
            if(!isAuthorized("",getvalue("id"),"")){
                echo "<result status='WARN'>Yetkisiz bir kullanıcıya işlem yapamazsınız.</result>";
                return;
            }
            try{
            $sql_update_emp = "UPDATE ".$table_app_employee." SET ".
            " emp_job='".$get_personelin_gorevi."',emp_name='".$get_isim."',emp_surname='".$get_soyisim."',emp_citizenid='".$get_tckimlikno."', ".
            " emp_phone_number_gsm='".$get_gsm_number."',emp_phone_number_work='".$get_telefon."',emp_email_personal='".$get_eposta."', ".
            " emp_email_work='".$get_eposta."',emp_birthplace='".$get_dogum_yeri."',emp_birthdate=".$get_dogum_tarihi.",  ".
            " emp_state_origin='".$get_ikamet_ulke."',emp_work_start_date=".$get_ise_baslama_tarihi.",emp_nationality='".$get_uyruk."', ".
            " emp_habit_county='".$get_ikamet_il."',emp_habit_town='".$get_ikamet_ilce."',emp_habit_fulladdress='".$get_adres."', ".
            " emp_sex='".$get_cinsiyeti."',emp_mil_obligation='".$get_askerlik_durumlari."',emp_mil_suspension_date=".$get_tecil_tarihi.",".
            " emp_mil_suspension_reason='".$get_muaf_neden."',emp_mil_end_date=".$get_terhis_tarihi.",emp_marital_status='".$get_medeni_durum."', ".
            " emp_child_count='".$get_cocuk_sayisi."', emp_driving_licence_status='".$get_ehliyet."',emp_driving_licence_type='".$get_ehliyet_sinifi."', ".
            " emp_driving_licence_year='".$get_ehliyet_yili."',emp_spouse_work_state='".$get_es_calisma_durumu."', emp_spouse_workplace='".$get_es_isyeri_adi."', ".
            " emp_spouse_work_position='".$get_es_gorevi."',emp_mother_name='".$get_anne_adi."',emp_father_name='".$get_baba_adi."', ".
            " emp_father_job='".$get_baba_meslegi."', emp_mother_job='".$get_baba_meslegi."' ".
            " WHERE id='".getvalue("id")."'";
            
            $update_app_emp = new query($sql_update_emp);
            
            $sql_update_ac = "UPDATE ".$table_app_employee_accountancy." SET ".
            " net_salary='".$get_net_maas."', food_cost='".$get_yemek_ucreti."', road_fee='".$get_yol_ucreti."', gross_wage='".$get_brut_ucret."', ".
            " account_number='".$get_hesap_no."',branch_code='".$get_sube_kodu."',iban='".$get_iban."', update_date=NOW(), update_by='".$_SESSION['SYS_USER_ID']."' ";
            
            $update_ac = new query($sql_update_ac);
            
            $s = "SELECT user_id FROM ".$table_app_employee." WHERE id='".getvalue("id")."'";
            $q = new query($s);
            $row=$q->fetchobject();
            $user_id=$row->user_id;
            
            $sql_update_roles = "UPDATE ".$table_app_employee_roles." SET employee_role_id='".$get_yetki_turu."' WHERE employee_user_id='".$user_id."'";
            $update_roles = new query($sql_update_roles);
            
            //E2_TODO::update sys_permissions
            //delete all old page permissions
            new query("DELETE FROM ".$table_sys_permissions." WHERE oid='".$user_id."'");
            
            //add new ones
            $p_ids = getPermissionsFromUserType($get_yetki_turu);
            if(count($p_ids)>0){
                foreach ($p_ids as $k=>$p) {
                $sql_insert_sys_permissions = "INSERT INTO " . $table_sys_permissions .
                    " VALUES('$user_id','$p','1111','0000')";
                $insert_sys_permissions = new query($sql_insert_sys_permissions);
                }    
            }
            echo "<result status='UPDATE'>Bilgiler Başarıyla Güncellendi!</result>";
            
            }catch(exception $e){
                echo "<result status='ERROR'>Bir hata oluştu!</result>";
            }
            
            
            
        } else {
            //insert

            //insert script
            //each employee also is a user. so user records must be done before employee process.
            //we can copy the process of registers.
            //firstly check is tc is already saved to system
            $sql_check_employee = " SELECT emp_name FROM " . $table_app_employee .
                " WHERE emp_citizenid='" . $get_tckimlikno . "' AND firm_id='" . $_SESSION['SYS_USER_FIRM_ID'] .
                "' ";
            $select_app_employee = new query($sql_check_employee);
            if ($select_app_employee->numrows() > 0) {
                //tc is already in the system
                echo "<result status='WARN'>Bu Personel zaten sistemde kayıtlıdır.</result>";
                return;
            }

            //then check if email is saved before
            $sql_check_email = " SELECT name FROM " . $table_sys_users . " WHERE email='" .
                $get_eposta . "' ";
            $select_sys_users = new query($sql_check_email);
            if ($select_sys_users->numrows() > 0) {
                //this email is taken before.
                echo "<result status='WARN'>Bu Eposta adresi ile daha önce sisteme kullanıcı kaydedilmiş.</result>";
                return;
            }


            //continue
            //tables to insert

            /**
             * tables to insert
             * sys_objects,
             * sys_users, 
             * sys_group_members,
             * app_firms,
             * app_employee,
             * app_user_detail,
             * sys_permissions,
             * app_employee_roles
             */

            //sys_objects
            $sql_insert_sys_objects = "INSERT INTO " . $table_sys_objetcs .
                " VALUES(null,NOW(),'user','" . $_SESSION['SYS_USER_ID'] . "','1')";
            $insert_sys_objects = new query($sql_insert_sys_objects);
            if ($insert_sys_objects->affectedrows() > 0) {
                //success.continue
                $insertId = $insert_sys_objects->insertid(); //this is the ID that we will link with every user issue


                //generate a 6-digit code. the code will be used to register user.
                $digit = rand(10000, 99999);

                //sys_users
                $sql_insert_sys_users = "INSERT INTO " . $table_sys_users . " VALUES('$insertId','$get_eposta','" .
                    $digit . "','" . $get_isim . " " . $get_soyisim . "','email_sended','$get_eposta','1','fb','2',null,'1','0')";
                /*notes: cpnl 2=> email will send*/
                $insert_sys_users = new query($sql_insert_sys_users);

                if ($insert_sys_users->affectedrows() > 0) {

                    //sys_group_members
                    $sql_insert_sys_group_members = "INSERT INTO " . $table_sys_group_members .
                        " VALUES('2','$insertId',NOW())";
                    $insert_sys_group_members = new query($sql_insert_sys_group_members);

                    if ($insert_sys_group_members->affectedrows() > 0) {
                        $guid = trim(getGUID(), '{}');

                        //app_employee
                        $sql_insert_app_employee = "INSERT INTO " . $table_app_employee . " (
                              `id`,`user_id`,`emp_job`,`emp_title`,`firm_id`,`firm_department_id`,`firm_branch_id`,`emp_name`,`emp_surname`,`emp_citizenid`
                              ,`emp_phone_number_gsm`,`emp_phone_number_work`,`emp_email_personal`,`emp_email_work`,`emp_birth_county`,`emp_birth_town`
                              ,`emp_birthplace`,`emp_birthdate`,`emp_state_origin`,`emp_work_start_date`,`emp_work_end_date`,`emp_work_type`,`emp_nationality`,`emp_habit_county`
                              ,`emp_habit_town` ,`emp_habit_fulladdress`,`emp_sex`,`emp_mil_obligation`,`emp_mil_suspension_date`,`emp_mil_suspension_reason` 
                              ,`emp_mil_end_date`,`emp_marital_status` ,`emp_child_count` ,`emp_education_status`,`emp_driving_licence_status`,`emp_driving_licence_type` 
                              ,`emp_driving_licence_year`,`emp_spouse_work_state`,`emp_spouse_workplace`,`emp_spouse_work_position`,`emp_mother_name`
                              ,`emp_father_name`,`emp_father_job`,`emp_mother_job`,`emp_job_desc`,`emp_job_position`,`notes`,`is_active`
                                )VALUES(
                                '" . $guid . "','" . $insertId . "','" . $get_personelin_gorevi .
                            "','pozisyon buraya gelecek','" . $_SESSION['SYS_USER_FIRM_ID'] . "','0','0','" .
                            $get_isim . "','" . $get_soyisim . "',
                                '" . $get_tckimlikno . "','" . $get_gsm_number .
                            "','" . $get_telefon . "','" . $get_eposta . "','" . $get_eposta . "','0','0','".$get_dogum_yeri."'," .
                            $get_dogum_tarihi . ",
                                '" . $get_ikamet_ulke . "'," . $get_ise_baslama_tarihi .
                            ",null,'0','" . $get_uyruk . "','" . $get_ikamet_il . "','" . $get_ikamet_ilce .
                            "',
                                '" . $get_adres . "','" . $get_cinsiyeti . "','" .
                            $get_askerlik_durumlari . "'," . $get_tecil_tarihi . ",'" . $get_muaf_neden .
                            "'," . $get_terhis_tarihi . ",
                                '" . $get_medeni_durum . "','" . $get_cocuk_sayisi .
                            "','0','" . $get_ehliyet . "','" . $get_ehliyet_sinifi . "','" . $get_ehliyet_yili .
                            "','" . $get_es_calisma_durumu . "',
                                '" . $get_es_isyeri_adi . "','" . $get_es_gorevi .
                            "','" . $get_anne_adi . "','" . $get_baba_adi . "','" . $get_baba_meslegi .
                            "','" . $get_anne_meslegi . "',
                                '','','','1'
                                )
                            ";
                        $insert_app_employee = new query($sql_insert_app_employee);

                        if ($insert_app_employee->affectedrows() > 0) {

                            //employee_accountancy
                            $sql_insert_app_employee_accountancy = "INSERT INTO " . $table_app_employee_accountancy .
                                "
                        	   (`id`,`emp_id`,`net_salary`,`food_cost`,`road_fee` ,`gross_wage`,`account_number`,
                               `bank_id`,`branch_code`,`iban`,
                              `insert_date`,`insert_by`,`update_date`,`update_by`,
                              `is_active`) VALUES(null,'" . $guid . "','" . $get_net_maas .
                                "','" . $get_yemek_ucreti . "','" . $get_yol_ucreti . "',
                              '" . $get_brut_ucret . "','" . $get_hesap_no .
                                "','0','" . $get_sube_kodu . "','" . $get_iban . "',NOW(),'" . $_SESSION['SYS_USER_ID'] .
                                "',NULL,NULL,1)";

                            $insert_app_employee_accountancy = new query($sql_insert_app_employee_accountancy);

                            if ($insert_app_employee_accountancy->affectedrows() > 0) {
                                //app_user_detail
                                $otp = trim(getGUID(), '{}');
                                $sql_insert_app_user_detail = "INSERT INTO " . $table_app_user_detail .
                                    " (id,user_id,firm_id,added_date,inserted_by,is_main_account,account_type,related_account_user_id,otp,otp_used,is_active) " .
                                    " VALUES(null,'$insertId','" . $_SESSION['SYS_USER_FIRM_ID'] . "',NOW(),'" . $_SESSION['SYS_USER_ID'] .
                                    "',0,'0','" . $_SESSION['SYS_USER_FIRM_ID'] . "','$otp',0,1)";
                                $insert_app_user_detail = new query($sql_insert_app_user_detail);

                                if ($insert_app_user_detail->affectedrows() > 0) {

                                    //employee_roles
                                    $sql_insert_app_employee_roles = "INSERT INTO " . $table_app_employee_roles .
                                        " VALUES(null,'$insertId','" . $get_yetki_turu . "',NOW(),'" . $_SESSION['SYS_USER_ID'] .
                                        "',null,null,null,1)";
                                    $insert_app_employee_roles = new query($sql_insert_app_employee_roles);

                                    if ($insert_app_employee_roles->affectedrows() > 0) {

                                        //success. ::TODO:: sys_permissions will be added. 
                                        //because all pages comes from permission. which role see which pages. will be added.
                                        $p_ids = getPermissionsFromUserType($get_yetki_turu);
                                        
                                        if(count($p_ids)>0){
                                            foreach ($p_ids as $k=>$p) {
                                            $sql_insert_sys_permissions = "INSERT INTO " . $table_sys_permissions .
                                                " VALUES('$insertId','$p','1111','0000')";
                                            $insert_sys_permissions = new query($sql_insert_sys_permissions);
                                            }
                                        }

                                        //send email to user email address
                                        $text_mail_register_message =
                                            "<div style='width:100%;height:500px;background:#efefef;padding:10px;'>" .
                                            "<div align='center'><h3><b>EKAREye hoşgeldin,</b><h3></div>" .
                                            "<div align='center'><p align='center'>Sana birbirinden güzel ve kullanışlı İnsan Kaynakları ekranlarımızı göstermek için sabırsızlanıyoruz.Bunun için son bir adım kaldı. Aşağıdaki doğrulama linkine tıklayıp hesabını aktifleştirmen yeterli.<p></div>" .
                                            "<div align='center'><a style='text-decoration:none;padding:5px;background:#cc0033;color:#fff' href='".$mail_path."index.php?pid=11&sid=confirm&otp=" .
                                            $otp . "'>Hesabı Aktifleştir!</a></div>" . "</div>";

                                        sendMail($get_eposta, "Lütfen Eposta Adresini Doğrulayın", $text_mail_register_message);

                                        echo "<result status='OK'>" . $MESSAGE_NEW_USER_ADD_OK . "</result>";
                                        return;


                                    } else {
                                        echo "<result status='ERROR'>" . $MESSAGE_ERROR_OCCURED . "</result>";
                                        return;
                                    }
                                } else {
                                    echo "<result status='ERROR'>" . $MESSAGE_ERROR_OCCURED . "</result>";
                                    return;
                                }
                            } else {
                                echo "<result status='ERROR'>" . $MESSAGE_ERROR_OCCURED . "</result>";
                                return;
                            }


                        } else {
                            echo "<result status='ERROR'>" . $MESSAGE_ERROR_OCCURED . "</result>";
                            return;
                        }
                    } else {
                        echo "<result status='ERROR'>" . $MESSAGE_ERROR_OCCURED . "</result>";
                        return;
                    }
                } else {
                    echo "<result status='ERROR'>" . $MESSAGE_ERROR_OCCURED . "</result>";
                    return;
                }
            } else {
                echo "<result status='ERROR'>" . $MESSAGE_ERROR_OCCURED . "</result>";
                return;
            }

        }
        break;
}
?>