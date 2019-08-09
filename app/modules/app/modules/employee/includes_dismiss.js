$(document).ready(function(){
    App_GetSelectItems("emp_dismiss_reason",0,"dismiss_types",0);
})

$('#emp_dismiss_button').click(function(){
    if($('#emp_dismiss_reason').val()<=0){
        return;
    }
    $.ajax({
        type:"POST",
        url:url+"&act=dismiss_emp",
        data:$('#EmpDismissForm').serialize()+"&i="+ee,
        success:function(data){
            
        }
    })
})