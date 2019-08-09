<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-9" />
<meta http-equiv="Content-Type" content="text/html; charset=windows-1254" />
<title>Polis Akademisi Başkanlığı</title>
</head>
<body>
<form action="mailsender.php" method="post">
	<table>
		<tr>
			<td>
				<table>
					<tr><td>Polis Akademisi Başkanlığı</td></tr>
					<tr><td>Bilgi İşlem Şube Müdürlüğü</td></tr>
					<tr><td>&nbsp;<?php //echo $sesval; ?></td></tr>
					<tr><td>MAİL TEST PROGRAMI</td></tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table>
					<tr>
						<td>İsim</td>
						<td>&nbsp;</td>
						<td><INPUT TYPE="text" NAME="isim" width="200" ></td>
					</tr>
					<tr>
						<td>e-Posta</td>
						<td>&nbsp;</td>
						<td><INPUT TYPE="text" NAME="email" width="200" ></td>
					</tr>
					<tr>
						<td>Telefon</td>
						<td>&nbsp;</td>
						<td><INPUT TYPE="text" NAME="telefon" width="200" ></td>
					</tr>
					<tr>
						<td>Mesaj</td>
						<td>&nbsp;</td>
						<td><INPUT TYPE="text" height="100" width="200" NAME="msj" ></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td><input name="send" type="submit" value="GÖNDER"></TD>
					</TR>
				</table>
			</td>
		</tr>
	</table>
</form>
</body>
</html>
