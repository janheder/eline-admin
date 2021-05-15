<?php
error_reporting(E_ERROR | E_PARSE | E_WARNING); 
ini_set('default_charset', 'utf-8');
ini_set('display_errors', true);
ini_set('xdebug.max_nesting_level', 512);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 10240);

define('__WEB_DIR__', '..');
require('../skripty/init.php');
require('./config.php');

Admin::adminLogin(3);

switch ($_GET['typ'])
{

case 'odstranit_prislusenstvi':

if($_POST['idp'] && $_POST['id_pp'])
{

	$data_dp = Db::queryRow('SELECT prislusenstvi_id_arr FROM produkty WHERE id=? ', array($_POST['idp']));
	if($data_dp['prislusenstvi_id_arr'])
	{
		// produkt už má nějaké příslušenství
		$pr_arr = unserialize($data_dp['prislusenstvi_id_arr']);

			if(($key = array_search(strval($_POST['id_pp']), $pr_arr)) !== false) 
			{
				// smažeme 
				unset($pr_arr[$key]);
			}
			
			if(count($pr_arr) > 0)
			{
				$pr_arr_ser = serialize($pr_arr);

				
			}
			else
			{
				$pr_arr_ser = '';
			}
					
	}
	else
	{
		// produkt nemá příslušenství
		$pr_arr_ser = '';
	}

$data_update = array('prislusenstvi_id_arr' => $pr_arr_ser);
$where_update = array('id' => $_POST['idp']);
$query = Db::update('produkty', $data_update, $where_update);

$data_insert = array(
'id_admin' => $_SESSION['admin']['id'],
'uz_jm' => $_SESSION['admin']['uz_jm'],
'udalost' => 'Produkty - odstranění příslušenství u produktu s id '.intval($_POST['idp']),
'url' => sanitize($_SERVER['HTTP_REFERER']),
'ip' => sanitize(getip()),
'datum' => time()
 );
$query_insert = Db::insert('admin_log_udalosti', $data_insert);   
   
   echo $query;
	
}


break;


/***********************************************************************/


case 'pridat_prislusenstvi':


if($_POST['idp'] && $_POST['id_pp'])
{

	$data_dp = Db::queryRow('SELECT prislusenstvi_id_arr FROM produkty WHERE id=? ', array($_POST['idp']));
	if($data_dp['prislusenstvi_id_arr'])
	{
		// produkt už má nějaké příslušenství
		$pr_arr = unserialize($data_dp['prislusenstvi_id_arr']);
		// kontrolujeme jestli nepřidává již přidaný produkt
		if(in_array(strval($_POST['id_pp']),$pr_arr)) 
		{
		   // již je, nic neděláme
		   $pr_arr_ser = serialize($pr_arr);
		}
		else
		{
		  $pr_arr[] = strval($_POST['id_pp']);
		  $pr_arr_ser = serialize($pr_arr);
		}
		
	}
	else
	{
		// produkt nemá příslušenství
		$pr_arr = array();
		$pr_arr[] = strval($_POST['id_pp']);
		$pr_arr_ser = serialize($pr_arr);
	}
	
	$data_update = array('prislusenstvi_id_arr' => $pr_arr_ser);
	$where_update = array('id' => intval($_POST['idp']));
	$query = Db::update('produkty', $data_update, $where_update);

	$data_insert = array(
	'id_admin' => $_SESSION['admin']['id'],
	'uz_jm' => $_SESSION['admin']['uz_jm'],
	'udalost' => 'Produkty - přidání příslušenství u produktu s id '.intval($_POST['idp']),
	'url' => sanitize($_SERVER['HTTP_REFERER']),
	'ip' => sanitize(getip()),
	'datum' => time()
	 );
	$query_insert = Db::insert('admin_log_udalosti', $data_insert);  
	
	echo $query;
}




break;


/***********************************************************************/


case 'xml_heureka':

	// generujeme XML pro Heureku
	$xml_soubor = '../xml/heureka_cz.xml';
	$xml_feed = '<?xml version="1.0" encoding="UTF-8"?><SHOP>';

	$data_pr = Db::queryAll('SELECT V.*, P.nazev, P.popis, P.id as IDP, P.str, P.nazev_heureka, P.category_text_heureka, P.id_kat_arr, D.dph, VYR.vyrobce, DOST.dostupnost_porovnavace 
	FROM produkty_varianty V 
	LEFT JOIN produkty P ON P.id = V.id_produkt
	LEFT JOIN dph D ON D.id = P.id_dph
	LEFT JOIN produkty_vyrobci VYR ON VYR.id = P.id_vyrobce
	LEFT JOIN produkty_dostupnost DOST ON DOST.id = V.id_dostupnost
	WHERE P.aktivni=? AND V.aktivni_var=? ', array(1,1));
	if($data_pr !== false ) 
	{
		   foreach ($data_pr as $row_pr) 
			{
				   $xml_feed .= '<SHOPITEM>';
				   $xml_feed .= '<ITEM_ID>'.$row_pr['IDP'].'_'.$row_pr['id'].'</ITEM_ID>';
				   if($row_pr['nazev_heureka'])
				   {
				     $xml_feed .= '<PRODUCTNAME><![CDATA['.stripslashes($row_pr['nazev_heureka']).' '.stripslashes($row_pr['nazev_var']).']]></PRODUCTNAME>';
				     $xml_feed .= '<PRODUCT><![CDATA['.stripslashes($row_pr['nazev_heureka']).' '.stripslashes($row_pr['nazev_var']).']]></PRODUCT>';
				   }
				   else
				   {
				     $xml_feed .= '<PRODUCTNAME><![CDATA['.stripslashes($row_pr['nazev']).' '.stripslashes($row_pr['nazev_var']).']]></PRODUCTNAME>';
				     $xml_feed .= '<PRODUCT><![CDATA['.stripslashes($row_pr['nazev']).' '.stripslashes($row_pr['nazev_var']).']]></PRODUCT>';
				   }
				    
				   $xml_feed .= '<DESCRIPTION><![CDATA['.stripslashes($row_pr['popis']).']]></DESCRIPTION>';
				   $xml_feed .= '<URL>'.__URL__.'/produkty/'.$row_pr['str'].'</URL>';
				   if($row_pr['foto_var'])
				   {
				     $xml_feed .= '<IMGURL>'.__URL__.'/fotky/produkty/velke/'.$row_pr['foto_var'].'</IMGURL>';
			       }
			       else
			       {
					   $data_f = Db::queryRow('SELECT foto FROM produkty_foto WHERE id_produkt=? AND typ=? ', array($row_pr['IDP'],1));
					   if($data_f !== false)
					   {
					     $xml_feed .= '<IMGURL>'.__URL__.'/fotky/produkty/velke/'.$data_f['foto'].'</IMGURL>';
				       }
				   }
				   // fotky další
				   $data_fd = Db::queryAll('SELECT foto FROM produkty_foto WHERE id_produkt=? AND typ=? ', array($row_pr['IDP'],0));
				   if($data_fd !== false)
				   {
						foreach ($data_fd as $row_fd) 
						{
							$xml_feed .= '<IMGURL_ALTERNATIVE>'.__URL__.'/fotky/produkty/velke/'.$row_fd['foto'].'</IMGURL_ALTERNATIVE>';
						}
				   }
				   
				   // cena s DPH
				   if($row_pr['sleva'] > 0 &&  ($row_pr['sleva_datum_od'] < time() && $row_pr['sleva_datum_do'] > time() ) )
				   {
						$xml_feed .= '<PRICE_VAT>'.round((($row_pr['cena_A'] - ($row_pr['cena_A'] / 100 * $row_pr['sleva'])) * ($row_pr['dph'] / 100 + 1)),2).'</PRICE_VAT>';
				   }
				   else
				   {
						$xml_feed .= '<PRICE_VAT>'.round(($row_pr['cena_A'] * ($row_pr['dph'] / 100 + 1)),2).'</PRICE_VAT>';
				   }	
				   
				   $xml_feed .= '<VAT>'.$row_pr['dph'].'%</VAT>';
				   $xml_feed .= '<MANUFACTURER><![CDATA['.$row_pr['vyrobce'].']]></MANUFACTURER>';
				   
				   // category text
				   if($row_pr['category_text_heureka'])
				   {
						$xml_feed .= '<CATEGORYTEXT>'.$row_pr['category_text_heureka'].'</CATEGORYTEXT>';
				   }
				   else
				   {
						$xml_feed .= '<CATEGORYTEXT>';
						if($row_pr['id_kat_arr'])
						{
							$id_kat = unserialize($row_pr['id_kat_arr']);
							foreach($id_kat as $idk_k => $idk_v)
							{
								$data_k1 = Db::queryRow('SELECT id, vnor, id_nadrazeneho, nazev FROM kategorie WHERE id=? ', array($idk_v));
								$vnor = $data_k1['vnor'];
								$id_nad = $data_k1['id_nadrazeneho'];

								if($vnor==1)
								{
								  $xml_feed .= stripslashes($data_k1['nazev']);  
								}
				
								if($vnor==2 && $predchozi_vnor != $vnor)
								{
									 $data_k2 = Db::queryRow('SELECT id, vnor, id_nadrazeneho, nazev FROM kategorie WHERE id=? ', array($idk_v));
									 $xml_feed .= ' | '.stripslashes($data_k2['nazev']); 
								}
				
								if($vnor==3 && $predchozi_vnor != $vnor)
								{
									 $data_k3 = Db::queryRow('SELECT id, vnor, id_nadrazeneho, nazev FROM kategorie WHERE id=? ', array($idk_v));
									 $xml_feed .= ' | '.stripslashes($data_k3['nazev']);
								}	
								
								$predchozi_vnor = $vnor;

							}
						}
						
						$xml_feed .= '</CATEGORYTEXT>';
				   }
				    
				   if($row_pr['ean_var']) 
				   {
						$xml_feed .= '<EAN>'.$row_pr['ean_var'].'</EAN>';
				   }

				   $xml_feed .= '<PRODUCTNO>'.$row_pr['kat_cislo_var'].'</PRODUCTNO>';
				   // parametry
				   $data_par= Db::queryAll('SELECT nazev,hodnota FROM produkty_tech_par WHERE id_produkt=? ', array($row_pr['IDP']));
				   if($data_par !== false)
				   {	
						foreach ($data_par as $row_par) 
						{
							$xml_feed .= '<PARAM>';
							$xml_feed .= '<PARAM_NAME>'.stripslashes($row_par['nazev']).'</PARAM_NAME>';
							$xml_feed .= '<VAL>'.stripslashes($row_par['hodnota']).'</VAL>';
							$xml_feed .= '</PARAM>';
						}
						
					}
 
				    $xml_feed .= '<DELIVERY_DATE>'.$row_pr['dostupnost_porovnavace'].'</DELIVERY_DATE>';
				    
				    // doprava
				    $data_dop= Db::queryAll('SELECT D.*, DP.dph FROM doprava D 
				    LEFT JOIN dph DP ON DP.id=D.id_dph
				    WHERE D.aktivni=? ', array(1));
				    if($data_dop !== false)
				    {	
						foreach ($data_dop as $row_dop) 
						{
							$xml_feed .= '<DELIVERY>
							<DELIVERY_ID>'.$row_dop['dopravce_heureka'].'</DELIVERY_ID>
							<DELIVERY_PRICE>'.round($row_dop['cena'] * ($row_dop['dph'] / 100 + 1)).'</DELIVERY_PRICE>
							</DELIVERY>';
						}
					 }

				    $xml_feed .= '<ITEMGROUP_ID>P_'.$row_pr['IDP'].'</ITEMGROUP_ID>';
				    $xml_feed .= '</SHOPITEM>';
			}
			
			$xml_feed .= '</SHOP>';
			file_put_contents($xml_soubor, $xml_feed);
			echo 'XML feed vygenerován'; 
			
	}
	else
	{
	    echo 'Chyba při generování XML '.$_POST['t'];
    }
		

break;

/***********************************************************************/

case 'xml_zbozi':

	// generujeme XML pro Zbozi.cz
	$xml_soubor = '../xml/zbozi_cz.xml';
	$xml_feed = '<?xml version="1.0" encoding="utf-8"?><SHOP xmlns="http://www.zbozi.cz/ns/offer/1.0">';

	$data_pr = Db::queryAll('SELECT V.*, P.nazev, P.popis, P.id as IDP, P.str, P.nazev_zbozi, P.product, P.category_text_heureka, P.id_kat_arr, 
	P.id_category_text_zbozi, P.id_category_text_zbozi2, P.productno, P.extramessage, P.extramessage2, P.extramessage3, P.brand, P.vydejnimisto, 
	P.vydejnimisto2, P.vydejnimisto3, P.productline, P.maxcpc, P.maxcpc_search, P.itemgroupid, P.visibility, P.CUSTOM_LABEL_0, P.CUSTOM_LABEL_1, 
	P.CUSTOM_LABEL_2, P.CUSTOM_LABEL_3, P.nazev_zbozi, P.product, P.parovaci_kod, 
	D.dph, VYR.vyrobce, DOST.dostupnost_porovnavace 
	FROM produkty_varianty V 
	LEFT JOIN produkty P ON P.id = V.id_produkt
	LEFT JOIN dph D ON D.id = P.id_dph
	LEFT JOIN produkty_vyrobci VYR ON VYR.id = P.id_vyrobce
	LEFT JOIN produkty_dostupnost DOST ON DOST.id = V.id_dostupnost
	WHERE P.aktivni=? AND V.aktivni_var=? ', array(1,1));
	if($data_pr !== false ) 
	{
		   foreach ($data_pr as $row_pr) 
			{
				   $xml_feed .= '<SHOPITEM>';
				   $xml_feed .= '<ITEM_ID>'.$row_pr['IDP'].'_'.$row_pr['id'].'</ITEM_ID>';
				   if($row_pr['nazev_zbozi'])
				   {
				     $xml_feed .= '<PRODUCTNAME><![CDATA['.stripslashes($row_pr['nazev_heureka']).' '.stripslashes($row_pr['nazev_var']).']]></PRODUCTNAME>';
				   }
				   else
				   {
				     $xml_feed .= '<PRODUCTNAME><![CDATA['.stripslashes($row_pr['nazev']).' '.stripslashes($row_pr['nazev_var']).']]></PRODUCTNAME>';
				   }
				   
				   if($row_pr['product'])
				   {
				     $xml_feed .= '<PRODUCT><![CDATA['.stripslashes($row_pr['nazev_heureka']).' '.stripslashes($row_pr['nazev_var']).']]></PRODUCT>';
				   }
				   else
				   {
				     $xml_feed .= '<PRODUCT><![CDATA['.stripslashes($row_pr['nazev']).' '.stripslashes($row_pr['nazev_var']).']]></PRODUCT>';
				   }
				    
				   $xml_feed .= '<DESCRIPTION><![CDATA['.stripslashes($row_pr['popis']).']]></DESCRIPTION>';
				   $xml_feed .= '<URL>'.__URL__.'/produkty/'.$row_pr['str'].'</URL>';
				   if($row_pr['foto_var'])
				   {
				     $xml_feed .= '<IMGURL>'.__URL__.'/fotky/produkty/velke/'.$row_pr['foto_var'].'</IMGURL>';
			       }
			       else
			       {
					   $data_f = Db::queryRow('SELECT foto FROM produkty_foto WHERE id_produkt=? AND typ=? ', array($row_pr['IDP'],1));
					   if($data_f !== false)
					   {
					     $xml_feed .= '<IMGURL>'.__URL__.'/fotky/produkty/velke/'.$data_f['foto'].'</IMGURL>';
				       }
				   }
				   // fotky další
				   $data_fd = Db::queryAll('SELECT foto FROM produkty_foto WHERE id_produkt=? AND typ=? ', array($row_pr['IDP'],0));
				   if($data_fd !== false)
				   {
						foreach ($data_fd as $row_fd) 
						{
							$xml_feed .= '<IMGURL_ALTERNATIVE>'.__URL__.'/fotky/produkty/velke/'.$row_fd['foto'].'</IMGURL_ALTERNATIVE>';
						}
				   }
				   
				   // cena s DPH
				   if($row_pr['sleva'] > 0 &&  ($row_pr['sleva_datum_od'] < time() && $row_pr['sleva_datum_do'] > time() ) )
				   {
						$xml_feed .= '<PRICE_VAT>'.round((($row_pr['cena_A'] - ($row_pr['cena_A'] / 100 * $row_pr['sleva'])) * ($row_pr['dph'] / 100 + 1)),2).'</PRICE_VAT>';
				   }
				   else
				   {
						$xml_feed .= '<PRICE_VAT>'.round(($row_pr['cena_A'] * ($row_pr['dph'] / 100 + 1)),2).'</PRICE_VAT>';
				   }	
				   
				   //$xml_feed .= '<VAT>'.$row_pr['dph'].'%</VAT>';
				   $xml_feed .= '<MANUFACTURER><![CDATA['.$row_pr['vyrobce'].']]></MANUFACTURER>';
				   
				    
				   if($row_pr['ean_var']) 
				   {
						$xml_feed .= '<EAN>'.$row_pr['ean_var'].'</EAN>';
				   }

				   $xml_feed .= '<PRODUCTNO>'.$row_pr['kat_cislo_var'].'</PRODUCTNO>';
				   // parametry
				   $data_par= Db::queryAll('SELECT nazev,hodnota FROM produkty_tech_par WHERE id_produkt=? ', array($row_pr['IDP']));
				   if($data_par !== false)
				   {	
						foreach ($data_par as $row_par) 
						{
							$xml_feed .= '<PARAM>';
							$xml_feed .= '<PARAM_NAME>'.stripslashes($row_par['nazev']).'</PARAM_NAME>';
							$xml_feed .= '<VAL>'.stripslashes($row_par['hodnota']).'</VAL>';
							$xml_feed .= '</PARAM>';
						}
						
					}
 
				    $xml_feed .= '<DELIVERY_DATE>'.$row_pr['dostupnost_porovnavace'].'</DELIVERY_DATE>';
				    
				    // doprava
				    $data_dop= Db::queryAll('SELECT D.*, DP.dph FROM doprava D 
				    LEFT JOIN dph DP ON DP.id=D.id_dph
				    WHERE D.aktivni=? ', array(1));
				    if($data_dop !== false)
				    {	
						foreach ($data_dop as $row_dop) 
						{
							$xml_feed .= '<DELIVERY>
							<DELIVERY_ID>'.$row_dop['dopravce_heureka'].'</DELIVERY_ID>
							<DELIVERY_PRICE>'.round($row_dop['cena'] * ($row_dop['dph'] / 100 + 1)).'</DELIVERY_PRICE>
							</DELIVERY>';
						}
					 }

				    $xml_feed .= '<ITEMGROUP_ID>P_'.$row_pr['IDP'].'</ITEMGROUP_ID>';
				    
				    // speciální parametry
				    if($row_pr['extramessage'])
					{
						$xml_feed .=  "<EXTRA_MESSAGE>".strip_tags($row_con_rss->extramessage)."</EXTRA_MESSAGE>\n";	
					}
					
					if($row_pr['extramessage2'])
					{
						$xml_feed .=  "<EXTRA_MESSAGE>".strip_tags($row_con_rss->extramessage2)."</EXTRA_MESSAGE>\n";	
					}
					
					if($row_pr['extramessage3'])
					{
						$xml_feed .=  "<EXTRA_MESSAGE>".strip_tags($row_con_rss->extramessage3)."</EXTRA_MESSAGE>\n";	
					}
					
					if($row_pr['brand'])
					{
						$xml_feed .=  "<BRAND><![CDATA[".strip_tags($row_con_rss->brand)."]]></BRAND>\n";
				    }
					
					if($row_pr['vydejnimisto'])
					{
						$xml_feed .=  "<SHOP_DEPOTS>".$row_con_rss->vydejnimisto."</SHOP_DEPOTS>\n";
					}
					if($row_pr['vydejnimisto2'])
					{
						$xml_feed .=  "<SHOP_DEPOTS>".$row_con_rss->vydejnimisto2."</SHOP_DEPOTS>\n";
					}
					if($row_pr['vydejnimisto3'])
					{
						$xml_feed .=  "<SHOP_DEPOTS>".$row_con_rss->vydejnimisto3."</SHOP_DEPOTS>\n";
					}
					
					if($row_pr['productline'])
					{
						$xml_feed .=  "<PRODUCT_LINE>".$row_con_rss->productline."</PRODUCT_LINE>\n";
					}
					
					if($row_pr['maxcpc'])
					{
						$xml_feed .=  "<MAX_CPC>".$row_con_rss->maxcpc."</MAX_CPC>\n";
					}
					
					if($row_pr['maxcpc_search'])
					{
						$xml_feed .=  "<MAX_CPC_SEARCH>".$row_con_rss->maxcpc_search."</MAX_CPC_SEARCH>\n";
					}
					
					if($row_pr['itemgroupid'])
					{
						$xml_feed .=  "<ITEMGROUP_ID>".$row_con_rss->itemgroupid."</ITEMGROUP_ID>\n";
					}
					
					if($row_pr['product'])
					{
						$xml_feed .=  "<PRODUCT>".$row_con_rss->product."</PRODUCT>\n";
					}
					
					if($row_pr['CUSTOM_LABEL_0'])
					{
						$xml_feed .=  "<CUSTOM_LABEL_0>".$row_con_rss->CUSTOM_LABEL_0."</CUSTOM_LABEL_0>\n";
					}
					
					if($row_pr['CUSTOM_LABEL_1'])
					{
						$xml_feed .=  "<CUSTOM_LABEL_1>".$row_con_rss->CUSTOM_LABEL_1."</CUSTOM_LABEL_1>\n";
					}
					
					if($row_pr['CUSTOM_LABEL_2'])
					{
						$xml_feed .=  "<CUSTOM_LABEL_2>".$row_con_rss->CUSTOM_LABEL_2."</CUSTOM_LABEL_2>\n";
					}
					
					if($row_pr['CUSTOM_LABEL_3'])
					{
						$xml_feed .=  "<CUSTOM_LABEL_3>".$row_con_rss->CUSTOM_LABEL_3."</CUSTOM_LABEL_3>\n";
					}
					
					$xml_feed .=  "<ITEM_TYPE>new</ITEM_TYPE>\n";
					$xml_feed .=  "<VISIBILITY>".$row_pr['visibility']."</VISIBILITY>\n";	
					
					if($row_pr['category_text_zbozi'])
					{
						$xml_feed .=  "<CATEGORYTEXT>".$row_con_rss->category_text_zbozi."</CATEGORYTEXT>\n";
					}
					
					if($row_pr['category_text_zbozi2'])
					{
						$xml_feed .=  "<CATEGORYTEXT>".$row_con_rss->category_text_zbozi2."</CATEGORYTEXT>\n";
					}
					
		
				    $xml_feed .= '</SHOPITEM>';
			}
			
			$xml_feed .= '</SHOP>';
			file_put_contents($xml_soubor, $xml_feed);
			echo 'XML feed vygenerován'; 
			
	}
	else
	{
	    echo 'Chyba při generování XML '.$_POST['t'];
    }
		

break;


/***********************************************************************/

case 'xml_google':

	// generujeme XML pro Google
	$xml_soubor = '../xml/google.xml';
	$xml_feed = '<?xml version="1.0" encoding="utf-8"?>
	<feed xmlns="http://www.w3.org/2005/Atom" xmlns:g="http://base.google.com/ns/1.0">
	<title>'.__TITLE__.'</title>
	<link rel="self" href="'.__URL__.'/xml/google.xml"/>
    <updated>'.date('Y').'-'.date('m').'-'.date('d').'T03:00:00</updated>';

	$data_pr = Db::queryAll('SELECT V.*, P.nazev, P.popis, P.id as IDP, P.str, P.id_kat_arr, 
	D.dph, VYR.vyrobce, DOST.dostupnost_porovnavace 
	FROM produkty_varianty V 
	LEFT JOIN produkty P ON P.id = V.id_produkt
	LEFT JOIN dph D ON D.id = P.id_dph
	LEFT JOIN produkty_vyrobci VYR ON VYR.id = P.id_vyrobce
	LEFT JOIN produkty_dostupnost DOST ON DOST.id = V.id_dostupnost
	WHERE P.aktivni=? AND V.aktivni_var=? ', array(1,1));
	if($data_pr !== false ) 
	{
		   foreach ($data_pr as $row_pr) 
			{
				   $xml_feed .= '<entry>';
				   $xml_feed .= '<g:id>'.$row_pr['IDP'].'_'.$row_pr['id'].'</g:id>';
				   $xml_feed .= '<title><![CDATA['.stripslashes($row_pr['nazev']).' '.stripslashes($row_pr['nazev_var']).']]></title>';

				    
				   $xml_feed .= '<description><![CDATA['.stripslashes($row_pr['popis']).']]></description>';
				   $xml_feed .= '<link>'.__URL__.'/produkty/'.$row_pr['str'].'</link>';
				   $xml_feed .= '<g:condition>new</g:condition>';
				   if($row_pr['foto_var'])
				   {
				     $xml_feed .= '<g:image_link>'.__URL__.'/fotky/produkty/velke/'.$row_pr['foto_var'].'</g:image_link>';
			       }
			       else
			       {
					   $data_f = Db::queryRow('SELECT foto FROM produkty_foto WHERE id_produkt=? AND typ=? ', array($row_pr['IDP'],1));
					   if($data_f !== false)
					   {
					     $xml_feed .= '<g:image_link>'.__URL__.'/fotky/produkty/velke/'.$data_f['foto'].'</g:image_link>';
				       }
				   }

				   // cena s DPH
				   if($row_pr['sleva'] > 0 &&  ($row_pr['sleva_datum_od'] < time() && $row_pr['sleva_datum_do'] > time() ) )
				   {
						$xml_feed .= '<g:price>'.round((($row_pr['cena_A'] - ($row_pr['cena_A'] / 100 * $row_pr['sleva'])) * ($row_pr['dph'] / 100 + 1)),2).' CZK</g:price>';
				   }
				   else
				   {
						$xml_feed .= '<g:price>'.round(($row_pr['cena_A'] * ($row_pr['dph'] / 100 + 1)),2).' CZK</g:price>';
				   }	
				   
				   $xml_feed .= '<g:brand><![CDATA['.$row_pr['vyrobce'].']]></g:brand>';
				   
				    
				   if($row_pr['ean_var']) 
				   {
						$xml_feed .= '<g:gtin>'.$row_pr['ean_var'].'</g:gtin>';
				   }

				   $xml_feed .= '<g:mpn>'.$row_pr['kat_cislo_var'].'</g:mpn>';

					
					if($row_pr['dostupnost_porovnavace']==0)
					{
						$xml_feed .= '<g:availability>in stock</g:availability>';
					}
					else
					{
						$utc = time() + ($row_pr['dostupnost_porovnavace'] * 3600 * 24);
						$xml_feed .= '<g:availability>preorder</g:availability>';
						$xml_feed .= '<g:availability_date>'.date('Y-m-d\TH:iZ',$utc).'</g:availability_date>'; // RRRR-MM-DDThh:mmZ
					}

				    $xml_feed .= '</entry>';
			}
			
			$xml_feed .= '</feed>';
			file_put_contents($xml_soubor, $xml_feed);
			echo 'XML feed vygenerován'; 
			
	}
	else
	{
	    echo 'Chyba při generování XML '.$_POST['t'];
    }

break;

/***********************************************************************/


case 'zobrazit_prislusenstvi':


$prislusenstvi = ' ';

if($_POST['idp'])
{
	
	$data_p = Db::queryRow('SELECT prislusenstvi_id_arr FROM produkty WHERE id=? ', array($_POST['idp']));
	if($data_p['prislusenstvi_id_arr'])
	{
	   $prislusenstvi_id_arr = unserialize($data_p['prislusenstvi_id_arr']);
	   
	   $data_pr = Db::queryAll('SELECT P.id, P.nazev, P.kat_cislo, F.foto 
	      FROM produkty P
		  LEFT JOIN produkty_foto F ON F.id_produkt=P.id 
		  WHERE P.id IN('.implode(",",$prislusenstvi_id_arr).') AND F.typ=1 GROUP BY P.id ORDER BY P.id ', array());
			if($data_pr !== false ) 
	        {
			
			   foreach ($data_pr as $row_pr) 
				{
					   $prislusenstvi .= '<div class="box_pr_vypis">
					   <div class="bdv_in"><img src="/fotky/produkty/male/'.$row_pr['foto'].'" style="width: 150px;"></div>
					   <br>
					   <a href="./index.php?p=produkty&action=update&id='.$row_pr['id'].'" target="_blank">'.$row_pr['nazev'].'</a>
					   <br>kat. číslo: '.$row_pr['kat_cislo'].'<br>
					    <a href="javascript:OdstranitPrislusenstvi('.$_POST['idp'].','.$row_pr['id'].');">odstranit z příslušenství</a>
					  </div>';
				}
			}
	}
	
		

}
 
				

echo $prislusenstvi; 


break;


/***********************************************************************/

case 'hlavni_foto_produkt':

 list($id_p,$id_foto) = explode('_',$_POST['idf']);
 
 $data_update = array('typ' => 0);
 $where_update = array('id_produkt' => $id_p);
 $query = Db::update('produkty_foto', $data_update, $where_update);
 
 $data_update2 = array('typ' => 1);
 $where_update2 = array('id' => $id_foto);
 $query2 = Db::update('produkty_foto', $data_update2, $where_update2);
 
 echo $query2;

		
	
	
break;


/***********************************************************************/


case 'kontrola_uz_jm':

 $data = Db::queryAffected('SELECT id FROM zakaznici WHERE uz_jmeno=? ', array($_POST['uname']));
 echo $data;
		
		
 
break;



/***********************************************************************/



case 'kontrola_produkt':

 $data = Db::queryAffected('SELECT id FROM produkty WHERE nazev=? ', array($_POST['uname']));
 echo $data;
		
		
 
break;



/***********************************************************************/


case 'razeni_stranky':

    $position = $_POST['position'];
    $i=1;

    foreach($position as $k=>$v)
    {
        
        $data_update = array('razeni' => $i);
		$where_update = array('id' => $v);
		$query = Db::update('stranky', $data_update, $where_update);
		
        $i++;

    }
    
    echo 'Pořadí změněno';

	
break;

/***********************************************************************/


case 'razeni_kategorie':

    $position = $_POST['position'];
    $i=1;

    foreach($position as $k=>$v)
    {
        
        $data_update = array('razeni' => $i);
		$where_update = array('id' => $v);
		$query = Db::update('kategorie', $data_update, $where_update);
		
        $i++;

    }
    
    echo 'Pořadí změněno';

	
break;


/***********************************************************************/


case 'razeni_doprava':

    $position = $_POST['position'];
    $i=1;

    foreach($position as $k=>$v)
    {
        
        $data_update = array('razeni' => $i);
		$where_update = array('id' => $v);
		$query = Db::update('doprava', $data_update, $where_update);
		
        $i++;

    }
    
    echo 'Pořadí změněno';

	
break;


/***********************************************************************/


case 'razeni_platba':

    $position = $_POST['position'];
    $i=1;

    foreach($position as $k=>$v)
    {
        
        $data_update = array('razeni' => $i);
		$where_update = array('id' => $v);
		$query = Db::update('platba', $data_update, $where_update);
		
        $i++;

    }
    
    echo 'Pořadí změněno';

	
break;


/***********************************************************************/


case 'razeni_stavy_obj':

    $position = $_POST['position'];
    $i=1;

    foreach($position as $k=>$v)
    {
        
        $data_update = array('razeni' => $i);
		$where_update = array('id' => $v);
		$query = Db::update('objednavky_stavy', $data_update, $where_update);
		
        $i++;

    }
    
    echo 'Pořadí změněno';

	
break;



/***********************************************************************/


case 'razeni_sms_sablony':

    $position = $_POST['position'];
    $i=1;

    foreach($position as $k=>$v)
    {
        
        $data_update = array('razeni' => $i);
		$where_update = array('id' => $v);
		$query = Db::update('sms_sablony', $data_update, $where_update);
		
        $i++;

    }
    
    echo 'Pořadí změněno';

	
break;




/***********************************************************************/


case 'sms_text':

	    $data_sms = Db::queryRow('SELECT text FROM sms_sablony WHERE  id=?  ', array($_POST['sms_id']));

		if($data_sms !== false ) 
        {
			$telefonni_cislo = str_replace(' ','',$_POST['telefon']);
			$sms_zprava = $data_sms['text'];
			// nahradime 
			/*
		    [CELKOVA_CASTKA] = celková částka za objednávku
			[CISLO_UCTU] = číslo účtu z obecného nastavení pro daný eshop
			[VARIABILNI_SYMBOL] = var. symbol = číslo objednávky
			[TRASOVANI_ZASILKY] = vygeneruje odkaz na trasování zásilky
			[KONTAKTY_PATA] = kontakty do patičky emailu z obecného nastavení
	         */
	         
	        $data_obj = Db::queryRow('SELECT * FROM objednavky WHERE id = ?', array($_POST['id_obj']));
			if($data_obj['cislo_zasilky'])
			{
			  $trasovani_zasilky = 'Trasování zásilky: https://t.17track.net/cs#nums='.$data_obj['cislo_zasilky'];
			}
			
			$sms_zprava = str_replace('[CELKOVA_CASTKA]',$data_obj['cena_celkem_s_dph'].' '.__MENA__,$sms_zprava);
			$sms_zprava = str_replace('[KONTAKTY_PATA]',$kod_do_emailu.__KONTAKTY_PATA__,$sms_zprava);
			$sms_zprava = str_replace('[VARIABILNI_SYMBOL]',$data_obj['cislo_obj'],$sms_zprava);
			$sms_zprava = str_replace('[CISLO_UCTU]',__CISLO_UCTU__,$sms_zprava);
			$sms_zprava = str_replace('[TRASOVANI_ZASILKY]',$trasovani_zasilky,$sms_zprava);

		 echo $sms_zprava;
		
		}
		 
		
	
break;


/***********************************************************************/


case 'sms_send':

$telefonni_cislo = str_replace(' ','',$_POST['telefon']);
$apixml = new Sms(__SMS_LOGIN__, __SMS_PASSWORD__);
/* Odeslani SMS
*parametry:
*telefonni cislo - povinny
*text zpravy - povinny
*odeslat v - nepovinny , format je YmdHis 20090510122343   viz dokumentace API
*dorucenka - nepovinny, 20 = vyzadnovani dorucenky, 0 = nevyzadovani dorucenky

vraci XML s vysledkem odeslani SMS
*/
$sms_odeslani = $apixml->send_message($telefonni_cislo,$_POST['sms_text'], null,0);

	if($sms_odeslani)
	{
		$xml = simplexml_load_string($sms_odeslani, "SimpleXMLElement", LIBXML_NOCDATA);
		$json = json_encode($xml);
		$array = json_decode($json,TRUE);
		if($array['id'])
		{
		  list($id_stav,$zb) = explode(';',$array['id']);
		}
 
		// kontrola stavového kódu
		if($id_stav==400)
		{
		  echo 'Chyba 400 - SMS zpráva na '.$_POST['telefon'].' nebyla odeslána!';
		  $data_insert_sms = array(
							    'id_zakaznik' => $_POST['id_registrace'],
							    'id_obj' => $_POST['id_obj'],
							    'id_admin' => $_SESSION['admin']['id'],
							    'telefon' => sanitize($telefonni_cislo),
							    'sms' => sanitize($_POST['sms_text']),
							    'stav' => sanitize($sms_odeslani),
							    'datum' => time()
							     );
							     
			 $query_insert_sms = Db::insert('sms_odeslane', $data_insert_sms);
		}
		else
		{
			echo 'V pořádku! SMS byla odeslána na '.sanitize($_POST['telefon']);
			// sms byla odeslana
			// ulozime do databaze odeslanych
			 $data_insert_sms = array(
							    'id_zakaznik' => $_POST['id_registrace'],
							    'id_obj' => $_POST['id_obj'],
							    'id_admin' => $_SESSION['admin']['id'],
							    'telefon' => sanitize($telefonni_cislo),
							    'sms' => sanitize($_POST['sms_text']),
							    'stav' => sanitize($sms_odeslani),
							    'datum' => time()
							     );
							     
			 $query_insert_sms = Db::insert('sms_odeslane', $data_insert_sms);
		}

		
		
	}
	else
	{
		echo 'Chyba! Nepodařilo se odeslat SMS na '.sanitize($_POST['telefon']);
	}

break;



/***********************************************************************/




case 'zobraz_nadrazene_kategorie':

$form = '';

 if($_POST['vnor'])
 {
	$vnor_nad = ($_POST['vnor'] - 1); 
	$data = Db::queryAll('SELECT id, nazev FROM kategorie WHERE aktivni=1 AND vnor=? ', array($vnor_nad));

		if($data !== false ) 
        {
			$form .= '<label class="control-label" for="_id_nadrazeneho">Nadřazená kategorie:</label><select name="id_nadrazeneho" id="id_nadrazeneho" required >';
		    foreach ($data as $row) 
			{
				
				$form .= '<option value="'.$row['id'].'">'.$row['nazev'].'</option>';
			}
			
			$form .= '</select>';
		}
				
 } 
 
 
 
 echo $form;

		
	
	
break;



/***********************************************************************/


case 'dalsi_varianty':

$form = '';
$id = intval($_POST['id']);

$form .= '<div class="clear" style="height: 5px;"></div>';
$form .= '<div class="cara"></div>';
$form .= '<div class="clear" style="height: 5px;"></div>';
$form .= '<div class="varianty_obal">';
$form .=  '<div style="float: left; width: 50%;">
<label class="control-label" for="nazev_var_'.$id.'"><b>Název varianty '.($id+1).'</b>:</label>
<input type="text" name="nazev_var[]" id="nazev_var_'.$id.'" value="" class="input_req" required  >
<label class="control-label" for="cena_A_'.$id.'">Cena A bez DPH:</label><input type="text" name="cena_A[]" id="cena_A_'.$id.'" value="" class="input_req" required 
onkeyup="vypocitej_cenu_s_dph('.__DPH__.',\'cena_A_'.$id.'\',\'cena_A_'.$id.'_s_dph\');" ><span>&nbsp;&nbsp;orientační cena s DPH: <span id="cena_A_'.$id.'_s_dph" style="color: red; text-align: right; border: 0;" ></span> </span>
<label class="control-label" for="cena_B_'.$id.'">cena B bez DPH:</label><input type="text" name="cena_B[]" id="cena_B_'.$id.'" value=""    >
<label class="control-label" for="cena_C_'.$id.'">Cena C bez DPH:</label><input type="text" name="cena_C[]" id="cena_C_'.$id.'" value=""    >
<label class="control-label" for="cena_D_'.$id.'">Cena D bez DPH:</label><input type="text" name="cena_D[]" id="cena_D_'.$id.'" value=""    >
<label class="control-label" for="foto_var_'.$id.'">Foto varianty:</label><input type="file" name="foto_var[]" id="foto_var_'.$id.'" value=""   > <span>jpg, png, gif</span>
<canvas id="imageCanvas_'.$id.'" class="imageCanvas"></canvas>
</div>';

$form .= '<div style="float: left; width: 50%;">
<label class="control-label" for="sleva_'.$id.'">Sleva:</label><input type="text" name="sleva[]" id="sleva_'.$id.'" value=""    >
<label class="control-label" for="datum_od_'.$id.'">Datum od:</label><input type="text" name="datum_od[]" id="od_'.$id.' value="" class="dat"   >
<label class="control-label" for="datum_do_'.$id.'">Datum do:</label><input type="text" name="datum_do[]" id="do_'.$id.'" value="" class="dat"    >
<label class="control-label" for="kat_cislo_var_'.$id.'">Katalogové číslo varianty:</label><input type="text" name="kat_cislo_var[]" id="kat_cislo_var_'.$id.'" value="" class="input_req" required  >
<label class="control-label" for="ean_var_'.$id.'">EAN varianty:</label><input type="text" name="ean_var[]" id="ean_var_'.$id.'" value=""   >
<label class="control-label" for="ks_skladem_'.$id.'">Ks skladem:</label><input type="text" name="ks_skladem[]" id="ks_skladem_'.$id.'" value=""    >
<label class="control-label" for="_id_dostupnost'.$id.'">Dostupnost:</label>
<select name="id_dostupnost[]" id="id_dostupnost_'.$id.'"   required  >';
$data_dost = Db::queryAll('SELECT * FROM produkty_dostupnost WHERE 1 ORDER BY id ASC', array());
$form .= '<option value="0"  selected >vyberte</option>';
foreach ($data_dost as $row_dost) 
{
  $form .= '<option value="'.$row_dost['id'].'" >'.$row_dost['dostupnost'].'</option>';
}
$form .= '</select>
<label class="control-label" for="_aktivni_var_'.$id.'">Stav:</label>
<select name="aktivni_var[]" id="aktivni_var_'.$id.'"    >';
foreach($stav_adm_arr as $stav_k=>$stav_v)
{
  $form .= '<option value="'.$stav_k.'"  >'.$stav_v.'</option>';
}
$form .= '</select>
</div>

<div class="clear" style="height: 1px;"></div></div>';
			
echo $form;

		
	
	
break;



/***********************************************************************/


case 'zobraz_technicke_parametry':

$tabulka = '';

 if($_POST['id_sablony'])
 {

	$data = Db::queryRow('SELECT * FROM sablony_tech_par WHERE id=? ', array($_POST['id_sablony']));

		if($data !== false ) 
        {
			 $tabulka .= '<table style="width: 600px;" >';
			 
			 if($data['parametry_arr'])
			 {
				$par_arr = unserialize($data['parametry_arr']);
			 	
			 	foreach($par_arr as $p_key=>$p_val)
			 	{
				$tabulka .= '<tr>
				<td style="width: 40%;">'.$p_val.'</td>
				<td>
				<input type="hidden" name="tp_n[]" value="'.$p_val.'">
				<input type="text" name="tp_h[]"></td>
				</tr>';
			    }
			    
			 }
			
			$tabulka .= '</table>';
		}
				
 } 
 
 
 
 echo $tabulka;

		
	
	
break;




/***********************************************************************/



case 'stav_objednavky_obsah_editoru':

$obsah = '';

 if($_POST['id_stav'])
 {

	$data = Db::queryRow('SELECT * FROM objednavky_stavy WHERE id=? ', array($_POST['id_stav']));

		if($data !== false ) 
        {

			$obsah .= $data['text_email'];
		}
				
 } 
 
 
 
 echo $obsah;

		
	
	
break;


/***********************************************************************/



case 'odeslat_email':



 if($_POST['id_stav'] && $_POST['id_objednavky'] && $_POST['obsah_editoru'] && $_POST['email_zakaznika'])
 {

	$data_eml = Db::queryRow('SELECT nazev FROM objednavky_stavy WHERE id = ?', array($_POST['id_stav']));
	if($data_eml)
	{
	    $trasovani_zasilky = ''; 
	    $kod_do_emailu = '';
	    $eml = New Email('html',false);
		$eml->nastavFrom(__EMAIL_FROM__);
		$eml->nastavTo($_POST['email_zakaznika']);
		
		// nahradime 
		/*
	    [CELKOVA_CASTKA] = celková částka za objednávku
		[CISLO_UCTU] = číslo účtu z obecného nastavení pro daný eshop
		[VARIABILNI_SYMBOL] = var. symbol = číslo objednávky
		[TRASOVANI_ZASILKY] = vygeneruje odkaz na trasování zásilky
		[KONTAKTY_PATA] = kontakty do patičky emailu z obecného nastavení
         */
        
        $data_obj = Db::queryRow('SELECT * FROM objednavky WHERE id = ?', array($_POST['id_objednavky']));
		
		$eml->nastavSubject('Změna stavu vaší objednávky č. '.$data_obj['cislo_obj'].' - '.$data_eml['nazev']);
		$body_eml = $_POST['obsah_editoru'];
		
		if($data_obj['cislo_zasilky'])
		{
		  $trasovani_zasilky = 'Trasování zásilky: <a href="https://t.17track.net/cs#nums='.$data_obj['cislo_zasilky'].'">zjistit polohu zásilky</a><br>';
		}
		
		// slevový kod
		if($_POST['slev_kod'])
		{
		  if(__SLEVOVE_KODY_AUTOMAT_GEN__==1)
		  {
		    // máme povoleno automatické generování
		    if(__SLEVOVE_KODY_CASTKA_OD__ < intval($data_obj['cena_celkem_s_dph']))
		    {
			  // částka objednávky je větší než definovaná hodnota v obecném nastavení
			  // vygenerujeme slevový kód
			  $slevovy_kod = random_strings(12);
			  $platnost_od = time();
			  $platnost_do = (time() + intval(__SLEVOVE_KODY_DOBA_PLATNOSTI__));
			  
			  // uložíme do databáze
			  $data_insert_sl = array(
			    'kod' => $slevovy_kod,
			    'typ' => intval(__SLEVOVE_KODY_TYP__),
			    'opakovane_pouziti' => intval(__SLEVOVE_KODY_OPAKOVANE_POUZITI__),
			    'datum_od' => $platnost_od,
			    'datum_do' => $platnost_do,
			    'castka' => intval(__SLEVOVE_KODY_HODNOTA__),
			    'aktivni' => 1
			     );
			     $query_insert_sl = Db::insert('slevove_kody', $data_insert_sl);
			     
			  // pridáme do emailu
			  $kod_do_emailu = "<br><br><b>Slevový kód ve výši ".__SLEVOVE_KODY_HODNOTA__;
			  if(__SLEVOVE_KODY_TYP__==1){$kod_do_emailu .= " ".__MENA__;}
			  else{$kod_do_emailu .= " %";}
			  $kod_do_emailu .= " pro Vaši příští objednávku: ".$slevovy_kod."\nPlatnost tohoto slevového kódu je do ".date("d.m.Y",$platnost_do)."</b><br><br>";
			  
			}
		  }
		}
		
		$body_eml = str_replace('[CELKOVA_CASTKA]',$data_obj['cena_celkem_s_dph'].' '.__MENA__,$body_eml);
		$body_eml = str_replace('[KONTAKTY_PATA]',$kod_do_emailu.nl2br(__KONTAKTY_PATA__),$body_eml);
		$body_eml = str_replace('[VARIABILNI_SYMBOL]',$data_obj['cislo_obj'],$body_eml);
		$body_eml = str_replace('[CISLO_UCTU]',__CISLO_UCTU__,$body_eml);
		$body_eml = str_replace('[TRASOVANI_ZASILKY]',$trasovani_zasilky,$body_eml);
		$eml->nastavBody($body_eml);
		$eml_odeslani = $eml->odesliEmail();
		
		// uložíme změnu stavu
		$data_update = array('id_stav' => $_POST['id_stav']);
		$where_update = array('id' => $_POST['id_objednavky']);
		$query = Db::update('objednavky', $data_update, $where_update);
		
		// historie změn
		$data_insert = array(
					    'id_obj' => $_POST['id_objednavky'],
					    'id_admin' => $_SESSION['admin']['id'],
					    'datum' => time(),
					    'stav' => sanitize($data_eml['nazev'])
					     );
			     
		$query_insert = Db::insert('objednavky_historie_zmen', $data_insert);
		
		
		// log záznam
		$data_insert = array(
					    'id_admin' => $_SESSION['admin']['id'],
					    'uz_jm' => $_SESSION['admin']['uz_jm'],
					    'udalost' => 'Objednávky - změna stavu na '.$data_eml['nazev'].' U obj. ID '.intval($_POST['id_objednavky']),
					    'url' => sanitize($_SERVER['HTTP_REFERER']),
					    'ip' => sanitize(getip()),
					    'datum' => time()
					     );
			     
		$query_insert = Db::insert('admin_log_udalosti', $data_insert);
		
		if($eml_odeslani)
		{
			echo 'Email byl odeslán na '.sanitize($_POST['email_zakaznika']);
		}
		else
		{
			echo 'Chyba! Npodařilo se odeslat email';
		}
	}
				
 } 
 
 

		
	
	
break;



/***********************************************************************/


case 'razeni_fotek_galerie':

if($_POST['pages']) 
{
    parse_str($_POST['pages'], $itemOrder);
    
    foreach ($itemOrder['page'] as $key => $value) 
    {
        
        $data_update = array('razeni' => $key);
		$where_update = array('id' => $value);
		$query = Db::update('fotogalerie_fotky', $data_update, $where_update);
    }
    
    echo $query;
}


break;


/***********************************************************************/

case 'razeni_fotek_produkty':

if($_POST['pages']) 
{
    parse_str($_POST['pages'], $itemOrder);
    
    foreach ($itemOrder['page'] as $key => $value) 
    {
        
        $data_update = array('razeni' => $key);
		$where_update = array('id' => $value);
		$query = Db::update('produkty_foto', $data_update, $where_update);
    }
    
    echo $query;
}


break;


/***********************************************************************/

case 'razeni_priloh_produkty':

if($_POST['pages']) 
{
    parse_str($_POST['pages'], $itemOrder);
    
    foreach ($itemOrder['page'] as $key => $value) 
    {
        
        $data_update = array('razeni' => $key);
		$where_update = array('id' => $value);
		$query = Db::update('produkty_prilohy', $data_update, $where_update);
    }
    
    echo $query;
}


break;



/***********************************************************************/

case 'vygeneruj_selbox_produkty':
 
if($_POST['idp'] && $_POST['id_kat']) 
{
	$selbox = '';
	
    $selbox .= '<div style="float: left;"><label class="control-label" for="_id_pr_dp">Produkty:</label>
	<select name="id_produkt_dp" id="id_produkt_dp"  >';
	$data_p = Db::queryAll("SELECT id, nazev, kat_cislo FROM produkty 
	WHERE aktivni=1 AND id!=".intval($_POST['idp'])." AND id_kat_arr LIKE '%\"".intval($_POST['id_kat'])."\"%' ORDER BY nazev", array());
	$selbox .= '<option value="0" >vyberte</option>';
	foreach ($data_p as $row_p) 
	{
	  $selbox .= '<option value="'.$row_p['id'].'" >'.$row_p['nazev'].' ('.$row_p['kat_cislo'].')</option>';
	}
	$selbox .= '</select></div>
	<div style="float: left;margin-left: 20px; padding-top: 15px;"><input type="button" name="but" id="but" value="Přidat k příslušenství" onclick="PridejPrislusenstvi('.$_POST['idp'].');" ></div>';
	
	echo $selbox;
}


break;


/***********************************************************************/

case 'fakturoid_vystav_fakturu':
 
require('./lib/Fakturoid.php');
$f = new Fakturoid\Client(__FAKTUROID_SLUG__, __FAKTUROID_EMAIL__, __FAKTUROID_API_KEY__, __DOMENA__.' <info@w-software.com>');
 
	if($_POST['ido'])
	{
		
		$data_o = Db::queryRow('SELECT O.id_zakaznik, O.vaha, O.udana_cena, O.cislo_obj, O.cena_celkem_s_dph, O.datum, O.kurz_euro,
		P.dobirka, P.prevodem, P.karta, P.typ_fakturoid,
		Z.jmeno, Z.prijmeni, Z.email, Z.telefon, 
		Z.dodaci_nazev, Z.dodaci_ulice, Z.dodaci_cislo, Z.dodaci_obec, Z.dodaci_psc, Z.dodaci_id_stat, 
		Z.fakturacni_firma, Z.fakturacni_ulice, Z.fakturacni_cislo, Z.fakturacni_obec, Z.fakturacni_psc, Z.fakturacni_id_stat, Z.ic, Z.dic
		FROM objednavky O 
		LEFT JOIN zakaznici Z ON Z.id=O.id_zakaznik
		LEFT JOIN platba P ON P.id=O.platba_id
		WHERE O.id=?  ', array($_POST['ido']));
		if($data_o !== false ) 
		{
			$kurz_euro = $data_o['kurz_euro'];
			$kod_meny = __KOD_MENY__;
			$stat = $staty_iso_arr[$data_o['fakturacni_id_stat']];
		  
		}
			  
		
		$client_data_arr = array();
		
		// client_name, client_street, client_city, client_zip, client_registration_no (IČ), subject_custom_id (id registrace), status (paid), order_number (č. obj)
		// vat_price_mode(null, without_vat, from_total_with_vat), public_html_url
	
		if($data_o['ic'])
		{
		   $client_data_arr['registration_no'] = $data_o['ic'];
		}
		
		if($data_o['dic'])
		{
		   $client_data_arr['vat_no'] = $data_o['dic'];
		}
		
		if($data_o['fakturacni_firma'])
		{
		   $jmeno = $data_o['fakturacni_firma'];
		}
		else
		{
		   $jmeno = $data_o['jmeno'].' '.$data_o['prijmeni'];
		}
			
 
 		
	   
	    $client_data_arr['name'] = $jmeno;
	    $client_data_arr['street'] = $data_o['fakturacni_ulice'].' '.$data_o['fakturacni_cislo'];
	    $client_data_arr['city'] = $data_o['fakturacni_obec'];
	    $client_data_arr['zip'] = $data_o['fakturacni_psc'];
	    $client_data_arr['country'] = $stat;
	    $client_data_arr['email'] = $data_o['email'];
	    $client_data_arr['subject_custom_id'] = $data_o['id_zakaznik'];
	    $client_data_arr['order_number'] = $data_o['cislo_obj'];	 
	    $client_data_arr['variable_symbol'] = $data_o['cislo_obj'];	  
	    
	    // řádky faktury
	    $radky_faktury = array();
	    
	    // nejdříve zboží
		$data_op = Db::queryAll('SELECT * FROM objednavky_polozky WHERE id_obj=? AND typ=? ORDER BY id', array($_POST['ido'],0));
		if($data_op !== false ) 
		{	
			foreach ($data_op as $row_op) 
			{
				$radky_faktury[] = array('name' => $row_op['polozka'], 'quantity' => $row_op['pocet'], 'unit_price' => $row_op['cena_za_ks'], 'vat_rate' => $row_op['dph']);
			}
		}
		
		// pak slevový kód
		// ten se při realizaci objednávky vždy přepočte na částku a ta už se nemění
		$data_ops = Db::queryRow('SELECT * FROM objednavky_polozky WHERE id_obj=? AND typ=?', array($_POST['ido'],3));
		if($data_ops !== false) 
	    {
		    $radky_faktury[] = array('name' => $data_ops['polozka'], 'quantity' => $data_ops['pocet'], 'unit_price' => $data_ops['cena_za_ks'], 'vat_rate' => $data_ops['dph']);
		}
		
		// pak doprava
		$data_opd = Db::queryRow('SELECT * FROM objednavky_polozky WHERE id_obj=? AND typ=?', array($_POST['ido'],1));
		if($data_opd !== false) 
	    {
			$radky_faktury[] = array('name' => $data_opd['polozka'], 'quantity' => $data_opd['pocet'], 'unit_price' => $data_opd['cena_za_ks'], 'vat_rate' => $data_opd['dph']);
		}
		
		// pak platba
		$data_opp = Db::queryRow('SELECT * FROM objednavky_polozky WHERE id_obj=? AND typ=?', array($_POST['ido'],2));
		if($data_opp !== false) 
	    {
			$radky_faktury[] = array('name' => $data_opp['polozka'], 'quantity' => $data_opp['pocet'], 'unit_price' => $data_opp['cena_za_ks'], 'vat_rate' => $data_opp['dph']);
		}
			


		 // create subject
		 $response = $f->createSubject($client_data_arr);
		 $subject  = $response->getBody();
			
		 // create invoice with lines
		 $lines    = $radky_faktury;
		 

		 if($_POST['transferred_tax_liability']==1)
		 {
				$response = $f->createInvoice(array('subject_id' => $subject->id, 'payment_method'=>$data_o['typ_fakturoid'], 'currency'=>$kod_meny, 'exchange_rate'=>$kurz_euro, 'transferred_tax_liability' => true, 'lines' => $lines));
		 }
		 else
		 {
			$response = $f->createInvoice(array('subject_id' => $subject->id, 'payment_method'=>$data_o['typ_fakturoid'], 'lines' => $lines));
		 }

		 
		 $invoice  = $response->getBody();
		 $faktura_url = $invoice->public_html_url;
		 $cislo_faktury = $invoice->number;
		 
		 $f->fireInvoice($invoice->id, 'pay'); // uhrazeno
	
	    if($faktura_url)
	    {
		    
		   echo 'Faktura '.$cislo_faktury.' byla vystavena';
		   
		   // uložíme do faktur
		   // předpokládá se, že je faktura uhrazena = id_stav = 2
		   
		   $data_cc_bez = Db::queryRow('SELECT sum(pocet * cena_za_ks) AS CELKEM_BEZ FROM objednavky_polozky WHERE id_obj=?  ', array($_POST['ido']));
		   $data_cc_s = Db::queryRow('SELECT sum(pocet * cena_za_ks * (dph / 100 + 1)) AS CELKEM_S FROM objednavky_polozky WHERE id_obj=?  ', array($_POST['ido']));
			 
		   $data_insert_f = array(
		    'cislo_faktury' => $cislo_faktury,
		    'id_stav' => 2,
		    'id_zakaznik' => $data_o['id_zakaznik'],
		    'id_objednavky' => $_POST['ido'],
		    'cena_celkem_bez_dph' => $data_cc_bez['CELKEM_BEZ'],
		    'cena_celkem_s_dph' => $data_cc_s['CELKEM_S'],
		    'datum_vystaveni' => time(),
		    'var_symbol' => $data_o['cislo_obj'],
		    'faktura_url' => $faktura_url
		     );
     
		   $query_insert_f = Db::insert('faktury', $data_insert_f);
		   
		   // aktualizujeme objednávku
		   $data_update = array(
		    'faktura_url' => $faktura_url,
			'faktura_cislo' => $faktura_url,
			'faktura_id' => $invoice->id
			);
			$where_update = array('id' => $_POST['ido']);
			$query = Db::update('objednavky', $data_update, $where_update);

			
			// log
			$data_insert_log = array(
		    'id_admin' => $_SESSION['admin']['id'],
		    'uz_jm' => $_SESSION['admin']['uz_jm'],
		    'udalost' => 'Nová faktura k objednávce s id '.intval($_POST['ido']),
		    'url' => sanitize($_SERVER['HTTP_REFERER']),
		    'ip' => sanitize(getip()),
		    'datum' => time()
		     );
     
		   $query_insert_log = Db::insert('admin_log_udalosti', $data_insert_log);
 
		}
		else
		{
		   echo 'Něco se pokazilo';
		}
    
    }
    else
    {
	  echo 'Chybí číslo objednávky';
	}


break;


/***********************************************************************/

case 'fakturoid_smaz_fakturu':

require('./lib/Fakturoid.php');
$f = new Fakturoid\Client(__FAKTUROID_SLUG__, __FAKTUROID_EMAIL__, __FAKTUROID_API_KEY__, __DOMENA__.' <info@w-software.com>');
 
if($_POST['ido'])
{
	$data_o = Db::queryRow('SELECT * FROM objednavky WHERE O.id=?  ', array($_POST['ido']));
	if($data_o !== false ) 
	{
		if($data_o['faktura_id'])	
		{
			$vysledek = $f->deleteInvoice($data_o['faktura_id']);
			if($vysledek->statusCode == 204)
			{
				echo 'Faktura k objednávce '.$_POST['ido'].' byla smazána';
				
				// aktualizujeme objednávku
			   $data_update = array(
			    'faktura_url' => '',
				'faktura_cislo' => '',
				'faktura_id' => ''
				);
				$where_update = array('id' => $_POST['ido']);
				$query = Db::update('objednavky', $data_update, $where_update);
				
				// smažeme fakturu z db
				$where_delete = array('id_objednavky' => $_POST['ido']);
				$query = Db::delete('faktury', $where_delete); 
				
				// log
				$data_insert_log = array(
			    'id_admin' => $_SESSION['admin']['id'],
			    'uz_jm' => $_SESSION['admin']['uz_jm'],
			    'udalost' => 'Smazání faktury k objednávce s id '.intval($_POST['ido']),
			    'url' => sanitize($_SERVER['HTTP_REFERER']),
			    'ip' => sanitize(getip()),
			    'datum' => time()
			     );
	     
			   $query_insert_log = Db::insert('admin_log_udalosti', $data_insert_log);
		   
 
			}
			else
			{
			  echo 'Fakturu k objednávce '.$_POST['ido'].' se nepodařilo smazat';
			}
		}
		 else
	    {
		   echo 'Chybí ID faktury pro objednávku '.$_POST['ido'];
		}
	}
    

}
else
{
  echo 'Chybí číslo objednávky';
}


break;	



/***********************************************************************/


default:
 
}


Db::close();
?>
