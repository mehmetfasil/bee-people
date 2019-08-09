<? defined( "PASS") or die( "Dosya Yok!"); 
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
    	<div class="x_panel">
    		<!-- search text -->
    		<div class="col-sm-6">
    			<div class="input-group">
                    <input type="text" class="form-control" placeholder="Aranacak Personel" aria-label="Text input with dropdown button">
    				<div class="input-group-btn">
    					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    						Filtre
    						<span class="caret">
    						</span>
    					</button>
    					<ul class="dropdown-menu dropdown-menu-right" role="menu">
    						<li>
    							<a>Aktif</a>
    						</li>
    						<li>
    							<a>Ayrılan</a>
    						</li>
    					</ul>
    				</div>
    				<!-- /btn-group -->
    			</div>
    		</div>
    		<div class="col-sm-6 text-right">
                <a href='index.php?pid=<?=menuID("SITE_APP_EMPLOYEE");?>&sid=calisan_ekle' class="btn btn-primary"><i class="fa fa-plus-square"></i> Yeni Personel Ekle    </a>
    		</div>
    	</div>
        <div class="x_panel">
            <!-- list table starts -->
                <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action dt-responsive nowrap" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th><input type="checkbox" id="check-all" class="flat"></th>
                      <th>Fotoğraf</th>
                      <th>Ad</th>
                      <th>Soyad</th>
                      <th>Görevi</th>
                      <th>Telefon</th>
                      <th>TC Kimlik</th>
                      <th>Doğum Tarihi</th>
                      <th>İşlemler</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
            <!-- //list table ends -->
        </div>
    </div>
</div>

<!-- modals-->
<div class="modal fade emp_sum_modal" tabindex="-1" role="dialog" aria-hidden="true" id="empFastModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
    
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Çalışan Görüntüleme</h4>
        </div>
        <div class="modal-body">
          <div id="emp_fast_look">
            <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
              <div class="profile_img">
                <div id="crop-avatar">
                  <!-- Current avatar -->
                  <img class="img-responsive avatar-view" id="ql_personel_foto" style="width: 100px;" src="images/picture.jpg" alt="Avatar" title="Change the avatar">
                </div>
              </div>
              <h3 id="ql_personel_adi"></h3>

              <ul class="list-unstyled user_data">
                <li><i class="fa fa-map-marker user-profile-icon"></i>&nbsp;<span id="ql_personel_adres"></span>
                </li>
                <li>
                  <i class="fa fa-briefcase user-profile-icon"></i>&nbsp;<span id="ql_personel_is"></span>
                </li>

                <li class="m-top-xs">
                  <i class="fa fa-phone"></i>&nbsp;<span id="ql_personel_telefon"></span>
                </li>
              </ul>
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">
              <div class="profile_title">
                <div class="col-md-6">
                  <h4>Özet Bilgi</h4>
                </div>
              </div>
                <table width="100%" class="table table-striped">
                    <tr>
                        <td><label>Görevi</label></td>
                        <td><label id="lbl_personel_gorevi">Yükleniyor...</label></td>
                    </tr>
                    <tr>
                        <td><label>İşe Başlama Tarihi</label></td>
                        <td><label id="lbl_personel_isebaslama">Yükleniyor...</label></td>
                    </tr>
                    <tr>
                        <td><label>Brüt Maaş</label></td>
                        <td><label id="lbl_personel_brutmaas">Yükleniyor...</label></td>
                    </tr>
                    <tr>
                        <td><label>Kimlik Numarası</label></td>
                        <td><label id="lbl_personel_kimlikno">Yükleniyor...</label></td>
                    </tr>
                    <tr>
                        <td><label>Doğum Tarihi</label></td>
                        <td><label id="lbl_personel_dogumtarihi">Yükleniyor...</label></td>
                    </tr>
                    <tr>
                        <td><label>Doğum Yeri</label></td>
                        <td><label id="lbl_personel_dogumyeri">Yükleniyor...</label></td>
                    </tr>
                    <tr>
                        <td><label>Cinsiyet</label></td>
                        <td><label id="lbl_personel_cinsiyet">Yükleniyor...</label></td>
                    </tr>
                    <tr>
                        <td><label>Medeni Durum</label></td>
                        <td><label id="lbl_personel_medenidurum">Yükleniyor...</label></td>
                    </tr>
                    <tr>
                        <td><label>Hesap No</label></td>
                        <td><label id="lbl_personel_hesapno">Yükleniyor...</label></td>
                    </tr>
                </table>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
          <button type="button" class="btn btn-primary" id="printButton">Yazdır</button>
        </div>
    
      </div>
    </div>
</div>
<!--//modals -->
<script type="text/javascript" src="modules/app/modules/employee/includes.js"></script>