$(document).ready(function(){
    
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (f) {
    // here is the new selected tab id
    var selectedTabId = f.target.id;
    switch(selectedTabId){
        case "referans-tab":
        gRef(e);
        break;
        
        case "deneyim-tab":
            var l = $('#deneyim_firma_ili').children("option").length;
            if(l<=1){
                App_GetSelectItems("deneyim_firma_ili",0,"get_counties",0);
            }
            gExp(e);
        break;
        
        case "egitim-tab":
        gEd(e);
        break;
        
    }
    });
    
    $('#ImageUploadButton').click(function(){
        if($('#emp_photo').val()==""){
            
        }else{
            $('#imagePost').submit();
            $('#upload_target').fadeIn();
        }
    })
    
    $('#fileUploadButton').click(function(){
       if($('#fileUploadType').val()<=0){
        return;
       }
       if($('#fileUploadName').val().trim()==""){
        $('#fileUploadName').focus();
        return;
       }
       $('#filePostForm').submit();
       $('#file_upload_frame').fadeIn();
    });
    
    $('input[type=text]').keyup(function(){
    this.value = this.value.turkishToUpper();
    });

    ui = ['2', '3', '4'];
    
    var sets = new Array("ikamet_il","egitim_okul_il");
    App_GetSelectItems("yetki_turu",0,"get_roles",0);
    App_GetSelectItems(sets,0,"get_counties",0);
    App_GetSelectItems("ikamet_ulke",0,"get_states",0);
    App_GetSelectItems("uyruk",0,"get_states",0);
    
    if(typeof e !=='undefined'){
        GEM(e);
        App_GetSelectItems("fileUploadType",0,"get_ftypes",0);
        gF(e);gEd(e);
        $('#editToggle').click(function(){
            $('#editToggleDiv').toggle();
            $('#empExtras').toggle();
            $('#editToggleDiv').scrollTop(0);
            $('html,body').animate({scrollTop: $('#editToggleDiv').offset().top}, 800);
        })
    $('#egitim_id').val(e);
    }
    
    $('#askerlik_durumlari').on('change',function(){
    $('#military_'+$(this).val()).removeClass("display-none").addClass("display-block");
    var i = ui.indexOf($(this).val());
    
    if(i != -1) {
    	ui.splice(i, 1);
    }
    
    for (index = 0; index < ui.length; ++index) {
    $('#military_'+ui[index]+" :input").val("");
    $('#military_'+ui[index]).removeClass("display-block").addClass("display-none");
    }
    ui = ['2', '3', '4'];
    })
    
    $('#es_calisma_durumu').on('change',function(){
        if($(this).val()==2){
            
        }
    })
    
    $('#deneyim_firma_ili').on('change',function(){
        App_GetSelectItems("deneyim_firma_ilcesi",$(this).val(),"get_towns",0);
    })
    
    $('#ehliyet').on('change',function(){
        if($(this).val()==2){
            $('#ehliyet_1, #ehliyet_2').removeClass("display-none").addClass("display-block");
        }else{
            $('#ehliyet_1, #ehliyet_2').removeClass("display-block").addClass("display-none");
            $('#ehliyet_yili').val("");
        }
    })
    
    $('#ikamet_il').on('change',function(){
        App_GetSelectItems("ikamet_ilce",$(this).val(),"get_towns",0);
    })
    $('#es_calisma_durumu').on('change',function(){
        if($(this).val()=="2"){
            $('#es_isyeri_adi_div, #es_isyeri_gorevi_div').removeClass("display-none").addClass("display-block");
        }else if($(this).val()==1){
            $('#es_isyeri_adi_div, #es_isyeri_gorevi_div').removeClass("display-block").addClass("display-none");
            $('#es_isyeri_adi').val("");
            $('#es_gorevi').val("");
        }
    })
    
    $('#btn_save').click(function(){
        if (validator.checkAll($('#employee_fast_form'))) {
            $.ajax({
                type:"POST",
                url:url+"&act=save_employee",
                data:$('#employee_fast_form').serialize(),
                success:function(data){
                    var status = $(data).find("result").attr("status");
                    if(status=="REGEX"){
                        //show 
                       showNotify("Uyarı","info",$(data).find("result").text(),"",true);
                    }else if(status=="WARN"){
                        showNotify("Uyarı","warning",$(data).find("result").text(),"",true);
                    }else if(status=="OK"){
                        showNotify("Başarılı","success",$(data).find("result").text(),"",true);
                        $('#employee_fast_form')[0].reset();
                        $('#id').val("");
                    }else if(status=="UPDATE"){
                        showNotify("Başarılı","success",$(data).find("result").text(),"",true);
                    }
                }
            })
        }
    })
    
    $('#egitimKayitButton').click(function(){
        if($('#egitim_turu').val()<=0 || $('#egitim_durumu').val()<=0 || $('#egitim_okul_ulke').val()<=0 || $('#egitim_okul_il').val()<=0)
        return;
        if($('#egitim_baslangic').val().trim()==""){
            $('#egitim_baslangic').focus();
            return;
        }
        if($('#egitim_bitis').val().trim()==""){
            $('#egitim_bitis').focus();
        }
        if($('#egitim_okul_adi').val().trim()==""){
            $('#egitim_okul_adi').focus();
            return;
        }
        if($('#egitim_bolum_adi').val().trim()==""){
            $('#egitim_bolum_adi').focus();
            return;
        }
        if($('#egitim_mezuniyet_derecesi').val().trim()==""){
            $('#egitim_mezuniyet_derecesi').focus();
            return;
        }
        $.ajax({
            type:"POST",
            url:url+"&act=save_education",
            data:$('#educationPostForm').serialize(),
            success:function(data){
                var d = $(data).find("result");
                if(d.attr("status")=="OK"){
                    showNotify("Başarılı","success",d.text(),"",true);
                    $('#educationPostForm')[0].reset();
                    gEd(e);
                }else if(d.attr("status")=="ERROR"){
                    showNotify("Başarılı","error",d.text(),"",true);
                }
            }
        })
    })
    
    $('#referansKayitButton').click(function(){
        if($('#referans_adisoyadi').val().trim()==""){
            $('#referans_adisoyadi').focus();
            return;
        }
        if($('#referans_kurumadi').val().trim()==""){
            $('#referans_kurumadi').focus();
            return;
        }
        if($('#referans_gorevi').val().trim()==""){
            $('#referans_gorevi').focus();
            return;
        }
        if($('#referans_adresi').val().trim()==""){
            $('#referans_adresi').focus();
            return;
        }
        if($('#referans_telefon').val().trim()==""){
            $('#referans_telefon').focus();
            return;
        }
        $.ajax({
            type:"POST",
            url:url+"&act=save_reference",
            data:$('#referencePostForm').serialize()+"&i="+e,
            success:function(data){
                var status = $(data).find("result");
                if(status.attr("status")=="OK"){
                    showNotify("Başarılı","success",status.text(),"",true);
                    $('#referencePostForm')[0].reset();
                    gRef(e);
                }else if(status.attr("status")=="ERROR"){
                    showNotify("Hata","error",status.text(),"",true);
                }
            }
        })
    })
    
    $('#deneyimKayitButton').click(function(){
        if(validator.checkAll($('#experiencePostForm'))){
            $.ajax({
                type:"post",
                url:url+"&act=save_exp",
                data:$('#experiencePostForm').serialize()+"&i="+e,
                success:function(data){
                    var d = $(data).find("result");
                    if(d.attr("status")=="ERROR"){
                        showNotify("Hata","error",d.text(),"",true); 
                    }else if(d.attr("status")=="OK"){
                        showNotify("Başarılı","success",d.text(),"",true);
                        $('#experiencePostForm')[0].reset();
                        gExp(e);
                    }
                }
            })
        }
    })
})

