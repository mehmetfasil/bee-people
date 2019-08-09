<?
/**
 *@author : Mehmet FASIL
 * @abstract : Bee Inc
 */

defined("PASS") or die("Dosya Yok");

if(isset($_SESSION['SYS_USER_ID']) && $_SESSION['SYS_USER_ID']>0){
    header("Location:index.php?pid=".menuID("SITE_APP_MAIN"));
}
?>

<!DOCTYPE html>
<html lang="tr" style="height:100%;min-height:100%;">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?=CONF_SYSTEM_TITLE?> </title>

    <!-- Bootstrap -->
    <link href="objects/js/app/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="objects/js/app/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="objects/js/app/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="objects/js/app/vendors/animate.css/animate.min.css" rel="stylesheet">
    <!-- PNotify -->
    <link href="objects/js/app/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="objects/js/app/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="objects/js/app/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="objects/js/app/build/css/custom.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>
      <div class="login_wrapper">
        
        <div class="animate form login_form">
          <section class="login_content">
          <div align="center">
            <img src="templates/app/images/logo.png" style="width: 30%;"/>
        </div>
            <div id="loginFormDiv">
                <form name="loginForm" id="loginForm" action="" method="POST">
                  <h1>Kullanıcı Girişi</h1>
                  <div>
                    <input type="text" id="user_name" name="user_name" class="form-control" placeholder="Kullanıcı Adı" required="required" />
                  </div>
                  <div>
                    <input type="password" id="user_pass" name="user_pass" class="form-control" placeholder="Şifre" required="required" />
                  </div>
                  <div>
                    <span id="loginResult"></span>
                  </div>
                  <div>
                    <button class="btn btn-primary" type="button" id="btnLogin">Giriş Yap</button>
                    <a class="reset_pass" id="lost_password" href="#">Şifrenizi mi unuttunuz?</a>
                  </div>
    
                  <div class="clearfix"></div>
    
                  <div class="separator">
                    <p class="change_link">Kayıtlı Değil misiniz?
                      <a href="#signup" class="to_register"> Hesap Oluştur </a>
                    </p>
    
                    <div class="clearfix"></div>
                    
                  </div>
                </form>
            </div>
            <div class="hidden" id="lostPasswordDiv">
                <form name="passwordForm" id="passwordForm" method="POST">
                    <h1>Şifremi Unuttum</h1>
                    <div>
                        <input type="text" id="lost_email" name="lost_email" class="form-control" placeholder="Eposta Adresiniz" required="required" />
                    </div>
                    <div>
                        <span id="lostPasswordResult" style="color:#fff;">&nbsp;</span>
                    </div>
                    <div>
                        <button class="btn btn-success" type="button" id="btnLostPass">Şifremi Hatırlat</button>
                    </div>
                    <div>
                        <br /><a href="#" id="reload_login" class="to_register"> Giriş Yap </a>
                    </div>
                </form>
            </div>
          </section>
        </div>

        <div id="register" class="animate form registration_form">
          <section class="login_content">
            <form name="register_form" id="register_form">
              <h1>Hesap Oluştur</h1>
              <div>
                <input type="text"  name="register_name" id="register_name" class="form-control" placeholder="Adı" required="required" />
              </div>
              <div>
                <input type="text"  name="register_surname" id="register_surname" class="form-control" placeholder="Soyadı" required="required" />
              </div>
              <div>
                <input type="email"  name="register_email" id="register_email" class="form-control" placeholder="Email Adresi" required="required" />
              </div>
              <div>
                <input type="text" name="register_firm" id="register_firm" class="form-control" placeholder="Şirket Adı" required="required" />
              </div>
              <div>
                <input type="password" name="register_password" id="register_password" class="form-control" placeholder="Şifre" required="required" />
              </div>
              <div id="register_control" style="color: #fff;font-weight:bold;padding:2px;">
                &nbsp;
              </div>
              <div  align="center">
                <button class="btn btn-success" type="button" id="btnRegister">Kaydol</button>
              </div>
              <div align="center">
                <button class="btn btn-primary display-none" type="button" id="btnResend">Yeniden Eposta Gönder</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Zaten Üyemisiniz ?
                  <a href="#signin" class="to_register"> Giriş Yap </a>
                </p>

                <div class="clearfix"></div>
                
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
  
    <!-- jQuery -->
    <script src="objects/js/app/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="objects/js/app/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="objects/js/app/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="objects/js/app/vendors/nprogress/nprogress.js"></script>
    <!-- validator -->
    <script src="objects/js/app/vendors/validator/validator.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="objects/js/app/build/js/custom.min.js"></script>
    
    <!-- PNotify -->
	<script src="objects/js/app/vendors/pnotify/dist/pnotify.js"></script>
	<script src="objects/js/app/vendors/pnotify/dist/pnotify.buttons.js"></script>
	<script src="objects/js/app/vendors/pnotify/dist/pnotify.nonblock.js"></script>
    
    <!-- appjs-->
    <script src="modules/app/includes_app.js"></script>
    
    <!-- validator -->
    <script>
      // initialize the validator function
      
      validator.message.date = 'not a real date';

      // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
      $('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);

      $('.multi.required').on('keyup blur', 'input', function() {
        validator.checkField.apply($(this).siblings().last()[0]);
      });

      $('form').submit(function(e) {
        e.preventDefault();
        var submit = true;

        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
          submit = false;
        }

        if (submit)
          this.submit();

        return false;
      });
    </script>
    <!-- /validator -->
    
    <!-- login form -->
    <script>
    
    var url = '<?=CONF_MAIN_PAGE."?pid=".PID."&sid=query"?>';
    
    $(document).ready(function(){
        $('#btnLostPass').click(function(){
            if($('#lost_email').val().trim()==""){
                $('#lost_email').focus();
                return;
            }    
            $.ajax({
                type:"POST",
                url:url+"&act=lost_pass",
                data:"email="+$('#lost_email').val().trim(),
                success:function(data){
                    var d = $(data).find("result");
                    if(d.attr("status")=="ERROR"){
                        showNotify("Hata","error",d.text(),"",true);
                    }else{
                        $('#lostPasswordResult').text(d.text());
                        $('#lost_email').val("");
                    }
                }
            })
        })
        
        $('#lost_password').click(function(){
            $('#loginFormDiv').addClass("hidden");
            $('#lostPasswordDiv').removeClass("hidden");            
        });
        
        $('#reload_login').click(function(){
            $('#lostPasswordDiv').addClass("hidden");
            $('#loginFormDiv').removeClass("hidden");
        })
        
		$("#btnLogin").click(function(){			
			if($.trim($("#user_name").val())==""){
				$("#loginResult").html("<span style='color:#60C2EF'><?=label("Lütfen Kullanıcı Adını Giriniz")?><\/span>");
				$("#user_name").get(0).focus();
				return false;
			}
			if($.trim($("#user_pass").val())==""){
				$("#loginResult").html("<span style='color:#60C2EF'><?=label("Lütfen Parolayı Giriniz")?><\/span>");
				$("#user_pass").get(0).focus();
				return false;
			}
			
			// loading message
			var loading="<img src='objects/icons/loaders/4.gif' style='vertical-align:middle;' \/>&nbsp;";
					loading+="<?=label("Sorgulanıyor, Lütfen Bekleyiniz")?>";
			$("#loginResult").html(loading);			
			$.ajax({
				type:"post",
				url:'<?=CONF_MAIN_PAGE."?pid=".menuID("SITE_LOGIN")."&act=login"?>',
				data:$("#loginForm").serialize(),
				error:function(){
					$("#loginResult").html("<?=label("Bir Hata Oluştu !")?>");
				},
				success:function(data){
				    
					if($(data).find("result").attr("status")=="OK"){
						$("#loginResult").html("<?=label("Başarıyla Giriş yaptınız, Yönlendiriliyorsunuz")?>");
                        getDatas();
					}else{
						$("#loginResult").html("<span style='color:#60C2EF;'>"+$(data).find("result").text()+"<\/span>");
					}
				}
			});
			return false;
		});
        
        $('#btnRegister').click(function(){
            if($.trim($("#register_name").val())==""){
				$("#register_control").html("<span><?=label("Adınızı Giriniz")?><\/span>");
				$("#register_name").focus();
				return false;
			}
            if($.trim($("#register_surname").val())==""){
				$("#register_control").html("<span><?=label("Soyadınızı Giriniz")?><\/span>");
				$("#register_surname").focus();
				return false;
			}
            if($.trim($("#register_email").val())==""){
				$("#register_control").html("<span><?=label("Mail Adresinizi Giriniz")?><\/span>");
				$("#register_email").focus();
				return false;
			}
			if($.trim($("#register_firm").val())==""){
				$("#register_control").html("<span><?=label("Lütfen Firma İsmi Giriniz")?><\/span>");
				$("#register_firm").focus();
				return false;
			}
            
            if($.trim($("#register_password").val())==""){
				$("#register_control").html("<span><?=label("Lütfen Şifre Giriniz")?><\/span>");
				$("#register_password").focus();
				return false;
			}
            
            $('#register_control').html("<img src='objects/icons/loaders/1.gif'/>Kontroller Sağlanıyor...");
            
            $.ajax({
                type:"POST",
                url:url+"&act=register_user",
                data:$('#register_form').serialize(),
                success:function(data){
                    var status = $(data).find("report").attr("status");
                    var message = $(data).find("report").text();
                    
                    if(status=="ACCOUNT_EXIST"){
                        $("#register_control").html("<span><b>"+message+"</b><\/span>");
                        return;
                    }
                    
                    if(status=="ACCOUNT_EXIST_EMAIL_CHECK"){
                        $("#register_control").html("<span><b>"+message+"</b><\/span>");
                        $('#btnRegister').addClass("display-none");
                        $('#btnResend').removeClass("display-none").addClass("display-block");
                        
                        return;
                    }
                    
                    if(status=="ERROR"){
                        $("#register_control").html("<span style='color:#cc0033'><b>"+message+"</b><\/span>");
                        return;
                    }
                    
                    if(status=="OK"){
                        clearFields();
                       $("#register_control").html("<span style='color:#cc0033'><b>"+message+"</b><\/span>"); 
                    }
                }
            })
        })
        
        $('#btnResend').click(function(){
            if($('#register_email').val()!=""){
                $('#register_control').html("<img src='objects/icons/loaders/1.gif'/>Kontroller Sağlanıyor...");
                
                $.ajax({
                    type:"POST",
                    url:url+"&act=resend_email",
                    data:"email="+$("#register_email").val(),
                    success:function(data){
                        var status = $(data).find("report").attr("status");
                        var message = $(data).find("report").text();
                        
                        if(status=="ERROR"){
                          $("#register_control").html("<span style='color:#cc0033'><b>"+message+"</b><\/span>");
                          return; 
                        }
                        
                        if(status=="OK"){
                          clearFields();
                          $("#register_control").html("<span style='color:#cc0033'><b>"+message+"</b><\/span>");
                          $('#btnRegister').removeClass("display-none").addClass("display-block");
                          $('#btnResend').removeClass("display-block").addClass("display-none");
                          return; 
                        }
                    }
                    
                })
            }else{
                $("#register_email").focus();
            }
        })
		
	});
    
    function clearFields(){
        $('#register_form')[0].reset();
    }
    
    function getDatas(){
        $.ajax({
            type:"POST",
            url:url+"&act=get_datas",
            data:"",
            success:function(data){
                window.location='<?=CONF_MAIN_PAGE."?pid=".PID?>';
            }
        })
    }
    </script>
    <!-- /login form-->
</html>
