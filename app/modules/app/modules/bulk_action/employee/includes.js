$(document).ready(function(){
    //click
    $('#dosya_yukle').click(function(){
        $('#toplu_islem').trigger('click');
    })
    
    //changes
    $('#toplu_islem').on('change',function(){
        $('#bulkForm').submit();
        $('#upload_target').fadeIn();
    })
})