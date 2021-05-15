<?php

if(!$_GET['p'])
{
$_GET['p'] = 'objednavky'; 
}
$p = sanitize($_GET['p']);
if($_GET['pp'])
{
$pp = sanitize($_GET['pp']);
}
else
{
$pp = '';	
}

define("__TR_BG__","onmouseover=\"this.style.background='#f2f2f2'\" onmouseout=\"this.style.background='#ffffff'\"");
define("__JS_CONFIRM__","onClick=\"if(confirm('Opravdu chcete nenávratně smazat tento záznam?'))return true;else return false;\"");
define("__SMS_AFF__",28721); // SMS aff ID


/*
$menu_admin = array
	(
	'produkty'=>array('produkty'=>'Produkty','vyrobci'=>'Výrobci','dostupnost'=>'Dostupnost','sablony-tech-par'=>'Šablony technických parametrů','slevy'=>'Slevy','sazby-dph'=>'Sazby DPH'),
	'kategorie'=>'Kategorie',
	'objednavky'=>array('objednavky'=>'Objednávky','stavy-objednavek'=>'Stavy objednávek','faktury'=>'Faktury','slevove-kody'=>'Slevové kódy'),
	'zakaznici'=>array('zakaznici'=>'Zákazníci','skupiny-zakazniku'=>'Skupiny zákazníků'),
	'staticke-stranky'=>'Statické stránky',
	'fotogalerie'=>'Fotogalerie',
	'aktuality'=>'Aktuality',

	'doprava'=>'Doprava',
	'platba'=>'Platba',

	'statistiky'=>array('statistiky'=>'Statistiky objednávky','statistiky-obecne'=>'Statistiky obecné'),
	
	'obecne-nastaveni'=>array('obecne-nastaveni'=>'Obecné nastavení',
	'obecne-nastaveni-google'=>'Google',
	'obecne-nastaveni-seznam'=>'Seznam',
	'obecne-nastaveni-slevove-kody'=>'Slevové kódy',
	'obecne-nastaveni-zasilkovna'=>'Zásilkovna',
	'obecne-nastaveni-fakturoid'=>'Fakturoid',
	'obecne-nastaveni-balikobot'=>'Balíkobot',
	'obecne-nastaveni-karta-fio'=>'Platba kartou FIO',
	'obecne-nastaveni-karta-csob'=>'Platba kartou ČSOB',
	'obecne-nastaveni-karta-gpwebpay'=>'Platba kartou GP WebPay'),
	
	'blokovani-ip'=>'Blokování IP',
	'reklamni-okno'=>'Reklamní okno',
	'bannery'=>'Bannery úvodka',
	'presmerovani'=>'Přesměrování',
	'neuspesne-prihlaseni'=>'Neúspěšné přihlášení',
	'log-udalosti'=>'Log událostí',
	'admin'=>array('admin'=>'Admin','admin-login'=>'Admin login'),
	);*/

$menu_admin = array
	(
	'produkty'=>array('produkty'=>'Produkty',
	'vyrobci'=>'Výrobci',
	'dostupnost'=>'Dostupnost',
	'sablony-tech-par'=>'Šablony technických parametrů',
	'slevy'=>'Slevy',
	'sazby-dph'=>'Sazby DPH',
	'produkty-sklad'=>'Sklad',
	'produkty-import-xml'=>'Import produktů z XML'
	),
	
	'kategorie'=>'Kategorie',
	
	'objednavky'=>array(
	'objednavky'=>'Objednávky',
	'stavy-objednavek'=>'Stavy objednávek',
	'faktury'=>'Faktury',
	'slevove-kody'=>'Slevové kódy',
	'sms-sablony'=>'SMS šablony',
	'odeslane-sms'=>'Odeslané SMS'
	),
	
	'zakaznici'=>array('zakaznici'=>'Zákazníci',
	'skupiny-zakazniku'=>'Skupiny zákazníků'),
	'staticke-stranky'=>'Statické stránky',
	'fotogalerie'=>'Fotogalerie',
	'aktuality'=>'Aktuality',

	'doprava'=>'Doprava',
	'platba'=>'Platba',

	'statistiky'=>array('statistiky'=>'Statistiky objednávky',
	'statistiky-obecne'=>'Statistiky obecné',
	'statistiky-grafy'=>'Statistiky grafy',
	'statistiky-nejprodavanejsi'=>'Statistiky nejprodávanější produkty',
	),
	
	'obecne-nastaveni'=>array(
	'obecne-nastaveni'=>'Obecné nastavení',
	'obecne-nastaveni-google'=>'Google',
	'obecne-nastaveni-seznam'=>'Seznam',
	'obecne-nastaveni-slevove-kody'=>'Slevové kódy',
	'obecne-nastaveni-fakturoid'=>'Fakturoid',
	'obecne-nastaveni-karta-fio'=>'Platba kartou FIO',
	'obecne-nastaveni-karta-csob'=>'Platba kartou ČSOB',
	'obecne-nastaveni-karta-gpwebpay'=>'Platba kartou GP WebPay',
	'obecne-nastaveni-sms'=>'SMS'
	),
	
	'blokovani-ip'=>'Blokování IP',
	'reklamni-okno'=>'Reklamní okno',
	'bannery'=>'Bannery úvodka',
	'presmerovani'=>'Přesměrování',
	'neuspesne-prihlaseni'=>'Neúspěšné přihlášení',
	'log-udalosti'=>'Log událostí',
	
	'admin'=>array('admin'=>'Admin',
	'admin-login'=>'Admin login'),
	'xml-feedy'=>'XML feedy',
	'napoveda'=>'Nápověda'
	);
	
