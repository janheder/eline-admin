<?php 
// zákazníci

Admin::adminLogin(4);

// filtr
if(!$_GET['action'])
{
	echo '<fieldset><legend>Filtr</legend>';
	$form = new Form('', '', 'get', 'formular2', 'formular', 'utf-8', '', 0);
	
	echo $form->formOpen();
	echo $form->addHTML('<div style="float: left; width: 30%;">');
	echo $form->inputText('jmeno','jmeno',sanitize($_GET['jmeno']),'Jméno','','',0,0,'');
	echo $form->inputText('prijmeni','prijmeni',sanitize($_GET['prijmeni']),'Příjmení','','',0,0,'');
	echo $form->inputText('email','email',sanitize($_GET['email']),'Email','','',0,0,'');
	echo $form->addHTML('</div>');

	echo $form->addHTML('<div style="float: left; width: 30%;">');
	$stavy_vyhl_arr = $stav_adm_arr;
	$stavy_vyhl_arr[4] = 'Vše';
	if(!isset($_GET['stav'])){$_GET['stav'] = 4;}
	echo $form->inputSelect('stav','stav',sanitize($_GET['stav']),$stavy_vyhl_arr,'Stav','',0,0,'');
	echo $form->inputText('obec','obec',sanitize($_GET['email']),'Obec','','',0,0,'');
	echo $form->addHTML('</div>');
	
	echo $form->addHTML('<div style="float: left; width: 30%; margin-top: 20px;">');
	echo $form->inputHidden('p','p',sanitize($_GET['p']),'',0);
	echo $form->inputHidden('pp','pp',sanitize($_GET['pp']),'',0);
	echo $form->inputSubmit('submit','submit','Zobrazit','','','');
	echo $form->inputButton('redir','redir','Zobrazit vše','','onclick="self.location.href=\'index.php?p=zakaznici\'"','');
	echo $form->addHTML('</div>');
	echo $form->formClose();

	echo '</fieldset>';
}


