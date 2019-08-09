function messageDialog(message,props){
	if(props==null){
		props={
			labelOK:LANGUAGE["ok"],
			functionOK:function(){
				$(this).dialog("close")
			},
			state:"highlight",
			icon:"info",
			type:"OK"
		}
	}
	var state=props.state ? props.state : "highlight";
	var icon=props.icon ? props.icon : "info";
	switch(icon){
		case "caution.gif":
		state="highlight";
		icon="alert";
		break;
		
		case "warning.gif":
		state="error";
		icon="alert";
		break;
		
		case "info.gif":
		state="highlight";
		icon="info";
		break;
		
		case "help.gif":
		state="highlight";
		icon="help";
		break;
		
		default:
			switch(state){
				case "error":
				icon=props.icon ? props.icon : "alert"
				break;
	
				case "highlight":
				icon=props.icon ? props.icon : "info"
				break;
	
				case "default":
				icon=props.icon ? props.icon : "info"
				break;
			}
		break;
	}
			
	delete props.state;
	delete props.icon;
	if(props){
		var type=props.type ? props.type : "OK"		
		var button=props;
    var fncOK=props.functionOK ? props.functionOK : function(){+("#messageDialog").dialog("close")};
    var fncYES=props.functionYES ? props.functionYES : function(){+("#messageDialog").dialog("close")};
    var fncNO=props.functionNO ? props.functionNO : function(){+("#messageDialog").dialog("close")};
		var onClose=props.onClose
		
		var functionOK;functionOK = typeof(button.functionOK)=="function" ?  function(){$("#messageDialog").dialog("close");fncOK();}: function(){$("#messageDialog").dialog("close");eval(fncOK)};						
		var functionYES;functionYES = typeof(button.functionYES)=="function" ?  function(){$("#messageDialog").dialog("close");fncYES();}:function(){$("#messageDialog").dialog("close");eval(fncYES)};
		var functionNO;functionNO = typeof(button.functionNO)=="function" ?  function(){$("#messageDialog").dialog("close");fncNO();}:function(){$("#messageDialog").dialog("close");eval(fncNO)};
		if(onClose){var functionCLOSE;functionCLOSE = typeof(button.onClose)=="function" ?  function(){onClose();}:function(){eval(onClose);}}

		switch(type){
			case "YESNO":
				var labelNO=button.labelNO ? button.labelNO : LANGUAGE["no"];
				var labelYES=button.labelYES ? button.labelYES : LANGUAGE["yes"];
				button[labelNO]=functionNO;
				button[labelYES]=functionYES;
			break;
			
			default:
				var labelOK=button.labelOK ? button.labelOK : LANGUAGE["ok"];
				button[labelOK]=functionOK;
			break;
		}

		delete button.type;	
		delete button.labelOK;
		delete button.labelYES;
		delete button.labelNO;
		delete button.functionOK;
		delete button.functionYES;
		delete button.functionNO;	
		delete button.onClose;
	}	

	$("#messageDialog").dialog("destroy").remove();
	$("<div style=\"margin:5px;\" />")
	.attr("id", "messageDialog")
	.attr("title", "Mesaj")
	.attr("class", "ui-state-"+state+" ui-corner-all")
	.appendTo($("body"));
	var iconHtml='<span class="ui-icon ui-icon-'+icon+'" style="margin: 0pt 7px 20px 0pt; float: left;"/>';
	$("#messageDialog").html("<div style=\"padding:5px;font-size:13px;\">"+iconHtml+message+"</div>");	
	// add indicator attr for test there is any modal dialog
	var modals=0;
	var opa=0;
	var bgcolor="gray";
	$("div").each(function(){($(this).attr("modal")=="true") ? modals++ : ""});
	// if there is any modal
	if(modals>0){
		$("#messageDialog").attr("modal","false");
		opa=0.2;
		bgcolor="white"
	}else {
		$("#messageDialog").attr("modal","true");
		opa=0.3
		bgcolor="black"
	}
	// make dialog
	$("#messageDialog").dialog({
		bgIframe:true,
		autoOpen:false,
		width:350,
		minHeight:100,
		modal:true,
		resizable:false,
		overlay:{
			background:bgcolor,
			opacity:opa
		},		
		hide:"fold",
		buttons:button,
		close:function(){
			functionCLOSE;
			if(!modals>0){
				$.each($.browser, function(i, val) {if(i=="msie" && val){$("select").css({visibility:"visible"});}});
				$("applet").css({visibility:"visible"});
				$("object").css({visibility:"visible"});
				$("embed").css({visibility:"visible"});
				$("iframe").css({visibility:"visible"});
			}
		}
	});
	// IE select menu problem
	$.each($.browser, function(i, val) {if(i=="msie" && val){$("select").css({visibility:"hidden"});}});
	// hide problem tags
	$("applet").css({visibility:"hidden"});
	$("object").css({visibility:"hidden"});
	$("embed").css({visibility:"hidden"});
	$("iframe").css({visibility:"hidden"});		
	// open dialog
	$("#messageDialog").dialog("open");	
}
// easy way for dialogs
function layer(action,target,w,h,t,props,isModal,isResizable){
	// for old type buttons	
	var button
	if(props){			
			
		var type=props.type;
		var button=props;
    var fncOK=props.functionOK ? props.functionOK : function(){};
    var fncYES=props.functionYES ? props.functionYES : function(){};
    var fncNO=props.functionNO ? props.functionNO : function(){};
    var fncSAVE=props.functionSAVE ? props.functionSAVE : function(){};
    var fncDELETE=props.functionDELETE ? props.functionDELETE : function(){};
    var fncCANCEL=props.functionCANCEL ? props.functionCANCEL : function(){};
		var fncClOSE=props.onClose
		
		var labelNO=button.labelNO ? button.labelNO : LANGUAGE["no"];
		var labelYES=button.labelYES ? button.labelYES : LANGUAGE["yes"];
		var labelSAVE=button.labelSAVE ? button.labelSAVE : LANGUAGE["save"];
		var labelDELETE=button.labelDELETE ? button.labelDELETE : LANGUAGE["delete"];
		var labelOK=button.labelOK ? button.labelOK : LANGUAGE["ok"];
		var labelCANCEL=button.labelCANCEL ? button.labelCANCEL : LANGUAGE["cancel"];
		
		var functionOK;functionOK = typeof(button.functionOK)=="function" ?  function(){fncOK()}: function(){eval(fncOK)};						
		var functionYES;functionYES = typeof(button.functionYES)=="function" ?  function(){fncYES()}:function(){eval(fncYES)};
		var functionNO;functionNO = typeof(button.functionNO)=="function" ?  function(){fncNO()}:function(){eval(fncNO)};
		var functionSAVE;functionSAVE = typeof(button.functionSAVE)=="function" ?  function(){fncSAVE()}:function(){eval(fncSAVE)};
		var functionDELETE;functionDELETE = typeof(button.functionDELETE)=="function" ?  function(){fncDELETE()}:function(){eval(fncDELETE)};
		var functionCANCEL;functionCANCEL = typeof(button.functionCANCEL)=="function" ?  function(){fncCANCEL()}:function(){eval(fncCANCEL)};
		if(fncClOSE){var functionCLOSE;functionCLOSE = typeof(button.onClose)=="function" ?  function(){fncClOSE()}:function(){eval(fncClOSE);}}

		if(type){
			switch(type){
				case "YESNO":
				button[labelNO]=functionNO;
				button[labelYES]=functionYES;
				break;
				
				case "YESNOCANCEL":
				button[labelCANCEL]=functionCANCEL;
				button[labelNO]=functionNO;
				button[labelYES]=functionYES;
				break;
				
				case "OKCANCEL":
				button[labelCANCEL]=functionCANCEL;
				button[labelOK]=functionOK;
				break;
				
				case "SAVECANCEL":
				button[labelCANCEL]=functionCANCEL;
				button[labelSAVE]=functionSAVE;
				break;
				
				case "SAVEDELETECANCEL":
				button[labelCANCEL]=functionCANCEL;
				button[labelDELETE]=functionDELETE;
				button[labelSAVE]=functionSAVE;
				break;
				
				case "OK":
				button[labelOK]=functionOK;
				break;
			}
		}
				
		delete button.type	
		delete button.labelOK
		delete button.labelCANCEL
		delete button.labelYES
		delete button.labelNO
		delete button.labelSAVE
		delete button.labelDELETE
		delete button.functionOK
		delete button.functionCANCEL
		delete button.functionSAVE
		delete button.functionYES
		delete button.functionNO
		delete button.functionDELETE
		delete button.onClose;
		delete button.align;
	}
	
	target="#"+target
	
	if($(target).length>0){
		if(action=="show"){
			$(target).dialog("destroy")
			// modal mı 
			isModal==false ? isModal=false : isModal=true
			// resizable mı 
			isResizable==false ? isResizable=false : isResizable=true
			// add indicator attr for test there is any modal dialog		
			var modals=0;
			var opa=0;
			$("div").each(function(){($(this).attr("modal")=="true") ? modals++ : ""});
			// if there is any modal
			if(modals>0){$(target).attr("modal","false");opa=0;}else {$(target).attr("modal","true");opa=0.6}		
			$(target).dialog({
				autoOpen:false,
				title:t,
				width:w,
				height:h,
				modal:isModal,
				resizable:isResizable,
				overlay:{
					background:"#000000",
					opacity:opa
				},
				hide:"drop",									
				buttons:button,
				close:function(){
					$(target).hide("clip");
					functionCLOSE;
					$(target).attr("modal","false");
					if(!modals>0){
						// IE select menu problem
						$.each($.browser, function(i, val) {if(i=="msie" && val){$("select").css({visibility:"visible"});}});
						$("applet").css({visibility:"visible"});
						$("object").css({visibility:"visible"});
						$("embed").css({visibility:"visible"});
						$("iframe").css({visibility:"visible"});
					}
				}
			});			
			// hiding select menus if browser is msie
			$.each($.browser,function(i,val){if(i=="msie" && val){$("select").css({visibility:"hidden"});}});
			// hide problem tags
			$("applet").css({visibility:"hidden"});
			$("object").css({visibility:"hidden"});
			$("embed").css({visibility:"hidden"});
			$("iframe").css({visibility:"hidden"});					
			// resetting  forms in dialog 
			$(target+"form").each(function(){this.reset();});
			// open dialog
			$(target).dialog("open")
		}else {			
			// dialog artık modal değil - opacity ayarı için
			$(target).attr("modal","false");
			// eğer dialog kapansın denmişse			
			$(target).dialog("close")			
		}
	}else {
		messageDialog("Hedef İçerik Bulunamadı !",{state:"error"})
	}
}
// loading dialog
$(document).ready(function(){			
	$("<div/>")
	.attr("id","blockLayer")
	.css({
		width:"100%",
		height:$(document).height()+"px",
		position:"absolute",
		left:"0px",
		top:"0px",
		zIndex:"1000",
		background:"black",
		opacity:"0"
	})
	.appendTo("body")
	.ajaxStart(function(){
		$(this).css({height:$(document).height()+"px"});
		$(this).show()
	})
	.ajaxStop(function(){
		$(this).css({height:$(document).height()+"px"});
		$(this).hide();
	})
	.hide()
	$("<div/>")
	.css({
		background:"white",
		border:"5px solid #777777",
		width:"300px",
		height:"120px",
		padding:"15px",
		position:"absolute",
		left:"35%",
		top:"35%",
		opacity:"1",
		zIndex:"2000"
	})
	.attr("class","ui-state-default ui-corner-all")
	.attr("align","center")
	.html((
	$("<div />")
	.css({
		marginTop:"30px"
	})
	.html("<img src='objects/icons/loaders/20.gif' />"+"<br><br>"+
	"<span style='font-size:15px; color:#333333;'>"+LANGUAGE["loadingMessage"]+"<span>")
	))
	.appendTo("body")
	.hide()
	.ajaxStart(function(){
		$(this).show("clip")
	})
	.ajaxStop(function(){
		$(this).hide("clip")
	})	
	$(window).resize(function(){$("#blockLayer").css({height:$(document).height()+"px"})});
});
