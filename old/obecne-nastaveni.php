<?php 
// obecne nastaveni

Admin::adminLogin(4);

if($_GET['action']=='update')
{
	
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 0);
	$form->required = '';
	
	if($form->submit())
	{
			if($_POST['id'])
			{
				
				 if($form->errors==false)
			     {
					 // kurz euro - vyměna čárky za tečku
					 if($_POST['id'])
					 {
						$data_update = array('obsah' => str_replace(',','.',$_POST['obsah']));
					 }
					 else
					 {
						$data_update = array('obsah' => $_POST['obsah']);
					 }
				  
				  $where_update = array('id' => $_POST['id']);
			      $query = Db::update('obecne_nastaveni', $data_update, $where_update);
			      
			       $data_insert = array(
					    'id_admin' => $_SESSION['admin']['id'],
					    'uz_jm' => $_SESSION['admin']['uz_jm'],
					    'udalost' => 'Obecné nastavení - úprava záznamu id '.intval($_POST['id']),
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
			$data = Db::queryRow('SELECT * FROM obecne_nastaveni WHERE id = ?', array($_GET['id']));
			
			if($data)
			{
				echo $form->formOpen();
				echo $form->inputText('str','str',$data['nazev'],'Název','style="width: 430px;" readonly','',0,0,'');
				
				if($data['id']==10)
				{
					// selectbox
					echo $form->inputSelect('obsah','obsah',intval($data['obsah']),$stav_adm_sluzby_arr,'Hodnota','',0,0,'');
					
				}
				elseif($data['id']==102)
				{
					// selectbox
					echo $form->inputSelect('obsah','obsah',intval($data['obsah']),$karta_adm_typ_arr,'Hodnota','',0,0,'');
					
				}
				elseif($data['id']==124)
				{
					// selectbox
					echo $form->inputSelect('obsah','obsah',intval($data['obsah']),$doprava_zdarma_typ_arr,'Hodnota','',0,0,'');
					
				}
				elseif($data['id']==35)
				{	
					// úprava - pro některé položky wysiwyg editor
					echo $form->addHTML('<script type="text/javascript"> aktivujTinyMCE(\'obsah\',300) </script>');
					echo $form->inputTextarea('obsah','obsah',stripslashes($data['obsah']),'Hodnota','','',0,0,'');
				}
				else
				{
				    echo $form->inputTextarea('obsah','obsah',stripslashes($data['obsah']),'Hodnota','','',0,0,'');
				}
				
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
else
{
	// vypis vsech
	/*
	$tabulka = new Vypisy('obecne_nastaveni','typ<1','','nazev',array('id','nazev','obsah'),array('Id','Název','Obsah','Akce'),1,0,0);
	echo $tabulka->GenerujVypis();*/
	
	echo '<table border="0" class="ta" cellspacing="0" cellpadding="6">
		  <tr class="tr-header">
		  <td>Id</td>
		  <td>Název</td>
		  <td>Obsah</td>
		  <td style="width: 90px;">Akce</td>
		  </tr>';

	$data_s = Db::queryAll('SELECT * FROM obecne_nastaveni WHERE typ<1 ORDER BY nazev ASC', array());
	if($data_s !== false ) 
	{
		foreach ($data_s as $row_s) 
		{
			echo '<tr id="'.$row_s['id'].'" '.__TR_BG__.' >
			  <td>'.$row_s['id'].'</td>
			  <td>'.$row_s['nazev'].'</td>
			  <td>';
			  // dle ID záznamu vypisujeme 
			  if($row_s['id']==10){ echo $stav_adm_sluzby_arr[$row_s['obsah']];}
			  elseif($row_s['id']==102){ echo $karta_adm_typ_arr[$row_s['obsah']];}
			  elseif($row_s['id']==124){ echo $doprava_zdarma_typ_arr[$row_s['obsah']];}
			  else{echo $row_s['obsah']; }
			  echo '</td>
			  <td><a href="./index.php?p='.$_GET['p'].'&pp='.$_GET['pp'].'&action=update&id='.$row_s['id'].'">upravit</a></td>
			  </tr>';
			  	
		}
	}
	
	echo '</table>';
}

?>
