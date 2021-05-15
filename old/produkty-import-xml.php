<?php 
// import produktů z XML
set_time_limit (6800);
Admin::adminLogin(4);


	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 1);
	$form->required = 'xml';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
					
				$i_i = 0;
				$r = 2;
				$fileContent = file_get_contents($_FILES['xml']['tmp_name']);
				$xml = new SimpleXMLElement($fileContent);
				$pocet = count($xml->SHOPITEM);
				
				/*foreach($xml as $child)
				{
					
					$fotky_dalsi = $child->IMGURL_ALTERNATIVE;
					//var_dump($parametry);
				    //$child(getAsXMLContent($child));
				    var_dump($fotky_dalsi);
				    
				    foreach($parametry as $par_k=>$par_v)
				    {
					  var_dump($par_v);
					}
					echo "---------------------------------<br><br>";
				    
				    if($i_i > 100){ break;}
				 
				    $i_i++;
				}
				
			
				die();*/

			    
			    if ($pocet) 
			    {

					foreach($xml as $child)
				    {
						
						 $id_original = (string)$child->ITEM_ID;
						 $kat_cislo = (string)$child->PRODUCTNO;  
						 $ean = (string)$child->EAN; 
						 $vyrobce = (string)$child->MANUFACTURER;  
						 $nazev = (string)$child->PRODUCT; 
						 $nazev_pn = (string)$child->PRODUCTNAME;  
						 $popis =  (string)getAsXMLContent($child->DESCRIPTION);  
						 $fotka =  (string)$child->IMGURL; 
						 $fotky_dalsi =  $child->IMGURL_ALTERNATIVE; // může být pole
						 $cena_s_dph = (string)$child->PRICE_VAT; 
						 $dostupnost = (string)$child->DELIVERY_DATE; 
						 $parametry = $child->PARAM; 
						 $dph = (string)$child->VAT;  // odstranit % na konci
						 $parovaci_kod = (string)$child->ITEMGROUP_ID; 
				
						 $jednotka = 'ks';  
						 $zaruka = '2 roky';
						 
						 if($nazev)
						 {
						    $nazev_pr = $nazev;
						 }
						 else
						 {
							$nazev_pr = $nazev_pn;
						 }

						 $nazev_pr = str_replace('<![CDATA[','',$nazev_pr);
						 $nazev_pr = str_replace(']]>','',$nazev_pr);

						 $popis = str_replace('<![CDATA[','',$popis);
						 $popis = str_replace(']]>','',$popis);
						 $popis = str_replace('<br />','<br>',$popis);
						 
						 $vyrobce = str_replace('<![CDATA[','',$vyrobce);
						 $vyrobce = str_replace(']]>','',$vyrobce);
						 
						 
						 
						 
						 
			 				// DPH
							if($dph)
							{
								
								$dph = rtrim($dph,'%');
								
								$cena_bez_dph = round(($cena_s_dph / ($dph/100+1)),2);
								$cena_bez_dph = str_replace(',','.',$cena_bez_dph);
								
								$data_dph = Db::queryRow('SELECT id FROM dph WHERE dph=? ', array($dph));
								if($data_dph !== false)
								{
								  $id_dph = $data_dph['id'];
								}
								else
								{
								  // uložíme nové
								  $data_insert_dph = array(
											    'dph' => intval($dph)
											     );
											          
											     
								  $query_insert_dph = Db::insert('dph', $data_insert_dph);
								  $id_dph = $query_insert_dph;
								  
								}
							
							}
							else
							{
								$id_dph = 3; // aktuální sazba 21%
							}
							
							// vyrobce
							if($vyrobce)
							{
								
								$data_vyr = Db::queryRow('SELECT id FROM produkty_vyrobci WHERE vyrobce=? ', array($vyrobce));
								if($data_vyr !== false)
								{
								  $id_vyrobce = $data_vyr['id'];
								}
								else
								{
								  // uložíme nového
								  $data_insert_vyr = array(
											    'str' => bez_diakritiky($vyrobce),
												'vyrobce' => sanitize($vyrobce),
												'prvni_pismeno' => mb_strtolower($vyrobce{0}),
												'popis' => '',
												'datum' => time(),
												'aktivni' => 1
											     );
											          
											     
								  $query_insert_vyr = Db::insert('produkty_vyrobci', $data_insert_vyr);
								  $id_vyrobce = $query_insert_vyr;
								  
								}
							
							}
							else
							{
								$id_vyrobce = 0;
							}
							
							
							// dostupnost
							if($dostupnost)
							{
								
								$data_dost = Db::queryRow('SELECT id FROM produkty_dostupnost WHERE dostupnost_porovnavace=? ', array($dostupnost));
								if($data_dost !== false)
								{
								  $id_dostupnost = $data_dost['id'];
								}
								else
								{
								  // uložíme nového
								  $data_insert_dost = array(
											    'dostupnost' => 'do '.intval($dostupnost).' dnů',
												'dostupnost_porovnavace' => intval($dostupnost),
											     );
											          
											     
								  $query_insert_dost = Db::insert('produkty_dostupnost', $data_insert_dost);
								  $id_dostupnost = $query_insert_dost;
								  
								}
							
							}
							else
							{
								$id_dostupnost = 0;
							}
							
							// kontrola unikátního názvu
							$data_kontrola = Db::queryAffected('SELECT id FROM produkty WHERE str=? ', array(bez_diakritiky($nazev_pr)));
							if($data_kontrola)
							{
							    //$str_rand = '-'.mt_rand(1,99);
							    
							    $str_rand = ' '.$r;
							    $r++;
							}
							else
							{
								$str_rand = '';
							}
							
							// insert produktu
							$data_insert_produkt = array(
											'id_vyrobce' => $id_vyrobce,
											'id_dph' => $id_dph,
											'str' => bez_diakritiky($nazev_pr.$str_rand),
										    'nazev' => sanitize($nazev_pr.$str_rand),
										    'popis' => sanitize($popis),
										    'jednotka' => $jednotka,
										    'zaruka' => $zaruka,
										    'datum' => time(),
											'indexovani' => 1,
										    'aktivni' => 0,
										    'parovaci_kod' => $parovaci_kod
										     );
										     
							$query_insert_produkt = Db::insert('produkty', $data_insert_produkt);			     
										     
										     
										     
							// insert varianty
							$data_insert_v = array(
							    'id_produkt' => $query_insert_produkt,
							    'id_dostupnost' => intval($id_dostupnost),
							    'nazev_var' => sanitize($nazev_pr),
							    'cena_A' => $cena_bez_dph,
							    'cena_B' => $cena_bez_dph,
							    'cena_C' => $cena_bez_dph,
							    'cena_D' => $cena_bez_dph,
							    'datum' => time(),
							    'sleva' => 0,
							    'kat_cislo_var' => sanitize($kat_cislo),
							    'ean_var' => sanitize($ean),
							    'ks_skladem' => 0,
							    'aktivni_var' => 0
							     );
							     
							$query_insert_v = Db::insert('produkty_varianty', $data_insert_v);
							
							
							// insert fotek
							if($fotka)
							{
								 foto_produkt_xml($fotka,500,1200,$query_insert_produkt,$nazev_pr,1);
							}
							
							
							// obrázky doplňkové
							if($fotky_dalsi)
							{

								  if(is_array($fotky_dalsi))
								  {
									  // vice obrazku
									  foreach($fotky_dalsi as $fd_key=>$fd_val)
									  {

										    foto_produkt_xml((string)$fd_val,500,1200,$query_insert_produkt,$nazev_pr,0);

									     
									  }
 
	
									  
								  }
								  else
								  {
									   // jeden obrazek
									   foto_produkt_xml((string)$fotky_dalsi,500,1200,$data_insert_produkt,$nazev_pr,0);
								  }
							  
							}
							
							
							
							
							// insert technických parametrů	
							if($parametry)
							{
								// 2 verze podle toho jestli je parametr jen jeden nebo více - Heureka fail
								
									if(is_array($parametry->PARAM_NAME))
									{
										 foreach ($parametry as $key_par=>$value_par)
										 {
										   $nazev_parametru = (string)$value_par->PARAM_NAME;
										   $hodnota_parametru = (string)$value_par->VAL;
										   $hodnota_parametru = str_replace('<![CDATA[','',$hodnota_parametru);
										   $hodnota_parametru = str_replace(']]>','',$hodnota_parametru);
										   
										  $data_insert_tp = array(
												    'id_produkt' => $query_insert_produkt,
												    'id_sablona' => 0,
												    'id_kategorie' => 0,
												    'nazev' => sanitize($nazev_parametru),
												    'hodnota' => sanitize($hodnota_parametru)
												     );
						                  $query_insert_tp = Db::insert('produkty_tech_par', $data_insert_tp);
										 
										 }
									}
									elseif($parametry->PARAM_NAME)
									{
										
										$nazev_parametru = (string)$parametry->PARAM_NAME;
										$hodnota_parametru = (string)$parametry->VAL;
										$hodnota_parametru = str_replace('<![CDATA[','',$hodnota_parametru);
										$hodnota_parametru = str_replace(']]>','',$hodnota_parametru);

										 $data_insert_tp = array(
												    'id_produkt' => $query_insert_produkt,
												    'id_sablona' => 0,
												    'id_kategorie' => 0,
												    'nazev' => sanitize($nazev_parametru),
												    'hodnota' => sanitize($hodnota_parametru)
												     );
						                 $query_insert_tp = Db::insert('produkty_tech_par', $data_insert_tp);
 
										
									}
									


								
							}
							
							$i_i++;
							
								     
		
					}
				
				}
				

			    
				
				/*****************************************************************/
	 
 
                
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Produkty - import z XML - '.$pocet,
							    'url' => sanitize($_SERVER['HTTP_REFERER']),
							    'ip' => sanitize(getip()),
							    'datum' => time()
							     );
					     
	            $query_insert_log = Db::insert('admin_log_udalosti', $data_insert_log);
				
				echo '<div class="alert-success">Uloženo!<br>Import proběhl v pořádku a bylo naimportováno '.$i_i.' produktů.<br><a href="./index.php?p=produkty">Přejít na přehled</a></div>';
		    }
		    else
		    {
			   echo '<div class="alert-warning">Chyba!<br>Nejsou vyplněny všechny povinné položky<br>';
			   foreach($form->errors as $ke=>$ve){ echo $ve.'<br>'; }
			   echo '<a href="'.$_SERVER['HTTP_REFERER'].'">Přejít zpět</a><br>
			   <a href="./index.php?p=produkty">Přejít na přehled</a></div>';
		    }
	}
	else
	{			

				echo 'Zde můžete naimportovat do eshopu produkty z XML. XML soubor musí být kompatibilní s <a href="https://sluzby.heureka.cz/napoveda/xml-feed/" target="_blank">XML pro Heuréku</a>.
				<br>Produkty je nutno po importu přiřadit ručně do kategorií.<br>
				Pozor, import není aktualizace!<br>
				Nezavírejte okno prohlížeče dokud import neskončí. U velkých souborů může import běžet i desítky minut.';
			    echo $form->formOpen();
				echo $form->inputFile('xml','xml','','XML soubor','',1,0,'');
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
				
				 
	}





?>
 <script type="text/javascript">
	 
 $(document).ready(function () {
    $("#formular").validate({
       
    });
});






</script>
