<?php 
// sazby-dph

Admin::adminLogin(4);




if($_GET['action']=='update')
{
	
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 0);
	$form->required = 'dph';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				 
					   if($form->errors==false)
					   {

							$data_update = array(
							'dph' => $_POST['dph']
							);
							$where_update = array('id' => $_POST['id']);
							$query = Db::update('dph', $data_update, $where_update);
			      
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Sazby DPH - úprava záznamu id '.intval($_POST['id']),
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
			$data = Db::queryRow('SELECT * FROM dph WHERE id = ?', array($_GET['id']));
			
			if($data)
			{


				echo $form->formOpen();
				echo $form->inputText('dph','dph',$data['dph'],'Sazba DPH','class="input_req"','',1,0,'');
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
	$form->required = 'dph';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
	
							
			    $data_insert = array(
							    'dph' => $_POST['dph']
							     );
							          
							     
				$query_insert = Db::insert('dph', $data_insert);

		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Sazby DPH - přidání záznamu: '.strip_tags($_POST['dph']),
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
				echo $form->inputText('dph','dph','','Sazba DPH','class="input_req"','',1,0,'');
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
	}
}
else
{
	// vypis vsech
	

	
	$tabulka = new Vypisy('dph','','','',array('id','dph'),array('Id','Výše DPH','Akce'),1,1,0);
	echo $tabulka->GenerujVypis();
}

?>
 <script type="text/javascript">
	 
 $(document).ready(function () {
    $("#formular").validate({
       
    });
});

</script>
