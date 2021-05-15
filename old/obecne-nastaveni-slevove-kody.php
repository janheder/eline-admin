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
				  $data_update = array('obsah' => $_POST['obsah']);
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
				echo $form->inputText('str','str',$data['nazev'],'Název','style="width: 400px;" readonly','',0,0,'');
				
				if($data['id']==118)
				{
					// selectbox
					echo $form->inputSelect('obsah','obsah',intval($data['obsah']),$stav_adm_sluzby_arr,'Hodnota','',0,0,'');
					
				}
				elseif($data['id']==121)
				{
					// selectbox
					echo $form->inputSelect('obsah','obsah',intval($data['obsah']),$stav_adm_sluzby_arr,'Hodnota','',0,0,'');
					
				}
				elseif($data['id']==120)
				{
					// selectbox
					echo $form->inputSelect('obsah','obsah',intval($data['obsah']),$doba_platnosti_arr,'Hodnota','',0,0,'');
					
				}
				elseif($data['id']==122)
				{
					// selectbox
					echo $form->inputSelect('obsah','obsah',intval($data['obsah']),$typ_sl_kod_arr,'Hodnota','',0,0,'');
					
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
	$tabulka = new Vypisy('obecne_nastaveni','typ=9','','nazev',array('id','nazev','obsah'),array('Id','Název','Obsah','Akce'),1,0,0);
	echo $tabulka->GenerujVypis();*/
	
		
	echo '<table border="0" class="ta" cellspacing="0" cellpadding="6">
		  <tr class="tr-header">
		  <td>Id</td>
		  <td>Název</td>
		  <td>Obsah</td>
		  <td style="width: 90px;">Akce</td>
		  </tr>';

	$data_s = Db::queryAll('SELECT * FROM obecne_nastaveni WHERE typ=9 ORDER BY nazev ASC', array());
	if($data_s !== false ) 
	{
		foreach ($data_s as $row_s) 
		{
			echo '<tr id="'.$row_s['id'].'" '.__TR_BG__.' >
			  <td>'.$row_s['id'].'</td>
			  <td>'.$row_s['nazev'].'</td>
			  <td>';
			  // dle ID záznamu vypisujeme 
			  if($row_s['id']==118){ echo $stav_adm_sluzby_arr[$row_s['obsah']];}
			  elseif($row_s['id']==119){ echo $row_s['obsah'].' '.__MENA__;}
			  elseif($row_s['id']==120){ echo round($row_s['obsah'] / 3600 / 24).' dnů';}
			  elseif($row_s['id']==121){ echo $stav_adm_sluzby_arr[$row_s['obsah']];}
			  elseif($row_s['id']==122){ echo $typ_sl_kod_arr[$row_s['obsah']];}
			  elseif($row_s['id']==123)
			      { 
					  
					  echo $row_s['obsah'].' ';
					  if(__SLEVOVE_KODY_TYP__==2){ echo '%';}
					  else{echo __MENA__;}
				  }
			  else{echo $row_s['obsah']; }
			  echo '</td>
			  <td><a href="./index.php?p='.$_GET['p'].'&pp='.$_GET['pp'].'&action=update&id='.$row_s['id'].'">upravit</a></td>
			  </tr>';
			  	
		}
	}
	
	echo '</table>';
}

?>
