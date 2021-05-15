<?php 
// statistiky objednavek
Admin::adminLogin(5);


	// filtr
	echo '<fieldset><legend>Filtr</legend>';
	$form = new Form('', '', 'get', 'formular2', 'formular', 'utf-8', '', 0);
	
	
	$data = Db::queryAll('SELECT id, nazev FROM objednavky_stavy WHERE 1 ORDER BY razeni ASC', array());
	$stav_obj_adm_arr = array();
	if($data !== false ) 
	{
		$stav_obj_adm_arr[0] = 'vyberte';
	    foreach ($data as $row) 
		{
		  $stav_obj_adm_arr[$row['id']] = $row['nazev'];	
		}
	}
	else
	{
		$stav_obj_adm_arr = false;
	}
	
	echo $form->formOpen();
	echo $form->addHTML('<div style="float: left; width: 40%;">');
	echo $form->inputSelect('stav','stav',intval($_GET['stav']),$stav_obj_adm_arr,'Stav','',0,0,'');
	echo $form->addHTML('</div>');
	echo $form->addHTML('<div style="float: left; width: 45%; margin-top: 20px;">');
	echo $form->inputHidden('p','p',sanitize($_GET['p']),'',0);
	echo $form->inputHidden('pp','pp',sanitize($_GET['pp']),'',0);
	echo $form->inputSubmit('submit','submit','Zobrazit','','','');
	echo $form->inputButton('redir','redir','Zobrazit vše','','onclick="self.location.href=\'index.php?p=statistiky\'"','');
	echo $form->addHTML('</div>');
	echo $form->formClose();

	echo '</fieldset>';
	
	// vypis vsech
	if($_GET['stav'])
	{
		
		$sql_podminka .= ' AND id_stav="'.intval($_GET['stav']).'" ';

	}
	else
	{
		$sql_podminka = '';
	}
	
 


echo '<table border="0" class="ta" cellspacing="0" cellpadding="6">
<tr class="tr-header">
<td>Období</td>
<td>Počet obj.</td>
<td>Částka celkem s DPH</td>
<td title="Seznam.cz" style="text-align: center;">Seznam.cz</td>
<td title="Firmy.cz" style="text-align: center;">Firmy.cz</td>
<td title="Zbozi.cz" style="text-align: center;">Zbozi.cz</td>
<td title="Sklik.cz" style="text-align: center;">Sklik.cz</td>
<td title="Google.cz" style="text-align: center;">Google.cz</td>
<td title="Google.com" style="text-align: center;">Google.com</td>
<td title="Facebook.com" style="text-align: center;">Facebook</td>
<td title="Heureka.cz" style="text-align: center;">Heureka.cz</td>
<td title="Ostatní" style="text-align: center;">Ostatni</td>
</tr>';

