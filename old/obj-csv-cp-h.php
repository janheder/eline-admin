<?php
// generování CSV pro českou poštu
define('__WEB_DIR__', '..');
require('../skripty/init.php');
require('./config.php');

Admin::adminLogin(4);


if($_POST['ido'])
{

/* vygenerujeme CSV s nasledujici strukturou
1 - prijmeni jmeno / nazev firmy
2 - NIC
3 - subjekt (P / F) P = firma
4 - obec
5 - ulice + cislo 
6 - NIC
7 - psc
8 - NIC
9 - NIC
10 - NIC
11 - NIC
12 - typ zasilky (RR - doporucene psani, DR - balik do ruky, NP - balik na postu, CE - obchodni balik )
13 - vaha
14 - udana cena
15 - mobil
16 - e-mail
17 - kod sluzby ( 1A - dorucit dopoledne, 1B - dorucit odpoledne, 40 - dorucit firme, 41 - bezdokladova dobirka, 45 - avizo SMS +EMail, 4 - dobirkova poukazka A, 9 - prioritni  )
18 - vyse dobirky
19 - variabilni symbol - cislo obj
20 - stat - Česká republika / Slovensko
*/

		$csv = '';
		$x = 0;
		$data_o = Db::queryAll('SELECT O.id_zakaznik, O.vaha, O.udana_cena, O.cislo_obj, O.cena_celkem_s_dph, O.id_cp_na_postu, O.id_cp_balikovna, P.dobirka,
		Z.jmeno, Z.prijmeni, Z.email, Z.telefon, Z.dodaci_ulice, Z.dodaci_cislo, Z.dodaci_obec, Z.dodaci_psc, Z.dodaci_id_stat, Z.fakturacni_firma, Z.fakturacni_ulice, Z.fakturacni_cislo, 
		Z.fakturacni_obec, Z.fakturacni_psc, Z.fakturacni_id_stat, Z.ic, Z.dic
		FROM objednavky O 
		LEFT JOIN zakaznici Z ON Z.id=O.id_zakaznik
		LEFT JOIN platba P ON P.id=O.platba_id
		WHERE O.id IN('.implode(",",$_POST['ido']).')  ', array());
		$pocet_query = count($data_o);
		if($data_o !== false ) 
		{
		   foreach ($data_o as $row_o) 
		   {
			
				   if($row_o['dobirka']==1)
				   {
						$dobirka = round($row_o['cena_celkem_s_dph']);
				   }
				   else
				   {
						$dobirka = 0;
				   }
				   
				   $cislo_uctu = __CISLO_UCTU__;
				   $banka = __BANKA__; 
				   $hmotnost = round(($row_o['vaha'] / 1000),3); // v kg - převod z g
					
				   if($row_o['vaha'] && $row_o['udana_cena'])	
				   {
				   
					 if($row_o['fakturacni_firma'])
					 {
					 $csv .= '"'.$row_o['fakturacni_firma'].'"';
					 $subjekt = 'P';
					 }
					 else
					 {
					 $csv .= '"'.$row_o['jmeno'].' '.$row_o['prijmeni'].'"';
					 $subjekt = 'F';
					 }
					 
					 // typ zásilky
					 if($row_o['id_cp_na_postu'])
					 {
					     $typ_zasilky = 'NP'; // balík na poštu
					 }
					 elseif($row_o['id_cp_balikovna'])
					 {
					     $typ_zasilky = 'NB'; // balík do balíkovny
					 }
					 else
					 {
						$typ_zasilky = 'DR'; // balík do ruky
					 }
					 
					 
					 $csv .= ';""';
					 $csv .= ';"'.$subjekt.'"';
					 $csv .= ';"'.$row_o['dodaci_obec'].'"';
					 $csv .= ';"'.$row_o['dodaci_ulice'].' '.$row_o['dodaci_cislo'].'"';
					 $csv .= ';""';
					 $csv .= ';"'.$row_o['dodaci_psc'].'"';
					 $csv .= ';""';
					 $csv .= ';""';
					 $csv .= ';""';
					 $csv .= ';""';
					 $csv .= ';"'.$typ_zasilky.'"';
					 $csv .= ';"'.$hmotnost.'"';
					 $csv .= ';"'.$row_o['udana_cena'].'"';
					 $csv .= ';"'.$row_o['telefon'].'"';
					 $csv .= ';"'.$row_o['email'].'"';
					 $csv .= ';"41+45"';
					 $csv .= ';"'.$dobirka.'"';
					 $csv .= ';"'.$row_o['cislo_obj'].'"';
					 $csv .= ';"'.$staty_arr[$row_o['dodaci_id_stat']].'"';
					 
					 if($x<$pocet_query)
					 {
					    $csv .= "\r\n";
					 }
					 

				   
				   
				   }
				   else
				   {
					 echo 'Není zadána hmotnost nebo udaná cena.<br /><a href="./index.php?p=objednavky">zpět</a>';
				   } 
				   
				   
				   $x++;
		  
		   }
		   
		   			header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream; charset=utf-8');
					header('Content-Disposition: attachment; filename='.basename('csv_export_hromadny_cp_'.date('d-m-Y').'.csv'));
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					ob_clean();
					flush();
					echo $csv;
					exit();
		   
		}

 
}
else
{
 echo 'Chybí ID objednávky';
}
?>
