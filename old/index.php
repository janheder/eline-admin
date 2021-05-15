<?php
define('__WEB_DIR__', '..');
require('../skripty/init.php');
require('./config.php');
if(!$_SESSION['admin']['login'])
{
if(Admin::blokujIP()){ echo 'Přístup odepřen'; exit();} // z DB blokovaných IP
if(Admin::blokujPristup() >=3 ){ echo 'Přístup odepřen'; exit();} // po 3 neúspěšných pokusech za hodinu
$adm_ret = Admin::zalogujAdmin();
}


Admin::adminLogin(3);

$html = new Html();
echo $html->GenerujKod('_header'); 

if(!$_SESSION['admin']['login'])
{
// loginform
$login_form = new Html();
echo $login_form->pridejDoKodu('{login_error}',$adm_ret); 
echo $login_form->generujKod('_loginform'); 
}
else
{
echo '<body>';	
echo '<div class="holder">';
echo '<div class="pasek_vpravo"> 
<i class="far fa-user" style="font-size: 22px; color:#F1D211;"></i> &nbsp;
Přihlášen je: <b>'.sanitize($_SESSION['admin']['uz_jm']).'</b>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="./logout.php">odhlásit</a></div>';
echo '<div class="pasek"><img src="./img/zamek_stredni.png" alt="" style="float: left; margin-right: 20px;" /> Administrace '.StringUtils::capitalize(__DOMENA__).'</div>';
echo '<table width="100%" border="0" align="center" class="admin" cellpadding="5">
<tr>
<td width="190" valign="top" class="popis_admin">';
odkazy($p,$menu_admin);
echo '</td>
<td valign="top">';

if(defined('__SUB_EX__'))
{
echo '<div class="submenu">';
menu_top($p,$menu_admin);
echo '</div>';
}

echo '<div class="clear"></div>';

echo '<fieldset><legend><h1>';
if(defined('__PODNADPIS__'))
{
	echo __PODNADPIS__;
}
else
{
	echo __NADPIS__;
}

echo '</h1></legend>';

  if($pp)
  {
	  // podstranky
	  if (file_exists('./'.$pp.'.php'))
	  {
	  require('./'.$pp.'.php');
	  }
	  else
	  {
	  echo 'soubor <strong>'.$pp.'.php</strong> se na serveru nenachází';
	  }
  }
  elseif($p)
  {
	  if (file_exists('./'.$p.'.php'))
	  {
	  require('./'.$p.'.php');
	  }
	  else
	  {
	  echo 'soubor <strong>'.$p.'.php</strong> se na serveru nenachází';
	  }
  }
  
echo '</fieldset>'; 


echo '</td>
</tr>
</table>
</div>';

}

echo '</body>
</html>';
Db::close();
?>
