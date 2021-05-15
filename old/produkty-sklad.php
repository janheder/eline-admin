<?php 
// zákazníci

Admin::adminLogin(4);

// filtr
if(!$_GET['action'])
{
	echo '<fieldset><legend>Filtr</legend>';
	$form = new Form('', '', 'get', 'formular2', 'formular', 'utf-8', '', 0);
	
	echo $form->formOpen();
	
	echo $form->addHTML('<div style="float: left; width: 27%;">');
	echo $form->inputText('nazev','nazev',sanitize($_GET['nazev']),'Název produktu','','',0,0,'');
	echo $form->inputText('kat_cislo','kat_cislo',sanitize($_GET['kat_cislo']),'Katalogové číslo','','',0,0,'');
	echo $form->inputText('ean','ean',sanitize($_GET['ean']),'EAN','','',0,0,'');
	echo $form->addHTML('</div>');

	echo $form->addHTML('<div style="float: left; width: 27%;">');
	
	$stavy_vyhl_arr = $stav_adm_arr;
	$stavy_vyhl_arr[4] = 'Vše';
	if(!isset($_GET['stav'])){$_GET['stav'] = 4;}
	echo $form->inputSelect('stav','stav',sanitize($_GET['stav']),$stavy_vyhl_arr,'Stav','',0,0,'');
	
	// výrobce selectbox
	$data_v = Db::queryAll('SELECT id,vyrobce FROM produkty_vyrobci WHERE 1 ORDER BY vyrobce', array());
	$id_vyr_arr = array();
	if($data_v !== false ) 
	{	
	   $id_vyr_arr[0] = 'vše';
		
	   foreach ($data_v as $row_v) 
		{
		  $id_vyr_arr[$row_v['id']] = $row_v['vyrobce'];	
		}
	}
	else
	{
		$id_vyr_arr = false;
	}
	echo $form->inputSelect('vyrobce','vyrobce',sanitize($_GET['vyrobce']),$id_vyr_arr,'Výrobce','',0,0,'');
	
	// kategorie selectbox
	$data_k = Db::queryAll('SELECT id,nazev FROM kategorie WHERE vnor=1 ORDER BY nazev', array());
	$id_kat_arr = array();
	if($data_k !== false ) 
	{	
	   $id_kat_arr[0] = 'vše';
		
	   foreach ($data_k as $row_k) 
		{
			  $id_kat_arr[$row_k['id']] = $row_k['nazev'];	
			  // druhá úroveň
			  $data_k2 = Db::queryAll('SELECT id,nazev FROM kategorie WHERE vnor=2 AND id_nadrazeneho='.$row_k['id'].' ORDER BY nazev', array());
			  foreach ($data_k2 as $row_k2) 
			  {
				  $id_kat_arr[$row_k2['id']] = '&nbsp;&nbsp;&nbsp;'.$row_k2['nazev'];
			  }
		  
		}
	}
	else
	{
		$id_kat_arr = false;
	}
	echo $form->inputSelect('kategorie','kategorie',sanitize($_GET['kategorie']),$id_kat_arr,'Kategorie','',0,0,'');

	echo $form->addHTML('</div>');
	
	echo $form->addHTML('<div style="float: left; width: 20%;">');
	echo '<div class="clear" style="height: 20px;"></div>';
	echo '<label for="akce" class="inline"><input type="checkbox" name="akce" value="1" ';
	if($_GET['akce']==1){echo ' checked ';}
	echo ' id="akce"> Akce</label>';
	echo '<div class="clear" style="height: 5px;"></div>';
	echo '<label for="novinka" class="inline"><input type="checkbox" name="novinka" value="1" ';
	if($_GET['novinka']==1){echo ' checked ';}
	echo ' id="novinka"> Novinka</label>';
	echo '<div class="clear" style="height: 5px;"></div>';
	echo '<label for="doporucujeme" class="inline"><input type="checkbox" name="doporucujeme" value="1" ';
	if($_GET['doporucujeme']==1){echo ' checked ';}
	echo ' id="doporucujeme"> Doporučujeme</label>';
	echo '<div class="clear" style="height: 5px;"></div>';
	echo '<label for="doprodej" class="inline"><input type="checkbox" name="doprodej" value="1" ';
	if($_GET['doprodej']==1){echo ' checked ';}
	echo ' id="doprodej"> Doprodej</label>';
	echo $form->addHTML('</div>');
	
	echo $form->addHTML('<div style="float: left; width: 20%; margin-top: 20px;">');
	echo $form->inputHidden('p','p',sanitize($_GET['p']),'',0);
	echo $form->inputHidden('pp','pp',sanitize($_GET['pp']),'',0);
	echo $form->inputSubmit('submit','submit','Zobrazit','','','');
	echo $form->inputButton('redir','redir','Zobrazit vše','','onclick="self.location.href=\'index.php?p=produkty&pp=produkty-sklad\'"','');
	echo $form->addHTML('</div>');
	echo $form->formClose();

	echo '</fieldset>';
}




	// vypis vsech
	if($_GET['nazev'])
	{
		$sql_podminka_nazev .= ' AND P.nazev LIKE "%'.sanitize($_GET['nazev']).'%" ';
	}
	else
	{
		$sql_podminka_nazev = '';
	}
	
	if($_GET['kat_cislo'])
	{
		$sql_podminka_kat_cislo .= ' AND P.kat_cislo LIKE "%'.sanitize($_GET['kat_cislo']).'%" ';
	}
	else
	{
		$sql_podminka_kat_cislo = '';
	}
	
	if($_GET['ean'])
	{
		$sql_podminka_ean .= ' AND P.ean LIKE "%'.sanitize($_GET['ean']).'%" ';
	}
	else
	{
		$sql_podminka_ean = '';
	}
	
	
	if(isset($_GET['stav']) && $_GET['stav']!=4)
	{
		$sql_podminka_stav .= ' AND P.aktivni='.intval($_GET['stav']).' ';
	}
	else
	{
		$sql_podminka_stav = '';
	}
	
	
	if($_GET['vyrobce'])
	{
		$sql_podminka_vyrobce .= ' AND P.id_vyrobce='.intval($_GET['vyrobce']).' ';
	}
	else
	{
		$sql_podminka_vyrobce = '';
	}
	
	
	if($_GET['kategorie'])
	{
		$sql_podminka_kategorie .= "  AND P.id_kat_arr LIKE '%\"".intval($_GET['kategorie'])."\"%' ";
	}
	else
	{
		$sql_podminka_kategorie = '';
	}
	
	
	if($_GET['akce'])
	{
		$sql_podminka_akce .= ' AND P.akce='.intval($_GET['akce']).' ';
	}
	else
	{
		$sql_podminka_akce = '';
	}
	
	
	if($_GET['novinka'])
	{
		$sql_podminka_novinka .= ' AND P.novinka='.intval($_GET['novinka']).' ';
	}
	else
	{
		$sql_podminka_novinka = '';
	}
	
	
	if($_GET['doporucujeme'])
	{
		$sql_podminka_doporucujeme .= ' AND P.doporucujeme='.intval($_GET['doporucujeme']).' ';
	}
	else
	{
		$sql_podminka_doporucujeme = '';
	}
	
	
	if($_GET['doprodej'])
	{
		$sql_podminka_doprodej .= ' AND P.doprodej='.intval($_GET['doprodej']).' ';
	}
	else
	{
		$sql_podminka_doprodej = '';
	}
	
	
	// výpis

	
	// GET parametry
	if($_GET)
	{
		$get_parametry = '?';
		
		foreach($_GET as $get_key=>$get_val)
		{
			if($get_key!='limit')
			{
			   $get_parametry .= $get_key.'='.$get_val.'&';
			} 
			 
		}
		 
	}	
	
	$limit = intval($_GET['limit']);
	if(!$limit){$limit = 0;}
	

		
	$pocet = Db::queryAffected('SELECT V.id, P.nazev, P.str, P.id AS IDP
	FROM produkty_varianty V
	LEFT JOIN produkty P ON P.id = V.id_produkt 
	LEFT JOIN produkty_foto PF ON PF.id_produkt = P.id
	WHERE 1  '.$sql_podminka_nazev.$sql_podminka_kat_cislo.$sql_podminka_ean.$sql_podminka_stav.$sql_podminka_vyrobce.$sql_podminka_kategorie.$sql_podminka_akce.$sql_podminka_novinka.$sql_podminka_doporucujeme.$sql_podminka_doprodej.' ', array()); 
	
		if(__STRANKOVANI_ADMIN__ < $pocet)
			{ // pokud je méně výsledků než je výpis na stránku tak stránkování nezobrazujeme
				echo  '<div class="strankovani">';

	
				
				for ($px=0;$px<ceil($pocet/__STRANKOVANI_ADMIN__);$px++)
			    {
					echo '&nbsp;<a href="./index.php'.$get_parametry.'limit='.$limit2.'">';
					if($limit==$limit2) {echo '<strong class="navlink">'.($px+1).'</strong>';}
					else{echo ($px+1);}
					echo '</a>&nbsp;';
					$limit2 = $limit2 + __STRANKOVANI_ADMIN__;
				}
				echo '</div>';
		    }
	
	echo '<form name="f_prehled" id="f_prehled" method="post" action="./produkty-zmena-stavu.php">';
	
	echo '<table border="0" class="ta" cellspacing="0" cellpadding="6">
	<tr class="tr-header">
	<td>Foto</td>
	<td>Produkt</td>
	<td>Kat. číslo</td>
	<td>EAN</td>
	<td>Stav skladu</td>
	<td>Rezervace</td>
	<td>Datum</td>
	<td>Stav</td>
	<td style="width: 90px;">Akce</td>
	</tr>';
	 
	// výpis
	$data_vypis = Db::queryAll('SELECT V.*, P.nazev, P.str, P.id AS IDP
	FROM produkty_varianty V
	LEFT JOIN produkty P ON P.id = V.id_produkt 
	LEFT JOIN produkty_foto PF ON PF.id_produkt = P.id
	WHERE 1 
	'.$sql_podminka_nazev.$sql_podminka_kat_cislo.$sql_podminka_ean.$sql_podminka_stav.$sql_podminka_vyrobce.$sql_podminka_kategorie.$sql_podminka_akce.$sql_podminka_novinka.$sql_podminka_doporucujeme.$sql_podminka_doprodej.' GROUP BY V.id ORDER BY datum DESC LIMIT '.$limit.','.__STRANKOVANI_ADMIN__.'  ', array());
	if($data_vypis !== false ) 
	{
		foreach ($data_vypis as $row_vypis) 
		{
		
			echo '<tr '.__TR_BG__.'>
			<td>';
			if($row_vypis['foto_var'])
			{
			     echo '<a href="/produkty/'.$row_vypis['str'].'"><img src="/fotky/produkty/male/'.$row_vypis['foto_var'].'" style="width: 32px;"></a>';
			}
			elseif($row_vypis['foto'])
			{
				echo '<a href="/produkty/'.$row_vypis['str'].'"><img src="/fotky/produkty/male/'.$row_vypis['foto'].'" style="width: 32px;"></a>';
			}
			echo '</td>
			<td>'.$row_vypis['nazev'].' - '.$row_vypis['nazev_var'].'</td>
			<td>'.$row_vypis['kat_cislo_var'].'</td>
			<td>'.$row_vypis['ean_var'].'</td>
			<td>'.$row_vypis['ks_skladem'].'</td>
			<td>';
			// rezervace = zboží v košíku
			$data_kosik = Db::queryRow('SELECT SUM(pocet) AS POCET_CELKEM FROM kosik WHERE id_produkt=? AND id_var=? ', array($row_vypis['IDP'],$row_vypis['id']));
			echo intval($data_kosik['POCET_CELKEM']);
			echo '</td>
			<td>'.date('d.m.Y',$row_vypis['datum']).'</td>
			<td>';
			if($row_vypis['aktivni_var']==1){echo '<span class="r">'.$stav_adm_arr[$row_vypis['aktivni_var']].'</span>';}
			else{echo '<span class="b">'.$stav_adm_arr[$row_vypis['aktivni_var']].'</span>';}
			echo '</td>
			<td>&nbsp;<a href="./index.php?p=produkty&action=update&id='.$row_vypis['IDP'].'">upravit</a></td>
			</tr>';
		}
	}
	
	
	echo '</table>';
	


?>
