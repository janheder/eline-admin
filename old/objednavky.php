<?php 
// objednavky

Admin::adminLogin(3);

// filtr
if(!$_GET['action'])
{
	echo '<fieldset><legend>Filtr</legend>';
	$form = new Form('', '', 'get', 'formular2', 'formular', 'utf-8', '', 0);
	
	
	$data = Db::queryAll('SELECT id,nazev FROM objednavky_stavy WHERE aktivni=1 ORDER BY nazev', array());
	$id_stav_arr = array();
	if($data !== false ) 
	{	
	   $id_stav_arr[0] = 'vše';
		
	   foreach ($data as $row) 
		{
		  $id_stav_arr[$row['id']] = $row['nazev'];	
		}
	}
	else
	{
		$id_stav_arr = false;
	}
	
	

	
				
	echo $form->formOpen();
	echo $form->addHTML('<div style="float: left; width: 70%;">');
	
	echo $form->addHTML('<div style="float: left; width: 40%;">');
	echo $form->inputText('cislo_obj','cislo_obj',sanitize($_GET['cislo_obj']),'Číslo objednávky','','',0,0,'');
	echo $form->inputSelect('id_stav','id_stav',sanitize($_GET['id_stav']),$id_stav_arr,'Stav','',0,0,'');
	
	// nelze použít třídu
	echo '<label class="control-label" for="_id_stav_uhrady">Stav úhrady:</label>';
	echo '<select name="id_stav_uhrady" id="id_stav_uhrady">';
	echo '<option value=""> vše </option>';
	foreach($stavy_fak_arr as $stavy_fak_k=>$stavy_fak_v)
	{
		echo '<option value="'.$stavy_fak_k.'" ';
		if(isset($_GET['id_stav_uhrady']) && $_GET['id_stav_uhrady']==$stavy_fak_k){echo ' selected ';}
		echo ' > '.$stavy_fak_v.' </option>';
	}
	echo '</select>';
	//echo $form->inputSelect('id_stav_uhrady','id_stav_uhrady',sanitize($_GET['id_stav_uhrady']),$stavy_fak_arr,'Stav úhrady','',0,0,'');
	//echo $form->inputText('jmeno','jmeno',sanitize($_GET['jmeno']),'Jméno','','',0,0,'');
	echo $form->inputText('prijmeni','prijmeni',sanitize($_GET['prijmeni']),'Příjmení','','',0,0,'');
	echo $form->addHTML('</div>');
	
	echo $form->addHTML('<div style="float: left; width: 40%;">');
	echo $form->inputText('email','email',sanitize($_GET['email']),'E-mail','','',0,0,'');
	echo $form->inputText('telefon','telefon',sanitize($_GET['telefon']),'Telefon','','',0,0,'');
	echo $form->inputText('obec','obec',sanitize($_GET['obec']),'Obec','','',0,0,'');
	echo $form->inputText('datum','datepicker',sanitize($_GET['datum']),'Datum','','',0,0,'');
	echo $form->addHTML('</div>');

	echo $form->addHTML('</div>');
	
	echo $form->addHTML('<div style="float: left; width: 30%; margin-top: 20px;">');
	echo $form->inputHidden('p','p',sanitize($_GET['p']),'',0);
	echo $form->inputHidden('pp','pp',sanitize($_GET['pp']),'',0);
	echo $form->inputSubmit('submit','submit','Zobrazit','','','');
	echo $form->inputButton('redir','redir','Zobrazit vše','','onclick="self.location.href=\'index.php?p=objednavky\'"','');
	echo $form->addHTML('</div>');
	echo $form->formClose();

	echo '</fieldset>';
}