$prava_adm_arr = array(3=>'3 - host',4=>'4 - admin',5=>'5 - superadmin');
$stav_adm_arr = array(1=>'aktivní',0=>'neaktivní');
$stav_adm_sluzby_arr = array(1=>'ANO',0=>'NE');
$karta_adm_typ_arr = array(0=>'Žádná',1=>'FIO',2=>'ČSOB',3=>'GP WebPay');
$stranky_adm_typ_arr = array(1=>'Je v menu',0=>'Není v menu');
$staty_arr = array(1=>'Česká republika',2=>'Slovensko');
$staty_iso_arr = array(1=>'CZ',2=>'SK');
$ceny_arr = array('A'=>'A','B'=>'B','C'=>'C','D'=>'D');
$typ_sl_kod_arr = array(1=>'Částka',2=>'Procento');
$kar_vnor_arr = array(1=>'První',2=>'Druhá',3=>'Třetí');
$stavy_fak_arr = array(0=>'Nezaplaceno',1=>'Částečně zaplaceno',2=>'Zaplaceno',3=>'Stornováno',);
$doba_platnosti_arr = array(604800=>'Týden',2592000=>'Měsíc',15552000=>'Pů roku',31104000=>'Rok');
$doprava_zdarma_typ_arr = array(0=>'Pro všechny produkty v košíku pokud alespoň jeden má příznak Doprava Zdarma',1=>'Pouze pokud jsou v košíku jen produkty s příznakem Doprava zdarma');

$_SESSION['stav_adm_arr'] = $stav_adm_arr;
$_SESSION['stav_adm_sluzby_arr'] = $stav_adm_sluzby_arr;
$_SESSION['stranky_adm_typ_arr'] = $stranky_adm_typ_arr;
$_SESSION['staty_arr'] = $staty_arr;
$_SESSION['typ_sl_kod_arr'] = $typ_sl_kod_arr;
$_SESSION['stavy_fak_arr'] = $stavy_fak_arr;

function odkazy($p,$menu)
{
  echo "<ul>";
  foreach ($menu as $key => $value)
  {
	  if(is_array($value))
	  {		

			$px=0;
		    foreach ($value as $sub_key => $sub_value)
			{

			  if($px==0)
			   {
				   $key_sub = $sub_key;
				   $value_sub = $sub_value;
			   }
			  $px++;
			}
			
			if($key==$p)
		    {
			  define("__SUB_EX__",1);
			  define("__NADPIS__",$value_sub);
		      echo "<li class=\"aktiv\"><a href=\"./index.php?p=".$key_sub."\" title=\"".$value_sub."\" >".$value_sub."</a></li>";
		    }
		    else
		    {
		      echo "<li><a href=\"./index.php?p=".$key_sub."\" title=\"".$value_sub."\" >".$value_sub."</a></li>";
		    }
			
			
	  }
	  else
	  {
		    if($key==$p)
		    {   
			  define("__NADPIS__",$value);
		      echo "<li class=\"aktiv\"><a href=\"./index.php?p=".$key."\" title=\"".$value."\" >".$value."</a></li>";
		    }
		    else
		    {
		      echo "<li><a href=\"./index.php?p=".$key."\" title=\"".$value."\" >".$value."</a></li>";
		    }
	  }
	 
    
  }
 echo "</ul>"; 

}


