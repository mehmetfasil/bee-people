<?
defined("PASS") or die("Dosya Yok!");
?>
<link href="objects/js/app/vendors/bootstrap-tour/build/css/bootstrap-tour.min.css" rel="stylesheet">

<div class="col-md-4 col-sm-4 col-xs-12">
  <div class="x_panel tile fixed_height_320 overflow_hidden">
    <div class="x_title">
      <h2><i class="glyphicon glyphicon-user"></i> Personel Dağılımı</h2>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <table class="" style="width:100%">
        <tr>
          <td>
            <canvas id="canvas" height="120" width="120" style="margin: 15px 10px 10px 0"></canvas>
          </td>
          <td>
            <table class="tile_info">
              <tr>
                <td>
                  <p><i class="fa fa-square blue"></i>Grafiker </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p><i class="fa fa-square green"></i>Yazılımcı </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p><i class="fa fa-square purple"></i>IK Personeli </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p><i class="fa fa-square aero"></i>Yönetici </p>
                </td>
              </tr>
              <tr>
                <td>
                  <p><i class="fa fa-square red"></i>Diğer </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-12">
    <div class="x_panel fixed_height_320">
        <div class="x_title">
            <h2><i class="glyphicon glyphicon-time"></i> İzin Talepleri</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div id="izin_talepleri">
                <p>Talep Bulunmamaktadır.</p>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-12">
    <div class="x_panel fixed_height_320">
        <div class="x_title">
            <h2><i class="glyphicon glyphicon-ok"></i> Onay Bekleyen İşlemler</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <!--<table class="table table-striped">
            <tbody>
              <tr>
                <td>3 Adet İzin Dilekçesi</td>
              </tr>
              <tr>
                <td>Yeni Personel Ekleme Talebi</td>
              </tr>
              <tr>
                <td>Personel İşten Çıkarma</td>
              </tr>
            </tbody>
          </table>-->
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="col-md-4 col-sm-4 col-xs-12">
    <div class="x_panel fixed_height_390">
        <div class="x_title">
            <h2><i class="glyphicon glyphicon-calendar"></i> Şirket Takvimi</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <table class="table table-striped">
            <tbody>
              <tr>
                <td><b>12:00</b> Toplantı</td>
              </tr>
              <tr>
                <td><b>21 Haziran 2016</b> Tanışma Toplantısı</td>
              </tr>
            </tbody>
          </table>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-12">
    <div class="x_panel fixed_height_390">
        <div class="x_title">
            <h2><i class="glyphicon glyphicon-book"></i> Notlar</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <!--<ul class="to_do">
                          <li>
                            <p>
                              <input type="checkbox" class="flat"> Çocuklar Kreşten Alınacak </p>
                          </li>
                          <li>
                            <p>
                              <input type="checkbox" class="flat"> Müdüre Bilgi Notu</p>
                          </li>
                          <li>
                            <p>
                              <input type="checkbox" class="flat"> Akşam Fenerin Maçı</p>
                          </li>
            </ul>-->
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-4 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="glyphicon glyphicon-cloud"></i> Hava Durumu</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                        <div class="col-sm-12">
                          <div class="temperature"><b>Pazartesi</b>,
                            <span>F</span>
                            <span><b>C</b></span>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-4">
                          <div class="weather-icon">
                            <canvas height="84" width="84" id="partly-cloudy-day"></canvas>
                          </div>
                        </div>
                        <div class="col-sm-8">
                          <div class="weather-text">
                            <h2>Ankara <br><i>Parçalı Bulutlu</i></h2>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="weather-text pull-right">
                          <h3 class="degrees">23</h3>
                        </div>
                      </div>

                      <div class="clearfix"></div>

                      <div class="row weather-days">
                        <div class="col-sm-2">
                          <div class="daily-weather">
                            <h2 class="day">Pzt</h2>
                            <h3 class="degrees">25</h3>
                            <canvas id="clear-day" width="32" height="32"></canvas>
                            <h5>15 <i>km/s</i></h5>
                          </div>
                        </div>
                        <div class="col-sm-2">
                          <div class="daily-weather">
                            <h2 class="day">Sal</h2>
                            <h3 class="degrees">25</h3>
                            <canvas height="32" width="32" id="rain"></canvas>
                            <h5>12 <i>km/s</i></h5>
                          </div>
                        </div>
                        <div class="col-sm-2">
                          <div class="daily-weather">
                            <h2 class="day">Çrş</h2>
                            <h3 class="degrees">27</h3>
                            <canvas height="32" width="32" id="snow"></canvas>
                            <h5>14 <i>km/s</i></h5>
                          </div>
                        </div>
                        <div class="col-sm-2">
                          <div class="daily-weather">
                            <h2 class="day">Prş</h2>
                            <h3 class="degrees">28</h3>
                            <canvas height="32" width="32" id="sleet"></canvas>
                            <h5>15 <i>km/s</i></h5>
                          </div>
                        </div>
                        <div class="col-sm-2">
                          <div class="daily-weather">
                            <h2 class="day">Cu</h2>
                            <h3 class="degrees">28</h3>
                            <canvas height="32" width="32" id="wind"></canvas>
                            <h5>11 <i>km/s</i></h5>
                          </div>
                        </div>
                        <div class="col-sm-2">
                          <div class="daily-weather">
                            <h2 class="day">Cmt</h2>
                            <h3 class="degrees">26</h3>
                            <canvas height="32" width="32" id="cloudy"></canvas>
                            <h5>10 <i>km/s</i></h5>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                      </div>
        </div>
    </div>
</div>

<!-- modals-->
<div class="modal fade izinOnayModal" tabindex="-1" role="dialog" aria-hidden="true" id="izinOnayModal">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
    
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">İzin Onay Formu</h4>
        </div>
        <div class="modal-body">
          <form method="post" id="izinOnayPostForm" name="izinOnayPostForm">
          <input type="hidden" id="izinOnayId" name="izinOnayId"/>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="item form-group">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<b>İzin Türü:&nbsp;</b><span id="izinonay_izinturu"></span>
					</div>
				</div>
                <div class="item form-group">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<b>Başlangıç Tarihi:&nbsp;</b><span id="izinonay_izinbaslangic"></span>
					</div>
				</div>
                <div class="item form-group">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<b>Bitiş Tarihi:&nbsp;</b><span id="izinonay_izinbitis"></span>
					</div>
				</div>
                <div class="item form-group">
					<div class="col-md-12 col-sm-12 col-xs-12">
                        <b>Açıklama:&nbsp;</b><span id="izinonay_izinaciklama"></span>
					</div>
				</div>
                <div class="item form-group">
					<div class="col-md-12 col-sm-12 col-xs-12">
                        <b>Gün Sayısı:&nbsp;</b><span id="izinonay_izincount"></span>
					</div>
				</div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="s_IzinTalep(2)">Reddet</button>
          <button type="button" class="btn btn-primary" id="izinSaveButton" onclick="s_IzinTalep(1)">Onayla</button>
        </div>
    
      </div>
    </div>
    </div>

<!-- Chart.js -->
<script src="objects/js/app/vendors/Chart.js/dist/Chart.min.js"></script>
<!-- Skycons -->
<script src="objects/js/app/vendors/skycons/skycons.js"></script>
<script src="objects/js/app/vendors/bootstrap-tour/build/js/bootstrap-tour.min.js"></script>
<script src="https://unpkg.com/packery@2.1/dist/packery.pkgd.min.js"></script>
<script src="modules/app/modules/dashboard/includes.js"></script>
