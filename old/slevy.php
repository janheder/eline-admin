<?php 
// hromadné slevy

Admin::adminLogin(5);



if($_GET['action']=='insert')
{
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 0);
	$form->required = 'nazev,datum_od,datum_do';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
				 $kategorie = '';
				 $vyrobce = '';
				 $sql_slevy = '';
				 $pr_id_arr = array();
				 
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
				
				if($_POST['id_kat'])
				{
				    $data_k = Db::queryRow('SELECT nazev FROM kategorie WHERE id = ? ', array($_POST['id_kat']));
					if($data_k) 
					{
						$kategorie = $data_k['nazev'];
						$sql_slevy .= " AND P.id_kat_arr LIKE '%\"".intval($_POST['id_kat'])."\"%' ";
					}
							
				}
				
				if($_POST['id_vyrobce'])
				{
				    $data_v = Db::queryRow('SELECT vyrobce FROM produkty_vyrobci WHERE id = ? ', array($_POST['id_vyrobce']));
					if($data_v) 
					{
						$vyrobce = $data_v['vyrobce'];
						$sql_slevy .= " AND P.id_vyrobce=".$_POST['id_vyrobce']." ";
					}
							
				}


				// získáme id produktů
				$data_p = Db::queryAll("SELECT P.id FROM produkty P WHERE 1 ".$sql_slevy." ORDER BY P.id ASC", array());
				if($data_p !== false ) 
				{
				    foreach ($data_p as $row_p) 
					{
					    $pr_id_arr[] = $row_p['id'];
					}
					

				}
				
				// nastavení slev u variant produktů dle podmínek
				if(count($pr_id_arr)>0)
				{
					$data_v_update = Db::updateS('UPDATE produkty_varianty SET 
					sleva_datum_od='.$datum_od.',
					sleva_datum_do='.$datum_do.',
					sleva='.intval($_POST['sleva']).'
					 WHERE id_produkt IN('.implode(",",$pr_id_arr).') ');
					 
					 
				}
				
				$data_insert = array(
							    'nazev' => $_POST['nazev'],
								'sleva' => intval($_POST['sleva']),
								'kategorie' => $kategorie,
							    'vyrobce' => $vyrobce,
							    'datum_od' => $datum_od,
							    'datum_do' => $datum_do,
							    'datum' => time()
							     );
							          
							     
				$query_insert = Db::insert('produkty_hromadne_slevy', $data_insert);
 
                
                
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Hromadné slevy - přidání záznamu: '.strip_tags($_POST['nazev']).' sleva: '.$_POST['sleva'],
							    'url' => sanitize($_SERVER['HTTP_REFERER']),
							    'ip' => sanitize(getip()),
							    'datum' => time()
							     );
					     
	            $query_insert_log = Db::insert('admin_log_udalosti', $data_insert_log);
				
				echo '<div class="alert-success">Uloženo!<br>Sleva aplikována na '.$data_v_update.' variant<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
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
				// výrobce
				$data_vyr = Db::queryAll('SELECT id,vyrobce FROM produkty_vyrobci WHERE aktivni=1 ORDER BY vyrobce ASC', array());
				$vyr_arr = array();
				if($data_vyr !== false ) 
				{
					$vyr_arr[0] = 'vyberte';	
					 
				    foreach ($data_vyr as $row_vyr) 
					{
					  $vyr_arr[$row_vyr['id']] = $row_vyr['vyrobce'];	
					}
				}
				else
				{
					$vyr_arr = false;
				}
				
			    echo $form->formOpen();
				echo $form->inputText('nazev','nazev','','Název','class="input_req"','',1,0,'');
				echo $form->inputText('sleva','sleva','','Sleva','','',0,0,'%');
				echo $form->inputText('datum_od','datepicker_od','','Datum od','','',1,0,'');
				echo $form->inputText('datum_do','datepicker_do','','Datum do','','',1,0,'');
				
				// kategorie
				$data_kat_dp_1 = Db::queryAll('SELECT id, nazev FROM kategorie WHERE aktivni=1 AND vnor=1 ORDER BY razeni ASC', array());
				if($data_kat_dp_1 !== false ) 
				{
					$kat_dop_pr_arr[0] = 'vyberte';
					
					foreach ($data_kat_dp_1 as $row_kat_dp_1) 
					{		
							$kat_dop_pr_arr[$row_kat_dp_1['id']] = $row_kat_dp_1['nazev'];	
						
						    $data_kat_dp_2 = Db::queryAll('SELECT id, nazev FROM kategorie WHERE aktivni=1 AND vnor=2 AND id_nadrazeneho='.$row_kat_dp_1['id'].' ORDER BY razeni ASC', array());
							foreach ($data_kat_dp_2 as $row_kat_dp_2) 
							{	
									$kat_dop_pr_arr[$row_kat_dp_2['id']] = '&nbsp;&nbsp;&nbsp;'.$row_kat_dp_2['nazev'];	
									
									$data_kat_dp_3 = Db::queryAll('SELECT id, nazev FROM kategorie WHERE aktivni=1 AND vnor=3 AND id_nadrazeneho='.$row_kat_dp_2['id'].' ORDER BY razeni ASC', array());
									foreach ($data_kat_dp_3 as $row_kat_dp_3) 
									{
										
										$kat_dop_pr_arr[$row_kat_dp_3['id']] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row_kat_dp_3['nazev'];	
										
									}
								
							}
					   
					}
					

					echo $form->inputSelect('id_kat','id_kat','',$kat_dop_pr_arr,'Kategorie','',0,0,'');

					
				}
				echo $form->inputSelect('id_vyrobce','id_vyrobce','',$vyr_arr,'Výrobce','',0,0,'');
				
				
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
	}
}
else
{
	// vypis vsech

	$tabulka = new Vypisy('produkty_hromadne_slevy','','','',array('id','nazev','sleva','kategorie','vyrobce','datum_od','datum_do','datum'),
	array('Id','Název','Sleva v %','Kategorie','Výrobce','Datum platnosti od','Datum platnosti od','Datum vytvoření','Akce'),0,1,0);
	echo $tabulka->GenerujVypis();
	
	

	
	
}

?>
 <script type="text/javascript">
	 
 $(document).ready(function () {
    $("#formular").validate({
       
    });
});


 

</script>