function menu_top($p,$menu)
{
  foreach ($menu as $key => $value)
  {
	  
	  if(is_array($value))
	  {	
	     $pm_arr[$key] = $key;  
	  }
	  
  }

  foreach ($menu as $key => $value)
  {
	 if(is_array($value))
	  {	
		  
		  
	     if($p==$pm_arr[$key])
	     {
			
	        foreach ($value as $sub_key => $sub_value)
				{
	
				    if($sub_key==$_GET['pp'])
				    {
					  define("__PODNADPIS__",$sub_value);
				      echo "<div class=\"aktiv\"><a href=\"./index.php?p=".$p."&pp=".$sub_key."\" title=\"".$sub_value."\" >".$sub_value."</a></div>";
				    }
				    else
				    {
				      echo "<div><a href=\"./index.php?p=".$p."&pp=".$sub_key."\" title=\"".$sub_value."\" >".$sub_value."</a></div>";
				    }
				  
				}
				
				
	     } 
	  }
  }
  
  
	
}


function vyhledavace($vyhl,$od,$do,$stav)
{
	if(!$stav)
	{
		$stav_sql = "";
	}
	else
	{ 
		$sql_stav = " AND id_stav='".intval($stav)."' ";
	}
	
	$cena = 0;
	$castka_celkem = Db::queryRow('SELECT sum(cena_celkem_s_dph) AS CENA_CELKEM, count(id) AS POCET  
	FROM objednavky WHERE datum>='.$od.' AND datum<='.$do.' AND referer LIKE "%'.$vyhl.'%" '.$sql_stav, array());
	

	$arr = array();
	$arr['pocet'] = $castka_celkem['POCET'];
	$arr['cena'] = $castka_celkem['CENA_CELKEM'];
	

	return $arr;
			
	
}



function foto_produkt($filename,$size_m,$size_v,$id,$nazev_produktu)
{


	//$logo = ImageCreateFromPNG($pre."/img/vodoznak.png");
	$fx = 0;

 
	foreach($_FILES[$filename]['name'] as $foto_val)
	{

	  // upload + zmenseni fotky
	    if($_FILES[$filename]['size'][$fx])
	    {
			
			$obrazek_name = $_FILES[$filename]["name"][$fx] ;
			$obrazek_type = $_FILES[$filename]["type"][$fx];
			$error = $_FILES[$filename]["error"][$fx] ;
			$obrazek = $_FILES[$filename]["tmp_name"][$fx];
			$velikost = getimagesize($obrazek);
			$s=$velikost[0];
			$v=$velikost[1];
			$pomer = $s/$v;
			// 500, 1200
			$vyska_n = $size_m; // maly
			$sirka_n = round(($pomer*$vyska_n), 0);
			
			$vyska_v = $size_v; // velky
			$sirka_v = round(($pomer*$vyska_v), 0);
			$foto_popis_db = $foto_popis[$fx];
			
			

		
		  if ($error!=0) {die("<br /><span class=\"r\">".$error."</span><br />");}	
		
		  $pripona = array_reverse(explode(".",$obrazek_name));
		  $pripona = $pripona[0];
		  $bez_pripony = substr($obrazek_name,0,(strlen($obrazek_name) - strlen($pripona) - 1));
		
		  $fotoname = StringUtils::removeAccents($nazev_produktu)."_".mt_rand(1, 9999).".".$pripona;
		  
		  // var_dump($obrazek);
		  // rozdeleni na JPG,  GIF a PNG
			if($obrazek_type=="image/jpeg" || $obrazek_type=="image/pjpeg")
			{
			
			     // male
				$in_m = ImageCreateFromJPEG($obrazek);
				$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_n - 250),($vyska_n - 80),0,0,250,70);
				
				ImageJpeg($out_m,"../fotky/produkty/male/".$fotoname,85);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
				 // velke
				$in_m = ImageCreateFromJPEG($obrazek);
				$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_v - 250),($vyska_v - 80),0,0,250,70);
				
				ImageJpeg($out_m,"../fotky/produkty/velke/".$fotoname,85);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
			}
			elseif($obrazek_type=="image/gif")
			{
				// male
				$in_m = ImageCreateFromgif($obrazek);
				$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_v - 250),($vyska_v - 80),0,0,250,70);
				
				Imagegif($out_m,"../fotky/produkty/male/".$fotoname);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
				 // velke
				$in_m = ImageCreateFromgif($obrazek);
				$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_v - 250),($vyska_v - 80),0,0,250,70);
				
				Imagegif($out_m,"../fotky/produkty/velke/".$fotoname);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
				
			}
			elseif($obrazek_type=="image/png" || $obrazek_type=="image/x-png")
			{
			     // male
				$in_m = ImageCreateFrompng($obrazek);
				$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
				
				imagecolortransparent($out_m, imagecolorallocatealpha($out_m, 255, 255, 255, 127));
			    imagealphablending($out_m, false);
			    imagesavealpha($out_m, true);
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_v - 250),($vyska_v - 80),0,0,250,70);
				
				Imagepng($out_m,"../fotky/produkty/male/".$fotoname,0);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
				
				  // velke
				$in_m = ImageCreateFrompng($obrazek);
				$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_v - 250),($vyska_v - 80),0,0,250,70);
				
				Imagepng($out_m,"../fotky/produkty/velke/".$fotoname,0);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
			
			}
			elseif($obrazek_type=="image/webp")
			{
			
			     // male
				$in_m = imagecreatefromwebp($obrazek);
				$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
				
				imagewebp($out_m,"../fotky/produkty/male/".$fotoname,85);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
				 // velke
				$in_m = imagecreatefromwebp($obrazek);
				$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
				
				imagewebp($out_m,"../fotky/produkty/velke/".$fotoname,85);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
			}
			else
			{
			die("Nepodporovany typ obrazku - ".$obrazek_type);
			}
			
			
			
			    $data_insert = array(
								'id_produkt' => $id,
								'foto' => $fotoname
							     );
				
				if($fx==0)
				{
					$data_insert['typ'] = 1; // první foto je hlavní
				}			    

				$query_insert = Db::insert('produkty_foto', $data_insert);
				
				
				$data_update = array('razeni' => $query_insert );
				$where_update = array('id' => $query_insert);
				$query = Db::update('produkty_foto', $data_update, $where_update);
			
			
		}
	    
		

		$fx++;
	
	}
	
}