if($_GET['action']=='delete')
{
		if($_GET['id'])
		{
			
			// kontrolujeme jestli nemá objednávky
			$pocet_objednavek = Db::queryCount2('objednavky','id','','id_zakaznik='.$_GET['id']);
			
			if($pocet_objednavek>0)
			{
				echo '<div class="alert-warning">Chyba!<br>Tento zákazník má '.$pocet_objednavek.' objednávek. Nelze ho smazat. Nejdříve smažte jeho objednávky.</div>';
			}
			else
			{
				$where_delete = array('id' => $_GET['id']);
				$query = Db::delete('zakaznici', $where_delete); 
				if($query)
				{
					
					$data_insert = array(
								    'id_admin' => $_SESSION['admin']['id'],
								    'uz_jm' => $_SESSION['admin']['uz_jm'],
								    'udalost' => 'Zákazníci - smazání záznamu id '.intval($_GET['id']),
								    'url' => sanitize($_SERVER['HTTP_REFERER']),
								    'ip' => sanitize(getip()),
								    'datum' => time()
								     );
						     
					$query_insert = Db::insert('admin_log_udalosti', $data_insert);
					
					echo '<div class="alert-success">Smazáno!<br>Záznam byl smazán</div>';
				}
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
	$form->required = 'jmeno,prijmeni,uz_jmeno,email,telefon';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				 
					   if($form->errors==false)
					   {

							$data_update = array(
							'jmeno' => $_POST['jmeno'],
							'prijmeni' => $_POST['prijmeni'],
							'uz_jmeno' => $_POST['uz_jmeno'],
							'email' => $_POST['email'],
							'telefon' => $_POST['telefon'],
							
							'dodaci_nazev' => $_POST['dodaci_nazev'],
							'dodaci_ulice' => $_POST['dodaci_ulice'],
							'dodaci_cislo' => $_POST['dodaci_cislo'],
							'dodaci_obec' => $_POST['dodaci_obec'],
							'dodaci_psc' => $_POST['dodaci_psc'],
							'dodaci_id_stat' => $_POST['dodaci_id_stat'],
							
							'fakturacni_firma' => $_POST['fakturacni_firma'],
							'fakturacni_ulice' => $_POST['fakturacni_ulice'],
							'fakturacni_cislo' => $_POST['fakturacni_cislo'],
							'fakturacni_obec' => $_POST['fakturacni_obec'],
							'fakturacni_psc' => $_POST['fakturacni_psc'],
							'fakturacni_id_stat' => $_POST['fakturacni_id_stat'],
							'ic' => $_POST['ic'],
							'dic' => $_POST['dic'],
							
							'id_skupiny_slev' => $_POST['id_skupiny_slev'],
							'cenova_skupina' => $_POST['cenova_skupina'],
							'nl' => intval($_POST['nl']),
							'aktivni' => $_POST['aktivni']);
							
							 // změnil heslo
						    if($_POST['heslo'])
						    {
								$data_update['heslo'] = hash('sha256',$_POST['heslo']);
							}
							
							$where_update = array('id' => $_POST['id']);
							$query = Db::update('zakaznici', $data_update, $where_update);
			      
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Zákazníci - úprava záznamu id '.intval($_POST['id']),
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
			$data = Db::queryRow('SELECT * FROM zakaznici WHERE id = ?', array($_GET['id']));
			
			if($data)
			{
				// skupiny zákazníků
				$data_sk = Db::queryAll('SELECT * FROM zakaznici_skupiny WHERE aktivni=1 ORDER BY procento ASC', array());
				$sk_arr = array();
				if($data_sk !== false ) 
				{
					$sk_arr[0] = 'vyberte';	
					 
				    foreach ($data_sk as $row_sk) 
					{
					  $sk_arr[$row_sk['id']] = $row_sk['nazev'].' - '.$row_sk['procento'].'%';	
					}
				}
				else
				{
					$sk_arr = false;
				}

				echo $form->formOpen();
				echo $form->inputText('jmeno','jmeno',$data['jmeno'],'Jméno','class="input_req"','',1,0,'');
				echo $form->inputText('prijmeni','prijmeni',$data['prijmeni'],'Příjmení','class="input_req"','',1,0,'');
				echo $form->inputText('uz_jmeno','uz_jmeno',$data['uz_jmeno'],'Uživatelské jméno','class="input_req" autocomplete="new-password"','',1,0,'');
				echo $form->inputText('heslo','heslo','','Heslo','autocomplete="new-password"','',0,0,'pokud nezadáte zůstane původní');
				 
				echo $form->inputEmail('email','email',$data['email'],'Email','class="input_req"','',1,0,'');
				echo $form->inputText('telefon','telefon',$data['telefon'],'Telefon','class="input_req"','',1,0,'');
				 
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				echo $form->addHTML('<fieldset><legend><h2>Dodací údaje</h2></legend>');	
				echo $form->inputText('dodaci_nazev','dodaci_nazev',$data['dodaci_nazev'],'Název','','',0,0,'pro pobočku Zásilkovny atd.');
				echo $form->inputText('dodaci_ulice','dodaci_ulice',$data['dodaci_ulice'],'Ulice','','',0,0,'');
				echo $form->inputText('dodaci_cislo','dodaci_cislo',$data['dodaci_cislo'],'Číslo popisné','','',0,0,'');
				echo $form->inputText('dodaci_obec','dodaci_obec',$data['dodaci_obec'],'Obec','','',0,0,'');
				echo $form->inputText('dodaci_psc','dodaci_psc',$data['dodaci_psc'],'PSČ','','',0,0,'');
				echo $form->inputSelect('dodaci_id_stat','dodaci_id_stat',$data['dodaci_id_stat'],$staty_arr,'Stát','',0,0,'');
				echo $form->addHTML('</fieldset>');	
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				echo $form->addHTML('<fieldset><legend><h2>Fakturační údaje</h2></legend>');	
				echo $form->inputText('fakturacni_firma','fakturacni_firma',$data['fakturacni_firma'],'Firma','','',0,0,'');
				echo $form->inputText('fakturacni_ulice','fakturacni_ulice',$data['fakturacni_ulice'],'Ulice','','',0,0,'');
				echo $form->inputText('fakturacni_cislo','fakturacni_cislo',$data['fakturacni_cislo'],'Číslo popisné','','',0,0,'');
				echo $form->inputText('fakturacni_obec','fakturacni_obec',$data['fakturacni_obec'],'Obec','','',0,0,'');
				echo $form->inputText('fakturacni_psc','fakturacni_psc',$data['fakturacni_psc'],'PSČ','','',0,0,'');
				echo $form->inputSelect('fakturacni_id_stat','fakturacni_id_stat',$data['fakturacni_id_stat'],$staty_arr,'Stát','',0,0,'');
				echo $form->inputText('ic','ic',$data['ic'],'IČ','','',0,0,'');
				echo $form->inputText('dic','dic',$data['dic'],'DIČ','','',0,0,'');
				echo $form->addHTML('</fieldset>');	
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				
				echo $form->inputSelect('id_skupiny_slev','id_skupiny_slev',$data['id_skupiny_slev'],$sk_arr,'Slevová skupina','',0,0,''); 
				echo $form->inputSelect('cenova_skupina','cenova_skupina',$data['cenova_skupina'],$ceny_arr,'Cenová skupina','',0,0,'');
					
				echo $form->inputCheckbox('nl','nl',1,$data['nl'],'ANO','Chce newsletter','',0,0,'');
				echo $form->inputSelect('aktivni','aktivni',$data['aktivni'],$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputHidden('id','id',intval($_GET['id']),'',0);
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
				
				// výpis objednávek
				// + odkaz na detail obj.
				// datum, cislo, cena, stav
				echo '<div class="clear" style="height: 10px;"></div>';	
				echo '<fieldset><legend><h2>Objednávky</h2></legend>';
				
					$data_o = Db::queryAll('SELECT O.id, O.cislo_obj, O.cena_celkem_s_dph, O.datum, OS.nazev AS STAV
					FROM objednavky O 
					LEFT JOIN objednavky_stavy OS ON OS.id=O.id_stav
					WHERE O.id_zakaznik='.intval($_GET['id']).' ORDER BY O.datum ASC', array());
					if($data_o !== false ) 
					{	
						echo '<table style="width: 500px;" cellpadding="3" border="0">';
						echo '<tr>
						<td>Datum</td>
						<td>Číslo objednávky</td>
						<td>Cena celkem s DPH</td>
						<td>Stav</td>
						</tr>';
						foreach ($data_o as $row_o) 
						{
						  
						  echo '<tr>
						  <td>'.date('d.m.Y',$row_o['datum']).'</td>
						  <td><a href="./index.php?p=objednavky&action=update&id='.$row_o['id'].'">'.$row_o['cislo_obj'].'</a></td>
						  <td>'.$row_o['cena_celkem_s_dph'].' '.__MENA__.'</td>
						  <td>'.$row_o['STAV'].'</td>
						  </tr>';
						
						}
						echo '</table>';
					
					}
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
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 0);
	$form->required = 'jmeno,prijmeni,uz_jmeno,heslo,email,telefon';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
	
							
			    $data_insert = array(
							    'jmeno' => $_POST['jmeno'],
								'prijmeni' => $_POST['prijmeni'],
								'uz_jmeno' => $_POST['uz_jmeno'],
								'heslo' => hash('sha256',$_POST['heslo']),
								'email' => $_POST['email'],
								'telefon' => $_POST['telefon'],
								
								'dodaci_nazev' => $_POST['dodaci_nazev'],
								'dodaci_ulice' => $_POST['dodaci_ulice'],
								'dodaci_cislo' => $_POST['dodaci_cislo'],
								'dodaci_obec' => $_POST['dodaci_obec'],
								'dodaci_psc' => $_POST['dodaci_psc'],
								'dodaci_id_stat' => $_POST['dodaci_id_stat'],
								
								'fakturacni_firma' => $_POST['fakturacni_firma'],
								'fakturacni_ulice' => $_POST['fakturacni_ulice'],
								'fakturacni_cislo' => $_POST['fakturacni_cislo'],
								'fakturacni_obec' => $_POST['fakturacni_obec'],
								'fakturacni_psc' => $_POST['fakturacni_psc'],
								'fakturacni_id_stat' => $_POST['fakturacni_id_stat'],
								'ic' => $_POST['ic'],
								'dic' => $_POST['dic'],
								
								'datum' => time(),
								'id_skupiny_slev' => $_POST['id_skupiny_slev'],
								'cenova_skupina' => $_POST['cenova_skupina'],
								'nl' => intval($_POST['nl']),
								'aktivni' => $_POST['aktivni']
							     );
							          
							     
				$query_insert = Db::insert('zakaznici', $data_insert);
                
                
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Zákazníci - přidání záznamu: '.strip_tags($_POST['uz_jmeno']),
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
				// skupiny zákazníků
				$data_sk = Db::queryAll('SELECT * FROM zakaznici_skupiny WHERE aktivni=1 ORDER BY procento ASC', array());
				$sk_arr = array();
				if($data_sk !== false ) 
				{
					$sk_arr[0] = 'vyberte';	
					 
				    foreach ($data_sk as $row_sk) 
					{
					  $sk_arr[$row_sk['id']] = $row_sk['nazev'].' - '.$row_sk['procento'].'%';	
					}
				}
				else
				{
					$sk_arr = false;
				}

			    echo $form->formOpen();
				echo $form->inputText('jmeno','jmeno','','Jméno','class="input_req"','',1,0,'');
				echo $form->inputText('prijmeni','prijmeni','','Příjmení','class="input_req"','',1,0,'');
				echo $form->inputText('uz_jmeno','uz_jmeno','','Uživatelské jméno','class="input_req" autocomplete="new-password"','',1,0,'&nbsp;<span id="kontrola_uz_jm"></span>');
				echo $form->inputText('heslo','heslo','','Heslo','class="input_req" autocomplete="new-password"','',1,0,'<span id="pass_strenght" class="registerForm__inputGroup">
				<span class="progress"><span id="StrengthProgressBar" class="progress-bar"></span></span></span>');
				 
				echo $form->inputEmail('email','email','','Email','class="input_req"','',1,0,'');
				echo $form->inputText('telefon','telefon','','Telefon','class="input_req"','',1,0,'');
				 
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				echo $form->addHTML('<fieldset><legend><h2>Dodací údaje</h2></legend>');	
				echo $form->inputText('dodaci_nazev','dodaci_nazev','','Název','','',0,0,'pro pobočku Zásilkovny atd.');
				echo $form->inputText('dodaci_ulice','dodaci_ulice','','Ulice','','',0,0,'');
				echo $form->inputText('dodaci_cislo','dodaci_cislo','','Číslo popisné','','',0,0,'');
				echo $form->inputText('dodaci_obec','dodaci_obec','','Obec','','',0,0,'');
				echo $form->inputText('dodaci_psc','dodaci_psc','','PSČ','','',0,0,'');
				echo $form->inputSelect('dodaci_id_stat','dodaci_id_stat','',$staty_arr,'Stát','',0,0,'');
				echo $form->addHTML('</fieldset>');	
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				echo $form->addHTML('<fieldset><legend><h2>Fakturační údaje</h2></legend>');	
				echo $form->inputText('fakturacni_firma','fakturacni_firma','','Firma','','',0,0,'');
				echo $form->inputText('fakturacni_ulice','fakturacni_ulice','','Ulice','','',0,0,'');
				echo $form->inputText('fakturacni_cislo','fakturacni_cislo','','Číslo popisné','','',0,0,'');
				echo $form->inputText('fakturacni_obec','fakturacni_obec','','Obec','','',0,0,'');
				echo $form->inputText('fakturacni_psc','fakturacni_psc','','PSČ','','',0,0,'');
				echo $form->inputSelect('fakturacni_id_stat','fakturacni_id_stat','',$staty_arr,'Stát','',0,0,'');
				echo $form->inputText('ic','ic','','IČ','','',0,0,'');
				echo $form->inputText('dic','dic','','DIČ','','',0,0,'');
				echo $form->addHTML('</fieldset>');	
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				
				echo $form->inputSelect('id_skupiny_slev','id_skupiny_slev','',$sk_arr,'Slevová skupina','',0,0,''); 
				echo $form->inputSelect('cenova_skupina','cenova_skupina','A',$ceny_arr,'Cenová skupina','',0,0,'');
					
				echo $form->inputCheckbox('nl','nl',1,'','ANO','Chce newsletter','',0,0,'');
				echo $form->inputSelect('aktivni','aktivni',1,$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
	}
}
else
{
	// vypis vsech
	if($_GET['jmeno'])
	{
		$sql_podminka_jmeno .= ' AND Z.jmeno LIKE "%'.sanitize($_GET['jmeno']).'%" ';
	}
	else
	{
		$sql_podminka_jmeno = '';
	}
	
	if($_GET['prijmeni'])
	{
		$sql_podminka_prijmeni .= ' AND Z.prijmeni LIKE "%'.sanitize($_GET['prijmeni']).'%" ';
	}
	else
	{
		$sql_podminka_prijmeni = '';
	}
	
	if($_GET['email'])
	{
		$sql_podminka_email .= ' AND Z.email LIKE "%'.sanitize($_GET['email']).'%" ';
	}
	else
	{
		$sql_podminka_email = '';
	}
	
	if($_GET['obec'])
	{
		$sql_podminka_obec .= ' AND Z.obec LIKE "%'.sanitize($_GET['obec']).'%" ';
	}
	else
	{
		$sql_podminka_obec = '';
	}
	
	if(isset($_GET['stav']) && $_GET['stav']!=4)
	{
		$sql_podminka_stav .= ' AND Z.aktivni = '.intval($_GET['stav']).' ';
	}
	else
	{
		$sql_podminka_stav = '';
	}
	
	$tabulka = new Vypisy('zakaznici','','SELECT Z.*, ZS.procento 
	FROM zakaznici Z
	LEFT JOIN zakaznici_skupiny ZS ON ZS.id=Z.id_skupiny_slev 
	WHERE 1 '.$sql_podminka_jmeno.$sql_podminka_prijmeni.$sql_podminka_email.$sql_podminka_obec.$sql_podminka_stav.'
	','',array('Z.id','Z.jmeno','Z.prijmeni','Z.email','Z.telefon','Z.dodaci_obec','Z.cenova_skupina','ZS.procento','Z.datum','Z.aktivni'),
	array('Id','Jméno','Příjmení','Email','Telefon','Obec','Ceny','Slevová skupina %','Datum','Stav','Akce'),1,1,1);
	echo $tabulka->GenerujVypis();
}

?>
 <script type="text/javascript">
	 
 $(document).ready(function () {
    $("#formular").validate({
       
    });
});


<?php
if($_GET['action']=='insert')
{
?>
$(document).ready(function(){
	$('#uz_jmeno').keyup(function(){
	var uname = $('#uz_jmeno').val().trim();
	
	if(uname != '')
	{

         $('#kontrola_uz_jm').show();

         $.ajax({
            url: '_ajax.php?typ=kontrola_uz_jm',
            type: 'post',
            data: {uname:uname},
            success: function(response)
            {

                if(response > 0)
                {
                    $('#kontrola_uz_jm').html('<span style="color: red;"> Takové už. jméno již existuje, zvolte jiné!</span>');
                }
                else
                {
                    $('#kontrola_uz_jm').html('<span style="color: green;">OK</span>');
                }

             }
          });
      }
      else
      {
         $('#kontrola_uz_jm').hide();
      }
	
	});
});
<?php
}
?>

</script>
