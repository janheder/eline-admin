<?php 
// statistiky nejprodavanejsi

Admin::adminLogin(5);

 
	// filtr
	echo '<fieldset><legend>Filtr</legend>';
	$form = new Form('', '', 'get', 'formular2', 'formular', 'utf-8', '', 0);
	
	
	echo $form->formOpen();
	echo $form->addHTML('<div style="float: left; width: 40%;">');
	echo $form->inputText('datum_od','datepicker_od',sanitize($_GET['datum_od']),'Datum od','','',0,0,'');
	echo $form->inputText('datum_do','datepicker_do',sanitize($_GET['datum_do']),'Datum do','','',0,0,'');
	echo $form->addHTML('</div>');
	echo $form->addHTML('<div style="float: left; width: 45%; margin-top: 20px;">');
	echo $form->inputHidden('p','p',sanitize($_GET['p']),'',0);
	echo $form->inputHidden('pp','pp',sanitize($_GET['pp']),'',0);
	echo $form->inputSubmit('submit','submit','Zobrazit','','','');
	echo $form->inputButton('redir','redir','Zobrazit vše','','onclick="self.location.href=\'index.php?p=statistiky&pp=statistiky-nejprodavanejsi\'"','');
	echo $form->addHTML('</div>');
	echo $form->formClose();

	echo '</fieldset>';
	
	
	$sql_podminka = '';
	
	// pokud nejsou zadané datumy tak se bere poslední měsíc
	
	if($_GET['datum_od'])
	{
				  
		 list($den,$mesic,$rok) = explode(".",$_GET['datum_od']);
		 $hodina = 0;
		 $minuta = 0;
		 $sekunda = 1;
		 $datum_od = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);
		 
		$sql_podminka .= ' AND datum >='.intval($datum_od).' ';
	}
	else
	{
		 $hodina = 0;
		 $minuta = 0;
		 $sekunda = 1;
		 $datum_od = mktime($hodina,$minuta,$sekunda,intval(date('n')),1,date('Y'));
		 
		$sql_podminka .= ' AND datum >='.intval($datum_od).' ';
	}
	
	if($_GET['datum_do'])
	{
				  
		 list($den,$mesic,$rok) = explode(".",$_GET['datum_do']);
		 $hodina = 23;
		 $minuta = 59;
		 $sekunda = 59;
		 $datum_do = mktime($hodina,$minuta,$sekunda,intval($mesic),intval($den),$rok);
		 
		$sql_podminka .= ' AND datum <='.intval($datum_do).' ';
	}
	else
	{
	
		 $hodina = 23;
		 $minuta = 59;
		 $sekunda = 59;
		 $datum_do = mktime($hodina,$minuta,$sekunda,intval(date('n')),date('t'),date('Y'));
		 
		$sql_podminka .= ' AND datum >='.intval($datum_do).' ';
	}
	
	
	echo '<br>Není zohledněn stav objednávky.<br><br>';
	
	echo '<table border="0" class="ta" cellspacing="0" cellpadding="6">
		  <tr class="tr-header">
			<td>Pořadí</td>
			<td>Název</td>
			<td>Kat. číslo</td>
			<td>Cena za kus bez DPH</td>
			<td>Počet</td>
			<td>Cena celkem bez DPH</td>
			</tr>';
	
   $x = 1;	
   $data_s = Db::queryAll('SELECT sum(OP.pocet) AS POCET_CELKEM, sum(OP.pocet * OP.cena_za_ks) AS CENA_CELKEM,
   OP.kat_cislo, OP.id_produkt, OP.polozka, OP.cena_za_ks
   FROM objednavky_polozky OP
   LEFT JOIN objednavky O ON O.id=OP.id_obj
   WHERE OP.typ=0 AND (O.datum >='.$datum_od.' AND O.datum <='.$datum_do.') GROUP BY OP.kat_cislo ORDER BY POCET_CELKEM DESC LIMIT 50', array());
	if($data_s !== false ) 
	{
		foreach ($data_s as $row_s) 
		{
			echo '<tr '.__TR_BG__.' >
			  <td>'.$x.'</td>
			  <td><a href="./index.php?p=produkty&action=update&id='.$row_s['id_produkt'].'">'.$row_s['polozka'].'</a></td>
			  <td>'.$row_s['kat_cislo'].'</td>
			  <td>'.$row_s['cena_za_ks'].' '.__MENA__.'</td>
			  <td>'.$row_s['POCET_CELKEM'].'</td>
			  <td>'.$row_s['CENA_CELKEM'].' '.__MENA__.'</td>
			  </tr>';
			  
			  $x++;
			  	
		}
	}
	
	
	

echo '</table>';
	


?>

