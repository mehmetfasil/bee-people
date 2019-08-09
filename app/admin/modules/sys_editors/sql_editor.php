<?php
defined("PASS") or die("Dosya yok!");
define("CONF_ADMIN_SCREEN_WIDTH",1100);
?>
<script type="text/javascript" src="objects/js/jquery/plugin/common.js"></script>
<script type="text/javascript" src="objects/js/jquery/plugin/tree-min.js"></script>
<script type="text/javascript" src="objects/js/jquery/plugin/loading.js"></script>
<script type="text/javascript" src="objects/js/calendar/core.js"></script>
<script type="text/javascript" src="objects/js/calendar/calendar-setup.js"></script>
<script type="text/javascript" src="objects/js/calendar/lang/tr.js"></script>
<script type="text/javascript">
//<![CDATA[

// prototype functions


var url='<?=CONF_MAIN_PAGE?>?admin&pid=<?=PID?>&sid=sqlquery'
var activedb="";
var activetable="";
$(document).ready(function(){
	$("#tree").jstree({
		collapseOthers:true,
		onClick:function(obj,path){
			// aktif databasi set ediyoruz
			activedb=path[0];
			// secilen veritabanı kısmına basıyoruz db ismini
			$("#choosendb").text(path[0]);
			// secilen tablo degiskenini unset ediyoruz
			activetable=null;
			// secilen tablo kısmını temizliyoruz
			$("#choosentable").text("--");
			// tablolar ve viewleri goster aktif oluyor
			$("#btnListTables").removeAttr("disabled");
			$("#btnListViews").removeAttr("disabled");
			$("#btnDialogQuery").removeAttr("disabled");
			// alanı temizlioruz
			$("#contentArea").html("");			
			// tablolar secilmisse
			if(path[1]!=null && path[1]=="Tablolar" && obj.siblings("ul").css("display")=="none"){
				//listTables({db:activedb,x:0,y:30});
			}
			// tablo secilmisse
			if(path[2]!=null && (path[1]=="Tablolar" || path[1]=="Görünümler")){
				showTableData(path[2])
			}
		},
		async:{
			type:"post",
			url:url,
			data:{
				act:"dbtree",
			}
		}
	});
});

function listTables(options){
	options=$.extend({
		type:"table"
	},options);
	
	// ajax
	$.ajax({
		type:"get",
		url:url+"&act=listTables",
		data:{
			db:options.db,
			type:options.type
		},
		error:function(){
			messageDialog("İsteğiniz Gerçekleştirilemedi !",{icon:"warning.gif"})
		},
		success:function(data){
			var result="";
			if($(data).find("list").children("item").length>0){
				// başlıklar
				result="<table cellspacing='0' cellpadding='4' width='100%' class='gridTable'>"
				result+="<tr>"
				result+="<td class='gridTitle' align='center' width='30'>S.No<\/td>"
				result+="<td class='gridTitle' align='center' width='200'>Tablo<\/td>"
				result+="<td class='gridTitle' align='center' width='100'>Toplam Kayıt<\/td>"
				result+="<td class='gridTitle' align='center' width='50'>Türü<\/td>"
				result+="<td class='gridTitle' align='center' width='130'>Karşılaştırma<\/td>"
				result+="<td class='gridTitle' align='center' width='70'>Boyut<\/td>"
				result+="<td class='gridTitle'><\/td>"
				result+="<\/tr>"
				// veriler
				$(data).find("list").children("item").each(function(i){
					result+="<tr>"
					result+="<td class='gridRow' align='center'>"+(i+1)+"<\/td>"
					result+="<td class='gridRow' >"+$(this).text()+"<\/td>"
					result+="<td class='gridRow' align='right'>"+$(this).attr("rows")+"<\/td>"
					result+="<td class='gridRow' align='center'>"+$(this).attr("engine")+"<\/td>"
					result+="<td class='gridRow' align='center'>"+$(this).attr("collation")+"<\/td>"
					result+="<td class='gridRow' align='right'>"+$(this).attr("length")+"<\/td>"
					result+="<td class='gridRow' >"
					result+="<a href='javascript:void(0);' title='Verileri Görüntüle' onclick=\"showTableData('"+$(this).text()+"')\"><img border='0' src='objects/icons/16x16/data_view.png' /><\/a>"
					result+="<\/td>"
					result+="<\/tr>"
				});
				result+="<\/table>"
			}else{
				result="<div class='caution'><img src='objects/icons/32x32/caution.gif' style='vertical-align:middle' />&nbsp;Sonuç Bulunamadı !<\/div>"
			}
			$("#contentArea").html(result);
			// satırların hoverı
			$("#contentArea > .gridTable > tr:gt(0) > td").hover(function(){
				alert("as")
				$(this).parent().children().attr("class","gridRowOver")
			},function(){
				$(this).parent().children().attr("class","gridTitle")
			});
		}
	});
}