function prilohy_produkt($filename,$id)
{

	$fx = 0;
	$zakazane_prilohy = array('js','php','php3','php4','php5','php6','php7','php8');
 
	foreach($_FILES[$filename]['name'] as $file_val)
	{

	  // upload 
	    if($_FILES[$filename]['size'][$fx])
	    {
			
			$priloha_name = StringUtils::removeAccents($_FILES[$filename]["name"][$fx]) ;
			$priloha_type = $_FILES[$filename]["type"][$fx];
			$error = $_FILES[$filename]["error"][$fx] ;
			$priloha = $_FILES[$filename]["tmp_name"][$fx];
			
			
		  if ($error!=0) {die("<br /><span class=\"r\">".$error."</span><br />");}	
		
		   $pripona = array_reverse(explode(".",$priloha_name));
		   $pripona = $pripona[0];
		   $bez_pripony = substr($priloha_name,0,(strlen($priloha_name) - strlen($pripona) - 1));
		
		   $priloha_n = $bez_pripony."_".rand(1, 9999).".".$pripona;
		   
		   if(in_array($pripona,$zakazane_prilohy))
		   {
			   die("<br /><span style=\"color: red;\">Nepovolený formát přílohy.</span><br />");
		   }
		   else
		   {
		     
		     move_uploaded_file($priloha, '../prilohy/'.$priloha_n);
		     
		     $data_insert = array(
							'id_produkt' => $id,
							'nazev' => '',
							'priloha' => $priloha_n
							 );

			$query_insert = Db::insert('produkty_prilohy', $data_insert);
			
			//řazení
			$data_update = array('razeni' => $query_insert);
			$where_update = array('id' => $query_insert);
			$query = Db::update('produkty_prilohy', $data_update, $where_update);
			
		   }
		  
		}
		
		
		
		$fx++;
	}

}

