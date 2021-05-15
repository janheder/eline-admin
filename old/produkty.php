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
	echo '<div class="clear" style="height: 5px;"></div>';
	echo '<label for="doprava_zdarma" class="inline"><input type="checkbox" name="doprava_zdarma" value="1" ';
	if($_GET['doprava_zdarma']==1){echo ' checked ';}
	echo ' id="doprava_zdarma"> Doprava zdarma</label>';
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
	
	echo '<div class="clear" style="height: 5px;"></div>';
	echo '<label for="specialni_doprava" class="inline"><input type="checkbox" name="specialni_doprava" value="1" ';
	if($_GET['specialni_doprava']==1){echo ' checked ';}
	echo ' id="specialni_doprava"> Speciální doprava</label>';

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
	echo $form->inputButton('redir','redir','Zobrazit vše','','onclick="self.location.href=\'index.php?p=produkty\'"','');
	echo $form->addHTML('</div>');
	echo $form->formClose();

	echo '</fieldset>';
}


if($_GET['action']=='delete')
{
		if($_GET['id'])
		{
			

				$where_delete = array('id' => $_GET['id']);
				$query = Db::delete('produkty', $where_delete); 
				if($query)
				{
					
					// mažeme i varianty
					$data_fv = Db::queryRow('SELECT foto_var FROM produkty_varianty WHERE id_produkt=?  ', array($_GET['id']));
					if($data_fv !== false ) 
					{
						// smažeme z disku
						unlink('../fotky/produkty/male/'.$data_fv['foto_var']);
						unlink('../fotky/produkty/velke/'.$data_fv['foto_var']);
					}
					$where_delete_v = array('id_produkt' => $_GET['id']);
				    $query_v = Db::delete('produkty_varianty', $where_delete_v);
				    
				    // mažeme i tech. par.
				    $where_delete_tp = array('id_produkt' => $_GET['id']);
				    $query_tp = Db::delete('produkty_tech_par', $where_delete_tp);
				    
				    // mažeme fotky
				    $data_fp = Db::queryAll('SELECT foto FROM produkty_foto WHERE id_produkt=? ORDER BY id', array($_GET['id']));
					if($data_fp !== false ) 
					{	
						foreach ($data_fp as $row_fp) 
						{
							// smažeme z disku
							unlink('../fotky/produkty/male/'.$row_fp['foto']);
							unlink('../fotky/produkty/velke/'.$row_fp['foto']);
						}
					}
				    $where_delete_f = array('id_produkt' => $_GET['id']);
				    $query_f = Db::delete('produkty_foto', $where_delete_f);
					
					$data_insert = array(
								    'id_admin' => $_SESSION['admin']['id'],
								    'uz_jm' => $_SESSION['admin']['uz_jm'],
								    'udalost' => 'Produkty - smazání záznamu id '.intval($_GET['id']),
								    'url' => sanitize($_SERVER['HTTP_REFERER']),
								    'ip' => sanitize(getip()),
								    'datum' => time()
								     );
						     
					$query_insert = Db::insert('admin_log_udalosti', $data_insert);
					
					echo '<div class="alert-success">Smazáno!<br>Záznam byl smazán</div>';
				}
			
			
			
		}
		else
		{
			echo '<div class="alert-warning">Chyba!<br>Chybí ID záznamu</div>';
		}

}


