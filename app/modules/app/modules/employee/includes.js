$(document).ready(function() {
    
    $('#printButton').click(function(){
        PrintElem('empFastModal');
    })
    
    var $datatable = $('#datatable-checkbox');
        
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
        
        $.ajax({
            type:"POST",
            url:url+"&act=get_employee_list",
            data:$('#referencePostForm').serialize(),
            success:function(data){
                
                var status = $(data).find("result").attr("status");
                if(status=="OK"){
                    $(data).find("emp").each(function(){
                        var id = $(this).attr("id");
                        var name = $(this).attr("name");
                        var surname = $(this).attr("surname");
                        var position = $(this).attr("position");
                        var tc = $(this).attr("tc");
                        var phone = $(this).attr("phone");
                        var birthdate = $(this).attr("birthdate");
                        var p = $(this).attr("p");
                        
                        addData = [];
                        addData.push("<input type='checkbox' id='"+id+"' name='"+id+"'>");
                        addData.push("<img src='"+p+"' width='50%'/>");
                        addData.push(name);addData.push(surname);
                        addData.push(position);addData.push(phone);addData.push(tc);addData.push(birthdate);
                        addData.push("<a href='index.php?pid="+PID+"&sid=calisan_ekle&i="+id+"' class='fa fa-edit img_cursor' style='font-size:20px;'></a>&nbsp;&nbsp;&nbsp;"+'<a onclick="emp_fast_look(\'' + id + '\')" aria-hidden="true" data-toggle="modal" data-target=".emp_sum_modal" class="fa fa-eye img_cursor" style="font-size:20px;"></a>');
                        listTable.fnAddData(addData);
                    })
                }
            }
        })
        
        
        $datatable.on('draw.dt', function() {
          $('input').iCheck({
            checkboxClass: 'icheckbox_flat-green'
          });
        });

         $('input[type=search]').keyup(function(){
        this.value = this.value.turkishToUpper();
        });
        
      });

function emp_fast_look(id){
    //$('#emp_fast_look').html("Yükleniyor...");
    $.ajax({
        type:"POST",
        url:url+"&act=get_emp_ql",
        data:"i="+id, //EKARE_TODO replace withID
        success:function(data){
            var s = $(data).find("result");
            if(s.attr("status")=="OK"){
                var d = $(data).find("ql");
                $('#ql_personel_adi').text(d.attr("emp_name")+" "+d.attr("emp_surname"));
                $('#ql_personel_foto').attr("src",d.attr("p"));
                $('#ql_personel_adres').text(d.attr("address"));
                $('#ql_personel_is').text(d.attr("job"));
                $('#ql_personel_telefon').text(d.attr("gsm"));
                $('#lbl_personel_gorevi').text(d.attr("job"));
                $('#lbl_personel_isebaslama').text(d.attr("work_start"));
                $('#lbl_personel_brutmaas').text(d.attr("gross_wage"));
                $('#lbl_personel_kimlikno').text(d.attr("citizenid"));
                $('#lbl_personel_dogumtarihi').text(d.attr("birthdate"));
                $('#lbl_personel_dogumyeri').text(d.attr("birth_place"));
                $('#lbl_personel_cinsiyet').text(d.attr("gender"));
                $('#lbl_personel_medenidurum').text(d.attr("marital"));
                $('#lbl_personel_hesapno').text(d.attr("iban"));
            }else{
                
            }
        }
    })
}


