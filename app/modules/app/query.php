<?
defined("PASS") or die("Dosya Yok!");
//app includes
include(CONF_DOCUMENT_ROOT . "modules" . DS . "app" . DS . "includes.php");
switch (ACT) {
    
    case "lost_pass":
        $email = trim(getvalue("email"));
        if(filter_var($email,FILTER_VALIDATE_EMAIL)===false){
            echo "<result status='ERROR'>Geçerli Mail Formatı Değil</result>";
            return;
        }
        //check email
        $get = "SELECT id FROM ".$table_sys_users. " WHERE email='".$email."' and `lock`='0' ";
        $select = new query($get);
        if($select->numrows()>0){
            $row = $select->fetchobject();
            //user lost password. update sys users status and then send an email.
                $otp = trim(getGUID(), '{}');
                
                //check if otp already send
                $check = new query("SELECT otp FROM ".$table_sys_otp_history." WHERE otp_type='1' and user_id='".$row->id."' and is_used='0' ");
                if($check->numrows()>0){
                    $controlRow = $check->fetchobject();
                    $otp = $controlRow->otp;
                    //already sent. update otp date
                    new query("UPDATE ".$table_sys_otp_history." SET `request_date`=NOW() where user_id='".$row->id."' and otp_type='1' and is_used='0' ");
                }else{
                //update ok. insert otp history
                $sql_insert = "INSERT INTO ".$table_sys_otp_history. " (id,otp_type,user_id,`key`,otp,request_date,is_used)".
                " VALUES(id,'1','".$row->id."','".$email."','".$otp."',NOW(),0)";
                //exit($sql_insert);
                $insert_otp = new query($sql_insert);
                }
                $sql_update = "UPDATE ".$table_sys_users." SET detail='email_sended', cpnl='2' WHERE email='".$email."' and id='".$row->id."' ";
                $update = new query($sql_update);
                $text_mail_register_message =
                                        "<div style='width:100%;height:500px;background:#efefef;padding:10px;'>" .
                                        "<div align='center'><h3><b>Şifre Sıfırlama Talebi,</b><h3></div>" .
                                        "<div align='center'><p align='center'>Şifreni Hemen Sıfırlayabilirsin. Bunun için aşağıdaki linke tıklayıp yönlendirileceğin sayfada yeni şifreleri girmen yeterli.<p></div>" .
                                        "<div align='center'><a style='text-decoration:none;padding:5px;background:#cc0033;color:#fff' href='".$mail_path."index.php?pid=11&sid=lost_password&lotp=" .
                                        $otp . "'>Şifreyi Sıfırla!</a></div>" . "</div>";
                    sendMail($email, "Şifre Sıfırlama Talebi", $text_mail_register_message);
                    echo "<result status='OK'>Şifre Sıfırlama Talebi mail adresinize gönderildi.</result>";
                return;
            
        }else{
            echo "<result status='ERROR'>Sistemde Kayıtlı Bir Eposta Adresi Değil</result>";
            return;
        }
    break;
    
    case "register_user":

        //posted data
        /**
         * [register_email] => mehmetfasil@gmail.com
         * [register_firm] => Synapse
         * [register_password] => 123456
         * [register_name] => mehmet
         * [register_surname] => fasıl
         */
        //tables to check
        /**
         * he/she could be 
         * 1) an old premium-test user.
         * 2) an employee. 
         **/

        $get_email = checkInput(getvalue("register_email"));
        $get_firm = checkInput(getvalue("register_firm"));
        $get_password = checkInput(getvalue("register_password"));
        $get_name = checkInput(getvalue("register_name"));
        $get_surname = checkInput(getvalue("register_surname"));
        $fullname = $get_name . " " . $get_surname;

        $query = "SELECT s.name,s.detail FROM " . $table_sys_users . " as s inner join " .
            $table_sys_objetcs . " as o on s.id = o.id WHERE s.email='" . $get_email .
            "' and o.active='1' ";
        $select = new query($query);
        if ($select->numrows() > 0) {
            /*email already taken. return */
            //check if sended email approved by user.
            $row = $select->fetchobject();
            $row_user_email_state = $row->detail;
            if ($row_user_email_state == "email_sended") {
                echo "<report status='ACCOUNT_EXIST_EMAIL_CHECK'>" . $MESSAGE_USER_REGISTER_OK_CHECK_EMAIL .
                    "</report>";
                return;
            } else {

            }
            echo "<report status='ACCOUNT_EXIST'>" . $MESSAGE_ACCOUNT_TAKEN . "</report>";
            return;
        }

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

        //lets start
        $sql_insert_sys_objects = "INSERT INTO " . $table_sys_objetcs .
            " VALUES(null,NOW(),'user','1','1')";
        $insert_sys_objects = new query($sql_insert_sys_objects);
        if ($insert_sys_objects->affectedrows() > 0) {

            //success.continue
            $insertId = $insert_sys_objects->insertid(); //this is the ID that we will link with every user issue

            $sql_insert_sys_users = "INSERT INTO " . $table_sys_users . " VALUES('$insertId','$get_email','" .
                md5($get_password) . "','$fullname','email_sended','$get_email','1','fb','2',null,'1','0')";
             /*notes: fullname will be empty. user then will be able to fulfill. cpnl 2 email will send*/
            $insert_sys_users = new query($sql_insert_sys_users);

            if ($insert_sys_users->affectedrows() > 0) {

                //success
                $sql_insert_sys_group_members = "INSERT INTO " . $table_sys_group_members .
                    " VALUES('2','$insertId',NOW())";
                $insert_sys_group_members = new query($sql_insert_sys_group_members);

                if ($insert_sys_group_members->affectedrows() > 0) {
                    //success
                    $sql_insert_app_firms = "INSERT INTO " . $table_app_firms .
                        " (id,firm_name,related_user_id,insert_date,is_active) VALUES(null,'$get_firm','$insertId',NOW(),1) ";
                    $insert_app_firms = new query($sql_insert_app_firms);

                    if ($insert_app_firms->affectedrows() > 0) {
                        //success
                        $firmId = $insert_app_firms->insertid(); //firmId will be linked to employee record
                        $empId = trim(getGUID(), '{}');
                        $sql_insert_app_employee = "INSERT INTO " . $table_app_employee .
                            " (id,user_id,firm_id,is_active,emp_name,emp_surname) VALUES ('$empId','$insertId','$firmId',1,'$get_name','$get_surname')";
                        $insert_app_employee = new query($sql_insert_app_employee);

                        if ($insert_app_employee->affectedrows() > 0) {
                            //success
                            //newID
                            $otp = trim(getGUID(), '{}');
                            $sql_insert_app_user_detail = "INSERT INTO " . $table_app_user_detail .
                                " (id,user_id,firm_id,added_date,inserted_by,is_main_account,account_type,related_account_user_id,otp,otp_used,is_active) " .
                                " VALUES(null,'$insertId','$firmId',NOW(),'1',1,'0','1','$otp',0,1)";
                            $insert_app_user_detail = new query($sql_insert_app_user_detail);

                            if ($insert_app_user_detail->affectedrows() > 0) {
                                //success
                                $sql_insert_app_employee_roles = "INSERT INTO " . $table_app_employee_roles .
                                    " VALUES(null,'$insertId','1',NOW(),'1',null,null,null,1)"; //all users which create an account will be added to account owner role (means id 1)
                                $insert_app_employee_roles = new query($sql_insert_app_employee_roles);

                                if ($insert_app_employee_roles->affectedrows() > 0) {
                                    //success. ::TODO:: sys_permissions will be added. because all pages comes from permission. which role see which pages. will be added.
                                    foreach ($array_permission_for_account_owner as $p) {
                                        $sql_insert_sys_permissions = "INSERT INTO " . $table_sys_permissions .
                                            " VALUES('$insertId','$p','1111','0000')";
                                        $insert_sys_permissions = new query($sql_insert_sys_permissions);
                                    }

                                    //send email to user email address
                                    $text_mail_register_message =
                                        "<div style='width:100%;height:500px;background:#efefef;padding:10px;'>" .
                                        "<div align='center'><h3><b>EKAREye hoşgeldin,</b><h3></div>" .
                                        "<div align='center'><p align='center'>Sana birbirinden güzel ve kullanışlı İnsan Kaynakları ekranlarımızı göstermek için sabırsızlanıyoruz.Bunun için son bir adım kaldı. Aşağıdaki doğrulama linkine tıklayıp hesabını aktifleştirmen yeterli.<p></div>" .
                                        "<div align='center'><a style='text-decoration:none;padding:5px;background:#cc0033;color:#fff' href='".$mail_path."index.php?pid=11&sid=confirm&otp=" .
                                        $otp . "'>Hesabı Aktifleştir!</a></div>" . "</div>";

                                    sendMail($get_email, "Lütfen Eposta Adresini Doğrulayın", $text_mail_register_message);


                                    echo "<report status='OK'>" . $MESSAGE_USER_REGISTER_OK . "</report>";
                                    return;
                                } else {
                                    echo "<report status='ERROR'>" . $MESSAGE_ERROR_OCCURED . "</report>";
                                    return;
                                }
                            } else {
                                echo "<report status='ERROR'>" . $MESSAGE_ERROR_OCCURED . "</report>";
                                return;
                            }
                        } else {
                            echo "<report status='ERROR'>" . $MESSAGE_ERROR_OCCURED . "</report>";
                            return;
                        }
                    } else {
                        echo "<report status='ERROR'>" . $MESSAGE_ERROR_OCCURED . "</report>";
                        return;
                    }
                } else {
                    echo "<report status='ERROR'>" . $MESSAGE_ERROR_OCCURED . "</report>";
                    return;
                }

            } else {
                echo "<report status='ERROR'>" . $MESSAGE_ERROR_OCCURED . "</report>";
                return;
            }

        } else {
            echo "<report status='ERROR'>" . $MESSAGE_ERROR_OCCURED . "</report>";
            return;
        }

        break;

    case "resend_email":

        //posted data
        /**
         * [email] => mehmetfasil@gmail.com
         */

        $get_email = checkInput(getvalue("email"));

        //check if email exists;
        $sql_select_sys_users = "SELECT name FROM " . $table_sys_users .
            " WHERE email='" . $get_email . "' and detail='email_sended' ";
        $select_sys_users = new query($sql_select_sys_users);
        if ($select_sys_users->numrows() > 0) {
            //emailsend
            sendMail($get_email, "Lütfen Eposta Adresini Doğrulayın", $text_mail_register_message);
            echo "<report status='OK'>" . $MESSAGE_EMAIL_RESENDED . "</report>";
            return;

        } else {
            echo "<report status='ERROR'>" . $MESSAGE_EMAIL_NOT_FOUND_FOR_REGISTER .
                "</report>";
            return;
        }

        break;

    case "get_datas":
        if (isset($_SESSION['SYS_USER_ID']) && $_SESSION['SYS_USER_ID'] > 0) {

            $sql_select_app_firm_name = "select f.id,f.firm_name,f.related_user_id from " . $table_app_employee .
                " as e inner join " . $table_app_firms .
                " as f on e.firm_id = f.id WHERE e.user_id='" . $_SESSION['SYS_USER_ID'] . "' ";
            $select_app_employee = new query($sql_select_app_firm_name);
            if ($select_app_employee->numrows() > 0) {
                $row = $select_app_employee->fetchobject();
                $firmname = $row->firm_name;
                $firmId = $row->id;
                $related_user_id = $row->related_user_id;
                if($related_user_id==$_SESSION['SYS_USER_ID']){
                    $_SESSION['IS_USER_OWNER'] = true;
                    //get account type
                    $sql_select_app_user_detail = "SELECT account_type,DATE_FORMAT(added_date,'%d-%m-%Y') as added_date FROM ".$table_app_user_detail." WHERE user_id='".$_SESSION['SYS_USER_ID']."' and is_main_account='1' ";
                    $select_app_user_detail = new query($sql_select_app_user_detail);
                    if($select_app_user_detail->numrows()>0){
                        $row = $select_app_user_detail->fetchobject();
                        if($row->account_type==0){
                            //user is in trial mode. notify him about days left.
                            $added_date = $row->added_date;
                            $new_date = date("d-m-Y",strtotime($added_date.'+ 16 days'));
                            $d = date_parse_from_format("d-m-Y", $new_date);
                            $maketime = mktime(0,0,0,$d["month"],$d["day"],$d["year"]);
                            $today = time();
                            $diff = $maketime - $today ;
                            $diff = floor($diff/60/60/24);
                            if($diff>0){
                                
                                $_SESSION['SYS_USER_ACCOUNT_REMAINING_DAY'] = $diff;
                            }else{
                                $_SESSION['SYS_USER_ACCOUNT_REMAINING_DAY'] = 0;
                            }
                        }
                    }
                }
                $_SESSION['SYS_USER_FIRM_NAME'] = $firmname;
                $_SESSION['SYS_USER_FIRM_ID'] = $firmId;
            }

            $sql_select_app_employee_roles = "select r.id, r.role_name from " . $table_app_employee_roles .
                " as er
                                    left join " . $table_app_roles .
                " as r on r.id = er.employee_role_id
                                    where er.employee_user_id = '" . $_SESSION['SYS_USER_ID'] .
                "' ";
            $select_app_employee_roles = new query($sql_select_app_employee_roles);
            if ($select_app_employee_roles->numrows() > 0) {
                $row = $select_app_employee_roles->fetchobject();
                $rolename = $row->role_name;
                $roleId = $row->id;
                $_SESSION['SYS_USER_ROLE_NAME'] = $rolename;
                $_SESSION['SYS_USER_ROLE_ID'] = $roleId;
            }
        }
        break;
}
?>