if($_GET['action']=='update')
{
	
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 1);
	$form->required = 'nazev,popis_kratky,popis,id_kat_arr,id_dph,nazev_var,cena_A,kat_cislo_var';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				  
					   if($form->errors==false)
					   {
							if(is_array($_POST['id_kat_arr']))
							{
							    $id_kat_arr = serialize($_POST['id_kat_arr']);
							}
							else
							{
								$id_kat_arr = '';
							}
				
							$data_update = array(
								'id_kat_arr' => $id_kat_arr,
							    'id_sablona' => intval($_POST['id_sablona']),
							    'id_vyrobce' => intval($_POST['id_vyrobce']),
							    'id_dph' => intval($_POST['id_dph']),
							    'str' => bez_diakritiky($_POST['nazev']),
								'nazev' => $_POST['nazev'],
								'popis_kratky' => $_POST['popis_kratky'],
								'popis' => $_POST['popis'],
								'poznamka' => $_POST['poznamka'],
								'jednotka' => $_POST['jednotka'],
								'zaruka' => $_POST['zaruka'],
								'novinka' => intval($_POST['novinka']),
								'akce' => intval($_POST['akce']),
								'doporucujeme' => intval($_POST['doporucujeme']),
								'doprodej' => intval($_POST['doprodej']),
								'doprava_zdarma' => intval($_POST['doprava_zdarma']),
								'specialni_doprava' => intval($_POST['specialni_doprava']),
								'datum' => time(),
								'aktivni' => intval($_POST['aktivni']),
								
								'keywords' => $_POST['keywords'],
								'title' => $_POST['title'],
								'description' => $_POST['description'],
								'og_title' => $_POST['og_title'],
								'og_description' => $_POST['og_description'],
								'og_site_name' => $_POST['og_site_name'],
								'og_url' => $_POST['og_url'],
								'og_image' => $_POST['og_image'],
								'indexovani' => $_POST['indexovani'],
								'nazev_heureka' => $_POST['nazev_heureka'],
								'category_text_heureka' => $_POST['category_text_heureka'],
								
								'id_category_text_zbozi' => $_POST['category_text_zbozi'],
								'id_category_text_zbozi2' => $_POST['category_text_zbozi2'],
								'productno' => $_POST['productno'],
								'extramessage' => $_POST['extramessage'],
								'extramessage2' => $_POST['extramessage2'],
								'extramessage3' => $_POST['extramessage3'],
								'brand' => $_POST['brand'],
								'vydejnimisto' => $_POST['vydejnimisto'],
								'vydejnimisto2' => $_POST['vydejnimisto2'],
								'vydejnimisto3' => $_POST['vydejnimisto3'],
								'productline' => $_POST['productline'],
								'maxcpc' => $_POST['maxcpc'],
								'maxcpc_search' => $_POST['maxcpc_search'],
								'itemgroupid' => $_POST['itemgroupid'],
								'visibility' => $_POST['visibility'],
								'CUSTOM_LABEL_0' => $_POST['CUSTOM_LABEL_0'],
								'CUSTOM_LABEL_1' => $_POST['CUSTOM_LABEL_1'],
								'CUSTOM_LABEL_2' => $_POST['CUSTOM_LABEL_2'],
								'CUSTOM_LABEL_3' => $_POST['CUSTOM_LABEL_3'],
								'nazev_zbozi' => $_POST['nazev_zbozi'],
								'product' => $_POST['product']
							     );
							

							$where_update = array('id' => $_POST['id']);
							$query_udate = Db::update('produkty', $data_update, $where_update);
							
							
							// technické parametry
							// nejdříve smažeme stávající
							$where_delete_tp = array('id_produkt' => $_POST['id']);
							$query_delete_tp = Db::delete('produkty_tech_par', $where_delete_tp);
							
			                if(is_array($_POST['tp_n']))
			                {
								// musíme zjistit ID kategorie nejnižší úrovně
								if($_POST['id_kat_arr'])
								{
									$data_k = Db::queryRow('SELECT id FROM kategorie WHERE id IN('.implode(",",$_POST['id_kat_arr']).') ORDER BY vnor DESC LIMIT 1 ', array());
									if($data_k) 
									{
									    $kategorie_sablona = $data_k['id'];
									}
									else
									{
										$kategorie_sablona = 0;
									}
							    }
							   $tpx = 0;	
							   foreach($_POST['tp_n'] as $tp_n_k=>$tp_n_v)
							   {
							      
							      $data_insert_tp = array(
										    'id_produkt' => $_POST['id'],
										    'id_sablona' => intval($_POST['id_sablona']),
										    'id_kategorie' => intval($kategorie_sablona),
										    'nazev' => sanitize($tp_n_v),
										    'hodnota' => sanitize($_POST['tp_h'][$tpx])
										     );
				                 $query_insert_tp = Db::insert('produkty_tech_par', $data_insert_tp);
							     $tpx++;
							   }
							}
							
							// přílohy
			                if($_FILES['prilohy']['size'][0])
							{
								
								prilohy_produkt('prilohy',$_POST['id']);
								
							}
			                
			                // fotky
			                if($_FILES['foto']['size'][0])
							{
								
								foto_produkt('foto',500,1200,$_POST['id'],$_POST['nazev']);
								
							}
							
							// fotky stávající 
							// změna popisu
							if($_POST['_foto_popis'])
							{   
								foreach($_POST['_foto_popis'] as $fp_k=>$fp_v)
								{
									$where_update_fp = array('id' => $fp_k);
									$data_update_fp = array('popis' => $fp_v);
							        $query_fp = Db::update('produkty_foto', $data_update_fp, $where_update_fp);
								}
							}
							
							
							// prilohy stávající 
							// změna popisu
							if($_POST['_priloha_popis'])
							{   
								foreach($_POST['_priloha_popis'] as $fp_k=>$fp_v)
								{
									$where_update_fp = array('id' => $fp_k);
									$data_update_fp = array('nazev' => $fp_v);
							        $query_fp = Db::update('produkty_prilohy', $data_update_fp, $where_update_fp);
								}
							}
							
							// varianty - aktualizace start ******************************************************
							$pocet_variant_update = 0;
							if($_POST['id_var'])
			                {
							   $vx = 0;	
							   $pocet_variant_update = count($_POST['id_var']);
							   
							   foreach($_POST['id_var'] as $nv_k=>$nv_v)
							   {
							      // kontrola cen a vyměna čárky za tečku
							      $cena_A = str_replace(',','.',$_POST['cena_A'][$vx]);
							      
							      if($_POST['cena_B'][$vx])
							      {
								     $cena_B = str_replace(',','.',$_POST['cena_B'][$vx]);
								  }
								  else
								  {
									 $cena_B = $cena_A;
								  }
								  
								  if($_POST['cena_C'][$vx])
							      {
								     $cena_C = str_replace(',','.',$_POST['cena_C'][$vx]);
								  }
								  else
								  {
									 $cena_C = $cena_A;
								  }
								  
								  if($_POST['cena_D'][$vx])
							      {
								     $cena_D = str_replace(',','.',$_POST['cena_D'][$vx]);
								  }
								  else
								  {
									 $cena_D = $cena_A;
								  }
								  

							      $data_update_v = array(
										    'id_produkt' => $_POST['id'],
										    'id_dostupnost' => intval($_POST['id_dostupnost'][$vx]),
										    'nazev_var' => $_POST['nazev_var'][$vx],
										    'cena_A' => $cena_A,
										    'cena_B' => $cena_B,
										    'cena_C' => $cena_C,
										    'cena_D' => $cena_D,
										    'sleva' => intval($_POST['sleva'][$vx]),
										    'kat_cislo_var' => $_POST['kat_cislo_var'][$vx],
										    'ean_var' => $_POST['ean_var'][$vx],
										    'ks_skladem' => $_POST['ks_skladem'][$vx],
										    'aktivni_var' => intval($_POST['aktivni_var'][$vx])
										     );
										     
								 // foto varianty
								 if($_FILES['foto_var']['size'][$vx])
							     {
								   $foto_var = foto_produkt_varianta('foto_var',$vx,$_POST['id'],300,800);
								   $data_update_v['foto_var'] = $foto_var;
								 }
								 
								 // datumy slevy od - do
								  if($_POST['datum_od'][$vx])
								  {
									list($den,$mesic,$rok) = explode(".",$_POST['datum_od'][$vx]);
									 $hodina = 1;
									 $minuta = 1;
									 $sekunda = 1;
									 $datum_od = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);  
									$data_update_v['sleva_datum_od'] = $datum_od;
								  }
								  else
								  {
								    // může slevu odstranit
								    $data_update_v['sleva_datum_od'] = 0;
								  }
								  
								  if($_POST['datum_do'][$vx])
								  {
									list($den,$mesic,$rok) = explode(".",$_POST['datum_do'][$vx]);
									 $hodina = 1;
									 $minuta = 1;
									 $sekunda = 1;
									 $datum_do = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);  
									$data_update_v['sleva_datum_do'] = $datum_do;
								  }
								  else
								  {
								    // může slevu odstranit
								    $data_update_v['sleva_datum_do'] = 0;
								  }
								 		     
 
				                 $where_update_v = array('id' => $nv_v);
								 $query_update_v = Db::update('produkty_varianty', $data_update_v, $where_update_v);
								 
								 $vx++;	
						       }
							  
							}
							
							// varianty - aktualizace stop  ******************************************************
							
							
							// varianty - insert start  **********************************************************
							
							if($_POST['nazev_var'])
							{
							   $pocet_variant_celkem = count($_POST['nazev_var']);
							   
							   if($pocet_variant_celkem > $pocet_variant_update)
							   {
							    
							     $pocet_variant_nove = ($pocet_variant_celkem - $pocet_variant_update);
							     
							     for ($vx = $pocet_variant_update; $vx < $pocet_variant_celkem; $vx++) 
							     {
									 
									 // varianty + foto

									      // kontrola cen a vyměna čárky za tečku
									      $cena_A = str_replace(',','.',$_POST['cena_A'][$vx]);
									      
									      if($_POST['cena_B'][$vx])
									      {
										     $cena_B = str_replace(',','.',$_POST['cena_B'][$vx]);
										  }
										  else
										  {
											 $cena_B = $cena_A;
										  }
										  
										  if($_POST['cena_C'][$vx])
									      {
										     $cena_C = str_replace(',','.',$_POST['cena_C'][$vx]);
										  }
										  else
										  {
											 $cena_C = $cena_A;
										  }
										  
										  if($_POST['cena_D'][$vx])
									      {
										     $cena_D = str_replace(',','.',$_POST['cena_D'][$vx]);
										  }
										  else
										  {
											 $cena_D = $cena_A;
										  }
										  
										  
									      
									      $data_insert_v = array(
												    'id_produkt' => $_POST['id'],
												    'id_dostupnost' => intval($_POST['id_dostupnost'][$vx]),
												    'nazev_var' => $_POST['nazev_var'][$vx],
												    'cena_A' => $cena_A,
												    'cena_B' => $cena_B,
												    'cena_C' => $cena_C,
												    'cena_D' => $cena_D,
												    'datum' => time(),
												    'sleva' => intval($_POST['sleva'][$vx]),
												    'kat_cislo_var' => $_POST['kat_cislo_var'][$vx],
												    'ean_var' => $_POST['ean_var'][$vx],
												    'ks_skladem' => $_POST['ks_skladem'][$vx],
												    'aktivni_var' => intval($_POST['aktivni_var'][$vx])
												     );
												     
										 // foto varianty
										 if($_FILES['foto_var']['size'][$vx])
									     {
										   $foto_var = foto_produkt_varianta('foto_var',$vx,$_POST['id'],300,800);
										   $data_insert_v['foto_var'] = $foto_var;
										 }
										 
										 // datumy slevy od - do
										  if($_POST['datum_od'][$vx])
										  {
											list($den,$mesic,$rok) = explode(".",$_POST['datum_od'][$vx]);
											 $hodina = 1;
											 $minuta = 1;
											 $sekunda = 1;
											 $datum_od = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);  
											$data_insert_v['sleva_datum_od'] = $datum_od;
										  }
										  
										  if($_POST['datum_do'][$vx])
										  {
											list($den,$mesic,$rok) = explode(".",$_POST['datum_do'][$vx]);
											 $hodina = 1;
											 $minuta = 1;
											 $sekunda = 1;
											 $datum_do = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);  
											$data_insert_v['sleva_datum_do'] = $datum_do;
										  }
										 		     
						                 $query_insert_v = Db::insert('produkty_varianty', $data_insert_v);

									   
								 } 
																    
							   
							   }
							}
							
							// varianty - insert stop  ************************************************************
			      
			      
							// log
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Produkty - úprava záznamu id '.intval($_POST['id']),
							    'url' => sanitize($_SERVER['HTTP_REFERER']),
							    'ip' => sanitize(getip()),
							    'datum' => time()
							     );
					     
							  $query_insert = Db::insert('admin_log_udalosti', $data_insert);
							
							echo '<div class="alert-success">Uloženo!<br>Záznam byl v pořádku změněn<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a>
							<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'&action=update&id='.intval($_GET['id']).'">Znovu upravit</a></div>';
					   }
					   else
					   {
						   echo '<div class="alert-warning">Chyba!<br>Nejsou vyplněny všechny povinné položky<br>';
						   foreach($form->errors as $ke=>$ve){ echo $ve.'<br>'; }
						   echo '<a href="'.$_SERVER['HTTP_REFERER'].'">Přejít zpět</a><br>
						   <a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
					   }
				  	     
			
			}
			else
			{
				echo '<div class="alert-warning">Chyba!<br>Chybí ID záznamu<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
			}
			
	}
	else
	{
		if($_GET['id'])
		{
			$data = Db::queryRow('SELECT * FROM produkty WHERE id = ?', array($_GET['id']));
			
			if($data)
			{
				// výrobce
				$data_vyr = Db::queryAll('SELECT id,vyrobce FROM produkty_vyrobci WHERE aktivni=1 ORDER BY vyrobce ASC', array());
				$vyr_arr = array();
				if($data_vyr !== false ) 
				{
					$vyr_arr[0] = 'vyberte';	
					 
				    foreach ($data_vyr as $row_vyr) 
					{
					  $vyr_arr[$row_vyr['id']] = $row_vyr['vyrobce'];	
					}
				}
				else
				{
					$vyr_arr = false;
				}
				
				// dph
				$data_dph = Db::queryAll('SELECT * FROM dph WHERE 1 ORDER BY dph ASC', array());
				$dph_arr = array();
				if($data_dph !== false ) 
				{
					 
				    foreach ($data_dph as $row_dph) 
					{
					  $dph_arr[$row_dph['id']] = $row_dph['dph'];	
					}
				}
				else
				{
					$dph_arr = false;
				}
			
				
				
				// sablona tech. par
				$data_sab = Db::queryAll('SELECT * FROM sablony_tech_par WHERE 1 ORDER BY nazev ASC', array());
				$sablona_arr = array();
				if($data_sab !== false ) 
				{
					$sablona_arr[0] = 'vyberte';
					 
				    foreach ($data_sab as $row_sab) 
					{
					  $sablona_arr[$row_sab['id']] = $row_sab['nazev'];	
					}
				}
				else
				{
					$sablona_arr = false;
				}
				
				
				// dostupnost
				$data_dost = Db::queryAll('SELECT * FROM produkty_dostupnost WHERE 1 ORDER BY id ASC', array());
				$dostupnost_arr = array();
				if($data_dost !== false ) 
				{
					$dostupnost_arr[0] = 'vyberte';
					 
				    foreach ($data_dost as $row_dost) 
					{
					  $dostupnost_arr[$row_dost['id']] = $row_dost['dostupnost'];	
					}
				}
				else
				{
					$dostupnost_arr = false;
				}

				echo $form->formOpen();
				echo $form->inputText('nazev','nazev',$data['nazev'],'Název','class="input_req" style="min-width: 500px;"','',1,0,'&nbsp;<span id="kontrola_produkt"></span>');
				echo $form->inputSelect('id_vyrobce','id_vyrobce',$data['id_vyrobce'],$vyr_arr,'Výrobce','',0,0,'');
				echo $form->inputTextarea('popis_kratky','popis_kratky',$data['popis_kratky'],'Popis krátky','class="input_req"','',1,0,'');
				echo $form->addHTML('<script type="text/javascript"> aktivujTinyMCE(\'popis\',300) </script>');
				echo $form->inputTextarea('popis','popis',$data['popis'],'Popis','','',1,0,'');
				echo $form->inputSelect('id_dph','id_dph',$data['id_dph'],$dph_arr,'DPH','',1,0,'');
				echo $form->inputText('jednotka','jednotka',$data['jednotka'],'Jednotka','','',0,0,'');
				echo $form->inputText('zaruka','zaruka',$data['zaruka'],'Záruka','','',0,0,'');
	
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				echo $form->addHTML('<fieldset><legend><h2>Zařazení do kategorií</h2></legend>');	
				echo $form->addHTML('<label for="kat"><input type="checkbox" name="kat" id="kat" value="1" checked onclick="ZobrazSEO(\'_kat\');" > Zařazení do kategorií</label>
				<div id="_kat" style="display: block;">');	
				
				if($data['id_kat_arr'])
				{
				  $kat_arr = unserialize($data['id_kat_arr']);
				}
				
				// kategorie 1
				$data_kat1 = Db::queryAll('SELECT id, nazev FROM kategorie WHERE aktivni=1 AND vnor=1 ORDER BY razeni ASC', array());
				if($data_kat1 !== false ) 
				{
					 
				    foreach ($data_kat1 as $row_kat1) 
					{
					  
					  echo '<label for="k_'.$row_kat1['id'].'" class="inline"><input type="checkbox" name="id_kat_arr[]" 
					  value="'.$row_kat1['id'].'" id="k_'.$row_kat1['id'].'" ';
					  if($data['id_kat_arr'] && in_array($row_kat1['id'],$kat_arr)){ echo ' checked ';}
					  echo ' required > <b>'.$row_kat1['nazev'].'</b></label>';
					  
					    // kategorie 2
						$data_kat2 = Db::queryAll('SELECT id, nazev FROM kategorie WHERE aktivni=1 AND vnor=2 AND id_nadrazeneho='.$row_kat1['id'].' ORDER BY razeni ASC', array());
						foreach ($data_kat2 as $row_kat2) 
						{
							echo '<label for="k_'.$row_kat2['id'].'" class="inline" style="margin-left: 30px;"><input type="checkbox" name="id_kat_arr[]" 
							value="'.$row_kat2['id'].'" id="k_'.$row_kat2['id'].'" ';
							if($data['id_kat_arr'] && in_array($row_kat2['id'],$kat_arr)){ echo ' checked ';}
							echo ' required > '.$row_kat2['nazev'].'</label>';
							
							 // kategorie 3
							$data_kat3 = Db::queryAll('SELECT id, nazev FROM kategorie WHERE aktivni=1 AND vnor=3 AND id_nadrazeneho='.$row_kat2['id'].' ORDER BY razeni ASC', array());
							foreach ($data_kat3 as $row_kat3) 
							{
								echo '<label for="k_'.$row_kat3['id'].'" class="inline" style="margin-left: 50px;"><input type="checkbox" name="id_kat_arr[]" 
								value="'.$row_kat3['id'].'" id="k_'.$row_kat3['id'].'" ';
								if($data['id_kat_arr'] && in_array($row_kat3['id'],$kat_arr)){ echo ' checked ';}
								echo ' required > '.$row_kat3['nazev'].'</label>';
							}
						}
				  
				    }
				}
					  
				echo $form->addHTML('</div>');
				echo $form->addHTML('</fieldset>');	
				
				// příznaky
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				echo $form->addHTML('<fieldset><legend><h2>Příznaky</h2></legend>');	
				echo '<label for="_novinka" class="inline"><input type="checkbox" name="novinka" value="1" id="_novinka" ';
				if($data['novinka']==1){ echo ' checked ';}
				echo ' >Novinka</label>';
				echo '<label for="_akce" class="inline"><input type="checkbox" name="akce" value="1" id="_akce" ';
				if($data['akce']==1){ echo ' checked ';}
				echo '>Akce</label>';
				echo '<label for="_doporucujeme" class="inline"><input type="checkbox" name="doporucujeme" value="1" id="_doporucujeme" ';
				if($data['doporucujeme']==1){ echo ' checked ';}
				echo '>Doporučujeme</label>';
				echo '<label for="_doprodej" class="inline"><input type="checkbox" name="doprodej" value="1" id="_doprodej" ';
				if($data['doprodej']==1){ echo ' checked ';}
				echo '>Doprodej</label>';
				echo '<label for="_doprava_zdarma" class="inline"><input type="checkbox" name="doprava_zdarma" value="1" id="_doprava_zdarma" ';
				if($data['doprava_zdarma']==1){ echo ' checked ';}
				echo '>Doprava zdarma</label>';
				echo '<label for="_specialni_doprava" class="inline"><input type="checkbox" name="specialni_doprava" value="1" id="_specialni_doprava" ';
				if($data['specialni_doprava']==1){ echo ' checked ';}
				echo '>Speciální doprava</label>';
				echo $form->addHTML('</fieldset>');	
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				
				// technické parametry
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				echo $form->addHTML('<fieldset><legend><h2>Technické parametry</h2></legend>');	
				echo $form->inputSelect('id_sablona','id_sablona',$data['id_sablona'],$sablona_arr,'Šablona technických parametrů','onchange="ZobrazSablonu();"',0,0,'');
				echo $form->addHTML('<div id="tech_par">');

				echo '<table style="width: 600px;" >';
				$data_tp = Db::queryAll('SELECT * FROM produkty_tech_par WHERE id_produkt=? ORDER BY id ASC', array($data['id']));
				foreach ($data_tp as $row_tp) 
				   {
					echo '<tr>
					<td style="width: 40%;"><input type="text" name="tp_n[]" value="'.$row_tp['nazev'].'" readonly ></td>
					<td><input type="text" name="tp_h[]" value="'.stripslashes($row_tp['hodnota']).'"></td>
					<td><a href="./smazat-tp-produkty.php?idp='.$data['id'].'&id_tp='.$row_tp['id'].'" '.__JS_CONFIRM__.'>smazat</a></td>
					</tr>';
				    }
				
				echo '</table>';
			
			
				echo $form->addHTML('</div>');	
				echo $form->addHTML('</fieldset>');	
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				
				// varianty
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				echo $form->addHTML('<fieldset><legend><h2>Varianty</h2></legend>');
				
				echo $form->addHTML('<div class="varianty_obal">');	
				
				//****************************** varianty start *************************
				$cv = 1;
				$data_v = Db::queryAll('SELECT * FROM produkty_varianty WHERE id_produkt=? ORDER BY id ASC', array($data['id']));
				foreach ($data_v as $row_v) 
				{
					if($row_v['sleva'] > 0){$sleva_var = $row_v['sleva'];}
					else{$sleva_var = '';}
					
					if($row_v['sleva_datum_od'] > 0){$datum_od = date('d.m.Y',$row_v['sleva_datum_od']);}
					else{$datum_od = '';}
					
					if($row_v['sleva_datum_do'] > 0){$datum_do = date('d.m.Y',$row_v['sleva_datum_do']);}
					else{$datum_do = '';}
					
					   
				echo $form->addHTML('<div style="float: left; width: 50%;">');	
				echo $form->inputText('nazev_var[]','nazev_var',$row_v['nazev_var'],'<b>Název varianty '.$cv.' 
				<a href="./smazat-produkt-var.php?idv='.$row_v['id'].'" '.__JS_CONFIRM__.'>smazat variantu</a></b>','class="input_req"','',1,0,'');
				echo $form->inputText('cena_A[]','cena_A_'.$row_v['id'],$row_v['cena_A'],'Cena A bez DPH','class="input_req" 
				onkeyup="vypocitej_cenu_s_dph('.__DPH__.',\'cena_A_'.$row_v['id'].'\',\'cena_A_'.$row_v['id'].'_s_dph\');"','',1,0,'&nbsp;&nbsp;orientační cena s DPH: <span id="cena_A_'.$row_v['id'].'_s_dph" style="color: red; text-align: right; border: 0;" >'.round(($row_v['cena_A'] * (__DPH__ / 100 + 1)),2).'</span> ');
				echo $form->inputText('cena_B[]','cena_B',$row_v['cena_B'],'cena B bez DPH','','',0,0,'');
				echo $form->inputText('cena_C[]','cena_C',$row_v['cena_C'],'Cena C bez DPH','','',0,0,'');
				echo $form->inputText('cena_D[]','cena_D',$row_v['cena_D'],'Cena D bez DPH','','',0,0,'');
				echo $form->inputFile('foto_var[]','foto_var','','Foto varianty','',0,0,'jpg, png, gif, webp');
				if($row_v['foto_var'])
				{
					echo $form->addHTML('<div style="width: 250px; text-align: center">
					<img src="/fotky/produkty/male/'.$row_v['foto_var'].'" style="width: 150px;">
					<br><a href="./smazat-foto-var.php?idv='.$row_v['id'].'&f='.$row_v['foto_var'].'" '.__JS_CONFIRM__.'>smazat foto</a>
					</div>');
				}
				echo $form->addHTML('<canvas id="imageCanvas"></canvas>');
				echo $form->addHTML('</div>');
				
				echo $form->addHTML('<div style="float: left; width: 50%;">');
				echo $form->inputText('sleva[]','sleva',$sleva_var,'Sleva','','',0,0,'');
				echo $form->inputText('datum_od[]','od_'.$row_v['id'],$datum_od,'Datum od','class="dat"','',0,0,'');
				echo $form->inputText('datum_do[]','do_'.$row_v['id'],$datum_do,'Datum do','class="dat"','',0,0,'');
				echo $form->inputText('kat_cislo_var[]','kat_cislo_var',$row_v['kat_cislo_var'],'Katalogové číslo varianty','class="input_req"','',1,0,'');
				echo $form->inputText('ean_var[]','ean_var',$row_v['ean_var'],'EAN varianty','','',0,0,'');
				echo $form->inputText('ks_skladem[]','ks_skladem',$row_v['ks_skladem'],'Ks skladem','','',0,0,'');
				echo $form->inputSelect('id_dostupnost[]','id_dostupnost',$row_v['id_dostupnost'],$dostupnost_arr,'Dostupnost','',1,0,'');
				echo $form->inputSelect('aktivni_var[]','aktivni_var',$row_v['aktivni_var'],$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputHidden('id_var[]','id_var',$row_v['id'],'',0);
				echo $form->addHTML('</div>');
				
				echo $form->addHTML('<div class="clear" style="height: 5px;"></div>');
				echo $form->addHTML('<div class="cara"></div>');
				echo $form->addHTML('<div class="clear" style="height: 5px;"></div>');
				
				$cv++;
			    
			    }
				
				//****************************** varianty stop *************************
 
				
				echo $form->addHTML('</div>');
				
				echo $form->addHTML('<div id="dalsi_varianty"></div>');
				echo $form->addHTML('<div class="clear" style="height: 5px;"></div>');
				echo $form->addHTML('<div style="height: 20px; text-align: right;"><a href="javascript:DalsiVarianta('.$cv.')">Přidat další variantu</a></div>');	
				echo $form->addHTML('</fieldset>');	
				

				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				
				
				// foto - multi - pole
				echo $form->addHTML('<fieldset><legend><h2>Fotky</h2></legend>');	    
				echo $form->inputFile('foto[]', 'files', '', 'Přidejte fotografie', 'multiple accept="image/x-png,image/gif,image/jpeg"', 0, 0,'');
			    echo $form->addHTML('<output id="list"></output>');
			    
			    // fotky
				$data_fp = Db::queryAll('SELECT * FROM produkty_foto WHERE id_produkt='.$data['id'].' ORDER BY razeni', array());
				if($data_fp !== false ) 
		        {
				   echo $form->addHTML('<div class="clear" style="height: 5px;"></div>');
				   echo 'řazení přetažením<div style="width: auto; clear: both;"><ul id="list_of_tasks" class="ui-sortable"> ';	
				   foreach ($data_fp as $row_fp) 
					{
					   echo '<li id="page_'.$row_fp['id'].'"><div class="datablock">
					   <img src="/fotky/produkty/male/'.$row_fp['foto'].'" style="width: 150px;';
					   if($row_fp['typ']==1){echo 'border: solid 2px red;';}
					   else{echo 'border: solid 2px white;';}
					   echo '" id="f_'.$data['id'].'_'.$row_fp['id'].'" class="fd">
					   <br><a href="./smazat-foto-produkt.php?id='.$row_fp['id'].'&id_p='.$data['id'].'&f='.$row_fp['foto'].'" '.__JS_CONFIRM__.'>smazat  foto</a>
					   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;hlavní <input type="radio" class="hlavni_foto" name="hlavni_foto[]"  value="'.$data['id'].'_'.$row_fp['id'].'" ';
					   if($row_fp['typ']==1){echo ' checked ';}
					   echo '>
					   <br>
					   <div class="clear" style="height: 5px;"></div>
					   <input type="text" name="_foto_popis['.$row_fp['id'].']" id="_foto_popis'.$row_fp['id'].'" style="min-width: 80px;" value="'.$row_fp['popis'].'" placeholder="popis fotky">
					   </div></li>';
					   
 
						
					}
					
					echo '</ul></div>';
				}

				echo $form->addHTML('</fieldset>');
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');
				
				
				// přílohy - multi - pole
				echo $form->addHTML('<fieldset><legend><h2>Přílohy</h2></legend>');
				echo $form->inputFile('prilohy[]', 'prilohy', '', 'Přílohy', 'multiple accept="*"', 0, 0,'');
				echo $form->addHTML('<div class="clear" style="height: 5px;"></div>');
				
				$data_pr = Db::queryAll('SELECT * FROM produkty_prilohy WHERE id_produkt='.$data['id'].' ORDER BY razeni', array());
				if($data_pr !== false ) 
		        {
					echo $form->addHTML('<div class="clear" style="height: 5px;"></div>');
				    echo 'řazení přetažením<div style="width: auto; clear: both;"><ul id="list_of_tasks2" class="ui-sortable"> ';	
				    foreach ($data_pr as $row_pr) 
					{
					   echo '<li id="page_'.$row_pr['id'].'"><div class="datablock">
					   <img src="./img/priloha.png" style="width: 30px;">
					   <br>'.$row_pr['priloha'].'
					   <br><a href="./smazat-prilohu-produkt.php?id='.$row_pr['id'].'&id_p='.$data['id'].'&pr='.$row_pr['priloha'].'" '.__JS_CONFIRM__.'>smazat  přílohu</a>
					   <br>
					   <div class="clear" style="height: 5px;"></div>
					   <input type="text" name="_priloha_popis['.$row_pr['id'].']" id="_priloha_popis'.$row_pr['id'].'" 
					   style="min-width: 80px;" value="'.$row_pr['nazev'].'" placeholder="název přílohy">
					   </div></li>';
					   
 
						
					}
					
					echo '</ul></div>';
				}

				echo $form->addHTML('</fieldset>');
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');
				
				// *********************** doporučené příslušenství start **************************
				
				echo $form->addHTML('<fieldset><legend><h2>Doporučené příslušenství</h2></legend>');
				
				// kategorie
				$data_kat_dp_1 = Db::queryAll('SELECT id, nazev FROM kategorie WHERE aktivni=1 AND vnor=1 ORDER BY razeni ASC', array());
				if($data_kat_dp_1 !== false ) 
				{
					$kat_dop_pr_arr[0] = 'vyberte';
					
					foreach ($data_kat_dp_1 as $row_kat_dp_1) 
					{		
							$kat_dop_pr_arr[$row_kat_dp_1['id']] = $row_kat_dp_1['nazev'];	
						
						    $data_kat_dp_2 = Db::queryAll('SELECT id, nazev FROM kategorie WHERE aktivni=1 AND vnor=2 AND id_nadrazeneho='.$row_kat_dp_1['id'].' ORDER BY razeni ASC', array());
							foreach ($data_kat_dp_2 as $row_kat_dp_2) 
							{	
									$kat_dop_pr_arr[$row_kat_dp_2['id']] = '&nbsp;&nbsp;&nbsp;'.$row_kat_dp_2['nazev'];	
									
									$data_kat_dp_3 = Db::queryAll('SELECT id, nazev FROM kategorie WHERE aktivni=1 AND vnor=3 AND id_nadrazeneho='.$row_kat_dp_2['id'].' ORDER BY razeni ASC', array());
									foreach ($data_kat_dp_3 as $row_kat_dp_3) 
									{
										
										$kat_dop_pr_arr[$row_kat_dp_3['id']] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row_kat_dp_3['nazev'];	
										
									}
								
							}
					   
					}
					
					echo $form->addHTML('<div style="float: left; width: 30%;">');
					echo $form->inputSelect('id_kat_dp','id_kat_dp','',$kat_dop_pr_arr,'Kategorie','onchange="VygenerujProdukty('.$data['id'].')"',0,0,'');
					echo $form->addHTML('</div>');
					
					echo $form->addHTML('<div style="float: left; width: 50%;" id="selbox_produkty"></div>');
					echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');
					echo $form->addHTML('<div id="dop_pr_vypis">');
					
					if($data['prislusenstvi_id_arr'])
					{
					  $prislusenstvi_id_arr = unserialize($data['prislusenstvi_id_arr']);
	   
					   $data_pr = Db::queryAll('SELECT P.id, P.nazev, F.foto 
					      FROM produkty P
						  LEFT JOIN produkty_foto F ON F.id_produkt=P.id 
						  WHERE P.id IN('.implode(",",$prislusenstvi_id_arr).') AND F.typ=1 GROUP BY P.id ORDER BY P.id ', array());
							if($data_pr !== false ) 
					        {
							
							   foreach ($data_pr as $row_pr) 
								{
									   echo '<div class="box_pr_vypis">
									   <div class="bdv_in"><img src="/fotky/produkty/male/'.$row_pr['foto'].'" style="width: 150px;"></div>
									   <br>
									   <a href="./index.php?p=produkty&action=update&id='.$row_pr['id'].'" target="_blank">'.$row_pr['nazev'].'</a>
									   <br>
									    <a href="javascript:OdstranitPrislusenstvi('.$_GET['id'].','.$row_pr['id'].');ZobrazitPrislusenstvi('.$_GET['id'].');">odstranit z příslušenství</a>
									  </div>';
								}
							}
					}
					
					echo $form->addHTML('</div>');
				}
				else
				{
					$kat_dop_pr_arr = false;
				}
				
				echo $form->addHTML('<div class="dop_prislusenstvi_obal"></div>');	
				 
				 echo $form->addHTML('</fieldset>');
				 
				// *********************** doporučené příslušenství end ************************** 
				
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');
				
				// kategorie 1 zboží
				$data_kat_zbozi1 = Db::queryAll('SELECT id, nazev FROM kategorie_zbozi WHERE vnor=1 ORDER BY nazev ASC', array());
				$category_text_zbozi_arr = array();
				if($data_kat_zbozi1 !== false ) 
				{
					 $category_text_zbozi_arr[0] = 'vyberte';
					 
				    foreach ($data_kat_zbozi1 as $row_kat_zbozi1) 
					{
					    $category_text_zbozi_arr[$row_kat_zbozi1['id']] = $row_kat_zbozi1['nazev'];
					  
					    // kategorie 2
						$data_kat_zbozi2 = Db::queryAll('SELECT id, nazev FROM kategorie_zbozi WHERE vnor=2 AND id_nad='.$row_kat_zbozi1['id'].' ORDER BY nazev ASC', array());
						foreach ($data_kat_zbozi2 as $row_kat_zbozi2) 
						{
							$category_text_zbozi_arr[$row_kat_zbozi2['id']] = '&nbsp;&nbsp;&nbsp;'.$row_kat_zbozi2['nazev'];
							
							 // kategorie 3
							$data_kat_zbozi3 = Db::queryAll('SELECT id, nazev FROM kategorie_zbozi WHERE vnor=3 AND id_nad='.$row_kat_zbozi2['id'].' ORDER BY nazev ASC', array());
							foreach ($data_kat_zbozi3 as $row_kat_zbozi3) 
							{
								 $category_text_zbozi_arr[$row_kat_zbozi3['id']] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row_kat_zbozi3['nazev'];
							}
						}
				  
				    }
				}
				
				// zboží.cz
				echo $form->addHTML('<label for="zbozi_cz_par"><input type="checkbox" name="zbozi" id="zbozi_cz_par" value="1" onclick="ZobrazSEO(\'zbozi_parametry\');" > Zobrazit parametry pro Zboží.cz</label><div id="zbozi_parametry" style="display: none;">');	
				echo $form->inputText('nazev_zbozi','nazev_zbozi',$data['nazev_zbozi'],'Název Zboží.cz','','',0,0,'');
				echo $form->inputText('product','product',$data['product'],'Product Zboží.cz','','',0,0,'');
				echo $form->inputSelect('id_category_text_zbozi','id_category_text_zbozi',$data['id_category_text_zbozi'],$category_text_zbozi_arr,'Category text Zboží.cz','',0,0,'');
				echo $form->inputSelect('id_category_text_zbozi2','id_category_text_zbozi2',$data['id_category_text_zbozi2'],$category_text_zbozi_arr,'Category text 2 Zboží.cz','',0,0,'');
				echo $form->inputText('productno','productno',$data['productno'],'Produktový kód výrobce','','',0,0,'');
				echo $form->inputText('extramessage','extramessage',$data['extramessage'],'Extra zpráva 1','','',0,0,'');
				echo $form->inputText('extramessage2','extramessage2',$data['extramessage2'],'Extra zpráva 1','','',0,0,'');
				echo $form->inputText('extramessage3','extramessage3',$data['extramessage3'],'Extra zpráva 1','','',0,0,'');
				echo $form->inputText('brand','brand',$data['brand'],'Brand','','',0,0,'');
				echo $form->inputText('vydejnimisto','vydejnimisto',$data['vydejnimisto'],'ID výdejního místa','','',0,0,'');
				echo $form->inputText('vydejnimisto2','vydejnimisto2',$data['vydejnimisto2'],'ID výdejního místa','','',0,0,'');
				echo $form->inputText('vydejnimisto3','vydejnimisto3',$data['vydejnimisto3'],'ID výdejního místa','','',0,0,'');
				echo $form->inputText('productline','productline',$data['productline'],'Produktová řada','','',0,0,'');
				echo $form->inputText('maxcpc','maxcpc',$data['maxcpc'],'MAX CPC','','',0,0,'');
				echo $form->inputText('maxcpc_search','maxcpc_search',$data['maxcpc_search'],'MAX CPC Search','','',0,0,'');
				echo $form->inputText('itemgroupid','itemgroupid',$data['itemgroupid'],'ITEM GROUP ID','','',0,0,'');
				
				echo $form->inputText('visibility','visibility',$data['visibility'],'VISIBILITY','','',0,0,'');
				echo $form->inputText('CUSTOM_LABEL_0','CUSTOM_LABEL_0',$data['CUSTOM_LABEL_0'],'CUSTOM LABEL 0','','',0,0,'');
				echo $form->inputText('CUSTOM_LABEL_1','CUSTOM_LABEL_1',$data['CUSTOM_LABEL_1'],'CUSTOM LABEL 1','','',0,0,'');
				echo $form->inputText('CUSTOM_LABEL_2','CUSTOM_LABEL_2',$data['CUSTOM_LABEL_2'],'CUSTOM LABEL 2','','',0,0,'');
				echo $form->inputText('CUSTOM_LABEL_3','CUSTOM_LABEL_3',$data['CUSTOM_LABEL_3'],'CUSTOM LABEL 3','','',0,0,'');
				
				echo $form->addHTML('</div>');
				
				// SEO parametry
				echo $form->addHTML('<label for="seoparam"><input type="checkbox" name="seoparam" id="seoparam" value="1" onclick="ZobrazSEO(\'seoparametry\');" > Zobrazit SEO parametry</label><div id="seoparametry" style="display: none;">');	
				echo $form->inputText('nazev_heureka','nazev_heureka',$data['nazev_heureka'],'Název Heureka','','',0,0,'');
				echo $form->inputText('category_text_heureka','category_text_heureka',$data['category_text_heureka'],'Category text Heureka','','',0,0,'');
				echo $form->inputText('title','title',$data['title'],'Title','','',0,0,'');
				echo $form->inputText('keywords','keywords',$data['keywords'],'Keywords','','',0,0,'');
				echo $form->inputText('description','description',$data['description'],'Description','','',0,0,'');
				echo $form->inputText('og_title','og_title',$data['og_title'],'OG Title','','',0,0,'');
				echo $form->inputText('og_description','og_description',$data['og_description'],'OG Description','','',0,0,'');
				echo $form->inputText('og_site_name','og_site_name',$data['og_site_name'],'OG SiteName','','',0,0,'');
				echo $form->inputText('og_url','og_url',$data['og_url'],'OG URL','','',0,0,'');
				echo $form->inputText('og_image','og_image',$data['og_image'],'OG Image','','',0,0,'absolutní adresa k obrázku');
				echo $form->inputSelect('indexovani','indexovani',$data['indexovani'],$stav_adm_sluzby_arr,'Indexování','',0,0,'');
				echo $form->addHTML('</div>');
				
				echo $form->inputTextarea('poznamka','poznamka',$data['poznamka'],'Interní poznámka k produktu','','',0,0,'');
				
				echo $form->inputSelect('aktivni','aktivni',$data['aktivni'],$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputHidden('id','id',intval($_GET['id']),'',0);
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
				
				 
				echo '</fieldset>';
				echo '<div class="clear" style="height: 10px;"></div>';
				
			}
			else
			{
				echo '<div class="alert-warning">Chyba!<br>Žádný záznam v databázi pro id '.strip_tags($_GET['id']).'<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
			}
		    
			
		}
		else
		{
			echo '<div class="alert-warning">Chyba!<br>Chybí ID záznamu<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
		}
		    
			
	}
	


}
elseif($_GET['action']=='insert')
{    
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 1);
	$form->required = 'nazev,popis_kratky,popis,id_kat_arr,id_dph,nazev_var,cena_A,kat_cislo_var';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
				if(is_array($_POST['id_kat_arr']))
				{
				    $id_kat_arr = serialize($_POST['id_kat_arr']);
				}
				else
				{
					$id_kat_arr = '';
				}
							
			    $data_insert = array(
							    'id_kat_arr' => $id_kat_arr,
							    'id_sablona' => intval($_POST['id_sablona']),
							    'id_vyrobce' => intval($_POST['id_vyrobce']),
							    'id_dph' => intval($_POST['id_dph']),
							    'str' => bez_diakritiky($_POST['nazev']),
								'nazev' => $_POST['nazev'],
								'popis_kratky' => $_POST['popis_kratky'],
								'popis' => $_POST['popis'],
								'poznamka' => $_POST['poznamka'],
								'jednotka' => $_POST['jednotka'],
								'zaruka' => $_POST['zaruka'],
								'novinka' => intval($_POST['novinka']),
								'akce' => intval($_POST['akce']),
								'doporucujeme' => intval($_POST['doporucujeme']),
								'doprodej' => intval($_POST['doprodej']),
								'doprava_zdarma' => intval($_POST['doprava_zdarma']),
								'specialni_doprava' => intval($_POST['specialni_doprava']),
								'datum' => time(),
								'aktivni' => intval($_POST['aktivni']),
								
								'keywords' => $_POST['keywords'],
								'title' => $_POST['title'],
								'description' => $_POST['description'],
								'og_title' => $_POST['og_title'],
								'og_description' => $_POST['og_description'],
								'og_site_name' => $_POST['og_site_name'],
								'og_url' => $_POST['og_url'],
								'og_image' => $_POST['og_image'],
								'indexovani' => $_POST['indexovani'],
								'nazev_heureka' => $_POST['nazev_heureka'],
								'category_text_heureka' => $_POST['category_text_heureka'],
								
								'id_category_text_zbozi' => $_POST['category_text_zbozi'],
								'id_category_text_zbozi2' => $_POST['category_text_zbozi2'],
								'productno' => $_POST['productno'],
								'extramessage' => $_POST['extramessage'],
								'extramessage2' => $_POST['extramessage2'],
								'extramessage3' => $_POST['extramessage3'],
								'brand' => $_POST['brand'],
								'vydejnimisto' => $_POST['vydejnimisto'],
								'vydejnimisto2' => $_POST['vydejnimisto2'],
								'vydejnimisto3' => $_POST['vydejnimisto3'],
								'productline' => $_POST['productline'],
								'maxcpc' => $_POST['maxcpc'],
								'maxcpc_search' => $_POST['maxcpc_search'],
								'itemgroupid' => $_POST['itemgroupid'],
								'visibility' => $_POST['visibility'],
								'CUSTOM_LABEL_0' => $_POST['CUSTOM_LABEL_0'],
								'CUSTOM_LABEL_1' => $_POST['CUSTOM_LABEL_1'],
								'CUSTOM_LABEL_2' => $_POST['CUSTOM_LABEL_2'],
								'CUSTOM_LABEL_3' => $_POST['CUSTOM_LABEL_3'],
								'nazev_zbozi' => $_POST['nazev_zbozi'],
								'product' => $_POST['product']
								

							     );
	          
							     
				$query_insert = Db::insert('produkty', $data_insert);
                
                
                // technické parametry
                if(is_array($_POST['tp_n']))
                {
					// musíme zjistit ID kategorie nejnižší úrovně
					if($_POST['id_kat_arr'])
					{
						$data_k = Db::queryRow('SELECT id FROM kategorie WHERE id IN('.implode(",",$_POST['id_kat_arr']).') ORDER BY vnor DESC LIMIT 1 ', array());
						if($data_k) 
						{
						    $kategorie_sablona = $data_k['id'];
						}
						else
						{
							$kategorie_sablona = 0;
						}
				    }
				   $tpx = 0;	
				   foreach($_POST['tp_n'] as $tp_n_k=>$tp_n_v)
				   {
				      
				      $data_insert_tp = array(
							    'id_produkt' => $query_insert,
							    'id_sablona' => intval($_POST['id_sablona']),
							    'id_kategorie' => intval($kategorie_sablona),
							    'nazev' => sanitize($tp_n_v),
							    'hodnota' => sanitize($_POST['tp_h'][$tpx])
							     );
	                 $query_insert_tp = Db::insert('produkty_tech_par', $data_insert_tp);
				     $tpx++;
				   }
				}
                
                // přílohy
                if($_FILES['prilohy']['size'][0])
				{
					
					prilohy_produkt('prilohy',$query_insert);
					
				}
                
                // fotky
                if($_FILES['foto']['size'][0])
				{
					
					foto_produkt('foto',500,1200,$query_insert,$_POST['nazev']);
					
				}
                
                // varianty + foto
                if($_POST['nazev_var'])
                {
				   $vx = 0;	
				   foreach($_POST['nazev_var'] as $nv_k=>$nv_v)
				   {
				      // kontrola cen a vyměna čárky za tečku
				      $cena_A = str_replace(',','.',$_POST['cena_A'][$vx]);
				      
				      if($_POST['cena_B'][$vx])
				      {
					     $cena_B = str_replace(',','.',$_POST['cena_B'][$vx]);
					  }
					  else
					  {
						 $cena_B = $cena_A;
					  }
					  
					  if($_POST['cena_C'][$vx])
				      {
					     $cena_C = str_replace(',','.',$_POST['cena_C'][$vx]);
					  }
					  else
					  {
						 $cena_C = $cena_A;
					  }
					  
					  if($_POST['cena_D'][$vx])
				      {
					     $cena_D = str_replace(',','.',$_POST['cena_D'][$vx]);
					  }
					  else
					  {
						 $cena_D = $cena_A;
					  }
					  
					  
				      
				      $data_insert_v = array(
							    'id_produkt' => $query_insert,
							    'id_dostupnost' => intval($_POST['id_dostupnost'][$vx]),
							    'nazev_var' => $_POST['nazev_var'][$vx],
							    'cena_A' => $cena_A,
							    'cena_B' => $cena_B,
							    'cena_C' => $cena_C,
							    'cena_D' => $cena_D,
							    'datum' => time(),
							    'sleva' => intval($_POST['sleva'][$vx]),
							    'kat_cislo_var' => $_POST['kat_cislo_var'][$vx],
							    'ean_var' => $_POST['ean_var'][$vx],
							    'ks_skladem' => $_POST['ks_skladem'][$vx],
							    'aktivni_var' => intval($_POST['aktivni_var'][$vx])
							     );
							     
					 // foto varianty
					 if($_FILES['foto_var']['size'][$vx])
				     {
					   $foto_var = foto_produkt_varianta('foto_var',$vx,$query_insert,300,800);
					   $data_insert_v['foto_var'] = $foto_var;
					 }
					 
					 // datumy slevy od - do
					  if($_POST['datum_od'][$vx])
					  {
						list($den,$mesic,$rok) = explode(".",$_POST['datum_od'][$vx]);
						 $hodina = 1;
						 $minuta = 1;
						 $sekunda = 1;
						 $datum_od = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);  
						$data_insert_v['sleva_datum_od'] = $datum_od;
					  }
					  
					  if($_POST['datum_do'][$vx])
					  {
						list($den,$mesic,$rok) = explode(".",$_POST['datum_do'][$vx]);
						 $hodina = 1;
						 $minuta = 1;
						 $sekunda = 1;
						 $datum_do = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);  
						$data_insert_v['sleva_datum_do'] = $datum_do;
					  }
					 		     
	                 $query_insert_v = Db::insert('produkty_varianty', $data_insert_v);
					 $vx++;	
			       }
				  
				}
                
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Produkty - přidání záznamu: '.strip_tags($_POST['nazev']),
							    'url' => sanitize($_SERVER['HTTP_REFERER']),
							    'ip' => sanitize(getip()),
							    'datum' => time()
							     );
					     
	            $query_insert_log = Db::insert('admin_log_udalosti', $data_insert_log);
				
				echo '<div class="alert-success">Uloženo!<br>Záznam byl v pořádku uložen<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
		    }
		    else
		    {
			   echo '<div class="alert-warning">Chyba!<br>Nejsou vyplněny všechny povinné položky<br>';
			   foreach($form->errors as $ke=>$ve){ echo $ve.'<br>'; }
			   echo '<a href="'.$_SERVER['HTTP_REFERER'].'">Přejít zpět</a><br>
			   <a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
		    }
	}
	else
	{			
				// výrobce
				$data_vyr = Db::queryAll('SELECT id,vyrobce FROM produkty_vyrobci WHERE aktivni=1 ORDER BY vyrobce ASC', array());
				$vyr_arr = array();
				if($data_vyr !== false ) 
				{
					$vyr_arr[0] = 'vyberte';	
					 
				    foreach ($data_vyr as $row_vyr) 
					{
					  $vyr_arr[$row_vyr['id']] = $row_vyr['vyrobce'];	
					}
				}
				else
				{
					$vyr_arr = false;
				}
				
				// dph
				$data_dph = Db::queryAll('SELECT * FROM dph WHERE 1 ORDER BY dph ASC', array());
				$dph_arr = array();
				if($data_dph !== false ) 
				{
					 
				    foreach ($data_dph as $row_dph) 
					{
					  $dph_arr[$row_dph['id']] = $row_dph['dph'];	
					}
				}
				else
				{
					$dph_arr = false;
				}
				
				$id_dph = array_search (__DPH__, $dph_arr);
				
				
				// sablona tech. par
				$data_sab = Db::queryAll('SELECT * FROM sablony_tech_par WHERE 1 ORDER BY nazev ASC', array());
				$sablona_arr = array();
				if($data_sab !== false ) 
				{
					$sablona_arr[0] = 'vyberte';
					 
				    foreach ($data_sab as $row_sab) 
					{
					  $sablona_arr[$row_sab['id']] = $row_sab['nazev'];	
					}
				}
				else
				{
					$sablona_arr = false;
				}
				
				
				// dostupnost
				$data_dost = Db::queryAll('SELECT * FROM produkty_dostupnost WHERE 1 ORDER BY id ASC', array());
				$dostupnost_arr = array();
				if($data_dost !== false ) 
				{
					$dostupnost_arr[0] = 'vyberte';
					 
				    foreach ($data_dost as $row_dost) 
					{
					  $dostupnost_arr[$row_dost['id']] = $row_dost['dostupnost'];	
					}
				}
				else
				{
					$dostupnost_arr = false;
				}
		

			    echo $form->formOpen();
				echo $form->inputText('nazev','nazev','','Název','class="input_req" style="min-width: 500px;"','',1,0,'&nbsp;<span id="kontrola_produkt"></span>');
				echo $form->inputSelect('id_vyrobce','id_vyrobce','',$vyr_arr,'Výrobce','',0,0,'');
				echo $form->inputTextarea('popis_kratky','popis_kratky','','Popis krátky','class="input_req"','',1,0,'');
				echo $form->addHTML('<script type="text/javascript"> aktivujTinyMCE(\'popis\',300) </script>');
				echo $form->inputTextarea('popis','popis','','Popis','','',1,0,'');
				echo $form->inputSelect('id_dph','id_dph',$id_dph,$dph_arr,'DPH','',1,0,'');
				echo $form->inputText('jednotka','jednotka','','Jednotka','','',0,0,'');
				echo $form->inputText('zaruka','zaruka','','Záruka','','',0,0,'');
	
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				echo $form->addHTML('<fieldset><legend><h2>Zařazení do kategorií</h2></legend>');	
				echo $form->addHTML('<label for="kat"><input type="checkbox" name="kat" id="kat" value="1" checked onclick="ZobrazSEO(\'_kat\');" > Zařazení do kategorií</label>
				<div id="_kat" style="display: block;">');	
				
				// kategorie 1
				$data_kat1 = Db::queryAll('SELECT id, nazev FROM kategorie WHERE aktivni=1 AND vnor=1 ORDER BY razeni ASC', array());
				if($data_kat1 !== false ) 
				{
					 
				    foreach ($data_kat1 as $row_kat1) 
					{
					  
					  echo '<label for="k_'.$row_kat1['id'].'" class="inline"><input type="checkbox" name="id_kat_arr[]" 
					  value="'.$row_kat1['id'].'" id="k_'.$row_kat1['id'].'" required > <b>'.$row_kat1['nazev'].'</b></label>';
					  
					    // kategorie 2
						$data_kat2 = Db::queryAll('SELECT id, nazev FROM kategorie WHERE aktivni=1 AND vnor=2 AND id_nadrazeneho='.$row_kat1['id'].' ORDER BY razeni ASC', array());
						foreach ($data_kat2 as $row_kat2) 
						{
							echo '<label for="k_'.$row_kat2['id'].'" class="inline" style="margin-left: 30px;"><input type="checkbox" name="id_kat_arr[]" 
							value="'.$row_kat2['id'].'" id="k_'.$row_kat2['id'].'" required > '.$row_kat2['nazev'].'</label>';
							
							 // kategorie 3
							$data_kat3 = Db::queryAll('SELECT id, nazev FROM kategorie WHERE aktivni=1 AND vnor=3 AND id_nadrazeneho='.$row_kat2['id'].' ORDER BY razeni ASC', array());
							foreach ($data_kat3 as $row_kat3) 
							{
								echo '<label for="k_'.$row_kat3['id'].'" class="inline" style="margin-left: 50px;"><input type="checkbox" name="id_kat_arr[]" 
								value="'.$row_kat3['id'].'" id="k_'.$row_kat3['id'].'" required > '.$row_kat3['nazev'].'</label>';
							}
						}
				  
				    }
				}
					  
				echo $form->addHTML('</div>');
				echo $form->addHTML('</fieldset>');	
				
				// příznaky
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				echo $form->addHTML('<fieldset><legend><h2>Příznaky</h2></legend>');	
				echo '<label for="_novinka" class="inline"><input type="checkbox" name="novinka" value="1" id="_novinka">Novinka</label>';
				echo '<label for="_akce" class="inline"><input type="checkbox" name="akce" value="1" id="_akce">Akce</label>';
				echo '<label for="_doporucujeme" class="inline"><input type="checkbox" name="doporucujeme" value="1" id="_doporucujeme">Doporučujeme</label>';
				echo '<label for="_doprodej" class="inline"><input type="checkbox" name="doprodej" value="1" id="_doprodej">Doprodej</label>';
				echo '<label for="_doprava_zdarma" class="inline"><input type="checkbox" name="doprava_zdarma" value="1" id="_ddoprava_zdarma">Doprava zdarma</label>';
				echo '<label for="_specialni_doprava" class="inline"><input type="checkbox" name="specialni_doprava" value="1" id="_specialni_doprava">Speciální doprava</label>';
				echo $form->addHTML('</fieldset>');	
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				
				// technické parametry
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				echo $form->addHTML('<fieldset><legend><h2>Technické parametry</h2></legend>');	
				echo $form->inputSelect('id_sablona','id_sablona','',$sablona_arr,'Šablona technických parametrů','onchange="ZobrazSablonu();"',0,0,'');
				echo $form->addHTML('<div id="tech_par"></div>');	
				echo $form->addHTML('</fieldset>');	
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				
				// varianty
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				echo $form->addHTML('<fieldset><legend><h2>Varianty</h2></legend>');
				
				echo $form->addHTML('<div class="varianty_obal">');	
				echo $form->addHTML('<div style="float: left; width: 50%;">');	
				echo $form->inputText('nazev_var[]','nazev_var','','<b>Název varianty 1</b>','class="input_req"','',1,0,'');
				echo $form->inputText('cena_A[]','cena_A','','Cena A bez DPH','class="input_req" onkeyup="vypocitej_cenu_s_dph('.__DPH__.',\'cena_A\',\'cena_A_s_dph\');"','',1,0,
				'&nbsp;&nbsp;orientační cena s DPH: <span id="cena_A_s_dph" style="color: red; text-align: right; border: 0;" ></span> ');
				echo $form->inputText('cena_B[]','cena_B','','cena B bez DPH','','',0,0,'');
				echo $form->inputText('cena_C[]','cena_C','','Cena C bez DPH','','',0,0,'');
				echo $form->inputText('cena_D[]','cena_D','','Cena D bez DPH','','',0,0,'');
				echo $form->inputFile('foto_var[]','foto_var','','Foto varianty','',0,0,'jpg, png, gif, webp');
				echo $form->addHTML('<canvas id="imageCanvas"></canvas>');
				echo $form->addHTML('</div>');
				
				echo $form->addHTML('<div style="float: left; width: 50%;">');
				echo $form->inputText('sleva[]','sleva','','Sleva','','',0,0,'');
				echo $form->inputText('datum_od[]','od','','Datum od','class="dat"','',0,0,'');
				echo $form->inputText('datum_do[]','do','','Datum do','class="dat"','',0,0,'');
				echo $form->inputText('kat_cislo_var[]','kat_cislo_var','','Katalogové číslo varianty','class="input_req"','',1,0,'');
				echo $form->inputText('ean_var[]','ean_var','','EAN varianty','','',0,0,'');
				echo $form->inputText('ks_skladem[]','ks_skladem','','Ks skladem','','',0,0,'');
				echo $form->inputSelect('id_dostupnost[]','id_dostupnost','',$dostupnost_arr,'Dostupnost','',1,0,'');
				echo $form->inputSelect('aktivni_var[]','aktivni_var',1,$stav_adm_arr,'Stav','',0,0,'');
				echo $form->addHTML('</div>');
				echo $form->addHTML('</div>');
				
				echo $form->addHTML('<div id="dalsi_varianty"></div>');
				echo $form->addHTML('<div class="clear" style="height: 5px;"></div>');
				echo $form->addHTML('<div style="height: 20px; text-align: right;"><a href="javascript:DalsiVarianta(0)">Přidat další variantu</a></div>');	
				echo $form->addHTML('</fieldset>');	
				
				

				
					
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				
				// foto - multi - pole
					    
				echo $form->inputFile('foto[]', 'files', '', 'Přidejte fotografie', 'multiple accept="image/x-png,image/gif,image/jpeg"', 0, 0,'');
			    echo $form->addHTML('<output id="list"></output>');
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');
				
				
				// přílohy - multi - pole
				echo $form->inputFile('prilohy[]', 'prilohy', '', 'Přílohy', 'multiple accept="*"', 0, 0,'');
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');
				
				
				// kategorie 1 zboží
				$data_kat_zbozi1 = Db::queryAll('SELECT id, nazev FROM kategorie_zbozi WHERE vnor=1 ORDER BY nazev ASC', array());
				$category_text_zbozi_arr = array();
				if($data_kat_zbozi1 !== false ) 
				{
					 $category_text_zbozi_arr[0] = 'vyberte';
					 
				    foreach ($data_kat_zbozi1 as $row_kat_zbozi1) 
					{
					    $category_text_zbozi_arr[$row_kat_zbozi1['id']] = $row_kat_zbozi1['nazev'];
					  
					    // kategorie 2
						$data_kat_zbozi2 = Db::queryAll('SELECT id, nazev FROM kategorie_zbozi WHERE vnor=2 AND id_nad='.$row_kat_zbozi1['id'].' ORDER BY nazev ASC', array());
						foreach ($data_kat_zbozi2 as $row_kat_zbozi2) 
						{
							$category_text_zbozi_arr[$row_kat_zbozi2['id']] = '&nbsp;&nbsp;&nbsp;'.$row_kat_zbozi2['nazev'];
							
							 // kategorie 3
							$data_kat_zbozi3 = Db::queryAll('SELECT id, nazev FROM kategorie_zbozi WHERE vnor=3 AND id_nad='.$row_kat_zbozi2['id'].' ORDER BY nazev ASC', array());
							foreach ($data_kat_zbozi3 as $row_kat_zbozi3) 
							{
								 $category_text_zbozi_arr[$row_kat_zbozi3['id']] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row_kat_zbozi3['nazev'];
							}
						}
				  
				    }
				}
				
				// zboží.cz
				echo $form->addHTML('<label for="zbozi_cz_par"><input type="checkbox" name="zbozi" id="zbozi_cz_par" value="1" onclick="ZobrazSEO(\'zbozi_parametry\');" > Zobrazit parametry pro Zboží.cz</label><div id="zbozi_parametry" style="display: none;">');	
				echo $form->inputText('nazev_zbozi','nazev_zbozi','','Název Zboží.cz','','',0,0,'');
				echo $form->inputText('product','product','','Product Zboží.cz','','',0,0,'');
				echo $form->inputSelect('id_category_text_zbozi','id_category_text_zbozi','',$category_text_zbozi_arr,'Category text Zboží.cz','',0,0,'');
				echo $form->inputSelect('id_category_text_zbozi2','id_category_text_zbozi2','',$category_text_zbozi_arr,'Category text 2 Zboží.cz','',0,0,'');
				echo $form->inputText('productno','productno','','Produktový kód výrobce','','',0,0,'');
				echo $form->inputText('extramessage','extramessage','','Extra zpráva 1','','',0,0,'');
				echo $form->inputText('extramessage2','extramessage2','','Extra zpráva 1','','',0,0,'');
				echo $form->inputText('extramessage3','extramessage3','','Extra zpráva 1','','',0,0,'');
				echo $form->inputText('brand','brand','','Brand','','',0,0,'');
				echo $form->inputText('vydejnimisto','vydejnimisto','','ID výdejního místa','','',0,0,'');
				echo $form->inputText('vydejnimisto2','vydejnimisto2','','ID výdejního místa','','',0,0,'');
				echo $form->inputText('vydejnimisto3','vydejnimisto3','','ID výdejního místa','','',0,0,'');
				echo $form->inputText('productline','productline','','Produktová řada','','',0,0,'');
				echo $form->inputText('maxcpc','maxcpc','','MAX CPC','','',0,0,'');
				echo $form->inputText('maxcpc_search','maxcpc_search','','MAX CPC Search','','',0,0,'');
				echo $form->inputText('itemgroupid','itemgroupid','','ITEM GROUP ID','','',0,0,'');
				
				echo $form->inputText('visibility','visibility',1,'VISIBILITY','','',0,0,'');
				echo $form->inputText('CUSTOM_LABEL_0','CUSTOM_LABEL_0','','CUSTOM LABEL 0','','',0,0,'');
				echo $form->inputText('CUSTOM_LABEL_1','CUSTOM_LABEL_1','','CUSTOM LABEL 1','','',0,0,'');
				echo $form->inputText('CUSTOM_LABEL_2','CUSTOM_LABEL_2','','CUSTOM LABEL 2','','',0,0,'');
				echo $form->inputText('CUSTOM_LABEL_3','CUSTOM_LABEL_3','','CUSTOM LABEL 3','','',0,0,'');
				
				echo $form->addHTML('</div>');
				
				// SEO parametry
				echo $form->addHTML('<label for="seoparam"><input type="checkbox" name="seoparam" id="seoparam" value="1" onclick="ZobrazSEO(\'seoparametry\');" > Zobrazit SEO parametry</label><div id="seoparametry" style="display: none;">');	
				echo $form->inputText('nazev_heureka','nazev_heureka','','Název Heureka','','',0,0,'');
				echo $form->inputText('category_text_heureka','category_text_heureka','','Category text Heureka','','',0,0,'');
				echo $form->inputText('title','title','','Title','','',0,0,'');
				echo $form->inputText('keywords','keywords','','Keywords','','',0,0,'');
				echo $form->inputText('description','description','','Description','','',0,0,'');
				echo $form->inputText('og_title','og_title','','OG Title','','',0,0,'');
				echo $form->inputText('og_description','og_description','','OG Description','','',0,0,'');
				echo $form->inputText('og_site_name','og_site_name','','OG SiteName','','',0,0,'');
				echo $form->inputText('og_url','og_url','','OG URL','','',0,0,'');
				echo $form->inputText('og_image','og_image','','OG Image','','',0,0,'absolutní adresa k obrázku');
				echo $form->inputSelect('indexovani','indexovani',1,$stav_adm_sluzby_arr,'Indexování','',0,0,'');
				echo $form->addHTML('</div>');
				
				echo $form->inputTextarea('poznamka','poznamka','','Interní poznámka k produktu','','',0,0,'');
				
				echo $form->inputSelect('aktivni','aktivni',1,$stav_adm_arr,'Stav','',0,0,'');
				echo $form->addHTML('<div class="clear" style="height: 20px;"></div>');	
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
	}
}
else
{
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
		$sql_podminka_kat_cislo .= ' AND V.kat_cislo_var LIKE "%'.sanitize($_GET['kat_cislo']).'%" ';
	}
	else
	{
		$sql_podminka_kat_cislo = '';
	}
	
	if($_GET['ean'])
	{
		$sql_podminka_ean .= ' AND V.ean_var LIKE "%'.sanitize($_GET['ean']).'%" ';
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
	
	if($_GET['doprava_zdarma'])
	{
		$sql_podminka_doprava_zdarma .= ' AND P.doprava_zdarma='.intval($_GET['doprava_zdarma']).' ';
	}
	else
	{
		$sql_podminka_doprava_zdarma = '';
	}
	
	if($_GET['specialni_doprava'])
	{
		$sql_podminka_specialni_doprava .= ' AND P.specialni_doprava='.intval($_GET['specialni_doprava']).' ';
	}
	else
	{
		$sql_podminka_specialni_doprava = '';
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
	
	if(!$_GET['razeni'])
	{
	   $_GET['razeni'] = 'P.id';
	   $_GET['ord'] = 'DESC';
	}
	
	if($_GET['ord']=='ASC'){$ord = 'DESC';}
	else{$ord = 'ASC';}
		
	$pocet = Db::queryAffected('SELECT P.id FROM produkty P 
	LEFT JOIN produkty_varianty V ON V.id_produkt = P.id
	WHERE 1 '.$sql_podminka_nazev.$sql_podminka_kat_cislo.$sql_podminka_ean.$sql_podminka_stav.$sql_podminka_vyrobce.$sql_podminka_kategorie.$sql_podminka_akce.$sql_podminka_novinka.$sql_podminka_doporucujeme.$sql_podminka_doprodej.$sql_podminka_doprava_zdarma.$sql_podminka_specialni_doprava.' GROUP BY P.id', array()); 
	
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
	<td><a href="'.$get_parametry.'limit='.$limit.'&razeni=P.id&ord='.$ord.'">Id</a>';
	if($ord=='DESC' && ($_GET['razeni']=='P.id')){echo '&nbsp;<i class="fas fa-arrow-up" style="color:#000000;"></i>';}
	elseif($ord=='ASC' && ($_GET['razeni']=='P.id')){echo '&nbsp;<i class="fas fa-arrow-down" style="color:#000000;"></i>';}
	echo '</td>
	<td>Foto</td>
	<td><a href="'.$get_parametry.'limit='.$limit.'&razeni=P.nazev&ord='.$ord.'">Produkt</a>';
	if($ord=='DESC' && ($_GET['razeni']=='P.nazev')){echo '&nbsp;<i class="fas fa-arrow-up" style="color:#000000;"></i>';}
	elseif($ord=='ASC' && ($_GET['razeni']=='P.nazev')){echo '&nbsp;<i class="fas fa-arrow-down" style="color:#000000;"></i>';}
	echo '</td>
	<td><a href="'.$get_parametry.'limit='.$limit.'&razeni=P.cena_razeni&ord='.$ord.'">Cena od bez DPH</a>';
	if($ord=='DESC' && ($_GET['razeni']=='P.cena_razeni')){echo '&nbsp;<i class="fas fa-arrow-up" style="color:#000000;"></i>';}
	elseif($ord=='ASC' && ($_GET['razeni']=='P.cena_razeni')){echo '&nbsp;<i class="fas fa-arrow-down" style="color:#000000;"></i>';}
	echo '</td>
	<td><a href="'.$get_parametry.'limit='.$limit.'&razeni=P.zobrazeno&ord='.$ord.'">Návštěvnost</a>';
	if($ord=='DESC' && ($_GET['razeni']=='P.zobrazeno')){echo '&nbsp;<i class="fas fa-arrow-up" style="color:#000000;"></i>';}
	elseif($ord=='ASC' && ($_GET['razeni']=='P.zobrazeno')){echo '&nbsp;<i class="fas fa-arrow-down" style="color:#000000;"></i>';}
	echo '</td>
	<td><a href="'.$get_parametry.'limit='.$limit.'&razeni=P.datum&ord='.$ord.'">Datum</a>';
	if($ord=='DESC' && ($_GET['razeni']=='P.datum')){echo '&nbsp;<i class="fas fa-arrow-up" style="color:#000000;"></i>';}
	elseif($ord=='ASC' && ($_GET['razeni']=='P.datum')){echo '&nbsp;<i class="fas fa-arrow-down" style="color:#000000;"></i>';}
	echo '</td>
	<td><a href="'.$get_parametry.'limit='.$limit.'&razeni=P.aktivni&ord='.$ord.'">Stav</a>';
	if($ord=='DESC' && ($_GET['razeni']=='P.aktivni')){echo '&nbsp;<i class="fas fa-arrow-up" style="color:#000000;"></i>';}
	elseif($ord=='ASC' && ($_GET['razeni']=='P.aktivni')){echo '&nbsp;<i class="fas fa-arrow-down" style="color:#000000;"></i>';}
	echo '</td>
	<td style="width: 90px;">Akce</td>
	</tr>';
	echo '<tr><td colspan="8" style="text-align: right;"><a href="./index.php?p=produkty&action=insert">přidat záznam</a></td></tr>';
	
	// výpis
	$data_vypis = Db::queryAll('SELECT P.id, P.nazev, P.str, P.zobrazeno, P.aktivni, P.datum, PF.foto 
	FROM produkty P 
	LEFT JOIN produkty_foto PF ON PF.id_produkt=P.id
	LEFT JOIN produkty_varianty V ON V.id_produkt = P.id
	WHERE 1 AND PF.typ=1 
	'.$sql_podminka_nazev.$sql_podminka_kat_cislo.$sql_podminka_ean.$sql_podminka_stav.$sql_podminka_vyrobce.$sql_podminka_kategorie.$sql_podminka_akce.$sql_podminka_novinka.$sql_podminka_doporucujeme.$sql_podminka_doprodej.$sql_podminka_doprava_zdarma.$sql_podminka_specialni_doprava.' GROUP BY P.id ORDER BY '.sanitize($_GET['razeni']).' '. $_GET['ord'].' LIMIT '.$limit.','.__STRANKOVANI_ADMIN__.'  ', array());
	if($data_vypis !== false ) 
	{
		foreach ($data_vypis as $row_vypis) 
		{
		
			echo '<tr '.__TR_BG__.'>
			<td>'.$row_vypis['id'].'&nbsp;<input type="checkbox" name="idp[]" value="'.$row_vypis['id'].'" style="display: inline-block;" ></td>
			<td><a href="/produkty/'.$row_vypis['str'].'"><img src="/fotky/produkty/male/'.$row_vypis['foto'].'" style="width: 32px;"></a></td>
			<td>'.$row_vypis['nazev'].'&nbsp;&nbsp;<a href="javascript:ZobrazVar('.$row_vypis['id'].');">varianty</a>';
			
			// výpis variant na klik
			echo '<div class="clear"></div>
			<div id="var_'.$row_vypis['id'].'" style="display: none;">';
			
			$data_vypis_var = Db::queryAll('SELECT V.id, V.nazev_var, V.cena_A, V.kat_cislo_var, V.ean_var, V.ks_skladem, V.aktivni_var, D.dostupnost 
			FROM produkty_varianty V
			LEFT JOIN produkty_dostupnost D ON D.id=V.id_dostupnost 
			WHERE V.id_produkt=?', array($row_vypis['id']));
			if($data_vypis_var !== false ) 
			{
			echo '<br>
			<table class="noborder" style="width: 100%;" border="0">';
			echo '<tr>
						<td style="width: 30%; border-bottom: solid 1px #000000;">Název varianty</td>
						<td style="width: 10%; border-bottom: solid 1px #000000;">Kat. č.</td>
						<td style="width: 15%; border-bottom: solid 1px #000000;">EAN</td>
						<td style="width: 15%; border-bottom: solid 1px #000000;">Ks skladem</td>
						<td style="width: 10%; border-bottom: solid 1px #000000;">Cena A bez DPH</td>
						<td style="width: 10%; border-bottom: solid 1px #000000;">Dostupnost</td>
						<td style="border-bottom: solid 1px #000000;">Stav</td>
					</tr>';
				foreach ($data_vypis_var as $row_vypis_var) 
				{
					// rezervace = zboží v košíku
					$data_kosik = Db::queryRow('SELECT SUM(pocet) AS POCET_CELKEM FROM kosik WHERE id_produkt=? AND id_var=? ', array($row_vypis['id'],$row_vypis_var['id']));
					
					echo '<tr>
						<td>'.$row_vypis_var['nazev_var'].'</td>
						<td>'.$row_vypis_var['kat_cislo_var'].'</td>
						<td>'.$row_vypis_var['ean_var'].'</td>
						<td>'.$row_vypis_var['ks_skladem'].' ('.intval($data_kosik['POCET_CELKEM']).')</td>
						<td>'.$row_vypis_var['cena_A'].'</td>
						<td>'.$row_vypis_var['dostupnost'].'</td>
						<td>';
						if($row_vypis_var['aktivni_var']==1){echo '<span class="r">'.$stav_adm_arr[$row_vypis_var['aktivni_var']].'</span>';}
						else{echo '<span class="b">'.$stav_adm_arr[$row_vypis_var['aktivni_var']].'</span>';}
						echo '</td>
					</tr>';
				}
			
			
			echo '</table>';
		    }
			echo '</div>';
			
			echo '</td>
			<td>';
			// nejnižší cena varianty
				$data_var = Db::queryRow('SELECT min(cena_A) AS MIN FROM produkty_varianty WHERE id_produkt = ? ', array($row_vypis['id']));
				if($data_var)
				{
				   echo $data_var['MIN'].' '.__MENA__;
				}
			echo '</td>
			<td>'.$row_vypis['zobrazeno'].'</td>
			<td>'.date('d.m.Y',$row_vypis['datum']).'</td>
			<td>';
			if($row_vypis['aktivni']==1){echo '<span class="r">'.$stav_adm_arr[$row_vypis['aktivni']].'</span>';}
			else{echo '<span class="b">'.$stav_adm_arr[$row_vypis['aktivni']].'</span>';}
			echo '</td>
			<td>&nbsp;<a href="./index.php?p=produkty&action=update&id='.$row_vypis['id'].'">upravit</a>&nbsp;&nbsp;&nbsp;<a href="./index.php?p=produkty&action=delete&id='.$row_vypis['id'].'" '.__JS_CONFIRM__.'>smazat</a></td>
			</tr>';
		}
	}
	
	
	echo '</table>';
	
	echo '<div class="clear" style="height: 20px;"></div>';
	

			
	echo '<select name="stav" style="display: inline-block;" required>';
	echo '<option value=""> vyberte </option>';
	foreach($stav_adm_arr as $stav_k=>$stav_v)
	{
		echo '<option value="'.$stav_k.'"> '.$stav_v.' </option>';
	}
	echo '</select>
	&nbsp;&nbsp;';
	
	echo '<input type="submit" name="zmena_stavu" id="zmena_stavu" value="Změnit stav vybraných produktů" style="display: inline-block;" >';
	echo '</form>';
	
 
}

?>
 <script type="text/javascript">

 $(document).ready(function () {
    $("#f_prehled").validate({


    });
  	
		
});


	 
 $(document).ready(function () {
    $("#formular").validate({

	
	rules: {
       'id_kat_arr[]': {
        required: true,
        minlength: 1
		},
		
		'id_dostupnost[]': {
        required: true,
        min: 1
        },
	},
	 messages: {

            'id_kat_arr[]': "Je nutné vybrat alespoň jednu kategorii",
            'id_dostupnost[]': "Vyberte dostupnost"
        }

    });
  	
		
});



 
 

 $(document).ready(function () {

    
    $(function(){
	 $( '#title,#keywords,#description,#og_title,#og_description,#og_site_name,#og_url,#og_image' ).EnsureMaxLength({

	    limit: 255

	  });
	});
});



function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }

      var reader = new FileReader();

      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
          // Render thumbnail.
          var span = document.createElement('span');
          span.innerHTML = ['<img class="thumb" src="', e.target.result,
                            '" title="', escape(theFile.name), '"/>'].join('');
          document.getElementById('list').insertBefore(span, null);
        };
      })(f);

      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
    }
  }

  if($('#files').length)
  {
    document.getElementById('files').addEventListener('change', handleFileSelect, false);
  }
  
  
  
