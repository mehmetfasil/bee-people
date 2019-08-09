function App_GetSelectItems(destination,selectedValue,act,selectedSub){
    $.ajax({
        type:"POST",
        url:urlCommon+act,
        data:"id="+selectedValue,
        success:function(data){
            var html = "";
            html += "<option value='-1'>Seçiniz</option>";
            $(data).find("item").each(function(){
                var i = $(this).attr("id");
                var name = $(this).attr("name");
                html += "<option value='"+i+"'>"+name+"</option>";
            });
            if( Object.prototype.toString.call( destination ) == '[object Array]' ) {
                destination.forEach(function(el){
                    $('#'+el).html(html);
                })
            }else{
             $('#'+destination).html(html);   
            }
            if(selectedSub!=0){
                $('#'+destination).val(selectedSub);
            }
        },
        error:function (request, status, error) {
        alert(request.responseText);
        }
    });   
}

function App_SetUISelectboxComponents(objectToHandle, list,listItemToShow){
    if(list.lenght()==0){
        return;
    }
    var temp_ = list;
    $('#'+objectToHandle+listItemToShow).removeClass("display-none").addClass("display-block");
    var i = list.indexOf(listItemToShow);
    if(i != -1) {
    	list.splice(i, 1);
    }
    for (index = 0; index < list.length; ++index) {
    $('#'+objectToHandle+list[index]).removeClass("display-block").addClass("display-none");
    }
}


String.prototype.turkishToUpper = function(){
    var string = this;
    var letters = { "i": "İ", "ş": "Ş", "ğ": "Ğ", "ü": "Ü", "ö": "Ö", "ç": "Ç", "ı": "I" };
    string = string.replace(/(([iışğüçö]))+/g, function(letter){ return letters[letter]; })
    return string.toUpperCase();
}

String.prototype.turkishToLower = function(){
    var string = this;
    var letters = { "İ": "i", "I": "ı", "Ş": "ş", "Ğ": "ğ", "Ü": "ü", "Ö": "ö", "Ç": "ç" };
    string = string.replace(/(([İIŞĞÜÇÖ]))+/g, function(letter){ return letters[letter]; })
    return string.toLowerCase();
}


function showNotify(title,type,text,style,hide){
    new PNotify({
          title: title,
          type: type,
          text: text,
          nonblock: {
              nonblock: true
          },
          styling: 'bootstrap3',
          hide: hide,
          delay: 3000
        });
}


function getDateTimeFromUnix(unixTimeFormat) {
    var now     = new Date(unixTimeFormat); 
    var year    = now.getFullYear();
    var month   = now.getMonth()+1; 
    var day     = now.getDate();
    var hour    = now.getHours();
    var minute  = now.getMinutes(); 
    if(month.toString().length == 1) {
        var month = '0'+month;
    }
    if(day.toString().length == 1) {
        var day = '0'+day;
    }   
    if(hour.toString().length == 1) {
        var hour = '0'+hour;
    }
    if(minute.toString().length == 1) {
        var minute = '0'+minute;
    }   
    var dateTime = day+'/'+month+'/'+year+' '+hour+':'+minute;   
     return dateTime;
}


function PrintElem(elem)
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write($('#'+elem).html());
    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    mywindow.print();
    mywindow.close();
    return true;
}