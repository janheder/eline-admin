<?php 
// admin
Admin::adminLogin(5);

//var_dump($_SERVER['HTTP_REFERER']);
//var_dump($_SERVER['TMP']);
//var_dump(session_id());
$aktual_url = 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];


if($_GET['action']=='delete-sess' && $_GET['ids'] && ($_SESSION['admin_referer'] != $aktual_url))
{
		unlink($_SERVER['TMP'].'/sess_'.sanitize($_GET['ids']));
}
elseif($_GET['action']=='update')
{
	
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 1);
	$form->required = 'uz_jm,email,prava';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				 
					   if($form->errors==false)
					   {
						   
							
							$data_update = array('uz_jm' => $_POST['uz_jm'],'email' => $_POST['email'],'prava' => $_POST['prava'],'aktivni' => $_POST['aktivni']);
							
						    // změnil heslo
						    if($_POST['heslo'])
						    {
								$data_update['heslo'] = hash('sha256',$_POST['heslo']);
							}


		
							$where_update = array('id' => $_POST['id']);
							$query = Db::update('admin', $data_update, $where_update);
			      
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Admin - úprava záznamu id '.intval($_POST['id']),
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
			$data = Db::queryRow('SELECT * FROM admin WHERE id = ?', array($_GET['id']));
			if($data)
			{
				

				echo $form->formOpen();
				echo $form->inputText('uz_jm','uz_jm',$data['uz_jm'],'Uživatelské jméno','class="input_req" autocomplete="new-password"','',1,0,'');
				echo $form->inputText('heslo','heslo','','Heslo','autocomplete="new-password"','',0,0,'pokud nezadáte zůstane původní');
				echo $form->inputEmail('email','email',stripslashes($data['email']),'Email','class="input_req"','',1,0,'');
				echo $form->inputSelect('prava','prava',intval($data['prava']),$prava_adm_arr,'Práva','',0,0,'');

				echo $form->addHTML('<div class="clear" style="height: 20px;"></div>');
				echo $form->inputSelect('aktivni','aktivni',intval($data['aktivni']),$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputHidden('id','id',intval($_GET['id']),'',0);
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
			}
			else
			{
				echo '<div class="alert-warning">Chyba!<br>Žádný záznam v databázi pro id '.strip_tags($_GET['id']).'<br><a href="./index.php?p=admin">Přejít na přehled</a></div>';
			}
		    
			
		}
		else
		{
			echo '<div class="alert-warning">Chyba!<br>Chybí ID záznamu<br><a href="./index.php??p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
		}
		    
			
	}
	


}
elseif($_GET['action']=='insert')
{
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 1);
	$form->required = 'uz_jm,email,heslo';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
				

							
			    $data_insert = array(
							    'uz_jm' => $_POST['uz_jm'],
							    'email' => $_POST['email'],
							    'heslo' => hash('sha256',$_POST['heslo']),
							    'prava' =>  $_POST['prava'],
							    'aktivni' => $_POST['aktivni'],
							    'datum' => time()
							     );
		     
				$query_insert = Db::insert('admin', $data_insert);
                
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Admin - přidání záznamu: '.strip_tags($_POST['uz_jm']),
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
				echo $form->inputText('uz_jm','uz_jm','','Uživatelské jméno','class="input_req" autocomplete="new-password"','',1,0,'');
				echo $form->inputText('heslo','heslo','','Heslo','class="input_req"  autocomplete="new-password"','',1,0,'<span id="pass_strenght" class="registerForm__inputGroup">
				<span class="progress"><span id="StrengthProgressBar" class="progress-bar"></span></span></span>');
				/*
				echo $form->inputPassword('heslo','heslo','','Heslo','class="input_req" autocomplete="new-password"',1,'<span id="pass_strenght" class="registerForm__inputGroup">
				<span class="progress"><span id="StrengthProgressBar" class="progress-bar"></span></span></span>');
				*/

				echo $form->inputEmail('email','email','','Email','class="input_req"','',1,0,'');
				echo $form->inputSelect('prava','prava','',$prava_adm_arr,'Práva','',0,0,'');
				echo $form->addHTML('<div class="clear"></div>');
				echo $form->inputSelect('aktivni','aktivni',1,$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
	}
}





if($_GET['action']!='update' && $_GET['action']!='insert')
{
echo '<table border="0" class="ta" cellspacing="0" cellpadding="6">
<tr class="tr-header">
<td>Uživatelské jméno</td>
<td>Práva</td>
<td>Email</td>
<td>Datum registrace</td>
<td>Datum posl. přihlášení</td>
<td>IP posl. přihlášení</td>
<td>Platnost sešny</td>
<td>Stav</td>
<td>Akce</td>
</tr>
<tr><td colspan="9"  style="text-align: right;"><a href="./index.php?p=admin&action=insert">přidat záznam</a></td></tr>';



$data = Db::queryAll('SELECT * FROM admin ORDER BY id ASC', array());
if($data !== FALSE) 
{
	foreach ($data as $row) 
	{
		
		echo '<tr '.__TR_BG__.'>
		<td>'.$row['uz_jm'].'</td>
		<td>'.$prava_adm_arr[$row['prava']].'</td>
		<td>'.$row['email'].'</td>
		<td>'.date('d.m.Y',$row['datum']).'</td>
		<td>';
		if($row['datum_posledniho_prihlaseni']){ echo date('d.m.Y',$row['datum_posledniho_prihlaseni']);}
		echo '</td>
		<td>'.$row['ip_posledniho_prihlaseni'].'</td>
		<td>';
		if($row['sess_id'])
		{ 
			echo Admin::platnostSesny($row['sess_id']);
		}
		echo '</td>
		<td>';
		if($row['aktivni']=='1'){echo '<span class="r">'.$_SESSION['stav_adm_arr'][$row['aktivni']].'</span>'; }
		else{echo '<span class="b">'.$_SESSION['stav_adm_arr'][$row['aktivni']].'</span>'; }
		echo '</td>
		<td><a href="index.php?p=admin&action=update&id='.$row['id'].'">upravit</a></td>
		</tr>';
	
    }
}


echo '</table>';
}
 

?>
<script>

	 if($('#StrengthProgressBar').length)
	 {

		$('#StrengthProgressBar').zxcvbnProgressBar({ passwordInput: '#heslo' });

	 }

</script>