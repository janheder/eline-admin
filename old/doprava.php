<?php 
// doprava

Admin::adminLogin(4);



if($_GET['action']=='delete')
{
		if($_GET['id'])
		{
			$where_delete = array('id' => $_GET['id']);
			$query = Db::delete('doprava', $where_delete); 
			if($query)
			{
				
				$data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Doprava - smazání záznamu id '.intval($_GET['id']),
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
	
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 0);
	$form->required = 'nazev,dopravce_heureka,platba_arr';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				 
					   if($form->errors==false)
					   {
							
							if($_POST['platba_arr']){$platba_arr = serialize($_POST['platba_arr']);}
							else{$platba_arr = '';}
							
							$data_update = array(
							'nazev' => $_POST['nazev'],
							'dopravce_heureka' => $_POST['dopravce_heureka'],
							'popis' => $_POST['popis'],
							'castka_od' => intval($_POST['castka_od']),
							'castka_do' => intval($_POST['castka_do']),
							'cena' => str_replace(',','.',$_POST['cena']),
							'id_dph' => $_POST['id_dph'],
							'platba_arr' => $platba_arr,
							'cp_balik_na_postu' => intval($_POST['cp_balik_na_postu']),
							'cp_balik_do_balikovny' => intval($_POST['cp_balik_do_balikovny']),
							'zasilkovna' => intval($_POST['zasilkovna']),
							'specialni_doprava' => intval($_POST['specialni_doprava']),
							'aktivni' => $_POST['aktivni']);
							$where_update = array('id' => $_POST['id']);
							$query = Db::update('doprava', $data_update, $where_update);
			      
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Doprava - úprava záznamu id '.intval($_POST['id']),
							    'url' => sanitize($_SERVER['HTTP_REFERER']),
							    'ip' => sanitize(getip()),
							    'datum' => time()
							     );
					     
							  $query_insert = Db::insert('admin_log_udalosti', $data_insert);
							
							echo '<div class="alert-success">Uloženo!<br>Záznam byl v pořádku změněn<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a><br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'&action=update&id='.intval($_GET['id']).'">Znovu upravit</a></div>';
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
			$data = Db::queryRow('SELECT * FROM doprava WHERE id = ?', array($_GET['id']));
			
			if($data)
			{
				// DPH arr
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
				
				// platby arr
				$data_platba = Db::queryAll('SELECT * FROM platba WHERE aktivni=1 ORDER BY razeni ASC', array());
				$platba_arr = array();
				if($data_platba !== false ) 
				{
				    foreach ($data_platba as $row_platba) 
					{
					  $platba_arr[$row_platba['id']] = $row_platba['nazev'];	
					}
				}
				else
				{
					$platba_arr = false;
				}
				
				if($data['platba_arr']){$data_platba_arr = unserialize($data['platba_arr']);}

				echo $form->formOpen();
				echo $form->inputText('nazev','nazev',$data['nazev'],'Název dopravy','class="input_req"','',1,0,'');
				echo $form->inputText('dopravce_heureka','dopravce_heureka',$data['dopravce_heureka'],'ID dopravce Heureka','class="input_req"','',1,0,'');
				echo $form->inputTextarea('popis','popis',$data['popis'],'Popis','','',0,0,'');
				echo $form->inputText('castka_od','castka_od',$data['castka_od'],'Částka za zboží od','','',0,0,'');
				echo $form->inputText('castka_do','castka_do',$data['castka_do'],'Částka za zboží do','','',0,0,'');
				echo $form->inputText('cena','cena',$data['cena'],'Cena bez DPH','onkeyup="vypocitej_cenu_s_dph('.__DPH__.',\'cena\',\'cena_s_dph\');"','',0,0,__MENA__.
				'&nbsp;&nbsp;orientační cena s DPH: <span id="cena_s_dph" style="color: red; text-align: right; border: 0;" >'.round(($data['cena'] * (__DPH__ / 100 + 1)),2).'</span> '.__MENA__.'  ');
				echo $form->inputSelect('id_dph','id_dph',$data['id_dph'],$dph_arr,'DPH','',0,0,'');	
				echo $form->inputCheckboxMulti('platba_arr[]','platba_arr',$data_platba_arr,$platba_arr,'Platby','',1,0,'');
				echo $form->addHTML('<div class="clear" style="height: 20px;"></div>');	
				echo $form->inputCheckbox('cp_balik_na_postu','cp_balik_na_postu',1,$data['cp_balik_na_postu'],'ANO','Balík na poštu','',0,0,'');
				echo $form->inputCheckbox('cp_balik_do_balikovny','cp_balik_do_balikovny',1,$data['cp_balik_do_balikovny'],'ANO','Balík do balíkovny','',0,0,'');
				echo $form->inputCheckbox('zasilkovna','zasilkovna',1,$data['zasilkovna'],'ANO','Zásilkovna','',0,0,'');
				echo $form->inputCheckbox('specialni_doprava','specialni_doprava',1,$data['specialni_doprava'],'ANO','Speciální doprava','',0,0,'');
				echo $form->inputSelect('aktivni','aktivni',$data['aktivni'],$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputHidden('id','id',intval($_GET['id']),'',0);
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
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
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 0);
	$form->required = 'nazev,dopravce_heureka,platba_arr';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
	
				if($_POST['platba_arr']){$platba_arr = serialize($_POST['platba_arr']);}
				else{$platba_arr = '';}
										
			    $data_insert = array(
							    'nazev' => $_POST['nazev'],
							    'dopravce_heureka' => $_POST['dopravce_heureka'],
							    'popis' => $_POST['popis'],
							    'castka_od' => intval($_POST['castka_od']),
								'castka_do' => intval($_POST['castka_do']),
							    'cena' => str_replace(',','.',$_POST['cena']),
							    'id_dph' => $_POST['id_dph'],
							    'cp_balik_na_postu' => intval($_POST['cp_balik_na_postu']),
							    'cp_balik_do_balikovny' => intval($_POST['cp_balik_do_balikovny']),
							    'zasilkovna' => intval($_POST['zasilkovna']),
							    'specialni_doprava' => intval($_POST['specialni_doprava']),
							    'platba_arr' => $platba_arr,
							    'aktivni' => $_POST['aktivni']
							     );
							          
							     
				$query_insert = Db::insert('doprava', $data_insert);
				
				
				//řazení
				$data_update = array('razeni' => $query_insert);
				$where_update = array('id' => $query_insert);
				$query = Db::update('doprava', $data_update, $where_update);
                
                
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Doprava - přidání záznamu: '.strip_tags($_POST['nazev']),
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
				// DPH arr
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
				
				// platby arr
				$data_platba = Db::queryAll('SELECT * FROM platba WHERE aktivni=1 ORDER BY razeni ASC', array());
				$platba_arr = array();
				if($data_platba !== false ) 
				{
				    foreach ($data_platba as $row_platba) 
					{
					  $platba_arr[$row_platba['id']] = $row_platba['nazev'];	
					}
				}
				else
				{
					$platba_arr = false;
				}

			    echo $form->formOpen();
				echo $form->inputText('nazev','nazev','','Název dopravy','class="input_req"','',1,0,'');
				echo $form->inputText('dopravce_heureka','dopravce_heureka','','ID dopravce Heureka','class="input_req"','',1,0,'');
				echo $form->inputTextarea('popis','popis','','Popis','','',0,0,'');
				echo $form->inputText('castka_od','castka_od','','Částka za zboží od','','',0,0,'');
				echo $form->inputText('castka_do','castka_do','','Částka za zboží do','','',0,0,'');
				echo $form->inputText('cena','cena','','Cena bez DPH','onkeyup="vypocitej_cenu_s_dph('.__DPH__.',\'cena\',\'cena_s_dph\');"','',0,0,
				__MENA__.'&nbsp;&nbsp;orientační cena s DPH: <span id="cena_s_dph" style="color: red; text-align: right; border: 0;" ></span> '.__MENA__.'  ');
				echo $form->inputSelect('id_dph','id_dph',$id_dph,$dph_arr,'DPH','',0,0,'');	
				echo $form->inputCheckboxMulti('platba_arr[]','platba_arr','',$platba_arr,'Platby','',1,0,'');
				echo $form->addHTML('<div class="clear" style="height: 20px;"></div>');	
				echo $form->inputCheckbox('cp_balik_na_postu','cp_balik_na_postu',1,'','ANO','Balík na poštu','',0,0,'');
				echo $form->inputCheckbox('cp_balik_do_balikovny','cp_balik_do_balikovny',1,'','ANO','Balík do balíkovny','',0,0,'');
				echo $form->inputCheckbox('zasilkovna','zasilkovna',1,'','ANO','Zásilkovna','',0,0,'');
				echo $form->inputCheckbox('specialni_doprava','specialni_doprava',1,'','ANO','Speciální doprava','',0,0,'');
				echo $form->inputSelect('aktivni','aktivni',1,$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
	}
}
else
{
	// vypis vsech
	// kvůli řazení přetažením nemůžeme generovat výpis třídou

	/*
	$tabulka = new Vypisy('doprava','','','razeni ASC',array('id','nazev','cena','cp_balik_na_postu','cp_balik_do_balikovny','zasilkovna','aktivni'),
	array('Id','Doprava','Cena bez DPH','Balík na poštu','Balík do balíkovny','Zásilkovna','Stav','Akce'),1,1,1);
	echo $tabulka->GenerujVypis();
	* */
	
	echo 'Způsoby dopravy lze řadit přetažením řádku na příslušnou pozici. Více info v nápovědě.<br><br>';
	
		echo '<table border="0" class="ta" cellspacing="0" cellpadding="6">
		  <tr class="tr-header">
		  <th>Id</th>
		  <th>Doprava</th>
		  <th>Cena bez DPH</th>
		  <th>Částka od</th>
		  <th>Částka do</th>
		  <th>Balík na poštu</th>
		  <th>Balík do balíkovny</th>
		  <th>Zásilkovna</th>
		  <th>Speciální doprava</th>
		  <th>Stav</th>
		  <th style="width: 90px;">Akce</th>
		  </tr>
		  <tr><td colspan="11" style="text-align: right;"><a href="./index.php?p=doprava&action=insert">přidat záznam</a></td></tr>
		  <tbody class="row_position">';

	$data_s = Db::queryAll('SELECT * FROM doprava WHERE 1 ORDER BY razeni ASC', array());
	if($data_s !== false ) 
	{
		foreach ($data_s as $row_s) 
		{
			echo '<tr id="'.$row_s['id'].'" '.__TR_BG__.' >
			  <td style="cursor: ns-resize;">'.$row_s['id'].'</td>
			  <td>'.$row_s['nazev'].'</td>
			  <td>'.$row_s['cena'].'</td>
			  <td>'.$row_s['castka_od'].'</td>
			  <td>'.$row_s['castka_do'].'</td>
			  <td>';
			  if($row_s['cp_balik_na_postu']=='1'){echo  '<span class="r">'.$_SESSION['stav_adm_sluzby_arr'][$row_s['cp_balik_na_postu']].'</span>'; }
			  else{ echo '<span class="b">'.$_SESSION['stav_adm_sluzby_arr'][$row_s['cp_balik_na_postu']].'</span>'; }		
			  echo '</td>
			  <td>';
			  if($row_s['cp_balik_do_balikovny']=='1'){echo  '<span class="r">'.$_SESSION['stav_adm_sluzby_arr'][$row_s['cp_balik_do_balikovny']].'</span>'; }
			  else{ echo '<span class="b">'.$_SESSION['stav_adm_sluzby_arr'][$row_s['cp_balik_do_balikovny']].'</span>'; }		
			  echo '</td>
			  <td>';
			  if($row_s['zasilkovna']=='1'){echo  '<span class="r">'.$_SESSION['stav_adm_sluzby_arr'][$row_s['zasilkovna']].'</span>'; }
			  else{ echo '<span class="b">'.$_SESSION['stav_adm_sluzby_arr'][$row_s['zasilkovna']].'</span>'; }		
			  echo '</td>
			  <td>';
			  if($row_s['specialni_doprava']=='1'){echo  '<span class="r">'.$_SESSION['stav_adm_sluzby_arr'][$row_s['specialni_doprava']].'</span>'; }
			  else{ echo '<span class="b">'.$_SESSION['stav_adm_sluzby_arr'][$row_s['specialni_doprava']].'</span>'; }		
			  echo '</td>
			  <td>';
			  if($row_s['aktivni']=='1'){echo '<span class="r">'.$stav_adm_arr[$row_s['aktivni']].'</span>'; }
			  else{ echo '<span class="b">'.$stav_adm_arr[$row_s['aktivni']].'</span>'; }
			  echo '</td>
			  <td><a href="./index.php?p='.$_GET['p'].'&action=update&id='.$row_s['id'].'">upravit</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="./index.php?p='.$_GET['p'].'&action=delete&id='.$row_s['id'].'" '.__JS_CONFIRM__.'>smazat</a></td>
			  </tr>';
		}
	  
	}
	
	

	echo '</tbody></table>';	
	
}

?>
 <script type="text/javascript">
	 
 $(document).ready(function () {
    $("#formular").validate({
       
    });
});


$(".row_position").sortable({
	delay: 150,
	stop: function() {
		var selectedData = new Array();
		$('.row_position>tr').each(function() {
			selectedData.push($(this).attr("id"));

		});
		updateOrder(selectedData,'razeni_doprava');
	}

});
</script>
