<? defined( "PASS") or die( "Dosya Yok!");?>
<link rel="stylesheet" href="objects/js/app/vendors/jquery.qtip.custom/jquery.qtip.min.css"/>
<link rel="stylesheet" href="objects/js/app/vendors/cardjs/css/style.css"/>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="" role="tabpanel" data-example-id="togglable-tabs">
				<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
					<li role="presentation" class="active">
						<a href="#sirket_bilgileri" id="sirketbilgileri-tab" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-bank"></i>&nbsp;Şirket Bilgileri</a>
					</li>
					<li role="presentation" class="">
						<a href="#birimler" role="tab" id="birimler-tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-code-fork"></i>&nbsp;Birimler</a>
					</li>
					<li role="presentation" class="">
						<a href="#sistem" role="tab" id="sistem-tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-gear"></i>&nbsp;Sistem</a>
					</li>
                    <li role="presentation" class="">
						<a href="#tanimlar" role="tab" id="tanimlar-tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-th-list"></i>&nbsp;Tanımlar</a>
					</li>
					<li role="presentation" class="">
						<a href="#odeme" role="tab" id="odeme-tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-cc-visa"></i>&nbsp;Ödeme</a>
					</li>
				</ul>
				<div id="myTabContent" class="tab-content">
					<div role="tabpanel" class="tab-pane fade active in" id="sirket_bilgileri" aria-labelledby="sirketbilgileri-tab">
						<form class="form-horizontal form-label-left" id="firm_form" name="firm_form" novalidate data-parsley-validate>
							<div class="x_panel">
								<div class="x_title">
									<h2>
										Şirket Bilgileri
										<small>
											Ad,ünvan,adres
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="firma_ismi">
											Firma İsmi
											<span class="required">
												*
											</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input id="firma_ismi" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="firma_ismi" placeholder="Firma İsmi" required="required" type="text">
										</div>
									</div>
									<div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="firma_ili">
											Firma İli
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<select class="form-control" name="firma_ili" id="firma_ili">
												<option value="-1">
													Seçiniz
												</option>
											</select>
										</div>
									</div>
									<div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="firma_ilcesi">
											Firma İlçesi
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<select class="form-control" name="firma_ilcesi" id="firma_ilcesi">
												<option value="-1">
													Seçiniz
												</option>
											</select>
										</div>
									</div>
                                    <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="firma_adres">
											Açık Adres
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<textarea class="form-control" name="firma_adresi" id="firma_adresi"></textarea>
										</div>
									</div>
									<div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="firma_tel">
											Telefon Numarası
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input id="firma_tel" data-inputmask="'mask' : '(999) 999-9999'" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="firma_tel" placeholder="Telefon Numarası" type="text">
										</div>
									</div>
									<div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="firma_fax">
											Fax Numarası
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input id="firma_fax" data-inputmask="'mask' : '(999) 999-9999'" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="firma_fax" placeholder="Fax Numarası" type="text">
										</div>
									</div>
									<div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="firma_website">
											Websitesi
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input id="firma_website" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="firma_website" placeholder="Websitesi" type="url">
										</div>
									</div>
									<div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mersis_no">
											Mersis Numarası
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input id="mersis_no" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="mersis_no" placeholder="Mersis Numarası" type="text">
										</div>
									</div>
									<div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sgk_no">
											SGK Numarası
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input id="sgk_no" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="sgk_no" placeholder="SGK İşyeri Numarası" type="text">
										</div>
									</div>
								</div>
							</div>
                            <div class="x_panel">
								<div class="x_title">
									<h2>
										Fatura Bilgileri
										<small>
											vergi dairesi,fatura
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="firma_unvan">
											Firma Ünvanı
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input id="firma_unvan" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="firma_unvan" placeholder="Firma Unvanı"  type="text">
										</div>
									</div>
									<div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="vergi_no">
											Vergi Numarası
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input id="vergi_no" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="vergi_no" placeholder="Vergi Numarası"  type="text">
										</div>
									</div>
                                    <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="vergi_dairesi">
											Vergi Dairesi
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input id="vergi_dairesi" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="vergi_dairesi" placeholder="Vergi Dairesi"  type="text">
										</div>
									</div>
                                    <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fatura_adresi">
											Fatura Adresi
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input id="fatura_adresi" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="fatura_adresi" placeholder="Fatura Adresi"  type="text">
										</div>
									</div>
                                    <div class="item form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fatura_eposta">
											Fatura Eposta
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input id="fatura_eposta" class="form-control col-md-7 col-xs-12" data-validate-length-range="11" data-validate-words="1" name="fatura_eposta" placeholder="Fatura Eposta"  type="text">
										</div>
									</div>
								</div>
							</div>
                            <div align="center">
                                <button class="btn btn-primary btn-lg" type="button" id="btnFirmSave" name="btnFirmSave">Kaydet</button>
                            </div>
						</form>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="birimler" aria-labelledby="birimler-tab">
                        <div>
                            <div class="x_panel">
                                <div class="x_content">
                                    <div align="center">
            							<div class="col-md-6 col-sm-6 col-xs-6">
                                            <h2 align="left">Birim Türü</h2>
                                            <select class="form-control" name="birim_turu" id="birim_turu" onchange="UnitLayout()">
                                                <option value="1">Şubeler</option>
                                                <option value="2">Departmanlar</option>
                                            </select>
            							</div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div id="branches">
                                        <div class="col-md-6 col-xs-12">
                                                <h2>Şubeler</h2>
                                                <ul class="to_do" id="unit_list">
                                                </ul>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <h2 align="center">Yeni Ekle</h2>
                                            <form class="form-horizontal form-label-left" id="subePostForm" method="post" enctype="multipart/form-data" novalidate data-parsley-validate>
                                                <input type="hidden" id="sube_rid" name="sube_rid"/>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="sube_adi">
                        								Şube Adı
                                                        <span class="required">
                        									*
                        								</span>
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<input type="text" class="form-control item form-group" id="sube_adi" name="sube_adi" placeholder="Örn: MGA " required="required"/>
                        							</div>
                        						</div>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="sube_ili">
                        								Şube İli
                                                        <span class="required">
                        									*
                        								</span>
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<select class="form-control" name="sube_il" id="sube_il">
                                                            <option value="-1">Yükleniyor...</option>
                                                        </select>
                        							</div>
                        						</div>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="sube_ilce">
                        								Şube İlçesi
                                                        <span class="required">
                        									*
                        								</span>
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<select class="form-control" name="sube_ilce" id="sube_ilce">
                                                            <option value="-1">Seçiniz</option>
                                                        </select>
                        							</div>
                        						</div>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="sube_adi">
                        								Şube Adresi
                                                        <span class="required">
                        									*
                        								</span>
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<input type="text" class="form-control item form-group" id="sube_adresi" name="sube_adresi" placeholder="Örn: Ankara " required="required"/>
                        							</div>
                        						</div>
                                                <div align="right">
                                                    <button class="btn btn-primary" type="button" id="btnSubeSave" name="btnSubeSave" onclick="sUnits()">Kaydet</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div id="departments" class="hidden">
                                        <div class="col-md-6 col-xs-12">
                                                <h2>Departmanlar</h2>
                                                <ul class="to_do" id="department_list">
                                                </ul>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <h2 align="center">Yeni Ekle</h2>
                                            <form class="form-horizontal form-label-left" id="departmanPostForm" method="post" enctype="multipart/form-data" novalidate data-parsley-validate>
                                                <input type="hidden" id="departman_rid" name="departman_rid"/>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="departman_subesi">
                        								Departman Şubesi
                                                        <span class="required">
                        									*
                        								</span>
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<select class="form-control" name="departman_subesi" id="departman_subesi">
                                                            <option value="-1">Yükleniyor...</option>
                                                        </select>
                        							</div>
                        						</div>
                                                <div class="item form-group">
                        							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="sube_adi">
                        								Departman Adı
                                                        <span class="required">
                        									*
                        								</span>
                        							</label>
                        							<div class="col-md-8 col-sm-8 col-xs-12">
                        								<input type="text" class="form-control item form-group" id="departman_adi" name="departman_adi" placeholder="Örn: İnsan Kaynakları " required="required"/>
                        							</div>
                        						</div>
                                                <div align="right">
                                                    <button class="btn btn-primary" type="button" id="btnDepartmanSave" name="btnDepartmanSave" onclick="sUnits()">Kaydet</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
                    <div role="tabpanel" class="tab-pane fade" id="tanimlar" aria-labelledby="tanimlar-tab">
						 <!-- start accordion -->
                        <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                          <div class="panel">
                            <a class="panel-heading" role="tab" id="headingDosya" data-toggle="collapse" data-parent="#accordion" href="#collapseDosya" aria-expanded="true" aria-controls="collapseDosya">
                              <h4 class="panel-title"><i class="fa fa-file"></i>&nbsp;Dosya Türleri</h4>
                            </a>
                            <div id="collapseDosya" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingDosya">
                              <div class="panel-body">
                                <div class="col-xs-12 col-lg-8 col-md-10 col-sm-12">
                                    <button class="btn btn-success" type="button" id="tanimlar_yeni_dosya" aria-hidden="true" data-toggle="modal" data-target=".dosyaModal"><i class="fa fa-plus"></i>Yeni Dosya Türü Ekle</button>
                                    <ul class="list-group" id="dosya_list">
                                        
                                    </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="panel">
                            <a class="panel-heading" role="tab" id="headingTakvim" data-toggle="collapse" data-parent="#accordion" href="#collapseTakvim" aria-expanded="true" aria-controls="collapseTakvim">
                              <h4 class="panel-title"><i class="fa fa-calendar"></i>&nbsp;Çalışma Takvimleri</h4>
                            </a>
                            <div id="collapseTakvim" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTakvim">
                              <div class="panel-body">
                                <div class="col-xs-12 col-lg-8 col-md-10 col-sm-12">
                                    <button class="btn btn-success" type="button" id="tanimlar_yeni_takvim" aria-hidden="true" data-toggle="modal" data-target=".takvimModal"><i class="fa fa-plus"></i>Yeni Çalışma Takvimi Ekle</button>
                                    <ul class="list-group" id="takvim_list">
                                        
                                    </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="panel">
                            <a class="panel-heading" role="tab" id="headingIzinTurleri" data-toggle="collapse" data-parent="#accordion" href="#collapseIzinTurleri" aria-expanded="true" aria-controls="collapseİzinTurleri">
                              <h4 class="panel-title"><i class="fa fa-times-circle"></i>&nbsp;İzin Türleri</h4>
                            </a>
                            <div id="collapseIzinTurleri" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingIzinTurleri">
                              <div class="panel-body">
                                <div class="col-xs-12 col-lg-8 col-md-10 col-sm-12">
                                    <button class="btn btn-success" type="button" id="tanimlar_yeni_izinturu" aria-hidden="true" data-toggle="modal" data-target=".izinModal"><i class="fa fa-plus"></i>Yeni İzin Türü Ekle</button>
                                    <ul class="list-group" id="izinturu_list">
                                        
                                    </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="panel">
                            <a class="panel-heading collapsed" role="tab" id="headingUnvan" data-toggle="collapse" data-parent="#accordion" href="#collapseUnvan" aria-expanded="true" aria-controls="collapseUnvan">
                              <h4 class="panel-title"><i class="fa fa-group"></i>&nbsp;Unvanlar</h4>
                            </a>
                            <div id="collapseUnvan" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingUnvan">
                              <div class="panel-body">
                                <div class="col-xs-12 col-lg-8 col-md-10 col-sm-12">
                                    <button class="btn btn-success" type="button" id="tanimlar_yeni_unvan" aria-hidden="true" data-toggle="modal" data-target=".unvanModal"><i class="fa fa-plus"></i>Yeni Unvan Ekle</button>
                                    <ul class="list-group" id="unvan_list">
                                        
                                    </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="panel">
                            <a class="panel-heading collapsed" role="tab" id="headingisdenayrilma" data-toggle="collapse" data-parent="#accordion" href="#collapseisdenayrilma" aria-expanded="false" aria-controls="collapseisdenayrilma">
                              <h4 class="panel-title"><i class="fa fa-power-off"></i>&nbsp;İşden Ayrılma Nedenleri</h4>
                            </a>
                            <div id="collapseisdenayrilma" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingisdenayrilma">
                              <div class="panel-body">
                                <div class="col-xs-12 col-lg-8 col-md-10 col-sm-12">
                                    <button class="btn btn-success" type="button" id="tanimlar_yeni_ic" aria-hidden="true" data-toggle="modal" data-target=".icModal"><i class="fa fa-plus"></i>Yeni İşden Çıkarma Nedeni Ekle</button>
                                    <ul class="list-group" id="ic_list">
                                        
                                    </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="panel">
                            <a class="panel-heading collapsed" role="tab" id="headingZimmet" data-toggle="collapse" data-parent="#accordion" href="#collapseZimmet" aria-expanded="false" aria-controls="collapseZimmet">
                              <h4 class="panel-title"><i class="fa fa-sitemap"></i>&nbsp;Zimmet Kategorileri</h4>
                            </a>
                            <div id="collapseZimmet" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingZimmet">
                              <div class="panel-body">
                                <div class="col-xs-12 col-lg-8 col-md-10 col-sm-12">
                                    <button class="btn btn-success" type="button" id="tanimlar_yeni_zimmet" aria-hidden="true" data-toggle="modal" data-target=".zimmetModal"><i class="fa fa-plus"></i>Zimmet Kategorisi Ekle</button>
                                    <ul class="list-group" id="zimmet_list">
                                        
                                    </ul>
                                </div>
                            </div>
                          </div>
                        </div>
                        <!-- end of accordion -->
					   </div>
				</div>
                <div role="tabpanel" class="tab-pane fade" id="sistem" aria-labelledby="sistem-tab">
						<p>sistem
						</p>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="odeme" aria-labelledby="odeme-tab">
                        <div class="col-xs-12 col-md-12 col-sm-12">
                            <button type="button" class="btn btn-success pull-right" id="" aria-hidden="true" data-toggle="modal" data-target="#odemeModal"><i class="fa fa-credit-card"></i>&nbsp;Kart Bilgileri Tanımla</button>
                        </div>
                        <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-xs-6">
                          <p class="lead">Tanımlı Kart Bilgisi Bulunamadı</p>
                          <img src="templates/app/images/visa.png" alt="Visa">
                          <img src="templates/app/images/mastercard.png" alt="Mastercard">
                          <img src="templates/app/images/american-express.png" alt="American Express">
                          <img src="templates/app/images/paypal.png" alt="Paypal">
                          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                            desteklenen kartlar yukarıdaki gibidir.
                          </p>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-6">
                          <p class="lead">Üyelik Bilgileri</p>
                          <div class="table-responsive">
                            <table class="table">
                              <tbody>
                                <tr>
                                  <th style="width:50%">Üyelik Tarihi:</th>
                                  <td>13/04/2015</td>
                                </tr>
                                <tr>
                                  <th>Üyelik Türü</th>
                                  <td>Deneme Sürümü</td>
                                </tr>
                                <tr>
                                  <th>Personel Sayısı</th>
                                  <td>12</td>
                                </tr>
                                <tr>
                                  <th>Aylık Ücret</th>
                                  <td>15$ + KDV (Çalışan Başına)</td>
                                </tr>
                                <tr>
                                  <th>Ödeme Periyodu</th>
                                  <td>Aylık</td>
                                </tr>
                                <tr>
                                  <th>Son Ödeme Tarihi</th>
                                  <td>10/11/2016</td>
                                </tr>
                                <tr>
                                  <th>Sonraki Ödeme Tarihi</th>
                                  <td>10/12/2016</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <!-- /.col -->
                      </div>
                      <div class="row">
                            <h2>Önceki Ödemeler</h2>
                            <table id="odeme_history_datatable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr>
                                  <th>Ödeme Tarihi</th>
                                  <th>Ücret</th>
                                  <th>Durumu</th>
                                  <th>Yazdır</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>10/08/2016</td>
                                  <td>35$</td>
                                  <td>Tahsil Edildi</td>
                                  <td><button type="button" class="btn btn-primary"><i class="fa fa-print"></i>&nbsp;Yazdır</button></td>
                                </tr>
                                <tr>
                                  <td>10/09/2016</td>
                                  <td>35$</td>
                                  <td>Tahsil Edildi</td>
                                  <td><button type="button" class="btn btn-primary"><i class="fa fa-print"></i>&nbsp;Yazdır</button></td>
                                </tr>
                               
                              </tbody>
                            </table>
                      </div>
                    </div>
                    
		</div>
	</div>
    
    <!-- modals-->
    <div class="modal fade unvanModal" tabindex="-1" role="dialog" aria-hidden="true" id="unvanModal">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
    
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Unvan Ekle/Düzenle</h4>
            </div>
            <div class="modal-body">
              <!-- form-->
                <form id="unvanPostForm" name="unvanPostForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="unvan_rid" id="unvan_rid"/>
                    <input type="text" name="unvan_adi" id="unvan_adi" class="form-control"/>
                </form>
              <!--//form-->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
              <button type="button" class="btn btn-primary" id="UnvanPostButton">Kaydet</button>
            </div>
    
          </div>
        </div>
    </div>
    <div class="modal fade icModal" tabindex="-1" role="dialog" aria-hidden="true" id="icModal">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
    
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">İşden Çıkarma Ekle/Düzenle</h4>
            </div>
            <div class="modal-body">
              <!-- form-->
                <form id="icPostForm" name="icPostForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="ic_rid" id="ic_rid"/>
                    <input type="text" name="ic_adi" id="ic_adi" class="form-control"/>
                </form>
              <!--//form-->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
              <button type="button" class="btn btn-primary" id="icPostButton">Kaydet</button>
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade zimmetModal" tabindex="-1" role="dialog" aria-hidden="true" id="zimmetModal">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
    
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Zimmet Kategorisi Ekle/Düzenle</h4>
            </div>
            <div class="modal-body">
              <!-- form-->
                <form id="zimmetPostForm" name="zimmetPostForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="zimmet_rid" id="zimmet_rid"/>
                    <input type="text" name="zimmet_adi" id="zimmet_adi" class="form-control"/>
                </form>
              <!--//form-->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
              <button type="button" class="btn btn-primary" id="zimmetPostButton">Kaydet</button>
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade takvimModal" tabindex="-1" role="dialog" aria-hidden="true" id="takvimModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
    
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Çalışma Takvimi Ekle/Düzenle</h4>
            </div>
            <div class="modal-body">
              <!-- form-->
                <form id="takvimPostForm" name="takvimPostForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="takvim_rid" id="takvim_rid"/>
                    <div class="item form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="">
							Çalışma Takvimi Adı
                            <span class="required">
								*
							</span>
						</label>
						<div class="col-md-8 col-sm-8 col-xs-12">
							<input type="text" class="form-control item form-group" id="ct_adi" name="ct_adi" placeholder="Merkez Ofis Takvimi" required="required"/>
						</div>
					</div>
                    <div class="item form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="">
							Çalışma Günleri
                            <span class="required">
								*
							</span>
						</label>
						<div class="col-md-8 col-sm-8 col-xs-12">
							 <label><input name="days[]" id="cb_d_1" type="checkbox" value="1" class="flat"/> Pazartesi</label>
                             <label><input name="days[]" id="cb_d_2" type="checkbox" value="2" class="flat"/> Salı</label>
                             <label><input name="days[]" id="cb_d_3" type="checkbox" value="3" class="flat"/> Çarşamba</label>
                             <label><input name="days[]" id="cb_d_4" type="checkbox" value="4" class="flat"/> Perşembe</label>
                             <label><input name="days[]" id="cb_d_5" type="checkbox" value="5" class="flat"/> Cuma</label>
                             <label><input name="days[]" id="cb_d_6" type="checkbox" value="6" class="flat"/> Cumartesi</label>
                             <label><input name="days[]" id='cb_d_7' type="checkbox" value="7" class="flat"/> Pazar</label>
						</div>
					</div>
                    <div class="item form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="">
							Mesai Başlangıç / Bitiş
                            <span class="required">
								*
							</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="text" data-inputmask="'mask' : '99:99'" class="form-control item form-group" id="ct_baslangic_saati" name="ct_baslangic_saati" placeholder="Mesai Başlangıç" required="required"/>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="text" data-inputmask="'mask' : '99:99'" class="form-control item form-group" id="ct_bitis_saati" name="ct_bitis_saati" placeholder="Mesai Bitiş" required="required"/>
                            </div>
						</div>
					</div>
                    <div class="item form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" for="">
							Yemek Başlangıç / Bitiş
                            <span class="required">
								*
							</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="text" data-inputmask="'mask' : '99:99'" class="form-control item form-group" id="ct_yemek_baslangic_saati" name="ct_yemek_baslangic_saati" placeholder="Yemek Başlangıç" required="required"/>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="text" data-inputmask="'mask' : '99:99'" class="form-control item form-group" id="ct_yemek_bitis_saati" name="ct_yemek_bitis_saati" placeholder="Yemek Bitiş" required="required"/>
                            </div>
						</div>
					</div>
                </form>
              <!--//form-->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
              <button type="button" class="btn btn-primary" id="takvimPostButton">Kaydet</button>
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade odemeModal" tabindex="-1" role="dialog" aria-hidden="true" id="odemeModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
    
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Kart Bilgilerini Tanımla</h4>
            </div>
            <div class="modal-body">
              <!-- form-->
                <div class="card-wrapper"></div>
                <br />
                <form id="paymentForm">
                    <div align="center">
                        <div class="col-xs-12 col-md-12 col-sm-12">
                          <input class="form-control" id="column-left" type="text" name="first-name" placeholder="First Name"/>
                        </div>
                        <div class="col-xs-12 col-md-12 col-sm-12"> 
                              <input class="form-control" id="column-right" type="text" name="last-name" placeholder="Surname"/>
                        </div>
                        <div class="col-xs-12 col-md-12 col-sm-12"> 
                              <input class="form-control" id="input-field" type="text" name="number" placeholder="Card Number"/>
                        </div>
                        <div class="col-xs-6 col-md-6 col-sm-6">
                              <input class="form-control" id="column-left" type="text" name="expiry" placeholder="MM / YY"/>
                        </div>
                        <div class="col-xs-6 col-md-6 col-sm-6">   
                              <input class="form-control" id="column-right" type="text" name="cvc" placeholder="CCV"/>
                        </div>
                    </div>
                </form> 
              <!--//form-->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
              <button type="button" class="btn btn-primary" id="odemePostButton">Kaydet</button>
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade izinModal" tabindex="-1" role="dialog" aria-hidden="true" id="izinModal">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
    
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">İzin Türü Tanımlama</h4>
            </div>
            <div class="modal-body">
              <!-- form-->
                <form id="izinTuruForm">
                    <input type="hidden" name="izin_rid" id="izin_rid"/>
                    <div align="center">
                        <div class="col-xs-12 col-md-12 col-sm-12">
                          <input class="form-control" id="izin_adi" type="text" required="required" name="izin_adi" placeholder="İzin Adı"/>
                        </div>
                        <div class="col-xs-12 col-md-12 col-sm-12"> 
                              <input class="form-control" id="izin_aciklamasi" required="required" type="text" name="izin_aciklamasi" placeholder="İzin Açıklaması"/>
                        </div>
                    </div>
                </form> 
              <!--//form-->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
              <button type="button" class="btn btn-primary" id="izinPostButton">Kaydet</button>
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade dosyaModal" tabindex="-1" role="dialog" aria-hidden="true" id="dosyaModal">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
    
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Dosya Türü Ekle/Düzenle</h4>
            </div>
            <div class="modal-body">
              <!-- form-->
                <form id="dosyaTuruPostForm" name="dosyaTuruPostForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="dosya_rid" id="dosya_rid"/>
                    <input type="text" name="dosya_adi" id="dosya_adi" class="form-control"/>
                </form>
              <!--//form-->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
              <button type="button" class="btn btn-primary" id="DosyaTuruPostButton">Kaydet</button>
            </div>
    
          </div>
        </div>
    </div>
    <!--///modals-->
 <script type="text/javascript" src="objects/js/app/vendors/jquery.qtip.custom/jquery.qtip.min.js"></script>
 <script type="text/javascript" src="modules/app/modules/settings/includes_settings.js"></script>
 <script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/121761/card.js'></script>
 <script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/121761/jquery.card.js'></script>