function foto_galerie($filename,$size_m,$size_v,$id)
{

	
	//$logo = ImageCreateFromPNG($pre."/img/vodoznak.png");
	$fx = 0;

 
	foreach($_FILES[$filename]['name'] as $foto_val)
	{

	  // upload + zmenseni fotky
	    if($_FILES[$filename]['size'][$fx])
	    {
			
			$obrazek_name = StringUtils::removeAccents($_FILES[$filename]["name"][$fx]) ;
			$obrazek_type = $_FILES[$filename]["type"][$fx];
			$error = $_FILES[$filename]["error"][$fx] ;
			$obrazek = $_FILES[$filename]["tmp_name"][$fx];
			$velikost = getimagesize($obrazek);
			$s=$velikost[0];
			$v=$velikost[1];
			$pomer = $s/$v;
			// 476, 640
			$vyska_n = $size_m; // maly
			$sirka_n = round(($pomer*$vyska_n), 0);
			
			$vyska_v = $size_v; // velky
			$sirka_v = round(($pomer*$vyska_v), 0);

		
		  if ($error!=0) {die("<br /><span class=\"r\">".$error."</span><br />");}	
		
		  $pripona = array_reverse(explode(".",$obrazek_name));
		  $pripona = $pripona[0];
		  $bez_pripony = substr($obrazek_name,0,(strlen($obrazek_name) - strlen($pripona) - 1));
		
		  $fotoname = $bez_pripony."_".rand(1, 9999).".".$pripona;
		  
		  // var_dump($obrazek);
		  // rozdeleni na JPG,  GIF a PNG
			if($obrazek_type=="image/jpeg" || $obrazek_type=="image/pjpeg")
			{
			
			     // male
				$in_m = ImageCreateFromJPEG($obrazek);
				$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_n - 250),($vyska_n - 80),0,0,250,70);
				
				ImageJpeg($out_m,"../fotky/galerie/male/".$fotoname,85);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
				 // velke
				$in_m = ImageCreateFromJPEG($obrazek);
				$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_v - 250),($vyska_v - 80),0,0,250,70);
				
				ImageJpeg($out_m,"../fotky/galerie/velke/".$fotoname,85);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
			}
			elseif($obrazek_type=="image/gif")
			{
				// male
				$in_m = ImageCreateFromgif($obrazek);
				$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_v - 250),($vyska_v - 80),0,0,250,70);
				
				Imagegif($out_m,"../fotky/galerie/male/".$fotoname);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
				 // velke
				$in_m = ImageCreateFromgif($obrazek);
				$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_v - 250),($vyska_v - 80),0,0,250,70);
				
				Imagegif($out_m,"../fotky/galerie/velke/".$fotoname);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
				
			}
			elseif($obrazek_type=="image/png" || $obrazek_type=="image/x-png")
			{
			     // male
				$in_m = ImageCreateFrompng($obrazek);
				$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
				
				imagecolortransparent($out_m, imagecolorallocatealpha($out_m, 255, 255, 255, 127));
			    imagealphablending($out_m, false);
			    imagesavealpha($out_m, true);
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_v - 250),($vyska_v - 80),0,0,250,70);
				
				Imagepng($out_m,"../fotky/galerie/male/".$fotoname,0);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
				
				  // velke
				$in_m = ImageCreateFrompng($obrazek);
				$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_v - 250),($vyska_v - 80),0,0,250,70);
				
				Imagepng($out_m,"../fotky/galerie/velke/".$fotoname,0);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
			
			}
			elseif($obrazek_type=="image/webp")
			{
			
			     // male
				$in_m = imagecreatefromwebp($obrazek);
				$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
				
				imagewebp($out_m,"../fotky/galerie/male/".$fotoname,85);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
				 // velke
				$in_m = imagecreatefromwebp($obrazek);
				$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
				
				imagewebp($out_m,"../fotky/galerie/velke/".$fotoname,85);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
			}
			else
			{
			die("Nepodporovany typ obrazku - ".$obrazek_type);
			}
			
			
			
			    $data_insert = array(
								'id_fotogalerie' => $id,
								'foto' => $fotoname,
								'aktivni' => 1,
							    'datum' => time()
							     );

				$query_insert = Db::insert('fotogalerie_fotky', $data_insert);
				
				
				$data_update = array('razeni' => $query_insert );
				$where_update = array('id' => $query_insert);
				$query = Db::update('fotogalerie_fotky', $data_update, $where_update);
			
			
		}
	    
		

		$fx++;
	
	}
	
}





