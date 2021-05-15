<?php 
// bannery

Admin::adminLogin(4);

 

if($_GET['action']=='delete')
{
		if($_GET['id'])
		{
			$where_delete = array('id' => $_GET['id']);
			$query = Db::delete('bannery', $where_delete); 
			if($query)
			{
				
				$data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Bannery - smazání záznamu id '.intval($_GET['id']),
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
	
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 1);
	$form->required = 'nazev';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				 
					   if($form->errors==false)
					   {

							if($_FILES['banner']['size'])
							{
								$banner = StringUtils::removeAccents($_FILES['banner']['name']);
								move_uploaded_file($_FILES['banner']['tmp_name'], '../prilohy/b/'.$banner);
							}
							else
							{
								$banner = '';
							}

							$data_update = array(
								'nazev' => $_POST['nazev'],
								'url' => $_POST['url'],
								'aktivni' => $_POST['aktivni'],
								
							);
							
							
							if($banner)
							{
								$data_update['banner'] = $banner;
							}

						    
							$where_update = array('id' => $_POST['id']);
							$query = Db::update('bannery', $data_update, $where_update);
			      
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Bannery - úprava záznamu id '.intval($_POST['id']),
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
			$data = Db::queryRow('SELECT * FROM bannery WHERE id = ?', array($_GET['id']));
			
			if($data)
			{

				if($data['banner'])
				{
					$html_foto = '<div style="clear: right;">
					<img src="/prilohy/b/'.$data['banner'].'" style="width: 350px;">
					</div>';
				}
				else
				{
					$html_foto = '';
				}
				

				echo $form->formOpen();
				echo $form->inputText('nazev','nazev',$data['nazev'],'Název','class="input_req"','',1,0,'');
				echo $form->inputText('url','url',$data['url'],'URL','','',0,0,'');
				echo $form->inputFile('banner','banner','','Banner','',0,0,'jpg, png, gif, webp'.$html_foto);
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
elseif($_GET['action']=='insert')
{
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 1);
	$form->required = 'nazev,banner';
	
	if($form->submit())
	{
		 
		
			if($form->errors==false)
		    {
				 			
				if($_FILES['banner']['size'])
				{
					$banner = StringUtils::removeAccents($_FILES['banner']['name']);
					move_uploaded_file($_FILES['banner']['tmp_name'], '../prilohy/b/'.$banner);
				}
				else
				{
					$banner = '';
				}

	
			    $data_insert = array(
								'banner' => $banner,
								'nazev' => $_POST['nazev'],
								'url' => $_POST['url'],
								'datum' => time(),
								'aktivni' => $_POST['aktivni']
							     );
							     
	     
				$query_insert = Db::insert('bannery', $data_insert);
                
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Bannery - přidání záznamu: '.strip_tags($_POST['nazev']),
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
				echo $form->inputText('url','url','','URL','','',0,0,'');
				echo $form->inputFile('banner','banner','','Banner','',1,0,'jpg, png, gif, webp');
				echo $form->inputSelect('aktivni','aktivni',1,$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputSubmit('submit','checkBtn','Uložit','','','');
				echo $form->formClose();
	}
}
else
{ 
	
	$tabulka = new Vypisy('bannery','','','',array('id','nazev','url','banner','aktivni'),array('Id','Název','URL','Banner','Stav','Akce'),1,1,1);
	echo $tabulka->GenerujVypis();
}



?>
<script type="text/javascript">
	 
 $(document).ready(function () {
    $("#formular").validate({
       
    });
});

</script>
