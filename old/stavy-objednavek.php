<?php 
// stavy objednávek

Admin::adminLogin(4);



if($_GET['action']=='update')
{
	
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 0);
	$form->required = 'nazev,text_email';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				 
					   if($form->errors==false)
					   {

							$data_update = array(
							'nazev' => $_POST['nazev'],
							'text_email' => $_POST['text_email'],
							'aktivni' => $_POST['aktivni']
							);
							
							$where_update = array('id' => $_POST['id']);
							$query = Db::update('objednavky_stavy', $data_update, $where_update);
			      
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Stavy objednávek - úprava záznamu id '.intval($_POST['id']),
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
			$data = Db::queryRow('SELECT * FROM objednavky_stavy WHERE id = ?', array($_GET['id']));
			
			if($data)
			{

				echo $form->formOpen();		
				echo $form->inputText('nazev','nazev',$data['nazev'],'Název','class="input_req" readonly','',1,0,'');
				echo $form->addHTML('<script type="text/javascript"> aktivujTinyMCE(\'text_email\',300) </script>');
				echo $form->inputTextarea('text_email','text_email',$data['text_email'],'Text emailu','','',1,0,'');
				echo $form->addHTML('<div class="clear" style="height: 20px;"></div>');	
				
				echo $form->addHTML('<b>Proměnné do šablon</b><br>');	
				echo $form->addHTML('[CELKOVA_CASTKA] = celková částka za objednávku<br>');	
				echo $form->addHTML('[CISLO_UCTU] = číslo účtu z obecného nastavení pro daný eshop<br>');	
				echo $form->addHTML('[VARIABILNI_SYMBOL] = var. symbol = číslo objednávky<br>');	
				echo $form->addHTML('[TRASOVANI_ZASILKY] = vygeneruje odkaz na trasování zásilky<br>');	
				echo $form->addHTML('[KONTAKTY_PATA] = kontakty do patičky emailu z obecného nastavení<br> ');	
				
				
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
	$form->required = 'nazev,text_email';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
	
							
			    $data_insert = array(
							    'nazev' => $_POST['nazev'],
								'text_email' => $_POST['text_email'],
								'aktivni' => $_POST['aktivni']
							     );
							          
							     
				$query_insert = Db::insert('objednavky_stavy', $data_insert);
				
				
				//řazení
				$data_update = array('razeni' => $query_insert);
				$where_update = array('id' => $query_insert);
				$query = Db::update('objednavky_stavy', $data_update, $where_update);
                
                
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Stavy objednávek - přidání záznamu: '.strip_tags($_POST['nazev']),
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

			    echo $form->formOpen();
				echo $form->inputText('nazev','nazev','','Název','class="input_req"','',1,0,'');
				echo $form->addHTML('<script type="text/javascript"> aktivujTinyMCE(\'text_email\',300) </script>');
				echo $form->inputTextarea('text_email','text_email','','Text emailu','','',1,0,'');
				echo $form->addHTML('<div class="clear" style="height: 20px;"></div>');	
				echo $form->addHTML('<b>Proměnné do šablon</b><br>');	
				echo $form->addHTML('[CELKOVA_CASTKA] = celková částka za objednávku<br>');	
				echo $form->addHTML('[CISLO_UCTU] = číslo účtu z obecného nastavení pro daný eshop<br>');	
				echo $form->addHTML('[VARIABILNI_SYMBOL] = var. symbol = číslo objednávky<br>');	
				echo $form->addHTML('[TRASOVANI_ZASILKY] = vygeneruje odkaz na trasování zásilky<br>');	
				echo $form->addHTML('[KONTAKTY_PATA] = kontakty do patičky emailu z obecného nastavení<br> ');	
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
	$tabulka = new Vypisy('objednavky_stavy','','','razeni ASC',array('id','nazev','razeni','aktivni'),array('Id','Název','Pořadí','Stav','Akce'),1,1,0);
	echo $tabulka->GenerujVypis();*/
	
	echo '<table border="0" class="ta" cellspacing="0" cellpadding="6">
		  <tr class="tr-header">
		  <th>Id</th>
		  <th>Název</th>
		  <th>Stav</th>
		  <th style="width: 90px;">Akce</th>
		  </tr>
		  <tr><td colspan="4" style="text-align: right;"><a href="./index.php?p=objednavky&pp=stavy-objednavek&action=insert">přidat záznam</a></td></tr>
		  <tbody class="row_position">';

	$data_s = Db::queryAll('SELECT * FROM objednavky_stavy WHERE 1 ORDER BY razeni ASC', array());
	if($data_s !== false ) 
	{
		foreach ($data_s as $row_s) 
		{
			echo '<tr id="'.$row_s['id'].'" '.__TR_BG__.' >
			  <td style="cursor: ns-resize;">'.$row_s['id'].'</td>
			  <td>'.$row_s['nazev'].'</td>
			  <td>';
			  if($row_s['aktivni']=='1'){echo '<span class="r">'.$stav_adm_arr[$row_s['aktivni']].'</span>'; }
			  else{ echo '<span class="b">'.$stav_adm_arr[$row_s['aktivni']].'</span>'; }
			  echo '</td>
			  <td><a href="./index.php?p='.$_GET['p'].'&pp='.$_GET['pp'].'&action=update&id='.$row_s['id'].'">upravit</a></td>
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
		updateOrder(selectedData,'razeni_stavy_obj');
	}

});

</script>
