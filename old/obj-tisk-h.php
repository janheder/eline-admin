<?php
// hromadný tisk objednávek
define('__WEB_DIR__', '..');
require('../skripty/init.php');
require('./config.php');

Admin::adminLogin(4);


if($_POST['ido'])
{
	
	$html = new Html();
	echo $html->GenerujKod('_header'); 
	
	echo '<body onload="window.print();">';
		  
		$data_o = Db::queryAll('SELECT O.*, 
		  Z.jmeno, Z.prijmeni, Z.email, Z.telefon, Z.dodaci_ulice, Z.dodaci_cislo, Z.dodaci_obec, Z.dodaci_psc, Z.dodaci_id_stat, Z.fakturacni_firma, Z.fakturacni_ulice, Z.fakturacni_cislo, 
		  Z.fakturacni_obec, Z.fakturacni_psc, Z.fakturacni_id_stat, Z.ic, Z.dic,
		  SO.nazev AS STAV 
		  FROM objednavky O 
		  LEFT JOIN zakaznici Z ON Z.id=O.id_zakaznik
		  LEFT JOIN objednavky_stavy SO ON SO.id=O.id_stav
		  WHERE O.id IN('.implode(",",$_POST['ido']).') ', array());
		   foreach ($data_o as $row_o) 
		   {

		    echo '<h3>Objednávka č. '.$row_o['cislo_obj'].'</h3>';
		    echo '<div class="clear" style="height: 5px;"></div>';

		    echo '<div style="width: 30%; float: left;">';
		    echo 'Datum: '.date('d.m.Y H:i:s',$row_o['datum']).'<br />';
		    echo 'Zákazník: <a href="./index.php?p=zakaznici&action=update&id='.$row_o['id_zakaznik'].'">'.$row_o['jmeno'].' '.$row_o['prijmeni'].'</a><br />';
		    echo 'Email: '.$row_o['email'].'<br />';
		    echo 'Telefon: '.$row_o['telefon'].'<br />';
		    echo '</div>';
		    
		    echo '<div style="width: 40%; float: left;">';
		    echo 'Cenová skupina: '.$row_o['cenova_skupina'].'<br />';
		    echo 'Slevová skupina: ';
		    if($row_o['skupina_slev']>0){echo $row_o['skupina_slev'].'%';}
		    echo '<br />';
		    echo 'IP: '.$row_o['ip'].'<br />';
		    echo 'Referer: '.$row_o['referer'].'<br />';
		    echo 'OS: '.$row_o['os'].'<br />';
		    echo '</div>';
		    
		    echo '<div style="width: 30%; float: left;">';
		    echo 'Souhlas s Obch. podmínkami: '.$stav_adm_sluzby_arr[$row_o['souhlas_op']].'<br>';
		    echo 'Nesouhlas Heureka: '.$stav_adm_sluzby_arr[$row_o['nesouhlas_heureka']].'<br>';
		    echo 'Faktura: <a href="'.$row_o['faktura_url'].'" target="_blank">'.$row_o['faktura_cislo'].'</a>';
		    echo '</div>';
		    
		    echo '<div class="clear" style="height: 20px;"></div>';
		    
		    // fakturační adresa
		    echo '<div style="width: 300px; float: left;">';
		    echo '<fieldset><legend><h3>Fakturační údaje</h3></legend>';
		    if($row_o['fakturacni_firma']){echo $row_o['fakturacni_firma'].'<br>';}
		    if($row_o['ic']){echo 'IČ: '.$row_o['ic'].'<br>';}
		    if($row_o['dic']){echo 'DIČ: '.$row_o['dic'].'<br>';}
		    echo $row_o['fakturacni_ulice'].' '.$row_o['fakturacni_cislo'].'<br>';
		    echo $row_o['fakturacni_obec'].'<br>';
		    echo $row_o['fakturacni_psc'].'<br>';
		    echo $staty_arr[$row_o['fakturacni_id_stat']].'<br>';
		    echo '</fieldset>';
		    echo '</div>';
		    
		    // dodací adresa
		    echo '<div style="width: 300px; float: left;">';
		    echo '<fieldset><legend><h3>Dodací údaje</h3></legend>';
		    if($row_o['dodaci_nazev']){echo $row_o['dodaci_nazev'].'<br>';}
		    echo $row_o['dodaci_ulice'].' '.$row_o['dodaci_cislo'].'<br>';
		    echo $row_o['dodaci_obec'].'<br>';
		    echo $row_o['dodaci_psc'].'<br>';
		    echo $staty_arr[$row_o['dodaci_id_stat']].'<br>';
		    echo '</fieldset>';
		    echo '</div>';
		    
		    echo '<div class="clear" style="height: 10px;"></div>';
			
			//produkty objednávky
			echo '<table border="0" class="ta" id="tab_obj" cellspacing="0" cellpadding="3">
			<tr class="tr-header">
			<td style="width: 50%">Produkt</td>
			<td style="width: 5%">Počet</td>
			<td style="width: 13%">Cena za ks bez DPH</td>
			<td style="width: 5%">DPH</td>
			<td style="width: 13%">Cena celekem bez DPH</td>
			<td >Cena celekem s DPH</td>
			</tr>';
			
			// nejdříve zboží
			$row_op = Db::queryAll('SELECT * FROM objednavky_polozky WHERE id_obj=? AND typ=? ORDER BY id', array($row_o['id'],0));
			if($row_op !== false ) 
			{	
				foreach ($row_op as $row_op) 
				{
				  echo '<tr '.__TR_BG__.'>
						<td><a href="./index.php?p=produkty&action=update&id='.$row_op['id_produkt'].'">'.$row_op['polozka'].'</a>
						<br>Kat. číslo var.: '.$row_op['kat_cislo'].'<br>ID var.: '.$row_op['id_varianta'].'</td>
						<td>'.$row_op['pocet'].'</td>
						<td>'.$row_op['cena_za_ks'].' '.__MENA__.'</td>
						<td>'.$row_op['dph'].'</td>
						<td>'.round(($row_op['cena_za_ks'] * $row_op['pocet']),2).' '.__MENA__.'</td>
						<td>'.number_format(round(($row_op['cena_za_ks'] * $row_op['pocet'] * ($row_op['dph'] / 100 + 1)),2), 2, '.', '').' '.__MENA__.'</td>
						</tr>';
						
				}
			}
			
			// pak slevový kód
			// ten se při realizaci objednávky vždy přepočte na částku a ta už se nemění
			$row_ops = Db::queryRow('SELECT * FROM objednavky_polozky WHERE id_obj=? AND typ=?', array($row_o['id'],3));
			if($row_ops !== false) 
		    {
					 echo '<tr '.__TR_BG__.'>
						 <td>Slevový kód'.$row_ops['polozka'].'</a>
						 <br>'.$row_ops['kat_cislo'].'</td>
						 <td>'.$row_ops['pocet'].'</td>
						 <td>'.$row_ops['cena_za_ks'].' '.__MENA__.'</td>
						 <td>'.$row_ops['dph'].'</td>
						 <td>'.$row_ops['cena_za_ks'].' '.__MENA__.'</td>
						 <td>'.number_format(round(($row_ops['cena_za_ks'] * ($row_ops['dph'] / 100 + 1)),2), 2, '.', '').' '.__MENA__.'</td>
						 </tr>';
			}
			
			// pak doprava
			$row_opd = Db::queryRow('SELECT * FROM objednavky_polozky WHERE id_obj=? AND typ=?', array($row_o['id'],1));
			if($row_opd !== false) 
		    {
					 echo '<tr '.__TR_BG__.'>
						 <td>Doprava: '.$row_opd['polozka'].'</a>
						 <br>'.$row_opd['kat_cislo'].'</td>
						 <td>'.$row_opd['pocet'].'</td>
						 <td>'.$row_opd['cena_za_ks'].' '.__MENA__.'</td>
						 <td>'.$row_opd['dph'].'</td>
						 <td>'.$row_opd['cena_za_ks'].' '.__MENA__.'</td>
						 <td>'.number_format(round(($row_opd['cena_za_ks'] * ($row_opd['dph'] / 100 + 1)),2), 2, '.', '').' '.__MENA__.'</td>
						 </tr>';
			}
			
			// pak platba
			$row_opp = Db::queryRow('SELECT * FROM objednavky_polozky WHERE id_obj=? AND typ=?', array($row_o['id'],2));
			if($row_opp !== false) 
		    {
					 echo '<tr '.__TR_BG__.'>
						 <td>Platba: '.$row_opp['polozka'].'</a>
						 <br>'.$row_opp['kat_cislo'].'</td>
						 <td>'.$row_opp['pocet'].'</td>
						 <td>'.$row_opp['cena_za_ks'].' '.__MENA__.'</td>
						 <td>'.$row_opp['dph'].'</td>
						 <td>'.$row_opp['cena_za_ks'].' '.__MENA__.'</td>
						 <td>'.number_format(round(($row_opp['cena_za_ks'] * ($row_opp['dph'] / 100 + 1)),2), 2, '.', '').' '.__MENA__.'</td>
						 </tr>';
			}
			
			 $data_cc_bez = Db::queryRow('SELECT sum(pocet * cena_za_ks) AS CELKEM_BEZ FROM objednavky_polozky WHERE id_obj=?  ', array($row_o['id']));
			 $data_cc_s = Db::queryRow('SELECT sum(pocet * cena_za_ks * (dph / 100 + 1)) AS CELKEM_S FROM objednavky_polozky WHERE id_obj=?  ', array($row_o['id']));
			 echo '<tr '.__TR_BG__.'>
						 <td colspan="4"><b>Cena celkem</b></td>
						 <td><b>'.number_format(round($data_cc_bez['CELKEM_BEZ'],2), 2, '.', ' ').' '.__MENA__.'</b></td>
						 <td><b>'.number_format(round($data_cc_s['CELKEM_S'],2), 2, '.', ' ').' '.__MENA__.'</b></td>
						 </tr>';
			
			echo '</table><div class="clear" style="height: 10px;"></div>';
			
			// váha + poznámka zákazníka
			echo '<div style="width: 35%; float: left;">';
			echo '<label class="control-label" for="cvz">Celková váha zásilky:</label>
			<div class="clear"></div>
			'.$row_o['vaha'].' <span >v gramech</span>
			<div class="clear" style="height: 10px;"></div>
			<label class="control-label" for="popis">Poznámka zákazníka:</label>
			<div class="clear" style="height: 10px;"></div>
			'.$row_o['poznamka_zakaznik'];
			echo '</div>';
			
			// udaná cena + interní poznámka
			echo '<div style="width: 35%; float: left;">';
			echo '<label class="control-label" for="ucz">Udaná cena zásilky:</label>
			<div class="clear"></div>
			'.$row_o['udana_cena'].' <span >'.__MENA__.'</span>
			<div class="clear" style="height: 10px;"></div>
			<label class="control-label" for="popis">Poznámka naše:</label>
			<div class="clear" style="height: 10px;"></div>'.$row_o['poznamka_prodejce'];
			echo '</div>';
			
			// číslo zásilky
			echo '<div style="width: 30%; float: left;">';
			echo '<label class="control-label" for="cz">Číslo zásilky:</label>
			'.$row_o['cislo_zasilky'].'
			<div class="clear" style="height: 10px;"></div>';
			
			// stav uhrady	
			echo '<label class="control-label" for="stav_uhrady">Stav úhrady:</label> ';
			echo $stavy_fak_arr[$row_o['id_stav_uhrady']]; 
			echo '<div class="clear" style="height: 10px;"></div>';
			
			// stav obj
			
			$data_stav = Db::queryRow('SELECT * FROM objednavky_stavy WHERE id=? ORDER BY id ASC', array($row_o['id_stav']));
			echo '<label class="control-label" for="stav_obj">Stav objednávky:</label>';
			echo $data_stav['nazev'];
			echo '</div>';
			
			// konec stránky
		    echo '<div class="page_break_print"></div>';
		   
		   }
	
	
}
else
{
 echo 'Chybí ID objednávek';
}
?>
</body>
</html>