function GEM(id){
    $.ajax({
        type:"POST",
        url:url+"&act=gem",
        data:"id="+id,
        success:function(data){
            var status = $(data).find("result").attr("status");
            if(status=="ERROR"){
                showNotify("Hata!","error",$(data).find("result").text(),"",true);
            }else if(status=="OK"){
                var em = $(data).find("e");
                $('#id').val(em.attr("i")); $('#tckimlikno').val(em.attr("emp_citizenid"));$('#isim').val(em.attr("emp_name"));
                $('#soyisim').val(em.attr("emp_surname"));$('#dogum_tarihi').val(em.attr("b_date")); $('#dogum_yeri').val(em.attr("e_b_place"));
                $('#telefon').val(em.attr("emp_phone_number_work"));$('#gsm_number').val(em.attr("emp_phone_number_gsm"));
                $('#eposta').val(em.attr("emp_email_work"));$('#ikamet_ulke').val(em.attr("e_state")).trigger("change");
                $('#ikamet_il').val(em.attr("e_h_county"));$('#adres').val(em.attr("e_address"));
                $('#uyruk').val(em.attr("e_nationality")).trigger("change");
                App_GetSelectItems("ikamet_ilce",em.attr("e_h_county"),"get_towns",em.attr("e_h_town"));
                $('#cinsiyeti').val(em.attr("e_sex")); $('#askerlik_durumlari').val(em.attr("e_mil_o")).trigger("change");
                $('#terhis_tarihi').val(em.attr("e_mil_end_date")); $('#muaf_neden').val(em.attr("e_mi_sus_reason"));
                $('#tecil_tarihi').val(em.attr("e_mil_sus_date"));$('#medeni_durum').val(em.attr("e_marital"));
                $('#cocuk_sayisi').val(em.attr("e_child")); $('#es_calisma_durumu').val(em.attr("e_spouse_w")).trigger("change");
                $('#ehliyet').val(em.attr("e_d_licence")).trigger("change");
                $('#ehliyet_sinifi').val(em.attr("e_d_l_type")); $('#ehliyet_yili').val(em.attr("e_d_l_year"));
                $('#es_isyeri_adi').val(em.attr("e_spouse_w_p")); $('#es_gorevi').val(em.attr("e_spouse_w_pos"));
                $('#anne_adi').val(em.attr("e_mother_n")); $('#anne_meslegi').val(em.attr("e_mother_j"));
                $('#baba_adi').val(em.attr("e_father_n")); $('#baba_meslegi').val(em.attr("e_father_j")); $('#yetki_turu').val(em.attr("r"));
                $('#empJobToInform').text($('#yetki_turu option:selected').text());
                $('#personelin_gorevi').val(em.attr("emp_job")); $('#ise_baslama_tarihi').val(em.attr("e_w_start_date"));
                if(em.attr("p")!=""){$('#empPhoto').attr('src',em.attr("p"));}
                var a = $(data).find("a");
                $('#net_maas').val(a.attr("n_s")); $('#yemek_ucreti').val(a.attr("f_c"));$('#yol_ucreti').val(a.attr("r_f"));
                $('#brut_ucret').val(a.attr("g_w"));$('#hesap_no').val(a.attr("a_n")); $('#sube_kodu').val(a.attr("b_c"));$('#iban').val(a.attr("iban"));
                $('#empNameToInform').text(em.attr("emp_name")+" "+em.attr("emp_surname"));
            }
        }
    })
}
function upLOAD(){
    $('#emp_photo').val("");
    showNotify("Yükleme Başarılı","success","Personel Fotoğrafı Başarıyla Yüklendi","",true);
    $('#PhotoModal').modal('toggle');
    GEM(e);
}

