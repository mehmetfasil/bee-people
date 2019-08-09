
      $(window).load(function() {
        var date = new Date(),
            d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear(),
            started,
            categoryClass;
        
        var calendar = $('#calendar').fullCalendar({
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
          },
          lang:'tr',
          selectable: true,
          selectHelper: true,
          select: function(start, end, allDay) {
            
            $('#fc_create').click();

            started = start;
            ended = end;

            $(".antosubmit").on("click", function() {
              var title = $("#title").val();
              var descr = $('#descr').val();
              if (end) {
                ended = end;
              }

              categoryClass = $("#event_type").val();

              if (title) {
                calendar.fullCalendar('renderEvent', {
                    title: title,
                    start: started,
                    end: end,
                    allDay: false
                  },
                  true // make the event "stick"
                );
                //sendtodb
                SaveEvent(1,title,descr,started,end,0);
              }

              $('#title').val('');

              calendar.fullCalendar('unselect');

              $('.antoclose').click();

              return false;
            });
          },
          eventClick: function(calEvent, jsEvent, view) {
            $('#fc_edit').click();
            $('#title2').val(calEvent.title);
            $('#descr2').val(calEvent.description);
            categoryClass = $("#event_type").val();

            $(".antosubmit2").on("click", function() {
              calEvent.title = $("#title2").val();
              calEvent.description = $('#descr2').val();
              SaveEvent('2',$('#title2').val(),$('#descr2').val(),Date.parse(calEvent.start.format()),Date.parse(calEvent.end.format()),calEvent.id);
              calendar.fullCalendar('updateEvent', calEvent);
              $('.antoclose2').click();
            });

            calendar.fullCalendar('unselect');
          },
          editable: true,
          eventResize: function(event, delta, revertFunc) {
            SaveEvent('2',event.title,event.description,Date.parse(event.start.format()),Date.parse(event.end.format()),event.id);
            revertFunc();
          },timeFormat: 'H(:mm)'
        });
        
            
        GetEvents();
        
        
      });
      
function SaveEvent(UOI,title,desc,start,end,id){
dateObj = new Date(start);
    $.ajax({
        type:"POST",
        url:url+"&act=save",
        data:"uoi="+UOI+"&title="+title+"&desc="+desc+"&start="+start+"&end="+end+"&id="+id,
        success:function(data){
            var d = $(data).find("result");
            if(d.attr("status")=="OK"){
                showNotify("Başarılı","success",d.text(),"",true);
                $('#title').val("");$('#descr').val("");
            }else{
                showNotify("Hata","error",d.text(),"",true);
            }
        }
    })
}

function GetEvents(){
    $.ajax({
        type:"POST",
        url:url+"&act=get_events",
        success:function(data){
            var s = $(data).find("result");
            if(s.attr("status")=="OK"){
             $(data).find("event").each(function(){
                var startDate = $(this).attr("start");
                var endDate = $(this).attr("end");
                var event={id:$(this).attr("id") , title: $(this).attr("name"),description: $(this).attr("desc"), start: new Date(parseFloat(startDate)).toISOString(), end: new Date(parseFloat(endDate)).toISOString() };
                $('#calendar').fullCalendar( 'renderEvent', event, true);
             })   
            }
        }
    })
}
      