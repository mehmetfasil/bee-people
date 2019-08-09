<!DOCTYPE html>
<html lang="tr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= CONF_SYSTEM_TITLE ?> </title>

    <!-- Bootstrap -->
    <link href="objects/js/app/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="objects/js/app/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="objects/js/app/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="objects/js/app/vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="objects/js/app/build/css/custom.min.css" rel="stylesheet">
  </head>
  
<?
defined("PASS") or die("Dosya Yok!");
include("modules/app/includes.php");

//update pass and other related issues.
define("HOST", "mysql.ekare.online"); // The host you want to connect to.
define("USER", "ekare"); // The database username.
define("PASSWORD", "CErx23E2"); // The database password.
define("DATABASE", "ekare"); // The database name.

define("CAN_REGISTER", "any");
define("DEFAULT_ROLE", "member");

$submit = getvalue("subm");

if ($submit != "") {
        //post etmis
        $password = getvalue("password");
        $password_again = getvalue("password_again");
        $token = getvalue("p");

        if ($password == "" || $password_again == "") {
        //null values.
        ?>
        <div class="alert alert-danger alert-dismissible fade in" role="alert" align="center">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <strong>Hata!</strong> Lütfen şifreyi giriniz.
        </div>
        
        <?
        }
        if ($password != $password_again) {
        ?>
        <div class="alert alert-danger alert-dismissible fade in" role="alert" align="center">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <strong>Hata!</strong> Girdiğiniz Şifreler Eşleşmiyor.
        </div>
        <?
        }
        checkPassword($password, $errors);
        $error = count($errors);
        if ($error == 0) {
            
        $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

        if ($stmt = $mysqli->prepare("SELECT user_id,otp,otp_used,is_main_account FROM " .
            $table_app_user_detail . "
        WHERE otp = ?
        LIMIT 1")) {
            $stmt->bind_param('s', $token); // Bind param
            $stmt->execute(); // Execute the prepared query.
            $stmt->store_result();

            // get variables from result.
            $stmt->bind_result($user_id, $otp, $otp_used, $is_main_account);
            $stmt->fetch();
            if ($stmt->num_rows == 1) {
                if (!$otp_used) {
                    //change app_user_detail otp_used column, sys_users detail column
                    $mysqli->query(" UPDATE " . $table_sys_users .
                        " SET detail='account_approved', pass='" . md5($password) .
                        "', cpnl='1' where id='" . $user_id . "'  ");
                    $mysqli->query(" UPDATE " . $table_app_user_detail .
                        " SET otp_used=1 WHERE user_id='" . $user_id . "' ");

                    //done.warn and header to index.
                    ?>
                    <div class="alert alert-success alert-dismissible fade in" role="alert" align="center">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <strong>Başarılı!</strong> Şireniz Belirlendi. Giriş Sayfasına Yönlendiriliyorsunuz.
                    </div>
                    <?
                    header('Refresh: 3; URL=index.php');
                    exit();
                    
                } else {
                    ?>
                    <div align="center" style="padding-top: 10%;">
                        <img src="templates/app/images/logo.png"/><br />
                        Bu eposta adresi daha önce doğrulanmış. Login Sayfasına yönlendiriliyorsunuz...
                    </div>
                    <?
                //header to index.php
                header('Refresh: 3; URL=index.php');
                }

            }
        }

        } else {
        //show error
        $text = "";
        foreach ($errors as $e) {
            $text .= $e . "<br/>";
        }
        ?>
        <div class="alert alert-danger alert-dismissible fade in" role="alert" align="center">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <strong>Hata!</strong> <?= $text ?>.
        </div>
        <?
        }
}

//first visit
$token = getvalue("otp");

if ($token == "" || $token == null) {
    header("Location:index.php");
} else {
    //check token isset and valid
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    if ($stmt = $mysqli->prepare("SELECT user_id,otp,otp_used,is_main_account FROM " .
        $table_app_user_detail . "
       WHERE otp = ?
        LIMIT 1")) {
        $stmt->bind_param('s', $token); // Bind param
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();

        // get variables from result.
        $stmt->bind_result($user_id, $otp, $otp_used, $is_main_account);
        $stmt->fetch();
        if ($stmt->num_rows == 1) {
            if ($otp_used) {
            ?>
            <div align="center" style="padding-top: 10%;">
                <img src="templates/app/images/logo.png"/><br />
                Bu eposta adresi daha önce doğrulanmış. Login Sayfasına yönlendiriliyorsunuz...
            </div>
            <?
                //header to index.php
                header('Refresh: 3; URL=index.php');

            } else {
                //if user account owner or employee
                if ($is_main_account == 0) {
                    //employee. open a form to set his/her new password

?>
                <div style="width: 40%;margin:0px auto" align="center">
                <h3>Şifre Ekranı</h3>
                <form method="POST" id="PForm" name="PForm">
                    <input type="hidden" id="p" name="p" value="<?= $token; ?>"/>
                    <input type="password" id="password" class="form-control" name="password" placeholder="Şifre"/>
                    <input type="password" id="password_again" class="form-control" placeholder="Şifre Tekrar" name="password_again"/><br />
                    <button id="subm" name="subm" value="subm" type="submit" class="form-control btn-primary">Şifreyi Kaydet</button>
                </form>
                </div>
                <?
                } else {

                    //change app_user_detail otp_used column, sys_users detail column
                    $mysqli->query(" UPDATE " . $table_sys_users .
                        " SET detail='account_approved' where id='" . $user_id . "'  ");
                    $mysqli->query(" UPDATE " . $table_sys_users . " SET cpnl='1' where id='" . $user_id .
                        "'  "); //it was 2
                    $mysqli->query(" UPDATE " . $table_app_user_detail .
                        " SET otp_used=1 WHERE user_id='" . $user_id . "' ");
?>
                <div align="center" style="padding-top: 10%;">
                <img src="templates/app/images/logo.png"/><br />
                Tebrikler, eposta adresinizi başarıyla onayladınız. Giriş sayfasına yönlendiriliyorsunuz.
            </div>
            
            <?
                    header('Refresh: 3; URL=index.php');

                }
            }
        } else {
            // no otp exist. head to index
            header("Location:index.php");
        }
    } else {
        echo $mysqli->error;
    }
}
?>


</body>
</div>