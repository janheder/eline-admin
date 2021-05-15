<?php 
// šablony

Admin::adminLogin(4);




if($_GET['action']=='update')
{
	
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 0);
	$form->required = 'nazev,parametry_arr';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				 
					   if($form->errors==false)
					   {
							if($_POST['parametry_arr'])
							{
							  $parametry_arr = serialize($_POST['parametry_arr']);
							}
				
							$data_update = array(
							'nazev' => $_POST['nazev'],
							'parametry_arr' => $parametry_arr,
							'aktivni' => $_POST['aktivni']
							);
							$where_update = array('id' => $_POST['id']);
							$query = Db::update('sablony_tech_par', $data_update, $where_update);
			      
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Šablony tech. par. - úprava záznamu id '.intval($_POST['id']),
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
			$data = Db::queryRow('SELECT * FROM sablony_tech_par WHERE id = ?', array($_GET['id']));
			
			if($data)
			{

				echo $form->formOpen();
				echo $form->inputText('nazev','nazev',$data['nazev'],'Název šablony','class="input_req"','',1,0,'');
				echo $form->addHTML('<table id="obj_table">');
				// pole parametrů
				if($data['parametry_arr'])
				{
					  $parametry_unserialize = unserialize($data['parametry_arr']);
					  foreach( $parametry_unserialize as $par_k=>$par_v)
					  {
					    
					    echo '<tr><td><input type="text" name="parametry_arr[]" id="parametry_arr" value="'.$par_v.'" class="input_req" required  ></td></tr>';
	
					  }
					  
					    echo '<tr><td> <a href="javascript:sablona_polozky();">přidat další položku</a></td></tr>';
				}
				echo $form->addHTML('</table>');
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
	$form->required = 'nazev,parametry_arr';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
				
				if($_POST['parametry_arr'])
				{
				  $parametry_arr = serialize($_POST['parametry_arr']);
				}
							
			    $data_insert = array(
							    'nazev' => $_POST['nazev'],
							    'parametry_arr' => $parametry_arr,
							    'aktivni' => $_POST['aktivni']
							     );
							          
							     
				$query_insert = Db::insert('sablony_tech_par', $data_insert);
				

                
                
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Šablony tech. par. - přidání záznamu: '.strip_tags($_POST['nazev']),
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
				echo $form->inputText('nazev','nazev','','Název šablony','class="input_req"','',1,0,'');
				echo $form->addHTML('<table id="obj_table"><tr><td>');
				echo $form->inputText('parametry_arr[]','parametry_arr','','Parametry','class="input_req"','',1,0,'<br><a href="javascript:sablona_polozky();">přidat další položku</a>');
				echo $form->addHTML('</tr></td>');
				echo $form->addHTML('</table>');
				echo $form->inputSelect('aktivni','aktivni',1,$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
	}
}
else
{
	// vypis vsech
	

	
	$tabulka = new Vypisy('sablony_tech_par','','','',array('id','nazev','aktivni'),array('Id','Šablona','Stav','Akce'),1,1,0);
	echo $tabulka->GenerujVypis();
}

?>
 <script type="text/javascript">
	 
 $(document).ready(function () {
    $("#formular").validate({
       
    });
});

</script>