function showTableData(table){
	// aktif tablo
	activetable=table;
	// secilen tablo kısmına basıyoruz db ismini
	$("#choosentable").text(table);
	getTableData({x:0,y:20})
}

function getTableData(options){
	// database secilmemisse
	if(activedb==""){
		messageDialog("Veritabanını Seçmelisiniz !",{type:"caution.gif"});
		return;
	}else if(activetable==""){
		messageDialog("Tablo Seçmelisiniz !",{type:"caution.gif"});
		return;		
	}
	// onceki eventları kaldırıyoz
	$("#contentArea .gridTable").find("td").unbind();
	// ajax
	$.ajax({
		type:"get",
		url:url+"&act=getTableData&x="+options.x+"&y="+options.y,
		data:{
			db:activedb,
			table:activetable
		},
		error:function(){
			messageDialog("İsteğiniz Gerçekleştirilemedi !",{icon:"warning.gif"})
		},
		success:function(data){
			var result="";
			if($(data).find("fields").children("field").length>0){
				// paging
				var total=parseInt($(data).find("stat").attr("total"));
				var x=parseInt($(data).find("stat").attr("x"))
				var y=parseInt($(data).find("stat").attr("y"))
				var fn='getTableData'
				var options;
				// yeni olustur ekliyoruz
				result+="<div style='padding-left:5px'>"
				result+="<button class='button' id='btnAddNewRow'>Yeni Ekle<\/button>&nbsp;"
				result+="<button class='button' id='btnRefreshData'>Yenile<\/button>&nbsp;"
				result+="<button class='button' id='btnTruncateTable'>Tabloyu Boşalt<\/button>"
				result+="<\/div>"
				result+="<div style='margin-top:20px'>"
				// paging
				result+=$(data).find("list").children("item").length>0 ? paging("top",total,x,y,fn) : ""	
				// başlıklar
				result+="<table cellspacing='0' cellpadding='0' width='100%' class='gridTable'>"
				result+="<tr>"
					result+="<td class='gridTitle'><\/td>"
				$(data).find("fields").children("field").each(function(){
					result+="<td class='gridTitle' nowrap=''>"
					result+="<div style='padding:4px' >"+$(this).text()+"<\/div>"
					result+="<span class='hidden'>"+$(this).attr("options")+"<\/span>"
					result+="<\/td>"
				});
				result+="<\/tr>"
				// veriler
				if($(data).find("list").children("item").length>0){
					$(data).find("list").children("item").each(function(i){
						result+="<tr>"
						result+="<td align='center' class='gridTitle' style='width:50px' nowrap='nowrap'>"
									+"<img src='objects/icons/16x16/data_ok.png' style='cursor:pointer' title='Kaydet' />&nbsp;"
									+"<img src='objects/icons/16x16/data_delete.png' style='cursor:pointer' title='Sil' />"
									+"<\/td>"		
						$(this).children().each(function(){							
							options=eval("("+$(data).find("fields").children("field:contains("+this.tagName+")").attr("options")+")")																						
							result+="<td class='gridRow' nowrap='nowrap'>"
							result+="<div style='padding:4px;height:15px' >"+(options.type.indexOf("text")!=-1 ? "MEMO" : $(this).text())+"<\/div>";
							result+="<span style='display:none'>"+$(this).text()+"<\/span>";
							result+="<\/td>"
						});
						result+="<\/tr>"
					});
				}else {
					// bos bi satır atıyoz	
					result+="<tr title='new'>"
						result+="<td align='center' class='gridTitle' style='width:50px' nowrap='nowrap'>"
									+"<img src='objects/icons/16x16/data_ok.png' style='cursor:pointer' title='Kaydet' />&nbsp;"
									+"<img src='objects/icons/16x16/data_delete.png' style='cursor:pointer' title='Sil' />"
									+"<\/td>"				
						$(data).find("fields").children("field").each(function(){
							options=eval("("+$(this).attr("options")+")")
							result+="<td class='gridRow' nowrap=''>"
							result+="<div style='padding:4px;height:15px'>"+(options.defaultvalue!=null ? options.defaultvalue : "")+"<\/div>"
							result+="<span class='hidden'>"+(options.defaultvalue!=null ? options.defaultvalue : "-")+"<\/span>"
							result+="<\/td>"
						});
					result+="<\/tr>"	
				}
				result+="<\/table>"						
				result+="<\/div>"
			}else{
				result="<div class='caution'><img src='objects/icons/32x32/caution.gif' style='vertical-align:middle' />&nbsp;Sonuç Bulunamadı !<\/div>"
			}
			$("#contentArea").html(result);
			$(".gridTable").grid();
		}
	});
}

