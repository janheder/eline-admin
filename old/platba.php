<?php 
// platba

Admin::adminLogin(4);



if($_GET['action']=='delete')
{
		if($_GET['id'])
		{
			$where_delete = array('id' => $_GET['id']);
			$query = Db::delete('platba', $where_delete); 
			if($query)
			{
				
				$data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Platba - smazání záznamu id '.intval($_GET['id']),
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
	$form->required = 'nazev,typ_fakturoid';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				 
					   if($form->errors==false)
					   {

							$data_update = array(
							'nazev' => $_POST['nazev'],
							'popis' => $_POST['popis'],
							'cena' => str_replace(',','.',$_POST['cena']),
							'id_dph' => $_POST['id_dph'],
							'prevodem' => intval($_POST['prevodem']),
							'karta' => intval($_POST['karta']),
							'dobirka' => intval($_POST['dobirka']),
							'typ_fakturoid' => trim($_POST['typ_fakturoid']),
							'aktivni' => $_POST['aktivni']
							);
							$where_update = array('id' => $_POST['id']);
							$query = Db::update('platba', $data_update, $where_update);
			      
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Platba - úprava záznamu id '.intval($_POST['id']),
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
			$data = Db::queryRow('SELECT * FROM platba WHERE id = ?', array($_GET['id']));
			
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

				echo $form->formOpen();
				echo $form->inputText('nazev','nazev',$data['nazev'],'Název platby','class="input_req"','',1,0,'');
				echo $form->inputTextarea('popis','popis',$data['popis'],'Popis','','',0,0,'');
				echo $form->inputText('cena','cena',$data['cena'],'Cena bez DPH','onkeyup="vypocitej_cenu_s_dph('.__DPH__.',\'cena\',\'cena_s_dph\');"','',0,0,__MENA__.
				'&nbsp;&nbsp;orientační cena s DPH: <span id="cena_s_dph" style="color: red; text-align: right; border: 0;" >'.round(($data['cena'] * (__DPH__ / 100 + 1)),2).'</span> '.__MENA__.'  ');
				echo $form->inputSelect('id_dph','id_dph',$data['id_dph'],$dph_arr,'DPH','',0,0,'');	
				echo $form->inputCheckbox('prevodem','prevodem',1,$data['prevodem'],'ANO','Převodem','',0,0,'');
				echo $form->inputCheckbox('karta','karta',1,$data['karta'],'ANO','Karta','',0,0,'');
				echo $form->inputCheckbox('dobirka','dobirka',1,$data['dobirka'],'ANO','Dobírka','',0,0,'');
				echo $form->inputText('typ_fakturoid','typ_fakturoid',$data['typ_fakturoid'],'Fakturoid payment method','class="input_req"','',1,0,'');
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
	$form->required = 'nazev,typ_fakturoid';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
	
							
			    $data_insert = array(
							    'nazev' => $_POST['nazev'],
							    'popis' => $_POST['popis'],
							    'cena' => str_replace(',','.',$_POST['cena']),
							    'id_dph' => $_POST['id_dph'],
							    'prevodem' => intval($_POST['prevodem']),
							    'karta' => intval($_POST['karta']),
							    'dobirka' => intval($_POST['dobirka']),
							    'typ_fakturoid' => trim($_POST['typ_fakturoid']),
							    'aktivni' => $_POST['aktivni']
							     );
							          
							     
				$query_insert = Db::insert('platba', $data_insert);
				
				
				//řazení
				$data_update = array('razeni' => $query_insert);
				$where_update = array('id' => $query_insert);
				$query = Db::update('platba', $data_update, $where_update);
                
                
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Platba - přidání záznamu: '.strip_tags($_POST['nazev']),
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

			    echo $form->formOpen();
				echo $form->inputText('nazev','nazev','','Název platby','class="input_req"','',1,0,'');
				echo $form->inputTextarea('popis','popis','','Popis','','',0,0,'');
				echo $form->inputText('cena','cena','','Cena bez DPH','onkeyup="vypocitej_cenu_s_dph('.__DPH__.',\'cena\',\'cena_s_dph\');"','',0,0,
				__MENA__.'&nbsp;&nbsp;orientační cena s DPH: <span id="cena_s_dph" style="color: red; text-align: right; border: 0;" ></span> '.__MENA__.'  ');
				echo $form->inputSelect('id_dph','id_dph',$id_dph,$dph_arr,'DPH','',0,0,'');	
				echo $form->inputCheckbox('prevodem','prevodem',1,'','ANO','Převodem','',0,0,'');
				echo $form->inputCheckbox('karta','karta',1,'','ANO','Karta','',0,0,'');
				echo $form->inputCheckbox('dobirka','dobirka',1,'','ANO','Dobírka','',0,0,'');
				echo $form->inputText('typ_fakturoid','typ_fakturoid','','Fakturoid payment method','class="input_req"','',1,0,'');
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
	$tabulka = new Vypisy('platba','','','razeni ASC',array('id','nazev','cena','prevodem','karta','aktivni'),array('Id','Platba','Cena bez DPH','Převodem','Karta','Stav','Akce'),1,1,1);
	echo $tabulka->GenerujVypis();
	* */
	echo 'Způsobý platby lze řadit přetažením řádku na příslušnou pozici. Více info v nápovědě.<br><br>';
	
	echo '<table border="0" class="ta" cellspacing="0" cellpadding="6">
		  <tr class="tr-header">
		  <th>Id</th>
		  <th>Platba</th>
		  <th>Cena bez DPH</th>
		  <th>Převodem</th>
		  <th>Karta</th>
		  <th>Dobírka</th>
		  <th>Stav</th>
		  <th style="width: 90px;">Akce</th>
		  </tr>
		  <tr><td colspan="8" style="text-align: right;"><a href="./index.php?p=platba&action=insert">přidat záznam</a></td></tr>
		  <tbody class="row_position">';

	$data_s = Db::queryAll('SELECT * FROM platba WHERE 1 ORDER BY razeni ASC', array());
	if($data_s !== false ) 
	{
		foreach ($data_s as $row_s) 
		{
			echo '<tr id="'.$row_s['id'].'" '.__TR_BG__.' >
			  <td style="cursor: ns-resize;">'.$row_s['id'].'</td>
			  <td>'.$row_s['nazev'].'</td>
			  <td>'.$row_s['cena'].'</td>
			  <td>';
			  if($row_s['prevodem']=='1'){echo  '<span class="r">'.$_SESSION['stav_adm_sluzby_arr'][$row_s['prevodem']].'</span>'; }
			  else{ echo '<span class="b">'.$_SESSION['stav_adm_sluzby_arr'][$row_s['prevodem']].'</span>'; }				
			  echo '</td>
			  <td>';
			  if($row_s['karta']=='1'){echo  '<span class="r">'.$_SESSION['stav_adm_sluzby_arr'][$row_s['karta']].'</span>'; }
			  else{ echo '<span class="b">'.$_SESSION['stav_adm_sluzby_arr'][$row_s['karta']].'</span>'; }
			  echo '</td>
			  <td>';
			  if($row_s['dobirka']=='1'){echo  '<span class="r">'.$_SESSION['stav_adm_sluzby_arr'][$row_s['dobirka']].'</span>'; }
			  else{ echo '<span class="b">'.$_SESSION['stav_adm_sluzby_arr'][$row_s['dobirka']].'</span>'; }
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
		updateOrder(selectedData,'razeni_platba');
	}

});

</script>
