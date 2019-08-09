<?
defined("PASS") or die("Dosya Yok!");

?>
<div class="x_panel">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-4 col-xs-12">
            <select name="bordro_yil" id="bordro_yil" class="form-control">
                <option value="2016">2016</option>
            </select>
        </div>
        <div class="col-md-4 col-xs-12">
            <select name="bordro_ay" id="bordro_ay" class="form-control">
                <option value="11">Ekim</option>
            </select>
        </div>
        <div class="col-md-4 col-xs-12">
            <button name="bordroButton" id="bordroButton" type="button" class="btn btn-primary"><i class="glyphicon glyphicon-fire"></i>&nbsp;Bordro Oluştur</button>
        </div>
    </div>
</div>
<!-- list table starts -->
<table id="bordro_table" class="display-none table table-striped table-bordered bulk_action dt-responsive nowrap" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>Kimlik Numarası</th>
      <th>Adı/Soyadı</th>
      <th>Brüt Maaş</th>
      <th>Net Maaş</th>
      <th>Bordro</th>
      <th>İşlemler</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>
<!-- //list table ends -->
<script type="text/javascript" src="modules/app/modules/account_management/bordro/includes.js"></script>