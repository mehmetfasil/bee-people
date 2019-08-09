$(document).ready(function(){
    
        
    $('#bordroButton').click(function(){
        $.ajax({
            type:"post",
            url:url+"&act=get_bordro",
            data:"y="+$('#bordro_yil').val()+"&a="+$('#bordro_ay').val()+"&t=0",
            success:function(data){
                var status = $(data).find("result").attr("status");
                if(status=="PREVIOUS_RECORD"){
                    
                }else{
                    var s = $(data).find("result");
                    if(s.attr("status")=="OK"){
                        $('#bordro_table').DataTable().clear().destroy();
                        var $datatable = $('#bordro_table');
                        var listTable = $datatable.dataTable({
                          'columnDefs': [
                            { orderable: false, targets: [0] }
                          ],dom: 'Bfrtip',
                          buttons: [
                             { extend: 'excel', text: 'Excel' },
                               { extend: 'print', text: 'Yazdır' }
                            ],"language": {
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
        
                        $(data).find("bordro").each(function(){
                            addData = [];
                            addData.push($(this).attr("ci"));addData.push($(this).attr("ename"));
                            addData.push($(this).attr("gw")); addData.push($(this).attr("ns"));
                            addData.push("<a target='_blank' href='"+$(this).attr("b")+"' class='btn btn-primary'>Bordro</a>");
                            addData.push("<button href='"+$(this).attr("b")+"' class='btn btn-success'><i class='fa fa-envelope'></i>&nbsp;Mail</button>"+"<button href='"+$(this).attr("b")+"' class='btn btn-success'><i class='fa fa-mobile'></i>&nbsp;SMS</button>");
                            listTable.fnAddData(addData);
                        })
                        $('#bordro_table').removeClass("display-none");
                    }else{
                        showNotify("Hata","error",s.text(),"",true);
                    }
                }
            }
        })
    })
})