$stat_date_y_from = '2021'; 
$stat_date_y_to = date("Y");

		for($rok = $stat_date_y_to; $rok >= $stat_date_y_from; $rok--) 
		{	
			
			for($mesic = 1; $mesic <= 12; $mesic++) 
		    {
				echo '<tr '.__TR_BG__.'>
				<td>'.$rok.' / ';
			
				if(strlen($mesic)==1)
				   {
				    echo '0';
				   }
				   
				echo $mesic;
				
				$den_max = cal_days_in_month(CAL_GREGORIAN, $mesic, $rok); 
				$datum_zacatek_timestamp = mktime(0,0,0,$mesic,1,$rok);
				$datum_konec_timestamp = mktime(23,59,59,$mesic,$den_max,$rok);
				$celkem_cz = 0;
	
				// pocet cz objednavek za dane obdobi
				$pocet_obj = Db::queryCount2('objednavky','id','',' datum>='.$datum_zacatek_timestamp.' AND datum<='.$datum_konec_timestamp.$sql_podminka);

				// castka cz objednavek za dane obdobi
				$castka_celkem = Db::queryRow('SELECT sum(cena_celkem_s_dph) AS CENA_CELKEM FROM objednavky WHERE datum>='.$datum_zacatek_timestamp.' AND datum<='.$datum_konec_timestamp.$sql_podminka, array());

				echo '</td>
				<td style="text-align: center;">'.$pocet_obj.'</td>
				<td style="text-align: center;">'.number_format($castka_celkem['CENA_CELKEM'], 0, '.', ' ').' '.__MENA__.'</td>';
				
				$s_cz = vyhledavace('seznam.cz',$datum_zacatek_timestamp,$datum_konec_timestamp,$_GET['stav']);
				$f_cz = vyhledavace('firmy.cz',$datum_zacatek_timestamp,$datum_konec_timestamp,$_GET['stav']);
				$z_cz = vyhledavace('zbozi.cz',$datum_zacatek_timestamp,$datum_konec_timestamp,$_GET['stav']);
				$sk_cz = vyhledavace('imedia.cz',$datum_zacatek_timestamp,$datum_konec_timestamp,$_GET['stav']);
				$g_cz = vyhledavace('google.cz',$datum_zacatek_timestamp,$datum_konec_timestamp,$_GET['stav']);
				$g_com = vyhledavace('google.com',$datum_zacatek_timestamp,$datum_konec_timestamp,$_GET['stav']);
				$g_n = vyhledavace('facebook.com',$datum_zacatek_timestamp,$datum_konec_timestamp,$_GET['stav']);
				$h_cz = vyhledavace('heureka.cz',$datum_zacatek_timestamp,$datum_konec_timestamp,$_GET['stav']);
				
				$ostatni = ($pocet_obj - ($s_cz['pocet'] + $f_cz['pocet'] + $z_cz['pocet'] + $sk_cz['pocet'] + $g_cz['pocet'] + $g_com['pocet'] + $g_n['pocet'] + $h_cz['pocet']));
				
				$ostatni_cena = ($castka_celkem['CENA_CELKEM'] - ($s_cz['cena'] + $f_cz['cena'] + $z_cz['cena'] + $sk_cz['cena'] + $g_cz['cena'] + $g_com['cena'] + $g_n['cena'] + $h_cz['cena']));
				
				// Seznam.cz
				echo '<td style="text-align: center;"><a href="./_det-statistiky.php?vyhl=seznam.cz&od='.$datum_zacatek_timestamp.'&do='.$datum_konec_timestamp.'&stav='.$_GET['stav'].'" class="iframe" title="Statistika Seznam.cz za '.strftime('%B %Y',$datum_zacatek_timestamp).'">'.$s_cz['pocet'].'</a><br />'.$s_cz['cena'].' '.__MENA__.'</td>';
				// Firmy.cz
				echo '<td style="text-align: center;"><a href="./_det-statistiky.php?vyhl=firmy.cz&od='.$datum_zacatek_timestamp.'&do='.$datum_konec_timestamp.'&stav='.$_GET['stav'].'" class="iframe" title="Statistika Firmy.cz za '.strftime('%B %Y',$datum_zacatek_timestamp).'">'.$f_cz['pocet'].'</a><br />'.$f_cz['cena'].' '.__MENA__.'</td>';
				// Zbozi.cz
				echo '<td style="text-align: center;"><a href="./_det-statistiky.php?vyhl=zbozi.cz&od='.$datum_zacatek_timestamp.'&do='.$datum_konec_timestamp.'&stav='.$_GET['stav'].'" class="iframe" title="Statistika Zbozi.cz za '.strftime('%B %Y',$datum_zacatek_timestamp).'">'.$z_cz['pocet'].'</a><br />'.$z_cz['cena'].' '.__MENA__.'</td>';
				// Sklik.cz
				echo '<td style="text-align: center;"><a href="./_det-statistiky.php?vyhl=imedia.cz&od='.$datum_zacatek_timestamp.'&do='.$datum_konec_timestamp.'&stav='.$_GET['stav'].'" class="iframe" title="Statistika Sklik.cz za '.strftime('%B %Y',$datum_zacatek_timestamp).'">'.$sk_cz['pocet'].'</a><br />'.$sk_cz['cena'].' '.__MENA__.'</td>';
				// Google.cz
				echo '<td style="text-align: center;"><a href="./_det-statistiky.php?vyhl=google.cz&od='.$datum_zacatek_timestamp.'&do='.$datum_konec_timestamp.'&stav='.$_GET['stav'].'" class="iframe" title="Statistika Google.cz za '.strftime('%B %Y',$datum_zacatek_timestamp).'">'.$g_cz['pocet'].'</a><br />'.$g_cz['cena'].' '.__MENA__.'</td>';
				// Google.com
				echo '<td style="text-align: center;"><a href="./_det-statistiky.php?vyhl=google.com&od='.$datum_zacatek_timestamp.'&do='.$datum_konec_timestamp.'&stav='.$_GET['stav'].'" class="iframe" title="Statistika Google.com za '.strftime('%B %Y',$datum_zacatek_timestamp).'">'.$g_com['pocet'].'</a><br />'.$g_com['cena'].' '.__MENA__.'</td>';
				// Facebook
				echo '<td style="text-align: center;"><a href="./_det-statistiky.php?vyhl=facebook.com&od='.$datum_zacatek_timestamp.'&do='.$datum_konec_timestamp.'&stav='.$_GET['stav'].'" class="iframe" title="Statistika Facebook za '.strftime('%B %Y',$datum_zacatek_timestamp).'">'.$g_n['pocet'].'</a><br />'.$g_n['cena'].' '.__MENA__.'</td>';
				// Heureka.cz
				echo '<td style="text-align: center;"><a href="./_det-statistiky.php?vyhl=heureka.cz&od='.$datum_zacatek_timestamp.'&do='.$datum_konec_timestamp.'&stav='.$_GET['stav'].'" class="iframe" title="Statistika Heureka.cz za '.strftime('%B %Y',$datum_zacatek_timestamp).'">'.$h_cz['pocet'].'</a><br />'.$h_cz['cena'].' '.__MENA__.'</td>';
				// Ostatni.cz
				echo '<td style="text-align: center;"><a href="./_det-statistiky.php?vyhl=&od='.$datum_zacatek_timestamp.'&do='.$datum_konec_timestamp.'&stav='.$_GET['stav'].'" class="iframe" title="Statistika Ostatní za '.strftime('%B %Y',$datum_zacatek_timestamp).'">'.$ostatni.'</a><br />'.$ostatni_cena.' '.__MENA__.'</td>';
				
				echo '</tr>';
			 
			 
			 }

			
			
		}
	
	
	


echo '</table>';

 

?>

