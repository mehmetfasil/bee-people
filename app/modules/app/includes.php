<?
defined("PASS" or die("Dosya Yok!"));

/*tables*/
$table_sys_users = 'sys_users';
$table_sys_objetcs = 'sys_objects';
$table_sys_group_members = 'sys_group_members';
$table_app_dayoff_types = 'app_dayoff_types';
$table_app_employee = 'app_employee';
$table_app_employee_accountancy = 'app_employee_accountancy';
$table_app_firms = 'app_firms';
$table_app_firm_wc = 'app_firm_workcal';
$table_app_firm_event = 'app_firm_event';
$table_app_f_lookups='app_firms_lookups';
$table_app_user_detail = 'app_user_detail';
$table_app_employee_roles = 'app_employee_roles';
$table_app_roles = 'app_roles';
$table_app_employee_files = 'app_employee_files';
$table_app_employee_education = 'app_employee_education';
$table_app_file_definition = 'app_file_definition';
$table_app_employee_dayoff = 'app_employee_dayoff';
$table_app_employee_reference = 'app_employee_reference';
$table_app_employee_experience = 'app_employee_experience';
$table_app_emp_salaries = 'app_employee_salaries';
$table_sys_permissions = 'sys_permissions';
$table_sys_otp_history = 'sys_otp_history';
$table_app_branch='app_firms_branch';
$table_app_department = 'app_firms_departments';
$table_l_county = 'l_county';
$table_l_town = 'l_town';
$table_l_states = 'l_states';



/*uyarılar*/
$MESSAGE_ACCOUNT_TAKEN=" Bu eposta adresi ile daha önce hesap alınmış. Lütfen farklı bir mail adresi ile kaydolmayı deneyiniz. ";
$MESSAGE_ERROR_OCCURED = "Bir Hata Oluştu";
$MESSAGE_USER_REGISTER_OK = "Kullanıcı Kaydı Başarıyla Yapıldı. Lütfen Eposta Adresinize Gönderilen Doğrulamayı Onaylayınız.";
$MESSAGE_USER_REGISTER_OK_CHECK_EMAIL = "Bu eposta adresi ile daha önce kullanıcı kaydı yapılmış. Lütfen Mail Adresinizi Onaylayınız";
$MESSAGE_EMAIL_NOT_FOUND_FOR_REGISTER = "Eposta adresi sistemde kayıtlı değil";
$MESSAGE_EMAIL_RESENDED = "Eposta adresine doğrulama isteği yeniden gönderildi";
$MESSAGE_NEW_USER_ADD_OK = "Yeni Çalışan Kaydı Başarıyla Yapıldı";

/*
url
*/
$mail_path= "http://ekare.online/app/";
//$mail_path= "http://localhost/ekare_5";

/**
 * permission pages for roles
 * */
$array_permission_for_account_owner = array("664","667","668","669","671","672","673","674","675","677","678","679","680","682");
$array_permission_for_employee = array("664");



function EmpDOCount($empId){
    //calisma suresi
    //yasi
    //toplam izin hakkı
    //onceki harcadiklari
    //kalan
    
    $get = new query("SELECT emp_work_start_date FROM app_employee WHERE id='".$empId."'");
    $r= $get->fetchobject();
    $start_date = $r->emp_work_start_date;
    $date1=new DateTime("now") ;
    $date2=new DateTime($start_date);
    $date= $date1->diff($date2);
    $earned = 0;
    $totalDODays = 0;
    $workingYears = $date->format('%y');
    //sıfırdan buyukse izin hakki var.
    if($workingYears>0 && $workingYears<6){
        $earned = 14;
    }
    if($workingYears>=6 && $workingYears<15){
        $earned = 20;
    }
    if($workingYears>=15){
        $earned = 26;
    }
    $result = array();
    if($earned>0){
        //izin haketmis. calisilan yil kadar yillik izni olacak.
        
    }else{
        $result["earned"] = 0;
        $result["left"] = 0;
    }
    return $result;
}


function GetDays($sStartDate, $sEndDate){  
  // Firstly, format the provided dates.  
  // This function works best with YYYY-MM-DD  
  // but other date formats will work thanks  
  // to strtotime().  
  $sStartDate = gmdate("Y-m-d", strtotime($sStartDate));  
  $sEndDate = gmdate("Y-m-d", strtotime($sEndDate));  
  
  // Start the variable off with the start date  
  $aDays[] = $sStartDate;  
  
  // Set a 'temp' variable, sCurrentDate, with  
  // the start date - before beginning the loop  
  $sCurrentDate = $sStartDate;  
  
  // While the current date is less than the end date  
  while($sCurrentDate < $sEndDate){  
    // Add a day to the current date  
    $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));  
  
    // Add this new day to the aDays array  
    $aDays[] = $sCurrentDate;  
  }  
  
  // Once the loop has finished, return the  
  // array of days.  
  return $aDays;  
}

