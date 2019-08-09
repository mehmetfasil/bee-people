$(document).ready(function(){
                    
    
    $('#btn_excel').click(function(){
        var myWindow = window.open("index.php?pid=38", "", "width=200,height=100");
    })
    $('#getPuantaj').click(function(){
        if($('#puantaj_start').val().trim()==""){
        $('#puantaj_start').focus();return;}
        if($('#puantaj_end').val().trim()==""){
        $('#puantaj_end').focus();return;}
        $.ajax({
            type:"POST",
            url:url+"&act=pp",
            data:$('#puantajForm').serialize(),
            success:function(data){
                var s = $(data).find("result");
                if(s.attr("status")=="OK"){
                    var html = "";
                    html +="<thead>";
                    html +="<tr>";
                    html +="<td align='center'>Çalışanlar</td>";
                    $(data).find("days").each(function(){
                        html +="<td align='center' style='padding:0px;'><p class='rotate'>"+$(this).attr("shortname")+"</p>"+$(this).attr("num")+"</td>";
                    })
                    html +="<td><p class=''>Tplm</p></td>";
                    html +="</tr>";
                    html +="</thead>"
                    html +="<tbody>";
                    html +="</tbody>";
                    $('#result').html(html);
                    
                   var puantajTable =  $('#result').DataTable({
                        dom: 'Bfrtip',
                        "bSort" : false,
                        "bRetrieve": true,
                        buttons: [
                         { extend: 'excel', text: 'Excel' },
                          { extend: 'pdf', text: 'PDF' },
                           { extend: 'print', text: 'Yazdır' }
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
                        }
                    });
                    
                     $(data).find("emp").each(function(){
                        addData = [];
                        addData.push($(this).attr("name"));
                        $(this).find("d").each(function(){
                            addData.push("<td align='center'><b>"+$(this).text()+"</b></td>");
                        })
                        addData.push("<td align='center'><b>30</b></td>");
                        puantajTable.row.add(addData).draw();
                    })
                    
                    
                      
                    
                }else{
                    showNotify("Hata","error",s.text(),"",true);
                    $('#result').html("");
                }
            }
        })
    })
})