function foto_aktuality($filename,$size_m,$size_v)
{
	
// upload + zmenseni fotky
$obrazek_name = StringUtils::removeAccents($_FILES[$filename]["name"]) ;
$obrazek_type = $_FILES[$filename]["type"];
$error = $_FILES[$filename]["error"] ;
$obrazek = $_FILES[$filename]["tmp_name"];
$velikost = getimagesize($obrazek);
$s=$velikost[0];
$v=$velikost[1];

$pomer = $v/$s;

$sirka_n = $size_m; // maly
$vyska_n = round(($pomer*$sirka_n), 0);


$sirka_v = $size_v; // velky
$vyska_v = round(($pomer*$sirka_v), 0);
 
if ($error!=0) {die("<br /><span class=\"r\">".$error."</span><br />");}	

  $pripona = array_reverse(explode(".",$obrazek_name));
  $pripona = $pripona[0];
  $bez_pripony = substr($obrazek_name,0,(strlen($obrazek_name) - strlen($pripona) - 1));

  $fotoname = $bez_pripony."_".time().".".$pripona;


// rozdeleni na JPG,  GIF a PNG
if($obrazek_type=="image/jpeg" || $obrazek_type=="image/pjpeg")
{

     // male
	$in_m = ImageCreateFromJPEG($obrazek);
	$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
	
	ImageJpeg($out_m,"../fotky/aktuality/male/".$fotoname,85);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
	 // velke
	$in_m = ImageCreateFromJPEG($obrazek);
	$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
	
	ImageJpeg($out_m,"../fotky/aktuality/velke/".$fotoname,85);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
}
elseif($obrazek_type=="image/gif")
{
	     // male
	$in_m = ImageCreateFromgif($obrazek);
	$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
	
	Imagegif($out_m,"../fotky/aktuality/male/".$fotoname);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
	 // velke
	$in_m = ImageCreateFromgif($obrazek);
	$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
	
	Imagegif($out_m,"../fotky/aktuality/velke/".$fotoname);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
	
}
elseif($obrazek_type=="image/png" || $obrazek_type=="image/x-png")
{
     // male
	$in_m = ImageCreateFrompng($obrazek);
	$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
	
	imagecolortransparent($out_m, imagecolorallocatealpha($out_m, 255, 255, 255, 127));
    imagealphablending($out_m, false);
    imagesavealpha($out_m, true);
    
    
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
	
	Imagepng($out_m,"../fotky/aktuality/male/".$fotoname,0);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
	
	  // velke
	$in_m = ImageCreateFrompng($obrazek);
	$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
	
	Imagepng($out_m,"../fotky/aktuality/velke/".$fotoname,0);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	

}
elseif($obrazek_type=="image/webp")
{

     // male
	$in_m = imagecreatefromwebp($obrazek);
	$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
	
	imagewebp($out_m,"../fotky/aktuality/male/".$fotoname,85);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
	 // velke
	$in_m = imagecreatefromwebp($obrazek);
	$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
	
	imagewebp($out_m,"../fotky/aktuality/velke/".$fotoname,85);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
}
else
{
die("Nepodporovany typ obrazku - ".$obrazek_type);
}

return $fotoname;
}



function foto_produkt_varianta($filename,$id_arr,$idp,$size_m,$size_v)
{
	
// upload + zmenseni fotky
$obrazek_name = StringUtils::removeAccents($_FILES[$filename]["name"][$id_arr]) ;
$obrazek_type = $_FILES[$filename]["type"][$id_arr];
$error = $_FILES[$filename]["error"][$id_arr] ;
$obrazek = $_FILES[$filename]["tmp_name"][$id_arr];
$velikost = getimagesize($obrazek);
$s=$velikost[0];
$v=$velikost[1];

$pomer = $v/$s;

$sirka_n = $size_m; // maly
$vyska_n = round(($pomer*$sirka_n), 0);


$sirka_v = $size_v; // velky
$vyska_v = round(($pomer*$sirka_v), 0);

if ($error!=0) {die("<br /><span class=\"r\">".$error."</span><br />");}	

  $pripona = array_reverse(explode(".",$obrazek_name));
  $pripona = $pripona[0];
  $bez_pripony = substr($obrazek_name,0,(strlen($obrazek_name) - strlen($pripona) - 1));

  $fotoname = $bez_pripony."_".time().".".$pripona;


// rozdeleni na JPG,  GIF a PNG
if($obrazek_type=="image/jpeg" || $obrazek_type=="image/pjpeg")
{

     // male
	$in_m = ImageCreateFromJPEG($obrazek);
	$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
	
	ImageJpeg($out_m,"../fotky/produkty/male/".$fotoname,85);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
	 // velke
	$in_m = ImageCreateFromJPEG($obrazek);
	$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
	
	ImageJpeg($out_m,"../fotky/produkty/velke/".$fotoname,85);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
}
elseif($obrazek_type=="image/gif")
{
	     // male
	$in_m = ImageCreateFromgif($obrazek);
	$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
	
	Imagegif($out_m,"../fotky/produkty/male/".$fotoname);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
	 // velke
	$in_m = ImageCreateFromgif($obrazek);
	$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
	
	Imagegif($out_m,"../fotky/produkty/velke/".$fotoname);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
	
}
elseif($obrazek_type=="image/png" || $obrazek_type=="image/x-png")
{
     // male
	$in_m = ImageCreateFrompng($obrazek);
	$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
	
	imagecolortransparent($out_m, imagecolorallocatealpha($out_m, 255, 255, 255, 127));
    imagealphablending($out_m, false);
    imagesavealpha($out_m, true);
    
    
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
	
	Imagepng($out_m,"../fotky/produkty/male/".$fotoname,0);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
	
	  // velke
	$in_m = ImageCreateFrompng($obrazek);
	$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
	
	Imagepng($out_m,"../fotky/produkty/velke/".$fotoname,0);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	

}
elseif($obrazek_type=="image/webp")
{

     // male
	$in_m = imagecreatefromwebp($obrazek);
	$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
	
	imagewebp($out_m,"../fotky/produkty/male/".$fotoname,85);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
	 // velke
	$in_m = imagecreatefromwebp($obrazek);
	$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
	
	imagewebp($out_m,"../fotky/produkty/velke/".$fotoname,85);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
}
else
{
die("Nepodporovany typ obrazku - ".$obrazek_type);
}

return $fotoname;
}



