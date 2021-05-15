<?php
// generování XML pro Pohodu
define('__WEB_DIR__', '..');
require('../skripty/init.php');
require('./config.php');

Admin::adminLogin(4);

if($_GET['ido'])
{



$csv = 'version 6;;;;;;;;;;;;;;;;;;;;;;;;';
$csv .= "\r\n";
$csv .= "\r\n";

		$data_o = Db::queryRow('SELECT O.id_zakaznik, O.vaha, O.udana_cena, O.cislo_obj, O.cena_celkem_s_dph, O.id_zasilkovna, O.datum,
		P.dobirka, P.prevodem, P.karta,
		Z.jmeno, Z.prijmeni, Z.email, Z.telefon, 
		Z.dodaci_nazev, Z.dodaci_ulice, Z.dodaci_cislo, Z.dodaci_obec, Z.dodaci_psc, Z.dodaci_id_stat, 
		Z.fakturacni_firma, Z.fakturacni_ulice, Z.fakturacni_cislo, Z.fakturacni_obec, Z.fakturacni_psc, Z.fakturacni_id_stat, Z.ic, Z.dic
		FROM objednavky O 
		LEFT JOIN zakaznici Z ON Z.id=O.id_zakaznik
		LEFT JOIN platba P ON P.id=O.platba_id
		WHERE O.id=?  ', array($_GET['ido']));
		if($data_o !== false ) 
		{
			
		     $hmotnost = round(($data_o['vaha'] / 1000),3); // v kg - převod z g
		     
		     if($data_o['dobirka'])
		     {
			    $dobirka = round($data_o['cena_celkem_s_dph']);
			 }
			 else
			 {
			    $dobirka = '';
			 }

		
			$csv .= ';"'.$data_o['cislo_obj'].'"';
			$csv .= ';"'.$data_o['jmeno'].'"';
			$csv .= ';"'.$data_o['prijmeni'].'"';
			$csv .= ';"'.$data_o['fakturacni_firma'].'"';
			$csv .= ';"'.$data_o['email'].'"';
			$csv .= ';"'.str_replace(' ','',$data_o['telefon']).'"';
			$csv .= ';"'.$dobirka.'"';
			$csv .= ';""'; // CZK nebo EUR
			$csv .= ';"'.round($data_o['udana_cena']).'"';
			$csv .= ';"'.$hmotnost.'"';
			$csv .= ';"'.$data_o['id_zasilkovna'].'"';
			$csv .= ';"'.__URL__.'"';
			$csv .= ';"0"';
			$csv .= ';';
			$csv .= ';"'.$data_o['dodaci_ulice'].'"';
			$csv .= ';"'.$data_o['dodaci_cislo'].'"';
			$csv .= ';"'.$data_o['dodaci_obec'].'"';
			$csv .= ';"'.str_replace(' ','',$data_o['dodaci_psc']).'"';
					

		 	header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream; charset=utf-8');
			header('Content-Disposition: attachment; filename='.basename('csv_export_'.$data_o['cislo_obj'].'.csv'));
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
