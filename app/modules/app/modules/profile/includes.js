var izinList;
$(document).ready(function(){
    var $datatable = $('#izin_datatable');
    izinList = $datatable.dataTable({
      'columnDefs': [
        { orderable: false, targets: [0] }
      ], "language": {
        "lengthMenu": "Her Sayfa _MENU_ kayıt ",
        "zeroRecords": "Kayıt Bulunamadı",
        "info": "Gösterilen: _PAGE_ Toplam Sayfa: _PAGES_",
        "infoEmpty": "Kayıt Yok",
        "infoFiltered": "( _MAX_ kayıt içinden filtrelendi)",
        "paginate": {
            "previous": "Önceki",
            "next":"Sonraki"
        }
    },"bFilter": true
    });
                
    //get_izin
    g_Izin();
    
    $('#izinModal').on('show.bs.modal', function (e) {
    // do cool stuff here all day… no need to change bootstrap
    App_GetSelectItems("izin_turu",0,"dayoff_types",0);
    })
    
    
        //onclick
        $('#izinSaveButton').click(function(){
            if (validator.checkAll($('#izinPostForm'))) {
                $.ajax({
                    type:"POST",
                    url:url+"&act=s_izin",
                    data:$('#izinPostForm').serialize(),
                    success:function(data){
                        var s = $(data).find("result");
                        if(s.attr("status")=="OK"){
                            $('#izinPostForm')[0].reset();
                            showNotify("Başarılı","success",s.text(),"",true);
                            $('#izinModal').modal('toggle');
                            g_Izin();
                        }else{
                            showNotify("Hata","error",s.text(),"",true);
                        }
                    }
                })
            }
        })
})

$(function() {
Morris.Bar({
element: 'graph_bar',
data: [
{ "period": "Oca", "Çalışma Saati": 80 }, 
{ "period": "Sub", "Çalışma Saati": 125 }, 
{ "period": "Mar", "Çalışma Saati": 176 }, 
{ "period": "Nis", "Çalışma Saati": 224 }, 
{ "period": "May", "Çalışma Saati": 265 }, 
{ "period": "Haz", "Çalışma Saati": 314 }, 
{ "period": "Tem", "Çalışma Saati": 347 }, 
{ "period": "Agu", "Çalışma Saati": 287 }, 
{ "period": "Eyl", "Çalışma Saati": 240 }, 
{ "period": "Eki", "Çalışma Saati": 211 }
],
xkey: 'period',
hideHover: 'auto',
barColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
ykeys: ['Çalışma Saati', 'sorned'],
labels: ['Çalışma Saati', 'SORN'],
xLabelAngle: 60,
resize: true
});

$MENU_TOGGLE.on('click', function() {
$(window).resize();
});
});

function g_Izin(){
    $.ajax({
        type:"POST",
        url:url+"&act=g_izin",
        success:function(data){
            var s = $(data).find("result").attr("status");
            if(s=="OK"){
                var html = "";
                izinList.fnClearTable();
                $(data).find("i").each(function(){
                addData = [];
                addData.push($(this).attr("n"));
                addData.push($(this).attr("s"));
                addData.push($(this).attr("f"));
                addData.push($(this).attr("a"));
                addData.push("<button type='button' class='btn btn-sm btn-success'><i class='fa fa-print'></i>Yazdır</button>");
                izinList.fnAddData(addData);
               })
            }
        }
    });
}

function changePass(){
    $('#pass_stat').text("");
    if($('#profile_old_pass').val()==""){
        $('#profile_old_pass').focus();
        return;
    }
    if($('#profile_new_pass').val()==""){
        $('#profile_new_pass').focus();
        return;
    }
    if($('#profile_new_pass_again').val()==""){
        $('#profile_new_pass_again').focus();
        return;
    }
    if($('#profile_new_pass').val()!==$('#profile_new_pass_again').val()){
        $('#pass_stat').text("Şifreler Uyuşmuyor");
        return;
    }
    $.ajax({
        type:"POST",
        url:url+"&act=s_p",
        data:$('#passForm').serialize(),
        success:function(data){
            
        }
    })
}