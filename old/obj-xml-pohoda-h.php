<?php
// generování XML pro Pohodu
define('__WEB_DIR__', '..');
require('../skripty/init.php');
require('./config.php');

Admin::adminLogin(4);

if($_POST['ido'])
{

// vygenerovani xml
$xml = "<?xml version=\"1.0\" encoding=\"windows-1250\"?>
<dat:dataPack id=\"".time()."\" ico=\"".__ICO__."\" application=\"xml export orders\" version=\"2.0\" note=\"Import Objednavek\" xmlns:dat=\"http://www.stormware.cz/schema/version_2/data.xsd\" xmlns:ord=\"http://www.stormware.cz/schema/version_2/order.xsd\" xmlns:typ=\"http://www.stormware.cz/schema/version_2/type.xsd\">";

		$data_o = Db::queryAll('SELECT O.id, O.id_zakaznik, O.vaha, O.udana_cena, O.cislo_obj, O.cena_celkem_s_dph, O.id_cp_na_postu, O.id_cp_balikovna, O.datum,
		P.dobirka, P.prevodem, P.karta,
		Z.jmeno, Z.prijmeni, Z.email, Z.telefon, 
		Z.dodaci_nazev, Z.dodaci_ulice, Z.dodaci_cislo, Z.dodaci_obec, Z.dodaci_psc, Z.dodaci_id_stat, 
		Z.fakturacni_firma, Z.fakturacni_ulice, Z.fakturacni_cislo, Z.fakturacni_obec, Z.fakturacni_psc, Z.fakturacni_id_stat, Z.ic, Z.dic
		FROM objednavky O 
		LEFT JOIN zakaznici Z ON Z.id=O.id_zakaznik
		LEFT JOIN platba P ON P.id=O.platba_id
		WHERE O.id IN('.implode(",",$_POST['ido']).')  ', array());
		$pocet_query = count($data_o);
		if($data_o !== false ) 
		{
		  
		   foreach ($data_o as $row_o) 
		   {		
		  
				  $adresa_osobni = $row_o['jmeno']." ".$row_o['prijmeni'].", ".$row_o['dodaci_ulice']." ".$row_o['dodaci_cislo'].", ".$row_o['dodaci_obec'].", ".$row_o['dodaci_psc'];
		
				  // platby k dipsozici: hotove - cash, slozenkou - postal, dobirka - delivery, kreditni kartou - creditcard, zalohova faktura - advance 
				  
				  if($row_o['dobirka']==1)
				  {
					$platba = 'Dobírka';
				  }
				  elseif($row_o['prevodem']==1)
				  {
					$platba = 'Příkazem';
				  }
				  elseif($row_o['karta']==1)
				  {
					$platba = 'Kreditní kartou';
				  }
				  else
				  {
					$platba = 'Hotově';
				  }
				
				
				  $xml .= "<dat:dataPackItem id=\"".$row_o['id']."\" version=\"2.0\">	
						<ord:order version=\"2.0\">			
							<ord:orderHeader>
								<ord:orderType>receivedOrder</ord:orderType>
								<ord:numberOrder>".$row_o['cislo_obj']."</ord:numberOrder>
								<ord:date>".date("Y-m-d",$row_o['datum'])."</ord:date>";
				  $xml .= "<ord:partnerIdentity>
				   <typ:address>";
				  $xml .= "<typ:company><![CDATA[".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$row_o['fakturacni_firma'])."]]></typ:company>";
				  $xml .= "<typ:name><![CDATA[".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$row_o['jmeno']." ".$row_o['prijmeni'])."]]></typ:name>
				  <typ:city>".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$row_o['fakturacni_obec'])."</typ:city>
				  <typ:street>".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$row_o['fakturacni_ulice']." ".$row_o['fakturacni_ulice'])." </typ:street>
				  <typ:zip>".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$row_o['fakturacni_psc'])."</typ:zip>";
				  $xml .= "<typ:ico>".$row_o['ic']."</typ:ico>";
				  $xml .= "<typ:dic>".$row_o['dic']."</typ:dic>";
				
				  $xml .= "<typ:phone>".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$row_o['telefon'])."</typ:phone>
						   <typ:email>".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$row_o['email'])."</typ:email>";
				  $xml .= "</typ:address>
				  
				 <typ:shipToAddress>";
				  $xml .= "<typ:company><![CDATA[".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$row_o['dodaci_nazev'])."]]></typ:company>";	  		
				  $xml .= "<typ:name><![CDATA[".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$row_o['jmeno']." ".$row_o['prijmeni'])."]]></typ:name>
				    <typ:city>".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$row_o['dodaci_obec'])."</typ:city>
				    <typ:street>".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$row_o['dodaci_ulice']." ".$row_o['dodaci_cislo'])." </typ:street>
				    <typ:zip>".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$row_o['dodaci_psc'])."</typ:zip>";	
				
				  $xml .= "</typ:shipToAddress>
					</ord:partnerIdentity>
					<ord:paymentType>
						<typ:ids>".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$platba)."</typ:ids>
					</ord:paymentType>
					<ord:note>".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$row_o['poznamka_zakaznik'])."</ord:note>
					</ord:orderHeader>
					<ord:orderDetail>";
							
							// jednotlive polozky objednavky
							$data_op = Db::queryAll('SELECT O.*, P.jednotka 
							FROM objednavky_polozky O 
							LEFT JOIN produkty P ON P.id=O.id_produkt
							WHERE id_obj=? AND typ=? ORDER BY id', array($row_o['id'],0));
							if($data_op !== false ) 
							{	
								foreach ($data_op as $row_op) 
								{
									  if($row_op['dph']==__DPH__)
									  {
										$dph = 'high';
									  }
									  elseif($row_op['dph']==15)
									  {
										$dph = 'low';
									  }
									  elseif($row_op['dph']==10)
									  {
										$dph = 'third';
									  }
									  else
									  {
										$dph = 'none';
									  }
									  
									  $xml .="<ord:orderItem>
												<ord:text>".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',htmlspecialchars($row_op['polozka'], ENT_QUOTES))."</ord:text>
												<ord:quantity>".$row_op['pocet']."</ord:quantity>
												<ord:unit>".$row_op['jednotka']."</ord:unit>
												<ord:rateVAT>".$dph."</ord:rateVAT>
												<ord:homeCurrency>
													<typ:unitPrice>".$row_op['cena_za_ks']."</typ:unitPrice>
												</ord:homeCurrency>
												<ord:stockItem>
													<typ:stockItem>
														<typ:ids>".$row_op['kat_cislo']."</typ:ids>
													</typ:stockItem>
												</ord:stockItem>
											</ord:orderItem>";
									
								}
							}
		
		
							// slevový kód
							$data_ops = Db::queryRow('SELECT * FROM objednavky_polozky WHERE id_obj=? AND typ=?', array($row_o['id'],3));
							if($data_ops !== FALSE) 
							{	
								$xml .="<ord:orderItem>
									<ord:text>".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$data_ops['polozka'])."</ord:text>
									<ord:quantity>1</ord:quantity>
									<ord:unit>ks</ord:unit>
									<ord:rateVAT>high</ord:rateVAT>
									<ord:homeCurrency>
										<typ:unitPrice>".$data_ops['cena_za_ks']."</typ:unitPrice>
									</ord:homeCurrency>
								</ord:orderItem>";
							
							}
							
							
							
							// doprava 
							$data_opd = Db::queryRow('SELECT * FROM objednavky_polozky WHERE id_obj=? AND typ=?', array($row_o['id'],1));
							if($data_opd !== FALSE) 
							{
				
								$xml .="<ord:orderItem>
									<ord:text>".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$data_opd['polozka'])."</ord:text>
									<ord:quantity>1</ord:quantity>
									<ord:unit>ks</ord:unit>
									<ord:rateVAT>high</ord:rateVAT>
									<ord:homeCurrency>
										<typ:unitPrice>".$data_opd['cena_za_ks']."</typ:unitPrice>
									</ord:homeCurrency>
								</ord:orderItem>";
							}
								
								
								
							// platba 
							$data_opp = Db::queryRow('SELECT * FROM objednavky_polozky WHERE id_obj=? AND typ=?', array($row_o['id'],2));
							if($data_opp !== FALSE) 
							{
				
								$xml .="<ord:orderItem>
									<ord:text>".iconv("UTF-8", "WINDOWS-1250".'//TRANSLIT',$data_opp['polozka'])."</ord:text>
									<ord:quantity>1</ord:quantity>
									<ord:unit>ks</ord:unit>
									<ord:rateVAT>high</ord:rateVAT>
									<ord:homeCurrency>
										<typ:unitPrice>".$data_opp['cena_za_ks']."</typ:unitPrice>
									</ord:homeCurrency>
								</ord:orderItem>";
							}
							
								
						$xml .="</ord:orderDetail>
						</ord:order>
					</dat:dataPackItem>";
			
		}

	}

$xml .= "</dat:dataPack>";

header('Pragma: public');
header('Content-Transfer-Encoding: windows-1250');
header('Content-Type: text/xml;');  
header('Content-Disposition: attachment; filename="xml_export_pohoda_hromadny_'.date('d-m-Y').'.xml');
echo $xml;
exit();		
		   

}
else
{
 echo 'Chybí ID objednávky';
}
?>