function foto_kategorie($filename,$size_m)
{
	
// upload + zmenseni fotky
$obrazek_name = StringUtils::removeAccents($_FILES[$filename]["name"]) ;
$obrazek_type = $_FILES[$filename]["type"];
$error = $_FILES[$filename]["error"] ;
$obrazek = $_FILES[$filename]["tmp_name"];
$velikost = getimagesize($obrazek);
$s=$velikost[0];
$v=$velikost[1];

$pomer = $v/$s;

$sirka_n = $size_m; // maly
$vyska_n = round(($pomer*$sirka_n), 0);


if ($error!=0) {die("<br /><span class=\"r\">".$error."</span><br />");}	

  $pripona = array_reverse(explode(".",$obrazek_name));
  $pripona = $pripona[0];
  $bez_pripony = substr($obrazek_name,0,(strlen($obrazek_name) - strlen($pripona) - 1));

  $fotoname = $bez_pripony."_".time().".".$pripona;


// rozdeleni na JPG,  GIF a PNG
if($obrazek_type=="image/jpeg" || $obrazek_type=="image/pjpeg")
{

     // male
	$in_m = ImageCreateFromJPEG($obrazek);
	$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
	
	ImageJpeg($out_m,"../fotky/kategorie/male/".$fotoname,85);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
	
}
elseif($obrazek_type=="image/gif")
{
	     // male
	$in_m = ImageCreateFromgif($obrazek);
	$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
	
	Imagegif($out_m,"../fotky/kategorie/male/".$fotoname);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
	
	
}
elseif($obrazek_type=="image/png" || $obrazek_type=="image/x-png")
{
     // male
	$in_m = ImageCreateFrompng($obrazek);
	$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
	
	imagecolortransparent($out_m, imagecolorallocatealpha($out_m, 255, 255, 255, 127));
    imagealphablending($out_m, false);
    imagesavealpha($out_m, true);
    
    
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
	
	Imagepng($out_m,"../fotky/kategorie/male/".$fotoname,0);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	

	

}
elseif($obrazek_type=="image/webp")
{

     // male
	$in_m = imagecreatefromwebp($obrazek);
	$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
	
	imagewebp($out_m,"../fotky/kategorie/male/".$fotoname,85);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
	 // velke
	$in_m = imagecreatefromwebp($obrazek);
	$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
	ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
	
	imagewebp($out_m,"../fotky/kategorie/velke/".$fotoname,85);
	ImageDestroy($in_m);
	ImageDestroy($out_m);
	
}
else
{
die("Nepodporovany typ obrazku - ".$obrazek_type);
}

return $fotoname;
}




