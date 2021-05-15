<?php
define('__WEB_DIR__', '..');
require('../skripty/init.php');
require('./config.php');


if(!$_SESSION['pin_id'])
{
// pokud není nastaven pin_id tak přesměrujeme na index
header("location: /error/403.html");
exit();	
}
else
{

	if(Admin::blokujIP()){ echo 'Přístup odepřen'; exit();} // z DB blokovaných IP
	if(Admin::blokujPristup() >=3 ){ echo 'Přístup odepřen'; exit();} // po 3 neúspěšných pokusech za hodinu
	
	$html = new Html();
	echo $html->GenerujKod('_header'); 

	if($_SESSION['admin']['login'])
	{
		echo 'Již jste přihlášeni. Pokračute <a href="/admin/">zde.</a>';
	}
	else
	{
		$adm_ret = Admin::zalogujPIN();
		echo $adm_ret;

	}
	
	
 
	
	echo '</body>
</html>';
	

}




Db::close();
?>