function checkInput($input=""){
    $notAllowedCommands = array(
    'DELETE',
    'TRUNCATE',
    'DROP',
    'USE',
    'delete',
    'drop',
    'DATABASE',
    'SELECT'
    );
    
    $returnValue = trim($input);
    $temp= explode(' ', $returnValue);

    if(count(array_intersect($notAllowedCommands, $temp)) > 0)
    {
        $returnValue="";
    }
    
    return $returnValue;
}

function sendMail($email="",$header="",$text=""){
    include_once("objects/libraries/phpmailer/PHPMailerAutoload.php");
    $mail = new PHPMailer();
    $mail->isSMTP();
    //$mail->SMTPDebug = 1; // hata ayiklama: 1 = hata ve mesaj, 2 = sadece mesaj
    $mail->SMTPAuth = true;
    //$mail->SMTPSecure = 'tls'; // Güvenli baglanti icin ssl normal baglanti icin tls
    $mail->Host = "mail.enginerdogan.net"; // Mail sunucusuna ismi
    $mail->Port = 587; // Guvenli baglanti icin 465 Normal baglanti icin 587
    $mail->IsHTML(true);
    $mail->CharSet  ="utf-8";
    $mail->Username = "noreply@enginerdogan.net"; // Mail adresimizin kullanicı adi
    $mail->Password = "CErx23E2"; // Mail adresimizin sifresi
    $mail->SetFrom("noreply@enginerdogan.net", "E2"); // Mail attigimizda gorulecek ismimiz
    $mail->AddAddress($email); // Maili gonderecegimiz kisi yani alici
    $mail->Subject = $header; // Konu basligi
    $mail->Body = $text; // Mailin icerigi
    if(!$mail->Send()){
        return true;
    } else {
        return false;
    }
}

function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }
    else {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}

function isTcKimlik($tc)  
{  
if(strlen($tc) < 11){ return false; }  
if($tc[0] == '0'){ return false; }  
$plus = ($tc[0] + $tc[2] + $tc[4] + $tc[6] + $tc[8]) * 7;  
$minus = $plus - ($tc[1] + $tc[3] + $tc[5] + $tc[7]);  
$mod = $minus % 10;  
if($mod != $tc[9]){ return false; }  
$all = '';  
for($i = 0 ; $i < 10 ; $i++){ $all += $tc[$i]; }  
if($all % 10 != $tc[10]){ return false; }  
  
return true;  
}  


function script_insert_generate($columns,$tableName){
    
    if(!is_array($columns))
        return "not array format";
    
    foreach($columns as $column){
            
    }
}


function checkPassword($pwd, &$errors) {
    $errors_init = $errors;
    if (strlen($pwd) < 8) {
        $errors[] = "Şifreniz En az 8 karakter olmalıdır!";
    }

    if (!preg_match("#[0-9]+#", $pwd)) {
        $errors[] = "Şifre En az bir sayı içermelidir!";
    }

    if (!preg_match("#[a-zA-Z]+#", $pwd)) {
        $errors[] = "Şifre en az bir harf içermelidir!";
    }     

    return ($errors == $errors_init);
}

/**
 * check if user is allowed to action
 * @param @admin,@user,$act (10,2,"photo_upload")
 * 
 * */
function isAuthorized($adminUserId,$empId,$act){
    $sql_select_app_employee = "SELECT emp_name,user_id FROM app_employee WHERE id='".$empId."' AND firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' ";
    $select_app_employee = new query($sql_select_app_employee);
    if($select_app_employee->numrows()>0){
        return true;
    }else{
        return false;   
    }
}

function isAuthorizedForLookups($rid){
    $sql = "SELECT id FROM app_firms_lookups WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and id='".$rid."' ";
    $q = new query($sql);
    return($q->numRows()>0);
}

function isAuthorizedForItemOnTable($rid,$table_name){
    $sql = "SELECT id FROM ".$table_name." WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and id='".$rid."' ";
    $q = new query($sql);
    return($q->numRows()>0);
}

/**
 * @abstract Turkce Hafta gunu verir
 **/
$TurkceGun = array("1"=>"Pazartesi","2"=>"Salı","3"=>"Çarşamba","4"=>"Perşembe","5"=>"Cuma","6"=>"Cumartesi","7"=>"Pazar");

/**
 * @abstract Turkce Hafta gunu verir
 **/
$TurkceGunKisa = array("1"=>"Pzt","2"=>"Sl","3"=>"Çrş","4"=>"Prş","5"=>"Cu","6"=>"Cts","7"=>"Pzr");

function getPermissionsFromUserType($get_auth_type){
    $q = new query("SELECT p_ids FROM app_role_pages WHERE auth_type='".$get_auth_type."'");
    if($q->numrows()>0){
        $row=$q->fetchobject();
        return explode(",",$row->p_ids);
    }
}

?>