if($_GET['action']=='update')
{

  
    $form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 0);
	$form->required = 'obj_pocet,obj_cena';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				 
					   if($form->errors==false)
					   {

								//var_dump($_POST);
								
								// nejdříve update stávajících položek
								// + poznámky
								// + váha
								// + udaná cena
								// + číslo zásilky
								// + přepočet cen
								
								$data_update = array(
										'vaha' => intval($_POST['vaha']),
										'udana_cena' => intval($_POST['udana_cena']),
										'cislo_zasilky' => $_POST['cislo_zasilky'],
										'id_stav_uhrady' => $_POST['id_stav_uhrady'],
										'poznamka_prodejce' => $_POST['poznamka_prodejce'],
										'poznamka_zakaznik' => $_POST['poznamka_zakaznik']
										);
										$where_update = array('id' => $_POST['id']);
										$query = Db::update('objednavky', $data_update, $where_update);
										
								// log
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Objednávky - úprava obj ID '.intval($_POST['id']),
							    'url' => sanitize($_SERVER['HTTP_REFERER']),
							    'ip' => sanitize(getip()),
							    'datum' => time()
							     );
							     $query_insert = Db::insert('admin_log_udalosti', $data_insert);
							     		
							     		
								if($_POST['obj_pocet'])
								{   
									foreach($_POST['obj_pocet'] as $op_k=>$op_v)
									{
										$data_update = array(
										'pocet' => $op_v,
										'cena_za_ks' => str_replace(',','.',$_POST['obj_cena'][$op_k])
										);
										$where_update = array('id' => $op_k);
										$query = Db::update('objednavky_polozky', $data_update, $where_update);
									}
								}
								
								
								
								// následně přidání nových položek
								// + přepočet cen
								if($_POST['novy_nazev_pr'])
								{
							    
									foreach($_POST['novy_nazev_pr'] as $nn_k=>$nn_v)
									{
										$data_insert_n = array(
									    'id_obj' => $_POST['id'],
									    'typ' => 0,
									    'polozka' => $nn_v,
									    'kat_cislo' => $_POST['novy_kat_c_pr'][$nn_k],
									    'pocet' => $_POST['novy_pocet'][$nn_k],
									    'cena_za_ks' => str_replace(',','.',$_POST['novy_cena'][$nn_k]),
									    'dph' => $_POST['novy_dph'][$nn_k]
									     );
									     $query_insert_n = Db::insert('objednavky_polozky', $data_insert_n);
									}
									
									// log
						            $data_insert = array(
								    'id_admin' => $_SESSION['admin']['id'],
								    'uz_jm' => $_SESSION['admin']['uz_jm'],
								    'udalost' => 'Objednávky - přidání produktu do obj ID '.intval($_POST['id']),
								    'url' => sanitize($_SERVER['HTTP_REFERER']),
								    'ip' => sanitize(getip()),
								    'datum' => time()
								     );
								     $query_insert = Db::insert('admin_log_udalosti', $data_insert);
							    
							    }
							    
							    // přepočet cen
								$data_cc_bez = Db::queryRow('SELECT sum(pocet * cena_za_ks) AS CELKEM_BEZ FROM objednavky_polozky WHERE id_obj=?  ', array($_POST['id']));
								$data_cc_s = Db::queryRow('SELECT sum(pocet * cena_za_ks * (dph / 100 + 1)) AS CELKEM_S FROM objednavky_polozky WHERE id_obj=?  ', array($_POST['id']));
								
								// aktualizujeme objednávku
								$data_update = array(
								'cena_celkem' => $data_cc_bez['CELKEM_BEZ'],
								'cena_celkem_s_dph' => $data_cc_s['CELKEM_S']
								);
								$where_update = array('id' => $_POST['id']);
								$query = Db::update('objednavky', $data_update, $where_update);


							
							echo '<div class="alert-success">Uloženo!<br>Záznam byl v pořádku změněn<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a>
							<br>
							<a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'&action=update&id='.intval($_GET['id']).'">Znovu upravit</a></div>';
					   }
					   else
					   {
						   echo '<div class="alert-warning">Chyba!<br>Nejsou vyplněny všechny povinné položky<br>';
						   foreach($form->errors as $ke=>$ve){ echo $ve.'<br>'; }
						   echo '<a href="'.$_SERVER['HTTP_REFERER'].'">Přejít zpět</a><br>
						   <a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
					   }
				  	     
			
			}
			else
			{
				echo '<div class="alert-warning">Chyba!<br>Chybí ID záznamu<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
			}
			
	}
	else
	{
		
          
		  $data_o = Db::queryRow('SELECT O.*, 
		  Z.id AS ZID, Z.jmeno, Z.prijmeni, Z.email, Z.telefon, Z.dodaci_ulice, Z.dodaci_cislo, Z.dodaci_obec, Z.dodaci_psc, Z.dodaci_id_stat, Z.fakturacni_firma, Z.fakturacni_ulice, Z.fakturacni_cislo, 
		  Z.fakturacni_obec, Z.fakturacni_psc, Z.fakturacni_id_stat, Z.ic, Z.dic,
		  SO.nazev AS STAV 
		  FROM objednavky O 
		  LEFT JOIN zakaznici Z ON Z.id=O.id_zakaznik
		  LEFT JOIN objednavky_stavy SO ON SO.id=O.id_stav
		  WHERE O.id=?  ', array($_GET['id']));
		  if($data_o !== false ) 
		  {
		    echo '<h3>Objednávka č. '.$data_o['cislo_obj'].'</h3>';
		    echo '<div class="clear" style="height: 5px;"></div>';

		    echo '<div style="width: 300px; float: left;">';
		    echo 'Datum: '.date('d.m.Y H:i:s',$data_o['datum']).'<br />';
		    echo 'Zákazník: <a href="./index.php?p=zakaznici&action=update&id='.$data_o['id_zakaznik'].'">'.$data_o['jmeno'].' '.$data_o['prijmeni'].'</a><br />';
		    echo 'Email: '.$data_o['email'].'<br />';
		    echo 'Telefon: '.$data_o['telefon'].'<br />';
		    echo '</div>';
		    
		    echo '<div style="width: 400px; float: left;">';
		    echo 'Cenová skupina: '.$data_o['cenova_skupina'].'<br />';
		    echo 'Slevová skupina: ';
		    if($data_o['skupina_slev']>0){echo $data_o['skupina_slev'].'%';}
		    echo '<br />';
		    echo 'IP: <a href="https://www.infosniper.net/index.php?lang=1&ip_address='.$data_o['ip'].'" target="_blank" rel="nofollow">'.$data_o['ip'].'</a><br />';
		    echo 'Referer: '.$data_o['referer'].'<br />';
		    echo 'OS: '.$data_o['os'].'<br />';
		    echo '</div>';
		    
		    echo '<div style="width: 300px; float: left;">';
		    echo 'Souhlas s Obch. podmínkami: '.$stav_adm_sluzby_arr[$data_o['souhlas_op']].'<br>';
		    echo 'Nesouhlas Heureka: '.$stav_adm_sluzby_arr[$data_o['nesouhlas_heureka']].'<br>';
		    echo 'Faktura: <a href="'.$data_o['faktura_url'].'" target="_blank">'.$data_o['faktura_cislo'].'</a>';
		    echo '</div>';
		    
		    
		    // odesílání SMS
		    echo '<div style="width: 300px; float: left;">';
		    echo $form->addHTML('<label class="control-label" for="_odeslat_sms">Odeslat SMS:</label>');
		    echo $form->addHTML('<select name="odeslat_sms" id="odeslat_sms" onchange="zobraz_sms_pole(\'div_sms_text\','.time().');" >');
		    echo $form->addHTML('<option value="0"  selected >neodesílat</option>');
		     

			   $data_sms = Db::queryAll('SELECT * FROM sms_sablony ORDER BY id ', array());
			   if($data_sms !== false) 
			   {
					foreach ($data_sms as $row_sms) 
					{
			          echo $form->addHTML('<option value="'.$row_sms['id'].'" >'.$row_sms['nazev'].'</option>');
			        }
				}

		    echo $form->addHTML('</select>');
		    echo $form->addHTML('<div style="display: none;" id="div_sms_text"><textarea rows="5" name="sms_text" id="sms_text"></textarea></div>');
		    echo $form->addHTML('<input type="hidden" name="zak_id" id="zak_id" value="'.$data_o['ZID'].'">');
		    echo $form->addHTML('<input type="hidden" name="zak_tel" id="zak_tel" value="'.$data_o['telefon'].'">');
		    echo $form->addHTML('<input type="hidden" name="obj_id" id="obj_id" value="'.$data_o['id'].'">');
		    echo $form->addHTML('<input type="hidden" name="obj_c_z" id="obj_c_z" value="'.$data_o['cislo_zasilky'].'">');
		    echo $form->addHTML('&nbsp;&nbsp;<input type="button" name="odeslat_sms2" id="odeslat_sms2" value="odeslat SMS" onclick="odeslat_sms_zpravu();" />');
		    echo '</div>';
		    
		    echo '<div class="clear" style="height: 10px;"></div>';
		    
		    // fakturační adresa
		    echo '<div style="width: 300px; float: left;">';
		    echo '<fieldset><legend><h3>Fakturační údaje</h3></legend>';
		    if($data_o['fakturacni_firma']){echo $data_o['fakturacni_firma'].'<br>';}
		    if($data_o['ic']){echo 'IČ: '.$data_o['ic'].'<br>';}
		    if($data_o['dic']){echo 'DIČ: '.$data_o['dic'].'<br>';}
		    echo $data_o['fakturacni_ulice'].' '.$data_o['fakturacni_cislo'].'<br>';
		    echo $data_o['fakturacni_obec'].'<br>';
		    echo $data_o['fakturacni_psc'].'<br>';
		    echo $staty_arr[$data_o['fakturacni_id_stat']].'<br>';
		    echo '</fieldset>';
		    echo '</div>';
		    
		    // dodací adresa
		    echo '<div style="width: 300px; float: left;">';
		    echo '<fieldset><legend><h3>Dodací údaje</h3></legend>';
		    if($data_o['dodaci_nazev']){echo $data_o['dodaci_nazev'].'<br>';}
		    echo $data_o['dodaci_ulice'].' '.$data_o['dodaci_cislo'].'<br>';
		    echo $data_o['dodaci_obec'].'<br>';
		    echo $data_o['dodaci_psc'].'<br>';
		    echo $staty_arr[$data_o['dodaci_id_stat']].'<br>';
		    echo '</fieldset>';
		    echo '</div>';
		    
		    echo '<div class="clear" style="height: 10px;"></div>';
			
			//produkty objednávky
			echo '<form name="uo" method="post"><table border="0" class="ta" id="tab_obj" cellspacing="0" cellpadding="3">
			<tr class="tr-header">
			<td style="width: 450px;">Produkt</td>
			<td>Počet</td>
			<td>Cena za ks bez DPH</td>
			<td>DPH</td>
			<td>Cena celekem bez DPH</td>
			<td>Cena celekem s DPH</td>
			<td style="width: 80px;">Akce</td>
			</tr>';
			
			echo '<tr><td colspan="7" style="text-align: right;"><a href="javascript:PridatPolozkuObjednavky(\''.__MENA__.'\',\''.__DPH__.'\');">přidat položku do objednávky</a></td></tr>';
			
			
			// nejdříve zboží
			$data_op = Db::queryAll('SELECT * FROM objednavky_polozky WHERE id_obj=? AND typ=? ORDER BY id', array($data_o['id'],0));
			if($data_op !== false ) 
			{	
				foreach ($data_op as $row_op) 
				{
				  echo '<tr '.__TR_BG__.'>
						<td><a href="./index.php?p=produkty&action=update&id='.$row_op['id_produkt'].'">'.$row_op['polozka'].'</a>
						<br>Kat. číslo var.: '.$row_op['kat_cislo'].'<br>ID var.: '.$row_op['id_varianta'].'</td>
						<td><input type="text" name="obj_pocet['.$row_op['id'].']" id="obj_pocet_'.$row_op['id'].'" value="'.$row_op['pocet'].'" onkeyup="VypocitejCenuRadekPuvodni('.$row_op['id'].',\''.__MENA__.'\')" style="min-width: 20px;max-width: 20px;"></td>
						<td><input type="text" name="obj_cena['.$row_op['id'].']" id="obj_cena_'.$row_op['id'].'" value="'.$row_op['cena_za_ks'].'" onkeyup="VypocitejCenuRadekPuvodni('.$row_op['id'].',\''.__MENA__.'\')" style="min-width: 80px;max-width: 80px;"> '.__MENA__.'</td>
						<td id="obj_dph_'.$row_op['id'].'">'.$row_op['dph'].'</td>
						<td class="obj_c_c_b" id="obj_cena_bez_'.$row_op['id'].'">'.round(($row_op['cena_za_ks'] * $row_op['pocet']),2).' '.__MENA__.'</td>
						<td class="obj_c_c" id="obj_cena_s_'.$row_op['id'].'">'.number_format(round(($row_op['cena_za_ks'] * $row_op['pocet'] * ($row_op['dph'] / 100 + 1)),2), 2, '.', '').' '.__MENA__.'</td>
						<td><a href="smazat-polozku-objednavky.php?ido='.$data_o['id'].'&idp='.$row_op['id'].'" '.__JS_CONFIRM__.'>smazat položku</a></td>
						</tr>';
						
				}
			}
			
			// pak slevový kód
			// ten se při realizaci objednávky vždy přepočte na částku a ta už se nemění
			$data_ops = Db::queryRow('SELECT * FROM objednavky_polozky WHERE id_obj=? AND typ=?', array($data_o['id'],3));
			if($data_ops !== false) 
		    {
					 echo '<tr '.__TR_BG__.'>
						 <td>Slevový kód'.$data_ops['polozka'].'</a>
						 <br>'.$data_ops['kat_cislo'].'</td>
						 <td><input type="hidden" name="obj_pocet['.$data_ops['id'].']" value="'.$data_ops['pocet'].'">'.$data_ops['pocet'].'</td>
						 <td><input type="text" name="obj_cena['.$data_ops['id'].']" value="'.$data_ops['cena_za_ks'].'" style="min-width: 80px;max-width: 80px;"> '.__MENA__.'</td>
						 <td>'.$data_ops['dph'].'</td>
						 <td class="obj_c_c_b">'.$data_ops['cena_za_ks'].' '.__MENA__.'</td>
						 <td class="obj_c_c">'.number_format(round(($data_ops['cena_za_ks'] * ($data_ops['dph'] / 100 + 1)),2), 2, '.', '').' '.__MENA__.'</td>
						 <td><a href="smazat-polozku-objednavky.php?ido='.$data_o['id'].'&idp='.$data_ops['id'].'" '.__JS_CONFIRM__.'>smazat položku</a></td>
						 </tr>';
			}
			
			// pak doprava
			$data_opd = Db::queryRow('SELECT * FROM objednavky_polozky WHERE id_obj=? AND typ=?', array($data_o['id'],1));
			if($data_opd !== false) 
		    {
					 echo '<tr '.__TR_BG__.'>
						 <td>Doprava: '.$data_opd['polozka'].'</a>
						 <br>'.$data_opd['kat_cislo'].'</td>
						 <td><input type="hidden" name="obj_pocet['.$data_opd['id'].']" id="obj_pocet_'.$data_opd['id'].'" value="'.$data_opd['pocet'].'" >'.$data_opd['pocet'].'</td>
						 <td><input type="text" name="obj_cena['.$data_opd['id'].']" id="obj_cena_'.$data_opd['id'].'" value="'.$data_opd['cena_za_ks'].'" onkeyup="VypocitejCenuRadekPuvodni('.$data_opd['id'].',\''.__MENA__.'\')" style="min-width: 80px;max-width: 80px;"> '.__MENA__.'</td>
						 <td id="obj_dph_'.$data_opd['id'].'">'.$data_opd['dph'].'</td>
						 <td class="obj_c_c_b" id="obj_cena_bez_'.$data_opd['id'].'">'.$data_opd['cena_za_ks'].' '.__MENA__.'</td>
						 <td class="obj_c_c" id="obj_cena_s_'.$data_opd['id'].'">'.number_format(round(($data_opd['cena_za_ks'] * ($data_opd['dph'] / 100 + 1)),2), 2, '.', '').' '.__MENA__.'</td>
						 <td><a href="smazat-polozku-objednavky.php?ido='.$data_o['id'].'&idp='.$data_opd['id'].'" '.__JS_CONFIRM__.'>smazat položku</a></td>
						 </tr>';
			}
			
			// pak platba
			$data_opp = Db::queryRow('SELECT * FROM objednavky_polozky WHERE id_obj=? AND typ=?', array($data_o['id'],2));
			if($data_opp !== false) 
		    {
					 echo '<tr '.__TR_BG__.'>
						 <td>Platba: '.$data_opp['polozka'].'</a>
						 <br>'.$data_opp['kat_cislo'].'</td>
						 <td><input type="hidden" name="obj_pocet['.$data_opp['id'].']" id="obj_pocet_'.$data_opp['id'].'" value="'.$data_opp['pocet'].'">'.$data_opp['pocet'].'</td>
						 <td><input type="text" name="obj_cena['.$data_opp['id'].']" id="obj_cena_'.$data_opp['id'].'" value="'.$data_opp['cena_za_ks'].'" onkeyup="VypocitejCenuRadekPuvodni('.$data_opp['id'].',\''.__MENA__.'\')" style="min-width: 80px;max-width: 80px;"> '.__MENA__.'</td>
						 <td id="obj_dph_'.$data_opp['id'].'">'.$data_opp['dph'].'</td>
						 <td class="obj_c_c_b" id="obj_cena_bez_'.$data_opp['id'].'">'.$data_opp['cena_za_ks'].' '.__MENA__.'</td>
						 <td class="obj_c_c" id="obj_cena_s_'.$data_opp['id'].'">'.number_format(round(($data_opp['cena_za_ks'] * ($data_opp['dph'] / 100 + 1)),2), 2, '.', '').' '.__MENA__.'</td>
						 <td><a href="smazat-polozku-objednavky.php?ido='.$data_o['id'].'&idp='.$data_opp['id'].'" '.__JS_CONFIRM__.'>smazat položku</a></td>
						 </tr>';
			}
			
			 $data_cc_bez = Db::queryRow('SELECT sum(pocet * cena_za_ks) AS CELKEM_BEZ FROM objednavky_polozky WHERE id_obj=?  ', array($data_o['id']));
			 $data_cc_s = Db::queryRow('SELECT sum(pocet * cena_za_ks * (dph / 100 + 1)) AS CELKEM_S FROM objednavky_polozky WHERE id_obj=?  ', array($data_o['id']));
			 echo '<tr '.__TR_BG__.'>
						 <td colspan="4"><b>Cena celkem</b></td>
						 <td id="obj_c_c_b"><b>'.number_format(round($data_cc_bez['CELKEM_BEZ'],2), 2, '.', ' ').' '.__MENA__.'</b></td>
						 <td id="obj_c_c_c"><b>'.number_format(round($data_cc_s['CELKEM_S'],2), 2, '.', ' ').' '.__MENA__.'</b></td>
						 <td>&nbsp;</td>
						 </tr>';
			
			echo '</table><div class="clear" style="height: 10px;"></div>';
			
			// váha + poznámka zákazníka
			echo '<div style="width: 400px; float: left;">';
			echo '<label class="control-label" for="cvz">Celková váha zásilky:</label>
			<div class="clear"></div>
			<input type="text" name="vaha" id="vaha" value="'.$data_o['vaha'].'" style="min-width: 120px;max-width: 120px;"  ><span >v gramech</span>
			<div class="clear" style="height: 10px;"></div>
			<label class="control-label" for="popis">Poznámka zákazníka:</label><textarea name="poznamka_zakaznik" 
			id="poznamka_zakaznik" style="width: 350px;" >'.$data_o['poznamka_zakaznik'].'</textarea>';
			echo '</div>';
			
			// udaná cena + interní poznámka
			echo '<div style="width: 400px; float: left;">';
			echo '<label class="control-label" for="ucz">Udaná cena zásilky:</label>
			<div class="clear"></div>
			<input type="text" name="udana_cena" id="udana_cena" value="'.$data_o['udana_cena'].'" style="min-width: 120px;max-width: 120px;"  ><span >'.__MENA__.'</span>
			<div class="clear" style="height: 10px;"></div>
			<label class="control-label" for="popis">Poznámka naše:</label><textarea name="poznamka_prodejce" 
			id="poznamka_prodejce" style="width: 350px;" >'.$data_o['poznamka_prodejce'].'</textarea>';
			echo '</div>';
			
			// číslo zásilky
			echo '<div style="width: 250px; float: left;">';
			echo '<label class="control-label" for="cz">Číslo zásilky:</label>
			<input type="text" name="cislo_zasilky" id="cislo_zasilky" value="'.$data_o['cislo_zasilky'].'" style="min-width: 200px;max-width: 200px;"  >
			<div class="clear" style="height: 10px;"></div>';
			
			// stav uhrady
				
			echo '<label class="control-label" for="stav_uhrady">Stav úhrady:</label>';
			echo '<select name="id_stav_uhrady" id="id_stav_uhrady" >'; 
		    foreach ($stavy_fak_arr as $stavy_fak_arr_k => $stavy_fak_arr_v) 
			{
			     echo '<option value="'.$stavy_fak_arr_k.'" ';
			     if($data_o['id_stav_uhrady']==$stavy_fak_arr_k){echo ' selected ';}
			     echo '>'.$stavy_fak_arr_v.'</option>';
			}
			
			echo '</select>&nbsp;&nbsp;';
			echo '</div>';
			
			echo '<div style="float: right;">';
			echo '<input type="hidden" name="id" id="id" value="'.$_GET['id'].'"   >
			<input type="submit" name="submit" id="submit" value="Uložit provedené úpravy"  >
			<input type="hidden" name="_formbp" value="'.md5(time()).'" >';
			echo '</div>';
			echo '</form>';
			echo '<div class="clear" style="height: 20px;"></div>';
			
			
			// stav obj
			echo '<fieldset><legend><h2>Stav objednávky</h2></legend>';
			
			$data_stavy = Db::queryAll('SELECT * FROM objednavky_stavy WHERE aktivni=1 ORDER BY id ASC', array());
			if($data_stavy !== false ) 
			{	
				echo '<label class="control-label" for="stav_obj">Stav:</label>';
				echo '<select name="id_stav" id="id_stav" onchange="ZmenObsahEditoru();">'; 
			    foreach ($data_stavy as $row_stavy) 
				{
				     echo '<option value="'.$row_stavy['id'].'" ';
				     if($data_o['id_stav']==$row_stavy['id']){echo ' selected ';$obsah_editoru = $row_stavy['text_email'];}
				     echo '>'.$row_stavy['nazev'].'</option>';
				}
				
				echo '</select>&nbsp;&nbsp;
				<label for="slevovy_kod" class="inline"><input type="checkbox" name="slevovy_kod" id="slevovy_kod" value="1"> přidat slevový kód</label>&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="hidden" name="email_zakaznika" id="email_zakaznika" value="'.$data_o['email'].'">
				<input type="hidden" name="id_objednavky" id="id_objednavky" value="'.$data_o['id'].'">
				<input type="button" name="odeslat_email" id="odeslat_email" value="Odeslat zákazníkovi email" onclick="OdeslatEmail();"  style="display: inline-block" >';
				echo '<div class="clear" style="height: 20px;"></div>';
				echo '<script type="text/javascript"> aktivujTinyMCE(\'text_email\',300) </script>
				<label class="control-label" for="text_email">Text emailu:</label><textarea name="text_email" id="text_email"  required  >'.$obsah_editoru.'</textarea>';
			}
            
            echo '</fieldset>';
			
			
			// kontrolujeme jestli máme pro jednotlivé služby (fakturoid atd.) vyplněny požadované údaje v obecném nastavení
			// pokud ne, tak tlačítka vůbec nezobrazíme
			
			echo '
			<input type="button" name="xml_pohoda" id="xml_pohoda" value="XML Pohoda"  style="display: inline-block" 
			onclick="window.location.href=\'obj-xml-pohoda.php?ido='.$data_o['id'].'\'">&nbsp;&nbsp;
			<input type="button" name="xml_zasilkovna" id="xml_zasilkovna" value="CSV Zásilkovna" style="display: inline-block" 
			onclick="window.location.href=\'obj-csv-zasilkovna.php?ido='.$data_o['id'].'\'" >&nbsp;&nbsp;';
			
			echo '<input type="button" name="csv_cp" id="csv_cp" value="CSV Česká Pošta" style="display: inline-block" 
			onclick="window.location.href=\'obj-csv-cp.php?ido='.$data_o['id'].'\'"  >&nbsp;&nbsp;';
			
			//echo '<input type="button" name="balikobot_pridat" id="balikobot_pridat" value="Balíkobot přidat balík"  style="display: inline-block" >&nbsp;&nbsp;';
			
			if($data_o['faktura_id'])
			{
				echo '<input type="button" name="fakturoid_smazat" id="fakturoid_smazat" value="Smazat fakturu Fakturoid" style="display: inline-block" 
				onclick="FakturoidSmazatFakturu('.$data_o['id'].')" >&nbsp;&nbsp;';
		    }
		    else
		    {
				  echo '<input type="button" name="fakturoid_vystavit" id="fakturoid_vystavit" value="Vystavit fakturu Fakturoid" style="display: inline-block" ';
				  if(__FAKTUROID_SLUG__ && __FAKTUROID_API_KEY__ && __FAKTUROID_EMAIL__){ echo 'onclick="FakturoidVystavitFakturu('.$data_o['id'].')"';}
				  else{echo 'onclick="alert(\'Nemáte vyplněny potřebné údaje v Obecném nastavení v sekci Fakturoid.\')"';}
				  echo ' >&nbsp;&nbsp;';
			}
			
			echo '<label for="transferred_tax_liability" class="inline"><input type="checkbox" name="transferred_tax_liability" id="transferred_tax_liability" value="1" > reverse charge</label>';
		    
			
			echo '<input type="button" name="tisk" id="tisk" value="Tisk objednávky" onclick="window.print();" style="display: inline-block; float: right;"  >';
			 
			
			
            
            
            // historie změn stavů objednavky
            echo '<div class="clear" style="height: 20px;"></div>';
            echo '<div style="float: left; width: 50%">';
            echo '<fieldset><legend><h2>Historie změn stavů objednávky</h2></legend>';
            
            $data_zmeny = Db::queryAll('SELECT OZ.*, A.uz_jm 
            FROM objednavky_historie_zmen OZ 
            LEFT JOIN admin A ON A.id=OZ.id_admin
            WHERE OZ.id_obj='.$data_o['id'].' ORDER BY OZ.id DESC', array());
			if($data_zmeny !== false ) 
			{	
				echo '<table border="0" class="ta" cellspacing="0" cellpadding="3">
				<tr class="tr-header">
				<td>Datum</td>
				<td>Stav</td>
				<td>Admin</td>
				</tr>';
				
				foreach ($data_zmeny as $row_zmeny) 
				{
					 echo '<tr '.__TR_BG__.'>
						 <td>'.date('d.m.Y H:i:s',$row_zmeny['datum']).'</td>
						 <td>'.$row_zmeny['stav'].'</td>
						 <td>'.$row_zmeny['uz_jm'].'</td>
						 </tr>';
				}
				
				echo '</table>';
			
			}
            echo '</fieldset>';
            echo '</div>';
            
            
            // historie odeslaných SMS
            // echo '<div class="clear" style="height: 20px;"></div>';
            echo '<div style="float: left; width: 50%">';
            echo '<fieldset><legend><h2>Historie odeslaných SMS</h2></legend>';
            
            $data_zmeny = Db::queryAll('SELECT S.*, A.uz_jm 
            FROM sms_odeslane S
            LEFT JOIN admin A ON A.id=S.id_admin
            WHERE S.id_obj='.$data_o['id'].' ORDER BY S.id DESC', array());
			if($data_zmeny !== false ) 
			{	
				echo '<table border="0" class="ta" cellspacing="0" cellpadding="3">
				<tr class="tr-header">
				<td>Datum</td>
				<td>SMS</td>
				<td>Admin</td>
				</tr>';
				
				foreach ($data_zmeny as $row_zmeny) 
				{
					 echo '<tr '.__TR_BG__.'>
						 <td>'.date('d.m.Y H:i:s',$row_zmeny['datum']).'</td>
						 <td>'.$row_zmeny['sms'].'</td>
						 <td>'.$row_zmeny['uz_jm'].'</td>
						 </tr>';
				}
				
				echo '</table>';
			
			}
            echo '</fieldset>';
            echo '</div>';
            
            echo '<div class="clear" style="height: 20px;"></div>';
			
			 
		    
		  }
		  else
		  {
			  echo '<div class="alert-warning">Chyba!<br>Nepodařilo se vybrat data z databáze objednávky<br></div>';
		  }
  }
  
  

}
else
{
	// vypis vsech
	//$sql_podminka_jmeno = '';
	$sql_podminka_prijmeni = '';
	$sql_podminka_email = '';
	$sql_podminka_telefon = '';
	$sql_podminka_obec = '';
	$sql_podminka_cislo_obj = '';
	$sql_podminka_stav = '';
	$sql_podminka_stav_uhrady = '';
	$sql_podminka_datum = '';
	
	/*if($_GET['jmeno'])
	{		
		$sql_podminka_jmeno .= ' AND Z.jmeno="'.sanitize($_GET['jmeno']).'" ';
	}*/

	if($_GET['prijmeni'])
	{
		$sql_podminka_prijmeni .= ' AND Z.prijmeni="'.sanitize($_GET['prijmeni']).'" ';
	}

	if($_GET['email'])
	{
		$sql_podminka_email .= ' AND Z.email="'.sanitize($_GET['email']).'" ';
	}
	
	if($_GET['telefon'])
	{
		$sql_podminka_telefon .= ' AND Z.telefon LIKE "%'.sanitize($_GET['telefon']).'%" ';	
	}
	
	if($_GET['obec'])
	{
		$sql_podminka_obec .= ' AND Z.dodaci_obec LIKE "%'.sanitize($_GET['obec']).'%" ';	
	}

	if($_GET['cislo_obj'])
	{
		$sql_podminka_cislo_obj .= ' AND O.cislo_obj="'.sanitize($_GET['cislo_obj']).'" ';
	}
	
	if($_GET['id_stav'])
	{
		$sql_podminka_stav .= ' AND O.id_stav="'.sanitize($_GET['id_stav']).'" ';
	}
	
	if(isset($_GET['id_stav_uhrady']))
	{
		$sql_podminka_stav_uhrady .= ' AND O.id_stav_uhrady="'.sanitize($_GET['id_stav_uhrady']).'" ';
	}
	
	if($_GET['datum'])
	{
		list($den,$mesic,$rok) = explode(".",$_GET['datum']);
		 $hodina = 0;
		 $minuta = 0;
		 $sekunda = 1;
		 $datum_od = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);
		 
		list($den,$mesic,$rok) = explode(".",$_GET['datum']);
		 $hodina = 23;
		 $minuta = 59;
		 $sekunda = 59;
		 $datum_do = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok); 
							 
		$sql_podminka_datum .= ' AND (O.datum >="'.$datum_od.'" AND O.datum <="'.$datum_do.'") ';
	}


	echo '<form name="f_prehled" id="f_prehled" method="post" action="">';
	
	$odkaz_jinam_arr = array('faktura_url','','','faktura');
	
	$tabulka = new Vypisy('objednavky','','SELECT O.*, Z.jmeno, Z.prijmeni, Z.email, Z.telefon, Z.dodaci_obec, Z.cenova_skupina, SO.nazev AS STAV 
	FROM objednavky O 
	LEFT JOIN zakaznici Z ON Z.id=O.id_zakaznik 
	LEFT JOIN objednavky_stavy SO ON SO.id=O.id_stav 
	WHERE 1 '.$sql_podminka_prijmeni.$sql_podminka_email.$sql_podminka_telefon.$sql_podminka_obec.$sql_podminka_cislo_obj.$sql_podminka_stav.$sql_podminka_stav_uhrady.$sql_podminka_datum.' ','',
	array('O.id','O.cislo_obj','Z.jmeno','Z.prijmeni','Z.dodaci_obec','Z.email','Z.telefon','O.cena_celkem_s_dph','O.cenova_skupina','O.datum','SO.STAV','O.id_stav_uhrady'),
	array('Id','Č. objednávky','Jméno','Příjmení','Obec','Email','Telefon','Cena s DPH','Cenová sk.','Dat. vytvoření','Stav','Stav úhrady','Akce'),1,0,0,$odkaz_jinam_arr);
	echo $tabulka->GenerujVypis();
	
	echo '<div class="clear" style="height: 20px;"></div>';
	
	echo '<input type="button" name="xml_pohoda" id="xml_pohoda" value="XML Pohoda"  style="display: inline-block" 
			onclick="ObjPrehled(1);">&nbsp;&nbsp;
			
		  <input type="button" name="xml_zasilkovna" id="xml_zasilkovna" value="CSV Zásilkovna" style="display: inline-block" 
			onclick="ObjPrehled(2);" >&nbsp;&nbsp;';
			
	echo '<input type="button" name="csv_cp" id="csv_cp" value="CSV Česká Pošta" style="display: inline-block" 
	onclick="ObjPrehled(3);"  >&nbsp;&nbsp;';
	
	echo '<input type="button" name="tisk" id="tisk" value="Tisk objednávky"
	onclick="ObjPrehled(4);" style="display: inline-block; float: right;"  >';
	echo '</form>';

}

?>
<script type="text/javascript">

 $(document).ready(function () {
    $("#formular").validate({
       
    });
    
     });
 
    
</script>    
