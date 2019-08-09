<?
defined("PASS") or die("Dosya Yok!");
?>


<div class="alert alert-success alert-dismissible fade in" role="alert">
<button type="button" class="pull-right btn btn-lg btn-success">Şablonu İndir</button>
    <strong><h3>Toplu İşlem Merkezimize hoşgeldiniz.</h3> <br/>İşlerinizi kolaylaştırmak için burdayız.</strong>
    <p>Lütfen aşağıdaki adımları sırasıyla takip edin.</p>
    <ul>
        <li>Öncelikle şuradan toplu çalışan ekleme şablonumuzu indirin.</li>
        <li>Şablona uygun olarak personellerinizi altalta ekleyin.</li>
        <li>Sonra da dosyanızı tekrar aşağıdaki yükleme menüsünden E2ye yükleyin.</li>
        <li>Bu kadar. Sizin için onlarca personelinizi sistemimize kaydettik bile:) </li>
    </ul>
    <br />
    <button type="button" class="btn btn-lg btn-primary">Örnek Video İzle</button>
    <button type="button" class="btn btn-lg btn-warning" name="dosya_yukle" id="dosya_yukle">Dosya Yükle</button>
    <form method="post" id="bulkForm" name="bulkForm" action="index.php?pid=<?=menuID("SITE_APP_CORP_BULK_EMPLOYEE")?>&sid=upload" enctype="multipart/form-data" target="upload_target">
    <input type="file" name="toplu_islem" id="toplu_islem" class="hidden"/>
    </form>
</div>
<div>
    <iframe id="upload_target" name="upload_target" src="index.php?pid=<?=menuID("SITE_APP_CORP_BULK_EMPLOYEE")?>&sid=upload" style="width: 100%;height:100px;border:0px;" class="display-none"></iframe>
</div>
 <script type="text/javascript" src="modules/app/modules/bulk_action/employee/includes.js"></script>