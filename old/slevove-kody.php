<?php 
// slevové kódy

Admin::adminLogin(4);


// filtr
if(!$_GET['action'])
{
	echo '<fieldset><legend>Filtr</legend>';
	$form = new Form('', '', 'get', 'formular2', 'formular', 'utf-8', '', 0);
	
	echo $form->formOpen();
	echo $form->addHTML('<div style="float: left; width: 70%;">');
	
	echo $form->addHTML('<div style="float: left; width: 40%;">');
	echo $form->inputText('kod','kod',sanitize($_GET['kod']),'Kód','','',0,0,'');
	echo $form->addHTML('</div>');

	
	echo $form->addHTML('<div style="float: left; width: 30%; margin-top: 20px;">');
	echo $form->inputHidden('p','p',sanitize($_GET['p']),'',0);
	echo $form->inputHidden('pp','pp',sanitize($_GET['pp']),'',0);
	echo $form->inputSubmit('submit','submit','Zobrazit','','','');
	echo $form->inputButton('redir','redir','Zobrazit vše','','onclick="self.location.href=\'index.php?p=objednavky&pp=slevove-kody\'"','');
	echo $form->addHTML('</div>');
	echo $form->formClose();

	echo '</fieldset>';
}



if($_GET['action']=='delete')
{
		if($_GET['id'])
		{
			$where_delete = array('id' => $_GET['id']);
			$query = Db::delete('slevove_kody', $where_delete); 
			if($query)
			{
				
				$data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Slevové kódy - smazání záznamu id '.intval($_GET['id']),
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
	$form->required = 'kod,typ,datum_od,datum_do,castka';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				 
					   if($form->errors==false)
					   {
							list($den,$mesic,$rok) = explode(".",$_POST['datum_od']);
							 $hodina = 1;
							 $minuta = 1;
							 $sekunda = 1;
							 $datum_od = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);
							 
							 list($den,$mesic,$rok) = explode(".",$_POST['datum_do']);
							 $hodina = 23;
							 $minuta = 59;
							 $sekunda = 59;
							 $datum_do = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);
							 
							 
							$data_update = array(
							'kod' => $_POST['kod'],
							'typ' => $_POST['typ'],
							'opakovane_pouziti' => intval($_POST['opakovane_pouziti']),
							'datum_od' => $datum_od,
							'datum_do' => $datum_do,
							'castka' => $_POST['castka'],
							'aktivni' => $_POST['aktivni']
							);
							
							$where_update = array('id' => $_POST['id']);
							$query = Db::update('slevove_kody', $data_update, $where_update);
			      
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Slevové kódy - úprava záznamu id '.intval($_POST['id']),
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
			$data = Db::queryRow('SELECT * FROM slevove_kody WHERE id = ?', array($_GET['id']));
			
			if($data)
			{	
				$datum_od = date("d.m.Y",$data['datum_od']);
				$datum_do = date("d.m.Y",$data['datum_do']);

				echo $form->formOpen();		
				echo $form->inputText('kod','kod',$data['kod'],'Kód','class="input_req"','',1,0,'');
				echo $form->inputSelect('typ','typ',$data['typ'],$typ_sl_kod_arr,'Typ','',1,0,'');
				echo $form->inputSelect('opakovane_pouziti','opakovane_pouziti',$data['opakovane_pouziti'],$stav_adm_sluzby_arr,'Opakované použití','',0,0,'');
				echo $form->inputText('castka','castka',$data['castka'],'Hodnota','class="input_req"','',1,0,'u částky se zadává hodnota bez DPH');
				echo $form->inputText('datum_od','datepicker_od',$datum_od,'Platnost od','','',1,0,'');
				echo $form->inputText('datum_do','datepicker_do',$datum_do,'Platnost do','','',1,0,'');
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
	$form->required = 'kod,typ,datum_od,datum_do,castka';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
	
				list($den,$mesic,$rok) = explode(".",$_POST['datum_od']);
				$hodina = 1;
				$minuta = 1;
				$sekunda = 1;
				$datum_od = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);
				 
				list($den,$mesic,$rok) = explode(".",$_POST['datum_do']);
				$hodina = 23;
				$minuta = 59;
				$sekunda = 59;
				$datum_do = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);
							 			
			    $data_insert = array(
							   'kod' => $_POST['kod'],
								'typ' => $_POST['typ'],
								'opakovane_pouziti' => intval($_POST['opakovane_pouziti']),
								'datum_od' => $datum_od,
								'datum_do' => $datum_do,
								'castka' => $_POST['castka'],
								'aktivni' => $_POST['aktivni']
							     );
							          
							     
				$query_insert = Db::insert('slevove_kody', $data_insert);
				
	
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Slevové kódy - přidání záznamu: '.strip_tags($_POST['nazev']),
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
				$datum_od = date("d.m.Y");
				$datum_do = date("d.m.Y");
				
			    echo $form->formOpen();
				echo $form->inputText('kod','kod','','Kód','class="input_req"','',1,0,'');
				echo $form->inputSelect('typ','typ','',$typ_sl_kod_arr,'Typ','',1,0,'');
				echo $form->inputSelect('opakovane_pouziti','opakovane_pouziti',1,$stav_adm_sluzby_arr,'Opakované použití','',0,0,'');
				echo $form->inputText('castka','castka','','Hodnota','class="input_req"','',1,0,'u částky se zadává hodnota bez DPH');
				echo $form->inputText('datum_od','datepicker_od',$datum_od,'Platnost od','','',1,0,'');
				echo $form->inputText('datum_do','datepicker_do',$datum_do,'Platnost do','','',1,0,'');
				echo $form->inputSelect('aktivni','aktivni',1,$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
	}
}
else
{
	// vypis vsech
	$sql_podminka_datum = '';
	
	if($_GET['kod'])
	{		
		$sql_podminka_kod .= ' AND SK.kod LIKE "%'.sanitize($_GET['kod']).'%" ';
	}

	
	$tabulka = new Vypisy('slevove_kody','','SELECT SK.* FROM slevove_kody SK WHERE 1 '.$sql_podminka_kod,'',array('SK.id','SK.kod','SK.typ','SK.opakovane_pouziti','SK.castka','SK.datum_od','SK.datum_do','SK.aktivni'),
	array('Id','Kód','Typ','Opakované použití','Hodnota','Plastnost od','Platnost do','Stav','Akce'),1,1,1);
	echo $tabulka->GenerujVypis();
}

?>
 <script type="text/javascript">
	 
 $(document).ready(function () {
    $("#formular").validate({
       
    });
});

</script>