// řazení fotek přetažením
$(document).ready(function(){
	$("#list_of_tasks").sortable({
		update: function(event, ui) {
			$.post("_ajax.php?typ=razeni_fotek_produkty", { pages: $('#list_of_tasks').sortable('serialize') });
 
		}
	});
});


// řazení příloh přetažením
$(document).ready(function(){
	$("#list_of_tasks2").sortable({
		update: function(event, ui) {
			$.post("_ajax.php?typ=razeni_priloh_produkty", { pages: $('#list_of_tasks2').sortable('serialize') });
 
		}
	});
});


// změna příznaku hlavní fotky
$(document).ready(function(){
    $('.hlavni_foto').on('click', function ()
    {


        var idf = $('.hlavni_foto:checked').val(); 
		if(idf != '')
		{
			$.ajax({
            url: '_ajax.php?typ=hlavni_foto_produkt',
            type: 'post',
            data: {idf:idf},
            success: function(response)
            {

                if(response > 0)
                {

					$('.fd').css("border","none");
                    $('#f_'+idf).css("border","2px solid red");
   
                }
                else
                {
					alert('chyba, nepodařilo se změnit hlavní foto');
				}
                

             }
          });
		}
	
    });
});


if($('#foto_var').length)
{

var imageLoader = document.getElementById('foto_var');
    imageLoader.addEventListener('change', handleImage, false);
var canvas = document.getElementById('imageCanvas');
var ctx = canvas.getContext('2d');

}




<?php
if($_GET['action']=='insert')
{
?>
$(document).ready(function(){
	$('#nazev').keyup(function(){
	var uname = $('#nazev').val().trim();
	
	if(uname != '')
	{

         $('#kontrola_produkt').show();

         $.ajax({
            url: '_ajax.php?typ=kontrola_produkt',
            type: 'post',
            data: {uname:uname},
            success: function(response)
            {

                if(response > 0)
                {
                    $('#kontrola_produkt').html('<span style="color: red;"> Produkt s tímto názvem již existuje, zvolte jiný název!</span>');
                }
                else
                {
                    $('#kontrola_produkt').html('<span style="color: green;">OK</span>');
                }

             }
          });
      }
      else
      {
         $('#kontrola_produkt').hide();
      }
	
	});
});
<?php
}
?>

</script>
