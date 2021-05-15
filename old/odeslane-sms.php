<?php 
// odeslané SMS

Admin::adminLogin(3);


// filtr
if(!$_GET['action'])
{
	echo '<fieldset><legend>Filtr</legend>';
	$form = new Form('', '', 'get', 'formular2', 'formular', 'utf-8', '', 0);
	
	echo $form->formOpen();
	echo $form->addHTML('<div style="float: left; width: 40%;">');
	echo $form->inputText('jmeno','jmeno',sanitize($_GET['jmeno']),'Jméno příjemce','','',0,0,'');
	echo $form->inputText('telefon','telefon',sanitize($_GET['telefon']),'Telefon příjemce','','',0,0,'');
	echo $form->addHTML('</div>');
	echo $form->addHTML('<div style="float: left; width: 45%; margin-top: 20px;">');
	echo $form->inputHidden('p','p',sanitize($_GET['p']),'',0);
	echo $form->inputHidden('pp','pp',sanitize($_GET['pp']),'',0);
	echo $form->inputSubmit('submit','submit','Zobrazit','','','');
	echo $form->inputButton('redir','redir','Zobrazit vše','','onclick="self.location.href=\'index.php?p=objednavky&pp=odeslane-sms\'"','');
	echo $form->addHTML('</div>');
	echo $form->formClose();

	echo '</fieldset>';
}


	// vypis vsech
	if($_GET['jmeno'])
	{
		
		$sql_podminka_jmeno .= ' AND (Z.jmeno="'.sanitize($_GET['jmeno']).'" OR Z.prijmeni="'.sanitize($_GET['jmeno']).'") ';
		
		
	}
	else
	{
		$sql_podminka_jmeno = '';
	}
	
	if($_GET['telefon'])
	{
		
		$sql_podminka_telefon .= ' AND S.telefon LIKE"%'.sanitize($_GET['telefon']).'%" ';
		
		
	}
	else
	{
		$sql_podminka_telefon = '';
	}
	


	
	$tabulka = new Vypisy('sms_odeslane','','SELECT S.*, CONCAT(Z.jmeno, " ", Z.prijmeni) AS CELE_JMENO 
	FROM sms_odeslane S 
	LEFT JOIN zakaznici Z ON Z.id=S.id_zakaznik WHERE 1 '.$sql_podminka_jmeno.$sql_podminka_telefon.'   ','',
	array('S.id','Z.CELE_JMENO','S.telefon','S.sms','S.stav','S.datum'),
	array('Id','Jméno','Telefon','Zpráva','Stav','Datum'),0,0,0);
	echo $tabulka->GenerujVypis();



 
?>