function listViews(){
	
}

function getViewData(){
	
}

// grid yapıyoruz
$.fn.grid=function(){
	$(this).each(function(){
		// parent table
		var table=$(this)
		// tablonun genisligini sabitliyoruz
		table.attr("width",table.width());
		// sutunların genisligini sabitliyoruz
		table.find("tr:eq(0)").find("td").each(function(){
			$(this).css("width",$(this).width()+"px");
		});
		// satırların hoverı
		$(this).find("tr:gt(0) > td:not(:nth-child(1))").hover(function(){
			$(this).siblings("td").not($(this)).not(":eq(0)").attr("class","gridRowOver")
			$(this).attr("class","rowSelected").css("cursor","text")
		},function(){
			$(this).siblings("td").not($(this)).not(":eq(0)").attr("class","gridRow")
			$(this).attr("class","gridRow")
		});

		// sutun tıklanınca ait oldugu baslıgın ozelliklerine gore alan olacak
		var options,index;
		$(this).find("tr:gt(0) > td:not(:nth-child(1))").click(function(){
			index=$(this).parent().children().index(this);
			options=eval("("+table.find("tr:eq(0) > td:eq("+index+") > span:first").text()+")");
			if($(this).children(":input").length>0)
			return;

			// degiskenler
			var input,values,html,buttons,text,parent;
			// enum ise
			if(options.type=="enum"){ // enum
				input=$("<select />")
				values=options.values.split(",");
				for(var i=0;i<values.length;i++){
					$("<option "+($(this).children("span:first").text()==values[i] ? "selected" : "")+" />")
					.attr("value",values[i]).text(values[i]).appendTo(input)
				}
				input.html(html)
				input.css({
					width:$(this).width(),
					height:$(this).height(),
					backgroundColor:"#004080",
					color:"#FFFFFF"
				})
				.blur(function(){
					parent=$(this).parent();
					// veri degismisse eskisini saklıyoruz
					if(parent.children("span:eq(1)").length==0 && parent.children("span:first").text()!=$(this).val()){
						$("<span style='display:none'/>").appendTo(parent).text(parent.children("span:first").text());
					}
					// verileri giriyoruz
					parent.children("div:first").text($(this).val());
					parent.children("span:first").text($(this).val());
					$(this).remove();
					// veriyi gosteriyoruz
					parent.children("div:first").show();
				})
				.keypress(function(e){					
					e.which==13 ? $(this).blur() : "";
				});				
				$(this).children().hide();
				input.appendTo($(this)).focus();
			} else if(options.type.indexOf("text")!=-1){ // tinytext,text,longtext
				// buttons nesnesinin icinde kullanmak icin referans veriyoruz objeyi
				var obj=$(this)
				// oncekini kaldırıyoz
				$("#containerTextArea").remove();
				var container=$("<div />")
				.attr("id","containerTextArea")
				.hide()
				.appendTo("body")
				var textarea=$("<textarea style='width:400px;height:100px' />")
					.val(obj.children("span:first").text())
					.appendTo(container)
				buttons={
					type:"OKCANCEL",
					functionOK:function(){
						// veri degismisse eskisini saklıyoruz						
						if(obj.children("span:eq(1)").length==0 && obj.children("span:first").text()!=textarea.val()){
							$("<span style='display:none'/>").appendTo(obj).text(obj.children("span:first").text());
						}
						obj.children("span:first").text(textarea.val());
						layer("hide","containerTextArea");
					},functionCANCEL:function(){
						layer("hide","containerTextArea");
					}
				}
				layer("show","containerTextArea",405,100,"İçerik Düzenle",buttons)
			}else { // varchar,int kısaca enum ve text olmayanlar		
				input=$("<input />")
				.attr({
					id:options.type=="date" || options.type=="datetime" ? "dateField" : "",
					type:"text",
					maxlength:options.length
				})
				.val($(this).children("span:first").text())
				.css({
					border:"none",
					width:$(this).width()-8,
					height:$(this).height()-8,
					backgroundColor:"#004080",
					color:"#FFFFFF",
					padding:"4px 4px 4px 3px"
				})
				.focus(function(){
					// tipi date veya datetime ise takvim gosteriyoruz
					if(options.type=="date" || options.type=="datetime"){
						Calendar.setup({
							inputField : "dateField", // id of the input field
							ifFormat : "%Y-%m-%d"+(options.type=="datetime" ? " %H:%M:%S" : ""), // format of the input field
							align : "B1", // alignment (defaults to "Bl")
							singleClick : true,
							eventName:"focus",
							showsTime : (options.type=="datetime" ? true : false),
							onClose:function(){
								input.blur();
								window.calendar.hide();
							}
						});						
					}
				})
				.blur(function(){
					parent=$(this).parent();
					text=$.trim($(this).val());										
					// veri degismisse eskisini saklıyoruz
					if(parent.children("span:eq(1)").length==0 && parent.children("span:first").text()!=text){
						$("<span style='display:none'/>").appendTo(parent).text(parent.children("span:first").text());
					}
					// verileri giriyoruz					
					parent.children("div:first").text(text);
					parent.children("span:first").text(text);
					$(this).remove();
					// veriyi gosteriyoz
					parent.children("div:first").show();
				})
				.keypress(function(e){					
					e.which==13 ? $(this).blur() : "";
				});
				// tipine gore karakter yazılabilecek
				options.type.indexOf("int")!=-1 ? input.ctype("numeric") : "";				
				$(this).children().hide();
				input.appendTo($(this)).focus();
			}
		});
		
		// gride yeni satır ekliyoruz
		$("#btnAddNewRow").click(function(){
			// eger bos bir satır eklenmemisse
			if(table.find("tr[title='new']").length>0)
				return;
				
			// insertBefore(table.find("tr:eq(1):first"))			
			var row=table.find("tr:eq(1)").clone(true);
			
			// yeni satır oldugunu anlayacagız
			row.attr("title","new");
			// default degerleri girecegiz
			var options;
		
			row.find("td:gt(0)").each(function(){
				// kopyaladıgımız satırın degisikliklerini kaldırıyoruz
				$(this).find("span:eq(1)").remove();
				options=table.find("tr:eq(0)").children("td:eq("+$(this).parent().children("td").index($(this))+")").find("span:first").text()
				options=eval("("+options+")")
				
				if(options.type.indexOf("text")!=-1){								
					$(this).find("div").text("MEMO");
				}else if(options.defaultvalue!=null){
					$(this).find("div").text(options.defaultvalue);
					$(this).find("span").text(options.defaultvalue);
				}else {
					$(this).find("div").text("");
					$(this).find("span").text("");
				}				
			});
			// satırı tabloya ekliyoruz
			row.insertAfter(table.find("tr:eq(0)"));
		});
		
		// tablo verilerini yeniliyoruz
		$("#btnRefreshData").click(function(){
			showTableData(activetable);
		});
		
		// tabloyu truncate ediyoruz
		$("#btnTruncateTable").click(function(){
			truncateTable(activetable);
		});
		// update işlemi 
		$(this).find("tr:gt(0)").find("td:nth-child(1)").find("img:eq(0)").click(function(){
			updateData($(this).parent());
		});
		// silme işlemi
		$(this).find("tr:gt(0)").find("td:nth-child(1)").find("img:eq(1)").click(function(){
			var obj=$(this).parent();
			// birden fazla satır yoksa kaldırmıyoruz
			if(obj.parent().siblings().length<=1 && obj.parent().attr("title")=="new")
				return false;
			// eger satır yeni ise kaldırıyoz
			if(obj.parent().attr("title")=="new"){
				obj.parent().remove();
				return;
			}
			messageDialog("Silmek istediğinize emin misiniz ?",{
				icon:"caution.gif",
				type:"YESNO",
				functionYES:function(){
					deleteRow(obj);
				}
			});			
		});
		
		$(this).find("tr:gt(0) > td:nth-child(1)").hover(function(){
			$(this).css("cursor","pointer");
			$(this).siblings("td").attr("class","gridRowOver")
		},function(){
			$(this).siblings("td").attr("class","gridRow")
		});
		
	});	
}


