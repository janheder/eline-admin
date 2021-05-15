<?php 
// log událostí

Admin::adminLogin(5);


	// vypis vsech
	$odkaz_jinam_arr = array('ip','ip','./index.php?p=blokovani-ip&action=insert','blokovat IP');
	
	$tabulka = new Vypisy('admin_log_prihlaseni','typ=1 AND zalogovan=0','','',array('id','uz_jm','heslo','ip','datum'),array('Id','Už. jméno','Heslo','IP','datum','Akce'),0,0,0,$odkaz_jinam_arr);
	echo $tabulka->GenerujVypis();


?>
