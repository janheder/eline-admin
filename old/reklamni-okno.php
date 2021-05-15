<?php 
// reklamní okno

Admin::adminLogin(4);

if($_GET['action']=='update')
{
	
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 1);
	$form->required = 'nazev,obsah';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				 
					   if($form->errors==false)
					   {
						   
						    $obsah = $_POST['obsah']; 
							
							if($_POST['datum_od'])
							{
								 list($den,$mesic,$rok) = explode(".",$_POST['datum_od']);

								 $hodina = 0;
								 $minuta = 0;
								 $sekunda = 1;
								 
								 
								 $datum_od = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);
							}
							else
							{
								$datum_od = '';
							}
							
							if($_POST['datum_do'])
							{
								 list($den,$mesic,$rok) = explode(".",$_POST['datum_do']);

								 $hodina = 23;
								 $minuta = 59;
								 $sekunda = 59;
								 
								 
								 $datum_do = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);
							}
							else
							{
								$datum_od = '';
							}

						    
							$data_update = array(
								'datum_od' => $datum_od,
								'datum_do' => $datum_do,
								
								'nazev' => $_POST['nazev'],
								'obsah' => $obsah,
								'aktivni' => $_POST['aktivni']
							);
							
							
						    
							$where_update = array('id' => $_POST['id']);
							$query = Db::update('reklamni_okno', $data_update, $where_update);
			      
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Reklamní okno - úprava záznamu id '.intval($_POST['id']),
							    'url' => sanitize($_SERVER['HTTP_REFERER']),
							    'ip' => sanitize(getip()),
							    'datum' => time()
							     );
					     
							  $query_insert = Db::insert('admin_log_udalosti', $data_insert);
							
							echo '<div class="alert-success">Uloženo!<br>Záznam byl v pořádku změněn<br>
							<a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a>
							<br>
							<a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'&action=update&id='.intval($_GET['id']).'">Znovu upravit</a></div>';
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
			$data = Db::queryRow('SELECT * FROM reklamni_okno WHERE id = ?', array($_GET['id']));
			
			if($data)
			{
				if($data['datum_od'])
				{
					$datum_od = date("d.m.Y",$data['datum_od']);
				}
				else
				{
					$datum_od = '';
				}
				
				if($data['datum_do'])
				{
					$datum_do = date("d.m.Y",$data['datum_do']);
				}
				else
				{
					$datum_do = '';
				}

				
				echo $form->formOpen();
				
				echo $form->inputText('datum_od','datepicker_od',$datum_od,'Datum od','','',0,0,'');
				echo $form->inputText('datum_do','datepicker_do',$datum_do,'Datum do','','',0,0,'');
				
				echo $form->inputText('nazev','nazev',$data['nazev'],'Nadpis','class="input_req"','',1,0,'');
				
				echo $form->addHTML('<script type="text/javascript"> aktivujTinyMCE(\'obsah\',300) </script>');
				echo $form->inputTextarea('obsah','obsah',$data['obsah'],'Obsah','','',0,0,'');

				echo $form->inputSelect('aktivni','aktivni',$data['aktivni'],$stav_adm_arr,'Stav','',0,0,'');
				
				echo $form->inputHidden('id','id',intval($_GET['id']),'',0);
				echo $form->inputSubmit('submit','checkBtn','Uložit','','','');
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
else
{
	// vypis vsech

	$tabulka = new Vypisy('reklamni_okno','','','',array('id','datum_od','datum_do','nazev','obsah','aktivni'),
	array('Id','Datum od','Datum do','Nadpis','Obsah','Stav','Akce'),1,0,0);
	echo $tabulka->GenerujVypis();
}

?>