function updateData(obj){
	// sutun bilgilerinin oldugu ilk satır
	var meta=obj.parent().parent().children("tr:eq(0)");
	// sutun,index,values,modified
	var index,field,oldData=new Array(),newData=new Array(),modified=0;
	// gecerli satır
	obj.siblings("td").each(function(){
		// index
		index=$(this).parent().children().index(this)			
		// field
		field=meta.children(":eq("+index+")").children(":first").text()		
		 // eger degisiklik varsa	
		if($(this).find("span:eq(1)").length>0){
			// eski verileri belirliyoruz	
			oldData[field]=$(this).find("span:eq(1)").text();
			// yeni verileri belirliyoruz	
			newData[field]=$(this).find("span:eq(0)").text();			
			// degismis
			modified++;
		}else if($(this).find("span:eq(0)").length>0){
			oldData[field]=$(this).find("span:eq(0)").text();	
		}
	});
	
	// eger degisiklik olmussa veya satır yeni eklenmisse
	if(modified>0 || obj.parent().attr("title")=="new"){	
		// dizilerimiz stringe ceviriyoz
		var data="";
		// eskiler
		for(var e in oldData){
			data+="old_"+e+"="+oldData[e]+"&"
		}
		// yeniler
		for(var e in newData){
			data+="new_"+e+"="+newData[e]+"&"
		}
		// sunucuya gidiyor
		$.ajax({
			type:"post",
			url:url+"&act=update&db="+activedb+"&table="+activetable+"&progress="+(obj.parent().attr("title")=="new" ? "insert" : "update"),
			data:data,
			error:function(){
				messageDialog("İsteğiniz Gerçekleştirilemedi !",{icon:"warning.gif"});
			},
			success:function(data){
				var result=$(data).find("result");
				if(result.length>0){
					if(result.attr("type")=="OK"){
						// yeni satır eklenmisse, artık new ifadesini kaldırıyoruz
						obj.parent().attr("title")=="new" ? obj.parent().removeAttr("title") : "";
						messageDialog("Başarıyla Kaydedildi",{icon:"info.gif"});
					}else{
						messageDialog(result.text(),{icon:"warning.gif"});
					}
				}
			}
		});	
	}
}

