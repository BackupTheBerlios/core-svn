<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>./DEV-LOG: poleć znajomemu</title>
<meta http-equiv="Content-Type" content="text/html; CHARSET=iso-8859-2" />
<link href="style/style.css" rel="STYLESHEET" type="text/css" />
</head>
<body style="margin-top: 5px;">
<table width="100%"  border="0" cellspacing="0" cellpadding="0" style="height:120px; ">
  <tr>
    <td style="padding-left:15px; "><strong>Ciekawe?</strong> - poleć znajomemu.<br />
      <br />
      <form action="send.php?a=s&amp;url=<?php echo "http://dev.no1-else.com/1," . $_GET['id'] . ",1,item.html"; ?>" method="post" name="form_polec" onsubmit="return(check_form_polec());">

      <table  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="110">Osoba polecająca: </td>
          <td><input type="text" name="sender" /></td>
        </tr>
        <tr>
          <td colspan="2" height="5"></td>
          </tr>
        <tr>
          <td>e-mail znajomego: </td>
          <td><input type="text" name="email" /></td>
        </tr>
        <tr>

          <td colspan="2" style="text-align: right; padding-top: 3px;">
            <a href="javascript:document.forms[0].submit()">poleć znajomemu</a></td>
          </tr>
      </table>
		<script type="text/javascript">
			<!--
			function check_form_polec() {
				var x=0;
				var msg='';
				if (document.form_polec.kto.value.replace(' ','')=='') { x++; msg+='Nie podano osoby polecającej'+'\n'; }
				if (document.form_polec.email.value=='') { x++; msg+='Nie podano adresu e-mail'+'\n'; }
				if (document.form_polec.email.value!=''&&!valid_mail(document.form_polec.email.value)) { x++; msg+='Podano nieprawidłowy adres e-mail'+'\n'; }
				if (x>0) { alert(msg); return false; }
				else return true;
			}
			//-->
		</script>
      </form>
        </td>
  </tr>
</table>
</body>
</html>