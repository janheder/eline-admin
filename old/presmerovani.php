<?php 
// presmerovani

Admin::adminLogin(5);

// filtr
if(!$_GET['action'])
{
	echo '<fieldset><legend>Filtr</legend>';
	$form = new Form('', '', 'get', 'formular2', 'formular', 'utf-8', '', 0);
	
	echo $form->formOpen();
	echo $form->addHTML('<div style="float: left; width: 40%;">');
	echo $form->inputText('puvodni_url','puvodni_url',sanitize($_GET['puvodni_url']),'Původní URL','','',0,0,'');
	echo $form->addHTML('</div>');
	echo $form->addHTML('<div style="float: left; width: 45%; margin-top: 20px;">');
	echo $form->inputHidden('p','p',sanitize($_GET['p']),'',0);
	echo $form->inputHidden('pp','pp',sanitize($_GET['pp']),'',0);
	echo $form->inputSubmit('submit','submit','Zobrazit','','','');
	echo $form->inputButton('redir','redir','Zobrazit vše','','onclick="self.location.href=\'index.php?p=presmerovani\'"','');
	echo $form->addHTML('</div>');
	echo $form->formClose();

	echo '</fieldset>';
}


if($_GET['action']=='delete')
{
		if($_GET['id'])
		{
			$where_delete = array('id' => $_GET['id']);
			$query = Db::delete('presmerovani', $where_delete); 
			if($query)
			{
				
				$data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Přesměrování - smazání záznamu id '.intval($_GET['id']),
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
elseif($_GET['action']=='update')
{
	
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 0);
	$form->required = 'puvodni_url,nova_url';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				 
					   if($form->errors==false)
					   {

							$data_update = array('puvodni_url' => $_POST['puvodni_url'],'nova_url' => $_POST['nova_url'],'datum' => time(),'aktivni' => $_POST['aktivni']);

		
							$where_update = array('id' => $_POST['id']);
							$query = Db::update('presmerovani', $data_update, $where_update);
			      
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Přesměrování - úprava záznamu id '.intval($_POST['id']),
							    'url' => sanitize($_SERVER['HTTP_REFERER']),
							    'ip' => sanitize(getip()),
							    'datum' => time()
							     );
					     
							  $query_insert = Db::insert('admin_log_udalosti', $data_insert);
							
							echo '<div class="alert-success">Uloženo!<br>Záznam byl v pořádku změněn<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a>
							<br>
							<a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'&action=update&id='.intval($_GET['id']).'">Znovu upravit</a></div>';
					   }
					   else
					   {
						   echo '<div class="alert-warning">Chyba!<br>Nejsou vyplněny všechny povinné položky<br>';
						   foreach($form->errors as $ke=>$ve){ echo $ve.'<br>'; }
						   echo '<a href="'.$_SERVER['HTTP_REFERER'].'">Přejít zpět</a><br>
						   <a href="./index.php?p=presmerovani">Přejít na přehled</a></div>';
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
			$data = Db::queryRow('SELECT * FROM presmerovani WHERE id = ?', array($_GET['id']));
			
			if($data)
			{


				echo $form->formOpen();
				echo $form->inputText('puvodni_url','puvodni_url',$data['puvodni_url'],'Původní URL','class="input_req"','',1,0,'');
				echo $form->inputText('nova_url','nova_url',$data['nova_url'],'Nová URL','class="input_req"','',1,0,'');
				echo $form->inputSelect('aktivni','aktivni',intval($data['aktivni']),$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputHidden('id','id',intval($_GET['id']),'',0);
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
			}
			else
			{
				echo '<div class="alert-warning">Chyba!<br>Žádný záznam v databázi pro id '.strip_tags($_GET['id']).'<br><a href="./index.php?p=presmerovani">Přejít na přehled</a></div>';
			}
		    
			
		}
		else
		{
			echo '<div class="alert-warning">Chyba!<br>Chybí ID záznamu<br><a href="./index.php?p=presmerovani">Přejít na přehled</a></div>';
		}
		    
			
	}
	


}
elseif($_GET['action']=='insert')
{
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 0);
	$form->required = 'puvodni_url,nova_url';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
	
							
			    $data_insert = array(
							    'puvodni_url' => $_POST['puvodni_url'],
							    'nova_url' => $_POST['nova_url'],
							    'aktivni' => $_POST['aktivni'],
							    'datum' => time()
							     );
							          
							     
				$query_insert = Db::insert('presmerovani', $data_insert);
                
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Přesměrování - přidání záznamu: '.strip_tags($_POST['nova_url']),
							    'url' => sanitize($_SERVER['HTTP_REFERER']),
							    'ip' => sanitize(getip()),
							    'datum' => time()
							     );
					     
	            $query_insert_log = Db::insert('admin_log_udalosti', $data_insert_log);
				
				echo '<div class="alert-success">Uloženo!<br>Záznam byl v pořádku uložen<br><a href="./index.php?p=presmerovani">Přejít na přehled</a></div>';
		    }
		    else
		    {
			   echo '<div class="alert-warning">Chyba!<br>Nejsou vyplněny všechny povinné položky<br>';
			   foreach($form->errors as $ke=>$ve){ echo $ve.'<br>'; }
			   echo '<a href="'.$_SERVER['HTTP_REFERER'].'">Přejít zpět</a><br>
			   <a href="./index.php?p=presmerovani">Přejít na přehled</a></div>';
		    }
	}
	else
	{
			    echo $form->formOpen();
				echo $form->inputText('puvodni_url','puvodni_url','','Původní URL','class="input_req"','',1,0,'');
				echo $form->inputText('nova_url','nova_url','','Nová URL','class="input_req"','',1,0,'');
				echo $form->inputSelect('aktivni','aktivni',1,$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
	}
}
else
{
	// vypis vsech
	
	if($_GET['puvodni_url'])
	{
		
		$sql_podminka = ' AND P.puvodni_url LIKE "%'.sanitize($_GET['puvodni_url']).'%" ';
		
		
	}
	else
	{
		$sql_podminka = '';
	}
	
	$tabulka = new Vypisy('presmerovani','','SELECT P.* FROM presmerovani P WHERE 1 '.$sql_podminka,'',array('P.id','P.puvodni_url','P.nova_url','P.datum','P.aktivni'),array('Id','Původní URL','Nová URL','Datum vytvoření','Stav','Akce'),1,1,1);
	echo $tabulka->GenerujVypis();
}

?>
 <script type="text/javascript">
	 
 $(document).ready(function () {
    $("#formular").validate({
       
    });
});

</script>