function foto_produkt_xml($filename,$size_m,$size_v,$id,$nazev_produktu,$typ)
{

	  // upload + zmenseni fotky
	    if($filename)
	    {
			
			$obrazek = file_get_contents($filename);
			$koncovka = exif_imagetype($filename);
	        if($koncovka == 1){$kon = 'gif';}
	        elseif($koncovka == 2){$kon = 'jpg';}
	        elseif($koncovka == 3){$kon = 'png';}
	        elseif($koncovka == 18){$kon = 'webp';}
	        else
	        {
				echo  "Nepodporovaný formát obrázku: ".$koncovka2." u produktu ".$nazev_produktu."<br>
				<span class=\"r\">Obrázek nebyl uložen !!</span>";
			}
			
 
			$velikost = getimagesize($filename);
			$s=$velikost[0];
			$v=$velikost[1];
			$pomer = $s/$v;
			// 500, 1200
			$vyska_n = $size_m; // maly
			$sirka_n = round(($pomer*$vyska_n), 0);
			
			$vyska_v = $size_v; // velky
			$sirka_v = round(($pomer*$vyska_v), 0);

		    $fotoname = bez_diakritiky($nazev_produktu)."_".mt_rand(1, 9999).".".$kon;
		  
		  // var_dump($obrazek);
		  // rozdeleni na JPG,  GIF a PNG
			if($koncovka==2)
			{
			
			     // male
				$in_m = imagecreatefromstring($obrazek);
				$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_n - 250),($vyska_n - 80),0,0,250,70);
				
				ImageJpeg($out_m,"../fotky/produkty/male/".$fotoname,85);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
				 // velke
				$in_m = imagecreatefromstring($obrazek);
				$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_v - 250),($vyska_v - 80),0,0,250,70);
				
				ImageJpeg($out_m,"../fotky/produkty/velke/".$fotoname,85);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
			}
			elseif($koncovka==1)
			{
				// male
				$in_m = imagecreatefromstring($obrazek);
				$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_v - 250),($vyska_v - 80),0,0,250,70);
				
				Imagegif($out_m,"../fotky/produkty/male/".$fotoname);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
				 // velke
				$in_m = imagecreatefromstring($obrazek);
				$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_v - 250),($vyska_v - 80),0,0,250,70);
				
				Imagegif($out_m,"../fotky/produkty/velke/".$fotoname);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
				
			}
			elseif($koncovka==3)
			{
			     // male
				$in_m = imagecreatefromstring($obrazek);
				$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
				
				imagecolortransparent($out_m, imagecolorallocatealpha($out_m, 255, 255, 255, 127));
			    imagealphablending($out_m, false);
			    imagesavealpha($out_m, true);
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_v - 250),($vyska_v - 80),0,0,250,70);
				
				Imagepng($out_m,"../fotky/produkty/male/".$fotoname,0);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
				
				  // velke
				$in_m = imagecreatefromstring($obrazek);
				$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
				
				//ImageCopy($out_m,$logo,($sirka_v - 250),($vyska_v - 80),0,0,250,70);
				
				Imagepng($out_m,"../fotky/produkty/velke/".$fotoname,0);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
			
			}
			elseif($koncovka==18)
			{
			
			     // male
				$in_m = imagecreatefromstring($obrazek);
				$out_m = ImageCreateTrueColor($sirka_n,$vyska_n); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_n,$vyska_n,$s,$v);
				
				imagewebp($out_m,"../fotky/produkty/male/".$fotoname,85);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
				 // velke
				$in_m = imagecreatefromstring($obrazek);
				$out_m = ImageCreateTrueColor($sirka_v,$vyska_v); 
				ImageCopyResampled($out_m,$in_m,0,0,0,0,$sirka_v,$vyska_v,$s,$v);
				
				imagewebp($out_m,"../fotky/produkty/velke/".$fotoname,85);
				ImageDestroy($in_m);
				ImageDestroy($out_m);
				
			}
			else
			{
			die("Nepodporovany typ obrazku - ".$obrazek_type);
			}
			
			
			
			    $data_insert = array(
								'id_produkt' => $id,
								'foto' => $fotoname,
								'typ' => $typ
							     );
						    

				$query_insert = Db::insert('produkty_foto', $data_insert);
				
				
				$data_update = array('razeni' => $query_insert );
				$where_update = array('id' => $query_insert);
				$query = Db::update('produkty_foto', $data_update, $where_update);
			
			
		}
	    
		

	
	
}



function getAsXMLContent($xmlElement)
{
  $content=$xmlElement->asXML();

  $end=strpos($content,'>');
  if ($end!==false)
  {
    $tag=substr($content, 1, $end-1);

    return str_replace(array('<'.$tag.'>', '</'.$tag.'>'), '', $content);
  }
  else
    return '';
}





function file_upload_max_size() {
  static $max_size = -1;

  if ($max_size < 0) {
    // Start with post_max_size.
    $post_max_size = parse_size(ini_get('post_max_size'));
    if ($post_max_size > 0) {
      $max_size = $post_max_size;
    }

    // If upload_max_size is less, then reduce. Except if upload_max_size is
    // zero, which indicates no limit.
    $upload_max = parse_size(ini_get('upload_max_filesize'));
    if ($upload_max > 0 && $upload_max < $max_size) {
      $max_size = $upload_max;
    }
  }
  return $max_size;
}


function parse_size($size) {
  $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
  $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
  if ($unit) {
    // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
    return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
  }
  else {
    return round($size);
  }
}

?>