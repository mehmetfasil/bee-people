<? defined( "PASS") or die( "Dosya Yok!");
include_once (CONF_DOCUMENT_ROOT . "modules" . DS . "app" . DS . "includes.php");
//firstly, an employee will be added quickly. and then detail screens will be enable to insert details

$empId = getvalue("i");
if($empId!=""){
    //update record
    //check if user allowed to edit that record.
    $sql_select_app_employee = "SELECT emp_name FROM ".$table_app_employee." WHERE id='".$empId."' AND firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' ";
    $select_app_employee = new query($sql_select_app_employee);
    if($select_app_employee->numrows()>0){
        ?>
        <script>var e = '<?=$empId?>';</script>
        <?
    }else{
        //hack.reload
        header("Location:index.php");
    }
}else{
}


?>
	<!-- Select2 -->
	<link href="objects/js/app/vendors/select2/dist/css/select2.min.css" rel="stylesheet"/>
    
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
				
                <!-- photo -->
                <?
                if($empId!=""){
                    ?>
                    <div class="x_panel" align="center">
                        <div class="col-md-6 col-xs-12">
                            <img src="objects/icons/no-photo.png" class="img-circle emp_image" id="empPhoto"/>
                            <br />
                            <br />
                            <span class="glyphicon glyphicon-upload pointer" aria-hidden="true" data-toggle="modal" data-target=".bs-example-modal-lg"></span>&nbsp;&nbsp;
                            <span class="glyphicon  glyphicon-remove-circle pointer" aria-hidden="true" data-toggle="modal" data-target=".deleteModal"></span>&nbsp;&nbsp;
                            <a class="glyphicon  glyphicon-download-alt pointer" aria-hidden="true" href="index.php?pid=<?=menuID("SITE_DOWNLOAD")?>&file=e2box/ROOT/FIRMS/32/EMP/31/31.jpg"></a>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <h3 id="empNameToInform"></h3>
                            <h2 id="empJobToInform"></h2>
                            <div class="col-md-12 col-xs-12">
                                <a href="tel:905546819955" class="btn btn-app">
                                    <i class="fa fa-phone-square"></i>
                                    Ara
                                </a>
                                <a class="btn btn-app" aria-hidden="true" data-toggle="modal" data-target="#belgelerModal">
                                    <i class="fa fa-envelope"></i>
                                    Belgeler
                                </a>
                                <a  class="btn btn-app" id="editToggle">
                                    <i class="fa fa-edit"></i>
                                    Düzenle
                                </a>
                                <a  class="btn btn-app" href="index.php?pid=<?=menuID("SITE_APP_EMPLOYEE")?>&sid=emp_dismiss&i=<?=$empId?>">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    İşden Çıkar
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- modals -->
                    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="PhotoModal">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
    
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                              </button>
                              <h4 class="modal-title" id="myModalLabel">E2 Media Yükleyici</h4>
                            </div>
                            <div class="modal-body">
                              <h4>Yüklenecek Resmi Seçiniz</h4>
                              <p>Eski personel fotoğrafı sistemden silineccektir.</p>
                              
                                <form id="imagePost" action="index.php?pid=<?=menuID("SITE_APP_EMPLOYEE")?>&sid=po" method="post" enctype="multipart/form-data" target="upload_target">
                                    <input class="form-control" type="file" name="emp_photo" id="emp_photo"/>
                                    <input type="hidden" id="ef" name="ef" value="<?=$empId?>"/>
                                    <iframe id="upload_target" name="upload_target" src="index.php?pid=<?=menuID("SITE_APP_EMPLOYEE")?>&sid=po" style="width: 100%;height:50px;border:0px;" class="display-none"></iframe>
                                </form>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                              <button type="button" class="btn btn-primary" id="ImageUploadButton">Yükle</button>
                            </div>
    
                          </div>
                        </div>
                    </div>
                    
                    <div class="modal fade deleteModal" tabindex="-1" role="dialog" aria-hidden="true" id="ensureModal">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
    
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                              </button>
                              <h4 class="modal-title" id="myModalLabel">İşlem</h4>
                            </div>
                            <div class="modal-body">
                              <h4>Personel Fotoğrafı</h4>
                              <p>Sistemden silinecektir. Onaylıyor musunuz?</p>
                              
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Hayır</button>
                              <button type="button" class="btn btn-primary" onclick="dPpicture()">Evet</button>
                            </div>
    
                          </div>
                        </div>
                    </div>
                    <div class="modal fade belgelerModal" tabindex="-1" role="dialog" aria-hidden="true" id="belgelerModal">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
    
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                              </button>
                              <h4 class="modal-title" id="myModalLabel">Belgeler</h4>
                            </div>
                            <div class="modal-body">
                              <ul>
                                <li><a href="index.php?pid=<?=menuID("SITE_APP_EMPLOYEE")?>&sid=file_wp">Sözleşme</a></li>
                                <li>Yönetmelik</li>
                                <li>İzin Talep Formu</li>
                                <li>Savunma Formu</li>
                                <li>Uyarı Formu</li>
                                <li>İstifa Mektubu</li>
                                <li>Çıkış Mülakat Formu</li>
                              </ul>
                              
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                            </div>
    
                          </div>
                        </div>
                    </div>
                    
                    <!-- // modals -->
                    <div class="x_content" id="empExtras">
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <div id="div_personel_detay_tabs">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#tab-dosya" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-file">&nbsp;</i><span class="personel_tabs_dosyalar">Dosyaları</span></a>
                                    </li>
                                    <!--<li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-coffee">&nbsp;</i><span class="personel_tabs_izinler">İzin</span></a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false"><i class="fa fa-key">&nbsp;</i><span class="personel_tabs_zimmetler">Zimmetler</span></a>
                                    </li>-->
                                    <li role="presentation" class=""><a href="#tab_deneyim" role="tab" id="deneyim-tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-history">&nbsp;</i><span class="personel_tabs_isdeneyimleri">İş Deneyimi</span></a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_referanslar" role="tab" id="referans-tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-envelope">&nbsp;</i><span class="personel_tabs_referanslar">Referanslar</span></a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_egitim" role="tab" id="egitim-tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-graduation-cap">&nbsp;</i><span class="personel_tabs_egitimler">Eğitimler</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab-dosya" aria-labelledby="home-tab">
                                  <div class="x_panel" align="center">
                                    <div class="x_title">
                						<h2>
                							Dosyaları
                							<small>
                								cv, özlük vb.
                							</small>
                						</h2>
                						<ul class="nav navbar-right panel_toolbox">
                							<li>
                								<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                							</li>
                						</ul>
                						<div class="clearfix">
                						</div>
            					   </div>
                                   <div class="x_content">
                                       <div class="col-md-6 col-xs-12">
                                        <h2>Kayıtlı Dosyalar</h2>
                                        <div align="left">
                                            <ul class="to_do" id="file_list">
                                              
                                            </ul>
                                        </div>
                                       </div>
                                       <div class="col-md-6 col-xs-12">
                                        <h2>Yeni Dosya Ekle</h2>
                                        <form id="filePostForm" action="index.php?pid=<?=menuID("SITE_APP_UPLOAD")?>" method="post" enctype="multipart/form-data" target="file_upload_frame">
                                            <input type="hidden" id="uploadId" name="uploadId" value="<?=$empId?>"/>
                                            <select class="form-control item form-group" name="fileUploadType" id="fileUploadType">
                                                <option value="-1">Yükleniyor...</option>
                                            </select>
                                            <input type="text" class="form-control item form-group" id="fileUploadName" name="fileUploadName" placeholder="Dosya Adı (Örn: İzin Dilekçesi)"/>
                                            <input type="file" class="form-control item form-group" name="fileUploadFile" placeholder="Dosyayı Seçiniz"/>
                                            <button type="button" id="fileUploadButton" name="fileUploadButton" class="btn btn-primary">Kaydet</button>
                                        </form>
                                        <iframe id="file_upload_frame" name="file_upload_frame" src="index.php?pid=<?=menuID("SITE_APP_UPLOAD")?>" style="width: 100%;height:50px;border:0px;" class="display-none"></iframe>
                                       </div>
                                   </div>
                                </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_deneyim" aria-labelledby="profile-tab">
                                  <div class="x_panel">
                                    <div class="x_content">
                                        <div class="col-md-6 col-xs-12">
                                            <h2>Kayıtlar</h2>
                                            <ul class="to_do" id="experience_list">
                                              
                                            </ul>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <h2 align="center">Yeni Ekle</h2>
                                            <form class="form-horizontal form-label-left" id="experiencePostForm" method="post" enctype="multipart/form-data" novalidate data-parsley-validate>
                                                <input type="hidden" id="exp_rid" name="exp_rid"/>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="deneyim_firma_adi">
                        								Firma İsmi
                                                        <span class="required">
                        									*
                        								</span>
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<input type="text" class="form-control item form-group" id="deneyim_firma_adi" name="deneyim_firma_adi" placeholder="Örn: MGA " required="required"/>
                        							</div>
                        						</div>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="deneyim_firma_ili">
                        								İli
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<select class="form-control" name="deneyim_firma_ili" id="deneyim_firma_ili">
                                                            <option value="-1">Yükleniyor...</option>
                                                        </select>
                        							</div>
                        						</div>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="deneyim_firma_ilcesi">
                        								İlçesi
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<select class="form-control" name="deneyim_firma_ilcesi" id="deneyim_firma_ilcesi">
                                                            <option value="-1">Seçiniz...</option>
                                                        </select>
                        							</div>
                        						</div>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="deneyim_bitis">
                        								Başlangıç Tarihi
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="deneyim_baslangic" id="deneyim_baslangic"/>
                        							</div>
                        						</div>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="deneyim_bitis">
                        								Bitiş Tarihi
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="deneyim_bitis" id="deneyim_bitis"/>
                        							</div>
                        						</div>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="deneyim_pozisyon">
                        								Pozisyon
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<input type="text" class="form-control"  name="deneyim_pozisyon" id="deneyim_pozisyon"/>
                        							</div>
                        						</div>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="deneyim_ayrilma_nedeni">
                        								Ayrılma Nedeni
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<input type="text" class="form-control"  name="deneyim_ayrilma_nedeni" id="deneyim_ayrilma_nedeni"/>
                        							</div>
                        						</div>
                                                <div class="item form-group" align="center">
                                                    <button type="button" id="deneyimKayitButton" name="deneyimKayitButton" class="btn btn-primary">Kaydet</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_referanslar" aria-labelledby="profile-tab">
                                  <div class="x_panel">
                                    <div class="x_content">
                                        <div class="col-md-6 col-xs-12">
                                            <h2>Kayıtlar</h2>
                                            <ul class="to_do" id="reference_list">
                                              
                                            </ul>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <h2 align="center">Yeni Ekle</h2>
                                            <form class="form-horizontal form-label-left" id="referencePostForm" method="post" enctype="multipart/form-data">
                                                <input type="hidden" id="reference_rid" name="reference_rid"/>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="egitim_turu">
                        								Referans Adı/Soyadı
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<input type="text" class="form-control item form-group" id="referans_adisoyadi" name="referans_adisoyadi" placeholder="Örn: Mehmet FASIL"/>
                        							</div>
                        						</div>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="egitim_turu">
                        								Kurum Adı
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<input type="text" class="form-control item form-group" id="referans_kurumadi" name="referans_kurumadi" placeholder="Örn: MGA"/>
                        							</div>
                        						</div>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="egitim_turu">
                        								Görevi
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<input type="text" class="form-control item form-group" id="referans_gorevi" name="referans_gorevi" placeholder="Örn: IK Uzmanı"/>
                        							</div>
                        						</div>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="egitim_turu">
                        								Adresi
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<input type="text" class="form-control item form-group" id="referans_adresi" name="referans_adresi" placeholder="Örn: Çankaya, Ankara"/>
                        							</div>
                        						</div>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="egitim_turu">
                        								Telefonu
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<input type="text" data-inputmask="'mask' : '(999) 999-9999'" class="form-control item form-group" id="referans_telefon" name="referans_telefon" placeholder="555 666 7788"/>
                        							</div>
                        						</div>
                                                <div class="item form-group" align="center">
                                                    <button type="button" id="referansKayitButton" name="referansKayitButton" class="btn btn-primary">Kaydet</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_egitim" aria-labelledby="egitim-tab">
                                  <div class="x_panel">
                                    <div class="x_content">
                                        <div class="col-md-6 col-xs-12">
                                        <h2>Kayıtlar</h2>
                                        <div align="left">
                                            <ul class="to_do" id="education_list">
                                              
                                            </ul>
                                        </div>
                                       </div>
                                       <div class="col-md-6 col-xs-12">
                                        <h2 align="center">Yeni Ekle</h2>
                                        <form class="form-horizontal form-label-left" id="educationPostForm" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="egitim_id" id="egitim_id"/>
                                            <input type="hidden" name="egitim_rid" id="egitim_rid"/>
                                            <div class="item form-group">
                    							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="egitim_turu">
                    								Eğitim Türü
                    							</label>
                    							<div class="col-md-6 col-sm-6 col-xs-12">
                    								<select class="form-control item form-group" name="egitim_turu" id="egitim_turu">
                                                        <option value="-1">Seçiniz</option>
                                                        <option value="1">Üniversite</option>
                                                        <option value="2">Lise</option>
                                                        <option value="3">İlk/OrtaOkul</option>
                                                    </select>
                    							</div>
                    						</div>
                                            <div class="item form-group">
                    							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="egitim_baslangic">
                    								Başlangıç Tarihi
                    							</label>
                    							<div class="col-md-6 col-sm-6 col-xs-12">
                    								<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="egitim_baslangic" id="egitim_baslangic"/>
                    							</div>
                    						</div>
                                            <div class="item form-group">
                    							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="egitim_bitis">
                    								Bitiş Tarihi
                    							</label>
                    							<div class="col-md-6 col-sm-6 col-xs-12">
                    								<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="egitim_bitis" id="egitim_bitis"/>
                    							</div>
                    						</div>
                                            <div class="item form-group">
                    							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="egitim_durumu">
                    								Durumu
                    							</label>
                    							<div class="col-md-6 col-sm-6 col-xs-12">
                    								<select class="form-control item form-group" name="egitim_durumu" id="egitim_durumu">
                                                        <option value="1">Tamamlandı</option>
                                                        <option value="2">Devam Ediyor</option>
                                                        <option value="3">Terk</option>
                                                    </select>
                    							</div>
                    						</div>
                                            <div class="item form-group">
                    							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="egitim_okul_adi">
                    								Okul Adı
                    							</label>
                    							<div class="col-md-6 col-sm-6 col-xs-12">
                    								<input type="text" class="form-control item form-group" id="egitim_okul_adi" name="egitim_okul_adi" placeholder="Örn: Hacettepe Üniversitesi "/>
                    							</div>
                    						</div>
                                            <div class="item form-group">
                    							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="egitim_okul_ulke">
                    								Ülke
                    							</label>
                    							<div class="col-md-6 col-sm-6 col-xs-12">
                    								<select class="form-control item form-group" name="egitim_okul_ulke" id="egitim_okul_ulke">
                                                        <option value="-1">Seçiniz</option>
                                                        <option value="1">Türkiye</option>
                                                    </select>
                    							</div>
                    						</div>
                                            <div class="item form-group">
                    							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="egitim_okul_il">
                    								İl
                    							</label>
                    							<div class="col-md-6 col-sm-6 col-xs-12">
                    								<select class="form-control item form-group" name="egitim_okul_il" id="egitim_okul_il">
                                                        <option value="-1">Seçiniz</option>
                                                    </select>
                    							</div>
                    						</div>
                                            <div class="item form-group">
                    							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="egitim_bolum_adi">
                    								Bölüm Adı
                    							</label>
                    							<div class="col-md-6 col-sm-6 col-xs-12">
                    								<input type="text" class="form-control item form-group" id="egitim_bolum_adi" name="egitim_bolum_adi" placeholder="Örn: İşletme, Bilg. Mühendisliği "/>
                    							</div>
                    						</div>
                                            <div class="item form-group">
                    							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="egitim_mezuniyet_derecesi">
                    								Mezuniyet Derecesi
                    							</label>
                    							<div class="col-md-6 col-sm-6 col-xs-12">
                    								<input type="text" class="form-control item form-group" id="egitim_mezuniyet_derecesi" name="egitim_mezuniyet_derecesi" placeholder="Örn: 84 veya 2.3 "/>
                    							</div>
                    						</div>
                                            <div class="item form-group" align="center">
                                                <button type="button" id="egitimKayitButton" name="egitimKayitButton" class="btn btn-primary">Kaydet</button>
                                            </div>
                                        </form>
                                       </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="display-none" id="editToggleDiv">
                    <?
                    
                }
                ?>
                <form class="form-horizontal form-label-left" id="employee_fast_form" name="employee_fast_form" novalidate data-parsley-validate>
				<input type="hidden" id="id" name="id"/>                
                <!-- genel bilgiler -->
				<div class="x_panel">
					<div class="x_title">
						<h2>
							Genel Bilgiler
							<small>
								personel bilgileri
							</small>
						</h2>
						<ul class="nav navbar-right panel_toolbox">
							<li>
								<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</li>
						</ul>
						<div class="clearfix">
						</div>
					</div>
					<div class="x_content">
                        <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="yetki_turu">
								Yetki Türü
								<span class="required">
									*
								</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="yetki_turu" id="yetki_turu" class="form-control">
                                    <option value="-1">Yükleniyor...</option>
                                </select>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tckimlikno">
								Tc / Yabancı Kimlik No
								<span class="required">
									*
								</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="tckimlikno" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99999999999'" data-validate-length-range="11" data-validate-words="1" name="tckimlikno" placeholder="Tc veya Yabancı Kimlik No" required="required" type="text">
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="isim">
								Ad
								<span class="required">
									*
								</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="isim" class="form-control col-md-7 col-xs-12" data-validate-length-range="50" data-validate-words="1" name="isim" placeholder="Ad" required="required" type="text">
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="soyisim">
								Soyad
								<span class="required">
									*
								</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="soyisim" class="form-control col-md-7 col-xs-12" data-validate-length-range="50" data-validate-words="1" name="soyisim" placeholder="Soyad" required="required" type="text">
							</div>
						</div>
                        <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="dogum_tarihi">
								Doğum Tarihi
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'" name="dogum_tarihi" id="dogum_tarihi"/>
							</div>
						</div>
                        <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="dogum_yeri">
								Doğum Yeri
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="dogum_yeri" class="form-control col-md-7 col-xs-12" name="dogum_yeri" placeholder="Doğum Yeri" type="text">
							</div>
						</div>
						
					</div>
				</div>
                <!-- genel bilgiler -->
                <!-- iletisim bilgileri -->
				<div class="x_panel">
					<div class="x_title">
						<h2>
							İletişim Bilgileri
							<small>
								email,cep telefonu
							</small>
						</h2>
						<ul class="nav navbar-right panel_toolbox">
							<li>
								<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</li>
						</ul>
						<div class="clearfix">
						</div>
					</div>
					<div class="x_content">
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="telefon">
								Telefon Numarası
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="telefon" data-inputmask="'mask' : '(999) 999-9999'" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="telefon" placeholder="Telefon Numarası" type="text">
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gsm_number">
								GSM Numarası
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="gsm_number" data-inputmask="'mask' : '(999) 999-9999'" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="gsm_number" placeholder="GSM Numarası" type="text">
							</div>
						</div>
                        <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="eposta">
								E-Posta
                                <span class="required">
									*
								</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="eposta" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="eposta" placeholder="E-mail" required="required"  type="text">
							</div>
						</div>
					</div>
				</div>
				<!-- //iletisim bilgileri son -->
				<!-- adres bilgileri -->
				<div class="x_panel">
					<div class="x_title">
						<h2>
							Adres Bilgileri
							<small>
								ikamet,açık adres
							</small>
						</h2>
						<ul class="nav navbar-right panel_toolbox">
							<li>
								<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</li>
						</ul>
						<div class="clearfix">
						</div>
					</div>
					<div class="x_content">
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ikamet_ulke">
								Ülkesi
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class=" form-control" name="ikamet_ulke" id="ikamet_ulke" tabindex="-1"><!-- select2_single -->
									<option>
									</option>
									<option value="AK">
										Alaska
									</option>
									<option value="HI">
										Hawaii
									</option>
									<option value="CA">
										California
									</option>
									<option value="NV">
										Nevada
									</option>
									<option value="OR">
										Oregon
									</option>
									<option value="WA">
										Washington
									</option>
									<option value="AZ">
										Arizona
									</option>
									<option value="CO">
										Colorado
									</option>
									<option value="ID">
										Idaho
									</option>
									<option value="MT">
										Montana
									</option>
									<option value="NE">
										Nebraska
									</option>
									<option value="NM">
										New Mexico
									</option>
								</select>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ikamet_il">
								İkamet İli
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="ikamet_il" id="ikamet_il" class="form-control">
									<option value="1">
										Seçiniz
									</option>
								</select>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ikamet_ilce">
								İkamet İlçesi
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class="form-control" name="ikamet_ilce" id="ikamet_ilce">
									<option value="-1">
										Seçiniz
									</option>
								</select>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="adres">
								İkamet Adresi (En az 20 karakter)
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<textarea id="adres" class="form-control" name="adres"></textarea>
							</div>
						</div>
					</div>
				</div>
				<!-- //adres bilgileri son -->
                <!-- diger bilgiler -->
				<div class="x_panel">
					<div class="x_title">
						<h2>
							Diğer Bilgileri
							<small>
								askerlik,uyruk
							</small>
						</h2>
						<ul class="nav navbar-right panel_toolbox">
							<li>
								<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</li>
						</ul>
						<div class="clearfix">
						</div>
					</div>
					<div class="x_content">
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="uyruk">
								Uyruk
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class=" form-control" name="uyruk" id="uyruk" tabindex="-1"><!-- select2_single -->
									<option>
									</option>
									<option value="AK">
										Alaska
									</option>
									<option value="HI">
										Hawaii
									</option>
									<option value="CA">
										California
									</option>
									<option value="NV">
										Nevada
									</option>
									<option value="OR">
										Oregon
									</option>
									<option value="WA">
										Washington
									</option>
									<option value="AZ">
										Arizona
									</option>
									<option value="CO">
										Colorado
									</option>
									<option value="ID">
										Idaho
									</option>
									<option value="MT">
										Montana
									</option>
									<option value="NE">
										Nebraska
									</option>
									<option value="NM">
										New Mexico
									</option>
								</select>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cinsiyeti">
								Cinsiyeti
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="cinsiyeti" id="cinsiyeti" class="form-control">
									<option value="1">
										Bayan
									</option>
                                    <option value="2">
										Erkek
									</option>
								</select>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="askerlik_durumlari">
								Askerlik Durumu
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select class="form-control" name="askerlik_durumlari" id="askerlik_durumlari">
									<option value="1">Yapılmadı</option>
                                    <option value="2">Yapıldı</option>
                                    <option value="3">Muaf</option>
                                    <option value="4">Tecilli</option>
								</select>
							</div>
						</div>
						<div class="item form-group display-none" id="military_3">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="muaf_neden">
								Muaf Nedeni
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="muaf_neden" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="muaf_neden" placeholder="Muaflık Nedeni"  type="text">
							</div>
						</div>
                        <div class="item form-group display-none" id="military_4">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="tecil_tarihi">
								Tecil Tarihi
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="tecil_tarihi" data-inputmask="'mask': '99/99/9999'" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="tecil_tarihi" placeholder="Tecil Tarihi"  type="text">
							</div>
						</div>
                        <div class="item form-group display-none" id="military_2">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="terhis_tarihi">
								Terhis Tarihi
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="terhis_tarihi" data-inputmask="'mask': '99/99/9999'" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="terhis_tarihi" placeholder="Terhis Tarihi"  type="text">
							</div>
						</div>
                        
					</div>
				</div>
				<!-- //diger bilgiler son -->
                <!-- aile bilgileri -->
                <div class="x_panel">
					<div class="x_title">
						<h2>
							Aile Bilgileri
							<small>
								çocuk,anne-baba
							</small>
						</h2>
						<ul class="nav navbar-right panel_toolbox">
							<li>
								<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</li>
						</ul>
						<div class="clearfix">
						</div>
					</div>
					<div class="x_content">
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="medeni_durum">
								Medeni Durum
								
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="medeni_durum" id="medeni_durum" class="form-control">
									<option value="1">
										Evli
									</option>
                                    <option value="2">
										Bekar
									</option>
                                    <option value="3">
										Diğer
									</option>
								</select>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cocuk_sayisi">
								Çocuk Sayısı
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="cocuk_sayisi" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" data-validate-words="1" name="cocuk_sayisi" placeholder="Çocuk Sayısı" type="number">
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="es_calisma_durumu">
								Eş Çalışma Durumu
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="es_calisma_durumu" id="es_calisma_durumu" class="form-control">
									<option value="1" selected="selected">
										Çalışmıyor
									</option>
                                    <option value="2">
										Çalışıyor
									</option>
								</select>
							</div>
						</div>
                        <div class="item form-group display-none" id="es_isyeri_adi_div">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="es_isyeri_adi">
								İşyeri Adı
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" class="form-control col-md-7 col-xs-12" name="es_isyeri_adi" placeholder="İşyeri Adı" id="es_isyeri_adi" />
							</div>
						</div>
                        <div class="item form-group display-none" id="es_isyeri_gorevi_div">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="es_gorevi">
								Görevi
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" class="form-control col-md-7 col-xs-12" name="es_gorevi" placeholder="Görevi" id="es_gorevi" />
							</div>
						</div>
                        <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="anne_adi">
								Anne Adı
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" class="form-control col-md-7 col-xs-6" name="anne_adi" placeholder="Anne Adı" id="anne_adi" />
							</div>
						</div>
                        <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="anne_meslegi">
								Anne Mesleği
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" class="form-control col-md-7 col-xs-6" name="anne_meslegi" placeholder="Anne Mesleği" id="anne_meslegi" />
							</div>
						</div>
                        <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="baba_adi">
								Baba Adı
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" class="form-control col-md-7 col-xs-6" name="baba_adi" placeholder="Anne Adı" id="baba_adi" />
							</div>
						</div>
                        <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="baba_meslegi">
								Baba Mesleği
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" class="form-control col-md-7 col-xs-6" name="baba_meslegi" placeholder="Baba Mesleği" id="baba_meslegi" />
							</div>
						</div>
						
					</div>
				</div>
                <!-- //aile bilgileri son -->
                <!-- hesap bilgileri -->
                <div class="x_panel">
					<div class="x_title">
						<h2>
							Hesap Bilgileri
							<small>
								IBAN,maaş
							</small>
						</h2>
						<ul class="nav navbar-right panel_toolbox">
							<li>
								<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</li>
						</ul>
						<div class="clearfix">
						</div>
					</div>
					<div class="x_content">
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="personelin_gorevi">
								Personelin Görevi
								
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="personelin_gorevi" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" data-validate-words="1" name="personelin_gorevi" placeholder="Personelin Görevi" type="text">
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ise_baslama_tarihi">
								İşe Başlama Tarihi
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="ise_baslama_tarihi" class="form-control col-md-7 col-xs-12"  data-inputmask="'mask': '99/99/9999'" data-validate-length-range="2" data-validate-words="1" name="ise_baslama_tarihi" placeholder="İşe Başlama Tarihi" type="text">
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="net_maas">
								Net Maaş
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="net_maas" class="form-control col-md-7 col-xs-12"  data-validate-length-range="2" data-validate-words="1" name="net_maas" placeholder="Net Maaş" type="text">
							</div>
						</div>
                        <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="yemek_ucreti">
								Yemek Ücreti
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="yemek_ucreti" class="form-control col-md-7 col-xs-12"  data-validate-length-range="2" data-validate-words="1" name="yemek_ucreti" placeholder="Yemek Ücreti" type="text">
							</div>
						</div>
                        <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="yol_ucreti">
								Yol Ücreti
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="yol_ucreti" class="form-control col-md-7 col-xs-12"  data-validate-length-range="2" data-validate-words="1" name="yol_ucreti" placeholder="Yol Ücreti" type="text">
							</div>
						</div>
                        <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="brut_ucret">
								Brüt Ücret
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="brut_ucret" class="form-control col-md-7 col-xs-12"  data-validate-length-range="2" data-validate-words="1" name="brut_ucret" placeholder="Brüt Ücret" type="text">
							</div>
						</div>
                        <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="hesap_no">
								Hesap No
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="hesap_no" class="form-control col-md-7 col-xs-12"  data-validate-length-range="2" data-validate-words="1" name="hesap_no" placeholder="Hesap No" type="text">
							</div>
						</div>
                        <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sube_kodu">
								Şube Kodu
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="sube_kodu" class="form-control col-md-7 col-xs-12"  data-validate-length-range="2" data-validate-words="1" name="sube_kodu" placeholder="Şube Kodu" type="text">
							</div>
						</div>
                        <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="iban">
								IBAN
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="iban" class="form-control col-md-7 col-xs-12"  data-validate-length-range="2" data-validate-words="1" name="iban" placeholder="IBAN Numarası" type="text">
							</div>
						</div>
                        <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ehliyet">
								Ehliyet
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="ehliyet" id="ehliyet" class="form-control">
                                    <option value="1">YOK</option>
                                    <option value="2">VAR</option>
                                </select>
							</div>
						</div>
                        <div class="item form-group display-none" id="ehliyet_1">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ehliyet_sinifi">
								Ehliyet Sınıfı
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="ehliyet_sinifi" id="ehliyet_sinifi" class="form-control">
                                    <option value="1">A2</option>
                                    <option value="2">B</option>
                                    <option value="2">E</option>
                                </select>
							</div>
						</div>
                        <div class="item form-group display-none" id="ehliyet_2">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ehliyet_yili">
								Ehliyet Yılı
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="ehliyet_yili" class="form-control col-md-7 col-xs-12"  data-validate-length-range="2" data-validate-words="1" name="ehliyet_yili" placeholder="Ehliyet Yılı" type="text">
							</div>
						</div>
					</div>
				</div>
                <!-- //hesap bilgileri son -->
				<!-- save button -->
				<div class="form-group" align="right">
					<div class="col-md-9">
						<button type="button" class="btn btn-success btn-lg" id="btn_save" name="btn_save">
							Kaydet
						</button>
					</div>
				</div>
				<!-- //save button -->
			</form>
            <?
            if($empId!=""){
                ?>
                </div><!-- for display-none block -->
            <?
            }
            ?>
		</div>
	</div>
    
    <!-- modals -->
    
    
    <!-- module javascript file -->
    <script type="text/javascript" src="modules/app/modules/employee/includes_new_emp.js"></script>
    
	<!-- validator -->
	
	<!-- /validator -->
	<!-- Select2 -->
	<script>
      $(document).ready(function() {
        $(".select2_single").select2({
          placeholder: "Uyruk Seçiniz",
          allowClear: true
        });
        $(".select2_group").select2({});
      });
	</script>
	<!-- /Select2 -->
	<!-- Parsley -->
	<script>
      $(document).ready(function() {
        $.listen('parsley:field:validate', function() {
          validateFront();
        });
        $('#employee_fast_form .btn').on('click', function() {
          $('#employee_fast_form').parsley().validate();
          validateFront();
        });
        var validateFront = function() {
          if (true === $('#employee_fast_form').parsley().isValid()) {
            $('.bs-callout-info').removeClass('hidden');
            $('.bs-callout-warning').addClass('hidden');
          } else {
            $('.bs-callout-info').addClass('hidden');
            $('.bs-callout-warning').removeClass('hidden');
          }
        };
      });
      try {
        hljs.initHighlightingOnLoad();
      } catch (err) {}
    
	</script>
	<!-- /Parsley -->