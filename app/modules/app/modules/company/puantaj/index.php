<?
defined("PASS") or die("Dosya Yok!");
include(CONF_DOCUMENT_ROOT . "modules" . DS . "app" . DS . "includes.php");
/**
 * @author: Mehmet FASIL
 * @abstract: puantaj
 * @param: corp_id, emp_id, role
 **/
?>
<div>
<div class="x_panel">
    <div align="center" class="row">
        <h2>Puantaj Hazırla</h2>
        <form method="POST" id="puantajForm" enctype="multipart/form-data">
            <div class="col-md-4 col-sm-4 col-xs-4 ">
            <input type="text" class="form-control" placeholder="Başlangıç Tarihi" data-inputmask="'mask': '99/99/9999'" name="puantaj_start" id="puantaj_start"/>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4 ">
            <input type="text" class="form-control" placeholder="Başlangıç Tarihi" data-inputmask="'mask': '99/99/9999'" name="puantaj_end" id="puantaj_end"/>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4 ">
            <button class="btn btn-success" type="button" id="getPuantaj" name="getPuantaj">Getir</button>
            </div>
        </form>
    </div>
</div>

<script src="modules/app/modules/company/puantaj/includes.js"></script>
<div class="form-group">
    <table class="table table-striped table-bordered bulk_action dt-responsive nowrap" id="result"></table>
</div>

</div>