function fileUploaded(){
    $('#filePostForm')[0].reset();
    gF(e);
    setTimeout(function(){ $('#file_upload_frame').fadeOut(); }, 3000);
}

function gF(i){
    $.ajax({
            type:"POST",
            url:url+"&act=gf",
            data:"i="+i,
            success:function(data){
                var s = $(data).find("result");
                if(s.attr("status")=="OK"){
                    var html = "";
                    $(data).find("f").each(function(){
                        html += "<li>";
                        html += "<a target='_blank' class='pointer' href='"+$(this).attr("p")+"'><i class='fa fa-file-word-o'></i>&nbsp;&nbsp;"+$(this).attr("n")+"</a>";
                        html +="</li>";
                    })
                    $('#file_list').html(html);
                }else if(s.attr("status")=="ERROR"){
                    showNotify("Hata","error",s.text(),"",true);
                }
            }
        })
}

function gEd(i){
    $.ajax({
        type:"POST",
        url: url+"&act=ged",
        data:"i="+i,
        success:function(data){
            $('#education_list').html("");
            var d = $(data).find("result");
            if(d.attr("status")=="OK"){
                var html = "";
                $(data).find("e").each(function(){
                    html += "<li>";
                    html += "<p class='pointer'><i class='fa fa-remove' style='color:#cc0033' onclick='dED("+$(this).attr("id")+")'></i>&nbsp;&nbsp;&nbsp;<i class='fa fa-edit'></i>&nbsp;&nbsp;&nbsp;<i class='glyphicon glyphicon-education'></i>&nbsp;&nbsp;&nbsp;<b>Türü</b>:"+$(this).attr("etype")+" <b>Okul: </b>"+$(this).attr("school")+" <b>Şehir: </b>"+$(this).attr("city")+"</p>";
                    html +="</li>";
                })
                $('#education_list').html(html);
            }
        }
    })
}

