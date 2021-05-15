<?php
// detail statistiky objednávek
define('__WEB_DIR__', '..');
require('../skripty/init.php');
require('./config.php');

Admin::adminLogin(5);


?>
<!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" name="viewport">
    <title><?php echo StringUtils::capitalize(__DOMENA__);?> - admin</title>
    <meta content="noindex,nofollow,nosnippet,noarchive" name="robots">
    <link rel="stylesheet" href="./css/admin.css" type="text/css" media="all" />
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
  </head>
<body>
<?php
echo '<div class="obal">';


if($_GET['vyhl'] && $_GET['od'] && $_GET['do'])
{

            if(!$_GET['stav'])
			{
				$stav_sql = "";
			}
			else
			{ 
				$sql_stav = " AND id_stav='".intval($_GET['stav'])."' ";
			}
			
			$x = 1;
			$cena = 0;
			$data_obj = Db::queryAll('SELECT O.id, O.cislo_obj, O.cena_celkem_s_dph, O.id_stav, O.datum, S.nazev 
			FROM objednavky O 
			LEFT JOIN objednavky_stavy S ON S.id=O.id_stav
			WHERE O.datum>='.intval($_GET['od']).' AND O.datum<='.intval($_GET['do']).$stav_sql.' AND O.referer LIKE "%'.addslashes($_GET['vyhl']).'%" ORDER BY O.datum ASC', array());
			
			if($data_obj)
			{
			  
			    foreach ($data_obj as $row_obj) 
				{
				
					echo $x.'.&nbsp;&nbsp;'.date('d.m.Y',$row_obj['datum']).' &nbsp;&nbsp; 
					<b><a href="./index.php?p=objednavky&action=update&id='.$row_obj['id'].'" target="_parent">'.$row_obj['cislo_obj'].'</a></b>&nbsp;&nbsp;&nbsp;
					Celková cena za objednávku: <b>'.$row_obj['cena_celkem_s_dph'].' '.__MENA__.'</b>
					&nbsp;&nbsp;['.$row_obj['nazev'].']
					<br />';
					$cena = $cena + $row_obj['cena_celkem_s_dph'];
					$x++;
				}
			  
			}

			
			echo '<div class="clear"></div>
			<div class="cara"></div>
			<div class="clear"></div>';
			echo '<br /><b>Celkem: '.$cena.' '.__MENA__.'</b>';
}
elseif($_GET['od'] && $_GET['do'])
{
 // ostatni
			if(!$_GET['stav'])
			{
				$stav_sql = "";
			}
			else
			{ 
				$sql_stav = " AND id_stav='".addslashes($_GET['stav'])."' ";
			}
			
			$x = 1;
			$cena = 0;
			$data_obj = Db::queryAll('SELECT O.id, O.cislo_obj, O.cena_celkem_s_dph, O.datum, S.nazev  
			FROM objednavky O 
			LEFT JOIN objednavky_stavy S ON S.id=O.id_stav
			WHERE O.datum>='.intval($_GET['od']).' AND O.datum<='.intval($_GET['do']).$stav_sql.' 
			AND 
			(O.referer NOT LIKE "%seznam.cz%" AND 
			O.referer NOT LIKE "%firmy.cz%" AND 
			O.referer NOT LIKE "%zbozi.cz%" AND 
			O.referer NOT LIKE "%imedia.cz%" AND 
			O.referer NOT LIKE "%google.cz%" AND  
			O.referer NOT LIKE "%google.com%" AND 
			O.referer NOT LIKE "%facebook.com%" AND 
			O.referer NOT LIKE "%heureka.cz%" ) 
			ORDER BY O.datum ASC', array());
			
			if($data_obj)
			{
			  
			    foreach ($data_obj as $row_obj) 
				{
				
					echo $x.'.&nbsp;&nbsp;'.date('d.m.Y',$row_obj['datum']).' &nbsp;&nbsp; 
					<b><a href="./index.php?p=objednavky&action=update&id='.$row_obj['id'].'" target="_parent">'.$row_obj['cislo_obj'].'</a></b>&nbsp;&nbsp;&nbsp;
					Celková cena za objednávku: <b>'.$row_obj['cena_celkem_s_dph'].' '.__MENA__.'</b><br />';
					$cena = $cena + $row_obj['cena_celkem_s_dph'];
					$x++;
				}
			  
			}
			
			echo '<div class="clear"></div>
			<div class="cara"></div>
			<div class="clear"></div>';
			echo '<br /><b>Celkem: '.$cena.' '.__MENA__.'</b>';
			
			
 
}
else
{
echo 'Chybí parametry';
}

	
echo '</div>';
?>
</body>
</html>
