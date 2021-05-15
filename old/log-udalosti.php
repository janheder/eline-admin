<?php 
// log událostí

Admin::adminLogin(5);

	
	// filtr
	echo '<fieldset><legend>Filtr</legend>';
	$form = new Form('', '', 'get', 'formular2', 'formular', 'utf-8', '', 0);
	
	echo $form->formOpen();
	echo $form->addHTML('<div style="float: left; width: 40%;">');
	echo $form->inputText('uz_jm','uz_jm',sanitize($_GET['uz_jm']),'Uživatelské jméno','','',0,0,'');
	echo $form->inputText('datum','datepicker',sanitize($_GET['datum']),'Datum do','','',0,0,'');
	echo $form->addHTML('</div>');
	echo $form->addHTML('<div style="float: left; width: 45%; margin-top: 20px;">');
	echo $form->inputHidden('p','p',sanitize($_GET['p']),'',0);
	echo $form->inputHidden('pp','pp',sanitize($_GET['pp']),'',0);
	echo $form->inputSubmit('submit','submit','Zobrazit','','','');
	echo $form->inputButton('redir','redir','Zobrazit vše','','onclick="self.location.href=\'index.php?p=log-udalosti\'"','');
	echo $form->addHTML('</div>');
	echo $form->formClose();

	echo '</fieldset>';
	
	// vypis vsech
	if($_GET['uz_jm'] || $_GET['datum'])
	{
		$sql_podminka = '';
		if($_GET['uz_jm'])
		{
			$sql_podminka .= 'uz_jm="'.sanitize($_GET['uz_jm']).'" ';
			if($_GET['datum']){$sql_podminka .= ' AND ';}
		}
		if($_GET['datum'])
		{
					  
			$datum = new DateTime($_GET['datum'].'T23:59:59');
			$sql_datum = $datum->getTimestamp();
			$sql_podminka .= 'datum <='.intval($sql_datum).' ';
		}
		
	}
	else
	{
		$sql_podminka = '';
	}
	
	$tabulka = new Vypisy('admin_log_udalosti',$sql_podminka,'','',array('id','uz_jm','udalost','url','ip','datum'),array('Id','Už. jméno','Událost','URL','IP','datum'),0,0,0);
	echo $tabulka->GenerujVypis();


?>
