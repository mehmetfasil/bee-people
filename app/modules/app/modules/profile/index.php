<? defined( "PASS") or die( "Dosya Yok!"); ?>
 <!-- morris.js -->
    <script src="objects/js/app/vendors/raphael/raphael.min.js"></script>
    <script src="objects/js/app/vendors/morris.js/morris.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="objects/js/app/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="objects/js/app/src/js/moment/moment.min.js"></script>
    <script src="objects/js/app/src/js/datepicker/daterangepicker.js"></script>
    
	<div class="x_panel">
		<div class="x_title">
			<h2>
				Profil
				<small>
					Detay Bilgileri
				</small>
			</h2>
			<div class="clearfix">
			</div>
		</div>
		<div class="x_content">
			<div class="col-md-3 col-sm-3 col-xs-12 profile_left">
				<div class="profile_img">
					<div id="crop-avatar">
						<!-- Current avatar -->
					<img class="img-responsive avatar-view" src="objects/assets/uploads/user_photos/img.png" width="150" alt="Avatar" title="Kullanıcı Profil Fotoğrafı"/>
					</div>
				</div>
				<h3>
					<?=$_SESSION['SYS_USER_FULLNAME']?>
				</h3>
				<ul class="list-unstyled user_data">
					<li>
						<i class="fa fa-map-marker user-profile-icon">
						</i>
						Yükleniyor...
					</li>
					<li>
						<i class="fa fa-briefcase user-profile-icon">
						</i>
						Software Engineer
					</li>
					<li class="m-top-xs">
						<i class="fa fa-external-link user-profile-icon">
						</i>
						<a href="http://www.kimlabs.com/profile/" target="_blank">www.enginerdogan.net</a>
					</li>
				</ul>
				<a class="btn btn-success" data-toggle="modal" data-target="#passModal"><i class="fa fa-edit m-right-xs"></i>Şifre Değiştir</a>
				
			</div>
			<div class="col-md-9 col-sm-9 col-xs-12">
				<div class="profile_title">
					<div class="col-md-12">
						<h2>
							Puantajınız
						</h2>
					</div>
				</div>
				<!-- start of user-activity-graph -->
				<div id="graph_bar" style="width:100%; height:280px;">
				</div>
				<!-- end of user-activity-graph -->
				<div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                      <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">İzinler</a>
                      </li>
                      <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Ödemeler</a>
                      </li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                      <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                        <!-- start recent activity -->
                            <!-- top tiles -->
                          <div class="row tile_count" align="center">
                            <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
                              <span class="count_top"><i class="fa fa-user"></i> Toplam İzin Günü</span>
                              <div class="count">15</div>
                              <span class="count_bottom"><i class="green">Son bir sene</i></span>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-6 tile_stats_count">
                              <span class="count_top"><i class="fa fa-clock-o"></i> Kalan İzin Günü</span>
                              <div class="count">4</div>
                              <span class="count_bottom"><i class="green">son bir sene</i></span>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-6">
                            <button aria-hidden="true" data-toggle="modal" data-target="#izinModal" type="button" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>&nbsp;İzin Talep Et</button>
                            </div>
                          </div>
                          <div class="row">
                            <h2>İzin Geçmişi</h2>
                            <table id="izin_datatable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr>
                                  <th>İzin Türü</th>
                                  <th>Başlangıç Tarihi</th>
                                  <th>Bitiş Tarihi</th>
                                  <th>Durumu</th>
                                  <th>Yazdır</th>
                                </tr>
                              </thead>
                              <tbody>
                                
                              </tbody>
                            </table>
                          </div>
                        <!-- /top tiles -->
                        <!-- end recent activity -->
                      </div>
                      <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                        <!-- start user projects -->
                        <table class="data table table-striped no-margin" id="odeme_table">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Tarih</th>
                              <th>Ödeme Türü</th>
                              <th class="hidden-phone">Açıklama</th>
                              <th>Tutar</th>
                              <th>İşlem</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1</td>
                              <td>12/09/2916</td>
                              <td>Maaş Ödemesi</td>
                              <td class="hidden-phone"></td>
                              <td>4500 TL</td>
                              <td><button type="button" class="btn btn-sm btn-success"><i class="fa fa-print"></i>Yazdır</button></td>
                            </tr>
                          </tbody>
                        </table>
                        <!-- end user projects -->

                      </div>
                      <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                        <p>xxFood truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui
                          photo booth letterpress, commodo enim craft beer mlkshk </p>
                      </div>
                    </div>
                      </div>
               
			</div>
		</div>
	</div>
    
    <!-- modals-->
    <div class="modal fade izinModal" tabindex="-1" role="dialog" aria-hidden="true" id="izinModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
    
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">İzin Formu</h4>
        </div>
        <div class="modal-body">
          <form method="post" id="izinPostForm" name="izinPostForm">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="item form-group">
					<label class="control-label col-md-4 col-sm-4 col-xs-12" for="izin_turu">
						İzin Türü
                        <span class="required">
							*
						</span>
					</label>
					<div class="col-md-8 col-sm-8 col-xs-12">
						<select class="form-control" name="izin_turu" id="izin_turu">
                            <option value="-1">Yükleniyor...</option>
                        </select>
					</div>
				</div>
                <div class="item form-group">
					<label class="control-label col-md-4 col-sm-4 col-xs-12" for="izin_baslangic_tarihi">
						Başlangıç Tarihi
                        <span class="required">
							*
						</span>
					</label>
					<div class="col-md-8 col-sm-8 col-xs-12">
						<div class="controls">
                              <div class=" xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left"  data-inputmask="'mask': '99/99/9999'" id="izin_baslangic_tarihi"  name="izin_baslangic_tarihi" placeholder="Başlangıç Tarihi" aria-describedby="inputSuccess2Status">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                              </div>
                        </div>
					</div>
				</div>
                <div class="item form-group">
					<label class="control-label col-md-4 col-sm-4 col-xs-12" for="izin_bitis_tarihi">
						Bitiş Tarihi
                        <span class="required">
							*
						</span>
					</label>
					<div class="col-md-8 col-sm-8 col-xs-12">
						<div class="controls">
                              <div class=" xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left"   data-inputmask="'mask': '99/99/9999'" id="izin_bitis_tarihi" name="izin_bitis_tarihi" placeholder="Bitiş Tarihi" aria-describedby="inputSuccess2Status">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status" class="sr-only">(success)</span>
                              </div>
                        </div>
					</div>
				</div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="item form-group">
					<label class="control-label col-md-4 col-sm-4 col-xs-12" for="izin_aciklama">
						Açıklamalar
                        <span class="required">
							*
						</span>
					</label>
					<div class="col-md-8 col-sm-8 col-xs-12">
                        <textarea class="form-control" name="izin_aciklama" id="izin_aciklama"></textarea> 
					</div>
				</div>
                <div class="item form-group">
                    <div style="margin: 5%;" class="col-md-12 col-sm-12 col-xs-12">
					   <span class="blue">Otomatik Hesaplanan  İzin Günü : 0</span>
                    </div>
				</div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
          <button type="button" class="btn btn-primary" id="izinSaveButton">Kaydet</button>
        </div>
    
      </div>
    </div>
</div>

<!-- modals-->
<div class="modal fade passModal" tabindex="-1" role="dialog" aria-hidden="true" id="passModal">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
      <form name="passForm" id="passForm" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Şifre İşlemleri</h4>
        </div>
        <div class="modal-body">
          <input class="form-control" placeholder="Eski Şifre" type="password" id="profile_old_pass" name="profile_old_pass"/>
          <input class="form-control" placeholder="Yeni Şifre" type="password" id="profile_new_pass" name="profile_new_pass"/>
          <input class="form-control" placeholder="Yeni Şifre Tekrar" type="password" id="profile_new_pass_again" name="profile_new_pass_again"/>
          <span id="pass_stat"></span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
          <button type="button" class="btn btn-primary" onclick="changePass()">Kaydet</button>
        </div>
        </form>
      </div>
    </div>
</div>
<!-- // modals-->
 <script type="text/javascript" src="modules/app/modules/profile/includes.js"></script>