function deleteRow(obj){				
	// sutun bilgilerinin oldugu ilk satır
	var meta=obj.parent().parent().children("tr:eq(0)");
	// sutun,index,values,modified
	var index,data="",field,fields=new Array();
	// gecerli satır
	obj.siblings("td").each(function(){
		// index
		index=$(this).parent().children().index(this)			
		// field
		field=meta.children(":eq("+index+")").children(":first").text()		
		
		if($(this).find("span:eq(0)").length>0){
			fields[field]=$(this).find("span:eq(0)").text();	
		}
	});
	
	for(var e in fields){
		data+=e+"="+fields[e]+"&"
	}
	// sunucuya gidiyor
	$.ajax({
		type:"post",
		url:url+"&act=deleteRow&db="+activedb+"&table="+activetable,
		data:data,
		error:function(){
			messageDialog("İsteğiniz Gerçekleştirilemedi !",{icon:"warning.gif"});
		},
		success:function(data){
			var result=$(data).find("result");
			if(result.length>0){
				if(result.attr("type")=="OK"){					
					messageDialog("Başarıyla Silindi",{icon:"info.gif"});
					showTableData(activetable);
				}else{
					messageDialog(result.text(),{icon:"warning.gif"});
				}
			}
		}
	});		
}

function truncateTable(table){
	messageDialog("Tabloyu boşaltmak istediğinize emin misiniz ?",{
		type:"YESNO",
		icon:"caution.gif",
		functionYES:function(){
			$.ajax({
				type:"get",
				url:url,
				data:{
					act:"truncateTable",
					db:activedb,
					table:table
				},
				error:function(){
					messageDialog("İsteğiniz Gerçekleştirilemedi !",{icon:"caution.gif"});
				},
				success:function(data){
					if($(data).find("result").attr("type")=="OK"){
						messageDialog("Tablo Boşaltıldı",{icon:"info.gif",functionOK:function(){
							showTableData(table);
						}});
					}else{
						messageDialog($(data).find("result:first").text(),{icon:"warning.gif"});
					}
				}
			});
		}
	});
}

