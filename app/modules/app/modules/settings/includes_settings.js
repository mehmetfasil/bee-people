    $(document).ready(function() {
        //tabs
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (f) {
        var selectedTabId = f.target.id;
        switch(selectedTabId){
            case "birimler-tab":
                var l = $('#sube_il').children("option").length;
                if(l<=1){
                    App_GetSelectItems("sube_il",0,"get_counties",0);
                }
                var birimType = $('#birim_turu').val();
                gUnits(birimType);
            break;
            case "tanimlar-tab":
                gFileTypes();
            break;

        }
        });
        $('#odeme_history_datatable').DataTable({
        "language": {
            "lengthMenu": "Her Sayfa _MENU_ kayıt ",
            "zeroRecords": "Kayıt Bulunamadı",
            "info": "Gösterilen: _PAGE_ Toplam Sayfa: _PAGES_",
            "infoEmpty": "Kayıt Yok",
            "infoFiltered": "( _MAX_ kayıt içinden filtrelendi)",
            "paginate": {
                "previous": "Önceki",
                "next":"Sonraki"
            }
        },"bFilter": false
    });
        
        $('#paymentForm').card({
            container: '.card-wrapper',
            width: 280,
        
            formSelectors: {
                nameInput: 'input[name="first-name"], input[name="last-name"]'
            }
        });
        
        $('#accordion').on('shown.bs.collapse',function(f){
            selectedAc= f.target.id;
            switch(selectedAc){
                case "collapseisdenayrilma":
                    gIc();
                break;
                case "collapseZimmet":
                gZim();
                break;
                case "collapseTakvim":
                gClndr();
                break;
                case "collapseUnvan":
                gUnv();
                break;
                case "collapseIzinTurleri":
                gIzinTypes();
                break;
            }
        });
        
        //ONCHANGES
        $('#sube_il').on('change',function(){
            App_GetSelectItems("sube_ilce",$(this).val(),"get_towns",0);
        })
    
			$.listen('parsley:field:validate', function() {
				validateFront();
			});

			$('#firm_form .btn').on('click', function() {
				$('#firm_form').parsley().validate();
				validateFront();
			});

			var validateFront = function() {
				if (true === $('#firm_form').parsley().isValid()) {
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
	
		$(document).ready(function() {
            getFirmDetailDatas();
            
            //events
			$('#firma_ili').on('change', function() {
				App_GetSelectItems("firma_ilcesi", $(this).val(), "get_towns");
			})
            $('#unvanModal').on('hidden.bs.modal', function () {
                $('#unvanPostForm')[0].reset();
                $('#unvan_rid').val("");
            })
            $('#takvimModal').on('hidden.bs.modal', function () {
                $('#takvimPostForm input:checkbox').iCheck('uncheck');
                $('#takvimPostForm')[0].reset();
                $('#takvim_rid').val("");
            })
            
            /*click*/
             $('#DosyaTuruPostButton').click(function(){
                if (validator.checkAll($('#dosyaTuruPostForm'))) {
					$.ajax({
						type: "POST",
						url: url + "&act=save_dosyaturu",
						data: $('#dosyaTuruPostForm').serialize(),
						success: function(data) {
							var s = $(data).find("result");
							if (s.attr("status") == "OK") {
								showNotify("Başarılı","success",s.text(),"",true);
                                $('#dosyaTuruPostForm')[0].reset();
                                $('#dosya_rid').val("");
                                gFileTypes();
                                $('#dosyaModal').modal('toggle');
							}else{
							     showNotify("Hata","error",s.text(),"",true);
							}
						}
					});
				}
            })
            
            $('#izinPostButton').click(function(){
                if (validator.checkAll($('#izinTuruForm'))) {
					$.ajax({
						type: "POST",
						url: url + "&act=save_izinturu",
						data: $('#izinTuruForm').serialize(),
						success: function(data) {
							var s = $(data).find("result");
							if (s.attr("status") == "OK") {
								showNotify("Başarılı","success",s.text(),"",true);
                                $('#izinTuruForm')[0].reset();
                                $('#izin_rid').val("");
                                gIzinTypes();
                                $('#izinModal').modal('toggle');
							}else{
							     showNotify("Hata","error",s.text(),"",true);
							}
						}
					});
				}
            })
            
            $('#takvimPostButton').click(function(){
                if (validator.checkAll($('#takvimPostForm'))) {
					$.ajax({
						type: "POST",
						url: url + "&act=save_ct",
						data: $('#takvimPostForm').serialize(),
						success: function(data) {
							var s = $(data).find("result");
							if (s.attr("status") == "OK") {
								showNotify("Başarılı","success",s.text(),"",true);
                                $('#takvimPostForm input:checkbox').iCheck('uncheck');
                                $('#takvimPostForm')[0].reset();
                                $('#takvim_rid').val("");
                                gClndr();
                                $('#takvimModal').modal('toggle');
							}else{
							     showNotify("Hata","error",s.text(),"",true);
							}
						}
					});
				}
            })
            
            $('#UnvanPostButton').click(function(){
                if($('#unvan_adi').val().trim()==""){
                    $('#unvan_adi').focus();
                    return;
                }else{
                    $.ajax({
                        type:"POST",
                        url:url+"&act=s_u",
                        data:$('#unvanPostForm').serialize(),
                        success:function(data){
                            var d = $(data).find("result");
                            if(d.attr("status")=="OK"){
                                showNotify("Başarılı","success",d.text(),"",true);
                                $('#unvanPostForm')[0].reset();
                                $('#unvan_rid').val("");
                                $('#unvanModal').modal('toggle');
                                gUnv();
                            }else{
                                showNotify("Hata","error",d.text(),"",true);
                            }
                        }
                    })
                }
            })
            $('#icPostButton').click(function(){
                if($('#ic_adi').val().trim()==""){
                    $('#ic_adi').focus();
                    return;
                }else{
                    $.ajax({
                        type:"POST",
                        url:url+"&act=s_ic",
                        data:$('#icPostForm').serialize(),
                        success:function(data){
                            var d = $(data).find("result");
                            if(d.attr("status")=="OK"){
                                showNotify("Başarılı","success",d.text(),"",true);
                                $('#icPostForm')[0].reset();
                                $('#ic_rid').val("");
                                $('#icModal').modal('toggle');
                                gIc();
                            }else{
                                showNotify("Hata","error",d.text(),"",true);
                            }
                        }
                    })
                }
            })
            $('#zimmetPostButton').click(function(){
                if($('#zimmet_adi').val().trim()==""){
                    $('#zimmet_adi').focus();
                    return;
                }else{
                    $.ajax({
                        type:"POST",
                        url:url+"&act=s_zimmet",
                        data:$('#zimmetPostForm').serialize(),
                        success:function(data){
                            var d = $(data).find("result");
                            if(d.attr("status")=="OK"){
                                showNotify("Başarılı","success",d.text(),"",true);
                                $('#zimmetPostForm')[0].reset();
                                $('#zimmet_rid').val("");
                                $('#zimmetModal').modal('toggle');
                                gZim();
                            }else{
                                showNotify("Hata","error",d.text(),"",true);
                            }
                        }
                    })
                }
            })
			$('#btnFirmSave').click(function() {
				if (validator.checkAll($('#firm_form'))) {
					$.ajax({
						type: "POST",
						url: url + "&act=save_firm_detail",
						data: $('#firm_form').serialize(),
						success: function(data) {
							var status = $(data).find("result").attr("status");
							if (status == "OK") {
								showNotify("Başarılı","success","Firma Bilgileri Başarıyla Güncellendi","",true);
							}
						}
					});
				}
			})
		})
        
        function getFirmDetailDatas(){
            App_GetSelectItems("firma_ili", 0, "get_counties");
            
             $.ajax({
                type:"POST",
                url:url+"&act=get_firm_details",
                data:"",
                success:function(data){
                    var status=$(data).find("result").attr("status");
                    if(status=="OK"){
                        var firm = $(data).find("f");
                        var f_name = firm.attr("name"); $('#firma_ismi').val(f_name);
                        var f_county = firm.attr("county"); $('#firma_ili').val(f_county);
                        var f_town = firm.attr("town");
                        App_GetSelectItems("firma_ilcesi", f_county, "get_towns",f_town);
                        var f_address = firm.attr("address"); $('#firma_adresi').val(f_address);
                        var f_tel = firm.attr("phone"); $('#firma_tel').val(f_tel);
                        var f_fax = firm.attr("fax"); $('#firma_fax').val(f_fax);
                        var f_web = firm.attr("website"); $('#firma_website').val(f_web);
                        var f_mersis = firm.attr("mersis"); $('#mersis_no').val(f_mersis);
                        var f_sgk = firm.attr("sgk"); $('#sgk_no').val(f_sgk);
                        var f_apellation = firm.attr("apellation"); $('#firma_unvan').val(f_apellation);
                        var f_tax_number = firm.attr("tax_number"); $('#vergi_no').val(f_tax_number);
                        var f_tax_place = firm.attr("tax_place"); $('#vergi_dairesi').val(f_tax_place);
                        var f_bill_address = firm.attr("bill_address"); $('#fatura_adresi').val(f_bill_address);
                        var f_bill_email = firm.attr("bill_email"); $('#fatura_eposta').val(f_bill_email);
                        
                    }else{
                        
                    }
                }
             })   
            }
            
            function gFileTypes(){
                $.ajax({
                    type:"post",
                    url:url+"&act=g_filetypes",
                    success:function(data){
                        var d = $(data).find("result");
                        if(d.attr("status")=="OK"){
                            var html = "";
                            $(data).find("f").each(function(){
                                html+= "<li class='list-group-item' id='"+$(this).attr("i")+"'>";
                                html+="<span><i class='fa fa-minus'></i>&nbsp;&nbsp;"+$(this).attr("n")+"</span>";
                                html+="<span class='pull-right'>";
                                if($(this).attr("p")=="1"){
                                    html+="<button type='button' "+ 'onclick="fillDosyaFrm('+$(this).attr("i")+',\'' + $(this).attr("n") + '\')"' +" class='btn btn-link tmp-spn-edit'>Düzenle</button>";
                                    html+="<button type='button' id='"+$(this).attr("i")+"' class='btn btn-link tmp-spn-edit dosyaturu_dummy'>Sil</button>";
                                }else{
                                    html+="<i class='fa fa-lock'></i>";
                                }
                                html+="</span>";
                                html+="</li>";
                            })
                            $('#dosya_list').html(html);
                            
                           $('.dosyaturu_dummy').each(function(){
                            var ii = this.id;
                                 $(this).qtip({
                                content:'<span>Silmek istediğinize emin misiniz? <button class="btn btn-xs btn-warning" onclick="dDosyaTuru('+ii+')">Evet</button><button class="btn btn-xs btn-warning shot-me" onclick="hideQtip()">Hayır</button></span>',
                                position: {at: 'right top',my:'right top',adjust: {x: -50,y:5}},
                                show: {
                                event: 'click'
                                },hide:{
                                    event:'click'
                                }
                                });                                 
                           })
                        }
                    }
                })
            }
            
            function gIzinTypes(){
                $.ajax({
                    type:"post",
                    url:url+"&act=g_izintypes",
                    success:function(data){
                        var d = $(data).find("result");
                        if(d.attr("status")=="OK"){
                            var html = "";
                            $(data).find("it").each(function(){
                                html+= "<li class='list-group-item' id='"+$(this).attr("i")+"'>";
                                html+="<span><i class='fa fa-minus'></i>&nbsp;&nbsp;"+$(this).attr("n")+"</span>";
                                html+="<span class='pull-right'>";
                                html+="<button type='button' "+ 'onclick="fillIzinFrm('+$(this).attr("i")+',\"'+$(this).attr("n")+'\",\"'+$(this).attr("d")+'\")"' +" class='btn btn-link tmp-spn-edit'>Düzenle</button>";
                                html+="<button type='button' id='"+$(this).attr("i")+"' class='btn btn-link tmp-spn-edit izinturu_dummy'>Sil</button>";
                                html+="</span>";
                                html+="</li>";
                            })
                            $('#izinturu_list').html(html);
                            
                           $('.izinturu_dummy').each(function(){
                            var ii = this.id;
                                 $(this).qtip({
                                content:'<span>Silmek istediğinize emin misiniz? <button class="btn btn-xs btn-warning" onclick="dIzinTuru('+ii+')">Evet</button><button class="btn btn-xs btn-warning shot-me" onclick="hideQtip()">Hayır</button></span>',
                                position: {at: 'right top',my:'right top',adjust: {x: -50,y:5}},
                                show: {
                                event: 'click'
                                },hide:{
                                    event:'click'
                                }
                                });                                 
                           })
                        }
                    }
                })
            }
            
            function dClndr(id){
                if(id!=null){
                    $.ajax({
                        type:"POST",
                        url:url+"&act=d_clndr",
                        data:"i="+id,
                        success:function(data){
                            var d = $(data).find("result");
                            if(d.attr("status")=="OK"){
                                showNotify("Başarılı","success",d.text(),"",true);
                                $('.calendar_dummy').qtip('hide');
                                gClndr();
                            }else{
                                showNotify("Hata","error",d.text(),"",true);
                            }
                        }
                    })
                }
            }
            
            function gClndr(){
                $.ajax({
                    type:"post",
                    url:url+"&act=g_ct",
                    success:function(data){
                        var d = $(data).find("result");
                        if(d.attr("status")=="OK"){
                            var html = "";
                            $(data).find("wc").each(function(){
                                html+= "<li class='list-group-item' id='"+$(this).attr("i")+"'>";
                                html+="<span><i class='fa fa-minus'></i>&nbsp;&nbsp;"+$(this).attr("n")+"</span>";
                                html+="<span class='pull-right'>";
                                html+="<button type='button' "+ 'onclick="fillClndr('+$(this).attr("i")+')"' +" class='btn btn-link tmp-spn-edit'>Düzenle</button>";
                                html+="<button type='button' id='"+$(this).attr("i")+"' class='btn btn-link tmp-spn-edit calendar_dummy'>Sil</button>";
                                html+="</span>";
                                html+="</li>";
                            })
                            $('#takvim_list').html(html);
                            
                           $('.calendar_dummy').each(function(){
                            var ii = this.id;
                                 $(this).qtip({
                                content:'<span>Silmek istediğinize emin misiniz? <button class="btn btn-xs btn-warning" onclick="dClndr('+ii+')">Evet</button><button class="btn btn-xs btn-warning shot-me" onclick="hideQtip()">Hayır</button></span>',
                                position: {at: 'right top',my:'right top',adjust: {x: -50,y:5}},
                                show: {
                                event: 'click'
                                },hide:{
                                    event:'click'
                                }
                                });                                 
                           })
                        }
                    }
                })
            }
            
            function fillClndr(id){
                $('#takvim_rid').val(id);
                $.ajax({
                    type:"POST",
                    url:url+"&act=g_cl_detail",
                    data:"i="+id,
                    success:function(data){
                        var s = $(data).find("result");
                        if(s.attr("status")=="OK"){
                            var e = $(data).find("ce");
                            $('#ct_adi').val(e.attr("n"));
                            var days= e.attr("wd");
                            var s = days.split("-");
                            for(i=0;i<s.length;i++){
                                $('#cb_d_'+s[i]).iCheck('check');
                            }
                            $('#ct_baslangic_saati').val(e.attr("whs"));
                            $('#ct_bitis_saati').val(e.attr("whe"));
                            $('#ct_yemek_baslangic_saati').val(e.attr("mhs"));
                            $('#ct_yemek_bitis_saati').val(e.attr("mhe"));
                        }else{
                            
                        }
                    }
                })
                $('#takvimModal').modal("toggle");
            }
            
            function gUnits(id){
                switch(id){
                    case "1":
                        $.ajax({
                            type:"POST",
                            url:url+"&act=get_units",
                            data:"t=1",
                            success:function(data){
                                        var html = "";
                                        var dep = "";
                                        $(data).find("su").each(function(){
                                            html += "<li>";
                                            html += "<p class='pointer'><i class='fa fa-remove' style='color:#cc0033' onclick=''></i>&nbsp;&nbsp;&nbsp;<i class='fa fa-edit'></i>&nbsp;&nbsp;&nbsp;<b>Şube Adı</b>:"+$(this).attr("n")+" <b>İl: </b>"+$(this).attr("il")+"</p>";
                                            html +="</li>";
                                            dep += "<option value='"+$(this).attr("i")+"'>"+$(this).attr("n")+"</option>";
                                        });
                                        $('#departman_subesi').html(dep);
                                        $('#unit_list').html(html);
                            }
                            
                            });
                    break;
                    case "2":
                        $.ajax({
                            type:"POST",
                            url:url+"&act=get_units",
                            data:"t=2",
                            success:function(data){
                                        var html = "";
                                        $(data).find("d").each(function(){
                                            html += "<li>";
                                            html += "<p class='pointer'><i class='fa fa-remove' style='color:#cc0033' onclick=''></i>&nbsp;&nbsp;&nbsp;<i class='fa fa-edit'></i>&nbsp;&nbsp;&nbsp;<b>Adı:</b>:"+$(this).attr("n")+" <b>Şube: </b>"+$(this).attr("sube")+"</p>";
                                            html +="</li>";
                                        })
                                        $('#department_list').html(html);
                            }
                            });
                    break;
                }
            }
            function sUnits(){
                switch($('#birim_turu').val().trim()){
                    case "1":
                        if(validator.checkAll($('#subePostForm'))){
                            $.ajax({
                                type:"post",
                                url:url+"&act=s_unit",
                                data:$('#subePostForm').serialize()+"&t=1",
                                success:function(data){
                                    var s = $(data).find("result");
                                    switch(s.attr("status")){
                                        case "ERROR":
                                            showNotify("Hata","error",s.text(),"",true);
                                        break;
                                        case "OK":
                                            showNotify("Başarılı","success",s.text(),"",true);
                                            $('#subePostForm')[0].reset();
                                            gUnits("1");
                                        break;
                                    }
                                }
                            })
                        }
                    break;
                    
                    case "2":
                        if(validator.checkAll($('#departmanPostForm'))){
                            $.ajax({
                                type:"post",
                                url:url+"&act=s_unit",
                                data:$('#departmanPostForm').serialize()+"&t=2",
                                success:function(data){
                                    var s = $(data).find("result");
                                    switch(s.attr("status")){
                                        case "ERROR":
                                            showNotify("Hata","error",s.text(),"",true);
                                        break;
                                        case "OK":
                                            showNotify("Başarılı","success",s.text(),"",true);
                                            $('#departmanPostForm')[0].reset();
                                            gUnits("2");
                                        break;
                                    }
                                }
                            })
                        }
                    break;
                }  
            }
            function UnitLayout(){
                var i = $('#birim_turu').val();
                
                switch(i){
                    case "1":
                    $('#branches').removeClass("hidden");
                    $('#departments').addClass("hidden");
                    break;
                    
                    case "2":
                    gUnits("2");
                    $('#branches').addClass("hidden");
                    $('#departments').removeClass("hidden");
                    
                    break;
                }
            }
            
            /***** ZIMMET *******/
            function gZim(){
                $.ajax({
                    type:"POST",
                    url:url+"&act=g_zimmet",
                    success:function(data){
                        var s= $(data).find("result").attr("status");
                        if(s=="OK"){
                            var html = "";
                            $(data).find("ic").each(function(){
                                html+= "<li class='list-group-item' id='"+$(this).attr("i")+"'>";
                                html+="<span><i class='fa fa-minus'></i>&nbsp;&nbsp;"+$(this).attr("n")+"</span>";
                                html+="<span class='pull-right'>";
                                html+="<button type='button' "+ 'onclick="fillZim('+$(this).attr("i")+',\'' + $(this).attr("n") + '\')"' +" class='btn btn-link tmp-spn-edit'>Düzenle</button>";
                                html+="<button type='button' id='"+$(this).attr("i")+"' class='btn btn-link tmp-spn-edit zimmet_dummy'>Sil</button>";
                                html+="</span>";
                                html+="</li>";
                                
                            })
                            $('#zimmet_list').html(html);
                            
                           $('.zimmet_dummy').each(function(){
                            var ii = this.id;
                                 $(this).qtip({
                                content:'<span>Silmek istediğinize emin misiniz? <button class="btn btn-xs btn-warning" onclick="dZim('+ii+')">Evet</button><button class="btn btn-xs btn-warning shot-me" onclick="hideQtip()">Hayır</button></span>',
                                position: {at: 'right top',my:'right top',adjust: {x: -50,y:5}},
                                show: {
                                event: 'click'
                                },hide:{
                                    event:'click'
                                }
                                });                                 
                           })
                            
                        }
                    }
                })
            }
            
            function dZim(id){
                if(id!=null){
                    $.ajax({
                        type:"POST",
                        url:url+"&act=d_zimmet",
                        data:"i="+id,
                        success:function(data){
                            var d = $(data).find("result");
                            if(d.attr("status")=="OK"){
                                showNotify("Başarılı","success",d.text(),"",true);
                                $('.zimmet_dummy').qtip('hide');
                                gZim();
                            }else{
                                showNotify("Hata","error",d.text(),"",true);
                            }
                        }
                    })
                }
            }
            
            function fillZim(id,n){
                $('#zimmet_rid').val(id);
                $('#zimmet_adi').val(n);
                $('#zimmetModal').modal("toggle");
            }
            
            function fillIzinFrm(id,n,d){
                $('#izin_rid').val(id);
                $('#izin_adi').val(n);
                $('#izin_aciklamasi').val(d);
                $('#izinModal').modal("toggle");
            }
            
            function fillDosyaFrm(id,n){
                $('#dosya_rid').val(id);
                $('#dosya_adi').val(n);
                $('#dosyaModal').modal("toggle");
            }
            
            /****** ISDEN CIKARILMA NEDENLERI *****/
            
            function gIc(){
                $.ajax({
                    type:"POST",
                    url:url+"&act=g_ic",
                    success:function(data){
                        var s= $(data).find("result").attr("status");
                        if(s=="OK"){
                            var html = "";
                            $(data).find("ic").each(function(){
                                html+= "<li class='list-group-item' id='"+$(this).attr("i")+"'>";
                                html+="<span><i class='fa fa-minus'></i>&nbsp;&nbsp;"+$(this).attr("n")+"</span>";
                                html+="<span class='pull-right'>";
                                html+="<button type='button' "+ 'onclick="fillIc('+$(this).attr("i")+',\'' + $(this).attr("n") + '\')"' +" class='btn btn-link tmp-spn-edit'>Düzenle</button>";
                                html+="<button type='button' id='"+$(this).attr("i")+"' class='btn btn-link tmp-spn-edit ic_dummy'>Sil</button>";
                                html+="</span>";
                                html+="</li>";
                                
                            })
                            $('#ic_list').html(html);
                            
                           $('.ic_dummy').each(function(){
                            var ii = this.id;
                                 $(this).qtip({
                                content:'<span>Silmek istediğinize emin misiniz? <button class="btn btn-xs btn-warning" onclick="dIc('+ii+')">Evet</button><button class="btn btn-xs btn-warning shot-me" onclick="hideQtip()">Hayır</button></span>',
                                position: {at: 'right top',my:'right top',adjust: {x: -50,y:5}},
                                show: {
                                event: 'click'
                                },hide:{
                                    event:'click'
                                }
                                });                                 
                           })
                            
                        }
                    }
                })
            }
            
            function dIc(id){
                if(id!=null){
                    $.ajax({
                        type:"POST",
                        url:url+"&act=d_ic",
                        data:"i="+id,
                        success:function(data){
                            var d = $(data).find("result");
                            if(d.attr("status")=="OK"){
                                showNotify("Başarılı","success",d.text(),"",true);
                                $('.ic_dummy').qtip('hide');
                                gIc();
                            }else{
                                showNotify("Hata","error",d.text(),"",true);
                            }
                        }
                    })
                }
            }
            
            function fillIc(id,n){
                $('#ic_rid').val(id);
                $('#ic_adi').val(n);
                $('#icModal').modal("toggle");
            }
            
            /*******UNVANLAR*****/
            function gUnv(){
                $.ajax({
                    type:"POST",
                    url:url+"&act=g_u",
                    success:function(data){
                        var s= $(data).find("result").attr("status");
                        if(s=="OK"){
                            var html = "";
                            $(data).find("u").each(function(){
                                html+= "<li class='list-group-item' id='"+$(this).attr("i")+"'>";
                                html+="<span><i class='fa fa-minus'></i>&nbsp;&nbsp;"+$(this).attr("n")+"</span>";
                                html+="<span class='pull-right'>";
                                html+="<button type='button' id='tanimlar_duzenle_unvan'"+ 'onclick="fillUnv('+$(this).attr("i")+',\'' + $(this).attr("n") + '\')"' +" class='btn btn-link tmp-spn-edit'>Düzenle</button>";
                                html+="<button type='button' id='"+$(this).attr("i")+"' class='btn btn-link tmp-spn-edit delete_dummy'>Sil</button>";
                                html+="</span>";
                                html+="</li>";
                                
                            })
                            $('#unvan_list').html(html);
                            
                           $('.delete_dummy').each(function(){
                            var ii = this.id;
                                 $(this).qtip({
                                content:'<span>Silmek istediğinize emin misiniz? <button class="btn btn-xs btn-warning" onclick="dUnv('+ii+')">Evet</button><button class="btn btn-xs btn-warning shot-me" onclick="hideQtip()">Hayır</button></span>',
                                position: {at: 'right top',my:'right top',adjust: {x: -50,y:5}},
                                show: {
                                event: 'click'
                                },hide:{
                                    event:'click'
                                }
                                });                                 
                           })
                            
                        }
                    }
                })
            }
            
            function fillUnv(id,n){
                $('#unvan_rid').val(id);
                $('#unvan_adi').val(n);
                $('#unvanModal').modal("toggle");
            }
            
            function dUnv(id){
                if(id!=null){
                    $.ajax({
                        type:"POST",
                        url:url+"&act=d_u",
                        data:"i="+id,
                        success:function(data){
                            var d = $(data).find("result");
                            if(d.attr("status")=="OK"){
                                showNotify("Başarılı","success",d.text(),"",true);
                                $('.delete_dummy').qtip('hide');
                                gUnv();
                            }else{
                                showNotify("Hata","error",d.text(),"",true);
                            }
                        }
                    })
                }
            }
            
            function dDosyaTuru(id){
                if(id!=null){
                    $.ajax({
                        type:"POST",
                        url:url+"&act=d_d",
                        data:"i="+id,
                        success:function(data){
                            var d = $(data).find("result");
                            if(d.attr("status")=="OK"){
                                showNotify("Başarılı","success",d.text(),"",true);
                                $('.dosyaturu_dummy').qtip('hide');
                                gFileTypes();
                            }else{
                                showNotify("Hata","error",d.text(),"",true);
                            }
                        }
                    })
                }
            }
            
            
            function hideQtip(){
                $('.delete_dummy, .ic_dummy, .zimmet_dummy, .calendar_dummy, .izinturu_dummy, .dosyaturu_dummy').qtip('hide');
            }