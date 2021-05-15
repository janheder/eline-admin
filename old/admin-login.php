<?php 
// admin
Admin::adminLogin(5);


// smazat heslo
if($_GET['action']=='delete')
{
		if($_GET['id'])
		{
			$data_update = array('heslo' => '');
			$where_update = array('id' => $_GET['id']);
			$query = Db::update('admin_log_prihlaseni', $data_update, $where_update);
			if($query)
			{

				echo '<div class="alert-success">Smazáno!<br>Heslo bylo smazáno</div>';
			}
		}
		else
		{
			echo '<div class="alert-warning">Chyba!<br>Chybí ID záznamu</div>';
		}

}

$limit = intval($_GET['limit']);
if(!$limit){$limit = 0;}
	
	
$pocet = Db::queryAffected('SELECT id FROM admin_log_prihlaseni WHERE typ=1', array()); 

	if(__STRANKOVANI_ADMIN__ < $pocet)
		{ // pokud je méně výsledků než je výpis na stránku tak stránkování nezobrazujeme
			echo  '<div class="strankovani">';

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



echo '<table border="0" class="ta" cellspacing="0" cellpadding="6">
<tr class="tr-header">
<td>Uživatelské jméno</td>
<td>Heslo</td>
<td>Úspěšne přihlášen</td>
<td>IP</td>
<td>Země</td>
<td>Otisk prohlížeče</td>
<td>Datum přihlášení</td>
<td>Datum odhlášení</td>
<td>Platnost sešny</td>
<td style="width: 80px;">Akce</td>
</tr>';



$data = Db::queryAll('SELECT * FROM admin_log_prihlaseni WHERE typ=? ORDER BY id DESC LIMIT '.$limit.','.__STRANKOVANI_ADMIN__.' ', array(1));
if($data !== FALSE) 
{
	foreach ($data as $row) 
	{
		
		echo '<tr '.__TR_BG__.'>
		<td>'.$row['uz_jm'].'</td>
		<td>'.$row['heslo'].'</td>
		<td>';
		if($row['zalogovan']==1){echo 'ANO';}
		elseif($row['zalogovan']==2){echo 'Chybný PIN';}
		elseif($row['zalogovan']==3){echo 'Nesouhlasí otisk';}
		else{echo 'NE';}
		echo '</td>
		<td><a href="https://www.infosniper.net/index.php?ip_address='.$row['ip'].'&k=&lang=1" target="_blank">'.$row['ip'].'</a></td>
		<td>'.$row['zeme'].'</td>
		<td>'.$row['browser'].'</td>
		<td>'.date('d.m.Y',$row['datum']).'</td>
		<td>';
		if($row['sess_id'])
		{
			$data_p = Db::queryRow('SELECT datum FROM admin_log_prihlaseni WHERE typ=? AND sess_id=?', array(2,$row['sess_id']));
			if($data_p !== FALSE) 
		    {
			  echo date("d.m.Y H:i:s",$data_p['datum']);
			}
		}
		echo '</td>
		<td>';
		if($row['sess_id'])
		{ 
			echo Admin::platnostSesny($row['sess_id']);
		}
		echo '</td>';
		echo '
		<td>
		<a href="./index.php?p=blokovani-ip&action=insert&ip='.$row['ip'].'">blokovat IP</a>';
		if($row['heslo'])
		{
		echo '<br>
		<a href="./index.php?p=admin&pp=admin-login&action=delete&id='.$row['id'].'" '.__JS_CONFIRM__.'>smazat heslo</a>';
	    }
		echo '</td>
		</tr>';
	
    }
}


echo '</table>';

 

?>

