<?
defined("PASS") or die("Dosya Yok!");
include_once (CONF_DOCUMENT_ROOT . "modules" . DS . "app" . DS . "includes.php");

$empId = getvalue("i");
if($empId!=""){
    //update record
    //check if user allowed to edit that record.
    $sql_select_app_employee = "SELECT emp_name FROM ".$table_app_employee." WHERE id='".$empId."' AND firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and is_active='1' ";
    $select_app_employee = new query($sql_select_app_employee);
    if($select_app_employee->numrows()>0){
        ?>
        <script>var ee = '<?=$empId?>';</script>
        <?
    }else{
        //hack.reload
        header("Location:index.php");
    }
}else{
    //hack.reload
    header("Location:index.php");
}
?>

<div class="alert alert-success alert-dismissible fade in" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4>Lütfen Dikkat!</h4>
    Personeli İşden Çıkarma İşlemlerini Tamamladığınızda;
    <ul>
        <li>Kişi E2ye giriş yapamaz.</li>
        <li>İşletmenizde Kayıtlı Tüm özlük dosyaları muhafaza edilir.</li>
        <li>Kişinin E2 üzerinde sahip olduğu tüm yetkiler kaldırılır.</li>
        <li>Kişi işden ayrılan personeliniz olarak personel kayıtlarınızda muhafaza edilir.</li>
        <li>Kişinin aktif izin-zam vb. talepleri iptal edilir.</li>
        <li>Kişinin varsa sistemde kayıtlı yöneticilikleri iptal edilir.</li>
    </ul>
</div>
                  
<form id="EmpDismissForm" name="EmpDismissForm" method="POST">
    <div class="item form-group">
		<label class="control-label col-md-4 col-sm-4 col-xs-12" for="emp_dismiss_reason">
			İşden Çıkarma Sebebi
            <span class="required">
				*
			</span>
		</label>
		<div class="col-md-8 col-sm-8 col-xs-12">
			<select class="form-control item form-group" name="emp_dismiss_reason" id="emp_dismiss_reason">
                <option value="-1">Yükleniyor...</option>
            </select>
		</div>
	</div>
    <div class="item form-group">
    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="emp_dismiss_reason">
			Açıklama
		</label>
		<div class="col-md-8 col-sm-8 col-xs-12">
			<textarea name="emp_dismiss_desc" id="emp_dismiss_desc" class="form-control"></textarea>
		</div>
	</div>
    
    <div class="item form-group">
		<div class="col-md-12 col-sm-12 col-xs-12">
        <br />
			<button type="button" name="emp_dismiss_button" id="emp_dismiss_button" class="btn btn-primary pull-right">Personeli İşden Çıkar</button>
		</div>
	</div>
</form>
 <script type="text/javascript" src="modules/app/modules/employee/includes_dismiss.js"></script>