// sorgu sonucundan gelen veriyi listeliyoruz
function listData(options){
	options=$.extend({x:0,y:30},options);
	var query=$.trim($("#query").val());	

	$.ajax({
		type:"get",
		url:url+"&act=doQuery",
		data:{
			x:options.x,
			y:options.y,
			db:activedb,
			query:query
		},
		error:function(){
			messageDialog("İsteğiniz Gerçekleştirilemedi !",{icon:"warning.gif"});
		},
		success:function(data){			
			if($(data).find("result").length>0){ // eger list yok ise
				if($(data).find("result:first").attr("type")=="OK"){ // hata yok ise
					$("#queryResult").text("Etkilenen Satır : "+$(data).find("result:first").text());
				}else{
					$("#queryResult").text("Hata Oluştu : "+$(data).find("result:first").text());					
				}
			}else { 
				var result=""
				// sutunlar varsa
				if($(data).find("columns").children("item").length>0){ 
					// paging
					var total=$(data).find("stat").attr("total")
					var x=$(data).find("stat").attr("x")
					var y=$(data).find("stat").attr("y")
					var fn='listData'
					// veri varsa paging gosteriyoz
					result+=$(data).find("list").children("item").length>0 ? paging("top",total,x,y,fn) : "";
					result+="<table cellpadding='4' cellspacing='0' width='100%' class='gridTable'>"
						result+="<tr>"
						$(data).find("columns").children("item").each(function(){
								result+="<td class='gridTitle' nowrap='nowrap'>"+$(this).text()+"<\/td>"							
						});
						result+="<\/tr>"
						if($(data).find("list").children("item").length>0){							
							var type="";
							$(data).find("list").children("item").each(function(){
								result+="<tr>"
								$(this).children().each(function(){
									result+="<td class='gridRow' nowrap='nowrap'>"+$(this).text()+"<\/td>"
								});
								result+="<\/tr>"
							});							
						}
					result+="<\/table>"
				}else{
					result="<div class='caution'><img src='objects/icons/32x32/caution.gif' style='vertical-align:middle'>&nbsp;Sonuç Bulunamadı !<\/<div>"
				}
				// htmli basıyoruz
				$("#dialogQueryResult").html(result);
				// memo yazan text alanlarına tıklanınca
				$("#dialogQueryResult").find(".gridTable").find("td").find("span").each(function(){
					// onceki eventlar kalkıyor
					$(this).parent().unbind();
					$(this).parent().click(function(){
						// kaldırıyoz
						$("#dialogTextContent").remove()
						$("<div />")
							.attr("id","dialogTextContent")
							.css({
								padding:"10px 0 0 10px",								
								width:580,height:300,
								overflow:"auto",display:"none"
							})
							.html($(this).children("span:first").html())
							.appendTo("body")
						// layer gosteriyoz
						layer("show","dialogTextContent",600,300,"İçerik");	
					});
				});
			}
					
		}
	});

}

