      $(document).ready(function(){
        var options = {
          legend: false,
          responsive: false
        };

        new Chart(document.getElementById("canvas"), {
          type: 'doughnut',
          tooltipFillColor: "rgba(51, 51, 51, 0.55)",
          data: {
            labels: [
              "Yönetici",
              "IK Personeli",
              "Diğer",
              "Yazılımcı",
              "Grafiker"
            ],
            datasets: [{
              data: [15, 20, 30, 10, 30],
              backgroundColor: [
                "#BDC3C7",
                "#9B59B6",
                "#E74C3C",
                "#26B99A",
                "#3498DB"
              ],
              hoverBackgroundColor: [
                "#CFD4D8",
                "#B370CF",
                "#E95E4F",
                "#36CAAB",
                "#49A9EA"
              ]
            }]
          },
          options: options
        });
        
        
        var tour = new Tour({
  steps: [
  {
    element: "#tour_site_title",
    backdrop:true,
    title: "Şirket Adınız",
    content: "E2yi kullanırken şirket adınız sol üst kısımda yer alıyor"
  },
  {
    element:'#tour_user_role_name',
    backdrop:true,
    title: "Kullanıcı Yetkiniz",
    content: "Giriş Yaptıktan sonra sistemdeki kullanıcı yetkiniz bu kısımda yer alıyor"
  },
  {
    element:'#tour_menus',
    backdrop:true,
    title: "Kullanabileceğiniz menüler",
    content: "Sol tarafta yetkilerinize göre menüler listeleniyor.",
    onNext: function(){
        $('#tour_employee').click();
      }
  },
  {
    element:'#tour_add_new_employee',
    title: "Yeni Personel Tanımlama",
    content: "Şirketinize yeni personel ekleme işlemini buradan yapabilirsiniz",
    onNext: function(){
        $('#tour_employee').click();
        $('#tour_system').click();
      }
  },
  {
    element:'#tour_system_settings',
    title: "Şirket Bilgileri Ve Ayarlar",
    content: "Şirketiniz ile ilgili bilgileri ve diğer ayarları buradan yapabilirsiniz."
  }
]});

// Initialize the tour
tour.init();

// Start the tour
tour.start();

      });
      
      $(document).ready(function() {
        
        //dayoffs
        g_DoRqst();
        
        var icons = new Skycons({
            "color": "#73879C"
          }),
          list = [
            "clear-day", "clear-night", "partly-cloudy-day",
            "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
            "fog"
          ],
          i;

        for (i = list.length; i--;)
          icons.set(list[i], list[i]);

        icons.play();
      });

function g_DoRqst(){
    $.ajax({
        type:"POST",
        url:url + "&act=g_do_rqst",
        success:function(data){
            var s = $(data).find("result");
            if(s.attr("status")=="OK"){
                var html = "";
                html +="<ul class='to_do' id='file_list'>";
                $(data).find("it").each(function(){
                html += "<li>";
                html += "<p class='pointer' onclick="+'g_IzinTalepDetay("'+$(this).attr("i")+'")'+">"+$(this).attr("empname")+"-"+$(this).attr("name")+"</p>";
                html +="</li>";
                })
                html +="</ul>";  
                $('#izin_talepleri').html(html);
            }else if(s.attr("status")=="NR"){
                $('#izin_talepleri').html("İzin Talebi Bulunmamaktadır.");
            }
            
        }
    })
}

function g_IzinTalepDetay(id){
    $.ajax({
        type:"post",
        url:url+"&act=g_izin_detay",
        data:"i="+id,
        success:function(data){
            var s = $(data).find("result").attr("status");
            if(s=="OK"){
                var d = $(data).find("idty");
                $('#izinonay_izinturu').text(d.attr("izin"));
                $('#izinonay_izinbaslangic').text(d.attr("baslangic"));
                $('#izinonay_izinbitis').text(d.attr("bitis"));
                $('#izinonay_izinaciklama').text(d.attr("aciklama"));
                $('#izinonay_izincount').text(d.attr("gun"));
                $('#izinOnayId').val(d.attr("i"));
                $('#izinOnayModal').modal("toggle");
            }
        }
    })
}

function s_IzinTalep(type){
    $.ajax({
        type:"post",
        url:url+"&act=s_izin_talep",
        data:$('#izinOnayPostForm').serialize()+"&type="+type,
        success:function(data){
            var s = $(data).find("result");
            if(s.attr("status")=="OK"){
                $('#izinOnayId').val("");
                $('#izinOnayModal').modal("toggle");
                showNotify("Başarılı","success",s.text(),"",true);
                g_DoRqst();
            }else{
                showNotify("Hata","error",s.text(),"",true);
            }
        }
    })
}

