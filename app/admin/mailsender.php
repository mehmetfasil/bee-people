<?php

$to='mail@mail.com';
// Post ile gelen degiskenler
    $isim = $_POST['isim'];
    $email = $_POST['email']; 
    $telefon = $_POST['telefon']; 
    $msj = $_POST['mesaj']; 
    
// HTML Mesaj içerigi <table> gibi kodlari kullanabilirsiniz.
    $konu = "[!] Web Ziyaretci Mesaji.";            
    $mesaj = 'Merhaba, web sitesinden gönderilen mesaj asagidadir.
    <br>Iyi günler.
    <br><br>
    ==================================<br>
    <b>Gönderen</b> : '.$isim.'<br>
    <b>E-Mail</b> : <a href="mailto:'.$email.'">'.$email.'</a><br>
    <b>Telefon</b> : '.$telefon.'<br>
    <b>Mesaj</b> : '.$msj.'<br>
    ==================================
    <br><br>
    <br><br>
    __________________________________<br>
    Web Siteniz<br>
    <a href="http://siteadi">http://siteadi</a> ';
    
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=windows-1254"' . "\r\n";
    $headers .= 'To: Web Siteniz <mail@mail.com>' . "\r\n";
    $headers .= 'From: '.$isim.' <'.$email.'>' . "\r\n";
    
if ( mail($to,$konu,$mesaj,$headers) ) { 
    echo "Mesaj basariyla gönderildi!"; }
    
else { 
    echo "Mesaj Gönderilemedi!"; }
?>