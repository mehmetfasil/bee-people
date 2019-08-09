<? 
defined("PASS") or die("Giriş Engellendi");

?>

<script language="JavaScript" type="text/javascript">
	var url = '<?=CONF_MAIN_PAGE."?admin&pid=".PID?>'
	$(document).ready(function(){
		list();
	});
	
	function list(options){
		options=$.extend({
			x:0,
			y:30
		},options);
		
		$.ajax({
			type:"get",
			url:url+"&sid=query&act=list&groupby=1",
			data:{x:options.x,y:options.y},
			error:function(){
				messageDialog("Bir Hata Oluştu",{icon:"warning.gif"})
			},
			success:function(data){
				if($(data).find("item").length>0){
					var result= ""
					var total=parseInt($(data).find("stat").attr("total"));
					var x=parseInt($(data).find("stat").attr("x"))
					var y=parseInt($(data).find("stat").attr("y"))
					var fn='list'
					var counter=x
					result=paging("top",total,x,y,fn)
					result+="<table width='100%' cellspacing='0' cellpadding='4'>"
						result+="<tr>"
							result+="<td class='gridTitle' width='30' align='center'>ID</td>"
							result+="<td class='gridTitle' >İsim</td>"
							result+="<td class='gridTitle' >Açıklama</td>"
							result+="<td class='gridTitle' width='50'></td>"
						result+="</tr>"
						$(data).find("item").each(function(){
							result+="<tr>"
								result+="<td class='gridRow' align='center'>"+$(this).find("id").text()+"</td>"
								result+="<td class='gridRow'>"+$(this).find("name").text()+"</td>"
								result+="<td class='gridRow'>"+$(this).find("title").text()+"</td>"
								result+="<td class='gridRow' align='center'><a href='javascript:void(0);' onclick=\"showDialogForm("+$(this).find("id").text()+")\"><img src='objects/icons/16x16/edit.png' style='vertical-align:middle;' /></a></td>"
							result+="</tr>"
						});
					result+="</table>"
					result+=paging("bottom",total,x,y,fn)
				} else{
					result="<div class='caution'><img src='objects/icons/32x32/caution.gif' style='vertical-align:middle;' />&nbsp; Sonuç Bulunamadı !</div>"
				}				
				$("#result").html(result);
			}
		});
	}
	
	function showDialogForm(id){
		// clear inputs
		$("#name").val("")
		$("#title").val("")
		
		isNaN(parseInt(id)) ? $("#id").val(0) : $("#id").val(id)
		if(!isNaN(parseInt(id)) && parseInt(id)>0){
			$.ajax({
				type:"get",
				url:url+"&sid=query&act=list",
				data:{id:$("#id").val()},
				error:function(){
					messageDialog("Bir Hata Oluştu",{icon:"caution.gif"})
				},
				success:function(data){
					if($(data).find("item").length>0){
						$("#name").val($(data).find("item:eq(0)").find("name").text())
						$("#title").val($(data).find("item:eq(0)").find("title").text())
					}
				}
			});
		}
		var buttons={
			type:"YESNOCANCEL",
			labelYES:"Kaydet",
			functionYES:function(){
				if($("#name").val()==""){
					messageDialog("Lütfen Tüm Alanları Doldurunuz !",{icon:"caution.gif",functionOK:function(){$("#name").get(0).focus();}});
					return;
				}
				if($("#title").val()==""){
					messageDialog("Lütfen Tüm Alanları Doldurunuz !",{icon:"caution.gif",functionOK:function(){$("#title").get(0).focus();}});
					return;
				}
				$.ajax({
					type:"post",
					url:url+"&sid=query&act=save",
					data:$("#newItemForm").serialize(),
					error:function(){
						messageDialog("Bir Hata Oluştu !",{icon:"warning.gif"})
					},
					success:function(data){
						var icon="caution.gif"
						if($(data).find("result").attr("status")=="OK"){
							icon="info.gif"
						}
						messageDialog($(data).text(),{icon:icon,functionOK:function(){list({x:0,y:30})}})
					}
				});
				layer("hide","dialogForm")
			},
			labelNO:"Sil",
			functionNO:function(){
				var options={
					icon:"caution.gif",
					type:"YESNO",
					functionYES:function(){
						$.ajax({
							type:"get",
							url:url+"&sid=query&act=delete",
							data:"id="+id,
							error:function(){
								messageDialog("Bir Hata Oluştu",{icon:"warning.gif"})
							},
							success:function(data){
								if($(data).find("result").attr("status")=="OK"){
									messageDialog("Başarıyla Silindi",{icon:"info.gif",functionOK:function(){list({x:0,y:30})}})									
								}else{
									messageDialog("Değişiklik Yapılmadı !",{icon:"caution.gif"})
								}
								layer("hide","dialogForm")
							}
						})
					}
				}
				messageDialog("Silmek İstediğinize Emin Misiniz ?",options)
			},
			functionCANCEL:function(){
				layer("hide","dialogForm")
			}
		}
		layer("show","dialogForm",500,300,"Yeni Oluştur",buttons)
	}
</script>

<div style="padding:10px;" align="right">
	<a href="javascript:void(0);" onclick="showDialogForm();" class="new">Yeni Oluştur</a>
</div>
<div id="result" style="margin:10px;"></div>


<div style="display:none;" id="dialogForm">
<form id="newItemForm" method="post" action="">
	<?=formElement("hidden","id")?>
	<table cellpadding="4" cellspacing="0" width="100%">
		<tr>
			<td width="40%" class="gridLeft"><label for="name">İsim</label></td>
			<td  class="gridRight"><?=formElement("text","name","","",""," style='width:150px;'")?></td>
		</tr>
		<tr>
			<td class="gridLeft"><label for="title">Açıklama</label></td>
			<td class="gridRight"><?=formElement("text","title","","",""," style='width:150px;'")?></td>
		</tr>
	</table>
	</form>
</div>

