<? 
defined("PASS") or die("Giriş Engellendi");

// mysqli
$mysqli = new mysqli(MYHOST,MYUSER,MYPASS,MYDB);
$mysqli->query("SET NAMES 'utf8' COLLATE 'utf8_turkish_ci'");
// menus 
$query =" SELECT id,name,caption FROM sys_menus ";
$query.=" WHERE type='site'";
$result = $mysqli->query($query);
$menus	= array();
while ($row=$result->fetch_object()) {
	$menus[$row->id]=array("name"=>$row->name,"caption"=>$row->caption);
}
?>
<script language="JavaScript" type="text/javascript">
	var url='<?=CONF_MAIN_PAGE."?admin&pid=".PID."&sid=query"?>'
	function list(options){
		options=$.extend({
			x:0,
			y:30
		},options);
		
		$.ajax({
			type:"get",
			url:url+"&act=list&groupby=0",
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
							result+="<td class='gridTitle' width='50' align='center'>Site ID</td>"
							result+="<td class='gridTitle' >Site İsmi</td>"
							result+="<td class='gridTitle' >Site Açıklaması</td>"
							result+="<td class='gridTitle' >Menu ID</td>"
							result+="<td class='gridTitle' >Menu İsmi</td>"
							result+="<td class='gridTitle' >Menu Açıklaması</td>"
							result+="<td class='gridTitle' ></td>"
						result+="</tr>"
						$(data).find("item").each(function(){
							result+="<tr>"
								result+="<td class='gridRow' align='center'>"+$(this).find("id").text()+"</td>"
								result+="<td class='gridRow'>"+$(this).find("name").text()+"</td>"
								result+="<td class='gridRow'>"+$(this).find("title").text()+"</td>"
								result+="<td class='gridRow'>"+$(this).find("menu_id").text()+"</td>"
								result+="<td class='gridRow'>"+$(this).find("menu_name").text()+"</td>"
								result+="<td class='gridRow'>"+$(this).find("menu_caption").text()+"</td>"
								result+="<td class='gridRow' align='center'><a href='javascript:void(0);' onclick=\"deleteSiteMenu("+$(this).find("id").text()+","+$(this).find("menu_id").text()+")\"><img src='objects/icons/16x16/delete.png' style='vertical-align:middle;' /></a></td>"
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
	
	function showDialog(){
		var options={
			type:"SAVECANCEL",
			functionSAVE:function(){
				$.ajax({
					type:"post",
					url:url+"&act=saveSiteMenu",
					data:$("#menuForm").serialize(),
					error:function(){
						showmsg("Bir Hata Oluştu",3);
					},
					success:function(data){
						if($(data).find("result").attr("status")=="OK"){							
							messageDialog("Başarıyla Kaydedildi",{icon:"info.gif",functionOK:function(){layer("hide","dialogMenus");list({x:0,y:30});}})								
						}else{
							showmsg("Değişiklik Yapılmadı !",2);
						}
					}
				});
			},
			functionCANCEL:function(){layer("hide","dialogMenus")}
		};
		layer("show","dialogMenus",600,300,"Site - Menü Eşleştir",options)
	}
	
	function deleteSiteMenu(site_id,menu_id){
		site_id=parseInt(site_id);
		menu_id=parseInt(menu_id);
		var options={			
			icon:"caution.gif",
			type:"YESNO",
			functionYES:function(){
				$.ajax({
					type:"get",
					url:url+"&act=deleteSiteMenu",
					data:"site_id="+site_id+"&menu_id="+menu_id,
					error:function(){
						showmsg("Bir Hata Oluştu",3);
					},
					success:function(data){
						if($(data).find("result").attr("status")=="OK"){
							showmsg("Başarıyla Silindi",1);
							list({x:0,y:30});
						}else{
							showmsg("Değişiklik Yapılmadı",2);
						}
					}
				});
			}
		}
		messageDialog("Silmek istediğinize emin misiniz ?",options)
	}
			
	$(document).ready(function(){
		list({x:0,y:30});
		
		// events
		$("#trigger-create-new").click(function(){
			showDialog();
		});
		
		$("#site").change(function(){
			$("#menuForm input[type='checkbox']").removeAttr("checked");
			parseInt($(this).val())==0 ? $("#menuForm input[type='checkbox']").attr("disabled","true") : $("#menuForm input[type='checkbox']").removeAttr("disabled");
			
			if(parseInt($(this).val())!=0){
				$.ajax({
					type:"get",
					url:url+"&act=list&id="+$(this).val(),
					success:function(data){
						if($(data).find("menu_id").length>0){
							$(data).find("menu_id").each(function(){
								$("#menu-"+$(this).text()).attr("checked","true")
							});
						}
					}
				});
			}			
		});
	});
</script>

<div align="right" style="margin:10px;">
	<a class="new" href="javascript:void(0);" id="trigger-create-new">Yeni Oluştur</a>
</div>
<div id="result" style="margin:10px;"></div>
<!--
	HTML Markups
-->
<div id="dialogMenus" style="display:none;height:300px;overflow-y:auto;">
<form id="menuForm" action="" method="post">
	<table cellpadding="4" cellspacing="0" width="100%">
		<tr>
			<td class="gridTitle">
				<label for="">Site </label>
			</td>
			<td class="gridTitle" colspan="2">
				<?=formElement("select","site",getList("sys_sites","id,CONCAT('[',name,'] ',title) AS name","","id DESC","Seçiniz"))?>
			</td>
		</tr>
		<? 
		foreach ($menus as $id=>$menu){
			?>
				<tr>
					<td class="gridRow" width="200"><label for="<?="menu-".$id?>"><?=$menu["caption"]?></label></td>				
					<td class="gridRow"><label for="<?="menu-".$id?>"><?=$menu["name"]?></label></td>
					<td class="gridRow" width="20"><?=formElement("checkbox","menu[]",$id,"","disabled","","menu-".$id)?></td>
				</tr>
			<?
		}
	?>
	</table>
</form>
</div>