function gRef(i){
    $.ajax({
        type:"POST",
        url: url+"&act=gref",
        data:"i="+i,
        success:function(data){
            var d = $(data).find("result");
            if(d.attr("status")=="OK"){
                var html = "";
                $(data).find("r").each(function(){
                    html += "<li>";
                    html += "<p class='pointer'><i class='fa fa-remove' style='color:#cc0033' onclick=''></i>&nbsp;&nbsp;&nbsp;<i class='fa fa-edit'></i>&nbsp;&nbsp;&nbsp;"+$(this).attr("fname")+"-"+$(this).attr("wplace")+"-"+$(this).attr("rphone")+"</p>";
                    html +="</li>";
                })
                $('#reference_list').html(html);
            }
        }
    })
}


function gExp(i){
    $.ajax({
        type:"POST",
        url: url+"&act=gexp",
        data:"i="+i,
        success:function(data){
            var d = $(data).find("result");
            if(d.attr("status")=="OK"){
                var html = "";
                $(data).find("ex").each(function(){
                    html += "<li>";
                    html += "<p class='pointer'><i class='fa fa-remove' style='color:#cc0033' onclick=''></i>&nbsp;&nbsp;&nbsp;<i class='fa fa-edit'></i>&nbsp;&nbsp;&nbsp;<b>İşYeri: </b>"+$(this).attr("fname")+" <b>Pozisyon: </b>"+$(this).attr("expo")+" <b>Süre: </b>"+$(this).attr("yil")+" "+$(this).attr("ay")+" "+$(this).attr("gun")+"</p>";
                    html +="</li>";
                })
                $('#experience_list').html(html);
            }
        }
    })
}

function dED(i){
    $.ajax({
        type:"POST",
        url:url+"&act=deEdu",
        data:"i="+i,
        success:function(data){
            var status= $(data).find("result").attr("status");
            gEd(e);
        }
    })
}

function dPpicture(){
    $.ajax({
        type:"POST",
        url:urlCommon + "&act=delete_emp_file",
        data:"e_id="+e+"&f_type=1",
        success:function(data){
            var d = $(data).find("result");
            if(d.attr("status")=="OK"){
                $('#empPhoto').attr('src','objects/icons/no-photo.png');
                $('.deleteModal').modal('hide');
            }
        },
        error:function (request, status, error) {
        alert(request.responseText);
        }
    })
    
}