$(document).ready(function(){
	// tasıyıcı genisligi ayarlıyoz
	$("#contentArea").width($(document).width()-300)
	// tables
	$("#btnListTables").click(function(){
		listTables({db:activedb});
	});	
	// views
	$("#btnListViews").click(function(){
		listTables({db:activedb,type:"view"});
	});
	// sorgu dialogunu acıyoz
	$("#btnDialogQuery").click(function(){
		if(activedb=="")
			return;
			layer("show","dialogExecuteQuery",400,200,"SQL Çalıştır",{
				type:"OKCANCEL",
				functionOK:function(){
					var query=$.trim($("#query").val());
					// eger soru bossa
					if(query.length<=0){
						messageDialog("Sorgu Yazmalısınız",{icon:"caution.gif",functionOK:function(){$("#query").focus()}});
						return;
					}
					layer("show","dialogQueryResult",700,500,"Sorgu Sonucu");
					listData({x:0,y:30});
				},
				functionCANCEL:function(){
					layer("hide","dialogExecuteQuery");
				}
			});
	});
	
});

//]]>
</script>
<div id="main-container">
	<div style="float:left">
		<fieldset>
		<legend>Veritabanları</legend>
		<div class="container" id="tree"></div>
	</fieldset>	
	</div>
		<div>
			<fieldset>
				<legend>İçerik</legend>
				<div style="float:left">
				<b>Veritabanı</b> : <span id="choosendb">--</span><br />
				<b>Tablo</b> : <span id="choosentable">--</span>						
			</div>
			<div style="float:right">
				<button class="button" type="button" id="btnListTables" disabled="disabled">Tablolar</button>&nbsp;
				<button class="button" type="button" id="btnListViews" disabled="disabled">Görünümler</button>
				<button class="button" type="button" id="btnDialogQuery" disabled="disabled">Sorgu Çalıştır</button>
			</div>
		<div id="contentArea" style="padding-top:10px;clear:both;height:350px;overflow:auto;"></div>
			</fieldset>
		</div>			
</div>
<div id="dialogExecuteQuery" style="display:none;height:120px">
	<table cellpadding="4" cellspacing="0" width="100%">
		<tr>
			<td class="gridLeft" style="text-align:left;">SQL Sorgusu</td>
			<td class="gridRight">
				<?=formElement("textarea","query","","",""," style='padding:5px 0 0 5px;width:300px;height:100px'")?>
			</td>			
		</tr>	
	</table>
</div>
<div id="dialogQueryResult" style="display:none;height:400px;overflow:auto"></div>	